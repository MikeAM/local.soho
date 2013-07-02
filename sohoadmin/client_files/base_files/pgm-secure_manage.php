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

$pr = "Manage Account";
reset($_POST);
while (list($name, $value) = each($_POST)) {
		$value = htmlspecialchars($value);	// Bugzilla #13
		${$name} = $value;
}

##########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE ###
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE      ###
##########################################################################

include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");
$dot_com = $this_ip;

#########################################################
### MAKE pageRequest VAR AND pr VAR MATCH			###
#########################################################

if ($pageRequest == "" && $pr != "") { $pageRequest = $pr; }

$site_title = strtoupper($SERVER_NAME);

#######################################################################################################
###### Start the edit data form now
#######################################################################################################

if ($ACTION == "") {

		$THIS_DISPLAY = "<div align=left class=text>\n";

//		# TESTING
//		$THIS_DISPLAY .= "<p>MD5CODE = [".$_SESSION['MD5CODE']."]</p>";

		//$result = mysql_query("SELECT * FROM sec_users WHERE MD5CODE = '$MD5CODE'");
		$result = mysql_query("SELECT * FROM sec_users WHERE USERNAME = '".$_SESSION['SOHO_AUTH']."' AND PASSWORD = '".$_SESSION['SOHO_PW']."' limit 1");
		$USER_DATA = mysql_fetch_array($result);
		$_SESSION['sec_user_prikey'] = $USER_DATA['PRIKEY'];
		$PRIKEY = $USER_DATA['PRIKEY'];
		$OLDPW = $USER_DATA['PASSWORD'];
		if ($err_rtn == 1) {
			$RETURN_MSG = "<BR><FONT COLOR=RED>".lang("Your login password does not match")."<BR>".lang("your verification password. Please re-enter.")."</FONT>";
		}

		if ($err_rtn == 2) {
			$RETURN_MSG = "<BR><FONT COLOR=RED>".lang("One or more fields were left blank or are too short.")."<br> ".lang("All fields must have at least 5 characters.")."<FONT>";
		}

		if ($err_rtn == 3) {
			$RETURN_MSG = "<BR><FONT COLOR=DARKGREEN>".lang("Your authentication data has been updated")."!</FONT>\n";
		}


		$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"pgm-secure_manage.php\">\n\n";

			$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=8 CELLSPACING=0 ALIGN=CENTER CLASS=text STYLE='border: 1px inset black;'>\n";
			$THIS_DISPLAY .= "<TR>\n";
			$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=#708090 WIDTH=95%><FONT COLOR=WHITE>\n";
			$THIS_DISPLAY .= "<B>".lang("Manage Authenticated User Account")."\n";
			$THIS_DISPLAY .= "</TD></TR>\n";
			$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=TOP BGCOLOR=WHITE CLASS=text>\n";
			$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=customernumber VALUE=\"$customernumber\">\n";

				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"ACTION\" VALUE=\"PROCESS_REQ\">\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"SEC_CHKSUM\" VALUE=\"$PHPSESSID\">\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"OLDPW\" VALUE=\"$OLDPW\">\n";
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"PRIKEY\" VALUE=\"$PRIKEY\">\n";
				$THIS_DISPLAY .= "<B>".lang("Your Name").":</B><BR><INPUT TYPE=TEXT NAME=\"NOWNER_NAME\" VALUE=\"".$USER_DATA['OWNER_NAME']."\" class=\"text\" style='width: 275px;'><BR><BR>\n";
				$THIS_DISPLAY .= "<B>".lang("Your Email Address").":</B><BR><INPUT TYPE=TEXT NAME=\"NOWNER_EMAIL\" VALUE=\"$USER_DATA[OWNER_EMAIL]\" CLASS=text STYLE='width: 275px;'><BR><BR>\n";
				//$THIS_DISPLAY .= "<B>".lang("Login Username").":</B><BR><INPUT TYPE=TEXT NAME=\"NUSERNAME\" VALUE=\"$USER_DATA[USERNAME]\" CLASS=text STYLE='width: 275px;'><BR><BR>\n";
				$THIS_DISPLAY .= "<INPUT TYPE=hidden NAME=\"NUSERNAME\" VALUE=\"$USER_DATA[USERNAME]\">\n";
				$THIS_DISPLAY .= "<B>".lang("Login Password").":</B><BR><INPUT TYPE=PASSWORD NAME=\"NPASSWORD\" VALUE=\"\" CLASS=text STYLE='width: 275px;'><BR><BR>\n";
				$THIS_DISPLAY .= "<B>".lang("Verify Password").":</B><BR><INPUT TYPE=PASSWORD NAME=\"VPASSWORD\" VALUE=\"\" CLASS=text STYLE='width: 275px;'><BR><BR>\n";

				// $THIS_DISPLAY .= "$MD5CODE<BR>$USER_DATA[MD5CODE]<BR><BR>\n";

			$THIS_DISPLAY .= "<DIV ALIGN=CENTER><INPUT TYPE=SUBMIT VALUE=\" ".lang("Update Your Data")." \" CLASS=FormLt1 STYLE='CURSOR: HAND;'><BR>$RETURN_MSG</DIV>\n";
			$THIS_DISPLAY .= "</td></tr></TABLE>\n";


		$THIS_DISPLAY .= "</FORM>\n\n</DIV>";

} // End Action = NULL

