<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 12/04/19
 * PROJECT: turno
 *
 * DESCRIPTION: Este archivo realiza todas las transacciones del módulo de Tipos sesiones (altas,bajas,modificaciones,etc.)
 *
 */

$varUbicacion = 'securezone';
include_once("../class/class.brain.php");

$database = new db();
try {
    if(!empty($_POST)){            
        extract($_REQUEST);
        switch($cmd){
            case "cargaSelect":
                $sql="SELECT t.tipoSesionId as id, t.nombre FROM tiposSesiones as t
                WHERE 1
                AND t.establecimientoId= ?
                AND t.tipoSesionId NOT IN(SELECT tipoSesionId FROM relacionesRecursoTipoSesion as rt 
                WHERE rt.recursoId = ? )"; 
                $getElements = $database->getRows($sql, array($establecimiento, $recurso));
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case "getRecursos":
                $sql="SELECT  * FROM recursos where establecimientoId=?"; 
                $getElements = $database->getRows($sql, array($establecimiento));
                //print_r($getElements);
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case "getRelTipoSesiones":
                $sql="SELECT rt.idRelacionesRecursoTipoSesion as id, r.recursoId, t.tipoSesionId, r.establecimientoId, t.nombre 
                FROM relacionesRecursoTipoSesion as rt
                INNER JOIN recursos as r on r.recursoId = rt.recursoId 
                INNER JOIN tiposSesiones as t on t.tipoSesionId = rt.tipoSesionId
                WHERE 1
                AND r.establecimientoId = ?
                AND t.establecimientoId = ?
                AND rt.recursoId = ? "; 
                $getElements = $database->getRows($sql, array($establecimiento, $establecimiento, $recurso));
                //print_r($getElements);
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case "getRecurso":
                $sql="SELECT  * FROM recursos where recursoId=?"; 
                $getElement = $database->getRow($sql, array($recurso));
                //print_r($getElement);
                $jsonElement=json_encode($getElement);
                echo $jsonElement;
            break;
            case "recursoEditar":
                $editarDatosElement="UPDATE recursos set 
                        nombre='".$recursoNombreEdit."',
                        cantidad='".$recursoCantidadEdit."'
                            where recursoId=?";
                
                $editElementData=$database->updateRow($editarDatosElement,array($recursoIdEdit));
                //print_r($recursoIdEdit);
                if($editElementData==true){
                    $ConsultarGetElement="SELECT  *
                        FROM recursos                                            
                        where recursoId=?";
                    $GetElement=$database->getRow($ConsultarGetElement,array($recursoIdEdit));
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
            case 'recursoRegistrar':
                $database->beginTransactionDB();
                $newElement=array();
                $newElement[0]=$establecimientoIdNew;
                $newElement[1]=$recursoNombreNew;
                $newElement[2]=$recursoCantidadNew;
                $registrarElement=$database->insertRow("INSERT into recursos(
                                                establecimientoId,
                                                nombre,
                                                cantidad
                                                ) 
                                                values(?,?,?)",$newElement);
                if($registrarElement==true){
                    $getElementLastId=$database->lastIdDB();
                    $ConsultarGetElement="SELECT  *
                                            FROM recursos                                              
                                            where recursoId=?";
                    $GetElement=$database->getRow($ConsultarGetElement,array($getElementLastId));
                    if($GetElement==true){
                        $database->commitDB();
                        $jsonElement=json_encode($GetElement);
                        echo $jsonElement;
                    }else{
                        $database->rollBackDB();
                        echo "0";
                    }
                }else{
                    $database->rollBackDB();
                    echo "0";
                }
                break;
            case "eliminarRecurso":         
                if(SeguridadSistema::validarEntero(trim($recurso))==true){
                    $eliminarElementInfo="DELETE FROM recursos where recursoId=?";
                    $eliminarElementData=$database->deleteRow($eliminarElementInfo,array($recurso));
                    if($eliminarElementData==true){
                            echo "1";
                    }else{
                            echo "0";
                    }
                }            
                break;
        }
    }
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

?>