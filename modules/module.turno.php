<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 25/03/19
 * PROJECT: turno
 *
 * DESCRIPTION: Este archivo realiza todas las transacciones del módulo de turnos (altas,bajas,modificaciones,etc.)
 *
 */

$varUbicacion = 'securezone';
include_once("../class/class.brain.php");

$database = new db();
try {
if(!empty($_POST)){
   
    extract($_REQUEST);
        switch($cmd){
            case 'registrarTurno':
                $database->beginTransactionDB();
                $newElement=array();
                $newElement[0]=$establecimientoId;
                $newElement[1]=$recursoId;
                $newElement[2]=$tipoSesionId;
                $newElement[3]=$usuarioId;
                $newElement[4]=$horaInicio;
                $newElement[5]=$horaFin;
                $newElement[6]=$fecha;
                $newElement[7]=$estatusId;
                $registrarElement=$database->insertRow("INSERT into turnos(
                                                establecimientoId, 
                                                recursoId,
                                                tipoSesionId,
                                                usuarioId,
                                                horaInicio,
                                                horaFin,
                                                fecha,
                                                estatusId
                                                ) 
                                                values(?,?,?,?,?,?,?,?)",$newElement);
                if($registrarElement==true){
                    $getElementLastId=$database->lastIdDB();
                    $ConsultarGetElement="SELECT  *
                                            FROM turnos                                              
                                            where turnoId=?";
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
            


         }



}
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

?>
