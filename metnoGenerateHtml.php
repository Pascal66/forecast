<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName='metnoGenerateHtml.php';
$pageVersion	= '2.10 2013-01-31';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#-------------------------------------------------------------------------------
#	(2013-03-08)	updated color temp / C and F /html5
#	(2013-03-26)	updated color temp / C and F for highcharts 3.0
#
# version 2.01 2013-01-31  version release 2
#
#-------------------------------------------------------------------------------
// temparray 2 starts at -32C, so add 32 to C temp
$tempArray2=array(
'#F6AAB1', '#F6A7B6', '#F6A5BB', '#F6A2C1', '#F6A0C7', '#F79ECD', '#F79BD4', '#F799DB', '#F796E2', '#F794EA',
'#F792F3', '#F38FF7', '#EA8DF7', '#E08AF8', '#D688F8', '#CC86F8', '#C183F8', '#B681F8', '#AA7EF8', '#9E7CF8',
'#9179F8', '#8477F9', '#7775F9', '#727BF9', '#7085F9', '#6D8FF9', '#6B99F9', '#68A4F9', '#66AFF9', '#64BBFA',
'#61C7FA', '#5FD3FA', '#5CE0FA', '#5AEEFA', '#57FAF9', '#55FAEB', '#52FADC', '#50FBCD', '#4DFBBE', '#4BFBAE',
'#48FB9E', '#46FB8D', '#43FB7C', '#41FB6A', '#3EFB58', '#3CFC46', '#40FC39', '#4FFC37', '#5DFC35', '#6DFC32',
'#7DFC30', '#8DFC2D', '#9DFC2A', '#AEFD28', '#C0FD25', '#D2FD23', '#E4FD20', '#F7FD1E', '#FDF01B', '#FDDC19',
'#FDC816', '#FDC816', '#FEB414', '#FEB414', '#FE9F11', '#FE9F11', '#FE890F', '#FE890F', '#FE730C', '#FE730C',
'#FE5D0A', '#FE5D0A', '#FE4607', '#FE4607', '#FE2F05', '#FE2F05', '#FE1802', '#FE1802', '#FF0000', '#FF0000',
);
$timezone 		= $SITE['tz'];
$lat 			= $SITE['latitude'];
$long			= $SITE['longitude'];
$dateTimeFormat = $SITE['timeFormat'];
$timeFormat 	= $SITE['timeOnlyFormat'];
$dateFormat 	= $SITE['dateOnlyFormat'];
$dateLongFormat = isset($SITE['dateLongFormat'])? $SITE['dateLongFormat'] : 'l d F Y';
$utcDiff 		= date('Z');// used for graphs timestamps
$srise			= 8;
$sset			= 20;
$dayParts		= array ( langtransstr('evening'), langtransstr('night'), langtransstr('morning'), langtransstr('afternoon'), );
$from 			= array ('&deg;',' ','/');
$uomTemp 		= str_replace ($from,'',$SITE['uomTemp']);
$tempSimple		= false;
if (isset ($SITE['tempSimple']) ) {
	$tempSimple = $SITE['tempSimple'];
}
#echo '<pre>'; print_r($returnArray['forecast']);
# informative text with update times and name of forecast area
# --------------
# text for top of page time/date of updates
$fileTime		= strtotime($returnArray['dates']['filetime']);
$nextUpdate 	= strtotime($returnArray['dates']['nextUpdate']);

$wsUpdateTimes = '
		<div style="text-align: left; margin:  0px 10px 10px 10px;">
		<span style="float:right;text-align:right;">';
$wsUpdateTimes .= 	langtransstr('Updated').': '.myLongDate ($fileTime).' - '.date ($timeFormat,$fileTime).'<br />';
$wsUpdateTimes .= 	langtransstr('Next update').': '.myLongDate ($nextUpdate).' - '.date ($timeFormat,$nextUpdate).'
		</span>
		<h4 style="margin: 0px;">'.langtransstr('MetNoForecast.').' '.langtransstr($SITE['yourArea']).'
				<br />'.langtransstr('by: ').$SITE['organ'].'</h4>';
