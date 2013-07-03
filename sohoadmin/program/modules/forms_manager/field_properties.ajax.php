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
# Forms Manager Module v2.0 - Edit field properties
# NOTES:
#   Called from edit_form.php via ajaxDo()
#   $_REQUEST['field_id'] must be available at all times
#===============================================================================================

error_reporting(E_PARSE);
session_start();
require_once("../../includes/product_gui.php");
include_once("_forms_manager_functions.inc.php");

$test=range(0, 10);
$index=2;
$data="---here---";
$result = array_merge(array_slice($test, 0, $index), array($data), array_slice($test, $index));
//echo "<pre>";var_dump($result);echo "</pre><br/><br/>";


# Pull field properties from db
if ( $_REQUEST['field_id'] == "" ) {
   echo "[No field selected. Click on a field to edit its properties.]";


} else { // Display properties
   $getField = $_SESSION['form_fields'][$_REQUEST['field_id']];

   # Determines which types of fields can have which types of properties
   include_once("nocando.inc.php");

   /*---------------------------------------------------------------------------------------------------------*
     _____ __         _
    / ___// /  ___   (_)____ ___  ___
   / /__ / _ \/ _ \ / // __// -_)(_-<
   \___//_//_/\___//_/ \__/ \__//___/

   # Various actions relating to choices for dropdowns, etc
   /*---------------------------------------------------------------------------------------------------------*/
   # ADD choice
   if ( $_GET['todo'] == "add_choice" ) {
      # Add new choice directly after passed choice number
      $newchoicedata = array('text' => "", 'default' => "");

      # After this field...
      $keyup = $_GET['afterchoice'];

      # At this position
      $keyat = $_GET['afterchoice'] + 1;

      # Form a new array by merging: The choice array up until the pre-add choice + The new option + the rest of the array
      $newchoices = array_merge(array_slice($getField['choices'], 0, $keyat), array($newchoicedata), array_slice($getField['choices'], $keyat));

      $getField['checked'] = $_GET['newdflist'];
      $getField['choices'] = $newchoices;

      $_SESSION['form_fields'][$_REQUEST['field_id']] = $getField;

   } // End if add_choice

   # REMOVE choice
   if ( $_GET['remove_choice'] != "" ) {
      # Remove element by numerical key passed
      unset($getField['choices'][$_GET['remove_choice']]);

      # Reset numerical index
      $getField['choices'] = array_values($getField['choices']);
      $getField['checked'] = $_GET['newdflist'];

      $_SESSION['form_fields'][$_REQUEST['field_id']] = $getField;
   }


   # HTML output container var
   $disHTML = "";

   $disHTML .= " <form name=\"properties_form\" id=\"properties_form\">\n";
   $disHTML .= " \n";

   # [?] title
   $disHTML .= " <label><span class=\"help_link\" onclick=\"toggleid('help-field_title');\">[?]</span>Field title/label <span style=\"color: #ff0000;\">(required)</span></label>\n";

   # field_title

   # Output javascript to update title field after delay (onkeyup causing DOS attack errors on some servers)
   ob_start();
?>
<script type="text/javascript">
//function save_titlefield() {
//   alert('something');
//}
//function titlefield() {
//   alert('asdf');
//      showtime = setInterval("save_titlefield()", 1000);
//}
</script>
<?php
   $disHTML .= ob_get_contents();
   ob_end_clean();

//   $disHTML .= " <input type=\"text\" id=\"field_title\" name=\"field_title\" value=\"".stripslashes($getField['title'])."\" class=\"textfield\" onblur=\"saveField('".$_REQUEST['field_id']."', 'title', encodeURI(this.value));\">\n";
//   $disHTML .= " <input type=\"text\" id=\"field_title\" name=\"field_title\" value=\"".stripslashes($getField['title'])."\" class=\"textfield\" onclick=\"start_titlefield();\" onkeyup=\"clearInterval(showtime);start_titlefield();\">\n";
   $disHTML .= " <input type=\"text\" id=\"field_title\" name=\"field_title\" value=\"".stripslashes($getField['title'])."\" class=\"textfield\" onkeyup=\"start_titlefield('".$_REQUEST['field_id']."', 'title', encodeURI(this.value.replace('\'', ':squote:')));\">\n";


   # help-field_title
   $disHTML .= " <p id=\"help-field_title\" style=\"display: none;\"><strong>Field title/label -</strong> You must specify a title for every field. \n";
   $disHTML .= " Aside from displaying on your form as label text for this field, the field title also determines the column name in the data table where the \n";
   $disHTML .= " submitted info for this field will be saved (if you configure the form to save to data table when you drop it on a page). \n";
   $disHTML .= " The field title also listed next to the submitted data in auto-repsonse emails sent to you (the webmaster) and the visitor.\n";
   $disHTML .= " If you do not want the title to display on the form, set the 'Field label position?' option below (under Style and Formatting) to 'hidden'.</p>\n";

   # instructions?
   if ( in_array($getField['field_type'], $cando_notes) ) {
      $disHTML .= " <label>Instructions/Notes (optional)</label>\n";
      $disHTML .= " <textarea id=\"field_notes\" name=\"field_notes\" onkeyup=\"saveField('".$_REQUEST['field_id']."', 'notes', encodeURI(this.value));\">".stripslashes($getField['notes'])."</textarea>\n";
   }

   # required?
   if ( !in_array($getField['field_type'], $nocando_required) ) {
      $selectedno = ""; $selectedyes = "";
      if ( $getField['required'] == "yes" ) { $selectedyes = " checked"; } else { $selectedno = " checked"; }
      $disHTML .= " <fieldset>\n";
      $disHTML .= "  <legend>Require field?</legend>\n";
      $disHTML .= "  <span class=\"row\">\n";
      $disHTML .= "   <span class=\"radio_option\" onclick=\"toggle_checkbox('required_yes', 'check');saveField('".$_REQUEST['field_id']."', 'required', radiovalue('properties_form', 'required_yesno'));\">\n";
      $disHTML .= "    <span class=\"props-titletxt\">Yes</span>\n";
      $disHTML .= "    <input type=\"radio\" name=\"required_yesno\" id=\"required_yes\" value=\"yes\"".$selectedyes."/>\n";
      $disHTML .= "   </span>\n";
      $disHTML .= "   <span class=\"radio_option\" onclick=\"toggle_checkbox('required_no', 'check');saveField('".$_REQUEST['field_id']."', 'required', radiovalue('properties_form', 'required_yesno'));\">\n";
      $disHTML .= "    <span class=\"props-titletxt\">No</span>\n";
      $disHTML .= "    <input type=\"radio\" name=\"required_yesno\" id=\"required_no\" value=\"no\"".$selectedno."/>\n";
      $disHTML .= "   </span>\n";
      $disHTML .= "  </span>\n";

      $disHTML .= " </fieldset>\n";
   } // End if can be required

   # choices?
   if ( in_array($getField['field_type'], $cando_choice) ) {
      $disHTML .= "<fieldset class=\"choices\">\n";
      $disHTML .= " <legend>Choices</legend>\n";
      $disHTML .= " <div id=\"choices\" style=\"border: 1px solid #fffaad;\">\n"; // Keep border here inline or display will break for no reason

      $max = count($getField['choices']);

      # Build "checked" options
      $checked_options = "";

      for ( $c = 0; $c < $max; $c++ ) {
         $optionvalue = supersterilize($getField['choices'][$c]['text']);
         $disHTML .= "  <span class=\"row\">\n";

         $disHTML .= "   <input class=\"choicebox\" type=\"text\" id=\"choice-".$c."\" value=\"".stripslashes($getField['choices'][$c]['text'])."\" onkeyup=\"saveChoice('".$_REQUEST['field_id']."', this.id, encodeURI(this.value));\"/>\n";

         # [-]
         $disHTML .= "   <span class=\"choicebtn-minus\" onclick=\"removeChoice('".$getField['field_id']."', '".$c."');\">(-)</span>\n";

         # [+]
         $disHTML .= "   <span class=\"choicebtn-plus\" onclick=\"addChoice('".$getField['field_id']."', '".$c."');\">(+)</span>\n";

         # [v]
         # multi alllowed?
         if ( in_array($getField['field_type'], $cando_multichecked) ) { $singleormulti = "multi"; } else { $singleormulti = "single"; }
         # already checked?
         if ( eregi($c.";", $getField['checked']) ) { $chkstyle = "choicebtn-check-on"; } else { $chkstyle = "choicebtn-check"; }

         $disHTML .= "   <span id=\"checkbtn-".$c."\" class=\"".$chkstyle."\" onclick=\"mkChecked('".$_REQUEST['field_id']."', '".$singleormulti."', '".$c."');\">(&radic;)</span>\n";

         $disHTML .= "   <div class=\"ie_cleardiv\"></div>\n";
         $disHTML .= "  </span>\n"; // End class=row
      }
      $disHTML .= "  <div class=\"ie_cleardiv\"></div>\n";

      # Apply Changes
      $disHTML .= " </div>\n";

      $disHTML .= " <div class=\"ie_cleardiv\"></div>\n";

      # numchoices - hidden field containing total number of choices so they can be looped through by mkChecked()
      $disHTML .= "  <input id=\"numchoices\" type=\"hidden\" class=\"hidden\" value=\"".$c."\"/>\n";

      # checked - hidden field containing pre-checked item(s)
      $disHTML .= "  <input id=\"checked\" type=\"hidden\" class=\"hidden\" value=\"".$getField['checked']."\">\n";


      $disHTML .= "</fieldset>\n";
   }

   $disHTML .= "<fieldset class=\"radio_group\">\n";
   $disHTML .= " <legend>Style and Formatting</legend>\n";

   # heading?
   if ( $getField['field_type'] == "heading" ) {
      $disHTML .= " <label>Heading level</label>\n";
      $disHTML .= " <select id=\"optionlayout\" onchange=\"saveStyle('".$_REQUEST['field_id']."', 'heading_level', this.value);\">\n";
      for ( $h = 1; $h <= 6; $h++ ) {
         # selected?
         if ( $getField['style']['heading_level'] == $h ) { $selected = " selected"; } else { $selected = ""; }
         $disHTML .= "  <option value=\"".$h."\"".$selected.">Level ".$h."</option>\n";
      }
      $disHTML .= " </select>\n";
   }

   # maxlength?
   if ( in_array($getField['field_type'], $cando_maxlength) ) {
      $disHTML .= " <span class=\"row\">\n";
      $disHTML .= "  <span class=\"props-inline_title\">Character Limit:</span>\n";
      $disHTML .= "  <input type=\"text\" id=\"field_maxlength\" value=\"".$getField['style']['maxlength']."\" onblur=\"saveStyle('".$_REQUEST['field_id']."', 'maxlength', this.value);\" style=\"width: 40px;\">\n";
      $disHTML .= " </span>\n";
   }

   # width?
   if ( !in_array($getField['field_type'], $nocando_width) ) {
      $disHTML .= " <span class=\"row\">\n";
      $disHTML .= "  <span class=\"props-inline_title\">Width:</span>\n";
      $disHTML .= "  <input type=\"text\" id=\"field_width\" value=\"".$getField['width']."\" onblur=\"saveField('".$_REQUEST['field_id']."', 'width', this.value);\">\n";
      $disHTML .= "  <span class=\"props-inline_title props-notetxt\">px</span>\n";
      $disHTML .= " </span>\n";
   }

   # height?
   if ( $getField['field_type'] == "textarea" ) {
      $disHTML .= " <span class=\"row\" style=\"margin-top: 3px;\">\n";
      $disHTML .= "  <span class=\"props-inline_title\">Height:</span>\n";
      $disHTML .= "  <input type=\"text\" id=\"field_height\" value=\"".$getField['style']['height']."\" onblur=\"saveStyle('".$_REQUEST['field_id']."', 'height', this.value);\">\n";
      $disHTML .= "  <span class=\"props-inline_title props-notetxt\">px</span>\n";
      $disHTML .= " </span>\n";
   }

   # title position?
   if ( !in_array($getField['field_type'], $nocando_toptitle) ) {
      $selectedtop = ""; $selectedleft = "";
//      $selectedhidden = "";
      # Note: formStyle value is global from Form Styles | $getField value is for this specific field
      # ...if they conflict, specific field setting wins
      if ( $getField['style']['titlepos'] == "top" || (formStyle("label_position") == "top" && $getField['style']['titlepos'] == "") ) {
         $selectedtop = " checked";
      } elseif ( $getField['style']['titlepos'] == "hidden" || (formStyle("label_position") == "hidden" && $getField['style']['titlepos'] == "") ) {
         $selectedhidden = " checked";
      } else {
         $selectedleft = " checked";
      }
      $disHTML .= " <label>Field label position?</label>\n";
      $disHTML .= " <span class=\"row\">\n";
      $disHTML .= "  <span class=\"radio_option\" onclick=\"toggle_checkbox('titlepos_left', 'check');saveStyle('".$_REQUEST['field_id']."', 'titlepos', radiovalue('properties_form', 'title_position'));\">\n";
      $disHTML .= "   <span class=\"props-titletxt\">To the left</span>\n";
      $disHTML .= "   <input type=\"radio\" name=\"title_position\" id=\"titlepos_left\" value=\"left\"".$selectedleft."/>\n";
      $disHTML .= "  </span>\n";
      $disHTML .= "  <span class=\"radio_option\" onclick=\"toggle_checkbox('titlepos_top', 'check');saveStyle('".$_REQUEST['field_id']."', 'titlepos', radiovalue('properties_form', 'title_position'));\">\n";
      $disHTML .= "   <span class=\"props-titletxt\">Above</span>\n";
      $disHTML .= "   <input type=\"radio\" name=\"title_position\" id=\"titlepos_top\" value=\"top\"".$selectedtop."/>\n";
      $disHTML .= "  </span>\n";
      $disHTML .= "  <span class=\"radio_option\" onclick=\"toggle_checkbox('titlepos_hidden', 'check');saveStyle('".$_REQUEST['field_id']."', 'titlepos', radiovalue('properties_form', 'title_position'));\">\n";
      $disHTML .= "   <span class=\"props-titletxt\">Hidden</span>\n";
      $disHTML .= "   <input type=\"radio\" name=\"title_position\" id=\"titlepos_hidden\" value=\"hidden\"".$selectedhidden."/>\n";
      $disHTML .= "  </span>\n";
      $disHTML .= "  <div class=\"ie_cleardiv\"></div>\n";
      $disHTML .= " </span>\n";
   }

   # List layout style?
   if ( in_array($getField['field_type'], $cando_listlayout) ) {
      $selectedinline = ""; $selectedlist = "";
      if ( $getField['style']['optionlayout'] == "inline" ) { $selectedinline = " selected"; } else { $selectedlist = " selected"; }
      $disHTML .= " <label>Layout style for choices?</label>\n";
      $disHTML .= " <select id=\"optionlayout\" onchange=\"saveStyle('".$_REQUEST['field_id']."', 'optionlayout', this.value);\">\n";
      $disHTML .= "  <option value=\"inline\"".$selectedinline.">All on one line</option>\n";
      $disHTML .= "  <option value=\"list\"".$selectedlist.">List-style</option>\n";
      $disHTML .= " </select>\n";
   }

   $disHTML .= "</fieldset>\n";


} // End if $_REQUEST['field_id'] != ""

$disHTML .= "<div class=\"ie_cleardiv\"></div>\n";

$disHTML .= "</form>\n";

echo $disHTML;
?>