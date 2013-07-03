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

session_start();
require_once("../../includes/product_gui.php");

#######################################################
### STAcRT HTML/JAVASCRIPT CODE						###
#######################################################
if(!function_exists('sterilize_char')){
	function sterilize_char ($sterile_var) {
	
		$sterile_var = stripslashes($sterile_var);
		$sterile_var = str_replace(";", ",", $sterile_var);
		$sterile_var = str_replace(" ", "_", $sterile_var);
		$st_l = strlen($sterile_var);
		$st_a = 0;
		$tmp = "";
		while($st_a != $st_l) {
			$temp = substr($sterile_var, $st_a, 1);
			if (eregi("[0-9a-z_]", $temp)) { $tmp .= $temp; }
			$st_a++;
		}//endwhile	
		$sterile_var = $tmp;
		return $sterile_var;	
	}//sterilize_char
}
$MOD_TITLE = lang("Event Calendar: Main Menu");
$BG = "shared/enews_bg.jpg";

//foreach($_REQUEST as $var=>$val){
//   echo "var = (".$var.") val = (".$val.")<br>\n";
//}

#######################################################
### CHECK FOR MAIN MENU SELECTIONS
#######################################################

if ( $_REQUEST['CATEGORY'] != "" ) {
	header("Location: event_calendar/category_setup.php?".SID);
	exit;
}

if ( $_REQUEST['SEARCH'] != "" ) {
	header("Location: event_calendar/search_events.php?".SID);
	exit;
}

if ( $_REQUEST['DISPLAY'] != "" ) {
	header("Location: event_calendar/cal_display_settings.php?".SID);
	exit;
}

#######################################################
### IF THE 'calendar_events' TABLE DOES NOT EXIST;
### CREATE NOW
#######################################################
if(!table_exists('calendar_events')){
	create_table('calendar_events');
}

#######################################################
### IF THE 'calendar_category' TABLE DOES NOT EXIST;
### CREATE NOW
#######################################################
if(!table_exists('calendar_category')){
	create_table('calendar_category');
}

#######################################################
### IF THE 'calendar_display' TABLE DOES NOT EXIST;
### CREATE NOW
#######################################################
if(!table_exists('calendar_display')){
	create_table('calendar_display');
}

$getinfo = mysql_query("SHOW FIELDS FROM calendar_events");
echo mysql_error();
while($getinfo_ar = mysql_fetch_assoc($getinfo)){
	if($getinfo_ar['Field']=='PRIKEY'){
		if(!preg_match('/varchar/i', $getinfo_ar['Type'])){
			mysql_query("alter table calendar_events modify PRIKEY varchar(255)");
		}
	}
}

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

function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}
function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}


show_hide_layer('MAIN_MENU_LAYER?header','','hide');
show_hide_layer('CALENDAR_MENU_LAYER?header','','show');
//var p = "Event Calendar";
//parent.frames.footer.setPage(p);


//-->
</script>

<style>

form {
   margin:0;
}

.cal_btn {
   margin:0;
/*   padding-top:1px;
   padding-bottom:1px;*/
   text-align: center;
   border: 3px outset #CFCFCF;
   /*border: 1px dashed red;*/
   cursor: pointer;
   background: #86BBEF;
   /*width: 100%;*/
}

.cal_btn_over {
/*   padding-top:1px;
   padding-bottom:1px;*/
   text-align: center;
   border: 3px outset #A2ADBC;
   cursor: pointer;
   background: #539ADF;
   /*width: 100%;*/
}

.cal_nav {
   /*border: 1px dashed #000000;*/
   margin:-10 -10 0 -10;
   /*margin:0;*/
   padding:0;
   /*display: none;*/
   height: 29px;
   background-image: url(event_calendar/images/nav_bar2.gif);
   width:780px;
}

.view_btn {
   font-weight: bold;
   color: #FFFFFF;
   float: right; 
   text-align: center; 
   padding-top: 3px; 
   background-image:url(event_calendar/images/view_btn.gif); 
   background-repeat: no-repeat; 
   width:58px; 
   height: 19px; 
   margin-top: 5px; 
   margin-right: 20px;
   cursor: pointer;
}

.cal_main_btn {
   color: #FFFFFF;
   font-weight: bold;
   float: left; 
   text-align: center; 
   padding-top: 3px; 
   background-image:url(event_calendar/images/cal_main_btn.gif); 
   background-repeat: no-repeat; 
   width:121px; 
   height: 19px; 
   margin-top: 5px; 
   margin-left: 20px;
   cursor: pointer;
}

.edit_view {
   font-weight: bold;
   color: #FFFFFF;
   float: right; 
   /*border: 1px dashed #000000; */
   margin-top: 5px; 
   margin-right: 15px;
}

