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

set_time_limit(0);
//error_reporting(0);
session_start();

########################################################
### READ CONFIGURATION VARIABLE FILE		   		  ###
########################################################
/*
$filename = "config/isp.conf.php";		// This server should be setup; if not; let's set it up.

if ($file = fopen("$filename", "r")) {
	$body = fread($file,filesize($filename));
	$lines = split("\n", $body);
	$numLines = count($lines);

	for ($x=2;$x<=$numLines;$x++) {

		// --------------------------------------------------------------
		// Session Register all Variables contained inside isp.conf file
		// --------------------------------------------------------------

		if (!eregi("#", $lines[$x])) {
			$temp = split("=", $lines[$x]);
			$variable = $temp[0];
			$value = $temp[1];
				$value = chop($value);
				$value = rtrim($value);
			
			${$variable} = $value;
		}
	}

	fclose($file);

	$user_table = "login";
	$com_key = "pro";

	// -----------------------------------------------------------------------------
	// If the config file did not exist; let the user configure the setup via a GUI
	// -----------------------------------------------------------------------------

}
*/
?>