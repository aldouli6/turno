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
var steps=0;
$(document).ready(function() {
    creaCalendario(new Date());
    steps=$('#establecimientoStepping').val();
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
    getRecursosHoy(fecha,dia,mes,year, establecimiento, 0);
    cargaHorasDia(fecha);
    $( "#recursoId" ).change(function() {
        getTipoSesiones(establecimiento, $(this).val());
        cargaHorariosRecursoHoy(fecha,dia,mes,year,establecimiento, $(this).val());
    });
    $( "#tipoSesionId" ).change(function() {
        getDatosTipoSesion($(this).val());
    });
}
function getDatosTipoSesion(tiposesion) {
    var url_request = "modules/module.tipoSesion.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getTipoSesion",
            tipoSesion:tiposesion
        },
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(response);
            var duracionhoras = obj.duracion.substring(0,2);
            var duracionminutos = obj.duracion.substring(3,5);
            duracionminutos = parseInt(duracionminutos)+(parseInt(duracionhoras)*60);
            var anteshoras = obj.tiempoEspera.substring(0,2);
            var antesminutos = obj.tiempoEspera.substring(3,5);
            antesminutos = parseInt(antesminutos)+(parseInt(anteshoras)*60);
            var despueshoras = obj.tiempoEspera.substring(0,2);
            var despuesminutos = obj.tiempoEspera.substring(3,5);
            despuesminutos = parseInt(despuesminutos)+(parseInt(despueshoras)*60);
            momentoenabledhover();
            $(".momentoenabled").hover(
                function(){
                    var antes=$(this);
                    var flag=true;
                    var temp=$(this);
                    tiempoTotal=antesminutos+duracionminutos+despuesminutos;
                    console.log(tiempoTotal);
                    for (let x = 0; x < tiempoTotal; x+=parseInt(steps)) {
                        if(!temp.hasClass('momentoenabled')){
                            flag=false;
                        }
                        temp= temp.next('.momentoenabled');
                    }
                    if(flag){
                        for (let x = 0; x < antesminutos; x+=parseInt(steps)) {
                            
                            antes.addClass('momentoanteshover');
                            antes= antes.next('.momentoenabled');
                        }
                        var duracion=antes;
                        for (let x = 0; x < duracionminutos; x+=parseInt(steps)) {
                            duracion.addClass('momentoduracionhover');
                            duracion= duracion.next('.momentoenabled');
                        }
                        var despues=duracion;
                        for (let x = 0; x < despuesminutos; x+=parseInt(steps)) {
                            despues.addClass('momentodespueshover');
                            despues= despues.next('.momentoenabled');
                        }
                    }else{
                        for (let x = 0; x < antesminutos; x+=parseInt(steps)) {
                            
                            antes.addClass('nopermitido');
                            antes= antes.next('.momentoenabled');
                        }
                        var duracion=antes;
                        for (let x = 0; x < duracionminutos; x+=parseInt(steps)) {
                            duracion.addClass('nopermitido');
                            duracion= duracion.next('.momentoenabled');
                        }
                        var despues=duracion;
                        for (let x = 0; x < despuesminutos; x+=parseInt(steps)) {
                            despues.addClass('nopermitido');
                            despues= despues.next('.momentoenabled');
                        }
                    }
                }, function(){
                    var antes=$(this);
                    for (let x = 0; x < antesminutos; x+=parseInt(steps)) {
                        antes.removeClass('momentoanteshover').removeClass('nopermitido');
                        antes= antes.next('.momentoenabled');
                    }
                    var duracion=antes;
                    for (let x = 0; x < duracionminutos; x+=parseInt(steps)) {
                        duracion.removeClass('momentoduracionhover').removeClass('nopermitido');
                        duracion= duracion.next('.momentoenabled');
                    }
                    var despues=duracion;
                    for (let x = 0; x < despuesminutos; x+=parseInt(steps)) {
                        despues.removeClass('momentodespueshover').removeClass('nopermitido');
                        despues= despues.next('.momentoenabled');
                    }
              });
              $(".momentoenabled").click(
                  
                function(){
                    if(!$('.momentoenabled').hasClass('nopermitido')){
                        $('.momentoenabled').unbind('mouseenter').unbind('mouseleave')
                        var antes=$(this);
                        tiempoTotal=antesminutos+duracionminutos+despuesminutos;
                        for (let x = 0; x < tiempoTotal; x+=parseInt(steps)) {
                            antes.addClass('momentoselected');
                            antes= antes.next('.momentoenabled');
                        }
                        $('.momentoenabled').unbind('click');
                        $('#botonclean').removeClass('hide');
                        //$('#botonclean').show();
                    }else{
                        Swal.fire({
                            type: 'error',
                            title: 'No se puede asignar un turno a esa hora',
                            showConfirmButton: false,
                            timer: 1500
                          })
                    }
                });
            
        }
    });
}
function momentoenabledhover(){
    
}
$( "#botonclean" ).click(function() {
    getDatosTipoSesion($('#tipoSesionId').val());
    $('.momentoselected').removeClass('momentoselected').removeClass('momentoanteshover').removeClass('momentoduracionhover').removeClass('momentodespueshover');
});
function cargaHorariosRecursoHoy(fecha,dia,mes,year,establecimiento, recurso) {
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRecursosHoy",
            establecimiento:establecimiento,
            recurso:recurso,
            dia:fecha.getDay(),
            date: year+'-'+mes+'-'+dia
        },
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            $.each(obj, function( key, value ) {
                
                var horaInicio = value.horaInicio.substring(0,2);
                var horaFin = value.horaFin.substring(0,2);
                var minInicio = value.horaInicio.substring(3,5);
                var minFin = value.horaFin.substring(3,5);
                var timeInicio= new Date(fecha);
                timeInicio.setHours(horaInicio);
                timeInicio.setMinutes(minInicio);
                var timeFin= new Date(fecha);
                timeFin.setHours(horaFin);
                timeFin.setMinutes(minFin);
                while ( timeInicio< timeFin ) {
                    var hora=timeInicio.getHours();
                    var hora=(hora<10)?'0'+hora:hora;
                    var mins=timeInicio.getMinutes();
                    var mins=(mins<10)?'0'+mins:mins;

                    console.log(hora, mins);
                    $('#momento'+hora+'_'+mins).removeClass('momentodisabled');
                    $('#momento'+hora+'_'+mins).addClass('momentoenabled');

                    timeInicio.setMinutes(timeInicio.getMinutes()+ parseInt(steps));
                }
                  
            });
            var elid = $('.momentoenabled:first').attr('id');
            var elmnt = document.getElementById(elid);
            elmnt.scrollIntoView();
        }
    });
}
function cargaHorasDia(fecha) { 
    var i = new Date(fecha);
    var manana=new Date(fecha);
    var ahora=new Date();
    manana.setDate(manana.getDate()+1);
    var band=false;
    if(i.getDate() == ahora.getDate() && i.getMonth() == ahora.getMonth() && i.getFullYear() == ahora.getFullYear()){
        band=true;
    }
    console.log(i, new Date());
    $("#horasDia").empty();
    while ( i.getDate() < manana.getDate() ) {
        if(band){
            if(i>ahora){
                var hora=i.getHours();
                var hora=(hora<10)?'0'+hora:hora;
                var mins=i.getMinutes();
                var mins=(mins<10)?'0'+mins:mins;
                var html='';
                html+='<div class="row momentodisabled" id="momento'+hora+'_'+mins+'">';
                html+='<div class="col-sm-3">'+hora+':'+mins+'</div>';
                html+='<div class="col-sm-9"></div>';
                html+='</div>';
            }
        }else{
            var hora=i.getHours();
            var hora=(hora<10)?'0'+hora:hora;
            var mins=i.getMinutes();
            var mins=(mins<10)?'0'+mins:mins;
            var html='';
            html+='<div class="row momentodisabled" id="momento'+hora+'_'+mins+'">';
            html+='<div class="col-sm-3">'+hora+':'+mins+'</div>';
            html+='<div class="col-sm-9"></div>';
            html+='</div>';
        }
        
        $("#horasDia").append(html);
        i.setMinutes(i.getMinutes()+ parseInt(steps));
    }
}
function getRecursosHoy(fecha,dia,mes,year, establecimiento, recurso) {
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
            recurso:recurso,
            dia:fecha.getDay(),
            date: year+'-'+mes+'-'+dia
        },
        success: function (response) {
            var obj = JSON.parse(response);
            var html='<option ></option>';
            var unicos=[];
            $.each(obj, function( key, value ) {
                if(!unicos.includes(value.recursoId))
                    html+= '<option value="'+value.recursoId+'">'+value.nombre+'</option>';
                unicos[key]=value.recursoId;
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
    var today = new Date();
    today.setDate(today.getDate() - 1);
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

            var enmes=(fecha.getMonth()==mes)?'enmes':'noenmes';
            enmes+=(fecha>today)?' mayorhoy ':' menorhoy ';
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






/////////////

function scrollToElm(container, elm, duration){
  var pos = getRelativePos(elm);
  scrollTo( container, pos.top , 2);  // duration in seconds
}

function getRelativePos(elm){
  var pPos = elm.parentNode.getBoundingClientRect(), // parent pos
      cPos = elm.getBoundingClientRect(), // target pos
      pos = {};

  pos.top    = cPos.top    - pPos.top + elm.parentNode.scrollTop,
  pos.right  = cPos.right  - pPos.right,
  pos.bottom = cPos.bottom - pPos.bottom,
  pos.left   = cPos.left   - pPos.left;

  return pos;
}
    
function scrollTo(element, to, duration, onDone) {
    var start = element.scrollTop,
        change = to - start,
        startTime = performance.now(),
        val, now, elapsed, t;

    function animateScroll(){
        now = performance.now();
        elapsed = (now - startTime)/1000;
        t = (elapsed/duration);

        element.scrollTop = start + change * easeInOutQuad(t);

        if( t < 1 )
            window.requestAnimationFrame(animateScroll);
        else
            onDone && onDone();
    };

    animateScroll();
}

function easeInOutQuad(t){ return t<.5 ? 2*t*t : -1+(4-2*t)*t };