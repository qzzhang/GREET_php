<?php
//echo "File name: addPubli.php";
$key_rand = rand(10000,100000);
echo '<form method="POST" action="./insertPubli.php" enctype="multipart/form-data">';
echo '<br/><h3>Add a new publication</h3>
	  <br/><h4>Please fill the following fields to add an article</h4>';
echo '<p>Title:<br/><textarea name="Title" id="title_insert" rows="2" cols="108">'.$line[0].'</textarea></p>
		<p>Author(s):<br/><textarea name="Author" id="author_insert" rows="2" cols="108"></textarea></p>
		<p>Abstract:<br/><textarea name="Abstract" rows="10" cols="108"></textarea></p>
		<p>Link to journal article: <input type="text" name="journal" id="journal_insert"> Publication Date: <input type="text" name="pubdatepicker" id="pubdatepicker"></p>
		<p>File location (please !!!APPEND!!! the file name): <input type="text" name="storageLoc" style="width:200px" value="public/pdf/2016/***"></p>
		<p>Group
		<select name="group">
			<option value="GMR">GREET Model Reports</option>
			<option value="TP">Technical Publications</option>
			<option value="SGP">Selected GREET Presentations</option>
			<option value="B">Brochures</option>
		</select></p>
		<p>Key (edit as you like) <input type="text" name="key_a" id="key_add" style="width:220px;" value="'.$key_rand.'"></p>
';//<p>Upload <input type="file" name="upfile" id="upfile" accept=".xls, .xlsx, .doc, .docx, .pdf"></p>

//$svr_str = $_SERVER['DOCUMENT_ROOT']."/public/pdf/2015/";
//echo '<br><a href="'.$svr_str.'" >Go to uploads folder</a>';

include "./includes/publiKeyTable.php";

echo '<p><input type="submit" value="Add publication"></p>
		<p><input type="hidden" name="relKeys" id="relKeys"></p>
</form>';

?>
