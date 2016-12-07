DROP DATABASE chucho;
CREATE DATABASE chucho;
use chucho;

CREATE TABLE verbo (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO verbo (cadena) VALUES 
('esta'),
('es'),
('tiene'),
('ha crecido'),
('sabe'),
('crece'),
('son');

CREATE TABLE adverbio (
	id INTEGER NOT NULL AUTO_INCREMENT,
	cadena VARCHAR(255) NOT NULL,
	valor FLOAT NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO adverbio (cadena,valor) VALUES 
('muy',0.9),
('poco',0.2),
('a veces',0.5),
('mas o menos',0.5),
('como',0.5),
('algunas',0.3),
('alguna',0.3),
('alguno',0.3),
('algun',0.3),
('muchos',0.8),
('pocas',0.2),
('fuera',1),
('dentro',1),
('no',0),
('ningunas',0.1),
('ninguna',0.1),
('ningun',0.1),
('demasiadas',0.9),
('semi',0.5);

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
('verdes'),
('verde'),
('cafe'),
('fibrosa'),
('calido'),
('morado'),
('redonda'),
('acido'),
('frio'),
('mediano'),
('mediana'),
('dura'),
('lleno'),
('llena');

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
('racimo'),
('color'),
('agua'),
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
('sus'),
('su'),
('en');