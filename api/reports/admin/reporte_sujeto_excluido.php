<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/factura_sujeto_excluido_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Facturas sujeto excluido', 'l');
// Se instancia el módelo Categoría para obtener los datos.
$factura = new factura_sujeto_excluido;
// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataFactura = $factura->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(200);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Espacio disponible 249mm
    // Se imprimen las celdas con los encabezados.
    $pdf->cell(22, 10, 'Nombre', 1, 0, 'C', 1);
    $pdf->cell(22, 10, 'Apellido', 1, 0, 'C', 1);
    $pdf->cell(22, 10, 'Dirección', 1, 1, 'C', 1);
    $pdf->cell(22, 10, 'Departamento', 1, 1, 'C', 1);
    $pdf->cell(22, 10, 'Municipio', 1, 1, 'C', 1);
    $pdf->cell(22, 10, 'Correo', 1, 1, 'C', 1);
    $pdf->cell(22, 10, 'Teléfono', 1, 1, 'C', 1);
    $pdf->cell(22, 10, 'DUI', 1, 1, 'C', 1);
    $pdf->cell(22, 10, 'Tipo de servicio', 1, 1, 'C', 1);
    $pdf->cell(22, 10, 'Monto', 1, 1, 'C', 1);
    $pdf->cell(22, 10, 'Fecha de emisión', 1, 1, 'C', 1);

    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(240);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataFactura as $rowFactura) {
        // Se imprimen las celdas con los datos de los productos.
        $pdf->cell(22, 10, $pdf->encodeString($rowFactura['nombre_cliente']), 1, 0);
        $pdf->cell(22, 10, $rowFactura['apellido_cliente'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['direccion_cliente'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['departamento_cliente'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['municipio_cliente'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['email_cliente'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['telefono_cliente'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['dui_cliente'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['tipo_servicio'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['monto'], 1, 0);
        $pdf->cell(22, 10, $rowFactura['fecha_emision'], 1, 0);
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay registros para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'FacturaSE.pdf');
