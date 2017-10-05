<?php
    # - --------------------------------------------------------------------------------------------------------------------------- -
    # - Este fichero PHP tiene la función de resolver peticiones AJAX para actualizar las tablas de los sensores de forma asincrona -
    # - --------------------------------------------------------------------------------------------------------------------------- -

    # Creamos los includes para los diferentes ficheros PHP necesarios para que funcione todo correctamente:
    include ("../../Clases/sqlite.php");
    include ("../../Clases/sensorValue.php");
    include ("../../Recursos/variables.php");

    // Instanciamos la clase de la base de datos:
    $sqliteConnection = new Cl_SQLite($ficheroSqlite);

    // Recogemos el parametro principal que nos indicara que información quiere la petición AJAX:
    $nodeId = $_POST['nodeId'];
    $childNode = $_POST['childNode'];
    $columnOrder = $_POST['columnOrder'];
    $orderType = $_POST['orderType'];
    $numberRows = $_POST['numberRows'];

    // Nos aseguramos de que se hayan pasado todos los parametros ya que es información muy importante
    if($nodeId == null || $childNode == null || $columnOrder == null || $orderType == null || $orderType == null || $numberRows == null)
    {
        $json = '[{"error":1, "descripcion":"Error: Faltan parametros en la consulta"}]';
    }
    else
    {
        $sensorValuesArray = array();
        $sensorValuesArray = $sqliteConnection->getSensorValue($nodeId, $childNode, $columnOrder, $orderType, $numberRows);
        if(count($sensorValuesArray) > 0)
        {
            $json = json_encode($sensorValuesArray);
        }
        else
        {
            $json = '[{"error":2, "descripcion":"Información: No hay datos"}]';
        }
        
    }

    // Retornamos el json con la informacion para la petición AJAX
    echo $json;
?>