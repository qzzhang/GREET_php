<?php
////////////////////////////////////////////////////////////////
function check_file_uploaded_name($filename)
{//make sure the file name in English characters, numbers and (_-.) symbols
    return (bool)((preg_match("/^[-0-9A-Z_\.]+$/i",$filename)) ? true : false);
}
function check_file_uploaded_length ($filename)
{//make sure that the file name not bigger than 250 characters
    return (bool)((mb_strlen($filename,"UTF-8") > 225) ? true : false);
}

function codeToMessage($code) 
{ 
        switch ($code) { 
            case UPLOAD_ERR_INI_SIZE: 
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini"; 
                break; 
            case UPLOAD_ERR_FORM_SIZE: 
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form"; 
                break; 
            case UPLOAD_ERR_PARTIAL: 
                $message = "The uploaded file was only partially uploaded"; 
                break; 
            case UPLOAD_ERR_NO_FILE: 
                $message = "No file was uploaded"; 
                break; 
            case UPLOAD_ERR_NO_TMP_DIR: 
                $message = "Missing a temporary folder"; 
                break; 
            case UPLOAD_ERR_CANT_WRITE: 
                $message = "Failed to write file to disk"; 
                break; 
            case UPLOAD_ERR_EXTENSION: 
                $message = "File upload stopped by extension"; 
                break; 

            default: 
                $message = "Unknown upload error"; 
                break; 
        } 
        return $message; 
} 
	
ini_set('display_errors',1);
error_reporting(E_ALL);

$phpFileUploadErrors = array(
    0 => 'There is no error, the file uploaded with success', 
    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',//UPLOAD_ERR_INI_SIZE
    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',//UPLOAD_ERR_FORM_SIZE
    3 => 'The uploaded file was only partially uploaded',//UPLOAD_ERR_PARTIAL
    4 => 'No file was uploaded',//UPLOAD_ERR_NO_FILE
    6 => 'Missing a temporary folder',//UPLOAD_ERR_NO_TMP_DIR
    7 => 'Failed to write file to disk.',//UPLOAD_ERR_CANT_WRITE
    8 => 'A PHP extension stopped the file upload.'//UPLOAD_ERR_EXTENSION
);

if( strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) == 'post' && !empty( $_FILES ) && $_FILES['upfile']['error'] != 4)
{echo "Uploading file '" .$_FILES['upfile']['name'] . "'......";
	//Upload the file to this directory
	//$upd_dir = $_SERVER['DOCUMENT_ROOT']."/public/pdf/2016/";
	$upd_dir = $_SERVER['DOCUMENT_ROOT']."/public/pdf/2016/";
	$upd_tmp_dir = $_SERVER['DOCUMENT_ROOT']."/../Greet_Temp/";
	//echo "<br>check upload dir: " .$upd_dir. " ". is_writable($upd_dir);
	//echo "<br>check temp dir: " .$upd_tmp_dir. " ". is_writable($upd_tmp_dir);
	$file2upload = $upd_dir.basename($_FILES["upfile"]["name"]);
	
	//echo "<br>".basename($_FILES["upfile"]["name"]);//echo "<br>".$_FILES["upfile"]["name"];
	//echo "<br/>".$file2upload;

	$updOk = 1;
	
	//check if upload file already exists
	if(file_exists($file2upload))
	{
		echo "<br/>This file already exists in the database";
		$updOk = 0;
	}

	//check file size
	//echo "<br/>".$_FILES["upfile"]["size"];
	if($_FILES["upfile"]["size"] > 15000000)
	{
		echo "<br/>Your file is too large to upload";
		$updOk = 0;
	}
		
	//check file type
	$filetype = pathinfo($file2upload,PATHINFO_EXTENSION);
	if($filetype != "pdf" && $filetype != "doc" && $filetype != "txt" && $filetype != "docx" && $filetype != "xls" && $filetype != "xlsx" && $filetype != "html")
	{
		echo "<br/>Wrong format, the allowed formats are : pdf, doc, txt, xls, html";
		$updOk = 0;
	}
	
	if(!is_dir($upd_dir) || !is_writable($upd_dir)) {
		echo 'Upload directory is not writable, or does not exist.';
	} 
	else 
	{// do upload logic here	
		if($updOk == 0)
		{
			echo "<br/>Your file was not uploaded";
		}
		else
		{
			if(move_uploaded_file($_FILES["upfile"]["name"],$file2upload))
			{
				echo "<br/>Your file ".basename($_FILES["upfile"]["name"])." has been uploaded";
			}
			else
			{
				$html_body = '<h4>File upload error!</h4>';
				$html_body .= codeToMessage($_FILES['upfile']['error']);
				echo ($html_body);
			}
		}
	}
}
else
{
	echo "No file is set to be uploaded for this publication.";
}
echo "<br>";

/////////////////////////////////////////////////////////////////////////////////////////
?>