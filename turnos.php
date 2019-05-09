<?php
$varUbicacion = 'turnos';
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
                    <div class="modal fade slide-up disable-scroll modalturno" id="formturno" tabindex="-1" role="dialog" aria-hidden="false">
                        <div class="modal-dialog ">
                        <div class="modal-content-wrapper">
                            <div class="modal-content">
                            <div class="modal-header clearfix text-left">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                                </button>
                                <h5 >Turnos del día</h5>
                                <p id="eldia" class="p-b-10"></p>
                            </div>
                            <div class="modal-body">
                                <!-- START PANEL -->
                                    <form role="form" class="formturno" id="formturno" autocomplete="off" novalidate="novalidate">
                                    <input type="hidden" name="establecimientoId" id="establecimientoId" value="<?=$_SESSION['EstablecimientoID']?>">
                                    <input type="hidden" name="cmd" value="turno'.$name.'">
                                    <input type="hidden" name="turnoId" id="turnoId">
                                    <div class="form-group-attached">
                                        <!-- <div class="row clearfix">
                                            <div class="col-sm-6">
                                                        <div class="p-r-5 p-l-5 m-b-20">
                                                            <label style="font-weight: bold;">Hora </label>
                                                    <div class="input-group bootstrap-timepicker">
                                                        <input id="hora" name="hora" class="form-control timepicker" value="00:00" aria-invalid="false" type="text">
                                                        <span class="input-group-addon"><i class="pg-clock"></i></span>
                                                    </div>
                                                </div>                            
                                            </div>
                                        </div> -->
                                        <div class="row clearfix">
                                            <div class="col-sm-5">
                                                <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                                    <label >Recurso</label>
                                                    <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Selecciona una opción" data-init-plugin="select2" name="recursoId" id="recursoId" required>
                                                        <option></option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                                    <label >Tipo de Sesión</label>
                                                    <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Selecciona una opción" data-init-plugin="select2" name="tipoSesionId" id="tipoSesionId" required>
                                                        <option></option>
                                                    </select>
                                                </div>
                                                <br>
                                                <div class="row pull-down">
                                                    <div class="col-sm-6  m-t-10 sm-m-t-10">
                                                    <div class="pull-left">
                                                        <button class="btn p-l-20 p-r-20  btn-primary btn-lg" type="submit">
                                                        <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                                                        </button>
                                                    </div>
                                                    </div>
                                                    <div class="col-sm-6 m-t-10 sm-m-t-10">
                                                    <div class="pull-right">
                                                        <button type="button" class="btn p-l-20 p-r-20 btn-primary btn-lg" data-dismiss="modal">
                                                        <span class="glyphicon glyphicon-remove-sign"></span> Cerrar
                                                        </button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-7">
                                                <div id="horasDia" class="cuerpo" ></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                    </div>
                                    <br>
                                    
                                    <br>
                                    <div id="mensajeGuardadoUsuario"></div>
                                    </form>
                                </div>
                                </div>
                        </div>
                        <!-- /.end modal-content -->
                        </div>
                    </div>       
                        <?php
                            MostrarPerfilUsuario::MostrarPerfil();
                        ?>
                        <input type="hidden" name="establecimientoId" id="establecimientoId" value="<?= $_SESSION['EstablecimientoID']?>">
                        
                        <div class="" style="padding: 15px;background: #ffffff;margin-left: 10px;margin-right: 10px;border: 1px solid #e7e7e7;">
                            
                            <div id="prueba" class=" m-t-15" style="min-height:500px;">
                                <div id="calendario" class="">
                                    <div id="nombreMes" class="">
                                    </div>
                                    <div id="year" class="">
                                    </div>
                                    <div id="nombreDiasSemana" style="display:flex" class=" justify-content-md-center">
                                        
                                    </div>
                                    <div id="fechas" class="">
                                    </div>
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
<style>
.hora{
    height: 6vh;
    background: #f0f0f0;
    color: #21242d;
    margin: 0 0.5vh 1vh 0.5vh !important;
    border-radius: 1vh;
    font-size: 2vh;
}
.hora>.col-sm-3{
    padding: 2vh 0;
    text-align: center;
}
#horasDia{
    height: 72vh;
    margin: 0 1vw;
    overflow-y: scroll;
}
.box {
  position: relative;
  width: 100%;
  /* desired width */
  /* margin: 5px; */
  
}
.box:before {
  content: "";
  display: block;
  padding-top: 100%;
  /* initial ratio of 1:1*/
}
.celda {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  color: #fff;
/*   display: flex;
  align-items: center; */
  line-height:100%;
  height:100%;
  text-align: center;
  display: flex;
  align-items: center;
  justify-content: center;
}

.mes{
    background: #019498;
    border-radius: 25px 25px 0px 0px;
    font-size:4.5vh;
}
.mes>div{
    width:20vw;
}
.icon:hover{
    background: #01868a;
}
.icon{
    font-size: 3vh;;
    cursor: pointer;
    padding:5px
}
.year{
    background: #019498;
    font-size:3.5vh;;
}
.numero{
    position: absolute;
    top: 1vh;
    right: 1vh;
}
.dia{
    background: #f0f0f0;
    color: #21242d;
    height: 9.5vh;
    margin: 1.5vh 0.5vh 0 0.5vh;
    border-radius: 1vh;
    font-size: 1.5vw;
}
.enmes:hover{
    background: #005194;
    height: 10vh;
    color: #fff;
    cursor: pointer;
}
.noenmes{
    background: #868484;
    color: #2c2c2c;
    cursor: not-allowed;
}
.titulos{
    background: #253056;
    font-size: 2.5vw;
}
.semana{
    background: #253056;
    color: #fff;
    height: 6.5vh;
    margin: 1.5vh 0.5vh 0 0.5vh;
    border-radius: 1vh;
    font-size: 2.5vw;
}
/* Other ratios */

.ratio4_1:before {
  padding-top: 5vh;
}

.ratio20_1:before {
  padding-top: 6vh;
}

.ratio4_3:before {
  padding-top: 75%;
}

.ratio16_9:before {
  padding-top: 11vh;
}
</style>