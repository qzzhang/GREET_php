<?php 
	$email = $_GET['error'];
	$event = $_GET['event'];
	
	echo TXT_EMAIL." : ".strip_tags($email)." ".TXT_ALREADY_EXISTS;
	if(empty($event))
		echo "<br><br>Please fill the registration form : <a href=\"register\"> here </a>";
	else
		echo "<br><br>Please fill the event registration form : <a href=\"event-".$event."\"> here </a>";
	if(strip_tags($email)!=$email)
	{
		report_error("wrong_user.php : Tags are tried to be passed on the wrong_user form");
	}
?>