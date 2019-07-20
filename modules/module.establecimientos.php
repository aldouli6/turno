<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 1/04/19
 * PROJECT: turno
 *
 * DESCRIPTION: Este archivo realiza todas las transacciones del módulo de establecimientos (altas,bajas,modificaciones,etc.)
 *
 */

$varUbicacion = 'securezone';
include_once("../class/class.brain.php");

$database = new db();
try {
    if(!empty($_POST)){            
        extract($_REQUEST);
        switch($cmd){
            case 'getEstablecimiento':
                $sql="SELECT *
                FROM establecimientos 
                WHERE 1
                AND establecimientoId=?"; 
                $getElements = $database->getRow($sql, array($establecimiento));
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case 'editnombrecontacto':
                $editarDatosElement="UPDATE establecimientos set 
                        nombre='".$nombre."',
                        emailEstablecimiento='".$emailEstablecimiento."',
                        telefonoEstablecimiento='".$telefonoEstablecimiento."',
                        categoriaId='".$categoria."',
                        subcategoriaId='".$subcategoria."'
                            where establecimientoId=?";
                // print_r($editarDatosElement);
                $editElementData=$database->updateRow($editarDatosElement,array($establecimientoId));
                
                if($editElementData==true){
                    $ConsultarGetElement="SELECT *
                                            FROM establecimientos                                             
                                            where establecimientoId=?";
                    $GetElement=$database->getRow($ConsultarGetElement,array($establecimientoId));
                    if($GetElement==true){
                        $jsonElement=json_encode($GetElement);
                        echo $jsonElement;
                    }else{
                        echo "0";
                    }
                }else{
                    echo "0";
                }
            break;
            case 'editubicacion':
                $editarDatosElement="UPDATE establecimientos set 
                        calle='".$calle."',
                        numeroExt='".$numeroExt."',
                        numeroInt='".$numeroInt."',
                        colonia='".$colonia."',
                        estado='".$estado."',
                        ciudad='".$ciudad."',
                        latitud='".$latitud."',
                        longitud='".$longitud."'
                            where establecimientoId=?";
                // print_r($editarDatosElement);
                $editElementData=$database->updateRow($editarDatosElement,array($establecimientoId));
                
                if($editElementData==true){
                    $ConsultarGetElement="SELECT *
                                            FROM establecimientos                                             
                                            where establecimientoId=?";
                    $GetElement=$database->getRow($ConsultarGetElement,array($establecimientoId));
                    if($GetElement==true){
                        $jsonElement=json_encode($GetElement);
                        echo $jsonElement;
                    }else{
                        echo "0";
                    }
                }else{
                    echo "0";
                }
            break;
            case 'editgenerales':
                $diasFeriadosExtra=json_encode($diasFeriadosExtra);
                $editarDatosElement="UPDATE establecimientos set 
                        stepping='".$stepping."',
                        diasAsuetoExtra='".$diasFeriadosExtra."',
                        diasAsuetoOficiales='".$asuetoOficial1."',
                        tooltipayuda='".$tooltipayuda1."'
                            where establecimientoId=?";
                 //print_r($editarDatosElement);
                $editElementData=$database->updateRow($editarDatosElement,array($establecimientoId));
                
                if($editElementData==true){
                    $ConsultarGetElement="SELECT *
                                            FROM establecimientos                                             
                                            where establecimientoId=?";
                    $GetElement=$database->getRow($ConsultarGetElement,array($establecimientoId));
                    if($GetElement==true){
                        $jsonElement=json_encode($GetElement);
                        echo $jsonElement;
                    }else{
                        echo "0";
                    }
                }else{
                    echo "0";
                }
            break;
            case "registrarEstablecimiento":
                try {
                    $database->beginTransactionDB();
                    $establecimiento=array();
                    $userId=$_REQUEST['regEstabUsuario'];
                    unset($_REQUEST['cmd']);
                    unset($_REQUEST['establecimiento']);
                    unset($_REQUEST['regEstabUsuario']);
                    foreach ($_REQUEST as $key => $value) {
                        $establecimiento[]=$value;
                    }
                    $registrarEstablecimiento=$database->insertRow("INSERT into establecimientos(
                        pais,
                        latitud,
                        longitud,
                        nombre, 
                        emailEstablecimiento,
                        telefonoEstablecimiento,
                        categoriaId,
                        subcategoriaId,
                        calle,
                        numeroExt,
                        numeroInt,
                        codigoPostal,
                        colonia,
                        estado,
                        ciudad)
                        values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?) ", $establecimiento);
                    if($registrarEstablecimiento==true){
                        $getEstabLastId=$database->lastIdDB();
                        $editarDatosUsuario="UPDATE usuarios set 
                                establecimientoId='".$getEstabLastId."'
                                where usuarioId=?";
                        $editUsuarioData=$database->updateRow($editarDatosUsuario,array($userId));
                        if($editUsuarioData==true){
                            $ConsultarGetEstable="SELECT u.usuarioId, u.estatus,u.tipoUsuarioId,u.username,u.nombre,u.apellidos,u.password,u.email,u.telefono,u.fecha_registro,
                            e.establecimientoId, e.nombre as estabNombre 
                            FROM 	usuarios as u 
                            INNER JOIN establecimientos as e on u.establecimientoId = e.establecimientoId 
                            WHERE e.establecimientoId = ? ";
                            $getEstab=$database->getRow($ConsultarGetEstable,array($getEstabLastId));
                            if($getEstab==true){
                                $database->commitDB();
                                //session_destroy();
                                session_start();
                                $_SESSION["EstablecimientoId"]=$getEstabLastId;
                                $_SESSION["UsuarioID"] = $getEstab['usuarioId'];
                                $_SESSION["UsuarioNombre"] = $getEstab['nombre']." ".$getEstab['apellidoPaterno'];
                                $_SESSION["UsuarioEmail"] = $getEstab['email'];
                                $_SESSION["EstablecimientoID"]=$getEstab['establecimientoId'];
                                $_SESSION["EstablecimientoNombre"]=$getEstab['estabNombre'];
                                Sessions::loadvarsesion($getEstab['tipoUsuarioId']);
                                //print_r($_SESSION);
                                echo "1";
                            }else{
                                $database->rollBackDB();
                                echo "0";
                            }
                        }else{
                            $database->rollBackDB();
                            echo "0";
                        }
                            
                    }else{
                        $database->rollBackDB();
                        echo "0";
                    }
                } catch (Exception $e) {
                    print_r($e);
                }
                break;
            


        }
    }

} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

?>