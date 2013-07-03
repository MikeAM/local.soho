<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../includes/product_gui.php');
chdir($curdir);
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
################################################################################################
##===============================================================================================
## Forms Manager Module v2.0
##===============================================================================================
#################################################################################################


//header("location: forms_manager/form_builder.php?form_id=billy");

# Make sure form-related db tables exist
include_once("forms_manager/form_dbcheck.inc.php");

# Shared forms-related php functions
include_once("forms_manager/forms_manager_functions.inc.php");

$formpref = new userdata('forms');

# Delete form
if ( $_GET['delete_form'] != "" ) {
   # Kill file
   $killfile = $_SESSION['docroot_path']."/media/".$_GET['delete_form'];
   unlink($killfile);

   # Kill db properties/field records (if exists)
   if ( $_GET['form_id'] != "" ) {
      $qry = "delete from form_properties where form_id = '".$_GET['form_id']."'";
      $rez = mysql_query($qry);
      $qry = "delete from form_fields where form_id = '".$_GET['form_id']."'";
      $rez = mysql_query($qry);
   }
}

# Save spam preference to block links
if ( $_GET['todo'] == 'block-links' ) {
	if ( $formpref->get('block-links') == 'on' ) {
		$formpref->set('block-links', 'off');
	} else {
		$formpref->set('block-links', 'on');
	}
}

# Save spam preference to block links
if ( $_GET['todo'] == 'include-captcha' ) {
	if ( $formpref->get('include-captcha') == 'on' ) {
		$formpref->set('include-captcha', 'off');
	} else {
		$formpref->set('include-captcha', 'on');
	}
}


# Save spam preference to block links
if ( $_GET['todo'] == 'double-emails' ) {
	if ( $formpref->get('double-emails') == 'on' ) {
		$formpref->set('double-emails', 'off');
	} else {
		$formpref->set('double-emails', 'on');
	}
}

# Compatibility on certain servers
if ( $_GET['todo'] == 'from-header' ) {
	if ( $formpref->get('from-header') == 'disabled' ) {
		$formpref->set('from-header', 'enabled');
	} else {
		$formpref->set('from-header', 'disabled');
	}
}

# spam-trap-message
if ( $_POST['spam-trap-message'] != '' ) {
	$formpref->set('spam-trap-message', $_POST['spam-trap-message']);
}



/*---------------------------------------------------------------------------------------------------------*
  _____                 __         ____
 / ___/____ ___  ___ _ / /_ ___   / __/___   ____ __ _
/ /__ / __// -_)/ _ `// __// -_) / _/ / _ \ / __//  ' \
\___//_/   \__/ \_,_/ \__/ \__/ /_/   \___//_/  /_/_/_/
/*---------------------------------------------------------------------------------------------------------*/
if ( $_POST['todo'] == "create_form" ) {
   # Form name blank? If so, call it "My Form"
   if ( trim($_POST['newform_name']) == "" ) {
      $_POST['newform_name'] = "My Form";
   }
	$form_error = '';
	$findforms = mysql_query("select form_name from form_properties where form_name = '".supersterilize($_POST['newform_name'])."'");
	if(mysql_num_rows($findforms) > 0){
		$form_error = "There is already a form using the name ".supersterilize($_POST['newform_name']);
		echo "<script language=\"javascript\">\nalert('".$form_error."');\n</script>\n";
	} else {
	
	   # form_properties
	   # ...Build form_properties insert
	   $data = array();
	   $data['form_name'] = $_POST['newform_name']; // For webmaster reference in forms manager, etc
	   $data['form_id'] = md5($_POST['newform_name']); // Necessary for temporary editing as "[id]-temp"
	   $data['date_created'] = time();
	   $data['form_filename'] = supersterilize($_POST['newform_name']); // For filename, etc
	
	   # Set default styles here for new forms
	   $style = array('label_width' => '75', 'label_textalign' => 'left');
	   $data['style'] = serialize($style);
	
	   $myqry = new mysql_insert("form_properties", $data);
	   $myqry->insert();
	
	   # form_fields
	   # ...Insert form title as a default heading
	   $data = array();
	   $data['dbname'] = supersterilize($_POST['newform_name']);
	   $data['title'] = $_POST['newform_name'];
	   $data['notes'] = lang("This is my form. Please fill it out.");
	   $data['field_type'] = lang("heading");
	   $data['field_id'] = time();
	   $data['form_id'] = md5($_POST['newform_name']);
	   $data['sort_order'] = 0;
	   $style = array('heading_level' => "1");
	   $data['style'] = serialize($style);
	   $myqry = new mysql_insert("form_fields", $data);
	   $myqry->insert();
	
	   # REDIRECT: Edit your new form now
	   header("location: forms_manager/edit_form.php?form_id=".$data['form_id']); exit;
	}
}

