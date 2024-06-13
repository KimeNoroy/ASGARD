<?php

//import de clase database
require_once("../../helpers/database.php");

//crear handler
class ComprobanteCreditoHandler
{
    //campos de la tabla

    protected $id = null;
    protected $id_cliente = null;
    protected $id_servicio = null;
    protected $nit = null;
    protected $nombre = null;
    protected $nrc = null;
    protected $giro = null;
    protected $direccion = null;
    protected $email = null;
    protected $telefono = null;
    protected $dui = null;

    public function searchRows()
    {
        $value = '%' . Validator::getSearchValue() . '%';
        $sql = 'SELECT id_comprobante, id_cliente, id_servicio, nit_credito_fiscal, nombre_credito_fiscal, nrc_credito_fiscal, giro_credito_fiscal, direccion_credito_fiscal, email_credito_fiscal, telefono_credito_fiscal, dui_credito_fiscal
                FROM tb_comprobante_credito_fiscal
                WHERE nombre_credito_fiscal LIKE ? 
                ORDER BY id_comprobante';
        $params = array($value, $value);
        return Database::getRows($sql, $params);
    }

    public function createRow()
    {
        $sql = 'INSERT INTO tb_comprobante_credito_fiscal(id_cliente, id_servicio, nit_credito_fiscal, nombre_credito_fiscal, nrc_credito_fiscal, giro_credito_fiscal, direccion_credito_fiscal, email_credito_fiscal, telefono_credito_fiscal, dui_credito_fiscal)
                VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $params = array($this->id_cliente, $this->id_servicio, $this->nit, $this->nombre, $this->nrc, $this->giro, $this->direccion, $_SESSION['idAdministrador']);
        return Database::executeRow($sql, $params);
    }

    public function readAll()
    {
        $sql = 'SELECT id_comprobante, id_cliente, id_servicio, nit_credito_fiscal, nombre_credito_fiscal, nrc_credito_fiscal, giro_credito_fiscal, direccion_credito_fiscal, email_credito_fiscal, telefono_credito_fiscal, dui_credito_fiscal
                FROM tb_comprobante_credito_fiscal
                ORDER BY nombre_credito_fiscal';
        return Database::getRows($sql);
    }

    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_producto, descripcion_producto, precio_producto, existencias_producto, imagen_producto, id_categoria, estado_producto
                FROM producto
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function readFilename()
    {
        $sql = 'SELECT imagen_producto
                FROM producto
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }

    public function updateRow()
    {
        $sql = 'UPDATE producto
                SET imagen_producto = ?, nombre_producto = ?, descripcion_producto = ?, precio_producto = ?, estado_producto = ?, id_categoria = ?
                WHERE id_producto = ?';
        $params = array($this->imagen, $this->nombre, $this->descripcion, $this->precio, $this->estado, $this->categoria, $this->id);
        return Database::executeRow($sql, $params);
    }

    public function deleteRow()
    {
        $sql = 'DELETE FROM producto
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }

    public function readProductosCategoria()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, descripcion_producto, precio_producto, existencias_producto
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                WHERE id_categoria = ? AND estado_producto = true
                ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }

    /*
    *   Métodos para generar gráficos.
    */
    public function cantidadProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, COUNT(id_producto) cantidad
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY cantidad DESC LIMIT 5';
        return Database::getRows($sql);
    }

    public function porcentajeProductosCategoria()
    {
        $sql = 'SELECT nombre_categoria, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM producto)), 2) porcentaje
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                GROUP BY nombre_categoria ORDER BY porcentaje DESC';
        return Database::getRows($sql);
    }

    /*
    *   Métodos para generar reportes.
    */
    public function productosCategoria()
    {
        $sql = 'SELECT nombre_producto, precio_producto, estado_producto
                FROM producto
                INNER JOIN categoria USING(id_categoria)
                WHERE id_categoria = ?
                ORDER BY nombre_producto';
        $params = array($this->categoria);
        return Database::getRows($sql, $params);
    }
}
