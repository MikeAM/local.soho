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
require_once("../../includes/SohoEmail_class/SohoEmail.php");

eval(hook("security_create_user.php:global-stuff"));

$globalprefObj = new userdata('global');


function send_login_email($useridInt) {
	global $globalprefObj;
	$msgTxt = $globalprefObj->get('custom_login_email');
	$qry = "select * from sec_users where PRIKEY = '".$useridInt."' LIMIT 1";
	$rez = mysql_query($qry);
	
	$getUser = mysql_fetch_assoc($rez);
	$fieldnameArr = array_keys($getUser);
//	echo testArray($getUser, '500');
	
	foreach ( $fieldnameArr as $key ) {
		$msgTxt = str_replace('['.$key.']', $getUser[$key], $msgTxt);
	}
	$headers = 'From: '.$globalprefObj->get('login-email-from')."\r\n".'Reply-To: '.$globalprefObj->get('login-email-from')."\r\n" .'X-Mailer: PHP/' . phpversion();
	
	if(!SohoEmail($getUser['OWNER_EMAIL'], $globalprefObj->get('login-email-from'), $globalprefObj->get('login-email-subject'), str_replace("\n","<br/>\n", $msgTxt))){
		mail($getUser['OWNER_EMAIL'], $globalprefObj->get('login-email-subject'), $msgTxt, $headers);
	}
	$reportStr = lang('Email message sent to').' '.$getUser['OWNER_EMAIL'];	
	return $reportStr;
}


if ( $_GET['send_email'] != '' ) {
	$report[] = send_login_email($_REQUEST['id']);
}

#######################################################
### DELETE USER CHOOSEN
#######################################################

if ($ACTION == "DELUSER") {
	mysql_query("DELETE FROM sec_users WHERE PRIKEY = '$DID'");
	header("Location: security.php?=SID");
	exit;
}

function user_info($prikey, $field) {
   $qry = "select ".$field." from sec_users where PRIKEY = '".$prikey."'";
   $rez = mysql_query($qry);
   return mysql_result($rez, 0);
}

#######################################################
### FIRST THINGS FIRST.  IF THE SAVE/UPDATE FLAG IS
### PRESENT, LET'S WRITE THE NEW DATA TO THE sec_users
### DATA TABLE AND REDIRECT TO MAIN SECURITY PAGE
########################################################

