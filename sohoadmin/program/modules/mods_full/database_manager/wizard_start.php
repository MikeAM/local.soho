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
session_cache_limiter('public');
session_start();

//include("../includes/login.php");
//include("../includes/db_connect.php");
include("../../../../includes/emulate_globals.php");
require_once("../../../includes/product_gui.php");

// Sterilize Character String Function

function sterilize_char ($sterile_var) {

	$sterile_var = stripslashes($sterile_var);
	$sterile_var = eregi_replace(";", ",", $sterile_var);
	// $sterile_var = eregi_replace(" ", "_", $sterile_var);

	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";

	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[0-9a-z_ ]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}

	$sterile_var = $tmp;
	return $sterile_var;

}

#############################################################################################
### For each step we will keep building hidden vars and passing them to the next section
### in case of the end-user presses the back button.  This way it's easier to track the
### "session" without registering the session data. (Less of a memory hog)
#############################################################################################

$HIDDEN_POST = "";
reset($HTTP_POST_VARS);
while (list($name, $value) = each($HTTP_POST_VARS)) {
	$value = stripslashes($value);			// Strip all slashes from data for HTML exekution
	if ($name != "STEP_NUM") {
		if ($name == "SEARCH_NAME") { $value = sterilize_char($value); }
		$HIDDEN_POST .= "<INPUT TYPE=HIDDEN NAME=\"$name\" VALUE=\"$value\">\n";
		${$name} = $value;
	}
}

#############################################################################################
### START HTML/JAVASCRIPT CODE
#############################################################################################
$MOD_TITLE = lang("Data-Table Search Wizard");		// Give Mod Title to System
$BG = "../shared/db_man_bg.jpg";				// Use Background Image for Wizard
$TBL_HT = "400";								// For wizard, let's make our table expand height wise
$err_show = "";

#############################################################################################
### Modify Title and Basic Error Trap for Step 1.  Require a Search Name
#############################################################################################

if ($SEARCH_NAME != "") {

	$SEARCH_NAME = strtoupper($SEARCH_NAME);
	$MOD_TITLE .= " : '$SEARCH_NAME'";

} else {

	if ($STEP_NUM == 2) {
		$err_show = "<BR><BR><FONT COLOR=RED>Please identify this search setup with a name.</FONT>";
	}
	$STEP_NUM = "";
}


if ($STEP_NUM == 3 && $TABLE_NAME == "") {
	$STEP_NUM = 2;
	$err_show = "<BR><BR><FONT COLOR=RED>You did not select a table. Please do so before continuing.</FONT>\n";
}

#############################################################################################
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

function build() {
	LOAD_LAYER.style.visibility = '';
	userOpsLayer.style.visibility = 'hidden';
}

function demo_form() {
	alert('This form is strictly to show the look and feel of the search form.\n\nIt is not operational within the wizard.');
}

//-->
</script>


<?

// =================================================================================================
//
//     #####
//        ##
//        ##
//        ##
//        ##
//        ##
//        ##
//    #########
//
// Step One: This is a new "search" setup.
// =================================================================================================

if ($STEP_NUM == "") {

	$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 1 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"wizard_start.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=2>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

   $THIS_DISPLAY .= "<h2 class=\"blue\" style=\"padding-left: 15px;\"><u>".lang("STEP")." 1/7</u>: ".lang("ASSIGN SEARCH NAME")."</h2>\n";


//	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" BGCOLOR=#EFEFEF STYLE='BORDER: 1px inset black;'>\n";
//	$THIS_DISPLAY .= "<TR>\n";
//	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE>\n";
//	$THIS_DISPLAY .= "<FONT STYLE='font-size: 11pt;'><TT><U>".lang("STEP")." 1/7</U>: ".lang("ASSIGN SEARCH NAME")."</B></TT></FONT>\n";
//	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" >\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=top class=text>\n";

		$THIS_DISPLAY .= lang("Give this search a name.")." \n";
		$THIS_DISPLAY .= lang("This will be used as an identifier in the Page Editor, and displayed to site visitors when searching").":<br/><br/> \n";
		$THIS_DISPLAY .= "<INPUT TYPE=TEXT CLASS=text NAME=SEARCH_NAME VALUE=\"$SEARCH_NAME\" STYLE='WIDTH: 200px; height: 30px; font-size: 20px;'>\n";
		$THIS_DISPLAY .= $err_show;
		$THIS_DISPLAY .= "<DIV ALIGN=RIGHT><INPUT TYPE=SUBMIT VALUE=\" Next >> \" CLASS=FormLt1></DIV>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 1 ----------------------- -->\n\n";

} // END STEP ONE

// =================================================================================================
//
//   ########
//   ##    ##
//        ##
//       ##
//      ##
//     ##
//    ##    ##
//   #########
//
// Step Two: Which data table will this search use?
// =================================================================================================

if ($STEP_NUM == "2") {

	// Get all current user defined tables
	// ------------------------------------------------------------

	$result = mysql_list_tables("$db_name");
	$i = 0;
	$CURRENT_TABLES = "     <OPTION VALUE=\"\" STYLE='COLOR: darkblue;'>Current UDT Tables...</OPTION>\n";
	while ($i < mysql_num_rows ($result)) {
		$tb_names[$i] = mysql_tablename ($result, $i);
		if (eregi("UDT_", $tb_names[$i])) {		// Only Get UDT Tables (Remember They can delete these kinds of tables (Dangerous)
			// Added for Multi-User Access Rights
			if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";$tb_names[$i];", $CUR_USER_ACCESS)) {
				$tmp_disp = $tb_names[$i];
				$CURRENT_TABLES .= "     <OPTION VALUE=\"$tb_names[$i]\">$tmp_disp</OPTION>\n";
			}
		}
		$i++;
	}

	// ------------------------------------------------------------

	$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 2 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"wizard_start.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=3>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

   $THIS_DISPLAY .= "<h2 class=\"blue\" style=\"padding-left: 15px;\"><u>".lang("STEP")." 2/7</u>: ".lang("SELECT DATA TABLE USAGE")."</h2>\n";

