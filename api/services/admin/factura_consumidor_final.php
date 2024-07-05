<?php
// Se incluye la clase del modelo.
require_once('../../models/data/factura_consumidor_final_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $facturaConsumidorFinal = new FacturaConsumidorFinalData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!isset($_POST['buscarfacturaConsumidorFinal']) || !Validator::validateSearch($_POST['buscarfacturaConsumidorFinal'])) {
                    $result['error'] = 'Búsqueda inválida';
                } else {
                    Validator::setSearchValue($_POST['buscarfacturaConsumidorFinal']);
                    $result['dataset'] = $facturaConsumidorFinal->searchRows();
                    if ($result['dataset']) {
                        $result['status'] = 1;
                        $result['message'] = count($result['dataset']) . ' coincidencias';
                    } else {
                        $result['error'] = 'No hay coincidencias';
                    }
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
               
                    !$facturaConsumidorFinal->setIdCliente($_POST['idCliente']) or
                    !$facturaConsumidorFinal->setNit($_POST['nitCliente']) or
                    !$facturaConsumidorFinal->setNombreCliente($_POST['nombreCliente']) or
                    !$facturaConsumidorFinal->setDireccionCliente($_POST['direccionCliente']) or
                    !$facturaConsumidorFinal->setDepartamentoCliente($_POST['departamentoCliente']) or
                    !$facturaConsumidorFinal->setMunicipioCliente($_POST['municipioCliente']) or
                    !$facturaConsumidorFinal->setEmailCliente($_POST['emailCliente']) or
                    !$facturaConsumidorFinal->setTelefonoCliente($_POST['telefonoCliente']) or
                    !$facturaConsumidorFinal->setDuiCliente($_POST['duiCliente']) or
                    !$facturaConsumidorFinal->setIdServicio($_POST['idServicio']) or
                    !$facturaConsumidorFinal->setMonto($_POST['monto']) or
                    !$facturaConsumidorFinal->setIdEmpleado($_POST['idEmpleado']) or
                    !$facturaConsumidorFinal->setFechaEmision($_POST['fechaEmision']) 
                   
                ) {
                    $result['error'] = $facturaConsumidorFinal->getDataError();
                } elseif ($facturaConsumidorFinal->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'facturaConsumidorFinal creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el facturaConsumidorFinal';
                }
                break;
            case 'readAll':
                $result['dataset'] = $facturaConsumidorFinal->readAll();
                if ($result['dataset']) {
                    $result['status'] = 1;
                    $result['message'] = 'Mostrando ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen facturaConsumidorFinals registrados';
                }
                break;
            case 'readOne':
                if (!isset($_POST['id_cliente']) || !$facturaConsumidorFinal->setId($_POST['id_cliente'])) {
                    $result['error'] = 'ID de cliente inválido';
                } else {
                    $result['dataset'] = $facturaConsumidorFinal->readOne();
                    if ($result['dataset']) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'facturaConsumidorFinal inexistente';
                    }
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$facturaConsumidorFinal->setIdCliente($_POST['idCliente']) or
                    !$facturaConsumidorFinal->setNit($_POST['nitCliente']) or
                    !$facturaConsumidorFinal->setNombreCliente($_POST['nombreCliente']) or
                    !$facturaConsumidorFinal->setDireccionCliente($_POST['direccionCliente']) or
                    !$facturaConsumidorFinal->setDepartamentoCliente($_POST['departamentoCliente']) or
                    !$facturaConsumidorFinal->setMunicipioCliente($_POST['municipioCliente']) or
                    !$facturaConsumidorFinal->setEmailCliente($_POST['emailCliente']) or
                    !$facturaConsumidorFinal->setTelefonoCliente($_POST['telefonoCliente']) or
                    !$facturaConsumidorFinal->setDuiCliente($_POST['duiCliente']) or
                    !$facturaConsumidorFinal->setIdServicio($_POST['idServicio']) or
                    !$facturaConsumidorFinal->setIdEmpleado($_POST['idEmpleado']) or
                    !$facturaConsumidorFinal->setMonto($_POST['monto']) or
                    !$facturaConsumidorFinal->setIdFactura($_POST['idFactura']) or
                    !$facturaConsumidorFinal->setFechaEmision($_POST['fechaEmision']) 

                ) {
                    $result['error'] = $facturaConsumidorFinal->getDataError();
                } elseif ($facturaConsumidorFinal->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'facturaConsumidorFinal modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el facturaConsumidorFinal';
                }
                break;
            case 'deleteRow':
                if (!isset($_POST['id_cliente']) || !$facturaConsumidorFinal->setId($_POST['id_cliente'])) {
                    $result['error'] = 'ID de cliente inválido';
                } else {
                    if ($facturaConsumidorFinal->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'facturaConsumidorFinal eliminado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el facturaConsumidorFinal';
                    }
                }
                break;
            default:
                $result['error'] = 'Acción no disponible';
        }
        // Se obtiene la excepción del servidor de bd por si ocurrió un problema.
        $result['exception'] = Database::getException();
        // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
        header('Content-type: application/json; charset=utf-8');
        // Se imprime el resultado en formato JSON y se retorna al controlador.
        print(json_encode($result));
    } else {
        print(json_encode('Acceso denegado'));
    }
} else {
    print(json_encode('Recurso no disponible'));
}
