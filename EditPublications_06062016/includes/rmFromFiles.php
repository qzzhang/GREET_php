<?php
	//echo "Remove entry from files.xml";
	
	include "./includes/loadFiles.php";//to set the values for $xml and $xfilenm
	
	$f_xml = new SimpleXMLElement($xml->asXML());
	foreach($f_xml->files->file as $f)
	{
		if($f['key'] == $key)
		{
			$dom = dom_import_simplexml($f);
			$dom->parentNode->removeChild($dom);
		}
	}

//preserve good formatting after changes to the xml elements
$dom1 = new DOMDocument('1.0');
$dom1->preserveWhiteSpace = false;
$dom1->formatOutput = true;
$dom1->loadXML($f_xml->asXML());
$dom1->save($xfilenm);
?>