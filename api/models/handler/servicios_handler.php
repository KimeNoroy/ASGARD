<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
*	Clase para manejar el comportamiento de los datos de la tabla SERVICIOS
*/
class ServiciosHandler
{



    public function readAll()
    {
        $sql = 'SELECT id_servicio, nombre_servicio, descripcion, id_cliente
                FROM tb_servicios
                ORDER BY nombre_servicio';
        return Database::getRows($sql);
    }

    

    public function serviciosOfrecidos()
    {
        $sql = 'SELECT 
    tipo_servicio,
    COUNT(*) AS cantidad
FROM 
    tb_servicios
GROUP BY 
    tipo_servicio;
';
        return Database::getRows($sql);
    }
}