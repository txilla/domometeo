<?php

    # We include other important files
    include ("../../Clases/user.php");
    include ("../../Clases/sqlite.php");
    include ("../../Clases/sensorValue.php");
    include ("../../Clases/service.php");
    include ("../../Recursos/variables.php");
    include ("../functions/system.php");
    include ("../functions/calculos.php");

	session_start(); // We open the session

    ##### CONTROL DE ACCESO #####
    // Comprobamos si el estado del logeo es correcto para autorizarle el acceso al dashboard, si no lo es lo reedireccionamos al login para que inicie sesión
    if($_SESSION['loginState'] != true)
    {
        header("Location:../../Login/login.php"); // Reedireccionamos al login
    }
    else
    {

        // Obtenemos la información del usuario que ha iniciado sesión de forma correcta, para determinar si tiene acceso a esta pagina por su rol
        $usuario = $_SESSION['user'];

        // Si el rol del usuario no es administrador se reedirige a la pagina principal
        if($usuario->getRole() != 'admin')
        {
            header("Location: index.php");
        }

        # Generamos los datos necesarios para la gestión del Sistema, esta gestión es solo exclusiva para equipos Linux

        // We get the CPU charge of the Linux System
        $cargaCPU = obtenerCargaCpu();

        // We get the RAM values of the Linux System
        $usedRam = obtenerRam('usada');
        $totalRam = obtenerRam('total');
        $availableRam = $totalRam - $usedRam;                           // Para obtener la ram que queda disponible resto a la total la usada

        // We get the percentage of the RAM values of the Linux System
        $porcentajeRamUsada = calcularPorcentaje($usedRam, $totalRam);
        $porcentajeRamDisponible = calcularPorcentaje($availableRam, $totalRam);

        // We create an instance of the Apache Server to check the status
        $sApache = new Cl_Service('apache2.service', 'Apache 2 Web Server');                        // Servicio web
        $sMySensorsGateway = new Cl_Service('mysgw.service', 'My Sensors Gateway Daemon');          // Gateway de MySensors
        $sController = new Cl_Service('ClienteRaspberry.jar', 'Java Controller Domo-Meteo');        // Nuestro Controller, este es un proceso pero lo tratamos como servicio

        // We create and array with all the created services, only the services not the process
        $services = array();
        $services[] = $sApache;
        $services[] = $sMySensorsGateway;
        
        // Recorremos el Array y comprobamos el estado de los servicios
        foreach ($services as $index => $service) {
            $service->checkServiceStatus();
        }
        
        if(existeProceso($sController->getName()))
        {
            $sController->setStatus('active (running)');
        }
        else
        {
            $sController->setStatus('inactive (dead)');
        }
        
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

    <title>Domo Meteo - Estación Externa</title>

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

    <!-- Ionicons Icon Font Ionic Framework CSS -->
    <link href="../vendor/ionicons/css/ionicons.css" rel="stylesheet">

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
                <!-- <li class="dropdown">
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
                    </ul>

                </li>

                <li class="dropdown">
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

                </li>

                <li class="dropdown">
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
                    <h1 class="page-header">Monitor del Sistema</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!--/.row -->

            <div class="row">
              <! -- KPI CARGA DE LA CPU -->
              <div class="col-lg-4 col-md-6">
                  <div class="panel panel-primary">
                      <div class="panel-heading">
                          <div class="row">
                              <div class="col-xs-3">
                                  <i class="fa fa-server fa-5x"></i>
                              </div>
                              <div class="col-xs-9 text-right">
                                  <?php
                                      echo "<div id='monitorCargaCPU' class='huge'>".$cargaCPU." %</div>";
                                  ?>
                                  <div>Carga actual de la CPU</div>
                                  <div class="help-block"> </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12">
                                  <?php
                                    echo "<div class='progress'>";
                                    echo "<div id='barraCargaCpu' class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$cargaCPU."' aria-valuemin='0' aria-valuemax='100' style='width: ".$cargaCPU."%;'><span class='sr-only'>50% Complete</span></div>";
                                    echo "</div>";
                                   ?>
                           </div>
                          </div>
                      </div>
                  </div>
              </div>
            <! -- KPI CARGA DE LA RAM -->
              <div class="col-lg-4 col-md-6">
                  <div class="panel panel-primary">
                      <div class="panel-heading">
                          <div class="row">
                              <div class="col-xs-3">
                                  <i class="fa fa-tasks fa-5x"></i>
                              </div>
                              <div class="col-xs-9 text-right">
                                  <?php
                                      echo "<div id='monitorRAMUsada' class='huge'>".$usedRam." Mb</div>";
                                  ?>
                                  <div>Memoria RAM usada</div>
                                  <div class="help-block"> </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12">
                                  <?php
                                    echo "<div class='progress'>";
                                    echo "<div id='barraCargaRamUsada' class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$porcentajeRamUsada."' aria-valuemin='0' aria-valuemax='100' style='width:".$porcentajeRamUsada."%;'><span class='sr-only'></span></div>";
                                    echo "</div>";
                                   ?>
                           </div>
                          </div>
                      </div>
                  </div>
              </div>

              <! -- KPI CARGA DE LA RAM -->
              <div class="col-lg-4 col-md-6">
                  <div class="panel panel-primary">
                      <div class="panel-heading">
                          <div class="row">
                              <div class="col-xs-3">
                                  <i class="fa fa-tasks fa-5x"></i>
                              </div>
                              <div class="col-xs-9 text-right">
                                  <?php
                                      echo "<div id='monitorRamAvailable' class='huge'>".$availableRam." Mb</div>";
                                  ?>
                                  <div>Memoria RAM libre</div>
                                  <div class="help-block"> </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-xs-12">
                                  <?php
                                    echo "<div class='progress'>";
                                    echo "<div id='barraCargaRamDisponible' class='progress-bar progress-bar-success' role='progressbar' aria-valuenow='".$porcentajeRamDisponible."' aria-valuemin='0' aria-valuemax='100' style='width:".$porcentajeRamDisponible."%;'><span class='sr-only'></span></div>";
                                    echo "</div>";
                                   ?>
                           </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
            <!--/.row -->
            <div class="row">
                <! -- Tabla Servicios destacados -->
                <div class="col-lg-8 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-hdd-o"></i></i> Estado de los servicios
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="tablaEstadoServicios" class="table">
                                    <thead>
                                        <tr> <th> Servicio </th> <th> Estado </th> </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach ($services as $index => $service) {
                                                if($service->getStatus() == 'active (running)')
                                                {
                                                    echo '<tr> <td>'.$service->getFullName().'</td> <td> <span class="label label-success">'.$service->getStatus().'</span></td> </tr>';
                                                }
                                                else
                                                {
                                                    echo '<tr> <td>'.$service->getFullName().'</td> <td> <span class="label label-danger">'.$service->getStatus().'</span></td> </tr>';                                                    
                                                }
                                            }

                                            if($sController->getStatus() == 'active (running)')
                                                echo '<tr> <td>'.$sController->getFullName().'</td> <td> <span class="label label-success">'.$sController->getStatus().'</span></td> </tr>';
                                            else
                                                echo '<tr> <td>'.$sController->getFullName().'</td> <td> <span class="label label-danger">'.$sController->getStatus().'</span></td> </tr>';      
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
                <!-- /.col-lg-4 -->
            </row>
            <!--/.row -->            
            
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
    <script src="../js/actualizarSistema.js"></script>

</body>

</html>
