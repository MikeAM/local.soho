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
require_once('../../includes/product_gui.php');
include_once('../../includes/smt_module.class.php');
#######################################################
### START HTML/JAVASCRIPT CODE			    ###
#######################################################
$statsObj = new userdata('global');
$MOD_TITLE = lang("Site Statistics");
$BG = "shared/stats_bg.jpg";

# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();


$st_db_result = soho_list_tables();
$st_db_match = 0;
$stdb_i = 0;
while ($stdb_i <= mysql_num_rows ($st_db_result)) {
	$tb_names[$stdb_i] = mysql_tablename($st_db_result, $stdb_i);
	if ($tb_names[$stdb_i] == "stats_top25_archive") { $st_db_match++; }
	if ($tb_names[$stdb_i] == "stats_byday_archive") { $st_db_match++; }
	if ($tb_names[$stdb_i] == "stats_byhour_archive") { $st_db_match++; }
	if ($tb_names[$stdb_i] == "stats_refer_archive") { $st_db_match++; }
	if ($tb_names[$stdb_i] == "stats_unique_archive") { $st_db_match++; }
	if ($tb_names[$stdb_i] == "stats_browser_archive") { $st_db_match++; }
	$stdb_i++;
}
if ($st_db_match != 6) {

	create_table("stats_top25_archive");
	create_table("stats_byday_archive");
	create_table("stats_byhour_archive");
	create_table("stats_refer_archive");
	create_table("stats_unique_archive");
	create_table("stats_browser_archive");
   	mysql_query("alter table stats_top25_archive modify PriKey int not null auto_increment primary key");
	mysql_query("alter table stats_byday_archive modify PriKey int not null auto_increment primary key");
	mysql_query("alter table stats_byhour_archive modify PriKey int not null auto_increment primary key");
	mysql_query("alter table stats_refer_archive modify PriKey int not null auto_increment primary key");
	mysql_query("alter table stats_unique_archive modify PriKey int not null auto_increment primary key");
	mysql_query("alter table stats_browser_archive modify PriKey int not null auto_increment primary key");
}


if (isset($_REQUEST['ARCHIVE'])) {
   header ("Location: statistics/statistics_archive.php");
   exit;   
}

?>

<script language="javascript">

function killErrors() {
	return true;
}
window.onerror = killErrors;

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

var p = "Site Statistics";
parent.frames.footer.setPage(p);

</script>

<?php

// Determine which stat mod to pull into IFRAME display.  This was treated
// with an IFRAME because we had just re-written the statistics routine
// weeks prior to the V4 project; therefore we simply ported over the stats
// routine from Version 3 with session and "real sql date" mods for V4.
// --------------------------------------------------------------------------

if ($STAT_SHOW == "" || isset($_POST['UNIQUE'])) {
	$STAT_SHOW = "statistics/includes/unique.php";
	//$STAT_SHOW = "http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/mods_full/".$STAT_SHOW;
	//$STAT_SHOW = "shopping_cart.php";
}

if (isset($_REQUEST['TOP25'])) { $STAT_SHOW = "statistics/includes/top25.php?SID=".SID; }
if (isset($_REQUEST['BYDAY'])) { $STAT_SHOW = "statistics/includes/byday.php?SID=".SID; }
if (isset($_REQUEST['BYHOUR'])) { $STAT_SHOW = "statistics/includes/byhour.php?SID=".SID; }
if (isset($_REQUEST['REFERER'])) { $STAT_SHOW = "statistics/includes/refer.php?SID=".SID; }
if (isset($_REQUEST['BROWSERS'])) { $STAT_SHOW = "statistics/includes/browser.php?SID=".SID; }
if (isset($_REQUEST['SPIDERS'])) { $STAT_SHOW = "statistics/includes/spiders.php?SID=".SID; }
if (isset($_REQUEST['GOOG_ANALTICS'])){
	$STAT_SHOW = "statistics/includes/googleAnalytics.php";
}

// Setup Sub-Mod Navigation to be consistant with V4 GUI
// --------------------------------------------------------------------------

$THIS_DISPLAY = "<form method=\"POST\" action=\"statistics.php\" style=\"display:inline;\">\n";

$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"100%\" align=\"center\" class=\"smtext\" style=\"height:95%;\">\n";
$THIS_DISPLAY .= "<tr>\n";

