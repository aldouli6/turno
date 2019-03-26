$(document).ready(function() {
    observableColono();
    observableColono_removed();
    observableAlerta();
    observableReporte_removed();
    referenceTable();
    referenceTable_2();
    refreshColonosRegistradosTable();
    $('input').lc_switch("","");
});

var statusColono = 1;
var cont = 0; // Variable para contabilizar los nuevos registros en tiempo real
var statusColono = 1;
var contReport = 0;

var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();

if(cont == 0)
{
     $("#No_Notifications").css('display', 'block');
     //console.log('true');
}

$('body').delegate('.lcs_check', 'lcs-on', function() {
     document.getElementById('divstatus2').style.display = 'none';
     document.getElementById('divstatus1').style.display = 'block';
     statusColono = 2;
     //console.log(statusColono);
});

$('body').delegate('.lcs_check', 'lcs-off', function() {
     document.getElementById('divstatus2').style.display = 'block';
     document.getElementById('divstatus1').style.display = 'none';
     statusColono = 3;
     //console.log(statusColono);
});



$('body').delegate('.lcs_check2', 'lcs-on', function() {
     document.getElementById('divstatusB').style.display = 'none';
     document.getElementById('divstatusA').style.display = 'block';
     statusColono = 2;
     //console.log(statusColono);
});

$('body').delegate('.lcs_check2', 'lcs-off', function() {
     document.getElementById('divstatusB').style.display = 'block';
     document.getElementById('divstatusA').style.display = 'none';
     statusColono = 3;
     //console.log(statusColono);
});

function referenceTable() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#ColonosRegistradosDataTable').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLoadingRecords": "Cargando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sInfoFiltered": "(Datos filtrados de un total de _MAX_ registros)",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfoEmpty": "Mostrando 0-0 de un total de 0 registros",
            "sInfo": "Mostrando _START_-_END_ de un total de _TOTAL_ registros",
            "sSearch": "Buscar:",
            "sZeroRecords": "No se encontraron resultados",
            "oPaginate": {
                "sNext": '<span class=" pg-arrow_right"></span>',
                "sPrevious": '<span class=" pg-arrow_left"></span>'
            },
        },"columnDefs": [
          {
              "targets": [9, 10],
              "orderable": false
          },
          {
              "targets": [0,8],
              "visible": false
          }]
    });
}



function referenceTable_2() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#usuarioColonoDataTable').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLoadingRecords": "Cargando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sInfoFiltered": "(Datos filtrados de un total de _MAX_ registros)",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfoEmpty": "Mostrando 0-0 de un total de 0 registros",
            "sInfo": "Mostrando _START_-_END_ de un total de _TOTAL_ registros",
            "sSearch": "Buscar:",
            "sZeroRecords": "No se encontraron resultados",
            "oPaginate": {
                "sNext": '<span class=" pg-arrow_right"></span>',
                "sPrevious": '<span class=" pg-arrow_left"></span>'
            },
        },"columnDefs": [
          {
              "targets": [9,10],
              "orderable": false
          },
          {
              "targets": [0,8],
              "visible": false
          }]
    });
}

function refreshColonosRegistradosTable(){
    var url_request = "modules/module.colonos.php";
    var method = "POST";
    var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "dataTableColonos",
            subfraccionamientoRelacion: subfraccionamientoRelacion
        },
        success: function(response){
            if(response!=""){
                getData = response.split(",");
                for (var i = 0; i < getData.length; i++) {
                    data = getData[i].split("%");
                    $("#ColonosRegistradosDataTable").DataTable().row.add(data).draw();
    	       }
            }
        }
    })
}

$.ajax({ //Se carga la lista de los estados en el formulario de registro del fraccionamiento
    async: true,
    url: "modules/module.colonos.php",
    type: "POST",
    data: {
        cmd: "listaSubfraccionamientos"
    },
    success: function(response) {
        $('#editSubfraccionamientoColono').html(response);
    }
});

