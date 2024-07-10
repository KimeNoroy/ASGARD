/*
*   Controlador de uso general en las páginas web del sitio privado.
*   Sirve para manejar la plantilla del encabezado y pie del documento.
*/

// Constante para completar la ruta de la API.
const USER_API = 'services/admin/administrador.php';
// Constante para establecer el elemento del contenido principal.
const MAIN = document.querySelector('main');
// Se establece el título de la página web.
document.querySelector('title').textContent = 'ASGARD';


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
            MAIN.insertAdjacentHTML(
                'beforebegin', `
                   <div class="menu">
<ion-icon name="menu-outline"></ion-icon>
<ion-icon name="close-outline"></ion-icon>
</div>

<div class="barra-lateral">
<div>
    <div class="nombre-pagina">
        <ion-icon id="cloud" name="cloud-outline"></ion-icon>
        <span><span class="amarillo">ASGARD</span></span>
    </div>  
</div>



<nav class="navegacion">
    <ul>
    <li>
    <a href="inicio.html" class="d-flex grid gap-2">
    <ion-icon name="home-outline"></ion-icon>
        <span>Inicio</span>
    </a>
</li>
     <li>
            <a id="" href="usuariofacturacion.html" class="d-flex grid gap-2">
                <ion-icon name="mail-unread-outline"></ion-icon>
                <span>Fanturación</span>
            </a>
        </li>
        <li>
            <a href="consumidorfinal.html" class="d-flex grid gap-2">
                <ion-icon name="paper-plane-outline"></ion-icon>
                <span>Consumidor final</span>
            </a>
        </li>
        <li>
            <a href="sujeto_excluido.html" class="d-flex grid gap-2">
                <ion-icon name="document-text-outline"></ion-icon>
                <span>Sujeto excluido electronico
                </span>
            </a>
        </li>
        <li>
            <a href="comprobante_credito_fiscal.html" class="d-flex grid gap-2">
                <ion-icon name="bookmark-outline"></ion-icon>
                <span>Comprobante de credito fiscal</span>
            </a>
        </li>
        <li>
            <a href="facturanormal.html" class="d-flex grid gap-2">
            <ion-icon name="albums-outline"></ion-icon>
                <span>factura normal</span>
            </a>
        </li>
    </ul>
    <div class="linea"></div>
    <ul>
    <li>
            <a href="#" class="d-flex grid gap-2">
            <ion-icon name="book-outline"></ion-icon>
                <span>Factura emitidas</span>
            </a>
        </li>
        <li>
            <a href="clientes.html" class="d-flex grid gap-2">
            <ion-icon name="person-outline"></ion-icon>
                <span>Clientes</span>
            </a>
        </li>
        <li>
            <a href="empleados.html" class="d-flex grid gap-2">
            <ion-icon name="person-add-outline"></ion-icon>
                <span>Empleados</span>
            </a>
        </li>
        <li>
            <a href="mi_perfil.html" class="d-flex grid gap-2">
            <ion-icon name="person-add-outline"></ion-icon>
                <span>Mi perfil</span>
            </a>
        </li>
          <li>
            <a onclick="logOut()" class="d-flex grid gap-2">
            <ion-icon name="log-out-outline"></ion-icon>
                <span >LogOut</span>
            </a>
        </li>
    </ul>
</nav>

<div>
    <div class="linea"></div>
        <div class="switch">
            <div class="base">
                <div class="circulo">
                    
                </div>
            </div>
        </div>
    </div>
    <div class="usuario">
</div>

            `);
            loadNavBarJs();
        } else {
            //sweetAlert(3, DATA.error, false, 'index.html');
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
                                <img src="../../resources/img/logo.png" alt="inventory" width="50">
                            </a>
                        </div>
                    </nav>
                </header>
            `);
        } else {
            //location.href = 'index.html';
        }
    }
}

const loadNavBarJs = () => {
    const SIDEBAR = document.getElementById("SIDEBAR");
    const SIDEBAR_ACTIVATOR = document.getElementById("SIDEBAR-ACTIVATOR");
    const MAIN = document.querySelector("main");
    let active = false;

    function toggleSidebar() {
        if (!active) {
            SIDEBAR.classList.add("active");
            MAIN.classList.add("blur");
        } else {
            SIDEBAR.classList.remove("active");
            MAIN.classList.remove("blur");
        }
        active = !active;

        // Detectar el ancho de la pantalla y quitar la clase "active" si es mayor a 600px
        if (window.matchMedia("(min-width: 600px)").matches) {
            SIDEBAR.classList.remove("active");
            MAIN.classList.remove("blur");
            active = false;
        }
    }

    //SIDEBAR_ACTIVATOR.addEventListener("click", toggleSidebar);

    // Lógica adicional para manejar cambios en el tamaño de la ventana
    window.addEventListener("resize", () => {
        // Si la pantalla es mayor a 600px y el sidebar está activo, desactívalo
        if (window.matchMedia("(min-width: 600px)").matches && active) {
            SIDEBAR.classList.remove("active");
            MAIN.classList.remove("blur");
            active = false;
        }
    });
}