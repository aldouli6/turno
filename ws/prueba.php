<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// date_default_timezone_set('UTC');
date_default_timezone_set('America/New_York');
$varUbicacion = 'webservice';
include_once '../class/class.brain.php';


	$opts = array(
		'http'=>array(
			'user_agent' => 'PHPSoapClient'
			)
	);
	
	$context = stream_context_create($opts);

	libxml_disable_entity_loader(false);
	
	// $c = new soapclient('https://www.gardeapp.com/ws/wsgarde.php');

	$c = new soapclient('http://127.0.0.1/ws/wsgarde.php', array('stream_context' => $context));


	// $c->authtype = 'certificate';
	// $c->certRequest['sslcertfile']='/CA/server.crt';
	// $c->certRequest['passphrase']='passphrase';
	// $c->certRequest['sslkeyfile']='/CA/server.key';
	// $c->certRequest['verifypeer']=0;      
	// $c->certRequest['verifyhost']=0;   


	$error = $c->getError();
	if ($error) {
	    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
	}

	// $stockprice = $c->call('wsmethod',array('case'=>'altaAlerta', 'value'=>'{"usuarioId:"9", "subfraccionamientoId":"6", "tiporeporteId":"3", "fechahora":"2017-09-15 14:36:30", "latitud":"21.1401783314", "longitud":"-101.618029587", "comentario":"Comentario para el reporte", "ReporteDireccionDetalle":"Aqui es la direccion del reporte", "ReporteIsIn":"1"}'));
	// $stockprice = $c->call('wsmethod',array('case'=>'getMyReports', 'value'=>'{"colonoId":124}'));
	// $stockprice = $c->call('wsmethod',array('case'=>'listaSubfraccionamientos', 'value'=>''));
	
	$stockprice = $c->call('wsmethod',array('case'=>'holamundo', 'value'=>''));

	if ($c->fault) {
	    echo "<h2>Fault</h2><pre>";
	    print_r($stockprice);
	    echo "</pre>";
	}
	else {
	    $error = $c->getError();
	    //var_dump($c);
	    if ($error) {
	        echo "<h2>Error</h2><pre>" . $error . "</pre>";
	    }
	    else {
	        echo "<pre>";
	        echo "The data is $stockprice.";
	        echo "</pre>";
	    }
	}
	var_dump($c);
?>
