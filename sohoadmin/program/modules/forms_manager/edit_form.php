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
# Forms Manager Module v2.0 - Edit Form
# REQUIRES: $_REQUEST['form_id'] must be available at all times
#===============================================================================================
error_reporting(E_PARSE);
session_start();
include_once($_SESSION['product_gui']);

# Make sure form-related db tables exist
include_once("form_dbcheck.inc.php");

# Shared forms-related php functions
include_once("_forms_manager_functions.inc.php");


/*---------------------------------------------------------------------------------------------------------*
   ____                   ___
  / __/___ _ _  __ ___   / _ |  ___
 _\ \ / _ `/| |/ // -_) / __ | (_-<
/___/ \_,_/ |___/ \__/ /_/ |_|/___/

# Save current changes as completely new form
# Note: $_POST['todo'] == save_form with save as action too...
#       ...uses $_POST['saveas_name'] check to catch process and create form before normal save routine
/*---------------------------------------------------------------------------------------------------------*/
if ( $_POST['saveas_name'] != "" ) {
   # form_properties - Build form_properties insert
   $data = array();
   $data['form_name'] = $_POST['saveas_name']; // For webmaster reference in forms manager, etc
   $data['form_id'] = md5($_POST['saveas_name']); // Necessary for temporary editing as "[id]-temp"
   $data['date_created'] = time();
   $data['form_filename'] = supersterilize($_POST['saveas_name']); // For filename, etc

   $myqry = new mysql_insert("form_properties", $data);
   $myqry->insert();

   # Set this as the current working form and trigger normal save routine
   $_REQUEST['form_id'] = $data['form_id'];
   $_SESSION['form_id'] = $data['form_id'];

//   header("location: edit_form.php?todo=reset_form&form_id=".$_REQUEST['form_id']); exit;
}


# Pull form properties
$qry = "select * from form_properties where form_id = '".$_REQUEST['form_id']."'";
if ( !$rez = mysql_query($qry) ) {
   $report[] = "Error. No form selected. Please go back and choose a form to edit or create a new form.";
} else {
   $getForm = mysql_fetch_array($rez);
}


/*---------------------------------------------------------------------------------------------------------*
   ____                   ____
  / __/___ _ _  __ ___   / __/___   ____ __ _
 _\ \ / _ `/| |/ // -_) / _/ / _ \ / __//  ' \
/___/ \_,_/ |___/ \__/ /_/   \___//_/  /_/_/_/

# Convert session array data to db updates/inserts
/*---------------------------------------------------------------------------------------------------------*/
if ( $_REQUEST['todo'] == "save_form" ) {
   # form_properties
   $data = array();
   $qry = "update form_properties set";
   $qry .= " style = '".addslashes(serialize($_SESSION['form_properties']['style']))."'";
   $qry .= " where form_id = '".$_REQUEST['form_id']."'";
   $rez = mysql_query($qry);

   # form_fields
   # Delete existing field records
   $qry = "delete from form_fields where ";
   $qry .= " form_id = '".$_REQUEST['form_id']."'";
   mysql_query($qry);

   # Now insert all-new field records using session array data
   $max1 = count($_SESSION['form_fieldsort']);
   $max = count($_SESSION['form_fields']);

   # Make sure sort array starts at zero
   $_SESSION['form_fieldsort'] = array_values($_SESSION['form_fieldsort']);

//   echo "<div style=\"height: 470px;overflow: auto;\">\n";
//   echo testArray($_SESSION['form_fieldsort']);
//   echo testArray($_SESSION['form_fields']);
//   echo "max1 = [".$max1."] | max = [".$max."]<br/>";
//   echo "</div>\n";
//   exit;

   for ( $f = 0; $f < $max; $f++ ) {
      $fieldid = $_SESSION['form_fieldsort'][$f];
      $getField = $_SESSION['form_fields'][$fieldid];

      $data = array();
      if ( $globalprefObj->get('utf8') == 'on' ) {
      	$data['dbname'] = $getField['dbname'];
      } else {
      	$data['dbname'] = supersterilize($getField['dbname']);
      }
      $data['dbname'] = str_replace('?', '', $getField['dbname']);
      $data['title'] = addslashes($getField['title']);
      $data['field_type'] = $getField['field_type'];
      $data['field_id'] = $getField['field_id'];
      $data['form_id'] = $_REQUEST['form_id'];
      $data['sort_order'] = $f;
      $data['required'] = $getField['required'];
      $data['width'] = $getField['width'];
      $data['style'] = serialize($getField['style']);
      $data['choices'] = addslashes(serialize($getField['choices']));
      $data['checked'] = $getField['checked'];
      $data['notes'] = addslashes($getField['notes']);

      $myqry = new mysql_insert("form_fields", $data);
      $myqry->insert();
   }

   # Write form file
   ob_start();
   include("create_formfile.inc.php");
   $formHTML = ob_get_contents();
   ob_end_clean();

   $formfile = $_SESSION['docroot_path']."/media/".$_SESSION['form_properties']['form_filename'].".form.html";
   if ( !$fp = fopen($formfile, "w") ) {
      $report[] = "Error: Could not create form .html file. Check permissions on the '".dirname($_SESSION['docroot_path'])."/media' folder to make sure php has write access.\n";
   } else {
      fwrite($fp, $formHTML);
   }
   # DO NOT PUT NEWLINES (\n) in this message or it will cause the "preview pane goes blank on save but not on revert to saved" thing
   $savedmsg = "<h3>Form and .html file created successfully!</h3>";
   $savedmsg .= " To place this form on a page, open that page in the Page Editor, drag-and-drop the <strong>Forms</strong> object onto the page,";
   $savedmsg .= " select <strong>".basename($formfile)."</strong> from the drop-down list of forms, and configure as desired.";
//   $status = "Form saved!";
   fclose($fp);

//   echo "<iframe src=\"create_formfile.inc.php?form_id=".$_REQUEST['form_id']."\" style=\"width: 650px;height: 400px;\"></iframe>\n"; exit;

//   echo "done"; exit;
}


