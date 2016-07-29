<?php
class_exists("DistanceMetric") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/DistanceMetric.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/security/functions.php');

if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml") && file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/test_ktest.xml"))
{	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/filestest.xml");
	$keyw = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/test_ktest.xml");
}
else
{
	echo "Sorry, failed to load the publication data";
	report_error("publi.php : failed to open the xml file containing publications listing");
}

	
$title = htmlspecialchars($_POST["Title"]);
$aut = htmlspecialchars($_POST["Author"]);
$abstract = htmlspecialchars($_POST["Abstract"]);
$loc = htmlspecialchars($_POST["Link"]);
$group_nbr = htmlspecialchars($_POST["group"]);
switch($group_nbr)
{
	case "GMR":
		$group = 1;
	case "TP":
		$group = 2;
	case "SGP":
		$group = 4;
	case "B":
		$group = 5;
}
date_default_timezone_set('America/Chicago');
$dat = date('l jS \of F Y h:i:s A');
$key = rand(10000,100000);

$metric = new DistanceMetric();
$keywords = $metric->multiexplode(array(" ",", ",": "," and "," for "," the "," to "," 1 "," 2 "," 3 "," 4 "," 5 "," 6 "," 7 "," 8 "," 9 "," 0 "," in "," for "," from ",". "," of "," a ","through","their"),$title);
$metric->multiexplode(array(" ",", ",". ",".","-"),$aut);
$keywords = implode(";",$keywords);

////////////////////////////////////////////////////////////////
//Upload the file
$dir = $_SERVER['DOCUMENT_ROOT']."/public/pdf";
$file2upload = $dir.basename($FILES["upfile"]["name"]);
$upcheck = 1;
$filetype = pathinfo($file2upload,PATHINFO_EXTENSION);
if(isset($_POST["submit"]))
{
	$check = getimagesize($_FILES["file2upload"]["tmp_name"]);
	if($check !== false)
	{
		$upcheck = 1;
	}
	else
	{
		$upcheck = 0;
	}
}

//check if upload file already exists
if(file_exists($file2upload))
{
	echo "<br/>This file already exists in the database";
	$upcheck = 0;
}

//check file size
if($_FILES["upfile"]["size"] > 15000000)
{
	echo "<br/>Your file is too large to upload";
	$upcheck = 0;
}

//check file type
if($filetype != "pdf" && $filetype != "doc" && $filetype != "txt" && $filetype != "docx")
{
	echo "<br/>Wrong format, the allowed formats are : pdf, doc, txt";
	$upcheck = 0;
}

//check if $upcheck has been set to 0
if($upcheck == 0)
{
	echo "<br/>Your file was not uploaded";
}
else
{
	if(move_uploaded_file($_FILES["upfile"]["tmp_name"],$file2upload))
	{
		echo "<br/>Your file ".basename($_FILES["upfile"]["name"])." has been uploaded";
	}
	else
	{
		echo "<br/>There was an error uploading your file, please try again";
	}
}
/////////////////////////////////////////////////////////////////////////////////////////

//add file to files.xml
$file = $xml->files->addChild("file");
$file->addAttribute("need_registration",0);
$file->addAttribute("date",$dat);
$file->addAttribute("revision_date","");
$file->addAttribute("title",$title);
$file->addAttribute("abstract",$abstract);
$file->addAttribute("publication_location","");
$file->addAttribute("location",$loc);
$file->addAttribute("filename_user","");
$file->addAttribute("group",$group);
$file->addAttribute("size",filesize($file2upload));
$file->addAttribute("author",$aut);
$file->addAttribute("key",$key);

$xml->asXML($_SERVER['DOCUMENT_ROOT']."/static_data/filestest.xml");

//add file to keywords
$dfile = $keyw->addChild("dfile");
$dfile->addChild("keys",$key);
$dfile->addChild("keywords",$keywords);
$dfile->addChild("relevance",0);

$keyw->asXML($_SERVER['DOCUMENT_ROOT']."/static_data/test_ktest.xml");

echo "<br/>Your publication has been submitted. Thank you !";

?>
