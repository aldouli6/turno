<?php
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');
	include_once('../lib/nusoap/nusoap.php');
	require __DIR__.'/vendor/autoload.php';
	use Kreait\Firebase\Factory;
	use Kreait\Firebase\ServiceAccount;
    $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/secret/turno-1554146989867-17eb7d2a4084.json');
	date_default_timezone_set('America/Mexico_City');
	$firebase = (new Factory)
		->withServiceAccount($serviceAccount)
		->create();

	$database = $firebase->getDatabase();    
	$c = new nusoap_client('http://localhost:8888/turno/ws/wsturno.php');
	$c->soap_defencoding = 'UTF-8';
	$c->decode_utf8 = FALSE;
	$error = $c->getError();
	if ($error) {

	    // echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
	    echo "Constructor error " . $error . "";
	}
	switch ($_GET['task']) {

		case 'login': //ndi.mx/prueba.php?task=login&em=jguzman@nextdata.com.mx&ps=3DB683C69D821F85F72829918FDC8D72A4FA55F6
			$values = array( "email" => $_GET['em'], "password" => $_GET['ps']);
    		$stockprice = $c->call('wsmethod',array('case'=>'login','value'=>json_encode($values)));
		break;

		case 'listaCallesBySubFracc'://ndi.mx/prueba.php?task=listaCallesBySubFracc?&nombre=Carrizal
			$values = array( "subfraccionamientoNombre" => $_GET['nombre']);
			$stockprice = $c->call('wsmethod',array('case'=>'listaCallesBySubFracc','value'=>json_encode($values)));
		break;

		
		case 'getDataforTurno'://http://192.168.0.8:8888/turno/ws/api.php?task=getDataforTurno&horainicio=09:00&sesion=28
			$values = array( 	"horainicio" => $_GET['horainicio'],
								"sesion" => $_GET['sesion']);
			$stockprice = $c->call('wsmethod',array('case'=>'getDataforTurno','value'=>json_encode($values)));
		break;
		case 'getTurnosHoy'://http://192.168.0.8:8888/turno/ws/api.php?task=getTurnosHoy&id=1
			$values = array( 	"fecha" => $_GET['fecha'],
								"estab" => $_GET['estab'],
								"recursos" => $_GET['recursos']);
			$stockprice = $c->call('wsmethod',array('case'=>'getTurnosHoy','value'=>json_encode($values)));
		break;
		case 'getRecursosDisponibles'://http://192.168.0.8:8888/turno/ws/api.php?task=getRecursosDisponibles&id=1
			$values = array( 	"tiposesion" => $_GET['tiposesion'],
								"estab" => $_GET['estab'],
								"dayofweek" => $_GET['dayofweek']);
			$stockprice = $c->call('wsmethod',array('case'=>'getRecursosDisponibles','value'=>json_encode($values)));
		break;
		
		case 'getSesionesDia'://http://192.168.0.8:8888/turno/ws/api.php?task=getSesionesDia&id=1
			$values = array( 	"dia" => $_GET['dia'],
								"estab" => $_GET['estab'],
								"dayofweek" => $_GET['dayofweek']);
			$stockprice = $c->call('wsmethod',array('case'=>'getSesionesDia','value'=>json_encode($values)));
		break;

		case 'resetEmail'://ndi.mx/prueba.php?task=resetEmail&em=jguzman@nextdata.com.mx
			$values = array( "nickname" => $_GET['em']);
			$stockprice = $c->call('wsmethod',array('case'=>'resetEmail','value'=>json_encode($values)));
		break;
		
		case 'getEstablecimiento'://http://192.168.0.8:8888/turno/ws/api.php?task=getEstablecimiento&id=1
			$values = array( "id" => $_GET['id']);
			$stockprice = $c->call('wsmethod',array('case'=>'getEstablecimiento','value'=>json_encode($values)));
		break;
		case 'getEstablecimientos'://http://192.168.0.8:8888/turno/ws/api.php?task=getEstablecimientos&id=1
			$values = array( "categoria" => $_GET['id']);
			$stockprice = $c->call('wsmethod',array('case'=>'getEstablecimientos','value'=>json_encode($values)));
		break;
		case 'getCategorias'://http://192.168.0.8:8888/turno/ws/api.php?task=getCategorias&id=1
			// echo "algo";
			$values = array( "categoria" => $_GET['id']);
			$stockprice = $c->call('wsmethod',array('case'=>'getCategorias','value'=>json_encode($values)));
		break;
		case 'setTurno'://http://192.168.0.8:8888/turno/ws/api.php?task=setTurno&establecimientoId=23&usuarioId=19&recursoId=13&tipoSesionId=28&fecha=2019-08-27&horainicio=09:30:00&horafin=10:15:00
			$values = array( 
					"establecimientoId" => $_GET['establecimientoId'], 
					"usuarioId" => $_GET['usuarioId'], 
					"recursoId" => $_GET['recursoId'], 
					"tipoSesionId" => $_GET['tipoSesionId'], 
					"fecha" => $_GET['fecha'], 
					"horainicio" => $_GET['horainicio'], 
					"horafin" => $_GET['horafin']
				);
			$stockprice = $c->call('wsmethod',array('case'=>'setTurno','value'=>json_encode($values)));
			$sp = json_decode($stockprice);
			if($sp->ws_error=='0'){
				$newPost = $database->getReference($_GET['establecimientoId'] . '-establecimiento/turno/' . $sp->notiId.'/notificacionId')->set($sp->notiId);
				$newPost = $database->getReference($_GET['establecimientoId'] . '-establecimiento/turno/' . $sp->notiId.'/notificacionEstatus')->set('5');
				$newPost = $database->getReference($_GET['establecimientoId'] . '-establecimiento/turno/' . $sp->notiId.'/notificacionTurnoId')->set($sp->turnoId);
				$newPost = $database->getReference($_GET['establecimientoId'] . '-establecimiento/turno/' . $sp->notiId.'/notificacionTime')->set(date('Y-m-d h:i:s'));
			}
			break;
		case 'registroCliente':
            $dev = $_GET['udev'] == '2' ? 0 : $_GET['udev'];
			 $values = array( 
			 		"apellidos" => $_GET['ape'], 
			 		"username" => $_GET['uname'], 
			 		"email" => $_GET['em'], 
			 		"nombre" => $_GET['nom'], 
			 		"password" => $_GET['ps'], 
			 		"telefono" => $_GET['tel']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'registroCliente','value'=>json_encode($values)));
            $sp = json_decode($stockprice);
            // $firebase = (new Factory)
            //     ->withServiceAccount($serviceAccount)
            //     ->withDatabaseUri('https://garde-aca75.firebaseio.com/')
            //     ->create();
            // $database = $firebase->getDatabase();
            // $newPost = $database->getReference($_GET['sid'] . '-Subfraccionamiento/NuevosUsuarios/' . $sp->ColonoId)->set($sp->ColonoId);
		break;
		
		case 'setTest':
			$values = array( 
			 		"nombre" => $_GET['id'], 
			 		"telefono" => $_GET['tel'], 
			 		"password" => $_GET['ps']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'setTest','value'=>json_encode($values)));
		break;

		case 'uploadToken'://ndi.mx/prueba.php?task=uploadToken?ntoken=xxxxxx?id=1
			$values = array( 
			 		"newToken" => $_GET['ntoken'], 
			 		"colonoId" => $_GET['id']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'uploadToken','value'=>json_encode($values)));
		break;
		
		case 'getColonoData'://ndi.mx/prueba.php?task=getColonoData?id=1
			$values = array( 
			 		"usuarioId" => $_GET['id']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'getColonoData','value'=>json_encode($values)));
		break;

		case 'changePass':
			$values = array( 
			 		"usuarioId" => $_GET['id'], 
			 		"usuarioPassOld" => $_GET['oPass'], 
			 		"usuarioPassNew" => $_GET['nPass']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'changePass','value'=>json_encode($values)));
		break;

		case 'getHistorico':
			$values = array( 
			 		"uid" => $_GET['uid'], 
			 		"tipoRep" => $_GET['tipoRep'],
			 		"fechaInicio" => $_GET['fechaInicio'],
			 		"fechaFin" => $_GET['fechaFin']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'getHistorico','value'=>json_encode($values)));
		break;
		
		case 'getMyReports'://ndi.mx/prueba.php?task=getMyReports?id=1
			$values = array( 
			 		"colonoId" => $_GET['id']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'getMyReports','value'=>json_encode($values)));
		break;
		
		case 'recoverPass'://ndi.mx/prueba.php?task=recoverPass?em=jguzman@nextdata.com.mx
			$values = array( 
			 		"colonoEmail" => $_GET['em']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'recoverPass','value'=>json_encode($values)));
		break;
		
		case 'getReporteDetail'://ndi.mx/prueba.php?task=getReporteDetail?id=1
			$values = array( 
			 		"reporteId" => $_GET['id']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'getReporteDetail','value'=>json_encode($values)));
		break;
		
		case 'uploadDevice':
			$values = array( 
			 		"UsuarioDevice" => $_GET['udevice'],
			 		"colonoId" => $_GET['id']
			 	);
			$stockprice = $c->call('wsmethod',array('case'=>'uploadDevice','value'=>json_encode($values)));
		break;
		
		default:
			echo "default";
		break;
	}

	if ($c->fault) {
	    // echo "<h2>Fault</h2><pre>";
	    echo "Fault";
	    print_r($stockprice);
	    // echo "</pre>";

	}
	else {
	    $error = $c->getError();
	    if ($error) {
	        // echo "<h2>Error</h2><pre>" . $error . "</pre>";
			echo "Error .." .$error . "";
			print_r($c);
	    }
	    else {
	        // echo "<pre>";
	        echo $stockprice;
			// echo "</pre>";
	    }
	}

?>