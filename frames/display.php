<?php
class_exists("TableSorter") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/TableSorter.php");
class_exists("DistanceMetric") || require_once($_SERVER['DOCUMENT_ROOT']."/includes/DistanceMetric.php");
 if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
{	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");}
else
{
	echo "Sorry, failed to load the publication data";
	report_error("publi.php : failed to open the xml file containing publications listing");
}
include_once($_SERVER['DOCUMENT_ROOT'].'/include/action_test1.php');

$results_keys;

foreach($xml->groups->group as $xchild)
{
	echo "<a name=".str_replace(' ','_',$xchild['name'])."></a>  ";

	echo "<p></p><h5>".$xchild['name']. "</h5>";
	$id = $xchild['id'];
	
	echo "<table class=\"documents\">";
	echo "<tr>
	<th>Title
	<a href=\"index.php?content=publications&amp;by=title&amp;order=down#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_down.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	<a href=\"index.php?content=publications&amp;by=title&amp;order=up#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_up.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	</th>
	<th width=\"120\">Authors
	<a href=\"index.php?content=publications&amp;by=authors&amp;order=down#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_down.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	<a href=\"index.php?content=publications&amp;by=authors&amp;order=up#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_up.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	</th>
	<th width=\"65\">Date
	<a href=\"index.php?content=publications&amp;by=date&amp;order=down#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_down.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\"></a>
	<a href=\"index.php?content=publications&amp;by=date&amp;order=up#".str_replace(' ','_',$xchild['name'])."\">
	<img src=\"public\images\arrow_up.png\" width=\"10\" height=\"9\" border=\"0\" alt=\"\" ></a>
	</th>
	<th width=\"65\">File
	
	</th>
	</tr>";
	
	$odd = true;
	
	//gets all the files xml nodes and creates array
	$xfiles_list = $xml->files->file;
	
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
		if(empty(htmlspecialchars($_GET['research'])))
		{
			 if($odd)
				//echo "<tr class=\"odd\">";
				echo "<tr>";
			else
				echo "<tr class=\"even\">";
			$odd = !$odd;
	 
			echo "<td><a class=\"publi\" href=\"publication-".$line[5]."\">".$line[0];
			echo "</a></td>";
			echo "<td>";
			if($line[3]!="")
				echo $line[3];
			echo "</td>";
			echo "<td>".date("M Y",$line[1])."</td>";
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
			foreach($res as $key=>$value)
			{
				if((string)$line[5]==(string)$value)
				{
					$present = true;
				}
			}
			if($present)
			{
				
				if($odd)
				{
					echo "<tr class=\"odd\">";
				}	
				else
				{
					//echo "<tr class=\"even\">";
					echo "<tr>";
				}
					
				$odd = !$odd;

				echo "<td><a class=\"publi\" href=\"publication-".$line[5]."\">".$line[0];
				echo "</a></td>";
				echo "<td>";
				if($line[3]!="")
					echo $line[3];
				echo "</td>";
				echo "<td>".date("M Y",$line[1])."</td>";
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



















?>