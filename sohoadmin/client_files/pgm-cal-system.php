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

#######################################################
## BUILD RAND KEY FIELD FUNCTION
#######################################################
error_reporting(E_PARSE);
session_start();
include_once('sohoadmin/includes/emulate_globals.php');
include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");

include_once("sohoadmin/program/includes/smt_functions.php");


$globalprefObj = new userdata('global');
$from_email = $globalprefObj->get('login-email-from');

if($from_email == ''){
	$from_email = 'noreply@'.$_SESSION['this_ip'];	
}

function GEN_KEY() {
	for ($GK=1;$GK<=15;$GK++) {
		$BASE_32 .= rand(0,999);
	}
	$BASE_32 .= date("gis");	// Append timestamp with seconds num to 100% guarantee rnd key
	$NEW_KEY = md5($BASE_32);
	return $NEW_KEY;
}

######################################################################
### DEFINE THE PAGE IN WHICH THIS MODULE IS RUNNING
######################################################################

$CAL_MOD_PAGE = $pr;
$CAL_MOD_PAGE = str_replace('"', '', str_replace("'", '', $CAL_MOD_PAGE));

if(!preg_match('/^(\d)+$/', $_REQUEST['EDIT_EVENT_RECORD']) && $_REQUEST['EDIT_EVENT_RECORD']!=""){
	exit;	
}

######################################################################
### SETUP OVERALL DISPLAY SETTINGS AND DETERMINE WHICH STEP
### TO BEGIN WITH.  WE HAVE TWO CALENDAR TYPES, MONTHLY AND WEEKLY
######################################################################

// --------------------------------------------------------
// Get product display setup (colors; permissions; etc.)
// --------------------------------------------------------

$result = mysql_query("SELECT * FROM calendar_display");

$err_test = mysql_num_rows($result);
if ($err_test < 1) { echo lang("Please Setup Calendar System Display Settings."); exit; }

$DISPLAY = mysql_fetch_array($result);

if ($SEL_MONTH == "") { $SEL_MONTH = date("m"); }	// Set default dates if drop down not active
if ($SEL_YEAR == "") { $SEL_YEAR = date("Y"); }
if ($CHANGE_CAT == "") { $CHANGE_CAT = "ALL"; }

// --------------------------------------------------------
// Setup Drop Down Options for Categories
// --------------------------------------------------------

if ($CHANGE_CAT == "ALL") { $osel = "SELECTED"; } else { $osel = ""; }
$copts = "<OPTION VALUE=\"ALL\" $osel>".lang("All")."</OPTION>\n";

$c = mysql_query("SELECT * FROM calendar_category ORDER BY Category_Name");
$NUM_CATEGORIES = mysql_num_rows($c);
while ($r = mysql_fetch_array($c)) {
	if ($CHANGE_CAT == "$r[Category_Name]") { $osel = "SELECTED"; $CURRENT_CAT_KEY = $r[PRIKEY]; } else { $osel = ""; }

	$CAT_DISPLAY_NAME[$r[PRIKEY]] = $r[Category_Name];

	if ($CALFORM_CATEGORY == $r[Category_Name]) { $CALFORM_CATNUM = $r[PRIKEY]; }

	$copts .= "<OPTION VALUE=\"$r[Category_Name]\" $osel>$r[Category_Name]</OPTION>\n";
}

if (isset($OWNER_NAME) && $DISPLAY[ALLOW_PERSONAL_CALENDARS] == "Y") {
	if (eregi("AUTH:", $CHANGE_CAT)) { $osel = "SELECTED"; } else { $osel = ""; }
	$copts .= "<OPTION VALUE=\"AUTH:$MD5CODE\" $osel>".lang("Private").": $OWNER_NAME</OPTION>\n";
}


$MONTH_OPTIONS = "";
for ($x=1;$x<=12;$x++) {
	$val = date("m", mktime(0,0,0,$x,1,2002));
	$display = date("M", mktime(0,0,0,$x,1,2002));
	if ($val == $SEL_MONTH) { $SEL = "SELECTED"; } else { $SEL = ""; }
	$MONTH_OPTIONS .= "<OPTION VALUE=\"$val\" $SEL>$display</OPTION>\n";
}

$YEAR_OPTIONS = "";
for ($x=2002;$x<=2015;$x++) {
	if ($x == $SEL_YEAR) { $SEL = "SELECTED"; } else { $SEL = ""; }
	$YEAR_OPTIONS .= "<OPTION VALUE=\"$x\" $SEL>$x</OPTION>\n";
}

############################################################################
### CHECK FOR USER SUBMISSION AND PROCESS FORM ACTIVITY NOW
############################################################################

