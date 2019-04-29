<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 18/03/19
 * PROJECT: turno
 *
 * LIBRERIAS PARA IMPRIMIR CSS, JAVASCRIPT
 * DESCRIPTION: IMPREME LAS LIBRERIAS NECESARIAS PARA CADA CASO
 *
 */

/*---------------------------------------------------------------*/
//Headers General
/*---------------------------------------------------------------*/
class Mostrargeneral
{
    public static function mostrarderechosreservados($nombrecomercial)
    {
        echo $nombrecomercial.'&copy;&nbsp;Copyright '.date(Y).'. Todos los derechos reservados.';
    }
    //Muestra los JS de la página completa
    public static function mostrarTitulo($i = 0)
    {
        $metadatos = null;

        switch ($i)
        {
          case 'login':
              $metadatos .= '<title>TURNO - Iniciar Sesión</title>';
          break;

          case 'panel':
              $metadatos .= '<title>TURNO - Panel</title>';
          break;

          case 'usuarios':
              $metadatos .= '<title>TURNO - Usuarios</title>';
          break;
          case 'categorias':
              $metadatos .= '<title>TURNO - Categorías</title>';
          break;
          case 'tipoSesiones':
              $metadatos .= '<title>TURNO - Tipos de Sesiones</title>';
          break;
          case 'recursos':
              $metadatos .= '<title>TURNO - Recursos</title>';
          break;
          case 'horarios':
              $metadatos .= '<title>TURNO - Horarios</title>';
          break;
          case 'turnos':
              $metadatos .= '<title>TURNO - Turnos</title>';
          break;
          default:
            $metadatos .= '<title>TURNO</title>';
        }

        $metadatos .= '<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
                       <meta charset="utf-8" />
                       <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
                       <meta name="apple-mobile-web-app-capable" content="yes">
                       <meta name="apple-touch-fullscreen" content="yes">
                       <meta name="apple-mobile-web-app-status-bar-style" content="default">
                       <meta content="" name="description" />
                       <meta content="" name="author" />';

        echo $metadatos;
    }
    //Muestra los CSS
    public static function mostrarcss($varubicacion = 0)
    {
        $css = null;

        $css .= '<link rel="apple-touch-icon" href="pages/ico/60.png">
                 <link rel="apple-touch-icon" sizes="76x76" href="assets/img/logo_pages.png">
                 <link rel="apple-touch-icon" sizes="120x120" href="assets/img/logo_pages.png">
                 <link rel="apple-touch-icon" sizes="152x152" href="assets/img/logo_pages.png">
                 <link rel="icon" type="image/x-icon" href="assets/img/logo_pages.png" />
                 <link href="assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
                 <link href="assets/plugins/boostrapv3/css/bootstrap.css" rel="stylesheet" type="text/css" />
                 <link href="assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
                 <link href="assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
                 <link href="assets/plugins/bootstrap-select2/select2.css" rel="stylesheet" type="text/css" media="screen" />
                 <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" type="text/css" media="screen" />
                 <link href="assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
                 <link href="pages/css/pages-icons.css" rel="stylesheet" type="text/css">
                 <link href="pages/css/pages.css" rel="stylesheet" type="text/css" class="main-stylesheet" />
                 <link href="pages/css/dataTable.css" rel="stylesheet" type="text/css" class="main-stylesheet" />
                 <link href="assets/plugins/lc_switch/css/lc_switch.css" rel="stylesheet" type="text/css" media="screen" />';

        switch ($varubicacion)
        {
            case 'login':
                $css .= '';
            break;

            case 'tipoSesiones':
                $css .= '<link href="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" type="text/css" media="screen">';
            break;

            
            default:
                $css .= '';
        }
        $css .= '<!--[if lte IE 9]><link href="pages/css/ie9.css" rel="stylesheet" type="text/css" /><![endif]-->';
        echo $css;
    }
    //Muestra fixed para Internet Explorer
    public static function mostrarfixeds()
    {
        echo '<script type="text/javascript">
                window.onload = function()
                {
                  // fix for windows 8
                  if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
                    document.head.innerHTML += \'<link rel="stylesheet" type="text/css" href="pages/css/windows.chrome.fix.css" />\'
                }
              </script>';
    }
    //Muestra los JS
    public static function mostrarjs($varubicacion = 0)
    {
        $javascript = null;
        $javascript = '<script src="assets/plugins/pace/pace.min.js" type="text/javascript"></script>
					  <script src="assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
					  <script src="assets/plugins/modernizr.custom.js" type="text/javascript"></script>
					  <script src="assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
					  <script src="assets/plugins/boostrapv3/js/bootstrap.js" type="text/javascript"></script>
					  <script src="assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
					  <script src="assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
					  <script src="assets/plugins/jquery-bez/jquery.bez.min.js"></script>
					  <script src="assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
            <script src="assets/plugins/sweetalert2-master/dist/sweetalert2.all.min.js"></script>
            <script src="assets/plugins/sweetalert2-master/dist/sweetalert2.min.js"></script>
            <script type="module"> import Swal from "./assets/plugins/sweetalert2-master/src/sweetalert2.js";    </script>
					  <script src="assets/plugins/jquery-actual/jquery.actual.min.js"></script>
					  <script src="assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
					  <script src="assets/plugins/bootstrap-select2/select2.min.js" type="text/javascript"></script>
					  <script src="assets/plugins/classie/classie.js" type="text/javascript"></script>
					  <script src="assets/plugins/boostrap-form-wizard/js/jquery.bootstrap.wizard.min.js" type="text/javascript"></script>
					  <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
					  <script src="assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
					  <script src="assets/plugins/jquery-validation/js/jquery.validate.js" type="text/javascript"></script>
            <script type="text/javascript" src="assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
					  <script src="pages/js/pages.min.js" type="text/javascript"></script>
            <script src="assets/js/scripts.js" type="text/javascript"></script>
					  <script src="assets/js/md5.js" type="text/javascript"></script>
					  <script src="assets/js/jquery.inputmask.min.js" type="text/javascript"></script>
					  <!--<script src="assets/js/form_elements.js" type="text/javascript"></script>-->
					  <script src="assets/js/dataTable.js" type="text/javascript"></script>
            <script src="https://www.gstatic.com/firebasejs/4.2.0/firebase.js"></script>
            <script src="assets/plugins/lc_switch/js/lc_switch.js" type="text/javascript"></script>
            <script src="assets/js/jquery.timeago.js" type="text/javascript"></script>';
        switch ($varubicacion) {
            //Base - Ideal para lo más basico
            case 'login':
              $javascript .= '<script src="assets/js/login.js"></script>';
              break;
            case 'usuarios':
              $javascript .= '<script src="assets/js/usuario.js" type="text/javascript"></script>
              <script src="assets/js/perfilUsuario.js" type="text/javascript"></script>
              <script type="text/javascript" src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
              <script src="assets/js/notificaciones.js" type="text/javascript"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>';
              break;
            case 'wizard':
              $javascript .= '<script src="assets/js/wizard.js"></script>
              <script src="assets/js/perfilUsuario.js" type="text/javascript"></script>';
              break;
            case 'categorias':
              $javascript .= '<script src="assets/js/categoria.js"></script>
              <script src="assets/js/perfilUsuario.js" type="text/javascript"></script>';
              break;
            case 'tipoSesiones':
              $javascript .= '<script src="assets/js/tipoSesion.js"></script>
              <script type="text/javascript" src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>
              <script src="assets/js/perfilUsuario.js" type="text/javascript"></script>';
              break;
            case 'recursos':
              $javascript .= '<script src="assets/js/recurso.js"></script>
              <script src="assets/js/perfilUsuario.js" type="text/javascript"></script>';
              break;
            case 'horarios':
              $javascript .= '<script src="assets/js/horario.js"></script>
              <script src="assets/js/perfilUsuario.js" type="text/javascript"></script>
              <script type="text/javascript" src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>
              <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>';
              break;
            case 'turnos':
              $javascript .= '<script src="assets/js/turno.js"></script>
              <script src="assets/js/recurso.js"></script>
              <script src="assets/js/perfilUsuario.js" type="text/javascript"></script>';
              break;
            default:
              $javascript .= '';
            break;
        }
        echo $javascript;
    }
    //Muestra encabezado
    public static function encabezadoLogo()
    {
        echo '<div class="sidebar-header">
        <img src="assets/img/logo_jpg.jpg" alt="logo" class="brand" data-src="assets/img/logo_jpg.jpg" data-src-retina="assets/img/logo_jpg.jpg" width="100" height="33">
        <div class="sidebar-header-controls">

				  <!--<button type="button" class="btn btn-xs sidebar-slide-toggle btn-link m-l-20" data-pages-toggle="#appMenu"><i class="fa fa-angle-down fs-16"></i></button>-->
				  <!--<button type="button" class="btn btn-link visible-lg-inline" data-toggle-pin="sidebar"><i class="fa fs-12"></i></button>-->
        </div>

			  </div>';
    }
     //Muestra el logo Large
     public static function logoLg()
     {
         echo '<div class="pull-center hidden-md hidden-lg">
                 <div class="header-inner">
                   <div class="brand inline">
           <!--<img src="assets/img/logo_jpg.jpg" alt="logo" data-src="assets/img/logo_jpg.jpg" data-src-retina="assets/img/logo_jpg.jpg" width="193" height="33">-->
                   </div>
                 </div>
               </div>';
     }
     //Muestra el logo Small
     public static function logoSm()
     {
         echo '<div class="brand inline"><img src="assets/img/logo_jpg.jpg" alt="logo" data-src="assets/img/logo_jpg.jpg" data-src-retina="assets/img/logo_jpg.jpg" width="100" height="33"> </div>';
         echo '<h5 style="margin-top: -30px;position: absolute;margin-left: 137px;font-weight: bolder;">| &nbsp;'  . $_SESSION["EstablecimientoNombre"] . '</h5>' ;
      }
     //Muestra el menu del mobile
     public static function menumobile()
     {
         echo '<div id="appMenu" class="sidebar-overlay-slide from-top"></div>';
     }
    //Muestra el menu del mobile
    public static function header(){
        echo '<div class="header ">
                <div class="container-fluid relative">
                    <div class="pull-left full-height visible-sm visible-xs">
                        ';
                    Mostrargeneral::actionBar();
        echo '
                    </div>
                </div>
                <div class=" pull-left sm-table hidden-xs hidden-sm">
                    <div class="header-inner">';
                         Mostrargeneral::logoSm();
                    echo'</div>
                </div>
                <div class=" pull-right">';
                     MostrarPlugins::mostrarConfigUser();         
                echo'</div>
            </div>';
    }
     //Muestra el ActionBar
     public static function actionBar()
     {
         echo '<!-- START ACTION BAR -->
             <div class="header-inner">
               <a href="#" class="btn-link toggle-sidebar visible-sm-inline-block visible-xs-inline-block padding-5" data-toggle="sidebar">
                 <span class="icon-set menu-hambuger"></span>
               </a>
             </div>
             <!-- END ACTION BAR -->';
     }
     public static function mostrarFooter()
     {
         echo '<!-- START FOOTER -->
                 <div class="container-fluid container-fixed-lg footer">
                   <div class="copyright sm-text-center">
                     <p class="small no-margin pull-left sm-pull-reset">
                       <span class="hint-text">Copyright © '.date('Y').'</span>
                       <span class="font-montserrat"></span>.
                       <span class="hint-text">Todos los derechos reservados.</span>
                       <span class="sm-block"><a href="#" class="m-l-10">Politicas de privacidad</a></span>
                     </p>
                     <div class="clearfix"></div>
                   </div>
                 </div>
                 <!-- END FOOTER -->';
     }
     public static function mostrarTituloPage($titulo = '')
     {
       echo '<!-- START JUMBOTRON -->
       <div class="jumbotron" data-pages="parallax">
         <div class="container-fluid container-fixed-lg sm-p-l-20 sm-p-r-20">
           <div class="inner">
           <ul class="breadcrumb">
             <li><p>Inicio</p></li>
             <li><a href="#" class="active">'.$titulo.'</a></li>
           </ul>
           </div>
         </div>
       </div>';
     }
     public static function mostrarPanelNotificaciones()
      {
        echo '

        <div class="col-md-3 no-padding full-height">
                <div class="bg-white  full-height" style="position: fixed;width: 100%;bottom: calc(0%);height: 100% !important;width: 49.2%;">
                  <!-- YOU CAN REMOVE FULL-HEIGHT IN ALL PARENT ELEMENTS TO EXPEND TO CONTENT HEIGHT
                              YOU CAN ALSO CHANGE THE BACKGROUN COLOR BY ADDING THE BG CLASSES
                              EXAMPLE : bg-success
                            -->
                          <!-- <h5 style="margin-top: calc(60px);background: #ababab;">Notificaciones</h5> -->


                            <div class="view bg-white" style="margin-top: 60px;width: 49.2%;">
                                <!-- BEGIN Alert List !-->
                                <div data-init-list-view="ioslist" class="list-view boreded no-top-border" style="width: 49.2%;">

                                        <h1 class="list-view-fake-header" style="font-size: 14px !important;font-weight: 600;width: 100%;z-index: 100;line-height: 14px !important;style="width: 49.2%;"">

                                        Centro de notificaciones</h1>

                                        <!-- BEGIN List Group !-->
                                        <div class="scroll-wrapper list-view-wrapper" style="position: absolute;width: 49.2%;">
                                              <div class="list-view-wrapper scroll-content" data-ios="false" style="height: 100vh; margin-bottom: 0px; margin-right: 0px; max-height: none;width: 49.2%;">
                                                  <div class="list-view-group-container">

                                                        <div class="list-view-group-container">
                                                                      <!-- BEGIN List Group Header!-->
                                                                      <div class="list-view-group-header text-uppercase">
                                                                              Nuevos colonos
                                                                      </div>
                                                                      <!-- END List Group Header!-->

                                                                      <ul id="NotificacionColonoLista">
                                                                          <!-- BEGIN List Group Item!-->
                                                                          <li class="alert-list">
                                                                                <center><p id="No_Notifications" style="padding:20px;margin-left:calc(5%);display:none;">No hay notificaciones</p></center>
                                                                          </li>
                                                                      </ul>
                                                          </div>

                                                          <div class="list-view-group-container">
                                                                    <!-- BEGIN List Group Header!-->
                                                                    <div class="list-view-group-header text-uppercase">
                                                                            Alertas
                                                                    </div>

                                                                      <!-- END List Group Header!-->
                                                                      <ul id="NotificacionReporteLista" style="width: 95%;">
                                                                            <li class="alert-list">
                                                                                <center><p id="No_NotificationsReport" style="padding:20px;margin-left:calc(5%);">No hay notificaciones</p></center>
                                                                            </li>
                                                                      </ul>
                                                          </div>

                                                          <div class="list-view-group-container">
                                                              <!-- BEGIN List Group Header!-->
                                                              <div class="list-view-group-header text-uppercase">
                                                                      Alertas fuera del fraccionamiento
                                                              </div>

                                                                <!-- END List Group Header!-->
                                                                <ul id="NotificacionReporteListaFueraFraccionamiento" style="width: 95%;">
                                                                      <li class="alert-list">
                                                                          <center><p id="No_NotificationsReportOut" style="padding:20px;margin-left:calc(5%);">No hay notificaciones</p></center>
                                                                      </li>
                                                                </ul>
                                                          </div>
                                                </div>

                                                <div class="scroll-element scroll-x">
                                                  <div class="scroll-element_outer">
                                                          <div class="scroll-element_size"></div>
                                                          <div class="scroll-element_track"></div>
                                                          <div class="scroll-bar" style="width: 96px;"></div>
                                                    </div>
                                                </div>

                                                <div class="scroll-element scroll-y">
                                                    <div class="scroll-element_outer">
                                                        <div class="scroll-element_size"></div>
                                                        <div class="scroll-element_track"></div>
                                                        <div class="scroll-bar" style="height: 96px;"></div>
                                                    </div>
                                                </div>
                                      </div>
                                  <!-- END List Group !-->

                              <!-- END Alert List !-->
                              </div>
                      </div>
                </div>
        </div>
      ';
    }
}