$THIS_DISPLAY .= "<td colspan=\"1\" align=\"left\" valign=\"middle\" class=\"text\">\n<table align=\"left\" class=\"smtext\" style=\"width:230px;\" cellspacing=0 cellpadding=0><tr>\n\n";

//$THIS_DISPLAY .= "<td><button onClick=\"document.location.href='statistics/includes/googleAnalytics.php';\" type=\"button\" name=\"GOOG_ANALTICS\" class=\"blueButton\"><span><span>".lang("Google Analytics")."</span></span></button></TD>\n";
//$THIS_DISPLAY .= "<td><button onClick=\"$('#stats_frame').attr('src','statistics/includes/unique.php');\" type=\"button\" name=\"UNIQUE\" class=\"blueButton\"><span><span>".lang("Unique Visitors")."</span></span></button></TD>\n";
//$THIS_DISPLAY .= "<td><button onClick=\"$('#stats_frame').attr('src','statistics/includes/top25.php');\" type=\"button\" name=\"TOP25\" class=\"blueButton\"><span><span>".lang("Top 25 Pages")."</span></span></span></span></button></TD>\n";
//$THIS_DISPLAY .= "<td><button onClick=\"$('#stats_frame').attr('src','statistics/includes/byday.php');\" type=\"button\" name=\"BYDAY\" class=\"blueButton\"><span><span>".lang("Views By Day")."</span></span></button></TD>\n";
//$THIS_DISPLAY .= "<td><button onClick=\"$('#stats_frame').attr('src','statistics/includes/byhour.php');\" type=\"button\" name=\"BYHOUR\" class=\"blueButton\"><span><span>".lang("Views By Hour")."</span></span></button></TD>\n";
//$THIS_DISPLAY .= "<td><button onClick=\"$('#stats_frame').attr('src','statistics/includes/refer.php');\" type=\"button\" name=\"REFERER\" class=\"blueButton\"><span><span>".lang("Referrer Sites")."</span></span></button></TD>\n";
//$THIS_DISPLAY .= "<td><button onClick=\"$('#stats_frame').attr('src','statistics/includes/browser.php');\" type=\"button\" name=\"BROWSERS\" class=\"blueButton\"><span><span>".lang("Browser/OS")."</span></span></button></TD>\n";
//$THIS_DISPLAY .= "<td><button onClick=\"$('#stats_frame').attr('src','statistics/includes/spiders.php');\" type=\"button\" name=\"SPIDERS\" class=\"blueButton\"><span><span>".lang("Web Crawlers")."</span></span></button></TD>\n";
//$THIS_DISPLAY .= "<td><input type=\"SUBMIT\" name=\"ARCHIVE\" value=\"".lang("Archive Stats")."\" class=\"FormLt1\"></TD>\n";
$THIS_DISPLAY .= "	<td colspan=\"1\">\n";
$THIS_DISPLAY .= "		View Stats For: \n";
$THIS_DISPLAY .= "		<select id=\"show\" name=\"show\" onChange=\"$('#stats_frame').attr('src',this.options[this.selectedIndex].value);\">\n";
// $('#stats_frame').attr('src','statistics/includes/browser.php');
$THIS_DISPLAY .= "		<option value=\"statistics.php\" style=\"font-style:italic;\">select stats page ...</option>\n";
$THIS_DISPLAY .= "		<option value=\"statistics/includes/unique.php\">".lang("Unique Visitors")."</option>\n";
$THIS_DISPLAY .= "		<option value=\"statistics/includes/top25.php\">".lang("Top 25 Pages")."</option>\n";
$THIS_DISPLAY .= "		<option value=\"statistics/includes/byday.php\">".lang("Views By Day")."</option>\n";
$THIS_DISPLAY .= "		<option value=\"statistics/includes/byhour.php\">".lang("Views By Hour")."</option>\n";
$THIS_DISPLAY .= "		<option value=\"statistics/includes/refer.php\">".lang("Referrer Sites")."</option>\n";
$THIS_DISPLAY .= "		<option value=\"statistics/includes/browser.php\">".lang("Browser/OS")."</option>\n";
$THIS_DISPLAY .= "		<option value=\"statistics/includes/spiders.php\">".lang("Web Crawlers")."</option>\n";
$THIS_DISPLAY .= "		<option value=\"statistics/includes/googleAnalytics.php\">".lang("Google Analytics")."</option>\n";
//$THIS_DISPLAY .= "		<option value=\"statistics/statistics_archive.php\">".lang("Archive Stats")."</option>\n";
$THIS_DISPLAY .= "		</select>\n";
$THIS_DISPLAY .= "	</form></td>\n";
$THIS_DISPLAY .= "</tr>\n</table>\n";


