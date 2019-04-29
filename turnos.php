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
                        <?php
                            MostrarPerfilUsuario::MostrarPerfil();
                        ?>
                        <input type="hidden" name="establecimientoId" id="establecimientoId" value="<?= $_SESSION['EstablecimientoID']?>">
                        
                        <div class="" style="padding: 15px;background: #ffffff;margin-left: 10px;margin-right: 10px;border: 1px solid #e7e7e7;">
                            
                            <div id="prueba" class=" m-t-15" style="min-height:500px;">
                                <div id="calendario" class="">
                                    <div id="nombreMes" class="">
                                        El nombre del mes
                                    </div>
                                    <div id="fechas" class="container">
                                        <div id="semana1" style="display:flex" class="row justify-content-md-center">
                                            <div id="week" style="width:16%">week</div>
                                            <div id="dia0" style="width:12%">31</div>
                                            <div id="dia1" style="width:12%">1</div>
                                            <div id="dia2" style="width:12%">2</div>
                                            <div id="dia3" style="width:12%">3</div>
                                            <div id="dia4" style="width:12%">4</div>
                                            <div id="dia5" style="width:12%">5</div>
                                            <div id="dia6" style="width:12%">6</div>
                                        </div>
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
#prueba *{
    border:1px solid 
}
</style>