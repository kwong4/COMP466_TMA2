DROP DATABASE IF EXISTS Forgetmenot;

CREATE DATABASE Forgetmenot;

USE Forgetmenot;

CREATE TABLE users
(
	Username VARCHAR(50),
	Password VARCHAR(50),
	Email VARCHAR(64),
	PRIMARY KEY (Username)
);

CREATE TABLE bookmarks
(
	Username VARCHAR(50),
	Name VARCHAR(100),
	Url VARCHAR(200),
	PRIMARY KEY (Username, Url),
	FOREIGN KEY (Username) REFERENCES users(Username)
);