<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 25/03/19
 * PROJECT: turno
 *
 * DESCRIPTION: Este archivo realiza todas las transacciones del módulo de usuario (altas,bajas,modificaciones,etc.)
 *
 */

$varUbicacion = 'securezone';
include_once("../class/class.brain.php");

$database = new db();

if(!empty($_POST)){
   
    extract($_REQUEST);
        switch($cmd){
            case "dataTableUsuario":
                if($_SESSION["typeuser"] == "root"){
                    //Este case trae todo los datos de los usuarios de la tabla usuarios y los recrea en un datatable.
                    $consultaDatosUsuario="SELECT  u.usuarioId, u.nombre, u.apellidos, u.email, t.nombre as descripcion, u.estatus
                                        FROM usuarios as u
                                        inner join tiposUsuarios as t on u.tipoUsuarioId=t.tipoUsuarioId       
                                        
                                        order by u.usuarioId";
                                         
                    $getUsuario = $database->getRows($consultaDatosUsuario);
                    
                    if($getUsuario!=false){
                        foreach ($getUsuario as $rsUsuario) {
                            echo "<tr id='Usuario".$rsUsuario["usuarioId"]."'>
                                    <td>".$rsUsuario["usuarioId"]."</td><td>".$rsUsuario["nombre"]."</td><td>".$rsUsuario["apellidos"]."</td>"
                                    ."</td><td>".$rsUsuario["email"]."</td><td>".$rsUsuario["descripcion"]."</td><td>".$rsUsuario["estatus"]."</td>"
                                    .'<td>
                                        <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditUsuario" onclick="editUsuarioData('.$rsUsuario["usuarioId"].');">
                                        <span class="glyphicon glyphicon-pencil capa"></span>
                                        </button>
                                    </td>'
                                    .'<td>
                                        <button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarUsuarioData('.$rsUsuario["usuarioId"].');">
                                            <span class="glyphicon glyphicon-trash capa" style="color:white"></span>
                                        </button>
                                    </td>
                                </tr>';
                        }//Fin foreach
                    }

               }
               if($_SESSION["typeuser"] == "admin")
               {
                        $subfraccionamientoRelacion = $_REQUEST['subfraccionamientoRelacion'];
                        //Este case trae todo los datos de los usuarios de la tabla usuarios y los recrea en un datatable.
                        $consultaDatosUsuario="SELECT  usuario.UsuarioId, usuario.UsuarioNombre, usuario.UsuarioApellidos,usuario.UsuarioNickName,
                        tipousuario.TipoUsuarioDescripcion, usuario.EstatusUsuario
                                                FROM usuario
                                                inner join tipousuario on usuario.TipoUsuarioId=tipousuario.TipoUsuarioId       
                           where usuario.TipoUsuarioId = 3 or usuario.TipoUsuarioId = 5 and usuario.SubfraccionamientoId = ?
                           order by usuario.UsuarioId";

                        $getUsuario = $database->getRows($consultaDatosUsuario, array($subfraccionamientoRelacion));

                          if($getUsuario!=false)
                          {

                                foreach ($getUsuario as $rsUsuario) {

                                      echo "<tr id='Usuario".$rsUsuario["UsuarioId"]."'>
                                                <td>".$rsUsuario["UsuarioId"]."</td><td>".$rsUsuario["UsuarioNombre"]."</td><td>".$rsUsuario["UsuarioApellidos"]."</td>"
                                                ."</td><td>".$rsUsuario["UsuarioNickName"]."</td><td>".$rsUsuario["TipoUsuarioDescripcion"]."</td><td>".$rsUsuario["EstatusUsuario"]."</td>"
                                              .'<td>
                                                  <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditUsuario" onclick="editUsuarioData('.$rsUsuario["UsuarioId"].');">
                                                  <span class="glyphicon glyphicon-pencil capa"></span>
                                                  </button>
                                                </td>'
                                              .'<td>
                                                  <button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarUsuarioData('.$rsUsuario["UsuarioId"].');">
                                                      <span class="glyphicon glyphicon-trash capa" style="color:white"></span>
                                                  </button>
                                              </td>
                                          </tr>';
                              }//Fin foreach
                          }
               }
                                          		
                 break;


                case "listaTipoUsuario":

                               if($_SESSION["typeuser"] == "root")
                               {
                                    //Crea una lista con todos los tipos de usuarios para integrarse en el Select donde mandó llamar.
                                    $getTipoUsuario = $database->getRows("SELECT  TipoUsuarioId, TipoUsuarioDescripcion FROM tipousuario where TipoUsuarioId>1 and TipoUsuarioDescripcion != 'Colono' and TipoUsuarioDescripcion != 'Guardia'", array($usuario));

                  				    echo "<option value='0'>-- Seleccione un tipo usuario --</option>";

                  					foreach ($getTipoUsuario as $rsTipoUsuario) 
                                    {
                  					   echo "<option value='".$rsTipoUsuario['TipoUsuarioId']."'>".$rsTipoUsuario['TipoUsuarioDescripcion']."</option>";
                                       //echo "<option value='".$rsTipoUsuario['TipoUsuarioId']."'>".$_SESSION["typeuser"]."</option>";                                              
                  					}
                               }       
                               elseif($_SESSION["typeuser"] == "admin")
                               {
                                    //Crea una lista con todos los tipos de usuarios para integrarse en el Select donde mandó llamar.
                                    $getTipoUsuario = $database->getRows("SELECT  TipoUsuarioId, TipoUsuarioDescripcion FROM tipousuario where TipoUsuarioId>1 and TipoUsuarioDescripcion != 'Colono' and TipoUsuarioDescripcion != 'Administrador'", array($usuario));

                  				    echo "<option value='0'>-- Seleccione un tipo usuario --</option>";

                  					foreach ($getTipoUsuario as $rsTipoUsuario) 
                                    {
                  					   echo "<option value='".$rsTipoUsuario['TipoUsuarioId']."'>".$rsTipoUsuario['TipoUsuarioDescripcion']."</option>";
                                       //echo "<option value='".$rsTipoUsuario['TipoUsuarioId']."'>".$_SESSION["typeuser"]."</option>";                                              
                  					}
                               }

                        break;



                case "registrarUsuario":
                   
                   //Case para registrar los datos del usuario en la base de datos

                    $database->beginTransactionDB();
                    $regUser=array();/*Se crea el arreglo regUser que nos servira para almacenar todos los datos del usuario a registrar en la base de datos.*/

            		$regUser[0]=$_REQUEST["regUsuarioNombre"];
                    $regUser[1]=$_REQUEST["regUsuarioApellidos"]; 
                    $regUser[2]=$_REQUEST['regTipoUsuario'];            
                    $regUser[3]=$_REQUEST["regUsuario"];                    
                    $regUser[4]=$_REQUEST["regUsuarioPass"];
                    $regUser[5]=date("Y-m-d H:i:s");
                    $regUser[6]= $_REQUEST["StatusUsuarioSistema"];
                    $regUser[7]=$_REQUEST['horaInicio'];
                    $regUser[8]=$_REQUEST['horaFin'];
            		$regUser[9]=$_REQUEST['subfraccionamientoRelacion'];
            		$regUser[10]="0";


                    // var_dump($regUser);

                    //Se vuelven a almacenar el usuaro,password, tipo usuario y fecha y hora, éste último debe ser exactamente al que se almaceno previamente esto
                    //nos garantiza que es el usuario que se registro en esa fecha y esa hora, además del usuario, contraseña y tipo se usuario que nos ayudaran a ser
                    //más específos en nuestra búsqueda

		            $registrarUsuario=$database->insertRow("INSERT into usuario(UsuarioNombre, UsuarioApellidos, TipoUsuarioId, UsuarioNickName, UsuarioPassword, UsuarioFechaRegistro, EstatusUsuario, horaEntrada, horaSalida, SubfraccionamientoId, statusLogin) values(?,?,?,?,?,?,?,?,?,?,?)",$regUser);


                    // var_dump($registrarUsuario);

                  	if($registrarUsuario==true)
                    {
                  		//En caso de que la consulta se haya registrado correctamente devolverá un true por lo que se mostrará al usuario un mensaje de que todo ha salido bien.
                        //Una vez que se inserta el usuario se deberá obtener su información de inmediato esto con el fin de insertar su ID en la tabla de acuerdo a su tipo de usuario

                        $getUserLastId=$database->lastIdDB();

                        // var_dump($getUserLastId);

                        $tipoUsuarioRegistro="";
                        //Una vez que se busca al usuario registrado recientemente su ID de usuaroi será registrado en una tabla de acuerdo a su tipo de usuario

                            // $ConsultarGetUsuario="SELECT  usuario.UsuarioId, usuario.UsuarioNombre, usuario.UsuarioApellidos,estado.EstadosNombre,municipio.MunicipioNombre,usuario.UsuarioCalle, usuario.UsuarioColonia, usuario.UsuarioTelefono, usuario.UsuarioNickName,
                            //                          tipousuario.TipoUsuarioDescripcion, usuario.EstatusUsuario
                            //                          FROM usuario
                            //                          inner join tipousuario on usuario.TipoUsuarioId=tipousuario.TipoUsuarioId
                            //                          inner join municipio on municipio.EstadoId=usuario.EstadosId && municipio.MunicipioId=usuario.MunicipioId
                            //                          inner join estado on estado.EstadosId=municipio.EstadoId
                            //                          where usuario.UsuarioId=?";


                        $ConsultarGetUsuario="SELECT  usuario.UsuarioId, usuario.UsuarioNombre, usuario.UsuarioApellidos,usuario.UsuarioNickName,
                                                tipousuario.TipoUsuarioDescripcion, usuario.EstatusUsuario
                                                FROM usuario
                                                inner join tipousuario on usuario.TipoUsuarioId=tipousuario.TipoUsuarioId                                                
                                                where usuario.UsuarioId=?";

                            $GetUsuario=$database->getRow($ConsultarGetUsuario,array($getUserLastId));

                            if($GetUsuario==true)
                            {
                                $database->commitDB();

                                $BotonEditarUsuario='<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditUsuario" onclick="editUsuarioData('.$GetUsuario["UsuarioId"].');">
                                                        <span class="glyphicon glyphicon-pencil capa"></span>
                                                     </button>';

                                $BotonEliminarUsuario='<button style="background:gray" type="button" class="btn btn-default btn-sm" onclick="eliminarUsuarioData('.$GetUsuario["UsuarioId"].');">
                                                          <span class="glyphicon glyphicon-trash capa" style="color:white"></span>
                                                       </button>';

                                // $BotonStatusUsuario = '                                
                                //     <input type="checkbox" data-init-plugin="switchery" data-size="small" data-color="primary" checked="checked" data-switchery="true" style="display: none;"><span class="switchery switchery-small" style="background-color: rgb(109, 92, 174); border-color: rgb(109, 92, 174); box-shadow: rgb(109, 92, 174) 0px 0px 0px 11px inset; transition: border 0.4s, box-shadow 0.4s, background-color 1.2s;"><small style="left: 13px; background-color: rgb(255, 255, 255); transition: left 0.2s;"></small></span>                                      
                                // ';

                                echo $GetUsuario["UsuarioId"]."%".$GetUsuario["UsuarioNombre"]."%".$GetUsuario["UsuarioApellidos"]."%".$GetUsuario["UsuarioNickName"]."%".$GetUsuario["TipoUsuarioDescripcion"]."%".$GetUsuario["EstatusUsuario"]."%".$BotonEditarUsuario."%".$BotonEliminarUsuario;
                            }
                            else
                            {
                                $database->rollBackDB();
                                echo "0";
                            }
                    }
                    else
                    {
                        //Else registrar usuario
                        $database->rollBackDB();
                        echo "0";
                    }
                break;

                case "setDatosUsuario":    
                    //Se muestran los datos del usuario seleccionado en el formulario de edición por medio del ID de usuario.
                    if(SeguridadSistema::validarEntero(trim($_REQUEST['claveUsuario']))==true){
                        $InfoUsuaro=null;
                        $jsonInfoUsuario=null;
                        //Se obtienen los datos del usuario por medio de su ID Usuario y se muestran en el modal form de la edición del usuario
                        $consultaInfoUsuario="SELECT u.usuarioId, u.nombre, u.apellidos, u.tipoUsuarioId, u.password,
                        t.nombre as descripcion, u.email, u.fecha_registro,u.estatus 
                        FROM usuarios as u
                        inner join tiposUsuarios as t on t.tipoUsuarioId=u.tipoUsuarioId 
                        WHERE usuarioId = ?";                        
                        $getIDUsuario=$_REQUEST["claveUsuario"];
                        $getUsuarioData=$database->getRow($consultaInfoUsuario, array($getIDUsuario));
                        if($getUsuarioData != false){
                            /*Se obtienen los datos en caso de que la consulta haya sido efectuada correctamente*/
                            //Unas vez obtenidos los datos se separan por comas(indicador que nos sirve para poder dividir al información) y así obtener cada uno de los elementos
                            $InfoUsuaro=array("usuarioId"=>$getUsuarioData["usuarioId"],
                                "nombre"=>$getUsuarioData["nombre"],
                                "apellidos"=>$getUsuarioData["apellidos"],                                                                     
                                "tipoUsuarioId"=>$getUsuarioData["tipoUsuarioId"],
                                "password"=>$getUsuarioData["password"],
                                "descripcion"=>$getUsuarioData["descripcion"],
                                "email"=>$getUsuarioData["email"],
                                "fecha_registro"=>$getUsuarioData["fecha_registro"],
                                "estatus"=>$getUsuarioData["estatus"]
                            );
                            $jsonInfoUsuario=json_encode($InfoUsuaro);
                            echo $jsonInfoUsuario;
                        }
                    }
                break;
                    

          		case "editarUsuario":

                              $idUsuarioEditar=$_REQUEST['idUsuarioSistemaEditar'];
                              //Se editan los datos del usuario permitidos por el sistema, todo esto por medio del ID del usuario

                              $editarDatosUsuario="UPDATE usuario set UsuarioNombre='".$_REQUEST["editUsuarioNombre"]."', UsuarioApellidos='".$_REQUEST["editUsuarioApellidos"]."', UsuarioNickName='".$_REQUEST["editUsuarioEmail"]
                                ."', UsuarioPassword='".$_REQUEST["editUsuarioPass"]."', TipoUsuarioId='".$_REQUEST["editTipoUsuarios"]."', EstatusUsuario='".$_REQUEST["EditStatusUsuarioSistema"]."', horaEntrada='".$_REQUEST["edithoraInicio1"]."', horaSalida='".$_REQUEST["edithoraFin2"]."' where UsuarioId=?";

                              $editUsuarioData=$database->updateRow($editarDatosUsuario,array($idUsuarioEditar));


                              if($editUsuarioData==true)
                              {
                                            $ConsultarGetUsuario="SELECT  usuario.UsuarioId, usuario.UsuarioNombre, usuario.UsuarioApellidos, usuario.UsuarioNickName,
                                               tipousuario.TipoUsuarioDescripcion, usuario.EstatusUsuario
                                               FROM usuario
                                               inner join tipousuario on usuario.TipoUsuarioId=tipousuario.TipoUsuarioId                                             
                                               where usuario.UsuarioId=?";
                                            $GetUsuario=$database->getRow($ConsultarGetUsuario,array($idUsuarioEditar));

                                            if($GetUsuario==true){

                                                $BotonEditarUsuario='<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditUsuario" onclick="editUsuarioData('.$GetUsuario["UsuarioId"].');">
                                                <span class="glyphicon glyphicon-pencil capa"></span>
                                             </button>';

                                                $BotonEliminarUsuario='<button style="background:gray" type="button" class="btn btn-default btn-sm" onclick="eliminarUsuarioData('.$GetUsuario["UsuarioId"].');">
                                                  <span class="glyphicon glyphicon-trash capa" style="color:white"></span>
                                               </button>';

                                              echo $GetUsuario["UsuarioId"]."%".$GetUsuario["UsuarioNombre"]."%".$GetUsuario["UsuarioApellidos"]."%".$GetUsuario["UsuarioNickName"]."%".$GetUsuario["TipoUsuarioDescripcion"]."%".$GetUsuario["EstatusUsuario"]."%".$BotonEditarUsuario."%".$BotonEliminarUsuario;                                              
                                            }
                                            else{
                                                echo "0";
                                            }
                              }
                              else{
                                  echo "0";
                              }

          		break;


		          case "eliminarUsuario":

                        if(SeguridadSistema::validarEntero(trim($_REQUEST['claveUsuarioEliminar']))==true)
                        {
                                //Se realiza un update a la base de datos para cambiar el estatus del usuario a cero y así no se visualice en el datatable de usuarios
                                $idUsuarioEliminar=$_REQUEST['claveUsuarioEliminar'];

                                $eliminarUsuarioInfo="UPDATE usuario set usuario.EstatusUsuario=0 where usuario.UsuarioId=?";
                                $eliminarUsuarioData=$database->deleteRow($eliminarUsuarioInfo,array($idUsuarioEliminar));

                                if($eliminarUsuarioData==true)
                                {
                                      echo "1";
                                }
                                else
                                {
                                     echo "0";
                                }
                        }

                break;


                case "eliminarUsuarioSistema":
                                                        
                                        if(SeguridadSistema::validarEntero(trim($_REQUEST['claveUsuarioEliminar']))==true){
                                                //Se realiza un update a la base de datos para cambiar el estatus del usuario a cero y así no se visualice en el datatable de usuarios
                                                $idUsuarioEliminar=$_REQUEST['claveUsuarioEliminar'];
                
                                                $eliminarUsuarioInfo="DELETE FROM usuario where usuario.UsuarioId=?";
                
                                                $eliminarUsuarioData=$database->deleteRow($eliminarUsuarioInfo,array($idUsuarioEliminar));
                
                
                                                if($eliminarUsuarioData==true){
                                                      echo "1";
                                                }
                                                else{
                                                     echo "0";
                                                }
                                        }            
                break;


                case "listaEstadosUsuario":

                      $consultaListaEstadosUsuario="select * from estado";

                      $getListaEstadosUsuario=$database->getRows($consultaListaEstadosUsuario,array($usuario));

                      echo '<option value="0">Seleccione el estado</option>';

                      foreach ($getListaEstadosUsuario as $rsEstadosUsuario){
                          echo '<option value="'.$rsEstadosUsuario["EstadosId"].'">'.$rsEstadosUsuario["EstadosNombre"].'</option>';
                      }


                break;


                case "listaMunicipiosUsuario":

                        $idEstado=$_REQUEST["idEstadoUsuario"];

                        $consultaMunicipiosUsuario="select * from municipio where EstadoId=?";

                        $getListaMuncipiosUsuario=$database->getRows($consultaMunicipiosUsuario,array($idEstado));

                        echo '<option value="0">Seleccione el municipio</option>';

                        foreach ($getListaMuncipiosUsuario as $rsMunicipiosUsuario){
                            echo '<option value="'.$rsMunicipiosUsuario["MunicipioId"].'">'.$rsMunicipiosUsuario["MunicipioNombre"].'</option>';
                        }



                break;


         }



}


?>
