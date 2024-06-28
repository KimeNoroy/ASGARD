<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla SUJETO EXCLUIDO.
 */
class factura_sujeto_excluido_handler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */
    protected $id_cliente = null;
    protected $nombre_cliente = null;
    protected $email_cliente = null;
    protected $dui_cliente = null;
    protected $nit_cliente = null;
    protected $telefono_cliente = null;
    protected $direccion_cliente = null;
    protected $departamento_cliente = null;
    protected $municipio_cliente = null;
    protected $tipo_servicio = null;
    protected $monto = null;

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar usuarios.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, tipo_servicio, monto, fecha_emision
                FROM tb_factura_sujeto_excluido
                WHERE nombre_cliente LIKE ?
                ORDER BY nombre_cliente';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Método para crear un nuevo usuario.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_factura_sujeto_excluido(id_cliente, nombre_cliente, email_cliente, dui_cliente, nit_cliente, telefono_cliente, direccion_cliente, departamento_cliente, municipio_cliente, id_servicio, tipo_servicio, monto)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            $this->id_cliente,
            $this->nombre_cliente,
            $this->email_cliente,
            $this->dui_cliente,
            $this->nit_cliente,
            $this->telefono_cliente,
            $this->direccion_cliente,
            $this->departamento_cliente,
            $this->municipio_cliente,
            $this->tipo_servicio,
            $this->monto
        );
        return Database::executeRow($sql, $params);
    }

    // Método para leer todos los usuarios.
    public function readAll()
    {
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, tipo_servicio, monto, fecha_emision
                FROM tb_factura_sujeto_excluido
                ORDER BY nombre_cliente';
        return Database::getRows($sql);
    }

    // Método para leer un usuario específico.
    public function readOne()
    {
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, tipo_servicio, monto, fecha_emision
                FROM tb_factura_sujeto_excluido
                WHERE id_cliente = ?';
        $params = array($this->id_cliente);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_factura_sujeto_excluido
                SET nombre_cliente = ?, email_cliente = ?, dui_cliente = ?, nit_cliente = ?, telefono_cliente = ?, direccion_cliente = ?, departamento_cliente = ?, municipio_cliente = ?, tipo_servicio = ?, monto = ?
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
            $this->monto,
            $this->id_cliente
        );
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_factura_sujeto_excluido
                WHERE id_cliente = ?';
        $params = array($this->id_cliente);
        return Database::executeRow($sql, $params);
    }

    // Método para comprobar duplicados.
    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_cliente
                FROM tb_factura_sujeto_excluido
                WHERE dui_cliente = ? OR nit_cliente = ? OR email_cliente = ? OR telefono_cliente = ?';
        $params = array($value, $value, $value, $value);
        return Database::getRow($sql, $params);
    }

    // Método para comprobar duplicados por valor excluyendo un ID.
    public function checkDuplicateWithId($value)
    {
        $sql = 'SELECT id_cliente
                FROM tb_factura_sujeto_excluido
                WHERE (dui_cliente = ? OR nit_cliente = ? OR email_cliente = ? OR telefono_cliente = ?) AND id_cliente != ?';
        $params = array($value, $value, $value, $value, $this->id_cliente);
        return Database::getRow($sql, $params);
    }

}
