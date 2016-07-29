<?php
if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml") && file_exists($_SERVER['DOCUMENT_ROOT']."/includes/article_mapping.xml"))
{	
	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
	$article_mapping = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/includes/article_mapping.xml");
}
else
{
	echo "Sorry, failed to load the publication data";
	report_error("publi.php : failed to open the xml file containing publications listing");
}




?>