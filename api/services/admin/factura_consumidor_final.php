<?php
// Se incluye la clase del modelo.
require_once('../../models/data/factura_consumidor_final.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $usuario = new factura_consumidor_final;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_cliente'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!isset($_POST['buscarusuario']) || !Validator::validateSearch($_POST['buscarusuario'])) {
                    $result['error'] = 'Búsqueda inválida';
                } else {
                    Validator::setSearchValue($_POST['buscarusuario']);
                    $result['dataset'] = $usuario->searchRows();
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
                    !isset($_POST['nombre_cliente']) ||
                    !$usuario->setNombre($_POST['nombre_cliente'])
                ) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($usuario->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el usuario';
                }
                break;
            case 'readAll':
                $result['dataset'] = $usuario->readAll();
                if ($result['dataset']) {
                    $result['status'] = 1;
                    $result['message'] = 'Mostrando ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen usuarios registrados';
                }
                break;
            case 'readOne':
                if (!isset($_POST['id_cliente']) || !$usuario->setId($_POST['id_cliente'])) {
                    $result['error'] = 'ID de cliente inválido';
                } else {
                    $result['dataset'] = $usuario->readOne();
                    if ($result['dataset']) {
                        $result['status'] = 1;
                    } else {
                        $result['error'] = 'Usuario inexistente';
                    }
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !isset($_POST['id_cliente']) ||
                    !isset($_POST['nombre_cliente']) ||
                    !$usuario->setId($_POST['id_cliente']) ||
                    !$usuario->setNombre($_POST['nombre_cliente'])
                ) {
                    $result['error'] = $usuario->getDataError();
                } elseif ($usuario->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Usuario modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el usuario';
                }
                break;
            case 'deleteRow':
                if (!isset($_POST['id_cliente']) || !$usuario->setId($_POST['id_cliente'])) {
                    $result['error'] = 'ID de cliente inválido';
                } else {
                    if ($usuario->deleteRow()) {
                        $result['status'] = 1;
                        $result['message'] = 'Usuario eliminado correctamente';
                    } else {
                        $result['error'] = 'Ocurrió un problema al eliminar el usuario';
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
