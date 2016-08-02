<?php
echo "File name: checkKey.php";

function element_attributes($element_name, $xml) {//extracting the attributes from an element in an XML sample
    if ($xml == false) {
        return false;
    }
    // Grab the string of attributes inside an element tag.
    $found = preg_match('#<'.$element_name.
            '\s+([^>]+(?:"|\'))\s?/?>#',
            $xml, $matches);
    if ($found == 1) {
        $attribute_array = array();
        $attribute_string = $matches[1];
        // Match attribute-name attribute-value pairs.
        $found = preg_match_all(
                '#([^\s=]+)\s*=\s*(\'[^<\']*\'|"[^<"]*")#',
                $attribute_string, $matches, PREG_SET_ORDER);
        if ($found != 0) {
            // Create an associative array that matches attribute
            // names to attribute values.
            foreach ($matches as $attribute) {
                $attribute_array[$attribute[1]] =
                        substr($attribute[2], 1, -1);
            }
            return $attribute_array;
        }
    }
    // Attributes either weren't found, or couldn't be extracted
    // by the regular expression.
    return false;
}

if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
{	
	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
}
else
{
	echo "Sorry, failed to load the publication data";
	report_error("insert.php : failed to open the xml file containing publications listing");
}

//get all the files data in an array
$xfiles_list = $xml->files->file;
$line = array();

foreach($xfiles_list as $xfile)
{
	//print_r($xfile[0]->attributes());
	foreach($xfile->attributes() as $a => $b) {
		if( $a == "key")
			echo $a,'=',$b,'<br>';//echo $xml->files->file["title"].'<br>';//
}
	/*
	if($xfile['key'] == $key)
	{
		$line = array($xfile['title'],$used_date,(int)$xfile['size'],$xfile['author'],$xfile['location'],(string)$xfile['key'],$xfile['publication_location'],$xfile['abstract'],$xfile['group']);
	}*/
}

echo "<br>after opening file";
?>
