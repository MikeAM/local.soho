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
# Forms Manager Module v2.0 - Generate final form html to be written to file
#===============================================================================================

error_reporting(E_PARSE);
session_start();
include_once($_SESSION['product_gui']);

# Determines which types of fields can have which types of properties
include_once("nocando.inc.php");

# Shared forms-related php functions
include_once("_forms_manager_functions.inc.php");

$justmodifed_fieldid = ""; // Used to show field w/yellow bg if it just had an action performed on it

//echo testArray($_SESSION['form_fieldsort']);

# output buffer - faster than echo'ing every line
ob_start();
//$form_container_id = $_SESSION['form_properties']['form_filename']."-container";
//echo testArray($_SESSION['form_fields']);
//# File fields in form?
//if ( array_search("upload", $_SESSION['form_fields']) !== false ) {
//   echo "Has upload field"; exit;
//} else {
//   echo "No upload field"; exit;
//}

echo "<!---Begin form display-->\n";
echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"pgm-form_submit.php\">\n";

# Generate CSS rules based on user styles
include("myform.css.php");

# userform - START form html
echo "<!---Begin form display-->\n";
if ( formStyle('form_body-background-color') == "" ) { $bgcolorstyle = "background-color: transparent;"; } else { $bgcolorstyle = "background-color: #".formStyle('form_body-background-color').";"; }
echo "<div id=\"form_body_container\" style=\"text-align: left;".$bgcolorstyle."margin: ".formStyle('form_body-margin').";padding: ".formStyle('form_body-padding').";width: ".formStyle('form_body_width').";border-style: ".formStyle('form_border_style').";border-width: ".formStyle('form_border_width').";border-color: #".formStyle('form_border_color').";\">\n";

//echo testArray($_SESSION['form_fieldsort']);

