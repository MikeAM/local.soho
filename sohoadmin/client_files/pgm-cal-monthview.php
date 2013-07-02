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

########################################################################
### THIS INCLUDE IS ALSO USED WITH THE CALENDAR SYSTEM FOR DISPLAY.
### IF SO THE REUSE FLAG WILL BE SET TO 1; OTHERWISE THIS IS USED AS
### THE STAND ALONE "MONTHLY VIEW" CATEGORY OBJECT FROM THE PAGE EDITOR.
########################################################################


// If this .inc is not being reused, set the SELECTED month and YEAR as
// this year and display the category as passed from the page editor.
// Otherwise Replace the Category Name display with a drop down tag
// for the Main Calendar System to interpret with selection boxes.
// -------------------------------------------------------------------------

if($hide_drop_down == 1){
   if ($SEL_MONTH == "") { $SEL_MONTH = date("m"); }	// Set default dates if drop down not active
   if ($SEL_YEAR == "") { $SEL_YEAR = date("Y"); }
	$DISP_CAT_NAME = "##DROPDOWN##";
	$DISP_CAT_NAME .= "</td><td><B><I>".$lang["Category"].": $CHANGE_CAT</I></B></font>";
	$REUSE_FLAG = '';
} else {
	if ($REUSE_FLAG != 1) {
//		echo 'not dropdown';
		$DISP_CAT_NAME = "<div align=right><font size=1 face=Arial><B><I>".lang("Category").": ".str_replace('"', '', str_replace("'", '', $CHANGE_CAT))."</I></B></font></div>";
		$SEL_MONTH = date("m");
		$SEL_YEAR = date("Y");

	} else {
		$DISP_CAT_NAME = "##DROPDOWN##";
	}
}

// Because of the page editor, we are passing the category value as the
// actual "Category_Name" for the calendar.  Normally we would use the
// PriKey field and convert for display.  Let's Convert the "Name" to the
// "PriKey" number so we can locate events by category in our SQL query.
// -------------------------------------------------------------------------

$CHANGE_CAT = ltrim($CHANGE_CAT);	// Make sure there are no trailing or preface spaces in the var
$CHANGE_CAT = rtrim($CHANGE_CAT);

$vres = mysql_query("SELECT * FROM calendar_category");
if ($CHANGE_CAT != "ALL" && $CHANGE_CAT != "All" && !eregi("AUTH:", $CHANGE_CAT)) {
	while ($t = mysql_fetch_array($vres)) {
		if ($t[Category_Name] == $CHANGE_CAT) { $CHANGE_CAT = $t[PRIKEY]; }
	}
} else {
	if (eregi("AUTH:", $CHANGE_CAT)) {
		// Leave Change Cat Alone
	} else {
		$CHANGE_CAT = "ALL";
	}
}

// Setup display and date array variables that will be used for the display
// routine to follow.
// -------------------------------------------------------------------------

$day_of_week = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");

$disp = mysql_query("SELECT * FROM calendar_display");
$DISPLAY = mysql_fetch_array($disp);

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
	if ($DISPLAY[ALLOW_PERSONAL_CALENDARS] == "Y") {
		$SEC_SEARCH .= " OR EVENT_CATEGORY = '".$MD5CODE."'";
	}
	if ($flag != 0) { $SEC_SEARCH = "($SEC_SEARCH)"; }
}


// Let's perform the actual SQL query to find all events for the SELECTED
// YEAR and MONTH based on the category view choosen or passed by page
// editor.
// -------------------------------------------------------------------------
//if(!mysql_query('select custom_start from calendar_events limit 1')){
//	//mysql_query('alter table calendar_events change FUTURE1 custom_start varchar(255)');
//	//mysql_query('alter table calendar_events change FUTURE2 custom_end varchar(255)');
//}


$tmp = "$SEL_YEAR-$SEL_MONTH-";		// Setup LIKE variable for Selected Year/Month var

