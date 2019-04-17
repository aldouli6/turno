<?php
$varUbicacion = 'recursos';
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
                            MostrarRecursop::modalRecurso('New','Registrar');
                            MostrarRecursop::modalRecurso('Edit','Editar');
                        ?>
                        <div class="row" style="padding: 35px;background: #ffffff;margin-left: 5px;margin-right: 10px;border: 1px solid #e7e7e7;">
                            <div class="col-sm-6">
                                <div class="row " >
                                    <button type="button" class="btn btn-primary btn-lg  h-100" data-toggle="modal" data-target="#formRegistrarRecurso" id="regButtonRecurso" style="height: 53px;">
                                    <span class="fa fa-plus fa-x2"></span>&nbsp;Registrar Recurso
                                    </button>
                                </div>
                                <br>
                                <br>
                                <div class=" table table-responsive">
                                <table id="tablaRecurso" class="table table-hover " cellspacing="0" width="100%" style="background: #f0f0f0;">
                                    <thead>
                                    <tr>
                                            <th colspan="3"  style="color: black;">Recursos</th>                                                                 
                                        </tr>
                                    <tr id="trRecurso">
                                        <th>Nombre</th>
                                        <th>Cantidad</th>   
                                        <th></th>
                                        <th></th>                                                                 
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>    
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                    <tbody id="contenidoRecurso">
                                
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="col-sm-6 hide" id="relRecursoTipoSesion">
                                <div class="row " >
                                    <form role="form" id="formNewRelRecSes" role="form" autocomplete="off">
                                        <input type="hidden" name="recursoId" id="recursoId" >
                                        <input type="hidden" name="cmd" value="addRelRecSesion" >
                                        <div class="col-sm-6">
                                        <div class="form-group-default required">
                                            <label style="font-weight: bold;">Tipo de Sesión</label>
                                            <select title="" tabindex="-1" class="full-width select2-offscreen" data-init-plugin="select2" name="sesionIdSelect" id="sesionIdSelect"  required>
                                                
                                            </select>
                                        </div>
                                        </div>
                                        <div class="col-sm-6" >
                                            <button type="submit" class="btn btn-primary btn-lg h-100" id="btnNewRecSesion" style="height: 53px;">
                                                <span class="fa fa-plus fa-x2"></span>&nbsp;Asignar Tipo de Sesión
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <br>
                                <br>
                                <div class=" table table-responsive">
                                    <table id="tableRelRecSesion" class="table table-hover " cellspacing="0" width="100%" style="background: #f0f0f0;">
                                        <thead>
                                        <tr>
                                            <th id="thRelRecSesion" colspan="3"  style="color: black;"></th>                                                                 
                                        </tr>
                                        <tr id="TrRelRecSes">
                                            <th>Nombre</th>
                                            <th>Eliminar</th>                                                                  
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Eliminar</th>                                                                  
                                        </tr>
                                        </tfoot>
                                        <tbody id="contenidoListaRelRecSesion">

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