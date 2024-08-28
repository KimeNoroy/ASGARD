<?php
// Se incluye la clase del modelo.
require_once('../../models/data/clientes_data.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $cliente = new ClienteData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['idAdministrador'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $cliente->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$cliente->setNombre($_POST['nombreCliente']) or
                    !$cliente->setApellido($_POST['apellidoCliente']) or
                    !$cliente->setCorreo($_POST['emailCliente']) or
                    !$cliente->setDUI($_POST['nitCliente']) or
                    !$cliente->setDireccion($_POST['direccionCliente']) or
                    !$cliente->setDepartamento($_POST['departamentoCliente']) or
                    !$cliente->setMunicipio($_POST['municipioCliente']) or
                    !$cliente->setTelefono($_POST['telefonoCliente']) or
                    !$cliente->setDUI($_POST['duiCliente'])
                ) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($cliente->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el Cliente';
                }
                break;
            case 'readAll':
                if ($result['dataset'] = $cliente->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen clientes registrados';
                }
                break;

                // case 'readDashboardStats':
                //     if ($result['dataset'] = $cliente->readDashboardStats()) {
                //         $result['status'] = 1;
                //         $result['message'] = 'Chido';
                //     } else {
                //         $result['error'] = 'No existen cliente registrados';
                //     }
                //     break;

            case 'readOne':
                if (!isset($_POST['idCliente']) || !$cliente->setId($_POST['idCliente'])) {
                    $result['error'] = 'Cliente incorrecto';
                } elseif ($result['dataset'] = $cliente->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Cliente inexistente';
                }
                break;

            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$cliente->setId($_POST['idCliente']) or
                    !$cliente->setNombre($_POST['nombreCliente']) or
                    !$cliente->setApellido($_POST['apellidoCliente']) or
                    !$cliente->setCorreo($_POST['emailCliente']) or
                    !$cliente->setNit($_POST['nitCliente']) or
                    !$cliente->setDireccion($_POST['direccionCliente']) or
                    !$cliente->setDepartamento($_POST['departamentoCliente']) or
                    !$cliente->setMunicipio($_POST['municipioCliente']) or
                    !$cliente->setTelefono($_POST['telefonoCliente']) or
                    !$cliente->setDui($_POST['duiCliente'])
                ) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($cliente->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el cliente';
                }
                break;

            case 'deleteRow':
                // Agregar depuración para verificar la recepción de datos
                error_log('Datos recibidos en deleteRow: ' . json_encode($_POST));
                if (!isset($_POST['idCliente'])) {
                    $result['error'] = 'El idCliente no está definido';
                } elseif (!$cliente->setId($_POST['idCliente'])) {
                    $result['error'] = $cliente->getDataError();
                } elseif ($cliente->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Cliente eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el Cliente';
                }
                break;

            case 'getUser':
                if (isset($_SESSION['emailCliente'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['emailCliente'];
                } else {
                    $result['error'] = 'Email de cliente indefinido';
                }
                break;
            //Case para predecir los clientes del siguiente mes    
            case 'graficoPrediccionClientes':
                $result['action'] = $_GET['action'];
                // Llamar a la función que realiza la predicción
                if ($prediccion = $cliente->graficoPrediccionClientes()) {
                    $result['status'] = 1;
                    $result['prediccion'] = $prediccion;
                } else {
                    $result['error'] = 'No se pudo realizar la predicción';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($cliente->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Debe autenticarse para ingresar';
                } else {
                    $result['error'] = 'Debe crear un administrador para comenzar';
                }
                break;
        }
    }
    // Se obtiene la excepción del servidor de base de datos por si ocurrió un problema.
    $result['exception'] = Database::getException();
    // Se indica el tipo de contenido a mostrar y su respectivo conjunto de caracteres.
    header('Content-type: application/json; charset=utf-8');
    // Se imprime el resultado en formato JSON y se retorna al controlador.
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}