if ( count($_SESSION['form_fields']) < 1 ) {
   # ERROR: No fields found for this form
   echo "<p>[Add some fields!]</p>";

} else {

   /*---------------------------------------------------------------------------------------------------------*
    ___   _            _               ___
   |   \ (_) ___ _ __ | | __ _  _  _  | __|___  _ _  _ __
   | |) || |(_-<| '_ \| |/ _` || || | | _|/ _ \| '_|| '  \
   |___/ |_|/__/| .__/|_|\__,_| \_, | |_| \___/|_|  |_|_|_|
                |_|             |__/
   # Build/output field html
   /*---------------------------------------------------------------------------------------------------------*/
//   foreach ( $_SESSION['form_fields'] as $key=>$field ) {

   # first field cannot go up, last field cannot go down
//   $keytop = end(array_keys($_SESSION['form_fieldsort']));
   $keybtm = end(array_keys($_SESSION['form_fieldsort']));

   foreach ( $_SESSION['form_fieldsort'] as $key=>$fieldid ) {
      $getField = $_SESSION['form_fields'][$fieldid];
      $idname = $getField['field_id'];
      $sortkey = array_search($getField['field_id'], $_SESSION['form_fieldsort']); // sort key

      # Add to required_fields list?
      if ( $getField['required'] == "yes" ) {
         
         // If required is upload, add fileupload[] instead of field label.
         // Uploads do not get field label as name.
         // BUZ #704
         if ( $getField['field_type'] == "upload" ) {
            $required_fields .= "fileupload[];";
         }else{
            $required_fields .= $getField['dbname'].";";
         }
      }

      if ( $idname != "" ) {
         # Was field just modified? If so, make it appear "selected"
         if ( $justmodifed_fieldid == $getField['field_id'] ) {
            $bgstyle = " style=\"background-color: #fff66f;border: 1px solid #fff;\"";
            $optdisplay = " style=\"display: block;\"";
         } else {
            $bgstyle = " style=\"border: 1px solid #fff;\"";
            $optdisplay = "";
         }

         # Special label position setting for this field or use form default?
         if ( $getField['style']['titlepos'] != "" ) {
            $titleclass = "myform-field_title-".$getField['style']['titlepos']; // Field-specific
         } else {
            $titleclass = "myform-field_title-".formStyle("label_position"); // Form-wide style setting
         }


         # Format label text
         $title_displaytext = stripslashes($getField['title']);
//         # Prevent title from breaking onto multiple lines
//         $title_displaytext = str_replace(" ", "&nbsp;", $title_displaytext);

         # Format label width value
         if ( formStyle("label_width") != "" ) {
            $label_widthval = formStyle("label_width")."px";
         } else {
            $label_widthval = "auto";
         }

         # maxlength?
         if ( $getField['style']['maxlength'] != "" ) { $maxlength = " maxlength=\"".$getField['style']['maxlength']."\""; } else { $maxlength = ""; }

         # [field_id]
         echo "<div class=\"field-container\">\n";

         if ( $getField['field_type'] == "heading" ) {
            # heading
            echo " <h".$getField['style']['heading_level']." style=\"color: #".formStyle("label_color").";\">".$title_displaytext."</h".$getField['style']['heading_level'].">\n";
            if ( $getField['notes'] != "" ) { echo " <p class=\"instructions\" style=\"color: #".formStyle("label_color").";\">".stripslashes($getField['notes'])."</p>\n"; }

         } elseif ( $getField['field_type'] == "text" ) {
            # text
            echo " <p class=\"".$titleclass."\" style=\"width: ".$label_widthval.";\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-input_container\"><input type=\"text\" name=\"".$getField['dbname']."\" style=\"width: ".$getField['width']."px;\"".$maxlength."/></p>\n";

         } elseif ( $getField['field_type'] == "email" ) {
            # email
            echo " <p class=\"".$titleclass."\" style=\"width: ".$label_widthval.";\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-input_container\"><input type=\"text\" name=\"emailaddr\" style=\"width: ".$getField['width']."px;\"".$maxlength."/></p>\n";

         } elseif ( $getField['field_type'] == "upload" ) {
            # upload
            echo " <p class=\"".$titleclass."\" style=\"width: ".$label_widthval.";\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-input_container\"><input type=\"file\" name=\"fileupload[]\" size=\"".$getField['width']."\"/></p>\n";

         } elseif ( $getField['field_type'] == "textarea" ) {
            # <textarea>
            if ( $getField['style']['height'] != "" ) { $height = "height: ".$getField['style']['height']."px;"; } else { $height = ""; }
            echo " <p class=\"".$titleclass."\" style=\"width: ".$label_widthval.";\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-formfield_container\"><textarea name=\"".$getField['dbname']."\" style=\"width: ".$getField['width']."px;".$height."\"".$maxlength."></textarea></p>\n";

         } elseif ( $getField['field_type'] == "select" ) {
            # <select>
            echo " <p class=\"".$titleclass."\" style=\"width: ".$label_widthval.";\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-formfield_container\">\n";
            echo "  <select name=\"".$getField['dbname']."\" style=\"width: ".$getField['width']."px;\">"; // No newline on <select>'s or "missing required" page screws up

            $max = count($getField['choices']);
            for ( $c = 0; $c < $max; $c++ ) {
               # format option value
               $optionvalue = supersterilize($getField['choices'][$c]['text']);
               # checked?
               if ( eregi($c, $getField['checked']) ) { $selected = " selected"; } else { $selected = ""; }
               echo "<option value=\"".supersterilize($getField['choices'][$c]['text'])."\"".$selected.">".stripslashes($getField['choices'][$c]['text'])."</option>";
            }

            echo "</select>\n";
            echo " </p>\n";

         } elseif ( $getField['field_type'] == "checkbox" ) {
            # checkbox
            echo " <p class=\"".$titleclass."\" style=\"width: ".$label_widthval.";\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-formfield_container\" style=\"color: #".formStyle("label_color").";\">\n";

            $max = count($getField['choices']);
            for ( $c = 0; $c < $max; $c++ ) {
               # format option value
               $optionvalue = supersterilize($getField['choices'][$c]['text']);
               # checked?
               if ( eregi($c, $getField['checked']) ) { $selected = " checked"; } else { $selected = ""; }
               # inline or list-style?
               if ( $getField['style']['optionlayout'] == "inline" ) {
                  echo "   <input name=\"".$getField['dbname']."[]\" type=\"checkbox\" value=\"".supersterilize($getField['choices'][$c]['text'])."\"".$selected.">".stripslashes($getField['choices'][$c]['text'])."\n";
               } else {
                  echo "   <input name=\"".$getField['dbname']."[]\" type=\"checkbox\" value=\"".supersterilize($getField['choices'][$c]['text'])."\"".$selected.">".stripslashes($getField['choices'][$c]['text'])."<br/>\n";
               }
            }
            echo " </p>\n";

         } elseif ( $getField['field_type'] == "radio" ) {
            # radio
            echo " <p class=\"".$titleclass."\" style=\"width: ".$label_widthval.";\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-formfield_container\" style=\"color: #".formStyle("label_color").";\">\n";

            $max = count($getField['choices']);
            for ( $c = 0; $c < $max; $c++ ) {
               # format option value
               $optionvalue = supersterilize($getField['choices'][$c]['text']);
               # checked?
               if ( eregi($c, $getField['checked']) ) { $selected = " checked"; } else { $selected = ""; }
               # inline or list-style?
               if ( $getField['style']['optionlayout'] == "inline" ) {
                  echo "   <input name=\"".$getField['dbname']."\" type=\"radio\" value=\"".supersterilize($getField['choices'][$c]['text'])."\"".$selected.">".stripslashes($getField['choices'][$c]['text'])."\n";
               } else {
                  echo "   <input name=\"".$getField['dbname']."\" type=\"radio\" value=\"".supersterilize($getField['choices'][$c]['text'])."\"".$selected.">".stripslashes($getField['choices'][$c]['text'])."<br/>\n";
               }
            }
            echo " </p>\n";
         }

         # ie_cleardiv
         echo " <div class=\"ie_cleardiv\">\n";
   //      echo "  &nbsp;\n";
         echo " </div>\n";

         echo "</div>\n"; // end field_container-[field_id]

      } // End if idname != ''

   } // End while looping through field records
}

# required_fields
echo "<input type=\"hidden\" name=\"required_fields\" value=\"".$required_fields."\"/>\n";

# submit
echo " <div id=\"userform-submit_btn-container\" style=\"text-align: ".formStyle("submit_btn_align").";\">\n";
echo "  <input id=\"userform-submit_btn\" type=\"submit\" value=\"".stripslashes(formStyle("submit_btn_text"))."\" style=\"font-size: ".formStyle("submit_btn-font-size")."px;font-weight: ".formStyle("submit_btn_weight").";\">\n";
echo " </div>\n";
?>

</form>

</div>


<?
$disHTML = ob_get_contents();
ob_end_clean();
echo $disHTML;
?>