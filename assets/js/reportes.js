var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();
var map;
var marker = null;

$(document).ready(function() {
    referenceTable();
    refreshReporteTable();

    //console.log(subfraccionamientoRelacion);
});

function referenceTable() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#reporteDataTable').dataTable({
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
          {"targets": 8,"orderable": false},
          {"targets": [0,5,6,7], "visible": false}
        ],"dom":"Bfrtip",
        "buttons": ["excel"]
        //buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
}

function refreshReporteTable() {
    var url_request = "modules/module.reportes.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "listaReportes",
            subfraccionamientoRelacion: subfraccionamientoRelacion
        },
        success: function(response) {
            if (response!=""){
                getData = response.split("**");
                for (x=0; x<getData.length; x++){
                    data = getData[x].split("%");
                    $("#reporteDataTable").DataTable().row.add(data).draw();
                }
            }
        }
    });
}

function detallesReporteData(reporteId){
    //console.log(reporteId);
    var url_request = "modules/module.reportes.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getDataReporte",
            idReporte: reporteId
        },
        success: function(response){
            //console.log(response);
            if(response != ""){
                if (marker != null){
                    marker.setMap(null);
                }
                var DatosReporte = jQuery.parseJSON(response);
                map.setCenter(new google.maps.LatLng(DatosReporte.ReporteLatitud, DatosReporte.ReporteLongitud));
                map.setZoom(18);
                $("#infoTipoReporte").text(DatosReporte.TiporeporteDescripcion);
                $("#infoColono").text(DatosReporte.UsuarioNombreCompleto);
                $("#infoFecha").text(DatosReporte.ReporteRegistro);
                $("#infoEstatus").text(DatosReporte.EstatusDescripcion);
                $("#infoComentario").text(DatosReporte.ReporteComentario);
                $("#infoUbicacion").text(DatosReporte.ReporteDireccionDetalle);
                $("#infoComentarioFinal").text(DatosReporte.RazonCancelado);
                //var reporteLocation = {lat: DatosReporte.ReporteLatitud, lng: DatosReporte.ReporteLongitud};
                //marker.setMap(null);
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(DatosReporte.ReporteLatitud, DatosReporte.ReporteLongitud),
                    title:DatosReporte.TiporeporteDescripcion
                });
                marker.setMap(map);
            }
        }
    })
}

function initMap() {
    var uluru = {lat: -25.363, lng: 131.044};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 18,
        center: uluru,
        disableDefaultUI: true
    });
    map.setOptions({draggable: false});
    google.maps.event.trigger(map, "resize");
}

$("#modFormDetallesReporte").on("shown.bs.modal", function () {
    google.maps.event.trigger(map, "resize");
});