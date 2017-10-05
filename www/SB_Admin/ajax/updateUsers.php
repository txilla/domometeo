<?php
    # - ------------------------------------------------------------------------------------------------------------ -
    # - Este fichero PHP tiene la función de resolver peticiones AJAX para actualizar la información de los usuarios -
    # - ------------------------------------------------------------------------------------------------------------ -

    # Creamos los includes para los diferentes ficheros PHP necesarios para que funcione todo correctamente:
    include ("../../Clases/sqlite.php");
    include ("../../Clases/user.php");
    include ("../../Recursos/variables.php");

    // Instanciamos la clase de la base de datos:
    $sqliteConnection = new Cl_SQLite($ficheroSqlite);

    // Recogemos la accion principal que nos indicara que información quiere la petición AJAX:
    $accion = $_POST['principal'];
    $userName = $_POST['user'];
    $password = $_POST['password'];


    switch ($accion) {
        case 'activarUsuario':
            if($sqliteConnection->activateUser($userName))
                $datos[] = array('ok' => true, 'descripcion' => 'Usuario actualizado');
            else
                $datos[] = array('ok' => false, 'descripcion' => 'Error al actualizar el usuario');   
            break;
        case 'eliminarUsuario':
            if($sqliteConnection->deleteUser($userName))
                $datos[] = array('ok' => true, 'descripcion' => 'Usuario eliminado');
            else
                $datos[] = array('ok' => false, 'descripcion' => 'Error al eliminar el usuario');
            break;
        case 'desactivarUsuario':
            if($sqliteConnection->disableUser($userName))
                $datos[] = array('ok' => true, 'descripcion' => 'Usuario deshabilitado');
            else
                $datos[] = array('ok' => false, 'descripcion' => 'Error al deshabilitar el usuario');
            break;
        case 'cambiarPassword':
            if($password != "" && $userName != "")
            {
                if($sqliteConnection->changePassword($userName, $password))
                    $datos[] = array('ok' => true, 'descripcion' => 'Contraseña actualizada');
                else
                    $datos[] = array('ok' => true, 'descripcion' => 'Error, no se ha podido actualizar la contraseña');
            }
            else
                $datos[] = array('ok' => true, 'descripcion' => 'Error, parametros incorrectos');
            break;
        default:
            $datos[] = array('ok' => false, 'descripcion' => 'El parametro principal no existe');
            break;
    }

    // Pasamos la array a JSON
    $json = json_encode($datos);

    // Retornamos el JSON de la petición AJAX con un echo
    echo $json;


?>