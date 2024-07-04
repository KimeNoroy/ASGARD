<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla SUJETO EXCLUIDO.
 */
class factura_consumidor_final_handler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */

    protected $id_factura = null,
    protected $id_cliente = null;
    protected $nit_cliente = null;
    protected $nombre_cliente = null;
    protected $direccion_cliente = null;
    protected $departamento_cliente = null;
    protected $email_cliente = null;
    protected $telefono_cliente = null;
    protected $dui_cliente = null;
    protected $id_servicio = null;
    protected $id_empleado = null;
    protected $tipo_servicio = null;
    protected $fecha_emision = null;
    


    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar usuarios.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, id_empleado, tipo_servivio, fecha_emision
                FROM tb_factura_consumidor_final
                WHERE nombre_cliente LIKE ?
                ORDER BY nombre_cliente';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Método para crear un nuevo usuario.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_factura_consumidor_final(id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, id_empleado, tipo_servivio, fecha_emision)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array(

            $this->id_factura,
            $this->id_cliente,
            $this->nit_cliente,
            $this->nombre_cliente,
            $this->direccion_cliente,
            $this->departamento_cliente,
            $this->municipio_cliente,
            $this->email_cliente,
            $this->telefono_cliente,
            $this->dui_cliente,
            $this->id_servicio,
            $this->id_empleado,
            $this->tipo_servicio
            $this->fecha_emision,
        );
        return Database::executeRow($sql, $params);
    }

    // Método para leer todos los usuarios.
    public function readAll()
    {
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, id_empleado, tipo_servivio, fecha_emision
                FROM tb_factura_consumidor_final
                ORDER BY nombre_cliente';
        return Database::getRows($sql);
    }

    // Método para leer un usuario específico.
    public function readOne()
    {
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, id_empleado, tipo_servivio, fecha_emision
                FROM tb_factura_consumidor_final
                WHERE id_cliente = ?';
        $params = array($this->id_cliente);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_factura_consumidot_final
                SET nombre_cliente = ?, email_cliente = ?, dui_cliente = ?, nit_cliente = ?, telefono_cliente = ?, direccion_cliente = ?, departamento_cliente = ?, municipio_cliente = ?, tipo_servicio = ?
                WHERE id_cliente = ?';
        $params = array(
            $this->nombre_cliente,
            $this->email_cliente,
            $this->dui_cliente,
            $this->nit_cliente,
            $this->telefono_cliente,
            $this->direccion_cliente,
            $this->departamento_cliente,
            $this->municipio_cliente,
            $this->tipo_servicio,
            $this->id_cliente
        );
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_factura_consumidor_final
                WHERE id_cliente = ?';
        $params = array($this->id_cliente);
        return Database::executeRow($sql, $params);
    }

    // Método para comprobar duplicados.
    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_cliente
                FROM tb_factura_consumidor_final
                WHERE dui_cliente = ? OR nit_cliente = ? OR email_cliente = ? OR telefono_cliente = ?';
        $params = array($value, $value, $value, $value);
        return Database::getRow($sql, $params);
    }

    // Método para comprobar duplicados por valor excluyendo un ID.
    public function checkDuplicateWithId($value)
    {
        $sql = 'SELECT id_cliente
                FROM tb_factura_consumidor_final
                WHERE (dui_cliente = ? OR nit_cliente = ? OR email_cliente = ? OR telefono_cliente = ?) AND id_cliente != ?';
        $params = array($value, $value, $value, $value, $this->id_cliente);
        return Database::getRow($sql, $params);
    }

}