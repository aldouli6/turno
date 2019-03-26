/*var config = {
    apiKey: "AIzaSyDTVcHyIef9OPAJtABCNd5bDqdpUZRoMaM",
    databaseURL: "https://botondepanico-173320.firebaseio.com"
};

firebase.initializeApp(config);
//End of Initialize Firebase

//Creates references to the firebase database
var colonoRef = firebase.database().ref('colonos/');
//End of Create references to the firebase database

sinisRef.on('child_added', function(snapAdd) 
{ 
        var newColono = snapAdd.val();
        var getData;
        console.log(newColono);
});*/

$(document).ready(function() 
{
    observableColono();
    observableColono_removed();
    observableAlerta();
    observableReporte_removed();
});

var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();

var statusColono = 1;
var cont = 0; // Variable para contabilizar los nuevos registros en tiempo real
var contReport = 0;

if(cont == 0)
{
     $("#No_Notifications").css('display', 'block');
     console.log('true');
}

function observableColono()
{

    

    var config = 
    {
        apiKey: "AIzaSyB25zmVjs9jxTUJGEowArgB23Jm7Z6HiJM",
        databaseURL: "https://garde-aca75.firebaseio.com",
    };
    firebase.initializeApp(config);
    // Get a reference to the database service
    var database = firebase.database();

    var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();

    var colonoRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/NuevosUsuarios');

    console.log('hola mundo');

    colonoRef.on('child_added', function(snapAdd)
    {
        cont++;
        var new_colono = snapAdd.val();
        var getData;
        console.log("Nuevo colono registrado: " + new_colono);
        $.ajax({
            async: true,
            url: "modules/module.colonos.php",
            type: "POST",
            data: {
                cmd: "getColonoInfo",
                colonoData: new_colono
            },
            success: function(response) {
                //console.log(response);
                getData = response.split("%");
                $("#usuarioColonoDataTable").DataTable().row.add(getData).draw();
                //changeRowsRH(newHelp.idhelp, getData[8]);
                //console.log(getData);

                //console.log("En total hay: " + cont);
                // $("#No_Notifications").remove('');
                $("#No_Notifications").css('display', 'none');
                //console.log('false');
                $("#NotificacionColonoLista").append('<li class="alert-list" id="li' + getData[0] +'"><a href="colonos.php" class="p-t-10 p-b-10" data-navigate="view" data-view-port="#chat" data-view-animation="push-parrallax"> <p class="col-xs-height col-middle"> <span class="text-complete fs-10"><!--<i class="fa fa-circle"></i>--><img src="assets/img/notificactionA.gif" style="width: 25px;"/></span> </p> <p class="p-l-10 col-xs-height col-middle col-xs-12 overflow-ellipsis fs-12"><span class="text-master link">' + 'Nueva solicitud de registro' +'<br></span><span class="text-master">' + 'Colono: ' + getData[1] + '</span><br><span class="text-master"> Fraccionamiento: ' + getData[7] +'</span></p></a></li>');
                var snd = new Audio("assets/sounds/notificacionA.mp3"); // buffers automatically when created
                snd.play();
            }
        });
    });
}

function observableColono_removed()
{
    var database = firebase.database();

    var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();
    
    var colonoRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/NuevosUsuarios');


    // var colonoRef = firebase.database().ref('NuevosUsuarios/');

    colonoRef.on('child_removed', function(snapRemoveRH) 
    { 

         cont--;

        //Removes the sinester case from the datatable and the Google Maps marker related, from the global array.
        var reRH = snapRemoveRH.val();
    
        $("#li" + reRH).remove();

        if(cont == 0)
        {
            $("#No_Notifications").css('display', 'block');
            console.log('true');
        }

        document.getElementById("sSuccessB").innerHTML = cont;

        // document.getElementById("sSuccessB").innerHTML = snapRemoveRH.numChildren();    
    });
}




