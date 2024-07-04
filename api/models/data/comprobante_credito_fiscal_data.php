<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/comprobante_handler.php');

/*
*   Clase para manejar el encapsulamiento de los datos de la tabla COMPROBANTE_CREDITO_FISCAL.
*/
class ComprobanteCreditoFiscalData extends ComprobanteHandler
{
    // Atributo genérico para manejo de errores.
    private $data_error = null;

    /*
    *   Métodos para validar y establecer los datos.
    */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del comprobante es incorrecto';
            return false;
        }
    }

    public function setNombre($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El nombre debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nombre = $value;
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setNit($value, $min = 14, $max = 14)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'El NIT no es válido';
            return false;
        } elseif (!Validator::validateLength($value, $min, $max)) {
            $this->data_error = 'El NIT debe tener una longitud de ' . $min . ' caracteres';
            return false;
        } elseif($this->checkDuplicate($value)) {
            $this->data_error = 'El NIT ingresado ya existe';
            return false;
        } else {
            $this->nit = $value;
            return true;
        }
    }

    public function setGiro($value, $min = 2, $max = 100)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'El giro contiene caracteres prohibidos';
            return false;
        } elseif(Validator::validateLength($value, $min, $max)) {
            $this->giro = $value;
            return true;
        } else {
            $this->data_error = 'El giro debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }

    /*
    *   Métodos para realizar las operaciones CRUD.
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_comprobante, nombre, nit, giro
                FROM comprobante_credito_fiscal
                WHERE nombre ILIKE ?';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO comprobante_credito_fiscal(nombre, nit, giro)
                VALUES(?, ?, ?)';
        $params = array($this->nombre, $this->nit, $this->giro);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_comprobante, nombre, nit, giro
                FROM comprobante_credito_fiscal';
        $params = null;
        return Database::getRows($sql, $params);
    }

    public function readOne()
    {
        $sql = 'SELECT id_comprobante, nombre, nit, giro
                FROM comprobante_credito_fiscal
                WHERE id_comprobante = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE comprobante_credito_fiscal
                SET nombre = ?, nit = ?, giro = ?
                WHERE id_comprobante = ?';
        $params = array($this->nombre, $this->nit, $this->giro, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM comprobante_credito_fiscal
                WHERE id_comprobante = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
?>
