<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/factura_sujeto_excluido_data.php');

// Se instancia la clase para procesar los datos de la factura.
$factura = new factura_sujeto_excluido;

// Se establece la plantilla para obtener los datos de la factura.
if ($factura->setId($_GET['id_factura'])) {
    // Obtiene los datos de la factura.
    if ($dataFactura = $factura->readFactura()) {
        // Obtiene el nombre del cliente.
        $nombreCliente = $dataFactura['nombre_cliente']; 
        $apellidoCliente = $dataFactura['apellido_cliente']; 

          // Concatenar nombre y apellido
          $nombreCompletoCliente = $nombreCliente . ' ' . $apellidoCliente;

        // Se instancia la clase para crear el reporte.
        $pdf = new Report;
        // Se inicia el reporte con el encabezado del documento, incluyendo el nombre del cliente.
        $pdf->startReport('Factura de sujeto excluido de: ' . $nombreCompletoCliente, 'l');;

        // Establece color de texto, relleno, borde y fuente para los encabezados.
        $pdf->setTextColor(255, 255, 255);
        $pdf->setFillColor(255, 121, 13); // Color #FF790D
        $pdf->setDrawColor(255, 121, 13); // Color #FF790D
        $pdf->setFont('Arial', 'B', 11);

        // Imprime las celdas con los encabezados.
        $pdf->cell(37, 10, 'Foto', 1, 0, 'C', 1); // Nueva columna para imagen
        $pdf->cell(60, 10, 'Correo ', 1, 0, 'C', 1);
        $pdf->cell(32, 10, 'Monto(US$)', 1, 0, 'C', 1); // Nueva columna para monto
        $pdf->cell(40, 10, 'Fecha de emision', 1, 0, 'C', 1);
        $pdf->cell(83, 10, 'Descripcion ', 1, 1, 'C', 1);

        // Establece un color de relleno para mostrar el nombre de la categoría.
        $pdf->setFillColor(240);
        $pdf->setFont('Arial', '', 11);

        // Verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
        if ($dataFactura) {
            // Verifica si se ha creado una nueva página
            if ($pdf->getY() + 15 > 279 - 30) { // Ajusta este valor según el tamaño de tus celdas y la altura de la página
                // Establecer color de texto a blanco
                $pdf->setTextColor(255, 255, 255);
                $pdf->addPage('P', 'Letter'); // Añade una nueva página y con letter se define de tamaño carta
                $pdf->setFillColor(255, 121, 13); // Color #FF790D
                $pdf->setDrawColor(255, 121, 13); // Color #FF790D
                $pdf->setFont('Arial', 'B', 11);
                // Vuelve a imprimir los encabezados en la nueva página
                $pdf->cell(37, 10, 'Foto', 1, 0, 'C', 1); // Nueva columna para imagen
                $pdf->cell(60, 10, 'Correo ', 1, 0, 'C', 1);
                $pdf->cell(32, 10, 'Monto(US$)', 1, 0, 'C', 1); // Nueva columna para monto
                $pdf->cell(40, 10, 'Fecha de emision', 1, 0, 'C', 1);
                $pdf->cell(83, 10, 'Descripcion ', 1, 1, 'C', 1);
            }

            $currentY = $pdf->getY(); // Obtén la coordenada Y actual
            // Establecer color de texto a negro
            $pdf->setTextColor(0, 0, 0);
            // Se establecen los colores de las celdas
            $pdf->setDrawColor(0, 0, 0);
            $pdf->setFont('Arial', '', 11);
            $pdf->setFillColor(255, 255, 255); // Color de fondo para los datos

            // Verificación de que el archivo existe
            $imagePath = '../../image/clientes/' . $dataFactura['imagen_cliente'];
            // Verifica si el archivo existe y si es un PNG válido
            if (!file_exists($imagePath) || exif_imagetype($imagePath) != IMAGETYPE_PNG) {
                // Usa una imagen por defecto o maneja el error
                $imagePath = '../../image/clientes/default.png';
            }
            $pdf->cell(37, 15, $pdf->image($imagePath, $pdf->getX() + 10, $currentY + 2, 10), 1, 0); // Foto

            // Imprime la fila con los datos del registro
            $pdf->cell(60, 15, $pdf->encodeString($dataFactura['email_cliente']), 1, 0, 'C'); // Correo
            $pdf->cell(32, 15, $pdf->encodeString($dataFactura['monto']), 1, 0, 'C'); // Monto
            $pdf->cell(40, 15, $pdf->encodeString($dataFactura['fecha_emision']), 1, 0, 'C'); // Fecha de emisión
            $pdf->cell(83, 15, $pdf->encodeString($dataFactura['descripcion']), 1, 1, 'C'); // Descripción

            // Mostrar el nombre del administrador al final del reporte.
            $pdf->ln(10); // Espacio en blanco antes de mostrar el nombre del administrador.
            $pdf->setFont('Arial', 'B', 12);
            $pdf->cell(0, 10, 'Reporte generado por: ' . $pdf->encodeString($_SESSION['emailAdministrador']), 0, 1, 'L');
        } else {
            $pdf->cell(0, 10, $pdf->encodeString('No existen datos'), 1, 1);
        }
    } else {
        $pdf->cell(0, 10, $pdf->encodeString('Factura inexistente'), 1, 1);
    }

    // Se llama implícitamente al método footer() y se envía el documento al navegador web.
    $pdf->output('I', 'factura.pdf');
} else {
    echo 'Error al obtener datos de la factura.';
}
