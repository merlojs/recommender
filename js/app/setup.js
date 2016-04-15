$(document).ready(function () {
    $.validator.messages.required = '* required field';
    $('#dbUser').change(function(){ checkValidUsername(); });    
});

function checkValidUsername() {
    var username = $('#dbUser').val();
    
    if (username.toUpperCase() == 'ROOT'){
        alert("Your username cannot be ROOT. Please Choose a different one");
        $('#dbUser').val('');
    }
}

