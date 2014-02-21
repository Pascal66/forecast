<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'wsIconUrl.php';
$pageVersion	= '2.0B 2013-01-11';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
$pathString .= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#---------------------------------------------------------------------------
# 1.01y 2013-01-11 adapted for SA wxsim with kde and  xx icons and xref
#
# 2.0Beta  2013-01-16		adapt for default icons  more header styles
#
#---------------------------------------------------------------------------
#
$arrLookupNoaa = array (		// known noaa icon codes to default icon translation array
'bkn'	=>	'300', 	'nbkn'	=>	'300n',	'nbknfg'=>	'252n',	'few'	=>	'100',	'nfew'	=>	'100n',
'cloudy'=>	'300', 	'ncloudy'=>	'300n',	'fg'	=>	'252',	'nfg'	=>	'252n',	'hazy'	=>	'150', 
'hi_tsra'=>	'342',	'hi_ntsra'=>'342',	'ip'	=>	'232',	'nip'	=>  '232n',	'mist'	=>	'250',
'ovc'	=>	'400',	'novc'	=>	'400n',	'ra'	=>	'211',	'nra'	=>	'211n',	'rasn'	=>	'231',
'nrasn'	=>	'231n',	'ra1'	=>	'901',	'skc'	=>	'100', 	'nskc'	=>	'100n', 'sn'	=>	'121',
'nsn'	=>	'121n',	'sct'	=>	'200',	'nsct'	=>	'200n',	'sctfg'	=>	'251',	'scttsra'=>	'141',
'nscttsra'=>'141n','nsvrtsra'=>'342n', 	'tsra'	=>	'241',	'ntsra'	=>	'241n',	'shra'	=>	'312',
'nshra'	=>	'312n', 'wind'	=>	'600', 	'nwind' => '600','windyrain' =>'600', 	'mix' => '432', 
'nmix'	=>	'432n',	'cold'	=>	'700',	'hot'	=>  '701',	'fzra'	=>  '231',	'nfzra' =>   '231n',
'hi_shwrs' =>'111',	'hi_nshwrs'=>'111n','raip'	=> '231',	'nraip'=> '231n',
);
$arrLookupYA = array (		// yahoo icon to default icon translation array
'0' =>	'600',	'1' =>	'600',	'2' =>	'600',	'3' =>	'442',	'4' =>	'442',
'5' =>	'432',	'6' =>	'431',	'7' =>	'431',	'8' =>	'430',	'9' =>	'410',
'10' =>	'431',	'11' =>	'411',	'12' =>	'411',	'13' =>	'420',	'14' =>	'420',
'15' =>	'421',	'16' =>	'421',	'17' =>	'430',	'18' =>	'431',	'19' =>	'901',
'20' =>	'451',	'21' =>	'450',	'22' =>	'901',	'23' =>	'600',	'24' =>	'600',
'25' =>	'700',	'26' =>	'400',	'27' =>	'300n',	'28' =>	'300',	'29' =>	'200n',
'30' =>	'200',	'31' =>	'000n',	'32' =>	'000',	'33' =>	'100n','34' =>	'100',
'35' =>	'431',	'36' =>	'701',	'37' =>	'440',	'38' =>	'440',	'39' =>	'411',
'40' =>	'421',	'41' =>	'422',	'42' =>	'221',	'43' =>	'422',	'44' =>	'200',
'45' =>	'442',	'46' =>	'431',	'47' =>	'440',	'3200'=> '901'
);
$arrLookupHwa = array (			// hwa icon to default icon translation array
'0' =>	'901',	'1' =>	'000',	'2' =>	'200',	'3' =>	'230',	'4' =>	'212',	'5' =>	'210',
'6' =>	'230',	'7' =>	'141',	'8' =>	'141',	'9' =>	'242',	'10' =>	'130',	'11' =>	'400',
'12' =>	'420',	'13' =>	'411',	'14' =>	'410',	'15' =>	'421',	'16' =>	'421',	'17' =>	'440',
'18' =>	'441',	'19' =>	'441',	'20' =>	'440',	'21' =>	'421',	'22' =>	'452',	'23' =>	'000n',
'24' =>	'100n',	'25' =>	'210n',	'26' =>	'212n',	'27' =>	'211n',	'28' =>	'231n',	'29' =>	'241n',
'30' =>	'241n',	'31' =>	'242n',	'32' =>	'242n',	'33' =>	'400n',	'34' =>	'431n',	'35' =>	'412',
'36' =>	'411',	'37' =>	'431',	'38' =>	'440',	'39' =>	'241',	'40' =>	'441',	'41' =>	'440',
'42' =>	'100',	'43' =>	'100',	'44' =>	'100n',	'45' =>	'200n',	'46' =>	'120',	'47' =>	'130',
'48' =>	'452',	'49' =>	'452n',	'50' =>	'231',	'51' =>	'231n',	'52' =>	'331',	'53' =>	'331n',
'54' =>	'231',	'55' =>	'231n',	'56' =>	'221',	'57' =>	'221n',	'58' =>	'800',	'59' =>	'600', '60' =>	'800'			
);
$arrLookupWorld = array (		// world weather icon to default icon translation array
'113' => 'sunny',	'116' => 'cloudy3',		'119' => 'cloudy4',		'122' => 'overcast',	'143' => 'mist',	'176' => 'shower1',		'179' => 'snow1',
'182' => 'sleet',	'185' => 'sleet',		'200' => 'tstorm1',		'227' => 'snow4',		'230' => 'dunno',	'248' => 'fog',			'260' => 'sleet',
'263' => 'shower1',	'266' => 'shower1',		'281' => 'sleet',		'284' => 'sleet',		'293' => 'shower1',	'296' => 'shower1',		'299' => 'shower2',
'302' => 'shower2',	'305' => 'shower3',		'308' => 'shower3',		'311' => 'sleet',		'314' => 'sleet',	'317' => 'sleet',		'320' => 'sleet',
'323' => 'snow1',	'326' => 'snow1',		'329' => 'snow2',		'332' => 'snow3',		'335' => 'snow3',	'338' => 'snow4',		'350' => 'hail',
'353' => 'shower1',	'356' => 'shower2',		'359' => 'shower3',		'362' => 'sleet',		'365' => 'sleet',	'368' => 'snow1',		'371' => 'snow2',
'374' => 'hail',	'377' => 'hail',		'386' => 'tstorm1', 	'389' => 'tstorm2', 	'392' => 'snow1', 	'395' => 'snow2',
);
$arrLookupWU = array(		// weather underground to default icon translation array
'chanceflurries'	=>'sleet',			'chancerain'		=>'shower1',			'chancesleet'	=>'sleet',		'chancesnow'	=>'snow1',			'chancetstorms'		=>'tstorm1',
'clear'				=>'sunny',			'cloudy' 			=>'cloudy5',			'flurries'		=>'sleet',		'fog' 			=>'fog',			'hazy' 				=>'mist',
'mostlycloudy' 		=>'cloudy4',		'mostlysunny'		=>'cloudy1',
'nt_chanceflurries'	=>'sleet',			'nt_chancerain'		=>'shower1_night',		'nt_chancesleet'=>'sleet',		'nt_chancesnow'	=>'snow1_night',	'nt_chancetstorms'	=>'tstorm1_night',
'nt_clear'			=>'sunny_night',	'nt_cloudy'			=>'overcast',			'nt_flurries'	=>'sleet',		'nt_fog'		=>'fog_night',
'nt_hazy'			=>'mist_night',		'nt_mostlycloudy'	=>'cloudy4_night',		'nt_mostlysunny'=>'cloudy1_night',
'nt_partlycloudy'	=>'cloudy3_night', 	'nt_partlysunny'	=>'cloudy4_night',
'nt_rain'			=>'shower2_night', 	'nt_sleet'			=>'sleet',				'nt_snow'		=>'snow5',		'nt_sunny'		=>'sunny_night',	'nt_tstorms'		=>'shower1_night',
'partlycloudy'		=>'cloudy3',		'partlysunny'		=>'cloudy4',
'rain'				=>'shower2',		'sleet'				=>'sleet',				'snow'			=>'snow4',		'sunny'			=>'sunny',			'tstorms'			=>'tstorm2',
'unknown'			=>'dunno',			'nt_unknown'		=>'dunno',
);
$arrLookupYrno = array(		//  YrNo icon to default icon translation array
'01d'=>'000',	'01n'=>'000n',	'02d'=>'100',	'02n'=>'100n',	'03d'=>'300',	'03n'=>'300n',
'04' =>'400',	'04d'=>'400',	'04n'=>'400n',	'05d'=>'211',	'05n'=>'211n',	'06d'=>'241',
'06n'=>'241n',	'07d'=>'231',	'07n'=>'231n',	'08d'=>'221',	'08n'=>'221n',	'09' =>'411',
'09d'=>'211',	'09n'=>'211n',	'10' =>'412',	'10d'=>'212',	'10n'=>'212n',	'11' =>'441',
'11d'=>'241',	'11n'=>'241n',	'12' =>'432',	'12d'=>'232',	'12n'=>'232n',	'13' =>'422',
'13d'=>'222',	'13n'=>'222n',	'14' =>'442',	'14d'=>'242',	'14n'=>'242n',	'15' =>'452',
'15d'=>'252',	'15n'=>'252n',
);
$arrLookupWd = array(		//  WeatherDisplay icon to default icon translation array
0  => 'sunny',			1  => 'sunny_night',	2 => 'cloudy1',		3  => 'cloudy1',		4  => 'cloudy2_night',	5  => 'cloudy1',
6  => 'fog',			7  => 'fog',			8  => 'shower2',	9  => 'cloudy1',		10 => 'fog',
11 => 'fog_night',		12 => 'shower2_night',	13 => 'overcast',	14 => 'shower2_night',	15 => 'shower1_night',
16 => 'snow2_night',	17 => 'tstorm2_night',	18 => 'overcast',	19 => 'cloudy2',		20 => 'shower1',
21 => 'shower2',		22 => 'shower1',		23 => 'sleet',		24 => 'sleet',			25 => 'snow1',
26 => 'snow1',			27 => 'snow4',			28 => 'sunny',		29 => 'tstorm3',		30 => 'tstorm3',
31 => 'tstorm3',		32 => 'tstorm3',		33 => 'windy',		34 => 'cloudy1',		35 => 'shower1',
);
$arrLookupKDE = array(		//  KDE icons to default icon translation array
'cloudy1'	=> '100',	'cloudy1_night'	=> '100n',	'cloudy2'	=> '200',	'cloudy2_night'	=> '200n',
'cloudy3'	=> '200',	'cloudy3_night'	=> '200n',	'cloudy4'	=> '300',	'cloudy4_night'	=> '300n',
'cloudy5'	=> '400',	'dunno'			=> '901',	'fog'		=> '352',	'fog_night'		=> '352n',
'hail'		=> '432',	'light_rain'	=> '410',	'mist'		=> '150',	'mist_night'	=> '150n',
'overcast'	=> '400n',
'shower1'	=> '110',	'shower1_night'	=> '110n',	'shower2'	=> '211',	'shower2_night'	=> '211n',
'shower3'	=> '412',	'sleet'			=> '432',
'snow1'		=> '120',	'snow1_night'	=> '120n',	'snow2'		=> '221',	'snow2_night'	=> '221n',
'snow3'		=> '322',	'snow4'			=> '422',	'snow5'		=> '422n',	
'sunny'		=> '000',	'sunny_night'	=> '000n',
'tstorm1'	=> '241',	'tstorm1_night'	=> '241n',	'tstorm2'	=> '342',	'tstorm2_night'	=> '342n',
'tstorm3'	=> '442',
);
$arrXrefKdeYrno = array(		// default icon to yrno icon translation
'cloudy1'	=> '02d',		'cloudy1_night'	=> '02n',			'cloudy2'	=> '02d',		'cloudy2_night'	=> '02n',
'cloudy3'	=> '03d',		'cloudy3_night'	=> '03n',			'cloudy4'	=> '03d',		'cloudy4_night'	=> '03n',
'cloudy5'	=> '04',		'dunno'			=> 'dunno',			'fog'		=> '15d',		'fog_night'		=> '15n',
'hail'		=> '12',		'light_rain'	=> '09',			'mist'		=> '15d',		'mist_night'	=> '15n',
'overcast'	=> '04',
'shower1'	=> '05d',		'shower1_night'	=> '05n',			'shower2'	=> '09',		'shower2_night'	=> '09',
'shower3'	=> '09',		'shower3a'		=> '09',			'shower3b'	=> '09',		'sleet'			=> '12',
'snow1'		=> '08d',		'snow1_night'	=> '08n',			'snow2'		=> '13',		'snow2_night'	=> '13',
'snow3'		=> '13',		'snow3_night'	=> '13',			'snow4'		=> '13',		'snow5'			=> '13',	
'sunny'		=> '01d',		'sunny_night'	=> '01n',
'tstorm1'	=> '11',		'tstorm1_night'	=> '11',			'tstorm2'	=> '11',		'tstorm2_night'	=> '11',
'tstorm3'	=> '11',
);

