<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'wsFunctions.php';
$pageVersion	= '1.01h3 2012-08-25';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
if (isset ($pathString) ) {$pathString	.='<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;}
#-------------------------------------------------------------------------------------
# 	returns Temp Feels like based on chill heat and temp
#-------------------------------------------------------------ajax page-------------------
function wsFeelslikeTemp ($temp,$windchill,$heatindex,$tempUOM) {
global $Debug;
// establish the feelslike temperature and return a word describing how it feels
$HeatWords = array(
'Unknown', 'Extreme Heat Danger', 'Heat Danger', 'Extreme Heat Caution', 'Extremely Hot', 'Uncomfortably Hot',
'Hot', 'Warm', 'Comfortable', 'Cool', 'Cold', 'Uncomfortably Cold', 'Very Cold', 'Extreme Cold' );

// first convert all temperatures to Centigrade if need be
$TC = $temp;
$WC = $windchill;
$HC = $heatindex;

if(strpos($TC,',') !== false) {  // decimal: comma to point
	$TC = preg_replace('|,|','.',$temp);
	$WC = preg_replace('|,|','.',$windchill);
	$HC = preg_replace('|,|','.',$heatindex);
}

if (preg_match('|F|i',$tempUOM))  { // convert F to C if need be
	 $TC = sprintf("%01.1f",round(($TC-32.0) / 1.8,1));
	 $WC = sprintf("%01.1f",round(($WC-32.0) / 1.8,1));
	 $HC = sprintf("%01.1f",round(($HC-32.0) / 1.8,1));
}

// Feelslike

if ($TC <= 16.0 ) {
	$feelslike = $WC; //use WindChill
} elseif ($TC >=27.0) {
	$feelslike = $HC; //use HeatIndex
} else {
	$feelslike = $TC;   // use temperature
}

if (preg_match('|F|i',$tempUOM))  { // convert C back to F if need be
	$feelslike = (1.8 * $feelslike) + 32.0;
}
$feelslike = round($feelslike,0);

// determine the 'heat color word' to use  
$hcWord = $HeatWords[0];
$hcFound = false;
if ($TC > 32 and $HC > 29) {
	if ($HC > 54 and ! $hcFound) { $hcWord = '<span style="border: solid 1px; color: white; background-color: #BA1928;">&nbsp;'.htmlentities (langtransstr($HeatWords[1])).'&nbsp;</span>'; $hcFound = true;}
	if ($HC > 45 and ! $hcFound) { $hcWord = '<span style="border: solid 1px; color: white; background-color: #E02538;">&nbsp;'.htmlentities (langtransstr($HeatWords[2])).'&nbsp;</span>'; $hcFound = true;}
	if ($HC > 39 and ! $hcFound) { $hcWord = '<span style="border: solid 1px; color: black; background-color: #E178A1;">&nbsp;'.htmlentities (langtransstr($HeatWords[4])).'&nbsp;</span>'; $hcFound = true;}
	if ($HC > 29 and ! $hcFound) { $hcWord = '<span style="border: solid 1px; color: white; background-color: #CC6633;">&nbsp;'.htmlentities (langtransstr($HeatWords[6])).'&nbsp;</span>'; $hcFound = true;}
} elseif ($WC < 16 ) {
	if ($WC < -18 and ! $hcFound) { $hcWord = '<span style="border: solid 1px; color: black; background-color: #91ACFF;">&nbsp;'.htmlentities (langtransstr($HeatWords[13])).'&nbsp;</span>'; $hcFound = true; }
	if ($WC < -9 and ! $hcFound)  { $hcWord = '<span style="border: solid 1px; color: white; background-color: #806AF9;">&nbsp;'.htmlentities (langtransstr($HeatWords[12])).'&nbsp;</span>'; $hcFound = true; }
	if ($WC < -1 and ! $hcFound)  { $hcWord = '<span style="border: solid 1px; color: white; background-color: #3366FF;">&nbsp;'.htmlentities (langtransstr($HeatWords[11])).'&nbsp;</span>'; $hcFound = true; }
	if ($WC < 8 and ! $hcFound)   { $hcWord = '<span style="border: solid 1px; color: white; background-color: #6699FF;">&nbsp;'.htmlentities (langtransstr($HeatWords[10])).'&nbsp;</span>'; $hcFound = true; }
	if ($WC < 16 and ! $hcFound)  { $hcWord = '<span style="border: solid 1px; color: black; background-color: #89B2EA;">&nbsp;'.htmlentities (langtransstr($HeatWords[9])).'&nbsp;</span>'; $hcFound = true; }
} elseif ($WC >= 16 and $TC <= 32) {
	if ($TC <= 26 and ! $hcFound) { $hcWord = '<span style="border: solid 1px; color: black; background-color: #C6EF8C;">&nbsp;'.htmlentities (langtransstr($HeatWords[8])).'&nbsp;</span>'; $hcFound = true; }
	if ($TC <= 32 and ! $hcFound) { $hcWord = '<span style="border: solid 1px; color: black; background-color: #CC9933;">&nbsp;'.htmlentities (langtransstr($HeatWords[7])).'&nbsp;</span>'; $hcFound = true; }
}

if(isset($_REQUEST['debug'])) {
//  echo "<!-- MH_setFeelslike input T,WC,HI,U='$temp,$windchill,$heatindex,$tempUOM' cnvt T,WC,HI='$TC,$WC,$HC' feelslike=$feelslike hcWord=$hcWord -->\n";
}

return(array($feelslike,$hcWord));
	
} // end of Feelslike
#-------------------------------------------------------------------------------------
# 	returns Barotrend text  like "Rising Slowly"
#-------------------------------------------------------------------------------------
function wsBarotrendText($rawpress,$usedunit='hPa') {
global $Debug;
// routine from Anole's wxsticker PHP (adapted)  
//   Barometric Trend(3 hour)

// Change Rates
// Rapidly: =.06" inHg; 1.5 mm Hg; 2 hPa; 2 mb
// Slowly: =.02" inHg; 0.5 mm Hg; 0.7 hPa; 0.7 mb

// 5 Arrow Positions:
// Rising Rapidly
// Rising Slowly
// Steady
// Falling Slowly
// Falling Rapidly

// Page 52 of the PDF Manual
// http://www.davisnet.com/product_documents/weather/manuals/07395.234-VP2_Manual.pdf

// first convert to hPa for comparisons
	 if (preg_match('/hPa|mb/i',$usedunit)) {
		$btrend = sprintf("%02.1f",round($rawpress * 1.0,1)); // leave in hPa
	 } elseif (preg_match('/mm/i',$usedunit)) {
	   $btrend = sprintf("%02.1f",round($rawpress * 1.333224,1)); 
	 } else {
		$btrend = sprintf("%02.1f",round($rawpress  / 33.86388158,1));
	 }

// figure out a text value for barometric pressure trend
(float)$baromtrend = $btrend;
//   settype($baromtrend, "float");
switch (TRUE) {
case (($baromtrend >= -0.6) and ($baromtrend <= 0.6)):
$baromtrendwords = "Steady";
break;
case (($baromtrend > 0.6) and ($baromtrend < 2.0)):
$baromtrendwords = "Rising Slowly";
break;
case ($baromtrend >= 2.0):
$baromtrendwords = "Rising Rapidly";
break;
case (($baromtrend < -0.6) and ($baromtrend > -2.0)):
$baromtrendwords = "Falling Slowly";
break;
case ($baromtrend <= -2.0):
$baromtrendwords = "Falling Rapidly";
break;
} // end switch
$Debug .= "<!-- MH_get_barotrend_text in=$rawpress $usedunit out=$btrend hPa [$baromtrend] ($baromtrendwords) -->\n";
return($baromtrendwords);
} // eof barotrend text
#-------------------------------------------------------------------------------------
#   converts (and translates) degrees to windlabels f.e. 2 degrees to North
function convertWinddir ($degrees) {
// figure out a text value for compass direction
// Given the wind direction, return the text label
// for that value.  16 point compass
$winddir = $degrees;
if (!isset($winddir)) {
return "---";
}
if (!is_numeric($winddir)) {
	return($winddir);
}
$windlabel = array ("North","NNE", "NE", "ENE", "East", "ESE", "SE", "SSE", "South",
	 "SSW","SW", "WSW", "West", "WNW", "NW", "NNW");
$dir = $windlabel[ fmod((($winddir + 11) / 22.5),16) ];
return($dir);
} // eof convertWinddir
#-------------------------------------------------------------------------------------
#    Convert windspeed
function wsConvertWindspeed($amount, $usedunit,$reqUnit='') {
	global $SITE;
	$amount=str_replace(',','.',$amount);
	$out = 0;	
	if ($reqUnit == '') {$toUnit = $SITE['uomWind'];} else {$toUnit = $reqUnit;}
	$repl = array ('/',' ','p');
	$with = array ('','','');
	$convertArr= array
			   ("kmh"=> array('kmh' => 1		, 'kts' => 0.5399568034557235	, 'ms' => 0.2777777777777778 	, 'mh' => 0.621371192237334 ),
				"kts"=> array('kmh' => 1.852	, 'kts' => 1 					, 'ms' => 0.5144444444444445 	, 'mh' => 1.1507794480235425),
				"ms" => array('kmh' => 3.6		, 'kts' => 1.9438444924406046	, 'ms' => 1 					, 'mh' => 2.236936292054402 ),
				"mh" => array('kmh' => 1.609344	, 'kts' => 0.8689762419006479	, 'ms' => 0.44704 				, 'mh' => 1 ));
	$from = trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   = trim(strtolower(str_replace ($repl,$with,$toUnit)));
	if (($from ==='kmh') || ($from === 'kts') || ($from === 'ms') || ($from === 'mh')) {
		if (($to ==='kmh') || ($to === 'kts') || ($to === 'ms') || ($to === 'mh')) {$out = $convertArr[$from][$to];}  
	}      // invalid unit
	return(round($out*$amount,1));
} // eof convert windspeed
#-------------------------------------------------------------------------------------
#    Convert baro pressure
function wsConvertBaro($amount, $usedunit,$reqUnit='') {
	global $SITE;
	$amount=str_replace(',','.',$amount);
	$out = 0;	
	if ($reqUnit == '') {$toUnit = $SITE['uomBaro'];} else {$toUnit = $reqUnit;}
	$repl = array ('/',' ');
	$with = array ('','');
	$convertArr= array
			   ("mb" 	=> array('mb' => 1		, 'hpa' => 1 , 		'mmhg' => 0.75006 	, 'inhg' => 0.02953 ),
				"hpa"	=> array('mb' => 1		, 'hpa' => 1 , 		'mmhg' => 0.75006 	, 'inhg' => 0.02953),
				"mmhg"	=> array('mb' => 1.3332	, 'hpa' => 1.3332 , 'mmhg' => 1 		, 'inhg' => 0.03937 ),
				"inhg"	=> array('mb' => 33.864	, 'hpa' => 33.864 , 'mmhg' => 25.4 		, 'inhg' => 1));
	$from = trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   = trim(strtolower(str_replace ($repl,$with,$toUnit)));
	if (($from ==='mb') || ($from === 'hpa') || ($from === 'mmhg') || ($from === 'inhg')) {
		if (($to ==='mb') || ($to === 'hpa') || ($to === 'mmhg') || ($to === 'inhg')) {$out = $convertArr[$from][$to];}  
	}      // invalid unit
	if ($toUnit == "hpa" || $toUnit == "mb" ) {return(round($out*(float)$amount,1));}
	return(round($out*(float)$amount,3));
} // eof convert baropressure
#-------------------------------------------------------------------------------------
#    Convert rainfall
function wsConvertRainfall($amount, $usedunit,$reqUnit='') {
	global $SITE;
	$amount=str_replace(',','.',$amount);
	$out = 0;	
	if ($reqUnit == '') {$toUnit = $SITE['uomRain'];} else {$toUnit = $reqUnit;}
	$repl = array ('/',' ');
	$with = array ('','');
	$convertArr= array
			   ("mm"=> array('mm' => 1		,'in' => 0.03937007874015748 	, 'cm' => 0.1 ),
				"in"=> array('mm' => 25.4	,'in' => 1						, 'cm' => 2.54),
				"cm"=> array('mm' => 10		,'in' => 0.3937007874015748 	, 'cm' => 1 )
				);
	$from = trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   = trim(strtolower(str_replace ($repl,$with,$toUnit)));
	if (($from ==='mm') || ($from === 'in') || ($from === 'cm')) {
		if (($to ==='mm') || ($to === 'in') || ($to === 'cm')){$out = $convertArr[$from][$to];}  
	}      // invalid unit
	if ($to == 'mm') {return(round($out*$amount,1));} else {return(round($out*$amount,3));}
} // eof convert rainfall
#-------------------------------------------------------------------------------------
#    Convert temperature and clean up input
function wsConvertTemperature($amount, $usedunit,$reqUnit='') {
	global $SITE;
	if (isset ($amount)) {
		$amount=str_replace(',','.',$amount);
		$out = $amount*1.0;
	}	else {$out=0;}
	if ($reqUnit == '') {$toUnit = $SITE['uomTemp'];} else {$toUnit = $reqUnit;}
	$repl = array ('/',' ','&deg;','elsius');
	$with = array ('','','','');
	$from = trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   = trim(strtolower(str_replace ($repl,$with,$toUnit)));
	if ($from == $to) {return($out);}
	elseif (($from == 'c') && ($to = 'f')) {$out = 32 +(9*$amount/5);}    //
	elseif (($from == 'f') && ($to = 'c')) {$out = 5*($amount -32)/9;}
// invalid unit
	return(round($out,1));
} // eof convert temperature
#-------------------------------------------------------------------------------------
#    Convert distance
function wsConvertDistance($amount, $usedunit,$reqUnit='') {
	global $SITE;
	if (isset ($amount)) {
		$amount=str_replace(',','.',$amount);
		$out = ((int)$amount)*1.0;
	}	else {$out=0;}
	if ($reqUnit == '') {$toUnit = $SITE['uomDistance'];} else {$toUnit = $reqUnit;}
	$repl = array ('/',' ');
	$with = array ('','');
	$from = trim(strtolower(str_replace ($repl,$with,$usedunit)));
	$to   = trim(strtolower(str_replace ($repl,$with,$toUnit)));
	$convertArr= array
			   ("km"	=> array('km' => 1					, 'mi' => 0.621371192237		, 'ft' => 3280.83989501 		, 'm' => 1000 ),
				"mi"	=> array('km' => 1.609344000000865	, 'mi' => 1						, 'ft' => 5280					, 'm' => 1609.344000000865 ),
				"ft"	=> array('km' => 0.0003048			, 'mi' => 0.000189393939394		, 'ft' => 1					 	, 'm' => 0.30480000000029017 ),
				"m"		=> array('km' => 0.001				, 'mi' => 0.000621371192237		, 'ft' => 3.28083989501 		, 'm' => 1 )
				);
	if (($from ==='km') || ($from === 'mi') || ($from === 'ft') || ($from === 'm')) {
		if (($to ==='km') || ($to === 'mi') || ($to === 'ft') || ($to === 'm')) {$out = $convertArr[$from][$to];}  
	}      // invalid unit
	return(round($out*$amount,1));
	return(round($out,1));
} // eof convert distance
#-------------------------------------------------------------------------------------
#    Convert array of meteo  values
function wsConvertArray($kind, $array, $usedunit,$reqUnit) {
	if ($usedunit == $reqUnit) {return($array);}
	foreach ($array as $key => $value) {
		switch ($kind) {
			case 'temp':
		$array[$key] = wsConvertTemperature($value, $usedunit,$reqUnit);
		break;
			case 'wind':
		$array[$key] = wsConvertWindspeed($value, $usedunit,$reqUnit);
		break; 
	case 'rain':
		$array[$key] = wsConvertRainfall($value, $usedunit,$reqUnit);
		break; 
	case 'baro':
		$array[$key] = wsConvertBaro($value, $usedunit,$reqUnit);
		break; 
} // end switch
} // end foreach
return ($array);		
} // eof convert array of meteovalues
#-------------------------------------------------------------------------------------
#	returns Beaufort Number based on windspeed
#----------------------------------------------------------------------not used yet --
function wsBeaufortNumber ($inWind,$usedunit) {
global $Debug;

$rawwind = $inWind;
// first convert all winds to knots
if(strpos($inWind,',') !== false) {
	   $rawwind = preg_replace('|,|','.',$inWind);
}
$wind0kts = 0.0;
if       (preg_match('/kts|knot/i',$usedunit)) {
	$wind0kts = $rawwind * 1.0;
} elseif (preg_match('/mph/i',$usedunit)) {
	$wind0kts = wsConvertWindspeed($inWind, 'mph','kts');		//  $wind0kts = $rawwind * 0.8689762;
} elseif (preg_match('/mps|m\/s/i',$usedunit)) {
	$wind0kts = wsConvertWindspeed($inWind, 'ms','kts');		//  $wind0kts = $rawwind * 1.94384449;
} elseif  (preg_match('/kmh|km\/h/i',$usedunit)) {
	$wind0kts = wsConvertWindspeed($inWind, 'kmh','kts');		//  $wind0kts = $rawwind * 0.539956803;
} else {
	   $Debug .= "<!-- MH_beaufortNumber .. unknown input unit '$usedunit' for wind=$rawwind -->\n";
	   $wind0kts = $rawwind * 1.0;
}
// return a number for the beaufort scale based on wind in knots
if ($wind0kts < 1 ) {return(0); }
if ($wind0kts < 4 ) {return(1); }
if ($wind0kts < 7 ) {return(2); }
if ($wind0kts < 11 ) {return(3); }
if ($wind0kts < 17 ) {return(4); }
if ($wind0kts < 22 ) {return(5); }
if ($wind0kts < 28 ) {return(6); }
if ($wind0kts < 34 ) {return(7); }
if ($wind0kts < 41 ) {return(8); }
if ($wind0kts < 48 ) {return(9); }
if ($wind0kts < 56 ) {return(10); }
if ($wind0kts < 64 ) {return(11); }
if ($wind0kts >= 64 ) {return(12); }
return("0");
} // eof MH_beaufortNumber
#-------------------------------------------------------------------------------------
#	wsBeaufortText returns descriptive text like "Light breeze"
#-------------------------------------------------------------------------------------
function wsBeaufortText ($beaufortnumber) {
	$B = array( /* Beaufort 0 to 12 in English */
	"Calm", "Light air", "Light breeze", "Gentle breeze", "Moderate breeze", "Fresh breeze",
	"Strong breeze", "Near gale", "Gale", "Strong gale", "Storm",
	"Violent storm", "Hurricane"
	);
	
	if(isset($B[$beaufortnumber])) {
		return $B[$beaufortnumber];
	} else {
		return "Unknown $beaufortnumber Bft";
	}
} // end MH_beaufortText
#-------------------------------------------------------------------------------------
#	wsBeaufortColor returns color code for Bft number >= 6
#-------------------------------------------------------------------------------------
function wsBeaufortColor ($beaufortnumber) {
	$color = array( /* Beaufort 0 to 12 in English */
	"transparent", "transparent", "transparent", "transparent", "transparent", "transparent", 
	"#FFFF53", "#F46E07", "#F00008", "#F36A6A", "#6D6F04", "#640071", "#650003"
	);	
	if(isset($color[$beaufortnumber])) {
		return $color[$beaufortnumber];
	} else {
		return "Unknown $beaufortnumber Bft";
	}
} // end wsBeaufortColor
#-------------------------------------------------------------------------------------
#   wsGenDifference     generate an up/down arrow to show differences/trend
#-------------------------------------------------------------------------------------  
function wsGenDifference($nowTemp, $yesterTemp, $unit, $textUP, $textDN, $DP=1) {
global $SITE, $DebugMode, $poort;
$nowTemp		=str_replace(',','.',$nowTemp);
$yesterTemp		=str_replace(',','.',$yesterTemp);
$tnowTemp 		= strip_units($nowTemp);
$tyesterTemp 	= strip_units($yesterTemp);
$diff = round(($tnowTemp - $tyesterTemp),3);
$absDiff = abs($diff);
//  $diffStr = sprintf("%01.".$DP."F",$diff);
$diffStr=number_format($diff,$DP);
//  $absDiffStr = sprintf("%01.".$DP."F",$absDiff);
$absDiffStr=number_format($absDiff,$DP);
if($SITE['commaDecimal']) {
	 $absDiffStr = preg_replace('|\.|',',',$absDiffStr);
}
if($DebugMode) {
	  echo "<!-- gen_difference DP=$DP now='$nowTemp':'$tnowTemp' yest='$yesterTemp':'$tyesterTemp' dif='$diff':'$diffStr' absDiff='$absDiff':'$absDiffStr' -->\n";
	  echo "<!-- txtUP='$textUP' txtDN='$textDN' Unit='$unit' -->\n";
}
if ($diffStr == '0.0') {
// no change

$image = '&nbsp;'; 

} elseif ($diffStr > '0.0') {
// today is greater 
$msg = sprintf($textUP,$absDiffStr); 
$image = "<img src=\"".$SITE['imgAjaxDir']."rising.gif\" alt=\"$msg\" title=\"$msg\" width=\"14\" height=\"16\" style=\"border: 0; margin: 1px 3px;\" />";


} else {
// today is lesser
$msg = sprintf($textDN,$absDiffStr); 
$image = "<img src=\"".$SITE['imgAjaxDir']."falling.gif\" alt=\"$msg\" title=\"$msg\" width=\"14\" height=\"16\" style=\"border: 0; margin: 1px 3px;\" />";

}
if ($unit) {
return ($nowTemp . $unit . $image);
	} else {
	   return $image;
	}
} // eof   
#-------------------------------------------------------------------------------------  
# 	wsgetUVrange     returns description for uv value and a corresponding color 
#-------------------------------------------------------------------------------------  
function wsgetUVrange ( $inUV ) {
if(strpos($inUV,',') !== false ) {   // change a ',' in input value to a point
	   $uv = preg_replace('|,|','.',$inUV);
} else {
	   $uv = $inUV;
}
$uv=$uv*1.0;
switch (TRUE) {
case ($uv == 0):
$uv = langtransstr('None');
break;
case (($uv > 0) and ($uv < 3)):
$uv = '<span style="border: solid 1px; color: black; background-color: #A4CE6a;">&nbsp;' . langtransstr('Low') . '&nbsp;</span>';
break;
case (($uv >= 3) and ($uv < 6)):
$uv = '<span style="border: solid 1px; color: black; background-color: #FBEE09;">&nbsp;' . langtransstr('Medium') . '&nbsp;</span>';
break;
case (($uv >=6 ) and ($uv < 8)):
$uv = '<span style="border: solid 1px; color: black; background-color: #FD9125;">&nbsp;' . langtransstr('High') . '&nbsp;</span>';
break;
case (($uv >=8 ) and ($uv < 11)):
$uv = '<span style="border: solid 1px; color: #FFFFFF; background-color: #F63F37;">&nbsp;' . langtransstr('Very&nbsp;High') . '&nbsp;</span>';
break;
case (($uv >= 11) ):
$uv = '<span style="border: solid 1px; color: #FFFF00; background-color: #807780;">&nbsp;' . langtransstr('Extreme') . '&nbsp;</span>';
break;
} // end switch
return $uv;
} // end get_UVrange
#-------------------------------------------------------------------------------------  
# 	wsgetUVword     returns description for uv value 
#-------------------------------------------------------------------------------------  
function wsgetUVword ( $inUV ) {
if(strpos($inUV,',') !== false ) {   // change a ',' in input value to a point
	   $uv = preg_replace('|,|','.',$inUV);
} else {
	   $uv = $inUV;
}
$uv=$uv*1.0;
switch (TRUE) {
case ($uv == 0):
$uv = langtransstr('None');
break;
case (($uv > 0) and ($uv < 3)):
$uv = '<span style:"color: #A4CE6a;">'.langtransstr('Low').'</span>';
break;
case (($uv >= 3) and ($uv < 6)):
$uv = '<span style="color: #FBEE09;">'.langtransstr('Medium').'</span>';
break;
case (($uv >=6 ) and ($uv < 8)):
$uv = '<span style="color: #FD9125;">'.langtransstr('High').'</span>';
break;
case (($uv >=8 ) and ($uv < 11)):
$uv = '<span style="color:#F63F37;">' . langtransstr('Very&nbsp;High').'</span>';
break;
case (($uv >= 11) ):
$uv = '<span style="color: #807780;">'.langtransstr('Extreme').'</span>';
break;
} // end switch
return $uv;
} // end get_UVrange
#------------------------------------------------------------------------------------- 
# returns formatted date or time string same as php date function
# but is used to have an intermidiate between weatherprogram dates and website dates
#------------------------------------------------------------------------------------- 
function string_date ($input, $text) {
	global $SITE;
//	if ($SITE['WXsoftware'] == 'MH') {
		$date = strtotime(substr($input,0,8).'T'.substr($input,8,6));
		$string = date($text,$date);
		return $string;
//	}
//	return 'unknown';
}
# -----------------------------------------------------------------------------
#
# -----------------------------------------------------------------------------
function get_utcdate ($unixTime ) {
	return(gmmktime(date('H', $unixTime), date('i', $unixTime), '0', date('n', $unixTime), date('j', $unixTime), date('Y', $unixTime)));	
} // eof get_utcdate
# -----------------------------------------------------------------------------
#
# -----------------------------------------------------------------------------
function wsTimeOnlyToText ( $unixTime ) {
global $timeOnlyFormat;
	$string=date($timeOnlyFormat, $unixTime);

return ($string);
}  // eof wsTimeOnlyToText
# -----------------------------------------------------------------------------
#
# -----------------------------------------------------------------------------
function wsDateOnlyToText ( $unixTime ) {
global $dateOnlyFormat;
	$string=date($dateOnlyFormat, $unixTime);
return ($string);
} // eof wsDateOnlyToText
# -----------------------------------------------------------------------------
#
# -----------------------------------------------------------------------------
function wsDateToText ( $unixTime ) {
global $timeFormat;
	$string=date($timeFormat, $unixTime);
return ($string);
} // eof wsDateToTextﬂ
#------------------------------------------------------------------------------------- 
# fetch file without hanging
#------------------------------------------------------------------------------------- 
function fetchUrlWithoutHanging($url) {
global $Status;
// Set maximum number of seconds (can have floating-point) to wait for feed before displaying page without feed
$numberOfSeconds=4;    
// Suppress error reporting so Web site visitors are unaware if the feed fails
//   error_reporting(0);
// Extract resource path and domain from URL ready for fsockopen
$url = str_replace("http://","",$url);
$urlComponents = explode("/",$url);
$domain = $urlComponents[0];
$resourcePath = str_replace($domain,"",$url);
$xml = false;
// Establish a connection
$socketConnection = fsockopen($domain, 80, $errno, $errstr, $numberOfSeconds);
if (!$socketConnection)
{
// You may wish to remove the following debugging line on a live Web site
$Status .= "<!-- Network error: $errstr ($errno) -->\n";
}    // end if
else    {
$xml = '';
fputs($socketConnection, "GET $resourcePath HTTP/1.1\r\nHost: $domain\r\nConnection: Close\r\nCookie: Units=metric;\r\n\r\n");   
// Loop until end of file
while (!feof($socketConnection))
{
$xml .= fgets($socketConnection, 15000);
}    // end while
fclose ($socketConnection);
}    // end else
return($xml);
}    // end function
#-------------------------------------------------------------------------------------  
#
#-------------------------------------------------------------------------------------
function fetchEUAUrlWithoutHanging($url) {
   // Set maximum number of seconds (can have floating-point) to wait for feed before displaying page without feed
   $numberOfSeconds=4;   

   // Suppress error reporting so Web site visitors are unaware if the feed fails
   error_reporting(0);

   // Extract resource path and domain from URL ready for fsockopen

   $url = str_replace("http://","",$url);
   $urlComponents = explode("/",$url);
   $domain = $urlComponents[0];
   $resourcePath = str_replace($domain,"",$url);

   // Establish a connection
   $socketConnection = fsockopen($domain, 80, $errno, $errstr, $numberOfSeconds);

   if (!$socketConnection)
       {
       // You may wish to remove the following debugging line on a live Web site
        print("<!-- Network error: $errstr ($errno) -->\n");
       }    // end if
   else    {
       $xml = '';
       fputs($socketConnection, "GET $resourcePath HTTP/1.1\r\nHost: $domain\r\nConnection: Close\r\n\r\n");
   
       // Loop until end of file
       while (!feof($socketConnection))
           {
           $xml .= fgets($socketConnection, 4096);
           }    // end while

       fclose ($socketConnection);

       }    // end else
	  

   return($xml);

   }    // end function
#------------------------------------------------------------------------------------- 
# clean headers etc from file loaded by url or from cache
#------------------------------------------------------------------------------------- 
function cleanFile($string,$kind){
	echo "<!-- clean file $kind -->\n";
	if ($kind== 'xml'){
		$f1 = '<'; $f2 = '>';
	} elseif ($kind== 'json') {
		$f1 = '{'; $f2 = '}';
	} else {return($string);}
	$i = strpos($string,$f1);			// search for first { or <
	if ($i === false) {echo "not found $f1"; return($string);}
	$ir = strrpos($string, $f2);		// search for last } or >
	echo "<!-- cleaned file from pos ".$i.' -- '.$ir."  ()-->\n";
	$result= substr($string,$i ,$ir - $i + 1);
	return($result);
}
#------------------------------------------------------------------------------------- 
# get file by url or from cache
#------------------------------------------------------------------------------------- 
function getWeatherFile($file,$refetchSeconds,$url,$kind,$useFopen) {
	global $SITE;
	$fileLoad=$useFopen;
	$string=false;
	echo "<!-- try loading data $file -->\n";	
	if ((file_exists($file)) && (filemtime($file) + $refetchSeconds >= time())){
		echo "<!-- loading data  from cache $file -->\n";
		$string = file_get_contents($file); 	
	} else {
		echo "<!-- file $file is outdated, loading from $url -->\n";
		if (!$fileLoad) {
			echo "<!-- loading from $url  with fsock-->\n";
			$string = fetchUrlWithoutHanging($url);  								// get file from url
			if ($string == false) {
				echo "<!-- error occured while loading from $url with fsock, use filesystem instead -->\n";
				$fileLoad=true;
			} // errors
		}  // normal load fsock
		if ($fileLoad) {
			echo "<!-- loading from $url  with file_get_contents -->\n";
			$string = file_get_contents($url);
		} 	
		if ($string == false) {
			echo "<!--- errors while loading load from $url";  
			}					
		else { 
			echo "<!-- cached file to  $file -->\n";
			$string=cleanFile($string,$kind);
			$ret = file_put_contents ($file, $string, LOCK_EX);
			}				// put file away
	} 							
	return($string);
} //eof get weather file
#-------------------------------------------------------------------------------------  
#
#-------------------------------------------------------------------------------------  
function wsmoonWord ($LunarPhasePerc , $LunarAge) {
$mdaysd =  $LunarAge *1.0;
$mpct = $LunarPhasePerc * 1.0;
if ($mdaysd <= 29.53/2) { // increasing illumination
$ph = "Waxing";
	$qtr = "First";
} 
else 
{ // decreasing illumination
$ph = "Waning";
	$qtr = "Last";
}

if ($mpct < 1 ) { return("New Moon"); }  //$LunarPhasePerc
if ($mpct <= 49) { return("$ph Crescent"); }
if ($mpct < 51) { return("$qtr Quarter"); }
if ($mpct < 99) { return("$ph Gibbous"); }
	return("Full Moon");
} // eof wsmoonWord
#------------------------------------------------------------------------------------- 
# strip trailing units from a measurement  i.e. '30.01 in. Hg' becomes '30.01'
#------------------------------------------------------------------------------------- 
function strip_units ($data) {
preg_match('/([\d\,\.\+\-]+)/',$data,$t);
return $t[1];
}  // eof strip units
#-------------------------------------------------------------------------------------  
#
#-------------------------------------------------------------------------------------
function gen_uv_icon($inUV) {
	global $SITE, $img;
	if($inUV == 'n/a') { return( ''); }
	$uv = preg_replace('|,|','.',$inUV);
	$ourUVrounded = round($uv,0);
	if ($ourUVrounded > 11) {$ourUVrounded = 11; }
	if ($ourUVrounded < 1 ) {$ourUVrounded = 1; }
	$ourUVicon = "uv" . sprintf("%02d",$ourUVrounded) . ".gif";
	
	return( "<img src=\"$img$ourUVicon\" height=\"76\" width=\"40\"  alt=\"UV Index\" title=\"UV Index\" />");
}
#-------------------------------------------------------------------------------------
#  decode UV to word+color for display
#-------------------------------------------------------------------------------------
function get_uv_word ( $inUV ) {
	global $SITE;
// figure out a text value and color for UV exposure text
//  0 to 2  Low
//  3 to 5     Moderate
//  6 to 7     High
//  8 to 10 Very High
//  11+     Extreme
   $uv = preg_replace('|,|','.',$inUV);
   switch (TRUE) {
	 case ($uv == 'n/a'):
	   $uv = '';
	 break;
     case ($uv == 0):
       $uv = langtransstr('None');
     break;
     case (($uv > 0) and ($uv < 3)):
       $uv = '<span style="border: solid 1px; background-color: #A4CE6a;">&nbsp;'.langtransstr('Low').'&nbsp;</span>';
     break;
     case (($uv >= 3) and ($uv < 6)):
       $uv = '<span style="border: solid 1px;background-color: #FBEE09;">&nbsp;'.langtransstr('Medium').'&nbsp;</span>';
     break;
     case (($uv >=6 ) and ($uv < 8)):
       $uv = '<span style="border: solid 1px; background-color: #FD9125;">&nbsp;'.langtransstr('High').'&nbsp;</span>';
     break;
     case (($uv >=8 ) and ($uv < 11)):
       $uv = '<span style="border: solid 1px; color: #FFFFFF; background-color: #F63F37;">&nbsp;'.langtransstr('Very&nbsp;High').'&nbsp;</span>';
     break;
     case (($uv >= 11) ):
       $uv = '<span style="border: solid 1px; color: #FFFF00; background-color: #807780;">&nbsp;'.langtransstr('Extreme').'&nbsp;</span>';
     break;
   } // end switch
   return $uv;
} // end getUVword
#-------------------------------------------------------------------------------------
#  weather display fdate conversion
#-------------------------------------------------------------------------------------
function wdDate($time){    // for todays time stamps: remove ':' in time and combine to YYYYMMDDHHMMSS
	global $ymd;
	$int = strtotime($time);
	return ($ymd.strftime('%H%M%S',$int) );
}
#-------------------------------------------------------------------------------------
#  weather display fdate conversion inl ymd
#-------------------------------------------------------------------------------------
function wdYMD($year,$month,$day,$hour='12',$minute='00',$seconds='00') {  // month and day can be 1 char long!
	$string= $year.substr('00'.$month,-2).substr('00'.$day,-2).substr('00'.$hour,-2).substr('00'.$minute,-2).substr('00'.$seconds,-2);
	return ($string);
}
?>