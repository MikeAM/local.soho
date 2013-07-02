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

###############################################################################
## LICENSE AGREEMENTS PROHIBITS MODIFICATION OR CHANGING OF THIS SCRIPT OR ANY
## CODE BELOW THIS LINE.
###############################################################################
session_start();

//if(isset($_GET['PHP_AUTH_USER'])){ 
//	$_SESSION['PHP_AUTH_USER'] = base64_decode($_GET['PHP_AUTH_USER']);
//}
//if(isset($_GET['PHP_AUTH_PW'])){
//	$_SESSION['PHP_AUTH_PW'] = base64_decode($_GET['PHP_AUTH_PW']);
//}
//if(isset($_GET['process'])){
//	$_SESSION['process'] = base64_decode($_GET['process']);
//}

# Include core interface files
require_once("program/includes/product_gui.php");

##########################################################################
### VALIDATE EMAIL FUNCTION (For License Agreement Only)
##########################################################################

function email_is_valid ($email) {
	if (eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$", $email, $check)) {
		if ( getmxrr(substr(strstr($check[0], '@'), 1), $validate_email_temp) ) {
			return TRUE;
		}
		// THIS WILL CATCH DNSs THAT ARE NOT MX.
		if(checkdnsrr(substr(strstr($check[0], '@'), 1),"ANY")){
			return TRUE;
		}
	}
	return FALSE;
}

$email_err = 0;

##########################################################################

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html style="height:100%;">
<head>
<?php echo "<title>Site: ".$this_ip."</title>\n"; ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="icons/favicon.ico" />
<link rel="icon" type="image/x-icon" href="icons/favicon.ico">
<link rel="stylesheet" type="text/css" href="program/product_gui.css">
</head>

<script language="JavaScript">
	var width = (screen.width);
	var height = (screen.height - 25);
	var centerleft = 0;
	var centertop = 0;
	var centerleft = (width/2) - (800/2);
	var centertop = (height/2) - (575/2);
	var width=800;
	var height=575;
	if(window.name != 'admin_dialog_content'){
//   	window.moveTo(centerleft,centertop);
//   	window.resizeTo(width, height);
   	window.focus();
   }

	function touchThis(url){
		window.location = url;
	}
	function windowOptions(h, w){
//      parent.windowResize(h, w);
	}
	
</script>

<?php

############################################################################################
/// Configure System Variables
###=========================================================================================
$selSpecs = mysql_query("SELECT * FROM site_specs");
$getSpec = mysql_fetch_array($selSpecs);

if ($getSpec['df_lang'] == "") {
   $language = "english.php";
} else {
   $language = $getSpec['df_lang'];
   $language = rtrim($language);
   $language = ltrim($language);
}

if ( $lang_dir == "" ) {
   $lang_dir = "language";
}

$lang_include = "$lang_dir/$language";

include ("$lang_include");

// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
$_SESSION['btn_edit'] = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";
$_SESSION['btn_build'] = "class=\"btn_build\" onMouseover=\"this.className='btn_buildon';\" onMouseout=\"this.className='btn_build';\"";
$_SESSION['btn_save'] = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
$_SESSION['btn_delete'] = "class=\"btn_delete\" onMouseover=\"this.className='btn_deleteon';\" onMouseout=\"this.className='btn_delete';\"";
$_SESSION['nav_main'] = "class=\"nav_main\" onMouseover=\"this.className='nav_mainon';\" onMouseout=\"this.className='nav_main';\"";
$_SESSION['nav_save'] = "class=\"nav_save\" onMouseover=\"this.className='nav_saveon';\" onMouseout=\"this.className='nav_save';\"";
$_SESSION['nav_soho'] = "class=\"nav_soho\" onMouseover=\"this.className='nav_sohoon';\" onMouseout=\"this.className='nav_soho';\"";
$_SESSION['nav_logout'] = "class=\"nav_logout\" onMouseover=\"this.className='nav_logouton';\" onMouseout=\"this.className='nav_logout';\"";

# For Main Menu button only
$_SESSION['nav_mainmenu'] = "class=\"nav_mainmenu\" onMouseover=\"this.className='nav_mainmenuon';\" onMouseout=\"this.className='nav_mainmenu';\"";

