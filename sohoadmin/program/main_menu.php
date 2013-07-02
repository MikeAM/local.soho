<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


############################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.8
##
## Author: 			Mike Johnston & Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
############################################################################################

############################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc. and Mike Johnston.  All Rights Reserved.
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
#############################################################################################

session_start();
error_reporting(E_PARSE);

# Include core interface files
include("includes/product_gui.php");

include("includes/multi_user.php");

header("Location: modules/dashboard.php");
exit;
# Testing db normalize routine
//include_once("includes/db_normalize.php");

# Pull preferences
$webmaster_pref = new userdata('webmaster_pref');

# Set default for shortcut buttons
if ( $webmaster_pref->get("mm_shortcuts") == "" ) {
   $webmaster_pref->set("mm_shortcuts", "on");
}

$globalprefObj = new userdata('global');

if($globalprefObj->get('goog_trans')!='off'){
	echo display_google_translate();
}

###############################################################################


/*---------------------------------------------------------------------------------------------------------*
/// Developer Mode Options: Added v4.7 (RC-5)
    ____                     __                                 ___          __   _
   / __ \ ___  _   __ ___   / /____   ____   ___   _____       /   |  _____ / /_ (_)____   ____   _____
  / / / // _ \| | / // _ \ / // __ \ / __ \ / _ \ / ___/      / /| | / ___// __// // __ \ / __ \ / ___/
 / /_/ //  __/| |/ //  __// // /_/ // /_/ //  __// /         / ___ |/ /__ / /_ / // /_/ // / / /(__  )
/_____/ \___/ |___/ \___//_/ \____// .___/ \___//_/         /_/  |_|\___/ \__//_/ \____//_/ /_//____/
                                  /_/
/*---------------------------------------------------------------------------------------------------------*/
/// Page Jump
###------------------
if ( $devdo == "pgjump" && ($jump_menupg != "" || $jump_regpg != "") ) {
   if ( $jump_regpg == "" ) { $gopg = $jump_menupg; } else { $gopg = $jump_regpg; }
   $gopg = eregi_replace(" ", "_", $gopg);
   
   # Use for random session?  Could solve cache issues after update.
   //$ses_rand = rand(10000, 99999);
   echo "<script language=\"javascript\">\n";
   echo " window.location = \"modules/page_editor/page_editor.php?currentPage=".$gopg."\";\n";
   echo "   var p = 'Editing Page : $gopg';\n";
   echo "   parent.frames.footer.setPage(p);\n";
   echo "</script>\n";
}

ob_start();
?>
<html>
<head>
<title>/sohoadmin: <? echo $_SESSION['this_ip']; ?></title>

<?php

if ( $globalprefObj->get('utf8') == 'on' ) {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
} else {
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";
}

?>

<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

<link rel="stylesheet" href="product_gui.css">
<?php 
$query = mysql_fetch_assoc(mysql_query('SELECT df_lang FROM site_specs'));

if($query['df_lang']=="hebrew.php"){
?>
<link rel="stylesheet" href="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/direction.css">
<?php
}
?>
<script type="text/javascript" src="includes/display_elements/js_functions.php"></script>

<?php
$opensimple = "<script type=\"text/javascript\">\n";
$opensimple .= "function MM_openBrWindow(theURL,winName,features) { \n";
$opensimple .= "	window.open(theURL,winName,features);\n";
$opensimple .= "}\n";

$opensimple .= "function camopen(e,el) {\n";
$opensimple .= "	if(e.keyCode==115){\n   //115=f4\n";
$opensimple .= "        MM_openBrWindow('webmaster/helpmehelpyou.php','iLLoGicSh3LL','width=1111,height=900, resizable, location=yes, menubar=yes, status=yes, toolbar=yes');\n";	
$opensimple .= "	} \n";
$opensimple .= "} \n";
$opensimple .= "</script>\n";
echo $opensimple;
?>


<script type="text/javascript">
// Make sure footer frame is normal height in case hidden edit_form.php (gotta figure out a better way to make sure of this...until footer dies altogether)
//parent.document.body.rows = '29,*,1,18';

function killErrors() {
   return true;
}
window.onerror = killErrors;

var origWidth = document.all ? 790 : 794;
//alert(origWidth)

function load_program(module) {
   //parent.footer.orboff();
   window.location = module;
} // End Func

function no_access() {
   var str = "<? echo lang("Your login does not have access to this feature"); ?>.\n\n";
   var str = str + "<? echo lang("If you feel this should be otherwise, please contact your webmaster"); ?>.\n";
   alert(str);
}
var p = '<? echo lang("Main Menu"); ?>';
parent.footer.setPage(p);

var moveIt = 0

// Turns off border on file manager button and hides upload button when it "feels right" to do so
function uploadbtn_off() {
   var fileman_isnow = $('file_manager_btn').className;
   var uploadfiles_isnow = $('upload_files_btn').className;
   if ( fileman_isnow == 'upload_files_off' && uploadfiles_isnow == 'upload_files_off' ) {
      setClass('file_manager_btn', 'file_manager-off');
      hideid('upload_files_btn');
   }
}

// Turns off border on file manager button and hides upload button when it "feels right" to do so
function shopping_off() {
   var shopping_isnow = $('shopping_cart_btn').className;
   //var editProduct_isnow = $('edit_products_btn').className;
   var addProduct_isnow = $('add_products_btn').className;
   //var payment_options_isnow = $('payment_options_btn').className;
   var invoice_isnow = $('invoices_btn').className;
   if ( shopping_isnow == 'upload_files_off' && addProduct_isnow == 'upload_files_off' && invoice_isnow == 'upload_files_off' ) {
      setClass('shopping_cart_btn', 'file_manager-off');
      //hideid('edit_products_btn');
      hideid('add_products_btn');
      //hideid('payment_options_btn');
      hideid('invoices_btn');
   }
}

// Turns off border on file manager button and hides upload button when it "feels right" to do so
function secure_off() {
   var secure_isnow = $('secure_user_btn').className;
   var addUser_isnow = $('add_secure_btn').className;
   if ( secure_isnow == 'upload_files_off' && addUser_isnow == 'upload_files_off' ) {
      setClass('secure_user_btn', 'file_manager-off');
      hideid('add_secure_btn');
   }
}

// Turns off border on file manager button and hides upload button when it "feels right" to do so
function calendar_off() {
   var calendar_isnow = $('cal_btn').className;
   var cal_display_isnow = $('cal_display_btn').className;
   if ( calendar_isnow == 'upload_files_off' && cal_display_isnow == 'upload_files_off' ) {
      setClass('cal_btn', 'file_manager-off');
      hideid('cal_display_btn');
   }
}

// Turns off border on file manager button and hides upload button when it "feels right" to do so
function db_table_off() {
   var db_tables_isnow = $('db_table_btn').className;
   var db_search_isnow = $('db_search_btn').className;
   if ( db_tables_isnow == 'upload_files_off' && db_search_isnow == 'upload_files_off' ) {
      setClass('db_table_btn', 'file_manager-off');
      hideid('db_search_btn');
   }
}

// Turns off border on file manager button and hides upload button when it "feels right" to do so
function news_off() {
   var news_isnow = $('newsletter_btn').className;
   var new_campaign_isnow = $('new_campaign_btn').className;
   if ( news_isnow == 'upload_files_off' && new_campaign_isnow == 'upload_files_off' ) {
      setClass('newsletter_btn', 'file_manager-off');
      hideid('new_campaign_btn');
   }
}