function observableAlerta()
{
    // Get a reference to the database service
    var database = firebase.database();
    
    var alertaRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/Reportes');
    
    // var alertaRef = firebase.database().ref('Reportes/');

    alertaRef.on('child_added', function(snapAddReporte)
    {
        var new_alert = snapAddReporte.val();
        console.log(new_alert);
        console.log('estoyaquinotificaciones');
        $.ajax({
            url: "modules/module.alertas.php",
            async: true,
            type: "POST",
            data: {
                cmd: "getReporteInfo",
                reporteId: new_alert.notificacionId
            },
            success: function(response) 
            {            
                contReport++;

                // if(contReport == 1)
                // {                                
                        var obj = JSON.parse(response);                        
                        
                        if(obj.ReporteIsIn == 1)
                        {
                            console.log('Notificacion dentro del fraccionamiento');
        					
        
                            var _latX = obj.ReporteLatitud;
                            var _longY = obj.ReporteLongitud;
                                                                
                            if(obj.ReporteComentario === "" || obj.ReporteComentario === null)
                            {
                                $("#Comentario").text("Sin comentario");
                                console.log('sin comentario');
                            }
                            else
                            {
                                $("#Comentario").text(obj.ReporteComentario);
                                console.log('con comentario');
                            }
            
                            $("#fechaHora").text(obj.ReporteRegistro);
                            $("#direccion").text(obj.ReporteDireccionDetalle);
                            $("#estatus").text(obj.EstatusDescripcion);
            
                            
                            $("#No_NotificationsReport").css('display', 'none');
            
                            //code goes here that will be run every 5 seconds.
                            var timeAgoReport = jQuery.timeago(obj.ReporteRegistro);
                                    
                            var calleClean = obj.ReporteDireccionDetalle;
                            var newCalle = calleClean.replace(/\,/g, "");
            
                            firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/Reportes/' + obj.ReporteId).once('value').then(function(snapshot) 
                            {
                                var _statusReport = snapshot.val().notificacionStatus;
            
                                if(_statusReport == 2)
                                {
                                    $("#iconNotificacion" + obj.ReporteId).css('color','#f8d053');
                                }
                            });
            
                            if(obj.EstatusDescripcion === "Atendiendo")
                            {
                                obj.EstatusDescripcion = 'Estas atendiendo este reporte';
                            }
            
                            $("#NotificacionReporteLista").append("<li class='alert-list' id='liR" + obj.ReporteId + "'> <div class='row'> <div class='col-sm-10'>  <a href='alertas.php' class='p-t-10 p-b-10' data-navigate='view' data-view-port='#chat' data-view-animation='push-parrallax'> <p class='col-xs-height col-middle'> <span class='text-complete fs-10'> <img src='assets/img/notificationB.gif'" + " id='notiReportAtendiendo" + obj.ReporteId  + "' style='width: 25px;display:none;'> <img src='assets/img/notificactionA.gif'" + " id='notiReportEnEspera" + obj.ReporteId  + "' style='width: 25px;'></span> </p> <p class='p-l-10 col-xs-height col-middle col-xs-12 overflow-ellipsis fs-12'><span class='text-master link'>Nuevo reporte <span id='agoTime' class='text-master link' style='margin-left: calc(8%);float: right;'>" 
                            + timeAgoReport + "</span> </span><br><span class='text-master'>Reporte: " + obj.TiporeporteDescripcion + "</span><br><span class='text-master'>Colono: " + obj.UsuarioNombreCompleto 
                            + "</span></p> </a></div>    <div class='col-sm-2' style='background: white;height: 68px;padding: 0px;'> <button type='button' onclick='DialogToSendNotifications(" + subfraccionamientoRelacion + "," + obj.ReporteId 
                            + ",\"" + obj.TiporeporteDescripcion + "\"" +");' class='btn btn-default' style='border:solid 0px;margin-right: 20px;position: absolute;width: 100%;height: 68px;'> <i id='iconNotificacion" + obj.ReporteId +  "' class='fa fa-bell' aria-hidden='true'></i></button> </div>      </div></li>");                        
                                                                     
                            if(obj.EstatusDescripcion === "Estas atendiendo este reporte")
                            {
                                $('#notiReportEnEspera' + obj.ReporteId).css('display', 'none');
                                $('#notiReportAtendiendo' + obj.ReporteId).css('display', 'initial');
                            }
            
                            var snd = new Audio("assets/sounds/notificacionA.mp3"); // buffers automatically when created
                            snd.play();
        
        
                        }
                        else if(obj.ReporteIsIn == 0)
                        {
                            console.log('Notificacion fuera del fraccionamiento');
        
                            var _latX = obj.ReporteLatitud;
                            var _longY = obj.ReporteLongitud;
                                                                
                            if(obj.ReporteComentario === "" || obj.ReporteComentario === null)
                            {
                                $("#Comentario").text("Sin comentario");
                                console.log('sin comentario');
                            }
                            else
                            {
                                $("#Comentario").text(obj.ReporteComentario);
                                console.log('con comentario');
                            }
            
                            $("#fechaHora").text(obj.ReporteRegistro);
                            $("#direccion").text(obj.ReporteDireccionDetalle);
                            $("#estatus").text(obj.EstatusDescripcion);
                                        
                            $("#No_NotificationsReportOut").css('display', 'none');
            
                            //code goes here that will be run every 5 seconds.
                            var timeAgoReport = jQuery.timeago(obj.ReporteRegistro);
                                    
                            var calleClean = obj.ReporteDireccionDetalle;
                            var newCalle = calleClean.replace(/\,/g, "");
            
                            firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/Reportes/' + obj.ReporteId).once('value').then(function(snapshot) 
                            {
                                var _statusReport = snapshot.val().notificacionStatus;
            
                                if(_statusReport == 2)
                                {
                                    $("#iconNotificacion" + obj.ReporteId).css('color','#f8d053');
                                }
                            });
            
                            if(obj.EstatusDescripcion === "Atendiendo")
                            {
                                obj.EstatusDescripcion = 'Estas atendiendo este reporte';
                            }
            
                            $("#NotificacionReporteListaFueraFraccionamiento").append("<li class='alert-list' id='liR" + obj.ReporteId + "'> <div class='row'> <div class='col-sm-10'>  <a href='alertas.php' class='p-t-10 p-b-10' data-navigate='view' data-view-port='#chat' data-view-animation='push-parrallax'> <p class='col-xs-height col-middle'> <span class='text-complete fs-10'> <img src='assets/img/RadioA.gif'" + " id='notiReportAtendiendo" + obj.ReporteId  + "' style='width: 25px;display:none;'> <img src='assets/img/Radio.gif'" + " id='notiReportEnEspera" + obj.ReporteId  + "' style='width: 25px;'></span> </p> <p class='p-l-10 col-xs-height col-middle col-xs-12 overflow-ellipsis fs-12'><span class='text-master link'>Nuevo reporte <span id='agoTime' class='text-master link' style='margin-left: calc(8%);float: right;'>" 
                            + timeAgoReport + "</span> </span><br><span class='text-master'>Reporte: " + obj.TiporeporteDescripcion + "</span><br><span class='text-master'>Colono: " + obj.UsuarioNombreCompleto 
                            + "</span></p> </a></div>    <div class='col-sm-2' style='background: white;height: 68px;padding: 0px;'> <button type='button' onclick='DialogToSendNotifications(" + subfraccionamientoRelacion + "," + obj.ReporteId 
                            + ",\"" + obj.TiporeporteDescripcion + "\"" +");' class='btn btn-default' style='border:solid 0px;margin-right: 20px;position: absolute;width: 100%;height: 68px;'> <i id='iconNotificacion" + obj.ReporteId +  "' class='fa fa-bell' aria-hidden='true'></i></button> </div>      </div></li>");                        
                                                                     
                            if(obj.EstatusDescripcion === "Estas atendiendo este reporte")
                            {
                                $('#notiReportEnEspera' + obj.ReporteId).css('display', 'none');
                                $('#notiReportAtendiendo' + obj.ReporteId).css('display', 'initial');
                            }
            
                            var snd = new Audio("assets/sounds/notificacionA.mp3"); // buffers automatically when created
                            snd.play();
                        }
            }
        });
    });
}



function observableReporte_removed()
{
    var database = firebase.database();
    var reporteRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/Reportes');
    
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


//     var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();
//     var alertaRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/Reportes');
    
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