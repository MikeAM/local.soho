<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.6
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


function iniTest($setting, $setval, $color = "") {
   $oldval = ini_get($setting);
   ini_set($setting, $setval);
   $newval = ini_get($setting);

   echo "<span style=\"color: #8b8b8b;\">".$setting." [<span style=\"color: #999;\">".$oldval."</span>]</span><br>";
   echo "<span style=\"color: #000;\">".$setting."</span> [<span style=\"color: red;\">".$newval."</span>]<br><br>";
}


if (!file_exists("config/isp.conf.php")) {
	header("Location: setup.php");
	exit;
}


# Include core interface files!
if ( !include("program/includes/product_gui.php") ) {
   echo "\n\n\n\n <!---Could not include this file:<br>[".$product_gui."]----> \n\n\n\n";
}


error_reporting(E_PARSE);
#########################################################################################
###### DISPLAY LOADING SCREEN AND OPEN APPLICATION WINDOW.							      #####
#########################################################################################
header ("Location: http://".$_SESSION['this_ip']."/sohoadmin/version.php");
exit;


?>