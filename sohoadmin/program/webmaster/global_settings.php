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

session_start();
include("../includes/product_gui.php");

#######################################################
### START HTML/JAVASCRIPT CODE			        ###
#######################################################


	   
	   if(!table_exists("site_specs")){
	     # Build site_specs field structure
	     $wDeez = "df_company CHAR(255), df_address1 CHAR(255), df_address2 CHAR(255), df_city CHAR(100), ";
	     $wDeez .= "df_state CHAR(100), df_zip CHAR(25), df_country CHAR(50), ";
	     $wDeez .= "df_phone CHAR(50), df_email CHAR(255), df_domain CHAR(255), ";
	     $wDeez .= "df_page CHAR(50), df_logo VARCHAR(255), df_lang CHAR(50), ";
	     $wDeez .= "news_cat VARCHAR(10), promo_cat VARCHAR(10),";
	     $wDeez .= "copyright BLOB,df_misc1 BLOB,df_misc2 BLOB,";
	     $wDeez .= "df_hdrtxt VARCHAR(255),df_slogan VARCHAR(255),dev_mode VARCHAR(255),startpage VARCHAR(255), df_fax VARCHAR(50)";
	     
	     
	     //   $nowww = preg_replace('/www\./', '', $this_ip);
	     $nowww = eregi_replace("^www\.", "", $this_ip);
	     if ( $headertext == "" ) { $headertext = "Welcome"; }
	     
	     $dfemail = strtolower($_SESSION['PHP_AUTH_USER']);
	     
	     # Build INSERT data
	     $nDis = "'$dfuser_company','$dfuser_address','$dfuser_aptnum','$dfuser_city',";
	     $nDis .= "'$dfuser_state','$dfuser_zip','$dfuser_country',";
	     $nDis .= "'$dfuser_phone','".$dfemail."','$this_ip',";
	     $nDis .= "'Home','','$lang_set',";
	     $nDis .= "'$newscat','$promocat',";
	     $nDis .= "'".date("Y")." $dfuser_company','','',"; // Clear through df_misc2
	     $nDis .= "'$headertext','$subheadertext','', 'Home', ''"; // Clear through df_fax
	     
	     # CREATE TABLE site_specs
	     if ( !mysql_db_query("$db_name","CREATE TABLE site_specs ($wDeez)") ) { $sql_prob++; }
	     
	     # INSERT INTO site_specs
	     if ( !mysql_query("INSERT INTO site_specs VALUES($nDis)") ) { $sql_prob++; }
	     
	     $selSpecs = mysql_query("SELECT * FROM site_specs");
	     
	   }




$thisip_error = '';
if($_POST['action'] == 'changethisip'){
	include('update_thisip.php');
}

$globalprefObj = new userdata('global');
if($globalprefObj->get('tinymode')==''){
	$globalprefObj->set('tinymode', 'basic');
}


if($globalprefObj->get('goog_trans')==''){
	$globalprefObj->set('goog_trans', 'off');
}
if($globalprefObj->get('goog_trans_website')==''){
	$globalprefObj->set('goog_trans_website', 'off');
}

if($_GET['tinymode']!=''){
	$globalprefObj->set('tinymode', $_GET['tinymode']);
}

if($_GET['goog_trans']!=''){
	$globalprefObj->set('goog_trans', $_GET['goog_trans']);
	echo "<script language=\"javascript\">\n";	
	echo " document.location.href='global_settings.php';\n";
	echo "</script>\n";
	exit;
}

if($_GET['goog_trans_website']!=''){
	$globalprefObj->set('goog_trans_website', $_GET['goog_trans_website']);
}


$MOD_TITLE = "Global Settings";

# To escape or not to escape?
# Designed to address gpc_magic_quotes problem (as in, how some have it on and some have it off)
function db_string_format($string) {
   if ( !get_magic_quotes_gpc() ) {
      return mysql_real_escape_string($string);
   } else {
      return $string;
   }
}

# Set new startpage
if ( $_GET['todo'] == "save_startpage" && $_GET['startpage'] != "" ) {
   $qry = "UPDATE site_specs SET startpage = '".$_GET['startpage']."'";
   //echo $qry; exit;
   if ( !mysql_query($qry) ) { echo lang("ERROR").": ".lang("Unable to change startpage assignment")."!<br/>".mysql_error(); exit; }
   $_SESSION['getSpec']['startpage'] = $_GET['startpage'];
}




if($_GET['df_email'] != '' && $_GET['todo'] == 'save_email'){
   $qry = "UPDATE site_specs SET df_email = '".$_GET['df_email']."'";   
   if ( !mysql_query($qry) ) { echo lang("ERROR").": ".lang("Unable to save email address")."!<br/>".mysql_error(); exit; }
   $_SESSION['getSpec']['df_email'] = $_GET['df_email'];   
}

