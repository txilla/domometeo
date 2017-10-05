<?php
    // * This PHP file is for register users to access to the web, the work on this file is really important

    # We include other important files
    include ("../../Clases/user.php");
    include ("../../Clases/sqlite.php");
    include ("../../Recursos/variables.php");

    session_start();

    // We obtain the new username, the password and the password verification on the register page
    $username = $_POST['username'];
    $password = $_POST['password'];
    $vPassword = $_POST['confirm-password'];

    if($password != $vPassword)
    {
        echo "Error, las contraseñas no coinciden";
        $_SESSION['registrationError'] = 1;    // No match between both passwords
    }
    else
    {
        // When we obtained all the information, we chech if the username exist in the SQLite database
        $sqliteConnection = new Cl_SQLite($ficheroSqlite);

        $loginOk = false;

        // If the user exist throw an error
        if($sqliteConnection->userExist($username))
        {
            echo "Error, este nombre de usuario ya existe";
            $_SESSION['registrationError'] = 2;    //Error the username already exist
        }
        else    // If not exist, save it on the database
        {
            // Initialize a user object
            $user = new Cl_User($username, $password, 'standard', 0);

            if($sqliteConnection->saveUser($user))
            {
                // Return user registred correctly
                echo "Todo bien";
                $_SESSION['registrationError'] = 4; // Everything Ok
                $loginOk = true;
            }
            else
            {
                // Return user not registred correctly
                echo "Error de registro, contacte con el soporte tecnico";
                $_SESSION['registrationError'] = 3;    // Registration error, contact with technical support
            }
        }
    }

    header("Location:../login.php");

?>