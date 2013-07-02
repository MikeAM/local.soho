<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

//echo "something"; exit;
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
session_cache_limiter('none'); 
session_start();
track_vars;
include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");
$pr = "Recover your password";
reset($HTTP_POST_VARS);
while (list($name, $value) = each($HTTP_POST_VARS)) {
		$value = htmlspecialchars($value);	// Bugzilla #13
		${$name} = $value;
}

##########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE ###
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE      ###
##########################################################################

$dot_com = $this_ip;

#########################################################
### MAKE pageRequest VAR AND pr VAR MATCH			###
#########################################################

if ($pageRequest == "" && $pr != "") { $pageRequest = $pr; }

$site_title = strtoupper($SERVER_NAME);

#######################################################################################################
###### Process "Find" Request									  
#######################################################################################################

if ($ACTION == "PROCESS_REQ") {

	$FOUND_FLAG = 0;

	$find_login = strtoupper($find_login);
	$find_login = chop($find_login);		// Format email address for proper search

	$result = mysql_query("SELECT USERNAME, PASSWORD, OWNER_NAME FROM sec_users WHERE UPPER(OWNER_EMAIL) = '$find_login'");
 	$find_results = mysql_num_rows($result);

	if ($find_results > 0) {	// We found customer... Yea Come-on!

		$this_customer = mysql_fetch_array($result);
		
		// Build Quick and Dirty Text Email to Send Now!

		$EMAIL = "$this_customer[OWNER_NAME]:\n\n";
		$EMAIL .= lang("Here is the username and password associated with your email address").":\n\n";

		$EMAIL .= lang("Username").": $this_customer[USERNAME]\n";
		$EMAIL .= lang("Password").": $this_customer[PASSWORD]\n\n";

		$EMAIL .= "** ".lang("This is an automated email from")." $SERVER_NAME. ".lang("Please")."\n";
		$EMAIL .= lang("DO NOT REPLY to this email").".\n\n\n";

		// Email user information now

		$find_login = strtolower($find_login);

		mail("$find_login", "$SERVER_NAME Login Data", "$EMAIL", "From: webmaster@$SERVER_NAME");

		$FOUND_FLAG = 1;

	}

	if ($FOUND_FLAG == 1) {
		$RETURN_MSG .= "<BR><FONT COLOR=DARKBLUE><B>".lang("Customer data found successfully").". ".lang("You should receive")."<BR />".lang("an email within the next few minutes").".</B></FONT>\n";
		$RETURN_MSG .= "<BR><BR><A HREF=\"pgm-secure_login.php?customernumber=$customernumber&=SID\">".lang("Go To Login")."</a>\n";
	}

	if ($FOUND_FLAG == 0) {
		$RETURN_MSG .= "<BR><FONT COLOR=RED><B>".lang("We were unable to locate that email address in")."<BR>".lang("our customer database; please try again").".</B></FONT>\n";
	}

} // End Process Request

#######################################################################################################
###### Show "Find" Form										  
#######################################################################################################
$module_active = "yes";				// Make sure to leave #CONTENT# variable intact when returning header/footer var
include_once('sohoadmin/client_files/pgm-realtime_builder.php');

		$contentarea .= "<FORM METHOD=POST ACTION=\"pgm-secure_remember.php\">\n\n";

		$contentarea .= "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=450 CLASS=text>\n";
		$contentarea .= "<TR>\n";
		$contentarea .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text WIDTH=50%>\n";

			$contentarea .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% CLASS=text STYLE='border: 1px inset black;'>\n";
			$contentarea .= "<TR>\n";
			$contentarea .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=#EFEFEF WIDTH=95%><FONT COLOR=BLACK>\n";
			$contentarea .= "<B>".lang("Forgotten Login")."\n";
			
			$contentarea .= "</TD></TR>\n";
			$contentarea .= "<TR><TD ALIGN=LEFT VALIGN=TOP BGCOLOR=WHITE CLASS=text>\n";
			$contentarea .= lang("Please enter your email address in the space below and we will locate your username and password in our database.  Once located, we will instantly send an email to")." \n";
			$contentarea .= lang("the address that matches your input.  Thank you.")."<BR><BR>\n";
			$contentarea .= "<INPUT TYPE=HIDDEN NAME=customernumber VALUE=\"$customernumber\"><INPUT TYPE=HIDDEN NAME=\"ACTION\" VALUE=\"PROCESS_REQ\">\n";
			$contentarea .= "<DIV ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"find_login\" CLASS=text STYLE='width: 275px;'> <INPUT TYPE=SUBMIT VALUE=\" ".lang("Find Now")." \" CLASS=FormLt1 STYLE='CURSOR: HAND;'><BR>$RETURN_MSG</DIV>\n";
			$contentarea .= "<BR>&nbsp;</td></tr></TABLE>\n";

		$contentarea .= "</TD>\n";
		$contentarea .= "</TR></TABLE>\n";

		$contentarea .= "</FORM>\n\n";



#######################################################################################################
##### BUILD PAGE AND DISPLAY CONTENT NOW
#######################################################################################################




echo ("$template_header\n");			// Go ahead and display header now

#######################################################################################################

// **************************************************************************
// Replace intact #CONTENT# var with $contentarea created within this script
// **************************************************************************

$template_footer = eregi_replace("#CONTENT#", $contentarea, $template_footer);

// **************************************************************************
// Display template footer var from realtime_builder and close out this page
// **************************************************************************

echo ("$template_footer\n");

exit;

?>