if ($ACTION == "CREATE") {

   # TESTING: Build my new insert statement for me
   $qry = "SHOW COLUMNS FROM sec_users";
   $rez = mysql_query($qry);
   $insertcode = "";

   # Start building insert array
   $data = array();

   # Exclusion list -- field names that don't exactly match a post var name
   $special_vars = array("PRIKEY", "GROUPS", "EXPIRATION_DATE");

   while ( $getCol = mysql_fetch_assoc($rez) ) {
      if ( !in_array($getCol['Field'], $special_vars) ) {
         $data[$getCol['Field']] = $_POST[$getCol['Field']];
      }
   }

	// ------------------------------------------------------------
	// Let's get all variables ready to create db_table if needed
	// and format for insert
	// ------------------------------------------------------------

	$GROUPS_ARRAY = "";
	$EXPIRATION_DATE = "";

	$SQL_CREATE = "PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY, ";

//	reset($HTTP_POST_VARS);
//	while (list($name, $value) = each($HTTP_POST_VARS)) {
	foreach($_POST as $name=>$value){

		$nouse = 0;

		if ($name == "ACTION") { $nouse = 1; }
		if (eregi("EXP_", $name)) { $nouse = 1; }
		if (eregi("SEC_GROUP", $name)) { $nouse = 1; }

			if (eregi("EXP_", $name) && $value != "") {				// Compile Exp Date if in use
				$EXPIRATION_DATE .= "$value/";
			}

			if (eregi("SEC_GROUP", $name) && $value != "") {			// Compile Groups for Blob
				$groups_flag = 1;
				$GROUP_ARRAY .= "$value;";
			}

		if ($nouse == 0) {
			$field_name = strtoupper($name);
			$SQL_CREATE .= "$field_name CHAR(150), ";
		}

		${$name} = $value;	// Bug Fix 2003-05-27

	} // End While Loop

	// -------------------------------------------------------
	// Format Groups String to have NO trailing semi-colon
	// -------------------------------------------------------

	$tmp = strlen($GROUP_ARRAY);
	$end = $tmp - 1;
	$GROUPS = substr($GROUP_ARRAY, 0, $end);
	$SQL_CREATE .= "GROUPS BLOB, ";

	// -------------------------------------------------------
	// Format expiration date to have NO trailing backslash
	// -------------------------------------------------------

	$tmp = strlen($EXPIRATION_DATE);
	$end = $tmp - 1;
	$visual_date = substr($EXPIRATION_DATE, 0, $end);

	$tmp = split("/", $visual_date);
	$SQL_DATE = "$tmp[2]-$tmp[0]-$tmp[1]";	// Must format date for mySQL format for easy expiration math calc later

	$SQL_CREATE .= "EXPIRATION_DATE DATE, MD5CODE CHAR(255)";

	$tmp = $OWNER_EMAIL.$OWNER_NAME;
	$tmp = eregi_replace(" ", "", $tmp);
	$MD5CODE = md5($tmp);

	# Add formatted data to newschool insert data
	$data['GROUPS'] = $GROUPS;
	$data['EXPIRATION_DATE'] = $SQL_DATE;
	$data['MD5CODE'] = $MD5CODE;

	// -------------------------------------------------------
	// Insert Newly Formated user data into database, if not
	// created yet, then create it!
	// -------------------------------------------------------

	// mysql_query("DROP TABLE sec_users");			// DANGEROUS! FOR TESTING PURPOSES ONLY - DO NOT UNCOMMENT!

	$match = 0;
	$tablename = "sec_users";

	$result = soho_list_tables();
	$i = 0;
	while ($i < mysql_num_rows ($result)) {
		$tb_names[$i] = mysql_tablename ($result, $i);
		if ($tb_names[$i] == $tablename) {
			$match = 1;
		}
		$i++;
	}

	if ($match != 1) {
		mysql_db_query("$db_name","CREATE TABLE $tablename ($SQL_CREATE)");
	}


   # Newschool insert
   $unchk = mysql_query('select * from sec_users where upper(USERNAME) = \''.strtoupper($_POST['USERNAME']).'\'');
   if(mysql_num_rows($unchk) > 0){
		$formz = "";
		foreach($_REQUEST as $namez=>$valuez){
			if($valuez != 'submit' && $valuez != '' && $namez!='submit' && $valuez!='CREATE'){
				$formz .= "&".$namez."=".$valuez;
			}
		}
		echo "<script language=\"javascript\">\n alert('The username ".$_POST['USERNAME']." is already in use by a different user. Please select a different username.');\n\n";
		echo "location.href='security_create_user.php?form=submit".$formz."'; \n </script>\n";
   	exit;
	} else {
		if($_REQUEST['PASSWORD'] == ''){
			$formz = "";
			foreach($_REQUEST as $namez=>$valuez){
				if($valuez != 'submit' && $valuez != '' && $namez!='submit' && $valuez!='CREATE'){
					$formz .= "&".$namez."=".$valuez;
				}
			}
			echo "<script language=\"javascript\">\n alert('The password field can\'t be blank.');\n";
			//echo "<script language=\"javascript\">location.href='security_create_user.php'; \n </script>\n";
			echo "location.href='security_create_user.php?form=submit".$formz."'; \n </script>\n";
			exit;
		} elseif($_REQUEST['USERNAME'] == ''){
			$formz = "";
			foreach($_REQUEST as $namez=>$valuez){
				if($valuez != 'submit' && $valuez != '' && $namez!='submit' && $valuez!='CREATE'){
					$formz .= "&".$namez."=".$valuez;
				}
			}
			echo "<script language=\"javascript\">\n alert('The Username field can\'t be blank.');\n";
			//echo "<script language=\"javascript\">location.href='security_create_user.php'; \n </script>\n";
			echo "location.href='security_create_user.php?form=submit".$formz."'; \n </script>\n";
			exit;
		} else {
   		$dbqry = new mysql_insert("sec_users", $data);
   		$dbqry->insert();
   		
			if ( $globalprefObj->get('member-email-on-create') ) {
				send_login_email(mysql_insert_id());
			}
   		
			echo "<script language=\"javascript\">location.href='security.php'; \n </script>\n";
			exit;
   	}
	}
	// -------------------------------------------------------
	// User Added, redirect to main security setup page with
	// new user now displayed.
	// -------------------------------------------------------


	exit;

} // End Create Action


#######################################################
### PROCESS UPDATE FUNCTION
########################################################

