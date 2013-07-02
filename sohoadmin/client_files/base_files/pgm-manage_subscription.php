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

reset($_POST);
$UNDO_EMAIL = $_REQUEST['UNDO_EMAIL'];
$UNDO_EMAIL = str_replace("'", '', str_replace('"', '', str_replace("\\", '', $UNDO_EMAIL)));
$UNDO_EMAIL = slashthis($UNDO_EMAIL);

$id = slashthis($_REQUEST['id']);

########################################################################

if ($id == "unsubscribe") {

	$THIS_URL = strtoupper($SERVER_NAME);

	echo "<HTML><HEAD><TITLE>SUBSCRIPTION CONTROL CENTER ($SERVER_NAME)</TITLE></HEAD>\n";
	echo "<BODY BGCOLOR=WHITE TEXT=BLACK LINK=RED ALINK=RED VLINK=RED>\n\n";
	echo "<BR><TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=550 STYLE='BORDER: 1px inset black;' ALIGN=CENTER>\n";
	echo "<TR><TD BGCOLOR=#708090 ALIGN=LEFT VALIGN=MIDDLE>\n";
	echo "<FONT SIZE=2 FACE=VERDANA COLOR=WHITE><B>".lang("UNSUBSCRIBE FROM")." ".$THIS_URL." ".lang("EMAIL SERVICE")."</B></FONT>\n";
	echo "</TD></TR>\n";
	echo "<TR><TD BGCOLOR=WHITE ALIGN=CENTER VALIGN=MIDDLE STYLE='font-family: Arial; font-size: 9pt;'>\n";
	echo "<FORM METHOD=POST ACTION=\"pgm-manage_subscription.php\">\n";
	echo "<INPUT TYPE=HIDDEN NAME=id VALUE=\"UNDO_ACTION\">\n";
	echo "<BR>".lang("Please enter the email address where you wish NOT to receive future emails").":<BR>\n";
	echo "<INPUT TYPE=TEXT NAME=UNDO_EMAIL VALUE=\"\" STYLE='font-family: Arial; font-size: 9pt; color: darkblue; width: 350px;'>\n";
	echo "<BR><BR><INPUT TYPE=SUBMIT VALUE=\"".lang("Unsubscribe Now")."\" STYLE='cursor: hand; font-family: Arial; font-size: 8pt;'>\n";
	echo "</FORM>\n";
	echo "</TD></TR></TABLE>\n";
	echo "</BODY></HTML>\n";
	exit;

}

if ($id == "UNDO_ACTION") {


	if (strlen($UNDO_EMAIL) < 5) {
		header("Location: pgm-manage_subscription.php?id=unsubscribe");
		exit;
	}
	
	$UNDO_EMAIL = strtolower($UNDO_EMAIL);
	if(!table_exists('unsubscribe')){
		mysql_query("CREATE TABLE unsubscribe (
		PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		UNSUB_EMAIL_ADDR CHAR(255),
		UNSUB_DATE DATE)");
		$today = date("Y-m-d");
	}
	
	$today = date("Y-m-d");
	$fe = mysql_query("select UNSUB_EMAIL_ADDR from unsubscribe where UNSUB_EMAIL_ADDR='".$UNDO_EMAIL."'");	
	if(mysql_num_rows($fe) == 0){
		mysql_query("INSERT INTO unsubscribe VALUES('NULL','$UNDO_EMAIL','$today')");
	}
	
	$THIS_URL = strtoupper($SERVER_NAME);

	echo "<HTML><HEAD><TITLE>SUBSCRIPTION CONTROL CENTER ($SERVER_NAME)</TITLE></HEAD>\n";
	echo "<BODY BGCOLOR=WHITE TEXT=BLACK LINK=RED ALINK=RED VLINK=RED>\n\n";
	echo "<BR><TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=550 STYLE='BORDER: 1px inset black;' ALIGN=CENTER>\n";
	echo "<TR><TD BGCOLOR=#708090 ALIGN=LEFT VALIGN=MIDDLE>\n";
	echo "<FONT SIZE=2 FACE=VERDANA COLOR=WHITE><B>".lang("UNSUBSCRIBE FROM")." $THIS_URL ".lang("EMAIL SERVICE")."</B></FONT>\n";
	echo "</TD></TR>\n";
	echo "<TR><TD BGCOLOR=WHITE ALIGN=CENTER VALIGN=MIDDLE STYLE='font-family: Arial; font-size: 9pt;'>\n";

	echo "<BR><B><FONT SIZE=2>".lang("The email address")." \"".$UNDO_EMAIL."\" ".lang("is no longer subscribed to our services.")."</FONT></B><BR><BR>\n";
	echo lang("If you need to remove another email address from our subscription system").", <a href=\"index.php?id=unsubscribe\">".lang("click here")."</a>.<BR><BR>\n";
	
	echo "</TD></TR></TABLE>\n";
	echo "</BODY></HTML>\n";
	exit;
	
}

#############################################################################################
## Otherwise, This must be a request to "View" the current Newsletter
#############################################################################################

$result = mysql_query("SELECT HTML_CONTENT, TEXT_CONTENT, CLICK_THRU_CNT FROM campaign_manager WHERE PRIKEY = '$id'");
$row = mysql_fetch_array($result);

#############################################################################################
## DISPLAY LINK TO HOME PAGE
#############################################################################################

	echo "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 ALIGN=CENTER WIDTH=612 STYLE='background: WHITE; border: 0px inset black;'>\n";
	echo "<tr><td align=center valign=top><font STYLE='font-family: Arial; font-size: 10pt;'><B>\n";
	echo "[ <A HREF=\"../index.php\">".lang("Visit")." $SERVER_NAME ".lang("now")."!</a> ]\n";
	echo "</TD></TR></TABLE><BR>\n\n";
	
#############################################################################################
## PARSE OUT WIERD CHARS TO MAKE LINKS AND IMAGES WORK CORRECTLY
#############################################################################################

	$THIS_HTML = eregi_replace("href=\"media/", "href=\"http://$this_ip/media/", $row[HTML_CONTENT]);
	$THIS_HTML = eregi_replace("runtime.css", "http://$this_ip/runtime.css", $THIS_HTML);
	$THIS_HTML = eregi_replace("shopping/", "http://$this_ip/shopping/", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-secure", "http://$this_ip/pgm-secure", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-form", "http://$this_ip/pgm-form", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("pgm-download", "http://$this_ip/pgm-download", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-email", "http://$this_ip/pgm-email", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-print", "http://$this_ip/pgm-print", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-view", "http://$this_ip/pgm-view", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("pgm-print", "http://$this_ip/pgm-print", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-view_video", "http://$this_ip/pgm-view_video", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("VALUE=\"media/", "VALUE=\"http://$this_ip/media/", $THIS_HTML);
	$THIS_HTML = eregi_replace("index.php", "http://$this_ip/index.php", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("src=\"images", "src=\"http://$this_ip/images", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("http://$this_ip/http://$this_ip/", "http://$this_ip/", $THIS_HTML);	// Text Editor Work around for images

	echo $THIS_HTML;
	
	// Update Click Through Count on Views Display in Campaign Manager
	
	$new_view = $row[CLICK_THRU_CNT] + 1;
	mysql_query("UPDATE campaign_manager SET CLICK_THRU_CNT = '$new_view' WHERE PRIKEY = '$id'");

?>