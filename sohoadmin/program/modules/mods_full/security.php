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
require_once('../../includes/product_gui.php');

$globalprefObj = new userdata('global');

# Make Sure Security Groups exist
#======================================================
$match = 0;
$result = soho_list_tables();
$i = 0;
while ($i < mysql_num_rows ($result)) {
	$tb_names[$i] = mysql_tablename ($result, $i);
	if ($tb_names[$i] == "sec_codes") { $match = 1; }
	if ($tb_names[$i] == "sec_users") { $matchsec = 1; }
	$i++;
}
if ($match != 1) {
	mysql_db_query("$db_name","CREATE TABLE sec_codes (PriKey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, security_code CHAR(15))");
}


if ($matchsec != 1) {
	mysql_db_query("$db_name",'CREATE TABLE sec_users (PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY, OWNER_NAME CHAR(150), OWNER_EMAIL CHAR(150), USERNAME CHAR(150), PASSWORD CHAR(150), REDIRECT_PAGE CHAR(150), BFIRSTNAME CHAR(150), BLASTNAME CHAR(150), BCOMPANY CHAR(150), BADDRESS1 CHAR(150), BADDRESS2 CHAR(150), BCITY CHAR(150), BSTATE CHAR(150), BCOUNTRY CHAR(150), BZIPCODE CHAR(150), BPHONE_COUNTRYCODE CHAR(150), BPHONE_AREACODE CHAR(150), BPHONE_PREFIX CHAR(150), BPHONE_SUFFIX CHAR(150), BEMAILADDRESS CHAR(150), SFIRSTNAME CHAR(150), SLASTNAME CHAR(150), SCOMPANY CHAR(150), SADDRESS1 CHAR(150), SADDRESS2 CHAR(150), SCITY CHAR(150), SSTATE CHAR(150), SCOUNTRY CHAR(150), SZIPCODE CHAR(150), SPHONE_COUNTRYCODE CHAR(150), SPHONE_AREACODE CHAR(150), SPHONE_PREFIX CHAR(150), SPHONE_SUFFIX CHAR(150), GROUPS BLOB, EXPIRATION_DATE DATE, MD5CODE CHAR(255))');
}
# PROCESS "ADD NEW GROUP" ACTION
#======================================================
$add_alert = 0;

if ($ACTION == "NG") {

		# Check for duplicates
		$ef = 0;
		$NEWGROUP = ucwords($NEWGROUP);

		$result = mysql_query("SELECT security_code FROM sec_codes ORDER BY security_code");
		$num_groups = mysql_num_rows($result);

		if ($num_groups > 0) {
			while($GROUP = mysql_fetch_array($result)) {
				if ($GROUP['security_code'] == $NEWGROUP) { $ef = 1; }
			}
		}

      # Insert if no error
		if ( $NEWGROUP != "" && $ef != 1 ) {
			mysql_query("INSERT INTO sec_codes VALUES('NULL', '$NEWGROUP')");
			$add_alert = 1;
		}

}


if ( $_GET['send_login'] != '' ) {
	$globalprefObj->set('member-email-on-create', $_GET['send_login']);
	$report[] = 'Member preferece > automatically email login data to new members as they are created > set to <strong>'.$_GET['send_login'].'</strong>';
}

if ( $_GET['reset-message'] == 'yes' ) {
	$globalprefObj->set('custom_login_email', '');
	$report[] = 'Member login message reset';
}

if ( $_POST['custom_login_email'] != "" ) {
	$globalprefObj->set('custom_login_email', $_POST['custom_login_email']);
	$globalprefObj->set('login-email-from', $_POST['login-email-from']);
	$globalprefObj->set('login-email-subject', $_POST['login-email-subject']);
	$report[] = 'New member email message saved.';
}

#######################################################
### PROCESS "DELETE GROUP" ACTION			    ###
#######################################################
$del_alert = 0;
if ($ACTION == "DG") {
	if ($id != "") {
		mysql_query("DELETE FROM sec_codes WHERE PriKey = '$id'");
		$del_alert = 1;
	}
}


