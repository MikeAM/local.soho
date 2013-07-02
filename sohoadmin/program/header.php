<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston 
## Copyright 2003-2007 Soholaunch.com, Inc.
## All Rights Reserved.
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

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// By License you may not modify any portion of this script. This particular
// script has dependancies and programming that can not be modified.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

error_reporting(0);
track_vars;
session_start();

# Include core interface files!
require_once("includes/product_gui.php");

###############################################################################
function enc($v) {
	$v = md5($v);
	return $v;
}
$SECURE_MOD_LICENSE = 0;
$tmp = eregi_replace("tmp_content", "", $cgi_bin);
$filename = $tmp."filebin/soholaunch.lic";
$file = fopen("$filename", "r");
	$data = fread($file,filesize($filename));
fclose($file);
$keydata = split("\n", $data);
// Security
$check_sum = enc("secure");
if (trim($keydata[7]) == $check_sum) {
	$SECURE_MOD_LICENSE = 1;
} else {
	$SECURE_MOD_LICENSE = 0;
}
###############################################################################
if($_GET['logout'] == 'logout'){
	session_destroy();
	echo "<script language=\"JavaScript\">\n";
	echo "parent.close();";
	echo "</script>\n";
}
?>

<html>
<head>
<title><? echo "Site: ".$_SESSION['this_ip']; ?></title>
<link rel="stylesheet" href="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/product_buttons-ultra.css">
<script language="JavaScript">

function killErrors() {
	return true;
}
window.onerror = killErrors;

