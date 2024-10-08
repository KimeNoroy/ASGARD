<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
require_once('../../helpers/encryption.php');
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

    protected $columns = [1, 2, 3];

    public function changeTempPassword()
    {
        $sql = 'UPDATE tb_administrador
                SET contraseña_administrador = ?
                WHERE id_administrador = ?';
        $params = array($this->contraseña, $_SESSION['tempChanger']['id']);
        return Database::executeRow($sql, $params);
    }

    public function clearValidator()
    {
        $sql = 'CALL clear_past_validators();';
        return Database::executeSingleRow($sql);
    }

    public function setValidator($email)
    {
        $sql = 'CALL update_validatorcount(?);';
        $params = array(Encryption::aes128_ofb_encrypt($email));
        return Database::executeRow($sql, $params);
    }

    public function getValidator($email){
        $sql = "SELECT IF(COUNT(*) = 0, 1, IF(validator IS NULL OR validator = '', 1, 0)) AS value 
                FROM tb_administrador WHERE email_administrador = ?;";
        $params = array(Encryption::aes128_ofb_encrypt($email));
        $result = Database::getRow($sql, $params);
        return $result['value'] == 1;
    }

      public function validatePassword()
    {
        $sql = 'SELECT verificar_cambio_contraseña(?) AS date;';
        $params = array($_SESSION['tempChanger']['id']);
        $result = Database::getRow($sql, $params);
        return $result['date'] == 1;
    }

    /*
     *  Métodos para gestionar la cuenta del administrador.
     */
    public function checkUser($email, $password): int
    {
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, email_administrador, contraseña_administrador
                FROM tb_administrador
                WHERE  email_administrador = ?';
        $params = array(Encryption::aes128_ofb_encrypt($email));
        $result = Database::getRow($sql, $params);
        if (!($data = Database::getRow($sql, $params))) {
              return 0;
          } elseif (password_verify($password, $data['contraseña_administrador'])) {

            $sql = 'SELECT verificar_cambio_contraseña(?) AS date;';
            $params = array($data['id_administrador']);
            $result = Database::getRow($sql, $params);

            if(!$result['date'] == 1){
                $_SESSION['idAdministrador'] = $data['id_administrador'];
                $_SESSION['emailAdministrador'] =  Encryption::aes128_ofb_decrypt($data['email_administrador']);
                return 1;
            } else{
                $_SESSION['tempChanger'] = [
                    'id' => $data['id_administrador'],
                    'expiration_time' => time() + (60 * 10) # (x*y) y=minutos de vida 
                ];
                return 2;
            }
             
          } else {
              return 0;
          }
    }

    public function ValidateLogin($email, $password){
        $sql = 'SELECT id_administrador, nombre_administrador, apellido_administrador, email_administrador, contraseña_administrador
                FROM tb_administrador
                WHERE  email_administrador = ?';
        $params = array(Encryption::aes128_ofb_encrypt($email));
        if (!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['contraseña_administrador'])) {
            return true;
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
        return Database::getRow($sql, $params, $this->columns);
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
        $params = array($this->contraseña, Encryption::aes128_ofb_encrypt($_SESSION['usuario_correo_vcc']['correo']));
        
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
