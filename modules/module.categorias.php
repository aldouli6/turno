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
                $sql="SELECT  categoriaId, nombre FROM categorias where categoriaPadre = '0'";  
                $getCategorias = $database->getRows($sql);
                echo "<option value='0'>-- Seleccione una categoría --</option>";
                foreach ($getCategorias as $rsCategoria) {
                    echo "<option value='".$rsCategoria['categoriaId']."'>".$rsCategoria['nombre']."</option>";                                             
                }
            break;
            case "listaSubcategorias":
                $categoria=$_REQUEST["categoria"];
                $sql="SELECT  categoriaId, nombre FROM categorias where categoriaPadre=?"; 
                $getSubcategorias = $database->getRows($sql, array($categoria));
                if ($getSubcategorias){
                    echo "<option value='0'>-- Seleccione una subcategoría --</option>";
                    foreach ($getSubcategorias as $rsSubcategoria) {
                        echo "<option value='".$rsSubcategoria['categoriaId']."'>".$rsSubcategoria['nombre']."</option>";                                             
                    }
                }else{
                    echo "0";
                }
                
            break;


        }
    }
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

?>