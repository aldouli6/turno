<?php
/*
* CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 23/03/19
 * PROJECT: turno
 *
 * DESCRIPTION:Muestra el contenido más importante del módulo de usuarios tales como: Los modales de los formularios de registro y edición de usuarios, así como los el datatable (solo encabezados y pies)
 *
 */


class MostrarUsuario{


   public static function formularioRegistroUsuario(){

        //Muestra en el módulo de usuario el modal form del registro de usuarios

      echo '<!--  start modal registro usuario -->
      <div class="modal fade slide-up disable-scroll" id="formRegUsuario" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5>Registrar usuario</h5>
                <p class="p-b-10">Formulario de registro de usuarios</p>
              </div>
              <div class="modal-body">
                <!-- START PANEL -->
                <div class="panel panel-transparent">
                  <div class="panel-body">
                    <form role="form" id="formRegistroUsuario" role="form" autocomplete="off">
                      <input type="hidden" name="regEstablecimientoId" id="regEstablecimientoId" value="'.$_SESSION["EstablecimientoID"].'">
                      <p>Datos personales</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Nombre(s)</label>
                              <input type="text" class="form-control" name="regUsuarioNombre" id="regUsuarioNombre" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Apellido(s)</label>
                              <input type="text" class="form-control" name="regUsuarioApellidos" id="regUsuarioApellidos" required>
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>
                      <p class="m-t-10">Datos de la cuenta</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Correo Electrónico</label>
                              <input type="email" class="form-control" name="regEmail" id="regEmail" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Contraseña</label>
                              <input type="password" class="form-control" name="regUsuarioPass" id="regUsuarioPass" required>
                            </div>
                          </div>
                        </div>
                        <div class="row clearfix">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Teléfono</label>
                              <input type="text" class="form-control" name="regTelefono" id="regTelefono" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Username</label>
                              <input type="text" class="form-control" name="regUsername" id="regUsername" required>
                            </div>
                          </div>
                        </div>
                        <div class="form-group form-group-default  required">
                          <label >Tipo usuario</label>
                          <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="regTipoUsuario" id="regTipoUsuario" required>
                            <option value="0">Seleccione un tipo usuario</option>
                          </select>
                        </div>
                      </div>
                      <br>
                      
                      <div class="form-group form-group-default input-group">
                        <div id="divstatus1" style="display: block;"><label class="inline" style="margin-top: 15px; margin-left: 12px" id="StatusUsuario1" name="StatusUsuario1">Habilitado</label></div>
                        <div id="divstatus2" style="display: none;"><label class="inline" style="margin-top: 15px; margin-left: 12px" id="StatusUsuario2" name="StatusUsuario2">Deshabilitado</label></div>
                        <span class="input-group-addon bg-transparent">
                          <div class="lcs_wrap">
                            <input name="StatusUsuario" id="StatusUsuario" class="lcs_check'.$action.'" type="checkbox">
                            <div class="lcs_switch  lcs_checkbox_switch lcs_on">
                              <div class="lcs_cursor"></div>
                            </div>
                          </div>
                        </span>
                        <input type="hidden" name="StatusUsuarioSistema" id="StatusUsuarioSistema"/>
                      </div>
                      <div class="row">
                        <div class="col-sm-6  m-t-10 sm-m-t-10">
                          <div class="pull-left">
                            <button  class="btn btn-primary btn-lg"  type="submit">
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
      <!-- /. end modal-dialog  formEditUsuario,  formEdicionUsuario-->';
   }





   public static function formularioEdicionUsuario(){
    //Mostrará en el módulo de usuario el modal form de edición del usuario
    echo '
        <!--  start modal edicion usuario -->
        <div class="modal fade slide-up disable-scroll" id="formEditUsuario" tabindex="-1" role="dialog" aria-hidden="false">
          <div class="modal-dialog ">
            <div class="modal-content-wrapper">
              <div class="modal-content">
                <div class="modal-header clearfix text-left">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                  </button>
                  <h5>Editar usuario</h5>
                  <p class="p-b-10">Formulario de edición de usuarios</p>
                </div>
                <div class="modal-body">
                  <!-- START PANEL -->
                  <div class="panel panel-transparent">
                    <div class="panel-body">
                      <form role="form" id="formEdicionUsuario" role="form" autocomplete="off">
                      <input type="hidden" name="editEstablecimientoId" id="editEstablecimientoId" value="'.$_SESSION["EstablecimientoID"].'">
                        <p>Datos personales</p>
                        <div class="form-group-attached">
                          <div class="row clearfix">
                            <div class="col-sm-6">
                              <div class="form-group form-group-default">
                                <label>Nombre(s)</label>
                                <input type="text" class="form-control" name="editUsuarioNombre" id="editUsuarioNombre" required>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group form-group-default">
                                <label>Apellido(s)</label>
                                <input type="text" class="form-control" name="editUsuarioApellidos" id="editUsuarioApellidos" required>
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <p class="m-t-10">Datos de la cuenta</p>
                        <div class="form-group-attached">
                          <div class="row clearfix">
                            <div class="col-sm-12">
                              <div class="form-group form-group-default required">
                                <label>Correo Electrónico</label>
                                <input type="email" class="form-control" name="editUsuarioEmail" id="editUsuarioEmail" required>
                              </div>
                            </div>
                          </div>
                          <div class="row clearfix">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Teléfono</label>
                              <input type="text" class="form-control" name="editTelefono" id="editTelefono" required>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required">
                              <label>Username</label>
                              <input type="text" class="form-control" name="editUsername" id="editUsername" required>
                            </div>
                          </div>
                        </div>
                          <div class="form-group form-group-default  required">
                            <label>Tipo usuario</label>
                            <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="editTipoUsuarios" id="editTipoUsuarios" required>
                              <option value="0">Seleccione un tipo usuario</option>
                            </select>
                          </div>
                        </div>
                        <br>
                        <div class="form-group form-group-default input-group">
                          <div id="divstatus1Edit" style="display: block;">
                            <label class="inline" style="margin-top: 15px; margin-left: 12px" id="StatusUser1Edit" name="StatusUser1Edit">Habilitado</label>
                          </div>
                          <div id="divstatus2Edit" style="display: none;">
                            <label class="inline" style="margin-top: 15px; margin-left: 12px" id="StatusUser2Edit" name="StatusUser2Edit">Deshabilitado</label>
                          </div>
                          <span class="input-group-addon bg-transparent">
                            <div class="lcs_wrap">
                              <input name="editStatusUser" id="editStatusUser" class="lcs_checkEdit" type="checkbox">
                              <div class="lcs_switch  lcs_checkbox_switch lcs_on">
                                <div class="lcs_cursor">
                                </div>
                              </div>
                            </div>
                          </span>
                          <input type="hidden" name="EditStatusUsuarioSistema" id="EditStatusUsuarioSistema" />
                        </div>
                        <input type="hidden" id="idUsuarioSistemaEditar" name="idUsuarioSistemaEditar" />
                        <br>
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
        <!-- /. end modal-dialog -->
      ';
   }
// ..................................................................

}

?>
