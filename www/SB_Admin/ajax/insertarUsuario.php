<?php
    # - -------------------------------------------------------------------------------------------------------- -
    # - Este fichero PHP tiene la función de resolver peticiones AJAX para insertar $usuarios de forma asincrona -
    # - -------------------------------------------------------------------------------------------------------- -

    # Creamos los includes para los diferentes ficheros PHP necesarios para que funcione todo correctamente:
    include ("../../Clases/sqlite.php");
    include ("../../Clases/user.php");
    include ("../../Recursos/variables.php");

    // Variables del fichero PHP
    $numError = -1;                 // Nos especificara el numero de error que tendremos que retornar, si es 0 no hay error
    $userInserted = false;          // Variable que nos permitira saber si hemos podido insertar o no el usuario por defecto falso

    // Instanciamos la clase de la base de datos:
    $sqliteConnection = new Cl_SQLite($ficheroSqlite);

    // Recogemos la información principal de la petición AJAX que nos permitirá insertar el usuario en la BD
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $status = $_POST['state'];

    // Comprobamos que los datos que nos han pasado por la petición AJAX sean correctos:
    $numError = comprobarErrores($username, $password, $role, $status);

    // Si los datos son 100% seguros, procedemos a comprobar si no hay errores al insertar el usuario por parte de la base de datos
    if($numError == 0)
    {
        // Si el usuario no existe procedemos a insertarlo
        if(!$sqliteConnection->userExist($username))
        {
            // Instanciamos un objeto Cl_User con sus propiedades
            $user = new Cl_User($username, $password, $role, $status);
            
            // Insertamos el Usuario
            if($sqliteConnection->saveUser($user))
                $userInserted = true;                   // No hay error
            else
                $numError = 8;                          // Error inesperado al insertar el usuario en la base de datos
        }
        else
        {
            $numError = 7;                              // El usuario ya existe en la base de datos
        }
    }

    // Creamos una Array para retornar información de como ha ido el proceso a la petición Ajax
    $datos[] = array('ok' => $userInserted, 'descripcion' => $numError);

    // Pasamos la array a JSON
    $json = json_encode($datos);

    // Retornamos el JSON a la petición AJAX
    echo $json;

    // Función que comprobara si hay errores cuando nos mandan los datos por la petición AJAX
    function comprobarErrores($usuario, $password, $role, $status)
    {
        $numError = 0;

        if($usuario == "" || $password == "" || $role == "" || $status == "")
            $numError = 1;
        else if(count($usuario) <= 0)
            $numError = 2;
        else if(count($usuario) > 30)
            $numError = 3;
        else if(count($password) <= 0)
            $numError = 4;
        else if(count($password) > 30)
            $numError = 5;
        else if($role != "admin" && $role != "standard")
            $numError = 9;
        else if($status != 1 && $status != 0)
            $numError = 10;

        return($numError);
    }

?>