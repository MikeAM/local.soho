<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


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
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston 
## Copyright 2003-2007 Soholaunch.com, Inc.
## All Rights Reserved.
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
include($_SESSION['product_gui']);

error_reporting(0);

############################################################################
### SAVE UPDATED PRIVACY POLICY		                               ###
############################################################################

$update_complete = 0;

if ($ACTION == "savepolicy") {

	$filename = "$cgi_bin/privacy.txt";
	$file = fopen("$filename", "w");
		$privacy = stripslashes($privacy);
		fwrite($file, "$privacy\n");
	fclose($file);

	while (!file_exists($filename)) {
		// Wait for file to be written -- Very Important Piece Here!
	}

	$update_complete = 1;

}

############################################################################
### READ PRIVACY POLICY FILE INTO MEMORY                                 ###
############################################################################

	$filename = "$cgi_bin/privacy.txt";
	if (!file_exists($filename)) {
		$filename = "shared/privacy_statement.txt";
	}

	$file = fopen("$filename", "r") or DIE("Error: Could not open privacy statement base file.  Please re-install application to insure proper functionality. It will not erase any data associated with your web site; however it could overwrite modifications to code.");
		$privacy = fread($file,filesize($filename));
	fclose($file);

	// ----------------------------------
	// Parse one size fits all variables
	// ----------------------------------

	$INSERT = strtoupper($SERVER_NAME);

	$privacy = eregi_replace("\[DOTCOM\]", "$SERVER_NAME", $privacy);
	$privacy = eregi_replace("\[USER\]", "$INSERT", $privacy);
	$privacy = eregi_replace("\[EMAIL\]", "webmaster@$SERVER_NAME", $privacy);

#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################

ob_start();

?>
<script language="JavaScript">
<?
if ($update_complete == 1) {
	echo ("alert('Privacy Policy Updated!');\n");
	echo ("window.location = '../shopping_cart.php?=SID';\n");
}
?>

//-->
</script>

<?

$THIS_DISPLAY = "";

$THIS_DISPLAY .= "<form name=\"privacypolicy\" method=\"post\" action=\"privacy_policy.php\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"savepolicy\">\n\n";

$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 CLASS=allBorder ALIGN=CENTER><TR><TD>\n\n";
$THIS_DISPLAY .= "     <TEXTAREA NAME=privacy style='background: #EFEFEF; width: 600px; height: 250px; font-family: Arial; font-size: 8pt;'>$privacy</TEXTAREA>\n\n";
$THIS_DISPLAY .= "</TD></TR></TABLE><BR><BR>\n\n";

$THIS_DISPLAY .= "<div class=\"center\"><button type=\"button\" class=\"greenButton\" onClick=\"document.privacypolicy.submit();\"><span><span>".lang("Save Privacy Statement")."</span></span></button></div>\n\n";

$THIS_DISPLAY .= "</form>\n\n";

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Privacy Policy", "program/modules/mods_full/shopping_cart/privacy_policy.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/shopping_cart-enabled.gif";
$intro_text = lang("Standardized eCommerce systems use a privacy policy to disclose how systems operate. The one provided here is generic")."\n";
$intro_text .= lang("and covers all technical issues regarding the operation of this shopping cart system such as session management and cookies.")."\n";
$intro_text .= lang("You may wish to modify this policy statement to your particular business needs. It should disclose all information pertaining")."\n";
$intro_text .= lang("to the use and storage of all data gathered from the checkout process.");
$module->heading_text = "Privacy Policy";
$module->description_text = $intro_text;
$module->good_to_go();
?>
