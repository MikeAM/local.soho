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
### START HTML/JAVASCRIPT CODE			             ###
#######################################################

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

//-->
</script>

<?

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

# PROCESS: Turn on/off main menu hover shortcuts
if ( isset($_GET['mm_shortcuts']) ) {
   $webmaster_pref->set("mm_shortcuts", $_GET['mm_shortcuts']);
}

# Make sure default is set
if ( $webmaster_pref->get("mm_shortcuts") == "" ) {
   $webmaster_pref->set("mm_shortcuts", "on");
}

$THIS_DISPLAY = "";

# Webmaster nav button row
include("webmaster_nav_buttons.inc.php");


$THIS_DISPLAY .= "<fieldset id=\"sitebuilder_admin_tool\">\n";
$THIS_DISPLAY .= " <legend>".$_SESSION['hostco']['sitebuilder_name']." ".lang("Admin Tool")."</legend>\n";

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
$THIS_DISPLAY .= help_popup("popup-mmshortcuts", "Turn on/off Main Menu Shortcut buttons", $popup, "top: 20%;left: 10%;", $other);

$THIS_DISPLAY .= "<div style=\"text-align: left;padding: 10px 2px;\">\n";
$THIS_DISPLAY .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\">\n";
$THIS_DISPLAY .= "   Main Menu shortcut buttons:\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <select id=\"mm_shortcuts\" name=\"mm_shortcuts\" onchange=\"document.location.href='preferences.php?mm_shortcuts='+this.value;\">\n";
$THIS_DISPLAY .= "    <option value=\"off\">off</option>\n";
$THIS_DISPLAY .= "    <option value=\"on\">on</option>\n";
$THIS_DISPLAY .= "   </select>\n";
$THIS_DISPLAY .= "   <span class=\"orange uline hand\" onclick=\"showid('popup-mmshortcuts');hide_dropdowns();\">[?]</span>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</div>\n";

$THIS_DISPLAY .= "<script type=\"text/javascript\">\n";
$THIS_DISPLAY .= "document.getElementById('mm_shortcuts').value = '".$webmaster_pref->get("mm_shortcuts")."';\n";
$THIS_DISPLAY .= "</script>\n";


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
$THIS_DISPLAY .= "   <label>".$_SESSION['hostco']['sitebuilder_name']." interface language:</label>\n";

function langchk() {
   $langDir = "../../language/*.php";
   $output = '';
	foreach (glob($langDir) as $langfile) {
   	if(file_exists($langfile)) {
   		$lfile = basename($langfile);
   		$lname = eregi_replace('_', ' ', $lfile);
   		$lname = eregi_replace('\.php', '', $lname);
      	$output .= "<option value=\"". strtolower($lfile)."\">".ucfirst($lname)."</option>\n";
   	}
	}
	return($output);
}

# df_lang
$THIS_DISPLAY .= "   <select name=\"df_lang\" id=\"df_lang\" style=\"width: 150px;\" class=\"text\" onchange=\"showid('save_lang_links');\">\n";
$THIS_DISPLAY .= langchk();
//$THIS_DISPLAY .= langchk("Chinese");
////$THIS_DISPLAY .= langchk("English-UK");
//$THIS_DISPLAY .= langchk("Dutch");
//$THIS_DISPLAY .= langchk("English");
//$THIS_DISPLAY .= langchk("French");
//$THIS_DISPLAY .= langchk("Italian");
//$THIS_DISPLAY .= langchk("Japanese");
//$THIS_DISPLAY .= langchk("Korean");
//$THIS_DISPLAY .= langchk("Norwegian");
//$THIS_DISPLAY .= langchk("Russian");
//$THIS_DISPLAY .= langchk("Spanish");
//$THIS_DISPLAY .= langchk("Vietnamese");
$THIS_DISPLAY .= "   </SELECT>\n";

# save_lang_links
$cancelJs = "\$('df_lang').value = '".$_SESSION['language']."';hideid('save_lang_links');";
$saveHref = basename($_SERVER['PHP_SELF'])."?setlang='+\$('df_lang').value;";
$THIS_DISPLAY .= "   <div id=\"save_lang_links\" style=\"display: none;\">\n";
$THIS_DISPLAY .= "    <span class=\"red uline hand\" onclick=\"".$cancelJs."\">Cancel</span> |\n";
$THIS_DISPLAY .= "    <a href=\"#\" class=\"sav\" onclick=\"document.location.href='".$saveHref."\">Save</a>\n";
$THIS_DISPLAY .= "   </div>\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

