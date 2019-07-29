<?php

date_default_timezone_set('America/Mexico_City');
/*
 * CREATOR: VZERT.COM
 * DEVELOPER: Francisco Javier Salazar González
 * DATE: 16/06/2016
 * TIME: 10:04
 * PROJECT:Aseguradora
 *
 * DESCRIPTION: This file creates a main webservice with multiple tasks, each one does a different action in database.
 *
 */

//call brain class and this one loads libraries that we need

$varUbicacion = 'webservice';
include_once '../class/class.brain.php';

$servicio = new soap_server();

$ws = 'urn:aseguradorawsdl';
$servicio->configureWSDL('wsaseguradora', $ws);
$servicio->schemaTargetNamespace = $ws;
$servicio->register('wsmethod', array('case' => 'xsd:string', 'value' => 'xsd:string'), array('return' => 'xsd:string'), $ws);

//wsmethod('setStatusSiniestro','{"SiniestroFolio":"FLDR00086","SiniestroEstatus":1}');
function wsmethod($case, $value)
{

//through a web service the app access different cases and return diferent replies storage in the same variable.

$database = new db(); // variable that instances type connection objects.

$wsReply = ''; //This variable stores different replies in different cases.

  switch ($case) {

//General webservices------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
    case 'login':
                   //Login case: User enters nickname and password, if those existits or not it's return a request.------------------------------->

                   //PLEASE READ LINE AT BELLOW-------------------------------//
                   //Types of replies 0=User not found, 1: User found, 2:User disabled, 3:Incorrect password, 4: User doesn't exist

                   $jsonLoginUserValues = json_decode($value); //Decode json set by webservice client and proceed to execute database query
                   $jsonReturnUserValues = null; //Stores return values of database's login query

                      if (trim($jsonLoginUserValues->{'nickname'}) != '' && trim($jsonLoginUserValues->{'password'}) != 'd41d8cd98f00b204e9800998ecf8427e') { //d41d8cd98f00b204e9800998ecf8427e is a empty string value to md5
                           //Verify if nickname field and password field aren't in blank

                        $userNickname = $jsonLoginUserValues->{'nickname'};
                          $consultaDatosUsuario = 'SELECT  usuario.UsuarioId, usuario.UsuarioNombre, usuario.UsuarioApellidos,usuario.UsuarioCalle,usuario.UsuarioColonia,municipio.MunicipioNombre,
                                                       estado.EstadosNombre,usuario.UsuarioTelefono,usuario.UsuarioNickName, usuario.TipoUsuarioId, usuario.EstatusUsuario, usuario.UsuarioPassword
                                                  FROM usuario
                                               inner join municipio on usuario.MunicipioId=municipio.MunicipioId && usuario.EstadosId=municipio.EstadoId
                                               inner join estado on municipio.EstadoId=estado.EstadosId
                                               WHERE usuario.UsuarioNickName =?';
                          $getReplyUser = $database->getRow($consultaDatosUsuario, array($userNickname));
                          if ($getReplyUser != false) {
                              if ($getReplyUser['EstatusUsuario'] == 1) {
                                  if ($getReplyUser['UsuarioPassword'] == strtoupper($jsonLoginUserValues->{'password'})) {

                                      //User identified

                                      if ($getReplyUser['TipoUsuarioId'] == '5') {
                                          //Asegurado Query

                                              $getAsegurado = $database->getRow('Select AseguradoId from asegurado where UsuarioId=?', array($getReplyUser['UsuarioId']));

                                          if ($getAsegurado != false) {
                                              $replyUserData = array('replyUser' => '1', 'idusuario' => $getReplyUser['UsuarioId'], 'nombre' => $getReplyUser['UsuarioNombre'], 'apellidos' => $getReplyUser['UsuarioApellidos'],
                                                    'calle' => $getReplyUser['UsuarioCalle'], 'colonia' => $getReplyUser['UsuarioColonia'], 'municipio' => $getReplyUser['MunicipioNombre'], 'estado' => $getReplyUser['EstadosNombre'],
                                                    'telefono' => $getReplyUser['UsuarioTelefono'], 'nickname' => $getReplyUser['UsuarioNickName'], 'tipousuario' => $getReplyUser['TipoUsuarioId'], 'idTipoUsuario' => $getAsegurado['AseguradoId'], );
                                          }
                                      } elseif ($getReplyUser['TipoUsuarioId'] == '4') {
                                          //Ajustador Query
                                            $getAjustador = $database->getRow('Select AjustadorId from ajustador where UsuarioId=?', array($getReplyUser['UsuarioId']));

                                          if ($getAjustador != false) {
                                              $replyUserData = array('replyUser' => '1', 'idusuario' => $getReplyUser['UsuarioId'], 'nombre' => $getReplyUser['UsuarioNombre'], 'apellidos' => $getReplyUser['UsuarioApellidos'],
                                              'calle' => $getReplyUser['UsuarioCalle'], 'colonia' => $getReplyUser['UsuarioColonia'], 'municipio' => $getReplyUser['MunicipioNombre'], 'estado' => $getReplyUser['EstadosNombre'],
                                              'telefono' => $getReplyUser['UsuarioTelefono'], 'nickname' => $getReplyUser['UsuarioNickName'], 'tipousuario' => $getReplyUser['TipoUsuarioId'], 'idTipoUsuario' => $getAjustador['AjustadorId'], );
                                          }
                                      }

                                      $jsonReturnUserValues = json_encode($replyUserData);
                                      $wsReply = $jsonReturnUserValues;
                                  } else {
                                      $replyUserData = array('replyUser' => '3'); //Error password
                                      $jsonReturnUserValues = json_encode($replyUserData);
                                      $wsReply = $jsonReturnUserValues;
                                  }
                              } else {
                                  $replyUserData = array('replyUser' => '2'); //Status cero
                                 $jsonReturnUserValues = json_encode($replyUserData);
                                  $wsReply = $jsonReturnUserValues;
                              }
                          } else {
                              $replyUserData = array('replyUser' => '4'); //error query
                                 $jsonReturnUserValues = json_encode($replyUserData);
                              $wsReply = $jsonReturnUserValues;
                          }
                      } else {
                          //If any field is blank, enter in this part and show a error message
                          $replyUserData = array('replyUser' => '0');
                          $jsonReturnUserValues = json_encode($replyUserData);
                          $wsReply = $jsonReturnUserValues;
                      }

        break; //End case login---------------------------------------------------->

        case 'resetEmail':
              $jsonEmailUsuario = json_decode($value);
              $jsonEmailReturn = null;
              $replyEmailData = null;
              $_email = $jsonEmailUsuario->{'nickname'};

              $consultaEmailUsuario = 'SELECT usuario.UsuarioNickName FROM usuario WHERE usuario.UsuarioNickName=?';

              $getReplyUser = $database->getRow($consultaEmailUsuario, array($_email));

              if ($getReplyUser != false)
              {
                $_replyUserData = array('replyUser' => '1'); //Status cero
                $_jsonReturnUserValues = json_encode($_replyUserData);
                $wsReply = $_jsonReturnUserValues;

                //$_email = $_POST['remail'];
              }
              else
              {
                $_replyUserData = array('replyUser' => '4'); //Status cero
                $_jsonReturnUserValues = json_encode($_replyUserData);
                $wsReply = $_jsonReturnUserValues;
              }

        break;

//End general webservices------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

//START ASEGURADO WEBSERVICES--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

     //Get Poliza Table information from AseguradoId-------------------------------------------------------------------------------------->
     case 'getPoliza':

         $jsonIdAseguradoPoliza = json_decode($value);
         $jsonPolizaAseguradoReturn = null;
         $replyPolizaData = null;
         $AseguradoId = $jsonIdAseguradoPoliza->{'AseguradoID'};
         $consultaPolizaAsegurado = 'SELECT poliza . * , tipopoliza.TipoPolizaDescripcion, tipopoliza.TipoPolizaCobertura
                                  FROM poliza
                                  INNER JOIN tipopoliza ON tipopoliza.TipoPolizaId = poliza.TipoPolizaId
                                  WHERE poliza.AseguradoID =?
                                  AND poliza.EstatusPolizaId =1';
         $getReplyPoliza = $database->getRows($consultaPolizaAsegurado, array($AseguradoId));
         $contadorPolizas = 0;
         foreach ($getReplyPoliza as $rsPolizaInfo) {
             if ($contadorPolizas == 0) {
                 $replyPolizaData = array(($contadorPolizas) => array('replyPoliza' => '1', 'PolizaId' => $rsPolizaInfo['PolizaId'], 'NumPoliza' => $rsPolizaInfo['NumPoliza'], 'AseguradoId' => $rsPolizaInfo['AseguradoID'],
              'PolizaFechaAlta' => $rsPolizaInfo['PolizaFechaAlta'], 'PolizaVigencia' => $rsPolizaInfo['PolizaVigencia'], 'TipoPolizaId' => $rsPolizaInfo['TipoPolizaId'], 'EstatusPolizaId' => $rsPolizaInfo['EstatusPolizaId'],
              'VehiculoId' => $rsPolizaInfo['VehiculoId'], 'PolizaSumaAsegurada' => $rsPolizaInfo['PolizaSumaAsegurada'], 'PolizaClausulaDeducible' => $rsPolizaInfo['PolizaClausulaDeducible'],
              'TipoPolizaDescripcion' => $rsPolizaInfo['TipoPolizaDescripcion'], 'TipoPolizaCobertura' => $rsPolizaInfo['TipoPolizaCobertura'], ));
             } else {
                 $replyPolizaData += array(($contadorPolizas) => array('replyPoliza' => '1', 'PolizaId' => $rsPolizaInfo['PolizaId'], 'NumPoliza' => $rsPolizaInfo['NumPoliza'], 'AseguradoId' => $rsPolizaInfo['AseguradoID'],
             'PolizaFechaAlta' => $rsPolizaInfo['PolizaFechaAlta'], 'PolizaVigencia' => $rsPolizaInfo['PolizaVigencia'], 'TipoPolizaId' => $rsPolizaInfo['TipoPolizaId'], 'EstatusPolizaId' => $rsPolizaInfo['EstatusPolizaId'],
             'VehiculoId' => $rsPolizaInfo['VehiculoId'], 'PolizaSumaAsegurada' => $rsPolizaInfo['PolizaSumaAsegurada'], 'PolizaClausulaDeducible' => $rsPolizaInfo['PolizaClausulaDeducible'],
             'TipoPolizaDescripcion' => $rsPolizaInfo['TipoPolizaDescripcion'], 'TipoPolizaCobertura' => $rsPolizaInfo['TipoPolizaCobertura'], ));
             }
             ++$contadorPolizas;
         }

         $jsonPolizaAseguradoReturn = json_encode($replyPolizaData);
         $wsReply = $jsonPolizaAseguradoReturn;

     break;
     //End Get Poliza Table information from AseguradoId------------------------------------------------------------------------------------->

    case 'getVehiculo':

               $jsonIdAseguradoVehiculo = json_decode($value);
               $jsonVehiculoAseguradoReturn = null;
               $replyVehiculoData = null;
               $contadorVehiculos = 0;
               $AseguradoIDVehiculo = $jsonIdAseguradoVehiculo->{'AseguradoId'};
               $consultaVehiculoAsegurado = 'SELECT vehiculo.VehiculoId,vehiculo.TipoVehiculoId,tipovehiculo.TipoVehiculoDescripcion,vehiculo.VehiculoDescripcion,vehiculo.VehiculoMarca, vehiculo.VehiculoModelo,
                                                             vehiculo.Vehiculocapacidad,vehiculo.TipoServicioId,tiposervicio.TipoServicioDescripcion,vehiculo.VehiculoUso,
                                                             vehiculo.VehiculoPlacas,vehiculo.VehiculoZonaCirculacion,vehiculo.AseguradoId,
                                                             vehiculo.VehiculoPuertas,vehiculo.VehiculoColor,vehiculo.VehiculoNumMotor,vehiculo.VehiculoNumSerie
                                                      from vehiculo
                                                      inner join tipovehiculo on vehiculo.TipoVehiculoId=tipovehiculo.TipoVehiculoId
                                                      inner join tiposervicio on vehiculo.TipoServicioId=tiposervicio.TipoServicioId
                                                      where vehiculo.AseguradoId=?';
               $getReplyVehiculoAsegurado = $database->getRows($consultaVehiculoAsegurado, array($AseguradoIDVehiculo));

              foreach ($getReplyVehiculoAsegurado as $rsVehiculoAsegurado) {
                  if ($contadorVehiculos == 0) {
                      $replyVehiculoData = array(($contadorVehiculos) => array('replyVehiculo' => '1', 'VehiculoId' => $rsVehiculoAsegurado['VehiculoId'], 'TipoVehiculoId' => $rsVehiculoAsegurado['TipoVehiculoId'],
                     'TipoVehiculoDescripcion' => $rsVehiculoAsegurado['TipoVehiculoDescripcion'], 'VehiculoDescripcion' => $rsVehiculoAsegurado['VehiculoDescripcion'], 'VehiculoMarca' => $rsVehiculoAsegurado['VehiculoMarca'], 'VehiculoModelo' => $rsVehiculoAsegurado['VehiculoModelo'],
                     'Vehiculocapacidad' => $rsVehiculoAsegurado['Vehiculocapacidad'], 'TipoServicioId' => $rsVehiculoAsegurado['TipoServicioId'], 'TipoServicioDescripcion' => $rsVehiculoAsegurado['TipoServicioDescripcion'], 'VehiculoUso' => $rsVehiculoAsegurado['VehiculoUso'],
                     'VehiculoPlacas' => $rsVehiculoAsegurado['VehiculoPlacas'], 'VehiculoZonaCirculacion' => $rsVehiculoAsegurado['VehiculoZonaCirculacion'], 'AseguradoId' => $rsVehiculoAsegurado['AseguradoId'],
                     'VehiculoPuertas' => $rsVehiculoAsegurado['VehiculoPuertas'], 'VehiculoColor' => $rsVehiculoAsegurado['VehiculoColor'], 'VehiculoNumMotor' => $rsVehiculoAsegurado['VehiculoNumMotor'],
                     'VehiculoNumSerie' => $rsVehiculoAsegurado['VehiculoNumSerie'] ));
                  } else {
                      $replyVehiculoData += array(($contadorVehiculos) => array('replyVehiculo' => '1', 'VehiculoId' => $rsVehiculoAsegurado['VehiculoId'], 'TipoVehiculoId' => $rsVehiculoAsegurado['TipoVehiculoId'],
                   'TipoVehiculoDescripcion' => $rsVehiculoAsegurado['TipoVehiculoDescripcion'], 'VehiculoDescripcion' => $rsVehiculoAsegurado['VehiculoDescripcion'], 'VehiculoMarca' => $rsVehiculoAsegurado['VehiculoMarca'], 'VehiculoModelo' => $rsVehiculoAsegurado['VehiculoModelo'],
                   'Vehiculocapacidad' => $rsVehiculoAsegurado['Vehiculocapacidad'], 'TipoServicioId' => $rsVehiculoAsegurado['TipoServicioId'], 'TipoServicioDescripcion' => $rsVehiculoAsegurado['TipoServicioDescripcion'], 'VehiculoUso' => $rsVehiculoAsegurado['VehiculoUso'],
                   'VehiculoPlacas' => $rsVehiculoAsegurado['VehiculoPlacas'], 'VehiculoZonaCirculacion' => $rsVehiculoAsegurado['VehiculoZonaCirculacion'], 'AseguradoId' => $rsVehiculoAsegurado['AseguradoId'],
                   'VehiculoPuertas' => $rsVehiculoAsegurado['VehiculoPuertas'], 'VehiculoColor' => $rsVehiculoAsegurado['VehiculoColor'], 'VehiculoNumMotor' => $rsVehiculoAsegurado['VehiculoNumMotor'],
                   'VehiculoNumSerie' => $rsVehiculoAsegurado['VehiculoNumSerie'] ));
                  }
                  ++$contadorVehiculos;
              }

             $jsonVehiculoAseguradoReturn = json_encode($replyVehiculoData);
             $wsReply = $jsonVehiculoAseguradoReturn;

    break;

    //Set values on Siniestro table------------------------------------------------------------------->
     case 'setSiniestro':

           $jsonSiniestroAsegurado = json_decode(utf8_encode($value));
           $jsonResultSetSiniestro = null; //This variable will stores the values of set siniestro query's result in Json

           $replySiniestroInfo = null;

              $fecha = new DateTime();
              $fechaActual = date('Y-m-d');
              $horaActual = date('H:i:s');
              $siniestroTimeStamp = $fecha->getTimestamp();

              $database->beginTransactionDB();
              $SiniestroInfo = array();
              $SiniestroInfo[0] = $jsonSiniestroAsegurado->{'PolizaId'};
              $SiniestroInfo[1] = $jsonSiniestroAsegurado->{'AjustadorId'};
              $SiniestroInfo[2] = $jsonSiniestroAsegurado->{'SiniestroUbicacion'};
              $SiniestroInfo[3] = $jsonSiniestroAsegurado->{'LocationX'}.','.$jsonSiniestroAsegurado->{'LocationY'};
              $SiniestroInfo[4] ='';//Siniestro folio is empty
              //$SiniestroInfo[4] = SeguridadSistema::generarClaveSiniestro(8, ($querySizeSiniestro['SiniestroId'] + 1));
              //$SiniestroInfo[5]=$jsonSiniestroAsegurado->{"SiniestroOcurrido"};
              //$SiniestroInfo[6]=$jsonSiniestroAsegurado->{"SiniestroHora"};

              $SiniestroInfo[5] = $fechaActual;
              $SiniestroInfo[6] = $horaActual;
              $SiniestroInfo[7] = $jsonSiniestroAsegurado->{'TipoAccidenteId'};
              $SiniestroInfo[8] = ''; //Tipo accidente comentarios is empty
              $SiniestroInfo[9] = $jsonSiniestroAsegurado->{'ConductorId'};
              $SiniestroInfo[10] = '0';
              $SiniestroInfo[11] = '0';
              $registrarDatosSiniestro = 'insert into siniestro(PolizaId,AjustadorId,SiniestroUbicacion,SiniestroCoordenadas,SiniestroFolio,SiniestroFecha,SiniestroHora,TipoAccidenteId,TipoAccidenteComentarios,ConductorId,SiniestroTerceros,SiniestroEstatus)
                                   values(?,?,?,?,?,?,?,?,?,?,?,?)';

              $regSiniestro = $database->insertRow($registrarDatosSiniestro, $SiniestroInfo);

              if ($regSiniestro == true) {

                      $getSiniestroLastId = $database->lastIdDB();
                      $AddSiniestroFolio=SeguridadSistema::generarClaveSiniestro(8, $getSiniestroLastId);
                      $ConsultaAgregarFolioSiniestro="update siniestro set SiniestroFolio='".$AddSiniestroFolio."' where SiniestroId=?";
                      $AgregarFolioSiniestroDB=$database->updateRow($ConsultaAgregarFolioSiniestro,array($getSiniestroLastId));

                      if($AgregarFolioSiniestroDB==true){
                        $database->commitDB();
                        $replySiniestroInfo = array('getReplySiniestro' => '1', 'timeStampSiniestro' => $siniestroTimeStamp, 'SiniestroId' => $getSiniestroLastId, 'SiniestroFecha' => $fechaActual, 'SiniestroHora' => $horaActual, 'SiniestroEstatus' => '1');
                      }
                      else{
                        $database->rollBackDB();
                        $replySiniestroInfo = array('getReplySiniestro' => '0', 'timeStampSiniestro' => '0', 'SiniestroId' => '0', 'SiniestroFecha' => '0', 'SiniestroHora' => '0', 'SiniestroEstatus' => '0');

                      }

               }
               else {
                  $database->rollBackDB();
                  $replySiniestroInfo = array('getReplySiniestro' => '0', 'timeStampSiniestro' => '0', 'SiniestroId' => '0', 'SiniestroFecha' => '0', 'SiniestroHora' => '0', 'SiniestroEstatus' => '0');
              }

              $jsonResultSetSiniestro = json_encode($replySiniestroInfo);


        $wsReply = $jsonResultSetSiniestro;

     break;


     case "calificarUsuario":

            $jsonGetCalificacionAjustador=json_decode($value);
            $DatosCalificacionAjustador=array();
            $replyCalificacion=null;
            $jsonResultCalificacion=null;
            $DatosCalificacionAjustador[0]=$jsonGetCalificacionAjustador->{"SetAjustadorIdCalificacion"};
            $DatosCalificacionAjustador[1]=$jsonGetCalificacionAjustador->{"SetSiniestroIdCalificacion"};
            $DatosCalificacionAjustador[2]=$jsonGetCalificacionAjustador->{"SetCalificacionId"};

            $ConsultaRegistrarCalificacionAjustador="insert into calificacionajustador(AjustadorId,SiniestroId,CalificacionId) values(?,?,?)";
            $RegistrarCalificacionAjustador=$database->insertRow($ConsultaRegistrarCalificacionAjustador,$DatosCalificacionAjustador);

            if($RegistrarCalificacionAjustador==true){
                $replyCalificacion=array("responseGradeAjustador"=>"1");

            }
            else{
                $replyCalificacion=array("responseGradeAjustador"=>"0");
            }

            $jsonResultCalificacion=json_encode($replyCalificacion);
            $wsReply=$jsonResultCalificacion;

    break;


    //End set values on Siniestro table--------------------------------------------------------------->

//Start, set ajustador siniestro data to webservice----------------------------------------------------->
   case 'getAjustadorInfoSiniestro':

      //Get necessary ajustador's info to show in the map marker.
      $jsonGetAjustadorInfoSiniestro = json_decode($value);
      $ajustadorSiniestroID = $jsonGetAjustadorInfoSiniestro->{'AjustadorId'};
      $replyAjustadorSiniestroInfo = null;
      $jsonResultAjustadorSiniestro = null;

      $consultaAjustadorSiniestro = 'select ajustador.AjustadorId,usuario.UsuarioId,usuario.UsuarioNombre,usuario.UsuarioApellidos,usuario.UsuarioTelefono,ajustador.AjustadorId
                                   from usuario
                                   inner join ajustador on ajustador.UsuarioId=usuario.UsuarioId
                                   where ajustador.AjustadorId=?';

      $getreplyAjustadorSiniestroData = $database->getRow($consultaAjustadorSiniestro, array($ajustadorSiniestroID));

      if ($getreplyAjustadorSiniestroData != false) {
          $replyAjustadorSiniestroInfo = array('getReplyAjustadorSiniestro' => '1', 'AjustadorId' => $getreplyAjustadorSiniestroData['AjustadorId'], 'UsuarioNombre' => $getreplyAjustadorSiniestroData['UsuarioNombre'],
           'UsuarioApellidos' => $getreplyAjustadorSiniestroData['UsuarioApellidos'], 'UsuarioTelefono' => $getreplyAjustadorSiniestroData['UsuarioTelefono'], );
      } else {
          $replyAjustadorSiniestroInfo = array('getReplyAjustadorSiniestro' => '0', 'AjustadorId' => '0', 'UsuarioNombre' => '', 'UsuarioApellidos' => '', 'UsuarioTelefono' => '');
      }

      $jsonResultAjustadorSiniestro = json_encode($replyAjustadorSiniestroInfo);

      $wsReply = $jsonResultAjustadorSiniestro;

   break;

//End set ajustador siniestro data to webservice------------------------------------------------------>

   case 'setAsistenciaVialAsegurado':
        $querySizeAsistenciaVial = $database->getRow('SELECT COUNT( * ) AS totalAsisteciaVial FROM asistenciavial');
        $replyAsistenciaVial = null;
        $jsonResultSetAsistenciaVial = null;

        $fechaAsistenciaVial = date('Y-m-d');
        $horaAsistenciaVial = date('H:i:s');

        if ($querySizeAsistenciaVial != false) {
            $database->beginTransactionDB();
            $jsonGetAseguradoAsistenciaVial = json_decode(utf8_encode($value));
            $infoAsistenciaVial = array();
            //$infoAsistenciaVial[0] = SeguridadSistema::generarClaveAsistenciaVial(8, ($querySizeAsistenciaVial['totalAsisteciaVial'] + 1));
            $infoAsistenciaVial[0] = '';
            $infoAsistenciaVial[1] = $jsonGetAseguradoAsistenciaVial->{'TipoAsistenciaVialId'};
            $infoAsistenciaVial[2] = $fechaAsistenciaVial.' '.$horaAsistenciaVial;
            $infoAsistenciaVial[3] = $jsonGetAseguradoAsistenciaVial->{'PolizaId'};
            $infoAsistenciaVial[4] = $jsonGetAseguradoAsistenciaVial->{'ProveedorId'};
            $infoAsistenciaVial[5] = $jsonGetAseguradoAsistenciaVial->{'AsistenciaVialUbicacion'};
            $infoAsistenciaVial[6] = $jsonGetAseguradoAsistenciaVial->{'AsistenciaVialObservaciones'};
            $infoAsistenciaVial[7] = $jsonGetAseguradoAsistenciaVial->{'EstatusAsistenciaVialId'};

            $consultaRegistrarAsistenciaVial = 'insert into asistenciavial(AsistenciaVialFolio,TipoAsistenciaVialId,AsistenciaVialFechaEmision,PolizaId,
                                              ProveedorId,AsistenciaVialUbicacion,AsistenciaVialObservaciones,EstatusAsistenciaVialId) values(?,?,?,?,?,?,?,?)';
            $registrarDatosAsistenciaVial = $database->insertRow($consultaRegistrarAsistenciaVial, $infoAsistenciaVial);

            if ($registrarDatosAsistenciaVial != false) {
                $getAsistenciaVialLastId = $database->lastIdDB();
                $AgregarAsistenciaVialFolio=SeguridadSistema::generarClaveAsistenciaVial(8,$getAsistenciaVialLastId);
                $ConsultaAgregarFolioAsistenciaVial="update asistenciavial set AsistenciaVialFolio='".$AgregarAsistenciaVialFolio."' where AsistenciaVialId=?";
                $AgregarFolioAsistenciaVialDB=$database->updateRow($ConsultaAgregarFolioAsistenciaVial,array($getAsistenciaVialLastId));
                if($AgregarFolioAsistenciaVialDB==true){
                  $database->commitDB();
                  $replyAsistenciaVial = array('getReplyAsistenciaVial' => '1', 'AsistenciaVialId' => $getAsistenciaVialLastId, 'ProveedorId' => $jsonGetAseguradoAsistenciaVial->{'ProveedorId'},
                                              'TipoAsistenciaVialId' => $jsonGetAseguradoAsistenciaVial->{'TipoAsistenciaVialId'}, 'FechaAsistenciaVial' => $fechaAsistenciaVial, 'HoraAsistenciaVial' => $horaAsistenciaVial,
                                              'EstatusAsistenciaVialId' => '0', );
                }
                else{
                    $database->rollBackDB();
                    $replyAsistenciaVial = array('getReplyAsistenciaVial' => '0', 'AsistenciaVialId' => '0', 'ProveedorId' => '0', 'TipoAsistenciaVialId' => '0', 'FechaAsistenciaVial' => '', 'HoraAsistenciaVial' => '', 'EstatusAsistenciaVialId' => '0');
                }

            }
            else {
                $database->rollBackDB();
                $replyAsistenciaVial = array('getReplyAsistenciaVial' => '0', 'AsistenciaVialId' => '0', 'ProveedorId' => '0', 'TipoAsistenciaVialId' => '0', 'FechaAsistenciaVial' => '', 'HoraAsistenciaVial' => '', 'EstatusAsistenciaVialId' => '0');
            }
            $jsonResultSetAsistenciaVial = json_encode($replyAsistenciaVial);
        }

      $wsReply = $jsonResultSetAsistenciaVial;

   break;

   case 'cancelSiniestro':
         $jsonGetSiniestroCancelado = json_decode($value);
         $cancelInfoSiniestro = array();
         $cancelInfoSiniestro[0] = $jsonGetSiniestroCancelado->{'EstatusSiniestroCancelado'};
         $cancelInfoSiniestro[1] = $jsonGetSiniestroCancelado->{'SiniestroIdCancelado'};

         $cancelarSiniestroConsulta = 'update siniestro set SiniestroEstatus=? where SiniestroId=?';

         $cancelarSiniestro = $database->updateRow($cancelarSiniestroConsulta, $cancelInfoSiniestro);

         if ($cancelarSiniestro != false) {
             $replyCancelarSiniestro = array('getReplyCancelSiniestro' => '1', 'getCancelSiniestroId' => $jsonGetSiniestroCancelado->{'SiniestroIdCancelado'});
         } else {
             $replyCancelarSiniestro = array('getReplyCancelSiniestro' => '0', 'getCancelSiniestroId' => '0');
         }

          $jsonResultCancelarSiniestro = json_encode($replyCancelarSiniestro);
          $wsReply = $jsonResultCancelarSiniestro;

   break;


   case "updateListPoliza":
         $sizeListaPoliza=0;
         $contadorListPoliza=0;
         $jsonInfoAsegurado=json_decode($value);
         $AseguradoInfoUpdatePoliza=array();
         $AseguradoInfoUpdatePoliza[0]=$jsonInfoAsegurado->{"AseguradoId"};
         $AseguradoInfoUpdatePoliza[1]=$jsonInfoAsegurado->{"PolizaId"};
         $NewPolizas=null;
         $jsonNewPolizas=null;
         $ConsultaUpdateListPoliza="SELECT poliza.* , tipopoliza.TipoPolizaDescripcion, tipopoliza.TipoPolizaCobertura,
                    vehiculo.TipoVehiculoId,tipovehiculo.TipoVehiculoDescripcion,vehiculo.VehiculoDescripcion,vehiculo.VehiculoMarca, vehiculo.VehiculoModelo,
                    vehiculo.Vehiculocapacidad,vehiculo.TipoServicioId,tiposervicio.TipoServicioDescripcion,vehiculo.VehiculoUso,
                    vehiculo.VehiculoPlacas,vehiculo.VehiculoZonaCirculacion,vehiculo.AseguradoId,
                    vehiculo.VehiculoPuertas,vehiculo.VehiculoColor,vehiculo.VehiculoNumMotor,vehiculo.VehiculoNumSerie
                         FROM poliza
                         INNER JOIN tipopoliza ON tipopoliza.TipoPolizaId = poliza.TipoPolizaId
                         INNER JOIN vehiculo ON vehiculo.VehiculoId=poliza.VehiculoId
            						 inner join tipovehiculo on vehiculo.TipoVehiculoId=tipovehiculo.TipoVehiculoId
            						 inner join tiposervicio on vehiculo.TipoServicioId=tiposervicio.TipoServicioId
                         WHERE poliza.AseguradoID =?
                         and poliza.PolizaId>?
                         AND poliza.EstatusPolizaId =1";

                                  $UpdateListPoliza=$database->getRows($ConsultaUpdateListPoliza,$AseguradoInfoUpdatePoliza);

                                  if($UpdateListPoliza==true){

                                         $sizeListaPoliza=sizeof($UpdateListPoliza);



                                         if($sizeListaPoliza>0){


                                               foreach ($UpdateListPoliza as $rsUpdatePoliza) {
                                                  if($contadorListPoliza==0){
                                                        $NewPolizas=array(($contadorListPoliza)=>array("getReplyUpdatePoliza"=>"1","PolizaId"=>$rsUpdatePoliza["PolizaId"],"NumPoliza"=>$rsUpdatePoliza["NumPoliza"],
                                                        "AseguradoId"=>$rsUpdatePoliza["AseguradoID"],"PolizaFechaAlta"=>$rsUpdatePoliza["PolizaFechaAlta"],"PolizaVigencia"=>$rsUpdatePoliza["PolizaVigencia"],
                                                        "TipoPolizaId"=>$rsUpdatePoliza["TipoPolizaId"],"EstatusPolizaId"=>$rsUpdatePoliza["EstatusPolizaId"],"VehiculoId"=>$rsUpdatePoliza["VehiculoId"],
                                                        "PolizaSumaAsegurada"=>$rsUpdatePoliza["PolizaSumaAsegurada"],"PolizaClausulaDeducible"=>$rsUpdatePoliza["PolizaClausulaDeducible"],
                                                        "TipoPolizaDescripcion"=>$rsUpdatePoliza["TipoPolizaDescripcion"],"TipoPolizaCobertura"=>$rsUpdatePoliza["TipoPolizaCobertura"],
                                                        "TipoVehiculoId"=>$rsUpdatePoliza["TipoVehiculoId"],"TipoVehiculoDescripcion"=>$rsUpdatePoliza["TipoVehiculoDescripcion"],
                                                        "VehiculoDescripcion"=>$rsUpdatePoliza["VehiculoDescripcion"],"VehiculoMarca"=>$rsUpdatePoliza["VehiculoMarca"],"VehiculoModelo">$rsUpdatePoliza["VehiculoModelo"],
                                                        "Vehiculocapacidad"=>$rsUpdatePoliza["Vehiculocapacidad"],"TipoServicioId"=>$rsUpdatePoliza["TipoServicioId"],
                                                        "TipoServicioDescripcion"=>$rsUpdatePoliza["TipoServicioDescripcion"],"VehiculoUso"=>$rsUpdatePoliza["VehiculoUso"],
                                                        "VehiculoPlacas"=>$rsUpdatePoliza["VehiculoPlacas"],"VehiculoZonaCirculacion"=>$rsUpdatePoliza["VehiculoZonaCirculacion"],
                                                        "AseguradoId"=>$rsUpdatePoliza["AseguradoId"],"VehiculoPuertas"=>$rsUpdatePoliza["VehiculoPuertas"],"VehiculoColor"=>$rsUpdatePoliza["VehiculoColor"],
                                                        "VehiculoNumMotor"=>$rsUpdatePoliza["VehiculoNumMotor"],"VehiculoNumSerie"=>$rsUpdatePoliza["VehiculoNumSerie"]));
                                                  }
                                                  else{
                                                        $NewPolizas+=array(($contadorListPoliza)=>array("getReplyUpdatePoliza"=>"1","PolizaId"=>$rsUpdatePoliza["PolizaId"],"NumPoliza"=>$rsUpdatePoliza["NumPoliza"],
                                                        "AseguradoId"=>$rsUpdatePoliza["AseguradoID"],"PolizaFechaAlta"=>$rsUpdatePoliza["PolizaFechaAlta"],"PolizaVigencia"=>$rsUpdatePoliza["PolizaVigencia"],
                                                        "TipoPolizaId"=>$rsUpdatePoliza["TipoPolizaId"],"EstatusPolizaId"=>$rsUpdatePoliza["EstatusPolizaId"],"VehiculoId"=>$rsUpdatePoliza["VehiculoId"],
                                                        "PolizaSumaAsegurada"=>$rsUpdatePoliza["PolizaSumaAsegurada"],"PolizaClausulaDeducible"=>$rsUpdatePoliza["PolizaClausulaDeducible"],
                                                        "TipoPolizaDescripcion"=>$rsUpdatePoliza["TipoPolizaDescripcion"],"TipoPolizaCobertura"=>$rsUpdatePoliza["TipoPolizaCobertura"],
                                                        "TipoVehiculoId"=>$rsUpdatePoliza["TipoVehiculoId"],"TipoVehiculoDescripcion"=>$rsUpdatePoliza["TipoVehiculoDescripcion"],
                                                        "VehiculoDescripcion"=>$rsUpdatePoliza["VehiculoDescripcion"],"VehiculoMarca"=>$rsUpdatePoliza["VehiculoMarca"],"VehiculoModelo">$rsUpdatePoliza["VehiculoModelo"],
                                                        "Vehiculocapacidad"=>$rsUpdatePoliza["Vehiculocapacidad"],"TipoServicioId"=>$rsUpdatePoliza["TipoServicioId"],
                                                        "TipoServicioDescripcion"=>$rsUpdatePoliza["TipoServicioDescripcion"],"VehiculoUso"=>$rsUpdatePoliza["VehiculoUso"],
                                                        "VehiculoPlacas"=>$rsUpdatePoliza["VehiculoPlacas"],"VehiculoZonaCirculacion"=>$rsUpdatePoliza["VehiculoZonaCirculacion"],
                                                        "AseguradoId"=>$rsUpdatePoliza["AseguradoId"],"VehiculoPuertas"=>$rsUpdatePoliza["VehiculoPuertas"],"VehiculoColor"=>$rsUpdatePoliza["VehiculoColor"],
                                                        "VehiculoNumMotor"=>$rsUpdatePoliza["VehiculoNumMotor"],"VehiculoNumSerie"=>$rsUpdatePoliza["VehiculoNumSerie"]));
                                                  }


                                                       $contadorListPoliza++;
                                               }
                                         }
                                         else{

                                           $NewPolizas=array(($contadorListPoliza)=>array("getReplyUpdatePoliza"=>"0","PolizaId"=>"0","NumPoliza"=>"0","AseguradoId"=>"0","PolizaFechaAlta"=>"0",
                                           "PolizaVigencia"=>"0","TipoPolizaId"=>"0","EstatusPolizaId"=>"0","VehiculoId"=>"0","PolizaSumaAsegurada"=>"0","PolizaClausulaDeducible"=>"0",
                                           "TipoPolizaDescripcion"=>"0","TipoPolizaCobertura"=>"0","TipoVehiculoId"=>"0","TipoVehiculoDescripcion"=>"0","VehiculoDescripcion"=>"0","VehiculoMarca"=>"0",
                                           "VehiculoModelo">"0","Vehiculocapacidad"=>"0","TipoServicioId"=>"0","TipoServicioDescripcion"=>"0","VehiculoUso"=>"0","VehiculoPlacas"=>"0","VehiculoZonaCirculacion"=>"0",
                                           "AseguradoId"=>"0","VehiculoPuertas"=>"0","VehiculoColor"=>"0","VehiculoNumMotor"=>"0","VehiculoNumSerie"=>"0"));
                                                       $contadorListPoliza++;
                                         }
                                  }
                                  else{

                                    $NewPolizas=array(($contadorListPoliza)=>array("getReplyUpdatePoliza"=>"Error","PolizaId"=>"0","NumPoliza"=>"0","AseguradoId"=>"0","PolizaFechaAlta"=>"0",
                                    "PolizaVigencia"=>"0","TipoPolizaId"=>"0","EstatusPolizaId"=>"0","VehiculoId"=>"0","PolizaSumaAsegurada"=>"0","PolizaClausulaDeducible"=>"0",
                                    "TipoPolizaDescripcion"=>"0","TipoPolizaCobertura"=>"0","TipoVehiculoId"=>"0","TipoVehiculoDescripcion"=>"0","VehiculoDescripcion"=>"0","VehiculoMarca"=>"0",
                                    "VehiculoModelo">"0","Vehiculocapacidad"=>"0","TipoServicioId"=>"0","TipoServicioDescripcion"=>"0","VehiculoUso"=>"0","VehiculoPlacas"=>"0","VehiculoZonaCirculacion"=>"0",
                                    "AseguradoId"=>"0","VehiculoPuertas"=>"0","VehiculoColor"=>"0","VehiculoNumMotor"=>"0","VehiculoNumSerie"=>"0"));
                                          $contadorListPoliza++;
                                  }

                                  $jsonNewPolizas=json_encode($NewPolizas);
                                  $wsReply=$jsonNewPolizas;

   break;

//END ASEGURADO WEBSERVICES------------------------------------------------------------------------------------------------------------------------------------------------------------------------------>

//operador webservices----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

//End operador webservices

//Ajustador webservices----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

 //Get Poliza Table information from AseguradoId-------------------------------------------------------------------------------------->
 case 'getAseguradoData':

  $jsonIdAsegurado = json_decode($value);
  $jsonReturnAseguradoValues = null;
  $replyAseguradoData = null;

  $AseguradoId = $jsonIdAsegurado->{'AseguradoId'};
  $consultaAsegurado = 'SELECT usuario.UsuarioId, usuario.UsuarioNombre, usuario.UsuarioApellidos, usuario.UsuarioTelefono, tipousuario.TipoUsuarioDescripcion
                        FROM usuario
                        INNER JOIN tipousuario on usuario.TipoUsuarioId = tipousuario.TipoUsuarioId
                        INNER JOIN asegurado on usuario.UsuarioId = asegurado.UsuarioId
                        WHERE asegurado.AseguradoId = ?';

  $getreplyAseguradoData = $database->getRow($consultaAsegurado, array($AseguradoId));

  if ($getreplyAseguradoData != false) {
      $replyDataAsegurado = array('replyAsegurado' => '1', 'UsuarioId' => $getreplyAseguradoData['UsuarioId'], 'UsuarioNombre' => $getreplyAseguradoData['UsuarioNombre'], 'UsuarioApellidos' => $getreplyAseguradoData['UsuarioApellidos'],
        'UsuarioTelefono' => $getreplyAseguradoData['UsuarioTelefono'], 'TipoUsuarioDescripcion' => $getreplyAseguradoData['TipoUsuarioDescripcion'], );
  }

  $jsonReturnAseguradoValues = json_encode($replyDataAsegurado);
  $wsReply = $jsonReturnAseguradoValues;

 break;

case 'getSiniestro':

    $jsonIdAsegurado_siniestro = json_decode($value);
    $jsonReturnSiniestroValues = null;
    $replySiniestroData = null;

    $AseguradoId_siniestro = $jsonIdAsegurado_siniestro->{'AseguradoId'};
    $SiniestroId_siniestro = $jsonIdAsegurado_siniestro->{'SiniestroId'};
    $consultaSiniestro = 'SELECT siniestro.SiniestroId, siniestro.polizaId, siniestro.SiniestroUbicacion, siniestro.SiniestroFolio, siniestro.SiniestroOcurrido, siniestro.SiniestroHora,
	                        poliza.NumPoliza, poliza.PolizaFechaAlta, poliza.PolizaVigencia, usuario.UsuarioId, usuario.UsuarioNombre,
                          usuario.UsuarioApellidos, usuario.UsuarioTelefono, vehiculo.VehiculoId, vehiculo.VehiculoDescripcion,
                          vehiculo.VehiculoMarca, vehiculo.VehiculoModelo, vehiculo.Vehiculocapacidad, vehiculo.VehiculoUso, vehiculo.VehiculoPlacas,
                          vehiculo.VehiculoZonaCirculacion, vehiculo.VehiculoPuertas, vehiculo.VehiculoColor, vehiculo.VehiculoNumMotor, vehiculo.VehiculoNumSerie
	                        FROM siniestro
                          INNER JOIN poliza on siniestro.PolizaId=poliza.PolizaId
	                        INNER JOIN asegurado on asegurado.AseguradoId=poliza.AseguradoID
	                        INNER JOIN usuario on asegurado.UsuarioId=usuario.UsuarioId
	                        INNER JOIN ajustador on siniestro.AjustadorId=ajustador.AjustadorId
	                        INNER JOIN vehiculo on poliza.AseguradoID = vehiculo.AseguradoId
	                        WHERE asegurado.AseguradoId = ? and siniestro.SiniestroId=?';

    $getReplySiniestroData = $database->getRow($consultaSiniestro, array($AseguradoId_siniestro, $SiniestroId_siniestro));

    if ($getReplySiniestroData != false) {
        $replyDataSiniestro = array('replySiniestro' => '1', 'SiniestroId' => $getReplySiniestroData['SiniestroId'], 'polizaId' => $getReplySiniestroData['polizaId'],
        'SiniestroUbicacion' => $getReplySiniestroData['SiniestroUbicacion'], 'SiniestroFolio' => $getReplySiniestroData['SiniestroFolio'], 'SiniestroOcurrido' => $getReplySiniestroData['SiniestroOcurrido'],
        'SiniestroHora' => $getReplySiniestroData['SiniestroHora'], 'NumPoliza' => $getReplySiniestroData['NumPoliza'],
        'PolizaFechaAlta' => $getReplySiniestroData['PolizaFechaAlta'], 'PolizaVigencia' => $getReplySiniestroData['PolizaVigencia'], 'UsuarioId' => $getReplySiniestroData['UsuarioId'],
        'UsuarioNombre' => $getReplySiniestroData['UsuarioNombre'], 'UsuarioApellidos' => $getReplySiniestroData['UsuarioApellidos'], 'UsuarioTelefono' => $getReplySiniestroData['UsuarioTelefono'],
        'VehiculoId' => $getReplySiniestroData['VehiculoId'], 'VehiculoDescripcion' => $getReplySiniestroData['VehiculoDescripcion'], 'VehiculoMarca' => $getReplySiniestroData['VehiculoMarca'],
        'VehiculoModelo' => $getReplySiniestroData['VehiculoModelo'], 'Vehiculocapacidad' => $getReplySiniestroData['Vehiculocapacidad'], 'VehiculoUso' => $getReplySiniestroData['VehiculoUso'],
        'VehiculoPlacas' => $getReplySiniestroData['VehiculoPlacas'], 'VehiculoZonaCirculacion' => $getReplySiniestroData['VehiculoZonaCirculacion'], 'VehiculoPuertas' => $getReplySiniestroData['VehiculoPuertas'],
        'VehiculoColor' => $getReplySiniestroData['VehiculoColor'], 'VehiculoNumMotor' => $getReplySiniestroData['VehiculoNumMotor'], 'VehiculoNumSerie' => $getReplySiniestroData['VehiculoNumSerie'], );
    }

    $jsonReturnSiniestroValues = json_encode($replyDataSiniestro);
    $wsReply = $jsonReturnSiniestroValues;

break;

case 'getIdAjustador':

    $jsonIdAjustador = json_decode($value);
    $jsonReturnAjustadorValue = null;
    $replyAjustadorData = null;

    $_AjustadorId = $jsonIdAjustador->{'UsuarioId'};
    $consultaAjustador = 'SELECT ajustador.AjustadorId
                          FROM usuario
                          INNER JOIN tipousuario on usuario.TipoUsuarioId = tipousuario.TipoUsuarioId
                          INNER JOIN ajustador on usuario.UsuarioId = ajustador.UsuarioId
                          WHERE usuario.UsuarioId = ?';

    $getReplyAjustadorData = $database->getRow($consultaAjustador, array($_AjustadorId));

    if ($getReplyAjustadorData != false) {
        $replyDataAjustador = array('replyAjustador' => '1', 'AjustadorId' => $getReplyAjustadorData['AjustadorId']);
    }

    $jsonReturnAjustadorValue = json_encode($replyDataAjustador);
    $wsReply = $jsonReturnAjustadorValue;

break;

case 'setDatosConductor': //DONE

          $jsonConductorInfo = json_decode(utf8_encode($value));
          $descripcionDiaAccidente = $jsonConductorInfo->{'DiaAccidenteDescripcion'};
          $IdentificacionConductorInfoDB = array();
          $ConductorInfoDB = array();
          $replyConductorInfo = null;
          $jsonResultSetConductor = null;
          $database->beginTransactionDB();

          $IdentificacionConductorInfoDB[0] = $jsonConductorInfo->{'TipoIdentificacionConductorId'};
          $IdentificacionConductorInfoDB[1] = $jsonConductorInfo->{'IdentificacionNumero'};
          $IdentificacionConductorInfoDB[2] = $jsonConductorInfo->{'IdentificacionVencimiento'};
          $IdentificacionConductorInfoDB[3] = $jsonConductorInfo->{'IdentificacionDescripcion'};

          $consultaRegistroInfoIdentificacionConductor = 'insert into identificacion(TipoIdentificacionConductorId,IdentificacionNumero,IdentificacionVencimiento,IdentificacionDescripcion) '
                                                        .'values(?,?,?,?)';

          $registrarIdentificacionConductor = $database->insertRow($consultaRegistroInfoIdentificacionConductor,  $IdentificacionConductorInfoDB);

          ///*******************************************************************

          if ($registrarIdentificacionConductor == true) {
              $getIdentificacionConductorLastId = $database->lastIdDB(); //Se obtiene el id de la última identicacion del conductor

            $consultaRegistroDiaAccidenteDescripcion = 'insert into diaaccidente(DiaAccidenteDescripcion) values(?)';
              $registrarDiaAccidenteDescripcion = $database->insertRow($consultaRegistroDiaAccidenteDescripcion, array($descripcionDiaAccidente));
              $getDiaAccidenteDescripcionLastId = $database ->lastIdDB();

              if ($registrarDiaAccidenteDescripcion == true) {
                  $ConductorInfoDB[0] = $jsonConductorInfo->{'ConductorNombreCompleto'};
                  $ConductorInfoDB[1] = $jsonConductorInfo->{'ConductorSexo'};
                  $ConductorInfoDB[2] = $jsonConductorInfo->{'ConductorLugarHechos'};
                  $ConductorInfoDB[3] = $getIdentificacionConductorLastId;
                  $ConductorInfoDB[4] = intval($jsonConductorInfo->{'ConductorEdad'});
                  $ConductorInfoDB[5] = $getDiaAccidenteDescripcionLastId;
                  $ConductorInfoDB[6] = $jsonConductorInfo->{'ConductorDireccion'};
                  $ConductorInfoDB[7] = $jsonConductorInfo->{'ConductorTel'};
                  $ConductorInfoDB[8] = $jsonConductorInfo->{'ConductorNarracion'};

                  $consultaRegistroInfoConductor = 'insert into conductor(ConductorNombreCompleto,ConductorSexo,ConductorLugarHechos,IdentificacionId,'.
                                                          'ConductorEdad,DiaAccidenteId,ConductorDireccion,ConductorTel,ConductorNarracion) values(?,?,?,?,?,?,?,?,?)';

                  $registrarInfoConductor = $database->insertRow($consultaRegistroInfoConductor, $ConductorInfoDB);

                  if ($registrarInfoConductor == true) {
                      $ConductorLastId = $database->lastIdDB();
                      $database->commitDB();
                      $replyConductorInfo = array('replySetConductor' => '1', 'ConductorId' => $ConductorLastId, 'ConductorNombreCompleto' => $jsonConductorInfo->{'ConductorNombreCompleto'});
                  } else {
                      $database->rollBackDB();
                      $replyConductorInfo = array('replySetConductor' => '0', 'ConductorId' => '0', 'ConductorNombreCompleto' => '0');
                  }
              }
          }
                  $jsonResultSetConductor = json_encode($replyConductorInfo);
                $wsReply = $jsonResultSetConductor;

break;

case 'setTipoAccidente'://DONE

    $jsonTipoAccidente = json_decode(utf8_encode($value));
    $TipoAccidenteInfo = array();
    $replyTipoAccidente = null;
    $jsonResultTipoAccidente = null;
    $database->beginTransactionDB();
    $consultaConductorId = 'SELECT conductor.ConductorId FROM conductor order by ConductorId desc limit 1';
    $getReplyConductor = $database->getRow($consultaConductorId,array($siniestro));

    $consultaSiniestroOcurrido ='SELECT DiaAccidenteDescripcion FROM diaaccidente INNER JOIN conductor on conductor.DiaAccidenteId=diaaccidente.DiaAccidenteId WHERE ConductorId=?';

    $getReplySiniestroOcurrio = $database->getRow($consultaSiniestroOcurrido,array(intval($getReplyConductor['ConductorId'])));




    $TipoAccidenteInfo[0] = $jsonTipoAccidente->{'TipoAccidenteId'};
    $TipoAccidenteInfo[1] = $jsonTipoAccidente->{'TipoAccidenteComentarios'};
    $TipoAccidenteInfo[2] = $jsonTipoAccidente->{'SiniestroTerceros'};
    $TipoAccidenteInfo[3] = intval($getReplyConductor['ConductorId']);
    $TipoAccidenteInfo[4] = $getReplySiniestroOcurrio['DiaAccidenteDescripcion'];
    $TipoAccidenteInfo[5] = $jsonTipoAccidente->{'SiniestroFolio'};

    $replyTipoAccidente = null;
    $jsonResultTipoAccidente = null;


    $consultaDescripcionTipoAccidente = 'update siniestro set TipoAccidenteId=?,TipoAccidenteComentarios=?,SiniestroTerceros=?,ConductorId=?,SiniestroOcurrido=? where SiniestroFolio=?';

    $ActualizarTipoAccidente = $database->updateRow($consultaDescripcionTipoAccidente, $TipoAccidenteInfo);

    if ($ActualizarTipoAccidente == true) {
        if ($TipoAccidenteInfo[0] == 4) {


          $queryId = 'select SiniestroId from siniestro where SiniestroFolio=?';
          $getReplyId = $database->getRow($queryId,array($TipoAccidenteInfo[5]));


            $siniestroImagenes = array();
            $siniestroImagenes[0] = intval($getReplyId['SiniestroId']);
            $siniestroImagenes[1] =$jsonTipoAccidente->{'SiniestroImagenDelantera'};
            $siniestroImagenes[2] = $jsonTipoAccidente->{'SiniestroImagenTrasera'};


            $consultarSiniestroImagenes = 'insert into siniestroImg(SiniestroId,SiniestroImagenDelantera, SiniestroImagenTrasera) values(?,?,?)';

            $RegistrarImagenes = $database->insertRow($consultarSiniestroImagenes, $siniestroImagenes);

            if (($RegistrarImagenes == true) && ($ActualizarTipoAccidente==true)) {
              $database->commitDB();
                $replyTipoAccidente = array('getReplyTipoAccidente' => '1', 'TipoAccidenteId' => $jsonTipoAccidente->{'TipoAccidenteId'}, 'TipoAccidenteComentarios' => $jsonTipoAccidente->{'TipoAccidenteComentarios'});
            } else {
              $database->rollBackDB();
                $replyTipoAccidente = array('getReplyTipoAccidente' => '0', 'TipoAccidenteId' => '0', 'TipoAccidenteComentarios' => '0');
            }
        }else{
          $database->commitDB();
            $replyTipoAccidente = array('getReplyTipoAccidente' => '1', 'TipoAccidenteId' => $jsonTipoAccidente->{'TipoAccidenteId'}, 'TipoAccidenteComentarios' => $jsonTipoAccidente->{'TipoAccidenteComentarios'});

        }

    }
    $jsonResultTipoAccidente = json_encode($replyTipoAccidente);
    $wsReply = $jsonResultTipoAccidente;

break;

case 'setDatosTercero'://DONE

      $jsonDatosTercero = json_decode(utf8_encode($value));
      $replyTerceros =null;
      $jsonResultTerceros = null;
      $IdentificacionTerceroInfo = array();
      $database->beginTransactionDB();
      $IdentificacionTerceroInfo[0] = $jsonDatosTercero->{'TipoIdentificacionConductorId'};
      $IdentificacionTerceroInfo[1] = $jsonDatosTercero->{'IdentificacionNumero'};
      $IdentificacionTerceroInfo[2] = $jsonDatosTercero->{'IdentificacionVencimiento'};
      $IdentificacionTerceroInfo[3] = $jsonDatosTercero->{'IdentificacionDescripcion'};
      $DatosVehiculoTercero = array();
      $DatosVehiculoTercero[0] = $jsonDatosTercero->{'VehiculoTerceroDescripcion'};
      $DatosVehiculoTercero[1] = $jsonDatosTercero->{'VehiculoTerceroMarca'};
      $DatosVehiculoTercero[2] = $jsonDatosTercero->{'VehiculoTerceroModelo'};
      $DatosVehiculoTercero[3] = $jsonDatosTercero->{'VehiculoTerceroPlacas'};

      $consultaRegistroIdentificacionTercero = 'insert into identificacion(TipoIdentificacionConductorId,IdentificacionNumero,IdentificacionVencimiento,IdentificacionDescripcion) '.
                                              'values(?,?,?,?)';

      $registrarIdentificacionTercero = $database->insertRow($consultaRegistroIdentificacionTercero, $IdentificacionTerceroInfo);

      if ($registrarIdentificacionTercero == true) {
          $getIdentificacionTerceroLastId = $database->lastIdDB();

          $consultaRegistroVehiculoTercero = 'insert into vehiculotercero(VehiculoTerceroDescripcion,VehiculoTerceroMarca,VehiculoTerceroModelo,VehiculoTerceroPlacas) '.
                                                 'values(?,?,?,?)';

          $registrarVehiculoTercero = $database->insertRow($consultaRegistroVehiculoTercero, $DatosVehiculoTercero);


          if ($registrarVehiculoTercero == true) {
              $getVehiculoTerceroLastId = $database->lastIdDB();


              $queryIdT = 'select SiniestroId from siniestro where SiniestroFolio=?';
              $getReplyIdT = $database->getRow($queryIdT,array($jsonDatosTercero->{'SiniestroFolio'}));

              $DatosTerceroInfo = array();
              $DatosTerceroInfo[0] = $jsonDatosTercero->{'TerceroNombre'};
              $DatosTerceroInfo[1] = $jsonDatosTercero->{'TerceroTelefono'};
              $DatosTerceroInfo[2] = $jsonDatosTercero->{'TerceroDireccion'};
              $DatosTerceroInfo[3] = $getIdentificacionTerceroLastId; //IdentificaionId
              $DatosTerceroInfo[4] = $jsonDatosTercero->{'TerceroEstaAsegurado'};
              $DatosTerceroInfo[5] = $jsonDatosTercero->{'TerceroAseguradora'};
              $DatosTerceroInfo[6] = $jsonDatosTercero->{'TerceroNumeroPoliza'};
              $DatosTerceroInfo[7] = $getVehiculoTerceroLastId; //VehiculoTerceroId
              $DatosTerceroInfo[8] = intval($getReplyIdT['SiniestroId']);
              $DatosTerceroInfo[9] = date('Y-m-d G:i:s');
              $DatosTerceroInfo[10] = $jsonDatosTercero->{'TerceroEmail'};

              $consultaRegistroDatosTercero = 'insert into tercero (TerceroNombreCompleto,TerceroTelefono,TerceroDireccion,IdentificacionId,TerceroEstaAsegurado,TerceroAseguradora,'.
                                                     'TerceroNumeroPoliza,VehiculoTerceroId,SiniestroId,TerceroFechaCreacion,TerceroEmail)'.
                                                     'values(?,?,?,?,?,?,?,?,?,?,?)';

              $registroDatosTercero = $database->insertRow($consultaRegistroDatosTercero, $DatosTerceroInfo);

              if ($registroDatosTercero == true) {

                $queryId = 'select SiniestroId from siniestro where SiniestroFolio=?';
                $getReplyId = $database->getRow($queryId,array($jsonDatosTercero->{'SiniestroFolio'}));

                    $siniestroImagenes = array();
                    $siniestroImagenes[0] = intval($getReplyId['SiniestroId']);
                    $siniestroImagenes[1] = $jsonDatosTercero->{'TerceroImagenDelantera'};
                    $siniestroImagenes[2] = $jsonDatosTercero->{'TerceroImagenTrasera'};

                    $consultarSiniestroImagenes = 'insert into terceroImg(SiniestroId,TerceroImagenDelantera, TerceroImagenTrasera) values(?,?,?)';

                    $RegistrarImagenes = $database->insertRow($consultarSiniestroImagenes, $siniestroImagenes);

                    if ($RegistrarImagenes == true) {
                      $database->commitDB();
                        $replyTerceros = array('getReplyTercero' => '1', '' => $jsonDatosTercero->{'TerceroNombre'});
                    } else {
                        $database->rollBackDB();
                        $replyTerceros = array('getReplyTercero' => '0', 'TerceroNombre' => '0', 'TipoAccidenteComentarios' => '0');
                    }
              }else{
                $database->commitDB();
                  $replyTerceros = array('getReplyTercero' => '1', '' => $jsonDatosTercero->{'TerceroNombre'});
              }
          }
      }
      $jsonResultTerceros = json_encode($replyTerceros);
      $wsReply = $jsonResultTerceros;

break; //End case setDatosTercero-------------------------------------------------------------------------------------------------------------------------------------->

case 'setDatosTestigo': //DONE

        $jsonTestigoSiniestro = json_decode(utf8_encode($value));

        $siniestro = array();
        $siniestro[0] = $jsonTestigoSiniestro->{'SiniestroFolio'};

        $consultaSiniestroId = 'SELECT SiniestroId FROM siniestro where SiniestroFolio=?';

        $getReplySiniestro = $database->getRow($consultaSiniestroId,$siniestro);

        $DatosTestigoSiniestro = array();
        $DatosTestigoSiniestro[0] = $jsonTestigoSiniestro->{'TestigoNombre'};
        $DatosTestigoSiniestro[1] = $jsonTestigoSiniestro->{'TestigoApellidos'};
        $DatosTestigoSiniestro[2] = $jsonTestigoSiniestro->{'TestigoComentarios'};
        $DatosTestigoSiniestro[3] = $getReplySiniestro['SiniestroId'];

        $consultaRegistroTestigoSiniestro = 'insert into testigosiniestro(TestigoNombre,TestigoApellidos,TestigoComentarios,SiniestroId) values(?,?,?,?)';

        $registrarTestigoSiniestro = $database->insertRow($consultaRegistroTestigoSiniestro , $DatosTestigoSiniestro);

        if ($registrarTestigoSiniestro == true) {

        }

break;

case 'setStatusSiniestro':
      $jsonGetSiniestroEstatus = json_decode($value);
      $statusInfoSiniestro = array();
      $statusInfoSiniestro[0] = $jsonGetSiniestroEstatus->{'SiniestroEstatus'};
      $statusInfoSiniestro[1] = $jsonGetSiniestroEstatus->{'SiniestroFolio'};

      $estatusSiniestroConsulta = 'update siniestro set SiniestroEstatus=? where SiniestroFolio=?';

      $estatusSiniestro = $database->updateRow($estatusSiniestroConsulta, $statusInfoSiniestro);

break;

case 'setStatusSiniestroApp':
      $jsonGetSiniestroEstatus = json_decode($value);
      $statusInfoSiniestro = array();
      $statusInfoSiniestro[0] = $jsonGetSiniestroEstatus->{'StatusSiniestro'};
      $statusInfoSiniestro[1] = $jsonGetSiniestroEstatus->{'SiniestroId'};

      $estatusSiniestroConsulta = 'update siniestro set SiniestroEstatus=? where SiniestroId=?';

      $estatusSiniestro = $database->updateRow($estatusSiniestroConsulta, $statusInfoSiniestro);

      if($estatusSiniestro==true){
            $_replyUserData = array('replySiniestro' => '1'); //Status cero
            $_jsonReturnUserValues = json_encode($_replyUserData);
            $wsReply = $_jsonReturnUserValues;
      }
      else{
        $_replyUserData = array('replySiniestro' => '0'); //Status cero
        $_jsonReturnUserValues = json_encode($_replyUserData);
        $wsReply = $_jsonReturnUserValues;
      }

break;

//End ajustador webservices-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------->

//End operador webservice

    default:
        // code...
        break;

  }//End webservice switch

  return $wsReply; //Return case value
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$servicio->service($HTTP_RAW_POST_DATA);
