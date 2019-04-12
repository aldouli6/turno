$(document).ready(function() {
    getRecursos($('#establecimientoIdNew').val());
    
});
function tableListenerRow(){
    var table = $('#tablaRecurso').DataTable();
     
    $('#contenidoRecurso').on('click', 'tr', function () {
        var data = table.row( '#'+this.id ).data();
        var recurso = data.DT_RowId.substr(9);
        var establecimiento = $("#establecimientoIdNew").val();
        $("#thRelRecSesion").text(data[0]);
        getRelTipoSesiones(establecimiento, recurso);
        cargaSelect(establecimiento,recurso);
    } );
}
function cargaSelect(estab,rec) {
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "cargaSelect",
            establecimiento:estab,
            recurso: rec
        },
        success: function (response) {
            
            var obj = JSON.parse(response);
            var html='<option value="">Selecciona un Tipo de Sesión</option>';
            $.each(obj, function( key, value ) {
                html+= '<option value="'+value.id+'">'+value.nombre+'</option>';
            });
            $("#sesionIdSelect").html(html);
        }
    });
}
function getRelTipoSesiones(estab,rec) {
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRelTipoSesiones",
            establecimiento:estab,
            recurso: rec
        },
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            var html='';
            $.each(obj, function( key, value ) {
                html+= "<tr id='relSesionId"+value.id+"'>";
                html+= "<td>"+value.nombre+"</td>";
                html+='<td>';
                html+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarRelSesion('+value.id+' )">';
                html+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                html+=    '</button>';
                html+='</td>';
                html+='</tr>';
            });
            $("#contenidoListaRelRecSesion").html(html);
            referenceTable("#tableRelRecSesion");
        }
    });
}
function getRecursos(id) {

    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRecursos",
            establecimiento:id
        },
        success: function (response) {
            
            var obj = JSON.parse(response);
            var html='';
            $.each(obj, function( key, value ) {
                html+= "<tr id='recursoId"+value.recursoId+"'>";
                html+= "<td>"+value.nombre+"</td>";
                html+= "<td>"+value.cantidad+"</td>";
                html+="<td>";
                html+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditarRecurso"  onclick="editarRecurso('+value.recursoId+')">';
                html+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                html+=    '</button>';
                html+='</td>';
                html+='<td>';
                html+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarRecurso('+value.recursoId+' )">';
                html+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                html+=    '</button>';
                html+='</td>';
                html+='</tr>';
            });
            $("#contenidoRecurso").html(html);
            referenceTable("#tablaRecurso");
            tableListenerRow(); 
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


            //console.log(aData);

            if (aData[5] == 0) {
                $("#recursoId" + aData[0]).css('color', 'red');
                $("#recursoId" + aData[0]).css('font-weight', 'bolder');
                // $('td', nRow).css('background-color', '#ffd9d9');
                //$('td', nRow).css('color', 'red');
                //$('td', nRow).css('font-weight', 'bolder');
            } else if (aData[5] == 1) {
                $("#recursoId" + aData[0]).css('color', '#626262');
                $("#recursoId" + aData[0]).css('font-weight', 'inherit');
            }
        }

    });
    
}
$('#formRecursoNew').validate({
    submitHandler: function (form) {
        var formulario = $(form).serialize();
        $.ajax({
            url: "modules/module.recurso.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                $('.formRecurso').get(0).reset();
                if (response != "0") {
                    Swal.fire("\u00A1En hora buena!", "El Recurso se ha registrado correctamente", "success");
                    $('.modalrecurso').modal('hide');
                    var value = JSON.parse(response);
                    var  botonEditar="";var  botonEliminar="";var  data="";var  getData="";
                    botonEditar+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#formEditarRecurso"  onclick="editarRecurso('+value.recursoId+' )">';
                    botonEditar+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                    botonEditar+=    '</button>';
                    botonEliminar+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarRecurso('+value.recursoId+')">';
                    botonEliminar+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                    botonEliminar+=    '</button>';
                    data=value.nombre+"%"+value.cantidad+"%"+botonEditar+"%"+botonEliminar;
                    getData=data.split("%");
                    $("#tablaRecurso").DataTable().row.add(getData).draw().node();
                } else {
                    Swal.fire("\u00A1Error!", "El recurso no se ha podido registrar", "error");
                    $('.modalrecurso').modal('hide');
                }
            }
        });
    }
});
$('#formRecursoEdit').validate({
    submitHandler: function (form) {
        
        var formulario = $(form).serialize();
        //console.log(formulario);
        $.ajax({
            url: "modules/module.recurso.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                var obj = JSON.parse(response);
                if (response!='0') {
                    $('#tablaRecurso').DataTable().cell(("#recursoId" +obj.recursoId),0).data(obj.nombre);
                    $('#tablaRecurso').DataTable().cell(("#recursoId" +obj.recursoId),1).data(obj.cantidad);
                    Swal.fire("\u00A1En hora buena!", "El recurso ha sido editado correctamente", "success");
                    $('.modalrecurso').modal('hide');
                }else{
                    Swal.fire("Error", "El recurso no ha podido ser eliminada.", "error");
                    $('.modalrecurso').modal('hide');
                }
            }
        });
    }
});
function editarRecurso(id) {
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRecurso",
            recurso:id
        },
        success: function (response) {
            console.log(response);
           var obj = JSON.parse(response);
           $('#recursoIdEdit').val(obj.recursoId);
           $('#recursoNombreEdit').val(obj.nombre);
           $('#recursoCantidadEdit').val(obj.cantidad);
                
        }
    });
}
function eliminarRecurso(id) {
    Swal.fire({
        title: "\u00BFEst\u00E1s seguro de eliminar el recurso ?",
        text: "\u00A1Una vez eliminado no se podra recuperar!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn btn-primary",
        confirmButtonColor: '#428bca',
        confirmButtonText: "Si, \u00A1elim\u00EDnalo!",
        cancelButtonText: "No, cancelar",
    }).then((result) => {
        if (result.value) {
            var url_path = "modules/module.recurso.php";
            metodo = "POST";
            $.ajax({
                async: true,
                url: url_path,
                type: metodo,
                data: {
                    cmd: 'eliminarRecurso',
                    recurso: id
                },
                success: function (response) {
                    if (response == "1") {
                        $('#tablaRecurso').DataTable().rows("#recursoId" + id).remove().draw();
                        Swal.fire("\u00A1En hora buena!", "El recurso ha sido eliminado correctamente", "success");
                    } else {
                        Swal.fire("Error", "El recurso no ha podido ser eliminado.", "error");
                    }
                }
            });
        }else {
            Swal.fire("Cancelado", "No se han realizado cambios", "error");
        }
    });
}