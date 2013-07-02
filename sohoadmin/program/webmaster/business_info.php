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
require_once("../includes/product_gui.php");

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



#######################################################
### Read current info from site_specs
#######################################################
$spcRez = mysql_query("SELECT * from site_specs");
if(mysql_num_rows($spcRez) == 0){
	mysql_query("drop table site_specs");
	echo mysql_error()."<br/>";
}

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
	$nDis = "'','$dfuser_company','$dfuser_address','$dfuser_aptnum','$dfuser_city',";
	$nDis .= "'$dfuser_state','$dfuser_zip','$dfuser_country',";
	$nDis .= "'$dfuser_phone','".$dfemail."','$this_ip',";
	$nDis .= "'Home','','$lang_set',";
	$nDis .= "'$newscat','$promocat',";
	$nDis .= "'".date("Y")." $dfuser_company','','',"; // Clear through df_misc2
	$nDis .= "'$headertext','$subheadertext','', 'Home', ''"; // Clear through df_fax
	
	create_table("site_specs");
	# INSERT INTO site_specs
	if ( !mysql_query("INSERT INTO site_specs VALUES($nDis)") ) { $sql_prob++; }
	
	$spcRez = mysql_query("SELECT * FROM site_specs");

}


# Set new startpage
if ( $_GET['todo'] == "save_startpage" && $_GET['startpage'] != "" ) {
   $qry = "UPDATE site_specs SET startpage = '".$_GET['startpage']."'";
   //echo $qry; exit;
   if ( !mysql_query($qry) ) { echo "".lang("ERROR").": ".lang("Unable to change startpage assignment")."!<br/>".mysql_error(); exit; }
   $_SESSION['getSpec']['startpage'] = $_GET['startpage'];
}



# SAVE global settings action
if ( $_POST['action'] == "saveglobals" ) {

   //$df_addr = eregi_replace("\n", "", $df_addr);
   //$df_addr = eregi_replace("\r", "", $df_addr);
   //$df_addr = eregi_replace(",,", ", ", $df_addr);

   $df_domain = eregi_replace("http://","", $_POST['df_domain']);

   $stuff = "df_company = '".db_string_format($_POST['df_company'])
           ."', df_address1 = '".db_string_format($_POST['df_address1'])
           ."', df_address2 = '".db_string_format($_POST['df_address2'])
           ."', df_city = '".db_string_format($_POST['df_city'])
           ."', df_state = '".db_string_format($_POST['df_state'])
           ."', df_zip = '".db_string_format($_POST['df_zip'])
           ."', df_country = '".db_string_format($_POST['df_country'])
           ."', df_phone = '".db_string_format($_POST['df_phone'])
           ."', df_email = '".db_string_format($_POST['df_email'])
           ."', df_domain = '".db_string_format($_POST['df_domain'])
           ."', df_logo = '".db_string_format($_POST['df_logo'])
           ."', copyright = '".db_string_format($_POST['copyright'])
           ."', df_fax = '".db_string_format($_POST['df_fax'])."'";

   if ( $df_lang != "" && $df_lang != $getSpec['df_lang'] ) {
       $_SESSION['lang'] = NULL;
       $_SESSION['getSpec'] = NULL;
       $_SESSION['language'] = trim($df_lang);
       # Update site_specs table
       $stuff .= ", df_lang = '".mysql_real_escape_string($_SESSION['language'])."'";
   }

   mysql_query("UPDATE site_specs SET $stuff");

   // Reload Tool Window Now
   $redir_to = "../../version.php";
   echo "<script language=\"javascript\">\n";
//   echo " window.parent.location.href='http://".$_SESSION['docroot_url']."/sohoadmin/version.php';\n";
   echo " document.location='business_info.php';\n";  
   echo "</script>\n";
   //header("Location: ".$redir_to);
   exit;
}





$getSpec = mysql_fetch_assoc($spcRez);



$headertext = $getSpec['df_hdrtxt'];
$subheadertext = $getSpec['df_slogan'];
$df_logo = $getSpec['df_logo'];

$df_company = $getSpec['df_company'];
$df_address1 = $getSpec['df_address1'];
$df_address2 = $getSpec['df_address2'];
$df_city = $getSpec['df_city'];
$df_state = $getSpec['df_state'];
$df_zip = $getSpec['df_zip'];
$df_country = $getSpec['df_country'];
$df_phone = $getSpec['df_phone'];
$df_email = $getSpec['df_email'];
$df_domain = $getSpec['df_domain'];
$df_page = $getSpec['df_page'];
$df_logo = $getSpec['df_logo'];
$df_lang = $getSpec['df_lang'];


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
$file = fopen("$filename", "r") or DIE("".lang("Error").": ".lang("Could not open country data")." (shared/contries.dat).");
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



