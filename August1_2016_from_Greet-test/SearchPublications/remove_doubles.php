<?php

$kwfilenm = $_SERVER['DOCUMENT_ROOT']."/static_data/keywords_with_abstract.xml";
if (file_exists($kwfilenm))
{
	$keyw = simplexml_load_file($kwfilenm);
}


//remove doublons
foreach($keyw->dfiles->dfile as $kwa)
{
	$krel = explode(";",$kwa->keywords);
	$nk = array();
	foreach($krel as $kk)
	{
		
		if(!in_array($kk,$nk))
		{
			array_push($nk,$kk);
		}
	}
	$nk = implode(";",$nk);
	$kwa->keywords = $nk;
}
$keyw->asXML($kwfilenm);
echo "<br/>Doublon removed";

?>