# PROCESS: Delete user(s)
function user_info($prikey, $field) {
   $qry = "select ".$field." from sec_users where PRIKEY = '".$prikey."'";
   $rez = mysql_query($qry);
   return mysql_result($rez, 0);
}
if ( count($_POST['delete_users']) > 0 ) {
   $userlist = "";
   $qry = "delete from sec_users where";
   foreach ( $_POST['delete_users'] as $key=>$prikey ) {
      $qry .= " PRIKEY = '".$prikey."' OR";
      $userlist .= user_info($prikey, "OWNER_NAME").", ";
   }
   $qry = substr($qry, 0, -3); // Strip trailing "OR"
   $userlist = substr($userlist, 0, -2); // Trailing ", "
   mysql_query($qry);
   $report[] = "<b>Users deleted:</b> ".$userlist;
}


# Start buffering output
ob_start();

#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################



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

show_hide_layer('NEWSLETTER_LAYER?header','','hide');
show_hide_layer('MAIN_MENU_LAYER?header','','hide');
show_hide_layer('CART_MENU_LAYER?header','','hide');
show_hide_layer('DATABASE_LAYER?header','','hide');
show_hide_layer('CALENDAR_MENU_LAYER?header','','hide');
show_hide_layer('SECURE_USERS_LAYER?header','','show');
//var p = "Secure Users";
//parent.frames.footer.setPage(p);

function show_help() {
	document.getElementById('HELP').style.display = '';
	document.getElementById('MAIN').style.display = 'none';
}

function exit_help() {
	document.getElementById('HELP').style.display = 'none';
	document.getElementById('MAIN').style.display = '';
}

function create_user() {
	window.location = 'security_create_user.php?<?=SID?>';
}


function confirm_del() {
	var tiny = window.confirm('You have selected to delete this security code [group].\n\nIf you proceed, any pages or products that are\nassociated with this code will become public.\n\nSelect OK to DELETE this security code now.');
	if (tiny != false) {
		document.DELFORM.submit();
	 } else {
		// Cancelled Delete operation
	 }
}

<?

if ($del_alert == 1) {
	echo "alert('The security code [group] you selected has been deleted.');\n\n";
}

if ($add_alert == 1) {
	echo "alert('Your new security code [group] has been added.');\n\n";
}

?>


//-->
</script>

<style>
select.user_dropdown {
   font-size: 14px;
}

#delete_users {
   height: 150px;
}


form {
   margin:0;
}