# Make sure default is set
if ( $webmaster_pref->get("mm_shortcuts") == "" ) {
   $webmaster_pref->set("mm_shortcuts", "on");
}

$THIS_DISPLAY = "";

# Webmaster nav button row
include("webmaster_nav_buttons.inc.php");


// ==============================================================================================
// BEGIN CONTACT INFO FIELDS
// ==============================================================================================

$THIS_DISPLAY .= "<table style=\"position:relative; width:100%;\"  border=\"0\" cellspacing=\"0\" cellpadding=\"5\">\n";

# df_domain
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" width=\"17%\">\n";

$THIS_DISPLAY .= "<form name=\"globe_ops\" method=post action=\"#\">\n";
$THIS_DISPLAY .= "<input type=hidden name=action value=\"saveglobals\">\n";

$THIS_DISPLAY .= "&nbsp;</td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">&nbsp;\n";
//$THIS_DISPLAY .= "   <label>".lang("Default domain name to display")."</label>\n";
//$THIS_DISPLAY .= "   <sublabel>".lang("For pre-populated email addresses and such").".</sublabel>\n";
//$THIS_DISPLAY .= "   <input style=\"width: 300px;\" type=\"text\" name=\"df_domain\" size=\"35\" class=text value=\"".$getSpec[df_domain]."\">\n";
$THIS_DISPLAY .= "  </td>\n";

# [Save Button] - Disable save action in demo site mode
$THIS_DISPLAY .= "  <td align=\"center\" width=\"305\" valign=\"top\" rowspan=\"5\">\n";
$THIS_DISPLAY .= "   <div id=\"savebtn-container\">\n";
$THIS_DISPLAY .= "    <button type=\"button\" name=\"Submit\" class=\"greenButton\" onClick=\"document.globe_ops.submit();\"><span><span>".lang("Save Settings")."</span></span></button>\n";
$THIS_DISPLAY .= "   </div>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_logo
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\" width=\"17%\">\n";
$THIS_DISPLAY .= "   ".lang("Logo Image").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <select name=\"df_logo\" style=\"width: 300px\" class=\"text\">\n";
# Build logo image drop-down
$THIS_DISPLAY .= "    <OPTION VALUE=\"\">[".lang("No Image")."] ($df_logo)</OPTION>\n";

##################################################################################
### READ IMAGE FILES INTO MEMORY
##################################################################################

//$img_selection = "     <OPTION VALUE=\" \">[".lang("No Image")."]</OPTION>\n";

$count = 0;
$directory = "$doc_root/images";
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$count++;
			$imageFile[$count] = ucwords($files) . "~~~" . $files;
		}
	}
$numImages = $count;
closedir($handle);

if ($count != 0) {
	sort($imageFile);
	if ($count == 1) {
		$imageFile[0] = $imageFile[1];
	}
	$numImages--;
}


for ($x=0;$x<=$numImages;$x++) {

	$thisImage = split("~~~", $imageFile[$x]);
	if (file_exists("$directory/$thisImage[1]")) {
//		$tempArray = getImageSize("$directory/$thisImage[1]");
		$origW = $tempArray[0];
		$origH = $tempArray[1];
		$oW = "";
		$oH = "";

		if ($origH > 300) {
				$oH = "HEIGHT=300 ";
		}

		if ($origW > 275) {
			$oW = "WIDTH=275";
		}

		$WH = "$oW $oH ";
	}

   if ( $thisImage[1] == $df_logo ) {
      $THIS_DISPLAY .= "     <option value=\"".$thisImage[1]."\" selected>".$thisImage[0]."</option>\n";
   } else {
      $THIS_DISPLAY .= "     <option value=\"".$thisImage[1]."\">".$thisImage[0]."</option>\n";
   }

	//$img_selection .= "     <option value=\"".$thisImage[0]."\">".$thisImage[0]."</option>\n";
}

