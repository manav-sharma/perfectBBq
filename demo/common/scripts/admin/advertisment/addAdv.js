$(function() { 
		$("input").on('click', '', function() { 
			var inputFieldFormError="."+$(this).attr('id')+'formError'; 
			$(inputFieldFormError).fadeOut(150, function() {  
			   $(this).remove();
			});
		}); 
		
		$(frmAdv).validationEngine('attach',{
		autoHidePrompt:true, 
		autoHideDelay: 5000});
 
		$( "#datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			onSelect: function(dateText) {   
				$(".datepickerformError").fadeOut(150, function() {  
				   $(this).remove();
				});
			}
		});
		$( "#datepicker" ).datepicker( "option", "showAnim", "clip" ); 
		
		$( "#hide-option" ).tooltip({
			show: null,
			hide: {
				effect: "explode",
				delay: 250
			},
			position: {
				my: "left top",
				at: "left bottom"
			},
			open: function( event, ui ) {
				ui.tooltip.animate({ top: ui.tooltip.position().top + 10 }, "fast" );
			}
		});
});

 function validateVideoType(field, rules, i, options) {
	 var file = field.val().toUpperCase();
	 var extension = file.substring(file.lastIndexOf('.') + 1).toUpperCase(); 
	 //alert(extension);
	 if (extension == "PNG" || extension == "JPG" || extension == "JPEG" || extension == "GIF") {
		return true;
	 } else {
		return "Invalid file type.";
	 } 
}

	
/* $.validationEngineLanguage = $.validationEngineLanguage || {}; */
$.validationEngineLanguage.allRules = $.extend($.validationEngineLanguage.allRules,{ 
					"onlyLetterSp": {
						"regex": /^[a-zA-Z\ \']+$/,
						"alertText": "* Letters only"
					},
					"onlyNumber": {
						"regex": /^[\d ]+$/,
						"alertText": "* Number  only"
					},
					"onlyNumberFloat": {
						"regex": /[+-]?([0-9]*[.])?[0-9]+$/,
						"alertText": "* Number  only"
					},
					 "checkTitleEngExist": {
						// remote json service location
						"url": siteurl+"advertisment/checkTitleEngExist",
						// error
						"alertText": "* This title is already exist!",
						// if you provide an "alertTextOk", it will show as a green prompt when the field validates
						"alertTextOk": "* This title is available",
						// speaks by itself
						"alertTextLoad": "* Validating, please wait"

					},
					"checkTitleDutchExist": {
						// remote json service location
						"url": siteurl+"advertisment/checkTitleDutchExist",
						// error
						"alertText": "* This title is already exist!",
						// if you provide an "alertTextOk", it will show as a green prompt when the field validates
						"alertTextOk": "* This title is available",
						// speaks by itself
						"alertTextLoad": "* Validating, please wait"

					}
					
	});	