</style>
<?php
echo "<script type=\"text/javascript\">
function changedate(month,year){ 
	var sel = document.getElementById('SEL_MONTH');
	for(var i, j = 0; i = sel.options[j]; j++) {
		if(i.value == month) {
			sel.selectedIndex = j;
			//break;
		}
	}
	var sel2 = document.getElementById('SEL_YEAR');
	for(var i2, j2 = 0; i2 = sel2.options[j2]; j2++) {
		if(i2.value == year) {
			sel2.selectedIndex = j2;
		}
	}
	document.forms.top_nav_form.submit();
}
</script>\n";


########################################################################
### IF THIS IS FIRST RUN; SET CURRENT MONTH AND YEAR TO "TODAY"
########################################################################

if ($SEL_MONTH == "" && $SEL_YEAR == "") {
	$SEL_MONTH = date("m");
	$SEL_YEAR = date("Y");
}


########################################################################
### SETUP GLOBAL CALENDAR VARS
########################################################################

$day_of_week = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

$add_button_style = "width: 49px; font-family: Arial; background-color: darkgreen; color: white; font-size: 7pt; cursor: hand; border: inset #999999 1px;";

//$MONTH_OPTIONS = "";
//
//
//for ($x=1;$x<=12;$x++) {
//	$val = date("m", mktime(0,0,0,$x,1,2002));
//	$display = date("M", mktime(0,0,0,$x,1,2002));
//	if ($val == $SEL_MONTH) { $SEL = "SELECTED"; } else { $SEL = ""; }
//	$MONTH_OPTIONS .= "<OPTION VALUE=\"$val\" $SEL>$display</OPTION>\n";
//}
//
//$YEAR_OPTIONS = "";
//
//for ($x=2002;$x<=2015;$x++) {
//	if ($x == $SEL_YEAR) { $SEL = "SELECTED"; } else { $SEL = ""; }
//	$YEAR_OPTIONS .= "<OPTION VALUE=\"$x\" $SEL>$x</OPTION>\n";
//}


$MONTH_OPTIONS = "";
$lastyear = date('Y',strtotime('-366 days'));
for ($x=1;$x<=12;$x++) {
	$val = date("m", mktime(0,0,0,$x,1,$lastyear));
	$display = date("M", mktime(0,0,0,$x,1,$lastyear));
	if ($val == $SEL_MONTH) { $SEL = "SELECTED"; } else { $SEL = ""; }
	$MONTH_OPTIONS .= "<OPTION VALUE=\"$val\" $SEL>$display</OPTION>\n";
}

$YEAR_OPTIONS = "";
for ($x=$lastyear;$x<=($lastyear+5);$x++) {
	if ($x == $SEL_YEAR) { $SEL = "SELECTED"; } else { $SEL = ""; }
	$YEAR_OPTIONS .= "<OPTION VALUE=\"$x\" $SEL>$x</OPTION>\n";
}





########################################################################
### START HEADER MENU NAVIGATION
########################################################################

	$THIS_DISPLAY .= "<form method=post name=\"top_nav_form\" action=\"event_calendar.php\">\n\n";
	$THIS_DISPLAY .= "   <input type=\"hidden\" id=\"action_type\" name=\"action_type\" value=\"action_type\" />\n";
	
	$THIS_DISPLAY .= "<div class=\"cal_nav\">\n";
	
	$THIS_DISPLAY .= "   <div class=\"cal_main_btn\" onclick=\"document.getElementById('action_type').name='SEARCH'; document.forms.top_nav_form.submit();\">".lang("Search Events")."</div>\n";
	$THIS_DISPLAY .= "   <div class=\"cal_main_btn\" onclick=\"document.getElementById('action_type').name='DISPLAY'; document.forms.top_nav_form.submit();\">".lang("Display Settings")."</div>\n";
	$THIS_DISPLAY .= "   <div class=\"cal_main_btn\" onclick=\"document.getElementById('action_type').name='CATEGORY'; document.forms.top_nav_form.submit();\">".lang("Category Setup")."</div>\n";
	$THIS_DISPLAY .= "   <div class=\"view_btn\" onclick=\"document.forms.top_nav_form.submit();\">View</div>\n";
	$THIS_DISPLAY .= "   <div class=\"edit_view\">\n";
	$THIS_DISPLAY .= "      ".lang("Edit View").": <SELECT id=\"SEL_MONTH\" NAME=\"SEL_MONTH\">$MONTH_OPTIONS</SELECT> <SELECT id=\"SEL_YEAR\" NAME=\"SEL_YEAR\">$YEAR_OPTIONS</SELECT>\n";
	$THIS_DISPLAY .= "   </div>\n";
	$THIS_DISPLAY .= "</div>\n";
	
	
