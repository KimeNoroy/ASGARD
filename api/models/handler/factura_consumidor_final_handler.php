<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla SUJETO EXCLUIDO.
 */
class FacturaConsumidorFinalHandler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */
    protected $id= null;
    protected $id_cliente = null;
    protected $id_servicio = null;
    protected $tipo_servicio = null;
    protected $monto = null;
    protected $descripcion = null;
    protected $fecha = null;

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar usuarios.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_factura, nombre_cliente, apellido_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, tipo_servicio, monto, fecha_emision, descripcion
                FROM tb_factura_consumidor_final
                WHERE nombre_cliente LIKE ? OR apellido_cliente LIKE ?
                ORDER BY nombre_cliente';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Método para crear un nuevo usuario.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_factura_consumidor_final(tipo_servicio, monto, fecha_emision, descripcion, id_administrador, id_cliente, id_servicio)
                VALUES(?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            $this->tipo_servicio,
            $this->monto,
            $this->fecha,
            $this->descripcion,
            $_SESSION['idAdministrador'],
            $this->id_cliente,
            $this->id_servicio
        );
        return Database::executeRow($sql, $params);
    }

    // Método para leer todos los usuarios.
    public function readAll()
    {
        $sql = 'SELECT id_factura, nombre_cliente, apellido_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, tipo_servicio, monto, fecha_emision
                FROM vista_tb_factura_consumidor_final
                ORDER BY nombre_cliente';
        return Database::getRows($sql);
    }

   

    // Método para leer todos los clientes.
    public function readAllclientes()
    {
        $sql = 'SELECT id_cliente, nombre_cliente
                FROM tb_clientes';
        return Database::getRows($sql);
    }
    // Método para leer todos los servicios.
    public function readAllservicio()
    {
        $sql = 'SELECT id_servicio, nombre_servicio
                FROM tb_servicios';
        return Database::getRows($sql);
    }

    // Método para leer un usuario específico.
    public function readOne()
    {
        $sql = 'SELECT id_factura, id_cliente,nombre_cliente, id_servicio, tipo_servicio, monto, fecha_emision, descripcion
                FROM vista_tb_factura_consumidor_final
                WHERE id_factura = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    
    public function readFactura()
    {
        $sql = 'SELECT fse.id_factura, c.imagen_cliente, c.nombre_cliente, c.apellido_cliente, fse.id_cliente, fse.id_servicio, c.email_cliente, fse.tipo_servicio, fse.monto, fse.fecha_emision, fse.descripcion
                FROM tb_factura_sujeto_excluido fse
                INNER JOIN  tb_clientes c ON fse.id_cliente = c.id_cliente
                WHERE id_factura = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_factura_consumidor_final
                SET tipo_servicio = ?, monto = ?, fecha_emision = ?, descripcion = ?, id_administrador = ?, id_cliente = ?, id_servicio = ?
                WHERE id_factura = ?';
        $params = array(
            $this->tipo_servicio,
            $this->monto,
            $this->fecha,
            $this->descripcion,
            $_SESSION['idAdministrador'],
            $this->id_cliente,
            $this->id_servicio,
            $this->id
        );
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_factura_consumidor_final
                WHERE id_factura = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    

}