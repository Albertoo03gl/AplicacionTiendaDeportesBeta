CREATE DATABASE IF NOT EXISTS MaterialDeporte DEFAULT CHARACTER SET latin1 COLLATE latin1_spanish_ci;

USE MaterialDeporte;

CREATE TABLE Marca (
    id_marca INT NOT NULL AUTO_INCREMENT,
    nombre_marca VARCHAR(50) NOT NULL,
    PRIMARY KEY (id_marca),
    UNIQUE (nombre_marca)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;


INSERT INTO Marca (nombre_marca) VALUES 
('Nike'),
('Adidas'),
('Puma'),
('Wilson'),
('Spalding'),
('Trek'),
('Everlast'),
('Specialized'),
('Under Armour'),
('Reebok'),
('Decathlon'),
('Bowflex'),
('Giant'),
('Cannondale'),
('Jordan'),
('Venum'),
('Title Boxing'),
('Ringside'),
('Cleto Reyes'),
('Fairtex');


CREATE TABLE Producto (
    id_producto INT NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    precio INT NOT NULL,
    cantidadStock INT NOT NULL,
    id_marca INT NOT NULL,  
    PRIMARY KEY (id_producto),
    FOREIGN KEY (id_marca) REFERENCES Marca (id_marca) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

INSERT INTO Producto (nombre, tipo, precio, cantidadStock, id_marca) VALUES 
('Bicicleta de montaña', 'Bicicleta', 300, 10, 1),  
('Bicicleta de carretera', 'Bicicleta', 400, 15, 2),  
('Bicicleta BMX', 'Bicicleta', 250, 8, 3),  
('Bicicleta eléctrica', 'Bicicleta', 900, 5, 4),  
('Bicicleta híbrida', 'Bicicleta', 350, 12, 5),  
('Camiseta de compresión', 'Ropa', 30, 60, 6),  
('Chaqueta impermeable', 'Ropa', 80, 20, 7),  
('Shorts deportivos', 'Ropa', 25, 70, 8),  
('Sudadera térmica', 'Ropa', 60, 40, 9),  
('Polo de entrenamiento', 'Ropa', 40, 50, 10),  
('Zapatillas para correr', 'Calzado', 100, 25, 11),  
('Botas de senderismo', 'Calzado', 120, 15, 12),  
('Tenis casuales', 'Calzado', 60, 30, 13),  
('Zapatos de ciclismo', 'Calzado', 90, 20, 14),  
('Zapatos de baloncesto', 'Calzado', 110, 18, 15),  
('Guantes de boxeo estándar', 'Guantes', 50, 20, 16),  
('Guantes de boxeo de cuero', 'Guantes', 80, 15, 17),  
('Guantes de boxeo para sparring', 'Guantes', 70, 12, 18),  
('Guantes de boxeo para niños', 'Guantes', 40, 30, 19),  
('Guantes de boxeo premium', 'Guantes', 100, 10, 20);
       

CREATE TABLE CLIENTES (
    id_cliente INT NOT NULL AUTO_INCREMENT,
    correo VARCHAR(50) NOT NULL,
    contrasenia VARCHAR(9) NOT NULL,
    nombre_social VARCHAR(50) NOT NULL,
    CIF INT NOT NULL, 
    pais VARCHAR(50) NOT NULL,
    direccion VARCHAR(50) NOT NULL,
    codigo_postal INT NOT NULL,
    PRIMARY KEY (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

INSERT INTO CLIENTES (correo, contrasenia, nombre_social, CIF, pais, direccion, codigo_postal) 
VALUES 
('gonzalezlopezalberto511@gmail.com', 'abc12345', 'Deportes S.A.', 12345678, 'España', 'Calle Mayor 10', 28001);

CREATE TABLE Pedidos (
    id_pedido INT(11) NOT NULL AUTO_INCREMENT, 
    id_cliente INT(11) NOT NULL,                
    fecha_pedido DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,  
    total DECIMAL(10, 2) NOT NULL,              
    PRIMARY KEY (id_pedido),                    
    FOREIGN KEY (id_cliente) REFERENCES CLIENTES (id_cliente) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

CREATE TABLE DetallePedidos (
    id_detalle INT(11) NOT NULL AUTO_INCREMENT, 
    id_pedido INT(11) NOT NULL,                 
    id_producto INT(11) NOT NULL,           
    cantidad INT(11) NOT NULL,                 
    precio_unitario DECIMAL(10, 2) NOT NULL,    
    subtotal DECIMAL(10, 2) NOT NULL,           
    PRIMARY KEY (id_detalle),                   
    FOREIGN KEY (id_pedido) REFERENCES Pedidos (id_pedido) ON DELETE CASCADE,
    FOREIGN KEY (id_producto) REFERENCES Producto (id_producto) ON DELETE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;