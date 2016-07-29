<?php
//echo "File name: fields_filled.php";

include "./includes/loadFiles.php";//to set the value for $xml

$key=$_GET['key'];

//get all the file data and keys in arrays
$xfiles_list = $xml->files->file;
$line = array();

foreach($xfiles_list as $xfile)
{
	if($xfile['key'] == $key)
	{
		$line = array($xfile['title'],$used_date,(int)$xfile['size'],$xfile['author'],$xfile['location'],(string)$xfile['key'],$xfile['publication_location'],$xfile['abstract'],$xfile['group'],$xfile->related);
	}
}

if( $line[8] == 1 )
	$line1 = 'value="GMR" selected>GREET Model Reports';
else
	$line1 = 'value="GMR">GREET Model Reports';
if( $line[8] == 2 )
	$line2 = 'value="TP" selected>Technical Publications';
else
	$line2 = 'value="TP">Technical Publications';
if( $line[8] == 4 )
	$line4 = 'value="SGP" selected>Selected GREET Presentations';
else
	$line4 = 'value="SGP">Selected GREET Presentations';
if( $line[8] == 5 )
	$line5 = 'value="B" selected>Brochures';
else
	$line5 = 'value="B">Brochures';

$size_author = max(strlen($line[3][0])*8,100);
$size_title = max(strlen($line[0])*8.5,100)/2;
$size_location = max(strlen($line[4])*8,100);
$size_publication_location = max(strlen($line[6])*8,100);

if( $line[4] == "" )
	$line[4] = "not specified";

echo '<p>Author(s):<br> <input type="text" name="Author" id="author_insert" style="width:'.$size_author.'px" value="'.$line[3][0].'"></p>
		<p>Title:<br>  <textarea name="Title" id="title_insert" rows="2" cols="108">'.$line[0].'</textarea></p>
		<p>Abstract:<br><textarea name="Abstract" rows="10" cols="108">'.$line[7].'</textarea></p>
		<p>Link to journal article <input type="text" name="journal" id="journal_insert" style="width:'.$size_publication_location.'px" value="'.$line[6].'"></p>
		<span>(File location: ' .$line[4]. ')</span>
		<input type="hidden" name="relKeys" id="relKeys"></p>
';//<p>Upload file <input type="file" name="upfile" id="upfile" disabled="true">

echo '<p>Group
		<select name="group">
			<option '.$line1.'</option>
			<option '.$line2.'</option>
			<option '.$line4.'</option>
			<option '.$line5.'</option>
		</select></p>
		<p>Key <input type="text" name="key_i" id="key_insert" style="width:220px;" value="'.$line[5].'"></p>
';

include "./includes/publiKeyTable.php";

echo '<p><input type="submit" name="denyrm" value="Remove publication" onclick="validChange()" id="remove">
		<input type="submit" name="mod" value="Update publication" id="modify"><p/>';
	
?>