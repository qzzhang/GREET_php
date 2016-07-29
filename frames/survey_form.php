<?php


//check that we have a registered user
if($registered)
{
	
	$email = $_SESSION['mail'];
	//check that the user is registered to the event 
	$fh_event = @fopen($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\workshop_dec_2011\\".$email,'r');	
	if ($fh_event==false)
	{
		sleep(2);
		report_error("get_survey_inptus : user not registeded to the event workshop_dec_2011 "." | ".$email);
		echo "<p>You're not registered to the workshop, the survey is only accessible for user paticipating to the workshop</p>";
		echo "<p>You can register by clicking <a href=\"event-workshop_dec_2011\">here</a>";
	}
	else
	{
		//check if the user already answered the form
		$lines = file($_SERVER['DOCUMENT_ROOT']."\\users_data\\events\\workshop_dec_2011\\".$email);
		$explevel_found = false;
		foreach($lines as $line)
		{
			if(strpos($line, 'explevel=') !== false)
			{
				$explevel_found = true;
			}
		}
		if($explevel_found)
		{ //if explevel is found in the user file we assume that he already answered the form, he cannot anser twice
			sleep(2);
			report_error("get_survey_inptus : form already filled, tried to fill it again "." | ".$email);
			echo "You've already filled out the survey, thanks.<br>";
		}
		else
		{
			echo "<form nmae=frmSurvey action=get_survey_inputs method=post>"
			."<h2 align=left>GREET Software User Survey</h2>"."<p>To help maximize your benefit of the workshop, please respond to this optional short questionnaire.:</p>"
			."<p>"
			."<table name=explvl border=0 cellspacing=0 cellpadding=0 width=100%>"
			. "<tr>"
			. "<td colspan=3 valign=top><p align=left><b>Your level of experience with GREET :</b></p></td>"
			. "</tr>"
			. "<tr>"
			. "<td width=10% valign=top><p align=left><input type=radio name=erd value=3>Advanced</input></p></td>"
			. "<td width=10% valign=top><p align=left><input type=radio name=erd value=2>Intermediate</input></p></td>"
			. "<td width=10% valign=top><p align=left><input type=radio name=erd value=1>Beginner</input></p></td>"
			. "</tr>"
			. "</table>"
			. "</p>"
			. "<p>"
			. "<table name=favpath[]wy border=0 cellspacing=0 cellpadding=0 width=100%>"
			. "<tr> "
			. "<td width=40% valign=top colspan=2><p align=left><b>What pathways interest you the most (please check all that apply)</b></p></td>"
			. "</tr>"
			."<tr> "
			. "<td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=2>Petroluem</input></p></td>"
			." <td width=40% valign=top><p align=left></p></td>"
			."</tr>"
			."<tr> "
			. "<td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=3>Natural Gas</input></p></td>"
			. "<td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=8>Biofuels from Corn</input></p></td>"
			."</tr>"
			."<tr> "
			." <td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=4>Hydrogen</input></p></td>"
			." <td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=9>Biofuels from Cellulosic Biomass</input></p></td>"
			."</tr>"
			."<tr> "
			." <td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=5>Electricity</input></p></td>"
			." <td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=10>Biofuels from Algae</input></p></td>"
			."</tr>"
			."<tr> "
			." <td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=6>Aviation fuels</input></p></td>"
			." <td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=11>Biofuels from Pyrolysis</input></p></td>"
			."</tr>"
			."<tr> "
			." <td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=7>Renewable NG</input></p></td>"
			." <td width=40% valign=top><p align=left></p></td>"
			."</tr>"
			."<tr> "
			." <td width=40% valign=top><p align=left><input type=checkbox name=favpath[] value=12>Other</input></p></td>"
			." <td width=40% valign=top><p align=left><input type=text name=othr size=50 value=\"Please specify\" onmousedown=\"this.value='';\"></input></p></td>"
			."</tr>"
			." </table>"
			." </p>"
			." <p>We are currently developping a new software based on the GREET Fuel Cycle Model Excel spreadsheet.<br>"
			." The software is a stand alone executable for Windows composed of :"
			." <ul>"
			."	<li>User-friendly interface. "
			."	<li>Modular and layered data structure. "
			."	<li>Intuitive data editor, drag and drop features, graphics, diagrams."
			."	<li>Customization (e.g., results units, functional units, formats, track changes of parameters, data export)."
			."	<li>Wide spectrum of results (e.g., pathway level, process level, technology level, with and without upstream, categorization by primary energy source type and emissions groups, etc.) "
			."	<li>Online wiki user guide and documentation etc"
			."</ul>"
			." The project is still under development but we would be glad to show you what capabilities this software offers at this point and perform case studies.<br></p>"
			." <table name=learnmore border=0 cellspacing=0 cellpadding=0 width=100%> "
			." <tr>"
			." <td colspan=2 width=50% valign=top><p align=left><b>Would you be interested in learning about GREET.net ?</b></p></td>"
			." </tr>"
			." <tr>"
			." <td width=10% valign=top><p align=left><input type=radio name=nrd value=1>Yes</input></p></td>"
			." <td width=10% valign=top><p align=left><input type=radio name=nrd value=0>No</input></p></td>"
			." </tr>"
			." </table>"
			." <br>"
			."<input type=\"hidden\" name=\"handle\" value=\"".$_GET['key']."\" /> "
			."<input type=\"submit\" value=\"Submit\" />"
			."</form>";
			
		}
	}
}
else
{
	sleep(2);
	report_error("survey_form : 79186 try to pass a wrong user_key combo"." | ".$email." | ".$user_id);
	echo "Error 79186";
}
?>

