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
# Forms Manager Module v2.0 - Form edit/compile ajax script
# NOTES:
#   Called from edit_form.php via ajaxDo()
#   $_REQUEST['form_id'] must be available at all times
#===============================================================================================

error_reporting(E_PARSE);
session_start();
require_once("../../includes/product_gui.php");

# Determines which types of fields can have which types of properties
include_once("nocando.inc.php");

$justmodifed_fieldid = ""; // Used to show field w/yellow bg if it just had an action performed on it

//echo testArray($_SESSION['form_fieldsort']);


/*---------------------------------------------------------------------------------------------------------*
   __  ___                  ____ _       __    __
  /  |/  /___  _  __ ___   / __/(_)___  / /___/ /
 / /|_/ // _ \| |/ // -_) / _/ / // -_)/ // _  /
/_/  /_/ \___/|___/ \__/ /_/  /_/ \__//_/ \_,_/

# Move field up/down
/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['move'] != "" ) {
   # Get keynum of fieldid
   $keynow = array_search($_GET['field_id'], $_SESSION['form_fieldsort']);
   $keyup = ($keynow - 1);
   $keydown = ($keynow + 1);
   $keylast = end(array_keys($_SESSION['form_fieldsort']));

   # direction?
   if ( $_GET['move'] == "up" ) {
      $swapkey = $keyup;
   } else {
      $swapkey = $keydown;
   }

//   Button now not even displayed if can't move up/down
//   # Top field can't go up, bottom field can't go down
//   if ( !(($keynow == 0 && $_GET['move'] == "up") || ($keynow != $keylast && $_GET['move'] == "down")) ) {
      # Id of neighbor field
      $swapid = $_SESSION['form_fieldsort'][$swapkey];

      # Replace neighbor field id with current field id
      $_SESSION['form_fieldsort'][$swapkey] = $_GET['field_id'];

      # Replace current field id at old position with neighbor field's id
      $_SESSION['form_fieldsort'][$keynow] = $swapid;

      # Re-select field after move
      $justmodifed_fieldid = $_GET['field_id'];
//   }
}


/*---------------------------------------------------------------------------------------------------------*
   ____                   _____ __         _
  / __/___ _ _  __ ___   / ___// /  ___   (_)____ ___
 _\ \ / _ `/| |/ // -_) / /__ / _ \/ _ \ / // __// -_)
/___/ \_,_/ |___/ \__/  \___//_//_/\___//_/ \__/ \__/

/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['choicekey'] != "" ) {
   # Rebuild choice data
   $choicedata = array('text' => $_GET['choicevalue'], 'default' => "");

   $_SESSION['form_fields'][$_GET['field_id']]['choices'][$_GET['choicekey']] = $choicedata;

   $justmodifed_fieldid = $_GET['field_id'];
//   echo "[".$justmodifed_fieldid."]<br/>";
}


/*---------------------------------------------------------------------------------------------------------*
   ___      __    __  ____ _       __    __
  / _ | ___/ /___/ / / __/(_)___  / /___/ /
 / __ |/ _  // _  / / _/ / // -_)/ // _  /
/_/ |_|\_,_/ \_,_/ /_/  /_/ \__//_/ \_,_/

# PROCESS: Add field
/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['todo'] == "add_field" ) {
   # Build form_properties insert
   $data = array();
   $data['dbname'] = "my_field"; // Db-friendly name for looking at field data via DTM, default to "my_field"
   $data['title'] = "My field"; // Actual text title displayed to website visitor, default to 'My field'
   $data['form_id'] = $_REQUEST['form_id']; // Matches form_id of assocciated form_properties record
   $data['field_id'] = time(); // For use in editing (by creating "-temp" clone)
   $data['field_type'] = $_REQUEST['field_type']; // Passed from ajaxDo qry in edit_form.php when add button is clicked

   # Choices?
   if ( in_array($data['field_type'], $cando_choice) ) {
      if ( $data['field_type'] == "checkbox" || $data['field_type'] == "radio" ) { $chkword = " checked"; } else { $chkword = " selected"; }
      # 3 by default
      $choices = array();
      $choices[] = array('text' => "First option", 'default' => $chkword);
      $choices[] = array('text' => "Second option", 'default' => "");
      $choices[] = array('text' => "Third option", 'default' => "");

      $data['choices'] = $choices;
   }

   # heading? - default to <h1>
   if ( $data['field_type'] == "heading" ) {
      $data['style']['heading_level'] = "1";
   }

   # <p>
   if ( $data['field_type'] == "paragraph" ) {
      $data['notes'] = "This is my form. Please fill it out.";
   }

   # Default 150px width?
   if ( !in_array($data['field_type'], $nocando_width) ) { $data['width'] = "220"; } else { $data['width'] = ""; }

   # Add to field sort & field data session arrays
   $_SESSION['form_fieldsort'][] = $data['field_id'];
   $_SESSION['form_fields'][$data['field_id']] = $data;

   # DEFAULT: sort_order = last key in sort array + 1
   $_SESSION['form_fields'][$data['field_id']]['sort_order'] = (end(array_keys($_SESSION['form_fieldsort'])) + 1);

   # Mark this field as "just modified" so it can be reselected, etc
   $justmodifed_fieldid = $data['field_id'];
}

/*---------------------------------------------------------------------------------------------------------*
   ____                   ____ _       __    __
  / __/___ _ _  __ ___   / __/(_)___  / /___/ /
 _\ \ / _ `/| |/ // -_) / _/ / // -_)/ // _  /
/___/ \_,_/ |___/ \__/ /_/  /_/ \__//_/ \_,_/

# REQUIRES: field_id, save_field, newvalue
/*---------------------------------------------------------------------------------------------------------*/
# Save main field data
if ( $_GET['save_field'] != "" ) {

   # Saving title? Then save dbname too
   if ( $_GET['save_field'] == "title" )  {
      $_SESSION['form_fields'][$_GET['field_id']]['dbname'] = supersterilize($_GET['newvalue']);
   }
   $_SESSION['form_fields'][$_GET['field_id']][$_GET['save_field']] = $_GET['newvalue'];
   $justmodifed_fieldid = $_GET['field_id'];
}

