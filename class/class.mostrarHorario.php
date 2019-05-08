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
                                        <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="recursoId'.$action.'" id="recursoId'.$action.'" required>
                                            <option  value="0">Seleccione una opción</option>
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
                                            <input readonly="readonly" id="horaInicio'.$action.'" name="horaInicio'.$action.'" class="form-control timepicker" value="00:00" aria-invalid="false" type="text">
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
                                        <select multiple="multiple" title="" tabindex="-1" class="diasLaborables multiselect full-width select2-offscreen" data-placeholder="Selecciona un día" data-init-plugin="select2" name="diasLaborales'.$action.'[]" id="diasLaborales'.$action.'" required>
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