<?
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
$form_container_id = $_SESSION['form_properties']['form_filename']."-container";

?>
<style>
/* Generated CSS theme for user-created form */

/* Workaround to fix border on floated elements in IE */
.ie_cleardiv {
   display: block;
   clear: both;
   float: none;
   margin: 0;
   /*border: 1px dotted red;*/
}
#<? echo $form_container_id; ?> * {
   font-family: Trebuchet MS, arial, helvetica, sans-serif;
   font-size: 12px;
   text-align: left;
}
#<? echo $form_container_id; ?> {
   margin: 10px;
   text-align: left;
}
.field-container {
   display: block;
   clear: both;
   margin-bottom: 6px;
   vertical-align: top;
   /*border: 1px solid red;*/
}
.asterisk {
   color: red;
}

.instructions {
   margin-top: 0;
   color: #2e2e2e;
}

.myform-field_title, .myform-field_title-left {
   font-size: 12px;
   font-weight: bold;
   margin-bottom: 0;
}
.myform-field_title-left {
   display: block;
   float: left;
   margin-right: 15px;
   /*margin-top: 12px;*/
   margin-top: 2px;
   /*border: 1px solid red;*/
}

.myform-input_container, .myform-formfield_container {
   display: block;
   float: left;
   margin-top: 0;
   font-size: 11px;
}


/* Headings - <h1>...<h6> */
/*---------------------------------------------------------------------------------------------------------*/
#<? echo $form_container_id; ?> h1, #<? echo $form_container_id; ?> h2, #<? echo $form_container_id; ?> h3, #<? echo $form_container_id; ?> h4, #<? echo $form_container_id; ?> h5, #<? echo $form_container_id; ?> h6 {
   margin-bottom: 0;
}
#<? echo $form_container_id; ?> h1 {
   font-size: 17px;
}
#<? echo $form_container_id; ?> h2 {
   font-size: 15px;
}
#<? echo $form_container_id; ?> h3 {
   font-size: 14px;
}
#<? echo $form_container_id; ?> h4 {
   font-size: 13px;
}
#<? echo $form_container_id; ?> h5 {
   font-size: 12px;
}
#<? echo $form_container_id; ?> h6 {
   font-size: 11px;
}
</style>
<?
$disCSS = ob_get_contents();
ob_end_clean();
echo $disCSS;
?>