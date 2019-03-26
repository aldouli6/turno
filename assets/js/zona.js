$(document).ready(function() {
    referenceTable();
    refreshZonaTable();
});
var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();
/*$.ajax({ //Se carga la lista de los subfraccionamientos en el formulario de registro del zonas
    url: "modules/module.zonas.php",
    type: "POST",
    data: {
          cmd: "listaSubfraccionamientos"
    },
    success: function(response) {
        $('#regZonaSubfraccionamientoId').html(response);
        $('#editZonaSubfraccionamientoId').html(response);
    }
});*/

function referenceTable() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#ZonaDataTable').dataTable({
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
              "targets": [3,4],
              "orderable": false
          },
          {
              "targets": 0,
              "visible": false
          }]
    });
}

$('#formRegistroZona').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
    },
    submitHandler: function(form) {
        //El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax
        var formulario = $(form).serialize() + "&cmd=registrarZona&regZonaSubfraccionamientoId="+subfraccionamientoRelacion;
        $.ajax({
            url: "modules/module.zonas.php",
            type: "POST",
            data: formulario,
            success: function(response) {
                $("#formRegistroZona").get(0).reset();
                //$("#regZonaSubfraccionamientoId").select2("val", 0);
                if(response!="0"){
                    swal("\u00A1En hora buena!", "la zona se ha registrado correctamente", "success");
                    getData = response.split("%");
                    $("#ZonaDataTable").DataTable().row.add(getData).draw();
                }
                else{
                    swal("\u00A1Error!", "la zona no se ha podido registrar", "error");
                }
            }
        });
    }
});

function refreshZonaTable() {
    var url_request = "modules/module.zonas.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "dataTableZona",
            subfraccionamientoRelacion: subfraccionamientoRelacion
        },
        success: function(response) {
            if (response!=""){
                getData = response.split(",");
                for (x=0; x<getData.length; x++){
                    data = getData[x].split("%");
                    $("#ZonaDataTable").DataTable().row.add(data).draw();
                }
            }
        }
    });
}

function editZonaData(id_zona) {
    /*Esta funcion recibe como parámetro el ID de la zona y con este se obtendrán los datos de la zona para posteriormente proseguir con la edición*/
    var url_request = "modules/module.zonas.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: 'setDatosZona',
            claveZona: id_zona
        },
        success: function(response) {
            var DatosZona = jQuery.parseJSON( response );
            $("#IdZonaEditar").val(DatosZona.ZonaId);
            $("#editZonaDescripcion").val(DatosZona.ZonaDescripcion);
            //$("#editZonaSubfraccionamientoId").select2("val",DatosZona.SubfraccionamientoId);
        }
    });
}

$('#formEdicionZona').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
    },
    submitHandler: function(form) {
        /*El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax*/
        var formularioEditar = $(form).serialize() + "&cmd=editarZona&editZonaSubfraccionamientoId="+subfraccionamientoRelacion;
        $.ajax({
            url: "modules/module.zonas.php",
            type: "POST",
            data: formularioEditar,
            success: function(response) {
                if(response!="0"){
                    getData = response.split("%");
                    $("#ZonaDataTable").DataTable().rows().eq(0).each(function(index) {
                        var ZonaIdTable = $("#ZonaDataTable").DataTable().cell(index, 0).data();
                        if(ZonaIdTable==$("#IdZonaEditar").val()){
                            $("#ZonaDataTable").DataTable().cell(index,1).data(getData[1]);
                            $("#ZonaDataTable").DataTable().cell(index,2).data(getData[2]);
                            swal("\u00A1En hora buena!", "la zona se ha editado correctamente", "success");
                        }
                    });
                } else{
                    swal("\u00A1Error!", "la zona no se ha podido editar", "error");
                }
            }
        });
    }
});

function eliminarZonaData(id_zona) {
    swal({
    title: "\u00BFEst\u00E1s seguro de eliminar la zona con la clave "+id_zona+"?",
    text: "\u00A1Una vez eliminada no se podra recuperar!",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: '#428bca',
    confirmButtonText: "Si, \u00A1elim\u00EDnala!",
    cancelButtonText: "No, cancelar",
    closeOnConfirm: false,
    closeOnCancel: false
    },
    function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                //async: true,
                url: "modules/module.zonas.php",
                type: "POST",
                data: {
                    cmd: 'eliminarZona',
                    claveZonaEliminar: id_zona
                },
                success: function(response) {
                    if(response=="1"){
                        $("#ZonaDataTable").DataTable().rows().eq(0).each(function(index) {
                            var rowZona = $("#ZonaDataTable").DataTable().cell(index, 0).data();
                            if (rowZona == id_zona) {
                                $("#ZonaDataTable").DataTable().row(index).remove().draw();
                            }
                        });
                        swal("\u00A1En hora buena!","la zona ha sido eliminada correctamente", "success");
                    } else{
                        swal("Error", "La zona no ha podido ser eliminada.", "error");
                    }
                }
            });
        } else {
           swal("Cancelado", "No se ha realizado ning\u00FAn cambio", "error");
        }
    });
}