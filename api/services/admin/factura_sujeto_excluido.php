<?php
// Se incluye la clase del modelo.
require_once('../../models/data/factura_sujeto_excluido.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();
    // Se instancia la clase correspondiente.
    $usuario = new factura_sujeto_excluido;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'fileStatus' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.
    if (isset($_SESSION['id_empleado'])) {
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['buscarUsuario'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $clientes->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;
            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$usuario->setNombre($_POST['nombre_cliente']) or
                    !$usuario->setApellido($_POST['apellido_cliente']) or
                    !$usuario->setDUI($_POST['dui_cliente'], 0) or
                    !$usuario->setNIT($_POST['nit_cliente'], 0) or
                    !$usuario->setFecha($_POST['fecha_emision']) or
                    !$usuario->setDireccion($_POST['direccion_cliente']) or
                    !$usuario->setDepartamento($_POST['departamento_cliente']) or
                    !$usuario->setMunicipio($_POST['municipio_cliente']) or
                    !$usuario->setEmail($_POST['email_cliente'], 0) or
                    !$usuario->setTelefono($_POST['telefono_cliente'], 0) or
                    !$usuario->setDescripcion($_POST['descripcion']) or
                    !$usuario->setTipoServicio($_POST['tipo_servicio']) or
                    !$usuario->setMonto($_POST['monto'])
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
                if (!$usuario->setId($_POST['id_factura'])) {
                    $result['error'] = 'ID es inválido';
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
                    !$usuario->setNombre($_POST['nombre_cliente']) or
                    !$usuario->setApellido($_POST['apellido_cliente']) or
                    !$usuario->setDUI($_POST['dui_cliente'], 1) or
                    !$usuario->setNIT($_POST['nit_cliente'], 1) or
                    !$usuario->setFecha($_POST['fecha_emision']) or
                    !$usuario->setDireccion($_POST['direccion_cliente']) or
                    !$usuario->setDepartamento($_POST['departamento_cliente']) or
                    !$usuario->setMunicipio($_POST['municipio_cliente']) or
                    !$usuario->setEmail($_POST['email_cliente'], 1) or
                    !$usuario->setTelefono($_POST['telefono_cliente'], 1) or
                    !$usuario->setDescripcion($_POST['descripcion']) or
                    !$usuario->setTipoServicio($_POST['tipo_servicio']) or
                    !$usuario->setMonto($_POST['monto'])
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
                if (!$usuario->setId($_POST['id_factura'])) {
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
