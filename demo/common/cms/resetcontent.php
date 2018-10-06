<?php
require("../configs/system.php");

$cmsContentSource = CMSCONTENTSOURCE;

if($cmsContentSource == "db") //Restore system for database
{
	//DB connection 
	$cl = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD);
	mysql_select_db(DB_NAME,$cl);
	
	mysql_query("SET NAMES 'utf8'");
	//Restore data from previous saved.
	if(isset($_POST['pn']) and !empty($_POST['pn']))
	{
		$resultSet = mysql_query('select * from tbl_cms where cmsVariable = "' . $_POST['pn'] . '"');
		if(mysql_num_rows($resultSet) > 0)
		{
			$cmsPreviousContent = mysql_result($resultSet,0,"cmsPrvContent");
			if(!empty($cmsPreviousContent))
				mysql_query('UPDATE tbl_cms SET cmsContent = cmsPrvContent,cmsPrvContent = "", cmsDateModified = "' . date("Y-m-d H:i:s") . '" WHERE cmsVariable = "' . $_POST['pn'] . '"');
		}
	}
	//Restore all data as factory setting.
	elseif($_POST['cn'] == 'all')
		mysql_query('UPDATE tbl_cms SET cmsContent = cmsOriContent,cmsPrvContent = "", cmsDateModified = "' . date("Y-m-d H:i:s") . '"');
	//Restore data as factory setting.
	else if(isset($_POST['cn']) and !empty($_POST['cn']))
		mysql_query('UPDATE tbl_cms SET cmsContent = cmsOriContent,cmsPrvContent = "", cmsDateModified = "' . date("Y-m-d H:i:s") . '" WHERE cmsVariable = "' . $_POST['cn'] . '"');
}
else //Restore system for files.
{	
	if(defined("CMSCONTENTDIR"))
		$contentdirectory = "../".CMSCONTENTDIR;
	else
		$contentdirectory = "content/";
	
	//Restore data from previous saved.
	if(isset($_POST['pn']) and !empty($_POST['pn']))
	{
		$cmsfilename = $contentdirectory.$_POST['pn'].".cms";
		$prvfilename = $contentdirectory.$_POST['pn'].".prv";
		
		if(is_file($prvfilename))
		{
			copy($prvfilename, $cmsfilename);
			@unlink($prvfilename);
		}		
	}
	//Restore all data as factory setting.
	elseif($_POST['cn'] == 'all')
	{
		foreach (glob($contentdirectory."*.bck") as $filename) 
		{
			if(is_file($filename))
			{
				$destinationFileName = preg_replace('/(.bck)$/i', '.cms', $filename);
				$prvFileName = preg_replace('/(.bck)$/i', '.prv', $filename);
				copy($filename,$destinationFileName);
				@unlink($prvFileName);
			}
		}
	}
	//Restore data as factory setting.
	else if(isset($_POST['cn']) and !empty($_POST['cn']))
	{
		$cmsfilename = $contentdirectory.$_POST['cn'].".cms";		
		$backfilename = $contentdirectory.$_POST['cn'].".bck";
		$prvkfilename = $contentdirectory.$_POST['cn'].".bck";
		
		if(is_file($backfilename))
		{
			copy($backfilename, $cmsfilename);
			@unlink($prvkfilename);
		}
	}
}
exit;
?>