if ($ACTION == "UPDATE") {

	// ------------------------------------------------------------
	// Let's get all variables ready to create db_table if needed
	// and format for insert
	// ------------------------------------------------------------

	$GROUPS_ARRAY = "";
	$EXPIRATION_DATE = "";

	$SQL_UPDATE = "";

//	reset($HTTP_POST_VARS);
//	while (list($name, $value) = each($HTTP_POST_VARS)) {
	foreach($_POST as $name=>$value){

		$nouse = 0;

		if ($name == "ACTION") { $nouse = 1; }
		if (eregi("EXP_", $name)) { $nouse = 1; }
		if (eregi("SEC_GROUP", $name)) { $nouse = 1; }
		if (eregi("PRIKEY", $name)) { $nouse = 1; $PRIKEY = $value; }
		if ( eregi("id", $name) ) { $nouse = 1; }

eval(hook("security_create_user.php:field-check"));

			if (eregi("EXP_", $name) && $value != "") {				// Compile Exp Date if in use
				$EXPIRATION_DATE .= "$value/";
			}

			if (eregi("SEC_GROUP", $name) && $value != "") {			// Compile Groups for Blob
				$groups_flag = 1;
				$GROUP_ARRAY .= "$value;";
			}

		if ($nouse == 0) {
			$field_name = strtoupper($name);
			${$field_name} = $value;							// On update routine, force the reset of the value for session
			$SQL_UPDATE .= "$field_name = '$value', ";
		}

	} // End While Loop

	// -------------------------------------------------------
	// Format Groups String to have NO trailing semi-colon
	// -------------------------------------------------------

	$tmp = strlen($GROUP_ARRAY);
	$end = $tmp - 1;
	$GROUPS = substr($GROUP_ARRAY, 0, $end);
	$SQL_UPDATE .= "GROUPS = '$GROUPS', ";

	// -------------------------------------------------------
	// Format expiration date to have NO trailing backslash
	// -------------------------------------------------------

	$tmp = strlen($EXPIRATION_DATE);
	$end = $tmp - 1;
	$visual_date = substr($EXPIRATION_DATE, 0, $end);

	$tmp = split("/", $visual_date);
	$SQL_DATE = "$tmp[2]-$tmp[0]-$tmp[1]";	// Must format date for mySQL format for easy expiration math calc later

	if ($SQL_DATE == "0000-00-00") { $SQL_DATE = "NULL"; }

	$SQL_UPDATE .= "EXPIRATION_DATE = '$SQL_DATE'";

	// -------------------------------------------------------
	// Insert Newly Formated user data into database, if not
	// created yet, then create it!
	// -------------------------------------------------------
   $GOODTOGO = 1;
   $unchk = mysql_query('select * from sec_users where upper(USERNAME) = \''.strtoupper($_POST['USERNAME']).'\'');

   while($unchka = mysql_fetch_array($unchk)){

	   if ( $_POST['id'] != $unchka['PRIKEY'] && $_POST['PRIKEY'] != $unchka['PRIKEY'] ) {
	   	$GOODTOGO = 0;
	   }
	}

   if($GOODTOGO != 1){
		$formz = "";
		foreach($_POST as $namez=>$valuez){
			if($valuez != 'submit' && $valuez != '' && $namez!='submit' && $namez!='ACTION'){
				$formz .= "&".$namez."=".$valuez;
			}
		}
		$formz .= "&id=".$_REQUEST['PRIKEY'];
//		$formz .= "&PRIKEY=".$_REQUEST['PRIKEY'];
		echo "<script language=\"javascript\">\n alert('The username ".$_POST['USERNAME']." is already in use by a different user. Please select a different username.');\n\n";
		echo "location.href='security_create_user.php?form=submit".$formz."'; \n </script>\n";
   	exit;
	} else {
		if($_POST['PASSWORD'] == ''){
			$formz = "";
			foreach($_POST as $namez=>$valuez){
				if($valuez != 'submit' && $valuez != '' && $namez!='submit' && $namez!='ACTION'){
					$formz .= "&".$namez."=".$valuez;
				}
			}
		$formz .= "&id=".$_REQUEST['PRIKEY'];
//		$formz .= "&PRIKEY=".$_REQUEST['PRIKEY'];
			echo "<script language=\"javascript\">\n alert('The password field can\'t be blank.');\n";
			//echo "<script language=\"javascript\">location.href='security_create_user.php'; \n </script>\n";
			echo "location.href='security_create_user.php?form=submit".$formz."'; \n </script>\n";
			exit;
		} else {
			mysql_query("UPDATE sec_users SET $SQL_UPDATE WHERE PRIKEY = '$PRIKEY'");
			header("Location: security.php?=SID");
			exit;
   	}
	}


	// echo '<TT>mysql_query("UPDATE sec_users SET '.$SQL_UPDATE.' WHERE PRIKEY = \''.$PRIKEY.'\'");';
	// exit;

	// -------------------------------------------------------
	// User Updated, redirect to main security setup page with
	// update alert.
	// -------------------------------------------------------




} // End Update Action


