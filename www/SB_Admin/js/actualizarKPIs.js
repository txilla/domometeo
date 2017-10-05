/* JAVASCRIPT PARA ACTUALIZAR KPIs */

// Función universal para actualizar la información de un KPI de temperatura exterior
function update_kpi(kpiName, kpiColorId, kpiValueId, nodeId, childNode, columnOrder, orderType, numberRows = 1)
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

            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla en el KPI
            if(json[0]['error'] == 1)
            {
                $('#'+kpiColorId+'').attr('class', 'panel panel-primary');
                $('#'+kpiValueId+'').text('Param Error');
            }
            else if(json[0]['error'] == 2)
            {
                $('#'+kpiColorId+'').attr('class', 'panel panel-primary');
                $('#'+kpiValueId+'').text('-');
            }
            else
            {
                var value = json[0]['value'];

                switch(kpiName)
                {
                    case 'temp_ext':
                        update_kpi_temp_ext(kpiColorId, kpiValueId, value);
                    break;
                    case 'hum_ext':
                        update_kpi_hum_ext(kpiColorId, kpiValueId, value);
                    break;
                    case 'door_ext':
                        update_kpi_door_ext(kpiColorId, kpiValueId, value);
                    break;
                    case 'rain_ext':
                        update_kpi_rain_ext(kpiColorId, kpiValueId, value);
                    break;
                    case 'temp_int':
                        update_kpi_temp_int(kpiColorId, kpiValueId, value);
                    break;
                    case 'hum_int':
                        update_kpi_hum_int(kpiColorId, kpiValueId, value);
                    break;
                    case 'calidad_int':
                        update_kpi_calidad_int(kpiColorId, kpiValueId, value);
                    break;
                }
            }
            
        }
    });
}

function update_kpi_rain_ext(kpiColorId, kpiValueId, state)
{
    var cadenaEstado = "";

    if(state == 2)
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-green');
        cadenaEstado = "No llueve";
    }
    else if(state == 1)
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-yellow');
        cadenaEstado = "Va a llover";
    }
    else
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-primary');
        cadenaEstado = "Llueve";
    }

    $('#'+kpiValueId+'').text(cadenaEstado);
}

function update_kpi_door_ext(kpiColorId, kpiValueId, state)
{
    var cadenaEstado = "";

    if(state == 0)
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-primary');
        cadenaEstado = "Cerrada";
    }      
    else
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-red');
        cadenaEstado = "Abierta";
    }
         
    $('#'+kpiValueId+'').text(cadenaEstado);    
}

function update_kpi_temp_int(kpiColorId, kpiValueId, temp)
{
    if(temp <=18)
        $('#'+kpiColorId+'').attr('class', 'panel panel-primary'); 
    else if(temp > 18 && temp < 28)
        $('#'+kpiColorId+'').attr('class', 'panel panel-green');
    else if(temp >=28)
        $('#'+kpiColorId+'').attr('class', 'panel panel-red');
    
    $('#'+kpiValueId+'').text(temp);    
}

function update_kpi_hum_int(kpiColorId, kpiValueId, hum)
{
    if(hum >= 30 && hum <= 50)
        $('#'+kpiColorId+'').attr('class', 'panel panel-green');
    else
        $('#'+kpiColorId+'').attr('class', 'panel panel-red');        
    
    $('#'+kpiValueId+'').text(hum);
}

function update_kpi_calidad_int(kpiColorId, kpiValueId, calidad)
{

    if(calidad > 350 && calidad <= 500)
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-green');
        $('#'+kpiValueId+'').text('Optima');
    }
    else if(calidad > 500 && calidad <= 800)
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-primary');
        $('#'+kpiValueId+'').text('Buena');        
    }
    else if(calidad > 800 && calidad <= 1200)
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-yellow');
        $('#'+kpiValueId+'').text('Media');        
    }
    else if(calidad > 1200 )
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-red');
        $('#'+kpiValueId+'').text('Baja');        
    }
    else
    {
        $('#'+kpiColorId+'').attr('class', 'panel panel-primary');
        $('#'+kpiValueId+'').text('-');
    }
    
    //$('#'+kpiValueId+'').text(hum);
}

