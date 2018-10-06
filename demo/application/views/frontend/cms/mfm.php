<?php       

//include("../configs/system.php");
//user authentification (add your own, if you need one)
//if (NO AUTENTIFICATION) { die('Unauthorized!'); }

//config (if your TinyMCE location is different from example, you should also check paths at line ~360)

$cmsLibraryDir = $this->config->item('cmsLibraryDir');
$cmsContentSource = $this->config->item('cmsContentSource');
$cmsCssFile = $this->config->item('cmsCssFile');
$cmsTemplateFile = $this->config->item('cmsTemplateFile');

//if(defined("CMSLIBRARYDIR"))
if(!empty($cmsLibraryDir))
{
	$file_root2 = "../".$cmsLibraryDir.$this->cms_mfm->currentpath($_GET['type']); 		//where to store files, must be created and writable
    $file_root = CMS_UPLOADS_PATH.$cmsLibraryDir.$this->cms_mfm->currentpath($_GET['type']); 		//where to store files, must be created and writable
}
else
{
	$file_root2 = 'library/'.$this->cms_mfm->currentpath($_GET['type']);
    $file_root = CMS_UPLOADS_PATH.'cms/library/'.$this->cms_mfm->currentpath($_GET['type']);
}
//$root_path = '/projects/more/inhouse/cmsv6/cms'; 					//path from webroot, without trailing slash. If your page is located in http://www.example.com/john/, this should be '/john'
$root_path =parse_url(site_url(), PHP_URL_PATH)."common/cms";

$thmb_size = 100;       	//max size of preview thumbnail
$no_script = false;       //true/false - turns scripts into text files
$lang = 'en';           	//language (look in /mfm/lang/ for available)
error_reporting(0);				//'E_ALL' for debugging, '0' for use

//array of known file types (used for icons)
$file_class = array('swf','txt','zip','gz','rar','cab','tar','7z','mp3','ogg','mid','avi','mpg','flv','mpeg','pdf','tif','exe');
$directory_restriction = array('../media/library','../media/library/images','../media/library/documents','../media/library/media');
$titlelist = array('image'=>'Image','advvideo'=>'Video','advaudio'=>'Audio','advfile'=>'Document');

//upload class (see file for credits)
//require('mfm/class.upload.php');

//lang
$lng = array();
//require('mfm/lang/lang_' . strtolower($lang) . '.php');

$lng['delete'] = 'Delete';
$lng['delete_title'] = 'Delete file';

$lng['refresh'] = 'Refresh';
$lng['refresh_files_title'] = 'Refresh file list';
$lng['refresh_tree_title'] = 'Refresh directory tree';

$lng['new_folder'] = 'New folder';
$lng['new_folder_title'] = 'Create new folder';
$lng['delete_folder'] = 'Delete folder';
$lng['delete_folder_title'] = 'Remove current folder';
$lng['default_folder'] = 'new_folder';

$lng['new_file'] = 'Add file';
$lng['new_file_title'] = 'Upload new file';
$lng['new_file_manipulations'] = 'Image manipulations';

$lng['form_file'] = 'File:';
$lng['form_width'] = 'Change width to:';
$lng['form_rotate'] = 'Rotate image:';
$lng['form_greyscale'] = 'convert to grayscale';
$lng['form_submit'] = 'Upload';

$lng['message_created_folder'] = ' - folder created!'; //(after folder name)
$lng['message_cannot_create'] = 'Cannot create folder '; //(before folder name)
$lng['message_cannot_write'] = 'Check that you have permission to create folders!';
$lng['message_exists'] = 'Folder exists!';
$lng['message_cannot_delete_nonexist'] = 'Cannod delete file. File missing!';
$lng['message_cannot_delete'] = 'Cannod delete file!';
$lng['message_deleted'] = 'File deleted!';
$lng['message_uploaded'] = 'File successfully uploaded!';
$lng['message_upload_failed'] = 'Error: cannot upload file :(';
$lng['message_wrong_dir'] = 'Error: destination directory does not exist!';
$lng['message_folder_deleted'] = 'Directory deleted!';
$lng['message_cant_delete_folder'] = 'Error: unable to delete directory!';
$lng['message_folder_not_exist'] = 'Error: no such directory!';

$lng['ask_folder_title'] = 'New folder title:';
$lng['ask_really_delete'] = 'Are you sure?';

$lng['loading'] = 'Loading...';

$lng['window_title'] = 'File Manager';




header("Content-type: text/html; charset=utf-8");