/*---------------------------------------------------------------------------------------------------------*
   ____    __ _  __    ____
  / __/___/ /(_)/ /_  / __/___   ____ __ _
 / _/ / _  // // __/ / _/ / _ \ / __//  ' \
/___/ \_,_//_/ \__/ /_/   \___//_/  /_/_/_/

/*---------------------------------------------------------------------------------------------------------*/
if ( $_REQUEST['form_id'] != "" ) {

   # Do not reset session array if editing same form (prevent back button mishaps...will need a 'reset all' button now though)
   if ( $_SESSION['form_id'] != $_REQUEST['form_id'] || $_REQUEST['todo'] == "save_form" || $_REQUEST['todo'] == "reset_form" ) {
//      echo "[".$_SESSION['form_id']."] = [".$_REQUEST['form_id']."]"; exit;
      $_SESSION['form_properties'] = array();
      $_SESSION['form_fields'] = array();
      $_SESSION['form_fieldsort'] = array();
      $_SESSION['form_id'] = $_REQUEST['form_id'];

      # Pull FORM data into session array
      $qry = "select * from form_properties where form_id = '".$_REQUEST['form_id']."'";
      $rez = mysql_query($qry);
      $_SESSION['form_properties'] = mysql_fetch_assoc($rez);
      $_SESSION['form_properties']['style'] = unserialize($_SESSION['form_properties']['style']);

      # DEFAULT: Make sure default form styles are set
      set_formstyle_default("label_position", "left");
      set_formstyle_default("label_color", "595959");
      set_formstyle_default("label_weight", "normal");
//      set_formstyle_default("label_width", "125");
      set_formstyle_default("label_textalign", "left");
      set_formstyle_default("label_font", "Trebuchet MS, arial, helvetica, sans-serif");
      set_formstyle_default("label_fontSize", "11");
      set_formstyle_default("submit_btn_text", lang("Submit")." &gt;&gt;");
      set_formstyle_default("submit_btn-font-size", "13");
      set_formstyle_default("submit_btn-color", "000");
      set_formstyle_default("submit_btn-background-color", "none");
      set_formstyle_default("submit_btn_weight", "normal");
      set_formstyle_default("submit_btn_align", "center");
//      set_formstyle_default("form_body_width", "500");
      set_formstyle_default("form_body-padding", "0px");
      set_formstyle_default("form_body-margin", "10");
      set_formstyle_default("form_border_width", "0");
      set_formstyle_default("form_border_style", "none");
      set_formstyle_default("form_border_color", "000");


      # Pull FIELD data into session array
      $qry = "select * from form_fields where form_id = '".$_REQUEST['form_id']."' order by sort_order asc";
      $rez = mysql_query($qry);
      while ( $getField = mysql_fetch_assoc($rez) ) {
         if ( strlen(trim($getField['field_id'])) > 5 ) { // Defeat Gremlins (i.e. empty/corrup field records in db)
            # Field DATA
            $_SESSION['form_fields'][$getField['field_id']] = $getField;
            $_SESSION['form_fields'][$getField['field_id']]['style'] = unserialize($getField['style']);
            $_SESSION['form_fields'][$getField['field_id']]['choices'] = unserialize($getField['choices']);

            # Field SORT
            $_SESSION['form_fieldsort'][$getField['sort_order']] = $getField['field_id'];
         }
      }

   } // End if editing new, saving, or reseting

} // End retrieve form data for edit


