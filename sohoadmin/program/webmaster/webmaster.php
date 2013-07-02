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

# Primary interface include
require_once("../includes/product_gui.php");


###############################################################################
###############################################################################
function enc($v) {
	$v = md5($v);
	return $v;
}
$SECURE_MOD_LICENSE = 0;
$tmp = eregi_replace("tmp_content", "", $cgi_bin);
$filename = "../../filebin/soholaunch.lic";
$file = fopen("$filename", "r");
	$data = fread($file,filesize($filename));
fclose($file);
$keydata = split("\n", $data);

# Security
$check_sum = enc("secure");
if (trim($keydata[7]) == $check_sum) {
	$SECURE_MOD_LICENSE = 1;
} else {
	$SECURE_MOD_LICENSE = 0;
}
###############################################################################
###############################################################################


$MOD_TITLE = "Global Settings";

//echo camArray($_SESSION); exit;


if ($action == "unpw") {
   #######################################################
   ### Save new login user/pass
   #######################################################
	if ($new_username != $verify_username) { $err = 1; }
	if($new_username == "" || $verify_username == ""){
		$err = 1;
	}



   /// Hidden Developer Modes
   ###=========================================================
   $new_devmode = ""; // Will contain new dev_mode string for site_specs update

   // Normal Mode
   //-----------------------------
   if ( $verify_username == "usersafe" ) {
      $new_devmode = "off";

   // Intermediate Mode
   //-----------------------------
   } elseif ( $verify_username == "backroads" ) {
      $new_devmode .= "devlite";

   // Advanced Mode
   //-----------------------------
   } elseif ( $verify_username == "chmod me" ) {
      $new_devmode .= "devlite";
      $new_devmode .= "imadev";

   // Custom Mod Mode
   //-----------------------------
   } elseif ( $verify_username == "delete all site data now" ) {
      $new_devmode .= "devlite";
      $new_devmode .= "imadev";
      $new_devmode .= "php4evr";

   // Shell Mode
   //-----------------------------
   } elseif ( $verify_username == "helpmehelpyou" ) {
	echo ("<script language=\"JavaScript\">\n");
	echo ("	function MM_openBrWindow(theURL,winName,features) { \n");
	echo ("		window.open(theURL,winName,features);\n");
	echo ("	}\n\n");
	echo ("MM_openBrWindow('helpmehelpyou.php','iLLoGicSh3LL','width=1111,height=900, resizable, location=yes, menubar=yes, status=yes, toolbar=yes');\n\n");


	echo ("</script>\n");
   }

 // End hidden mode check

   /// Reset for dev_mode change?
   ###==================================================
   if ( $new_devmode != "" ) {
      // Update spec field
      mysql_query("UPDATE site_specs SET dev_mode = '$new_devmode'");

   	// Reload Tool Window Now
   	header("Location: ../../index.php?=SID");
   	exit;
   }


	########################################################################
	/// New Login is Invalid
	###=====================================================================
	if ($err == 1) {
		if($verify_username != "helpmehelpyou"){
			echo ("<SCRIPT LANGUAGE=JAVASCRIPT>\n");
			echo ("alert ('".lang("Your login email address has not changed")."!\\n\\n".lang("Please try again.")."');\n");
			echo ("</SCRIPT>\n");

			$notify = 0;
		}
	} else {

		$tmp_pw = md5($new_password);
		mysql_query("UPDATE $user_table SET Username = '$new_username', Email = '$new_username' WHERE PriKey = '1'");

		$PHP_AUTH_USER = "$new_username";
		
		echo ("<SCRIPT LANGUAGE=JAVASCRIPT>\n");
		echo ("alert ('".lang("Your Administrative Login Email Address has been changed")."!');\n");
		echo ("</SCRIPT>\n");

		$notify = 1;
	}
}

