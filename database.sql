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

CREATE TABLE adjetivo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO adjetivo (cadena) VALUES ('dulce'),('amarillo');

CREATE TABLE sustantivo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO sustantivo (cadena) VALUES ('semillas');

CREATE TABLE articulo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO articulo (cadena) VALUES ('un'),('una'),('la'),('las'),('los');

CREATE TABLE preposicion (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO preposicion (cadena) VALUES ('de'),('por'),('en');