//	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" BGCOLOR=#EFEFEF STYLE='BORDER: 1px inset black;'>\n";
//	$THIS_DISPLAY .= "<TR>\n";
//	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE>\n";
//	$THIS_DISPLAY .= "<FONT STYLE='font-size: 11pt;'><TT><U>".lang("STEP")." 2/7</U>: ".lang("SELECT DATA TABLE USAGE")."</B></TT></FONT>\n";
//	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" >\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=top class=text>\n";

		$THIS_DISPLAY .= lang("Select the User Defined Table (UDT) that this search will utilize").":<br/><br/> \n";
		$THIS_DISPLAY .= "<SELECT NAME=\"TABLE_NAME\" CLASS=text STYLE='WIDTH: 300px; height: 30px; font-size: 15px;'>\n";
		$THIS_DISPLAY .= $CURRENT_TABLES;
		$THIS_DISPLAY .= "</SELECT>\n\n";

		$THIS_DISPLAY .= $err_show;

		$THIS_DISPLAY .= "<DIV ALIGN=RIGHT><INPUT TYPE=BUTTON VALUE=\" << ".lang("Back")." \" CLASS=FormLt1 ONCLICK=\"javascript: history.back();\">&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=SUBMIT VALUE=\" ".lang("Next")." >> \" CLASS=FormLt1></DIV>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 2 ----------------------- -->\n\n";

} // END STEP TWO


// =================================================================================================
//
//   ########
//         ##
//         ##
//      #####
//         ##
//         ##
//         ##
//    #######
//
// Step Three: Form Setup Selection
// =================================================================================================