$wsUpdateTimes .= '</div>';
#
# we loop through all data and build arrays for the coloms of the output.
$foundFirst	= '';
$arrTime 	= array ();		$arrTimeGraph 	= array ();
$arrDay 	= array ();
$arrIcon	= array ();		$arrIconGraph	= array ();
$arrDesc	= array ();
$arrTemp	= array ();		$arrTempGraph	= array ();
$arrRain	= array ();		$arrRainGraph	= array ();
$arrCoR		= array ();
$arrCoT		= array ();
$arrCoS		= array ();
$arrWind	= array ();		$arrWindGraph	= array ();
$arrWdir	= array ();		$arrWdirGraph	= array ();
$arrWindIcon= array ();
$arrBaro	= array ();		$arrBaroGraph	= array ();
$graphsDays = array ();
$oldDay		= '';		// to detect day cahnges in input
$graphsData	= '';		// we store all javascript data here
$graphLines = 0;		// number of javascript data lines
$graphsStop = 0;
$graphsStart= 0;
$graphTempMin = 100;
$graphTempMax = -100;
$graphBaroMin = 2000;
$graphBaroMax = 0;
$graphRainMax = 0;
$graphWindMax = 0;
#
$rowColor			= $rowColorDtl = 'row-dark';
$firstTime			= true;
$metnoListTable   	= '';
$metnoListTable    .= '<table class="genericTable" style="width: 100%;"><tbody>'.PHP_EOL;
$metnoListHead		= '
		<tr class="table-top">
		<td>'.langtransstr('Period').'</td><td colspan="2">'.langtransstr('Forecast').'</td>
				<td>'.langtransstr('Temperature').'</td><td>'.langtransstr('Precipitation').'</td>
						<td>'.langtransstr('Wind').'</td><td>'.langtransstr('Humidity').'</td><td>'.langtransstr('Pressure').'</td>
								</tr>'.PHP_EOL;
$metnoDetailTable  = '<table class="genericTable" style="width: 100%;"><tbody>'.PHP_EOL;
$metnoDetailHead		= '
		<tr class="table-top">
		<td>'.langtransstr('Period').'</td><td colspan="2">'.langtransstr('Forecast').'</td>
				<td>'.langtransstr('Temperature').'</td><td>'.langtransstr('Precipitation').'</td>
						<td colspan="2">'.langtransstr('Wind').'</td><td>'.langtransstr('Humidity').'</td><td>'.langtransstr('Cloud cover').'</td><td>'.langtransstr('Pressure').'</td>
								</tr>'.PHP_EOL;
