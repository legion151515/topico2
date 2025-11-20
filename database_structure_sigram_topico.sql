-- ============================================================
-- ESTRUCTURA COMPLETA DE BASE DE DATOS: sigram_topico
-- Sistema Integrado de Gestión y Registro de Atenciones - Bienestar
-- Generado: 2025-11-20
-- ============================================================

SET FOREIGN_KEY_CHECKS=0;
DROP DATABASE IF EXISTS sigram_topico;
CREATE DATABASE sigram_topico CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sigram_topico;

-- ============================================================
-- TABLA: users
-- Descripción: Usuarios del sistema (administradores, médicos, recepcionistas, estudiantes)
-- ============================================================
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Nombre completo del usuario',
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'Correo electrónico único',
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL COMMENT 'Contraseña encriptada',
    tipo_usuario VARCHAR(50) NOT NULL DEFAULT 'estudiante' COMMENT 'administrador, medico, recepcionista, estudiante',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_tipo_usuario (tipo_usuario),
    INDEX idx_email (email)
) ENGINE=InnoDB COMMENT='Usuarios del sistema con roles diferenciados';

-- ============================================================
-- TABLA: carreras
-- Descripción: Carreras profesionales (Tecnológico, Pedagógico, Escuela)
-- ============================================================
CREATE TABLE carreras (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL COMMENT 'Nombre de la carrera',
    categoria VARCHAR(100) NOT NULL COMMENT 'Tecnológico, Pedagógico, Escuela, Otros',
    descripcion TEXT NULL COMMENT 'Descripción de la carrera',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_categoria (categoria),
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB COMMENT='Carreras y programas académicos del instituto';

-- ============================================================
-- TABLA: pacientes
-- Descripción: Estudiantes, docentes y personal que reciben atención médica
-- ============================================================
CREATE TABLE pacientes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    dni VARCHAR(20) NOT NULL UNIQUE COMMENT 'DNI único del paciente',
    nombre VARCHAR(255) NOT NULL COMMENT 'Nombre(s) del paciente',
    apellido VARCHAR(255) NOT NULL COMMENT 'Apellidos del paciente',
    edad INT NOT NULL COMMENT 'Edad del paciente',
    carrera_id BIGINT UNSIGNED NULL COMMENT 'Carrera actual del paciente (puede cambiar)',
    otros_especificacion VARCHAR(255) NULL COMMENT 'Especificación para categoría "Otros"',
    created_at TIMESTAMP NULL COMMENT 'Fecha de primer registro',
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (carrera_id) REFERENCES carreras(id) ON DELETE SET NULL,
    INDEX idx_dni (dni),
    INDEX idx_nombre (nombre, apellido),
    INDEX idx_carrera (carrera_id)
) ENGINE=InnoDB COMMENT='Registro de pacientes del sistema de salud';

-- ============================================================
-- TABLA: niveles
-- Descripción: Información académica detallada de cada paciente
-- Permite rastrear cambios académicos del estudiante
-- ============================================================
CREATE TABLE niveles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    paciente_id BIGINT UNSIGNED NOT NULL COMMENT 'Paciente asociado',
    categoria VARCHAR(100) NOT NULL COMMENT 'Tecnológico, Pedagógico, Escuela, Otros',
    semestre VARCHAR(10) NULL COMMENT 'Semestre para Tecnológico/Pedagógico (I-X)',
    nivel_escuela VARCHAR(50) NULL COMMENT 'INICIAL, PRIMARIA, SECUNDARIA',
    grado VARCHAR(10) NULL COMMENT 'Grado escolar (1°-6° primaria, 1°-5° secundaria)',
    anios VARCHAR(10) NULL COMMENT 'Años para nivel INICIAL (3, 4, 5 años)',
    otros_especificacion VARCHAR(255) NULL COMMENT 'Especificación para categoría "Otros"',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    INDEX idx_paciente (paciente_id),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB COMMENT='Niveles y categorías académicas de pacientes';

-- ============================================================
-- TABLA: motivo_consultas
-- Descripción: Catálogo de motivos de consulta médica
-- ============================================================
CREATE TABLE motivo_consultas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL COMMENT 'Descripción del motivo de consulta',
    descripcion TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_nombre (nombre)
) ENGINE=InnoDB COMMENT='Catálogo de motivos de consulta médica';