function MM_findObj(n, d) { // v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

function MM_showHideLayers() { // v3.0
  var i,p,v,obj,args=MM_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}

function SOHO_openBrWindow(theURL,winName,features) {
  window.open(theURL,winName,features);
}

// #################################################################################

function SOHO_Alert(alertVar) {
	alert(alertVar);
}
</script>

<!-- ----------------------------------------------------------------------------- -->
<!-- CREATE CUSTOM JScript FUNCTIONS TO SAVE PAGE DATA IN "MAIN EDIT" WINDOW FRAME -->
<!-- ----------------------------------------------------------------------------- -->

<SCRIPT LANGUAGE="javascript">

function savePage(redirect) {

	var confirm_save = 1;

	if (redirect != "page_editor.php") {
		var tiny = window.confirm('<? echo lang("Do you wish to save the changes you have made"); ?>?\n\n<? echo lang("Click \"OK\" to Save changes now OR"); ?>\n<? echo lang("Click \"Cancel\" to discard changes"); ?>.');
		if (tiny != false) { var confirm_save = 1; } else { var confirm_save = 0; }
	}else{
	   var confirm_save = 1;
	}

   if(!document.all){
//      is_save = redirect.search("editor");
//      if(is_save>0){
//         redirect='page_editor.php';
//      }
   	if (confirm_save == 1) {
         //show_hide_layer('ProgressBarSave?body','','show');			// Display Save Progress Bar in Edit Window
   		//parent.footer.CURPAGENAME.innerHTML = 'Saving Changes...';	// Update Status Bar
   		var saveText = "";
   		var tempValue = "";
   		//var saveText = parent.body.saveForm.innerHTML;			// Get information from Page Editor
   		var saveText = parent.body.GetSaveForm();			// Get information from Page Editor

   		<?php

   		###############################################
   		## BUILD JSCRIPT ROW/COLUMN ARRAY FOR SAVING ##
   		###############################################

   		for ($x=1;$x<=10;$x++) {
   			for ($y=1;$y<=3;$y++) {
   				$thisVar = "R" . $x . "C" . $y;
   				//echo ("                 tempValue = parent.body.TD$thisVar.innerHTML;\n");
   				echo ("                   tempValue = parent.body.TouchMe('TD$thisVar');\n");

			      echo ("							is_txtarea = tempValue.search('<textarea');\n");
			      echo ("							if(is_txtarea>0){\n");
			      echo ("								var textArr = tempValue.split('<textarea')\n");
			      echo ("								var textLen = textArr.length\n");
			      //echo ("								alert(tempValue)\n");
			      echo ("								for(var x=0; x<textLen; x++){\n");
			      echo ("									tempValue = tempValue.replace('<textarea','<sohotextarea');\n");
			      echo ("									tempValue = tempValue.replace('</textarea>','</sohotextarea>');\n");
			      echo ("								}\n");
			      //echo ("								alert(tempValue)\n");
			      echo ("							}\n");
   				//echo ("                   tempValue = encodeURI(tempValue)\n");


   				echo ("                   saveText = saveText+\"<TEXTAREA NAME=$thisVar STYLE=display: none>\"+tempValue+\"</TEXTAREA>\";\n");
   			}
   		}

   		echo "            saveText = saveText+\"<input type=hidden name=redirect value=\"+redirect+\"><input type=hidden name=serial_number value='$serial_number'><input type=hidden name=dot_com value='$dot_com'>\";\n";
   		?>

   		//parent.body.saveForm.innerHTML = saveText;			// Finalize "Save Data"
   		parent.body.SendSaveText(saveText);			// Finalize "Save Data"
   		parent.body.GoToSave();							// Save current Page

   	} else {
         //parent.body.ShowNoSave();
   //      show_hide_layer('NOSAVE_LAYER','','show');			// Display NO SAVE Loading image in Edit Window
         if(redirect == "preview"){
            var daPage = parent.frames.body.sendPageName();
            var prev_path = "modules/page_editor/page_editor.php?previewWindow=1&currentPage="+daPage+"&=SID";
   		   parent.body.location.href=prev_path;
   		}else{
   		   parent.body.location.href="modules/page_editor/"+redirect;
   		}
   	}
   }else{

	if (confirm_save == 1) {
        MM_showHideLayers('ProgressBarSave?body','','show');			// Display Save Progress Bar in Edit Window
		parent.frames.footer.CURPAGENAME.innerHTML = 'Saving Changes...';	// Update Status Bar
		var saveText = "";
		var tempValue = "";
		var saveText = parent.frames.body.saveForm.innerHTML;			// Get information from Page Editor
		var daPage = parent.frames.body.sendPageName();
		<?

		###############################################
		## BUILD JSCRIPT ROW/COLUMN ARRAY FOR SAVING ##
		###############################################

		for ($x=1;$x<=10;$x++) {
			for ($y=1;$y<=3;$y++) {
				$thisVar = "R" . $x . "C" . $y;
				echo ("                    tempValue = parent.frames.body.TD$thisVar.innerHTML;\n");

		      echo ("							is_txtarea = tempValue.search('<TEXTAREA');\n");
		      echo ("							if(is_txtarea>0){\n");
		      echo ("								var textArr = tempValue.split('<TEXTAREA')\n");
		      echo ("								var textLen = textArr.length\n");
		      echo ("								for(var x=0; x<textLen; x++){\n");
		      echo ("									tempValue = tempValue.replace('<TEXTAREA','<sohotextarea');\n");
		      echo ("									tempValue = tempValue.replace('</TEXTAREA>','</sohotextarea>');\n");
		      echo ("								}\n");
		      echo ("							}\n");

				echo ("                 saveText = saveText+\"<TEXTAREA NAME=$thisVar STYLE=display: none>\"+tempValue+\"</TEXTAREA>\";\n");
			}
		}

		echo "            saveText = saveText+\"<input type=hidden name=redirect value=\"+redirect+\"><input type=hidden name=serial_number value='$serial_number'><input type=hidden name=dot_com value='$dot_com'>\";\n";
		//echo "         alert(saveText)\n";


		?>

		parent.frames.body.saveForm.innerHTML = saveText;			// Finalize "Save Data"
		parent.frames.body.save.submit();							// Save current Page

	} else {

        MM_showHideLayers('NOSAVE_LAYER?body','','show');			// Display NO SAVE Loading image in Edit Window
		parent.frames.body.location.href="main_menu.php?<?=SID?>";
	}
}

} // End Save Page Function

// <!-- ----------------------------------------------------------------------------- -->
// <!-- ----------------------------------------------------------------------------- -->

function StatusReset() {
<?
echo ("parent.footer.CURPAGENAME.innerHTML = '';\n");
echo ("parent.footer.SUBPAGEOF.innerHTML = \"\";\n");
echo ("parent.footer.PAGESTAT.innerHTML = '';\n");
?>
}

function redirect() {
<? echo("strLink = \"main_menu.php?=SID\";\n"); ?>
   parent.footer.orboff();
   parent.body.location.href=strLink;
}

function navigateHome() {
<? echo("strLink = \"main_menu.php?=SID\";\n"); ?>
   parent.body.location.href=strLink;
   var p = 'Main Menu';
   parent.frames.footer.setPage(p);
}

function navigateOpen() {
<? echo("strLink = \"modules/open_page.php?=".SID."\";\n"); ?>
   parent.body.location.href=strLink;
}

function logout() {
<? echo("strLink2 = \"header.php?logout=logout\";\n"); ?>
	parent.body.location.href=strLink2;
	//parent.close();
}

function logoutEditor() {
 parent.close();
}

function open_new_window(theURL,winName,features) {
	window.open(theURL,winName,features);
}

function viewsite() {
	open_new_window('http://<? echo $this_ip; ?>/index.php?nosessionkill=1','VIEWSITE','width=980,height=800, scrollbars=yes,resizable=yes,toolbar=yes');
}

function page_properties() {
   if(!document.all){
	   parent.body.ShowPageProps();
   }else{
      MM_showHideLayers('pageproperties?body','','show','saveaslayer?body','','hide');
      parent.frames.body.save.style.display = '';
   }
}

function save_as_layer() {
   if(!document.all){
      parent.body.ShowSaveAs();
   }else{
      MM_showHideLayers('pageproperties?body','','hide','saveaslayer?body','','show');
      parent.frames.body.save.style.display = '';
   }
}

function cartdo(value) {

	var doTest = 0;

	if (value == "userprefs") {
		strLink = "webmaster/webmaster.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "aupdate") {
		strLink = "webmaster/software_updates.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "bakrest") {
		strLink = "webmaster/backup_restore.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "metadata") {
		strLink = "webmaster/meta_data.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "globalset") {
		strLink = "webmaster/global_settings.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "siteback") {
		strLink = "webmaster/backup_restore.php?<?=SID?>";
		var doTest = 1;
	}

	if (value == "newsletter") {
		strLink = "modules/mods_full/enewsletter.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}

	if (value == "calendar") {
		strLink = "modules/mods_full/event_calendar.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}

	if (value == "shopping_cart") {
		strLink = "modules/mods_full/shopping_cart.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}

	if (value == "database") {
		strLink = "modules/mods_full/database_tables.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}

	if (value == "secure_users") {
		strLink = "modules/mods_full/security.php?<?=SID?>";
		parent.body.location.href=strLink;
		var doTest = 1;
	}


	if ( doTest == 1 ) {
		parent.body.location.href=strLink;
	} else {
		alert(value);
	}

}

function findit() {
	alert('Search Working');
}


<?
# Where should the [?] icon link to?
if ( $_SESSION['hostco']['help_icon'] == "custom" && $_SESSION['hostco']['help_icon_url'] != "" ) { // Pull link from branding options array
   $helpicon_goto = $_SESSION['hostco']['help_icon_url'];

} elseif ( strlen($users_man) > 10 && $_SESSION['hostco']['help_icon'] == "" ) {
   $helpicon_goto = $users_man; // Account for old branding method (host.conf.php)

} else {
   $helpicon_goto = "manual.soholaunch.com"; //  Go to Soholaunch Online Manual
}

$helpicon_goto = str_replace("http://", "", $helpicon_goto);

# Pass 'from product' var so manual script knows to scroll
if ( eregi("\?", $helpicon_goto) ) {
   $helpicon_goto .= "&p=1";
} else {
   $helpicon_goto .= "?p=1";
}

?>

function view_docs() {
   parent.footer.orboff();
	strLink = "http://<? echo $helpicon_goto; ?>";
	parent.body.location.href=strLink;
}

function showHelp(){
	//alert('something');
	window.open('modules/help_center/help_center.php','billy','width=800, height=600, scrollbars=auto');
	//window.open('includes/help_files/HelpMe_V2.php','billy','width=800, height=600, scrollbars=auto');
}

var newWin = null;
function openHelp(openLink){
	if (!newWin || newWin.closed){
		newWin=window.open(openLink,'HelpCenter','');
	} else {
		newWin.close();
		newWin=window.open(openLink,'HelpCenter','');
	}
}
<?php


/*---------------------------------------------------------------------------------------------------------*
 _  _            _
| || | ___  ___ | |__
| __ |/ _ \/ _ \| / /
|_||_|\___/\___/|_\_\

/*---------------------------------------------------------------------------------------------------------*/
eval(hook("header.php:top_javascript", basename(__FILE__)));
?>


</SCRIPT>
</head>
<!--- <link rel="stylesheet" href="product_gui.css" type="text/css"> --->
<link rel="stylesheet" href="includes/display_elements/product_gui-v2.css" type="text/css">

<body bgcolor="#EFEFEF" background="includes/images/top-bg.png" text="black" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">


<?

/// Determine current 'User Mode'
###===============================================================
$usrmde = "";
if ( eregi("php4evr", $getSpec["dev_mode"]) ) {
   $usrmde = "<font style=\"color: #D70000;\">Custom Mod</font>";

} elseif ( eregi("imadev", $getSpec["dev_mode"]) ) {
   $usrmde = "<font style=\"color: #FF6600;\">Advanced</font>";

} elseif ( eregi("devlite", $getSpec["dev_mode"]) ) {
   $usrmde = "<font style=\"color: #6699cc;\">Intermediate</font>";
}

//if ( $usrmde != "" ) {
//   echo "  <td align=\"left\" valign=\"middle\" class=\"text\" style=\"padding-left: 10px;\">\n";
//   echo "   <b>User Mode:</b> ".$usrmde."\n";
//   echo "  </td>\n";
//}


######################################################################################################
# Define button arrangements for feature modules
# NOTE: All upper menus have Main Menu and logout buttons
#
# mkbutton(id, display text, css class, onclick)
#=====================================================================================================
$upnav = array(); // Will contain each menu layer and its buttons - indexed by div id

## MAIN MENU
##===============================================
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_EDIT_PAGES;", $CUR_USER_ACCESS)) {
   $upnav['MAIN_MENU_LAYER'][] = mkbutton( "openpage", "Open Page", "nav_main", "navigateOpen()" );
}
$upnav['MAIN_MENU_LAYER'][] = mkbutton( "viewsite", "View Site", "nav_main", "viewsite();" );
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_WEBMASTER;", $CUR_USER_ACCESS)) {
   $upnav['MAIN_MENU_LAYER'][] = mkbutton( "webmaster", "Webmaster", "nav_main", "cartdo('userprefs');" );
}


## PAGE EDITOR
##===============================================
$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "openpage", "Open Page", "nav_main", "savePage('../open_page.php');" );
$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "savepage", "Save Page", "nav_save", "savePage('page_editor.php');" );
//$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "viewsite", "View Site", "nav_main", "viewsite();" );
if($_SESSION['product_mode']!='trial'){	
	$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "saveas", "Save As Copy", "nav_save", "save_as_layer();" );
}

