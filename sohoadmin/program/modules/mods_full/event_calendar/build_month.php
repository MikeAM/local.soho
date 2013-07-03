<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../includes/product_gui.php');
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

########################################################################
### BUILD JAVASCRIPT ELEMENTS
########################################################################

?>

<SCRIPT LANGUAGE=Javascript>

function add_new(m,d,y) {
	window.location = 'event_calendar/add_event.php?am='+m+'&ad='+d+'&ay='+y+'&mode=add&<?=SID?>';
}

</SCRIPT>

<style>

.calendar {
	/*width: 141px;*/
	padding: 0;
	margin: 0;
	border-left: 1px solid #A2ADBC;
	font: normal 11px/12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #4D565F;
	text-align: center;
	background-color: #fff;
}

.calendar th {
	font: bold 11px/20px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #4D565F;
	background: #D9E2E1;
	border-right: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	border-top: 1px solid #A2ADBC;
}

.calendar td {
	border-right: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;

	/*text-align: center;*/
}

.day_num {
   width: 15px;
   _width: 20px;
   border-right: 1px solid #A2ADBC;
   border-bottom: 1px solid #A2ADBC;
   background: #EFEFEF;
   float: left;
   padding:2;
   text-align: center;
   /*display: none;*/
   /*margin:0;*/
}

.add_event_btn {
   margin:0;
   padding-top:2px;
   padding-bottom:2px;
   text-align: center;
   border-bottom: 1px solid #E0EFE2;
   /*border: 1px dashed red;*/
   cursor: pointer;
   background: #E0EFE2;
   /*width: 100%;*/
}

.add_event_btn_over {
   padding-top:2px;
   padding-bottom:2px;
   text-align: center;
   border-bottom: 1px solid #A2ADBC;
   cursor: pointer;
   background: #FFFFFF;
   /*width: 100%;*/
}

.event_item {
   list-style-type: none;
   clear: both;
   padding: 0;
   margin: 0 0 2px 0;
   font-size:10px;
   letter-spacing:-.5px;
   min-width: 120px;
   max-width: 200px;
   /*border: 1px dotted red;*/
}

.event_item a {
   color: #4D565F;
   text-decoration: none;
}
.event_item a:hover {
   color: #2C344F;
   text-decoration: underline;
}
ul{
   padding:0;
   margin:0;
   font-size:10px;
}

.caltd {  
	font-size:10px;
   min-width: 120px;
   max-width: 200px;
   height: 100px; 
}

</style>

<?php

########################################################################
### HOW MANY DAYS ARE IN THE "SELECTED MONTH"
########################################################################

$NUM_DAYS_IN_MONTH = date("t", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));
$START_DOW = date("l", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));			// What day of week does month start on?
$top_month = date("F", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));
$top_year = date("Y", mktime(0,0,0,$SEL_MONTH,1,$SEL_YEAR));
if($SEL_MONTH == 12 || $SEL_MONTH == 01){
	if($SEL_MONTH == 12){
		$last_month = date("F", mktime(0,0,0,($SEL_MONTH-1),1,$SEL_YEAR));
		$last_year = date("Y", mktime(0,0,0,($SEL_MONTH-1),1,$SEL_YEAR));	
		$next_month = date("F", mktime(0,0,0,01,1,($SEL_YEAR+1)));
		$next_year = date("Y", mktime(0,0,0,01,1,($SEL_YEAR+1)));
	} else {
		$last_month = date("F", mktime(0,0,0,12,1,($SEL_YEAR-1)));
		$last_year = date("Y", mktime(0,0,0,12,1,($SEL_YEAR-1)));
		$next_month = date("F", mktime(0,0,0,($SEL_MONTH+1),1,$SEL_YEAR));
		$next_year = date("Y", mktime(0,0,0,($SEL_MONTH+1),1,$SEL_YEAR));
	}
} else {
	$last_month = date("F", mktime(0,0,0,($SEL_MONTH-1),1,$SEL_YEAR));
	$last_year = date("Y", mktime(0,0,0,($SEL_MONTH-1),1,$SEL_YEAR));
	$next_month = date("F", mktime(0,0,0,($SEL_MONTH+1),1,$SEL_YEAR));
	$next_year = date("Y", mktime(0,0,0,($SEL_MONTH+1),1,$SEL_YEAR));
}

$last_month_val = date('m',strtotime($last_month." ".$last_year));
$next_month_val = date('m',strtotime($next_month." ".$next_year));





if ($SEL_MONTH == date("m")) { $HIGHLIGHT = "on"; $HIGHLIGHT_DAY = date("j"); }

