<?php
//echo "File name:edit_remove.php";

//get all the files data in an array
$authors_list=array();
include "./includes/loadArticleMapping.php";//to set the value for $article_mapping

foreach($article_mapping->corpus as $corpus)
{
	if( $corpus['author'] && (string)$corpus['author'] != "")
		array_push($authors_list,(string)$corpus['author']);
}
sort($authors_list);

echo '<form method="POST" action="./modification.php" enctype="multipart/form-data" onsubmit="selectAll(\'relatedKeys\');">
<br/><h3>Modify/remove a publication</h3>
<br/><h4>If you want to modify or remove an article, please select an author name:</h4>
<select name="author" id="authors" onchange="getArticlesByAuthor(this.options[this.selectedIndex].text);">
<option selected value="default">Please select an author</option>';

foreach($authors_list as $key=>$val)
{
	echo '<option value="'.$val.'">'.$val.'</option>';
}
echo '</select>';

//div to be filled by the articles
echo '<br/><div id="title_from_author" name="title_from_author"></div>';

//div with the fields
echo '<br/><div id="fields_filled" name="fields_filled"></div>';

echo '</form>';

?>