$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "previewpage", "View Page", "nav_main", "savePage('preview');" );
$upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "prop", "Page Properties", "nav_main", "page_properties();" );
if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_FILES;", $CUR_USER_ACCESS)) {
   $upnav['PAGE_EDITOR_LAYER'][] = mkbutton( "uploadfiles", "Upload Files", "nav_main", "savePage('../upload_files.php');" );
}


## PAGE EDITOR NO SAVE/PREVIEW
##===============================================
$upnav['PAGE_EDITOR_LAYER_NO_SAVE'][] = "";
//$upnav['PAGE_EDITOR_LAYER_NO_SAVE'][] = mkbutton( "openpage", "Open Page", "nav_main", "savePage('../open_page.php');" );
//if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_FILES;", $CUR_USER_ACCESS)) {
//   $upnav['PAGE_EDITOR_LAYER_NO_SAVE'][] = mkbutton( "uploadfiles", "Upload Files", "nav_main", "savePage('../upload_files.php');" );
//}


# EDIT_PAGES_LAYER
$upnav['EDIT_PAGES_LAYER'][] = mkbutton("openpage", "Open Page", "nav_main", "navigateOpen()");
$upnav['EDIT_PAGES_LAYER'][] = mkbutton( "viewsite", "View Site", "nav_main", "viewsite();" );
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_TEMPLATES;", $CUR_USER_ACCESS) ) {
   $upnav['EDIT_PAGES_LAYER'][] = mkbutton("template_manager", "Template Manager", "nav_main", "parent.body.location.href='modules/site_templates.php';");
}
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_MENUSYS;", $CUR_USER_ACCESS) ) {
   $upnav['EDIT_PAGES_LAYER'][] = mkbutton("automenu", "Menu Navigation", "nav_main", "parent.body.location.href='modules/auto_menu_system.php';");
}


