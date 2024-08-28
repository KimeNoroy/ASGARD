<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/clientes_data.php');

// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Reporte predictivo para los clientes del siguiente mes', 'l');

// Se instancia el modelo ClienteData para obtener los datos.
$cliente = new ClienteData;

// Aquí se deben realizar cálculos o análisis para predecir la cantidad de clientes del próximo mes.
// Esto es solo un ejemplo de cómo podrías obtener y procesar los datos.
$clientesEsteMes = $cliente->countClientsCurrentMonth();
$clientesMesAnterior = $cliente->countClientsPreviousMonth();

// Simplemente para ilustrar un posible cálculo predictivo:
$prediccionClientesProximoMes = round($clientesEsteMes * 1.05); // Un 5% de aumento como ejemplo.

// Mostrar el resultado del análisis predictivo en el reporte.
$pdf->setFont('Arial', '', 12);
$pdf->ln(10); // Espacio en blanco antes del texto del análisis.
$pdf->multiCell(0, 10, "Se espera que el número de clientes para el próximo mes sea de aproximadamente: " . $prediccionClientesProximoMes . " clientes, basado en un análisis del crecimiento mensual observado en los últimos meses.");

// Opcionalmente, puedes agregar más texto explicando el método de predicción.
$pdf->ln(10);
$pdf->multiCell(0, 10, "El análisis se basa en un aumento del 5% en el número de clientes en comparación con el mes anterior, y tiene en cuenta factores como el comportamiento estacional y las tendencias observadas en el historial de clientes.");

// Mostrar el nombre del administrador al final del reporte.
$pdf->ln(10); // Espacio en blanco antes de mostrar el nombre del administrador.
$pdf->setFont('Arial', 'B', 12);
$pdf->cell(0, 10, 'Reporte generado por: ' . $pdf->encodeString($_SESSION['emailAdministrador']), 0, 1, 'L');

// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'ReportePredictivoClientes.pdf');