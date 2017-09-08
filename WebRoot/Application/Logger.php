<?php

class Logger {
	
	// Log a message in the server's log file
	public static function log(string $message, string $level="LOG") {
		$date = "2017-08-30 3:42:55"; //TODO get date as string
		$log_line = "$date $level: $message";
		//TODO: append to a log file
		echo "$log_line<br/>";
	}
	
	// An error occurred, but it needn't be brought to the user's attention
	public static function warn(string $message){
		Logger::log($message, "WARNING");
	}
	
	// An error occurred which would interfere with the user's experience
	// Redirect user to error page which may have a seperate display message free
	//  of the technical details which are logged
	public static function error(string $message, $display = null){
		Logger::log($message, "ERROR");
		//TODO: redirect user to error page
		die($display);
	}
	
	// A potentially devastating failure occurred, notify the user and
	//  possibly shut down the entire server
	public static function critical(string $message, bool $stop = true){
		Logger::log($message, "CRITICAL");
		die("CRITICAL ERROR: ".$message);
		//TODO: possibly shut down server
	}
}