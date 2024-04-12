const ASIDE = document.querySelector('aside');

//template del navbar
const NAVBAR =

  `    <aside id="sidebar">
<div class="sidebar-title">
  <div class="sidebar-brand">
    <span class="material-icons-outlined">inventory</span> ASGARD
  </div>
  <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
</div>

<ul class="sidebar-list">
  <li class="sidebar-list-item">
    <a href="#" target="_blank">
      <span class="material-icons-outlined"></span> Inicio
    </a>
  </li>
  <li class="sidebar-list-item">
  <a href="#" target="_blank">
    <span class="material-icons-outlined">dashboard</span> Usuarios para facturación
  </a>
</li>
  <li class="sidebar-list-item">
    <a href="#" target="_blank">
      <span class="material-icons-outlined">inventory_2</span> Usuarios para crédito fiscal
    </a>
  </li>
  <li class="sidebar-list-item">
    <a href="#" target="_blank">
      <span class="material-icons-outlined">fact_check</span> Usuarios para consumidor final
    </a>
  </li>
  <li class="sidebar-list-item">
    <a href="#" target="_blank">
      <span class="material-icons-outlined">add_shopping_cart</span> Factura Sujeto Excluido Electrónico
    </a>
  </li>
  <li class="sidebar-list-item">
    <a href="#" target="_blank">
      <span class="material-icons-outlined">shopping_cart</span> Usuarios para consumidor final
    </a>
  </li>
  <li class="sidebar-list-item">
    <a href="#" target="_blank">
      <span class="material-icons-outlined">poll</span> Factura Sujeto Excluido Electrónico
    </a>
  </li>
  <li class="sidebar-list-item">
    <a href="#" target="_blank">
      <span class="material-icons-outlined">settings</span> Usuarios para facturación
    </a>
  </li>
</ul>
</aside>
 `;

//insercion del navbar
ASIDE.insertAdjacentHTML('beforebegin', NAVBAR);

const HEADER = document.querySelector('header');

//template del navbar
const header = `    <header class="header">
<div class="menu-icon" onclick="openSidebar()">
  <span class="material-icons-outlined">menu</span>
</div>
<div class="header-left">
</div>
</header>
 `;

HEADER.insertAdjacentHTML('beforebegin', header);


//declarar una variable para poder abrir el navbar
let sidebarOpen = false;
const sidebar = document.getElementById('sidebar');


//Funcion para abrir el navbar cuando este en una pantalla pequeña
function openSidebar() {
  if (!sidebarOpen) {
    sidebar.classList.add('sidebar-responsive');
    sidebarOpen = true;
  }
}

//Funcion para cerrar el navbar cuando este en una pantalla pequeña
function closeSidebar() {
  if (sidebarOpen) {
    sidebar.classList.remove('sidebar-responsive');
    sidebarOpen = false;
  }
}