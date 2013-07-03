<?php
error_reporting('341');
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

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// By License you may not modify any portion of this script. This particular
// script has dependancies and programming that can not be modified.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


session_start();

include_once("includes/autoconfig.php");
include_once("program/includes/SohoEmail_class/SohoEmail.php");
if(!isset($_SESSION['keystroke_login'])){ $_SESSION['keystroke_login'] = $keystroke_login; }

if ($keystroke == "on") {
	$keystroke_login = $keystroke;
}

# Pull webmaster preferences
$webmaster_prefs = new userdata('webmaster_pref');

############################################################################
### DETERMINE WHAT DIRECTORY THE APPLICATION IS RUNNING IN ON THE SERVER ###
############################################################################

$directory = $_SESSION['docroot_path'];
$msg = array();

if (is_dir($directory)) {
   $handle = opendir("$directory");
   while ($files = readdir($handle)) {
      $this_file = $files;
      $files = $directory . "/$files";
      if (is_dir($files) && strlen($files) > 2) {
         $tmp = opendir("$files");
         while ($test_files = readdir($tmp)) {
            if ($test_files == "version.php") {
               $INSTALL_DIR = $this_file;
            }
         }
      }
   }

   closedir($handle);

//	if (eregi($_SERVER["HTTP_HOST"], $_SESSION['this_ip']) || eregi($_SESSION['this_ip'], $_SERVER["HTTP_HOST"])) {
//		$_SESSION['this_ip'] = $_SERVER["HTTP_HOST"];
//		$this_ip = $_SERVER["HTTP_HOST"];
//	}

	if(!is_dir('../shopping')){
	//if(basename($_SERVER['HTTP_REFERER'])=='index.php'){
		$msg[] = "<p><span style=\"color:red;\">*<b>".lang("Important").":</b> </span>".lang("Bookmark this page for for future use. This is the login page for your new website! ")."</p>\n";
	}


   if (isset($_SERVER['HTTPS']) & $_SERVER['HTTPS'] == 'on' ) {
      $https = "on";
      } else { $https = "off";
   }

   if ( $https == "on" ) {
      $submit_form = "https://".$_SESSION['this_ip']."/sohoadmin/".basename($_SERVER['PHP_SELF']);
   } else {
      $submit_form = "http://".$_SESSION['this_ip']."/sohoadmin/".basename($_SERVER['PHP_SELF']);
   }
}

//if ( $_POST['jubjub'] == "DownloadVBS" ) {
//   $docroot = str_replace(basename(__FILE__), "", __FILE__);
//	 $docfolder = eregi_replace("sohoadmin/includes/", '', $docroot);
//   $VBSfilepath = $_SESSION['docroot_path']."/sohoadmin/config/ResolveDomain.vbs";
//   $filePathvbs = $docroot."ResolveDomain.vbs";
//   $fileSize = filesize($VBSfilepath);
//   if ( strstr($HTTP_USER_AGENT, "MSIE") ) {
//      $attachment = "";
//   } else {
//      $attachment = "attachment;";
//   }
//
//	header("Content-Description: File Download");
//	header("Content-Length: $fileSize");
//	header("Content-Type: application/force-download");
//	header("Content-Disposition: $attachment; filename=\"resolvedomain.vbs\"");
//	echo file_get_contents($VBSfilepath); exit;
//
//}

# Email password to default email address
//if ( $_POST['todo'] == "send_password" && $webmaster_prefs->get("forgotpw") != "no" ) {


