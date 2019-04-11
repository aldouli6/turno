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
                            if($_SESSION['typeuser'] != "root"){
                                echo '<div class="row full-height no-margin">
                                <div class="col-md-6">';
                            }                
                            // *******************************
                        ?>
                        <div class="row" style="padding: 35px;background: #ffffff;margin-left: 5px;margin-right: 10px;border: 1px solid #e7e7e7;">
                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#formRegistraSesion" id="regButtonSesion">
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
                    <div class="modal fade slide-up disable-scroll" id="formRegistraSesion" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5>Registrar Tipo de Sesión</h5>
                <p class="p-b-10">Formulario de Tipo de Sesiones</p>
              </div>
              <div class="modal-body">
                <!-- START PANEL -->
                <div class="panel panel-transparent">
                  <div class="panel-body">
                    <form role="form" id="formTipoSesionNew" autocomplete="off" novalidate="novalidate">
                      <input type="hidden" name="establecimientoIdNew" id="establecimientoIdNew" value=".$_SESSION['EstablecimientoID'].">
                      <p>Datos principales</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-12">
                            <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                              <label >Nombre</label>
                              <input type="text" class="form-control" name=SesionNombreNew" id=SesionNombreNew" required="" aria-required="true">
                            </div>
                          </div>
                        </div>
                        <div class="row clearfix">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label>Clientes por sesión</label>
                              <input type="number" class="form-control" name="clientesSesionNew" id="clientesSesionNew" required="" aria-required="true">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default " >
                              <label>Costo</label>
                              <input type="number" class="form-control" name="costoSesionNew" id="costoSesionNew">
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>
                      <p class="m-t-10">Datos de la cuenta</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label>Correo Electrónico</label>
                              <input type="email" class="form-control" name="regEmail" id="regEmail" required="" aria-required="true">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label class="fade">Contraseña</label>
                              <input type="password" class="form-control" name="regUsuarioPass" id="regUsuarioPass" required="" aria-required="true" aria-invalid="false" data-original-title="" title="">
                            </div>
                          </div>
                        </div>
                        <div class="row clearfix">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label>Teléfono</label>
                              <input type="text" class="form-control" name="regTelefono" id="regTelefono" required="" aria-required="true">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label class="fade">Username</label>
                              <input type="text" class="form-control" name="regUsername" id="regUsername" required="" aria-required="true" aria-invalid="false" data-original-title="" title="">
                            </div>
                          </div>
                        </div>
                        <div class="form-group form-group-default  required" aria-required="true">
                          <label>Tipo usuario</label>
                          <div class="select2-container full-width" id="s2id_regTipoUsuario" title=""><a href="javascript:void(0)" class="select2-choice" tabindex="-1">   <span class="select2-chosen" id="select2-chosen-1">Seleccione un tipo usuario</span><abbr class="select2-search-choice-close"></abbr>   <span class="select2-arrow" role="presentation"><b role="presentation"></b></span></a><label for="s2id_autogen1" class="select2-offscreen"></label><input class="select2-focusser select2-offscreen" type="text" aria-haspopup="true" role="button" aria-labelledby="select2-chosen-1" id="s2id_autogen1" tabindex="-1"><div class="select2-drop select2-display-none select2-with-searchbox">   <div class="select2-search">       <label for="s2id_autogen1_search" class="select2-offscreen"></label>       <input type="text" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="select2-input" role="combobox" aria-expanded="true" aria-autocomplete="list" aria-owns="select2-results-1" id="s2id_autogen1_search" placeholder="">   </div>   <ul class="select2-results" role="listbox" id="select2-results-1">   </ul></div></div><select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="regTipoUsuario" id="regTipoUsuario" required="" aria-required="true"><option value="0">-- Seleccione un tipo usuario --</option><option value="3">Usuario</option><option value="4">Cliente</option></select>
                        </div>
                      </div>
                      <br>
                      
                      <div class="form-group form-group-default input-group">
                        <div id="divstatus1" style="display: block;"><label class="inline" style="margin-top: 15px; margin-left: 12px" id="StatusUsuario1" name="StatusUsuario1">Habilitado</label></div>
                        <div id="divstatus2" style="display: none;"><label class="inline" style="margin-top: 15px; margin-left: 12px" id="StatusUsuario2" name="StatusUsuario2">Deshabilitado</label></div>
                        <span class="input-group-addon bg-transparent">
                          <div class="lcs_wrap">
                            <input name="StatusUsuario" id="StatusUsuario" class="lcs_check" type="checkbox">
                            <div class="lcs_switch  lcs_checkbox_switch lcs_on">
                              <div class="lcs_cursor"></div>
                            </div>
                          </div>
                        </span>
                        <input type="hidden" name="StatusUsuarioSistema" id="StatusUsuarioSistema" value="1">
                      </div>
                      <div class="row">
                        <div class="col-sm-6  m-t-10 sm-m-t-10">
                          <div class="pull-left">
                            <button class="btn btn-primary btn-lg" type="submit">
                            <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                            </button>
                          </div>
                        </div>
                        <div class="col-sm-6 m-t-10 sm-m-t-10">
                          <div class="pull-right">
                            <button type="button" class="btn btn-primary btn-lg" data-dismiss="modal">
                            <span class="glyphicon glyphicon-remove-sign"></span> Cerrar
                            </button>
                          </div>
                        </div>
                      </div>
                      <br>
                      <div id="mensajeGuardadoUsuario"></div>
                    </form>
                  </div>
                </div>
                <!-- END PANEL -->
              </div>
            </div>
          </div>
          <!-- /.end modal-content -->
        </div>
      </div>       

                </div>
            </div>
        </div>
                            
    

        <?php Mostrargeneral::mostrarjs($varUbicacion);?>
        
    </body>
</html>