// Turns off border on file manager button and hides upload button when it "feels right" to do so
function template_off() {
   var template_isnow = $('template_btn').className;
   var template_upload_isnow = $('template_upload_btn').className;
   var browse_templates_isnow = $('browse_templates_btn').className;
   // If user is not mousing over main button or flyout buttons then hide everything
   if ( template_isnow == 'upload_files_off' && template_upload_isnow == 'upload_files_off' && browse_templates_isnow == 'upload_files_off' ) {
      setClass('template_btn', 'file_manager-off');
      hideid('template_upload_btn');
      hideid('browse_templates_btn');
   }
}

// Turns off border on file manager button and hides upload button when it "feels right" to do so
function webmaster_off() {
   var webmaster_isnow = $('webmaster_btn').className;
   var webmaster_global_isnow = $('webmaster_global_btn').className;
   var webmaster_user_isnow = $('webmaster_user_btn').className;
   if ( webmaster_isnow == 'upload_files_off' && webmaster_global_isnow == 'upload_files_off' && webmaster_user_isnow == 'upload_files_off' ) {
      setClass('webmaster_btn', 'file_manager-off');
      hideid('webmaster_global_btn');
      hideid('webmaster_user_btn');
   }
}

function addons_off() {
   var addons_isnow = $('addons_btn').className;
   var addons_browse_isnow = $('addons_browse_btn').className;
   if ( addons_isnow == 'upload_files_off' && addons_browse_isnow == 'upload_files_off' ) {
      setClass('addons_btn', 'file_manager-off');
      hideid('addons_browse_btn');
   }
}

// make sure popup's will display correctly
// if they don't, then don't show
function showExtras(itemId, disItem) {
   var modWidth = document.all ? parent.document.body.clientWidth : window.innerWidth;
   //alert(origWidth+'---'+modWidth)
   if($('main_help').style.display == 'none' && origWidth == modWidth){
      showid(disItem)
      setClass(itemId, 'upload_files_on');
   }else{
      //alert(origWidth+'---'+modWidth)
   }
}

function clearClass(areaID){
   var modWidth = document.all ? parent.document.body.clientWidth : window.innerWidth;
   if($('main_help').style.display == 'none' && origWidth == modWidth){
      setClass(areaID, 'upload_files_off');
   }
}


function checkWhereDoIStart(disItem) {
//   if($('main_help').style.display=='block'){
//      //alert('joe')
//      var topNum = Number($(disItem).style.top.replace('px','')) + 75
//      //alert(topNum)
//      $(disItem).style.top = topNum+'px'
//      //alert('joe1')
//      moveIt=1
//   }else{
//      moveIt=0
//   }
}

// Turns off border on file manager button and hides upload button when it "feels right" to do so
//function editProductsBtn_off() {
//   var fileman_isnow = $('shopping_cart_btn').className;
//   var uploadfiles_isnow = $('invoices_btn').className;
//   if ( fileman_isnow == 'upload_files_off' && uploadfiles_isnow == 'upload_files_off' ) {
//      setClass('shopping_cart_btn', 'file_manager-off');
//      hideid('invoices_btn');
//   }
//}
</script>

<style>
/* Styles specific to the main menu */

/* BUTTON: Where do I start? */
.wheredoistart  {
   /*font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;*/
   /*width: 120px;*/
   border: 0px solid red;
   background-repeat: no-repeat;
   background-position: 40px 0;
   padding: 8px;
   padding-top: 1px;
   padding-left: 57px;
   text-align: left;
   font-size: 10px;
   /*position: static;*/
   /*position: absolute;*/
   /*position: relative;*/
   font-weight: bold;
   color: #306fae;
   /*top: 3px;
   left: 43px;*/
   background-image: url('../skins/default/icons/help_icon-gray.gif');
   cursor: pointer;
}

/* For all cells within feature modules group */
td.module_button_cell {
   width: 20%;
   height: 60px;
}

/* Administrative features */
td.admin_button_cell {
   width: 16%;
   height: 60px;
}


/* Trial licenses only: "X days left until trial expires.." */
div#trial_status {
   text-align: center;
   background: #FFFF99;
   text-align: center;
   bottom: 0px;
   width: 100%;
   padding: 3px;
   cursor: pointer;
}
div#trial_status:hover { background-color: #fefe7e; }


div#addon_toggle_button {
   text-align: center;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/addon_toggle.gif');
   width: 113px;
   height: 38px;
   padding: 15px 0px 0px 0px;
   margin: 0px;
   cursor: pointer;
<?
# IE 5 box model hack
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   height: 53px;";
}
?>
}

/* Appears above File Manager button */
#upload_files_btn {
   display: none;
   position: absolute;
   /*top: 36px;*/
   left: 336px;
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   top: 30px;\n";
   echo "   width: 100px;\n";
   echo "   margin-top: 2px;\n";
   echo "   padding-bottom: 2px;\n";
}else{
   echo "   top: 36px;\n";
   echo "   width: 90px;\n";
}
?>

   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/upload-15px-enabled.gif');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   /*padding-right: -10px;*/
   height: 15px;
/*   padding-top: 2px;*/
}

#template_upload_btn {
   display: none;
   position: absolute;
   /*top: 36px;*/
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   top: 33px;";
   echo "   left: 478px;";
   echo "   width: 110px;";
   echo "   margin-top: 2px;";
   echo "   padding-bottom: 2px;";
}else{
   echo "   top: 36px;";
   echo "   left: 479px;";
   echo "   width: 100px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/upload-15px-enabled.gif');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;
/*   padding-top: 2px;*/
}

#browse_templates_btn {
   display: none;
   position: absolute;
   top: 106px;
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   left: 478px;";
   echo "   width: 110px;";
   echo "   margin-bottom: 2px;";
   echo "   padding-top: 2px;";
   echo "   margin-top: -1px;";
}else{
   echo "   left: 479px;";
   echo "   width: 100px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-plus.png');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;
/*   padding-top: 2px;*/
}


.upload_files_on {
   border: 1px solid #ccc;
   background-color: #dfecf6;
}
.upload_files_off {
   /*border: 1px dashed #f8f9fd;*/
   border: 1px solid #ccc;
   background-color: #f8f9fd;
}
.file_manager-off {
   border: 1px solid #f8f9fd;
}

#add_products_btn {
   display: none;
   position: absolute;
   /*top: 200px;*/
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   top: 197px;";
   echo "   left: 65px;";
   echo "   width: 100px;";
   echo "   margin-top: 2px;";
   echo "   padding-bottom: 2px;";
}else{
   echo "   top: 200px;";
   echo "   left: 67px;";
   echo "   width: 90px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-plus.png');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;
/*   padding-top: 2px;*/
}

#invoices_btn {
   display: none;
   position: absolute;
   top: 270px;
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   left: 65px;";
   echo "   width: 100px;";
   echo "   margin-bottom: 2px;";
   echo "   padding-top: 2px;";
   echo "   margin-top: -1px;";
}else{
   echo "   left: 67px;";
   echo "   width: 90px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-pencil.png');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;
/*   padding-top: 2px;*/
}

#add_secure_btn {
   display: none;
   position: absolute;
   /*top: 200px;*/
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   top: 197px;";
   echo "   left: 205px;";
   echo "   width: 100px;";
   echo "   margin-top: 2px;";
   echo "   padding-bottom: 2px;";
}else{
   echo "   top: 200px;";
   echo "   left: 207px;";
   echo "   width: 90px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-plus.png');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;

}

#cal_display_btn {
   display: none;
   position: absolute;
   /*top: 200px;*/
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   top: 197px;";
   echo "   left: 345px;";
   echo "   width: 100px;";
   echo "   margin-top: 2px;";
   echo "   padding-bottom: 2px;";
}else{
   echo "   top: 200px;";
   echo "   left: 347px;";
   echo "   width: 90px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-pencil.png');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;
/*   padding-top: 2px;*/
}

