<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

#=====================================================================================
# Soholaunch(R) Site Management Tool
#
# Author:        Mike Morrison
# Homepage:      http://www.soholaunch.com
# Release Notes: http://wiki.soholaunch.com
#
#=====================================================================================

error_reporting(E_PARSE);
session_start();

# Include core files
include_once($_SESSION['docroot_path']."/sohoadmin/program/includes/product_gui.php");
include_once($_SESSION['docroot_path']."/sohoadmin/program/includes/smt_module.class.php");

# Restore newsletter prefs from db
$newspref = new userdata("newsletter");

# PROCESS: Save prefs on form submit
if ( isset($_POST['pref']) ) {
   foreach ( $_POST['pref'] as $prefname=>$value ) {
	 if($value != '' || $prefname=='tablename_filter' || $prefname=='default_template' || $prefname=='pagename_filter'){
      	$newspref->set($prefname, $value);
     }
   }

   $report[] = "Preferences saved!";
}

# DEFAULTS: Set default preferences, where applicable
if ( $newspref->get("default_emailfrom") == "" ) {
   $newspref->set("default_emailfrom", $_SESSION['getSpec']['df_email']);
}

if ( $newspref->get("default_emailfrom_display") == "" ) {
   $newspref->set("default_emailfrom_display", $newspref->get("default_emailfrom"));
}



if($newspref->get("newsletter_send_to_address") == "" ) {
   $newspref->set("newsletter_send_to_address", "list@".preg_replace('/^www\./', '', $this_ip));
}

if($newspref->get("newsletter_send_to_name") == "" ) {
   $newspref->set("newsletter_send_to_name", preg_replace('/^www\./', '', $this_ip)." subscriber");
}



# Build site template list for drop-down
$temp_dir = $_SESSION['doc_root']."/sohoadmin/program/modules/site_templates/pages";
$thandle = opendir($temp_dir);
while ( $tmlates = readdir($thandle) ) {
   if ( strlen($tmlates) > 2 && eregi("NEWSLETTER", $tmlates) ) {
      $disTemp = $temp_dir."/".$tmlates;
      $handle = opendir($disTemp);
      while ($files = readdir($handle)) {
         if (strlen($files) > 2 && eregi("index.html", $files)) {
            $TEMPLATES .= "     <option value=\"".$tmlates."\">".$tmlates."</option>\n";
            break;
         }
      }
      closedir($handle);
   }
}
closedir($thandle);


# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();


/*---------------------------------------------------------------------------------------------------------*
 _  _       _         ___
| || | ___ | | _ __  | _ \ ___  _ __  _  _  _ __  ___
| __ |/ -_)| || '_ \ |  _// _ \| '_ \| || || '_ \(_-<
|_||_|\___||_|| .__/ |_|  \___/| .__/ \_,_|| .__//__/
              |_|              |_|         |_|
/*---------------------------------------------------------------------------------------------------------*/
# pophelp-filter_string
ob_start();
?>
 <p>In Step 1 of creating a newsletter campaign you are prompted to "Choose [the] database table that contains the email addresses for [your] campaign".
 By default this list contains all of your User Database Tables (UDT). If you have a lot of tables on your site this list can be kind of anoying to scroll through
 every time you create a campaign.</p>

 <p>By naming your email-list-containing tables consistently and setting a "filter string" here in Preferences you can shorten that
 list to just those tables whose name contains the string/word(s) you specify here, saving you a few seconds of minor frustration/confustion (they add up).</p>

 <h2>Example</h2>
 <p>Name any data table that contains email addresses for use your newsletter campaigns as "NEWSLIST_WHATEVER"
 (actual table names would be "UDT_NEWSLIST_WHATEVER"), then you set "NEWSLIST_" as your filter string.</p>

 <h2>Notes</h2>
 <ul>
  <li>Certain system tables like cart_customers are immune to the filter string and will always appear as options in table list
  (unless you set the "Hide known system tables" preference to "yes").</li>
  <li>Your filter string can be a regular expressions (for advanced users).</li>
  <li>Filter string is NOT case sensitive.</li>
 </ul>
<?
$popup = ob_get_contents();
ob_end_clean();

echo help_popup("pophelp-filter_string", "Database table name filter word/string", $popup, "top: 10%;left: 10%;width: 600px;");


/*---------------------------------------------------------------------------------------------------------*
 ___                   _  _  _____  __  __  _
| __|___  _ _  _ __   | || ||_   _||  \/  || |
| _|/ _ \| '_|| '  \  | __ |  | |  | |\/| || |__
|_| \___/|_|  |_|_|_| |_||_|  |_|  |_|  |_||____|
/*---------------------------------------------------------------------------------------------------------*/
?>
<style>
label {
   display: block;
   margin-top: 5px;
}
fieldset {
   margin-bottom: 15px;
   padding-bottom: 10px;
}
legend {
   font-size: 125%;
   font-weight: bold;
}
label .instructions {
   display: none;
   color: #888c8e;
}
input[type="submit"] {
   margin-top: 15px;
}
#default_emailfrom {
   width: 225px;
   font-family: Courier New, courier, mono;
   font-size: 1.2em;
}
</style>

<!---START: eNewsletter preferences form-->
<form id="enews_pref_form" name="enews_pref_form" method="post" action="preferences.php">

