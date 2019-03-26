<?php
/*
 * CREATOR: VZERT.COM
 * DEVELOPER: SALVADOR MTZ. ANDRADE
 * DATE: 13/06/2016
 * PROJECT: SegurosOnline
 *
 * LIBRERIAS PARA PLUGIS
 * DESCRIPTION: ES NECESARIO CARGAR LA CLASE DE
 *
 */

 
/*---------------------------------------------------------------*/
//Mostrar plugins
/*---------------------------------------------------------------*/
class MostrarPlugins{
	public static function mostrarConfigUser(){
		echo '<!-- START User Info-->
          <div class="visible-lg visible-md m-t-10">
            
                <div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
                  <span class="semi-bold" style="position: absolute;margin-left: -104px;margin-top: -8px;font-size: 15px;">'.$_SESSION["UsuarioNombre"].'</span>                            
                </div>
                <div class="pull-left p-r-10 p-t-10 fs-16 font-heading">
                  <span class="semi-bold" style="font-size: 13px;margin-top: 11px;position: absolute;margin-left: -114px;">'.$_SESSION["desctypeuser"].'</span>
                </div>
            

            <div class="dropdown pull-right">
              <button type="button" data-toggle="modal" data-target="#modalSlideLeft" style="background: white;border: solid 0px;">
                <span class="thumbnail-wrapper d32 circular inline m-t-5">
                    <img src="assets/img/userAccount.png" alt="" data-src="assets/img/userAccount.png"  width="32" height="32">
                </span>
              </button>

              <!--<ul class="dropdown-menu profile-dropdown" role="menu">
                <li class="" style="padding-top: 2px;">                                 
                    <a href="#" data-toggle="modal" data-target="#modalSlideLeft">Ver mi perfil</a>
                </li>

                  <li class="bg-master-lighter" style="margin-top: 0px;">
                    <a href="logout.php" class="clearfix">
                      <span class="pull-left">Cerrar sesión</span>
                      <span class="pull-right"><i class="pg-power"></i></span>
                    </a>
                  </li>
              </ul>-->


            </div>
          </div>
          <!-- END User Info-->
                    
          ';
             
  }
  

}