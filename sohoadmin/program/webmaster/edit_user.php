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
require_once("../includes/product_gui.php");

# Display success msg coming from add user?
if ( isset($_GET['success_msg']) ) {
   $report[] = $_GET['success_msg'];
}

#######################################################
### UPDATE USER NOW								###
#######################################################

if ($ACTION == "UPDATE") {

	// First delete the occurrence of this user in the system
	// ---------------------------------------------------------------

	if ($EDIT_USER > 1) {
		mysql_query("DELETE FROM login WHERE PriKey = '$EDIT_USER'");
		mysql_query("DELETE FROM user_access_rights WHERE LOGIN_KEY = '$EDIT_USER'");
	} else {
		exit;
	}

	// sleep(2);

	// Now pretend this was a new addition to the data table set
	// ---------------------------------------------------------------

	$ACCESS_STRING = ";";

	reset($HTTP_POST_VARS);
	while (list($name, $value) = each($HTTP_POST_VARS)) {

		$value = stripslashes($value);
		$name = stripslashes($name);

		if ($name != "EDIT_USER" && $name != "ACTION" && $name != "FULL_NAME" && $name != "LOGIN_USERNAME" && $name != "LOGIN_PASSWORD") {
			$ACCESS_STRING .= "$name;";
			// echo "$name<BR>";
		}

	} // End While Loop

	// Step 1: Add New User to Login Table
	// --------------------------------------------------------------

	$tmp_pw = md5("$LOGIN_PASSWORD");
	mysql_query("INSERT INTO login VALUES('NULL','$SERVER_NAME','SOHOUSER','SOHOUSER','$FULL_NAME','$LOGIN_USERNAME','$LOGIN_PASSWORD','$tmp_pw')");

	$login_key = mysql_insert_id();

	// Step 2: Insert Access Rights Compliment
	// --------------------------------------------------------------

	mysql_query("INSERT INTO user_access_rights VALUES('NULL','$login_key','$ACCESS_STRING','future','future')");

	echo "<SCRIPT LANGUAGE=Javascript>\n";
	echo "     alert('".lang("The settings for")." $FULL_NAME ".lang("have been updated.")."');\n";
	echo "     window.location = 'webmaster.php?=SID';\n";
	echo "</SCRIPT>\n";

	exit;

} // End Action IF


#######################################################
### READ CURRENT USER DATA INTO MEMORY
#######################################################

$result = mysql_query("SELECT * FROM login WHERE PriKey = '$EDIT_USER'");
while ($row = mysql_fetch_array($result)) {
	$EDIT_FULL_NAME = $row[Email];
	$EDIT_LOGIN_USERNAME = $row[Username];
	$EDIT_LOGIN_PASSWORD = $row[Password];
	$EDIT_PRIKEY = $row[PriKey];
}

$ares = mysql_query("SELECT ACCESS_STRING FROM user_access_rights WHERE LOGIN_KEY = '$EDIT_PRIKEY'");
while ($row = mysql_fetch_array($ares)) {
	$EDIT_ACCESS = $row[ACCESS_STRING];
}

#######################################################
### START HTML/JAVASCRIPT CODE					    ###
#######################################################


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

function navto(where) {
	window.location = where+"?<?=SID?>";
}

