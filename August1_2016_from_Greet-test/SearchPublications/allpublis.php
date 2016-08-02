<?php
//writing the articles
echo "<br>ALL ARTICLES:<br/>";

include_once($_SERVER['DOCUMENT_ROOT'].'/security/functions.php');

if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
{	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");}
else
{
		echo "Sorry, failed to load the publication data";
		report_error("allpubli.php : failed to open the xml file containing publications listing");
}

foreach($xml->groups->group as $xchild)
{
	//First, the table caption row
	$grp_name = str_replace(' ','_',$xchild['name']);

	echo "<div style=\"font-size: 1.2em; padding: 1em .25em; font-weight: bold; color:rgb(66, 184, 221);\">".$xchild['name']. "</div>";
	$id = $xchild['id'];
	
	echo "<table id=\"".$grp_name."\" class=\"tablesorter\">";
	echo "<thead>";
	echo "<tr>
	<th style=\"width:45%\">Title</th>	
	<th style=\"width:35%\">Authors</th>	
	<th style=\"width:12%\">Date</th>
	<th class=\"sorter-false\">File</th></tr>";
	echo "</thead>";
	
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
	
	//Then display all the articles in the array $lines

	$odd = true;
	echo "<tbody>";
	foreach ($lines as $line)
	{
		 if($odd)
			 echo "<tr>"; 
		 else
			 echo "<tr>"; 
		$odd = !$odd;
	 
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
			//echo $line[5];
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
	
	//echo '<a href="#" class="ajax-append">append new table data</a><br><br>';
	
	/*
	echo '
<!-- pager -->
<div class="pager">
    <img src="http://mottie.github.com/tablesorter/addons/pager/icons/first.png" class="first" />
    <img src="http://mottie.github.com/tablesorter/addons/pager/icons/prev.png" class="prev" /> <span class="pagedisplay"></span> 
    <!-- this can be any element, including an input -->
    <img src="http://mottie.github.com/tablesorter/addons/pager/icons/next.png" class="next" />
    <img src="http://mottie.github.com/tablesorter/addons/pager/icons/last.png" class="last" />
    <select class="pagesize" title="Select page size">
        <option selected="selected" value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
    </select>
    <!--select class="gotoPage" title="Select page number"></select-->
</div>
	';
*/
}
?>