# Start buffering output
ob_start();

# ajaxDo() javascript function
include("ajaxdo.js.php");

# Centralize core parts of ajaxDo queries
# Called in onclicks and such
function ajax_qry($qrystring) {
   return "ajaxDor('myform.ajax.php?form_id=".$GLOBALS['getForm']['form_id']."&".$qrystring."', 'container-my_form');";
}
?>
<link rel="stylesheet" type="text/css" href="edit_form.css">
<!--- <link rel="stylesheet" type="text/css" href="css-properties.css"> -->
<link rel="stylesheet" type="text/css" href="properties.css"/>
<!--- <link rel="stylesheet" type="text/css" href="css-edit_compile_form.css"> -->
<link rel="stylesheet" type="text/css" href="myform-edit.css"/>
<!--- <link rel="stylesheet" type="text/css" href="myform.css.php"> -->

<?
# Generates css rules for user form
include("myform.css.php");
?>

<script type="text/javascript">
// Expirimental -- these functions allow the field title field to be updated Xsecs after the last onkeyup
function save_titlefield(fieldid, title, thevalue) {
   saveField(fieldid, title, thevalue);

   if ( typeof(showtime) != "undefined" && showtime != null ) {
      clearInterval(showtime);
   }
}
// Make sure the first arg is the function name
function start_titlefield(fieldid, title, thevalue) {

   // If save countdown is started restart it
   if ( typeof(showtime) != "undefined" && showtime != null ) {
      clearInterval(showtime);
   }

   var numargs = start_titlefield.arguments.length;
   var args = ''; // Will contain argument string passed to myfunc()
   for (i = 0;i < numargs;i++) {
      args += ', '+start_titlefield.arguments[i];
   }

//   showtime = setInterval("save_titlefield('blah')", 2000);
   showtime = setInterval("save_titlefield('"+fieldid+"', '"+title+"', '"+thevalue+"')", 3000);
//   alert('started');
}

//-onkeyup start 2sec to save
//-on save clear 2sec interval
// GOAL: Field title saves ONCE 2 secs after onkeyup



// TEST: Try to get stylesheets by name because the freakin' key index num keeps changing
function getUserSheet() {
   for ( i = 0; i < document.styleSheets.length; i++ ) {
      if ( document.styleSheets[i].title ) {
         return i;
      }
   }
}

