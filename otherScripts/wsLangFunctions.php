<?php
if (!isset($SITE)){
	header ("Location: ../index.php");	// back to index/startpage if someone tries an
	exit;  								//  page to load without menu system//
}
$pageName		= 'wsLangFunctions.php';
$pageVersion	= '1.01y 2013-01-11';
$SITE['wsModules'][$pageName] = 'version: ' . $pageVersion;
$pageFile = basename(__FILE__);			// check to see this is the real script
if ($pageFile <> $pageName) {
	$SITE['wsModules'][$pageFile]	= 'this file loaded instead of '.$pageName;
}
// no output sent until this time, so no echo allowed
$pathString.= '<!-- module '.$pageFile.' ==== '.$SITE['wsModules'][$pageFile]." -->".PHP_EOL;
#--------------------------------------------------------------------------------------------------
#	1.01	2012-06-10
#	1.01y	2013-01-11	cleaned up a little bit for use in SA wxsim version. no real changes
#--------------------------------------------------------------------------------------------------
# Common:  mostly Language translate functions from english to desired language           
#--------------------------------------------------------------------------------------------------
function langtrans ( $item ) {	// translate and echo
	$trans= langtransstr ( $item ); 
	echo $trans;
}  // eof langtrans  Translate and echo              
#--------------------------------------------------------------------------------------------------
function langtransstr ( $item ) {		// translate a string
	global $LANGLOOKUP,$missingTrans;
	$string = trim ((string) $item);
	if (isset($LANGLOOKUP[$string])) {
		$string = $LANGLOOKUP[$string];
	} else {
		if(isset($string) and $string <> '') { $missingTrans[$string] = true; }
	}
	return $string;
} // eof langtransstr Translate string only
#--------------------------------------------------------------------------------------------------
function print_language_selects($key='') { // create string to  print_language_selects_dropdown function
	global $SITE;
	$use_onchange_submit = true;
	$string1 = '';
	$arr = $SITE['installedLanguages'];
	if (!is_array($arr)){return;}

	$string = '	<form method="get" name="lang_select" action="" style="padding: 0px; margin: 0px">'.PHP_EOL;
	$string .= '	<input type="hidden" style="padding: 0px; border: 0px; margin: 0px" name="p" value ="'.$key.'"/>'.PHP_EOL;
	$string .= '	<span style="font-size: 10px">' . langtransstr('Language') .':&nbsp; </span>'.PHP_EOL; 
	$string .= '	<select id="lang" name="lang"  style="font-size: 9px" onchange="this.form.submit();">'.PHP_EOL;
	$flag = '';
	foreach ($arr as $key => $value) {
		if($SITE['lang'] == $key) {
			$selected = ' selected="selected"';
			$flag = '	<img src="'. $SITE['imgDir'] . 'flag-'. $key .'.gif" alt="'. $value .'" title="'. $value .'" style="padding: 0px; border: 0px; margin: 0px" />'.PHP_EOL;
		  } else {
			$selected = '';
		  }
	$string .= '	    <option value="'.$key.'"'.$selected.'>'.$value.'</option>'.PHP_EOL;
	} // end foreach
	$string .= '	</select>'.PHP_EOL;
	if($SITE['langFlags'] == true) {
		$string .= $flag;
	}   
	$string .= '	</form>'; 
	return $string;
}// eof create string to  print_language_selects_dropdown function
#
#--------------------------------------------------------------------------------------------------
#	At loading of this script the $LANGLOOKUP table is populated with the language strings
#  
$LANGLOOKUP 	= array();		// array with FROM and TO languages
$missingTrans	= array();		// array with strings with missing translation requests
#
#  make url / filepath for neede language file f.e. wsLanguage-nl.text
#
$langfile = $SITE['langDir'].'wsLanguage-' . $lang.'.txt';
if (!file_exists($langfile) ) {	// there is no language file for this language
	$pathString.= "<!-- langfile ($langfile) does not exist -->".PHP_EOL;					
	$langfile 	= $SITE['langDir'].'wsLanguage-'.$SITE['langBackup'].'.txt'; // than try site language 
	if (!file_exists($langfile) ) {
		$pathString.= "<!-- langfile ($langfile) does not exist either <br /> languagesupport failed -->".PHP_EOL;
		return; 
	} // eo backup
}	// eo file not found  
#
$lfile 		= file($langfile);			// file exist, so read it into area
$pathString 	.= "<!-- langfile '$langfile' loading -->".PHP_EOL;
#
# make url / filepath for local language file f.e. wsLanguage-nl-local.text
$langfile 	= $SITE['langDir'].'wsLanguage-' . $lang .'-local.txt';
if (file_exists($langfile)) { 	
	$lfile2  	= file($langfile); 		// file exist, so read it into area
	$pathString.= "<!-- langfile '$langfile' loading -->".PHP_EOL;
	$lfile 		= array_merge($lfile,$lfile2);  // and merge the two files
} else {
	$pathString.= "<!-- local langfile '$langfile' does not exist -->".PHP_EOL;
}
$nLanglookup = 0;					// number of entries
foreach ($lfile as $rec) { 			// process the language file
	$recin = trim($rec);
	if (substr($recin,0,1) <> '#' && $recin <> '') { // only process non blank, non comment records
		list($type, $item,$translation) = explode('|',$recin . '|||||');
		$type = trim($type);
		$item = trim($item);
		if ($type == 'langlookup' && $item && $translation) {
			if (isset ($LANGLOOKUP[$item])) {
				$pathString .= '<!-- multiple langlookup entry = '.$item.' ==> '.$translation.' ==> '.$LANGLOOKUP[$item].' -->'.PHP_EOL;
			}
			$LANGLOOKUP[$item] = preg_replace('|\&amp;nbsp;|Uis','&nbsp;',$translation);
			$nLanglookup++;
		}
	} // end if nonblank, non comment

} // end foreach entry in input files
unset ($lfile );
unset ($lfile2);
$pathString .= "<!-- loaded $nLanglookup langtrans entries -->".PHP_EOL;
$pathString .= "<!-- load_langtrans finished -->".PHP_EOL;
?>