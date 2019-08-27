<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// ini_set("soap.wsdl_cache_enabled", "0");
date_default_timezone_set('America/Mexico_City');
//date_default_timezone_set('UTC');


/*
 * CREATOR: VZERT.COM
 * DEVELOPER: Francisco Javier Salazar González
 * DATE: 16/06/2016
 * TIME: 10:04
 * PROJECT:Aseguradora
 *
 * DESCRIPTION: This file creates a main webservice with multiple tasks, each one does a different action in database.
 *
 */

//call brain class and this one loads libraries that we need

$varUbicacion = 'webservice';
include_once '../class/class.brain.php';

$servicio = new soap_server();
$ws = 'urn:turnowsdl'; 
$servicio->configureWSDL('wsturno', $ws);
$servicio->schemaTargetNamespace = $ws;
$servicio->register('wsmethod', array('case' => 'xsd:string', 'value' => 'xsd:string'), array('return' => 'xsd:string'), $ws);

// wsmethod('setStatusSiniestro','{"SiniestroFolio":"FLDR00086","SiniestroEstatus":1}');
// wsmethod('setTest','{"nombre":"Cesar","telefono":"477 237 1297","password":"tT890q83d"}');
// wsmethod('registroNuevoColono','{"SubfraccionamientoId":1,"CalleId":1,"DireccionNumExt":127,"DireccionNumInt":113,"UsuarioNombre":"César Fabián Arguijo Flores","UsuarioEmail":"cesar.arguijo@vzert.com","UsuarioPassword":"QMcPlCFvXwvuPX/fdsCYEg==","UsuarioTelefono":"477 271 8121"}');

// wsmethod('setTest','{"nombre":"César"}');

