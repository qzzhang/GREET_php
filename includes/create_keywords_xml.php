<DOCTYPE html>
<html>
<head>
<title>Testing publications</title>
<script src="../javascripts/publications.js"></script>
</head>
<body>

<?php
class_exists("TableSorter") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/TableSorter.php");
class_exists("DistanceMetric") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/DistanceMetric.php");

$srchstr = $_GET['research'];
if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
{	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");}
else
{
		echo "Sorry, failed to load the publication data";
		report_error("publi.php : failed to open the xml file containing publications listing");
}
echo "<br/>IN";
$xfiles_list = $xml->files->file;

$metric = new DistanceMetric();

$metric->generate_keywords_xml($xfiles_list);

echo "<br/>The XML file has been succesfully generated";
?>
</body>
</html>