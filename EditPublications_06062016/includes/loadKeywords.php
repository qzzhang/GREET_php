<?php
//echo "load the keyword information into xml elements from keywords_without_abstract.xml and keywords_with_abstract.xml";

$kfilenm1 = $_SERVER['DOCUMENT_ROOT']."/static_data/keywords_without_abstract.xml";
$kfilenm2 = $_SERVER['DOCUMENT_ROOT']."/static_data/keywords_with_abstract.xml";

if (file_exists( $kfilenm1 ) && file_exists( $kfilenm2 ) )
{	
	$keyw = simplexml_load_file( $kfilenm1 );
	$keyw_abs = simplexml_load_file( $kfilenm2 );
}
else
{
	echo "Sorry, failed to load the publication and keywords data";
	report_error("loadKeywords.php : failed to open the files.");
}
?>