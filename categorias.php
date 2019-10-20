<?php
$varUbicacion = 'categorias';
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
                    <div class="container-fluid full-height no-padding">
                        <?php
                            //Este método se manda llamar desde la carpeta class/class.mostrarusuario.php
                            MostrarPerfilUsuario::MostrarPerfil($_SESSION['EstablecimientoID']);//Se carga el método formularioRegistroUsuario de la clase MostrarUsuario el cuál nos va a imprimir sobre la página el modal del registro de usuarios.
                            if($_SESSION['typeuser'] != "root"){
                                echo '<div class="row full-height no-margin">
                                <div class="col-md-9">';
                            }                
                            // *******************************
                        ?>
                        <div class="row" style="padding: 35px;background: #ffffff;margin-left: 5px;margin-right: 10px;border: 1px solid #e7e7e7;">
                            <div class="col-sm-6">
                                <div class="row " >
                                    <form role="form" id="formNewCategoria" role="form" autocomplete="off">
                                        <div class="col-sm-6">
                                        <div class="form-group form-group-default required">
                                            <label>Categoría</label>
                                            <input type="hidden" name="categoriaPadreInp" value='0' >
                                            <input type="text" class="form-control" name="newCategoria" id="newCategoria" required>
                                        </div>
                                        </div>
                                        <div class="col-sm-6" >
                                            <button type="submit" class="btn btn-primary btn-lg h-100" id="btnNewCategoria" style="height: 53px;">
                                                <span class="fa fa-plus fa-x2"></span>&nbsp;Registrar Categoría
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <br>
                                <br>
                                <div class=" table table-responsive">
                                    <table id="tableCategorias" class="table table-hover " cellspacing="0" width="100%" style="background: #f0f0f0;">
                                        <thead>
                                        <tr id="TrCategoria">
                                            <th>Nombre</th>
                                            <th>Guardar</th>
                                            <th>Eliminar</th>                                                                  
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Guardar</th>
                                            <th>Eliminar</th>                                                                  
                                        </tr>
                                        </tfoot>
                                        <tbody id="contenidoListaCategorias">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-sm-6 ">
                                <div class="row " >
                                    <form role="form" id="formNewSubCategoria" role="form" autocomplete="off">
                                        <div class="col-sm-6">
                                        <div class="form-group form-group-default required">
                                            <label>Subcategoría</label>
                                            <input type="hidden" name="categoriaPadreInp" id="categoriaPadreInp" >
                                            <input type="text" class="form-control" name="newCategoria" id="newSubCategoria" required>
                                        </div>
                                        </div>
                                        <div class="col-sm-6" >
                                            <button type="submit" class="btn btn-primary btn-lg h-100" id="btnNewSubCategoria" style="height: 53px;">
                                                <span class="fa fa-plus fa-x2"></span>&nbsp;Registrar Subcategoría
                                            </button>
                                        </div>
                                    </form>
                                </div>
                                
                                <br>
                                <br>
                                <div class=" table table-responsive">
                                    <table id="tableSubCategorias" class="table table-hover " cellspacing="0" width="100%" style="background: #f0f0f0;">
                                        <thead>
                                        <tr>
                                            <th colspan="3" id="ThCategoriaPadre" style="color: black;"></th>                                                                 
                                        </tr>
                                        <tr id="TrSubCategoria">
                                            <th>Nombre</th>
                                            <th>Guardar</th>
                                            <th>Eliminar</th>                                                                  
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Guardar</th>
                                            <th>Eliminar</th>                                                                  
                                        </tr>
                                        </tfoot>
                                        <tbody id="contenidoListaSubCategorias">

                                        </tbody>
                                    </table>
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