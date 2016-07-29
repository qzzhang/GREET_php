<?php
$email=$_GET['email'];
$event=$_GET['event'];
$key=$_GET['key'];

//check if the event is valid
//opens the events data file
if (file_exists($_SERVER['DOCUMENT_ROOT']."\\static_data\\events.xml"))
{
	$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."\\static_data\\events.xml");
}
else
{
	sleep(2);
	echo "Error 7163 ";
	report_error("event_reg.php : error 7163 failed to open the xml file containing publications listing");
	exit;
}

//gets all the events xml nodes and creates array
$xevents_list = $xml->events->event;
$founded = false;
$name='';
$capacity=0;
$opening='';
$closing='';
$description='';
$title='';
$sold_out='';
$too_early='';
$too_late='';
foreach($xevents_list as $xevent)
{ 
	if($xevent['name']==$event)
	{
		$founded = true;
		$name=$xevent['name'];
		$capacity=$xevent['capacity'];
		$opening=$xevent['opening'];
		$closing=$xevent['closing'];
		$description=$xevent['description'];
		$title=$xevent['title'];
		$sold_out=$xevent['sold_out'];
		$too_early=$xevent['too_early'];
		$too_late=$xevent['too_late'];
		$location= $xevent['location'];
	}
}
if($founded == false)
{
	sleep(2);
	report_error("confirm_event_registration : 246 try to pass undefined event"."|".$_GET['event']);
	echo 'Error 246';
	exit;
}
else
{	
//check if user already registered to the website and get a user file
	$fh_user = @fopen($_SERVER['DOCUMENT_ROOT']."/users_data/".$email,'r');	
	if ($fh_user==false)
	{
		sleep(2);
		report_error("confirm_event_registration : 6432 no file for the email entered "." | ".$email." | ".$event." | ".$key);
		echo 'Error 6432';
		fcose($fh_user);
		exit;
	}
	else
	{
		//at this point we know that we have that user in the database, we register his session
		$_SESSION['mail'] = $email;
		$registered = true;
		$session_mail = $email;
		report_connextion($email,$_POST['Res']);
	
		//check if user already registered to the event by checking if file present there
		$fh_event = @fopen($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\".$name."\\".$email,'r');	
		if ($fh_event==false)
		{
			sleep(2);
			report_error("confirm_event_registration : 8796 user non registered for the event and ask confirmation "." | ".$email." | ".$event." | ".$key);
			echo 'Error 8796';
			fclose($fh_event);
			exit;
		}
		else
		{
			//check that the key corresponds to the user
			$lines = file($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\".$name."\\".$email);
			$id_found = false;
			$confirmed = false;
			$attending = false;
			foreach($lines as $line)
			{
				if($line == 'id='.$key."\n" || $line == 'id='.$key."\r\n")
				{
					$id_found = true;
				}
				if(strpos($line, 'mail_validated=1') !== false)
				{
					$confirmed = true;
				}
				if(strpos($line, 'attending=true') !== false)
				{
					$attending = true;
				}
			}
			
			if($attending == false)
			{
				echo "We're sorry to hear that you can't attend this event.<br><br>";
				echo "Thanks for your participation.";
				exit;
			}
			
			
			if($id_found && $confirmed == false)
			{//regular user asking for confirmation
				echo 'Your registration for the event : '.$title.' has been confirmed.<br><br>';
				//echo 'Please complete our <a href=survey>online survey</a>, 3 questions which will help us to adapt the content of our workshop to your interrest.<br><br>'; 
				echo 'In preparation of your upcoming visit to Argonne National Laboratory, please register using the following link in order to obtain a gate pass:';
				echo '<br>';
				echo '<a href="https://webapps.anl.gov/registration/visitors">https://webapps.anl.gov/registration/visitors</a>';
				echo '. You will be prompted to provide a sponsor&#39;s e-mail address. Please use jpapiernik@anl.gov where requested, and continue to complete the remainder of the web form.';
				echo ' Foreign nationals should allow 14 days for processing.';
				
				//validate registration
				$content = file_get_contents($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\".$name."\\".$email);
		
				$i = 1; $fh=false;
				while ($i <= 10 && $fh==false) //try 5 times to open the file in write mode with a random delay 10-50ms
				{
					 $fh = @fopen($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\".$name."\\".$email,'w');
					 $i = $i +1;
					 usleep(rand(10000,50000));
				}
				if ($fh)
				{
					$content = str_replace('mail_validated=0','mail_validated=1',$content);
					fwrite($fh,$content);
					fclose($fh);
					sleep(2);
					send_mail("ddieffenthaler@anl.gov","Event confirmation","The user ".$email." is confirmed for the event :".$name);
					send_html_mail($email,"Event : ".$title.", Confirmed",
						"The user ".$email." is registered and confirmed for the event ".$title." <br><br>".
						//"The agenda can be found <a href=\"http://greet.es.anl.gov/files/workshop-2012-09-agenda\">here</a><br><br>".
						//"Please complete our <a href=\"http://greet.es.anl.gov/survey\">online survey</a>, 3 questions which will help us to adapt the content of our workshop to your interrest.<br><br>". 
						"In preparation of your upcoming visit to Argonne National Laboratory, please register using the following link in order to obtain a gate pass.".
						"<a href='https://webapps.anl.gov/registration/visitors'>https://webapps.anl.gov/registration/visitors</a>".
						". You will be prompted to provide a sponsor&#39;s e-mail address. Please use jpapiernik@anl.gov where requested, and continue to complete the remainder of the web form.".
						" Foreign nationals should allow 14 days for processing.<br><br>".
						$location.
						"Thank you for your interest in this event and see you there");
					echo "<br><br><br>".$location;
				}
				else
				{
					sleep(2);
					report_error("confirm_event_registration : 4375 user file cannot be written"." | ".$email." | ".$event." | ".$key);
					echo "Error 4375";
				}
				
				//now here we could try to add to the real user file that the email has been validated too, that way we collect data about that user
				//but are we going to use it ?
				
			}
			else if($id_found && $confirmed)
			{//user asking twice for confirmation
				sleep(2);
				echo 'Your request for the event : '.$title.' has been found.<br>';
				echo 'You have already confirmed your registration for this event<br><br>';
				//echo 'Please complete our <a href=survey>online survey</a>, 3 questions which will help us to adapt the content of our workshop to your interrest.<br><br>'; 
				echo 'In preparation of your upcoming visit to Argonne National Laboratory, please register using the following link in order to obtain a gate pass:';
				echo '<br>';
				echo '<a href="https://webapps.anl.gov/registration/visitors">https://webapps.anl.gov/registration/visitors</a>';
				echo '. You will be prompted to provide a sponsor&#39;s e-mail address. Please use <a href="mailto:jpapiernik@anl.gov">jpapiernik@anl.gov</a> where requested, and continue to complete the remainder of the web form.';
				echo ' Foreign nationals should allow 14 days for processing.';
				
				echo "<br><br><br>".$location;
			}
			else
			{//user asking confirmation for someone who is registered to the event with a wrong key
				sleep(2);
				report_error("confirm_event_registration : 8795 user registered for the event and ask confirmation with a wrong key"." | ".$email." | ".$event." | ".$key);
				echo 'Error 8795';
				exit;
			}
		}
		fclose($fh_event);
	}
	fclose($fh_user);
}

?>