if ($STEP_NUM == "3") {

	// Get all field data about selected use table
	// ------------------------------------------------------------

	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	for ($x=0;$x<=$numberFields;$x++) {

		$fieldname[$x] = mysql_field_name($result, $x);
		$fieldname[$x] = $fieldname[$x];

		$fieldtype[$x] = mysql_field_type($result, $x);
		$fieldtype[$x] = strtoupper($fieldtype[$x]);

		$fieldlength[$x] = mysql_field_len($result, $x);

		$meta = mysql_fetch_field($result, $x);
		$field_keyflag[$x] = $meta->primary_key;

	}

	// ------------------------------------------------------------

	$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 3 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<SCRIPT LANGUAGE=JAVASCRIPT>\n\n";
	$THIS_DISPLAY .= "     function ok_next() {\n";
	$THIS_DISPLAY .= "          S3.NXT.disabled = '';\n";
	$THIS_DISPLAY .= "     }\n\n";
	$THIS_DISPLAY .= "</SCRIPT>\n\n";

	$THIS_DISPLAY .= "<FORM NAME=S3 METHOD=POST ACTION=\"wizard_start.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=4>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

   $THIS_DISPLAY .= "<h2 class=\"blue\" style=\"padding-left: 15px;\"><u>".lang("STEP")." 3/7</u>: ".lang("CONFIGURE SEARCH FORM")."</h2>\n";

//	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" BGCOLOR=#EFEFEF STYLE='BORDER: 1px inset black;'>\n";
//	$THIS_DISPLAY .= "<TR>\n";
//	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE>\n";
//	$THIS_DISPLAY .= "<FONT STYLE='font-size: 11pt;'><TT><U>".lang("STEP")." 3/7</U>: ".lang("CONFIGURE SEARCH FORM")."</B></TT></FONT>\n";
//	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" >\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=top class=text>\n";

		$THIS_DISPLAY .= lang("Configure the search criteria by which site visitors will search")." $SEARCH_NAME.<BR><I>".lang("NOTE").": ".lang("You will be able to preview the form in the next step and make changes if you wish").".</I><BR><BR>\n";

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Keyword Search Setup
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 ALIGN=CENTER bgcolor=#EFEFEF CLASS=text WIDTH=90% STYLE='border: 1px solid #999999;'>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=MIDDLE COLSPAN=3 bgcolor=#999999>\n";
		$THIS_DISPLAY .= "<FONT COLOR=WHITE><B><U>KEYWORD SEARCH FIELDS</U>: </B></FONT><FONT COLOR=black STYLE='font-size: 7pt;'>(".lang("If you wish to utilize a keyword search, select which fields should be searched.").")</FONT>\n";
		$THIS_DISPLAY .= "</TD></TR>\n";

		$x = 0;
		while ($x <= $numberFields) {

				if ($fieldname[$x] != "AUTO_SECURITY_AUTH") {

					$THIS_DISPLAY .= "<TR>\n";
					$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text>\n";
					$THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX onclick=\"ok_next();\" id=\"".$fieldname[$x]."-t\" CLASS=text NAME=\"KEYWORD_SEARCH_$fieldname[$x]\"> <label for=\"".$fieldname[$x]."-t\">$fieldname[$x]</label> &nbsp;\n";
					$THIS_DISPLAY .= "</TD>\n";

				}

				$x++;

				if ($fieldname[$x] != "AUTO_SECURITY_AUTH") {
					$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text>\n";
					if ($x <= $numberFields) {
   					$THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX onclick=\"ok_next();\" id=\"".$fieldname[$x]."-t\" CLASS=text NAME=\"KEYWORD_SEARCH_$fieldname[$x]\"> <label for=\"".$fieldname[$x]."-t\">$fieldname[$x]</label> &nbsp;\n";
   					$THIS_DISPLAY .= "</TD>\n";

					} else {
						$THIS_DISPLAY .= "&nbsp;</TD>";
					}
				}
				$x++;

				if ($fieldname[$x] != "AUTO_SECURITY_AUTH") {
					$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text>\n";
					if ($x <= $numberFields) {
   					$THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX onclick=\"ok_next();\" id=\"".$fieldname[$x]."-t\" CLASS=text NAME=\"KEYWORD_SEARCH_$fieldname[$x]\"> <label for=\"".$fieldname[$x]."-t\">$fieldname[$x]</label> &nbsp;\n";
   					$THIS_DISPLAY .= "</TD>\n";
					} else {
						$THIS_DISPLAY .= "&nbsp;</TD>";
					}
				}
				$x++;

				$THIS_DISPLAY .= "</TR>";

		} // End While Loop

		$THIS_DISPLAY .= "</TABLE><BR><BR>\n\n";

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Drop Down Selection
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 ALIGN=CENTER bgcolor=#EFEFEF CLASS=text WIDTH=90% STYLE='border: 1px solid #999999;'>\n";
		$THIS_DISPLAY .= "<TR><TD ALIGN=LEFT VALIGN=MIDDLE COLSPAN=3 BGCOLOR=#999999>\n";
		$THIS_DISPLAY .= "<FONT COLOR=WHITE><B><U>".lang("DROP DOWN BOX SELECTION FIELDS")."</U>: </B></FONT><FONT COLOR=black STYLE='font-size: 7pt;'>(".lang("Fields selected here will display all records within as options in a drop down box.").")</FONT>\n";
		$THIS_DISPLAY .= "</TD></TR>\n";

		$x = 0;
		while ($x <= $numberFields) {

				if ($fieldname[$x] != "AUTO_SECURITY_AUTH") {

					$THIS_DISPLAY .= "<TR>\n";
					$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text>\n";
					$THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX onclick=\"ok_next();\" CLASS=text id=\"DROPDOWNBOX_$fieldname[$x]\" NAME=\"DROPDOWNBOX_$fieldname[$x]\">&nbsp;\n";
					$THIS_DISPLAY .= "<SELECT NAME=\"DROPDOWNBOX_$fieldname[$x]_SORTORDER\" CLASS=text STYLE='WIDTH: 55px; font-size: 7pt;'>\n";
					$THIS_DISPLAY .= "<OPTION VALUE=\"ASC\" STYLE='color: #999999;'>ASC</OPTION>\n<OPTION VALUE=\"DESC\" STYLE='color: #999999;'>DESC</OPTION>\n</SELECT> <label for=\"DROPDOWNBOX_$fieldname[$x]\">$fieldname[$x]</label> \n";
					$THIS_DISPLAY .= "</TD>\n";

				}

				$x++;


				if ($fieldname[$x] != "AUTO_SECURITY_AUTH") {

					$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text>\n";
					if ($x <= $numberFields) {
   					$THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX onclick=\"ok_next();\" CLASS=text id=\"DROPDOWNBOX_$fieldname[$x]\" NAME=\"DROPDOWNBOX_$fieldname[$x]\">&nbsp;\n";
   					$THIS_DISPLAY .= "<SELECT NAME=\"DROPDOWNBOX_$fieldname[$x]_SORTORDER\" CLASS=text STYLE='WIDTH: 55px; font-size: 7pt;'>\n";
   					$THIS_DISPLAY .= "<OPTION VALUE=\"ASC\" STYLE='color: #999999;'>ASC</OPTION>\n<OPTION VALUE=\"DESC\" STYLE='color: #999999;'>DESC</OPTION>\n</SELECT> <label for=\"DROPDOWNBOX_$fieldname[$x]\">$fieldname[$x]</label> \n";
					} else {
						$THIS_DISPLAY .= "&nbsp;";
					}
					$THIS_DISPLAY .= "</TD>\n";

				}

				$x++;


				if ($fieldname[$x] != "AUTO_SECURITY_AUTH") {

					$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE CLASS=text>\n";
					if ($x <= $numberFields) {
   					$THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX onclick=\"ok_next();\" CLASS=text id=\"DROPDOWNBOX_$fieldname[$x]\" NAME=\"DROPDOWNBOX_$fieldname[$x]\">&nbsp;\n";
   					$THIS_DISPLAY .= "<SELECT NAME=\"DROPDOWNBOX_$fieldname[$x]_SORTORDER\" CLASS=text STYLE='WIDTH: 55px; font-size: 7pt;'>\n";
   					$THIS_DISPLAY .= "<OPTION VALUE=\"ASC\" STYLE='color: #999999;'>ASC</OPTION>\n<OPTION VALUE=\"DESC\" STYLE='color: #999999;'>DESC</OPTION>\n</SELECT> <label for=\"DROPDOWNBOX_$fieldname[$x]\">$fieldname[$x]</label> \n";
						$THIS_DISPLAY .= "</TD>\n";
					} else {
						$THIS_DISPLAY .= "&nbsp;";
					}

				}

				$x++;

				$THIS_DISPLAY .= "</TR>";

		} // End While Loop

		$THIS_DISPLAY .= "</TABLE>\n\n";

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Special Drop Down Selections ??? Date/Days of Week/Etc.
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~





		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		$THIS_DISPLAY .= "<br/><div align=right><INPUT TYPE=BUTTON VALUE=\" << ".lang("Back")." \" CLASS=FormLt1 ONCLICK=\"javascript: history.back();\">&nbsp;&nbsp;&nbsp;&nbsp;<INPUT DISABLED TYPE=SUBMIT NAME=NXT VALUE=\" ".lang("Next")." >> \" CLASS=FormLt1>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 3 ----------------------- -->\n\n";

} // END STEP THREE

