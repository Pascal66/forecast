<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ================================                                     */
/* This version name NPDS Copyright (c) 2001-2002 by Philippe Brunier   */
/************************************************************************/
/* Original Copyright (c) [ ANNEE ] Par [ NOM DU DEVELOPPEUR ]          */
/* Module   : [ NOM DU MODULE ]                                         */
/* Auteur   : [ NOM DU DEVELOPPEUR ]                                    */
/* Mail     : [ MAIL DU DEVELOPPEUR ]                                   */
/* Site     : [ SITE DU DEVELOPPEUR ]                                   */
/************************************************************************/
/* MODULE DEVELOPPE POUR NPDS VERSION [ VERSION DU CORE NPDS ]          */
/************************************************************************/
/* [ ACTION ( Correction, MaJ, ...) ] Par [ NOM ] le [ ANNEE ]          */
/************************************************************************/
/* This NPDS modules is free software. You can redistribute it          */
/* and/or modify it under the terms of the GNU General Public License   */
/* as published by the Free Software Foundation; either version 2 of    */
/* the License.                                                         */
/************************************************************************/

##########################################################################
##
## CARTOUCHE FONCTIONNEL NPDS

if (!stristr($_SERVER['PHP_SELF'],"modules.php")) { die(); }
if (strstr($ModPath,"..") || strstr($ModStart,"..") || stristr($ModPath, "script") || stristr($ModPath, "cookie") || stristr($ModPath, "iframe") || stristr($ModPath, "applet") || stristr($ModPath, "object") || stristr($ModPath, "meta") || stristr($ModStart, "script") || stristr($ModStart, "cookie") || stristr($ModStart, "iframe") || stristr($ModStart, "applet") || stristr($ModStart, "object") || stristr($ModStart, "meta")) {
   die();
}

global $language, $NPDS_Prefix;

if (!function_exists("sql_connect")) {
   include ("modules/$ModPath/retro-compat/mysql.php");
}

if (file_exists("modules/$ModPath/admin/pages.php")) {
   include ("modules/$ModPath/admin/pages.php");
}

include_once ("modules/$ModPath/lang/$language.php");
include ("modules/$ModPath/forecast.conf.php");
include ("modules/$ModPath/forecast.func.php");
include_once('cache.class.php');
include ("modules/$ModPath/cache.timings.php");

include ("header.php");

$ThisFile = "modules.php?ModPath=$ModPath&amp;ModStart=$ModStart";
$ThisRedo = "modules.php?ModPath=$ModPath&ModStart=$ModStart";

if (($cache_obj->genereting_output==1) or ($cache_obj->genereting_output==-1) or (!$SuperCache)) {

## CARTOUCHE FONCTIONNEL NPDS
##
##########################################################################


switch ($op){


    case "cas1exemple":
	
		// [ CODE ]
		
    break;
    
	
    case "cas2exemple":
	
		// [ CODE ]
		
    break;
    
	
    default:

		include("forecast.php");

    break;
    
}


##########################################################################
##
## CARTOUCHE FONCTIONNEL NPDS

   }

   if ($SuperCache) {
      $cache_obj->endCachingPage();
   }
   include("footer.php");
   
## CARTOUCHE FONCTIONNEL NPDS
##
##########################################################################
?>