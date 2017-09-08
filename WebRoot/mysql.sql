## Database settup: ##

# Create user for access from PHP code
#DELETE USER IF EXISTS 'game_db_user';  #
CREATE USER 'game_db_user'@'localhost' IDENTIFIED BY 'game_db_pass_44561';

# Create new database
DROP DATABASE IF EXISTS `game_db`;
CREATE DATABASE `game_db`;

# Use created database
USE `game_db`;

# Grant user all privileges
GRANT ALL PRIVILEGES ON `game_db` TO 'game_db_user'@'localhost';
FLUSH PRIVILEGES;

## Create tables: ##

# Table for holding the set of user id numbers and screen names to identify them
# This table is for both temporry and permanent user data
DROP TABLE IF EXISTS `temp_user`;
DROP TABLE IF EXISTS `full_user`;
DROP TABLE IF EXISTS `admin`;
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
	`id` INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	`screen_name` VARCHAR(25)
);

# Table to hold data for temporary users
DROP TABLE IF EXISTS `temp_user`;
CREATE TABLE `temp_user` (
	`user_id` INT,
	`ip_address` INT UNSIGNED UNIQUE, # Use INET_ATON() or INET_NTOA() to convert
	`last_active_date` DATE,
	FOREIGN KEY(`user_id`) REFERENCES `user`(`id`) 
	ON DELETE CASCADE ON UPDATE CASCADE
);

# Table to hold data for permanent users
DROP TABLE IF EXISTS `full_user`;
CREATE TABLE `full_user` (
	`user_id` INT,
	`email` VARCHAR(255) UNIQUE,
	`date_registered` DATE,
	FOREIGN KEY(`user_id`) REFERENCES `user`(`id`)
	ON DELETE CASCADE ON UPDATE CASCADE
);

# A list of all admins and their user ID's, as well as their admin status
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
	`user_id` INT,
	`admin_status` ENUM('trial','moderator','full','super','owner'),
	FOREIGN KEY(`user_id`) REFERENCES `user`(`id`)
	ON DELETE CASCADE ON UPDATE CASCADE
);