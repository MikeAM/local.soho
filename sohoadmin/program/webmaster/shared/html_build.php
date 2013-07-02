<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch SMT                   Version 4.5.1      
##
## Copyright 1999-2003 Soholaunch.com, Inc. 					          
## Author: Mike Johnston [mike.johnston@soholaunch.com]                 
##					  
## Created: 12/12/1999           Last Modified: 2/2003	               
## Homepage:     			    http://www.soholaunch.com  		       
## Support:						http://devnet.soholaunch.com          
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
## and affiliates from any liability that might arise from its use.                                                      #  
##                                                                           
## Selling the code for this program without prior written consent is       
## expressly forbidden and in violation of Domestic and International 
## copyright laws.  		                                           
###############################################################################

set_time_limit(0);

#######################################################
### READ CONFIGURATION VARIABLE FILE		    ###
#######################################################	

$filename = "shared/html_skeleton.html";
$file = fopen("$filename", "r") or DIE("Error: Could not skeleton file.");
	$HTMLOUTPUT = fread($file,filesize($filename));
fclose($file);

// *****************************************************
// The display HTML is now in the variable $HTMLOUTPUT
// *****************************************************

// 1. Make the title of this module "Shopping Cart"
// -------------------------------------------------

$HTMLOUTPUT = eregi_replace("##TITLE##", "$MOD_TITLE", $HTMLOUTPUT);

// 2. Place the display variable we have just built into the proper location
// ---------------------------------------------------------------------------

$HTMLOUTPUT = eregi_replace("##MOD_DISPLAY##", "$THIS_DISPLAY", $HTMLOUTPUT);

// 3. Place the background image tag if it exists
// ---------------------------------------------------------------------------

if ($BG != "") {
	$HTMLOUTPUT = eregi_replace("##BG##", "BACKGROUND=$BG", $HTMLOUTPUT);
} else {
	$HTMLOUTPUT = eregi_replace("##BG##", "", $HTMLOUTPUT);
}

// 4. Echo the final result to the user
// ---------------------------------------------------------------------------

echo $HTMLOUTPUT;

?>
