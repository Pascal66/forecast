<?php
/************************************************************************/
/* DUNE by NPDS                                                         */
/*                                                                      */
/* NPDS Copyright (c) 2001-2007 by Philippe Brunier                     */
/* =========================                                            */
/*                                                                      */
/* Multi DataBase Support                                               */
/* Copyright (c) Tribal-Dolphin (www.tribal-dolphin.net) 2005           */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

$sql_nbREQ=0;

// Escape string
   function SQL_escape_string ($arr) {
      if (function_exists("mysql_escape_string"))
         @mysql_escape_string($arr);
      elseif (function_exists("mysql_real_escape_string"))
         @mysql_real_escape_string($arr);
      return ($arr);
   }
// Connexion
   function sql_connect() {
      global $mysql_p, $dbhost, $dbuname, $dbpass, $dbname;

      if (($mysql_p) or (!isset($mysql_p))) {
         $dblink=@mysql_pconnect($dbhost, $dbuname, $dbpass);
      } else {
         $dblink=@mysql_connect($dbhost, $dbuname, $dbpass);
      }

      if (!$dblink) {
         return (false);
      } else {
         if (!@mysql_select_db($dbname, $dblink))
            return (false);
         else
            return ($dblink);
      }
   }
// Erreur survenue
   function sql_error() {
      return @mysql_error();
   }
// Ex�cution de requ�te
   function sql_query($sql) {
      global $sql_nbREQ;
      $sql_nbREQ++;
      if (!$query_id = @mysql_query(SQL_escape_string($sql)))
         return false;
      else
         return $query_id;
   }
// Tableau Associatif du r�sultat
   function sql_fetch_assoc($q_id="") {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
        return @mysql_fetch_assoc($q_id);
   }
// Tableau Num�rique du r�sultat
   function sql_fetch_row($q_id="") {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return @mysql_fetch_row($q_id);
   }
// Resultat sous forme d'objet
   function sql_fetch_object($q_id="") {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return @mysql_fetch_object($q_id);
   }
// Nombre de lignes d'un r�sultat
   function sql_num_rows($q_id="") {
      if (empty($q_id)) {
         global $query_id;
         $q_id = $query_id;
      }
      return @mysql_num_rows($q_id);
   }
// Nombre de champs d'une requ�te
   function sql_num_fields($q_id="") {
      if (empty($q_id)) {
        global $query_id;
        $q_id = $query_id;
      }
      return @mysql_num_fields($q_id);
   }
// Nombre de lignes affect�es par les requ�tes de type INSERT, UPDATE et DELETE
   function sql_affected_rows() {
      return @mysql_affected_rows();
   }
// Le dernier identifiant g�n�r� par un champ de type AUTO_INCREMENT
   function sql_last_id() {
      return @mysql_insert_id();
   }
// Lister les tables
   function sql_list_tables($dbnom="") {
      if (empty($dbnom)) {
         global $dbname;
         $dbnom = $dbname;
      }
      return @sql_query("SHOW TABLES FROM ".SQL_escape_string($dbnom));
   }
// Lib�re toute la m�moire et les ressources utilis�es par la requ�te $query_id
   function sql_free_result($q_id="") {
      return @mysql_free_result($q_id);
   }
// Ferme la connexion avec la Base de donn�es
   function sql_close($dblink) {
      return @mysql_close($dblink);
   }
?>