<!DOCTYPE html>
<html>
<head>
	<title>Article form</title>
	<script src="../javascripts/publications.js"></script>
</head>
<body style="background-color:rgb(235,235,235)">
<?php
if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml") && file_exists($_SERVER['DOCUMENT_ROOT']."/includes/article_mapping.xml"))
{	
	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
	$article_mapping = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/includes/article_mapping.xml");
}
else
{
	echo "Sorry, failed to load the publication data";
	report_error("publi.php : failed to open the xml file containing publications listing");
}


//get all the files data in an array
$xfiles_list = $xml->files->file;
echo '<form method="POST" action="insert.php" enctype="multipart/form-data">
<br/><h2>Modify/remove/add an article</h2>
<br/><h4>If you want to modify or remove an article, choose an author</h4><br/>
<select name="author" id="authors" onchange="getArticlesByAuthor(this.options[this.selectedIndex].text);">
<option selected value="default">Please select an author</option>';

foreach($article_mapping->corpus as $corpus)
{
	echo '<option value="'.$corpus['author'].'">'.$corpus['author'].'</option>';
}
echo '</select>';

//div to be filled by the articles
echo '<br/><div id="title_from_author" name="title_from_author">';
echo '</div>';

//div with the fields
echo '<br/><div id="fields_filled" name="fields_filled" >';
echo '</div>';

echo '<form method="POST" action="insert.php">';
echo "<br/><h4>Else fill the following fields to add an article</h4>";
echo '<p>Author <input type="text" name="Author" id="author_insert"></p>
		<p>Title <input type="text" name="Title" id="title_insert"></p>
		<p>Abstract</p><p> <textarea name="Abstract" rows="10" cols="90"></textarea></p>
		<p>Location <input type="text" name="Link" id="link_insert"></p>
		<p>Upload (max 15Mo)<input type="file" name="upfile" id="upfile"></p>
		<p>Group
		<select name="group">
			<option value="GMR">GREET Model Reports</option>
			<option value="TP">Technical Publications</option>
			<option value="SGP">Selected GREET Presentations</option>
			<option value="B">Brochures</option>
		</select></p>
		<p><input type="submit" value="add publication"></p>
</form>';



?>
</body>
</html>