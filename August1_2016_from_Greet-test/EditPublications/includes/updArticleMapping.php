<?php
echo "Updating publication information in article_mapping.xml file...";

include "./includes/loadArticleMapping.php";//to set the values for $article_mapping and $afilenm

foreach($authors as $auth)
{
	$in_map = false;
	foreach($article_mapping->corpus as $c)
	{
		$in_c = false;
		if( strlen($auth) > 1 && $c['author'] == $auth)
		{//echo "found author";
			foreach($c->article as $art)
			{
				if($art['key']==$key)
				{//update the article
					$art['title'] = $title;
					$in_c = true;
					$art['key'] = $keyNew;
					break;
				}
			}
			if($in_c == false)
			{//add the article
				$a_new = $c->addChild("article");
				$a_new->addAttribute("title",$title);
				$a_new->addAttribute("key",$keyNew);
			}
			$in_map = true;
			break;
		}
	}
	if( strlen($auth) > 1 && $in_map==false)
	{//echo "not found author";
		$corpus_new = $article_mapping->addChild("corpus");
		$corpus_new->addAttribute("author",$auth);
		$a_new = $corpus_new->addChild("article");
		$a_new->addAttribute("title",$title);
		$a_new->addAttribute("key",$keyNew);
	}
}

//preserve good formatting after changes to the xml elements
$dom1 = new DOMDocument('1.0');
$dom1->preserveWhiteSpace = false;
$dom1->formatOutput = true;
$dom1->loadXML($article_mapping->asXML());

//$afilenm1 = $_SERVER['DOCUMENT_ROOT']."/EditPublications/article_mapping_test.xml";
$dom1->save($afilenm);
echo "<br/>";
?>