# TEMPLATE_MANAGER
#===============================================
//$upnav['TEMPLATE_MANAGER'][] = mkbutton("upload_template", "Upload Template", "nav_main", "parent.body.location.href='modules/site_templates.php?tabnum=3';");
$upnav['TEMPLATE_MANAGER'][] = mkbutton( "openpage", "Open Page", "nav_main", "navigateOpen()" );
$upnav['TEMPLATE_MANAGER'][] = mkbutton("automenu", "Menu Navigation", "nav_main", "parent.body.location.href='modules/auto_menu_system.php';");
$upnav['TEMPLATE_MANAGER'][] = mkbutton("template_manager", "Template Manager", "nav_main", "parent.body.location.href='modules/site_templates.php';");


## WEBMASTER
##===============================================
//$upnav['WEBMASTER_MENU_LAYER'][] = mkbutton( "software_updates", "Software Updates", "nav_main", "cartdo('aupdate');" );
$upnav['WEBMASTER_MENU_LAYER'][] = mkbutton( "openpage", "Open Page", "nav_main", "navigateOpen()" );
$upnav['WEBMASTER_MENU_LAYER'][] = mkbutton( "viewsite", "View Site", "nav_main", "viewsite();" );
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_WEBMASTER;", $CUR_USER_ACCESS)) {
   $upnav['WEBMASTER_MENU_LAYER'][] = mkbutton( "webmaster", "Webmaster", "nav_main", "cartdo('userprefs');" );
}


