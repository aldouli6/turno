<?php 


    class MostrarPerfilUsuario
    {
        public static function MostrarPerfil()
        {
            echo '          
                    <div class="modal fade slide-right in" id="modalSlideLeft" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog modal-sm" style="width: 445px;">
                      <div class="modal-content-wrapper">
                        <div class="modal-content">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="pg-close fs-14"></i>
                          </button>
                          <div class="container-xs-height full-height">



                          <form role="form" id="formEditProfileUser" role="form" autocomplete="off">
                                                  
                                    <div class="row" style="padding: 28px">

                                              <div class="row">
                                                    <div class="col-md-1">
                                                        <img src="assets/img/userAccount.png" alt="" data-src="assets/img/userAccount.png" height="32" width="32" style="margin-top: 14px;">
                                                    </div>     
                                              
                                                    <div class="col-md-11">
                                                        <h2>Perfil de usuario</h2>
                                                    </div>                                                    
                                              </div>

                                              <hr>
                                              
                                              <p>Datos personales</p>
                                              <div class="form-group form-group-default">
                                                  <label>Nombre(s)</label>
                                                  <input type="text" class="form-control" name="perfilNombre" id="perfilNombre" required>
                                              </div>

                                              <div class="form-group form-group-default">
                                                  <label>Apellido(s)</label>
                                                  <input type="text" class="form-control" name="perfilApellidos" id="perfilApellidos" required>
                                              </div>

                                              <br>

                                              <p class="m-t-10">Datos de la cuenta</p>
                                              <div class="form-group form-group-default required">
                                                  <label>Usuario (Correo Eléctronico)</label>
                                                  <input type="email" class="form-control" name="perfilEmail" id="perfilEmail" required>
                                              </div>


                                              <div class="form-group form-group-default required">
                                                  <label>Contraseña</label>
                                                  <input type="password" class="form-control" name="perfilPassword" id="perfilPassword" required>
                                              </div>

                                              <br>

                                              <button type="submit" class="btn btn-primary btn-block">Guardar</button>
                                              <br>
                                              <button type="button" onclick="logout()" class="btn btn-default btn-block" data-dismiss="modal" style="background: #1e2229;border: solid 0px;color: white;">
                                                        Cerrar sesión &nbsp; <i class="fa fa-sign-out" aria-hidden="true"></i>                                                        
                                              </button>                                              
                                                                                                                                                                                                              
                                              <input type="hidden" name="" id=""/>
                                              <input type="hidden" id="profileUserId" name="profileUserId" value="' . $_SESSION["UsuarioID"] . '"/>

                                    </div>                                                                                                                                                     
                          
                          </form>
                         
                          </div>
                        </div>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
            ';            

        }
    }


?>