-- ============================================================
-- TABLA: medicamentos
-- Descripción: Inventario de medicamentos y suministros médicos
-- Soporta diferentes unidades de medida (unidades, ml, gr, ampollas, sobres)
-- ============================================================
CREATE TABLE medicamentos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL COMMENT 'Nombre del medicamento o suministro',
    descripcion TEXT NULL COMMENT 'Descripción, indicaciones, presentación',
    tipo_unidad VARCHAR(50) NOT NULL DEFAULT 'unidad' COMMENT 'unidad, ml, gr, ampolla, sobre, otros',
    presentacion VARCHAR(100) NULL COMMENT 'Presentación comercial (ej: 1000ml, 500mg)',
    cantidad_stock DECIMAL(10,2) NOT NULL DEFAULT 0 COMMENT 'Cantidad disponible en stock (permite decimales para líquidos)',
    stock_minimo_alerta INT NOT NULL DEFAULT 10 COMMENT 'Nivel mínimo para alertar reabastecimiento',
    fecha_vencimiento DATE NULL COMMENT 'Fecha de vencimiento del medicamento',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_nombre (nombre),
    INDEX idx_stock_bajo (cantidad_stock, stock_minimo_alerta),
    INDEX idx_vencimiento (fecha_vencimiento)
) ENGINE=InnoDB COMMENT='Inventario de medicamentos con control de stock y unidades múltiples';

-- ============================================================
-- TABLA: atencions
-- Descripción: Registro de cada atención médica realizada
-- Incluye SNAPSHOTS de datos del paciente al momento de la atención
-- Permite rastrear cambios académicos y de carrera a lo largo del tiempo
-- ============================================================
CREATE TABLE atencions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    paciente_id BIGINT UNSIGNED NOT NULL COMMENT 'Paciente atendido',
    user_id BIGINT UNSIGNED NULL COMMENT 'Usuario que atendió (médico/administrador)',

    -- SNAPSHOTS: Datos académicos del paciente EN ESTE MOMENTO
    categoria VARCHAR(100) NULL COMMENT 'Snapshot: Categoría al momento de la atención',
    nivel_id BIGINT UNSIGNED NULL COMMENT 'Snapshot: Nivel académico asociado',
    semestre VARCHAR(10) NULL COMMENT 'Snapshot: Semestre en esta atención',
    grado VARCHAR(10) NULL COMMENT 'Snapshot: Grado en esta atención',
    nivel_escuela VARCHAR(50) NULL COMMENT 'Snapshot: Nivel escuela (INICIAL/PRIMARIA/SECUNDARIA)',
    anios VARCHAR(10) NULL COMMENT 'Snapshot: Años para nivel INICIAL',
    otros_especificacion VARCHAR(255) NULL COMMENT 'Snapshot: Especificación para "Otros"',

    -- Motivo de consulta
    motivo_id BIGINT UNSIGNED NULL COMMENT 'Motivo predefinido de consulta',
    motivo_otro TEXT NULL COMMENT 'Motivo personalizado cuando se selecciona "Otros"',

    -- Información temporal
    fecha DATE NOT NULL COMMENT 'Fecha de la atención',
    hora_entrada TIME NOT NULL COMMENT 'Hora de entrada del paciente',
    hora_salida TIME NULL COMMENT 'Hora de salida del paciente',
    tipo_salida VARCHAR(50) NULL COMMENT 'Manual o Automático',

    -- Otros datos
    token_firma VARCHAR(255) NULL COMMENT 'Token de firma digital',
    observaciones TEXT NULL COMMENT 'Notas y observaciones médicas',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (motivo_id) REFERENCES motivo_consultas(id) ON DELETE SET NULL,
    FOREIGN KEY (nivel_id) REFERENCES niveles(id) ON DELETE SET NULL,

    INDEX idx_paciente (paciente_id),
    INDEX idx_user (user_id),
    INDEX idx_fecha (fecha),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB COMMENT='Registro de atenciones médicas con snapshots de datos académicos';

-- ============================================================
-- TABLA: atencion_medicamento (PIVOT)
-- Descripción: Relación muchos a muchos entre atenciones y medicamentos
-- Registra qué medicamentos se usaron en cada atención y en qué cantidad
-- ============================================================
CREATE TABLE atencion_medicamento (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    atencion_id BIGINT UNSIGNED NOT NULL COMMENT 'Atención médica',
    medicamento_id BIGINT UNSIGNED NOT NULL COMMENT 'Medicamento utilizado',
    cantidad_usada DECIMAL(10,2) NOT NULL DEFAULT 1 COMMENT 'Cantidad usada (permite decimales para líquidos)',
    observaciones TEXT NULL COMMENT 'Notas sobre el uso del medicamento',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (atencion_id) REFERENCES atencions(id) ON DELETE CASCADE,
    FOREIGN KEY (medicamento_id) REFERENCES medicamentos(id) ON DELETE CASCADE,

    UNIQUE KEY unique_atencion_medicamento (atencion_id, medicamento_id),
    INDEX idx_atencion (atencion_id),
    INDEX idx_medicamento (medicamento_id)
) ENGINE=InnoDB COMMENT='Medicamentos utilizados en cada atención médica';