/*---------------------------------------------------------------------------------------------------------*
   ____
  / __/___ _ _  __ ___
 _\ \ / _ `/| |/ // -_)
/___/ \_,_/ |___/ \__/

# Save language setting
/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['setlang'] != "" ) {

   # Update site_specs table
   $qry = "update site_specs set df_lang = '".mysql_real_escape_string(trim($_GET['setlang']))."'";
   $rez = mysql_query($qry);

   # Refresh lang strings in session
   $_SESSION['lang'] = NULL;
   $_SESSION['getSpec'] = NULL;
   $_SESSION['language'] = trim($_GET['setlang']);

   // Reload Tool Window Now
   $redir_to = "../../version.php";
   echo "<script language=\"javascript\">\n";
   echo " window.parent.location.href='http://".$_SESSION['docroot_url']."/sohoadmin/version.php';\n";
   echo "</script>\n";
   //header("Location: ".$redir_to);
   exit;
}

# Default
if ( $globalprefObj->get('utf8') == "" ) {
	$globalprefObj->set('utf8', 'on');
}

if ( $_GET['setutf8'] != "" ) {
	$globalprefObj->set('utf8', $_GET['setutf8']);
	$report[] = 'UTF8 preference set to: '.$_GET['setutf8'];
}

if ( $_GET['styleToggle'] != "" ) {
	$globalprefObj->set('styleToggle', $_GET['styleToggle']);
	$report[] = 'Text Editor Stylesheet preference set to: '.$_GET['styleToggle'];
}


#######################################################
### Read current info from site_specs
#######################################################
$spcRez = mysql_query("SELECT * from site_specs");
$pullSpec = mysql_fetch_array($spcRez);

$headertext = $pullSpec['df_hdrtxt'];
$subheadertext = $pullSpec['df_slogan'];
$df_logo = $pullSpec['df_logo'];

$df_company = $pullSpec['df_company'];
$df_address1 = $pullSpec['df_address1'];
$df_address2 = $pullSpec['df_address2'];
$df_city = $pullSpec['df_city'];
$df_state = $pullSpec['df_state'];
$df_zip = $pullSpec['df_zip'];
$df_country = $pullSpec['df_country'];
$df_phone = $pullSpec['df_phone'];
$df_email = $pullSpec['df_email'];
$df_domain = $pullSpec['df_domain'];
$df_page = $pullSpec['df_page'];
$df_logo = $pullSpec['df_logo'];
$df_lang = $pullSpec['df_lang'];


###############################################################################
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
###############################################################################

# Start buffering output
ob_start();
?>


<script language="JavaScript">
<!--
function SV2_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;
}
function SV2_showHideLayers() { //v3.0
  var i,p,v,obj,args=SV2_showHideLayers.arguments;
  for (i=0; i<(args.length-2); i+=3) if ((obj=SV2_findObj(args[i]))!=null) { v=args[i+2];
    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
    obj.visibility=v; }
}
function SV2_popupMsg(msg) { //v1.0
  alert(msg);
}
function SV2_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function navto(where) {
	window.location = where+"?<?=SID?>";
}

SV2_showHideLayers('addCartMenu?header','','hide');
SV2_showHideLayers('blankLayer?header','','hide');
SV2_showHideLayers('linkLayer?header','','hide');
SV2_showHideLayers('newsletterLayer?header','','hide');
SV2_showHideLayers('cartMenu?header','','show');
SV2_showHideLayers('menuLayer?header','','hide');
SV2_showHideLayers('editCartMenu?header','','hide');

// Show credit to translator where applicable
function lang_credit() {
   var isnow = document.getElementById('df_lang').value;
   if ( isnow == "dutch.php" ) {
      showid('lang_credit-dutch');
   } else {
      hideid('lang_credit-dutch');
   }
}
//-->
</script>

<?php

//$BG = "webmaster_bg.jpg";

####################################################################
### FOR VISUAL CONSISTANCY; WE USE AN HTML TEMPLATE BUILDER FILE
### LOCATED IN THE /shared FOLDER.  THIS WAY ALL OF OUR MODULE
### INTERFACES LOOK THE SAME. YOU MUST SUPPLY THE VARIABLES:
###
### $MOD_TITLE		Title of this Module
### $THIS_DISPLAY		HTML Content to display to end user
### $BG 			Background Image for content table if used
###
### THIS SAME METHOD SHOULD BE USED WHEN BUILDING ANY OF YOUR OWN
### CUSTOM MODULES.  REMEMBER TO INCLUDE THE HEADER "INCLUDES"
### ABOVE FOR PROPER FUNCTIONALITY WITHIN THE APPLICAITON.
####################################################################

#######################################################
### GET COUNTRY DATA FROM FLAT FILE
$filename = "shared/countries.dat";
$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open country data")." (shared/contries.dat).");
	$tmp_data = fread($file,filesize($filename));
fclose($file);

$natDat = split("\n", $tmp_data);
$numNats = count($natDat);

//natDat: T.M.I (for now) format for proper display and usage
$natNam = "";
for ($f=0; $f < $numNats; $f++) {
   $tmpSplt = split("::", $natDat[$f]);
   $natNam[$f] = "$tmpSplt[0] - $tmpSplt[1]";
   $natNam[$f] = strtoupper($natNam[$f]);
}
###
#######################################################

# Pull webmaster userdata
$webmaster_pref = new userdata("webmaster_pref");

//# PROCESS: Turn on/off main menu hover shortcuts
//if ( isset($_GET['mm_shortcuts']) ) {
//   $webmaster_pref->set("mm_shortcuts", $_GET['mm_shortcuts']);
//}

if ( isset($_POST['timezone_adjust']) ) {
   $webmaster_pref->set("timezone_adjust", $_POST['timezone_adjust']);
}

//# Make sure default is set
//if ( $webmaster_pref->get("mm_shortcuts") == "" ) {
//   $webmaster_pref->set("mm_shortcuts", "on");
//}

$THIS_DISPLAY = "";

# Webmaster nav button row
include("webmaster_nav_buttons.inc.php");


$THIS_DISPLAY .= "<fieldset style=\"width:99%;\" class=\"all_inline_labels\" id=\"sitebuilder_admin_tool\">\n";
$THIS_DISPLAY .= " <legend>".$_SESSION['hostco']['sitebuilder_name']." Admin Tool</legend>\n";

/*---------------------------------------------------------------------------------------------------------*
 __  __  __  __   ___  _               _              _
|  \/  ||  \/  | / __|| |_   ___  _ _ | |_  __  _  _ | |_  ___
| |\/| || |\/| | \__ \| ' \ / _ \| '_||  _|/ _|| || ||  _|(_-<
|_|  |_||_|  |_| |___/|_||_|\___/|_|   \__|\__| \_,_| \__|/__/

/*---------------------------------------------------------------------------------------------------------*/
# popup-mmshortcuts
$popup = "";
$popup .= "<p>Setting this option to 'on' will enable the shortcut buttons that appear when you mouse-over certain items on the Main Menu.</p>\n";
$popup .= "<p>Really just a personal preference thing here. Turn them on if you like them and find them useful; turn it off if you don't.</p>\n";
$popup .= "<p>If you have no idea what these buttons are, try turning this option 'on', then going to the Main Menu and putting your mouse\n";
$popup .= "over the File Manager button. You should see a little 'Upload Files' button appear above the main button.</p>\n";
$other = array('onclose' => "show_dropdowns();");
//$THIS_DISPLAY .= help_popup("popup-mmshortcuts", "Turn on/off Main Menu Shortcut buttons", $popup, "top: 20%;left: 10%;", $other);

