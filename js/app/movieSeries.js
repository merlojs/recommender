$(document).ready(function () {
    ocultarMensaje();
    cargarGrilla();
    ajaxSuggestByTitle();
});

function cargarGrilla(pagina, mensajeShow) {
    pagina = typeof pagina !== 'undefined' ? pagina : 1;
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
   
    
    $.ajax({
        type: "POST",
        url: "movieSeries.php",
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
            //alert(data);
            $("#resultados").html(data);
            paginar();
        }
    });
}

function editar(id) {
    $('#form_titulo').html('Edit');
    limpiarCampos();
    llenarFormulario(id);
    abrirPopUp();
}

function editCast(id) {
    $('.overlay').css({'z-index':'1'});
    $('#idMovie').val(id);    
    $("#popUpPerformer").show();
    $("#popUpPerformer").css({'z-index':'2'});
    $('#popUpPerformer').fadeIn(500);
    $('.overlay').fadeIn(400);
    $("#perfomerDetails").hide();
    loadCast(id);
}

function loadCast(idMovie) {
    $('#idPerformer').val('');
    $('#idMoviePerformer').val('');
    pagina = typeof pagina !== 'undefined' ? pagina : 1;
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    $.ajax({
        type: "POST",
        url: "performer.php",
        data: {
            idMovie: idMovie,
            controllerAction: 'listar'
        },
        beforeSend: function () {
            mostrarMensaje("Loading...", 2);
        },
        success: function (data) {
            ocultarMensaje();
            $("#resultadosCast").html(data);
        }
    });
}

function cerrarPopUpPerformer() {
    $("#castLiveSearch").show();
    $("#popUpPerformer").hide();
    $('#popUpPerformer').fadeOut();
    $('.overlay').fadeOut();
}

function saveCast() {
    var idMovie = $('#idMovie').val();
    var id = $('#idPerformer').val();
    var performerType = $('#performerType').val();
    var personId = $('#personId').val();
    $.ajax({
        type: "POST",
        dataType: "json",
        async: false,
        url: "performer.php",
        data: {
            id: id,
            idMovie: idMovie,
            performerType: performerType,
            personId: personId,
            controllerAction: 'save'
        },
        beforeSend: function () {
            mostrarMensaje("Saving...", 2);
        },
        success: function (data) {
            if (data.result) {
                mostrarMensaje("Record was saved successfully", 1);
            } else {
                mostrarMensaje("An error occurred while saving your record: " + data.error, 0);
            }
        }
    });
    $("#perfomerDetails").hide();
    $("#castLiveSearch").show();
    loadCast(idMovie);
}

function editPerformer(id) {
    $("#perfomerDetails").show();
    $("#castLiveSearch").hide();
    fillPerformerDetails(id);
}

function deletePerformer(id) {
    /*alert('To Do!');*/
    $('#idPerformer').val(id);
    var idMovie = $('#idMovie').val();
    $.ajax({
        type: "POST",
        dataType: "json",
        async: false,
        url: "performer.php",
        data: {
            id: id,
            controllerAction: 'delete'
        },
        beforeSend: function () {
            mostrarMensaje("Deleting...", 2);
        },
        success: function (data) {
            if (data.result) {
                mostrarMensaje("Record was delete successfully", 1);
            } else {
                mostrarMensaje("An error occurred while deleting your record: " + data.error, 0);
            }
        }
    });
    loadCast(idMovie);
}

function eliminar(id) {
    if (confirm('Are you sure you want to delete this title?')) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "movieSeries.php",
            data: {
                id: id,
                controllerAction: 'delete'
            },
            beforeSend: function () {
                mostrarMensaje("Loading...",2);
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
    $('#originalTitle').val('');
    $('#MovieSeriesFlag').val('');    
    $('#ImdbLink').val('');
    $('#Year').val(0);
    $('#MovieImageLink').val('');
    $('#TrailerLink').val('');
    $('#Seasons').val('');
    $('#SeriesFinishedFlag').val('');
    $('.error').removeClass('error success');
    $('.success').removeClass('success');
    $("#formulario span").remove();
}

function llenarFormulario(id) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "movieSeries.php",
        data: {
            id: id,
            controllerAction: 'edit'
        },
        beforeSend: function () {
            mostrarMensaje("Loading...", 2);
        },
        success: function (data) {
            ocultarMensaje();
            $('#id').val(data.id);
            $('#originalTitle').val(data.OriginalTitle);            
            $('#MovieSeriesFlag').val(data.MovieSeriesFlag);
            $('#GenreId').val(data.GenreId);
            $('#countryId').val(data.countryId);
            $('#ImdbLink').val(data.ImdbLink);
            $('#Year').val(data.Year);
            $('#MovieImageLink').val(data.MovieImageLink);
            $('#TrailerLink').val(data.TrailerLink);
            $('#Seasons').val(data.Seasons);
            $('#SeriesFinishedFlag').val(data.SeriesFinishedFlag);
        }
    });
}


function fillPerformerDetails(id) {

    $.ajax({
        type: "POST",
        dataType: "json",
        async: false,
        url: "performer.php",
        data: {
            id: id,
            controllerAction: 'edit'
        },
        beforeSend: function () {
            mostrarMensaje("Loading...", 2);
        },
        success: function (data) {
            ocultarMensaje();
            $('#idPerformer').val(id);
            $('#performerType').val(data.performerType);
            $('#performerFullName').val(data.performerLastname + ', ' + data.performerFirstname);
            $('#personId').val(data.personId);
        }
    });
}

function save() {
    if ($("#formulario").valid()) {
        var id = $('#id').val();
        var OriginalTitle = $('#originalTitle').val();
        var GenreId = $('#GenreId').val();
        var countryId = $('#countryId').val();
        var MovieSeriesFlag = $('#MovieSeriesFlag').val();        
        var ImdbLink = $('#ImdbLink').val();
        var Year = $('#Year').val();
        var MovieImageLink = $('#MovieImageLink').val();
        var TrailerLink = $('#TrailerLink').val();
        var Seasons = $('#Seasons').val();
        
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "movieSeries.php",
            data: {
                id: id,
                OriginalTitle: OriginalTitle,
                GenreId: GenreId,
                countryId: countryId,
                MovieSeriesFlag: MovieSeriesFlag,                
                ImdbLink: ImdbLink,
                Year: Year,
                MovieImageLink: MovieImageLink,
                TrailerLink: TrailerLink,
                Seasons: Seasons,                
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

function ajaxSuggestByTitle() {

    $("#movieTextSearch").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "ajax/liveSearchAjax.php",
                dataType: "json",
                data: {
                    term: request.term,
                    controllerAction: "titleSuggest"
                },
                beforeSend: function () {
                },
                success: function (data) {
                    response(data);
                }
            });
        },
        minLength: 2,
        delay: 400,
        select: function (event, ui) {
            $("#movieTextSearch").val(ui.item.value);
        }
    });
}

function searchByTitle() {

    var pagina = typeof pagina !== 'undefined' ? pagina : 1;
    var mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    var controllerAction = 'titleSearch';
    var title = $("#movieTextSearch").val();
    
    if(title !== ''){

        $.ajax({
            type: "POST",
            url: "movieSeries.php",
            //dataType: "json",
            data: {
                title: title,
                controllerAction: controllerAction,
                pagina: pagina
            },
            beforeSend: function () {
                mostrarMensaje("Loading...", 2);

            },
            success: function (data) {            
                ocultarMensaje();                 
                $("#resultados").html(data);
                paginar();
            }
        });
    } else {
        alert("Text field should not be empty");
    }
}

    
