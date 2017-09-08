<?php

class Database{
	
	//Database connection data:
	const DB_NAME = "game_db";
	const DB_USER = "game_db_user";
	const DB_PASS = "game_db_pass_44561";
	
	//Connects to database based o above information
	public static function connect() : PDO {
		$dsn = "mysql:host=localhost;dbname=" . Database::DB_NAME;
		$pdo = new PDO($dsn, Database::DB_USER, Database::DB_PASS);
		if(!$pdo){
			Logger::error("Error connecting to database");
		}
		return $pdo;
	}
}