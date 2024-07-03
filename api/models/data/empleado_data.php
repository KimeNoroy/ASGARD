<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/empleado_handler.php');
/*
*	Clase para manejar el encapsulamiento de los datos de la tabla empleado.
*/
class EmpleadoData extends EmpleadoHandler
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
            $this->data_error = 'El identificador del empleado es incorrecto';
            return false;
        }
    }

    public function setNombres($value, $min = 2, $max = 100)
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

    public function setApellidos($value, $min = 2, $max = 100)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El apellido debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->apellido = $value;
            return true;
        } else {
            $this->data_error = 'El apellido debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setEmail($value, $min = 8, $max = 100)
    {
        if (!Validator::validateEmail($value)) {
            $this->data_error = 'El correo no es válido';
            return false;
        } elseif (!Validator::validateLength($value, $min, $max)) {
            $this->data_error = 'El correo debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        } elseif($this->checkDuplicate($value)) {
            $this->data_error = 'El correo ingresado ya existe';
            return false;
        } else {
            $this->correo = $value;
            return true;
        }
    }

    public function setDUI($value)
    {
        if (!Validator::validateDUI($value)) {
            $this->data_error = 'El DUI debe tener el formato ########-#';
            return false;
        } elseif($this->checkDuplicate($value)) {
            $this->data_error = 'El DUI ingresado ya existe';
            return false;
        } else {
            $this->dui = $value;
            return true;
        }
    }

    public function setContraseña($value)
    {
        if (Validator::validatePassword($value)) {
            $this->contraseña = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            $this->data_error = Validator::getPasswordError();
            return false;
        }
    }
    
    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }
}
