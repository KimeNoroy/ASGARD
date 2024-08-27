// Constantes para completar las rutas de la API.
<<<<<<< HEAD
const EMPLEADO_API = 'services/admin/empleados.php';
//const CATEGORIA_API = 'services/admin/categoria.php';
// Constante para establecer el formulario de buscar.
//const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer el contenido de la tabla.
const TABLE_BODY = document.getElementById('tableBodyEmpleados'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#crearModal');
   // MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_EMPLEADO = document.getElementById('id_empleado'),
    NOMBRE_EMPLEADO = document.getElementById('nombres_empleado'),
    APELLIDO_EMPLEADO = document.getElementById('apellidos_empleado'),
    DUI_EMPLEADO = document.getElementById('dui_empleado'),
    EMAIL_EMPLEADO = document.getElementById('email_empleado'),
    PASSWORD_EMPLEADO = document.getElementById('contrasena');
=======
const ADMINISTADOR_API = 'services/admin/administrador.php';
// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para establecer el contenido de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_ADMINISTRADOR = document.getElementById('idAdmin'),
    NOMBRE_ADMINISTRADOR = document.getElementById('NAdmin'),
    APELLIDO_ADMINISTRADOR = document.getElementById('ApAdmin'),
    CORREO_ADMINISTRADOR = document.getElementById('CorreoAd'),
    CONTRASEÑA_ADMINISTRADOR = document.getElementById('ContraAd'),
    CONTRASEÑA_CONFIRMAR_ADMINISTRADOR = document.getElementById('confirmarClaveA'),
    BOTON_ACTUALIZAR = document.getElementById('btnAgregar'),
    BOTON_AGREGAR = document.getElementById('btnActualizar');
document.querySelector('title').textContent = 'Empleados';
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
<<<<<<< HEAD
    //loadTemplate();
    // Se establece el título del contenido principal.
    //MAIN_TITLE.textContent = 'Gestionar clientes';
=======
    loadTemplate();
    // Se establece el título del contenido principal.
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

<<<<<<< HEAD
=======
// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
<<<<<<< HEAD
    (ID_EMPLEADO.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(EMPLEADO_API, action, FORM);
=======
    (ID_ADMINISTRADOR.value) ? action = 'updateRow' : action = 'createTrabajadores';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(ADMINISTADOR_API, action, FORM);
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        SAVE_MODAL.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

/*
*   Función asíncrona para llenar la tabla con los registros disponibles.
*   Parámetros: form (objeto opcional con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    // Se inicializa el contenido de la tabla.
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
<<<<<<< HEAD
    const DATA = await fetchData(CLIENTE_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se establece un icono para el estado del producto.
            //(row.estado_producto) ? icon = 'bi bi-eye-fill' : icon = 'bi bi-eye-slash-fill';
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombres_empleado}</td>
                    <td>${row.apellidos_empleado}</td>
                    <td>${row.dui_empleado}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_empleado})">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_empleado})">
=======
    const DATA = await fetchData(ADMINISTADOR_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.nombre_administrador}</td>
                    <td>${row.apellido_administrador}</td>
                    <td>${row.email_administrador}</td>
                    <td>
                        <button type="button" class="btn btn-outline-primary" onclick="openUpdate(${row.id_administrador})">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="openDelete(${row.id_administrador})">
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función para preparar el formulario al momento de insertar un registro.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
<<<<<<< HEAD
    //MODAL_TITLE.textContent = 'Crear empleado';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    //EXISTENCIAS_PRODUCTO.disabled = false;
    //fillSelect(CATEGORIA_API, 'readAll', 'categoriaProducto');
=======
    MODAL_TITLE.textContent = 'Crear empleado';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    ALIAS_ADMINISTRADOR.disabled = false;
    CLAVE_ADMINISTRADOR.disabled = false;
    CONFIRMAR_CLAVE.disabled = false;

    BOTON_ACTUALIZAR.classList.remove('d-none');
    BOTON_AGREGAR.classList.add('d-none');
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
<<<<<<< HEAD
    // Se define un objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_empleado', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(EMPLEADO_API, 'readOne', FORM);
=======
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idAdmin', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(ADMINISTADOR_API, 'readOne', FORM);
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
<<<<<<< HEAD
        //MODAL_TITLE.textContent = 'Actualizar cliente';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        //EXISTENCIAS_PRODUCTO.disabled = true;
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_EMPLEADO.value = ROW.id_empleado;
        NOMBRE_EMPLEADO.value = ROW.nombres_empleado;
        APELLIDO_EMPLEADO.value = ROW.apellidos_empleado;
        DUI_EMPLEADO.value = ROW.dui_empleado;
        EMAIL_EMPLEADO.value = ROW.email_empleado;
        PASSWORD_EMPLEADO.value = ROW.contrasena;
        
        //fillSelect(CATEGORIA_API, 'readAll', 'categoriaProducto', ROW.id_categoria);
=======
        MODAL_TITLE.textContent = 'Actualizar empleado';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        CONTRASEÑA_ADMINISTRADOR.disabled = true;
        CONTRASEÑA_CONFIRMAR_ADMINISTRADOR.disabled = true;
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_ADMINISTRADOR.value = ROW.id_administrador;
        NOMBRE_ADMINISTRADOR.value = ROW.nombre_administrador;
        APELLIDO_ADMINISTRADOR.value = ROW.apellido_administrador;
        CORREO_ADMINISTRADOR.value = ROW.email_administrador;

        BOTON_AGREGAR.classList.remove('d-none');
        BOTON_ACTUALIZAR.classList.add('d-none');
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

/*
*   Función asíncrona para eliminar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar el empleado de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
<<<<<<< HEAD
        FORM.append('id_empleado', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(EMPLEADO_API, 'deleteRow', FORM);
=======
        FORM.append('idAdmin', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(ADMINISTADOR_API, 'deleteRow', FORM);
>>>>>>> 0a0e6f8a12de6d32de4b089eb059952920947d62
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se muestra un mensaje de éxito.
            await sweetAlert(1, DATA.message, true);
            // Se carga nuevamente la tabla para visualizar los cambios.
            fillTable();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}
