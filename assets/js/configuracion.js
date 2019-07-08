$(document).ready(function() {
    traeCategorias('0', null);
    cargaDatos($('#establecimientoId').val(), 'nombrecontacto');
    $('input').lc_switch("", "");
    switchs_tabs('nombrecontacto');
    switchs_tabs('ubicacion');
    alert('askhvb');
});
$('#formnombrecontacto').validate({
    submitHandler: function (form) {
        
        var formulario = $(form).serialize();
        formulario+='&establecimientoId='+$('#establecimientoId').val();
        console.log(formulario);
        $.ajax({
            url: "modules/module.establecimientos.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                console.log(response);
                var obj = JSON.parse(response);
                $('#nombrecontacto_switch').lcs_off(); 
                if (response!='0') {
                    Swal.fire("\u00A1En hora buena!", "Los datos han sido guardado correctamente", "success");
                }else{
                    Swal.fire("Error", "Los datos no han podido ser guardos.", "error");
                }
            }
        });
    }
});
function switchs_tabs(tab) {
    $('body').delegate('#switch_'+tab, 'lcs-on', function () {
        console.log(1);
        $('#form'+tab).find('*').removeClass('disabled');
        $('#form'+tab).find('*').removeAttr('disabled');
    });
    $('body').delegate('#switch_'+tab, 'lcs-off', function () {
        console.log(0);
        $('#form'+tab).find('*').addClass('disabled')
        $('#form'+tab).find('*').attr('disabled',true)
    });
}
function cargaDatos(establecimiento) {
    $.ajax({
        async: true,
        url: "modules/module.establecimientos.php",
        type: "POST",
        data: {
          cmd: "getEstablecimiento",
          establecimiento:establecimiento
        },
        success: function(response){
            console.log(response);
            var obj = JSON.parse(response);
            $('#nombre').val(obj.nombre);  
            $('#emailEstablecimiento').val(obj.emailEstablecimiento); 
            $('#telefonoEstablecimiento').val(obj.telefonoEstablecimiento);  
            $('#categoria').select2("val",obj.categoriaId);
            traeCategorias( obj.categoriaId,  ()=> {
                $('#subcategoria').select2("val",obj.subcategoriaId);
                $('#nombrecontacto_switch').lcs_off(); 
            }); 
            $('#calle').val(obj.calle);  
            $('#numeroExt').val(obj.numeroExt);
            $('#numeroInt').val(obj.numeroInt);
            $('#codigoPostal').val(obj.codigoPostal);
            changeCP(()=>{ $('#colonia').select2("val",obj.colonia);});
            traerEstados(()=>{
              $('#estado').select2("val",obj.estado); 
              changeEstado( ()=>{
                $('#ciudad').select2("val",obj.ciudad);
                // $('#ubicacion_switch').lcs_off(); 
              });   
            });
            $('#longitud').val(obj.longitud);
            $('#latitud').val(obj.latitud);

        }
      });
}
function traerEstados(callback){
    $.ajax({
      async: true,
      url: "https://public.opendatasoft.com/api/records/1.0/search/?dataset=estados-de-mexico&rows=32&facet=codigo&facet=estado",
      type: "GET",
      success: function(response){
          var estados=[];
          $.each( response.records, function( key, value ) {
            estados[key]={"nombre":value.fields.estado};
          });
          estados=sortJSON(estados,'nombre','asc');
          estadosHTML ="<option ></option>";
          $.each(estados, function( key, value ) {
            estadosHTML+= "<option value='"+value.nombre+"'>"+value.nombre+"</option>";
          });
          $("#estado").html(estadosHTML);
          typeof callback === 'function' && callback();
      }
    });
  }
  
  $("#estado").change(function () {
    changeEstado(null);
  }); 
  function changeEstado(callback) {
    $("#ciudad").select2("val", 0);
    $('#ciudad').empty();
    $.ajax({ //Se carga la lista de los tipos de servicio en la lista desplegable
        url: "https://public.opendatasoft.com/api/records/1.0/search/?dataset=ciudades-de-mexico&facet=name_1&facet=name_2&refine.name_1="+$("#estado").val(),
        type: "GET",
        success: function (response) {
            console.log(response);
          var ciudades=[];
          $.each( response.records, function( key, value ) {
            ciudades[key]={"nombre":value.fields.name_2};
          });   
          ciudades=sortJSON(ciudades,'nombre','asc');
          ciudadesHTML ="<option></option>";
          $.each(ciudades, function( key, value ) {
            ciudadesHTML+= "<option value='"+value.nombre+"'>"+value.nombre+"</option>";
          });
          $('#ciudad').removeAttr("disabled");
          $("#ciudad").html(ciudadesHTML);  
          $('#ciudad').select2("val", 0);  
          if(ciudad){;
            $('#ciudad').select2("val","Querétaro"); 
          }
          typeof callback === 'function' && callback();   
        }
    });
  }
$("#codigoPostal").change(function () {
    changeCP(null);
  }); 
  function changeCP(callback) {
    $.ajax({ //Se carga la lista de los tipos de servicio en la lista desplegable
      url: "https://api-codigos-postales.herokuapp.com/v2/codigo_postal/"+$("#codigoPostal").val(),
      type: "GET",
      success: function (response) {
          console.log(response);
        var colonias=[];
        $.each( response.colonias, function( key, value ) {
          colonias[key]={"nombre":value};
        });  
        ciudad=response.municipio;
        estado=response.estado;
        colonias=sortJSON(colonias,'nombre','asc');
        coloniasHTML ="<option ></option>";
        $.each(colonias, function( key, value ) {
          coloniasHTML+= "<option value='"+value.nombre+"'>"+value.nombre+"</option>";
        });
        $('#colonia').removeAttr("disabled");
        $("#colonia").html(coloniasHTML);          
        $('#colonia').select2("val", 0);  
        if(colonia)
          $('#colonia').select2("val",colonia); 
        if(estado)
          $('#estado').select2("val",estado); 
        if(ciudad)
          $('#ciudad').select2("val",ciudad); 
        typeof callback === 'function' && callback();   
      }
  });
  }
function traeCategorias(cat, callback){
    $.ajax({
      async: true,
      url: "modules/module.categorias.php",
      type: "POST",
      data: {
        cmd: "listaCategorias",
        categoria:cat
      },
      success: function(response){
          var obj = JSON.parse(response);
          var cathtml;
          cathtml += "<option></option>";
          $.each(obj, function( key, value ) {
            cathtml += "<option value='"+value.categoriaId+"'>"+value.nombre+"</option>";
          });
          if (cat!='0') {
              $('#subcategoria').removeAttr("disabled");
              $('#subcategoria').parent().parent().removeAttr("disabled");
              $('#subcategoria').html(cathtml);
              $("#subcategoria").select2("val", 0);
          }else{
              $("#categoria").html(cathtml);
          } 
          typeof callback === 'function' && callback();
      }
    });

    
  }
  $("#categoria").change(function () {
    $("#subcategoria").select2("val", 0);
    $('#subcategoria').empty();
    traeCategorias($("#categoria").val());
  
  });
  function sortJSON(data, key, orden) {
    return data.sort(function (a, b) {
        var x = a[key],
        y = b[key];
  
        if (orden === 'asc') {
            return ((x < y) ? -1 : ((x > y) ? 1 : 0));
        }
  
        if (orden === 'desc') {
            return ((x > y) ? -1 : ((x < y) ? 1 : 0));
        }
    });
  }