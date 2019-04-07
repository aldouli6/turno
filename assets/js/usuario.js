var _oldPassword;

$(document).ready(function () {

    $("#regUsuarioTelefono").mask("(999) 999-9999"); //Aplica un formato de teléfono al campo seleccionado
    $("#editUsuarioTelefono").mask("(999) 999-9999");
    // refreshUsuarioTable(); /*manda llamar al método para actualizar el datatable del módulo de usuarios*/
    loadListaTiposUsuario(); /*Carga la lista de tipos de usuario en el formulario de registro de usuario*/

    refreshUsuarioSistemaTable();


    $('input').lc_switch("", "");
    $('#editStatusColono').lc_switch("", "");

    var statusUsuario = 1;
    $("#StatusUsuarioSistema").val(statusUsuario);

    var statusUsuarioEdit = 1;
    $("#EditStatusUsuarioSistema").val(statusUsuarioEdit);

    // ****************************************************    

    $('body').delegate('.lcs_check', 'lcs-on', function () {
        document.getElementById('divstatus2').style.display = 'none';
        document.getElementById('divstatus1').style.display = 'block';
        statusUsuario = 1;
        //console.log(statusUsuario);
        $("#StatusUsuarioSistema").val(statusUsuario);
    });

    $('body').delegate('.lcs_check', 'lcs-off', function () {
        document.getElementById('divstatus2').style.display = 'block';
        document.getElementById('divstatus1').style.display = 'none';
        statusUsuario = 0;
        //console.log(statusUsuario);
        $("#StatusUsuarioSistema").val(statusUsuario);
    });


    // ****************************************************


    $('body').delegate('.lcs_checkEdit', 'lcs-on', function () {
        document.getElementById('divstatus2Edit').style.display = 'none';
        document.getElementById('divstatus1Edit').style.display = 'block';
        statusUsuarioEdit = 1;
        //console.log(statusUsuarioEdit);
        $("#EditStatusUsuarioSistema").val(statusUsuarioEdit);
    });

    $('body').delegate('.lcs_checkEdit', 'lcs-off', function () {
        document.getElementById('divstatus2Edit').style.display = 'block';
        document.getElementById('divstatus1Edit').style.display = 'none';
        statusUsuarioEdit = 0;
        //console.log(statusUsuarioEdit);
        $("#EditStatusUsuarioSistema").val(statusUsuarioEdit);
    });


    // ***************************************************



    jQuery.validator.addMethod('selectcheck', function (value) { //Se crea la regla para la lista desplegable de Tipo de Usuario
        return (value != '0');
    }, "Este campo es obligatorio.");

    jQuery.validator.addMethod('selectcheckEdit', function (value) { //Se crea la regla para la lista desplegable de Tipo de Usuario de la edición del usuario
        return (value != '0');
    }, "Este campo es obligatorio.");


    $(function () {
        $("#horaInicio").datetimepicker({
            // format: 'LT'
            format: 'HH:mm'
        });

        $("#horaFin").datetimepicker({
            // format: 'LT'
            format: 'HH:mm'
        });

    });

    $(function () {
        $("#edithoraInicio1").datetimepicker({
            // format: 'LT'
            format: 'HH:mm'
        });

        $("#edithoraFin2").datetimepicker({
            // format: 'LT'
            format: 'HH:mm'
        });

    });

    $.ajax({ //Se carga la lista de los estados en el formulario de registro del usuario
        url: "modules/module.usuarios.php",
        type: "POST",
        data: {
            cmd: "listaEstadosUsuario"
        },
        success: function (response) {
            $('#regUsuarioEstado').html(response);
        }
    });


    $.ajax({ //Se carga la lista de los estados en el formulario de edición del usuario
        url: "modules/module.usuarios.php",
        type: "POST",
        data: {
            cmd: "listaEstadosUsuario"
        },
        success: function (response) {
            $('#editUsuarioEstado').html(response);
        }
    });


    $('#formEdicionUsuario').validate({
        /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
        rules: {
            editTipoUsuario: {
                selectcheckEdit: true
            }
        },
        submitHandler: function (form) { 
            var formularioEditar = $(form).serialize() + "&cmd=editarUsuario";
            $.ajax({
                   url: "modules/module.usuarios.php",
                type: "POST",
                data: formularioEditar,
                success: function (response) {
                    if (response != "0") {
                        getData = response.split("%");
                        $("#usuarioSistemaDataTable").DataTable().rows().eq(0).each(function (index) {
                            var UsuarioIdTable = $("#usuarioSistemaDataTable").DataTable().cell(index, 0).data();
                            if (UsuarioIdTable == $("#idUsuarioSistemaEditar").val()) {
                                for (i = 0; i < 7; i++) {
                                    $("#usuarioSistemaDataTable").DataTable().cell(index, i).data(getData[i]);
                                    if (getData[5] == 1) {
                                        $("#Usuario" + getData[0]).css('color', '#626262');
                                        $("#Usuario" + getData[0]).css('font-weight', 'inherit');
                                        // console.log('On');
                                    } else if (getData[5] == 0) {
                                        $("#Usuario" + getData[0]).css('color', 'red');
                                        $("#Usuario" + getData[0]).css('font-weight', 'bolder');
                                        // console.log('Off');
                                    }
                                }
                                $('#formEditUsuario').modal('hide');
                                 Swal.fire("\u00A1En hora buena!", "El usuario se ha editado correctamente", "success");
                            }
                        });
                    } else {
                        $('#formEditUsuario').modal('hide');
                         Swal.fire("\u00A1Error!", "El usuario no se ha podido editar", "error");
                    }
                } //Fin método success
            }); //Fin método ajax
        } //Fin submitHandler
    }); //Termina el método para editar usuarios-----------------------------------------------------------------------------------------------------------------


    //Carga la lista de tipos de usuario en el formulario del registro de usuario-------------------------------------------------------------------------------------------------------------

    function loadListaTiposUsuario() {
        var url_request = "modules/module.usuarios.php";
        var method = "POST";
        $.ajax({
            async: true,
            url: url_request,
            type: method,
            data: {
                cmd: "listaTipoUsuario"
            },
            success: function (response) {
                $("#regTipoUsuario").html(response); /*Se consultan los tipos de usuario en la base de datos y se cargan en la lista despegable del formulario de registro de usuario*/
                $("#editTipoUsuarios").html(response);
            }
        });
    }
}); /*Fin del método document ready*/

