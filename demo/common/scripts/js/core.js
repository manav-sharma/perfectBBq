// JavaScript Document
$(document).ready(function(){
	$('.topText').click(function(){
		$('#main').toggle('700');
	});
});

$(function() { 
		$("#frmAdvertiser").validationEngine('attach',{
			autoHidePrompt:true, 
			autoHideDelay: 5000
		});
		
});

$(function() { 
		$("#contactus").validationEngine('attach',{
			autoHidePrompt:true, 
			autoHideDelay: 5000
		});
		
});
