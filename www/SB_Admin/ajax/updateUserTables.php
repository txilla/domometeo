<?php
    # - --------------------------------------------------------------------------------------------------------------------------- -
    # - Este fichero PHP tiene la función de resolver peticiones AJAX para actualizar las tablas de los usuarios de forma asincrona -
    # - --------------------------------------------------------------------------------------------------------------------------- -

    # Creamos los includes para los diferentes ficheros PHP necesarios para que funcione todo correctamente:
    include ("../../Clases/sqlite.php");
    include ("../../Clases/user.php");
    include ("../../Recursos/variables.php");

    // Instanciamos la clase de la base de datos:
    $sqliteConnection = new Cl_SQLite($ficheroSqlite);

    // Recogemos la accion principal que nos indicara que información quiere la petición AJAX:
    $accion = $_POST['principal'];

    if($accion != null && $accion != "")
    {
        switch ($accion) {
            case 'activeUsers':
                $users = $sqliteConnection->getActiveUsers();                                               // Obtenemos los usuarios activos
                if(count($users) > 0)
                    $json = json_encode($users);                                                            // Transformamos el Array de objetos Cl_User a una array de objetos con formato JSON
                else
                    $json = '[{"error":2, "descripcion":"Información: No hay usuarios activos"}]';          // Si no hay usuarios creamos el mensaje informativo
                break;
            case 'disableUsers':
                $users = $sqliteConnection->getInactiveUsers();                                             // Obtenemos los usuarios inactivos
                if(count($users) > 0)
                    $json = json_encode($users);                                                            // Tranformamos el Array de objetos Cl_User a una array de objetos con formato JSON
                else
                    $json = '[{"error":2, "descripcion":"Información: No hay usuarios deshabilitados"}]';   // Si no hay usuarios creamos el mensaje informativo
            break;
            default:
                // Creamos la array con el error y lo transformamos a json
                $datos[] = array('ok' => false, 'descripcion' => 'El parametro principal no existe');       // Creamos el mensaje de error
                $json = json_encode($datos);
                break;
        }
    }
    else
    {
        // Creamos la array con el error y lo transformamos a json
        $datos[] = array('ok' => false, 'descripcion' => 'Faltan parametros');
        $json = json_encode($datos);
    }

    // Retornamos el JSON a la petición AJAX con un echo
    echo $json;

?>