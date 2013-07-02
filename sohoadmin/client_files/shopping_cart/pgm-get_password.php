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
session_cache_limiter('none'); 
session_start();	
track_vars;

$THIS_DISPLAY = "";		// Make Display Variable Blank in Case of Session Memory

#################################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE  
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE      
#################################################################################

include("pgm-cart_config.php");
$dot_com = $this_ip;	// Assign dot_com variable to configured ip address

foreach($_REQUEST as $name=>$value){
	$value = stripslashes($value);
	$value = htmlspecialchars($value);	// Bugzilla #13
	${$name} = $value;
	$_POST[$name] = $value;
	$_GET[$name] = $value;
	$_REQUEST[$name] = $value;
}


#################################################################################
### READ DATABASED OPTIONS INTO MEMORY NOW
#################################################################################

$result = mysql_query("SELECT * FROM cart_options");
$OPTIONS = mysql_fetch_array($result);

#################################################################################
### STEP 1: IF ACTION != "PROCESS_REQ" THEN WE ARE LOOKING FOR CUSTOMER INPUT
#################################################################################

if ($ACTION != "PROCESS_REQ") {

		$RETURN_MSG = "";

		if ($FOUND_FLAG == 1) {
			$RETURN_MSG .= "<BR><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]><B>".lang("Customer data successfully located.")." ".lang("You should receive an email within the next few minutes.")."</B></FONT>\n";
			$RETURN_MSG .= "<BR><BR><A HREF=\"pgm-checkout.php?customernumber=$customernumber&=SID\">".lang("Return To Checkout Login")."</a>\n";
		}

		if ($FOUND_FLAG == 0) {
			$RETURN_MSG .= "<BR><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]><B>".lang("Failed to locate email address; please try again or login as a new customer.")."</B></FONT>\n";
			$RETURN_MSG .= "<BR><BR><A HREF=\"pgm-checkout.php?customernumber=$customernumber&=SID\">".lang("Return To Checkout Login")."</a>\n";
		}

		if ($ACTION == "FIND") { $RETURN_MSG = ""; }

		// ----------------------------------------------------------
		// DISPLAY CHECKOUT ROUTINE STEPS FOR REFERENCE BY CUSTOMER
		// ----------------------------------------------------------

		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"100%\" class=\"shopping-selfcontained_box\" id=\"checkout-steps\">\n";

//		# Testing - echo Step number
//		$THIS_DISPLAY .= " <tr><td colspan=\"6\" align=\"center\">Step = (".$STEP.")</td></tr>\n";

		# Current Step
		$THIS_DISPLAY .= " <tr>\n";
 		$THIS_DISPLAY .= "  <th align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= "   <b>".lang("Step")." 1:<br/>".lang("Customer Sign-in")."</b>\n";
		$THIS_DISPLAY .= "  </th>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 2:<br/>".lang("Billing & Shipping")."<br/>".lang("Information")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 3:<br/>".lang("Shipping Options")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 4:<br/>".lang("Verify Order Details")."<br/>\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 5:<br/>".lang("Make Payment")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\">\n";
		$THIS_DISPLAY .= lang("Step")." 6:<br/>".lang("Print Final")."<br/>".lang("Invoice")."\n";
		$THIS_DISPLAY .= "  </td>\n";

		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= "</table><br/>\n";


		// ----------------------------------------------------------

		$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% CLASS=text STYLE='border: 1px inset black;'>\n";
		$THIS_DISPLAY .= "<TR>\n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=95%><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";
		$THIS_DISPLAY .= "<B>ONLINE CUSTOMER SERVICE</B><BR>".lang("Follow the instructions below to resolve your issue quickly.")."\n";
		$THIS_DISPLAY .= "</TD></TR></TABLE><BR>\n\n";

		$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"pgm-get_password.php\">\n\n";

		$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100% CLASS=text>\n";
		$THIS_DISPLAY .= "<TR>\n";
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text WIDTH=50%>\n";

			$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=0 WIDTH=100% CLASS=text STYLE='border: 1px inset black;'>\n";
			$THIS_DISPLAY .= "<TR>\n";
			$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=95%><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";
			$THIS_DISPLAY .= "<B>".lang("Find Username and Password for Login")."\n";
			$THIS_DISPLAY .= "</TD></TR>\n";
			$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=TOP  CLASS=text BGCOLOR=$OPTIONS[DISPLAY_HEADERBG] WIDTH=95%><FONT COLOR=$OPTIONS[DISPLAY_HEADERTXT]>\n";
			$THIS_DISPLAY .= lang("Your username and password was displayed on the invoice of your first order with us.")." ".lang("If you have the email or a printed copy handy, it may expedite your request.")."<BR><BR>\n";
			$THIS_DISPLAY .= lang("Otherwise, please enter your email address in the space below.")." ".lang("We will locate your username and password in our database and instantly send an email to")." \n";
			$THIS_DISPLAY .= lang("the address that matches your input.")."  ".lang("Thank you for being a valued return customer.")."<BR><BR>\n";
			$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=customernumber VALUE=\"$customernumber\"><INPUT TYPE=HIDDEN NAME=\"ACTION\" VALUE=\"PROCESS_REQ\">\n";
			$THIS_DISPLAY .= "<DIV ALIGN=CENTER><INPUT TYPE=TEXT NAME=\"find_login\" CLASS=text STYLE='width: 275px;'> <INPUT TYPE=SUBMIT VALUE=\" ".lang("Find Now")." \" CLASS=FormLt1 STYLE='CURSOR: HAND;'><BR>$RETURN_MSG</DIV>\n";
			$THIS_DISPLAY .= "<BR>&nbsp;</font></td></tr></TABLE>\n";

		$THIS_DISPLAY .= "</TD>\n";
		$THIS_DISPLAY .= "</TR></TABLE>\n";

		$THIS_DISPLAY .= "</FORM>\n\n";


} else {					// Try and locate user email address and email info; else err

	$FOUND_FLAG = 0;
	$find_login = eregi_replace('[^a-zA-Z0-9\.@]', '', stripslashes($find_login));
	$find_login = strtoupper($find_login);
	$result = mysql_query("SELECT USERNAME, PASSWORD, BILLTO_EMAILADDR, BILLTO_FIRSTNAME, BILLTO_LASTNAME FROM cart_customers WHERE UPPER(USERNAME) = '$find_login'");
 	$find_results = mysql_num_rows($result);

	if ($find_results > 0) {	// We found customer... Yea Come-on!

		$this_customer = mysql_fetch_array($result);
		
		// Build Quick and Dirty Text Email to Send Now!

		$EMAIL = "$this_customer[BILLTO_FIRSTNAME],\n\n";
		$EMAIL .= lang("We have received your request for a lost username and")."\n";
		$EMAIL .= lang("password and have located that information in our system.")."\n";
		$EMAIL .= lang("They are as follows").":\n\n";

		$EMAIL .= lang("Username").": $this_customer[USERNAME]\n";
		$EMAIL .= lang("Password").": $this_customer[PASSWORD]\n\n";

		$EMAIL .= lang("Thank you for being a loyal prefered customer.")."\n";
		$EMAIL .= lang("We look forward to continuing to serve you in the future.")."\n\n\n\n";
		$EMAIL .= "** ".lang("This is an automated email from")." $SERVER_NAME. \n";
		$EMAIL .= lang("Please DO NOT REPLY to this email.")."\n\n\n";

		// Email user information now

		mail("$this_customer[USERNAME]", "$SERVER_NAME customer data", "$EMAIL", "From: webmaster@$SERVER_NAME");

		$FOUND_FLAG = 1;

	}

	header("Location: pgm-get_password.php?customernumber=$customernumber&FOUND_FLAG=$FOUND_FLAG&=SID");
	exit;

}	// End process

