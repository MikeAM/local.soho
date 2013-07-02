<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../program/includes/product_gui.php');
chdir($curdir);
############################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.8
##
## Author: 			Mike Johnston & Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
############################################################################################

############################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc. and Mike Johnston.  All Rights Reserved.
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
#############################################################################################

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// By License you may not modify any portion of this script. This particular
// script has dependancies and programming that can not be modified.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

error_reporting(E_PARSE);
session_start();

# Reset login flag in case user is re-launching the window from an existing session (re-launching treated as 'logging-in')
$_SESSION['login_flag'] = false;

# Added by Cameron to prevent Trial message from showing up when licening domain without killing session.
unset($_SESSION['product_mode']);
unset($_SESSION['trial_expires']);


# Kill stuff while testing
//mysql_query("drop table SYSTEM_PLUGINS");
//mysql_query("drop table SYSTEM_HOOK_ATTACHMENTS");

# In case user is 'restarting' to complete installation of plugin(s)...
# clear list of 'newly installed' plugins so they're not still marked in Plugin Manager
# as 'restart to ensure proper operation' description
unset($_SESSION['new_plugins']);

# First time logging-in to fresh install?
if ( first_login() ) {
   include_once("includes/first_login.php");
}

# Was build version just updated?
if ( file_exists("config/justupdated.txt") ) {
   //include("includes/create_system_tables.inc.php");
   //include("includes/create_system_folders.inc.php");
   //include("includes/normalize_db_tables.inc.php");
   //include("includes/version_compat_updates.inc.php");
   $_SESSION['refresh_css'] = true; // Reload the main menu once after update to make sure they're not running the old css file out of cache

   # Kill "just updated" flag file
   @unlink("config/justupdated.txt");
}

# Copy all pgm- files from master location to docroot location
include("includes/create_system_folders.inc.php");
include("includes/copy_runtime_files.inc.php");

# If demo site make sure demo_track table is created
if ( $_SESSION['demo_site'] == "yes" && !table_exists("demo_track") ) {
	$qry = "prikey int(15) NOT NULL PRIMARY KEY AUTO_INCREMENT, session_id VARCHAR(75), filename VARCHAR(255), timestamp varchar(20)";

	mysql_db_query("$db_name","CREATE TABLE demo_track ($qry)");
}


##########################################################################################
##########################################################################################
##########################################################################################
##########################################################################################
### YOUR LICENSE AGREEMENT FORBIDS THE MODIFICATION OR REMOVAL OF ANY
### LINES OF CODE PAST THIS COMMENT BLOCK.
##########################################################################################
##########################################################################################
##########################################################################################
##########################################################################################

# Attempt to pull down current license key
include ($_SESSION['docroot_path']."/sohoadmin/includes/license.php");

# Open and read type.lic
#================================
$file = fopen("$type_lic", "r");
$data = fread($file,filesize($soholaunch_lic));
fclose($file);
$typedata = split("::::", $data);

# Register type data in session
$_SESSION['typelic'] = $typedata[1];

# Open and read soholaunch.lic
#================================
$file = fopen($soholaunch_lic, "r");
$data = fread($file,filesize($soholaunch_lic));
fclose($file);
$keydata = split("\n", $data);

# Register key data in session
$_SESSION['soholaunchlic'] = $data;

# Make sure domain matches license (give it benifit of doubt)
#---------------------------------------------------------------------
$check_sumA = strtolower($_SESSION['this_ip']); // !
$check_sumA = str_replace("http://", "", $check_sumA);
$check_sumA = trim($check_sumA);
$check_sumA = md5($check_sumA);

$check_sumB = strtolower($_SESSION['this_ip']);
$check_sumB = str_replace("http://", "", $check_sumB);
$check_sumB = eregi_replace("^www\.", "", $check_sumB);
$check_sumB = eregi_replace("^www1\.", "", $check_sumB);
$check_sumB = eregi_replace("^www2\.", "", $check_sumB);
$check_sumB = eregi_replace("^www3\.", "", $check_sumB);
$check_sumB = trim($check_sumB);
$check_sumB = md5($check_sumB);

$check_sumC = strtolower($_SESSION['final_ip']); // !
$check_sumC = str_replace("http://", "", $check_sumC);
$check_sumC = preg_replace("/^www\./i", "", $check_sumC);
$check_sumC = trim($check_sumC);
$check_sumC = md5($check_sumC);