#######################################################
### IN CASE OF DEVELOPER TESTING,
### AND THIS WILL HAPPEN, THERE IS A CHANCE THAT THE
### VARIABLES USED HERE WILL BE "ALIVE" IN A PREVIOUS
### SESSION VIA THE CLIENT INTERFACE.  THEREFORE, WE
### WANT TO UNREGISTER THOSE VARS NOW.  THIS HAPPENS
### BECAUSE WE ARE UTILIZING THE SAME .INC HTML FILE
### THAT WE USE FOR THE CHECKOUT ROUTINE.
########################################################

		$_SESSION['BFIRSTNAME'] = '';
		$_SESSION['BLASTNAME'] = '';
		$_SESSION['BCOMPANY'] = '';
		$_SESSION['BADDRESS1'] = '';
		$_SESSION['BADDRESS2'] = '';
		$_SESSION['BCITY'] = '';
		$_SESSION['BSTATE'] = '';
		$_SESSION['BCOUNTRY'] = '';
		$_SESSION['BZIPCODE'] = '';
		$_SESSION['BEMAILADDRESS'] = '';

		$_SESSION['BPHONE_COUNTRYCODE'] = '';
		$_SESSION['BPHONE_AREACODE'] = '';
		$_SESSION['BPHONE_PREFIX'] = '';
		$_SESSION['BPHONE_SUFFIX'] = '';


		$_SESSION['SFIRSTNAME'] = '';
		$_SESSION['SLASTNAME'] = '';
		$_SESSION['SCOMPANY'] = '';
		$_SESSION['SADDRESS1'] = '';
		$_SESSION['SADDRESS2'] = '';
		$_SESSION['SCITY'] = '';
		$_SESSION['SSTATE'] = '';
		$_SESSION['SCOUNTRY'] = '';
		$_SESSION['SZIPCODE'] = '';

		$_SESSION['SPHONE_COUNTRYCODE'] = '';
		$_SESSION['SPHONE_AREACODE'] = '';
		$_SESSION['SPHONE_PREFIX'] = '';
		$_SESSION['SPHONE_SUFFIX'] = '';

		$_SESSION['REPEATCUSTOMER'] = '';
		$_SESSION['PRIKEY'] = '';



		unset($PRIKEY);

		// ----------------------------------------------------------------------

#######################################################
### IF THIS IS AN EDIT CALL ($id) WILL BE POPULATED ###
#######################################################

if (isset($_REQUEST['id'])) {

	$result = mysql_query("SELECT * FROM sec_users WHERE PRIKEY = '$id'");
	$user_data = mysql_fetch_array($result);
	$numberFields = mysql_num_fields($result);
	$numberFields--;

	for ($x=0;$x<=$numberFields;$x++) {
		$field_name = mysql_field_name($result, $x);		
		if($field_name == ''){ $_SESSION[$field_name] = $user_data[$field_name]; }
		${$field_name} = $user_data[$field_name];
	}

}

# Start buffering output
ob_start();
?>



<STYLE>

.text { font-family: Arial; font-size: 8pt; }
.smtext { font-family: Arial; font-size: 8pt; }

	.tab {
		background-color: #EAA510;
		color: #000000;
		font-size: 7pt;
		cursor: hand;
		width: 175px;
		border-left: inset black 1px;
		border-top: inset black 1px;
		border-right: inset black 1px;
		border-bottom: inset #EAA510 1px;
		}


.tab-off, .tab-on {
   text-align: center;
   width: 175px;
   height: 18px;
   vertical-align: top;
   padding: 5px 10px;
   background-color: #efefef;
   border: 1px solid #ccc;
   border-top: 3px solid #ccc;
   border-bottom: 0;
   color: #595959;
   cursor: pointer;
}

