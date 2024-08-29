<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');

/*
*   Clase para manejar el comportamiento de los datos de la tabla SERVICIOS
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
    s.id_servicio,
    s.nombre_servicio,
    COALESCE(fc.count_comprobante_credito_fiscal, 0) +
    COALESCE(fs.count_factura_sujeto_excluido, 0) +
    COALESCE(fcf.count_factura_consumidor_final, 0) AS total_usos
FROM tb_servicios s
LEFT JOIN (
    SELECT id_servicio, COUNT(*) AS count_comprobante_credito_fiscal
    FROM tb_comprobante_credito_fiscal
    GROUP BY id_servicio
) fc ON s.id_servicio = fc.id_servicio
LEFT JOIN (
    SELECT id_servicio, COUNT(*) AS count_factura_sujeto_excluido
    FROM tb_factura_sujeto_excluido
    GROUP BY id_servicio
) fs ON s.id_servicio = fs.id_servicio
LEFT JOIN (
    SELECT id_servicio, COUNT(*) AS count_factura_consumidor_final
    FROM tb_factura_consumidor_final
    GROUP BY id_servicio
) fcf ON s.id_servicio = fcf.id_servicio;';
        return Database::getRows($sql);
    }

    // Nuevo método para obtener la cantidad de facturas emitidas por mes
    public function facturasPorMes()
    {
        $sql = 'SELECT 
                    MONTH(fecha_emision) AS mes, 
                    COUNT(*) AS total_facturas 
                FROM (
                    SELECT fecha_emision FROM tb_comprobante_credito_fiscal
                    UNION ALL
                    SELECT fecha_emision FROM tb_factura_sujeto_excluido
                    UNION ALL
                    SELECT fecha_emision FROM tb_factura_consumidor_final
                ) AS todas_facturas
                GROUP BY mes
                ORDER BY mes';
        return Database::getRows($sql);
    }
    /*
    Función con la consulta para obtener los datos del gráfico Clientes por Departamento
*/
    public function clientesPorDepartamento() 
    {
        $sql = 'SELECT COUNT(CLI.id_cliente) AS CantidadClientes, DEP.nombre_departamento
            FROM tb_clientes AS CLI
            INNER JOIN tb_departamentos AS DEP ON CLI.id_departamento = DEP.id_departamento
            GROUP BY DEP.nombre_departamento
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