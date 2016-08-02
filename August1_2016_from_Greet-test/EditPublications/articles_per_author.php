<?php
//echo "File name: articles_per_author.php";
include "./includes/arrayFuncs.php";

$aut =$_GET["author"];
include "./includes/loadArticleMapping.php";
$art_list = array();

foreach($article_mapping->corpus as $corpus)
{
	if($corpus['author'] == $aut)
	{
		foreach($corpus->article as $article)
			$art_list[] = array('akey' => (string)$article['key'], 'atitle' => (string)$article['title']);
		break;
	}
}
$art_list = array_orderby( $art_list, 'atitle', SORT_ASC, 'akey', SORT_DESC);

//echo "<table><tr>";
echo "<select name='article_from_author' style='width: 99%' onchange='fillallfields(this.options[this.selectedIndex].value);'>";//Note: the value of the selected item is the key of the publication.
echo "<option value='default' selected>Please select one article</option>";
//to set the value for $article_mapping
foreach( $art_list as $art)
	echo '<option value="'.$art['akey'].'">'.$art['atitle'].'</option>';
echo "</select>";

//echo "</tr></table>";
?>