// Returns object reference of CSS rule matching passed selector string
function getRule(selectortxt) {

   // Set cross-browser CSS rules object
   // cssnum = styleSheet index number of inline <style> generated by myform.css.php (or sometimes i swear it's 3...grr)
   var cssnum = getUserSheet();
   var myrules = '';
//   if ( document.styleSheets[cssnum].cssRules ) {
//      myrules = document.styleSheets[cssnum].cssRules;
//   } else if (document.styleSheets[cssnum].rules) {
//      myrules = document.styleSheets[cssnum].rules;
//   }
   if ( document.styleSheets[cssnum].rules ) {
      myrules = document.styleSheets[cssnum].rules;
   } else {
      myrules = document.styleSheets[cssnum].cssRules
   }

   for ( i = 0; i < myrules.length; i++ ){
      chkselector = myrules[i].selectorText.toLowerCase();
//      alert('tofind: ('+selectortxt+') in ['+chkselector+']');

      if ( chkselector.search(selectortxt) > 0 ) {
//         alert('('+selectortxt+') found in ['+chkselector+']');
         targetrule = myrules[i];
         return targetrule;
         break;
      }
   }
}

// Currently unused. Written for since-deleted-but-soon-returning mouseover/click action for selecting fields to edit in myform preview area
function setbgcolor(field_id, color) {
   document.getElementById(field_id).style.backgroundColor = color;
}

// JS port of php supersterilize() function
// For making a field title/name db-colname-friendly
function supersterilize(dirtystr, dblimit) {
   cleanstr = dirtystr;

   var cleaner = "";
   max = cleanstr.length;
   for ( i = 0; i < max; i++ ) {
      c = cleanstr.charAt(i);

      // Spaces to underscores
      if ( c == " " ) { c = "_"; }

      // Alpha-only
      c = c.replace(/[^a-zA-Z0-9_]/, "");

      cleaner = cleaner + c;
   }

   cleanstr = cleaner;

   // 64 char limit
   cleanstr = cleanstr.substr(0, 64);

   // Return sterilized string
//   alert('Clean: ['+cleanstr+']');
   return cleanstr;
}


// Switch to requested tab in properties pane
function props_tab(formorfield) {

   if ( formorfield == "form" ) { tohide = "field"; } else { tohide = "form"; }

   var boxid_on = formorfield+'_properties';
   var tabid_on = 'tab-'+formorfield;
   var boxid_off = tohide+'_properties';
   var tabid_off = 'tab-'+tohide;

   // Turn target tab 'on' and other tab 'off'
   setClass(tabid_on, 'tab-on');
   setClass(tabid_off, 'tab-off');

   // Show target content layer, hide other one
   hideid(boxid_off);
   showid(boxid_on);

} // End props_tab() function


// Flash background color
function set_bgcolor(boxid, newcolor) {
   document.getElementById(boxid).style.backgroundColor = '#'+newcolor;
}
function flash_bg(boxid) {
   set_bgcolor(boxid, 'fff66f');

//   window.setTimeout("set_bgcolor("+boxid+", 'fffab2');", 500);
//   window.setTimeout("set_bgcolor("+boxid+", 'fff66f');", 1000);
}

// Allows incrementing/decrimenting numeric value in text field with arrow keys
function formStyle_sizeField(theEvent, boxid) {

   // Shift + up/down changes value by 25
   if ( theEvent.shiftKey ) {
      incrementby = 25;
   } else {
      incrementby = 1;
   }

   // Only if not empty
   if ( document.getElementById(boxid).value != "" ) {

      switch(theEvent.keyCode) {
         // Arrow up
         case 38:
         document.getElementById(boxid).value = parseInt(document.getElementById(boxid).value) + parseInt(incrementby);
         break;

         // Arrow down
         case 40:
         document.getElementById(boxid).value = parseInt(document.getElementById(boxid).value) - parseInt(incrementby);
         break;
      }
   }

   // Modify raw value in form preview pane
   formStyle(boxid, document.getElementById(boxid).value);
}



// Reload container-my_form preview
function refresh_preview() {
   var mydate = new Date()
   var randomman = mydate.getMilliseconds();
   ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&blah='+randomman, 'container-my_form');
}

function clear_properties() {
   document.getElementById('field_properties').innerHTML = '<b>No field selected</b> - Click on a field in the preview to the right to edit it, or add a new field by clicking the (+)Add Field button in the footer below.';
}

// For multi select - returns semicolon-delimited list of selected options
function get_multicheck(formname, selectname) {
    len = eval("document."+formname+"."+selectname+".length");

    stuff = "";

    for ( i=0; i < len; i++ ) {
      thisone = eval("document."+formname+"."+selectname+"["+i+"]");
      if ( thisone.selected ) {
         stuff = stuff + thisone.value + ";";
      }
   }

   return stuff;
}


