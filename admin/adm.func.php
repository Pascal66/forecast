<?php
/************************************************************************/
/* NPDS : Net Portal Dynamic System                                     */
/* ===========================                                          */
/*                                                                      */
/* [ NOM DU MODULE ] ADM Function File [ ANNEE ] par [ NOM DU DEVELOPPEUR ] */
/*                                                                      */
/************************************************************************/

Function MenuAdm(){
	global $ThisFile;
	echo '<a href="$ThisFile&amp;subop=formconfig">'.exemple_trans("configurer").'</a> ';
	echo '<a href="$ThisFile&amp;subop=bbbbbbbbbb">'.exemple_trans("aaaaaaaaaaaaa").'</a> ';
}

function PrintFormConfig(){
	global $ModPath, $ModStart;
	echo "<form method=\"post\" action=\"admin.php\">";
	echo "<input type=\"hidden\" name=\"op\" value=\"Extend-Admin-SubModule\" />\n";
	echo "<input type=\"hidden\" name=\"ModPath\" value=\"$ModPath\" />\n";
	echo "<input type=\"hidden\" name=\"ModStart\" value=\"$ModStart\" />\n";
	echo "<input type=\"hidden\" name=\"subop\" value=\"writeconf\" />\n";
	
	echo "............";
	
	echo "</form>";
}


Function SaveFormConfig($xxx,$yyy,$zzzz){

   global $ModPath, $ModStart, $ThisRedo;
   
   $filename = "modules/".$ModPath."/forecast.conf.php";
   $content = "<?php\n";
   $content.= "/************************************************************************/\n";
   $content.= "/* NPDS : Net Portal Dynamic System                                     */\n";
   $content.= "/* ===========================                                          */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/* [ NOM DU MODULE ] Configuration File [ ANNEE ] par [ NOM DU DEVELOPPEUR ]            */\n";
   $content.= "/*                                                                      */\n";
   $content.= "/************************************************************************/\n\n";
	
	$content.= "// variable 1  \n";
	$content.= "\$variable = $xxx\n\n";
	$content.= "// variable 1  \n";
	$content.= "\$variable = $xxx\n\n";
	$content.= "// variable 1  \n";
	$content.= "\$variable = $xxx\n\n";
	
	$content.= "?>";

   if ($myfile = fopen("$filename", "wb")) {
      fwrite($myfile, "$content");
      fclose($myfile);
      unset($content);
      redirect_url($ThisRedo);
   } else {
      redirect_url($ThisRedo."&subop=formconfig");
   }
}

?>