$arrXrefNumKde = array(			// calculated icon WXSIM to kde icon
'000'  => 'sunny',
'000n' => 'sunny_night',	
'100'  => 'cloudy1',		'110'  => 'shower1',		'111'  => 'shower1',		'112'  => 'shower1',
'100n' => 'cloudy1_night',	'110n' => 'shower1_night',	'111n' => 'shower1_night',	'112n' => 'shower1_night',
'200'  => 'cloudy2',		'210'  => 'shower2',		'211'  => 'shower2',		'212'  => 'shower2',
'200n' => 'cloudy2_night',	'210n' => 'shower2_night',	'211n' => 'shower2_night',	'212n' => 'shower2_night',
'300'  => 'cloudy4',		'310'  => 'shower2',		'311'  => 'shower2',		'312'  => 'shower2',
'300n' => 'cloudy4_night',	'310n' => 'shower2_night',	'311n' => 'shower2_night',	'312n' => 'shower2_night',
'400'  => 'cloudy5',		'410'  => 'shower3a',		'411'  => 'shower3b',		'412'  => 'shower3',
'400n' => 'overcast',		'410n' => 'shower3a',		'411n' => 'shower3b',		'412n' => 'shower3',
							'120'  => 'snow1',			'121'  => 'snow1',			'122'  => 'snow2',
							'120n' => 'snow1_night',	'121n' => 'snow1_night',	'122n' => 'snow2_night',
							'220'  => 'snow2',			'221'  => 'snow2',			'222'  => 'snow3',
							'220n' => 'snow2_night',	'221n' => 'snow2_night',	'222n' => 'snow3_night',
							'320'  => 'snow3',			'321'  => 'snow3',			'322'  => 'snow4',
							'320n' => 'snow3_night',	'321n' => 'snow3_night',	'322n' => 'snow4',
							'420'  => 'snow4',			'421'  => 'snow4',			'422'  => 'snow4',
							'420n' => 'snow4',			'421n' => 'snow4',			'422n' => 'snow4',
							'130'  => 'sleet',			'131'  => 'sleet',			'132'  => 'sleet',
							'130n' => 'sleet',			'131n' => 'sleet',			'132n' => 'sleet',
							'230'  => 'sleet',			'231'  => 'sleet',			'232'  => 'sleet',
							'230n' => 'sleet',			'231n' => 'sleet',			'232n' => 'sleet',
							'330'  => 'sleet',			'331'  => 'sleet',			'332'  => 'sleet',
							'330n' => 'sleet',			'331n' => 'sleet',			'332n' => 'sleet',
							'430'  => 'sleet',			'431'  => 'sleet',			'432'  => 'sleet',
							'430n' => 'sleet',			'431n' => 'sleet',			'432n' => 'sleet',
							'140'  => 'tstorm1',		'141'  => 'tstorm1',		'142'  => 'tstorm1',
							'140n' => 'tstorm1_night',	'141n' => 'tstorm1_night',	'142n' => 'tstorm1_night',
							'240'  => 'tstorm2',		'241'  => 'tstorm2',		'242'  => 'tstorm2',
							'240n' => 'tstorm2_night',	'241n' => 'tstorm2_night',	'242n' => 'tstorm2_night',
							'340'  => 'tstorm2',		'341'  => 'tstorm2',		'342'  => 'tstorm2',
							'340n' => 'tstorm3',		'341n' => 'tstorm3',		'342n' => 'tstorm3',
							'440'  => 'tstorm3',		'441'  => 'tstorm3',		'442'  => 'tstorm3',
							'440n' => 'tstorm3',		'441n' => 'tstorm3',		'442n' => 'tstorm3',
							'150'  => 'mist',			'151'  => 'mist',			'152'  => 'fog',
							'150n' => 'mist_night',		'151n' => 'mist_night',		'152n' => 'fog_night',
							'250'  => 'mist',			'251'  => 'mist',			'252'  => 'fog',
							'250n' => 'mist_night',		'251n' => 'mist_night',		'252n' => 'fog_night',
							'350'  => 'mist',			'351'  => 'fog',			'352'  => 'fog',
							'350n' => 'mist_night',		'351n' => 'fog_night',		'352n' => 'fog_night',
							'450'  => 'mist',			'451'  => 'fog',			'452'  => 'fog',
							'450n' => 'mist_night',		'451n' => 'fog_night',		'452n' => 'fog_night',
							'600'  => 'windy', '700'  => 'cold',  '701' => 'hot', '800' => 'road' , '900' => 'extreme','901' => 'dunno',
);