$metnoDetailTableColumns = 10;
#
$now 		= time();
$oldDay  	= $oldDayDtl = '';
$dataLine 	= $firstTime = true;
#echo '<pre>'.PHP_EOL; print_r ($returnArray); echo '</pre>'.PHP_EOL;
for ($i =1; $i < count($returnArray['forecast']); $i++) {
	$arr 			= $returnArray['forecast'][$i];
	$arrDateTo 		= substr( $arr['dateTo'],0,10);
	if ($now > $arr['timestamp']) {
		continue;
	}	// skip all lines in the past;
	#
	$strDay	= date('Y-m-d',$arr['timestamp']);
	if ($oldDay <> $strDay) {		// do we have a new day
		$oldDay  		= $strDay;
		$graphsDays[]	= 1000 * strtotime($strDay.'T00:00:00Z');
		$dataLine		= true;
	} //
	if ($arr['timeFrame'] <> 6) {  // detail table
		if ($strDay <> $oldDayDtl)	{
			$tableColoms	= $metnoDetailTableColumns;
			$metnoDetailTable  .= myDateLinePrint($arr['timestamp'], $rowColorDtl);
			$metnoDetailTable  .= $metnoDetailHead;
			$oldDayDtl		= $strDay;
		}
		$metnoDetailTable	.= '<tr class="'.$rowColorDtl.'">'.PHP_EOL;
		if ($rowColorDtl == 'row-dark') {
			$rowColorDtl = 'row-light';
		} else {$rowColorDtl =  'row-dark';
		}
		$to		= date($SITE['hourOnlyFormat'],$arr['timestamp']);
		$from	= date($SITE['hourOnlyFormat'],$arr['timeFrom']);
		if ($to <> $from) {
			$to = $from.'-'.$to;
		}
		$rain = '';
		if (isset ($arr['rainTxtNUDtl']) && $arr['rainTxtNUDtl'] <> 0) {
			$rain = $arr['rainTxtDtl'];
		}
		$temp 		= $arr['tempNU'];
		$tempString	= myCommonTemperature($temp);
		$windSpeed	= $arr['windSpeedNU'];
		$value		= wsBeaufortNumber ($windSpeed,$SITE['uomWind']);
		$color		= wsBeaufortColor ($value);
		$tekst		= langtransstr(wsBeaufortText ($value) );

		$windIconTxt= '<img style="height: 32px;    width: 32px;" src="'. $windIcons. $arr['windDirTxt']. '.png" alt=""/>'.'<br />'.$arr['windSpeedNU'].'&nbsp;'.trim($SITE['uomWind']);
		$windText	='<span style="background-color: '.$color.';">'.$tekst.'</span>';
		$wind		= $windText.'<br />'.langtransstr ('from the').' '.langtransstr($arr['windDirTxt']);
		$humidity	= $arr['hum'].'%';
		if ($arr['timestamp'] < $srise || $arr['timestamp'] > $sset) {
			$imgstr='n';
		}  else {$imgstr='d';
		}
		if (strlen($arr['iconDtl']) == 1) {
			$icon ='0'.$arr['iconDtl'].$imgstr;
		} else {$icon = $arr['iconDtl'].$imgstr;
		}
		$notUsed 	= $iconUrl = $iconOut = $iconUrlOut = '';
		wsChangeIcon ('yrno',$icon, $iconOut,$notUsed, $iconUrlOut, $notUsed);
		$description= langtransstr($arr['weatherDescDtl']);
		$icon = '<img src="'.$iconUrlOut.'" style="width:40px;" alt =" " title="'.$description.'"/>';
		$metnoDetailTable	.= 	'<td>'.$to.'</td><td>'.$description.'</td>
				<td>'.$icon.'</td><td>'.$tempString.'</td>
						<td>'.$rain.'</td><td>'.$windIconTxt.'</td><td>'.$wind.'</td><td>'.$humidity.'</td><td>'.floor($arr['clouds']).'</td><td>'.$arr['baro'].'</td></tr>'.PHP_EOL;
	}
	if (!isset ($arr['dayPart']) ) {
		continue;
	}
	#	translate icon
	#	day or night
	if ($arr['dayPart'] <= 1 )  {
		$imgstr='n';
	}  else {$imgstr='d';
	}			// ????
	if (strlen($arr['icon']) == 1) {
		$arr['icon']='0'.$arr['icon'].$imgstr;
	} else {$arr['icon']=$arr['icon'].$imgstr;
	}
	# first the javascript graph
	# time
	$arrTimeGraph[$graphLines]	= $arr['timestamp']+$utcDiff;
	# icon
	$notUsed  = $iconOut = $iconUrlOut = '';
	wsChangeIcon ('yrno',$arr['icon'], $iconOut,$notUsed, $iconUrlOut, $notUsed);
	$string						= str_replace ('/'.$iconOut,'_small/'.$iconOut,$iconUrlOut);
	$string						= str_replace ('"','',$string);
	$string						= str_replace ('png width=32px','png',$string);

	$arrIconGraph[$graphLines]	= $string;
	# rain
	if (!isset ($arr['rain']) ) {
		$value = '-';
	} else {
		$value = $arr['rain'];
		if ($value > $graphRainMax) {
			$graphRainMax = $value;
		}
	}
	$arrRainGraph[$graphLines] = $value;
	# baro
	$value 			= $arr['baroNU'];
	if ($value > $graphBaroMax) {
		$graphBaroMax = $value;
	}
	if ($value < $graphBaroMin) {
		$graphBaroMin = $value;
	}
	$arrBaroGraph[$graphLines]	= $value;
	# temp
	$value 			= $arr['tempNU'];
	if ($value > $graphTempMax) {
		$graphTempMax = $value;
	}
	if ($value < $graphTempMin) {
		$graphTempMin = $value;
	}
	$arrTempGraph[$graphLines]	= $value;
	# wind
	$value 			= $arr['windSpeedNU'];
	if ($value > $graphWindMax) {
		$graphWindMax = $value;
	}
	$arrWindGraph[$graphLines]	= $value;
	$arrWdirGraph[$graphLines]	= $arr['windDirTxt'];
	# store all javascript data
	$graphsData	.= 	'tsv['.$graphLines.'] ="'.
			$arrTimeGraph[$graphLines].'|'.
					$arrTempGraph[$graphLines].'|'.
							$arrBaroGraph[$graphLines].'|'.
									$arrWindGraph[$graphLines].'|'.
											$arrWdirGraph[$graphLines].'|'.
													($arrTimeGraph[$graphLines]).'|'.$arrRainGraph[$graphLines].'|'.
															($arrTimeGraph[$graphLines]).'|'.$arrIconGraph[$graphLines].'|";'.PHP_EOL;
															$graphLines++;
															#
															# now the yrno list table
															if ($dataLine == true) {
																$tableColoms = 8;
																$metnoListTable  .= myDateLinePrint($arr['timestamp'], $rowColor);
																$metnoListTable  .= $metnoListHead;
																$dataLine = false;
															}
															$metnoListTable  .='<tr class="'.$rowColor.'">'.PHP_EOL;
															if ($rowColor == 'row-dark') {
																$rowColor = 'row-light';
															} else {$rowColor =  'row-dark';
															}
															$to		= date($SITE['hourOnlyFormat'],$arr['timestamp']);
															$start  = date($SITE['hourOnlyFormat'],($arr['timestamp'] - 6* 60 *60));
															$period = $start .' - '. $to;
															$rain = '';
															if (isset ($arr['rain']) && $arr['rain'] <> 0) {
																$rain = $arr['rainTxt'];
															}
															$temp 		= $arr['tempNU'];
															$tempString = myCommonTemperature($temp);
															$windSpeed	= $arr['windSpeedNU'];
															$value		= wsBeaufortNumber ($windSpeed,$SITE['uomWind']);
															$color		= wsBeaufortColor ($value);
															$tekst		= langtransstr(wsBeaufortText ($value) );
															$windText	='<span style="background-color: '.$color.';">'.$arr['windSpeed'].' - '.$tekst.'</span>';
															$wind		= $windText.'<br />'.langtransstr ('from the').' '.langtransstr($arr['windDirTxt']);
															$humidity	= $arr['hum'].'%';
															$notUsed 	= $iconUrl = $iconOut = $iconUrlOut = '';
															wsChangeIcon ('yrno',$arr['icon'], $iconOut,$notUsed, $iconUrlOut, $notUsed);
															$description= langtransstr($arr['weatherDesc']);
															$icon = '<img src="'.$iconUrlOut.'" alt =" " style="width:40px;" title="'.$description.'"/>';
															$metnoListTable  .='<td>'.$period.'</td><td>'.$description.'</td>
																	<td>'.$icon.'</td><td>'.$tempString.'</td>
																			<td>'.$rain.'</td><td>'.$wind.'</td><td>'.$humidity.'</td><td>'.$arr['baro'].'</td></tr>'.PHP_EOL;
															#
															$arrTime[]	= $arr['timestamp'];
															$dayText 	= langtransstr( date('l', ($arr['timestamp']-3*60*60) ) );
															$dayText2	= $dayParts[$arr['dayPart']];
															if ($foundFirst === '') { 			// do first time things
																$foundFirst = 'xx';
																$dayString 	= langtransstr('this').' '.$dayText2;
																$arrDay[]	= $dayString;
															} else {
																$arrDay[]	= $dayText.' '.$dayText2;
															}
															$notUsed 	= $iconUrl = $iconOut = $iconUrlOut = '';
															wsChangeIcon ('yrno',$arr['icon'], $iconOut,$notUsed, $iconUrlOut, $notUsed);
															$arrIcon[]		= $iconUrlOut;
															$arrDesc[]		= langtransstr($arr['weatherDesc']);
															$arrTemp[]		= $arr['tempNU'];
															$arrRain[]		= $arr['rainTxtNU'];
															$arrWind[]		= $arr['windSpeed'];
															#	$arrWdir[]		= $arr['windDir'];
															$arrWindIcon[]	= $arr['windDirTxt'];
															$arrBaro[]		= $arr['baroNU'];
															#
															$DateLineString = '';
}

