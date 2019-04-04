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
	static function menumostra($tipousuario, $permisos, $estatusMenu)
	{
		//echo $tipousuario;
			$activeMenu = "";
			$menu = NULL;
			$menu .= '<div class="sidebar-menu">
						<ul class="menu-items"><br>';
			
			$activeMenu = "";
				
			switch ($tipousuario) 
			{					
				case "root":
					if($estatusMenu == 'usuarios')
					{
						$activeMenu = "bg-success";
					}
					else
					{
						$activeMenu = "";
					}
					$menu .= Menus::crearSeccionMenu('Usuarios', 'fa fa-user', 'usuarios.php', $activeMenu);
				 	
					
				break;	

				case "admin":
					if($estatusMenu == 'usuarios')
					{
						$activeMenu = "bg-success";
					}
					else
					{
						$activeMenu = "";
					}
					$menu .= Menus::crearSeccionMenu('Usuarios', 'fa fa-user', 'usuarios.php', $activeMenu);

					break;

				case "guardia":
					if($estatusMenu == 'alertas')
					{
						$activeMenu = "bg-success";
					}
					else
					{
						$activeMenu = "";
					}
					$menu .= Menus::crearSeccionMenu('Alertas', 'fa fa-bell', 'alertas.php', $activeMenu);	
					
					if ($estatusMenu == 'reportes')
					{
						$activeMenu = "bg-success";
					}
					else 
					{
						$activeMenu = "";
					}
					$menu .= Menus::crearSeccionMenu('Reportes', 'fa fa-book', 'reportes.php',$activeMenu);
				break;
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
