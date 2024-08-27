<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
*	Clase para manejar el comportamiento de los datos de la tabla SERVICIOS
*/
class ServiciosHandler
{
    protected $id = null;
    protected $nombre = null;
    protected $descipcion = null;



    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_servicio, nombre_servicio
                FROM tb_servicios
                WHERE nombre_servicio LIKE ?
                ORDER BY nombre_servicio';
        $params = array($value);
        return Database::getRows($sql, $params);
    }
    public function createRow()
    {
        $sql = 'INSERT INTO tb_servicios(nombre_servicio, descripcion)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->descipcion);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_servicio, nombre_servicio, descripcion, id_cliente
                FROM tb_servicios
                ORDER BY nombre_servicio';
        return Database::getRows($sql);
    }


    public function readOne()
    {
        $sql = 'SELECT id_servicio, nombre_servicio, descripcion
                FROM tb_servicios
                WHERE id_servicio = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_servicios
                SET nombre_servicio = ?, descripcion = ?
                WHERE id_servicio = ?';
        $params = array($this->nombre, $this->descipcion, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_servicios
                WHERE id_servicio = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
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

    /*
    Función con la consulta para obtener los datos del grafico Monto total por Servicios
    */
    public function montoTotalPorServicios() 
    {
        $sql = 'SELECT (SUM(CCF.monto) + SUM(FSE.monto) + SUM(FCF.monto)) AS MontoTotal, SRV.nombre_servicio
                FROM tb_servicios AS SRV
                INNER JOIN tb_comprobante_credito_fiscal AS CCF USING(id_servicio)
                INNER JOIN tb_factura_sujeto_excluido AS FSE USING(id_servicio)
                INNER JOIN tb_factura_consumidor_final AS FCF USING(id_servicio)
                GROUP BY SRV.nombre_servicio
                ';
        return Database::getRows($sql);
    }
}