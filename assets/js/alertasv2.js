var map;
var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();
var markersReport = [];
var markersReport_alterno = [];
var mapReport_center = [];
var contReport = 0;
var flagNotifyColono = 2;
var poligonoFraccionamiento;
var statusColono = 1;
var cont = 0; // Variable para contabilizar los nuevos registros en tiempo real

function initMap()
{
    // Create a map object and specify the DOM element for display.
    var mapProp = {
        center: {lat: 21.120613756756352, lng: -101.69155865907669},
        scrollwheel: false,
        zoom: 17,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById('map'), mapProp);
}

$(document).ready(function()
{
    initMap();
    observableColono();
    observableColono_removed();
    observableAlerta();
    observableReporte_removed();
});

if(cont == 0)
{
     $("#No_Notifications").css('display', 'block');
}


function observableColono()
{
    var config =
    {
        apiKey: "AIzaSyDupPz8HYiMt0nZs2JlYVS6aDfc6heDapk",
        databaseURL: "https://botonpanico-c1d41.firebaseio.com",
    };
    firebase.initializeApp(config);
    // Get a reference to the database service
    var database = firebase.database();


    var colonoRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/NuevosUsuarios');

    colonoRef.on('child_added', function(snapAdd)
    {
        cont++;
        var new_colono = snapAdd.val();
        var getData;

        $.ajax({
            url: "modules/module.colonos.php",
            async: true,
            type: "POST",
            data: {
                cmd: "getColonoInfo",
                colonoData: new_colono
            },
            success: function(response) {

                getData = response.split("%");

                $("#No_Notifications").css('display', 'none');
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

    var colonoRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/NuevosUsuarios');

    colonoRef.on('child_removed', function(snapRemoveRH)
    {
        cont--;
        //Removes the sinester case from the datatable and the Google Maps marker related, from the global array.
        var reRH = snapRemoveRH.val();
        $("#li" + reRH).remove();
        if(cont == 0)
        {
            $("#No_Notifications").css('display', 'block');
        }
        document.getElementById("sSuccessB").innerHTML = cont;
    });
}


function observableAlerta()
{
    // Get a reference to the database service
    var database = firebase.database();

    var alertaRef = firebase.database().ref(subfraccionamientoRelacion + '-Subfraccionamiento/Reportes');

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
                    }
                    else
                    {
                        $("#Comentario").text(obj.ReporteComentario);
                    }

                    $("#fechaHora").text(obj.ReporteRegistro);
                    $("#direccion").text(obj.ReporteDireccionDetalle);
                    $("#estatus").text(obj.EstatusDescripcion);

                    createMarker(_latX, _longY, obj.TiporeporteDescripcion, obj.UsuarioNombreCompleto, obj.ReporteComentario, obj.ReporteRegistro, obj.ReporteDireccionDetalle, obj.EstatusDescripcion);

                    $("#No_NotificationsReport").css('display', 'none');

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

                    $("#NotificacionReporteLista").append("<li class='alert-list' id='liR" + obj.ReporteId + "'> <div class='row'> <div class='col-sm-10'>  <a onclick='SelectReporte("
                    + _latX + "," + _longY + ",\"" + obj.TiporeporteDescripcion + "\",\"" + obj.UsuarioNombreCompleto + "\",\"" + obj.ReporteComentario + "\",\"" + obj.ReporteRegistro + "\",\""
                    + newCalle + "\",\"" + obj.EstatusDescripcion + "\"," + obj.ReporteId
                    + "," + obj.UsuarioId + "\,\"" + obj.UsuarioTelefono +  "\",\"" + obj.UsuarioEmail + "\"" + "," + obj.EstatusId +")' class='p-t-10 p-b-10' data-navigate='view' data-view-port='#chat' data-view-animation='push-parrallax'> <p class='col-xs-height col-middle'> <span class='text-complete fs-10'> <img src='assets/img/notificationB.gif'" + " id='notiReportAtendiendo" + obj.ReporteId  + "' style='width: 25px;display:none;'> <img src='assets/img/notificactionA.gif'" + " id='notiReportEnEspera" + obj.ReporteId  + "' style='width: 25px;'></span> </p> <p class='p-l-10 col-xs-height col-middle col-xs-12 overflow-ellipsis fs-12'><span class='text-master link'>Nuevo reporte <span id='agoTime' class='text-master link' style='margin-left: calc(8%);float: right;'>"
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
                    }
                    else
                    {
                        $("#Comentario").text(obj.ReporteComentario);
                    }

                    $("#fechaHora").text(obj.ReporteRegistro);
                    $("#direccion").text(obj.ReporteDireccionDetalle);
                    $("#estatus").text(obj.EstatusDescripcion);

                    createMarker(_latX, _longY, obj.TiporeporteDescripcion, obj.UsuarioNombreCompleto, obj.ReporteComentario, obj.ReporteRegistro, obj.ReporteDireccionDetalle, obj.EstatusDescripcion);

                    $("#No_NotificationsReportOut").css('display', 'none');

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

                    $("#NotificacionReporteListaFueraFraccionamiento").append("<li class='alert-list' id='liR" + obj.ReporteId + "'> <div class='row'> <div class='col-sm-10'>  <a onclick='SelectReporte("
                    + _latX + "," + _longY + ",\"" + obj.TiporeporteDescripcion + "\",\"" + obj.UsuarioNombreCompleto + "\",\"" + obj.ReporteComentario + "\",\"" + obj.ReporteRegistro + "\",\""
                    + newCalle + "\",\"" + obj.EstatusDescripcion + "\"," + obj.ReporteId
                    + "," + obj.UsuarioId + "\,\"" + obj.UsuarioTelefono +  "\",\"" + obj.UsuarioEmail + "\"" + "," + obj.EstatusId +")' class='p-t-10 p-b-10' data-navigate='view' data-view-port='#chat' data-view-animation='push-parrallax'> <p class='col-xs-height col-middle'> <span class='text-complete fs-10'> <img src='assets/img/RadioA.gif'" + " id='notiReportAtendiendo" + obj.ReporteId  + "' style='width: 25px;display:none;'> <img src='assets/img/Radio.gif'" + " id='notiReportEnEspera" + obj.ReporteId  + "' style='width: 25px;'></span> </p> <p class='p-l-10 col-xs-height col-middle col-xs-12 overflow-ellipsis fs-12'><span class='text-master link'>Nuevo reporte <span id='agoTime' class='text-master link' style='margin-left: calc(8%);float: right;'>"
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

function createMarker(lat, long, _tipoReport, _colono, _comentario, _fecha, _direccion, _estatus)
{
    var contentString = '<div id="content">'+
    '<div id="siteNotice">'+
    '</div>'+
    '<h4 id="firstHeading" class="firstHeading">' + _tipoReport +'</h4>'+
    '<div id="bodyContent">'+
    '<p><b>Colono: </b>' + _colono + '<br>' +
    '</div>'+
    '</div>';

    var infowindow = new google.maps.InfoWindow({
        content: contentString
    });

    var image = {
        url: 'assets/img/location-1.gif',
        // This marker is 20 pixels wide by 32 pixels high.
        size: new google.maps.Size(20, 32),
      };

    var marker = new google.maps.Marker({
        position: new google.maps.LatLng(lat, long),
        optimized:false, // <-- required for animated gif
        map: map,
        // icon: "assets/img/notificactionA.gif",
        icon: 'assets/img/markerLoc.png',
        animation: google.maps.Animation.DROP,
    });

    markersReport_alterno.push(marker);

    markersReport.push(marker);


    marker.addListener('click', function() {
       infowindow.open(map, marker);
    });
}

// Sets the map on all markers in the array.
function setMapOnAll(map) {
    for (var i = 0; i < markersReport.length; i++) {
        markersReport[i].setMap(map);
    }
  }

  function setMapOnAll_alterno(map) {
    for (var i = 0; i < markersReport_alterno.length; i++) {
        markersReport_alterno[i].setMap(map);
    }
  }

// Removes the markers from the map, but keeps them in the array.
  function clearMarkers() {
    setMapOnAll(null);
  }

  // Shows any markers currently in the array.
  function showMarkers() {
    setMapOnAll(map);
  }


  function showMarkersAlterno() {
    clearMarkers();
    setMapOnAll_alterno(map);

    //  console.log(mapReport_center);
     map.setCenter(new google.maps.LatLng(mapReport_center[1], mapReport_center[0]));
     map.setZoom(17);
     map.setOptions({draggable: true, zoomControl: true, scrollwheel: false, disableDoubleClickZoom: false});
  }

  // Deletes all markers in the array by removing references to them.
  function deleteMarkers() {
    clearMarkers();
    markers = [];
  }


  $.ajax({
      url: "modules/module.alertas.php",
      async: true,
      type: "POST",
      data: {
          cmd: "getPolygon",
          subfraccionamientoId: subfraccionamientoRelacion
      },
      success: function(response) {
          var coordenadas = response.substring(3, (response.length-3));
          var coordenadas2 = coordenadas.replace(/\[/g, "{");
          var coordenadas3 = coordenadas2.replace(/\]/g, "}");
          var coordenadas4 = coordenadas3.split("},{");
          var coords = [];
          for (var i = 0; i < coordenadas4.length; i++) {
              var latLon = coordenadas4[i].split(",");
              var latLongKeys = new google.maps.LatLng(latLon[1], latLon[0]);
              coords.push(latLongKeys);
          }
          var fraccionamientoPolygon = new google.maps.Polygon({
              paths: coords,
              strokeColor: '#000000',
              strokeOpacity: 0.5,
              strokeWeight: 3,
              fillColor: '#000000',
              fillOpacity: 0.25
          });
          fraccionamientoPolygon.setMap(map);
      }
  });


  $.ajax({
      url: "modules/module.alertas.php",
      async: true,
      type: "POST",
      data: {
          cmd: "getCenter",
          subfraccionamientoId: subfraccionamientoRelacion
      },
      success: function(response) {
          var coordenadasCentro = response.substring(2, (response.length-2));
          var center = coordenadasCentro.split(",");
          // console.log(center);
          map.setCenter(new google.maps.LatLng(center[1], center[0]));

          mapReport_center = center;

      }
  });


  function checkNotify(checkbox)
  {
      if(checkbox.checked)
      {
          console.log('aia');
          flagNotifyColono = 1;
          console.log(flagNotifyColono);
      }
      else
      {
          console.log('not aia');
          flagNotifyColono = 2;
          console.log(flagNotifyColono);
      }
  }


  function SelectReporte(_locXCenter, _locYCenter, _tipoReportA, _colonoA, _comentarioA, _fechaA, _direccionA, _estatusA, _RID, _colId, _telUser, _emailUser, _idEstatusR)
  {
      clearMarkers();

      if(_comentarioA === "" || _comentarioA === null)
      {
          _comentarioA = "Sin comentario";
      }

      map.setZoom(20);

      map.setCenter(new google.maps.LatLng(_locXCenter, _locYCenter));

      map.setOptions({draggable: false, zoomControl: false, scrollwheel: false, disableDoubleClickZoom: false});

      var contentStringA = '<div id="content" style="width:500px;">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h4 id="firstHeading" class="firstHeading" style="font-weight: bold;">' + _tipoReportA +'</h4>'+ '<hr>' +
      '<div id="bodyContent">'+
      '<div class="row" style="width:100%"> <div class="col-sm-2"> <p><b>Colono: </b></p> </div> <div class="col-sm-10">' + '<p>' + _colonoA + '</p>' + '</div> </div>' +
      '<div class="row" style="width:100%"> <div class="col-sm-2"> <p><b>Teléfono: </b></p> </div> <div class="col-sm-10">' + '<p>' + _telUser + '</p>' + '</div> </div>' +
      '<div class="row" style="width:100%"> <div class="col-sm-2"> <p><b>Email: </b></p> </div> <div class="col-sm-10">' + '<p>' + _emailUser + '</p>' + '</div> </div>' +
      '<div class="row" style="width:100%"> <div class="col-sm-2"> <p><b>Comentario: </b></p> </div> <div class="col-sm-10">' + '<p>' + _comentarioA + '</p>' + '</div> </div>' +
      '<div class="row" style="width:100%"> <div class="col-sm-2"> <p><b>Fecha: </b></p> </div> <div class="col-sm-10">' + '<p>' + _fechaA + '</p>' + '</div> </div>' +
      '<div class="row" style="width:100%"> <div class="col-sm-2"> <p><b>Dirección: </b></p> </div> <div class="col-sm-10">' + '<p>' + _direccionA + '</p>' + '</div> </div>' +
      '<div class="row" style="width:100%"> <div class="col-sm-2"> <p><b>Estatus: </b></p> </div> <div class="col-sm-10">' + '<p id="statusReportA'+ _RID + '">' +  _estatusA + '</p>' +'</div> </div>' +

      '<hr>' +
      '<button type="button" class="btn btn-default btn-sm" id="atenderReporte' + _RID + '" onclick="AtenderReporte(' + 4 + "," + _RID + "," + "\'" + _tipoReportA + "\'" +  "," + "\'" + _colonoA + "\'" + ","  + _colId  + "," + 4 + ')" style="border: solid 1px #607D8B;color: #fdfdff;background: #607D8B;"><span class="fa fa-exclamation-circle fa-x2"></span>&nbsp;Atender </button>' +
      '&nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" id="resolvedReporte' + _RID + '" onclick="ResolveReporte(' + 5 + "," + _RID + "," + "\'" + _tipoReportA + "\'" +  "," + "\'" + _colonoA + "\'" + ","  + _colId  + "," + 5 + ')" style="display: none;border: solid 1px #4CAF50;color: white;background: #4CAF50;"><span class="fa fa-check fa-x2"></span>&nbsp;Resuelto</button>' +
      '&nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" id="cancelarReporte"  onclick="cancelReporte(' + _RID + "," + "\'" + _tipoReportA + "\'" +  ","  + _colId  + "," +  "\'" +  _colonoA  +  "\'"  +  "," + 6 + ')" style="border: solid 1px #c62e2a;background: #c62f2a;color: white;"><span class="fa fa-times fa-x2"></span>&nbsp;Cancelar</button>' +
      '<div class="checkbox check-info"  style="float: right;margin-right: 9px;margin-top: 5px;"><input type="checkbox" value="1" id="checkbox-agree" aria-invalid="false" onclick="checkNotify(this)"><label for="checkbox-agree">&nbsp;¿Notificar a colonos?</label></div>' +

      '</div>'+
      '</div>';


      var infowindowA = new google.maps.InfoWindow({
          content: contentStringA
      });

      var markerA = new google.maps.Marker({
          position: new google.maps.LatLng(_locXCenter, _locYCenter),
          map: map,
          icon: "assets/img/markerLoc.png",
          animation: google.maps.Animation.BOUNCE,
      });

      infowindowA.open(map, markerA);

      if(_estatusA === 'Estas atendiendo este reporte')
      {
                  $('#atenderReporte' + _RID).css('display', 'none');
                  $('#resolvedReporte' + _RID).css('display', 'inline');
      }

      markersReport.push(markerA);

      var latlng = new google.maps.LatLng(_locXCenter, _locXCenter);
  }
