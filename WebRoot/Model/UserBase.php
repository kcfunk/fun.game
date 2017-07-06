<?php

abstract class UserBase{
	protected $screen_name;
	protected $user_id;
	protected $game_history;
	
	//Different possible types of accounts
	public const ACCOUNT_ADMIN = 3;
	public const ACCOUNT_NORMAL = 2;
	public const ACCOUNT_TEMP = 1;
	
	public function getScreenName() : string{
		return $screen_name;
	}
	
	public function getUserId() : int{
		return $user_id;
	}
	
	public abstract function getAccountType() : int;
	
	public function setScreenName(string $new_name){
		$screen_name = $new_name;
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
}