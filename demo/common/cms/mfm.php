<?php
include("../configs/system.php");
//user authentification (add your own, if you need one)
//if (NO AUTENTIFICATION) { die('Unauthorized!'); }

//config (if your TinyMCE location is different from example, you should also check paths at line ~360)
if(defined("CMSLIBRARYDIR"))
	$file_root = "../".CMSLIBRARYDIR.currentpath($_GET['type']); 		//where to store files, must be created and writable
else
	$file_root = 'library/'.currentpath($_GET['type']);

//$root_path = '/projects/more/inhouse/cmsv6/cms'; 					//path from webroot, without trailing slash. If your page is located in http://www.example.com/john/, this should be '/john'
$root_path =parse_url(HOST_URL, PHP_URL_PATH)."cms";

$thmb_size = 100;       	//max size of preview thumbnail
$no_script = false;       //true/false - turns scripts into text files
$lang = 'en';           	//language (look in /mfm/lang/ for available)
error_reporting(0);				//'E_ALL' for debugging, '0' for use

//array of known file types (used for icons)
$file_class = array('swf','txt','zip','gz','rar','cab','tar','7z','mp3','ogg','mid','avi','mpg','flv','mpeg','pdf','tif','exe');
$directory_restriction = array('../media/library','../media/library/images','../media/library/documents','../media/library/media');
$titlelist = array('image'=>'Image','advvideo'=>'Video','advaudio'=>'Audio','advfile'=>'Document');

//upload class (see file for credits)
require('mfm/class.upload.php');

//lang
$lng = array();
require('mfm/lang/lang_' . strtolower($lang) . '.php');
header("Content-type: text/html; charset=utf-8");

//stand alone or tynimce?
$mode = 'mce';
if(isset($_GET['mode'])) { $mode = $_GET['mode']; }


function showtitle($val)
{
	global $titlelist;
	echo ($titlelist[$val] ? $titlelist[$val] . " " : "") . "Library";
}

function currentpath($val)
{
	$filespath = array('image'=>'images','advvideo'=>'media/video','advaudio'=>'media/audio','advfile'=>'documents');
	return $filespath[$val] ? $filespath[$val] : "";
}
	
//a last step above to filter out "." and ".." segments from the returned URL's path
function url_remove_dot_segments( $path )
{
    // multi-byte character explode
    $inSegs  = preg_split( '!/!u', $path );
    $outSegs = array( );
    foreach ( $inSegs as $seg )
    {
        if ( $seg == '' || $seg == '.')
            continue;
        if ( $seg == '..' )
            array_pop( $outSegs );
        else
            array_push( $outSegs, $seg );
    }
    $outPath = implode( '/', $outSegs );
    if ( $path[0] == '/' )
        $outPath = '/' . $outPath;
    // compare last multi-byte character against '/'
    if ( $outPath != '/' && (mb_strlen($path)-1) == mb_strrpos( $path, '/', 'UTF-8' ) )
        $outPath .= '/';
    return $outPath;
}

//replaces special characters for latvian and russian lang., and removes all other
function format_filename($filename) {
	$bads = array(' ','ā','č','ē','ģ','ī','ķ','ļ','ņ','ŗ','š','ū','ž','Ā','Č','Ē','Ģ','Ī','Ķ','Ļ','Ņ','Ŗ','Š','Ū','Ž','$','&','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','ЫЬ','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','шщ','ъ','ы','ь','э','ю','я');
	$good = array('-','a','c','e','g','i','k','l','n','r','s','u','z','A','C','E','G','I','K','L','N','R','S','U','Z','s','and','A','B','V','G','D','E','J','Z','Z','I','J','K','L','M','N','O','P','R','S','T','U','F','H','C','C','S','S','T','T','E','Ju','Ja','a','b','v','g','d','e','e','z','z','i','j','k','l','m','n','o','p','r','s','t','u','f','h','c','c','s','t','t','y','z','e','ju','ja');
	$filename = str_replace($bads,$good,trim($filename));
	$allowed = "/[^a-z0-9\\.\\-\\_\\\\]/i";
	$filename = preg_replace($allowed,'',$filename);
	return $filename;
}

