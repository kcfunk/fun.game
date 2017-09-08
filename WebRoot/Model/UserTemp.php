<?php

class UserTemp extends UserBase{
	
	public const DAYS_TO_EXPIRE = 14;
	
	private $ip_addr;
	private $last_active_date;
	
	public function getIpAddress() : string {
		return $ip_addr;
	}
	
	public function getExpirationDate() : string {
		return $last_active_date;
	}
	
	public function isExpired() : bool {
		//Get interval between current date and last active date
		$interval = date_create()->diff(date_create($last_active_date));
		//Check that the time since last activity is within the expiration date
		return $interval->days > UserTemp::DAYS_TO_EXPIRE;
	}
	
	public function refreshExpiration() {
		$current_date = date_create()->format("Y-m-d");
		if($last_active_date != $current_date){
			$last_active_date = $current_date;
			//Update the database to reflect the new date
			$pdo = Database::connect();
			
			$sql = "UPDATE `temp_user` SET `last_active_date` = '$last_active_date' WHERE `user_id` = $id";
			$statement = $pdo->prepare($sql);
			
			if(!$statement->execute()){
				Logger::error("Failed to update user#$id last active date: ".$statement->errorInfo()[2],
							  "Could not update expiration, please contact admin if this persists.");
			}
		}
	}
	
	public function getAccountType() : int {
		return UserBase::ACCOUNT_TEMP;
	}
	
	public static function create(string $screen_name) : ?UserTemp {
		//Get user's IP address
		//TODO Check that it's not banned
		$ip = $_SERVER['REMOTE_ADDR'];
		//Get date
		$date = date_create()->format("Y-m-d");
		//Allocate a user ID with given name
		$id = UserBase::allocateUserId($screen_name);
		//TODO Create new entry in temp_user table
		$pdo = Database::connect();
		$sql = "INSERT INTO `temp_user` VALUES ($id, INET_ATON('$ip'), '$date')";
		$statement = $pdo->prepare($sql);
		if($statement->execute()){
			$user = new UserTemp();
			$user->screen_name = $screen_name;
			$user->user_id = $id;
			$user->ip_addr = $ip;
			$user->last_active_date = $date;
			return $user;
		}else{
			UserBase::removeUser($id);
			Logger::error("Failed to create new temp user: ".$statement->errorInfo()[2],
						  "Could not register user data, please try again.");
		}
	}
	
	private static function getUserFromObj($user_data) : UserTemp {
		$user = new UserTemp();
		$user->screen_name = $user_data->screen_name;
		$user->user_id = $user_data->id;
		$user->ip_addr = $user_data->ip_address;
		$user->last_active_date = $user_data->last_active_date;
		return $user;
	}
	
	private static function getUserByCondition(string $sql_clause, bool $multi = false) {
		$pdo = Database::connect();
		//Join the user and temp_user tables
		$sql  = "SELECT u.`id`, u.`screen_name`, tu.`ip_address`, tu.`last_active_date` ";
		$sql .= "FROM `user` AS u JOIN `temp_user` AS tu ON u.`id` = tu.`user_id` ";
		$sql .= "WHERE $sql_clause";
		$statement = $pdo->prepare($sql);
		if($statement->execute()){
			$user_data_arr = $statement->fetchAll(PDO::FETCH_OBJ);
			if($multi){
				$users = array();
				foreach($user_data_arr as $user_data){
					$users[] = UserTemp::getUserFromObj($user_data);
				}
				return $users;
			}else if(isset($user_data_arr[0])){
				return UserTemp::getUserFromObj($user_data_arr[0]);
			}else{
				return null;
			}
		}else{
			Logger::error("Failed to create new temp user: ".$statement->errorInfo()[2],
						  "Could not register user data, please try again.");
		}
	}
	
	public static function findCurrentUser() : ?UserTemp{
		//Get user's IP address
		//TODO Check that it's not banned
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$user = UserTemp::getUserByCondition("tu.`ip_address` = INET_ATON('$ip')");
		return $user;
	}
	
	public static function getUserById(int $id) : ?UserTemp {
		return UserTemp::getUserByCondition("tu.`user_id` = $id");
	}
	
	public static function getTempUsers() : array {
		return UserTemp::getUserByCondition("1", true);
	}
	
	public function removeTempData(){
		$pdo = Database::connect();
		
		$sql = "DELETE FROM `temp_user` WHERE `user_id` = $id";
		$statement = $pdo->prepare($sql);
		
		if(!$statement->execute()){
			Logger::error("Failed to remove temp user#$id data: ".$statement->errorInfo()[2],
						  "Could not remove temp user data, please contact administration.");
		}
	}
}