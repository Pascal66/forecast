<?php

/************************************************************************/
/************************************************************************/
/*                                                                      */
/* NMIG : NPDS Module Installer Generator                               */
/* --------------------------------------                               */
/*                                                                      */
/* Version 1.1 - 13 Juin 2005                                           */
/* --------------------------                                           */
/*                                                                      */
/* Générateur de fichier de configuration pour Module-Install 1.1       */
/*                                                                      */
/* Développé par Boris - http://www.lordi-depanneur.com                 */
/*                                                                      */#   N      N  M      M  IIIII     GGG
/* Module-Install est un installeur inspiré du programme d'installation */#   NN     N  MM    MM    I     GG   GG
/* d'origine du module Hot-Projet développé par Hotfirenet              */#   N N    N  M M  M M    I    G       G
/*                                                                      */#   N  N   N  M  MM  M    I    G
/************************************************************************/#   N   N  N  M      M    I    G   GGGGGG
/*                                                                      */#   N    N N  M      M    I    G      GG
/* NPDS : Net Portal Dynamic System                                     */#   N     NN  M      M    I     GG   GG
/* ================================                                     */#   N      N  M      M  IIIII     GGG
/*                                                                      */
/* This version name NPDS Copyright (c) 2001 by Philippe Brunier        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/*                                                                      */
/************************************************************************/
/************************************************************************/

#autodoc $name_module: Nom du module

$name_module = "Forecast";
global $ModInstall;


#autodoc $list_fich : Modifications de fichiers: Dans le premier tableau, tapez le nom du fichier
#autodoc et dans le deuxième, A LA MEME POSITION D'INDEX QUE LE PREMIER, tapez le code à insérer dans le fichier.
#autodoc Si le fichier doit être créé, n'oubliez pas les < ? php et ? > !!! (sans espace!).
#autodoc Synopsis: $list_fich = array(array("nom_fichier1","nom_fichier2"), array("contenu_fchier1","contenu_fichier2"));

$list_fich = array(array("admin/extend-modules.txt"), array("\r\n[module]\r\n  [nom]".$ModInstall."[/nom]\r\n  [ModPath]".$ModInstall."[/ModPath]\r\n  [ModStart]admin/adm[/ModStart]\r\n  [niveau]radminsuper[/niveau]\r\n[/module]\r\n"));


#autodoc $sql = array(""): Si votre module doit exécuter une ou plusieurs requêtes SQL, tapez vos requêtes ici.
#autodoc Attention! UNE requête par élément de tableau!
#autodoc Synopsis: $sql = array("requête_sql_1","requête_sql_2");
global $NPDS_Prefix;
$sql = array("");


#autodoc $blocs = array(array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""), array(""))
#autodoc                titre      contenu    membre     groupe     index      rétention  actif      aide       description
#autodoc Configuration des blocs

$blocs = array(array("Forecast"), array("include#modules/".$ModInstall."/today.php"), array("0"), array(""), array("1"), array("0"), array("1"), array(""), array("Forecast"));


#autodoc $txtdeb : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera au début de l'install
#autodoc Si rien n'est mis, le texte par défaut sera automatiquement affiché

$txtdeb = "<br /><b>Attention:<b> si le module ".$ModInstall." est d&eacute;j&agrave; install&eacute; sur votre site, veuillez sauter l'&eacute;tape de cr&eacute;ation de la base de donn&eacute;es<br /><br />Sinon les tables tdgal_* de votre bases de donn&eacute;es seront &eacute;cras&eacute;es</b><br /><br />";


#autodoc $txtfin : Vous pouvez mettre ici un texte de votre choix avec du html qui s'affichera à la fin de l'install

$txtfin = "Nous vous recommandons de lire le tutorial situ&eacute; dans le r&eacute;pertoire install du module.<br />Et pour les questions et le support rendez vous <a href=\"http://modules.npds.org\" target=\"_blank\">modules.npds.org</a>";


#autodoc $link: Lien sur lequel sera redirigé l'utilisateur à la fin de l'install (si laissé vide, redirigé sur index.php)
#autodoc N'oubliez pas les '\' si vous utilisez des guillemets !!!

$end_link = "admin.php?op=Extend-Admin-SubModule&ModPath=".$ModInstall."&ModStart=admin/adm";
?>