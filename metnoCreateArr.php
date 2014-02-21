<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'metnoCreateArr.php';
$pageVersion	= '0.11 2013-01-27';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
echo '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
# retrieve weather infor from weathersource (MetNo weather == YrNo)
# and return array with retrieved data in the desired language and units C/F
#	&lat  = latitude   &lon  = longitude
#  http://api.met.no/weatherapi/locationforecast/1.8/?lat=$lat&lon=$lon
#   wilsele  50.8952   4.6974
#--------------------------------------------------------------------------------------------------
# retrieve weather infor from weathersource
# and return array with retrieved data in the desired language and units C/F
#--------------------------------------------------------------------------------------------------
class metnoWeather{
/*
# public variables
	public $lat				= $SITE['latitude']; //'50.8952';	//
	public $lon				= $SITE['longitude']; //'4.6974';	//
*/
	# private variables
	private $uomTemp		= 'c';
	private $uomWindDir		= 'deg';
	private $uomWindSpeed	= 'mps';
	private $uomHum			= 'percent';
	private $uomBaro		= 'hPa';
	private $uomCloud		= 'percent';
	private $uomRain		= ' mm';
	
	private $enableCache	= true;			// cache should be anabled when frequent request are made. Keep in mind that the data is only refreshed every hour by google
//	private $cache			= 'cache';		// cache dir is created when not available
	private $cacheTime 		= 3600; 		// Cache expiration time Default: 3600 seconds = 1 Hour
	private $cacheFile		= 'xxx';
	