## SHOPPING CART
##===============================================
$upnav['CART_MENU_LAYER'][] = mkbutton( "shoppingcart", "Shopping Cart Menu", "nav_main", "cartdo('shopping_cart');" );


## CALENDAR_MENU_LAYER
##===============================================
$upnav['CALENDAR_MENU_LAYER'][] = mkbutton( "calmenu", "Calendar Menu", "nav_main", "cartdo('calendar');" );


## NEWSLETTER
##===============================================
$upnav['NEWSLETTER_LAYER'][] = mkbutton("newsmenu", "eNewsletter Menu", "nav_main", "cartdo('newsletter');");


## DATABASE_LAYER
##===============================================
$upnav['DATABASE_LAYER'][] = mkbutton("dbmenu", "Database Menu", "nav_main", "cartdo('database');");


# SECURE_USERS_LAYER
#===============================================
$upnav['SECURE_USERS_LAYER'][] = mkbutton("securemenu", "Secure Users Menu", "nav_main", "cartdo('secure_users');");


/*---------------------------------------------------------------------------------------------------------*
 ___  _              _         ___        _    _
| _ \| | _  _  __ _ (_) _ _   | _ ) _  _ | |_ | |_  ___  _ _   ___
|  _/| || || |/ _` || || ' \  | _ \| || ||  _||  _|/ _ \| ' \ (_-<
|_|  |_| \_,_|\__, ||_||_||_| |___/ \_,_| \__| \__|\___/|_||_|/__/
              |___/

# Build and output button sets defined by plugin modules, if any
# Example button entry in install_manifest..
#    $data['button1']['button_text'] = "My Button"
#    $data['button1']['button_onclick'] = "base64_encode(dosomething();)"
#    $data['button2']['button_text'] = "My Other Button"
#    $data['button2']['button_onclick'] = base64_encode("parent.body.location.href='checkoutmysinglequotes.php';")
/*---------------------------------------------------------------------------------------------------------*/
$plugin = special_hook("header_nav_buttons");

