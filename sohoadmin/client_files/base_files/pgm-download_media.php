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
error_reporting(0);
track_vars;

# Prevent isp.conf.php download exploit
$name = basename($_GET['name']);

//echo urlencode($name);

$local_name = strtoupper($_GET['name']);
$name = urldecode($name);
$name = str_replace('/', '', $name);
$name = str_replace('\\', '', $name);
$name = str_replace("'", '', $name);
$name = str_replace('"', '', $name);
$name = str_replace('$', '', $name);
$download_file = "media/".$name;
if(file_exists($download_file)){

	if ( filesize($download_file) > 16123759 || eregi('.pdf', $download_file) ) {
	   header("location: $download_file"); exit;
	}
	
	$fp = fopen("$download_file","r");
	$buff = fread($fp,filesize($download_file));
	
	Header("Content-Type: application/x-octet-stream");
	Header( "Content-Disposition: attachment; filename=\"$local_name\"");
	echo $buff;
} else {
	exit;	
}

?>