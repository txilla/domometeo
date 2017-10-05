/* JAVASCRIPT PARA pages/usuarios.php */

var user_global = "";

/**************************** EVENTOS BOTONES *****************************************/
// Función que saltará cuando hagan click al boton activar y activara el usuario en la BD
function activateUser(event)
{
    // obtenemos el elemento name del objeto que ha echo saltar el evento
    var nombre = event.target.name;

    // Actualizar de forma asincrona la Tabla de Temperatura Exterior
    $.ajax({
        url: '../ajax/updateUsers.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'principal':'activarUsuario',
            'user':nombre,
        },
        complete: function activateUser(data) {
            
            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el json retorna falso es que ha habido un error mostramos ese error
            if(json[0]['ok'] == false)
            {
                alert(json[0]['descripcion']);
            }
            if(json[0]['ok'] == true)
            {
                // Eliminamos la fila de la tabla que tiene el atributo id igual que el atributo name del elemento que ha echo saltar el evento click
                $('#'+nombre+'').remove();

                // Actualizamos la tabla de usuarios activados
                update_ActivatedUsersTable();

            }

        }
    });
}

// Función que saltará cuando hagan click al boton eliminar y eliminara el usuario en la BD
function deleteUser(event)
{
    // obtenemos el elemento name del objeto que ha echo saltar el evento y lo ponemos en la variable global user para
    // poderlo pasar por parametro a la función de confirmación
    user_global = event.target.name;

    // Preguntar con un pop up si quiere borrar realmente el usuario
    var jc = $.confirm({
        title: 'Estas seguro de borrar el usuario ' + user_global + '?',
        content: 'Este dialogo se cerrara en 6 segundos si no respondes.',
        autoClose: 'No|8000',
        theme: 'modern',
        //theme: 'boostrap',
        buttons: {
            deleteUser: {
                text: 'Si',
                btnClass: 'btn-red',
                action: function () {
                        $.ajax({
                        url: '../ajax/updateUsers.php',
                        type: 'POST',
                        dataType: 'jsonp',
                        async: true,
                        data:{
                            'principal':'eliminarUsuario',
                            'user':user_global,
                        },
                        complete: function deleteUser(data) {
                            
                            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
                            var json = JSON.parse(data.responseText);

                            // Si el json retorna falso es que ha habido un error mostramos ese error
                            if(json[0]['ok'] == false)
                            {
                                alert(json[0]['descripcion']);
                            }
                            else if(json[0]['ok'] == true)
                            {
                                // Eliminamos la fila de la tabla que tiene el atributo id igual que el atributo name del elemento que ha echo saltar el evento click
                                $('#'+user_global+'').remove();

                                // Actualizamos la tabla de usuarios activados
                                update_ActivatedUsersTable();
                                $.alert('Usuario con nombre ' + user_global + ' eliminado.');    
                                
                                // Reseteamos la variable global
                                user_global = "";                            
                            }
                        }
                    });
                }
            },
            No: function () {
                $.alert('La acción se ha cancelado');

                // Reseteamos la variable global
                user_global = "";
            }
        }
    });
}

// Función que saltara cuando hagan click al botón inhabilitar usuario y inhabilitara al usuario en la BD
function disableUser(event)
{
    // obtenemos el elemento name del objeto que ha echo saltar el evento
    var nombre = event.target.name;

    // Actualizar de forma asincrona la Tabla de Temperatura Exterior
    $.ajax({
        url: '../ajax/updateUsers.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'principal':'desactivarUsuario',
            'user':nombre,
        },
        complete: function disableUser(data) {
            
            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el json retorna falso es que ha habido un error mostramos ese error
            if(json[0]['ok'] == false)
            {
                alert(json[0]['descripcion']);
            }
            else if(json[0]['ok'] == true)
            {
                // Eliminamos la fila de la tabla que tiene el atributo id igual que el atributo name del elemento que ha echo saltar el evento click
                $('#'+nombre+'').remove();

                // Actualizamos la tabla de usuarios inactivos
                update_disableUsersTable();
            }

        }
    });
}

// Evento del boton limpiar formulario que permite limpiar los datos introducido en el formulario
function limpiarFormUsuarios()
{
    $('input[type=text]#username').val("");
    $('input[type=password]#password').val("");
    $('input[type=password]#confirmPassword').val("");   
}

