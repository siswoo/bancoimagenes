DROP DATABASE IF EXISTS camaleon_banco;
CREATE DATABASE camaleon_banco;
USE camaleon_banco;

DROP TABLE IF EXISTS imagenes;
CREATE TABLE imagenes (
	id INT AUTO_INCREMENT,
	nombre VARCHAR(250) NOT NULL,
	formato VARCHAR(250) NOT NULL,
	ruta VARCHAR(250) NOT NULL,
	fecha_inicio date NOT NULL,
	PRIMARY KEY (id)
); ALTER TABLE imagenes CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
