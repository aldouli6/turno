

$(document).ready(function() {
  traeCategorias();
});


function traeCategorias(){
  $.ajax({
    async: true,
    url: "modules/module.categorias.php",
    type: "POST",
    data: {
      cmd: "listaCategorias",
    },
    success: function(response){
        $("#regEstabCategoria").html(response);
      
    }
  });
}
$("#regEstabCategoria").change(function () {
  //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
  $("#regEstabSubcategoria").select2("val", 0);
  $('#regEstabSubcategoria').empty();
  $.ajax({ //Se carga la lista de los tipos de servicio en la lista desplegable
      url: "modules/module.categorias.php",
      type: "POST",
      data: {
          cmd: "listaSubcategorias",
          categoria: $("#regEstabCategoria").val()
      },
      success: function (response) {
        if(response!='0'){
          $('#regEstabSubcategoria').removeAttr("disabled");
          $('#regEstabSubcategoria').html(response);
          $("#regEstabSubcategoria").select2("val", 0);
        }
          
      }
  });

});