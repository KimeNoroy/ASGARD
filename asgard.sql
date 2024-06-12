-- Eliminar la base de datos si existe y crearla nuevamente
DROP DATABASE IF EXISTS asgard;
CREATE DATABASE IF NOT EXISTS asgard;
USE asgard;
 
-- Tabla de Departamentos
CREATE TABLE tb_departamentos (
   id_departamento INT PRIMARY KEY AUTO_INCREMENT,
   nombre_departamento VARCHAR(100) NOT NULL
);
 
 CREATE TABLE tb_administrador (
  id_administrador int(10) UNSIGNED NOT NULL,
  nombre_administrador varchar(50) NOT NULL,
  apellido_administrador varchar(50) NOT NULL,
  email_administrador varchar(100) NOT NULL,
  contraseña_administrador varchar(100) NOT NULL,
);

-- Tabla de Clientes
CREATE TABLE tb_clientes (
   id_cliente INT PRIMARY KEY AUTO_INCREMENT,
   nombre_cliente VARCHAR(100) NOT NULL,
   apellido_cliente VARCHAR(100) NOT NULL,
   email_cliente VARCHAR(100) UNIQUE,
   contraseña_cliente VARCHAR(50) NOT NULL,
   telefono VARCHAR(15) UNIQUE,
   dui_cliente VARCHAR(10) UNIQUE,
   nit_cliente VARCHAR(100) UNIQUE,
   id_departamento INT,
   FOREIGN KEY (id_departamento) REFERENCES tb_departamentos(id_departamento),
   CHECK (LENGTH(dui_cliente) = 10),
   CHECK (LENGTH(telefono) <= 15)
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
   tipo_servicio ENUM('Credito Fiscal', 'Factura Comercial', 'Otro') NOT NULL,
   precio DECIMAL(10, 2) NOT NULL CHECK (precio >= 0)
);
 
-- Tabla de Relación entre Clientes y Servicios
CREATE TABLE tb_cliente_servicio (
   id_cliente INT,
   id_servicio INT,
   PRIMARY KEY (id_cliente, id_servicio),
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente),
   FOREIGN KEY (id_servicio) REFERENCES tb_servicios(id_servicio)
);
 
-- Tabla de Empleados
CREATE TABLE tb_empleados (
   id_empleado INT PRIMARY KEY AUTO_INCREMENT,
   nombres_empleado VARCHAR(100) NOT NULL,
   apellidos_empleado VARCHAR(100) NOT NULL,
   dui_empleado VARCHAR(100) UNIQUE,
   contrasena VARCHAR(100) NOT NULL
);
 
-- Tabla de Relación entre Empleados y Clientes
CREATE TABLE tb_empleado_cliente (
   id_empleado INT,
   id_cliente INT,
   PRIMARY KEY (id_empleado, id_cliente),
   FOREIGN KEY (id_empleado) REFERENCES tb_empleados(id_empleado),
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente)
);
 
-- Tabla de Comprobante de Crédito Fiscal
CREATE TABLE tb_comprobante_credito_fiscal (
   id_comprobante INT PRIMARY KEY AUTO_INCREMENT,
   id_cliente INT,
   id_servicio INT,
   nit VARCHAR(100) UNIQUE,
   nombre VARCHAR(100) NOT NULL,
   nrc VARCHAR(100),
   giro VARCHAR(255),
   departamento VARCHAR(100),
   municipio VARCHAR(100),
   direccion VARCHAR(255),
   email VARCHAR(100),
   telefono VARCHAR(15),
   dui VARCHAR(10),
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente),
   FOREIGN KEY (id_servicio) REFERENCES tb_servicios(id_servicio)
);
 
-- Tabla de Factura Sujeto Excluido
CREATE TABLE tb_factura_sujeto_excluido (
   id_factura INT PRIMARY KEY AUTO_INCREMENT,
   id_cliente INT,
   nit_cliente VARCHAR(100),
   nombre_cliente VARCHAR(100) NOT NULL,
   direccion_cliente VARCHAR(255),
   departamento_cliente VARCHAR(100),
   municipio_cliente VARCHAR(100),
   email_cliente VARCHAR(100),
   telefono_cliente VARCHAR(15),
   dui_cliente VARCHAR(10),
   id_servicio INT,
   id_empleado INT,
   tipo_servicio ENUM('Credito Fiscal', 'Factura Consumidor Final', 'Factura Sujeto Excluido') NOT NULL,
   monto DECIMAL(10, 2),
   fecha_emision DATETIME,
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente),
   FOREIGN KEY (id_servicio) REFERENCES tb_servicios(id_servicio),
   FOREIGN KEY (id_empleado) REFERENCES tb_empleados(id_empleado)
);
 
-- Tabla de Factura de Consumidor Final
CREATE TABLE tb_factura_consumidor_final (
   id_factura INT PRIMARY KEY AUTO_INCREMENT,
   id_cliente INT,
   nit_cliente VARCHAR(100),
   nombre_cliente VARCHAR(100) NOT NULL,
   direccion_cliente VARCHAR(255),
   departamento_cliente VARCHAR(100),
   municipio_cliente VARCHAR(100),
   email_cliente VARCHAR(100),
   telefono_cliente VARCHAR(15),
   dui_cliente VARCHAR(10),
   id_servicio INT,
   id_empleado INT,
   tipo_servicio ENUM('Credito Fiscal', 'Factura Consumidor Final', 'Factura Sujeto Excluido') NOT NULL,
   monto DECIMAL(10, 2),
   fecha_emision DATETIME,
   FOREIGN KEY (id_cliente) REFERENCES tb_clientes(id_cliente),
   FOREIGN KEY (id_servicio) REFERENCES tb_servicios(id_servicio),
   FOREIGN KEY (id_empleado) REFERENCES tb_empleados(id_empleado)
);
 
