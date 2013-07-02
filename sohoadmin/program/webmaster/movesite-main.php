<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
ini_set("max_execution_time", "120000");
ini_set("default_socket_timeout", "120000");
ini_set("max_post_size", "900M");
ini_set("max_input_time", "120000");
ini_set("memory_limit", "128M");
set_time_limit(0);
session_start();
include_once("../includes/product_gui.php");
# Pull webmaster userdata

ob_start();
$webmaster_pref = new userdata("webmaster_pref");

# Make sure default is set
if($webmaster_pref->get("mm_shortcuts") == "" ) {
	$webmaster_pref->set("mm_shortcuts", "on");
}

# Webmaster nav button row
include("webmaster_nav_buttons.inc.php");
$bakfiley =  $_SESSION['doc_root']."/transfer_backup_file.tgz";
$bakfileytgz =  $_SESSION['doc_root']."/site_transfer.tgz";
$backup_info_filey = $_SESSION['doc_root']."/bakupinfo.php";
	

if($_GET['delete']=='yes'){
	unlink($bakfiley);
	unlink($backup_info_filey);
	unlink($bakfileytgz);
}
	
if($_GET['dl']=='yes'){
	echo "<font color=green><H2>".lang("Transfer File Ready")."!!</H2></font>\n<br/><a href=\"movesite.php?dl=yes\" target=\"_blank\"><strong>".lang("CLICK HERE")."</strong></a> ".lang("to download the")." <font color=green><strong>".lang("transfer file")."</strong></font>. \n";
} elseif($_GET['error']=='yes'){
	if(isset($_SESSION['error_rep']['backup'])){
		echo "<H2><font color=red>".lang("Error creating backup file")."!</font></H2>".$_SESSION['error_rep']['backup'];
		unset($_SESSION['error_rep']);
	}	
	if(isset($_SESSION['error_rep']['transfer'])){
		echo "<H2><font color=red>".lang("Error transfering site")."!</font></H2>".$_SESSION['error_rep']['transfer'];
		unset($_SESSION['error_rep']);
	}
	
} elseif( file_exists($backup_info_filey) && (file_exists($bakfiley) || file_exists($bakfileytgz)) ){
	echo "<font color=green><H2>".lang("Transfer File Ready")."!!</H2></font>\n<br/><a style=\"color:green;\" href=\"movesite.php?dl=yes\" target=\"_blank\"><strong>".lang("CLICK HERE")."</strong></a> ".lang("to download the")." <font color=green><strong>".lang("transfer file")."</strong></font>. <br/><br/>\n";
	echo "<br/><br/>".lang("To delete the")." <font color=red><strong>".lang("transfer file")."</strong> <a href=\"movesite-main.php?delete=yes\" style=\"color:red;\"><strong>".lang("CLICK HERE")."</strong></a></font>. \n";
	
} else {
	unset($_SESSION['error_rep']);
	
	$tabledisplay = "<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n<body bgcolor=\"blue\">\n</head>\n";
	$tabledisplay .= "<script type=\"text/javascript\" src=\"../includes/display_elements/window/prototype.js\"></script>\n";
	$tabledisplay .= "<script type=\"text/javascript\" src=\"../includes/display_elements/window/window.js\"></script>\n";
	$tabledisplay .= "<script type=\"text/javascript\" src=\"../includes/display_elements/window/effects.js\"></script>\n";
	$tabledisplay .= "<link href=\"../includes/display_elements/window/default.css\" rel=\"stylesheet\" type=\"text/css\"></link>\n";
	$tabledisplay .= "<link href=\"../includes/display_elements/window/alert_lite.css\" rel=\"stylesheet\" type=\"text/css\"></link>\n";
	$tabledisplay .= "<script language=\"javascript\">\n";
	$tabledisplay .= "function openInfoDialogs(dmsg) {\n";
	$tabledisplay .= " Dialog.info(\"<br>\"+dmsg+\"...\", {windowParameters: {className: \"alert_lite\",width:350, height:100}, showProgress: true});\n";
	//$tabledisplay .= " Dialog.info(\"Backing up Website...\", {windowParameters: {className: \"alert_lite\",width:250, height:100}, showProgress: true});\n";
	$tabledisplay .= "}\n";
	$tabledisplay .= "</script>\n";
	$tabledisplay .= "<script language=\"javascript\">\n";
	$tabledisplay .= "   document.getElementById('modal_dialog_scroll').innerHTML += '".$title."<br/>'\n";
	$tabledisplay .= "   var cur_pos = document.getElementById('modal_dialog_scroll').scrollTop;\n";
	$tabledisplay .= "   var cur_pos = Number(cur_pos);\n";
	$tabledisplay .= "   var posi = (cur_pos+200);\n";
	$tabledisplay .= "   document.getElementById('modal_dialog_scroll').scrollTop= posi;\n";
	$tabledisplay .= "</script>\n";
	$tabledisplay .= "</html>\n";	
	echo $tabledisplay;	
	
	$transferform = "<table width=\"685\"  border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"feature_sub\" style=\"border-bottom: 1px solid #000000;\">\n";
	$transferform .= "	<tr>\n";
	$transferform .= "		<td><form name=\"getmoven\" action=\"movesite.php\">".lang("Move this website to a different server, domain, or sub-domain.")." <br/><br/>\n";
	//$transferform .= "		<a href=\"#\" onclick=\"openInfoDialog();\">click</a>\n";
	$transferform .= "		<span onclick=\"openInfoDialogs('Creating Website Transfer File'); document.getmoven.submit();\" class=\"button_image\" id=\"check_updates_btn_off\" onmouseover=\"this.id='check_updates_btn_on'\" onmouseout=\"this.id='check_updates_btn_off'\">\n";
	$transferform .= "		<span class=\"button_image_text\">".LANG("Move This Website")."</span>\n";
	$transferform .= "		<span style=\"display: none;\" id=\"check_updates_btn_on\">&nbsp;</span>\n";
	$transferform .= "		</span></form>\n";
	$transferform .= "		</td>\n";
	$transferform .= "	</tr>\n";
	
	$transferform .= "</table>\n";
	echo $transferform .= "\n<br/><br/>\n";
	include('movesite-transfer.php');
}

//echo $THIS_DISPLAY .= "</table>\n";
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Easily transfer this site to a different domain, sub-domain, or server, Or import a site to this domain.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("Webmaster");
$module->add_breadcrumb_link(lang("Webmaster"), "program/webmaster/webmaster.php");
$module->add_breadcrumb_link(lang("Site Transfer"), "program/webmaster/movesite-main.php");
$module->icon_img = "program/webmaster/movingtruck.gif";
$module->heading_text = lang("Site Transfer");
$module->description_text = $instructions;
$module->add_cssfile("webmaster_global_styles.css");
$module->good_to_go();



?>