if ($CHANGE_CAT != "ALL") {

	if (eregi("AUTH:", $CHANGE_CAT)) {
		$tmp_cat = eregi_replace("AUTH:", "", $CHANGE_CAT);
	} else {
		$tmp_cat = $CHANGE_CAT;
	}

	$result = mysql_query("SELECT PRIKEY, EVENT_DATE, EVENT_CATEGORY, EVENT_TITLE, EVENT_DETAILS, EVENT_DETAILPAGE, EVENT_START, EVENT_END, custom_start, custom_end
						FROM calendar_events WHERE EVENT_DATE LIKE '$tmp%' AND $SEC_SEARCH AND EVENT_CATEGORY = '$tmp_cat' ORDER BY EVENT_DATE, EVENT_START");
} else {

	$result = mysql_query("SELECT PRIKEY, EVENT_DATE, EVENT_CATEGORY, EVENT_TITLE, EVENT_DETAILS, EVENT_DETAILPAGE, EVENT_START, EVENT_END, custom_start, custom_end
						FROM calendar_events WHERE EVENT_DATE LIKE '$tmp%' AND $SEC_SEARCH ORDER BY EVENT_DATE, EVENT_START");
}

// Get the total number of events the query returns and place the values into
// an array (the only place we do this) so that we can build the calendar on
// the fly; pluging in EVENTS as the dates present themselves
// -------------------------------------------------------------------------

$NUM_EVENTS = mysql_num_rows($result);

$x=0;

while ($row = mysql_fetch_array($result)) {
	$x++;

	$DB_EVENT_PRIKEY[$x] = $row[PRIKEY];
	$DB_EVENT_DATE[$x] = $row[EVENT_DATE];
	$DB_EVENT_TITLE[$x] = $row[EVENT_TITLE];
	$DB_EVENT_DETAILS[$x] = $row[EVENT_DETAILS];
	$DB_EVENT_DETAILPAGE[$x] = $row[EVENT_DETAILPAGE];
	$DB_EVENT_START[$x] = $row[EVENT_START];
	$DB_EVENT_END[$x] = $row[EVENT_END];
	$DB_EVENT_CATEGORY[$x] = $row[EVENT_CATEGORY];
	$DB_custom_start[$x] = $row['custom_start'];
	$DB_custom_end[$x] = $row['custom_end'];

} // End While Loop


// Setup the numbers that allow us to start building this Year/Month
// Calendar display.
// -------------------------------------------------------------------------

$NUM_DAYS_IN_MONTH = date("t", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));
$START_DOW = date("l", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));			// What day of week does month start on?

// Is the current month the month we are displaying? If so, we want to
// highlight special, "TODAY".
// -------------------------------------------------------------------------

if ($SEL_MONTH == date("m")) { $HIGHLIGHT = "on"; $HIGHLIGHT_DAY = date("j"); }

// Setup the actual display variable that shows MONTH YEAR
// -------------------------------------------------------------------------

$top_month = date("F", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));
$top_year = date("Y", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));

########################################################################
### START BUILDING CALENDAR FOR DISPLAY :: HEADER
########################################################################

if ($REUSE_FLAG == 1) {

	echo "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"99%\" align=\"center\">\n";

   # Month & Year
	echo " <tr>\n";
	echo "  <td colspan=\"7\" align=\"center\" valign=\"top\">\n";
	echo "   <font face=\"verdana\" size=\"4\"><b>".lang($top_month)." ".$top_year."</b></font>\n";
	echo "  </td>\n";
	echo " </tr>\n";

	echo " <tr>\n";
	echo "  <td align=center colspan=7 style='border-bottom: 0px inset black;'><br>".$DISP_CAT_NAME."</td>\n";
	echo " </tr>\n";

	echo "</TABLE><BR>\n";

} else {

	echo "<table border=0 cellpadding=2 cellspacing=0 width=99% align=center>\n";
	echo " <tr>\n";
	echo "  <td colspan=4 align=left valign=top>\n";
	echo "   <font face=verdana size=2><b>".lang($top_month)." ".$top_year."</b></font></td>\n";
	echo "  <td align=right colspan=3 style='border-bottom: 0px inset black;'>".$DISP_CAT_NAME."</td>\n";
	echo " </tr>\n";
	echo "</table>\n";


} // End Header base on Reuse Flag

########################################################################
### START BUILDING CALENDAR FOR DISPLAY :: ROW ONE = DAYS OF WEEK
########################################################################

