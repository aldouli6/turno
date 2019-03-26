var count=1;
var calles = [];
var subfraccionamientoId = $('#SubfraccionamientoRelacion').val();

$(document).ready(function() {
    $('#nombreCalle').focus();
	referenceTable();
});

$.ajax({
  async: true,
  url: "modules/module.panel.php",
  type: "POST",
  data: {
    cmd: "validateSubfraccionamientoData",
    subfraccionamientoId: subfraccionamientoId
  },
  success: function(response){
    //console.log(response);
    if (response != "0"){
      console.log("ya hay datos");
      location.href="usuarios.php";
    } else {
      $(".se-pre-con").fadeOut("slow");
    }
  }
});

$('#rootwizard').bootstrapWizard({
    onNext: function(tab, navigation, index) {
        if(index==1){
          if(calles.length<1){
            swal("¡Atencion!", "Agrega tus calles antes de avanzar", "warning");
            //alert('Agrega tus calles antes de avanzar');
            return false;
          }
        }
    }, onTabShow: function(tab, navigation, index) {
        var total = navigation.find('li').length;
        if (index == 1){
          google.maps.event.trigger(map, 'resize');
          var center = new google.maps.LatLng(21.15064600684834, -101.67477440088986);
          // using global variable:
          map.panTo(center);
          $('#tabCalles').removeClass('btn-primary').addClass('');
          $('#tabUbicacion').addClass('btn-primary');
        } else if(index == 0){
          $('#tabUbicacion').removeClass('btn-primary').addClass('');
          $('#tabCalles').addClass('btn-primary');
        }
        if (index < (total - 1)){
          $('#rootwizard .finish').hide();
          $('#rootwizard .next').show();
        } else {
          if(total == 0){
            $('#rootwizard .finish').hide();
            $('#rootwizard .next').show();
          } else {
            $('#rootwizard .finish').show();
            $('#rootwizard .next').hide();
          }
        }
    }
});

function referenceTable() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#callesDataTable').dataTable({
        "language": {
        	  "pageLength": 5,
            "sProcessing": "Procesando...",
            "sLoadingRecords": "Cargando...",
            "sLengthMenu": "Mostrar _MENU_",
            "sInfoFiltered": "(Datos filtrados de un total de _MAX_)",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfoEmpty": "Mostrando 0-0 de un total de 0",
            "sInfo": "Mostrando _START_-_END_ de un total de _TOTAL_",
            "sSearch": "Buscar:",
            "sZeroRecords": "No se encontraron resultados",
            "oPaginate": {
                "sNext": '<span class=" pg-arrow_right"></span>',
                "sPrevious": '<span class=" pg-arrow_left"></span>'
            },
        },"columnDefs": [
          {"targets": 1, "orderable": false},
          {"width":"10%", "targets":0},
          {"width":"65%", "targets":1},
          {"targets":0, "visible":false}
          ]
    });
}

$('#añadirCalle').click(function(){
    agregaCalle(1);
});

function borrarCalle(id){
	$("#callesDataTable").DataTable().rows().eq(0).each(function(index) {
                   var rowCalle = $("#callesDataTable").DataTable().cell(index, 0).data();
                   if (rowCalle == id) {
                             $("#callesDataTable").DataTable().row(index).remove().draw();
                             calles.splice(index, 1);
                   }
     });
}

document.getElementById('nombreCalle').addEventListener("keydown",function(e){
    if(e.keyCode == 13){
       agregaCalle(2);
    } 
});

function agregaCalle(from){
    var calle = $('#nombreCalle').val();
    var calle_clean = calle.replace(/(<([^>]+)>)/ig,"");
    if (calle_clean==""){
        if(from==1){
            swal("¡Atencion!", "Escribe el nombre de una calle", "error");
            $('#nombreCalle').focus();
        }
    } else{
         var botonEliminar = "<button type='button' class='btn btn-danger btn-sm' style='float: right; background: gray; border: gray;' onclick='borrarCalle(\""+count+"\");'>"+
            "<i class='fa fa-trash-o' aria-hidden='true' style='color: white;'></i></button>";
         var data = [count, calle_clean, botonEliminar];
         $("#callesDataTable").DataTable().row.add(data).draw();
         $('#nombreCalle').val("");
        calles.push(calle_clean);
         count++;
        $('#nombreCalle').focus();
    }
}


$('#rootwizard .finish').click(function() {
    var subfraccionamientoRelacion = $('#SubfraccionamientoRelacion').val();
    if(coordenadas=="" || centerpoint==""){
        swal("¡Atencion!", "Ubica y delimita en el mapa tu fraccionamiento para finalizar", "warning");
    } else {
        if (Object.keys(datosMapa.features).length > 2){
            swal("Atencion!", "Solo debes seleccionar un area en el mapa y marcar un punto centro", "warning");
        } else{
            $.ajax({ //Se carga la lista de los municipios en la lista desplegable
                async: true,
                url: "modules/module.wizard.php",
                type: "POST",
                data: {
                    cmd: "actualizaDatos",
                    coordenadas: coordenadas,
                    calles: calles,
                    idSubfraccionamiento: subfraccionamientoRelacion,
                    centerPoint: centerpoint
                },
                success: function(response) {
                  if (response == 0){
                    swal("Error!", "Ha ocurrido un error, intente mas tarde", "error");
                  } else {
                    swal("Completado!", "Todos los datos se han guardado", "success");
                    swal({
                      title: "Completado",
                      text: "Todos los datos se han guardado",
                      type: "success",
                      showCancelButton: false,
                      confirmButtonClass: "btn-success",
                      confirmButtonText: "Continuar",
                      closeOnConfirm: false
                    },
                    function(){
                      location.href='usuarios.php';
                    });
                  }
                }
            });
        }
    }
});