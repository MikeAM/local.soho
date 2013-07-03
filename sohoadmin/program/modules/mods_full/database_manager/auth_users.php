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
require_once("../../../includes/product_gui.php");
#######################################################
### START HTML/JAVASCRIPT CODE			            ###
#######################################################

$MOD_TITLE = lang("Authenticate Users : Add Authorized Users via Data Table");

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
		$HIDDEN_POST .= "<INPUT TYPE=HIDDEN NAME=\"$name\" VALUE=\"$value\">\n";
	}
}

#############################################################################################
### BUILD ERROR TRAP IN CASE ALL REQUIRED FIELD NAMES WHERE NOT SELECTED FOR THE AUTH
### INPUT SCREEN
#############################################################################################

if ($STEP_NUM == "3") {
	$err = 0;
	if ($N1 == "NULL") { $err = 1; }
	// if ($N2 == "NULL") { $err = 1; }
	if ($EMAIL == "NULL") { $err = 1; }
	if ($USERN == "NULL") { $err = 1; }
	if ($PASSW == "NULL") { $err = 1; }

	if ($err == 1) { $STEP_NUM = 2; $err_report = "<div align=center><FONT COLOR=RED SIZE=3><TT>".lang("You must select a field name for all red selection boxes.")."<BR>".lang("The second selection under 'user/company full name' is optional.")."</TT></FONT></div><BR>\n"; }
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

function process() {
	SV2_showHideLayers('LOAD_LAYER','','show');
	SV2_showHideLayers('SELECT_LAYER','','hide');
}

//-->
</script>

<DIV ID="LOAD_LAYER" style="position:absolute; left:0px; top:0px; width:100%; height:98%; z-index:100; border: 2px none #000000; visibility: hidden; overflow: hidden">
<table border=0 cellpadding=0 width=100% height=100% bgcolor=WHITE>
    <tr>
      <td align=center valign=middle class=text>
		<img src="batch_auth.gif" width=156 height=30 border=0><BR>
		<FONT COLOR=#999999><? echo lang("This may take a few seconds..."); ?></FONT>
      </td>
    </tr>
  </table>
</DIV>

<?

###############################################################################################
### ERROR TRAP :: MAKE SURE THAT "sec_users" TABLE EXISTS
### BEFORE STARTING, OTHERWISE NO IMPORT WILL TAKE PLACE
###############################################################################################

if ($STEP_NUM == "") {

	$pass = 0;
	$result = soho_list_tables();
	$i = 0;
	while ($i < mysql_num_rows ($result)) {
		$tb_names[$i] = mysql_tablename ($result, $i);
		if ($tb_names[$i] == "sec_users") { $pass = 1; }
		$i++;
	}

	if ($pass == 1) {	// Table exists for users; are there any sec codes created?

		$pass = 0;		// Reset var for this check

		$result = mysql_query("SELECT * FROM sec_codes");
		$tmp = mysql_num_rows($result);
		if ($tmp > 0) { $pass = 1; }

	}

	if ($pass == 0) {

		$THIS_DISPLAY .= "<CENTER><H4><FONT COLOR=RED>".lang("CAN NOT AUTHENTICATE USERS VIA TABLE")."</FONT></H4>\n";
		$THIS_DISPLAY .= lang("This would indicate that you have not set-up a security code (group) OR")."<BR>\n";
		$THIS_DISPLAY .= lang("you have not created at least (1) authorized user.")."<BR><BR>\n";
		$THIS_DISPLAY .= lang("You will need to do these things before adding authenticated users via a table dump.")."\n\n";

		$STEP_NUM = "5000000";

	}

} // End Error Trap

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
// =================================================================================================

if ($STEP_NUM == "") {

	// Read all UDT's into selection box variable for selection by client

	$result = soho_list_tables();
	$i = 0;
	$CURRENT_TABLES = "     <OPTION VALUE=\"\" STYLE='COLOR: darkblue;'>".lang("Current UDT Tables...")."</OPTION>\n";
	while ($i < mysql_num_rows ($result)) {
		$tb_names[$i] = mysql_tablename ($result, $i);
		if (eregi("UDT_", $tb_names[$i]) && !eregi("udt_content_search_replace", $tb_names[$i])) {		// Only Get UDT Tables (Remember They can delete these kinds of tables (Dangerous)
			// Added for Multi-User Access Rights
			if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";$tb_names[$i];", $CUR_USER_ACCESS)) {
				$CURRENT_TABLES .= "     <OPTION VALUE=\"$tb_names[$i]\">$tb_names[$i]</OPTION>\n";
			}
		}
		$i++;
	}

	// Start Screen Display...

	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"auth_users.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=2>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 WIDTH=\"100%\" class=\"feature_sub\">\n";
	$THIS_DISPLAY .= " <TR>\n";
	$THIS_DISPLAY .= "  <TD ALIGN=LEFT VALIGN=MIDDLE class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "   ".lang("STEP")." 1/3: ".lang("SELECT DATA TABLE USAGE")."\n";
	$THIS_DISPLAY .= "  </TD>\n";
	$THIS_DISPLAY .= " </TR>\n";

	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=WHITE CLASS=text>\n";

		$THIS_DISPLAY .= lang("Select the User Defined Table (UDT) that you wish to use as your authenticated user data:")." \n";
		$THIS_DISPLAY .= "<SELECT NAME=\"TABLE_NAME\" CLASS=text STYLE='WIDTH: 200px;'>\n";
		$THIS_DISPLAY .= $CURRENT_TABLES;
		$THIS_DISPLAY .= "</SELECT>\n\n";

		$THIS_DISPLAY .= "<BR><BR><BR><BR><DIV ALIGN=RIGHT><INPUT TYPE=SUBMIT VALUE=\" ".lang("Next")." >> \" ".$btn_edit."></DIV>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

} // END STEP ONE

