-- ============================================
-- SISTEMA DE EMERGENCIAS CUAUTITLÁN
-- Base de datos completa - Versión ORIGINAL
-- ============================================
-- Crear base de datos
CREATE DATABASE IF NOT EXISTS emergenciascuautitlan;
USE emergenciascuautitlan;
-- Tabla central (master) - SIN CAMBIOS
CREATE TABLE tblcentral (
    Id INT(5) AUTO_INCREMENT PRIMARY KEY,
    CentralReportes VARCHAR(120) NOT NULL,
    Departamentos VARCHAR(50) NOT NULL,
    NombreVictima VARCHAR(100) NOT NULL,
    EdadVictima INT(2) NOT NULL,
    NumeroTelEmergencia VARCHAR(15) NOT NULL,
    DireccionVictima TEXT NOT NULL,
    Evento VARCHAR(60) NOT NULL,
    DescripcionEvento TEXT NOT NULL,
    Prioridad VARCHAR(10) NOT NULL,
    Estatus ENUM('Pendiente', 'En proceso', 'Atendido') DEFAULT 'Pendiente',
    Fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla bomberos con foreign key OPCIONAL y campo Origen
CREATE TABLE tblbomberos (
    Id INT(5) AUTO_INCREMENT PRIMARY KEY,
    IdCentral INT(5) NULL,
    Origen ENUM('central', 'directo') DEFAULT 'directo',
    NombreVictima VARCHAR(100) NOT NULL,
    EdadVictima INT(2) NOT NULL,
    Evento VARCHAR(60) NOT NULL,
    LugarEvento TEXT NOT NULL,
    NumeroTelEmergencia VARCHAR(15) NOT NULL,
    Correo VARCHAR(60) NOT NULL,
    DireccionVictima TEXT NOT NULL,
    DescripcionEvento TEXT NOT NULL,
    Estatus ENUM('Pendiente', 'En proceso', 'Atendido') DEFAULT 'Pendiente',
    Fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdCentral) REFERENCES tblcentral(Id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla policias con foreign key OPCIONAL y campo Origen
CREATE TABLE tblpolicias (
    Id INT(5) AUTO_INCREMENT PRIMARY KEY,
    IdCentral INT(5) NULL,
    Origen ENUM('central', 'directo') DEFAULT 'directo',
    NombreVictima VARCHAR(100) NOT NULL,
    EdadVictima INT(2) NOT NULL,
    Evento VARCHAR(60) NOT NULL,
    LugarEvento TEXT NOT NULL,
    NumeroTelEmergencia VARCHAR(15) NOT NULL,
    Correo VARCHAR(60) NOT NULL,
    DireccionVictima TEXT NOT NULL,
    DescripcionEvento TEXT NOT NULL,
    Estatus ENUM('Pendiente', 'En proceso', 'Atendido') DEFAULT 'Pendiente',
    Fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdCentral) REFERENCES tblcentral(Id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla medico con foreign key OPCIONAL y campo Origen
CREATE TABLE tblmedico (
    Id INT(5) AUTO_INCREMENT PRIMARY KEY,
    IdCentral INT(5) NULL,
    Origen ENUM('central', 'directo') DEFAULT 'directo',
    NombreVictima VARCHAR(100) NOT NULL,
    EdadVictima INT(2) NOT NULL,
    Evento VARCHAR(60) NOT NULL,
    LugarEvento TEXT NOT NULL,
    NumeroTelEmergencia VARCHAR(15) NOT NULL,
    Correo VARCHAR(60) NOT NULL,
    DireccionVictima VARCHAR(200) NOT NULL,
    DescripcionEvento TEXT NOT NULL,
    Estatus ENUM('Pendiente', 'En proceso', 'Atendido') DEFAULT 'Pendiente',
    Fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (IdCentral) REFERENCES tblcentral(Id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- Tabla usuarios (sin cambios)
CREATE TABLE tblusuarios (
    Id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'bomberos', 'policia', 'medico') NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estatus ENUM('activo', 'inactivo') DEFAULT 'activo'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4;
-- ============================================
-- DATOS DE EJEMPLO
-- ============================================
-- Insertar usuarios (sin foreign key)
INSERT INTO tblusuarios (nombre_completo, username, password, rol, email)
VALUES (
        'Amezcua Sagrero, Sergio Daniel',
        'sergio.amezcua',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'medico',
        'sergio@emergencias.com'
    ),
    (
        'Lujano Valeriano, Dulce Odalys',
        'dulce.lujano',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'policia',
        'dulce@emergencias.com'
    ),
    (
        'Cruz Vergara, Laura Rocxana',
        'laura.cruz',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin',
        'laura@emergencias.com'
    ),
    (
        'Vargas Almanza, Gerardo Enrique',
        'gerardo.vargas',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'bomberos',
        'gerardo@emergencias.com'
    );
-- Insertar datos de ejemplo en tblcentral
INSERT INTO tblcentral (
        CentralReportes,
        Departamentos,
        NombreVictima,
        EdadVictima,
        NumeroTelEmergencia,
        DireccionVictima,
        Evento,
        DescripcionEvento,
        Prioridad
    )
VALUES (
        'Central Norte',
        'bomberos',
        'Juan Pérez',
        35,
        '5512345678',
        'Av. Principal #123',
        'Incendio',
        'Incendio en cocina por aceite',
        'Alta'
    ),
    (
        'Central Sur',
        'bomberos',
        'María López',
        28,
        '5598765432',
        'Calle Secundaria #45',
        'Fuga gas',
        'Olor a gas fuerte en el local',
        'Alta'
    ),
    (
        'Central Este',
        'policia',
        'Carlos García',
        42,
        '5532145698',
        'Parque Central',
        'Robo',
        'Robo a mano armada',
        'Media'
    ),
    (
        'Central Oeste',
        'medico',
        'Ana Martínez',
        50,
        '5547852369',
        'Av. Reforma y 5 de Mayo',
        'Accidente',
        'Persona inconsciente',
        'Alta'
    );
-- Insertar en tblbomberos (algunos con IdCentral, otros sin)
INSERT INTO tblbomberos (
        IdCentral,
        Origen,
        NombreVictima,
        EdadVictima,
        Evento,
        LugarEvento,
        NumeroTelEmergencia,
        Correo,
        DireccionVictima,
        DescripcionEvento
    )
VALUES (
        1,
        'central',
        'Juan Pérez',
        35,
        'Incendio',
        'Casa habitación',
        '5512345678',
        'juan@email.com',
        'Av. Principal #123',
        'Incendio en cocina por aceite'
    ),
    (
        2,
        'central',
        'María López',
        28,
        'Fuga gas',
        'Restaurante',
        '5598765432',
        'maria@email.com',
        'Calle Secundaria #45',
        'Olor a gas fuerte en el local'
    ),
    (
        NULL,
        'directo',
        'Roberto Sánchez',
        40,
        'Rescate animal',
        'Árbol en parque',
        '5551234567',
        'roberto@email.com',
        'Calle Arboleda #78',
        'Gato atrapado en árbol'
    ),
    (
        NULL,
        'directo',
        'Laura Gómez',
        25,
        'Incendio menor',
        'Bodega industrial',
        '5567890123',
        'laura@email.com',
        'Zona Industrial #12',
        'Incendio en bodega con materiales inflamables'
    );
-- Insertar en tblpolicias (algunos con IdCentral, otros sin)
INSERT INTO tblpolicias (
        IdCentral,
        Origen,
        NombreVictima,
        EdadVictima,
        Evento,
        LugarEvento,
        NumeroTelEmergencia,
        Correo,
        DireccionVictima,
        DescripcionEvento
    )
VALUES (
        3,
        'central',
        'Carlos García',
        42,
        'Robo',
        'Parque Central',
        '5532145698',
        'carlos@email.com',
        'Parque Central',
        'Robo a mano armada'
    ),
    (
        NULL,
        'directo',
        'Fernando Torres',
        30,
        'Accidente vial',
        'Avenida Principal km 5',
        '5545678901',
        'fernando@email.com',
        'Av. Principal km 5',
        'Choque entre dos vehículos'
    ),
    (
        NULL,
        'directo',
        'Sofía Ramírez',
        35,
        'Pelea callejera',
        'Mercado municipal',
        '5578901234',
        'sofia@email.com',
        'Mercado Municipal',
        'Disputa entre vendedores'
    );
-- Insertar en tblmedico (algunos con IdCentral, otros sin)
INSERT INTO tblmedico (
        IdCentral,
        Origen,
        NombreVictima,
        EdadVictima,
        Evento,
        LugarEvento,
        NumeroTelEmergencia,
        Correo,
        DireccionVictima,
        DescripcionEvento
    )
VALUES (
        4,
        'central',
        'Ana Martínez',
        50,
        'Accidente',
        'Avenida Reforma',
        '5547852369',
        'ana@email.com',
        'Av. Reforma y 5 de Mayo',
        'Persona inconsciente'
    ),
    (
        NULL,
        'directo',
        'Miguel Ángel',
        60,
        'Infarto',
        'Casa particular',
        '5589012345',
        'miguel@email.com',
        'Calle Salud #34',
        'Dolor en el pecho, dificultad para respirar'
    ),
    (
        NULL,
        'directo',
        'Patricia Ruiz',
        28,
        'Fractura',
        'Cancha deportiva',
        '5590123456',
        'patricia@email.com',
        'Unidad Deportiva',
        'Caída durante partido de fútbol'
    );