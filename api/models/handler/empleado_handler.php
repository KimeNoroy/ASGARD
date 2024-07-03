<?php
// Se incluye la clase para trabajar con la base de datos.
require_once('../../helpers/database.php');
/*
*	Clase para manejar el comportamiento de los datos de la tabla CLIENTE.
*/
class EmpleadoHandler
{
    /*
    *   Declaración de atributos para el manejo de datos.
    */
    protected $id = null;
    protected $nombre = null;
    protected $apellido = null;
    protected $email = null;
    protected $dui = null;
    protected $contraseña = null;

    /*
     *  Métodos para gestionar la cuenta del empleado.
     */
    public function checkUser($email, $password)
    {
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleado, dui_empleado, contraseña
                FROM tb_empleados
                WHERE  email_empleado = ?';
          $params = array($email);
          if (!($data = Database::getRow($sql, $params))) {
              return false;
          } elseif (password_verify($password, $data['contraseña'])) {
              $this ->id = $data['id_empleado'];
              $this->contraseña = $data['contraseña'];
              $this->email = $data['email_empleado'];
  
              return true;
          } else {
              return false;
          }
    }

    public function checkPassword($password)
    {
        $sql = 'SELECT contraseña
                FROM tb_empleados
                WHERE id_empleado = ?';
        $params = array($_SESSION['idEmpleado']);
        $data = Database::getRow($sql, $params);
        // Se verifica si la contraseña coincide con el hash almacenado en la base de datos.
        if (password_verify($password, $data['contraseña'])) {
            return true;
        } else {
            return false;
        }
    }

    public function changePassword()
    {
        $sql = 'UPDATE tb_empleados
                SET contraseña = ?
                WHERE id_empleado = ?';
        $params = array($this->contraseña, $_SESSION['idEmpleado']);
        return Database::executeRow($sql, $params);
    }

    public function readProfile()
    {
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleado, dui_empleado, contraseña
                FROM tb_empleados
                WHERE id_empleado = ?';
        $params = array($_SESSION['idEmpleado']);
        return Database::getRow($sql, $params);
    }

    public function editProfile()
    {
        $sql = 'UPDATE tb_empleados
                SET nombres_empleado = ?, apellidos_empleado = ?, email_empleado = ?, dui_empleado = ?
                WHERE id_empleado = ?';
        $params = array($this->nombre, $this->apellido, $this->email, $this->dui, $_SESSION['idEmpleado']);
        return Database::executeRow($sql, $params);
    }

    /*
     *  Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleado, dui_empleado
                FROM tb_empleados
                WHERE apellidos_empleado LIKE ? OR nombres_empleado LIKE ? OR email_empleado  LIKE ? OR dui_empleado LIKE ?;
                ORDER BY nombres_empleado';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_empleados(nombres_empleado, apellidos_empleado, email_empleado, dui_empleado, contraseña)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombre, $this->apellido, $this->email, $this->dui, $this->contraseña);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleado, dui_empleado
                FROM tb_empleados
                ORDER BY nombres_empleado';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_empleado, nombres_empleado, apellidos_empleado, email_empleado, dui_empleado
                FROM tb_empleados
                WHERE id_empleado = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE tb_empleados
                SET nombres_empleado = ?, apellidos_empleado = ?, email_empleado = ?, dui_empleado = ?
                WHERE id_empleado = ?';
        $params = array($this->id, $this->nombre, $this->apellido, $this->email, $this->dui);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_empleados
                WHERE id_empleado = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
}
