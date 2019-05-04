Date.prototype.getWeek = function(){
    var d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
    var dayNum = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dayNum);
    var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
    return Math.ceil((((d - yearStart) / 86400000) + 1)/7)
  };
  Date.prototype.semanasmes = function() {
    var year= this.getFullYear();
    var mes = this.getMonth();
    var primerdia = new Date(year, mes, 0);
    var ultimodia = new Date(year, mes+1, 0);
    var used         = primerdia.getDay() + ultimodia.getDate();
    return Math.ceil( used / 7);
  }
  Date.prototype.yyyymmdd = function() {     
    var yyyy = this.getFullYear().toString();                                    
    var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based         
    var dd  = this.getDate().toString();  
    return yyyy + '' + (mm[1]?mm:"0"+mm[0]) + '' + (dd[1]?dd:"0"+dd[0]);
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
    creaCalendario(new Date('2019-05-04'));
});
// $( "#sesionIdSelect" ).change(function() {
//     getRecursosFromSesion($('#establecimientoId').val(), $(this).val());
//   });

function creaCalendario(hoy) {
    
    var mes = hoy.getMonth(); 
    var dias = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    var meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    var mesNombre = meses[mes];
    var year = hoy.getFullYear();
    var firstDayOfMonth = new Date(year, mes, 1);
    var firstDayOfWeek=funfirstDayOfWeek(firstDayOfMonth, 0);
    console.log(firstDayOfMonth);
    console.log(firstDayOfWeek);
    $('#nombreMes').append('<div  class="box ratio20_1"><div class="dia"><i class="fa fa-chevron-left" aria-hidden="true"></i> '+mesNombre+' <i class="fa fa-chevron-right" aria-hidden="true"></i></div></div>');
    $('#year').append('<div  class="box ratio20_1"><div class="dia">'+year+'</div></div>');
    $('#nombreDiasSemana').append('<div  class="box ratio4_1"><div class="dia">Semana</div></div>');
    for (let i = 0; i < dias.length; i++) {
        var html='<div  class="box ratio4_1">';
            html+='<div class="dia">'+dias[i]+'</div>';
            html+='</div>';
            $('#nombreDiasSemana').append(html);
    }
    var fecha = firstDayOfWeek;
    for (let j = 0; j < 6; j++) {
        var week=fecha.getWeek();
        var html='<div id="semana'+week+'" style="display:flex" class=" justify-content-md-center"></div>';
        $('#fechas').append(html);    
        var html='<div id="week'+week+'" class="box ratio16_9"><div class="dia">'+week+' </div> ';
        $('#semana'+week).append(html); 
        for (let i = 0; i <= 6; i++) {
            var dayOfWeek= fecha.getDay();
            var html='';
            html+='<div id="dia'+fecha.yyyymmdd()+'" class="box ratio16_9"><div class="dia">'+fecha.getDate()+' </div> ';
            $('#semana'+week).append(html); 
            console.info(fecha)
            fecha.setDate(fecha.getDate() + 1);
        }
        //fecha.setDate(fecha.getDate() + 1);
    }
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