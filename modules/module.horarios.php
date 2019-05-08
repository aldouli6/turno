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
            case 'getHorario':
                    $sql="SELECT *
                    FROM horariosRecursos 
                    WHERE 1
                    AND horarioRecursoId=?"; 
                    $getElements = $database->getRow($sql, array($horario));
                    $jsonElements=json_encode($getElements);
                    echo $jsonElements;
                    break;
            case 'getHorarios':
                    $sql="SELECT h.horarioRecursoId as id, r.nombre, h.diasLaborables, h.horaInicio, h.horaFin
                    FROM horariosRecursos as h
                    INNER JOIN recursos as r on r.recursoId = h.recursoId
                    WHERE 1
                    AND r.establecimientoId=?"; 
                    $getElements = $database->getRows($sql, array($establecimiento));
                    $jsonElements=json_encode($getElements);
                    echo $jsonElements;
                    break;
            case 'getRecuros':
                $sql="SELECT  recursoId as id, nombre FROM recursos where establecimientoId=?"; 
                $getElements = $database->getRows($sql, array($establecimiento));
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case 'horarioRegistrar':
                $database->beginTransactionDB();
                $newElement=array();
                $newElement[0]=$recursoIdNew;
                $newElement[1]=$horaInicioNew;
                $newElement[2]=$horaFinNew;
                $newElement[3]=json_encode($diasLaboralesNew);
                $registrarElement=$database->insertRow("INSERT into horariosRecursos(
                                                recursoId,
                                                horaInicio,
                                                horaFin,
                                                diasLaborables
                                                ) 
                                                values(?,?,?,?)",$newElement);
                if($registrarElement==true){
                    $getElementLastId=$database->lastIdDB();
                    $ConsultarGetElement="SELECT h.horarioRecursoId as id, r.nombre, h.diasLaborables, h.horaInicio, h.horaFin
                                            FROM horariosRecursos as h
                                            INNER JOIN recursos as r on r.recursoId = h.recursoId                                             
                                            where h.horarioRecursoId=?";
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
            case "horarioEditar":
                $jsondiasLaboralesEdit=json_encode($diasLaboralesEdit);
                $editarDatosElement="UPDATE horariosRecursos set 
                        recursoId='".$recursoIdEdit."',
                        horaInicio='".$horaInicioEdit."',
                        horaFin='".$horaFinEdit."',
                        diasLaborables='".$jsondiasLaboralesEdit."'
                            where horarioRecursoId=?";
                //print_r($editarDatosElement);
                $editElementData=$database->updateRow($editarDatosElement,array($horarioIdEdit));
                
                if($editElementData==true){
                    $ConsultarGetElement="SELECT h.horarioRecursoId as id, r.nombre, h.diasLaborables, h.horaInicio, h.horaFin
                                            FROM horariosRecursos as h
                                            INNER JOIN recursos as r on r.recursoId = h.recursoId                                             
                                            where h.horarioRecursoId=?";
                    $GetElement=$database->getRow($ConsultarGetElement,array($horarioIdEdit));
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
            case "eliminarHorario":         
                if(SeguridadSistema::validarEntero(trim($horario))==true){
                    $eliminarElementInfo="DELETE FROM horariosRecursos where horarioRecursoId=?";
                    $eliminarElementData=$database->deleteRow($eliminarElementInfo,array($horario));
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