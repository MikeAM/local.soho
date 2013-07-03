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
include("../../includes/product_gui.php");

#######################################################
### FIND ALL USER DATA TABLES (UDT) AND CREATE VAR
### TO POPULATE "CURRENT" TABLE DROP DOWN BOX
#######################################################

$tBG = "white";
$result = soho_list_tables();
$i = 0;
$CURRENT_TABLES = "     <OPTION VALUE=\"\" STYLE='COLOR: darkblue;'>Current database tables...</OPTION>\n";
while ($i < mysql_num_rows ($result)) {
	$tb_names[$i] = mysql_tablename ($result, $i);

	# Make sure admin user has rights to view this table
	if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";$tb_names[$i];", $CUR_USER_ACCESS)) {

	   # Alternate bg color
	   if ($tBG == "white") { $tBG = "#EFEFEF"; } else { $tBG = "white"; }

	   # Put UDT_ and system tables into separate arrays for sorting
	   if ( eregi("UDT_", $tb_names[$i]) ) {
	      $udt_tables[$tb_names[$i]] = "     <OPTION VALUE=\"".$tb_names[$i]."\" style=\"background: $tBG;\">".$tb_names[$i]."</OPTION>\n";
	   } else {
	      $system_tables[$tb_names[$i]] = "     <OPTION VALUE=\"".$tb_names[$i]."\" style=\"background: $tBG;color: #888c8e;\">".$tb_names[$i]."</OPTION>\n";
	   }
	} // End if webmaster/authorized admin

//	if ( ($getSpec["dev_mode"] != "imadev" && eregi("UDT_", $tb_names[$i]) || eregi("BLOG_", $tb_names[$i])  ) || eregi("imadev", $getSpec["dev_mode"]) ) {		// Only Get UDT Tables (Remember They can delete these kinds of tables (Dangerous)
//
//		// Added for Multi-User Access Rights
//		if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";$tb_names[$i];", $CUR_USER_ACCESS)) {
//			$tmp_display = strtoupper($tb_names[$i]);
//			if ($tBG == "white") { $tBG = "#EFEFEF"; } else { $tBG = "white"; }
//			$CURRENT_TABLES .= "     <OPTION VALUE=\"$tb_names[$i]\" style='background: $tBG;'>$tmp_display</OPTION>\n";
//		}
//	}
	$i++;
} // End while looping through tablenames

# Combine sorted dropdown options
# List user tables first
$CURRENT_TABLES .= "     <OPTION VALUE=\"\" style=\"background-color: #66cc91; font-weight: bold;\">".lang("User-created data tables")."</OPTION>\n";
foreach ( $udt_tables as $tablename=>$dd_option ) {
   $CURRENT_TABLES .= $dd_option;
}
# Now add system table options to bottom
$CURRENT_TABLES .= "     <OPTION VALUE=\"\" style=\"background-color: #ccc; color: #980000; font-weight: bold;\">".lang("System data tables - advanced users only")."</OPTION>\n";
foreach ( $system_tables as $tablename=>$dd_option ) {
   $CURRENT_TABLES .= $dd_option;
}


#######################################################
### START HTML/JAVASCRIPT CODE			  			###
#######################################################

ob_start();

// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
$editOn = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";
$saveOn = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
$buildOn = "class=\"btn_build\" onMouseover=\"this.className='btn_buildon';\" onMouseout=\"this.className='btn_build';\"";
$deleteOn = "class=\"btn_delete\" onMouseover=\"this.className='btn_deleteon';\" onMouseout=\"this.className='btn_delete';\"";
?>

<script language="javascript">
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

show_hide_layer('NEWSLETTER_LAYER?header','','hide');
show_hide_layer('MAIN_MENU_LAYER?header','','hide');
show_hide_layer('CART_MENU_LAYER?header','','hide');
show_hide_layer('DATABASE_LAYER?header','','show');
//var p = "Database Table Manager";
//parent.frames.footer.setPage(p);


function navto(a,b) {
	if (a != "") {
		if (b == "data") {
			var b = document.DB.CURRENT_TABLES.value;
		}
		window.location = a+"?mt="+b+"&<?=SID?>";
	}
}