// =================================================================================================
//
//   ##    ##
//   ##    ##
//   ##    ##
//   ########
//         ##
//         ##
//         ##
//         ##
//
// Step Four: Verify Form
// =================================================================================================

if ($STEP_NUM == "4") {

	// ------------------------------------------------------------
	// Get all field data about selected use table
	// ------------------------------------------------------------

	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	$keyword_activation = 0;
	$dropdown_activation = 0;

	for ($x=0;$x<=$numberFields;$x++) {

		$fieldname[$x] = mysql_field_name($result, $x);
		$fieldname[$x] = $fieldname[$x];

		$fieldtype[$x] = mysql_field_type($result, $x);
		$fieldtype[$x] = strtoupper($fieldtype[$x]);

		$fieldlength[$x] = mysql_field_len($result, $x);

		$meta = mysql_fetch_field($result, $x);
		$field_keyflag[$x] = $meta->primary_key;

		$tmp = "KEYWORD_SEARCH_".$fieldname[$x];
		if (${$tmp} == "on") { $keyword_activation = 1; }

		$tmp = "DROPDOWNBOX_".$fieldname[$x];
		if (${$tmp} == "on") { $dropdown_activation = 1; }

	}

	// ------------------------------------------------------------

	$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 4 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"wizard_start.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=5>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

   $THIS_DISPLAY .= "<h2 class=\"blue\" style=\"padding-left: 15px;\"><u>".lang("STEP")." 4/7</u>: ".lang("VERIFY SEARCH FORM")."</h2>\n";

//	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" BGCOLOR=#EFEFEF STYLE='BORDER: 1px inset black;'>\n";
//	$THIS_DISPLAY .= "<TR>\n";
//	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE>\n";
//	$THIS_DISPLAY .= "<FONT STYLE='font-size: 11pt;'><TT><U>".lang("STEP")." 4/7</U>: ".lang("VERIFY SEARCH FORM")."</B></TT></FONT>\n";
//	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" >\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=top class=text>\n";
	$THIS_DISPLAY .= lang("This is exactly the form site visitors will see when using this search.")."<BR>".lang("Click the back button to make any changes.")."<BR><BR>\n";


	// $THIS_DISPLAY .= "\n\nKeys: $keyword_activation - Drops: $dropdown_activation<BR><BR>\n";

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Start End-User Preview Form Display
	//
	// Temporarily we will place this HTML in the USER_FORM variable,because we
	// will create a "blob" holding cell for this data to use in the final
	// include.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		// Build Keyword Display
		// ---------------------------------------

			$key_opts = "<select name=\"KEY_FIELD_SEARCH\" class=text>\n<option value=\"all\">".lang("All Fields")."</option>\n";

		$x = 0;				// Reset Field Counter
		$keydisplay = "";	// Reset Keyword Display String
		while ($x <= $numberFields) {

			$tmp_data = "KEYWORD_SEARCH_".$fieldname[$x];
			$tmp_data = ${$tmp_data};

			if ($tmp_data == "on") {

				$tmp_disp = eregi_replace("_", " ", $fieldname[$x]);
				$tmp_disp = strtolower($tmp_disp);
				$tmp_disp = ucwords($tmp_disp);
				$key_opts .= "<option value=\"$fieldname[$x]\">$tmp_disp Only</option>\n";	// Drop Down Selection build

				$keydisplay .= "$fieldname[$x], ";
			}

			$x++;

		} // End While Loop

			$key_opts .= "</select>\n";

		$tmp = strlen($keydisplay);
		$new_tmp = $tmp - 2;
		$keydisplay = substr($keydisplay, 0, $new_tmp);

		$keydisplay = eregi_replace("_", " ", $keydisplay);	// Format Keyword display string for proper viewing
		$keydisplay = strtolower($keydisplay);
		$keydisplay = ucwords($keydisplay);

		$SEARCH_COUNT = 1;

		$USER_FORM = "";
		$USER_FORM .= "<TABLE BORDER=0 CELLPADDING=8 CELLSPACING=0 CLASS=text WIDTH=600 ALIGN=CENTER STYLE='border: 1px inset black;'>\n";
		$USER_FORM .= "<TR>\n";
		$USER_FORM .= "<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=#EFEFEF CLASS=text><H3><FONT COLOR=DARKBLUE>".lang("SEARCH")." $SEARCH_NAME</FONT></H3>\n";

		if ($keyword_activation == 1) {

			$USER_FORM .= "<B>$SEARCH_COUNT. <U>".lang("Search by Keyword")."</U>: </B> <FONT COLOR=#999999>(".lang("Separate multiple keywords by spaces").")</FONT><BR><FONT STYLE='font-size: 7pt;'>[ $keydisplay ]</FONT></B><BR><BR>\n";
			$USER_FORM .= "<INPUT TYPE=TEXT NAME=\"KEY_SEARCH_FOR\" SIZE=25 CLASS=text STYLE='WIDTH: 250px; COLOR: darkblue;'>&nbsp;in&nbsp;$key_opts<BR><BR>\n";
			$SEARCH_COUNT++;

		} // End, If keywords are in use

		if ($dropdown_activation == 1) {

			$USER_FORM .= "<B>$SEARCH_COUNT. <U>".lang("Detail Search")."</U>:</B><BR><BR>\n";
			$USER_FORM .= "<TABLE WIDTH=100% CELLPADDING=2 CELLSPACING=0 BORDER=0>\n";

			$x = 0;				// Reset our field counter
			$width_count = 0;	// Place two drop down selections per row; so lets reset

			while ($x <= $numberFields) {

					if ($width_count == 2) {
						$width_count = 0;
						$USER_FORM .= "</TR>\n";
						$spacer_flag = 1;
					}


					$tmp_data = "DROPDOWNBOX_".$fieldname[$x];
					$tmp_sort = "DROPDOWNBOX_".$fieldname[$x]."_SORTORDER";
					$tmp_sort = ${$tmp_sort};

					if (${$tmp_data} == "on") {			// Has drop down been activated for this field?

						if ($width_count == 0) {
							$USER_FORM .= "<TR>";
						}

						$this_option = "<OPTION VALUE=\"\" STYLE='COLOR: #999999;'>".lang("All")."</OPTION>\n";

						$result = mysql_query("SELECT DISTINCT $fieldname[$x] FROM $TABLE_NAME ORDER BY $fieldname[$x] $tmp_sort");	// Index this field
						while ($row = mysql_fetch_array($result)) {
								$v = $row[$fieldname[$x]];
								$this_option .= "<OPTION VALUE=\"$v\">$v</OPTION>\n";
						}

						$display_fn = eregi_replace("_", " ", $fieldname[$x]);
						$display_fn = strtolower($display_fn);
						$display_fn = ucwords($display_fn);

						$USER_FORM .= "<TD ALIGN=RIGHT VALIGN=MIDDLE class=text>$display_fn:</TD><TD ALIGN=LEFT VALIGN=MIDDLE><SELECT NAME=\"DROPSEARCH_$fieldname[$x]\" CLASS=text STYLE='width: 200px;'>\n";
						$USER_FORM .= "$this_option\n";
						$USER_FORM .= "</SELECT></TD>\n";
						$width_count++;

					} // End if Drop Down activated for this field

					$x++;	// Increment our field counter

			} // End Field While Loop

			if ($width_count == 0) { $USER_FORM .= "<TD COLSPAN=4 class=text>&nbsp;</TD></TR>\n"; }
			if ($width_count == 1 && $spacer_falg == 1) { $USER_FORM .= "<TD class=text>&nbsp;</TD><TD class=text>&nbsp;&nbsp;&nbsp;&nbsp;</TD></TR>\n"; }
			if ($width_count == 1 && $spacer_flag != 1) { $USER_FORM .= "<TD class=text>&nbsp;</TD><TD width=50% class=text>&nbsp;&nbsp;&nbsp;&nbsp;</TD></TR>\n"; }
			if ($width_count == 2) { $USER_FORM .= "</TR>\n"; }

			$USER_FORM .= "</TABLE>\n";				// End Drop Down Table
			$SEARCH_COUNT++;


		} // End, If Dropdown Activation is on

		if ($keyword_activation == 1 && $dropdown_activation == 1) {

			// Show Search Detail Options
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			$USER_FORM .= "<BR><BR><B>$SEARCH_COUNT. <U>".lang("Define Search Method")."</U>:</B> \n";
			$USER_FORM .= "<SELECT NAME=\"SEARCH_BOOL\" CLASS=text STYLE='WIDTH: 200px;'>\n";
			$USER_FORM .= "     <OPTION VALUE=\"KEYONLY\" SELECTED>".lang("Keyword Only")."</OPTION>\n";
			$USER_FORM .= "     <OPTION VALUE=\"SELONLY\">".lang("Selections Only")."</OPTION>\n";
			$USER_FORM .= "     <OPTION VALUE=\"KEYANDSEL\">".lang("Keyword AND Selections")."</OPTION>\n";
			$USER_FORM .= "     <OPTION VALUE=\"KEYORSEL\">".lang("Keyword OR Selections")."</OPTION>\n";
			$USER_FORM .= "</SELECT>\n\n";

		} // End, If keyword + dropdown activation

		$USER_FORM .= "<DIV ALIGN=RIGHT><INPUT TYPE=BUTTON VALUE=\" ".lang("Search Now")." \" STYLE='font-family: Arial; font-size: 8pt; cursor: hand;' ONCLICK=\"demo_form();\"></DIV>\n\n";

		$USER_FORM .= "</TD></TR></TABLE>\n";	// End End-User Display Table

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$THIS_DISPLAY .= $USER_FORM;

	// $USER_FORM = addslashes($USER_FORM); // Make sure HTML data transports well
	// $THIS_DISPLAY .= "\n\n\n\n\n<TEXTAREA STYLE='DISPLAY: NONE;' NAME=SEARCH_HTML>$USER_FORM</TEXTAREA>\n\n\n\n\n";

	$THIS_DISPLAY .= "<br/><DIV ALIGN=RIGHT><INPUT TYPE=BUTTON VALUE=\" << ".lang("Back")." \" CLASS=FormLt1 ONCLICK=\"javascript: history.back();\">&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=SUBMIT VALUE=\" ".lang("Next")." >> \" CLASS=FormLt1>\n\n";

	$THIS_DISPLAY .= "</TD</TR></TABLE>\n";
	$THIS_DISPLAY .= "</FORM>";
	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 4 ----------------------- -->\n\n";

} // End Step Four

