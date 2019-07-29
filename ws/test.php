<?php
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
//echo '{"EstatusId":"1","TiporeporteId":"11","ReporteId":"317","ReporteRegistro":"02\/27\/2018 11:22:28 PM"}';

include_once('../lib/nusoap/nusoap.php');
	$c = new nusoap_client('http://gardeapp.com/ws/wsgarde.php');
	$c->soap_defencoding = 'UTF-8';
	$c->decode_utf8 = FALSE;
	$error = $c->getError();

	if ($error) {
	    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
	}
	
	$userId= $_GET['id'];//"63";
	$subfracc = $_GET['sf'];//"42";
	$tiporeporte = $_GET['tr'];//"11";
	$fecha = date("m/d/Y h:i:s A");
	$lat = $_GET['lt'];//"20.675978087571";
	$long = $_GET['ln'];//"-100.32057601959";
	$coment = $_GET['cm'];//"Botón de pánico";
	$direcc = $_GET['dr'];//"Xathé Norte"
	$stockprice = $c->call('wsmethod',array('case'=>'altaAlerta','value'=>'{"usuarioId":"'. $userId .'","subfraccionamientoId":"'. $subfracc .'","tiporeporteId":"'. $tiporeporte .'","fechahora":"'.$fecha.'","latitud":"'.$lat.'","longitud":"'.$long.'","comentario":"'. $coment .'","ReporteDireccionDetalle":"'. $direcc .'","ReporteIsIn":"0"}'));

	if ($c->fault) {
	    echo "<h2>Fault</h2><pre>";
	    print_r($stockprice);
	    echo "</pre>";
	}
	else {
	    $error = $c->getError();
	    if ($error) {
	        echo "<h2>Error</h2><pre>" . $error . "</pre>";
	    }
	    else {
	        echo "<pre>";
	        echo "The data is ".$stockprice;
			echo "</pre>";
	    }
	}

	var_dump($c);
	
?>