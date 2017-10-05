/* JAVASCRIPT PARA ACTUALIZAR TABLAS */

// FUNCION UNIVERSAL PARA ACTUALIZAR LAS TABLAS DE LOS SENSORES POR JQUERY + AJAX
function update_sensor_table(idTabla, nodeId, childNode, columnOrder, orderType, numberRows, factor = "")
{
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':nodeId,
            'childNode':childNode,
            'columnOrder':columnOrder,
            'orderType':orderType,
            'numberRows':numberRows
        },
        complete: function updateTable(data) {

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                // Eliminamos todas las filas de la tabla humedad
                $('#'+idTabla+' > tbody > tr').remove();
                //$('#'+idTabla+' > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
                $('#'+idTabla+' > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
                
                
            }
            else
            {
                // Eliminamos todas las filas de la tabla humedad
                $('#'+idTabla+' > tbody > tr').remove();
                
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                }
            }
        }
    });
}

// FUNCIÓN ESPECIFICA PARA ACTUALIZAR TABLAS DE DETECTORES DE PRESENCIA
function update_sensor_table_Presence(idTabla, nodeId, childId, columnOrder, orderType, numberRows)
{
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':nodeId,
            'childNode':childId,
            'columnOrder':columnOrder,
            'orderType':orderType,
            'numberRows':numberRows
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla humedad
            $('#'+idTabla+' > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                //$('#'+idTabla+' > tbody').append('<tr> <td>'+ json[0]['descripcion'] +'</td> </tr>');
                $('#'+idTabla+' > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> </tr>');
                }
            }
        }
    });
}

// FUNCIÓN ESPECIFICA PARA ACTUALIZAR TABLAS DE CALIDAD DE AIRE
function update_sensor_table_CalidadAire(idTabla, nodeId, childId, columnOrder, orderType, numberRows, factor)
{
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':nodeId,
            'childNode':childId,
            'columnOrder':columnOrder,
            'orderType':orderType,
            'numberRows':numberRows
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla humedad
            $('#'+idTabla+' > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                $('#'+idTabla+' > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    /*if(json[i]['value'] < 300)
                    {
                        $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                    }
                    else
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-danger"> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                    }*/

                    if (json[i]['value'] > 350 && json[i]['value'] <= 500)
                    {
                        $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                    }
                    else if (json[i]['value'] > 500 && json[i]['value'] <= 800)
                    {
                        $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                    }
                    else if (json[i]['value'] > 800 && json[i]['value'] <= 1200)
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-warning"> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                    }
                    else if (json[i]['value'] > 1200)
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-danger"> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                    }
                    else
                    {
                        $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                    }

                    //$('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');                  
                }
            }
        }
    });
}

// FUNCIÓN ESPECIFICA PARA ACTUALIZAR TABLAS DE DETECCIÓN DE LLUVIA
function update_sensor_table_Lluvia(idTabla, nodeId, childId, columnOrder, orderType, numberRows)
{
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':nodeId,
            'childNode':childId,
            'columnOrder':columnOrder,
            'orderType':orderType,
            'numberRows':numberRows
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla humedad
            $('#'+idTabla+' > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                $('#'+idTabla+' > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    if(json[i]['value'] == 2)
                    {
                        $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td> Sin lluvia </td> </tr>');
                    }
                    else if(json[i]['value'] == 1)
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-warning"> <td>' + json[i]['date'] +'</td> <td> Previsión de lluvia </td> </tr>');
                    }
                    else
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-danger"> <td>' + json[i]['date'] +'</td> <td> Esta lloviendo </td> </tr>');
                    }                  
                }
            }
        }
    });
}

// FUNCIÓN ESPECIFICA PARA ACTUALIZAR TABLAS DE DETECCIÓN DE APERTURA DE PUERTAS Y VENTANAS
function update_sensor_table_Aperturas(idTabla, nodeId, childId, columnOrder, orderType, numberRows)
{
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':nodeId,
            'childNode':childId,
            'columnOrder':columnOrder,
            'orderType':orderType,
            'numberRows':numberRows
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla humedad
            $('#'+idTabla+' > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                $('#'+idTabla+' > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    if(json[i]['value'] == 0)
                    {
                        $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td> Cerrada </td> </tr>');
                    }
                    else
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-danger"> <td>' + json[i]['date'] +'</td> <td> Abierta </td> </tr>');
                    }                 
                }
            }
        }
    });
}

// FUNCIÓN ESPECIFICA PARA ACTUALIZAR TABLAS SENSOR DE GAS LPG
function update_sensor_table_LPG(idTabla, nodeId, childId, columnOrder, orderType, numberRows)
{
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':nodeId,
            'childNode':childId,
            'columnOrder':columnOrder,
            'orderType':orderType,
            'numberRows':numberRows
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla humedad
            $('#'+idTabla+' > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                $('#'+idTabla+' > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    if(json[i]['value'] <= 60)
                    {
                        $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td> Nivel de gas correcto </td> </tr>');
                    }
                    else if(json[i]['value'] > 60 && json[i]['value'] <= 1200)
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-warning"> <td>' + json[i]['date'] +'</td> <td> Atención Gas Detectado </td> </tr>');
                    }
                    else if(json[i]['value'] > 1200 && json[i]['value'] <= 10000)
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-danger"> <td>' + json[i]['date'] +'</td> <td> Peligro! </td> </tr>');                        
                    }                
                }
            }
        }
    });
}

// FUNCIÓN ESPECIFICA PARA ACTUALIZAR TABLAS SENSOR DE HUMO
function update_sensor_table_Smoke(idTabla, nodeId, childId, columnOrder, orderType, numberRows)
{
    $.ajax({
        url: '../ajax/updateSensorTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'nodeId':nodeId,
            'childNode':childId,
            'columnOrder':columnOrder,
            'orderType':orderType,
            'numberRows':numberRows
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla humedad
            $('#'+idTabla+' > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['error'] >= 1)
            {
                $('#'+idTabla+' > tbody').append('<tr> <td> - </td> <td>'+ json[0]['descripcion'] +'</td> </tr>');
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    if(json[i]['value']  <= 15)
                    {
                        $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td> No hay humo </td> </tr>');
                    }
                    else if(json[i]['value'] > 15 && json[i]['value'] <= 1200)
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-warning"> <td>' + json[i]['date'] +'</td> <td> Atención humo detectado </td> </tr>');
                    }
                    else if(json[i]['value'] > 1200 && json[i]['value'] <= 10000)
                    {
                        $('#'+idTabla+' > tbody').append('<tr class="bg-danger"> <td>' + json[i]['date'] +'</td> <td> Peligro, humo! </td> </tr>');                        
                    }                
                }
            }
        }
    });
}