// =================================================================================================
//
//   ########
//   ##
//   ##
//   ########
//         ##
//         ##
//         ##
//   ########
//
// Step Five: Setup Display Results
// =================================================================================================

if ($STEP_NUM == "5") {

	// ------------------------------------------------------------
	// Get all field data about selected use table
	// ------------------------------------------------------------

	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	for ($x=0;$x<=$numberFields;$x++) {

		$fieldname[$x] = mysql_field_name($result, $x);
		$fieldname[$x] = $fieldname[$x];

		$fieldtype[$x] = mysql_field_type($result, $x);
		$fieldtype[$x] = strtoupper($fieldtype[$x]);

		$fieldlength[$x] = mysql_field_len($result, $x);

		$meta = mysql_fetch_field($result, $x);
		$field_keyflag[$x] = $meta->primary_key;

	}

	// ------------------------------------------------------------

	$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 5 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"wizard_start.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=6>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

   $THIS_DISPLAY .= "<h2 class=\"blue\" style=\"padding-left: 15px;\"><u>".lang("STEP")." 5/7</u>: ".lang("SEARCH RESULTS DISPLAY")."</h2>\n";

//	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" BGCOLOR=#EFEFEF STYLE='BORDER: 1px inset black;'>\n";
//	$THIS_DISPLAY .= "<TR>\n";
//	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE>\n";
//	$THIS_DISPLAY .= "<FONT STYLE='font-size: 11pt;'><TT><U>".lang("STEP")." 5/7</U>: ".lang("SEARCH RESULTS DISPLAY")."</B></TT></FONT>\n";
//	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" >\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<td align=\"center\" valign=top class=text>\n";
	$THIS_DISPLAY .= "<FONT COLOR=RED><B>!! IMPORTANT !!</B></FONT> <FONT COLOR=DARKBLUE>".lang("There are two steps used when displaying the results of a search.")." \n";
	$THIS_DISPLAY .= lang("The first data displayed is called the 'Initial Results', and displays the selected field data in a chart format.")." \n";
	$THIS_DISPLAY .= lang("At that point, site visitors may select to <I>View Details</I>, which displays the 'Details Page'.")." \n";
	$THIS_DISPLAY .= lang("This page shows more detailed information about the choosen record.")."</FONT><BR><BR>\n";
	$THIS_DISPLAY .= lang("Select for each field when and where it's value should be displayed during the above process").":</B><BR><BR>\n";


		$THIS_DISPLAY .= "<TABLE BORDER=1 CELLPADDING=3 CELLSPACING=0 ALIGN=CENTER WIDTH=612>\n";

		$THIS_DISPLAY .= "<TR>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE class=text BGCOLOR=DARKBLUE><FONT COLOR=WHITE><B>".lang("Field Name")."</b></TD>\n";
		$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE class=text colspan=4 BGCOLOR=DARKBLUE><FONT COLOR=WHITE><B>".lang("Display Setting")."</b></TD>\n";
		$THIS_DISPLAY .= "</TR>\n";

		$x = 0;				// Reset Field Counter

		while ($x <= $numberFields) {

			if ($BGCOLOR == "WHITE") { $BGCOLOR = "#EFEFEF"; } else { $BGCOLOR = "WHITE"; }

			if ($fieldname[$x] != "AUTO_SECURITY_AUTH") {	// Do not give the option to display the secure authentication key

				$THIS_DISPLAY .= "<TR>\n";
				$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=text BGCOLOR=$BGCOLOR>$fieldname[$x]</TD>\n";
				$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=text BGCOLOR=$BGCOLOR><INPUT TYPE=RADIO CHECKED id=\"DISPLAY_$fieldname[$x]-0\" NAME=\"DISPLAY_$fieldname[$x]\" VALUE=\"0\"><label for=\"DISPLAY_$fieldname[$x]-0\">".lang("Don't Display")."</label></TD>\n";
				$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=text BGCOLOR=$BGCOLOR><INPUT TYPE=RADIO id=\"DISPLAY_$fieldname[$x]-I\" NAME=\"DISPLAY_$fieldname[$x]\" VALUE=\"I\"><label for=\"DISPLAY_$fieldname[$x]-I\">".lang("Initial Results")."</label></TD>\n";
				$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=text BGCOLOR=$BGCOLOR><INPUT TYPE=RADIO id=\"DISPLAY_$fieldname[$x]-D\" NAME=\"DISPLAY_$fieldname[$x]\" VALUE=\"D\"><label for=\"DISPLAY_$fieldname[$x]-D\">".lang("Details Page")."</label></TD>\n";
				$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE class=text BGCOLOR=$BGCOLOR><INPUT TYPE=RADIO id=\"DISPLAY_$fieldname[$x]-B\" NAME=\"DISPLAY_$fieldname[$x]\" VALUE=\"B\"><label for=\"DISPLAY_$fieldname[$x]-B\">".lang("Display on Both")."</label></TD>\n";
				$THIS_DISPLAY .= "</TR>\n\n";

			} // Close BEFORE $x loop; otherwise; while will just hang

			$x++;

		} // End While Loop

		$THIS_DISPLAY .= "</TABLE>\n\n";



	$THIS_DISPLAY .= "<br/><DIV ALIGN=RIGHT><INPUT TYPE=BUTTON VALUE=\" << Back \" CLASS=FormLt1 ONCLICK=\"javascript: history.back();\">&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=SUBMIT VALUE=\" Next >> \" CLASS=FormLt1>\n\n";

	$THIS_DISPLAY .= "</TD</TR></TABLE>\n";
	$THIS_DISPLAY .= "</FORM>";
	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 5 ----------------------- -->\n\n";


} // End Step 5

