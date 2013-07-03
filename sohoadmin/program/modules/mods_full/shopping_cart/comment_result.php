<?php
error_reporting('341');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author:        Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:      http://www.soholaunch.com
## Bug Reports:   http://bugzilla.soholaunch.com
## Release Notes: sohoadmin/build.dat.php
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

error_reporting('341');

session_start();

# Include core files
require_once("../../../includes/product_gui.php");


# Set comment status
if($_REQUEST['process'] == "comment"){

   $BLOG_QRY = "UPDATE cart_comments SET STATUS = '".$_REQUEST['do']."' WHERE PRIKEY = '".$_REQUEST['comment']."'";
   if(!mysql_query($BLOG_QRY)){
      echo "Error updating comment!";
   }else{
   	if($_REQUEST['do']=='denied'){
   		//echo "Comment ".$_REQUEST['do'];	
   		echo "<span style=\"color: red;\">Comment denied</span>";
   	} else {
   		echo "Comment ".$_REQUEST['do'];	
   	}
      
   }
}

# Delete comment
if($_REQUEST['process'] == "delete"){
   if(!mysql_query("DELETE FROM cart_comments WHERE PRIKEY = '".$_REQUEST['comment']."'")){
    //  echo mysql_error();
   }else{
      echo "<span style=\"color: red;\">Comment deleted</span>";
   }
}

# Update comment settings
if($_REQUEST['process'] == "comment_settings"){
   
if($_REQUEST['email_to']!=''){
	mysql_query("update cart_options set BIZ_VERIFY_COMMENTS='".$_REQUEST['email_to']."'");
}

if($_REQUEST['is_enabled']!=''){
	mysql_query("update cart_options set DISPLAY_COMMENTS='".$_REQUEST['is_enabled']."'");
}


if($_REQUEST['require_approval']!=''){
	//echo $_REQUEST['require_approval'];
	$cartprefs = new userdata("cart");
	$cartprefs->set("comments_required_approval", $_REQUEST['require_approval']);
}

//
//$SITE_SPECS = mysql_fetch_array($result);
//$admin_email = $SITE_SPECS['BIZ_VERIFY_COMMENTS'];
//$enable_comments = $SITE_SPECS['DISPLAY_COMMENTS'];
//   
//   $blog_comment_settings = new userdata("blog_comment");
//   
//   //$blog_comment_settings->set("allow_comments", $_REQUEST['enable']);
//   $blog_comment_settings->set("emailto", $_REQUEST['email_to']);
//   $blog_comment_settings->set("captcha", $_REQUEST['captcha']);
//   $blog_comment_settings->set("require_approval", $_REQUEST['require_approval']);
//   //$blog_comment_settings->set("allowed_categorys", $_REQUEST['allowed_categorys']);
   echo "Settings Updated";
}

//# Delete styles
//if($_REQUEST['process'] == "delete_styles"){
//   if(!mysql_query("DELETE FROM smt_userdata WHERE plugin = 'blog_styles' AND fieldname = 'styles'")){
//      echo mysql_error();
//   }else{
//      echo "Default Styles Restored";
//   }
//}

?>