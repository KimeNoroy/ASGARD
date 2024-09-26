<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/administrador_handler.php');

require_once('../../helpers/encryption.php');

/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla USUARIO.
 */
class AdministradorData extends AdministradorHandler
{
    // Atributo genérico para manejo de errores.
    private $data_error = null;
    private $info_error = null;

    /*
     *  Métodos para validar y asignar valores de los atributos.
     */
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del administrador es incorrecto';
            return false;
        }
    }

    public function setNombre($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El nombre debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->nombre =  Encryption::aes128_ofb_encrypt($value);
            return true;
        } else {
            $this->data_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setApellido($value, $min = 2, $max = 50)
    {
        if (!Validator::validateAlphabetic($value)) {
            $this->data_error = 'El apellido debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->apellido =  Encryption::aes128_ofb_encrypt($value);
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
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->email = Encryption::aes128_ofb_encrypt($value);
            return true;
        } else {
            $this->data_error = 'El correo debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setContraseña($value)
    {
        // Validación de la contraseña
        $hasLetter = preg_match('/[A-Za-z]/', $value);
        $hasDigit = preg_match('/\d/', $value);  
        $hasSpecialChar = preg_match('/[\W_]/', $value); // Caracteres especiales
        $noSpaces = !preg_match('/\s/', $value); // Sin espacios
    
        // Validación de secuencias numéricas
        $noSequentialNumbers = !preg_match('/(0123456789|123456789|23456789|3456789|456789)/', $value);
    
        if ($hasLetter && $hasDigit && $hasSpecialChar && $noSpaces && $noSequentialNumbers) {
            $this->contraseña = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            $this->data_error = "la contraseña debe contener al menos un carácter alfanumérico, un carácter especial, no debe tener espacios y no debe contener secuencias numéricas consecutivas.";
            return false;
        }
    }
    
    
    
    

    // Método para obtener el error de los datos.
    public function getDataError()
    {
        return $this->data_error;
    }

    public function getInfoError()
    {
        return $this->info_error;
    }
    
}
