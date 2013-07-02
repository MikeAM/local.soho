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

# Include core files
require_once("../../../includes/product_gui.php");
require_once("../../../includes/smt_module.class.php");

$calpref = new userdata("calendar");

#######################################################
### SAVE DISPLAY SETTINGS
#######################################################
if ($ACTION == "save") {

	mysql_query("DELETE FROM calendar_display"); 	// Delete single record table completly

	mysql_query("INSERT INTO calendar_display VALUES('NULL','$TEXT_COLOR','$BACKGROUND_COLOR','$ALLOW_PERSONAL_CALENDARS','$DISPLAY_STYLE','$ALLOW_PUBLIC_SUBMISSIONS','$EMAIL_CONFIRMATION','','')");

}

# Save line break pref
if ( isset($_GET['preserve_breaks']) ) {
   $calpref->set("linebreaks", $_GET['preserve_breaks']);
}


#######################################################
### START HTML/JAVASCRIPT CODE					    ###
#######################################################

$MOD_TITLE = $lang["Calendar Display Settings"];

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

show_hide_layer('addCartMenu?header','','hide');
show_hide_layer('blankLayer?header','','hide');
show_hide_layer('linkLayer?header','','hide');
show_hide_layer('newsletterLayer?header','','hide');
show_hide_layer('cartMenu?header','','show');
show_hide_layer('menuLayer?header','','hide');
show_hide_layer('editCartMenu?header','','hide');

//-->
</script>

<style>

form {
   margin:0;
}

.calendar_search_contain {
	/*width: 141px;*/
	padding: 0;
	margin: 0;
	border-left: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	font: normal 12px/20px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #33393F;
	text-align: center;
	background-color: #fff;
}

.calendar_search_contain th {
	font: bold 11px/20px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	/*color: #4D565F;*/
	background: #D9E2E1;
	border-right: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	border-top: 1px solid #A2ADBC;
	padding:2;
}

.calendar_search_contain td {
   padding-bottom:7px;
	/*border-right: 1px solid #A2ADBC;*/
	/*border-bottom: 1px solid #A2ADBC;*/
	/*text-align: center;*/
}

.cal_btn {
   margin:0;
   /*padding-top:2px;
   padding-bottom:2px;*/
   text-align: center;
   border: 2px outset #CFCFCF;
   /*border: 1px dashed red;*/
   cursor: pointer;
   background: #A7DFAF;
   /*width: 100%;*/
}

.cal_btn_over {
   /*padding-top:2px;
   padding-bottom:2px;*/
   text-align: center;
   border: 2px outset #AFFFBA;
   cursor: pointer;
   background: #6FDF7E;
   /*width: 100%;*/
}

</style>

<?php

$result = mysql_query("SELECT * FROM calendar_display");
$DISPLAY = mysql_fetch_array($result);

$THIS_DISPLAY .= "<FORM NAME=\"displayform\" METHOD=POST ACTION=\"cal_display_settings.php\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"save\">\n";

	ob_start();
	 	include("includes/calendar_settings_form.php");
		$THIS_DISPLAY .= ob_get_contents();
	ob_end_clean();

$THIS_DISPLAY .= "</FORM>\n";
echo $THIS_DISPLAY;


# DEFAULT: Do not preserve line breaks (changing might wreak havok on backwards-compatibility)
if ( $calpref->get("linebreaks") == "" ) { $calpref->set("linebreaks", "no"); } // Set default
?>

<script type="text/javascript">
document.getElementById('preserve_breaks').value = '<? echo $calpref->get("linebreaks"); ?>';
document.displayform.TEXT_COLOR.value = '<? echo $text_color; ?>';
document.displayform.BACKGROUND_COLOR.value = '<? echo $back_color; ?>';
</script>


<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = "Take control of your calendar display colors and other advanced options.";

$module = new smt_module($module_html);
$module->meta_title = "Calendar Display Settings";
$module->add_breadcrumb_link("Event Calendar", "program/modules/mods_full/event_calendar.php");
$module->add_breadcrumb_link("Display Settings", "program/modules/mods_full/event_calendar/cal_display_settings.php");
$module->icon_img = "program/includes/images/calendar-icon-med.png";
$module->heading_text = "Calendar Display Settings";
$module->description_text = $instructions;
$module->good_to_go();
?>