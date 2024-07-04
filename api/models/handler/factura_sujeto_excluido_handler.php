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
    protected $id= null;
    protected $nombre = null;
    protected $apellido = null;
    protected $email = null;
    protected $dui = null;
    protected $nit = null;
    protected $telefono = null;
    protected $direccion = null;
    protected $departamento = null;
    protected $municipio = null;
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
        $sql = 'SELECT id_factura, nit_cliente, nombre_cliente, apellido_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, tipo_servicio, monto, fecha_emision, descripcion
                FROM tb_factura_sujeto_excluido
                WHERE nombre_cliente LIKE ?
                ORDER BY nombre_cliente';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Método para crear un nuevo usuario.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_factura_sujeto_excluido(id_factura, nit_cliente, nombre_cliente, apellido_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, tipo_servicio, monto, fecha_emision, descripcion)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            $this->id,
            $this->nombre,
            $this->apellido,
            $this->email,
            $this->dui,
            $this->nit,
            $this->telefono,
            $this->direccion,
            $this->departamento,
            $this->municipio,
            $this->tipo_servicio,
            $this->monto,
            $this->fecha,
            $this->descripcion,
            $_SESSION['id_empleado']
        );
        return Database::executeRow($sql, $params);
    }

    // Método para leer todos los usuarios.
    public function readAll()
    {
        $sql = 'SELECT id_factura, nit_cliente, nombre_cliente, apellido_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, tipo_servicio, monto, fecha_emision, descripcion
                FROM tb_factura_sujeto_excluido
                ORDER BY nombre_cliente';
        return Database::getRows($sql);
    }

    // Método para leer un usuario específico.
    public function readOne()
    {
        $sql = 'SELECT id_factura, nit_cliente, nombre_cliente, apellido_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, tipo_servicio, monto, fecha_emision, descripcion
                FROM tb_factura_sujeto_excluido
                WHERE id_factura = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_factura_sujeto_excluido
                SET nombre_cliente = ?, apellido_cliente = ?, email_cliente = ?, dui_cliente = ?, nit_cliente = ?, telefono_cliente = ?, direccion_cliente = ?, departamento_cliente = ?, municipio_cliente = ?, tipo_servicio = ?, monto = ?,  descripcion = ?,  fecha_emision = ?
                WHERE id_factura = ?';
        $params = array(
            $this->id, $this->nombre, $this->apellido, $this->email, $this->dui, $this->nit, $this->telefono, $this->direccion, $this->departamento, $this->municipio, $this->tipo_servicio, $this->monto, $this->descripcion, $this->fecha
        );
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_factura_sujeto_excluido
                WHERE id_factura = ?';
        $params = array($this->id_factura);
        return Database::executeRow($sql, $params);
    }

    // Método para comprobar duplicados.
    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_factura
                FROM tb_factura_sujeto_excluido
                WHERE dui_cliente = ? OR nit_cliente = ? OR email_cliente = ? OR telefono_cliente = ?';
        $params = array($value, $value, $value, $value);
        return Database::getRow($sql, $params);
    }

    // Método para comprobar duplicados por valor excluyendo un ID.
    public function checkDuplicateWithId($value)
    {
        $sql = 'SELECT id_factura
                FROM tb_factura_sujeto_excluido
                WHERE (dui_cliente = ? OR nit_cliente = ? OR email_cliente = ? OR telefono_cliente = ?) AND id_factura != ?';
        $params = array($value, $value, $value, $value, $this->id_factura);
        return Database::getRow($sql, $params);
    }

}
