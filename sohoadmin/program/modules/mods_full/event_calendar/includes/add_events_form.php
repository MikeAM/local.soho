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

</SCRIPT>

<style>

form {
   margin:0;
}

</style>

<?php

$display_dow = date("l", mktime(0,0,0,$am,$ad,$ay));
$string_month = date("F", mktime(0,0,0,$am,$ad,$ay));
$event_date = $string_month." $ad, $ay";

// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
$editOn = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";
$saveOn = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
?>

<FORM name="addeve" METHOD=POST ACTION="add_event.php">
<INPUT TYPE=HIDDEN NAME="ACTION" VALUE="SAVE_EVENT">
<INPUT TYPE=HIDDEN NAME="EVENT_DATE" VALUE="<?php echo "$ay-$am-$ad"; ?>">

<TABLE WIDTH="700" BORDER="0" ALIGN="CENTER" CELLPADDING="5" CELLSPACING="0" class="feature_sub">
 <TR>
  <TD WIDTH="50%" ALIGN="LEFT" VALIGN="TOP" class="fsub_title">
   <?php echo lang("Apply To"); ?>:
	<SELECT NAME="APLLY_SAVE_ACTION" CLASS=text style='width: 200px; background: #EFEFEF;'>
    <OPTION VALUE="A" SELECTED><?php echo lang("THIS EVENT ONLY"); ?></OPTION>
    <!-- <OPTION VALUE="B"><?php echo lang("All occurrences of this event"); ?></OPTION> -->
   </SELECT>
  </TD>
  <TD WIDTH="50%" ALIGN=RIGHT class="fsub_title" style="padding-right: 5px;">
<button onClick="document.addeve.submit();" TYPE="button" class="greenButton"><span><span><?php echo lang("Save Event"); ?></span></span></button>
  </td>
 </tr>
 <tr align="right">
  <td width="50%" align="left"><?php echo lang("Event Date"); ?>: <B><?php echo $display_dow.", ".$event_date; ?></B>
   &nbsp;&nbsp;</TD>
  <td width="50%"> <?php echo lang("Start Time"); ?>:
   <select name="START_TIME" class="text" style='width: 75px;'>
	 <option value="" selected>n/a</option>

		<?php

		$clock_flag = "am";
		for ($z=1;$z<=24;$z++) {

			$v = $z;
			$v2 = $z;

			if ($z > 12) { $v = $z-12; }
			if ($v2 < 10) { $v2 = "0".$v2; }

			if ($v == 12) { $clock_flag = "pm"; }
			if ($z == 24) { $clock_flag = "am"; }

			$d = "$v:00 $clock_flag";
			// if ($d == "9:00 am") { $SEL = "SELECTED"; } else { $SEL = ""; }
			echo "<OPTION VALUE=\"$v2:00\" STYLE='background: #EFEFEF; color: black;' $SEL>$d</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:15\" STYLE='color: #999999;'>$v:15 $clock_flag</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:30\" STYLE='color: #999999;'>$v:30 $clock_flag</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:45\" STYLE='color: #999999;'>$v:45 $clock_flag</OPTION>\n";

		}

		?>
      </SELECT> <?php echo lang("End Time"); ?>:
      <SELECT NAME="END_TIME" CLASS="text" STYLE='width: 75px;'>
      <OPTION VALUE="[none]">[none]</OPTION>
      <OPTION VALUE="" SELECTED>N/A</OPTION>

		<?php

		$clock_flag = "am";
		for ($z=1;$z<=24;$z++) {

			$v = $z;
			$v2 = $z;

			if ($z > 12) { $v = $z-12; }
			if ($v2 < 10) { $v2 = "0".$v2; }

			if ($v == 12) { $clock_flag = "pm"; }
			if ($z == 24) { $clock_flag = "am"; }

			$d = "$v:00 $clock_flag";
			echo "<OPTION VALUE=\"$v2:00\" STYLE='background: #EFEFEF; color: black;' $SEL>$d</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:15\" STYLE='color: #999999;'>$v:15 $clock_flag</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:30\" STYLE='color: #999999;'>$v:30 $clock_flag</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:45\" STYLE='color: #999999;'>$v:45 $clock_flag</OPTION>\n";

		}

		?>

      </SELECT> </TD>
  </TR>
  <TR>
    <TD COLSPAN="2"><?php echo lang("Event Title"); ?>:<BR> <INPUT TYPE="text" NAME="EVENT_TITLE" CLASS="text" STYLE='width: 100%;'>
    </TD>
  </TR>
  <TR>
    <TD><?php echo lang("Event Details (Description)"); ?>:<BR> <TEXTAREA NAME="EVENT_DETAILS" CLASS="text" STYLE="width: 100%; HEIGHT: 115px;" WRAP=VIRTUAL></TEXTAREA>
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP"><?php echo lang("Event Category"); ?>:<BR> <SELECT NAME="EVENT_CATEGORY" CLASS="text" STYLE='width: 200px;'>
        <OPTION VALUE="ALL" SELECTED><?php echo lang("All"); ?></OPTION>
        <?php

		$result = mysql_query("SELECT * FROM calendar_category ORDER BY Category_Name");
		while ($row = mysql_fetch_array($result)) {
			echo "<OPTION VALUE=\"$row[PRIKEY]\">$row[Category_Name]</OPTION>\n";
		}

		?>
      </SELECT>
      <BR>
      <BR>
      <?php echo lang("Security Code (Group)"); ?>:<BR> <SELECT NAME="EVENT_SECURITYCODE" CLASS="text" ID="EVENT_SECURITYCODE" STYLE='width: 200px;'>
        <OPTION VALUE="Public" SELECTED><?php echo lang("Public"); ?></OPTION>
		<?php

		$result = mysql_query("SELECT * FROM sec_codes ORDER BY security_code");
		while ($row = mysql_fetch_array($result)) {
			echo "<OPTION VALUE=\"$row[security_code]\">$row[security_code]</OPTION>\n";
		}

		?>
      </SELECT>
      <BR>
      <BR>
      Detail Page:<BR> <SELECT NAME="EVENT_DETAILPAGE" CLASS="text" ID="EVENT_DETAILPAGE" STYLE='width: 200px;'>
        <OPTION VALUE="" SELECTED>N/A</OPTION>
		<?php

		// Removed reliance upon "type" pages in V4.6 (Still works for upgrades)
		$result = mysql_query("SELECT page_name, url_name, link FROM site_pages ORDER BY page_name");
		while ($row = mysql_fetch_array($result)) {
			if(!preg_match('/^http:/i', $row['link'])){
				echo "<OPTION VALUE=\"$row[page_name]\">$row[page_name]</OPTION>\n";
			}
		}

		?>
      </SELECT> </TD>
  </TR>
  <TR>
    <TD><?php echo lang("When saving or changing this event, email a notice to the following email addresses"); ?>:<BR>
      <INPUT TYPE="text" NAME="EVENT_EMAIL_CC" CLASS="text" STYLE='width: 100%;'>
    </TD>
    <TD ALIGN="LEFT" VALIGN="TOP">&nbsp; </TD>
  </TR>

