$(document).ready(function () {
    ocultarMensaje();
    $('#btnSubmit').hide();
    $('#Password').change(function(){ verificaPass(); });
    $('#PasswordConfirm').change(function(){ verificaPass(); });
});

function checkAvailableUser() {
    var username = $('#Username').val();

    $.ajax({
        type: "POST",
        url: "userSignup.php",
        data: {
            username: username,
            controllerAction: 'checkAvailableUser'
        },
        beforeSend: function () {            
                mostrarMensaje("Loading...", 2);            
        },
        success: function (data) {
            ocultarMensaje();
            //if the result is 1  
            if (data == 'SI') {
                //show that the username is available  
                $('#checkOk').show();
                $('#checkKo').hide();
                $('#checkUser').val(1);
                $('#btnSubmit').show();
            } else {
                $('#checkKo').show();
                $('#checkOk').hide();
                $('#checkUser').val('');
                //show that the username is NOT available  
            }
        }
    });
}

function verificaPass(){
    var p1 = $('#Password').val();
    var p2 = $('#PasswordConfirm').val();
    if((p1 != '') && (p2 != '') && (p1 != p2)){
        alert('Check your password!');
    }
}

function save() {
    if ($("#formulario").valid()) {
        var id = $('#id').val();
        var username = $('#Username').val();
        var password = $('#Password').val();
        var userLastname = $('#UserLastname').val();
        var userFirstname = $('#UserFirstname').val();            
        var profileCode = $('#profileCode').val();   

        $.ajax({
            async: false,
            type: "POST",
            dataType: "json",
            url: "userSignup.php",
            data: {
                id: id,
                username: username,
                password: password,
                userLastname: userLastname,
                userFirstname: userFirstname,                
                profileCode: profileCode,                
                controllerAction: 'save'
            },
            beforeSend: function () {
                mostrarMensaje("Loading...", 2);
            },
            success: function (data) {
                if (data.result) {
                    mostrarMensaje("Record was saved successfully", 1);
                    $('#btnSubmit').hide();
                } else {
                    mostrarMensaje("An error occurred while saving your record: " + data.error, 0);
                }
            }
        });
    } else {
        alert('Missing Required Fields or User Ivalid!');
    }
}