if($_POST['todo'] == "send_password"){
	$forgot_timer = $webmaster_prefs->get("forgotpw_timer");
	if($_SESSION['email_hint']==''){
		$_SESSION['email_hint'] = $webmaster_prefs->get("forgotpw_emailhint");
	}
	$recover_timer_mins = .1;

	if($forgot_timer != "" && $forgot_timer > (time() - ($recover_timer_mins *60)) ){
		$howlong = round((time() - $forgot_timer)/60);
		if($howlong == 1){ $minlang = 'minute'; } else { $minlang = 'minutes'; }
		$msgt .= "Your login info has was emailed to you ".$howlong." ".$minlang." ago.\n";
		$msgt .= "<p><b>".lang("Note").":</b> ".lang("The email was sent to the main email address for this site.")." <br/>(Hint: ".$_SESSION['email_hint'].")</p> \n";
		$msgt .= "<p>*".lang("If don't see the email in your inbox, check your Junk folder.")."</p> \n";
		$msg[] = $msgt;
	} else {
		unset($my_email_ar['df_email']);
		# Pull password from db
		$qry = "SELECT Username,Email,Password FROM login WHERE First_Name = 'WEBMASTER' limit 1";
		$rez = mysql_query($qry);
		$getLogin = mysql_fetch_assoc($rez);	   
		if(mysql_num_rows($rez) == 1){
			$my_email = str_replace(' ','',$getLogin['Email']);
			
			# Build forgot my password email
			$this_web_ip = $_SESSION['this_ip'];
			$from_addr = "From: noreply@".preg_replace('/^www\./i', '', $this_web_ip)."\n";
			$email_header = "";
			$email_header .= "Content-Type: text/html; charset=us-ascii;\n";
			$email_header .= "Content-Transfer-Encoding: 7bit\n";
			$email_header .= "Content-Disposition: inline;\n\n";
			
			$email_msg = "".lang("You are receiving this message because somebody (presumably yourself) clicked the 'Email my login info to me' link")." ";
			$email_msg .= "".lang("on the sitebuilder login screen for your website")." (".$this_web_ip.").<br/><br/>";
			
			$email_msg .= "".lang("Your login information is")."...<br/>\n";
			$email_msg .= "".lang("USERNAME").": <b>".$getLogin['Username']."</b><br/>\n";
			$email_msg .= "".lang("PASSWORD").": <b>".$getLogin['Password']."</b><br/>\n";
			# Send email now
			if(SohoEmail($my_email, preg_replace('/^www\./i', '', $this_web_ip), $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg)){
//			if(mail($my_email, $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg, $from_addr.$email_header)){
				$email_hint = $my_email[0].'****'.preg_replace('/.*@/i', '@', $my_email);
				$msgt .= lang("Your login info has been emailed to you.")."\n";
				$msgt .= "<p><b>".lang("Note").":</b> ".lang("The email was sent to the main email address for this site.")." <br/>(Hint: ".$email_hint.")</p> \n";
				$msgt .= "<p>*".lang("If don't see the email in your inbox, check your Junk folder.")."</p> \n";
				$msg[] = $msgt;
			} else {
//				if(mail($my_email, $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg, $email_header)){
				if(SohoEmail($my_email, preg_replace('/^www\./i', '', $this_web_ip), $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg)){
					$email_hint = $my_email[0].'****'.preg_replace('/.*@/i', '@', $my_email);
					$msgt .= lang("Your login info has been emailed to you.")."\n";
					$msgt .= "<p><b>".lang("Note").":</b> ".lang("The email was sent to the main email address for this site.")." <br/>(Hint: ".$email_hint.")</p> \n";
					$msgt .= "<p>*".lang("If don't see the email in your inbox, check your Junk folder.")."</p> \n";
					$msg[] = $msgt;
				} else {
//					if(mail($my_email, $this_web_ip." Ultra ".lang("sitebuilder login info"), str_replace('</b>','',str_replace('<b>','',str_replace('<br/>',"",$email_msg))))){
					if(SohoEmail($my_email, preg_replace('/^www\./i', '', $this_web_ip), $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg)){
						$email_hint = $my_email[0].'****'.preg_replace('/.*@/i', '@', $my_email);
						$msgt .= lang("Your login info has been emailed to you.")."\n";
						$msgt .= "<p><b>".lang("Note").":</b> ".lang("The email was sent to the main email address for this site.")." <br/>(Hint: ".$email_hint.")</p> \n";
						$msgt .= "<p>*".lang("If don't see the email in your inbox, check your Junk folder.")."</p> \n";
						$msg[] = $msgt;
					} else {
							$my_emailq = mysql_query("select df_email from site_specs limit 1");
							$my_email_ar = mysql_fetch_assoc($my_emailq);
							$my_email = $my_email_ar['df_email'];
							$email_hint = $my_email[0].'****'.preg_replace('/.*@/i', '@', $my_email);
							//mail($my_email, $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg, $from_addr.$email_header);
							SohoEmail($my_email, preg_replace('/^www\./i', '', $this_web_ip), $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg);
							SohoEmail($getLogin['Username'], preg_replace('/^www\./i', '', $this_web_ip), $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg);
							//mail($getLogin['Username'], $this_web_ip." Ultra ".lang("sitebuilder login info"), $email_msg, $from_addr.$email_header);
							$msgt .= lang("Your login info has been emailed to you.")."\n";
							$msgt .= "<p><b>".lang("Note").":</b> ".lang("The email was sent to the main email address for this site.")." <br/>(Hint: ".$email_hint.")</p>\n";
							$msgt .= "<p>*".lang("If don't see the email in your inbox, check your Junk folder.")."</p> \n";
							$msg[] = $msgt;
					}
				}
			}

			if($email_hint!=''){
				$_SESSION['email_hint'] = $email_hint;
				$webmaster_prefs->set("forgotpw_emailhint", $email_hint);
			}
			$webmaster_prefs->set("forgotpw_timer", time());
		}
	}
} // End if todo = send_password