echo "<table border=\"1\" cellpadding=\"2\" cellspacing=\"0\" width=\"99%\" align=\"center\" style=\"border-color: black;\" id=\"calendar_monthview\">\n";

	# Col headings - Sunday | Monday | Tuesday
	echo " <tr>\n";
	for ($x=0;$x<=6;$x++) {
		echo "  <th align=\"center\" valign=\"middle\" width=\"150\" bgcolor=\"#".$DISPLAY['BACKGROUND_COLOR']."\" class=\"text\">";
		echo "   <font color=\"".$DISPLAY['TEXT_COLOR']."\" face=\"Verdana\" size=\"2\">\n";
		echo "   <b>".lang($day_of_week[$x])."</b>\n";
		echo "   <font>";
		echo "  </th>\n";

	}
	echo "</tr>\n";

	// -----------------------------------------------------------------
	// Display first week based on when first day of month starts
	// -----------------------------------------------------------------

	echo "\n<TR>\n";

	$FLAG = 0;
	$display_day = 1;

	for ($x=0;$x<=6;$x++) {

		# Day of month exist for this weekday?
		if (eregi("$START_DOW", $day_of_week[$x]) || $FLAG == 1) {
         # YES - Show date and events and such
			if ($HIGHLIGHT == "on" && $display_day == $HIGHLIGHT_DAY) { $BGCOLOR = "OLDLACE"; $fontColor = "#000"; } else { $BGCOLOR = ""; $fontColor = "inherit"; }

			echo "  <td align=\"left\" valign=\"top\" bgcolor=\"".$BGCOLOR."\" class=\"day_square smtext\" style=\"height: 100px; width: 100px;color: ".$fontColor.";\">\n";
			echo "   <font face=verdana size=2>\n";
			echo "   <div style=\"background-color: #".$DISPLAY['BACKGROUND_COLOR']."; padding: 1px; color: #".$DISPLAY['TEXT_COLOR']."; width: 20px; border: 1px solid black;\">\n";
			echo "    <b>".lang("$display_day")."</b>\n";
			echo "   </div>\n";
			echo "   </font><br clear=all>";

			// ========================================================================
			// Display Events for this date
			// ========================================================================

			for ($z=0;$z<=$NUM_EVENTS;$z++) {

				$mm = "";
				$tmp = split("-", $DB_EVENT_DATE[$z]);
				$look_for = $tmp[2];

				if ( $look_for == $display_day ) {
				   # Event found for this day of month
					$tmp = split(":", $DB_EVENT_START[$z]);

               # Start time
               if ( ($DB_EVENT_START[$z] == "00:00:00" && $DB_EVENT_END[$z] == "00:00:00") || ($DB_EVENT_START[$z] == "00:00:00" && $DB_custom_start[$z] == "[nothing]") ) {
                  $mm = ""; // v4.9.2 r15 - fixes bug where "12:00am" would show for events with no end time asigned"
               } else {
	            	$mm = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$this_month,1,$this_year));
					}

//					if ($tmp[0] != "00") {
//						$mm = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$this_month,1,$this_year));
//					}

					if ($DB_EVENT_CATEGORY[$z] == $MD5CODE) {
						echo "<img src=\"sohoadmin/client_files/securelogin.gif\" alt=\"This is your private event.\" border=0 hspace=2 vspace=2 align=absmiddle>\n";
					}

					if (strlen($DB_EVENT_DETAILS[$z]) > 3 || $DB_EVENT_DETAILPAGE[$z] != "") {
						echo "<span class=\"event-container\"><span class=\"event-title\"><a href=\"#\" style=\"color: ".$fontColor.";\" onclick=\"javscript: window.open('pgm-cal-details.inc.php?id=$DB_EVENT_PRIKEY[$z]','EVENTDETAILS', 'scrollbars=yes,location=no,resizable=yes,width=470,height=400');\">";
						echo "$DB_EVENT_TITLE[$z]</a></span><BR><span class=\"event-time\">$mm";
					} else {
						echo "<span class=\"event-title\">".$DB_EVENT_TITLE[$z]."</span><BR><span class=\"event-time\">$mm";
					}

               $tmp = split(":", $DB_EVENT_END[$z]);

               # End time
               if ( ($DB_EVENT_START[$z] == "00:00:00" && $DB_EVENT_END[$z] == "00:00:00") || ($DB_EVENT_END[$z] == "00:00:00" && $DB_custom_end[$z] == "[nothing]") ) {
                  $mm = ""; // v4.9.2 r15 - fixes bug where "12:00am" would show for events with no end time asigned"
               } else {
	            	$mm = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$this_month,1,$this_year));
					}
					if($mm != ''){
						echo " - $mm";
					}
					
					echo "</span></span>"; // Closes event-time and event-container spans
					echo "<BR><BR>";

				} // End Found Event

			} // End Event Loop

			// ========================================================================

			echo "\n</TD>\n";

			$display_day++;
			$FLAG = 1;

		} else {
         # NO - This a (leading) dead day square
			echo "  <td align=\"left\" valign=\"top\" class=\"dead_daysquare text\" style=\"height: 75px;\">";
			echo "   &nbsp;";
			echo "  </td>\n";

		}
	}
	echo "</TR>\n";

	// -----------------------------------------------------------------
	// Finish out display of remaining weeks in selected month
	// -----------------------------------------------------------------

	$FLAG=0;
	$NUM_ROWS = $NUM_DAYS_IN_MONTH/7;
	if ($NUM_ROWS > 4) { $NUM_ROWS = 5; }

	for ($x=1;$x<=$NUM_ROWS;$x++) {

		echo "<TR>\n";

		# Loop through days in this month
		for ( $y=1;$y<=7;$y++ ) {

			# As long as FLAG == 0 there are days left in the month?
			if ( $FLAG != 1 ) {

				if ($HIGHLIGHT == "on" && $display_day == $HIGHLIGHT_DAY) { $BGCOLOR = "OLDLACE"; $fontColor = "#000"; } else { $BGCOLOR = ""; $fontColor = "inherit"; }

				echo "  <td align=\"left\" valign=\"top\" bgcolor=\"".$BGCOLOR."\" class=\"day_square smtext\" style='height: 100px; width: 100px;color: ".$fontColor.";'>\n";

				echo "   <b><font face=\"verdana\" size=2><div style=\"background-color: #".$DISPLAY['BACKGROUND_COLOR']."; padding: 1px; color: #".$DISPLAY['TEXT_COLOR']."; width: 20px; border: 1px solid black;\">$display_day</div></font></b>";

				// ========================================================================
				// Display Events for this date
				// ========================================================================

				for ($z=0;$z<=$NUM_EVENTS;$z++) {
					$mm = "";
					$tmp = split("-", $DB_EVENT_DATE[$z]);
					$look_for = $tmp[2];

					if ($look_for == $display_day) {	// Event found for this day of month

						$tmp = split(":", $DB_EVENT_START[$z]);

                  if ( ($DB_EVENT_START[$z] == "00:00:00" && $DB_EVENT_END[$z] == "00:00:00") || ($DB_EVENT_START[$z] == "00:00:00" && $DB_custom_start[$z] == "[nothing]") ) {
                     $mm = ""; // v4.9.2 r15 - fixes bug where "12:00am" would show for events with no end time asigned"
                  } else {
   	            	$mm = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$this_month,1,$this_year));
   					}

						if ($DB_EVENT_CATEGORY[$z] == $MD5CODE) {
							echo "<img src=\"sohoadmin/client_files/securelogin.gif\" alt=\"This is your private event.\" border=0 hspace=2 vspace=2 align=absmiddle>\n";
						}

						if (strlen($DB_EVENT_DETAILS[$z]) > 3 || $DB_EVENT_DETAILPAGE[$z] != "") {
							echo "<span class=\"event-container\"><span class=\"event-title\"><a href=\"#\" style=\"color: ".$fontColor.";\" onclick=\"javscript: window.open('pgm-cal-details.inc.php?id=$DB_EVENT_PRIKEY[$z]','EVENTDETAILS', 'scrollbars=yes,location=no,resizable=yes,width=470,height=400');\">";
							echo "$DB_EVENT_TITLE[$z]</a></span><BR><span class=\"event-time\">$mm";
						} else {
							echo "<span class=\"event-title\">$DB_EVENT_TITLE[$z]</span><BR><span class=\"event-time\">$mm";
						}


                  # End time
                  if ( ($DB_EVENT_START[$z] == "00:00:00" && $DB_EVENT_END[$z] == "00:00:00") || ($DB_EVENT_END[$z] == "00:00:00" && $DB_custom_end[$z] == "[nothing]") ) {
                     $mm = ""; // v4.9.2 r15 - fixes bug where "12:00am" would show for events with no end time asigned"
                  } else {
                     $tmp = split(":", $DB_EVENT_END[$z]);
                     $mm = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$this_month,1,$this_year));

   					}
   					if($mm != ''){
	   					echo " - $mm";
	   				}
	   				echo "</span></span>"; // Closes event-time and event-container spans

						echo "<BR><BR>";

					} // End Found Event

				} // End Event Loop

				// ========================================================================

				echo "\n</TD>\n";

			} else {

				# FLAG = 1, out of days for this money, show empty cell
				echo "  <td align=\"left\" valign=\"top\" bgcolor=\"#EFEFEF\" class=\"dead_daysquare text\" style=\"height: 75px;\">";
				echo "   &nbsp;";
				echo "  </td>\n";

			}

			if ($display_day == $NUM_DAYS_IN_MONTH) { $FLAG = 1; }

			$display_day++;

		} // End Week ($y) Loop

		echo "\n</TR>\n";

	} // End Month ($x) Loop

	// -----------------------------------------------------------------
	// End Calendar Display
	// -----------------------------------------------------------------

	echo "\n\n</TABLE>\n";


?>