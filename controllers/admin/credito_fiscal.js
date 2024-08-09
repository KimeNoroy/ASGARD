// Constante para completar la ruta de la API.
const FACTURA_API = 'services/admin/comprobante_credito_fiscal.php';
const CLIENTE_API = 'services/admin/clientes.php';

// Constante que almacena el form de búsqueda.
const SEARCH_FORM = document.getElementById('searchForm');
// Constantes para cargar los elementos de la tabla.
const TABLE_BODY = document.getElementById('tableBody'),
ROWS_FOUND = document.getElementById('rowsFound');

const SAVE_MODAL = new bootstrap.Modal('#saveModal'),
    MODAL_TITLE = document.getElementById('modalTitle');

const SAVE_FORM = document.getElementById('saveForm'),
    ID_FACTURA = document.getElementById('id_factura'),
    DESCRIPCION = document.getElementById('descripcionServicio'),
    ID_CLIENTE = document.getElementById('id_cliente'),
    TIPO_SERVICIO = document.getElementById('tipoServicio'),
    ID_SERVICIO = document.getElementById('id_servicio'),
    MONTO = document.getElementById('monto'),
    FECHA_EMISION = document.getElementById('fechaEmision');

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();

    // Se establece el título del contenido principal.
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
    (ID_PRODUCTO.value) ? action = 'updateRow' : action = 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(SAVE_FORM);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(FACTURA_API, action, FORM);
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


const fillTable = async (form = null) => {
     // Se inicializa el contenido de la tabla.
     ROWS_FOUND.textContent = '';
     TABLE_BODY.innerHTML = '';
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(FACTURA_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            TABLE_BODY.innerHTML += `
                <tr>
                    <td class="text-center">${row.nombre_cliente}</td>
                    <td class="text-center">${row.apellido_cliente}</td>
                    <td class="text-center">${row.nit_cliente}</td>
                    <td class="text-center">${row.direccion_cliente}</td>
                    <td class="text-center">${row.departamento_cliente}</td>
                    <td class="text-center">${row.municipio_cliente}</td>
                    <td class="text-center">${row.email_cliente}</td>
                    <td class="text-center">${row.telefono_cliente}</td>
                    <td class="text-center">${row.dui_cliente}</td>
                    <td class="text-center">${row.tipo_servicio}</td>
                    <td class="text-center">${row.monto}</td>
                    <td class="text-center">${row.fecha_emision}</td>
                    <td class="celda-agregar-eliminar text-right text-center">
                        <button type="button" class="btn btn-outline-primary" onclick="openUpdate(${row.id_factura})">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="openDelete(${row.id_factura})">
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

const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Crear factura';
    // Se prepara el formulario.
    SAVE_FORM.reset();
    fillSelect(CLIENTE_API, 'readAll', 'id_cliente');
    fillSelect(FACTURA_API, 'readAllservicio', 'id_servicio');
    fillSelect(FACTURA_API, 'readTipoServicio', 'tipoServicio');
   
}

const openUpdate = async (id) => {
    // Se define una constante tipo objeto con los datos del registro seleccionado.
    const FORM = new FormData();
    FORM.append('id_factura', id);
    // Petición para obtener los datos del registro solicitado.
    const DATA = await fetchData(FACTURA_API, 'readOne', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con el error.
    if (DATA.status) {
        // Se muestra la caja de diálogo con su título.
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar factura';
        // Se prepara el formulario.
        SAVE_FORM.reset();
        // Se inicializan los campos con los datos.
        const ROW = DATA.dataset;
        ID_FACTURA.value = ROW.id_factura;
        DESCRIPCION.value = ROW.descripcion;
        MONTO.value = ROW.monto;
        TIPO_SERVICIO.value = ROW.tipo_servicio;
        FECHA_EMISION.value = ROW.fecha_emision;
        DESCRIPCION.value = ROW.descripcion;
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

const openDelete = async (id) => {
    // Llamada a la función para mostrar un mensaje de confirmación, capturando la respuesta en una constante.
    const RESPONSE = await confirmAction('¿Desea eliminar la factura de forma permanente?');
    // Se verifica la respuesta del mensaje.
    if (RESPONSE) {
        // Se define una constante tipo objeto con los datos del registro seleccionado.
        const FORM = new FormData();
        FORM.append('id_factura', id);
        // Petición para eliminar el registro seleccionado.
        const DATA = await fetchData(FACTURA_API, 'deleteRow', FORM);
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