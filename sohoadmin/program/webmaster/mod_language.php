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

error_reporting(E_PARSE);
session_start();
include($_SESSION['product_gui']);

	unset($lang);
	unset($language);

	$language = $new_lang;
	
	$language = rtrim($language);
	$language = ltrim($language);
	
// ----------------------------------------------------
// Configure System Variables for Language Version
// ----------------------------------------------------

	// Update site_specs table
   mysql_query("UPDATE site_specs SET df_lang = '$language'");
	
	// Reload Tool Window Now
	header("Location: ../../index.php?=SID");
	//echo "new_lang: ($new_lang) | language: ($language)\n";
	exit;
	
?>