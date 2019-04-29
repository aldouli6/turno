$(document).ready(function() {
   
    //cargaSelect($('#establecimientoId').val(),'0');
    //getRecursosFromSesion($('#establecimientoId').val(), '0');
    creaCalendario();
});
// $( "#sesionIdSelect" ).change(function() {
//     getRecursosFromSesion($('#establecimientoId').val(), $(this).val());
//   });

function creaCalendario(params) {
    
}


















function getRecursosFromSesion(establecimiento, tipoSesion) {
    
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRecursosFromTipoSesion",
            establecimiento:establecimiento,
            tipoSesion:tipoSesion
        },
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            var html='';
            var html2='';
            $.each(obj, function( key, value ) {
                html+='<li id="rId'+value.recursoId+'" class=" full-width">';
                html+='<a href="#recurso'+value.recursoId+'" data-toggle="tab">';
                html+=value.nombre+'</a></li>';
                html2+='<div class="tab-pane" id="recurso'+value.recursoId+'">Contenido por el momento'+value.nombre+'</div>';

            });
            $("#vtabs").html(html);
            $("#vcontent").html(html2);
        }
    });
}
// function getRecursosTurno(id) {
//     console.log(id);
//     var url_request = "modules/module.recurso.php";
//     var method = "POST";
//     $.ajax({
//         async: true,
//         url: url_request,
//         type: method,
//         data: {
//             cmd: "getRecursos",
//             establecimiento:id
//         },
//         success: function (response) {
//             var obj = JSON.parse(response);
//             var html='';
//             var html2='';
//             $.each(obj, function( key, value ) {
//                 html+='<li id="rId'+value.recursoId+'" class=" full-width">';
//                 html+='<a href="#recurso'+value.recursoId+'" data-toggle="tab">';
//                 html+=value.nombre+'</a></li>';
//                 html2+='<div class="tab-pane" id="recurso'+value.recursoId+'">Contenido por el momento'+value.nombre+'</div>';

//             });
//             $("#vtabs").html(html);
//             $("#vcontent").html(html2);
//         }
//     });
// }