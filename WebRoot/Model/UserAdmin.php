<?php

class UserAdmin extends User{
	
	private $admin_level;
	
	public function getAccountType() : int {
		return UserBase::ACCOUNT_ADMIN;
	}
	
	public int getAdminLevel() : int {
		return $admin_level;
	}
}