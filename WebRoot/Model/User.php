<?php

class User extends UserBase{
	
	private $email;
	
	public function getEmail(){
		return $email;
	}
	
	public static function create(string $username, string $email, string $password) : ?User {
		
	}
	
	public static function create(UserTemp $user, string $email, string $password) : ?User {
		
	}
	
	private static function create(int $id, string $username, string $email, string $password) : ?User {
		
	}
	
	public function getAccountType() : int {
		return UserBase::ACCOUNT_NORMAL;
	}
}