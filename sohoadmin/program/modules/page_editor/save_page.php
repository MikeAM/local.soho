<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


//echo "<script language=\"javascript\">\n";
//foreach($_POST as $var=>$val){
//	//if($var == "R1C2"){
////		echo "alert('".$var." = '".$val."')\n";
//	//}
//   //echo "var = (".$var.") val = (".$val.")<br>";
//}
//
//echo "</script>\n";
//
//exit;

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc.  All Rights Reserved.
##
## This script may be used and modified in accordance to the license
## agreement attached (license.txt) except where expressly noted within
## commented areas of the code body. This copyright notice and the comments
## comments above and below must remain intact at all times.  By using this
## code you agree to indemnify Soholaunch.com, Inc, its coporate agents
## and affiliates from any liability that might arise from its use.
##
## Selling the code for this program without prior written consent is
## expressly forbidden and in violation of Domestic and International
## copyright laws.
###############################################################################

session_start();
error_reporting(E_PARSE);
set_time_limit(0);		// IMPORTANT! If server hangs, this will save you! (Not necesary in PHP-Safe_Mode)

# Include core interface files!
require_once("../../includes/product_gui.php");

if($_POST['SAVEAS_name'] != ''){
	$_POST['SAVEAS_name'] = str_replace('_', ' ', $_POST['SAVEAS_name']);
	$SAVEAS_name = $_POST['SAVEAS_name'];

	$SAVEAS_name = slashthis($SAVEAS_name);
	$SAVEAS_name = eregi_replace("'", "", $SAVEAS_name);
	$SAVEAS_name = str_replace("&", "", $SAVEAS_name);

	$checkq = mysql_query("select prikey, page_name, url_name from site_pages where page_name='".str_replace('_', ' ', $SAVEAS_name)."' or url_name='".str_replace(' ', '_', $SAVEAS_name)."'");
	if(mysql_num_rows($checkq) > 0){
		echo "<script type=\"text/javascript\">\n";
		echo "alert('".lang('A page named ').$SAVEAS_name.' '.lang('already exists.')."');\n";
		echo "window.location = 'page_editor.php?currentPage=".str_replace('_', ' ', $_POST['PROP_name'])."&nocache=".time()."'; \n";
		echo "</script>\n"; exit;
	}
}


$currentPage = trim($currentPage);

#######################################################
### READ CONTENT AREA SETTING				  		###
#######################################################
$filename = $cgi_bin . "/contentarea.conf";
if (file_exists("$filename")) {
	$file = fopen("$filename", "r");
	$CONTENTAREA_VAR = fread($file,filesize($filename));
	fclose($file);
} else {
	$CONTENTAREA_VAR = "FIXED";
}
$CONTENTAREA_VAR = chop($CONTENTAREA_VAR);

#######################################################
### UPDATE PAGE PROPERTIES WITH SAVE
#######################################################

function sterilize($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
//	while($st_a != $st_l) {
//		$temp = substr($sterile_var, $st_a, 1);
//		if (eregi("[0-9a-z_ !זרו-]", $temp)) { $tmp .= $temp; }
//		$st_a++;
//	}
	$tmp = str_replace('?', '', $sterile_var);
	$tmp = str_replace(',', '', $tmp);
	$tmp = str_replace('', '', $tmp);
	$tmp = str_replace('/', '', $tmp);
	$tmp = str_replace('\\', '', $tmp);
	$tmp = str_replace('&', "&amp;", $tmp);
	$tmp = str_replace('=', '', $tmp);
	$tmp = str_replace('"', ' ', $tmp);
	$tmp = str_replace('\'', '', $tmp);
	$sterile_var = $tmp;
	$sterile_var = trim($sterile_var);
	return $sterile_var;
}

// include ("update-properties.php");
//echo "PROP_KEYNAME=$PROP_KEYNAME<br>PROP_name=$PROP_name<br>SAVEAS_name=$SAVEAS_name<br>currentPage=$currentPage<br>\n";

###################################################################
#### GET CONTENT VARIABLES
###################################################################

