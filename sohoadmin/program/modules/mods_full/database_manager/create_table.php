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

session_cache_limiter('none');
session_start();
require_once('../../../includes/product_gui.php');

#######################################################
### START HTML/JAVASCRIPT CODE			   			###
#######################################################

$MOD_TITLE = lang("Table Manager: Create New Table");

// Pre-build Mouseover script for new v4.7 buttons (because nobody likes side-scrolling)
$editOn = "class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\"";
$saveOn = "class=\"btn_save\" onMouseover=\"this.className='btn_saveon';\" onMouseout=\"this.className='btn_save';\"";
$buildOn = "class=\"btn_build\" onMouseover=\"this.className='btn_buildon';\" onMouseout=\"this.className='btn_build';\"";
$deleteOn = "class=\"btn_delete\" onMouseover=\"this.className='btn_deleteon';\" onMouseout=\"this.className='btn_delete';\"";

// --------------------------------------------------------------
// Establish a sterilize routine to kill any non-alphanumeric
// characters in a string. -- When working with mySQL and Linux
// you must be very aware of case sensitivity issues and
// characters used in database tables.  Since most users
// don't have the slightest idea what they are doing, we are
// making this routine default to some pretty "stupid" things
// but it insures that the novice can't screw it up. [update]
// --------------------------------------------------------------

function sterilize_char ($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$sterile_var = eregi_replace(" ", "_", $sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[0-9a-z_]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}
	$sterile_var = $tmp;
	return $sterile_var;
}

if ($NEW_TABLE_NAME == "" && $ACTION != "") { header("Location: create_table.php?ACTION=&err=noname&=SID"); exit; }

// -------------------------------------------------------------------------------
// 3. Process Final creation routine. Must be at the top of the script because
// if we are successful (and you know we will be), the table is created and
// we will "header" send the user back to the main database menu
// -------------------------------------------------------------------------------

if ($ACTION == "3") {			// BUILD THIS TABLE AND GET OUT OF HERE!

	$dup_auto_flag = 0;			// Make sure they did not type in our auto image field
	$dup_auto_secure = 0;

	$SQL_BUILD = "";
	$SQL_BUILD .= "CREATE TABLE $NEW_TABLE_NAME (PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";

	for ($x=1;$x<=$NEW_TABLE_NUM_FIELDS;$x++) {

		$t_fn = "FIELD_NAME" . $x; $this_field = ${$t_fn};
			//$this_field = strtoupper($this_field);
			$this_field = sterilize_char($this_field);
		$t_ft = "FIELD_TYPE" . $x; $this_type = ${$t_ft};
		$t_fl = "FIELD_LENGTH" . $x; $this_length = ${$t_fl};
		$t_t = "DEFAULT" . $x; $this_default = ${$t_t};

		//stop auto adding auto_image and auto_security_auto fields
		$dup_auto_flag = 1;
		$dup_auto_secure = 1;

		if ($this_field == "AUTO_IMAGE") { $dup_auto_flag = 1; }		// They already created an image field we can recognize
		if ($this_field == "AUTO_SECURITY_AUTH") { $dup_auto_secure = 1; }
		if ($this_length != "") { $this_length = "($this_length)"; }
		if (strlen($this_default) > 0) { $this_default = "DEFAULT '$this_default' NOT NULL"; }

		$SQL_BUILD .= "$this_field $this_type".$this_length." $this_default, ";

	}

	// Add extra field for image data so that our "Wizard" knows where to look
	// for any image associated with records in this data table.
	// (Always called "AUTO_IMAGE")

	if ($dup_auto_flag == 0) {					// Place our automatic field in this table now
		$SQL_BUILD .= "AUTO_IMAGE CHAR(100),";
	}

	if ($dup_auto_secure == 0) {
		$SQL_BUILD .= "AUTO_SECURITY_AUTH CHAR(255)";
	} else {									// Pull the last comma out and keep going
		$tmp_l = strlen($SQL_BUILD);
		$tmp_end = $tmp_l - 2;
		$tmp_s = substr($SQL_BUILD, 0, $tmp_end);
		$SQL_BUILD = $tmp_s;
	} // End auto_image build

	$SQL_BUILD .= ")";

	if (!mysql_db_query("$db_name", "$SQL_BUILD")) {

		// 2003-09-08 Bugzilla #3 updated fix
		$THIS_DISPLAY = "<HTML><HEAD></HEAD><BODY BGCOLOR=RED><BR><BR><TABLE BORDER=0 CELLPADDING=30 CELLSPACING=0 WIDTH=70% ALIGN=CENTER BGCOLOR=WHITE STYLE='border: 2px solid black;'><TR><TD align=center valign=top>";
			$THIS_DISPLAY .= "<FONT COLOR=RED><TT>";
			$THIS_DISPLAY .= lang("Error")." ". mysql_errno() . ":<BR>" . mysql_error(). "</font><BR><BR>\n";
			$THIS_DISPLAY .= "<FONT COLOR=#666666><TT>$SQL_BUILD</TT></FONT><BR><BR>";
			$THIS_DISPLAY .= "<B><font face=Tahoma><a href='javascript: history.back();'><< ".lang("BACK TO TABLE BUILD")." <<</a></font>";
		$THIS_DISPLAY .= "</td></tr></table></BODY></HTML>\n";

		echo $THIS_DISPLAY;
		exit;

	} else {
		$_SESSION['recent_tables'][strtolower($NEW_TABLE_NAME)] = $NEW_TABLE_NAME; // strtolower necc for ksort()
		header("Location: ../download_data.php?=SID");
		exit;
	}

}

