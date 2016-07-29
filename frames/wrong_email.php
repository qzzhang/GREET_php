<?php 
	$email = $_GET['error'];
	echo TXT_EMAIL." : ".strip_tags($email).TXT_ISINVALID;
	echo "<br>Please fill the registration form again : <a href=\"register\"> here </a>";
	if(strip_tags($email)!=$email)
	{
		report_error("wrong_email.php : Tags are tried to be passed on the wrong_email form");
	}
?>