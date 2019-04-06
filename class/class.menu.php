<?php
/*
 * CREATOR: VZERT.COM
 * DEVELOPER: SALVADOR MTZ. ANDRADE
 * DATE: 17/12/2015
 * PROJECT: BusOnline
 *
 * LIBRERIAS PARA IMPRIMIR MENU PRINCIPAL
 * DESCRIPTION: IMPREME LAS LIBRERIAS NECESARIAS PARA CADA CASO
 *
 */

/*---------------------------------------------------------------*/
//Headers General
/*---------------------------------------------------------------*/

/*
$permisos = {
	root=>{usuarios, ventas, etc}
	administrador => {ventas, compras, etc}
	guardia => {panel clientes}

	}
*/
class Menus
{
	//Muestra el menu
	static function menumostra($varUbicacion, $permisos, $estatusMenu){
			$menu = NULL;
			$menu .= '<div class="sidebar-menu">
					<ul class="menu-items"><br>';
			foreach ($permisos as $permiso => $value) {
				if($value["where"]=="all")
					$menu .= Menus::crearSeccionMenu($value["label"], 
						$value["icon"], 
						$permiso.'.php', 
						($estatusMenu==$permiso)?"bg-success":"");
			}
			$menu .= '</ul>
			<div class="clearfix"></div>
			</div>';
		echo $menu;
	}

	static function crearSeccionMenu ($titulo, $icono, $link, $activeMenu)
	{
		$clase = '';
		if($titulo == 'Panel')
		{
			$clase = 'm-t-30';
		}
		$temp = '<li class="'.$clase.'">
			<a href="'.$link.'">
				<span class="title">'.$titulo.'</span>
			</a>
			<span class="icon-thumbnail '.$activeMenu.'" ><i class="'.$icono.'"></i></span>
		</li>';
		return $temp;
	}
}
?>