// MYFORM: When user clicks on field in My Form view
function selectField(field_id) {
   var mydate = new Date()
   var randomman = mydate.getMilliseconds();

   // Narrow to div's within 'myform'
   var outercontainer = document.getElementById('myform');
   var fields = outercontainer.getElementsByTagName('div');

   for ( var i=0; i < fields.length; i++ ) {
      // Clear bg color for all field_container's
      if ( fields[i].id != "" && fields[i].className == 'field_container' ) {
         document.getElementById(fields[i].id).style.backgroundColor = '';
         hideid('field_options_container-'+fields[i].id);
      }
   }

   // Highlight choosen field with dark yellow bg and kill mouseover
   document.getElementById(field_id).style.backgroundColor='#FFF66F';

   // Show Field Properties tab
   props_tab('field');

   // Show move/delete options for this field
   showid('field_options_container-'+field_id);

   // Show "De-select field" option
   showid('cancel_select');

   // Call up properties for this field
   ajaxDor('field_properties.ajax.php?form_id=<? echo $getForm['form_id']; ?>&field_id='+field_id+'&blah='+randomman, 'field_properties');
}


// PROPS: When user commits edit of title text for particular field
function saveField(field_id, whattochange, newvalue) {
//   alert(newvalue);
   var mydate = new Date()
   var randomman = mydate.getMilliseconds();

   // Get scrollto position of affected element
   offset = document.getElementById(field_id).offsetTop;

   // don't rescroll at all if not below fold
   if ( offset < 250 ) { offset = 0; }

   ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&field_id='+field_id+'&save_field='+whattochange+'&newvalue='+newvalue+'&blah='+randomman, 'container-my_form', offset);
}

// Add a new field -- php/ajax method
function addField(fieldtype) {
   var mydate = new Date()
   var randomman = mydate.getMilliseconds();

   // Hide addfield popup
   hideid('addfield_buttons-container');

   // Clear properties window
   clear_properties();

   // Scroll to bottom of form preview (figure 1000 should do the trick)
   offset = 1000;

   // Add to form preview
   ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&todo=add_field&field_type='+fieldtype, 'container-my_form', offset);

   // Get just added field id and select it
   newfieldid = document.getElementById('justmodified_fieldid').value;

   // Call up properties for this field
   // Won't work even with pendingqry thing because first qry has to finish to get newfieldid value...possible fix: gerneate new field id here in js rather than in php routine
//   ajaxDor('field_properties.ajax.php?form_id=<? echo $getForm['form_id']; ?>&field_id='+newfieldid+'&blah='+randomman, 'field_properties');
}

// [v] [^] - Move field up or down
function moveField(field_id, direction, offset) {
   if ( direction == "up" ) {
      offset = (offset / 2); // Smooths out un-diagnosed problem with up-movement rescroll action
   }
   // don't rescroll at all if not below fold
   if ( offset < 250 ) { offset = 0; }

   ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&field_id='+field_id+'&move='+direction, 'container-my_form', offset);
}

// Save style/layout info for FIELD (i.e. labelpos=top/layout=list/layout=inline
function saveStyle(field_id, whattochange, newvalue) {
   var mydate = new Date()
   var randomman = mydate.getMilliseconds();

   ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&field_id='+field_id+'&save_style='+whattochange+'&newvalue='+newvalue+'&blah='+randomman, 'container-my_form');
}

// formStyle()
// Saves (applies to preview) particular form display setting, Called from form_properties dialog (form_properties.inc.php)
function formStyle(whattochange, newvalue) {
   var mydate = new Date()
   var randomman = mydate.getMilliseconds();

   // Add stylesheet refresh as pending qry
//   document.getElementById('pendingqry').value = '
//   document.getElementById('link-myform-edit_css').href = 'myform.css.php'; // Will this refresh as intended?
   ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&todo=form_style&whattochange='+whattochange+'&newvalue='+newvalue+'&blah='+randomman, 'container-my_form');
}