//$THIS_DISPLAY .= "<div style=\"text-align: left;padding: 0px 2px 0px 2px;\">\n";
$THIS_DISPLAY .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";


//$THIS_DISPLAY .= " <tr>\n";
//$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" style=\"width:100%;\" >\n";
//$THIS_DISPLAY .= "   <label>".lang('Webmaster Email Address').": (".lang("used to retrieve forgotton login information.").")</label>\n";
//
//$THIS_DISPLAY .= "   <input style=\"width: 300px;\" id=\"df_email\" name=\"df_email\" size=\"35\" class=\"text\" type=\"text\" value=\"".$df_email."\">\n";
//
//
////$saveHref = basename($_SERVER['PHP_SELF'])."?setutf8='+\$('utf8').value;";
//$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;<button class=\"greenButton\" type=\"button\" onClick=\"document.location.href='global_settings.php?df_email='+document.getElementById('df_email').value+'&todo=save_email';\"><span><span>".lang('Update Email Address')."</span></span></button>\n";
//
//$THIS_DISPLAY .= "  </td>\n";
//$THIS_DISPLAY .= " </tr>\n";


//$THIS_DISPLAY .= " <tr>\n";
//$THIS_DISPLAY .= "  <td style=\"padding-top:10px;\" align=\"left\">\n";
//$THIS_DISPLAY .= "   Main Menu shortcut buttons:\n";
////$THIS_DISPLAY .= "  </td>\n";
////$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
//$THIS_DISPLAY .= "   <select id=\"mm_shortcuts\" name=\"mm_shortcuts\" onchange=\"document.location.href='global_settings.php?mm_shortcuts='+this.value;\">\n";
//$THIS_DISPLAY .= "    <option value=\"off\">off</option>\n";
//$THIS_DISPLAY .= "    <option value=\"on\">on</option>\n";
//$THIS_DISPLAY .= "   </select>\n";
//$THIS_DISPLAY .= "   <span class=\"orange uline hand\" onclick=\"showid('popup-mmshortcuts');hide_dropdowns();\">[?]</span>\n";
//$THIS_DISPLAY .= "  </td>\n";
//$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";
//$THIS_DISPLAY .= "</div>\n";


