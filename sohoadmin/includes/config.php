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

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// By License you may not modify any portion of this script. This particular
// script has dependancies and programming that can not be modified.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

set_time_limit(0);
//error_reporting(E_PARSE);
session_start();

/// Ensure 100% legit docroot var is available
###-------------------------------------------------------------------------------------
if ( !is_dir($_SESSION['docroot_path']) ) {

   # Known aspects of path
   $clipknown = DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR.basename(__FILE__);

   # Strip away fluff and presto: garaunteed-accurate docroot path
   $_SESSION['docroot_path'] = str_replace( $clipknown, "", __FILE__);

   # Strip away fluff and presto: garaunteed-accurate docroot path
   $_SESSION['product_gui'] = $_SESSION['docroot_path']."/sohoadmin/program/includes/product_gui.php";

   # Define domain root path (for html stuff)
   $_SESSION['docroot_url'] = $_SERVER['HTTP_HOST'].str_replace( $clipknown, "", $_SERVER['PHP_SELF'] );

   # Define path to interface graphics directory
   $_SESSION['icon_dir'] = $_SESSION['docroot_url'].DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."display_elements".DIRECTORY_SEPARATOR."graphics".DIRECTORY_SEPARATOR;
}


# Emulate register_globals = 1!
include($_SESSION['docroot_path']."/sohoadmin/includes/emulate_globals.php");

//echo "[".$_SESSION['this_ip']."]<br>";
########################################################
### READ ISP.CONF.PHP CONFIGURATION VARIABLE FILE	  ###
########################################################
$filename = $_SESSION['docroot_path']."/sohoadmin/config/isp.conf.php";		// This server should be setup; if not; let's set it up.

if ($file = fopen("$filename", "r")) {
	$body = fread($file,filesize($filename));
	$lines = split("\n", $body);
	$numLines = count($lines);
	$key = '';
	for ($x=2;$x<=$numLines;$x++) {

		// --------------------------------------------------------------
		// Session Register all Variables contained inside isp.conf file
		// --------------------------------------------------------------

		if (!preg_match("/^#/", $lines[$x])) {
			$variable = strtok($lines[$x], "=");
			$value = strtok("\n");
			$value = rtrim($value);

			${$variable} = $value;
			$_SESSION[$variable] = $value;
		}
	}

	fclose($file);
	
	if($key == ''){
		$gta=explode(' ', microtime());
		$key =  md5($gta['1'].str_replace('0.', '', $gta['0']));
		$_SESSION['key'] = $key;
		$body = str_replace("\ndb_server=", "\nkey=".$key."\n\ndb_server=", $body);
		$file = fopen("$filename", "w");
		fwrite($file, $body);
		fclose($file);
	}
	

	$user_table = "login";
	$com_key = "pro";

	// -----------------------------------------------------------------------------
	// If the config file did not exist; let the user configure the setup via a GUI
	// -----------------------------------------------------------------------------

	if (eregi("IIS", $SERVER_SOFTWARE) && isset($windir)) {
		$WINDIR = $windir;
		$_SESSION['WINDIR']=$WINDIR;
	}

} else {
   echo "\n\n\n\n\n\n\n\n";
   echo "<!===============================================>-\n";
   echo "<!-------Unable to read config file-------------->\n";
   echo "<!===============================================>\n";
   echo "\n\n\n\n\n\n\n\n";

} // End If File Open

//echo "[".$_SESSION['this_ip']."] - (".$this_ip.")<br>";


########################################################
### READ HOST.CONF.PHP CONFIGURATION VARIABLE FILE	  ###
########################################################
$filename = "config/host.conf.php";		// This server should be setup; if not; let's set it up.

if ($file = fopen("$filename", "r")) {
	$body = fread($file,filesize($filename));
	$lines = split("\n", $body);
	$numLines = count($lines);

	for ($x=0;$x<=$numLines;$x++) {

		// --------------------------------------------------------------
		// Session Register all Variables contained inside host.conf file
		// --------------------------------------------------------------

		if (!preg_match("/^#/", $lines[$x])) {
			$variable = strtok($lines[$x], "=");
			$value = strtok("\n");
			$value = rtrim($value);

			${$variable} = $value;
			$_SESSION[$variable] = $value;
		}
	}

	fclose($file);

} // End If File Open

$_SESSION['docroot_url'] = $this_ip;
$_SESSION['this_ip'] = $this_ip;

if ( $custom_hostname != "yes" ) {
   //$this_ip = $_SESSION['docroot_url'];
}

if ( $custom_docroot != "yes" ) {
   $doc_root = $_SESSION['docroot_path'];
}

if ( $_SESSION['skin'] == "" ) {
   $_SESSION['skin'] = "default";
}

//echo "[".$_SESSION['this_ip']."] - (".$this_ip.")<br>";

# Force redirect login screen to this_ip
if ( $_SESSION['this_ip'] != $_SERVER['HTTP_HOST'] && $_SERVER['PHP_SELF'] == "/sohoadmin/index.php" ) {
   //echo "location: http://".$_SESSION['this_ip'].$_SERVER['PHP_SELF']; exit;
   //header("location:http://".$_SESSION['this_ip'].$_SERVER['PHP_SELF']);
}

# Register language strings in session
if ( !is_array($_SESSION['lang']) ) {
   $_SESSION['lang_dir'] = $lang_dir;
   $_SESSION['lang_set'] = $lang_set;
   include_once($_SESSION['lang_dir']."/".$_SESSION['lang_set']);
   $_SESSION['lang'] = $lang;
}


//if ( isset($_SESSION['lang']) ) {
//   include_once($_SESSION['lang_dir']."/".$_SESSION['lang_set']);
//
//} else {
//   # Register language strings in session
//   $_SESSION['lang_dir'] = $lang_dir;
//   $_SESSION['lang_set'] = $lang_set;
//   include_once($_SESSION['lang_dir']."/".$_SESSION['lang_set']);
//   $_SESSION['lang'] = $lang;
//}


?>