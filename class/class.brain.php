<?php
session_start();
date_default_timezone_set('America/Mexico_City');
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 18/03/19
 * PROJECT: turno
 *
 * CARGAR LIBRERIAS O CLASES NECESARIAS PARA CADA SITUACIÓN
 * DESCRIPTION: IMPREME LAS LIBRERIAS NECESARIAS PARA CADA CASO
 *
 */
//------------ Variables locales ----------------
$nombreEmpresaCompleto = 'TURNO APP';
$nombreEmpresaCorto = 'TURNO';
$nombreEmpresaAcronimo = 'TRN';
$nombreEmpresaLegal = 'TURNO APP S.A. de C.V.';
//------------ END Variables locales ------------
include_once("./class/class.sesiones.php");
include_once("./class/class.mostrargeneral.php");
include_once("../class/class.conexion.php");
include_once("class/class.menu.php");
switch ($varUbicacion) {
	//Views
    case "login":
      break;
    case "securezone":
      include_once("../class/class.sesiones.php");
      include_once("../class/class.conexion.php");
      include_once("../class/class.sistemageneral.php");
      include_once("../class/class.seguridad.php");
      break;
    case "usuarios":
      include_once("class/class.mostrarusuario.php");
      include_once("class/class.mostrarplugins.php");
      include_once("class/class.seguridad.php");
      include_once("class/class.mostrarPerfilUsuario.php");
      break;
    case "categorias":
      include_once("class/class.mostrarplugins.php");
      include_once("class/class.seguridad.php");
      include_once("class/class.mostrarPerfilUsuario.php");
      break;
    case "wizard":
      include_once("./class/class.mostrarplugins.php");
      include_once("./class/class.mostrarwizard.php");
      include_once("./class/class.mostrarPerfilUsuario.php");
      break;
    case "tipoSesiones":
      include_once("./class/class.mostrarplugins.php");
      include_once("./class/class.mostrarPerfilUsuario.php");
      include_once("./class/class.mostrarTipoSesion.php");
      break;
    case "recursos":
      include_once("./class/class.mostrarplugins.php");
      include_once("./class/class.mostrarPerfilUsuario.php");
      include_once("./class/class.mostrarRecurso.php");
      break;
    case "horarios":
      include_once("./class/class.mostrarplugins.php");
      include_once("./class/class.mostrarPerfilUsuario.php");
      include_once("./class/class.mostrarHorario.php");
      break;
}