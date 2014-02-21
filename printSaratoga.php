<?php
$errorMessages = true;	// for testing and debugging only  -  set to false for production versions
#
#---------------------------------------------------------------------------
# set error reporting  
#---------------------------------------------------------------------------
if (isset($_REQUEST['debug'])){		// display error messages 
	$noMissingLang = $errorMessages = true;
} 
if ($errorMessages) {
	ini_set('display_errors', 'On'); 
	error_reporting(E_ALL);
}
#---------------------------------------------------------------------------
# Needed for stand alone use (without the template envirionment) 
#$SITE = array();
#---------------------------------------------------------------------------
#
# Customize here:
#
$includeHead			= false; 		// <head><body><css><scripts> are loaded
$colorClass				= 'pastel';		// pastel green blue beige orange 
$pageWidth				= '800px';		// set do disired width 999px  or 100%
#
$updateTimes			= true;			// two lines with recent file / new update information
#
$iconGraph				= true;			// icon type header  with 2 icons for each day (12 hours data)
#
$topCount				= 8;			// max nr of day-part forecasts in icons or graph
$topWidth				= '100%';		// set do disired width 999px  or 100%
#
$chartsGraph			= true;			// high charts graph one colom for every 3 hours
$graphsSeparate			= true;			// high charts graph separate (true) or in a tab (false)
#
$metnoTable				= true;			// table with one line for every 3 hours
#
#$script		='./'.'metnoSettings.php';	// we need hard coded directory path here because settings are not loaded yet.
#$pathString	= '<!-- trying to load '.$script.' -->'.PHP_EOL;
#include ($script);
#
#---------------------------------------------------------------------------
# just to know which script version is executing
#---------------------------------------------------------------------------
#
$pageName		= 'printSaratoga.php';
$pageVersion	= '1.00 2013-05-14';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
$pathString	= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
$myPage = $pageFile;
#---------------------------------------------------------------------------
# set error reporting  
#---------------------------------------------------------------------------
if (isset($_REQUEST['debug'])){		// display error messages 
	$noMissingLang = $errorMessages = true;
} 
if ($errorMessages) {
	ini_set('display_errors', 'On'); 
	error_reporting(E_ALL);
}
#
$currentSiteArray		=	$SITE;				// save $SITE array in the case we do disturb it 
$SITE['yrnoIconsOwn']	= true; 				// #####	use original yrno icons or our general icons (false)
#
$SITE['yourArea']		= 'yourArea';			// #####	example	Leuven
$SITE['organ']			= 'yourStationName';	// #####			Weerstation Leuven
$SITE['siteUrl']		= 'your website url';	// #####			www.weerstation-leuven.be
#
$SITE['latitude']		= '50.89518';  			// #####	for Leuven
$SITE['longitude']		= '4.69741';			// #####	for Leuven
#
$SITE['tempSimple']		= false;				// false = we want colorfull temps;  true = red blue temps
#
$SITE['hourOnlyFormat']	= 'H';			// Euro format 'H'  (hh=00..23);  us format 'h a'   05 pm
#
$SITE['scriptDir']		= 'wsMetNoSA/'; 			// the folder the script is executing from
$SITE['cacheDir']		= 'cache/';				// the retrieved information is cached here
#---------------------------------------------------------------------------
# directories where supporting scripts and files are located, all relative to this script
#---------------------------------------------------------------------------
$SITE['iconsDir']		=  $SITE['scriptDir'].'wsIcons/';	// all icons are store here in separate folders
$SITE['scriptIconsDir']	=  $SITE['iconsDir'].'yrno_icons/';	// yrno original icons
$SITE['defaultIconsDir']=  
$SITE['ownIconsDir']	=  $SITE['iconsDir'].'default_icons/';	// default KDE icons
$SITE['scriptIconsWind']=  $SITE['scriptDir'].'windIcons/';					// wind icons (white ones)
$SITE['yrnoIconsDir']	=  $SITE['scriptIconsDir'];			// DO NOT CHANGE
$windIcons				=  $SITE['scriptDir'].'windIcons/';	
$windIconsSmall			=  $SITE['scriptDir'].'windIconsSmall/';				// DO NOT CHANGE	
$SITE['imgDir']			=  $SITE['scriptDir'].'img/';				// contains images like sun-up  sun-down
$SITE['javascriptsDir']	=  $SITE['scriptDir'].'javaScripts/';		// contains the javascripts for graphs
$SITE['supportDir']		= 'otherScripts/';	

