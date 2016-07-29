<?php
echo "Adding publication information to the files.xml file...";

include "./includes/loadFiles.php";//to set the values for $xml and $xfilenm

$relks = explode("/RELK/", $_POST['relKeys']);

function convertFileSize($file, $size=null, $decimals=2, $dec_sep='.', $thousands_sep=',')
{
	if (!is_file($file)){
		return "File does not exist.";
	}
	$bytes = filesize($file);
	$sizes = 'BKMGTP';
	if (isset($size)){
		$factor = strpos($sizes, $size[0]);
		if ($factor===false){
			return "The size should be B, K, M, G, T or P";
		}
	} else {
		$factor = floor((strlen($bytes) - 1) / 3);
		$size = $sizes[$factor];
	}
	return number_format($bytes / pow(1024, $factor), $decimals, $dec_sep, $thousands_sep).' '.$size;
}

$fileuploaded = $_SERVER['DOCUMENT_ROOT']."/".$storageLoc;
$upldSize = 0;
//check if upload file already exists
if(file_exists($fileuploaded))
{
	$upldSize = filesize($fileuploaded);//convertFileSize($fileuploaded, $size=null, $decimals=2, $dec_sep='.', $thousands_sep=',');
}

$file = $xml->files->addChild("file");
$file->addAttribute("need_registration",0);
$file->addAttribute("date",$dat);
$file->addAttribute("revision_date","");
$file->addAttribute("title",$title);
$file->addAttribute("abstract",$abstract);
$file->addAttribute("publication_location",$journal);
$file->addAttribute("location",$storageLoc);
$file->addAttribute("filename_user","");
$file->addAttribute("group",$id_group);
$file->addAttribute("size",$upldSize);
$file->addAttribute("author",$aut);
$file->addAttribute("key",$key_a);

foreach($relks as $rkitem) 
{//echo "add all the related keys";
	if($rkitem != "")
	{
		$rel = $file->addChild("related");
		$rel['key'] = $rkitem;
	}
}

//preserve good formatting after changes to the xml elements
$dom1 = new DOMDocument('1.0');
$dom1->preserveWhiteSpace = false;
$dom1->formatOutput = true;
$dom1->loadXML($xml->asXML());
$dom1->save($xfilenm);

echo "<br/>";
?>