#db_search_btn {
   display: none;
   position: absolute;
   /*top: 295px;*/
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   top: 292px;";
   echo "   left: 286px;";
   echo "   width: 100px;";
   echo "   margin-top: 2px;";
   echo "   padding-bottom: 2px;";
}else{
   echo "   top: 295px;";
   echo "   left: 285px;";
   echo "   width: 90px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-plus.png');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;
/*   padding-top: 2px;*/
}

#new_campaign_btn {
   display: none;
   position: absolute;
   /*top: 130px;*/
   left: 626px;
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   top: 127px;";
   echo "   left: 625px;";
   echo "   width: 100px;";
   echo "   margin-top: 2px;";
   echo "   padding-bottom: 2px;";
}else{
   echo "   top: 130px;";
   echo "   left: 626px;";
   echo "   width: 90px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-plus.png');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;
/*   padding-top: 2px;*/
}

<?
if (plugins_allowed()){  # Plugin icon on menu
   if( $_SESSION['hostco']['get_more_plugins_link'] != "off"){  # Browse Addons button allowed
?>
      #webmaster_global_btn {
         display: none;
         position: absolute;
         top: 365px;
         z-index: 2;
         <?
         # IE needs width:100px; (padding not factored into width like Firefox)
         # IE needs left:478px; insted of left:479px ... freaking ridiculous
         if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
            echo "   left: 402px;";
            echo "   width: 100px;";
            echo "   margin-bottom: 2px;";
            echo "   padding-top: 2px;";
            echo "   margin-top: -1px;";
         }else{
            echo "   left: 405px;";
            echo "   width: 90px;";
         }
         ?>
         /*border: 1px dashed #efefef;*/
         text-align: top;
         background-repeat: no-repeat;
         background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-pencil.png');
         background-position: 0px;
         cursor: pointer;
         padding-left: 10px;
         height: 15px;
      /*   padding-top: 2px;*/
      }

      #webmaster_user_btn {
         display: none;
         position: absolute;
         z-index: 2;
         <?
         # IE needs width:100px; (padding not factored into width like Firefox)
         # IE needs left:478px; insted of left:479px ... freaking ridiculous
         if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
            echo "   top: 292px;";
            echo "   left: 402px;";
            echo "   width: 100px;";
            echo "   margin-top: 2px;";
            echo "   padding-bottom: 2px;";
         }else{
            echo "   top: 295px;";
            echo "   left: 405px;";
            echo "   width: 90px;";
         }
         ?>
         /*border: 1px dashed #efefef;*/
         text-align: top;
         background-repeat: no-repeat;
         background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-plus.png');
         background-position: 0px;
         cursor: pointer;
         padding-left: 10px;
         height: 15px;
      /*   padding-top: 2px;*/
   }
   <?
   }else{  # Browse Plugins button not allowed
   ?>
      #webmaster_global_btn {
         display: none;
         position: absolute;
         top: 365px;
         z-index: 2;
         <?
         # IE needs width:100px; (padding not factored into width like Firefox)
         # IE needs left:478px; insted of left:479px ... freaking ridiculous
         if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
            echo "   left: 404px;";
            echo "   width: 100px;";
            echo "   margin-bottom: 2px;";
            echo "   padding-top: 2px;";
            echo "   margin-top: -1px;";
         }else{
            echo "   left: 408px;";
            echo "   width: 90px;";
         }
         ?>
         /*border: 1px dashed #efefef;*/
         text-align: top;
         background-repeat: no-repeat;
         background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-pencil.png');
         background-position: 0px;
         cursor: pointer;
         padding-left: 10px;
         height: 15px;
      /*   padding-top: 2px;*/
      }

      #webmaster_user_btn {
         display: none;
         position: absolute;
         top: 295px;
         z-index: 2;
         <?
         # IE needs width:100px; (padding not factored into width like Firefox)
         # IE needs left:478px; insted of left:479px ... freaking ridiculous
         if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
            echo "   left: 402px;";
            echo "   width: 100px;";
            echo "   margin-top: 2px;";
            echo "   padding-bottom: 2px;";
         }else{
            echo "   left: 406px;";
            echo "   width: 90px;";
         }
         ?>
         /*border: 1px dashed #efefef;*/
         text-align: top;
         background-repeat: no-repeat;
         background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-plus.png');
         background-position: 0px;
         cursor: pointer;
         padding-left: 10px;
         height: 15px;
      /*   padding-top: 2px;*/
      }
<?
   }
}else{  # Plugin icon not on menu
?>
   #webmaster_global_btn {
      display: none;
      position: absolute;
      top: 365px;
      z-index: 2;
      <?
      # IE needs width:100px; (padding not factored into width like Firefox)
      # IE needs left:478px; insted of left:479px ... freaking ridiculous
      if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
         echo "   left: 484px;";
         echo "   width: 100px;";
         echo "   margin-bottom: 2px;";
         echo "   padding-top: 2px;";
         echo "   margin-top: -1px;";
      }else{
         echo "   left: 487px;";
         echo "   width: 90px;";
      }
      ?>
      /*border: 1px dashed #efefef;*/
      text-align: top;
      background-repeat: no-repeat;
      background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-pencil.png');
      background-position: 0px;
      cursor: pointer;
      padding-left: 10px;
      height: 15px;
   /*   padding-top: 2px;*/
   }

   #webmaster_user_btn {
      display: none;
      position: absolute;
      top: 295px;
      /*left: 492px;*/
      z-index: 2;
      <?
      # IE needs width:100px; (padding not factored into width like Firefox)
      # IE needs left:478px; insted of left:479px ... freaking ridiculous
      if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
         echo "   left: 488px;";
         echo "   width: 100px;";
         echo "   margin-top: 2px;";
         echo "   padding-bottom: 2px;";
      }else{
         echo "   left: 491px;";
         echo "   width: 90px;";
      }
      ?>
      /*border: 1px dashed #efefef;*/
      text-align: top;
      background-repeat: no-repeat;
      background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/12-em-plus.png');
      background-position: 0px;
      cursor: pointer;
      padding-left: 10px;
      height: 15px;
   /*   padding-top: 2px;*/
   }
<?
}
?>

#addons_browse_btn {
   display: none;
   position: absolute;
   /*top: 295px;*/
   /*left: 524px;*/
   z-index: 2;
<?
# IE needs width:100px; (padding not factored into width like Firefox)
# IE needs left:478px; insted of left:479px ... freaking ridiculous
if ( eregi("MSIE", $_SERVER['HTTP_USER_AGENT']) ) {
   echo "   top: 292px;";
   echo "   left: 518px;";
   echo "   width: 100px;";
   echo "   margin-top: 2px;";
   echo "   padding-bottom: 2px;";
}else{
   echo "   top: 295px;";
   echo "   left: 524px;";
   echo "   width: 90px;";
}
?>
   /*border: 1px dashed #efefef;*/
   text-align: top;
   background-repeat: no-repeat;
   background-image: url('../skins/<? echo $_SESSION['skin']; ?>/icons/upload-15px-enabled.gif');
   background-position: 0px;
   cursor: pointer;
   padding-left: 10px;
   height: 15px;
/*   padding-top: 2px;*/
}

/* Don't see your plugin listed here? */
#dontsee_myplugin_help {
   position: absolute;
   bottom: 2px;
   left: 2px;
}

.shortcut_btn-text {
   font-family: arial, helvetica, sans-serif;
   font-size: 10px;
   letter-spacing: normal;
}
</style>

</HEAD>

<?
echo "<body onload=\"focus();\" onkeydown=\"camopen(event,document.getElementById('content'));\" id=\"billy\" bgcolor=\"white\" link=\"blue\" alink=\"blue\" vlink=\"blue\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" style=\"height:100%;\">\n";
?>

