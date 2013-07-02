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



#######################################################
### CHECK FOR EDIT/DELETE PROCESSES
#######################################################

	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {
		if (ereg("DELETE", $name)) { 
			$ACTION = "DELETE"; 
			$tmp = eregi_replace("DELETE", "", $name);
			$a = split("-", $tmp);
			$id = $a[0];
			$type = $a[1];
		}
		if (ereg("EDIT", $name)) { 
			$ACTION = "EDIT";
			$tmp = eregi_replace("EDIT", "", $name);
			$a = split("-", $tmp);
			$id = $a[0];
			$type = $a[1];
		}
	}
	
#######################################################

if ($ACTION == "DELETE") {
	echo "[$id] DELETE WAS CLICKED";
	exit;
}

if ($ACTION == "EDIT") {

	header("Location: edit_event.php?id=$id&type=$type&".SID);
	exit;
	
}
#######################################################
### PERFORM UPDATE CATEGORY LISTING				    ###	
#######################################################

if ($ACTION == "SEARCH") {

	// Step 1: Build the sql "WHERE" clause...
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	if ($SEARCH_MONTH != "" && $SEARCH_YEAR != "") {
		$SQL_WHERE = "(EVENT_DATE LIKE '$SEARCH_YEAR-$SEARCH_MONTH-%') AND";
	}
	
	if ($SEARCH_MONTH != "" && $SEARCH_YEAR == "") {
		$SQL_WHERE = "(EVENT_DATE LIKE '%-$SEARCH_MONTH-%') AND";
	}
	
	if ($SEARCH_MONTH == "" && $SEARCH_YEAR != "") {
		$SQL_WHERE = "(EVENT_DATE LIKE '$SEARCH_YEAR-%') AND";
	}
	
	// What about Category Detail?
	
	if ($SEARCH_CATEGORY != "") {
		$SQL_WHERE .= " (EVENT_CATEGORY = '$SEARCH_CATEGORY') AND";
	}
	
	// Step 2: Setup Search String
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	$SQL_STRING = "SELECT PRIKEY, EVENT_DATE, EVENT_TITLE, EVENT_CATEGORY, EVENT_DETAILS, RECUR_MASTER FROM calendar_events WHERE $SQL_WHERE ";
	$SQL_STRING .= "(EVENT_TITLE LIKE '%$SEARCH_KEYWORDS%' OR EVENT_KEYWORDS LIKE '%SEARCH_KEYWORDS%' OR ";
	$SQL_STRING .= "EVENT_DETAILS LIKE '%SEARCH_KEYWORDS%' OR EVENT_DATE LIKE '%SEARCH_KEYWORDS%') AND EVENT_SECURITYCODE NOT LIKE '~~~%' ORDER BY EVENT_DATE";
	
	$SEARCH = mysql_query("$SQL_STRING");
	$TOTAL_FOUND = mysql_num_rows($SEARCH);	
		
} // End Event Search


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

