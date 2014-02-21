<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/* [ NOM DU MODULE ] Language File [ ANNEE ] par [ NOM DU DEVELOPPEUR ] */
/*                                                                      */
/************************************************************************/

function exemple_trans($phrase) {
	if (cur_charset=="utf-8") {
		return utf8_encode($phrase);
	} else {
		return ($phrase);
	}  
}
?>