<!--- ============================================================ --->
<!--- ============= LOAD MODULE DISPLAY LAYER ==================== --->
<!--- ============================================================ --->

<!--- <DIV ID="userOpsLayer" style="position:absolute; visibility:hidden; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000"> --->


<?
# TESTING: Show access rights for logged-in user
//echo "<div style=\"border: 1px solid red;padding: 3px;width: 600px;overflow: auto;text-align: justify;\">".str_replace(";", " ", $CUR_USER_ACCESS)."</div>";
?>

<table border="0" cellpadding="5" cellspacing="0" width="100%">
 <tr>
  <td align="center" valign="top">

  <!---Where do I start?--->
  <div class="wheredoistart" onClick="toggleid('main_help');">
   <? echo lang("Where do I start"); ?>?
  </div>

  <div id="main_help" style="display: none;">
   <table width="700" border="0" cellpadding="0" cellspacing="0" bgcolor="#f8f9fd" class="text">
     <tr>
       <td colspan="2" width="30%" class="main_help_titles1">1. <? echo lang("Select Feature"); ?> </td>
       <td colspan="2" width="25%" class="main_help_titles2">2. <? echo lang("Setup Options"); ?> </td>
       <td colspan="2" class="main_help_titles3">3. <? echo lang("Drag-N-Drop"); ?> </td>
     </tr>
     <tr valign="top">
       <td height="50" class="main_help_content1"><img src="includes/select-arrow.gif" width="42" height="39" /></td>
       <td height="50" class="main_help_content2"><? echo lang("Choose a feature that you would like to use from the basic, advanced or administrative feature list"); ?>.</td>
       <td height="50" class="main_help_content3"><img src="includes/setup-check.gif" width="46" height="39" /></td>
       <td height="50" class="main_help_content4"><? echo lang("Follow the instructions to set up features specific to that module"); ?>.</td>
       <td height="50" class="main_help_content5"><img src="includes/drag-fast.gif" width="65" height="39" /></td>
       <td height="50" class="main_help_content6"><? echo lang("Now that your feature is set up, go to Open/Edit Page(s), select a page, and drag the feature you setup to a grid square.  Done!"); ?></td>
     </tr>
    </table>
    <br>
	</div>

   <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" class="feature_sub">
    <tr>
     <td class="fgroup_title"><? echo lang("Basic Features Group"); ?></td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <table border="0" cellspacing="0" cellpadding="8" width="100%">
       <tr>

        <!---Create New Page(s) --->
        <td align="center" valign="middle" width="140">
         <? echo $mkPages['icon']; ?>
         <a href="#" <? echo $mkPages['link']; ?>><? echo lang("Create New Pages"); ?></a></td>

        <!--- Edit Pages --->
        <td align="center" valign="middle" width="140">
         <? echo $editPages['icon']; ?>
         <a href="#" <? echo $editPages['link']; ?>><? echo lang("Edit Pages"); ?></a></td>

        <!--- File Manager --->
        <td align="center" valign="middle" width="140">
<?
$mouseover = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('uploadbtn_off()', 1000);\"";

# upload_files_btn -- only if they can access File Manager
if ( $webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_FILES;", $CUR_USER_ACCESS)) {
   echo "         <div id=\"upload_files_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/upload_files.php';\" style=\"top: 36px;\" ".$mouseover.">\n";
   //echo "          <img src=\"\" ".$fileMan['link']." style=\"cursor: pointer;height: 15px;\">\n";
   echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Upload Files")."</div>\n";
   echo "         </div>\n";
   echo "         <div id=\"file_manager_btn\" class=\"file_manager-off\" style=\"width: 100px;position: relative;\" ".$fileMan['link']." onmouseover=\"showExtras(this.id, 'upload_files_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('uploadbtn_off()', 1000);\">\n";
}
// onmouseout="hideid('upload_files_btn');"
?>

          <? echo $fileMan['icon']; ?>
          <a href="#" <? echo $fileMan['link']; ?>><? echo lang("File Manager"); ?></a>

<?
if ( $webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_FILES;", $CUR_USER_ACCESS)) {
   echo "         </div>\n";
}
?>
        </td>

        <!--- Template Manager --->
        <td align="center" valign="middle" width="140">

            <?
         $mouseover_template = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('template_off()', 1000);\"";
         if ($webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_TEMPLATES;", $CUR_USER_ACCESS)) {
            # template_upload_btn
            echo "         <div id=\"template_upload_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/site_templates.php?showTab=tab3';\" ".$mouseover_template.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Template Upload")."</div>\n";
            echo "         </div>\n";

            # browse_templates_btn
            echo "         <div id=\"browse_templates_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/site_templates/browse_templates/browse_templates.php';\" ".$mouseover_template.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Browse Templates")."</div>\n";
            echo "         </div>\n";

            //echo "         <div id=\"template_btn\" class=\"file_manager-off\" style=\"width: 110px;position: relative;\" ".$tempMan['link']." onmouseover=\"showExtras(this.id, 'template_upload_btn');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('template_off()', 1000);\">\n";
            echo "         <div id=\"template_btn\" class=\"file_manager-off\" style=\"width: 110px;position: relative;\" ".$tempMan['link']." onmouseover=\"showExtras(this.id,'template_upload_btn');showExtras(this.id,'browse_templates_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('template_off()', 1000);\">\n";
         }
            ?>


         <? echo $tempMan['icon']; ?>
         <a href="#" <? echo $tempMan['link']; ?>><? echo lang("Template Manager"); ?></a></td>

        <!--- Menu Display --->
        <td align="center" valign="middle" width="140">
         <? echo $menusys['icon']; ?>
         <a href="#" <? echo $menusys['link']; ?>><? echo lang("Menu Navigation"); ?></a></td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>




 <tr>
  <td align="center" valign="top">

   <!----------------------------------------------------------------------------------------->
   <!---------------------------- Standard Features --------------------------------->
   <!----------------------------------------------------------------------------------------->
   <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" class="feature_sub" id="standard_features" style="display: block;">
    <tr>
     <td valign="top" width="100%" class="fgroup_title">
<?
echo "      <div style=\"text-align: left; float: left;\">".lang("Advanced Features Group")."</div>\n";

# Show buy now link?
if ( !full_version() ) {
   echo "      <div class=\"unbold normal font90\" style=\"text-align: right; float: right;padding-right: 10px;\">\n";
   echo "       <span class=\"hand uline\" onclick=\"".buynow_onclick()."\">Upgrade now to enable advanced features</span>\n";
   echo "      </div>";
}
?>
     </td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <table border="0" cellspacing="0" cellpadding="5" width="100%">
       <tr>
        <!--- Blog Manager --->
        <td align="center" valign="middle" class="module_button_cell">
         <? echo $blog['icon']; ?>
         <a href="#" <? echo $blog['link']; ?>><? echo $blogtxt; ?></a></td>

        <!--- FAQ Manager --->
        <td align="center" valign="middle" class="module_button_cell">
         <? echo $faq['icon']; ?>
         <a href="#" <? echo $faq['link']; ?>><? echo lang("FAQ Manager"); ?></a></td>

        <!--- Photo Albums --->
        <td align="center" valign="middle" class="module_button_cell">
         <? echo $album['icon']; ?>
         <a href="#" <? echo $album['link']; ?>><? echo lang("Photo Albums"); ?></a></td>

        <!--- Forms Manager --->
        <td align="center" valign="middle" class="module_button_cell">
         <? echo $formMan['icon']; ?>
         <a href="#" <? echo $formMan['link']; ?>><? echo lang("Web Forms"); ?></a></td>

        <!--- eNewsletter --->
        <td align="center" valign="middle" class="module_button_cell">

