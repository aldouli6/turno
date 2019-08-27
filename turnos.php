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
                                    <form role="form" class="formturno" id="formaturno" autocomplete="off" novalidate="novalidate">
                                        <div class="modal-header clearfix text-left">
                                            <div class="row">
                                                <div class="col-sm-1">
                                                <i target="" id="prevday" class="iconmod fa fa-chevron-left" aria-hidden="true"></i>
                                                </div>
                                                <div class="col-sm-5">
                                                    <h5 >Turnos del día</h5>
                                                    <p id="eldia" class="p-b-10"></p>
                                                </div>
                                                <div class="col-sm-5">
                                                    <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                                        <label >Tipo de Sesión</label>
                                                        <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Selecciona una opción" data-init-plugin="select2" name="tipoSesionId" id="tipoSesionId" required>
                                                            <option></option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                <i target="" id="nextday" class="iconmod fa fa-chevron-right" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="modal-body">
                                        <!-- START PANEL -->
                                        
                                            <input type="hidden" name="establecimientoId" id="establecimientoId" value="<?=$_SESSION['EstablecimientoID']?>">
                                            <input type="hidden" name="cmd" value="registrarTurno">
                                            <input type="hidden" name="turnoId" id="turnoId" value="0">
                                            <div class="form-group-attached">
                                                <div id="horasDia" class="cuerpo" ></div>
                                            </div>
                                        
                                    </div>
                                    <div id="mensajeGuardadoUsuario"></div>
                                    <div class="modal-footer" style="text-align:left !important;">
                                        <div id="datosocultos" class="hide">
                                            <div class="row  pull-down">
                                                <div class="col-sm-6">
                                                    <label>Buscar usuario por:</label>
                                                    <div  id= "buttongroup" class="btn-group btn-group-xs">
                                                        <button type="button" id="porusername" class="busquedapor btn btn-primary">Usuario</button>
                                                        <button type="button" id="pornombre" class="busquedapor btn btn-primary">Nombre</button>
                                                        <button type="button" id="portelefono" class="busquedapor btn btn-primary">Teléfono</button>
                                                        <button type="button" id="poremail" class="busquedapor btn btn-primary">Email</button>
                                                    </div>
                                                    <br>
                                                    <div id="selectdeltipo" >
                                                        <div class="form-group form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                                            <label id="labelselect" ></label>
                                                            <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Selecciona una opción" data-init-plugin="select2" name="usuarioId" id="usuarioId" required>
                                                                <option></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" style="top: 56px;">
                                                    <div class="form-group form-group-default required" id='divrecursoId' aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                                        <label >Recurso</label>
                                                        <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Selecciona una opción" data-init-plugin="select2" name="recursoId" id="recursoId" required>
                                                            <!-- <option></option> -->
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="horaInicio" id="horaInicio">
                                        <input type="hidden" name="horaFin" id="horaFin">
                                        <input type="hidden" name="fecha" id="fecha">
                                        <input type="hidden" name="estatusId" id="estatusId">
                                        <div class="row pull-down">
                                            <div class="col-sm-6  m-t-10 sm-m-t-10">
                                                <div class="pull-left">
                                                    <button id="submitBtn" class="btn p-l-20 p-r-20  btn-primary btn-lg" type="submit">
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
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>       
                        <?php
                            MostrarPerfilUsuario::MostrarPerfil();
                        ?>
                        <input type="hidden" name="establecimientoId" id="establecimientoId" value="<?= $_SESSION['EstablecimientoID']?>">
                        <input type="hidden" name="establecimientoStepping" id="establecimientoStepping" value="<?= $_SESSION['EstablecimientoStepping']?>">
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
#selectdeltipo > div{
    margin: 1vh 0px;
}
::-webkit-scrollbar {
    display: none;
}
.rec-disponible{
    background: #868484;
    color: #ccc;
    border-radius: .5vh;  
    font-size: 1.3vw;
    margin: .5vh !important;
    min-width: fit-content;
    padding: 0 .3vh !important
}
.rec{
    border-radius: .5vh;  
    font-size: 1.3vw;
    margin: .5vh !important;
    min-width: fit-content;
    padding: 0 .3vh !important
}
.contenido{
    display: flex;
    overflow-x: scroll;
}
.nopermitido{
    background: #f76b68 !important;
}
.bloqueado{;
    background: #f76b68 ;
    color: #2c2c2c;
    cursor: not-allowed;
    border-radius: .5vh;
}
.momentoenabled{
    height: 6vh;
    background: #f0f0f0;
    color: #21242d;
    margin: 0.5vh !important;
    border-radius: 1vh;
    font-size: 2vh;
}

.momentodisabled{
    background: #868484;
    color: #2c2c2c;
    cursor: not-allowed;
    border-radius: .5vh;
}
.rec-agendado{
    background: #55be6e;
    color: #2c2c2c;
    cursor: pointer;
}
.rec-atendiendo{
    background: #dac73d;
    color: #2c2c2c;
    cursor: pointer;
}
.rec-atendido{
    background: #005194;
    color: #ffffff;
    cursor: pointer;
}
/* .recagendado:hover{
    -webkit-box-shadow: 0px 0px 10px 0px rgb(83, 234, 119);
    -moz-box-shadow: 0px 0px 10px 0px rgb(83, 234, 119);
    box-shadow: 0px 0px 10px 0px rgb(83, 234, 119);
} */
.minicio{
    border-radius: 1vh 1vh 0px 0px;
    margin-top: 0.5vh !important;
}
.mfin{
    border-radius: 0px 0px 1vh 1vh ;
    margin-bottom: 0.5vh !important;
}
.momentoenabled>.col-sm-3{
    padding: 1vh 0;
    text-align: center;
}
#horasDia{
    height: 50vh;
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
.iconmod:hover{
    zoom:1.3;
}
.iconmod{
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
.mayorhoy:hover{
    background: #005194;
    height: 10vh;
    color: #fff;
    cursor: pointer;
}
.momentoduracionhover{
    background-color: #005194;
    color: #fff
}
.momentoanteshover{
    background-color: #019498;
    color: #fff
}
.momentodespueshover{
    background-color: #253056;
    color: #fff
}
.momentoselected{
    -webkit-box-shadow: inset 0px 0px 14px 6px rgba(0,0,0,0.49);
    -moz-box-shadow: inset 0px 0px 14px 6px rgba(0,0,0,0.49);
    box-shadow: inset 0px 0px 14px 6px rgba(0,0,0,0.49);
}

.menorhoy{
    background: #868484;
    color: #2c2c2c;
    cursor: not-allowed;
}
.noenmes{
    background: #fff;
    color: #fff;
    cursor: auto;
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