$metnoListTable  .= '</tbody></table>'.PHP_EOL;
$metnoDetailTable  .= '</tbody></table>'.PHP_EOL;
#echo $metnoDetailTable; exit;
#
if (count($arrTime) < $topCount) {
	$end	= count($arrTime);
} else {$end	= $topCount;
}
$topCount 	= $end;
$iconWidth	= 100 / $topCount;
$tableIcons  ='
		<!-- start icon output -->
		<table class=" genericTable" style=" background-color: transparent;">
		<tbody>
		<tr>'.PHP_EOL;

for ($i = 0; $i < $end; $i++) {
	$tableIcons  .=  '<td style="width='.$iconWidth.'%;">'.$arrDay[$i].'</td>'.PHP_EOL;
}
$tableIcons  .= '</tr>
		<tr>'.PHP_EOL;
for ($i = 0; $i < $end; $i++) {
	$icon = '<img src="'.$arrIcon[$i].'" alt ="" width ="40" title="'.$arrDesc[$i].'"/>';
	$tableIcons  .=  '<td style="width='.$iconWidth.'%;">'.$icon.'<br />'.$arrDesc[$i].'</td>'.PHP_EOL;
}
$tableIcons  .= '</tr>
		<tr>'.PHP_EOL;
for ($i = 0; $i < $end; $i++) {
	$temp 		= $arrTemp[$i];
	$tempString = myCommonTemperature($temp);
	$tableIcons  .=  '<td>'.$tempString.'</td>'.PHP_EOL;
}
$tableIcons  .= '</tr>
		<tr>'.PHP_EOL;
for ($i = 0; $i < $end; $i++) {
	if ($arrRain[$i] == 0) {
		$rain = '-';
	} else {$rain = $arrRain[$i].$SITE['uomRain'];
	}
	$tableIcons  .=  '<td>'.$rain.'</td>'.PHP_EOL;
}
$tableIcons  .= '</tr>
		<tr>'.PHP_EOL;
for ($i = 0; $i < $end; $i++) {
	$stringWind = '<img src="'.$windIcons.$arrWindIcon[$i].'.png" style="width: 32px;" alt="" /><br />'.$arrWind[$i];
	$tableIcons  .=  '<td>'.$stringWind.'</td>'.PHP_EOL;
}
$tableIcons  .= '</tr>
		</tbody></table>
		<!-- end icon ouptput -->
		'.PHP_EOL;
