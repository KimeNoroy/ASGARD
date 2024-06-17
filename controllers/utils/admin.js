/*
*   Controlador de uso general en las páginas web del sitio privado.
*   Sirve para manejar la plantilla del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'services/admin/administrador.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
// Se establece el título de la página web.
document.querySelector('title').textContent = 'ASGAR - ADMIN';
// Constante para establecer el elemento del título principal.
//const MAIN_TITLE = document.getElementById('mainTitle');
//MAIN_TITLE.classList.add('text-center', 'py-3');

/*  Función asíncrona para cargar el encabezado y pie del documento.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const loadTemplate = async () => {
    // Petición para obtener en nombre del usuario que ha iniciado sesión.
    const DATA = await fetchData(USER_API, 'getUser');
    // Se verifica si el usuario está autenticado, de lo contrario se envía a iniciar sesión.
    if (DATA.session) {
        // Se comprueba si existe un alias definido para el usuario, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se agrega el encabezado de la página web antes del contenido principal.
            MAIN.insertAdjacentHTML('beforebegin', `
                <header>
                    <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary">
                        <div class="container">
                            <a class="navbar-brand" href="dashboard.html">
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarContent">
                                <ul class="navbar-nav ms-auto">
                                    <li class="nav-item">
                                        <a class="nav-link" href="producto.html">Productos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="categoria.html">Categorías</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="administrador.html">Administradores</a>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Cuenta: <b>${DATA.username}</b></a>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="profile.html">Editar perfil</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#" onclick="logOut()">Cerrar sesión</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </header>
            `);

            // Se agrega el pie de la página web después del contenido principal.
        } else {
            sweetAlert(3, DATA.error, false, 'index.html');
        }
    } else {
        // Se comprueba si la página web es la principal, de lo contrario se direcciona a iniciar sesión.
        if (location.pathname.endsWith('index.html')) {
            // Se agrega el encabezado de la página web antes del contenido principal.
            MAIN.insertAdjacentHTML('beforebegin', `
                <header>
                    <nav class="navbar fixed-top bg-body-tertiary">
                        <div class="container">
                            <a class="navbar-brand" href="index.html">
                            </a>
                        </div>
                    </nav>
                </header>
            `);
        } else {
            location.href = 'index.html';
        }
    }
}