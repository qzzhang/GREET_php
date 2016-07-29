
<?php
class_exists("DistanceMetric") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/DistanceMetric.php");
include_once($_SERVER['DOCUMENT_ROOT'].'/security/functions.php');

$srchstr = htmlspecialchars($_GET['research']);
$srchopt = htmlspecialchars($_GET['range']);
$srchrange = "";

if ($srchopt == 1) {
	$kwfilenm = $_SERVER['DOCUMENT_ROOT']."/static_data/keywords_without_abstract.xml";
	$srchrange = "search Authors/Titles";
}
else {
	$kwfilenm = $_SERVER['DOCUMENT_ROOT']."/static_data/keywords_with_abstract.xml";
	$srchrange = "search Authors/Titles/Abstracts";
}

if (file_exists($kwfilenm))
	$keyw = simplexml_load_file($kwfilenm);
else
{
	echo "Sorry, failed to load the keyword data";
	report_error("publi.php : failed to open the xml file containing search keywords");
}

if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
else
{
	echo "Sorry, failed to load the publication data";
	report_error("publi.php : failed to open the xml file containing publications listing");
}

//get all the files data in an array
$xfiles_list = $xml->files->file;

//To reload the keywords, create an instance of DistanceMetric and use the generate_keywords method with the argument $xml->files->file
$for_explode_purpose = new DistanceMetric();
$top = 3;
$perfect_match = 3;
$ssr = $for_explode_purpose->multiexplode(array(" ",",",";",".","-","_","/"),$srchstr);
$nbr = sizeof($ssr);
$max_relevance = $perfect_match*$nbr;

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
				//echo "<br/>theme->".$theme; 
				$part_relevance = 0;
				$part_relevance_temp = 0;
				$kk = $kfile->keywords;
				$kk = explode(";",$kk);
				
				foreach($kk as $part_key) //10
				{
					if(strcasecmp($part_key,$theme)==0)
					{
						//perfect match, the relevance of the word is set to the maximum
						//echo "<br/>Perfect match";
						//echo "<br/>part key->".$part_key;
						global $perfect_match;
						$part_relevance = $perfect_match;
						//echo "<br/>Part relevance is :".$part_relevance;
						
						break;
					}
					else if(levenshtein(strtolower($part_key),strtolower($theme))<=(3-$srchopt))
					{
						$dist = levenshtein(strtolower($part_key),strtolower($theme));
						//echo "<br/>Close enough";
						//echo "<br/>part key->".$part_key;
						if($dist>0)
						{
							if($part_relevance < 1/$dist)
							{
								$part_relevance_temp =  1/$dist;
							}
						}
						else
						{
							$part_relevance_temp = 1;
						}
						//echo "<br/>Part relevance is :".$part_relevance;
						if($part_relevance_temp>$part_relevance)
						{
							//echo "<br/>part relevance update";
							$part_relevance = $part_relevance_temp;
							
						}
						//echo "<br/>Relevance temp :".$relevance;
					}
					
				}
				$relevance += $part_relevance;

			}
			//echo "<br/>Relevance for article :".$relevance;
			//echo "<br/>";
			$kfile->relevance = $relevance;

		}
		$keyw->asXML($kwfilenm);
		///////////////////////////////////////////////////////////////////////////
		
		///Sort the files by relevance
		$search_result = $metric->sort_by_relevance($keyw,$nbr,$max_relevance,$srchstr,$srchopt);
	}
	else
	{
		//blank search string
		///return all the keys
		foreach($keywords->dfiles->dfile->keys as $kkeys)
		{
			array_push($search_result,(string)$kkeys);
		}
	}
}
else
{
	//Null search string
	///return all the keys
	foreach($keywords->dfiles->dfile->keys as $kkeys)
	{
		array_push($search_result,(string)$kkeys);
	}
}

if(empty($srchstr))//Display all articles if no word searched
{
	include($_SERVER['DOCUMENT_ROOT']."/includes/allpublis.php");
}
else
{
	echo "<p style=\"color:white; font-size:1.1em; background-color: rgb(66, 184, 221);\">Results from ".$srchrange." for: ".$srchstr."</p>";
	foreach($xml->groups->group as $xchild)
	{
		$lines = array();
		$id = $xchild['id'];
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
		//array to store the lines related to the research
		$searched_lines = array();
		
		foreach($lines as $line)
		{
			foreach($search_result as $key=>$value)
			{
				//echo "<br>searched key=".$value."----publication key=".$line[5];
				//check if the key of the article is present in the list of result keys
				if((string)$line[5]==(string)$value)
				{
					array_push($searched_lines,$line);
				}
			}
		}
		
		if(!sizeof($searched_lines)==0)
		{
			//First, the table caption row
			$grp_name = str_replace(' ','_',$xchild['name']);

			echo "<div style=\"font-size: 1.2em; padding: 0.5em .25em; font-weight: bold; color:rgb(66, 184, 221);\">".$xchild['name']. "</div>";
			$id = $xchild['id'];
			
			echo "<table id=\"".$grp_name."\" class=\"tablesorter\">";
			echo "<thead>";
			echo "<tr>
			<th style=\"width:45%\">Title</th>	
			<th style=\"width:35%\">Authors	</th>	
			<th style=\"width:12%\">Date</th>
			<th>File</th></tr>";
			echo "</thead>";

			//Display the searched lines
			echo "<tbody>";
			foreach($searched_lines as $line)
			{
				echo "<tr>";		

				echo "<td><a href=\"publication-".$line[5]."\" target=\"_blank\">".$line[0];
				echo "</a></td>";
				echo "<td>";
				if($line[3]!="")
					echo $line[3];
				echo "</td>";
				echo "<td>".date("m-d-Y",$line[1])."</td>";
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
			echo "</tbody>";
			echo "</table>";
		}
	}
}
?>