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
    protected $id = null;
    protected $nombre = null;
    protected $correo = null;
    protected $dui = null;
    protected $nit = null;
    protected $telefono = null;
    protected $direccion = null;
    protected $departamento = null;
    protected $municipio = null;
    protected $tipo_servicio = null;
    protected $precio_servicio = null;

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar usuarios.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, id_empleado, tipo_servicio, monto, fecha_emision
        FROM tb_factura_sujeto_excluido
        WHERE id_factura LIKE ?
        ORDER BY id_factura';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Método para crear un nuevo usuario.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_factura_sujeto_excluido(id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, id_empleado, tipo_servicio, monto, fecha_emision)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->correo, $this->dui, $this->nit, $this->telefono, $this->direccion, $this->departamento, $this->municipio, $this->tipo_servicio, $this->precio_servicio);
        return Database::executeRow($sql, $params);
    }

    // Método para leer todos los usuarios.
    public function readAll()
    {
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, id_empleado, tipo_servicio, monto, fecha_emision
                FROM tb_factura_sujeto_excluido
                ORDER BY nombre_cliente';
        return Database::getRows($sql);
    }

    // Método para leer un usuario específico.
    public function readOne()
    {
        $sql = 'SELECT id_factura, id_cliente, nit_cliente, nombre_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, id_servicio, id_empleado, tipo_servicio, monto, fecha_emision
                FROM tb_factura_sujeto_excluido
                WHERE id_factura = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_factura_sujeto_excluido
                SET id_factura = ?, id_cliente = ?, nit_cliente = ?, nombre_cliente = ?, direccion_cliente = ?, departamento_cliente = ?, municipio_cliente = ?, email_cliente = ?, telefono_cliente = ?, dui_cliente = ?, id_servicio = ?, id_empleado = ?, tipo_servicio = ?, monto = ?, fecha_emision = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->correo, $this->dui, $this->nit, $this->telefono, $this->direccion, $this->departamento, $this->municipio, $this->tipo_servicio, $this->precio_servicio);
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_factura_sujeto_excluido
                WHERE id_factura = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Método para comprobar duplicados.
    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_factura
                FROM tb_factura_sujeto_excluido
                WHERE dui_cliente = ? OR nit_cliente =? OR email_cliente = ? OR telefono_cliente = ?';
        $params = array($value, $value, $value, $value);
        return Database::getRow($sql, $params);
    }

    // Método para comprobar duplicados por valor excluyendo un ID.
    public function checkDuplicateWithId($value)
    {
        $sql = 'SELECT id_factura
                FROM tb_factura_sujeto_excluido
                WHERE (dui_cliente = ? OR nit_cliente =? OR email_cliente = ? OR telefono_cliente = ?) AND id_factura != ?';
        $params = array($value, $value, $value, $value, $this->id);
        return Database::getRow($sql, $params);
    }

}