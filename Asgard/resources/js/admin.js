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
        <span>ASGARD</span>
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
            <a href="#">
                <ion-icon name="document-text-outline"></ion-icon>
                <span>Sujeto excluido electronico
                </span>
            </a>
        </li>
        <li>
            <a href="#">
                <ion-icon name="bookmark-outline"></ion-icon>
                <span>Comprobante de credito fiscal</span>
            </a>
        </li>
        <li>
            <a href="#">
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
            <a href="#">
            <ion-icon name="person-outline"></ion-icon>
                <span>Clientes</span>
            </a>
        </li>
        <li>
            <a href="#">
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

//insercion del navbar
ASIDE.insertAdjacentHTML('beforebegin', NAVBAR);

const HEADER = document.querySelector('header');


const cloud = document.getElementById("cloud");
const barraLateral = document.querySelector(".barra-lateral");
const spans = document.querySelectorAll("span");
const palanca = document.querySelector(".switch");
const circulo = document.querySelector(".circulo");
const menu = document.querySelector(".menu");
const main = document.querySelector("main");

menu.addEventListener("click", () => {
    barraLateral.classList.toggle("max-barra-lateral");
    if (barraLateral.classList.contains("max-barra-lateral")) {
        menu.children[0].style.display = "none";
        menu.children[1].style.display = "block";
    }
    else {
        menu.children[0].style.display = "block";
        menu.children[1].style.display = "none";
    }
    if (window.innerWidth <= 320) {
        barraLateral.classList.add("mini-barra-lateral");
        main.classList.add("min-main");
        spans.forEach((span) => {
            span.classList.add("oculto");
        })
    }
});

palanca.addEventListener("click", () => {
    let body = document.body;
    body.classList.toggle("dark-mode");
    body.classList.toggle("");
    circulo.classList.toggle("prendido");
});

cloud.addEventListener("click", () => {
    barraLateral.classList.toggle("mini-barra-lateral");
    main.classList.toggle("min-main");
    spans.forEach((span) => {
        span.classList.toggle("oculto");
    });
});


