<?php
echo "<p><h4>GREET Publications of the GREET Model Development and Applications</h4><p>
<p>Below is a list of the title, authors, publication date, venue of availability, and a description of the content of each GREET model report, 
which can be narrowed down by keyword search. For a complete list, click <a href=\"list.php\" target=\"_blank\">here</a>.</p>";

echo "
	<input type='text' name='searchterm' id='searchterm' size='60'/><br>
	<button id='btn_srch1' onclick ='findpubs(document.getElementById(\"searchterm\").value,1);'>Search Author/Title</button>
	<button id='btn_srch2' onclick='findpubs(document.getElementById(\"searchterm\").value,2);'>Search Author/Title/Abstract</button>
";

if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
{$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");}
else
{
		echo "Sorry, failed to load the publication data";
		report_error("publi.php : failed to open the xml file containing publications listing");
}

//gets all the files xml nodes and creates array
$files = array();//array of class
$xfiles_list = $xml->files->file;



echo "<img id=\"loading_image\" style=\"display: none;margin: 0 auto;\" src=\"../greet/pic/ajaxloader.gif\">";

echo '<div id="pubsfound">';

include($_SERVER['DOCUMENT_ROOT']."/includes/allpublis.php");
echo '</div>';

echo "<br> <center>";
$last_modified = filemtime($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
print("Last Modified on ");
print(date("F j, Y", $last_modified));
echo "</center>";
?>

