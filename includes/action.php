

<?php
class_exists("TableSorter") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/TableSorter.php");
class_exists("DistanceMetric") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/DistanceMetric.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/security/functions.php');

$srchstr = htmlspecialchars($_GET['research']);

if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml") && file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/keywords_without_abstract.xml"))
{	
	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
	$keyw = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/keywords_without_abstract.xml");
}
else
{
	echo "Sorry, failed to load the publication data";
	report_error("publi.php : failed to open the xml file containing publications listing");
}

/*
//use php cache
$pathxml = $_SERVER['DOCUMENT_ROOT']."/static_data/keywords_without_abstract.xml";
if(file_exists($pathxml))
{
	if(time() - filemtime($pathxml) < 3600)
	{
		$handle = fopen($pathxml,"r");
		if($handle)
		{
			header('Content-Type: text/xml');
			header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public')
			while(!feof($handle))
			{
				$buffer = fgets($handle,4096);
				echo $buffer;
			}
			fclose($handle);
		}
		exit;
	}
	else
		unlink($pathxml);
}
////////////////////////////////////////////////////////////////////////////////
*/











//get all the files data in an array
$xfiles_list = $xml->files->file;

//To reload the keywords, create an instance of DistanceMetric and use the generate_keywords method with the argument $xml->files->file
$for_explode_purpose = new DistanceMetric();
$top = 3;
$perfect_match = 3;
$ssr = $for_explode_purpose->multiexplode(array(" ",",",";",".","-","_","/"),$srchstr);
$nbr = sizeof($ssr);
$max_relevance = $perfect_match*$nbr;
echo "<br/>Results for: ".$srchstr;



$search_result = array();

if(isset($srchstr))
{
	$srchstr = str_replace("'", "",$srchstr);
	if(sizeof($srchstr)>0)
	{
		///Compute the relevance of each file
		$metric = new DistanceMetric();
		$searching = explode(" ",$srchstr);
		foreach($keyw->dfiles->dfile as $kfile) //200
		{
			$relevance = 0;
			foreach($searching as $theme) //1-2
			{
				$part_relevance = 0;
				$kk = $kfile->keywords;
				$kk = explode(";",$kk);
				
				foreach($kk as $part_key) //10
				{
					if(strcasecmp($part_key,$theme)==0)
					{
						//perfect match, the relevance of the word is set to the maximum
						global $perfect_match;
						$part_relevance = $perfect_match;
						$relevance += $part_relevance;
						break;
					}
					else if($metric->DL_distance($part_key,$theme)<=2)
					{
						if($metric->DL_distance($part_key,$theme)>0)
						{
							if($part_relevance < 1/$metric->DL_distance($part_key,$theme))
							{
								$part_relevance =  1/$metric->DL_distance($part_key,$theme);
							}
						}
						else
						{
							$part_relevance = 1;
						}
						$relevance += $part_relevance;
					}

				}

			}

			$kfile->relevance = $relevance;

		}
		$keyw->asXML($_SERVER['DOCUMENT_ROOT']."/static_data/keywords_without_abstract.xml");
		///////////////////////////////////////////////////////////////////////////
		
		///Sort the files by relevance
		$search_result = $metric->sort_by_relevance($keyw,$nbr,$max_relevance,$srchstr);
	}
	else
	{
		//No research
		///return all the keys
		foreach($keywords->dfiles->dfile->keys as $kkeys)
		{
			array_push($search_result,(string)$kkeys);
		}
	}
}
else
{
	//No research
	///return all the keys
	foreach($keywords->dfiles->dfile->keys as $kkeys)
	{
		array_push($search_result,(string)$kkeys);
	}
}







