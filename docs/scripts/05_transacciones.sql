CREATE TABLE transacciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    order_id VARCHAR(255) UNIQUE,
    monto DECIMAL(10,2),
    estado VARCHAR(50),
    productos TEXT, 
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