// =================================================================================================
//
//   ########
//   ##
//   ##
//   ########
//   ##    ##
//   ##    ##
//   ##    ##
//   ########
//
// Step Six: Setup Details Page
// =================================================================================================

if ($STEP_NUM == "6") {


	######################################################
	### READ ANY .INC or .PHP FILES INTO MEMORY; AT THIS
	### POINT THERE IS NO SECURITY ON THESE FILES BECAUSE
	### THEY SIMPLY EXIST ON THE SERVER IN A STANDARD
	### PORT :80 ACCESSIBLE DIRECTORY.  IF SOMEONE WANTED
	### TO MODIFY UPLOAD FILES TO PLACE FILES INTO A
	### DATABASE; THAT WOULD SOLVE THAT PROBLEM.
	### I JUST DID NOT DO IT IN THE INITIAL DESIGN.
	###
	### WHILE WE'RE HERE, MIGHT AS WELL POPULATE THE
	### SELECTION BOX FOR CUSTOM FORM ATTACHMENT AS WELL.
	######################################################

	$inc_file = "     <OPTION VALUE=\"\">N/A</OPTION>\n";
// Bugz 508
	$DBSEARCH_BASE_LIST = "     <OPTION  VALUE=standard SELECTED>".lang("Standard (Default)")."</OPTION>\n";
// Bugz 508

	$count = 0;
	$directory = "$doc_root/media";
	if (is_dir($directory)) {
	$handle = opendir("$directory");
		while ($files = readdir($handle)) {
			if (strlen($files) > 2) {
				if (eregi(".inc", $files) || eregi(".php", $files)) {
					$count++;
					$tmp = "$directory/$files";
					$tmp_space = filesize($tmp);
					$tmp_srt = ucwords($files);
					$site_file[$count] = $tmp_srt . "~~~media~~~$tmp_space~~~" . $files;
				}
			}
		}
	closedir($handle);
	}

	if ($count > 1) { sort($site_file); };
	$file_count = count($site_file);

	for ($x=0;$x<=$file_count;$x++) {

			$tmp = split("~~~", $site_file[$x]);
			$filename = $tmp[3];
			$filesize = $tmp[2];
			$filedir = $tmp[1];

			if (strlen($filename) > 2) {

				// -----------------------------------------
				// Calculate "Human" Filesize for display
				// -----------------------------------------

				if ($filesize >= 1048576) {
					$filesize = round($filesize/1048576*100)/100;
					$filesize = $filesize . "&nbsp;Mb";
				 } elseif ($filesize >= 1024) {
					$filesize = round($filesize/1024*100)/100;
					$filesize = $filesize . "&nbsp;K";
				 } else {
					$filesize = $filesize . "&nbsp;Bytes";
				 }

				$inc_file .= "     <OPTION VALUE=\"$filename\">$filename [$filesize]</OPTION>\n";
// Bugz 508
				if (eregi("dbsearch_",$filename) && eregi(".php",$filename)) {
					$DBSEARCH_BASE_OPTION = eregi_replace("dbsearch_","",$filename);
					$DBSEARCH_BASE_OPTION = eregi_replace(".php","",$DBSEARCH_BASE_OPTION);
					$DBSEARCH_BASE_OPTION = eregi_replace("_"," ",$DBSEARCH_BASE_OPTION);
					$DBSEARCH_BASE_OPTION = ucwords($DBSEARCH_BASE_OPTION);
					$DBSEARCH_BASE_LIST .= "     <OPTION  VALUE=\"$filename\">$DBSEARCH_BASE_OPTION</OPTION>\n";
				}
// Bugz 508
			}

	}

	####################################################################

	// ----------------------------------------------------------------------
	// Pull Security Codes [groups] from data table for selection
	// ----------------------------------------------------------------------

	$SEC_CODES = "<OPTION VALUE=\"Public\" SELECTED>".lang("Public")."</OPTION>\n";

	$result = mysql_query("SELECT * FROM sec_codes ORDER BY security_code");
	$num_groups = mysql_num_rows($result);
	if ($num_groups > 0) {
		while($GROUP = mysql_fetch_array($result)) {
			$SEC_CODES .= "     <OPTION VALUE=\"$GROUP[security_code]\">$GROUP[security_code]</OPTION>\n";
		}
	}

	// ----------------------------------------------------------------------

	$THIS_DISPLAY .= "\n\n<!-- ----------------- START STEP 6 ---------------------- -->\n\n";

	$THIS_DISPLAY .= "<FORM NAME=tmpform METHOD=POST ACTION=\"wizard_start.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=7>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

   $THIS_DISPLAY .= "<h2 class=\"blue\" style=\"padding-left: 15px;\"><u>".lang("STEP")." 6/7</u>: ".lang("DETAIL VIEW SETUP AND SECURITY")."</h2>\n";

//	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" BGCOLOR=#EFEFEF STYLE='BORDER: 1px inset black;'>\n";
//	$THIS_DISPLAY .= "<TR>\n";
//	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=MIDDLE>\n";
//	$THIS_DISPLAY .= "<FONT STYLE='font-size: 11pt;'><TT><U>".lang("STEP")." 6/7</U>: ".lang("DETAIL VIEW SETUP AND SECURITY")."</B></TT></FONT>\n";
//	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 CLASS=text width=\"100%\" >\n";
	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<td align=\"left\" valign=top class=text>\n";

		$THIS_DISPLAY .= "<BR>1. <B>".lang("Select the display format (look and feel) of the 'Details Page'")."</B>: <BR>\n";
		$THIS_DISPLAY .= "&nbsp;&nbsp;<INPUT TYPE=RADIO NAME=\"DETAILS_DISPLAY\" id=\"DETAILS_DISPLAY-S\" VALUE=\"S\" CHECKED ONCLICK=\"javascript: tmpform.DETAILS_DISPLAY_INC.disabled = 'true';  tmpform.DETAILS_DISPLAY_INC.options.value = '';\"> <label for=\"DETAILS_DISPLAY-S\">".lang("Standard (Default)")."</label><BR>\n";
		$THIS_DISPLAY .= "&nbsp;&nbsp;<INPUT TYPE=RADIO NAME=\"DETAILS_DISPLAY\" id=\"DETAILS_DISPLAY-C\" VALUE=\"C\" ONCLICK=\"javascript: tmpform.DETAILS_DISPLAY_INC.disabled = '';\"> <label for=\"DETAILS_DISPLAY-C\">".lang("Custom PHP Include")."</label> <TT>--></TT> \n";
		$THIS_DISPLAY .= "<SELECT NAME=\"DETAILS_DISPLAY_INC\" CLASS=text DISABLED STYLE='width: 250px;'>$inc_file</SELECT>\n";
// Bugz 508
		$THIS_DISPLAY .= "<BR><BR><BR><BR>2. <B>".lang("Select your prefered search method")."</B>\n";
		$THIS_DISPLAY .= "<BR>&nbsp;&nbsp;&nbsp;&nbsp;<SELECT NAME=DBSEARCH_BASE  CLASS=text STYLE='width: 200px;'>\n$DBSEARCH_BASE_LIST</SELECT>\n";
// Bugz 508
		$THIS_DISPLAY .= "<BR><BR><BR><BR>3. <B>".lang("Select a security code (group) required to access this search")."</B>:  <FONT COLOR=#999999>(".lang("Public is Default").")</FONT>\n";
		$THIS_DISPLAY .= "<BR>&nbsp;&nbsp;&nbsp;&nbsp;<SELECT NAME=\"SEARCH_SECURITY_CODE\" CLASS=text STYLE='width: 110px;'>$SEC_CODES</SELECT>\n";


	$THIS_DISPLAY .= "<br/><DIV ALIGN=CENTER><INPUT TYPE=BUTTON VALUE=\" << Back \" CLASS=FormLt1 ONCLICK=\"javascript: history.back();\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE=SUBMIT VALUE=\" ".lang("Build Search Now")." >> \" CLASS=FormLt1 onclick=\"build();\" STYLE='BACKGROUND: DARKGREEN;'></DIV>\n\n";

	$THIS_DISPLAY .= "</TD</TR></TABLE>\n";
	$THIS_DISPLAY .= "</FORM>";
	$THIS_DISPLAY .= "\n\n<!-- ------------------ END STEP 6 ----------------------- -->\n\n";


} // End Step 6

