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
   
    creaCalendario(new Date());
    
});
function listerners(){
    $( ".icon" ).click(function() {
        var target= $(this).attr('target');
        console.log(target);
        creaCalendario(new Date(target))
    });
    $( ".enmes" ).click(function() {
        var aidi = $(this).attr('id').substring(3);
        var year=aidi.substring(0,4);
        var mes=aidi.substring(4,6);
        var dia=aidi.substring(6,8);
        cargarModal(dia,mes,year, $('#establecimientoId').val());
    });
}
function cargarModal(dia,mes,year, establecimiento) {
    var fecha=new Date(year, mes-1, dia);
    $('#eldia').text(fechaNombre(fecha));
    getTipoSesiones(establecimiento, '0');
    getRecursosHoy(fecha,dia,mes,year, establecimiento);
    $( "#recursoId" ).change(function() {
        getTipoSesiones(establecimiento, $(this).val());
      });
}
function getRecursosHoy(fecha,dia,mes,year, establecimiento) {
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $('#recursoId').select2("val", 0);
    $("#recursoId").empty();
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRecursosHoy",
            establecimiento:establecimiento,
            dia:fecha.getDay(),
            date: year+'-'+mes+'-'+dia
        },
        success: function (response) {
            var obj = JSON.parse(response);
            var html='<option ></option>';
            $.each(obj, function( key, value ) {
                html+= '<option value="'+value.recursoId+'">'+value.nombre+'</option>';
            });
            $("#recursoId").html(html);
        }
    });
}
function getTipoSesiones(establecimiento, recurso) {
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $('#tipoSesionId').select2("val", 0);
    $("#tipoSesionId").empty();
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRelTipoSesiones",
            establecimiento:establecimiento,
            recurso: recurso
        },
        success: function (response) {
            var obj = JSON.parse(response);
            var html='<option ></option>';
            $.each(obj, function( key, value ) {
                html+= '<option value="'+value.tipoSesionId+'">'+value.nombre+'</option>';
            });
            $("#tipoSesionId").html(html);
        }
    });
}
function fechaNombre(fechaS) {
    var dias = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    var meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    return dias[fechaS.getDay()] +', '+ fechaS.getDate()+' de '+ meses[fechaS.getMonth()]+' del '+ fechaS.getFullYear();
}
function creaCalendario(hoy) {
    
    var mes = hoy.getMonth(); 
    var dias = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    var meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    var mesNombre = meses[mes];
    var year = hoy.getFullYear();
    var firstDayOfMonth = new Date(year, mes, 1);
    var firstDayOfWeek=funfirstDayOfWeek(firstDayOfMonth, 0);
    var mesAntes = new Date(year, mes-1, 1);
    var mesDespues =  new Date(year, mes+1, 1);
    var yearAntes = new Date(year-1, mes, 1);
    var yearDespues = new Date(year+1, mes, 1);
    $('#nombreMes').empty();
    $('#year').empty();
    $('#nombreDiasSemana').empty();
    $('#fechas').empty();
    $('#nombreMes').append('<div  class="box ratio20_1"><div class=" celda mes"><i target="'+mesAntes+'" class="icon fa fa-chevron-left" aria-hidden="true"></i><div class="m-r-50 m-l-50"> '+mesNombre+'</div> <i target="'+mesDespues+'" class="icon fa fa-chevron-right" aria-hidden="true"></i></div></div>');
    $('#year').append('<div  class="box ratio20_1"><div class="celda year"><i target="'+yearAntes+'"  class="icon fa fa-chevron-left" aria-hidden="true"></i><div class="m-r-50 m-l-50">'+year+'</div><i target="'+yearDespues+'"  class="icon fa fa-chevron-right" aria-hidden="true"></i></div></div>');
    //$('#nombreDiasSemana').append('<div  class="box ratio4_1"><div class="celda titulos">Semana</div></div>');
    for (let i = 0; i < dias.length; i++) {
        var html='<div  class="box ratio4_1">';
            html+='<div class="celda titulos">'+dias[i]+'</div>';
            html+='</div>';
            $('#nombreDiasSemana').append(html);
    }
    var fecha = firstDayOfWeek;
    for (let j = 0; j < 5; j++) {
         var week=fecha.getWeek();
        var html='<div id="semana'+week+'" style="display:flex" class=" justify-content-md-center"></div>';
        $('#fechas').append(html);    
        // var html='<div id="week'+week+'" class="box ratio16_9"><div class="celda semana">'+week+' </div> ';
        // $('#semana'+week).append(html); 
        for (let i = 0; i <= 6; i++) {
            var dayOfWeek= fecha.getDay();
            var html='';
            var enmes=(fecha.getMonth()==mes)?'enmes':'noenmes'
            var modal=(fecha.getMonth()==mes)?'data-toggle="modal" data-target="#formturno"':'';
            html+='<div   class="box ratio16_9"><div id="dia'+fecha.yyyymmdd()+'" '+modal+' class="'+enmes+' celda dia"><div class="numero" >'+fecha.getDate()+'</div> </div> ';
            $('#semana'+week).append(html); 
            fecha.setDate(fecha.getDate() + 1);
        }
        //fecha.setDate(fecha.getDate() + 1);
    }
    listerners();
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