/*---------------------------------------------------------------------------------------------------------*
 _
| |    __ _  _ _   __ _  _  _  __ _  __ _  ___
| |__ / _` || ' \ / _` || || |/ _` |/ _` |/ -_)
|____|\__,_||_||_|\__, | \_,_|\__,_|\__, |\___|
                  |___/             |___/
# Sitebuilder interface language
/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "<table width=\"99%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" width=\"50%\">\n";
$THIS_DISPLAY .= "   <label>".$_SESSION['hostco']['sitebuilder_name']." Interface language:</label>\n";

function langchk() {
   $langDir = "../../language/*.php";
   $output = '';
	foreach (glob($langDir) as $langfile) {
	   	if(file_exists($langfile)) {
	   		$lfile = basename($langfile);
	   		$lname = eregi_replace('_', ' ', $lfile);
	   		$lname = eregi_replace('\.php', '', $lname);
			 if($lname!='index'){
	   			# Ability to turn off languages with credits
		   		if ( $lname != "dutch" || (strtolower($lname) == "dutch" && $_SESSION['hostco']['dutch_lang'] == "on") ) {
		      	   $output .= "<option value=\"". strtolower($lfile)."\">".ucfirst($lname)."</option>\n";
		      	}
	     	}
	   	}
	}
	return($output);
}

# df_lang
$THIS_DISPLAY .= "   <select name=\"df_lang\" id=\"df_lang\" style=\"width: 150px;\" class=\"text\" onchange=\"showid('save_lang_links');lang_credit();\">\n";
$THIS_DISPLAY .= langchk();
$THIS_DISPLAY .= "   </select>\n";
# lang_credit-dutch
$THIS_DISPLAY .= "   <div id=\"lang_credit-dutch\" style=\"display: none;\">\n";
$THIS_DISPLAY .= "    Special thanks to Theo Kiers (<span onclick=\"popup_window('http://www.aksomhosting.nl');\" class=\"blue uline hand\">www.aksomhosting.nl</span>)\n";
$THIS_DISPLAY .= "    single-handedly taking care of Dutch translation!\n";
$THIS_DISPLAY .= "   </div>\n";

# save_lang_links
$cancelJs = "document.getElementById('df_lang').value = '".$_SESSION['language']."';hideid('save_lang_links');";
$saveHref = basename($_SERVER['PHP_SELF'])."?setlang='+document.getElementById('df_lang').value;";
$THIS_DISPLAY .= "   <div id=\"save_lang_links\" style=\"display: none;\">\n";
$THIS_DISPLAY .= "    <span class=\"red uline hand\" onclick=\"".$cancelJs."\">Cancel</span> |\n";
$THIS_DISPLAY .= "    <a href=\"#\" class=\"sav\" onclick=\"document.location.href='".$saveHref."\">Save</a>\n";
$THIS_DISPLAY .= "   </div>\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";


$saveHref = basename($_SERVER['PHP_SELF'])."?goog_trans='+document.getElementById('goog_trans').value;";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" width=\"50%\">\n";
$THIS_DISPLAY .= "   <label>Display Google Translation option in site editor?</label>\n";

$THIS_DISPLAY .= "   <select name=\"goog_trans\" id=\"goog_trans\" style=\"width: 150px;\" class=\"text\" onchange=\"document.location.href='".$saveHref.";\">\n";
$THIS_DISPLAY .= "    <option value=\"on\">Yes</option>\n";
$THIS_DISPLAY .= "    <option value=\"off\">No</option>\n";
$THIS_DISPLAY .= "   </select>\n";

$THIS_DISPLAY .= "   <script type=\"text/javascript\">document.getElementById('goog_trans').value = '".$globalprefObj->get('goog_trans')."';</script>\n";

$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= " </tr>\n";


$saveHref = basename($_SERVER['PHP_SELF'])."?goog_trans_website='+document.getElementById('goog_trans_website').value;";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" width=\"50%\">\n";
$THIS_DISPLAY .= "   <label>Display Google Translation option on your website for website visitors?</label>\n";

$THIS_DISPLAY .= "   <select name=\"goog_trans_website\" id=\"goog_trans_website\" style=\"width: 150px;\" class=\"text\" onchange=\"document.location.href='".$saveHref.";\">\n";
$THIS_DISPLAY .= "    <option value=\"on\">Yes</option>\n";
$THIS_DISPLAY .= "    <option value=\"off\">No</option>\n";
$THIS_DISPLAY .= "   </select>\n";

$THIS_DISPLAY .= "   <script type=\"text/javascript\">document.getElementById('goog_trans_website').value = '".$globalprefObj->get('goog_trans_website')."';</script>\n";

$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= " </tr>\n";

/*---------------------------------------------------------------------------------------------------------*
 _   _ _____ ___   ___ 
| | | |_   _| __| ( _ )
| |_| | | | | _|  / _ \
 \___/  |_| |_|   \___/

# UTF8?
/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" width=\"50%\">\n";
$THIS_DISPLAY .= "   <label>".lang('Use UTF-8 encoding?')." (".lang("better if using double-byte characters like Chinese, Japanese, etc").")</label>\n";

# df_lang
$saveHref = basename($_SERVER['PHP_SELF'])."?setutf8='+document.getElementById('utf8').value;";
$THIS_DISPLAY .= "   <select name=\"utf8\" id=\"utf8\" style=\"width: 150px;\" class=\"text\" onchange=\"document.location.href='".$saveHref.";\">\n";
$THIS_DISPLAY .= "    <option value=\"off\">UTF-8 Off</option>\n";
$THIS_DISPLAY .= "    <option value=\"on\">UTF-8 On</option>\n";
$THIS_DISPLAY .= "   </select>\n";
$THIS_DISPLAY .= "   <script type=\"text/javascript\">document.getElementById('utf8').value = '".$globalprefObj->get('utf8')."';</script>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

$THIS_DISPLAY .= "</table>\n";

$THIS_DISPLAY .= "</fieldset>\n"; // End sitebuilder_admin_tool fieldset
// -------------------------------------------------------------------------------------------------------


# website_preferences
$THIS_DISPLAY .= "<fieldset class=\"all_inline_labels\" id=\"website_preferences\">\n";
$THIS_DISPLAY .= " <legend>".lang("General Website Preferences")."</legend>\n";

/*---------------------------------------------------------------------------------------------------------*
 ___  _               _
/ __|| |_  __ _  _ _ | |_   _ __  __ _  __ _  ___
\__ \|  _|/ _` || '_||  _| | '_ \/ _` |/ _` |/ -_)
|___/ \__|\__,_||_|   \__| | .__/\__,_|\__, |\___|
                           |_|         |___/
# Change default "start" page
/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= "<table width=\"99%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"top\" class=\"nopad\">\n";
$THIS_DISPLAY .= "   <table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
$THIS_DISPLAY .= "    <tr>\n";

# Viewing or editing?
if ( $_GET['todo'] == "change_startpage" ) { // Edit

   $THIS_DISPLAY .= "     <td width=\"40%\">".lang("Default \"home\" page").":</td>\n";

   # Cancel
   $THIS_DISPLAY .= "     <td>[ <a href=\"".$_SERVER['PHP_SELF']."\" class=\"del\">".lang("Cancel")."</a> ]</td>\n";

   # Save
   $save_onclick = "document.location.href='".basename($_SERVER['PHP_SELF'])."?todo=save_startpage&startpage='+document.getElementById('startpage_dd').value;";
   $THIS_DISPLAY .= "     <td>[ <span class=\"hand green_33 uline\" onclick=\"".$save_onclick."\"><b>".lang("Save")."</b></span> ]</td>\n";

   $THIS_DISPLAY .= "    </tr>\n";

   # Site pages dropdown
   $THIS_DISPLAY .= "    <tr>\n";
   $THIS_DISPLAY .= "     <td colspan=\"3\" class=\"nopad_top\">\n";
   $THIS_DISPLAY .= "      <select name=\"startpage\" id=\"startpage_dd\" style=\"width: 225px;\">\n";
   include("../modules/sitepage_dropdown.inc.php");
   $THIS_DISPLAY .= "      ".$dropdown_options."\n";
   $THIS_DISPLAY .= "      </select>\n";

   # Preselect current startpage setting
   $THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
   //$THIS_DISPLAY .= "alert('".startpage(false)."');\n";
   $THIS_DISPLAY .= "document.getElementById('startpage_dd').value = '".startpage(false)."';\n";
   $THIS_DISPLAY .= "</script>\n";

   $THIS_DISPLAY .= "     </td>\n";

} else { // View
   $THIS_DISPLAY .= "     <td style=\"white-space: nowrap;\">".lang("Default \"home\" page").": <b>".startpage(false)."</b></td>\n";
   $THIS_DISPLAY .= "     <td width=\"100%\">[ <a href=\"".$_SERVER['PHP_SELF']."?todo=change_startpage\">".lang("Change")."</a> ]</td>\n";
   $THIS_DISPLAY .= "    </tr>\n";
   $THIS_DISPLAY .= "    <tr>\n";
   $THIS_DISPLAY .= "     <td colspan=\"2\" class=\"gray_33 nopad_top\">\n";
   $THIS_DISPLAY .= "   ".lang("This page will be the first page that pulls up when a visitor goes to")." '".$this_ip."'.\n";
   $THIS_DISPLAY .= "   ".lang("Also known as").": ".lang("start page").", ".lang("index page").", ".lang("or")." ".lang("default page").".\n";
   $THIS_DISPLAY .= "     </td>\n";

}
$THIS_DISPLAY .= "    </tr>\n";


$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td style=\"white-space: nowrap;\">".lang("Text Editor Mode").":</td>\n";
$THIS_DISPLAY .= "     <td width=\"100%\">\n";
$saveTiny = basename($_SERVER['PHP_SELF'])."?tinymode='+document.getElementById('tinymode').value;";
$THIS_DISPLAY .= "   <select name=\"tinymode\" id=\"tinymode\" style=\"width: 150px;\" class=\"text\" onchange=\"document.location.href='".$saveTiny.";\">\n";
$THIS_DISPLAY .= "    <option value=\"basic\">Normal</option>\n";
$THIS_DISPLAY .= "    <option value=\"advanced\">Advanced</option>\n";
$THIS_DISPLAY .= "   </select>\n";
$THIS_DISPLAY .= "   <script type=\"text/javascript\">document.getElementById('tinymode').value = '".$globalprefObj->get('tinymode')."';</script>\n";

$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

/*---------------------------------------------------------------------------------------------------------*
Toggle style import in text editor
/*---------------------------------------------------------------------------------------------------------*/
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <label>".lang('Text Editor should use your template\'s stylesheet?')."</label>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
$saveHref = basename($_SERVER['PHP_SELF'])."?styleToggle='+document.getElementById('styleToggle').value;";
$THIS_DISPLAY .= "   <select name=\"styleToggle\" id=\"styleToggle\" style=\"width: 150px;\" class=\"text\" onchange=\"document.location.href='".$saveHref.";\">\n";
$THIS_DISPLAY .= "    <option value=\"no\">No</option>\n";
$THIS_DISPLAY .= "    <option value=\"yes\">Yes</option>\n";
$THIS_DISPLAY .= "   </select>\n";
$THIS_DISPLAY .= "   <script type=\"text/javascript\">document.getElementById('styleToggle').value = '".$globalprefObj->get('styleToggle')."';</script>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

