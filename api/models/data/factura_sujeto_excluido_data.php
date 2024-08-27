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
    private $data_error = null;

    /*
     *  Métodos para validar y establecer los datos.
     */

    // Método para establecer el nombre del cliente.
    public function setId($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            $this->data_error = 'El identificador del usuario es incorrecto';
            return false;
        }
    }

    public function setIdCliente($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_cliente = $value;
            return true;
        } else {
            $this->data_error = 'El cliente es incorrecto o esta vacio';
            return false;
        }
    }

    public function setIdServicio($value)
    {
        if (Validator::validateNaturalNumber($value)) {
            $this->id_servicio = $value;
            return true;
        } else {
            $this->data_error = 'El servicio es incorrecto o esta vacio';
            return false;
        }
    }

    // Método para establecer el tipo de servicio.
    public function setTipoServicio($value, $min = 3, $max = 25)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'No se ha seleccionado ningun servicio';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->tipo_servicio = $value;
            return true;
        } else {
            $this->data_error = 'El tipo servicio debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    // Método para establecer el precio del servicio.
    public function setMonto($value)
    {
        if(Validator::validateMoney($value)){
            $this->monto = $value; 
            return true;
        } else{
            $this->info_error = 'El precio debe ser un número positivo';
            return false;
        }
    }

    public function setDescripcion($value, $min = 2, $max = 500)
    {
        if (!Validator::validateString($value)) {
            $this->data_error = 'La descripción contiene caracteres prohibidos';
            return false;
        } elseif (Validator::validateLength($value, $min, $max)) {
            $this->descripcion = $value;
            return true;
        } else {
            $this->data_error = 'La descripción debe tener una longitud entre ' . $min . ' y ' . $max;
            return false;
        }
    }

    public function setFecha($value)
    {
        if (Validator::validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            $this->data_error = 'La fecha es incorrecta';
            return false;
        }
    }
    
    // Método para obtener el mensaje de error.
    public function getDataError()
    {
        return $this->info_error;
    }
}