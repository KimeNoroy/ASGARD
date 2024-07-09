<?php

// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla Comprobante de Crédito Fiscal.
 */
class ComprobanteCreditoHandler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $id_cliente = null;
    protected $id_servicio = null;
    protected $nit = null;
    protected $nombre = null;
    protected $nrc = null;
    protected $giro = null;
    protected $direccion = null;
    protected $email = null;
    protected $telefono = null;
    protected $dui = null;
    protected $fecha_emision = null;

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar comprobantes de crédito fiscal.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_comprobante, id_cliente, id_servicio, nit_credito_fiscal, nombre_credito_fiscal, nrc_credito_fiscal, giro_credito_fiscal, direccion_credito_fiscal, email_credito_fiscal, telefono_credito_fiscal, dui_credito_fiscal, fecha_emision
                FROM tb_comprobante_credito_fiscal
                WHERE nombre_credito_fiscal LIKE ? 
                ORDER BY nombre_credito_fiscal';
        $params = array($value);
        return Database::getRows($sql, $params);
    }

    // Método para crear un nuevo comprobante de crédito fiscal.
    public function createRow()
{
    $sql = 'INSERT INTO tb_comprobante_credito_fiscal(id_cliente, id_servicio, id_administrador, nit_credito_fiscal, nombre_credito_fiscal, nrc_credito_fiscal, giro_credito_fiscal, direccion_credito_fiscal, email_credito_fiscal, telefono_credito_fiscal, dui_credito_fiscal, fecha_emision)
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $params = array(
        $this->id_cliente,
        $this->id_servicio,
        $_SESSION['idAdministrador'], // Asumiendo que 'idAdministrador' es una clave válida en $_SESSION
        $this->nit,
        $this->nombre,
        $this->nrc,
        $this->giro,
        $this->direccion,
        $this->email,
        $this->telefono,
        $this->dui,
        $this->fecha_emision
    );
    
    try {
        return Database::executeRow($sql, $params);
    } catch (PDOException $e) {
        // Manejo de la excepción, puedes imprimir el mensaje de error o realizar alguna acción específica.
        error_log('Error al insertar el comprobante de crédito fiscal: ' . $e->getMessage());
        return false;
    }
}

    // Método para leer todos los comprobantes de crédito fiscal.
    public function readAll()
    {
        $sql = 'SELECT id_comprobante, id_cliente, id_servicio, nit_credito_fiscal, nombre_credito_fiscal, nrc_credito_fiscal, giro_credito_fiscal, direccion_credito_fiscal, email_credito_fiscal, telefono_credito_fiscal, dui_credito_fiscal, fecha_emision
                FROM vista_tb_comprobante_credito_fiscal
                ORDER BY nombre_credito_fiscal';
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

    // Método para leer un comprobante de crédito fiscal específico.
    public function readOne()
    {
        $sql = 'SELECT id_comprobante, id_cliente, id_servicio, nit_credito_fiscal, nombre_credito_fiscal, nrc_credito_fiscal, giro_credito_fiscal, direccion_credito_fiscal, email_credito_fiscal, telefono_credito_fiscal, dui_credito_fiscal, fecha_emision
                FROM vista_tb_comprobante_credito_fiscal
                WHERE id_comprobante = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un comprobante de crédito fiscal.
    public function updateRow()
    {
        $sql = 'UPDATE tb_comprobante_credito_fiscal
                SET id_cliente = ?, id_servicio = ?, nit_credito_fiscal = ?, nombre_credito_fiscal = ?, nrc_credito_fiscal = ?, giro_credito_fiscal = ?, direccion_credito_fiscal = ?, email_credito_fiscal = ?, telefono_credito_fiscal = ?, dui_credito_fiscal = ?, fecha_emision = ?
                WHERE id_comprobante = ?';
        $params = array(
            $this->id_cliente,
            $this->id_servicio,
            $_SESSION['idAdministrador'],
            $this->nit,
            $this->nombre,
            $this->nrc,
            $this->giro,
            $this->direccion,
            $this->email,
            $this->telefono,
            $this->dui,
            $this->fecha_emision,
            $this->id
        );
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un comprobante de crédito fiscal.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_comprobante_credito_fiscal
                WHERE id_comprobante = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
?>
