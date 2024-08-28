<?php
// Se incluye la clase con las plantillas para generar reportes.
require_once('../../helpers/report.php');
// Se incluyen las clases para la transferencia y acceso a datos.
require_once('../../models/data/clientes_data.php');
 
// Se instancia la clase para crear el reporte.
$pdf = new Report;
// Se inicia el reporte con el encabezado del documento.
$pdf->startReport('Facturas de:  '  , 'l');
 
// Se instancia el modelo Producto para procesar los datos.
$cliente = new ClienteData;
// Se establece la plantilla para obtener sus productos, de lo contrario se imprime un mensaje de error.
if ($cliente->setId($_GET['id_cliente'])) {
    // Establecer color de texto a blanco
    $pdf->setTextColor(255, 255, 255);
    // Se establece un color de relleno para los encabezados.
    $pdf->setFillColor(2, 8, 135);
    // Se establece el color del borde.
    $pdf->setDrawColor(2, 8, 135);
    // Se establece la fuente para los encabezados.
    $pdf->setFont('Arial', 'B', 11);
    // Se imprimen las celdas con los encabezados.
 
    // Explicación de funcionamiento de los valores de las celdas:
    // (Ancho, Alto, Texto, Borde, Salto de linea, Alineación (Centrado = C, Izquierda = L, Derecha = R), Fondo, Link)
    // $pdf->cell(60, 10, 'Correo ', 1, 0, 'C', 1);
    $pdf->cell(37, 10, 'Monto', 1, 0, 'C', 1); // Nueva columna para imagen
    $pdf->cell(33, 10, 'Fecha de emision', 1, 0, 'C', 1);
    $pdf->cell(60, 10, 'Descripcion ', 1, 0, 'C', 1);
    $pdf->cell(50, 10, 'Tipo de servicio ', 1, 1, 'C', 1);
 
    // Se establece un color de relleno para mostrar el nombre de la categoría.
    $pdf->setFillColor(240);
    // Se establece la fuente para los datos de los productos.
    $pdf->setFont('Arial', '', 11);
    // Se verifica si existen registros para mostrar, de lo contrario se imprime un mensaje.
    if ($dataCliente = $cliente->readOne()) {
        // Verifica si se ha creado una nueva página
        if ($pdf->getY() + 15 > 279 - 30) { // Ajusta este valor según el tamaño de tus celdas y la altura de la página
            // Establecer color de texto a blanco
            $pdf->setTextColor(255, 255, 255);
            $pdf->addPage('P', 'Letter'); // Añade una nueva página y con letter se define de tamaño carta
            $pdf->setFillColor(2, 8, 135);
            $pdf->setDrawColor(2, 8, 135);
            $pdf->setFont('Arial', 'B', 11);
            // Vuelve a imprimir los encabezados en la nueva página
            // $pdf->cell(60, 10, 'Correo ', 1, 0, 'C', 1);
            $pdf->cell(37, 10, 'Monto', 1, 0, 'C', 1);
            $pdf->cell(33, 10, 'Fecha de emision', 1, 0, 'C', 1);
            $pdf->cell(60, 10, 'Descripcion ', 1, 0, 'C', 1);
            $pdf->cell(50, 10, 'Tipo de servicio ', 1, 1, 'C', 1);
        }

        $currentY = $pdf->getY(); // Obtén la coordenada Y actual
        // Establecer color de texto a negro
        $pdf->setTextColor(0, 0, 0);
        // Se establecen los colores de las celdas
        $pdf->setDrawColor(0, 0, 0);
        $pdf->setFont('Arial', '', 11);
        $pdf->setFillColor(255, 255, 255);
        // $imagePath = '../../image/clientes/' . $dataFactura['imagen_cliente'];
        // // Verifica si el archivo existe y si es un PNG válido
        // if (!file_exists($imagePath) || exif_imagetype($imagePath) != IMAGETYPE_PNG) {
        //     // Usa una imagen por defecto o maneja el error
        //     $imagePath = '../../images/jugadores/default.png';
        // }
        // $pdf->cell(37, 15, $pdf->image($imagePath, $pdf->getX() + 10, $currentY + 2, 10), 1, 0);
     
        // Imprime la fila con los datos del registro
        // $pdf->cell(60, 15, $pdf->encodeString($dataFactura['email_cliente']), 1, 0, 'C');
        $pdf->cell(37, 15, $pdf->encodeString($dataCliente['monto']), 1, 0, 'C');
        $pdf->cell(35, 15, $pdf->encodeString($dataCliente['fecha_emision']), 1, 0, 'C');
        $pdf->cell(60, 15, $pdf->encodeString($dataCliente['descripcion']), 1, 0, 'C');
        $pdf->cell(50, 15, $pdf->encodeString($dataCliente['tipo_servicio']), 1, 1, 'C');
 
    } else {
        $pdf->cell(0, 10, $pdf->encodeString('No existen datos'), 1, 1);
    }
} else {
    $pdf->cell(0, 10, $pdf->encodeString('Factura inexistente'), 1, 1);
}
 
// Se llama implícitamente al método footer() y se envía el documento al navegador web.
$pdf->output('I', 'productos.pdf');