//	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=0 WIDTH=\"100%\">
//	$THIS_DISPLAY .= " <TR>\n";
//	$THIS_DISPLAY .= "  <td align=center valign=middle >\n";
//
//	// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
//	$onBtns = "class=\"cal_btn\" onMouseover=\"this.className='cal_btn_over';\" onMouseout=\"this.className='cal_btn';\""; // Edit/View Button
//
//		$THIS_DISPLAY .= "   <INPUT TYPE=SUBMIT NAME=SEARCH ".$onBtns." VALUE=\" ".lang("Search Events")." \">\n";
//		$THIS_DISPLAY .= "   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//		$THIS_DISPLAY .= "   <INPUT TYPE=SUBMIT NAME=DISPLAY ".$onBtns." VALUE=\" ".lang("Display Settings")." \" style=\"width: 125px;\">\n";
//		$THIS_DISPLAY .= "   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//		$THIS_DISPLAY .= "   <INPUT TYPE=SUBMIT NAME=CATEGORY ".$onBtns." VALUE=\" ".lang("Category Setup")." \" style=\"width: 125px;\">\n";
//		$THIS_DISPLAY .= "   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
//
//		$THIS_DISPLAY .= "   ".lang("Edit View").": <SELECT NAME=\"SEL_MONTH\">$MONTH_OPTIONS</SELECT> <SELECT NAME=\"SEL_YEAR\">$YEAR_OPTIONS</SELECT>\n";
//		$THIS_DISPLAY .= "   &nbsp;<INPUT TYPE=image src=\"event_calendar/images/view_btn.gif\" VALUE=\" ".lang("View")." \" style=\"margin-top: 3px;\">\n";
//
//	$THIS_DISPLAY .= "  </TD>\n";
//	$THIS_DISPLAY .= " </TR>\n";
//	$THIS_DISPLAY .= "</table>\n";
	
	
	$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"780px\">\n";
	$THIS_DISPLAY .= " <tr>\n";
	$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text gray\" style=\"padding-top: 5px;\">\n";
	$THIS_DISPLAY .= "   <font color=\"#FF0033\">[R]</font> = ".lang("Denotes an event that is a 'Recurrence' of an original master event.")."\n";
	$THIS_DISPLAY .= "  </td>\n";
	$THIS_DISPLAY .= " </tr>\n";
	$THIS_DISPLAY .= " <tr>\n";
	$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\" class=\"text gray\" style=\"padding-top: 10px; padding-bottom: 5px;\">\n";
	$THIS_DISPLAY .= "   <font color=\"#339959\">[M]</font> = ".lang("Denotes the original 'Master' event within a recurring event cycle.")."\n";
	$THIS_DISPLAY .= "  </td>\n";
	$THIS_DISPLAY .= " </tr>\n";
	$THIS_DISPLAY .= "</table>\n";
	
	$THIS_DISPLAY .= "</form>\n\n";


########################################################################
### PULL EVENT DATA FOR SELECTED MONTH AND YEAR
########################################################################

$tmp = "$SEL_YEAR-$SEL_MONTH";
$result = mysql_query("SELECT PRIKEY, EVENT_DATE, EVENT_TITLE, EVENT_CATEGORY, EVENT_SECURITYCODE, RECUR_MASTER,EVENT_START,EVENT_END,EVENT_DETAILPAGE FROM calendar_events WHERE EVENT_DATE LIKE '$tmp%'");
//$find_dates = mysql_query("select PRIKEY,EVENT_DATE,EVENT_START,EVENT_END,EVENT_DETAILPAGE,custom_start,custom_end from calendar_events where EVENT_DATE >= '".date('Y-m-d')."' and EVENT_DETAILPAGE='".$tmp_keyid[$z]."' order by EVENT_DATE, EVENT_START");
// $NUM_EVENTS = mysql_num_rows($result);	!! Only if there are no personal calendars !!

$x=0;

while ($row = mysql_fetch_assoc($result)) {

	$x++;

	if (strlen($row[EVENT_CATEGORY]) > 15 || eregi("~~~", $row[EVENT_SECURITYCODE])) {	// Don't show Personal Calendar Events for users
		$x = $x - 1;
	} else {
		$DB_EVENT_PRIKEY[$x] = $row[PRIKEY];
		$DB_EVENT_DATE[$x] = $row[EVENT_DATE];
		$DB_EVENT_TITLE[$x] = $row[EVENT_TITLE];
		$DB_EVENT_CATEGORY[$x] = $row[EVENT_CATEGORY];
		$DB_RECUR_MASTER[$x] = $row[RECUR_MASTER];
		$DB_EVENT_SECURITYCODE[$x] = $row[EVENT_SECURITYCODE];
		$DB_EVENT_PAGE[$x] = $row[EVENT_DETAILPAGE];
		$DB_EVENT_END[$x] = $row[EVENT_END];
		$DB_EVENT_START[$x] = $row[EVENT_START];
		
		
	}
 
} // End While Loop

$NUM_EVENTS = $x;

########################################################################
### BUILD 3.5 CALENDAR MANAGER GUI
########################################################################

	ob_start();
		include("event_calendar/build_month.php");
		$THIS_DISPLAY .= ob_get_contents();
	ob_end_clean();



echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Manage your calendar by adding events, changing display settings and organizing your month.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Event Calendar";
$module->add_breadcrumb_link("Event Calendar", "program/modules/mods_full/event_calendar.php");
$module->icon_img = "program/includes/images/calendar-icon-med.png";
$module->heading_text = "Event Calendar";
$module->description_text = $instructions;
$module->good_to_go();
?>