// =================================================================================================
//
//    ######
//   ##    ##
//        ##
//       ##
//      ##
//     ##
//    ##   ##
//    #######
//
// =================================================================================================

if ($STEP_NUM == "2") {

	// Read All Field Names for Selected Table and place in drop down box
	// variable for quick display

	$FIELD_OPTIONS = "<OPTION VALUE=\"NULL\" STYLE='COLOR: RED;'>".lang("Select Field Name")."...</OPTION>\n";

	$result = mysql_query("SELECT * FROM $TABLE_NAME");
	$numrows = mysql_num_rows($result);
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	for ($x=0;$x<=$numberFields;$x++) {
		$fieldname = mysql_field_name($result, $x);
		if ($fieldname != "") {
			$FIELD_OPTIONS .= "<OPTION VALUE=\"$fieldname\">$fieldname</OPTION>\n";
		}
	}

	// Start screen display now...

	$THIS_DISPLAY .= "<DIV ID=\"SELECT_LAYER\">\n";

	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"auth_users.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=STEP_NUM VALUE=3>\n";
	$THIS_DISPLAY .= $HIDDEN_POST;

	$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=10 CELLSPACING=0 WIDTH=\"100%\" class=\"feature_sub\">\n";
	$THIS_DISPLAY .= " <TR>\n";
	$THIS_DISPLAY .= "  <TD ALIGN=LEFT VALIGN=MIDDLE class=\"fsub_title\">\n";
	$THIS_DISPLAY .= "   ".lang("STEP")." 2/3: ".lang("CONFIGURE AUTHENTICATION DATA")." (".lang("AUTHORIZE")." $numrows ".lang("USERS").")\n";
	$THIS_DISPLAY .= "  </TD>\n";
	$THIS_DISPLAY .= " </TR>\n";

	$THIS_DISPLAY .= "<TR>\n";
	$THIS_DISPLAY .= "<TD ALIGN=\"center\" VALIGN=TOP BGCOLOR=WHITE CLASS=text>\n";

		$THIS_DISPLAY .= lang("For each field needed to register an authenticated user, match the field name in ")."'$TABLE_NAME' ".lang("to the required authenticated user fields.")."\n";
		$THIS_DISPLAY .= "<BR><BR>$err_report\n";

		ob_start();
			include("includes/auth_user_form.inc");
			$THIS_DISPLAY .= ob_get_contents();
		ob_end_clean();

		$THIS_DISPLAY .= "<BR><BR><DIV ALIGN=RIGHT><INPUT TYPE=SUBMIT VALUE=\" ".lang("Next")." >> \" ".$btn_edit." onclick=\"process();\"></DIV>\n\n";

	$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

	$THIS_DISPLAY .= "</FORM>";

	$THIS_DISPLAY .= "</DIV>\n";

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
// =================================================================================================

if ($STEP_NUM == "3") {

	// First Let's Build Email Check var so that we don't duplicate users
	// within the sec_users table

	$EMAIL_CHK = "";

	$result = mysql_query("SELECT OWNER_EMAIL FROM sec_users");

	$t = mysql_num_rows($result);

	if ($t > 0) {
		while ($row = mysql_fetch_array($result)) {
			$EMAIL_CHK .= $row[OWNER_EMAIL] . ";";
		}
	} else {
		$EMAIL_CHK = "~~~~~~~~~~;~~~~~~~~~";
	}

	// Pull Value Data from Choosen Table ($TABLE_NAME);

	$SQL_QUERY = "SELECT $N1, $N2, $EMAIL, $USERN, $PASSW FROM $TABLE_NAME";

	// Set "non-changing" variables now for use with Insert routine

	$this_expire = $EXP_YEAR."-".$EXP_MONTH."-".$EXP_DAY;
	$this_redirect = $REDIRECT_PAGE;

	$this_groups = "";

	for ($x=0;$x<=9;$x++) {
		$tmp = "SEC_GROUP".$x;
		if (${$tmp} != "") { $this_groups .= ${$tmp} . ";"; }
	}

	// Start looping through table data and inserting "new users" into
	// the sec_users table

	$added_user = 0;

	$result = mysql_query("$SQL_QUERY");
	while ($data = mysql_fetch_array($result)) {

		$SQL_INSERT = "";

		$tmp_chk = $data[$EMAIL];
		$tmp_chk = strtolower($tmp_chk);	// Make sure duplicate check is case insensitive
		$EMAIL_CHK = strtolower($EMAIL_CHK);

		if (!eregi("$tmp_chk", $EMAIL_CHK) && $data[$EMAIL] != "") {		// This Email Addr does not exist in sec-users table and is not blank

				// Build Field Name Vars

				$this_owner = $data[$N1];

				if ($data[$N2] != "NULL") { $this_owner .= " $data[$N2]"; }

				$this_email = $data[$EMAIL];
				$this_un = $data[$USERN];
				$this_pw = $data[$PASSW];

				if ($this_un == "") { $this_un = $this_email; }

				// if ($this_pw == "") { $this_pw = gen_pw(); }

				$tmp = $this_email.$this_owner;	// Create and individual UNIQUE security ID for editing db data and calendar data from client site
				$MD5CODE = md5($tmp);

				$SQL_INSERT = "INSERT INTO sec_users VALUES('NULL','$this_owner','$this_email','$this_un','$this_pw','$this_redirect',";
				for ($z=1;$z<=27;$z++) { $SQL_INSERT .= "'',"; }	// Build for fields we are not using
				$SQL_INSERT .= "'$this_groups','$this_expire','$MD5CODE')";

				mysql_query("$SQL_INSERT");

				// Modify AUTO_SECURITY_AUTH field within IMPORT table with new MD5 code match
				mysql_query("UPDATE $TABLE_NAME SET AUTO_SECURITY_AUTH = '$MD5CODE' WHERE $EMAIL = '$this_email' AND $USERN = '$this_un' AND $PASSW = '$this_pw'");


				$added_user++;

		} // End Email Check IF

	} // End While Loop

	$THIS_DISPLAY .= "<BR><BR><H2><FONT COLOR=DARKBLUE>[$added_user] ".lang("New Authenticated Users Added")."!</FONT></H2>\n\n";
	$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"../database_tables.php\">\n";
	$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT ".$btn_edit." VALUE=\" ".lang("Database Menu")." \"></FORM>\n\n";
	$THIS_DISPLAY .= "<DIV STYLE='font-size: 10pt;'>\n";
	$THIS_DISPLAY .= lang("You can view and/or edit individual user settings through<BR>the Secure Users feature.")."\n";
	$THIS_DISPLAY .= "<BR><BR></DIV>\n";

} // END STEP THREE

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = "Import records from one of your database tables into the Member Logins feature. ";
$instructions = "Example use: Upload/import a CSV full of members via the Site Data Tables feature, then use this feature to auto-create member logins for all of them.";

$module = new smt_module($module_html);
$module->add_breadcrumb_link("Database Tables", "program/modules/mods_full/download_data.php");
$module->add_breadcrumb_link("Batch Create Member Logins", "program/modules/mods_full/database_manager/auth_users.php");
$module->icon_img = "program/includes/images/data-tables-icon-med.png";
$module->heading_text = "Batch Create Member Logins";
$module->description_text = $instructions;
$module->good_to_go();
?>