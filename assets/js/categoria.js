

$(document).ready(function() {
    traeCategorias('0','#tableCategorias');
    
});
function tableListenerRow(){
    var table = $('#tableCategorias').DataTable();
     
    $('#tableCategorias tbody').on('click', 'tr', function () {
        var data = table.row( this ).data();
        var id = data.DT_RowId.substr(5);
        traeCategorias(id, '#tableSubCategorias', "Categoría Padre: <b>"+data[0]+"</b>");
        
    } );
}

function traeCategorias(cat, tabla, padre="") {

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
            
            var obj = JSON.parse(response);
            var cathtml='';
            $.each(obj, function( key, value ) {
                cathtml+= "<tr id='catId"+value.categoriaId+"'>";
                cathtml+= "<td>"+value.nombre+"</td>";
                cathtml+="<td>";
                cathtml+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal"  onclick="editarCategoria('+value.categoriaId+', \''+value.nombre+'\',\''+tabla+'\'  )">';
                cathtml+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                cathtml+=    '</button>';
                cathtml+='</td>';
                cathtml+='<td>';
                cathtml+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarCategoria('+value.categoriaId+',\''+tabla+'\' )">';
                cathtml+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                cathtml+=    '</button>';
                cathtml+='</td>';
                cathtml+='</tr>';
            });
            // console.log(obj);
            $(tabla).DataTable().destroy();
            $(tabla+"  tbody").empty();
            $(tabla+"  tbody").html(cathtml);
            if (tabla=="#tableSubCategorias") {
                $("#ThCategoriaPadre").html(padre)
                $("#categoriaPadreInp").val(cat);
            }
            referenceTableCategorias(tabla);
                
        }
    });
}
function referenceTableCategorias(tabla) {
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
if (tabla=='#tableCategorias') {
    tableListenerRow();
}
    
}
$('#formNewCategoria').validate({
    
    submitHandler: function (form) {
        var formulario = $(form).serialize() + "&cmd=registraCategoria";
        $.ajax({
            url: "modules/module.categorias.php",
            type: "POST",
            data: formulario,
            success: function (response) {
              $("#formNewCategoria").get(0).reset();
              if (response!='0') {
                var value = JSON.parse(response);
                var  botonEditar="";var  botonEliminar="";var  data="";var  getData="";
                botonEditar+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal"  onclick="editarCategoria('+value.categoriaId+', \''+value.nombre+'\',\'#tableCategorias\'  )">';
                botonEditar+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                botonEditar+=    '</button>';
                botonEliminar+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarCategoria('+value.categoriaId+',\'#tableCategorias\' )">';
                botonEliminar+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                botonEliminar+=    '</button>';
                data=value.nombre+"%"+botonEditar+"%"+botonEliminar;
                getData=data.split("%");
                $("#tableCategorias").DataTable().row.add(getData).draw().node();
                Swal.fire("\u00A1En hora buena!", "La categoría se ha registrado correctamente", "success");
              } else {
                Swal.fire("\u00A1Error!", "La categoria no se ha podido registrar", "error");
              }
                
              
            }
        });
    }
  });
  $('#formNewSubCategoria').validate({
    
    submitHandler: function (form) {
        var formulario = $(form).serialize() + "&cmd=registraCategoria";
        // console.log(formulario);
        $.ajax({
            url: "modules/module.categorias.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                // console.log(response);
              $("#formNewSubCategoria").get(0).reset();
              if (response!='0') {
                  
                var value = JSON.parse(response);

                var  botonEditar="";var  botonEliminar="";var  data="";var  getData="";
                botonEditar+=   '<button type="button" class="btn btn-default btn-sm" data-toggle="modal"  onclick="editarCategoria('+value.categoriaId+', \''+value.nombre+'\',\'#tableSubCategorias\'  )">';
                botonEditar+=    '<span class="glyphicon glyphicon-pencil capa"></span>';
                botonEditar+=    '</button>';
                botonEliminar+=   '<button style="background:gray;" type="button" class="btn btn-default btn-sm" onclick="eliminarCategoria('+value.categoriaId+',\'#tableSubCategorias\' )">';
                botonEliminar+=        '<span class="glyphicon glyphicon-trash capa" style="color:white"></span>';
                botonEliminar+=    '</button>';
                data=value.nombre+"%"+botonEditar+"%"+botonEliminar;
                getData=data.split("%");
                $("#tableSubCategorias").DataTable().row.add(getData).draw().node();
                Swal.fire("\u00A1En hora buena!", "La categoría se ha registrado correctamente", "success");
              } else {
                Swal.fire("\u00A1Error!", "La categoria no se ha podido registrar", "error");
              }
                
              
            }
        });
    }
  });
  function editarCategoria(id, actual, tabla) {
    const elid=id;
    const elactual=actual;
        Swal.fire({
        title: 'Ingresa el nuevo nombre de la categoría',
        input:'text',
        inputValue:elactual,
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Editar',
        inputValidator: (value) => {
            if (!value) {
              return 'Tienes que escribir algo!'
            }
          }
      }).then((value) => {
        // console.log(value);
            var url_path = "modules/module.categorias.php";
            metodo = "POST";
            $.ajax({
                async: true,
                url: url_path,
                type: metodo,
                data: {
                    cmd: 'editarCategoria',
                    categoria: elid,
                    nuevoNombre:value.value
                },
                success: function (result) {
                    // console.log(result);
                    if (result=='1') {
                        $(tabla).DataTable().cell(("#catId" +elid),0).data(value.value);
                        Swal.fire("\u00A1En hora buena!", "La categoría ha sido editada correctamente", "success");
                       
                    }else{
                        Swal.fire("Error", "La categoría no ha podido ser eliminada.", "error");
                    }
                },
                error: function (result) {
                    Swal.showValidationMessage('No se ha podido editar');
                }
            });
        
      })
  }
  function eliminarCategoria(id, tabla) {
    
    Swal.fire({
            title: "\u00BFEst\u00E1s seguro de eliminar la categoría ?",
            text: "\u00A1Una vez eliminada no se podra recuperar!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn btn-primary",
            confirmButtonColor: '#428bca',
            confirmButtonText: "Si, \u00A1elim\u00EDnalo!",
            cancelButtonText: "No, cancelar",
        }).then((result) => {
            if (result.value) {
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
                            $(tabla).DataTable().rows("#catId" + id).remove().draw();
                            Swal.fire("\u00A1En hora buena!", "La categoría ha sido eliminada correctamente", "success");
                        } else {
                            Swal.fire("Error", "La categoría no ha podido ser eliminada.", "error");
                        }
                    }
                });
            }else {
                Swal.fire("Cancelado", "No se han realizado cambios", "error");
            }
        }); //Fin mensaje cancelar usuario




}