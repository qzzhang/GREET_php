<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>jQuery UI Datepicker - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    $( "#datepicker" ).datepicker({dateFormat: 'mm-dd-yy'});
  });
  </script>
</head>
<body>
 
<p>Date: <input type="text" id="datepicker"></p>
 
 <?php
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

$fileuploaded = $_SERVER['DOCUMENT_ROOT']."/public/pdf/2016/AFLEETToolVersionHistory-2016.pdf";
$upldSize = 0;
//check if upload file already exists
if(file_exists($fileuploaded))
{
	$upldSize = convertFileSize($fileuploaded, $size=null, $decimals=2, $dec_sep='.', $thousands_sep=',');
}

echo "file size=" . $upldSize;
?>
</body>
</html>
