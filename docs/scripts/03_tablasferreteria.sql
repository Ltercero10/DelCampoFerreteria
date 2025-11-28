CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    estado ENUM('ACT', 'INA') DEFAULT 'ACT',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de productos
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    imagen VARCHAR(255),
    estado ENUM('ACT', 'INA') DEFAULT 'ACT',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL
);

CREATE TABLE productos_imagenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    url_imagen VARCHAR(500) NOT NULL,
    orden INT DEFAULT 1,
    estado CHAR(3) DEFAULT 'ACT',
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);


UPDATE productos 
SET imagen = '/uploads/productos/plano' 
WHERE id = 2;

ALTER TABLE productos 
MODIFY COLUMN imagen LONGTEXT NULL;