// Evento que saltara cuando clique el boton para crear un usuario
function crearUsuario(event)
{
    var numeroError = -1;
    var cadenaMensaje = "";
    var username = $('input[type=text]#username').val();
    var password = $('input[type=password]#password').val();
    var rPassword = $('input[type=password]#confirmPassword').val();
    var userType = $('#userRole').val();
    var userState = $('#userStatus').val();

    var string = username + "," + password + "," + rPassword + "," + userType + "," + userState;

    // Comprobamos si hay algun error con los datos introducidos, esto retornara un numero de error, si es 0 es todo correcto
    numeroError = comprobarErrores(username, password, rPassword);

    // Si hay una alerta de error por algun dato erroneo anterior se elimina
    $('#errorUser').remove();

    if(numeroError != 0)
    {
        // Obtenemos el valor del error
        cadenaMensaje = obtenerMensaje(numeroError);

        // Mostramos el error
        $('#formUsuarios').after("<div id='errorUser' class='alert alert-danger' role='alert'><strong> Aviso: </strong>" +cadenaMensaje+ "</div>"); 
    }
    else if(numeroError == 0)
    {
        // Se inserta el usuario de forma asincrona, la función se ocupa de mostrar errores
        insertarUsuario(username, password, userType, userState);            
    }
}

/****************************** METODOS Y FUNCIONES *************************************/

// Metodo que inserta un usuario de forma asicrona mediante jQuery+Ajax+PhP
function insertarUsuario(username, password, userType, userState)
{
    // A partir de jQuery creamos una petición Ajax para insertar al usuario
    $.ajax({
        url: '../ajax/insertarUsuario.php',
        type: 'POST',
        dataType: 'jsonp',
        async: false,
        data:{
            'username':username,
            'password':password,
            'role':userType,
            'state':userState
        },
        complete: function comprobarDatos(data) {
            
            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);
            var numeroMensaje = -1;
            var cadenaMensaje = "";
            
            // Recogemos el numero de descripcion que nos ha devuelto el servidor
            numeroMensaje = json[0]['descripcion'];

            // Llamamos a una función que nos mostrara si se ha podido insertar o no el usuario creado
            mostrarErrorInsertarUsuario(numeroMensaje, userState);
        }
    });
}

// Metodo que muestra un el error al insertar el usuario y realiza operaciones de actualización de tablas si todo ha ido bn
function mostrarErrorInsertarUsuario(numeroMensaje, userState)
{
    // Obtenemos el mensaje de error
    var cadenaMensaje = obtenerMensaje(numeroMensaje);
        
    // Si después de insertar el usuario la función retorna 0 es que el usuario se ha insertado de forma correcta
    if(numeroMensaje == 0)
    {
        // Llamamos al metodo para limpiar el formulario si se ha podido insertar el usuario
        limpiarFormUsuarios();

        // Comprobamos que usuario hemos insertado para saber que listado hay que actualizar
        if(userState == 0) 
        {
            update_disableUsersTable();         // Actualizamos la tabla de usuarios deshabilitados
        }
        else
        {
            update_ActivatedUsersTable();       // Actualizamos la tabla de usuarios activos
        }

        // Mostramos el mensaje correspondiente con la cadena de mensaje obtenida anteriormente
        $('#formUsuarios').after("<div id='errorUser' class='alert alert-success' role='alert'><strong> Información: </strong>" +cadenaMensaje+ "</div>");
    }
    else
    {
        // Mostramos el mensaje correspondiente con la cadena de mensaje obtenida anteriormente            
        $('#formUsuarios').after("<div id='errorUser' class='alert alert-danger' role='alert'><strong> Aviso: </strong>" +cadenaMensaje+ "</div>");             
    }
}

