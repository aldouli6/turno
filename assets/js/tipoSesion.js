$(document).ready(function() {
    getTiposSesiones($('#establecimientoIdNew').val());
    $('[data-toggle="tooltip"]').tooltip();
    $(".timepicker").datetimepicker({
        format: 'HH:mm'
    });
    $(".datepicker").datetimepicker({
        format:'YYYY-MM-DD'
    });
    cargarOptions();
    
});
function cargarOptions(){
    var options='';
    var options24='';
    for (var i = 0; i < 60; i++) {
        if(i<24)
            options24+='<option value="'+i+'">'+i+'</option>';
        options+='<option value="'+i+'">'+i+'</option>';
    } 
    $('select.sesentas').html(options) 
    $('select.sesentas').select2("val", 0);
    $('select.veintitres').html(options24) 
    $('select.veintitres').select2("val", 0);
}
function getTiposSesiones(id) {

    var url_request = "modules/module.tipoSesion.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getTipoSesiones",
            establecimiento:id
        },
        success: function (response) {
            
            var obj = JSON.parse(response);
            var html='';
            $.each(obj, function( key, value ) {
                html+= "<tr id='tsesId"+value.tipoSesionId+"'>";
                html+= "<td>"+value.nombre+"</td>";
                html+= "<td>"+value.clientesPorSesion+"</td>";
                html+= "<td>"+value.costo+"</td>";
                html+= "<td>"+value.duracion+"</td>";
                html+="<td>";
                html+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditarSesion"  onclick="editartipoSesion('+value.tipoSesionId+')">';
                html+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                html+=    '</button>';
                html+='</td>';
                html+='<td>';
                html+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminartipoSesion('+value.tipoSesionId+' )">';
                html+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                html+=    '</button>';
                html+='</td>';
                html+='</tr>';
            });
            $("#contenidoTipoSesion").html(html);
            referenceTable("#tablaTipoSesion");
                
        }
    });
}
function referenceTable(tabla) {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $(tabla).dataTable({
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
        },
        "retrieve": true,
        "columnDefs": [{
                "targets": [4,5],
                "orderable": false
            }
        ],

        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {


            ////console.log(aData);

            if (aData[5] == 0) {
                $("#catId" + aData[0]).css('color', 'red');
                $("#catId" + aData[0]).css('font-weight', 'bolder');
                // $('td', nRow).css('background-color', '#ffd9d9');
                //$('td', nRow).css('color', 'red');
                //$('td', nRow).css('font-weight', 'bolder');
            } else if (aData[5] == 1) {
                $("#catId" + aData[0]).css('color', '#626262');
                $("#catId" + aData[0]).css('font-weight', 'inherit');
            }
        }

    });
    
}
$('#formTipoSesionNew').validate({
    submitHandler: function (form) {
        var formulario = $(form).serialize();
        $.ajax({
            url: "modules/module.tipoSesion.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                $('.formTipoSesion').get(0).reset();
                $(".formTipoSesion > select").select2("val", '0');
                $('#maximoAgendarDiasNew').select2("val",'0');
                $('#maximoAgendarHorasNew').select2("val",'0');
                $('#maximoAgendarMinsNew').select2("val",'0');
                $('#limiteAntesAgendarDiasNew').select2("val",'0');
                $('#limiteAntesAgendarHorasNew').select2("val",'0');
                $('#limiteAntesAgendarMinsNew').select2("val",'0');
                if (response != "0") {
                    Swal.fire("\u00A1En hora buena!", "El Tipo de Sesión se ha registrado correctamente", "success");
                    $('.modalsesion').modal('hide');
                    var value = JSON.parse(response);
                    var  botonEditar="";var  botonEliminar="";var  data="";var  getData="";
                    botonEditar+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditarSesion"  onclick="editartipoSesion('+value.tipoSesionId+' )">';
                    botonEditar+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                    botonEditar+=    '</button>';
                    botonEliminar+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminartipoSesion('+value.tipoSesionId+')">';
                    botonEliminar+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                    botonEliminar+=    '</button>';
                    data=value.nombre+"%"+value.clientesPorSesion+"%"+value.costo+"%"+value.duracion+"%"+botonEditar+"%"+botonEliminar;
                    getData=data.split("%");
                    $("#tablaTipoSesion").DataTable().row.add(getData).draw().node().id="tsesId"+value.tipoSesionId;
                } else {
                    Swal.fire("\u00A1Error!", "El TipoSesion no se ha podido registrar", "error");
                    $('.modalsesion').modal('hide');
                }
            }
        });
    }
});
$('#formTipoSesionEdit').validate({
    submitHandler: function (form) {
        
        var formulario = $(form).serialize();
        //console.log(formulario);
        $.ajax({
            url: "modules/module.tipoSesion.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                //console.log(response);
                var obj = JSON.parse(response);
                if (response!='0') {
                    $('#tablaTipoSesion').DataTable().cell(("#tsesId" +obj.tipoSesionId),0).data(obj.nombre);
                    $('#tablaTipoSesion').DataTable().cell(("#tsesId" +obj.tipoSesionId),1).data(obj.clientesPorSesion);
                    $('#tablaTipoSesion').DataTable().cell(("#tsesId" +obj.tipoSesionId),2).data(obj.costo);
                    $('#tablaTipoSesion').DataTable().cell(("#tsesId" +obj.tipoSesionId),3).data(obj.duracion);
                    Swal.fire("\u00A1En hora buena!", "La categoría ha sido editada correctamente", "success");
                    $('.modalsesion').modal('hide');
                }else{
                    Swal.fire("Error", "La categoría no ha podido ser eliminada.", "error");
                    $('.modalsesion').modal('hide');
                }
            }
        });
    }
});
function editartipoSesion(id) {
    var url_request = "modules/module.tipoSesion.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getTipoSesion",
            tipoSesion:id
        },
        success: function (response) {
           var obj = JSON.parse(response);
           //console.log(obj)
           $('#tipoSesionIdEdit').val(obj.tipoSesionId);
           $('#SesionNombreEdit').val(obj.nombre);
           $('#clientesSesionEdit').val(obj.clientesPorSesion);
           $('#costoSesionEdit').val(obj.costo);
           $('#duracionEdit').val(obj.duracion);
           $('#tiempoEsperaEdit').val(obj.tiempoEspera);
           $('#tiempEntreSesionEdit').val(obj.tiempoEntreSesion);
           $('#maximoAgendarDiasEdit').select2("val",obj.maximoAgendarDias);
           $('#maximoAgendarHorasEdit').select2("val",obj.maximoAgendarHoras);
           $('#maximoAgendarMinsEdit').select2("val",obj.maximoAgendarMins);
           $('#limiteAntesAgendarDiasEdit').select2("val",obj.limiteAntesAgendarDias);
           $('#limiteAntesAgendarHorasEdit').select2("val",obj.limiteAntesAgendarHoras);
           $('#limiteAntesAgendarMinsEdit').select2("val",obj.limiteAntesAgendarMins);
           $('#fechaFinSesionEdit').val(obj.fechaFin);
                
        }
    });
}
function eliminartipoSesion(id) {
    Swal.fire({
        title: "\u00BFEst\u00E1s seguro de eliminar el tipo de sesión ?",
        text: "\u00A1Una vez eliminado no se podra recuperar!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn btn-primary",
        confirmButtonColor: '#428bca',
        confirmButtonText: "Si, \u00A1elim\u00EDnalo!",
        cancelButtonText: "No, cancelar",
    }).then((result) => {
        if (result.value) {
            var url_path = "modules/module.tipoSesion.php";
            metodo = "POST";
            $.ajax({
                async: true,
                url: url_path,
                type: metodo,
                data: {
                    cmd: 'eliminarTipoSesion',
                    tipoSesion: id
                },
                success: function (response) {
                    if (response == "1") {
                        $('#tablaTipoSesion').DataTable().rows("#tsesId" + id).remove().draw();
                        Swal.fire("\u00A1En hora buena!", "El tipo de sesión ha sido eliminado correctamente", "success");
                    } else {
                        Swal.fire("Error", "El tipo de sesión no ha podido ser eliminado.", "error");
                    }
                }
            });
        }else {
            Swal.fire("Cancelado", "No se han realizado cambios", "error");
        }
    });
}