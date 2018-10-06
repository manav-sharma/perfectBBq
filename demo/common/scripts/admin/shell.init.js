$(document).ready(function () {

    $.preloadCssImages();

    $('body img').imgpreload();

    $('.logoHome').click(function () {
        $(location).attr('href', './');
    });

    // filter by enter in input filed
    $('.filtering').keydown(function (event) {
        if (event.keyCode == '13') {
            $(this).parents("form").attr("action", "filtering");
            $(this).parents("form").submit();
        }
    });


    //Sorting
    $('.sorting').click(function () {
        $(this).submit()
    });

    //Select All
    $('#selectAll').click(function () {
        $('.selectRow').prop("checked", true);
        $('.selectRow').parent().parent().addClass('selected');
    });
    $('#selectNone').click(function () {
        $('.selectRow').prop("checked", false);
        $('.selectRow').parent().parent().removeClass('selected');
    });

    $('.selectRow').click(function () {
        if ($(this).attr("checked")) {
            $(this).parent().parent().addClass('selected');
        } else {
            $(this).parent().parent().removeClass('selected');
        }
    });

    // Default Value
    $(".defaultValue").each(function () {
        if ($(this).attr("value") == $(this).attr("default") || $(this).attr("value") == '')
            $(this).attr("value", $(this).metadata().data);
    });
    $(".defaultValue").click(function () {
        $(this).attr("value", "");
    });
    $(".defaultValue").blur(function () {
        if ($(this).attr("value") == $(this).attr("default") || $(this).attr("value") == '')
            $(this).attr("value", $(this).metadata().data);
    });


    // Tabs
    $('#leftMenu li').click(function () {
        var tabObj = $(this);
        $('#pageContent .column2').each(function () {
            $(this).addClass("hidden");
        });
        $('#leftMenu li').each(function () {
            $(this).removeClass("active");
        });

        $("#" + tabObj.metadata().tab).removeClass("hidden");
        tabObj.addClass("active");
    });

    // Toggle Field
    $('.toggleRadio').click(function () {
        var fieldName = $(this).attr("name");
        $('.tf_' + fieldName).addClass("hidden");
        var fieldId = "#" + $(this).metadata().data;
        $(fieldId).removeClass("hidden");
    });

    // OK Button in BlockUI Message
    $('#okBtn').click(function () {
        $.unblockUI();
    });

    // Combobox Editor
    $(".applyEdr").each(function () {
        var cmbName = $(this).attr("name");

        /** Whene click on add icon **/
        $("#emb_" + cmbName + " .add").click(function () {
            $(this).parent("div").siblings(".operation").removeClass("hidden");
            $(this).parent("div").siblings(".operation").find("[name=txtEditor]").addClass("add").val("");
            $(this).parent("div").addClass("hidden");
            $("[name=" + cmbName + "]").attr("disabled", "disabled").addClass("editMode");
        });

        /** Whene click on edit icon **/
        $("#emb_" + cmbName + " .edit").click(function () {
            if ($("[name=" + cmbName + "]").val() != '')
            {
                $(this).parent("div").siblings(".operation").removeClass("hidden");
                //if(getQueryValue('l') != '' || getQueryValue('l') != 'EN')
                //	$(this).parent("div").siblings(".operation").find("[name=txtEditor]").addClass("edit").val($("[name="+cmbName+"] option:selected").attr("title"));
                //	else
                $(this).parent("div").siblings(".operation").find("[name=txtEditor]").addClass("edit").val($("[name=" + cmbName + "] option:selected").text());
                $(this).parent("div").addClass("hidden");
                $("[name=" + cmbName + "]").attr("disabled", "disabled").addClass("editMode");
            } else
                appMessage(INLIN_CMB_MESSAGE01, "neg");
        });

        /** Whene click on delete icon **/
        $("#emb_" + cmbName + " .delete").click(function () {

            var fieldId = $("[name=" + cmbName + "]").val();
            if (fieldId != '')
            {
                var submitURL = "index.php?m=" + getQueryValue('m');
                if (getQueryValue('a') != '')
                    submitURL = submitURL + "&a=" + getQueryValue('a');
                else
                    submitURL = submitURL + "index";

                if (confirm(INLIN_CMB_MESSAGE02))
                {
                    var thisobj = $(this);
                    $(this).siblings(".preLd").css({"display": ""});
                    $.ajax({
                        type: "POST",
                        url: submitURL + ".ajax",
                        dataType: "script",
                        data: cmbName + "Value=" + fieldId + "&action=delete&fieldName=" + cmbName,
                        success: function (msg) {
                            thisobj.siblings(".preLd").css({'display': 'none'});
                            $("[name=" + cmbName + "]").attr("disabled", "").removeClass("editMode");
                        }
                    });
                }
            } else
                appMessage(INLIN_CMB_MESSAGE01, "neg");
        });

        /** Whene click on cancle icon **/
        $("#emb_" + cmbName + " .cancel").click(function () {
            $(this).parent("div").siblings(".operator").removeClass("hidden");
            $(this).siblings("[name=txtEditor]").val("");
            $(this).parent("div").addClass("hidden");
            $("[name=" + cmbName + "]").attr("disabled", "").removeClass("editMode");
        });

        /** Whene click on go button **/
        $("#emb_" + cmbName + " .goBtn").click(function () {
            var thisobj = $(this);
            var fieldValue = $(this).siblings("[name=txtEditor]").val();
            var fieldId = $("[name=" + cmbName + "]").val();
            if ($(this).siblings("[name=txtEditor]").hasClass("add"))
                action = "add";
            else if ($(this).siblings("[name=txtEditor]").hasClass("edit"))
                action = "edit";
            var submitURL = "index.php?m=" + getQueryValue('m');
            if (getQueryValue('l') != '')
                submitURL = submitURL + "&l=" + getQueryValue('l');
            if (getQueryValue('a') != '')
                submitURL = submitURL + "&a=" + getQueryValue('a');
            else
                submitURL = submitURL + "index";
            $(this).siblings(".preLd").css({"display": ""});
            $.ajax({
                type: "POST",
                url: submitURL + ".ajax",
                data: cmbName + "Text=" + fieldValue + "&" + cmbName + "Value=" + fieldId + "&action=" + action + "&fieldName=" + cmbName,
                dataType: "script",
                success: function (msg) {
                    thisobj.siblings(".preLd").css({'display': 'none'});
                    thisobj.siblings("[name=txtEditor]").attr("value", "");
                    thisobj.parent("div").siblings(".operator").removeClass("hidden");
                    thisobj.parent("div").addClass("hidden");
                    $("[name=" + cmbName + "]").attr("disabled", "").removeClass("editMode");
                }
            });
        });
    });

    $('input').keyup(function (event) {
        if (event.which == 13) {
            $(this).parents('form').submit();
        }
    });

    /*  	tinyMCE.init({
     plugins : '-example', // - tells TinyMCE to skip the loading of the plugin
     mode : "specific_textareas",
     editor_selector : "mceditor",
     theme : "advanced",
     theme_advanced_buttons1 : "mymenubutton,bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink",
     theme_advanced_buttons2 : "",
     theme_advanced_buttons3 : "",
     theme_advanced_toolbar_location : "top",
     theme_advanced_toolbar_align : "left",
     theme_advanced_statusbar_location : "bottom",
     theme_advanced_path : false
     }); */


    /* MY Changes */




    /*  Start Listing record sorting */

    $(".radioSubmitClass").click(function (e) {
        e.preventDefault();
        $("#" + $(this).attr("for")).click().change();
        $("#filterOrSort").attr("value", "sort");
        $(formId).submit();
    });

    /*  End Listing record sorting */


    /* Start Listing from action */

    $('#cmbActions').on('change', function () {
        var vale = $(this).val();
        if ($(".selectRow:checked").length < 1) {
            switch (vale) {
                case 'delete':
                case 'active':
                case 'inactive':
                    $("#cmbActions option[selected]").removeAttr("selected");
                    $("#cmbActions option[value='']").attr("selected", "selected");
                    appMessage('You have to select some  ' + $(this).attr('name') + '(s)' +' before any action can be performed on them.', 'neg');
                    break;
            }
            return false;
        } else if (vale == 'delete' && !confirm('Are you sure you want to delete selected ' + $(this).attr('name') + '(s)?')) {
            $("#cmbActions option[selected]").removeAttr("selected");
            $("#cmbActions option[value='']").attr("selected", "selected");
            return false;
        } else {

            var valee = $(formId).attr("name") + '/' + vale;
            var formurl = siteurl + valee;
            $(formId).attr("action", formurl);
            $(formId).attr("method", 'POST');
            $(formId).submit();
            return true;
        }
    });

    /* END Listing from action */

    /* Start from modification action like add,edit ,delete etc.*/

    $('.selActions').change(function () {
        var vale = $(this).val();
        if (vale.length != 0)
        {
            if (vale == 'delete' && !confirm('Are you sure you want to delete selected ' + $(this).attr('name') + '(s)?')) {
                $(".selActions option[selected]").removeAttr("selected");
                $(".selActions option[value='']").attr("selected", "selected");
                return false;
            }
            var id = $(this).attr("id");
            /* var valee = 'users/'+vale;   */
            var valee = $(formId).attr("name") + '/' + vale;
            var uri = siteurl + valee;
            var editu = uri + "/" + id;
            $(location).attr('href', editu);
            return true;
        } else
        {
            return false;
        }
    });

    /* END from modification action like add,edit ,delete etc.*/

	/* Start paging related function */
	 $("#pagingList").change(function(){		
		$(formId).submit();
	 });
	 
	$("#pagingItemsPerPage").change(function(){
        $("#pagingList").val("1");
        $(formId).submit();
    });
    $("#prevPage").click(function(){
        valPre=$("#prevPage").attr("for");		
        $("#pagingList").val(valPre);
         $(formId).submit();
    });
    $("#nextPage").click(function(){
        valNxt = $("#nextPage").attr("for");
		//alert(valPre);
        $("#pagingList").val(valNxt);
        $(formId).submit();
    });

    /* END paging related function */

});

