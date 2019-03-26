$(document).ready(function() {
    referenceTable();
    refreshCalleTable();
});

var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();

/*$.ajax({ //Se carga la lista de los fraccionamientos en el formulario de registro del subfraccionamiento
    async: true,
    url: "modules/module.calles.php",
    type: "POST",
    data: {
        cmd: "listaSubfraccionamientos",
        subfraccionamientoRelacion: subfraccionamientoRelacion
    },
    success: function(response) {
        $('#regCalleSubfraccionamiento').html(response);
        $('#editCalleSubfraccionamiento').html(response);
    }
});*/

/*$("#regCalleSubfraccionamiento").change(function() {
    //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
    $("#regCalleZona").select2("val", 0);
    $.ajax({ //Se carga la lista de los municipios en la lista desplegable
        async: true,
        url: "modules/module.calles.php",
        type: "POST",
        data: {
            cmd: "listaZonas",
            idCalleSubfraccionamiento: $("#regCalleSubfraccionamiento").val()
        },
        success: function(response) {
            $('#regCalleZona').html(response);
        }
    });
});*/

$.ajax({
        async: true,
        url: "modules/module.calles.php",
        type: "POST",
        data: {
            cmd: "listaZonas",
            idCalleSubfraccionamiento: $("#SubfraccionamientoRelacion").val()
        },
        success: function(response) {
            $('#regCalleZona').html(response);
            $('#editCalleZona').html(response);
        }
    });

/*$("#editCalleSubfraccionamiento").change(function() {
    //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
    $("#editCalleZona").select2("val", 0);
    $.ajax({ //Se carga la lista de los municipios en la lista desplegable
        async: true,
        url: "modules/module.calles.php",
        type: "POST",
        data: {
            cmd: "listaZonas",
            idCalleSubfraccionamiento: $("#editCalleSubfraccionamiento").val()
        },
        success: function(response) {
            $('#editCalleZona').html(response);
        }
    });
});*/

function referenceTable() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#calleDataTable').dataTable({
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
              "targets": [4,5],
              "orderable": false
          },
          {
                "targets": [0, 3],
                "visible": false
          }]
    });
}

$('#formRegistroCalle').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
    },
    submitHandler: function(form) {
        //El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax
        var formulario = $(form).serialize() + "&cmd=registrarCalle&regCalleSubfraccionamiento="+subfraccionamientoRelacion;
        //console.log(formulario);
        $.ajax({
            async: true,
            url: "modules/module.calles.php",
            type: "POST",
            data: formulario,
            success: function(response) {
                $("#formRegistroCalle").get(0).reset();
                //$("#regCalleSubfraccionamiento").select2("val", 0);
                //$("#regCalleZona").html('<option value="0">Seleccione la zona</option>');
                $("#regCalleZona").select2("val", 0);
                if(response!="0"){
                    swal("\u00A1En hora buena!", "la calle se ha registrado correctamente", "success");
                    getData = response.split("%");
                    $("#calleDataTable").DataTable().row.add(getData).draw();
                }
                else{
                    swal("\u00A1Error!", "la calle no se ha podido registrar", "error");
                }
            }
        });
    }
});

function refreshCalleTable() {
    var url_request = "modules/module.calles.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "dataTableCalle",
            subfraccionamientoRelacion: subfraccionamientoRelacion
        },
        success: function(response) {
            console.log(response);
            if (response!=""){
                getData = response.split(",");
                for (x=0; x<getData.length; x++){
                    data = getData[x].split("%");
                    $("#calleDataTable").DataTable().row.add(data).draw();
                }
            }
        }
    });
}

function editCalleData(id_calle) {
    /*Esta funcion recibe como parámetro el ID del fraccionamiento y con este se obtendrán los datos del fraccionamiento para posteriormente proseguir con la edición*/
    var url_request = "modules/module.calles.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: 'setDatosCalle',
            claveCalle: id_calle
        },
        success: function(response) {
            var DatosCalle = jQuery.parseJSON( response );
            $("#IdCalleEditar").val(DatosCalle.CalleId);
            $("#editCalleNombre").val(DatosCalle.CalleNombre);
            $("#editCalleSubfraccionamiento").select2("val",DatosCalle.SubfraccionamientoId);
            $.ajax({ //Se carga la lista de los muncipios de acuerdo al estado que tenga el usuario
                url: "modules/module.calles.php",
                type: "POST",
                data: {
                    cmd: "listaZonas",
                    idCalleSubfraccionamiento: DatosCalle.SubfraccionamientoId
                },
                success: function(response) {
                    $('#editCalleZona').html(response);
                    $('#editCalleZona').select2('val',DatosCalle.ZonaId);
                }
            });
        }
    });
}

$('#formEdicionCalle').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
    },
    submitHandler: function(form) {
        /*El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax*/
        var formularioEditar = $(form).serialize() + "&cmd=editarCalle&editCalleSubfraccionamiento=" + subfraccionamientoRelacion;
        $.ajax({
            async: true,
            url: "modules/module.calles.php",
            type: "POST",
            data: formularioEditar,
            success: function(response) {
                if(response!="0"){
                    getData = response.split("%");
                    //console.log(getData);
                    $("#calleDataTable").DataTable().rows().eq(0).each(function(index) {
                        var CalleIdTable = $("#calleDataTable").DataTable().cell(index, 0).data();
                        if(CalleIdTable==$("#IdCalleEditar").val()){
                            $("#calleDataTable").DataTable().cell(index,1).data(getData[1]);
                            $("#calleDataTable").DataTable().cell(index,2).data(getData[2]);
                            $("#calleDataTable").DataTable().cell(index,3).data(getData[3]);
                            swal("\u00A1En hora buena!", "la calle se ha editado correctamente", "success");
                        }
                    });
                } else{
                    //console.log(response);
                    swal("\u00A1Error!", "la calle no se ha podido editar", "error");
                }
            }
        });
    }
});

function eliminarCalleData(id_calle) {
    swal({
        title: "\u00BFEst\u00E1s seguro de eliminar la calle con la clave "+id_calle+"?",
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
                url: "modules/module.calles.php",
                type: "POST",
                data: {
                    cmd: 'eliminarCalle',
                    claveCalleEliminar: id_calle
                },
                success: function(response) {
                    if(response=="1"){
                        $("#calleDataTable").DataTable().rows().eq(0).each(function(index) {
                            var rowCalle = $("#calleDataTable").DataTable().cell(index, 0).data();
                            if (rowCalle == id_calle) {
                                $("#calleDataTable").DataTable().row(index).remove().draw();
                            }
                        });
                        swal("\u00A1En hora buena!","la calle ha sido eliminado correctamente", "success");
                    } else if(response == "2"){
                        swal("Atencion", "antes de eliminar la calle, asegurate de eliminar primero las direcciones relacionadas a esta.", "warning");
                    } else{
                        swal("Error", "la calle no ha podido ser eliminado.", "error");
                    }
                }
            });
        } else {
            swal("Cancelado", "No se ha realizado ning\u00FAn cambio", "error");
        }
    });
}