<?php
	echo "Remove entry from the article_mapping.xml";
	
	include "./includes/loadArticleMapping.php";//to set the values for $article_mapping and $afilenm
	
	function hasChild($p) 
	{
		if ($p->hasChildNodes()) 
		{
			foreach ($p->childNodes as $d) {
				if ($d->nodeType == XML_ELEMENT_NODE)
					return true;
			}
		}
		return false;
	}

	$c_xml = new SimpleXMLElement($article_mapping->asXML());
	foreach($c_xml->corpus as $c)
	{
		foreach($c->article as $a)
		{
			if($a['key'] == $key)
			{//echo "Found the key: " . $key;
				$dom = dom_import_simplexml($a);
				$dom->parentNode->removeChild($dom);
			}
		}
	}
	
//preserve good formatting after changes to the xml elements
$dom1 = new DOMDocument('1.0');
$dom1->preserveWhiteSpace = false;
$dom1->formatOutput = true;
$dom1->loadXML($c_xml->asXML());
$dom1->save($afilenm);
	//echo "<br>removed from file article_mapping.xml";
?>