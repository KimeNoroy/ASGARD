<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluye el handler donde se encuentra la función predictiva.
require_once('../../models/data/factura_sujeto_excluido_data_admin.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte predictivo para el servicio factura sujeto excluido electrónico', 'l');

// Se instancia el handler para obtener los datos.
$usuario = new factura_sujeto_excluido();

// Llamar a la función predictNextMonthRecords para obtener la predicción.
$prediccionProximoMes = $usuario->predictNextMonthRecords();

// Mostrar el resultado de la predicción en el reporte.
$pdf->setFont('Arial', '', 12);
$pdf->ln(10); // Espacio en blanco antes del texto del análisis.
$pdf->multiCell(0, 10,  $pdf->encodeString("Se espera que el número de registros para el próximo mes sea de aproximadamente: " . $prediccionProximoMes . " registros, basado en el análisis de los datos del último año."));

// Opcionalmente, puedes agregar más texto explicando el método de predicción.
$pdf->ln(10);
$pdf->multiCell(0, 10,  $pdf->encodeString("El análisis se basa en el cambio promedio mensual en la cantidad de registros observados en el último año."));

// Mostrar el nombre del administrador al final del reporte.
$pdf->ln(10); // Espacio en blanco antes de mostrar el nombre del administrador.
$pdf->setFont('Arial', 'B', 12);
$pdf->cell(0, 10, 'Reporte generado por: ' . $pdf->encodeString($_SESSION['emailAdministrador']), 0, 1, 'L');

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'ReportePredictivoRegistros.pdf');