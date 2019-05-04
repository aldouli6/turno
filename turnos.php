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
/* #prueba *{
    border:1px solid 
} */
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
.dia {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: #333;
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
/* Other ratios */

.ratio4_1:before {
  padding-top: 25%;
}

.ratio20_1:before {
  padding-top: 5%;
}

.ratio4_3:before {
  padding-top: 75%;
}

.ratio16_9:before {
  padding-top: 56.25%;
}
</style>