//convert file size to human readable format
function byte_convert($bytes) {
  $symbol = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $exp = 0;
  $converted_value = 0;
  if( $bytes > 0 ) {
    $exp = floor( log($bytes)/log(1024) );
    $converted_value = ( $bytes/pow(1024,floor($exp)) );
  }
  return sprintf( '%.2f '.$symbol[$exp], $converted_value );
}

//show recursive directory tree
function print_tree($dir = '.') {
	global $root_path;
  echo '<ul class="dirlist">';
  $d = opendir($dir);
  while($f = readdir($d)) {
    if(strpos($f, '.') === 0) continue;
    $ff = $dir . '/' . $f;
    if(is_dir($ff)) {
  	  echo '<li><a href="' . $root_path . '/' . $ff . '/" onclick="currentdirectory(\'mfm.php?viewdir=' . $ff . '\',\'view-files\',this); return false;">' . $f . '</a>';
			print_tree($ff);
  	  echo '</li>';
		}
  }
  echo '</ul>';
}

//show file list of given directory
function print_files($c = '.') {
	global $root_path, $mode, $thmb_size, $file_class, $lng;
  echo('<table id="file-list">');
  $d = opendir($c);
  $i = 0;
  while($f = readdir($d)) {
    if(strpos($f, '.') === 0) continue;
    $ff = $c . '/' . $f;
    $ext = strtolower(substr(strrchr($f, '.'), 1));
    if(!is_dir($ff)) {
	    echo '<tr' . ($i%2 ? ' class="light"' : ' class="dark"') .'>';
	    //show preview and different icon, if file is image
	    $imageinfo = @getimagesize($ff);
	    if($imageinfo && $imageinfo[2] > 0 && $imageinfo[2]< 4) {
	    	$resize = '';
	    	if($imageinfo[0] > $thmb_size or $imageinfo[1] > $thmb_size) {
		    	if($imageinfo[0] > $imageinfo[1]) {
						$resize = ' style="width: ' . $thmb_size . 'px;"';
					} else {
						$resize = ' style="height: ' . $thmb_size . 'px;"';
					}
				}
				if ($imageinfo[2] == 1) {
					$imagetype = "image_gif";
				} elseif ($imageinfo[2] == 2) {
					$imagetype = "image_jpg";
				} elseif ($imageinfo[2] == 3) {
					$imagetype = "image_jpg";
				} else {
					$imagetype = "image";
				}
				echo '<td><a class="file thumbnail ' . $imagetype . '" href="#" onclick="submit_url(\'' . url_remove_dot_segments($root_path . '/' . $ff) . '\');">' . $f . '<span><img' . $resize . ' src="' . $root_path . '/' . $ff . '" /></span></a>'; echo '</td>';
			//known file types
			} elseif(in_array($ext,$file_class)) {
				echo '<td><a class="file file_' . $ext . '" href="#" onclick="submit_url(\'' . url_remove_dot_segments($root_path . '/' . $ff) . '\');">' . $f . '</a>'; echo '</td>';
			//all other files
			} else {
				echo '<td><a class="file unknown" href="#" onclick="submit_url(\'' . url_remove_dot_segments($root_path . '/' . $ff) . '\');">' . $f . '</a>'; echo '</td>';
	    }
			echo '<td>' . byte_convert(filesize($ff)) . '</td>';
			echo '<td class="delete"><a style="cursor:pointer;" title="' . $lng['delete_title'] . '" onclick="delete_file(\'' . $c . '\',\'' . $f . '\');">' . $lng['delete'] . '</a></td>';
	    echo '</tr>';
	    $i++;
    }
  }
  echo('</table>');
}

function delete_directory($dirname) {
	global $directory_restriction;
	if (is_dir($dirname))
		$dir_handle = opendir($dirname);
	if (!$dir_handle)
		return false;
	if(in_array($dirname,$directory_restriction))
		return false;
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
				unlink($dirname."/".$file);
			else
				delete_directory($dirname.'/'.$file);
		}
	}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

$uploadstatus = 0;
if(isset($_GET['status'])) { $uploadstatus = $_GET['status']; }

