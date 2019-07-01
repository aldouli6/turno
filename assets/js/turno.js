Date.prototype.getWeek = function(){
    var d = new Date(Date.UTC(this.getFullYear(), this.getMonth(), this.getDate()));
    var dayNum = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dayNum);
    var yearStart = new Date(Date.UTC(d.getUTCFullYear(),0,1));
    return Math.ceil((((d - yearStart) / 86400000) + 1)/7)
  };
  function addDays(date, days) {
    var result = new Date(date);
    result.setDate(result.getDate() + days);
    return result;
  }
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
$.fn.removeClassStartingWith = function (filter) {
    $(this).removeClass(function (index, className) {
        return (className.match(new RegExp("\\S*" + filter + "\\S*", 'g')) || []).join(' ')
    });
    return this;
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

var fechaglobal;
//$(window).load(function() {
 $(document).ready(function() {
    creaCalendario(new Date(2019,6,1));
    console.log(new Date());
    $("#submitBtn").on( "click", function(){        
        $("#formaturno").submit(); 
    });
    steps=$('#establecimientoStepping').val();
    var aidi;
    var year;
    var mes;
    var dia;
    var establecimiento= $('#establecimientoId').val();
    $('body').on('click','.icon',function() {
        var target= $(this).attr('target');
        console.log(target);
        creaCalendario(new Date(target))
    });
    $('body').on('click','.iconmod', function() {
        var target= $(this).attr('target');
        target= new Date(target);
        cargarModal(target.getDate(),target.getMonth()+1,target.getFullYear(),establecimiento);
    });
    $('body').on('click',".enmes.mayorhoy", function() {
        aidi = $(this).attr('id').substring(3);
        year=aidi.substring(0,4);
        mes=aidi.substring(4,6);
        dia=aidi.substring(6,8);
        fechaglobal =new Date(year, mes-1, dia);
        //console.log(dia,mes,year,establecimiento);
        cargarModal(dia,mes,year,establecimiento);
    });
    $('body').on('hide.bs.modal',"#formturno", function(){
        limpiar();
      });
    $('#divrecursoId').hide();
    $('body').on('change',"#usuarioId",function() {
        $('#divrecursoId').show();
    });
    $('body').on('change',"#tipoSesionId",function() {
        var sesionid=$(this).val();

        $('#datosocultos').addClass('hide');
        $(".momentoenabled").unbind('mouseenter').unbind('mouseleave').unbind('click');
        $('.momentoenabled').removeClass(' momentoanteshover momentoduracionhover momentodespueshover momentoselected')
        $.ajax({
            async: true,
            url: "modules/module.recurso.php",
            type: "POST",
            data: {
                cmd: "getRecursosFromTipoSesion",
                establecimiento:establecimiento,
                tipoSesion: $(this).val(),
            },
            success: function (response) {
                var obj = JSON.parse(response);
                $.each(obj, function( key, value ) {
                    //console.log( '---------cargaHorariosRecursoHoy',fechaglobal,dia,mes,year,establecimiento, value.recursoId)
                    cargaHorariosRecursoHoy(fechaglobal,dia,mes,year,establecimiento, value.recursoId);
                });
                $.each(obj, function( key, value ) {
                    //console.log( '+++++++++cargaTurnos',fechaglobal,dia,mes,year,establecimiento, value.recursoId)
                    cargaTurnos(fechaglobal,dia,mes,year,establecimiento, value.recursoId);
                });
                getDatosTipoSesion(sesionid);
                bloqueaLimites(fechaglobal,establecimiento, sesionid);
            }
        });
        
    });
});
function bloqueaLimites(fecha,establecimiento, tipoSesionId) {
    var url_request = "modules/module.tipoSesion.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getTipoSesion",
            tipoSesion:tipoSesionId
        },
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(obj);
            var ahora=new Date();
            console.log(ahora);
            console.log(obj.limiteAntesAgendarDias);
            var limiteAntes= ahora;
            limiteAntes.setDate(ahora.getDate()+parseInt(obj.limiteAntesAgendarDias));
            console.log(limiteAntes);
            limiteAntes.setHours(limiteAntes.getHours()+parseInt(obj.limiteAntesAgendarHoras));
            console.log(limiteAntes);
            limiteAntes.setMinutes(limiteAntes.getMinutes()+parseInt(obj.limiteAntesAgendarMins));
            console.log(limiteAntes);
            console.log(fecha);
            while (limiteAntes>fecha) {
                var hora=fecha.getHours();
                var hora=(hora<10)?'0'+hora:hora;
                var mins=fecha.getMinutes();
                var mins=(mins<10)?'0'+mins:mins; 
                console.log(hora+'_'+mins);
                if( $('#momento'+hora+'_'+mins).hasClass('momentoenabled') ){
                    $('#momento'+hora+'_'+mins).removeClass('momentoenabled');
                    $('#momento'+hora+'_'+mins).addClass('bloqueado');
                    $('#momento'+hora+'_'+mins).unbind('click');
                }
                fecha.setMinutes(fecha.getMinutes()+ parseInt(steps));
            }
        }
    });
}
function cargarModal(dia,mes,year, establecimiento) {
    
    var fecha=new Date(year, mes-1, dia);
    var prevday = addDays(fecha, -1);
    var nextday = addDays(fecha, 1);
    var hoy=new Date()
    var hoy2 =  new Date(hoy.getFullYear(),hoy.getMonth(), hoy.getDate() )
    if(prevday>=hoy2){
        $('#prevday').show();
        $('#prevday').attr('target',prevday);
    }else{
        $('#prevday').hide();
    }
    $('#nextday').attr('target',nextday);
    $('#fecha').val(year+'-'+mes+'-'+dia);
    $('#eldia').text(fechaNombre(fecha));
    fechaglobal=fecha;
    getRecursosHoy(fecha,dia,mes,year, establecimiento, 0);
    
    cargaHorasDia(fecha);
    
}
function cargaTurnos(fecha,dia,mes,year,establecimiento, recurso) {
    var url_request = "modules/module.turno.php";
    var method = "POST";
    console.log(establecimiento);
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getTurnosHoy",
            establecimiento:establecimiento,
            recurso:recurso,
            date: year+'-'+mes+'-'+dia
        },
        success: function (response) {
            var obj = JSON.parse(response);
            console.log(obj);
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
                var tempfin=new Date(timeFin);
                tempfin.setMinutes(tempfin.getMinutes()- parseInt(steps));
                horaFin=tempfin.getHours();
                horaFin=(horaFin<10)?'0'+horaFin:horaFin;
                minFin=tempfin.getMinutes();
                minFin=(minFin<10)?'0'+minFin:minFin;
                while ( timeInicio<timeFin ) {
                    var hora=timeInicio.getHours();
                    var hora=(hora<10)?'0'+hora:hora;
                    var mins=timeInicio.getMinutes();
                    var mins=(mins<10)?'0'+mins:mins;
                    var elrec= $('#momento'+hora+'_'+mins).find("#recdisp"+value.recursoId);

                    elrec.removeClassStartingWith('rec-');
                    console.log('rec'+value.estatus);
                    elrec.addClass('rec-'+value.estatus);
                    elrec.attr('turnid',value.turnoId);
                    var disponible= $('#momento'+hora+'_'+mins).find(".rec-disponible");
                    if (disponible.length==0) {
                        $('#momento'+hora+'_'+mins).removeClass('momentoenabled');
                        $('#momento'+hora+'_'+mins).addClass('momentodisabled');
                    }
                    timeInicio.setMinutes(timeInicio.getMinutes()+ parseInt(steps));
                }
                  
            });
            $(".rec-agendado , .rec-atendiendo").click(function(e) {
                var url_request = "modules/module.turno.php";
                var method = "POST";
                var elturno =$(this).attr('turnid');
                var elrec =$(this).attr('recid');
                var eluser;
                var nuevoestatus;
                var estatusletra;
                var textoconfirm;
                $.ajax({
                    async: true,
                    url: url_request,
                    type: method,
                    data: {
                        cmd: "getDataTurno",
                        turno:elturno,
                    },
                    success: function (response) {
                        var obj = JSON.parse(response)
                        console.log(obj);
                        eluser=obj.usuarioId;
                        switch (obj.estatusId) {
                            case '1':
                                textoconfirm='Atender Turno!'
                                nuevoestatus='3'
                                estatusletra= 'En atención!'
                                break;
                            case '3':
                                textoconfirm='Turno Atendido!'
                                nuevoestatus='2'
                                estatusletra= 'Atendido'
                                break;
                            default:
                                break;
                        }
                        Swal.fire({
                            type: 'info',
                            title: 'Turno número: '+obj.turnoId,
                            html:   '<div style="margin:0 20%">' +
                                    '<b class="pull-left">Usuario:</b> <span class="pull-right">'+obj.nombre+' '+obj.apellidos+'</span><br>' +
                                    '<b class="pull-left">Recurso:</b> <span class="pull-right">'+obj.rNombre+'</span><br>' +
                                    '<b class="pull-left">Tipo de Sesión:</b> <span class="pull-right">'+obj.tsNombre+'</span><br>' +
                                    '<b class="pull-left">Fecha:</b> <span class="pull-right">'+obj.fecha+'</span><br>' +
                                    '<b class="pull-left">Hora de Inicio:</b> <span class="pull-right">'+obj.horaInicio+'</span><br>' +
                                    '<b class="pull-left">Hora de Fin:</b> <span class="pull-right">'+obj.horaFin+'</span><br>' +
                                    '<b class="pull-left">Estatus:</b> <span class="pull-right">'+obj.eNombre+'</span></div>', 
                            
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonText: textoconfirm,
                            cancelButtonText: 'Cancelar Turno!',
                        }).then((result) => {
                            if (result.value) {
                                //Atender
                                $.ajax({
                                    async: true,
                                    url: url_request,
                                    type: method,
                                    data: {
                                        cmd: "cambiarEstatusTurno",
                                        turnoId:elturno,
                                        estatus:nuevoestatus,
                                    },
                                    success: function (response) {
                                        var obj = JSON.parse(response)
                                        console.log(obj);
                                        $('#tipoSesionId').val($('#tipoSesionId').val()).trigger('change');
                                        Swal.fire(
                                            estatusletra,
                                            'El turno '+obj.turnoId+' está '+estatusletra,
                                            'success'
                                          );
                                    } 
                                });
                              
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                Swal.fire({
                                    title: 'Confimación!',
                                    text: "Estas seguro de canelar el turno",
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Si! cancelarlo',
                                    cancelButtonText: 'No',
                                  }).then((result) => {
                                    if (result.value) {
                                        $.ajax({
                                            async: true,
                                            url: url_request,
                                            type: method,
                                            data: {
                                                cmd: "cambiarEstatusTurno",
                                                turnoId:elturno,
                                                estatus:'4',
                                            },
                                            success: function (response) {
                                                var obj = JSON.parse(response)
                                                console.log(obj);
                                                $('#tipoSesionId').val($('#tipoSesionId').val()).trigger('change');
                                                Swal.fire(
                                                    'Cancelado',
                                                    'El turno '+obj.turnoId+' está cancelado!',
                                                    'success'
                                                );
                                            } 
                                        });
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    //   Swal.fire(
                                    //     'Cancelled',
                                    //     'Your imaginary file is safe :)',
                                    //     'error'
                                    //   )
                                    }
                                  })
                                
                            }
                          })
                        
                    }
                });

                

        });
        }
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
            console.log(obj);
            var duracionhoras = obj.duracion.substring(0,2);
            var duracionminutos = obj.duracion.substring(3,5);
            duracionminutos = parseInt(duracionminutos)+(parseInt(duracionhoras)*60);
            var anteshoras = obj.tiempoEspera.substring(0,2);
            var antesminutos = obj.tiempoEspera.substring(3,5);
            antesminutos = parseInt(antesminutos)+(parseInt(anteshoras)*60);
            var despueshoras = obj.tiempoEntreSesion.substring(0,2);
            var despuesminutos = obj.tiempoEntreSesion.substring(3,5);
            despuesminutos = parseInt(despuesminutos)+(parseInt(despueshoras)*60);
            $(".momentoenabled").hover(
                function(){
                    var antes=$(this);
                    var flag=true;
                    var temp=$(this);
                    tiempoTotal=antesminutos+duracionminutos+despuesminutos;
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
                        var horaI = antes.children('.col-sm-3').text();
                        var html='';
                        $("#recursoId").empty();
                        var elids=0;
                        antes.children('.contenido').children('.rec-disponible').each(function( index ) {
                            if (elids==0) {
                                elids= $( this ).attr('recid')
                            }
                            html+= '<option value="'+$( this ).attr('recid')+'">'+$( this ).text()+'</option>';
                          });
                        $("#recursoId").html(html);
                        $("#recursoId").select2("val",elids);
                        tiempoTotal=antesminutos+duracionminutos+despuesminutos;
                        for (let x = 0; x < tiempoTotal; x+=parseInt(steps)) {
                            antes.addClass('momentoselected');
                            antes= antes.next('.momentoenabled');
                        }
                        var horaF = antes.children('.col-sm-3').text();
                        $('#horaInicio').val(horaI);
                        $('#horaFin').val(horaF);
                        $('#estatusId').val(1);
                        $('.momentoenabled').unbind('click');
                        $('#datosocultos').removeClass('hide');
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
$('#formaturno').validate({
    submitHandler: function (form) {
        var formulario = $(form).serialize();
        if (form.turnoId.value=='0') {
            $.ajax({
                url: "modules/module.turno.php",
                type: "POST",
                data: formulario,
                success: function (response) {
                    console.log(response);
                    if (response!='0') {
                        $('#formturno').modal('hide');
                        Swal.fire({
                            type: 'success',
                            title: "\u00A1En hora buena!",
                            text: 'El turno ha sido agendado correctamente',
                          })
                    }else{
                        Swal.fire({
                            type: 'error',
                            title: 'No se puede asignar un turno a esa hora',
                            showConfirmButton: false,
                            timer: 1500
                          })
                    }
                }
            });
        } else {
                
        }
    }
});

function limpiar() {
    getDatosTipoSesion($('#tipoSesionId').val());
    $('.momentoselected').removeClass('momentoselected').removeClass('momentoanteshover').removeClass('momentoduracionhover').removeClass('momentodespueshover');
    $('#divrecursoId').hide();
    $('#datosocultos').addClass('hide');
    $('#tipoSesionId').select2("val",'0');
    $('#usuarioId').select2("val",'0');
    $('#datosocultos').addClass('hide');
    $('#horaInicio').val('');
    $('#horaFin').val('');
    $('#fecha').val('');
    $('#estatusId').val('');
    $('.contenido').empty();
    $("#recursoId").empty();
    $("#turnoId").val('0');
}
$( ".busquedapor" ).click(function() {
    var elvalor=$(this).text();
    var elid=$(this).attr('id');
    $('#labelselect').text('Busqueda por '+elvalor);
    console.log(elid.substring(3));
    cargaselectbusquedapor(elid.substring(3));
    });
function cargaselectbusquedapor(tipo, preselct=0) {
    var url_request = "modules/module.usuarios.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getclientes",
            busqueda:tipo,
            usuarioId:0
        },
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            var html='<option ></option>';
            $.each(obj, function( key, value ) {
                html+= '<option value="'+value.id+'">'+value.busqueda+'</option>';
            });
            $("#usuarioId").html(html);
            if(preselct!=0){
                $('#usuarioId').select2("val",preselct).trigger('change')
            }
        }
    });
}
function cargaHorariosRecursoHoy(fecha,dia,mes,year,establecimiento, recurso) {
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $('.contenido').empty();
    console.log(fecha,dia,mes,year,establecimiento, recurso);
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
                    if( !$('#momento'+hora+'_'+mins).hasClass('bloqueado')){
                        $('#momento'+hora+'_'+mins).addClass('momentoenabled');
                    }

                    $('#momento'+hora+'_'+mins).removeClass('momentodisabled');
                    var eldiv='<div id="recdisp'+value.recursoId+'" class="rec-disponible  rec col-sm-3" recid="'+value.recursoId+'">'+value.nombre+'</div>'
                    $('#contenido'+hora+'_'+mins).append(eldiv);
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
        var nopermitido = (i<=ahora)?' bloqueado ':'';
        var hora=i.getHours();
        var hora=(hora<10)?'0'+hora:hora;
        var mins=i.getMinutes();
        var mins=(mins<10)?'0'+mins:mins;
        var html='';
        html+='<div class="row momentodisabled '+nopermitido+'" id="momento'+hora+'_'+mins+'">';
        html+='<div class="col-sm-3">'+hora+':'+mins+'</div>';
        html+='<div class="col-sm-9 contenido row" id="contenido'+hora+'_'+mins+'"></div>';
        html+='</div>';
        $("#horasDia").append(html);
        i.setMinutes(i.getMinutes()+ parseInt(steps));
    }
}
function getRecursosHoy(fecha,dia,mes,year, establecimiento, recurso) {
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $('#recursoId').select2("val", 0);
    $("#recursoId").empty();
    console.log('getRecursosHoy');
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
            var html='<option ></option>';
            var unicos=[];
            $.each(obj, function( key, value ) {
                if(!unicos.includes(value.recursoId))
                    html+= '<option value="'+value.recursoId+'">'+value.nombre+'</option>';
                unicos[key]=value.recursoId;
            });
            $("#recursoId").html(html);
            getTipoSesiones(establecimiento, unicos.join(','));
        }
    });
}
function getTipoSesiones(establecimiento, recursos) {
    console.log(recursos);
    var url_request = "modules/module.recurso.php";
    var method = "POST";
    $('#tipoSesionId').select2("val", 0);
    $("#tipoSesionId").empty();
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRelTipoSesionesSinID",
            establecimiento:establecimiento,
            recurso: recursos
        },
        success: function (response) {
            console.log(response);
            var obj = JSON.parse(response);
            console.log(obj);
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
    console.log(hoy);
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
    for (let j = 0; j < 6; j++) {
         var week=fecha.getWeek();
        var html='<div id="semana'+week+'" style="display:flex" class=" justify-content-md-center"></div>';
        $('#fechas').append(html);    
        // var html='<div id="week'+week+'" class="box ratio16_9"><div class="celda semana">'+week+' </div> ';
        // $('#semana'+week).append(html); 
        for (let i = 0; i <= 6; i++) {
            var dayOfWeek= fecha.getDay();
            var html='';

            var enmes=(fecha.getMonth()==mes)?'enmes':'noenmes';
            enmes+=(fecha>today && fecha.getMonth()==mes)?' mayorhoy ':' menorhoy ';
            var modal=(fecha>today && fecha.getMonth()==mes)?'data-toggle="modal" data-target="#formturno"':'';
            html+='<div   class="box ratio16_9"><div id="dia'+fecha.yyyymmdd()+'" '+modal+' class="'+enmes+' celda dia"><div class="numero" >'+fecha.getDate()+'</div> </div> ';
            $('#semana'+week).append(html); 
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