// For dropdown boxes and such
function removeChoice(field_id, choicekey) {
   // Adjust default-checked list accordingly
   chklist = document.getElementById('checked').value;
   chked = chklist.split(';');
   max = chked.length;
   newdflist = '';
   for ( x = 0; x < max; x++ ) {
      if ( chked[x] != '' ) {
         // If greater than choicekey, decrement
         if ( parseInt(chked[x]) > parseInt(choicekey) ) {
            newcval = parseInt(chked[x]) - parseInt(1);
         } else {
            newcval = chked[x];
         }
         newdflist = newdflist + newcval+';';
      }
   }
   ajaxDor('field_properties.ajax.php?form_id=<? echo $getForm['form_id']; ?>&field_id='+field_id+'&remove_choice='+choicekey+'&newdflist='+newdflist, 'field_properties');
}

// Updated note: As far as I can tell upon looking at this again,
// the primary function of this function is to re-arrange the fields that are checked by default
// field_properties.ajax.php seems to handle the actual re-structuring of the choices
function addChoice(field_id, afterchoice) {
   // Adjust default-checked list accordingly
   chklist = document.getElementById('checked').value; // hidden field that contains key id for fields that should be pre-checked
   chked = chklist.split(';');
   max = chked.length;
   newdflist = '';
   for ( x = 0; x < max; x++ ) {
      if ( chked[x] != '' ) {
         // If greater than afterchoice, increment
         if ( parseInt(chked[x]) > parseInt(afterchoice) ) {
            newcval = parseInt(chked[x]) + parseInt(1);
         } else {
            newcval = chked[x];
         }
         newdflist = newdflist + newcval+';';
      }
   }

   // Re-index session choices array for this field
   ajaxDor('field_properties.ajax.php?form_id=<? echo $getForm['form_id']; ?>&field_id='+field_id+'&todo=add_choice&afterchoice='+afterchoice+'&newdflist='+newdflist, 'field_properties');

   // Show changes in form preview
   refresh_preview();
}

// Save text of option choice for multiple-choice form elements
function saveChoice(field_id, choiceid, value) {
   // Strip string id name and just get key number
   var choicekey = choiceid.replace('choice-', '');

   // Ready
   ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&field_id='+field_id+'&choicekey='+choicekey+'&choicevalue='+value, 'container-my_form');
}


// Delete a field
function deleteField(field_id) {
   clear_properties();
   ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&delete_field='+field_id, 'container-my_form');
}


// Save preference as to whether a particular choice should be checked by default
// Either replaces 'checked' value for field with this choice (if radio or regular dropdown)
// ...or adds to 'checked' list (for multi-default allowed elements like checkboxes and multi-select)
function mkChecked(field_id, singleormulti, choicenum) {

   if ( singleormulti == "single" ) {
      // SINGLE - Replace default list entirely with new value
      newdflist = choicenum+';';

      // Loop through all choices' [-/] buttons -- check this one, uncheck others (there can be only ONE checked with "single" elements)
      cmax = document.getElementById('numchoices').value;
      for ( c = 0; c < cmax; c++ ) {
         thisElement = 'checkbtn-'+c;
         theNewCheckedOne = 'checkbtn-'+choicenum;
         if ( thisElement == theNewCheckedOne ) {
            // Check it!
            document.getElementById(thisElement).className = 'choicebtn-check-on';
         } else {
            // Un-check it
            document.getElementById(thisElement).className = 'choicebtn-check';
         }
      }


   } else {
      // MULTI - Add clicked choice to default-checked list
      // This routine works like a toggle - if checked, uncheck; if unchecked, check

      // Current value of default-checked list hidden field
      oldval = document.getElementById('checked').value;

      // Already checked?
      if ( document.getElementById('checkbtn-'+choicenum).className == 'choicebtn-check-on' ) {
         // uncheck - remove from default-checked list and turn [-/] button ON
         newdflist = oldval.replace(choicenum+';', '');
         document.getElementById('checkbtn-'+choicenum).className = 'choicebtn-check';

      } else {
         // check - add to default-checked list and turn [-/] button ON
         newdflist = oldval + choicenum+';';
         document.getElementById('checkbtn-'+choicenum).className = 'choicebtn-check-on';
      }

   } // end if single/multi allowed

   // Update hidden default-checked field value
   document.getElementById('checked').value = newdflist;

   // Save new value to form preview
   saveField(field_id, 'checked', newdflist);
}
</script>

