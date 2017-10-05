<?php
    # - ----------------------------------------------------------------------------------------------------------------- -
    # - Este fichero PHP tiene la función de resolver peticiones AJAX para insertar actualizar servicios de forma asincrona -
    # - ------------------------------------------------------------------------------------------------------------------- -

    include ("../../Clases/service.php");
    include ("../functions/system.php");

    $apache = new Cl_Service('apache2.service', 'Apache 2 Web Server');                        // Servicio web
    $mysensors = new Cl_Service('mysgw.service', 'My Sensors Gateway Daemon');          // Gateway de MySensors
    $controller = new Cl_Service('ClienteRaspberry.jar', 'Java Controller Domo-Meteo');        // Nuestro Controller, este es un proceso pero lo tratamos como servicio

    $services = array();
    $services[] = $apache;
    $services[] = $mysensors;
    
    // Recorremos el Array y comprobamos el estado de los servicios
    foreach ($services as $index => $service) {
        $service->checkServiceStatus();
    }

    // Comprobamos el estado del proceso del controller
    if(existeProceso($controller->getName()))
    {
        $controller->setStatus('active (running)');
    }
    else
    {
        $controller->setStatus('inactive (dead)');
    }

    $services[] = $controller;

    $json = json_encode($services);

    echo $json;

?>