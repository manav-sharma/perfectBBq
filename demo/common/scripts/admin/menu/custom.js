function generateUUID(){
    var d = new Date().getTime();
    var uuid = 'xxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = (d + Math.random()*16)%16 | 0;
        d = Math.floor(d/16);
        return (c=='x' ? r : (r&0x7|0x8)).toString(16);
    });
    return uuid;
};
function createNestedSortableLi(id){  
	var uniqid	= generateUUID();
	var pageId=id;
	var title= $("#"+id).val();
	var content='';
		content+='<li class="mjs-nestedSortable-leaf '+uniqid+'"  id="list_'+pageId+'">';
		content+='<div><span class="disclose"><span></span></span>';
		content+=title;
		content+="<span class='floatRight' onclick=deleteLi('"+uniqid+"')>Delete</span><span class='customChanges'><a class="+uniqid+">Page</a></span></div>"; 
			content+='<span class="fullwidth '+uniqid+'">';
				content+='<span class="col">';
				content+='<label>Navigation Label : <span>*</span></label>';
				content+='<input type="text" value="'+title+'" name="nav_'+uniqid+'">';
				content+='</span>'; 
			content+='</span></li>';
		$( "#menuOL" ).append(content);
}  
function createNestedSortableLi1(custname,custurl){ 
	var uniqid	= generateUUID();
	var pageId  = 0;
	var title   = custname;
	var url   = custurl;
	var content='';
		content+='<li class="mjs-nestedSortable-leaf '+uniqid+'"  id="list_'+pageId+','+title+','+url+'">';
		content+='<div><span class="disclose"><span></span></span>';
		content+=title;
		content+="<span class='floatRight' onclick=deleteLi('"+uniqid+"')>Delete</span><span class='customChanges'><a class="+uniqid+">Custom Url</a></span></div>"; 
			content+='<span class="fullwidth '+uniqid+'">';
				content+='<span class="col">';
				content+='<label>Navigation Label : <span>*</span></label>';
				content+='<input type="text" value="'+title+'" name="nav_'+uniqid+'">';
				content+='</span>'; 
			content+='</span></li>';
		$( "#menuOL" ).append(content);
} 

	 
function deleteLi(id){ 
	var liID="."+id; 
	$(liID).remove();    
}

 
$(document).ready(function(){ 

	$("#admenuButton").click(function(){  
	    
		if($('#menuOL').length == 1){
			$(".emptyDiv").css("display","NONE");
		}else{
			$(".emptyDiv").css("display","block");
		}	
		
		 $('input[type="checkbox"]:checked').each(function() { 
			 createNestedSortableLi($(this).val()) 
		});
		 $( 'input[type="checkbox"]:checked' ).removeAttr('checked');
		
	});
		$("#admenuButton1").click(function(){  
			var custname = $('#custname').val();
			var custurl = $('#custurl').val();
			
				if((custname != '' && custurl !='')){
				}
				else if((custname =='' && custurl !='') || (custname !='' && custurl == 'http://') ||(custname !='' && custurl =='')){
					return false;
				}		
				else{
					return false;
				}
				
				if($('#menuOL').length == 1){
					$(".emptyDiv").css("display","NONE");
				}else{
					$(".emptyDiv").css("display","block");
				}	
		
			createNestedSortableLi1(custname,custurl);
			$('#custname').val('');
			$('#custurl').val('http://');
	});
		
	$('ol.sortable').nestedSortable({
		forcePlaceholderSize: true,
		handle: 'div',
		helper:	'clone',
		items: 'li',
		opacity: .6,
		placeholder: 'placeholder',
		revert: 250,
		tabSize: 25,
		tolerance: 'pointer',
		toleranceElement: '> div',
		maxLevels: 10,

		isTree: true,
		expandOnHover: 700,
		startCollapsed: true
	});
 
	$(document).on("click",".disclose",function() { 
		$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
	}) 
	
});  