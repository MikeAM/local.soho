<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../includes/product_gui.php');
require_once("../../../includes/SohoEmail_class/SohoEmail.php");
chdir($curdir);

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

function GEN_KEY() {

	for ($GK=1;$GK<=15;$GK++) {
		$BASE_32 .= rand(0,999);
	}

	$BASE_32 .= date("gis");	// Append timestamp with seconds num to 100% guarantee rnd key
	$NEW_KEY = md5($BASE_32);
	return $NEW_KEY;

}

#######################################################
### PERFORM SAVE (NEW) EVENT ACTION				    ###
#######################################################

if ($_REQUEST['ACTION'] == "SAVE_EVENT") {

	// ---------------------------------------------------------------
	// First Get all Posted Variables into Memory and deal with them
	// ---------------------------------------------------------------

	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {
		$value = stripslashes($value);						// Strip all slashes from data for HTML execution
		${$name} = $value;
	}

	if (strlen($EVENT_TITLE) < 3) {
		header("Location: ../event_calendar.php?=SID");
		exit;
	}

	// Make sure passed date selection is dead on what sql is looking for in a date field

	$tmp = split("-", $EVENT_DATE);
	$EVENT_DATE = date("Y-m-d", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));

	$DATE_ARRAY = split("-", $EVENT_DATE);			// We will use this alot in recurrence routines

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Step 1: Build New "Master Event" regardless of Recurrence
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$THIS_KEY = GEN_KEY();
	$SQL_INSERT = "INSERT INTO calendar_events VALUES('$THIS_KEY','$EVENT_DATE', ";

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Generate Keyword Field for this event
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$KEYWORDS = "";
	$KEYWORDS .= date("F", mktime(0,0,0,$DATE_ARRAY[1], $DATE_ARRAY[2], $DATE_ARRAY[0])) . ";";
	$KEYWORDS .= date("M", mktime(0,0,0,$DATE_ARRAY[1], $DATE_ARRAY[2], $DATE_ARRAY[0])) . ";";

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Prepare title/detail vars for inclusion into data table
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

