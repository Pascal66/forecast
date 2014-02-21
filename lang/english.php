<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/* ENGLISH                                                              */
/* [ NOM DU MODULE ] Language File [ ANNEE ] par [ NOM DU DEVELOPPEUR ] */
/*                                                                      */
/************************************************************************/

function  exemple_trans($phrase) {
    switch ($phrase) {
		
		// cas1exemple
       case "Merci": $tmp = "Thanks"; break;
    
		// cas2exemple
	   case "Bonjour": $tmp = "Hello"; break;
	   
	   //Admin
       case "Administrer": $tmp = "Manage"; break;

		// DEFAUT
       default: $tmp = "Translation error <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}
?>