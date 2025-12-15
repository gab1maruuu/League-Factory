-- 1. Crear la base de datos (opcional si ya la tienes)
CREATE DATABASE IF NOT EXISTS app_ligas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE app_ligas;

-- 2. Tabla de USUARIOS (Incluye Admins, Organizadores y Jugadores)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- Recuerda usar password_hash() en PHP
    rol ENUM('admin', 'organizador', 'usuario') DEFAULT 'usuario',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Tabla de LIGAS
CREATE TABLE ligas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    deporte VARCHAR(50) NOT NULL, -- Ej: Fútbol, Baloncesto
    temporada VARCHAR(20), -- Ej: 2024/2025
    estado ENUM('abierta', 'en_curso', 'finalizada') DEFAULT 'abierta',
    creado_por INT NOT NULL, -- El usuario organizador
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (creado_por) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 4. Tabla de EQUIPOS
CREATE TABLE equipos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    escudo_url VARCHAR(255), -- URL a la imagen del logo
    capitan_id INT, -- Usuario que gestiona el equipo
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (capitan_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Tabla de INSCRIPCIONES (Relación Ligas <-> Equipos)
-- Permite saber qué equipos juegan en qué liga
CREATE TABLE inscripciones_liga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liga_id INT NOT NULL,
    equipo_id INT NOT NULL,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    puntos INT DEFAULT 0,
    partidos_jugados INT DEFAULT 0,
    victorias INT DEFAULT 0,
    empates INT DEFAULT 0,
    derrotas INT DEFAULT 0,
    goles_favor INT DEFAULT 0,
    goles_contra INT DEFAULT 0,
    FOREIGN KEY (liga_id) REFERENCES ligas(id) ON DELETE CASCADE,
    FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE,
    UNIQUE(liga_id, equipo_id) -- Un equipo no puede estar dos veces en la misma liga
) ENGINE=InnoDB;

-- 6. Tabla de JORNADAS (Opcional, para organizar partidos por semanas)
CREATE TABLE jornadas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liga_id INT NOT NULL,
    numero_jornada INT NOT NULL, -- Ej: Jornada 1, Jornada 2
    fecha_inicio DATE,
    fecha_fin DATE,
    FOREIGN KEY (liga_id) REFERENCES ligas(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Tabla de PARTIDOS (Incluye Horarios y Ubicación)
CREATE TABLE partidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liga_id INT NOT NULL,
    jornada_id INT, -- Opcional, si usas la tabla jornadas
    equipo_local_id INT NOT NULL,
    equipo_visitante_id INT NOT NULL,
    fecha_hora DATETIME, -- Aquí guardas el HORARIO
    ubicacion VARCHAR(150), -- Estadio o pista
    goles_local INT DEFAULT NULL, -- NULL significa que no se ha jugado
    goles_visitante INT DEFAULT NULL,
    estado ENUM('programado', 'finalizado', 'suspendido') DEFAULT 'programado',
    FOREIGN KEY (liga_id) REFERENCES ligas(id) ON DELETE CASCADE,
    FOREIGN KEY (jornada_id) REFERENCES jornadas(id) ON DELETE SET NULL,
    FOREIGN KEY (equipo_local_id) REFERENCES equipos(id) ON DELETE CASCADE,
    FOREIGN KEY (equipo_visitante_id) REFERENCES equipos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. Tabla de JUGADORES (Detalle de la plantilla de un equipo)
-- "Todo lo que veas conveniente": Es útil tener la lista de jugadores aparte del usuario login
CREATE TABLE jugadores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipo_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    dorsal INT,
    posicion VARCHAR(50), -- Portero, Delantero, etc.
    usuario_asociado_id INT NULL, -- Si el jugador también es usuario de la app
    FOREIGN KEY (equipo_id) REFERENCES equipos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_asociado_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 9. Tabla de ESTADÍSTICAS / EVENTOS (Goles, Tarjetas, MVP)
CREATE TABLE eventos_partido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    partido_id INT NOT NULL,
    jugador_id INT, -- Quién metió el gol o recibió tarjeta
    tipo_evento ENUM('gol', 'tarjeta_amarilla', 'tarjeta_roja', 'asistencia') NOT NULL,
    minuto INT,
    FOREIGN KEY (partido_id) REFERENCES partidos(id) ON DELETE CASCADE,
    FOREIGN KEY (jugador_id) REFERENCES jugadores(id) ON DELETE CASCADE
) ENGINE=InnoDB;