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
# Forms Manager Module v2.0 - Edit field properties
# NOTES:
#   Called from edit_form.php via ajaxDo()
#   $_REQUEST['field_id'] must be available at all times
#===============================================================================================

error_reporting(E_PARSE);
session_start();
include($_SESSION['product_gui']);

# Pull field properties from db
if ( $_REQUEST['field_id'] == "" ) {
   echo "[No field selected. Click on a field to edit its properties.]";


} else { // Display properties
   $getField = $_SESSION['form_fields'][$_REQUEST['field_id']];

   # Determines which types of fields can have which types of properties
   include_once("nocando.inc.php");

   $disHTML = "";

//echo "[".$_REQUEST['field_id']."]<br/>";
//echo testArray($getField);
//echo testArray($_SESSION['form_fields']);
?>

<!---==============================================-->
<!---MUST BE REMOVED WHEN FINISHED OR IE WILL BREAK-->
<link rel="stylesheet" type="text/css" href="edit_properties.css"/>
<!---MUST BE REMOVED WHEN FINISHED OR IE WILL BREAK-->
<!---==============================================-->

<form name="field_properties_form" id="field_properties_form">

<!---field_title-->
<label>Field Title</label>
<input type="text" id="field_title" name="field_title" value="<? echo $getField['title']; ?>" class="textfield" onkeyup="saveField('<? echo $_REQUEST['field_id']; ?>', 'title', this.value);">

<?
   # REQUIRED?
   if ( !in_array($getField['field_type'], $nocando_required) ) {

      # required_yesno - which is selected?
      $selectedno = ""; $selectedyes = "";
      if ( $getField['required'] == "yes" ) { $selectedyes = " checked"; } else { $selectedno = " checked"; }
?>
<!---required_yesno-->
<fieldset class="radio_group">
 <legend>Require field?</legend>

 <span class="radio_option" onclick="toggle_checkbox('required_yes', 'check');saveField('<? echo $_REQUEST['field_id']; ?>', 'required', radiovalue('field_properties_form', 'required_yesno'));">
  <span class="props-titletxt">Yes</span>
  <input type="radio" name="required_yesno" id="required_yes" value="yes"<? echo $selectedyes; ?>/>
 </span>

 <span class="radio_option" onclick="toggle_checkbox('required_no', 'check');saveField('<? echo $_REQUEST['field_id']; ?>', 'required', radiovalue('field_properties_form', 'required_yesno'));">
  <span class="props-titletxt">No</span>
  <input type="radio" name="required_yesno" id="required_no" value="no"<? echo $selectedno; ?>/>
 </span>

</fieldset>
<?
   } // End if can be required

   # multiple choice?
   if ( in_array($getField['field_type'], $cando_choice) ) {
      $disHTML .= "<fieldset>\n";
      $disHTML .= " <legend>Choices</legend>\n";

      # choices - start out with 3, use innerHTML to add more
      $disHTML .= " <div id=\"props-choices-container\">\n";
      $disHTML .= "  <input type=\"text\" id=\"choice-0\" value=\"".$getField['choices'][0]."\" onkeyup=\"saveField('".$_REQUEST['field_id']."', 'choices', this.value);\"/>\n";
      $disHTML .= "  <input type=\"text\" id=\"choice-1\" value=\"".$getField['choices'][1]."\" onkeyup=\"saveField('".$_REQUEST['field_id']."', 'choices', this.value);\"/>\n";
      $disHTML .= "  <input type=\"text\" id=\"choice-2\" value=\"".$getField['choices'][2]."\" onkeyup=\"saveField('".$_REQUEST['field_id']."', 'choices', this.value);\"/>\n";
      $disHTML .= " </div>\n";
      $disHTML .= "</fieldset>\n";
   }


   # WIDTH?
   if ( !in_array($getField['field_type'], $nocando_width) ) {
      $disHTML .= "<fieldset>\n";
      $disHTML .= " <span class=\"props-inline_title\">Width:</span>\n";
      $disHTML .= " <input type=\"text\" id=\"field_width\" value=\"".$getField['width']."\" onblur=\"saveField('".$_REQUEST['field_id']."', 'width', this.value);\">\n";
      $disHTML .= " <span class=\"props-inline_title props-notetxt\">px</span>\n";
      $disHTML .= "</fieldset>\n";
   }

   # toptitle?
   if ( !in_array($getField['field_type'], $nocando_toptitle) ) {
      $selectedtop = ""; $selectedleft = "";
      if ( $getField['titlepos'] == "top" ) { $selectedtop = " checked"; } else { $selectedleft = " checked"; }

      $disHTML .= "<fieldset>\n";
      $disHTML .= " <legend>Title above field?</lagend>\n";
//      $disHTML .= " <select id=\"title_position\" onchange=\"saveField('".$_REQUEST['field_id']."', 'toptitle', this.value);\">\n";

      $disHTML .= " <span class=\"radio_option\" onclick=\"toggle_checkbox('titlepos_top', 'check');saveField('".$_REQUEST['field_id']."', 'titlepos', radiovalue('field_properties_form', 'title_position'));\">\n";
      $disHTML .= "  <span class=\"props-titletxt\">Yes</span>\n";
      $disHTML .= "  <input type=\"radio\" name=\"title_position\" id=\"titlepos_top\" value=\"top\"".$selectedtop."/>\n";
      $disHTML .= " </span>\n";
      $disHTML .= " <span class=\"radio_option\" onclick=\"toggle_checkbox('titlepos_left', 'check');saveField('".$_REQUEST['field_id']."', 'titlepos', radiovalue('field_properties_form', 'title_position'));\">\n";
      $disHTML .= "  <span class=\"props-titletxt\">No</span>\n";
      $disHTML .= "  <input type=\"radio\" name=\"title_position\" id=\"titlepos_left\" value=\"left\"".$selectedleft."/>\n";
      $disHTML .= " </span>\n";

      $disHTML .= "</fieldset>\n";
   }


} // End if $_REQUEST['field_id'] != ""

$disHTML .= "</form>\n";

echo $disHTML;
?>