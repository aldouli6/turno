$(document).ready(function() {
    //$('.multiselect').multiSelect();
    cargaSelectRecuros($('#establecimientoIdNew').val());
    cargaSelectAsuetoExtra();
    $('input').lc_switch("", "");

    var diasAsuetoOficialesNew = 1;
    $("#diasAsuetoOficialesNew").val(diasAsuetoOficialesNew);

    var diasAsuetoOficialesEdit = 1;
    $("#diasAsuetoOficialesEdit").val(diasAsuetoOficialesEdit);

    // ****************************************************    

    $('body').delegate('.lcs_check', 'lcs-on', function () {
        document.getElementById('divstatus2').style.display = 'none';
        document.getElementById('divstatus1').style.display = 'block';
        diasAsuetoOficialesNew = 1;
        //console.log(diasAsuetoOficialesNew);
        $("#diasAsuetoOficialesNew").val(diasAsuetoOficialesNew);
    });

    $('body').delegate('.lcs_check', 'lcs-off', function () {
        document.getElementById('divstatus2').style.display = 'block';
        document.getElementById('divstatus1').style.display = 'none';
        diasAsuetoOficialesNew = 0;
        //console.log(diasAsuetoOficialesNew);
        $("#diasAsuetoOficialesNew").val(diasAsuetoOficialesNew);
    });
    $('body').delegate('.lcs_checkEdit', 'lcs-on', function () {
        document.getElementById('divstatus2Edit').style.display = 'none';
        document.getElementById('divstatus1Edit').style.display = 'block';
        diasAsuetoOficialesEdit = 1;
        //console.log(diasAsuetoOficialesEdit);
        $("#diasAsuetoOficialesEdit").val(diasAsuetoOficialesEdit);
    });

    $('body').delegate('.lcs_checkEdit', 'lcs-off', function () {
        document.getElementById('divstatus2Edit').style.display = 'block';
        document.getElementById('divstatus1Edit').style.display = 'none';
        diasAsuetoOficialesEdit = 0;
        //console.log(diasAsuetoOficialesEdit);
        $("#diasAsuetoOficialesEdit").val(diasAsuetoOficialesEdit);
    });

});
function cargaSelectAsuetoExtra(params) {
    var d = new Date();
    var months = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oc", "Nov", "Dic"];
    var html='';
    for (let i = 0; i < 730; i++) {
        d.setDate(d.getDate() + 1);
        fechaString=("0" + d.getDate()).slice(-2)+' '+months[d.getMonth()]+' '+d.getFullYear();
        fechaValue=d.getFullYear()+'/'+("0" + (d.getMonth()+1)).slice(-2)+'/'+("0" + d.getDate()).slice(-2)
        html+= '<option value="'+fechaValue+'">'+fechaString+'</option>';
           
    }
    $("#diasAsuetoExtraNew").html(html); 
     
}
function cargaSelectRecuros(id) {
    var url_request = "modules/module.horarios.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "getRecuros",
            establecimiento:id
        },
        success: function (response) {
            var obj = JSON.parse(response);
            var html='<option value="">Selecciona un Recurso</option>';
            $.each(obj, function( key, value ) {
                html+= '<option value="'+value.id+'">'+value.nombre+'</option>';
            });
            $("#recursoNew").html(html); 
        }
    });
}