//foreach($_POST as $var=>$val){
//   echo "var = (".$var.") val = (".$val.") sterile_var = (".sterilize($val).")<br>\n";
//}
//exit;

//echo "HTTP_POST_VARS(".$HTTP_POST_VARS.")";
$string = implode("!~!", $HTTP_POST_VARS);
$formValues = split("!~!", $string);
$numVars = count($formValues);

###################################################################
#### Save REGENERATION FILE
###################################################################
if(url_get_encoding('http://'.$_SESSION['this_ip'].'/sohoadmin/config/isp.conf.php') != 'UTF-8' && function_exists('utf8_encode')){
	$PROP_name = utf8_decode($PROP_name);
	$PROP_name = utf8_encode($PROP_name);
}
if (str_replace('_', ' ', $PROP_name) != str_replace('_', ' ', $PROP_KEYNAME)) {
//if ($PROP_name != $PROP_KEYNAME) {
	$new_name = stripslashes($PROP_name);
	$new_name = sterilize($new_name);
	//$new_name = ucwords($new_name);
	$thisPage = str_replace(" ", "_", $new_name);
	$oldthisPage = str_replace(" ", "_", $PROP_KEYNAME);
	$oldpagedocrootphp = $doc_root.'/'.$oldthisPage.'.php';
	$newpagedocrootphp = $doc_root.'/'.$thisPage.'.php';

	$newfilenamecon = "$cgi_bin/$thisPage.con";
	$newfilenameregen = "$cgi_bin/$thisPage.regen";
	$oldfilenamecon = "$cgi_bin/$oldthisPage.con";
	$oldfilenameregen = "$cgi_bin/$oldthisPage.regen";

	if(file_exists($newfilenamecon)){
		//echo "<b>Unable to rename page to (".$new_name.") because that page_name already exists!!<b><br><br>\n";
		
		echo "<script type=\"text/javascript\">\n";
		echo "alert('".lang('Unable to rename page to ').'('.$new_name.') '.lang(' because that page name already exists!! ')."');\n";
		echo "window.location = 'page_editor.php?currentPage=".str_replace('_', ' ', $oldthisPage)."&nocache=".time()."'; \n";
		echo "</script>\n"; exit;
		
		$thisPage = $oldthisPage;
	} else {
		rename($oldfilenameregen, $newfilenameregen);
		rename($oldfilenamecon, $newfilenamecon);
		rename($oldpagedocrootphp, $newpagedocrootphp);
	}
} elseif ($SAVEAS_name) {
	$new_name = stripslashes($SAVEAS_name);
	//$new_name = ucwords($new_name);
   $thisPage = eregi_replace(" ", "_", $new_name);
} else {
   $thisPage = eregi_replace(" ", "_", $currentPage);
}
//echo "page name(".$currentPage.")<br/>\n";

$daPage = eregi_replace(" ", "%20", $currentPage);

$filename = "$cgi_bin/$thisPage.regen";
$thispageRegen = '';
# If can't write: attempt to fix, try write again, then bomb if it still fails
# Note: This only has to be done here for the first fwrite call since it won't even get to the others if there's a problem
if ( !$file = fopen("$filename", "w") ) { testWrite("sohoadmin/tmp_content", true); $file = fopen("$filename", "w"); }

