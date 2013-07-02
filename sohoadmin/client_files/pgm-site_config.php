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

# Allows site developer to use custom session objects
# If you utilize this feature, make sure you call session_start() in the session_object_includes.php file

$sobj_incfile = "media/session_object_includes.php";
if ( file_exists($sobj_incfile) ) {
   include_once($sobj_incfile);
} else {
   session_start();
}

error_reporting(0);

## Pull 100% legit docroot from path to this script
//==============================================================

# Build known aspects of path to clip
$clipknown = DIRECTORY_SEPARATOR.'sohoadmin'.DIRECTORY_SEPARATOR.'client_files'.DIRECTORY_SEPARATOR.basename(__FILE__);
# Define full docroot path from root (for php stuff)
$_SESSION['docroot_path'] = str_replace( $clipknown, "", __FILE__ );

# Define domain root path (for html stuff)
$clipurl = "/index.php";

# Define full path to core product include script
$_SESSION['product_gui'] = $_SESSION['docroot_path'].DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."product_gui.php";

include_once($_SESSION['docroot_path'].DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."config.php");
include_once($_SESSION['docroot_path'].DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."db_connect.php");

include_once($_SESSION['docroot_path'].DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."emulate_globals.php");


?>