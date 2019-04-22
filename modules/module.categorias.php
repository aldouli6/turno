<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 1/04/19
 * PROJECT: turno
 *
 * DESCRIPTION: Este archivo realiza todas las transacciones del módulo de categorias (altas,bajas,modificaciones,etc.)
 *
 */

$varUbicacion = 'securezone';
include_once("../class/class.brain.php");

$database = new db();
try {
    if(!empty($_POST)){            
        extract($_REQUEST);
        switch($cmd){
            case "listaCategorias":
                $categoria=$_REQUEST["categoria"];
                $sql="SELECT  categoriaId, nombre FROM categorias where categoriaPadre=? ORDER BY nombre"; 
                $getCategorias = $database->getRows($sql, array($categoria));
                //print_r($getSubcategorias)
                $jsonCategorias=json_encode($getCategorias);
                echo $jsonCategorias;
                
            break;
            case 'registraCategoria':
                $database->beginTransactionDB();
                $newCatego=array();
                $newCatego[0]=$_REQUEST["newCategoria"];
                $newCatego[1]=$_REQUEST["categoriaPadreInp"];
                $registrarCatego=$database->insertRow("INSERT into categorias(
                                                nombre,categoriaPadre) 
                                                values(?,?)",$newCatego);
                if($registrarCatego==true){
                    $getCategoLastId=$database->lastIdDB();
                    $ConsultarGetCatego="SELECT  *
                                            FROM categorias                                              
                                            where categoriaId=?";
                    $GetCategoria=$database->getRow($ConsultarGetCatego,array($getCategoLastId));
                    if($GetCategoria==true){
                        $database->commitDB();
                        $jsonCategoria=json_encode($GetCategoria);
                        echo $jsonCategoria;
                    }else{
                        $database->rollBackDB();
                        echo "0";
                    }
                }else{
                    $database->rollBackDB();
                    echo "0";
                }
                break;
            case "editarCategoria":
                $editarDatosCategoria="UPDATE categorias set 
                        nombre='".$nuevoNombre."'
                            where categoriaId=?";
                $editCategoData=$database->updateRow($editarDatosCategoria,array($categoria));
                if($editCategoData==true){
                    $ConsultarGetCatego="SELECT  *
                        FROM categorias                                            
                        where categoriaId=?";
                    $GetCatego=$database->getRow($ConsultarGetCatego,array($categoria));
                    if($GetCatego==true){
                        echo '1';
                    }else{
                        echo "0";
                    }
                }else{
                    echo "0";
                }
                break;
            case "eliminarCategoria":         
                if(SeguridadSistema::validarEntero(trim($_REQUEST['categoria']))==true){
                    $eliminarCategoInfo="DELETE FROM categorias where categoriaId=?";
                    $eliminarCategoData=$database->deleteRow($eliminarCategoInfo,array($categoria));
                    if($eliminarCategoData==true){
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