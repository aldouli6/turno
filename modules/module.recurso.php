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
            case "getRecursosHoy":
                $claves=["D","L","M","X","J","V","S"];
                $clave=$claves[$dia];
                $sql='SELECT DISTINCT recursoId, nombre, horaInicio, horaFin FROM (
                    SELECT r.recursoId, r.nombre, r.diasAsuetoExtra, r.diasAsuetoOficiales, h.horaInicio, h.horaFin, h.diasLaborables,
                    if( r.diasAsuetoOficiales  = \'0\' , \'[]\',
                          (SELECT CONCAT(\'[\',  GROUP_CONCAT(JSON_ARRAy(dia)), \']\' )   FROM diasAsuetoOficiales ) ) as oficiales
                
                    FROM recursos r
                    INNER JOIN horariosRecursos as h
                    ON h.recursoId= r.recursoId
                    WHERE r.establecimientoId = '.$establecimiento.' and r.recursoId ';
                $sql.=($recurso>0)? ' = ': '<>';
                $sql.=$recurso.' 

                    and JSON_CONTAINS(h.diasLaborables, \'["'.$clave.'"]\') -- para el filtrar por el día de hoy
                    and !JSON_CONTAINS(r.diasAsuetoExtra, \'["'.$date.'"]\') -- para filtrar los dias de asueto extra
                   -- 
                 ) as interna  WHERE !JSON_CONTAINS(oficiales, \'["'.$date.'"]\') order by horaInicio -- para filtrar los dias de asueto oficiales';

                $getElements = $database->getRows($sql);
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case "addRelRecSesion":
                $database->beginTransactionDB();
                $newElement=array();
                $newElement[0]=$recursoId;
                $newElement[1]=$sesionIdSelect;
                $registrarElement=$database->insertRow("INSERT into relacionesRecursoTipoSesion(
                                                recursoId,
                                                tipoSesionId
                                                ) 
                                                values(?,?)",$newElement);
                if($registrarElement==true){
                    $getElementLastId=$database->lastIdDB();
                    $ConsultarGetElement="SELECT  rt.idRelacionesRecursoTipoSesion as id, t.nombre, rt.tipoSesionId
                                            FROM relacionesRecursoTipoSesion as rt
                                            INNER JOIN tiposSesiones as t on t.tipoSesionId = rt.tipoSesionId                                              
                                            where idRelacionesRecursoTipoSesion=?";
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
            case "eliminarRelRecSesion":         
                if(SeguridadSistema::validarEntero(trim($relrecurso))==true){
                    $eliminarElementInfo="DELETE FROM relacionesRecursoTipoSesion where idRelacionesRecursoTipoSesion=?";
                    $eliminarElementData=$database->deleteRow($eliminarElementInfo,array($relrecurso));
                    if($eliminarElementData==true){
                            echo "1";
                    }else{
                            echo "0";
                    }
                }            
                break;
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
                $sql="SELECT  * FROM recursos where establecimientoId =? order by nombre"; 
                $getElements = $database->getRows($sql, array($establecimiento));
                //print_r($getElements);
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case "getRecursosFromTipoSesion":
                $sql="SELECT DISTINCT (r.recursoId),r.nombre FROM recursos as r
                INNER JOIN relacionesRecursoTipoSesion as rrt
                ON rrt.recursoId = r.recursoId
                WHERE 1
                AND r.establecimientoId = ? AND rrt.tipoSesionId";
                $sql.=($tipoSesion!='0')?" = ":" <> ";
                $sql.="? ORDER BY r.nombre"; 
                $getElements = $database->getRows($sql, array($establecimiento, $tipoSesion));
                //print_r($getElements);
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case "getRelTipoSesiones":
                $sql="SELECT DISTINCT t.tipoSesionId, t.nombre 
                FROM relacionesRecursoTipoSesion as rt
                INNER JOIN recursos as r on r.recursoId = rt.recursoId 
                INNER JOIN tiposSesiones as t on t.tipoSesionId = rt.tipoSesionId
                WHERE 1
                AND r.establecimientoId = ?
                AND t.establecimientoId = ?
                AND rt.recursoId ";
                $sql.=($recurso!='0')?" = ":" <> ";
                $sql.="? ORDER BY t.nombre"; 
                $getElements = $database->getRows($sql, array($establecimiento, $establecimiento, $recurso));
                // print_r($sql);
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
                $jsondiasAsuetoExtraEdit=json_encode($diasAsuetoExtraEdit);
                $editarDatosElement="UPDATE recursos set 
                        nombre='".$recursoNombreEdit."',
                        cantidad='".$recursoCantidadEdit."',
                        diasAsuetoOficiales='".$diasAsuetoOficialesEdit."',
                        diasAsuetoExtra='".$jsondiasAsuetoExtraEdit."'
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
                $newElement[4]=$diasAsuetoOficialesNew;
                $newElement[5]=json_encode($diasAsuetoExtraNew);
                $registrarElement=$database->insertRow("INSERT into recursos(
                                                establecimientoId,
                                                nombre,
                                                cantidad,
                                                diasAsuetoOficiales,
                                                diasAsuetoExtra
                                                ) 
                                                values(?,?,?,?,?)",$newElement);
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