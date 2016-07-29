<?php
	//echo "Remove entries in the keywords_without_abstract.xml and keywords_with_abstract.xml files.";
	
	include "./includes/loadKeywords.php";//to set the values for $keyw and $keyw_abs; $kfilenm1 and $kfilenm2
	
	$obj_xml = new SimpleXMLElement($keyw->asXML());
	
	///Remove entries in the keywords_without_abstract.xml file
	foreach($obj_xml->dfiles->dfile as $f1)
	{	
		if($f1->keys==$key)
		{
			$dom = dom_import_simplexml($f1);
			$dom->parentNode->removeChild($dom);			
		}
	}
//preserve good formatting after changes to the xml elements
$dom1 = new DOMDocument('1.0');
$dom1->preserveWhiteSpace = false;
$dom1->formatOutput = true;
$dom1->loadXML($obj_xml->asXML());
$dom1->save($kfilenm1);
	//$obj_xml->asXML( $kfilenm1 );
	echo "<br>removed from keywords file keywords_without_abstract.xml";
	
	///Remove entries in the keywords_with_abstract.xml file
	$obj_xml_abs = new SimpleXMLElement($keyw_abs->asXML());
	foreach($obj_xml_abs->dfiles->dfile as $f2)
	{	
		if($f2->keys==$key)
		{
			$dom = dom_import_simplexml($f2);
			$dom->parentNode->removeChild($dom);
		}
	}
//preserve good formatting after changes to the xml elements
$dom2 = new DOMDocument('1.0');
$dom2->preserveWhiteSpace = false;
$dom2->formatOutput = true;
$dom2->loadXML($obj_xml_abs->asXML());
$dom2->save($kfilenm2);
	//$obj_xml_abs->asXML( $kfilenm2 );
	echo "<br>removed from keywords file keywords_with_abstract.xml";
?>