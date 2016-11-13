DROP DATABASE chucho;
CREATE DATABASE chucho;
use chucho;

CREATE TABLE verbo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO verbo (cadena) VALUES ('es'),('tiene'),('ha'),('sabe');

CREATE TABLE adverbio (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO adverbio (cadena) VALUES ('muy'),('poco');