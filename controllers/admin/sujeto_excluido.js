// Constante para completar la ruta de la API.
const FACTURA_API = 'services/admin/factura_sujeto_excluido.php';
// Constante para almacenar el modal de editar.
const MODALSUJETO = new bootstrap.Modal('#modalSujeto');
// Constante que almacena el form de búsqueda.
const FORM_BUSCAR = document.getElementById('formBuscar');
// Constante para almacenar el modal de eliminar.
const MODALBSUJETO = new bootstrap.Modal('#borrarModalSujeto');
// Constantes para cargar los elementos de la tabla.
const FILAS_ENCONTRADAS = document.getElementById('filasEncontradas'),
    CUERPO_TABLA = document.getElementById('cuerpoTabla');
// Constante para definir el título del modal y botón.
const TITULO_MODAL = document.getElementById('tituloModal'),
    BOTON_ACCION = document.getElementById('btnAccion');
// Constantes para establecer los elementos del formulario.
const FORM_SUJETO = document.getElementById('formSujeto'),
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
    MAIN_TITLE.textContent = 'Gestionar usuarios';
    // Llamada a la función para llenar la tabla con los registros existentes.
    fillTable();
});

// Función para abrir el modal crear o editar.
const abrirModal = async (tituloModal, idFactura) => {
    // Se configura el título del modal.
    TITULO_MODAL.textContent = tituloModal;

    if (idFactura == null) {
        // Se remueve el antiguo color del botón.
        BOTON_ACCION.classList.remove('btn-success');
        // Se configura el nuevo color del botón.
        BOTON_ACCION.classList.add('btn-primary');
        // Se configura el título del botón.
        BOTON_ACCION.innerHTML = 'Agregar usuario';
        // Se limpian los input para dejarlos vacíos.
        FORM_SUJETO.reset();

        await fillSelect(FACTURA_API, 'readAllclientes', 'id_cliente');
        await fillSelect(FACTURA_API, 'readAllservicio', 'id_servicio');
        // Se abre el modal agregar.
        MODALSUJETO.show();
    }
    else {
        // Se define una constante tipo objeto que almacenará el idFactura
        const FORM = new FormData();
        // Se almacena el nombre del campo y el valor (idFactura) en el formulario.
        FORM.append('id_factura', idFactura);
        // Petición para obtener los datos del registro solicitado.
        const DATA = await fetchData(FACTURA_API, 'readOne', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se configura el título del modal.
            TITULO_MODAL.textContent = 'Actualizar servicio';
            // Se remueve el antiguo color del botón.
            BOTON_ACCION.classList.remove('btn-primary');
            // Se configura el nuevo color del botón.
            BOTON_ACCION.classList.add('btn-success');
            // Se configura el título del botón.
            BOTON_ACCION.innerHTML = 'Editar servicio';
            // Se prepara el formulario para cargar los input.
            FORM_SUJETO.reset();
            // Se cargan los campos de la base en una variable.
            const ROW = DATA.dataset;
            // Se carga el id del usuario en el input idUsuario.
            ID_FACTURA.value = ROW.id_factura;
            // Se carga el nombre del cliente en el input nombreSujeto.
            await fillSelect(FACTURA_API, 'readAllclientes', 'id_cliente', ROW.id_cliente);
            await fillSelect(FACTURA_API, 'readAllservicio', 'id_servicio', ROW.id_servicio);
            TIPO_SERVICIO.value = ROW.tipo_servicio;
            MONTO.value = ROW.monto;
            FECHA_EMISION.value = ROW.fecha_emision;
            DESCRIPCION.value = ROW.descripcion;
            // Se abre el modal editar.
            MODALSUJETO.show();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}

// Método del evento para cuando se envía el formulario de buscar.
FORM_BUSCAR.addEventListener('submit', (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(FORM_BUSCAR);
    // Llamada a la función para llenar la tabla con los resultados de la búsqueda.
    loadTemplate(FORM);
});

// Función para abrir el modal de eliminar.
const eliminarServicio = async (id_factura) => {

    // Se define una constante tipo objeto donde se almacenará el idFactura.
    const FORM = new FormData();
    // Se almacena el nombre del campo y el valor (idFactura).
    FORM.append('id_factura', id_factura);
    // Petición para eliminar el registro seleccionado.
    const DATA = await fetchData(FACTURA_API, 'deleteRow', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        //Se oculta el modal
        MODALBSUJETO.hide();
        // Se muestra un mensaje de éxito.
        await sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        loadTemplate();
    } else {
        sweetAlert(2, DATA.error, false);
    }
}


// Evento que carga los recursos de barra de navegación y función de rellenar tabla.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    //Llamar la función para cargar los datos de la tabla.
    cargarTabla();
});

// Método del evento para cuando se envía el formulario de guardar.
FORM_SUJETO.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    (ID_FACTURA.value) ? action = 'updateRow' : action = 'createRow';
    console.log(ID_FACTURA.value);
    console.log(action);
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(FORM_SUJETO);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(FACTURA_API, action, FORM);
    console.log(DATA);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        MODALSUJETO.hide();
        // Se muestra un mensaje de éxito.
        sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        loadTemplate();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

const loadTemplate = async (form = null) => {
    // Se verifica la acción a realizar.
    (form) ? action = 'searchRows' : action = 'readAll';
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(FACTURA_API, action, form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se inicializa el contenido de la tabla.
        FILAS_ENCONTRADAS.textContent = '';
        CUERPO_TABLA.innerHTML = '';
        // Se recorre el conjunto de registros fila por fila.
        DATA.dataset.forEach(row => {
            // Se crean y concatenan las filas de la tabla con los datos de cada registro.
            CUERPO_TABLA.innerHTML += `
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
                    <td class="text-center">${row.descripcion}</td>
                    <td class="celda-agregar-eliminar text-right text-center">
                        <button type="button" class="btn btn-success text-center" onclick="abrirModal('Editar factura',${row.id_factura})">
                            <img src="../../resources/img/lapiz.png" alt="lapizEditar" width="30px">
                        </button>
                        <button type="button" class="btn btn-danger text-center" onclick="eliminarServicio(${row.id_factura})">
                            <img src="../../resources/img/eliminar.png" alt="lapizEditar" width="30px">
                        </button>
                    </td>
                </tr>
            `;
        });
        // Se muestra un mensaje de acuerdo con el resultado.
        FILAS_ENCONTRADAS.textContent = DATA.message;
    } else {
        // En caso de que no existan usuarios registrados o no se encuentren coincidencias de búsqeuda. 
        if (DATA.error == 'No existen usuarios registrados' || DATA.error == 'No hay coincidencias') {
            // Se muestra el mensaje de la API.
            sweetAlert(4, DATA.error, true);
            // Se restablece el contenido de la tabla.
            FILAS_ENCONTRADAS.textContent = '';
            CUERPO_TABLA.innerHTML = '';
        } else if (DATA.error == 'Ingrese un valor para buscar') {
            // Se muestra el mensaje de la API.
            sweetAlert(4, DATA.error, true);
        } else {
            // Se muestra el error de la API.
            sweetAlert(2, DATA.error, true);
        }
    }
}