if ($ACTION == "PROCESS_REQ") {
	$PRIKEY = $_POST['PRIKEY'];
	$OLDPW = $_POST['OLDPW'];
	if ($NPASSWORD == "" && $VPASSWORD == "") {
		$NPASSWORD = $OLDPW;
		$VPASSWORD = $OLDPW;
	}
			// ---------------------------------------------------------------
			// Let's do some general error and security checking first
			// ---------------------------------------------------------------

			// 1. Does the check sum session id match the current session?
			// This tells us that the update info request has come within
			// the last 30 minutes and someone else is not trying to "hack"
			// this update routine
			// ----------------------------------------------------------------

			if ($SEC_CHKSUM != $PHPSESSID) { echo "<H4>ERROR 401: AUTHORIZATION REQUIRED."; exit; }

			// 2. Lets make sure the new password and verify password match
			// as well as not allowing "blank" passwords in system
			// otherwise, the user may not truly know what his/her new pw is
			// ----------------------------------------------------------------

			if ($NPASSWORD != $VPASSWORD || $NPASSWORD == "" || $VPASSWORD == "") {
					header("Location: pgm-secure_manage.php?err_rtn=1&".SID);
					exit;
			}

			// 3. Just make sure that all fields have data in them now.
			// ----------------------------------------------------------------

			if (strlen($NOWNER_NAME) < 2 || strlen($NUSERNAME) < 4) {
					header("Location: pgm-secure_manage.php?err_rtn=2&".SID);
					exit;
			}

			// ---------------------------------------------------------------
			// Now we are ready to update this user's data in the system
			// ---------------------------------------------------------------
			$PRIKEY = $_SESSION['sec_user_prikey'];
			mysql_query("UPDATE sec_users SET OWNER_NAME = '$NOWNER_NAME', OWNER_EMAIL = '$NOWNER_EMAIL', PASSWORD = '$NPASSWORD' WHERE PRIKEY = '$PRIKEY'");
			
			//mysql_query("UPDATE sec_users SET OWNER_NAME = '$NOWNER_NAME', OWNER_EMAIL = '$NOWNER_EMAIL', USERNAME = '$NUSERNAME', PASSWORD = '$NPASSWORD' WHERE PRIKEY = '$PRIKEY'");

				$_SESSION['OWNER_NAME'] = $NOWNER_NAME;
				$_SESSION['OWNER_EMAIL'] = $NOWNER_EMAIL;
				//$_SESSION['SOHO_AUTH'] = $NUSERNAME;
				$_SESSION['SOHO_PW'] = $NPASSWORD;

			header("Location: pgm-secure_manage.php?err_rtn=3&".SID);
			exit;


} // End Process Action Request

#######################################################################################################
##### BUILD PAGE AND DISPLAY CONTENT NOW
#######################################################################################################

$module_active = "yes";				// Make sure to leave #CONTENT# variable intact when returning header/footer var
include_once("sohoadmin/client_files/pgm-realtime_builder.php");	

echo ("$template_header\n");			// Go ahead and display header now

#######################################################################################################

// **************************************************************************
// Replace intact #CONTENT# var with $contentarea created within this script
// **************************************************************************

$contentarea = $THIS_DISPLAY;

$template_footer = eregi_replace("#CONTENT#", $contentarea, $template_footer);

// **************************************************************************
// Display template footer var from realtime_builder and close out this page
// **************************************************************************

echo ("$template_footer\n");

exit;

?>