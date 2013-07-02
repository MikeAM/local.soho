<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../../includes/product_gui.php');
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

?>


<SCRIPT LANGUAGE=Javascript>

function start_recur(a) {
	no_recur();
	RSECONDARY.style.display = '';
	eval(a+".style.display = '';");
}

function no_recur() {
	RSECONDARY.style.display = 'none';
	RECUR1.style.display = 'none';
	RECUR2.style.display = 'none';
	RECUR3.style.display = 'none';
	RECUR4.style.display = 'none';
}

function edit_recur(a,t) {
	window.location = "edit_event.php?id="+a+"&type="+t+"&<?=SID?>";
}

</SCRIPT>

<?php

	$tmp = split("-", $DATA[EVENT_DATE]); 	$this_date = date("l, F j, Y", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));


	// DOES THIS EVENT HAVE RECURRENCES ASSOCIATED WITH IT?

	if ($type == "m") {
		$result = mysql_query("SELECT PRIKEY, EVENT_DATE, RECUR_MASTER FROM calendar_events WHERE RECUR_MASTER = '$DATA[PRIKEY]' ORDER BY EVENT_DATE");
		$recur_exist_flag = mysql_num_rows($result);
	} else {
		$result = mysql_query("SELECT PRIKEY, EVENT_DATE, RECUR_MASTER FROM calendar_events WHERE RECUR_MASTER = '$DATA[RECUR_MASTER]' OR PRIKEY = '$DATA[RECUR_MASTER]' ORDER BY EVENT_DATE");
		$recur_exist_flag = mysql_num_rows($result);
	}


?>

<FORM NAME="updateform" METHOD=POST ACTION="edit_event.php">
<INPUT TYPE=HIDDEN NAME="ACTION" VALUE="1">
<INPUT TYPE=HIDDEN NAME="ID" VALUE="<? echo $DATA[PRIKEY]; ?>">
<INPUT TYPE=HIDDEN NAME="EVENT_DATE" VALUE="<? echo $DATA[EVENT_DATE]; ?>">

