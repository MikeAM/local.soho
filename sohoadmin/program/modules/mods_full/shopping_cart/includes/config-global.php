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

# Emulate register_globals = 1!
if(1==2){
	
	include($_SESSION['docroot_path']."/sohoadmin/includes/emulate_globals.php");
	
	set_time_limit(0);
	error_reporting(0);
	session_start();
	
	########################################################
	### READ ISP.CONF.PHP CONFIGURATION VARIABLE FILE	  ###
	########################################################
	
	if ( !isset($_SESSION['docroot_path']) ) {
	   # Build known aspects of path to clip
	   $clipknown = "/sohoadmin/program/modules/mods_full/shopping_cart/includes/".basename(__FILE__);
	
	   # Define full docroot path from root (for php stuff)
	   $_SESSION['docroot_path'] = str_replace( $clipknown, "", __FILE__ );
	}
	
	$filename = $_SESSION['docroot_path']."/sohoadmin/config/isp.conf.php";		// This server should be setup; if not; let's set it up.
	
	if ($file = fopen("$filename", "r")) {
		$body = fread($file,filesize($filename));
		$lines = split("\n", $body);
		$numLines = count($lines);
	
		for ($x=2;$x<=$numLines;$x++) {
	
			// --------------------------------------------------------------
			// Session Register all Variables contained inside isp.conf file
			// --------------------------------------------------------------
	
			if (!eregi("#", $lines[$x])) {
				$variable = strtok($lines[$x], "=");
				$value = strtok("\n");
				$value = rtrim($value);
	
				$_SESSION[$variable] = $value;
				${$variable} = $value;
			}
		}
	
		fclose($file);
	
		$user_table = "login";
		$com_key = "pro";
	
		// -----------------------------------------------------------------------------
		// If the config file did not exist; let the user configure the setup via a GUI
		// -----------------------------------------------------------------------------
	
		if (preg_match("/IIS/i", $SERVER_SOFTWARE) && isset($windir)) {
			$WINDIR = $windir;
			$_SESSION['WINDIR']=$WINDIR;
		}
	
	} // End If File Open
	
	
	########################################################
	### READ HOST.CONF.PHP CONFIGURATION VARIABLE FILE	  ###
	########################################################
	$filename = $_SESSION['docroot_path']."/sohoadmin/config/host.conf.php";		// This server should be setup; if not; let's set it up.
	
	if ($file = fopen("$filename", "r")) {
		$body = fread($file,filesize($filename));
		$lines = split("\n", $body);
		$numLines = count($lines);
	
		for ($x=0;$x<=$numLines;$x++) {
	
			// --------------------------------------------------------------
			// Session Register all Variables contained inside host.conf file
			// --------------------------------------------------------------
	
			if (!eregi("#", $lines[$x])) {
				$variable = strtok($lines[$x], "=");
				$value = strtok("\n");
				$value = rtrim($value);
				$_SESSION[$variable] = $value;
				${$variable} = $value;
			}
		}
	
		fclose($file);
	
	} // End If File Open
	
	
	// What is current Actual Build Date
	$filename = 'build.dat.php';
	if (file_exists($filename)) {	
		$_SESSION['GLOBAL_BUILD_NUM'] = $GLOBAL_BUILD_NUM;
	    $GLOBAL_BUILD_NUM = date("ymd", filemtime($filename));
	}
	
	
	##################################################################################
	// Register session variables
	###===============================================================================
	$selSpecs = mysql_query("SELECT * FROM site_specs");
	$getSpec = mysql_fetch_array($selSpecs);
	
	if ($getSpec[df_lang] == "") {
	   $language = "english.php";
	} else {
	   $language = $getSpec[df_lang];
	   $language = rtrim($language);
	   $language = ltrim($language);
	}
	
	if ( $lang_dir == "" ) {
	   $lang_dir = "language";
	}
	
	$lang_include = "$lang_dir/$language";
	
	include_once("$lang_include");
	
	// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
	$btn_edit = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";
	$btn_build = "class=\"btn_build\" onMouseover=\"this.className='btn_buildon';\" onMouseout=\"this.className='btn_build';\"";
	$btn_save = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
	$btn_delete = "class=\"btn_delete\" onMouseover=\"this.className='btn_deleteon';\" onMouseout=\"this.className='btn_delete';\"";
	$nav_main = "class=\"nav_main\" onMouseover=\"this.className='nav_mainon';\" onMouseout=\"this.className='nav_main';\"";
	$nav_save = "class=\"nav_save\" onMouseover=\"this.className='nav_saveon';\" onMouseout=\"this.className='nav_save';\"";
	$nav_logout = "class=\"nav_logout\" onMouseover=\"this.className='nav_logouton';\" onMouseout=\"this.className='nav_logout';\"";
	
	$_SESSION['btn_edit'] = $btn_edit;
	$_SESSION['btn_build'] = $btn_build;
	$_SESSION['btn_save'] = $btn_save;
	$_SESSION['btn_delete'] = $btn_delete;
	$_SESSION['nav_main'] = $nav_main;
	$_SESSION['nav_save'] = $nav_save;
	$_SESSION['nav_logout'] = $nav_logout;
	$_SESSION['language'] = $language;
	$_SESSION['getSpec'] = $getSpec;
	foreach($lang as $lvar=>$lval){
		$_SESSION['lang'][$lvar]=$lval;
	}
	

}
?>