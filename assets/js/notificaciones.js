
//End of Initialize Firebase



var estabId = $('#establecimientoId').val();

var statusColono = 1;
var cont = 0; // Variable para contabilizar los nuevos registros en tiempo real
var contReport = 0;
$(document).ready(function() {

    var config = {
        apiKey: "AIzaSyBT_b-a8D_sPVOm1DJ3kv_9W5mIusabFTA",
        databaseURL: "https://turno-1554146989867.firebaseio.com/"
    };
    
    firebase.initializeApp(config);
    observaNotificacion();
    
    $("#notificationLink").click(function(){
        $("#notificationContainer").fadeToggle(300);
        $("#notification_count").fadeOut("slow");
            return false;
        });

       
        //Document Click hiding the popup 
        $(document).click(function(){
            $("#notificationContainer:visible").hide(onHideNotificatios);
        });

        // //Popup on click
        // $("#notificationContainer").click(function(){
        //     console.log('clic dentro del container');
        //     return false;
        // });
    });
    function onHideNotificatios() { 
        console.log('onHideNotificatios');
        notificacionesVistas();
    }
    $('.bellcontainer').click(function(){
        if(!$("#notificationContainer").is(":visible")){
            //notificacionesVistas();
            observaNotificacion(false);
        }
    });
function notificacionesVistas() {
    $.each($('.notinueva'), function (key, value) {
        var notiId=$(this).attr('nid');
        $.ajax({
            async: true,
            url: API_URL+'?task=notifiVistas&notificacionId='+notiId+"&estatus="+VISTA,
            type: 'GET',
            success: function (response) {
                console.log(response);
                $('.bellcontainer .bellnotification').removeClass('show-count').removeClass('notify').removeAttr('data-count') ;
            }
        });
    
    });
   
}
function cargaNotificacion(noti, sonido) {
    var url_request = "modules/module.notificaciones.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getNotificacion",
            establecimiento:estabId,
            noti:noti.notificacionId
        },
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj){
                creaNotificacion(obj, sonido);
            }
                
        }
    });
}
function creaNotificacion(noti, sonido) {
    
    fecha = new Date(
        noti.fecha.substring(0,4),
        parseInt(noti.fecha.substring(5,7))-1, 
        noti.fecha.substring(8,10)
         );
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    fecha=fecha.toLocaleDateString("es-Es", options);
    fecha_hora = new Date( 
        noti.fecha_hora.substring(0,4),
        parseInt(noti.fecha_hora.substring(5,7))-1, 
        noti.fecha_hora.substring(8,10),
        noti.fecha_hora.substring(11,13),
        noti.fecha_hora.substring(14,16)
        );
    var options = {  year: 'numeric', month: 'long', day: 'numeric', hour:'2-digit', minute:'2-digit' };
    fecha_hora=fecha_hora.toLocaleDateString("es-Es", options);
    var html='';
    html+='<div class="row fila notificacion" id="noti'+noti.notificacionId+'" nid="'+noti.notificacionId+'" tid="'+noti.turnoId+'">';
        html+='<div class="col-xs-3">';
            html+='  <img style="max-width:67px" src="https://previews.123rf.com/images/yupiramos/yupiramos1705/yupiramos170514529/77978485-diseño-gráfico-del-ejemplo-del-vector-del-icono-del-perfil-del-hombre-joven.jpg" alt="">';
            html+=' <small>@'+noti.username+'</small>';
            html+='  <small><b>'+noti.unombre+'</b></small>';
        html+='</div>';
        html+='<div class="texto col-xs-9"> ';
            html+='<div class="horanoti pull-right">'+fecha_hora+'</div><br>'
            html+='<div> Turno <b class="cap">"'+noti.estatus_t+'"</b> para <b class="cap">"'+noti.nombre+'"</b> el día <b>'+fecha+'</b> desde las <b>'+noti.horaInicio+'</b> a las <b>'+noti.horaFin+'</b>' ;
            html+='</div>';
        html+='</div>';
        html+='<hr>';
    html+='</div>';
    
    if(noti.hora_vista==null){
        $('#notificationsBodynuevas').append(html);
        $('#noti'+noti.notificacionId).addClass('notinueva');
        if(sonido){
            notificacionNueva();

            var snd = new Audio("assets/sounds/ding.mp3"); // buffers automatically when created
            snd.play();
        }
       
    }else{
        $('#notificationsBodyvistas').append(html);
        $('#noti'+noti.notificacionId).addClass('notivista');
       
    }
}
function notificacionNueva(){
    var el = document.querySelector('.bellnotification');
    var count = Number(el.getAttribute('data-count')) || 0;
    console.log(count);
    el.setAttribute('data-count', count + 1);
    el.classList.remove('notify');
    el.offsetWidth = el.offsetWidth;
    el.classList.add('notify');
    if(count === 0){
        el.classList.add('show-count');
    }
    
}


