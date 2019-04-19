<?php
$varUbicacion = 'securezone';
include_once("../class/class.brain.php");
date_default_timezone_set('America/Mexico_City');
/*
 * SESSIONES
 * BASE DE DATOS: N/A
 * DESCRIPTION: MANEJO GLOBAL ZONA DESMILITARIZADA - Utilizada para funciones o procedimiento donde el usuario no se encuentra logeado
 *
 */
 //error_reporting("E_ALL & ~E_WARNING & ~E_NOTICE");
 try {
 if(!empty($_POST))
 {
	//No usar para variables con contenido importante - * $_POST['var'] 0 $_GET['var']
	extract($_REQUEST);
	
	switch($cmd){
		case "singin":
			$today = date("Y-m-d H:i:s");
			if(trim($_POST["username"]) != "" && trim($_POST["password"]) != "" 
				&& trim($_POST["nombre"]) != "" && trim($_POST["apellido"]) != "" 
				&& trim($_POST["email"]) != "" && trim($_POST["telefono"]) != ""){
			 	$username = strtolower(htmlentities($_POST["username"], ENT_QUOTES));
				$username = Sistemageneral::clean($username);
				$email = strtolower(htmlentities($_POST["email"], ENT_QUOTES));
				$email = Sistemageneral::clean($email);
				$password = strtoupper($_POST["password"]);
				$nombre = $_POST["nombre"];
				$apellido = $_POST["apellido"];
				$telefono = $_POST["telefono"];
				 $database = new db();
				$getusername = $database->getRow("SELECT username FROM usuarios WHERE username = ?  ", array($username ));
				$getemail= $database->getRow("SELECT email FROM usuarios WHERE email= ?  ", array($email ));
				$gettelefono = $database->getRow("SELECT telefono FROM usuarios WHERE telefono = ?  ", array($telefono ));
				if(!$getusername  && !$getemail  && !$gettelefono ){
					$database->beginTransactionDB();
					$newElement=array();
					$newElement[0]=$username;
					$newElement[1]=$password;
					$newElement[2]=$nombre;
					$newElement[3]=$apellido;
					$newElement[4]=$email;
					$newElement[5]=$telefono;
					$newElement[6]=$today;
					$newElement[7]='2';
					$newElement[8]=md5($email.$today);
					$registrarElement=$database->insertRow("INSERT into usuarios(
													username,
													password,
													nombre,
													apellidos,
													email,
													telefono,
													fecha_registro,
													tipoUsuarioId,
													activacion
													) 
													values(?,?,?,?,?,?,?,?,?)",$newElement);
					if($registrarElement==true){
						$getElementLastId=$database->lastIdDB();
						$ConsultarGetElement="SELECT  *
												FROM usuarios                                              
												where usuarioId=?";
						$GetElement=$database->getRow($ConsultarGetElement,array($getElementLastId));
						if($GetElement==true){
							$database->commitDB();
							
							include("../sendemail.php");//Mando a llamar la funcion que se encarga de enviar el correo electronico
							// 1.Inicia sesión en tu cuenta de Google.
							// 2.Accede a la página Configuración de las aplicaciones menos seguras: https://myaccount.google.com/lesssecureapps
							// 3.En la sección “permitir el acceso de aplicaciones menos seguras“, selecciona Activar como en la imagen siguiente:
							/*Configuracion de variables para enviar el correo*/
							$mail_username="aldouli6@gmail.com";//Correo electronico saliente ejemplo: tucorreo@gmail.com
							$mail_userpassword="googlechocolates";//Tu contraseña de gmail
							$mail_addAddress=$GetElement['email'];//correo electronico que recibira el mensaje
							$template="../email_template.html";//Ruta de la plantilla HTML para enviar nuestro mensaje
							
							/*Inicio captura de datos enviados por $_POST para enviar el correo */
							$mail_setFromEmail='aldouli6@outlook.com';
							$mail_setFromName='Turno App';
							$txt_message='Ingresa o copea este link para verificar tu cuenta <br>'.'http://localhost:8888/turno/activacion.php?code='.$GetElement['activacion'];
							$mail_subject='Email de Verificación';
							$respuesta=sendemail($mail_username,$mail_userpassword,$mail_setFromEmail,$mail_setFromName,$mail_addAddress,$txt_message,$mail_subject,$template);//Enviar el mensaje
							if($respuesta=='1'){
								$json['state']='1';
								$json['message']='Su cuenta ha sido satisfactoriamente registrada!!<br>
							favor de verificar su correo';
							}else{
								$json['state']='0';
								$error='No se pudo dar de alta el usuario <br>
									Error: '.$respuesta;
								$json['message']=$error;
							}
							echo json_encode($json);
							
						}else{
							$database->rollBackDB();
							$json['state']='0';
							$error=' Error! No se pudo dar de alta el usuario';
							$json['message']=$error;
							echo json_encode($json);
						}
					}else{
						$database->rollBackDB();
						$json['state']='0';
						$error=' Error! No se pudo registrar el usuario';
						$json['message']=$error;
						echo json_encode($json);
					}
				}else{
					$json['state']='0';
					$error=' Error! Los siguientes datos ya existen: ';
					$error.=($getusername)?'<br>Nombre de usuario ':'';
					$error.=($gettelefono)?'<br>Teléfono':'';
					$error.=($getemail)?'<br>Email ':'';
					$json['message']=$error;
					print_r($getusername);
						print_r($getemail);
						print_r($gettelefono);
					echo json_encode($json);
				}
			// 	if($getrow != false)
			// 	{
			// 		if($getrow['estatus'] == 1)
			// 		{
			// 			if($getrow['password'] == strtoupper($password))
			// 			{							
            //                 Sessions::keySesionCreate($getrow['usuarioId']);

            //                 $_SESSION["UsuarioID"] = $getrow['usuarioId'];
            //                 $_SESSION["UsuarioNombre"] = $getrow['nombre']." ".$getrow['apellidoPaterno'];
            //                 $_SESSION["UsuarioEmail"] = $getrow['email'];
            //                 $_SESSION["EstablecimientoID"]=$getrow['establecimientoId'];
            //                 $_SESSION["EstablecimientoNombre"]=$getrow['estabNombre'];
            //                 Sessions::loadvarsesion($getrow['tipoUsuarioId']);
                            
            //                 echo $getrow['tipoUsuarioId'];
							
			// 			}
			// 			else
			// 			{
			// 				echo 'Password incorrecto';
			// 			}
			// 		}
			// 		else
			// 		{
			// 			echo 'El usuario esta deshabilitado';
			// 		}
			// 	}
			// 	else
			// 	{
			// 		echo 'Datos incorrectos';
			// 	}
			}
			break;
		//---------------------------  Login  ---------------------------
		case "login":

			$today = date("H:i:s");
			if(trim($_POST["username"]) != "" && trim($_POST["password"]) != "")
			{
				$usuario = strtolower(htmlentities($_POST["username"], ENT_QUOTES));
				$usuario = Sistemageneral::clean($usuario);
				$password = $_POST["password"];
				// SQL - Referencia de la consulta para extraer el password y compararlo según la variable del POST $password
				$database = new db();
				
				$getrow = $database->getRow("SELECT u.usuarioId, u.estatus,u.tipoUsuarioId,u.username,u.nombre,u.apellidos,u.password,u.email,u.telefono,u.fecha_registro,
				e.establecimientoId, e.nombre as estabNombre FROM 	usuarios as u LEFT JOIN establecimientos as e on u.establecimientoId = e.establecimientoId WHERE u.username  = ? ", array($usuario));

				if($getrow != false)
				{
					if($getrow['estatus'] == 1)
					{
						if($getrow['password'] == strtoupper($password))
						{							
                            Sessions::keySesionCreate($getrow['usuarioId']);

                            $_SESSION["UsuarioID"] = $getrow['usuarioId'];
                            $_SESSION["UsuarioNombre"] = $getrow['nombre']." ".$getrow['apellidoPaterno'];
                            $_SESSION["UsuarioEmail"] = $getrow['email'];
                            $_SESSION["EstablecimientoID"]=$getrow['establecimientoId'];
                            $_SESSION["EstablecimientoNombre"]=$getrow['estabNombre'];
                            Sessions::loadvarsesion($getrow['tipoUsuarioId']);
                            
                            echo $getrow['tipoUsuarioId'];
							
						}
						else
						{
							echo 'Password incorrecto';
						}
					}
					else
					{
						echo 'El usuario esta deshabilitado';
					}
				}
				else
				{
					echo 'Datos incorrectos';
				}
			}
			break;

			case "login-sa":
				if(trim($_POST["username"]) != "" && trim($_POST["password"]) != "")
				{
						$usuario = strtolower(htmlentities($_POST["username"], ENT_QUOTES));
						$usuario = Sistemageneral::clean($usuario);
						$password = $_POST["password"];
				}
			break;
	}
}
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}
?>