$("#editSubfraccionamientoColono").change(function() {
    $("#editCalleColono").select2("val", 0);
    $.ajax({
        async: true,
        url: "modules/module.colonos.php",
        type: "POST",
        data: {
            cmd: "listaCalles",
            claveSubfraccionamiento: $("#editSubfraccionamientoColono").val()
        },
        success: function(response) {
            $('#editCalleColono').html(response);
        }
    });
});

function editColonoData(id_colono){
    var url_request = "modules/module.colonos.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: 'getDatosColono',
            claveColono: id_colono
        },
        success: function(response) {
            //console.log(response);
            var DatosColono = jQuery.parseJSON(response);
            $("#IdColonoEditar").val(DatosColono.Id);
            $("#IdDireccionColonoEditar").val(DatosColono.DireccionId);
            $("#editNombreColono").val(DatosColono.Nombre);
            $("#editTelefonoColono").val(DatosColono.Telefono);
            $("#editCorreoColono").val(DatosColono.Email);
            $("#editNumExtColono").val(DatosColono.NumExt);
            $("#editNumIntColono").val(DatosColono.NumInt);
            //console.log(DatosColono.Estatus);
            if (DatosColono.Estatus == "2"){
                $('input').lcs_on();
                document.getElementById('divstatus2').style.display = 'none';
                document.getElementById('divstatus1').style.display = 'block';
            } else if (DatosColono.Estatus == "3"){
                $('input').lcs_off();
                document.getElementById('divstatus2').style.display = 'block';
                document.getElementById('divstatus1').style.display = 'none';
            }
            $('#editSubfraccionamientoColono').select2('val',DatosColono.SubfraccionamientoId);
            $.ajax({
                async: true,
                url: "modules/module.colonos.php",
                type: "POST",
                data: {
                    cmd: "listaCalles",
                    claveSubfraccionamiento: DatosColono.SubfraccionamientoId
                },
                success: function(response) {
                    $('#editCalleColono').html(response);
                    $('#editCalleColono').select2('val',DatosColono.CalleId);
                }
            });
        }
    });
}

$('#formEdicionColono').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
    },
    submitHandler: function(form) {
        var formularioEditar = $(form).serialize() + "&cmd=editarColono&editStatusColono="+statusColono;
        $.ajax({
            async: true,
            url: "modules/module.colonos.php",
            type: "POST",
            data: formularioEditar,
            success: function(response) {
                //console.log(response);
                if(response!="0"){
                    getData = response.split("%");
                    $("#ColonosRegistradosDataTable").DataTable().rows().eq(0).each(function(index) {
                        var ColonoIdTable = $("#ColonosRegistradosDataTable").DataTable().cell(index, 0).data();
                        if(ColonoIdTable==$("#IdColonoEditar").val()){
                            $("#ColonosRegistradosDataTable").DataTable().cell(index,1).data(getData[1]);
                            $("#ColonosRegistradosDataTable").DataTable().cell(index,2).data(getData[2]);
                            $("#ColonosRegistradosDataTable").DataTable().cell(index,3).data(getData[3]);
                            $("#ColonosRegistradosDataTable").DataTable().cell(index,4).data(getData[4]);
                            $("#ColonosRegistradosDataTable").DataTable().cell(index,5).data(getData[5]);
                            $("#ColonosRegistradosDataTable").DataTable().cell(index,6).data(getData[6]);
                            $("#ColonosRegistradosDataTable").DataTable().cell(index,7).data(getData[7]);
                            swal("\u00A1En hora buena!", "el colono se ha editado correctamente", "success");
                        }
                    });
                } else{
                    swal("\u00A1Error!", "el colono no se ha podido editar", "error");
                }
            }
        });
    }
});