// Metodo que nos retorna una cadena a partir de un numero de error pasado por parametro
function obtenerMensaje(numero)
{
    var cadenaMensaje = "";
    
    switch(numero)
    {
        case 0:
            // Todo correcto
            cadenaMensaje = "Perfecto, usuario creado";
            break;
        case 1:
            // Faltan datos obligatorios
            cadenaMensaje = "Oye, faltan datos obligatorios!!";
            break;
        case 2:
            // No puedes dejar vacio el nombre de usuario
            cadenaMensaje = "No, falta el nombre de usuario!!";
            break;
        case 3:
            // El usuario no puede superar los 30 caracteres de longitud
            cadenaMensaje = "Para que lo sepas, el nombre de usuario no puede superar los 30 caracteres de longitud!!";
            break;
        case 4:
            // La contraseña esta vacía
            cadenaMensaje = "Suerte..., falta la contraseña!!";
            break;
        case 5:
            // La contraseña supera el maximo de caracteres
            cadenaMensaje = "No te pases, la contraseña no puede superar los 30 caracteres!!";
            break;
        case 6:
            // Las contraseñas no coinciden
            cadenaMensaje = "Te has equivocado, las contraseñas no coinciden!!";
            break;
        case 7:
            // El usuario ya existe en la base de datos
            cadenaMensaje = "Lo siento, el usuario ya existe en la base de datos"
            break;
        case 8:
            // Error inesperado al insertar el usuario en la base de datos
            cadenaMensaje = "Perdón, error al insertar el usuario en la base de datos";
            break;
        case 9:
            // Error el rol no existe
            cadenaMensaje = "No vayas de listo, el rol especificado no existe";
            break;
        case 10:
            // Error el estado no existe
            cadenaMensaje = "No vayas de listo, el estado especificado no existe";
            break;
    }

    return(cadenaMensaje);
}

// Función que retorna un numero de error, si el numero de error es 0 es que la info es correcta
function comprobarErrores(usuario, password, xpassword)
{
    var numError = 0;

    if(usuario == "" || password == "" || xpassword == "")
        numError = 1;
    else if(usuario.length <= 0)
        numError = 2;
    else if(usuario.length > 30)
        numError = 3;
    else if(password.length <= 0)
        numError = 4;
    else if(password.length > 30)
        numError = 5;
    else if(password != xpassword)
        numError = 6;

    return(numError);
}

// Metodo que actualizara la tabla de usuarios activos
function update_ActivatedUsersTable()
{
     // Actualizar de forma asincrona la Tabla de listado de usuarios activos
    $.ajax({
        url: '../ajax/updateUserTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'principal':'activeUsers'
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla de usuarios activos
            $('#tablaUsuariosActivos > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['ok'] == false)
            {
                alert(json[0]['descripcion']);
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    $('#tablaUsuariosActivos > tbody').append('<tr id='+json[i]['username']+'> <td>' + json[i]['username'] +' </td> <td>'+json[i]['role']+'</td> <td> <button name="' + json[i]['username'] + '" type="button" class="btn btn-primary" onclick="disableUser(event);"> Inhabilitar </button> <button name="' + json[i]['username'] + '" type="button" class="btn btn-danger" onclick="deleteUser(event);"> Eliminar </button> </td> </tr>');
                }
            }
        }
    });
}

// Metodo que actualizara la tabla de usuarios deshabiltados
function update_disableUsersTable()
{
    // Actualizar de forma asincrona la Tabla de listado de usuarios activos
    $.ajax({
        url: '../ajax/updateUserTables.php',
        type: 'POST',
        dataType: 'jsonp',
        async: true,
        data:{
            'principal':'disableUsers'
        },
        complete: function updateTable(data) {

            // Eliminamos todas las filas de la tabla de usuarios activos
            $('#tablaUsuariosDeshabilitados > tbody > tr').remove();

            // Hacemo un JSON.parse de los datos recibidos para transformarlo a JSON en JavaScript y tratarlo
            var json = JSON.parse(data.responseText);

            // Si el JSON es un error lo mostramos por pantalla
            if(json[0]['ok'] == false)
            {
                alert(json[0]['descripcion']);
            }
            else
            {
                // Recorremos el JSON recibido en la petición Ajax y rellenamos la tabla      
                for(i in json)
                {
                    $('#tablaUsuariosDeshabilitados > tbody').append('<tr id='+json[i]['username']+'> <td>' + json[i]['username'] +' </td> <td> <button name="' + json[i]['username'] + '" type="button" class="btn btn-primary" onclick="activateUser(event);"> Activar </button> <button name="' + json[i]['username'] + '" type="button" class="btn btn-danger" onclick="deleteUser(event);"> Eliminar </button> </td> </tr>');
                }
            }
        }
    });
}