$THIS_DISPLAY .= "</td>\n";

if($_POST['statstogle']=='enable'){
	$statsObj->set('disable-stats','no');
}
if($_POST['statstogle']=='disable'){
	$statsObj->set('disable-stats','disable');
}

$THIS_DISPLAY .= "<td colspan=\"7\" align=\"left\" valign=\"middle\" class=\"text\">\n";
$THIS_DISPLAY .= "<form name=\"togglestats\" method=\"POST\" action=\"statistics.php\" style=\"display:inline;\">\n";
$THIS_DISPLAY .= "Enable/Disable: <select name=\"statstogle\" style=\"width: 290px; \" onchange=\"document.togglestats.submit();\">\n";
$selected_on = ' selected="selected"';
$selected_off = '';

if($statsObj->get('disable-stats')=='disable'){
	$selected_off = ' selected="selected"';
	$selected_on = '';
}
$THIS_DISPLAY .= "<option value=\"enable\" ".$selected_on.">Track Site Visitor Traffic & Statistics (default)</option>\n";
$THIS_DISPLAY .= "<option value=\"disable\" ".$selected_off.">Disable Tracking of Site Visitors & Statistics</option>\n";
$THIS_DISPLAY .= "</select>\n";
$THIS_DISPLAY .= "<span style=\"color:#999999;font-style:italic;\"><br/>&nbsp;*(Disabling stats may speed-up your website's page load times)</span>\n";
$THIS_DISPLAY .= "</form>\n";
$THIS_DISPLAY .= "</td>\n";



$THIS_DISPLAY .= "</tr>\n";


$THIS_DISPLAY .= "<tr><td colspan=\"8\" align=\"left\" valign=\"middle\" class=\"text\">\n";
$THIS_DISPLAY .= "<FONT COLOR=\"#999999\">".lang("You should <a href=\"statistics.php?ARCHIVE=yes\" style=\"color:#999999;\">archive your stats</a> at least every six months or so depending on traffic.");
$THIS_DISPLAY .= "&nbsp;".lang("If you experience slowness in loading reports, your log tables have probably gone unattended for some time.")."</FONT>&nbsp;\n";
$THIS_DISPLAY .= "\n";
$THIS_DISPLAY .= "</td></tr>\n";


//$THIS_DISPLAY .= "<tr><td colspan=\"8\" align=\"left\" valign=\"middle\" class=\"text\">\n";
//$THIS_DISPLAY .= "<FONT COLOR=\"#999999\">".lang("You should")." <a href=\"statistics/statistics_archive.php\">".lang("archive your log")."</a> ".lang("tables at least every six months or so depending on traffic.");
//$THIS_DISPLAY .= "&nbsp;".lang("If you experience slowness in loading reports, your log tables have probably gone unattended for some time.")."</FONT>\n";
//$THIS_DISPLAY .= "</td></tr>\n";
$THIS_DISPLAY .= "</table>\n";
$THIS_DISPLAY .= "</form>\n";


//$THIS_DISPLAY .= "I-Frame src=\"".$STAT_SHOW."\"<br>";
$THIS_DISPLAY .= "<iframe width=\"95%\"  border=\"0\" id=\"stats_frame\" style=\"height:70%;min-height:550px;border: 1px solid #ccc;\" src=\"".$STAT_SHOW."\" scroll=\"auto\" align=\"center\" valign=\"top\"></iframe>\n";

echo $THIS_DISPLAY;

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$module = new smt_module($module_html);
$module->meta_title = "Traffic Statistics";
$module->add_breadcrumb_link("Traffic Statistics", "program/modules/mods_full/statistics.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/site_statistics-enabled.gif";
$module->heading_text = "Site Traffic Statistics";
$module->description_text = "Review various reports on the browsing behaviour of your site visitors.";
$module->good_to_go();
?>