$THIS_DISPLAY .= "   </table>\n";
$THIS_DISPLAY .= "  </td>\n";


$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

$THIS_DISPLAY .= "</fieldset>\n"; // End website_preferences fieldset

$THIS_DISPLAY .= "<SCRIPT LANGUAGE=JAVASCRIPT>\n";
$THIS_DISPLAY .= " document.getElementById('df_lang').value = '".$language."';\n";
$THIS_DISPLAY .= "</SCRIPT>\n\n";


/*---------------------------------------------------------------------------------------------------------*
 ___  _____  ___
| __||_   _|| _ \
| _|   | |  |  _/
|_|    |_|  |_|
/*---------------------------------------------------------------------------------------------------------*/
# Turned off in branding controls?
$ftpstuff = 'off';
if($ftpstuff != 'off'){
	if ( $_SESSION['hostco']['ftp_features'] != "off" ) {
	
	   # PROCESS: Save FTP info
	   if ( $_POST['ftp_username'] != "" ) {
	      # Save to db
	      $webmaster_pref->set("ftp_username", $_POST['ftp_username']);
	   //   $webmaster_pref->set("ftp_username", "");
	      $webmaster_pref->set("ftp_password", $_POST['ftp_password']);
	
	      # Verify that ftp info works
	      if ( !check_ftp() ) {
	      	$reportmsg .= lang("FTP connection attempt failed")." \n";
	      	$reportmsg .= "(username=".$webmaster_pref->get("ftp_username").", username=".$webmaster_pref->get("ftp_password")."). \n";
	      	$reportmsg .= lang("Please make sure the FTP login information you provided is correct.");
	      	$report[] = $reportmsg;
	
	      } else {
	         $report[] = lang("FTP connection successful! FTP login info saved.");
	      }
	   } // End if ftp info posted
	
	   # pophelp-ftp_info
	   $popup = "";
	   $popup .= "<p>".lang("Storing your FTP login username/password here will allow the sitebuilder tool to perform higher-level server/file system functions")."\n";
	   $popup .= " ".lang("it may not be able to perform with php's standard privileges").".</p>\n";
	
	   $popup .= "<p><b>".lang("Translation").":</b> ".lang("If the sitebuilder tool encounters certain problems (i.e. not being able to save page content, incomplete version update, etc)").", \n";
	   $popup .= lang("it will have more power to be able to fix the problem automatically and move past it instead of just throwing up an error message")." \n";
	   $popup .= lang("instructing you to go in and fix it \"manually\" via an FTP client").".</p>\n";
	
	   //$popup .= "<p style=\"text-align: center;\"><input type=\"button\" value=\"Fix My Permissions\"/></p>\n";
	   $THIS_DISPLAY .= help_popup("pophelp-ftp_info", lang("FTP Login Information"), $popup, "top: 10%;left: 10%;");
	
	   # ftp_login_info
	   $THIS_DISPLAY .= "<form name=\"ftpinfo_form\" id=\"ftpinfo_form\" method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."\">\n";
	   $THIS_DISPLAY .= "<fieldset id=\"ftp_login_info\" class=\"all_inline_labels\">\n";
	   $THIS_DISPLAY .= " <legend>\n";
	   $THIS_DISPLAY .= "  <span class=\"help_link\" onclick=\"toggleid('pophelp-ftp_info');\">[?]</span>\n";
	   $THIS_DISPLAY .= "  ".lang("FTP Login information")."\n";
	   $THIS_DISPLAY .= " </legend>\n";
	   # valid info on file?
	   $ftpmsg = "";
	   if ( check_ftp() ) {
	      # YES!
	      $ftpmsg .= "<div id=\"ftpmsg\" style=\"background: #fff;padding: 3px;\">\n";
	      $ftpmsg .= "<span class=\"green bold\">&radic;</span> ".lang("FTP connection succeeded using saved username/password. Your FTP info is good!");
	
	   } else {
	      # NO
	      $ftpmsg .= "<div id=\"ftpmsg\" style=\"background: #fff;padding: 3px;\">\n";
	      $ftpmsg .= " <span class=\"red bold\">&empty;</span>";
	      if ( $webmaster_pref->get("ftp_username") != ""  ) {
	         $ftpmsg .= " ".lang("FTP connection failed using saved username/password. Please make sure your FTP username and password are correct.");
	      } else {
	         $ftpmsg .= " ".lang("Cannot establish FTP connection. No valid FTP username & password on file. Please fill-in below.");
	      }
	   }
	   $ftpmsg .= "</div>\n";
	   $THIS_DISPLAY .= $ftpmsg;
	   $THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n";
	   $THIS_DISPLAY .= "  <tr>\n";
	   $THIS_DISPLAY .= "   <td>\n";
	   $THIS_DISPLAY .= "    <label>".lang("FTP Username").":</label>\n";
	   $THIS_DISPLAY .= "    <input type=\"text\" id=\"ftp_username\" name=\"ftp_username\" value=\"".$webmaster_pref->get("ftp_username")."\"/>\n";
	   $THIS_DISPLAY .= "   </td>\n";
	   $THIS_DISPLAY .= "   <td>\n";
	   $THIS_DISPLAY .= "    <label>".lang("FTP Password").":</label>\n";
	   $THIS_DISPLAY .= "    <input type=\"text\" id=\"ftp_password\" name=\"ftp_password\" value=\"".$webmaster_pref->get("ftp_password")."\"/>\n";
	   $THIS_DISPLAY .= "   </td>\n";
	   $THIS_DISPLAY .= "   <td>\n";
	   $THIS_DISPLAY .= "    <button type=\"button\" class=\"greenButton\" onClick=\"document.ftpinfo_form.submit();\"><span><span>".lang("Save Changes to FTP Info")." &gt;&gt;</span></span></button>\n";
	   $THIS_DISPLAY .= "   </td>\n";
	   $THIS_DISPLAY .= "  </tr>\n";
	   $THIS_DISPLAY .= " </table>\n";
	   //$THIS_DISPLAY .= " <p style=\"text-align: center;\"><input type=\"button\" value=\"Fix My Permissions\"/></p>\n";
	   $THIS_DISPLAY .= "</fieldset>\n"; // End: ftp_login_info
	   $THIS_DISPLAY .= "</form>\n";
	} // End if ftp_features != "off"
}


