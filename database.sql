DROP DATABASE chucho;
CREATE DATABASE chucho;
use chucho;

CREATE TABLE verbo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO verbo (cadena) VALUES 
('es'),
('tiene'),
('ha'),
('sabe'),
('crece'),
('son'),
('esta');

CREATE TABLE adverbio (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO adverbio (cadena) VALUES 
('muy'),
('poco'),
('a veces'),
('mas o menos'),
('como'),
('algunas'),
('alguna'),
('alguno'),
('algun'),
('muchos'),
('pocas'),
('fuera'),
('dentro'),
('no'),
('ningunas'),
('ninguna'),
('ningun'),
('demasiadas');

CREATE TABLE adjetivo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO adjetivo (cadena) VALUES 
('dulce'),
('amarillo'),
('amarilla'),
('alargada'),
('grandes'),
('grande'),
('rosada'),
('naranja'),
('roja'),
('rojo'),
('verde'),
('cafe'),
('fibrosa'),
('calido'),
('morado'),
('redonda'),
('acido'),
('frio'),
('mediano'),
('mediana');

CREATE TABLE sustantivo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO sustantivo (cadena) VALUES 
('semillas'),
('semilla'),
('arboles'),
('arbol'),
('clima'),
('sabor'),
('zarcillos'),
('fruto'),
('racimos'),
('color'),
('enrredadera');

CREATE TABLE articulo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO articulo (cadena) VALUES 
('una'),
('un'),
('las'),
('la'),
('los');

CREATE TABLE preposicion (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO preposicion (cadena) VALUES 
('de'),
('por'),
('en');