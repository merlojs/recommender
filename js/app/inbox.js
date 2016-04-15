$(document).ready(function() {
    ocultarMensaje();
    cargarGrilla();
});


function cargarGrilla(pagina, mensajeShow) {
    pagina = typeof pagina !== 'undefined' ? pagina : 1;
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    $.ajax({
        type: "POST",
        url: "inbox.php",
        data: {
            pagina: pagina,
            controllerAction: 'listar'
        },
        beforeSend : function (){
            if (mensajeShow == true) {
                mostrarMensaje("Loading...", 2);
            }
            },
        success: function(data) {
                if (mensajeShow == true) {
                    ocultarMensaje();
                }
                $("#resultados").html(data);
                paginar();
            }
        });
}

function read(id) {
    $.ajax({
        type: "POST",
        url: "ajax/messageAjax.php",
        async: false,
        data: {
            messageId: id,
            action: 'read'
        },
        beforeSend: function () {
            //mostrarMensaje("Loading...",2);
        },
        success: function (data) {
            //ocultarMensaje();
            if (data == 'ok') {
                window.location = "inbox.php";
            } else {
                mostrarMensaje("An error occurred while reading your message", 0);
            }

        }
    });
}