function del_user(key) {
	var tiny = window.confirm('<? echo lang("You have selected to delete the user")." $EDIT_FULL_NAME"; ?>.\n\n<? echo lang("Once you click OK, you can not undo this process."); ?>\n\n<? echo lang("Are you sure you wish to delete this user"); ?>?');
	if (tiny != false) {
		window.location = "del_user.php?id="+key+"&<?=SID?>";
	} else {
		// Cancel Action
	}
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
.op_title {
   background: #DDE2F8;
   border: 1px solid #d3e9ef;
   font-family: tahoma, verdana, arial, helvetica, sans-serif;
   font-size: 12px;
   font-wieght: bold;
   letter-spacing: 1;
}

.op_box {
   background: #f8f9fd;
   border: 1px solid #d3e9ef;
   font-family: tahoma, verdana, arial, helvetica, sans-serif;
   font-size: 11px;
}

.mod_links {
   font-weight: bold;
}
</style>

<?

$THIS_DISPLAY = "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text WIDTH=100%><TR>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";


$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"edit_user.php\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"ACTION\" value=\"UPDATE\">\n";
$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"EDIT_USER\" value=\"$EDIT_USER\">\n";

$THIS_DISPLAY .= lang("Admin User's Full Name").":<BR>\n";
$THIS_DISPLAY .= "<INPUT TYPE=TEXT CLASS=text STYLE='WIDTH: 300px;' NAME=\"FULL_NAME\" VALUE=\"$EDIT_FULL_NAME\"><BR><BR>\n";

$THIS_DISPLAY .= lang("Login Username").":<BR>\n";
$THIS_DISPLAY .= "<INPUT TYPE=TEXT CLASS=text STYLE='WIDTH: 300px;' NAME=\"LOGIN_USERNAME\" VALUE=\"$EDIT_LOGIN_USERNAME\"><BR><BR>\n";

$THIS_DISPLAY .= lang("Login Password").":<BR>\n";
$THIS_DISPLAY .= "<INPUT TYPE=TEXT CLASS=text STYLE='WIDTH: 300px;' NAME=\"LOGIN_PASSWORD\" VALUE=\"$EDIT_LOGIN_PASSWORD\"><BR><BR>\n";

$THIS_DISPLAY .= "</TD><TD ALIGN=CENTER VALIGN=MIDDLE>\n";

$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\" ".lang("Cancel Edit")." \" onclick=\"navto('webmaster.php');\" ".$_SESSION['btn_edit'].">\n";
$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\" ".lang("Delete User")." \" onclick=\"del_user('$EDIT_USER');\" ".$_SESSION['btn_delete'].">\n";

$THIS_DISPLAY .= "</TD></TR><TR><TD ALIGN=LEFT VALIGN=TOP COLSPAN=2>\n";

// ========================================================================================
// == Control Access to Each Module
// ========================================================================================
$THIS_DISPLAY .= "<HR STYLE='HEIGHT: 1PX; COLOR: BLACK;'>\n";
$THIS_DISPLAY .= "<B>".lang("Select the seperate Modules that this user should have access to").":</b><BR><BR>\n";


if (eregi(";MOD_CREATE_PAGES;", $EDIT_ACCESS)) { $mkPages_CHECK = "CHECKED"; } else { $mkPages_CHECK = ""; }
if (eregi(";MOD_EDIT_PAGES;", $EDIT_ACCESS)) { $modPages_CHECK = "CHECKED"; } else { $modPages_CHECK = ""; }
if (eregi(";MOD_MENUSYS;", $EDIT_ACCESS)) { $MENU_CHECK = "CHECKED"; } else { $MENU_CHECK = ""; }
if (eregi(";MOD_SITE_FILES;", $EDIT_ACCESS)) { $FILES_CHECK = "CHECKED"; } else { $FILES_CHECK = ""; }
if (eregi(";MOD_TEMPLATES;", $EDIT_ACCESS)) { $TEMP_CHECK = "CHECKED"; } else { $TEMP_CHECK = ""; }
if (eregi(";MOD_FORMS;", $EDIT_ACCESS)) { $FORMS_CHECK = "CHECKED"; } else { $FORMS_CHECK = ""; }
if (eregi(";MOD_FAQ;", $EDIT_ACCESS)) { $FAQ_CHECK = "CHECKED"; } else { $FAQ_CHECK = ""; }

if (eregi(";MOD_STATS;", $EDIT_ACCESS)) { $STATS_CHECK = "CHECKED"; } else { $STATS_CHECK = ""; }
if (eregi(";MOD_PHOTO_ALBUM;", $EDIT_ACCESS)) { $PHOTO_CHECK = "CHECKED"; } else { $PHOTO_CHECK = ""; }
if (eregi(";MOD_SITE_TABLES;", $EDIT_ACCESS)) { $dbTables_CHECK = "CHECKED"; } else { $dbTables_CHECK = ""; }
if (eregi(";MOD_BLOG;", $EDIT_ACCESS)) { $BLOG_CHECK = "CHECKED"; } else { $BLOG_CHECK = ""; }
if (eregi(";MOD_SHOPPING_CART;", $EDIT_ACCESS)) { $SC_CHECK = "CHECKED"; } else { $SC_CHECK = ""; }
if (eregi(";MOD_CALENDAR;", $EDIT_ACCESS)) { $CAL_CHECK = "CHECKED"; } else { $CAL_CHECK = ""; }
if (eregi(";MOD_NEWSLETTER;", $EDIT_ACCESS)) { $NEWS_CHECK = "CHECKED"; } else { $NEWS_CHECK = ""; }
if (eregi(";MOD_DB_MANAGER;", $EDIT_ACCESS)) { $dbMan_CHECK = "CHECKED"; } else { $dbMan_CHECK = ""; }
if (eregi(";MOD_SECURITY;", $EDIT_ACCESS)) { $SEC_CHECK = "CHECKED"; } else { $SEC_CHECK = ""; }


if (eregi(";INVOICES_NO;", $EDIT_ACCESS)) {
   $invNoChk = "selected";
} elseif (eregi(";INVOICES_YES;", $EDIT_ACCESS)) {
   $invYesChk = "selected";
}


//Enable Basic Features
$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 WIDTH=90% CLASS=op_box>\n";
$THIS_DISPLAY .= "<tr>\n";
$THIS_DISPLAY .= " <td colspan=\"8\" class='op_title'>";
$THIS_DISPLAY .= "  ".lang("Enable Basic Features")."</td>\n";
$THIS_DISPLAY .= "</tr>\n";

$THIS_DISPLAY .= "<tr>\n";
$THIS_DISPLAY .= " <td align=\"right\"><input type=\"CHECKBOX\" name=\"MOD_CREATE_PAGES\" value=\"CREATE_PAGES\" $mkPages_CHECK></td>\n";
$THIS_DISPLAY .= " <td align=\"left\"><font class='mod_links'>".lang("Create New Pages")."</font></td>\n";
$THIS_DISPLAY .= " <td align=\"right\"><input type=\"CHECKBOX\" name=\"MOD_EDIT_PAGES\" value=\"EDIT_PAGES\" $modPages_CHECK></td>\n";
$THIS_DISPLAY .= " <td align=\"left\"><font class='mod_links'>".lang("Edit Pages")."</font></td>\n";
$THIS_DISPLAY .= " <td align=\"right\"><input type=\"CHECKBOX\" name=\"MOD_MENUSYS\" value=\"MENUSYS\" ".$MENU_CHECK."></td>\n";
$THIS_DISPLAY .= " <td align=\"left\"><font class='mod_links'>".lang("Menu Display")."</font></td>\n";
$THIS_DISPLAY .= " <td align=\"right\"><input type=\"checkbox\" name=\"MOD_FAQ\" value=\"FAQ\" ".$FAQ_CHECK."></td>\n";
$THIS_DISPLAY .= " <td align=\"left\"><font class='mod_links'>".lang("FAQ Manager")."</font></td>\n";
$THIS_DISPLAY .= "</tr>\n";

$THIS_DISPLAY .= "<TR>\n";
$THIS_DISPLAY .= "<TD ALIGN=right><INPUT TYPE=CHECKBOX NAME=\"MOD_SITE_FILES\" VALUE=\"SITE_FILES\" $FILES_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("File Manager")."</font></TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=right><INPUT TYPE=CHECKBOX NAME=\"MOD_TEMPLATES\" VALUE=\"TEMPLATES\" $TEMP_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("Template Manager")."</font></TD>\n";
$THIS_DISPLAY .= "<TD ALIGN=right><INPUT TYPE=CHECKBOX NAME=\"MOD_FORMS\" VALUE=\"FORMS\" $FORMS_CHECK></td>\n";
$THIS_DISPLAY .= "<TD colspan=\"3\" ALIGN=LEFT><font class='mod_links'>".lang("Forms Manager")."</font></TD>\n";
$THIS_DISPLAY .= "</TR>\n";
$THIS_DISPLAY .= "</TABLE>\n";

$THIS_DISPLAY .= "<br><br>\n";

//Enable Advanced Features
$THIS_DISPLAY .= "<TABLE BORDER=0 CELPADDING=2 CELLSPACING=0 WIDTH=90% CLASS=op_box>\n";
$THIS_DISPLAY .= "<tr>\n";
$THIS_DISPLAY .= " <td colspan=8 class='op_title'>";
$THIS_DISPLAY .= "  ".lang("Enable Advanced Features")."</td>\n";
$THIS_DISPLAY .= "</tr>\n";

$THIS_DISPLAY .= "<TR>\n";
$THIS_DISPLAY .= "<TD ALIGN=right style='padding-left:10px;'><INPUT TYPE=CHECKBOX NAME=\"MOD_STATS\" VALUE=\"STATS\" $STATS_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("Site Statistics")."</font></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=right><INPUT TYPE=CHECKBOX NAME=\"MOD_PHOTO_ALBUM\" VALUE=\"ALBUMS\" $PHOTO_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("Photo Albums")."</font></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=right><INPUT TYPE=CHECKBOX NAME=\"MOD_SITE_TABLES\" VALUE=\"dbTables\" $dbTables_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("Site Data Tables")."</font></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=right style='padding-left:10px;'><INPUT TYPE=CHECKBOX NAME=\"MOD_BLOG\" VALUE=\"BLOG\" $BLOG_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("Blog Manager")."</font></td>\n";
$THIS_DISPLAY .= "</TR>\n";

$THIS_DISPLAY .= "<TR>\n";;
$THIS_DISPLAY .= "<TD ALIGN=right style='padding-left:10px;'><INPUT TYPE=CHECKBOX NAME=\"MOD_SHOPPING_CART\" VALUE=\"SHOPPING_CART\" $SC_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("Shopping Cart")."</td>\n";
$THIS_DISPLAY .= "<TD ALIGN=right><INPUT TYPE=CHECKBOX NAME=\"MOD_CALENDAR\" VALUE=\"CALENDAR\" $CAL_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("Event Calendar")."</td>\n";
$THIS_DISPLAY .= "<TD ALIGN=right><INPUT TYPE=CHECKBOX NAME=\"MOD_NEWSLETTER\" VALUE=\"NEWSLETTER\" $NEWS_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("eNewsletter")."</td>\n";
$THIS_DISPLAY .= "<TD ALIGN=right style='padding-left:10px;'><INPUT TYPE=CHECKBOX NAME=\"MOD_DB_MANAGER\" VALUE=\"DB_MANAGER\" $dbMan_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT><font class='mod_links'>".lang("Database Table Manager")."</td>\n";
$THIS_DISPLAY .= "</TR>\n";

$THIS_DISPLAY .= "<TR>\n";
$THIS_DISPLAY .= "<TD ALIGN=right style='padding-left:10px;'><INPUT TYPE=CHECKBOX NAME=\"MOD_SECURITY\" VALUE=\"SECURITY\" $SEC_CHECK></td>\n";
$THIS_DISPLAY .= "<TD ALIGN=LEFT colspan='7'><font class='mod_links'>".lang("Secure Users")."</font></td>\n";
$THIS_DISPLAY .= "</TR>\n";

$THIS_DISPLAY .= "</TABLE><BR><BR>\n\n";


$THIS_DISPLAY .= "<HR STYLE='HEIGHT: 1PX; COLOR: BLACK;'>\n";

$THIS_DISPLAY .= "<B>".lang("Select each Site Page this user should have access to").":</b><BR>\n";
$THIS_DISPLAY .= "<i>".lang("Note: User will not be able to access these pages unless the Edit Pages module itself is enabled (above).")."</i><BR><br>\n";

// ========================================================================================
// == LOOP THROUGH ALL SITE PAGES AND PLACE CHECKBOX OPTIONS NEXT TO EACH FOR BLOB RIGHTS
// ========================================================================================

$result = mysql_query("SELECT page_name, url_name, link FROM site_pages WHERE type = 'Main' ORDER BY page_name");
$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 WIDTH=90% ALIGN=CENTER CLASS=text>\n";

$rcnt = 1;

while ($row = mysql_fetch_array($result)) {
	if(!preg_match('/^http:/i', $row['link'])){
		
		if ($rcnt == 1) { $THIS_DISPLAY .= "<TR>\n"; }
		$rcnt++;
		
		$checked = "";
		$tmp_chk = eregi_replace(" ", "_", $row[page_name]);
		if (eregi(";$tmp_chk;", $EDIT_ACCESS)) { $checked = "CHECKED"; }
		
		$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";
		$THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX $checked NAME=\"$row[page_name]\" VALUE=\"$row[page_name]\"> $row[page_name]";
		$THIS_DISPLAY .= "</TD>\n";
		
		if ($rcnt == 4) {
		   $THIS_DISPLAY .= "</TR>\n";
		   $rcnt = 1;
		}
	}
} // End While Loop

$THIS_DISPLAY .= "</TABLE><BR><BR>\n";

$THIS_DISPLAY .= "<HR STYLE='HEIGHT: 1PX; COLOR: BLACK;'>\n";

// ========================================================================================
// == SHOPPING CART ACCESS OPTIONS
// ========================================================================================

$THIS_DISPLAY .= "<B>".lang("Shopping Cart access options").":</b><BR>\n";
$THIS_DISPLAY .= "<i>".lang("Note: User must have access to Shopping Cart module itself (above).")."</b><BR><br>\n";

$THIS_DISPLAY .= "<TABLE BORDER=0 CELPADDING=0 CELLSPACING=0 WIDTH=90% CLASS=op_box>\n";
$THIS_DISPLAY .= "<tr>\n";
$THIS_DISPLAY .= " <td align='left' width='100' style='padding: 3px;'>".lang("View Invoices Only")."?</td>";
$THIS_DISPLAY .= " <td align='left' style='padding: 3px;'>\n";
$THIS_DISPLAY .= "  <select name=\"INVOICES_ONLY\">\n";
$THIS_DISPLAY .= "   <option value=\"INVOICES_NO\" $invNoChk>".lang("No")."</option>\n";
$THIS_DISPLAY .= "   <option value=\"INVOICES_YES\" $invYesChk>".lang("Yes")."</option>\n";
$THIS_DISPLAY .= " </td>\n";
$THIS_DISPLAY .= "</tr>\n";
$THIS_DISPLAY .= "</TABLE>\n";

$THIS_DISPLAY .= "<br><HR STYLE='HEIGHT: 1PX; COLOR: BLACK;'>\n";

$THIS_DISPLAY .= "<B>".lang("Select each User Data Table this user should have access to").":<BR><BR>\n";

// ========================================================================================
// == LOOP THROUGH ALL UDT_ TABLES AND PLACE CHECKBOX OPTIONS NEXT TO EACH FOR BLOB RIGHTS
// ========================================================================================


$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 WIDTH=90% CLASS=text>\n";
$rcnt = 1;

$result = mysql_list_tables("$db_name");
$i = 0;

while ($i < mysql_num_rows ($result)) {
   $tb_names[$i] = mysql_tablename ($result, $i);
   if (eregi("UDT_", $tb_names[$i])) {    // Only Get UDT Tables (Remember They can delete these kinds of tables (Dangerous)

         if ($rcnt == 1) { $THIS_DISPLAY .= "<TR>\n"; }
         $rcnt++;

         $checked = "";
         if (eregi(";$tb_names[$i];", $EDIT_ACCESS)) { $checked = "CHECKED"; }

         $THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";
         $THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX $checked NAME=\"$tb_names[$i]\" VALUE=\"$tb_names[$i]\"> $tb_names[$i]";
         $THIS_DISPLAY .= "</TD>\n";

         if ($rcnt == 4) {
            $THIS_DISPLAY .= "</TR>\n";
            $rcnt = 1;
         }


   }  // End IF UDT
   $i++; // Increment Table Counter
} // End While

$THIS_DISPLAY .= "</TABLE><BR><BR>\n";



$THIS_DISPLAY .= "<DIV ALIGN=CENTER>\n";
$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT CLASS=FormLt1 VALUE=\" ".lang("Update User")." \"></DIV>\n";

$THIS_DISPLAY .= "</FORM>\n";


$THIS_DISPLAY .= "</TD></TR></TABLE>\n";

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();


$module = new smt_module($module_html);
$module->meta_title = "".lang("Edit Administrative User").": ".$EDIT_FULL_NAME;
$module->heading_text = $module->meta_title;
$module->add_breadcrumb_link(lang("Webmaster"), "program/webmaster/webmaster.php");
$module->add_breadcrumb_link($module->meta_title, "program/webmaster/edit_user.php?EDIT_USER=".$_REQUEST['EDIT_USER']);
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/webmaster-enabled.gif";
$module->description_text = lang("When this administrative user logs-in, what should he/she have access to? What aspects of your website should he be able to manage?");
$module->good_to_go();

####################################################################

?>