# Start buffering output
ob_start();
?>
<link rel="stylesheet" type="text/css" href="forms_manager/forms_manager.css">

<script type="text/javascript">
function chkkill(form_filename, form_id) {
   usure = window.confirm('<? echo lang("Are you sure you want to permanently delete this entire form");?>?\n\n<? echo lang("NOTE: If you have placed it on any pages those pages may display broken links, etc until you remove the form from them individually"); ?>.');

   if ( usure == true ) {
      document.location.href='forms_manager.php?delete_form='+form_filename+'&form_id='+form_id;
   }
}

function preview(form) {
   form = '<? echo $_SESSION['docroot_path']; ?>/media/'+form;
	document.getElementById('form_preview_window').src = 'forms_manager/preview_form.php?formfile='+form;
}

</script>

<!---container-saved_forms-->
<div id="container-saved_forms">
 <h1><? echo lang("Saved Forms"); ?></h1>

 <table width="100%" border="0" cellspacing="0" cellpadding="5" class="feature_sub">
  <tr>
   <td colspan="4" align="left" valign="middle" class="fsub_title">
    <? echo lang("Current Forms"); ?>
   </td>
  </tr>

<?
/*---------------------------------------------------------------------------------------------*
 __  __         ___
|  \/  | _  _  | __|___  _ _  _ __   ___
| |\/| || || | | _|/ _ \| '_|| '  \ (_-<
|_|  |_| \_, | |_| \___/|_|  |_|_|_|/__/
         |__/

#- List of existing forms from database (only those built via new method)
/*---------------------------------------------------------------------------------------------*/
$rez = mysql_query("SELECT prikey, form_id, form_name, form_filename FROM form_properties order by date_created desc");