for ($x=1;$x<=10;$x++) {
	for ($y=1;$y<=3;$y++) {
		$varTemp = "R" . $x . "C" . $y;
		//echo "-------------------------------------(".$varTemp.")<br/>\n";

		$varTemp = ${$varTemp};
		$varTemp = stripslashes($varTemp);
		//echo "<textarea style=\"width:900px;height:150px;\">".$varTemp."</textarea><br/>\n";
		$varTemp = eregi_replace("<\?xml:namespace prefix = o ns = \"urn:schemas-microsoft-com:office:office\" />", "", $varTemp);

		$varTemp = str_replace("http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage=".$currentPage."&=SID", "", $varTemp);
		$varTemp = str_replace("http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage=".$currentPage, "", $varTemp);

		$varTemp = str_replace("http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage=".$daPage."&=SID", "", $varTemp);
		$varTemp = str_replace("http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage=".$daPage, "", $varTemp);

		$varTemp = str_replace("http://".$_SESSION['this_ip']."/#", "#", $varTemp);

		$varTemp = eregi_replace("href=\"http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/","href=\"", $varTemp);

      if(eregi("<sohotextarea", $varTemp)){
      	$varTemp = eregi_replace("<sohotextarea", "<textarea", $varTemp);
      	$varTemp = eregi_replace("</sohotextarea", "</textarea", $varTemp);
      }
      //echo "(".$varTemp.")<br/><br/>\n\n";
		fwrite($file, "$varTemp!~!\n");
		$thispageRegen .= "$varTemp!~!\n";
		//echo "<textarea style=\"width:900px;height:150px;\">".$varTemp."</textarea><br/>\n";
	}	
}

//echo "(".$varTemp.")<br/>";
//echo "(".$dis_other.")<br/>";

###################################################################
#### BUILD OBJ DROP AREAS FOR DISPLAY
###################################################################

$numLinkVars=0;
for ($x=0;$x<=$numVars;$x++) {
	if (eregi("PICLINK", $formValues[$x])) {
		$numLinkVars++;
		$regenTemp[$numLinkVars] = $formValues[$x];
		$temp = eregi("(.*)PICLINK", $formValues[$x], $out);
		$imageName = $out[1];
		$temp = split(" ", $imageName);
		$imageId[$numLinkVars] = $temp[0];
		$temp = eregi("PICLINK(.*)", $formValues[$x], $out);
		$mtmp = $out[1];
		${$imageId[$numLinkVars]} = $mtmp;
		$linkImageToo[$numLinkVars] = $mtmp;
   }
}



fwrite ($file, "$numLinkVars!~!\n");
$thispageRegen .= "$numLinkVars!~!\n";
for ($x=1;$x<=$numLinkVars;$x++) {
	fwrite($file, "$regenTemp[$x]!~!\n");
	$thispageRegen .= "$regenTemp[$x]!~!\n";
}
//echo "<textarea style=\"width:900px;height:150px;\">".$thispageRegen."</textarea><br/>\n";
//exit;
fclose($file);
mysql_query("update site_pages set content_regen='".addslashes($thispageRegen)."' where url_name='".$thisPage."'");
chmod($filename, 0755);

###################################################################
#### START WRITE TO HTML SAVE ROUTINE 				   			###
###################################################################

$spacer = "<image src=\"spacer.gif\" width=\"199\" height=\"1\" border=\"0\">\n";
$tab = "\n     ";

if ($CONTENTAREA_VAR == "FIXED") {
	// Fixed Expansion (Old Style)
	$thispage = "<table border=0 cellpadding=1 cellspacing=0 width=612>\n";
	$thispage .= "\n<tr>$tab<td align=center valign=top width=199>$tab</td>$tab<td align=center valign=top width=199>$spacer</td>$tab<td align=center valign=top width=199>$spacer</td>\n</tr>\n";
} else {
	// Liquid Expansion Addition 2003-03-04
	// -------------------------------------------------------------------------------
	// Added by popular demand.  The old way is still in the code, just commented out
	// in case fixed width comes back in style.

	$thispage = "<table id=\"content-parent\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">\n";
}

