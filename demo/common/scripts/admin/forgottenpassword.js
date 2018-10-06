$(document).ready(function(){
$('#txtUserName').keypress(function(e) {
    if(e.which == 13) { // Checks for the enter key
        e.preventDefault(); // Stops IE from triggering the button to be clicked
    }
});
});