function ok_data(v) {
	if (v != "") {
		if (v != "UDT_CONTENT_SEARCH_REPLACE" && v != "udt_content_search_replace") {
			document.DB.MOD1.disabled = '';
			document.DB.MOD2.disabled = '';
			document.getElementById('notice').innerHTML = "<font face=Arial color=darkblue><B>You may now choose to modify the selected table<BR>or enter/edit its record data.</B></font>";
		} else {
			document.DB.MOD1.disabled = 'true';
			document.DB.MOD2.disabled = '';
			document.getElementById('notice').innerHTML = "<font face=Arial color=maroon><B><I>The UDT_CONTENT_SEARCH_REPLACE table is a system table and can not be deleted or modified. Refer to users manual to learn how this table operates and interacts with your website.</b></i></font>";
		}
	} else {
		document.DB.MOD1.disabled = 'true';
		document.DB.MOD2.disabled = 'true';
		document.getElementById('notice').innerHTML = "<font face=Arial color=darkblue><B><I>Please select a user data table.</I></B></font>";
	}
}


//-->
</script>

<FORM NAME=DB>

<?

// -------------------------------------------------------------------------------
// Since this is the "intro" to all of the database table mangement, we will present
// an "easy to follow" menu system that mimics the main menu.
// -------------------------------------------------------------------------------

	if ($CUR_USER_ACCESS != "WEBMASTER") { $ACCESS_TMP = "DISABLED"; } else { $ACCESS_TMP = ""; }


	$THIS_DISPLAY .= "\n\n<BR><BR><TABLE BORDER=0 CELLPADDING=8 CELLSPACING=0 CLASS=allBorder><TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP WIDTH=50%>\n";

	$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\"".lang("Create New Data Table")."\" ".$buildOn." onclick=\"navto('database_manager/create_table.php','');\" style='width: 150px;'><BR>&nbsp;<BR>\n";
	$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\"".lang("Create a Table Search Form")."\"  ".$buildOn." onclick=\"navto('database_manager/wizard_start.php','');\" style='width: 150px;'><BR>&nbsp;<BR>\n";
	$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\"".lang("Delete a Table")."\" $ACCESS_TMP ".$deleteOn." onclick=\"navto('database_manager/delete_table.php','');\" style='width: 150px;'>\n";
	$THIS_DISPLAY .= "</TD><TD ALIGN=CENTER VALIGN=TOP WIDTH=50%>\n";

	$THIS_DISPLAY .= "<SELECT NAME=\"CURRENT_TABLES\" CLASS=text STYLE='width: 350px; font-size: 8pt;'; onchange=\"ok_data(this.value);\">$CURRENT_TABLES</SELECT><BR><BR>\n";
	$THIS_DISPLAY .= "<INPUT DISABLED NAME=MOD1 TYPE=BUTTON VALUE=\"".lang("Modify Selected Table")."\" ".$editOn." onclick=\"navto('database_manager/modify_table.php','data');\" style='width: 150px;'>\n";
	$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;";
	$THIS_DISPLAY .= "<INPUT DISABLED NAME=MOD2 TYPE=BUTTON VALUE=\"".lang("Enter/Edit Record Data")."\" ".$editOn." onclick=\"navto('database_manager/enter_edit_data.php','data');\" style='width: 150px;'>\n";

	if ($CUR_USER_ACCESS != "WEBMASTER" && !eregi(";MOD_SECURITY;", $CUR_USER_ACCESS)) { $ACCESS_TMP = "DISABLED"; } else { $ACCESS_TMP = ""; }

	$THIS_DISPLAY .= "<BR CLEAR=ALL><BR><SPAN ID=notice><font face=Arial color=darkblue><B>".lang("Please select a user data table.")."</B></font></SPAN>\n";
	$THIS_DISPLAY .= "</TD></TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP COLSPAN=2><INPUT TYPE=BUTTON $ACCESS_TMP ".$editOn." VALUE=\"".lang("Batch Authenticate Users")."\" onclick=\"navto('database_manager/auth_users.php','');\" STYLE='WIDTH: 150px;'></TD></TR>\n";
	$THIS_DISPLAY .= "</TABLE><BR><BR>\n\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Database Table Manager", "program/modules/mods_full/database_tables.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/data_table_manager-enabled.gif";
$module->heading_text = "Database Table Manager";

$intro_text = "Manage your MySQL database tables and create db table search forms.";
$module->description_text = $intro_text;

$module->good_to_go();
?>