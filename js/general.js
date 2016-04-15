$(document).keydown(function (e) {
    // ESCAPE key pressed
    if (e.keyCode == 27) {
        cerrarPopUpLogin();
        cerrarPopUpInbox();
    }
});

function abrirPopUp() {
    $("#popUpDatos").show();
    $('#popUpDatos').fadeIn(500);
    $('.overlay').fadeIn(400);
}


function cerrarPopUp() {
    $("#popUpDatos").hide();
    $('#popUpDatos').fadeOut();
    $('.overlay').fadeOut();
}


function paginar() {
    $("#paginador_desde").html($("#resultado_desde").val());
    $("#paginador_hasta").html($("#resultado_hasta").val());
    $("#paginador_totales").html($("#resultado_cantidadTotal").val());
    $("#paginador_paginas").html("");
    for (var i = 1; i <= $("#resultado_paginasTotales").val(); i++)
    {
        if ($("#resultado_pagina").val() == i)
            $("#paginador_paginas").append("<div class='pagina_button btnRojo width_25' onclick='irPagina(" + i + ")'><a href='#'>" + i + "</a></div>");
        else
            $("#paginador_paginas").append("<div class='pagina_button btnGris width_25' onclick='irPagina(" + i + ")'><a href='#'>" + i + "</a></div>");
        //$("#paginador_paginas").append("<a href='#' onclick='irPagina("+i+")' tabindex='0' class='paginate_button'>"+i+"</a>");

    }
}

function irPagina(numero, mensajeShow) {
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    cargarGrilla(numero, mensajeShow);
}

function irPaginaAnterior() {
    if ($("#resultado_pagina").val() - 1 > 0) {
        cargarGrilla($("#resultado_pagina").val() - 1);
    }
}
function irPaginaSigiente() {
    if ($("#resultado_pagina").val() < $("#resultado_paginasTotales").val()) {
        cargarGrilla(parseInt($("#resultado_pagina").val(), 10) + 1);
    }
}

function irPaginaUltima() {
    cargarGrilla($("#resultado_paginasTotales").val());
}

function recargarGrilla(mensajeShow) {
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    irPagina($("#resultado_pagina").val(), mensajeShow);
}


function nuevo() {
    $('#form_titulo').html('Alta');
    limpiarCampos();
    abrirPopUp();
}

function closeForm() {
    cerrarPopUp();
    limpiarCampos();
}



function mostrarMensaje(texto, tipo) {
    $('#mensaje span').html(texto);
    $('#mensaje').removeClass("m_green m_red m_yellow");
    if (tipo == 1)
        $('#mensaje').addClass("m_green");
    if (tipo == 0)
        $('#mensaje').addClass("m_red");
    if (tipo == 2)
        $('#mensaje').addClass("m_yellow");
    $('#mensaje').fadeIn();
}

function ocultarMensaje() {
    $('#mensaje').fadeOut();
}

function abrirPopUpLogin() {
    
    $("#loginUsername").val('');
    $("#loginPassword").val (''); 
    
    
    $("#popUpLoginForm").show();
    $('#popUpLoginForm').fadeIn(500);
    $('.overlay').fadeIn(400);

    $(document).keydown(function (e) {
        // ESCAPE key pressed
        if ($("#loginUsername").val != '' && $("#loginPassword").val !== '' && e.keyCode == 13) {
            authenticate();
        }
    });
}

function cerrarPopUpLogin() {
    $("#popUpLoginForm").hide();
    $('#popUpLoginForm').fadeOut();
    $('.overlay').fadeOut();
}

function abrirPopUpInbox() {
    //loadInboxPreview();
    $("#popUpInbox").show();
    $('#popUpInbox').fadeIn(500);
    $('.overlay').fadeIn(400);
}


function cerrarPopUpInbox() {
    $("#popUpInbox").hide();
    $('#popUpInbox').fadeOut();
    $('.overlay').fadeOut();
}


function loadInboxPreview() {
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    $.ajax({
        type: "POST",
        url: "userMessage.php",
        data: {            
            controllerAction: 'listPreview'
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
                $("#resultadosInbox").html(data);
                paginar();
            }
        });
}

function authenticate() {
    var loginUsername = $('#loginUsername').val();
    var loginPassword = $('#loginPassword').val();

    $.ajax({
        async: false,
        type: "POST",
        url: "ajax/authenticateAjax.php",
        data: {
            loginUsername: loginUsername,
            loginPassword: loginPassword,
            controllerAction: 'validate'
        },
        beforeSend: function () {
            mostrarMensaje("Logging In...", 2);
        },
        success: function (data) {
            if (data == 'ok') {
                window.location = "../controllers/index.php";
            } else {
                alert('Incorrect Username or Password. Please try again');
            }
        }
    });
}

function volver() {
    window.location = "../controllers/index.php";
}