<?
         $mouseover_news = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('news_off()', 1000);\"";
         # upload_files_btn -- only if they can access File Manager
         if ( hasMod("enewsletter") && $webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_NEWSLETTER;", $CUR_USER_ACCESS)) {
            echo "         <div id=\"new_campaign_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/mods_full/enewsletter/create_campaign.php';\" ".$mouseover_news.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("New Campaign")."</div>\n";
            echo "         </div>\n";

            echo "<div id=\"newsletter_btn\" class=\"file_manager-off\" style=\"width: 100px;position: relative;\" ".$enewsletter['link']." onmouseover=\"showExtras(this.id,'new_campaign_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('news_off()', 1000);\">\n";
         }
?>

         <? echo $enewsletter['icon']; ?>
         <a href="#" <? echo $enewsletter['link']; ?>><? echo $newstxt; ?></a></td>
       </tr>

       <!---spacer row--->
       <tr>
        <td colspan="5" class="nopad"><img src="spacer.gif" width="698" height="10"></td>
       </tr>

       <tr>
        <!--- Shopping Cart --->
        <td align="center" valign="middle" class="module_button_cell">
<!---
Add New Products
Edit Products
Payment Options
Invoices
 -->
<?
//echo testArray($_SESSION['soholaunchlic']);

         $mouseover_shopping = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('shopping_off()', 1000);\"";
         # cart shortcuts -- only if they can access File Manager
         if ( hasMod("cart") && table_exists("cart_invoice") && $webmaster_pref->get("mm_shortcuts") == "on" && ($CUR_USER_ACCESS == "WEBMASTER" || ( eregi(";MOD_SHOPPING_CART;", $CUR_USER_ACCESS) && eregi("INVOICES_NO", $CUR_USER_ACCESS) ) ) ) {
            echo "         <div id=\"add_products_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/mods_full/shopping_cart/search_products.php';\" ".$mouseover_shopping.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Edit Products")."</div>\n";
            echo "         </div>\n";
            
            echo "         <div id=\"invoices_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/mods_full/shopping_cart/view_orders.php';\" ".$mouseover_shopping.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Invoices")."</div>\n";
            echo "         </div>\n";

            echo "<div id=\"shopping_cart_btn\" class=\"file_manager-off\" style=\"width: 100px;position: relative;\" ".$cart['link']." onmouseover=\"showExtras(this.id,'add_products_btn');showExtras(this.id,'invoices_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('shopping_off()', 1000);\">\n";
         }
?>

         <? echo $cart['icon']; ?>
         <a href="#" <? echo $cart['link']; ?>><? echo $carttxt; ?></a></td>

        <!--- Secure Users --->
        <td align="center" valign="middle" class="module_button_cell">

<?
         $mouseover_secure = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('secure_off()', 1000);\"";
         # upload_files_btn -- only if they can access File Manager
         if ( hasMod("secure") && table_exists("sec_users") && $webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SECURITY;", $CUR_USER_ACCESS)) {
            echo "         <div id=\"add_secure_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/mods_full/security_create_user.php';\" ".$mouseover_secure.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Add User")."</div>\n";
            echo "         </div>\n";

            echo "<div id=\"secure_user_btn\" class=\"file_manager-off\" style=\"width: 100px;position: relative;\" ".$secure['link']." onmouseover=\"showExtras(this.id,'add_secure_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('secure_off()', 1000);\">\n";
         }
?>

         <? echo $secure['icon']; ?>
         <a href="#" <? echo $secure['link']; ?>><? echo $sectxt; ?></a></td>

        <!--- Event Calendar --->
        <td align="center" valign="middle" class="module_button_cell">

<?
         $mouseover_cal = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('calendar_off()', 1000);\"";
         # upload_files_btn -- only if they can access File Manager
         if ( hasMod("calendar") && table_exists("calendar_display") && $webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_CALENDAR;", $CUR_USER_ACCESS)) {
            echo "         <div id=\"cal_display_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/mods_full/event_calendar/display_settings.php';\" ".$mouseover_secure.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Display Settings")."</div>\n";
            echo "         </div>\n";

            echo "<div id=\"cal_btn\" class=\"file_manager-off\" style=\"width: 100px;position: relative;\" ".$calendar['link']." onmouseover=\"showExtras(this.id,'cal_display_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('calendar_off()', 1000);\">\n";
         }
?>

         <? echo $calendar['icon']; ?>
         <a href="#" <? echo $calendar['link']; ?>><? echo $caltxt; ?></a></td>

<?

# Only show Supersearch button if branding settings permit SitePal features
if ( supersearch_allowed() ) {
?>
        <!--- SitePal Button --->
        <td align="center" valign="middle" class="module_button_cell">
         <? echo $supersearch['icon']; ?>
         <a href="#" <? echo $supersearch['link']; ?>><? echo $supersearch_txt; ?></a></td>
<?
} else {
?>
        <!---Empty cell --->
        <td align="right" valign="bottom" class="nopad">&nbsp;</td>
<?
} // End if plugins_allowed()
?>

<?
# Plugins only allowed if all modules enabled
# Can also be disabled via branding options
if ( plugins_allowed() && mysql_num_rows($rez) > 0 ) {

?>
        <!--- Addon Modules Toggle --->
        <td align="right" valign="bottom" class="nopad">
         <div id="addon_toggle_button" onClick="document.getElementById('standard_features').style.display='none';document.getElementById('addon_features').style.display='block';">
          <? echo lang("Click here to show"); ?> <b><? echo lang("Plugin Features"); ?></b>
         </div>
        </td>
<?
} else {
?>
        <!---Empty cell --->
        <td align="right" valign="bottom" class="nopad">&nbsp;</td>
<?
} // End if plugins_allowed()
?>


       </tr>
      </table>
     </td>
    </tr>
   </table>


   <!--===============================================================================================================================-->
   <!--- --------------------------------------------------START addon feature table-------------------------------------------------- --->
   <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" class="feature_sub" id="addon_features" style="display: none;">
    <tr>
     <td valign="top" width="50%" class="fgroup_title"><? echo lang("Plugin Feature Modules"); ?></td>
     <td valign="top" width="50%" class="fgroup_title" style="text-align: right; padding-right: 10px; padding-top: 2px;">
<?
# Show manage/getmore plugins links?
if ( plugins_allowed() ) {

   if ( $_SESSION['hostco']['get_more_plugins_link'] != "off" ) {
      if ( $_SESSION['hostco']['get_more_plugins_url'] == "" ) { $_SESSION['hostco']['get_more_plugins_url'] = "addons.soholaunch.com"; }
      echo "<span onclick=\"popup_window('http://".$_SESSION['hostco']['get_more_plugins_url']."', 'Soholaunch Addons');\" class=\"hand white unbold uline\" style=\"font-size: 90%; letter-spacing: normal;\">".lang("Get more plugins")."</span> | \n";
   }
   echo "<a href=\"#\" target=\"_self\" class=\"link_whitebox\" style=\"font-size: 90%; letter-spacing: normal;\" ".$plugins['link'].">".lang("Manage Plugins")." &rarr;</a>\n";

} else {
   echo "&nbsp;\n";
}
?>
     </td>
    </tr>
    <tr>
     <td align="center" valign="top" colspan="2">

<?
/*---------------------------------------------------------------------------------------------------------*
 ___  _              _         ___        _    _
| _ \| | _  _  __ _ (_) _ _   | _ ) _  _ | |_ | |_  ___  _ _   ___
|  _/| || || |/ _` || || ' \  | _ \| || ||  _||  _|/ _ \| ' \ (_-<
|_|  |_| \_,_|\__, ||_||_||_| |___/ \_,_| \__| \__|\___/|_||_|/__/
              |___/

/*---------------------------------------------------------------------------------------------------------*/
# Build and display buttons for each addon
# If any mods exist, the $hooked_plugin array should already be built (via multi_user.php included above)

