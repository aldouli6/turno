<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 1/04/19
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
            case "getTipoSesiones":
                $sql="SELECT  * FROM tiposSesiones where establecimientoId=?"; 
                $getTipoSesiones = $database->getRows($sql, array($establecimiento));
                //print_r($getTipoSesiones);
                $jsonTipoSesiones=json_encode($getTipoSesiones);
                echo $jsonTipoSesiones;
            break;
            case "getTipoSesion":
                $sql="SELECT  * FROM tiposSesiones where tipoSesionId=?"; 
                $getTipoSesion = $database->getRow($sql, array($tipoSesion));
                //print_r($getTipoSesion);
                $jsonTipoSesion=json_encode($getTipoSesion);
                echo $jsonTipoSesion;
            break;
            case "tipoSesionEditar":
                $fechaFinSesionEdit=(isset($fechaFinSesionEdit))?'NULL':$fechaFinSesionEdit;
                $costoSesionEdit=($costoSesionEdit==='')?'0':$costoSesionEdit;
                //echo $costoSesionEdit;
                $editarDatosElement="UPDATE tiposSesiones set 
                        nombre='".$SesionNombreEdit."',
                        clientesPorSesion='".$clientesSesionEdit."',
                        costo='".$costoSesionEdit."',
                        duracion='".$duracionEdit."',
                        fechaFin=".$fechaFinSesionEdit.",
                        limiteAntesAgendarDias='".$limiteAntesAgendarDiasEdit."',
                        limiteAntesAgendarHoras='".$limiteAntesAgendarHorasEdit."', 
                        limiteAntesAgendarMins='".$limiteAntesAgendarMinsEdit."',
                        maximoAgendarDias='".$maximoAgendarDiasEdit."',
                        maximoAgendarHoras='".$maximoAgendarHorasEdit."',
                        maximoAgendarMins='".$maximoAgendarMinsEdit."',
                        tiempoEntreSesion='".$tiempEntreSesionEdit."',
                        tiempoEspera='".$tiempoEsperaEdit."'
                            where tipoSesionId=?";
                
                $editElementData=$database->updateRow($editarDatosElement,array($tipoSesionIdEdit));
                //print_r($tipoSesionIdEdit);
                if($editElementData==true){
                    $ConsultarGetElement="SELECT  *
                        FROM tiposSesiones                                            
                        where tipoSesionId=?";
                    $GetElement=$database->getRow($ConsultarGetElement,array($tipoSesionIdEdit));
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
            case 'tipoSesionRegistrar':
                $database->beginTransactionDB();
                $newElement=array();
                $newElement[0]=$establecimientoIdNew;
                $newElement[1]=$SesionNombreNew;
                $newElement[2]=$clientesSesionNew;
                $newElement[3]=($costoNew=='')?'0':$costoNew;
                $newElement[4]=$duracionNew;
                $newElement[5]=$tiempoEsperaNew;
                $newElement[6]=$tiempEntreSesionNew;
                $newElement[7]=$maximoAgendarDiasNew;
                $newElement[8]=$maximoAgendarHorasNew;
                $newElement[9]=$maximoAgendarMinsNew;
                $newElement[10]=$limiteAntesAgendarDiasNew;
                $newElement[11]=$limiteAntesAgendarHorasNew;
                $newElement[12]=$limiteAntesAgendarMinsNew;
                $newElement[13]=($fechaFinNew=='')?null:$fechaFinNew;
                //print_r($newElement);
                $registrarElement=$database->insertRow("INSERT into tiposSesiones(
                                                establecimientoId,
                                                nombre,
                                                clientesPorSesion,
                                                costo,
                                                duracion,
                                                tiempoEspera,
                                                tiempoEntreSesion,
                                                maximoAgendarHoras,
                                                maximoAgendarDias,
                                                maximoAgendarMins,
                                                limiteAntesAgendarHoras,
                                                limiteAntesAgendarDias,
                                                limiteAntesAgendarMins,
                                                fechaFin
                                                ) 
                                                values(?,?,?,?,?,?,?,?,?,?,?,?,?,?)",$newElement);
                if($registrarElement==true){
                    $getElementLastId=$database->lastIdDB();
                    $ConsultarGetElement="SELECT  *
                                            FROM tiposSesiones                                              
                                            where tipoSesionId=?";
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
            case "eliminarTipoSesion":         
                if(SeguridadSistema::validarEntero(trim($tipoSesion))==true){
                    $eliminarElementInfo="DELETE FROM tiposSesiones where tipoSesionId=?";
                    //echo $eliminarElementInfo;
                    $eliminarElementData=$database->deleteRow($eliminarElementInfo,array($tipoSesion));
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