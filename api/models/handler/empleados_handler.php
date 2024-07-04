<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
 *  Clase para manejar el comportamiento de los datos de la tabla empleados.
 */
class EmpleadoHandler
{
    /*
     *  Declaración de atributos para el manejo de datos.
     */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $email = null;
    protected $dui = null;
    protected $clave = null;

    /*
     *  Métodos para gestionar la cuenta del administrador.
     */
    public function checkUser($email, $password)
    {
        $sql = 'SELECT id_empleado, nombres_empleado, email_empleados, dui_empleado, contrasena
                FROM tb_empleados 
                WHERE  email_empleado = ?';
        $params = array($email);
        if (!($data = Database::getRow($sql, $params))) {
            return false;
        } elseif (password_verify($password, $data['contrasena'])) {
            $this ->id = $data['id_empleado'];
            $this->clave = $data['contrasena'];
            $this->email = $data['email_empleado'];
           // $this->estado = $data['estado_cliente'];

            return true;
        } else {
            return false;
        }
    }


    public function checkPassword($password)
    {
        $sql = 'SELECT contrasena
                FROM tb_empleados
                WHERE id_empleado = ?';
        $params = array($_SESSION['id_empleado']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['contrasena'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE tb_empleados
                SET contrasena = ?
                WHERE id_empleado = ?';
        $params = array($this->clave, $_SESSION['id_empleado']);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleados, dui_empleado
                FROM tb_empleados
                WHERE id_empleado = ?';
        $params = array($_SESSION['id_empleado']);
        return Database::getRow($sql, $params);
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleados, dui_empleado
                FROM tb_empleados
                WHERE nombres_empleado LIKE ? OR apellidos_empleado LIKE ?
                ORDER BY apellidos_empleado';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_empleados(nombres_empleado, apellidos_empleado, email_empleados, dui_empleado, contrasena)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->email, $this->dui, $this->clave);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleados, dui_empleado
                FROM tb_empleados
                ORDER BY apellidos_empleado';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleados, dui_empleado
                FROM tb_empleados
                WHERE id_empleado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_empleados
                SET nombres_empleado = ?, apellidos_empleado = ?, email_empleado =?, dui_empleado = ?
                WHERE id_empleado = ?';
        $params = array($this->nombre, $this->apellido,  $this->email, $this->dui, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function checkDuplicate($value)
    {
        $sql = 'SELECT id_empleado
                FROM tb_empleados
                WHERE dui_empleado = ?';
        $params = array($value, $value);
        return Database::getRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_empleados
                WHERE id_empleado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}