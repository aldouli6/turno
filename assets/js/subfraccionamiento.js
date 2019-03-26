$(document).ready(function() {
    referenceTable();
    refreshSubfraccionamientoTable();
    dataAdministradores(0,1);
});

$.ajax({ //Se carga la lista de los fraccionamientos en el formulario de registro del subfraccionamiento
    async: true,
    url: "modules/module.subfraccionamientos.php",
    type: "POST",
    data: {
          cmd: "listaFraccionamientos"
    },
    success: function(response) {
        $('#regSubfraccionamientoId').html(response);
        $('#editFraccionamientoId').html(response);
    }
});

function dataAdministradores(id_administrador, request){
    $.ajax({ //Se carga la lista de los administradores en el formulario de registro del subfraccionamiento
        async: true,
        url: "modules/module.subfraccionamientos.php",
        type: "POST",
        data: {
              cmd: "listaAdministradores",
              idAdministrador: id_administrador
        },
        success: function(response) {
            if(request == 1){
                $('#regSubfraccionamientoAdmin').html(response);
            } else if (request == 2){
                $('#editSubfraccionamientoAdmin').html(response);
                $("#editSubfraccionamientoAdmin").select2("val",id_administrador);
            }
        }
    });
}

function referenceTable() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#subfraccionamientoDataTable').dataTable({
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

$('#formRegistroSubfraccionamiento').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
    },
    submitHandler: function(form) {
          //El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax
          //console.log($('#regSubfraccionamientoAdmin').val());
          var formulario = $(form).serialize() + "&cmd=registrarSubfraccionamiento";

          console.log(formulario);

          $.ajax({
              async: true,
              url: "modules/module.subfraccionamientos.php",
              type: "POST",
              data: formulario,
              success: function(response) 
              {
                console.log("Respuesta del server: " + response);

                  /*$("#formRegistroSubfraccionamiento").get(0).reset();
                  $("#regSubfraccionamientoId").select2("val", 0);
                  $("#regSubfraccionamientoAdmin").select2("val", 0);
                  if(response!="1" && response!="2" && response!="3"){
                        swal("\u00A1En hora buena!", "el subfraccionamiento se ha registrado correctamente", "success");
                        getData = response.split("%");
                        $("#subfraccionamientoDataTable").DataTable().row.add(getData).draw();
                        dataAdministradores(0,1);
                  }
                  else{
                        swal("\u00A1Error "+response+"!", "el subfraccionamiento no se ha podido registrar", "error");
                  }*/
              }
          });
    }
});

function refreshSubfraccionamientoTable() {
    var url_request = "modules/module.subfraccionamientos.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "dataTableSubfraccionamiento"
        },
        success: function(response) {
            if (response!=""){
                getData = response.split("**");
                for (x=0; x<getData.length; x++){
                    data = getData[x].split("%");
                    $("#subfraccionamientoDataTable").DataTable().row.add(data).draw();
                }
            }
        }
    });
}

function editSubfraccionamientoData(id_subfraccionamiento, id_administrador) {
    /*Esta funcion recibe como parámetro el ID del subfraccionamiento y con este se obtendrán los datos del subfraccionamiento para posteriormente proseguir con la edición*/
    var url_request = "modules/module.subfraccionamientos.php";
    var method = "POST";
    dataAdministradores(id_administrador, 2);
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: 'setDatosSubfraccionamiento',
            claveSubfraccionamiento: id_subfraccionamiento
        },
        success: function(response) {
            var DatosSubfraccionamiento = jQuery.parseJSON( response );
            $("#IdSubfraccionamientoEditar").val(DatosSubfraccionamiento.SubfraccionamientoId);
            $("#editSubfraccionamientoNombre").val(DatosSubfraccionamiento.SubfraccionamientoNombre);
            $("#editFraccionamientoId").select2("val",DatosSubfraccionamiento.FraccionamientoId);
            $("#IdSubfraccionamientoAdminEditar").val(id_administrador);
            console.log(DatosSubfraccionamiento.UsuarioId);
        }
    });
}

$('#formEdicionSubfraccionamiento').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
        rules: {
        },
        submitHandler: function(form) {
            /*El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax*/
            var formularioEditar = $(form).serialize() + "&cmd=editarSubfraccionamiento";
            $.ajax({
                async: true,
                url: "modules/module.subfraccionamientos.php",
                type: "POST",
                data: formularioEditar,
                success: function(response) {
                  //console.log(response);
                  if(response!="0"){
                     getData = response.split("%");
                     $("#subfraccionamientoDataTable").DataTable().rows().eq(0).each(function(index) {
                             var SubfraccionamientoIdTable = $("#subfraccionamientoDataTable").DataTable().cell(index, 0).data();
                             if(SubfraccionamientoIdTable==$("#IdSubfraccionamientoEditar").val()){
                                       $("#subfraccionamientoDataTable").DataTable().cell(index,1).data(getData[1]);
                                       $("#subfraccionamientoDataTable").DataTable().cell(index,2).data(getData[2]);
                                       $("#subfraccionamientoDataTable").DataTable().cell(index,3).data(getData[3]);
                                       $("#subfraccionamientoDataTable").DataTable().cell(index,4).data(getData[4]);
                                       $("#subfraccionamientoDataTable").DataTable().cell(index,5).data(getData[5]);
                                       $('#formEditSubfraccionamiento').modal('hide');
                                  swal("\u00A1En hora buena!", "el subfraccionamiento se ha editado correctamente", "success");
                             }
                     });
                  } else{
                     swal("\u00A1Error!", "el subfraccionamiento no se ha podido editar", "error");
                  }
                }
            });
        }
});

function eliminarSubfraccionamientoData(id_subfraccionamiento, id_administrador) {
    swal({
    title: "\u00BFEst\u00E1s seguro de eliminar el subfraccionamiento con la clave "+id_subfraccionamiento+"?",
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
                        url: "modules/module.subfraccionamientos.php",
                        type: "POST",
                        data: {
                            cmd: 'eliminarSubfraccionamiento',
                            claveSubfraccionamientoEliminar: id_subfraccionamiento,
                            administradorId: id_administrador
                        },
                        success: function(response) {
                              if(response=="1"){
                                    $("#subfraccionamientoDataTable").DataTable().rows().eq(0).each(function(index) {
                                        var rowSubfraccionamiento = $("#subfraccionamientoDataTable").DataTable().cell(index, 0).data();
                                        if (rowSubfraccionamiento == id_subfraccionamiento) {
                                            $("#subfraccionamientoDataTable").DataTable().row(index).remove().draw();
                                        }
                                    });
                                  swal("\u00A1En hora buena!","el subfraccionamiento ha sido eliminado correctamente", "success");
                              } else if(response=="2"){
                                  swal("Atencion", "Antes de eliminar el Subfraccionamiento se deben eliminar las calles relacionadas a este.", "warning");
                              } else{
                                  swal("Error", "El subfraccionamiento no ha podido ser eliminado.", "error");
                              }
                        }
                    });
          } else {
           swal("Cancelado", "No se ha realizado ning\u00FAn cambio", "error");
          }
    });
}