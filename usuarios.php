<?php
$varUbicacion = 'usuarios';
include_once("./class/class.brain.php");
Sessions::validarUsuario();
Sessions::validateType($_SESSION["typeuser"],$varUbicacion);
?>
<!DOCTYPE html>
<html>
  <head>
  	<?php
  	Mostrargeneral::mostrarTitulo($varUbicacion);
  	Mostrargeneral::mostrarcss($varUbicacion);
    Mostrargeneral::mostrarfixeds();
    ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" />
  </head>
  <body class="fixed-header menu-behind">
    <div class="page-sidebar" data-pages="sidebar">
      <?php Mostrargeneral::menumobile(); ?>
      <?php Mostrargeneral::encabezadoLogo(); ?>
      <?php Menus::menumostra($_SESSION['typeuser'], $_SESSION['permissions'],$varUbicacion);?>
    </div>
    <div class="page-container">
      <div class="header ">
        <div class="container-fluid relative">
          <div class="pull-left full-height visible-sm visible-xs">
            <?php Mostrargeneral::actionBar(); ?>
          </div>
            <?php Mostrargeneral::logoLg(); ?>
          </div>
          <div class=" pull-left sm-table hidden-xs hidden-sm">
            <div class="header-inner">
              <?php Mostrargeneral::logoSm(); ?>
            </div>
          </div>
          <div class=" pull-right">
            <?php MostrarPlugins::mostrarConfigUser(); ?>          
          </div>
        </div>
        <div class="page-content-wrapper">
          <div class="content full-height">
            <?php Mostrargeneral::mostrarTituloPage($varUbicacion); ?>
            <div class="container-fluid full-height no-padding"> <!-- container-fluid container-fixed-lg -->
              <!-- Contenido Inicia -->
              <?php
                 //Este método se manda llamar desde la carpeta class/class.mostrarusuario.php
                 MostrarPerfilUsuario::MostrarPerfil();//Se carga el método formularioRegistroUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal del registro de usuarios.
                 //MostrarUsuario::formularioRegistroUsuario();//Se carga el método formularioRegistroUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal del registro de usuarios.
                 //MostrarUsuario::formularioEdicionUsuario();//Se carga el método formularioEdicionUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal de edición de usuarios.
                 if($_SESSION['typeuser'] != "root"){
                    echo '<div class="row full-height no-margin">
                      <div class="col-md-9">';
                  }                
                // *******************************
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
   

    <?php Mostrargeneral::mostrarjs($varUbicacion);?>
  </body>
</html>