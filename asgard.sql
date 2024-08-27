DROP DATABASE IF EXISTS asgard;
CREATE DATABASE IF NOT EXISTS asgard;
USE asgard;

CREATE TABLE tb_administrador (
  id_administrador INT PRIMARY KEY AUTO_INCREMENT,
  nombre_administrador VARCHAR(50) NOT NULL,
  apellido_administrador VARCHAR(50) NOT NULL,
  email_administrador VARCHAR(100) NOT NULL,
  contraseña_administrador VARCHAR(100) NOT NULL
);

-- Tabla de Clientes
CREATE TABLE tb_clientes (
   id_cliente INT PRIMARY KEY AUTO_INCREMENT,
   nombre_cliente VARCHAR(100) NOT NULL,
   apellido_cliente VARCHAR(100) NOT NULL,
   email_cliente VARCHAR(100) UNIQUE,
   dui_cliente VARCHAR(10) UNIQUE,
   nit_cliente VARCHAR(100) UNIQUE,
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
   id_cliente INT,
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
   monto DECIMAL(10, 2),
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
        tg.nit_cliente,
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
        tg.nit_cliente,
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
        tg.nit_cliente,
        tg.direccion_cliente,
        tg.municipio_cliente,
        tg.departamento_cliente
FROM tb_comprobante_credito_fiscal dt
INNER JOIN
    tb_clientes tg ON dt.id_cliente = tg.id_cliente;    
    

/*inserts a tablas */

INSERT INTO tb_clientes (nombre_cliente, apellido_cliente, email_cliente, dui_cliente, nit_cliente, direccion_cliente, departamento_cliente, municipio_cliente, telefono_cliente) VALUES
('Juan', 'Pérez', 'juan.perez@example.com', '001234567-8', '001-123456-123-4', 'Calle Principal 123', 'San Salvador', 'San Salvador', '72123456'),
('Ana', 'Gómez', 'ana.gomez@example.com', '002345678-9', '002-234567-234-5', 'Avenida Central 456', 'La Libertad', 'Santa Tecla', '72234567'),
('Carlos', 'López', 'carlos.lopez@example.com', '003456789-0', '003-345678-345-6', 'Boulevard Norte 789', 'Santa Ana', 'Santa Ana', '72345678'),
('María', 'Hernández', 'maria.hernandez@example.com', '004567890-1', '004-456789-456-7', 'Calle Sur 321', 'Sonsonate', 'Sonsonate', '72456789'),
('Pedro', 'Martínez', 'pedro.martinez@example.com', '005678901-2', '005-567890-567-8', 'Avenida Este 654', 'San Miguel', 'San Miguel', '72567890');
SELECT * FROM tb_clientes;

INSERT INTO tb_servicios (nombre_servicio, descripcion, id_cliente) VALUES
('Consultoría', 'Servicio de consultoría empresarial', 1),
('Desarrollo Web', 'Creación de sitios web personalizados', 2),
('Soporte Técnico', 'Soporte y mantenimiento de sistemas informáticos', 3),
('Marketing Digital', 'Estrategias de marketing online', 4),
('Capacitación', 'Cursos y talleres de formación profesional', 5);
SELECT * FROM tb_servicios;

INSERT INTO tb_empleados (nombres_empleado, apellidos_empleado, dui_empleado) VALUES
('Luis', 'Fernández', '001234567-8'),
('Sofía', 'Ramírez', '002345678-9'),
('Diego', 'Torres', '003456789-0'),
('Laura', 'Vásquez', '004567890-1'),
('Andrés', 'Morales', '005678901-2');
SELECT * FROM tb_empleados;

SELECT *  FROM tb_administrador;


