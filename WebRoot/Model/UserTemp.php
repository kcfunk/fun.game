<?php

class UserTemp extends UserBase{
	
	public function getAccountType() : int {
		return UserBase::ACCOUNT_TEMP;
	}
	
	public static function create(string $username) : ?UserTemp {
		
	}
}