$THIS_DISPLAY .= "   </select>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_email
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\" width=\"17%\">\n";
$THIS_DISPLAY .= "   ".lang("Default Email Address").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <input style=\"width: 300px;\" type=\"text\" name=\"df_email\" size=\"35\" class=text value=\"".$getSpec[df_email]."\">\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_company
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\" width=\"17%\">\n";
$THIS_DISPLAY .= "   ".lang("Name or Company").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <input style=\"width: 300px;\" type=\"text\" name=\"df_company\" size=\"35\" class=text value=\"".$getSpec[df_company]."\">\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_phone
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\" width=\"17%\">\n";
$THIS_DISPLAY .= "   ".lang("Phone Number").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <input style=\"width: 300px;\" type=\"text\" name=\"df_phone\" size=\"35\" class=text value=\"".$getSpec[df_phone]."\">\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_fax
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\" width=\"17%\">\n";
$THIS_DISPLAY .= "   ".lang("Fax Number").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <input style=\"width: 300px;\" type=\"text\" name=\"df_fax\" size=\"35\" class=text value=\"".$getSpec['df_fax']."\">\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_address1
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\">\n";
$THIS_DISPLAY .= "   ".lang("Address").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <input style=\"width: 300px;\" type=\"text\" name=\"df_address1\" size=\"35\" class=text value=\"".$getSpec[df_address1]."\">\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_address2
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\">\n";
$THIS_DISPLAY .= "   ".lang("Apt. / Suite").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <input style=\"width: 300px;\" type=\"text\" name=\"df_address2\" size=\"35\" class=text value=\"".$getSpec[df_address2]."\">\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_city, df_state, df_zip
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\">\n";
$THIS_DISPLAY .= "   ".lang("City").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\">\n";
$THIS_DISPLAY .= "   <table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
$THIS_DISPLAY .= "    <tr>\n";
$THIS_DISPLAY .= "     <td align=\"left\">\n";
$THIS_DISPLAY .= "      <input style=\"width: 100px;\" type=\"text\" name=\"df_city\" size=\"35\" class=text value=\"".$getSpec[df_city]."\">\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "     <td align=\"left\" width=\"25\">&nbsp;</td>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "      ".lang("State").":\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "     <td align=\"left\">\n";
$THIS_DISPLAY .= "      <input style=\"width: 100px;\" type=\"text\" name=\"df_state\" size=\"35\" class=text value=\"".$getSpec[df_state]."\">\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "     <td align=\"left\" width=\"25\">&nbsp;</td>\n";
$THIS_DISPLAY .= "     <td align=\"right\" valign=\"top\">\n";
$THIS_DISPLAY .= "      ".lang("Postal Code").":\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "     <td align=\"left\">\n";
$THIS_DISPLAY .= "      <input style=\"width: 85px;\" type=\"text\" name=\"df_zip\" size=\"35\" class=text value=\"".$getSpec[df_zip]."\">\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";
$THIS_DISPLAY .= "   </table>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# df_country
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\">\n";
$THIS_DISPLAY .= "   ".lang("Country").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"top\">\n";

$THIS_DISPLAY .= "   <SELECT NAME=\"df_country\" STYLE='font-family: Arial; font-size: 10px; width: 145px;'>\n";

//Build country list and select current
for ($n=0;$n < $numNats;$n++) {
	$sel = "";
	if ($natNam[$n] == $getSpec[df_country]) { $sel = "selected"; }
	$THIS_DISPLAY .= "    <OPTION VALUE=\"$natNam[$n]\" $sel>$natNam[$n]</OPTION>\n";
}

$THIS_DISPLAY .= "   </SELECT>\n";

$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";

# copyright
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td align=\"right\" valign=\"top\">\n";
$THIS_DISPLAY .= "   ".lang("Copyright Text").":\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= "  <td colspan=\"2\" align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "   <textarea style=\"width: 400px; height: 65px;\" name=\"copyright\" class=\"text\">".$getSpec['copyright']."</textarea>\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</FORM>\n";

$THIS_DISPLAY .= "<SCRIPT LANGUAGE=JAVASCRIPT>\n";
$THIS_DISPLAY .= " \$('df_lang').value = '".$language."';\n";
$THIS_DISPLAY .= "</SCRIPT>\n\n";

####################################################################
echo $THIS_DISPLAY;
####################################################################

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Fill-in this contact info for your business. Some site template layouts pull some or all of this information and display it in dedicated area(s) within the layout.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("Webmaster");
$module->add_breadcrumb_link(lang("Webmaster"), "program/webmaster/webmaster.php");
$module->add_breadcrumb_link(lang("Default Contact Info"), "program/webmaster/business_info.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/webmaster-enabled.gif";
$module->heading_text = lang("Default Contact Info");
$module->description_text = $instructions;
$module->add_cssfile("webmaster_global_styles.css");
$module->good_to_go();
?>