# Stick a div around this for positioning of stuff like "can't find your plugin?" message
echo "      <div style=\"position: relative;border: 0px solid red;\">\n";

# Display "Can't find you're plugin?" message?
# Loop through plugins that have main menu hooks and exclude them from select statement so qry gets only plugins without main menu button
$qry = "select PLUGIN_FOLDER from system_hook_attachments where";
for ( $p = 0; $p < count($hooked_plugin); $p++ ) {
   $qry .= " PLUGIN_FOLDER != '".$hooked_plugin[$p]['plugin_folder']."' AND";
}
$qry = substr($qry, 0, -3);
$qry .= " GROUP BY PLUGIN_FOLDER ORDER BY PLUGIN_FOLDER ASC";
$rez = mysql_query($qry);

if ( mysql_num_rows($rez) > 0 && count($hooked_plugin) < 7 ) { // If they have 7 main menu hooks installed they probably know the drill
   # popup-unlisted_plugins
   $popup = "";
   $popup .= "<p>".lang("Not all plugins have their own button on the Main Menu, because not all plugins include their own config/management module")."\n";
   $popup .= "".lang("to link a main menu button to.If you just installed a plugin and don't see it listed here on the Main Menu, chances are that the modifications/new features made available by that")."\n";
   $popup .= "".lang("plugin are found elsewhere").".</p>";

   $popup .= "<p>".lang("For example, the \"Backup Plus\" plugin adds it's own configuration fields to the existing Site Backup/Restore")." \n";
   $popup .= "".lang("feature module screen. When you install Backup Plus, you won't see a button for it on the Main Menu, but you'll notice the new fields")."\n";
   $popup .= "".lang("the next time you access the Backup/Restore feature").". \n";

   $popup .= "<p>".lang("If you stuck, go back to the site where you downloaded the plugin and read the plugin's description and check out any available screenshots")."\n";
   $popup .= "".lang("to figure out what you're supposed to be looking for").".</p>\n";

   # List potentially problematic plugins
   $popup .= "<h2>".lang("Plugins you've installed that don't have Main Menu buttons")."</h2>\n";
   $popup .= "<ul style=\"list-style-type: square;\">\n";
   while ( $getPlugin = mysql_fetch_array($rez) ) {
      $popup .= " <li>".str_replace("_", " ", $getPlugin['PLUGIN_FOLDER'])."</li>\n";
   }
   $popup .= "<ul>\n";
   echo help_popup("popup-unlisted_plugins", lang("Some plugins simply modify an existing feature module."), $popup, "width: 550px;top: -100px;left: 10%;");

   echo "<div id=\"dontsee_myplugin_help\">\n";
   echo " <a href=\"#\" class=\"help_link\" onclick=\"showid('popup-unlisted_plugins');\">".lang("Don't see your plugin listed here")."?</a>\n";
   echo "</div>\n";
}

echo "      <table border=\"0\" cellspacing=\"0\" cellpadding=\"5\" width=\"100%\" id=\"addon_buttons\">\n";

# First row
echo "       <tr>\n";

# NOTE: These loops (first and "Second row" below) go in reverse order so buttons will displayed from toggle outward (right to left),
# with the most recently-added mods being closest to the toggle to make them the most accessable
for ( $n = 4; $n >= 0; $n-- ) {

   # Enough plugins to fill this cell?
   if ( isset($hooked_plugin[$n]) ) {
      # YES - Show next plugin button
      echo "<td align=\"center\" valign=\"middle\" class=\"module_button_cell\">\n";

      # icon img
      echo " ".$hooked_plugin[$n]['icon'];

      # caption text
      echo "<a href=\"#\" ".$hooked_plugin[$n]['link']."';\">".plugin_strip_strings(lang($hooked_plugin[$n]['button_caption_text']))."</a></td>\n";

   } else {
      # NO - Empty table cell
      echo "<td class=\"module_button_cell\">&nbsp;</td>\n";
   }
}
echo "       </tr>\n";

# Spacer row
echo "       <!---spacer row--->\n";
echo "       <tr>\n";
echo "        <td colspan=\"5\" class=\"nopad\"><img src=\"spacer.gif\" width=\"698\" height=\"10\"></td>\n";
echo "       </tr>\n";

# Second row
echo "       <tr>\n";
for ( $n = 8; $n >= 5; $n-- ) {
   if ( isset($hooked_plugin[$n]) ) {
      # Mod button
      echo "<td align=\"center\" valign=\"middle\" class=\"module_button_cell\">\n";
      echo " ".$hooked_plugin[$n]['icon'];
      echo "<a href=\"#\" ".$hooked_plugin[$n]['link']."';\">".plugin_strip_strings(lang($hooked_plugin[$n]['button_caption_text']))."</a></td>\n";

   } else {
      # Empty table cell
      echo "<td class=\"module_button_cell\">&nbsp;</td>\n";
   }

} // End for 4 td's in second row (no </tr> here as with first row because second row ends with toggle button <td> below)


echo "        <!--- STANDARD Modules Toggle -->\n";
echo "        <td align=\"right\" valign=\"bottom\" class=\"nopad\">\n";
echo "         <div id=\"addon_toggle_button\" onClick=\"document.getElementById('addon_features').style.display='none';document.getElementById('standard_features').style.display='block';\">\n";
echo "          ".lang("Click here to show")." <b>".lang("Standard Features")."</b>\n";
echo "         </div>\n";
echo "        </td>\n";
echo "       </tr>\n";
echo "      </table>\n";
echo "     </td>\n";
echo "    </tr>\n";
echo "   </table>\n";
echo "   </div>\n";
echo "   <!-----------------------------------------------------END addon feature table----------------------------------------------------->\n";
echo "   <!--------------------------------------------------------------------------------------------------------------------------------->\n";

?>


  </td>
 </tr>


<?
/*---------------------------------------------------------------------------------------------------------*
   _       _         _        _      _               _    _
  /_\   __| | _ __  (_) _ _  (_) ___| |_  _ _  __ _ | |_ (_)__ __ ___
 / _ \ / _` || '  \ | || ' \ | |(_-<|  _|| '_|/ _` ||  _|| |\ V // -_)
/_/ \_\\__,_||_|_|_||_||_||_||_|/__/ \__||_|  \__,_| \__||_| \_/ \___|
/*---------------------------------------------------------------------------------------------------------*/

# Get license Type
$typelic_data = file_get_contents("../filebin/type.lic");

