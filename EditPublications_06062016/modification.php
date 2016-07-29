<!DOCTYPE html>
<html>
<head>
	<title>Edit a GREET Publication</title>
	<link rel='shortcut icon' href='../greet/pic/greetLogo.ico' type='image/x-icon' />
	<link rel="stylesheet" href="../css/themes/blue/style.css" type="text/css" media="print, projection, screen" />
</head>
<body style="margin: 0 auto; width: 900px; border-style: solid; border-width: thick;border-color: lightgray;">

<?php
//echo "File name:modification.php";

include_once($_SERVER['DOCUMENT_ROOT'].'/security/init.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/security/functions.php');
class_exists("DistanceMetric") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/DistanceMetric.php");

$metric = new DistanceMetric();

$keyNew = $_POST['key_i'];
$key = $_POST['article_from_author'];//get the original key of the selected article

$title = htmlspecialchars($_POST["Title"]);
$aut = htmlspecialchars($_POST["Author"]);
$abstract = htmlspecialchars($_POST["Abstract"]);
$journal = htmlspecialchars($_POST["journal"]);
//$storageLoc = htmlspecialchars($_POST["storageLoc"]);

//get rid of name initials
$pattn = '/( .\.)/i';
$replmnt = '';
$aut = preg_replace($pattn, $replmnt, $aut);
//$authors = $metric->imultiexplode(array(",",". ",".","-",", "),$aut);
$authors = $metric->imultiexplode(array(" ",", ",". ",".","-", " for ", " on ", " of ", " at ", " and ", " the ", " or ", " et. ", " al. ", " et ", " al ", " etc. ", " etc "),$aut);

$group_nbr = htmlspecialchars($_POST["group"]);

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

if(isset($_POST['remove']) || isset($_POST['denyrm']))
	include "./removePublication.php";
else if(isset($_POST['mod']))
	include "./updatePublication.php";
?>
</body>
</html>