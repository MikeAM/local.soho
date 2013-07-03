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

error_reporting('341');
session_start();

include_once("pgm-cart_config.php");


if(preg_match('/[^0-9]/i',$_REQUEST['id']) || $_REQUEST['id']==''){
	exit;
}
if(preg_match('/[^0-9\.]/i',$_REQUEST['comkey']) || $_REQUEST['comkey']==''){
	exit;
}
//// Re-registers all global & session info
//if ( strlen(lang("Order Date")) < 4 ) {
//   if ( !include("../sohoadmin/program/modules/mods_full/shopping_cart/includes/config-global.php") ) {
//      echo lang("Could not include config script!"); exit;
//   }
//   if ( !include("../sohoadmin/includes/db_connect.php") ) {
//      echo lang("Error")." 1: ".lang("Your session has expired. Please go back through the checkout process").".";
//      exit;
//   }
//}

// ----------------------------------------------------
// Configure System Variables for Language Version
// ----------------------------------------------------
if ( strlen(lang("Order Date")) < 4 ) {
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
	
	include_once($lang_include);
	
	$_SESSION['getSpec'] = $getSpec;
	$_SESSION['language'] = $language;
	foreach($lang as $lvar=>$lval){
		$_SESSION['lang'][$lvar]=$lval;
	}
}
// ----------------------------------------------------

//$filename = "CART_".$id.".".$key;

if ($_GET['id']=='' || $_GET['comkey']=='') { exit; }
$comresult='';
$fqry = mysql_query("select * from cart_comments where PROD_ID='".$_GET['id']."' AND AUTH_KEY='".$_GET['comkey']."' limit 1");

if(mysql_num_rows($fqry) < 1){
	$comresult = lang("This comment has already been added to the system or no longer exists.");

} else {
	$cc = mysql_fetch_assoc($fqry);
	if($cc['STATUS']!='approved'){
		mysql_query("update cart_comments set STATUS='approved' where PROD_ID='".$_GET['id']."' AND AUTH_KEY='".$_GET['comkey']."'");
	}
	
	$NEW_COMMENT = $cc['COMMENT'];	
	$comresult = lang("CUSTOMER COMMENT ADDED").": $NEW_COMMENT";
}
$comresult = preg_replace('/["\']/','',$comresult);
echo "<script language=\"javascript\">\n";
echo "alert('".$comresult."');\n";
echo "</script>\n";
?>