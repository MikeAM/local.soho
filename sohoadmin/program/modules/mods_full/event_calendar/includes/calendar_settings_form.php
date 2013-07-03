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

$filename = "shared/color_table.dat";
$fp = fopen("$filename", "r");
	$colors = fread($fp, filesize("$filename")); 
fclose($fp);

$color = split("\n", $colors);
$clr_cnt = count($color);

$COPTIONS = "";

for ($x=0;$x<=$clr_cnt;$x++) {
	$tmp = split(",", $color[$x]);
	if ($tmp[0] != "") {
		$COPTIONS .= "<OPTION VALUE=\"#".$tmp[1]."\">$tmp[0]</OPTION>\n";
	}
}

?>

<SCRIPT LANGUAGE=Javascript>

function e_text(a) {
	document.getElementById('DEMO').style.color = a;
}

function e_back(a) {
	document.getElementById('DEMO').style.backgroundColor = a;
}

</SCRIPT>

<table width="750" border="0" cellspacing="0" cellpadding="8" class="calendar_search_contain">
   <tr>
      <th colspan="2" align="left"><? echo lang("Display Settings"); ?>:</th>
   </tr>
   <tr>
      <td align="left" valign="top"><b><? echo lang("Color Scheme"); ?>:</b><br> 
	
         <table class="text" align="left" border="0" cellspacing="0" cellpadding="2">
           <tr> 
             <td align=right><? echo lang("Header Text"); ?>:</td>
             <TD align=left><SELECT STYLE="width: 150px;" CLASS="text"  NAME="TEXT_COLOR" ID="TEXT_COLOR" onchange="e_text(this.value);">
                 <OPTION SELECTED VALUE=""><? echo lang("Select Text Color"); ?>:</OPTION>
   			  <? echo $COPTIONS; ?>
               </SELECT></TD>
           </TR>
           <TR> 
             <TD align=right><? echo lang("Header Background"); ?>:</TD>
             <TD align=left><SELECT STYLE="width: 150px;" CLASS="text" NAME ="BACKGROUND_COLOR" ID="BACKGROUND_COLOR" onchange="e_back(this.value);">
                 <OPTION SELECTED VALUE=""><? echo lang("Select Background Color"); ?>:</OPTION>
   			  <? echo $COPTIONS; ?>
               </select></td>
           </tr>
           <tr> 
             <td>&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
         </table>
      
      </TD>
    <TD ALIGN="LEFT" VALIGN="TOP" style="border-right: 1px solid #A2ADBC;"><b><? echo lang("Allow authorized users to maintain personal calendars"); ?>?</b><BR> 
     <SELECT STYLE="width: 250px;" CLASS="text" NAME ="ALLOW_PERSONAL_CALENDARS" ID="ALLOW_PERSONAL_CALENDARS">
        <OPTION VALUE="Y"><? echo lang("Yes"); ?></OPTION>
        <OPTION VALUE="N" SELECTED><? echo lang("No"); ?></OPTION>
      </SELECT> <BR> <BR> <b><? echo lang("Initial Calendar Display Layout"); ?>:</b><BR> 
      <SELECT STYLE="width: 250px;" CLASS="text" NAME ="DISPLAY_STYLE" ID="select3">
        <OPTION VALUE="M" SELECTED><? echo lang("Monthly"); ?></OPTION>
        <OPTION VALUE="W"><? echo lang("Weekly"); ?></OPTION>
      </SELECT> </TD>
  </TR>
  <TR> 
    <TD ALIGN="CENTER" VALIGN="TOP">(<? echo lang("Color Preview"); ?>)<BR>
      <BR>
      <TABLE WIDTH="75%" HEIGHT="100" BORDER="0" ALIGN="CENTER" CELLPADDING="2" CELLSPACING="0" CLASS="text" STYLE="border: 1px inset black;">
        <TR> 
          <TD HEIGHT="20" ALIGN="CENTER" VALIGN="MIDDLE" ID=DEMO><? echo lang("Calendar Header"); ?></TD>
        </TR>
        <TR>
          <TD ALIGN="CENTER" VALIGN="MIDDLE" STYLE='border-top: 1px inset black; background: white;'><? echo lang("Event Dates"); ?></TD>
        </TR>
     </TABLE>
	 
	 </TD>
    <TD WIDTH="50%" ALIGN="LEFT" VALIGN="TOP" style="border-right: 1px solid #A2ADBC;">
     <b><? echo lang("Allow the public to submit events for inclusion"); ?>?</b><BR> <SELECT STYLE="width: 250px;" CLASS="text" NAME ="ALLOW_PUBLIC_SUBMISSIONS" ID="select4">
        <OPTION VALUE="Y" SELECTED><? echo lang("Yes"); ?></OPTION>
        <OPTION VALUE="N"><? echo lang("No"); ?></OPTION>
      </SELECT> <BR>
      <? echo lang("If so, where should confirmations be emailed to"); ?>?<BR> <INPUT CLASS="text" STYLE="width: 250px;" NAME="EMAIL_CONFIRMATION" TYPE="text" ID="EMAIL_CONFIRMATION"> <br/><br/>
      
      <?
      echo "<u><b>".lang("Preferences")."</b></u><br/>\n";
      echo "<b>".lang("Preserve line breaks in event details/description popup")."?</b>\n";
      ?>
      <select id="preserve_breaks" name="preserve_breaks" onchange="document.location.href='cal_display_settings.php?preserve_breaks='+this.value">
       <option value="no" selected><? echo lang("No"); ?> (default)</option>
       <option value="yes"><? echo lang("Yes"); ?></option>
      </select>
    </TD>
  </TR>
  <TR> 
    <TD ALIGN="CENTER" colspan="2" style="border-right: 1px solid #A2ADBC;">
	<button onClick="document.displayform.submit();" TYPE="button" NAME="Submit" class="greenButton"><span><span><? echo lang("Save Display Settings"); ?></span></span></button>
    </TD>
  </TR>
</TABLE>

<?

if ($DISPLAY[TEXT_COLOR] == "") { $text_color = "FFFFFF"; } else { $text_color = $DISPLAY[TEXT_COLOR]; }
if ($DISPLAY[BACKGROUND_COLOR] == "") { $back_color = "708090"; } else { $back_color = $DISPLAY[BACKGROUND_COLOR]; }

if ($DISPLAY[ALLOW_PUBLIC_SUBMISSIONS] == "") { $ps = "N"; } else { $ps = $DISPLAY[ALLOW_PUBLIC_SUBMISSIONS]; }
if ($DISPLAY[ALLOW_PERSONAL_CALENDARS] == "") { $pc = "N"; } else { $pc = $DISPLAY[ALLOW_PERSONAL_CALENDARS]; }
if ($DISPLAY[DISPLAY_STYLE] == "") { $ds = "M"; } else { $ds = $DISPLAY[DISPLAY_STYLE]; }
$email = $DISPLAY[EMAIL_CONFIRMATION]; 
?>

<SCRIPT LANGUAGE=JAVASCRIPT>

	document.getElementById('DEMO').style.color = '<? echo $text_color; ?>';
	document.getElementById('DEMO').style.backgroundColor = '<? echo $back_color; ?>';
	
	document.displayform.ALLOW_PUBLIC_SUBMISSIONS.value = '<? echo $ps; ?>';
	document.displayform.ALLOW_PERSONAL_CALENDARS.value = '<? echo $pc; ?>';
	document.displayform.DISPLAY_STYLE.value = '<? echo $ds; ?>';
	document.displayform.EMAIL_CONFIRMATION.value = '<? echo $email; ?>';
	
</SCRIPT>