// -------------------------------------------------------------------------------

ob_start();

// -------------------------------------------------------------------------------
// 1. Get Table Name and Number of Fields it Should Contain
// -------------------------------------------------------------------------------

IF ($ACTION == "") {

	$THIS_DISPLAY .= "<form method=post action=\"create_table.php\">\n";
	$THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"2\">\n\n";

	$THIS_DISPLAY .= "<table width=\"100%\" border=0 cellpadding=5 cellspacing=0 class=\"feature_sub\"><tr>\n";
	$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\">\n";

	$THIS_DISPLAY .= "<B>".lang("1. What is the name for this table")."?</B> <FONT COLOR=#666666>Maximum characters of 25.\n";
	$THIS_DISPLAY .= lang("NOTE: Do not use numbers or spaces in names; these are invalid")."\n";
	$THIS_DISPLAY .= lang("SQL table names. You may use underscores to represent spaces.")."</FONT><br/><br/>\n\n";
	$THIS_DISPLAY .= "<div align=\"center\" style=\"font-size: 14px; font-weight: bold;\">".lang("Table Name").": \n";
	$THIS_DISPLAY .= "<span class=\"unbold gray\">UDT_</span><input type=text maxlength=25 name=\"NEW_TABLE_NAME\" class=text style='width: 300px; height: 30px;font-size: 18px;font-weight: normal;'></div>\n";

	if ($err == "noname") {
		$THIS_DISPLAY .= "<div align=center><font color=red>*".lang("Invalid Table Name")."</font></div>\n";
	}

	$THIS_DISPLAY .= "<br/><br/><b>".lang("2. How many fields will this table contain")."?</b> \n";
	$THIS_DISPLAY .= "<select name=\"NEW_TABLE_NUM_FIELDS\" class=text style='width: 75px;'>\n";

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Let's limit the number of fields to keep idiots from blowing themselves up.
	// Who the hell's going to build a 150 field table anyway but stranger things
	// have happened, recently!
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	for ($x=1;$x<=150;$x++) {
		$THIS_DISPLAY .= "     <option value=\"$x\">$x</option>\n";
	}

	$THIS_DISPLAY .= "</select>\n\n<br/><br/>";
	$THIS_DISPLAY .= "<div align=right><input type=submit value=\" NEXT >> \" ".$editOn." style='width: 75px;'></div>\n";


	$THIS_DISPLAY .= "</td></tr></table></form>\n\n";

} // End No Action (New Setup)

