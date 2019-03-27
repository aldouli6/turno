<?php
/*
* CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 25/03/19
 * PROJECT: turno
 *
 * DESCRIPTION: Este archivo realiza todas las transacciones del módulo de usuario (altas,bajas,modificaciones,etc.)
 *
 */

$varUbicacion = 'securezone';
include_once("../class/class.brain.php");
$database = new db();
 if(!empty($_POST)){
    extract($_REQUEST);
    switch($cmd){
        case "getProfileInfo":
            //Se muestran los datos del usuario seleccionado en el formulario de edición por medio del ID de usuario.    
            $InfoUsuaro=null;
            $jsonInfoUsuario=null;
            $getIDUsuario=$_REQUEST["idProfileUser"];
            //Se obtienen los datos del usuario por medio de su ID Usuario y se muestran en el modal form de la edición del usuario
            $consultaInfoUsuario="SELECT * FROM usuarios WHERE usuarioId = ?";                                                    
            $getUsuarioData=$database->getRow($consultaInfoUsuario, array($getIDUsuario));
            if($getUsuarioData != false){
                /*Se obtienen los dato∫s en caso de que la consulta haya sido efectuada correctamente*/
                //Unas vez obtenidos los datos se separan por comas(indicador que nos sirve para poder dividir al información) y así obtener cada uno de los elementos
                $InfoUsuaro=array("usuarioId"=>$getUsuarioData["usuarioId"],
                    "nombre"=>$getUsuarioData["nombre"],
                    "apellidos"=>$getUsuarioData["apellidos"],
                    "tipoUsuarioId"=>$getUsuarioData["tipoUsuarioId"],
                    //"password"=>$getUsuarioData["password"],
                    "username"=>$getUsuarioData["username"],
                    "email"=>$getUsuarioData["email"]);
                $jsonInfoUsuario=json_encode($InfoUsuaro);
                echo $jsonInfoUsuario;
            } 
        break;                
        case "editarPerfilUsuario":
            $idUsuarioEditar=$_REQUEST['profileUserId'];
            //Se editan los datos del usuario permitidos por el sistema, todo esto por medio del ID del usuario
            $editarDatosUsuario="UPDATE usuarios set nombre='".$_REQUEST["perfilNombre"]."', apellidos='".$_REQUEST["perfilApellidos"]."',  email='".$_REQUEST["perfilEmail"]
            ."' where usuarioId=?";
            $editUsuarioData=$database->updateRow($editarDatosUsuario,array($idUsuarioEditar));
            if($editUsuarioData==true){
                $ConsultarGetUsuario="SELECT nombre, apellidos, email FROM usuarios WHERE usuarioId = ?";
                $GetUsuario=$database->getRow($ConsultarGetUsuario,array($idUsuarioEditar));
                if($GetUsuario==true){
                    $jsonGetUsuario=json_encode($GetUsuario);
                    echo $jsonGetUsuario;
                }else{
                    echo "0";
                }
            }else{
                echo "0";
            }
        break;
    }
}


?>