	private $apiUrlpart 	= array(		// http://api.met.no/weatherapi/locationforecast/1.8/?lat=50.8952&lon=4.6974
	 0 => 'http://api.met.no/weatherapi/locationforecast/1.8/?lat=',
	 1 => 'userinputLatitude',
	 2 => '&lon=',
	 3 => 'userinputLatitude'
	);
	private $weatherApiUrl	= '';
	private $rawData		= '';
#--------------------------------------------------------------------------------------------------
# public functions
#--------------------------------------------------------------------------------------------------
	public function getWeatherData($lat = '', $lon = '') {
		global $SITE, $pageFile;
		
		$this->cache = $SITE['cacheDir'];
		#----------------------------------------------------------------------------------------------
		# clean user input
		#----------------------------------------------------------------------------------------------
		$this->apiUrlpart[1] = trim($SITE['latitude']/*$lat*/);
		$this->apiUrlpart[3] = trim($SITE['longitude']/*$lon*/);
		#----------------------------------------------------------------------------------------------
		# try loading data from cache
		#----------------------------------------------------------------------------------------------
		if ( $this->enableCache && !empty($this->cache) ){
			$this->cache	= $SITE['cacheDir'];
			$uoms			= $SITE['uomTemp'].'-'.$SITE['uomWind'].'-'.$SITE['uomRain'].'-'.$SITE['uomBaro'];
			$from			= array('&deg;','°','/',' ');
			$to				= array('','','','');
			$uoms			= str_replace($from,$to,$uoms);
			$this->cacheFile= $this->cache . $pageFile . '-' .$this->apiUrlpart[1]. '-' . $this->apiUrlpart[3] . '-' .$uoms;
			$returnArray	= $this->loadFromCache();	// load from cache returns data only when its data is valid
			if (!empty($returnArray)) {
				return $returnArray;					// if data is in cache and valid return data to calling program
			}	// eo valid data, return to calling program
		}  		// eo check cache
		#----------------------------------------------------------------------------------------------
		# combine everything into required url
		#  http://api.met.no/weatherapi/locationforecast/1.8/?lat=50.8952&lon=4.6974
		#----------------------------------------------------------------------------------------------
		$this->weatherApiUrl = '';
		$end	= count($this->apiUrlpart);
		for ($i = 0; $i < $end; $i++){
			$this->weatherApiUrl .= $this->apiUrlpart[$i];
		}
		#----------------------------------------------------------------------------------------------
		if ($this->makeRequest()) {  	// load xml from url and process
			$xml = new SimpleXMLElement($this->rawData);
#			print_r ($xml); exit;
			$returnArray = array();
			$utcDiff 								= date('Z');// to help to correct utc differences
			$yrNo			= true;
			if (isset ($xml->meta->model[1]['runended']) ) {$i = 1;}  else {$i=0; $yrNo = false;}
			$returnArray['dates']['filetime']		= date('c', strtotime((string) $xml->meta->model[$i]['runended']) );
			$returnArray['dates']['nextUpdate']		= date('c', strtotime((string) $xml->meta->model[$i]['nextrun']) );
#--------------------------------------------------------------------------------------------------
#  get forecast info
#--------------------------------------------------------------------------------------------------
			$i=0;
			$returnArray['forecast'][$i]['dateFrom'] 	= 'dateFrom';	// 2013-01-25T09:00:00Z
			$returnArray['forecast'][$i]['dateTo'] 		= 'dateTo';		// 2013-01-25T09:00:00Z
			$returnArray['forecast'][$i]['timeFrame'] 	= 'timeFrame';	// 1 2 3 6 hours valid
			$returnArray['forecast'][$i]['dayPart'] 	= 'daypart';	// 0  1  2  3 : 2&3 = day
			$returnArray['forecast'][$i]['timeFrom'] 	= 'unxiTime';	// end time valid in local time
			$returnArray['forecast'][$i]['timestamp'] 	= 'unxiTime';	// end time valid in local time
			
# first the forecast exact point values = valid for this exact end time only
# <temperature id="TTT" unit="celcius" value="-4.0"/>
			$returnArray['forecast'][$i]['tempNU'] 		= 'temp';
			$returnArray['forecast'][$i]['temp'] 		= 'temp';
#  <windDirection id="dd" deg="107.2" name="E"/>
			$returnArray['forecast'][$i]['windDirTxt'] 	= 'windDir';	// name="E"
			$returnArray['forecast'][$i]['windDirDeg'] 	= 'windDir';	// deg="107.2"
# <windSpeed id="ff" mps="0.7" beaufort="1" name="Flau vind"/>
			$returnArray['forecast'][$i]['windSpeedNU']	= 'wind';		// mps="0.7"
			$returnArray['forecast'][$i]['windSpeed']	= 'wind';
			$returnArray['forecast'][$i]['windBft']		= 'wind';		// beaufort="1"
# <humidity value="32.5" unit="percent"/>
			$returnArray['forecast'][$i]['hum']			= 'humidity';	// value="96.2"
# <pressure id="pr" unit="hPa" value="1020.5"/>
			$returnArray['forecast'][$i]['baroNU'] 		= 'baro';
			$returnArray['forecast'][$i]['baro'] 		= 'baro';
# <cloudiness id="NN" percent="67.4"/>
# <lowClouds id="LOW" percent="67.3"/>
# <mediumClouds id="MEDIUM" percent="0.0"/>
# <highClouds id="HIGH" percent="0.0"/>
			$returnArray['forecast'][$i]['clouds']		= 'cloud cover'; // percent="67.4"
# <fog id="FOG" percent="0.2"/>
			$returnArray['forecast'][$i]['fog']			= 'fog';		// percent="0.2"
#
# now the forecast values valid for a certain period. we  use 6 hour values for the dayparts and the smallest value (3 or 1 hour)
# detailed data
			$returnArray['forecast'][$i]['iconDtl']			= 'icon nr';	// number="3"
			$returnArray['forecast'][$i]['weatherDescDtl']	= 'condition';	// id="PARTLYCLOUD"
			$returnArray['forecast'][$i]['rainTxtNUDtl'] 	= 'rain';		// minvalue="0.0"  -  maxvalue="0.0"  or value="0.0"
			$returnArray['forecast'][$i]['rainTxtDtl'] 		= 'rain';		//
			$returnArray['forecast'][$i]['rainDtl'] 		= 'rain';		//
# daypart
			$returnArray['forecast'][$i]['icon']		= 'icon nr';	// number="3"
			$returnArray['forecast'][$i]['weatherDesc']	= 'condition';	// id="PARTLYCLOUD"
			$returnArray['forecast'][$i]['rainTxtNU'] 	= 'rain';		// minvalue="0.0"  -  maxvalue="0.0"  or value="0.0"
			$returnArray['forecast'][$i]['rainTxt'] 	= 'rain';		//
			$returnArray['forecast'][$i]['rain'] 		= 'rain';		//
#
			$end = count($xml->product->time);   // 2012-03-09T18:00:00
			$i			= 0; // new forecast to assemble
			$oldTime	= (string) $xml->product->time[0]['to'];
			for ($n = 1; $n < $end; $n++) {
				$time				= $xml->product->time[$n];
				$data 				= $time->location;
#	echo '<pre> $data = '.PHP_EOL; print_r ($time);PHP_EOL; print_r ($data);
#	exit;
				$strTimeTo			= (string) $time['to'];
				if ($strTimeTo <> $oldTime) {
					$i++;						// new set of forecasts started
					$lastTimeTo		= strtotime($oldTime);
					$strTimeFrom 	= $oldTime;
					$oldTime 		= $strTimeTo;
				}
				if (isset ($data->temperature) ){	// most info from a point record
					$returnArray['forecast'][$i]['dateFrom'] 	= $strTimeFrom;
					$returnArray['forecast'][$i]['dateTo'] 		= $strTimeTo;
					$returnArray['forecast'][$i]['timeFrom'] 	= strtotime($strTimeFrom);
					$returnArray['forecast'][$i]['timestamp']	= $timeTo	= strtotime($strTimeTo);
					$returnArray['forecast'][$i]['timeFrame']	= round( ($timeTo - $lastTimeTo)/3600);;
# 	<temperature id="TTT" unit="celcius" value="-4.0"/>
					$string 									= (string) $data->temperature['value'];
					$amount										= round(wsConvertTemperature($string, $this->uomTemp));
					$returnArray['forecast'][$i]['tempNU'] 		= (string) $amount;
					$returnArray['forecast'][$i]['temp'] 		= (string) $amount.$SITE['uomTemp'];
#  	<windDirection id="dd" deg="107.2" name="E"/>
					$returnArray['forecast'][$i]['windDirTxt'] 	= (string) $data->windDirection['name'];
					$returnArray['forecast'][$i]['windDirDeg'] 	= (string) $data->windDirection['deg'];
# 	<windSpeed id="ff" mps="0.7" beaufort="1" name="Flau vind"/>
					$string										= (string) $data->windSpeed['mps'];
					$amount 									= round( wsConvertWindspeed($string, $this->uomWindSpeed));
					$returnArray['forecast'][$i]['windSpeedNU']	= (string) $amount;
					$returnArray['forecast'][$i]['windSpeed']	= (string) $amount.$SITE['uomWind'];
					$returnArray['forecast'][$i]['windBft'] 	= (string) $data->windSpeed['beaufort'];
#	<humidity value="32.5" unit="percent"/>
					$returnArray['forecast'][$i]['hum']			= round((string) $data->humidity['value']);
# 	<pressure id="pr" unit="hPa" value="1020.5"/>
					$string 									= (string) $data->pressure['value'];
					$amount										= round(wsConvertBaro($string, $this->uomBaro));
					$returnArray['forecast'][$i]['baroNU'] 		= (string) $amount;
					$returnArray['forecast'][$i]['baro'] 		= (string) $amount.$SITE['uomBaro'];
# 	<cloudiness id="NN" percent="67.4"/>
					$returnArray['forecast'][$i]['clouds']		= (string) $data->cloudiness['percent'];
# 	<fog id="FOG" percent="0.2"/>
					$returnArray['forecast'][$i]['fog']			= (string) $data->fog['percent'];
					continue;
				} else {
					$strTimeFrom = (string) $time['from'];
				}
				$intTimeFrom		= strtotime($strTimeFrom);
				$intTimeTo			= strtotime($strTimeTo);
				$timeFrame			= round( ($intTimeTo - $intTimeFrom) /3600);
				$hour				= date('H',$intTimeTo - $utcDiff);  // - utc diff
				$rest				= $hour % 6;
				if ($rest == 0)	{
					$utcDiffHrs = round ($utcDiff / 3600);
					switch (true) {
						case ($utcDiffHrs >= 9) :	$daypartDiff = 2;	break;
						case ($utcDiffHrs >= 3) :	$daypartDiff = 1;	break;
						case ($utcDiffHrs <= -9):	$daypartDiff = -2;	break;
						case ($utcDiffHrs <= -3):	$daypartDiff = -1;	break;
					default: $daypartDiff = 0;
					}
					$hour = $daypartDiff + ($hour / 6);
					if ($hour < 0) {$hour = $hour + 4;} elseif ($hour > 3) {$hour = $hour - 4;}
					$returnArray['forecast'][$i]['dayPart'] = $hour;
#		<symbol id="PARTLYCLOUD" number="3"/>
					$returnArray['forecast'][$i]['icon']		= (string) $data->symbol['number'];		// = icon number
					$returnArray['forecast'][$i]['weatherDesc']	= (string) $data->symbol['id'];
#		<precipitation unit="mm" value="0.0" minvalue="0.0" maxvalue="0.0"/>
					if (isset ($data->precipitation['maxvalue'])) {
						$amount		= (string) $data->precipitation['minvalue'];
						$string		= (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
						$amountMax	= (string) $data->precipitation['maxvalue'];
						if ($amount <> $amountMax) {
							$string		.= '-'.(string) wsConvertRainfall($amountMax, $this->uomRain,$SITE['uomRain']);
							$amount 	= ($amount + $amountMax)/2;
						}
					} else {
						$amount = (string) $data->precipitation['value'];
						$string = (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
					}
					$returnArray['forecast'][$i]['rainTxtNU'] 		= $string;
					$returnArray['forecast'][$i]['rainTxt'] 		= $string.$SITE['uomRain'];
					$returnArray['forecast'][$i]['rain'] 			= (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
#  eo 6 hour period / daypart
				}
				if ($timeFrame == $returnArray['forecast'][$i]['timeFrame']) { // detail information but search for correct time period
#		<symbol id="PARTLYCLOUD" number="3"/>
					$returnArray['forecast'][$i]['iconDtl']		= (string) $data->symbol['number'];		// = icon number
					$returnArray['forecast'][$i]['weatherDescDtl']	= (string) $data->symbol['id'];
#		<precipitation unit="mm" value="0.0" minvalue="0.0" maxvalue="0.0"/>
					if (isset ($data->precipitation['maxvalue'])) {
						$amount		= (string) $data->precipitation['minvalue'];
						$string		= (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
						$amountMax	= (string) $data->precipitation['maxvalue'];
						if ($amount <> $amountMax) {
							$string .='-'.(string) wsConvertRainfall($amountMax, $this->uomRain,$SITE['uomRain']);
							$amount 	= ($amount + $amountMax)/2;
						}
					} else
					{
						$amount = (string) $data->precipitation['value'];
						$string = (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
					}
					$returnArray['forecast'][$i]['rainTxtNUDtl'] 	= $string;
					$returnArray['forecast'][$i]['rainTxtDtl'] 		= $string.$SITE['uomRain'];
					$returnArray['forecast'][$i]['rainDtl'] 		= (string) wsConvertRainfall($amount, $this->uomRain,$SITE['uomRain']);
					continue;
				}
			}	// eo for loop forecasts
			
#		echo '<pre>'; print_r($returnArray); echo '</pre>';
		
		if ($this->enableCache && !empty($this->cache)){
			$this->writeToCache($returnArray);
		}
		return $returnArray;
		
		}  // eo makeRequest processing

	} // eof getWeatherData
	
	private function loadFromCache(){
		if (file_exists($this->cacheFile)){
			$file_time = filectime($this->cacheFile);
			$now = time();
			$diff = ($now-$file_time);
			if ($diff <= $this->cacheTime){
				echo "<!-- weatherdata ($this->cacheFile) loaded from cache  -->".PHP_EOL;
				$returnArray =  unserialize(file_get_contents($this->cacheFile));
				return $returnArray;
			}
		}
	} // eof loadFromCache
	
	private function writeToCache($data){
		if (!file_exists($this->cache)){
			mkdir($this->cache, 0777);   // attempt to make the cache dir
		}
	//			print_r ($data); return;
		if (!file_put_contents($this->cacheFile, serialize($data))){
			echo PHP_EOL."<!-- Could not save data ($this->cacheFile) to cache ($this->cacheFile). Please make sure your cache directory exists and is writable. -->".PHP_EOL;
		} else {echo "<!-- Weatherdata ($this->cacheFile) saved to cache  -->".PHP_EOL;}
	} // eof writeToCache
	
	private function makeRequest(){
		global $SITE;
		$test= false;
		if ($test) {
			$this->rawData  = file_get_contents('test.xml');
#			print_r ($this->rawData); exit;
		} else {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_URL, $this->weatherApiUrl);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 60);
#		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $SITE['curlFollow']);
		$this->rawData = curl_exec ($ch);
		curl_close ($ch);
		}
//		echo $this->weatherApiUrl;

		if (empty($this->rawData)){
			return false;
		}
		$search = array ('Service Unavailable','Error 504','Error 503');
		$error = false;
		for ($i = 0; $i < count($search); $i++) {
			$int = strpos($this->rawData , $search[$i]);
			if ($int > 0) {$error = true; break;}
		}
		if ($error == false) {return true;	} else {return false;}
	} // eof makeRequest
	
}