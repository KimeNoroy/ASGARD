
DROP DATABASE IF EXISTS asgard;
CREATE DATABASE IF NOT EXISTS asgard;
USE asgard;

CREATE TABLE tb_administrador (
  id_administrador INT PRIMARY KEY AUTO_INCREMENT,
  nombre_administrador VARCHAR(50) NOT NULL,
  apellido_administrador VARCHAR(50) NOT NULL,
  email_administrador VARCHAR (100) UNIQUE,
  contraseña_administrador VARCHAR(100) NOT NULL,
  validator DATETIME,
  validatorcount INT DEFAULT 0,
  cambio_contraseña DATETIME DEFAULT (NOW() + INTERVAL 90 DAY)
);

-- Tabla de Clientes
CREATE TABLE tb_clientes(
   id_cliente INT PRIMARY KEY AUTO_INCREMENT,
   nombre_cliente VARCHAR(100) NOT NULL,
   apellido_cliente VARCHAR(100) NOT NULL,
   email_cliente VARCHAR(100) UNIQUE,
   imagen_cliente VARCHAR(25) NOT NULL,
   dui_cliente VARCHAR(10) UNIQUE,
   direccion_cliente VARCHAR(255),
   departamento_cliente VARCHAR(100) NOT NULL,
   municipio_cliente VARCHAR(100),
   telefono_cliente VARCHAR(15) UNIQUE NOT NULL
);
 
-- Tabla de Documentos Emitidos
CREATE TABLE tb_documentos_emitidos (
   id_documento INT PRIMARY KEY AUTO_INCREMENT,
   tipo_documento VARCHAR(50) NOT NULL,
   fecha_emision DATETIME NOT NULL,
   id_cliente INT,  -- Se añadió esta línea
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente)
);
 
-- Tabla de Servicios
CREATE TABLE tb_servicios (
   id_servicio INT PRIMARY KEY AUTO_INCREMENT,
   nombre_servicio VARCHAR(100) NOT NULL,
   descripcion VARCHAR(255),
   id_cliente INT,
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente)
);
 
-- Tabla de Empleados
CREATE TABLE tb_empleados (
   id_empleado INT PRIMARY KEY AUTO_INCREMENT,
   nombres_empleado VARCHAR(100) NOT NULL,
   apellidos_empleado VARCHAR(100) NOT NULL,
   dui_empleado VARCHAR(100) UNIQUE
);
 
-- Tabla de Relación entre Empleados y Clientes
CREATE TABLE tb_empleado_cliente (
   id_empleado INT,
   id_cliente INT,
   PRIMARY KEY (id_empleado, id_cliente),
   FOREIGN KEY (id_empleado) REFERENCES tb_empleados(id_empleado),
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente)
);

-- Tabla de Relación entre Administradores y Clientes
CREATE TABLE tb_administrador_cliente (
   id_administrador INT,
   id_cliente INT,
   PRIMARY KEY (id_administrador, id_cliente),
   FOREIGN KEY (id_administrador) REFERENCES tb_administrador(id_administrador),
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente)
);
 
-- Tabla de Comprobante de Crédito Fiscal
CREATE TABLE tb_comprobante_credito_fiscal (
   id_factura INT PRIMARY KEY AUTO_INCREMENT,
   id_cliente INT,
   descripcion VARCHAR(500) NOT NULL,
   id_servicio INT,
   id_administrador INT,
   id_empleado INT,
   tipo_servicio ENUM('Credito Fiscal', 'Factura Consumidor Final', 'Factura Sujeto Excluido', 'Otro') NOT NULL,
   monto DECIMAL(10, 2),
   fecha_emision DATE,
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente),
   FOREIGN KEY (id_servicio) REFERENCES tb_servicios(id_servicio),
   FOREIGN KEY (id_empleado) REFERENCES tb_empleados(id_empleado),
   FOREIGN KEY (id_administrador) REFERENCES tb_administrador(id_administrador)
);
 
-- Tabla de Factura Sujeto Excluido
CREATE TABLE tb_factura_sujeto_excluido (
   id_factura INT PRIMARY KEY AUTO_INCREMENT,
   id_cliente INT,
   descripcion VARCHAR(500) NOT NULL,
   id_servicio INT,
   id_administrador INT,
   id_empleado INT,
   tipo_servicio ENUM('Credito Fiscal', 'Factura Consumidor Final', 'Factura Sujeto Excluido', 'Otro') NOT NULL,
   monto DECIMAL(10, 2) NOT NULL,
   fecha_emision DATE,
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente),
   FOREIGN KEY (id_servicio) REFERENCES tb_servicios(id_servicio),
   FOREIGN KEY (id_empleado) REFERENCES tb_empleados(id_empleado),
   FOREIGN KEY (id_administrador) REFERENCES tb_administrador(id_administrador)
);
 
