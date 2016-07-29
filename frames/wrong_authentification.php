<?php 
	$email = $_GET['error'];
	$event = $_GET['event'];
	echo 'User email '.strip_tags($email).' was not found. Please enter correct email.<br>';
	echo 'If you have not filled out the form previously, go back and do so.';

	if(empty($event))
		echo "<br><br>Please fill the registration form : <a href=\"register\"> here </a>";
	else
		echo "<br><br>Please fill the event registration form : <a href=\"event-".$event."\"> here </a>";
		
	if(strip_tags($email)!=$email)
	{
		report_error("wrong_authentification.php : Tags are tried to be passed on the wrong_authentification form");
	}
?>