.tab-on {
   color: #000;
   background-color: #efefef;
   border-top: 3px solid #EAA510;
   font-weight: bold;
}


</STYLE>

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

SV2_showHideLayers('NEWSLETTER_LAYER?header','','hide');
SV2_showHideLayers('MAIN_MENU_LAYER?header','','hide');
SV2_showHideLayers('CART_MENU_LAYER?header','','hide');
SV2_showHideLayers('DATABASE_LAYER?header','','hide');
SV2_showHideLayers('SECURE_USERS_LAYER?header','','show');


function show(s,h) {

	all_reset();
	
	eval("document.getElementById('"+s+"').style.display = '';");
	eval("document.getElementById('"+h+"').style.display = 'none';");
	
	


	if (s == "GEN") { btn = "AUTHBUT"; }
	if (s == "CART") { btn = "USERBUT"; }

//	eval("document.CHECKOUT1."+btn+".style.background = '#EAA510';");
//	eval("document.CHECKOUT1."+btn+".style.color = '#000000';");
//	eval("document.CHECKOUT1."+btn+".style.border = 'solid #black 1px';");

}

function all_reset() {
	
	document.getElementById('GEN').style.display = '';
	document.getElementById('CART').style.display = 'none';
//
//	document.CHECKOUT1.AUTHBUT.style.background = "#EFEFEF";
//	document.CHECKOUT1.AUTHBUT.style.color = "#000000";
//	document.CHECKOUT1.AUTHBUT.style.border = "inset #EFEFEF 1px";
//
//	document.CHECKOUT1.USERBUT.style.background = "#EFEFEF";
//	document.CHECKOUT1.USERBUT.style.color = "#000000";
//	document.CHECKOUT1.USERBUT.style.border = "inset #EFEFEF 1px";

}

function del_user(id) {
	<? echo "var tiny = window.confirm('".$lang["You have selected to delete this authorized user."]."\\n\\n".$lang["THIS PROCESS CAN NOT BE REVERSED"]."!\\n\\n".$lang["Select OK to DELETE this user now."]."');\n"; ?>
	if (tiny != false) {
		window.location = 'security_create_user.php?ACTION=DELUSER&DID='+id+'&<?=SID?>';
	 } else {
		// Cancelled Delete operation
	 }
}


//-->
</script>


<FORM id="create_user_form" NAME=CHECKOUT1 METHOD=POST ACTION="security_create_user.php">

<?

$SAVE_BTN = "Save New User";

if ( $_REQUEST['id'] != "" ) {
   $_REQUEST['PRIKEY'] = $_REQUEST['id'];
   $PRIKEY = $_REQUEST['id'];
}

if ( isset($_REQUEST['PRIKEY']) != "" ) {
	echo "<!-- UPDATE ROUTINE OVER-RIDES CREATE ROUTINE -->\n\n";
	echo "<INPUT TYPE=HIDDEN NAME=PRIKEY VALUE=\"$PRIKEY\">\n";
	echo "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"UPDATE\">\n\n";
	echo "<!-- ---------------------------------------- -->\n\n";
	$SAVE_BTN = $lang["Save Changes"];
} else {
   echo "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"CREATE\">\n";
}

// --------------------------------------------------------------------------------------------------
// SETUP TOP NAVIGATION TAB STRUCTURE TO HIDE BILLING/SHIPPING DATA FOR NON-USERS OF THIS OPTION
// OR THOSE THAT COULD CARE LESS
// --------------------------------------------------------------------------------------------------

$THIS_DISPLAY .= "<TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" WIDTH=\"700\">\n";
$THIS_DISPLAY .= "<TR>\n\n";
$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\" colspan=\"4\">\n";

	$THIS_DISPLAY .= "<div  style=\"padding-bottom:5px;\" align=right>\n";
	if ( $_REQUEST['id'] != "" ) {
		//$THIS_DISPLAY .= "	<INPUT TYPE=BUTTON VALUE=\"".lang('Send login info to user')."\" onclick=\"document.location.href='security_create_user.php?id=".$_REQUEST['id']."&send_email='+document.getElementById('OWNER_EMAIL').value;\" class=\"btn_edit\" onMouseover=\"this.className='btn_editon';\" onMouseout=\"this.className='btn_edit';\">&nbsp;&nbsp;&nbsp;&nbsp;\n";
		$THIS_DISPLAY .= "	<BUTTON class=\"blueButton\" TYPE=BUTTON onclick=\"document.location.href='security_create_user.php?id=".$_REQUEST['id']."&send_email='+document.getElementById('OWNER_EMAIL').value;\"><span><span>".lang('Send login info to user')."</span></span></button>&nbsp;&nbsp;&nbsp;&nbsp;\n";
	}
	$THIS_DISPLAY .= "	<button onClick=\"document.CHECKOUT1.submit();\"type=\"button\" value=\"$SAVE_BTN\" class=\"greenButton\"><span><span>$SAVE_BTN</span></span></button> &nbsp;&nbsp;&nbsp;&nbsp;\n";
