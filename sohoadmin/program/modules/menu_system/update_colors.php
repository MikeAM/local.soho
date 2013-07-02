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

session_start();
require_once('../../includes/product_gui.php');
eval(hook("update_colors.php:save_menu_colors"));

#########################################################
### FIRST PARSE ALL VARIABLES THAT ARE SENT TO SCRIPT ###	
#########################################################

$customTxtBtn = str_replace("#", "", $customTxtBtn);
$customBtn = str_replace("#", "", $customBtn);

#########################################################

$filename = "$cgi_bin/menucolor.conf";

#########################################################

 	$file = fopen("$filename", "w");
 		fwrite($file, "linkc=$customTxtBtn\n");
		fwrite($file, "menubg=$customBtn\n");
 		fwrite($file, "menufont=\n");
		fwrite($file, "sound=\n");	
 	fclose($file);

#########################################################

header("Location: ../auto_menu_system.php?cok=1&=SID");
exit;

?>
