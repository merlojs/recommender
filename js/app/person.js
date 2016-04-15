$(document).ready(function () {
    ocultarMensaje();
    cargarGrilla();
    fechas();
    ajaxSuggestByName();
});

function fechas() {
    $(".fecha").datepicker({
        dateFormat: 'mm/dd/yy',
        changeYear: true,
        changeMonth: true,
        yearRange: "1900:+nn",
        /*
         * Disable Sundays
         * 
         * beforeShowDay: function(date) {
         var day = date.getDay();
         return [(day != 0), ''];
         },
         */

        firstDay: 0,
        dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
        dayNamesMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
    });
}

function cargarGrilla(pagina, mensajeShow) {
    pagina = typeof pagina !== 'undefined' ? pagina : 1;
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    $.ajax({
        type: "POST",
        url: "person.php",
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
            url: "person.php",
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
    $('#PersonLastname').val('');
    $('#PersonFirstname').val('');
    $('#BirthDate').val('');
    $('#PersonImageLink').val('');
    $('#countryId').val('');

    $('.error').removeClass('error success');
    $('.success').removeClass('success');
    $("#formulario span").remove();
}

function llenarFormulario(id) {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "person.php",
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
            $('#PersonLastname').val(data.PersonLastname);
            $('#PersonFirstname').val(data.PersonFirstname);
            $('#BirthDate').val(data.BirthDate);
            $('#PersonImageLink').val(data.PersonImageLink);
            $('#countryId').val(data.countryId);

            /* Set image attributes */
            $('#personImg').attr("src", data.PersonImageLink);
            $('#personImg').attr("alt", data.PersonFirstname + data.PersonLastname);
            $('#personImg').attr("title", data.PersonFirstname + data.PersonLastname);

        }
    });
}

function save() {
    if ($("#formulario").valid()) {
        var id = $('#id').val();
        var PersonLastname = $('#PersonLastname').val();
        var PersonFirstname = $('#PersonFirstname').val();
        var BirthDate = $('#BirthDate').val();
        var PersonImageLink = $('#PersonImageLink').val();
        var countryId = $('#countryId').val();

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "person.php",
            data: {
                id: id,
                PersonLastname: PersonLastname,
                PersonFirstname: PersonFirstname,
                BirthDate: BirthDate,
                PersonImageLink: PersonImageLink,
                countryId: countryId,
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
                    ocultarMensaje();
                } else {
                    mostrarMensaje("An error occurred while saving your record: " + data.error, 0);
                }
            }
        });

    }
}

function ajaxSuggestByName() {

    $("#personTextSearch").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "ajax/liveSearchAjax.php",
                dataType: "json",
                data: {
                    term: request.term,
                    controllerAction: "nameSuggest"
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
            $("#personTextSearch").val(ui.item.value);
        }
    });
}

function searchByName() {

    var pagina = typeof pagina !== 'undefined' ? pagina : 1;
    var mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    var controllerAction = 'nameSearch';
    var name = $("#personTextSearch").val();

    if (name !== '') {

        $.ajax({
            type: "POST",
            url: "person.php",
            data: {
                name: name,
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