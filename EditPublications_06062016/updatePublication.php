<?php
if(isset($_POST['mod']))
{
	echo "<br>Updating the article info......<br><br>";

	include "./includes/updArticleMapping.php";
	include "./includes/updFiles.php";
	include "./includes/updKeywordFiles.php";
	
	date_default_timezone_set('America/Chicago');
	$log_desc = "\r\n".date("Y-m-d H:i:s")."---".$_SERVER["REMOTE_ADDR"]."---Modified---".$key;
	include "./includes/write2Log.php";
	include "./includes/uploadFile.php";
	include "./includes/emailGreet.php";
	echo "<br/>Finished!";
	//echo "<br/>".date("Y-m-d H:i:s")."---".$_SERVER["REMOTE_ADDR"]."---Modified---".$key;
}
?>
