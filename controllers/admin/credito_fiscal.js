
// Constante para completar la ruta de la API.
const CREDITO_FISCAL_API = 'services/admin/comprobante_credito_fiscal.php';
const FACTURA_API = 'services/admin/factura_sujeto_excluido.php';


// Constante para almacenar el modal de editar.
const MODAL_CREDITO_FISCAL = new bootstrap.Modal('#modalServicio');



// Constante que almacena el form de búsqueda.
const FORM_BUSCAR = document.getElementById('formBuscar');


// Constante para almacenar el modal de eliminar.
const MODAL_ELIMINAR_CREDITO_FISCAL = new bootstrap.Modal('#borrarModalServicio');


// Constantes para cargar los elementos de la tabla.
const FILAS_ENCONTRADAS = document.getElementById('filasEncontradas');
const CUERPO_TABLA = document.getElementById('usuariosTableBody');


// Constante para definir el título del modal y botón.
const TITULO_MODAL = document.getElementById('modalServicioLabel');
const BOTON_ACCION = document.querySelector('#formServicio button[type="submit"]');

document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();

   
    
   
});


// Constantes para establecer los elementos del formulario.
const FORM_CREDITO_FISCAL = document.getElementById('formServicio');
const DESCRIPCION_SERVICIO = document.getElementById('descripcionServicio');
const MONTO_SERVICIO = document.getElementById('montoServicio');
const TIPO_SERVICIO = document.getElementById('tipoServicio');
const ID_COMPROBANTE = document.getElementById('id_comprobante');
const ID_CLIENTE = document.getElementById('id_cliente');
const NCR = document.getElementById('ncr');
const ACTIVIDAD_ECONOMICA = document.getElementById('actividadEconomica');
const FECHA_EMISION = document.getElementById('fechaEmision');


