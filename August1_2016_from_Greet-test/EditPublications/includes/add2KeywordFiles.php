<?php
echo "Adding publication keyword information to the corresponding files...";

include "./includes/loadKeywords.php";//to set the values for $keyw and $keyw_abs; $kfilenm1 and $kfilenm2

//extracting the keywords for this publication after modifications from web form inputs "Title", "Author" and "Abstract"
$keywords = $metric->multiexplode(array(" ",", ",": "," and "," for "," the "," to ","-"," 1 "," 2 "," 3 "," 4 "," 5 "," 6 "," 7 "," 8 "," 9 "," 0 "," in "," for "," from ",". "," of "," a ","through","their"),$title);

foreach($authors as $a)
{
	if( strlen($a) > 1  )
		array_push($keywords,$a);
}

$keywords_abs = $keywords;
$abst = $metric->multiexplode(array(" ",", ",": "," and "," for "," the "," to ","-"," 1 "," 2 "," 3 "," 4 "," 5 "," 6 "," 7 "," 8 "," 9 "," 0 "," in "," for "," from ",". "," of "," a ","through","their","<",">","&"),$abstract);
foreach($abst as $ab)
{
	if( strlen($ab) > 1  )
		array_push($keywords_abs,$ab);
}
$keywords = implode(";",$keywords);
$keywords_abs = implode(";",$keywords_abs);

//add file to keywords without abstract
$dfile = $keyw->dfiles->addChild("dfile");
$dfile->addChild("keys",$key_a);
$dfile->addChild("keywords",$keywords);
$dfile->addChild("relevance",0);

//preserve good formatting after changes to the xml elements
$dom1 = new DOMDocument('1.0');
$dom1->preserveWhiteSpace = false;
$dom1->formatOutput = true;
$dom1->loadXML($keyw->asXML());
$dom1->save($kfilenm1);
//$keyw->asXML( $kfilenm1 );

//add file to keywords with abstract
$abfile = $keyw_abs->dfiles->addChild("dfile");
$abfile->addChild("keys",$key_a);
$abfile->addChild("keywords",$keywords_abs);
$abfile->addChild("relevance",0);

//preserve good formatting after changes to the xml elements
$dom2 = new DOMDocument('1.0');
$dom2->preserveWhiteSpace = false;
$dom2->formatOutput = true;
$dom2->loadXML($keyw_abs->asXML());
$dom2->save($kfilenm2);
//$keyw_abs->asXML( $kfilenm2 );

echo "<br/>";
?>