function observaNotificacion(sonido=true){
    console.log('observaNotificacion') ;
    $('#notificationsBodyvistas').empty();
    $('#notificationsBodynuevas').empty();
    var database  = firebase.database();
    var notifRef  = database.ref(estabId + '-establecimiento/turno');
    notifRef.on('child_added', (snapshot) => {
        
        var noti = snapshot.val();
        console.log(noti);
        if(noti.notificacionId){
            console.log('user was added !!');
             cargaNotificacion(noti, sonido);
        }
        
    });
}
$('body').on('click','.notificacion', function() {
    var divnoti= $(this);
    console.log(divnoti);
    var url_request = "modules/module.turno.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getDataTurno",
            turno:$(this).attr('tid'),
        },
        success: function (response) {
            var turno = JSON.parse(response)
            console.log(turno);
       
            Swal.fire({
                type: 'info',
                title: 'Turno número: '+turno.turnoId,
                html:   '<div style="margin:0 10%">' +
                        '<b class="pull-left">Usuario:</b> <span class="pull-right">'+turno.nombre+' '+turno.apellidos+'</span><br>' +
                        '<b class="pull-left">Recurso:</b> <span class="pull-right">'+turno.rNombre+'</span><br>' +
                        '<b class="pull-left">Tipo de Sesión:</b> <span class="pull-right">'+turno.tsNombre+'</span><br>' +
                        '<b class="pull-left">Fecha:</b> <span class="pull-right">'+turno.fecha+'</span><br>' +
                        '<b class="pull-left">Hora de Inicio:</b> <span class="pull-right">'+turno.horaInicio+'</span><br>' +
                        '<b class="pull-left">Hora de Fin:</b> <span class="pull-right">'+turno.horaFin+'</span><br>' +
                        '<b class="pull-left">Estatus:</b> <span class="pull-right">'+turno.eNombre+'</span></div>', 
                
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonText: 'Agendar Turno!',
                cancelButtonText: 'Cancelar Turno!',
            }).then((result) => {
                if (result.value) {
                    //Atender
                    $.ajax({
                        async: true,
                        url: API_URL+'?task=cambiarEstatusTurno&turnoId='+turno.turnoId+'&estatus='+AGENDADO+'&comentario=null&notificacion=true',
                        type: "GET",
                        success: function (response) {
                            // console.log(response);
                            var obj = response;
                            Swal.fire({
                                type: 'success',
                                title: 'El turno '+obj.turnoId+' está Agendado',
                                timer: 2000,
                            });
                        } 
                    });
                
                } else if (result.dismiss === Swal.DismissReason.cancel) {
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
                                } 
                            });
                        }
                    });
                    
                }
            });
        }
    });
});


function observableReporte_removed()
{
    var database = firebase.database();
    var reporteRef = firebase.database().ref(estabId + '-Subfraccionamiento/Reportes');
    
    reporteRef.on('child_removed', function(snapRemoveRH) 
    {
        contReport--;

        console.log('me removieron');

        //Removes the sinester case from the datatable and the Google Maps marker related, from the global array.
        var reRH = snapRemoveRH.val();

        console.log(reRH);       
        
        $("#liR" + reRH.notificacionId).remove();
                     
        if(contReport == 0)
        {
            $("#No_NotificationsReport").css('display', 'block');
            console.log('true');
        }

    });
}


// function observableAlerta()
// {
//     // Get a reference to the database service
//     var database = firebase.database();


//     var estabId = $('#SubfraccionamientoRelacion').val();
//     var alertaRef = firebase.database().ref(estabId + '-Subfraccionamiento/Reportes');
    
//     // var alertaRef = firebase.database().ref('Reportes/');

//     alertaRef.on('child_added', function(snapAddReporte)
//     {
//         var new_alert = snapAddReporte.val();
        
//         snapAddReporte.forEach(function(childSnapshot) 
//         {
//             var childData = childSnapshot.val();
//             //console.log("X: " + childData);
//             //-------------------------------------------------
//             if(childData == 1)
//             {
//                 //console.log('Nada');
//             }
//             else
//             {
//                 $.ajax({
//                     url: "modules/module.reportes.php",
//                     type: "POST",
//                     data: {
//                         cmd: "getReporteInfo",
//                         reporteId: childData
//                     },
//                     success: function(response) {
//                         //console.log(response);
//                         getData = response.split("%");
                  
//                         $("#No_NotificationsReport").css('display', 'none');
//                         $("#NotificacionReporteLista").append('<li class="alert-list" id="li' + getData[0] +'"><a href="alertas.php" class="p-t-10 p-b-10" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax"> <p class="col-xs-height col-middle"> <span class="text-complete fs-10"><!--<i class="fa fa-circle"></i>--><img src="assets/img/notificactionA.gif" style="width: 25px;"/></span> </p> <p class="p-l-10 col-xs-height col-middle col-xs-12 overflow-ellipsis fs-12"><span class="text-master link">' + 'Nueva solicitud de registro' +'<br></span><span class="text-master">' + 'Reporte: ' + getData[2] + ', Colono: ' + getData[1] +'</span></p></a></li>');
                        
//                         var snd = new Audio("assets/sounds/notificacionA.mp3"); // buffers automatically when created
//                         snd.play();
//                     }  
//                 });
//             }
//             //-------------------------------------------------
//         });
//     });
// }