-- Insertar datos en tb_administrador
INSERT INTO tb_administrador (nombre_administrador, apellido_administrador, email_administrador, contraseña_administrador)
VALUES
('gabs', 'Odinson', 'g@gmail.com', '12345678'),
('Loki', 'Laufeyson', 'loki@asgard.com', 'mischief@456'),
('Odin', 'Allfather', 'odin@asgard.com', 'allfather@789'),
('Freyja', 'Vanadis', 'freyja@asgard.com', 'love@321'),
('Balder', 'Bright', 'balder@asgard.com', 'light@654'),
('Tyr', 'Warrior', 'tyr@asgard.com', 'warrior@987'),
('Frigga', 'Queen', 'frigga@asgard.com', 'queen@321'),
('Heimdall', 'Guardian', 'heimdall@asgard.com', 'guard@456'),
('Sif', 'Shieldmaiden', 'sif@asgard.com', 'shield@789'),
('Valkyrie', 'Brunnhilde', 'valkyrie@asgard.com', 'valkyrie@123');

-- Insertar datos en tb_clientes
INSERT INTO tb_clientes (nombre_cliente, apellido_cliente, email_cliente, imagen_cliente, dui_cliente, direccion_cliente, departamento_cliente, municipio_cliente, telefono_cliente)
VALUES
('Jane', 'Doe', 'jane.doe@example.com', 'jane.jpg', '12345678', '123 Elm Street', 'Midgard', 'New York', '555-1234'),
('John', 'Smith', 'john.smith@example.com', 'john.jpg', '87654321', '456 Oak Avenue', 'Midgard', 'Los Angeles', '5355-5678'),
('Bruce', 'Wayne', 'bruce.wayne@example.com', 'bruce.jpg', '11223344', '1007 Mountain Drive', 'Gotham', 'Gotham City', '555-7890'),
('Clark', 'Kent', 'clark.kent@example.com', 'clark.jpg', '22334455', '344 Clinton Street', 'Metropolis', 'Metropolis City', '555-6789'),
('Diana', 'Prince', 'diana.prince@example.com', 'diana.jpg', '33445566', '123 Themyscira Road', 'Themyscira', 'Themyscira City', '555-5678'),
('Wade', 'Wilson', 'wade.wilson@example.com', 'wade.jpg', '44556677', '456 Deadpool Lane', 'Deadpool', 'Deadpool City', '555-4567'),
('Peter', 'Parker', 'peter.parker@example.com', 'peter.jpg', '55667788', '20 Ingram Street', 'New York', 'New York City', '555-3456'),
('Natasha', 'Romanoff', 'natasha.romanoff@example.com', 'natasha.jpg', '66778899', '14 Avengers Tower', 'New York', 'New York City', '555-2345'),
('Tony', 'Stark', 'tony.stark@example.com', 'tony.jpg', '77889900', '10880 Malibu Point', 'Malibu', 'Malibu City', '555-1230'),
('Stephen', 'Strange', 'stephen.strange@example.com', 'stephen.jpg', '88990011', '177A Bleecker Street', 'New York', 'New York City', '555-6781');

-- Insertar datos en tb_servicios
INSERT INTO tb_servicios (nombre_servicio, descripcion, id_cliente)
VALUES
('Servicio de Consultoría', 'Consultoría en IT y soporte técnico.', 1),
('Servicio de Auditoría', 'Auditoría financiera y contable.', 2),
('Desarrollo de Software', 'Desarrollo personalizado de software.', 3),
('Mantenimiento de Sistemas', 'Mantenimiento y soporte de sistemas.', 4),
('Entrenamiento en Seguridad', 'Entrenamiento en ciberseguridad.', 5),
('Asesoría Legal', 'Asesoría en temas legales.', 6),
('Servicio de Marketing', 'Marketing digital y publicidad.', 7),
('Análisis de Datos', 'Análisis y visualización de datos.', 8),
('Gestión de Proyectos', 'Gestión de proyectos y consultoría.', 9),
('Optimización de Procesos', 'Optimización de procesos empresariales.', 10);

-- Insertar datos en tb_empleados
INSERT INTO tb_empleados (nombres_empleado, apellidos_empleado, dui_empleado)
VALUES
('Odin', 'Allfather', '11122334'),
('Heimdall', 'Guardian', '11123334'),
('Thor', 'Odinson', '22334455'),
('Loki', 'Laufeyson', '33445566'),
('Freyja', 'Vanadis', '44556677'),
('Balder', 'Bright', '55667788'),
('Tyr', 'Warrior', '66778899'),
('Frigga', 'Queen', '77889900'),
('Sif', 'Shieldmaiden', '88990011'),
('Valkyrie', 'Brunnhilde', '99001122');

