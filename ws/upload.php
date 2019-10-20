<?php

include "class.upload.php";
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Request-Headers: cache-control,x-requested-with");
header("Access-Control-Request-Method: POST");

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With, cache-control");
/// mostrar errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 $tipo='usuarios';
$perfil='19';
    $file_ext = 'png';
    // print_r($_FILES);
    
    // print_r($_POST);
    $target_path = $_SERVER['DOCUMENT_ROOT']."/turno/assets/img/profiles/";
    $target_path .= $tipo.'_'.$perfil.'.'.$file_ext; 
    if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/turno/assets/img/profiles/".$tipo."_".$perfil.".png";
        echo $actual_link;
    } else{
        echo 0;
    }

?>