//stand alone or tynimce?
$mode = 'mce';
if(isset($_GET['mode'])) { $mode = $_GET['mode']; }


//functions



$uploadstatus = 0;
if(isset($_GET['status'])) { $uploadstatus = $_GET['status']; }



//remove unnecessary folder
if(isset($_GET['deletefolder'])) {
	if(is_dir($_GET['deletefolder'])) {
		if($this->cms_mfm->delete_directory($_GET['deletefolder'])) {
      		header('Location:'.site_url('cms/mfm').'?status=4&type='.$_GET['type']);
		} else {
      		$uploadstatus = 5;
		}
	} else {
    	$uploadstatus = 6;
	}
}

//display only directory tree for dynamic AHAH requests
if(isset($_GET['viewtree'])) {
?>
			<ul class="dirlist">
				<li><a href="<?php echo  $file_root; ?>/" onClick="load('<?php echo site_url('cms/mfm'); ?>?type=<?php echo $_GET['type']; ?>&viewdir=<?php echo $file_root; ?>','view-files'); return false;"><?php $this->cms_mfm->showtitle($_GET['type'])?></a> <a href="#" onClick="load('mfm/?type=<?php echo $_GET['type']; ?>&viewtree=true','view-tree'); return false;" id="refresh-tree"><?php echo $lng['refresh']; ?></a>
				<?php $this->cms_mfm->print_tree($file_root); ?>
				</li>
			</ul>
<?php
	exit;
}

