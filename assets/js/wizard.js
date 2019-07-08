var map;
var marker;
var geocoder;
var cp;
var colonia;
var ciudad;
var estado;
var pais;
function initMap(lati,long) {
  // Create a map object and specify the DOM element for display.
  
  var mapProp = {
      center: {lat: lati, lng: long},
      scrollwheel: false,
      zoom: 14,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: false,
      mapTypeControl:false,
      
  };
  map = new google.maps.Map(document.getElementById('elmapa'), mapProp);
  google.maps.event.addListener(map, 'click', function(event) {
    placeMarker(event.latLng);
    getaddrees(event);
  });
  geocoder = new google.maps.Geocoder();
  function getaddrees(event){
    console.log(event.latLng.lat(),event.latLng.lng());

    $('#regEstabLatitud').val(event.latLng.lat());
    $('#regEstabLongitud').val(event.latLng.lng());
    
    geocoder.geocode({
      'latLng': event.latLng
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
          console.log(results);
          if(results[0].address_components.length == 7){
            cp=results[0].address_components[6].long_name;
            colonia=results[0].address_components[2].long_name;
            ciudad = results[0].address_components[3].long_name;
            estado= results[0].address_components[4].long_name;
            pais= results[0].address_components[5].long_name;
          }else{
            cp=results[0].address_components[7].long_name;
            pais= results[0].address_components[6].long_name;
            estado= results[0].address_components[5].long_name;
            ciudad = results[0].address_components[3].long_name;
            colonia=results[0].address_components[2].long_name;
          }          
          $('#regEstabPais').val(pais);
          $('#regEstabCalle').val(results[0].address_components[1].long_name);
          $('#regEstabNumExt').val(results[0].address_components[0].long_name);
          $('#regEstabEstado').val(estado).trigger('change');
          $('#regEstabCP').val(cp).trigger('change');
          
        }
      }
    });
  }
  function placeMarker(location) {
    if ( marker ) {
      marker.setPosition(location);
    } else {
        marker = new google.maps.Marker({
        position: location, 
        map: map,
        animation: google.maps.Animation.DROP,
        draggable:true,
      });
    }
    google.maps.event.addListener(marker, 'dragend', function (event) {
      getaddrees(event);
  
    });
  }
}


function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(setPosition);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}
function setPosition(position) {
  initMap(position.coords.latitude, position.coords.longitude);
}
$(document).ready(function() {
  traeCategorias('0');
  traerEstados();
});


function traeCategorias(cat){
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
        cathtml ="<option value='0'> Seleccione una Categoría</option>";
        $.each(obj, function( key, value ) {
          cathtml += "<option value='"+value.categoriaId+"'>"+value.nombre+"</option>";
        });
        if (cat!='0') {
            $('#regEstabSubcategoria').removeAttr("disabled");
            $('#regEstabSubcategoria').html(cathtml);
            $("#regEstabSubcategoria").select2("val", 0);
        }else{
            $("#regEstabCategoria").html(cathtml);
        }        
    }
  });
}
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
function traerEstados(){
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
        estadosHTML ="<option value='0'>-- Seleccione un estado --</option>";
        $.each(estados, function( key, value ) {
          estadosHTML+= "<option value='"+value.nombre+"'>"+value.nombre+"</option>";
        });
        $("#regEstabEstado").html(estadosHTML);
    }
  });
}
$("#regEstabCP").change(function () {
  $.ajax({ //Se carga la lista de los tipos de servicio en la lista desplegable
      url: "https://api-codigos-postales.herokuapp.com/v2/codigo_postal/"+$("#regEstabCP").val(),
      type: "GET",
      success: function (response) {
        var colonias=[];
        $.each( response.colonias, function( key, value ) {
          colonias[key]={"nombre":value};
        });  
        ciudad=response.municipio;
        estado=response.estado;
        colonias=sortJSON(colonias,'nombre','asc');
        coloniasHTML ="<option value='0'>-- Seleccione una colonia--</option>";
        $.each(colonias, function( key, value ) {
          coloniasHTML+= "<option value='"+value.nombre+"'>"+value.nombre+"</option>";
        });
        $('#regEstabColonia').removeAttr("disabled");
        $("#regEstabColonia").html(coloniasHTML);          
        $('#regEstabColonia').select2("val", 0);  
        if(colonia)
          $('#regEstabColonia').select2("val",colonia); 
        if(estado)
          $('#refEstabEstado').select2("val",estado); 
        if(ciudad)
          $('#regEstabCiudad').select2("val","Querétaro"); 
      }
  });
}); 
$("#regEstabEstado").change(function () {
  //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
  $("#regEstabCiudad").select2("val", 0);
  $('#regEstabCiudad').empty();
  $.ajax({ //Se carga la lista de los tipos de servicio en la lista desplegable
      url: "https://public.opendatasoft.com/api/records/1.0/search/?dataset=ciudades-de-mexico&facet=name_1&facet=name_2&refine.name_1="+$("#regEstabEstado").val(),
      type: "GET",
      success: function (response) {
        var ciudades=[];
        $.each( response.records, function( key, value ) {
          ciudades[key]={"nombre":value.fields.name_2};
        });   
        ciudades=sortJSON(ciudades,'nombre','asc');
        ciudadesHTML ="<option value='0'>-- Seleccione una ciudad--</option>";
        $.each(ciudades, function( key, value ) {
          ciudadesHTML+= "<option value='"+value.nombre+"'>"+value.nombre+"</option>";
        });
        $('#regEstabCiudad').removeAttr("disabled");
        $("#regEstabCiudad").html(ciudadesHTML);  
        $('#regEstabCiudad').select2("val", 0);  
        if(ciudad){;
          $('#regEstabCiudad').select2("val","Querétaro"); 
        }
            
      }
  });
}); 
$("#regEstabCategoria").change(function () {
  //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
  $("#regEstabSubcategoria").select2("val", 0);
  $('#regEstabSubcategoria').empty();
  traeCategorias($("#regEstabCategoria").val());
  

});

$('#formRegistroEstablecimiento').validate({
  /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
  rules: {
    regEstabCategoria: {
      selectcheck: true
    },
    regEstabSubcategoria: {
      selectcheck: true
    },
    regEstabColonia: {
      selectcheck: true
    },
    regEstabEstado: {
      selectcheck: true
    },
    regEstabCiudad: {
      selectcheck: true
    }
  },
  submitHandler: function (form) {
      var formulario = $(form).serialize() + "&cmd=registrarEstablecimiento";
      console.log(formulario);
      $.ajax({
          url: "modules/module.establecimientos.php",
          type: "POST",
          data: formulario,
          success: function (response) {
            console.log(response);
            
            if(response!="0"){
              Swal.fire({ 
                  title: "\u00A1En hora buena!",
                  text: "El establecimiento se ha registrado correctamente",
                  type: "success" 
                }).then((result) => {
                  location.href="usuarios.php"
                });
              
            }else{
              Swal.fire("\u00A1Error!", "El establecimiento no se ha podido registrar", "error");
              $("#formRegistroEstablecimiento").get(0).reset();
              $("#regEstabCategoria").select2("val", 0);
              $("#regEstabSubcategoria").select2("val", 0);
              $("#regEstabColonia").select2("val", 0);
              $("#regEstabEstado").select2("val", 0);
              $("#regEstabCiudad").select2("val", 0);
            }
          }
      });
  }
});
jQuery.validator.addMethod('selectcheck', function (value) { //Se crea la regla para la lista desplegable de Tipo de Usuario
  return (value != '0');
}, "Este campo es obligatorio.");