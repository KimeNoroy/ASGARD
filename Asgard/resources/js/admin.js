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
        <span>ASGARD</span>
    </div>


<nav class="navegacion">
    <ul>
        <li>
            <a id="inbox" href="#">
                <ion-icon name="mail-unread-outline"></ion-icon>
                <span>Usuarios para facturación</span>
            </a>
        </li>
        <li>
            <a href="usuariofacturacion.html">
                <ion-icon name="star-outline"></ion-icon>
                <span>Usuarios para crédito fiscal</span>
            </a>
        </li>
        <li>
            <a href="#">
                <ion-icon name="paper-plane-outline"></ion-icon>
                <span>Usuarios para consumidor final</span>
            </a>
        </li>
        <li>
            <a href="#">
                <ion-icon name="document-text-outline"></ion-icon>
                <span>Factura Sujeto Excluido Electrónico</span>
            </a>
        </li>
        <li>
            <a href="#">
                <ion-icon name="bookmark-outline"></ion-icon>
                <span>Comprobante de Crédito Fiscal</span>
            </a>
        </li>
        <li>
            <a href="#">
                <ion-icon name="alert-circle-outline"></ion-icon>
                <span>Usuarios para factura normal</span>
            </a>
        </li>
        <li>
            <a href="#">
                <ion-icon name="trash-outline"></ion-icon>
                <span>Trash</span>
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
        <img src="/Jhampier.jpg" alt="">
        <div class="info-usuario">
            <div class="nombre-email">
                <span class="nombre">Jhampier</span>
                <span class="email">jhampier@gmail.com</span>
            </div>
        </div>
    </div>
</div>
</div>


 `;

//insercion del navbar
ASIDE.insertAdjacentHTML('beforebegin',NAVBAR);

const HEADER = document.querySelector('header');


const cloud = document.getElementById("cloud");
const barraLateral = document.querySelector(".barra-lateral");
const spans = document.querySelectorAll("span");
const menu = document.querySelector(".menu");
const main = document.querySelector("main");

menu.addEventListener("click",()=>{
    barraLateral.classList.toggle("max-barra-lateral");
    if(barraLateral.classList.contains("max-barra-lateral")){
        menu.children[0].style.display = "none";
        menu.children[1].style.display = "block";
    }
    else{
        menu.children[0].style.display = "block";
        menu.children[1].style.display = "none";
    }
    if(window.innerWidth<=320){
        barraLateral.classList.add("mini-barra-lateral");
        main.classList.add("min-main");
        spans.forEach((span)=>{
            span.classList.add("oculto");
        })
    }
});



