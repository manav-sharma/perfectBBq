$(function () {
    $("#datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm-dd',
        onSelect: function (dateText) {
            $(".datepickerformError").fadeOut(150, function () {
                $(this).remove();
            });
        }
    });
    $("#datepicker").datepicker("option", "showAnim", "clip");

    $("#hide-option").tooltip({
        show: null,
        hide: {
            effect: "explode",
            delay: 250
        },
        position: {
            my: "left top",
            at: "left bottom"
        },
        open: function (event, ui) {
            ui.tooltip.animate({top: ui.tooltip.position().top + 10}, "fast");
        }
    });

});



/* $.validationEngineLanguage = $.validationEngineLanguage || {}; */
$.validationEngineLanguage.allRules = $.extend($.validationEngineLanguage.allRules, {
    "onlyLetterSp": {
        "regex": /^[a-zA-Z\ \']+$/,
        "alertText": "* Letters only"
    },
    "onlyNumber": {
        "regex": /^[\d ]+$/,
        "alertText": "* Number  only"
    }
});
$(document).ready(function () {
    $('#cmbCountry').on('change', function () {
        var c = this.value;
        $.ajax({//create an ajax request to load_page.php
            type: "POST",
            url: baseurl + "admin/users/getStateList",
            dataType: "html", //expect html to be returned
            data: {
                val: c
            },
            success: function (response) {
                //alert(response);
                $("#cmbState").html(response);

            },
        });

    });

    $('#cmbState').on('change', function () {
        var c = this.value;
        $.ajax({//create an ajax request to load_page.php
            type: "POST",
            url: baseurl + "admin/users/getCityList",
            dataType: "html", //expect html to be returned
            data: {
                val: c
            },
            success: function (response) {
                //alert(response);
                $("#cmbCity").html(response);

            },
        });

    });
});

$(document).ready(function () {
    $("input").on('click', '', function () {
        var inputFieldFormError = "." + $(this).attr('id') + 'formError';
        $(inputFieldFormError).fadeOut(150, function () {
            $(this).remove();
        });
    });

    $('#frmAdvertiser').validationEngine('attach', {
        autoHidePrompt: true,
        autoHideDelay: 5000});
});  