//	$EVENT_TITLE = slashthis($EVENT_TITLE);
//	$EVENT_DETAILS = slashthis($EVENT_DETAILS);
	if($_POST['EVENT_CARTPAGE']!=''){
		$EVENT_DETAILPAGE = $_POST['EVENT_CARTPAGE'];
	}
	$EVENT_TITLE = str_replace("'", "&rsquo;", $EVENT_TITLE);	
	$EVENT_DETAILS = str_replace("'", "&rsquo;", $EVENT_DETAILS);	
	$EVENT_DETAILPAGE = str_replace("'", "&rsquo;", $EVENT_DETAILPAGE);	
	$EVENT_CATEGORY = str_replace("'", "&rsquo;", $EVENT_CATEGORY);
	$KEYWORDS = str_replace("'", "&rsquo;", $KEYWORDS);
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Finish building intitial mySql query
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ( $_POST['START_TIME'] == "" ) { $custom_start = '[nothing]'; } else { $custom_start = ''; }
	if ( $_POST['END_TIME'] == "" ) { $custom_end = '[nothing]'; } else { $custom_end = ''; }
	$SQL_INSERT .= "'$KEYWORDS', '$START_TIME', '$END_TIME', '$EVENT_TITLE', '$EVENT_DETAILS', '$EVENT_CATEGORY', ";
	$SQL_INSERT .= "'$EVENT_DETAILPAGE', '$EVENT_EMAIL_CC', '$EVENT_SECURITYCODE', 'Y', '', '$custom_start', '$custom_end')";

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Insert New Master Event into calendar and get last insert
	// key id for use with recurrence if necessary
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	mysql_query("$SQL_INSERT");

	$LAST_KEY_ID = $THIS_KEY;


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// If set to Email this Event; Do It Now
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if (strlen($EVENT_EMAIL_CC) > 5) {

		$eheader = strtoupper($SERVER_NAME);

		$email_str = "** EVENT ADDED TO $eheader **\n\n";

		$tmp = split("-", $EVENT_DATE);
		$edate = date("l, F j, Y", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));
		$tmpt = split(":", $START_TIME);
		$etime = date("g:ia", mktime($tmpt[0],$tmpt[1],$tmpt[2],$tmp[1],$tmp[2],$tmp[0]));

		$etitle = stripslashes($EVENT_TITLE);
		$edetail = stripslashes($EVENT_DETAILS);

		$email_str .= "$etitle\n$edate ($etime)\n\n$edetail";
		$email_str .= "\n\n\n** THIS IS AN AUTO-GENERATED MESSAGE; DO NOT REPLY **";

		if(!SohoEmail($EVENT_EMAIL_CC, preg_replace('/^www\./i','',$_SESSION['this_ip']), "$SERVER_NAME Calendar Update", str_replace("\n","<br/>\n", $email_str))){
			mail("$EVENT_EMAIL_CC", "$SERVER_NAME Calendar Update", "$email_str", "From: webmaster@$SERVER_NAME");
		}

	} // End Email Event

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// RECURRENCE: DO WE EVEN HAVE TO DEAL WITH IT?
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($RECUR_FREQUENCY != "NONE") {

		// *********************************************
		// MAKE DAILY RECURRENCE HAPPEN
		// *********************************************

		if ($RECUR_FREQUENCY == "DAILY") {

			$this_day = $DATE_ARRAY[2];
			if ($RECUR_LENGTH == "LIMIT") { $limit_cnt = $RECUR_LIMIT_NUMBER - 1; } else { $limit_cnt = 365; }

			$x = 1;
			$NUM_TO_ADD = 0;

			while ($x <= $limit_cnt) {

				$this_day = $this_day + $DAILY_NUMDAYS;
				$this_date = date("Y-m-d", mktime(0,0,0, $DATE_ARRAY[1], $this_day, $DATE_ARRAY[0]));

				if ($this_date != $EVENT_DATE) {
					$NUM_TO_ADD++;
					$NEW_EVENT_ADD[$x] = $this_date;
				}

				$x++;

			} // End While


			// Add each recurrence to its own calendar record now

			for ($t=1;$t<=$NUM_TO_ADD;$t++) {

				$THIS_KEY = GEN_KEY();
				$SQL_INSERT = "INSERT INTO calendar_events VALUES('$THIS_KEY','$NEW_EVENT_ADD[$t]', ";
				$SQL_INSERT .= "'$KEYWORDS', '$START_TIME', '$END_TIME', '$EVENT_TITLE', '$EVENT_DETAILS', '$EVENT_CATEGORY', ";
				$SQL_INSERT .= "'$EVENT_DETAILPAGE', '$EVENT_EMAIL_CC', '$EVENT_SECURITYCODE', '$LAST_KEY_ID', '', '', '')";

				mysql_query("$SQL_INSERT");

			}

		} // End Daily Recur

		// **********************************************************************************
		// MAKE DAILY RECURRENCE HAPPEN
		// (Modified 2003-03-26 - Hasn't worked right since V4.0 Release; does now!
		// **********************************************************************************

		if ($RECUR_FREQUENCY == "WEEKLY") {

			if ($RECUR_LENGTH == "LIMIT") { $limit_cnt = $RECUR_LIMIT_NUMBER; } else { $limit_cnt = 365; }

			$x = 1;
			$NUM_TO_ADD = 0;

			$event_dow_num = date("w", mktime(0,0,0,$DATE_ARRAY[1], $DATE_ARRAY[2], $DATE_ARRAY[0])); 	// Numeric Day of Week 0=Sun, 1=Mon, etc.
			$start_week_day = $DATE_ARRAY[2] - $event_dow_num;											// What "Day of this Month" do we start the week?

			while ($x <= $limit_cnt) {		// Start Loop Through Each Ocurrence

				// Every Sunday?
				if (isset($WEEKLY1)) {
					$this_day = $start_week_day + 0;
					$this_date = date("Y-m-d", mktime(0,0,0,$DATE_ARRAY[1], $this_day, $DATE_ARRAY[0]));
					if ($this_date != $EVENT_DATE) {
						$NUM_TO_ADD++;
						$NEW_EVENT_ADD[$NUM_TO_ADD] = $this_date;
					}
				} // End each Sunday

				// Every Monday?
				if (isset($WEEKLY2)) {
					$this_day = $start_week_day + 1;
					$this_date = date("Y-m-d", mktime(0,0,0,$DATE_ARRAY[1], $this_day, $DATE_ARRAY[0]));
					if ($this_date != $EVENT_DATE) {
						$NUM_TO_ADD++;
						$NEW_EVENT_ADD[$NUM_TO_ADD] = $this_date;
					}
				} // End each Monday

				// Every Tue?
				if (isset($WEEKLY3)) {
					$this_day = $start_week_day + 2;
					$this_date = date("Y-m-d", mktime(0,0,0,$DATE_ARRAY[1], $this_day, $DATE_ARRAY[0]));
					if ($this_date != $EVENT_DATE) {
						$NUM_TO_ADD++;
						$NEW_EVENT_ADD[$NUM_TO_ADD] = $this_date;
					}
				} // End Tue

				// Every Wed?
				if (isset($WEEKLY4)) {
					$this_day = $start_week_day + 3;
					$this_date = date("Y-m-d", mktime(0,0,0,$DATE_ARRAY[1], $this_day, $DATE_ARRAY[0]));
					if ($this_date != $EVENT_DATE) {
						$NUM_TO_ADD++;
						$NEW_EVENT_ADD[$NUM_TO_ADD] = $this_date;
					}
				} // End Wed

				// Every Thur
				if (isset($WEEKLY5)) {
					$this_day = $start_week_day + 4;
					$this_date = date("Y-m-d", mktime(0,0,0,$DATE_ARRAY[1], $this_day, $DATE_ARRAY[0]));
					if ($this_date != $EVENT_DATE) {
						$NUM_TO_ADD++;
						$NEW_EVENT_ADD[$NUM_TO_ADD] = $this_date;
					}
				} // End Thur

				// Every Fri
				if (isset($WEEKLY6)) {
					$this_day = $start_week_day + 5;
					$this_date = date("Y-m-d", mktime(0,0,0,$DATE_ARRAY[1], $this_day, $DATE_ARRAY[0]));
					if ($this_date != $EVENT_DATE) {
						$NUM_TO_ADD++;
						$NEW_EVENT_ADD[$NUM_TO_ADD] = $this_date;
					}
				} // End Fri

				// Every Sat
				if (isset($WEEKLY7)) {
					$this_day = $start_week_day + 6;
					$this_date = date("Y-m-d", mktime(0,0,0,$DATE_ARRAY[1], $this_day, $DATE_ARRAY[0]));
					if ($this_date != $EVENT_DATE) {
						$NUM_TO_ADD++;
						$NEW_EVENT_ADD[$NUM_TO_ADD] = $this_date;
					}
				} // End Sat


				$jump_weeks = $WEEKLY_NUMWEEKS*7;					// Occur Every "How Many" Weeks?
				$start_week_day = $start_week_day + $jump_weeks;	// Progress to the "next" week (orig = 7)
				$x++;												// Increment our limit counter

			} // End While


			// Add each recurrence to its own calendar record now

			for ($t=1;$t<=$NUM_TO_ADD;$t++) {

				$THIS_KEY = GEN_KEY();
				$SQL_INSERT = "INSERT INTO calendar_events VALUES('$THIS_KEY','$NEW_EVENT_ADD[$t]', ";
				$SQL_INSERT .= "'$KEYWORDS', '$START_TIME', '$END_TIME', '$EVENT_TITLE', '$EVENT_DETAILS', '$EVENT_CATEGORY', ";
				$SQL_INSERT .= "'$EVENT_DETAILPAGE', '$EVENT_EMAIL_CC', '$EVENT_SECURITYCODE', '$LAST_KEY_ID', '', '', '')";

				// The next line is for testing purposes only -- comment out for normal operation
				// echo "($EVENT_TITLE) $NEW_EVENT_ADD[$t]<BR><BR>";

				mysql_query("$SQL_INSERT");

			} // End $t For Loop



		} // End Weekly Recur

		// *********************************************
		// MAKE MONTHLY RECURRENCE HAPPEN
		// *********************************************

		if ($RECUR_FREQUENCY == "MONTHLY") {

			$cur_month = $DATE_ARRAY[1];
			$cur_year = $DATE_ARRAY[0];

			if ($RECUR_LENGTH == "LIMIT") { $last_month = $cur_month + $RECUR_LIMIT_NUMBER; } else { $last_month = $cur_month + 365; }

			$NUM_TO_ADD = 0;

			for ($x=$cur_month;$x<=$last_month;$x++) {   // Start loop through each month set by limit

				$num_days_in_month = date("t", mktime(0,0,0,$x,1,$cur_year));
				$match_counter = 0;

				for ($i=1;$i<=$num_days_in_month;$i++) {	// Loop through first seven days of month to find a "DAYOFWEEK" match

						$this_dow = date("l", mktime(0,0,0,$x,$i,$cur_year));
						$this_date = date("Y-m-d", mktime(0,0,0,$x,$i,$cur_year));

						if (eregi("$this_dow", $MONTHLY_DOW)) {
							$match_counter++;
							if ($match_counter == $MONTHLY_NUM && $this_date != $EVENT_DATE) {
								$NUM_TO_ADD++;
								$NEW_EVENT_ADD[$NUM_TO_ADD] = $this_date;
							}
						} // End if Dow Match is found (It's only going to be one per week

				} // End Month loop

			} // End Limit Loop

			for ($t=1;$t<=$NUM_TO_ADD;$t++) {

				$THIS_KEY = GEN_KEY();
				$SQL_INSERT = "INSERT INTO calendar_events VALUES('$THIS_KEY','$NEW_EVENT_ADD[$t]', ";
				$SQL_INSERT .= "'$KEYWORDS', '$START_TIME', '$END_TIME', '$EVENT_TITLE', '$EVENT_DETAILS', '$EVENT_CATEGORY', ";
				$SQL_INSERT .= "'$EVENT_DETAILPAGE', '$EVENT_EMAIL_CC', '$EVENT_SECURITYCODE', '$LAST_KEY_ID', '', '', '')";

				mysql_query("$SQL_INSERT");

			}

		} // End Monthly

		// *********************************************
		// MAKE YEARLY RECURRENCE HAPPEN
		// *********************************************

		if ($RECUR_FREQUENCY == "YEARLY") {

			if ($RECUR_LENGTH == "LIMIT") { $cnt = $RECUR_LIMIT_NUMBER; } else { $cnt = 10; }

			$N = $DATE_ARRAY[0];

			for ($x=1;$x<=$cnt;$x++) {

				$N = $N + 1;

				$NEW_EVENT_ADD = date("Y-m-d", mktime(0,0,0,$DATE_ARRAY[1], $DATE_ARRAY[2], $N));

				if ($NEW_EVENT_ADD != $EVENT_DATE) {

					$THIS_KEY = GEN_KEY();
					$SQL_INSERT = "INSERT INTO calendar_events VALUES('$THIS_KEY','$NEW_EVENT_ADD', ";
					$SQL_INSERT .= "'$KEYWORDS', '$START_TIME', '$END_TIME', '$EVENT_TITLE', '$EVENT_DETAILS', '$EVENT_CATEGORY', ";
					$SQL_INSERT .= "'$EVENT_DETAILPAGE', '$EVENT_EMAIL_CC', '$EVENT_SECURITYCODE', '$LAST_KEY_ID', '', '', '')";
					mysql_query("$SQL_INSERT");
				}

			}

		} // End Yearly Recurring

	} // End Build Recurring as a whole

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Redirect to Main Calendar Manager with "added date" month
	// and year -- so the calendar comes up in "editing month"
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	header("Location: ../event_calendar.php?added_flag=1&SEL_MONTH=$DATE_ARRAY[1]&SEL_YEAR=$DATE_ARRAY[0]&=SID");
	exit;

} // End Add New Event Action


# Start buffering output
ob_start();
?>



<script language="JavaScript">
<!--
function SV2_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;
}
function SV2_showHideLayers() { //v3.0
  var i,p,v,obj,args=SV2_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=SV2_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}
function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}
function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');

//-->
</script>

<?

if ($mode == "add") {

	ob_start();
		include("includes/add_events_form.php");
		$THIS_DISPLAY .= ob_get_contents();
	ob_end_clean();

}

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Enter all the specifics of this event.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Add Event";
$module->add_breadcrumb_link("Event Calendar", "program/modules/mods_full/event_calendar.php");
$module->add_breadcrumb_link("Add Event", "program/modules/mods_full/event_calendar.php");
$module->icon_img = "program/includes/images/calendar-icon-med.png";
$module->heading_text = "Add Event";
$module->description_text = $instructions;
$module->good_to_go();
?>