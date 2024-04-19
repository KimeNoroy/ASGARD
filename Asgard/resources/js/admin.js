const ASIDE = document.querySelector('aside');

//template del navbar
const NAVBAR =

    `    <div class="menu">
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
    <a href="inicio.html">
    <ion-icon name="home-outline"></ion-icon>
        <span>Inicio</span>
    </a>
</li>
     <li>
            <a id="" href="usuariofacturacion.html">
                <ion-icon name="mail-unread-outline"></ion-icon>
                <span>Fanturación</span>
            </a>
        </li>
        <li>
            <a href="usuariocreditofiscal.html">
                <ion-icon name="star-outline"></ion-icon>
                <span>Credito fiscal</span>
            </a>
        </li>
        <li>
            <a href="consumidorfinal.html">
                <ion-icon name="paper-plane-outline"></ion-icon>
                <span>Consumidor final</span>
            </a>
        </li>
        <li>
            <a href="sujetoexcluidoelectrónico.html">
                <ion-icon name="document-text-outline"></ion-icon>
                <span>Sujeto excluido electronico
                </span>
            </a>
        </li>
        <li>
            <a href="comprobantecreditofiscal.html">
                <ion-icon name="bookmark-outline"></ion-icon>
                <span>Comprobante de credito fiscal</span>
            </a>
        </li>
        <li>
            <a href="facturanormal.html">
            <ion-icon name="albums-outline"></ion-icon>
                <span>factura normal</span>
            </a>
        </li>
    </ul>
    <div class="linea"></div>
    <ul>
    <li>
            <a href="#">
            <ion-icon name="book-outline"></ion-icon>
                <span>Factura emitidas</span>
            </a>
        </li>
        <li>
            <a href="clientes.html">
            <ion-icon name="person-outline"></ion-icon>
                <span>Clientes</span>
            </a>
        </li>
        <li>
            <a href="empleados.html">
            <ion-icon name="person-add-outline"></ion-icon>
                <span>Empleados</span>
            </a>
        </li>
    </ul>
</nav>

<div>
    <div class="linea"></div>

    <div class="modo-oscuro">
        <div class="info">
            <ion-icon name="moon-outline"></ion-icon>
            <span>Dark Mode</span>
        </div>
        <div class="switch">
            <div class="base">
                <div class="circulo">
                    
                </div>
            </div>
        </div>
    </div>

    <div class="usuario">
        <img src="../../resources/img/usuario.png" alt="">
        <div class="info-usuario">
            <div class="nombre-email">
                <span class="nombre">Admin</span>
                <span class="email">admin@gmail.com</span>
            </div>
            <ion-icon name="ellipsis-vertical-outline"></ion-icon>
        </div>
    </div>
</div>

</div>
 `;

// Inserción del navbar
ASIDE.insertAdjacentHTML('beforebegin', NAVBAR);

// Selección de elementos relevantes
const body = document.body;
const menu = document.querySelector('.menu');
const barraLateral = document.querySelector('.barra-lateral');
const main = document.querySelector('main');
const spans = document.querySelectorAll('span');
const palanca = document.querySelector('.switch');
const circulo = document.querySelector('.circulo');
const cloud = document.getElementById("cloud");

// Evento click para abrir/cerrar el menú
menu.addEventListener("click", () => {
    // Código para abrir/cerrar el menú...
});

// Evento click para activar/desactivar el modo oscuro
palanca.addEventListener("click", () => {
    // Cambiar de inmediato el modo oscuro y guardar el estado en el almacenamiento local
    body.classList.toggle("dark-mode");
    circulo.classList.toggle("prendido");
    const modoOscuro = body.classList.contains("dark-mode");
    localStorage.setItem("modoOscuro", modoOscuro);
    // Aplicar los estilos correspondientes
    if (modoOscuro) {
        aplicarModoOscuro();
    } else {
        aplicarModoClaro();
    }
});

// Evento click en el icono de nube para alternar entre la barra lateral grande y pequeña
cloud.addEventListener("click", () => {
    // Código para alternar entre la barra lateral grande y pequeña...
});

// Función para aplicar los estilos oscuros
function aplicarModoOscuro() {
    body.style.backgroundColor = "#121212"; // Fondo oscuro
    body.style.color = "#ffffff"; // Texto blanco
    // Aquí puedes agregar más estilos oscuros según tus necesidades
}

// Función para aplicar los estilos claros
function aplicarModoClaro() {
    body.style.backgroundColor = ""; // Restaurar al valor original del CSS
    body.style.color = ""; // Restaurar al valor original del CSS
    // Aquí puedes restaurar más estilos originales según tus necesidades
}

// Aplicar el modo oscuro si está activado en el almacenamiento local al cargar la página
window.addEventListener("load", () => {
    const modoOscuro = localStorage.getItem("modoOscuro") === "true";
    if (modoOscuro) {
        body.classList.add("dark-mode");
        aplicarModoOscuro();
    }
});