.feature_contain {
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

.feature_contain th {
	font: bold 11px/20px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	/*color: #4D565F;*/
	background: #D9E2E1;
	border-right: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	border-top: 1px solid #A2ADBC;
	padding:2;
}

.feature_contain td {
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

.cal_del_btn {
   margin:0;
   /*padding-top:2px;
   padding-bottom:2px;*/
   text-align: center;
   border: 2px outset #CFCFCF;
   /*border: 1px dashed red;*/
   cursor: pointer;
   background: #FF0000;
   /*width: 100%;*/
   color: #FFFFFF;
}

.cal_del_btn_over {
   /*padding-top:2px;
   padding-bottom:2px;*/
   text-align: center;
   border: 2px outset #CCCCCC;
   cursor: pointer;
   background: #FF4F4F;
   /*width: 100%;*/
   color: #FFFFFF;
}


</style>

<?
# Pull any data from "sec_users" table for edit ability
$result = mysql_query("SELECT PRIKEY, OWNER_NAME FROM sec_users ORDER BY OWNER_NAME");
$num_users = mysql_num_rows($result);

# Build user-select dropdown options (for edit and delete)
$user_dropdown_options = "";
while ( $USERS = mysql_fetch_array($result) ) {
   $user_dropdown_options .= "     <option value=\"".$USERS['PRIKEY']."\">".$USERS['OWNER_NAME']."</option>\n";
}

$THIS_DISPLAY .= "<DIV ID=\"HELP\" STYLE='display: none; font-size: 10pt;'><DIV ALIGN=RIGHT><B>[ <A HREF=\"#\" ONCLICK=\"exit_help();\">Close Diagram</a> ]</B><BR></DIV><IMG SRC=\"shared/sec_chart.gif\" border=0 wdith=566 height=432 align=absmiddle></CENTER></DIV>";

# tool-delete_users
$popup = "";
$popup .= "<p>Select one or more members (users) to delete. Careful: This is permanent and cannot be un-done. \n";
$popup .= " If you accidentally delete somebody you'll have to add them back manually via Create New User (see button on screen behind this popup).</p>\n";
$popup .= "<p>Note: Hold down your Ctrl key to select multiple users.</p>\n";
$popup .= "<form name=\"killuser_form\" action=\"".$_SERVER['PHP_SELF']."\" method=\"post\">\n";
$popup .= " <select id=\"delete_users\" name=\"delete_users[]\" class=\"user_dropdown\" multiple>\n";
$popup .= "  ".$user_dropdown_options;
$popup .= " </select>\n";
$popup .= " <p><input type=\"submit\" value=\"Delete Member(s) &gt;&gt;\" ".$_SESSION['btn_delete']."/></p>\n";
$popup .= "</form>\n";
$other['onclose'] = "show_dropdowns();";
$THIS_DISPLAY .= help_popup("tool-delete_users", "Delete one or more member accounts", $popup, "top: 15%;left: 10%;", $other);

$THIS_DISPLAY .= "<DIV ID=\"MAIN\">\n";

$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 WIDTH=100% ALIGN=CENTER>\n";
$THIS_DISPLAY .= "<TR>\n";
$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=TOP WIDTH=50%>\n";

$THIS_DISPLAY .= "<table border=0 cellpadding=4 cellspacing=0 width=\"75%\" align=center class=\"feature_contain\">\n";
$THIS_DISPLAY .= " <TR>\n";
$THIS_DISPLAY .= "  <th align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   ".lang("Authorized Users")."\n";
$THIS_DISPLAY .= "  </th>\n";
$THIS_DISPLAY .= " </TR>\n";
$THIS_DISPLAY .= " <TR>\n";
$THIS_DISPLAY .= "  <td valign=\"top\" style=\"border-right: 1px solid #A2ADBC;\">\n";

$THIS_DISPLAY .= "<br/><button type=\"button\" id=\"create_new_user\" name=\"create_new_user\" onclick=\"create_user();\" class=\"greenButton\"><span><span>".lang("Create New User")."</span></span></button><br/><br/>\n";

if ($num_users > 0) {
   $THIS_DISPLAY .= "<form name=\"edituser_form\" method=\"post\" action=\"security_create_user.php\">\n";
   $THIS_DISPLAY .= "".lang("Current Authorized Users").":<BR>\n\n";
   $THIS_DISPLAY .= "<select id=\"edit_user\" name=\"id\" class=\"text\" style=\"width: 150px;\" onchange=\"document.edituser_form.submit();\">\n";
   $THIS_DISPLAY .= " <option value=\"\">".lang("Select User")."...</option>\n";
   $THIS_DISPLAY .= $user_dropdown_options;
   $THIS_DISPLAY .= "\n</select>\n</form>\n";
//   $THIS_DISPLAY .= "<INPUT TYPE=SUBMIT VALUE=\" ".lang("Edit")." \" ".$btn_edit."></FORM>\n";
}

$THIS_DISPLAY .= "   <div style=\"text-align: center; margin-top: 10px;\"><span onclick=\"hide_dropdowns('delete_users');toggleid('tool-delete_users');\" class=\"red uline hand\">Delete multiple users</span></div>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</td>\n";

$THIS_DISPLAY .= "<td align=center valign=top width=\"50%\">\n";
$THIS_DISPLAY .= "<form name=\"mkgroupform\" method=\"post\" action=\"security.php\">\n";
$THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"NG\">\n";

$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"75%\" align=\"center\" class=\"feature_contain\">\n";
$THIS_DISPLAY .= " <TR>\n";
$THIS_DISPLAY .= "  <th align=left valign=top>\n";
$THIS_DISPLAY .= "   ".lang("Security Codes")." (Groups)\n";
$THIS_DISPLAY .= "  </th>\n";
$THIS_DISPLAY .= " </TR>\n";
$THIS_DISPLAY .= " <TR>\n";
$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\" style=\"border-right: 1px solid #A2ADBC;\">\n";

   $THIS_DISPLAY .= lang("Create New Security Code (Group)").":<br/><b>".lang("Name").":</b> <INPUT TYPE=TEXT NAME=NEWGROUP CLASS=text MAXLENGTH=15 STYLE='width: 105px;'>&nbsp;\n";
   $THIS_DISPLAY .= "<button name=\"create_group\" id=\"create_group\" type=\"button\" onClick=\"document.mkgroupform.submit();\" class=\"greenButton\"><span><span>".lang("Create Group")."</span></span></button></FORM>\n";


   // Pull any data from "sec_codes" table for display use

   $result = mysql_query("SELECT * FROM sec_codes ORDER BY security_code");
   $num_groups = mysql_num_rows($result);

   if ($num_groups > 0) {

      $THIS_DISPLAY .= "<div align=center style=\"margin-top: 15px; margin-bottom: 10px;\"><FORM NAME=DELFORM METHOD=POST ACTION=\"security.php\">\n";
      $THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"".lang("ACTION")."\" VALUE=\"DG\">\n";
      $THIS_DISPLAY .= "<U>".lang("Current Security Codes (Groups)")."</U>:<BR>\n\n";
      $THIS_DISPLAY .= "<SELECT NAME=id CLASS=text STYLE='width: 150px;'>\n";
      $THIS_DISPLAY .= "     <OPTION VALUE=\"\">".lang("Select Code")."...</OPTION>\n";

      while($GROUP = mysql_fetch_array($result)) {
         $THIS_DISPLAY .= "     <OPTION VALUE=\"$GROUP[PriKey]\">$GROUP[security_code]</OPTION>\n";
      }

      $THIS_DISPLAY .= "\n</SELECT>&nbsp;<INPUT TYPE=BUTTON VALUE=\" ".lang("Delete")." \" ONCLICK=\"confirm_del();\" class=\"cal_del_btn\" onmouseover=\"this.className='cal_del_btn_over'\" onmouseout=\"this.className='cal_del_btn'\"></FORM></DIV>\n";



   }

$THIS_DISPLAY .= "</TD></TR></TABLE>\n";


$THIS_DISPLAY .= "</TD>\n";
$THIS_DISPLAY .= "</TR></TABLE><BR><BR>\n";

$THIS_DISPLAY .= "<center><div class=text>".lang("How does this module work")."? [ <a href=\"#\" onclick=\"show_help();\">".lang("Click Here")."</a> ]</div></center>\n";
$THIS_DISPLAY .= "<br />";
$THIS_DISPLAY .= "<center><div class=text>".lang("Automatically email username and password when creating a new Member Login")."? \n";
$THIS_DISPLAY .= " <select id=\"send_login\" name=\"send_login\" onChange=\"document.location.href='security.php?send_login='+this.value;\"><option value=\"off\">off</option><option value=\"on\">on</option></select>\n";


# popup-email-message
$popupHTML = '';
$popupHTML .= 'You have two options for emailing your members their login info:<br/><br/>';
$popupHTML .= '1. You can have the Member Login feature email them automatically when you create new accounts (make sure the Automatically Email option above is turned on).<br/><br/>';
$popupHTML .= '2. You can leave the automatic option "off" and only send them their login info one at a time as needed by pushing the "Send login info to user" button within the add/edit user page (add or edit a user above).';
$THIS_DISPLAY .= help_popup('popup-email-message', 'Email your members their login info', $popupHTML, 'top: 10%;');


$THIS_DISPLAY .= "<a href=\"javascript:void(0);\" class=\"help_link\" onclick=\"showid('popup-email-message');\">[?] What's the deal with this option?</a></div>";
$THIS_DISPLAY .= "</center>\n";
$THIS_DISPLAY .= "</div>\n";

$THIS_DISPLAY .= "<script type=\"text/javascript\">document.getElementById('send_login').value = '".$globalprefObj->get('member-email-on-create')."';</script>\n";


# Default message
$msgTxt = "Hi [OWNER_NAME],\n\n";
$msgTxt .= 'We have created a member account for you so you can access protected areas of our website. See below for your username and password.'."\n\n";
$msgTxt .= "Username: [USERNAME]\n";
$msgTxt .= "Password: [PASSWORD]\n";
$msgTxt .= "\n\n";
$msgTxt .= "See our website for more...\n";
$msgTxt .= "http://".$_SESSION['this_ip']."\n\n";
$msgTxt .= "Sincerely,\n";
$msgTxt .= ucwords($_SESSION['this_ip'])." Staff\n";

if ( $globalprefObj->get('custom_login_email') == '' ) {
	$globalprefObj->set('custom_login_email', $msgTxt);
	$globalprefObj->set('login-email-from', 'noreply@'.eregi_replace('^www.', '', $_SESSION['this_ip']));
	$globalprefObj->set('login-email-subject', 'Here is your member login information');
}

$popupHTML = '<p>Note: "B", as in "BFIRSTNAME", stands for "Billing" and "S", as in "SFIRSTNAME" stands for "Shipping". So, for example, BFIRSTNAME would be the first name associated with their <em>billing</em> info under the "Member Info" tab (when you add/edit a member),';
$popupHTML .= 'whereas SFIRSTNAME would the first name associated with their <em>shipping</em> info.</p>';
$qryStr = "select * from sec_users LIMIT 1";
$rez = mysql_query($qryStr);
$fieldArr = array_keys(mysql_fetch_assoc($rez));
//$popupHTML .= testArray($fieldArr);
foreach ( $fieldArr as $key ) {
	$popupHTML .= '['.$key.']<br/>';
}
$THIS_DISPLAY .= help_popup('popinfo-user-field-list', 'User fields that you can use in your email message...', $popupHTML, 'top: 1%;');

$THIS_DISPLAY .= "<div align=\"center\" id=\"custom_email\" style=\"width:80%; display: block; margin-left: auto; margin-right: auto;\">\n";
$THIS_DISPLAY .= "<br />";
$THIS_DISPLAY .= "<table border=0 cellpadding=4 cellspacing=0 align=\"center\" style=\"width:100%;\" class=\"feature_contain\">\n";
$THIS_DISPLAY .= " <TR>\n";
$THIS_DISPLAY .= "  <th align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   ".lang("Custom Email Message")." - \n";
$THIS_DISPLAY .= "   <span style=\"font-weight: normal !important;\">".lang("Text-only (no html).")."</span>\n";
$THIS_DISPLAY .= "   <span style=\"font-weight: normal !important;\">".lang("You can include member info dynamically using [FIELDNAME].")."</span>\n";
$THIS_DISPLAY .= "   <span style=\"font-weight: normal !important;\" class=\"help_link\" onclick=\"showid('popinfo-user-field-list');\">".lang("See full field list here.")."</span>\n";
$THIS_DISPLAY .= "  </th>\n";
$THIS_DISPLAY .= " </TR>\n";
$THIS_DISPLAY .= " <TR>\n";
$THIS_DISPLAY .= "  <td valign=\"top\" style=\"border-right: 1px solid #A2ADBC;text-align: center;\">\n";

$THIS_DISPLAY .= "  <form id=\"custom-email-form\" method=\"post\">";
ob_start();
?>
<table style="width:100%;">
	<tr>
		<td style="width:55px;">From: </td>
		<td style="text-align:left;"><input type="text" id="login-email-from" name="login-email-from" value="<?php echo $globalprefObj->get('login-email-from'); ?>" style="width: 250px;"/></td>
	</tr>
	<tr>
		<td >Subject: </td>
		<td style="text-align:left;"><input type="text" id="login-email-subject" name="login-email-subject" value="<?php echo $globalprefObj->get('login-email-subject'); ?>" style="width: 250px;"/></td>
	</tr>
	<tr>
		<td valign="top">Message: </td>
		<td><textarea style="width: 100%; height: 200px;" id="custom_login_email" name="custom_login_email"><?php echo $globalprefObj->get('custom_login_email'); ?></textarea></td>
	</tr>		
</table>
<?php
$THIS_DISPLAY .= ob_get_contents();
ob_end_clean();

$THIS_DISPLAY .= "  \n";
$THIS_DISPLAY .= "<br />";

$THIS_DISPLAY .= "  <button type=\"button\" class=\"grayButton\" onclick=\"document.location='security.php?reset-message=yes';\"><span><span>".lang("Reset")."</span></span></button>\n";
$THIS_DISPLAY .= "  <button type=\"button\" class=\"greenButton\" onclick=\"document.getElementById('custom-email-form').submit();\"><span><span>".lang("Save")."</span></span></button>\n";
$THIS_DISPLAY .= "  </form>";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</div>";




echo $THIS_DISPLAY;


# Disable buttons if mod disabled
//### disable this for now
//if ( !hasMod("secure") ) {
//   echo "<script language=\"javascript\">\n";
//   echo " document.getElementById('create_group').disabled=true;\n";
//   echo " document.getElementById('create_new_user').disabled=true;\n";
//   echo "</script>\n";
//}


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Setup secure users who are authorized to view certain parts of your website.  ");
$instructions .= lang("Create security codes (groups) for these users to be assigned to.  ");
$instructions .= "<a href=\"#\" onclick=\"show_help();\">".lang("Click Here")."</a> for more information.";

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Member Logins";
$module->add_breadcrumb_link("Member Logins", "program/modules/mods_full/security.php");
$module->icon_img = "program/includes/images/member-logins-icon-med.png";
$module->heading_text = "Member Logins";
$module->description_text = $instructions;
$module->good_to_go();
?>
