<?php

    # We include other important files
    include ("../Clases/user.php");
    include ("../Clases/sqlite.php");

	session_start(); // We open the session

	##### CONTROL DE ACCESO #####
    // Comprobar si hay ya una sessión abierta de un usuario en el navegador para reedireccionarlo a dashboard o si no al login para iniciar sesión
    if($_SESSION['loginState'] == true)
    {
        header("Location:../SB_Admin/pages/index.php");  // Reedireccionamos al dashboard
    }
	
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="EstacionDomoMeteorologica" content="Web para la administración de las estaciones domoticas">
    <meta name="ArantxaRuben" content="">
    <link rel="shortcut icon" href="../../Imagenes/Iconos/icon-home.png" type="image/x-icon">

    <title>Inicio de sesión</title>

    <!-- Bootstrap core CSS -->
    <link href="../Bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../Bootstrap/css/dashboard.css" rel="stylesheet">

    <!-- Login styles for this template, thanks to Bootsnip -->
    <link href="css/login.css" rel="stylesheet" >
  </head>

  <body>
    <div class="container">
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<!-- Esta parte de aqui nos permite saber si hay un mensaje de registro de error poner como link activo registrate o iniciar sesion -->
								<?php if($_SESSION['registrationError'] > 0): ?>
									<a href="" id="login-form-link">Inicia sesión</a>
								<?php else : ?>
									<a href="" class="active" id="login-form-link">Inicia sesión</a>
								<?php endif; ?>	
							</div>
							<div class="col-xs-6">
								<?php if($_SESSION['registrationError'] > 0): ?>
									<a href="" class="active" id="register-form-link">Registrate</a>
								<?php else : ?>	
									<a href="" id="register-form-link">Registrate</a>
								<?php endif; ?>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
							<!-- Esta parte de aqui nos permite saber si hay un mensaje de registro de error para mostrar o no el apartado inicia sesion o registate -->
							<?php if($_SESSION['registrationError'] > 0): ?>
								<form id="login-form" action="php/control_login.php" method="post" role="form" style="display: none;">
							<?php else : ?>
								<form id="login-form" action="php/control_login.php" method="post" role="form" style="display: block;">
							<?php endif; ?>
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Usuario" value="" required>
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Contraseña" required>
									</div>
										<?php if($_SESSION['errorLogin'] == 1) { echo "<div class='alert alert-danger'> <strong>Error: </strong> El usuario no existe </div>"; $_SESSION['errorLogin'] = 0; } ?>
										<?php if($_SESSION['errorLogin'] == 3) { echo "<div class='alert alert-danger'> <strong>Error: </strong> Este usuario esta deshabilitado </div>"; $_SESSION['errorLogin'] = 0; } ?>
										<?php if($_SESSION['errorLogin'] == 2) { echo "<div class='alert alert-danger'> <strong>Error: </strong> Contraseña incorrecta </div>"; $_SESSION['errorLogin'] = 0;} ?>
									<!-- <div class="form-group text-center">
										<input type="checkbox" tabindex="3" class="" name="remember" id="remember">
										<label for="remember"> Recuerdame</label>
									</div> -->
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Entrar">
											</div>
										</div>
									</div>
									<!-- 
									<div class="form-group">
										<div class="row">
											<div class="col-lg-12">
												<div class="text-center">
													<a href="#" tabindex="5" class="forgot-password">No te acuerdas de la contraseña?</a>
												</div>
											</div>
										</div>
									</div> -->
								</form>
								<?php if($_SESSION['registrationError'] > 0): ?>
									<form id="register-form" action="php/control_registro.php" method="post" role="form" style="display: block;">
								<?php else: ?>
									<form id="register-form" action="php/control_registro.php" method="post" role="form" style="display: none;">
								<?php endif; ?>
									<div class="form-group">
										<input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Nombre de usuario" value="" required>
									</div>
									<div class="form-group">
										<input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Contraseña" required>
									</div>
									<div class="form-group">
										<input type="password" name="confirm-password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirmar contraseña" required>
									</div>
									<?php if($_SESSION['registrationError'] == 1) { echo "<div class='alert alert-danger'> <strong>Error: </strong> Las contraseñas no coinciden </div>"; $_SESSION['registrationError'] = 0; } ?>
									<?php if($_SESSION['registrationError'] == 2) { echo "<div class='alert alert-danger'> <strong>Error: </strong> Este nombre de usuario ya existe </div>"; $_SESSION['registrationError'] = 0; } ?>
									<?php if($_SESSION['registrationError'] == 3) { echo "<div class='alert alert-danger'> <strong>Error: </strong> Contacte con el administrador </div>"; $_SESSION['registrationError'] = 0; } ?>									
									<?php if($_SESSION['registrationError'] == 4) { echo "<div class='alert alert-success'> <strong>Success: </strong> Usuario registrado, a la espera de que te habilite un administrador </div>"; $_SESSION['registrationError'] = 0; } ?>									
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Registrarse">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <!-- ---- JAVASCRIPT ---->
    <!-- Ponemos el javascript al final de todo para que la pagina cargue mas rapido -->
    <script type="text/javascript" src="js/extraJQuery.js"></script>
    <script src="js/login.js"></script>
  </body>
</html>