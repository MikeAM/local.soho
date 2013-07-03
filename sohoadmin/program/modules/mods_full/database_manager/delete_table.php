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

error_reporting(E_PARSE);
session_start();

//include("../includes/login.php");
//include("../includes/db_connect.php");
include("../../../../includes/emulate_globals.php");
require_once("../../../includes/product_gui.php");

#######################################################
### FIND ALL USER DATA TABLES (UDT) AND CREATE VAR
### TO POPULATE "CURRENT" TABLE DROP DOWN BOX
#######################################################

$result = soho_list_tables();
$i = 0;
$CURRENT_TABLES = "     <OPTION VALUE=\"\">Choose Table to Delete...</OPTION>\n";
while ($i < mysql_num_rows ($result)) {
	$tb_names[$i] = mysql_tablename ($result, $i);
	if ( eregi("UDT_", $tb_names[$i]) ) {
		$CURRENT_TABLES1 .= "     <OPTION VALUE=\"".$tb_names[$i]."\">".$tb_names[$i]."</OPTION>\n";
	} else {
	   $CURRENT_TABLES2 .= "     <OPTION VALUE=\"".$tb_names[$i]."\" style=\"color: #ccc;\">".$tb_names[$i]."</OPTION>\n";
	}
	$i++;
}
$CURRENT_TABLES .= $CURRENT_TABLES1.$CURRENT_TABLES2;
#######################################################
### START HTML/JAVASCRIPT CODE			    		###
#######################################################

$MOD_TITLE = lang("Table Manager: Delete Table");

// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
$editOn = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";
$saveOn = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
$buildOn = "class=\"btn_build\" onMouseover=\"this.className='btn_buildon';\" onMouseout=\"this.className='btn_build';\"";
$deleteOn = "class=\"btn_delete\" onMouseover=\"this.className='btn_deleteon';\" onMouseout=\"this.className='btn_delete';\"";

##########################################################
### PROCESS DELETION OF DATABASE BASED ON CONFIRMATION ###
##########################################################

if ($ACTION == "2") {
	mysql_query("DROP TABLE $DELETE_TABLE");
	$sess_dbname = strtolower($_POST['DELETE_TABLE']);
	unset($_SESSION['recent_tables'][$sess_dbname]);	
	header("Location: ../download_data.php?=SID");
	exit;
}

ob_start();
?>
<script type="text/javascript"> 
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

function cancel_delete() {
	window.location = '../download_data.php?<?=SID?>';
}

function confirm_delete() {
   var disOne = document.getElementById('DELETE_TABLEname').selectedIndex;
	//var table_name = eval("document.getElementById('DELETE_TABLEname').options["+disOne+"].value");
	var table_name = document.getElementById('DELETE_TABLEname').selectedIndex;;
	//var table_name = DELFORM.DELETE_TABLE.options.value;
	if (table_name != "") {
		<?php echo "var tiny = window.confirm('!! ".lang("WARNING")." !!\\n\\n".lang("YOU ARE ABOUT TO DELETE THE TABLE")."\\n\"'+table_name+'\" ".lang("AND LOSE ALL RECORD DATA")."\\n".lang("CONTAINED INSIDE OF IT.")."\\n\\n ".lang("Are you sure you wish to do this now")."?');"; ?>
		
		if (tiny != false) {
		   //alert('ok');
			document.DELFORM.submit();
		}else{
		   //alert('ok2');
		}
	} else {
		<?php echo "alert('".lang("You did not select a table to delete.")."');\n"; ?>
	}
	
	
}
</script>
<?

IF ($ACTION == "") {

	$THIS_DISPLAY .= "<FORM NAME=DELFORM METHOD=POST ACTION=\"delete_table.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"ACTION\" VALUE=\"2\">\n\n";

	$THIS_DISPLAY .= "<BR><TABLE BORDER=0 CELLPADDING=8 CELLSPACING=0 CLASS=allBorder width=\"100%\"><TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=\"center\" VALIGN=TOP bgcolor=red>\n";
	$THIS_DISPLAY .= "<B><FONT COLOR=white>\n";
	$THIS_DISPLAY .= lang("NOTE").": <U>".lang("THIS PROCESS CAN NOT BE REVERSED ONCE COMPLETED.")."</U><BR><BR>".lang("ALL DATA WILL BE LOST WHEN THIS TABLE IS DELETED.")." \n";
	$THIS_DISPLAY .= lang("YOU WILL HAVE ONE CHANCE TO CONFIRM, BUT ONCE YOU 'OK' THE CONFIRMATION, THE TABLE WILL BE DELETED")."!\n\n";
	$THIS_DISPLAY .= "<BR><BR>\n";
	$THIS_DISPLAY .= "<DIV ALIGN=CENTER>".lang("Delete Table").": <SELECT name=\"DELETE_TABLE\" id=\"DELETE_TABLEname\" CLASS=text STYLE='width: 275px;'>"."\n";
	$THIS_DISPLAY .= $CURRENT_TABLES."\n</SELECT><BR></FONT></DIV>\n";
	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<span class=\"dgray\">".lang("Note: The light gray table names are system tables. Do not delete these unless you know what you're doing.")."</span>\n";

	$THIS_DISPLAY .= "<BR><BR><DIV ALIGN=CENTER><button class=\"blueButton\" TYPE=BUTTON onClick=\"cancel_delete();\"><span><span>".lang("Cancel Delete")."</span></span></button>\n";
	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	$THIS_DISPLAY .= "<button class=\"redButton\" TYPE=BUTTON  onclick=\"confirm_delete();\"><span><span>".lang("Delete Selected Table")."</span></span></button></DIV>\n";

	$THIS_DISPLAY .= "</FORM>\n\n";

} // End No Action (New Setup)

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = "Completely and permanently drop (delete) an entire database table. Careful with this, especially in regards to system data tables (system tables marked with gray text in drop-down box below).";

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Database Tables", "program/modules/mods_full/download_data.php");
$module->add_breadcrumb_link("Drop/Delete Database Table", "program/modules/mods_full/database_manager/delete_table.php");
$module->icon_img = "program/includes/images/data-tables-icon-med.png";
$module->heading_text = "Drop/Delete Database Table";
$module->description_text = $instructions;
$module->good_to_go();
?>