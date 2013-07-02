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

# Pull misc cart data
$cartpref = new userdata("cart");

############################################################################
### SAVE UPDATED PRIVACY POLICY		                               ###
############################################################################

$update_complete = 0;

if ($ACTION == "savepolicy") {

	$filename = "$cgi_bin/other_policies.txt";
	$file = fopen("$filename", "w");
		$policy = stripslashes($policy);
		fwrite($file, "$policy\n");
	fclose($file);

	while (!file_exists($filename)) {
		// Wait for file to be written -- Very Important Piece Here!
	}

	$update_complete = 1;

}

############################################################################
### READ PRIVACY POLICY FILE INTO MEMORY                                 ###
############################################################################

	$filename = "$cgi_bin/other_policies.txt";
	if (file_exists($filename)) {
		$file = fopen("$filename", "r") or DIE("Error: Could not open statement base file.  Please re-install application to insure proper functionality. It will not erase any data associated with your web site; however it could overwrite modifications to code.");
			$policy = fread($file,filesize($filename));
		fclose($file);
	} else {
		$policy = "";
	}

	// ----------------------------------
	// Parse one size fits all variables
	// ----------------------------------

	$INSERT = strtoupper($SERVER_NAME);

	$policy = eregi_replace("\[DOTCOM\]", "$SERVER_NAME", $policy);
	$policy = eregi_replace("\[USER\]", "$INSERT", $policy);
	$policy = eregi_replace("\[EMAIL\]", "webmaster@$SERVER_NAME", $policy);

#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################

ob_start();

?>


<script language="JavaScript">
<!--
<?

if ($update_complete == 1) {
	echo ("alert('Other Policy Statements Updated!');\n");
	echo ("window.location = '../shopping_cart.php?=SID';\n");
}

?>

//-->
</script>

<link rel="stylesheet" href="shopping_cart.css">

<style type="text/css">
label {
   display: block;
   font-size: 125%;
   font-weight: bold;
   margin-top: 10px;
}

span.sublabel {
   display: block;
   color: #888c8e;
}

input#policy_title {
   width: 300px;
}

</style>

<?
# SAVE: other_policy_title
if ( $_POST['other_policy_title'] != "" ) {
   $cartpref->set("other_policy_title", htmlspecialchars($_POST['other_policy_title']));
}

# DEFAULT: "Other Policies"
if ( $cartpref->get("other_policy_title") == "" ) { $cartpref->set("other_policy_title", lang("Other Policies")); }

$THIS_DISPLAY = "";

$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=other_policies.php name=\"other\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"savepolicy\">\n\n";

$THIS_DISPLAY .= "<label>".lang("Heading/title")."</label>";
$THIS_DISPLAY .= "<span class=\"sublabel\">".lang("Displayed above main body text of this policy.")."\n";
$THIS_DISPLAY .= " ".lang("By default this is set to 'Other Policies' but you can change it to anything you like (e.g., \"Terms of Service\").")."</span>";
$THIS_DISPLAY .= "<input type=\"text\" id=\"policy_title\" name=\"other_policy_title\" value=\"".$cartpref->get("other_policy_title")."\"/>\n";

$THIS_DISPLAY .= "<label>".lang("Policy text")."</label>";
$THIS_DISPLAY .= "<span class=\"sublabel\">".lang("If this field is left blank (as it is by default) the 'Other Policies' block will simply not appear at all on your website.")."</span>";
$THIS_DISPLAY .= "<textarea name=policy style='width: 400px; height: 175px; font-family: Arial; font-size: 8pt;'>".$policy."</textarea>\n\n";

$THIS_DISPLAY .= "<div class=\"center\" style=\"margin-top: 5px;\"><button type=\"button\" class=\"greenButton\" onClick=\"document.other.submit();\"><span><span>".lang("Save Policy Statement")."</span></span></button></div>\n\n";

$THIS_DISPLAY .= "</FORM>\n\n";


$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";
echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Shopping Cart Menu", "program/modules/mods_full/shopping_cart.php");
$module->add_breadcrumb_link("Other Policies", "program/modules/mods_full/shopping_cart/other_policies.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/shopping_cart-enabled.gif";
$module->heading_text = "Other Policies";

$intro_text = lang("Use this section to list other types of policies that you may have for your site.")."<BR>".lang("Remember to title each policy as it will displayed as is.")."\n";
$module->description_text = $intro_text;
$module->good_to_go();
?>