<!---hidden fields used by ajaxDor function-->
<input id="qryactive" type="hidden" class="hidden">
<input id="qrywaiting" type="hidden" class="hidden">


<?
/*---------------------------------------------------------------------------------------------------------*
   _       _     _   ___  _       _     _
  /_\   __| | __| | | __|(_) ___ | | __| |
 / _ \ / _` |/ _` | | _| | |/ -_)| |/ _` |
/_/ \_\\__,_|\__,_| |_|  |_|\___||_|\__,_|

# Hidden add field popup dialog
/*---------------------------------------------------------------------------------------------------------*/
?>
<!--- addfield_buttons-container -->
<div id="addfield_buttons-container" style="display: none;">
<?
include("addfield_popup.inc.php");
?>
</div>
<!---END: addfield_buttons-container-->

<?
/*---------------------------------------------------------------------------------------------------------*
 ___                     _
/ __| __ _ __ __ ___    /_\   ___
\__ \/ _` |\ V // -_)  / _ \ (_-< _  _  _
|___/\__,_| \_/ \___| /_/ \_\/__/(_)(_)(_)

# popconfig-save_as
/*---------------------------------------------------------------------------------------------------------*/
$popup = "";
$popup .= "<p>Save changes as a new form and leave the old one intact.</p>";
$popup .= "<form id=\"saveas_form\" method=\"post\" action=\"edit_form.php\">\n";
$popup .= "<input type=\"hidden\" name=\"todo\" value=\"save_form\">\n";

$popup .= "<h2>New Form Name:</h2>\n";
$popup .= "<input type=\"text\" name=\"saveas_name\" value=\"".$getForm['form_name']." - Copy\" style=\"font-size: 16px;width: 200px;\">\n";
$popup .= "<div style=\"display: block;padding: 8px;text-align: left;\"><input id=\"save_as\" type=\"button\" ".$_SESSION['btn_save']." value=\"".lang("Save")." &gt;&gt;\" onclick=\"document.getElementById('saveas_form').submit();\" style=\"font-weight: bold;font-size: 14px;\"></div>\n";
$popup .= "</form>\n";
$other = array('onclose' => "show_dropdowns();");
echo help_popup("popconfig-save_as", "Save As...", $popup, "width: 325px;right: 10%;top: 30%;", $other);


/*---------------------------------------------------------------------------------------------------------*
 ___           _
| __|___  ___ | |_  ___  _ _
| _|/ _ \/ _ \|  _|/ -_)| '_|
|_| \___/\___/ \__|\___||_|
/*---------------------------------------------------------------------------------------------------------*/
# Ducttape for now
$savebtnstyle = " style=\"border-style: none solid solid; border-width: 1px;";
$savebtnstyle .= "background-image: url('http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/display_elements/graphics/btn-nav_save-off.jpg');\n";
$savebtnstyle .= "height: 20px;color: #000;\"";

?>
<div id="footer">
 <!---<< Back to Forms Manager-->
 <button id="backto_forms" type="button" class="grayButton" onclick="document.location.href='../forms_manager.php';"><span><span>&lt;&lt; My Forms</span></span></button>

 <!---Revert to Saved-->
 <button id="revert_to_saved" type="button" class="redButton" onclick="document.location.href='edit_form.php?todo=reset_form&form_id=<? echo $_REQUEST['form_id']; ?>';"><span><span>Revert to Saved</span></span></button>

 <!---display_settings-->
 <!--- <input id="display_settings" type="button" <? echo $_SESSION['btn_edit']; ?> value="Customize Look &amp; Feel" onclick="toggleid('popconfig-display_settings');"> -->

 <div id="save_buttons">
  <!---Add Field-->
  <button id="add_field" type="button" class="blueButton" onclick="toggleid('addfield_buttons-container');"><span><span>(+) Add Field</span></span></button>

  <!---Save As..-->
  <button id="save_as" type="button" class="greenButton" onclick="hide_dropdowns();toggleid('popconfig-save_as');"><span><span><?php echo lang("Save As..."); ?></span></span></button>

  <!---Save Changes-->
  <button id="save_changes" type="button" class="greenButton" onclick="document.location.href='edit_form.php?todo=save_form&form_id=<? echo $_REQUEST['form_id']; ?>';" style="font-weight: bold;"><span><span><? echo lang("Save Changes"); ?> &gt;&gt;</span></span></button>
  <div class="ie_cleardiv"></div>
 </div>

 <div class="ie_cleardiv"></div>