if(($spanish=='yes' || $_SESSION['spanish']=='yes')&&$_REQUEST['lang']!='es'&& count($_POST)==0){
	header("Location:index.php?lang=es#googtrans(en|es)");
	exit;
}
?>
<!doctype html>
<html>
<head>
<title><? echo $_SESSION['this_ip']; ?>: Log-in to manage your website</title>
<?php

$globalprefObj = new userdata('global');
if($globalprefObj->get('goog_trans')!='off'){
	echo display_google_translate();
}
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=ut"."f-8\">\n";
?>
<script language="javascript">
<?php

if ($keystroke_login == "on") {

	echo "	var width = (screen.width);
			var height = (screen.height - 25);
			var centerleft = 0;
			var centertop = 0;
			var centerleft = (width/2) - (560/2);
			var centertop = (height/2) - (300/2);
			var width=560;
			var height=300;
			window.moveTo(centerleft,centertop);
			window.resizeTo(width, height);
			window.focus();\n\n";

} // End If

?>


</script>

<link rel="stylesheet" type="text/css" href="program/product_gui.css"/>
<link rel="stylesheet" type="text/css" href="program/includes/product_buttons-ultra.css"/>

<style type="text/css">
body {
   background-image: url('skins/default/getlogin_bg_gradient.png');
}
ul {
	list-style: none;
	margin: -3px 2px 0px 0px;
	padding: 10px;
	padding: 0px;
}
ul li {
	padding: 3px 0px 6px 15px;
	background: url('skins/default/getlogin_bullet.gif') no-repeat left top;
}

#login-box {
	width: 450px;
	margin: 10% auto;
	background-color: #fff;
	padding: 20px 20px 0 20px;
	border-radius: 4px;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;
	box-shadow: 3px 3px 6px #5e5e5e;
	-moz-box-shadow: 3px 3px 6px #5e5e5e;
	-webkit-box-shadow: 3px 3px 6px #5e5e5e;
}

#logo {
	position: relative;
	padding-bottom: 15px;
	width: 280px;
	height: 60px;
	/*border: 1px solid red;*/
	margin: 0px auto;
}
#logo h1 {
	position: absolute;
	margin: 0;
	padding: 0;
	left: 77px;
	top: 55px;
	font-size: 12px;
	font-weight: bold;
	color: #484848;
}

label {
	font-size: 14px;
	color: #484848;
	font-weight: bold;
}

input.text {
	width: 240px;
	font-size: 14px;
	border: 1px solid #888c8e;
	border-radius: 2px;
	-moz-border-radius: 2px;
	-webkit-border-radius: 2px;
	font-weight: normal;
}

.message, .error { 
	padding: 6px 3px;
	vertical-align: top;
	text-align: center; 
	margin: 7px;
	border: 2px solid #ccc;
	border-radius: 4px;
	-moz-border-radius: 4px;
	-webkit-border-radius: 4px;	
}

.message p, .error p { 
	margin-bottom: 0;
}

.message p:first-child, .error p:first-child { 
	margin-top: 0;
}

.error {
	border-color: #980000;
	background: #ffe2e2;
}

.message {
	border-color: #669959;
	background-color: #e2ffec;
}

.button-container { 
	margin: 15px 0 0;
	text-align: right; 
	padding-right: 60px;
}

button { font-weight: bold !important; }

