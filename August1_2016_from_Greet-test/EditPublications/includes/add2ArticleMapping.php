<?php
echo "Adding publication information to the article_mapping.xml file...";

include "./includes/loadArticleMapping.php";//to set the values for $article_mapping and $afilenm

foreach($authors as $auth)
{
	$authorin_map = false;
	foreach($article_mapping->corpus as $c)
	{
		$keyin_map = false;
		if( strlen($auth) > 1 && $c['author'] == $auth)
		{//echo "Found author";
			foreach( $c ->article as $a )
			{
				if( $a['key'] == $key_a )
				{
					$keyin_map = true;
					$a['title'] = $title;
					break;
				}
			}
			if( $keyin_map == false)
			{
				$a_new = $c->addChild("article");
				$a_new->addAttribute("title",$title);
				$a_new->addAttribute("key",$key_a);
			}
			$authorin_map = true;
			break;
		}
	}
	if( strlen($auth) > 1 && $authorin_map==false)
	{//echo "Author not found";
		$corpus_new = $article_mapping->addChild("corpus");
		$corpus_new->addAttribute("author",$auth);
		$a_new = $corpus_new->addChild("article");
		$a_new->addAttribute("title",$title);
		$a_new->addAttribute("key",$key_a);
	}
}

//preserve good formatting after changes to the xml elements
$dom1 = new DOMDocument('1.0');
$dom1->preserveWhiteSpace = false;
$dom1->formatOutput = true;
$dom1->loadXML($article_mapping->asXML());
$dom1->save($afilenm);

echo "<br/>";
?>