function wsHeaderLookup ($provider,$iconIn) {
	global	$SITE,			$arrHeaderWeather,
			$arrLookupWU,	$arrLookupYrno,	$arrLookupYA,	$arrLookupWorld,	$arrLookupHwa,
			$arrLookupWd,	$arrLookupNoaa,	$arrXrefNumKde,	 $arrLookupKDE,		$arrXrefKdeYrno,
			$otherIcons	;
}
function wsChangeIcon ($provider,$iconIn, &$iconOut, $iconUrlIn, &$iconUrlOut, &$headerClass) {
	global	$SITE,			$arrHeaderWeather,
			$arrLookupWU,	$arrLookupYrno,	$arrLookupYA,	$arrLookupWorld,	$arrLookupHwa,
			$arrLookupWd,	$arrLookupNoaa,	$arrXrefNumKde,	 $arrLookupKDE,		$arrXrefKdeYrno,
			$otherIcons	;
#	default no icon change
	$iconOut		= $iconIn;
	$iconUrlOut		= $iconUrlIn;
#	if we do not find the icon in the icon set we output a dunno icon
	$iconOwn 		= 'dunno';
	if (! isset($SITE['defaultIconsDir']) ){$SITE['defaultIconsDir'] = $SITE['iconsDir'].'default_icons/';}
#
	switch ($provider) {
		case 'wu':
			if ( isset ($arrLookupWU[$iconIn]) )  { 
				// do we find the specified icon in our table for this iconset
				$iconOwn	= $arrLookupWU[$iconIn];
			} 
			if ((isset($SITE['wuIconsCache'])) 	&& ($SITE['wuIconsCache']	== true))	{
				// use wu icons from our cache (true) or our wu icons from wu url (false)	
				$iconUrlOut	= $SITE['wuIconsDir'].$iconIn.'.gif';
			}
			if ((isset($SITE['wuIconsOwn'])) 	&& ($SITE['wuIconsOwn'] 	== false)) { 
				// use wu icons (true) or our template icons (false)	
				$iconOut 	= $iconOwn; 
				$iconUrlOut	= $SITE['ownIconsDir'].$iconOwn.'.png';
			}
			break;
		case 'yrno':
			if ( isset ($arrLookupYrno[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn	= $arrLookupYrno[$iconIn];
			}
			if ((isset($SITE['yrnoIconsOwn'])) 	&& ($SITE['yrnoIconsOwn'] 	== false))	{
				// use yrno icons (true) or our template icons (false)
				$iconOut	= $iconOwn;
				$iconUrlOut=$SITE['defaultIconsDir'].$iconOwn.'.png" width="32px';
			} else {
				$iconUrlOut=$SITE['yrnoIconsDir'].$iconOut.'.png"';}
			break;
		case 'yahoo':
			if ( isset ($arrLookupYA[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn = $arrLookupYA[$iconIn];
			}
			if ( isset($SITE['yahooIconsOwn']) 	&& $SITE['yahooIconsOwn'] 	== false ) {
				// use yahoo icons (true) or our template icons (false)
				$iconOut	= $iconOwn;
				$iconUrlOut	= $SITE['defaultIconsDir'].$iconOwn.'.png';
			}
			break;
		case 'world':
			if ( isset ($arrLookupWorld[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn = $arrLookupWorld[$iconIn];
			}
			if ( isset($SITE['worldIconsOwn']) 	&& $SITE['worldIconsOwn'] 	== false )	{
				// use worldweather icons (true) or our template icons (false)
				$iconOut	= $iconOwn;
				$iconUrlOut	= $SITE['ownIconsDir'].$iconOwn.'.png" width="43px';
			}
			break;
		case 'hwa':
			if ( isset ($arrLookupHwa[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn = $arrLookupHwa[$iconIn];
			}
			if (isset($SITE['hwaIconsOwn']) && $SITE['hwaIconsOwn'] == false)	{
				// use hwa icons from cache (true) or our template icons (false)
				$iconOut = $iconOwn;
				$iconUrlOut=$SITE['defaultIconsDir'].$iconOwn.'.png';
			}
			break;
		case 'wd':
			if ( isset ($arrLookupWd[$iconIn]) )   {
				// do we find the specified icon in our table for this iconset
				$iconOwn = $arrLookupWd[$iconIn];
			}
			// we do not use the wd icons for output as we could not find the correct icons
			$iconOut = $iconOwn;  
			$iconUrlOut=$SITE['ownIconsDir'].$iconOwn.'.png';			
			break;
		case 'noaa':
			if ( isset ($arrLookupNoaa[$iconIn]) ) {
				$iconOwn = $arrLookupNoaa[$iconIn];
			} else {$iconOwn = '901';}
			$iconOut = $iconOwn;
			$iconUrlOut=$SITE['defaultIconsDir'].$iconOwn.'.png';	
			break;
/*		case 'xx':
			if ( isset ($arrLookupXX[$iconIn]) )   {
				$iconOwn = $arrLookupXX[$iconIn];
			}
			$iconOut = $iconOwn;
			$iconUrlOut=$SITE['defaultIconsDir'].$iconIn.'.png';
		break; */
		case 'default':
			$iconOut = $iconIn;
			$iconUrlOut=$SITE['defaultIconsDir'].$iconIn.'.png';
		break;
		
		case 'kde':
			if ( isset ($arrLookupKDE[$iconIn]) )   {
				$iconOwn = $arrLookupKDE[$iconIn];
			}
			$iconOut = $iconOwn;
			$iconUrlOut=$SITE['defaultIconsDir'].$iconOwn.'.png';
			break;
# xref is not a template set but a translation form our default icons to the yrno/dotvoid set
		case 'xref':
			$iconOut = $arrXrefNumKde[$iconIn];
			$iconOut = $arrXrefKdeYrno[$iconOut];
			$iconUrlOut=$otherIcons.$iconOut.'.png"';
		break;
	}  // eo switch
#
# detect what background should be used based on this icon / weathercondition
	if (isset ($arrHeaderWeather[$iconOwn]) ) {
		$headerClass	= $arrHeaderWeather[$iconOwn];
	} else {
		$headerClass	= 'clouds';
	}
#
	if ($iconOwn == 'dunno') {	// if we did not find the icon we echo debug-type information
		echo "<!-- $provider  -  $iconIn  -  $iconOut  -  $iconUrlIn  -  $iconUrlOut  -  $headerClass     -->".PHP_EOL;
	}
#
}  // eo function
?>