// Función para abrir el modal crear o editar.
const abrirModal = async (tituloModal, idComprobante) => {
    // Se configura el título del modal.
    TITULO_MODAL.textContent = tituloModal;

    if (idComprobante == null) {
        // Se remueve el antiguo color del botón.
        BOTON_ACCION.classList.remove('btn-success');
        // Se configura el nuevo color del botón.
        BOTON_ACCION.classList.add('btn-primary');
        // Se configura el título del botón.
        BOTON_ACCION.innerHTML = 'Agregar comprobante';
        // Se limpian los input para dejarlos vacíos.
        FORM_CREDITO_FISCAL.reset();
        // Limpiar el valor de ID_COMPROBANTE.
        ID_COMPROBANTE.value = '';

        await fillSelect(CREDITO_FISCAL_API, 'readAllclientes', 'id_cliente');
        await fillSelect(CREDITO_FISCAL_API, 'readAllservicio', 'id_servicio');
        // Se abre el modal agregar.
        MODAL_CREDITO_FISCAL.show();
    } else {
        // Se define una constante tipo objeto que almacenará el idComprobante
        const FORM = new FormData();
        // Se almacena el nombre del campo y el valor (idComprobante) en el formulario.
        FORM.append('id_comprobante', idComprobante);
        // Petición para obtener los datos del registro solicitado.
        const DATA = await fetchData(CREDITO_FISCAL_API, 'readOne', FORM);
        // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
        if (DATA.status) {
            // Se configura el título del modal.
            TITULO_MODAL.textContent = 'Actualizar comprobante';
            // Se remueve el antiguo color del botón.
            BOTON_ACCION.classList.remove('btn-primary');
            // Se configura el nuevo color del botón.
            BOTON_ACCION.classList.add('btn-success');
            // Se configura el título del botón.
            BOTON_ACCION.innerHTML = 'Editar comprobante';
            // Se prepara el formulario para cargar los input.
            FORM_CREDITO_FISCAL.reset();
            // Se cargan los campos de la base en una variable.
            const ROW = DATA.dataset;
            // Se carga el id del comprobante en el input idComprobante.
            ID_COMPROBANTE.value = ROW.id_comprobante;
            // Se carga el nombre del cliente en el input nombreSujeto.
            await fillSelect(CREDITO_FISCAL_API, 'readAllclientes', 'id_cliente', ROW.id_cliente);
            await fillSelect(CREDITO_FISCAL_API, 'readAllservicio', 'id_servicio', ROW.id_servicio);
            TIPO_SERVICIO.value = ROW.tipo_servicio;
            MONTO_SERVICIO.value = ROW.monto;
            FECHA_EMISION.value = ROW.fecha_emision;
            DESCRIPCION_SERVICIO.value = ROW.descripcion;
            NCR.value = ROW.ncr;
            ACTIVIDAD_ECONOMICA.value = ROW.actividad_economica;
            // Se abre el modal editar.
            MODAL_CREDITO_FISCAL.show();
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
const eliminarComprobante = async (idComprobante) => {
    // Se define una constante tipo objeto donde se almacenará el idComprobante.
    const FORM = new FormData();
    // Se almacena el nombre del campo y el valor (idComprobante).
    FORM.append('id_comprobante', idComprobante);
    // Petición para eliminar el registro seleccionado.
    const DATA = await fetchData(CREDITO_FISCAL_API, 'deleteRow', FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se muestra un mensaje de éxito.
        await sweetAlert(1, DATA.message, true);
        // Actualiza la tabla después de eliminar el comprobante
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

const openCreate = () => {
    // Se muestra la caja de diálogo con su título.
    MODAL_CREDITO_FISCAL.show();
    TITULO_MODAL.textContent = "Agregar Credito Fiscal";
    console.log('olaaa');
    // Se prepara el formulario.
    FORM_CREDITO_FISCAL.reset();
    fillSelect(FACTURA_API, "readAllclientes", "id_cliente");
    fillSelect(FACTURA_API, "readAllservicio", "id_servicio");
  };
  

// Método del evento para cuando se envía el formulario de guardar.
FORM_CREDITO_FISCAL.addEventListener('submit', async (event) => {
    // Se evita recargar la página web después de enviar el formulario.
    event.preventDefault();
    // Se verifica la acción a realizar.
    let action = ID_COMPROBANTE.value ? 'updateRow' : 'createRow';
    // Constante tipo objeto con los datos del formulario.
    const FORM = new FormData(FORM_CREDITO_FISCAL);
    // Petición para guardar los datos del formulario.
    const DATA = await fetchData(CREDITO_FISCAL_API, action, FORM);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        // Se cierra la caja de diálogo.
        MODAL_CREDITO_FISCAL.hide();
        // Se muestra un mensaje de éxito.
        await sweetAlert(1, DATA.message, true);
        // Se carga nuevamente la tabla para visualizar los cambios.
        fillTable();
        // Se resetea el formulario.
        FORM_CREDITO_FISCAL.reset();
        // Limpiar el valor de ID_COMPROBANTE.
        ID_COMPROBANTE.value = '';
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

// Función para llenar la tabla con los registros.
const fillTable = async (form = null) => {
    // Se verifica la acción a realizar.
    let action = form ? 'searchRows' : 'readAll';
    // Petición para obtener los registros.
    const DATA = await fetchData(CREDITO_FISCAL_API, action, form);
    // Se comprueba si la respuesta es satisfactoria.
    if (DATA.status) {
        // Se inicializa el contenido de la tabla.
        FILAS_ENCONTRADAS.textContent = '';
        CUERPO_TABLA.innerHTML = '';
        // Se recorren los registros y se añaden a la tabla.
        DATA.dataset.forEach(row => {
            CUERPO_TABLA.innerHTML += `
                <tr>
                    <td class="text-center">${row.nombre_cliente} ${row.apellido_cliente}</td>
                    <td class="text-center">${row.tipo_servicio}</td>
                    <td class="text-center">${row.monto}</td>
                    <td class="text-center">${row.fecha_emision}</td>
                    <td class="text-center">${row.descripcion}</td>
                    <td class="text-center">${row.ncr}</td>
                    <td class="text-center">${row.actividad_economica}</td>
                    <td class="text-center">
                        <button class="btn btn-warning btn-sm" onclick="abrirModal('Editar comprobante', ${row.id_comprobante})">Editar</button>
                        <button class="btn btn-danger btn-sm" onclick="eliminarComprobante(${row.id_comprobante})">Eliminar</button>
                    </td>
                </tr>
            `;
        });
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

// Función para mostrar alertas.
const sweetAlert2 = async (type, message, reload = false) => {
    // Se importa la función de SweetAlert de la librería.
    const Swal = await import('sweetalert2');
    // Se configura el tipo de alerta y el mensaje a mostrar.
    await Swal.default.fire({
        icon: type === 1 ? 'success' : 'error',
        title: type === 1 ? 'Éxito' : 'Error',
        text: message
    });
    // Si se especifica, se recarga la página web después de mostrar la alerta.
    if (reload) location.reload();
}

// Función para cargar datos en un select.
const fillSelects = async (api, action, idSelect, selectedId = null) => {
    // Petición para obtener los datos a llenar en el select.
    const DATA = await fetchData(api, action);
    // Se obtiene el select por su id.
    const SELECT = document.getElementById(idSelect);
    // Se limpian las opciones existentes en el select.
    SELECT.innerHTML = '';
    // Se comprueba si la respuesta es satisfactoria.
    if (DATA.status) {
        // Se recorren los datos obtenidos y se añaden como opciones al select.
        DATA.dataset.forEach(item => {
            const OPTION = document.createElement('option');
            OPTION.value = item[idSelect];
            OPTION.textContent = `${item.nombre_cliente} ${item.apellido_cliente}`;
            if (selectedId && item[idSelect] == selectedId) {
                OPTION.selected = true;
            }
            SELECT.appendChild(OPTION);
        });
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

// Función para realizar peticiones fetch a la API.
const fetchDatas = async (api, action, form = null) => {
    // Se define la URL completa con la ruta de la API y la acción a realizar.
    const URL = `${api}?action=${action}`;
    try {
        // Se ejecuta la petición fetch con la URL y el método POST.
        const RESPONSE = await fetch(URL, {
            method: 'POST',
            body: form
        });
        // Se obtiene la respuesta en formato JSON.
        const DATA = await RESPONSE.json();
        // Se retorna la respuesta obtenida.
        return DATA;
    } catch (error) {
        // Se muestra un mensaje de error en la consola en caso de excepción.
        console.error('Error:', error);
        // Se retorna un objeto con estado false y el mensaje de error.
        return { status: false, error: 'Ocurrió un error al procesar la solicitud.' };
    }
}
