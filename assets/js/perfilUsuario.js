$(document).ready(function() {
    LoadProfileUser();
});


function logout()
{
    window.location.href = 'logout.php';
}

function LoadProfileUser()
{
        var str = $("#profileUserId").val();

        $.ajax({            
            url: "modules/module.perfilUsuario.php",
            type: "POST",
            data: {
                cmd: "getProfileInfo",
                idProfileUser: str
            },
            success: function(response) {

                var DatosPerfil = jQuery.parseJSON(response);
                $("#perfilNombre").val(DatosPerfil.nombre);
                $("#perfilApellidos").val(DatosPerfil.apellidos);
                $("#perfilEmail").val(DatosPerfil.email);
                $("#perfilPassword").val(DatosPerfil.password);                  
            }
        });
}


$('#formEditProfileUser').validate({ /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
    },
    submitHandler: function(form) {

        var passCiferEdit = calcMD5($("#perfilPassword").val());
        $("#perfilPassword").val(passCiferEdit);

        /*El argumento submitHandler evita que cuando el usuario presione un botón de tipo submit este refresque la página por lo que podemos enviar nuestra información por ajax*/
        var formularioEditar = $(form).serialize() + "&cmd=editarPerfilUsuario";

        console.log(formularioEditar);

        $.ajax({
            async: true,
            url: "modules/module.perfilUsuario.php",
            type: "POST",
            data: formularioEditar,
            success: function(response) {
                if(response!="0")
                {
                    getData = response.split("%");
                    console.log(getData);                                       
                    $("#perfilNombre").val(getData[1]);
                    $("#perfilApellidos").val(getData[2]);
                    $("#perfilEmail").val(getData[3]);
                    $("#perfilPassword").val(getData[4]);

                    swal("\u00A1Perfil guardado con exito!","tu información de perfil ha sido actualizada correctamente", "success");
                    
                } 
                else
                {
                    //console.log(response);
                    swal("\u00A1Error!", "Tu información de perfil no se ha podido editar", "error");
                }
            }
        });
    }
});