# save_style
if ( $_GET['save_style'] != "" ) {
   $_SESSION['form_fields'][$_GET['field_id']]['style'][$_GET['save_style']] = $_GET['newvalue'];
   $justmodifed_fieldid = $_GET['field_id'];
}


/*---------------------------------------------------------------------------------------------------------*
   ___        __      __         ____ _       __    __
  / _ \ ___  / /___  / /_ ___   / __/(_)___  / /___/ /
 / // // -_)/ // -_)/ __// -_) / _/ / // -_)/ // _  /
/____/ \__//_/ \__/ \__/ \__/ /_/  /_/ \__//_/ \_,_/

# Delete/remove a field
/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['delete_field'] != "" ) {
   # Remove from sort array
   $sortkey = array_search($_GET['delete_field'], $_SESSION['form_fieldsort']);
//   echo "killfield: [".$_GET['delete_field']."] (".$sortkey.")<br/>";
//   $_SESSION['form_fieldsort'][$sortkey] = NULL;
   unset($_SESSION['form_fieldsort'][$sortkey]);
   unset($_SESSION['form_fields'][$_GET['delete_field']]);
}


# Centralize core parts of ajaxDo queries
# Called in onclick's and such
function ajax_qry($qrystring) {
   return "ajaxDor('field_properties.ajax.php?form_id=".$GLOBALS['getForm']['form_id']."&".$qrystring."', 'field_properties');";
}

# Returns nothing or an asterisk depending on whether passed field id is a required field
function asterisk($field_id) {
   if ( $_SESSION['form_fields'][$field_id]['required'] == "yes" ) {
      return "<span class=\"asterisk\">*</span>\n";
   }
}

//echo testArray($_SESSION['form_fieldsort']);

# output buffer - faster than echo'ing every line
ob_start();
?>

<!---Begin form display-->
<form name="saveformform" method="post" action="edit_form.php" id="myform">
<?

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

      if ( $idname != "" ) {
         # Was field just modified? If so, make it appear "selected"
         if ( $justmodifed_fieldid == $getField['field_id'] ) {
            $bgstyle = " style=\"background-color: #fff66f;border: 1px solid #fff;\"";
            $optdisplay = " style=\"display: block;\"";
         } else {
            $bgstyle = " style=\"border: 1px solid #fff;\"";
            $optdisplay = "";
         }

         # Title above field?
         if ( $getField['style']['titlepos'] == "left" ) {
            $titleclass = "myform-field_title-left";
         } else {
            $titleclass = "myform-field_title";
         }

         # Prevent title from breaking onto multiple lines
         $title_displaytext = str_replace(" ", "&nbsp;", $getField['title']);

         # [field_id]
         echo "<div id=\"".$idname."\" class=\"field_container\" onclick=\"selectField('".$getField['field_id']."');\"".$bgstyle.">\n";

         if ( $getField['field_type'] == "heading" ) {
            # heading
            echo " <h".$getField['style']['heading_level'].">".$title_displaytext."</h".$getField['style']['heading_level'].">\n";
            if ( $getField['notes'] != "" ) { echo " <p class=\"instructions\">".stripslashes($getField['notes'])."</p>\n"; }

         } elseif ( $getField['field_type'] == "text" ) {
            # text
            echo " <p class=\"".$titleclass."\">".$title_displaytext."".asterisk($getField['field_id'])."\n";
//            if ( $getField['notes'] != "" ) { echo " <span class=\"instructions\">".stripslashes($getField['notes'])."</span></p>\n"; } else { echo "</p>\n"; }
            echo " <p class=\"myform-input_container\"><input type=\"text\" style=\"width: ".$getField['width']."px;\"/></p>\n";

         } elseif ( $getField['field_type'] == "textarea" ) {
            # <textarea>
            if ( $getField['style']['height'] != "" ) { $height = "height: ".$getField['style']['height']."px;"; } else { $height = ""; }
            echo " <p class=\"".$titleclass."\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-formfield_container\"><textarea style=\"width: ".$getField['width']."px;".$height."\"></textarea></p>\n";

         } elseif ( $getField['field_type'] == "select" ) {
            # <select>
            echo " <p class=\"".$titleclass."\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-formfield_container\">\n";
            echo "  <select style=\"width: ".$getField['width']."px;\">\n";

            $max = count($getField['choices']);
            for ( $c = 0; $c < $max; $c++ ) {
               # format option value
               $optionvalue = supersterilize($getField['choices'][$c]['text']);
               # checked?
               if ( eregi($c, $getField['checked']) ) { $selected = " selected"; } else { $selected = ""; }
               echo "   <option value=\"".$c."\"".$selected.">".$getField['choices'][$c]['text']."</option>\n";
            }

            echo "  </select>\n";
            echo " </p>\n";

         } elseif ( $getField['field_type'] == "checkbox" ) {
            # checkbox
            echo " <p class=\"".$titleclass."\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-formfield_container\">\n";

            $max = count($getField['choices']);
            for ( $c = 0; $c < $max; $c++ ) {
               # format option value
               $optionvalue = supersterilize($getField['choices'][$c]['text']);
               # checked?
               if ( eregi($c, $getField['checked']) ) { $selected = " checked"; } else { $selected = ""; }
               # inline or list-style?
               if ( $getField['style']['optionlayout'] == "inline" ) {
                  echo "   <input type=\"checkbox\" value=\"".$c."\"".$selected.">".$getField['choices'][$c]['text']."\n";
               } else {
                  echo "   <input type=\"checkbox\" value=\"".$c."\"".$selected.">".$getField['choices'][$c]['text']."<br/>\n";
               }
            }
            echo " </p>\n";

         } elseif ( $getField['field_type'] == "radio" ) {
            # radio
            echo " <p class=\"".$titleclass."\">".$title_displaytext."".asterisk($getField['field_id'])."</p>\n";
            echo " <p class=\"myform-formfield_container\">\n";

            $max = count($getField['choices']);
            for ( $c = 0; $c < $max; $c++ ) {
               # format option value
               $optionvalue = supersterilize($getField['choices'][$c]['text']);
               # checked?
               if ( eregi($c, $getField['checked']) ) { $selected = " checked"; } else { $selected = ""; }
               # inline or list-style?
               if ( $getField['style']['optionlayout'] == "inline" ) {
                  echo "   <input type=\"radio\" value=\"".$c."\"".$selected.">".$getField['choices'][$c]['text']."\n";
               } else {
                  echo "   <input type=\"radio\" value=\"".$c."\"".$selected.">".$getField['choices'][$c]['text']."<br/>\n";
               }
            }
            echo " </p>\n";
         }


         # field_options_container
         echo " <div class=\"field_options_container\" id=\"field_options_container-".$getField['field_id']."\"".$optdisplay.">\n";

         # [<>] Cancel
         echo "  <span class=\"option-cancel\" onclick=\"refresh_preview();\">[&lt;&gt;]</span>\n";

         # [v]
         if ( $sortkey != $keybtm ) {
            echo "  <span class=\"option-movedown\" onclick=\"moveField('".$getField['field_id']."', 'down');\">[v]</span>\n";
         } else {
            echo "  <span class=\"option-movedown-disabled\">[v]</span>\n";
         }

         # [^]
         if ( $sortkey != 0 ) {
            echo "  <span class=\"option-moveup\" onclick=\"moveField('".$getField['field_id']."', 'up');\">[^]</span>\n";
         } else {
            echo "  <span class=\"option-moveup-disabled\">[^]</span>\n";
         }

         # [x]
         echo "  <span class=\"option-delete\" onclick=\"deleteField('".$getField['field_id']."');\">[x]</span>\n";

         echo "  <div style=\"clear: both;\"></div>\n";
         echo " </div>\n";

         # ie_cleardiv
         echo " <div class=\"ie_cleardiv\">\n";
   //      echo "  &nbsp;\n";
         echo " </div>\n";

         echo "</div>\n"; // end field_container-[field_id]

      } // End if idname != ''

   } // End while looping through field records
}
?>

 <input type="hidden" id="justmodified_fieldid" value="<? echo $justmodifed_fieldid; ?>">


 <div style="clear: both;">
  <span class="
 </div>
</form>

<?
$disHTML = ob_get_contents();
ob_end_clean();
echo $disHTML;
?>