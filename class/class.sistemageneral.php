<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 24/03/19
 * PROJECT: turno
 *
 * FUNCIONES GENERALES
 * BASE DE DATOS - busonline
 * DESCRIPTION: MANEJO DE FUNCIONES GENERALES EN EL SISTEMA
 *
 */
class Sistemageneral{
	
	//Función elimina caracteres invalidos
	static function clean($query){
		$nopermitidos = array("<", ">", "/", "'", '"', "%", "!", "-", "=", "|", "&");
		$query = str_replace($nopermitidos, "", $query);
		return $query;
	}

	//funcion para crear el FOLIO o referencia interna
	static function createRandomString($stringLength)
	{
		$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$pass = '';
		$randomNum =0;
		while ($i <= $stringLength)
		{
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$randomNum = $randomNum . $tmp;
			$i++;
		}
		return $randomNum;
	}
}
?>
