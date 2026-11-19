-- HORSMANN PROYECTO VIDEOJUEGOS - base de datos
-- Importar desde phpMyAdmin (pestaña "Importar").

CREATE DATABASE IF NOT EXISTS horsmann_videojuegos
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE horsmann_videojuegos;

CREATE TABLE IF NOT EXISTS usuarios (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    nombre          VARCHAR(50)  NOT NULL,
    apellido        VARCHAR(50)  NOT NULL,
    dni             VARCHAR(8)   NOT NULL,
    email           VARCHAR(100) NOT NULL,
    nickname        VARCHAR(20)  NOT NULL,              -- gamertag del jugador
    plataforma      ENUM('PC','PlayStation','Xbox','Nintendo','Móvil') NOT NULL DEFAULT 'PC',
    password        VARCHAR(255) NOT NULL,              -- aca va el hash, no la clave
    rol             ENUM('usuario','admin') NOT NULL DEFAULT 'usuario',
    fecha_creacion  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_archivado DATETIME     NULL DEFAULT NULL,     -- NULL = cuenta activa
    PRIMARY KEY (id),
    UNIQUE KEY uq_usuarios_dni (dni),
    UNIQUE KEY uq_usuarios_email (email),
    UNIQUE KEY uq_usuarios_nickname (nickname)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de prueba
-- Administrador: admin@horsmann.com / Admin1234
-- Jugadores: contraseña Jugador123
INSERT INTO usuarios (nombre, apellido, dni, email, nickname, plataforma, password, rol, fecha_creacion, fecha_archivado) VALUES
('Admin', 'Horsmann', '30111222', 'admin@horsmann.com', 'MasterAdmin', 'PC',          '$2y$10$Wl3pIotkaGayMDnSLMIVpuru9vB2mcuUb4co1iEBlN4xKLMtM9Q9G', 'admin',   DATE_SUB(NOW(), INTERVAL 40 DAY), NULL),
('Lucía', 'Gómez',    '40222333', 'lucia@correo.com',   'LuciaPixel',  'PlayStation', '$2y$10$Ho6GmZE196xVCzFBIoAequ4a/kNrY/w8OTZMMPq/z/VZsNapNEXdy', 'usuario', DATE_SUB(NOW(), INTERVAL 15 DAY), NULL),
('Mateo', 'Díaz',     '41333444', 'mateo@correo.com',   'MateoRetro',  'Nintendo',    '$2y$10$Ho6GmZE196xVCzFBIoAequ4a/kNrY/w8OTZMMPq/z/VZsNapNEXdy', 'usuario', DATE_SUB(NOW(), INTERVAL 3 DAY),  NULL),
('Sofía', 'Ruiz',     '42444555', 'sofia@correo.com',   'SofiGamer',   'Xbox',        '$2y$10$Ho6GmZE196xVCzFBIoAequ4a/kNrY/w8OTZMMPq/z/VZsNapNEXdy', 'usuario', DATE_SUB(NOW(), INTERVAL 20 DAY), NOW());
