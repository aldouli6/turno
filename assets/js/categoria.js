$(document).ready(function() {
    traeCategorias('0');
});

function traeCategorias(cat) {

    var url_request = "modules/module.categorias.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "listaCategorias",
            categoria:cat
        },
        success: function (response) {
            // console.log(response);
            var obj = JSON.parse(response);
            var cathtml='';
            
            $.each(obj, function( key, value ) {
                cathtml+= "<tr id='catId"+value.categoriaId+"'>";
                cathtml+= "<td>"+value.nombre+"</td>";
                cathtml+="<td>";
                cathtml+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal"  onclick="editarCategoria('+value.categoriaId+')">';
                cathtml+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                cathtml+=    '</button>';
                cathtml+='</td>';
                cathtml+='<td>';
                cathtml+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarCategoria('+value.categoriaId+')">';
                cathtml+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                cathtml+=    '</button>';
                cathtml+='</td>';
                cathtml+='</tr>';
            });

            $("#contenidoListaCategorias").html(cathtml);

            referenceTableCategorias();
        }
    });
}
function referenceTableCategorias() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#tableCategorias').dataTable({
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
        "columnDefs": [{
                "targets": [1,2],
                "orderable": false
            },{
                "targets": [0],
                "visible": true
            },
        ],

        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {


            //console.log(aData);

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
$('#formNewCategoria').validate({
    
    submitHandler: function (form) {
        var formulario = $(form).serialize() + "&cmd=registraCategoria";
        console.log(formulario);
        $.ajax({
            url: "modules/module.categorias.php",
            type: "POST",
            data: formulario,
            success: function (response) {
              console.log(response);
              $("#formNewCategoria").get(0).reset();
              if (response!='0') {
                var value = JSON.parse(response);
                var  botonEditar="";var  botonEliminar="";var  data="";var  getData="";
                botonEditar+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal"  onclick="alert(null)">';
                botonEditar+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                botonEditar+=    '</button>';
                botonEliminar+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="alert()">';
                botonEliminar+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                botonEliminar+=    '</button>';
                data=value.nombre+"%"+botonEditar+"%"+botonEliminar;
                getData=data.split("%");
                $("#tableCategorias").DataTable().row.add(getData).draw().node();
                swal("\u00A1En hora buena!", "La categoría se ha registrado correctamente", "success");
              } else {
                swal("\u00A1Error!", "La categoria no se ha podido registrar", "error");
              }
                
              
            }
        });
    }
  });
  function editarCategoria(id) {
    var url_request = "modules/module.categorias.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "editarCategoria",
            categoria:id,
            nuevoNombre:$('#nombre'+id).val(),
        },
        success: function (response) {
            //console.log(response);
            if (response=='0') {
                swal("\u00A1En hora buena!", "La categoría se ha editado correctamente", "success");
            } else {
                swal("\u00A1Error!", "La categoría no se ha podido registrar", "error");
            }
        }
    });
  }
  function eliminarCategoria(id) {
    swal({
            title: "\u00BFEst\u00E1s seguro de eliminar la categoria ?",
            text: "\u00A1Una vez eliminada no se podra recuperar!",
            type: "warning",
            showCancelButton: true,
            //confirmButtonClass: "btn btn-primary",
            confirmButtonColor: '#428bca',
            confirmButtonText: "Si, \u00A1elim\u00EDnalo!",
            cancelButtonText: "No, cancelar",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                var url_path = "modules/module.categorias.php";
                metodo = "POST";
                $.ajax({
                    async: true,
                    url: url_path,
                    type: metodo,
                    data: {
                        cmd: 'eliminarCategoria',
                        categoria: id
                    },
                    success: function (response) {
                        if (response == "1") {
                            $("#tableCategorias").DataTable().rows("#catId" + id).remove().draw();
                            swal("\u00A1En hora buena!", "La categoría ha sido eliminada correctamente", "success");
                        } else {
                            swal("Error", "La categoría no ha podido ser eliminada.", "error");
                        }
                    }
                });
            } //Fin if confirm
            else {
                swal("Cancelado", "No se han realizado cambios", "error");
            }
        }); //Fin mensaje cancelar usuario




}