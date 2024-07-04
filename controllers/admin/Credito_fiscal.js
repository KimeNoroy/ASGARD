const COMPROBANTE_API = 'services/admin/comprobante_credito_fiscal.php';
const SEARCH_FORM = document.getElementById('searchForm');
const TABLE_BODY = document.getElementById('tableBody');
const ROWS_FOUND = document.getElementById('rowsFound');
const SAVE_MODAL = new bootstrap.Modal('#modalServicio');
const MODAL_TITLE = document.getElementById('modalTitle');
const SAVE_FORM = document.getElementById('saveForm');
const ID_COMPROBANTE = document.getElementById('idComprobante');
const NOMBRE = document.getElementById('nombre');
const NIT = document.getElementById('nit');
const GIRO = document.getElementById('giro');

// Evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    loadTemplate();
    fillTable();
});

// Evento para cuando se envía el formulario de buscar.
SEARCH_FORM.addEventListener('submit', (event) => {
    event.preventDefault();
    const FORM = new FormData(SEARCH_FORM);
    fillTable(FORM);
});

// Evento para cuando se envía el formulario de guardar.
SAVE_FORM.addEventListener('submit', async (event) => {
    event.preventDefault();
    const action = ID_COMPROBANTE.value ? 'updateRow' : 'createRow';
    const FORM = new FormData(SAVE_FORM);
    const DATA = await fetchData(COMPROBANTE_API, action, FORM);
    if (DATA.status) {
        SAVE_MODAL.hide();
        sweetAlert(1, DATA.message, true);
        fillTable();
    } else {
        sweetAlert(2, DATA.error, false);
    }
});

const fillTable = async (form = null) => {
    ROWS_FOUND.textContent = '';
    TABLE_BODY.innerHTML = '';
    const action = form ? 'searchRows' : 'readAll';
    const DATA = await fetchData(COMPROBANTE_API, action, form);
    if (DATA.status) {
        DATA.dataset.forEach(row => {
            TABLE_BODY.innerHTML += `
                <tr>
                    <td>${row.id_comprobante}</td>
                    <td>${row.nombre_credito_fiscal}</td>
                    <td>${row.nit_credito_fiscal}</td>
                    <td>${row.giro_credito_fiscal}</td>
                    <td>
                        <button type="button" class="btn btn-info" onclick="openUpdate(${row.id_comprobante})">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
                        <button type="button" class="btn btn-danger" onclick="openDelete(${row.id_comprobante})">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </td>
                </tr>
            `;
        });
        ROWS_FOUND.textContent = DATA.message;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

const openCreate = () => {
    SAVE_MODAL.show();
    MODAL_TITLE.textContent = 'Agregar Comprobante';
    SAVE_FORM.reset();
}

const openUpdate = async (id) => {
    const FORM = new FormData();
    FORM.append('idComprobante', id);
    const DATA = await fetchData(COMPROBANTE_API, 'readOne', FORM);
    if (DATA.status) {
        SAVE_MODAL.show();
        MODAL_TITLE.textContent = 'Actualizar Comprobante';
        SAVE_FORM.reset();
        const ROW = DATA.dataset;
        ID_COMPROBANTE.value = ROW.id_comprobante;
        NOMBRE.value = ROW.nombre_credito_fiscal;
        NIT.value = ROW.nit_credito_fiscal;
        GIRO.value = ROW.giro_credito_fiscal;
    } else {
        sweetAlert(2, DATA.error, false);
    }
}

const openDelete = async (id) => {
    const RESPONSE = await confirmAction('¿Desea eliminar este comprobante de forma permanente?');
    if (RESPONSE) {
        const FORM = new FormData();
        FORM.append('idComprobante', id);
        const DATA = await fetchData(COMPROBANTE_API, 'deleteRow', FORM);
        if (DATA.status) {
            await sweetAlert(1, DATA.message, true);
            fillTable();
        } else {
            sweetAlert(2, DATA.error, false);
        }
    }
}
