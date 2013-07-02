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

if (!isset($nosessionkill)) { session_cache_limiter('none'); }

# Allows site developer to use custom session objects
# If you utilize this feature, make sure you call session_start() in the session_object_includes.php file
$sobj_incfile = "media/session_object_includes.php";
if ( file_exists($sobj_incfile) ) {
   include_once($sobj_incfile);
} else {
   session_start();
}

error_reporting(E_PARSE);

##########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE ###
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE      ###
##########################################################################
include("sohoadmin/client_files/pgm-site_config.php");
$dot_com = $this_ip;	// Assign dot_com variable to configured ip address

# Include global php functions
include_once("sohoadmin/program/includes/shared_functions.php");

###############################################################################
### Update "Current Site Users" Log file (How many people are on site now?) ###
###############################################################################
include_once("sohoadmin/client_files/pgm-numusers.php");


# MAKE pageRequest VAR AND pr VAR MATCH	(Can use either to call page)
# This is to maintain backwards compatibility with v3 templates and such
if ( $pr == "" ) { $pr = startpage(); }
$pageRequest = $pr;


###########################################################################
### THE pgm-realtime_builder.php FILE COMPILES THE TEMPLATE DATA AND PAGE
### CONTENT DATA TOGETHER AND PUTS IT OUT AS THE $template_header AND
### $template_footer VARS RESPECTIVELY.  ANY MODIFICATION TO CHANGE THE
### WAY PAGES ARE OUTPUT TO THE SITE VISITOR SHOULD BE MADE WITHIN THE
### realtime_builder.php FILE
###########################################################################
eval(hook("index.php:b4_realtimebuilder"));
include_once('sohoadmin/client_files/pgm-realtime_builder.php');


#######################################################

echo "$template_header\n\n";
//	echo "\n\n<SCRIPT language=Javascript>\n     window.focus();\n</SCRIPT>\n\n";
echo "$template_footer\n\n";

###########################################################################
### INCLUDE SITE STATISTICS CALCULATIONS FOR THIS PAGE REQUEST          ###
###########################################################################

// For V4 modification, this should take place AFTER the realtime_builder
// include.  That way, IF the $statpage var is set within a page; it will
// take over for the $pageReqest variable and not count "this page" on top
// of the "forced" log page setting.

if (file_exists("sohoadmin/client_files/pgm-site_stats.inc.php")) {		// Check; this mod N/A in Lite Version
	if ($statpage == "") {							// Don't Log this page if another mod is calling this stat
		include ("sohoadmin/client_files/pgm-site_stats.inc.php");
	}
}

exit;

?>
