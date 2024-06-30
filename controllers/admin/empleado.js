// Crear los elementos HTML con JavaScript
const app = document.getElementById('app');

const container = document.createElement('div');
container.className = 'container';

const title = document.createElement('h2');
title.textContent = 'Agregar empleado';
container.appendChild(title);

const formFields = [
    { label: 'Nombre', id: 'nombre', placeholder: 'Escriba su nombre' },
    { label: 'Apellido', id: 'apellido', placeholder: 'Escriba su apellido' },
    { label: 'Dui', id: 'dui', placeholder: '12345678-9' },
    { label: 'Telefono', id: 'telefono', placeholder: 'Escriba su numero de telefono' },
    { label: 'Correo', id: 'correo', placeholder: 'ejemplo@gmail.com' },
    { label: 'NIT', id: 'nit', placeholder: '1234-567890-111-1' }
];

formFields.forEach(field => {
    const formGroup = document.createElement('div');
    formGroup.className = 'form-group';

    const label = document.createElement('label');
    label.setAttribute('for', field.id);
    label.textContent = field.label + ':';

    const input = document.createElement('input');
    input.id = field.id;
    input.placeholder = field.placeholder;

    formGroup.appendChild(label);
    formGroup.appendChild(input);
    container.appendChild(formGroup);
});

const buttonsDiv = document.createElement('div');
buttonsDiv.className = 'buttons';

const cancelarButton = document.createElement('button');
cancelarButton.className = 'cancelar';
cancelarButton.textContent = 'Cancelar';
cancelarButton.onclick = cancelar;

const guardarButton = document.createElement('button');
guardarButton.className = 'guardar';
guardarButton.textContent = 'Guardar';
guardarButton.onclick = guardar;

buttonsDiv.appendChild(cancelarButton);
buttonsDiv.appendChild(guardarButton);
container.appendChild(buttonsDiv);

const alertDiv = document.createElement('div');
alertDiv.className = 'alert';
alertDiv.style.display = 'none';
container.appendChild(alertDiv);

app.appendChild(container);

// Función para limpiar los campos del formulario
function cancelar() {
    formFields.forEach(field => {
        document.getElementById(field.id).value = '';
    });
}

// Función para guardar los datos del formulario
function guardar() {
    const empleado = {};
    let valid = true;

    formFields.forEach(field => {
        const value = document.getElementById(field.id).value;
        if (!value) {
            valid = false;
            alert('Por favor, complete todos los campos.');
            return;
        }
        empleado[field.id] = value;
    });

    if (valid) {
        console.log('Datos del formulario:', empleado);
        cancelar();
        mostrarAlerta('Datos guardados correctamente.');
    }
}

// Función para mostrar una alerta
function mostrarAlerta(mensaje) {
    alertDiv.textContent = mensaje;
    alertDiv.style.display = 'block';
    setTimeout(() => {
        alertDiv.style.display = 'none';
    }, 3000);
}