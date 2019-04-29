Date.prototype.getWeek = function(){
    var d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
    var dayNum = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dayNum);
    var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
    return Math.ceil((((d - yearStart) / 86400000) + 1)/7)
  };
function funfirstDayOfWeek(dateObject, firstDayOfWeekIndex) {

    const dayOfWeek = dateObject.getDay(),
        firstDayOfWeek = new Date(dateObject),
        diff = dayOfWeek >= firstDayOfWeekIndex ?
            dayOfWeek - firstDayOfWeekIndex :
            6 - dayOfWeek

    firstDayOfWeek.setDate(dateObject.getDate() - diff)
    firstDayOfWeek.setHours(0,0,0,0)

    return firstDayOfWeek
}
$(document).ready(function() {
   
    //cargaSelect($('#establecimientoId').val(),'0');
    //getRecursosFromSesion($('#establecimientoId').val(), '0');
    creaCalendario(new Date());
});
// $( "#sesionIdSelect" ).change(function() {
//     getRecursosFromSesion($('#establecimientoId').val(), $(this).val());
//   });

function creaCalendario(hoy) {
    
    var mes = hoy.getMonth();
    var meses = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var mesNombre = meses[mes];
    var year = hoy.getFullYear();
    var firstDayOfMonth = new Date(year, mes, 1);
    var dayOfWeek= firstDayOfMonth.getDay();
    var week=firstDayOfMonth.getWeek();
    var firstDayOfWeek=funfirstDayOfWeek(firstDayOfMonth, 0);
    console.log(firstDayOfMonth);
    console.log(firstDayOfWeek);
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