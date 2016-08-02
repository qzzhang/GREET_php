<?php
//echo "load the article_mapping information into an xml element.";

$afilenm = $_SERVER['DOCUMENT_ROOT']."/EditPublications/article_mapping.xml";

if (file_exists( $afilenm ) )
{	
	$article_mapping = simplexml_load_file( $afilenm );
}
else
{
	echo "Sorry, failed to load the publication data";
	report_error("loadArticleMapping.php : failed to open the xml file containing publications listing");
}
?>