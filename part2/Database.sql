DROP DATABASE IF EXISTS Learn_City;

CREATE DATABASE Learn_City;

USE Learn_City;

CREATE TABLE users
(
	Username VARCHAR(50),
	Password VARCHAR(50),
	Email VARCHAR(64),
	PRIMARY KEY (Username)
);

CREATE TABLE courses
(
	CourseId int NOT NULL AUTO_INCREMENT,
	Username VARCHAR(50),
	Name VARCHAR(100),
	PRIMARY KEY (CourseId, Username, Name),
	FOREIGN KEY (Username) REFERENCES users(Username)
);

CREATE TABLE units
(
	CourseId int,
	Unit_number int,
	Unit_title VARCHAR(100),
	PRIMARY KEY (CourseId, Unit_number),
	FOREIGN KEY (CourseId) REFERENCES courses(CourseId)
);

CREATE TABLE sections
(
	CourseId int,
	Unit_number int,
	Section_number int,
	Section_title VARCHAR(100),
	PRIMARY KEY (CourseId, Unit_number, Section_number),
	FOREIGN KEY (CourseId, Unit_number) REFERENCES units(CourseId, Unit_number)
);


CREATE TABLE paragraph
(
	CourseId int,
	Unit_number int,
	Section_number int,
	Paragraph_number INT,
	Paragraph VARCHAR(2000),
	PRIMARY KEY (CourseId, Unit_number, Section_number, Paragraph_number),
	FOREIGN KEY (CourseId, Unit_number, Section_number) REFERENCES sections(CourseId, Unit_number, Section_number)
);

CREATE TABLE quizes
(
	CourseId int,
	Unit_number int,
	Question_number int,
	Inquiry VARCHAR(100),
	Answer1 VARCHAR(200),
	Answer2 VARCHAR(200),
	Answer3 VARCHAR(200),
	Answer4 VARCHAR(200),
	AnswerNum INT,
	PRIMARY KEY (CourseId, Unit_number, Question_number),
	FOREIGN KEY (CourseId, Unit_number) REFERENCES units(CourseId, Unit_number)
);