if ( mysql_num_rows($rez) > 0 ) {
   while ( $getForm = mysql_fetch_array($rez) ) {

      if ( $bgclass == "fsub_border" ) { $bgclass = " class=\"fsub_border_alt\""; } else { $bgclass = " class=\"fsub_border\""; } // Alternate BG Color

      echo " <tr>\n";

      # Preview
      echo "  <td width=\"8%\" align=\"center\"".$bgclass." style=\"border-left: 0px;\">\n";
      echo "   <a href=\"#\" onclick=\"preview('".$getForm['form_filename'].".form.html');\"><img src=\"preview_icon.gif\" alt=\"Preview Form\" align=absmiddle border=0 vspace=3 hspace=3></a>\n";
      echo "  </td>";

      # Form Name
      echo "  <td width=\"53%\" align=\"left\"".$bgclass.">\n";
      echo "   <b>".$getForm['form_name']."</b>\n";
      echo "  </td>\n";

      # Delete Form
      echo "  <td width=\"17%\" align=\"center\"".$bgclass.">\n";
	  echo "   <button type=\"button\" class=\"redButton\" onclick=\"chkkill('".$getForm['form_filename']."','".$getForm['form_id']."')\"><span><span>".lang("Delete")."</span></span></button>\n";
      echo "  </td>\n";

//      # [ Display ]
//      echo "  <td width=\"22%\" align=\"center\"".$bgclass.">\n";
//      echo "   <input type=\"button\" onclick=\"window.location='forms_manager/edit_form.php?form_id=".$getForm['form_id']."'\" value=\"".lang("Display")."\"".$_SESSION['btn_build'].">\n";
//      echo "  </td>\n";

      # [ Edit Fields ]
      echo "  <td width=\"22%\" align=\"center\"".$bgclass.">\n";
	  echo "   <button type=\"button\" class=\"blueButton\" onclick=\"window.location='forms_manager/edit_form.php?form_id=".$getForm['form_id']."'\"><span><span>".lang("Edit")." &gt;&gt;</span></span></button>\n";
      echo "  </td>\n";

      echo " </tr>\n";
      $existing_forms++;

   } // End While
} // End if mysql_num_rows


 /*---------------------------------------------------------------------------------------------*
   ___           _                 ___
  / __|_  _  ___| |_  ___  _ __   | __|___  _ _  _ __   ___
 | (__| || |(_-<|  _|/ _ \| '  \  | _|/ _ \| '_|| '  \ (_-<
  \___|\_,_|/__/ \__|\___/|_|_|_| |_| \___/|_|  |_|_|_|/__/

 #- List 'Custom' forms (mainly those built via old method)
 /*---------------------------------------------------------------------------------------------*/
 $directory = "$doc_root/media";
 $handle = opendir("$directory");
 while ($files = readdir($handle)) {
    if (strlen($files) > 2) {
       if (eregi("\.form$", $files)) {
          $this_form = eregi_replace("_", " ", $files);
          $this_form = eregi_replace("\.form", "", $this_form);
          if ( $bgclass == "fsub_border" ) { $bgclass = " class=\"fsub_border_alt\""; } else { $bgclass = " class=\"fsub_border\""; } // Alternate BG Color
          echo " <tr>\n";

          # Preview
          echo "  <td width=\"8%\" align=\"center\"".$bgclass." style=\"border-left: 0px;\">\n";
          echo "   <a href=\"#\" onclick=\"preview('$files');\"><img src=\"preview_icon.gif\" alt=\"Preview Form\" align=absmiddle border=0 vspace=3 hspace=3></a>\n";
          echo "  </td>";

          # Form Name
          echo "  <td width=\"53%\" align=\"left\"".$bgclass.">\n";
          echo "   <b>".$this_form."</b>\n";
          echo "  </td>\n";

          # Delete Form
          echo "  <td width=\"17%\" align=\"center\"".$bgclass.">\n";
		  echo "   <button type=\"button\" onclick=\"chkkill('".$files."')\" class=\"redButton\"><span><span>".lang("Delete")."</span></span></button>\n";
          //echo "   [ <a href=\"#\" class=\"del\">".lang("Delete Form")."</a> ]\n";
          echo "  </td>\n";

          # Add New Fields
          echo "  <td width=\"22%\" align=\"center\" valign=\"middle\"".$bgclass.">\n";
		  echo "   <button type=\"button\" onclick=\"window.location='forms_manager-old.php?FORM_NAME=$files&ADDFIELDS=1'\" class=\"blueButton\"><span><span>".lang("Add Fields")."</span></span></button>\n";
          echo "  </td>";

          echo " </tr>\n";
          $existing_forms++;
       }
    }
 } // End While
 closedir($handle);

 if ($existing_forms == 0) {
    echo "<font color=#999999>";
	echo lang("There are currently no custom forms on your web site");
	echo ".</font>";
 }

 ?>
 </table>
</div>

<!---container-create_form-->
<div id="container-create_form">
 <form name="createformform" action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
  <input type="hidden" name="todo" value="create_form">
  <h1>Create new form</h1>
  <span class="label-newform_name"><? echo lang("Give your new form a name"); ?>:</span>
  <input type="text" id="newform_name" name="newform_name">
  <span id="container-create_form_button"><button type="button" id="button-create_form" value="" onclick="document.createformform.submit();" class="greenButton"><span><span><?php echo lang("Create Form"); ?> &gt;&gt;</span></span></button></span>
 </form>
 
<?php
if ( $formpref->get('block-links') == 'on' ) { $blocklinkschecked = ' checked'; } else { $blocklinkschecked = ''; }
if ( $formpref->get('include-captcha') == 'on' ) { $captchachecked = ' checked'; } else { $captchachecked = ''; }

