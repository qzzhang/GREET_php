<?php
//echo "File name: publiKeyTable.php";

include "./includes/arrayFuncs.php";
include "./includes/loadFiles.php";//to set the value for $xml

$xfiles_list = $xml->files->file;
$allKeys = array();
foreach($xfiles_list as $xf)
{
	$tstr = (string)$xf['title'];
	if( $tstr != "" )
		$allKeys[] = array('key'=>(string)$xf['key'], 'title'=>(string)$xf['title']);
}
$allKeys = array_orderby($allKeys, 'title', SORT_ASC, 'key', SORT_DESC);

	echo '<table><tr><th>Related Keys</th><th></th><th>Existing Keys</th></tr>';
	echo '<tr><td id="relDropdown">';
	echo '<select multiple name="relatedKeys" id="relatedKeys" style="width:120px;" size="5">';
	foreach( $line[9] as $rel )
			echo '<option>'.$rel["key"].'</option>';
	echo '</select></td>';
	
	echo '<td><button type="button" id="rmK" onclick="refreshDropdown(\'sourceKeys\',\'relatedKeys\',-1);resetRelKeys();">' . '=>' .'</button>';
	echo '<br><button type="button" id="adK" onclick="refreshDropdown(\'sourceKeys\',\'relatedKeys\',1);resetRelKeys();">' . '<=' .'</button></td>';

	echo '<td id="addKeysDropdown">
			<select multiple id="sourceKeys" style="width:600px;" size="5">';
	foreach($allKeys as $arr_kt)
	{
		echo '<option value="'.$arr_kt["key"].'">"'.$arr_kt["key"].'" -- '.$arr_kt["title"].'</option>';
	}
	echo '</select></td></tr>';
	echo '</table>';

?>