//handles file uploads
if(isset($_FILES['new_file']) && isset($_POST['return'])) {
	if(is_dir($_POST['return'])) {
		$handle = new upload($_FILES['new_file']);
	  if ($handle->uploaded) {
      $handle->file_new_name_body   = format_filename(substr($_FILES['new_file']['name'],0,-4));
      //resize image. more options coming soon.
      if(isset($_POST['new_resize']) && $_POST['new_resize'] > 0) {
	      $handle->image_resize         = true;
	      $handle->image_x              = (int)$_POST['new_resize'];
	      $handle->image_ratio_y        = true;
      }
      if(isset($_POST['new_greyscale']) && $_POST['new_greyscale']) {
				$handle->image_greyscale      = true;
			}
      if(isset($_POST['new_rotate']) && $_POST['new_rotate'] == 90 or $_POST['new_rotate'] == 180 or $_POST['new_rotate'] == 270) {
				$handle->image_rotate      		= $_POST['new_rotate'];
			}
			$handle->mime_check = $no_script;
			$handle->no_script = $no_script;
      $handle->process($_POST['return'] . '/');
      if ($handle->processed) {
        $handle->clean();
        $uploadstatus = 1;
      } else {
				//uncomment for upload debugging
        echo 'error : ' . $handle->error;
        $uploadstatus = 2;
      }
	  }
	} else {
		$uploadstatus = 3;
	}
}

