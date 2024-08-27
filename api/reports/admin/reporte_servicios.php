<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/servicios_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Registro de servicios', 'p');

// Se instancia el modelo Categoría para obtener los datos.
$servicios = new ServiciosData;

// Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
if ($dataServicios = $servicios->readAll()) {
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(200);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);

    // Se imprimen las celdas con los encabezados.
    $pdf->cell(95, 10, 'Servicio', 1, 0, 'C', 1);
    $pdf->cell(95, 10, 'Descripcion', 1, 1, 'C', 1);
   
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(240);
    // Se establece la fuente para los datos de las facturas.
    $pdf->setFont('Arial', '', 11);

    // Se recorren los registros fila por fila.
    foreach ($dataServicios as $rowServicio) {
        // Se imprimen las celdas con los datos de las facturas.
        $pdf->cell(95, 10, $pdf->encodeString($rowServicio['nombre_servicio']), 1, 0);
        $pdf->cell(95, 10, $rowServicio['descripcion'], 1, 1);
    }
    
    // Mostrar el nombre del administrador al final del reporte.
    $pdf->ln(10); // Espacio en blanco antes de mostrar el nombre del administrador.
    $pdf->setFont('Arial', 'B', 12);
    $pdf->cell(0, 10, 'Reporte generado por: ' . $pdf->encodeString($_SESSION['emailAdministrador']), 0, 1, 'L');

} else {
    $pdf->cell(0, 10, $pdf->encodeString('No hay registros para mostrar'), 1, 1);
}
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'Servicios.pdf');
?>
