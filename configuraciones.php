<?php
$varUbicacion = 'configuraciones';
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
            <?php Mostrargeneral::header($_SESSION['EstablecimientoID']); ?>
            
            <div class="page-content-wrapper">
                <div class="content full-height">
                    <?php Mostrargeneral::mostrarTituloPage($_SESSION['permissions'][$varUbicacion]['label']); ?>
                    <div id="elnewbody" class="container-fluid full-height no-padding" style="position: relative;">
                        <?php
                            MostrarPerfilUsuario::MostrarPerfil($_SESSION['EstablecimientoID']);
                        ?>
                        <input type="hidden" name="establecimientoId" id="establecimientoId" value="<?= $_SESSION['EstablecimientoID']?>">
                        
                        
                        <div class="row" style="padding: 35px;background: #ffffff;margin-left: 5px;margin-right: 10px;border: 1px solid #e7e7e7;">
                            <ul class="nav nav-tabs">
                                <li class="active" ><a data-toggle="tab" href="#nombrecontacto">Nombre y Contacto</a></li>
                                <li  ><a data-toggle="tab" href="#ubicacion">Ubicación</a></li>
                                <li ><a data-toggle="tab" href="#generales">Generales</a></li>
                                <li><a data-toggle="tab" href="#notificaciones">Notificaciones</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="nombrecontacto" class="tab-pane fade in active">
                                <div class="row">
                                    <div class="col-sm-1">
                                            <h3 class="">
                                            <div id="switch_nombrecontacto" class="lcs_wrap">
                                                <input name="nombrecontacto_switch" id="nombrecontacto_switch" class="lcs_check" type="checkbox">
                                                <div class="lcs_switch  lcs_checkbox_switch lcs_on">
                                                    <div class="lcs_cursor"></div>
                                                </div>
                                            </div>
                                            </h3>
                                    </div>
                                    <div class="col-sm-6"><h3>Nombre y Contacto del Establecimiento</h3></div>
                                </div>
                                 
                                    <form role="form" class="formnombrecontacto " id="formnombrecontacto" autocomplete="off" novalidate="novalidate" >
                                    <input type="hidden" name="cmd" id="cmd" value="editnombrecontacto">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="row  ">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default required">
                                                            <label>Nombre</label>
                                                            <input type="text" class="form-control" name="nombre" id="nombre"  required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row  ">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default required">
                                                                <label>Correo Electrónico</label>
                                                                <input type="email" class="form-control" name="emailEstablecimiento" id="emailEstablecimiento" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default required">
                                                                <label>Teléfono</label>
                                                                <input type="text" class="form-control" name="telefonoEstablecimiento" id="telefonoEstablecimiento" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row  ">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default  required">
                                                                <label >Categoría</label>
                                                                <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Seleccione una categoría" data-init-plugin="select2" name="categoria" id="categoria" required>
                                                                   <option ></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row  ">
                                                        <div class="col-sm-12" disabled>
                                                            <div class="form-group form-group-default  required">
                                                                <label >Subcategoría</label>
                                                                <select disabled title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Seleccione una subcategoría" data-init-plugin="select2" name="subcategoria" id="subcategoria" required>
                                                                <option ></option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6  m-t-10 sm-m-t-10">
                                                            <div class="pull-left">
                                                                <button class="btn btn-primary btn-lg" type="submit">
                                                                <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                                                                </button>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row  ">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default ">
                                                            <label>Descripción</label>
                                                            <input type="text" class="form-control" name="descripcion" id="descripcion"  >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row  ">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default ">
                                                                <label>Imagen de perfil</label>
                                                                <div class="row ">
                                                                    <div class="col-xs-10">
                                                                        <button class="btn btn-primary m-t-10 m-b-10" onclick="$('#myDropzone').show(); return false;">
                                                                            <span class="glyphicon glyphicon-repeat"></span> Cambiar imagen
                                                                        </button>
                                                                    </div>
                                                                    <?php 
                                                                    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/turno/assets/img/profiles/estab_".$_SESSION['EstablecimientoID'].".png";
                                                                    ?>
                                                                    <div class="col-xs-2 imgProfile muestra" style="background-image: url( <?=$actual_link?>);">
                                                                    </div>
                                                                </div>
                                                                <div class="row ">
                                                                    <div class="dropzone" id="myDropzone" style="display:none;"></div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                       
                                    </form>
                                      
                                </div>
                                <div id="ubicacion" class="tab-pane fade ">
                                    <div class="row">
                                        <div class="col-sm-1">
                                                <h3 class="">
                                                    <div id="switch_ubicacion" class="lcs_wrap">
                                                        <input name="ubicacion_switch" id="ubicacion_switch" class="lcs_check" type="checkbox">
                                                        <div class="lcs_switch  lcs_checkbox_switch lcs_on">
                                                            <div class="lcs_cursor"></div>
                                                        </div>
                                                    </div>
                                                </h3>
                                        </div>
                                        <div class="col-sm-6"><h3>Ubicación</h3></div>
                                    </div>
                                    <form role="form" class="formubicacion" id="formubicacion" autocomplete="off" novalidate="novalidate">
                                    <input type="hidden" name="cmd" id="cmd" value="editubicacion">
                                        <div class="row ">
                                            <div class="col-sm-6">
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default required">
                                                            <label>Calle</label>
                                                            <input type="text" class="form-control" name="calle" id="calle" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default required">
                                                            <label>Número Exterior</label>
                                                            <input type="text" class="form-control" name="numeroExt" id="numeroExt" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default ">
                                                            <label>Número Interior</label>
                                                            <input type="text" class="form-control" name="numeroInt" id="numeroInt" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default  ">
                                                            <label >Colonia</label>
                                                            <select disabled title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Seleccione una colonia" data-init-plugin="select2" name="colonia" id="colonia" >
                                                                <option ></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default required">
                                                            <label>Código Postal</label>
                                                            <input type="text" class="form-control" name="codigoPostal" id="codigoPostal" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default  required">
                                                            <label >Estado</label>
                                                            <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Seleccione un estado" data-init-plugin="select2" name="estado" id="estado" required>
                                                                <option ></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default  required">
                                                            <label >Ciudad</label>
                                                            <select disabled title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Seleccione una ciudad" data-init-plugin="select2" name="ciudad" id="ciudad" required>
                                                                <option ></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default ">
                                                            <label>Latitud</label>
                                                            <input type="text" class="form-control" name="latitud" id="latitud" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default ">
                                                            <label>Longitud</label>
                                                            <input type="text" class="form-control" name="longitud" id="longitud" >
                                                        </div>
                                                    </div>
                                                </div>
                                               
                                                <div class="row ">
                                                    <div class="col-sm-6  m-t-10 sm-m-t-10">
                                                        <div class="pull-left">
                                                            <button class="btn btn-primary btn-lg" type="submit">
                                                            <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </form>  
                                        </div>
                            
                                        <div class="col-sm-6">
                                        
                                            <div id="elmapa" class="col-sm-12" style="
                                                height: 60vh;
                                                background: gainsboro;
                                            "></div>
                                        </div>
                                        
                                    </div>
                                </div>
                                <div id="generales" class="tab-pane fade ">
                                    
                                    <div class="row">
                                        <div class="col-sm-1">
                                                <h3 class="">
                                                    <div id="switch_generales" class="lcs_wrap">
                                                        <input name="generales_switch" id="generales_switch" class="lcs_check" type="checkbox">
                                                        <div class="lcs_switch  lcs_checkbox_switch lcs_on">
                                                            <div class="lcs_cursor"></div>
                                                        </div>
                                                    </div>
                                                </h3>
                                        </div>
                                        <div class="col-sm-6"><h3>Generales</h3></div>
                                    </div>
                                    <form role="form" class="formgenerales" id="formgenerales" autocomplete="off" novalidate="novalidate">
                                    <input type="hidden" name="cmd" id="cmd" value="editgenerales">
                                        <div class="row ">
                                            <div class="col-sm-6">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default required">
                                                            <label>Cada cuantos minutos</label>
                                                            <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Selecciona una opción" data-init-plugin="select2" name="stepping" id="stepping" required>
                                                                <option></option>
                                                                <option value="5">5 minutos</option>
                                                                <option value="10">10 minutos</option>
                                                                <option value="15">15 minutos</option>
                                                                <option value="20">20 minutos</option>
                                                                <option value="30">30 minutos</option>
                                                                <option value="60">60 minutos</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default "  data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                                            <label >Días Feriados extra</label>
                                                            <select multiple="multiple" title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Selecciona un día" data-init-plugin="select2" name="diasFeriadosExtra[]" id="diasFeriadosExtra" >
                                                                
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-sm-12" disabled>
                                                        <div class="form-group p-b-10 p-t-10 form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                                            <div class="row">
                                                                <div class="col-sm-9">
                                                                    <div id="divasueto1" style="display: block;"><label class="inline" style="margin-top: 15px; margin-left: 12px" id="siAsuetoOficial" name="siAsuetoOficial">Días asueto oficial habilitados</label></div>
                                                                    <div id="divasueto2" style="display: none;"><label class="inline" style="margin-top: 15px; margin-left: 12px" id="noAsuetoOficial" name="noAsuetoOficial">Días asueto oficial deshabilitados </label></div>
                                                                 </div>
                                                                <div class="col-sm-3">
                                                                    <div class="lcs_wrap">
                                                                        <input name="asuetoOficial" id="asuetoOficial" class="lcs_checkasueto" type="checkbox">
                                                                        <div class="lcs_switch  lcs_checkbox_switch lcs_on" id="asuetoOficialcheck">
                                                                        <div class="lcs_cursor"></div>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="asuetoOficial1" id="asuetoOficial1"/>
                                                                </div>
                                                            </div>
                                                            
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-sm-12" disabled>
                                                        <div class="form-group p-b-10 p-t-10 form-group-default required" aria-required="true" data-toggle="tooltip" data-placement="top" title="Este es el nombre">
                                                            <div class="row">
                                                                <div class="col-sm-9">
                                                                    <div id="divtooltip1" style="display: block;"><label class="inline" style="margin-top: 15px; margin-left: 12px" id="tooltipsi" name="tooltipsi">Tooltips ayuda habilitados</label></div>
                                                                    <div id="divtooltip2" style="display: none;"><label class="inline" style="margin-top: 15px; margin-left: 12px" id="tooltipno" name="tooltipno">Tooltips ayuda deshabilitados </label></div>
                                                                </div>
                                                                <div class="col-sm-3">
                                                                    <div class="lcs_wrap">
                                                                        <input name="tooltipayuda" id="tooltipayuda" class="lcs_checktool" type="checkbox">
                                                                        <div class="lcs_switch  lcs_checkbox_switch lcs_on" id="tooltipayudacheck">
                                                                        <div class="lcs_cursor"></div>
                                                                        </div>
                                                                    </div>
                                                                    <input type="hidden" name="tooltipayuda1" id="tooltipayuda1"/>
                                                                </div>
                                                            </div>
                                                            
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6  m-t-10 sm-m-t-10">
                                                        <div class="pull-left">
                                                            <button class="btn btn-primary btn-lg" type="submit">
                                                                <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                            </div>
                                        </div>  
                                    </form>
                                </div>
                                <div id="notificaciones" class="tab-pane fade">
                                <h3>Notificaciones</h3>
                                    <p>Tiempo previo para enviar recordatorio</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
                            
    

        <?php Mostrargeneral::mostrarjs($varUbicacion);?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeXcEX2bq122bXJibs7C0Al_18PLyAXCY&callback=getLocation"></script>
        
    </body>
</html>