/////////////////////////////////////////////////////////////////////////////////////
///Display results
foreach($xml->groups->group as $xchild)
{
	echo "<a name=".str_replace(' ','_',$xchild['name'])."></a>  ";

	echo "<p></p><h4>".$xchild['name']. "</h4>";
	$id = $xchild['id'];
	
	echo "<table style=\"width:98%\" class=\"documents\">";

	echo "<tr style=\"background:#4d8abe;font-size:1.2em;font-weight:bold;color:#FFF\">
	
	<th style=\"width:45%\">Title
	<a href=\"index.php?content=publications&amp;by=title&amp;order=down#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_down.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	<a href=\"index.php?content=publications&amp;by=title&amp;order=up#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_up.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	</th>

	<th style=\"width:35%\">Authors
	<a href=\"index.php?content=publications&amp;by=authors&amp;order=down#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_down.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	<a href=\"index.php?content=publications&amp;by=authors&amp;order=up#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_up.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	</th>
	
	<th style=\"width:12%\">Date
	<a href=\"index.php?content=publications&amp;by=date&amp;order=down#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_down.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\"></a>
	<a href=\"index.php?content=publications&amp;by=date&amp;order=up#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_up.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	</th>
	
	<th>File
	</th>
	</tr>";
	
	$odd = true;
	
	
	//transforms all the file objects to an array
	$lines = array();
	foreach($xfiles_list as $xfile)
	{ 
		if((int)$xfile['group'] == (int)$id)
		{
			$revision_date=(int)strtotime($xfile['revision_date']);
			$date=(int)strtotime($xfile['date']);
			if($revision_date>=$date)
				$used_date = $revision_date;
			else
				$used_date = $date;
				
			$line = array($xfile['title'],$used_date,(int)$xfile['size'],$xfile['author'],$xfile['location'],$xfile['key'],$xfile['publication_location']);
			array_push($lines,$line);
		}		 
	}
	
	$order = $_GET['order'];
	if($sortby=="date")
		$sorter = new TableSorter(1,$order,'int');
	else if($sortby=="title")
		$sorter = new TableSorter(0,$order,'string');
	else if($sortby=="size")
		$sorter = new TableSorter(2,$order,'int');
	else if($sortby=="author")
		$sorter = new TableSorter(3,$order,'int');
	else
		$sorter = new TableSorter(1,$order,'int');
	
	$lines = $sorter->sort($lines);

	foreach($lines as $line)
	{

		if(empty($srchstr))
		{
			//If there is no search word, all articles are displayed
			 if($odd)
				echo "<tr>";
			else
				echo "<tr class=\"even\">";
			$odd = !$odd;
	 
			echo "<td style=\"width:45%\"><a class=\"publi\" href=\"publication-".$line[5]."\">".$line[0];
			echo "</a></td>";
			echo "<td style=\"width:35%\">";
			if($line[3]!="")
				echo $line[3];
			echo "</td>";
			echo "<td style=\"width:12%\">".date("M Y",$line[1])."</td>";
			echo "<td> ";
			if($line[4]!="")
			{
				echo "<div style=\"text-align:center;\">";
				echo icon_link($line[4],$line[5],$line[2]);
				echo "</div>";
			}
			else if($line[6]!="")
			{
				echo "<div style=\"text-align:center;\">";
				echo "<a href=".$line[6].">publication<br>link</a>";
				echo "</div>";
			}
			echo "</td>";
			echo "</tr>";
		}
		else
		{
			
			$present = false;
			foreach($search_result as $key=>$value)
			{
				//check if the key of the article is present in the list of result keys
				if((string)$line[5]==(string)$value)
				{
					$present = true;
				}
			}
			//Display the article if the key is present in the list
			if($present)
			{
				if($odd)
				{
					echo "<tr>";//echo "<tr class=\"odd\">";
				}	
				else
				{
					echo "<tr class=\"even\">";
				}
					
				$odd = !$odd;

				echo "<td style=\"width:50%\"><a class=\"publi\" href=\"publication-".$line[5]."\">".$line[0];
				echo "</a></td>";
				echo "<td style=\"width:30%\">";
				if($line[3]!="")
					echo $line[3];
				echo "</td>";
				echo "<td style=\"width:12%\">".date("M Y",$line[1])."</td>";
				echo "<td> ";
				if($line[4]!="")
				{
					echo "<div style=\"text-align:center;\">";
					echo icon_link($line[4],$line[5],$line[2]);
					echo "</div>";
				}
				else if($line[6]!="")
				{
					echo "<div style=\"text-align:center;\">";
					echo "<a href=".$line[6].">publication<br>link</a>";
					echo "</div>";
				}
				echo "</td>";
				echo "</tr>";		
			}
		}
		
	}
	echo "</table>";
}
//////////////////////////////////////////////////////////////////////////////////////////////
?>