<?php
// Se incluye la clase del modelo.
require_once('../../models/data/comprobante_credito_fiscal_data_admin.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $comprobante = new ComprobanteCreditoFiscal;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['buscar'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $comprobante->searchRows($_POST['buscar'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
                case 'createRow':
                    $_POST = Validator::validateForm($_POST);
                    if (
                    // Establecer los datos del comprobante desde $_POST
                    !$comprobante->setCliente($_POST['id_cliente']);
                    !$comprobante->setServicio($_POST['id_servicio']);
                    !$comprobante->setNit($_POST['nit']);
                    !$comprobante->setNombre($_POST['nombre']);
                    !$comprobante->setNrc($_POST['nrc']);
                    !$comprobante->setGiro($_POST['giro']);
                    !$comprobante->setDireccion($_POST['direccion']);
                    !$comprobante->setEmail($_POST['email']);
                    !$comprobante->setTelefono($_POST['telefono']);
                    !$comprobante->setDui($_POST['dui']);
                    !$comprobante->setFechaEmision($_POST['fechaEmision']); \
                    // Ejecutar la creación del comprobante
                    ) {
                        $result['error'] = $comprobante->getDataError();
                    } elseif ($comprobante->createRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Comprobante creado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al crear el comprobante';
                    }
                    break;
                
                
            case 'readAll':
                $result['dataset'] = $comprobante->readAll();
                if ($result['dataset']) {
                    $result['status'] = 1;
                    $result['message'] = 'Mostrando ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen comprobantes registrados';
                }
                break;
            case 'readOne':
                if (!$comprobante->setId($_POST['id_comprobante'])) {
                    $result['error'] = 'ID es inválido';
                } else {
                    $result['dataset'] = $comprobante->readOne();
                    if ($result['dataset']) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'Comprobante inexistente';
                    }
                }
                break;
                case 'updateRow':
                    $_POST = Validator::validateForm($_POST);
                    if (
                        !isset($_POST['id_comprobante']) or
                        !$comprobante->setId($_POST['id_comprobante']) or
                        !$comprobante->setCliente($_POST['id_cliente']) or
                        !$comprobante->setServicio($_POST['id_servicio']) or
                        !$comprobante->setNit($_POST['nit']) or
                        !$comprobante->setNombre($_POST['nombre']) or
                        !$comprobante->setNrc($_POST['nrc']) or
                        !$comprobante->setGiro($_POST['giro']) or
                        !$comprobante->setDireccion($_POST['direccion']) or
                        !$comprobante->setEmail($_POST['email']) or
                        !$comprobante->setTelefono($_POST['telefono']) or
                        !$comprobante->setDui($_POST['dui']) or
                        !$comprobante->setFechaEmision($_POST['fechaEmision'])
                    ) {
                        $result['error'] = $comprobante->getDataError();
                    } elseif ($comprobante->updateRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Comprobante modificado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al modificar el comprobante';
                    }
                    break;
                
            case 'deleteRow':
                if (!$comprobante->setId($_POST['id_comprobante'])) {
                    $result['error'] = 'ID de comprobante inválido';
                } else {
                    if ($comprobante->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Comprobante eliminado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el comprobante';
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
        print(json_encode(array('error' => 'Acceso denegado')));
    }
} else {
    print(json_encode(array('error' => 'Recurso no disponible')));
}
?>