$('#formHabilitarColono_data').validate({
    rules: {
    },
    submitHandler: function(form) {
        var formularioEditar = $(form).serialize() + "&cmd=habilitarColono";
        if(statusColono == 3)
        {
            $('#formHabilitarColono').modal('hide');
        }
        else
        {
            // console.log(formularioEditar);
            $.ajax({
                async: true,
                url: "modules/module.colonos.php",
                type: "POST",
                data: formularioEditar,
                success: function(response) 
                {          
                    if(response!="0"){

                        getData = response.split("%");
                                
                        if(getData[8] == 1)
                        {
                            console.log("Entra a habilitar IOS");
                            console.log(getData[8]);
                            console.log("El id que se va a eliminar es " + getData[0]);
    
                            Delete_ColonoById_firebase(getData[0]);
                            $("#ColonosRegistradosDataTable").DataTable().row.add(getData).draw();
                            $('#formHabilitarColono').modal('hide');
                                    
                            var tokenFCM = $("#TokenUser").val();
    
                            sendFCMIOS(tokenFCM);
    
                            swal("\u00A1En hora buena!", "el colono se ha habilitado correctamente", "success");
                        }
                        else if(getData[8] == 0)
                        {
                            console.log("Entra a habilitar Android");

                            getData = response.split("%");
                            console.log(getData);
                            console.log("El id que se va a eliminar es " + getData[0]);
    
                            Delete_ColonoById_firebase(getData[0]);
                            $("#ColonosRegistradosDataTable").DataTable().row.add(getData).draw();
                            $('#formHabilitarColono').modal('hide');
                                    
                            var tokenFCM = $("#TokenUser").val();
    
                            sendFCMAndroid(tokenFCM);
                        }
                       
                      
                        // var snd = new Audio("assets/sounds/finalfanta_3d4bpu2c.mp3"); // buffers automatically when created
                        // snd.play();
                    } 
                    else
                    {
                        swal("\u00A1Error!", "el colono no se ha podido habilitar", "error");
                    }
                }
            });
        }
    }
});

function eliminarColonoData(id_colono) {
    swal({
        title: "\u00BFEst\u00E1s seguro de eliminar el colono con la clave " + id_colono + "?",
        text: "\u00A1Una vez eliminado no se podra recuperar!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#428bca',
        confirmButtonText: "Si, \u00A1elim\u00EDnalo!",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                async: true,
                url: "modules/module.colonos.php",
                type: "POST",
                data: {
                    cmd: 'eliminarColono',
                    claveColonoEliminar: id_colono
                },
                success: function(response) {
                    if(response=="1"){
                        $("#ColonosRegistradosDataTable").DataTable().rows().eq(0).each(function(index) {
                            var rowColono = $("#ColonosRegistradosDataTable").DataTable().cell(index, 0).data();
                            if (rowColono == id_colono) {
                                $("#ColonosRegistradosDataTable").DataTable().row(index).remove().draw();
                            }
                        });
                        swal("\u00A1En hora buena!","el colono ha sido eliminado correctamente", "success");
                    } else{
                        swal("Error", "El colono no ha podido ser eliminado.", "error");
                    }
                }
            });
        } else {
            swal("Cancelado", "No se ha realizado ning\u00FAn cambio", "error");
        }
    });
}




