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
	### DETERMINE WHAT WEEK WE ARE IN RIGHT NOW AND BUILD "WHERE" STRING
	### FOR MYSQL
	########################################################################

	$this_month = date("m");
	$this_day = date("d");
	$this_year = date("Y");
	$current_dow = date("w", mktime(0,0,0,$this_month,$this_day,$this_year));
	
	$myresult = mysql_query("SELECT * FROM calendar_display");
	$DISPLAY = mysql_fetch_array($myresult);
	
	$DISP_CAT_NAME = "<font size=1 face=Arial><B><I>Category: $CHANGE_CAT</I></B></font>";
	
	$CHANGE_CAT = ltrim($CHANGE_CAT);
	$CHANGE_CAT = rtrim($CHANGE_CAT);

	$vres = mysql_query("SELECT * FROM calendar_category");
	if (!eregi("ALL", $CHANGE_CAT)) {
		while ($t = mysql_fetch_array($vres)) {
			if ($t[Category_Name] == $CHANGE_CAT) { $CHANGE_CAT = $t[PRIKEY]; }
		}
	} else {
		$CHANGE_CAT = "ALL";
	}
	
	?>

<TABLE BORDER=0 CELLPADDING=3 CELLSPACING=0 WIDTH=90% ALIGN=CENTER>
<tr><td align=right><? echo $DISP_CAT_NAME; ?></td></tr>
</TABLE>
<TABLE BORDER=1 CELLPADDING=3 CELLSPACING=0 WIDTH=90% ALIGN=CENTER STYLE='border-color: black;'>
<TR> 

    <?
		
	$zz = $this_day - $current_dow;
		
	for ($x=1;$x<=7;$x++) {
		$day = date("M d", mktime(0,0,0,$this_month,$zz,$this_year));
		$day_of_week = date("l", mktime(0,0,0,$this_month,$zz,$this_year));
		
		echo "<TD class=\"day_square\" STYLE='border-color: black;' ALIGN=CENTER VALIGN=MIDDLE CLASS=text BGCOLOR=$DISPLAY[BACKGROUND_COLOR]><FONT COLOR=$DISPLAY[TEXT_COLOR]><B>$day_of_week</B><BR><DIV CLASS=smtext>$day</DIV></FONT></TD>\n";
		$zz++;
	}
		
	?>

</TR><TR> 
    
	<?
			
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// If this is an Authorized User Loged In, Let's search for any events
	// they may be able to view beyond "public" events
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		
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
		if ($flag != 0) { $SEC_SEARCH = "($SEC_SEARCH)"; }
	}
		
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
				
	$zz = $this_day - $current_dow;
		
	for ($x=1;$x<=7;$x++) {
		$tDate = date("Y-m-d", mktime(0,0,0,$this_month,$zz,$this_year));
		$today_chk = "$this_year-$this_month-$this_day";
	
		if ($tDate == $today_chk) { $bg = "oldlace"; } else { $bg = "WHITE"; }
	
		echo "<TD ALIGN=LEFT VALIGN=TOP CLASS=\"smtext day_square\" BGCOLOR=$bg STYLE='width: 65px; border-color: black;'>";
			
		if ($CHANGE_CAT != "ALL") {
			$twkresult = mysql_query("SELECT PRIKEY, EVENT_TITLE, EVENT_START, EVENT_END, EVENT_DETAILS, EVENT_DETAILPAGE FROM calendar_events WHERE EVENT_DATE = '$tDate' AND $SEC_SEARCH AND EVENT_CATEGORY = '$CHANGE_CAT' ORDER BY EVENT_START");
		} else {
			$twkresult = mysql_query("SELECT PRIKEY, EVENT_TITLE, EVENT_START, EVENT_END, EVENT_DETAILS, EVENT_DETAILPAGE FROM calendar_events WHERE EVENT_DATE = '$tDate' AND $SEC_SEARCH ORDER BY EVENT_START");
		}
			
		$tmp = mysql_num_rows($twkresult);
		
		if ($tmp > 0) {
		
			while ($row = mysql_fetch_array($twkresult)) {
			
            if ($row['EVENT_START'] == "00:00:00") {
               $mm = "n/a"; // v4.9.2 r15 - fixes bug where "12:00am" would show for events with no end time asigned"
            } else {
               $tmp = split(":", $row['EVENT_START']);
            	$mm = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$this_month,1,$this_year));
				}
			
				if (strlen($row[EVENT_DETAILS]) > 3 || $row[EVENT_DETAILPAGE] != "") {
					echo "<span class=\"event-container\"><span class=\"event-title\"><a href=\"#\" onclick=\"javscript: window.open('pgm-cal-details.inc.php?id=$row[PRIKEY]','EVENTDETAILS', 'scrollbars=yes,location=no,resizable=yes,width=470,height=400');\">";
					echo "$row[EVENT_TITLE]</a></span><BR><span class=\"event-time\">$mm";
				} else {
					echo "<span class=\"event-container\"><span class=\"event-title\">$row[EVENT_TITLE]</span><BR><span class=\"event-time\">$mm";
				}
				
            if ($row['EVENT_END'] == "00:00:00") {
               $mm = "n/a"; // v4.9.2 r15 - fixes bug where "12:00am" would show for events with no end time asigned"
            } else {
               $tmp = split(":", $row['EVENT_END']);
            	$mm = date("g:ia", mktime($tmp[0],$tmp[1],$tmp[2],$this_month,1,$this_year));
				}
				echo "-$mm";
				echo "</span></span>"; // Closes event-time and event-container spans
		
				echo "<BR><BR>";
				
			} // End While Loop
			
		} else {
			echo "&nbsp;";
		}

		echo "</TD>\n";
		$zz++;
	}
		
	?>
  
</TR>

</TABLE>
