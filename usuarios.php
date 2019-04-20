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
      <?php Menus::menumostra($varUbicacion, $_SESSION['permissions'],$varUbicacion);?>
    </div>
    <div class="page-container">
    <?php Mostrargeneral::header(); ?>
        <div class="page-content-wrapper">
          <div class="content full-height">
            <?php Mostrargeneral::mostrarTituloPage($_SESSION['permissions'][$varUbicacion]['label']); ?>
            <div class="container-fluid full-height no-padding"> <!-- container-fluid container-fixed-lg -->
              <!-- Contenido Inicia -->
              <?php
                 //Este método se manda llamar desde la carpeta class/class.mostrarusuario.php
                 MostrarPerfilUsuario::MostrarPerfil();//Se carga el método formularioRegistroUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal del registro de usuarios.
                 MostrarUsuario::formularioRegistroUsuario();//Se carga el método formularioRegistroUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal del registro de usuarios.
                 MostrarUsuario::formularioEdicionUsuario();//Se carga el método formularioEdicionUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal de edición de usuarios.
                //  if($_SESSION['typeuser'] != "root"){
                //     echo '<div class="row full-height no-margin">
                //       <div class="col-md-9">';
                //   }                
                // *******************************
              ?>
              <div class="row" style="padding: 35px;background: #ffffff;margin-left: 5px;margin-right: 10px;border: 1px solid #e7e7e7;">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#formRegUsuario" id="regButtonUsuario">
                  <span class="fa fa-plus fa-x2"></span>&nbsp;Registrar Usuario
                </button>
                <br>
                <br>
                <div class=" table table-responsive">
                  <table id="usuarioSistemaDataTable" class="table table-hover " cellspacing="0" width="100%" style="background: #f0f0f0;">
                    <thead>
                      <tr id="TrUsuario">
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>                                                                          
                        <th>Usuario</th>
                        <th>Tipo de Usuario</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>                                                                  
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th>ID Usuario</th>
                        <th>Nombre</th>
                        <th>Apellidos</th>                                                                          
                        <th>Usuario</th>
                        <th>Tipo de Usuario</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>                                                        
                      </tr>
                    </tfoot>
                    <tbody id="contenidoListaUsuarioSistema">
                  
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
   

    <?php Mostrargeneral::mostrarjs($varUbicacion);?>
  </body>
</html>