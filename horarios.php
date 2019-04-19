<?php
$varUbicacion = 'horarios';
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
                            MostrarPerfilUsuario::MostrarPerfil();
                            MostrarHorario::modalHorario('New','Registrar');
                            MostrarHorario::modalHorario('Edit','Editar');
                        ?>
                        <div class="row" style="padding: 35px;background: #ffffff;margin-left: 5px;margin-right: 10px;border: 1px solid #e7e7e7;">
                            <div class="row " >
                                <button type="button" class="btn btn-primary btn-lg  h-100" data-toggle="modal" data-target="#formRegistrarHorario" id="regButtonHorario" style="height: 53px;">
                                <span class="fa fa-plus fa-x2"></span>&nbsp;Registrar Horario
                                </button>
                            </div>
                            <br>
                            <br>
                            <div class=" table table-responsive">
                            <table id="tablaHorario" class="table table-hover " cellspacing="0" width="100%" style="background: #f0f0f0;">
                                <thead>
                                <tr id="trHorario">
                                    <th>Rercuso</th>
                                    <th>Dias Laborales</th>   
                                    <th>Hora Inicio</th>  
                                    <th>Hora Fin</th>  
                                    <th></th>
                                    <th></th>                                                                 
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Rercuso</th>
                                    <th>Dias Laborales</th>   
                                    <th>Hora Inicio</th>  
                                    <th>Hora Fin</th>  
                                    <th></th>
                                    <th></th> 
                                </tr>
                                </tfoot>
                                <tbody id="contenidoHorario">
                            
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