//remove unnecessary folder
if(isset($_GET['deletefolder'])) {
	if(is_dir($_GET['deletefolder'])) {
		if(delete_directory($_GET['deletefolder'])) {
      		header('Location: mfm.php?status=4&type='.$_GET['type']);
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
				<li><a href="<?php echo $root_path . '/' . $file_root; ?>/" onClick="load('mfm.php?type=<?php echo $_GET['type']; ?>&viewdir=<?php echo $file_root; ?>','view-files'); return false;"><?php showtitle($_GET['type'])?></a> <a href="#" onClick="load('mfm.php?type=<?php echo $_GET['type']; ?>&viewtree=true','view-tree'); return false;" id="refresh-tree"><?php echo $lng['refresh']; ?></a>
				<?php print_tree($file_root); ?>
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
				<li class="file-refresh"><a href="#" title="<?php echo $lng['refresh_files_title']; ?>" onClick="load('mfm.php?viewdir=<?php echo $_GET['viewdir']; ?>','view-files'); return false;"><?php echo $lng['refresh']; ?></a></li>
			</ul>
			
			<!--<div id="current-loction">
			  <?php echo htmlspecialchars($root_path . '/' . $_GET['viewdir'] . '/'); ?>
			</div>-->
			
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
    $new_title = format_filename($_GET['newdir']);
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
	print_files($_GET['viewdir']);
	exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta http-equiv="content-language" content="en" />
  <title><?php showtitle($_GET['type'])?></title>
  <link rel="stylesheet" href="mfm/style.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $root_path; ?>/editor/themes/advanced/skins/default/dialog.css" type="text/css" />
  <script type="text/javascript" src="<?php echo $root_path; ?>/editor/tiny_mce_popup.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		
		//load content using AHAH (asynchronous HTML and HTTP)
  	function ahah(url, target) {
			document.getElementById(target).innerHTML = '<img src="mfm/loading.gif" alt="" /> <?php echo $lng['loading']; ?>';
			if (window.XMLHttpRequest) {
				req = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
      if (req != undefined) {
        req.onreadystatechange = function() {ahahDone(url, target);};
        var url = url + ((url.indexOf("?") == -1) ? "?" : "&") + escape(new Date().toString()); //permet de ne pas utiliser le cache
        req.open("GET", url, true);
        req.send("");
      }
		}
			
		function ahahDone(url, target) {
			if (req.readyState == 4) {
				if (req.status == 200) {
					document.getElementById(target).innerHTML = req.responseText;
				} else {
				document.getElementById(target).innerHTML=" AHAH Error:\n"+ req.status + "\n" +req.statusText;
				}
			}
		}
			
		function load(name, div) {
			ahah(name,div);
			return false;
		}
		
		function majax(url, target) {
			document.getElementById(target).innerHTML = '<img src="mfm/loading.gif" alt="" /> <?php echo $lng['loading']; ?>';
			if (window.XMLHttpRequest) {
				req = new XMLHttpRequest();
			} else if (window.ActiveXObject) {
				req = new ActiveXObject("Microsoft.XMLHTTP");
			}
      if (req != undefined) {
        req.onreadystatechange = function() {majaxDone(url, target);};
        var url = url + ((url.indexOf("?") == -1) ? "?" : "&") + escape(new Date().toString()); //permet de ne pas utiliser le cache
        req.open("GET", url, true);
        req.send("");
      }
		}
			
		function majaxDone(url, target) {
			if (req.readyState == 4) {
				if (req.status == 200) {
					document.getElementById(target).innerHTML = req.responseText;
					load('mfm.php?type=<?php echo $_GET['type']; ?>&viewtree=true','view-tree');
				} else {
				document.getElementById(target).innerHTML=" AHAH Error:\n"+ req.status + "\n" +req.statusText;
				}
			}
		}
			
		function mload(name, div) {
			majax(name,div);			
			return false;
		}
	
	  //ask for folder title and request it's creation
		function create_folder(viewdir) {
			var name=prompt("<?php echo $lng['ask_folder_title']; ?>","<?php echo $lng['default_folder']; ?>");
			if (name!=null && name!=""){
		  	mload('mfm.php?type=<?php echo $_GET['type']; ?>&viewdir=' + viewdir + '&newdir=' + name + '','view-files');
		  }
		}

		<?php
			//first one for inserting file name into given field, second for working as tinyMCE plugin
			if ($mode == 'standalone' && isset($_GET['field'])) {
		?>
    function submit_url(URL) {
      window.opener.document.getElementById('<?php echo $_GET['field']; ?>').value = URL;
			self.close();
    }
		<?php
			} else {
		?>
    function submit_url(URL) {
      var win = tinyMCEPopup.getWindowArg("window");
      win.document.getElementById(tinyMCEPopup.getWindowArg("input")).value = URL;
	  
      //if (win.ImageDialog.getImageData) win.ImageDialog.getImageData();
      //if (win.ImageDialog.showPreviewImage) win.ImageDialog.showPreviewImage(URL);
      tinyMCEPopup.close();
    }
    <?php } ?>
    
    //confirm and delete file
    function delete_file(dir,file) {
			var answer = confirm("<?php echo $lng['ask_really_delete']; ?>");
			if (answer){
		  	load('mfm.php?type=<?php echo $_GET['type']; ?>&viewdir=' + dir + '&deletefile=' + file,'view-files');
			} 
		}

    //confirm and delete folder
    function delete_folder(dir) {
			var answer = confirm("<?php echo $lng['ask_really_delete']; ?>");
			if (answer){
		  	location.href = 'mfm.php?type=<?php echo $_GET['type']; ?>&deletefolder=' + dir;
			}
		}

		//show/hide element (for file upload form)
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
		
function hasClass(ele,cls) {
	return ele.className.match(new RegExp('(\\s|^)'+cls+'(\\s|$)'));
}
function addClass(ele,cls) {
	if (!this.hasClass(ele,cls)) ele.className += " "+cls;
}
function removeClass(ele,cls) {
	if (hasClass(ele,cls)) {
		var reg = new RegExp('(\\s|^)'+cls+'(\\s|$)');
		ele.className=ele.className.replace(reg,' ');
	}
}
function currentdirectory(name, div, obj)
{
	var elms = getElementsByClassName(document, "a", "active");
	for(i=0;i<elms.length;i++)
		removeClass(elms[i],"active");
	addClass(obj,"active");
	load(name, div);
}
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
<body onLoad="load('mfm.php?type=<?php echo $_GET['type']; ?>&status=<?php echo $uploadstatus; ?>&amp;viewdir=<?php echo $return; ?>','view-files');">
	<div id="browser-wrapper">
    <div id="view-tree">
			<ul class="dirlist">
				<li><a href="<?php echo $root_path . '/' . $file_root; ?>/" onClick="load('mfm.php?type=<?php echo $_GET['type']; ?>&viewdir=<?php echo $file_root; ?>','view-files'); return false;"><?php showtitle($_GET['type'])?></a> <a href="#" title="<?php echo $lng['refresh_tree_title']; ?>" onClick="load('mfm.php?type=<?php echo $_GET['type']; ?>&viewtree=true','view-tree'); return false;" id="refresh-tree"><?php echo $lng['refresh']; ?></a>
				  <?php print_tree($file_root); ?>
				</li>
			</ul>
		</div>
    <div id="view-files"></div>
  </div>
</body>
</html>