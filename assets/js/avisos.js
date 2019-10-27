var estabId = $('#establecimientoId').val();
var steps=0
var statusColono = 1;
var cont = 0; // Variable para contabilizar los nuevos registros en tiempo real
var contReport = 0;
$(document).ready(function() {
    steps=$('#establecimientoStepping').val();
    botonhtml="<button id='btntest'>Test</button>";
    elmodal="";
    elmodal+='<div class="modal fade slide-up disable-scroll " id="modalAvisos" tabindex="-1" role="dialog" aria-hidden="true">'
    elmodal+='  <div class="modal-dialog modal-dialog-centered modal-lg" >'
    elmodal+='    <div class="modal-content-wrapper">'
    elmodal+='        <div class="modal-content">'
    elmodal+='            <div class="modal-header" id="modalAvisosHeader">'
    elmodal+='             <div class="row">'
    elmodal+='                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">'
    elmodal+='                  <span aria-hidden="true">&times;</span>'
    elmodal+='                  </button>'
    elmodal+='                 <div class="col-sm-12">'
    elmodal+='                     <h2 >Avisos</h2>'
    elmodal+='                 </div>'
    elmodal+='              </div>'
    elmodal+='            </div>'
    elmodal+='            <div class="modal-body" id="modalAvisosBody">'
    elmodal+='            </div>'
    elmodal+='            <div class="modal-footer" id="modalAvisosFooter">'
    elmodal+='            </div>'
    elmodal+='        </div>'
    elmodal+='    </div>'
    elmodal+='  </div>'
    elmodal+='</div>';
    //$('.content').prepend(botonhtml);
    $('.content').prepend(elmodal);
});
var timer = setInterval(function() {

    let now = new Date();
     if(now.getMinutes() % steps == 0 && now.getSeconds()==0){
        mes=parseInt(now.getMonth())+1;
        mes=(mes.length==1)?'0'+mes:mes;
        dia=now.getDate();
        dia=(dia.length==1)?'0'+dia:dia;
        hour=now.getHours();
        hour=(hour.length==1)?'0'+hour:hour;
        mins=now.getMinutes();
        mins=(mins.length==1)?'0'+mins:mins;
        hoy=now.getFullYear()+'-'+mes+'-'+dia;
        hora=hour+':'+mins+':00';
        if (!$('#modalAvisosBody').is(':visible')) {
            $('#modalAvisosBody').empty();
        }
        revisarTurnosActuales(hoy, hora, AGENDADO, 'horainicio');
        revisarTurnosActuales(hoy, hora, ATENDIENDO, 'horafin');
     }
    
}, 1000);
$('body').on('click','#btntest', function() {
    $.ajax({
        async: true,
        url: API_URL,
        type: 'GET',
        data: {
            task: "getEstablecimiento",
            id:estabId
        },
        success: function (obj) {
            if(obj.avisos==1){
                revisarTurnosActuales('2019-08-27', '09:30:00', '1', 'horainicio');
                revisarTurnosActuales('2019-08-27', '09:30:00', '3', 'horafin');
            }  
        }
    }); 
});
function revisarTurnosActuales(dia=null, hora=null, estado=0, revisar=null){
    
    var database  = firebase.database();
    var turnoRef  = database.ref(estabId + '-establecimiento/turno');
    turnoRef.on('child_added', (snapshot) => {
        var turno = snapshot.val();
        if(turno.fecha==dia && turno[revisar]==hora && turno.turnoEstatus==estado){
            creaAviso(turno);
            if (!$('#modalAvisosBody').is(':visible')) {
                $('#modalAvisos').modal('show');
            }
            
        }
    });
    
}
function creaAviso(turno) {
    fecha = new Date(
    turno.fecha.substring(0,4),
    parseInt(turno.fecha.substring(5,7))-1, 
    turno.fecha.substring(8,10)
        );
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    fecha=fecha.toLocaleDateString("es-Es", options);
    var html='';
    html+='<div id="turno'+turno.turnoId+'" class="turnoaviso">';
    html+='    <div class="row ">';
    html+='        <div class="col-xs-3 p-x-5" align="center">';
    html+='            <p>Turno #'+turno.turnoId+'</p>';
    html+='            <img style="max-width:67px" src="http://'+IP_LOCAL+':8888/turno/assets/img/profiles/usuarios_'+turno.usuarioId+'.png" alt="">';
    html+='                <div>';
    html+='                    <div class="col-xs-12 p-x-5">';
    html+='                        <p>@'+turno.username+'</p>';
    html+='                        <p><b>'+turno.nombre+'</b></p>';
    html+='                    </div>';
    html+='                </div>';
    html+='        </div>';
    html+='        <div class="col-xs-6 p-x-5">';
    html+='                <div class="col-xs-6 p-x-5">';
    html+='                    <small>Tipo de Sesión</small>';
    html+='                    <br><span><b>'+turno.ts_nombre+'</b></span>';
    html+='                </div>';
    html+='                <div class="col-xs-6 p-x-5">';
    html+='                    <small>Estado</small><br>';
    html+='                    <span><b>'+turno.e_nombre+'</b></span>';
    html+='                </div>';
    html+='            <div class="col-xs-12 p-x-5">';
    html+='                <small>Fecha</small><br>';
    html+='                <span><b>'+fecha+'</b></span>';
    html+='            </div>';
    html+='            <div class="row col-xs-12">';
    html+='                <div class="col-xs-6 p-x-5">';
    html+='                    <small>Desde</small><br>';
    html+='                    <span><b>'+turno.horainicio+'</b></span>';
    html+='                </div>';
    html+='                <div class="col-xs-6 p-x-5">';
    html+='                    <small>Hasta</small><br>';
    html+='                    <span><b>'+turno.horafin+'</b></span>';
    html+='                </div>';
    html+='            </div>';
    html+='        </div>';
    html+='        <div class="col-xs-3 p-x-5">';
    nuevoEstatus=(turno.turnoEstatus==AGENDADO)?ATENDIENDO:(turno.turnoEstatus==ATENDIENDO)?ATENDIDO:'';
    mensajeTurno=(turno.turnoEstatus==AGENDADO)?'Atender Turno!':(turno.turnoEstatus==ATENDIENDO)?'Turno Atendido':null;
    html+='           <button type="button" onclick="cambiarTurno('+nuevoEstatus+', '+turno.turnoId+')" class="btn btn-primary btn-block" aria-label="" >'+mensajeTurno+'</button>'; 
    html+='           <button type="button" onclick="cambiarTurno('+CANCELADO+', '+turno.turnoId+')" class="btn btn-danger btn-block" aria-label="" >Cancelar Turno!</button>'; 
    html+='        </div>';
    html+='    </div>';
    html+=' </div>';
    $('#modalAvisosBody').append(html);
    
}
function cambiarTurno(estatus=null, turnoId=null) {
    if(estatus!=null || turnoId!=null){
        if(estatus!=CANCELADO){
            $.ajax({
                async: true,
                url: API_URL+'?task=cambiarEstatusTurno&turnoId='+turnoId+'&estatus='+estatus+'&comentario=null&notificacion=true',
                type: "GET",
                success: function (response) {
                    
                    mensajeTurno=(estatus==ATENDIENDO)?' se está atendiendo.':(estatus==ATENDIDO)?' está atendido.':null;
                    var obj = response;
                    Swal.fire({
                        type: 'success',
                        title: 'El turno '+obj.turnoId+mensajeTurno,
                        timer: 2000,
                    });
                    $('#modalAvisos').modal('hide')
                } 
            });
        }else{
            Swal.fire({
                title: 'Estas seguro de canelar el turno?',
                text: "Ingresa el comentario de cancelación",
                input: 'text',
                type: 'warning',
                inputValidator: (value) => {
                    if (!value) {
                      return 'Por favor escribe un comentario para cancelar el turno'
                    }
                  },
                showCancelButton: true,
                confirmButtonText: 'Si! cancelarlo',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        async: true,
                        url: API_URL+'?task=cambiarEstatusTurno&turnoId='+turno.turnoId+'&estatus='+CANCELADO+'&notificacion=true&comentario='+result.value,
                        type: "GET",
                        success: function (response) {
                            var obj = response;
                            Swal.fire({
                                type: 'success',
                                title: 'El turno '+obj.turnoId+' está Cancelado',
                            });
                            $('#modalAvisos').modal('hide')
                        } 
                    });
                }
            });
        }
    }

}