###Change Domain Name
$THIS_DISPLAY .= "<fieldset id=\"domain_preferences\">\n";
$THIS_DISPLAY .= " <legend>".lang("Change Website Domain Name")."</legend>\n";
$THIS_DISPLAY .= "<form name=\"thisip_form\" id=\"thisip_form\" method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."\">\n";
$THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n";

if(is_array($thisip_error)){
	foreach($thisip_error as $val){
		$THIS_DISPLAY .= "  <tr>\n";
		$THIS_DISPLAY .= "   <td>\n";
		$THIS_DISPLAY .= "    <label>\n";
		$THIS_DISPLAY .= "    <span style=\"color:red;\">*".lang("Error").":</span>&nbsp;".$val."\n";
		$THIS_DISPLAY .= "    </label>\n";
		$THIS_DISPLAY .= "   <td></tr>\n";
	}
} else {
	if(is_array($thisip_success)){
		foreach($thisip_success as $val){
			$THIS_DISPLAY .= "  <tr>\n";
			$THIS_DISPLAY .= "   <td>\n";
			$THIS_DISPLAY .= "    <label>\n";
			$THIS_DISPLAY .= "    <span style=\"color:green;\">*".lang("Success").":</span>&nbsp;".$val."\n";
			$THIS_DISPLAY .= "    </label>\n";
			$THIS_DISPLAY .= "   <td></tr>\n";
		}
	}
}