$THIS_DISPLAY .= "<form method=\"post\" name=\"search_events_form\" action=\"search_events.php\">\n";
$THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"SEARCH\">\n";
$THIS_DISPLAY .= "<input type=\"hidden\" id=\"other_type\" name=\"other_type\" value=\"\">\n";

	ob_start(); 
		include("includes/event_search_form.php");
		$THIS_DISPLAY .= ob_get_contents(); 
	ob_end_clean(); 
	
	// Is this a search return? If so, display it below the search box
	
	if (isset($TOTAL_FOUND)) {
	
		$THIS_DISPLAY .= "<br/><br>\n";
		$THIS_DISPLAY .= "<table cellpadding=\"0\" cellspacing=\"0\" width=\"750\" align=\"center\" class=\"calendar_search_contain\">\n";
		$THIS_DISPLAY .= " <TR>\n";
		$THIS_DISPLAY .= "  <th align=\"left\" valign=\"top\" colspan=\"4\">\n";
		$THIS_DISPLAY .= "   ".$lang["Found"]." $TOTAL_FOUND ".$lang["events that match your search criteria"].":\n";
		$THIS_DISPLAY .= "  </th>\n";
		$THIS_DISPLAY .= " </tr>\n";
		
		while ($row = mysql_fetch_array($SEARCH)) {

			// Do not return Private Events or Pending Events on Search
			// -------------------------------------------------------------------------------
			
			if (!eregi("~~~", $row[EVENT_SECURITYCODE]) && strlen($row[EVENT_CATEGORY]) < 16) {
			
				if ($bgcolor == "white") { $bgcolor = "#EFEFEF"; } else { $bgcolor = "white"; }
				
				$tdate = $row[EVENT_DATE];
				$tmp = split("-", $tdate);
				$display_date = date("F j, Y", mktime(0,0,0,$tmp[1],$tmp[2],$tmp[0]));
				
				if ($row[RECUR_MASTER] == "Y") {
					$this_type = "<FONT COLOR=DARKGREEN>[M]</FONT>\n";
					$dadd = "m";
				} else {
					$dadd = "r";
					$this_type = "<FONT COLOR=MAROON>[R]</FONT>\n";
				}
				
				$THIS_DISPLAY .= "<TR>\n";
				
				// Commented out for Version 4.5 --> Redundant function and never finished in V4 anyway
				// -------------------------------------------------------------------------------------<BR>

				// $THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP BGCOLOR=$bgcolor WIDTH=50 style='border-top: 1px inset black;'>\n";
				// $THIS_DISPLAY .= "<INPUT TYPE=SUBMIT NAME=\"DELETE".$row[PRIKEY]."-$dadd\" VALUE=\" Delete Event \" CLASS=FormLt1 STYLE='background: red; color: white; border: 1px solid maroon;'></TD>\n";
				
				$THIS_DISPLAY .= "<td align=center valign=top bgcolor=\"".$bgcolor."\" width=50 style=''>\n";
				$THIS_DISPLAY .= "   <div name=\"EDIT".$row['PRIKEY']."-".$dadd."\" class=\"edit_event\" onclick=\"document.getElementById('other_type').name='EDIT".$row['PRIKEY']."-".$dadd."'; document.forms.search_events_form.submit();\" onMouseover=\"this.className='edit_event_over';\" onMouseout=\"this.className='edit_event';\" style=\"width: 120px;\">Edit Event</div>\n";
				//$THIS_DISPLAY .= "   <input type=submit name=\"EDIT".$row['PRIKEY']."-".$dadd."\" value=\" Edit Event \" class=\"cal_btn\" onMouseover=\"this.className='cal_btn_over';\" onMouseout=\"this.className='cal_btn';\" style=\"width: 120px;\">\n";
				$THIS_DISPLAY .= "</td>\n";
				
				$THIS_DISPLAY .= "<td align=right valign=top bgcolor=".$bgcolor." width=100 style='color:#2F2F2F;'>\n";
				$THIS_DISPLAY .= $display_date;
				$THIS_DISPLAY .= "</td>\n";
				
				$THIS_DISPLAY .= "<td align=left valign=top bgcolor=".$bgcolor." width=500 style='color:#2F2F2F; border-right: 1px solid #A2ADBC;'>\n";
				$THIS_DISPLAY .= $this_type." &nbsp;&nbsp; ".$row['EVENT_TITLE']."\n";
				$THIS_DISPLAY .= "</td>\n";
				
	
				$THIS_DISPLAY .= "</tr><tr><td align=left valign=top colspan=4 bgcolor=".$bgcolor." style=\"border-right: 1px solid #A2ADBC; border-bottom: 1px solid #A2ADBC;\">\n";
	
				if ($row[EVENT_DETAILS] == "") { $row[EVENT_DETAILS] = "<FONT COLOR=#999999>[ No Details ]</FONT>"; }
				
				$THIS_DISPLAY .= "$row[EVENT_DETAILS]</TD></TR>\n";
				
			}
			
		} // End While Loop
					
		if ($TOTAL_FOUND == 0) {
			$THIS_DISPLAY .= "<tr><td align=center valign=top class=text colspan=4>".$lang["Sorry, no events where found for your search. Please try again."]."</td></tr>\n";
		}
		
		$THIS_DISPLAY .= "</TABLE>\n\n";	
	
	}

$THIS_DISPLAY .= "</FORM>\n";


echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Find an event by searching for a keyword, specific Month/Year or specific category.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Calendar Search";
$module->add_breadcrumb_link("Event Calendar", "program/modules/mods_full/event_calendar.php");
$module->add_breadcrumb_link("Calendar Search", "program/modules/mods_full/event_calendar/search_events.php");
$module->icon_img = "program/includes/images/calendar-icon-med.png";
$module->heading_text = "Calendar Search";
$module->description_text = $instructions;
$module->good_to_go();
?>