<!---Step 1-->
<fieldset>
 <legend><? echo lang("Create Campaign > Step 1 > Choose table containing email addresses"); ?></legend>
 <label>
 <a href="javascript:void(0);" style="text-decoration:none;" class="help_link" onclick="toggleid('pophelp-filter_string');">[?]</a>
 <? echo lang("DB table name filter"); ?></label>
 <input type="text" name="pref[tablename_filter]" value="<? echo $newspref->get("tablename_filter"); ?>">

 <label><? echo lang("Hide known system tables like cart_options, sec_users, and login from table list?"); ?></label>
 <select id="hide_systemtables" name="pref[hide_systemtables]">
  <option value="no" selected>No (default)</option>
  <option value="yes">Yes</option>
 </select>
 <script type="text/javascript">$('hide_systemtables').value = '<? echo $newspref->get("hide_systemtables"); ?>';</script>
</fieldset>

<!---Step 2: Owner information-->

<script type="text/javascript">
	function showFields(){
		if($('smtp_custom').value == "yes"){
			showid('smtp_custom_fields');
		}else{
			hideid('smtp_custom_fields');
		}
	}
</script>

<fieldset>
 <legend><? echo lang("Create Campaign > Step 3 > Owner Information"); ?></legend>
 <label><? echo lang("Default \"From:\" email address"); ?>?</label>
 <input type="text" style="width:300px;" id="default_emailfrom" name="pref[default_emailfrom]" value="<? echo $newspref->get("default_emailfrom"); ?>">
 
 <label><? echo lang("Default \"From:\" email display name"); ?>?</label>
 <input type="text" style="width:300px;" id="default_emailfrom_display" name="pref[default_emailfrom_display]" value="<? echo $newspref->get("default_emailfrom_display"); ?>">


	<label><? echo lang("Change to 'Send through hostname' only if you're getting an error when you try to send newsletters"); ?></label>
	<select id="smtp_custom" name="pref[smtp_custom]" onchange="showFields()">
	 <option value="no"><? echo lang("Default"); ?></option>
	 <option value="yes"><? echo lang("Custom"); ?></option>
	</select>

<div style="padding-top:5px;" id="smtp_custom_fields" style="display:none;">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td><? echo lang("SMTP Hostname"); ?></td>
		<td><? echo lang("SMTP Username"); ?></td>
		<td><? echo lang("SMTP Password"); ?></td>
		<td><? echo lang("SMTP Port"); ?></td>
	</tr>
	<tr>
		<td><input type="text" id="smtp_hostname" name="pref[smtp_hostname]" value="<? echo $newspref->get("smtp_hostname"); ?>"></td>
		<td><input type="text" id="smtp_username" name="pref[smtp_username]" value="<? echo $newspref->get("smtp_username"); ?>"></td>
		<td><input type="text" id="smtp_password" name="pref[smtp_password]" value="<? echo $newspref->get("smtp_password"); ?>"></td>
		<td><input type="text" id="smtp_port" size="4" name="pref[smtp_port]" value="<? echo $newspref->get("smtp_port"); ?>"></td>
	</tr>
</table>
</div>


</fieldset>



<!---Step 4: HTML content-->
<fieldset>
 <legend><? echo lang("Create Campaign > Step 4 > Select HTML Content"); ?></legend>
 <label><? echo lang("Which template should be selected by default"); ?>?</label>
 <select id="default_template" name="pref[default_template]">
  <option value="" selected>Newsletter templates...</option>
  <? echo $TEMPLATES; ?>
 </select>
 <script type="text/javascript">$('default_template').value = '<? echo $newspref->get("default_template"); ?>';</script>

 <label>
  <a href="javascript:void(0);" style="text-decoration:none;" class="help_link" onclick="toggleid('pagefilter_instructions');">[?]</a>
  <? echo lang("Newsletter content page filter"); ?>
  <span id="pagefilter_instructions" class="instructions">Similiar in function to the "DB table name filter" option above.
  Useful if you have a lot of site pages and/or have named those you intend to send as newsletters with a certain prefix
  (e.g., "NL Happy New Year", "NL Valentines Special", etc).</span>
 </label>
 <input type="text" name="pref[pagename_filter]" value="<? echo $newspref->get("pagename_filter"); ?>">
</fieldset>

<script type="text/javascript">
$('smtp_custom').value = '<?php echo $newspref->get("smtp_custom"); ?>';
showFields();
</script>




<!---Other options-->
<fieldset>
 <legend><? echo lang("Additional Options"); ?></legend>
 <label><? echo lang("What email address should newsletters use for the send to address?&nbsp;&nbsp;(default is ")."list@".preg_replace('/^www\./', '', $this_ip).")"; ?>?</label>
 <input type="text" style="width:300px;" id="newsletter_send_to_address" name="pref[newsletter_send_to_address]" value="<? echo $newspref->get("newsletter_send_to_address"); ?>">
 
 <label>
  <? echo lang("What display name should newsletters use for send to address?"); ?>
 </label>
 <input type="text" style="width:300px;" name="pref[newsletter_send_to_name]" value="<? echo $newspref->get("newsletter_send_to_name"); ?>">
</fieldset>

<button id="savebtn" type="button" class="greenButton" onClick="document.enews_pref_form.submit()"><span><span><? echo lang("Save Preferences"); ?> &gt;&gt;</span></span></button>

<script type="text/javascript">
$('smtp_custom').value = '<?php echo $newspref->get("smtp_custom"); ?>';
showFields();
</script>
</form>
<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

# Note: "Create Pages" used for example purposes. Replace with your own stuff.
$module = new smt_module($module_html);
$module->add_breadcrumb_link("eNewsletter", "program/modules/mods_full/enewsletter.php");
$module->add_breadcrumb_link("Preferences", "program/modules/mods_full/enewsletter/preferences.php");
$module->icon_img = "program/includes/images/newsletter-icon-med.png";
$module->heading_text = "eNewsletter Preferences";
$module->description_text = "Miscellaneous settings that will apply to the newsletter campaigns you create.";
$module->good_to_go();
?>