$THIS_DISPLAY .= "  <tr>\n";
$THIS_DISPLAY .= "   <td>\n";
$THIS_DISPLAY .= "    <label>".lang("Domain Name").":</label>\n";
$THIS_DISPLAY .= "    <span style=\"width:400px; font: 11px Courier New, courier, mono;\">http://</span><input style=\"width:400px; font: 11px Courier New, courier, mono;\" type=\"text\" id=\"newip_val\" name=\"newip_val\" value=\"".$_SESSION['this_ip']."\"/>\n";
$THIS_DISPLAY .= "    <input type=\"hidden\" id=\"thisip_val\" name=\"thisip_val\" value=\"".$_SESSION['this_ip']."\"/>\n";
$THIS_DISPLAY .= "    <input type=\"hidden\" name=\"action\" value=\"changethisip\"/>\n";
//$THIS_DISPLAY .= "   </td>\n";
//$THIS_DISPLAY .= "   <td>\n";
$THIS_DISPLAY .= "    &nbsp;&nbsp;&nbsp;&nbsp;<button type=\"button\" class=\"greenButton\" onClick=\"document.thisip_form.submit();\"><span><span>".lang("Save New Domain Name")." &gt;&gt;</span></span></button>\n";
$THIS_DISPLAY .= "   </td>\n";
$THIS_DISPLAY .= "  </tr>\n";
$THIS_DISPLAY .= " </table>\n";
$THIS_DISPLAY .= " </form>\n";
$THIS_DISPLAY .= "</fieldset>\n"; //
###End Change Domain Name