########################################################################
### BUILD CALENDAR DISPLAY
########################################################################
	echo " <table width=\"780px\"<tr>\n";
	echo "  <td colspan=3 style='border-bottom: 0px inset black;text-align:left;'><a style=\"font-size:15px;text-decoration:none;bottom-border:0px;\" href=\"javascript:void(0);\" onClick=\"changedate('".$last_month_val."','".$last_year."');\">&lt;&lt;&nbsp;".$last_month."&nbsp;".$last_year."</a></td>\n";
	echo "  <td colspan=\"2\" align=\"center\" valign=\"top\" style=\"font-weight:bold;font-size:18px;text-align:center;padding:0px;\">\n";
	echo "   ".lang($top_month)." ".$top_year."\n";
	echo "  </td>\n";
	echo "  <td colspan=2 style='border-bottom: 0px inset black;text-align:right;'><a style=\"font-size:15px;text-decoration:none;bottom-border:0px;\" href=\"javascript:void(0);\" onClick=\"changedate('".$next_month_val."','".$next_year."');\">".$next_month."&nbsp;".$next_year."&nbsp;&gt;&gt;</a></td>\n";
	echo " </tr></table>\n";


echo "<table class=\"calendar\" cellpadding=\"0\" cellspacing=\"0\" >\n";

	// -----------------------------------------------------------------
	// Row for display of days of the week
	// -----------------------------------------------------------------
	
	echo "\n<tr>\n";	
	for ($x=0;$x<=6;$x++) {
	
		echo "\n<th align=\"center\" valign=\"middle\" bgcolor=\"#708090\" class=\"text\">";
		echo $day_of_week[$x];
		echo "</th>\n";
		
	}	
	echo "</tr>\n";

	// -----------------------------------------------------------------
	// Display first week based on when first day of month starts
	// -----------------------------------------------------------------
	
	echo "\n<TR>\n";
	
	$FLAG = 0;
	$display_day = 1;
		
	for ($x=0;$x<=6;$x++) {

		if (eregi("$START_DOW", $day_of_week[$x]) || $FLAG == 1) {
		
			if ($HIGHLIGHT == "on" && $display_day == $HIGHLIGHT_DAY) { $BGCOLOR = "OLDLACE"; } else { $BGCOLOR = "WHITE"; } 
		
			echo "\n<td align=left valign=top bgcolor=\"".$BGCOLOR."\" class=\"text caltd\">\n";
			echo "<div class=\"day_num\">".$display_day."</div>\n";
			echo "<div class=\"add_event_btn\" onclick=\"add_new('".$SEL_MONTH."','".$display_day."','".$SEL_YEAR."');\" onmouseover=\"this.className='add_event_btn_over'\" onmouseout=\"this.className='add_event_btn'\" >".$lang["Add Event"]."</div>";

			// ========================================================================
			// Display Events for this date
			// ========================================================================
         
         		$foundit = 0;
			for ($z=0;$z<=$NUM_EVENTS;$z++) {
			
				$tmp = split("-", $DB_EVENT_DATE[$z]);
				$look_for = $tmp[2];
			
				if ($look_for == $display_day) {	// Event found for this day of month
				   
					if($foundit == 0){
	   			      	$foundit = 1;
	   			      	echo "<ul>\n";
					} else {
	   			   		     echo "<hr noshade size=1 width=\"50%\" align=center> \n";
	   				}
				
					if ($DB_RECUR_MASTER[$z] == "Y") {
						echo "\n<LI class=\"event_item\" ><a href=\"event_calendar/edit_event.php?id=$DB_EVENT_PRIKEY[$z]&type=m&=SID\">$DB_EVENT_TITLE[$z]</a>&nbsp;<font color=green>[M]</FONT>\n";
					} else {
						// Link to # found in recur_master field
						echo "\n<LI class=\"event_item\" ><a href=\"event_calendar/edit_event.php?id=$DB_EVENT_PRIKEY[$z]&type=r&=SID\">$DB_EVENT_TITLE[$z]</a>&nbsp;<font color=red>[R]</FONT>\n";
					}
					
					 
						if(preg_match('/^[0-9]+/',$DB_EVENT_PAGE[$z])){
							$pp=mysql_query("select PROD_NAME,OPTION_INVENTORY_NUM from cart_products where PRIKEY='".$DB_EVENT_PAGE[$z]."'");
							$PROD=mysql_fetch_assoc($pp);
							$dbtablename=strtoupper(str_replace(' ','_','UDT_CART_DATA_'.sterilize_char($PROD['PROD_NAME'])));
							if(strlen($dbtablename) > 64){
								$dbtablename=substr($dbtablename,0,64);
							}
							if(table_exists($dbtablename)){
								$find_regss=mysql_query("select PRIKEY,EVENT_DATE from ".$dbtablename." where EVENT_DATE='".$DB_EVENT_DATE[$z]." ".$DB_EVENT_START[$z]."-".$DB_EVENT_END[$z]."'");
								$signupmax="/".$PROD['OPTION_INVENTORY_NUM'];
								//if($PROD['OPTION_INVENTORY_NUM']==50000){
									$signupmax=" signup";
								//}
								if(mysql_num_rows($find_regss) > 0){
									$dblink='database_manager/enter_edit_data.php?mt='.$dbtablename.'&TBL_SEARCH_FOR='.$DB_EVENT_DATE[$z]."+".$DB_EVENT_START[$z]."-".$DB_EVENT_END[$z].'&searchin=EVENT_DATE';
									echo "<span style=\"font-size: 0.8em;\">(<a  style=\"font-size: 0.8em;\" href=\"".$dblink."\">".mysql_num_rows($find_regss).$signupmax."</a>)</span>";
								} else {
									echo "<span style=\"font-size: 0.8em;\">(".(mysql_num_rows($find_regss)+0).$signupmax.")</span>";	
								}
								
							}
						}
						
						echo "</LI>\n";
				
				} // End Found Event
				
			} // End Event Loop
		   if($foundit == 1){
		      echo "</ul>\n";
		   }
			
			// ========================================================================

			echo "\n</TD>\n";
			
			$display_day++;
			$FLAG = 1;
			
		} else {
		
			echo "\n<td align=\"left\" valign=\"top\" bgcolor=\"#efefef\" style='height: 75px;'>";
			echo "&nbsp;";	
			echo "</td>\n";
			
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

		for ($y=1;$y<=7;$y++) {

			if ($FLAG != 1) {
			
				if ($HIGHLIGHT == "on" && $display_day == $HIGHLIGHT_DAY) { $BGCOLOR = "OLDLACE"; } else { $BGCOLOR = "WHITE"; } 
		
				echo "\n<td align=left valign=top bgcolor=".$BGCOLOR." class=\"text caltd\">\n";
				echo "<div class=\"day_num\">".$display_day."</div>\n";
				echo "<div class=\"add_event_btn\" onclick=\"add_new('$SEL_MONTH','$display_day','$SEL_YEAR');\" onmouseover=\"this.className='add_event_btn_over'\" onmouseout=\"this.className='add_event_btn'\" >".$lang["Add Event"]."</div>";
				
				// ========================================================================
				// Display Events for this date
				// ========================================================================
            
           			 $foundit = 0;
				for ($z=0;$z<=$NUM_EVENTS;$z++) {

				
					$tmp = split("-", $DB_EVENT_DATE[$z]);
					$look_for = $tmp[2];
				
					if ($look_for == $display_day) {	// Event found for this day of month
						if($foundit == 0){
		   			      	$foundit = 1;
		   			      	echo "<ul>\n";
						} else {
		   			   		     echo "<hr noshade size=1 width=\"50%\" align=center> \n";
		   				}
					
	   					if ($DB_RECUR_MASTER[$z] == "Y") {
	   						echo "\n<LI class=\"event_item\" ><a href=\"event_calendar/edit_event.php?id=$DB_EVENT_PRIKEY[$z]&type=m&=SID\">$DB_EVENT_TITLE[$z]</a>&nbsp;<font color=green>[M]</FONT>\n";
	   					} else {
	   						// Link to # found in recur_master field
	   						echo "\n<LI class=\"event_item\" ><a href=\"event_calendar/edit_event.php?id=$DB_EVENT_PRIKEY[$z]&type=r&=SID\">$DB_EVENT_TITLE[$z]</a>&nbsp;<font color=red>[R]</FONT>\n";
	   					}
						
						if(preg_match('/^[0-9]+/',$DB_EVENT_PAGE[$z])){
							$pp=mysql_query("select PROD_NAME,OPTION_INVENTORY_NUM from cart_products where PRIKEY='".$DB_EVENT_PAGE[$z]."'");
							$PROD=mysql_fetch_assoc($pp);
							$dbtablename=strtoupper(str_replace(' ','_','UDT_CART_DATA_'.sterilize_char($PROD['PROD_NAME'])));
							if(strlen($dbtablename) > 64){
								$dbtablename=substr($dbtablename,0,64);
							}
							if(table_exists($dbtablename)){
								$find_regss=mysql_query("select PRIKEY,EVENT_DATE from ".$dbtablename." where EVENT_DATE='".$DB_EVENT_DATE[$z]." ".$DB_EVENT_START[$z]."-".$DB_EVENT_END[$z]."'");
								$signupmax="/".$PROD['OPTION_INVENTORY_NUM'];
								//if($PROD['OPTION_INVENTORY_NUM']==50000){
									$signupmax=" signup";
								//}
								if(mysql_num_rows($find_regss) > 0){
									$dblink='database_manager/enter_edit_data.php?mt='.$dbtablename.'&TBL_SEARCH_FOR='.$DB_EVENT_DATE[$z]."+".$DB_EVENT_START[$z]."-".$DB_EVENT_END[$z].'&searchin=EVENT_DATE';
									echo "<span>(<a  style=\"font-size: 0.8em;\" href=\"".$dblink."\">".mysql_num_rows($find_regss).$signupmax."</a>)</span>";
								} else {
									echo "<span style=\"font-size: 0.8em;\">(".(mysql_num_rows($find_regss)+0).$signupmax.")</span>";	
								}
								
							}
						}
						
						echo "</LI>\n";
					} // End Found Event
				
				} // End Event Loop
				
			   if($foundit == 1){
			      echo "</ul>\n";
			   }
				
				// ========================================================================					
				
				echo "\n</TD>\n";
				
			} else {
			
				echo "\n<td align=left valign=top bgcolor=#efefef class=text style='height: 75px;'>";
				echo "&nbsp;";	
				echo "</TD>\n";
			
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
	
	echo "</FORM>\n";

?>