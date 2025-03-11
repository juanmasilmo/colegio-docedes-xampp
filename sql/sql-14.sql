-- Crear tabla provincias
CREATE TABLE provincias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crear tabla localidades
CREATE TABLE localidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    provincia_id INT NOT NULL,
    codigo_postal VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (provincia_id) REFERENCES provincias(id)
);

-- Insertar una provincia de ejemplo
INSERT INTO provincias (nombre) VALUES ('Córdoba');

-- Insertar dos localidades de ejemplo
INSERT INTO localidades (nombre, provincia_id, codigo_postal) VALUES 
('Córdoba Capital', 1, '5000'),
('Villa María', 1, '5900');

-- Modificar la tabla personas para agregar la relación con localidades
ALTER TABLE personas 
    CHANGE COLUMN id_localidad id_localidad INT NOT NULL,
    ADD CONSTRAINT fk_persona_localidad 
    FOREIGN KEY (id_localidad) REFERENCES localidades(id);