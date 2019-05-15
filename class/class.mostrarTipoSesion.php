<?php
/*
* CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 07/04/19
 * PROJECT: turno
 *
 * DESCRIPTION:Muestra el contenido más importante del módulo de tiposesiones tales como: Los modales de los formularios de registro y edición de tipossesiones, así como los el datatable (solo encabezados y pies)
 *
 */
class MostrarTipoSesion{


    public static function modalTipoSesion($action, $name){
        echo '<div class="modal fade slide-up disable-scroll modalsesion" id="form'.$name.'Sesion" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog ">
          <div class="modal-content-wrapper">
            <div class="modal-content">
              <div class="modal-header clearfix text-left">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                </button>
                <h5>'.$name.' Tipo de Sesión</h5>
                <p class="p-b-10">Formulario de Tipo de Sesiones</p>
              </div>
              <div class="modal-body">
                <!-- START PANEL -->
                <div class="panel panel-transparent">
                  <div class="panel-body">
                    <form role="form" class="formTipoSesion" id="formTipoSesion'.$action.'" autocomplete="off" novalidate="novalidate">
                      <input type="hidden" name="establecimientoId'.$action.'" id="establecimientoId'.$action.'" value="'.$_SESSION['EstablecimientoID'].'">
                      <input type="hidden" name="establecimientoStepping'.$action.'" id="establecimientoStepping'.$action.'" value="'.$_SESSION['EstablecimientoStepping'].'">
                      <input type="hidden" name="cmd" value="tipoSesion'.$name.'">
                      <input type="hidden" name="tipoSesionId'.$action.'" id="tipoSesionId'.$action.'">
                      <p>Datos principales</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-6">
                            <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                              <label >Nombre</label>
                              <input type="text" class="form-control" name="SesionNombre'.$action.'" id="SesionNombre'.$action.'" required="" aria-required="true">
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-group form-group-default " >
                              <label>Costo</label>
                              <input type="number" class="form-control" name="costoSesion'.$action.'" id="costoSesion'.$action.'">
                            </div>
                          </div>
                        </div>
                      </div>
                      <p class="m-t-10">Datos de la sesión</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-4">
                            <div class="p-r-5 p-l-5 m-b-20">
                              <label style="font-weight: bold;">Duración de sesión</label>
                              <div class="input-group bootstrap-timepicker">
                                  <input readonly="readonly" id="duracion'.$action.'" name="duracion'.$action.'" class="form-control timepicker" value="00:00" aria-invalid="false" type="text">
                                  <span class="input-group-addon"><i class="pg-clock"></i></span>
                              </div>
                            </div>                            
                          </div>
                          <div class="col-sm-4 ">
                            <div class="p-r-5 p-l-5 m-b-20">
                              <label style="font-weight: bold;">Tiempo de espera</label>
                              <div class="input-group bootstrap-timepicker">
                                  <input readonly="readonly" id="tiempoEspera'.$action.'" name="tiempoEspera'.$action.'" class="form-control timepicker" value="00:00" aria-invalid="false" type="text">
                                  <span class="input-group-addon"><i class="pg-clock"></i></span>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-4 ">
                            <div class="p-r-5 p-l-5 m-b-20">
                              <label style="font-weight: bold;">Tiempo entre sesión</label>
                              <div class="input-group bootstrap-timepicker">
                                  <input readonly="readonly" id="tiempEntreSesion'.$action.'" name="tiempEntreSesion'.$action.'" class="form-control timepicker" value="00:00" aria-invalid="false" type="text">
                                  <span class="input-group-addon"><i class="pg-clock"></i></span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <p class="m-t-10">Tiempo máximo para agendar</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label style="font-weight: bold;">Días</label>
                              <select title="" tabindex="-1" class="sesentas full-width select2-offscreen" data-init-plugin="select2" name="maximoAgendarDias'.$action.'" id="maximoAgendarDias'.$action.'" required>
                                
                              </select></div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label style="font-weight: bold;">Horas</label>
                              <select title="" tabindex="-1" class="veintitres full-width select2-offscreen" data-init-plugin="select2" name="maximoAgendarHoras'.$action.'" id="maximoAgendarHoras'.$action.'" required>
                                
                              </select></div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label style="font-weight: bold;">Minutos</label>
                              <select title="" tabindex="-1" class="sesentas full-width select2-offscreen" data-init-plugin="select2" name="maximoAgendarMins'.$action.'" id="maximoAgendarMins'.$action.'" required>
                                
                              </select></div>
                          </div>
                          
                        </div>
                      </div>
                      <p class="m-t-10">Tiempo límite antes de agendar</p>
                      <div class="form-group-attached">
                        <div class="row clearfix">
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label style="font-weight: bold;">Días</label>
                              <select title="" tabindex="-1" class="sesentas full-width select2-offscreen" data-init-plugin="select2" name="limiteAntesAgendarDias'.$action.'" id="limiteAntesAgendarDias'.$action.'" required>
                                
                              </select></div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label style="font-weight: bold;">Horas</label>
                              <select title="" tabindex="-1" class="veintitres full-width select2-offscreen" data-init-plugin="select2" name="limiteAntesAgendarHoras'.$action.'" id="limiteAntesAgendarHoras'.$action.'" required>
                                
                              </select></div>
                          </div>
                          <div class="col-sm-4">
                            <div class="form-group form-group-default required" aria-required="true">
                              <label style="font-weight: bold;">Minutos</label>
                              <select title="" tabindex="-1" class="sesentas full-width select2-offscreen" data-init-plugin="select2" name="limiteAntesAgendarMins'.$action.'" id="limiteAntesAgendarMins'.$action.'" required>
                                
                              </select></div>
                          </div>
                        </div>
                      </div>
                      <br>
                      <div class="form-group-attached">
                        <div class="row">
                          <div class="col-sm-6 pull-left">
                            <label style="font-weight: bold;">Fecha límite de este tipo de sesión</label>
                              <div class="input-group bootstrap-timepicker">
                                  <input id="fechaFinSesion'.$action.'" name="fechaFinSesion'.$action.'" class="form-control datepicker" aria-invalid="false" type="text">
                                  <span class="input-group-addon"><i class="pg-clock"></i></span>
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