<!DOCTYPE html>
<html>
<head>
	<title>Insert a GREET Publication</title>
	<link rel='shortcut icon' href='../greet/pic/greetLogo.ico' type='image/x-icon' />
	<link rel="stylesheet" href="../css/themes/blue/style.css" type="text/css" media="print, projection, screen" />
</head>
<body style="margin: 0 auto; width: 900px; border-style: solid; border-width: thick;border-color: lightgray;">

<?php
//echo "File name: insertPubli.php";

$title = htmlspecialchars($_POST["Title"]);
$aut = htmlspecialchars($_POST["Author"]);
$abstract = htmlspecialchars($_POST["Abstract"]);
if( $title == "" || $aut == "" )
{
	echo "Please enter your publication title and author name(s) before saving."; 
    exit;
}

$group_nbr = htmlspecialchars($_POST["group"]);
$journal = htmlspecialchars($_POST["journal"]);
$storageLoc = htmlspecialchars($_POST["storageLoc"]);
$dat = htmlspecialchars($_POST["pubdatepicker"]);
if($dat == "" )
{
	date_default_timezone_set('America/Chicago');
    $dat = date('m-d-Y');
}

//convert the date format to 'dd-mm-yy'
$tempDate = explode('-',$dat);
$dat = $tempDate[2].'-'.$tempDate[0].'-'.$tempDate[1];

class_exists("DistanceMetric") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/DistanceMetric.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/security/functions.php');
$metric = new DistanceMetric();

if($_POST['key_a'] == "")
{
	$key_a = rand(10000,100000);
}
else
{
	$key_a = htmlspecialchars($_POST['key_a']);
}

include "./includes/loadFiles.php";//to set the value for $xml

$xfiles_list = $xml->files->file;

//get all the file keys in an array
$xfiles_list = $xml->files->file;

foreach($xfiles_list as $xf)
{
	if( $xf["key"] == $key_a )// && $xf["location"] == $storageLoc)
	{
		echo $key_a.' already exists.<br>';
		$key_a .= "-" . $dat; //rand(10000,100000);
		echo "<br>Suggested new key: " .$key_a . "<br>";
		break;
	}
}

//get rid of name initials
$pattn = '/(^.\.)|(,.\.)|( .\.)/i';
$replmnt = '';
$authors = preg_replace($pattn, $replmnt, $aut);
$authors = $metric->imultiexplode(array(" ",", ",". ",".","-", " for ", " on ", " of ", " at ", " and ", " the ", " or ", " et. ", " al. ", " et ", " al ", " etc. ", " etc "),$authors);

$id_group = 0;
if($group_nbr == "GMR")
{
	$id_group = 1;
}
elseif($group_nbr == "TP")
{
	$id_group = 2;
}
elseif($group_nbr == "SGP")
{
	$id_group = 4;
}
elseif($group_nbr == "B")
{
	$id_group = 5;
}

include "./includes/add2Files.php";
include "./includes/add2ArticleMapping.php";
include "./includes/add2KeywordFiles.php";
//include "./includes/uploadFile.php";

$log_desc = "\r\n".date("Y-m-d H:i:s")."---".$_SERVER["REMOTE_ADDR"]."---Added---".$key_a;
include "./includes/write2Log.php";

echo "<br/>Your publication has been submitted. Thank you !";

?>

</body>
</html>