if (isset($PUBLIC_SUBMIT_EVENT)) {			// Submit event has been requested

	// Include event submission form for display in step one.
	// -------------------------------------------------------

	if ($FORM_SUBMIT_STEP == "") {

		if ($EDIT_EVENT_RECORD != "") {
			$edresult = mysql_query("SELECT * FROM calendar_events WHERE PRIKEY = '$EDIT_EVENT_RECORD'");
			$EDIT_DATA = mysql_fetch_array($edresult);
		}

		if ($DELETE_EVENT_RECORD != "") {

			mysql_query("DELETE FROM calendar_events WHERE PRIKEY = '$DELETE_EVENT_RECORD'");

			echo "<DIV ALIGN=CENTER CLASS=text>".lang("Your selected event has been deleted.")."</DIV>\n";
			$tResultFlag = 1;

		} else {

			// Log this calendar view into stats
			// -----------------------------------------------------------------

			if (file_exists("sohoadmin/client_files/pgm-site_stats.inc.php")) {		// Check; this mod N/A in Lite Version
				$statpage = "Calendar: Submit Event";
				include ("sohoadmin/client_files/pgm-site_stats.inc.php");
			}

			// -----------------------------------------------------------------

			$filename = "pgm-cal-submitevent.inc.php";
			ob_start();
				include("$filename");
				$TMP_HTML = ob_get_contents();
			ob_end_clean();

			echo $TMP_HTML;

		 } // End if Delete Check

	} // End Form Submit Step 1

	if ($FORM_SUBMIT_STEP == "2") {

		// Check for form submission errors
		// ----------------------------------------------------

		$err = 0;
		if (strlen($CALFORM_NAME) < 3) { $err = 1; }
		if (strlen($CALFORM_EMAILADDRESS) < 5) { $err = 1; }
		if (strlen($CALFORM_TITLE) < 3) { $err = 1; }

		// If Errors exists; flip back to form for correction
		// ----------------------------------------------------

		if ($err == 1) {

			$filename = "pgm-cal-submitevent.inc.php";
			ob_start();
				include("$filename");
				$TMP_HTML = ob_get_contents();
			ob_end_clean();
			echo "<DIV ALIGN=CENTER CLASS=text><font color=red>".lang("You did not enter one or more required fields. Please modify your submission and try again.")."</font></DIV><BR>\n";
			echo $TMP_HTML;

		}

		// No errors; let's decide what to do with the submission:
		// If the submitted category contains "AUTH:"; then this
		// is an addition to a personal calendar. Otherwise it
		// is a normal submission. (The user could not have a
		// choice of an AUTH: category if it were not theirs)
		// -------------------------------------------------------

		$tResultFlag = 0;

		if ($err == 0) {

			$tmp_date = $CALFORM_YEAR."-".$CALFORM_MONTH."-".$CALFORM_DAY;

			if (eregi("AUTH:", $CALFORM_CATEGORY)) {	// This is a personal event; just add it

				// Add this personal event to the data table now
				// -----------------------------------------------

				$tmp = eregi_replace("AUTH:", "", $CALFORM_CATEGORY);

				if ($EDIT_CAL_RECORD == "") {
						$THIS_NEW_KEY = GEN_KEY();
						mysql_query("INSERT INTO calendar_events VALUES('$THIS_NEW_KEY','$tmp_date',' ','$CALFORM_START_TIME',
						'$CALFORM_END_TIME','$CALFORM_TITLE','$CALFORM_DETAILS','$tmp','','','','Y','','','')");
				} else {
						mysql_query("UPDATE calendar_events SET EVENT_DATE = '$tmp_date', EVENT_START = '$CALFORM_START_TIME',
						EVENT_END = '$CALFORM_END_TIME', EVENT_TITLE = '$CALFORM_TITLE', EVENT_DETAILS = '$CALFORM_DETAILS', EVENT_CATEGORY = '$tmp'
						WHERE PRIKEY = '$EDIT_CAL_RECORD'");
				}

				$tResultFlag = 1;

				echo "<DIV ALIGN=CENTER CLASS=text><font color=maroon>[ ".lang("Event Added to your Calendar")." ]</font></DIV><BR>\n";

			} else {	// Build confirmation email and send

				// Add this event as a "Pending" event for confirmation
				// ---------------------------------------------------------

				$tmp = "~~~PENDING~~~";
				$pendKey = GEN_KEY();

				if ($CALFORM_CATNUM == "") { $CALFORM_CATNUM = "ALL"; }

				mysql_query("INSERT INTO calendar_events VALUES('$pendKey','$tmp_date',' ','$CALFORM_START_TIME',
					'$CALFORM_END_TIME','$CALFORM_TITLE','$CALFORM_DETAILS','$CALFORM_CATNUM','','','$tmp','Y','','','')");

				$LAST_ID = $pendKey;

				// Create confirmation email for webmaster
				// ----------------------------------------------------------

				$CALFORM_TITLE = stripslashes($CALFORM_TITLE);
				$CALFORM_DETAILS = stripslashes($CALFORM_DETAILS);

				$email_confirm = lang("The following event was submitted to your calendar. To approve this event, click the approve link below.")."\n\n";
				$email_confirm .= lang("If you do not wish to add this event to your calendar, simply disregard this email.")."\n\n";
				$email_confirm .= lang("Event Date").": $tmp_date\n";
				$email_confirm .= lang("Event Category").": $CALFORM_CATEGORY\n";
				$email_confirm .= lang("Event Title").": $CALFORM_TITLE\n";
				$email_confirm .= lang("Start Time").": $CALFORM_START_TIME   ".$lang["End Time"].": $CALFORM_END_TIME\n\n";
				$email_confirm .= lang("Event Details").": $CALFORM_DETAILS\n\n\n";

				$email_confirm .= lang("To approve, click the link below:")."\n";
				$email_confirm .= "http://$this_ip/pgm-cal-confirm.php?id=$LAST_ID\n\n\n";

				$email_confirm .= "*** ".lang("THIS IS AN AUTO-GENERATED EMAIL FROM YOUR WEBSITE")." ***";

				if ($DISPLAY[EMAIL_CONFIRMATION] != "") {
					mail("$DISPLAY[EMAIL_CONFIRMATION]", "Calendar Event Submission", "$email_confirm", "FROM: $from_email");
				}

				$tResultFlag = 1;

				echo "<DIV ALIGN=CENTER CLASS=text><font color=maroon>[ ".lang("Your submission has been sent to our calendar manager for approval.")." ".lang("Thank you").". ]</font></DIV><BR>\n";

			} // End post type

		} // End only if non-error

	} // End Step 2

	// Make sure that we flag the fact that we don't need to
	// display any calendar type functions that appear below
	// this function in the script
	// ------------------------------------------------------------------

	if ($tResultFlag != 1) {
		$dType = "FORM";
		if (isset($DISPLAY_SEARCH_CALENDAR)) { unset($DISPLAY_SEARCH_CALENDAR); }
		if (isset($DISPLAY_MONTHLY_CALENDAR)) { unset($DISPLAY_MONTHLY_CALENDAR); }
    }

} // End Submit Event

############################################################################

// Create overall form that will wrap around the display
// calendar for both displays
// -----------------------------------------------------------

$START_FORM = "<FORM METHOD=POST ACTION=\"index.php\">\n";
$START_FORM .= "<INPUT TYPE=HIDDEN NAME=\"pr\" VALUE=\"$CAL_MOD_PAGE\">\n";

$END_FORM = "</FORM>\n\n";

// --------------------------------------------------------
// $dType :: calendar display type during runtime
// M = Montly View || W = Weekly View
// --------------------------------------------------------

if (isset($DISPLAY_SEARCH_CALENDAR)) { $dType = "W"; }
if (isset($DISPLAY_MONTHLY_CALENDAR)) { $dType = "M"; }
if (isset($CHANGE_CALENDAR_CATEGORY)) { $dType = "M"; }
if (isset($CHANGE_CALENDAR_MONYEAR)) { $dType = "M"; }

if ($dType == "") { $dType = $DISPLAY[DISPLAY_STYLE]; }


#######################################################################
### LET'S DO THE MONTH (M) VIEW FIRST, SINCE WE ALREADY HAVE THAT ONE
### "CHEATED" FOR US IN THE "MONTH ONLY" OBJECT INCLUDE :)
#######################################################################

if (eregi("M", $dType) && !eregi("W", $dType) && $dType != "FORM") {					// It's late and I can't remember upper or lower case


	// Log this calendar view into stats
	// -----------------------------------------------------------------

	if (file_exists("sohoadmin/client_files/pgm-site_stats.inc.php")) {		// Check; this mod N/A in Lite Version
		$statpage = "Calendar: Monthly View";
		include ("sohoadmin/client_files/pgm-site_stats.inc.php");
	}
	 	// -----------------------------------------------------------------

	$REUSE_FLAG = 1;	// Set this flag for the include so it doesn't use "this month" by default

	// Include the single category monthly view include and pass
	// the REUSE FLAG to let the script know that we are using
	// this within the system.
	// -----------------------------------------------------------

	$filename = "pgm-cal-monthview.php";
	ob_start();
		include("$filename");
		$TMP_HTML = ob_get_contents();
	ob_end_clean();

	// Create drop down boxes that hold category and month/year
	// selection searchs for normal system operation.
	// -----------------------------------------------------------

	$DROP_DOWN = "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=CENTER WIDTH=100%>\n";
	$DROP_DOWN .= "<TR>\n";

	if ($NUM_CATEGORIES > 0) {	// Don't Display Category option if NO categories exist

		$DROP_DOWN .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text>\n";
		if($hide_drop_down != 1){
			$DROP_DOWN .= "<B>Category</B>: </FONT><SELECT NAME=\"CHANGE_CAT\" CLASS=smtext STYLE='background: #EFEFEF;'>\n";
			$DROP_DOWN .= $copts;
			$DROP_DOWN .= "</SELECT>&nbsp;<input type=submit name=CHANGE_CALENDAR_CATEGORY value=\"Change\" class=FormLt1>\n";
		}
		$DROP_DOWN .= "</TD>\n";

	}

	$DROP_DOWN .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text>\n";
	$DROP_DOWN .= "<B>".lang("Current View")."</B>: </font><SELECT NAME=\"SEL_MONTH\" class=smtext STYLE='background: #EFEFEF;'>$MONTH_OPTIONS</SELECT> <SELECT NAME=\"SEL_YEAR\" class=smtext STYLE='background: #EFEFEF;'>$YEAR_OPTIONS</SELECT>\n";
	$DROP_DOWN .= "&nbsp;<INPUT TYPE=SUBMIT NAME=CHANGE_CALENDAR_MONYEAR VALUE=\"".lang("View")."\" CLASS=FormLt1>\n";
	$DROP_DOWN .= "</TD>\n";

	$DROP_DOWN .= "</TR></TABLE>";

	// Create footer table that will allow Public Event Submissions
	// AND/OR display a link to the "detailed/searchable" calendar view
	// -------------------------------------------------------------------

	$CAL_FOOTER = "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 ALIGN=CENTER WIDTH=100%>\n";
	$CAL_FOOTER .= "<TR>\n";

	if ($DISPLAY[ALLOW_PUBLIC_SUBMISSIONS] == "Y" || ($DISPLAY[ALLOW_PERSONAL_CALENDARS] == "Y" && isset($OWNER_NAME))) {	// Don't Display Submit Event unless turned on.

		$CAL_FOOTER .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=text><BR>\n";
		$CAL_FOOTER .= "<input type=submit name=PUBLIC_SUBMIT_EVENT value=\" ".lang("Submit an Event")." \" class=FormLt1>\n";
		$CAL_FOOTER .= "</TD>\n";

	}

	$CAL_FOOTER .= "<TD ALIGN=CENTER VALIGN=MIDDLE CLASS=smtext><BR>\n";
	$CAL_FOOTER .= "<INPUT TYPE=SUBMIT NAME=DISPLAY_SEARCH_CALENDAR VALUE=\"".lang("Detail Event Search")."\" CLASS=FormLt1>\n";
	$CAL_FOOTER .= "</TD>\n";

	$CAL_FOOTER .= "</TR></TABLE>";

	// Replace ##DROPDOWN## insert from month view .inc with the
	// created drop down selections
	// -----------------------------------------------------------

	$TMP_HTML = str_replace("##DROPDOWN##", "$DROP_DOWN", $TMP_HTML);

	// Ok, Let's output the final result to the screen and call
	// it quits for now.
	// -----------------------------------------------------------

	echo $START_FORM;

	echo $TMP_HTML;
	echo $CAL_FOOTER;

	echo $END_FORM;

} // End Montly View Calendar Display



#######################################################################
### NOW LET'S CREATE THE "DETAIL EVENT SEARCH" OR "WEEKLY/DAILY" VIEW
### CALENDAR DISPLAY VERSION
#######################################################################

if (eregi("W", $dType) && !eregi("M", $dType) && $dType != "FORM") {

	// Check for passed variables from header menu that may overide default
	// selections
	// ---------------------------------------------------------------------

	if ($DETAIL_SHOW_MONTH != "") {
		$tmp = split("-", $DETAIL_SHOW_MONTH);
		$SEL_MONTH = $tmp[1];
		$SEL_YEAR = $tmp[0];
	}

	// If there is an Authorized User Loged In, Let's search for any events
	// they may be able to view beyond "public" events
	// -------------------------------------------------------------------------

	$SEC_SEARCH = "EVENT_SECURITYCODE = 'Public'";

	if (isset($GROUPS)) {
		$flag = 0;
		$tmp = split(";", $GROUPS);
		$tmpc = count($tmp);
		for ($dd=0;$dd<=$tmpc;$dd++) {
			if ($tmp[$dd] != "") {
				$SEC_SEARCH .= " OR EVENT_SECURITYCODE = '$tmp[$dd]'";
				$flag++;
			}
		}

		if ($DISPLAY[ALLOW_PERSONAL_CALENDARS] == "Y") { $SEC_SEARCH .= " OR EVENT_SECURITYCODE = ''"; }

		if ($flag != 0) { $SEC_SEARCH = "($SEC_SEARCH)"; }
	}

	// Setup base date calculation variables for use with header build
	// -----------------------------------------------------------------------

	$NUM_DAYS_IN_MONTH = date("t", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));

	$START_DOW = date("l", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));			// What day of week does month start on?
	$WK_NUM = date("w", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));				// What day of week NUMBER does this start on

	if ($SEL_MONTH == date("m")) { $HIGHLIGHT = "on"; $HIGHLIGHT_DAY = date("j"); }
	$day_of_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
	$sm_day_of_week = array('SUN','MON','TUE','WED','THU','FRI','SAT');

	$TEXT_MONTH = date("M", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));
	$LONG_TEXT_MONTH = date("F", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));

	$DI_DATE = date("F Y", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));
	$DI_DATE = strtoupper($DI_DATE);

	$t = $SEL_MONTH - 1;
	$PREV_MONTH = date("M", mktime(0,0,0,$t,1,$SEL_YEAR));
	$prev_pass = date("Y-m", mktime(0,0,0,$t,1,$SEL_YEAR));
	$t = $SEL_MONTH + 1;
	$NEXT_MONTH = date("M", mktime(0,0,0,$t,1,$SEL_YEAR));
	$next_pass = date("Y-m", mktime(0,0,0,$t,1,$SEL_YEAR));

	// BUILD HEADER SEARCH QUERY TABLE
	// -------------------------------------------------------------------

	echo $START_FORM;

	echo "<TABLE BORDER=0 CELLPADDING=8 CELLSPACING=0 WIDTH=99% ALIGN=CENTER STYLE='border: 0px inset black;'>\n";
	echo "<TR>\n";
	echo "<TD ALIGN=CENTER VALIGN=TOP CLASS=smtext WIDTH=50% BGCOLOR=WHITE>\n";

		// Build "Cal-At-A-Glance-Nav" Structure
		// ----------------------------------------------------------------

		echo "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=0 WIDTH=95% ALIGN=CENTER>\n";
		echo "<TR><TD ALIGN=LEFT CLASS=smtext><a href=\"".pagename($CAL_MOD_PAGE, "&")."DISPLAY_SEARCH_CALENDAR=&DETAIL_SHOW_MONTH=$prev_pass\"><< $PREV_MONTH</A></TD>\n";
		echo "<TD ALIGN=CENTER CLASS=text><B>$DI_DATE</B></TD>\n";
		echo "<TD ALIGN=RIGHT CLASS=smtext><a href=\"".pagename($CAL_MOD_PAGE, "&")."DISPLAY_SEARCH_CALENDAR=&DETAIL_SHOW_MONTH=$next_pass\">$NEXT_MONTH >></a></TD></TR></TABLE>\n\n";

		echo "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=0 WIDTH=95% ALIGN=CENTER STYLE='border: 1px inset black;'>\n";
		echo "<TR>\n";

		echo "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=\"OLDLACE\" CLASS=smtext  STYLE='border: 1px inset black;'>";
		echo "<font color=$DISPLAY[TEXT_COLOR]><a href=\"".pagename($CAL_MOD_PAGE, "&")."DISPLAY_MONTHLY_CALENDAR=&SEL_MONTH=$SEL_MONTH&SEL_YEAR=$SEL_YEAR&CHANGE_CAT=$CHANGE_CAT\">".lang("Month")."</b><font>";
		echo "</TD>\n";

		for ($x=0;$x<=6;$x++) {
			echo "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$DISPLAY[BACKGROUND_COLOR] CLASS=smtext  STYLE='border: 1px inset black;'>";
			echo "<font color=$DISPLAY[TEXT_COLOR]><B>$sm_day_of_week[$x]</b><font>";
			echo "</TD>\n";
		}

		echo "</TR>\n";
		echo "\n\n<TR>\n";

		$FLAG = 0;
		$display_day = 1;

		echo "<TD ALIGN=CENTER VALIGN=TOP BGCOLOR=OLDLACE CLASS=smtext STYLE='border: 1px inset black;'>\n";
		$this_start = 1;
		$this_end = 7 - $WK_NUM;
		echo "<a href=\"".pagename($CAL_MOD_PAGE, "&")."DISPLAY_SEARCH_CALENDAR=&DETAIL_VIEW_WEEK=$this_start-$this_end&SEL_MONTH=$SEL_MONTH&SEL_YEAR=$SEL_YEAR&CHANGE_CAT=$CHANGE_CAT\">$TEXT_MONTH<BR>$this_start-$this_end</A>";
		echo "</TD>\n";

		for ($x=0;$x<=6;$x++) {		// Start First Week Loop

			if (eregi("$START_DOW", $day_of_week[$x]) || $FLAG == 1) {
				if ($HIGHLIGHT == "on" && $display_day == $HIGHLIGHT_DAY) { $BGCOLOR = "skyblue"; } else { $BGCOLOR = "WHITE"; }
				echo "<TD ALIGN=CENTER VALIGN=TOP BGCOLOR=$BGCOLOR CLASS=smtext STYLE='border: 1px inset black;'>\n";
				$this_date = $SEL_YEAR."-".$SEL_MONTH."-".$display_day;
				echo "<a href=\"".pagename($CAL_MOD_PAGE, "&")."DISPLAY_SEARCH_CALENDAR=&DETAIL_VIEW=$this_date&SEL_MONTH=$SEL_MONTH&SEL_YEAR=$SEL_YEAR&CHANGE_CAT=$CHANGE_CAT\">$TEXT_MONTH<BR>$display_day</A>";
				echo "</TD>\n";
				$display_day++;
				$FLAG = 1;

			} else {

				echo "\n<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=#EFEFEF CLASS=text style='border: 1px inset black;'>";
				echo "&nbsp;";
				echo "</TD>\n";

			}

		} // End First Week Loop

		echo "</TR>\n";

		$FLAG=0;
		$NUM_ROWS = $NUM_DAYS_IN_MONTH/7;
      $days_in_first = $display_day - 1;
      $days_in_rest = $NUM_DAYS_IN_MONTH - $days_in_first;

      # Display extra row?
      if($days_in_rest > 28){
         if ($NUM_ROWS > 4) { $NUM_ROWS = 5; }
      }

		for ($x=1;$x<=$NUM_ROWS;$x++) {	// Start Weekly Loop

			echo "\n\n<TR>\n";

			echo "<TD ALIGN=CENTER VALIGN=TOP BGCOLOR=OLDLACE CLASS=smtext STYLE='border: 1px inset black;'>\n";
			$this_start = $display_day;
			$this_end = $this_start + 6;

			if ($this_end > $NUM_DAYS_IN_MONTH) {
				$this_end = $NUM_DAYS_IN_MONTH;
			}

			echo "<a href=\"".pagename($CAL_MOD_PAGE, "&")."DISPLAY_SEARCH_CALENDAR=&DETAIL_VIEW_WEEK=$this_start-$this_end&SEL_MONTH=$SEL_MONTH&SEL_YEAR=$SEL_YEAR&CHANGE_CAT=$CHANGE_CAT\">$TEXT_MONTH<BR>$this_start-$this_end</A>";
			echo "</TD>\n";

			for ($y=1;$y<=7;$y++) {			// Start Daily Loop

				if ($FLAG != 1) {

					if ($HIGHLIGHT == "on" && $display_day == $HIGHLIGHT_DAY) { $BGCOLOR = "skyblue"; } else { $BGCOLOR = "WHITE"; }
					echo "\n<TD ALIGN=CENTER VALIGN=TOP BGCOLOR=$BGCOLOR CLASS=smtext STYLE='border: 1px inset black;'>\n";
					$this_date = $SEL_YEAR."-".$SEL_MONTH."-".$display_day;
					echo "<a href=\"".pagename($CAL_MOD_PAGE, "&")."DISPLAY_SEARCH_CALENDAR=&DETAIL_VIEW=$this_date&SEL_MONTH=$SEL_MONTH&SEL_YEAR=$SEL_YEAR&CHANGE_CAT=$CHANGE_CAT\">$TEXT_MONTH<BR>$display_day</A>";
					echo "\n</TD>\n";

				} else {

					echo "\n<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=#EFEFEF CLASS=text STYLE='border: 1px inset black;'>";
					echo "&nbsp;";
					echo "</TD>\n";

				}

				if ($display_day == $NUM_DAYS_IN_MONTH) { $FLAG = 1; }
				$display_day++;

			} // End Daily ($y) Loop

			echo "</TR>\n";

		} // End Month ($x) Loop

		echo "\n\n</TABLE>\n";

		if (eregi("AUTH:", $CHANGE_CAT)) {
			$tmp = "($OWNER_NAME)";
		} else {
			$tmp = "$CHANGE_CAT";
		}

		echo "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=0 WIDTH=95% ALIGN=CENTER>\n";
		echo "<TR><TD ALIGN=CENTER BGCOLOR=WHITE CLASS=smtext>\n";
		echo "<FONT COLOR=#999999>".lang("Current Category").": $tmp</FONT>\n";
		echo "</TR></TABLE>\n\n";

	// Now Build the Search Side to the header Menu
	// --------------------------------------------------------------------------------

	echo "</TD><TD ALIGN=LEFT VALIGN=TOP CLASS=text WIDTH=50%>\n";

		echo "<INPUT TYPE=HIDDEN NAME=\"SEL_MONTH\" VALUE=\"$SEL_MONTH\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"SEL_YEAR\" VALUE=\"$SEL_YEAR\">\n";
		echo "<INPUT TYPE=HIDDEN NAME=\"dType\" VALUE=\"W\">\n";

		echo "<BR><B>Search Keywords</B>:<BR><INPUT TYPE=TEXT NAME=DETAIL_SEARCH_KEYWORDS VALUE=\"\" class=\"textfield\" STYLE='WIDTH: 210px;'><BR><BR>";

		 if ($NUM_CATEGORIES > 0) {
 			echo "<B>".lang("In Category")."</B>:<BR><SELECT NAME=\"CHANGE_CAT\" CLASS=smtext STYLE='WIDTH: 210px;'>\n";
			echo "$copts</SELECT><BR><BR>\n";
		}

		echo "<B>Sort by</B>:<BR><INPUT TYPE=RADIO NAME=SORT_RESULTS_BY VALUE=\"EVENT_DATE\" CHECKED> Event Date\n";
		echo "&nbsp;&nbsp;&nbsp;<INPUT TYPE=RADIO NAME=SORT_RESULTS_BY VALUE=\"EVENT_TITLE\"> Event Title\n";
		echo "&nbsp;&nbsp;&nbsp;<SELECT NAME=\"SORT_BY_ORDER\" CLASS=smtext><OPTION VALUE=\"ASC\">ASC</OPTION>\n";
		echo "<OPTION VALUE=\"DESC\">DESC</OPTION></SELECT><BR><BR>\n";
		echo "<INPUT TYPE=SUBMIT NAME=\"CALENDAR_SEARCH\" VALUE=\" ".lang("Search Now")." \" CLASS=FormLt1>\n";

		if ($DISPLAY[ALLOW_PUBLIC_SUBMISSIONS] == "Y" || ($DISPLAY[ALLOW_PERSONAL_CALENDARS] == "Y" && isset($OWNER_NAME))) {	// Don't Display Submit Event unless turned on.
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=submit name=PUBLIC_SUBMIT_EVENT value=\" ".lang("Submit an Event")." \" class=FormLt1>\n";
		}

	echo "<BR><DIV ALIGN=CENTER CLASS=smtext style='padding: 10px;'><FONT COLOR=#999999>".lang("Submit a search to change categories.")."</FONT></DIV>\n";
	echo "</TD></TR></TABLE>\n";	// End Header Table

	###################################################################################
	### START ACTUAL DETAIL DISPLAY BASED ON SEARCH OR "CLICK" REQUIREMENT
	###################################################################################

	// If an exact day way requested, pull request from this function
	// -----------------------------------------------------------------

	if ($DETAIL_VIEW != "") {

		// Log this calendar view into stats
		// -----------------------------------------------------------------

		if (file_exists("sohoadmin/client_files/pgm-site_stats.inc.php")) {		// Check; this mod N/A in Lite Version
			$statpage = "Calendar: Weekly View";
			include ("sohoadmin/client_files/pgm-site_stats.inc.php");
		}

		// -----------------------------------------------------------------

		$show_cal_type = "";

		$tmp = split("-", $DETAIL_VIEW);
		$REQ_YEAR = $tmp[0];
		$REQ_DAY = $tmp[2];
		$REQ_MONTH = $tmp[1];

		$tmp = date("l, F j, Y", mktime(0,0,0,$REQ_MONTH,$REQ_DAY,$REQ_YEAR));
		$REQUEST_TITLE = "<B>Events for $tmp:</B>";

		$tmp = "$REQ_YEAR-$REQ_MONTH-$REQ_DAY";

		if ($CHANGE_CAT != "ALL" && !eregi("AUTH:", $CHANGE_CAT)) {
			$CAT_QUERY = "AND EVENT_CATEGORY = '$CURRENT_CAT_KEY'";
		} else {
			$CAT_QUERY = "";
		}

		if (eregi("AUTH:", $CHANGE_CAT)) {
			$tmp = eregi_replace("AUTH:", "", $CHANGE_CAT);
			$CAT_QUERY = "AND EVENT_CATEGORY = '$tmp'";
		}

		$result = mysql_query("SELECT PRIKEY, EVENT_DATE, EVENT_TITLE, EVENT_CATEGORY, EVENT_DETAILS, EVENT_DETAILPAGE, EVENT_START, EVENT_END, EVENT_SECURITYCODE
						FROM calendar_events WHERE EVENT_DATE = '$tmp' AND $SEC_SEARCH $CAT_QUERY ORDER BY EVENT_DATE, EVENT_START");

	} // End Day View

	// If a weekly view was requested, pull query data from this routine
	// -----------------------------------------------------------------


	if ($DETAIL_VIEW_WEEK != "") {

		// Log this calendar view into stats
		// -----------------------------------------------------------------

		if (file_exists("sohoadmin/client_files/pgm-site_stats.inc.php")) {		// Check; this mod N/A in Lite Version
			$statpage = "Calendar: Weekly View";
			include ("sohoadmin/client_files/pgm-site_stats.inc.php");
		}

		// -----------------------------------------------------------------

		$tmp = split("-", $DETAIL_VIEW_WEEK);
		$START_DAY = $tmp[0];
		$END_DAY = $tmp[1];
		$REQUEST_TITLE = "<B>".lang("Events for the Week of")." $LONG_TEXT_MONTH $START_DAY-$END_DAY, $SEL_YEAR:</B>";

		$show_cal_type = "WEEKLYTEMPLATE";

	} // End Week View

	// If this request is in fact a true SEARCH request; use this routine
	// -------------------------------------------------------------------

	if (isset($CALENDAR_SEARCH)) {

		// Log this calendar view into stats
		// -----------------------------------------------------------------

		if (file_exists("sohoadmin/client_files/pgm-site_stats.inc.php")) {		// Check; this mod N/A in Lite Version
			$statpage = "Calendar: Search Events";
			include ("sohoadmin/client_files/pgm-site_stats.inc.php");
		}

		// -----------------------------------------------------------------

		$show_cal_type = "";

		if ($DETAIL_SEARCH_KEYWORDS == "") {
			$DETAIL_SEARCH_KEYWORDS = "All Events";
		}

		$REQUEST_TITLE = "<B>".lang("Events for")." $LONG_TEXT_MONTH $SEL_YEAR ".lang("that match your search for")." \"$DETAIL_SEARCH_KEYWORDS\" ";
		if ($NUM_CATEGORIES > 0) {
			if (eregi("AUTH:", $CHANGE_CAT)) {
				$tmp = lang("your personal calendar")." ($OWNER_NAME)";
			} else {
				$tmp = lang("the category").", \"$CHANGE_CAT\"";
			}
			$REQUEST_TITLE .= lang("located in")." $tmp:</B>";
		}

		if ($CHANGE_CAT != "ALL" && !eregi("AUTH:", $CHANGE_CAT)) {
			$CAT_QUERY = "AND EVENT_CATEGORY = '$CURRENT_CAT_KEY'";
		} else {
			$CAT_QUERY = "";
		}

		if (eregi("AUTH:", $CHANGE_CAT)) {
			$tmp = eregi_replace("AUTH:", "", $CHANGE_CAT);
			$CAT_QUERY = "AND EVENT_CATEGORY = '$tmp'";
		}

		$date_like = "$SEL_YEAR-$SEL_MONTH-";

		if ($DETAIL_SEARCH_KEYWORDS == "All Events") {

			$result = mysql_query("SELECT PRIKEY, EVENT_DATE, EVENT_KEYWORDS, EVENT_TITLE, EVENT_CATEGORY, EVENT_DETAILS, EVENT_DETAILPAGE, EVENT_START, EVENT_END, EVENT_SECURITYCODE
						FROM calendar_events WHERE $SEC_SEARCH $CAT_QUERY ORDER BY $SORT_RESULTS_BY, EVENT_START $SORT_BY_ORDER");

		} else {

			$result = mysql_query("SELECT PRIKEY, EVENT_DATE, EVENT_KEYWORDS, EVENT_TITLE, EVENT_CATEGORY, EVENT_DETAILS, EVENT_DETAILPAGE, EVENT_START, EVENT_END, EVENT_SECURITYCODE
						FROM calendar_events WHERE
						EVENT_DATE LIKE '$date_like%' AND
						(EVENT_KEYWORDS LIKE '%$DETAIL_SEARCH_KEYWORDS%' OR
						EVENT_DATE LIKE '%$DETAIL_SEARCH_KEYWORDS%' OR
						EVENT_TITLE LIKE '%$DETAIL_SEARCH_KEYWORDS%' OR
						EVENT_DETAILS LIKE '%$DETAIL_SEARCH_KEYWORDS%' OR
						EVENT_START LIKE '%$DETAIL_SEARCH_KEYWORDS%') AND
						$SEC_SEARCH $CAT_QUERY ORDER BY $SORT_RESULTS_BY, EVENT_START $SORT_BY_ORDER");

		} // End All or Something search

	} // End Calendar Event Search Check

	// BUILD HEADER DISPLAYING REQUESTED VIEW (THE DAY/WEEK)
	// -------------------------------------------------------------------

	if ($REQUEST_TITLE != "") {

		echo "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 WIDTH=99% ALIGN=CENTER STYLE='border: 1px inset black;'>\n";
		echo "<TR>\n";
		echo "<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=$DISPLAY[BACKGROUND_COLOR]><FONT SIZE=2 FACE=VERDANA COLOR=$DISPLAY[TEXT_COLOR]>\n";

		echo "$REQUEST_TITLE\n";

		echo "</TD></TR><TR><TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=\"$DISPLAY[BACKGROUND_COLOR]\">";

		$check_results = mysql_num_rows($result);

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~ START "EACH" RESULT DISPLAY ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		if ($show_cal_type != "WEEKLYTEMPLATE") {

				while ($row = mysql_fetch_array($result)) {

					$cat_num = $row[EVENT_CATEGORY];

					if ($cat_num == "ALL") {
						$category = "All";
					} else {
						$category = $CAT_DISPLAY_NAME[$cat_num];
					}

					if ($cat_num == $MD5CODE) {
						$category = "<a href=\"".pagename($pr, "&")."EDIT_EVENT_RECORD=$row[PRIKEY]&PUBLIC_SUBMIT_EVENT=\">".lang("Edit Event")."</a>] &nbsp; [<a href=\"".pagename($pr, "&")."DELETE_EVENT_RECORD=$row[PRIKEY]&PUBLIC_SUBMIT_EVENT=\">".lang("Delete Event")."</a>";
					}

					echo "<TABLE BORDER=0 CELLPADDING=6 CELLSPACING=0 WIDTH=100% ALIGN=LEFT STYLE='border: 1px inset black;'>\n";
					echo "<TR>\n";
					echo "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=\"#EFEFEF\">\n";

					if ($cat_num == $MD5CODE) {
						echo "<img src=\"sohoadmin/client_files/securelogin.gif\" alt=\"".lang("This is your private event.")."\" border=0 hspace=2 vspace=2 align=absmiddle>\n";
					}

					$t = $row[EVENT_DATE];
					$tmp = split("-", $t);
					$dDate = date("l, F j, Y", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));

					$row[EVENT_TITLE] = stripslashes($row[EVENT_TITLE]);
					$row[EVENT_DETAILS] = stripslashes($row[EVENT_DETAILS]);

					echo "[ <FONT COLOR=DARKGREEN>".$category."</FONT> ] $dDate<BR><FONT SIZE=2 FACE=VERDANA><B>$row[EVENT_TITLE]</B></FONT>\n";

						$tmp = split(":", $row[EVENT_START]);
						if ($tmp[0] != "00") {
							$tmp = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$SEL_MONTH,1,$SEL_YEAR));
							$event_time = $tmp;

							if ($row[EVENT_END] != "00:00:00") {
								$tmp = split(":", $row[EVENT_END]);
								$tmp = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$SEL_MONTH,1,$SEL_YEAR));
								$event_time .= "-$tmp";
							}
							echo "&nbsp;&nbsp;&nbsp;&nbsp; ($event_time)\n";
						}

					echo "</TD></TR><TR><TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=\"WHITE\" STYLE='border-top: 1px inset black;'>\n";

					echo "Event Details: ".$row[EVENT_DETAILS];

					if ($row[EVENT_DETAILPAGE] != "") {
						echo "<a href=\"#\" onclick=\"javscript: window.open('pgm-cal-details.inc.php?id=$row[PRIKEY]','EVENTDETAILS', 'scrollbars=yes,location=no,resizable=yes,width=470,height=400');\">";
						if ($row[EVENT_DETAILS] != "") {
							echo "[".lang("More Details")."...</a>]\n";
						} else {
							echo "[ ".lang("Click to View")." ]</a>.\n";
						}
					}

					if ($row[EVENT_DETAILPAGE] == "" && $row[EVENT_DETAILS] == "") {
						echo "<FONT COLOR=#999999>".lang("No details available for this event.")."</FONT>\n";
					}

					echo "</TD></TR></TABLE><BR CLEAR=ALL><BR>";

				} // End While Loop

		} else {	// Show Weekly display (different than others)


			   // Start building single column "day of the week" chart table
			   // =============================================================

				for ($DAY_COUNT=$START_DAY;$DAY_COUNT <= $END_DAY;$DAY_COUNT++) {

					#####
					#####
					##### BUILD ROW ONE
					#####
					#####

					echo "<TABLE BORDER=0 CELLPADDING=6 CELLSPACING=1 WIDTH=100% ALIGN=CENTER STYLE='border: 1px inset black;'>\n";
					echo "<TR>\n";
					echo "<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=#EFEFEF><FONT FACE=VERDANA SIZE=2 COLOR=BLACK>\n";

					$today ="'$SEL_YEAR-$SEL_MONTH-$DAY_COUNT";
					$dDateOwk = date("l", mktime(0,0,0,$SEL_MONTH,$DAY_COUNT,$SEL_YEAR));
					$my = date("(M j)", mktime(0,0,0,$SEL_MONTH,$DAY_COUNT,$SEL_YEAR));
					echo "<B>$dDateOwk</B> $my</TD></TR><TD ALIGN=LEFT VALIGN=TOP BGCOLOR=WHITE CLASS=text>\n";

					$WHERE_QUERY = "EVENT_DATE = '$SEL_YEAR-$SEL_MONTH-$DAY_COUNT'";


					if ($CHANGE_CAT != "ALL" && !eregi("AUTH:", $CHANGE_CAT)) {
						$CAT_QUERY = "AND EVENT_CATEGORY = '$CURRENT_CAT_KEY'";
					} else {
						$CAT_QUERY = "";
					}

					if (eregi("AUTH:", $CHANGE_CAT)) {
						$tmp = eregi_replace("AUTH:", "", $CHANGE_CAT);
						$CAT_QUERY = "AND EVENT_CATEGORY = '$tmp'";
					}

					$result = mysql_query("SELECT PRIKEY, EVENT_DATE, EVENT_TITLE, EVENT_CATEGORY, EVENT_DETAILS, EVENT_DETAILPAGE, EVENT_START, EVENT_END,
					EVENT_SECURITYCODE FROM calendar_events WHERE $WHERE_QUERY AND $SEC_SEARCH $CAT_QUERY ORDER BY EVENT_DATE, EVENT_START");

					$TOTAL_EVENTS_FOR_THIS_DAY = mysql_num_rows($result);

					while ($row = mysql_fetch_array($result)) {

								$cat_num = $row[EVENT_CATEGORY];

								if ($cat_num == "ALL") {
									$category = "All";
								} else {
									$category = $CAT_DISPLAY_NAME[$cat_num];
								}

								if ($cat_num == $MD5CODE) {
									$category = "<a href=\"".pagename($pr, "&")."EDIT_EVENT_RECORD=$row[PRIKEY]&PUBLIC_SUBMIT_EVENT=\">".$lang["Edit Event"]."</a>] &nbsp; [<a href=\"".pagename($pr, "&")."DELETE_EVENT_RECORD=$row[PRIKEY]&PUBLIC_SUBMIT_EVENT=\">".$lang["Delete Event"]."</a>";
								}

								echo "<TABLE BORDER=0 CELLPADDING=6 CELLSPACING=0 WIDTH=100% ALIGN=LEFT STYLE='border: 1px inset black;'>\n";
								echo "<TR>\n";
								echo "<TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=\"oldlace\">\n";

								if ($cat_num == $MD5CODE) {
									echo "<img src=\"sohoadmin/client_files/securelogin.gif\" alt=\"".$lang["This is your private event."]."\" border=0 hspace=2 vspace=2 align=absmiddle>\n";
								}

								$t = $row[EVENT_DATE];
								$tmp = split("-", $t);
								$dDate = date("F j, Y", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));

								$row[EVENT_TITLE] = stripslashes($row[EVENT_TITLE]);
								$row[EVENT_DETAILS] = stripslashes($row[EVENT_DETAILS]);

								echo "[ <FONT COLOR=DARKGREEN>".$category."</FONT> ] <FONT SIZE=2 FACE=VERDANA><B>$row[EVENT_TITLE]</B></FONT>\n";

									$tmp = split(":", $row[EVENT_START]);
									$tmp = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$SEL_MONTH,1,$SEL_YEAR));
									$event_time = $tmp;
									if ($row[EVENT_END] != "00:00:00") {
										$tmp = split(":", $row[EVENT_END]);
										$tmp = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$SEL_MONTH,1,$SEL_YEAR));
										$event_time .= "-$tmp";
									}

								echo "&nbsp;&nbsp;&nbsp;&nbsp; ($event_time)\n";
								echo "</TD></TR><TR><TD ALIGN=LEFT VALIGN=TOP CLASS=text BGCOLOR=\"WHITE\" STYLE='border-top: 1px inset black;'>\n";

								echo lang("Event Details").": ".$row[EVENT_DETAILS];

								if ($row[EVENT_DETAILPAGE] != "") {
									echo "<a href=\"#\" onclick=\"javscript: window.open('pgm-cal-details.inc.php?id=$row[PRIKEY]','EVENTDETAILS', 'scrollbars=yes,location=no,resizable=yes,width=470,height=400');\">";
									if ($row[EVENT_DETAILS] != "") {
										echo "[".lang("More Details")."...</a>]\n";
									} else {
										echo "[ ".lang("Click to View")." ]</a>.\n";
									}
								}

								if ($row[EVENT_DETAILPAGE] == "" && $row[EVENT_DETAILS] == "") {
									echo "<FONT COLOR=#999999>".lang("No details available for this event.")."</FONT>\n";
								}

								echo "</TD></TR></TABLE><BR CLEAR=ALL><BR>";

					} // End While Loop

					if ($TOTAL_EVENTS_FOR_THIS_DAY == 0) {
						echo "<FONT COLOR=#999999>".lang("There are no events scheduled for this day.")."</font>\n";
					}

			echo "</TD></TR></TABLE><BR>\n"; 	// End ROW loop

			}	// End For Each Day of the Week For Statement


		} // End Show Weekly

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~ END EACH DISPLAY ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		echo "</TD></TR></TABLE><BR>\n";

		if ($check_results == 0) {
			if (eregi("AUTH:", $CHANGE_CAT)) {
				$tmp = lang("your personal calendar").".<BR>($OWNER_NAME)";
			} else {
				$tmp = lang("in category").": \"$CHANGE_CAT\".";
			}
			echo "<DIV ALIGN=CENTER CLASS=text>".lang("There where no events found for your selection or search")." $tmp</DIV><BR>\n";
		}


		echo $END_FORM;

	} else {

		echo "<BR><BR><DIV ALIGN=CENTER CLASS=text>".lang("Please search for an event or select the day or week you wish to view.")."</DIV><BR><BR>\n";

	}  // End If Request Title Clicked

} // End Detail - Weekly/Daily Display


// If an authorized user is logged in; indicate at the bottom of each calendar screen
// WHO is logged in; and if; personal cal feature is on, display "LOCK KEY"
// -----------------------------------------------------------------------------------

if ($OWNER_NAME != "") {
	echo "<DIV ALIGN=CENTER CLASS=smtext><FONT COLOR=#999999>".lang("Authorized user logged in").": $OWNER_NAME</FONT><BR>\n";
	if ($DISPLAY[ALLOW_PERSONAL_CALENDARS] == "Y") {
		echo "<FONT COLOR=#999999><img src=\"sohoadmin/client_files/securelogin.gif\" alt=\"".lang("This is your private event.")."\" border=0 hspace=2 vspace=2 align=absmiddle> ".lang("Indicates your private event").". ".lang("No one else can view this event but")." $OWNER_NAME.</FONT></div>\n";
	}
}

?>