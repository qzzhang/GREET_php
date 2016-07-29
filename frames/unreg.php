<?php

//This script set the flag "mailing" to 0 for a specified user.
//To use it call this script like this
//greet.es.anl.gov/index.php?content=unregister&email=EMAIL@DOMAIN.COM&key=USER_KEY

$file = $_SERVER['DOCUMENT_ROOT']."/users_data/".$_GET['email'];
$key = $_GET['key'];

$fh = @fopen($file,'r');	
if ($fh==false)
{
	sleep(2);
	echo "cannot find your email in the database";
}
else
{
	fclose($fh);
	$content = file_get_contents($file);
	unlink($file);
	$content = str_replace("mailing=1", "mailing=0", $content);
	$fh = @fopen($file,'w');
	fwrite($fh,$content);
	fclose($fh);
	echo "<br><br>".$_GET['email']." has been removed from the mailing list.<br><br><br>Go back to the <a href=\"http://greet.es.anl.gov\">main page</a>";
}

?>