for ($row=1;$row<=10;$row++) {

	$note = "\n\n\n<!-- Content Row $row ----------------------------------------- -->\n\n\n";
	$thisrow = "";
	$fill = " ";

	$tmp = "R" . $row . "C1";
	$col[1] = ${$tmp};
	$col[1] = stripslashes($col[1]);
	$tmp = "R" . $row . "C2";
	$col[2] = ${$tmp};
	$col[2] = stripslashes($col[2]);
	$tmp = "R" . $row . "C3";
	$col[3] = ${$tmp};
	$col[3] = stripslashes($col[3]);

	if (!eregi("pixel.gif", $col[1])) { $thisrow = "1-"; } else { $thisrow = "0-"; }
	if (!eregi("pixel.gif", $col[2])) { $thisrow .= "1-"; } else { $thisrow .= "0-"; }
	if (!eregi("pixel.gif", $col[3])) { $thisrow .= "1"; } else { $thisrow .= "0"; }

	if ($CONTENTAREA_VAR == "FIXED") {

		if ($thisrow == "1-0-0") { $fill = "$note<tr>$tab<td align=left valign=top width=612 colspan=3>#COL1#</td>\n</tr>\n"; $l[1]=597; }
		if ($thisrow == "0-1-0") { $fill = "$note<tr>$tab<td align=center valign=top width=612 colspan=3>#COL2#</td>\n</tr>\n"; $l[2]=597; }
		if ($thisrow == "0-0-1") { $fill = "$note<tr>$tab<td align=right valign=top width=612 colspan=3>#COL3#</td>\n</tr>\n"; $l[3]=597; }

		if ($thisrow == "1-1-0") { $fill = "$note<tr>$tab<td align=center valign=top width=199>#COL1#</td>$tab<td align=center valign=top width=398 colspan=2>#COL2#</td>\n</tr>\n";  $l[1]=199; $l[2]=398; }
		if ($thisrow == "1-1-1") { $fill = "$note<tr>$tab<td align=center valign=top width=199>#COL1#</td>$tab<td align=center valign=top width=199>#COL2#</td>$tab<td align=center valign=top width=199>#COL3#</td>\n</tr>\n"; $l[1]=199; $l[2]=199; $l[3]=199; }
		if ($thisrow == "1-0-1") { $fill = "$note<tr>$tab<td align=center valign=top width=612 colspan=3><table border=0 cellpadding=0 cellspacing=0 width=612>\n<tr><td align=center valign=top width=298>#COL1#</td>$tab<td align=center valign=top width=298>#COL3#\n</td></tr></table>\n</td>\n</tr>\n"; $l[1]=298; $l[3]=298; }
		if ($thisrow == "0-1-1") { $fill = "$note<tr>$tab<td align=center valign=top width=398 colspan=2>#COL2#</td>$tab<td align=center valign=top width=199>#COL3#</td>\n</tr>\n"; $l[2]=398; $l[3]=199; }

	} else {

		// Liquid Expansion Added by popular demand.
		if ($thisrow == "1-0-0") { $fill = "$note<tr>$tab<td align=\"left\" valign=\"top\" width=\"100%\" colspan=\"3\" class=\"100percent\">#COL1#</td>\n</tr>\n"; $l[1]="100%"; }
		if ($thisrow == "0-1-0") { $fill = "$note<tr>$tab<td align=\"center\" valign=\"top\" width=\"100%\" colspan=\"3\" class=\"100percent\">#COL2#</td>\n</tr>\n"; $l[2]="100%"; }
		if ($thisrow == "0-0-1") { $fill = "$note<tr>$tab<td align=\"right\" valign=\"top\" width=\"100%\" colspan=\"3\" class=\"100percent\">#COL3#</td>\n</tr>\n"; $l[3]="100%"; }

		if ($thisrow == "1-1-0") { $fill = "$note<tr>$tab<td align=\"center\" valign=\"top\" width=\"33%\" class=\"33percent\">#COL1#</td>$tab<td align=\"center\" valign=\"top\" width=\"66%\" colspan=\"2\" class=\"66percent\">#COL2#</td>\n</tr>\n";  $l[1]="33%"; $l[2]="66%"; }
		if ($thisrow == "1-1-1") { $fill = "$note<tr>$tab<td align=\"center\" valign=\"top\" width=\"33%\" class=\"33percent\">#COL1#</td>$tab<td align=\"center\" valign=\"top\" width=\"33%\" class=\"33percent\">#COL2#</td>$tab<td align=\"center\" valign=\"top\" width=\"33%\" class=\"33percent\">#COL3#</td>\n</tr>\n"; $l[1]="33%"; $l[2]="33%"; $l[3]="33%"; }
		if ($thisrow == "1-0-1") { $fill = "$note<tr>$tab<td align=\"center\" valign=\"top\" width=\"100%\" colspan=\"3\" class=\"100percent\"><table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n<tr><td align=\"center\" valign=\"top\" width=\"50%\" class=\"50percent\">#COL1#</td>$tab<td align=\"center\" valign=\"top\" width=\"50%\" class=\"50percent\">#COL3#\n</td></tr></table>\n</td>\n</tr>\n"; $l[1]="50%"; $l[3]="50%"; }
		if ($thisrow == "0-1-1") { $fill = "$note<tr>$tab<td align=\"center\" valign=\"top\" width=\"66%\" colspan=\"2\" class=\"66percent\">#COL2#</td>$tab<td align=\"center\" valign=\"top\" width=\"33%\" class=\"33percent\">#COL3#</td>\n</tr>\n"; $l[2]="66%"; $l[3]="33%"; }

	}

	$content[$row] = $fill;

	for ($x=1;$x<=3;$x++) {
		if (!eregi("pixel.gif", $col[$x])) {
			$thisrow = $col[$x];
			$droparea = "";

			// ###############################################################################
			// The Object Write Include interprets all the hidden textarea data passed during
			// the editing process and writes the HTML for final output here.
			// If you wish to modify how an object writes to the client web site; modify the
			// object_write.php include!
			// ###############################################################################

			include("object_write.php");

			// ###############################################################################

			$content[$row] = eregi_replace("#COL$x#", $droparea, $content[$row]);
//     		echo "<textarea name=\"textarea\" style=\" width: 300; height: 300;\">".$content[$row]."</textarea><br><br>\n";

		}
	}

}

