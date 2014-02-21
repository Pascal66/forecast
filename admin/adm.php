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
if (!function_exists("sql_connect")) {
   include ("modules/$ModPath/retro-compat/mysql.php");
}

if ($admin) {
   global $language, $ModPath, $ModStart, $NPDS_Prefix;

   include_once("modules/$ModPath/forecast.conf.php");
   include_once("modules/$ModPath/admin/adm.func.php");
   include_once("modules/".$ModPath."/lang/".$language.".php");
   
   $ThisFile = "admin.php?op=Extend-Admin-SubModule&amp;ModPath=$ModPath&amp;ModStart=$ModStart";
   $ThisRedo = "admin.php?op=Extend-Admin-SubModule&ModPath=$ModPath&ModStart=$ModStart";
## CARTOUCHE FONCTIONNEL NPDS
##
##########################################################################



	switch ($subop){


		case "cas1admin":
			// [ CODE ]
		break;
		
		case "writeconf":
			SaveFormConfig($xxx,$yyy,$zzzz);
		break;
		
		case "config":
			PrintFormConfig();
		break;

		// default:
		default:
			// [ CODE ]
		break;
		
	}

}

?>