//		$THIS_DISPLAY .= "	<INPUT TYPE=SUBMIT VALUE=\" $SAVE_BTN \" ".$btn_save."> &nbsp;&nbsp;&nbsp;&nbsp;\n";

		if (isset($PRIKEY)) {
			$THIS_DISPLAY .= "	<button type=\"button\" class=\"redButton\" onclick=\"del_user('$PRIKEY');\"><span><span>".$lang["Delete User"]."</span></span></button> &nbsp;&nbsp;&nbsp;&nbsp;\n";
		}

		$THIS_DISPLAY .= "	<button type=\"button\" class=\"redButton\" onclick=\"cancel_user();\"><span><span>".$lang["Cancel"]."</span></span></button>\n";
      $THIS_DISPLAY .= "</div>\n\n";



$THIS_DISPLAY .= "</TD></TR>\n";
$THIS_DISPLAY .= "<TR>\n";
      //$THIS_DISPLAY .= "   <td id=\"AUTHBUT\" class=\"tab-on\" onclick=\"showid('recent_table_list');setClass('tab-recent', 'tab-on');hideid('udt_table_list');hideid('system_table_list');setClass('tab-system', 'tab-off');setClass('tab-udt', 'tab-off');\">\n";
      $THIS_DISPLAY .= "   <td id=\"AUTHBUT\" class=\"tab-on\" onclick=\"show('GEN','CART');setClass('AUTHBUT', 'tab-on');hideid('CART');setClass('USERBUT', 'tab-off');\">\n";
      
      $THIS_DISPLAY .= $lang["Authentication Info"];
      //$THIS_DISPLAY .= "    ".str_replace(" ", "&nbsp;", lang("Recent tables"))."\n";
      $THIS_DISPLAY .= "   </td>\n";
      
      $THIS_DISPLAY .= "   <td style=\"width:10px;\">&nbsp;</td>\n";
      
      $THIS_DISPLAY .= "   <td id=\"USERBUT\" class=\"tab-off\" onclick=\"show('CART','GEN');setClass('USERBUT', 'tab-on');hideid('GEN');setClass('AUTHBUT', 'tab-off');\">\n";
      $THIS_DISPLAY .= $lang["User Info"];
      //$THIS_DISPLAY .= "    ".str_replace(" ", "&nbsp;", lang("Recent tables"))."\n";
      $THIS_DISPLAY .= "   </td>\n";
	//$THIS_DISPLAY .= "<INPUT ID=AUTHBUT TYPE=BUTTON VALUE=\"".$lang["Authentication Info"]."\" CLASS=tab onclick=\"show('GEN','CART');\">&nbsp;\n";
	//$THIS_DISPLAY .= "<INPUT ID=USERBUT TYPE=BUTTON VALUE=\"".$lang["User Info"]."\" CLASS=tab onclick=\"show('CART','GEN');\">&nbsp;\n";

$THIS_DISPLAY .= "<td>&nbsp;</TD></TR></TABLE>\n\n";

// --------------------------------------------------------------------------------------------------


$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">\n";
$THIS_DISPLAY .= "<tr>\n";
$THIS_DISPLAY .= "<td align=\"center\" valign=\"top\">\n";

		$THIS_DISPLAY .= "<div id=\"GEN\">\n\n";

		ob_start();
			include("shared/sec_user_form.inc");
			$THIS_DISPLAY .= ob_get_contents();
		ob_end_clean();

		$THIS_DISPLAY .= "</div>\n\n";

		// --------------------------------------------------------------------------
		// Allow authorized user to be "pre-remembered" as a shopping cart customer
		// --------------------------------------------------------------------------

		$THIS_DISPLAY .= "<div id=\"CART\" style='display: none;'>\n\n";

		ob_start();
			include("shared/sec_billing_shipping.inc");
			$THIS_DISPLAY .= ob_get_contents();
		ob_end_clean();

		$THIS_DISPLAY .= "</div>\n\n";

		// --------------------------------------------------------------------------

