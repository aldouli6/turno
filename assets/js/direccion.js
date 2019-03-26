$(document).ready(function() {
    referenceTable();
    refreshDireccionTable();
});

var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();

function referenceTable() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#direccionDataTable').dataTable({
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
              "targets": 5,
              "orderable": false
          },
          {
            "targets": 0,
            "visible": false
          }]
    });
}

function refreshDireccionTable() {
    var url_request = "modules/module.direcciones.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "dataTableDireccion",
            subfraccionamientoRelacion: subfraccionamientoRelacion
        },
        success: function(response) {
            console.log(response);
            if (response!=""){
                getData = response.split(",");
                for (x=0; x<getData.length; x++){
                    data = getData[x].split("%");
                    $("#direccionDataTable").DataTable().row.add(data).draw();
                }
            }
        }
    });
}

function eliminarDireccionData(id_direccion) {
    swal({
        title: "\u00BFEst\u00E1s seguro de eliminar la direccion con la clave "+id_direccion+"?",
        text: "\u00A1Una vez eliminada no se podra recuperar!",
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
                url: "modules/module.direcciones.php",
                type: "POST",
                data: {
                    cmd: 'eliminarDireccion',
                    claveDireccionEliminar: id_direccion
                },
                success: function(response) {
                    if(response=="1"){
                        $("#direccionDataTable").DataTable().rows().eq(0).each(function(index) {
                            var rowDireccion = $("#direccionDataTable").DataTable().cell(index, 0).data();
                            if (rowDireccion == id_direccion) {
                                $("#direccionDataTable").DataTable().row(index).remove().draw();
                            }
                        });
                        swal("\u00A1En hora buena!","la direccion ha sido eliminada correctamente", "success");
                    } else if(response=="2"){
                        swal("Atencion", "antes de eliminar la direccion, asegurate que no pertenezca a algun colono.", "warning");
                    } else{
                        swal("Error", "la direccion no ha podido ser eliminada.", "error");
                    }
                }
            });
        } else {
            swal("Cancelado", "No se ha realizado ning\u00FAn cambio", "error");
        }
    });
}