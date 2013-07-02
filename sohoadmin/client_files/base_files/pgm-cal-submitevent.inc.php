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


reset($HTTP_POST_VARS);
while (list($name, $value) = each($HTTP_POST_VARS)) {
		$value = htmlspecialchars($value);	// Bugzilla #13
		${$name} = $value;
}

?>
<FORM NAME="calformedit" METHOD=POST ACTION="index.php">
<INPUT TYPE=HIDDEN NAME="pr" VALUE="<? echo str_replace('"', '', str_replace("'", '', $CAL_MOD_PAGE)); ?>">
<INPUT TYPE=HIDDEN NAME="FORM_SUBMIT_STEP" VALUE="2">
<INPUT TYPE=HIDDEN NAME="PUBLIC_SUBMIT_EVENT" VALUE="-">

<?php

if ($EDIT_DATA[PRIKEY] != "") {

	if(!preg_match('/^(\d)+$/', $EDIT_DATA[PRIKEY])){
		exit;	
	}

	echo "<INPUT TYPE=HIDDEN NAME=EDIT_CAL_RECORD VALUE=\"$EDIT_DATA[PRIKEY]\">\n";

	$tmp = split("-", $EDIT_DATA[EVENT_DATE]);
	$set_m = $tmp[1];
	$set_d = $tmp[2];
	$set_y = $tmp[0];

	if ($EDIT_DATA[EVENT_CATEGORY] == $MD5CODE) {
		$set_cat = "AUTH:$MD5CODE";
	}
	// Reset drop down boxes for edit; this sux

	// ----------------------------------------------------------------------
	
	$copts = "<OPTION VALUE=\"ALL\" $osel>All</OPTION>\n";
	$c = mysql_query("SELECT * FROM calendar_category ORDER BY Category_Name");
	while ($r = mysql_fetch_array($c)) {
		$copts .= "<OPTION VALUE=\"$r[Category_Name]\" $osel>$r[Category_Name]</OPTION>\n";
	}
	if (isset($OWNER_NAME) && $DISPLAY[ALLOW_PERSONAL_CALENDARS] == "Y") {
		$copts .= "<OPTION VALUE=\"AUTH:$MD5CODE\" SELECTED>".$lang["Private"].": $OWNER_NAME</OPTION>\n";
	}

	$MONTH_OPTIONS = "";

	for ($x=1;$x<=12;$x++) {
		$val = date("m", mktime(0,0,0,$x,1,2002));
		$display = date("M", mktime(0,0,0,$x,1,2002));
		if ($val == $set_m) { $SEL = "SELECTED"; } else { $SEL = ""; }
		$MONTH_OPTIONS .= "<OPTION VALUE=\"$val\" $SEL>$display</OPTION>\n";
	}
	
	$YEAR_OPTIONS = "";
	for ($x=2002;$x<=2015;$x++) {
		if ($x == $set_y) { $SEL = "SELECTED"; } else { $SEL = ""; }
		$YEAR_OPTIONS .= "<OPTION VALUE=\"$x\" $SEL>$x</OPTION>\n";
	}
} // End if Edit Key is a go!

?>

