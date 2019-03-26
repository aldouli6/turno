//Login
$("#btn_login").click(function(){
	login();
});

$(document).keypress(function(e) {
    if(e.which == 13) {
        login();
    }   
});

//Funcion de login paso de parametros - Cliente MD5
function login(){
	var txtusuario = $("#username").val();
	var txtpassword= $("#password").val();

	//console.log('Username: ' + txtusuario);
	//console.log('Password: ' + txtpassword);

	if(txtusuario === "" || txtpassword === "")
	{
		showNotification("Por favor ingrese su usuario / password","danger");
		//setTimeout('hideTop()',5000);
		return false;
	}else{
		// validausuario();
		txtpassword = calcMD5(txtpassword);
		//quitaré el MD5 para probar
		deliver(txtusuario, txtpassword);
	}
}

function deliver(txtusuario, txtpassword){


	console.log("USUARIO CONTRASEÑA:" + txtusuario + "    " + txtpassword);
	var url_request = "modules/module.zonadesmilitarizada.php";
	var method = "POST";
	$.ajax({
		async: false,
		url:url_request,
		method:method,
		data:{ username : txtusuario, password : txtpassword, cmd : 'login'},
		contentType:"application/x-www-form-urlencoded; charset=UTF-8",
		success: function( response )
		{
			console.log(response);
			if(response == '1' || response == '2' || response == '3' || response == '5')
			{
				if(response == '2'){
					$(location).attr('href','wizard.php');
				} else if (response == '3' || response == '5'){
					$(location).attr('href','alertas.php');
				}else{
					//validausuario();
					$(location).attr('href','usuarios.php');
				}
				//console.log("Usuario con exito");
				// setTimeout('showNotification("Bienvenido","success")',1000);
			}else if(response == 'Horario incorrecto'){
				showNotification("Error! No es tu horario de trabajo","warning");
			}else if(response == 'Datos incorrectos' || response == 'Password incorrecto'){
				showNotification("Usuario y/o contraseña incorrectos","danger");
			} else if(response == 'El usuario esta deshabilitado'){
				showNotification("El usuario esta deshabilitado","danger");
			}
		}
	});
}
//Funcion para inciar sesion con el form
function iSubmitEnter(oEvento, oFormulario){
	 var iAscii;
	 if (oEvento.keyCode)
		 iAscii = oEvento.keyCode;
	 else if (oEvento.which)
		 iAscii = oEvento.which;
	 else
		 return false;
	 if (iAscii == 13) login();
}
//Funcion redirecciona a el sitio web
function validausuario(){
	$(location).attr('href','usuarios.php');
	//setTimeout( "window.location.href='usuarios.php'", 0 );
}
//Alert messages
/*
$('#alertMessage').click(function(){
	hideTop();
});

function hideTop(){
	$('#alertMessage').animate({ opacity: 0,right: '-20'}, 500,function(){ $(this).hide(); });
}
*/

function showNotification(str, type){
	var position = "top-rigth";
	//$('#alertMessage').removeClass('success').addClass('error').html(str).stop(true,true).show().animate({ opacity: 1,right: '10'});
	$('#message').pgNotification({style: 'flip', message: str, position: position, timeout: 0, type: type}).show();
}
