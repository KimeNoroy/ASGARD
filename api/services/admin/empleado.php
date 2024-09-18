<?php
require_once('../../models/data/empleados_data.php');

if (isset($_GET['action'])) {
    session_start();
    $empleado = new EmpleadoData;
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null);

    if (isset($_SESSION['idAdministrador'])) {
        $result['session'] = 1;

        switch ($_GET['action']) {
            case 'readAll':
                if ($result['dataset'] = $empleado->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen empleados registrados';
                }
                break;

            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$empleado->setNombre($_POST['nombres_empleado']) or
                    !$empleado->setApellido($_POST['apellidos_empleado']) or
                    !$empleado->setDui($_POST['dui_empleado']) or
                    !$empleado->setEmail($_POST['email_empleado'])
                ) {
                    $result['error'] = $empleado->getDataError();
                } elseif ($empleado->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el empleado';
                }
                break;

            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
        }
    } else {
        $result['error'] = 'Debe iniciar sesión para realizar esta acción';
    }

    $result['exception'] = Database::getException();
    header('Content-type: application/json; charset=utf-8');
    print(json_encode($result));
} else {
    print(json_encode('Recurso no disponible'));
}
?>