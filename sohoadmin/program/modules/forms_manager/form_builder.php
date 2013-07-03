<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##      
## Author: 			Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE                                                     
## Copyright 1999-2005 Soholaunch.com, Inc.  All Rights Reserved.       
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

/*------------------------------------------------------------------------------------------------------------------------------------------*
.########..#######..########..##.....##.......########..##.....##.####.##.......########..########.########..................................
.##.......##.....##.##.....##.###...###.......##.....##.##.....##..##..##.......##.....##.##.......##.....##.................................
.##.......##.....##.##.....##.####.####.......##.....##.##.....##..##..##.......##.....##.##.......##.....##.................................
.######...##.....##.########..##.###.##.......########..##.....##..##..##.......##.....##.######...########..................................
.##.......##.....##.##...##...##.....##.......##.....##.##.....##..##..##.......##.....##.##.......##...##...................................
.##.......##.....##.##....##..##.....##.......##.....##.##.....##..##..##.......##.....##.##.......##....##..................................
.##........#######..##.....##.##.....##.......########...#######..####.########.########..########.##.....##.................................
/*------------------------------------------------------------------------------------------------------------------------------------------*/

session_start();
error_reporting(E_PARSE);

# Primary interface include
require_once("../../includes/product_gui.php");

include_once("form_dbcheck_inc.php"); // Checks for/Creates 'forms_' tables (also includes some shared functions)
include("form_manage-class.php"); // The real workhorse of this module
include("form_field-class.php"); // The real workhorse of this module

?>

<HTML>
<HEAD>
<TITLE>Forms Manager 2.0</TITLE>

<META HTTP-EQUIV="Content-Type" content="text/html; charset=utf-8">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">

<link rel="stylesheet" href="../../product_gui.css">

<script language="JavaScript">
<!--
function preview(form) {
	previewWindow.location = 'preview_form.php?f='+form;	
}

function chkkill(form,fid) {
   var rusure = window.confirm('WARNING!! This process cannot be undone!\nAre you sure you want to  permanently delete the form "'+form+'" and all of its fields?');
   if (rusure != false) { window.location = 'forms_manager.php?do=killform&kfile='+form+'&kid='+fid; }
}

//-->
</script>

</head>

<body bgcolor="white" text="black" link="darkblue" vlink="darkblue" alink="darkblue" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="scrollLayer" style="position:absolute; visibility:visible; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000">
<center>

<?


# New module object    
#:::::::::::::::::::::::::::::::::::
$dMod = new feature_module();

/*=============================================================================================*
    ___        __     __   ______ _        __     __
   /   |  ____/ /____/ /  / ____/(_)___   / /____/ /
  / /| | / __  // __  /  / /_   / // _ \ / // __  / 
 / ___ |/ /_/ // /_/ /  / __/  / //  __// // /_/ /  
/_/  |_|\__,_/ \__,_/  /_/    /_/ \___//_/ \__,_/   

/*=============================================================================================*/
if ( ($do == "add_field" || $do == "save_field") && $form_id != "" ) {
   
   $noDice = ""; // Error field names go here
   $dbFlds = array(); // Cleared for db
   
   # Check for required fields
   $req = explode(";", $req_fields);
   foreach ( $req as $fldname ) {
      if ( ${$fldname} == "" ) { $noDice .= $fldname; }
   }
   
   # Make sure options have been specified, where applicable
   if ( ($FIELD_TYPE == "select" || $FIELD_TYPE == "radio" || $FIELD_TYPE == "checkboxes") && strlen($OPT_LABELS) < 2 ) {
      $noDice .= "OPT_LABELS";
   } else {
      $dbFlds['OPT_LABELS'] = $OPT_LABELS;
   }   
      
   
   // Build array of submitted data (to populate object again)
   ##=============================================================
   foreach ( $_POST as $nam=>$val ) {
      if ( eregi($nam, $db_cols) ) {
         $dbFlds[$nam] = $val;
      }
   }
   
   if ( $noDice == "" ) {
      echo js_alert("Format option fields and write to database!");
      $do = "edit_field";
      
   } else {
      //echo "No Dice = (<b class=\"btn_red\">".$noDice."</b>)<br>";
      $do = "edit_field";
   }

} elseif ( $do == "add_field" && $form_id == "" ) {
   echo "<b class=\"btn_red\">Invalid Form ID: [$form_id]</b>\n"; exit;
   
} // End if adding new field


/*=============================================================================================*
 ______     _  _  _     ______                      
|  ____|   | |(_)| |   |  ____|                     
| |__    __| | _ | |_  | |__  ___   _ __  _ __ ___  
|  __|  / _` || || __| |  __|/ _ \ | '__|| '_ ` _ \ 
| |____| (_| || || |_  | |  | (_) || |   | | | | | |
|______|\__,_||_| \__| |_|   \___/ |_|   |_| |_| |_|

/*=============================================================================================*/
if ( $do == "edit_form" && $form_id != "" ) {
   
   // Show form autopsy
   
   $do = "edit_field"; // For now
   
}

if ( $do == "edit_field" && $form_id != "" ) {
   
   /// New form object
   ###:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
   $dForm = new form_manage($form_id);
   $dForm->build_jscript();
   $dForm->build_surgery();
   $module_title = $lang["Forms Manager"].": ".$dForm->prop['FORM_NAME'];
   
   /// New form field object
   /*#:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
   $dField = new form_field($form_id);
   $dField->doval = "add_field";
   
   if ( isset($dbFlds) ) {
      $dField->set_props($dbFlds);
   }
   
   $dMod->add_fgroup($module_title, $dField->add_edit());

} // End Build Fields Routine


# Output completed module display
echo $dMod->make_module($module_title);


?>
  </CENTER>
</div>
</BODY>
</HTML>