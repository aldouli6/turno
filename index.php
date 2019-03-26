<?php
    $varUbicacion = 'login';
    include_once("class/class.brain.php");
    Sessions::validarlogin();
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
    <body class="fixed-header">
        <div class="login-wrapper">
            <div class="bg-pic">
                <div class="bg-caption bottom-right txt-al-rigth text-white p-r-20 m-b-20">
                    <h2 class="semi-bold text-white">
                        Sistema de administración<br /><?=$nombreEmpresaCompleto ?>
                    </h2>
                    <p class="small">
                        Sistema de control de citas para la prestación de servicios.
                        <?=$nombreEmpresaLegal?> ©Copyright <?=date('Y'); ?>. Todos los derechos reservados.
                        <br>
                        <span class="tip">
                            <a title="Aviso de Privacidad" href="#"> Aviso de Privacidad.</a>
                        </span>
                    </p>
                </div>
            </div>
            <div class="login-container bg-white">
                <div class="p-l-40 m-l-20 p-r-40 m-r-10 p-t-40 m-t-20 sm-p-l-5 sm-p-r-5 sm-p-t-30">
                    <img src="assets/img/logo_jpg.jpg" alt="logo" data-src="assets/img/logo_2x.png" data-src-retina="assets/img/logo_jpg.jpg" width="217" height="110">
                    <p class="p-t-35">Accede al sistema desde tu cuenta</p>
                    <div class="form-group form-group-default">
                        <label>Iniciar Sesión</label>
                        <div class="controls">
                            <input type="text" id="username" name="username" placeholder="Cuenta o email" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required>
                        </div>
                    </div>
                    <div class="form-group form-group-default">
                        <label>Contraseña</label>
                        <div class="controls">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Credenciales" required>
                        </div>
                    </div>
                    <div class="p-l-15 row">
                        <div class="col-md-6 no-padding">
                            <div class="checkbox ">
                                <input type="checkbox" value="1" id="checkbox1">
                                <label for="checkbox1">Recordarme</label>
                            </div>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="recover.php" class="text-info small">¿Olvidó su contraseña?</a>
                        </div>
                    </div>
                    <div class="row" id="message"></div>
                    <button class="btn btn-primary btn-cons m-t-10" id="btn_login">Iniciar sesión</button>
                    <div class="pull-bottom sm-pull-bottom">
                        <div class="m-b-30 sm-m-t-20 sm-p-r-15 sm-p-b-20 clearfix">
                            <div class="col-sm-3 col-md-2">
                                <img alt=""  data-src="assets/img/logo_pages.png" data-src-retina="assets/img/logo_pages.png" height="60" src="assets/img/logo_pages.png" width="60">
                            </div>
                            <div class="col-sm-9 m-t-10" style="margin-left: 10px;">
                                <p>
                                    <small>
                                        Agendado de citas para la prestación de servicios.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            Mostrargeneral::mostrarjs($varUbicacion);
        ?>
    </body>
 <!-- <script src="https://www.gstatic.com/firebasejs/5.5.4/firebase-app.js"></script>
<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyB25zmVjs9jxTUJGEowArgB23Jm7Z6HiJM",
    authDomain: "garde-aca75.firebaseapp.com",
    databaseURL: "https://garde-aca75.firebaseio.com",
    projectId: "garde-aca75",
    storageBucket: "garde-aca75.appspot.com",
    messagingSenderId: "35195548418"
  };
  firebase.initializeApp(config);
</script>  -->
</html> 