-- Insertar datos en tb_empleado_cliente
INSERT INTO tb_empleado_cliente (id_empleado, id_cliente)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- Insertar datos en tb_administrador_cliente
INSERT INTO tb_administrador_cliente (id_administrador, id_cliente)
VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- Insertar datos en tb_comprobante_credito_fiscal
INSERT INTO tb_comprobante_credito_fiscal (id_cliente, descripcion, id_servicio, id_administrador, id_empleado, tipo_servicio, monto, fecha_emision)
VALUES
(1, 'Factura por servicio de consultoría', 1, 1, 1, 'Credito Fiscal', 1000.00, '2024-08-01'),
(2, 'Factura por servicio de auditoría', 2, 2, 2, 'Credito Fiscal', 1500.00, '2024-08-02'),
(3, 'Factura por desarrollo de software', 3, 1, 3, 'Credito Fiscal', 2000.00, '2024-08-03'),
(4, 'Factura por mantenimiento de sistemas', 4, 1, 4, 'Credito Fiscal', 800.00, '2024-08-04'),
(5, 'Factura por entrenamiento en seguridad', 5, 2, 5, 'Credito Fiscal', 1200.00, '2024-08-05'),
(6, 'Factura por asesoría legal', 6, 2, 6, 'Credito Fiscal', 1400.00, '2024-08-06'),
(7, 'Factura por servicio de marketing', 7, 3, 7, 'Credito Fiscal', 1700.00, '2024-08-07'),
(8, 'Factura por análisis de datos', 8, 3, 8, 'Credito Fiscal', 1600.00, '2024-08-08'),
(9, 'Factura por gestión de proyectos', 9, 4, 9, 'Credito Fiscal', 1300.00, '2024-08-09'),
(10, 'Factura por optimización de procesos', 10, 4, 10, 'Credito Fiscal', 1800.00, '2024-08-10');

-- Insertar datos en tb_factura_sujeto_excluido
INSERT INTO tb_factura_sujeto_excluido (id_cliente, descripcion, id_servicio, id_administrador, id_empleado, tipo_servicio, monto, fecha_emision)
VALUES
(1, 'Factura sujeto excluido por servicio de consultoría', 1, 1, 1, 'Factura Sujeto Excluido', 2000.00, '2024-08-05'),
(2, 'Factura sujeto excluido por desarrollo de software', 3, 3, 3, 'Factura Sujeto Excluido', 2500.00, '2024-08-06'),
(3, 'Factura sujeto excluido por mantenimiento de sistemas', 4, 4, 4, 'Factura Sujeto Excluido', 900.00, '2024-08-07'),
(4, 'Factura sujeto excluido por entrenamiento en seguridad', 5, 5, 5, 'Factura Sujeto Excluido', 1500.00, '2024-08-08'),
(5, 'Factura sujeto excluido por asesoría legal', 6, 6, 6, 'Factura Sujeto Excluido', 1800.00, '2024-08-09'),
(6, 'Factura sujeto excluido por servicio de marketing', 7, 7, 7, 'Factura Sujeto Excluido', 2200.00, '2024-08-10'),
(7, 'Factura sujeto excluido por análisis de datos', 8, 8, 8, 'Factura Sujeto Excluido', 1900.00, '2024-08-11'),
(8, 'Factura sujeto excluido por gestión de proyectos', 9, 9, 9, 'Factura Sujeto Excluido', 1600.00, '2024-08-12'),
(9, 'Factura sujeto excluido por optimización de procesos', 10, 10, 10, 'Factura Sujeto Excluido', 2100.00, '2024-08-13'),
(10, 'Factura sujeto excluido por desarrollo de software', 3, 1, 3, 'Factura Sujeto Excluido', 2200.00, '2024-08-14');

-- Insertar datos en tb_factura_consumidor_final
INSERT INTO tb_factura_consumidor_final (id_cliente, descripcion, id_servicio, id_administrador, id_empleado, tipo_servicio, monto, fecha_emision)
VALUES
(1, 'Factura consumidor final por servicio de consultoría', 1, 1, 1, 'Factura Consumidor Final', 1200.00, '2024-08-15'),
(2, 'Factura consumidor final por servicio de auditoría', 2, 2, 2, 'Factura Consumidor Final', 1700.00, '2024-08-16'),
(3, 'Factura consumidor final por desarrollo de software', 3, 3, 3, 'Factura Consumidor Final', 2300.00, '2024-08-17'),
(4, 'Factura consumidor final por mantenimiento de sistemas', 4, 4, 4, 'Factura Consumidor Final', 850.00, '2024-08-18'),
(5, 'Factura consumidor final por entrenamiento en seguridad', 5, 5, 5, 'Factura Consumidor Final', 1400.00, '2024-08-19'),
(6, 'Factura consumidor final por asesoría legal', 6, 6, 6, 'Factura Consumidor Final', 1600.00, '2024-08-20'),
(7, 'Factura consumidor final por servicio de marketing', 7, 7, 7, 'Factura Consumidor Final', 2000.00, '2024-08-21'),
(8, 'Factura consumidor final por análisis de datos', 8, 8, 8, 'Factura Consumidor Final', 1800.00, '2024-08-22'),
(9, 'Factura consumidor final por gestión de proyectos', 9, 9, 9, 'Factura Consumidor Final', 1500.00, '2024-08-23'),
(10, 'Factura consumidor final por optimización de procesos', 10, 10, 10, 'Factura Consumidor Final', 1900.00, '2024-08-24');