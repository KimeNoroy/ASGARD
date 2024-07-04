// Función para validar y formatear el NIT
function validarNIT(input) {
    var nit = input.replace(/\D/g, ''); // Eliminar todos los caracteres no numéricos
    var formattedNIT = '';

    // Agregar guiones según el formato 0000-000000-000-0
    for (var i = 0; i < nit.length; i++) {
        if (i === 4 || i === 10 || i === 13) {
            formattedNIT += '-';
        }
        formattedNIT += nit[i];
    }

    // Asignar el valor formateado al input
    return formattedNIT.substring(0, 15); // Asegurar que el NIT completo no supere los 14 caracteres
}

// Ejemplo de uso
var nitInput = '00001234567890';
var nitFormateado = validarNIT(nitInput);