-- ============================================================
-- TABLA: citas
-- Descripción: Sistema de agendamiento de citas médicas
-- ============================================================
CREATE TABLE citas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    paciente_id BIGINT UNSIGNED NULL COMMENT 'Paciente que solicita la cita',
    user_id BIGINT UNSIGNED NULL COMMENT 'Usuario que registró la cita',
    fecha_cita DATE NOT NULL COMMENT 'Fecha programada para la cita',
    hora_cita TIME NOT NULL COMMENT 'Hora programada para la cita',
    motivo TEXT NULL COMMENT 'Motivo de la cita',
    estado VARCHAR(50) NOT NULL DEFAULT 'pendiente' COMMENT 'pendiente, confirmada, atendida, cancelada',
    observaciones TEXT NULL COMMENT 'Notas adicionales',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (paciente_id) REFERENCES pacientes(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,

    INDEX idx_paciente (paciente_id),
    INDEX idx_fecha (fecha_cita),
    INDEX idx_estado (estado)
) ENGINE=InnoDB COMMENT='Sistema de gestión de citas médicas';

-- ============================================================
-- TABLA: cache (Laravel Framework)
-- ============================================================
CREATE TABLE cache (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    value MEDIUMTEXT NOT NULL,
    expiration INT NOT NULL,
    INDEX idx_expiration (expiration)
) ENGINE=InnoDB;

CREATE TABLE cache_locks (
    `key` VARCHAR(255) NOT NULL PRIMARY KEY,
    owner VARCHAR(255) NOT NULL,
    expiration INT NOT NULL,
    INDEX idx_expiration (expiration)
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: jobs (Laravel Queue)
-- ============================================================
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    queue VARCHAR(255) NOT NULL,
    payload LONGTEXT NOT NULL,
    attempts TINYINT UNSIGNED NOT NULL,
    reserved_at INT UNSIGNED NULL,
    available_at INT UNSIGNED NOT NULL,
    created_at INT UNSIGNED NOT NULL,
    INDEX idx_queue (queue)
) ENGINE=InnoDB;

CREATE TABLE job_batches (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    total_jobs INT NOT NULL,
    pending_jobs INT NOT NULL,
    failed_jobs INT NOT NULL,
    failed_job_ids LONGTEXT NOT NULL,
    options MEDIUMTEXT NULL,
    cancelled_at INT NULL,
    created_at INT NOT NULL,
    finished_at INT NULL
) ENGINE=InnoDB;

CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(255) NOT NULL UNIQUE,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload LONGTEXT NOT NULL,
    exception LONGTEXT NOT NULL,
    failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: sessions (Laravel Session Management)
-- ============================================================
CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    INDEX idx_user (user_id),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB;

-- ============================================================
-- TABLA: password_reset_tokens
-- ============================================================
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL PRIMARY KEY,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS=1;

-- ============================================================
-- DATOS DE EJEMPLO PARA MOTIVOS DE CONSULTA
-- ============================================================
INSERT INTO motivo_consultas (nombre, descripcion, created_at, updated_at) VALUES
('Dolor de cabeza', 'Cefalea, migraña', NOW(), NOW()),
('Dolor de estómago', 'Malestar estomacal, gastritis', NOW(), NOW()),
('Fiebre', 'Temperatura corporal elevada', NOW(), NOW()),
('Resfriado', 'Síntomas de gripe, congestión nasal', NOW(), NOW()),
('Dolor muscular', 'Mialgia, dolor en músculos', NOW(), NOW()),
('Lesión deportiva', 'Esguinces, fracturas por deporte', NOW(), NOW()),
('Control de presión', 'Monitoreo de presión arterial', NOW(), NOW()),
('Consulta nutricional', 'Asesoría sobre alimentación', NOW(), NOW());

-- ============================================================
-- DATOS DE EJEMPLO PARA CARRERAS
-- ============================================================
INSERT INTO carreras (nombre, categoria, descripcion, created_at, updated_at) VALUES
-- Tecnológico
('Computación e Informática', 'Tecnológico', 'Carrera técnica en sistemas y programación', NOW(), NOW()),
('Contabilidad', 'Tecnológico', 'Carrera técnica en contabilidad y finanzas', NOW(), NOW()),
('Administración de Empresas', 'Tecnológico', 'Carrera técnica en gestión empresarial', NOW(), NOW()),
('Secretariado Ejecutivo', 'Tecnológico', 'Carrera técnica en secretariado', NOW(), NOW()),