# now we are going to generate the javascript graphs
# calculate Y axis steps for graphs
$graphNrLines	= 6;
$graphTempMin	= $tempMin = floor ($graphTempMin);  // round down
$graphTempMax	= ceil 	($graphTempMax);  // round up
$stringY = '<!-- temp max: '.$graphTempMax. ' temp min: '.$graphTempMin;
$graphTempStep	= 2* ceil(($graphTempMax - $graphTempMin) / $graphNrLines);
$stringY .= ' temp step: '.$graphTempStep;
/*
 if ($graphTempMin < 0) {
$result = abs($graphTempMin) / $graphTempStep;
$result = ceil ($result);
$graphTempMin = -1 * $result * $graphTempStep;
} else {
$result = floor ($graphTempMin / $graphTempStep );
$graphTempMin = $result * $graphTempStep;
}
*/
$graphTempMax	= $graphTempStep * ceil($graphTempMax/$graphTempStep);
$tempMax		= $graphTempMax;
$tempMin		= $tempMin - $graphTempStep;
$graphTempMax	= $graphTempMax	+  $graphTempStep;
$graphTempMin   = $graphTempMax - (1+ $graphNrLines) * $graphTempStep;

$stringY .= '  temp max: '.$graphTempMax.' temp min: '.$graphTempMin;

$graphIconYvalue = $graphTempMax - ($graphTempStep/2);
#$graphIconYvalue = $graphTempMax;

$stringY .= ' icon: '.$graphIconYvalue. ' -->'.PHP_EOL;
#
$rainMax		=  $graphRainMax;
if (preg_match("|mm|",$SITE['uomRain'])) {
	if ($graphRainMax < 3.5) {
		$graphRainMax = 3.5;
	}
	$graphRainStep	= round (($graphRainMax / $graphNrLines),0);
	$graphRainMax	=  $graphRainStep * $graphNrLines;
} else {
	if ($graphRainMax < 1.3) {
		$graphRainMax = 14;
	} else {$graphRainMax = 10 * $graphRainMax;
	}
	$graphRainStep	= (ceil ($graphRainMax / $graphNrLines))/ 10;
	$graphRainMax	= $graphRainStep * $graphNrLines;
}

