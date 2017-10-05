<?php

    # Este PHP tiene la función de realizar el log out del usuario, para cerrar por completo la sesión, y destruir la información de la sesión.
    session_start();

    // Destruimos la sesión abierta
    session_destroy();

    // En el caso de que hubiera alguna cookie aqui deberiamos hacer que expirara

    // Reedireccionamos al usuario a la pagina de login para finalizar el logout
    header("Location:../../Login/login.php");

?>