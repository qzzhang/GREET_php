<?php
$ftxt = fopen($_SERVER['DOCUMENT_ROOT']."/EditPublications/log.txt",'a+');
fwrite($ftxt,$log_desc);
fclose($ftxt);
?>
