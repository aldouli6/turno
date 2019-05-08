<?php
/*
* CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 07/04/19
 * PROJECT: turno
 *
 * DESCRIPTION:Muestra el contenido más importante del módulo de recusos tales como: Los modales de los formularios de registro y edición de srecursoes, así como los el datatable (solo encabezados y pies)
 *
 */
class MostrarRecursop{


    public static function modalRecurso($action, $name){
        echo '<div class="modal fade slide-up disable-scroll modalrecurso" id="form'.$name.'Recurso" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5>'.$name.' Recurso</h5>
                <p class="p-b-10">Formulario de recursos</p>
              </div>
              <div class="modal-body">
                <!-- START PANEL -->
                <div class="panel panel-transparent">
                  <div class="panel-body">
                    <form role="form" class="formRecurso" id="formRecurso'.$action.'" autocomplete="off" novalidate="novalidate">
                      <input type="hidden" name="establecimientoId'.$action.'" id="establecimientoId'.$action.'" value="'.$_SESSION['EstablecimientoID'].'">
                      <input type="hidden" name="cmd" value="recurso'.$name.'">
                      <input type="hidden" name="recursoId'.$action.'" id="recursoId'.$action.'">
                      <p>Datos principales</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                    <label >Nombre</label>
                                    <input type="text" class="form-control" name="recursoNombre'.$action.'" id="recursoNombre'.$action.'" required="" aria-required="true">
                                </div>
                            </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required disabled" aria-required="true">
                              <label>Cantidad</label>
                              <input type="number" class="form-control" value="1" readonly name="recursoCantidad'.$action.'" id="recursoCantidad'.$action.'" required="" aria-required="true">
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-6 form-group form-group-default input-group row">
                              <label class="inline" >Días de Asueto Oficiales</label>
                              <div id="divstatus1'.$action.'" class="col-sm-6" style="display: block;"><label class="inline" style="margin-top: 5px; margin-left: 12px" id="disasAsuetoOficialesSi" name="disasAsuetoOficialesSi">Habilitados</label></div>
                              <div id="divstatus2'.$action.'" class="col-sm-6" style="display: none;"><label class="inline" style="margin-top: 5px; margin-left: 12px" id="disasAsuetoOficialesNo" name="disasAsuetoOficialesNo">Deshabilitados</label></div>
                              <span class="input-group-addon bg-transparent col-sm-6">
                              <div class="lcs_wrap">
                                  <input name="diasAsueto'.$action.'" id="diasAsueto'.$action.'" class="lcs_check'.$action.'" type="checkbox">
                                  <div class="lcs_switch  lcs_checkbox_switch lcs_on">
                                  <div class="lcs_cursor"></div>
                                  </div>
                              </div>
                              </span>
                              <input type="hidden" name="diasAsuetoOficiales'.$action.'" id="diasAsuetoOficiales'.$action.'"/>
                          </div>
                          <div class="col-sm-6">
                              <div  class="form-group form-group-default  p-b-15" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                  <label >Días de Asueto Extra</label>
                                  <select title="" multiple="multiple"  tabindex="-1" class="multiselect full-width select2-offscreen" data-placeholder="Selecciona un día" data-init-plugin="select2" name="diasAsuetoExtra'.$action.'[]" id="diasAsuetoExtra'.$action.'" >
                                      
                                  </select>
                              </div>
                          </div>
                        </div>
                      </div>
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
      </div>       ';
    }
}