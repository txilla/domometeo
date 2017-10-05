/* JAVASCRIPT PARA pages/index.php */


// Declaramos los Timers
var myTempTable = setInterval (update_elements,3000)

// PROCEDIMIENTO QUE REALIZARA EL TIMER CUANDO SALTE QUE ACTUALIZARÁ LAS TABLAS POR AJAX

function update_elements()
{
    // Llamamos al metodo que nos actualizara todos los KPI de la web
    update_kpis();

    // Llamamos a la función que nos actualizara la tablas
    update_tables();
}

function update_tables()
{
    // Llamamos a la función estandard que nos actualizaran las tablas con los datos mas recientes

    update_sensor_table('tablaTemperatura', 1, 3, '_date', 'desc', 5, 'ºC');                   // Temperatura
    update_sensor_table('tablaHumedad', 1, 2, '_date', 'desc', 5, '%');                     // Humedad
    update_sensor_table('tablaLuces', 1, 0 , '_date', 'desc', 5, 'Lux');                  // Luz
    update_sensor_table('tablaCO', 1, 6, '_date', 'desc', 5, 'ppm');                    // CO

    // Llamamos a una función especifica que nos actualizara los datos del detector de presencia

    update_sensor_table_Presence('tablaPresencia', 1, 1, '_date', 'desc', 5);           // Presencia

    // Llamamos a la función especifica que nos actualizara los datos del sensor de GAS
    
    update_sensor_table_CalidadAire('tablaCalidadAire', 1, 4, '_date', 'desc', 5, "ppm");      // Calidad de Aire
    //update_sensor_table_LPG('tablaLPG', 1, 7, '_date', 'desc', 5);                      // Gas Natural (LPG)
    //update_sensor_table_Smoke('tablaSmoke', 1, 5, '_date', 'desc', 5);                  // Humo

}

function update_kpis()
{
    update_kpi('temp_int', 'KpiColorTempInt', 'KpiValueTempInt', 1, 3, '_date', 'desc', 1)    // Last Temperature
    update_kpi('hum_int', 'KpiColorHumInt', 'KpiValueHumInt', 1, 2, '_date', 'desc', 1)       // Last Humidity
    update_kpi('calidad_int', 'KpiColorCalidad', 'KpiValueCalidad', 1, 4, '_date', 'desc', 1)       // Calidad Aire Interior
    update_kpi_flecha( 'KpiFlechaTempInt', 1, 3, '_date', 'desc', 5)
}

/*
function actualizar_Tabla_Humedad()
{
    // Actualizar de forma asincrona la Tabla de Humedad
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':1,
            'childNode':2,
            'columnOrder':'_date',
            'orderType':'desc',
            'numberRows':5
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla humedad
            $('#tablaHum > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                $('#tablaHum > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    $('#tablaHum > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' %</td> </tr>');
                }
            }
        }
    });
}

function actualizar_Tabla_Temperatura()
{
    // Actualizar de forma asincrona la Tabla de Temperatura
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':1,
            'childNode':3,
            'columnOrder':'_date',
            'orderType':'desc',
            'numberRows':5
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla temperatura
            $('#tablaTemp > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                $('#tablaTemp > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    $('#tablaTemp > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' ºC</td> </tr>');
                }
            }
        }
    });
}
*/
