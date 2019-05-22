$(document).ready(function() {
    //$('.multiselect').multiSelect();
    cargaSelectRecuros($('#establecimientoIdNew').val());
    getHorarios($('#establecimientoIdNew').val());
    $('.timepicker').each(function () {
        $(this).datetimepicker({
            format: "HH:mm",
            //sideBySide: true,
            ignoreReadonly:true
        });
    });
    // $(".timepicker").datetimepicker().ignoreReadonly(true)

});
function editarHorario(horaioId) {
    var url_request = "modules/module.horarios.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getHorario",
            horario:horaioId
        },
        success: function (response) {
            var obj = JSON.parse(response);
            var diasL = JSON.parse(obj.diasLaborables);
            $('#horarioIdEdit').val(obj.horarioRecursoId);
            $('#recursoIdEdit').select2("val",obj.recursoId);
            $('#horaInicioEdit').val(obj.horaInicio);
            $('#horaFinEdit').val(obj.horaFin);
            $('#diasLaboralesEdit').val(diasL).trigger('change');
       }
    });
}
function getHorarios(estab) {
    var url_request = "modules/module.horarios.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getHorarios",
            establecimiento:estab
        },
        success: function (response) {
            var obj = JSON.parse(response);
            var html='';
            var nombres={"D":"Domingo",
                            "L":"Lunes",
                            "M":"Martes",
                            "X":"Miércoles",
                            "J":"Jueves",
                            "V":"Viernes",
                            "S":"Sábado"}
            $.each(obj, function( key, value ) {
                
                html+= "<tr id='horarioId"+value.id+"'>";
                html+= "<td>"+value.nombre+"</td>";
                html+= "<td class='row'>";
                var dias = JSON.parse(value.diasLaborables);
                var td=''
                $.each(dias, function (k, v) {
                   td+='<div class="col-sm-3 multipledata" >'+nombres[v]+'</div>'; 
                });
                
                html+=td+"</td>";
                html+= "<td>"+value.horaInicio+"</td>";
                html+= "<td>"+value.horaFin+"</td>";
                html+="<td>";
                html+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditarHorario"  onclick="editarHorario('+value.id+')">';
                html+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                html+=    '</button>';
                html+='</td>';
                html+='<td>';
                html+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarHorario('+value.id+' )">';
                html+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                html+=    '</button>';
                html+='</td>';
                html+='</tr>';
            });
            $("#contenidoHorario").html(html);
            referenceTable("#tablaHorario");
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
                "targets": [0],
                "orderable": true
            }
        ],

        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {



            if (aData[5] == 0) {
                $("#horarioId" + aData[0]).css('color', 'red');
                $("#horarioId" + aData[0]).css('font-weight', 'bolder');
                // $('td', nRow).css('background-color', '#ffd9d9');
                //$('td', nRow).css('color', 'red');
                //$('td', nRow).css('font-weight', 'bolder');
            } else if (aData[5] == 1) {
                $("#horarioId" + aData[0]).css('color', '#626262');
                $("#horarioId" + aData[0]).css('font-weight', 'inherit');
            }
        }

    });
    
}
$('#formHorarioEdit').validate({
    submitHandler: function (form) {
        
        var formulario = $(form).serialize();
        $.ajax({
            url: "modules/module.horarios.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                 var obj = JSON.parse(response);
                if (response!='0') {
                    var td=''
                    var nombres={"D":"Domingo",
                            "L":"Lunes",
                            "M":"Martes",
                            "X":"Miércoles",
                            "J":"Jueves",
                            "V":"Viernes",
                            "S":"Sábado"}
                    var dias = JSON.parse(obj.diasLaborables);
                    $.each(dias, function (k, v) {
                    td+='<div class="col-sm-3 multipledata" >'+nombres[v]+'</div>'; 
                    });
                    $('#tablaHorario').DataTable().cell(("#horarioId" +obj.id),0).data(obj.nombre);
                    $('#tablaHorario').DataTable().cell(("#horarioId" +obj.id),1).data(td);
                    $('#tablaHorario').DataTable().cell(("#horarioId" +obj.id),2).data(obj.horaInicio);
                    $('#tablaHorario').DataTable().cell(("#horarioId" +obj.id),3).data(obj.horaFin);
                    Swal.fire("\u00A1En hora buena!", "El horario ha sido editado correctamente", "success");
                    $('.modalhorario').modal('hide');
                }else{
                    Swal.fire("Error", "El horario no ha podido ser editado.", "error");
                    $('.modalhorario').modal('hide');
                }
            }
        });
    } 
});
$('#formHorarioNew').validate({
    submitHandler: function (form) {
        var formulario = $(form).serialize();
        $.ajax({
            url: "modules/module.horarios.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                $('#recursoIdNew').select2("val",'').trigger('change');
                // $('#recursoIdNew').select2('data', {});
                // $('#recursoIdNew').select2({ allowClear: true });
                $('#diasLaboralesNew').select2("val",0).trigger('change');
                $('#diasAsuetoExtraNew').select2("val",0).trigger('change');
                if (response != "0") {
                    Swal.fire("\u00A1En hora buena!", "El Horario se ha registrado correctamente", "success");
                    $('.modalhorario').modal('hide');
                    var value = JSON.parse(response);
                    var  botonEditar="";var  botonEliminar="";var  data="";var  getData="";
                    var dias = JSON.parse(value.diasLaborables);
                    var td=''
                    var nombres={"D":"Domingo",
                            "L":"Lunes",
                            "M":"Martes",
                            "X":"Miércoles",
                            "J":"Jueves",
                            "V":"Viernes",
                            "S":"Sábado"}
                    $.each(dias, function (k, v) {
                    td+='<div class="col-sm-3 multipledata" >'+nombres[v]+'</div>'; 
                    });
                    botonEditar+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditarHorario"  onclick="editarHorario('+value.id+' )">';
                    botonEditar+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                    botonEditar+=    '</button>';
                    botonEliminar+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarHorario('+value.id+')">';
                    botonEliminar+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                    botonEliminar+=    '</button>';
                    data=value.nombre+"%"+td+"%"+value.horaInicio+"%"+value.horaFin+"%"+botonEditar+"%"+botonEliminar;
                    getData=data.split("%");
                    $("#tablaHorario").DataTable().row.add(getData).draw().node().id="horarioId"+value.id;
                } else {
                    Swal.fire("\u00A1Error!", "El Horario no se ha podido registrar", "error");
                    $('.modalhorario').modal('hide');
                }
            }
        });
    }
});
function eliminarHorario(horarioId) {
    Swal.fire({
        title: "\u00BFEst\u00E1s seguro de eliminar el horario ?",
        text: "\u00A1Una vez eliminado no se podra recuperar!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn btn-primary",
        confirmButtonColor: '#428bca',
        confirmButtonText: "Si, \u00A1elim\u00EDnalo!",
        cancelButtonText: "No, cancelar",
    }).then((result) => {
        if (result.value) {
            var url_path = "modules/module.horarios.php";
            metodo = "POST";
            $.ajax({
                async: true,
                url: url_path,
                type: metodo,
                data: {
                    cmd: 'eliminarHorario',
                    horario: horarioId
                },
                success: function (response) {
                    if (response == "1") {
                        $('#tablaHorario').DataTable().rows("#horarioId" + horarioId).remove().draw();
                        Swal.fire("\u00A1En hora buena!", "El horario ha sido eliminado correctamente", "success");
                    } else {
                        Swal.fire("Error", "El horario no ha podido ser eliminado.", "error");
                    }
                }
            });
        }else {
            Swal.fire("Cancelado", "No se han realizado cambios", "error");
        }
    });
}

function cargaSelectRecuros(id) {
    var url_request = "modules/module.horarios.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRecuros",
            establecimiento:id
        },
        success: function (response) {
            var obj = JSON.parse(response);
            var html='';
            $.each(obj, function( key, value ) {
                html+= '<option value="'+value.id+'">'+value.nombre+'</option>';
            });
            $("#recursoIdNew").html(html); 
            $("#recursoIdEdit").html(html); 
        }
    });
}