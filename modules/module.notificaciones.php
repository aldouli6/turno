<?php
/*
 * CREATOR: VELEZOFT
 * DEVELOPER: ALDO ULISES CORNEJO VELEZ
 * DATE: 12/04/19
 * PROJECT: turno
 *
 *
 */

$varUbicacion = 'securezone';
include_once("../class/class.brain.php");

$database = new db(); 
try {
    if(!empty($_POST)){            
        extract($_REQUEST);
        switch($cmd){
            case 'getNotificaciones':
                $sql="SELECT n.establecimientoId,n.notificacionId, n.estatus as estatusId_n, n.fecha_hora,
                    t.turnoId,   t.fecha, t.horaInicio, t.horaFin, t.estatusId estatusId_t,e.nombre estatus_t,
                    u.usuarioId,u.username,concat(u.nombre,' ', u.apellidos) unombre,
                    ts.tipoSesionId, ts.nombre,
                    r.recursoId, r.nombre rnombre
                    FROM notificaciones  n
                    INNER JOIN turnos t on t.turnoId=n.turnoId
                    INNER JOIN usuarios u on u.usuarioId=t.usuarioId
                    INNER JOIN tiposSesiones ts on ts.tipoSesionId=t.tipoSesionId
                    INNER JOIN estatus e on e.idestatus=t.estatusId
                    INNER JOIN recursos r on r.recursoId=t.recursoId
                    WHERE 1
                    AND n.establecimientoId = ".$establecimiento."
                    ORDER BY fecha_hora DESC "; 

                 $getElements = $database->getRows($sql);
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
            case 'getNotificacion':
                $sql="SELECT n.establecimientoId,n.notificacionId, n.estatus as estatusId_n, n.fecha_hora,n.hora_vista,
                    t.turnoId,   t.fecha, t.horaInicio, t.horaFin, t.estatusId estatusId_t, e.nombre estatus_t,
                    u.usuarioId,u.username,concat(u.nombre,' ', u.apellidos) unombre,
                    ts.tipoSesionId, ts.nombre,
                    r.recursoId, r.nombre rnombre
                    FROM notificaciones  n
                    INNER JOIN turnos t on t.turnoId=n.turnoId
                    INNER JOIN usuarios u on u.usuarioId=t.usuarioId
                    INNER JOIN tiposSesiones ts on ts.tipoSesionId=t.tipoSesionId
                    INNER JOIN estatus e on e.idestatus=t.estatusId
                    INNER JOIN recursos r on r.recursoId=t.recursoId
                    WHERE 1
                    AND t.estatusId = 8 
                    AND n.establecimientoId = ".$establecimiento."
                    AND n.notificacionId = ".$noti."
                    ORDER BY fecha_hora DESC "; 

                 $getElements = $database->getRow($sql);
                $jsonElements=json_encode($getElements);
                echo $jsonElements;
            break;
                
        }
    }
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
}

?>