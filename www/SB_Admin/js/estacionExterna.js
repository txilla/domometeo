/* JAVASCRIPT PARA pages/estacionExterna.php */


// Declaramos los Timers
var myTempTable = setInterval (update_elements,3000)

// PROCEDIMIENTO QUE REALIZARA EL TIMER CUANDO SALTE QUE ACTUALIZARA LAS TABLAS POR AJAX
function update_elements()
{
    // Llamamos al metodo que nos actualizara todos los KPI de la web
    update_kpis();

    // Llamamos a la función que nos actualizara la tablas
    update_tables();
}

function update_tables()
{
    // Llamamos a las funciones que nos actualizaran las tablas
    update_sensor_table('tablaTempExt', 2, 1, '_date', 'desc', 5, 'ºC');       // Temperature
    update_sensor_table('tablaHumExt', 2, 0, '_date', 'desc', 5, '%');         // Humidity
    update_sensor_table('tablaPresionExt', 2, 3, '_date', 'desc', 5, ' hPa');  // Pressure
    
    // Llamamos a una función especifica que nos actualizara los datos de la detección de lluvia
    update_sensor_table_Lluvia('tablaLluvia', 2, 2, '_date', 'desc', 5);       // Rain

    // Llamamos a una función especifica que nos actualizara los datos de la detección apertura de la puerta principal
    update_sensor_table_Aperturas('tablaPuerta', 2, 4, '_date', 'desc', 5);    // Door
}

function update_kpis()
{
    update_kpi('temp_ext', 'KpiColorTemp', 'KpiValueTemp', 2, 1, '_date', 'desc', 1);    // Last Temperature
    update_kpi('hum_ext', 'KpiColorHum', 'KpiValueHum', 2, 0, '_date', 'desc', 1);       // Last Humidity
    update_kpi('door_ext', 'KpiColorDoor', 'KpiValueDoor', 2, 4, '_date', 'desc', 1);    // Last Door State
    update_kpi('rain_ext', 'KpiColorRain', 'KpiValueRain', 2, 2, '_date', 'desc', 1);    // Last Rain State
    update_kpi_flecha('KpiFlechaTempExt', 2, 1, '_date', 'desc', 5);                     // Update Arrow KPI to define the tendency
}
