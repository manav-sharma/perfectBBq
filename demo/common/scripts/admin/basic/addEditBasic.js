$(function() { 
		$("input").on('click', '', function() { 
			var inputFieldFormError="."+$(this).attr('id')+'formError'; 
			$(inputFieldFormError).fadeOut(150, function() {  
			   $(this).remove();
			});
		}); 
		
		$(frmBasic).validationEngine('attach',{
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
		
		//add multiple ingredient template start
		$("#addMoreIng").click(function(){
			var html = "";
			html = html + '<tr>';
			html = html + '<td><input class="textIng" type="text" size="26" name="txtIngEng[]" maxlength="60" value="" /></td>';
			html = html + '<td><input class="textIng" type="text" size="26" name="txtIngDutch[]" maxlength="60" value="" /></td>';
			html = html + '<td><a class="deleteIng" href="javascript:void(0);">Delete</a></td>';
			html = html + '</tr>';
			$("#allIng").append(html);
		});
		//add multiple ingredient template end
		
		//Delete ingredient template start
		$('body').on("click",".deleteIng",function(){
			$(this).parent().parent().remove();
		});
		//Delete ingredient template end
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

function validateTitleEng(field, rules, i, options) {
	 var fieldVal = field.val();
	 
	 if ($.trim(fieldVal) != "") {
		 $.ajax({
			url: siteurl + "basic/checkTitleEngExist/",
			//async: false,
			type: "POST",
			data: "title=" + fieldVal,
			success: function(data) {// alert(data);
				 if (data == "err") {
					return "Title already exist.";
				 } else {
					return "Title already exist.";
				 } 
			}
		})
	 }
	 //"url": siteurl+"users/checkUserAddEmail",
			
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
						"url": siteurl+"basic/checkTitleEngExist",
						// error
						"alertText": "* This title is already exist!",
						// if you provide an "alertTextOk", it will show as a green prompt when the field validates
						"alertTextOk": "* This title is available",
						// speaks by itself
						"alertTextLoad": "* Validating, please wait"

					},
					"checkTitleDutchExist": {
						// remote json service location
						"url": siteurl+"basic/checkTitleDutchExist",
						// error
						"alertText": "* This title is already exist!",
						// if you provide an "alertTextOk", it will show as a green prompt when the field validates
						"alertTextOk": "* This title is available",
						// speaks by itself
						"alertTextLoad": "* Validating, please wait"

					}
					
	});	

