<?php
$varUbicacion = 'tipoSesiones';
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
                    <div class="container-fluid full-height no-padding">
                        <?php
                            //Este método se manda llamar desde la carpeta class/class.mostrarusuario.php
                            MostrarPerfilUsuario::MostrarPerfil();//Se carga el método formularioRegistroUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal del registro de usuarios.
                            MostrarTipoSesion::modalTipoSesion('New','Registrar');
                            MostrarTipoSesion::modalTipoSesion('Edit','Editar');
                            // if($_SESSION['typeuser'] != "root"){
                            //     echo '<div class="row full-height no-margin">
                            //     <div class="col-md-9">';
                            // }                
                            // *******************************
                        ?>
                        <div class="row" style="padding: 35px;background: #ffffff;margin-left: 5px;margin-right: 10px;border: 1px solid #e7e7e7;">
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#formRegistrarSesion" id="regButtonSesion">
                            <span class="fa fa-plus fa-x2"></span>&nbsp;Registrar Tipo de Sesión
                            </button>
                            <br>
                            <br>
                            <div class=" table table-responsive">
                            <table id="tablaTipoSesion" class="table table-hover " cellspacing="0" width="100%" style="background: #f0f0f0;">
                                <thead>
                                <tr id="trTipoSesion">
                                    <th>Nombre</th>
                                    <th>Clientes por Sesión</th>                                                                          
                                    <th>Costo</th>
                                    <th>Duración</th>
                                    <th></th>
                                    <th></th>                                                                  
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Clientes por Sesión</th>                                                                          
                                    <th>Costo</th>
                                    <th>Duración</th>
                                    <th></th>
                                    <th></th>                                                        
                                </tr>
                                </tfoot>
                                <tbody id="contenidoTipoSesion">
                            
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    

                </div>
            </div>
        </div>
                            
    

        <?php Mostrargeneral::mostrarjs($varUbicacion);?>
        
    </body>
</html>