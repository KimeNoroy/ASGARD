// Constantes para completar las rutas de la API.
const CLIENTE_API = 'services/admin/clientes.php';

// Constante para establecer el formulario de buscar.
const SEARCH_FORM = document.getElementById('searchForm');

// Constantes para establecer el contenido de la tabla.
const TABLE_BODY = document.getElementById('tableBodyClientes'),
    ROWS_FOUND = document.getElementById('rowsFound');
// Constantes para establecer los elementos del componente Modal.
const SAVE_MODAL = new bootstrap.Modal('#crearModal');
// MODAL_TITLE = document.getElementById('modalTitle');
// Constantes para establecer los elementos del formulario de guardar.
const SAVE_FORM = document.getElementById('saveForm'),
    ID_CLIENTE = document.getElementById('idCliente'),
    NOMBRE_CLIENTE = document.getElementById('nombre_cliente'),
    APELLIDO_CLIENTE = document.getElementById('apellido_cliente'),
    DUI_CLIENTE = document.getElementById('dui_cliente_crear'),
    DIRECCION_CLIENTE = document.getElementById('direccion_cliente')
    DEPARTAMENTO_CLIENTE = document.getElementById('departamento_cliente')
    MUNICIPIO_CLIENTE = document.getElementById('municipio_cliente')
    EMAIL_CLIENTE = document.getElementById('email_cliente');
    TELEFONO_CLIENTE = document.getElementById('telefono');

// Llamada a la función para establecer la mascara del campo teléfono.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('telefono'),
    mask: [/\d/, /\d/, /\d/, /\d/, '-', /\d/, /\d/, /\d/, /\d/]
});

// Llamada a la función para establecer la mascara del campo DUI.
vanillaTextMask.maskInput({
    inputElement: document.getElementById('dui_cliente_crear'),
    mask: [/\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, /\d/, '-', /\d/]
});



// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Se establece el título del contenido principal.
    //MAIN_TITLE.textContent = 'Gestionar clientes';
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

// Método del evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SEARCH_FORM);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    fillTable(FORM);
});

// Método del evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_CLIENTE.value) ? action = 'updateRow' : action = 'createRow';

    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(CLIENTE_API, action, FORM);
    console.log(DATA);
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
*   Parámetros: form (objeto opci   onal con los datos de búsqueda).
*   Retorno: ninguno.
*/
const fillTable = async (form = null) => {
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(CLIENTE_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        ROWS_FOUND.textContent = '';
        TABLE_BODY.innerHTML = '';
        // Se recorre el conjunto de registros (dataset) fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            TABLE_BODY.innerHTML += `
                <tr>
                    <td><img src="${SERVER_URL}image/clientes/${row.imagen_cliente}" height="50"></td>
                    <td>${row.nombre_cliente}</td>
                    <td>${row.apellido_cliente}</td>
                    <td>${row.dui_cliente}</td>
                    <td>${row.email_cliente}</td>
                    <td>${row.telefono_cliente}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_cliente})">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_cliente})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        ROWS_FOUND.textContent = DATA.message;
    } else {
        // En caso de que no existan usuarios registrados o no se encuentren coincidencias de búsqeuda. 
        if (DATA.error == 'No existen usuarios registrados' || DATA.error == 'No hay coincidencias') {
            // Se muestra el mensaje de la API.
            sweetAlert(4, DATA.error, true);
            // Se restablece el contenido de la tabla.
            ROWS_FOUND.textContent = '';
            TABLE_BODY.innerHTML = '';
        } else if (DATA.error == 'Ingrese un valor para buscar') {
            // Se muestra el mensaje de la API.
            sweetAlert(4, DATA.error, true);
        } else {
            // Se muestra el error de la API.
            sweetAlert(2, DATA.error, true);
        }
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
    //MODAL_TITLE.textContent = 'Crear cliente';
    // Se prepara el formulario.
    SAVE_FORM.reset();
}

/*
*   Función asíncrona para preparar el formulario al momento de actualizar un registro.
*   Parámetros: id (identificador del registro seleccionado).
*   Retorno: ninguno.
*/
const openUpdate = async (id) => {
    // Se define un objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('idCliente', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(CLIENTE_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_CLIENTE.value = ROW.id_cliente;
        NOMBRE_CLIENTE.value = ROW.nombre_cliente;
        APELLIDO_CLIENTE.value = ROW.apellido_cliente;
        DUI_CLIENTE.value = ROW.dui_cliente;
        DIRECCION_CLIENTE.value = ROW.direccion_cliente;
        DEPARTAMENTO_CLIENTE.value = ROW.departamento_cliente;
        MUNICIPIO_CLIENTE.value = ROW.municipio_cliente;
        EMAIL_CLIENTE.value = ROW.email_cliente;
        TELEFONO_CLIENTE.value = ROW.telefono_cliente;
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
    const RESPONSE = await confirmAction('¿Desea eliminar el cliente de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('idCliente', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(CLIENTE_API, 'deleteRow', FORM);
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
