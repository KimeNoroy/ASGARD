<?php
// Se incluye la clase para generar archivos PDF.
require_once('../../libraries/fpdf185/fpdf.php');

class PDF extends FPDF
{
    // Constante para definir la ruta de las vistas del sitio privado.
    const CLIENT_URL = 'http://localhost/coffeeshop/views/admin/';
    // Propiedad para guardar el título del reporte.
    private $title = null;

    /*
    *   Método para iniciar el reporte con el encabezado del documento.
    *   Parámetros: $title (título del reporte).
    *   Retorno: ninguno.
    */
    public function startReport($title)
    {
        // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en los reportes.
        session_start();
        // Se verifica si un administrador ha iniciado sesión para generar el documento, de lo contrario se direcciona a la página web principal.
        if (isset($_SESSION['idAdministrador'])) {
            // Se asigna el título del documento a la propiedad de la clase.
            $this->title = $title;
            // Se establece el título del documento (true = utf-8).
            $this->setTitle('CoffeeShop - Reporte', true);
            // Se establecen los margenes del documento (izquierdo, superior y derecho).
            $this->setMargins(15, 15, 15);
            // Se añade una nueva página al documento con orientación vertical y formato carta, llamando implícitamente al método header()
            $this->addPage('p', 'letter');
            // Se define un alias para el número total de páginas que se muestra en el pie del documento.
            $this->aliasNbPages();
        } else {
            header('location:' . self::CLIENT_URL);
        }
    }

    /*
    *   Método para codificar una cadena de alfabeto español a UTF-8.
    *   Parámetros: $string (cadena).
    *   Retorno: cadena convertida.
    */
    public function encodeString($string)
    {
        return mb_convert_encoding($string, 'ISO-8859-1', 'utf-8');
    }


    // Header
    function Header()
    {
        // Logo
        $this->Image('logo.png', 10, 8, 33);
        // Move to the right
        $this->Cell(80);
        // Company Info
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(100, 10, 'MULTINACIONAL GONZALES', 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(100, 5, 'Direccion: Calle cualquiera 123, Cualquier lugar', 0, 1, 'C');
        $this->Cell(100, 5, 'Email: Hola@sitioincreible.com', 0, 1, 'C');
        $this->Cell(100, 5, 'Tel: (55) 1234-5678', 0, 1, 'C');
        $this->Ln(20);
    }

    // Footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Create table for the invoice
    function InvoiceTable()
    {
        // Table header
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 10, 'Cantidad', 1);
        $this->Cell(70, 10, 'Descripcion', 1);
        $this->Cell(30, 10, 'Abono', 1);
        $this->Cell(30, 10, 'Resta', 1);
        $this->Cell(30, 10, 'Total', 1);
        $this->Ln();

        // Table body
        $this->SetFont('Arial', '', 10);
        for ($i = 0; $i < 10; $i++) {
            $this->Cell(30, 10, '', 1);
            $this->Cell(70, 10, '', 1);
            $this->Cell(30, 10, '', 1);
            $this->Cell(30, 10, '', 1);
            $this->Cell(30, 10, '', 1);
            $this->Ln();
        }
    }

    // Add Observations section
    function Observations()
    {
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 10, 'OBSERVACIONES', 1, 1, 'L');
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(190, 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 1);
    }

    // Add Service and Signature section
    function ServiceSignature()
    {
        $this->Ln(10);
        $this->Cell(100, 10, 'SERVICIO A DOMICILIO', 0, 0, 'L');
        $this->Cell(90, 10, 'FIRMA CLIENTE', 0, 1, 'R');
    }
}

// Instantiation of inherited class
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Client information
$pdf->Cell(40, 10, 'SENOR(ES):');
$pdf->Cell(90, 10, '', 0, 0);
$pdf->Cell(30, 10, 'TELEFONO:', 0, 1);
$pdf->Cell(40, 10, 'DIRECCION:');
$pdf->Cell(90, 10, '', 0, 0);
$pdf->Cell(30, 10, 'SECTOR:', 0, 1);
$pdf->Ln(10);

// Invoice Table
$pdf->InvoiceTable();

// Observations
$pdf->Ln(5);
$pdf->Observations();

// Service and Signature
$pdf->ServiceSignature();

$pdf->Output();
