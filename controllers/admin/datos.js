// Constante para completar la ruta de la API.
const SERVICIOS_API = 'services/admin/servicios.php';
const CLIENTES_API = 'services/admin/clientes.php';
const FACTURAS_API = 'services/admin/factura_sujeto_excluido.php';
const COMPROBANTES_API = 'services/admin/comprobante_credito_fiscal.php';
const DATA_EMPLEADOS = document.getElementById("cantidadEmpleados");
const DATA_CLIENTES = document.getElementById("cantidadClientes");
const DATA_FACTURAS = document.getElementById("cantidadFacturas");
const DEPARTAMENTOS_API =document.getElementById("clientesPorDepartamento");

// Método del evento para cuando el documento ha cargado.
document.addEventListener('DOMContentLoaded', () => {
    // Llamada a la función para mostrar el encabezado y pie del documento.
    loadTemplate();
    // Llamada a las funciones que generan los gráficos en la página web.
    graficoPastelServicios();
    graficoLineasFacturasPorMes(); // Asegúrate de que esta función esté llamada
    readDashboardStats();
    //graficoPrediccionClientes();
    graficoMontoTotalPorServicios();
    predictNextMonthRecords();
    predictNextMonthRecords1();
    graficoClientesPorDepartamento();
});

// Función para obtener los datos de facturas por mes
const fetchFacturasPorMes = async () => {
    try {
        // Llama a la función fetchData con la URL y la acción correcta
        const DATA = await fetchData(SERVICIOS_API, 'facturasPorMes');
        
        if (DATA.status === 1) {
            return DATA.dataset;
        } else {
            console.error(DATA.error);
            return [];
        }
    } catch (error) {
        console.error('Error fetching data:', error);
        return [];
    }
};




// Función para renderizar el gráfico de líneas
const graficoLineasFacturasPorMes = async () => {
    const data = await fetchFacturasPorMes();

    if (data && Array.isArray(data)) {
        const meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        const dataValues = new Array(12).fill(0);

        data.forEach(item => {
            const monthIndex = parseInt(item.mes) - 1;
            if (monthIndex >= 0 && monthIndex < 12) {
                dataValues[monthIndex] = item.total_facturas;
            }
        });

        lineGraph('facturasPorMes', meses, dataValues, 'Facturas Emitidas', 'Facturas Emitidas por Mes');
    } else {
        console.error('Datos no válidos recibidos:', data);
    }
};

// Función para leer las estadísticas del dashboard
const readDashboardStats = async (form = null) => {
    const DATA = await fetchData(USER_API, "readDashboardStats", form);
    if (DATA.status) {
        const ROW = DATA.dataset;
        DATA_EMPLEADOS.textContent = ROW.total_empleados;
        DATA_FACTURAS.textContent = ROW.total_facturas;
        DATA_CLIENTES.textContent = ROW.total_clientes;
    } else {
        sweetAlert(4, DATA.error, true);
    }
};

/*
*   Función asíncrona para mostrar un gráfico de pastel con el porcentaje de productos por categoría.
*   Parámetros: ninguno.
*   Retorno: ninguno.
*/
// Función para mostrar un gráfico de pastel con el porcentaje de productos por categoría
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
                legends.push(row.nombre_servicio);
                values.push(row.total_usos);
            });
            pieGraph('chart8', legends, values, 'Porcentaje de productos servicios');
        } else {
            document.getElementById('chart8').remove();
            console.error(DATA.error);
        }
    } catch (error) {
        console.error('Error al obtener datos:', error);
    }
};

// Función para mostrar un gráfico de barras del monto total por servicios
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
};