# Loop through each plugin that utilizes this hook
for ( $p = 0; $p < count($plugin); $p++ ) {

   # Loop through each button defined for this plugin
   for ( $b = 1; $b < count($plugin[$p]); $b++ ) {
      $button_id = str_replace(" ", "", $plugin[$p]['button'.$b]['button_text']);
      $div_id = $plugin[$p]['plugin_folder'];
      $onclick = base64_decode($plugin[$p]['button'.$b]['button_onclick']);
      $upnav[$div_id][] = mkbutton($button_id, $plugin[$p]['button'.$b]['button_text'], "nav_main", $onclick);
   }
}


/*---------------------------------------------------------------------------------------------------------*
 _  _            _
| || | ___  ___ | |__
| __ |/ _ \/ _ \| / /
|_||_|\___/\___/|_\_\
/*---------------------------------------------------------------------------------------------------------*/
eval(hook("header.php:menu_button_array"));

/*---------------------------------------------------------------------------------------------------------*
 ____          _  _      _     _    _  _______  __  __  _
|  _ \        (_)| |    | |   | |  | ||__   __||  \/  || |
| |_) | _   _  _ | |  __| |   | |__| |   | |   | \  / || |
|  _ < | | | || || | / _` |   |  __  |   | |   | |\/| || |
| |_) || |_| || || || (_| |   | |  | |   | |   | |  | || |____
|____/  \__,_||_||_| \__,_|   |_|  |_|   |_|   |_|  |_||______|
/*---------------------------------------------------------------------------------------------------------*/
# Will contain comma-separated list of menu button sets for use when building menu flip javascript
$button_sets = "";

