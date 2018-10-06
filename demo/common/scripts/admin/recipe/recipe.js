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
		$("#pagingItemsPerPage").change(function(){
			$('#pagingList option[value="1"]').attr('selected', true);
			submitForm();
		});
    $("#prevPage").click(function(){
        valPre=$("#prevPage").attr("for");
       
		$('#pagingList option[value="' + valPre + '"]').attr('selected', true);
        submitForm();
    });
    $("#nextPage").click(function(){
        valNext=$("#nextPage").attr("for");
		$('#pagingList option[value="' + valNext + '"]').attr('selected', true);
        submitForm();
    });
		
});
function submitForm(){
    varfrom = $('#dp1310711996720').val();
    varto  = $('#dp1310711996721').val();
    varfrom = new Date(varfrom).getTime();
    varto = new Date(varto).getTime();
    if(varfrom > varto)
    {
        setWarningMessage('neg','Date From should not be greater than Date To.');
        return false;
    }

    $('#listingForm').submit();
}