$THIS_DISPLAY .= "</td>\n";
$THIS_DISPLAY .= "</tr></table><br/><br/>\n";

echo $THIS_DISPLAY;




echo "<script language=\"javascript\">\n";
echo "	show('GEN','CART');\n";

if (isset($id)) {

	$GROUPS = eregi_replace(",", ";", $GROUPS);	// 4.5.3 Bug Fix - Fix export/re-import bug

	$tmp = split(";", $GROUPS);
	$tmp_cnt = count($tmp);
	for ($x=0;$x<=$tmp_cnt;$x++) {
		if ($tmp[$x] != "") {
			$NAME = "SEC_GROUP".$x;
			echo "document.CHECKOUT1.$NAME.value = '".$tmp[$x]."';\n";
		}
	}

	$tmp_date = split("-", $EXPIRATION_DATE);
	echo "document.CHECKOUT1.EXP_MONTH.value = '$tmp_date[1]';\n";
	echo "document.CHECKOUT1.EXP_DAY.value = '$tmp_date[2]';\n";
	echo "document.CHECKOUT1.EXP_YEAR.value = '$tmp_date[0]';\n";

	echo "document.CHECKOUT1.REDIRECT_PAGE.value = '$REDIRECT_PAGE';\n";

} // End If Update Check

echo "</SCRIPT>\n";
echo "</div>\n";


if ( $_REQUEST['id'] == "" && $PRIKEY != "" ) {
   $_REQUEST['id'] = $PRIKEY;
}


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

# Build into standard module template
$module = new smt_module($module_html);
$module->add_breadcrumb_link("Member Logins", "program/modules/mods_full/security.php");

# Edit or Create?
if ( $_REQUEST['id'] != "" ) {
   # EDIT
   $module->meta_title = lang("Edit").": ".user_info($_REQUEST['id'], "OWNER_NAME");
   $module->heading_text = user_info($_REQUEST['id'], "OWNER_NAME");

   $instructions = lang("Edit information, settings, and group associations for this member (user) account.");
   $breadcrumb_text = lang("Edit User").": <span class=\"unbold\">(".user_info($_REQUEST['id'], "OWNER_NAME").")</span>";
	$module->add_breadcrumb_link($breadcrumb_text, "program/modules/mods_full/security_create_user.php?id=".$_REQUEST['id']);
} else {
   # CREATE
   $module->heading_text = "Create new user account";
	$module->add_breadcrumb_link($breadcrumb_text, "program/modules/mods_full/security_create_user.php");
   $instructions = lang("Fill-in at least some essential info for this user, assign a username/password to log-in to your site with, and associate the user with at least one security group.");
   $breadcrumb_text = lang("Create New User");
}


$module->icon_img = "program/includes/images/member-logins-icon-med.png";
$module->description_text = $instructions;
$module->good_to_go();
		$BFIRSTNAME = "";
		$BLASTNAME = "";
		$BCOMPANY = "";
		$BADDRESS1 = "";
		$BADDRESS2 = "";
		$BCITY = "";
		$BSTATE = "";
		$BCOUNTRY = "";
		$BZIPCODE = "";
		$BEMAILADDRESS = "";

		$SFIRSTNAME = "";
		$SLASTNAME = "";
		$SCOMPANY = "";
		$SADDRESS1 = "";
		$SADDRESS2 = "";
		$SCITY = "";
		$SSTATE = "";
		$SCOUNTRY = "";
		$SZIPCODE = "";

		$BPHONE_SUFFIX = "";;
		$BPHONE_PREFIX = "";
		$BPHONE_AREACODE = "";
		$BPHONE_COUNTRYCODE = "";

		$SPHONE_SUFFIX = "";;
		$SPHONE_PREFIX = "";
		$SPHONE_AREACODE = "";
		$SPHONE_COUNTRYCODE = "";

		$OWNER_NAME = "";
		$OWNER_EMAIL = "";
		$USERNAME = "";
		$PASSWORD = "";
		$EXP_MONTH = "";
		$EXP_DAY = "";
		$EXP_YEAR = "";
		$PRIKEY = "";
		$REDIRECT_PAGE = "";
?>
