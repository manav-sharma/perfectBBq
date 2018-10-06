<?php if(isset($_SESSION['cmsEditMode']) and $_SESSION['cmsEditMode'] == "show") { ?>
<link href="cms/style/cms.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="cms/editor/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
var currentcmsedid;
var source;
var contentchangedflag = false;
var editorWidth=720;

if(!($.browser.mozilla))
	alert("It appears that you are using a Web Browser other than Mozilla Firefox and our Content Editor is compatible with Mozilla Firefox only!!! You may still continue with your current browser but if you encounter any problems, then please download a copy of FREE Mozilla Firefox and use it to access our Content Editor");

/************************ START - Initialize TinyMCE Editor *****************************/
function initEditor(cmswidth,cmsheight)
{
	tinyMCE.init({
		mode : "exact",
		elements : "e",
		theme : "advanced",
		plugins : "safari,pagebreak,style,table,advhr,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,template,wordcount,advfile,advimage,advvideo,advaudio",
		theme_advanced_buttons1 : "template,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,forecolor,backcolor,|,sub,sup,|,ltr,rtl,|,styleprops,attribs,removeformat,|,styleselect",
		theme_advanced_buttons2 : "undo,redo,|,pastetext,pasteword,|,search,replace,|,link,unlink,anchor,insertdate,inserttime,emotions,|,advfile,image,advvideo,advaudio,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons3 : "tablecontrols,|,moveforward,movebackward,|,advhr,pagebreak,charmap,|,visualaid,iespell,|,preview,print,fullscreen,|,code,cleanup",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		file_browser_callback : MadFileBrowser,
		onchange_callback : function (inst){contentchangedflag = true;},
		relative_urls : false,
		remove_script_host : false,
		theme_advanced_resizing : false,
        content_css : "<?php echo HOST_URL;?>styles/cms.css",
		//Drop lists for link/image/media/template dialogs
		<?php
		if(defined("CMSTEMPLATESFILE"))
		echo 'template_external_list_url : "'.HOST_URL.CMSTEMPLATESFILE.'",';
		if(defined("CMSCSSFILE"))
		// 'content_css : "'.HOST_URL.CMSCSSFILE.'",'
		?>
		width:cmswidth,
		height:cmsheight
	});
}

function MadFileBrowser(field_name, url, type, win) {
  tinyMCE.activeEditor.windowManager.open({file : "cms/mfm.php?field=" + field_name + "&type="+ type +"",title : "File1 Manager",width : 640, height : 450,resizable : "no",inline : "yes",close_previous : "no" },{window : win,input : field_name});
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

	$(".cms_contentWrapper").mouseover(function(){
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
					$.post("cms/save-content.php", { cmseditor_id: currentcmsedid, cmseditor_content: tinyMCE.get('cmsEditor_WYSISWYGEditor').getContent(), cmseditor_source: source });
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
		$.post("cms/save-content.php", { cmseditor_id: currentcmsedid, cmseditor_content: tinyMCE.get('cmsEditor_WYSISWYGEditor').getContent()},function(data) {document.location.href = document.location.href;});
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
			$.ajax({type: "POST",url: "cms/resetcontent.php",data: t+"="+val+"&sr="+sr,success: function(msg){alert("All Content Editable Regions of the website were successfully restored to the Default Factory Settings.\nPlease refresh the page to see the updated page content.");document.location.href = document.location.href;}});
	}
	else if(t == 'pn')
	{
		$.ajax({type: "POST",url: "cms/resetcontent.php",data: t+"="+val+"&sr="+sr,success: function(msg){alert("The content was successfully restored to the Last Saved Version.\nPlease refresh the page to see the updated page content.");document.location.href = document.location.href;}});
	}
	else
	{
		if(confirm("Warning: The content in the editable region below will be restored to default factory settings and it would not be possible to undo this action once performed. Do you want to continue?"))
		$.ajax({type: "POST",url: "cms/resetcontent.php",data: t+"="+val+"&sr="+sr,success: function(msg){alert("The content was successfully restored to the Default Factory Settings.\nPlease refresh the page to see the updated page content.");document.location.href = document.location.href;}});
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
<!--<div style="width:100%; height:32px; background:#3a3a3a;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60"  valign="middle" height="32"><a href="index.php" style="background:url('cms/images/toolbar/home.gif') left no-repeat; padding:0 0 0 22px; margin:0 0 0 12px; font-family:verdana,arial; font-size:11px; color:#bdbdbd; display:inline-block;">Home</a></td>
  	 <td width="150"  valign="middle" height="32"><a href="javascript:void(0);" onclick="resetcontent('all','cn','')" style="background:url('cms/images/toolbar/factoryRestore.gif') left no-repeat; padding:0 0 0 22px; margin:0 0 0 12px; font-family:verdana,arial; font-size:11px; color:#bdbdbd; display:inline-block;" title="Full Factory Restore: This will Restore the content of All Editable Regions on All Pages of the Website to Default Factory Settings. Click this only if you know what you are doing!" alt="Full Factory Restore: This will Restore the content of All Editable Regions on All Pages of the Website to Default Factory Settings. Click this only if you know what you are doing!">Full Factory Restore</a></td>
    <td width="40"></td>
    <td align="center" style="font-family:'segoe UI', Arial, sans-serif; font-size:14px; color:#c7dce3;">Content Manager</td>
    <?php if(isset($_SESSION['cmsAdminByPass']) and $_SESSION['cmsAdminByPass'] == 'no') { ?>
    <td valign="middle" height="32" width="140" align="right"><a href="admin/change.password.php" style="background:url('cms/images/toolbar/changePassword.gif') left no-repeat; padding:0 0 0 22px; margin:0 0 0 12px; font-family:verdana,arial; font-size:11px; color:#bdbdbd; display:inline-block; height:32px; line-height:32px;'">Change Password</a></td>
    <?php } ?>
    <td width="40"><a href="#" style="background:url('cms/images/toolbar/help.gif') left no-repeat; padding:0 0 0 22px; margin:0 0 0 12px; font-family:verdana,arial; font-size:11px; color:#bdbdbd; display:inline-block; line-height:22px;" class="cmsEditorHelp">Help</a></td>
    <?php if(isset($_SESSION['cmsAdminByPass']) and $_SESSION['cmsAdminByPass'] == 'no') { ?>
    <td width="80"><a href="admin/?mode=logout" style="background:url('cms/images/toolbar/logout.gif') left no-repeat; padding:0 0 0 22px; margin:0 12px; font-family:verdana,arial; font-size:11px; color:#bdbdbd; display:inline-block; line-height:22px;">Logout</a></td>
    <?php } else {?>
    <td width="80"><a href="admincp/?m=default&a=logout" style="background:url('cms/images/toolbar/logout.gif') left no-repeat; padding:0 0 0 22px; margin:0 12px; font-family:verdana,arial; font-size:11px; color:#bdbdbd; display:inline-block; line-height:22px;">Logout</a></td>
    <?php } ?>
  </tr>
</table>
</div>
<div id="cmsEditorHelp">
	<a href="#" class="closeHelp"></a>
    <h1>How to edit content using our Content Manager?</h1>
    <p>You are now logged in to the <strong>"Content Manager"</strong> for your website and you should be able to see the blue colored toolbars on top of all Editable Content Regions in your website. These are the toolbars with heading "Editable Content". You will also see the following 3 Icons at the right of all toolbars.</p>
        <ul>
            <li><strong>Edit:</strong> Click this icon to edit content within the content editable region below it.</li>
            <li><strong>Restore:</strong> Click this to revert to the last saved version of the content in case you did a mistake while editing.</li>
            <li><strong>Factory Restore:</strong> Click this to revert to the original version of the content which was there when we deployed the site. This should be used only in cases when the content has reached such a state that it can't be corrected easily. So, revert to the original/factory version &amp; try making the corrections again!</li>
        </ul>
    </li>
    <p>When you click the <strong>Edit Icon</strong> it will open an in-place text editor which will allow you to edit content using MS Word like tools. After you have changed the content, you can click the Save &amp; the changes would be instantly reflected on the main website for all your visitors.</p>
    <div style="margin:20px 0 0 0; padding:0 0 10px 0; background:#f2f4f7;">
    <h3>Please Note</h3>
    <ul>
      <li>If you are copying content from web pages or MS Word, then please copy it to Notepad first and then into the CMS editor.</li>
      <li>The Cut, Copy &amp; Paste buttons do not work in Firefox but standard Ctrl-C, Ctrl-V shortcuts do work well.</li>
      <li>Always remember to resize images while uploading to the Image Library. If the uploaded images hamper the layout of page or the content block to which you are trying to add the image, then reduce the image size by specifying the width of the image while uploading and make sure that the width of the images is not more than the width of the page or content block.</li>
      <li>Please only use "Firefox" Web Browser for editing content via our Content Editor. You can download Firefox from <a href="http://www.mozilla.com/firefox/" target="_blank">http://www.mozilla.com/firefox/</a></li>
      <li>The Editable Content Toolbars/Regions are visible on the site only if you have logged in to the admin section at the same time. Also, these are only visible to you/admin of the website and would not be visible to other people viewing the website. Lastly, the editable regions/toolbars would disappear once you logout from the admin section.</li>    
    </ul>
   </div> 
</div>-->

<script type="text/javascript" src="cms/editor/plugins/advvideo/swfobject.js"></script>
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