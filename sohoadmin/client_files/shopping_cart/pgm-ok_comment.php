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

error_reporting(0);
session_start();

include("pgm-cart_config.php");

// Re-registers all global & session info
if ( strlen(lang("Order Date")) < 4 ) {
   if ( !include("../sohoadmin/program/modules/mods_full/shopping_cart/includes/config-global.php") ) {
      echo lang("Could not include config script!"); exit;
   }
   if ( !include("../sohoadmin/includes/db_connect.php") ) {
      echo lang("Error")." 1: ".lang("Your session has expired. Please go back through the checkout process").".";
      exit;
   }
}

// ----------------------------------------------------
// Configure System Variables for Language Version
// ----------------------------------------------------

	$selSpecs = mysql_query("SELECT * FROM site_specs");
	$getSpec = mysql_fetch_array($selSpecs);
	
	if ($getSpec[df_lang] == "") {
		$language = "english.php";
	} else {
      $language = $getSpec[df_lang];
		$language = rtrim($language);
		$language = ltrim($language);
	}
	
	$lang_include = $lang_dir.'/'.$language;
	
	include ($lang_include);
	
	session_register("lang");
	session_register("language");
	session_register("getSpec");
// ----------------------------------------------------

//$filename = "CART_".$id.".".$key;

if ($_GET['id']=='' || $_GET['key']=='') { exit; }

$fqry = mysql_query("select * from cart_comments where PROD_ID='".$_GET['id']."' AND AUTH_KEY='".$_GET['key']."' AND STATUS='not_approved'");
if(mysql_num_rows($fqry) < 1){
	echo ("<h2>".lang("This comment has already been added to the system or no longer exists.")."</h2>\n");
	exit;
} else {
	$farray = mysql_fetch_assoc($fqry);
	$NEW_COMMENT = $farray['COMMENT_HTML'];
	mysql_query("update cart_comments set STATUS='approved' where PROD_ID='".$_GET['id']."' AND AUTH_KEY='".$_GET['key']."' AND STATUS='not_approved'");

	echo ("<H2>".lang("CUSTOMER COMMENT ADDED")."!</h2><br>$NEW_COMMENT\n"); exit;
}


//
//	$file = fopen("$filename", "r");
//		$NEW_COMMENT = fread($file,filesize($filename));
//	fclose($file);
//
//	$verify_name = $filename;
//	$filename = "CART_$key.REVIEW";
//
//	$file = fopen("$filename", "a");
//		fwrite($file, "$NEW_COMMENT");
//	fclose($file);
//
//	unlink($verify_name);
//
//	echo ("<H1>".lang("CUSTOMER COMMENT ADDED")."!</h1><br>$NEW_COMMENT\n");



exit;

?>