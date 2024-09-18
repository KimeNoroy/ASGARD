<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla administrador.
 */
class AdministradorHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $email = null;
    protected $clave = null;
    protected $contraseña = null;

    public function banValidator()
    {
        $sql = 'SELECT set_validator(1);';
        $params = array($_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    public function updateValidator()
    {
        $sql = 'SELECT clear_past_validators();';
        return Database::executeSingleRow($sql);
    }

    public function updateValidatorCount()
    {
        $sql = 'UPDATE tb_administradores
                SET validatorcount = validatorcount+1
                WHERE email_administrador = ?';
        $params = array($this->email);
        return Database::executeRow($sql, $params);
    }


    /*
     *  Métodos para gestionar la cuenta del administrador.
     */
    public function checkUser($email, $password)
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, email_administrador, contraseña_administrador
                FROM tb_administrador
                WHERE  email_administrador = ?';
          $params = array($email);
          if (!($data = Database::getRow($sql, $params))) {
              return false;
          } elseif (password_verify($password, $data['contraseña_administrador'])) {
            $_SESSION['idAdministrador'] = $data['id_administrador'];
            $_SESSION['emailAdministrador'] = $data['email_administrador'];
            return true;  
          } else {
              return false;
          }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT contraseña_administrador
                FROM tb_administrador
                WHERE id_administrador = ?';
        $params = array($_SESSION['idAdministrador']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['contraseña_administrador'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE tb_administrador
                SET contraseña_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->contraseña, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, email_administrador, contraseña_administrador
                FROM tb_administrador
                WHERE id_administrador = ?';
        $params = array($_SESSION['idAdministrador']);
        return Database::getRow($sql, $params);
    }

    public function editProfile()
    {
        $sql = 'UPDATE tb_administrador
                SET nombre_administrador = ?, apellido_administrador = ?, email_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->email, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, email_administrador
                FROM tb_administrador
                WHERE apellido_administrador LIKE ? OR nombre_administrador LIKE ?
                ORDER BY apellido_administrador';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_administrador(nombre_administrador, apellido_administrador, email_administrador, contraseña_administrador)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->email, $this->contraseña);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, email_administrador, contraseña_administrador
                FROM tb_administrador
                ORDER BY nombre_administrador';
        return Database::getRows($sql);
    }


    public function readOne()
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, email_administrador
                FROM tb_administrador
                WHERE id_administrador = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_administrador
                SET nombre_administrador = ?, apellido_administrador = ?, email_administrador = ?, contraseña_administrador=?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido, $this->email, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function updateRow2()
    {
        $sql = 'UPDATE tb_administrador
                SET nombre_administrador = ?, apellido_administrador = ?, email_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->nombre, $this->apellido,$this->email, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_administrador
                WHERE id_administrador = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readDashboardStats()
    {
        $sql = 'SELECT 
        (SELECT COUNT(*) FROM tb_empleados) AS total_empleados,
        (SELECT COUNT(*) FROM tb_factura_sujeto_excluido) + 
        (SELECT COUNT(*) FROM tb_factura_consumidor_final) AS total_facturas,
        (SELECT COUNT(*) FROM tb_clientes) AS total_clientes;';
        return Database::getRow($sql);
    }
    public function verifyExistingEmail()
    {
        $sql = 'SELECT COUNT(*) as count
                FROM tb_administrador
                WHERE email_administrador = ?';
        $params = array($this->email);
        $result = Database::getRow($sql, $params);
    
        if ($result['count'] > 0) {
            return true; // Hay resultados
        } else {
            return false; // No hay resultados
        }
    }

    public function changePasswordFromEmail()
    {
        // SQL para actualizar la contraseña
        $sql = 'UPDATE tb_administrador SET contraseña_administrador = ? WHERE email_administrador = ?';
        
        // Parámetros: contraseña encriptada y el correo del administrador
        $params = array($this->contraseña, $_SESSION['usuario_correo_vcc']['correo']);
        
        // Verificar el valor de la contraseña antes del UPDATE
        error_log("Valor de la contraseña encriptada antes del UPDATE: " . $this->contraseña);
        
        // Ejecutar la consulta SQL
        if ($result = Database::executeRow($sql, $params)) {
            // Registrar si la contraseña fue actualizada correctamente
            error_log("Contraseña actualizada correctamente para el email: " . $_SESSION['usuario_correo_vcc']['correo']);
            return true;
        } else {
            // Registrar en caso de error en la actualización
            error_log("Error al actualizar la contraseña.");
            return false;
        }
    }
}
