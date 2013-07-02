<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
# Plugin Manager
require_once("../../../../includes/product_gui.php");
error_reporting(E_PARSE);

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{$lang_Addfontz_title}</title>
	<script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="jscripts/functions.js"></script>
	<base target="_self" />


<?php

echo "<script language=\"javascript\"> \n";
echo "function camfont(nummy) { \n";
//echo "nummy; \n";
//echo "alert(document.getElementById(\"mouse\").href); \n";
echo "newnummy = nummy + 1; \n";
echo "document.getElementById(\"mouse\").href = 'javascript:camfont('+newnummy+');'; \n";
echo "document.getElementById(\"mouse\").innerHTML = '+ add font '+newnummy; \n";
echo "dafontname = 'font'+(nummy - 1); \n";
echo "dafontdisp = 'preview'+(nummy - 1); \n";
//echo "previewid = 'preview'+nummy+; \n";
echo "oldval = document.getElementById(dafontname).value; \n";


?>
	htinsert = '<tr><td>Font '+nummy+':&nbsp;</td> ';
	htinsert = htinsert+"<td><input type='text' id='font"+nummy+"' name='font[]' size='35' style='width: 250px; font-family: Tahoma; font-size: 9pt;'>&nbsp;";
	
	htinsert = htinsert+"	<td><input type='text' id='preview"+nummy+"' size='35' style='width: 250px;' readonly></td></tr>";

<?php
//
//echo "var newnewht = frmhtmls+\" \"+htinsert;	\n";
echo "document.getElementById(dafontname).value = oldval; \n";
echo "document.getElementById(dafontdisp).value = oldval; \n";
echo "document.getElementById(\"fontzstuff\").innerHTML=document.getElementById(\"fontzstuff\").innerHTML + htinsert; \n";
echo "document.getElementById(dafontname).value = oldval; \n";
echo "document.getElementById(dafontdisp).value = oldval; \n";
echo "document.update_fonts.submit(); \n";
//echo "document.getElementById(\"fontzstuff\").innerHTML=document.getElementById(\"fontzstuff\").innerHTML; \n";
echo "} \n";

echo "function restoreDemFonts() { \n";
echo "	location.href=\"camsuperfonts.php?restore=default\"; \n";
echo "} \n";
echo "</script> \n";


if($_GET['restore'] == 'default'){
	$cfonts = new userdata("customfonts");
	$customfonts = "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sand;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats";
	$cfonts->set("fontfams", $customfonts);		
}

if (isset($_POST['font'])) {
	
	$newfontz = '';
	foreach($_POST['font'] as $filly=>$connection){
		if($connection != ''){
			$newfontz .= $connection."=".$connection.",Times New Roman;";	
		}
	}   
	$newfontz = eregi_replace(';$', '', $newfontz);
	
	$cfonts = new userdata("customfonts");
	//if($cfonts->get("fontfams") == "") {
	//	$customfonts = "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sand;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats";
	$cfonts->set("fontfams", $newfontz);		
	//}
	$cfonts = new userdata("customfonts");
	$cfonts = $cfonts->get("fontfams");
	$cfontscamron = $cfonts;
	$cfonts = explode(';', $cfonts);
	//asort($cfonts);
	usort($cfonts, "strnatcasecmp");

	$fcount = 0;
	
	$specialteamsformz = '';

	foreach($cfonts as $fvar=>$fvals){
		$gval = explode('=', $fvals);
		$tvarname = 'font'.$fvar;
		${$tvarname} = $gval['0'];
		$specialteamsformz .= "window.top.body.updateFontsNow('".$fcount."', '".$gval['0']."', '".$gval['0']."')\n";
		$fcount++;
	}

$specialteamsform = "<script language=\"javascript\">\n";
$specialteamsformreset = "window.top.body.resetFontsNow();\n";
$specialteamsform .= $specialteamsformreset;
$specialteamsform .= $specialteamsformz;
$specialteamsform .= "</script>\n";
$goto_var_load = $fcount+1;
$gotovar_var = 'font'.$goto_var_load;

} else {
	$cfonts = new userdata("customfonts");
	if($cfonts->get("fontfams") == "") {
		$customfonts = "Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sand;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats";
		$cfonts->set("fontfams", $customfonts);		
	}
	$cfonts = $cfonts->get("fontfams");
	$cfonts = explode(';', $cfonts);
	usort($cfonts, "strnatcasecmp");
	//	asort($cfonts);
	$finalfonts = '';
	$fcount = 0;
	$specialteamsformz = '';
	foreach($cfonts as $fvar=>$fvals){
		$gval = explode('=', $fvals);
		$tvarname = 'font'.$fvar;
		${$tvarname} = $gval['0'];
		
		$specialteamsformz .= "window.top.body.updateFontsNow('".$fcount."', '".$gval['0']."', '".$gval['0']."')\n";
		$fcount++;
	}
	

$specialteamsform = "<script language=\"javascript\">\n";
$specialteamsformreset = "window.top.body.resetFontsNow();\n";
$specialteamsform .= $specialteamsformreset;
$specialteamsform .= $specialteamsformz;
$specialteamsform .= "</script>\n";
$goto_var_load = $fcount+1;
$gotovar_var = 'font'.$goto_var_load;
//$fform = "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../../../../program/product_gui.css\"> \n";
//$fform .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"cia_fonts.css\"> \n";
//$fform .= "</head>\n<body style=\"background-color:#F8F9FD;\" onload=\"document.getElementById('".$gotovar_var."').focus();\">\n";

}
$fform .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"../../../../../program/product_gui.css\"> \n";
$fform .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"cia_fonts.css\"> \n";
//$fform .= "</head>\n<body style=\"background-color:#F8F9FD;\" onload=\"tinyMCEPopup.executeOnLoad('tinyMCE.triggerNodeChange();'); document.getElementById('".$gotovar_var."').focus(); \">\n";
$fform .= "</head>\n<body style=\"background-color:#F8F9FD;\" onload=\"document.getElementById('".$gotovar_var."').focus(); \">\n";
//sort($cfonts);
$fform .= "<table width=\"100%\" valign=\"top\" align=\"left\" cellpadding=\"0\" cellspacing=\"0\" class=\"feature_sub\" style=\"padding:0px; margin-top: 0px; border: 0px solid red;\"> \n";
$fform .= "	<tr> \n";
$fform .= "		<td valign=\"top\"> \n";
$fform .= "		<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\"> \n";
$fform .= "		<tr> \n";
$fform .= "		<td style=\"width:480px;\" valign=\"top\"> \n";
$fform .= "			<p><b>To use:</b><br> \n";
$fform .= "			<ol style=\"margin-top: 0; font-size: 11px;\"> \n";
$fform .= "			<li>Enter the names of the fonts you would like to add to your font menu.</li> \n";
$fform .= "			<li>Hit 'Save' to preview and save fonts.<br> \n";
$fform .= "			<li>If the font doesn't appear to be correct, check your spelling. \n";
$fform .= "			</ol> \n";
$fform .= "			</p> \n";
$fform .= "		</td> \n";
$fform .= "		<td align=\"left\" valign=\"top\"> \n";
$fform .= "		<BUTTON NAME='RESTORE' CLASS='FormLt1' STYLE='background: #8F0000; width: 157px; border: 1px solid black; color: white; font-weight:bold;' onClick=\"restoreDemFonts();\">Restore Default Fonts</BUTTON> \n";
$fform .= "		</td> \n";
$fform .= "		</tr> \n";
$fform .= "		</table> \n";
$fform .= "	</tr> \n";