#---------------------------------------------------------------------------
# set the Timezone abbreviation automatically based on $SITE['tzname'];
#---------------------------------------------------------------------------
if (!function_exists('date_default_timezone_set')) {
	 putenv("TZ=" . $SITE['tz']);
} else {
	 date_default_timezone_set($SITE['tz']);
}
$SITE['tzName']	= date("T",time());
$pathString	= '<!-- Timezone = '.$SITE['tzName'].' Time = '.date($SITE['timeFormat'],time()).' -->'.PHP_EOL;
#---------------------------------------------------------------------------
# load supporting scripts 
#---------------------------------------------------------------------------
#$lang		= $SITE['lang'];
#$pathString	.= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#$script	= $SITE['scriptDir'].$SITE['supportDir'].'wsLangFunctions.php';
#$pathString	.= '<!-- trying to load '.$script.' -->'.PHP_EOL;
#include_once($script);
$script	= $SITE['scriptDir'].$SITE['supportDir'].'wsFunctions.php';
$pathString	.= '<!-- trying to load '.$script.' -->'.PHP_EOL;
include_once($script);
$script	= $SITE['scriptDir'].$SITE['supportDir'].'wsIconUrl.php';
$pathString .= '<!-- trying to load '.$script.' -->'.PHP_EOL;
include_once($script);
#
#---------------------------------------------------------------------------
#  the following lines output the needed html if you want a stand alone page
#  this includes the CSS file used for formatting
#---------------------------------------------------------------------------
if ($includeHead) {
echo'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta name="description" content="'.$SITE['organ'].' - '. $myPage.'>" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1 "/>
	<link rel="stylesheet" type="text/css" href="metno.css" media="screen" title="screen" />
	<link rel="shortcut icon" href="img/icon.png" type="image/x-icon" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta name="Keywords" content="weather, Weather, temperature, dew point, humidity, forecast, Davis Vantage Pro,  Weather, Your City, weather conditions, live weather, live weather conditions, weather data, weather history, Meteohub " />
	<meta name="Description" content="Weather conditions '.$SITE['organ'].'" />
	<title>'.$SITE['organ'].' -  met.no stand alone script</title>
	<script type="text/javascript">
		var docready=[],$=function(){return{ready:function(fn){docready.push(fn)}}};
	</script>
</head>
<body class="'.$colorClass.'" style="text-align: center;"><br />'.PHP_EOL;
}
echo $pathString;  //  display info about all loaded scripts and version numbers as html comment lines
#
#-----------------------  I M P O R T A N T   -----------------------------
#
#	if you use your own page setup you should ADD a line with
#
#	<link rel="stylesheet" type="text/css" href="wsMetNoSA/echo '<div style="background-color: transparent; width: 100%;">'.PHP_EOL;metno.css" media="screen" title="screen" />
#
#	make sure you have the correct path set in href
#
#  	in your html so that the correct css is loaded
#---------------------------------------------------------------------------
#	Now we output the enclosing div for which you set the width in the first lines of this script
#
#echo '<div id="pagina" style="background-color: transparent; width: '.$pageWidth.'">'.PHP_EOL;
echo '<div style="background-color: transparent; width: 100%;">
	<script type="text/javascript">
		var docready=[],$=function(){return{ready:function(fn){docready.push(fn)}}};
	</script>'.PHP_EOL;
