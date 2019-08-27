
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

        //Popup on click
        $("#notificationContainer").click(function(){
            console.log('clic dentro del container');
            return false;
        });
    });
    function onHideNotificatios() { 
        console.log('onHideNotificatios');
    }
function cargaNotificacion(noti) {
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
            console.log(response);
            var obj = JSON.parse(response);
            console.log(obj);
                creaNotificacion(obj);
                nuevanotificacion();
        }
    });
}
function creaNotificacion(noti) {

    fecha = new Date(noti.fecha);
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    console.log(
        fecha= fecha.toLocaleDateString("es-ES", options)
    );
    fecha_hora = new Date(noti.fecha_hora);
    var options = {  year: 'numeric', month: 'long', day: 'numeric', hour:'2-digit', minute:'2-digit' };
    console.log(
        fecha_hora= fecha_hora.toLocaleDateString("es-ES", options)
    );
    var html='';
    html+='<div class="row fila">';
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
    if(noti.estatusId_n==5){
        $('#notificationsBodynuevas').append(html);
        var snd = new Audio("assets/sounds/ding.mp3"); // buffers automatically when created
        snd.play();
    }
}
function nuevanotificacion(){
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

if(cont == 0)
{
     $("#No_Notifications").css('display', 'block');
     console.log('true');
}




function observaNotificacion(){
    console.log('observaNotificacion') ;
    var database  = firebase.database();
    var notifRef  = database.ref(estabId + '-establecimiento/turno');
    notifRef.on('child_added', (snapshot) => {
        
        var noti = snapshot.val();
        console.log(noti);
        if(noti.notificacionId){
            console.log('user was added !!');
             cargaNotificacion(noti);
        }
        
        // $.ajax({
        //     url: "modules/module.alertas.php",
        //     async: true,
        //     type: "POST",
        //     data: {
        //         cmd: "getReporteInfo",
        //         reporteId: new_alert.notificacionId
        //     },
        //     success: function(response) 
        //     {            
        //         contReport++;

        //         // if(contReport == 1)
        //         // {                                
        //                 var obj = JSON.parse(response);                        
                        
        //                 if(obj.ReporteIsIn == 1)
        //                 {
        //                     console.log('Notificacion dentro del fraccionamiento');
        					
        
        //                     var _latX = obj.ReporteLatitud;
        //                     var _longY = obj.ReporteLongitud;
                                                                
        //                     if(obj.ReporteComentario === "" || obj.ReporteComentario === null)
        //                     {
        //                         $("#Comentario").text("Sin comentario");
        //                         console.log('sin comentario');
        //                     }
        //                     else
        //                     {
        //                         $("#Comentario").text(obj.ReporteComentario);
        //                         console.log('con comentario');
        //                     }
            
        //                     $("#fechaHora").text(obj.ReporteRegistro);
        //                     $("#direccion").text(obj.ReporteDireccionDetalle);
        //                     $("#estatus").text(obj.EstatusDescripcion);
            
                            
        //                     $("#No_NotificationsReport").css('display', 'none');
            
        //                     //code goes here that will be run every 5 seconds.
        //                     var timeAgoReport = jQuery.timeago(obj.ReporteRegistro);
                                    
        //                     var calleClean = obj.ReporteDireccionDetalle;
        //                     var newCalle = calleClean.replace(/\,/g, "");
            
        //                     firebase.database().ref(estabId + '-Subfraccionamiento/Reportes/' + obj.ReporteId).once('value').then(function(snapshot) 
        //                     {
        //                         var _statusReport = snapshot.val().notificacionStatus;
            
        //                         if(_statusReport == 2)
        //                         {
        //                             $("#iconNotificacion" + obj.ReporteId).css('color','#f8d053');
        //                         }
        //                     });
            
        //                     if(obj.EstatusDescripcion === "Atendiendo")
        //                     {
        //                         obj.EstatusDescripcion = 'Estas atendiendo este reporte';
        //                     }
            
        //                     $("#NotificacionReporteLista").append("<li class='alert-list' id='liR" + obj.ReporteId + "'> <div class='row'> <div class='col-sm-10'>  <a href='alertas.php' class='p-t-10 p-b-10' data-navigate='view' data-view-port='#chat' data-view-animation='push-parrallax'> <p class='col-xs-height col-middle'> <span class='text-complete fs-10'> <img src='assets/img/notificationB.gif'" + " id='notiReportAtendiendo" + obj.ReporteId  + "' style='width: 25px;display:none;'> <img src='assets/img/notificactionA.gif'" + " id='notiReportEnEspera" + obj.ReporteId  + "' style='width: 25px;'></span> </p> <p class='p-l-10 col-xs-height col-middle col-xs-12 overflow-ellipsis fs-12'><span class='text-master link'>Nuevo reporte <span id='agoTime' class='text-master link' style='margin-left: calc(8%);float: right;'>" 
        //                     + timeAgoReport + "</span> </span><br><span class='text-master'>Reporte: " + obj.TiporeporteDescripcion + "</span><br><span class='text-master'>Colono: " + obj.UsuarioNombreCompleto 
        //                     + "</span></p> </a></div>    <div class='col-sm-2' style='background: white;height: 68px;padding: 0px;'> <button type='button' onclick='DialogToSendNotifications(" + estabId + "," + obj.ReporteId 
        //                     + ",\"" + obj.TiporeporteDescripcion + "\"" +");' class='btn btn-default' style='border:solid 0px;margin-right: 20px;position: absolute;width: 100%;height: 68px;'> <i id='iconNotificacion" + obj.ReporteId +  "' class='fa fa-bell' aria-hidden='true'></i></button> </div>      </div></li>");                        
                                                                     
        //                     if(obj.EstatusDescripcion === "Estas atendiendo este reporte")
        //                     {
        //                         $('#notiReportEnEspera' + obj.ReporteId).css('display', 'none');
        //                         $('#notiReportAtendiendo' + obj.ReporteId).css('display', 'initial');
        //                     }
            
        //                     var snd = new Audio("assets/sounds/notificacionA.mp3"); // buffers automatically when created
        //                     snd.play();
        
        
        //                 }
        //                 else if(obj.ReporteIsIn == 0)
        //                 {
        //                     console.log('Notificacion fuera del fraccionamiento');
        
        //                     var _latX = obj.ReporteLatitud;
        //                     var _longY = obj.ReporteLongitud;
                                                                
        //                     if(obj.ReporteComentario === "" || obj.ReporteComentario === null)
        //                     {
        //                         $("#Comentario").text("Sin comentario");
        //                         console.log('sin comentario');
        //                     }
        //                     else
        //                     {
        //                         $("#Comentario").text(obj.ReporteComentario);
        //                         console.log('con comentario');
        //                     }
            
        //                     $("#fechaHora").text(obj.ReporteRegistro);
        //                     $("#direccion").text(obj.ReporteDireccionDetalle);
        //                     $("#estatus").text(obj.EstatusDescripcion);
                                        
        //                     $("#No_NotificationsReportOut").css('display', 'none');
            
        //                     //code goes here that will be run every 5 seconds.
        //                     var timeAgoReport = jQuery.timeago(obj.ReporteRegistro);
                                    
        //                     var calleClean = obj.ReporteDireccionDetalle;
        //                     var newCalle = calleClean.replace(/\,/g, "");
            
        //                     firebase.database().ref(estabId + '-Subfraccionamiento/Reportes/' + obj.ReporteId).once('value').then(function(snapshot) 
        //                     {
        //                         var _statusReport = snapshot.val().notificacionStatus;
            
        //                         if(_statusReport == 2)
        //                         {
        //                             $("#iconNotificacion" + obj.ReporteId).css('color','#f8d053');
        //                         }
        //                     });
            
        //                     if(obj.EstatusDescripcion === "Atendiendo")
        //                     {
        //                         obj.EstatusDescripcion = 'Estas atendiendo este reporte';
        //                     }
            
        //                     $("#NotificacionReporteListaFueraFraccionamiento").append("<li class='alert-list' id='liR" + obj.ReporteId + "'> <div class='row'> <div class='col-sm-10'>  <a href='alertas.php' class='p-t-10 p-b-10' data-navigate='view' data-view-port='#chat' data-view-animation='push-parrallax'> <p class='col-xs-height col-middle'> <span class='text-complete fs-10'> <img src='assets/img/RadioA.gif'" + " id='notiReportAtendiendo" + obj.ReporteId  + "' style='width: 25px;display:none;'> <img src='assets/img/Radio.gif'" + " id='notiReportEnEspera" + obj.ReporteId  + "' style='width: 25px;'></span> </p> <p class='p-l-10 col-xs-height col-middle col-xs-12 overflow-ellipsis fs-12'><span class='text-master link'>Nuevo reporte <span id='agoTime' class='text-master link' style='margin-left: calc(8%);float: right;'>" 
        //                     + timeAgoReport + "</span> </span><br><span class='text-master'>Reporte: " + obj.TiporeporteDescripcion + "</span><br><span class='text-master'>Colono: " + obj.UsuarioNombreCompleto 
        //                     + "</span></p> </a></div>    <div class='col-sm-2' style='background: white;height: 68px;padding: 0px;'> <button type='button' onclick='DialogToSendNotifications(" + estabId + "," + obj.ReporteId 
        //                     + ",\"" + obj.TiporeporteDescripcion + "\"" +");' class='btn btn-default' style='border:solid 0px;margin-right: 20px;position: absolute;width: 100%;height: 68px;'> <i id='iconNotificacion" + obj.ReporteId +  "' class='fa fa-bell' aria-hidden='true'></i></button> </div>      </div></li>");                        
                                                                     
        //                     if(obj.EstatusDescripcion === "Estas atendiendo este reporte")
        //                     {
        //                         $('#notiReportEnEspera' + obj.ReporteId).css('display', 'none');
        //                         $('#notiReportAtendiendo' + obj.ReporteId).css('display', 'initial');
        //                     }
            
        //                     var snd = new Audio("assets/sounds/notificacionA.mp3"); // buffers automatically when created
        //                     snd.play();
        //                 }
        //     }
        // });
    });
}



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