# Global time zone - applied in db_connect.php
$THIS_DISPLAY .= "<fieldset id=\"website_preferences\">\n";
$THIS_DISPLAY .= "<legend>".lang("Global Time Zone")."</legend>\n";
$THIS_DISPLAY .= "<form name=\"timezone_form\" id=\"timezone_form\" method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."\">\n";
$THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n";
$THIS_DISPLAY .= "  <tr>\n";
$THIS_DISPLAY .= "   <td>\n";
$THIS_DISPLAY .= "    <label>".lang("Time Zone").":</label>\n";
$THIS_DISPLAY .= "   </td>\n";
$THIS_DISPLAY .= "  </tr>\n";
$THIS_DISPLAY .= "  <tr>\n";
$THIS_DISPLAY .= "   <td>\n";
$THIS_DISPLAY .= "    <select name=\"timezone_adjust\" id=\"timezone_adjust\">\n";

ob_start();
?>
<option value="Pacific/Midway">(GMT-11:00) Midway Island, Samoa</option>
<option value="Etc/GMT+10">(GMT-10:00) Hawaii</option>
<option value="Pacific/Marquesas">(GMT-09:30) Marquesas Islands</option>
<option value="America/Anchorage">(GMT-09:00) Alaska</option>
<option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US & Canada)</option>
<option value="America/Denver">(GMT-07:00) Mountain Time (US & Canada)</option>
<option value="America/Chicago">(GMT-06:00) Central Time (US & Canada)</option>
<option value="America/New_York">(GMT-05:00) Eastern Time (US & Canada)</option>
<option value="America/Caracas">(GMT-04:30) Caracas</option>
<option value="America/Glace_Bay">(GMT-04:00) Atlantic Time (Canada)</option>
<option value="America/St_Johns">(GMT-03:30) Newfoundland</option>
<option value="America/Godthab">(GMT-03:00) Greenland</option>
<option value="America/Noronha">(GMT-02:00) Mid-Atlantic</option>
<option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
<option value="Europe/London">(GMT) Greenwich Mean Time : London</option>
<option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
<option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
<option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
<option value="Asia/Tehran">(GMT+03:30) Tehran</option>
<option value="Asia/Dubai">(GMT+04:00) Abu Dhabi, Muscat</option>
<option value="Asia/Kabul">(GMT+04:30) Kabul</option>
<option value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>
<option value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
<option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
<option value="Asia/Novosibirsk">(GMT+06:00) Novosibirsk</option>
<option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
<option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
<option value="Asia/Hong_Kong">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
<option value="Australia/Eucla">(GMT+08:45) Eucla</option>
<option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
<option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
<option value="Australia/Darwin">(GMT+09:30) Darwin</option>
<option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
<option value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Island</option>
<option value="Etc/GMT-11">(GMT+11:00) Solomon Is., New Caledonia</option>
<option value="Pacific/Norfolk">(GMT+11:30) Norfolk Island</option>
<option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
<option value="Pacific/Chatham">(GMT+12:45) Chatham Islands</option>
<option value="Pacific/Tongatapu">(GMT+13:00) Nuku'alofa</option>
<option value="Pacific/Kiritimati">(GMT+14:00) Kiritimati</option>

<?php
$THIS_DISPLAY .= ob_get_contents();
ob_end_clean();

$THIS_DISPLAY .= "   </td>\n";
$THIS_DISPLAY .= "   <td>\n";
$THIS_DISPLAY .= "    <button type=\"button\" class=\"greenButton\" onClick=\"document.timezone_form.submit();\"><span><span>".lang("Save Time Zone")." &gt;&gt;</span></span></button>\n";
$THIS_DISPLAY .= "   </td>\n";
$THIS_DISPLAY .= "  </tr>\n";
$THIS_DISPLAY .= " </table>\n";
$THIS_DISPLAY .= " </form>\n";
$THIS_DISPLAY .= "</fieldset>\n";


# Pre-select saved values
$THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
$THIS_DISPLAY .= "document.getElementById('timezone_adjust').value = '".$webmaster_pref->get("timezone_adjust")."';\n";
$THIS_DISPLAY .= "</script>\n";

####################################################################
echo $THIS_DISPLAY;
####################################################################

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Miscellaneous preferences, options, and settings that apply to many areas within the sitebuilder admin tool and the content it creates for your website.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("Global Settings");
$module->add_breadcrumb_link(lang("Webmaster"), "program/webmaster/webmaster.php");
$module->add_breadcrumb_link(lang("Global Settings"), "program/webmaster/global_settings.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/webmaster-enabled.gif";
$module->heading_text = lang("Global Settings");
$module->description_text = $instructions;
$module->add_cssfile("webmaster_global_styles.css");
$module->good_to_go();
?>