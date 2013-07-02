<?php
error_reporting(E_PARSE);
session_start();
require_once('../includes/product_gui.php');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

#======================================================================================
# This outputs the row of buttons displayed on each screen in the Webmaster feature
# Included by: webmaster.php, global_settings.php, etc.
#======================================================================================

# Buttons use this function (should eventually go in asmt_javascript.php or something like that)
$THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
$THIS_DISPLAY .= "function navto(a) {\n";
$THIS_DISPLAY .= "   window.location = a+\"?=".SID."\"\n";
$THIS_DISPLAY .= "}\n";
$THIS_DISPLAY .= "</script>\n";

$current_page = basename($_SERVER['PHP_SELF']);
//$current_page = '';

# This is the row of buttons displayed in Webmaster, Global Settings, and Meta Tag Data
$THIS_DISPLAY .= "<table border=\"0\" cellpadding=3 cellspacing=\"0\" width=\"590px\" align=\"left\" style=\"display:block;margin-bottom:10px;\">\n";

$THIS_DISPLAY .= " <tr>\n";


if($current_page != 'global_settings.php'){
# Global Settings
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('global_settings.php');\" class=\"blueButton\"><span><span>".lang("Global Settings")."</span></span></button></td>\n";
} else {
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('global_settings.php');\" class=\"grayButton\"><span><span>".lang("Global Settings")."</span></span></button></td>\n";
}

if($current_page != 'webmaster.php'){
# Administrator Logins
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('webmaster.php');\" class=\"blueButton\"><span><span>".lang("Admin Users")."</span></span></button></td>\n";
} else {
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('webmaster.php');\" class=\"grayButton\"><span><span>".lang("Admin Users")."</span></span></button></td>\n";
}

if($current_page != 'business_info.php'){
# Business info
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('business_info.php');\" class=\"blueButton\"><span><span>".lang("Default Contact Info")."</span></span></button></td>\n";
} else {
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('business_info.php');\" class=\"grayButton\"><span><span>".lang("Default Contact Info")."</span></span></button></td>\n";
}

if($current_page != 'meta_data.php'){
# Search Engine Ranking
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('meta_data.php');\" class=\"blueButton\"><span><span>".lang("Search Engine Ranking")."</span></span></button></td>\n";
} else {
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('meta_data.php');\" class=\"grayButton\"><span><span>".lang("Search Engine Ranking")."</span></span></button></td>\n";
}

# software updates

if($current_page != 'software_updates.php'){
   $THIS_DISPLAY .= "  <td align=\"center\">\n";
   $THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('software_updates.php');\" class=\"blueButton\"><span><span>".lang("Software Updates")."</span></span></button></td>\n";
} else {
   $THIS_DISPLAY .= "  <td align=\"center\">\n";
   $THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('software_updates.php');\" class=\"grayButton\"><span><span>".lang("Software Updates")."</span></span></button></td>\n";
}



if($current_page != 'backup_restore.php'){
# Administrator Logins
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('backup_restore.php');\" class=\"blueButton\"><span><span>".lang("Backup / Restore")."</span></span></button></td>\n";
} else {
	$THIS_DISPLAY .= "  <td align=\"center\">\n";
	$THIS_DISPLAY .= "   <button type=\"button\" onClick=\"navto('backup_restore.php');\" class=\"grayButton\"><span><span>".lang("Backup / Restore")."</span></span></button></td>\n";
}

# FAQ Manager
//$THIS_DISPLAY .= "  <td align=\"center\"><input type=\"button\" value=\"".lang("FAQ Manager")."\" ".$nav_main." onClick=\"navto('faq_manager.php');\" style='width: 150px;'></td>\n";

# Site Backup / Resotore
//if ( $SECURE_MOD_LICENSE == 1 ) { $THIS_DISPLAY .= "  <td align=\"center\"><input type=\"button\" value=\"Site Backup/Restore\" onClick=\"navto('backup_restore.php');\" ".$nav_main." style='width: 150px;'></td>\n"; }

$THIS_DISPLAY .= " </tr>\n";
//$THIS_DISPLAY .= " <tr style=\"height:3px;\"><td colspan=\"6\" style=\"line-height:3px;height:3px;\">&nbsp;\n";
//$THIS_DISPLAY .= " </td></tr>\n";
$THIS_DISPLAY .= "</table><br/>\n";

//$THIS_DISPLAY .= "<table style=\"width:100%; height:12px; line-height:12px; display:block; \"><tr><td style=\"height:12px;width:100%;\"><br/>&nbsp;</td></tr>\n</table>\n"


?>
