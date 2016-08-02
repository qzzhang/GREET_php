<?php
//echo "File name: emailGreet.php


//the message
$msg = $log_desc;
$to = "qzhang@anl.gov";//"greet@anl.gov";
$subject = "Publication updated";
$headers = "From: qzhang@anl.gov" . "\r\n" . "Reply-To: qzhang@anl.gov" . "\r\n";

//use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
//mail($to,$subject,$msg,$headers);
send_mail($to,$subject,$msg);
//phpinfo();
?>