<TABLE WIDTH="700" BORDER="0" ALIGN="CENTER" CELLPADDING="5" CELLSPACING="0" CLASS=text STYLE='border: 1px inset black;'>
  <TR>
    <TD WIDTH="50%" ALIGN="LEFT" VALIGN="TOP" BGCOLOR="#708090"><FONT COLOR="#FFFFFF"><B><? echo $lang["Apply To"]; ?></B>:</FONT>
	  <SELECT NAME="APLLY_SAVE_ACTION" CLASS=text style='width: 250px; background: #EFEFEF;'>
        <OPTION VALUE="A" SELECTED><? echo $lang["THIS INDIVIDUAL EVENT ONLY"]; ?></OPTION>

		<?

		if ($recur_exist_flag > 0) {
			echo "<OPTION VALUE=\"B\" SELECTED>".$lang["ALL OCCURRENCES OF THIS EVENT"]."</OPTION>\n";
		}

		?>

      </SELECT></TD>
    <TD WIDTH="50%" ALIGN=RIGHT BGCOLOR="#708090">
	  <INPUT TYPE="SUBMIT" NAME="DELETEEVENTNOW" VALUE=" Delete Event " CLASS="FormLt1" STYLE='background: maroon; border: 1px solid black; color: white;'>
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  <INPUT TYPE="SUBMIT" VALUE=" Save Changes " CLASS="FormLt1" STYLE='background: darkgreen; border: 1px solid black; color: white;'>
	 </TD>
  </TR>
  <TR ALIGN="RIGHT">
    <TD WIDTH="50%" ALIGN="LEFT" BGCOLOR="#EFEFEF"><? echo lang("Event Date"); ?>: <B><? echo $this_date; ?></B>
      &nbsp;&nbsp;</TD>     <TD WIDTH="50%" BGCOLOR="#EFEFEF"> <? echo lang("Start Time"); ?>:
      <SELECT ID="START_TIME" NAME="START_TIME" CLASS="text" STYLE='width: 75px;'>
		<OPTION VALUE="" SELECTED>N/A</OPTION>

		<?

		# Select start time from drop-down
      function isstm( $chk ) {
         global $DATA;
         if ( $chk == $DATA['EVENT_START'] ) {
            return " selected";
         }
      }

      # Select end time from drop-down
      function isetm( $chk ) {
         global $DATA;
         if ( $chk == $DATA['EVENT_END'] ) {
            return " selected";
         }
      }

		for ($z=1;$z<=24;$z++) {

			$v = $z;
			$v2 = $z;

			if ($z > 12) { $v = $z-12; }
			if ($v2 < 10) { $v2 = "0".$v2; }

			if ($v == 12) { $clock_flag = "pm"; }
			if ($z == 24) { $clock_flag = "am"; }

			$d = "$v:00 $clock_flag";
			// if ($d == "9:00 am") { $SEL = "SELECTED"; } else { $SEL = ""; }
			if($DATA['EVENT_START'] == $v2.':00:00'){ $SEL = ' SELECTED'; } else { $SEL = ''; }
			echo "<OPTION VALUE=\"$v2:00\" STYLE='background: #EFEFEF; color: black;' $SEL>$d</OPTION>\n";
			if($DATA['EVENT_START'] == $v2.':15:00'){ $SEL = ' SELECTED'; } else { $SEL = ''; }
			echo "<OPTION VALUE=\"$v2:15\" STYLE='color: #999999;'>$v:15 $clock_flag</OPTION>\n";
			if($DATA['EVENT_START'] == $v2.':30:00'){ $SEL = ' SELECTED'; } else { $SEL = ''; }
			echo "<OPTION VALUE=\"$v2:30\" STYLE='color: #999999;'>$v:30 $clock_flag</OPTION>\n";
			if($DATA['EVENT_START'] == $v2.':45:00'){ $SEL = ' SELECTED'; } else { $SEL = ''; }
			echo "<OPTION VALUE=\"$v2:45\" STYLE='color: #999999;'>$v:45 $clock_flag</OPTION>\n";
			
			

			
		}

		?>

      </SELECT><? echo lang("End Time"); ?>:
      <SELECT ID="END_TIME" NAME="END_TIME" CLASS="text" STYLE='width: 75px;'>
       <option value="" <?php echo isetm(""); ?>>N/A</option>

		<?

		for ($z=1;$z<=24;$z++) {

			$v = $z;
			$v2 = $z;

			if ($z > 12) { $v = $z-12; }
			if ($v2 < 10) { $v2 = "0".$v2; }

			if ($v == 12) { $clock_flag = "pm"; }
			if ($z == 24) { $clock_flag = "am"; }

			$d = "$v:00 $clock_flag";
			if($DATA['EVENT_END'] == $v2.':00:00'){ $SEL = ' SELECTED'; } else { $SEL = ''; }
			echo "<OPTION VALUE=\"$v2:00\" STYLE='background: #EFEFEF; color: black;' $SEL>$d</OPTION>\n";
			if($DATA['EVENT_END'] == $v2.':15:00'){ $SEL = ' SELECTED'; } else { $SEL = ''; }
			echo "<OPTION VALUE=\"$v2:15\" STYLE='color: #999999;'>$v:15 $clock_flag</OPTION>\n";
			if($DATA['EVENT_END'] == $v2.':30:00'){ $SEL = ' SELECTED'; } else { $SEL = ''; }
			echo "<OPTION VALUE=\"$v2:30\" STYLE='color: #999999;'>$v:30 $clock_flag</OPTION>\n";
			if($DATA['EVENT_END'] == $v2.':45:00'){ $SEL = ' SELECTED'; } else { $SEL = ''; }
			echo "<OPTION VALUE=\"$v2:45\" STYLE='color: #999999;'>$v:45 $clock_flag</OPTION>\n";

		}

		?>
      </SELECT> </TD>
  </TR>
  <TR BGCOLOR="#EFEFEF">
    <TD COLSPAN="2"><? echo lang("Event Title"); ?>:<BR> <INPUT TYPE="text" NAME="EVENT_TITLE" CLASS="text" STYLE='width: 100%;' VALUE="<? echo $DATA[EVENT_TITLE]; ?>">
    </TD>
  </TR>
  <TR BGCOLOR="#EFEFEF">
    <TD><? echo lang("Event Details (Description)"); ?>:<BR> <TEXTAREA NAME="EVENT_DETAILS" CLASS="text" STYLE="width: 100%; HEIGHT: 115px;" WRAP=VIRTUAL><? echo $DATA[EVENT_DETAILS]; ?></TEXTAREA>
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><? echo lang("Event Category"); ?>:<BR> <SELECT NAME="EVENT_CATEGORY" CLASS="text" STYLE='width: 200px;'>
        <OPTION VALUE="ALL" SELECTED><? echo lang("All"); ?></OPTION>
        <?

		$resulta = mysql_query("SELECT * FROM calendar_category ORDER BY Category_Name");
		while ($row = mysql_fetch_array($resulta)) {
		   if ( $row['PRIKEY'] == $DATA['EVENT_CATEGORY'] ) {
		      $sel = " selected";
		   } else {
		      $sel = "";
		   }
			echo "<OPTION VALUE=\"$row[PRIKEY]\"".$sel.">$row[Category_Name]</OPTION>\n";
		}

		?>
      </SELECT>
      <BR>
      <BR>
      <? echo lang("Security Code (Group)"); ?>:<BR> <SELECT NAME="EVENT_SECURITYCODE" CLASS="text" ID="EVENT_SECURITYCODE" STYLE='width: 200px;'>
        <OPTION VALUE="Public" SELECTED><? echo lang("Public"); ?></OPTION>
		<?

		$resulta = mysql_query("SELECT * FROM sec_codes ORDER BY security_code");
		while ($row = mysql_fetch_array($resulta)) {
		   if ( $row['security_code'] == $DATA['EVENT_SECURITYCODE'] ) {
		      $sel = " selected";
		   } else {
		      $sel = "";
		   }
			echo "<OPTION VALUE=\"$row[security_code]\"".$sel.">$row[security_code]</OPTION>\n";
		}

		?>
      </SELECT>
      <BR>
      <BR>
      <? echo lang("Detail Page:"); ?>:<BR> <SELECT NAME="EVENT_DETAILPAGE" CLASS="text" ID="EVENT_DETAILPAGE" STYLE='width: 200px;'>
        <OPTION VALUE="" SELECTED>N/A</OPTION>
		<?

		// V4.6 Mod - No reliance upon page_type
		$resulta = mysql_query("SELECT page_name, url_name, link FROM site_pages ORDER BY page_name");
		while ($row = mysql_fetch_array($resulta)) {
			if(!preg_match('/^http:/i', $row['link'])){
			   if ( $row['page_name'] == $DATA['EVENT_DETAILPAGE'] ) {
			      $sel = " selected";
			   } else {
			      $sel = "";
			   }
				echo "<OPTION VALUE=\"$row[page_name]\"".$sel.">$row[page_name]</OPTION>\n";
			}
		}

		?>
      </SELECT> </TD>
  </TR>
  <TR>
    <TD BGCOLOR="#EFEFEF" COLSPAN=2>
     <? echo lang("When saving or changing this event, email a notice to the following email addresses"); ?>:
     <FONT COLOR=#999999>(<? echo lang("Use commas to seperate multiple email addresses"); ?>)</FONT><BR>
      <INPUT TYPE="text" NAME="EVENT_EMAIL_CC" CLASS="text" STYLE='width: 100%;' VALUE="<? echo $DATA[EVENT_EMAILNOTIFY]; ?>">
    </TD>

  </TR>