</div>


<?php
/*---------------------------------------------------------------------------------------------------------*
 __  __          _        _        ___   _            _
|  \/  | ___  __| | _  _ | | ___  |   \ (_) ___ _ __ | | __ _  _  _
| |\/| |/ _ \/ _` || || || |/ -_) | |) || |(_-<| '_ \| |/ _` || || |
|_|  |_|\___/\__,_| \_,_||_|\___| |___/ |_|/__/| .__/|_|\__,_| \_, |
                                               |_|             |__/
/*---------------------------------------------------------------------------------------------------------*/
?>

<div id="body_area_container">
<table id="split_parent_table" border="0" cellpadding="0" cellspacing="0">
 <tr>

  <!---LEFT: properties-->
  <th>
   <!---tab_bar-->
   <div id="tab_bar">
    <div id="tab-field" class="tab-on" onclick="props_tab('field');">
     Field Properties
    </div>
    <div id="tab-form" class="tab-off" onclick="props_tab('form');">
     Form Styles
    </div>
    <div class="ie_cleardiv"></div>
   </div>

   <!---form_properties-->
   <div id="form_properties">
<?
include("form_properties.inc.php");
?>
   </div>

   <!---field_properties-->
   <div id="field_properties">
    No field selected. Click on a field to edit its properties.
   </div>
  </th>

  <!---RIGHT: form preview area-->
  <td>
   <!---container-my_form-->
   <div id="container-my_form">
    <!---AJAX output goes here-->
   </div>
   <div class="ie_cleardiv">&nbsp;</div>
  </td>
 </tr>
</table>
</div>
<!---End: body_area_container-->


<script type="text/javascript">
// Hack to stop IE from caching queries
var mydate = new Date()
var randomman = mydate.getMilliseconds();

// Load form preview
ajaxDor('myform.ajax.php?form_id=<? echo $getForm['form_id']; ?>&dontcache='+randomman, 'container-my_form');

// Make sure Field Properties dialog shows 'select a field to edit properties' message
clear_properties();

<?
# Show saved message in properties div?
if ( $savedmsg != "" ) {
   echo "document.getElementById('field_properties').innerHTML = document.getElementById('field_properties').innerHTML + '<br/><br/><br/>".$savedmsg."';\n";
}
?>
// TESTING: While working on form properties dialog
//props_tab('form');

// IE hack to prevent vertical scrolling of parent layer
if ( navigator.appName.search("Internet Explorer") > 0 ) {
   ieheight = '393px';
   document.getElementById('body_area_container').style.height = ieheight;
}

</script>

<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Add or edit fields to your form. Make sure to save your changes periodically.");
//$instructions .= lang("Please only use alpha-numerical characters and spaces.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Edit Form";
$module->add_breadcrumb_link("Web Forms Manager", "program/modules/forms_manager.php");
$module->add_breadcrumb_link($getForm['form_name'].": <span class=\"unbold\">Add/Edit Fields</span>", "program/modules/forms_manager/edit_form.php?form_id=".$getForm['form_id']);
$module->icon_img = "skins/".$_SESSION['skin']."/icons/forms_manager-enabled.gif";
$module->heading_text = $getForm['form_name'].": <span class=\"unbold\">Add/Edit Fields</span>";
$module->description_text = $instructions;

# SPECIAL (for this module) - This module needs all the space it can get
$module->module_table_css = "margin: 0px;width: 100%;height: 100%;border: 0;";
$module->container_css = "margin: 0;padding: 0;";

$module->good_to_go();
?>