// -------------------------------------------------------------------------------
// 2. Process ACTION item 2 (Verify Table Name and Get Field Info
// -------------------------------------------------------------------------------

if ($ACTION == "2") {
	$NEW_TABLE_NAME = sterilize_char($_REQUEST['NEW_TABLE_NAME']);
	$NEW_TABLE_NAME = "UDT_" . $NEW_TABLE_NAME;

	// Build Javascript Error Trap Routines for each form field automatically
	// -----------------------------------------------------------------------

	$THIS_DISPLAY .= "\n\n<SCRIPT LANGUAGE=Javascript>\n\n";

	$THIS_DISPLAY .= "     function create_table() {\n\n";
	$THIS_DISPLAY .= "          var err=0;\n";

	for ($x=1;$x<=$NEW_TABLE_NUM_FIELDS;$x++) {

		$THIS_DISPLAY .= "          if(document.tablelayout.FIELD_NAME$x.value == '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(document.tablelayout.FIELD_TYPE$x.options.value == 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY' && tablelayout.FIELD_LENGTH$x.value != '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(document.tablelayout.FIELD_TYPE$x.options.value == 'BLOB' && tablelayout.FIELD_LENGTH$x.value != '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(document.tablelayout.FIELD_TYPE$x.options.value == 'LONGBLOB' && tablelayout.FIELD_LENGTH$x.value != '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(document.tablelayout.FIELD_TYPE$x.options.value == 'DATE' && tablelayout.FIELD_LENGTH$x.value != '') { err=$x; }\n";
		$THIS_DISPLAY .= "          if(document.tablelayout.FIELD_TYPE$x.options.value == 'TIME' && tablelayout.FIELD_LENGTH$x.value != '') { err=$x; }\n\n";
		$THIS_DISPLAY .= "          if(document.tablelayout.FIELD_LENGTH$x.value > 255) { err=$x; }\n";
	}

	$THIS_DISPLAY .= "          if(err==0) { document.tablelayout.submit(); }\n\n";
	$THIS_DISPLAY .= "          if(err>0) { alert('".lang("The data you have entered is not formated properly")."\\n".lang("in order to create your table. Please check your")."\\n".lang("setup and try again.")."\\n\\n".lang("NOTE").":\\n".lang("The last error calculation occurred on line item")." ['+err+']'); }\n\n";
	$THIS_DISPLAY .= "     } // End Create Table Function\n\n";
	$THIS_DISPLAY .= "</SCRIPT>\n\n\n";

	// -----------------------------------------------------------------------

	$THIS_DISPLAY .= "<FORM NAME=tablelayout METHOD=POST ACTION=\"create_table.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"ACTION\" VALUE=\"3\">\n\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"NEW_TABLE_NAME\" VALUE=\"$NEW_TABLE_NAME\">\n\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"NEW_TABLE_NUM_FIELDS\" VALUE=\"$NEW_TABLE_NUM_FIELDS\">\n\n";

	$THIS_DISPLAY .= "<table border=0 cellpadding=5 cellspacing=0 class=\"text\" width=\"100%\">\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE>\n";
	$THIS_DISPLAY .= "<DIV ALIGN=LEFT><FONT STYLE='font-family: Arial; font-size: 9pt;'><B>".lang("Create Table").": \"<FONT COLOR=MAROON>$NEW_TABLE_NAME</FONT>\".</B></FONT><BR>\n";
		$THIS_DISPLAY .= "<FONT COLOR=#666666 STYLE='font-size: 8pt;'>".lang("NOTE").": ".lang("Do not use numbers or spaces in names; these are invalid SQL field names.")." \n";
		$THIS_DISPLAY .= lang("You may use underscores(_) to represent spaces.");
		$THIS_DISPLAY .= "<br>".lang("Novices who are unsure about what some of these options mean, simply input your field names leaving the default selection as is.")." \n";
		$THIS_DISPLAY .= lang("This will insure proper operation.")." ".lang("By default, a Primary Key field and Image field will also be added automatically to your table.")."</FONT>\n\n";
	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=allBorder bgcolor=#EFEFEF width=\"100%\">\n";

	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE class=\"fsub_title\">&nbsp;</TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE class=\"fsub_title\">".lang("Field Name")."</TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE class=\"fsub_title\">".lang("Field Type")."</TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE class=\"fsub_title\">".lang("Field Length")."</TD>\n";
	$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE class=\"fsub_title\">".lang("Default Value")."</TD>\n";
	$THIS_DISPLAY .= "</TR>\n";

		$TYPE_OPTS = "<OPTION VALUE=\"INT\">INT</OPTION>\n";
		$TYPE_OPTS .= "<OPTION VALUE=\"DATE\">DATE</OPTION>\n";
		$TYPE_OPTS .= "<OPTION VALUE=\"TIME\">TIME</OPTION>\n";
		$TYPE_OPTS .= "<OPTION VALUE=\"CHAR\" SELECTED>CHAR</OPTION>\n";
		//$TYPE_OPTS .= "<OPTION VALUE=\"VARCHAR\">VARCHAR</OPTION>\n";
		$TYPE_OPTS .= "<OPTION VALUE=\"BLOB\">BLOB</OPTION>\n";
		// $TYPE_OPTS .= "<OPTION VALUE=\"LONGBLOB\">LONGBLOB</OPTION>\n";
		// $TYPE_OPTS .= "<OPTION VALUE=\"INT NOT NULL AUTO_INCREMENT PRIMARY KEY\">INT NOT NULL AUTO_INCREMENT PRIMARY KEY</OPTION>\n";

		$BG_COLOR = "#EFEFEF";

	for ($x=1;$x<=$NEW_TABLE_NUM_FIELDS;$x++) {

		if ($BG_COLOR == "#EFEFEF") { $BG_COLOR = "WHITE"; } else { $BG_COLOR = "#EFEFEF"; }	// Setup for alternating row colors

		$THIS_DISPLAY .= "<TR>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$BG_COLOR>$x</TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$BG_COLOR><INPUT TYPE=TEXT NAME=\"FIELD_NAME$x\" CLASS=text STYLE='width: 150px;'></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$BG_COLOR><SELECT NAME=\"FIELD_TYPE$x\" CLASS=text>$TYPE_OPTS</SELECT></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$BG_COLOR><INPUT TYPE=TEXT NAME=\"FIELD_LENGTH$x\" VALUE=\"255\" MAXLENGTH=3 CLASS=text STYLE='width: 50px;'></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE BGCOLOR=$BG_COLOR><INPUT TYPE=TEXT NAME=\"DEFAULT$x\" CLASS=text STYLE='width: 100px;'></TD>\n";
		$THIS_DISPLAY .= "</TR>\n";

	}

	$THIS_DISPLAY .= "</TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\">\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=MIDDLE>\n";
	$THIS_DISPLAY .= "<button type=\"button\" class=\"greenButton\" onclick=\"create_table();\"><span><span>".lang("Create Table")."</span></span></button>&nbsp;&nbsp;\n";
	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>\n";

} // End Step 2

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Create a new database table that you can import records into or use for whatever. If you're creating a database table you're probably an advanced enough user to know what you want to do with it.");

$module = new smt_module($module_html);
$module->meta_title = "Create Database Table";
$module->add_breadcrumb_link("Database Tables", "program/modules/mods_full/download_data.php");
$module->add_breadcrumb_link("Create Database Table", "program/modules/mods_full/database_manager/create_table.php");
$module->icon_img = "program/includes/images/data-tables-icon-med.png";
$module->heading_text = "Create Database Table";
$module->description_text = $instructions;
$module->good_to_go();

?>