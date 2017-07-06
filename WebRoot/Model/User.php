<?php

class User extends UserBase{
	
	private $email;
	private $is_admin;
	
	public function getEmail(){
		return $email;
	}
	
	public static function create(string $username, string $email, string $password) : ?User {
		
	}
	
	public function getAccountType() : int {
		return $is_admin ? UserBase::ACCOUNT_ADMIN : UserBase::ACCOUNT_NORMAL;
	}
}