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
class MostrarHorario{


    public static function modalHorario($action, $name){
        echo '<div class="modal fade slide-up disable-scroll modalhorario" id="form'.$name.'Horario" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5>'.$name.' Horario</h5>
                <p class="p-b-10">Formulario de horarios</p>
              </div>
              <div class="modal-body">
                <!-- START PANEL -->
                <div class="panel panel-transparent">
                  <div class="panel-body">
                    <form role="form" class="formHorario" id="formHorario'.$action.'" autocomplete="off" novalidate="novalidate">
                        <input type="hidden" name="establecimientoId'.$action.'" id="establecimientoId'.$action.'" value="'.$_SESSION['EstablecimientoID'].'">
                        <input type="hidden" name="cmd" value="horario'.$name.'">
                        <input type="hidden" name="horarioId'.$action.'" id="horarioId'.$action.'">
                        <p>Datos principales</p>
                        <div class="form-group-attached">
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                        <label >Recurso</label>
                                        <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="recurso'.$action.'" id="recurso'.$action.'" required>
                                            <option value="0">Seleccione un recurso</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row clearfix">
                                <div class="col-sm-6">
                                    <div class="p-r-5 p-l-5 m-b-20">
                                        <label style="font-weight: bold;">Hora Inicio</label>
                                        <div class="input-group bootstrap-timepicker">
                                            <input id="horaInicio'.$action.'" name="horaInicio'.$action.'" class="form-control timepicker" value="00:00" aria-invalid="false" type="text">
                                            <span class="input-group-addon"><i class="pg-clock"></i></span>
                                        </div>
                                    </div>                            
                                </div>
                                <div class="col-sm-6 ">
                                    <div class="p-r-5 p-l-5 m-b-20">
                                        <label style="font-weight: bold;">Hora Final</label>
                                        <div class="input-group bootstrap-timepicker">
                                            <input id="horaFin'.$action.'" name="horaFin'.$action.'" class="form-control timepicker" value="00:00" aria-invalid="false" type="text">
                                            <span class="input-group-addon"><i class="pg-clock"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-12">
                                    <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                        <label >Días Laborales</label>
                                        <select multiple="multiple" title="" tabindex="-1" class="multiselec full-width select2-offscreen" data-placeholder="Selecciona un día" data-init-plugin="select2" name="diasLaborales'.$action.'[]" id="diasLaborales'.$action.'" required>
                                            <option value="D">Domingo</option>
                                            <option value="L">Lunes</option>
                                            <option value="M">Martes</option>
                                            <option value="X">Miércoles</option>
                                            <option value="J">Jueves</option>
                                            <option value="V">Viernes</option>
                                            <option value="S">Sábado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-sm-6 form-group form-group-default input-group row">
                                    <label class="inline" >Días de Asueto Oficiales</label>
                                    <div id="divstatus1" class="col-sm-6" style="display: block;"><label class="inline" style="margin-top: 5px; margin-left: 12px" id="disasAsuetoOficialesSi" name="disasAsuetoOficialesSi">Habilitados</label></div>
                                    <div id="divstatus2" class="col-sm-6" style="display: none;"><label class="inline" style="margin-top: 5px; margin-left: 12px" id="disasAsuetoOficialesNo" name="disasAsuetoOficialesNo">Deshabilitados</label></div>
                                    <span class="input-group-addon bg-transparent col-sm-6">
                                    <div class="lcs_wrap">
                                        <input name="diasAsueto'.$action.'" id="diasAsueto'.$action.'" class="lcs_check" type="checkbox">
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