# The more forgiving we are here, the less of our time it will take up later
if( (trim($keydata[1]) != $check_sumA) && (trim($keydata[1]) != $check_sumB) && (trim($keydata[8]) != $check_sumA) && (trim($keydata[8]) != $check_sumB) && (trim($keydata[1]) != $check_sumC) && (trim($keydata[8]) != $check_sumC)){
   //echo "here!"; exit;
   unlink($soholaunch_lic);
   unlink($type_lic);

   echo "<script language=\"JavaScript\">window.location.reload();</script>";

   exit;

	echo "<br><br>\n";
	echo "<div style=\"letter-spacing: 1; padding: 12px; border: 1px solid #000000; border-bottom: 0px; background-color: #F8F9FD; font-family: Arial, helvetical, sans-serif; font-size: 14px;\">\n";
	echo "The license key installed is not authorized for this domain name.\n";
	echo "</div></font>\n";

	echo "<div style=\"font-family: Verdana, Arial, helvetical, sans-serif; color: #2E2E2E; font-size: 12px; border: 1px solid #336699; padding: 15px; background-color: #FFFFFF;\">\n";
	echo "<b>Note:</b> It is possible that your license key may simply be corrupted.\n";
	echo "If so, then you need only refresh your key file and access to the product will be restored instantly.<br><br>\n";

	echo "<p style=\"line-height: 20px;\">\n";
	echo "<b style=\"font-weight: 500; color: #336699; letter-spacing: 1.5;\">Refreshing your key file</b>:<br>\n";
	echo "1. Click on the link below to delete your existing (corrupt) key file<br>";
	echo "2. Close this and all other browser windows<br>";
	echo "3. Log in to the product as normal (product installs new key automatically).<br><br>";

	echo "If you are still unable to login after refreshing your key file, please contact us for further assistance.<br>\n";

	echo "<p align=\"center\" style=\"font-size: 11px;\">\n";
	echo "<form name=\"redl_key\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
	echo "<input type=\"hidden\" name=\"reset_lickey\" value=\"yes\">\n";
	echo "<input type=\"button\" value=\"Reset License Key\" onclick=\"document.redl_key.submit();\" ".$btn_delete.">\n";
	echo "</div>\n";

	echo "<br><br>\n";

	# Available site & server specs
	#-----------------------------------
	echo "<div style=\"font-size: 11px; padding: 0px;\">\n";
	echo "<table align=\"center\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"40%\" style=\"font-family: Verdana, Arial, helvetical, sans-serif; color: #595959; font-size: 11px; background-color: #EFEFEF; border: 1px solid #336699;\">\n";
	echo " <tr>\n";
	echo "  <td colspan=\"2\" align=\"center\"><b>Technical Diagnostic Information</b></td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td>Server Name:</td>\n";
	echo "  <td>".$_SERVER['SERVER_NAME']."</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td>HTTP Host:</td>\n";
	echo "  <td>".$_SERVER['HTTP_HOST']."</td>\n";
	echo " </tr>\n";
	echo " <tr>\n";
	echo "  <td>Server Address:</td>\n";
	echo "  <td>".$_SERVER['SERVER_ADDR']."</td>\n";
	echo " </tr>\n";
	echo "</div>\n";
	echo "</table>\n";


	exit;
}


# Make sure account is still active with host (Pro Server Only)
include("includes/pulse.php");


/*---------------------------------------------------------------------------------------------------------*
 ___                      _  _               ___         __
| _ ) _ _  __ _  _ _   __| |(_) _ _   __ _  |_ _| _ _   / _| ___
| _ \| '_|/ _` || ' \ / _` || || ' \ / _` |  | | | ' \ |  _|/ _ \
|___/|_|  \__,_||_||_|\__,_||_||_||_|\__, | |___||_||_||_|  \___/
                                     |___/
/*---------------------------------------------------------------------------------------------------------*/

# Pull down latest host config data from partner area
include("includes/get_host_config.php");

# Host config data (branding options)
$hostco_file = "config/hostco.conf.php";

# Read host config options into session array
if ( !$hostco_fp = fopen($hostco_file, 'r') ) {
   //echo "Unable to open host config file."; exit;

} else {
   if ( !$hostco_data = fread($hostco_fp, filesize($hostco_file)) ) {
      //echo "Unable to read config file!"; exit;
   }
   $_SESSION['hostco'] = unserialize($hostco_data);
   fclose($hostco_fp);

   # Set various defaults
   if ( $_SESSION['hostco']['get_more_plugins_url'] == "" ) {
      $_SESSION['hostco']['get_more_plugins_url'] = "addons.soholaunch.com";
   }

   if ( $_SESSION['hostco']['get_more_templates_url'] == "" ) {
      $_SESSION['hostco']['get_more_templates_url'] = "addons.soholaunch.com";
   }
}


/*#######################################################################################*/
?>