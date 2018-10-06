<?php
$cmsEditMode = $this->session->userdata('cmsEditMode');
if(isset($cmsEditMode) and $cmsEditMode == "show") { ?> 
<script type="text/javascript" src="<?php echo base_url();?>common/scripts/tiny_mce/tiny_mce.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>common/styles/cms/cms.css" type="text/css" media="screen"/>
<script language="javascript" type="text/javascript">
var currentcmsedid;
var source;
var contentchangedflag = false;
var editorWidth=720;

//if(!($.browser.mozilla))
	//alert("It appears that you are using a Web Browser other than Mozilla Firefox and our Content Editor is compatible with Mozilla Firefox only!!! You may still continue with your current browser but if you encounter any problems, then please download a copy of FREE Mozilla Firefox and use it to access our Content Editor");

/************************ START - Initialize TinyMCE Editor *****************************/
function initEditor(cmswidth,cmsheight)
{
    	tinyMCE.init({
		// General options
                editor_selector : "myBasicEditor",
		mode : "exact",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/content.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : 'Bold text', inline : 'b'},
			{title : 'Red text', inline : 'span', styles : {color : '#ff0000'}},
			{title : 'Red header', block : 'h1', styles : {color : '#ff0000'}},
			{title : 'Example 1', inline : 'span', classes : 'example1'},
			{title : 'Example 2', inline : 'span', classes : 'example2'},
			{title : 'Table styles'},
			{title : 'Table row 1', selector : 'tr', classes : 'tablerow1'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
}

function MadFileBrowser(field_name, url, type, win) {
  tinyMCE.activeEditor.windowManager.open({file : "<?php echo site_url('cms/mfm'); ?>?field=" + field_name + "&type="+ type +"",title : "File1 Manager",width : 640, height : 450,resizable : "no",inline : "yes",close_previous : "no" },{window : win,input : field_name});
  return false;
}
/************************ END - Initialize TinyMCE Editor *****************************/



/************************ START - Toogling CMS Editor *****************************/
$(document).ready(function(){
	$('.cmsEditorHelp').live('click',function(){
	var helpWidth = (($(window).width()-550)/2 + 'px');
	$.blockUI({ 
				message: $('#cmsEditorHelp'),
				css: {width:'550px', height:'auto', background:'#ffffff', cursor: 'inherit', left:helpWidth , border:'solid 4px #393939', top:'12px',padding:'10px'}
		});
	});
	// OK Button in BlockUI Message
	$('.closeHelp').click(function(){
		$.unblockUI();
	});

	$(".cmsContentWrapper").mouseover(function(){
		$(this).removeClass('cmsEd_mouseOutEffect');
		$(this).addClass('cmsEd_mouseOverEffect');
	}).mouseout(function(){
		$(this).removeClass('cmsEd_mouseOverEffect');
		$(this).addClass('cmsEd_mouseOutEffect');
	});
	
	var dHeight= ($(window).height()-30);
	var dWidth = (($(window).width()-editorWidth)/2 + 'px');
	
	//Add Event Handlers
	$(".cmsEd_editBtn").click(function(){
		$("body").css("overflow", "hidden");
		currentcmsedid = $(this).attr("id").substr(6,($(this).attr("id").length - 14));
		source = $(this).attr("source");
		
		$("#cmsEditor_WYSISWYGEditor").html($("#cmsEd_"+currentcmsedid).html());		
		$(".cmsEditor_title").html($("#cmsEd_"+currentcmsedid+"_lastUpdated").html());

		$.blockUI({ 
				message: $('#cmsEditor_Wrapper'),
				css: {width:editorWidth+'px', height:dHeight+'px', cursor: 'inherit', left:dWidth , top:'12px',border:'solid 1px #abc6dd'}
		});
		
		//Invoke WYSISWYG Editor Instance.
		initEditor(editorWidth,(dHeight-43));
		tinyMCE.execCommand('mceAddControl', false, "cmsEditor_WYSISWYGEditor");
	});	
	
	$(".cmsEditor_closeBtn").click(function(){
		  	if(contentchangedflag)
			{
				if(confirm("Do you want to save the changes?"))
				{
					$.post("<?php echo site_url('test/test/cmsSave')?>", { cmseditor_id: currentcmsedid, cmseditor_content: tinyMCE.get('cmsEditor_WYSISWYGEditor').getContent(), cmseditor_source: source });
					$("#cmsEd_"+currentcmsedid).html(tinyMCE.get('cmsEditor_WYSISWYGEditor').getContent());
				}
				contentchangedflag = false;
			}
		  tinyMCE.execCommand('mceRemoveControl', false, "cmsEditor_WYSISWYGEditor");
		  $("body").css("overflow", "auto");
		  $.unblockUI();
		  lunchmedia();
	   });
	
	$(".cmsEditor_saveBtn").click(function(){
		$.post("<?php echo site_url('cms/cmsSave')?>", { cmseditor_id: currentcmsedid, cmseditor_content: tinyMCE.get('cmsEditor_WYSISWYGEditor').getContent()},function(data) {document.location.href = document.location.href;});
		$("#cmsEd_"+currentcmsedid).html(tinyMCE.get('cmsEditor_WYSISWYGEditor').getContent());
		tinyMCE.execCommand('mceRemoveControl', false, "cmsEditor_WYSISWYGEditor");
		 $("body").css("overflow", "auto");
		 $.unblockUI();
		 lunchmedia();
	});
});
/************************ END - Toogling CMS Editor *****************************/


function resetcontent(val,t,sr)
{
	if(val == 'all')
	{
		if(confirm("Warning: All data/content in all editable regions on all pages of this website will be restored to default factory settings and it would not be possible to undo this action once performed. Do you want to continue?"))
			$.ajax({type: "POST",url: "<?php echo site_url('cms/resetcontent'); ?>",data: t+"="+val+"&sr="+sr,success: function(msg){alert("All Content Editable Regions of the website were successfully restored to the Default Factory Settings.\nPlease refresh the page to see the updated page content.");document.location.href = document.location.href;}});
	}
	else if(t == 'pn')
	{
		$.ajax({type: "POST",url: "<?php echo site_url('cms/resetcontent'); ?>",data: t+"="+val+"&sr="+sr,success: function(msg){alert("The content was successfully restored to the Last Saved Version.\nPlease refresh the page to see the updated page content.");document.location.href = document.location.href;}});
	}
	else
	{
		if(confirm("Warning: The content in the editable region below will be restored to default factory settings and it would not be possible to undo this action once performed. Do you want to continue?"))
		$.ajax({type: "POST",url: "<?php echo site_url('cms/resetcontent'); ?>",data: t+"="+val+"&sr="+sr,success: function(msg){alert("The content was successfully restored to the Default Factory Settings.\nPlease refresh the page to see the updated page content.");document.location.href = document.location.href;}});
	}
}
</script>

<div id="cmsEditor_Wrapper" style="display:none;">
<div class="cmsEditor_header">
    <div class="cmsEditor_title"></div>
    <div class="cmsEditor_buttons">
        <a href="javascript:void(0);" class="cmsEditor_saveBtn" title="Save">Save</a>
        <a href="javascript:void(0);" class="cmsEditor_closeBtn" title="Close">Close</a>
    </div>
</div>
<div id="cmsEditor_WYSISWYGEditor"></div>
</div>
 
<script language="javascript">
var videoHght=400;
var videoWdth=600;
$(document).ready(function(){
	lunchmedia();
});

function lunchmedia()
{
	$(".cmsEd_lunchMedia").click(function(){
		var vTop= (($(window).height()-(videoHght + 30))/2 + 'px');
		var vLeft = (($(window).width()-videoWdth)/2 + 'px');
		$('#cmsEditor_Mediapreview').html("&nbsp;");
		$("#cmsEditor_Mediapreview").load($(this).attr("href"));
		$("#cmsEditor_Mediapreview").attr({"style":"width:"+videoWdth+"px;height:"+videoHght+"px;"});
		$.blockUI({ 
				message: $('#cmsEditor_Media_Container'),
				css: {width:videoWdth+'px', height:(videoHght + 30)+'px',left:vLeft , top:vTop, cursor: 'inherit',border:'solid 1px #abc6dd'}
		});
		return false;
	});
	$(".cmsEditor_closeMeida").click(function(){
		  $.unblockUI();  
	 });
}
</script>

<div id="cmsEditor_Media_Container" style="display:none;">
	<div class="cmsEditor_header">
    	<div class="cmsEditor_title"></div>
    	<div class="cmsEditor_buttons">
        	<a href="javascript:void(0);" class="cmsEditor_closeMeida" title="Close"></a>
    	</div>
	</div>
	<div id="cmsEditor_Mediapreview"></div>
</div>
<?php } ?>