###################################################################
#### Save Final Page HTML for Display				               ####
###################################################################
if($new_name) {
   $pagefile = str_replace(" ", "_", $new_name);
} else {
   $pagefile = str_replace(" ", "_", $currentPage);
}

$filename = "$cgi_bin/$pagefile.con";
$thispageContent = $thispage;

//fwrite($file, "$thispage");
for ($row=1;$row<=10;$row++) {
	//fwrite($file, "$content[$row]");
	$thispageContent .= "$content[$row]";
}
//fwrite($file, "\n</table>\n");
$thispageContent .= "\n</table>\n";
$thispageContent = str_replace(' src="http://'.$this_ip.'/images/', ' src="images/', $thispageContent);

$file = fopen("$filename", "w");
if ( !fwrite($file, "$thispageContent") ) {
   echo "<b>Unable to save page content for (".$_GET['currentPage'].") to file (".$filename.")!<b><br><br>\n";
   echo "<u>Possible Solution</u>:<br>\n";
   echo "Log in to your hosting account via FTP and change permissions on the '/sohoadmin/tmp_content' directory \n";
   echo "from '".substr(sprintf('%o', fileperms($cgi_bin)), -4)."'\n";
   echo "to '0777'.<br><br>\n";
   exit;
}


mysql_query("update site_pages set content='".addslashes($thispageContent)."' where url_name='".$pagefile."'");
fclose($file);

chmod($filename, 0755);

# Write .php file of this page to docroot for SEO
$indexphpurl = "<?php\n";
$indexphpurl .= 'error_reporting(E_PARSE);'."\n";
$indexphpurl .= 'session_start();'."\n";
$indexphpurl .= 'if($_GET[\'_SESSION\'] != \'\' || $_POST[\'_SESSION\'] != \'\' || $_REQUEST[\'_SESSION\'] != \'\' || $_COOKIE[\'_SESSION\'] != \'\') { exit; }'."\n\n";
$indexphpurl .= 'include_once("sohoadmin/client_files/pgm-site_config.php");'."\n";
$indexphpurl .= 'include_once("sohoadmin/program/includes/shared_functions.php");'."\n";
	
