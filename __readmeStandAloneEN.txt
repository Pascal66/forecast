This a Forecast display script for MetNo data 

folders:
	cache			for saving converted xml arrays
	img				images as: sun-up sun-down
	javascripts		jquery.js	highcharts.js
	lang			language translate files 
	otherScripts	wsLangFunctions.php	wsFunctions.php wsIconUrl.php 
	windicons		white wind icons
	windiconsSmall	small blue wind icons
	wsIcons			icons for cloud/weather conditions

Stylesheets:
	metno.css			needed to correctly display the output

scripts:
	metnoSettings.php		all general (non output) settings are set once
	metnoCreateArr.php		read xml and saves caches translated array 
	metnoGenerateHtml.php	uses array (from metnoCreateArr.php) and generates strings for output

example scripts
	printDemo.php				adaption of printFull.php with extra info
	printFull.php				prints all available information
	printSmall.php				prints nearly all information in a very small footprint
	printTop.php				only the icons with some information are printed
	
	wxmetnoFcstpage.php			saratoga page to be called from Saratoga menu
	printSaratoga.php			settings and print script for us in Saratoga template

How to install:
===============
1. Put the install folder (wsMetNoSA) in the root of your website.
	You can change the name of the folder. No settings have to be changed for that.
	But please: 
		First try to install in the root and test (step2).
		If everything works as wanted you can move the whole folder to a new location.
	If you change the location of the folder (so NOT in the root, some settings have to be changed)

2. Execute the script with: www.yourwebsite.com/wsMetNoSA/printDemo.php?lang=en
                            =============================================
    If errors occur: check write permissions on the cache folder first.
	Set the lang=xx parameter to the desired language. (de german, fr  french, en  english, nl dutch)

It should work OUT of the box!

How to install in the Saratoga template:
========================================
After testing the standalone version to make sure you understand how the scripts work:

1. Put the wxmetnoFcstpage.php at the same place as the other Saratoga pages.
	This script simply calls the printSaratoga.php script from the wsMetNoSa folder.
	As the settings are done by the template, only the extra settings needed are defined.
	
2. Add an entry to your menu for this page.

3. Copy the contents of the language additions from the correct file in the lang folder to your own language file.

F.i. your language is Dutch:
You copy the contents of the file wsMetNoSA/lang/wsLanguage-nl-local.txt to end of the file in the Saratoga root language-nl-local.txt
If you do not have a local version of your language file in the Saratoga root you are advised to make one to store your own langauge additions or changes.

The script assumes that in the Saratoga folder (normally the root) a folder cache/ exists to store the preprocessed xml arrays.

Other changes:
==============
If you  make even 1 change to the settings during testing
ALWAYS empty the cache folder (wsMetNoSA/cache).
In this cache a pre-processed version of the xml is stored.
So strange results occur when changing settings and NOT emptying the cache.

Settings in metnoSettings.php are needed to get the script operating with your xml for your area.
The lines which you HAVE to check or change contain ##### in the remark


1. Choose if you want to use the YrNo icons or the KDE icons
	$SITE['yrnoIconsOwn']	= true;					// #####	use original yrno icons or our general icons (false)

2. 	Describe the area you are in and specify the name of your website
	$SITE['yourArea']		= 'yourArea';			// #####	example	Leuven
	$SITE['organ']			= 'yourStationName';	// #####			Weerstation Leuven
	$SITE['siteUrl']		= 'your website url';	// #####			www.weerstation-leuven.be

3. Your forecast is retrieved based on your coordinates. Set them coorectly. It is set to Leuven Belgium now.
	Also used to correctly calculate the hours of daylight.
	$SITE['latitude']		= '50.89518';			// #####	North=positive, South=negative decimal degrees
	$SITE['longitude']		= '4.69741';			// #####	East=positive, West=negative decimal degrees

4. The default settings (as used on most websites) of the units for rain, wind and so on can be changed now
	$SITE['uomTemp']		= '&deg;C';		// ='&deg;C', ='&deg;F'
	$SITE['uomRain']		= ' mm';		// =' mm', =' in'
	$SITE['uomWind'] 		= ' km/h';		// =' km/h', =' kts', =' m/s', =' mph'
	$SITE['uomBaro']		= ' hPa';		// =' hPa', =' mb', =' inHg'
	$SITE['uomSnow']		= ' cm';		// =' cm', =' in'
	$SITE['uomDistance']	= ' km';		// =' km', = ' mi'  used for visibillity 
	
5. The format of dates and times can be changed so that they are the same as the rest of your website
	$SITE['timeFormat']		= 'd-m-Y H:i';	// 31-03-2012 14:03
	$SITE['timeOnlyFormat']	= 'H:i';		// Euro format hh:mm  (hh=00..23);
	$SITE['dateOnlyFormat']	= 'd-m-Y';		// for 31-03-2013
	$SITE['dateLongFormat']	= 'l d F Y';	// Thursday 3 january 2013
	
	$SITE['hourOnlyFormat']	= 'H';			// Euro format 'H'  (hh=00..23);  us format 'h a'   05 pm
	
There are other settings which are normally never changed.

Settings in printFull.php have to be set only once.
            =============               ==========
You can than copy the script and save with a new name to further customize.

There are other (small and top) versions of the printFull.php which you can also use as a starting point.

1. First of all: do not change numerous settings without testing in between!
2. Do not forget to clean the cache when you are going to test with changed settings!
3. Error messages are ON by default. If you do not like that switch them off
	$errorMessages			= true;			// true: error messages for all php errors. false: supprres the messages
4. The script can output the necessary HTML to have a full functioning page.
	If you incorparate the output in your own pages, swith the generation of HTML / HEAD off
	$includeHead			= true; 		// <head><body><css><scripts> are loaded
5. The colors and the width of the output can be set
	$colorClass				= 'pastel';		// pastel green blue beige orange 
	$pageWidth				= '800px';		// set do disired width 999px  or 100%
6. The scripts generate four different pieces of output. You can choose which parts you want to print.
	$updateTimes			= true;			// two lines with recent file / new update information
	$iconGraph				= true;			// icon type header  with 1 icons for each daypart (6 hours data)
	$chartsGraph			= true;			// high charts graph one colom for every 6 hours
	$metnoTable				= true;			// table with one line for every 6 hours
7. You can set the number of coloms / icons 
	$topCount				= 8;			// max nr of day-part forecasts in icons or graph
	
In the middle part of the printFull.php the different parts are printed to the screen based on your settings so far.
There are default settings for margins, width and so on.
You can adapt them as you like.
But do not forget to test often.
If you only make one cahnge and the the test fails, you can easily spot your typing error.
If you make changes on multiple lines across the script firdst and than test, you are in trouble when the test fails.

From your yr.no xml  or metno xml:
<!--
In order to use the free weather data from yr no, you HAVE to display 
the following text clearly visible on your web page. The text should be a 
link to the specified URL.
-->
I enclosed this text in $creditString which is by default displayed at the bottom 
of the normal output pages.

Do not forget to include this (or another credit text) on your page
  
For changes and new scripts often visit www.weerstation-leuven.be

Succes.

Wim van der Kuil










