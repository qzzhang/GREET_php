<?php
echo "Updating publication information in the files.xml file...";

include "./includes/loadFiles.php";//to set the values for $xml and $xfilenm

$f_xml = new SimpleXMLElement($xml->asXML());//in the memory
$key_i = $_POST['key_i'];
$relks = explode("/RELK/", $_POST['relKeys']);
	
foreach($f_xml->files->file as $f)
{
	if( $f['key'] == $key )
	{//echo "<br>found key: ". $key ."<br>";

		$f['author'] = $aut;
		$f['title'] = $title;
		$f['abstract'] = $abstract;
		$f["publication_location"] = $journal;
		//$f['location'] = $storageLoc;
		$f['group'] = $id_group;
		$f['key'] = $key_i;
		
		if( $key != $key_i ) // otherwise, no need to modify alt_key
		{
			//check if the alt_keys already have the keys $key_i or $key, if so remove first
			foreach($f->alt_key as $altk)
			{
				if ($altk['old_key'] == $key_i) //allow recylcing old_key as a new one, but remove it from the <alt_key /> tag
				{//echo "<br>found alt_key: ". $key_i;
					$dom = dom_import_simplexml($altk);
					$dom->parentNode->removeChild($dom);	
				}
			
				if ($altk['old_key'] == $key) //if the old_key-tobe is already in <alt_key />, remove it first to avoid redundant
				{//echo "<br>found alt_key: ". $key;
					$dom = dom_import_simplexml($altk);
					$dom->parentNode->removeChild($dom);	
				}
			}
			//add the old_key-tobe into the <alt_key />
			$alt = $f->addChild("alt_key");
			$alt['old_key'] = $key;
		}
		while($f->related)
		{//remove all the related keys before re-populating
			$rlk = $f->related;
			$dom = dom_import_simplexml($rlk);
			$dom->parentNode->removeChild($dom);
		}
		foreach($relks as $rkitem) 
		{//echo "add all the newly modified keys";
			if( $rkitem != "" )
			{
				$rel = $f->addChild("related");
				$rel['key'] = $rkitem;
			}
		}
	}
}
//preserve good formatting after changes to the xml elements
$dom1 = new DOMDocument('1.0');
$dom1->preserveWhiteSpace = false;
$dom1->formatOutput = true;
$dom1->loadXML($f_xml->asXML());
$dom1->save($xfilenm);

echo "<br/>";
?>
