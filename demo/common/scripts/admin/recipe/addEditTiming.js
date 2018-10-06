$(function() { 
		$("input").on('click', '', function() { 
			var inputFieldFormError="."+$(this).attr('id')+'formError'; 
			$(inputFieldFormError).fadeOut(150, function() {  
			   $(this).remove();
			});
		}); 
		
		$(frmTiming).validationEngine('attach',{
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
		
		$(".minData").change(function(){
			var minVal = $(this).val();
			var secVal = $(this).parent().parent().find(".secData").val();
			if ($.trim(secVal) != "" && secVal.match(/^\d+$/)) {
				secVal = secVal;
			} else {
				secVal = 0;
			}
			var totalSec = 0;
			if(minVal.match(/^\d+$/)) {
				minutes = parseInt(minVal) * 60;
				seconds = parseInt(secVal);
				totalSec = parseInt(minutes) + parseInt(seconds); 
			}
			$(this).parent().parent().find(".rec_total_sec").val(totalSec);
		});
		
		$(".secData").change(function(){
			var minVal = $(this).parent().parent().find(".minData").val();
			var secVal = $(this).val();
			if ($.trim(minVal) != "" && minVal.match(/^\d+$/)) {
				minVal = minVal;
			} else {
				minVal = 0;
			}
			var totalSec = 0;
			if(secVal.match(/^\d+$/)) {
				minutes = parseInt(minVal) * 60;
				seconds = parseInt(secVal);
				totalSec = parseInt(minutes) + parseInt(seconds); 
			}
			$(this).parent().parent().find(".rec_total_sec").val(totalSec);
		});
});

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
					}
	});	

