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
### PERFORM UPDATE CATEGORY LISTING				    ###	
#######################################################

if ($ACTION == "UPDATE_CATS" && strlen($NEWCAT) > 1) {

	$NEWCAT = stripslashes($NEWCAT);
	$NEWCAT = addslashes($NEWCAT);
	
	mysql_query("INSERT INTO calendar_category VALUES('NULL','$NEWCAT')");
		
} // End Update Cats

if ($ACTION == "DELETE" && $id > 0) {

	mysql_query("DELETE FROM calendar_category WHERE PRIKEY = '$id'");
	
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
   padding-top:1px;
   padding-bottom:1px;
   text-align: center;
   border: 2px outset #CFCFCF;
   /*border: 1px dashed red;*/
   cursor: pointer;
   background: #A7DFAF;
   /*width: 100%;*/
}

.cal_btn_over {
   padding-top:1px;
   padding-bottom:1px;
   text-align: center;
   border: 2px outset #AFFFBA;
   cursor: pointer;
   background: #6FDF7E;
   /*width: 100%;*/
}

</style>

<?

// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
$saveOn = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
	
$THIS_DISPLAY .= "<FORM METHOD=POST name=\"addcategory\" ACTION=\"category_setup.php\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"UPDATE_CATS\">\n";

$THIS_DISPLAY .= "<table cellpadding=8 cellspacing=0 width=95% align=center class=\"calendar_search_contain\">";
$THIS_DISPLAY .= "<TR>\n";
$THIS_DISPLAY .= "   <th width=\"40%\" align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "      ".lang("Create New Category").":\n";
$THIS_DISPLAY .= "   </th>\n";
$THIS_DISPLAY .= "   <th align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "      ".lang("Current Categories").":\n";
$THIS_DISPLAY .= "   </th>\n";
$THIS_DISPLAY .= "</tr>\n";
$THIS_DISPLAY .= "<TR><td WIDTH=40% ALIGN=LEFT VALIGN=TOP BGCOLOR=#EFEFEF style=\"border-right: 1px solid #A2ADBC;\">\n";
$THIS_DISPLAY .= "<input type=text name=NEWCAT value=\"\" style='width: 200px;' MAXLENGTH=150><BR>\n";
$THIS_DISPLAY .= "<br><button onclick=\"document.addcategory.submit();\" type=button class=\"greenButton\" ><span><span>".lang("Add Category")."</span></span></button>\n";
$THIS_DISPLAY .= "</td><td align=left valign=top width=60% style=\"border-right: 1px solid #A2ADBC;\">\n";
$THIS_DISPLAY .= "<UL>\n";

$result = mysql_query("SELECT * FROM calendar_category ORDER BY Category_Name");
while ($row = mysql_fetch_array($result)) {
	$THIS_DISPLAY .= "<LI>[ <A HREF=\"category_setup.php?ACTION=DELETE&id=$row[PRIKEY]\">".lang("Delete")."</A> ] $row[Category_Name]<BR>&nbsp;</LI>\n";
}
$THIS_DISPLAY .= "</UL><BR>\n";

$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

$THIS_DISPLAY .= "</FORM>\n";


echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = "Calendar categories allow you to maintain separate calendars depending on your need.";

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Calendar Categorys";
$module->add_breadcrumb_link("Event Calendar", "program/modules/mods_full/event_calendar.php");
$module->add_breadcrumb_link("Calendar Categorys", "program/modules/mods_full/event_calendar/category_setup.php");
$module->icon_img = "program/includes/images/calendar-icon-med.png";
$module->heading_text = "Calendar Categorys";
$module->description_text = $instructions;
$module->good_to_go();
?>