/// Loop through button arrays and output divs and tables
###=========================================================================================
foreach ( $upnav as $divid=>$buttons ) {

   # Add div id of this button set to list used to build menu flip javascript
   $button_sets .= "\"".$divid."\",";

   echo "\n\n\n";
   echo "<!------------------------".$divid."------------------------>\n";
   echo "<div id=\"".$divid."\" class=\"upper_navbar\" style=\"margin-top: 0px; vertical-align: top;\">\n";
   echo " <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">\n";
   echo "  <tr>\n";
   echo "   <td align=\"left\" valign=\"top\" style=\"padding-top: 2px;\">\n";
   echo "    <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"upper_navbar\" align=\"center\" style=\"align:center;\">\n";
   echo "     <tr>\n";


   // Loop through button array and output table cells
   //===============================================================
   foreach ( $buttons as $nav=>$bttn ) {
      if(strlen($bttn)>1){
         echo "      <td align=\"center\" valign=\"top\">\n";
         echo "       ".$bttn."\n";
         echo "      </td>\n";
      }
   }

   // Place Main Menu, Logout, and Help [?] as standards except in Page Editor
   //--------------------------------------------------------------------------------
   if ( $divid != "PAGE_EDITOR_LAYER" && $divid != "PAGE_EDITOR_LAYER_NO_SAVE" ) {
      # Main Menu
      echo "      <td align=\"center\" valign=\"top\">\n";
      echo "       ".mkbutton( "mainmenu", "<b>".lang("Main Menu")."</b>", "nav_main", "navigateHome();" )."\n";
      echo "      </td>\n";

      # Logout
      echo "      <td align=\"center\" valign=\"top\">\n";
      echo "       ".mkbutton( "logout", "Logout", "nav_logout", "logout();" )."\n";
      echo "      </td>\n";

      # Help Docs [?]
      echo "      <td align=\"center\" valign=\"top\">\n";
      echo "		 <img src=\"includes/display_elements/graphics/help_center_icon-off.gif\" onClick=\"showHelp();\" onMouseOver=\"this.src='includes/display_elements/graphics/help_center_icon-on.gif';\" onMouseOut=\"this.src='includes/display_elements/graphics/help_center_icon-off.gif';\" width=\"19\" height=\"17\" alt=\"Help Center\" style=\"cursor: pointer;\">\n";
      echo "      </td>\n";

   } elseif ( $divid == "PAGE_EDITOR_LAYER_NO_SAVE" ) {           // When editing text in page editor
      # Main Menu
      echo "      <td align=\"center\" valign=\"middle\" style=\"font-size: 17px; font-weight: bold; color: #000000; padding-right:310px;\">\n";
//      echo "       Editing Page Content...\n";
      echo "      </td>\n";

   } else {
      # Main Menu
      echo "      <td align=\"center\" valign=\"top\">\n";
      echo "       ".mkbutton( "mainmenu", "<b>".lang("Main Menu")."</b>", "nav_main", "savePage('../../main_menu.php');" )."\n";
      echo "      </td>\n";
      
      echo "      <td align=\"center\" valign=\"baseline\" style=\"padding-top:3px;\">\n";
	 echo "	  <a onclick=\"openHelp('http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/help_center/help_center.php?man=Page_Editor.php');\" href=\"javascript:void(0);\" class=\"help\" style=\"vertical-align:baseline;padding:3px 15px 6px 25px; text-decoration:none;line-height:13px;width:25px;height:13px;font-family:Verdana,Geneva,sans-serif;font-size:11px;color:#ECECEC;background:url('http://".$_SESSION['docroot_url']."/sohoadmin/program/includes/images/hlp.png') no-repeat; \">Help</a>";
	 echo "      </td>\n";
      
   }

   echo "     </tr>\n";
   echo "    </table>\n";
   echo "   </td>\n";
   echo "  </tr>\n";
   echo " </table>\n";
   echo "</div>\n";
   echo "\n\n\n";

} // End for each menu div layer
/*****************************************************************/


# Hidden div layer to preload button rollover images
echo "<div id=\"preload_btns\" style=\"display: none; z-index: -10;\">\n";
$preload_nav = array( 'warn', 'save', 'main', 'logout' );
$img_dir = "includes/display_elements/graphics/";
foreach ( $preload_nav as $btn ) {
   $imgOff = $img_dir."btn-nav_".$btn."-off.jpg";
   $imgOn = $img_dir."btn-nav_".$btn."-on.jpg";

   echo " <img src=\"".$imgOff."\" height=\"1\" width=\"1\" border=\"0\">\n";
   echo " <img src=\"".$imgOn."\" height=\"1\" width=\"1\" border=\"0\">\n";

}
echo "</div>\n";

?>

<script type="text/javascript">
// Menu flip function
function flip_header_nav(divid) {
   var menusets = new Array(<? echo substr($button_sets, 0, -1); ?>);
   var setcount = menusets.length;
   //alert("Flipping menu to ["+divid+"]");

   // Show requested menu set and hide all other sets
   for ( m=0;m<=setcount;m++ ) {
      if ( menusets[m] == divid ) {
         parent.body.show_hide_layer(menusets[m]+'?header','','show');

      } else {
         parent.body.show_hide_layer(menusets[m]+'?header','','hide');
      }
   }
}
</script>





</BODY>
</HTML>
