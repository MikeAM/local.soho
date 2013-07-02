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
# Forms Manager Module v2.0 - Generate CSS rules for form html to be written to file
#===============================================================================================

error_reporting(E_PARSE);
session_start();
include_once($_SESSION['product_gui']);

# Determines which types of fields can have which types of properties
include_once("nocando.inc.php");

$justmodifed_fieldid = ""; // Used to show field w/yellow bg if it just had an action performed on it

//echo testArray($_SESSION['form_fieldsort']);

# output buffer - faster than echo'ing every line
ob_start();

# Use user-defined form name for referring to form in css rules
//$form_container_id = $_SESSION['form_properties']['form_filename']."-container";

?>
<style title="userform-inline_css">
/* Generated CSS theme for user-created form */

/* Hack to fix border on floated elements in IE */
.ie_cleardiv {
   display: block;
   clear: both;
   float: none;
   margin: 0;
   /*border: 1px dotted red;*/
}

.field-container {
   display: block;
   clear: both;
   margin-bottom: 6px;
   vertical-align: top;
   font-family: <? echo formStyle("label_font"); ?>;
   /*border: 1px solid red;*/
}
.asterisk {
   color: red;
}

.instructions {
   margin-top: 0;
   color: #2e2e2e;
   font-family: <? echo formStyle("label_font"); ?>;
   font-size: <? echo (formStyle("label_fontSize") + 1); ?>px;
   line-height: 1.1em !important;
}

.myform-field_title-top,
.myform-field_title-left {
   font-size: <? echo formStyle("label_fontSize"); ?>px;
   font-weight: <? echo formStyle("label_weight"); ?>;
   font-family: <? echo formStyle("label_font"); ?>;
   margin-bottom: 0;
   color: #<? echo formStyle("label_color"); ?>;
   border-width: 1px;
   border-color: #ccc;
   border-style: hidden;
}
.myform-field_title-left {
   display: block;
   float: left;
   margin-right: 15px;
   /*margin-top: 12px;*/
   margin-top: 2px;
   text-align: <? echo formStyle("label_textalign"); ?>;
   /*border: 1px solid red;*/
}

.myform-field_title-hidden {
   display: none;
}

.myform-input_container, .myform-formfield_container {
   display: block;
   float: left;
   margin-top: 0;
   font-size: 11px;
}

#form_body_container h1,
#form_body_container h2,
#form_body_container h3,
#form_body_container h4,
#form_body_container h5,
#form_body_container h6 {
   font-family: <? echo formStyle("label_font"); ?>;
   margin-bottom: 0;
}
</style>
<?
$disCSS = ob_get_contents();
ob_end_clean();
echo $disCSS;
?>