<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Author: 			Mike Morrison
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com
##
## COPYRIGHT NOTICE
## Copyright 1999-2007 Soholaunch.com, Inc.  All Rights Reserved.
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
#===============================================================================================
# Forms Manager Module v2.0 - Shared PHP functions for forms manager scripts
#===============================================================================================
require_once("../../includes/product_gui.php");
# Returns nothing or an asterisk depending on whether passed field id is a required field
function asterisk($field_id) {
   if ( $_SESSION['form_fields'][$field_id]['required'] == "yes" ) {
      return "<span class=\"asterisk\">*</span>\n";
   }
}

# Called from edit_form.php when form is loaded for editing to make sure all style properties have a value
function set_formstyle_default($cssprop, $defaultvalue) {
   if ( $_SESSION['form_properties']['style'][$cssprop] == "" || $forceset) {
      $_SESSION['form_properties']['style'][$cssprop] = $defaultvalue;
   }
}

# Saves some keystrokes vs. typeing out session array
function formStyle($whattoget) {
   return $_SESSION['form_properties']['style'][$whattoget];
}

?>