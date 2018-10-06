<?php 
function editBox($var)
{
	$error = '';
	$source = CMSCONTENTSOURCE;
	if($source == "db")
	{
		//DB connection 
		$con=mysql_connect(DBHOST,DBUSER,DBPASSWORD); 
		$dbc= mysql_select_db(DBNAME,$con);
		mysql_query("set names utf8");
		$result = mysql_query('SELECT *	FROM tbl_cms WHERE cmsVariable = "' . $var . '"');
		$row = mysql_fetch_array($result);
		$content = $row['cmsContent'.(isset($_SESSION['lng']) ? $_SESSION['lng'] : "")];
		$prvStatus = (empty($row['cmsPrvContent']))?'n':'y';
		$date = date('m/d/y, G:i',strtotime($row['cmsDateModified']));
	}
	else
	{
		if(file_exists(CMSCONTENTDIR))
		{
			$content = '';
			if(defined("CMSCONTENTDIR")) 
			{
				$lines = file(CMSCONTENTDIR.$var.".cms");
				$prvContent = file_get_contents(CMSCONTENTDIR.$var.".prv");
				if(file_exists(CMSCONTENTDIR.$var.".prv") and !empty($prvContent))
					$prvStatus = 'y';
				else
					$prvStatus = 'n';
			}
			else
			{
				$lines = file("cms/content/".$var.".cms");
				$prvContent = file_get_contents("cms/content/".$var.".prv");
				if(file_exists("cms/content/".$var.".prv") and !empty($prvContent))
					$prvStatus = 'y';
				else
					$prvStatus = 'n';
			}
									 
			$date = date('m/d/y, G:i',strtotime($lines[0]));
			array_shift($lines);
			foreach ($lines as $line) {
				$content .= $line;
			}
		}
		else
			$error = "<h1 style='color:#ff0000'>The directory path \"".CMSCONTENTDIR."\" does not found.</h1>";
	}
	
	if(!defined("HOST_URL"))
		$error = "<h1 style='color:#ff0000'>You have not defined the HOST_URL.</h1>";
	elseif(!defined("CMSCONTENTSOURCE"))
		$error = "<h1 style='color:#ff0000'>You have not defined the CMSCONTENTSOURCE.</h1>";
	elseif(!file_exists(CMSLIBRARYDIR))
		$error = "<h1 style='color:#ff0000'>The directory path \"".CMSLIBRARYDIR."\" does not found.</h1>";
	else if(!file_exists(CMSCSSFILE))
		$error = "<h1 style='color:#ff0000'>The file path \"".CMSCSSFILE."\" does not found.</h1>";
	else if(!file_exists(CMSTEMPLATESFILE))
		$error = "<h1 style='color:#ff0000'>The file path \"".CMSTEMPLATESFILE."\" does not found.</h1>";
	
	if(!empty($error))
	{
		return $error;
	}
	else if(isset($_SESSION['cmsEditMode']) and $_SESSION['cmsEditMode'] == "show")	
	{	

		/*return '<div class="cmsEd_header">
			<div  class="cmsEd_title"><b>Editable Content</b></div>
			<div class="cmsEd_buttons" ><a href="javascript:void(0);" onclick="resetcontent(\''.$var.'\',\'cn\',\''.$source.'\')" class="cmsEd_restoreBtn" title="Factory Restore: This will Restore the content of the Editable Region below to Default Factory Version.">&nbsp;</a>'.(($prvStatus == 'y')?'<a href="javascript:void(0);" onclick="resetcontent(\''.$var.'\',\'pn\',\''.$source.'\')" class="cmsEd_prev_restoreBtn" title="Restore: This will Restore the content of the Editable Region below to Last Saved Version.">&nbsp;</a>':'').'<a href="#" class="cmsEd_editBtn" id="cmsEd_'. $var .'_editBtn" source="'.$source.'" title="Edit Content">&nbsp;</a></div>
		</div>
		<div class="cms_contentWrapper">
		<div class="cmsEd_contentArea" id="cmsEd_'. $var .'" title="Last Edited: '. trim($date) .'">'. stripslashes($content) .'</div>
		<div class="cmsEd_updatedDateWrapper" id="cmsEd_'. $var .'_lastUpdated"><strong>Last Updated:</strong> '. trim($date) .'</div>
		</div>
		<br style="clear:both;">';*/
		
		return '<div class="cmsEd_header">
			<div  class="cmsEd_title"><b>Editable Content</b></div>
			<div class="cmsEd_buttons" ><a href="#" class="cmsEd_editBtn" id="cmsEd_'. $var .'_editBtn" source="'.$source.'" title="Edit Content">&nbsp;</a></div>
		</div>
		<div class="cms_contentWrapper">
		<div class="cmsEd_contentArea" id="cmsEd_'. $var .'" title="Last Edited: '. trim($date) .'">'. stripslashes($content) .'</div>
		<div class="cmsEd_updatedDateWrapper" id="cmsEd_'. $var .'_lastUpdated"><strong>Last Updated:</strong> '. trim($date) .'</div>
		</div>
		<br style="clear:both;">';
		
	} else {
		return stripslashes($content);
	}
}
?>