// Función para predecir los clientes del siguiente mes
/*
const graficoPrediccionClientes = async () => {
    const DATA = await fetchData(CLIENTE_API, 'historicoClientes');
    if (DATA.status) {
        let meses = [];
        let clientes = [];
        DATA.dataset.forEach(row => {
            meses.push(row.mes);
            clientes.push(row.cantidad);
        });

        const n = clientes.length;
        const sumX = meses.reduce((acc, val, i) => acc + (i + 1), 0);
        const sumY = clientes.reduce((acc, val) => acc + val, 0);
        const sumXY = clientes.reduce((acc, val, i) => acc + (val * (i + 1)), 0);
        const sumX2 = meses.reduce((acc, val, i) => acc + Math.pow(i + 1, 2), 0);

        const m = (n * sumXY - sumX * sumY) / (n * sumX2 - Math.pow(sumX, 2));
        const b = (sumY - m * sumX) / n;

        const prediccionClientes = Math.round(m * (n + 1) + b);

        meses.push('Próximo Mes');
        clientes.push(prediccionClientes);

        lineGraph('chart', meses, clientes, 'Predicción de Clientes para el Próximo Mes');
    } else {
        document.getElementById('chart3').remove();
        console.log(DATA.error);
    }
};*/

// Función asíncrona para mostrar un gráfico lineal predictivo de registros de sujetos excluidos para el siguiente mes.
const predictNextMonthRecords = async () => {
    try {
        console.log('Entro a la función');
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(FACTURAS_API, 'predictNextMonthRecords');
        console.log('No entro aqui bro');
        console.log(DATA.dataset2);
        // Se comprueba si la respuesta es satisfactoria.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let meses = [];
            let registros = [];

            // Se recorre el conjunto de registros fila por fila.
            DATA.dataset2.forEach(row => {
                meses.push(row.mes);
                registros.push(row.total);
            });
            meses.push('Próximo Mes');
            registros.push(DATA.dataset);
            console.log(meses);
            console.log(registros);
            // Llamada a la función generalizada para generar y mostrar un gráfico lineal.
            lineGraph4('chart4', meses, registros, 'Registros de Sujetos Excluidos', 'Predicción de Registros para el Siguiente Mes');
        } else {
            // En caso de error, se remueve el canvas del gráfico.
            document.getElementById('chart4').remove(); // Remover el gráfico si no hay datos
            console.error(DATA.error);
        }
    } catch (error) {
        console.error('Error al obtener datos:', error);
    }
}

// Función asíncrona para mostrar un gráfico lineal predictivo de registros de sujetos excluidos para el siguiente mes.
const predictNextMonthRecords1 = async () => {
    try {
        // Petición para obtener los datos del gráfico.
        const DATA = await fetchData(COMPROBANTES_API, 'predictNextMonthRecords1');

        // Se comprueba si la respuesta es satisfactoria.
        if (DATA.status) {
            // Se declaran los arreglos para guardar los datos a graficar.
            let meses = [];
            let registros = [];

            // Se recorre el conjunto de registros fila por fila.
            DATA.dataset2.forEach(row => {
                meses.push(row.mes);
                registros.push(row.total);
            });
            meses.push('Próximo Mes');
            registros.push(DATA.dataset);
            console.log(meses);
            console.log(registros);
            // Llamada a la función generalizada para generar y mostrar un gráfico lineal.
            lineGraph2('chart5', meses, registros, 'Registros de comprobante de credito fiscal', 'Predicción de Registros para el Siguiente Mes');
        } else {
            // En caso de error, se remueve el canvas del gráfico.
            document.getElementById('chart5').remove(); // Remover el gráfico si no hay datos
            console.error(DATA.error);
        }
    } catch (error) {
        console.error('Error al obtener datos:', error);
    }
}
// Función para mostrar un gráfico de barras del número de clientes por departamento
const graficoClientesPorDepartamento = async () => {
    // Petición para obtener los datos del gráfico.
    const DATA = await fetchData(DEPARTAMENTOS_API, 'clientesPorDepartamento');
    
    // Se comprueba si la respuesta es satisfactoria, de lo contrario se remueve la etiqueta canvas.
    if (DATA.status) {
        // Se declaran los arreglos para guardar los datos a gráficar.
        let clientes = [];
        let departamentos = [];
        
        // Se recorre el conjunto de registros fila por fila a través del objeto row.
        DATA.dataset.forEach(row => {
            // Se agregan los datos a los arreglos.
            clientes.push(row.NumeroClientes);
            departamentos.push(row.nombre_departamento);
        });
        
        // Llamada a la función para generar y mostrar un gráfico de barra. Se encuentra en el archivo components.js.
        barGraph('chart6', departamentos, clientes, 'Número de Clientes por Departamento', 'Clientes');
    } else {
        console.log(DATA.error);
    }
};