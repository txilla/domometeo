<?php

    # We include other important files
    include ("../../Clases/user.php");
    include ("../../Clases/sqlite.php");
    include ("../../Clases/sensorValue.php");
    include ("../../Recursos/variables.php");

	session_start(); // We open the session

    ##### CONTROL DE ACCESO #####
    // Comprobamos si el estado del logeo es correcto para autorizarle el acceso al dashboard, si no lo es lo reedireccionamos al login para que inicie sesión
    if($_SESSION['loginState'] != true)
    {
        header("Location:../../Login/login.php"); // Reedireccionamos al login
    }
    else
    {

        // Obtenemos la información del usuario que ha iniciado sesión de forma correcta
        $usuario = $_SESSION['user'];

        // Instanciamos el objeto de base de datos
        $sqliteConnection = new Cl_SQLite($ficheroSqlite);
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="EstacionDomoMeteorologica" content="Web para la administración de las estaciones domoticas">
    <meta name="ArantxaRuben" content="">
    <link rel="shortcut icon" href="../../Imagenes/Iconos/icon-home.png" type="image/x-icon">

    <title>Domo Meteo - Estación Interna </title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css">

    <!-- Ionicons Icon Font Ionic Framework CSS
    <link href="../vendor/ionicons/css/ionicons.css" rel="stylesheet"> -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Domo Meteo</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!--
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <strong>John Smith</strong>
                                    <span class="pull-right text-muted">
                                        <em>Yesterday</em>
                                    </span>
                                </div>
                                <div>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque eleifend...</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Read All Messages</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul> -->
                    <!-- /.dropdown-messages

                </li> -->
                <!-- /.dropdown -->

                <!-- <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-tasks fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-tasks">
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 1</strong>
                                        <span class="pull-right text-muted">40% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                            <span class="sr-only">40% Complete (success)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 2</strong>
                                        <span class="pull-right text-muted">20% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                            <span class="sr-only">20% Complete</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 3</strong>
                                        <span class="pull-right text-muted">60% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                            <span class="sr-only">60% Complete (warning)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <p>
                                        <strong>Task 4</strong>
                                        <span class="pull-right text-muted">80% Complete</span>
                                    </p>
                                    <div class="progress progress-striped active">
                                        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                            <span class="sr-only">80% Complete (danger)</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Tasks</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <!-- /.dropdown -->

                <!-- <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                    <span class="pull-right text-muted small">12 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> Message Sent
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-tasks fa-fw"></i> New Task
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                    <span class="pull-right text-muted small">4 minutes ago</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>See All Alerts</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>

                </li> -->
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="userProfile.php"><i class="fa fa-user fa-fw"></i> Perfil de usuario</a>
                        </li>
                        <!-- <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a> -->
                        </li>
                        <li class="divider"></li>
                        <li><a href="../functions/logout.php"><i class="fa fa-sign-out fa-fw"></i> Cerrar sesión</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <!-- <li class="sidebar-search">
                             <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                        </li> -->
                        <li>
                            <a href="#"><i class="fa fa-dashboard fa-fw"></i> Dashboard <span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="index.php"> <i class="fa fa-home"></i> Estación interna</a>
                                </li>
                                <li>
                                    <a href="estacionExterna.php"> <i class="fa fa-sun-o"></i> Estación externa</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="morris.php"><i class="fa fa-bar-chart-o fa-fw"></i> Gráficos </span></a>
                            <!--<ul class="nav nav-second-level">
                                <li>
                                    <a href="flot.html">Flot Charts</a>
                                </li>
                                <li>
                                    <a href="morris.html">Morris.js Charts</a>
                                </li>
                            </ul> -->
                            <!-- /.nav-second-level -->
                        </li>
                        <!-- A traves de PHP comprobamos que el usuario sea administrador para poder visualizar esta sección sino no aparece -->
                        <?php if($usuario->getRole() == 'admin'): ?>
                        <li>
                            <a href="usuarios.php"> <i class="fa fa-users"> </i> Usuarios </a>
                        </li>
                        <li>
                            <a href="rpiSystem.php"> <i class="fa fa-linux"></i> Monitorización </a>
                        </li>
                        <?php endif; ?>
                        <!-- <li>
                            <a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a>
                        </li>
                        <li>
                            <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panels-wells.html">Panels and Wells</a>
                                </li>
                                <li>
                                    <a href="buttons.html">Buttons</a>
                                </li>
                                <li>
                                    <a href="notifications.html">Notifications</a>
                                </li>
                                <li>
                                    <a href="typography.html">Typography</a>
                                </li>
                                <li>
                                    <a href="icons.html"> Icons</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grid</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="blank.html">Blank Page</a>
                                </li>
                                <li>
                                    <a href="login.html">Login Page</a>
                                </li>
                            </ul>
                        </li> -->
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Contenido principal del DASHBOARD -->

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Estación interna</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!--/.row -->
            <!-- /.row -->
            <!-- <div class="row">  Row inncesaria te lo corrijo Arantxa ya que hacia que la estructura HTML no quedara bien y el HTML no validaba -->

                <!-- Creamos una row (fila) -->
            <div class="row">
                <! -- KPI ULTIMA TEMPERATURA -->
                <div class="col-lg-3 col-md-6">
                    <?php
                    // Generamos por PHP 5 filas con la temperatura exterior, mostrando la fecha y el valor
                    $sensorValuesArray = array();
                    $sensorValuesArray = $sqliteConnection->getSensorValue(1, 3, "_date", "desc", 1); // Retornara una array con 10 objetos de sensorValues

                    $valorTemp ="";
                    if ($sensorValuesArray)
                    {
                        foreach ($sensorValuesArray as $key => $sensorValue) {
                            //$sensorValue->value = 20;
                            if ($sensorValue->value <=18 )
                            {
                                echo '<div id="KpiColorTempInt" class="panel panel-primary">';
                            }
                            else if ($sensorValue->value > 18 && $sensorValue->value < 28)
                            {
                                echo '<div id="KpiColorTempInt" class="panel panel-green">';
                            }
                            else if ($sensorValue->value >= 28 )
                            {
                                echo '<div id="KpiColorTempInt" class="panel panel-red">';
                            }

                            $valorTemp = "$sensorValue->value";
                        }
                    }
                    else
                    {
                        echo '<div id="KpiColorTempInt" class="panel panel-primary">';
                        $valorTemp = "-";
                    }

                    $sensorValuesArrayFlecha = array();
                    //$tendencia = array();
                    $sensorValuesArrayFlecha = $sqliteConnection->getSensorValue(1, 3, "_date", "desc", 5); // Retornara una array con 10 objetos de sensorValues

                    if($sensorValuesArrayFlecha)
                    {
                        $valorAnterior=222;
                        $tendencia =0; // ni crece ni decrece 1 crece 2 decrece
                        foreach ($sensorValuesArrayFlecha as $key => $sensorValue)
                        {

                            if ($valorAnterior != 222)
                            {
                                if( $valorAnterior < $sensorValue->value)
                                {
                                    if ($tendencia == 0)
                                    {
                                        $tendencia = 1;
                                    }
                                    else if ($tendencia == 1 || $tendencia == 4)
                                    {
                                        $tendencia = 1;
                                    }
                                    else if ($tendencia != 1)
                                    {
                                        $tendencia = 3;
                                    }

                                }
                                else if( $valorAnterior > $sensorValue->value)
                                {
                                    if ($tendencia == 0)
                                    {
                                        $tendencia = 2;
                                    }
                                    else if ($tendencia == 2 || $tendencia == 4)
                                    {
                                        $tendencia = 2;
                                    }
                                    else if ($tendencia != 2)
                                    {
                                        $tendencia = 3;
                                    }
                                }
                                else if( $valorAnterior == $sensorValue->value)
                                {
                                    if ($tendencia == 0)
                                    {
                                        $tendencia = 4;
                                    }
                                    else if ($tendencia == 1)
                                    {
                                        $tendencia = 1;
                                    }
                                    else if ($tendencia == 2)
                                    {
                                        $tendencia = 2;
                                    }
                                    else
                                    {
                                        $tendencia = 3;
                                    }
                                }
                                else
                                {
                                    $tendencia = 3;
                                }
                            }

                            $valorAnterior = $sensorValue->value;

                            //$valorTemp = "$tendencia";
                        }


                    }

                ?>
                    <!-- <div class="panel panel-primary"> -->
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3 text-left">
                                    <i class="fa fa-thermometer-three-quarters fa-5x"></i>

                                </div>
                                <div class="col-xs-1">
                                    <?php

                                        if ($tendencia == 2)
                                        {
                                             echo '<i id="KpiFlechaTempInt" class="fa fa-arrow-up fa-2x"></i>';

                                        }
                                        else if ($tendencia == 1)
                                        {
                                             echo '<i id="KpiFlechaTempInt" class="fa fa-arrow-down fa-2x"></i>';

                                        }
                                        else
                                        {
                                            echo '<i id="KpiFlechaTempInt" class="fa fa-minus fa-2x"></i>';

                                        }
                                        // De la array de temperatura recuperamos el primer objeto de lectura de temperatura que es el mas reciente
                                        //echo "<div id='KpiValueTempInt' class='huge'>".$valorTemp."</div>";
                                    ?>
                                </div>
                                <div class="col-xs-7 text-right">
                                    <?php
                                        // De la array de temperatura recuperamos el primer objeto de lectura de temperatura que es el mas reciente
                                        echo "<div id='KpiValueTempInt' class='huge'>".$valorTemp."</div>";
                                    ?>
                                    <div>Temperatura actual</div>
                                </div>

                            </div>
                        </div>
                        <a href="#tablaTemperatura">
                            <div class="panel-footer">
                                <span class="pull-left">Ver Detalles</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <a href="morris.php#chartTempInt">
                            <div class="panel-footer">
                                <span class="pull-left">Ver Gráfica</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>


                <! -- KPI ULTIMA HUMEDAD -->
                <div class="col-lg-3 col-md-6">
                    <?php
                    // Generamos por PHP 5 filas con la temperatura exterior, mostrando la fecha y el valor
                    $sensorValuesArray = array();
                    $sensorValuesArray = $sqliteConnection->getSensorValue(1, 2, "_date", "desc", 1); // Retornara una array con 10 objetos de sensorValues

                    if ($sensorValuesArray)
                    {
                        foreach ($sensorValuesArray as $key => $sensorValue) {

                            //$sensorValue->value = 20;
                            if ($sensorValue->value >= 30 && $sensorValue->value <= 50)
                            {
                                echo '<div  id="KpiColorHumInt" class="panel panel-green">';
                            }
                            else
                            {
                                echo '<div id="KpiColorHumInt" class="panel panel-red">';
                            }

                            $valorHum = "$sensorValue->value";
                        }
                    }
                    else
                    {
                        echo '<div id="KpiColorHumInt" class="panel panel-primary">';
                        $valorHum = "-";
                    }

                ?>
                    <!-- <div class="panel panel-primary"> -->
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-percent fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <?php
                                        // De la array de temperatura recuperamos el primer objeto de lectura de humedad que es el mas reciente
                                        echo "<div id='KpiValueHumInt' class='huge'>".$valorHum."</div>";
                                    ?>
                                    <div>Humedad</div>
                                    <div>actual</div>
                                </div>
                            </div>
                        </div>
                        <a href="#tablaHumedad">
                            <div class="panel-footer">
                                <span class="pull-left">Ver Detalles</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <a href="morris.php#chartHumInt">
                            <div class="panel-footer">
                                <span class="pull-left">Ver Gráfica</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>

                <! -- KPI CALIDAD AIRE -->
                <div class="col-lg-3 col-md-6">
                    <?php

                    $valorCalidad = "";
                    // Generamos por PHP 5 filas con la temperatura exterior, mostrando la fecha y el valor
                    $sensorValuesArray = array();
                    $sensorValuesArray = $sqliteConnection->getSensorValue(1, 4, "_date", "desc", 1); // Retornara una array con 10 objetos de sensorValues

                    if($sensorValuesArray)
                    {
                        foreach ($sensorValuesArray as $key => $sensorValue) {
                            //$sensorValue->value = 1300;
                            if ($sensorValue->value > 350 && $sensorValue->value <= 500)
                            {
                                echo '<div id="KpiColorCalidad" class="panel panel-green">';
                               $valorCalidad = "Optima";
                            }
                            else if ($sensorValue->value > 500 && $sensorValue->value <= 800)
                            {
                                echo '<div id="KpiColorCalidad" class="panel panel-primary">';
                               $valorCalidad = "Buena";
                            }
                            else if ($sensorValue->value > 800 && $sensorValue->value <= 1200)
                            {
                                echo '<div id="KpiColorCalidad" class="panel panel-yellow">';
                               $valorCalidad = "Media";
                            }
                            else if ($sensorValue->value > 1200)
                            {
                                echo '<div id="KpiColorCalidad" class="panel panel-red">';
                               $valorCalidad = "Baja";
                            }
                            else
                            {
                                echo '<div id="KpiColorCalidad" class="panel panel-primary">';
                                $valorCalidad = "-";
                            }

                        }
                    }
                    else
                    {
                        echo '<div class="panel panel-primary">';
                        $valorCalidad = "-";
                    }

                ?>
                    <!-- <div class="panel panel-primary"> -->
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-percent fa-4x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <?php
                                        // De la array de temperatura recuperamos el primer objeto de lectura de humedad que es el mas reciente
                                        echo "<div id='KpiValueCalidad' class='huge'>".$valorCalidad."</div>";
                                    ?>
                                    <div>Calidad Aire</div>
                                    <div>Interior</div>
                                </div>
                            </div>
                        </div>
                        <a href="#tablaCalidadAire">
                            <div class="panel-footer">
                                <span class="pull-left">Ver Detalles</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>


            </div>
            <!-- Creamos una row (fila) -->
            <div class="row">
                <!-- /.col-lg-6 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-thermometer-half"></i> Temperatura Interior
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tablaTemperatura" class="table">
                                    <thead>
                                        <tr> <th> Fecha </th> <th> Valor </th> </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            // Generamos por PHP 5 filas con la temperatura exterior, mostrando la fecha y el valor
                                            $sensorValuesArray = array();
                                            $sensorValuesArray = $sqliteConnection->getSensorValue(1, 3, "_date", "desc", 5); // Retornara una array con 10 objetos de sensorValues

                                            if(count($sensorValuesArray) > 0)
                                            {
                                                // Recorremos los 5 objetos de la temperatura obtenida al cargar la pagina por PHP y los mostramos en la tabla
                                                foreach ($sensorValuesArray as $key => $sensorValue) {
                                                    echo "<tr> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." ºC</td> </tr>";
                                                }
                                            }
                                            else
                                            {
                                                echo "<tr> <td> - </td> <td> Información: No hay datos </td> </tr>";
                                            }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                <!-- /.col-lg-6 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-percent"></i> Humedad Interior
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tablaHumedad" class="table">
                                    <thead>
                                        <tr> <th> Fecha </th> <th> Valor </th> </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $sensorValuesArray = array();
                                            $sensorValuesArray = $sqliteConnection->getSensorValue(1, 2, "_date", "desc", 5); // Retornara una array con 10 objetos de sensorValues

                                            
                                            if(count($sensorValuesArray) > 0)
                                            {
                                                // Recorremos los 5 objetos de la temperatura obtenida al cargar la pagina por PHP y los mostramos en la tabla
                                                foreach ($sensorValuesArray as $key => $sensorValue) {
                                                    echo "<tr> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." %</td> </tr>";
                                                }
                                            }
                                            else
                                            {
                                                echo "<tr> <td> - </td> <td> Información: No hay datos </td> </tr>";
                                            }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->
                <!-- /.col-lg-6 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-lightbulb-o"></i> Nivel de Luz
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div  class="table-responsive">
                                <table id="tablaLuces" class="table">
                                    <thead>
                                        <tr> <th> Fecha </th> <th> Valor </th> </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sqliteConnection = new Cl_SQLite($ficheroSqlite);

                                            $sensorValuesArray = array();
                                            $sensorValuesArray = $sqliteConnection->getSensorValue(1, 0, "_date", "desc", 5); // Retornara una array con 10 objetos de sensorValues

                                            
                                            if(count($sensorValuesArray) > 0)
                                            {
                                                // Recorremos los 5 objetos de la temperatura obtenida al cargar la pagina por PHP y los mostramos en la tabla
                                                foreach ($sensorValuesArray as $key => $sensorValue) {
                                                    echo "<tr> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." Lux</td> </tr>";
                                                }
                                            }
                                            else
                                            {
                                                echo "<tr> <td> - </td> <td> Información: No hay datos </td> </tr>";
                                            }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->





            </div>
            <!--/.row -->
            <!-- Creamos una row (fila) -->
            <div class="row">
                <!-- /.col-lg-6 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-arrows-alt" aria-hidden="true"></i> Detector de Movimiento
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tablaPresencia" class="table">
                                    <thead>
                                        <tr> <th> Fecha </th>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $sensorValuesArray = array();
                                            $sensorValuesArray = $sqliteConnection->getSensorValue(1, 1, "_date", "desc", 5); // Retornara una array con 10 objetos de sensorValues

                                            
                                            if(count($sensorValuesArray) > 0)
                                            {
                                                // Recorremos los 5 objetos de la temperatura obtenida al cargar la pagina por PHP y los mostramos en la tabla
                                                foreach ($sensorValuesArray as $key => $sensorValue) {
                                                    echo "<tr> <td>" . $sensorValue->date ."</td> </tr>";
                                                }
                                            }
                                            else
                                            {
                                                echo "<tr> <td> - </td> <td> Información: No hay datos </td> </tr>";
                                            }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-6 -->

                <!-- /.col-lg-6 -->
                <div class="col-lg-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-rss  "></i> Nivel de CO2
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tablaCalidadAire" class="table">
                                    <thead>
                                        <tr> <th> Fecha </th> <th> Valor </th> </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $sqliteConnection = new Cl_SQLite($ficheroSqlite);

                                            $sensorValuesArray = array();
                                            $sensorValuesArray = $sqliteConnection->getSensorValue(1, 4, "_date", "desc", 5); // Retornara una array con 10 objetos de sensorValues

                                            

                                            if(count($sensorValuesArray) > 0)
                                            {
                                                // Recorremos los 5 objetos de la temperatura obtenida al cargar la pagina por PHP y los mostramos en la tabla
                                                foreach ($sensorValuesArray as $key => $sensorValue) {

                                                    /*if ($sensorValue->value < 300)
                                                    {
                                                        echo "<tr> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." ppm</td> </tr>";
                                                    }
                                                    else
                                                    {
                                                        echo "<tr class='bg-danger'> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." ppm</td> </tr>";
                                                    }*/

                                                    if ($sensorValue->value > 350 && $sensorValue->value <= 500)
                                                    {
                                                        echo "<tr> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." ppm</td> </tr>";
                                                    }
                                                    else if ($sensorValue->value > 500 && $sensorValue->value <= 800)
                                                    {
                                                        echo "<tr> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." ppm</td> </tr>";
                                                    }
                                                    else if ($sensorValue->value > 800 && $sensorValue->value <= 1200)
                                                    {
                                                        echo "<tr class='bg-warning'> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." ppm</td> </tr>";
                                                    }
                                                    else if ($sensorValue->value > 1200)
                                                    {
                                                        echo "<tr class='bg-danger'> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." ppm</td> </tr>";
                                                    }
                                                    else
                                                    {
                                                        echo "<tr> <td>" . $sensorValue->date ."</td> <td>". $sensorValue->value ." ppm</td> </tr>";
                                                    }
                                                }
                                            }
                                            else
                                            {
                                                echo "<tr> <td> - </td> <td> Información: No hay datos </td> </tr>";
                                            }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.row -->
            </div>
        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    <!-- Se añaden las referencias a los ficheros Java Script al final del codigo para que la pagina cargue mas rapido -->

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script> -->

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <!-- Funciones de la pagina -->
    <script src="../js/actualizarTablas.js"></script>
    <script src="../js/estacionInterna.js"></script>
    <script src="../js/actualizarKPIs.js"></script>

</body>

</html>
