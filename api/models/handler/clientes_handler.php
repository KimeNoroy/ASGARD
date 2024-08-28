<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class ClienteHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $correo = null;
    protected $direccion = null;
    protected $departamento = null;
    protected $municipio = null;
    protected $dui = null;
    protected $nit = null;
    protected $telefono = null;



    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, email_cliente, telefono, direccion_cliente, departamento_cliente, municipio_cliente, dui_cliente, nit_cliente
                FROM tb_clientes
                WHERE apellido_cliente LIKE ? OR nombre_cliente LIKE ?
                ORDER BY apellido_cliente';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_clientes(nombre_cliente, apellido_cliente, email_cliente, direccion_cliente, departamento_cliente, municipio_cliente, telefono_cliente, dui_cliente, nit_cliente)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->direccion, $this->departamento, $this->municipio, $this->telefono, $this->dui, $this->nit);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_cliente, nombre_cliente, apellido_cliente, email_cliente, dui_cliente, nit_cliente, direccion_cliente, departamento_cliente, municipio_cliente, telefono_cliente
                FROM tb_clientes
                ORDER BY apellido_cliente';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = ' SELECT id_cliente, nombre_cliente, apellido_cliente, email_cliente, direccion_cliente, departamento_cliente, municipio_cliente, telefono_cliente, dui_cliente, nit_cliente
            FROM tb_clientes
            WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_clientes
                SET nombre_cliente = ?, apellido_cliente = ?, email_cliente = ?, dui_cliente = ?, telefono = ?, nit_cliente = ?
                WHERE id_cliente = ?';
        $params = array($this->nombre, $this->apellido, $this->correo, $this->dui, $this->direccion, $this->departamento, $this->municipio, $this->telefono, $this->nit, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_cliente
                FROM tb_clientes
                WHERE dui_cliente = ? OR email_cliente = ?';
        $params = array($value, $value);
        return Database::getRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_clientes
                WHERE id_cliente = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    // Función para predecir la cantidad de clientes por mes
    public function graficoPrediccionClientes()
    {
        // Estimado fijo de clientes por mes
        $estimadoClientesPorMes = 10;

        // Devolvemos la predicción para el próximo mes
        return $estimadoClientesPorMes;
    }

}