<?php
$key=$_GET['key'];

if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
{	
	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
}

//get all the files data in an array
$xfiles_list = $xml->files->file;
$line = array();

foreach($xfiles_list as $xfile)
{
	if($xfile['key'] == $key)
	{
		$line = array($xfile['title'],$used_date,(int)$xfile['size'],$xfile['author'],$xfile['location'],(string)$xfile['key'],$xfile['publication_location'],$xfile['abstract'],$xfile['group']);
	}
}


switch($line[8])
{
	case 1:
		$line[8]="Greet Model Report";
		break;
	case 2:
		$line[8]="Technical Publication";
		break;
	case 4:
		$line[8]="Selected Greet Presentations";
		break;
	case 5:
		$line[8]="Brochures";
		break;
}

$size_author = strlen($line[3][0])*8;
$size_title = strlen($line[0])*7.5;
$size_location = strlen($line[4])*7.5;


echo '<p>Author <input type="text" name="Author" id="author_insert" style="width:'.$size_author.'px" value="'.$line[3][0].'"></p>
		<p>Title <input type="text" name="Title" id="title_insert" style="width:'.$size_title.'px" value="'.$line[0].'"></p>
		<p>Abstract</p><p> <textarea name="Abstract" rows="10" cols="90">'.$line[7].'</textarea></p>
		<p>Location <input type="text" name="Link" id="link_insert" style="width:'.$size_location.'px" value="'.$line[4].'"></p>
		<p>Upload (max 15Mo)<input type="file" name="upfile" id="upfile"></p>
		<p>Group <input type="text" name"group" id="group" value="'.$line[8].'"></p>
		<p><input type="submit" value="remove_publication">
		<input type="submit" value="modify_publication"><p/></form>';
		
?>