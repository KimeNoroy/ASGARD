// Constante para completar la ruta de la API.
const SERVICIOS_API = 'services/admin/servicios.php';

const DATA_EMPLEADOS = document.getElementById("cantidadEmpleados");
const DATA_CLIENTES = document.getElementById("cantidadClientes");
const DATA_FACTURAS = document.getElementById("cantidadFacturas");

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Llamada a la funciones que generan los gráficos en la página web.
  //  graficoBarrasServicios();
    graficoPastelServicios();
    readDashboardStats();
    graficoPrediccionClientes();
    graficoMontoTotalPorServicios();
});


const readDashboardStats = async (form = null) => {
    // Petición para obtener los registros disponibles.
    const DATA = await fetchData(USER_API, "readDashboardStats", form);
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se muestra un mensaje con la excepción.
    if (DATA.status) {
        const ROW = DATA.dataset;
        DATA_EMPLEADOS.textContent=ROW.total_empleados;
        DATA_FACTURAS.textContent=ROW.total_facturas;
        DATA_CLIENTES.textContent=ROW.total_clientes;
    } else {
        sweetAlert(4, DATA.error, true);
    }
}

/*
*   Función asíncrona para mostrar un gráfico de barras con la cantidad de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/

// const graficoBarrasServicios = async () => {
//     // Petición para obtener los datos del gráfico.
//     const DATA = await fetchData(CLIENTES_API, 'clienteDepartamentos');
//     // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
//     if (DATA.status) {
//         // Se declaran los arreglos para guardar los datos a graficar.
//         let factura1 = [];
//         let facutura2 = [];
//         // Se recorre el conjunto de registros fila por fila a través del objeto row.
//         DATA.dataset.forEach(row => {
//             // Se agregan los datos a los arreglos.
//             factura1.push(row.departamento_cliente);
//             facutura2.push(row.departamento_cliente);
//         });
//         // Llamada a la función para generar y mostrar un gráfico de barras. Se encuentra en el archivo components.js
//         barGraph('chart1', factura1, facutura2, 'Cantidad de productos', 'Cantidad de productos por categoría');
//     } else {
//         document.getElementById('chart1').remove();
//         console.log(DATA.error);
//     }
// }

/*
*   Función asíncrona para mostrar un gráfico de pastel con el porcentaje de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const graficoPastelServicios = async () => {
    try {
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(SERVICIOS_API, 'serviciosOfrecidos');

        // Se comprueba si la respuesta es satisfactoria.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let legends = [];
            let values = [];
            
            // Se recorre el conjunto de registros fila por fila.
            DATA.dataset.forEach(row => {
                legends.push(row.tipo_servicio);
                values.push(row.cantidad); // Asumiendo que 'cantidad' es el valor numérico para el gráfico
            });

            // Llamada a la función para generar y mostrar un gráfico de pastel.
            pieGraph('chart2', legends, values, 'Porcentaje de productos servicios');
        } else {
            // En caso de error, se remueve el canvas del gráfico.
            document.getElementById('chart2').remove();
            console.error(DATA.error);
        }
    } catch (error) {
        console.error('Error al obtener datos:', error);
    }
}

//Función para predecir los clientes del siguiente mes
const graficoPrediccionClientes = async () => {
    // Petición para obtener los datos históricos de clientes.
    const DATA = await fetchData(CLIENTE_API, 'historicoClientes');
    
    // Se comprueba si la respuesta es satisfactoria.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a graficar.
        let meses = [];
        let clientes = [];
        
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            meses.push(row.mes);
            clientes.push(row.cantidad);
        });

        // Lógica para realizar la predicción utilizando regresión lineal simple.
        const n = clientes.length;
        const sumX = meses.reduce((acc, val, i) => acc + (i + 1), 0); // Suma de índices de meses (1, 2, 3, ...)
        const sumY = clientes.reduce((acc, val) => acc + val, 0); // Suma de cantidades de clientes
        const sumXY = clientes.reduce((acc, val, i) => acc + (val * (i + 1)), 0); // Suma de productos de clientes y su índice
        const sumX2 = meses.reduce((acc, val, i) => acc + Math.pow(i + 1, 2), 0); // Suma de cuadrados de índices

        // Calcular la pendiente (m) y la intersección (b) de la recta.
        const m = (n * sumXY - sumX * sumY) / (n * sumX2 - Math.pow(sumX, 2));
        const b = (sumY - m * sumX) / n;

        // Predicción para el próximo mes
        const prediccionClientes = Math.round(m * (n + 1) + b);

        // Agregar el próximo mes y su predicción al gráfico
        meses.push('Próximo Mes');
        clientes.push(prediccionClientes);

        // Llamada a la función para generar y mostrar un gráfico de líneas.
        lineGraph('chart3', meses, clientes, 'Predicción de Clientes para el Próximo Mes');
    } else {
        document.getElementById('carouselChart3').remove();
        console.log(DATA.error);
    }
}

/*
*   Función asíncrona para mostrar un gráfico de pastel con el monto total por servicios.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
const graficoMontoTotalPorServicios = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(SERVICIOS_API, 'montoTotalPorServicios');
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a gráficar.
        let montoTotal = [];
        let servicios = [];
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            montoTotal.push(row.MontoTotal);
            servicios.push(row.nombre_servicio);
        });
        // Llamada a la función para generar y mostrar un gráfico de barra. Se encuentra en el archivo components.js.
        barGraph('chartMontoSrv', servicios, montoTotal, 'Monto total por Servicios', 'Monto total por Servicios');
    } else {
        console.log(DATA.error);
    }
}