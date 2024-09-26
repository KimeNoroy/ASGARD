// Constante para establecer el formulario de registro del primer usuario.
// Constante para establecer el formulario de inicio de sesión.
const ADMIN_API = 'services/admin/administrador.php';

const LOGIN_FORM = document.getElementById('logInForm');
const LOGIN_CONFIRM = document.getElementById('logInConfirmForm');
const CHANGE_PASSWORD = document.getElementById('ninetyDaysPasswordChangerForm');
const SIGNUP_FORM = document.getElementById('signupForm');


const LOGIN_CONTENT = document.getElementById("loginContent");
const LOGIN_CONFIRM_CONTENT = document.getElementById("logInConfirmContent");
const CHANGE_PASSWORD_CONTENT = document.getElementById("ninetyDaysPasswordChangerContent");
const SIGNUP_CONTENT = document.getElementById("signUpContent");

var token_2fa = '';
var token_passchange = '';

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', async () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    // Petición para consultar los usuarios registrados.
    const DATA = await fetchData(USER_API, 'readUsers');
    // Se comprueba si existe una sesión, de lo contrario se sigue con el flujo normal.
    if (DATA.session) {
        // Se direcciona a la página web de bienvenida.
        location.href = 'inicio.html';
    } else if (DATA.status) {
        // Se establece el título del contenido principal.
        // Se muestra el formulario para iniciar sesión.
        LOGIN_CONTENT.classList.add('show');
        sweetAlert(4, DATA.message, true);
    } else {
        // Se establece el título del contenido principal.
        // Se muestra el formulario para registrar el primer usuario.
        SIGNUP_CONTENT.classList.add('show');
        sweetAlert(4, DATA.error, true);
    }
});

// Método del evento para cuando se envía el formulario de registro del primer usuario.
SIGNUP_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SIGNUP_FORM);
    // Petición para registrar el primer usuario del sitio privado.
    const DATA = await fetchData(USER_API, 'signUp', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        sweetAlert(1, DATA.message, true, 'index.html');
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

// Evento para cuando se envía el formulario de guardar
LOGIN_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(LOGIN_VALIDATOR_FORM);
    // Petición para iniciar sesión.
    const DATA = await fetchData(ADMIN_API, 'logIn', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {

        sweetAlert(1, DATA.message, true);

        if(DATA.dataset[0] == "authenticated"){
            window.location.href = "dashboard.html"
        } else if(DATA.dataset[0] == "passchange"){
            token_passchange = DATA.dataset[1];
            LOGIN_VALIDATOR_FORM.classList.add('hide');
            CHANGE_PASSWORD.classList.remove('hide');
        } else{
            token_2fa = DATA.dataset[1];
            LOGIN_VALIDATOR_FORM.classList.add('hide');
            LOGIN_CONFIRM.classList.remove('hide');
        }

    } else {
        sweetAlert(2, DATA.error, false);
        LOADING_SCREEN.classList.add('hide')
    }
});

LOGIN_CONFIRM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    LOADING_SCREEN.classList.remove('hide')
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(LOGIN_CONFIRM);
    FORM.append('token', token_2fa);
    // Petición para iniciar sesión.
    const DATA = await fetchData(ADMIN_API, 'logIn', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        sweetAlert(1, DATA.message, true);
        LOADING_SCREEN.classList.add('hide')
        if(DATA.dataset[0] == "authenticated"){
            sweetAlert(1, DATA.message, true, 'inicio.html');
        } else if(DATA.dataset[0] == "passchange"){
            token_passchange = DATA.dataset[1];
            LOGIN_CONFIRM.classList.add('hide');
            CHANGE_PASSWORD.classList.remove('hide');
        }

    } else {
        sweetAlert(2, DATA.error, false);
        LOADING_SCREEN.classList.add('hide')
    }
});

CHANGE_PASSWORD.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    LOADING_SCREEN.classList.remove('hide')
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(CHANGE_PASSWORD);
    FORM.append('token', token_passchange);
    // Petición para iniciar sesión.
    const DATA = await fetchData(ADMIN_API, 'ninetyDaysPasswordChanger', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        sweetAlert(1, DATA.message, true);
        if(DATA.dataset[0] == "authenticated"){
            sweetAlert(1, DATA.message, true, 'dashboard.html');
        } else{
            sweetAlert(1, DATA.error, true);
        }

    } else {
        sweetAlert(2, DATA.error, false);
        LOADING_SCREEN.classList.add('hide')
    }
});