/* Start Call jGrowl method for notification  */

function appMessage(msg, type) {
    if (type == "neg") {
        $.jGrowl(msg, {header: 'Error!', life: 114000});
    }

    if (type == "pos") {
        $.jGrowl(msg, {header: 'Success!', life: 4000});
    }

    if (type == "pre") {
        $('.alertBox p').html(msg);
        $('.alertBox h2').html("Please Wait!");

        $('.alertBox').removeClass('preloader positive negative');
        $('.alertBox').addClass('preloader');

        $('#okBtn').removeClass("btn");
        $('#okBtn').addClass("hidden");

        $.blockUI({
            message: $('.alertBox'),
            css: {width: '400px', height: 'auto', cursor: 'inherit', border: 'none', background: 'none', 'text-align': 'left'}
        });
    }
    if (type == "prepos") {
        $('.alertBox p').html(msg);
        $('.alertBox h2').html("Success!");

        $('.alertBox').removeClass('preloader positive negative');
        $('.alertBox').addClass('positive');

        $('#okBtn').addClass("btn");
        $('#okBtn').removeClass("hidden");
    }
    if (type == "preneg") {
        $('.alertBox p').html(msg);
        $('.alertBox h2').html("Success!");

        $('.alertBox').removeClass('preloader positive negative');
        $('.alertBox').addClass('positive');

        $('#okBtn').addClass("btn");
        $('#okBtn').removeClass("hidden");
    }
}

/* END Call jGrowl method for notification  */

function setWarningMessage(type, message) {
    $('form .warning').removeClass('hidden');
    $('form .warning').addClass(type);
    $('form .warning').html(message);
}

