$(document).ready(function(){

$('#txtOldPassword').keypress(function(e) {
    if(e.which == 13) { // Checks for the enter key
        e.preventDefault(); // Stops IE from triggering the button to be clicked
    }
});

$('#txtNewPassword').keypress(function(e) {
    if(e.which == 13) { // Checks for the enter key
        e.preventDefault(); // Stops IE from triggering the button to be clicked
    }
});

$('#txtConfirmNewPassword').keypress(function(e) {
    if(e.which == 13) { // Checks for the enter key
        e.preventDefault(); // Stops IE from triggering the button to be clicked
    }
});

});