$THIS_DISPLAY .= "</fieldset>\n"; // End sitebuilder_admin_tool fieldset
// -------------------------------------------------------------------------------------------------------


# website_preferences
$THIS_DISPLAY .= "<fieldset id=\"website_preferences\">\n";
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
$THIS_DISPLAY .= "   <table width=\"450\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">\n";
$THIS_DISPLAY .= "    <tr>\n";

# Viewing or editing?
if ( $_GET['todo'] == "change_startpage" ) { // Edit

   $THIS_DISPLAY .= "     <td width=\"40%\">Default \"home\" page:</td>\n";

   # Cancel
   $THIS_DISPLAY .= "     <td>[ <a href=\"".$_SERVER['PHP_SELF']."\" class=\"del\">Cancel</a> ]</td>\n";

   # Save
   $save_onclick = "document.location.href='".basename($_SERVER['PHP_SELF'])."?todo=save_startpage&startpage='+\$('startpage_dd').value;";
   $THIS_DISPLAY .= "     <td>[ <span class=\"hand green_33 uline\" onclick=\"".$save_onclick."\"><b>Save</b></span> ]</td>\n";

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
   $THIS_DISPLAY .= "     <td style=\"white-space: nowrap;\">Default \"home\" page: <b>".startpage(false)."</b></td>\n";
   $THIS_DISPLAY .= "     <td width=\"100%\">[ <a href=\"".$_SERVER['PHP_SELF']."?todo=change_startpage\">Change</a> ]</td>\n";
   $THIS_DISPLAY .= "    </tr>\n";
   $THIS_DISPLAY .= "    <tr>\n";
   $THIS_DISPLAY .= "     <td colspan=\"2\" class=\"gray_33 nopad_top\">\n";
   $THIS_DISPLAY .= "   This page will be the first page that pulls up when a visitor goes to 'yourdomain.com'.\n";
   $THIS_DISPLAY .= "   Also known as: start page, index page, default page.\n";
   $THIS_DISPLAY .= "     </td>\n";

}
$THIS_DISPLAY .= "    </tr>\n";
$THIS_DISPLAY .= "   </table>\n";
$THIS_DISPLAY .= "  </td>\n";


$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";

$THIS_DISPLAY .= "</fieldset>\n"; // End website_preferences fieldset

$THIS_DISPLAY .= "<SCRIPT LANGUAGE=JAVASCRIPT>\n";
$THIS_DISPLAY .= " \$('df_lang').value = '".$language."';\n";
$THIS_DISPLAY .= "</SCRIPT>\n\n";


