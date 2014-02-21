<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/* [ NOM DU MODULE ] Function File [ ANNEE ] par [ NOM DU DEVELOPPEUR ] */
/*                                                                      */
/************************************************************************/

function PrintHelloWord ($user) {

	$user = removehack($user);
	echo exemple_trans("Hello Word par"). " " . $user;
	
}

function RequeteSql($id) {

	settype($id,"integer");
	
	$reqsql = "SELECT * FROM tablenpds WHERE id = '".$id."'";
	$reqnow = sql_query($reqsql);
	
	if (sql_num_row($reqnow)>0){
		while (list($word) = sql_fetch_row($reqnow)){
		
			echo exemple_trans("Hello Word par"). " " . $word;
		
		}
	} else {
		exemple_trans("Pas d'enregistrement");	
	}
}


?>