$fform .= "<tr> \n";
$fform .= "<td width=100% align=left valign=\"top\"> \n";
$fform .= "<table width=100% cellpadding=0 cellspacing=0 style=\"margin-top: 0px; border: 0px solid;\" align=left valign=\"top\">\n";
$fform .= "	<tr valign=\"top\"> \n";
$fform .= "		<td valign=\"top\" align=\"center\" style=\"width:304px;\"><strong>Font Name</strong></td> \n";
$fform .= "		<td valign=\"top\" align=\"center\" style=\"width:250px;\"><strong>Font Preview</strong></td> \n";
$fform .= "		<td valign=\"top\" align=\"center\">&nbsp;</td> \n";
$fform .= "	</tr> \n";
$fform .= " </table>\n";
$fform .= "</td></tr> \n";

$fform .= "<tr> \n";
$fform .= "<td valign=\"top\"> \n";

$fform .= "<div id=\"dd\" style=\"height:316px; overflow:auto; valign:top; border: 1px solid #334F8D;\"><form name=\"update_fonts\" id=\"update_fonts\" method=\"post\" action=\"camsuperfonts.php\"> \n";
$fform .= "<table border=\"0\"  name=\"fontzstuff\" valign=\"top\"><tbody id=\"fontzstuff\"> \n";
$xax=0;
while($xax <= $fcount){

	$fontnum = ${'font'.$xax};
	$fform .= "	<tr id=\"row".$xax."\"> \n";
	$fform .= "		<td style=\"width:54px;\">Font&nbsp;".($xax + 1).":&nbsp;</td> \n";
	$xax++;
	$fform .= "		<td><input type='text' id='font".$xax."' value='".$fontnum."' name='font[]' size='35' style='width: 250px; font-family: Tahoma; font-size: 9pt;'></td> \n";
	$fform .= "		<td style='width: 250px; font-family: ".$fontnum."; font-size: 9pt;'>".$fontnum."&nbsp;</td> \n";
	//$fform .= "		<td><input type='text' id='preview".$xax."' value='".$fontnum."' size='35' style='width: 250px; font-family: ".$fontnum."; font-size: 9pt;' readonly></td> \n";
	$fform .= "	</tr> \n";
	
}

$fform .= "</tbody></table> \n";
$fform .= $specialteamsform;
//$fform .= "<tr><td style=\"width:54px;\">&nbsp;</td><td style=\"width:250px;\"></td>\n";
//$fform .= "<td align=right>\n";
////$fform .= "<INPUT TYPE='SUBMIT' NAME='UPDATEFONTS' VALUE=' Save Changes ' CLASS='FormLt1' STYLE='background: darkgreen; width: 87px; border: 1px solid black; color: white;'> \n";
//
//$fform .= "</td></tr></table> \n";
$fform .= "</div> \n";
$fform .= "</form><br/><button name='UPDATEFONTS' id='UPDATEFONTS' CLASS='FormLt1' STYLE='background: darkgreen; width: 120px; border: 1px solid black; color: white; font-weight:bold;' onClick=\"document.update_fonts.submit();\">Save Fonts</button>\n";
//$fform .= "<br/><a href=\"#\" onClick=\"reloadUI();\">Update editor</a>\n";

$fform .= "</td> \n";
$fform .= "</tr> \n";
$fform .= "</table> \n";

echo $fform .= "</body>\n</HTML> \n";
?>