$graphRainMax	= $graphRainMax	* 2;
$graphRainStep	= $graphRainStep * 2;
$rainMax		= $rainMax + $graphRainStep;
$stringY .= '<!-- rain max: '.$graphRainMax.'   rain step: '.$graphRainStep.' -->'.PHP_EOL;
$baroMax		= $graphBaroMax;
$baroMin		= $graphBaroMin;
if (preg_match("|hPa|",$SITE['uomBaro'])  || preg_match("|mb|",$SITE['uomBaro'])) {
	$graphBaroDiff = $graphBaroMax - $graphBaroMin;
	if (ceil($graphBaroDiff / 15) <= $graphNrLines) {
		$graphBaroStep = 15;
	} else {$graphBaroStep = 20;
	}
	$graphBaroMax  = $graphBaroStep * (ceil($graphBaroMax / $graphBaroStep));
	if ($graphBaroMax < 1035) {
		$graphBaroMax = 1035;
	}
	$graphBaroMin = $graphBaroMax - $graphNrLines * $graphBaroStep;
} else {  // inHg
	$graphBaroMax = 32; $graphBaroMin = 28.5; $graphBaroStep = .5;
}
$baroMax		= $baroMax + $graphBaroStep;
$baroMin		= $baroMin - $graphBaroStep;
$stringY .='<!-- baro max: '.$graphBaroMax.' baro min: '.$graphBaroMin.'-->'.PHP_EOL;
if ($graphWindMax < $graphNrLines) {
	$graphWindMax = $graphNrLines;
}
$graphWindStep = ceil ($graphWindMax / $graphNrLines);
$graphWindMax  = $graphNrLines * $graphWindStep;
$windMax		= $graphWindMax;
$graphWindMax  = $graphWindMax	* 2;
$graphWindStep = $graphWindStep * 2;
$stringY .='<!-- wind max: '.$graphWindMax.' wind step: '.$graphWindStep.'-->'.PHP_EOL;
echo $stringY;
#
$graphDaysString = '{';
$daysShort	= array ('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
$daysLong	= array ('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
for ($i = 0; $i < count ($daysShort); $i++) {
	$graphDaysString .= "'$daysShort[$i]':'".langtransstr($daysLong[$i])."',";
}
$graphDaysString = substr($graphDaysString, 0, strlen($graphDaysString) -1);
$graphDaysString .= '}';
$graphsStart 	= 1000 * ($arrTime[0] - 3600 + $utcDiff);
$n				= count($arrDay)-1;
$graphsStop		= 1000 * ($arrTime[$n] + 3600);
$ddays		= '';
#
for($i=1 ; $i<count($graphsDays); $i++) { //  shaded background every other day
	if($i ==  count($graphsDays)-1) {     // last incomplete day
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.($graphsStop).', color: "rgba(255, 255, 255, 0.9)" },';
	} else {
		$ddays.= '{ from: '.$graphsDays[$i].', to: '.$graphsDays[$i+1].', color: "rgba(255, 255, 255, 0.9)" },';
	}
	$i++;		// skip next day
}
$from = array ('&deg;',' ','/');
$uomRain = str_replace ($from,'',$SITE['uomRain']);
$uomTemp = str_replace ($from,'',$SITE['uomTemp']);
$uomBaro = str_replace ($from,'',$SITE['uomBaro']);
$uomWind = str_replace ($from,'',$SITE['uomWind']);
$negValue = "return '<span style=\"fill: blue;\">' + this.value + '</span>'";
$posValue = "return '<span style=\"fill: red;\">' + this.value + '</span>'";
$treshold = 0;
if ($uomTemp == "F") {
	$treshold = 32;
}

$graphPart1='
		<script type="text/javascript">
		<!--
		var days        = '.$graphDaysString.';

				var globalX = [{
				type: "datetime",
				min: '.$graphsStart.',
						max: '.$graphsStop.',
								plotBands: ['.substr($ddays, 0, -1).'],
										title: {text: null},
										dateTimeLabelFormats: {day: "%H",hour: "%H"},
										tickInterval: 6 * 3600 * 1000,
										gridLineWidth: 0.4,
										lineWidth: 0,
										labels: {y: 20,style:{fontWeight: \'normal\',fontSize:\'10px\'},
										formatter: function() {
										var uh = Highcharts.dateFormat("%H", this.value);
										if(uh=="12"){return Highcharts.dateFormat("%H <br />", this.value) + days[Highcharts.dateFormat("%a", this.value)];}
										else{return Highcharts.dateFormat("%H", this.value);}
}
}
}];
										-->
										</script>
										';
$graphPart1 .='
		<script type="text/javascript">
		<!--
		var tsv = [];
		'.$graphsData.'
				var temps = [],
				wsps = [],
				baros = [],
				precs = [],
				icos = [];
				for (j = 0; j < tsv.length; j++) {
				var line =[];
				line = tsv[j].split("|");
				if(line[0].length > 0 && parseInt(line[0]) != "undefined"){
				date = 1000 * parseInt(line[0]);
				d = new Date (date);
				temps.push([date, parseFloat(line[1])]);
				baros.push([date, parseFloat(line[2])]);
				mkr = "'.$windIconsSmall.'" +line[4]+".png";
						str = {x:date,y:parseFloat(line[3]), marker:{symbol:\'url(\'+mkr+\')\'}};
						wsps.push(str);
						if (line[6] != \'-\') {
						date = 1000 * parseInt(line[5]);
						precs.push([date, parseFloat(line[6])]);
						date = 1000 * parseInt(line[7]);
						mkr = line[8];
						str = {x:date,y:'.$graphIconYvalue.', marker:{symbol:\'url(\'+mkr+\')\'}};
								icos.push(str);
}
} // Line contains correct data
}; // eo for each tsv

								var yTitles 	= {color: "#000000", fontWeight: "bold", fontSize:"10px"};
								var yLabels 	= {color: "#4572A7", fontWeight: "bold", fontSize:"8px"};
								var yLabelsWind = {color: "#1485DC", fontWeight: "bold", fontSize:"8px"};
								var yLabelsBaro = {color: "#9ACD32", fontWeight: "bold", fontSize:"8px"};
								$(document).ready(function() {
								Highcharts.setOptions({
								tooltip: {
								//positioner: function () {return { x: 0};},
								backgroundColor: "#A2D959",
								borderColor: "#fff",
								borderRadius: 3,
								borderWidth: 0,
								shared: true,
								useHTML: true,
								crosshairs: { width: 0.5,color: "#666"},
								style: {lineHeight: "1.3em",fontSize: "11px",color: "#000"},
								formatter: function() {
								var tooltip ="";
								var tooltip = "" + days[Highcharts.dateFormat(\'%a\', this.x)]+" "+ Highcharts.dateFormat(\'%H:%M\', this.x) +"";

								$.each(this.points, function(i, point) {
								var unit = {
								"'.langtransstr('Precipation').'": " '.$uomRain.'",
										"'.langtransstr('Wind').'": " '.$uomWind.'",
												"'.langtransstr('Temperature').'": "°'.$uomTemp.'",
														"'.langtransstr('Pressure').'": " '.$uomBaro.'"
}[point.series.name];
																if(point.series.name != " ") {
																tooltip += "<br/>"+point.series.name+": <b>"+this.point.y+unit+"</b>";
}
});  // eo each
																return tooltip;
}
},
																chart: {
																spacingTop:4,
																renderTo: "placeholder",
																defaultSeriesType: "spline",
																backgroundColor: "rgba(255, 255, 255, 0.4)",
																plotBackgroundColor: {linearGradient: [0, 0, 0, 150],stops: [[0, "#ddd"],[1, "rgba(255, 255, 255, 0.4)"]]},
																plotBorderColor: "#88BCCE",
																plotBorderWidth: 0.5,
																marginRight: 60,
																marginTop: 30,
																marginLeft: 60,
																style: {fontFamily: \'"UbuntuM","Lucida Grande",Verdana,Helvetica,sans-serif\',fontSize:\'11px\'}
},
																title: {text: ""},
																xAxis: globalX,
																lang: {thousandsSep: ""},
																credits: {enabled: false},
																plotOptions: {
																series: {marker: { radius: 0,states: {hover: {enabled: true}}}},
																spline: {lineWidth: 1.5, shadow: false, cursor: "pointer",states:{hover:{enabled: false}}},
																column: {pointWidth:15},
																areaspline: {lineWidth: 1.5, shadow: false,states:{hover:{enabled: false}}}
},
																legend: { borderWidth: 0, align: \'center\', verticalAlign: \'top\', rtl: true},
																exporting: {enabled:false},

});  // eo set general options
																chartTempData  = new Highcharts.Chart({
																chart: { renderTo: "containerTemp" },
																yAxis: [
																{ lineWidth: 2,
																gridLineWidth: 0.4, min: '.$graphTempMin.',max:'.$graphTempMax.',tickInterval:'.$graphTempStep.', offset: 25,
																		title: {text: "°'.$uomTemp.'", rotation: 0, align:"high", offset: 4, y: 0, style:yTitles},
																				labels: {x: -4, y: 1, formatter: function() {if (this.value < '.$tempMin.' || this.value > '.$tempMax.' ){ return ""; }
																						else
																						{if (this.value < 0) {'.$negValue.';} else {'.$posValue.';}}
},style:yLabels}
},
{
																								gridLineWidth: 0, min: 0,max:'.$graphRainMax.',tickInterval:'.$graphRainStep.', offset: 0,
																										title: {text: "'.$uomRain.'", rotation: 0, align:"low", offset: 0,x: -30, y: 15, style:yTitles},
																												labels: {align: "left", x: -20, y: 1,  formatter: function() {if (this.value < 0 || this.value > '.$rainMax.' ){ return ""; } else {return this.value;}},style:yLabels}
},
{
																														gridLineWidth: 0, min: 0, max: '.$graphWindMax.', tickInterval: '.$graphWindStep.', opposite: true,
																																title: {text: "'.$uomWind.'", rotation:0, align:"low", offset: 5,x: 0, y: 15, style:yTitles},
																																		labels: {align: "right",x: 25, y: 1, formatter: function() {if (this.value < 0 || this.value > '.$windMax.' ){ return ""; } else {return this.value;}},style:yLabelsWind}
},
{ lineWidth: 2,
																																				gridLineWidth: 0, min: '.$graphBaroMin.',max: '.$graphBaroMax.',tickInterval: '.$graphBaroStep.',opposite: true, offset: 30,
																																						title: {text:"'.$uomBaro.'", rotation: 0, align:"high", offset: 20, y: 0, style:yTitles},
																																								labels: {align: "left",x: 4, y: 1, formatter: function() {if (this.value < '.$baroMin.' || this.value > '.$baroMax.' ){ return ""; } else {return this.value;}},style:yLabelsBaro}
}
																																										],
																																										series: [

																																										{name: "'.langtransstr('Pressure').'",data: baros,color: "#9ACD32",yAxis: 3},
																																										{name: "'.langtransstr('Precipation').'",data: precs,color:"#4572A7",type:"column",yAxis:1},
																																										{name: "'.langtransstr('Temperature').'",data: temps,color:"#EE4643", threshold: '.$treshold.', negativeColor: "#4572EE"},
																																										{name: "'.langtransstr('Wind').'", data: wsps,  color:"#1485DC",type: "scatter",yAxis:2, marker:{radius:2,symbol:"circle"}},
																																										{name: " ",color:"#006400",type:"scatter",events:{legendItemClick:false},data:icos}
																																												]
});  // eo chart
}); // eo document ready
																																												-->
																																												</script>'.PHP_EOL;
$logoMetYr = '<img src="'.$SITE['imgDir'].'met.no_logo2_eng_250px.jpg" style="height: 30px; margin: 4px 4px 4px 4px;" alt="Met.No - Yr.No logo"/>';
$creditString= '
		<span style="height:5px; font-size: 2px; width: 100%;">&nbsp;</span>
		<div style="width: 100%;">
		<table style="width: 100%;"><tr><td>'.$logoMetYr.'</td><td>
				<small style="float: right;">
				Meteogram and script developed by <a target="_blank" href="http://www.meteo66240.fr">
				Météo 66240</a>.&nbsp;&nbsp;
				Graphs are drawn using <a target="_blank"  href="http://www.highcharts.com">Highcharts</a><br />
				Weather <a target="new" href="http://www.yr.no/?lang=en">forecast</a> from yr.no,
				delivered by the Norwegian Meteorological Institute and the NRK.
				</small></td></tr></table>
				</div>';

#echo '<pre>'; print_r($returnArray); exit;
#echo '<pre>'.$metnoListTable ; exit;

# ------------------------------------------------------------------
function myLongDate ($time) {
	global $dateLongFormat, $longDays, $myLongDays, $longMonths, $myLongMonths;
	#
	$longDays		= array ("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$longMonths		= array ("January","February","March","April","May","June","July","August","September","October","November","December");
	#
	$longDate = date ($dateLongFormat,$time);
	$from	= array();
	$to		= array();
	for ($i = 0; $i < count($longDays); $i++) {
		if (wsfound($longDate,$longDays[$i])) {
			$from[] = $longDays[$i];
			$to[] 	= langtransstr($longDays[$i]);
			break;
		}
	}
	for ($i = 0; $i < count($longMonths); $i++) {
		if (wsfound($longDate,$longMonths[$i])) {
			$from[] = $longMonths[$i];
			$to[] 	= langtransstr($longMonths[$i]);
			break;
		}
	}
	$longDate = str_replace ($from, $to, $longDate);
	return $longDate;
}

function myDateLinePrint($time, &$rowColor) {
	global  $SITE, $lat, $long, $timeFormat, $srise, $sset, $tableColoms;
	$srise 	= date_sunrise($time, SUNFUNCS_RET_TIMESTAMP, $lat, $long);   // standard time integer
	$sset 	= date_sunset ($time, SUNFUNCS_RET_TIMESTAMP, $lat, $long);
	$dlength= $sset - $srise;
	$dlengthHr = floor ($dlength /3600);
	$dlengthMin = round (($dlength - (3600 * $dlengthHr) ) / 60);
	$strDayLength = $dlengthHr.':'. substr('00'.$dlengthMin,-2);

	$longDate = myLongDate ($time);
	$string='<tr class="dateline '.$rowColor.'"><td colspan="'.$tableColoms.'">
			<span style="float:left; position:relative;">&nbsp;<b>'.$longDate.'</b></span>
					<span style="float:right;position:relative;">
					<span class="rTxt"class="miscsprites sun_up">
					<img src="'.$SITE['imgDir'].'/sunrise.png" style="width: 24px; height: 12px;" alt="sunrise" />&nbsp;&nbsp;'.date($timeFormat,$srise).'&nbsp;&nbsp;
							<img src="'.$SITE['imgDir'].'/sunset.png"  style="width: 24px; height: 12px;" alt="sunset" />&nbsp;&nbsp;'.date($timeFormat,$sset).'&nbsp;&nbsp;&nbsp;'.
							langtransstr('Daylength').': '.$strDayLength.'&nbsp;
									</span>
									</span>
									</td></tr>'.PHP_EOL;
	if ($rowColor == 'row-dark') {
		$rowColor = 'row-light';
	} else {$rowColor =  'row-dark';
	}
	return $string;
}

function myCommonTemperature($value){
	global $SITE, $tempArray2, $tempSimple;
	$color = 'red';
	$temp = round($value);
	if (strpos ($SITE['uomTemp'], 'C') ) {
		$colorTemp = $temp + 32;
	} else {$colorTemp = 32 + round(wsConvertTemperature($value, 'F', 'C')  );
	} // for the color lookup we need C as unit
	if (!$tempSimple) {
		if ($colorTemp < 0) {
			$colorTemp = 0;
		} elseif ($colorTemp >= count ($tempArray2) )  {
			$colorTemp = count ($tempArray2) - 1;
		}
		$color		= $tempArray2[$colorTemp];
		$tempString	= '<span class="myTemp" style="text-shadow:1px 1px black; font-weight: bolder; font-size: 200%; color: '.$color.';" >'.$temp.'&deg;</span>';
	} else {
		if ($colorTemp <  32) {
			$color = 'blue';
		} else {$color = 'red';
		}
		$tempString	= '<span class="myTemp" style="text-shadow:1px 1px black; font-weight: bolder; font-size: 150%; color: '.$color.';" >'.$temp.'&deg;</span>';
	}
	return $tempString;
}

# Returns whether needle was found in haystack
function wsFound($haystack, $needle){
	$pos = strpos($haystack, $needle);
	if ($pos === false) {
		return false;
	} else {
		return true;
	}
}
?>