<?php
error_reporting('341');
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
header('Content-type: text/html; charset=UT'.'F-8');
header("X-XSS-Protection: 0");
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

//function email_is_valid ($email) {
//	if (eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,4}$", $email, $check)) {
//		if ( getmxrr(substr(strstr($check[0], '@'), 1), $validate_email_temp) ) {
//			return TRUE;
//		}
//		// THIS WILL CATCH DNSs THAT ARE NOT MX.
//		if(checkdnsrr(substr(strstr($check[0], '@'), 1),"ANY")){
//			return TRUE;
//		}
//	}
//	return FALSE;
//}
if(!function_exists('email_is_valid')){
	function email_is_valid ($email) {
		if(!function_exists('filter_var')){
			if(preg_match("/^[A-Z0-9._%-+]+@[A-Z0-9._%-+]+\.[A-Z]{2,4}$/i",$email)){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			if(filter_var($email,FILTER_VALIDATE_EMAIL) === false){
				return FALSE;
			} else {
				return TRUE;	
			}
		}
	}	// END VALIDATE EMAIL FUNCTION
}
$email_err = 0;

##########################################################################
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">'."\n";
?>
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<?php
if(preg_match('/MSIE/i',$_SERVER['HTTP_USER_AGENT'])){
	echo "	<meta content=\"IE=8\" http-equiv=\"X-UA-Compatible\" />\n";
}
echo "<title>Site: ".$this_ip."</title>\n"; 
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ut"."f-8\">\n";
?>

<link rel="shortcut icon" href="icons/favicon.ico" />
<link rel="icon" type="image/x-icon" href="icons/favicon.ico">
<link rel="stylesheet" type="text/css" href="program/product_gui.css">
<style>
/*frame,html,body {
	 -webkit-text-size-adjust:none!important; -ms-text-size-adjust:none!important; -moz-text-size-adjust:none!important; text-size-adjust:none!important; 
}*/
</style>
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


$_SESSION['getSpec'] = $getSpec;
$_SESSION['language'] = $language;
foreach($lang as $lvar=>$lval){
	$_SESSION['lang'][$lvar]=$lval;
}

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
//echo "<frameset rows=\"0,*,1,19\" cols=\"*\" border=0 id=\"master_frameset\">\n\n";


echo "<frameset rows=\"*,19px\" border=0>\n";
echo "	<frameset cols=\"235px,*\" border=0>\n";

echo "		<frame src=\"program/includes/ultra_menu.php\" id=\"ultramenu\" name=\"ultramenu\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" noresize frameborder=\"0\">\n";

echo "		<frameset rows=\"0,*,1\" cols=\"*\" border=0 id=\"master_frameset\">\n\n";
# HEADER --- Upper nav bar
echo "			<frame src=\"program/header.php?=SID\" id=\"upper_bar_frame\" name=\"header\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"0\">\n";

if($_POST['gotopage'] != ''){
	echo "			<frame src=\"".$_POST['gotopage']."\" name=\"body\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\"  noresize frameborder=\"0\">\n";	
} else {
	echo "			<frame src=\"program/loader.php?=SID\" name=\"body\" scrolling=\"no\" marginwidth=\"0\" marginheight=\"0\" noresize frameborder=\"0\">\n";
}
# REFRESHER --- The sole purpose of this frame is to refresh every so often so the session doesn't expire
echo "			<frame src=\"program/refresher_frame.php\" name=\"refresher\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"0\">\n";

# FOOTER
echo "		</frameset>\n";
echo "	</frameset>\n";
echo "	<frame id=\"footerid\" src=\"program/footer.php?=SID\" name=\"footer\" scrolling=\"NO\" marginwidth=\"0\" marginheight=\"0\" leftmargin=\"0\" topmargin=\"0\" noresize frameborder=\"0\">\n";
echo "</frameset>\n";

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