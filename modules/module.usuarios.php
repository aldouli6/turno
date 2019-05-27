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
try {
if(!empty($_POST)){
   
    extract($_REQUEST);
        switch($cmd){
            case 'getclientes':
                
                $sql="SELECT  usuarioId as id, ".$busqueda." as busqueda FROM usuarios where tipoUsuarioId = 4 and ";
                $sql.=($usuarioId!='0')?" usuarioId=? ":" usuarioId<>? "; 
                $getDatos = $database->getRows($sql, array($usuarioId));
                $jsonDatos=json_encode($getDatos);
                echo $jsonDatos;
                break;
            case "dataTableUsuario":
                $extra="";
                $consultaDatosUsuario="SELECT  u.usuarioId, u.nombre, u.apellidos, u.email, t.nombre as descripcion, u.estatus
                    FROM usuarios as u
                    inner join tiposUsuarios as t on u.tipoUsuarioId=t.tipoUsuarioId "; 
                switch ($_SESSION["typeuser"]) {
                    case 'root':
                        $extra = " where 1 ";
                        break;
                    case 'admin':
                        $extra = " where u.tipoUsuarioId in('2','3') and u.establecimientoId = '".$_SESSION["EstablecimientoID"]."' ";
                        break;
                    default:
                        $extra="";
                        break;
                }                
                $consultaDatosUsuario.=$extra.' order by u.usuarioId';
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
                                        <button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarUsuarioData('.$rsUsuario["usuarioId"].',\''.$rsUsuario["nombre"].'\');">
                                            <span class="glyphicon glyphicon-trash capa" style="color:white"></span>
                                        </button>
                                    </td>
                                </tr>';
                        }//Fin foreach
                    }
                                         		
                break;
                case "listaTipoUsuario":
                    $sql="SELECT  tipoUsuarioId, nombre FROM tiposusuarios ";  
                    if($_SESSION["typeuser"] == "root"){
                        $sql.=" where 1";                     
                    }elseif($_SESSION["typeuser"] == "admin"){
                        $sql.=" where tipoUsuarioId > 2";  
                    }
                    //Crea una lista con todos los tipos de usuarios para integrarse en el Select donde mandó llamar.
                    $getTipoUsuario = $database->getRows($sql, array($usuario));
                    echo "<option value='0'>-- Seleccione un tipo usuario --</option>";
                    foreach ($getTipoUsuario as $rsTipoUsuario) {
                        echo "<option value='".$rsTipoUsuario['tipoUsuarioId']."'>".$rsTipoUsuario['nombre']."</option>";                                             
                    }
                break;
                case "registrarUsuario":
                    $database->beginTransactionDB();
                    $regUser=array();
            		$regUser[0]=$_REQUEST["regUsuarioNombre"];
                    $regUser[1]=$_REQUEST["regUsuarioApellidos"]; 
                    $regUser[2]=$_REQUEST['regTipoUsuario'];            
                    $regUser[3]=$_REQUEST["regEmail"];                    
                    $regUser[4]=$_REQUEST["regUsuarioPass"];
                    $regUser[5]=date("Y-m-d H:i:s");
                    $regUser[6]= $_REQUEST["StatusUsuarioSistema"];
            		$regUser[7]=$_REQUEST['regUsername'];
            		$regUser[8]=$_REQUEST['regTelefono'];
            		$regUser[9]=$_REQUEST['regEstablecimientoId'];;
		            $registrarUsuario=$database->insertRow("INSERT into usuarios(
                                                    nombre,
                                                    apellidos, 
                                                    tipoUsuarioId, 
                                                    email, 
                                                    password, 
                                                    fecha_registro, 
                                                    estatus, 
                                                    username,
                                                    telefono,
                                                    establecimientoId) 
                                                    values(?,?,?,?,?,?,?,?,?,?)",$regUser);
                  	if($registrarUsuario==true){
                        $getUserLastId=$database->lastIdDB();
                        $tipoUsuarioRegistro="";
                        $ConsultarGetUsuario="SELECT  u.usuarioId, u.nombre, u.apellidos,u.email,
                                                t.nombre as descripcion, u.estatus
                                                FROM usuarios as u
                                                inner join tiposusuarios as t on u.tipousuarioId =t.tipousuarioId                                                
                                                where u.usuarioId=?";
                        $GetUsuario=$database->getRow($ConsultarGetUsuario,array($getUserLastId));
                        if($GetUsuario==true){
                            $database->commitDB();
                            $BotonEditarUsuario='<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditUsuario" onclick="editUsuarioData('.$GetUsuario["usuarioId"].');">
                                                    <span class="glyphicon glyphicon-pencil capa"></span>
                                                </button>';
                            $BotonEliminarUsuario='<button style="background:gray" type="button" class="btn btn-default btn-sm" onclick="eliminarUsuarioData('.$GetUsuario["usuarioId"].',\''.$rsUsuario["nombre"].'\');">
                                                        <span class="glyphicon glyphicon-trash capa" style="color:white"></span>
                                                    </button>';
                            echo $GetUsuario["usuarioId"]."%".$GetUsuario["nombre"]."%".$GetUsuario["apellidos"]."%".$GetUsuario["email"]."%".$GetUsuario["descripcion"]."%".$GetUsuario["estatus"]."%".$BotonEditarUsuario."%".$BotonEliminarUsuario;
                        }else{
                            $database->rollBackDB();
                            echo "0";
                        }
                    }else{
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
                        $consultaInfoUsuario="SELECT u.usuarioId, u.nombre, u.apellidos, u.tipoUsuarioId, u.password,u.telefono,u.username,
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
                                "estatus"=>$getUsuarioData["estatus"],
                                "username"=>$getUsuarioData["username"],
                                "telefono"=>$getUsuarioData["telefono"]
                            );
                            $jsonInfoUsuario=json_encode($InfoUsuaro);
                            echo $jsonInfoUsuario;
                        }
                    }
                break;
          		case "editarUsuario":
                    $idUsuarioEditar=$_REQUEST['idUsuarioSistemaEditar'];
                    $editarDatosUsuario="UPDATE usuarios set 
                            nombre='".$_REQUEST["editUsuarioNombre"]."', 
                            apellidos='".$_REQUEST["editUsuarioApellidos"]."', 
                            email='".$_REQUEST["editUsuarioEmail"]."', 
                            tipoUsuarioId='".$_REQUEST["editTipoUsuarios"]."', 
                            estatus='".$_REQUEST["EditStatusUsuarioSistema"]."', 
                            telefono='".$_REQUEST["editUsername"]."', 
                            username='".$_REQUEST["editTelefono"]."'
                             where usuarioId=?";
                    $editUsuarioData=$database->updateRow($editarDatosUsuario,array($idUsuarioEditar));
                    if($editUsuarioData==true){
                        $ConsultarGetUsuario="SELECT  u.usuarioId, u.nombre, u.apellidos, u.email,
                            t.nombre as descripcion, u.estatus
                            FROM usuarios as u
                            inner join tiposusuarios as t on u.tipoUsuarioId=t.tipoUsuarioId                                             
                            where u.usuarioId=?";
                        $GetUsuario=$database->getRow($ConsultarGetUsuario,array($idUsuarioEditar));
                        if($GetUsuario==true){
                            $BotonEditarUsuario='<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditUsuario" onclick="editUsuarioData('.$GetUsuario["UsuarioId"].');">
                            <span class="glyphicon glyphicon-pencil capa"></span>
                            </button>';

                            $BotonEliminarUsuario='<button style="background:gray" type="button" class="btn btn-default btn-sm" onclick="eliminarUsuarioData('.$GetUsuario["UsuarioId"].',\''.$rsUsuario["nombre"].'\');">
                                <span class="glyphicon glyphicon-trash capa" style="color:white"></span>
                            </button>';

                            echo $GetUsuario["usuarioId"]."%".$GetUsuario["nombre"]."%".$GetUsuario["apellidos"]."%".$GetUsuario["email"]."%".$GetUsuario["descripcion"]."%".$GetUsuario["estatus"]."%".$BotonEditarUsuario."%".$BotonEliminarUsuario;                                              
                        }else{
                            echo "0";
                        }
                    }else{
                        echo "0";
                    }
          		break;
		        case "eliminarUsuario":
                    if(SeguridadSistema::validarEntero(trim($_REQUEST['claveUsuarioEliminar']))==true){
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
                        $eliminarUsuarioInfo="DELETE FROM usuarios where usuarioId=?";
                        $eliminarUsuarioData=$database->deleteRow($eliminarUsuarioInfo,array($idUsuarioEliminar));
                        if($eliminarUsuarioData==true){
                                echo "1";
                        }else{
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
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

?>
