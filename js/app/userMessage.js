$(document).ready(function() {
    ocultarMensaje();
    cargarGrilla();
});


function cargarGrilla(pagina, mensajeShow) {
    pagina = typeof pagina !== 'undefined' ? pagina : 1;
    mensajeShow = typeof mensajeShow !== 'undefined' ? mensajeShow : true;
    $.ajax({
        type: "POST",
        url: "userMessage.php",
        data: {
            pagina: pagina,
            controllerAction: 'previewList'
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
            url: "userMessage.php",
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
    $('#id').val(0);
    $('#MessageDate').val(''); 
$('#SenderId').val(0); 
$('#RecipientId').val(0); 
$('#MessageText').val(''); 

    $('.error').removeClass('error success');
    $('.success').removeClass('success');
    $("#formulario span").remove();
}

function llenarFormulario(id){
    $.ajax({
    type: "POST",
    dataType: "json",
    url: "userMessage.php",
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
            $('#MessageDate').val(data.MessageDate); 
$('#SenderId').val(data.SenderId); 
$('#RecipientId').val(data.RecipientId); 
$('#MessageText').val(data.MessageText); 


        }
    });
}

function save(){
    if ($("#formulario").valid() ){
        id = $('#id').val();
        MessageDate = $('#MessageDate').val(); 
SenderId = $('#SenderId').val(); 
RecipientId = $('#RecipientId').val(); 
MessageText = $('#MessageText').val(); 

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "userMessage.php",
            data: {
                id: id,
                MessageDate: MessageDate, 
SenderId: SenderId, 
RecipientId: RecipientId, 
MessageText: MessageText, 

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

