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

	error_reporting(0);
	include_once("sohoadmin/client_files/pgm-site_config.php");
	include_once("sohoadmin/program/includes/shared_functions.php");
	$id = $_REQUEST['id'];
	if(!preg_match('/^[a-z0-9]{1,}$/i', $id) && $id!=""){
		exit;
	}
	
	$UPDATE_FLAG = 0;
	
	$result = mysql_query("SELECT EVENT_SECURITYCODE FROM calendar_events WHERE PRIKEY = '$id'");
	$tmp = mysql_fetch_array($result);

	if ($tmp[EVENT_SECURITYCODE] == "~~~PENDING~~~") {
		$UPDATE_FLAG = 1;
	}

	if ($UPDATE_FLAG == 1) {
		mysql_query("UPDATE calendar_events SET EVENT_SECURITYCODE = 'Public' WHERE PRIKEY = '$id'");
		$DISPLAY = "<BR><BR><BR><CENTER><DIV CLASS=text><h3>".lang("This event has been added to your calendar system.")."</h3></DIV></CENTER>\n";
	} else {
		$DISPLAY = "<BR><BR><BR><CENTER><DIV CLASS=text><FONT COLOR=MAROON>".lang("It appears this event has already been added to your system.")."</FONT></DIV></CENTER>\n";
	}
?>

<HTML>
<HEAD>
<TITLE>Confirm Calendar Event</TITLE>
<LINK HREF="runtime.css" REL="stylesheet" TYPE="text/css">
</HEAD>
<BODY BGCOLOR="#EFEFEF" TEXT="#000000" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<? echo $DISPLAY; ?>
</BODY>
</HTML>

