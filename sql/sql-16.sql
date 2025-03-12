/**
 * Opciones
 */
CREATE TABLE opciones
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(255),
  descripcion VARCHAR(255),
  icono VARCHAR(255),
  orden INT,
  estado INT DEFAULT 1,
  usuario_abm VARCHAR(255)
);


/**
 * Items
 */
CREATE TABLE items
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  descripcion VARCHAR(255),
  enlace VARCHAR(255),
  id_opcion INT,
  orden INT,
  estado INT DEFAULT 1,
  usuario_abm VARCHAR(255),
  FOREIGN KEY (id_opcion) REFERENCES opciones(id)
);



/**
 * Grupos
 */
CREATE TABLE grupos
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  descripcion VARCHAR(255),
  estado INT DEFAULT 1,  
  usuario_abm VARCHAR(255)
);

INSERT INTO grupos (id,descripcion,usuario_abm) VALUES (1,'Administrador', 'admin');

/**
 * Usuarios
 */
CREATE TABLE usuarios
(
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario VARCHAR(255) UNIQUE,
  persona_id INT,
  clave VARCHAR(255),
  estado INT DEFAULT 1,
  id_grupo INT,
  activo INT DEFAULT 1,
  fecha_alta TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  fecha_baja TIMESTAMP NULL,
  usuario_abm VARCHAR(255),
  FOREIGN KEY (id_grupo) REFERENCES grupos(id),
  FOREIGN KEY (persona_id) REFERENCES personas(id)
);

INSERT INTO usuarios (usuario,nombre_apellido,id_grupo,clave, usuario_abm) VALUES ('admin','admin',1,'$2y$10$SFTR98e2ru4O4Kf8HexwAOG9jeH9kO9Fc0JDaSHiIFbMrN.Vv19Vi', 'admin');

/**
 * Grupo - Items
 */
CREATE TABLE grupos_items
(
  id_grupo INT NOT NULL,
  id_item INT NOT NULL,
  usuario_abm VARCHAR(255),
  PRIMARY KEY (id_grupo, id_item),
  FOREIGN KEY (id_grupo) REFERENCES grupos(id),
  FOREIGN KEY (id_item) REFERENCES items(id)
);

/**
 * Grupo - Opciones
 */
CREATE TABLE grupos_opciones
(
  id_grupo INT NOT NULL,
  id_opcion INT NOT NULL,
  usuario_abm VARCHAR(255),
  PRIMARY KEY (id_grupo, id_opcion),
  FOREIGN KEY (id_grupo) REFERENCES grupos(id),
  FOREIGN KEY (id_opcion) REFERENCES opciones(id)
);

INSERT INTO opciones VALUES (1, 'Tablas Maestras', 'Administrar', 'fas fa-cog', 1, 1, 'admin');
INSERT INTO items VALUES (1, 'Usuarios', 'administracion/usuarios', 1, 1, 1, 'admin');
INSERT INTO items VALUES (2, 'Items', 'administracion/items', 1, 3, 1, 'admin');
INSERT INTO items VALUES (3, 'Grupos', 'administracion/grupos', 1, 4, 1, 'admin');
INSERT INTO items VALUES (4, 'Opciones de Grupos', 'administracion/opciones_grupos', 1, 5, 1, 'admin');
INSERT INTO items VALUES (5, 'Items de Grupos', 'administracion/items_grupos', 1, 6, 1, 'admin');
INSERT INTO items VALUES (6, 'Opciones', 'administracion/opciones', 1, 2, 1, 'admin');


INSERT INTO grupos_opciones VALUES (1,1);
INSERT INTO grupos_items VALUES(1,1);
INSERT INTO grupos_items VALUES(1,2);
INSERT INTO grupos_items VALUES(1,3);
INSERT INTO grupos_items VALUES(1,4);
INSERT INTO grupos_items VALUES(1,5);
INSERT INTO grupos_items VALUES(1,6);