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
	
	switch($cmd)
	{
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