// =================================================================================================
//
//   ########
//         ##
//         ##
//        ##
//       ##
//      ##
//      ##
//      ##
//
// Step Seven: BUILD SEARCH INCLUDE NOW
// =================================================================================================

if ($STEP_NUM == "7") {

	// Setup Final Include Filename for Output
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$SEARCH_NAME = eregi_replace(" ", "_", $SEARCH_NAME);
	$SEARCH_NAME = strtolower($SEARCH_NAME);

	$INC_FILENAME = "$doc_root/media/udt-search_".$SEARCH_NAME.".inc";


	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Read Template Include File into memory
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// Bugz 508
	if ($DBSEARCH_BASE == "standard") {$filename = "shared/dbsearch_base.php";
	} else {$filename = "$doc_root/media/".$DBSEARCH_BASE;}
// Bugz 508
	$file = fopen("$filename", "r");
		$tmp = fread($file,filesize($filename));
		$tmp_line = split("\n", $tmp);
		$numLines = count($tmp_line);
	fclose($file);

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Insert Variables based on wizard setup into template and write new
	// include to output file defined above.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$OUTPUT = "";

	for ($x=0;$x<=$numLines;$x++) {

			if (eregi("#-WIZARD_VARS-#", $tmp_line[$x])) {	// Is this where we add our wizard vars?

				$tmp_line[$x] = "\n";

				reset($HTTP_POST_VARS);
				while (list($name, $value) = each($HTTP_POST_VARS)) {
					$value = stripslashes($value);			// Strip all slashes from data for HTML exekution
					if ($name != "STEP_NUM" && $name != "KEY_SEARCH_FOR" && !eregi("DROPSEARCH_", $name) && $name != "SEARCH_BOOL") {
						$tmp_line[$x] .= "     $$name = \"$value\";\n";
					}
				} // End While Loop

			} // End wizard var line check

			$OUTPUT .= $tmp_line[$x] . "\n";			// Build Output HTML line

	} // End For Loop

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// Write new Include File to media folder
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$file = fopen("$INC_FILENAME", "w");
		fwrite($file, "$OUTPUT");
	fclose($file);

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$THIS_DISPLAY .= "<BR><H2><FONT COLOR=DARKBLUE>".lang("Search Creation Complete")."!</FONT></H2>\n\n";
	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"../download_data.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT CLASS=FormLt1 VALUE=\" ".lang("Database Menu")." \"></FORM>\n\n";
	$THIS_DISPLAY .= "<DIV STYLE='font-size: 10pt;'>\n";
	$THIS_DISPLAY .= lang("Use the 'Searchabe Database' object in the page editor to place your search on a site page.")."\n";
	$THIS_DISPLAY .= "</DIV>\n";

} // End Step 7

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Create a custom search form that visitors can use to search records in a particular database table. Example usage: letting club/organization members search through a member roster.");
//$instructions .= lang("<b>Settings</b> : Change your logo, slogan, business information.<br/>");
//$instructions .= lang("<b>Template Upload</b> : Upload your own custom template.");

$module = new smt_module($module_html);
$module->meta_title = "Create Table Search";
$module->add_breadcrumb_link("Database Tables", "program/modules/mods_full/download_data.php");
$module->add_breadcrumb_link("Create a Table Search Form", "program/modules/mods_full/database_manager/wizard_start.php");
$module->icon_img = "program/includes/images/data-tables-icon-med.png";
$module->heading_text = "Create a Table Search Form";
$module->description_text = $instructions;
$module->good_to_go();

?>