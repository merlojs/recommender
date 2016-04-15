$(document).ready(function() {
    ocultarMensaje();
    cargarGrilla();
});


function cargarGrilla(pagina, mensajeShow) {
    pagina = typeof pagina !== 'undefined' ? pagina : 1;
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    var idMovie = $('#idMovie').val();
    $.ajax({
        type: "POST",
        url: "performer.php",
        data: {
            pagina: pagina,
            idMovie : idMovie,
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

function editar(id){
    $('#form_titulo').html('Edicion');
    limpiarCampos();
    llenarFormulario(id);
    abrirPopUp();
}

function eliminar(id){
    if (confirm('Are you sure you want to delete this record?')) {
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "performer.php",
            data: {
                id: id,
                controllerAction: 'delete'
            },
            beforeSend : function (){
                    //mostrarMensaje("Loading...",2);
                },
            success: function(data) {
                    //ocultarMensaje();
                    if (data.result){
                        recargarGrilla(false);
                        mostrarMensaje("Record was successfully deleted",1);
                    }else{
                        mostrarMensaje("An error occurred while saving your record: " + data.error,0);
                    }

                }
            });


    }
}

function limpiarCampos(){
    $('#id').val();
    $('#performerType').val(''); 
    $('#personId').val(''); 

    $('.error').removeClass('error success');
    $('.success').removeClass('success');
    $("#formulario span").remove();
}

function llenarFormulario(id){
    $.ajax({
    type: "POST",
    dataType: "json",
    url: "performer.php",
    data: {
        id: id,
        controllerAction: 'edit'
    },
    beforeSend : function (){
            //mostrarMensaje("Loading...",2);
        },
    success: function(data) {
            //ocultarMensaje();
            $('#id').val(data.id);
            $('#performerType').val(data.performerType);        }
    });
}

function save(){
    if ($("#formulario").valid() ){
        var id = $('#id').val();
        var performerType = $('#performerType').val(); 
        var personId = $('#personId').val(); 
        var idMovie = $('#idMovie').val(); 
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "performer.php",
            data: {
                id: id,
                idMovie: idMovie,
                performerType: performerType,
                personId : personId,
                controllerAction: 'save'
            },
            beforeSend : function (){
                    //$(".cargando").show();
                    mostrarMensaje("Loading...",2);
                },
            success: function(data) {
                    if (data.result){
                        closeForm();
                        recargarGrilla(false);
                        mostrarMensaje("Record was saved successfully",1);
                    }else{
                        mostrarMensaje("An error occurred while saving your record: " + data.error,0);
                    }
                }
            });

    }
}

