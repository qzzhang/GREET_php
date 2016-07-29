<?php 
	$issues = $_GET['error'];
	$event = $_GET['event'];
	
	if((string)intval($issues) == $issues)
	{
		$issues = (int)$issues;
		$a = $issues%2;
		$temp = (int)($issues/2);
		$b = $temp%2;
		$temp = (int)($temp/2);
		$c = $temp%2;
		$temp = (int)($temp/2);
		$d = $temp%2;
		$temp = (int)($temp/2);
		$e = $temp%2;
		$temp = (int)($temp/2);
		$f = $temp%2;
		$temp = (int)($temp/2);
		$g = $temp%2;
		$temp = (int)($temp/2);
		$h = $temp%2;
		$temp = (int)($temp/2);
		$i = $temp%2;
		$temp = (int)($temp/2);
		$j = $temp%2;
		$temp = (int)($temp/2);
		$k = $temp%2;
		$temp = (int)($temp/2);
		$l = $temp%2;
		$temp = (int)($temp/2);
		$m = $temp%2;

		echo TXT_FORM_NOT_VALID."<br><br>";
		//wrong first name
		if($a==1)
		{echo TXT_WFN."<br>";}
		//wrong last name
		if($b==1)
		{echo TXT_WLN."<br>";}
		//wrong region
		if($c==1)
		{echo TXT_REG."<br>";}
		//wrong country
		if($d==1)
		{echo TXT_COU."<br>";}	
		//wrong user specified country
		if($e==1)
		{echo TXT_UCOU."<br>";}
		//wrong firm
		if($f==1)
		{echo TXT_FIRM_ERR."<br>";}
		//wrong email
		if($g==1)
		{echo TXT_EMA."<br>";}
		//wrong industry
		if($h==1)
		{echo TXT_IND."<br>";}
		//wrong user specified industry
		if($i==1)
		{echo TXT_UIND."<br>";}
		//wrong user specified middle name
		if($j==1)
		{echo TXT_WMN."<br>";}
		//wrong user specified state
		if($k==1)
		{echo TXT_WST."<br>";}
		//wrong user specified comment
		if($l==1)
		{echo TXT_WCO."<br>";}
		//email domain not accepted for registration
		if($m==1)
		{echo "Email domain not accepted for registration"."<br>"."You can contact <a href=\"mailto:greet@anl.gov\"> our team</a> for more details";}
		
		if(empty($event))
			echo "<br><br>Please fill the registration form : <a href=\"register\"> here </a>";
		else
			echo "<br><br>Please fill the event registration form : <a href=\"event-".$event."\"> here </a>";
		
	}
	else
		{
			echo "Error on the page";
			report_error("wrong_form.php : Non numeric value provided as the error get value for the wrong_form");
		}
	
?>