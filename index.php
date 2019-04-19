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
                        Sistema de Administración<br /><?=$nombreEmpresaCompleto ?>
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
                <div class="p-l-10 m-l-20 p-r-10 m-r-10 p-t-40 m-t-20 sm-p-l-5 sm-p-r-5 sm-p-t-30">
                    <img src="assets/img/logo_jpg.jpg" alt="logo" data-src="assets/img/logo_2x.png" data-src-retina="assets/img/logo_jpg.jpg" width="217" height="110">
                    <div class="row" id="message"></div>

                    <div id="exTab2" class="">	
                        <ul class="nav nav-tabs">
                            <li >
                                <a  href="#1" data-toggle="tab">Inciar Sesión</a>
                            </li>
                            <li class="active">
                                <a href="#2" data-toggle="tab">Registrarse</a>
                            </li>
                        </ul>
                        <div class="tab-content ">
                            <div class="tab-pane " id="1">
                                <p class="p-t-15">Accede al sistema desde tu cuenta</p>
                                <div class="form-group form-group-default">
                                    <label>Nombre de Usuario</label>
                                    <div class="controls">
                                        <input type="text" id="username" name="username" placeholder="Nombre de usuario" class="form-control"  required>
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
                                <button class="btn btn-primary btn-cons m-t-10" id="btn_login">Iniciar sesión</button>
                            </div>
                            <div class="tab-pane active" id="2">
                                <p class="p-t-15">Registra tu cuenta como administrador</p>
                                <div class="form-group form-group-default">
                                    <label>Nombre de Usuario</label>
                                    <div class="controls">
                                        <input type="text" id="usernameSign" name="usernameSign" placeholder="Nombre de usuario" class="form-control" value="username"  required>
                                    </div>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Email</label>
                                    <div class="controls">
                                        <input type="email" id="emailSign" name="emailSign" placeholder="Email" class="form-control"  value="aldouli6@gmail.com" required>
                                    </div>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Teléfono</label>
                                    <div class="controls">
                                        <input type="text" id="telefonoSign" name="telefonoSign" placeholder="Teléfono" class="form-control"  value="12345678" required>
                                    </div>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Nombre (s)</label>
                                    <div class="controls">
                                        <input type="text" id="nombreSign" name="nombreSign" placeholder="Nombre (s)" class="form-control"  value="username" required>
                                    </div>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Apellido (s)</label>
                                    <div class="controls">
                                        <input type="text" id="apellidoSign" name="apellidoSign" placeholder="Apellido (s)" class="form-control"  value="username" required>
                                    </div>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Contraseña</label>
                                    <div class="controls">
                                        <input type="password" class="form-control" id="passwordSign" name="passwordSign" placeholder="Credenciales" value="username" required>
                                    </div>
                                </div>
                                <div class="form-group form-group-default">
                                    <label>Aquí va el no soy robot</label>
                                </div>
                                <button class="btn btn-primary btn-cons m-t-10" id="btn_signIn">Registrarse</button>
                            </div>
                        </div>
                    </div>





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