<?php

/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/* [ NOM DU MODULE ] Configuration File [ ANNEE ] par [ NOM DU DEVELOPPEUR ] */
/*                                                                      */
/************************************************************************/

#---------------------------------------------------------------------------
# just to know which script version is executing
#---------------------------------------------------------------------------
#
$pageName		= 'yrnoSettings.php';
$pageVersion	= '0.10 2013-01-22';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
#---------------------------------------------------------------------------
#
#
$SITE['yrnoIconsOwn']	= true;					// #####	use original yrno icons or our general icons (false)
#
$SITE['yourArea']		= 'yourArea';			// #####	example	Leuven
$SITE['organ']			= 'yourStationName';	// #####			Weerstation Leuven
$SITE['siteUrl']		= 'your website url';	// #####			www.weerstation-leuven.be
#
$SITE['latitude']		= '42.713674';			// #####	North=positive, South=negative decimal degrees
$SITE['longitude']		= '2.849287';			// #####	East=positive, West=negative decimal degrees

#$SITE['latitude']		= '43.004647';			// #####	North=positive, South=negative decimal degrees
#$SITE['longitude']		= '-81.82';			// #####	East=positive, West=negative decimal degrees

# $SITE['latitude']		= '34.240';			// #####	North=positive, South=negative decimal degrees
# $SITE['longitude']		= '-118.566';			// #####	East=positive, West=negative decimal degrees

#
#---------------------------------------------------------------------------
# units of measurement UOM  settings     		// #####     set them the same as used on your other webpages
#---------------------------------------------------------------------------
$SITE['uomTemp']		= '&deg;C';		// ='&deg;C', ='&deg;F'
$SITE['uomRain']		= ' mm';		// =' mm', =' in'
$SITE['uomWind'] 		= ' km/h';		// =' km/h', =' kts', =' m/s', =' mph'
$SITE['uomBaro']		= ' hPa';		// =' hPa', =' mb', =' inHg'
$SITE['uomSnow']		= ' cm';		// =' cm', =' in'
$SITE['uomDistance']	= ' km';		// =' km', = ' mi'  used for visibillity
#
#---------------------------------------------------------------------------
# date and time settings
#---------------------------------------------------------------------------
$SITE['timeFormat']		= 'd-m-Y H:i';	// 31-03-2012 14:03
$SITE['timeOnlyFormat']	= 'H:i';		// Euro format hh:mm  (hh=00..23);
$SITE['dateOnlyFormat']	= 'd-m-Y';		// for 31-03-2013
$SITE['dateLongFormat']	= 'l d F Y';	// Thursday 3 january 2013
#
$SITE['hourOnlyFormat']	= 'H';			// Euro format 'H'  (hh=00..23);  us format 'h a'   05 pm
#
#---------------------------------------------------------------------------
#	if you want to change anything down here make sure you know what you are doing
#---------------------------------------------------------------------------
$SITE['tz']				= 'Europe/Brussels';
#$SITE['tz']				= 'America/Toronto';
#$SITE['tz']				= 'America/Los_Angeles';
# Time zone for the whole of western europe, leave it if you do not know your EXACT timezone description
#
#---------------------------------------------------------------------------
# Multilanguage support
#---------------------------------------------------------------------------
$SITE['lang'] 			= 'fr';					// default language  to use
$SITE['langFlags']		= false;    			// true=show flags  DO NOT CHANGE
$SITE['langDir']		= "modules/$ModPath/lang/";				// all language files are store here
$SITE['langAvail']		= array('en', 'nl', 'fr', 'de', ); // english + duth + french + german
#---------------------------------------------------------------------------
# directory settings
#---------------------------------------------------------------------------
$SITE['scriptDir']		= "modules/$ModPath/"; 				// the folder the script is executing from
$SITE['cacheDir']		= "modules/$ModPath/cache/";				// the retrieved information is cached here
#---------------------------------------------------------------------------
# directories where supporting scripts and files are located, all relative to this script
#---------------------------------------------------------------------------
$SITE['iconsDir']		=  $SITE['scriptDir'].'wsIcons/';	// all icons are store here in separate folders
$SITE['ownIconsDir']	=  $SITE['iconsDir'].'own_icons/';	// default KDE icons
$SITE['scriptIconsDir']	=  $SITE['iconsDir'].'yrno_icons/';	// yrno original icons
$SITE['scriptIconsWind']=  $SITE['scriptDir'].'windIcons/';					// wind icons (white ones)
$SITE['yrnoIconsDir']	=  $SITE['scriptIconsDir'];			// DO NOT CHANGE
$windIcons				=  $SITE['scriptIconsWind']; //'windIcons/';
$windIconsSmall			=  $SITE['scriptDir'].'windIconsSmall/';				// DO NOT CHANGE
$SITE['imgDir']			=  $SITE['scriptDir'].'img/';				// contains images like sun-up  sun-down
$SITE['javascriptsDir']	=  $SITE['scriptDir'].'javaScripts/';		// contains the javascripts for graphs
$SITE['supportDir']		=  'otherScripts/';
	// language translate scripts & language files	wsLangFunctions.php plus lang directory
// general functions							wsFunctions.php
// icon translate								wsIconUrl.php
# url added settings
#
#---------------------------------------------------------------------------
# set error reporting
#---------------------------------------------------------------------------
# for testing and debugging only  -  set to comment for production versions
# 	comment out in a production environment
if (isset($_REQUEST['debug'])){		// display error messages
	$noMissingLang = true;
	$errorMessages = true;
}
if ($errorMessages) {
ini_set('display_errors', 'On');
	error_reporting(E_ALL);
	}
	#---------------------------------------------------------------------------
	# Multilanguage support
	#---------------------------------------------------------------------------
	#  when starting the  script you can choose a different language
	#    by adding ?lang=xx at the end of the script name
	#    www.yourwebsite.com/wsYrNoSA/demoFull.php?lang=de
	#
	if (isset($_REQUEST['lang'])){		   // than check this request settings
		$s=trim(strtolower($_REQUEST['lang']));
	if (in_array($s, $SITE['langAvail']))	{
			$SITE['lang'] 	= $s;
	}
	}
?>