<?php
//include the script to check if there is a session currently opened 
//and include the default language file
include_once($_SERVER['DOCUMENT_ROOT'].'/security/init.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/security/functions.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/lang/en-lang.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/security/session_validate.php');


	if($ban==true)
	{
		header('HTTP/1.1 403 Forbidden"');
		include($_SERVER['DOCUMENT_ROOT'].'/frames/banned.php');
		die();
	}

//defines array for authorized pages and actions

	if($registered==false)
	{
		$pageok = array('registration' => $_SERVER['DOCUMENT_ROOT'].'/frames/register.php',
						'wrong_auth' => $_SERVER['DOCUMENT_ROOT'].'/frames/wrong_authentification.php',
						'wform' => $_SERVER['DOCUMENT_ROOT'].'/frames/wrong_form.php',
						'already' => $_SERVER['DOCUMENT_ROOT'].'/frames/wrong_user.php',
						'publications' => $_SERVER['DOCUMENT_ROOT'].'/frames/publi.php',
						'publi' => $_SERVER['DOCUMENT_ROOT'].'/frames/details.php',
						'index' => $_SERVER['DOCUMENT_ROOT'].'/frames/main_page.php',
						'fccalculator' => $_SERVER['DOCUMENT_ROOT'].'/frames/carbon_footprint_calculator.php',
						'sampleresults' => $_SERVER['DOCUMENT_ROOT'].'/frames/sample_results.php',
						'copyright' => $_SERVER['DOCUMENT_ROOT'].'/frames/copyright.php',
						'contact' => $_SERVER['DOCUMENT_ROOT'].'/frames/contacts.php',
						'unregister' => $_SERVER['DOCUMENT_ROOT'].'/frames/unreg.php',
						'event_registration' => $_SERVER['DOCUMENT_ROOT'].'/frames/event_reg.php',
						'confirm_participation' => $_SERVER['DOCUMENT_ROOT'].'/frames/confirm_event_registration.php',
						'workshops_page' => $_SERVER['DOCUMENT_ROOT'].'/frames/workshops.php',
						'trip' => $_SERVER['DOCUMENT_ROOT'].'/frames/trip_calculator.php',
						'search' => $_SERVER['DOCUMENT_ROOT'].'/frames/action.php',
						'afleet' => $_SERVER['DOCUMENT_ROOT'].'/frames/afleet.php',
						'greetdotnet' => $_SERVER['DOCUMENT_ROOT'].'/greet/greetdotnet.html');
		$actionsok = array('new' => $_SERVER['DOCUMENT_ROOT'].'/security/new_user.php',
							'check' => $_SERVER['DOCUMENT_ROOT'].'/security/check_mail.php',
							'check_file_details' => $_SERVER['DOCUMENT_ROOT'].'/security/check_file_details.php',
							'download' => $_SERVER['DOCUMENT_ROOT'].'/security/protected_download.php',
							'new_user_event' => $_SERVER['DOCUMENT_ROOT'].'/security/new_user_event_reg.php',
							'check_user_event' => $_SERVER['DOCUMENT_ROOT'].'/security/check_user_event_reg.php');
		$bypass = array();
	 }
	 else
	 {
		$pageok = array('download1x' => $_SERVER['DOCUMENT_ROOT'].'/frames/downloadv1x.php',
						'download2x' => $_SERVER['DOCUMENT_ROOT'].'/frames/downloadv2x.php',
						'power_water' => $_SERVER['DOCUMENT_ROOT'].'/frames/power_water_model.php',
						'publications' => $_SERVER['DOCUMENT_ROOT'].'/frames/publi.php',
						'publi' => $_SERVER['DOCUMENT_ROOT'].'/frames/details.php',
						'index' => $_SERVER['DOCUMENT_ROOT'].'/frames/main_page.php',
						'fccalculator' => $_SERVER['DOCUMENT_ROOT'].'/frames/carbon_footprint_calculator.php',
						'sampleresults' => $_SERVER['DOCUMENT_ROOT'].'/frames/sample_results.php',
						'copyright' => $_SERVER['DOCUMENT_ROOT'].'/frames/copyright.php',
						'contact' => $_SERVER['DOCUMENT_ROOT'].'/frames/contacts.php',
						'unregister' => $_SERVER['DOCUMENT_ROOT'].'/frames/unreg.php',
						'event_registration' => $_SERVER['DOCUMENT_ROOT'].'/frames/event_reg.php',
						'already_registered_event' => $_SERVER['DOCUMENT_ROOT'].'/frames/already_event_registered.php',
						'added_event' => $_SERVER['DOCUMENT_ROOT'].'/frames/correclty_added_to_event.php',
						'confirm_participation' => $_SERVER['DOCUMENT_ROOT'].'/frames/confirm_event_registration.php',
						'survey_workshop' => $_SERVER['DOCUMENT_ROOT'].'/frames/survey_form.php',
						'get_survey_inputs' => $_SERVER['DOCUMENT_ROOT'].'/security/get_survey_inputs.php',
						'workshops_page' => $_SERVER['DOCUMENT_ROOT'].'/frames/workshops.php',
						'search' => $_SERVER['DOCUMENT_ROOT'].'/frames/action.php',
						'trip' => $_SERVER['DOCUMENT_ROOT'].'/frames/trip_calculator.php',
						'afleet' => $_SERVER['DOCUMENT_ROOT'].'/frames/afleet.php',
						'greetdotnet' => $_SERVER['DOCUMENT_ROOT'].'/greet/greetdotnet.html');
		$actionsok = array('check_file_details' => $_SERVER['DOCUMENT_ROOT'].'/security/check_file_details.php',
							'download' => $_SERVER['DOCUMENT_ROOT'].'/security/protected_download.php',
							'check_user_event' => $_SERVER['DOCUMENT_ROOT'].'/security/check_user_event_reg.php');
		$bypass = array('registration' => $_SERVER['DOCUMENT_ROOT'].'/frames/register.php');
	 }
	
	$pub_key = $_GET['pub_key'];
	$page_title_for_publications = 'Argonne GREET Publication : ';
	$description_for_publications = 'GREET : Publication details. ';
	if (file_exists($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml"))
	{
		$xml = simplexml_load_file($_SERVER['DOCUMENT_ROOT']."/static_data/files.xml");
		$xfiles_list = $xml->files->file;
		$lines = array();
		$founded = false;
		foreach($xfiles_list as $xfile)
		{ 
			if($xfile['key']==$pub_key)
			{
				if($xfile['title']!='')
				{
						$page_title_for_publications .= $xfile['title'];
				}
				if($xfile['abstract']!='')
				{
					$description_for_publications .= $xfile['abstract'];
				}
			}
		}
	}

	$titles = array(	'download1x' => 'Argonne GREET Fuel Cycle Model',
						'download2x' => 'Argonne GREET Vehicle Cycle Model',
						'power_water' => 'Argonne Power Water Model',
						'registration' => 'Argonne GREET Registration Form',
						'publications' => 'Argonne GREET Publications',
						'publi' => $page_title_for_publications,
						'index' => 'Argonne GREET Model',
						'fccalculator' => 'Argonne Fleet - Carbon and Petroleum Footprint Calculator',
						'sampleresults' => 'Argonne GREET Sample Results',
						'copyright' => 'Argonne GREET Copyright',
						'contact' => 'Argonne GREET Contacts',
						'event_registration' => 'Argonne GREET Event Registration',
						'workshops_page' => 'Argonne Workshops',
						'trip' => 'Argonne Trip Calculator',
						'afleet' => 'AFLEET Tool');
						
	$descriptions = array('download1x' => 'GREET : Download page for the fuel cycle model',
						'download2x' => 'GREET : Download page for the vehicle cycle model',
						'power_water' => 'GREET : Power Water Model',
						'registration' => 'GREET : Registration form to access the download pages',
						'publications' => 'GREET : List of publications',
						'publi' => $description_for_publications,
						'index' => 'GREET : The Greenhouse Gases, Regulated Emissions, and Energy Use in Transportation Model by Argonne National Laboratory',
						'fccalculator' => 'GREET : Fleet Carbon and Petroleum Footprint Calculator',
						'sampleresults' => 'GREET : Sample Results',
						'copyright' => 'GREET : Copyright',
						'contact' => 'GREET : Contacts',
						'event_registration' => 'GREET : Event Registration',
						'workshops_page' => 'GREET : Workshops',
						'trip' => 'GREET : Trip Calculator',
						'afleet' => 'AFLEET Tool');
	
	
	//test which action is asked by the user
	 if ((isset($_GET['action'])) && $ban==false) 
	 {
		 if(isset($actionsok[$_GET['action']]))
		 {
			include($actionsok[$_GET['action']]);
		 }
		 else
		 {
			include($_SERVER['DOCUMENT_ROOT'].'/security/protected_download.php');
		 }
	 }
	 
	 
?>

<!DOCTYPE html>
<!-- saved from url=(0042)http://www.anl.gov/energy-systems/research -->
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" version="XHTML+RDFa 1.0" dir="ltr" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:dc="http://purl.org/dc/terms/" xmlns:foaf="http://xmlns.com/foaf/0.1/" xmlns:og="http://ogp.me/ns#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xmlns:sioc="http://rdfs.org/sioc/ns#" xmlns:sioct="http://rdfs.org/sioc/types#" xmlns:skos="http://www.w3.org/2004/02/skos/core#" xmlns:xsd="http://www.w3.org/2001/XMLSchema#" class="js"><!--<![endif]--><head profile="http://www.w3.org/1999/xhtml/vocab"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
<!--[if lte IE 9]><script src="http://www.anl.gov/sites/all/themes/anl/scripts/html5shiv.js" />
</script><![endif]--><!--[if lte IE 9]><script src="http://www.anl.gov/sites/all/themes/anl/scripts/selectivizr-min.js" />
</script><![endif]-->
<!--link rel="shortcut icon" href="http://www.anl.gov/sites/all/themes/anl_division/favicon.ico" type="image/vnd.microsoft.icon"-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<link rel="stylesheet" href="./css/themes/blue/style.css" type="text/css" media="print, projection, screen" />

<!-- load jQuery and tablesorter scripts -->
<script type="text/javascript" src="./javascripts/jquery-latest.js"></script> 
<script type="text/javascript" src="./javascripts/jquery.tablesorter.js"></script>
<script type="text/javascript" src="./javascripts/__jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="./javascripts/addons/pager/jquery.tablesorter.pager.js"></script>
<link rel="stylesheet" href="./javascripts/addons/pager/jquery.tablesorter.pager.css">

<script type="text/javascript"src="./javascripts/publications.js"></script>

<link rel='shortcut icon' href='./greet/pic/greetLogo.ico' type='image/x-icon' />
<meta http-equiv="X-UA-Compatible" content="IE=10">
<meta name="generator" content="Drupal 7 (http://drupal.org)">
<meta name="google-site-verification" content="FI3a01D7-_yBN0LCOk7Z0AQaFnXnJZGv5kOKv3OBRkk" />
<link rel="canonical" href="./index_files/index.html">
<link rel="shortlink" href="http://www.anl.gov/node/6152">


 <?php
if ( isset($_GET['content']) ){
	 if (isset($pageok[$_GET['content']]))
	 {
		echo '<title>'.$titles[$_GET['content']].'</title>';
		echo '<meta name=description content="'. $descriptions[$_GET['content']] . '">';
	 }
 	 else if ($bypass[$_GET['content']] && isset($_GET['from']) )
	 {
		echo '<title>'.$titles[$_GET['from']].'</title>';
		echo '<meta name=description content="'. $descriptions[$_GET['from']] . '">';
	 }
	 else
	 {
		echo '<title>Argonne GREET Model</title>';
		echo '<meta name=description content="GREET : The Greenhouse Gases, Regulated Emissions, and Energy Use in Transportation Model by Argonne National Laboratory">';
	 }
}
else
{	
	echo '<title>Argonne GREET Model</title>';
	echo '<meta name=description content="GREET : The Greenhouse Gases, Regulated Emissions, and Energy Use in Transportation Model by Argonne National Laboratory">';
}		
?>
<link type="text/css" rel="stylesheet" href="./index_files/css_xE-rWrJf-fncB6ztZfd2huxqgxu4WO-qwma6Xer30m4.css" media="all">
<link type="text/css" rel="stylesheet" href="./index_files/css__r408iefJFDp7tkJY64xde0BtyLdHU1-GytpTja6H4w.css" media="all">
<link type="text/css" rel="stylesheet" href="./index_files/css_qi73lf_EuS_p6Xrs4zNzF8MafQ0vkJ3SzIUeehmp990.css" media="all">
<link type="text/css" rel="stylesheet" href="./index_files/css_qr9pqr0G2cVXW7KEys3G4Q6pMyJmY2ubfLfb_b145Dg.css" media="all">
<link type="text/css" rel="stylesheet" href="./index_files/css_1NlJ7xgvN7tyeZ2gyeKpHbHEQah7MMx8dDFny7z0wnY.css" media="print">

</head>
<body class="html not-front not-logged-in one-sidebar sidebar-first page-node page-node- page-node-6152 node-type-landing-dflt group-energy-systems purple group-context group-context-group-8 group-context-taxonomy-term group-context-taxonomy-term-607 group-subdomain energy-systems research">
<script>
$(function(){
  $("table").tablesorter({
		theme : 'blue',
		
        headers: { 
            // assign the 4th column (we start counting zero) 
            3: { 
                // disable it by setting the property sorter to false 
                sorter: false 
            }
        } 
    });
});
</script>

  <div id="skip-link">
    <a href="http://www.anl.gov/energy-systems/research#main-content" class="element-invisible element-focusable">Skip to main content</a>
  </div>
  <div id="page-header">
  <div id="header-wrapper">
	<div id="header-top-wrapper">
		<div class="container">
			<div id="header-secondary-nav">
				  <div class="region region-divisionsecondarynav">
    <div class="block-energy-core-navigation-secondary-wrapper">
  <div id="block-energy-core-navigation-secondary" class="block block-energy-core block-energy-core-navigation-secondary clearfix">

          
    <div class="content">
      <div class="secondary-menu"><ul class="menu"><li class="first collapsed"><a href="http://www.anl.gov/energy-systems/about-us" title="">About Us</a></li>
<li class="leaf"><a href="http://intranet.es.anl.gov/" title="">For ES Employees</a></li>
<li class="last collapsed"><a href="http://www.anl.gov/energy-systems/staff" title="">Staff Directory</a></li>
</ul></div>    </div>
  </div>
</div>
  </div>
			</div>
			<div id="header-search">
				  <div class="region region-divisionsearch">
    <div class="block-argonne-search-argonne-search-wrapper">
  <div id="block-argonne-search-argonne-search" class="block block-argonne-search block-argonne-search-argonne-search clearfix">

          
    <div class="content">
      <form action="http://public-search.anl.gov/search" method="GET" id="argonne-search-search-block" accept-charset="UTF-8"><div><div class="form-item form-type-textfield form-item-q">
 <input placeholder="SEARCH" type="text" id="edit-q" name="q" value="" size="60" maxlength="128" class="form-text">
</div>
<input type="submit" id="edit-submit" name="op" value="" class="form-submit"><input type="hidden" name="output" value="xml_no_dtd">
<input type="hidden" name="client" value="anl_gov">
<input type="hidden" name="proxystylesheet" value="anl_gov">
<input type="hidden" name="filter" value="0">
<input type="hidden" name="site" value="all_external">
<input type="hidden" name="form_build_id" value="form-F2g3LbQvmMBAuHd6GyuhvckF-g04tU7gA60otcMvhsE">
<input type="hidden" name="form_id" value="argonne_search_search_block">
</div></form>    </div>
  </div>
</div>
  </div>
			</div>
		</div>
    </div>
    <div id="header" class="container column-row">
        <div class="region region-header">
    <div><div class="block-energy-core-site-name-wrapper">
  <div id="block-energy-core-site-name" class="block block-energy-core block-energy-core-site-name clearfix">

          
    <div class="content">
      <h1 class="site-name"><a href="http://www.anl.gov/">Argonne National Laboratory</a></h1>
<h2 class="site-name"><a href="http://www.anl.gov/energy-systems">Energy Systems</a></h2>    </div>
  </div>
</div>
</div>  </div>
    </div>
  </div>
  <div id="navigation-wrapper" class="container column-row">
    <div id="navigation" class="column last grid-16">
        <div class="region region-navigation">
    <div class="block-energy-core-navigation-primary-wrapper">
  <div id="block-energy-core-navigation-primary" class="block block-energy-core block-energy-core-navigation-primary clearfix">

          
    <div class="content">
      <div class="primary-menu"><ul class="menu"><li class="first collapsed active-trail"><a href="http://www.anl.gov/energy-systems/research" title="" class="active-trail active-trail active">Research</a></li>
<li class="collapsed"><a href="http://www.anl.gov/energy-systems/facilities" title="">Facilities</a></li>
<li class="collapsed"><a href="http://www.anl.gov/energy-systems/publications" title="">Publications</a></li>
<li class="last collapsed"><a href="http://www.anl.gov/energy-systems/news" title="">News</a></li>
</ul></div>    </div>
  </div>
</div>
  </div>
    </div>
  </div>
</div> 
<div id="content-wrapper">
  <div id="top-wrapper">
  <div id="top" class="container column-row">
      </div>
  </div>
  <div id="page-wrapper">
  <div id="page" class="container column-row">

          <div id="sidebar-left" class="sidebar column first grid-4">  <div class="region region-sidebar-first">
    <div class="block-energy-core-subnavigation-wrapper">
  <div id="block-energy-core-subnavigation" class="block block-energy-core block-energy-core-subnavigation clearfix">

          <h4 class="block-title"><a href="index.php" class="active">GREET</a></h4>
      
    <div class="content">
      <div class="subnavigation"><ul class="menu">
<li class="leaf"><a href="publications" title="">Publications</a></li>
<li class="leaf"><a href="index.php?content=greetdotnet" title="">GREET.net Model</a></li>
<li class="leaf"><a href="greet_1_series" title="">Fuel-Cycle Model</a></li>
<li class="leaf"><a href="greet_2_series" title="">Vehicle-Cycle Model</a></li>
<li class="leaf"><a href="results" title="">Mini-tool and Results</a></li>
<li class="leaf"><a href="afleet" title="">AFLEET Tool</a></li>
<li class="leaf"><a href="fleet_footprint_calculator" title="">Fleet Footprint Calculator</a></li>
<li class="leaf"><a href="trip-calculator" title="">Travel Carbon Calculator</a></li>
<li class="leaf"><a href="power_water" title="">Power Water Model</a></li>
<li class="leaf"><a href="workshops" title="">Workshops</a></li>
<li class="leaf"><a href="contacts" title="">Contact</a></li>
<li class="leaf"><a href="copyright" title="">Copyright Statement</a></li>
</ul></div>    </div>
  </div>
</div>
  </div>
</div>
    
    <div id="main-content" class="column last grid-12">
      
    <?php

	
	//test which content is called and if the user have credentials to access it
		if ( isset($_GET['content']) )
		{
			 if (isset($pageok[$_GET['content']]))
				 {
					include($pageok[$_GET['content']]);
				 }
			 else if ($bypass[$_GET['content']] && isset($_GET['from']) )
			 {
				include($pageok[$_GET['from']]);
			 }
			 else
			 {
				 report_error("index.php : try to pass undefined content"."|".$_GET['content']."|".$registered);
				 {
					include($pageok['index']);
				 }
			 }
		 }
		 else if ( isset($_GET['action']) == false)
		 {
			include($pageok['index']);
		 }	

?>

    </div>

  </div> 
</div> 

<div id="footer-wrapper">
  <div id="footer" class="container column-row">
    <div id="footer-left" class="column first grid-5">
      <!--div id="footer-top-link"><a href="http://www.anl.gov/energy-systems/research#skip-link">Back to top</a></div-->
	  <div id="footer-top-link"><a href="#page-header">Back to top</a></div>
        <div class="region region-footer-left">
    <div class="block-energy-core-social-links-wrapper">
  <div id="block-energy-core-social-links" class="block block-energy-core block-energy-core-social-links clearfix">

          
    <div class="content">
      <div class="item-list"><ul class="social-links"><li class="first"><a href="http://twitter.com/argonne" target="_blank" class="twitter">Twitter</a></li>
<li><a href="http://www.flickr.com/photos/argonne/" target="_blank" class="flickr">Flickr</a></li>
<li><a href="http://www.facebook.com/pages/Argonne-National-Laboratory/40096518565" target="_blank" class="facebook">Facebook</a></li>
<li><a href="http://www.linkedin.com/company/162510" target="_blank" class="linked-in">Linked In</a></li>
<li><a href="http://www.youtube.com/user/ArgonneNationalLab" target="_blank" class="youtube">YouTube</a></li>
<li><a href="http://pinterest.com/argonnelab/" target="_blank" class="pinterest">Pinterest</a></li>
<li class="last"><a href="https://plus.google.com/104126437234499884842" target="_blank" class="gplus">Google Plus</a></li>
</ul></div>    </div>
  </div>
</div>
<div class="column grid-4 first"><div class="contact-info"><div class="block-energy-core-footer-contact-info-wrapper">
  <div id="block-energy-core-footer-contact-info" class="block block-energy-core block-energy-core-footer-contact-info clearfix">

          
    <div class="content">
      <p>Energy Systems<br>
	Argonne National Laboratory<br>
	9700 S. Cass Avenue<br>
	Building 362<br>
	Argonne, IL 60439-4844&nbsp; USA<br>
	e-mail: <a href="mailto:energy_systems@anl.gov">energy_systems@anl.gov</a></p>
<p><a href="http://www.anl.gov/energy-systems/about-us/contact-us">Contact Us</a></p>
    </div>
  </div>
</div>
</div></div>  </div>
    </div>
    <div id="footer-nav" class="column last grid-11">
        <div class="region region-footer-nav">
    <div class="block-bean-egs-footer-admin-freetext-wrapper">
  <div id="block-bean-egs-footer-admin-freetext" class="block block-bean bean-admin-freetext block-bean-egs-footer-admin-freetext clearfix">

          
    <div class="content">
      <div class="entity entity-bean bean-admin-freetext clearfix" about="/block/egs-footer-admin-freetext" typeof="">

  <div class="content">
    <div class="field field-name-field-admin-freetext-body field-type-text-long field-label-hidden"><div class="field-items"><div class="field-item even"><div class="footer-menu-grouping">
<h4 style="width:600px;"><a href="http://www.anl.gov/egs" style="margin-left:0;">Energy and Global Security</a></h4>
<h5 style="margin-bottom:10px;">Research Divisions</h5>
<ul><li style="list-style:none; margin-left:0;"><span><a href="http://www.anl.gov/energy-systems">ES</a></span><a href="http://www.anl.gov/energy-systems">Energy Systems</a></li>
<li style="list-style:none; margin-left:0;"><span><a href="http://www.gss.anl.gov/">GSS</a></span><a href="http://www.dis.anl.gov/">Global Security Sciences</a></li>
<li style="list-style:none; margin-left:0;"><span>IAD</span>Intelligence Analysis</li>
<li style="list-style:none; margin-left:0;"><span><a href="http://www.ne.anl.gov/">NE</a></span><a href="http://www.ne.anl.gov/">Nuclear Engineering</a></li>
</ul></div>
<div class="footer-menu-grouping" style="padding-top:60px;">
<h5 style="margin-bottom:10px;">Centers, Institutes, and Programs</h5>
<ul><li style="list-style:none; margin-left:0;"><span><a href="http://www.gss.anl.gov/center-for-risk-and-infrastructure-sciences-overview">RISC</a></span><a href="http://www.gss.anl.gov/center-for-risk-and-infrastructure-sciences-overview">Risk and Infrastructure Science Center</a></li>
<li style="list-style:none; margin-left:0;"><span><a href="http://www.transportation.anl.gov/">TTRDC</a></span><a href="http://www.transportation.anl.gov/">Transportation Technology R&amp;D Center</a></li>
</ul><h5><a href="http://www.anl.gov/science">Other Organizations »</a></h5>
</div>
<p>&nbsp;</p>
</div></div></div>  </div>
</div>
    </div>
  </div>
</div>
  </div>
    </div>
  </div>
</div>

<div id="footer-legal-wrapper" class="container column-row">
  <div id="footer-legal">
    <div class="region region-footer-legal">
    <div class="block-energy-core-legal-footer-wrapper">
  <div id="block-energy-core-legal-footer" class="block block-energy-core block-energy-core-legal-footer clearfix">

          
    <div class="content">
      
    <h5><a href="http://energy.gov/" target="_blank">Energy.gov</a></h5>
    <ul>
      <li><a href="http://science.energy.gov/">U.S. Department of Energy Office of Science</a></li>
      <li><a href="http://www.uchicagoargonnellc.org/">UChicago Argonne LLC</a></li>
      <li><a href="http://www.anl.gov/privacy-security-notice">Privacy &amp; Security notice</a></li>
    </ul>
        </div>
  </div>
</div>
  </div>
  </div>
</div>
  <script type="text/javascript" async="" src="./index_files/ga.js"></script>
  <script type="text/javascript" src="./index_files/js_gm7u0AyI-KeATRhjUtnD8wHquagfIt5_NH5d84I4NUo.js"></script>
<script type="text/javascript" src="./index_files/js_CnIoZDRmmYqBjCsVJLXQxohcKrd_gMMI1qCfQl3agrc.js"></script>
<script type="text/javascript" src="./index_files/js_VxGg34IaoVRKxxkMcYFDZHWbbX1tLiPgdQnTBig5AJo.js"></script>
<script type="text/javascript">
<!--//--><![CDATA[//><!--
jQuery.extend(Drupal.settings, {"basePath":"\/","pathPrefix":"","ajaxPageState":{"theme":"anl_division","theme_token":"Fu8qSOPK7IgdcMqltefka0i2Ptg3_Vo2M5B_DPEIMDQ","js":{"0":1,"misc\/jquery.js":1,"misc\/jquery.once.js":1,"misc\/drupal.js":1,"sites\/all\/libraries\/jquery.cycle\/jquery.cycle.all.min.js":1,"sites\/all\/libraries\/jquery.hoverIntent\/jquery.hoverIntent.min.js":1,"sites\/all\/modules\/custom\/argonne_social\/js\/argonne_social.js":1,"sites\/all\/modules\/energy.gov\/energy_hero\/js\/full_width.js":1,"misc\/form.js":1,"sites\/all\/modules\/contrib\/field_group\/field_group.js":1,"misc\/collapse.js":1,"sites\/all\/themes\/anl\/scripts\/placeholder_shim.js":1,"sites\/all\/themes\/anl\/scripts\/equalHeights.js":1,"sites\/all\/themes\/anl\/scripts\/anl.js":1},"css":{"modules\/system\/system.base.css":1,"modules\/system\/system.menus.css":1,"modules\/system\/system.messages.css":1,"modules\/system\/system.theme.css":1,"sites\/all\/modules\/contrib\/date\/date_api\/date.css":1,"sites\/all\/modules\/contrib\/date\/date_popup\/themes\/datepicker.1.7.css":1,"modules\/field\/theme\/field.css":1,"modules\/node\/node.css":1,"modules\/search\/search.css":1,"modules\/user\/user.css":1,"sites\/all\/modules\/contrib\/views\/css\/views.css":1,"sites\/all\/modules\/custom\/argonne_careers_blocks\/css\/argonne_careers_blocks.css":1,"sites\/all\/modules\/custom\/argonne_media\/argonne_media.css":1,"sites\/all\/modules\/custom\/argonne_social\/css\/argonne_social.css":1,"sites\/all\/modules\/contrib\/ctools\/css\/ctools.css":1,"sites\/all\/modules\/energy.gov\/energy_content\/css\/wysiwyg.css":1,"sites\/all\/modules\/contrib\/ldap\/ldap_help\/ldap_help.css":1,"sites\/all\/modules\/contrib\/shib_auth\/shib_auth.css":1,"sites\/all\/modules\/contrib\/biblio\/biblio.css":1,"sites\/all\/modules\/contrib\/field_group\/field_group.css":1,"sites\/all\/themes\/anl\/style\/reset.css":1,"sites\/all\/themes\/anl\/style\/drupal.css":1,"sites\/all\/themes\/anl\/style\/grid.css":1,"sites\/all\/themes\/anl\/style\/layout.less":1,"sites\/all\/themes\/anl\/style\/fontography.less":1,"sites\/all\/themes\/anl_division\/style\/anl_division.css":1,"sites\/all\/themes\/anl\/style\/print.css":1}},"energy_content":{"nid":"6152","base_url":"http:\/\/www.anl.gov"},"field_group":{"div":"full","grid_column":"full"},"og":{"og_context":{"gid":"8","etid":"607","entity_type":"taxonomy_term","label":"Energy Systems","state":"1","created":"1374183959","rdf_mapping":[]}}});
//--><!]]>
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-40965405-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</div><script id="hiddenlpsubmitdiv" style="display: none;"></script><script>try{for(var lastpass_iter=0; lastpass_iter < document.forms.length; lastpass_iter++){ var lastpass_f = document.forms[lastpass_iter]; if(typeof(lastpass_f.lpsubmitorig2)=="undefined"){ lastpass_f.lpsubmitorig2 = lastpass_f.submit; if (typeof(lastpass_f.lpsubmitorig2)=='object'){ continue;}lastpass_f.submit = function(){ var form=this; var customEvent = document.createEvent("Event"); customEvent.initEvent("lpCustomEvent", true, true); var d = document.getElementById("hiddenlpsubmitdiv"); if (d) {for(var i = 0; i < document.forms.length; i++){ if(document.forms[i]==form){ if (typeof(d.innerText) != 'undefined') { d.innerText=i.toString(); } else { d.textContent=i.toString(); } } } d.dispatchEvent(customEvent); }form.lpsubmitorig2(); } } }}catch(e){}</script>
<script defer="defer"> 
$(document).ajaxStop(function() 
{ 
    $("table")
		.tablesorter()
	; 
}); 
</script>
</body>
</html>