-- Tabla de Factura de Consumidor Final
CREATE TABLE tb_factura_consumidor_final (
   id_factura INT PRIMARY KEY AUTO_INCREMENT,
   id_cliente INT,
   descripcion VARCHAR(500) NOT NULL,
   id_servicio INT,
   id_administrador INT,
   id_empleado INT,
   tipo_servicio ENUM('Credito Fiscal', 'Factura Consumidor Final', 'Factura Sujeto Excluido', 'Otro') NOT NULL,
   monto DECIMAL(10, 2),
   fecha_emision DATE,
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente),
   FOREIGN KEY (id_servicio) REFERENCES tb_servicios(id_servicio),
   FOREIGN KEY (id_empleado) REFERENCES tb_empleados(id_empleado),
   FOREIGN KEY (id_administrador) REFERENCES tb_administrador(id_administrador)
);

/*Vista para tabla sujeto excluido */  
CREATE VIEW vista_tb_factura_sujeto_excluido AS
    SELECT
        dt.id_factura,
        dt.descripcion,
        dt.id_servicio,
        dt.tipo_servicio,
        dt.monto,
        dt.id_cliente,
        dt.fecha_emision,
        tg.nombre_cliente,
        tg.apellido_cliente,
        tg.email_cliente,
        tg.telefono_cliente,
        tg.dui_cliente,
        tg.direccion_cliente,
        tg.municipio_cliente,
        tg.departamento_cliente
FROM tb_factura_sujeto_excluido dt
INNER JOIN
    tb_clientes tg ON dt.id_cliente = tg.id_cliente;
  
/*Vista para tabla consumidor final */    
CREATE VIEW vista_tb_factura_consumidor_final AS
    SELECT
        dt.id_factura,
        dt.descripcion,
        dt.id_servicio,
        dt.tipo_servicio,
        dt.monto,
        dt.id_cliente,
        dt.fecha_emision,
        tg.nombre_cliente,
        tg.apellido_cliente,
        tg.email_cliente,
        tg.telefono_cliente,
        tg.dui_cliente,
        tg.direccion_cliente,
        tg.municipio_cliente,
        tg.departamento_cliente
FROM tb_factura_consumidor_final dt
INNER JOIN
    tb_clientes tg ON dt.id_cliente = tg.id_cliente;

/*Vista para tabla comprobante de credito fiscal */    
CREATE VIEW vista_tb_comprobante_credito_fiscal AS
    SELECT
        dt.id_factura,
        dt.descripcion,
        dt.id_servicio,
        dt.tipo_servicio,
        dt.monto,
        dt.id_cliente,
        dt.fecha_emision,
        tg.nombre_cliente,
        tg.apellido_cliente,
        tg.email_cliente,
        tg.telefono_cliente,
        tg.dui_cliente,
        tg.direccion_cliente,
        tg.municipio_cliente,
        tg.departamento_cliente
FROM tb_comprobante_credito_fiscal dt
INNER JOIN
    tb_clientes tg ON dt.id_cliente = tg.id_cliente;    
    



DELIMITER $$

CREATE PROCEDURE set_validator(
    p_email_administrador VARCHAR(100)
)
BEGIN
    DECLARE v_current_datetime DATETIME;
    SET v_current_datetime = NOW();
    UPDATE tb_administrador
    SET validator = DATE_ADD(v_current_datetime, INTERVAL 1 DAY)
    WHERE email_administrador = p_email_administrador;
END $$

DELIMITER ;

DELIMITER $$ 

CREATE PROCEDURE clear_past_validators()
BEGIN
    UPDATE tb_administrador
    SET validator = NULL,
    validatorcount = 0
    WHERE validator <= NOW();
END $$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE update_validatorcount(
    p_email_administrador VARCHAR(100)
)
BEGIN
    DECLARE v_current_validatorcount INT;

    -- Obtener el valor actual de validatorcount
    SELECT COALESCE(validatorcount, 0) INTO v_current_validatorcount
    FROM tb_administrador
    WHERE email_administrador = p_email_administrador;

    -- Incrementar validatorcount
    SET v_current_validatorcount = v_current_validatorcount + 1;

    -- Actualizar el valor en la tabla
    UPDATE tb_administrador
    SET validatorcount = v_current_validatorcount
    WHERE email_administrador = p_email_administrador;

    -- Llamar a set_validator si es necesario
    IF v_current_validatorcount >= 3 THEN
        CALL set_validator(p_email_administrador);
    END IF;
END $$

DELIMITER ;

DELIMITER //

CREATE FUNCTION verificar_cambio_contraseña(id INT)
RETURNS INT
BEGIN
    DECLARE fecha_cambio DATETIME;

    SELECT cambio_contraseña INTO fecha_cambio
    FROM tb_administrador
    WHERE id_administrador = id;

    IF fecha_cambio <= NOW() THEN
        RETURN 1;
    ELSE
        RETURN 0;
    END IF;
END //

DELIMITER ;
