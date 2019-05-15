$(document).ready(function() {
    getRecursos($('#establecimientoIdNew').val());
    cargaSelectAsuetoExtra();
    $('input').lc_switch("", "");

    var diasAsuetoOficialesNew = 1;
    $("#diasAsuetoOficialesNew").val(diasAsuetoOficialesNew);
    var diasAsuetoOficialesEdit = 1;
    $("#diasAsuetoOficialesEdit").val(diasAsuetoOficialesEdit);
    $('body').delegate('.lcs_checNew', 'lcs-on', function () {
        document.getElementById('divstatus2New').style.display = 'none';
        document.getElementById('divstatus1New').style.display = 'block';
        diasAsuetoOficialesNew = 1;
        $("#diasAsuetoOficialesNew").val(diasAsuetoOficialesNew);
    });
    $('body').delegate('.lcs_checNew', 'lcs-off', function () {
        document.getElementById('divstatus2New').style.display = 'block';
        document.getElementById('divstatus1New').style.display = 'none';
        diasAsuetoOficialesNew = 0;
        $("#diasAsuetoOficialesNew").val(diasAsuetoOficialesNew);
    });
    $('body').delegate('.lcs_checkEdit', 'lcs-on', function () {
        document.getElementById('divstatus2Edit').style.display = 'none';
        document.getElementById('divstatus1Edit').style.display = 'block';
        diasAsuetoOficialesEdit = 1;
        $("#diasAsuetoOficialesEdit").val(diasAsuetoOficialesEdit);
    });
    $('body').delegate('.lcs_checkEdit', 'lcs-off', function () {
        document.getElementById('divstatus2Edit').style.display = 'block';
        document.getElementById('divstatus1Edit').style.display = 'none';
        diasAsuetoOficialesEdit = 0;
        $("#diasAsuetoOficialesEdit").val(diasAsuetoOficialesEdit);
    });
});
function cargaSelectAsuetoExtra(params) {
    var d = new Date();
    var months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oc", "Nov", "Dic"];
    var html='';
    for (let i = 0; i < 730; i++) {
        d.setDate(d.getDate() + 1);
        fechaString=("0" + d.getDate()).slice(-2)+' '+months[d.getMonth()]+' '+d.getFullYear();
        fechaValue=d.getFullYear()+'-'+("0" + (d.getMonth()+1)).slice(-2)+'-'+("0" + d.getDate()).slice(-2)
        html+= '<option value="'+fechaValue+'">'+fechaString+'</option>';
           
    }
    $("#diasAsuetoExtraNew").html(html); 
    $("#diasAsuetoExtraEdit").html(html); 
     
}
function tableListenerRow(){
    var table = $('#tablaRecurso').DataTable();
     
    $('#contenidoRecurso').on('click', 'tr', function () {
        $('#relRecursoTipoSesion').removeClass('hide');
        var data = table.row( '#'+this.id ).data();
        var recurso = this.id.substr(9);
        var establecimiento = $("#establecimientoIdNew").val();
        $("#thRelRecSesion").text(data[0]);
        getRelTipoSesiones(establecimiento, recurso);
        cargaSelect(establecimiento,recurso);
        $('#recursoId').val(recurso);
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
            var html='<option ></option>';
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
            var obj = JSON.parse(response);
            var html='';
            $.each(obj, function( key, value ) {
                html+= "<tr id='relSesionId"+value.id+"'>";
                html+= "<td>"+value.nombre+"</td>";
                html+='<td>';
                html+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarRelSesion('+value.id+', '+value.tipoSesionId+', \''+value.nombre+'\' )">';
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

$('#formNewRelRecSes').validate({
    submitHandler: function (form) {
        var formulario = $(form).serialize();
        $.ajax({
            url: "modules/module.recurso.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                if (response != "0") {
                    Swal.fire("\u00A1En hora buena!", "La relación entre recurso y tipo de sesión se ha registrado correctamente", "success");
                    var value = JSON.parse(response);
                    console.log(value);
                    var  botonEditar="";var  botonEliminar="";var  data="";var  getData="";
                    $("#sesionIdSelect option[value='"+value.tipoSesionId+"']").remove();
                    $('#sesionIdSelect').select2("val", 0);
                    botonEliminar+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarRelSesion('+value.id+', '+value.tipoSesionId+', \''+value.nombre+'\' )">';
                    botonEliminar+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                    botonEliminar+=    '</button>';
                    data=value.nombre+"%"+botonEliminar;
                    getData=data.split("%");
                    $("#tableRelRecSesion").DataTable().row.add(getData).draw().node().id="relSesionId"+value.id;
                    referenceTable("#tableRelRecSesion");
                } else {
                    Swal.fire("\u00A1Error!", "La relación entre recurso y tipo de sesión no se ha podido registrar", "error");
                   
                }
            }
        });
    }
});
function eliminarRelSesion(id, tipoSesionId, nombre) {
    Swal.fire({
        title: "\u00BFEst\u00E1s seguro de eliminar el tipo de sesión de este recurso ?",
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
                    cmd: 'eliminarRelRecSesion',
                    relrecurso: id
                },
                success: function (response) {
                    if (response == "1") {
                        $("#sesionIdSelect").append('<option value="'+tipoSesionId+'">'+nombre+'</option>');
                        $('#sesionIdSelect').select2("val", 0);
                        $('#tableRelRecSesion').DataTable().rows("#relSesionId" + id).remove().draw();
                        //referenceTable("#tableRelRecSesion");
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
                    data=value.nombre+"%"+botonEditar+"%"+botonEliminar;
                    getData=data.split("%");
                    $("#tablaRecurso").DataTable().row.add(getData).draw().node().id="recursoId"+value.recursoId;
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
                    Swal.fire("\u00A1En hora buena!", "El recurso ha sido editado correctamente", "success");
                    $('.modalrecurso').modal('hide');
                }else{
                    Swal.fire("Error", "El recurso no ha podido ser editada.", "error");
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
            var obj = JSON.parse(response);
            var diasAExtr = JSON.parse(obj.diasAsuetoExtra);
            $('#recursoIdEdit').val(obj.recursoId);
            $('#recursoNombreEdit').val(obj.nombre);
            $('#diasAsuetoExtraEdit').val(diasAExtr).trigger('change');  
            if(obj.diasAsuetoOficiales=='1')
                $('input').lcs_on();
            else
                $('input').lcs_off();
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
                        $('#relRecursoTipoSesion').addClass('hide');
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