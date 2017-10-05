<?php

    # We include other important files
    include ("../../Clases/user.php");

    session_start(); // We open the session

    ##### CONTROL DE ACCESO #####
    // Comprobar si hay ya una sessión abierta de un usuario en el navegador para reedireccionarlo a dashboard o si no al login para iniciar sesión
    if($_SESSION['loginState'] == true)
    {
        header("Location:SB_Admin/pages/index.php");  // Reedireccionamos al dashboard
    }
    else
    {
        header("Location:Login/login.php"); // Reedireccionamos al login
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Test domo</title>
    <!-- Bootstrap -->
    <link href="Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="Bootstrap/js/bootstrap.min.js"></script>
  </head>
  <body>
    <h1>Hello, I Am The Raspberry Pi Web Server!</h1>
  </body>
</html>