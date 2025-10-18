-- Tabla de canchas
CREATE DATABASE reservas_futbol; 

USE reservas_futbol;

CREATE TABLE canchas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_hora DECIMAL(10,2) NOT NULL,
    activa BOOLEAN DEFAULT TRUE
);

-- Tabla de horarios disponibles
CREATE TABLE horarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cancha_id INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    disponible BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (cancha_id) REFERENCES canchas(id),
    UNIQUE KEY unique_horario (cancha_id, fecha, hora)
);

-- Tabla de reservas
CREATE TABLE reservas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cancha_id INT NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    cliente_nombre VARCHAR(100) NOT NULL,
    cliente_email VARCHAR(150) NOT NULL,
    cliente_telefono VARCHAR(20),
    estado ENUM('confirmada', 'cancelada', 'completada') DEFAULT 'confirmada',
    fecha_reserva TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cancha_id) REFERENCES canchas(id)
);