<!-- RECURRENCE SECTION START --------------------------------- -->

  <TR>
    <TD WIDTH="50%" COLSPAN="2" ALIGN="LEFT" VALIGN="TOP" BGCOLOR="#708090"><FONT COLOR="#FFFFFF" FACE=VERDANA SIZE=2>
     <B><? echo lang("Event Recurrence"); ?> </B></FONT> <FONT COLOR="#FFFFFF">&nbsp;</FONT></TD>
  </TR>


<?

	###########################################################################
	### ONLY SHOW THE FOLLOWING RECURRENCE CHOICES IF NO SLAVE KEYS HAVE BEEN
	### USED ON THIS MASTER KEY BEFORE
	###########################################################################

?>


  <TR ALIGN="RIGHT" ID="MAINRECUR" STYLE='DISPLAY: NONE;'>
    <TD WIDTH="50%" COLSPAN="2" ALIGN="LEFT" BGCOLOR="#EFEFEF">
	<INPUT TYPE="radio" NAME="RECUR_FREQUENCY"VALUE="NONE" CHECKED onclick="no_recur();">
      <? echo lang("No Recurrence"); ?> &nbsp;&nbsp;&nbsp;
      <INPUT TYPE="radio" NAME="RECUR_FREQUENCY" VALUE="DAILY" onclick="start_recur('RECUR1');"> <? echo lang("Daily"); ?> &nbsp;&nbsp;&nbsp;&nbsp;
	<INPUT TYPE="radio" NAME="RECUR_FREQUENCY" VALUE="WEEKLY" onclick="start_recur('RECUR2');"> <? echo lang("Weekly"); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<INPUT TYPE="radio" NAME="RECUR_FREQUENCY" VALUE="MONTHLY" onclick="start_recur('RECUR3');"> <? echo lang("Monthly"); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<INPUT TYPE="radio" NAME="RECUR_FREQUENCY" VALUE="YEARLY" onclick="start_recur('RECUR4');"> <? echo lang("Yearly"); ?>
	</TD>
  </TR>

  <!-- DAILY RECUR OPTIONS -->

  <TR BGCOLOR="#CCCCCC" ID="RECUR1" STYLE='DISPLAY: NONE;'>
    <TD COLSPAN="2"><B><u><? echo lang("Daily Pattern"); ?></u>:</B><BR>
      <BR>
      <? echo lang("This event should re-occur every"); ?> <INPUT NAME="DAILY_NUMDAYS" CLASS=text TYPE="text" SIZE="5"> <? echo lang("days"); ?>.<BR>
      &nbsp; &nbsp;&nbsp;</TD>
  </TR>

  <!-- WEEKLY RECUR OPTIONS -->

  <TR BGCOLOR="#CCCCCC" ID="RECUR2" STYLE='DISPLAY: NONE;'>
    <TD COLSPAN="2"><B><u><? echo lang("Weekly Pattern"); ?></u>:</B><BR>
      <BR>
      <? echo lang("This event should re-occur every"); ?>
      <INPUT NAME="WEEKLY_NUMWEEKS" CLASS=text TYPE="text" SIZE="5">
      <? echo lang("weeks on"); ?>:<BR>
      <BR>
      <INPUT TYPE="checkbox" NAME="WEEKLY1" VALUE="SUNDAY">
      <? echo lang("Sunday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY2" VALUE="MONDAY">
      <? echo lang("Monday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY3" VALUE="TUESDAY">
      <? echo lang("Tuesday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY4" VALUE="WEDNESDAY">
      <? echo lang("Wednesday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY5" VALUE="THURSDAY">
      <? echo lang("Thursday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY6" VALUE="FRIDAY">
      <? echo lang("Friday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY7" VALUE="SATURDAY">
      <? echo lang("Saturday"); ?> <BR>
      &nbsp;&nbsp;&nbsp;</TD>
  </TR>

  <!-- MONTHLY RECUR OPTIONS -->

  <TR BGCOLOR="#CCCCCC" ID="RECUR3" STYLE='DISPLAY: NONE;'>
    <TD COLSPAN="2"><B><u><? echo lang("Monthly Pattern"); ?></u>:</B><BR>
      <BR>
      <? echo lang("This event should re-occur on the"); ?>
      <SELECT NAME="MONTHLY_NUM">
        <OPTION VALUE="1" SELECTED>1st</OPTION>
        <OPTION VALUE="2">2nd</OPTION>
        <OPTION VALUE="3">3rd</OPTION>
        <OPTION VALUE="4">4th</OPTION>
      </SELECT>
      <SELECT NAME="MONTHLY_DOW">
	  	<OPTION VALUE="Sunday"><? echo lang("Sunday"); ?></OPTION>
        <OPTION VALUE="Monday" SELECTED><? echo lang("Monday"); ?></OPTION>
        <OPTION VALUE="Tuesday"><? echo lang("Tuesday"); ?></OPTION>
        <OPTION VALUE="Wednesday"><? echo lang("Wednesday"); ?></OPTION>
        <OPTION VALUE="Thursday"><? echo lang("Thursday"); ?></OPTION>
        <OPTION VALUE="Friday"><? echo lang("Friday"); ?></OPTION>
        <OPTION VALUE="Saturday"><? echo lang("Saturday"); ?></OPTION>

      </SELECT>
      <? echo lang("of each month"); ?>. <BR>
      &nbsp;&nbsp;&nbsp;&nbsp;</TD>
  </TR>

    <!-- YEARLY RECUR OPTIONS -->

  <TR BGCOLOR="#CCCCCC" ID="RECUR4" STYLE='DISPLAY: NONE;'>
    <TD COLSPAN="2"><B><u><? echo lang("Yearly Pattern"); ?></u>:</B><BR>
      <BR>
      * <? echo lang("You have selected for this event to occurr every year on"); ?> <U><? echo $string_month . " " . $ad; ?></U>.<BR>
      &nbsp;&nbsp;</TD>
  </TR>

  <TR ID="RSECONDARY" STYLE='DISPLAY: NONE;'>
    <TD BGCOLOR="#EFEFEF">
     <? echo lang("This event will start on the date of the selected 'Event Date' and continue for how long"); ?>?<BR> <INPUT TYPE="radio" NAME="RECUR_LENGTH" VALUE="UNLIMITED">
      <? echo lang("No End Date"); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <INPUT NAME="RECUR_LENGTH" TYPE="radio" VALUE="LIMIT" CHECKED>
      <? echo lang("End after"); ?>
      <INPUT NAME="RECUR_LIMIT_NUMBER" TYPE="text" class=text VALUE="4" SIZE="5">
      <? echo lang("occurrences"); ?> </TD>
    <TD WIDTH="50%" BGCOLOR="#EFEFEF">&nbsp; </TD>
  </TR>

 <?

 	##############################################################################
	### NOW SHOW SLAVE EVENTS IF APPLICABLE
	##############################################################################

  	if ($recur_exist_flag > 0) {

		$tmp = $recur_exist_flag;

		if ($type == "r") { $tmp = $tmp - 1; }

		echo "<TR><TD COLSPAN=2 BGCOLOR=oldlace CLASS=text align=CENTER valign=top><FONT FACE=VERDANA SIZE=2><B>".lang("This event is a part of")." [$tmp] ".lang("other recursive events").".</TD></TR>\n";

		$c = 1;
		while ($row = mysql_fetch_array($result)) {

			$dt = $row[EVENT_DATE];

			if ($dt != $DATA[EVENT_DATE]) {

				$tmp = split("-", $dt);
				$rec_date = date("l, F j, Y", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));

				if ($row[RECUR_MASTER] == "Y") {
					$master_flag = "<FONT COLOR=GREEN>[".lang("Master Event").")</FONT>\n";
					$this_type = "m";
				} else {
					$master_flag = "<FONT COLOR=RED>[".lang("Recursive Event").")</font>\n";
					$this_type = "r";
				}

				if ($alt == "white") { $alt = "#EFEFEF"; } else { $alt = "white"; }

				echo "<TR ID=\"SLAVEEVENT$c\">\n";
				echo "<TD BGCOLOR=$alt COLSPAN=2 align=center valign=top>\n";

					echo "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 ALIGN=CENTER BGCOLOR=$alt>\n";
					echo "<TR>\n";
					echo "<TD ALIGN=LEFT VALIGN=TOP CLASS=text STYLE='WIDTH: 250px;'>\n";
					echo "$rec_date\n";
					echo "</TD><TD ALIGN=CENTER VALIGN=TOP CLASS=text STYLE='WIDTH: 250px;'>\n";
					echo "$master_flag\n";
					echo "</TD><TD ALIGN=CENTER VALIGN=TOP CLASS=text STYLE='WIDTH: 250px;'>\n";
					echo "<INPUT TYPE=BUTTON VALUE=\" Edit This Event \" CLASS=FormLt1 onclick=\"edit_recur('$row[PRIKEY]', '$this_type');\">\n";
					echo "</TD></TR></TABLE>\n";

				echo "</TD>\n";
				echo "</TR>\n";

			} // End No Duplicate of current record

			$c++;

		} // End While Loop

	} else {	// End if Slave Events Found; else flip on option to recur this event now

		echo "\n<SCRIPT LANGUAGE=Javascript> MAINRECUR.style.display = ''; </SCRIPT>\n\n";

	}

?>


</TABLE>
</FORM>




