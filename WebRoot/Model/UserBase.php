<?php

abstract class UserBase{
	protected $screen_name;
	protected $user_id;
	
	//Different possible types of accounts
	public const ACCOUNT_ADMIN = 3;
	public const ACCOUNT_NORMAL = 2;
	public const ACCOUNT_TEMP = 1;
	
	public function getScreenName() : string {
		return $screen_name;
	}
	
	public function getUserId() : int {
		return $user_id;
	}
	
	public abstract function getAccountType() : int;
	
	public function setScreenName(string $new_name) {
		$screen_name = $new_name;
		
		$pdo = Database::connect();
		
		$sql = "UPDATE `user` SET `screen_name` = '$screen_name' WHERE `id`=$id";
		
		$statement = $pdo->prepare($sql);
		if(!$statement->execute()){
			Logger::error("Failed to change screen name for user $id: ".$statement->errorInfo()[2],
						  "Could not change screen name, please try again.");
		}
	}
	
	public function delete() {
		UserBase::removeUser($id);
	}
	
	public static function isSignedIn() : bool {
		return isset($_SESSION['user']);
	}
	
	public static function get() : ?UserBase {
		if(isSignedIn()){
			return $_SESSION['user'];
		}else{
			return null;
		}
	}
	
	public static function isAuthorized(int $level) : bool {
		$user = UserBase::get();
		if($user == null){
			return false;
		}else{
			return $user->account_type >= $level;
		}
	}
	
	public static function allocateUserId(string $new_name) : int {
		$pdo = Database::connect();
		
		$sql = "INSERT INTO `user` VALUES (NULL, '$new_name')";
		
		$statement = $pdo->prepare($sql);
		
		if($statement->execute()){
			return $pdo->lastInsertId();
		}else{
			Logger::error("Failed to create new user: ".$statement->errorInfo()[2],
						  "Could not register new screen name, please try again.");
		}
	}
	
	public static function removeUser(int $id) {
		$pdo = Database::connect();
		
		$sql = "DELETE FROM `user` WHERE `id`=$id";
		
		$statement = $pdo->prepare($sql);
		
		if($statement->execute()){
			return $pdo->lastInsertId();
		}else{
			Logger::error("Failed to delete user: ID:$id ".$statement->errorInfo()[2],
						  "Could not remove user, please try again.");
		}
	}
}