$indexphpurl .= '$pagefile = __FILE__;'."\n";
$indexphpurl .= 'if($pagefile == \'\'){'."\n";
$indexphpurl .= '	$pagefile = $_SERVER[\'SCRIPT_FILENAME\'];'."\n";
$indexphpurl .= '}'."\n";

$indexphpurl .= 'if(function_exists(\'url_get_encoding\')){'."\n";

$indexphpurl .= '	if(url_get_encoding(\'http://\'.$_SESSION[\'this_ip\'].\'/sohoadmin/config/isp.conf.php\') != \'UTF-8\' && function_exists(\'utf8_decode\')){'."\n";
$indexphpurl .= '		$pagefile = utf8_decode($pagefile);'."\n";
$indexphpurl .= '	}'."\n";
$indexphpurl .= '}'."\n";

$indexphpurl .= '$pagefile = preg_replace(\'/\.php$/i\', \'\', basename($pagefile));'."\n";

$indexphpurl .= '$pagetitle = str_replace( "_", " ", $pagefile);'."\n";
$indexphpurl .= '$secure_setting = mysql_query("select username from site_pages where page_name = \'$pagetitle\'");'."\n";
$indexphpurl .= '$secure_name = mysql_fetch_array($secure_setting);'."\n";
$indexphpurl .= 'if (!isset($secure_name[\'username\']) or ($secure_name[\'username\'] == "")) {'."\n";
$indexphpurl .= '	$pr = $pagefile;'."\n";
$indexphpurl .= '	$_REQUEST[\'pr\'] = $pagefile;'."\n";
$indexphpurl .= '	$_GET[\'pr\'] = $pagefile;'."\n";
$indexphpurl .= '	$_POST[\'pr\'] = $pagefile;'."\n";
$indexphpurl .= '	$pageRequest = $pagefile;'."\n";
$indexphpurl .= '	include("index.php");'."\n";
$indexphpurl .= '} else { '."\n";
$indexphpurl .= '	$pr = $pagefile;'."\n";
$indexphpurl .= '	$_REQUEST[\'pr\'] = $pagefile;'."\n";
$indexphpurl .= '	$_GET[\'pr\'] = $pagefile;'."\n";
$indexphpurl .= '	$_POST[\'pr\'] = $pagefile;'."\n";
$indexphpurl .= '	$pageRequest = $pagefile;'."\n";
$indexphpurl .= '	include("index.php");'."\n";
//$indexphpurl .= '	$destination = "index.php?pr=".$pagefile;'."\n";
//$indexphpurl .= '	header("Location:$destination");'."\n";
$indexphpurl .= '}'."\n";
$indexphpurl .= 'exit;'."\n";
$indexphpurl .= "?>"."\n";



$pagelinkfile = $pagefile;
if(url_get_encoding('http://'.$_SESSION['this_ip'].'/sohoadmin/config/isp.conf.php') != 'UTF-8' && function_exists('utf8_encode')){
	$pagelinkfile = utf8_encode($pagelinkfile);
}

$indexphpfilename = $doc_root."/".$pagelinkfile.".php";
$indexphpfile = fopen($indexphpfilename, "w");
fwrite($indexphpfile, $indexphpurl);
fclose($indexphpfile);
chmod($indexphpfilename, 0755);


#######################################################
### UPDATE PAGE PROPERTIES WITH SAVE		    	    ###
#######################################################

include ("update-properties.php");

###################################################################
#### Go Back to Page			 				   ####
###################################################################

eval(hook("save_page.php:bottom-before-redirect"));

if ($redirect == "preview") {
   $redirect = "page_editor.php?previewWindow=1&currentPage=$currentPage&=SID";
	echo "<script>\n";
	echo "document.location.href='".$redirect."';";
	echo "</script>\n";
	exit;
	//header ("Location: $redirect");
	exit;
}
unset($_GET);
unset($_REQUEST);
unset($_POST);
echo "<script>\n";
echo "document.location.href='".$redirect."?currentPage=".$currentPage."';";
echo "</script>\n";
exit;
//header ("Location: $redirect?currentPage=$currentPage&=SID");
exit;

?>
