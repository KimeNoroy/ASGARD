<?php
// Se incluye la clase para validar los datos de entrada.
require_once('../../helpers/validator.php');
// Se incluye la clase padre.
require_once('../../models/handler/factura_sujeto_excluido_handler.php');

/*
 *  Clase para manejar el encapsulamiento de los datos de la tabla SUJETO EXCLUIDO.
 */
class factura_sujeto_excluido extends factura_sujeto_excluido_handler
{
    /*
     *  Atributos adicionales.
     */
    private $info_error = null;

    /*
     *  Métodos para validar y establecer los datos.
     */

    // Método para establecer el nombre del cliente.
    public function setNombre($valor, $min = 4, $max = 100)
    {
        if (!Validator::validateAlphabetic($valor)) {
            $this->info_error = 'El nombre debe ser un valor alfabético';
            return false;
        } elseif (Validator::validateLength($valor, $min, $max)) {
            $this->nombre_cliente = $valor;
            return true;
        } else {
            $this->info_error = 'El nombre debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para establecer el correo del cliente.
    public function setCorreo($valor, $min = 8, $max = 100)
    {
        if (!Validator::validateEmail($valor)) {
            $this->info_error = 'El correo no es válido';
            return false;
        } elseif (Validator::validateLength($valor, $min, $max)) {
            // Verifica si el correo ya existe en la base de datos
            if ($this->checkDuplicate($valor)) {
                $this->info_error = 'El correo ingresado ya existe';
                return false;
            } else {
                // Si todas las validaciones pasan, establece el correo
                $this->email_cliente = $valor;
                return true;
            }
        } else {
            $this->info_error = 'El correo debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para establecer el DUI del cliente.
    public function setDUI($value)
    {
        if (!Validator::validateDUI($value)) {
            $this->info_error = 'El DUI debe tener el formato ########-#';
            return false;
        } elseif($this->checkDuplicate($value)) {
            $this->info_error = 'El DUI ingresado ya existe';
            return false;
        } else {
            $this->dui_cliente = $value;
            return true;
        }
    }

    // Método para establecer el NIT del cliente.
    public function setNIT($value)
    {
        if (!Validator::validateNIT($value)) {
            $this->info_error = 'El NIT debe tener el formato #########';
            return false;
        } elseif($this->checkDuplicate($value)) {
            $this->info_error = 'El NIT ingresado ya existe';
            return false;
        } else {
            $this->nit_cliente = $value;
            return true;
        }
    }

    // Método para establecer el teléfono del cliente.
    public function setTelefono($value)
    {
        if (!Validator::validatePhone($value)) {
            $this->info_error = 'El teléfono debe iniciar con el formato (6, 7)###-####';
            return false;
        } elseif ($this->checkDuplicate($value)) {
            $this->info_error = 'El teléfono ingresado ya está siendo usado por otro cliente';
            return false;
        } else {
            $this->telefono_cliente = $value;
            return true;
        }
    }

    // Método para establecer la dirección del cliente.
    public function setDireccion($value, $min = 10, $max = 255)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->info_error = 'La dirección debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->direccion_cliente = $value;
            return true;
        } else {
            $this->info_error = 'La dirección debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para establecer el departamento del cliente.
    public function setDepartamento($value, $min = 10, $max = 100)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->info_error = 'El departamento debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->departamento_cliente = $value;
            return true;
        } else {
            $this->info_error = 'El departamento debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para establecer el municipio del cliente.
    public function setMunicipio($value, $min = 10, $max = 100)
    {
        if (!Validator::validateAlphanumeric($value)) {
            $this->info_error = 'El municipio debe ser un valor alfanumérico';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->municipio_cliente = $value;
            return true;
        } else {
            $this->info_error = 'El municipio debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para establecer el tipo de servicio.
    public function setTipoServicio($value)
    {
        $this->tipo_servicio = $value; // Aquí debes implementar la lógica de validación según tus requerimientos.
        return true; // Por ahora devuelve true, implementa la validación adecuada.
    }

    // Método para establecer el precio del servicio.
    public function setPrecioServicio($value)
    {
        if(Validator::validateMoney($value)){
            $this->monto = $value; // Asumo que monto es el campo correcto en la tabla según el SQL proporcionado.
            return true;
        } else{
            $this->info_error = 'El precio del servicio es incorrecto';
            return false;
        }
    }

    // Método para obtener el mensaje de error.
    public function getDataError()
    {
        return $this->info_error;
    }
}
