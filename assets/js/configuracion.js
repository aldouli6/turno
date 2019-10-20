var map;
var marker;
var geocoder;
var cp;
var colonia;
var ciudad;
var estado;
var pais; 
function initMap(lati,long) {
  var mapProp = {
      center: {lat: lati, lng: long},
      scrollwheel: false,
      zoom: 14,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: false,
      mapTypeControl:false,
  };
  map = new google.maps.Map(document.getElementById('elmapa'), mapProp);
    var LatLng = {lat: parseFloat($('#latitud').val()), lng: parseFloat($('#longitud').val())};
    placeMarker(LatLng);
  google.maps.event.addListener(map, 'click', function(event) {
    placeMarker(event.latLng);
    getaddrees(event);
  });
  geocoder = new google.maps.Geocoder();
  function getaddrees(event){

    $('#latitud').val(event.latLng.lat());
    $('#longitud').val(event.latLng.lng());
    
    geocoder.geocode({
      'latLng': event.latLng
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (results[0]) {
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
          $('#calle').val(results[0].address_components[1].long_name);
          $('#numeroExt').val(results[0].address_components[0].long_name);
          $('#estado').val(estado).trigger('change');
          $('#codigoPostal').val(cp).trigger('change');
          
        }
      }
    });
  }
  function placeMarker(location) {
    console.log(marker)
    if ( typeof marker !== 'undefined' ) {
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
    traeCategorias('0', null);
    cargaDatos($('#establecimientoId').val(), 'nombrecontacto');
    cargaSelectAsuetoExtra();
    $('input').lc_switch("", "");
    switchs_tabs('nombrecontacto');
    switchs_tabs('ubicacion');
    switchs_tabs('generales');
    $('#ubicacion_switch').lcs_off(); 
    var asuetoOficial = 1;
    $("#asuetoOficial1").val(asuetoOficial);
    $('body').delegate('.lcs_checkasueto', 'lcs-on', function () {
      console.log('asueto On');
        document.getElementById('divasueto2').style.display = 'none';
        document.getElementById('divasueto1').style.display = 'block';
        asuetoOficial = 1;
        $("#asuetoOficial1").val(asuetoOficial);
    });
    $('body').delegate('.lcs_checkasueto', 'lcs-off', function () {
      console.log('asueto Off');
        document.getElementById('divasueto2').style.display = 'block';
        document.getElementById('divasueto1').style.display = 'none';
        asuetoOficial = 0;
        $("#asuetoOficial1").val(asuetoOficial);
    });
    var tooltipayuda = 1;
    $("#tooltipayuda1").val(tooltipayuda);
    $('body').delegate('.lcs_checktool', 'lcs-on', function () {
        document.getElementById('divtooltip2').style.display = 'none';
        document.getElementById('divtooltip1').style.display = 'block';
        tooltipayuda = 1;
        $("#tooltipayuda1").val(tooltipayuda);
    });
    $('body').delegate('.lcs_checktool', 'lcs-off', function () {
        document.getElementById('divtooltip2').style.display = 'block';
        document.getElementById('divtooltip1').style.display = 'none';
        tooltipayuda = 0;
        $("#tooltipayuda1").val(tooltipayuda);
    });
    var elnewbody=document.getElementById('elnewbody');
    var _this;
    Dropzone.options.myDropzone = {
      url: 'modules/module.establecimientos.php',
      methor:'POST',
      maxFiles: 1, 
      dictDefaultMessage : 'Arrastra una imágen o dá click aquí!',
      acceptedFiles:'image/*',
      renameFile:'file',
      init: function() {
        _this=this;
        _this.on("sending", function(file, xhr, formData) {
          // Will send the filesize along with the file as POST data.
          formData.append("cmd", "subirImagen");
          formData.append("perfil", $('#establecimientoId').val());
          formData.append("tipo", 'estab');
        });
        _this.on("success", function(file,response) { 
          _this.removeAllFiles(true);
          
          if(response!='0'){
            $('#myDropzone').hide();
            d = new Date();
            response=response+'?'+d.getTime();
            console.log(response); 
            Swal.fire({
              type: 'success',
              title: 'Se ha cambiado la imagen con exito',
              timer: 2000,
          });
            $('.imgProfile').css("background-image", "url("+response+")"); 
          }
          
        });
      },
      transformFile: function(file, done) { 
        var myDropZone = this;
        // Create the image editor overlay
        var editor = document.createElement('div');
        editor.style.position = 'absolute';
        editor.style.left = 0;
        editor.style.right = 0;
        editor.style.top = 0;
        editor.style.bottom = 0;
        editor.style.zIndex = 9999;
        editor.style.backgroundColor = '#000';
        
        elnewbody.appendChild(editor);
        // Create confirm button at the top left of the viewport
        var buttonConfirm = document.createElement('button');
        buttonConfirm.className='btn btn-primary';
        buttonConfirm.style.position = 'absolute';
        buttonConfirm.style.right = '135px';
        buttonConfirm.style.top = '10px';
        buttonConfirm.style.zIndex = 9999;
        buttonConfirm.innerHTML  = '<span class="glyphicon glyphicon-open"></span> Subir Imágen';
        var buttonCancel = document.createElement('button');
        buttonCancel.className='btn btn-danger';
        buttonCancel.style.position = 'absolute';
        buttonCancel.style.right = '10px';
        buttonCancel.style.top = '10px';
        buttonCancel.style.zIndex = 9999;
        buttonCancel.innerHTML  = '<span class="glyphicon glyphicon-remove"></span> Cancelar';
        editor.appendChild(buttonConfirm);
        editor.appendChild(buttonCancel);
        // Create an image node for Cropper.js
        var image = new Image();
        image.src = URL.createObjectURL(file);
        editor.appendChild(image);
        
        // Create Cropper.js
        var cropper = new Cropper(image, { 
            aspectRatio: 1 ,
          }
          );
        buttonCancel.addEventListener('click', function() {
          _this.removeAllFiles(true);
          elnewbody.removeChild(editor);
        });
        buttonConfirm.addEventListener('click', function() {
          // Get the canvas with image data from Cropper.js
          var canvas = cropper.getCroppedCanvas({
            width: 256,
            height: 256
          });
          // Turn the canvas into a Blob (file object without a name)
          canvas.toBlob(function(blob) {
            // Create a new Dropzone file thumbnail
            myDropZone.createThumbnail(
              blob,
              myDropZone.options.thumbnailWidth,
              myDropZone.options.thumbnailHeight,
              myDropZone.options.thumbnailMethod,
              false, 
              function(dataURL) {
                
                // Update the Dropzone file thumbnail
                myDropZone.emit('thumbnail', file, dataURL);
                // Return the file to Dropzone
                done(blob);
            });
          });
          // Remove the editor from the view
          elnewbody.removeChild(editor);
        });
      }
     };
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
$('#formubicacion').validate({
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
              $('#ubicacion_switch').lcs_off(); 
              if (response!='0') {
                  Swal.fire("\u00A1En hora buena!", "Los datos han sido guardado correctamente", "success");
              }else{
                  Swal.fire("Error", "Los datos no han podido ser guardos.", "error");
              }
          }
      });
  }
});
$('#formgenerales').validate({
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
              //var obj = JSON.parse(response);
              $('#generales_switch').lcs_off(); 
              if (response!='0') {
                  Swal.fire("\u00A1En hora buena!", "Los datos han sido guardado correctamente", "success");
              }else{
                  Swal.fire("Error", "Los datos no han podido ser guardos.", "error");
              }
          }
      });
  }
});
function cargaSelectAsuetoExtra() {
  var d = new Date();
  var months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oc", "Nov", "Dic"];
  var html='';
  for (let i = 0; i < 730; i++) {
    d.setDate(d.getDate() + 1);
    fechaString=("0" + d.getDate()).slice(-2)+' '+months[d.getMonth()]+' '+d.getFullYear();
    fechaValue=d.getFullYear()+'-'+("0" + (d.getMonth()+1)).slice(-2)+'-'+("0" + d.getDate()).slice(-2)
    html+= '<option value="'+fechaValue+'">'+fechaString+'</option>';
  }
  $("#diasFeriadosExtra").html(html); 
}
function switchs_tabs(tab) {
    $('body').delegate('#switch_'+tab, 'lcs-on', function () {
        $('#form'+tab).find('*').removeClass('disabled');
        $('#form'+tab).find('*').removeAttr('disabled');
        if (tab=='ubicacion') {
          $('#elmapa').show();
        }
        if (tab=='generales') {
          $('#asuetoOficialcheck').show();
          $('#tooltipayudacheck').show();
        }
    });
    $('body').delegate('#switch_'+tab, 'lcs-off', function () {
        $('#form'+tab).find('*').addClass('disabled')
        $('#form'+tab).find('*').attr('disabled',true)
        if (tab=='ubicacion') {
          $('#elmapa').hide();
        }
        if (tab=='generales') {
          $('#asuetoOficialcheck').hide();
          $('#tooltipayudacheck').hide();
        }
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
            var obj = JSON.parse(response);
            console.log(obj);
            $('#nombre').val(obj.nombre);  
            $('#descripcion').val(obj.descripcion); 
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
                 $('#ubicacion_switch').lcs_off(); 
                getLocation();
              });   
            });
            $('#longitud').val(obj.longitud);
            $('#latitud').val(obj.latitud);
            var diasExtra = JSON.parse(obj.diasAsuetoExtra);
            $('#diasFeriadosExtra').val(diasExtra).trigger('change'); 
            $('#stepping').val(obj.stepping).trigger('change'); 
            (obj.diasAsuetoOficiales =='1')?$('#asuetoOficial').lcs_on():$('#asuetoOficial').lcs_off();
            (obj.tooltipayuda =='1')?$('#tooltipayuda').lcs_on():$('#tooltipayuda').lcs_off();
            $('#generales_switch').lcs_off(); 
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