    // Constante para completar la ruta de la API.
    const SERVICIOS_API = 'services/admin/servicios.php';

    const DATA_EMPLEADOS = document.getElementById("cantidadEmpleados");
    const DATA_CLIENTES = document.getElementById("cantidadClientes");
    const DATA_FACTURAS = document.getElementById("cantidadFacturas");

    // Método del evento para cuando el documento ha cargado.
    document.addEventListener('DOMContentLoaded', () => {
        // Llamada a la función para mostrar el encabezado y pie del documento.
        loadTemplate();
        // Llamada a las funciones que generan los gráficos en la página web.
        graficoPastelServicios();
        graficoLineasFacturasPorMes(); // Asegúrate de que esta función esté llamada
        readDashboardStats();
        graficoPrediccionClientes();
        graficoMontoTotalPorServicios();
    });

    // Función para obtener los datos de facturas por mes
    const fetchFacturasPorMes = async () => {
        try {
            // Construye la URL completa con el endpoint correcto
            const url = `${SERVICIOS_API}?action=facturasPorMes`;
    
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'facturasPorMes' }) // Asegúrate de que el backend espere un JSON con una acción
            });
    
            // Comprueba si la respuesta es exitosa
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
    
            const data = await response.json();
            console.log('Datos recibidos de la API:', data); // Añade esta línea para depuración
    
            if (data.status === 1) {
                return data.dataset;
            } else {
                console.error(data.error);
                return [];git 
            }
        } catch (error) {
            console.error('Error fetching data:', error);
            return [];
        }
    };
    

    // Función para renderizar el gráfico de líneas
    const graficoLineasFacturasPorMes = async () => {
        const data = await fetchFacturasPorMes();

        // Crear un arreglo con los nombres de los meses
        const meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        // Inicializar los datos con 0
        const dataValues = new Array(12).fill(0);

        // Extraer los totales de facturas para cada mes
        data.forEach(item => {
            const monthIndex = parseInt(item.mes) - 1; // Asegúrate de que 'mes' sea el número del mes (1-12)
            if (monthIndex >= 0 && monthIndex < 12) {
                dataValues[monthIndex] = item.total_facturas; // Asumimos que 'total_facturas' es el valor numérico
            }
        });

        // Usar la función lineGraph para crear el gráfico
        lineGraph('facturasPorMes', meses, dataValues, 'Facturas Emitidas', 'Facturas Emitidas por Mes');
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

    // Función para mostrar un gráfico de pastel con el porcentaje de productos por categoría
    const graficoPastelServicios = async () => {
        try {
            const DATA = await fetchData(SERVICIOS_API, 'serviciosOfrecidos');
            if (DATA.status) {
                let legends = [];
                let values = [];
                DATA.dataset.forEach(row => {
                    legends.push(row.tipo_servicio);
                    values.push(row.cantidad);
                });
                pieGraph('chart2', legends, values, 'Porcentaje de productos servicios');
            } else {
                document.getElementById('chart2').remove();
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

            lineGraph('chart3', meses, clientes, 'Predicción de Clientes para el Próximo Mes');
        } else {
            document.getElementById('chart3').remove();
            console.log(DATA.error);
        }
    };
