$(document).ready(function () {
    ocultarMensaje();
    cargarGrilla();
});


function cargarGrilla(pagina, mensajeShow) {
    pagina = typeof pagina !== 'undefined' ? pagina : 1;
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    $.ajax({
        type: "POST",
        url: "user.php",
        data: {
            pagina: pagina,
            controllerAction: 'listar'
        },
        beforeSend: function () {
            if (mensajeShow == true) {
                mostrarMensaje("Loading...", 2);
            }
        },
        success: function (data) {
            if (mensajeShow == true) {
                ocultarMensaje();
            }
            $("#resultados").html(data);
            paginar();
        }
    });
}

function editar(id) {
    $('#form_titulo').html('Edicion');
    limpiarCampos();
    llenarFormulario(id);
    abrirPopUp();
}

function eliminar(id) {
    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "user.php",
            data: {
                id: id,
                controllerAction: 'delete'
            },
            beforeSend: function () {
                //mostrarMensaje("Loading...",2);
            },
            success: function (data) {
                //ocultarMensaje();
                if (data.result) {
                    recargarGrilla(false);
                    mostrarMensaje("Record was successfully deleted", 1);
                } else {
                    mostrarMensaje("An error occurred while saving your record: " + data.error, 0);
                }

            }
        });


    }
}

function limpiarCampos() {
    $('#id').val(0);
    $('#Username').val('');
    $('#Password').val('');
    $('#UserLastname').val('');
    $('#UserFirstname').val('');
    $('#UserCreationDate').val('');
    $('#UserEnabledFlag').val(0);
    $('#UserModificationDate').val('');

    $('.error').removeClass('error success');
    $('.success').removeClass('success');
    $("#formulario span").remove();
}

function llenarFormulario(id) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "user.php",
        data: {
            id: id,
            controllerAction: 'edit'
        },
        beforeSend: function () {
            //mostrarMensaje("Loading...",2);
        },
        success: function (data) {
            //ocultarMensaje();
            $('#id').val(data.id);
            $('#Username').val(data.Username);
            $('#Password').val(data.Password);
            $('#UserLastname').val(data.UserLastname);
            $('#UserFirstname').val(data.UserFirstname);
            $('#UserCreationDate').val(data.UserCreationDate);
            $('#UserEnabledFlag').val(data.UserEnabledFlag);
            $('#UserModificationDate').val(data.UserModificationDate);


        }
    });
}

function save() {
    if ($("#formulario").valid()) {
        id = $('#id').val();
        Username = $('#Username').val();
        Password = $('#Password').val();
        UserLastname = $('#UserLastname').val();
        UserFirstname = $('#UserFirstname').val();
        UserCreationDate = $('#UserCreationDate').val();
        UserEnabledFlag = $('#UserEnabledFlag').val();
        UserModificationDate = $('#UserModificationDate').val();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "user.php",
            data: {
                id: id,
                Username: Username,
                Password: Password,
                UserLastname: UserLastname,
                UserFirstname: UserFirstname,
                UserCreationDate: UserCreationDate,
                UserEnabledFlag: UserEnabledFlag,
                UserModificationDate: UserModificationDate,
                controllerAction: 'save'
            },
            beforeSend: function () {
                //$(".cargando").show();
                mostrarMensaje("Loading...", 2);
            },
            success: function (data) {
                if (data.result) {
                    closeForm();
                    recargarGrilla(false);
                    mostrarMensaje("Record was saved successfully", 1);
                } else {
                    mostrarMensaje("An error occurred while saving your record: " + data.error, 0);
                }
            }
        });

    }
}

