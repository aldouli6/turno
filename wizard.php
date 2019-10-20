<?php
    $varUbicacion = 'wizard';
    include_once("class/class.brain.php");
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
        <div class="se-pre-con"></div>
        <div class="page-sidebar" data-pages="sidebar">
            <?php Mostrargeneral::menumobile(); ?>
            <?php Mostrargeneral::encabezadoLogo(); ?>
            <?php //Menus::menumostra($_SESSION['typeuser'], $_SESSION['permissions'],$varUbicacion);?>
        </div>
        <div class="page-container">
            <div class="header ">
                <div class="container-fluid relative">
                    <div class="pull-left full-height visible-sm visible-xs">
                        <?php Mostrargeneral::actionBar(); ?>
                    </div>
                    <?php Mostrargeneral::logoLg(); ?>
                </div>
                <div class=" pull-left sm-table hidden-xs hidden-sm">
                    <div class="header-inner">
                        <?php Mostrargeneral::logoSm(); ?>
                    </div>
                </div>
                <div class=" pull-right">
                    <?php MostrarPlugins::mostrarConfigUser(); ?>
                </div>
            </div>
            <div class="page-content-wrapper">
                <div class="content">
                    
                    <div class="container-fluid container-fixed-lg">
                        <!-- Contenido Inicia -->
                        
                        <?php MostrarPerfilUsuario::MostrarPerfil($_SESSION['EstablecimientoID']);//Se carga el método formularioRegistroUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal del registro de usuarios. ?>
                        <!--  start modal registro usuario -->
                        <!-- <div class="modal fade slide-up disable-scroll" id="formRegistroEstablecimiento" tabindex="-1" role="dialog" aria-hidden="false"> -->
                        <div class=" disable-scroll" id="formRegistroES" tabindex="-1" role="dialog" aria-hidden="false">
                            <!-- <div class="modal-dialog "> -->
                            <div class="">
                            <!-- <div class="modal-content-wrapper"> -->
                            <div class="">
                                <!-- <div class="modal-content"> -->
                                <form role="form" id="formRegistroEstablecimiento" role="form" autocomplete="off">
                                <div class="">
                                <div class="modal-header clearfix text-left">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="pull-left">
                                            <?php echo "Sesison".$_SESSION["EstablecimientoId"]; ?>
                                            <h5>Registrar establecimiento</h5>
                                            <p class="p-b-10">Formulario de registro de establecimiento</p>
                                        </div>
                                        </div>
                                    <div class="col-sm-6  m-t-10 sm-m-t-10">
                                        <div class="pull-right">
                                            <button  class="btn btn-primary btn-lg"  type="submit">
                                                <span class="glyphicon glyphicon-floppy-save"></span> Guardar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                    
                                </div>
                                <div class="">
                                    <!-- START PANEL -->
                                    <div class="panel panel-transparent">
                                    <div class="panel-body">
                                        <input type="hidden" name="establecimiento" id="establecimiento" value="<?php echo $_SESSION["EstablecimientoId"];?>"/>
                                        <input type="hidden" name="regEstabPais" id="regEstabPais">
                                        <input type="hidden" name="regEstabLatitud" id="regEstabLatitud" required>
                                        <input type="hidden" name="regEstabLongitud" id="regEstabLongitud" required>
                                        <input type="hidden" name="regEstabUsuario" id="regEstabUsuario" value="<?php echo $_SESSION["UsuarioID"]; ?>">
                                        <p>Datos principales</p>
                                        <div class="form-group-attached">
                                            <div class="row clearfix">
                                                <div class="col-sm-12">
                                                    <div class="form-group form-group-default required">
                                                    <label>Nombre</label>
                                                    <input type="text" class="form-control" name="regEstabNombe" id="regEstabNombe" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default required">
                                                        <label>Correo Electrónico</label>
                                                        <input type="email" class="form-control" name="regEstabEmail" id="regEstabEmail" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default required">
                                                        <label>Teléfono</label>
                                                        <input type="text" class="form-control" name="regEstabTelefono" id="regEstabTelefono" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-sm-6">
                                                    <div class="form-group form-group-default  required">
                                                        <label >Categoría</label>
                                                        <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="regEstabCategoria" id="regEstabCategoria" required>
                                                            <option value="0">Seleccione una categoría</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6" disabled>
                                                    <div class="form-group form-group-default  required">
                                                        <label >Subcategoría</label>
                                                        <select disabled title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="regEstabSubcategoria" id="regEstabSubcategoria" required>
                                                            <option value="0">Seleccione una subcategoría</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <p class="m-t-5">Datos de Ubicación</p>
                                        <div class="row clearfix">
                                            <div id="elmapa" class="col-sm-6" style="
                                                    height: 312px;
                                                ">
                                            
                                            </div>
                                            <div class="form-group-attached col-sm-6">
                                                <div class="row clearfix">
                                                    <div class="col-sm-12">
                                                        <div class="form-group form-group-default required">
                                                            <label>Calle</label>
                                                            <input type="text" class="form-control" name="regEstabCalle" id="regEstabCalle" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-group-default required">
                                                            <label>Número Exterior</label>
                                                            <input type="text" class="form-control" name="regEstabNumExt" id="regEstabNumExt" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-group-default ">
                                                            <label>Número Interior</label>
                                                            <input type="text" class="form-control" name="regEstabNumInt" id="regEstabNumInt" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-sm-6">
                                                        <div class="form-group form-group-default required">
                                                            <label>Código Postal</label>
                                                            <input type="text" class="form-control" name="regEstabCP" id="regEstabCP" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                    <div class="form-group form-group-default  ">
                                                        <label >Colonia</label>
                                                        <select disabled title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="regEstabColonia" id="regEstabColonia" >
                                                            <option value="0">Seleccione una colonia</option>
                                                        </select>
                                                    </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-default  required">
                                                    <label >Estado</label>
                                                    <select title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="regEstabEstado" id="regEstabEstado" required>
                                                        <option value="0">Seleccione un estado</option>
                                                    </select>
                                                </div>
                                                <div class="form-group form-group-default  required">
                                                    <label >Ciudad</label>
                                                    <select disabled title="" tabindex="-1" class="full-width select2-offscreen" data-placeholder="Select Country" data-init-plugin="select2" name="regEstabCiudad" id="regEstabCiudad" required>
                                                        <option value="0">Seleccione una ciudad</option>
                                                    </select>
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
                <?php Mostrargeneral::mostrarFooter(); ?>
            </div>
        </div>
        <?php Mostrargeneral::mostrarjs($varUbicacion);?>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAeXcEX2bq122bXJibs7C0Al_18PLyAXCY&callback=getLocation"></script>
        
        
        
    </body>
</html>