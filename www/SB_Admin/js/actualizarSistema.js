/* JAVASCRIPT PARA ACTUALIZAR LA PAGINA rpiSystem.php */

// Declaramos los Timers
var myTempMonitor = setInterval (update_elements,3000)

function update_elements()
{
    updateMonitors();           // Actualizar Monitores
    updateServices();
}

function updateServices()
{
    updateTable('tablaEstadoServicios');
}

function updateMonitors()
{
    updateMonitor('monitorCargaCPU', 'barraCargaCpu', 'cargaCPU');
    updateMonitor('monitorRAMUsada', 'barraCargaRamUsada', 'ramUsada');   
    updateMonitor('monitorRamAvailable', 'barraCargaRamDisponible', 'ramDisponible');    
     
}

function updateTable(idTabla)
{
    $.ajax({
        url: '../ajax/actualizarServicios.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'info':""
        },
        complete: function actualiza(data) {

            if(data.responseText != null && data.responseText != "")
            {
                var json = JSON.parse(data.responseText);
                var count = 0;                                  // Nos sirve para recorrer el JSON por cada objeto JSON de la Array

                // Recorremos cada fila de la tabla                          
                $('#'+idTabla+' > tbody > tr').each(function(index) {

                    // Recorremos cada columna de la tabla
                    $(this).children('td').each(function (index2) {

                        // Si la columna es la segunda comprobamos el estado del servicio actual y realizamos las tareas de actualizacion del estado
                        if(index2 == 1)
                        {
                            // Obtenemos el estado
                            var estadoServicio = json[count]['status'];
                            var etiquetaEstado = ''; 

                            if(estadoServicio == "active (running)")
                                etiquetaEstado = '<span class="label label-success">'+estadoServicio+'</span>';
                            else if(estadoServicio == "inactive (dead)")
                                etiquetaEstado = '<span class="label label-danger">'+estadoServicio+'</span>';
                            $(this).html(etiquetaEstado);
                        }
                    });

                    // Importante sumar el contador para ir al siguiente servicio
                    count++;
                });         
            }
        }
    });
}

// Funcion para actualizar los monitores del Sistema
function updateMonitor(idMonitor, idBarra, typeMonitor)
{
    $.ajax({
        url: '../ajax/updateMonitorSistema.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'principal':typeMonitor
        },
        complete: function update(data) {

            if(data.responseText != null && data.responseText != "")
            {
                var json = JSON.parse(data.responseText);

                // Si el JSON es un error lo mostramos por pantalla en el KPI
                if(json[0]['error'] == 1)
                {
                    $('#'+idMonitor+'').text('Param Error');
                }
                else if(json[0]['error'] == 2)
                {
                    $('#'+idMonitor+'').text('-');
                }
                else
                {
                    switch(typeMonitor)
                    {
                        case 'cargaCPU':
                            updateCarga_CPU(json[0]['porcentaje'], idMonitor, idBarra);
                        break;
                        case 'ramUsada':
                            updateRAM(json[0]['cantidad'], json[0]['porcentaje'], idMonitor, idBarra);
                        break;
                        case 'ramDisponible':
                            updateRAM(json[0]['cantidad'], json[0]['porcentaje'], idMonitor, idBarra);
                    }
                }
            }
        }
    });
}

function updateCarga_CPU(porcentaje, idMonitor, idBarra)
{
    $('#'+idMonitor+'').text(porcentaje + " %");
    $('#'+idBarra+'').attr('aria-valuenow', porcentaje);
    $('#'+idBarra+'').attr('style', 'width: ' + porcentaje + '%');
}

function updateRAM(cantidad, porcentaje, idMonitor, idBarra)
{
    $('#'+idMonitor+'').text(cantidad + " Mb");
    $('#'+idBarra+'').attr('aria-valuenow', porcentaje);
    $('#'+idBarra+'').attr('style', 'width: ' + porcentaje + '%');
}