if ( $formpref->get('double-emails') == 'on' ) { $doubleemailschecked = ' checked'; } else { $doubleemailschecked = ''; }
if ( $formpref->get('from-header') == 'disabled' ) { $fromheaderchecked = ' checked'; } else { $fromheaderchecked = ''; }
?>
 <form name="spampref" action="<? echo $_SERVER['PHP_SELF']; ?>" method="post">
 	<h1 style="margin-bottom:1;" >Spam Preferences</h1>
 	<table id="spam-preferences">
 		
 		<tr>
 			<th><input style="margin-top:-2;" type="checkbox" name="include-captcha" id="include-captcha" value="yes" onchange="document.location.href='forms_manager.php?todo=include-captcha'"<?php echo $captchachecked; ?>/></th>
 			<td><label for="include-captcha">Check to add a form verification field to forms to prevent spam bots.</label></td>
 		</tr>
 		
 		<tr>
 			<th><input style="margin-top:-2;"  type="checkbox" name="block-links" id="block-links" value="yes" onchange="document.location.href='forms_manager.php?todo=block-links'"<?php echo $blocklinkschecked; ?>/></th>
 			<td><label for="block-links">Block submissions that contain multiple link urls (e.g., "http://spamwebsite.com")</label></td>
 		</tr>
<?php
if ( $formpref->get('block-links') == 'on' ) {
	if ( $formpref->get('spam-trap-message') == '' ) {
		$rez = mysql_query('select df_phone from site_specs');
		$phoneStr = mysql_result($rez, 0);
		
		if ( $phoneStr != '' ) {
			$formpref->set('spam-trap-message', "Uh oh! Our system thinks you are a spammer, based on the format of your message. If you are not a spammer, we apologize for falsely accusing you. Please call us at ".$phoneStr.". We are always happy to talk to real people. :)");
		} else {
			$formpref->set('spam-trap-message', "Sorry for the inconvenience, but we cannot process your form submission because our system thinks (based on the format of your message) you are a spammer.");
		}
	}
?>
 		<tr>
 			<td colspan="2"><strong><?php echo lang('Custom spam trap message...'); ?></strong></th>
 		</tr>
 		<tr>
 			<td colspan="2"><textarea name="spam-trap-message" style="width: 275px;height: 75px;"><?php echo $formpref->get('spam-trap-message'); ?></textarea></th>
 		</tr> 		
 		<tr>
	<td colspan="2" align="right"><button type="button" onclick="document.spampref.submit();" class="greenButton"><span><span><?php echo lang("Save"); ?></span></span></button></th>
 		</tr> 		 		
<?php
}
?> 		
 	</table>
 </form>
 
	<h1 style="margin-bottom:1;">Diagnostic Tools</h1>
	<table>
		<tr>
			<th><input style="margin-top:-2;"  type="checkbox" name="double-emails" id="double-emails" value="yes" onchange="document.location.href='forms_manager.php?todo=double-emails'"<?php echo $doubleemailschecked; ?>/></th>
			<td><label for="double-emails">Check this box if you keep getting two emails for every form submission.</label></td>
		</tr>		
		<tr>
			<th><input style="margin-top:-2;"  type="checkbox" name="from-header" id="from-header" value="disabled" onchange="document.location.href='forms_manager.php?todo=from-header'"<?php echo $fromheaderchecked; ?>/></th>
			<td><label for="from-header">Check this box if email is not getting to the webmaster, but is getting to the form submitter.</label></td>
		</tr>				
	</table>
</div>

<!---form_preview_window-->
<div id="preview_window-container">
 <iframe id="form_preview_window" src="forms_manager/preview_form.php"></iframe>
</div>


<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Create new forms and view current forms.");
//$instructions .= lang("Please only use alpha-numerical characters and spaces.");

# Build into standard module template
$module = new smt_module($module_html);

$module->meta_title = "Web Forms Manager";
$module->add_breadcrumb_link(lang("Web Forms Manager"), "program/modules/forms_manager.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/forms_manager-enabled.gif";
$module->heading_text = lang("Web Forms Manager");
$module->description_text = $instructions;

# SPECIAL (for this module) - This module needs all the space it can get
$module->container_css = "margin: 0;padding: 0;";

$module->good_to_go();
?>