//session_register("btn_edit");
//session_register("btn_build");
//session_register("btn_save");
//session_register("btn_delete");
//
//session_register("nav_main");
//session_register("nav_mainmenu");
//session_register("nav_save");
//session_register("nav_soho");
//session_register("nav_logout");

session_register("lang");
session_register("language");
session_register("getSpec");


// ----------------------------------------------------
// Check for any relevant service packs or updates
// to the product since last login (These would be
// downloaded and installed from DevNet)
// ----------------------------------------------------
//include ("includes/prod_updates.php");


if ( isset($_SESSION['demo_site']) && $_SESSION['demo_site'] == "yes" ) {
   # Make sure this demo site is available (in case of bookmarks, etc)
   //mysql_query("UPDATE demo_timer SET site_active = 'no'"); exit;

   $timeRez = mysql_query("SELECT * FROM demo_timer");
   $getDemo = mysql_fetch_array($timeRez);
   $demo_inuse = $getDemo['site_active'];

   # Double check if table says site is 'in use'
   if ( $getDemo['site_active'] == "yes" ) {

      # Count number of users online
      $show_usercount = "no";
      include("program/users_connected.php");

      # Reset demo site
      //include("program/reset_demosite.php");

   }

} // End if demo site


// ----------------------------------------------------
// Update Client Runtime files in document root with
// available modules as defined in the "client_files"
// directory of the product.
// ----------------------------------------------------
include ("includes/update_client.php");


//include("../media/googletranslate.php");
//// ----------------------------------------------------
//// Build GUI Frameset and load up to Main Menu
//// ----------------------------------------------------
//echo "<frameset rows=\"*,1,19\" cols=\"*\" border=0>\n\n";
echo "<frameset rows=\"0,*,1,19\" cols=\"*\" border=0 id=\"master_frameset\">\n\n";

# HEADER --- Upper nav bar
echo("<frame src=\"program/header.php?=SID\" id=\"upper_bar_frame\" name=\"header\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"NO\">\n");


if($_REQUEST['gotopage'] != ''){
	echo "<frame src=\"".$_REQUEST['gotopage']."?=SID\" name=\"body\" scrolling=\"auto\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"NO\">\n";	
} else {
	echo "<frame src=\"program/loader.php?=SID\" name=\"body\" scrolling=\"auto\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"NO\">\n";
}

//# BODY --- Main content frame
//echo("<frame src=\"program/loader.php?=SID\" name=\"body\" scrolling=\"auto\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"NO\">\n");

# REFRESHER --- The sole purpose of this frame is to refresh every so often so the session doesn't expire
echo("<frame src=\"program/refresher_frame.php\" name=\"refresher\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"NO\">\n");

# FOOTER
echo("<frame id=\"footerid\" src=\"program/footer.php?=SID\" name=\"footer\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"NO\">\n");



//echo("<iframe style=\"height:29px; width:100%;\" src=\"program/header.php?=SID\" name=\"header\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"0\">\n</iframe>\n");
//
//
//
//# BODY --- Main content frame
//echo("<iframe onLoad=\"calcHeight();\" style=\"width:100%; \" src=\"program/loader.php?=SID\" name=\"body\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"0\">\n</iframe>\n");
//
//# REFRESHER --- The sole purpose of this frame is to refresh every so often so the session doesn't expire
//echo("<iframe style=\"height:1px; width:100%;\" src=\"program/refresher_frame.php\" name=\"refresher\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"0\">\n</iframe>\n");
//
//# FOOTER
//echo("<iframe style=\"height:19px; width:100%;\" src=\"program/footer.php?=SID\" name=\"footer\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"0\">\n</iframe>\n");

//



//
echo "<noframes>\n";
echo "<body bgcolor=\"#FFFFFF\">\n";
echo "This Program requires Internet Exporer 5.1 or above (all versions of FireFox, Chrome, and Safari will also work).<BR><BR>Make sure \"Frames\" is turned on for proper operation.\n";
echo "</body>\n";
echo "</noframes>\n";
echo "</html>\n";

?>