-- Pedagógico
('Educación Inicial', 'Pedagógico', 'Formación de docentes para nivel inicial', NOW(), NOW()),
('Educación Primaria', 'Pedagógico', 'Formación de docentes para nivel primario', NOW(), NOW()),
('Educación Física', 'Pedagógico', 'Formación de docentes en educación física', NOW(), NOW()),
('Idiomas - Inglés', 'Pedagógico', 'Formación de docentes en idioma inglés', NOW(), NOW()),

-- Escuela
('Educación Básica', 'Escuela', 'Estudiantes de colegio del instituto', NOW(), NOW());

-- ============================================================
-- USUARIO ADMINISTRADOR DE EJEMPLO
-- ============================================================
-- Contraseña: password (hasheada con bcrypt)
INSERT INTO users (name, email, password, tipo_usuario, created_at, updated_at) VALUES
('Administrador Sistema', 'admin@sigram.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY3/doBWnj0TQMy', 'administrador', NOW(), NOW()),
('Dr. Juan Pérez', 'medico@sigram.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY3/doBWnj0TQMy', 'medico', NOW(), NOW()),
('María López', 'recepcion@sigram.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY3/doBWnj0TQMy', 'recepcionista', NOW(), NOW());

-- ============================================================
-- MEDICAMENTOS DE EJEMPLO
-- ============================================================
INSERT INTO medicamentos (nombre, descripcion, tipo_unidad, presentacion, cantidad_stock, stock_minimo_alerta, created_at, updated_at) VALUES
-- Medicamentos en unidades
('Paracetamol 500mg', 'Analgésico y antipirético', 'unidad', '500mg por tableta', 500, 50, NOW(), NOW()),
('Ibuprofeno 400mg', 'Antiinflamatorio y analgésico', 'unidad', '400mg por tableta', 300, 50, NOW(), NOW()),
('Amoxicilina 500mg', 'Antibiótico de amplio espectro', 'unidad', '500mg por cápsula', 200, 30, NOW(), NOW()),

-- Líquidos en mililitros
('Alcohol 70%', 'Antiséptico para desinfección', 'ml', '1000ml por botella', 5000, 1000, NOW(), NOW()),
('Jarabe para la tos', 'Antitusivo y expectorante', 'ml', '120ml por frasco', 2400, 500, NOW(), NOW()),

-- Cremas y pomadas en gramos
('Pomada antibiótica', 'Antibiótico tópico', 'gr', '15gr por tubo', 450, 100, NOW(), NOW()),
('Crema hidratante', 'Para irritaciones de piel', 'gr', '50gr por tubo', 1000, 200, NOW(), NOW()),

-- Otros formatos
('Suero fisiológico', 'Solución salina estéril', 'ampolla', '10ml por ampolla', 100, 20, NOW(), NOW()),
('Sales de rehidratación', 'Para rehidratación oral', 'sobre', '27.9gr por sobre', 150, 30, NOW(), NOW());

-- ============================================================
-- RESUMEN DE LA ESTRUCTURA
-- ============================================================
-- TABLAS PRINCIPALES:
-- 1. users: Usuarios del sistema (administrador, médico, recepcionista, estudiante)
-- 2. carreras: Carreras del instituto (Tecnológico, Pedagógico, Escuela)
-- 3. pacientes: Estudiantes y personal que recibe atención médica
-- 4. niveles: Información académica detallada de cada paciente
-- 5. motivo_consultas: Catálogo de motivos de consulta
-- 6. medicamentos: Inventario con soporte para múltiples unidades (ml, gr, unidades)
-- 7. atencions: Registro de cada atención con snapshots de datos académicos
-- 8. atencion_medicamento: Medicamentos usados en cada atención
-- 9. citas: Sistema de agendamiento de citas médicas
--
-- CARACTERÍSTICAS PRINCIPALES:
-- ✓ Sistema de snapshots: Cada atención guarda categoría, carrera, semestre del momento
-- ✓ Auditoría: Registro de quién atendió a cada paciente (user_id)
-- ✓ Unidades múltiples: Medicamentos en ml, gr, unidades, ampollas, sobres
-- ✓ Control de stock: Alertas automáticas de stock mínimo
-- ✓ Relaciones completas: Foreign keys con ON DELETE CASCADE/SET NULL
-- ✓ Índices optimizados: Para búsquedas rápidas por DNI, fecha, nombre, etc.
--
-- NOTAS IMPORTANTES:
-- - Los campos con NULL permiten que el dato sea opcional
-- - Los snapshots en 'atencions' permiten rastrear cambios académicos históricos
-- - El sistema soporta estudiantes con múltiples carreras a lo largo del tiempo
-- - Las unidades de medicamentos permiten decimales (ej: 100.5 ml)
-- ============================================================
