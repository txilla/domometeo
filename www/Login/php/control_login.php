<?php
    // * This PHP file is for control the access to the web, the work on this file is really important

    # We include other important files
    include ("../../Clases/user.php");
    include ("../../Clases/sqlite.php");
    include ("../../Recursos/variables.php");
    
    session_start();    // We open the session control

    // We obtain the username and the password that user posted on the login page
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];

    // When we obtained all the information, we check if the username exist in the SQLite database
    $sqliteConnection = new Cl_SQLite($ficheroSqlite);

    if($sqliteConnection->userExist($login_username))
    {
        // Obtain the user information
        $user = $sqliteConnection->getUser($login_username);
        
        if(!$user->userEnabled())   // If the user is not enabled, deny the access to the web page
        {
            echo "User Account not enabled";
            $loginOk = false;
            $_SESSION['errorLogin'] = 3;    // User account not enabled by the Administrator
        }
        else if($user->password == $login_password)
        {
            echo "Access granted";
            $loginOk = true;
            $_SESSION['user'] = $user;  // Access granted
        }
        else
        {
            echo "Incorrect password";
            $loginOk = false;
            $_SESSION['errorLogin'] = 2;    // Incorrect password
        }
    }
    else
    {
        echo "Username doesn't exist";
        $loginOk = false;
        $_SESSION['errorLogin'] = 1;    // Username not exist in the database
    }

    $_SESSION['loginState'] = $loginOk;

    // Re-direct to another page depends on the case
    switch ($_SESSION['loginState']) {
        case (true):
            header("Location:../../SB_Admin/index.html"); // Re-direct to the dashboard site
            break;
        
        case (false):
            header("Location:../login.php"); // Re-direct to the login site
            break;
    }
    
?>
