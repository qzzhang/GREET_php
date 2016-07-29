<?php

$pub_key = $_GET['pub_key'];
$url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https://'.$_SERVER['SERVER_NAME'] : 'http://'.$_SERVER['SERVER_NAME'];

if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
{
	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
}
else
{
	sleep(2);
	echo "Failed to locate the file informations";
	report_error("Details.php : failed to open the xml file containing publications listing");
}
//gets all the files xml nodes and creates array
$xfiles_list = $xml->files->file;

$lines = array();
$founded = false;

foreach($xfiles_list as $xfile)
{ 

	$possible_keys = array();
	$alternates_keys = $xfile->alt_key;

	$possible_keys[] = $xfile['key'];
	foreach($alternates_keys as $alt_key)
	{
		$possible_keys[] = $alt_key['old_key'];
	}

	foreach($possible_keys as $tested_key)
	{
		if($tested_key==$pub_key)
		{
			echo "<p></p><h2>Publication Details</h2>";
			
			if($xfile['title']!=''){echo "<b>Title</b> : ".$xfile['title']."<br>";}
			if($xfile['date']!=''){echo "<b>Publication Date</b> : ".printDate($xfile['date'])."<br>";}
			if($xfile['revision_date']!=''){echo "<b>Revision Date</b> : ".printDate($xfile['revision_date'])."<br>";}
			if($xfile['publication_location']!=''){echo "<b>Publication Journal</b> : ".printPossibleLink($xfile['publication_location'])."<br>";}
			if($xfile['author']!=''){echo "<b>Authors</b> : ".$xfile['author']."<br>";}
			if($xfile['abstract']!=''){echo "<b>Abstract</b> : ".nl2br($xfile['abstract'])."<br>";}
			if($xfile['location']!="")
			{
				echo "<div style=\"text-align:center;\">";
				echo icon_link($xfile['location'],$xfile['key'],$xfile['size']);
				echo "</div>";
			}
			
			$xrelated_list = $xfile->related;
			if(empty($xrelated_list)==false)
			{
				echo "<p><br><br><br></p><h2>Other Related Documents</h2>";
				foreach($xrelated_list as $xrelated)
				{
					foreach($xfiles_list as $xfile_related)
					{
						if(strcmp($xfile_related['key'],$xrelated['key']) == 0)
						{
							if($xfile_related['title']!=''){echo "<b>Title</b> : ".$xfile_related['title']."<br>";}
							if($xfile_related['date']!=''){echo "<b>Publication Date</b> : ".printDate($xfile_related['date'])."<br>";}
							if($xfile_related['revision_date']!=''){echo "<b>Revision Date</b> : ".printDate($xfile_related['revision_date'])."<br>";}
							if($xfile_related['publication_location']!=''){echo "<b>Publication Journal</b> : ".printPossibleLink($xfile_related['publication_location'])."<br>";}
							if($xfile_related['author']!=''){echo "<b>Authors</b> : ".$xfile_related['author']."<br>";}
							if($xfile_related['abstract']!=''){echo "<b>Abstract</b> : ".stripslashes(nl2br($xfile_related['abstract']))."<br>";}
							if($xfile_related['location']!="")
							{
								echo "<div style=\"text-align:center;\">";
								echo icon_link($xfile_related['location'],$xfile_related['key'],$xfile_related['size']);
								echo "</div>";
							}
						}
					}
				}
			}
			$founded = true;
		}
	}
}

if($founded == false)
{
	sleep(2);
	//report_error("details.php : Invalid file key is called :".$_GET["key"]);
	header("HTTP/1.0 404 Not Found"); 
	//header("Location: http://greet.es.anl.gov");
	exit;
}

?>