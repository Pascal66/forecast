<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/* SPANISH                                                              */
/* [ NOM DU MODULE ] Language File [ ANNEE ] par [ NOM DU DEVELOPPEUR ] */
/*                                                                      */
/************************************************************************/


function  exemple_trans(($phrase) {
    switch ($phrase) {
	
 		// cas1exemple
       case "Merci": $tmp = "Gracias"; break;
    
		// cas2exemple
	   case "Bonjour": $tmp = "Ola"; break;
	   
	   //Admin
       case "Administrer": $tmp = "administrado ?? "; break;
	
		// DEFAUT 
       default: $tmp = "Necesita ser traducido <b>[** $phrase **]</b>"; break;
    }
    return $tmp;
}
?>