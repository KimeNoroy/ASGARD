<?php
// Se incluye la clase del modelo.
require_once('../../models/data/administrador_data.php');
require_once('../../helpers/email.php');

// Se comprueba si existe una acción a realizar, de lo contrario se finaliza el script con un mensaje de error.
if (isset($_GET['action'])) {
    // Se crea una sesión o se reanuda la actual para poder utilizar variables de sesión en el script.
    session_start();

    // Tiempo límite de inactividad en segundos
    $inactiveLimit = 3000000; // Set inactivity limit in seconds

    // Verifica y actualiza el tiempo de actividad
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time(); // Initialize last activity time
    } else if (time() - $_SESSION['last_activity'] > $inactiveLimit) {
        session_unset(); // Limpia la sesión
        session_destroy(); // Destruye la sesión
        echo "La sesión ha sido destruida por inactividad.";
    }
    
    // Actualiza el tiempo de actividad
    $_SESSION['last_activity'] = time();

    // Se instancia la clase correspondiente.
    $administrador = new AdministradorData;
    // Se declara e inicializa un arreglo para guardar el resultado que retorna la API.
    $result = array('status' => 0, 'session' => 0, 'message' => null, 'dataset' => null, 'error' => null, 'exception' => null, 'username' => null);
    // Se verifica si existe una sesión iniciada como administrador, de lo contrario se finaliza el script con un mensaje de error.

    if(isset($_SESSION['tempChanger'])){
        if ($_SESSION['tempChanger']['expiration_time'] <= time() or !$administrador->validatePassword()){
            unset($_SESSION['tempChanger']);
            unset($_SESSION['90_days_password_changer']);
        }
    }

    if(isset($_SESSION['tempChanger'])){

        switch ($_GET['action']) {
            case 'ninetyDaysPasswordChanger':
                $_POST = Validator::validateForm($_POST);
                if(!$administrador->validatePassword()){
                    $result['error'] = 'No es tiempo para cambiar su contraseña aún';
                } elseif (!isset($_POST['token'])) {
                    $result['error'] = "El token no fue proporcionado";
                } elseif ($_SESSION['90_days_password_changer'] != $_POST["token"]) {
                    $result['error'] = 'El token es invalido';
                } elseif ($_POST['claveNueva'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$administrador->setContraseña($_POST['claveNueva'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->changeTempPassword()) {
                    unset($_SESSION['90_days_password_changer']);
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                    $result['dataset'] = ["authenticated"];
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;
            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                $administrador->clearValidator();
                if ($administrador->checkUser($_POST['email'], $_POST['clave'])==2) {
                    $result['dataset'] = ["change", $_SESSION['90_days_password_changer']];
                } elseif($administrador->setValidator($_POST['email'])) {
                    $result['error'] = 'Credenciales incorrectas';
                }
                break;
            case 'logIn2':
                $_POST = Validator::validateForm($_POST);
                    
                // Verificar que 'email' y 'clave' están definidos
                if (isset($_POST['email']) && isset($_POST['clave'])) {
                if ($administrador->checkUser($_POST['email'], $_POST['clave'])) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                } else {
                    $result['error'] = 'Credenciales incorrectas';
                }
                } else {
                    $result['error'] = 'Email y contraseña son requeridos';
                }
                break;
            default:
                $result['error'] = 'Obligatorio cambiar de contraseña antes de realizar una acción';
        }


    }
    
    elseif (isset($_SESSION['idAdministrador'])) {
        $result['session'] = 1;
        // Se compara la acción a realizar cuando un administrador ha iniciado sesión.
        switch ($_GET['action']) {
            case 'searchRows':
                if (!Validator::validateSearch($_POST['search'])) {
                    $result['error'] = Validator::getSearchError();
                } elseif ($result['dataset'] = $administrador->searchRows()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' coincidencias';
                } else {
                    $result['error'] = 'No hay coincidencias';
                }
                break;

            case 'createRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombreAdministrador']) or
                    !$administrador->setApellido($_POST['apellidoAdministrador']) or
                    !$administrador->setEmail($_POST['correoAdministrador']) or
                    !$administrador->setContraseña($_POST['claveAdministrador'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_POST['claveAdministrador'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($administrador->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el administrador';
                }
                break;

            case 'createTrabajadores':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['NAdmin']) or
                    !$administrador->setApellido($_POST['ApAdmin']) or
                    !$administrador->setEmail($_POST['CorreoAd']) or
                    !$administrador->setContraseña($_POST['ContraAd'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_POST['ContraAd'] != $_POST['confirmarClaveA']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($administrador->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador creado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al crear el administrador';
                }
                break;

            case 'readAll':
                if ($result['dataset'] = $administrador->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Existen ' . count($result['dataset']) . ' registros';
                } else {
                    $result['error'] = 'No existen administradores registradoss';
                }
                break;

            case 'readDashboardStats':
                if ($result['dataset'] = $administrador->readDashboardStats()) {
                    $result['status'] = 1;
                    $result['message'] = 'Chido';
                } else {
                    $result['error'] = 'No existen administradores registrados';
                }
                break;

            case 'readOne':
                if (!$administrador->setId($_POST['idAdmin'])) {
                    $result['error'] = 'Administrador incorrecto';
                } elseif ($result['dataset'] = $administrador->readOne()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Administrador inexistente';
                }
                break;
            case 'updateRow2':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setId($_POST['idAdministrador']) or
                    !$administrador->setNombre($_POST['nombreAdministrador']) or
                    !$administrador->setApellido($_POST['apellidoAdministrador'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->updateRow2()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el administrador';
                }
                break;
            case 'updateRow':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setId($_POST['idAdmin']) or
                    !$administrador->setNombre($_POST['NAdmin']) or
                    !$administrador->setApellido($_POST['ApAdmin']) or
                    !$administrador->setEmail($_POST['CorreoAd'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->updateRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador modificado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al modificar el administrador';
                }
                break;
            case 'deleteRow':
                if ($_POST['idAdmin'] == $_SESSION['idAdministrador']) {
                    $result['error'] = 'No se puede eliminar a sí mismo';
                } elseif (!$administrador->setId($_POST['idAdmin'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->deleteRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Empleado eliminado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al eliminar el administrador';
                }
                break;

            case 'getUser':
                if (isset($_SESSION['emailAdministrador'])) {
                    $result['status'] = 1;
                    $result['username'] = $_SESSION['emailAdministrador'];
                } else {
                    $result['error'] = 'Email de administrador indefinido';
                }
                break;

            case 'logOut':
                if (session_destroy()) {
                    $result['status'] = 1;
                    $result['message'] = 'Sesión cerrada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cerrar la sesión';
                }
                break;
            case 'readProfile':
                if ($result['dataset'] = $administrador->readProfile()) {
                    $result['status'] = 1;
                } else {
                    $result['error'] = 'Ocurrió un problema al leer el perfil';
                }

            case 'editProfile':
                $_POST = Validator::validateForm($_POST);

                // Verifica si los datos esperados están presentes en $_POST
                if (
                    !isset($_POST['nombreAdministrador']) ||
                    !isset($_POST['apellidoAdministrador']) ||
                    !isset($_POST['emailAdministrador'])
                ) {
                    $result['error'] = 'Datos incompletos. Faltan campos requeridos.';
                } else {
                    if (
                        !$administrador->setNombre($_POST['nombreAdministrador']) or
                        !$administrador->setApellido($_POST['apellidoAdministrador']) or
                        !$administrador->setEmail($_POST['emailAdministrador'])
                    ) {

                        $result['error'] = 'Datos incompletos. Faltan campos requeridos.';
                    } else {
                        if (
                            !$administrador->setNombre($_POST['nombreAdministrador']) or
                            !$administrador->setApellido($_POST['apellidoAdministrador']) or
                            !$administrador->setEmail($_POST['emailAdministrador'])
                        ) {
                            $result['error'] = $administrador->getDataError();
                        } elseif ($administrador->editProfile()) {
                            $result['status'] = 1;
                            $result['message'] = 'Perfil modificado correctamente';
                
                            // Verifica si 'aliasAdministrador' fue enviado en $_POST
                            if (isset($_POST['emailAdministrador'])) {
                                $_SESSION['emailAdministrador'] = $_POST['emailAdministrador'];
                            } else {
                                $result['error'] = "El alias del administrador no fue proporcionado.";
                            }
                        } else {
                            $result['error'] = 'Ocurrió un problema al modificar el perfil';
                        }
                    } 
                }   
                    break;
            // case 'emailPasswordSender':
            //         $_POST = Validator::validateForm($_POST);
                
            //         // Validar y establecer el email
            //         if (!$administrador->setEmail($_POST['emailAdministrador'])) {
            //             $result['error'] = $administrador->getDataError();
            //         } elseif ($administrador->editProfile()) {
            //             $result['status'] = 1;
            //             $result['message'] = 'Perfil modificado correctamente';

            //             // Verifica si 'aliasAdministrador' fue enviado en $_POST
            //             if (isset($_POST['aliasAdministrador'])) {
            //                 $_SESSION['aliasAdministrador'] = $_POST['aliasAdministrador'];
            //             } else {
            //                 $result['error'] = "El alias del administrador no fue proporcionado.";
            //             }
            //         } else {
            //             $result['error'] = 'Ocurrió un problema al modificar el perfil';
            //         }           
            //         break;
    
            case 'emailPasswordSender':
                $_POST = Validator::validateForm($_POST);
            
                // Validar y establecer el email
                if (!$administrador->setEmail($_POST['emailAdministrador'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->verifyExistingEmail()) {
                    // Generar código de cambio de contraseña y token
                    $secret_change_password_code = mt_rand(10000000, 99999999);
                    $token = Validator::generateRandomString(64);
            
                    // Almacenar código y token en sesión con tiempo de expiración
                    $_SESSION['secret_change_password_code'] = [
                        'code' => $secret_change_password_code,
                        'token' => $token,
                        'expiration_time' => time() + (60 * 15) // Expira en 15 minutos
                    ];
            
                    $_SESSION['usuario_correo_vcc'] = [
                        'correo' => $_POST['emailAdministrador'],
                        'expiration_time' => time() + (60 * 25) // Expira en 25 minutos
                    ];
            
                    // Enviar correo de verificación
                    sendVerificationEmail($_POST['emailAdministrador'], $secret_change_password_code);
            
                    $result['status'] = 1;
                    $result['message'] = 'Correo enviado';
                    $result['dataset'] = $token;
                } else {
                    $result['error'] = 'El correo indicado no existe';
                } // Cierre de if-elseif-else
            
                break; // Cierre del case
                    
    
            case 'emailPasswordValidator':
                $_POST = Validator::validateForm($_POST);

                if (!isset($_POST['secretCode'])) {
                    $result['error'] = "El código no fue proporcionado";
                } elseif (!isset($_POST["token"])) {
                    $result['error'] = 'El token no fue proporcionado';
                } elseif (!(ctype_digit($_POST['secretCode']) && strlen($_POST['secretCode']) === 8)) {
                    $result['error'] = "El código es inválido";
                } elseif (!isset($_SESSION['secret_change_password_code'])) {
                    $result['message'] = "El código ha expirado";
                } elseif ($_SESSION['secret_change_password_code']['token'] != $_POST["token"]) {
                    $result['error'] = 'El token es invalido';
                } elseif ($_SESSION['secret_change_password_code']['expiration_time'] <= time()) {
                    $result['message'] = "El código ha expirado.";
                    unset($_SESSION['secret_change_password_code']);
                } elseif ($_SESSION['secret_change_password_code']['code'] == $_POST['secretCode']) {
                    $token = Validator::generateRandomString(64);
                    $_SESSION['secret_change_password_code_validated'] = [
                        'token' => $token,
                        'expiration_time' => time() + (60 * 10) # (x*y) y=minutos de vida 
                    ];
                    $result['status'] = 1;
                    $result['message'] = "Verificación Correcta";
                    $result['dataset'] = $token;
                    unset($_SESSION['secret_change_password_code']);
                } else {
                    $result['error'] = "El código es incorrecto";
                }
                break;

            case 'changePasswordByEmail':
                $_POST = Validator::validateForm($_POST);
                if (!$administrador->setContraseña($_POST['nuevaClave'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif (!isset($_POST["token"])) {
                    $result['error'] = 'El token no fue proporcionado';
                } elseif ($_SESSION['secret_change_password_code_validated']['expiration_time'] <= time()) {
                    $result['error'] = 'El tiempo para cambiar su contraseña ha expirado';
                    unset($_SESSION['secret_change_password_code_validated']);
                } elseif ($_SESSION['secret_change_password_code_validated']['token'] != $_POST["token"]) {
                    $result['error'] = 'El token es invalido';
                } elseif ($_POST['nuevaClave'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$administrador->setContraseña($_POST['nuevaClave'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_SESSION['usuario_correo_vcc']['expiration_time'] <= time()) {
                    $result['error'] = 'El tiempo para cambiar su contraseña ha expirado';
                    unset($_SESSION['usuario_correo_vcc']);
                } elseif ($administrador->changePasswordFromEmail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                    unset($_SESSION['secret_change_password_code_validated']);
                    unset($_SESSION['usuario_correo_vcc']);
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;

            case 'changePassword':
                $_POST = Validator::validateForm($_POST);
                if (!$administrador->checkPassword($_POST['claveActual'])) {
                    $result['error'] = 'Contraseña actual incorrecta';
                } elseif ($_POST['claveNueva'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$administrador->setContraseña($_POST['claveNueva'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->changePassword()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;
            default:
                $result['error'] = 'Acción no disponible dentro de la sesión';
            }
    } 
    
    else {
        // Se compara la acción a realizar cuando el administrador no ha iniciado sesión.
        switch ($_GET['action']) {
            case 'readUsers':
                if ($administrador->readAll()) {
                    $result['status'] = 1;
                    $result['message'] = 'Debe autenticarse para ingresar';
                } else {
                    $result['error'] = 'Debe crear un administrador para comenzar';
                }
                break;
            case 'signUp':
                $_POST = Validator::validateForm($_POST);
                if (
                    !$administrador->setNombre($_POST['nombreAdministrador']) or
                    !$administrador->setApellido($_POST['apellidoAdministrador']) or
                    !$administrador->setEmail($_POST['emailAdministrador']) or
                    !$administrador->setContraseña($_POST['claveAdministrador'])
                ) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_POST['claveAdministrador'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Contraseñas diferentes';
                } elseif ($administrador->createRow()) {
                    $result['status'] = 1;
                    $result['message'] = 'Administrador registrado correctamente';
                } else {
                    $result['error'] = 'Ocurrió un problema al registrar el administrador';
                }
                break;
            
            

            case 'emailPasswordSender':
                $_POST = Validator::validateForm($_POST);

                // Validar y establecer el email
                if (!$administrador->setEmail($_POST['emailAdministrador'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($administrador->verifyExistingEmail()) {
                    // Generar código de cambio de contraseña y token
                    $secret_change_password_code = mt_rand(10000000, 99999999);
                    $token = Validator::generateRandomString(64);

                    // Almacenar código y token en sesión con tiempo de expiración
                    $_SESSION['secret_change_password_code'] = [
                        'code' => $secret_change_password_code,
                        'token' => $token,
                        'expiration_time' => time() + (60 * 15) // Expira en 15 minutos
                    ];

                    $_SESSION['usuario_correo_vcc'] = [
                        'correo' => $_POST['emailAdministrador'],
                        'expiration_time' => time() + (60 * 25) // Expira en 25 minutos
                    ];

                    // Enviar correo de verificación
                    sendVerificationEmail($_POST['emailAdministrador'], $secret_change_password_code);

                    $result['status'] = 1;
                    $result['message'] = 'Correo enviado';
                    $result['dataset'] = $token;
                } else {
                    $result['error'] = 'El correo indicado no existe';
                }
                break;
            case 'emailPasswordValidator':
                $_POST = Validator::validateForm($_POST);

                if (!isset($_POST['secretCode'])) {
                    $result['error'] = "El código no fue proporcionado";
                } elseif (!isset($_POST["token"])) {
                    $result['error'] = 'El token no fue proporcionado';
                } elseif (!(ctype_digit($_POST['secretCode']) && strlen($_POST['secretCode']) === 8)) {
                    $result['error'] = "El código es inválido";
                } elseif (!isset($_SESSION['secret_change_password_code'])) {
                    $result['message'] = "El código ha expirado";
                } elseif ($_SESSION['secret_change_password_code']['token'] != $_POST["token"]) {
                    $result['error'] = 'El token es invalido';
                } elseif ($_SESSION['secret_change_password_code']['expiration_time'] <= time()) {
                    $result['message'] = "El código ha expirado.";
                    unset($_SESSION['secret_change_password_code']);
                } elseif ($_SESSION['secret_change_password_code']['code'] == $_POST['secretCode']) {
                    $token = Validator::generateRandomString(64);
                    $_SESSION['secret_change_password_code_validated'] = [
                        'token' => $token,
                        'expiration_time' => time() + (60 * 10) # (x*y) y=minutos de vida 
                    ];
                    $result['status'] = 1;
                    $result['message'] = "Verificación Correcta";
                    $result['dataset'] = $token;
                    unset($_SESSION['secret_change_password_code']);
                } else {
                    $result['error'] = "El código es incorrecto";
                }
                break;
            case 'changePasswordByEmail':
                $_POST = Validator::validateForm($_POST);
                if (!$administrador->setContraseña($_POST['nuevaClave'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif (!isset($_POST["token"])) {
                    $result['error'] = 'El token no fue proporcionado';
                } elseif ($_SESSION['secret_change_password_code_validated']['expiration_time'] <= time()) {
                    $result['error'] = 'El tiempo para cambiar su contraseña ha expirado';
                    unset($_SESSION['secret_change_password_code_validated']);
                } elseif ($_SESSION['secret_change_password_code_validated']['token'] != $_POST["token"]) {
                    $result['error'] = 'El token es invalido';
                } elseif ($_POST['nuevaClave'] != $_POST['confirmarClave']) {
                    $result['error'] = 'Confirmación de contraseña diferente';
                } elseif (!$administrador->setContraseña($_POST['nuevaClave'])) {
                    $result['error'] = $administrador->getDataError();
                } elseif ($_SESSION['usuario_correo_vcc']['expiration_time'] <= time()) {
                    $result['error'] = 'El tiempo para cambiar su contraseña ha expirado';
                    unset($_SESSION['usuario_correo_vcc']);
                } elseif ($administrador->changePasswordFromEmail()) {
                    $result['status'] = 1;
                    $result['message'] = 'Contraseña cambiada correctamente';
                    unset($_SESSION['secret_change_password_code_validated']);
                    unset($_SESSION['usuario_correo_vcc']);
                } else {
                    $result['error'] = 'Ocurrió un problema al cambiar la contraseña';
                }
                break;

                
            case 'logIn':
                $_POST = Validator::validateForm($_POST);
                $administrador->clearValidator();
                if(!$administrador->getValidator($_POST['email'])){
                    $result['error'] = 'Su cuenta se ha suspendido temporalmente';
                } elseif ($administrador->ValidateLogin($_POST['email'], $_POST['clave'])) {

                    $secrete_code = mt_rand(10000000, 99999999);
                    $token = Validator::generateRandomString(64);
            
                    // Almacenar código y token en sesión con tiempo de expiración
                    $_SESSION['login_validator'] = [
                        'code' => $secrete_code,
                        'token' => $token,
                        'email' => $_POST['email'],
                        'password' => $_POST['clave'],
                        'expiration_time' => time() + (60 * 15)
                    ];
            
                    // Enviar correo de verificación
                    sendVerificationEmail($_POST['email'], $secrete_code);

                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $result['dataset'] = ['2fa', $token];

                } elseif($administrador->setValidator($_POST['email'])) {
                    $result['error'] = 'Credenciales incorrectas';
                }
                break;
                
            case 'logInConfirm':
                $_POST = Validator::validateForm($_POST);
                $administrador->clearValidator();
                if(!isset($_SESSION['login_validator'])){
                    $result['error'] = 'Debe de iniciar sesión primero';
                } elseif ($_SESSION['login_validator']['expiration_time'] <= time()){
                    $result['message'] = "El código ha expirado.";
                    unset($_SESSION['login_validator']);
                } elseif($_SESSION['login_validator']['token'] != $_POST['token']){
                    $result['error'] = 'Token incorrecto';
                } elseif($_SESSION['login_validator']['code'] != $_POST['code']){
                    $result['error'] = 'Codigo incorrecto';
                } elseif(!$administrador->getValidator($_SESSION['login_validator']['email'])){
                    $result['error'] = 'Su cuenta se ha suspendido temporalmente';
                } elseif ($administrador->checkUser($_SESSION['login_validator']['email'], $_SESSION['login_validator']['password'])==1) {
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta';
                    $result['dataset'] = ["authenticated"];
                } elseif ($administrador->checkUser($_SESSION['login_validator']['email'], $_SESSION['login_validator']['password'])==2) {
                    $_SESSION['90_days_password_changer'] = Validator::generateRandomString(64);
                    $result['status'] = 1;
                    $result['message'] = 'Autenticación correcta, obligatorio cambio de contraseña';
                    $result['dataset'] = ["change", $_SESSION['90_days_password_changer']];
                } elseif($administrador->setValidator($_SESSION['login_validator']['email'])) {
                    $result['error'] = 'Credenciales incorrectas';
                }
                break;  

            default:
                $result['error'] = 'Acción no disponible fuera de la sesión';
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
