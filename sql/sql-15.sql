CREATE TABLE paises (
  id INT AUTO_INCREMENT PRIMARY KEY,
  descripcion VARCHAR(255),
  observacion VARCHAR(255),
  FOREIGN KEY (provincia_id) REFERENCES provincias(id)
);

ALTER TABLE provincias
ADD COLUMN pais_id INT,
ADD FOREIGN KEY (pais_id) REFERENCES paises(id);