$('#formRegistroUsuario').validate({
    /*Método que valida que todos los campos requeridos del formulario esten llenos para poder mandar la información a la base de datos*/
    rules: {
        regTipoUsuario: {
            selectcheck: true
        }
    },
    submitHandler: function (form) {
        /*El argumento submitHandler evita que cuando el usuario presione un botón de tipo
             submit este refresque la página por lo que podemos enviar nuestra información por ajax*/
        var passCifer = calcMD5($("#regUsuarioPass").val());
        $("#regUsuarioPass").val(passCifer);
        var formulario = $(form).serialize() + "&cmd=registrarUsuario";
        $.ajax({
            url: "modules/module.usuarios.php",
            type: "POST",
            data: formulario,
            success: function (response) {
                $("#formRegistroUsuario").get(0).reset();
                $("#regTipoUsuario").select2("val", 0);
                if (response != "0") {
                     Swal.fire("\u00A1En hora buena!", "El usuario se ha registrado correctamente", "success");
                    $('#formRegUsuario').modal('hide');
                    getData = response.split("%");
                    $("#usuarioSistemaDataTable").DataTable().row.add(getData).draw().node();
                } else {
                     Swal.fire("\u00A1Error!", "El usuario no se ha podido registrar", "error");
                    $('#formRegUsuario').modal('hide');
                }
            }
        });
    }
}); //Termina el método para registrar usuarios-----------------------------------------------------------------------------------------------------------------




/*Esta parte se hacen las validaciones para el formulario de edición del usuario-----------------------------------------------------------------------------------------------*/

function editUsuarioData(id_usuario) {
    /*Esta funcion recibe como parámetro el ID del usuario y con este se obtendrán los datos del usuario para posteriormente proseguir con la edición*/

    //console.log(id_usuario);
    var url_request = "modules/module.usuarios.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "setDatosUsuario",
            claveUsuario: id_usuario
        },
        success: function (response) {
            var DatosUsuario = jQuery.parseJSON(response);
            _oldPassword = DatosUsuario.password;
            $("#idUsuarioSistemaEditar").val(DatosUsuario.usuarioId);
            $("#editUsuarioNombre").val(DatosUsuario.nombre);
            $("#editUsuarioApellidos").val(DatosUsuario.apellidos);
            $("#editUsername").val(DatosUsuario.username);
            $("#editTelefono").val(DatosUsuario.telefono);
            $("#editUsuarioEmail").val(DatosUsuario.email);
            $("#editUsuarioPass").val(DatosUsuario.password);
            //$("#editTipoUsuarios").html('<option value="' + DatosUsuario.tipoUsuarioId + '">' + DatosUsuario.descripcion + '</option>');
            $("#editTipoUsuarios").select2("val", DatosUsuario.tipoUsuarioId);
            $("#EditStatusUsuarioSistema").val(DatosUsuario.estatus);


            if (DatosUsuario.estatus == 1) {
                $('input').lcs_on();
                //console.log('encendido');
                // document.getElementById('divstatus2Edit').style.display = 'none';
                // document.getElementById('divstatus1Edit').style.display = 'block';                
                // $("#EditStatusUsuarioSistema").val(valSwitch);

            } else if (DatosUsuario.estatus == 0) {
                $('input').lcs_off();
                //console.log('apagado');
                // document.getElementById('divstatus2Edit').style.display = 'block';
                // document.getElementById('divstatus1Edit').style.display = 'none';
                // $("#EditStatusUsuarioSistema").val(valSwitch);
            }


            // $("#regTipoUsuario").html(response); /*Se consultan los tipos de usuario en la base de datos y se cargan en la lista despegable del formulario de registro de usuario*/
            //  $("#editTipoUsuarios").html(response);
        }
    });

}




