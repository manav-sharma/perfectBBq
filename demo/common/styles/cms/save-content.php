 <?php
session_start();
require("../configs/system.php");

$cmseditorId = $_POST['cmseditor_id'];
//$cmseditorContent = addslashes($_POST['cmseditor_content']);
$cmseditorContent = $_POST['cmseditor_content'];
$cmsContentSource = CMSCONTENTSOURCE;

if($cmsContentSource == "db")
{
	//DB connection 
	$cl = mysql_connect(DBHOST,DBUSER,DBPASSWORD);
	mysql_select_db(DBNAME,$cl);
	
	mysql_query("SET NAMES 'utf8'");
	$SQLQuery = mysql_query('select * from tbl_cms where cmsVariable = "' . $cmseditorId . '"') or die(mysql_error());
	if(mysql_num_rows($SQLQuery) < 1)
		mysql_query('insert into tbl_cms (cmsVariable,cmsContent'.$_SESSION['lng'].',cmsOriContent,cmsDateModified) values("' . $cmseditorId . '", "' . mysql_escape_string($cmseditorContent) . '", "' . $cmseditorContent . '","' . date("Y-m-d H:i:s") . '")') or die(mysql_error());
	else
	{
		mysql_query('UPDATE tbl_cms SET cmsPrvContent = cmsContent'.$_SESSION['lng'].',cmsContent'.$_SESSION['lng'].' = "' . mysql_escape_string($cmseditorContent) . '", cmsDateModified = "' . date("Y-m-d H:i:s") . '" WHERE cmsVariable = "' . $cmseditorId . '"') or die(mysql_error());
	}
}
else
{
	$cmseditorContent = ''. date("Y-m-d H:i:s") . "\n". $cmseditorContent;
	
	if(defined("CMSCONTENTDIR"))
	{		
		$fileName = "../".CMSCONTENTDIR.$cmseditorId.".cms";
		$prvFileName = "../".CMSCONTENTDIR.$cmseditorId.".prv";
	}
	else
	{
		$fileName = "content/".$cmseditorId.".cms";
		$prvFileName = "content/".$cmseditorId.".prv";
	}
	
	if(is_file($fileName))
		copy($fileName, $prvFileName);
		
	if(!file_exists($fileName))
	{
		$bckFileName = "../".CMSCONTENTDIR.$cmseditorId.".bck";
		$bckFileHandle = fopen($bckFileName, 'w') or die("can't open file");
		if (fwrite($bckFileHandle, stripslashes($cmseditorContent)) === FALSE) {
			echo "Cannot write to file ($bckFileName)";
			exit;
		}
		fclose($bckFileHandle);
	}
	
	if (!$handle = fopen($fileName, 'w')) {
         echo "Cannot open file ($filename)";
         exit;
    }
    if (fwrite($handle, stripslashes($cmseditorContent)) === FALSE) {
        echo "Cannot write to file ($fileName)";
        exit;
    }
	fclose($handle);
}
?>