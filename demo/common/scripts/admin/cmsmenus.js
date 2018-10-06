 $(document).ready(function(){
	$(".radioCSVExport").click(function(e){ 
			e.preventDefault(); 
			$("#"+$(this).attr("for")).click().change();
			 $('#listingForm').submit();
			$('input#exportCSV').removeAttr('checked');  
		});
		$( "#dp1310711996720" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			onSelect: function(dateText) {   
				$(".dp1310711996720Error").fadeOut(150, function() {  
				   $(this).remove();
				});
			}
		});
		$( "#dp1310711996721" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'yy-mm-dd',
			onSelect: function(dateText) {   
				$(".dp1310711996721Error").fadeOut(150, function() {  
				   $(this).remove();
				});
			}
		});
		 
});