/*Se termina la parte de las validaciones para la edición del usuario------------------------------------------------------------------------------------------*/




//En esta parte se crea la función para eliminar al usuario (se pone en estatus cero) de la base de datos y así ya no se visualice en el datatable de usuarios -------------------------



function eliminarUsuarioData(id_usuario,nombre) {
     Swal.fire({
            title: "\u00BFEst\u00E1s seguro de eliminar al usuario " + nombre + "?",
            text: "\u00A1Una vez eliminado no se podra recuperar!",
            type: "warning",
            showCancelButton: true,
            //confirmButtonClass: "btn btn-primary",
            confirmButtonColor: '#428bca',
            confirmButtonText: "Si, \u00A1elim\u00EDnalo!",
            cancelButtonText: "No, cancelar",
            }).then((result) => {
                if (result.value) {
                var url_path = "modules/module.usuarios.php";
                metodo = "POST";
                $.ajax({
                    async: true,
                    url: url_path,
                    type: metodo,
                    data: {
                        cmd: 'eliminarUsuarioSistema',
                        claveUsuarioEliminar: id_usuario
                    },
                    success: function (response) {
                        if (response == "1") {
                            $("#usuarioSistemaDataTable").DataTable().rows("#Usuario" + id_usuario).remove().draw();
                             Swal.fire("\u00A1En hora buena!", "el usuario ha sido eliminado correctamente", "success");
                        } else {
                             Swal.fire("Error", "El usuario no ha podido ser eliminado.", "error");
                        }
                    }
                });
            } //Fin if confirm
            else {
                 Swal.fire("Cancelado", "No se han realizado cambios", "error");
            }
        }); //Fin mensaje cancelar usuario




}


//Aquí termina la función para eliminar al usuario


//inicio instrucción que carga todos los datos de los usuario en el datatable del módulo de usuarios  --------------------------------------------------------------------------------------------------


// fin instrucción que carga todos los datos de los usuario en el datatable del módulo de usuarios  -----------------------------------------------------------------------------------

function refreshUsuarioSistemaTable() {

    var url_request = "modules/module.usuarios.php";
    var method = "POST";
    $.ajax({
        async: true,
        url: url_request,
        type: method,
        data: {
            cmd: "dataTableUsuario"
        },
        success: function (response) {
            //console.log(response);
            $("#contenidoListaUsuarioSistema").html(response);

            referenceTableUsuarios();
        }
    });
}

function referenceTableUsuarios() {
    //Método que crea la referencia del datatable con la tabla que se está mandado llamar, y así implementar todas las características dadas por esta herramienta.
    $('#usuarioSistemaDataTable').dataTable({
        "language": {
            "sProcessing": "Procesando...",
            "sLoadingRecords": "Cargando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sInfoFiltered": "(Datos filtrados de un total de _MAX_ registros)",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfoEmpty": "Mostrando 0-0 de un total de 0 registros",
            "sInfo": "Mostrando _START_-_END_ de un total de _TOTAL_ registros",
            "sSearch": "Buscar:",
            "sZeroRecords": "No se encontraron resultados",
            "oPaginate": {
                "sNext": '<span class=" pg-arrow_right"></span>',
                "sPrevious": '<span class=" pg-arrow_left"></span>'
            },
        },
        "columnDefs": [{
                "targets": [6, 7],
                "orderable": false
            },
            {
                "targets": [0, 5],
                "visible": false
            },
        ],

        "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {


            //console.log(aData);

            if (aData[5] == 0) {
                $("#Usuario" + aData[0]).css('color', 'red');
                $("#Usuario" + aData[0]).css('font-weight', 'bolder');
                // $('td', nRow).css('background-color', '#ffd9d9');
                //$('td', nRow).css('color', 'red');
                //$('td', nRow).css('font-weight', 'bolder');
            } else if (aData[5] == 1) {
                $("#Usuario" + aData[0]).css('color', '#626262');
                $("#Usuario" + aData[0]).css('font-weight', 'inherit');
            }
        }

    });

}


$("#regUsuarioEstado").change(function () {
    //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
    $("#regUsuarioMunicipio").select2("val", 0);
    $.ajax({ //Se carga la lista de los tipos de servicio en la lista desplegable
        url: "modules/module.usuarios.php",
        type: "POST",
        data: {
            cmd: "listaMunicipiosUsuario",
            idEstadoUsuario: $("#regUsuarioEstado").val()
        },
        success: function (response) {
            $('#regUsuarioMunicipio').html(response);
        }
    });

});


$("#editUsuarioEstado").change(function () {
    //Se usa el evento change en la lista de estados para que dependiendo del estado seleccionado se haga un filtrado de municipios en la lista de municipios
    $("#editUsuarioMunicipio").select2("val", 0);
    $.ajax({ //Se carga la lista de los tipos de servicio en la lista desplegable
        url: "modules/module.usuarios.php",
        type: "POST",
        data: {
            cmd: "listaMunicipiosUsuario",
            idEstadoUsuario: $("#editUsuarioEstado").val()
        },
        success: function (response) {
            $('#editUsuarioMunicipio').html(response);
        }
    });



});