function wsmethod($case, $value)
{

//through a web service the app access different cases and return diferent replies storage in the same variable.

$database = new db(); // variable that instances type connection objects.

$wsReply = ''; //This variable stores different replies in different cases.

  switch ($case) {

//General webservices------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
	case 'login':
	    	//Login case: User enters email and password, if those existits or not it's return a request.------------------------------->
		//PLEASE READ LINE AT BELLOW-------------------------------//
		$jsonLoginUserValues = json_decode(utf8_encode($value));
		$userEmail = $jsonLoginUserValues->{'email'};
		$responseLogin = "";
		$consultaUserData = "SELECT * 
                    FROM usuarios
                    WHERE email=?";
		$getUserData = $database->getRow($consultaUserData, array($userEmail));
		if ($getUserData != false){
			if ($getUserData['password'] == $jsonLoginUserValues->{'password'}){
        $responseLogin = array( 'estatus' => $getUserData['estatus'], 
                                'nombreCompleto' => $getUserData['nombre']." ".$getUserData['apellidos'], 
                                'usuarioId' => $getUserData['usuarioId'], 
                                'email' => $getUserData['email'], 
                                'telefono' => $getUserData['telefono']);
			} else {
				$responseLogin = array('estatus' => "0", 'ws_error' => "1" );//Contraseña incorrecta
			}
	  } else {
			$responseLogin = array('estatus' => "0", 'ws_error' => "2" );//Usuario no existe
    }
    $result = json_encode($responseLogin);
    $wsReply = $result;
	break; //End case login---------------------------------------------------->

        case "listaCallesBySubFracc":
            	$jsonGetCallesSubfraccionamiento = json_decode(utf8_encode($value));
            	$getCallesSubfraccionamientoInfo = array();
            	$getCallesSubfraccionamientoInfo[0] = $jsonGetCallesSubfraccionamiento->{'SubfraccionamientoNombre'};
                $consultaListaCallesSubfraccionamiento="SELECT CalleId, CalleNombre FROM calle INNER JOIN subfraccionamiento ON calle.SubfraccionamientoId = subfraccionamiento.SubfraccionamientoId where subfraccionamiento.SubfraccionamientoNombre = ?";
                $getListaCallesSubfraccionamiento=$database->getRows($consultaListaCallesSubfraccionamiento, $getCallesSubfraccionamientoInfo);
                $result = json_encode($getListaCallesSubfraccionamiento);
                //echo $result;
                $wsReply = $result;
        break;

        case "listaCallesT":
              $jsonGetCallesSubfraccionamiento = json_decode(utf8_encode($value));
                $consultaListaCallesSubfraccionamiento="SELECT CalleId, CalleNombre FROM calle INNER JOIN subfraccionamiento ON calle.SubfraccionamientoId = subfraccionamiento.SubfraccionamientoId";
                $getListaCallesSubfraccionamiento=$database->getRows($consultaListaCallesSubfraccionamiento);
                $result = json_encode($getListaCallesSubfraccionamiento);
                //echo $result;
                $wsReply = $result;
        break;

          case "GetSubfracId":
            	$jsonGetCallesSubfraccionamiento = json_decode(utf8_encode($value));
            	$getCallesSubfraccionamientoInfo = array();
            	$getCallesSubfraccionamientoInfo[0] = $jsonGetCallesSubfraccionamiento->{'SubfraccionamientoNombre'};
                $consultaListaCallesSubfraccionamiento="SELECT SubfraccionamientoId FROM subfraccionamiento where SubfraccionamientoNombre = ?";
                $getListaCallesSubfraccionamiento=$database->getRows($consultaListaCallesSubfraccionamiento, $getCallesSubfraccionamientoInfo);
                $result = json_encode($getListaCallesSubfraccionamiento);
                //echo $result;
                $wsReply = $result;
        break;

          case "GetFraccPadre":
              $jsonSubfraccId = json_decode(utf8_encode($value));
              $getSubfraccId = array();
              $getSubfraccId[0] = $jsonSubfraccId->{'SubfraccionamientoId'};
                $consultaSubfraccId="SELECT FraccionamientoId from subfraccionamiento where SubfraccionamientoId= ?";
                $getSubfraccId=$database->getRow($consultaSubfraccId, $getSubfraccId);
                $result = json_encode($getSubfraccId);
                //echo $result;
                $wsReply = $result;
        break;

         case "GetCalleId":
            	$jsonGetCallesSubfraccionamiento = json_decode(utf8_encode($value));
            	$getCallesSubfraccionamientoInfo = array();
            	$getCallesSubfraccionamientoInfo[0] = $jsonGetCallesSubfraccionamiento->{'calleNombre'};
                $consultaListaCallesSubfraccionamiento="SELECT CalleId FROM calle where CalleNombre = ?";
                $getListaCallesSubfraccionamiento=$database->getRows($consultaListaCallesSubfraccionamiento, $getCallesSubfraccionamientoInfo);
                $result = json_encode($getListaCallesSubfraccionamiento);
                //echo $result;
                $wsReply = $result;
        break;

        case 'resetEmail':
              $jsonEmailUsuario = json_decode($value);
              $jsonEmailReturn = null;
              $replyEmailData = null;
              $_email = $jsonEmailUsuario->{'nickname'};

              $consultaEmailUsuario = 'SELECT usuario.UsuarioNickName FROM usuario WHERE usuario.UsuarioNickName=?';

              $getReplyUser = $database->getRow($consultaEmailUsuario, array($_email));

              if ($getReplyUser != false)
              {
                $_replyUserData = array('replyUser' => '1'); //Status cero
                $_jsonReturnUserValues = json_encode($_replyUserData);
                $wsReply = $_jsonReturnUserValues;

                //$_email = $_POST['remail'];
              }
              else
              {
                $_replyUserData = array('replyUser' => '4'); //Status cero
                $_jsonReturnUserValues = json_encode($_replyUserData);
                $wsReply = $_jsonReturnUserValues;
              }

        break;
                case "getDataforTurno":
                  $jsonGet = json_decode($value);
                    $consulta="
                    SELECT t.tipoSesionId, t.nombre, TIME_FORMAT( ADDTIME(ADDTIME( ADDTIME('".$jsonGet->{"horainicio"}."',  t.duracion) ,t.tiempoEntreSesion), t.tiempoEspera), '%H:%i') as horafin, e.nombre as enombre, e.stepping
                    FROM tiposSesiones as t 
                    INNER JOIN establecimientos as e on e.establecimientoId=t.establecimientoId
                    WHERE 1
                    AND t.tipoSesionId=".$jsonGet->{"sesion"}."";
                    $get=$database->getRow($consulta);
                    $result = json_encode($get);
                    $wsReply = $result;
                break;
                case "getTurnosHoy":
                  $jsonGet = json_decode($value);
                    $consulta="
                      SELECT * 
                      FROM turnos
                      WHERE 1
                      AND estatusId<> 4
                      AND fecha='".$jsonGet->{"fecha"}."'
                      AND recursoId in (".$jsonGet->{"recursos"}.")
                      AND establecimientoId=".$jsonGet->{"estab"}."";
                  $get=$database->getRows($consulta);
                  $result = json_encode($get);
                  $wsReply = $result;
                break;
                case "getRecursosDisponibles":
                  $jsonGet = json_decode($value);
                  $consulta='
                  SELECT e.stepping, rrt.tipoSesionId,
                    r.recursoId, r.nombre as rnombre,  h.diasLaborables,
                    h.horaInicio, h.horaFin, 	
                    ((HOUR(t.tiempoEspera)+HOUR(t.tiempoEntreSesion)+HOUR(t.duracion)) * 60) + 
                    (MINUTE(t.tiempoEspera)+MINUTE(t.tiempoEntreSesion)+MINUTE(t.duracion))  as minutosTotales,
                    t.nombre snombre, t.duracion, t.tiempoEspera, t.tiempoEntreSesion, t.costo
                    FROM establecimientos as e 
                    INNER JOIN recursos as r on r.establecimientoId=e.establecimientoId
                    INNER JOIN horariosRecursos as h on h.recursoId=r.recursoId
                    INNER JOIN relacionesRecursoTipoSesion as rrt on rrt.recursoId=r.recursoId
                    INNER JOIN tiposSesiones as t on t.tipoSesionId=rrt.tipoSesionId
                    WHERE 1 
                    and e.establecimientoId = '.$jsonGet->{'estab'}.'
                    and t.tipoSesionId='.$jsonGet->{'tiposesion'}.'
                    AND JSON_CONTAINS(h.diasLaborables, \'["'.$jsonGet->{'dayofweek'}.'"]\')
                    ORDER BY r.nombre';
                  $get=$database->getRows($consulta);
                  $result = json_encode($get);
                  $wsReply = $result;
                break;
                case "getSesionesDia":
                  $jsonGetTipoSesion = json_decode($value);
                  
                  $consultaTipoSesion='
                  SELECT rrt.recursoId, t.tipoSesionId, t.nombre, t.fechaFin,h.diasLaborables, h.horaInicio, h.horaFin,
                  DATE_ADD( DATE_ADD( DATE_ADD(now(), INTERVAL t.limiteAntesAgendarDias DAY), INTERVAL t.limiteAntesAgendarHoras HOUR), INTERVAL t.limiteAntesAgendarMins MINUTE ) as limiteAntesAgendarFull, DATE_ADD(CURRENT_DATE(), INTERVAL t.limiteAntesAgendarDias DAY ) as limiteAntesAgendar,
                  DATE_ADD( DATE_ADD( DATE_ADD(now(), INTERVAL t.maximoAgendarDias DAY), INTERVAL t.maximoAgendarHoras HOUR), INTERVAL t.maximoAgendarMins MINUTE ) as maximoAgendarFull, DATE_ADD(CURRENT_DATE(), INTERVAL t.maximoAgendarDias DAY ) as maximoAgendar
                  FROM tiposSesiones as t
                  INNER JOIN relacionesRecursoTipoSesion as rrt on rrt.tipoSesionId=t.tipoSesionId
                  INNER JOIN horariosRecursos as h on rrt.recursoId = h.recursoId
                  WHERE 1 
                  AND t.establecimientoId = '.$jsonGetTipoSesion->{'estab'}.'
                  AND (t.fechaFin >="'.$jsonGetTipoSesion->{'dia'}.'" OR t.fechaFin is null)
                  AND JSON_CONTAINS(h.diasLaborables, \'["'.$jsonGetTipoSesion->{'dayofweek'}.'"]\') 
                  HAVING 1 
                  AND limiteAntesAgendar <= "'.$jsonGetTipoSesion->{'dia'}.'"
                  AND (maximoAgendar=CURRENT_DATE() or maximoAgendar >="'.$jsonGetTipoSesion->{'dia'}.'")';
                  $getTipoSesion=$database->getRows($consultaTipoSesion);
                  $result = json_encode($getTipoSesion);
                  $wsReply = $result;
                break;
                case "getEstablecimiento":
                  $jsonGetEstab = json_decode($value);
            	    $getEstab = array();
            	    $getEstab[0] = $jsonGetEstab->{'id'};
                  $consultaEstablecimiento="SELECT * FROM establecimientos where establecimientoId = ?";
                  $getEstablecimiento=$database->getRow($consultaEstablecimiento, $getEstab);
                  $result = json_encode($getEstablecimiento);
                  $wsReply = $result;
                break;
                case "getEstablecimientos":
                  $jsonGetEstab = json_decode($value);
            	    $getEstab = array();
            	    $getEstab[0] = $jsonGetEstab->{'categoria'};
                  $consultaEstablecimientos="SELECT * FROM establecimientos where subcategoriaId = ?";
                  $getEstablecimientos=$database->getRows($consultaEstablecimientos, $getEstab);
                  $result = json_encode($getEstablecimientos);
                  $wsReply = $result;
                break;

                case "getCategorias":
            	    $jsonGetCategorias = json_decode($value);
            	    $getCateInfo = array();
            	    $getCateInfo[0] = $jsonGetCategorias->{'categoria'};
                  $consultacategorias="SELECT  categoriaId, nombre FROM categorias where categoriaPadre=? ORDER BY nombre";
                  $getListaCategorias=$database->getRows($consultacategorias, $getCateInfo);
                  $result = json_encode($getListaCategorias);
                  //echo $result;
                  $wsReply = $result;
                break;
                case "setTurno":
                  $jsonSet = json_decode(utf8_encode($value));
                  $dataInfo = array();
                  
                  $data = "";
                  $datainfo = array();
                  $datainfo[0] = $jsonSet->{'establecimientoId'};
                  $datainfo[1] = $jsonSet->{'usuarioId'};
                  $datainfo[2] = $jsonSet->{'recursoId'};
                  $datainfo[3] = $jsonSet->{'tipoSesionId'};
                  $datainfo[4] = $jsonSet->{'fecha'};
                  $datainfo[5] = $jsonSet->{'horainicio'};
                  $datainfo[6] = $jsonSet->{'horafin'};
                  $datainfo[7] = '8';

                  $consultaInserta = "INSERT INTO turnos (establecimientoId, usuarioId, 
                    recursoId, tipoSesionId, fecha, horainicio, horafin, estatusId) VALUES (?,?,?,?,?,?,?,?)";
                  $set = $database->insertRow($consultaInserta, $datainfo);
                  if ($set == true){
                    $getLastId = $database->lastIdDB();
                    $consultaGetlastId = "SELECT *
                                            FROM turnos
                                            WHERE turnoId = ?";
                    $getId = $database->getRow($consultaGetlastId, array($getLastId));
                    if($getId == true){
                        $notiinfo = array();
                        $notiinfo[0] = $jsonSet->{'establecimientoId'};
                        $notiinfo[1] = '5';
                        $notiinfo[2] = date('Y-m-d h:i:s');
                        $notiinfo[3] = $getLastId;
                        $consultaInsertaNoti = "INSERT INTO notificaciones (establecimientoId, estatus, 
                          fecha_hora, turnoId) VALUES (?,?,?,?)";
                        $setnoti = $database->insertRow($consultaInsertaNoti, $notiinfo);
                        if ($setnoti == true){
                          $getLastIdNoti = $database->lastIdDB();
                          $consultaGetlastIdnoti = "SELECT * FROM `notificaciones`
                                                  WHERE notificacionId = ?";
                          $getIdnoti = $database->getRow($consultaGetlastIdnoti, array($getLastIdNoti));
                          
                          if($getIdnoti == true){
                            $data = array('turnoId' => $getLastId,
                              'notiId' => $getLastIdNoti, 
                              'ws_error' => '0');
                          }else{
                            $data = array('turnoId' => '0', 'ws_error' => '1','error' => 'no creo la notificacion');
                            //$data=print_r($consultaGetlastIdnoti ,true);
                          }

                        }else{
                          $data = array('turnoId' => '0', 'ws_error' => '2','error' => 'no creo el Id');
                        }
                        
                    } else{
                        $data = array('turnoId' => '0', 'ws_error' => '4');
                    }
                  } else {
                    $data = array('turnoId' => '0', 'ws_error' => '3');
                  }
                  $result = json_encode($data);

                  $wsReply = $result;

                break;
                case "registroCliente":
                  $jsonSetCliente = json_decode(utf8_encode($value));
                  $clienteInfo = array();
                  
                  $cliente = "";
                  $userinfo = array();
                  $userinfo[0] = $jsonSetCliente->{'email'};
                  $userinfo[1] = $jsonSetCliente->{'username'};
                  $consultaGetUsuario = "SELECT usuarioId FROM usuarios WHERE email=? or username=?";
                  $getCliente = $database->getRows($consultaGetUsuario, $userinfo);
                  if($getCliente == false){
                    $clienteInfo[0] = 0;
                    $clienteInfo[1] = 0;
                    $clienteInfo[2] = 4;
                    $clienteInfo[3] = $jsonSetCliente->{'username'};
                    $clienteInfo[4] = $jsonSetCliente->{'nombre'};
                    $clienteInfo[5] = $jsonSetCliente->{'apellidos'};
                    $clienteInfo[6] = $jsonSetCliente->{'password'};
                    $clienteInfo[7] = $jsonSetCliente->{'email'};
                    $clienteInfo[8] = $jsonSetCliente->{'telefono'};

                    $consultaInsertaCliente = "INSERT INTO usuarios (estatus, establecimientoId, tipousuarioId, 
                      username, nombre, apellidos, password, email, telefono, fecha_registro) VALUES (?,?,?,?,?,?,?,?,?,now())";
                    $setCliente = $database->insertRow($consultaInsertaCliente, $clienteInfo);
                    if ($setCliente == true){
                      $getUsuarioLastId = $database->lastIdDB();
                      $consultaGetSubfraccionamietoId = "SELECT *
                                              FROM usuarios
                                              WHERE usuarioId = ?";
                      $getUsuarioId = $database->getRow($consultaGetSubfraccionamietoId, array($getUsuarioLastId));
                      if($getUsuarioId == true){
                          $cliente = array('usuarioId' => $getUsuarioLastId, 'ws_error' => '0');
                      } else{
                          $cliente = array('usuarioId' => '0', 'ws_error' => '4');
                      }
                    } else {
                      $cliente = array('usuarioId' => '0', 'ws_error' => '3');
                    }
                  } else {
                    $cliente = array('usuarioId' => '0', 'ws_error' => '2');
                  }
                  $result = json_encode($cliente);

                  $wsReply = $result;

                break;

                case "setTest":

                     $jsonSetTest = json_decode(utf8_encode($value));

                      // var_dump($jsonSetTest);

                      $testArray = array();
                
                      $testArray[0] = $jsonSetTest->{'nombre'};
                       $testArray[1] = $jsonSetTest->{'telefono'};
                        $testArray[2] = $jsonSetTest->{'password'};

                      $consultaInsertaColono = "INSERT INTO test (nombre, telefono, password) VALUES (?,?,?)";
                      
                      $setColono = $database->insertRow($consultaInsertaColono, $colonoInfo);

                      // var_dump($testArray);

                      $result = json_encode($testArray);
                      
                      $wsReply = $result;                     
                break;

          	case 'uploadToken':
          		$jsonSetToken = json_decode(utf8_encode($value));
          		$response = "";
          		$consultaSetToken = "UPDATE usuarioColono SET UsuarioToken='".$jsonSetToken->{'newToken'}."' WHERE UsuarioId = ?";
          		$setToken = $database->updateRow($consultaSetToken, array($jsonSetToken->{'colonoId'}));
          		if ($setToken != false){
          			$response = array('ws_response' => '1');
          		} else {
          			$response = array('ws_response' => "2");
          		}
          		$wsReply = json_encode($response);
          	break;

          	case 'getColonoData':
          		$jsonGetColono = json_decode(utf8_encode($value));
          		//nombre completo, correo, telefono, direccion
          		$colono = "";
          		$consultaGetColono = "SELECT  usuarioColono.UsuarioNombreCompleto, usuarioColono.UsuarioEmail, usuarioColono.UsuarioTelefono, direccion.DireccionNumExt, direccion.DireccionNumInt, calle.CalleNombre
          				FROM usuarioColono
          				INNER JOIN direccion on usuarioColono.DireccionId = direccion.DireccionId
          				INNER JOIN calle on direccion.CalleId = calle.CalleId
          				WHERE usuarioColono.UsuarioId = ?";
          		$getColono = $database->getRow($consultaGetColono, array($jsonGetColono->{'usuarioId'}));
          		if($getColono != false){
          			$direccion = $getColono['CalleNombre']." #".$getColono['DireccionNumExt'];
          			if ($getColono['DireccionNumInt'] != ""){
          				$direccion .= " Int. ".$getColono['DireccionNumInt'];
          			}
          			$colono = array('Nombre' => $getColono['UsuarioNombreCompleto'], 'Correo' => $getColono['UsuarioEmail'], 'Direccion' => $direccion, 'Telefono' => $getColono['UsuarioTelefono']);
          		}
          		$wsReply = json_encode($colono);
          	break;


              case 'changePass':
              
                $jsonSetPass = json_decode(utf8_encode($value));

                $response = "";                
            
          		$setPassInfo = array();
                $consultaGetColonoPass = "SELECT UsuarioPassword FROM usuarioColono WHERE UsuarioId = ?";              
                $getColonoPass = $database->getRow($consultaGetColonoPass, array($jsonSetPass->{'usuarioId'}));
                
                if($getColonoPass == true)
                {                    
                     if($getColonoPass['UsuarioPassword'] == $jsonSetPass->{'usuarioPassOld'})
                     {
                        //$wsReply = json_encode('si concide la el password nuevo con el viejo');
                        $setPassInfo[0] = $jsonSetPass->{'usuarioPassNew'};
                        $setPassInfo[1] = $jsonSetPass->{'usuarioId'};
                        
                        $consultaSetPass = "UPDATE usuarioColono SET UsuarioPassword = ? WHERE UsuarioId = ?";
                        $setPass = $database->updateRow($consultaSetPass, $setPassInfo);
                        
                        if($setPass == true)
                        {
                            $response = array('ws_response' => '1');//Se ha cambiado la contraseña exitosamente
                        }
                        else
                        {
                            $response = array('ws_response' => '2');//Error al actualizar el campo
                        }

                     }
                     else
                     {
                        $response = array('ws_response' => '3');//Las contraseñas no coinciden
                     }
                }
                else
                {
                    $response = array('ws_response' => '4');//Error al buscar el usuario
                }

                $wsReply = json_encode($response);

          		// if(getColonoPass != false){
          		// 	if($getColonoPass['UsuarioPassword'] == $jsonSetPass->{'usuarioPassOld'}){
          		// 		$setPassInfo[0] = $jsonSetPass->{'usuarioPassNew'};
		        //   		$setPassInfo[1] = $jsonSetPass->{'usuarioId'};
		        //   		$consultaSetPass = "UPDATE usuarioColono SET UsuarioPassword = ? WHERE UsuarioId = ?";
		        //   		$setPass = $database->updateRow($consultaSetPass, $setPassInfo);
		        //   		if($setPass != false){
		        //   			$response = array('ws_response' => '1');//Se ha cambiado la contraseña exitosamente
		        //   		} else{
		        //   			$response = array('ws_response' => '2');//Error al actualizar el campo
		        //   		}
          		// 	} else{
          		// 		$response = array('ws_response' => '3');//Las contraseñas no coinciden
          		// 	}
          		// } else {
          		// 	$response = array('ws_response' => '4');//Error al buscar el usuario
          		// }                                                    

                // $wsReply = json_encode(($jsonSetPass));

          	break;

              case 'altaAlerta':
                    $jsonSetAlerta = json_decode(utf8_encode($value));
                    $response = "";
                    $setAlertaInfo = array();
                    $setAlertaInfo[0] = 1;
                    $setAlertaInfo[1] = $jsonSetAlerta->{'tiporeporteId'};
                    $setAlertaInfo[2] = $jsonSetAlerta->{'latitud'};
                    $setAlertaInfo[3] = $jsonSetAlerta->{'longitud'};
                    $setAlertaInfo[4] = $jsonSetAlerta->{'fechahora'};
                    $setAlertaInfo[5] = $jsonSetAlerta->{'comentario'};
                    $setAlertaInfo[6] = $jsonSetAlerta->{'subfraccionamientoId'};
                    $setAlertaInfo[7] = $jsonSetAlerta->{'usuarioId'};
                    $setAlertaInfo[8] = $jsonSetAlerta->{'ReporteDireccionDetalle'};                    
                    $setAlertaInfo[9] = $jsonSetAlerta->{'ReporteIsIn'};
                    $setAlertaInfo[10] = "";
                    
                    $consultaSetAlerta = "INSERT reporte (EstatusId, TiporeporteId, ReporteLatitud, ReporteLongitud, ReporteRegistro, ReporteComentario, SubfraccionamientoId, UsuarioId, ReporteDireccionDetalle, ReporteIsIn, ComentarioFinal) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
                    $setAlerta = $database->insertRow($consultaSetAlerta, $setAlertaInfo);
                    
                    if($setAlerta == true)
                    {
                        $idReporte = $database->lastIdDB();
                        $consultaGetReporte = "SELECT reporte.ReporteId, reporte.UsuarioId, reporte.TiporeporteId, reporte.ReporteLatitud, reporte.ReporteLongitud, reporte.ReporteRegistro, reporte.ReporteComentario, reporte.EstatusId, reporte.SubfraccionamientoId, reporte.ReporteDireccionDetalle, reporte.ComentarioFinal, reporte.ReporteIsIn, usuarioColono.UsuarioNombreCompleto, estatus.EstatusDescripcion, subfraccionamiento.SubfraccionamientoNombre, tiporeporte.TiporeporteDescripcion
                                  FROM reporte
                                  INNER JOIN usuarioColono ON reporte.UsuarioId = usuarioColono.UsuarioId
                                  INNER JOIN estatus ON reporte.EstatusId = estatus.EstatusId
                                  INNER JOIN subfraccionamiento ON reporte.SubfraccionamientoId = subfraccionamiento.SubfraccionamientoId
                                  INNER JOIN tiporeporte ON reporte.TiporeporteId = tiporeporte.TiporeporteId 
                                  WHERE reporte.ReporteId = ?";
                        $getReporte = $database->getRow($consultaGetReporte, array($idReporte));
                        
                        if($getReporte != false)
                        {                           
                            $consultaSetReporteHistorico = "INSERT reportesHistorico (ReporteId, UsuarioId, UsuarioNombreCompleto, TiporeporteId, TiporeporteDescripcion, ReporteLatitud, ReporteLongitud, ReporteRegistro, ReporteComentario, EstatusId, EstatusDescripcion, SubfraccionamientoId, SubfraccionamientoNombre, ReporteIsIn, ReporteDireccionDetalle, ComentarioFinal) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
                            $dataReporteHistorico = array();
                            $dataReporteHistorico[0] = $getReporte['ReporteId'];
                            $dataReporteHistorico[1] = $getReporte['UsuarioId'];
                            $dataReporteHistorico[2] = $getReporte['UsuarioNombreCompleto'];
                            $dataReporteHistorico[3] = $getReporte['TiporeporteId'];
                            $dataReporteHistorico[4] = $getReporte['TiporeporteDescripcion'];
                            $dataReporteHistorico[5] = $getReporte['ReporteLatitud'];
                            $dataReporteHistorico[6] = $getReporte['ReporteLongitud'];
                            $dataReporteHistorico[7] = $getReporte['ReporteRegistro'];
                            $dataReporteHistorico[8] = $getReporte['ReporteComentario'];
                            $dataReporteHistorico[9] = $getReporte['EstatusId'];
                            $dataReporteHistorico[10] = $getReporte['EstatusDescripcion'];
                            $dataReporteHistorico[11] = $getReporte['SubfraccionamientoId'];
                            $dataReporteHistorico[12] = $getReporte['SubfraccionamientoNombre'];
                            $dataReporteHistorico[13] = $getReporte['ReporteIsIn'];
                            $dataReporteHistorico[14] = $getReporte['ReporteDireccionDetalle'];
                            $dataReporteHistorico[15] = $getReporte['ComentarioFinal'];
                            $setReporteHistorico = $database->insertRow($consultaSetReporteHistorico,$dataReporteHistorico);

                            $response = array('EstatusId' => $getReporte['EstatusId'], 'TiporeporteId' => $getReporte['TiporeporteId'], 'ReporteId' => $getReporte['ReporteId'], 'ReporteRegistro' => $getReporte['ReporteRegistro']);
                            
                            //$response = array("Todo bien" =>'1');
                        }
                        else 
                        {
                            $response = array('ReporteId' => '0', 'ws_error' => '1');
                        }
                    }
                    else 
                    {
                        $response = array('ReporteId' => '0', 'ws_error' => '2');
                    }
                    $wsReply = json_encode($response);
              break;

              case 'listaTipoReporte':
                    $consultaGetTipoReportes = "SELECT TiporeporteId, TiporeporteDescripcion FROM tiporeporte";
                    $getTipoReportes = $database->getRows($consultaGetTipoReportes);
                    $wsReply = json_encode($getTipoReportes);
              break;

              case 'getMyReports':
                    $jsonGetMyReports = json_decode(utf8_encode($value));
                    $colonoId = $jsonGetMyReports->{'colonoId'};
                    $consultaGetMyReports = "SELECT reporte.ReporteId, reporte.EstatusId, reporte.ReporteRegistro, reporte.ReporteDireccionDetalle, tiporeporte.TiporeporteDescripcion
                              FROM reporte
                              INNER JOIN tiporeporte ON reporte.TiporeporteId = tiporeporte.TiporeporteId
                              WHERE reporte.UsuarioId = ?
                              ORDER BY reporte.ReporteRegistro DESC
                              LIMIT 0,10";
                    $getMyReports = $database->getRows($consultaGetMyReports, array($colonoId));
                    $wsReply = json_encode($getMyReports);
              break;
              case 'getHistorico': //task=getHistorico&uid=12&tiporeporte=2&fechaInicio=2018-11-01&fechaFin=2018-11-30
                    $jsonGetHistorico= json_decode($value);
                    $paramHist = array();
                    $paramHist[0] = $jsonGetHistorico->{'uid'};
                    $paramHist[1] = $jsonGetHistorico->{'fechaInicio'};
                    $paramHist[2] = $jsonGetHistorico->{'fechaFin'}; 
                    $paramHist[3] = $jsonGetHistorico->{'tipoRep'}; 
                    
                    $sqlextra = "";
                    if($paramHist[3]!=0){
                      
                      $sqlextra = " AND reporte.TiporeporteId = ? ";
                    }else{

                      $sqlextra = " AND reporte.TiporeporteId <> ? ";
                    }
                    
                    $consultaGetHistorico = "SELECT reporte.ReporteId, reporte.EstatusId, reporte.ReporteRegistro,reporte.ReporteDireccionDetalle, tiporeporte.TiporeporteDescripcion, reporte.ReporteComentario, reporte.ComentarioFinal, reporte.ReporteLatitud, reporte.ReporteLongitud   
                    FROM reportesHistorico reporte 
                    INNER JOIN tiporeporte ON reporte.TiporeporteId = tiporeporte.TiporeporteId 
                    WHERE reporte.UsuarioId = ? 
                    AND reporte.EstatusId in (5,6) 
                    AND str_to_date(reporte.ReporteRegistro,'%m/%d/%Y')  between ? and ?
                    ".$sqlextra."
                    ORDER BY reporte.ReporteRegistro 
                    DESC LIMIT 0,10;";
                    $getHistorico = $database->getRows($consultaGetHistorico, $paramHist);
                    $wsReply = json_encode($getHistorico);
                    // $wsReply = 'errores';
                    // 
              break;
              
              case 'recoverPass':
                    $response = "";
                    $jsonRecoverPass = json_decode(utf8_encode($value));
                    $colonoEmail = $jsonRecoverPass->{'colonoEmail'};
                    $consultaGetUser="SELECT UsuarioNombreCompleto, UsuarioEmail FROM usuarioColono WHERE UsuarioEmail=?";
                    $getUser = $database->getRow($consultaGetUser, array($colonoEmail));
                    if ($getUser == "") {
                        $response = array('ws_error'=>'1');
                    } else{
                        $name=$getUser['UsuarioNombreCompleto'];
                        $mail=$getUser['UsuarioEmail'];
                        $password = substr(sha1(uniqid(rand(),1)),3,8);
                        $pass = strtoupper(sha1($password));
                        $consultaUpdatePass="UPDATE usuarioColono set UsuarioHash=? WHERE UsuarioEmail=?";
                        $updatePass = $database->updateRow($consultaUpdatePass, array($pass, $mail));
                        if ($updatePass != false){
                            $urlrecover = "http://gardeapp.com/cp_colono.php?cpk=".$pass;
                            $to = $mail;
                            $subject = 'Recuperacion de contraseña';
                            $message = "Hola ". $name .",\n\nAlguien solicitó restablecer la contraseña de la cuenta de Garde ligada a este correo.\nSi tú realizaste esta petición, ingresa a la siguiente dirección para restablecer tu contraseña:\n".$urlrecover."\nSi no reconoces esta acción, te recomendamos hacer caso omiso de este correo.\n\nSaludos!";
                            $headers = 'From: rnd@nextdata.com.mx' . "\r\n" .
                                'Reply-To: rnd@nextdata.com.mx' . "\r\n" .
                                'X-Mailer: PHP/' . phpversion();
                            if (mail($to, $subject, $message, $headers)){
                                $response = array('ws_error'=>'0');
                            } else{
                                $response = array('ws_error'=>'2');
                            }
                        } else{
                            $response = array('ws_error'=>'3');
                        }
                    }
                    $wsReply = json_encode($response);
              break;

              case 'getReporteDetail':
                    $reporteData = "";
                    $jsonGetReportDetail = json_decode(utf8_encode($value));
                    $idReporte = $jsonGetReportDetail->{'reporteId'};
                    $consultaGetReportDetail = "SELECT reporte.ReporteId, reporte.ReporteRegistro, reporte.ReporteComentario, reporte.ReporteDireccionDetalle, reporte.ComentarioFinal, tiporeporte.TiporeporteDescripcion, estatus.EstatusDescripcion
                                FROM reporte
                                INNER JOIN tiporeporte ON reporte.TiporeporteId = tiporeporte.TiporeporteId
                                INNER JOIN estatus ON reporte.EstatusId = estatus.EstatusId
                                WHERE reporte.ReporteId = ?";
                    $getReportDetail = $database->getRow($consultaGetReportDetail, array($idReporte));
                    if($getReportDetail != false){
                        $reporteData = array('ws_error'=>'0', 'ReporteId'=>$getReportDetail['ReporteId'], 'ReporteRegistro'=>$getReportDetail['ReporteRegistro'], 'ReporteDireccionDetalle'=>$getReportDetail['ReporteDireccionDetalle'], 'RazonCancelado'=>$getReportDetail['ComentarioFinal'], 'TiporeporteDescripcion'=>$getReportDetail['TiporeporteDescripcion'], 'EstatusDescripcion'=>$getReportDetail['EstatusDescripcion'], 'ReporteComentario'=>$getReportDetail['ReporteComentario']);
                    } else {
                        $reporteData = array('ws_error'=>'1');
                    }
                    $wsReply = json_encode($reporteData);
              break;

              case 'uploadDevice':
                $jsonSetToken = json_decode(utf8_encode($value));
                $response = "";
                $consultaSetToken = "UPDATE usuarioColono SET UsuarioDevice='".$jsonSetToken->{'UsuarioDevice'}."' WHERE UsuarioId = ?";
                $setToken = $database->updateRow($consultaSetToken, array($jsonSetToken->{'colonoId'}));
                if ($setToken != false){
                  $response = array('ws_response' => '1');
                } else {
                  $response = array('ws_response' => "0");
                }
                $wsReply = json_encode($response);
              break;


              case 'holamundo':

              
                 $wsReply = json_encode(utf8_encode('response received from webservice'));
                  # code...
              break;


              case "insert_stop":
                  $reply = '';
                  $inser_tet = 'insert into prueba(col1,col2) values("76234","857232")';
      
                  $reg_stop = $database->insertRow($inser_tet, array(""));
      
                  if($reg_stop == true){
                      $reply = array("Todo bien" =>'1');
      
                  }
                  
                  $wsReply = json_encode($reply);
              break;

              

    default:
        // code...
        break;

  }//End webservice switch

  return $wsReply; //Return case value
}

/*$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$servicio->service($HTTP_RAW_POST_DATA);*/

$HTTP_RAW_POST_DATA = file_get_contents('php://input');
$servicio->service($HTTP_RAW_POST_DATA);