<TABLE WIDTH="90%" BORDER="0" CELLSPACING="0" CELLPADDING="5" CLASS="text" BGCOLOR="#EFEFEF" STYLE="border: 1px inset black;">
  <TR> 
    <TD COLSPAN="2" BGCOLOR="<? echo $DISPLAY[BACKGROUND_COLOR]; ?>">
     <FONT COLOR="<? echo $DISPLAY[TEXT_COLOR]; ?>" FACE=VERDANA SIZE=2><B><? echo lang("Submit an Event"); ?>:</B></FONT></TD>
  </TR>
  <TR> 
    <TD><? echo lang("Your Name"); ?>:<BR> <INPUT TYPE="text" CLASS="text" NAME="CALFORM_NAME" STYLE="width: 200px;" VALUE="<? echo $OWNER_NAME; ?>"> 
    </TD>
    <TD><? echo lang("Your Email Address"); ?>:<BR> <INPUT TYPE="text" CLASS="text" NAME="CALFORM_EMAILADDRESS" STYLE="width: 200px;" VALUE="<? echo $OWNER_EMAIL; ?>"></TD>
  </TR>
  <TR> 
    <TD><? echo lang("Event Date"); ?>:<BR> 
		<SELECT NAME="CALFORM_MONTH" CLASS="text">
        <? echo $MONTH_OPTIONS; ?>
		</SELECT> 
		<SELECT NAME="CALFORM_DAY" CLASS="text">
        <?
		for ($ui=1;$ui<=31;$ui++) {
			if ($ui == $set_d) { $osel = "SELECTED"; } else { $osel = ""; }
			echo "<OPTION VALUE=\"$ui\" $osel>$ui</OPTION>\n";
		}
		?>
      </SELECT> 
	  <SELECT NAME="CALFORM_YEAR" CLASS="text">
	  <? echo $YEAR_OPTIONS; ?>
	  </SELECT> 
	  </TD>
    <TD><? echo lang("Event Category"); ?>:<BR>
	  <SELECT NAME="CALFORM_CATEGORY" CLASS="text" STYLE="WIDTH: 200px;">
      <? echo $copts; ?>
	  </SELECT>
	  </TD>
  </TR>
  <TR>
    <TD CLASS="smtext"><? echo lang("Start Time"); ?>: 
      <SELECT NAME="CALFORM_START_TIME" CLASS="text" STYLE='width: 80px;'>

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
			
			if ($EDIT_DATA[PRIKEY] == "") {
				if ($d == "9:00 am") { $SEL = "SELECTED"; } else { $SEL = ""; }
			} 
			echo "<OPTION VALUE=\"$v2:00\" STYLE='background: #EFEFEF; color: black;' $SEL>$d</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:15\" STYLE='color: #999999;'>$v:15 $clock_flag</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:30\" STYLE='color: #999999;'>$v:30 $clock_flag</OPTION>\n";
			echo "<OPTION VALUE=\"$v2:45\" STYLE='color: #999999;'>$v:45 $clock_flag</OPTION>\n";			
		}			
		?>
      </SELECT>
      End Time: 
      <SELECT NAME="CALFORM_END_TIME" CLASS="text" STYLE='width: 80px;'>
        <OPTION VALUE="" >N/A</OPTION>
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
    <TD VALIGN=TOP ALIGN=LEFT><? echo lang("Event Title"); ?>:<BR> <INPUT TYPE="text" CLASS="text" NAME="CALFORM_TITLE" STYLE="width: 200px;" VALUE="<? echo $EDIT_DATA[EVENT_TITLE]; ?>"></TD>
  </TR>
  <TR> 
    <TD COLSPAN="2"><? echo lang("Event Details"); ?>:<BR> <TEXTAREA NAME="CALFORM_DETAILS" COLS="50" ROWS="7" WRAP="VIRTUAL" CLASS="text" STYLE="WIDTH: 95%;"><? echo $EDIT_DATA[EVENT_DETAILS]; ?></TEXTAREA> 
    </TD>
  </TR>
  <TR> 
    <TD>&nbsp;</TD>
    <TD ALIGN="CENTER" VALIGN="MIDDLE"><INPUT TYPE="submit" NAME="Submit" VALUE="<? echo $lang["Submit Event"]; ?>" CLASS="FormLt1"></TD>
  </TR>
  <TR ALIGN="CENTER"> 
    <TD COLSPAN="2" class=smtext><font color=#999999><? echo lang("All fields are required to submit an event except Event End Time and Event Details."); ?></font></TD>
  </TR>
</TABLE>
</FORM>
<?php

$set_st = $EDIT_DATA[EVENT_START];
$set_st = substr($set_st, 0, 5);

$set_en = $EDIT_DATA[EVENT_END];
$set_en = substr($set_en, 0, 5);

?>

<SCRIPT LANGUAGE=JAVASCRIPT>
	document.calformedit.CALFORM_END_TIME.value = '<? echo $set_en; ?>';
	document.calformedit.CALFORM_START_TIME.value = '<? echo $set_st; ?>';
</SCRIPT>