function HabilitarColono(id_colono){
    var url_request = "modules/module.colonos.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: 'getColonoHabilitar',
            claveColono: id_colono
        },
        success: function(response) {
            //console.log(response);
            var DatosColono = jQuery.parseJSON(response);
            $("#IdColonoHabilitar").val(DatosColono.Id);
            $("#habilitarNombreColono").val(DatosColono.Nombre);
            $("#TokenUser").val(DatosColono.Token);


            //console.log(DatosColono.Estatus);
            if (DatosColono.Estatus == "1"){
                $('input').lcs_off();
                $("#divstatusB").hide();
                $("#divstatusA").show();
            }
        }
    });
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
    colonoRef.on('child_added', function(snapAdd)
    {
        cont++;
        var new_colono = snapAdd.val();
        var getData;
        //console.log("Nuevo colono registrado: " + new_colono);
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


                console.log();


                $("#usuarioColonoDataTable").DataTable().row.add(getData).draw();
                //changeRowsRH(newHelp.idhelp, getData[8]);
                //console.log(getData);
                document.getElementById("sSuccessB").innerHTML = cont;
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


        console.log('me removieron');
        

        cont--;
        //Removes the sinester case from the datatable and the Google Maps marker related, from the global array.
        var reRH = snapRemoveRH.val();
        //  console.log("There are "+snapRemoveRH.numChildren()+" messages");
        $("#usuarioColonoDataTable").DataTable().rows().eq(0).each(function(index) 
        {
            var zxcv = $("#usuarioColonoDataTable").DataTable().cell(index, 0).data();
            if (zxcv == reRH) 
            {
                $("#usuarioColonoDataTable").DataTable().row(index).remove().draw();
                //console.log('Registro eliminado de firebase');       
                $("#li" + reRH).remove();
                // $("#NotificacionColonoLista").empty();             
            }
        });       
        if(cont == 0)
        {
            $("#No_Notifications").css('display', 'block');
            //console.log('true');
        }
        document.getElementById("sSuccessB").innerHTML = cont;
        // document.getElementById("sSuccessB").innerHTML = snapRemoveRH.numChildren();
    });
}


function Delete_ColonoById_firebase(eventContactId)
{

    var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();

    var eventContactsRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/NuevosUsuarios/' + eventContactId);
    
    // var eventContactsRef = firebase.database().ref('NuevosUsuarios/' + eventContactId);

    eventContactsRef.remove();
    //console.log('registro eliminado de firebase');
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



function sendFCMIOS(_token) 
{

    console.log("Token: " +  _token);

    var json = {
        "to": _token,
        "notification": {
          "title": "GARDE",
          "body": "Tu cuenta para GARDE sido habilitada"
        },
        "data": {
          "titulo": "GARDE",
          "descricao": "Corpo dos dados..."
        }
      };

      $.ajax({
        url: 'https://fcm.googleapis.com/fcm/send',
        type: "POST",
        processData : false,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('Content-Type', 'application/json');
          xhr.setRequestHeader('Authorization', 'key=AIzaSyDq6dpSqp3zu-cWE45f4P5-KVEEs768jXI');
        },
        data: JSON.stringify(json),
        success: function () {
          console.log("Mensaje enviado con exito!");
        },
        error: function(error) {
          console.log(error);
        }
      });

}

function sendFCMAndroid(_token) 
{

    console.log("Token: " +  _token);

    var json = {
        "to": _token,
        "data": {
          "body": "Tu cuenta para botón de pánico ha sido habilitada (Android)"
        }
      };

      $.ajax({
        url: 'https://fcm.googleapis.com/fcm/send',
        type: "POST",
        processData : false,
        beforeSend: function (xhr) {
          xhr.setRequestHeader('Content-Type', 'application/json');
          xhr.setRequestHeader('Authorization', 'key=AIzaSyDq6dpSqp3zu-cWE45f4P5-KVEEs768jXI');
        },
        data: JSON.stringify(json),
        success: function () {
          console.log("Mensaje enviado con exito!");
        },
        error: function(error) {
          console.log(error);
        }
      });

}

function eliminarColonoData2(id_colono) {
    swal({
        title: "\u00BFEst\u00E1s seguro de eliminar el colono con la clave " + id_colono + "?",
        text: "\u00A1Una vez eliminado no se podra recuperar!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#428bca',
        confirmButtonText: "Si, \u00A1elim\u00EDnalo!",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                async: true,
                url: "modules/module.colonos.php",
                type: "POST",
                data: {
                    cmd: 'eliminarColono',
                    claveColonoEliminar: id_colono
                },
                success: function(response) {
                    if(response=="1"){
                        Delete_ColonoById_firebase(id_colono);
                        swal("\u00A1En hora buena!","el colono ha sido eliminado correctamente", "success");
                    } else{
                        swal("Error", "El colono no ha podido ser eliminado.", "error");
                    }
                }
            });
        } else {
            swal("Cancelado", "No se ha realizado ning\u00FAn cambio", "error");
        }
    });
}