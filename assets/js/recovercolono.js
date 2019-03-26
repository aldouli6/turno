$('#formRecoverPass').validate({
    rules: {
    },
    submitHandler: function(form) {
          var formulario = $(form).serialize() + "&cmd=recoverPass";
          $.ajax({
              async: true,
              url: "modules/module.recoverpass.php",
              type: "POST",
              data: formulario,
              success: function(response) {
                if(response == "1"){
                  swal("\u00A1En hora buena!", "la contraseña se ha restablecido, ahora puedes iniciar sesion con tu nueva contraseña", "success");
                } else if(response == "2"){
                  swal("\u00A1Error!", "ha ocurrido un error con nuestros servidores, intente mas tarde", "error");
                } else if(response == "3"){
                  swal("\u00A1Error!", "El tiempo para la solicitud se ha agotado, haz una nueva solicitud desde la aplicacion", "error");
                } else if(response == "4"){
                  swal("\u00A1Error!", "El usuario no existe", "error");
                }
              }
          });
    }
});