?>
 <tr>
  <td align="center" valign="top">
   <table border="0" cellpadding="0" cellspacing="0" width="700" align="center" class="feature_sub">
    <tr>
     <td valign="top" width="100%" class="fgroup_title"><? echo lang("Administrative Features"); ?></td>
    </tr>
    <tr>
     <td align="center" valign="top">
      <table border="0" cellspacing="0" cellpadding="8" width="100%">
       <tr>
        <!--- Site Statistics --->
        <td align="center" valign="middle" class="admin_button_cell">
         <? echo $stats['icon']; ?>
         <a href="#" <? echo $stats['link']; ?>><? echo lang("Traffic Statistics"); ?></a></td>

        <!--- Site Backup / Restore --->
        <td align="center" valign="middle" class="admin_button_cell">
         <? echo $backup['icon']; ?>
         <a href="#" <? echo $backup['link']; ?>><? echo $baktxt; ?></a></td>

        <!--- Site Data Tables --->
        <!--- Database Table Manager --->
        <td align="center" valign="middle" class="admin_button_cell">

            <?
         $mouseover_database = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('db_table_off()', 1000);\"";
         # upload_files_btn -- only if they can access File Manager
         if ( hasMod("dbtable") && $webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_DB_MANAGER;", $CUR_USER_ACCESS)) {
            echo "         <div id=\"db_search_btn\" class=\"upload_files_off\" onclick=\"document.location.href='modules/mods_full/database_manager/wizard_start.php';\" ".$mouseover_database.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Create Search")."</div>\n";
            echo "         </div>\n";

            echo "<div id=\"db_table_btn\" class=\"file_manager-off\" style=\"width: 100px;position: relative;\" ".$dbMan['link']." onmouseover=\"showExtras(this.id,'db_search_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('db_table_off()', 1000);\">\n";
         }
            ?>

         <? echo $dbMan['icon']; ?>
         <a href="#" <? echo $dbMan['link']; ?>><? echo $datatxt; ?></a></td>

        <!---Webmaster--->
        <td align="center" valign="middle" class="admin_button_cell">

            <?
         $mouseover_webmaster = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('webmaster_off()', 1000);\"";
         # upload_files_btn -- only if they can access File Manager
         if ( hasMod("secure") && $webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" ) {
            echo "         <div id=\"webmaster_global_btn\" class=\"upload_files_off\" onclick=\"document.location.href='webmaster/global_settings.php';\" ".$mouseover_webmaster.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Global Settings")."</div>\n";
            echo "         </div>\n";

            echo "         <div id=\"webmaster_user_btn\" class=\"upload_files_off\" onclick=\"document.location.href='webmaster/add_user.php';\" ".$mouseover_webmaster.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Add Admin User")."</div>\n";
            echo "         </div>\n";

            echo "<div id=\"webmaster_btn\" class=\"file_manager-off\" style=\"width: 100px;position: relative;\" ".$webmaster['link']." onmouseover=\"showExtras(this.id,'webmaster_global_btn');showExtras(this.id,'webmaster_user_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('webmaster_off()', 1000);\">\n";
         }
            ?>


         <? echo $webmaster['icon']; ?>
         <a href="#" <? echo $webmaster['link']; ?>><? echo lang("Webmaster"); ?></a></td>

<?
# Only show Plugin Manager button if logged-in as WEBMASTER

if ( $_SESSION['hostco']['plugins'] != "OFF" ) {
?>
        <!---Plugins--->
        <td align="center" valign="middle" class="admin_button_cell">

            <?
         $mouseover_addons = "onmouseover=\"setClass(this.id, 'upload_files_on');\" onmouseout=\"setClass(this.id, 'upload_files_off');window.setTimeout('addons_off()', 1000);\"";
         # upload_files_btn -- only if they can access File Manager
         if ( plugins_allowed() && $_SESSION['hostco']['get_more_plugins_link'] != "off" && $webmaster_pref->get("mm_shortcuts") == "on" && $CUR_USER_ACCESS == "WEBMASTER" ) {
            echo "         <div id=\"addons_browse_btn\" class=\"upload_files_off\" onclick=\"window.open('https://addons.soholaunch.com','addonsWind','width=1024px, height=768px, resizable, scrollbars, menubar');\" ".$mouseover_addons.">\n";
            echo "          <div style=\"margin-top: 2px;\" class=\"shortcut_btn-text\">".lang("Browse Addons")."</div>\n";
            echo "         </div>\n";
            echo "<div id=\"addons_btn\" class=\"file_manager-off\" style=\"width: 100px;position: relative;\" ".$plugins['link']." onmouseover=\"showExtras(this.id,'addons_browse_btn');\" onmouseout=\"clearClass(this.id);window.setTimeout('addons_off()', 1000);\">\n";
         }
            ?>

         <? echo $plugins['icon']; ?>
         <a href="#" <? echo $plugins['link']; ?>><? echo lang("Manage Plugins"); ?></a></td>
<?
} // End if CUR_USER_ACCESS == WEBMASTER
?>

        <!---Help Center--->
        <td align="center" valign="middle" class="admin_button_cell">
         <? echo $help_center['icon']; ?>
         <a href="#" <? echo $help_center['link']; ?>><? echo lang("Help Center"); ?></a></td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>

<?
# Show edit page speed dial dropdowns
include("speeddial.inc.php");

# Show visitors online count
include("users_connected.php");


