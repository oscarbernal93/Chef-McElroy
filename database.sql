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
('muchas',0.8),
('pocas',0.2),
('fuera',1),
('dentro',1),
('ningunas',0.1),
('ninguna',0.1),
('ningun',0.1),
('demasiadas',0.9),
('semi',0.5),
('no',0);

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

CREATE TABLE fruta (
	id INTEGER NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO fruta (nombre) VALUES 
('manzana'),
('pera');

CREATE TABLE caracteristica (
	id INTEGER NOT NULL AUTO_INCREMENT,
	nombre VARCHAR(255) NOT NULL,
	PRIMARY KEY (id)
);
INSERT INTO caracteristica (nombre) VALUES 
('roja'),
('dulce'),
('arbol'),
('verde');

CREATE TABLE fruta_caracteristica (
	fruta_id INTEGER NOT NULL,
	caracteristica_id INTEGER NOT NULL,
	valor FLOAT NOT NULL,
	PRIMARY KEY (fruta_id,caracteristica_id)
);
INSERT INTO fruta_caracteristica (fruta_id,caracteristica_id,valor) VALUES 
(1,1,0.8),
(2,2,0.7),
(2,1,0),
(1,3,0.9),
(1,4,0.4),
(1,2,0.8);