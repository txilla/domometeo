<?php
    # - -------------------------------------------------------------------------------------------------------------- -
    # - Este fichero PHP tiene la función de resolver peticiones AJAX para actualizar las monitores de forma asincrona -
    # - -------------------------------------------------------------------------------------------------------------- -

    # Creamos los includes para los diferentes ficheros PHP necesarios para que funcione todo correctamente:
    include ("../functions/system.php");
    include ("../functions/calculos.php");

    // Recogemos los datos del POST
    $accion = $_POST['principal'];

    if($accion == null || $accion == "")
    {
        $datos[] = array('error' => 1, 'descripcion' => 'Faltan parametros');
    }
    else
    {
        // Cada monitor tiene un resultado, en el caso de la CPU nos devuelve un tanto por cierto
        // Pero en el caso de la RAM nos devuelve la cantidad y luego obtenemos el tanto por ciento
        $cantidad = null;
        $porcentaje = null;

        // Segun el tipo de monitor que quieran actualizar hacemos una cosa u otra
        switch ($accion)
        {
            case 'cargaCPU':
                $porcentaje = obtenerCargaCpu();
            break;
            case 'ramUsada':
                $cantidad = obtenerRam('usada');
                $total = obtenerRam('total');
                $porcentaje = calcularPorcentaje($cantidad, $total);
            break;
            case 'ramDisponible':
                $cantidad = obtenerRam('disponible');
                $total = obtenerRam('total');
                $porcentaje = calcularPorcentaje($cantidad, $total);
            break;
        }

        // Construimos el JSON con los datos de retorno
        $datos[] = array('error' => 0, 'cantidad' => $cantidad, 'porcentaje' => $porcentaje);
    }

    $json = json_encode($datos);
    
    echo ($json);

?>