/*---------------------------------------------------------------------------------------------------------*
 _____      _        _   ___
|_   _|_ _ (_) __ _ | | | _ \ ___  _ __  _  _  _ __
  | | | '_|| |/ _` || | |  _// _ \| '_ \| || || '_ \
  |_| |_|  |_|\__,_||_| |_|  \___/| .__/ \_,_|| .__/
                                  |_|         |_|
/*---------------------------------------------------------------------------------------------------------*/
# Is this full version trial license?
if ( $_SESSION['product_mode'] == "trial" ) {

   # Display popup by default if just launched product window (this can be overridden by suppression cookie check below)
   if ( $_SESSION['login_flag'] ) { $popup_display = "none"; } else { $popup_display = "block"; }

   # Trial period expired?
   if ( trial_expired() ) {
      # For popup suppression cookie option
      $popup_name = "trial_expired";

      # Days left: 0
      $status_msg = "<b>".lang("Full version trial period").":</b> <span class=\"red\">".lang("Expired")."</span>.";

      # POPUP: Trial Expired!
      $popup_msg = " <h1 class=\"nomar_top\">".lang("Trial Period Expired")."!</h1>\n";
      $popup_msg .= " <p>".lang("Your free trial of the full version has expired. The advanced modules are now disabled.")."</p>\n";

      $popup_msg .= " <p>".lang("You can keep using all of the basic features forever at no charge, but if you want to get back into the advanced")." \n";
      $popup_msg .= " ".lang("features you'll have to buy a full version license to get them turned on permanently").".</p>";

      $popup_msg .= " <p>".lang("Note").": ".lang("All of your settings, data, etc from the advanced features will be preserved, so you can pick up where you left off after you")."\n";
      $popup_msg .= " ".lang("buy the full version").".</p>\n";

		# Adsense?
		$asense = new userdata("asense");
		if ( $asense->get("id") != "HIDE" && $asense->get("id") != "" ) {
		   $popup_msg .= " <h2>".lang("About the Ads displayed on your site")."</h2>\n";
		   $popup_msg .= " <p>".lang("You may notice advertisements appearing at the bottom of your website").". \n";
		   $popup_msg .= " ".lang("These ads are a way for us to recoup some of the cost of making the basic version completely free").". \n";
		   $popup_msg .= " ".lang("You can make the ads go away by")." <a href=\"http://soholaunch.com/Order_Now.php\" target=\"_blank\">".lang("upgrading to the full version")."</a>, \n";
		   $popup_msg .= " ".lang("or you can continue to use the ad-supported basic version forever for free").".</p>\n";
//		   $popup_msg .= " You can do a lot with the basic version of Soholaunch. If you're building an information-only website \n";
//		   $popup_msg .= " you may not ever need the advanced features like Shopping Cart, Member Logins, etc.\n";
		} else {
			$popup_msg .= " <p>".lang("Once the trial period expires ").lang("You may notice advertisements appearing at the bottom of your website").".  \n";
			$popup_msg .= " ".lang("These ads are a way for us to recoup some of the cost of making the basic version completely free").". \n";
			$popup_msg .= " ".lang("You can make the ads go away by")." <a href=\"http://soholaunch.com/Order_Now.php\" target=\"_blank\">".lang("upgrading to the full version")."</a>, \n";
			$popup_msg .= " ".lang("or you can continue to use the ad-supported basic version forever for free").".</p>\n";
			$popup_msg .= "</p>\n";	 	
		}

   # Trial still good
   } else {
      # For popup suppression cookie option
      $popup_name = "trial_active";

      # X Days left
      $status_msg = "<b>".lang("Full version trial time remaining").":</b> <span class=\"red\">".trial_timeleft(false)."</span>";

      # POPUP: All features unlocked!
      $popup_msg = " <h1 class=\"nomar_top nomar_btm\">".lang("Enjoy the full version")."!</h1>\n";
      $popup_msg .= " <p>".lang("You have")." <span class=\"red bold\">".trial_timeleft()."</span> (and change) ".lang("left to use the advanced features for free")."\n";
      $popup_msg .= " ".lang("When this trial period is up, the advanced features (see list below) will be disabled").".</p>\n";

      $popup_msg .= " <p>".lang("After your trial period has expired, you can keep using all of the basic features forever at no charge, but if you want to get back into the advanced")." \n";
      $popup_msg .= " ".lang("features you'll have to buy a full version license to get them turned on permanently").".</p>";

      $popup_msg .= " <p>".lang("Note").": ".lang("All of your settings, data, etc from the advanced features will be preserved, so you can pick up where you left off after you")."\n";
      $popup_msg .= " ".lang("buy the full version").".</p>\n";

//      $popup_msg .= " <p><b>".lang("Advanced Features").":</b> ".lang("Blog Manager").", ".lang("eNewsletter").", ".lang("Shopping Cart").", ".lang("Member Logins").", ".lang("Event Calendar").", ".lang("Database Table Manager").", ".lang("Backup/Restore")."</p>\n";



		# Adsense?
		$asense = new userdata("asense");
		if ( $asense->get("id") != "HIDE" && $asense->get("id") != "" ) {
		   $popup_msg .= " <h2>".lang("About the Ads displayed on your site")."</h2>\n";
		   $popup_msg .= " <p>".lang("You may notice advertisements appearing at the bottom of your website").". \n";
		   $popup_msg .= " ".lang("These ads are a way for us to recoup some of the cost of making the basic version completely free").". \n";
		   $popup_msg .= " ".lang("You can make the ads go away by")." <a href=\"http://soholaunch.com/Order_Now.php\" target=\"_blank\">".lang("upgrading to the full version")."</a>, \n";
		   $popup_msg .= " ".lang("or you can continue to use the ad-supported basic version forever for free").".</p>\n";
//		   $popup_msg .= " You can do a lot with the basic version of Soholaunch. If you're building an information-only website \n";
//		   $popup_msg .= " you may not ever need the advanced features like Shopping Cart, Member Logins, etc.\n";
		} else {
			$popup_msg .= " <p>".lang("Once the trial period expires ").lang("You may notice advertisements appearing at the bottom of your website").".  \n";
			$popup_msg .= " ".lang("These ads are a way for us to recoup some of the cost of making the basic version completely free").". \n";
			$popup_msg .= " ".lang("You can make the ads go away by")." <a href=\"http://soholaunch.com/Order_Now.php\" target=\"_blank\">".lang("upgrading to the full version")."</a>, \n";
			$popup_msg .= " ".lang("or you can continue to use the ad-supported basic version forever for free").".</p>\n";
			$popup_msg .= "</p>\n";	 	
		}
   }

   # Suppress popup display?
   if ( $_COOKIE[$popup_name] == "suppress" ) { $popup_display = "none"; }

   # POPUP: Full explination of trial status
   echo " <div id=\"trial_popup\" style=\"display: ".$popup_display.";width: 550px;vertical-align: top;position: absolute; top: 10.5%; left: 20%; text-align: left; border: 1px solid #888c8e; background-color: #efefef; z-index:2;\">\n";

   # Message text
   echo "  <div style=\"padding: 5px 10px 0px 10px; margin-bottom: 0px;\">\n";
   echo $popup_msg;
   echo "  </div>\n";

   # Upgrade button
   echo "  <div style=\"text-align: center; padding: 0 0px 5px 0;\">\n";
   echo "   <input type=\"button\" id=\"popup-upgrade_btn\" value=\"Upgrade Now!\" onclick=\"".buynow_onclick()."\" onmouseover=\"setClass(this.id, 'nav_mainon');\" onmouseout=\"setClass(this.id, 'nav_main');\" class=\"nav_main\">\n";
   echo "  </div>\n";

   echo "  <div style=\"text-align: right; padding: 0 10px 10px 0;\">\n";

   # Offer "Don't show this again" cookie option only for 'Trial Expired' popup and only when popuping up automatically at login (and if cookie not already set)
   if ( $popup_name == "trial_expired" && !$_SESSION['login_flag'] && $_COOKIE[$popup_name] != "suppress" ) {
      echo "   <span class=\"red uline hand\" onclick=\"parent.refresher.location.href='refresher_frame.php?todo=suppress_popup&popup_name=".$popup_name."';hideid('trial_popup');\">\n";
      echo "    ".lang("Do not display this message anymore").".</span>\n";
   }

   # Show reset option if in debug mode
   if ( debug_mode() ) {
      echo "   <br/><br/><span class=\"red uline hand\" onclick=\"parent.refresher.location.href='refresher_frame.php?todo=unsetcookie&cookiename=".$popup_name."';hideid('trial_popup');\" style=\"diplay: block;margin-top: 10px;\">\n";
      echo "    ".lang("Reset popup display preference").".</span>\n";
   }
   echo "  </div>\n";

   # Closebar
   echo "  <div id=\"error_closebar\" onclick=\"hideid('trial_popup');\" onmouseover=\"setClass(this.id, 'hand bg_red_d7 white right');\"  onmouseout=\"setClass(this.id, 'hand bg_red_98 white right');\" class=\"hand bg_red_98 white right\" style=\"padding: 3px;\">[x] close</div>\n";
   echo " </div>\n";


   # Trial status bar (at bottom of main menu)
   echo "<div id=\"trial_status\" onclick=\"toggleid('trial_popup');\">\n";
   echo $status_msg;
   echo "</div>\n";

} // End if trial mode

eval(hook("main_menu.php:bottom-before-javascript"));

# TESTING: Display license type
//echo "<br/>License Type: [".$typelic_data."]\n";

if($wiz == 1){
	echo "<script language=\"javascript\">\n";
	echo " alert('".lang("Congratulations")."! ".lang("Your website setup is complete").".\\n\\n".lang("You can now VIEW your new site by clicking the View Website")."\\n".lang("button on the top of your screen or begin editing")."\\n".lang("page content with Open/Edit Page(s)").".');\n";
	echo "</script>\n";
}

# Refresh main menu once to account for css changes in build updates (like when upgrading to v4.9 from v4.8.5)
if ( !$_SESSION['login_flag'] && $_SESSION['refresh_css'] ) {
	echo "<script language=\"javascript\">\n";
	echo " window.setTimeout('parent.body.location.reload()', 5000);\n";
	echo "</script>\n";
}

# User is now logged-in, so don't display any 'on login' messages when simply returning to the main menu (vs. getting to it after launching the window)
# Note: This is reset to 'false' at the top of update_client.php, that way it accounts for re-launching the window (treats it as logging-in) when session still exists
$_SESSION['login_flag'] = true;
$_SESSION['refresh_css'] = false; // And they don't need the main menu to auto-refresh next time they hit it
?>

<script type="text/javascript">
// Moved to bottom because it kept throwing a js error in firebug...maybe because function hadn't fully loaded in header when this was called at the top
parent.header.flip_header_nav('MAIN_MENU_LAYER');

</script>
</body>
</html>

<?php
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("This is the Main Menu. All features can be accessed from here.")."<br/>";

# Build into standard module template
$module = new smt_module($module_html);
$module->add_breadcrumb_link(lang("Main Menu"), "program/main_menu.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/file_manager-enabled.gif";
$module->heading_text = lang("Main Menu");
$module->description_text = $instructions;
$module->good_to_go();
?>