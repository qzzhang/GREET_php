<?php
if(isset($_POST['remove']))
{
	echo "<br/>removing the publication info......";
	
	include "./includes/rmFromFiles.php";
	include "./includes/rmFromKeywordFiles.php";
	include "./includes/rmFromArticleMapping.php";
	
	$log_desc = "\r\n".date("Y-m-d H:i:s")."---".$_SERVER["REMOTE_ADDR"]."---Removed---".$key;
	include "./includes/write2Log.php";
	
	echo "<br>Publication removed";
}
else if(isset($_POST['denyrm']))
{
	echo "<br/>Remove action cancelled.";
	$log_desc = "\r\n".date("Y-m-d H:i:s")."---".$_SERVER["REMOTE_ADDR"]."---removal canceled---".$key;
	include "./includes/write2Log.php";
}
?>