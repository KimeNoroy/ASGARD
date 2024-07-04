<?php
require_once('../../helpers/database.php');
require_once('../../helpers/validator.php');
require_once('../../models/comprobante_credito_fiscal.php');

// Se comprueba si existe una acción a realizar.
if (isset($_GET['action'])) {
    // Se instancia el objeto de la clase correspondiente.
    $comprobanteCreditoFiscal = new ComprobanteCreditoFiscal;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'exception' => null);
    // Se verifica la acción a realizar.
    switch ($_GET['action']) {
        case 'readAll':
            if ($result['dataset'] = $comprobanteCreditoFiscal->readAll()) {
                $result['status'] = 1;
                $result['message'] = 'Se han encontrado registros.';
            } else {
                $result['exception'] = 'No se encontraron registros.';
            }
            break;
        case 'readOne':
            if (!$comprobanteCreditoFiscal->setIdComprobante($_GET['idComprobante'])) {
                $result['exception'] = 'ID de comprobante no válido.';
            } elseif ($result['dataset'] = $comprobanteCreditoFiscal->readOne()) {
                $result['status'] = 1;
            } else {
                $result['exception'] = 'No se pudo obtener el comprobante.';
            }
            break;
        case 'createRow':
            $_POST = $comprobanteCreditoFiscal->validateForm($_POST);
            if (!$comprobanteCreditoFiscal->setNitCreditoFiscal($_POST['nitCreditoFiscal'])) {
                $result['exception'] = 'NIT no válido.';
            } elseif (!$comprobanteCreditoFiscal->setNombreCreditoFiscal($_POST['nombreCreditoFiscal'])) {
                $result['exception'] = 'Nombre no válido.';
            } elseif (!$comprobanteCreditoFiscal->setNrcCreditoFiscal($_POST['nrcCreditoFiscal'])) {
                $result['exception'] = 'NRC no válido.';
            } elseif (!$comprobanteCreditoFiscal->setGiroCreditoFiscal($_POST['giroCreditoFiscal'])) {
                $result['exception'] = 'Giro no válido.';
            } elseif (!$comprobanteCreditoFiscal->setDireccionCreditoFiscal($_POST['direccionCreditoFiscal'])) {
                $result['exception'] = 'Dirección no válida.';
            } elseif (!$comprobanteCreditoFiscal->setEmailCreditoFiscal($_POST['emailCreditoFiscal'])) {
                $result['exception'] = 'Email no válido.';
            } elseif (!$comprobanteCreditoFiscal->setTelefonoCreditoFiscal($_POST['telefonoCreditoFiscal'])) {
                $result['exception'] = 'Teléfono no válido.';
            } elseif (!$comprobanteCreditoFiscal->setDuiCreditoFiscal($_POST['duiCreditoFiscal'])) {
                $result['exception'] = 'DUI no válido.';
            } elseif ($comprobanteCreditoFiscal->createRow()) {
                $result['status'] = 1;
                $result['message'] = 'Crédito fiscal creado correctamente.';
            } else {
                $result['exception'] = Database::getException();
            }
            break;
        case 'updateRow':
            $_POST = $comprobanteCreditoFiscal->validateForm($_POST);
            if (!$comprobanteCreditoFiscal->setIdComprobante($_POST['idComprobante'])) {
                $result['exception'] = 'ID de comprobante no válido.';
            } elseif (!$comprobanteCreditoFiscal->setNitCreditoFiscal($_POST['nitCreditoFiscal'])) {
                $result['exception'] = 'NIT no válido.';
            } elseif (!$comprobanteCreditoFiscal->setNombreCreditoFiscal($_POST['nombreCreditoFiscal'])) {
                $result['exception'] = 'Nombre no válido.';
            } elseif (!$comprobanteCreditoFiscal->setNrcCreditoFiscal($_POST['nrcCreditoFiscal'])) {
                $result['exception'] = 'NRC no válido.';
            } elseif (!$comprobanteCreditoFiscal->setGiroCreditoFiscal($_POST['giroCreditoFiscal'])) {
                $result['exception'] = 'Giro no válido.';
            } elseif (!$comprobanteCreditoFiscal->setDireccionCreditoFiscal($_POST['direccionCreditoFiscal'])) {
                $result['exception'] = 'Dirección no válida.';
            } elseif (!$comprobanteCreditoFiscal->setEmailCreditoFiscal($_POST['emailCreditoFiscal'])) {
                $result['exception'] = 'Email no válido.';
            } elseif (!$comprobanteCreditoFiscal->setTelefonoCreditoFiscal($_POST['telefonoCreditoFiscal'])) {
                $result['exception'] = 'Teléfono no válido.';
            } elseif (!$comprobanteCreditoFiscal->setDuiCreditoFiscal($_POST['duiCreditoFiscal'])) {
                $result['exception'] = 'DUI no válido.';
            } elseif ($comprobanteCreditoFiscal->updateRow()) {
                $result['status'] = 1;
                $result['message'] = 'Crédito fiscal actualizado correctamente.';
            } else {
                $result['exception'] = Database::getException();
            }
            break;
        case 'deleteRow':
            if (!$comprobanteCreditoFiscal->setIdComprobante($_POST['idComprobante'])) {
                $result['exception'] = 'ID de comprobante no válido.';
            } elseif ($comprobanteCreditoFiscal->deleteRow()) {
                $result['status'] = 1;
                $result['message'] = 'Crédito fiscal eliminado correctamente.';
            } else {
                $result['exception'] = Database::getException();
            }
            break;
        default:
            $result['exception'] = 'Acción no disponible.';
    }
    // Se imprime el resultado en formato JSON y se devuelve al controlador.
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($result);
}
?>
