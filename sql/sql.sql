CREATE DATABASE cl_framework;
USE cl_framework;

CREATE TABLE user (
	id int primary key auto_increment,
	nama varchar(100),
	email varchar(100) unique,
	hp varchar(30),
	username varchar(20) unique,
	password varchar(128),
	role enum("admin","user")
);

INSERT INTO user (nama, email, hp, username, password, role) VALUES ("Ratno Putro Sulistiyono [Admin]","ratno@comlabs.itb.ac.id","0817201101","ratno","ratno","admin");
INSERT INTO user (nama, email, username, password, role) VALUES ("Ratno [User]","ratno@knoqdown.com","user","user","user");