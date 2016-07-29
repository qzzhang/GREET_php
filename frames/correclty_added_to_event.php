<?php
$email = strtolower($_GET['user']);
$event = strip_tags($_GET['event']);
$key = strip_tags($_GET['key']);

if(!empty($email) && validEmail($email) && !empty($event)) 
{

	//check if the event is valid
	//opens the events data file
	if (file_exists($_SERVER['DOCUMENT_ROOT']."\\static_data\\events.xml"))
	{
		$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."\\static_data\\events.xml");
	}
	else
	{
		echo "Failed to locate the file informations";
		report_error("event_reg.php : failed to open the xml file containing publications listing");
	}

	//gets all the events xml nodes and creates array
	$xevents_list = $xml->events->event;
	$founded = false;
	$name='';
	$description='';
	$title='';
	foreach($xevents_list as $xevent)
	{ 
		if($xevent['name']==$event)
		{
			$founded = true;
			$name=$xevent['name'];
			$description=$xevent['description'];
			$title=$xevent['title'];
		}
	}
	if($founded == false)
	{
		sleep(2);
		report_error("correctly_added_to_event : try to pass undefined event"."|".$_GET['event']);
		echo 'Event not found';
		exit;
	}
	else
	{
		//check if user already registered to the website and get a user file
		$fh_user = @fopen($_SERVER['DOCUMENT_ROOT']."\\users_data\\".$email,'r');	
		if ($fh_user==false)
		{
			sleep(2);
			report_error("correctly_added_to_event : no file for the email entered "." | ".$email);
			header("Location: ".$url."/index.php?content=wrong_auth&error=".$email."&event=".$name);
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
				if(strpos($line, 'attending=true') !== false)
				{
					$attending = true;
				}
			}
			
			if($id_found == false)
			{//there is an error with the key passed 
				sleep(2);
				report_error("correctly_added_to_event : 74162 issue with the key"." | ".$email);
				echo "Error 74162";
				exit;
			}
			else
			{
				if($attending == false)
				{
					echo "We're sorry to hear that you can't attend this event.<br><br>";
					echo "Thanks for your participation.";
					exit;
				}
			
				//check if user already registered to the event by checking if file present there
				$fh_event = @fopen($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\".$name."\\".$email,'r');	
				if ($fh_event==true)
				{
					echo "You successfully registered for the  : ".$title."<br><br>";
					//echo 'Please complete our <a href=survey>online survey</a>, 3 questions which will help us to adapt the content of our workshop to your interrest.<br><br>'; 
					echo "A confirmation email has been sent to your email address : ".$email;
				}
				else
				{
					echo "You are not registered for this event, click the link below to register :<br>";
					echo "<a href=event-".$name.">&bull; ".$title."</a>";
					exit;
				}
			}
		}
	}
}	

?>