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
track_vars;
reset($_POST);
require_once("sohoadmin/client_files/pgm-site_config.php");
require_once("sohoadmin/program/includes/shared_functions.php");

//echo testArray($_POST);
?>

<script type="text/javascript">
function redirectback() {
	window.location.href = '<?php echo pagename($_GET['mailpage']); ?>';
}
</script>


<?php
if ( $_POST['emailcoming'] == '1' ) {
	echo "<script type=\"text/javascript\">window.location.href='mailto:".$_POST['sendto']."?subject=Thought you might like this!&body=".$_POST['message']."';redirectback();</script>";
}

//echo '<p>['.pagename($_GET['mailpage']).']</p>';

$mailpage=$_GET['mailpage'];
if($mailpage != ''){
	$pr=$_GET['mailpage'];
} else {
	$mailpage=$_POST['mailpage'];
	$pr=$_POST['mailpage'];
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
###### SEND EMAIL	(After the form has been processed and this script is then recalled)
#######################################################################################################

###########################################################################################
##### CALL TEMPLATE BUILDER AND PRESENT INITIAL FORM TO WEB VISITOR
###########################################################################################

$module_active = "yes";				// Make sure to leave #CONTENT# variable intact when returning header/footer var
include_once('sohoadmin/client_files/pgm-realtime_builder.php');

echo ("$template_header\n");			// Go ahead and display header now


#######################################################################################################
###### PRESENT EMAIL ITEM TO A FRIEND FORM
#######################################################################################################

//if ($emailcoming == "") {
	$mailpage = htmlspecialchars($_REQUEST['mailpage']);
	
	$contentarea = '';
	
	if ( $emailcoming == "" ) {
		$contentarea .= "<iframe name=\"myiframe\" id=\"myiframe\" FRAMEBORDER=\"0\" scrolling=\"no\" src=\"spacer.gif\" style=\"visibility: hidden;overflow: hidden; border:0px solid red; height:22px; width:550px; padding:0;\"></iframe>\n";
	}

	$contentarea .= "<center><table id=\"email_friend-outer\" border=0 align=center cellpadding=0 cellspacing=0 width=\"90%\"><tr><td><table border=0 cellpadding=4 cellspacing=0 width=100% bgcolor=slategray align=center><tr><td align=left valign=middle>\n";
	$contentarea .= "<font face=Verdana, Arial, sans-serif size=2 color=white><B>&nbsp;".lang("Email this page to a friend")."...</B></font></td></tr></table>\n";

	$contentarea .= "<form method=\"post\" action=\"pgm-email_friend.php\" onSubmit=\"setTimeout('redirectback()', 2000);\" target=\"myiframe\">\n";
//	$contentarea .= "<form method=\"post\" action=\"pgm-secure_login.php\" onSubmit=\"redirectback();\" target=\"myiframe\">\n";
//	$contentarea .= "<input type=hidden name=customernumber value=\"$customernumber\">\n";
	$contentarea .= "<input type=hidden name=\"emailcoming\" value=\"1\">\n";
	$contentarea .= "<input type=hidden name=mailpage value=\"$mailpage\">\n";

	$contentarea .= "<div style=\"display:none;\">\n";
	$contentarea .= "<input type=\"text\" name=\"email_field\" value=\"\">\n";
	$contentarea .= "</div>\n";

	$contentarea .= "<table id=\"email_friend-inner\" border=0 cellpadding=4 cellspacing=0 width=100%><tr>\n";
	$contentarea .= "<td align=right valign=middle><font face=Verdana, Arial, sans-serif size=2>".lang("Your Name").":</td><td align=left valign=middle><input type=text class=cinput size=22 name=yourname style='width: 300px;'></td></tr>\n";
	$contentarea .= "<tr><td align=right valign=middle><font face=Verdana, Arial, sans-serif size=2>".lang("Your Email Address").":</td><td align=left valign=middle><input type=text class=cinput size=22 name=from style='width: 300px;'></td></tr>\n";
	$contentarea .= "<tr><td align=right valign=middle><font face=Verdana, Arial, sans-serif size=2>".lang("Friends Email Address").":</td><td align=left valign=middle><input type=text class=cinput size=22 name=sendto style='width: 300px;'></td></tr>\n";
	$contentarea .= "<tr><td align=right valign=top><font face=Verdana, Arial, sans-serif size=2>".lang("Personal Message").":</td><td align=left valign=top><textarea name=message cols=25 rows=8 wrap=virtual class=\"textfield\" style='width: 300px;'></textarea></td></tr>\n";
	$contentarea .= "<tr><td align=center colspan=2><input type=submit class=FormLt1 value=\"".lang("Send Now")."!\"></td></tr></table></form></td></tr></table></center>\n";

	// Log this calendar view into stats
	// -----------------------------------------------------------------

	if (file_exists("sohoadmin/client_files/pgm-site_stats.inc.php")) {		// Check; this mod N/A in Lite Version
		$statpage = lang("Email a Friend");
		include ("sohoadmin/client_files/pgm-site_stats.inc.php");
	}

	// -----------------------------------------------------------------

//}

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