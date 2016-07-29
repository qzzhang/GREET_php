<!DOCTYPE html>
<html>
<head>
	<title>GREET Publication DB</title>
	<!--meta http-equiv="Content-Security-Policy" content="script-src https://code.jquery.com 'self';"-->
	<link rel='shortcut icon' href='../greet/pic/greetLogo.ico' type='image/x-icon' />
	<link rel="stylesheet" href="../css/themes/blue/style.css" type="text/css" media="print, projection, screen" />
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  	<script src="../javascripts/editPublications.js"></script>
	<script>
	$(function(){
$('body').on('click', '#pubdatepicker', function() { 
$('#pubdatepicker').datepicker(); 
$('#pubdatepicker').datepicker( "option", "dateFormat", "mm-dd-yy" );
$('#pubdatepicker').datepicker('show');
});
});
</script>
</head>
<body style="margin: 0 auto; width: 900px; border-style: solid; border-width: thick;border-color: lightgray;">

<?php
echo "<h2><center>Edit/Add/Remove entries in the GREET Publications database</center></h2>";
//phpinfo();

echo "
	<button id='btn_edrm' onclick ='loadForm(\"edit_remove\");'>Edit/Remove existing publication</button>
	<button id='btn_add' onclick='loadForm(\"addPubli\");bind_datepicker_to_date_fields();'>Add a new publication</button>
";

echo "<div id='formNM'>";
include "./edit_remove.php";
echo "</div>";

echo "<br><center>";
$last_modified = filemtime($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
print("Last Modified on ");
print(date("F j, Y", $last_modified));

echo '<p>
This page is maintained by QZ Zhang. If you have questions, please 
<a href="mailto:qzhang@anl.gov?Subject=GREET%20Publication%20editing" target="_blank">send an email.</a>
</p>
';
echo "</center>";
?>

</body>
</html>
