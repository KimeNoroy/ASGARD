<?php
// Se incluye la clase para trabajar con la base de datos.
require_once ('../../helpers/database.php');

/*
 *  Clase para manejar el comportamiento de los datos de la tabla SUJETO EXCLUIDO.
 */
class factura_sujeto_excluido_handler
{
    /*
     *   Declaración de atributos para el manejo de datos.
     */
    protected $id= null;
    protected $id_cliente = null;
    protected $id_servicio = null;
    protected $tipo_servicio = null;
    protected $monto = null;
    protected $descripcion = null;
    protected $fecha = null;

    /*
     *   Métodos para realizar las operaciones SCRUD (search, create, read, update, and delete).
     */

    // Método para buscar usuarios.
    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_factura, nombre_cliente, apellido_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, tipo_servicio, monto, fecha_emision, descripcion
                FROM tb_factura_sujeto_excluido
                WHERE nombre_cliente LIKE ? OR apellido_cliente LIKE ?
                ORDER BY nombre_cliente';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    // Método para crear un nuevo usuario.
    public function createRow()
    {
        $sql = 'INSERT INTO tb_factura_sujeto_excluido(tipo_servicio, monto, fecha_emision, descripcion, id_administrador, id_cliente, id_servicio)
                VALUES(?, ?, ?, ?, ?, ?, ?)';
        $params = array(
            $this->tipo_servicio,
            $this->monto,
            $this->fecha,
            $this->descripcion,
            $_SESSION['idAdministrador'],
            $this->id_cliente,
            $this->id_servicio
        );
        return Database::executeRow($sql, $params);
    }

    // Método para leer todos los usuarios.
    public function readAll()
    {
        $sql = 'SELECT id_factura, nombre_cliente, apellido_cliente, direccion_cliente, departamento_cliente, municipio_cliente, email_cliente, telefono_cliente, dui_cliente, tipo_servicio, monto, fecha_emision
                FROM vista_tb_factura_sujeto_excluido
                ORDER BY nombre_cliente';
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

    // Método para leer un usuario específico.
    public function readOne()
    {
        $sql = 'SELECT id_factura, id_cliente, id_servicio, tipo_servicio, monto, fecha_emision, descripcion
                FROM vista_tb_factura_sujeto_excluido
                WHERE id_factura = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    // Método para actualizar un usuario.
    public function updateRow()
    {
        $sql = 'UPDATE tb_factura_sujeto_excluido
                SET tipo_servicio = ?, monto = ?, fecha_emision = ?, descripcion = ?, id_administrador = ?, id_cliente = ?, id_servicio = ?
                WHERE id_factura = ?';
        $params = array(
            $this->tipo_servicio,
            $this->monto,
            $this->fecha,
            $this->descripcion,
            $_SESSION['idAdministrador'],
            $this->id_cliente,
            $this->id_servicio,
            $this->id
        );
        return Database::executeRow($sql, $params);
    }

    // Método para eliminar un usuario.
    public function deleteRow()
    {
        $sql = 'DELETE FROM tb_factura_sujeto_excluido
                WHERE id_factura = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    //Función predictiva para este servicio
    public function predictNextMonthRecords()
    {
        // Obtener los registros por mes en el último año.
        $sql = 'SELECT DATE_FORMAT(fecha_emision, "%Y-%m") AS mes, COUNT(*) AS total
                FROM tb_factura_sujeto_excluido
                WHERE fecha_emision >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY mes
                ORDER BY mes ASC';
        $result = Database::getRows($sql);

        // Si hay menos de dos meses de datos, no se puede hacer una predicción significativa.
        if (count($result) < 2) {
        return 'No hay suficientes datos para realizar una predicción.';
        }

        // Calcular el cambio promedio de un mes a otro.
        $total_change = 0;
        for ($i = 1; $i < count($result); $i++) {
        $total_change += ($result[$i]['total'] - $result[$i - 1]['total']);
        }
        $average_change = $total_change / (count($result) - 1);

        // Obtener el total del último mes y predecir el siguiente.
        $last_month_total = end($result)['total'];
        $predicted_total = round($last_month_total + $average_change);

        // Asegurarse de que la predicción no sea negativa.
        return max($predicted_total, 0);
    }

    //Función para reporte predictivo
    public function countClientsCurrentMonth()
    {
        $sql = 'SELECT COUNT(*) AS total
                FROM tb_factura_sujeto_excluido
                WHERE MONTH(fecha_emision) = MONTH(CURDATE()) AND YEAR(fecha_emision) = YEAR(CURDATE())';
        $result = Database::getRow($sql);
        return ($result) ? $result['total'] : 0;
    }

}