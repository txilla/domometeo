//** JAVASCRIPT FOR THE USER PROFILE PAGE */


// Creamos un evento LOAD con jQuery que saltará cuando se cargue el fichero JS, este evento revisara la hora del cliente y creara un mensaje de bienvenida al usuario
$(document).ready(function() {

    // Obtener hora actual
    var dt = new Date();
    var hora = dt.getHours();

    $('p#bienvenida').text(obtenerMensajePorHora(hora) + $('p#bienvenida').attr('name'));
});

$('#sorpresa').click(function() {

    $('#formRow').hide("slow");
    

});

// Creamos una escucha para cuando se haga click en el botón de cambiar contraseña, lo que hara es mostrar un formulario para cambiar la contraseña
$('a#changePassword').click(function() {
    
    // Obtenemos el nombre de usuario del propio boton en el atributo name
    name = $(this).attr('name');
    
    // Mostramos el formulario para crear usuarios
    $('#formRow').show("slow");
});

// Creamos una escucha para cuando se haga click en el botón de cambiar contraseña, nos cambiara la contraseña de forma asincrona
$('button#btChangePassword').click(function() {

    var username;
    var password;
    var cPassword;
    var numError = -1;

    // Obtenemos el nombre del usuario a partir del mensaje de bienvenida
    username = $('p#bienvenida').attr('name');

    // Obtenemos los datos del formulario
    password = $('#password').val();
    cPassword = $('#confirmPassword').val();

    // Comprobamos que no hayan errores
    numError = buscarErrorCambioContraseña(password, cPassword);
    
    // Si no hay errores pasamos a cambiar la contraseña del usuario en la BD con jQuery + AJAX
    if(numError != 0)
    {
        // Mostramos error
        $.alert(obtenerMensaje(numError));
    }
    else
    {
        // Lanzamos metodo asincrono para cambiar la contraseña, el mismo metodo nos lanzara el mensaje informativo
        changePasswordAsync(username, password)
    }

    // Retornamos falso al click para que no se ejecute el POST
    return false;

});

// Metodo que te limpia los formularios
function limpiarForm()
{
    $('input[type=password]#password').val("");
    $('input[type=password]#confirmPassword').val("");
}

// Se le pasa un usuario y la password y te cambia de forma asincrona la contraseña en la base de datos
function changePasswordAsync(username, password)
{
    $.ajax({
        url: '../ajax/updateUsers.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'principal':'cambiarPassword',
            'user':username,
            'password':password
        },
        complete: function change(data) {
            
            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el json retorna falso es que ha habido un error mostramos ese error
            if(json[0]['ok'] == false)
            {
                $.alert(json[0]['descripcion']);
            }
            else if(json[0]['ok'] == true)
            {
                $.alert(json[0]['descripcion']);
                limpiarForm();
            }
        }
    });
}

// Función que a partir de dos contraseñas nos verifica si no hay errores, sirve para los cambios de contraseña
function buscarErrorCambioContraseña(password, cPassword)
{
    var numError = -1;

    if(password == "" || cPassword == "")
    {
        numError = 1;
    }
    else if(password != cPassword)
    {
        numError = 2;
    }
    else if(password.length > 30)
    {
        numError = 3;
    }
    else
    {
        numError = 0;
    }

    return numError;
}

// Se le pasa una hora y depende de la hora nos devuelve uno u otro mensaje
function obtenerMensajePorHora(hora)
{
    var mensaje = "";

    if(hora >= 0 && hora <= 5)
        mensaje = "Hola, que no duermes ";
    else if(hora >= 6 && hora <= 9)
        mensaje = "Buenos dias, que madrugador ";
    else if(hora >= 10 && hora <= 11)
        mensaje = "Hola bienvenido de nuevo ";
    else if(hora >= 12 && hora <= 16)
        mensaje = "Buenas tardes ";
    else if(hora >= 17 && hora <= 20)
        mensaje = "Que hay de nuevo amigo/a ";
    else if(hora >= 21 && hora <= 23 )
        mensaje = "Buenas noches ";
    else 
        mensaje = "Que hay de nuevo amigo/a ";

    return (mensaje);
}

// Se le pasa un numero y nos retorna un mensaje descriptivo
function obtenerMensaje(numero)
{
    var cadenaMensaje = "";

    switch(numero)
    {
        case 0:
            cadenaMensaje = "Contraseña cambiada de forma correcta";
            break;
        case 1:
            cadenaMensaje = "Faltan datos";
            break;
        case 2:
            cadenaMensaje = "Las contraseñas no coinciden";
            break;
        case 3:
            cadenaMensaje = "La contraseña no puede sobrepasar de 30 caracteres";
            break;
    }

    return cadenaMensaje;
}