#
# Then we get the data from a weather class 
#
$script	= $SITE['scriptDir'].'metnoCreateArr.php';
echo '<!-- trying to load '.$script.' -->'.PHP_EOL;
include($script);
$weather 		= new metnoWeather ();
$returnArray 	= $weather->getWeatherData($SITE['latitude'],$SITE['longitude']);
#
#echo '<pre>'; print_r ($returnArray); exit;
#---------------------------------------------------------------------------
# Now create all tables and graphs to be printed here
#
$script	= $SITE['scriptDir'].'metnoGenerateHtml.php';
echo '<!-- trying to load '.$script.' -->'.PHP_EOL;
include ($script);
# Now ready for printing to the screen. Use echo for that
#	$wsUpdateTimes	: this forecast en next forecast times 
#	$tableIcons		: icon 
#	$graphPart1		: javascript / highcharts graph
#	$metnoListTable	: table with all forecast lines
#
$margin = '10px';
echo '<div style="width: '.$topWidth.'; clear: right;">';
echo $wsUpdateTimes;
echo '</div>'.PHP_EOL;
#
if (isset ($iconGraph) && $iconGraph == true) {
						// $topwidth is set in first lines of script 
	$margin = '10px';	// for top bottom margin. set to '0px' if not needed or any desired distance
	echo '<div id="iconGraph" style="width: '.$topWidth.'; border: 1px inset; border-radius: 5px; margin: '.$margin.' auto;">';
	echo $tableIcons.PHP_EOL;
	echo '</div>';
}
#
if ($graphsSeparate) {		// are the graphs separate (=true) on the page or are they in a tab
	if (isset ($chartsGraph) && $chartsGraph == true) {
							// $topwidth is set in first lines of script 
		$margin = '10px';	// for top bottom margin. set to '0px' if not needed or any desired distance
		echo '<div id="containerTemp" style="width: '.$topWidth.'; height: 340px; margin: '.$margin.' auto;">here the graph will be drawn</div>'.PHP_EOL;
		echo $graphPart1.PHP_EOL;
	}
}
#
$height = '400';	// to restrict height to suppress very large/long pages
$width	= '90%;';
if ( isset($height) ) {
	$styleHeight='height:'.($height+42).'px;';
} else {
	$styleHeight='';
}
echo '<div class="tabber"  style="width:100%; margin: auto;">'.PHP_EOL;
	if (!$graphsSeparate) {		// are the graphs separate on the page or are they in a tab  (=false)
		if (isset ($chartsGraph) && $chartsGraph == true) {
			echo '<div class="tabbertab" style=""><h2>'.langtransstr('Graph').'</h2>'.PHP_EOL;
				$margin = '10px 0px 0xp 0px';	// for top bottom margin. set to '0px' if not needed or any desired distance
				if ($height > 400) {$graphHeight = 400;} else {$graphHeight = $height;}
				echo '<div id="containerTemp" style="width: 99%; height:'.$graphHeight.'px; border: 1px inset; border-radius: 5px; margin: '.$margin.' auto;">here the graph will be drawn</div>'.PHP_EOL;
				echo $graphPart1.PHP_EOL;
			echo '</div>'.PHP_EOL;	
		}
	}
	echo '<div class="tabbertab" style="'.$styleHeight.'"><h2>'.langtransstr('Forecast by 6 hour intervals').'</h2>'.PHP_EOL;
		echo $metnoListTable.PHP_EOL;
	echo '</div>'.PHP_EOL;
	echo '<div class="tabbertab" style="'.$styleHeight.'"><h2>'.langtransstr('48 hour details').'</h2>'.PHP_EOL;
		echo $metnoDetailTable.PHP_EOL;
	echo '</div>'.PHP_EOL;
#	echo $creditString;
echo '</div>'.PHP_EOL;

echo '<div id="credit" style="width: '.$topWidth.'; margin: '.$margin.' auto;">';
echo $creditString;
echo '</div><br />'.PHP_EOL;
#---------------------------------------------------------------------------
#  end of enclosing div
echo '
</div>
<!-- end of pagina -->
<br />'.PHP_EOL; // end pagina div

#
#---------------------------------------------------------------------------
#  the following lines output the needed scripts / html for a stand alone page
#
#-------------------I M P O R T A N T  -------------------------------------
# now we add the needed javascripts
# if you use this script inside another script make sure you add the javascripts yourself
#---------------------------------------------------------------------------
$javaOutput = $includeHead;		// we print javascript based on setting of enclosing html
$javaOutput = true;			// javascripts are ALWAYS printed by removing the #
#
if ($javaOutput) {
	$javaFolder = $SITE['javascriptsDir'];
	echo '<script type="text/javascript" src="'.$javaFolder.'jquery.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript" src="'.$javaFolder.'tabber.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript" src="'.$javaFolder.'highcharts.js"></script>'.PHP_EOL;
	echo '<script type="text/javascript">$=jQuery;jQuery(document).ready(function(){for(n in docready){docready[n]()}});</script>'.PHP_EOL;
}
if ($includeHead) {
	echo '
</body>
</html>';
}
#---------------------------------------------------------------------------
# Leave this code here .. it will help you see what language translations are missing 
# if you dont want them set next line to comment by adding a # as first cahracter on the line
$noMissingLang = true;
#
If (!isset ($noMissingLang) || $noMissingLang  == true) {
	$string='';
	foreach ($missingTrans as $key => $val) {
		$string.= "langlookup|$key|$key|".PHP_EOL;
	}
	if (strlen($string) > 0) {
		echo PHP_EOL.'<!-- missing langlookup entries for lang='.$lang.PHP_EOL;
		echo $string;
		echo count($missingTrans).' entries found.'.PHP_EOL.'End of missing langlookup entries -->'.PHP_EOL;
	}
}
?>