#help-links {
	padding: 15px;
	background: #fff8bc;
	margin: 15px -20px 0 -20px;
	text-align: center;
	border-bottom-left-radius: 4px;
	border-bottom-right-radius: 4px;
	-moz-border-bottom-left-radius: 4px;
	-moz-border-bottom-right-radius: 4px;	
	-webkit-border-bottom-left-radius: 4px;
	-webkit-border-bottom-right-radius: 4px;		
}
</style>
</HEAD>
<?php
$DOMAIN = $_SESSION['this_ip'];
$IP = $_SERVER['SERVER_ADDR'];
$HOST = $IP."     ".$DOMAIN;
$host = php_uname(n);
$hostip = gethostbyname($host);
$TIP = gethostbyname($_SERVER['HTTP_HOST']);
$addr = gethostbyaddr($TIP);
$gethost = gethostbyname($addr);
$HostIPs = gethostbyname($_SERVER['HTTP_HOST']);

if ( $IP == $HostIPs) {
	$resolved = "yes";
} else {
	if ( $gethost == $hostip ) {
		$resolved = "yes";
	} else {
		$resolved = "no";
	}

}
?>

<body onload="document.getElementById('PHP_AUTH_USER').focus();" >

<script language="javascript">
function loginNow() {
		document.getElementById('PHP_AUTH_USER').style.border='1px solid #7F9DB9';
		document.getElementById('PHP_AUTH_PW').style.border='1px solid #7F9DB9';
	if(document.getElementById('PHP_AUTH_USER').value=='' || document.getElementById('PHP_AUTH_USER').value==null){
		document.getElementById('PHP_AUTH_USER').style.border='1px solid red';
		document.getElementById('PHP_AUTH_USER').focus();
	} else if(document.getElementById('PHP_AUTH_PW').value=='' || document.getElementById('PHP_AUTH_PW').value==null){
		document.getElementById('PHP_AUTH_PW').style.border='1px solid red';
		document.getElementById('PHP_AUTH_PW').focus();
	} else {
		document.login.submit();
	}
}
</script>



<form name="recover" method="POST" action="<?= $submit_form; ?>">
	<input type="hidden" name="todo" value="send_password">
</form>


<form name="login" method="post" action="<?= $submit_form; ?>">
	<input type="hidden" name="process" value="1">

	<!---parent table-->
	<div id="login-box">
		<div id="logo">
			<img src="program/includes/images/soholaunch-logo-noslogan.png"/>
			<h1>Log-in to manage your website.</h1>
		</div>
		
		<table width="300" align="center" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td width="20%" align="right"><label><?=lang("Username"); ?>:</label></td>
				<td valign="top"><input type="text" class="text" id="PHP_AUTH_USER" name="PHP_AUTH_USER" value="<?=$_POST['PHP_AUTH_USER']; ?>" onKeypress="if(document.getElementById('PHP_AUTH_USER').value!=''&&document.getElementById('PHP_AUTH_PW').value!=''){if(event.keyCode==13||event.charCode==13){loginNow();}};"/></td>
			</tr>
		
			<tr>
				<td align="right"><label><?=lang("Password"); ?>:</label></td>
				<td valign="top"><input type="password" class="text" id="PHP_AUTH_PW" name="PHP_AUTH_PW" onKeypress="if(document.getElementById('PHP_AUTH_USER').value!=''&&document.getElementById('PHP_AUTH_PW').value!=''){if(event.keyCode==13||event.charCode==13){loginNow();}};"/></td>
			</tr>
		</table>	
			
		<!---login button-->
		<div class="button-container">
			<button type="button" class="grayButton" style="cursor: hand;" onClick="loginNow();"><span><span><?= lang("Log In"); ?></span></span></button>
		</div>
			
<?php
# ERROR: Invalid username/password
//$error = 1; // testing
if ( $error == 1 ) {
?>
      <div class="error">
      	Invalid Username/Password. Please try again or <a onMouseOver="this.style.color='#FFF';" onMouseOut="this.style.color='#000';" href="javascript:void(0)" onClick="document.recover.submit();" rel="nofollow">send a reminder email</a>.
      </div>
<?php
} // End if error

# Recovery message
$maxmsg = count($msg);
if ( count($maxmsg) > 0 ) {
	for ( $m =0; $m < $maxmsg; $m++ ) {
		echo '<div class="message">';
		echo $msg[$m];
		echo '</div>';
	}
}
?>
		<!---cell: help/info links-->
		<div id="help-links">
			<a href="help.html" rel="nofollow"><? echo lang("Recommended browser settings"); ?></a> |
			<strong><a href="javascript:void(0)" onClick="document.recover.submit();" rel="nofollow"><?=lang("Forgot my password"); ?></a></strong>
		</div>	
	
	</div>
	<!---end login-box-->
</form>
</body>
</html>