#################################################################################
### BUILD OVERALL TABLE TO PLACE SEARCH COLUMN TO THE LEFT OR RIGHT OF
### SEARCH RESULT DISPLAY AS DEFINED IN DISPLAY OPTIONS
#################################################################################

$FINAL_DISPLAY = "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 WIDTH=612 ALIGN=CENTER>\n";

$FINAL_DISPLAY .= "<TR>\n";

	if (eregi("L", $OPTIONS[DISPLAY_COLPLACEMENT] )) {

		$FINAL_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP>\n\n$THIS_DISPLAY\n\n</TD>\n";

	} else {

		$FINAL_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP>\n\n$THIS_DISPLAY\n\n</TD>\n\n";

	}

$FINAL_DISPLAY .= "</TR>\n\n";

$FINAL_DISPLAY .= "</TABLE>";


#################################################################################
### THE pgm-template_builder.php FILE COMPILES THE TEMPLATE DATA AND PAGE 
### CONTENT DATA TOGETHER AND PUTS IT OUT AS THE $template_header AND 
### $template_footer VARS RESPECTIVELY.  
#################################################################################

$module_active = "yes";
include ("pgm-template_builder.php");

#################################################################################

echo ("$template_header\n");

	$template_footer = eregi_replace("#CONTENT#", $FINAL_DISPLAY, $template_footer);

echo ("$template_footer\n\n");

echo ("\n\n<SCRIPT language=Javascript>\n     window.focus();\n</SCRIPT>\n\n");

exit;

?>