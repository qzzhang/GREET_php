<?php
function fill_dropdown($author){
	if (file_exists($_SERVER['DOCUMENT_ROOT']."/includes/article_mapping.xml"))
	{	
		$article_mapping = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/includes/article_mapping.xml");
	}
	foreach($article_mapping->corpus as $corpus)
	{
		if($corpus['author'] == $author)
		{
			foreach($corpus->article as $article)
			{
				echo '<option value="'.$article['key'].'">'.$article['title'].'</option>';
			}
			break;
		}
	}
}

$aut =$_GET["author"];
echo "<select onchange='fillallfields(this.options[this.selectedIndex].value);'>";//get the key of the selected article
echo "<option value='default' selected>Please select one article</option>";
fill_dropdown($aut);
echo "</select>";
?>