/*---------------------------------------------------------------------------------------------------------*
 ___  _____  ___
| __||_   _|| _ \
| _|   | |  |  _/
|_|    |_|  |_|
/*---------------------------------------------------------------------------------------------------------*/
# Turned off in branding controls?
if ( $_SESSION['hostco']['ftp_features'] != "off" ) {

   # PROCESS: Save FTP info
   if ( $_POST['ftp_username'] != "" ) {
      # Save to db
      $webmaster_pref->set("ftp_username", $_POST['ftp_username']);
   //   $webmaster_pref->set("ftp_username", "");
      $webmaster_pref->set("ftp_password", $_POST['ftp_password']);

      # Verify that ftp info works
      if ( !check_ftp() ) {
      	$reportmsg .= "FTP connection attempt failed \n";
      	$reportmsg .= "(username=".$webmaster_pref->get("ftp_username").", username=".$webmaster_pref->get("ftp_password")."). \n";
      	$reportmsg .= "Please make sure the FTP login information you provided is correct.";
      	$report[] = $reportmsg;

      } else {
         $report[] = "FTP connection successful! FTP login info saved.";
      }
   } // End if ftp info posted

   # pophelp-ftp_info
   $popup = "";
   $popup .= "<p>Storing your FTP login username/password here will allow the sitebuilder tool to perform higher-level server/file system functions\n";
   $popup .= " it may not be able to perform with php's standard privileges.</p>\n";

   $popup .= "<p><b>Translation:</b> If the sitebuilder tool encounters certain problems (i.e. not being able to save page content, incomplete version update, etc), \n";
   $popup .= "it will have more power to be able to fix the problem automatically and move past it instead of just throwing up an error message \n";
   $popup .= "instructing you to go in and fix it \"manually\" via an FTP client.</p>\n";

   //$popup .= "<p style=\"text-align: center;\"><input type=\"button\" value=\"Fix My Permissions\"/></p>\n";
   $THIS_DISPLAY .= help_popup("pophelp-ftp_info", "FTP Login Information", $popup, "top: 10%;left: 10%;");

   # ftp_login_info
   $THIS_DISPLAY .= "<form id=\"ftpinfo_form\" method=\"post\" action=\"".basename($_SERVER['PHP_SELF'])."\">\n";
   $THIS_DISPLAY .= "<fieldset id=\"ftp_login_info\" class=\"all_inline_labels\">\n";
   $THIS_DISPLAY .= " <legend>\n";
   $THIS_DISPLAY .= "  <span class=\"help_link\" onclick=\"toggleid('pophelp-ftp_info');\">[?]</span>\n";
   $THIS_DISPLAY .= "  FTP Login information\n";
   $THIS_DISPLAY .= " </legend>\n";
   # valid info on file?
   $ftpmsg = "";
   if ( check_ftp() ) {
      # YES!
      $ftpmsg .= "<div id=\"ftpmsg\" style=\"background: #fff;padding: 3px;\">\n";
      $ftpmsg .= "<span class=\"green bold\">&radic;</span> FTP connection succeeded using saved username/password. Your FTP info is good!";

   } else {
      # NO
      $ftpmsg .= "<div id=\"ftpmsg\" style=\"background: #fff;padding: 3px;\">\n";
      $ftpmsg .= " <span class=\"red bold\">&empty;</span>";
      if ( $webmaster_pref->get("ftp_username") != ""  ) {
         $ftpmsg .= " FTP connection failed using saved username/password. Please make sure your FTP username and password are correct.";
      } else {
         $ftpmsg .= " Cannot establish FTP connection. No valid FTP username & password on file. Please fill-in below.";
      }
   }
   $ftpmsg .= "</div>\n";
   $THIS_DISPLAY .= $ftpmsg;
   $THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\">\n";
   $THIS_DISPLAY .= "  <tr>\n";
   $THIS_DISPLAY .= "   <td>\n";
   $THIS_DISPLAY .= "    <label>FTP Username:</label>\n";
   $THIS_DISPLAY .= "    <input type=\"text\" id=\"ftp_username\" name=\"ftp_username\" value=\"".$webmaster_pref->get("ftp_username")."\"/>\n";
   $THIS_DISPLAY .= "   </td>\n";
   $THIS_DISPLAY .= "   <td>\n";
   $THIS_DISPLAY .= "    <label>FTP Password:</label>\n";
   $THIS_DISPLAY .= "    <input type=\"text\" id=\"ftp_password\" name=\"ftp_password\" value=\"".$webmaster_pref->get("ftp_password")."\"/>\n";
   $THIS_DISPLAY .= "   </td>\n";
   $THIS_DISPLAY .= "   <td>\n";
   $THIS_DISPLAY .= "    <input type=\"submit\" ".$_SESSION['btn_save']." value=\"Save Changes to FTP Info &gt;&gt;\"/>\n";
   $THIS_DISPLAY .= "   </td>\n";
   $THIS_DISPLAY .= "  </tr>\n";
   $THIS_DISPLAY .= " </table>\n";
   //$THIS_DISPLAY .= " <p style=\"text-align: center;\"><input type=\"button\" value=\"Fix My Permissions\"/></p>\n";
   $THIS_DISPLAY .= "</fieldset>\n"; // End: ftp_login_info
   $THIS_DISPLAY .= "</form>\n";
} // End if ftp_features != "off"

####################################################################
echo $THIS_DISPLAY;
####################################################################

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Miscellaneous preferences, options, and settings that apply to many areas within the sitebuilder admin tool and the content it creates for your website.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Global Settings";
$module->add_breadcrumb_link("Webmaster", "program/webmaster/webmaster.php");
$module->add_breadcrumb_link("Global Settings", "program/webmaster/preferences.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/webmaster-enabled.gif";
$module->heading_text = "Global Settings";
$module->description_text = $instructions;
$module->add_cssfile("webmaster_global_styles.css");
$module->good_to_go();
?>