function update_kpi_temp_ext(kpiColorId, kpiValueId, temp)
{
    if(temp <= 10)
        $('#'+kpiColorId+'').attr('class', 'panel panel-primary'); 
    else if(temp > 10 && temp < 26)
        $('#'+kpiColorId+'').attr('class', 'panel panel-green');
    else
        $('#'+kpiColorId+'').attr('class', 'panel panel-red');
    
    $('#'+kpiValueId+'').text(temp);    
}

function update_kpi_hum_ext(kpiColorId, kpiValueId, hum)
{
    if(hum >= 30 && hum <= 50)
        $('#'+kpiColorId+'').attr('class', 'panel panel-green');
    else
        $('#'+kpiColorId+'').attr('class', 'panel panel-red');        
    
    $('#'+kpiValueId+'').text(hum);
}

function update_kpi_flecha(KpiFlechaTempInt, nodeId, childNode, columnOrder, orderType, numberRows = 5)
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

            // Eliminamos todas las filas de la tabla humedad
            /*$('#'+idTabla+' > tbody > tr').remove();

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
                    $('#'+idTabla+' > tbody').append('<tr> <td>' + json[i]['date'] +'</td> <td>'+ json[i]['value'] +' '+factor+'</td> </tr>');
                }
            }*/


            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla en el KPI
            if(json[0]['error'] == 1)
            {
                $('#'+KpiFlechaTempInt+'').attr('class', 'fa fa-minus fa-2x');
                //$('#'+kpiValueId+'').val('Param Error');
            }
            else if(json[0]['error'] == 2)
            {
                $('#'+KpiFlechaTempInt+'').attr('class', 'fa fa-minus fa-2x');
                //$('#'+kpiValueId+'').val('Sin datos');
            }
            else
            {
                $valorAnterior=222;
                $tendencia =0;

                for(i in json)
                {
                    var value = json[i]['value'];

                    if ($valorAnterior != 222)
                            {
                                if( $valorAnterior < value)
                                {
                                    if ($tendencia == 0)
                                    {
                                        $tendencia = 1;
                                    }
                                    else if ($tendencia == 1 || $tendencia == 4)
                                    {
                                        $tendencia = 1;
                                    }
                                    else if ($tendencia != 1)
                                    {
                                        $tendencia = 3;
                                    }
                                    
                                }
                                else if( $valorAnterior > value)
                                {
                                    if ($tendencia == 0)
                                    {
                                        $tendencia = 2;
                                    }
                                    else if ($tendencia == 2 || $tendencia == 4)
                                    {
                                        $tendencia = 2;
                                    }
                                    else if ($tendencia != 2)
                                    {
                                        $tendencia = 3;
                                    }
                                }
                                else if( $valorAnterior == value)
                                {
                                    if ($tendencia == 0)
                                    {
                                        $tendencia = 4;
                                    }
                                    else if ($tendencia == 1)
                                    {
                                        $tendencia = 1;
                                    }
                                    else if ($tendencia == 2)
                                    {
                                        $tendencia = 2;
                                    }
                                    else
                                    {
                                        $tendencia = 3;
                                    } 
                                }
                                else
                                {
                                    $tendencia = 3;
                                }
                            }

                            $valorAnterior = value;
                }
                //var value = json[0]['value'];

                if ($tendencia == 2)
                {
                    $('#'+KpiFlechaTempInt+'').attr('class', 'fa fa-arrow-up fa-2x');
                    //echo '<i id="KpiFlechaTempInt" class="fa fa-arrow-up fa-2x"></i>';
                    
                }
                else if ($tendencia == 1)
                {
                    $('#'+KpiFlechaTempInt+'').attr('class', 'fa fa-arrow-down fa-2x');
                    //echo '<i id="KpiFlechaTempInt" class="fa fa-arrow-down fa-2x"></i>';
                    
                }
                else
                {
                    $('#'+KpiFlechaTempInt+'').attr('class', 'fa fa-minus fa-2x');
                    //echo '<i id="KpiFlechaTempInt" class="fa fa-minus fa-2x"></i>';
                    
                }

                /*if(value >= 30 && value <= 50)
                    $('#'+kpiColorId+'').attr('class', 'fa fa-minus fa-2x');
                else
                    $('#'+kpiColorId+'').attr('class', 'fa fa-minus fa-2x');*/
            }


        }
    });
}