<!-- RECURRENCE SECTION START --------------------------------- -->

 <TR>
  <TD WIDTH="50%" COLSPAN="2" ALIGN="LEFT" VALIGN="TOP" class="fsub_title">
   <?php echo lang("Event Recurrence"); ?>
  </TD>
 </TR>
 <TR ALIGN="RIGHT">
  <TD WIDTH="50%" COLSPAN="2" ALIGN="LEFT">
	<INPUT TYPE="radio" NAME="RECUR_FREQUENCY"VALUE="NONE" CHECKED onclick="no_recur();">
      <?php echo lang("No Recurrence"); ?> &nbsp;&nbsp;&nbsp;
      <INPUT TYPE="radio" NAME="RECUR_FREQUENCY" VALUE="DAILY" onclick="start_recur('RECUR1');"> <?php echo lang("Daily"); ?> &nbsp;&nbsp;&nbsp;&nbsp;
	<INPUT TYPE="radio" NAME="RECUR_FREQUENCY" VALUE="WEEKLY" onclick="start_recur('RECUR2');"> <?php echo lang("Weekly"); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<INPUT TYPE="radio" NAME="RECUR_FREQUENCY" VALUE="MONTHLY" onclick="start_recur('RECUR3');"> <?php echo lang("Monthly"); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<INPUT TYPE="radio" NAME="RECUR_FREQUENCY" VALUE="YEARLY" onclick="start_recur('RECUR4');"> <?php echo lang("Yearly"); ?>
	</TD>
  </TR>

  <!-- DAILY RECUR OPTIONS -->

  <TR ID="RECUR1" STYLE='DISPLAY: NONE';>
    <TD COLSPAN="2"><B><u><?php echo lang("Daily Pattern"); ?></u>:</B><BR>
      <BR>
      <?php echo lang("This event should re-occur every"); ?>
      <INPUT NAME="DAILY_NUMDAYS" CLASS=text TYPE="text" SIZE="5">
      <?php echo lang("days"); ?>.<BR>
      &nbsp; &nbsp;&nbsp;</TD>
  </TR>

  <!-- WEEKLY RECUR OPTIONS -->

  <TR ID="RECUR2" STYLE='DISPLAY: NONE';>
    <TD COLSPAN="2"><B><u><?php echo lang("Weekly Pattern"); ?></u>:</B><BR>
      <BR>
      <?php echo lang("This event should re-occur every"); ?>
      <INPUT NAME="WEEKLY_NUMWEEKS" value="1" CLASS=text TYPE="text" SIZE="5">
      <?php echo lang("weeks on"); ?>:<BR>
      <BR> 
      
      <INPUT TYPE="checkbox" NAME="WEEKLY1" VALUE="SUNDAY">
      <?php echo lang("Sunday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY2" VALUE="MONDAY">
      <?php echo lang("Monday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY3" VALUE="TUESDAY">
      <?php echo lang("Tuesday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY4" VALUE="WEDNESDAY">
      <?php echo lang("Wednesday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY5" VALUE="THURSDAY">
      <?php echo lang("Thursday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY6" VALUE="FRIDAY">
      <?php echo lang("Friday"); ?>&nbsp;&nbsp;&nbsp;&nbsp;
      <INPUT TYPE="checkbox" NAME="WEEKLY7" VALUE="SATURDAY">
      <?php echo lang("Saturday"); ?> <BR>
      &nbsp;&nbsp;&nbsp;</TD>
  </TR>

  <!-- MONTHLY RECUR OPTIONS -->

  <TR ID="RECUR3" STYLE='DISPLAY: NONE';>
    <TD COLSPAN="2"><B><u><?php echo lang("Monthly Pattern"); ?></u>:</B><BR>
      <BR>
      <?php echo lang("This event should re-occur on the"); ?>
      <SELECT NAME="MONTHLY_NUM">
        <OPTION VALUE="1" SELECTED>1st</OPTION>
        <OPTION VALUE="2">2nd</OPTION>
        <OPTION VALUE="3">3rd</OPTION>
        <OPTION VALUE="4">4th</OPTION>
      </SELECT>
      <SELECT NAME="MONTHLY_DOW">
	  	<OPTION VALUE="Sunday"><?php echo lang("Sunday"); ?></OPTION>
        <OPTION VALUE="Monday" SELECTED><?php echo lang("Monday"); ?></OPTION>
        <OPTION VALUE="Tuesday"><?php echo lang("Tuesday"); ?></OPTION>
        <OPTION VALUE="Wednesday"><?php echo lang("Wednesday"); ?></OPTION>
        <OPTION VALUE="Thursday"><?php echo lang("Thursday"); ?></OPTION>
        <OPTION VALUE="Friday"><?php echo lang("Friday"); ?></OPTION>
        <OPTION VALUE="Saturday"><?php echo lang("Saturday"); ?></OPTION>

      </SELECT>
      <?php echo lang("of each month"); ?>. <BR>
      &nbsp;&nbsp;&nbsp;&nbsp;</TD>
  </TR>

    <!-- YEARLY RECUR OPTIONS -->

  <TR ID="RECUR4" STYLE='DISPLAY: NONE';>
    <TD COLSPAN="2"><B><u><?php echo lang("Yearly Pattern"); ?></u>:</B><BR>
      <BR>
      * <?php echo lang("You have selected for this event to occurr every year on"); ?> <U><?php echo $string_month . " " . $ad; ?></U>.<BR>
      &nbsp;&nbsp;</TD>
  </TR>

  <TR ID="RSECONDARY" STYLE='DISPLAY: NONE;'>
    <TD>
     <?php echo lang("This event will start on the date of the selected 'Event Date' and continue for how long"); ?>?<BR> <INPUT TYPE="radio" NAME="RECUR_LENGTH" VALUE="UNLIMITED">
     <?php echo lang("No End Date"); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <INPUT NAME="RECUR_LENGTH" TYPE="radio" VALUE="LIMIT" CHECKED>
     <?php echo lang("End after"); ?>
      <INPUT NAME="RECUR_LIMIT_NUMBER" TYPE="text" class=text VALUE="4" SIZE="5">
      <?php echo lang("occurrences"); ?> </TD>
    <TD WIDTH="50%">&nbsp; </TD>
  </TR>
</TABLE>
</FORM>
