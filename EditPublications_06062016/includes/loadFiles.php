<?php
//echo "load the publication information into an xml element from files.xml";

$xfilenm = $_SERVER['DOCUMENT_ROOT']."/static_data/files.xml";
if (file_exists( $xfilenm ))
{	
	$xml = simplexml_load_file( $xfilenm );
}
else
{
	echo "Sorry, failed to load the publication data";
	report_error("loadFiles.php : failed to open the xml file containing publications listing");
}
?>