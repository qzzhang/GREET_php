<?php
echo "Updating publication keyword information in corresponding files...";

include "./includes/loadKeywords.php";//to set the values for $keyw and $keyw_abs; $kfilenm1 and $kfilenm2

//extracting the keywords for this publication after modifications from web form inputs "Title", "Author" and "Abstract"
$metric = new DistanceMetric();
$keywords = $metric->multiexplode(array(" ",", ",": "," and "," for "," the "," to "," 1 "," 2 "," 3 "," 4 "," 5 "," 6 "," 7 "," 8 "," 9 "," 0 "," in "," for "," from ",". "," of "," a ","through","their"),$title);
$authors=$metric->multiexplode(array(" ",", ",". ",".","-"),$aut);
foreach($authors as $a)
{
	array_push($keywords,$a);
}

$keywords_abs = $keywords;
$abst = $metric->multiexplode(array(" ",", ",": "," and "," for "," the "," to "," 1 "," 2 "," 3 "," 4 "," 5 "," 6 "," 7 "," 8 "," 9 "," 0 "," in "," for "," from ",". "," of "," a ","through","their","<",">","&"),$abstract);
foreach($abst as $ab)
{
	array_push($keywords_abs,$ab);
}
$keywords = implode(";",$keywords);
$keywords_abs = implode(";",$keywords_abs);

foreach($keyw->dfiles->dfile as $d)
{
	if($d->keys == $key)
	{
		$d->relevance = 0;
		$d->keywords = $keywords;
		$d->keys = $_POST['key_i'];
	}
}
foreach($keyw_abs->dfiles->dfile as $d)
{
	if($d->keys == $key)
	{
		$d->relevance = 0;
		$d->keywords = $keywords_abs;
		$d->keys = $_POST['key_i'];
	}
}
$keyw->asXML( $kfilenm1 );
$keyw_abs->asXML( $kfilenm2 );
echo "<br/>";
?>
