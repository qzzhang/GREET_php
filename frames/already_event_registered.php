<?php

$email = strtolower($_GET['mail']);
$event = strip_tags($_GET['event']);
$key = strtolower($_GET['key']);

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
		report_error("already_event_registered : 1645 failed to open the xml file containing publications listing");
		echo "Error 1645";
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
		report_error("correctly_added_to_event : 789 try to pass undefined event"."|".$_GET['event']);
		echo 'Error 789';
		exit;
	}
	else
	{

		//check if user already registered to the website and get a user file
		$fh_user = @fopen($_SERVER['DOCUMENT_ROOT']."\\users_data\\".$email,'r');	
		if ($fh_user==false)
		{
			sleep(2);
			report_error("already_event_registered : 758 no file for the email entered "." | ".$email);
			echo "Error 758";
			exit;
		}
		else
		{	
			//check if user already registered to the event by checking if file present there
			$fh_event = @fopen($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\".$name."\\".$email,'r');	
			if ($fh_event==true)
			{
				$lines = file($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\".$name."\\".$email);
				$id_found=false;
				$email_validated='';
				$nationality='';
				
				foreach($lines as $line)
				{
					if($line == 'id='.$key."\n" || $line == 'id='.$key."\r\n")
					{
						$id_found = true;
					}
					else if(strpos($line, 'mail_validated='))
					{
						$explosion = explode("=",$line);
						$email_validated = trim($explosion[1], "\n");
						$email_validated = trim($explosion[1], "\r\n");
					}
				}
				if($id_found == false)
				{//the key passed was is not associated with that user
					sleep(2);
					report_error("already_event_registered : 14386 no file for the email entered "." | ".$email);
					echo "Error 14386";
				}
				else
				{
					if($email_validated == '1')
					echo "You are registered and validated already";
				 else
					echo "You are registered already, don't forget to validate your registration using the link which have been sent to you by email<br>";
					//echo "In order to get another confirmation link, click here.";
				}	
			}
			else
			{
				sleep(2);
				report_error("already_event_registered : 756 no file for the email entered "." | ".$email);
				echo "Error 756";
				exit;
			}
		}
	}
}
?>