//display file list for dynamic requests
if(isset($_GET['viewdir'])) {
?>
			<ul id="browser-toolbar">
				<li class="file-new"><a href="#" title="<?php echo $lng['new_file_title']; ?>" onClick="toggle_visibility('load-file'); return false;"><?php echo $lng['new_file']; ?></a></li>
				<li class="folder-new"><a href="#" title="<?php echo $lng['new_folder_title']; ?>" onClick="create_folder('<?php echo $_GET['viewdir']; ?>'); return false;"><?php echo $lng['new_folder']; ?></a></li>
   			<li class="folder-delete"><a style="cursor:pointer;" title="<?php echo $lng['delete_folder_title']; ?>" onClick="delete_folder('<?php echo $_GET['viewdir']; ?>');"><?php echo $lng['delete_folder']; ?></a></li>
				<li class="file-refresh"><a href="#" title="<?php echo $lng['refresh_files_title']; ?>" onClick="load('mfm/?viewdir=<?php echo $_GET['viewdir']; ?>','view-files'); return false;"><?php echo $lng['refresh']; ?></a></li>
			</ul> 
			
			<form style="display: none;" id="load-file" action="" class="load-file" method="post" enctype="multipart/form-data" onSubmit="return validation(this)">

				<fieldset>
				  <legend><?php echo $lng['new_file_title']; ?></legend>
					<input type="hidden" value="<?php echo $_GET['viewdir']; ?>" name="return" />
					<label><?php echo $lng['form_file']; ?><input type="file" name="new_file" /></label> <br /> Note: Maximum Filesize Supported = 4MB. You may use FTP to upload larger files or contact the administrator.
				</fieldset>
				
				<fieldset>
				  <legend><?php echo $lng['new_file_manipulations']; ?></legend>
				  <table>
						<tr>
							<td><label for="new_resize"><?php echo $lng['form_width']; ?></label></td>
							<td><input type="text" class="number" maxlength="4" id="new_resize" name="new_resize" value="" /> px</td>
						</tr>
						<tr>
							<td><label for="new_rotate"><?php echo $lng['form_rotate']; ?></label></td>
							<td>
								<select id="new_rotate" name="new_rotate">
								  <option value="0"></option>
								  <option value="90">90</option>
								  <option value="180">180</option>
								  <option value="270">270</option>
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="checkbox" class="checkbox" id="new_greyscale" name="new_greyscale" /><label for="new_greyscale"><?php echo $lng['form_greyscale']; ?></label></td>
						</tr>
				  </table>
				</fieldset>
				<input type="submit" id="insert" value="<?php echo $lng['form_submit']; ?>" />
			</form>
<?php

	//create directory and show results
	if(isset($_GET['newdir'])) {
    $new_title = $this->cms_mfm->format_filename($_GET['newdir']);
	  if(!is_dir($_GET['viewdir'] . '/' . $new_title)) {
			if(mkdir($_GET['viewdir'] . '/' . $new_title, 0777)) {
				echo '<p class="successful">&quot;' . $new_title . '&quot;' . $lng['message_created_folder'] . '</p>';
			} else {
				echo '<p class="failed">' . $lng['message_cannot_create'] . '&quot;' . $new_title . '&quot;!<br />' . $lng['message_cannot_write'] . '</p>';
			}
		} else {
			echo '<p class="failed">' . $lng['message_cannot_create'] . '&quot;' . $new_title . '&quot;!<br />' . $lng['message_exists'] . '</p>';
		}
	}
	
	//remove unnecessary files
	if(isset($_GET['deletefile'])) {
		if(!file_exists($_GET['viewdir'] . '/' . $_GET['deletefile'])) {
			echo '<p class="failed">' . $lng['message_cannot_delete_nonexist'] . '</p>';
		} else {
			if(unlink($_GET['viewdir'] . '/' . $_GET['deletefile'])) {
				echo '<p class="successful">' . $lng['message_deleted'] . '</p>';
			} else {
				echo '<p class="failed">' . $lng['message_cannot_delete'] . '</p>';
			}
		}
	}
	
	//show status messages by code
	if(isset($_GET['status'])) {
	  //upload file
		if($_GET['status'] == 1) {
			echo '<p class="successful">' . $lng['message_uploaded'] . '</p>';
		} elseif($_GET['status'] == 2) {
			echo '<p class="failed">' . $lng['message_upload_failed'] . '</p>';
		} elseif($_GET['status'] == 3) {
			echo '<p class="failed">' . $lng['message_wrong_dir'] . '</p>';
		//remove directory
		} elseif($_GET['status'] == 4) {
			echo '<p class="successful">' . $lng['message_folder_deleted'] . '</p>';
		} elseif($_GET['status'] == 5) {
			echo '<p class="failed">' . $lng['message_cant_delete_folder'] . '</p>';
		} elseif($_GET['status'] == 6) {
			echo '<p class="failed">' . $lng['message_folder_not_exist'] . '</p>';
		}
	}

	//finally show file list
    $this->cms_mfm->print_files($_GET['viewdir']);
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="content-language" content="en" />
  <title><?php $this->cms_mfm->showtitle($_GET['type'])?></title>
  <link rel="stylesheet" href="<?php echo base_url();?>common/cms/mfm/style.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $root_path; ?>/editor/themes/advanced/skins/default/dialog.css" type="text/css" />
  <script type="text/javascript" src="<?php echo $root_path; ?>/editor/tiny_mce_popup.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		 
		/**
		* @ Function Name		: ahah
		* @ Function Params		: url(string),target (string)
		* @ Function Purpose 	: load content using AHAH (asynchronous HTML and HTTP)
		*/	
  	function ahah(url, target) {
			document.getElementById(target).innerHTML = '<img src="<?php echo base_url();?>common/cms/mfm/loading.gif" alt="" /> <?php echo $lng['loading']; ?>';
			if (window.XMLHttpRequest) {
				req = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
      if (req != undefined) {
        req.onreadystatechange = function() {ahahDone(url, target);};
      
//        var url = url + ((url.indexOf("?") == -1) ? "?" : "&") + escape(new Date("October 13, 1975").toString()); //permet de ne pas utiliser le cache
        req.open("GET", url, true);
        req.send("");
      }
		}
		
		/**
		* @ Function Name		: ahahDone
		* @ Function Params		: url(string),target (string)
		* @ Function Purpose 	: 
		*/	
		
		function ahahDone(url, target) {
			if (req.readyState == 4) {
				if (req.status == 200) {
					document.getElementById(target).innerHTML = req.responseText;
				} else {
				document.getElementById(target).innerHTML=" AHAH Error:\n"+ req.status + "\n" +req.statusText;
				}
			}
		}
		
		/**
		* @ Function Name		: load
		* @ Function Params		: url(string),target (string)
		* @ Function Purpose 	: 
		*/	
		
		function load(name, div) {
			ahah(name,div);
			return false;
		}
		/**
		* @ Function Name		: majax
		* @ Function Params		: url(string),target (string)
		* @ Function Purpose 	: ajax
		*/ 
		function majax(url, target) {
			document.getElementById(target).innerHTML = '<img src="<?php echo base_url();?>common/cms/mfm/loading.gif" alt="" /> <?php echo $lng['loading']; ?>';
			if (window.XMLHttpRequest) {
				req = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
      if (req != undefined) {
        req.onreadystatechange = function() {majaxDone(url, target);};
//        var url = url + ((url.indexOf("?") == -1) ? "?" : "&") + escape(new Date("October 13, 1975").toString()); //permet de ne pas utiliser le cache
        req.open("GET", url, true);
        req.send("");
      }
		}
		
		/**
		* @ Function Name		: majaxDone
		* @ Function Params		: url(string),target (string)
		* @ Function Purpose 	: ajax
		*/ 	
		
		function majaxDone(url, target) {
			if (req.readyState == 4) {
				if (req.status == 200) {
					document.getElementById(target).innerHTML = req.responseText;
					load('mfm/?type=<?php echo $_GET['type']; ?>&viewtree=true','view-tree');
				} else {
				document.getElementById(target).innerHTML=" AHAH Error:\n"+ req.status + "\n" +req.statusText;
				}
			}
		}

		/**
		* @ Function Name		: mload
		* @ Function Params		: name(string),div (string)
		* @ Function Purpose 	: checking
		*/	
		
		function mload(name, div) {
			majax(name,div);			
			return false;
		}
	
	  
	 /**
	* @ Function Name	: create_folder
	* @ Function Params	: 
	* @ Function Purpose 	: ask for folder title and request it is creation 
	* @ Function Returns	: 
	*/
		function create_folder(viewdir) {
			var name=prompt("<?php echo $lng['ask_folder_title']; ?>","<?php echo $lng['default_folder']; ?>");
			if (name!=null && name!=""){
		  	mload('mfm/?type=<?php echo $_GET['type']; ?>&viewdir=' + viewdir + '&newdir=' + name + '','view-files');
		  }
		}

		<?php
			//first one for inserting file name into given field, second for working as tinyMCE plugin
			if ($mode == 'standalone' && isset($_GET['field'])) {
		?>
		
	 /**
	* @ Function Name	  	: submit_url
	* @ Function Params 	: URL(string) 
	* @ Function Purpose 	: ask for folder title and request it is creation 
	* @ Function Returns 	: 
	*/	
	
    function submit_url(URL) {
      window.opener.document.getElementById('<?php echo $_GET['field']; ?>').value = URL;
			self.close();
    }
		<?php
			} else {
		?>
	 /**
	* @ Function Name	  	: submit_url
	* @ Function Params 	: URL(string) 
	* @ Function Purpose 	: close pop up
	* @ Function Returns 	: 
	*/	
		
    function submit_url(URL) {
      var win = tinyMCEPopup.getWindowArg("window");
      win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;
	   
      tinyMCEPopup.close();
    }
    <?php } ?>
     
	/**
		* @ Function Name		: delete_file
		* @ Function Params		: dir(string),file (string)
		* @ Function Purpose 	: delete folder
		*/ 
		
    function delete_file(dir,file) {
			var answer = confirm("<?php echo $lng['ask_really_delete']; ?>");
			if (answer){
		  	load('<?php site_url('cms/mfm'); ?>?type=<?php echo $_GET['type']; ?>&viewdir=' + dir + '&deletefile=' + file,'view-files');
			} 
		}

    
		/**
		* @ Function Name		: delete_folder
		* @ Function Params		: dir(string)
		* @ Function Purpose 	: confirm and delete folder
		*/ 
    function delete_folder(dir) {
			var answer = confirm("<?php echo $lng['ask_really_delete']; ?>");
			if (answer){
		  	location.href = '<?php site_url('cms/mfm'); ?>?type=<?php echo $_GET['type']; ?>&deletefolder=' + dir;
			}
		}
 
		/**
		* @ Function Name		: toggle_visibility
		* @ Function Params		: id(string)
		* @ Function Purpose 	: hide element
		*/ 
		function toggle_visibility(id) {
			var e = document.getElementById(id);
			if(e != null) {
				if(e.style.display == 'none') {
					e.style.display = 'block';
				} else {
					e.style.display = 'none';
				}
			}
		}
		//]]>
/**
* @ Function Name		: hasClass
* @ Function Params		: ele(string), cls(string)
* @ Function Purpose 	: check class
*/ 
		
function hasClass(ele,cls) {
	return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}

/**
* @ Function Name		: addClass
* @ Function Params		: ele(string), cls(string)
* @ Function Purpose 	: add class
*/ 

function addClass(ele,cls) {
	if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}
/**
* @ Function Name		: removeClass
* @ Function Params		: ele(string), cls(string)
* @ Function Purpose 	: check RegExp 
*/ 

function removeClass(ele,cls) {
	if (hasClass(ele,cls)) {
		var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		ele.className=ele.className.replace(reg,' ');
	}
}

/**
* @ Function Name		: currentdirectory
* @ Function Params		: name(string), div(string), obj(object)
* @ Function Purpose 	: remove class
* @ Function Returns	:  
*/ 

function currentdirectory(name, div, obj)
{
	var elms = getElementsByClassName(document, "a", "active");
	for(i=0;i<elms.length;i++)
		removeClass(elms[i],"active");
	addClass(obj,"active");
	load(name, div);
}
/**
* @ Function Name		: getElementsByClassName
* @ Function Params		: 	oElm(string), strTagName(string), strClassName(string)
* @ Function Purpose 	: get name array
* @ Function Returns	: return name array
*/ 

function getElementsByClassName(oElm, strTagName, strClassName){
	var arrElements = (strTagName == "*" && document.all)? document.all : oElm.getElementsByTagName(strTagName);
	var arrReturnElements = new Array();
	strClassName = strClassName.replace(/\-/g, "\\-");
	var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
	var oElement;
	for(var i=0; i<arrElements.length; i++){
		oElement = arrElements[i];
		if(oRegExp.test(oElement.className)){
			arrReturnElements.push(oElement);
		}
	}
	return (arrReturnElements)
}
 /**
	* @ Function Name	  	: validation
	* @ Function Params 	: obj(object) 
	* @ Function Purpose 	: validate
	* @ Function Returns 	: 
	*/	
function validation(obj)
{
	<?php 
	switch($_GET['type'])
	{
		case 'image':
		echo "var valid_extensions = /(.jpg|.png|.jpeg|.gif)$/i;";
		break;
		case 'advvideo':
		echo "var valid_extensions = /(.flv|.swf|.ram|.rm|.rmvb|.ra|.mov|.wmv|.wav|.wma|.mpg|.mpeg)$/i;";
		break;
		case 'advaudio':
		echo "var valid_extensions = /(.mp3|.mp4)$/i;";
		break;
		case 'advfile':
		echo "var valid_extensions = /(.zip|.pdf|.rar|.exe|.doc|.ppt|.psd|.sitx|.sit|.eps|.cdr|.ai|.xls|.txt|.pps|.pub|.qbb|.indd|.dat|.mdb|.chm|.dmg|.iso|.wpd|.7z|.gz|.fla|.qxd|.rtf|.msi|.cab|.ttf|.qbw|.ps|.csv|.dxf|.docx|.xlsx|.pptx|.ppsx)$/i;";
		break;
		default:
		echo "var valid_extensions = /(.mp3|.mp4|.flv|.swf|.ram|.rm|.rmvb|.ra|.mov|.wmv|.wav|.wma|.mpg|.mpeg|.jpg|.png|.jpeg|.gif|.zip|.pdf|.rar|.exe|.doc|.ppt|.psd|.sitx|.sit|.eps|.cdr|.ai|.xls|.txt|.pps|.pub|.qbb|.indd|.dat|.mdb|.chm|.dmg|.iso|.wpd|.7z|.gz|.fla|.qxd|.rtf|.msi|.cab|.ttf|.qbw|.ps|.csv|.dxf|.docx|.xlsx|.pptx|.ppsx)$/i;";
		break;
	}
	?>
	
	if (!valid_extensions.test(obj.new_file.value))
	{
		alert("Invalide file type.");
		return false;
	}
	if(obj.new_resize.value > 400)
	{
		alert("Maximum image with would be 400 px.");
		return false;
	}
	return true;
}
	</script>
</head>

<?php
	$return = $file_root;
	if(isset($_REQUEST['return'])) {$return = $_REQUEST['return'];}
	
?>
<style>
a.active{font-weight:bold;}
</style>
<body onLoad="load('mfm/?type=<?php echo $_GET['type']; ?>&status=<?php echo $uploadstatus; ?>&amp;viewdir=<?php echo $return; ?>','view-files');">
	<div id="browser-wrapper">
    <div id="view-tree">
			<ul class="dirlist">
				<li><a href="<?php echo $root_path . '/' . $file_root; ?>/" onClick="load('mfm/?type=<?php echo $_GET['type']; ?>&viewdir=<?php echo $file_root; ?>','view-files'); return false;"><?php $this->cms_mfm->showtitle($_GET['type'])?></a> <a href="#" title="<?php echo $lng['refresh_tree_title']; ?>" onClick="load('mfm/?type=<?php echo $_GET['type']; ?>&viewtree=true','view-tree'); return false;" id="refresh-tree"><?php echo $lng['refresh']; ?></a>
				  <?php $this->cms_mfm->print_tree($file_root); ?>
				</li>
			</ul>
		</div>
    <div id="view-files">
    </div>
  </div>
</body>
</html>
       
       
     
