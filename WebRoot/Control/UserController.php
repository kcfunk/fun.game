<?php

class UserController{
	
	public static function test($screen_name){
		$user = UserTemp::findCurrentUser();
		//$user = UserTemp::create($screen_name);
		echo "Registered User: ";
		var_dump($user);
	}
	
	public static function temp(){
		if($_POST){
			
		}else{
			$GLOBALS['content'] = DOCROOT."/View/user/create_temp.phtml";
			include DOCROOT."/View/main.phtml";
		}
	}
}