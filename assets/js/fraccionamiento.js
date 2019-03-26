$(document).ready(function() {
    referenceTable();
    refreshFraccionamientoTable();
});

$.ajax({ //Se carga la lista de los estados en el formulario de registro del fraccionamiento
    url: "modules/module.fraccionamientos.php",
    type: "POST",
    data: {
          cmd: "listaEstados"
    },
    success: function(response) {
        $('#regFraccionamientoEstado').html(response);
        $('#editFraccionamientoEstado').html(response);
    }
});

$("#regFraccionamientoEstado").change(function() {
    //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
    $("#regFraccionamientoMunicipio").select2("val", 0);
    $.ajax({ //Se carga la lista de los municipios en la lista desplegable
        url: "modules/module.fraccionamientos.php",
        type: "POST",
        data: {
            cmd: "listaMunicipios",
            idEstadoFraccionamiento: $("#regFraccionamientoEstado").val()
        },
        success: function(response) {
            $('#regFraccionamientoMunicipio').html(response);
        }
    });
});

$("#editFraccionamientoEstado").change(function() {
    //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
    $("#editFraccionamientoMunicipio").select2("val", 0);
    $.ajax({ //Se carga la lista de los municipios en la lista desplegable
        url: "modules/module.fraccionamientos.php",
        type: "POST",
        data: {
            cmd: "listaMunicipios",
            idEstadoFraccionamiento: $("#editFraccionamientoEstado").val()
        },
        success: function(response) {
            $('#editFraccionamientoMunicipio').html(response);
        }
    });
});



$('#formRegistroFraccionamiento').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
    },
    submitHandler: function(form) {
          //El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax
          var formulario = $(form).serialize() + "&cmd=registrarFraccionamiento";
          //console.log(formulario);
          $.ajax({
              url: "modules/module.fraccionamientos.php",
              type: "POST",
              data: formulario,
              success: function(response) {
                  $("#formRegistroFraccionamiento").get(0).reset();
                  $("#regFraccionamientoEstado").select2("val", 0);
                  $("#regFraccionamientoMunicipio").html('<option value="0">Seleccione el municipio</option>');
                  $("#regFraccionamientoMunicipio").select2("val", 0);
                  if(response!="0"){
                        swal("\u00A1En hora buena!", "el fraccionamiento se ha registrado correctamente", "success");
                        getData = response.split("%");
                        $("#fraccionamientoDataTable").DataTable().row.add(getData).draw();
                  }
                  else{
                        swal("\u00A1Error!", "el fraccionamiento no se ha podido registrar", "error");
                  }
              }
          });
    }
});

function refreshFraccionamientoTable() {
    var url_request = "modules/module.fraccionamientos.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "dataTableFraccionamiento"
        },
        success: function(response) {
            if (response!=""){
                getData = response.split(",");
                for (x=0; x<getData.length; x++){
                    data = getData[x].split("%");
                    $("#fraccionamientoDataTable").DataTable().row.add(data).draw();
                }
            }
        }
    });
}

function referenceTable() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#fraccionamientoDataTable').dataTable({
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
          }]
    });
}

function editFraccionamientoData(id_fraccionamiento) {
    /*Esta funcion recibe como parámetro el ID del fraccionamiento y con este se obtendrán los datos del fraccionamiento para posteriormente proseguir con la edición*/
    var url_request = "modules/module.fraccionamientos.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: 'setDatosFraccionamiento',
            claveFraccionamiento: id_fraccionamiento
        },
        success: function(response) {
            var DatosFraccionamiento = jQuery.parseJSON( response );
            $("#IdFraccionamientoEditar").val(DatosFraccionamiento.FraccionamientoId);
            $("#editFraccionamientoNombre").val(DatosFraccionamiento.FraccionamientoNombre);
            $("#editFraccionamientoEstado").select2("val",DatosFraccionamiento.EstadoId);
            $.ajax({ //Se carga la lista de los muncipios de acuerdo al estado que tenga el usuario
                url: "modules/module.fraccionamientos.php",
                type: "POST",
                data: {
                    cmd: "listaMunicipios",
                    idEstadoFraccionamiento: DatosFraccionamiento.EstadoId
                },
                success: function(response) {
                    $('#editFraccionamientoMunicipio').html(response);
                    $('#editFraccionamientoMunicipio').select2('val',DatosFraccionamiento.MunicipioId);
                }
            });
        }
    });
}

$('#formEdicionFraccionamiento').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
        rules: {
        },
        submitHandler: function(form) {
            /*El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax*/
            var formularioEditar = $(form).serialize() + "&cmd=editarFraccionamiento";
            $.ajax({
                url: "modules/module.fraccionamientos.php",
                type: "POST",
                data: formularioEditar,
                success: function(response) {
                  if(response!="0"){
                     getData = response.split("%");
                     $("#fraccionamientoDataTable").DataTable().rows().eq(0).each(function(index) {
                             var FraccionamientoIdTable = $("#fraccionamientoDataTable").DataTable().cell(index, 0).data();
                             if(FraccionamientoIdTable==$("#IdFraccionamientoEditar").val()){
                                       $("#fraccionamientoDataTable").DataTable().cell(index,1).data(getData[1]);
                                       $("#fraccionamientoDataTable").DataTable().cell(index,2).data(getData[2]);
                                       $("#fraccionamientoDataTable").DataTable().cell(index,3).data(getData[3]);
                                  swal("\u00A1En hora buena!", "el fraccionamiento se ha editado correctamente", "success");
                             }
                     });
                  } else{
                     swal("\u00A1Error!", "el fraccionamiento no se ha podido editar", "error");
                  }
                }
            });
        }
});

function eliminarFraccionamientoData(id_fraccionamiento) {
    swal({
    title: "\u00BFEst\u00E1s seguro de eliminar el fraccionamiento con la clave "+id_fraccionamiento+"?",
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
                        //async: true,
                        url: "modules/module.fraccionamientos.php",
                        type: "POST",
                        data: {
                            cmd: 'eliminarFraccionamiento',
                            claveFraccionamientoEliminar: id_fraccionamiento
                        },
                        success: function(response) {
                              if(response=="1"){
                                    $("#fraccionamientoDataTable").DataTable().rows().eq(0).each(function(index) {
                                        var rowFraccionamiento = $("#fraccionamientoDataTable").DataTable().cell(index, 0).data();
                                        if (rowFraccionamiento == id_fraccionamiento) {
                                            $("#fraccionamientoDataTable").DataTable().row(index).remove().draw();
                                        }
                                    });
                                  swal("\u00A1En hora buena!","el fraccionamiento ha sido eliminado correctamente", "success");
                              } else if(response=="2"){
                                  swal("Atencion", "Antes de eliminar el fraccionamiento, asegurate de eliminar los subfraccionamientos relacionados a este.", "warning");
                              } else{
                                  swal("Error", "El fraccionamiento no ha podido ser eliminado.", "error");
                              }
                        }
                    });
          } else {
           swal("Cancelado", "No se ha realizado ning\u00FAn cambio", "error");
          }
    });
}