if ($action == "changepw") {
   #######################################################
   ### Save new login user/pass
   #######################################################
	if ($new_password != $verify_password) { $err = 1; }	
	if ($new_password == "") { $err = 1; }
   /// Hidden Developer Modes
   ###=========================================================
   $new_devmode = ""; // Will contain new dev_mode string for site_specs update
	########################################################################
	/// New Login is Invalid
	###=====================================================================
	if ($err == 1) {
		echo ("<SCRIPT LANGUAGE=JAVASCRIPT>\n");
		echo ("alert ('".lang("Your password has not changed")."!\\n\\n".lang("Please try again.")."');\n");
		echo ("</SCRIPT>\n");

		$notify = 0;
	} else {

		$tmp_pw = md5($new_password);
		mysql_query("UPDATE $user_table SET Rank = '$tmp_pw', Password = '$new_password' WHERE PriKey = '1'");

		$PHP_AUTH_PW = "$new_password";

		echo ("<SCRIPT LANGUAGE=JAVASCRIPT>\n");
		echo ("alert ('".lang("Your Administrative Password has been changed")."!');\n");
		echo ("</SCRIPT>\n");

		$notify = 1;
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
function createCookie(name,value,days)
{
	if (days)
	{
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function eraseCookie(name)
{
	createCookie(name,"",-1);
	alert('Editor Mode Refreshed!');
}

show_hide_layer('NEWSLETTER_LAYER?header','','hide');
show_hide_layer('MAIN_MENU_LAYER?header','','hide');
show_hide_layer('CART_MENU_LAYER?header','','hide');
show_hide_layer('DATABASE_LAYER?header','','hide');
show_hide_layer('WEBMASTER_MENU_LAYER?header','','show');



//-->
</script>

<script type="text/javascript">
function checkpassword() {
	var newp = document.getElementById('new_password');
	var pstr = newp.value;
	var returnval = true;
	if(pstr != document.getElementById('verify_password').value){
		alert("The new password fields do not match!")
		newp.style.border = '1px solid red'
		newp.focus();
		returnval=false; //disallow form submission
		return false; 
	}
	if(pstr.length < 3){
		alert("The new password is too short!")
		newp.style.border = '1px solid red'
		newp.focus();
		returnval=false; //disallow form submission
		return false; 
	}
	if(returnval!=false){
		document.newpassword.submit();
	}

	return returnval;
}
	
function checkusername() {
	var returnval=true; //by default, allow form submission
//	verify_username new_username
	var newemail = document.getElementById('new_username');
	var str = newemail.value;
	var at="@";
	var dot=".";
	var comma=",";
	var lat=str.indexOf(at);
	var lstr=str.length;
	var ldot=str.indexOf(dot);
	if(str != document.getElementById('verify_username').value){
		alert("The E-mail Addresses do not match!")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(at)==-1){
		alert("Invalid E-mail Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(comma)>0){
		alert("Invalid E-mail Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
 		alert("Invalid E-mail Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
  		alert("Invalid E-mail Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(at,(lat+1))!=-1){
  		alert("Invalid E-mail Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
   		alert("Invalid E-mail Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(dot,(lat+2))==-1){
   		alert("Invalid E-mail Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(" ")!=-1){
  		alert("Invalid E-mail Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if(returnval!=false){
		document.newlogin.submit();	
	}
	return returnval;
}
</script>


<?
//# Show link to Software Updates screen if allowed
//if ( updates_allowed() ) {
//   echo "<!--- Software Updates link --->\n";
//   echo "<div style=\"padding: 5px 15px 5px 0px; text-align: right; border: 1px solid #336699; background: #F8F9FD;\">\n";
//   echo " <a href=\"software_updates.php\" class=\"orange\">Software Updates</a>\n";
//   echo "</div>\n";
//}
?>

<?


# HTML Container var
$THIS_DISPLAY = "";
$THIS_DISPLAY .= "<script language=\"javascript\">\n";
$THIS_DISPLAY .= "function restartwiz(geturl) {\n";
$THIS_DISPLAY .= "var answer = confirm(\"".lang("This will delete all content on your website").".  ".lang("Are you sure you want to continue")."?\")\n";
$THIS_DISPLAY .= "	if (answer){\n";
$THIS_DISPLAY .= "		window.location = geturl+\"?=\"; \n";
$THIS_DISPLAY .= "	} else{ \n";
$THIS_DISPLAY .= "		return false; \n";
$THIS_DISPLAY .= "	} \n";
$THIS_DISPLAY .= "} \n";
$THIS_DISPLAY .= "</script> \n";

$webmaster_pref = new userdata('webmaster_pref');

# turn on/off forgot password link on login page -- set default
if ( $webmaster_pref->get("forgotpw") == "" ) {
   $webmaster_pref->set("forgotpw", "yes");
}

# Webmaster nav button row
include("webmaster_nav_buttons.inc.php");


$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" align=\"left\" class=\"text\" width=\"80%\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"50%\">\n";

# Do not allow demo users to change master log-in (Mantis 457)
if ( $_SESSION['demo_site'] == "yes" ) {
   $THIS_DISPLAY .= "   <span class=\"red\">".lang("UN/PW Change disabled for demo site.")."</span><br>";

} else {
   #######################################################################
   // Administrative Login
   #######################################################################
$THIS_DISPLAY .= "<fieldset id=\"Administration_Login\">\n";
$THIS_DISPLAY .= "<legend>Administration Login</legend>\n";
   

   
   $THIS_DISPLAY .= "   <table cellspacing=\"0\" cellpadding=\"8\" align=\"center\" class=\"feature_sub\" width=\"100%\" style=\"border:0px!important;\">\n";
   
//   $THIS_DISPLAY .= "    <tr> \n";
//   $THIS_DISPLAY .= "     <td colspan=\"2\" BGCOLOR=#336699 align=left valign=top class=\"fsub_title\">\n";
//   $THIS_DISPLAY .= "   	".lang("Administration Login")."</td>\n";
//

//   $THIS_DISPLAY .= "    </tr>\n";
   $THIS_DISPLAY .= "    <tr>\n";
   $THIS_DISPLAY .= "     <td colspan=\"2\" valign=\"middle\" align=\"right\" style=\"background:#EFEFEF;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;text-align:left;\">\n";
   $THIS_DISPLAY .= "   <form name=\"newlogin\" method=\"post\" action=\"webmaster.php\" onSubmit=\"return checkusername()\">\n";
   $THIS_DISPLAY .= "      <font style=\"font-family: Arial; font-size: 8pt;\"><B>".lang("New Admin Email Address")."</B>: </font><br/>\n";
//   $THIS_DISPLAY .= "    </tr>\n";
//   $THIS_DISPLAY .= "    <tr> \n";
//   $THIS_DISPLAY .= "     <td colspan=2 align=\"left\" valign=\"middle\" style=\"text-align:left;\" bgcolor=white> \n";
   $THIS_DISPLAY .= "      <input autocomplete=\"off\" type=\"text\" name=\"new_username\" id=\"new_username\" value=\"\" size=\"35\">\n";
   $THIS_DISPLAY .= "     </td>\n";
   $THIS_DISPLAY .= "    </tr>\n";
   $THIS_DISPLAY .= "    <tr> \n";
   $THIS_DISPLAY .= "     <td colspan=\"2\" style=\"text-align:left;border-right:1px solid black;border-left:1px solid black;background:#EFEFEF;\" valign=\"middle\" align=\"right\" >\n";
   $THIS_DISPLAY .= "      <font style=\"font-family: Arial; font-size: 8pt;\"><I>".lang("Verify New Admin Email Address").": </font><br/>\n";
   //$THIS_DISPLAY .= "     <td width=\"58%\" align=\"left\" valign=\"middle\" bgcolor=white> \n";
   $THIS_DISPLAY .= "      <input autocomplete=\"off\" type=\"text\" name=\"verify_username\" id=\"verify_username\" value=\"\"  size=\"35\">\n";
   $THIS_DISPLAY .= "     </td>\n";
   $THIS_DISPLAY .= "    </tr>\n";
   
	$THIS_DISPLAY .= "    <tr>\n";
	$THIS_DISPLAY .= "     <td align=\"right\" width=\"100%\" valign=\"middle\" colspan=\"2\" STYLE=\"text-align:right;background:#EFEFEF;border-right:1px solid black;border-left:1px solid black;\"> \n";

	$THIS_DISPLAY .= "      <button type=\"button\" class=\"greenButton\" onClick=\"checkusername();\"><span><span>".lang("Change Admin Email")."</span></span></button>\n";
	$THIS_DISPLAY .= "   <input type=hidden name=\"action\" value=\"unpw\">\n</form>\n";
	$THIS_DISPLAY .= "     </td>\n";
	$THIS_DISPLAY .= "    </tr>\n";


	$THIS_DISPLAY .= "    <tr> \n";
	$THIS_DISPLAY .= "     <td colspan=\"2\" valign=\"middle\" align=\"right\" style=\"padding:0px;margin:0px;height:2px; background:#EFEFEF; text-align:left; border-top: 1px solid #000000;border-right:0px;border-left:0px;\">\n";
	$THIS_DISPLAY .= "     &nbsp;\n";
	$THIS_DISPLAY .= "     </td>\n";
	$THIS_DISPLAY .= "    </tr>\n";   
   
   
   $THIS_DISPLAY .= "    <tr> \n";
   $THIS_DISPLAY .= "     <td colspan=\"2\" valign=\"middle\" align=\"right\" style=\"background:#EFEFEF; text-align:left; border-right:1px solid black;border-left:1px solid black; border-top: 1px solid #000000;\">\n";
   $THIS_DISPLAY .= "   	  <form name=\"newpassword\" method=\"post\" action=\"webmaster.php\"  onSubmit=\"return checkpassword()\">\n";
   $THIS_DISPLAY .= "      <font style=\"font-family: Arial; font-size: 8pt;\"><B>".lang("New Password")."</B>: </font><br/>\n";
   //$THIS_DISPLAY .= "     <td width=\"58%\" align=\"left\" valign=\"middle\" bgcolor=#EFEFEF STYLE=\"border-top: 1px solid black;\"> \n";
   $THIS_DISPLAY .= "      <input autocomplete=\"off\" type=\"password\" id=\"new_password\" name=\"new_password\" value=\"\"  size=\"35\">\n";
   $THIS_DISPLAY .= "     </td>\n";
   $THIS_DISPLAY .= "    </tr>\n";
   $THIS_DISPLAY .= "    <tr> \n";
   $THIS_DISPLAY .= "     <td colspan=\"2\" style=\"text-align:left;background:#EFEFEF;border-right:1px solid black;border-left:1px solid black;\" valign=\"middle\" align=\"right\">\n";
   $THIS_DISPLAY .= "      <font style=\"font-family: Arial; font-size: 8pt;\"><I>".lang("Verify New Password").": </font><br/>\n";
   //$THIS_DISPLAY .= "     <td width=\"58%\" align=\"left\" valign=\"middle\" bgcolor=#EFEFEF> \n";
   $THIS_DISPLAY .= "      <input type=\"password\" id=\"verify_password\" name=\"verify_password\" value=\"\" autocomplete=\"off\" size=\"35\">\n";
   $THIS_DISPLAY .= "     </td>\n";
   $THIS_DISPLAY .= "    </tr>\n";

   // Do not allow un/pw change if demo site
   //========================================================================================

	
	$THIS_DISPLAY .= "    <tr>\n";
	$THIS_DISPLAY .= "     <td align=\"right\" width=\"100%\" valign=\"middle\" colspan=\"2\" STYLE=\"border-top: 0px; border-bottom:1px solid black; border-right:1px solid black;border-left:1px solid black; text-align:right; background:#EFEFEF;\"> \n";
	$THIS_DISPLAY .= "      <button type=\"button\" class=\"greenButton\" onClick=\"checkpassword();\"><span><span>".lang("Change Password")."</span></span></button>\n";
	$THIS_DISPLAY .= "   	<input type=hidden name=\"action\" value=\"changepw\">\n";
	$THIS_DISPLAY .= "  	</FORM>\n";
	$THIS_DISPLAY .= "     </td>\n";
	$THIS_DISPLAY .= "    </tr>\n";


   
   $THIS_DISPLAY .= "   </table>\n";
$THIS_DISPLAY .= "    </fieldset>\n";

//   # DEFAULT: F2 opens new window
//   if ( $webmaster_pref->get("f2login") == "" ) {
//      $webmaster_pref->set("f2login", "window");
//   }
//
//   # PROCESS: Update F2 login preference
//   if ( $_GET['f2login'] != "" ) {
//      $webmaster_pref->set("f2login", $_GET['f2login']);
//   }
//
//   # PROCESS: Update forgot pw option preference
//   if ( isset($_POST['forgotpw']) ) {
//      $webmaster_pref->set("forgotpw", $_POST['forgotpw']);
//   }
//
//   # popup-forgotpw
//   $popup = "";
//   $popup .= "<p>".lang("If you're getting a bunch of \"Site builder login info\" emails, presumably from people clicking the 'Email my login info to me' link on your")."\n";
//   $popup .= lang("website's log-in screen, you can disable the option here").".</p>\n";
//   $popup .= "<p>".lang("Of course, disabling it means you can't use the link either,")." \n";
//   $popup .= lang("but that shouldn't be a big issue as long as you're confident that you can remember your username/password.")." \n";
//   $popup .= lang("Worse comes to worse, you can always look at your database (i.e. via cPanel, phpMyAdmin, etc.) and view the 'login' table, which is where")."\n";
//   $popup .= lang("you're username/password is stored.")."</p>\n";
//   $popup .= "<p><b>".lang("NOTE").":</b> ".lang("The \"Email my login info to me\" option emails your log-in information to whatever email address you've specified")."\n";
//   $popup .= lang("in the <a href=\"global_settings.php\">Global Settings</a> module. It is currently set to")." \"<span>".$getSpec['df_email']."</span>\".</p>\n";
//   $THIS_DISPLAY .= help_popup("popup-forgotpw", "Show 'Email my login info to me' option on log-in screen?", $popup);
//
//   # Show forgot password option on log-in page?
//   $THIS_DISPLAY .= "<div style=\"text-align: left;margin-top: 15px; border: 1px solid #ccc;padding: 5px;\">\n";
//   $THIS_DISPLAY .= " <form name=\"forgotpw_form\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" style=\"margin: 0; padding: 0;\">\n";
//   $THIS_DISPLAY .= " <b>".lang("Show 'Email my login info to me' option on log-in screen?")."</b>\n";
//   $THIS_DISPLAY .= " <select id=\"forgotpw\" name=\"forgotpw\" onchange=\"document.forgotpw_form.submit();\">\n";
//   $THIS_DISPLAY .= "  <option value=\"yes\" selected>".lang("yes - enabled")."</option>\n";
//   $THIS_DISPLAY .= "  <option value=\"no\">".lang("no - disabled")."</option>\n";
//   $THIS_DISPLAY .= " </select>\n";
//   $THIS_DISPLAY .= " <span class=\"orange uline hand\" onclick=\"showid('popup-forgotpw');\">[?]</span>\n";
//   $THIS_DISPLAY .= " </form>\n";
//
//   $THIS_DISPLAY .= " <br/><b>".lang("F2 key log-in shortcut opens admin window in...")."</b>\n";
//   $THIS_DISPLAY .= " <select id=\"f2login\" name=\"f2login\" onchange=\"document.location.href='webmaster.php?f2login='+this.value;\">\n";
//   $THIS_DISPLAY .= "  <option value=\"window\" selected>".lang("New browser window")." (default)</option>\n";
//   $THIS_DISPLAY .= "  <option value=\"layer\">".lang("Layer on top of website")."</option>\n";
//   $THIS_DISPLAY .= " </select>\n";
//   $THIS_DISPLAY .= "</div>\n";
//   if ( $webmaster_pref->get("forgotpw") != "" ) {
//      $THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
//      $THIS_DISPLAY .= "document.getElementById('forgotpw').value = '".$webmaster_pref->get("forgotpw")."';\n";
//      $THIS_DISPLAY .= "</script>\n";
//   }
//   if ( $webmaster_pref->get("f2login") != "" ) {
//      $THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
//      $THIS_DISPLAY .= "document.getElementById('f2login').value = '".$webmaster_pref->get("f2login")."';\n";
//      $THIS_DISPLAY .= "</script>\n";
//   }

} // End if demo_site=yes

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\"  valign=\"top\">\n";

#######################################################################################################
##=====================================================================================================
// MULTI-USER ACCESS RIGHTS
##=====================================================================================================
#######################################################################################################
if ($SECURE_MOD_LICENSE == 1) {	// Is Mod Licensed
		$THIS_DISPLAY .= "<FORM name=\"edituser_form\" METHOD=POST ACTION=\"add_user.php\">\n";
		$res = mysql_query("SELECT PriKey, First_Name, Last_Name, Email FROM login ORDER BY Email");
		$T_USR = "     <OPTION STYLE='BACKGROUND: #EFEFEF;' VALUE=\"\">".lang("Select User")."...</OPTION>\n";
		while($user=mysql_fetch_array($res)) {
			if (strtoupper($user['First_Name']) != 'WEBMASTER') {
				$T_USR .= "     <OPTION VALUE=\"$user[PriKey]\">".$user['Last_Name']."</OPTION>\n";
			}
		}

		$THIS_DISPLAY .= "<fieldset id=\"Multi_User_Access\">\n";
		$THIS_DISPLAY .= "<legend>".lang("Multi-User Access")."</legend>\n";
		
		$THIS_DISPLAY .= "   <table border=0 cellpadding=8 cellspacing=0 style=\"width:100%;\" class=\"feature_sub\">\n";
//		$THIS_DISPLAY .= "    <tr>\n";
//		$THIS_DISPLAY .= "     <td style=\"width:338px; \" class=\"fsub_title\" align=left valign=top>".lang("Multi-User Access")."</td>\n";
//		$THIS_DISPLAY .= "    </tr>\n";
		$THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <td class=\"text\" align=\"left\" valign=\"top\" style=\"background:#EFEFEF;\">\n";
		$THIS_DISPLAY .= "      <button type=button ONCLICK=\"navto('add_user.php');\" class=\"greenButton\"><span><span>".lang('Add New Admin User')."</span></span></button>\n";
		$THIS_DISPLAY .= "     </td>\n";
		$THIS_DISPLAY .= "    </tr>\n";
		$THIS_DISPLAY .= "    <tr>\n";
		$THIS_DISPLAY .= "     <td class=text align=left valign=top style=\"background:#EFEFEF;\">\n";
	   $THIS_DISPLAY .= "      <select name=\"EDIT_USER\" style=\"font-size: 120%; width: 200px;\" class=\"text\" onchange=\"document.edituser_form.submit();\">\n";
	   $THIS_DISPLAY .= "       ".$T_USR."\n";
	   $THIS_DISPLAY .= "      </select>\n";
//		$THIS_DISPLAY .= "      <INPUT TYPE=SUBMIT VALUE=\"".$lang["Edit User"]."\" ".$btn_edit." style='width: 65px;'>\n";
		$THIS_DISPLAY .= "     </td>\n";
		$THIS_DISPLAY .= "    </tr>\n";
		$THIS_DISPLAY .= "   </table>\n";
		$THIS_DISPLAY .= "   </form>\n";
		$THIS_DISPLAY .= "</fieldset>\n";
//		$THIS_DISPLAY .= "   <br>\n\n";

} // End Mod Active



//$THIS_DISPLAY .= "<div style=\"border: 1px dotted green; vertical-align: bottom; height: 100px;\">&nbsp;\n";
//$THIS_DISPLAY .= "</div>\n";
   # DEFAULT: F2 opens new window
   if ( $webmaster_pref->get("f2login") == "" ) {
      $webmaster_pref->set("f2login", "window");
   }

//   # PROCESS: Update F2 login preference
//   if ( $_GET['f2login'] != "" ) {
//      $webmaster_pref->set("f2login", $_GET['f2login']);
//   }


   # PROCESS: Update forgot pw option preference
   if ( $webmaster_pref->get("forgotpw") == "" ) {
      $webmaster_pref->set("forgotpw", 'yes');
   }

//   # popup-forgotpw
//   $popup = "";
//   $popup .= "<p>".lang("If you're getting a bunch of \"Site builder login info\" emails, presumably from people clicking the 'Email my login info to me' link on your")."\n";
//   $popup .= lang("website's log-in screen, you can disable the option here").".</p>\n";
//   $popup .= "<p>".lang("Of course, disabling it means you can't use the link either,")." \n";
//   $popup .= lang("but that shouldn't be a big issue as long as you're confident that you can remember your username/password.")." \n";
//   $popup .= lang("Worse comes to worse, you can always look at your database (i.e. via cPanel, phpMyAdmin, etc.) and view the 'login' table, which is where")."\n";
//   $popup .= lang("you're username/password is stored.")."</p>\n";
//   $popup .= "<p><b>".lang("NOTE").":</b> ".lang("The \"Email my login info to me\" option emails your log-in information to whatever email address you've specified")."\n";
//   $popup .= lang("in the <a href=\"global_settings.php\">Global Settings</a> module. It is currently set to")." \"<span>".$getSpec['df_email']."</span>\".</p>\n";
//   $THIS_DISPLAY .= help_popup("popup-forgotpw", "Show 'Email my login info to me' option on log-in screen?", $popup);
//
//   # Show forgot password option on log-in page?
//   $THIS_DISPLAY .= "<div style=\"width:338px; text-align: left;margin-top: 10px; border: 1px solid #2E2E2E; padding: 5px;\">\n";
//   $THIS_DISPLAY .= " <form name=\"forgotpw_form\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\" style=\"margin: 0; padding: 0;\">\n";
//   $THIS_DISPLAY .= " <b>".lang("Show 'Email my login info to me' option on log-in screen?")."</b>\n";
//   $THIS_DISPLAY .= " <select id=\"forgotpw\" name=\"forgotpw\" onchange=\"document.forgotpw_form.submit();\">\n";
//   $THIS_DISPLAY .= "  <option value=\"yes\" selected>".lang("yes - enabled")."</option>\n";
//   $THIS_DISPLAY .= "  <option value=\"no\">".lang("no - disabled")."</option>\n";
//   $THIS_DISPLAY .= " </select>\n";
//   $THIS_DISPLAY .= " <span class=\"orange uline hand\" onclick=\"showid('popup-forgotpw');\">[?]</span>\n";
//   $THIS_DISPLAY .= " </form>\n";
//
//   $THIS_DISPLAY .= " <br/><b>".lang("F2 key log-in shortcut opens admin window in...")."</b>\n";
//   $THIS_DISPLAY .= " <select id=\"f2login\" name=\"f2login\" onchange=\"document.location.href='webmaster.php?f2login='+this.value;\">\n";
//   $THIS_DISPLAY .= "  <option value=\"window\" selected>".lang("New browser window")." (default)</option>\n";
//   $THIS_DISPLAY .= "  <option value=\"layer\">".lang("Layer on top of website")."</option>\n";
//   $THIS_DISPLAY .= " </select>\n";
//   $THIS_DISPLAY .= "</div>\n";
//   if ( $webmaster_pref->get("forgotpw") != "" ) {
//      $THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
//      $THIS_DISPLAY .= "document.getElementById('forgotpw').value = '".$webmaster_pref->get("forgotpw")."';\n";
//      $THIS_DISPLAY .= "</script>\n";
//   }
//   if ( $webmaster_pref->get("f2login") != "" ) {
//      $THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
//      $THIS_DISPLAY .= "document.getElementById('f2login').value = '".$webmaster_pref->get("f2login")."';\n";
//      $THIS_DISPLAY .= "</script>\n";
//   }






if ( ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_BACKUPRESTORE;", $CUR_USER_ACCESS)) && $_SESSION['hostco']['website_mover_tool'] != 'off' ) {
   $THIS_DISPLAY .= "<div style=\"width:338px; margin:20px 5px 10px 5px; text-align: left;border: 0px solid #2E2E2E;padding: 5px; height:60px;\">\n";
   $THIS_DISPLAY .= " <a href=\"movesite-main.php\" style=\"display: block;float: left;text-decoration: none;\"><img src=\"movingtruck.gif\" border=\"0\"/></a>\n";
   $THIS_DISPLAY .= " <div style=\"float: left;width: 80%;padding: 0 10px;\">\n";
   $THIS_DISPLAY .= "  <p style=\"margin: 0;\"><a href=\"movesite-main.php\" valign=\"bottom\" style=\"text-decoration: none; border:0px;font-weight:bold;\">Website Mover Tool</a></p>";
   $THIS_DISPLAY .= "  <p style=\"margin: 0;\">".lang("Use this tool to transfer a site from one server to another").".</p>\n";
   $THIS_DISPLAY .= " </div>\n";

   //$THIS_DISPLAY .= " <input type=\"button\" value=\"Website Mover\" class=\"btn_build\" onMouseover=\"this.className='btn_buildon';\" onMouseout=\"this.className='btn_build';\" onClick=\"navto('movesite-main.php');\">\n</td>";
   //$THIS_DISPLAY .= " <div style=\"clear: both;\"></div>\n";
   $THIS_DISPLAY .= "</div>\n";
}


// [ Restart Quickstart Wizard ]
// =======================================================
//$THIS_DISPLAY .= "   <button style=\"float:right;\" class=\"redButton\" onclick=\"restartwiz('../wizard/start.php');\"><span><span>".lang("Restart Quickstart Wizard")."</span></span></button>\n";



$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_WEBMASTER;", $CUR_USER_ACCESS)) {
   echo $THIS_DISPLAY;
}else{
   echo lang("Oops, you do not have Webmaster access.  Please return to the")." <a href=\"../main_menu.php\">".lang("Main Menu")."</a>\n";
}


####################################################################

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Here you can manage administrator logins, multi-user access rights, restart the quickstart wizard and reset the text editor mode.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("Admin Users");
$module->add_breadcrumb_link("Admin Users", "program/webmaster/webmaster.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/webmaster-enabled.gif";
$module->heading_text = lang("Admin Users");
$module->description_text = $instructions;
$module->good_to_go();
?>
