<?php
header('Content-type: text/html; charset=UTF-8');
error_reporting(E_PARSE && E_ERROR);
ini_set("max_execution_time", "999");
ini_set("default_socket_timeout", "999");
ini_set("max_post_size", "200M");
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

#################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Author: 			Cameron Allen cameron.allen@soholaunch.com
## Homepage:	 	http://www.soholaunch.com
## This script is for Soholaunch Internal Use only.  Any other use is prohibited!
#################################################################################
#################################################################################
## COPYRIGHT NOTICE
## Copyright 2003-2010 Cameron Allen
## All Rights Reserved.
##
## This script may not be used or modified without permissions from the author, Cameron Allen.



//# Primary interface include
if (!require("../includes/product_gui.php") ) {
   exit;
} else {
	$helpmehelpyou = '1';
	if($_SESSION['newdir'] != '') {
		chdir($_SESSION['newdir']);
	} else {
		chdir($_SESSION['doc_root']);
	}	
}


if($_POST['dirfileshidden'] == 'on'){
	$_SESSION['dirfilesize'] = 'checked';
} elseif($_POST['dirfileshidden'] == 'off'){
	$_SESSION['dirfilesize'] = ' ';
}

if($_SESSION['dirfilesize'] == '' || $_SESSION['dirfilesize'] == ' '){
	$_SESSION['dirfilesize'] = ' ';
} else {
	$_SESSION['dirfilesize'] = 'checked';	
}

if($_POST['mysqlhidden'] == 'on'){
	$_SESSION['mysql_query'] = 'checked';
} elseif($_POST['mysqlhidden'] == 'off'){
	$_SESSION['mysql_query'] = ' ';
}

if($_SESSION['mysql_query'] == '' || $_SESSION['mysql_query'] == ' '){
	$_SESSION['mysql_query'] = ' ';
} else {
	$_SESSION['mysql_query'] = 'checked';	
}

if(eregi('WIN', PHP_OS)){
  $win = 'yes'; 
  $_SESSION['win'] = 'yes';
}

//$bgcolor = "#1F1D1D";
$bgcolor = "#000000";

$red = "#EF3B3B";
$simple_name = "simple.php";
if($helpmehelpyou == '1'){
	$simple_name = "helpmehelpyou.php";
}

$_SESSION['red'] = $red;

if($_GET['special'] == 'phpinfo'){
	$uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	$extra = $_SERVER['HTTP_HOST'].$uri;
	$thisdomain=eregi_replace("^www\.", "", $extra);
	$hostname = php_uname("n");
	$IP = $_SERVER['SERVER_ADDR'];
	$php_suexec = strtoupper(php_sapi_name());
	$OS = strtoupper(PHP_OS);
	$disabled = strtoupper(@ini_get("disable_functions")." ".@ini_get('suhosin.executor.func.blacklist'));

		$error = '';
	if(!isset($_SESSION['doc_root']) || $_SESSION['doc_root'] == ''){
		$error[] = "Session is not saving.  Check Permissions on the following folder: ".session_save_path()." and make sure that it is writable.";
		$_SESSION['doc_root'] = eregi_replace(basename($_SERVER["SCRIPT_FILENAME"]), '', $_SERVER["SCRIPT_FILENAME"]);
	}

	echo "<font color=\"blue\">Domain Name: </font><strong><font color=\"black\">".$thisdomain."</font></strong><br/>\n";
	echo "<font color=\"blue\">Host Name: </font><strong><font color=\"black\">".$hostname."</font></strong><br/>\n";
	echo "<font color=\"blue\">IP: </font><strong><font color=\"black\">".$IP."</font></strong><br/>\n";
	echo "<font color=\"blue\">Server API: </font><strong><font color=\"black\">".$php_suexec."</font></strong><br/>\n";
	echo "<font color=\"blue\">Opperating System: </font><strong><font color=\"black\">".$OS."</font></strong><br/>\n";
	echo "<font color=\"blue\">Doc Root: </font><strong><font color=\"black\">".$_SESSION['doc_root']."</font></strong><br/>\n";
	
	if($disabled != ''){
		echo "<font color=\"blue\">Disabled Functions: </font><strong><font color=\"black\">".$disabled."</font></strong><br/>\n";
	}
	
	function testIfWritable($path) {
		$testfile = $path."/test.txt";
		$file = fopen($testfile, "w");
		if(!fwrite($file, "test")) {
			return("The ".$path." directory is not writable.");
			return false;
		} else {
			unlink($testfile);
			return true;
		}
		fclose($file);
	}
	

	echo "<br/>\n"	;
	
	if (eregi('[^_]EXEC', $disabled)){
		$error[] = "exec is disabled.";
	}
	
	if (eregi('SHELL_EXEC', $disabled)){
		$error[] = "shell_exec is disabled.";
	}
	
	if (ini_get('safe_mode') == 1 || ini_get('safe_mode') == 'on'){
		$error[] = "safe_mode is enabled.";
	}

	if (ini_get('short_open_tag') == 0 || strtoupper(ini_get('short_open_tag')) == 'OFF'){
		$error[] = "short_open_tag is disabled.";
	}
	
	if (ini_get('register_long_arrays')){
		if (ini_get('register_long_arrays') == 0 || strtoupper(ini_get('register_long_arrays')) == 'OFF'){
			$error[] = "register_long_arrays is disabled.";
		}
	}
	$allowurlfopen = ini_get('allow_url_fopen');
	if ($allowurlfopen == 0 || strtoupper($allowurlfopen) == 'OFF'){
		$error[] = "allow_url_fopen is disabled.";
	}
	
	if (ini_get('asp_tags') == 1 || strtoupper(ini_get('asp_tags')) == 'ON'){
		$error[] = "asp_tags are enabled and need to be disabled.";
	}

	
	if(!function_exists('mysql_query')){
		$error[] = 'php is not compiled with mysql support';	
	}
	
	if($_SESSION['doc_root'] != ''){
	if(!$badpath = testIfWritable($_SESSION['doc_root'])){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/media')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/images')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/shopping')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/import')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/subscription')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/template')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/tCustom')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/sohoadmin/tmp_content')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/sohoadmin/config')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/sohoadmin/filebin')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/sohoadmin/program/webmaster/backups')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/sohoadmin/program/modules/site_templates/pages')){
		$error[] = $badpath;
	}
	
	if(!$badpath = testIfWritable($_SESSION['doc_root'].'/sohoadmin/plugins')){
		$error[] = $badpath;
	}
	
	$ispcon_path = $_SESSION['doc_root'].'/sohoadmin/config/isp.conf.php';
	$ispo = fopen($ispcon_path, "r+");
	if(!$ispr = fread($ispo, filesize($ispcon_path))){
		$error[] = "can't open ".$ispcon_path;
	} else {
		fclose($ispo);
		$lines = explode("\n", $ispr);

		foreach($lines as $lineval){
			if (!eregi("#", $lineval) && strlen($lineval) > 4) {
				$variable = strtok($lineval, "=");
				$value = strtok("\n");
				$value = rtrim($value);	
				${$variable} = $value;
			}			
		}

		$thecwd = getcwd();
		if(!is_dir('sohoadmin')){
			if(is_dir('../sohoadmin')){
				chdir('../');
			} elseif(is_dir($_SESSION['doc_root'].'/sohoadmin')){
				chdir($_SESSION['doc_root']);
			}
		}

		if($helpmehelpyou == 1){
			$curr_word_dir = str_replace('/sohoadmin/program/webmaster', '', getcwd());
		} else {
			$curr_word_dir = getcwd();
		}

		if($doc_root != $curr_word_dir){
			$error[] = "doc_root path is incorrect in isp.conf.php\n<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;doc_root&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;".$doc_root."\n<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;actual&nbsp;path&nbsp;&nbsp;=&nbsp;".$curr_word_dir;
		}
		
		if($cgi_bin != $curr_word_dir.'/sohoadmin/tmp_content'){
			$error[] = "cgi_bin path is incorrect in isp.conf.php\n<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;cgi_bin&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;".$cgi_bin."\n<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;actual&nbsp;path&nbsp;&nbsp;=&nbsp;".$curr_word_dir.'/sohoadmin/tmp_content';
		}
		
		if($lang_dir != $curr_word_dir.'/sohoadmin/language'){
			$error[] = "lang_dir path is incorrect in isp.conf.php\n<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;lang_dir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;".$lang_dir."\n<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;actual&nbsp;path&nbsp;&nbsp;=&nbsp;".$curr_word_dir.'/sohoadmin/language';
		}
		
		if($template_lib != $curr_word_dir.'/sohoadmin/program/modules/site_templates/pages'){
			$error[] = "template_lib path is incorrect in isp.conf.php\n<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;template_lib&nbsp;=&nbsp;".$template_lib."\n<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;actual&nbsp;path&nbsp;&nbsp;&nbsp;=&nbsp;".$curr_word_dir.'/sohoadmin/program/modules/site_templates/pages';
		}
		
		
	}
	
	
	}
	
	chdir($thecwd);
	if(is_array($error)){
		foreach($error as $errors){
			echo "<strong><font color=\"red\">ERROR: </font></strong><font color=\"black\">".$errors."</font><br/>\n";	
		}	
	}
	phpinfo();
	exit;
}

if($_SESSION['doc_root'] == ''){
   $_SESSION['doc_root'] = eregi_replace(basename($_SERVER["SCRIPT_FILENAME"]), '', $_SERVER["SCRIPT_FILENAME"]);
}

if(strlen($_POST['sort']) > 2){
	if($_POST['sort'] == 'name'){
		if(($_SESSION['simple_sort_order'] == 'name' && $_SESSION['simple_sort_method'] == 'desc') || $_SESSION['simple_sort_order'] != 'name'){
			$_SESSION['simple_sort_order'] = 'name';
			$_SESSION['simple_sort_method'] = 'asc';
		} else {
			$_SESSION['simple_sort_order'] = 'name';
			$_SESSION['simple_sort_method'] = 'desc';
		}
	}
	
	if($_POST['sort'] == 'size'){
		if(($_SESSION['simple_sort_order'] == 'size' && $_SESSION['simple_sort_method'] == 'desc') || $_SESSION['simple_sort_order'] != 'size'){
			$_SESSION['simple_sort_order'] = 'size';
			$_SESSION['simple_sort_method'] = 'asc';
		} else {
			$_SESSION['simple_sort_order'] = 'size';
			$_SESSION['simple_sort_method'] = 'desc';
		}
	}
	
	if($_POST['sort'] == 'type'){
		if(($_SESSION['simple_sort_order'] == 'type' && $_SESSION['simple_sort_method'] == 'desc') || $_SESSION['simple_sort_order'] != 'type'){
			$_SESSION['simple_sort_order'] = 'type';
			$_SESSION['simple_sort_method'] = 'asc';
		} else {
			$_SESSION['simple_sort_order'] = 'type';
			$_SESSION['simple_sort_method'] = 'desc';
		}
	}
	
	if($_POST['sort'] == 'modified'){
		if(($_SESSION['simple_sort_order'] == 'modified' && $_SESSION['simple_sort_method'] == 'desc') || $_SESSION['simple_sort_order'] != 'modified'){
			$_SESSION['simple_sort_order'] = 'modified';
			$_SESSION['simple_sort_method'] = 'asc';
		} else {
			$_SESSION['simple_sort_order'] = 'modified';
			$_SESSION['simple_sort_method'] = 'desc';
		}
	}
	
}

if(!isset($_SESSION['simple_sort_order'])){
	$_SESSION['simple_sort_order'] = 'name';
	$_SESSION['simple_sort_method'] = 'asc';
}

if($_POST['cmd']=='CLEAR_HISTORY'){
	unset($_SESSION['output_history']);
	unset($_POST['cmd']);
}

$css = "<html> \n";
$css .= "<head> \n";
$disp_url = $_SESSION['this_ip'];
if($disp_url == ''){ $disp_url = $_SERVER['HTTP_HOST']; }
if($helpmehelpyou == '1'){
	$css .= "<title>HelpMeHelpYou -".$disp_url."-</title> \n";
} else {
	$css .= "<title>SiMPLE -".$disp_url."-</title> \n";
}
$css .= "<link rel=\"icon\" type=\"image/x-icon\" href=\"http://securexfer.net/camerons_simple/kill.ico\"> \n";
$css .= "<style type=\"text/css\">\n";

$css .= " span.filesearch{\n";
$css .= "/*The URI pointing to the location of the image*/\n";
$css .= "	color: orange;\n";
$css .= "}\n";

$css .= " span.filesearch:hover{\n";
$css .= "/*The URI pointing to the location of the image*/\n";
$css .= "	color: red!important;\n";
$css .= "}\n";




$css .= "span:visited{\n";
$css .= "/*The URI pointing to the location of the image*/\n";
$css .= "	color: red;\n";
$css .= "}\n";

$css .= ".filelist {\n";
$css .= " padding:0px 2px 0px 2px;\n";
$css .= " font-size: 8pt;\n";
$css .= "}\n\n";
$css .= "body{\n";
$css .= $bgcolor." url('http://securexfer.net/camerons_simple/Mitch-simple.jpg') no-repeat fixed bottom right;\n";
$css .= "}\n";

$css .= "#div2 { \n";
$css .= "background: #808080 url('http://securexfer.net/camerons_simple/simple-tile.gif') repeat; \n";
$css .= "filter:alpha(opacity=15); \n";
$css .= "-moz-opacity:.15; \n";
$css .= "opacity:.15; \n";
$css .= "}\n";

$css .= ".nav_main, .nav_mainon, .nav_mainmenu, .nav_mainmenuon, .nav_save, .nav_saveon, .nav_soho, .nav_sohoon, .nav_logout, .nav_logouton { \n";
$css .= "	color: #FFFFFF; \n";
$css .= "	font-family: verdana, arial, helvetica, sans-serif; \n";
$css .= "	font-size: 10px; \n";
$css .= "	cursor: pointer; \n";
$css .= "} \n";

$css .= ".nav_main, .nav_mainon, .nav_mainmenu, .nav_mainmenuon { \n";
//$css .= "   background-color: #144B81; \n";
$css .= "   background-color: #10D91A; \n";

$css .= "	border: 1px solid #595959; \n";
$css .= "} \n";

$css .= ".nav_main { background-image: url(http://securexfer.net/camerons_simple/btn-nav_save-off.jpg); } \n";
$css .= ".nav_mainon { background-image: url(http://securexfer.net/camerons_simple/btn-nav_save-on.jpg); } \n";


$css .= ".nav_main1 {\n";
$css .= "	background-image: url(http://securexfer.net/camerons_simple/btn-nav_main-off.jpg);\n";
$css .= "-moz-border-radius:25px; \n";

$css .= " } \n";


$css .= ".nav_mainon1 { background-image: url(http://securexfer.net/camerons_simple/btn-nav_main-on.jpg); 	cursor: pointer; } \n";

$css .= ".nav_main2 { background-image: url(http://securexfer.net/camerons_simple/btn-nav_warn-off.jpg); cursor: pointer; } \n";
$css .= ".nav_mainon2 { background-image: url(http://securexfer.net/camerons_simple/btn-nav_warn-on.jpg); cursor: pointer; } \n";

 


$css .= ".nav_mainmenu { \n";
$css .= "	font-weight: bold; \n";
$css .= "} \n";

$css .= ".nav_mainmenuon { \n";
$css .= "	background-color: #3283D3; \n";
$css .= "	font-weight: bold; \n";
$css .= "} \n";


$css .= ".nav_save, .nav_saveon { \n";
$css .= "	background-color: #087D34; \n";
$css .= "	border: 2px solid #66CC70; \n";
$css .= "} \n";

$css .= ".nav_saveon { \n";
$css .= "	background-color: #149845; \n";
$css .= "} \n";

$css .= ".nav_soho, .nav_sohoon { \n";
$css .= "	background-color: #815714; \n";
$css .= "	border: 2px solid #CC9B66; \n";
$css .= "} \n";

$css .= ".nav_sohoon { \n";
$css .= "	background-color: #FF6600; \n";
$css .= "} \n";

$css .= ".nav_logout { \n";
$css .= "	border: 1px solid #595959; \n";
$css .= "	background-image: url(http://securexfer.net/camerons_simple/btn-nav_logout-off.jpg); \n";
$css .= "} \n";

$css .= ".nav_logouton { \n";
$css .= "	border: 1px solid #595959; \n";
$css .= "	background-image: url(http://securexfer.net/camerons_simple/btn-nav_logout-on.jpg); \n";
$css .= "} \n";

$css .= ".btn_edit, .btn_editon, .btn_save, .btn_saveon, .btn_delete, .btn_deleteon, .btn_build, .btn_buildon, .btn_risk, .btn_riskon { \n";
$css .= "	background-color: #C3DEFF; \n";
$css .= "	font-family: tahoma, verdana, arial, helvetica, sans-serif; \n";
$css .= "	color: #000000; \n";
$css .= "	font-size: 8pt; \n";
$css .= "	cursor: pointer; \n";
$css .= "	border: 2px solid #6699CC; \n";
$css .= "	border-right: 2px solid #336699; \n";
$css .= "	border-bottom: 2px solid #336699; \n";
$css .= "   border-left: 2px solid #6699CC; \n";
$css .= "} \n";

$css .= ".btn_editon { \n";
$css .= "	background-color: #C3EDFF; \n";
$css .= "} \n";

$css .= ".btn_save, .btn_saveon { \n";
$css .= "	background-color: #14B21C; \n";
$css .= "	color: #ffffff; \n";
$css .= "	border-top: 2px solid #158B1A; \n";
$css .= "	border-right: 2px solid #166D1A; \n";
$css .= "	border-bottom: 2px solid #166D1A; \n";
$css .= "   border-left: 2px solid #158B1A; \n";
$css .= "} \n";

$css .= ".btn_saveon { \n";
$css .= "	background-color: #10D91A; \n";
$css .= "} \n";

$css .= ".btn_delete, .btn_deleteon { \n";
$css .= "	background-color: #E31A1A; \n";
$css .= "	color: #FFFFFF; \n";
$css .= "	border-top: 2px solid #B81B1B; \n";
$css .= "	border-right: 2px solid #680808; \n";
$css .= "	border-bottom: 2px solid #680808; \n";
$css .= "   border-left: 2px solid #B81B1B; \n";
$css .= "} \n";

$css .= ".btn_deleteon { \n";
$css .= "	background-color: #FF0000; \n";
$css .= "} \n";

$css .= ".btn_risk, .btn_riskon { \n";
$css .= "	background-color: #F75D00; \n";
$css .= "	color: #FFFFFF; \n";
$css .= "	border-top: 2px solid #B81B1B; \n";
$css .= "	border-right: 2px solid #680808; \n";
$css .= "	border-bottom: 2px solid #680808; \n";
$css .= "   border-left: 2px solid #B81B1B; \n";
$css .= "} \n";

$css .= ".btn_riskon { \n";
$css .= "	background-color: #FE7613; \n";
$css .= "} \n";

$css .= ".btn_build, .btn_buildon { \n";
$css .= "	background-color: #BDEED1; \n";
$css .= "	color: #000000; \n";
$css .= "	border-top: 2px solid #66CCA2; \n";
$css .= "	border-right: 2px solid #33996D; \n";
$css .= "	border-bottom: 2px solid #33996D; \n";
$css .= "   border-left: 2px solid #66CCA2; \n";
$css .= "} \n";

$css .= ".btn_buildon { \n";
$css .= "	background-color: #B1FAD0; \n";
$css .= "} \n";

$css .= ".btn_blue, .btn_green, .btn_red, .btn_#FF2F37 { \n";
$css .= "	background-color: #C3DEFF; \n";
$css .= "	font-family: tahoma, verdana, arial, helvetica, sans-serif; \n";
$css .= "	color: #FFF; \n";
$css .= "	font-size: 8pt; \n";
$css .= "	cursor: hand; \n";
$css .= "} \n";

$css .= ".btn_blue { \n";
$css .= "	background-color: #336699; \n";
$css .= "	color: #FFFFFF; \n";
$css .= "	font-size: 8pt; \n";
$css .= "	cursor: hand; \n";
$css .= "	border: 2px outset #6699CC; \n";
$css .= "} \n";

$css .= ".btn_green { \n";
$css .= "	background-color: #087D34; \n";
$css .= "	color: #FFFFFF; \n";
$css .= "	font-size: 8pt; \n";
$css .= "	cursor: hand; \n";
$css .= "	border: 2px outset #66CC91; \n";
$css .= "} \n";

$css .= ".btn_red { \n";
$css .= "	background-color: #6E0000; \n";
$css .= "	color: #FFFFFF; \n";
$css .= "	font-size: 8pt; \n";
$css .= "	cursor: hand; \n";
$css .= "	border: 2px outset #9B0000; \n";
$css .= "} \n";

$css .= ".btn_#FF2F37 { \n";
$css .= "	background-color: #D75B00; \n";
$css .= "	color: #FFFFFF; \n";
$css .= "	font-size: 8pt; \n";
$css .= "	cursor: hand; \n";
$css .= "	border: 2px outset #9B5800; \n";
$css .= "} \n";



$css .= "div.upload_div2 { \n";
$css .= "	position: relative; \n";
$css .= "} \n";

$css .= "div.fakefile { \n";
$css .= "	position: absolute; \n";
$css .= "	top: 0px; \n";
$css .= "	left: 0px; \n";
$css .= "	z-index: 1; \n";
$css .= "} \n";

$css .= "input.file { \n";
$css .= "	position: relative; \n";
$css .= "	text-align: right; \n";
$css .= "	-moz-opacity:0 ; \n";
$css .= "	filter:alpha(opacity: 0); \n";
$css .= "	opacity: 0; \n";
$css .= "	z-index: 2; \n";
$css .= "	font-size: 2; \n";
$css .= "} \n";

$css .= "form.upload_shit input:focus {\n";
$css .= "	background-color: transparent;\n";
$css .= "}\n";

$css .= ".skin0{ \n";
$css .= "position:absolute; \n";
$css .= "width:180px; \n";
$css .= "border:2px solid ".$bgcolor."; \n";
$css .= "background-color:menu; \n";
$css .= "font-family:Verdana; \n";
$css .= "line-height:20px; \n";
$css .= "cursor:default; \n";
$css .= "font-size:12px; \n";
$css .= "z-index:4000; \n";
$css .= "visibility:hidden; \n";
$css .= "} \n";
$css .= ".menuitems{ \n";
$css .= "padding-left:10px; \n";
$css .= "padding-right:10px; \n";
$css .= "} \n";

$css .= "a.dropdown{ \n";
$css .= "	color: yellow; \n";
$css .= "} \n";
$css .= "a.dropdown:hover{ \n";
$css .= "	color: orange; \n";
$css .= "} \n";



echo $css .= "</style>\n";

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://securexfer.net/camerons_simple/niftyCorners.css\">\n";
echo "<script type=\"text/javascript\" src=\"http://securexfer.net/camerons_simple/nifty.js\"></script>\n";

if(!function_exists("table_exists")){
	function table_exists($tablename) {
		$db_name = $_SESSION['db_name'];		
	   # Select all db tables
	   $result = mysql_list_tables($db_name);	
	   # Loop through table names and listen for match
	   for ( $i = 0; $i < mysql_num_rows($result); $i++ ) {
	      if ( mysql_tablename($result, $i) == $tablename ) {
	         return true;
	      }
	   }
	   return false;
	}
}


if (!function_exists(mb_list_encodings)) {
	function mb_list_encodings(){
		$list_encoding = array("pass", "auto", "wchar", "byte2be", "byte2le", "byte4be", "byte4le", "BASE64", "UUENCODE", "HTML-ENTITIES", "Quoted-Printable", "7bit", "8bit", "UCS-4", "UCS-4BE", "UCS-4LE", "UCS-2", "UCS-2BE", "UCS-2LE", "UTF-32", "UTF-32BE", "UTF-32LE", "UTF-16", "UTF-16BE", "UTF-16LE", "UTF-8", "UTF-7", "UTF7-IMAP", "ASCII", "EUC-JP", "SJIS", "eucJP-win", "SJIS-win", "JIS", "ISO-2022-JP", "Windows-1252", "ISO-8859-1", "ISO-8859-2", "ISO-8859-3", "ISO-8859-4", "ISO-8859-5", "ISO-8859-6", "ISO-8859-7", "ISO-8859-8", "ISO-8859-9", "ISO-8859-10", "ISO-8859-13", "ISO-8859-14", "ISO-8859-15", "EUC-CN", "CP936", "HZ", "EUC-TW", "BIG-5", "EUC-KR", "UHC", "ISO-2022-KR", "Windows-1251", "CP866", "KOI8-R");
		return $list_encoding;
	}
}

if(!function_exists("is_utf8")){	
	function is_utf8($str) {
	    $c=0; $b=0;
	    $bits=0;
	    $len=strlen($str);
	    for($i=0; $i<$len; $i++){
	        $c=ord($str[$i]);
	        if($c > 128){
	            if(($c >= 254)) return false;
	            elseif($c >= 252) $bits=6;
	            elseif($c >= 248) $bits=5;
	            elseif($c >= 240) $bits=4;
	            elseif($c >= 224) $bits=3;
	            elseif($c >= 192) $bits=2;
	            else return false;
	            if(($i+$bits) > $len) return false;
	            while($bits > 1){
	                $i++;
	                $b=ord($str[$i]);
	                if($b < 128 || $b > 191) return false;
	                $bits--;
	            }
	        }
	    }
	    return true;
	}
}


if(function_exists("mb_detect_encoding")){

	$encodings = mb_list_encodings();
	if(!function_exists("fixEncoding")){
		function fixEncoding($in_str){
			$encodings = mb_list_encodings();
			$cur_encoding = mb_detect_encoding($in_str, $encodings);
			if(strtoupper($cur_encoding) == "UTF-8" && mb_check_encoding($in_str,"UTF-8")){
				return $in_str;
			} else {
				return utf8_encode($in_str);
			} // fixEncoding 
		}
	}
	
} else {
	
	$encodings = mb_list_encodings();
	if(!function_exists("fixEncoding")){
		function fixEncoding($in_str){
	//		$encodings = mb_list_encodings();
	//		$cur_encoding = mb_detect_encoding($in_str, $encodings);
	//		if(strtoupper($cur_encoding) == "UTF-8" && mb_check_encoding($in_str,"UTF-8")){
			if(is_utf8($in_str)){
				return $in_str;
			} else {
				return utf8_encode($in_str);
			} // fixEncoding 
		}
	}
	
}

class file_downloads {
   var $remote = array(); // Remote file data
   var $local = array(); // Local file data
   var $msg; // Specific success/failure message

   // Break full path into element arrays
   //================================================
   function file_downloads($rempath, $locpath, $donow = "rock") {
      $this->remote['path'] = $rempath;
      $this->remote['dir'] = dirname($rempath);
      $this->remote['file'] = basename($rempath);
      $this->local['path'] = $locpath;
      $this->local['dir'] = dirname($locpath);
      $this->local['file'] = dirname($locpath);
   }

   function getit() {
      if ( !$fp1 = fopen($this->remote['path'],"r") ) {
         $this->msg = "Unable to open remote update file.  Check your server's firewall settings.";
         return false;
      }

      // create local file
      if ( !$fp2 = fopen($this->local['path'],"w") ) {
         $this->msg = "Unable to write files to server.  \n";
         $this->msg .= "Check the permissions on the <strong>[".$this->local['dir']."]</strong> folder.  The permissions should be set to 777 for installation.";
         return false;
      }

      // read remote and write to local
      while (!feof($fp1)) {
           $output = fread($fp1,1024);
           fputs($fp2,$output);
      }

      fclose($fp1);
      fclose($fp2);
      $this->msg = "Remote file downloaded successfully.";
      return true;
   }
}
	
function mysqlPrint ( $array, $keyz, $exclude_numeric_keys = "yes" ) {
   $arrTable = "";

   # Header row for key names, data row for values
   $row1 = " <tr>\n";
   $row2 = " <tr>\n";

   # Loop through array
   foreach ( $array as $var=>$val ) {
      # Exclude numeric keys? (like from mysql_fetch_array)
      if ( ($exclude_numeric_keys == "yes" && !is_numeric($var)) || $exclude_numeric_keys != "yes" ) {
         # Prevent empty table cells
         if ( $val == "" ) { $val = "&nbsp;"; }
         # Format long strings into scrollable div boxes
         if ( strlen($val) > 40 ) { $val = "<div style=\"width: 100px; height: 60px; overflow: scroll; color: red;\">".$val."</div>\n"; }
         # Add column to header row
         $row1 .= "  <td style=\"background-color: #CCC;\" align=\"left\"><b>".$var."</b></td>\n";
         # Try to bust out sub-arrays
         if ( is_array($val) ) {
            $showVal = "";
            foreach ( $val as $vKey=>$vVal ) {
               $showVal .= "<span style=\"color: #2E2E2E;\">".$vKey."</span> = <span style=\"color: #F75D00;\">".$vVal."</span><br>";
            }
         } else {
            $showVal = $val;
         }
         # Add column to data row
         $bg = 'white';
         $row2 .= "  <td style=\"background-color:".$bg.";\"><span style=\"color: red;\">".$showVal."</span></td>\n";
      } // End if not numeric key or if numerics allowed
   }
   # Close header & data rows
   $row1 .= " </tr>\n";
   $row2 .= " </tr>\n";
   # Add rows to table html
	if($keyz=='key'){
		$arrTable .= $row1;
	}
   $arrTable .= $row2;
   //$arrTable .= "</table>";

   return $arrTable;
}


if(!function_exists("include_r")){
	if(!function_exists("testArray")){
		function testArray($array, $fixedheight = false) {
		   $arrTable = "";
		 //  $arrTable .= "<b>testArray output...</b><br>\n";
		   if ( $fixedheight ) {
		      $containerstyle = "height: ".$fixedheight."px;overflow: auto;";
		   }
		   $arrTable .= "<div style=\"".$containerstyle."\">\n";
		   $arrTable .= "<table class=\"content\" border=\"0\" cellspacing=\"0\" cellpadding=\"8\" style=\"font: 10px verdana; border: 1px solid #000;\">\n";
		
		   # Loop through array
		   foreach ( $array as $var=>$val ) {
		
		      # Alternate background colors
		      if ( $bg == "#FFFFFF" ) { $bg = "#EFEFEF"; } else { $bg = "#FFFFFF"; }
		
		      # Prevent empty table cells
		      if ( $val == "" ) { $val = "&nbsp;"; }
		
		      # Format long strings into scrollable div boxes
		      if ( strlen($val) > 40 ) {
		         $val = "<div style=\"width: 400px; height: 60px; overflow: scroll; color: red;\">".$val."</div>\n";
		      }
		
		      # Try to bust out sub-arrays
		      if ( is_array($val) ) {
		         $showVal = "";
		
		         foreach ( $val as $vKey=>$vVal ) {
		            $showVal .= "<span style=\"color: #2E2E2E;\">".$vKey."</span> = <span style=\"color: #F75D00;\">".$vVal."</span><br>";
		         }
		         $val = $showVal;
		      }
		
		      # Spit out table row
		      $arrTable .= " <tr>\n";
		      $arrTable .= "  <td style=\"vertical-align: top;background-color:".$bg.";\" align=\"left\"><b>".$var."</b></td>\n";
		      $arrTable .= "  <td style=\"background-color:".$bg.";\"><span style=\"color: red;\">".$val."</span></td>\n";
		      $arrTable .= " </tr>\n";
		   }
		   $arrTable .= "</table>";
		   $arrTable .= "</div>\n";
		
		   return $arrTable;
		}
	}
		
	function include_r($url) {
		$req = $url;
	   $pos = strpos($req, '://');
	   $protocol = strtolower(substr($req, 0, $pos));
	   $req = substr($req, $pos+3);
	   $pos = strpos($req, '/');
	
	   if($pos === false) {
	      $pos = strlen($req);
	   }
	
	   $host = substr($req, 0, $pos);
	
	   if(strpos($host, ':') !== false) {
	      list($host, $port) = explode(':', $host);
	   } else {
	      $host = $host;
	      $port = ($protocol == 'https') ? 443 : 80;
	   }
	
	   $uri = substr($req, $pos);
	   if($uri == '') {
	      $uri = '/';
	   }
	
	   $crlf = "\r\n";
	   // generate request
	   $req = 'GET ' . $uri . ' HTTP/1.0' . $crlf
	      .    'Host: ' . $host . $crlf
	      .    $crlf;
	
	   // fetch
	   $fp = fsockopen(($protocol == 'https' ? 'ssl://' : '') . $host, $port);
	   fwrite($fp, $req);
	   while(is_resource($fp) && $fp && !feof($fp)) {
	      $response .= fread($fp, 1024);
	   }
	   fclose($fp);
	
	   // split header and body
	   $pos = strpos($response, $crlf . $crlf);
	   if($pos === false) {
	      return($response);
	   }
	   $header = substr($response, 0, $pos);
	   $body = substr($response, $pos + 2 * strlen($crlf));
	
	    // parse headers
	   $headers = array();
	   $lines = explode($crlf, $header);
	   foreach($lines as $line) {
	      if(($pos = strpos($line, ':')) !== false) {
	         $headers[strtolower(trim(substr($line, 0, $pos)))] = trim(substr($line, $pos+1));
	      }
	   }
	    // redirection?
	   if(isset($headers['location'])) {
	   	echo include_r($headers['location']);
	      return(include_r($headers['location']));
	   } else {
	      echo $body;
	      return($body);
	   }
	}	// End include_r function

	
	class userdata {
	   # Plugin (folder) name. Must be set so functions know whose data to manipulate
	   var $plugin;
	   # Called first -- the other methods depend on this being set
	   function userdata($plugin) {
	      $this->plugin = $plugin;
	   }	
	   # Updates value of specific field (or inserts as new rec if fieldname not found)
	   # Example call: set("firstname", "billy")
	   function set($fieldname, $data) {
	      $qry = "SELECT * FROM smt_userdata WHERE plugin='".$this->plugin."' AND fieldname = '".$fieldname."'";
	      $rez = mysql_query($qry);	
	      # Insert new or update existing?
	      if ( mysql_num_rows($rez) < 1 ) {	
	         $qry = "INSERT INTO smt_userdata VALUES('', '".$this->plugin."', '".$fieldname."', '".$data."')";
	         mysql_query($qry);
	         //echo mysql_error(); exit;	
	      } else {
	         $qry = "UPDATE smt_userdata SET data = '".$data."' WHERE plugin='".$this->plugin."' AND fieldname = '".$fieldname."'";
	         mysql_query($qry);
	      }
	   }
	   
	   function get($fieldname = "") {
	      # Return value of all fields or just a specific one?
	      if ( $fieldname == "" ) {
	         # Return all field data for this plugin
	         $userdata = array();
	         $qry = "SELECT * FROM smt_userdata WHERE plugin='".$this->plugin."'";
	         $rez = mysql_query($qry);
	         while ( $getData = mysql_fetch_array($rez) ) {
	            $userdata[$getData['fieldname']] = $getData['data'];
	         }
	      } else {
	         # Return value of specific fieldname
	         $qry = "SELECT data FROM smt_userdata WHERE plugin='".$this->plugin."' and fieldname='".$fieldname."'";
	         $rez = mysql_query($qry);
	         $userdata = mysql_result($rez, 0);
	      }
	      return $userdata;
	   }
	   # Delete all data associated with this plugin
	   function delete() {
	      $qry = "DELETE FROM smt_userdata WHERE plugin='".$this->plugin."'";
	      mysql_query($qry);
	   }
	} // End userData class
}

if(!function_exists("dirsize")){
	function dirsize($dirname) {
	    if (!is_dir($dirname) || !is_readable($dirname)) {
	        return false;
	    }
	
	    $dirname_stack[] = $dirname;
	    $size = 0;
	
	    do {
	        $dirname = array_shift($dirname_stack);
	        $handle = opendir($dirname);
	        while (false !== ($file = readdir($handle))) {
	            if ($file != '.' && $file != '..' && is_readable($dirname . DIRECTORY_SEPARATOR . $file)) {
	                if (is_dir($dirname . DIRECTORY_SEPARATOR . $file)) {
	                    $dirname_stack[] = $dirname . DIRECTORY_SEPARATOR . $file;
	                }
	                $size += filesize($dirname . DIRECTORY_SEPARATOR . $file);
	            }
	        }
	        closedir($handle);
	    } while (count($dirname_stack) > 0);
	
	    return $size;
	}
}


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
////////////////////////////////////////////////////////////

class dataItem
{
	var $name;
	var $x;
	var $filetype;
	var $modified;
    //Constructor
    function dataItem($name,$x,$y,$filetype,$owner,$group,$modified)
    {
        $this->name = $name;
        $this->x = $x;
        $this->y = $y;
        $this->filetype = $filetype;
        $this->owner = $owner;
        $this->group = $group;
        $this->modified = $modified;
    }
}

class collection
{
    var $dataSet = array();
   
    //Creates a new data item and adds it to our array
    function add($name,$x,$y,$filetype,$owner,$group,$modified)
    {
        $this->dataSet[] = new dataItem($name,$x,$y,$filetype,$owner,$group,$modified);
    }
   
    //The wrapper sort function
    function sortDataSet($s)
    {
        //Sort by the given parameter
        switch($s)
        {
            case "name":
                //Note use of array to reference member method of this object in callback
                uasort($this->dataSet,array($this,"cmpName"));
                break;
           
            case "x":
                uasort($this->dataSet,array($this,"cmpX"));
                break;
               
            case "y":
                uasort($this->dataSet,array($this,"cmpY"));
                break;               

            case "filetype":                                
                uasort($this->dataSet,array($this,"cmpFiletype"));
                break;
               
            case "group":
                uasort($this->dataSet,array($this,"cmpGroup"));
                break;      
               
            case "modified":
                uasort($this->dataSet,array($this,"cmpModified"));
                break;               
           
            case "added":
            default:
                //Re-sort array by original keys
                ksort($this->dataSet);       
        }
    }

    //Callback function for sorting by name
    //$a and $b are dataItem objects
    function cmpName($a,$b)
    {
        //Use sort() for simple alphabetical comparison
        //Convert to lowercase to ensure consistent behaviour
        $sortable = array(strtolower($a->name),strtolower($b->name));
        $sorted = $sortable;
        sort($sorted);   
       
        //If the names have switched position, return -1. Otherwise, return 1.
        return ($sorted[0] == $sortable[0]) ? -1 : 1;
    }
 
    function cmpFiletype($a,$b)
    {
 
        
        $sortable = array(strtolower($a->filetype),strtolower($b->filetype));
        $sorted = $sortable;
        sort($sorted);   
        //If the names have switched position, return -1. Otherwise, return 1.
        return ($sorted[0] == $sortable[0]) ? -1 : 1;
    }
   
    //Callback function for sorting by x
    //$a and $b are dataItem objects
    function cmpX($a,$b)
    {
        //Use sort() for simple alphabetical comparison
        //Convert to lowercase to ensure consistent behaviour
        $sortable = array(strtolower($a->x),strtolower($b->x));
        $sorted = $sortable;
        sort($sorted);   
       
        //If the names have switched position, return -1. Otherwise, return 1.
        return ($sorted[0] == $sortable[0]) ? -1 : 1;
    }
   
    //Callback function for sorting by y
    //$a and $b are dataItem objects
    function cmpY($a,$b)
    {       
        //If $a's y attribute >= $b's y attribute, return 1. Otherwise, return -1.
        return ($a->y >= $b->y) ? 1 : -1;
    }   
    
    function cmpModified($a,$b)
    {       
		return ($a->modified >= $b->modified) ? 1 : -1;
    }   
}

	
function TurnToArray($inputObject){
	foreach($inputObject as $aa=>$bb){
		$name = $bb->name;
		$permissions = $bb->x;
		$filesize = $bb->y;
		
					if(strlen($filesize) > 9){
						$filesize = sprintf("%01.1f", ($filesize / 1000000000));
						$filesize .= "&nbsp;<font color=\"#2FB5FF\">GB</font>";
					} elseif(strlen($filesize) > 6){
						$filesize = sprintf("%01.1f", ($filesize / 1000000));
						$filesize .= "&nbsp;<font color=\"#D54FFF\">MB</font>";
					} elseif(strlen($filesize) > 3){
						$filesize = sprintf("%01.1f", ($filesize / 1000));
						$filesize .= "&nbsp;KB";
					} else {
						$filesize = sprintf("%01.1f", $filesize);
						$filesize .= "&nbsp;Bytes";
					}
		$filetype = $bb->filetype;
		$owner = $bb->owner;
		$group = $bb->group;
		$modified = $bb->modified;
		$files[$name] = array($permissions, $filesize, $filetype, $owner, $group, $modified);
	}

	return $files;
}
//Create a collection object


///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
///////////////////////////////////////////////////////

function sortls() {
$red = $_SESSION['red'];
ob_start();
echo phpinfo();
$php_info = ob_get_contents();
ob_end_clean();


if(eregi('WIN', PHP_OS)){
	$win = 'yes';	
}
$pathtosearch = getcwd();

//		if($win!='yes'){
//			$globstuff = array_merge(glob($pathtosearch.DIRECTORY_SEPARATOR.'*'),glob($pathtosearch.DIRECTORY_SEPARATOR.'.*'));
//		} else {
//			$globstuff = glob($pathtosearch.DIRECTORY_SEPARATOR.'*');
//		}
$globstuff = array_merge(glob($pathtosearch.DIRECTORY_SEPARATOR.'*'),glob($pathtosearch.DIRECTORY_SEPARATOR.'.*'));

foreach ($globstuff as $filename) {
//foreach (glob("{".$pathtosearch.DIRECTORY_SEPARATOR."*,".$pathtosearch.DIRECTORY_SEPARATOR.".*}", GLOB_BRACE) as $filename) {

//foreach (glob($pathtosearch."/*", GLOB_BRACE) as $filename) {
	if(!is_file($filename)){
		if(filetype($filename) != 'link'){
			$lastmodified = filemtime($filename);
			if($filename != $pathtosearch.'/..' && $filename != $pathtosearch.'/.'){
				$basefile = basename($filename);
				$fileperms = substr(sprintf('%o', fileperms($filename)), -3);
				if(eregi('--disable-posix', $php_info) || !function_exists("posix_getpwuid")){	
					$fileowner = '';
					$filegroup = '';
				} else {
					$fileowner = posix_getpwuid(fileowner($filename));
					$fileowner = $fileowner['name'];
					$filegroup = posix_getgrgid(filegroup($filename));
					$filegroup = $filegroup['name'];
				}
				
				if($_SESSION['dirfilesize'] == ' '){
					$directory_size = '';
				} else {
					$directory_size = dirsize($filename);
				
					if(strlen($directory_size) > 9){
						$directory_size = sprintf("%01.1f", ($directory_size / 1000000000));
						$directory_size .= "&nbsp;<font color=\"#2FB5FF\">GB</font>";
					} elseif(strlen($directory_size) > 6){
						$directory_size = sprintf("%01.1f", ($directory_size / 1000000));
						$directory_size .= "&nbsp;<font color=\"#D54FFF\">MB</font>";
					} elseif(strlen($directory_size) > 3){
						$directory_size = sprintf("%01.1f", ($directory_size / 1000));
						$directory_size .= "&nbsp;KB";
					} else {
						$directory_size = sprintf("%01.1f", $directory_size);
						$directory_size .= "&nbsp;Bytes";
					}
				}
				$dir_arr[$basefile] = array($fileperms, $fileowner, $filegroup, $directory_size, $lastmodified);
			}
		}
	} else {
		if(filetype($filename) != 'link'){
			$lastmodified = filemtime($filename);
			$basefile = basename($filename);
			$fileperms = substr(sprintf('%o', fileperms($filename)), -3);
			$filesize = filesize($filename);
			if(eregi('--disable-posix', $php_info) || !function_exists("posix_getpwuid")){	
				$fileowner = '';
				$filegroup = '';
			} else {
				$fileowner = posix_getpwuid(fileowner($filename));
				$fileowner = $fileowner['name'];
				$filegroup = posix_getgrgid(filegroup($filename));
				$filegroup = $filegroup['name'];
			}
			if(eregi('\.', $basefile)){
				$filetype = strtoupper(eregi_replace('^.*\.', '', $basefile));					
			} else {
				$filetype = '';
			}
			$file_arr[$basefile] = array($fileperms, $filesize, $fileowner, $filegroup, $filetype, $lastmodified);
		}
	}
}

$filename = $pathtosearch.'/.htaccess';
if(file_exists($filename )){
   $basefile = basename($filename);
   $fileperms = substr(sprintf('%o', fileperms($filename)), -3);
   $filesize = filesize($filename);

	if(!eregi('--disable-posix', $php_info) && function_exists("posix_getpwuid")){
		$fileowner = posix_getpwuid(fileowner($filename));
		$fileowner = $fileowner['name'];
		$filegroup = posix_getgrgid(filegroup($filename));
		$filegroup = $filegroup['name'];
		$filetype = strtoupper(eregi_replace('^.*\.', '', $basefile));
		$file_arr[$basefile] = array($fileperms, $filesize, $fileowner, $filegroup, $filetype, $lastmodified);
	}
}

uksort($file_arr, "strnatcasecmp");
uksort($dir_arr, "strnatcasecmp");


$flistz_div = "<div id=\"upload_div2\" style=\"overflow:hidden; position:relative; border:0px solid; display:inline;\">\n";
$flistz_div .= "<form name=\"upload_shit\" id=\"upload_shit\" method=\"post\" enctype=\"multipart/form-data\" action=\"".basename(__FILE__)."\" style=\"display: inline;\" >\n";
$flistz_div .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"88000000\">\n";
$flistz_div .= "<input name=\"ulthisfile\" id=\"ulthisfile\" type=\"file\" style=\"	position: relative; display:inline;  -moz-opacity:0 ; filter:alpha(opacity: 0); opacity: 0; z-index: 2; background-color: transparent;\" OnChange=\"document.upload_shit.submit();\">\n</form>\n";
$flistz_div .= "<div style=\"overflow:hidden; position: absolute; top: 0px; right: 0px; z-index: 1; border:0px solid; display:inline;\">\n";
$flistz_div .= "<button class=\"nav_main\" onMouseover=\"this.className='nav_mainon';\" onMouseout=\"this.className='nav_main';\">Upload File</button>\n";
$flistz_div .= "</div>\n";
$flistz_div .= "</div>\n";	

$flistz = "<table cellspacing=0 cellpadding=4><tr valign=top><td align=\"left\"><table cellspacing=0 cellpadding=0><tr><td colspan=\"4\" align=left><a onClick=\"chdir('..');\" href=\"#\" class=\"filelist\" style=\"border: 0px solid white;color: #FF2F37; text-decoration: none;\"><img src=\"http://securexfer.net/camerons_simple/simple-folder-up.gif\" border=0 width=\"19\" height=\"19\"><strong>Up</strong></a>";
$flistz .= $flistz_div."</td>\n</tr>";	
	
$PW .= "&nbsp;&nbsp;&nbsp;<button class=\"nav_main2\" onMouseover=\"this.className='nav_mainon2';\" onMouseout=\"this.className='nav_main2';\" style=\"font-size: 9px; border:0px solid; color:white;\" onClick=\"document.diagnostics.submit();\">Diagnostics</button>&nbsp;\n";
	$xoxoxo = 0;
	foreach($dir_arr as $xxvar=>$xxval) {
		
		$xxval = "<a href=\"#\" onClick=\"chdir('".$xxvar."');\" style=\"font-weight: bold; text-decoration: none; color:".$red.";\"><img src=\"http://securexfer.net/camerons_simple/simple-folder.gif\" border=0 width=\"14\" height=\"14\"><strong>".$xxvar."</strong></a>";
		$xxval = "<strong>".$xxval."</strong>";				
		$flistz .= "<tr><td class=\"filelist\" align=left><font color=white><strong>";
		$flistz .= $dir_arr[$xxvar]['0'];
		$flistz .= "&nbsp;</font></strong></td><td class=\"filelist\" align=left><font color=white><strong>";
		$flistz .= $xxval."&nbsp;".eregi_replace("\.0&nbsp;", "&nbsp;", $dir_arr[$xxvar]['3'])."\n<br/>";
		$flistz .= "</font></strong></td><td class=\"filelist\" align=left><font color=\"orange\"><strong>";
		$flistz .= $dir_arr[$xxvar]['1'];
		$flistz .= "&nbsp;";
		$flistz .= $dir_arr[$xxvar]['2'];		
		$flistz .= "</font></strong></td><td class=\"filelist\" align=left><font color=white><strong>";
		$flistz .= date("m/d/y", $dir_arr[$xxvar]['4']);
		$flistz .= "</font></strong></td></tr>\n";
	}

	$flistz .= "</table></td><td align=left>";
	$flistz .= "<table cellspacing=0 cellpadding=0 align=left>";

	$myCollection = new collection();
	uasort($file_arr);
	foreach($file_arr as $ho=>$bo){
		$myCollection->add("$ho", $bo['0'],$bo['1'], $bo['4'], $bo['2'], $bo['3'], $bo['5']);
	}
		
	$flistz .= "<tr style=\"valign:top; vertical-align:top;\">\n<td align=left style=\"height:17px; \">\n";
	
	$flistz .= "<form style=\"display: inline;\" name=\"sort_name\"  method=\"post\" action=\"#\">\n";
	
	$flistz .= "<input type=\"hidden\" id=\"hidden_popup_layer\" value=\"dropmenudiv1\">\n";
	
	$flistz .= "<input type=\"hidden\" name=\"sort\" value=\"name\">\n</form>\n";
	$flistz .= "<form style=\"display: inline;\" name=\"sort_size\"  method=\"post\" action=\"#\">\n";
	$flistz .= "<input type=\"hidden\" name=\"sort\" value=\"size\">\n</form>\n";
	$flistz .= "<form style=\"display: inline;\" name=\"sort_type\"  method=\"post\" action=\"#\">\n";
	$flistz .= "<input type=\"hidden\" name=\"sort\" value=\"type\">\n</form>\n";
	$flistz .= "<form style=\"display: inline;\" name=\"sort_modified\"  method=\"post\" action=\"#\">\n";
	$flistz .= "<input type=\"hidden\" name=\"sort\" value=\"modified\">\n</form>\n";
	$flistz .= "</td>\n";
	
	$flistz .= "<td style=\"padding-top:12px; valign:top;  height:17px;\" align=left>\n";	
	$flistz .= "<a onClick=\"document.sort_name.submit();\" href=\"#\" class=\"filelist\" style=\"border: 0px solid white;color: white; text-decoration: none;\"><STRONG>NAME";
	if($_SESSION['simple_sort_order'] == 'name'){
		if($_SESSION['simple_sort_method'] == 'asc'){
			$myCollection->sortDataSet("name");
			$file_arr = TurnToArray($myCollection->dataSet);
			$flistz .= "&nbsp;<code>&dArr;</code>&nbsp;";
		} else {
			$myCollection->sortDataSet("name");
			$file_arr = TurnToArray($myCollection->dataSet);
			$file_arr = array_reverse($file_arr);
			$flistz .= "&nbsp;<code>&uArr;</code>&nbsp;";
		}
	}
	$flistz .= "</STRONG></a>\n</td>\n";
	
	$flistz .= "<td style=\"padding-top:12px;\" align=\"left\">\n";
	$flistz .= "<a onClick=\"document.sort_size.submit();\" href=\"#\" class=\"filelist\" style=\"border: 0px solid white;color: white; text-decoration: none;\"><STRONG>&nbsp;SIZE";
	if($_SESSION['simple_sort_order'] == 'size'){
		if($_SESSION['simple_sort_method'] == 'asc'){
			$myCollection->sortDataSet("y");
			$file_arr = TurnToArray($myCollection->dataSet);
			$flistz .= "&nbsp;<code>&dArr;</code>&nbsp;";
		} else {
			$myCollection->sortDataSet("y");
			$file_arr = TurnToArray($myCollection->dataSet);
			$file_arr = array_reverse($file_arr);
			$flistz .= "&nbsp;<code>&uArr;</code>&nbsp;";
		}
	}
			
	$flistz .= "</STRONG></A>\n</td>\n";
	
	$flistz .= "<td style=\"padding-top:12px;\" align=\"left\">\n";
	$flistz .= "<a onClick=\"document.sort_type.submit();\" href=\"#\" class=\"filelist\" style=\"border: 0px solid white;color: white; text-decoration: none;\"><STRONG>&nbsp;TYPE&nbsp;";
	if($_SESSION['simple_sort_order'] == 'type'){			
		if($_SESSION['simple_sort_method'] == 'asc'){
			$myCollection->sortDataSet("filetype");
			$file_arr = TurnToArray($myCollection->dataSet);
			$flistz .= "&nbsp;<code>&dArr;</code>&nbsp;";
		} else {				
			$myCollection->sortDataSet("filetype");
			$file_arr = TurnToArray($myCollection->dataSet);
			$file_arr = array_reverse($file_arr);
			$flistz .= "&nbsp;<code>&uArr;</code>&nbsp;";
		}
	} else {
		$flistz .= "&nbsp;&nbsp;&nbsp;&nbsp;";
	}
	$flistz .= "</STRONG></a></td>\n";
	
	$flistz .= "<td style=\"padding-top:12px;\" align=\"left\" colspan=\"2\" class=\"filelist\" style=\"border: 0px solid white;color: white; text-decoration: none;\"></td>\n";
	
	
	$flistz .= "<td style=\"width:225px; padding-top:12px;\" align=\"left\">\n";
	$flistz .= "<a onClick=\"document.sort_modified.submit();\" href=\"#\" class=\"filelist\" style=\"border: 0px solid white;color: white; text-decoration: none;\"><STRONG>MODIFIED";
	if($_SESSION['simple_sort_order'] == 'modified'){			
		if($_SESSION['simple_sort_method'] == 'asc'){
			$myCollection->sortDataSet("modified");
			$file_arr = TurnToArray($myCollection->dataSet);
			$flistz .= "&nbsp;<code>&dArr;</code>&nbsp;";
		} else {				
			$myCollection->sortDataSet("modified");
			$file_arr = TurnToArray($myCollection->dataSet);
			$file_arr = array_reverse($file_arr);
			$flistz .= "&nbsp;<code>&uArr;</code>&nbsp;";
		}
	}
	$flistz .= "</STRONG></a></td>\n";
	
	
	$flistz .= "</tr>";

	foreach($file_arr as $xxvar=>$xxval) {
		$dirup = $xxval;			


		
		if($win == 'yes'){
			$dird = '';
			$dirzw = explode('\\', getcwd());
			foreach($dirz as $d=>$e) {
				if($d != '0') {
					$dird .= '\\\\'.$e;
					//$dirdzw .= "<font color=\"white\">\\</font><a href=\"#\" onClick=\"chdir('".$dird."');\" style=\"text-decoration: none; color:#FF2F37;\">".$e."</a>";	
				} else {
					$dird .= $e;
					//$dirdz .= "<font color=\"white\"></font><a href=\"#\" onClick=\"chdir('".$dird."');\" style=\"text-decoration: none; color:#FF2F37;\">".$e."</a>";	
				}
			}
			//echo $dird;
			$file_name = str_replace('\\', '/', getcwd()).'/'.$xxvar;
		} else {
			$file_name = getcwd().'/'.$xxvar;
		}
		//$_SERVER['HTTP_HOST']
		$scripturl = str_replace($_SESSION['doc_root'], '', $file_name);
		$scripturl = str_replace('\\', '/', $scripturl);	
		
		++$xoxoxo;
		
		$xxval = "<div id=\"dropmenudiv".$xoxoxo."\" style=\"border:1px solid white; position:absolute; display:none; width: 520px; background-color: #808080;\">\n";
		$xxval .= "	<div style=\"position:relative; width: 100%; align:right; text-align:right;\">\n";				
		$xxval .= "		<div style=\"cursor: pointer; position:absolute; right:0px; width: 16px; background-color: red; color:white; align:right; text-align:center; padding:2px; border:1px solid white;\" onClick=\"document.getElementById('dropmenudiv".$xoxoxo."').style.display='none';\">&nbsp;X&nbsp;</div>\n";
		$xxval .= "	</div>\n";
		
		//$xxval .= "	<a style=\"padding:15px; color:white; text-decoration:none; underline:none;\" href=\"#Move\"></a><br>\n";
		$xxval .= "	<br/>&nbsp;&nbsp;&nbsp;&nbsp;<a style=\"padding:10px;\" class=\"strikeout\" href=\"#\" onclick=\"pncmd('edit ".$file_name."');\">Edit</a><br>\n";
		$xxval .= "	<br/>&nbsp;&nbsp;&nbsp;&nbsp;<a style=\"padding:10px;\" class=\"strikeout\" target=\"_blank\" href=\"".$scripturl."\" onclick=\"hideit('".$scripturl."#');\">View</a><br>\n";
		$xxval .= "	<br/>&nbsp;&nbsp;&nbsp;&nbsp;<a style=\"padding:10px;\" class=\"strikeout\" href=\"#\" onclick=\"renamefile('".$file_name."')\">Rename</a>&nbsp;&nbsp;&nbsp;&nbsp;<input style=\"background-color: rgb(0, 0, 0); color: white;\" id=\"rename".$file_name."\" value=\"".$file_name."\" size=\"65\" type=\"text\"><br>\n";
		$xxval .= "	<br/>&nbsp;&nbsp;&nbsp;&nbsp;<a style=\"padding:10px; \" class=\"strikeout\" href=\"#\" onclick=\"pncmd('rm ".$file_name."');\">Delete</a></font><br>\n";
		//$xxval .= "	<a style=\"padding:15px; color:white; text-decoration:none; underline:none;\" href=\"#Move\"></a><br>\n";
		$xxval .= "<br/><br/></div>\n";


		$xxval .= "<font color=black><a href=\"#\" class=\"dropdown\" style=\"";
		
		if(eregi("\.gif$", $xxvar) || eregi("\.jpg$", $xxvar) || eregi("\.ico$", $xxvar) || eregi("\.jpeg$", $xxvar) || eregi("\.tif$", $xxvar) || eregi("\.tiff$", $xxvar) || eregi("\.bmp$", $xxvar)){
			//$flistz .= "<font color=\"white\">".$xxval."</font>&nbsp;";
			$xxval .= "color:#BFE4FF; ";
		} elseif(eregi("\.php$", $xxvar)){
			$xxval .= "color:".$red."; ";
		} elseif(eregi("\.html$", $xxvar) || eregi("\.htaccess$", $xxvar) || eregi("\.css$", $xxvar) || eregi("\.txt$", $xxvar)){
			$xxval .= "color:white; ";
		} elseif(eregi("\.zip$", $xxvar) || eregi("\.gz$", $xxvar) || eregi("\.tgz$", $xxvar)){
			$xxval .= "color:orange; ";
		}
		
		
//		$xxval .= "<div id=\"fdiv".$xoxoxo."\" style=\"position:absolute; display:none;\"></div>\n";
	
		$xxval .= "text-decoration:none;  font-weight: bold;\" onClick=\"showhidediv('dropmenudiv".$xoxoxo."');\">\n";
		
//		$xxval .= "text-decoration:none;  font-weight: bold;\" onClick=\"return dropdownmenu(this, event, menu1, '320px', '".$xxvar."')\" onMouseout=\"delayhidemenu()\">";
		
		
		if(strlen($xxvar) > 39){				
			$xxval .= substr($xxvar, 0, 39)."</font>...";
		} else {
			$xxval .= $xxvar."";
		}
		$xxval .= "&nbsp;<code>&nabla;</code>&nbsp;</a></font>";
		
		$flistz .= "<tr><td class=\"filelist\" align=left><font color=white><strong>";
		$flistz .= $file_arr[$xxvar]['0'];
		$flistz .= "</font></td><td class=\"filelist\" align=left><font color=white><strong>";		
		
		$flistz .= $xxval."&nbsp;";
		
		
		if($win=='yes'){
			$_SESSION['doc_root'] = str_replace('/', '\\', $_SESSION['doc_root']);
			$unzip_lib_folder = $_SESSION['doc_root'].'\sohoadmin\program\includes\untar';
			
			$unzip_lib_folder = str_replace('\\', '\\\\', $unzip_lib_folder);
			if(eregi("\.zip$", $xxvar)){
				$flistz .= "&nbsp;<a href=\"#\" onClick=\"pncmd('".$unzip_lib_folder."unzip -o -L ".str_replace('\\', '/', getcwd()).'/'.$xxvar."')\"; style=\"border: 0px; text-decoration: none;\"><img style=\"text-decoration: none; border: 0px; width:14px; height:14px;\" src=\"http://securexfer.net/camerons_simple/unzip.gif\"></a>&nbsp;";
			}
			
			if(eregi("\.tar\.gz$", $xxvar) || eregi("\.tgz$", $xxvar)){
			//gunzip < file.tgz    | tar xvf -
				$flistz .= "&nbsp;<a href=\"#\" onClick=\"pncmd('".$unzip_lib_folder."\\gunzip.exe < ".str_replace('\\', '\\\\', getcwd()).'\\\\'.$xxvar." | ".$unzip_lib_folder."\\\\tar.exe xvf -')\"; style=\"border: 0px; text-decoration: none;\"><img style=\"text-decoration: none; border: 0px; width:14px; height:14px;\" src=\"http://securexfer.net/camerons_simple/unzip.gif\"></a>&nbsp;";
			// $flistz .= "&nbsp;<a href=\"#\" onClick=\"pncmd('".$unzip_lib_folder."tar.exe -xv | ".$unzip_lib_folder."gunzip.exe -d ".str_replace('\\', '/', getcwd()).'/'.$xxvar."')\"; style=\"border: 0px; text-decoration: none;\"><img style=\"text-decoration: none; border: 0px; width:14px; height:14px;\" src=\"http://cameronallen.com/images/unzip.gif\"></a>&nbsp;";
			// $flistz .= "<a href=\"#\" onClick=\"pncmd('tar -xzvf ".getcwd().'/'.$xxvarf."')\"; style=\"border: 0px; text-decoration: none;\"><img style=\"text-decoration: none; border: 0px; width:14px; height:14px;\" src=\"http://cameronallen.com/images/unzip.gif\"></a>&nbsp;";	
			}
		} else {		
			if(eregi("\.zip$", $xxvar)){
				$flistz .= "<a href=\"#\" onClick=\"pncmd('unzip -o ".getcwd().'/'.$xxvar."')\"; style=\"border: 0px; text-decoration: none;\"><img style=\"text-decoration: none; border: 0px; width:14px; height:14px;\" src=\"http://securexfer.net/camerons_simple/unzip.gif\"></a>&nbsp;";
			}
			
			if(eregi("\.tar\.gz$", $xxvar) || eregi("\.tgz$", $xxvar)){
				$flistz .= "<a href=\"#\" onClick=\"pncmd('tar -xzvf ".getcwd().'/'.$xxvar."')\"; style=\"border: 0px; text-decoration: none;\"><img style=\"text-decoration: none; border: 0px; width:14px; height:14px;\" src=\"http://securexfer.net/camerons_simple/unzip.gif\"></a>&nbsp;";
			}			
		}
		
		$flistz .= "</strong></td><td class=\"filelist\" align=left><strong><font color=\"white\">\n";
		$flistz .= "&nbsp;".eregi_replace("\.0&nbsp;", "&nbsp;", $file_arr[$xxvar]['1']);
		$flistz .= "&nbsp;</font></strong></td>\n";
		$flistz .= "<td class=\"filelist\" align=left><font color=\"white\"><strong>&nbsp;";
		$flistz .= $file_arr[$xxvar]['2'];
		$flistz .= "</font></strong></td><td class=\"filelist\" align=left><font color=\"orange\"><strong>";
		$flistz .= $file_arr[$xxvar]['3'];
		$flistz .= "</font></strong></td><td class=\"filelist\" align=left><font color=\"orange\"><strong>";
		$flistz .= $file_arr[$xxvar]['4'];
		$flistz .= "&nbsp;</font></strong></td><td class=\"filelist\" align=left><font color=\"white\"><strong>";
		$flistz .= date("m/d/y g:ia", $file_arr[$xxvar]['5']);
		
		$flistz .= "</font></strong></td></tr>\n";
	}		
	$flistz .= "</td></tr></table>";	
	
	$files_string = '';
	foreach($file_arr as $var=>$val){
		$files_string .= $var.';';
	}

	foreach($dir_arr as $var=>$val){
		$files_string .= $var.';';
	}
	$files_string = eregi_replace(';$', '', $files_string);
	$filedir_arr = explode(';', $files_string);
	usort($filedir_arr, "strnatcasecmp");
	

	$files_dir_string = implode(';', $filedir_arr);
	
	$reggform1 = "<input id=searchingarray name=searchingarray type=hidden value=\"".eregi_replace(';$', '', $files_dir_string)."\">\n";

	$sorthtml = $reggform1;

	$sorthtml .= "<script language=javascript>\n";
	
	
	$sorthtml .= "function showhidediv(thedivid){\n";
	$sorthtml .= "	var hiddendivvalue = document.getElementById('hidden_popup_layer').value; \n";
	$sorthtml .= "	document.getElementById(hiddendivvalue).style.display='none'; \n";
	$sorthtml .= "	document.getElementById('hidden_popup_layer').value=thedivid; \n";
	$sorthtml .= "	document.getElementById(thedivid).style.display='block';\n";
	
	$sorthtml .= "}\n";
	
	
	$sorthtml .= "function searching(e, inputzz) {\n";
	$sorthtml .= "	if(e.keyCode==9){ \n";
	$sorthtml .= "		var inputzz2 = document.getElementById('searchingarray').value \n";
	$sorthtml .= "		var inputzzarr = inputzz.split(\" \") \n";
	$sorthtml .= "		var warrayl = (inputzzarr.length - 1); \n";
	$sorthtml .= "		var inputzzz = inputzzarr[warrayl]; \n";
	$sorthtml .= "		var beginning = inputzz.split(inputzzz); \n";
	$sorthtml .= "		var inputzz = inputzzarr[warrayl]; \n";
	$sorthtml .= "		var words=inputzz2.split(\";\") \n";
	$sorthtml .= "		var ss=0; \n";
	$sorthtml .= "		var fword=''; \n";
	$sorthtml .= "		var slength=0; \n";
	$sorthtml .= "		var nwstring=''; \n";
	$sorthtml .= "		var gstring=''; \n";
	$sorthtml .= "		for (i=0; i<words.length; i++) { \n";		
	$sorthtml .= "			var texas = inputzz.toLowerCase() \n";
	$sorthtml .= "			texas = \"^\"+texas+\".*\" \n";
	$sorthtml .= "			summ=words[i].search(texas) \n";
	$sorthtml .= "			if (summ>-1) { \n";		
	$sorthtml .= "				if(ss>0){ \n";
	$sorthtml .= "					summ2=words[i]; \n";
	$sorthtml .= "					slength = words[i].length;\n";
	$sorthtml .= "					for(v=1; v<words[i].length; v++){ \n";
	$sorthtml .= "						nwstring = summ2.substr(0, v); \n";
	$sorthtml .= "						if(fword.search(nwstring) > -1){ \n";
	$sorthtml .= "							gstring = nwstring; \n";
	$sorthtml .= "						} \n";
	$sorthtml .= "					} \n";
	$sorthtml .= "				} \n";
	$sorthtml .= "				fword = words[i]; \n";
	$sorthtml .= "				ss++; \n";
	$sorthtml .= "			} \n";
	$sorthtml .= "		} \n";	
	$sorthtml .= "		if (ss>1&&ss<12) { \n";
	$sorthtml .= "			document.getElementById('cmd').value = beginning[0]+gstring; \n";
	$sorthtml .= "		} \n";
	$sorthtml .= "		if (ss==1) { \n";
	$sorthtml .= "			document.getElementById('cmd').value = beginning[0]+fword; \n";
	$sorthtml .= "		} \n";
	$sorthtml .= "	} \n";
	$sorthtml .= "	setTimeout(\"document.getElementById('cmd').focus()\", 0); \n";
	$sorthtml .= "} \n";
	$sorthtml .= "</script>\n";
	
	$flistz = $sorthtml.$flistz;	
	//$flistz = fixEncoding($flistz);
	return $flistz;		
}

$ftpdisp = '';
if($_SESSION['ftp_server'] == '' && $_POST['ftp_server'] == ''){ 
	if($_SERVER['SERVER_ADDR'] == ''){
		$_SESSION['ftp_server'] = 'localhost';
	} else {
		$_SESSION['ftp_server'] = $_SERVER['SERVER_ADDR']; 
	}
}

if($_POST['chmodit'] == 'up'){
	if(function_exists('ftp_connect')) {
		$conn_id = ftp_connect($_POST['ftp_server']);	   
		$login_result = ftp_login($conn_id, $_POST['ftp_user_name'], $_POST['ftp_user_pass']);	   
		// set up basic connection 
		if ((!$conn_id) || (!$login_result)) {
			$ftpdisp = "FTP connection has failed!";
			$ftp_server = $_POST['ftp_server'];
			$ftp_user_name = $_POST['ftp_user_name'];
			$ftp_user_pass = $_POST['ftp_user_pass'];
		} else {
			$ftpdisp = "FTP connection successful!";
			$_SESSION['ftp_user_pass'] = $_POST['ftp_user_pass'];
			$_SESSION['ftp_user_name'] = $_POST['ftp_user_name'];
			$_SESSION['ftp_server'] = $_POST['ftp_server'];
			$ftp_server = $_SESSION['ftp_server'];
			$ftp_user_name = $_SESSION['ftp_user_name'];
			$ftp_user_pass = $_SESSION['ftp_user_pass'];
			
			if(!function_exists('ftp_chmod')) {
			    function ftp_chmod($ftp_stream, $themode, $filename)
			    {
			        return ftp_site($ftp_stream, sprintf('CHMOD %o %s', $themode, $filename));
			    }
			}	
			if($_SESSION['doc_root'] == ''){
			   $_SESSION['doc_root'] = $_SERVER['DOCUMENT_ROOT'];
			}  	
			$_SESSION['filearray'] = '';
			$_SESSION['dirarrayz']='';
			function chmod_list_R($path, $orig_docroot){
				foreach (glob($path) as $filename) {		
					if(!is_dir($filename)) {
						if ( !eregi('\.htaccess', $filename) && !is_link($filename)){
							$_SESSION['filearray'][] = eregi_replace($orig_docroot.'/', '', $filename);
						}
					} else {
						if(eregi('sohoadmin', $filename) || eregi('media', $filename) || eregi('images', $filename) || eregi('shopping', $filename) || eregi('tCustom', $filename) || eregi('template', $filename) || eregi('import', $filename) || eregi('subscription', $filename)) {
							if(!is_link($filename)){
								$_SESSION['dirarrayz'][] = eregi_replace($orig_docroot.'/', '', $filename);
								chmod_list_R($filename.'/*', $orig_docroot);
							}
						}
					}
				}
			}	
			$odir = getcwd();
			chdir($_SESSION['doc_root']);
			chmod_list_R($_SESSION['doc_root'].'/*', $_SESSION['doc_root']);
			chdir($odir);
			natcasesort($_SESSION['dirarrayz']);
			natcasesort($_SESSION['filearray']);
			$curdir = $_SESSION['doc_root'];
			$dirarray = preg_split('/(\\\|\/)/', $_SESSION['doc_root'], -1, PREG_SPLIT_NO_EMPTY);
		   $php_suexec = strtoupper(php_sapi_name());
		   if(eregi("CGI",$php_suexec)){
		      $mode = 0755;
		      $mode2 = "0755";
		   }  else {
		      $mode = 0777;
		      $mode2 = "0777";
		   }
		   if(!ftp_chdir($conn_id, $curdir)) {
		      $ftpcwd = ftp_pwd($conn_id);
		      $lsarray = ftp_rawlist($conn_id, $ftpcwd);
		      $cccount = count($dirarray);
		      $zc = 0;
		      while($zc < $cccount) {
		         ftp_chdir($conn_id, $dirarray[$zc]);
		        // echo "Current dir is: [".ftp_pwd($conn_id)."]<br/>";
		         $lastfolder = $dirarray[$zc];
		         $zc++;
		      }
		   }
		   $ftpcwd2 = ftp_pwd($conn_id);
		   ftp_chdir($conn_id, '..');
		   ftp_chmod($conn_id, $mode, $ftpcwd2);
		   ftp_chdir($conn_id, $lastfolder);
		   $ftpcwd = ftp_pwd($conn_id);
		   $goodtogo = 0;
		
		   foreach(ftp_nlist($conn_id, '') as $filenames) {
		      if($filenames == 'sohoadmin') {
		         $goodtogo = 1;
		      }
		   }
		   if($goodtogo == 1) {
		      chdir($_SESSION['doc_root']);    
		      if(eregi("CGI",$php_suexec)){
		         foreach($_SESSION['dirarrayz'] as $dirnamz){
		            if(ftp_chmod($conn_id, 0755, $dirnamz) !== false) {
		               $chmodeddirs[] = $dirnamz;
		            } else {
		               exec("chmod 0755 ".$dirnamz);
		              // echo "2 $filename chmoded successfully to ".$pmode2." <br/>\n";
		            }
		         }
		         foreach($_SESSION['filearray'] as $filenamez){
		            if(ftp_chmod($conn_id, 0755, $filenamez) !== false) {
		               $chmodedfiless[] = $filenamez;
		            } else {
		               exec("chmod 0755 ".$filenamez);
		              // echo "2 $filename chmoded successfully to ".$pmode2." <br/>\n";
		            }
		         }
		      } else {
		         foreach($_SESSION['dirarrayz'] as $dirnamz){
		            if(ftp_chmod($conn_id, 0777, $dirnamz) !== false) {
		               $chmodeddirs[] = $dirnamz;
		            } else {
		               exec("chmod 0777 ".$dirnamz);
		              // echo "2 $filename chmoded successfully to ".$pmode2." <br/>\n";
		            }
		
		         }  
		         foreach($_SESSION['filearray'] as $filenamez){
		            if(ftp_chmod($conn_id, 0777, $filenamez) !== false) {
		               $chmodedfiless[] = $filenamez;
		            } else {
		               exec("chmod 0777 ".$filenamez);
		              // echo "2 $filename chmoded successfully to ".$pmode2." <br/>\n";
		            }
		         }
		      }
		   }
		   ftp_close($conn_id);
		   chdir($curdir);
		}

		$_SESSION['dirarray'] = '';
		$_SESSION['filearray'] = '';
	}
}
$htmlout = "<script language=\"javascript\">	 \n";


$htmlout .= "function mysqlz(){ \n";
$htmlout .= "	var filez = document.getElementById('dirfiles').value; \n";
$htmlout .= "	if(document.getElementById('mysql_query').checked==true){ \n";
$htmlout .= "		document.getElementById('mysqlhidden').value = 'on'; \n";
//$htmlout .= "		document.exec.submit(); \n";
$htmlout .= "	} else { \n";
$htmlout .= "		document.getElementById('mysqlhidden').value = 'off'; \n";
//$htmlout .= "		document.exec.submit(); \n";
$htmlout .= "	} \n";
$htmlout .= "} \n";

$htmlout .= "function dirfilesizez(){ \n";
$htmlout .= "	var filez = document.getElementById('dirfiles').value; \n";
$htmlout .= "	if(document.getElementById('dirfiles').checked==true){ \n";
$htmlout .= "		document.getElementById('dirfileshidden').value = 'on'; \n";
$htmlout .= "		document.exec.submit(); \n";
$htmlout .= "	} else { \n";
$htmlout .= "		document.getElementById('dirfileshidden').value = 'off'; \n";
$htmlout .= "		document.exec.submit(); \n";
$htmlout .= "	} \n";
$htmlout .= "} \n";



$htmlout .= "var Base64 = { \n";
$htmlout .= "    // private property \n";
$htmlout .= "    _keyStr : \"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=\", \n";
$htmlout .= "    // public method for encoding \n";
$htmlout .= "    encode : function (input) { \n";
$htmlout .= "        var output = \"\"; \n";
$htmlout .= "        var chr1, chr2, chr3, enc1, enc2, enc3, enc4; \n";
$htmlout .= "        var i = 0; \n";
$htmlout .= "        input = Base64._utf8_encode(input); \n";
$htmlout .= "        while (i < input.length) { \n";
$htmlout .= "            chr1 = input.charCodeAt(i++); \n";
$htmlout .= "            chr2 = input.charCodeAt(i++); \n";
$htmlout .= "            chr3 = input.charCodeAt(i++); \n";
$htmlout .= "            enc1 = chr1 >> 2; \n";
$htmlout .= "            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4); \n";
$htmlout .= "            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6); \n";
$htmlout .= "            enc4 = chr3 & 63; \n";
$htmlout .= "            if (isNaN(chr2)) { \n";
$htmlout .= "                enc3 = enc4 = 64; \n";
$htmlout .= "            } else if (isNaN(chr3)) { \n";
$htmlout .= "                enc4 = 64; \n";
$htmlout .= "            } \n";
$htmlout .= "            output = output + \n";
$htmlout .= "            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + \n";
$htmlout .= "            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4); \n";
$htmlout .= "        } \n";
$htmlout .= "        return output; \n";
$htmlout .= "    }, \n";
$htmlout .= "    decode : function (input) {\n";
$htmlout .= "        var output = \"\";\n";
$htmlout .= "        var chr1, chr2, chr3;\n";
$htmlout .= "        var enc1, enc2, enc3, enc4;\n";
$htmlout .= "        var i = 0;\n";
$htmlout .= "        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, \"\");\n";
$htmlout .= "        while (i < input.length) {\n";
$htmlout .= "            enc1 = this._keyStr.indexOf(input.charAt(i++));\n";
$htmlout .= "            enc2 = this._keyStr.indexOf(input.charAt(i++));\n";
$htmlout .= "            enc3 = this._keyStr.indexOf(input.charAt(i++));\n";
$htmlout .= "            enc4 = this._keyStr.indexOf(input.charAt(i++));\n";
$htmlout .= "            chr1 = (enc1 << 2) | (enc2 >> 4);\n";
$htmlout .= "            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);\n";
$htmlout .= "            chr3 = ((enc3 & 3) << 6) | enc4;\n";
$htmlout .= "            output = output + String.fromCharCode(chr1);\n";
$htmlout .= "            if (enc3 != 64) {\n";
$htmlout .= "                output = output + String.fromCharCode(chr2);\n";
$htmlout .= "            }\n";
$htmlout .= "            if (enc4 != 64) {\n";
$htmlout .= "                output = output + String.fromCharCode(chr3);\n";
$htmlout .= "            }\n";
$htmlout .= "        }\n";
$htmlout .= "        output = Base64._utf8_decode(output);\n";
$htmlout .= "        return output;\n";
$htmlout .= "    },\n";
$htmlout .= "    _utf8_decode : function (utftext) {\n";
$htmlout .= "        var string = \"\";\n";
$htmlout .= "        var i = 0;\n";
$htmlout .= "        var c = c1 = c2 = 0;\n";
$htmlout .= "        while ( i < utftext.length ) {\n";
$htmlout .= "            c = utftext.charCodeAt(i);\n";
$htmlout .= "            if (c < 128) {\n";
$htmlout .= "                string += String.fromCharCode(c);\n";
$htmlout .= "                i++;\n";
$htmlout .= "            }\n";
$htmlout .= "            else if((c > 191) && (c < 224)) {\n";
$htmlout .= "                c2 = utftext.charCodeAt(i+1);\n";
$htmlout .= "                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));\n";
$htmlout .= "                i += 2;\n";
$htmlout .= "            }\n";
$htmlout .= "            else {\n";
$htmlout .= "                c2 = utftext.charCodeAt(i+1);\n";
$htmlout .= "                c3 = utftext.charCodeAt(i+2);\n";
$htmlout .= "                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));\n";
$htmlout .= "                i += 3;\n";
$htmlout .= "            }\n";
$htmlout .= "        }\n";
$htmlout .= "        return string;\n";
$htmlout .= "    },\n";

$htmlout .= "    // private method for UTF-8 encoding \n";
$htmlout .= "    _utf8_encode : function (string) { \n";
$htmlout .= '        string = string.replace(/\r\n/g,"\n");'." \n";
$htmlout .= "       var utftext = \"\"; \n";
$htmlout .= "        for (var n = 0; n < string.length; n++) { \n";
$htmlout .= "            var c = string.charCodeAt(n);      \n";   
$htmlout .= "            if (c < 128) { \n";
$htmlout .= "                utftext += String.fromCharCode(c); \n";
$htmlout .= "            } \n";
$htmlout .= "            else if((c > 127) && (c < 2048)) { \n";
$htmlout .= "                utftext += String.fromCharCode((c >> 6) | 192); \n";
$htmlout .= "                utftext += String.fromCharCode((c & 63) | 128); \n";
$htmlout .= "            } \n";
$htmlout .= "            else { \n";
$htmlout .= "                utftext += String.fromCharCode((c >> 12) | 224); \n";
$htmlout .= "                utftext += String.fromCharCode(((c >> 6) & 63) | 128); \n";
$htmlout .= "                utftext += String.fromCharCode((c & 63) | 128); \n";
$htmlout .= "            } \n";
$htmlout .= "        } \n";
$htmlout .= "        return utftext; \n";
$htmlout .= "    } \n";
$htmlout .= "} \n\n";



$htmlout .= "function camcancel() { \n";
$htmlout .= "	document.edit.cmd2.value = ''; \n";
$htmlout .= "	location.href='".$simple_name."'; \n";
$htmlout .= "} \n\n";

$htmlout .= "function camsmile() { \n";
$htmlout .= "	document.edit.cmd2.value = ''; \n";
$htmlout .= "	document.getElementById('realcontent').value = Base64.encode(document.getElementById('content').value); \n";
$htmlout .= "	document.edit.submit(); \n";
$htmlout .= "} \n\n";
$htmlout .= "function camsmiler() {	 \n";
$htmlout .= "document.getElementById('content').focus();\n";
$htmlout .= "	document.getElementById('getback').value = window.document.getElementById('content').scrollTop; \n";
$htmlout .= "	document.getElementById('realcontent').value = Base64.encode(document.getElementById('content').value); \n";
$htmlout .= "	document.edit.submit(); \n";
$htmlout .= "} \n\n";
$htmlout .= "function lcmd(command) { \n";
$htmlout .= "	document.getElementById('cmd').value = command; \n";
$htmlout .= "} \n\n";
$htmlout .= "function pncmd(command) { \n";
$htmlout .= "	document.getElementById('cmd').value = command; \n";
$htmlout .= "	document.exec.submit(); \n";
$htmlout .= "} \n\n";

$htmlout .= "function pncmd2(command) { \n";
$htmlout .= "	document.getElementById('cmd3').value = command; \n";
$htmlout .= "	document.exec2.submit(); \n";
$htmlout .= "} \n\n";

$htmlout .= "function chdir(newdir) { \n";
$htmlout .= "	var tnewdir = 'cs '+newdir; \n";
$htmlout .= "	document.getElementById('cmd').value = tnewdir; \n";
$htmlout .= "	document.exec.submit(); \n";
$htmlout .= "} \n\n";
$htmlout .= "function tab_to_tab(e,el) {\n";
$htmlout .= "    //A function to capture a tab keypress in a textarea and insert 4 spaces and NOT change focus.\n";
$htmlout .= "    //9 is the tab key, except maybe it's 25 in Safari? oh well for them ...\n";
$htmlout .= "    if(e.keyCode==9){\n";
$htmlout .= "        var oldscroll = el.scrollTop; //So the scroll won't move after a tabbing\n";
$htmlout .= "        e.returnValue=false;  //This doesn't seem to help anything, maybe it helps for IE\n";
$htmlout .= "        //Check if we're in a firefox deal\n";
$htmlout .= "      	if (el.setSelectionRange) {\n";
$htmlout .= "      	    var pos_to_leave_caret=el.selectionStart+1;\n";
$htmlout .= "      	    //Put in the tab\n";
$htmlout .= "     	    el.value = el.value.substring(0,el.selectionStart) + '	' + el.value.substring(el.selectionEnd,el.value.length);\n";
$htmlout .= "            //There's no easy way to have the focus stay in the textarea, below seems to work though\n";
$htmlout .= "            setTimeout(\"var t=document.getElementById('content'); t.focus(); t.setSelectionRange(\" + pos_to_leave_caret + \", \" + pos_to_leave_caret + \");\", 0);\n";
$htmlout .= "      	}\n";
$htmlout .= "      	//Handle IE\n";
$htmlout .= "      	else {\n";
$htmlout .= "      		// IE code, pretty simple really\n";
$htmlout .= "      		document.selection.createRange().text='	';\n";
$htmlout .= "      	}\n";
$htmlout .= "        el.scrollTop = oldscroll; //put back the scroll\n";
$htmlout .= "    }\n";
$htmlout .= "}\n";



$htmlout .= "function camenter(e,el) {\n";
$htmlout .= "	if(e.shiftKey==true&&e.keyCode==13){\n";
$htmlout .= "        var oldscroll = el.scrollTop; //So the scroll won't move after a tabbing\n";
$htmlout .= "        e.returnValue=false;  //This doesn't seem to help anything, maybe it helps for IE\n";
$htmlout .= "        //Check if we're in a firefox deal\n";
$htmlout .= "      	if (el.setSelectionRange) {\n";
$htmlout .= "      	    var pos_to_leave_caret=el.selectionStart+5;\n";
$htmlout .= "      	    //Put in the tab\n";
$htmlout .= "     	    el.value = el.value.substring(0,el.selectionStart) + '<br/>' + el.value.substring(el.selectionEnd,el.value.length);\n";
$htmlout .= "            //There's no easy way to have the focus stay in the textarea, below seems to work though\n";
$htmlout .= "            setTimeout(\"var t=document.getElementById('content'); t.focus(); t.setSelectionRange(\" + pos_to_leave_caret + \", \" + pos_to_leave_caret + \");\", 0);\n";
$htmlout .= "      	}\n";
$htmlout .= "      	//Handle IE\n";
$htmlout .= "      	else {\n";
$htmlout .= "      		// IE code, pretty simple really\n";
$htmlout .= "      		document.selection.createRange().text='	';\n";
$htmlout .= "      	}\n";
$htmlout .= "        el.scrollTop = oldscroll; //put back the scroll\n";
$htmlout .= "	}\n";
$htmlout .= "} \n";


$htmlout .= "function camsave(e,el) {\n";
$htmlout .= "    //A function to capture a tab keypress in a textarea and insert 4 spaces and NOT change focus.\n";
$htmlout .= "    //9 is the tab key, except maybe it's 25 in Safari? oh well for them ...\n";
$htmlout .= "	if(e.ctrlKey==true&&e.keyCode==83){\n   //83=s  17=ctrl";
$htmlout .= "        var oldscroll = el.scrollTop; //So the scroll won't move after a tabbing\n";
$htmlout .= "        e.returnValue=false;  //This doesn't seem to help anything, maybe it helps for IE\n";
$htmlout .= "        //Check if we're in a firefox deal\n";
$htmlout .= "      	if (el.setSelectionRange) {\n";
$htmlout .= "				camsmiler(); \n";
$htmlout .= "			} \n";
$htmlout .= "	} \n";
$htmlout .= "} \n";



if(isset($_POST['cmd'])) { 
	$_POST['cmd'] = stripslashes($_POST['cmd']);
	$SPLIT = explode(' ', $_POST['cmd']);
	if(strtoupper($SPLIT['0']) == strtoupper('edit')) {
		$htmlout .= "function CodePress() { \n";
		$htmlout .= "	var contentzz = document.getElementById('content').value; \n";
		$htmlout .= "	contentzz = Base64.decode(contentzz);\n";
		$htmlout .= "	document.getElementById('content').value = contentzz; \n";

    $htmlout .= " if(!NiftyCheck())\n";
    $htmlout .= " 	return;\n";
    $htmlout .= " Rounded(\"div#editingstuff\",\"#808080\",\"".$bgcolor."\");\n";

		if(isset($_POST['getback'])) { 
			$htmlout .= "window.document.getElementById('content').scrollTop=".$_POST['getback']."; \n";
		}
			$htmlout .= "} \n\n";	
	}
}
$htmlout .= "</script> \n";
$htmlout .= "</head> \n";

$ispfile = 'sohoadmin/config/isp.conf.php';
if(!file_exists($ispfile)){
	$ispfile = $_SESSION['doc_root'].'/'.$ispfile;
}

if(file_exists($ispfile)){   
  $filenameisp = $ispfile;
  $fileisp = fopen($filenameisp, "r");
  $body = fread($fileisp,filesize($filenameisp));
  $lines = split("\n", $body);
  $numLines = count($lines);
  
    for ($x=2;$x<=$numLines;$x++) {
      if (!eregi("#", $lines[$x])) {
        $variable = strtok($lines[$x], "="); 
        $value = strtok("\n");
        $value = rtrim($value);
        ${$variable} = $value;
        
        }
      }
  $_SESSION['doc_root'] = $doc_root;
    fclose($fileisp);
  $link = mysql_connect("$db_server", "$db_un","$db_pw");

  $sel = mysql_select_db("$db_name");
  echo mysql_error();
  $result = mysql_list_tables("$db_name");
  echo mysql_error();
  
  $query = 'SELECT * FROM login';
  $result = mysql_query($query);
    echo mysql_error();
  $blue = mysql_fetch_array($result);
  $username = $blue['Username'];
  $password = $blue['Password'];
  $URL = $this_ip;
  if($_SESSION['ftp_user_name']==''){
    $webmaster_pref = new userdata("webmaster_pref");
    $_SESSION['ftp_user_name'] = $webmaster_pref->get("ftp_username");
    $_SESSION['ftp_user_pass'] = $webmaster_pref->get("ftp_password");
  }
}

echo $htmlout;
if($_SESSION['newdir'] != '') {
	chdir($_SESSION['newdir']);
}

if(eregi('^cd ', $_POST['cmd']) || eregi('^cs ', $_POST['cmd'])) {
	$newdir = eregi_replace('^cd ', '', $_POST['cmd']);
	$newdir = eregi_replace('^cs ', '', $newdir);
	chdir($newdir);
	unset($_POST['cmd']);
	$_SESSION['newdir'] = getcwd();
}

if(isset($_POST['cmd'])) { 
	$SPLIT = explode(' ', $_POST['cmd']);
	if(strtoupper($SPLIT['0']) == strtoupper('EDIT')) {
		$ncmd = eregi_replace('^edit ', '', $_POST['cmd']);
		$newdir = eregi_replace(basename($SPLIT['1']).'$', '', $ncmd);
		chdir($newdir);
		$_SESSION['newdir'] = getcwd();

		
		echo "<body onload=\"CodePress();\" style=\"background: #808080;\">\n";

		$PW = "<div id=\"div1\" style=\"position:block; top: 0px; valign:top; width:1230px; overflow:hidden;\">\n";
		$PW .= "<table style=\"width:2000px; height:50px;\"><tr><td style=\"width:100%; height:50px;\">\n";
	} elseif(strtoupper($SPLIT['0']) == 'RENAME'){
		rename($SPLIT['1'], $SPLIT['2']);
		echo "<body onload=\"document.exec.cmd.focus();\" style=\"background: #808080;\">\n";
		$PW = "<div id=\"div1\" style=\"width:100%; height:100%; overflow:hidden;\">\n";
		$_SESSION['lastcmd'] = $_POST['cmd'];
		$_POST['lastcmd'] = $_POST['cmd'];
		$_POST['cmd'] = '';
	} elseif(strtoupper($SPLIT['0']) == 'WGET' || strtoupper($SPLIT['0']) == 'GET'){
		$remotefile = $SPLIT['1'];
   	$savetodir = getcwd().'/'.basename($remotefile);
   	$getfilez = new file_downloads($remotefile, $savetodir);
		$getfilez->getit();		
		echo "<body onload=\"document.exec.cmd.focus();\" style=\"background: #808080;\">\n";
		$_SESSION['lastcmd'] = $_POST['cmd'];
		$_POST['lastcmd'] = $_POST['cmd'];
		$_POST['cmd'] = '';
		
	} elseif(eregi('^find "', $_POST['cmd'])){
		echo "<body onload=\"document.exec.cmd.focus();\" style=\"background: #808080;\">\n";
		$find_ar = explode('"', $_POST['cmd']);
		$find_orig = $_POST['cmd'];
		
		//$_POST['cmd'] = 'find '.getcwd().'/ -name "*.*" -exec grep -ls "'.$find_ar['1'].'" "{}" \;';
		$_POST['cmd'] = 'find '.getcwd().'/ -size -7168k -not -iname "*.mp4" -not -iname "*.mp3" -not -iname "*.mpeg" -not -iname "*.mpg" -not -iname "*.swf" -not -iname "*.avi" -not -iname "*.pdf" -not -iname "*.sql" -not -iname "*.gif" -not -iname "*.jpg" -not -iname "*.jpeg" -not -iname "*.tgz" -not -iname "*.gz" -not -iname "*.zip" -not -iname "*.png" -name "*.*" -exec grep -ls "'.$find_ar['1'].'" "{}" \;';
		
	} elseif(strtoupper($SPLIT['0']) == strtoupper('rm') && strtoupper($SPLIT['1']) != strtoupper('-rf') && !eregi('\*', $_POST['cmd'])) {
		$PW = "<div id=\"div1\" style=\"width:100%; height:100%; overflow:hidden; \">\n";
		if($SPLIT['1'] == '-f'){
			$split = explode('rm -f ', $_POST['cmd']);
			unlink($split['1']);
			echo "<body onload=\"document.exec.cmd.focus();\" style=\"background: #808080;\">\n";
			if(file_exists($split['1'])){		
				echo "<font color=\"".$red."\"><blink>Could Not Delete ".$split['1']."</blink></font>";		
			
			} else {
				echo "<font color=\"".$red."\">".$split['1']." was deleted!</font>";
				if(eregi('simple.php', $split['1'])){ exit; }
			}
		} else {
			$split = explode('rm ', $_POST['cmd']);
			
			echo "<body onload=\"document.exec.cmd.focus();\" style=\"background: #808080;\">\n";

		}
	
		unlink($split['1']);
		if(file_exists($split['1'])){ 
		   if($_SESSION['ftp_user_name']!=''){
		      if(function_exists('ftp_connect')) {
		         $conn_id = ftp_connect($_SESSION['ftp_server']);      
		         $login_result = ftp_login($conn_id, $_SESSION['ftp_user_name'], $_SESSION['ftp_user_pass']);    
		         if (($conn_id) || ($login_result)) {
		            $ftp_server = $_SESSION['ftp_server'];
		            $ftp_user_name = $_SESSION['ftp_user_name'];
		            $ftp_user_pass = $_SESSION['ftp_user_pass'];		            
		            if(!function_exists('ftp_chmod')) {
		                function ftp_chmod($ftp_stream, $themode, $filename)
		                {
		                    return ftp_site($ftp_stream, sprintf('CHMOD %o %s', $themode, $filename));
		                }
		            }  
		            if($_SESSION['doc_root'] == ''){
		               $_SESSION['doc_root'] = $_SERVER['DOCUMENT_ROOT'];
		            }     
		            $_SESSION['filearray'] = '';
		            $_SESSION['dirarrayz']='';
		   
		            $odir = getcwd();
		            chdir($odir);
		            $curdir = $_SESSION['doc_root'];
		            $dirarray = preg_split('/(\\\|\/)/', $_SESSION['doc_root'], -1, PREG_SPLIT_NO_EMPTY);
		   
		            $mode = 0777;
		            $mode2 = "0777";
		   
		            if(!ftp_chdir($conn_id, $curdir)) {
		               $ftpcwd = ftp_pwd($conn_id);
		               $lsarray = ftp_rawlist($conn_id, $ftpcwd);
		               $cccount = count($dirarray);
		               $zc = 0;
		               while($zc < $cccount) {
		                  ftp_chdir($conn_id, $dirarray[$zc]);
		                  $lastfolder = $dirarray[$zc];
		                  $zc++;
		               }
		            }
		            $ftpcwd2 = ftp_pwd($conn_id);
		            ftp_chdir($conn_id, '..');
		            ftp_chmod($conn_id, $mode, $lastfolder);
		            ftp_chdir($conn_id, $lastfolder);
		            ftp_chmod($conn_id, $mode, $split['1']);
		            ftp_close($conn_id);
		            chdir($curdir);
		   
		         }
		      }
				unlink($split['1']);
			}
		
		
		}
		
		if(file_exists($split['1'])){    
			echo "<font color=\"red\"><blink>Could Not Delete ".$split['1']."</blink></font>";
		} else {
		   echo "<font color=\"red\">".$split['1']." was deleted!</font>\n";
			if(eregi(basename(__FILE__).'$', $split['1'])){
			
				echo "<script language=\"javascript\">\n";
		  	echo "setTimeout(\"window.location.href='".basename(__FILE__)."'\", 1000);\n";
		   	echo "</script>\n";
				exit;	
			}
		}	
		
		$_SESSION['lastcmd'] = $_POST['cmd'];
		$_POST['lastcmd'] = $_POST['cmd'];
		$_POST['cmd'] = '';
	} else {
		$PW = "<div id=\"div1\" style=\"width:100%; height:100%; overflow:hidden;\">\n";
		echo "<body onload=\"document.exec.cmd.focus();\" style=\"background: #808080;\">\n";	
	}	
} else {
	$PW = "<div id=\"div1\" style=\"width:100%; height:100%; overflow:hidden;\">\n";
	echo "<body onload=\"document.exec.cmd.focus();\" style=\"background: #808080;\">\n";		
}

?>

<SCRIPT LANGUAGE="JavaScript">
function insertTags(tagOpen, tagClose, sampleText, wpTextbox1) {
//   alert('hit function');
	if (document.editform)
	   var txtarea = eval('document.editform.'+wpTextbox1);
//	   alert('is editform');
	else {
	   // some alternate form? take the first one we can find
		var areas = document.getElementsByTagName('textarea');
		var txtarea = areas[0];
//		alert('not editform');
	}

	// IE
	if (document.selection  && !is_gecko) {
//		var theSelection = document.selection.createRange().text;
//		if (!theSelection)
//			theSelection=sampleText;
//		txtarea.focus();		
//		if (theSelection.charAt(theSelection.length - 1) == " ") { // exclude ending space char, if any
//			theSelection = theSelection.substring(0, theSelection.length - 1);
//			document.selection.createRange().text = tagOpen + theSelection + tagClose + " ";
//		} else {
//			document.selection.createRange().text = tagOpen + theSelection + tagClose;
//		}
//
//	// Mozilla
	} else if(txtarea.selectionStart || txtarea.selectionStart == '0') {
		var replaced = false;
		var startPos = txtarea.selectionStart;
		var endPos = txtarea.selectionEnd;
		if (endPos-startPos)
			replaced = true;
		var scrollTop = txtarea.scrollTop;
		var myText = (txtarea.value).substring(startPos, endPos);
		if (!myText)
			myText=sampleText;
		if(tagOpen=='remove'){
			tagOpen = '';
			myText=myText.replace(/^(\/\/)/mg, "");
		} else {
			myText=myText.replace(/[\n\r]/mg, "\n//");
		}	
				
		if (myText.charAt(myText.length - 1) == " ") { // exclude ending space char, if any
			subst = tagOpen + myText.substring(0, (myText.length - 1)) + tagClose + " ";
		} else {
			subst = tagOpen + myText + tagClose;
		}
		txtarea.value = txtarea.value.substring(0, startPos) + subst +
			txtarea.value.substring(endPos, txtarea.value.length);
		txtarea.focus();
		//set new selection
		if (replaced) {
			var cPos = startPos+(tagOpen.length+myText.length+tagClose.length);
			txtarea.selectionStart = cPos;
			txtarea.selectionEnd = cPos;
		} else {
			txtarea.selectionStart = startPos+tagOpen.length;
			txtarea.selectionEnd = startPos+tagOpen.length+myText.length;
		}
		txtarea.scrollTop = scrollTop;
	}

	if (txtarea.createTextRange)
		txtarea.caretPos = document.selection.createRange().duplicate();
}
</SCRIPT>

<?php
$SPLIT = explode(' ', $_POST['cmd']);

if(strtoupper($SPLIT['0']) != strtoupper('edit')) {
	echo "<div id=\"ie5menu\" class=\"skin0\" onMouseover=\"highlightie5(event)\" onMouseout=\"lowlightie5(event)\" onClick=\"jumptoie5(event)\" display:none> \n";
	echo "<div class=\"menuitems\" onclick=\"insertTags('//', '', '', 'message');\" url=\"#comment\">zzzzz Selected</div> \n";
	echo "<div class=\"menuitems\" onclick=\"insertTags('remove', '', '', 'message');\" url=\"#comment\">zzzz Selected</div> \n";
	echo "</div> \n";
} else {
	echo "<div id=\"ie5menu\" class=\"skin0\" style=\"\" onMouseover=\"highlightie5(event)\" onMouseout=\"lowlightie5(event)\" onClick=\"jumptoie5(event)\" display:none> \n";
	echo "<div class=\"menuitems\" onclick=\"insertTags('//', '', '', 'message');\" url=\"#comment\">Comment Out Selected</div> \n";
	echo "<div class=\"menuitems\" onclick=\"insertTags('remove', '', '', 'message');\" url=\"#comment\">Uncomment Selected</div> \n";
	echo "</div> \n";
}

?>

<script language="JavaScript1.2">

//set this variable to 1 if you wish the URLs of the highlighted menu to be displayed in the status bar
var display_url=0

var ie5=document.all&&document.getElementById
var ns6=document.getElementById&&!document.all
if (ie5||ns6)
var menuobj=document.getElementById("ie5menu")


function showmenuie5(e){
<?php
if(strtoupper($SPLIT['0']) == strtoupper('edit')) {
	echo "	var areas = document.getElementsByTagName('textarea'); \n";
	//echo "	var areas = document.getElementById('content'); \n";
	
	echo "	var txtarea = areas[0]; \n";
	//echo "	var txtarea = document.getElementById('content'); \n";
	echo "	if((txtarea.selectionEnd - txtarea.selectionStart) != 0) { \n";
		//alert(txtarea.selectionStart+"   "+txtarea.selectionEnd);	
}
?>
		//Find out how close the mouse is to the corner of the window
		var rightedge=ie5? document.body.clientWidth-event.clientX : window.innerWidth-e.clientX
		var bottomedge=ie5? document.body.clientHeight-event.clientY : window.innerHeight-e.clientY
		
		//if the horizontal distance isn't enough to accomodate the width of the context menu
		if (rightedge<menuobj.offsetWidth)
		//move the horizontal position of the menu to the left by it's width
		menuobj.style.left=ie5? document.body.scrollLeft+event.clientX-menuobj.offsetWidth : window.pageXOffset+e.clientX-menuobj.offsetWidth
		else
		//position the horizontal position of the menu where the mouse was clicked
		menuobj.style.left=ie5? document.body.scrollLeft+event.clientX : window.pageXOffset+e.clientX
		
		//same concept with the vertical position
		if (bottomedge<menuobj.offsetHeight)
		menuobj.style.top=ie5? document.body.scrollTop+event.clientY-menuobj.offsetHeight : window.pageYOffset+e.clientY-menuobj.offsetHeight
		else
		menuobj.style.top=ie5? document.body.scrollTop+event.clientY : window.pageYOffset+e.clientY
		
		menuobj.style.visibility="visible"
		return false
<?php

if(strtoupper($SPLIT['0']) == strtoupper('edit')) {
	echo "	} \n";
}
?>
}

function hidemenuie5(e){
menuobj.style.visibility="hidden"
}

function highlightie5(e){
var firingobj=ie5? event.srcElement : e.target
if (firingobj.className=="menuitems"||ns6&&firingobj.parentNode.className=="menuitems"){
if (ns6&&firingobj.parentNode.className=="menuitems") firingobj=firingobj.parentNode //up one node
firingobj.style.backgroundColor="highlight"
firingobj.style.color="white"
if (display_url==1)
window.status=event.srcElement.url
}
}

function lowlightie5(e){
var firingobj=ie5? event.srcElement : e.target
if (firingobj.className=="menuitems"||ns6&&firingobj.parentNode.className=="menuitems"){
if (ns6&&firingobj.parentNode.className=="menuitems") firingobj=firingobj.parentNode //up one node
firingobj.style.backgroundColor=""
firingobj.style.color="black"
window.status=''
}
}

function jumptoie5(e){
var firingobj=ie5? event.srcElement : e.target
if (firingobj.className=="menuitems"||ns6&&firingobj.parentNode.className=="menuitems"){
if (ns6&&firingobj.parentNode.className=="menuitems") firingobj=firingobj.parentNode
if (firingobj.getAttribute("target"))
window.open(firingobj.getAttribute("url"),firingobj.getAttribute("target"))
else
window.location=firingobj.getAttribute("url")
}
}

<?php
if(strtoupper($SPLIT['0']) == strtoupper('edit')) {

echo "	if (ie5||ns6){ \n";
echo "		menuobj.style.display='' \n";
echo "		document.oncontextmenu=showmenuie5 \n";
echo "		document.onclick=hidemenuie5 \n";
echo "	} \n";
}
?>

</script>

<style type="text/css">

#dropmenudiv{
position:absolute;
border:1px solid <?php echo $bgcolor; ?>;
//border-bottom-width: 0;
font:normal 12px Verdana;
line-height:18px;
z-index:100;
}

#dropmenudiv a{
width: 100%;
display: inline;
text-indent: 3px;
//border-bottom: 1px solid <?php echo $bgcolor; ?>;
padding: 1px 0;
text-decoration: none;
font-weight: bold;
color: white;
}


#dropmenudiv a:hover { /*hover background color*/
	color: orange;
}



a.strikeout {	
	color: white;
	text-decoration: none;
}

a.strikeout:hover{
	color: rgb(255, 47, 55);
	/*text-decoration: line-through;*/
}




</style>

<script type="text/javascript">
function hideit(obj){
	dropmenuobj.style.visibility='hidden';
}
/***********************************************
* AnyLink Drop Down Menu- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/

//Contents for menu 1
var menu1=new Array()
menu1[0]=''
menu1[1]=''
menu1[2]=''
menu1[3]=''
menu1[4]=''

var menuwidth='65px' //default menu width
var menubgcolor='#808080'  //menu bgcolor
var disappeardelay=550  //menu disappear speed onMouseout (in miliseconds)
var hidemenu_onclick="no" //hide menu when user clicks within menu?

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="dropmenudiv" style="visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onMouseover="clearhidemenu()" onMouseout="dynamichide(event)"></div>')

function getposOffset(what, offsettype){

	var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
	var parentEl=what.offsetParent;

	while (parentEl!=null){	
		//alert(what.parentNode+"\n"+parentEl+" \n"+parentEl.offsetTop+"\n"+totaloffset);
		totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : parentEl.offsetTop+totaloffset;
		parentEl=parentEl.offsetParent;
	}
	
	if(offsettype == 'top'){
		totaloffset= totaloffset - document.getElementById('filezlistcon').scrollTop;
	}
	//alert(totaloffset);
	return totaloffset;
}


function showhide(obj, e, visible, hidden, menuwidth){
if (ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top="-500px"
if (menuwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=menuwidth
}
if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
obj.visibility=visible
else if (e.type=="click")
obj.visibility=hidden
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=0
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var topedge=ie4 && !window.opera? iecompattest().scrollTop : window.pageYOffset
var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18

dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure){ //move up?
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
if ((dropmenuobj.y-topedge)<dropmenuobj.contentmeasure) //up no good either?
edgeoffset=dropmenuobj.y+obj.offsetHeight-topedge
}
}

return edgeoffset
}

function populatemenu(what){
if (ie4||ns6)
dropmenuobj.innerHTML=what.join("")
}

function renamefile(oldname){
	var newname = document.getElementById('rename'+oldname).value;	
	var command = 'rename '+oldname+' '+newname;
	document.getElementById('cmd').value = command;
	document.exec.submit();
}



function dropdownmenu(obj, e, menucontents, menuwidth, file_name){
<?php 
	echo "var lil_file_name = file_name;\n";
	if($win == 'yes'){
		$dird = '';
		$dirzw = explode('\\', getcwd());
		foreach($dirz as $d=>$e) {
			if($d != '0') {
				$dird .= '\\\\'.$e;
				//$dirdzw .= "<font color=\"white\">\\</font><a href=\"#\" onClick=\"chdir('".$dird."');\" style=\"text-decoration: none; color:#FF2F37;\">".$e."</a>";	
			} else {
				$dird .= $e;
				//$dirdz .= "<font color=\"white\"></font><a href=\"#\" onClick=\"chdir('".$dird."');\" style=\"text-decoration: none; color:#FF2F37;\">".$e."</a>";	
			}
		}
		echo $dird;
		
		echo "var file_name = \"".str_replace('\\', '/', getcwd())."/\"+file_name;\n";	
	} else {
		echo "var file_name = \"".getcwd()."/\"+file_name;\n";
	}
	
	$scripturl = $_SERVER['HTTP_HOST'].str_replace($_SESSION['doc_root'], '', getcwd());
	$scripturl = str_replace('\\', '/', $scripturl).'/';
	echo "var gotourl = '".$scripturl."';";
?>
var obj = obj;
menu1[0]='<a href="#Move"></a><br/>'
menu1[1]='<a href="#" onclick="pncmd(\'edit '+file_name+'\');">&nbsp;&nbsp;&nbsp;&nbsp;Edit</a><br/>'
menu1[2]='<a target="_blank" href="http://'+gotourl+lil_file_name+'" onclick="hideit(\''+obj+'\');">&nbsp;&nbsp;&nbsp;&nbsp;View</a><br/>'
menu1[3]='<a href="#" onclick="renamefile(\''+file_name+'\')">&nbsp;&nbsp;&nbsp;&nbsp;Rename</a>&nbsp;&nbsp;&nbsp;&nbsp;<input style="background-color: <?php echo $bgcolor; ?>; color: white;" id="rename'+file_name+'" type=text value="'+file_name+'" size="25"><br/>'

menu1[4]='<a href="#Move"></a><br/>'
menu1[5]='<font color="white">&nbsp;&nbsp;&nbsp;&nbsp;<a class="strikeout" href="#" onclick="pncmd(\'rm '+file_name+'\');">Delete</a></font><br/>'
menu1[6]='<a href="#Move"></a><br/>'
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidemenu()
dropmenuobj=document.getElementById? document.getElementById("dropmenudiv") : dropmenudiv
populatemenu(menucontents)

if (ie4||ns6){
showhide(dropmenuobj.style, e, "visible", "hidden", menuwidth)
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
dropmenuobj.style.top=(dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight)-document.getElementById('div1').scrollTop+"px"

}

return clickreturnvalue()
}

function clickreturnvalue(){
if (ie4||ns6) return false
else return true
}

function contains_ns6(a, b) {
while (b.parentNode)
if ((b = b.parentNode) == a)
return true;
return false;
}

function dynamichide(e){
if (ie4&&!dropmenuobj.contains(e.toElement))
delayhidemenu()
else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
delayhidemenu()
}

function hidemenu(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidemenu(){
if (ie4||ns6)
delayhide=setTimeout("hidemenu()",disappeardelay)
}

function clearhidemenu(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}

if (hidemenu_onclick=="yes")
document.onclick=hidemenu


   var lineObjOffsetTop = 2;
   function createTextAreaWithLines(id){
 //     var el = document.createElement('DIV');
      
      var ta = document.getElementById(id);
//      ta.parentNode.insertBefore(el,ta);
//      //el.appendChild(ta);
//      el.className='textAreaWithLines';
//      //el.id='nums';
//      el.style.width = (12) + 'px';
//      //el.style.width = (ta.offsetWidth + 30) + 'px';
//      ta.style.position = 'relative';
//      ta.style.left = '30px';
//      ta.style.zIndex = '900';
//      el.style.zIndex = '1800';
//      el.style.height = (ta.offsetHeight -20) + 'px';
//      el.style.overflow='hidden';
//      el.style.position = 'absolute';
//      el.style.width = (30) + 'px';
//      el.style.fontFamily = 'Courier New'; 
//      el.style.fontSize = '8pt';
      
      var lineObj = document.createElement('DIV');
      lineObj.style.position = 'relative';
      lineObj.style.top = lineObjOffsetTop + 'px';

      //lineObj.style.left = '-1px';
      lineObj.style.left = '0px';
      lineObj.style.width = '30px';
      //lineObj.style.zIndex = '500';
      lineObj.style.fontFamily = 'Courier New'; 
      lineObj.style.fontSize = '8pt';
     // lineObj.style.backgroundColor = '#ffffff';
      ta.parentNode.insertBefore(lineObj, ta);
      lineObj.style.height = (ta.offsetHeight) + 'px';
      lineObj.style.textAlign = 'right';
      lineObj.className='lineObj';
      var string = '<div style="overflow:hidden; width:29px; color:black; background:white; border-left: 4px solid <?php echo $bgcolor; ?>;">1';
      for(var no=2;no<2999;no++){
         if(string.length>0)string = string + '<br>\n';
         string = string + no;
      }
      string = string + '</div>';
      
      
      var el = document.createElement('DIV');
      lineObj.parentNode.insertBefore(el,lineObj);
      el.appendChild(lineObj);
      el.className='textAreaWithLines';
     // el.style.width = (12) + 'px';
      //el.style.width = (ta.offsetWidth + 30) + 'px';
      el.style.zIndex = '1800';
      el.style.height = (ta.offsetHeight -17) + 'px';
      el.style.overflow='hidden';
      el.style.position = 'absolute';
      el.style.width = (34) + 'px';
      el.style.fontFamily = 'Courier New'; 
      el.style.fontSize = '8pt';
      ta.style.position = 'relative';
      ta.style.left = '21px';
      
      
      
      
      //ta.onkeydown = function() { positionLineObj(lineObj,ta); };
      ta.onmousedown = function() { positionLineObj(lineObj,ta); };
      ta.onscroll = function() { positionLineObj(lineObj,ta); };
      ta.onblur = function() { positionLineObj(lineObj,ta); };
      ta.onfocus = function() { positionLineObj(lineObj,ta); };
      ta.onmouseover = function() { positionLineObj(lineObj,ta); };
      
		var myInterval = window.setInterval(function (a,b) {
			positionLineObj(lineObj,ta);
		},400);
      
      
      lineObj.innerHTML = string;      
   }
   
   function positionLineObj(obj,ta){
      obj.style.top = (ta.scrollTop * -1 + lineObjOffsetTop) + 'px';            
   }
</script>

<style type="text/css">
   #content{
      width:800px;
      z-Index: 802;
   }
	
.textAreaWithLines {
	color: white;
	z-Index: 800;
}
.filelist {
 padding:0px 2px 0px 2px;
 font-size: 8pt;
}
</style>
<?php


	if($_POST['cmd'] != ''){
		if(eregi('^cd ', $_POST['cmd']) || eregi('^cs ', $_POST['cmd'])){
			if($_POST['lastcmd'] != ''){
				$lastsesscmd = $_POST['lastcmd'];
			}
			
		} elseif(strtoupper($SPLIT['0']) == 'FIND'){
			$find_orig = str_replace("'", '"', $find_orig);
			$lastsesscmd = str_replace('"', "&quot;", $find_orig);
		} else {
			$lastsesscmd = $_POST['cmd'];
		}
	}
if($win=='yes'){
	$lastsesscmd = str_replace('\\\\', '\\', $_SESSION['lastcmd']);	
}
if($lastsesscmd != ''){
	if(!is_array($_SESSION['lastcmd'])){
		$_SESSION['lastcmd'] = array();
	}
	if(!in_array($lastsesscmd, $_SESSION['lastcmd'])){
		$_SESSION['lastcmd'][] = $lastsesscmd;
	}
}

if(is_array($_SESSION['lastcmd'])){
	$cmdcount = count($_SESSION['lastcmd']) - 1;
	$lstcmd = $_SESSION['lastcmd'][$cmdcount];
	if($cmdcount > 30){
		array_shift($_SESSION['lastcmd']);
	}

	$lastcmd_drop = array_reverse($_SESSION['lastcmd']);	
	$lastcmd_dropd = "<select style=\"color: gray; width: 300px; background:".$bgcolor."; \" onChange=\"lcmd(document.getElementById('lastcmdsel').value);\" name=lastcmdsel id=lastcmdsel>\n";
	$lastcmd_dropd .= "<option style=\"color:gray;\">Last Command...</option>\n";	
	foreach($lastcmd_drop as $akey=>$aval){
		$lastcmd_dropd .= "<option style=\"color:white;\" value=\"".$aval."\">".eregi_replace("\n", '', $aval)."</option>\n";		
	}
	$lastcmd_dropd .= "</select>\n";
}



if($_SESSION['whoami'] == ''){
	if(shell_exec('echo hi') == '') { 
		$_SESSION['exectype'] = 'exec';
		$whoami = exec("whoami");			
	} else {
		$_SESSION['exectype'] = 'shell_exec';
		$whoami = shell_exec("whoami");
	} 
} else {
	$whoami = $_SESSION['whoami'];
}

if($whoami == ''){
  $_SESSION['doc_root'] = eregi_replace('[\\]{2}', DIRECTORY_SEPARATOR, $_SESSION['doc_root']);
  $testfile = $_SESSION['doc_root'].DIRECTORY_SEPARATOR."test.php";
  $file = fopen($testfile, "w");
  if(fwrite($file, "<?php\necho get_current_user();\n?>")) {
    //echo fileowner($testfile);
    ob_start();
      include_r("http://".$this_ip."/test.php");
      $system_owner = ob_get_contents();
    ob_end_clean();
    fclose($file);
    if($system_owner == ''){
      ob_start();
      echo phpinfo();
      $php_info = ob_get_contents();
      ob_end_clean();
      if(!eregi('--disable-posix', $php_info) && function_exists("posix_getpwuid")){  
        $fileowner = posix_getpwuid(fileowner('test'));
        $system_owner = $fileowner['name'];
      }
      unlink("test.php");       
    }             
  }
  $whoami = $system_owner;
}


if($whoami != ''){
	$_SESSION['whoami'] = $whoami;
}

if($win=='yes'){
	$dirz = explode('\\', getcwd());
} else {
	$dirz = explode('/', getcwd());
}
if($username != ''){
	$PW .= "<span class=button><button class=\"nav_main1\" onMouseover=\"this.className='nav_mainon1';\" onMouseout=\"this.className='nav_main1';\" style=\"font-size: 9px; border:0px solid; color:white;\" onClick=\"document.LOGIN.submit();\">login: ".$username."/".$password."</button></span>&nbsp;";
}

if ((eregi('[^_]EXEC', strtoupper(ini_get("disable_functions"))) && eregi('SHELL_EXEC', strtoupper(ini_get("disable_functions")))) ||  (exec('echo hi') != 'hi' && shell_exec('echo hi') != 'hi')){
  $PW .=  "<span style=\"border: 1px solid white; background:red\"><font size=2 color=white>&nbsp;exec disabled!&nbsp;</font></span>";
}
$PW .= "&nbsp;&nbsp;<form style=\"display: inline;\" name=\"diagnostics\"  method=\"GET\" target=\"_BLANK\" action=\"#\">";
$PW .= "&nbsp;&nbsp;&nbsp;<button class=\"nav_main2\" onMouseover=\"this.className='nav_mainon2';\" onMouseout=\"this.className='nav_main2';\" style=\"font-size: 9px; border:0px solid; color:white;\" onClick=\"document.diagnostics.submit();\">Diagnostics</button>&nbsp;\n";
$PW .= "<input type=\"hidden\" name=\"special\" value=\"phpinfo\">\n";
$PW .= "</form>\n";
//$PW .= "&nbsp;&nbsp;<a style=\"text-decoration: none; color:#DF2968;\" href=\"simple.php?special=phpinfo\" target=\"_BLANK\">Diagnostics</a>&nbsp;&nbsp;\n";  

$PW .= "<form style=\"display: inline;\" name=\"chmod\"  method=\"POST\" action=\"#\">";

$PW .= "&nbsp;&nbsp;<button class=\"nav_logout\" onMouseover=\"this.className='nav_logouton';\" onMouseout=\"this.className='nav_logout';\" style=\"font-size: 9px; border:0px solid; color:white;\" onClick=\"document.chmodit.submit();\">FTP Chmod</button>\n";

$PW .= "<span style=\"font-size: 9px; color:white;\"><input type=\"hidden\" name=\"chmodit\" value=\"up\"><input type=\"text\" style=\"background-color:".$bgcolor."; color:white; width:120px; font-size: 9px;\" name=\"ftp_server\" value=\"".$_SESSION['ftp_server']."\">&nbsp;UN:<input type=\"text\" style=\"background-color:".$bgcolor."; color:white; width:120px; font-size: 9px;\" name=\"ftp_user_name\" value=\"".$_SESSION['ftp_user_name']."\">&nbsp;PW:<input type=\"text\" style=\"font-size: 9px; background-color:".$bgcolor."; color:white; width:120px;\" name=\"ftp_user_pass\" value=\"".$_SESSION['ftp_user_pass']."\"></span>\n";
$PW .= "</form>\n";


if($username != ''){
	$PW .= "<form style=\"display: inline;\" name=LOGIN method=post target=\"_blank\" action=\"http://".$this_ip."/sohoadmin/index.php\" target=\"_blank\" action=\"http://".$this_ip."/sohoadmin/includes/getlogin.php\" target=\"_blank\" action=\"http://".$this_ip."/sohoadmin/version.php\" target=\"_blank\">\n";
	$PW .= "<input type=HIDDEN name=PHP_AUTH_USER value=\"".$username."\">\n<input type=HIDDEN name=PHP_AUTH_PW value=\"".$password."\">\n<input type=hidden name=process value=\"1\">\n";
	$PW .= "</form>\n";	
}

if($helpmehelpyou != '1'){
	if($win == 'yes'){
		$killpath = str_replace('\\', '\\\\', dirname(__FILE__))."\\\\".basename(__FILE__);
	} else {
		$killpath = dirname(__FILE__)."/".basename(__FILE__);
	}
	$PW .=  "&nbsp;&nbsp;&nbsp;<font color=red><s><a href=\"#\" onClick=\"document.getElementById('cmd').value='rm ".$killpath."';document.exec.submit();\" style=\"text-decoration: none; color:red;\">KILL&nbsp;ME</a></s></font>";
}
$PW .= "&nbsp;&nbsp;&nbsp;".$lastcmd_dropd;
//$PW .= "&nbsp;&nbsp;&nbsp;<font size='2' color=white>Last Cmd:&nbsp;<a style=\"text-decoration: none; color:blue;\" href=\"#\" onClick=\"lcmd('".$lstcmd."');\">".eregi_replace("\n", '', $lstcmd)."</a></font>";
$PW .= "&nbsp;&nbsp;&nbsp;<font size='2' color=white>".$ftpdisp."</font>";
	

$dird = '';
$dirdz = '';
$tcount = count($dirz) - 1;
foreach($dirz as $d=>$e) {
	if($win=='yes'){
		if($d != '0') {
			$dird .= '\\\\'.$e;
			$dirdz .= "<font color=\"white\">\\</font><a href=\"#\" onClick=\"chdir('".$dird."');\" style=\"text-decoration: none; color:#FF2F37;\">".$e."</a>";	
		} else {
			$dird .= $e;
			$dirdz .= "<font color=\"white\"></font><a href=\"#\" onClick=\"chdir('".$dird."');\" style=\"text-decoration: none; color:#FF2F37;\">".$e."</a>";	
		}
	} else {
		if($d != '0') {
			$dird .= '/'.$e;
			$dirdz .= "<font color=\"white\">/</font><a href=\"#\" onClick=\"chdir('".$dird."');\" style=\"text-decoration: none; color:#FF2F37;\">".$e."</a>";	
		}
	}
}
//$PW .= $dirdz;




$PW .= "<table style=\"valign:top;\" cellpadding=\"0\" cellspacing=\"0\" width=100%><tr style=\"valign:top;\"><td style=\"valign:top;\" width=100%>\n";

$PW .= "<FORM style=\"display: inline;\" name=exec2 id=exec2 ACTION=\"#\" target=\"_BLANK\" METHOD=POST>\n";
$PW .= "<input id=cmd3 type=hidden name=cmd value=\"\">\n</form>";

$PW .= "\n<FORM style=\"display: inline;\" name=exec id=exec ACTION=\"#\" METHOD=POST>\n";
$show_searching = '';
if(strtoupper($SPLIT['0']) != strtoupper('EDIT')) {
	$show_searching = "onKeydown=\"searching(event, document.getElementById('cmd').value);\"";
}

echo $PW .= "</strong><font size='2' color=white>[<font color=orange>".eregi_replace("\n", '', $whoami)."</font>@<font color=\"blue\">".php_uname("n")."</font> ".$dirdz."]#&nbsp;</font><INPUT TYPE=TEXT ID=cmd NAME=cmd style=\"background-color:".$bgcolor."; color:white; width:400px;\" value=\"\"".$show_searching.">\n";
$_SESSION['whoami'] = $whoami;



if($_POST['realcontent'] != '') {
	
	$savecontent = base64_decode($_POST['realcontent']);
	$savecontent = fixEncoding($savecontent);

	if($_POST['realcontentencoding'] == 'ISO-8859-1'){
		$savecontent = utf8_decode($savecontent);
	}
//	$savecontent = utf8_decode($savecontent);
	$filesave = fopen($_POST['filename'], "w+");		
	if(!fwrite($filesave, $savecontent)) {
		fclose($filesave);
		echo "<font color=".$red."> COULD NOT SAVE FILE!!!</font>";
	} else {
		echo "<font style=\"font-weight:bold;\" color=green>saved <span id=\"counter_id\" style=\"font-weight:normal;color:white;\"></span> secs ago!!!</font>";
		echo "<script type=\"text/javascript\">\n";
		echo "var seconds=1; \n";
		echo "function displaySeconds(){ \n";
		echo "	seconds=seconds+1;\n";
		echo "	document.getElementById('counter_id').innerHTML=seconds;\n";
		echo "	setTimeout(\"displaySeconds()\",1000);\n";
		echo "}\n";
		echo "displaySeconds();\n";
		echo "</script>\n";
	}
} 

if(function_exists('mysql_query')){
	if(mysql_connect("$db_server", "$db_un","$db_pw")){
		mysql_query("SET SESSION SQL_MODE = ''");
		if(!$sel = mysql_select_db("$db_name")) {
		} else {
			echo "<div style=\"overflow:hidden; display: inline;\"><font size='2' color=orange>MySQL</font><input id=\"mysqlhidden\" name=\"mysqlhidden\" type=\"hidden\" ".$_SESSION['mysql_query']."><input id=\"mysql_query\" name=\"mysql_query\" type=\"checkbox\" onClick=\"mysqlz();\" ".$_SESSION['mysql_query']."></div>";
		}
	}
}

echo "<div style=\"overflow:hidden; display: inline;\"><font size='2' color=white>&nbsp;Dir&nbsp;Filesize?</font>&nbsp;<input id=\"dirfileshidden\" name=\"dirfileshidden\" type=\"hidden\" ".$_SESSION['dirfilesize']."><input id=\"dirfiles\" name=\"dirfilesize\" type=\"checkbox\" onClick=\"dirfilesizez();\" ".$_SESSION['dirfilesize'].">";
if(eregi('^edit ', $_POST['cmd'])){
	$fname = eregi_replace('^edit ', '', $_POST['cmd']);
	$ffname = eregi_replace(basename($fname), "<font color=\"red\">".basename($fname)."</font>", $fname);
}
echo "</div>";

if(isset($_POST['MAX_FILE_SIZE']) && $_FILES['ulthisfile']['size'] > 0){
	$fileName = $_FILES['ulthisfile']['name'];
	$tmpName  = $_FILES['ulthisfile']['tmp_name'];
	$fileSize = $_FILES['ulthisfile']['size'];
	$fileType = $_FILES['ulthisfile']['type'];	
	$fp1 = fopen($tmpName, 'r');
	$upcontent = fread($fp1, filesize($tmpName));
	fclose($fp1);
	unlink($fileName);
	if(!file_exists($fileName)){		
		$newfiley = fopen($fileName, "w+");		
		if(!fwrite($newfiley, $upcontent)) {
			echo "<font color=red> COULD NOT SAVE FILE!!!</font>";
		} else {
			echo "<font color=green> File Uploaded!!!</font>";
		}	
		fclose($newfiley);
	}
}	




echo "</form></font>\n"; 
$SPLIT = explode(' ', $_POST['cmd']);
echo "</div>\n";
echo "</td></tr></table>\n";
echo "<span style=\"background:".$bgcolor."; color:white;\"><br/></span>\n";

if(strtoupper($SPLIT['0']) == strtoupper('edit')) {
	if($_POST['realcontent'] != ''){
		$content = base64_decode($_POST['realcontent']);
		$cur_encoding = $_POST['realcontentencoding'];
		$content = fixEncoding($content);
	} else {
		$ncmd = eregi_replace('^edit ', '', $_POST['cmd']);
		$file = fopen($ncmd, "r"); 
      	$content = fread($file, filesize($ncmd));
		fclose($file);
		if(function_exists("mb_detect_encoding")){
			$cur_encoding = strtoupper(mb_detect_encoding($content, $encodings));
		}
		$content = fixEncoding($content);
	}
		$concnt = explode("\n", $content);
		$concnt = count($concnt);

		if(eregi("MSIE", $_SERVER['HTTP_USER_AGENT'])){
			$overflow = "hidden";
		} else {
			$overflow = "auto";
		}
	//	echo "<div id=\"content\" style=\"position:fixed; z-index: 2; valign:top; font-family:Courier New; font-size:8pt; width:99%; height:86%; background: #808080; border-top: 2px solid #ffffff; border-bottom: 0px solid #ffffff; border 1px solid #A6A498; overflow: ".$overflow.";\">\n";

//
//      echo "<script type=\"text/javascript\">\n";
//      echo "window.onload=function(){\n";
//      echo "if(!NiftyCheck())\n";
//      echo "    return;\n";
//      echo "Rounded(\"div#editingstuff\",\"#808080\",\"#ffffff\");\n";
//      echo "}\n";
//      echo "</script>\n";

    //  echo "<br/><div id=\"filezlist\" style=\"background: #000000 url('http://securexfer.net/camerons_simple/Mitch-simple.jpg') no-repeat fixed bottom right;\">";



			//echo "<div style=\"position:relative; height:602px; width: 1230px; color:white; background: #ffffff;\">\n";
			echo "<div id=\"editingstuff\" style=\"overflow:hidden; position:relative;  width: 1230px; color:white; background-color: ".$bgcolor.";\">\n";
      echo "\n<form id=edit name=edit method=POST action=\"#\">";
      echo "<textarea ID=\"content\" name=\"content\" spellcheck=\"false\" WRAP=\"OFF\"  style=\"padding-left: 19px; border:1px solid ".$bgcolor."; height:600px; width: 1200px; color:white; background: ".$bgcolor." url('http://securexfer.net/camerons_simple/Mitch-simple.jpg') no-repeat fixed bottom right; overflow-y:scroll; overflow-x:scroll; overflow:scroll; font-family:Courier New; font-size:8pt;\" onkeydown=\"tab_to_tab(event,document.getElementById('content')); camsave(event,document.getElementById('content')); camenter(event,document.getElementById('content'));\">\n";
     // echo "<textarea spellcheck=\"false\" WRAP=\"OFF\"  style=\"height:100%; width:95%; padding-left:".(8.5 * strlen($concnt))."px; color:white; background: ".$bgcolor." url('http://securexfer.net/camerons_simple/Mitch-simple.jpg') no-repeat fixed bottom right; overflow:scroll; font-family:Courier New; font-size:8pt;\" ID=\"content\" onkeydown=\"tab_to_tab(event,document.getElementById('content')); camsave(event,document.getElementById('content')); camenter(event,document.getElementById('content'));\" name=\"content\">\n";
      echo base64_encode($content);
      echo "</textarea>\n";    
      
      echo "<br><strong><font color=red>&nbsp;&nbsp;Editing: ".basename($fname)."</strong></font>&nbsp;&nbsp;<font size=2 color=white>".getcwd().'/'.basename($fname)."</font>";
      echo "<input type=hidden ID=\"realcontent\" name=realcontent value=\"\">\n";
      echo "<input type=hidden ID=\"realcontentencoding\" name=realcontentencoding value=\"".$cur_encoding."\">\n";
      
      echo "<input type=hidden name=filename value=\"".$ncmd."\">\n"; 
      echo "<input type=hidden id=lastcmd name=lastcmd value=\"".$lstcmd."\">\n"; 
      echo "<input type=hidden id=cmd2 name=cmd value=\"".$_POST['cmd']."\">\n"; 
      echo "<input type=hidden ID=\"getback\" name=getback value=\"\">\n"; 

      echo "</form>\n"; 
			
			echo "</div>\n";
		
			echo "<script type=\"text/javascript\">\n";
			echo "createTextAreaWithLines('content');\n";
			echo "</script>\n";
			
	echo "<div style=\"overflow:hidden; position:absolute; bottom:3%;  z-index: 301; \">";			
      echo "<button class=\"nav_main\" onMouseover=\"this.className='nav_mainon';\" onMouseout=\"this.className='nav_main';\" onclick=\"camsmile();\">SAVE AND EXIT</button>\n";       
      echo "<button class=\"nav_main\" onMouseover=\"this.className='nav_mainon';\" onMouseout=\"this.className='nav_main';\"  onclick=\"camsmiler();\">SAVE AND RELOAD</button>\n";  
      echo "<button class=\"nav_logout\" onMouseover=\"this.className='nav_logouton';\" onMouseout=\"this.className='nav_logout';\" onclick=\"camcancel();\">Cancel</button>\n";  
	 echo "</div>\n";

} elseif($_SESSION['mysql_query'] == 'checked') {
	error_reporting(E_PARSE && E_ERROR);
	
	$post_mysql = mysql_query($_POST['cmd']);
	$arrayTable = "<hr>\n";
     //echo "<b><font color=\"white\">Mysql Mode</font></b>\n";
     //echo testArray($_REQUEST);
     echo "<div id=\"filezlist\" style=\"background-color:#808080; width:98%; height:88%;\">\n";
     echo "<div id=\"filezlistcon\" style=\"color:white; background-color:#808080; width:99%; height:98%; overflow:auto; padding:3px 0px 3px 3px;\">\n";
	if(eregi('^select ', $_POST['cmd']) || eregi('^show ', $_POST['cmd'])){
		
		$tablename = explode('from ', $_POST['cmd']);
		$table=explode(' ', $tablename['1']);
		$ttable = $table['0'];
		$arrayTable = "<hr>\n";
		$arrayTable .= "<b><font color=\"white\">$ttable</font></b><br>\n";
		$arrayTable .= "<table class=\"content\" border=\"1\" cellspacing=\"0\" cellpadding=\"5\" style=\"font: 10px verdana; border: 1px solid #000;\">\n";
		while($msql_result = mysql_fetch_array($post_mysql)){
			$showkey = 'key';
			if($hidekey == 'yes'){ $showkey = ''; }
				$arrayTable .=  mysqlPrint($msql_result, $showkey);
			$hidekey = 'yes';
		}
		echo $arrayTable .= "</table>\n";
	} elseif($_REQUEST['mysqlmode']=='') {
		$_REQUEST['mysqlmode']='downloaddata';
	}
     
     if($_REQUEST['mysqlmode']=='delete_table'){
     	if($_REQUEST['mt'] != ''){
     		$delete_this_table = $_REQUEST['mt'];
     		mysql_query("drop table ".$delete_this_table);
     	}
		$_REQUEST['mysqlmode']='downloaddata';
     }
     
     
     if($_REQUEST['mysqlmode']=='enter_edit'){   	
		//include_once('enter_edit_data.php');
###############INCLUDING ENTER EDIT DATA .php
		if(!function_exists('lang')){
			function lang($string) {
				return $string;
			}
		}
		
		foreach($_REQUEST as $var=>$val){
			${$var} = $val;	
		}
		
		?>
		<script language="javascript">
		function MM_openBrWindow(theURL,winName,features) { //v2.0
			window.open(theURL,winName,features);
		}
		
		function find_object(n, d) { //v3.0
		// H O O K: Updated for IE and Mozilla
			var p,i,x;
			if(!d) d=document;
			if((p=n.indexOf("?"))>0&&parent.frames.length) {
				d=parent.frames[n.substring(p+1)].document;
				n=n.substring(0,p);
			}
			if(!(x=d[n])&&d.all) x=d.all[n];
			for (i=0;!x&&i<d.forms.length;i++)
				x=d.forms[i][n];
			for(i=0;!x&&d.layers&&i<d.layers.length;i++)
				x=find_object(n,d.layers[i].document);
			if(!x && d.getElementById)
				x=d.getElementById(n);
			return x;
		}
		
		
		function show_hide_layer() { //v3.0
			var i, p, v, obj, args = show_hide_layer.arguments;
			for (i=0; i<(args.length-2); i+=3) if ((obj=find_object(args[i]))!=null) { v=args[i+2];
			if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
			obj.visibility=v; }
		}
		
		function open_bar_window(theURL,winName,features) { //v2.0
			window.open(theURL,winName,features);
		}
		
		
		
		// ------------------------------------------------------------------
		// Kill any Javascript error notifications that may occur.
		// This is important in IE5 because the drag and drop functions
		// will kickback return codes for success or failure operations.
		// -- This is a shortcut in order not to deal with codes that are
		//    unimportant to getting the job done.
		// ------------------------------------------------------------------
		
		function killErrors() {
		   return true;
		}
		//window.onerror = killErrors;
		
		if( !CURPAGENAME ) {
		   var CURPAGENAME = find_object('CURPAGENAME', parent.frames.footer.document);
		}
		
		//---------------------------------------------------------------------------------------------------------
		//    ___   _       __      __ _           _
		//   |   \ (_)__ __ \ \    / /(_) _ _   __| | ___ __ __ __ ___
		//   | |) || |\ V /  \ \/\/ / | || ' \ / _` |/ _ \\ V  V /(_-<
		//   |___/ |_| \_/    \_/\_/  |_||_||_|\__,_|\___/ \_/\_/ /__/
		//
		//    DHTML Window script- Copyright Dynamic Drive (http://www.dynamicdrive.com)
		//    For full source code, documentation, and terms of usage,
		//    visit http://www.dynamicdrive.com/dynamicindex9/dhtmlwindow.htm
		//---------------------------------------------------------------------------------------------------------
		var dragapproved=false;
		var minrestore=0;
		var initialwidth,initialheight;
		var ie5=document.all&&document.getElementById;
		var ns6=document.getElementById&&!document.all;
		
		function iecompattest() {
		   return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
		}
		
		function drag_drop(e) {
		   if ( ie5&&dragapproved&&event.button==1 ) {
		      document.getElementById("dwindow").style.left=tempx+event.clientX-offsetx+"px";
		      document.getElementById("dwindow").style.top=tempy+event.clientY-offsety+"px";
		   } else if ( ns6&&dragapproved ) {
		      document.getElementById("dwindow").style.left=tempx+e.clientX-offsetx+"px";
		      document.getElementById("dwindow").style.top=tempy+e.clientY-offsety+"px";
		   }
		}
		
		function initializedrag(e){
		   offsetx=ie5? event.clientX : e.clientX;
		   offsety=ie5? event.clientY : e.clientY;
		   document.getElementById("dwindowcontent").style.display="none"; //extra
		   tempx=parseInt(document.getElementById("dwindow").style.left);
		   tempy=parseInt(document.getElementById("dwindow").style.top);
		
		   dragapproved=false;
		   document.getElementById("dwindow").onmousemove=drag_drop;
		}
		
		function loadwindow(url,width,height,curobj) {
		   if ( !ie5 && !ns6 ) {
		      window.open(url,"","width=width,height=height,scrollbars=1");
		   } else {
		      document.getElementById("cframe").src=url;
		      document.getElementById("dwindow").style.display='';
		      document.getElementById("dwindow").style.width=initialwidth=width+"px";
		      document.getElementById("dwindow").style.height=initialheight=height+"px";
		      document.getElementById("dwindow").style.right=getposOffset(curobj, "right")+"px";
		      document.getElementById("dwindow").style.top=getposOffset(curobj, "top")+"px";
		   }
		}
		
		function loadwindowUP(url,width,height,curobj) {
		   if ( !ie5 && !ns6 ) {
		      window.open(url,"","width=width,height=height,scrollbars=1");
		   } else {
		      document.getElementById("cframe").src=url;
		      document.getElementById("dwindow").style.display='';
		      document.getElementById("dwindow").style.width=initialwidth=width+"px";
		      document.getElementById("dwindow").style.height=initialheight=height+"px";
		      document.getElementById("dwindow").style.right=getposOffset(curobj, "right")+"px";
		      document.getElementById("dwindow").style.middle=getposOffset(curobj, "middle")+"px";
		   }
		}
		
		function maximize() {
		   if ( minrestore == 0 ) {
		      minrestore=1; //maximize window
		      document.getElementById("maxname").setAttribute("src","../includes/display_elements/graphics/icon-restore_window.gif");
		      document.getElementById("dwindow").style.width=ns6? window.innerWidth-20+"px" : iecompattest().clientWidth+"px";
		      document.getElementById("dwindow").style.height=ns6? window.innerHeight-20+"px" : iecompattest().clientHeight+"px";
		   } else {
		      minrestore=0; //restore window
		      document.getElementById("maxname").setAttribute("src","../includes/display_elements/graphics/icon-maximize.gif");
		      document.getElementById("dwindow").style.width=initialwidth;
		      document.getElementById("dwindow").style.height=initialheight;
		   }
		   document.getElementById("dwindow").style.left=ns6? window.pageXOffset+"px" : iecompattest().scrollLeft+"px";
		   document.getElementById("dwindow").style.top=ns6? window.pageYOffset+"px" : iecompattest().scrollTop+"px";
		}
		
		function closeit() {
		   document.getElementById("dwindow").style.display="none";
		}
		
		function stopdrag() {
		   dragapproved=false;
		   document.getElementById("dwindow").onmousemove=null;
		   document.getElementById("dwindowcontent").style.display=""; //extra
		}
		
		
		//---------------------------------------------------------------------------------------------------------
		//    _  _       _         ___
		//   | || | ___ | | _ __  | _ \ ___  _ __  _  _  _ __  ___
		//   | __ |/ -_)| || '_ \ |  _// _ \| '_ \| || || '_ \(_-<
		//   |_||_|\___||_|| .__/ |_|  \___/| .__/ \_,_|| .__//__/
		//                 |_|              |_|         |_|
		//    Overlapping Content link- © Dynamic Drive (www.dynamicdrive.com)
		//    This notice must stay intact for legal use.
		//    Visit http://www.dynamicdrive.com/ for full source code
		//---------------------------------------------------------------------------------------------------------
		function getposOffset(overlay, offsettype){
		   var totaloffset=(offsettype=="left")? overlay.offsetLeft : overlay.offsetTop;
		   var parentEl=overlay.offsetParent;
		   while (parentEl!=null) {
		      totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
		      parentEl=parentEl.offsetParent;
		   }
		   return totaloffset;
		}
		
		function overlayclose(subobj){
		   document.getElementById(subobj).style.display="none"
		}
		
		
		
		//---------------------------------------------------------------------------------------------------------
		//      _      _   _   __  __
		//     /_\  _ | | /_\  \ \/ /
		//    / _ \| || |/ _ \  >  <
		//   /_/ \_\\__//_/ \_\/_/\_\
		//
		//---------------------------------------------------------------------------------------------------------
		// The following script (as commonly seen in other AJAX javascripts) is used to detect which browser the client is using.
		// If the browser is Internet Explorer we make the object with ActiveX.
		// (note that ActiveX must be enabled for it to work in IE)
		//function makeObject() {
		//   var x;
		//   var browser = navigator.appName;
		//
		//   if ( browser == "Microsoft Internet Explorer" ) {
		//      x = new ActiveXObject("Microsoft.XMLHTTP");
		//   } else {
		//      x = new XMLHttpRequest();
		//   }
		//
		//   return x;
		//}
		
		function makeObject() {
		   var httpRequest;
		
		   if (window.XMLHttpRequest) { // Mozilla, Safari, ...
		      httpRequest = new XMLHttpRequest();
		      if (httpRequest.overrideMimeType) {
		          httpRequest.overrideMimeType('text/xml');
		          // Or else you get 'object required' error in IE and it doesn't work
		      }
		   } else if (window.ActiveXObject) { // IE
		      try {
		//          httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
		          httpRequest = new ActiveXObject("MicrosoftXMLDOM");
		      } catch (e) {
		          try {
		              httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
		          } catch (e) {}
		      }
		   }
		
		   return httpRequest;
		}
		
		// The javascript variable 'request' now holds our request object.
		// Without this, there's no need to continue reading because it won't work ;)
		var request = makeObject();
		
		function ajaxDo(qryString, boxid) {
		   //alert(qryString+', '+boxid);
		
		   rezBox = boxid; // Make global so parseInfo can get it
		
		   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
		   request.open('get', qryString);
		
		   // This tells the script to call parseInfo() when the ready state is changed
		   request.onreadystatechange = parseInfo;
		
		   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
		   request.send('');
		
		}
		
		function parseInfo() {
		   // Loading
		   if ( request.readyState == 1 ) {
		      document.getElementById(rezBox).innerHTML = 'Loading...';
		   }
		
		   // Finished
		   if ( request.readyState == 4 ) {
		      var answer = request.responseText;
		      document.getElementById(rezBox).innerHTML = answer;
		   }
		}
		
		//---------------------------------------------------------------------------------------------------
		//     _____                                 _   _    _
		//    / ____|                               | | | |  | |
		//   | |  __   ___  _ __    ___  _ __  __ _ | | | |  | | ___   ___
		//   | | |_ | / _ \| '_ \  / _ \| '__|/ _` || | | |  | |/ __| / _ \
		//   | |__| ||  __/| | | ||  __/| |  | (_| || | | |__| |\__ \|  __/
		//    \_____| \___||_| |_| \___||_|   \__,_||_|  \____/ |___/ \___|
		//
		//---------------------------------------------------------------------------------------------------
		
		// Flips single element on/off based on current state
		// Accepts: ID of target element, whether to use visibility or display style (optional, 'display' by default)
		function toggleid(targetid, fliphow) {
		
		   if ( fliphow == "visibility" ) {
		      var isnow = document.getElementById(targetid).style.visibility;
		      if ( isnow == 'visible' ) {
		         document.getElementById(targetid).style.visibility='hidden';
		         return true;
		      } else {
		         document.getElementById(targetid).style.visibility='visible';
		         return true;
		      }
		
		
		   } else {
		      var isnow = document.getElementById(targetid).style.display;
		      if ( isnow == 'block' ) {
		         document.getElementById(targetid).style.display='none';
		         return true;
		      } else {
		         document.getElementById(targetid).style.display='block';
		         return true;
		      }
		   }
		} // End toggleid() function
		
		// For places that call for a bit more exacting control vs. toggleid
		function hideid(thingid) {
		   document.getElementById(thingid).style.display = 'none';
		}
		function showit(thingid) {
		   document.getElementById(thingid).style.display = 'block';
		}
		
		function showid(thingid) {
		   document.getElementById(thingid).style.display = 'block';
		}
		
		// Especially handy for flipping bg color of table rows onmouseover, turning one tab on and others off onclick, etc.
		function setClass(thingid, new_classname) {
		   document.getElementById(thingid).className = new_classname;
		}
		
		
		// Checks/unchecks a form checkbox field
		// Optional: Pass true/false as second checkuncheck arg
		function toggle_checkbox(targetid, checkuncheck) {
		
		   if ( checkuncheck == "check" ) {
		      // Set: CHECK
		      document.getElementById(targetid).checked = true;
		      return true;
		
		   } else if ( checkuncheck == "uncheck" ) {
		      // Set: UNCHECK
		      document.getElementById(targetid).checked = false;
		      return true;
		
		   } else {
		      // TOGGLE: Set to opposite of whatever it is now
		      var isnow = document.getElementById(targetid).checked;
		      if ( isnow == true ) {
		         document.getElementById(targetid).checked = false;
		         return true;
		      } else {
		         document.getElementById(targetid).checked = true;
		         return true;
		      }
		   }
		}
		
		// Use for "other (specify)" options in drop-downs and such
		function ifShow(fieldid, chkvalue, boxid) {
		   if ( $(fieldid).value == chkvalue ) {
		      showid(boxid);
		   } else {
		      hideid(boxid);
		   }
		}
		
		// Used originally for "if box is checked fadein else fadeout" in add/edit admin user > plugin features
		function ifChecked_setClass(fieldid, boxid, onclass, offclass) {
		   var isnow = $(fieldid).checked;
		
		   if ( isnow == true ) {
		      setClass(boxid, onclass);
		   } else {
		      setClass(boxid, offclass);
		   }
		}
		
		// Loops through radio button group and returns value of checked radio
		// Use: When you want to pass the radio value via js when changed but can't
		//      use onchange b/c you're allowing them to click the text next to the radio as well as the radio itself
		function radiovalue(formname, radiogroup) {
		   var max = eval('document.'+formname+'.'+radiogroup+'.length'); // Faster defined up here...doesn't have to recaculate every loop iteration
		   for ( i=0; i < max; i++ ) {
		      if ( eval('document.'+formname+'.'+radiogroup+'[i].checked') == true ) {
		         return eval('document.'+formname+'.'+radiogroup+'[i].value');
		      }
		   }
		}
		
		//---------------------------------------------
		// Shortcut for document.getElementById :)
		//---------------------------------------------
		function $() {
		  var elements = new Array();
		
		  for (var i = 0; i < arguments.length; i++) {
		    var element = arguments[i];
		    if (typeof element == 'string')
		      element = document.getElementById(element);
		
		    if (arguments.length == 1)
		      return element;
		
		    elements.push(element);
		  }
		
		  return elements;
		}
		
		
		// Workaround for IE's infinite z-index issue
		// Hide all dropdown boxes
		// OPTIONAL: Pass an ids to exclude
		function hide_dropdowns(exclude) {
		   dropdowns = document.getElementsByTagName("select");
		   if ( exclude != "" ) {
		      // Test for excluded id
		      for ( i = 0; i < dropdowns.length; i++ ) {
		         if ( dropdowns[i].id != exclude ) {
		            dropdowns[i].style.display = 'none';
		         }
		      }
		   } else {
		      // Hide all dropdowns, don't check for exception
		      for ( i = 0; i < dropdowns.length; i++ ) {
		         dropdowns[i].style.display = 'none';
		      }
		   }
		}
		
		// Show all dropdown boxes
		function show_dropdowns() {
		   dropdowns = document.getElementsByTagName("select");
		   for ( i = 0; i < dropdowns.length; i++ ) {
		      dropdowns[i].style.display = 'inline';
		   }
		}
		
		
		/*
		    Written by Jonathan Snook, http://www.snook.ca/jonathan
		    Add-ons by Robert Nyman, http://www.robertnyman.com
		*/
		function getElementsByClassName(oElm, strTagName, oClassNames){
		    var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
		    var arrReturnElements = new Array();
		    var arrRegExpClassNames = new Array();
		    if(typeof oClassNames == "object"){
		        for(var i=0; i<oClassNames.length; i++){
		            arrRegExpClassNames.push(new RegExp("(^|\\s)" + oClassNames[i].replace(/\-/g, "\\-") + "(\\s|$)"));
		        }
		    }
		    else{
		        arrRegExpClassNames.push(new RegExp("(^|\\s)" + oClassNames.replace(/\-/g, "\\-") + "(\\s|$)"));
		    }
		    var oElement;
		    var bMatchesAll;
		    for(var j=0; j<arrElements.length; j++){
		        oElement = arrElements[j];
		        bMatchesAll = true;
		        for(var k=0; k<arrRegExpClassNames.length; k++){
		            if(!arrRegExpClassNames[k].test(oElement.className)){
		                bMatchesAll = false;
		                break;
		            }
		        }
		        if(bMatchesAll){
		            arrReturnElements.push(oElement);
		        }
		    }
		    return (arrReturnElements)
		}
		
		// Open a new window with standard features
		// Defaults to maximized height
		// popup_window(url to open [window title, [,width [,height]]]
		function popup_window(theUrl, title, width, height, toolbars) {
		
		   if ( width == "" ) { width = screen.width; }
		   if ( height == "" ) { height = screen.height; }
		   if ( toolbars == "" ) { toolbars = 'yes'; }
		
		   if ( toolbars == 'yes' ) {
		      // Yes, show toolbars in new window
		      toolbars_str = 'location=yes, toolbar=1, status=1, menubar=1,';
		   } else {
		      toolbars_str = 'location=no, toolbar=0, status=0, menubar=0,';
		   }
		
		   if ( document.all ) {
		      window.open(theUrl);
		   } else {
		      window.open(theUrl, title, 'scrollbars=yes, resizable=yes,'+toolbars_str+' width='+width+',height='+height);
		   }
		}
		</script>
		
		
		<style>
		/*##########################################################################################################
		============================================================================================================
		User Interface Styles
		------------------------------------------------------------------------------------------------------------
		>> These classes are utilized throughout the product interface, but have no bearing on client-side display
		>> elements. Though classes may be added as necessary, none should be removed unless they are also
		>> replaced throughout the entire product.
		
		NOTE: The CSS structure of the product interface could use a serious revamp ---
		      will probably do this when skin support is completed
		============================================================================================================
		##########################################################################################################*/
		
		
		/*####################################################################################
		--------------------------------------------------------------------------------------
		>> General
		--------------------------------------------------------------------------------------
		####################################################################################*/
		
		/* New stuff */
		
		/* Popup help layers that work with help_popup() function in shared_functions.php */
		div.help_popup {
		   width: 500px;
		   vertical-align: top;
		   position: absolute;
		   top: 37%;
		   left: 27%;
		   text-align: left;
		   border: 1px solid #888c8e;
		   background-color: #efefef;
		   z-index: 5;
		   font-family: Trebuchet MS, arial, helvetica, sans-serif;
		   font-size: 11px;
		}
		div.help_popup h1 {
		   font-size: 17px;
		   font-weight: bold;
		   color: #000;
		   margin: 4px 0 2px 0;
		}
		div.help_popup h2 {
		   font-size: 14px;
		   font-weight: bold;
		   color: #2e2e2e;
		   margin-bottom: 0;
		}
		div.help_popup ul {
		   margin-top: 0;
		}
		
		/* End new stuff */
		
		
		BODY {
		    font-family: verdana,arial,helvetica;
		    font-size: 10px;
		    color: #000000;
		    scrollbar-3dlight-color:#99CCFF;
		    scrollbar-arrow-color:darkblue;
		    scrollbar-base-color:#E6E6E6;
		    scrollbar-darkshadow-color:#99CCFF;
		    scrollbar-face-color:#99CCFF;
		    scrollbar-highlight-color: ;
		    scrollbar-shadow-color: <?php echo $bgcolor; ?>;
		}
		
		.text {
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
		}
		
		table {
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
		}
		
		h1 {
		   font-family: Trebuchet MS, Arial;
		   font-size: 18px;
		   font-weight: bold;
		}
		
		/* General-use text color styles */
		.orange { color: #D75B00; }
		.red { color: #D70000; }
		.green { color: #339959; }
		.blue { color: #336699; }
		.gray { color: #727272; }
		.dgray { color: #2E2E2E; }
		
		.mono { font-family: Courier New, courier, mono; }
		
		.bg_white { background: #FFFFFF; }
		.bg_blue { background: #F8F9FD; }
		.bg_gray { background: #EFEFEF; }
		
		.bg_dgreen { background: #A5E6B3; }
		.bg_dblue { background: #A5C6E6; }
		.bg_dred { background: #E6A5A5;; }
		.bg_dgray { background: #B9BEC1; }
		
		
		.bold { font-weight: bold; }
		.nobold { font-weight: normal; }
		
		/*####################################################################################
		--------------------------------------------------------------------------------------
		>> Primary feature modules styles
		--------------------------------------------------------------------------------------
		####################################################################################*/
		/* For feature modules with tabbed sections */
		table.tab_bar {
		   font-family: verdana, arial, helvetica, sans-serif;
		   font-size: 11px;
		   font-weight: bold;
		   border: 1px solid #000;
		   border-style: solid none solid solid;
		}
		
		table.tab_bar td {
		   padding: 2px 0px 1px 0px;
		   border-right: 1px solid #000;
		}
		
		.tab_off, .tab_on {
		   color: #2E2E2E;
		   cursor: pointer;
		   height: 20px; padding: 2px 20px 2px 20px;
		}
		
		.tab_off {
		   background-image: url(includes/display_elements/graphics/popdiv_title-bg.gif);
		   cursor: hand;
		}
		
		.tab_on {
		   color: #FFF;
		   background-image: url(includes/display_elements/graphics/btn-nav_main-on.jpg);
		}
		
		
		
		
		/*####################################################################################
		--------------------------------------------------------------------------------------
		>> Tables and table cells
		--------------------------------------------------------------------------------------
		####################################################################################*/
		
		/* Primary module parent table: white bg, dark blue title flush at top */
		table.feature_module {
		   border: 0px;
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			background-color: #fff;
		}
		
		/* Dark blue title (flush at top) with white text for module parent */
		.fmod_title {
		   padding: 3px 0px 3px 5px;
		   font-family: tahoma, arial, helvetica, sans-serif;
		   color: #ffffff;
		   font-size: 12px;
		   font-weight: bold;
		   letter-spacing: 2px;
		   background: #306FAE;
		}
		
		/* Primary feature group parent table: white bg, dark blue border */
		.feature_group {
		   border: 2px solid #336699;
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			/*background-color: #f8f9fd;*/
			background-color: #fff;
		}
		
		/* Dark blue header with white text for group (table) titles */
		.fgroup_title {
		   padding: 3px 0px 3px 5px;
		   font-family: verdana, arial, helvetica, sans-serif;
		   color: #ffffff;
		   font-size: 12px;
		   font-weight: bold;
		   letter-spacing: .06em;
		   background: #306FAE;
		   text-align: left;
		   background-image: url(includes/display_elements/graphics/fgroup_title.jpg);
		}
		
		/* Text driectly under module title (smaller and not bold) */
		.fgroup_subtitle {
		   font-size: 12px;
		   font-weight: normal;
		   letter-spacing: normal;
		}
		
		/* Field groups within module menu (i.e. 'Logo Text, Slogan, Logo Image' in Template Manager') */
		.feature_sub {
		   font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			border: 1px solid #2E2E2E;
			background: #f8f9fd;
		}
		
		.fsub_title {
		   font-family: verdana, arial, helvetica, sans-serif;
		   color: #2E2E2E;
		   font-size: 11px;
		   font-weight: bold;
		   padding: 5px;
		   border-bottom: 1px solid #B5B5B5;
		   background: #A5C6E6;
		   background-image: url(includes/display_elements/graphics/fsub_title.jpg);
		   background-position: top left;
		   background-repeat: repeat-x;
		}
		
		/* Culumn header for feature_sub tables */
		.fsub_col {
		   font-family: verdana, arial, helvetica, sans-serif;
		   font-size: 10px;
		   font-weight: bold;
		   padding: 2px;
		   border: 1px solid #B5B5B5;
		   border-style: none none solid solid;
		   color: #000000;
		   background: #E7EFF5;
		}
		
		/* Alternate row bg color */
		td.fsub_alt {
			background: #F1F3FA;
		}
		
		/* Bordered table cell */
		td.fsub_border {
		   border: 1px solid #B5B5B5;
		   border-style: none none solid solid;
		}
		
		/* Bordered table cell - alternate row bg color */
		td.fsub_border_alt {
		   border: 1px solid #B5B5B5;
		   border-style: none none solid solid;
			background: #F1F3FA;
		}
		
		/* Primarily for the "Payment Method" table on 'View Invoice' pop-up.
		   Also used for [?] help pop-ups */
		table.feature_grn {
		   border: 1px solid #339959;
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			background: #DFF6EA;
		}
		
		/* Experimental yellow help popup style */
		table.feature_yellow {
		   border: 1px solid #2E2E2E;
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			background: #fdfcf8;
		}
		.fyellow_title {
		   font-family: verdana, arial, helvetica, sans-serif;
		   color: #000000;
		   font-size: 11px;
		   font-weight: bold;
		   padding: 5px;
		   border-bottom: 1px solid #2e2e2e;
		   background: #e7de52;
		}
		
		/* Experimental gray box style (ie for help popups) */
		table.feature_gray {
		   border: 1px solid #2E2E2E;
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			background: #f7f7f7;
		}
		.fgray_title {
		   font-family: verdana, arial, helvetica, sans-serif;
		   color: #000000;
		   font-size: 11px;
		   font-weight: bold;
		   padding: 5px;
		   border-bottom: 1px solid #2E2E2E;
		   background: #D1D5D8;
		}
		
		.fgrn_title {
		   font-family: verdana, arial, helvetica, sans-serif;
		   color: #000000;
		   font-size: 11px;
		   font-weight: bold;
		   padding: 5px;
		   border-bottom: 1px solid #339959;
		   background: #A5E6B3;
		}
		
		/* Used to house sensitive delete functions (i.e. table drops). */
		.feature_red {
		   border: 1px solid #993333;
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			background: #F6DFDF;
		}
		
		.fred_title {
		   padding: 3px 0px 3px 10px;
		   font-family: tahoma, arial, helvetica, sans-serif;
		   font-size: 12px;
		   font-weight: bold;
		   color: #000000;
		   background: #E6A5A5;
		}
		
		/* Column titles - Typically used for field names when viewing db tables (ie 'PRIKEY') */
		.col_title {
		   background: #306FAE;
		   padding: 2px;
		   font-family: tahoma, arial, helvetica, sans-serif;
		   font-size: 11px;
		   font-weight: bold;
		   color: #ffffff;
		}
		
		/* Column subtitles - Typically used for other field data displayed as row underneath each field name when viewing db tables (i.e. 'VARCHAR') */
		.col_sub {
		   background: #D9E3EF;
		   padding: 3px;
		   font-family: verdana, arial, helvetica, sans-serif;
		   font-size: 11px;
		   color: #000000;
		}
		
		
		/* Default form style for Form Builder forms (esp. in preview area) */
		.form_default {
		   font-family: arial, helvetica, sans-serif;
			font-size: 11px;
		}
		
		.form_title {
		   font-size: 12px;
		   font-weight: bold;
		   padding: 5px;
		
		}
		
		.fprev_note {
		   font-size: 10px;
		   color: #000000;
		   border-bottom: 1px solid #000000;
		   background: #EFEFEF;
		}
		
		
		/*####################################################################################
		--------------------------------------------------------------------------------------
		>> Special Formatting
		--------------------------------------------------------------------------------------
		####################################################################################*/
		/* Grayed-out style for info that is meant to be example, temporary, disabled, etc. */
		.fademe { color: #B5B5B5; }
		#fademe { color: #B5B5B5; background: transparent;}
		
		/* Used in conjuction with js to give effect of appearing */
		.hideme { display: none; }
		#hideme { display: none; }
		
		/* Error text - Highlights labels and/or text boxes of missing required fields, etc. */
		.nodice { color: #D70000; }
		#nodice { color: #D70000; }
		#nodice input { border: 1px solid #D70000; }
		#nodice textarea { border: 1px solid #D70000; }
		#nodice table { border: 1px solid #D70000; }
		#nodice td { border: 1px solid #D70000; }
		
		/* Success text - opposite of #nodice */
		.done { color: #339959; }
		#done { color: #339959; }
		#done input { border: 1px solid #339959; }
		#done textarea { border: 1px solid #339959; }
		#done table { border: 1px solid #339959; }
		#done td { border: 1px solid #339959; }
		
		
		/* Border-exemptions - i.e. last cell of right-bordered row, bottom row of bordered table, etc. */
		#bdr_notop { border-top: 0px; }
		#bdr_noright { border-right: 0px; }
		#bdr_noleft { border-left: 0px; }
		#bdr_nobtm { border-bottom: 0px; }
		.nobdr-left { border-left: 0px !important; }
		
		/* Used mainly to give title cell the same padding as normal cells */
		#pad_flex { padding: inherit; }
		#pad_none { padding: 0px; }
		#pad_nobtm { padding-bottom: 0px; }
		#pad_notop { padding-top: 0px; }
		
		/* New look for 4.8.2 */
		.newDark {
		   color: #000000;
		   font-weight: bold;
		   }
		
		.newtext {
		   color: #000000;
		   }
		
		/*####################################################################################
		--------------------------------------------------------------------------------------
		>> Unique Elements
		--------------------------------------------------------------------------------------
		####################################################################################*/
		
		/*=================================================================================*/
		/* PopUp Div Layers (help screens, progress anis, user notes, version info, etc.)
		/*---------------------------------------------------------------------------------*/
		.gray_gel {
		   background-image: url(includes/display_elements/graphics/gray_gel.gif);
		   height: 23px;
		   border-bottom: 1px solid #2E2E2E;
		}
		
		
		/*---------------------------------------------------------------------------------------------------------*
		 ___        _    _
		| _ ) _  _ | |_ | |_  ___  _ _   ___
		| _ \| || ||  _||  _|/ _ \| ' \ (_-<
		|___/ \_,_| \__| \__|\___/|_||_|/__/
		
		/* New-school IE & FF friendly graphical buttons */
		/*---------------------------------------------------------------------------------------------------------*
		/* BIG BUTTONS  (ie 'Upload template files..') */
		/* Outer span has set dimenions and graphic bg */
		span.button_image {
		   display: block;
		   cursor: pointer;
		   padding: 0px 0px 0px 0px;
		   width: 200px;
		   height: 32px;
		}
		/* Inner span holds padded button text */
		span.button_image_text {
		   display: block;
		   text-align: left;
		   vertical-align: top;
		   padding: 10px 10px 0px 45px;
		}
		
		
		/* Spcecific buttons */
		#check_updates_btn_off { background-image: url('includes/display_elements/graphics/check_updates_btn-off.gif'); }
		#check_updates_btn_on { background-image: url('includes/display_elements/graphics/check_updates_btn-on.gif'); }
		
		/* DIALOG BUTTONS  (ie 'Install Now | Cancel') */
		/* Outer span has set dimenions and graphic bg */
		span.dialog_button {
		   display: block;
		   cursor: pointer;
		   padding: 0px 0px 0px 0px;
		   width: 102px;
		   height: 27px;
		}
		/* Inner span holds padded button text */
		span.dialog_button_text {
		   display: block;
		   text-align: left;
		   vertical-align: top;
		   padding: 7px 10px 0px 25px;
		   border: 0px solid red;
		}
		
		/* Spcecific buttons */
		#install_btn_off { background-image: url('includes/display_elements/graphics/install_btn-off.gif'); }
		#install_btn_on { background-image: url('includes/display_elements/graphics/install_btn-on.gif'); }
		#restart_btn_off { background-image: url('includes/display_elements/graphics/restart_btn-off.gif'); }
		#restart_btn_on { background-image: url('includes/display_elements/graphics/restart_btn-on.gif'); }
		#cancel_btn_off { background-image: url('includes/display_elements/graphics/cancel_btn-off.gif'); }
		#cancel_btn_on { background-image: url('includes/display_elements/graphics/cancel_btn-on.gif'); }
		#buy_btn_off { background-image: url('includes/display_elements/graphics/buy_btn-off.gif'); }
		#buy_btn_on { background-image: url('includes/display_elements/graphics/buy_btn-on.gif'); }
		
		/*=================================================================================*/
		/* Icon and button in footer that shows version info and update notifications
		/*---------------------------------------------------------------------------------*/
		#update_orb {
		   border: 0px solid red;
		   z-index: 10;
		   width: 200px;
		   height: 19px;
		   text-align: right;
		   vertical-align: top;
		   padding-top: 3px;
		   padding-right: 25px;
		   background-repeat: no-repeat;
		   background-attachment: fixed;
		   background-position: right top;
		}
		
		/* One of these will always be employed with #update_orb */
		.orb_off { background-image: url(includes/display_elements/graphics/ftr-update_orb-off.gif); }
		.orb_green { background-image: url(includes/display_elements/graphics/ftr-update_orb-grn.gif); }
		.orb_red { background-image: url(includes/display_elements/graphics/ftr-update_orb-red.gif); }
		.orb_orange { background-image: url(includes/display_elements/graphics/ftr-update_orb-orange.gif); }
		
		
		
		/*####################################################################################
		--------------------------------------------------------------------------------------
		>> Buttons
		--------------------------------------------------------------------------------------
		####################################################################################*/
		
		/*================================================================================
		>> Upper Bar Navigation Buttons
		================================================================================*/
		.nav_main, .nav_mainon, .nav_mainmenu, .nav_mainmenuon, .nav_save, .nav_saveon, .nav_soho, .nav_sohoon, .nav_logout, .nav_logouton {
			color: #FFFFFF;
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			cursor: pointer;
		}
		
		.nav_main, .nav_mainon, .nav_mainmenu, .nav_mainmenuon {
		   background-color: #144B81;
			border: 1px solid #595959;
		}
		
		.nav_main { background-image: url(includes/display_elements/graphics/btn-nav_main-off.jpg); }
		.nav_mainon { background-image: url(includes/display_elements/graphics/btn-nav_main-on.jpg); }
		
		
		.nav_mainmenu {
			font-weight: bold;
		}
		
		.nav_mainmenuon {
			background-color: #3283D3;
			font-weight: bold;
		}
		
		
		.nav_save, .nav_saveon {
			background-color: #087D34;
			border: 2px solid #66CC70;
		}
		
		.nav_saveon {
			background-color: #149845;
		}
		
		.nav_soho, .nav_sohoon {
			background-color: #815714;
			border: 2px solid #CC9B66;
		}
		
		.nav_sohoon {
			background-color: #FF6600;
		}
		
		.nav_logout, .nav_logouton {
			background-color: #9B0000;
			border: 2px solid #CC6666;
		}
		
		.nav_logouton {
			background-color: #D70000;
		}
		
		
		/*================================================================================
		>> Regular buttons
		================================================================================*/
		
		
		.btn_edit, .btn_editon, .btn_save, .btn_saveon, .btn_delete, .btn_deleteon, .btn_build, .btn_buildon, .btn_risk, .btn_riskon {
			background-color: #C3DEFF;
			font-family: tahoma, verdana, arial, helvetica, sans-serif;
			color: #000000;
			font-size: 8pt;
			cursor: pointer;
			border: 2px solid #6699CC;
			border-right: 2px solid #336699;
			border-bottom: 2px solid #336699;
		   border-left: 2px solid #6699CC;
		}
		
		
		.btn_editon {
			background-color: #C3EDFF;
		}
		
		.btn_save, .btn_saveon {
			background-color: #14B21C;
			color: #ffffff;
			border-top: 2px solid #158B1A;
			border-right: 2px solid #166D1A;
			border-bottom: 2px solid #166D1A;
		   border-left: 2px solid #158B1A;
		}
		
		.btn_saveon {
			background-color: #10D91A;
		}
		
		.btn_delete, .btn_deleteon {
			background-color: #E31A1A;
			color: #FFFFFF;
			border-top: 2px solid #B81B1B;
			border-right: 2px solid #680808;
			border-bottom: 2px solid #680808;
		   border-left: 2px solid #B81B1B;
		}
		
		.btn_deleteon {
			background-color: #FF0000;
		}
		
		/* For actions that may lead to undesireable but not necessarily irrecoverable consequences */
		.btn_risk, .btn_riskon {
			background-color: #F75D00;
			color: #FFFFFF;
			border-top: 2px solid #B81B1B;
			border-right: 2px solid #680808;
			border-bottom: 2px solid #680808;
		   border-left: 2px solid #B81B1B;
		}
		
		.btn_riskon {
			background-color: #FE7613;
		}
		
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		|| These btn_build buttons are mostly used for features that
		|| add something to the site (i.e. upload files, build form)
		~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
		.btn_build, .btn_buildon {
			background-color: #BDEED1;
			color: #000000;
			border-top: 2px solid #66CCA2;
			border-right: 2px solid #33996D;
			border-bottom: 2px solid #33996D;
		   border-left: 2px solid #66CCA2;
		}
		
		.btn_buildon {
			background-color: #B1FAD0;
		}
		
		
		/*================================================================================
		>> Minor action buttons within features (ie. "Move Item Up")
		================================================================================*/
		.btn_blue, .btn_green, .btn_red, .btn_orange {
			background-color: #C3DEFF;
			font-family: tahoma, verdana, arial, helvetica, sans-serif;
			color: #FFF;
			font-size: 8pt;
			cursor: hand;
		}
		
		.btn_blue {
			background-color: #336699;
			color: #FFFFFF;
			font-size: 8pt;
			cursor: hand;
			border: 2px outset #6699CC;
		}
		
		.btn_green {
			background-color: #087D34;
			color: #FFFFFF;
			font-size: 8pt;
			cursor: hand;
			border: 2px outset #66CC91;
		}
		
		.btn_red {
			background-color: #6E0000;
			color: #FFFFFF;
			font-size: 8pt;
			cursor: hand;
			border: 2px outset #9B0000;
		}
		
		.btn_orange {
			background-color: #D75B00;
			color: #FFFFFF;
			font-size: 8pt;
			cursor: hand;
			border: 2px outset #9B5800;
		}
		
		
		/*####################################################################################
		--------------------------------------------------------------------------------------
		>> Form fields
		--------------------------------------------------------------------------------------
		####################################################################################*/
		
		SELECT {
			font-family: verdana, arial, helvetica, sans-serif;
			font-size: 9px;
		}
		
		.tfield_hex {
		   font-family: verdana, arial, helvetica, sans-serif;
		   font-size: 10px;
		   color: #727272;
		   width: 57px;
		}
		
		.tfield {
		   font-family: verdana, arial, helvetica, sans-serif;
		   font-size: 10px;
		}
		
		
		/*####################################################################################
		--------------------------------------------------------------------------------------
		>> Text Links
		--------------------------------------------------------------------------------------
		####################################################################################*/
		
		.hand { cursor: pointer; }
		
		a:link { color: #336699; text-decoration: underline; }
		a:visited { color: #336699; text-decoration: underline; }
		a:hover { color: #6699cc; text-decoration: underline; }
		a:active { color: #a5c6e6; text-decoration: underline; }
		
		/* Help Links: Typically spawn a div help popup onclick */
		.help_popup_link:after { content: url("../icons/help_link_icon.gif"); }
		
		/* Main Menu: Enabled module links */
		a.on:link { color: #000000; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
		a.on:visited { color: #000000; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
		a.on:hover { color: #336699; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
		
		/* Main Menu: Disabled module links */
		a.off:link { color: #CCCCCC; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
		a.off:visited { color: #CCCCCC; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
		a.off:hover { color: #000000; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
		
		TABLE.clsNavLinks A:hover{text-decoration: none;}
		TABLE.clsNavLinks { clear: both; }
		
		.menusys a:link { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; text-decoration: none; }
		.menusys a:visited { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; text-decoration: none; }
		.menusys a:hover { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; text-decoration: underline;}
		.menusys a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; text-decoration: underline; }
		
		a.act:link { color: #F75D00; text-decoration: underline; }
		a.act:visited { color: #F75D00; text-decoration: underline; }
		a.act:hover { color: #FD8D3B; text-decoration: underline; }
		a.act:active { color: #FFC417; text-decoration: underline; }
		
		a.sav:link { color: #339959; text-decoration: underline; }
		a.sav:visited { color: #339959; text-decoration: underline; }
		a.sav:hover { color: #66CC91; text-decoration: underline; }
		a.sav:active { color: #A5E6B3; text-decoration: underline; }
		
		a.del:link { color: #9B0000; text-decoration: underline; }
		a.del:visited { color: #9B0000; text-decoration: underline; }
		a.del:hover { color: #D70000; text-decoration: underline; }
		a.del:active { color: #FF0000; text-decoration: underline; }
		
		a.darkbg:link { color: #FFFFFF; text-decoration: underline; }
		a.darkbg:visited { color: #FFFFFF; text-decoration: underline; }
		a.darkbg:hover { color: #D70000; text-decoration: underline; }
		a.darkbg:active { color: #EFEFEF; text-decoration: underline; }
		
		
		
		
		/*################################################################################################
		  _____                                 _   _    _
		 / ____|                               | | | |  | |
		| |  __   ___  _ __    ___  _ __  __ _ | | | |  | | ___   ___
		| | |_ | / _ \| '_ \  / _ \| '__|/ _` || | | |  | |/ __| / _ \
		| |__| ||  __/| | | ||  __/| |  | (_| || | | |__| |\__ \|  __/
		 \_____| \___||_| |_| \___||_|   \__,_||_|  \____/ |___/ \___|
		
		# Originally copied from info.soholaunch.com
		# Complete css revamp coming in v5
		/*################################################################################################
		
		/* LINKS */
		/*----------------------------*/
		
		/* Default link style */
		a:link {color: #306fae; text-decoration: underline; border-bottom:0px solid #004C9A;}
		a:visited {color: #306fae; text-decoration: underline; border-bottom:0px solid #004C9A;}
		a:hover {color: #6699cc; text-decoration: underline; border-bottom:0px solid #AEC9FF;}
		a:active {color: #A5C6E6; text-decoration: underline; border-bottom:0px solid #AEC9FF;}
		
		/* Mark all external links */
		a.external:link:after { content: url("external_link_icon-10px.gif"); }
		a.external:visited:after { content: url("external_link_icon-10px.gif"); }
		a.external:link { text-decoration: none; border-bottom: 1px dotted #7a7a7a; }
		a.external:visited { text-decoration: none; border-bottom: 1px dotted #7a7a7a; }
		a.external:hover { text-decoration: none; border-bottom: 1px dotted #7a7a7a; }
		a.external:active { text-decoration: none; border-bottom: 1px dotted #7a7a7a; }
		
		a.link_green:link  { color: #30ae6f !important; }
		a.link_green:visited { color: #30ae6f !important; }
		a.link_green:hover { color: #66cc91 !important; }
		a.link_green:active { color: #A5E6B3 !important; }
		
		a.link_orange:link  { color: #F75D00 !important; }
		a.link_orange:visited { color: #F75D00 !important; }
		a.link_orange:hover { color: #ff8d41 !important; }
		a.link_orange:active { color: #FFC417 !important; }
		
		a.link_whitebox:link  { color: #fff;text-decoration: none;border: 1px dotted #ccc;   }
		a.link_whitebox:visited { color: #fff;text-decoration: none;border: 1px dotted #ccc; }
		a.link_whitebox:hover { color: #fff;text-decoration: none;border: 1px dotted #fff; }
		a.link_whitebox:active { color: #fff;text-decoration: none;border: 1px dotted #fff; }
		
		
		/* BORDERS */
		/*----------------------------*/
		.bdr_nobtm { border-bottom: none !important; }
		.bdr_blue { border: 1px solid #6699CC !important; }
		.bdr_bluetop { border-top: 1px solid #6699CC !important; }
		.bdr_blueleft { border-left: 1px solid #6699CC !important; }
		.bdr_blueright { border-right: 1px solid #6699CC !important; }
		
		/* Newschool border colors */
		.bdr_blue_a5 { border-color: #a5c6e6; !important; }
		
		/* Newschool border styles */
		.bdr_solid_u { border-style: none solid solid solid; !important; }
		
		/* Newschool border widths */
		.bdr_1px { border-width: 1px; !important; }
		
		.bdr_right_dotted { border-right: 1px dotted #D1D5D8 !important; }
		.bdr_btm_dotted td { border-bottom: 1px dotted #D1D5D8; }
		
		
		/* BACKGROUND COLOR - newschool */
		.bg_gray_f8 { background-color: #F8F8F8 !important; }
		.bg_gray_ef { background-color: #efefef !important; }
		.bg_gray_a5 { background-color: #E8E8E8 !important; }
		.bg_gray_df { background-color: #D1D5D8 !important; }
		
		.bg_blue_f8 { background-color: #F8F9FD !important; }
		.bg_blue_a5 { background-color: #A5C6E6 !important; }
		.bg_blue_df { background-color: #DFECF6 !important; }
		.bg_blue_66 { background-color: #6699CC !important; }
		.bg_blue_30 { background-color: #306FAE !important; }
		.bg_blue_33 { background-color: #336699 !important; }
		.bg_blue_31 { background-color: #315173 !important; }
		
		.bg_green_f8 { background-color: #F8FDFB !important; }
		.bg_green_df { background-color: #DFF6EA !important; }
		.bg_green_a5 { background-color: #A5E6B3 !important; }
		.bg_green_66 { background-color: #66CC91 !important; }
		.bg_green_30 { background-color: #30AE6F !important; }
		.bg_green_33 { background-color: #339959 !important; }
		.bg_green_31 { background-color: #317344 !important; }
		
		.bg_red_98 { background-color: #980000 !important; }
		.bg_red_d7 { background-color: #d70000 !important; }
		.bg_red_66 { background-color: #cc6666 !important; }
		.bg_red_a5 { background-color: #e6a5a5 !important; }
		.bg_red_df { background-color: #f6dfdf !important; }
		.bg_red_f8 { background-color: #fdf8f8 !important; }
		
		
		/* BACKGROUND COLOR - oldstyle */
		.bg_white { background-color: #FFFFFF !important; }
		.bg_lgray { background-color: #F7F7F7 !important; }
		.bg_lgreen { background-color: #F8FDFB !important; }
		.bg_green { background-color: #DFF6EA !important; }
		.bg_lblue { background-color: #F8F9FD !important; }
		.bg_lblue2 { background-color: #F7F9FF !important; }
		.bg_yellow { background-color: #FFFF99 !important; }
		
		
		/* SHORTAND FONT FAMILY */
		.mono { font-family: courier !important; }
		
		
		/* FONT COLOR */
		.white { color: #FFFFFF !important; }
		
		.lblack { color: #2E2E2E !important; }
		.black { color: #000000 !important; }
		
		.gray_f8 { color: #F8F8F8 !important; }
		.gray_df { color: #D1D5D8 !important; }
		.gray_33 { color: #7A7A7A !important; }
		.gray_31 { color: #595959 !important; }
		.gray { color: #888c8e !important; }
		.dgray { color: #595959 !important; }
		
		.lblue { color: #6699cc !important; }
		.blue { color: #336699 !important; }
		.dblue { color: #315173 !important; }
		.blue_f8 { color: #F8F9FD !important; }
		.blue_df { color: #DFECF6 !important; }
		.blue_a5 { color: #A5C6E6 !important; }
		.blue_66 { color: #6699CC !important; }
		.blue_30 { color: #306FAE !important; }
		.blue_33 { color: #336699 !important; }
		.blue_31 { color: #315173 !important; }
		
		.red { color: #D70000 !important; }
		.dred { color: #980000 !important; }
		.orange { color: #FE7613 !important; }
		.green { color: #00831C !important; }
		.green_f8 { color: #F8FDFB !important; }
		.green_df { color: #DFF6EA !important; }
		.green_a5 { color: #A5E6B3 !important; }
		.green_66 { color: #66CC91 !important; }
		.green_30 { color: #30AE6F !important; }
		.green_33 { color: #339959 !important; }
		.green_31 { color: #317344 !important; }
		
		/* FONT STYLE */
		.bold { font-weight: bold !important; }
		.unbold { font-weight: normal !important; }
		.uline { text-decoration: underline !important; }
		.noline { text-decoration: none !important; }
		
		
		/* PADDING and MARGIN */
		.nopad { padding: 0px !important; }
		.nopad_top { padding-top: 0px !important; }
		.nopad_right { padding-right: 0px !important; }
		.nopad_btm { padding-bottom: 0px !important; }
		.nopad_left { padding-left: 0px !important; }
		
		.nomargin_btm { margin-bottom: 0px !important; }
		.nomar_btm { margin-bottom: 0px !important; }
		.nomar_top { margin-top: 0px !important; }
		.nomar { margin: 0px !important; }
		
		/* ALIGNMENT */
		.center { text-align: center !important; }
		.right { text-align: right !important; }
		.top { vertical-align: top !important; }
		.middle { vertical-align: middle !important; }
		
		/* OTHER MISC */
		
		/* Cross-browser hand pointer for buttons that use span onclicks and such */
		.hand { cursor: pointer !important; }
		
		/* Particularly handy for adding less-stressed text/links to fgroup_title headings */
		.normal { letter-spacing: normal !important; }
		.font90 { font-size: 90% !important; }
		
		/* Bottom borders for breaking up table rows */
		tr.row_spliter td { border-bottom: 1px dashed #ccc; }
		
		/* Full & Partial opacity styles - used to dim out user options layer when showing a popup div */
		.faded {
		   filter:progid:DXImageTransform.Microsoft.Alpha(opacity=40);
		   -moz-opacity: 0.4;
		}
		.notfaded {
		   filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100);
		   -moz-opacity: 1;
		}
		
		
		
		
		
		
		
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		Probably Outdated Sytles (duplicates removed) - menu.css
		------------------------------------------------------------------------------
		>> These are only going to remain in this file until there obsolecense
		>> can be investigated and (hopefully) proven.
		~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
		
		.mouse {cursor: default}
		.click {cursor: hand}
		.ob1 {cursor: hand;background: ghostwhite}
		.ob2 {cursor: hand;background: gainsboro}
		.saverec {font:8pt Arial,sans-serif;color: menutext; background: captiontext}
		
		.tbox {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; background-color: #CCCCCC; border: #000000; border-style: solid; border-top-width: thin; border-right-width: thin; border-bottom-width: thin; border-left-width: thin}
		
		
		.module_link {
		   font-family: verdana, arial, helvetica, sans-serif;
		   font-size: 11px;
		   font-weight: bold;
		   color: #000000;
		}
		
		.FormLt2 {
			font-family : Arial;
			font-size : 8pt;
			cursor: hand;
		}
		
		.FormLt1 {
			background-color: #336699;
			color: #FFFFFF;
			font-size: 8pt;
			cursor: hand;
			border: 2px solid #6699CC;
		}
		
		.chkout {
			font-family : Arial;
			font-size : 9pt;
			cursor: hand;
			width: 195px
		}
		
		.chkbox {
			font-size : 8pt;
			background-color : menu;
			border : none;
		}
		
		.allBorder {
			font-family: Arial;
			font-size: 8pt;
			border: 1px #000000 inset;
			background: #EFEFEF;
		}
		
		.border {
			font-family: Arial;
			font-size: 8pt;
			border: 1px #000000 inset;
		}
		
		
		
		.curoff {
			cursor: normal;
		}
		
		.curhand {
			cursor: hand;
		}
		
		.tblBorder {vertical-align: middle; border: 1 outset; margin: 0; font-family: Arial; font-size: 8pt; color: #000000;border: solid <?php echo $bgcolor; ?> 1px;border-left: 1px solid <?php echo $bgcolor; ?>;}
		.tblCell { border-left: 1px solid <?php echo $bgcolor; ?>; }
		.calcontrols {vertical-align: top; margin: 0; font-family: Arial; font-size: 8pt; background-color: #FFFFFF; color: #000000;}
		.calopcontrols {vertical-align: top; margin: 0; font-family: Arial; font-size: 8pt; background-color: oldlace; color: #000000;}
		.icalcontrols {vertical-align: top; margin: 0; font-family: Arial; font-size: 8pt; background-color: #FFFFFF; color: #000000;}
		.icalopcontrols {vertical-align: top; margin: 0; font-family: Arial; font-size: 8pt; background-color: #E6E6E6; color: #000000;}
		.catselect {font-family: Arial; font-size: 8pt;}
		.hintbox {width: 15; height: 15; vertical-align: top;}
		
		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
		/** {
		   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
		   font-size: 11px;
		   line-height: normal;
		}*/
		
		body {
		   height: 100%;
		}
		
		/* shopping_cart.php */
		table#shopping_cart_menu th, table#shopping_cart_menu td {
		   text-align: left;
		   border-left: 1px dotted #ccc;
		   padding: 5px 40px;
		}
		table#shopping_cart_menu td {
		   text-align: left;
		}
		
		h2 {
		   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
		   font-size: 15px;
		   font-weight: bold;
		   margin-bottom: 0;
		}
		
		/* Outer module container table with breadcrumb row, icon/heading row, body row */
		/* This should eventually replace feature_sub class */
		table.module_container {
		   width: 100%;
		   margin-top: 10px;
		   font-family: verdana, arial, helvetica, sans-serif;
			font-size: 10px;
			border: 1px solid #2E2E2E;
			background: #f8f9fd;
		}
		table.module_container td.module_body_area {
		   padding: 10px;
		}
		
		.fgroup_title {
		   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
		   font-weight: normal;
		   letter-spacing: normal;
		   font-size: 11px;
		}
		.fgroup_title a:link, .fgroup_title a:visited { color: #fff; text-decoration: none; }
		.fgroup_title a:hover, .fgroup_title a:active { color: #fff; text-decoration: underline; }
		
		
		/* These things: [?] --- the ones that spawn help popup divs */
		.help_link {
		   color: #FE7613;
		   cursor: pointer;
		   font-size: 90%;
		   text-decoration: underline;
		   cursor: pointer;
		}
		
		.note { color: #b1b1b1; }
		
		
		/* $report[] output layer in module template */
		div#report_messages {
		   width: 100%;
		   /*margin-top: -10px;*/
		   margin: 0;
		   /*padding-left: 40px;*/
		   background-color: #FFFAB2;
		}
		div#report_messages ul {
		   margin: 0;
		   list-style-type: square;
		}
		div#report_messages ul li {
		   /*margin-left: 15px;*/
		}
		
		
		p#module-description_text {
		   margin: 0;
		}
		
		
		/*------------------------------------------------------------------------------------------*
		New look for feature module headings?
		/*------------------------------------------------------------------------------------------*/
		table.feature_module_heading {
		   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
		   border-bottom: 1px solid #ccc;
		   color: #2e2e2e;
		   background-color: #d9e6ef;
		   background-image: url('../skins/default/feature_module_heading.gif');
		}
		
		table.feature_module_heading td {
		   padding: 5px;
		}
		
		.feature_module_heading h1 {
		   margin-top: 0px;
		   margin-bottom: 0;
		   font-size: 18px;
		}
		
		/* Module Description Text */
		.feature_module_heading p {
		   margin-top: 0px;
		   margin-bottom: 0px;
		   font-size: 12px;
		   line-height: 1em;
		}
		
		
		/*------------------------------------------------------------------------------------------*
		General-use
		/*------------------------------------------------------------------------------------------*/
		.bg_yellow { background-color: #FFFAB2; }
		.bg_yellow_dark { background-color: #FFF66F; }
		.fadeout { opacity: .5;-moz-opacity: 0.5;filter: alpha(opacity=50); }
		.fade15 { opacity: .15;-moz-opacity: 0.15;filter: alpha(opacity=15); }
		.fade30 { opacity: .3;-moz-opacity: 0.3;filter: alpha(opacity=30); }
		.fadein { opacity: 1 !important;-moz-opacity: 1 !important;filter: alpha(opacity=100); }
		
		
		/*------------------------------------------------------------------------------------------*
		 Stuff that will probably only apply to plugin manager starts here
		/*------------------------------------------------------------------------------------------*/
		/* Rectangle block for individual plugin with icon, title, author, descript, etc. */
		div.plugin_block, div.plugin_block-hover {
		   position: relative;
		   /*background-repeat: no-repeat;*/
		   width: 700px;
		   height: 65px;
		   cursor: default;
		   border: 1px dashed #ccc;
		}
		div.plugin_block-hover {
		   /*background-image: url('plugin_block-hover.gif');*/
		   background-color: #f2f7fb !important;
		}
		
		div.plugin_description {
		   background-position: 7px 15px;
		   background-repeat: no-repeat;
		   padding: 13px;
		   padding-left: 50px;
		   width: 385px;
		   overflow: auto;
		}
		
		/* BUTTON: Install new plugin */
		.install_button, .install_button-hover {
		   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
		   width: 152px;
		   background-repeat: no-repeat;
		   padding: 5px;
		   font-size: 12px;
		   text-align: left;
		   padding-top: 5px;
		   padding-left: 30px;
		   font-weight: bold;
		   cursor: pointer;
		}
		.install_button { background-image: url('images/install_plugin.gif'); }
		.install_button-hover { background-image: url('images/install_plugin-hover.gif'); }
		
		/* BUTTON: Uninstall Plugin */
		.uninstall_button, .uninstall_button-hover  {
		   /*font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;*/
		   /*width: 100px;*/
		   background-repeat: no-repeat;
		   padding: 8px;
		   padding-top: 4px;
		   padding-left: 17px;
		   text-align: left;
		   font-size: 10px;
		   /*font-weight: bold;*/
		   position: absolute;
		   top: 15px;
		   right: 0px;
		   cursor: pointer;
		}
		.uninstall_button  { background-image: url('images/uninstall_button.gif'); }
		.uninstall_button-hover  { background-image: url('images/uninstall_button-hover.gif'); }
		
		/* BUTTON: Options */
		.options_button, .options_button-hover  {
		   /*font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;*/
		   /*width: 100px;*/
		   background-repeat: no-repeat;
		   padding: 8px;
		   padding-top: 4px;
		   padding-left: 20px;
		   text-align: left;
		   font-size: 10px;
		   /*font-weight: bold;*/
		   position: absolute;
		   top: 15px;
		   right: 175px;
		   cursor: pointer;
		}
		.options_button  { background-image: url('images/options_button.gif'); }
		.options_button-hover  { background-image: url('images/options_button-hover.gif'); }
		
		/* BUTTON: Update Now */
		.update_button, .update_button-hover  {
		   /*font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;*/
		   /*width: 100px;*/
		   background-repeat: no-repeat;
		   padding: 8px;
		   padding-top: 4px;
		   padding-left: 21px;
		   text-align: left;
		   font-size: 10px;
		   /*font-weight: bold;*/
		   position: absolute;
		   top: 15px;
		   right: 90px;
		   cursor: pointer;
		}
		.update_button  { background-image: url('images/update_button.gif'); }
		.update_button-hover  { background-image: url('images/update_button-hover.gif'); }
		
		.droptable_option  {
		   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
		   text-align: left;
		   font-size: 12px;
		   position: absolute;
		   top: 40px;
		   right: 10px;
		   cursor: pointer;
		}
		
		/* IE Float border workaround */
		.ie_cleardiv {
		   margin: 0;
		   clear: both;
		}
		
		/* Formatted timestamp values */
		.formatted_timestamp {
		   font-style: italic;
		   font-size: 90%;
		}
		
		span.formatted_serialized {
		   /*font-size: 90%;*/
		   /*color: red;*/
		   display: block;
		   width: 450px;
		   height: 175px;
		   overflow: auto;
		}
		
		a.sup:link {color: #980404; text-decoration: none; font-family: Arial; font-size: 7pt;}
		a.sup:visited {color: #980404; text-decoration: none; font-family: Arial; font-size: 7pt;}
		a.sup:hover {color: white; text-decoration: none; font-family: Arial; font-size: 7pt; background: #980404;}
		
		table#timstamp_field_select td input,
		table#timstamp_field_select td label {
		   float: left;
		   /*border: 1px solid red;*/
		}
		table#timstamp_field_select td input {
		   margin-top: 0px;
		}
		table#timstamp_field_select td label {
		   margin-top: 1px;
		   margin-right: 5px;
		}
		
		</style>
		<?php
		
		# Account for tables with keyfields named something besides "PRIKEY"
		# Just pull first field
		$qry = "SELECT * FROM $mt LIMIT 1";
		$rez = mysql_query($qry);
		$KEYFIELD = mysql_field_name($rez, 0);
		
		if ( isset($_GET['kill_lastsearch']) ) {
		   $_SESSION[$_REQUEST['mt']]['last_search_term'] = NULL;
		   $_SESSION[$_REQUEST['mt']]['searchin'] = NULL;
		}
		
		
		# orderby
		if ( isset($_GET['orderby']) ) {
		   $_SESSION[$_REQUEST['mt']]['orderby'] = $_GET['orderby'];
		   $_SESSION[$_REQUEST['mt']]['orderhow'] = $_GET['orderhow'];
		}
		# kill_orderby
		if ( isset($_GET['kill_orderby']) ) {
		   $_SESSION[$_REQUEST['mt']]['orderby'] = NULL;
		   $_SESSION[$_REQUEST['mt']]['orderhow'] = NULL;
		}
		# DEFAULT: order by $KEYFIELD
		if ( !isset($_SESSION[$_REQUEST['mt']]['orderby']) ) {
		   $_SESSION[$_REQUEST['mt']]['orderby'] = $KEYFIELD;
		   $_SESSION[$_REQUEST['mt']]['orderhow'] = "asc";
		}
		
		
		# hide_multi_fields
		if ( $_POST['todo'] == "hide_multi_fields" ) {
		
			$qry = "SHOW COLUMNS FROM ".$mt;
			$fieldrez = mysql_query($qry);
			while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
			   if ( in_array($getCol['Field'], $_POST['multi_hide_fields']) ) {
			      $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$getCol['Field']] = "valuedoesntmatter";
			   } else {
			      unset($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$getCol['Field']]);
			   }
			}
		}
		
		# format_timestamps
		if ( $_POST['todo'] == "format_timestamps" ) {
			$qry = "SHOW COLUMNS FROM ".$mt;
			$fieldrez = mysql_query($qry);
			while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
			   if ( in_array($getCol['Field'], $_POST['timestamp_fields']) ) {
			      $_SESSION['timestamp_fields'][$_REQUEST['mt']][$getCol['Field']] = "valuedoesntmatter";
			   } else {
			      unset($_SESSION['timestamp_fields'][$_REQUEST['mt']][$getCol['Field']]);
			   }
			}
			$_SESSION['timestamp_date_format'][$_REQUEST['mt']] = $_POST['timestamp_date_format'];
		}
		
		# format_encoded
		if ( $_POST['todo'] == "format_decode" ) {
			$qry = "SHOW COLUMNS FROM ".$mt;
			$fieldrez = mysql_query($qry);
			while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
			   if ( in_array($getCol['Field'], $_POST['decode_fields']) ) {
			      $_SESSION['decode_fields'][$_REQUEST['mt']][$getCol['Field']] = "valuedoesntmatter";
			   } else {
			      unset($_SESSION['decode_fields'][$_REQUEST['mt']][$getCol['Field']]);
			   }
			}
			//$_SESSION['decode_format'][$_REQUEST['mt']] = $_POST['timestamp_date_format'];
		}
		
		# format_serialized
		if ( $_POST['todo'] == "format_serialized" ) {
			$qry = "SHOW COLUMNS FROM ".$mt;
			$fieldrez = mysql_query($qry);
			while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
			   if ( in_array($getCol['Field'], $_POST['serialized_fields']) ) {
			      $_SESSION['serialized_fields'][$_REQUEST['mt']][$getCol['Field']] = "valuedoesntmatter";
			   } else {
			      unset($_SESSION['serialized_fields'][$_REQUEST['mt']][$getCol['Field']]);
			   }
			}
		}
		
		# run_qry
		# Accepts custom qry in post or hard-coded query link via get
		if ( $_POST['todo'] == "run_qry" ) {
		   # Store query in session for quick access to last run queries
		   # MD5 qry string as array key to prevent duplicates
		   $qrykey = md5(trim($_POST['runthis']));
		   $_SESSION['dtm_custom_qry'][$qrykey] = $_POST['runthis'];
		
		   # SELECT queries are special
		   if ( eregi("^SELECT", $_POST['runthis']) ) {
		      $customselectqry = true;
		   }
		
		   # Run query
		   if ( !mysql_query(stripslashes($_REQUEST['runthis'])) ) {
		      echo mysql_error(); exit;
		
		   } else {
		//      echo "Query run successfully (or so it appears). This was the query...<br/>\n";
		//      echo "<span class=\"mono bold\">".stripslashes($_POST['runthis'])."</span><br/>";
		   }
		}
		
		# killqry
		# Remove custom query string from history (when they click the [x] in the custom query popup layer)
		if ( $_REQUEST['todo'] == "killqry" ) {
		   $_SESSION['dtm_custom_qry'][$_GET['qryid']] = NULL;
		}
		
		# dtm_viewmode
		# Change record view mode upon request (i.e. they click the 'hide blob data' link)
		# Do this on a per-table basis
		if ( isset($_GET['dtm_viewmode']) ) {
		   $_SESSION['dtm_viewmode'][$_REQUEST['mt']] = $_GET['dtm_viewmode'];
		}
		
		# dtm_collapse
		# Show/hide collapse/expand links for each column heading
		if ( isset($_GET['dtm_collapse']) ) {
		   $_SESSION['dtm_collapse_option'][$_REQUEST['mt']] = $_GET['dtm_collapse'];
		}
		
		# collapse_field
		# Add to collapsed field array (so column data will be hidden)
		if ( isset($_GET['collapse_field']) ) {
		   $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']][$_GET['collapse_field']] = "valuedoesntmatter";
		}
		
		# expand_field
		# Remove from collapsed field array (so column data will be shown)
		if ( isset($_GET['expand_field']) ) {
		   unset($_SESSION['dtm_collapse_fields'][$_REQUEST['mt']][$_GET['expand_field']]);
		}
		
		# expand_all
		# Expand any/all collapsed columns, show any hidden columns
		if ( $_GET['todo'] == "expand_all" ) {
		   unset($_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]);
		   unset($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]);
		}
		
		# collapse_all
		# Collapse all fields
		if ( $_GET['todo'] == "collapse_all" ) {
		   $qry = "select * from ".$_REQUEST['mt']." limit 1";
		   $rez = mysql_query($qry);
		   $numberFields = mysql_num_fields($rez);
		
			for ( $x = 0; $x <= $numberFields; $x++ ) {
			   $thefieldname = mysql_field_name($rez, $x);
			   $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']][$thefieldname] = "valuedoesntmatter";
			}
		}
		
		# hide_field
		# Hide field entirely (do not show column)
		if ( isset($_GET['hide_field']) ) {
		   $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$_GET['hide_field']] = "valuedoesntmatter";
		}
		# show_field
		# Restore hidden field and show as normal
		if ( isset($_GET['show_field']) ) {
		   unset($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$_GET['show_field']]);
		}
		
		#######################################################
		### Process "SAVE RECORD" Action		            ###
		#######################################################
		if ($_GET['ACTION'] == "DELETE_RECORD_NOW") {
		
			mysql_query("DELETE FROM $mt WHERE $KEYFIELD = '$ID'");
			$ACTION = "";
			unset($_GET);
			foreach($_SESSION[$_REQUEST['mt']]['last_page_view'] as $mo=>$ma){
				$_GET[$mo]=$ma;
				${$mo}=$ma;
			}
		
		} // End Delete Record Action
		
		#######################################################
		### Process "SAVE RECORD" Action		            ###
		#######################################################
		
		if ($ACTION == "SAVE_NEW") {
		
			$SQL_STRING = "INSERT INTO $mt VALUES(";
			$tmp_date = "";								// Prepare our tmp date string register
			$tmp_time = "";								// Prepare our tmp time string register as well
		
			reset($HTTP_POST_VARS);
			while (list($name, $value) = each($HTTP_POST_VARS)) {
		
				$value = stripslashes($value);		// First strip all slashes for insurance and refreshes
				$value = addslashes($value);		// Now add slashes for proper mysql data storage
		
				if (ereg("VALUE_", $name) && !ereg("_DATEYEAR", $name) && !ereg("_DATEMONTH", $name) && !ereg("_DATEDAY", $name) && !ereg("_TIMEHOUR", $name) && !ereg("_TIMEMIN", $name)) {		// This is a proper value
					$SQL_STRING .= "'$value', ";
				}
		
				if (ereg("_DATE", $name)) {
		
					if (ereg("_DATEMONTH", $name)) { $tmp_date .= "$value-"; }
					if (ereg("_DATEDAY", $name)) { $tmp_date .= "$value"; }
					if (ereg("_DATEYEAR", $name)) {
						$tmp_date = "$value-" . $tmp_date;
						$SQL_STRING .= "'$tmp_date', ";		// Now add to SQL string for processing
						$tmp_date = "";						// Reset tmp date string in case of a second date value
					}
				}
		
				if (ereg("_TIME", $name)) {
					if (ereg("_TIMEHOUR", $name)) { $tmp_time .= "$value:"; }
					if (ereg("_TIMEMIN", $name)) {
						$tmp_time .= "$value:00"; 				// Add minute to time string
						$SQL_STRING .= "'$tmp_time', ";			// Now add to SQL string for processing
						$tmp_time = "";							// Reset tmp time string in case of a second time value
					}
				}
		
			} // End WHILE loop
		
			// A bi-product of this loop method is the extra comma we
			// get at the end of our new sql_string.  Let's remove it.
		
			$tmp = strlen($SQL_STRING);
			$new = $tmp - 2;
			$SQL_STRING = substr($SQL_STRING, 0, $new);
		
			$SQL_STRING .= ")";					// Add closing insert bracket
		
			// echo $SQL_STRING;
			// exit;
		
			mysql_query("$SQL_STRING");			// Insert the new data now
		
			$ACTION = "";						// Force return to display page
		
		} // End Save Record Action
		
		#######################################################
		### Process "SAVE UPDATE" Action		            ###
		#######################################################
		
		if ($ACTION == "SAVE_UPDATE") {
		
			$SQL_STRING = "UPDATE $mt SET ";
			$tmp_date = "";								// Prepare our tmp date string register
			$tmp_time = "";								// Prepare our tmp time string register as well
		
			reset($HTTP_POST_VARS);
			while (list($name, $value) = each($HTTP_POST_VARS)) {
		
				$value = stripslashes($value);	// First strip all slashes for insurance and refreshes
				$value = addslashes($value);		// Now add slashes for proper mysql data storage
		
				if (ereg("VALUE_", $name) && !ereg("_DATEYEAR", $name) && !ereg("_DATEMONTH", $name) && !ereg("_DATEDAY", $name) && !ereg("_TIMEHOUR", $name) && !ereg("_TIMEMIN", $name)) {		// This is a proper value
					$name = ereg_replace("VALUE_", "", $name);
					$SQL_STRING .= "$name = '$value', ";
				}
		
				if (ereg("_DATE", $name)) {
		
					if (ereg("_DATEMONTH", $name)) { $tmp_date .= "$value-"; }
					if (ereg("_DATEDAY", $name)) { $tmp_date .= "$value"; }
					if (ereg("_DATEYEAR", $name)) {
						$tmp_date = "$value-" . $tmp_date;
		
						$name = ereg_replace("VALUE_", "", $name);
						$name = ereg_replace("_DATEYEAR", "", $name);
		
						$SQL_STRING .= "$name = '$tmp_date', ";		// Now add to SQL string for processing
						$tmp_date = "";						// Reset tmp date string in case of a second date value
					}
				}
		
				if (ereg("_TIME", $name)) {
					if (ereg("_TIMEHOUR", $name)) { $tmp_time .= "$value:"; }
					if (ereg("_TIMEMIN", $name)) {
						$tmp_time .= "$value:00"; 				// Add minute to time string
		
						$name = ereg_replace("VALUE_", "", $name);
						$name = ereg_replace("_TIMEMIN", "", $name);
		
						$SQL_STRING .= "$name = '$tmp_time', ";			// Now add to SQL string for processing
						$tmp_time = "";							// Reset tmp time string in case of a second time value
					}
				}
		
			} // End WHILE loop
		
			// A bi-product of this loop method is the extra comma we
			// get at the end of our new sql_string.  Let's remove it.
		
			$tmp = strlen($SQL_STRING);
			$new = $tmp - 2;
			$SQL_STRING = substr($SQL_STRING, 0, $new);
		
			$EDIT_WHERE_STRING = stripslashes($EDIT_WHERE_STRING);
			$SQL_STRING .= " WHERE ($EDIT_WHERE_STRING)";
		
			// echo $SQL_STRING;
			// exit;
		
			mysql_query("$SQL_STRING");			// Insert the new data now
		
			$ACTION = "";						// Force return to display page
		
		} // End Save UPDATE Action
		
		#######################################################
		### Process "Edit Record" Action (Part 1)           ###
		#######################################################
		
		$EDIT_FLAG = "off";
		
		if ($ACTION == "EDIT") {
		
			$qry = "SELECT * FROM ".$mt." WHERE ".$KEYFIELD." = '".$ID."'";
		//	echo $qry; exit;
			$result = mysql_query($qry);
		
			$numberRows = mysql_num_rows($result);
			$numberFields = mysql_num_fields($result);
			$numberFields--;
			$row = mysql_fetch_array($result);
		
			if ( $numberRows < 1 ) {
		//	   echo "Could not select row<br/>"; echo mysql_error(); exit;
			}
		
			for ($x=0;$x<=$numberFields;$x++) {
				$iDat = htmlspecialchars($row[$x]);	// Bugzilla #12
				$FIELD_DATA[$x] = $iDat;
			}
		
			$ACTION = "ADD_NEW";
			$EDIT_FLAG = "on";
		
		}
		
		###############################################################
		### Process "Add New Record" Action		            		###
		### >> This is also utilized in the edit routine (Part 2)   ###
		###############################################################
		
		if ($ACTION == "ADD_NEW") {
		
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			//  READ IMAGE FILES INTO MEMORY
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		
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
		
			$IMAGE_SELECT = "<OPTION VALUE=\"NULL\">Select Image...</OPTION>\n";
		
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			if(count($_SESSION[$_REQUEST['mt']]['last_page_view']) > 0){
				$last_q_string = "&";
				foreach($_SESSION[$_REQUEST['mt']]['last_page_view'] as $var=>$val){
					$last_q_string .= $var."=".$val."&";
				}
				$last_q_string = eregi_replace('&$', '', $last_q_string);
			}
		
			$THIS_DISPLAY .= "<FORM METHOD=POST ACTION=\"".$simple_name."?mysqlmode=enter_edit".$last_q_string."\">\n";
			$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=mt VALUE=\"$mt\">\n";
		
			if ($EDIT_FLAG != "on") {
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"SAVE_NEW\">\n";
				$SEC_TITLE = "Enter New Record";
			} else {
				$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=ACTION VALUE=\"SAVE_UPDATE\">\n";
				$SEC_TITLE = "Update Record Data";
			}
		
		
			$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 CLASS=text WIDTH=650 ALIGN=CENTER>\n";
			$THIS_DISPLAY .= "<TR>\n";
			$THIS_DISPLAY .= "<TD ALIGN=CENTER VALIGN=MIDDLE WIDTH=75%>\n";
			$THIS_DISPLAY .= "<FONT STYLE='font-family: Arial; font-size: 10pt;'><B>$SEC_TITLE in Table \"<FONT COLOR=MAROON>$mt</FONT>\".</B></FONT>\n";
			$THIS_DISPLAY .= "</TD><TD ALIGN=CENTER VALIGN=MIDDLE WIDTH=25%>\n";
			$THIS_DISPLAY .= "<INPUT TYPE=BUTTON VALUE=\" Cancel \" ".$btn_delete." onclick=\"javascript: history.back();\">\n";
			$THIS_DISPLAY .= "</TD></TR></TABLE>\n";
		
			$THIS_DISPLAY .= "<TABLE BORDER=0 WIDTH=600 CELLPADDING=10 CELLSPACING=1 CLASS=text ALIGN=CENTER style='border: 1px inset #CCCCCC; background: #CCCCCC;'>\n";
		
		
			$qry = "SELECT * FROM ".$mt." WHERE ".$KEYFIELD." = '".$ID."'";
			$result = mysql_query($qry);
			$numberFields = mysql_num_fields($result);
			$numberFields--;
		
			$FIX_TMP = mysql_fetch_array($result);
			$edit_tmp = "";
		
			for ($x=0;$x<=$numberFields;$x++) {
		
				if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }
		
				$THIS_DISPLAY .= "<TR>\n";
				$THIS_DISPLAY .= "<TD ALIGN=RIGHT VALIGN=TOP BGCOLOR=$BGCOLOR>\n";
		
				$fieldname[$x] = mysql_field_name($result, $x);
				$fieldname[$x] = strtoupper($fieldname[$x]);
				$fieldtype[$x] = mysql_field_type($result, $x);
				$fieldlength[$x] = mysql_field_len($result, $x);
				$fieldtype[$x] = strtoupper($fieldtype[$x]);
		
				$meta = mysql_fetch_field($result, $x);
		
				if ($EDIT_FLAG == "on") {
					$this_val = addslashes($FIELD_DATA[$x]);
					if ($this_val != "") {
						$edit_tmp .= "$fieldname[$x] = '$this_val' AND ";
					}
				}
		
				$display_fieldname = eregi_replace("_", " ", $fieldname[$x]);	// Format Field names for screen display
				$display_textbox = "MAXLENGTH=$fieldlength[$x]";     			// Make sure textbox entry can be no longer than set field length
		
				$THIS_DISPLAY .= "<B><U>$display_fieldname</U>&nbsp;&nbsp;<FONT STYLE='font-size: 7pt;'>($fieldtype[$x])</FONT>&nbsp;:</B>\n";
				$THIS_DISPLAY .= "</TD><TD VALIGN=TOP ALIGN=LEFT BGCOLOR=$BGCOLOR>";
		
				if ($fieldtype[$x] == "STRING" || $fieldtype[$x] == "INT" || $fieldtype[$x] == "REAL") {
		
					if ($fieldname[$x] != "AUTO_IMAGE" && $fieldname[$x] != "AUTO_SECURITY_AUTH") {
		
						if ($meta->primary_key == 1) {
		
		//					$DIS = "DISABLED";
							$this_value = $FIELD_DATA[$x];
		
							if ($FIELD_DATA[$x] != "") {
								$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"VALUE_".$fieldname[$x]."\" VALUE=\"".$FIELD_DATA[$x]."\">\n";
								$this_value = $FIELD_DATA[$x];
							} else {
								$THIS_DISPLAY .= "<INPUT TYPE=HIDDEN NAME=\"VALUE_$fieldname[$x]\" VALUE=\"NULL\">\n";
							}
		
						} else {
		
							$DIS = "";
							$this_value = $FIELD_DATA[$x];
		
						}
		
						$THIS_DISPLAY .= "<INPUT $DIS $display_textbox TYPE=TEXT NAME=\"VALUE_$fieldname[$x]\" VALUE=\"$this_value\" CLASS=text STYLE='WIDTH: 350px;'>\n";
		
					} else {
		
						if ($fieldname[$x] == "AUTO_IMAGE") {
		
							$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]\" CLASS=text STYLE='WIDTH: 350px;'>$IMAGE_SELECT\n";
		
							for ($z=0;$z<=$numImages;$z++) {
								$tmp = split("~~~", $imageFile[$z]);
								if ($FIELD_DATA[$x] == $tmp[1]) { $SEL = "SELECTED"; } else { $SEL = ""; }
									$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$tmp[1]\">$tmp[1]</OPTION>\n";
								}
							}
		
							$THIS_DISPLAY .= "</SELECT>\n";
						}
		
						if ($fieldname[$x] == "AUTO_SECURITY_AUTH") {
		
							$THIS_DISPLAY .= "<FONT COLOR=#999999>For internal security use only.</FONT><INPUT TYPE=HIDDEN NAME=\"VALUE_AUTO_SECURITY_AUTH\" VALUE=\"$FIELD_DATA[$x]\">\n";
		
						}
		
					} // End Auto-Image Check
		
				// ---------------------------------------------------------
				// Check for BLOB Field Now
				// ---------------------------------------------------------
		
				if ($fieldtype[$x] == "BLOB" || $fieldtype[$x] == "LONGBLOB") {
					$THIS_DISPLAY .= "<TEXTAREA NAME=\"VALUE_$fieldname[$x]\" ROWS=15 CLASS=text STYLE='WIDTH: 350px; HEIGHT: 150px;'>$FIELD_DATA[$x]</TEXTAREA>\n";
				}
		
				// ---------------------------------------------------------
				// Check for Date Field
				// ---------------------------------------------------------
		
				if ($fieldtype[$x] == "DATE") {
		
					if ($EDIT_FLAG == "on") {
						$F_DATA = split("-", $FIELD_DATA[$x]);
					}
		
					$this_month = date("M");
					$this_day = date("d");
					$this_year = date("Y");
		
					$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]_DATEMONTH\" CLASS=text STYLE='WIDTH: 50px;'>\n";
					for ($z=1;$z<=12;$z++) {
						$display_month = date("M", mktime(0,0,0,$z,1,$this_year));
						$v = date("m", mktime(0,0,0,$z,1,$this_year));
						$SEL = "";
						if ($F_DATA[1] == $v) { $SEL = "SELECTED"; }
						if ($F_DATA[1] == "" && $this_month == $display_month) { $SEL = "SELECTED"; }
						$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$v\">$display_month</OPTION>\n";
					}
					$THIS_DISPLAY .= "</SELECT> ";
		
					$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]_DATEDAY\" CLASS=text STYLE='WIDTH: 50px;'>\n";
					for ($z=1;$z<=31;$z++) {
						$display_day = date("d", mktime(0,0,0,1,$z,$this_year));
						$SEL = "";
						if ($F_DATA[2] == $display_day) { $SEL = "SELECTED"; }
						if ($F_DATA[2] == "" && $display_day == $this_day) { $SEL = "SELECTED"; }
						$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$display_day\">$display_day</OPTION>\n";
					}
					$THIS_DISPLAY .= "</SELECT> ";
		
					$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]_DATEYEAR\" CLASS=text STYLE='WIDTH: 55px;'>\n";
		
					$end_year = $this_year + 10;
					for ($z=1960;$z<=$end_year;$z++) {
						$SEL = "";
						if ($F_DATA[0] == $z) { $SEL = "SELECTED"; }
						if ($F_DATA[0] == "" && $z == $this_year) { $SEL = "SELECTED"; }
						$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$z\">$z</OPTION>\n";
					}
					$THIS_DISPLAY .= "</SELECT>";
		
				} // End Date Select
		
				// ---------------------------------------------------------
				// Check for Time Field Now
				// ---------------------------------------------------------
		
				if ($fieldtype[$x] == "TIME") {
		
					if ($EDIT_FLAG == "on") {
						$F_DATA = split(":", $FIELD_DATA[$x]);
					}
		
					$THIS_DISPLAY .= "<SELECT onchange=\"set_ampm(this.value);\"NAME=\"VALUE_$fieldname[$x]_TIMEHOUR\" CLASS=text STYLE='WIDTH: 50px;'>\n";
		
					for ($z=0;$z<=24;$z++) {
		
						$v = $z;
						$v2 = $z;
		
						if ($z > 12) { $v = $z-12; }
		
						if ($v < 10) { $v = "0".$v; }
						if ($v2 < 10) { $v2 = "0".$v2; }
		
						if ($F_DATA[0] == $v2) { $SEL = "SELECTED"; } else { $SEL = ""; }
						$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$v2\">$v</OPTION>\n";
					}
		
					$THIS_DISPLAY .= "</SELECT>&nbsp;";
					$THIS_DISPLAY .= "<SELECT NAME=\"VALUE_$fieldname[$x]_TIMEMIN\" CLASS=text STYLE='WIDTH: 50px;'>\n";
		
					for ($z=0;$z<=59;$z++) {
						$v = $z;
						if ($z < 10) { $v = "0".$z; }
						if ($F_DATA[1] == $v) { $SEL = "SELECTED"; } else { $SEL = ""; }
						$THIS_DISPLAY .= "<OPTION $SEL VALUE=\"$v\">$v</OPTION>\n";
					}
		
					$THIS_DISPLAY .= "</SELECT>&nbsp;<SPAN ID=\"AMPM\">AM</SPAN>";
		
					if ($EDIT_FLAG == "on") {
						$THIS_DISPLAY .= "\n\n<SCRIPT LANGUAGE=JAVASCRIPT>\n\n     set_ampm($F_DATA[0]);\n\n</SCRIPT>\n\n";
					}
		
				} // End Time Selections
		
				$THIS_DISPLAY .= "</TD></TR>\n\n";
		
			}
		
			if ($EDIT_FLAG == "on") {
		
				$tmp = strlen($edit_tmp);
				$new = $tmp - 5;
				$edit_tmp = substr($edit_tmp, 0, $new);
		
				$BTN_TITLE = " Save Changed Data ";
				$THIS_DISPLAY .= "<TEXTAREA NAME=EDIT_WHERE_STRING STYLE='display: none;'>$KEYFIELD = '$FIX_TMP[$KEYFIELD]'</TEXTAREA>\n";
		
			} else {
		
				$BTN_TITLE = " Save New Record Data ";
		
			}
		
			if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }
		
			$THIS_DISPLAY .= "<TR>\n";
			$THIS_DISPLAY .= "<TD COLSPAN=2 ALIGN=CENTER VALIGN=TOP BGCOLOR=$BGCOLOR>\n";
			$THIS_DISPLAY .= "<INPUT TYPE=SUBMIT ".$btn_save." VALUE=\"$BTN_TITLE\">\n\n";
			$THIS_DISPLAY .= "</TD></TR></TABLE></FORM>\n";
		
		} // End Add New Record Action
		
		#######################################################
		### START HTML/JAVASCRIPT CODE			            ###
		#######################################################
		
		$MOD_TITLE = lang("Table Manager: Enter/Edit Record Data")." '$mt'";
		
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
		
		SV2_showHideLayers('addCartMenu?header','','hide');
		SV2_showHideLayers('blankLayer?header','','hide');
		SV2_showHideLayers('linkLayer?header','','hide');
		SV2_showHideLayers('newsletterLayer?header','','hide');
		SV2_showHideLayers('cartMenu?header','','show');
		SV2_showHideLayers('menuLayer?header','','hide');
		SV2_showHideLayers('editCartMenu?header','','hide');
		
		function set_ampm(v) {		
			if (v > 11) {
				AMPM.innerHTML = "PM";
			} else {
				AMPM.innerHTML = "AM";
			}		
			if (v == 24) {
				AMPM.innerHTML = "AM";
			}		
		}

		
		function confirm_delete(table,key) {
				<?php echo "var tiny = window.confirm('".lang("You have selected to delete this record.")."\\n".lang("You will not be able to undo this choice.")."\\n\\n".lang("Do you wish to continue with this action")."?');"; ?>
				if (tiny != false) {
					// OK Redirect to Send Routine
					window.location = "<?php echo $simple_name; ?>?mysqlmode=enter_edit&ACTION=DELETE_RECORD_NOW&ID="+key+"&mt="+table+"&<?=SID?>";
				}
		}
		
		//-->
		</script>
		
		<style>
		/* Formatted timestamp values */
		.formatted_timestamp {
		   font-style: italic;
		   font-size: 90%;
		}
		
		span.formatted_serialized {
		   /*font-size: 90%;*/
		   /*color: red;*/
		   display: block;
		   width: 450px;
		   height: 175px;
		   overflow: auto;
		}
		
		a.sup:link {color: #980404; text-decoration: none; font-family: Arial; font-size: 7pt;}
		a.sup:visited {color: #980404; text-decoration: none; font-family: Arial; font-size: 7pt;}
		a.sup:hover {color: white; text-decoration: none; font-family: Arial; font-size: 7pt; background: #980404;}
		
		table#timstamp_field_select td input,
		table#timstamp_field_select td label {
		   float: left;
		   /*border: 1px solid red;*/
		}
		table#timstamp_field_select td input {
		   margin-top: 0px;
		}
		table#timstamp_field_select td label {
		   margin-top: 1px;
		   margin-right: 5px;
		}
		
		</style>
		
		
		<body bgcolor=white text=black link=darkblue vlink=darkblue alink=darkblue leftmargin=0 topmargin=0 marginwidth=0 marginheight=0 onLoad="show_hide_layer('Layer1','','hide','userOpsLayer','','show');">
		
		<!-- ============================================================ -->
		<!-- ============= LOAD MODULE DISPLAY LAYER ==================== -->
		<!-- ============================================================ -->
		
		<DIV ID="Layer1" style="position:absolute; left:0px; top:40%; width:100%; height:110px; z-index:100; border: 2px none #000000; visibility: hidden; overflow: hidden">
		
		  <table border=0 cellpadding=0 width=100% height=100% bgcolor=WHITE>
		    <tr>
		      <td align=center valign=middle class=text>Loading...<br/>
				<img src="../../../../icons/ajax-loader2.gif" width=60 height=30 border=0>
		      </td>
		    </tr>
		  </table>
		
		</DIV>
		
		<!--<DIV ID="userOpsLayer" style="position:absolute; visibility:userOpsLayer; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000">--->
		<DIV ID="userOpsLayer" style="visibility:userOpsLayer; width:100%; height:100%; border: 1px none <?php $bgcolor; ?>">
		<!---Module heading--->
		<table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="feature_sub" style="margin-top: 0px;">
		 <tr>
		  <td colspan="2" valign="top" class="nopad">
		   <table width="100%" border="0" cellspacing="0" cellpadding="5" class="feature_module_heading">
		    <tr>
		     <td colspan="2" class="fgroup_title">
		      <a href="<?php echo $simple_name."?mysqlmode=downloaddata"; ?>">Database Table Manager</a> &gt;
		      <a href="<?php echo $_SERVER['PHP_SELF']; ?>?mysqlmode=enter_edit&mt=<?php echo $mt; ?>" class="bold"><?php echo $mt; ?></a>
		     </td>
		     <td class="fgroup_title right" style="padding-right: 15px;">
		      &nbsp;
		     </td>
		    </tr>
		   </table>
		  </td>
		 </tr>
		 <tr>
		  <td valign="top">
		
		<?php
		# Log in recent table list for quick links elsewhere
		$_SESSION['recent_tables'][strtolower($_REQUEST['mt'])] = $_REQUEST['mt']; // strtolower necc for ksort()
		
		# Readability/convenience on action link urls
		$base_href = $simple_name."?mysqlmode=enter_edit&mt=".$mt."&TBL_SEARCH_FOR=".$_REQUEST['TBL_SEARCH_FOR']."&amp;searchin=".$_REQUEST['searchin'];
		
		if ($ACTION == "" || $ACTION == "show_all" ) {
		
			// Search Capability added for V4.5
			// ==============================================================================================
			$result = mysql_query("SELECT $KEYFIELD FROM $mt");
			$totalRecs = mysql_num_rows($result);
		
		   /*---------------------------------------------------------------------------------------------------------*
		                                     __                     _        _  _               _
		    _ __  ___  _ __  __  ___  _ _   / _| ___  ___ ___  _ _ (_) __ _ | |(_) ___  ___  __| |
		   | '_ \/ _ \| '_ \/ _|/ _ \| ' \ |  _||___|(_-</ -_)| '_|| |/ _` || || ||_ / / -_)/ _` |
		   | .__/\___/| .__/\__|\___/|_||_||_|       /__/\___||_|  |_|\__,_||_||_|/__| \___|\__,_|
		   |_|        |_|
		   /*---------------------------------------------------------------------------------------------------------*/
			# popconfig-serialized
			$popup = "";
		   $popup .= "<form name=\"mulihide_form\" method=\"post\" action=\"".$simple_name."?mysqlmode=enter_edit\">\n";
		   $popup .= "<input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
		   $popup .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
		   $popup .= "<input type=\"hidden\" name=\"todo\" value=\"format_serialized\"/>\n";
		   $popup .= "Note: If you're not a php programmer/developer, this is probably not of any use to you.\n";
			$popup .= "<p>Select field(s) containing serialized array data. Note that this will not actually alter the data.\n";
			$popup .= " It's just meant to display serialized array data in a more concised, readable format.</p>\n";
			$qry = "SHOW COLUMNS FROM ".$mt;
			$fieldrez = mysql_query($qry);
			$col = 1;
			$maxcols = 6;
			$counter = 0;
			while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
			   $counter++;
			   $tdidname = "block_".$counter;
			   $idname = "serialchkbox_".$counter;
			   if ( $col == 1 ) {
		         $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;margin-top: 10px;\">\n";
		         $popup .= " <tr>\n";
		      }
		
		      # checked? bolded?
		      if ( isset($_SESSION['serialized_fields'][$_REQUEST['mt']][$getCol['Field']]) ) { $checked = " checked"; $unbold = ""; } else { $checked = ""; $unbold = "unbold"; }
		
		      $onmouseover = "onmouseover=\"setClass(this.id, 'col_title font90 ".$unbold." center hand bg_yellow black');\"";
		      $onmouseover .= " onmouseout=\"setClass(this.id, 'col_title font90 ".$unbold." center hand');\"";
		      $onmouseover .= " onclick=\"toggle_checkbox('".$idname."');\"";
		
		      $cbox_border = "border: 1px inset #ccc;border-style: inset none inset inset";
		      $colname_border = "border: 1px inset #ccc;border-style: inset inset inset none";
		
		      $popup .= "  <td id=\"".$tdidname."-1\" class=\"col_title ".$unbold." font90 center hand\" style=\"padding-left: 5px;".$cbox_border."\">\n";
		      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"serialized_fields[]\" value=\"".$getCol['Field']."\"".$checked.">\n";
		      $popup .= "  </td>\n";
		
		      $popup .= "  <td id=\"".$tdidname."\" class=\"col_title ".$unbold." font90 center hand\" ".$onmouseover." style=\"".$colname_border."\">\n";
		      if ( eregi("time|date", $getCol['Field']) ) { $display_colname = "<span class=\"bold\">".$getCol['Field']."</span>"; } else { $display_colname = $getCol['Field']; }
		      $popup .= "   ".$display_colname."\n";
		      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"serialized_fields[]\" value=\"".$getCol['Field']."\"".$checked." style=\"display: none;\">\n";
		      $popup .= "  </td>\n";
		//      $popup .= "  <td style=\"width: 40px;background-color: #f8f9fd;\">&nbsp;</td>\n";
		      if ( $col == $maxcols ) {
		         $popup .= " </tr>\n";
		         $popup .= "</table>\n";
		         $col = 1;
		      } else {
		         $col++;
		      }
		   }
		   $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;width: 100%;\">\n";
		   $popup .= " <tr>\n";
		   $popup .= "  <td align=\"right\">\n";
		   $popup .= "   <script type=\"text/javascript\">\n";
		   $popup .= "   function multihide_all(checkthem) {\n";
		   $popup .= "      for ( x = 1; x <= ".$counter."; x++ ) {\n";
		   $popup .= "         \$('chkbox_'+x).checked = checkthem;\n";
		   $popup .= "      }\n";
		   $popup .= "   }\n";
		   $popup .= "   </script>\n";
		   $popup .= "   <a href=\"#\" onclick=\"multihide_all('thisstringdoesntmatter')\">check all</a> |\n";
		   $popup .= "   <a href=\"#\" onclick=\"multihide_all()\">un-check all</a>\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		   $popup .= " <tr>\n";
		   $popup .= "  <td align=\"right\">\n";
		   $popup .= "   <input type=\"submit\" value=\"Apply Changes &gt;&gt;\" ".$btn_save.">\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		   $popup .= "</table>\n";
		   $popup .= "</form>\n";
		   //$THIS_DISPLAY .= help_popup("popconfig-serialized", "Format serialized array data for display", $popup, "left: 7%;top: 15%;width: 700px;opacity: .95;");
		
		
		   /*---------------------------------------------------------------------------------------------------------*
				Base64_Decode
		   /*---------------------------------------------------------------------------------------------------------*/
			# popconfig-serialized
			$popup = "";
		   $popup .= "<form name=\"mulihide_form\" method=\"post\" action=\"".$simple_name."?mysqlmode=enter_edit\">\n";
		   $popup .= "<input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
		   $popup .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
		   $popup .= "<input type=\"hidden\" name=\"todo\" value=\"format_decode\"/>\n";
		   $popup .= "Note: Some field may store encoded data.  This tool decodes those fields.\n";
			$popup .= "<p>Select field(s) containing encoded data. Note that this will not actually alter the data.\n";
			$popup .= " It's just meant to display the encoded data in a readable format.</p>\n";
			$qry = "SHOW COLUMNS FROM ".$mt;
			$fieldrez = mysql_query($qry);
			$col = 1;
			$maxcols = 6;
			$counter = 0;
			while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
			   $counter++;
			   $tdidname = "block_".$counter;
			   $idname = "serialchkbox_".$counter;
			   if ( $col == 1 ) {
		         $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;margin-top: 10px;\">\n";
		         $popup .= " <tr>\n";
		      }
		
		      # checked? bolded?
		      if ( isset($_SESSION['decode_fields'][$_REQUEST['mt']][$getCol['Field']]) ) { $checked = " checked"; $unbold = ""; } else { $checked = ""; $unbold = "unbold"; }
		
		      $onmouseover = "onmouseover=\"setClass(this.id, 'col_title font90 ".$unbold." center hand bg_yellow black');\"";
		      $onmouseover .= " onmouseout=\"setClass(this.id, 'col_title font90 ".$unbold." center hand');\"";
		      $onmouseover .= " onclick=\"toggle_checkbox('".$idname."');\"";
		
		      $cbox_border = "border: 1px inset #ccc;border-style: inset none inset inset";
		      $colname_border = "border: 1px inset #ccc;border-style: inset inset inset none";
		
		      $popup .= "  <td id=\"".$tdidname."-1\" class=\"col_title ".$unbold." font90 center hand\" style=\"padding-left: 5px;".$cbox_border."\">\n";
		      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"decode_fields[]\" value=\"".$getCol['Field']."\"".$checked.">\n";
		      $popup .= "  </td>\n";
		
		      $popup .= "  <td id=\"".$tdidname."\" class=\"col_title ".$unbold." font90 center hand\" ".$onmouseover." style=\"".$colname_border."\">\n";
		      if ( eregi("time|date", $getCol['Field']) ) { $display_colname = "<span class=\"bold\">".$getCol['Field']."</span>"; } else { $display_colname = $getCol['Field']; }
		      $popup .= "   ".$display_colname."\n";
		      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"decode_fields[]\" value=\"".$getCol['Field']."\"".$checked." style=\"display: none;\">\n";
		      $popup .= "  </td>\n";
		//      $popup .= "  <td style=\"width: 40px;background-color: #f8f9fd;\">&nbsp;</td>\n";
		      if ( $col == $maxcols ) {
		         $popup .= " </tr>\n";
		         $popup .= "</table>\n";
		         $col = 1;
		      } else {
		         $col++;
		      }
		   }
		   $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;width: 100%;\">\n";
		   $popup .= " <tr>\n";
		   $popup .= "  <td align=\"right\">\n";
		   $popup .= "   <script type=\"text/javascript\">\n";
		   $popup .= "   function multihide_all(checkthem) {\n";
		   $popup .= "      for ( x = 1; x <= ".$counter."; x++ ) {\n";
		   $popup .= "         \$('chkbox_'+x).checked = checkthem;\n";
		   $popup .= "      }\n";
		   $popup .= "   }\n";
		   $popup .= "   </script>\n";
		   $popup .= "   <a href=\"#\" onclick=\"multihide_all('thisstringdoesntmatter')\">check all</a> |\n";
		   $popup .= "   <a href=\"#\" onclick=\"multihide_all()\">un-check all</a>\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		   $popup .= " <tr>\n";
		   $popup .= "  <td align=\"right\">\n";
		   $popup .= "   <input type=\"submit\" value=\"Apply Changes &gt;&gt;\" ".$btn_save.">\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		   $popup .= "</table>\n";
		   $popup .= "</form>\n";
		   //$THIS_DISPLAY .= help_popup("popconfig-decode", "Decode encoded data for display", $popup, "left: 7%;top: 15%;width: 700px;opacity: .95;");
		
		
		
		   /*---------------------------------------------------------------------------------------------------------*
		    ___             _   _               _____  _                  _
		   | _ \ ___  _ __ | | | | _ __   ___  |_   _|(_) _ __   ___  ___| |_  __ _  _ __   _ __  ___
		   |  _// _ \| '_ \| |_| || '_ \ |___|   | |  | || '  \ / -_)(_-<|  _|/ _` || '  \ | '_ \(_-<
		   |_|  \___/| .__/ \___/ | .__/         |_|  |_||_|_|_|\___|/__/ \__|\__,_||_|_|_|| .__//__/
		             |_|          |_|                                                      |_|
		   /*---------------------------------------------------------------------------------------------------------*/
			# popup-timestamps
			$popup = "";
		   $popup .= "<form name=\"mulihide_form\" method=\"post\" action=\"".$simple_name."?mysqlmode=enter_edit\">\n";
		   $popup .= "<input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
		   $popup .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
		   $popup .= "<input type=\"hidden\" name=\"todo\" value=\"format_timestamps\"/>\n";
			$popup .= "<p>Select timestamp field(s) to format as dates. Note that this will not actually alter the data.\n";
			$popup .= " It's just meant to display timestamp values as meaningful date/time strings to you can read them more easily.</p>\n";
			$qry = "SHOW COLUMNS FROM ".$mt;
			$fieldrez = mysql_query($qry);
			$col = 1;
			$maxcols = 6;
			$counter = 0;
			while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
			   $counter++;
			   $tdidname = "block_".$counter;
			   $idname = "timechkbox_".$counter;
			   if ( $col == 1 ) {
		         $popup .= "<table id=\"timstamp_field_select\" border=\"1\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;margin-top: 10px;\">\n";
		         $popup .= " <tr>\n";
		      }
		
		      # checked? bolded?
		      if ( isset($_SESSION['timestamp_fields'][$_REQUEST['mt']][$getCol['Field']]) ) { $checked = " checked"; $unbold = ""; } else { $checked = ""; $unbold = "unbold"; }
		
		      $onmouseover = "onmouseover=\"setClass(this.id, 'col_title font90 ".$unbold." center hand bg_yellow black');\"";
		      $onmouseover .= " onmouseout=\"setClass(this.id, 'col_title font90 ".$unbold." center hand');\"";
		//      $onmouseover .= " onclick=\"toggle_checkbox('".$idname."');\"";
		
		//      $cbox_border = "border: 1px inset #ccc;border-style: inset none inset inset";
		//      $colname_border = "border: 1px inset #ccc;border-style: inset inset inset none";
		
		      if ( eregi("time|date", $getCol['Field']) ) { $display_colname = "<span class=\"bold\">".$getCol['Field']."</span>"; } else { $display_colname = $getCol['Field']; }
		      $popup .= "  <td id=\"".$tdidname."-1\" class=\"col_title ".$unbold." font90 center\" style=\"padding-left: 5px;\">\n";
		      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"timestamp_fields[]\" value=\"".$getCol['Field']."\"".$checked.">\n";
		      $popup .= "   <label for=\"".$idname."\">".$display_colname."</label>\n";
		      $popup .= "  </td>\n";
		
		//      $popup .= "  <td style=\"width: 40px;background-color: #f8f9fd;\">&nbsp;</td>\n";
		      if ( $col == $maxcols ) {
		         $popup .= " </tr>\n";
		         $popup .= "</table>\n";
		         $col = 1;
		      } else {
		         $col++;
		      }
		   }
		   $popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;width: 100%;\">\n";
		   $popup .= " <tr>\n";
		   $popup .= "  <td align=\"right\">\n";
		   $popup .= "   <script type=\"text/javascript\">\n";
		   $popup .= "   function chkall_timestamps(checkthem) {\n";
		   $popup .= "      for ( x = 1; x <= ".$counter."; x++ ) {\n";
		   $popup .= "         \$('timechkbox_'+x).checked = checkthem;\n";
		   $popup .= "      }\n";
		   $popup .= "   }\n";
		   $popup .= "   </script>\n";
		   $popup .= "   <a href=\"#\" onclick=\"chkall_timestamps('thisstringdoesntmatter')\">check all</a> |\n";
		   $popup .= "   <a href=\"#\" onclick=\"chkall_timestamps()\">un-check all</a>\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		
		   # Date/Time format
		   $popup .= " <tr>\n";
		   $popup .= "  <td align=\"right\">\n";
		   $popup .= "   <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;\">\n";
		   $popup .= "    <tr>\n";
		   $popup .= "     <td>Date/Time Format:</td>\n";
		   $popup .= "     <td>\n";
		   $popup .= "      <select id=\"timestamp_date_format\" name=\"timestamp_date_format\">\n";
		   $popup .= "       <option value=\"M d, Y\">".date("M d, Y")."</option>\n";
		   $popup .= "       <option value=\"M d, Y - g:ia\">".date("M d, Y - g:ia")."</option>\n";
		   $popup .= "       <option value=\"D m/d - g:ia\">".date("D m/d - g:ia")."</option>\n";
		   $popup .= "      </select>\n";
		   $popup .= "      <script type=\"text/javascript\">\n";
		   $popup .= "       \$('timestamp_date_format').value = '".$_SESSION['timestamp_date_format'][$_REQUEST['mt']]."';\n";
		   $popup .= "      </script>\n";
		   $popup .= "     </td>\n";
		   $popup .= "    </tr>\n";
		   $popup .= "   </table>\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		
		   $popup .= " <tr>\n";
		   $popup .= "  <td align=\"right\">\n";
		   $popup .= "   <input type=\"submit\" value=\"Apply Changes &gt;&gt;\" ".$btn_save.">\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		   $popup .= "</table>\n";
		   $popup .= "</form>\n";
		   //$THIS_DISPLAY .= help_popup("popup-timestamps", "Format timestamps as human date strings", $popup, "left: 7%;top: 15%;width: 700px;opacity: .95;");
		
		
			# popup-hide_fields
			$popup = "";
		   $popup .= "<form name=\"mulihide_form\" method=\"post\" action=\"".$simple_name."?mysqlmode=enter_edit\">\n";
		   $popup .= "<input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
		   $popup .= "<input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
		   $popup .= "<input type=\"hidden\" name=\"todo\" value=\"hide_multi_fields\"/>\n";
			$popup .= "<p>Select fields to hide...</p>\n";
			$popup .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;\">\n";
		
			$qry = "SHOW COLUMNS FROM ".$mt;
			$fieldrez = mysql_query($qry);
			$col = 1;
			$maxcols = 4;
			$counter = 0;
			while ( $getCol = mysql_fetch_assoc($fieldrez) ) {
			   $counter++;
			   $idname = "chkbox_".$counter;
			   if ( $col == 1 ) {
		         $popup .= " <tr>\n";
		      }
		
		      $onmouseover = "onclick=\"toggle_checkbox('".$idname."');\"";
		
		      if ( isset($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']][$getCol['Field']]) ) { $checked = " checked"; } else { $checked = ""; }
		      $popup .= "  <td class=\"hand\" ".$onmouseover.">\n";
		      $popup .= "   <input type=\"checkbox\" id=\"".$idname."\" name=\"multi_hide_fields[]\" value=\"".$getCol['Field']."\"".$checked.">\n";
		      $popup .= "  </td>\n";
		      $popup .= "  <td class=\"hand\" ".$onmouseover.">\n";
		      $popup .= "   ".$getCol['Field']."\n";
		      $popup .= "  </td>\n";
		      $popup .= "  <td style=\"width: 50px;background-color: #f8f9fd;\">&nbsp;</td>\n";
		
		      if ( $col == $maxcols ) {
		         $popup .= " </tr>\n";
		         $col = 1;
		      } else {
		         $col++;
		      }
		   }
		
		   # check all
		   $popup .= " <tr>\n";
		   $popup .= "  <td colspan=\"".($maxcols * 2)."\" align=\"right\">\n";
		   $popup .= "   <script type=\"text/javascript\">\n";
		   $popup .= "   function multihide_all(checkthem) {\n";
		   $popup .= "      for ( x = 1; x <= ".$counter."; x++ ) {\n";
		   $popup .= "         \$('chkbox_'+x).checked = checkthem;\n";
		   $popup .= "      }\n";
		   $popup .= "   }\n";
		   $popup .= "   </script>\n";
		   $popup .= "   <a href=\"#\" onclick=\"multihide_all('thisstringdoesntmatter')\">check all</a> |\n";
		   $popup .= "   <a href=\"#\" onclick=\"multihide_all()\">un-check all</a>\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		   # [ Apply Changes >> ]
		   $popup .= " <tr>\n";
		   $popup .= "  <td colspan=\"".($maxcols * 2)."\" align=\"right\">\n";
		   $popup .= "   <input type=\"submit\" value=\"Apply Changes &gt;&gt;\" ".$btn_save.">\n";
		   $popup .= "  </td>\n";
		   $popup .= " </tr>\n";
		   $popup .= "</table>\n";
		   $popup .= "</form>\n";
		   //$THIS_DISPLAY .= help_popup("popup-hide_fields", "Hide/show multiple fields", $popup, "left: 5%;top: 15%;width: 650px;opacity: .95;");
		
		
		   /*---------------------------------------------------------------------------------------------------------*
		    ___                ___
		   | _ \ _  _  _ _    / _ \  _  _  ___  _ _  _  _
		   |   /| || || ' \  | (_) || || |/ -_)| '_|| || |
		   |_|_\ \_,_||_||_|  \__\_\ \_,_|\___||_|   \_, |
		                                             |__/
		
		   # popup-custom_qry - Popup layer with form to run custom query on database
		   /*---------------------------------------------------------------------------------------------------------*/
		   # Display popup by default if just ran a query
		   if ( $_REQUEST['todo'] == "killqry" || $_REQUEST['todo'] == "run_qry" ) { $qry_display = "block"; } else { $qry_display = "none"; }
		   $THIS_DISPLAY .= "<div id=\"popup-custom_qry\" style=\"opacity: 0.90;display: ".$qry_display.";width: 600px;position: absolute;left: 12%;top: 27%;border: 1px solid #ccc;z-index: 2;background-color: #f8f9fd;text-align: left;padding: 0;\">\n";
		   $THIS_DISPLAY .= " <form method=\"post\" action=\"".$simple_name."?mysqlmode=enter_edit\">\n";
		   $THIS_DISPLAY .= " <input type=\"hidden\" name=\"ACTION\" value=\"\"/>\n";
		   $THIS_DISPLAY .= " <input type=\"hidden\" name=\"mt\" value=\"".$mt."\"/>\n";
		   $THIS_DISPLAY .= " <input type=\"hidden\" name=\"todo\" value=\"run_qry\"/>\n";
		   $THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"marginfix\">\n";
		   $THIS_DISPLAY .= "  <tr>\n";
		   $THIS_DISPLAY .= "   <td colspan=\"2\">\n";
		   $THIS_DISPLAY .= "    <h2 style=\"margin-bottom: 0;\">Run custom datbase query</h2>\n";
		   $THIS_DISPLAY .= "    <p style=\"margin-top: 3px;\">For advanced users only.</p>\n";
		   $THIS_DISPLAY .= "   </td>\n";
		   $THIS_DISPLAY .= "  </tr>\n";
		   $THIS_DISPLAY .= "  <tr>\n";
		   $THIS_DISPLAY .= "   <td>MySQL Query String:<br/>\n";
		   $THIS_DISPLAY .= "    <textarea id=\"custom_qry_string\" name=\"runthis\" style=\"opacity: 1;width: 450px; height: 65px;\"></textarea><br/>\n";
		   $THIS_DISPLAY .= "   </td>\n";
		   $THIS_DISPLAY .= "   <td align=\"center\"><input type=\"submit\" name=\"submit\" value=\"Run Query\"></td>\n";
		   $THIS_DISPLAY .= "  </tr>\n";
		
		   # Previous Queries:
		   if ( count($_SESSION['dtm_custom_qry']) > 0 ) {
		      $THIS_DISPLAY .= "  <tr>\n";
		      $THIS_DISPLAY .= "   <td colspan=\"2\">\n";
		      $THIS_DISPLAY .= "    <b>Previous Queries:</b> (click to put text in query box...doesn't actually run it when you click)<br/>\n";
		
		      $THIS_DISPLAY .= "    <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"marginfix\" style=\"font-size: 90%;\">\n";
		
		      foreach ( $_SESSION['dtm_custom_qry'] as $qryid=>$qrystring ) {
		         if ( $qrystring != "" ) {
		            $THIS_DISPLAY .= "    <tr>\n";
		            $THIS_DISPLAY .= "     <td>\n";
		            $THIS_DISPLAY .= "      <span class=\"hand gray_31\" onclick=\"document.getElementById('custom_qry_string').innerHTML=this.innerHTML;\">";
		            $THIS_DISPLAY .= stripslashes($qrystring)."\n";
		            $THIS_DISPLAY .= "</span>\n";
		            $THIS_DISPLAY .= "     </td>\n";
		
		            # [x]
		            $THIS_DISPLAY .= "     <td valign=\"top\">\n";
		            $THIS_DISPLAY .= "      [<a href=\"".$simple_name."?mysqlmode=enter_edit&mt=".$mt."&todo=killqry&qryid=".$qryid."\" class=\"red\">x</a>]\n"; // Remove this one from history
		            $THIS_DISPLAY .= "     </td>\n";
		            $THIS_DISPLAY .= "    </tr>\n";
		         }
		      }
		      $THIS_DISPLAY .= "    </table>\n";
		      $THIS_DISPLAY .= "   </td>\n";
		      $THIS_DISPLAY .= "  </tr>\n";
		   }
		
		   $THIS_DISPLAY .= " </table>\n";
		   $THIS_DISPLAY .= " </form><br/><br/>\n";
		
		   # [x] close
		   $THIS_DISPLAY .= " <div id=\"popup-custom_qry-closebar\" onclick=\"hideid('popup-custom_qry');\" onmouseover=\"setClass(this.id, 'hand bg_red_d7 white right');\"  onmouseout=\"setClass(this.id, 'hand bg_red_98 white right');\" class=\"hand bg_red_98 white right\" style=\"padding: 3px;\">[x] close</div>\n";
		
		   $THIS_DISPLAY .= "</div>\n";
		   #---END custom query popup layer-------------------------------------------------------------------
		
			# Setup Display of Record Data
			#-----------------------------------------------------------
		   # Get total records in table
		   $result = mysql_query("SELECT $KEYFIELD FROM $mt");
		   $total_recs = mysql_num_rows($result);
		
			# Limit to 10 at a time by default
			if ( $ACTION != "show_all" ) { // Mantis #252
			   $num_to_show = 10;
			} else {
			   $num_to_show = $total_recs;
			}
		
			if ($start_show == "") { $start_show = 0; }
		
			$noShowFlag = 0;
		
			# Display full table or just search results?
			if ($TBL_SEARCH_FOR == "") {
		
			   # Custom SELECT via 'Run custom query'?
			   if ( $customselectqry ) {
			      # CUSTOM: Fetch array to display results of custom select query
			      if ( get_magic_quotes_gpc() ) { $qry = stripslashes($_POST['runthis']); } else { $qry = $_POST['runthis']; }
		//	      echo "<textarea style=\"width: 400px;\">".$qry."</textarea>\n"; // testing
		
			   } else {
		   	   # NORMAL: Full table (all records)
		   	   $qry = "SELECT * FROM ".$_REQUEST['mt']." ORDER BY ".$_SESSION[$_REQUEST['mt']]['orderby']." ".$_SESSION[$_REQUEST['mt']]['orderhow']." LIMIT ".$start_show.", ".$num_to_show;
		   	}
		
				$result = mysql_query($qry);
		
			} else {
		      /*---------------------------------------------------------------------------------------------------------*
		         ____                      __     ____
		        / __/___  ___ _ ____ ____ / /    / __ \  ____ __ __
		       _\ \ / -_)/ _ `// __// __// _ \  / /_/ / / __// // /
		      /___/ \__/ \_,_//_/   \__//_//_/  \___\_\/_/   \_, /
		                                                    /___/
		      /*---------------------------------------------------------------------------------------------------------*/
				# search in specific field option checked?
				if ( $_REQUEST['searchin'] == "" ) {
		   		# NO: Build "or [field] like.." for every field
		         $qry = "SHOW COLUMNS FROM ".$_REQUEST['mt'];
		         $rez = mysql_query($qry);
		         $query_string = "";
		
		         # For pulling field type below
		         $qry4type = 'select * from '.$_REQUEST['mt'].' limit 1';
		         $rez4type = mysql_query($qry4type);
		
		         $xInt = 0;
		         while ( $getCol = mysql_fetch_assoc($rez) ) {
		            # special search qry for blog field case insensitivity
		            if ( strtolower(mysql_field_type($rez4type, $xInt)) == 'blob' ) {
		               # Blob search method varies by mysql version
		               if( mysql_get_client_info() >= 4 ) {
		                  $query_string .= "CAST(".$getCol['Field']." as char) LIKE '%".$TBL_SEARCH_FOR."%' or ";
		               } else {
		                  $query_string .= "lcase(".$getCol['Field'].") LIKE lcase('%".$TBL_SEARCH_FOR."%') or ";
		               }
		            } else {
		               # not a blob, use normal qry
		               $query_string .= $getCol['Field']." LIKE '%".$TBL_SEARCH_FOR."%' OR ";
		            }
		            $xInt++;
		         }
		
		
		   		$tmp = strlen($query_string);
		   		$tv = $tmp - 3;
		   		$query_string = substr($query_string, 0, $tv);
		   		$_SESSION[$_REQUEST['mt']]['searchin'] = NULL;
		
		   	} else {
		   	   # YES: Just add one field to qry string
		   	   $query_string = $_REQUEST['searchin']." LIKE '%".$TBL_SEARCH_FOR."%'";
		   	   $_SESSION[$_REQUEST['mt']]['searchin'] = $_REQUEST['searchin'];
		   	}
		
				# Actual search qry
			   $qry = "SELECT * FROM ".$_REQUEST['mt']." WHERE ".$query_string;
			   $qry .= " ORDER BY ".$_SESSION[$_REQUEST['mt']]['orderby']." ".$_SESSION[$_REQUEST['mt']]['orderhow'];
			   $result = mysql_query($qry);
		
				$totalRecs = mysql_num_rows($result);
		
				# Flag for next and previous links
				$noShowFlag = 1;
		
				# Save last search term in session for easy re-searching
				$_SESSION[$_REQUEST['mt']]['last_search_term'] = $TBL_SEARCH_FOR;
				$_SESSION[$_REQUEST['mt']]['last_search_results'] = $totalRecs;
		
			} // End Search Option
		
			$numberRows = mysql_num_rows($result);
			$numberFields = mysql_num_fields($result);
			$numberFields--;
		
		
		   /*---------------------------------------------------------------------------------------------------------*
		    ___  _          _               ___   _
		   / __|| |_  __ _ | |_  _  _  ___ |   \ (_)__ __
		   \__ \|  _|/ _` ||  _|| || |(_-< | |) || |\ V /
		   |___/ \__|\__,_| \__| \_,_|/__/ |___/ |_| \_/
		   /*---------------------------------------------------------------------------------------------------------*/
		   $status_div = "<div style=\"color: #999;text-align: left; padding: 0 0 3px 8px;\">\n";
		
		   # Total number of records found ('show all' link)
		   $qry = "select ".$KEYFIELD." from ".$_REQUEST['mt'];
		   $rez = mysql_query($qry);
		   $total_in_table = mysql_num_rows($rez);
		
		   if ( $_SESSION[$_REQUEST['mt']]['last_search_term'] != "" ) {
		      # Last Search:
		      $status_div .= "      <span style=\"margin-left: 5px;\">Last Search:</span>\n";
		
		      # "realestate" in DOMAIN_NAME
		      $status_div .= "       <a href=\"".$simple_name."?mysqlmode=enter_edit&mt=".$mt."&TBL_SEARCH_FOR=".$_SESSION[$_REQUEST['mt']]['last_search_term']."&amp;searchin=".$_SESSION[$_REQUEST['mt']]['searchin']."\" class=\"noline\">";
		      $status_div .= "&quot;".$_SESSION[$_REQUEST['mt']]['last_search_term']."&quot;";
		      # searchin?
		      if ( $_SESSION[$_REQUEST['mt']]['searchin'] != "" ) {
		         $status_div .= "   in ".$_SESSION[$_REQUEST['mt']]['searchin'];
		      } else {
		         $status_div .= "   in all fields";
		      }
		      $status_div .= "</a>";
		
		      $status_div .= " (<b>".$_SESSION[$_REQUEST['mt']]['last_search_results']."</b> matches out of ".$total_in_table." total records)\n";
		
		      # [x]
		      $status_div .= " <a href=\"".$simple_name."?mysqlmode=enter_edit&mt=".$_REQUEST['mt']."&amp;kill_lastsearch=yes\" class=\"del font90\">[x]</a>\n";
		
		   } else {
		      # Total Number of Records in Table (show all)
		      $status_div .= " ".lang("Total Number of Records in Table").": <b>".$total_in_table."</b>\n";
		      $status_div .= " ( <a href=\"".$simple_name."?mysqlmode=enter_edit&mt=".$mt."&ACTION=show_all\">".lang("show all")."</a> )\n";
		   }
		
		
		   # Sorting in effect?
		   if ( $_SESSION[$_REQUEST['mt']]['orderby'] != $KEYFIELD ) {
		      $status_div .= " | Sorting by ".$_SESSION[$_REQUEST['mt']]['orderby']." in ".$_SESSION[$_REQUEST['mt']]['orderhow']."ending order.";
		      $status_div .= " <a href=\"".$base_href."&amp;kill_orderby=yes\" class=\"del font90\">[x]</a>\n";
		   }
		   $status_div .= "</div>\n";
		
		   $THIS_DISPLAY .= $status_div;
		
		
			$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" class=smtext width=\"100%\">\n";
			$THIS_DISPLAY .= " <tr>\n\n";
		
			# Search form
			$THIS_DISPLAY .= "  <form method=\"post\" action=\"".$simple_name."?mysqlmode=enter_edit\">\n";
			$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\">\n";
		   $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"ACTION\" value=\"\">\n";
		   $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"mt\" value=\"".$_REQUEST['mt']."\">\n";
		
		   # TBL_SEARCH_FOR
		   $THIS_DISPLAY .= "   <input type=\"text\" class=\"text\" name=\"TBL_SEARCH_FOR\" style='width: 200px;'>\n";
		
		   $THIS_DISPLAY .= "   <table cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">\n";
		   $THIS_DISPLAY .= "    <tr>\n";
		
		   # Within...
		   $THIS_DISPLAY .= "     <td align=\"right\">";
		   $onclick = "onclick=\"hideid('withinlink');showid('searchin');showid('withinx');\"";
		   $THIS_DISPLAY .= "      <span id=\"withinlink\" style=\"display: block;font-size: 90%;\" class=\"blue uline hand\" ".$onclick.">within field...</span>\n";
		
		   # searchin
		   $THIS_DISPLAY .= "      <div id=\"searchin_opt\">\n";
		   $THIS_DISPLAY .= "       <select id=\"searchin\" class=\"text\" name=\"searchin\" style=\"font-size: 90%;display: none;\">\n";
		   $THIS_DISPLAY .= "        <option value=\"\">choose...</option>\n";
		   $qry = "SHOW COLUMNS FROM ".$_REQUEST['mt'];
		   $rez = mysql_query($qry);
		   while ( $getCol = mysql_fetch_assoc($rez) ) {
		      $THIS_DISPLAY .= "        <option value=\"".$getCol['Field']."\">".$getCol['Field']."</option>\n";
		   }
		   $THIS_DISPLAY .= "       </select>\n";
		   $THIS_DISPLAY .= "      </div>\n";
		
		   # [x]
		   $onclick = "onclick=\"hideid('searchin');showid('withinlink');hideid('withinx');\"";
		   $THIS_DISPLAY .= "      <span id=\"withinx\" style=\"display: none;font-size: 90%;\" class=\"red uline hand\" ".$onclick.">[x]</span>\n";
		
		   $THIS_DISPLAY .= "     </td>\n";
		   $THIS_DISPLAY .= "    </tr>\n";
		   $THIS_DISPLAY .= "   </table>\n";
		
		   $THIS_DISPLAY .= "  </td>\n";
		
			# [Find Record]
			$THIS_DISPLAY .= "  <td align=\"left\" valign=\"top\" style=\"padding-right: 40px;\">\n";
		   $THIS_DISPLAY .= "   <input type=\"submit\" value=\"".lang("Find Record")."\" ".$btn_edit.">\n";
			$THIS_DISPLAY .= "  </td>\n";
			$THIS_DISPLAY .= "  </form>\n";
		
			# [Add New Record]
			$THIS_DISPLAY .= "  <form method=\"post\" action=\"".$simple_name."?mysqlmode=enter_edit\">\n";
			$THIS_DISPLAY .= "  <td align=\"center\" valign=\"middle\">\n";
		   $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"mt\" value=\"$mt\">\n";
		   $THIS_DISPLAY .= "   <input type=\"hidden\" name=\"ACTION\" value=\"ADD_NEW\">\n";
		   $THIS_DISPLAY .= "   <input type=\"submit\" value=\"".lang("Add New Record")."\" ".$btn_build.">\n";
		   $THIS_DISPLAY .= "  </td>\n";
		   $THIS_DISPLAY .= "  </form>\n";
		
			# Show/hide blob fields
			$THIS_DISPLAY .= "  <td valign=\"top\">\n";
			$THIS_DISPLAY .= "   <ul style=\"margin-bottom: 0;list-style-type: square;\">\n";
		
			if ( $_SESSION['dtm_viewmode'][$_REQUEST['mt']] == "hideblob" ) {
			   $THIS_DISPLAY .= "    <li><a href=\"".$base_href."&dtm_viewmode=default\">".str_replace(" ", "&nbsp;", lang("Show blob data"))."</a></li>\n";
			} else {
			   $THIS_DISPLAY .= "    <li><a href=\"".$base_href."&dtm_viewmode=hideblob\">".str_replace(" ", "&nbsp;", lang("Hide blob data"))."</a></li>\n";
			}
		
		
			# Show/hide collapse options
			if ( $_SESSION['dtm_collapse_option'][$_REQUEST['mt']] == "on" ) {
			   $THIS_DISPLAY .= "    <li><b><a href=\"".$base_href."&dtm_collapse=off\">".str_replace(" ", "&nbsp;", lang("Hide collapse options"))."</a></b></li>\n";
		
			   # Show all fields
			   if ( count($_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) > 0 ) {
			      $THIS_DISPLAY .= "    <li><a href=\"".$base_href."&amp;todo=expand_all\">".str_replace(" ", "&nbsp;", lang("Show all fields"))."</a></li>\n";
			   }
		
			   # Collapse all fields
			   $THIS_DISPLAY .= "    <li><a href=\"".$base_href."&amp;todo=collapse_all\">".str_replace(" ", "&nbsp;", lang("Collapse all fields"))."</a></li>\n";
		
			   # Hide multiple fields
			   $THIS_DISPLAY .= "    <li><a href=\"#\" onclick=\"showid('popup-hide_fields');\">".str_replace(" ", "&nbsp;", lang("Hide multiple fields"))."</a></li>\n";
		
			} else {
			   # Show collapse options
			   $THIS_DISPLAY .= "    <li><b><a href=\"".$simple_name."?mysqlmode=enter_edit&mt=".$mt."&TBL_SEARCH_FOR=".$_SESSION[$_REQUEST['mt']]['last_search_term']."&dtm_collapse=on\">".str_replace(" ", "&nbsp;", lang("Show collapse options"))."</a></b></li>\n";
			}
		
		   $THIS_DISPLAY .= "   </ul>\n";
			$THIS_DISPLAY .= "  </td>\n";
		
			# Run custom query
			$THIS_DISPLAY .= "  <td width=\"100%\" valign=\"top\">\n";
			$THIS_DISPLAY .= "   <ul style=\"margin-bottom: 0;list-style-type: square;\">\n";
			$THIS_DISPLAY .= "    <li><a href=\"#\" onclick=\"showid('popup-custom_qry');\" class=\"del\">".str_replace(" ", "&nbsp;", lang("Run custom query"))."</a></li>\n";
			$THIS_DISPLAY .= "   <li><a href=\"#\" onclick=\"showid('popup-timestamps');\">".str_replace(" ", "&nbsp;", lang("Format timestamps"))."</a></li>\n";
			$THIS_DISPLAY .= "   <li><a href=\"#\" onclick=\"showid('popconfig-serialized');\">".str_replace(" ", "&nbsp;", lang("Format serialized data"))."</a></li>\n";
			$THIS_DISPLAY .= "   <li><a href=\"#\" onclick=\"showid('popconfig-decode');\">".str_replace(" ", "&nbsp;", lang("Decode data"))."</a></li>\n";
			$THIS_DISPLAY .= "  </td>\n";
		
			$THIS_DISPLAY .= " </tr>\n";
			$THIS_DISPLAY .= "</table>\n\n";
		
			$THIS_DISPLAY .= "<table border=1 cellpadding=3 cellspacing=0 class=\"text\" width=\"98%\">\n\n";
			$THIS_DISPLAY .= " <tr>\n\n";
			$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"center\" valign=top><font color=white><b>".lang("Delete")."</b></font></td>\n";
			$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"center\" valign=top><font color=white><b>".lang("Edit")."</b></font></td>\n";
		
		
		   /*---------------------------------------------------------------------------------------------------------*
		     ___       _                      _____  _  _    _
		    / __| ___ | | _  _  _ __   _ _   |_   _|(_)| |_ | | ___
		   | (__ / _ \| || || || '  \ | ' \    | |  | ||  _|| |/ -_)
		    \___|\___/|_| \_,_||_|_|_||_||_|   |_|  |_| \__||_|\___|
			# Field Names (column headings)
			/*---------------------------------------------------------------------------------------------------------*/
			for ($x=0;$x<=$numberFields;$x++) {
				$fieldname[$x] = mysql_field_name($result, $x);
		
				# Do not show if hidden by user
				if ( !array_key_exists($fieldname[$x], $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) ) {
			   	$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"left\" valign=\"top\">\n";
		
			   	# For [<- ->] and [v]
			   	$tinylink = "style=\"font-size: 90%;font-weight: normal;text-decoration:none;\"";
		
			   	# [v]
			   	$sorticon = "";
			   	$sortlink = "";
			   	if ( $_SESSION[$_REQUEST['mt']]['orderby'] == $fieldname[$x] ) {
			   	   if ( $_SESSION[$_REQUEST['mt']]['orderhow'] == "desc" ) {
			   	      $sortlink = "asc";
			   	      $sorticon = "<a href=\"".$base_href."&amp;orderby=".$fieldname[$x]."&orderhow=asc\" ".$linkstyle." class=\"white noline\">[&uarr;]</a>\n";
			   	   } else {
			   	      $sortlink = "desc";
			   	      $sorticon = "<a href=\"".$base_href."&amp;orderby=".$fieldname[$x]."&orderhow=desc\" ".$linkstyle." class=\"white noline\">[&darr;]</a>\n";
			   	   }
			   	}
		
		   		# Collapsed field? (minimal display style if so)
		   		if ( array_key_exists($fieldname[$x], $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) ) {
		   		   $titlestring = str_replace("_", "<br/>_", $fieldname[$x]);
		   		   $titlestring = "<span style=\"font-size: 90%;font-weight: normal;\">".$titlestring."</span>";
		
		   		} else {
		   		   $titlestring = $fieldname[$x];
		   		}
		
		   		$titlestring = "<a href=\"".$base_href."&amp;orderby=".$fieldname[$x]."&orderhow=".$sortlink."\" ".$linkstyle." class=\"white noline\" title=\"".$sortlink."\">".$titlestring."</a>";
		
		         $THIS_DISPLAY .= $titlestring;
		   		$THIS_DISPLAY .= $sorticon;
		
		   		# [<- ->] ?
		   		if ( $_SESSION['dtm_collapse_option'][$_REQUEST['mt']] == "on" ) {
		
		   		   # Collapse link or Expand link?
		   		   if ( array_key_exists($fieldname[$x], $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) ) {
		   		      $THIS_DISPLAY .= "    <br/><a href=\"".$base_href."&amp;expand_field=".$fieldname[$x]."\" ".$tinylink." class=\"sav\">[&larr;|&rarr;]</a>\n";
		   		   } else {
		   		      $THIS_DISPLAY .= "    <br/><a href=\"".$base_href."&amp;collapse_field=".$fieldname[$x]."\" ".$tinylink." class=\"sav\">[&rarr;|&larr;]</a>\n";
		   		   }
		   		}
		   		$THIS_DISPLAY .= "  </td>\n";
		
		   	} // End if !array_key_exists -- field is not in hidden list
			}
		
			$THIS_DISPLAY .= "\n</TR>\n\n<TR>\n\n";
			$THIS_DISPLAY .= "<TD CLASS=\"col_sub\" ALIGN=CENTER VALIGN=TOP>[&nbsp;".lang("OPTION")."&nbsp;]</TD>\n";
			$THIS_DISPLAY .= "<TD CLASS=\"col_sub\" ALIGN=CENTER VALIGN=TOP>[&nbsp;".lang("OPTION")."&nbsp;]</TD>\n";
		
		
		   /*---------------------------------------------------------------------------------------------------------*
		    ___  _       _     _   _____
		   | __|(_) ___ | | __| | |_   _|_  _  _ __  ___
		   | _| | |/ -_)| |/ _` |   | | | || || '_ \/ -_)
		   |_|  |_|\___||_|\__,_|   |_|  \_, || .__/\___|
		                                 |__/ |_|
		   /*---------------------------------------------------------------------------------------------------------*/
			for ($x=0;$x<=$numberFields;$x++) {
				$fieldtype[$x] = mysql_field_type($result, $x);
				$fieldtype[$x] = strtoupper($fieldtype[$x]);
		
				# Do not show if hidden by user
				if ( !array_key_exists($fieldname[$x], $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) ) {
		
		   		# Collapsed field? (minimal display style if so)
		   		if ( array_key_exists($fieldname[$x], $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) ) {
		   		   $THIS_DISPLAY .= "  <td class=\"col_sub\" align=\"center\" valign=\"top\" style=\"font-size: 60%;color: #2e2e2e;\">[".$fieldtype[$x]."]</td>\n";
		   		} else {
		   		   $THIS_DISPLAY .= "  <td class=\"col_sub\" align=\"center\" valign=\"top\" style=\"font-size: 90%;color: #2e2e2e;\">[".$fieldtype[$x]."]</td>\n";
		   		}
		
		   	} // End if field not in hidden list
		
			} // End for loop through fields
		
			$THIS_DISPLAY .= "\n</TR>\n";
		
		
		   /*---------------------------------------------------------------------------------------------------------*
		    ___  _       _     _      _        _
		   | __|(_) ___ | | __| |  __| | __ _ | |_  __ _
		   | _| | |/ -_)| |/ _` | / _` |/ _` ||  _|/ _` |
		   |_|  |_|\___||_|\__,_| \__,_|\__,_| \__|\__,_|
		   /*---------------------------------------------------------------------------------------------------------*/
			$i = 0;
			while ($row = mysql_fetch_array ($result)) {
		
				if ($BGCOLOR == "WHITE") { $BGCOLOR="#EFEFEF"; } else { $BGCOLOR="WHITE"; }
		
				$edit_link = "[&nbsp;<a href=\"".$simple_name."?mysqlmode=enter_edit&ACTION=EDIT&ID=".$row[$KEYFIELD]."&mt=$mt&=SID\">".lang("Edit")."</a>&nbsp;]";
				$del_link = "[&nbsp;<a href=\"#\" onclick=\"confirm_delete('$mt','$row[$KEYFIELD]');\" class=\"del\">".lang("Delete")."</a>&nbsp;]";
				$i++;
		
				$THIS_DISPLAY .= "\n <tr>\n";
		      $THIS_DISPLAY .= "  <td bgcolor=\"".$BGCOLOR."\" align=\"center\" valign=top>".$del_link."</td>\n";
		      $THIS_DISPLAY .= "  <td bgcolor=\"".$BGCOLOR."\" align=\"center\" valign=top>".$edit_link."</td>\n";
		
				# Loop through fields
				for ($x=0;$x<=$numberFields;$x++) {
				   # Flags checked before trying to apply special formatting to data (ie trying unserialize truncated data)
				   $collapsed = false;
		         $serialized = false;
		
		   		# Do not show if hidden by user
		   		if ( !array_key_exists($fieldname[$x], $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) ) {
		   			$tmp = $row[$x];
		
		   			# Hide blob fields? (4.9 r23)
		   			if ( $_SESSION['dtm_viewmode'][$_REQUEST['mt']] == "hideblob" && $tmp != "" && $tmp != "NULL" ) {
		   			   if (strtoupper($fieldtype[$x]) == "BLOB") { $tmp = "[BLOB]"; $collapsed = true; }
		   			} else {
		   			   if (strtoupper($fieldtype[$x]) == "BLOB") { $tmp = $tmp; }
		   			}
		
		   			if ($tmp == "" || $tmp == "NULL") { $tmp = "&nbsp;"; }
		
		            # Timestamp field?
		            if ( array_key_exists($fieldname[$x], $_SESSION['timestamp_fields'][$_REQUEST['mt']]) ) {
		               $tmp = "<span class=\"formatted_timestamp\">".date($_SESSION['timestamp_date_format'][$_REQUEST['mt']], $tmp)."</span>";
		            }
		
		            # Collapsed field? (hide field data if so)
		            if ( array_key_exists($fieldname[$x], $_SESSION['dtm_collapse_fields'][$_REQUEST['mt']]) ) {
		               if ( strlen($tmp) > 6 ) {
		                  $tmp = substr($tmp, 0, 6)."...";
		               }
		               $collapsed = true;
		            }
		
		            # Serialzied data field?
		            if ( array_key_exists($fieldname[$x], $_SESSION['serialized_fields'][$_REQUEST['mt']]) && !$collapsed ) {
		               $displaytmp = "<span class=\"formatted_serialized\">\n";
		               $displaytmp .= " ".testArray(unserialize($tmp))."\n";
		               $displaytmp .= "</span>";
		               $tmp = $displaytmp;
		               $serialized = true;
		            }
		
		            # Encoded data field?
		            if ( array_key_exists($fieldname[$x], $_SESSION['decode_fields'][$_REQUEST['mt']]) && !$collapsed ) {
		
		               $displaytmp = "<span class=\"formatted_serialized\">\n";
		               if($tmp != "&nbsp;"){
			               $displaytmp .= base64_decode($tmp)."\n";
			             } else {
										$displaytmp .= "\n";
			             }
		  	           $displaytmp .= "</span>";
		               $tmp = $displaytmp;
		               $encoded = true;
		            }
		
		   			if ( $tmp != "&nbsp;" && !array_key_exists($fieldname[$x], $_SESSION['timestamp_fields'][$_REQUEST['mt']]) && !$serialized && !$encoded) { $tmp = htmlspecialchars($tmp); }	// Bugzilla #12
		
		   			$THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP BGCOLOR=$BGCOLOR>".$tmp."</TD>\n";
		   		}
				}
		
				$THIS_DISPLAY .= "\n</TR>\n";
		
			}
		
		
			# [hide]
			if ( $_SESSION['dtm_collapse_option'][$_REQUEST['mt']] == "on" ) {
		      $THIS_DISPLAY .= " <tr>\n";
		      $THIS_DISPLAY .= "  <td colspan=\"2\" align=\"right\" class=\"col_title\" style=\"font-weight: normal;\">Click to hide column:</td>\n";
		
		   	for ( $x = 0; $x <= $numberFields; $x++ ) {
		   		$fieldname[$x] = mysql_field_name($result, $x);
		
		   		# Do not show if hidden by user
		   		if ( !array_key_exists($fieldname[$x], $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) ) {
		   	   	$THIS_DISPLAY .= "  <td class=\"col_title\" align=\"center\" valign=\"top\" style=\"font-weight: normal;\">\n";
		            $THIS_DISPLAY .= "   [<a href=\"".$base_href."&hide_field=".$fieldname[$x]."\" class=\"white\">hide</a>]\n";
		      		$THIS_DISPLAY .= "  </td>\n";
		      	} // End if !array_key_exists -- field is not in hidden list
		   	}
		
		      $THIS_DISPLAY .= " </tr>\n";
		
		   	# hidden field1, hidden field2, etc
		   	# Show list of hidden fields with option to un-hide them?
		   	if ( count($_SESSION['dtm_hidden_fields'][$_REQUEST['mt']]) > 0 ) {
		         $THIS_DISPLAY .= " <tr>\n";
		         $THIS_DISPLAY .= "  <td colspan=\"2\" class=\"col_title\" align=\"right\" style=\"font-weight: normal;\">Restore hidden columns:</td>\n";
		         $THIS_DISPLAY .= "  <td colspan=\"".$numberFields."\" class=\"col_title\">\n";
		      	foreach ( $_SESSION['dtm_hidden_fields'][$_REQUEST['mt']] as $field=>$value ) {
		      	   $THIS_DISPLAY .= "   <a href=\"".$base_href."&show_field=".$field."\" class=\"white\">".$field."</a> <span style=\"font-weight: normal;\">|</span> ";
		      	}
		      	$THIS_DISPLAY .= "  </td>\n";
		      	$THIS_DISPLAY .= " </tr>\n";
		      }
		
		   } // End if collapse options = on
		
		
		
			$THIS_DISPLAY .= "\n</TABLE>\n\n";
		
			$THIS_DISPLAY .= "<BR>";
		
			# '<< Previous 10' and 'Next 10 >>' links for large tables
			#---------------------------------------------------------------------------
			if ($noShowFlag == 0) {
		
					$prev = $start_show - $num_to_show;
					if ($start_show > 0) {
						$THIS_DISPLAY .= "<a href=\"".$simple_name."?mysqlmode=enter_edit&mt=$mt&start_show=$prev\"><< ".lang("Previous")." $num_to_show</a>";
					}
		
					$next = $start_show + $num_to_show;
					if ($next < $total_recs) {
						$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						$THIS_DISPLAY .= "<a href=\"".$simple_name."?mysqlmode=enter_edit&mt=$mt&start_show=$next\">".lang("Next")." $num_to_show >></a>";
					}
		
					$THIS_DISPLAY .= "<BR>";
		
						// Build Inside Page Links
						// =======================================================
		
						$THIS_DISPLAY .= "<DIV ALIGN=CENTER STYLE='font-family: arial; font-size: 7pt; color: maroon; padding: 8px;'>";
						$s = 0;
						$s_display = $s+1;
						$f = $num_to_show;
						$THIS_DISPLAY .= "<a href=\"".$simple_name."?mysqlmode=enter_edit&mt=$mt&start_show=$s\" CLASS=sup>$s_display-$f</a>&nbsp;&nbsp;";
		
						$tmp = $total_recs/$num_to_show;
						$tmp = round($tmp);
		
						$br_count = 0;
		
						for ($z=1;$z<=$tmp;$z++) {
		
							if ($br_count == 16) {
								$THIS_DISPLAY .= "<BR>";
								$br_count = 0;
							}
		
							if ($f < $total_recs) {
								$s = $z*$num_to_show;
								$s_display = $s+1;
								$f = $s+$num_to_show;
								if ($f > $total_recs) { $f = $total_recs; }
								$THIS_DISPLAY .= "&nbsp;&nbsp;<a href=\"".$simple_name."?mysqlmode=enter_edit&mt=$mt&start_show=$s\" CLASS=sup>$s_display-$f</a>&nbsp;&nbsp;";
								$br_count++;
							} // End Safe Count
		
						}
		
						$THIS_DISPLAY .= "</DIV>";
		
			} // End No Show Flag
		
				// =======================================================
		
		} // End if NO ACTION submitted
		
		$THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= "</table>\n";
		
		echo $THIS_DISPLAY;
		

		
		####################################################################
		### Added feature to return display to previous display after editing or deleting a record - Cameron A
		//unset($_SESSION[$_REQUEST['mt']]['last_page_view']);
		if(count($_GET) > 0 && $_GET['ACTION'] == ''){
			foreach($_GET as $gvar=>$gval){
				$last_page_view[$gvar]=$gval;
			}
			$_SESSION[$_REQUEST['mt']]['last_page_view']=$last_page_view;
		} elseif(count($_POST) > 0 && $_POST['ACTION'] == ''){
			foreach($_POST as $gvar=>$gval){
				$last_page_view[$gvar]=$gval;
			}
			$_SESSION[$_REQUEST['mt']]['last_page_view']=$last_page_view;
		}
		######End previous record display
		
		echo "</body>\n";
###############END INCLUDE ENTER EDIT DATA .php		
     } elseif($_REQUEST['mysqlmode']=='downloaddata') {
//		include_once('download_data.php');
###############INCLUDING DOWNLOAD DATA .php
	error_reporting(E_PARSE && E_ERROR);
	//$db_name = "wiki_soho_com";
	//$db_un = "wikidot";
	//$db_pw = "wikic0m";
	//$db_server = 'localhost';
	$CUR_USER_ACCESS = 'WEBMASTER';
	//$_SESSION['db_name'] = $db_name;
	//$_SESSION['db_un'] = $db_un;
	//$_SESSION['db_pw'] = $db_pw;
	//$_SESSION['db_server'] = $db_server;
	//
	//
	////
	//	mysql_connect("$db_server", "$db_un","$db_pw");
	//	mysql_query("SET SESSION SQL_MODE = ''");
	//	$sel = mysql_select_db("$db_name");
	if(!function_exists('lang')){
		function lang($string) {
			return $string;
		}
	}
	
	?>
	<script language="javascript">
	function MM_openBrWindow(theURL,winName,features) { //v2.0
		window.open(theURL,winName,features);
	}
	
	function find_object(n, d) { //v3.0
	// H O O K: Updated for IE and Mozilla
		var p,i,x;
		if(!d) d=document;
		if((p=n.indexOf("?"))>0&&parent.frames.length) {
			d=parent.frames[n.substring(p+1)].document;
			n=n.substring(0,p);
		}
		if(!(x=d[n])&&d.all) x=d.all[n];
		for (i=0;!x&&i<d.forms.length;i++)
			x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++)
			x=find_object(n,d.layers[i].document);
		if(!x && d.getElementById)
			x=d.getElementById(n);
		return x;
	}
	
	
	function show_hide_layer() { //v3.0
		var i, p, v, obj, args = show_hide_layer.arguments;
		for (i=0; i<(args.length-2); i+=3) if ((obj=find_object(args[i]))!=null) { v=args[i+2];
		if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
		obj.visibility=v; }
	}
	
	function open_bar_window(theURL,winName,features) { //v2.0
		window.open(theURL,winName,features);
	}
	
	
	
	// ------------------------------------------------------------------
	// Kill any Javascript error notifications that may occur.
	// This is important in IE5 because the drag and drop functions
	// will kickback return codes for success or failure operations.
	// -- This is a shortcut in order not to deal with codes that are
	//    unimportant to getting the job done.
	// ------------------------------------------------------------------
	
	function killErrors() {
	   return true;
	}
	//window.onerror = killErrors;
	
	if( !CURPAGENAME ) {
	   var CURPAGENAME = find_object('CURPAGENAME', parent.frames.footer.document);
	}
	
	//---------------------------------------------------------------------------------------------------------
	//    ___   _       __      __ _           _
	//   |   \ (_)__ __ \ \    / /(_) _ _   __| | ___ __ __ __ ___
	//   | |) || |\ V /  \ \/\/ / | || ' \ / _` |/ _ \\ V  V /(_-<
	//   |___/ |_| \_/    \_/\_/  |_||_||_|\__,_|\___/ \_/\_/ /__/
	//
	//    DHTML Window script- Copyright Dynamic Drive (http://www.dynamicdrive.com)
	//    For full source code, documentation, and terms of usage,
	//    visit http://www.dynamicdrive.com/dynamicindex9/dhtmlwindow.htm
	//---------------------------------------------------------------------------------------------------------
	var dragapproved=false;
	var minrestore=0;
	var initialwidth,initialheight;
	var ie5=document.all&&document.getElementById;
	var ns6=document.getElementById&&!document.all;
	
	function iecompattest() {
	   return (!window.opera && document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body;
	}
	
	function drag_drop(e) {
	   if ( ie5&&dragapproved&&event.button==1 ) {
	      document.getElementById("dwindow").style.left=tempx+event.clientX-offsetx+"px";
	      document.getElementById("dwindow").style.top=tempy+event.clientY-offsety+"px";
	   } else if ( ns6&&dragapproved ) {
	      document.getElementById("dwindow").style.left=tempx+e.clientX-offsetx+"px";
	      document.getElementById("dwindow").style.top=tempy+e.clientY-offsety+"px";
	   }
	}
	
	function initializedrag(e){
	   offsetx=ie5? event.clientX : e.clientX;
	   offsety=ie5? event.clientY : e.clientY;
	   document.getElementById("dwindowcontent").style.display="none"; //extra
	   tempx=parseInt(document.getElementById("dwindow").style.left);
	   tempy=parseInt(document.getElementById("dwindow").style.top);
	
	   dragapproved=false;
	   document.getElementById("dwindow").onmousemove=drag_drop;
	}
	
	function loadwindow(url,width,height,curobj) {
	   if ( !ie5 && !ns6 ) {
	      window.open(url,"","width=width,height=height,scrollbars=1");
	   } else {
	      document.getElementById("cframe").src=url;
	      document.getElementById("dwindow").style.display='';
	      document.getElementById("dwindow").style.width=initialwidth=width+"px";
	      document.getElementById("dwindow").style.height=initialheight=height+"px";
	      document.getElementById("dwindow").style.right=getposOffset(curobj, "right")+"px";
	      document.getElementById("dwindow").style.top=getposOffset(curobj, "top")+"px";
	   }
	}
	
	function loadwindowUP(url,width,height,curobj) {
	   if ( !ie5 && !ns6 ) {
	      window.open(url,"","width=width,height=height,scrollbars=1");
	   } else {
	      document.getElementById("cframe").src=url;
	      document.getElementById("dwindow").style.display='';
	      document.getElementById("dwindow").style.width=initialwidth=width+"px";
	      document.getElementById("dwindow").style.height=initialheight=height+"px";
	      document.getElementById("dwindow").style.right=getposOffset(curobj, "right")+"px";
	      document.getElementById("dwindow").style.middle=getposOffset(curobj, "middle")+"px";
	   }
	}
	
	function maximize() {
	   if ( minrestore == 0 ) {
	      minrestore=1; //maximize window
	      document.getElementById("maxname").setAttribute("src","../includes/display_elements/graphics/icon-restore_window.gif");
	      document.getElementById("dwindow").style.width=ns6? window.innerWidth-20+"px" : iecompattest().clientWidth+"px";
	      document.getElementById("dwindow").style.height=ns6? window.innerHeight-20+"px" : iecompattest().clientHeight+"px";
	   } else {
	      minrestore=0; //restore window
	      document.getElementById("maxname").setAttribute("src","../includes/display_elements/graphics/icon-maximize.gif");
	      document.getElementById("dwindow").style.width=initialwidth;
	      document.getElementById("dwindow").style.height=initialheight;
	   }
	   document.getElementById("dwindow").style.left=ns6? window.pageXOffset+"px" : iecompattest().scrollLeft+"px";
	   document.getElementById("dwindow").style.top=ns6? window.pageYOffset+"px" : iecompattest().scrollTop+"px";
	}
	
	function closeit() {
	   document.getElementById("dwindow").style.display="none";
	}
	
	function stopdrag() {
	   dragapproved=false;
	   document.getElementById("dwindow").onmousemove=null;
	   document.getElementById("dwindowcontent").style.display=""; //extra
	}
	
	
	//---------------------------------------------------------------------------------------------------------
	//    _  _       _         ___
	//   | || | ___ | | _ __  | _ \ ___  _ __  _  _  _ __  ___
	//   | __ |/ -_)| || '_ \ |  _// _ \| '_ \| || || '_ \(_-<
	//   |_||_|\___||_|| .__/ |_|  \___/| .__/ \_,_|| .__//__/
	//                 |_|              |_|         |_|
	//    Overlapping Content link- © Dynamic Drive (www.dynamicdrive.com)
	//    This notice must stay intact for legal use.
	//    Visit http://www.dynamicdrive.com/ for full source code
	//---------------------------------------------------------------------------------------------------------
	function getposOffset(overlay, offsettype){
	   var totaloffset=(offsettype=="left")? overlay.offsetLeft : overlay.offsetTop;
	   var parentEl=overlay.offsetParent;
	   while (parentEl!=null) {
	      totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
	      parentEl=parentEl.offsetParent;
	   }
	   return totaloffset;
	}
	
	function overlayclose(subobj){
	   document.getElementById(subobj).style.display="none"
	}
	
	
	
	//---------------------------------------------------------------------------------------------------------
	//      _      _   _   __  __
	//     /_\  _ | | /_\  \ \/ /
	//    / _ \| || |/ _ \  >  <
	//   /_/ \_\\__//_/ \_\/_/\_\
	//
	//---------------------------------------------------------------------------------------------------------
	// The following script (as commonly seen in other AJAX javascripts) is used to detect which browser the client is using.
	// If the browser is Internet Explorer we make the object with ActiveX.
	// (note that ActiveX must be enabled for it to work in IE)
	//function makeObject() {
	//   var x;
	//   var browser = navigator.appName;
	//
	//   if ( browser == "Microsoft Internet Explorer" ) {
	//      x = new ActiveXObject("Microsoft.XMLHTTP");
	//   } else {
	//      x = new XMLHttpRequest();
	//   }
	//
	//   return x;
	//}
	
	function makeObject() {
	   var httpRequest;
	
	   if (window.XMLHttpRequest) { // Mozilla, Safari, ...
	      httpRequest = new XMLHttpRequest();
	      if (httpRequest.overrideMimeType) {
	          httpRequest.overrideMimeType('text/xml');
	          // Or else you get 'object required' error in IE and it doesn't work
	      }
	   } else if (window.ActiveXObject) { // IE
	      try {
	//          httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
	          httpRequest = new ActiveXObject("MicrosoftXMLDOM");
	      } catch (e) {
	          try {
	              httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
	          } catch (e) {}
	      }
	   }
	
	   return httpRequest;
	}
	
	// The javascript variable 'request' now holds our request object.
	// Without this, there's no need to continue reading because it won't work ;)
	var request = makeObject();
	
	function ajaxDo(qryString, boxid) {
	   //alert(qryString+', '+boxid);
	
	   rezBox = boxid; // Make global so parseInfo can get it
	
	   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
	   request.open('get', qryString);
	
	   // This tells the script to call parseInfo() when the ready state is changed
	   request.onreadystatechange = parseInfo;
	
	   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
	   request.send('');
	
	}
	
	function parseInfo() {
	   // Loading
	   if ( request.readyState == 1 ) {
	      document.getElementById(rezBox).innerHTML = 'Loading...';
	   }
	
	   // Finished
	   if ( request.readyState == 4 ) {
	      var answer = request.responseText;
	      document.getElementById(rezBox).innerHTML = answer;
	   }
	}
	
	//---------------------------------------------------------------------------------------------------
	//     _____                                 _   _    _
	//    / ____|                               | | | |  | |
	//   | |  __   ___  _ __    ___  _ __  __ _ | | | |  | | ___   ___
	//   | | |_ | / _ \| '_ \  / _ \| '__|/ _` || | | |  | |/ __| / _ \
	//   | |__| ||  __/| | | ||  __/| |  | (_| || | | |__| |\__ \|  __/
	//    \_____| \___||_| |_| \___||_|   \__,_||_|  \____/ |___/ \___|
	//
	//---------------------------------------------------------------------------------------------------
	
	// Flips single element on/off based on current state
	// Accepts: ID of target element, whether to use visibility or display style (optional, 'display' by default)
	function toggleid(targetid, fliphow) {
	
	   if ( fliphow == "visibility" ) {
	      var isnow = document.getElementById(targetid).style.visibility;
	      if ( isnow == 'visible' ) {
	         document.getElementById(targetid).style.visibility='hidden';
	         return true;
	      } else {
	         document.getElementById(targetid).style.visibility='visible';
	         return true;
	      }
	
	
	   } else {
	      var isnow = document.getElementById(targetid).style.display;
	      if ( isnow == 'block' ) {
	         document.getElementById(targetid).style.display='none';
	         return true;
	      } else {
	         document.getElementById(targetid).style.display='block';
	         return true;
	      }
	   }
	} // End toggleid() function
	
	// For places that call for a bit more exacting control vs. toggleid
	function hideid(thingid) {
	   document.getElementById(thingid).style.display = 'none';
	}
	function showit(thingid) {
	   document.getElementById(thingid).style.display = 'block';
	}
	
	function showid(thingid) {
	   document.getElementById(thingid).style.display = 'block';
	}
	
	// Especially handy for flipping bg color of table rows onmouseover, turning one tab on and others off onclick, etc.
	function setClass(thingid, new_classname) {
	   document.getElementById(thingid).className = new_classname;
	}
	
	
	// Checks/unchecks a form checkbox field
	// Optional: Pass true/false as second checkuncheck arg
	function toggle_checkbox(targetid, checkuncheck) {
	
	   if ( checkuncheck == "check" ) {
	      // Set: CHECK
	      document.getElementById(targetid).checked = true;
	      return true;
	
	   } else if ( checkuncheck == "uncheck" ) {
	      // Set: UNCHECK
	      document.getElementById(targetid).checked = false;
	      return true;
	
	   } else {
	      // TOGGLE: Set to opposite of whatever it is now
	      var isnow = document.getElementById(targetid).checked;
	      if ( isnow == true ) {
	         document.getElementById(targetid).checked = false;
	         return true;
	      } else {
	         document.getElementById(targetid).checked = true;
	         return true;
	      }
	   }
	}
	
	// Use for "other (specify)" options in drop-downs and such
	function ifShow(fieldid, chkvalue, boxid) {
	   if ( $(fieldid).value == chkvalue ) {
	      showid(boxid);
	   } else {
	      hideid(boxid);
	   }
	}
	
	// Used originally for "if box is checked fadein else fadeout" in add/edit admin user > plugin features
	function ifChecked_setClass(fieldid, boxid, onclass, offclass) {
	   var isnow = $(fieldid).checked;
	
	   if ( isnow == true ) {
	      setClass(boxid, onclass);
	   } else {
	      setClass(boxid, offclass);
	   }
	}
	
	// Loops through radio button group and returns value of checked radio
	// Use: When you want to pass the radio value via js when changed but can't
	//      use onchange b/c you're allowing them to click the text next to the radio as well as the radio itself
	function radiovalue(formname, radiogroup) {
	   var max = eval('document.'+formname+'.'+radiogroup+'.length'); // Faster defined up here...doesn't have to recaculate every loop iteration
	   for ( i=0; i < max; i++ ) {
	      if ( eval('document.'+formname+'.'+radiogroup+'[i].checked') == true ) {
	         return eval('document.'+formname+'.'+radiogroup+'[i].value');
	      }
	   }
	}
	
	//---------------------------------------------
	// Shortcut for document.getElementById :)
	//---------------------------------------------
	function $() {
	  var elements = new Array();
	
	  for (var i = 0; i < arguments.length; i++) {
	    var element = arguments[i];
	    if (typeof element == 'string')
	      element = document.getElementById(element);
	
	    if (arguments.length == 1)
	      return element;
	
	    elements.push(element);
	  }
	
	  return elements;
	}
	
	
	// Workaround for IE's infinite z-index issue
	// Hide all dropdown boxes
	// OPTIONAL: Pass an ids to exclude
	function hide_dropdowns(exclude) {
	   dropdowns = document.getElementsByTagName("select");
	   if ( exclude != "" ) {
	      // Test for excluded id
	      for ( i = 0; i < dropdowns.length; i++ ) {
	         if ( dropdowns[i].id != exclude ) {
	            dropdowns[i].style.display = 'none';
	         }
	      }
	   } else {
	      // Hide all dropdowns, don't check for exception
	      for ( i = 0; i < dropdowns.length; i++ ) {
	         dropdowns[i].style.display = 'none';
	      }
	   }
	}
	
	// Show all dropdown boxes
	function show_dropdowns() {
	   dropdowns = document.getElementsByTagName("select");
	   for ( i = 0; i < dropdowns.length; i++ ) {
	      dropdowns[i].style.display = 'inline';
	   }
	}
	
	
	/*
	    Written by Jonathan Snook, http://www.snook.ca/jonathan
	    Add-ons by Robert Nyman, http://www.robertnyman.com
	*/
	function getElementsByClassName(oElm, strTagName, oClassNames){
	    var arrElements = (strTagName == "*" && oElm.all)? oElm.all : oElm.getElementsByTagName(strTagName);
	    var arrReturnElements = new Array();
	    var arrRegExpClassNames = new Array();
	    if(typeof oClassNames == "object"){
	        for(var i=0; i<oClassNames.length; i++){
	            arrRegExpClassNames.push(new RegExp("(^|\\s)" + oClassNames[i].replace(/\-/g, "\\-") + "(\\s|$)"));
	        }
	    }
	    else{
	        arrRegExpClassNames.push(new RegExp("(^|\\s)" + oClassNames.replace(/\-/g, "\\-") + "(\\s|$)"));
	    }
	    var oElement;
	    var bMatchesAll;
	    for(var j=0; j<arrElements.length; j++){
	        oElement = arrElements[j];
	        bMatchesAll = true;
	        for(var k=0; k<arrRegExpClassNames.length; k++){
	            if(!arrRegExpClassNames[k].test(oElement.className)){
	                bMatchesAll = false;
	                break;
	            }
	        }
	        if(bMatchesAll){
	            arrReturnElements.push(oElement);
	        }
	    }
	    return (arrReturnElements)
	}
	
	// Open a new window with standard features
	// Defaults to maximized height
	// popup_window(url to open [window title, [,width [,height]]]
	function popup_window(theUrl, title, width, height, toolbars) {
	
	   if ( width == "" ) { width = screen.width; }
	   if ( height == "" ) { height = screen.height; }
	   if ( toolbars == "" ) { toolbars = 'yes'; }
	
	   if ( toolbars == 'yes' ) {
	      // Yes, show toolbars in new window
	      toolbars_str = 'location=yes, toolbar=1, status=1, menubar=1,';
	   } else {
	      toolbars_str = 'location=no, toolbar=0, status=0, menubar=0,';
	   }
	
	   if ( document.all ) {
	      window.open(theUrl);
	   } else {
	      window.open(theUrl, title, 'scrollbars=yes, resizable=yes,'+toolbars_str+' width='+width+',height='+height);
	   }
	}
	</script>
	
	<style>
	
	div.help_popup {
	   width: 500px;
	   vertical-align: top;
	   position: absolute;
	   top: 37%;
	   left: 27%;
	   text-align: left;
	   border: 1px solid #888c8e;
	   background-color: #efefef;
	   z-index: 5;
	   font-family: Trebuchet MS, arial, helvetica, sans-serif;
	   font-size: 11px;
	}
	div.help_popup h1 {
	   font-size: 17px;
	   font-weight: bold;
	   color: #000;
	   margin: 4px 0 2px 0;
	}
	div.help_popup h2 {
	   font-size: 14px;
	   font-weight: bold;
	   color: #2e2e2e;
	   margin-bottom: 0;
	}
	div.help_popup ul {
	   margin-top: 0;
	}
	
	/* End new stuff */
	
	
	BODY {
	    font-family: verdana,arial,helvetica;
	    font-size: 10px;
	    color: #000000;
	    scrollbar-3dlight-color:#99CCFF;
	    scrollbar-arrow-color:darkblue;
	    scrollbar-base-color:#E6E6E6;
	    scrollbar-darkshadow-color:#99CCFF;
	    scrollbar-face-color:#99CCFF;
	    scrollbar-highlight-color: ;
	    scrollbar-shadow-color: <?php echo $bgcolor; ?>;
	}
	
	.text {
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
	}
	
	table {
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
	}
	
	h1 {
	   font-family: Trebuchet MS, Arial;
	   font-size: 18px;
	   font-weight: bold;
	}
	
	/* General-use text color styles */
	.orange { color: #D75B00; }
	.red { color: #D70000; }
	.green { color: #339959; }
	.blue { color: #336699; }
	.gray { color: #727272; }
	.dgray { color: #2E2E2E; }
	
	.mono { font-family: Courier New, courier, mono; }
	
	.bg_white { background: #FFFFFF; }
	.bg_blue { background: #F8F9FD; }
	.bg_gray { background: #EFEFEF; }
	
	.bg_dgreen { background: #A5E6B3; }
	.bg_dblue { background: #A5C6E6; }
	.bg_dred { background: #E6A5A5;; }
	.bg_dgray { background: #B9BEC1; }
	
	
	.bold { font-weight: bold; }
	.nobold { font-weight: normal; }
	
	/*####################################################################################
	--------------------------------------------------------------------------------------
	>> Primary feature modules styles
	--------------------------------------------------------------------------------------
	####################################################################################*/
	/* For feature modules with tabbed sections */
	table.tab_bar {
	   font-family: verdana, arial, helvetica, sans-serif;
	   font-size: 11px;
	   font-weight: bold;
	   border: 1px solid #000;
	   border-style: solid none solid solid;
	}
	
	table.tab_bar td {
	   padding: 2px 0px 1px 0px;
	   border-right: 1px solid #000;
	}
	
	.tab_off, .tab_on {
	   color: #2E2E2E;
	   cursor: pointer;
	   height: 20px; padding: 2px 20px 2px 20px;
	}
	
	.tab_off {
	   background-image: url(includes/display_elements/graphics/popdiv_title-bg.gif);
	   cursor: hand;
	}
	
	.tab_on {
	   color: #FFF;
	   background-image: url(includes/display_elements/graphics/btn-nav_main-on.jpg);
	}
	
	
	
	
	/*####################################################################################
	--------------------------------------------------------------------------------------
	>> Tables and table cells
	--------------------------------------------------------------------------------------
	####################################################################################*/
	
	/* Primary module parent table: white bg, dark blue title flush at top */
	table.feature_module {
	   border: 0px;
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		background-color: #fff;
	}
	
	/* Dark blue title (flush at top) with white text for module parent */
	.fmod_title {
	   padding: 3px 0px 3px 5px;
	   font-family: tahoma, arial, helvetica, sans-serif;
	   color: #ffffff;
	   font-size: 12px;
	   font-weight: bold;
	   letter-spacing: 2px;
	   background: #306FAE;
	}
	
	/* Primary feature group parent table: white bg, dark blue border */
	.feature_group {
	   border: 2px solid #336699;
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		/*background-color: #f8f9fd;*/
		background-color: #fff;
	}
	
	/* Dark blue header with white text for group (table) titles */
	.fgroup_title {
	   padding: 3px 0px 3px 5px;
	   font-family: verdana, arial, helvetica, sans-serif;
	   color: #ffffff;
	   font-size: 12px;
	   font-weight: bold;
	   letter-spacing: .06em;
	   background: #306FAE;
	   text-align: left;
	   background-image: url(includes/display_elements/graphics/fgroup_title.jpg);
	}
	
	/* Text driectly under module title (smaller and not bold) */
	.fgroup_subtitle {
	   font-size: 12px;
	   font-weight: normal;
	   letter-spacing: normal;
	}
	
	/* Field groups within module menu (i.e. 'Logo Text, Slogan, Logo Image' in Template Manager') */
	.feature_sub {
	   font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		border: 1px solid #2E2E2E;
		background: #f8f9fd;
	}
	
	.fsub_title {
	   font-family: verdana, arial, helvetica, sans-serif;
	   color: #2E2E2E;
	   font-size: 11px;
	   font-weight: bold;
	   padding: 5px;
	   border-bottom: 1px solid #B5B5B5;
	   background: #A5C6E6;
	   background-image: url(includes/display_elements/graphics/fsub_title.jpg);
	   background-position: top left;
	   background-repeat: repeat-x;
	}
	
	/* Culumn header for feature_sub tables */
	.fsub_col {
	   font-family: verdana, arial, helvetica, sans-serif;
	   font-size: 10px;
	   font-weight: bold;
	   padding: 2px;
	   border: 1px solid #B5B5B5;
	   border-style: none none solid solid;
	   color: #000000;
	   background: #E7EFF5;
	}
	
	/* Alternate row bg color */
	td.fsub_alt {
		background: #F1F3FA;
	}
	
	/* Bordered table cell */
	td.fsub_border {
	   border: 1px solid #B5B5B5;
	   border-style: none none solid solid;
	}
	
	/* Bordered table cell - alternate row bg color */
	td.fsub_border_alt {
	   border: 1px solid #B5B5B5;
	   border-style: none none solid solid;
		background: #F1F3FA;
	}
	
	/* Primarily for the "Payment Method" table on 'View Invoice' pop-up.
	   Also used for [?] help pop-ups */
	table.feature_grn {
	   border: 1px solid #339959;
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		background: #DFF6EA;
	}
	
	/* Experimental yellow help popup style */
	table.feature_yellow {
	   border: 1px solid #2E2E2E;
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		background: #fdfcf8;
	}
	.fyellow_title {
	   font-family: verdana, arial, helvetica, sans-serif;
	   color: #000000;
	   font-size: 11px;
	   font-weight: bold;
	   padding: 5px;
	   border-bottom: 1px solid #2e2e2e;
	   background: #e7de52;
	}
	
	/* Experimental gray box style (ie for help popups) */
	table.feature_gray {
	   border: 1px solid #2E2E2E;
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		background: #f7f7f7;
	}
	.fgray_title {
	   font-family: verdana, arial, helvetica, sans-serif;
	   color: #000000;
	   font-size: 11px;
	   font-weight: bold;
	   padding: 5px;
	   border-bottom: 1px solid #2E2E2E;
	   background: #D1D5D8;
	}
	
	.fgrn_title {
	   font-family: verdana, arial, helvetica, sans-serif;
	   color: #000000;
	   font-size: 11px;
	   font-weight: bold;
	   padding: 5px;
	   border-bottom: 1px solid #339959;
	   background: #A5E6B3;
	}
	
	/* Used to house sensitive delete functions (i.e. table drops). */
	.feature_red {
	   border: 1px solid #993333;
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		background: #F6DFDF;
	}
	
	.fred_title {
	   padding: 3px 0px 3px 10px;
	   font-family: tahoma, arial, helvetica, sans-serif;
	   font-size: 12px;
	   font-weight: bold;
	   color: #000000;
	   background: #E6A5A5;
	}
	
	/* Column titles - Typically used for field names when viewing db tables (ie 'PRIKEY') */
	.col_title {
	   background: #306FAE;
	   padding: 2px;
	   font-family: tahoma, arial, helvetica, sans-serif;
	   font-size: 11px;
	   font-weight: bold;
	   color: #ffffff;
	}
	
	/* Column subtitles - Typically used for other field data displayed as row underneath each field name when viewing db tables (i.e. 'VARCHAR') */
	.col_sub {
	   background: #D9E3EF;
	   padding: 3px;
	   font-family: verdana, arial, helvetica, sans-serif;
	   font-size: 11px;
	   color: #000000;
	}
	
	
	/* Default form style for Form Builder forms (esp. in preview area) */
	.form_default {
	   font-family: arial, helvetica, sans-serif;
		font-size: 11px;
	}
	
	.form_title {
	   font-size: 12px;
	   font-weight: bold;
	   padding: 5px;
	
	}
	
	.fprev_note {
	   font-size: 10px;
	   color: #000000;
	   border-bottom: 1px solid #000000;
	   background: #EFEFEF;
	}
	
	
	/*####################################################################################
	--------------------------------------------------------------------------------------
	>> Special Formatting
	--------------------------------------------------------------------------------------
	####################################################################################*/
	/* Grayed-out style for info that is meant to be example, temporary, disabled, etc. */
	.fademe { color: #B5B5B5; }
	#fademe { color: #B5B5B5; background: transparent;}
	
	/* Used in conjuction with js to give effect of appearing */
	.hideme { display: none; }
	#hideme { display: none; }
	
	/* Error text - Highlights labels and/or text boxes of missing required fields, etc. */
	.nodice { color: #D70000; }
	#nodice { color: #D70000; }
	#nodice input { border: 1px solid #D70000; }
	#nodice textarea { border: 1px solid #D70000; }
	#nodice table { border: 1px solid #D70000; }
	#nodice td { border: 1px solid #D70000; }
	
	/* Success text - opposite of #nodice */
	.done { color: #339959; }
	#done { color: #339959; }
	#done input { border: 1px solid #339959; }
	#done textarea { border: 1px solid #339959; }
	#done table { border: 1px solid #339959; }
	#done td { border: 1px solid #339959; }
	
	
	/* Border-exemptions - i.e. last cell of right-bordered row, bottom row of bordered table, etc. */
	#bdr_notop { border-top: 0px; }
	#bdr_noright { border-right: 0px; }
	#bdr_noleft { border-left: 0px; }
	#bdr_nobtm { border-bottom: 0px; }
	.nobdr-left { border-left: 0px !important; }
	
	/* Used mainly to give title cell the same padding as normal cells */
	#pad_flex { padding: inherit; }
	#pad_none { padding: 0px; }
	#pad_nobtm { padding-bottom: 0px; }
	#pad_notop { padding-top: 0px; }
	
	/* New look for 4.8.2 */
	.newDark {
	   color: #000000;
	   font-weight: bold;
	   }
	
	.newtext {
	   color: #000000;
	   }
	
	/*####################################################################################
	--------------------------------------------------------------------------------------
	>> Unique Elements
	--------------------------------------------------------------------------------------
	####################################################################################*/
	
	/*=================================================================================*/
	/* PopUp Div Layers (help screens, progress anis, user notes, version info, etc.)
	/*---------------------------------------------------------------------------------*/
	.gray_gel {
	   background-image: url(includes/display_elements/graphics/gray_gel.gif);
	   height: 23px;
	   border-bottom: 1px solid #2E2E2E;
	}
	
	
	/*---------------------------------------------------------------------------------------------------------*
	 ___        _    _
	| _ ) _  _ | |_ | |_  ___  _ _   ___
	| _ \| || ||  _||  _|/ _ \| ' \ (_-<
	|___/ \_,_| \__| \__|\___/|_||_|/__/
	
	/* New-school IE & FF friendly graphical buttons */
	/*---------------------------------------------------------------------------------------------------------*
	/* BIG BUTTONS  (ie 'Upload template files..') */
	/* Outer span has set dimenions and graphic bg */
	span.button_image {
	   display: block;
	   cursor: pointer;
	   padding: 0px 0px 0px 0px;
	   width: 200px;
	   height: 32px;
	}
	/* Inner span holds padded button text */
	span.button_image_text {
	   display: block;
	   text-align: left;
	   vertical-align: top;
	   padding: 10px 10px 0px 45px;
	}
	
	
	/* Spcecific buttons */
	#check_updates_btn_off { background-image: url('includes/display_elements/graphics/check_updates_btn-off.gif'); }
	#check_updates_btn_on { background-image: url('includes/display_elements/graphics/check_updates_btn-on.gif'); }
	
	/* DIALOG BUTTONS  (ie 'Install Now | Cancel') */
	/* Outer span has set dimenions and graphic bg */
	span.dialog_button {
	   display: block;
	   cursor: pointer;
	   padding: 0px 0px 0px 0px;
	   width: 102px;
	   height: 27px;
	}
	/* Inner span holds padded button text */
	span.dialog_button_text {
	   display: block;
	   text-align: left;
	   vertical-align: top;
	   padding: 7px 10px 0px 25px;
	   border: 0px solid red;
	}
	
	/* Spcecific buttons */
	#install_btn_off { background-image: url('includes/display_elements/graphics/install_btn-off.gif'); }
	#install_btn_on { background-image: url('includes/display_elements/graphics/install_btn-on.gif'); }
	#restart_btn_off { background-image: url('includes/display_elements/graphics/restart_btn-off.gif'); }
	#restart_btn_on { background-image: url('includes/display_elements/graphics/restart_btn-on.gif'); }
	#cancel_btn_off { background-image: url('includes/display_elements/graphics/cancel_btn-off.gif'); }
	#cancel_btn_on { background-image: url('includes/display_elements/graphics/cancel_btn-on.gif'); }
	#buy_btn_off { background-image: url('includes/display_elements/graphics/buy_btn-off.gif'); }
	#buy_btn_on { background-image: url('includes/display_elements/graphics/buy_btn-on.gif'); }
	
	/*=================================================================================*/
	/* Icon and button in footer that shows version info and update notifications
	/*---------------------------------------------------------------------------------*/
	#update_orb {
	   border: 0px solid red;
	   z-index: 10;
	   width: 200px;
	   height: 19px;
	   text-align: right;
	   vertical-align: top;
	   padding-top: 3px;
	   padding-right: 25px;
	   background-repeat: no-repeat;
	   background-attachment: fixed;
	   background-position: right top;
	}
	
	/* One of these will always be employed with #update_orb */
	.orb_off { background-image: url(includes/display_elements/graphics/ftr-update_orb-off.gif); }
	.orb_green { background-image: url(includes/display_elements/graphics/ftr-update_orb-grn.gif); }
	.orb_red { background-image: url(includes/display_elements/graphics/ftr-update_orb-red.gif); }
	.orb_orange { background-image: url(includes/display_elements/graphics/ftr-update_orb-orange.gif); }
	
	
	
	/*####################################################################################
	--------------------------------------------------------------------------------------
	>> Buttons
	--------------------------------------------------------------------------------------
	####################################################################################*/
	
	/*================================================================================
	>> Upper Bar Navigation Buttons
	================================================================================*/
	.nav_main, .nav_mainon, .nav_mainmenu, .nav_mainmenuon, .nav_save, .nav_saveon, .nav_soho, .nav_sohoon, .nav_logout, .nav_logouton {
		color: #FFFFFF;
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		cursor: pointer;
	}
	
	.nav_main, .nav_mainon, .nav_mainmenu, .nav_mainmenuon {
	   background-color: #144B81;
		border: 1px solid #595959;
	}
	
	.nav_main { background-image: url(includes/display_elements/graphics/btn-nav_main-off.jpg); }
	.nav_mainon { background-image: url(includes/display_elements/graphics/btn-nav_main-on.jpg); }
	
	
	.nav_mainmenu {
		font-weight: bold;
	}
	
	.nav_mainmenuon {
		background-color: #3283D3;
		font-weight: bold;
	}
	
	
	.nav_save, .nav_saveon {
		background-color: #087D34;
		border: 2px solid #66CC70;
	}
	
	.nav_saveon {
		background-color: #149845;
	}
	
	.nav_soho, .nav_sohoon {
		background-color: #815714;
		border: 2px solid #CC9B66;
	}
	
	.nav_sohoon {
		background-color: #FF6600;
	}
	
	.nav_logout, .nav_logouton {
		background-color: #9B0000;
		border: 2px solid #CC6666;
	}
	
	.nav_logouton {
		background-color: #D70000;
	}
	
	
	/*================================================================================
	>> Regular buttons
	================================================================================*/
	
	
	.btn_edit, .btn_editon, .btn_save, .btn_saveon, .btn_delete, .btn_deleteon, .btn_build, .btn_buildon, .btn_risk, .btn_riskon {
		background-color: #C3DEFF;
		font-family: tahoma, verdana, arial, helvetica, sans-serif;
		color: #000000;
		font-size: 8pt;
		cursor: pointer;
		border: 2px solid #6699CC;
		border-right: 2px solid #336699;
		border-bottom: 2px solid #336699;
	   border-left: 2px solid #6699CC;
	}
	
	
	.btn_editon {
		background-color: #C3EDFF;
	}
	
	.btn_save, .btn_saveon {
		background-color: #14B21C;
		color: #ffffff;
		border-top: 2px solid #158B1A;
		border-right: 2px solid #166D1A;
		border-bottom: 2px solid #166D1A;
	   border-left: 2px solid #158B1A;
	}
	
	.btn_saveon {
		background-color: #10D91A;
	}
	
	.btn_delete, .btn_deleteon {
		background-color: #E31A1A;
		color: #FFFFFF;
		border-top: 2px solid #B81B1B;
		border-right: 2px solid #680808;
		border-bottom: 2px solid #680808;
	   border-left: 2px solid #B81B1B;
	}
	
	.btn_deleteon {
		background-color: #FF0000;
	}
	
	/* For actions that may lead to undesireable but not necessarily irrecoverable consequences */
	.btn_risk, .btn_riskon {
		background-color: #F75D00;
		color: #FFFFFF;
		border-top: 2px solid #B81B1B;
		border-right: 2px solid #680808;
		border-bottom: 2px solid #680808;
	   border-left: 2px solid #B81B1B;
	}
	
	.btn_riskon {
		background-color: #FE7613;
	}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	|| These btn_build buttons are mostly used for features that
	|| add something to the site (i.e. upload files, build form)
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	.btn_build, .btn_buildon {
		background-color: #BDEED1;
		color: #000000;
		border-top: 2px solid #66CCA2;
		border-right: 2px solid #33996D;
		border-bottom: 2px solid #33996D;
	   border-left: 2px solid #66CCA2;
	}
	
	.btn_buildon {
		background-color: #B1FAD0;
	}
	
	
	/*================================================================================
	>> Minor action buttons within features (ie. "Move Item Up")
	================================================================================*/
	.btn_blue, .btn_green, .btn_red, .btn_orange {
		background-color: #C3DEFF;
		font-family: tahoma, verdana, arial, helvetica, sans-serif;
		color: #FFF;
		font-size: 8pt;
		cursor: hand;
	}
	
	.btn_blue {
		background-color: #336699;
		color: #FFFFFF;
		font-size: 8pt;
		cursor: hand;
		border: 2px outset #6699CC;
	}
	
	.btn_green {
		background-color: #087D34;
		color: #FFFFFF;
		font-size: 8pt;
		cursor: hand;
		border: 2px outset #66CC91;
	}
	
	.btn_red {
		background-color: #6E0000;
		color: #FFFFFF;
		font-size: 8pt;
		cursor: hand;
		border: 2px outset #9B0000;
	}
	
	.btn_orange {
		background-color: #D75B00;
		color: #FFFFFF;
		font-size: 8pt;
		cursor: hand;
		border: 2px outset #9B5800;
	}
	
	
	/*####################################################################################
	--------------------------------------------------------------------------------------
	>> Form fields
	--------------------------------------------------------------------------------------
	####################################################################################*/
	
	SELECT {
		font-family: verdana, arial, helvetica, sans-serif;
		font-size: 9px;
	}
	
	.tfield_hex {
	   font-family: verdana, arial, helvetica, sans-serif;
	   font-size: 10px;
	   color: #727272;
	   width: 57px;
	}
	
	.tfield {
	   font-family: verdana, arial, helvetica, sans-serif;
	   font-size: 10px;
	}
	
	
	/*####################################################################################
	--------------------------------------------------------------------------------------
	>> Text Links
	--------------------------------------------------------------------------------------
	####################################################################################*/
	
	.hand { cursor: pointer; }
	
	a:link { color: #336699; text-decoration: underline; }
	a:visited { color: #336699; text-decoration: underline; }
	a:hover { color: #6699cc; text-decoration: underline; }
	a:active { color: #a5c6e6; text-decoration: underline; }
	
	/* Help Links: Typically spawn a div help popup onclick */
	.help_popup_link:after { content: url("../icons/help_link_icon.gif"); }
	
	/* Main Menu: Enabled module links */
	a.on:link { color: #000000; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
	a.on:visited { color: #000000; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
	a.on:hover { color: #336699; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
	
	/* Main Menu: Disabled module links */
	a.off:link { color: #CCCCCC; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
	a.off:visited { color: #CCCCCC; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
	a.off:hover { color: #000000; font-size: 10px; cursor: pointer; font-weight: normal; text-decoration: none; }
	
	TABLE.clsNavLinks A:hover{text-decoration: none;}
	TABLE.clsNavLinks { clear: both; }
	
	.menusys a:link { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; text-decoration: none; }
	.menusys a:visited { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; text-decoration: none; }
	.menusys a:hover { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; text-decoration: underline;}
	.menusys a:active { font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 9pt; text-decoration: underline; }
	
	a.act:link { color: #F75D00; text-decoration: underline; }
	a.act:visited { color: #F75D00; text-decoration: underline; }
	a.act:hover { color: #FD8D3B; text-decoration: underline; }
	a.act:active { color: #FFC417; text-decoration: underline; }
	
	a.sav:link { color: #339959; text-decoration: underline; }
	a.sav:visited { color: #339959; text-decoration: underline; }
	a.sav:hover { color: #66CC91; text-decoration: underline; }
	a.sav:active { color: #A5E6B3; text-decoration: underline; }
	
	a.del:link { color: #9B0000; text-decoration: underline; }
	a.del:visited { color: #9B0000; text-decoration: underline; }
	a.del:hover { color: #D70000; text-decoration: underline; }
	a.del:active { color: #FF0000; text-decoration: underline; }
	
	a.darkbg:link { color: #FFFFFF; text-decoration: underline; }
	a.darkbg:visited { color: #FFFFFF; text-decoration: underline; }
	a.darkbg:hover { color: #D70000; text-decoration: underline; }
	a.darkbg:active { color: #EFEFEF; text-decoration: underline; }
	
	
	
	
	/*################################################################################################
	  _____                                 _   _    _
	 / ____|                               | | | |  | |
	| |  __   ___  _ __    ___  _ __  __ _ | | | |  | | ___   ___
	| | |_ | / _ \| '_ \  / _ \| '__|/ _` || | | |  | |/ __| / _ \
	| |__| ||  __/| | | ||  __/| |  | (_| || | | |__| |\__ \|  __/
	 \_____| \___||_| |_| \___||_|   \__,_||_|  \____/ |___/ \___|
	
	# Originally copied from info.soholaunch.com
	# Complete css revamp coming in v5
	/*################################################################################################
	
	/* LINKS */
	/*----------------------------*/
	
	/* Default link style */
	a:link {color: #306fae; text-decoration: underline; border-bottom:0px solid #004C9A;}
	a:visited {color: #306fae; text-decoration: underline; border-bottom:0px solid #004C9A;}
	a:hover {color: #6699cc; text-decoration: underline; border-bottom:0px solid #AEC9FF;}
	a:active {color: #A5C6E6; text-decoration: underline; border-bottom:0px solid #AEC9FF;}
	
	/* Mark all external links */
	a.external:link:after { content: url("external_link_icon-10px.gif"); }
	a.external:visited:after { content: url("external_link_icon-10px.gif"); }
	a.external:link { text-decoration: none; border-bottom: 1px dotted #7a7a7a; }
	a.external:visited { text-decoration: none; border-bottom: 1px dotted #7a7a7a; }
	a.external:hover { text-decoration: none; border-bottom: 1px dotted #7a7a7a; }
	a.external:active { text-decoration: none; border-bottom: 1px dotted #7a7a7a; }
	
	a.link_green:link  { color: #30ae6f !important; }
	a.link_green:visited { color: #30ae6f !important; }
	a.link_green:hover { color: #66cc91 !important; }
	a.link_green:active { color: #A5E6B3 !important; }
	
	a.link_orange:link  { color: #F75D00 !important; }
	a.link_orange:visited { color: #F75D00 !important; }
	a.link_orange:hover { color: #ff8d41 !important; }
	a.link_orange:active { color: #FFC417 !important; }
	
	a.link_whitebox:link  { color: #fff;text-decoration: none;border: 1px dotted #ccc;   }
	a.link_whitebox:visited { color: #fff;text-decoration: none;border: 1px dotted #ccc; }
	a.link_whitebox:hover { color: #fff;text-decoration: none;border: 1px dotted #fff; }
	a.link_whitebox:active { color: #fff;text-decoration: none;border: 1px dotted #fff; }
	
	
	/* BORDERS */
	/*----------------------------*/
	.bdr_nobtm { border-bottom: none !important; }
	.bdr_blue { border: 1px solid #6699CC !important; }
	.bdr_bluetop { border-top: 1px solid #6699CC !important; }
	.bdr_blueleft { border-left: 1px solid #6699CC !important; }
	.bdr_blueright { border-right: 1px solid #6699CC !important; }
	
	/* Newschool border colors */
	.bdr_blue_a5 { border-color: #a5c6e6; !important; }
	
	/* Newschool border styles */
	.bdr_solid_u { border-style: none solid solid solid; !important; }
	
	/* Newschool border widths */
	.bdr_1px { border-width: 1px; !important; }
	
	.bdr_right_dotted { border-right: 1px dotted #D1D5D8 !important; }
	.bdr_btm_dotted td { border-bottom: 1px dotted #D1D5D8; }
	
	
	/* BACKGROUND COLOR - newschool */
	.bg_gray_f8 { background-color: #F8F8F8 !important; }
	.bg_gray_ef { background-color: #efefef !important; }
	.bg_gray_a5 { background-color: #E8E8E8 !important; }
	.bg_gray_df { background-color: #D1D5D8 !important; }
	
	.bg_blue_f8 { background-color: #F8F9FD !important; }
	.bg_blue_a5 { background-color: #A5C6E6 !important; }
	.bg_blue_df { background-color: #DFECF6 !important; }
	.bg_blue_66 { background-color: #6699CC !important; }
	.bg_blue_30 { background-color: #306FAE !important; }
	.bg_blue_33 { background-color: #336699 !important; }
	.bg_blue_31 { background-color: #315173 !important; }
	
	.bg_green_f8 { background-color: #F8FDFB !important; }
	.bg_green_df { background-color: #DFF6EA !important; }
	.bg_green_a5 { background-color: #A5E6B3 !important; }
	.bg_green_66 { background-color: #66CC91 !important; }
	.bg_green_30 { background-color: #30AE6F !important; }
	.bg_green_33 { background-color: #339959 !important; }
	.bg_green_31 { background-color: #317344 !important; }
	
	.bg_red_98 { background-color: #980000 !important; }
	.bg_red_d7 { background-color: #d70000 !important; }
	.bg_red_66 { background-color: #cc6666 !important; }
	.bg_red_a5 { background-color: #e6a5a5 !important; }
	.bg_red_df { background-color: #f6dfdf !important; }
	.bg_red_f8 { background-color: #fdf8f8 !important; }
	
	
	/* BACKGROUND COLOR - oldstyle */
	.bg_white { background-color: #FFFFFF !important; }
	.bg_lgray { background-color: #F7F7F7 !important; }
	.bg_lgreen { background-color: #F8FDFB !important; }
	.bg_green { background-color: #DFF6EA !important; }
	.bg_lblue { background-color: #F8F9FD !important; }
	.bg_lblue2 { background-color: #F7F9FF !important; }
	.bg_yellow { background-color: #FFFF99 !important; }
	
	
	/* SHORTAND FONT FAMILY */
	.mono { font-family: courier !important; }
	
	
	/* FONT COLOR */
	.white { color: #FFFFFF !important; }
	
	.lblack { color: #2E2E2E !important; }
	.black { color: #000000 !important; }
	
	.gray_f8 { color: #F8F8F8 !important; }
	.gray_df { color: #D1D5D8 !important; }
	.gray_33 { background-color: white; color: #7A7A7A !important; }
	.gray_31 { color: #595959 !important; }
	.gray { color: #888c8e !important; }
	.dgray { color: #595959 !important; }
	
	.lblue { color: #6699cc !important; }
	.blue { color: #336699 !important; }
	.dblue { color: #315173 !important; }
	.blue_f8 { color: #F8F9FD !important; }
	.blue_df { color: #DFECF6 !important; }
	.blue_a5 { color: #A5C6E6 !important; }
	.blue_66 { color: #6699CC !important; }
	.blue_30 { color: #306FAE !important; }
	.blue_33 { color: #336699 !important; }
	.blue_31 { color: #315173 !important; }
	
	.red { color: #D70000 !important; }
	.dred { color: #980000 !important; }
	.orange { color: #FE7613 !important; }
	.green { color: #00831C !important; }
	.green_f8 { color: #F8FDFB !important; }
	.green_df { color: #DFF6EA !important; }
	.green_a5 { color: #A5E6B3 !important; }
	.green_66 { color: #66CC91 !important; }
	.green_30 { color: #30AE6F !important; }
	.green_33 { color: #339959 !important; }
	.green_31 { color: #317344 !important; }
	
	/* FONT STYLE */
	.bold { font-weight: bold !important; }
	.unbold { font-weight: normal !important; }
	.uline { text-decoration: underline !important; }
	.noline { text-decoration: none !important; }
	
	
	/* PADDING and MARGIN */
	.nopad { padding: 0px !important; }
	.nopad_top { padding-top: 0px !important; }
	.nopad_right { padding-right: 0px !important; }
	.nopad_btm { padding-bottom: 0px !important; }
	.nopad_left { padding-left: 0px !important; }
	
	.nomargin_btm { margin-bottom: 0px !important; }
	.nomar_btm { margin-bottom: 0px !important; }
	.nomar_top { margin-top: 0px !important; }
	.nomar { margin: 0px !important; }
	
	/* ALIGNMENT */
	.center { text-align: center !important; }
	.right { text-align: right !important; }
	.top { vertical-align: top !important; }
	.middle { vertical-align: middle !important; }
	
	/* OTHER MISC */
	
	/* Cross-browser hand pointer for buttons that use span onclicks and such */
	.hand { cursor: pointer !important; }
	
	/* Particularly handy for adding less-stressed text/links to fgroup_title headings */
	.normal { letter-spacing: normal !important; }
	.font90 { font-size: 90% !important; }
	
	/* Bottom borders for breaking up table rows */
	tr.row_spliter td { border-bottom: 1px dashed #ccc; }
	
	/* Full & Partial opacity styles - used to dim out user options layer when showing a popup div */
	.faded {
	   filter:progid:DXImageTransform.Microsoft.Alpha(opacity=40);
	   -moz-opacity: 0.4;
	}
	.notfaded {
	   filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100);
	   -moz-opacity: 1;
	}
	
	
	
	
	
	
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	Probably Outdated Sytles (duplicates removed) - menu.css
	------------------------------------------------------------------------------
	>> These are only going to remain in this file until there obsolecense
	>> can be investigated and (hopefully) proven.
	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	
	.mouse {cursor: default}
	.click {cursor: hand}
	.ob1 {cursor: hand;background: ghostwhite}
	.ob2 {cursor: hand;background: gainsboro}
	.saverec {font:8pt Arial,sans-serif;color: menutext; background: captiontext}
	
	.tbox {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 10pt; background-color: #CCCCCC; border: <?php echo $bgcolor; ?>; border-style: solid; border-top-width: thin; border-right-width: thin; border-bottom-width: thin; border-left-width: thin}
	
	
	.module_link {
	   font-family: verdana, arial, helvetica, sans-serif;
	   font-size: 11px;
	   font-weight: bold;
	   color: #000000;
	}
	
	.FormLt2 {
		font-family : Arial;
		font-size : 8pt;
		cursor: hand;
	}
	
	.FormLt1 {
		background-color: #336699;
		color: #FFFFFF;
		font-size: 8pt;
		cursor: hand;
		border: 2px solid #6699CC;
	}
	
	.chkout {
		font-family : Arial;
		font-size : 9pt;
		cursor: hand;
		width: 195px
	}
	
	.chkbox {
		font-size : 8pt;
		background-color : menu;
		border : none;
	}
	
	.allBorder {
		font-family: Arial;
		font-size: 8pt;
		border: 1px <?php echo $bgcolor; ?> inset;
		background: #EFEFEF;
	}
	
	.border {
		font-family: Arial;
		font-size: 8pt;
		border: 1px <?php echo $bgcolor; ?> inset;
	}
	
	
	
	.curoff {
		cursor: normal;
	}
	
	.curhand {
		cursor: hand;
	}
	
	.tblBorder {vertical-align: middle; border: 1 outset; margin: 0; font-family: Arial; font-size: 8pt; color: <?php echo $bgcolor; ?>;border: solid <?php echo $bgcolor; ?> 1px;border-left: 1px solid <?php echo $bgcolor; ?>;}
	.tblCell { border-left: 1px solid <?php echo $bgcolor; ?>; }
	.calcontrols {vertical-align: top; margin: 0; font-family: Arial; font-size: 8pt; background-color: #FFFFFF; color: #000000;}
	.calopcontrols {vertical-align: top; margin: 0; font-family: Arial; font-size: 8pt; background-color: oldlace; color: #000000;}
	.icalcontrols {vertical-align: top; margin: 0; font-family: Arial; font-size: 8pt; background-color: #FFFFFF; color: #000000;}
	.icalopcontrols {vertical-align: top; margin: 0; font-family: Arial; font-size: 8pt; background-color: #E6E6E6; color: #000000;}
	.catselect {font-family: Arial; font-size: 8pt;}
	.hintbox {width: 15; height: 15; vertical-align: top;}
	
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	
	
	/** {
	   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
	   font-size: 11px;
	   line-height: normal;
	}*/
	
	
	
	/* shopping_cart.php */
	table#shopping_cart_menu th, table#shopping_cart_menu td {
	   text-align: left;
	   border-left: 1px dotted #ccc;
	   padding: 5px 40px;
	}
	table#shopping_cart_menu td {
	   text-align: left;
	}
	
	h2 {
	   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
	   font-size: 15px;
	   font-weight: bold;
	   margin-bottom: 0;
	}
	
	/* Outer module container table with breadcrumb row, icon/heading row, body row */
	/* This should eventually replace feature_sub class */
	table.module_container {
	   width: 100%;
	   margin-top: 10px;
	   font-family: verdana, arial, helvetica, sans-serif;
		font-size: 10px;
		border: 1px solid #2E2E2E;
		background: #f8f9fd;
	}
	table.module_container td.module_body_area {
	   padding: 10px;
	}
	
	.fgroup_title {
	   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
	   font-weight: normal;
	   letter-spacing: normal;
	   font-size: 11px;
	}
	.fgroup_title a:link, .fgroup_title a:visited { color: #fff; text-decoration: none; }
	.fgroup_title a:hover, .fgroup_title a:active { color: #fff; text-decoration: underline; }
	
	
	/* These things: [?] --- the ones that spawn help popup divs */
	.help_link {
	   color: #FE7613;
	   cursor: pointer;
	   font-size: 90%;
	   text-decoration: underline;
	   cursor: pointer;
	}
	
	.note { color: #b1b1b1; }
	
	
	/* $report[] output layer in module template */
	div#report_messages {
	   width: 100%;
	   /*margin-top: -10px;*/
	   margin: 0;
	   /*padding-left: 40px;*/
	   background-color: #FFFAB2;
	}
	div#report_messages ul {
	   margin: 0;
	   list-style-type: square;
	}
	div#report_messages ul li {
	   /*margin-left: 15px;*/
	}
	
	
	p#module-description_text {
	   margin: 0;
	}
	
	
	/*------------------------------------------------------------------------------------------*
	New look for feature module headings?
	/*------------------------------------------------------------------------------------------*/
	table.feature_module_heading {
	   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
	   border-bottom: 1px solid #ccc;
	   color: #2e2e2e;
	   background-color: #d9e6ef;
	   background-image: url('../skins/default/feature_module_heading.gif');
	}
	
	table.feature_module_heading td {
	   padding: 5px;
	}
	
	.feature_module_heading h1 {
	   margin-top: 0px;
	   margin-bottom: 0;
	   font-size: 18px;
	}
	
	/* Module Description Text */
	.feature_module_heading p {
	   margin-top: 0px;
	   margin-bottom: 0px;
	   font-size: 12px;
	   line-height: 1em;
	}
	
	
	/*------------------------------------------------------------------------------------------*
	General-use
	/*------------------------------------------------------------------------------------------*/
	.bg_yellow { background-color: #FFFAB2; }
	.bg_yellow_dark { background-color: #FFF66F; }
	.fadeout { opacity: .5;-moz-opacity: 0.5;filter: alpha(opacity=50); }
	.fade15 { opacity: .15;-moz-opacity: 0.15;filter: alpha(opacity=15); }
	.fade30 { opacity: .3;-moz-opacity: 0.3;filter: alpha(opacity=30); }
	.fadein { opacity: 1 !important;-moz-opacity: 1 !important;filter: alpha(opacity=100); }
	
	
	/*------------------------------------------------------------------------------------------*
	 Stuff that will probably only apply to plugin manager starts here
	/*------------------------------------------------------------------------------------------*/
	/* Rectangle block for individual plugin with icon, title, author, descript, etc. */
	div.plugin_block, div.plugin_block-hover {
	   position: relative;
	   /*background-repeat: no-repeat;*/
	   width: 700px;
	   height: 65px;
	   cursor: default;
	   border: 1px dashed #ccc;
	}
	div.plugin_block-hover {
	   /*background-image: url('plugin_block-hover.gif');*/
	   background-color: #f2f7fb !important;
	}
	
	div.plugin_description {
	   background-position: 7px 15px;
	   background-repeat: no-repeat;
	   padding: 13px;
	   padding-left: 50px;
	   width: 385px;
	   overflow: auto;
	}
	
	/* BUTTON: Install new plugin */
	.install_button, .install_button-hover {
	   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
	   width: 152px;
	   background-repeat: no-repeat;
	   padding: 5px;
	   font-size: 12px;
	   text-align: left;
	   padding-top: 5px;
	   padding-left: 30px;
	   font-weight: bold;
	   cursor: pointer;
	}
	.install_button { background-image: url('images/install_plugin.gif'); }
	.install_button-hover { background-image: url('images/install_plugin-hover.gif'); }
	
	/* BUTTON: Uninstall Plugin */
	.uninstall_button, .uninstall_button-hover  {
	   /*font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;*/
	   /*width: 100px;*/
	   background-repeat: no-repeat;
	   padding: 8px;
	   padding-top: 4px;
	   padding-left: 17px;
	   text-align: left;
	   font-size: 10px;
	   /*font-weight: bold;*/
	   position: absolute;
	   top: 15px;
	   right: 0px;
	   cursor: pointer;
	}
	.uninstall_button  { background-image: url('images/uninstall_button.gif'); }
	.uninstall_button-hover  { background-image: url('images/uninstall_button-hover.gif'); }
	
	/* BUTTON: Options */
	.options_button, .options_button-hover  {
	   /*font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;*/
	   /*width: 100px;*/
	   background-repeat: no-repeat;
	   padding: 8px;
	   padding-top: 4px;
	   padding-left: 20px;
	   text-align: left;
	   font-size: 10px;
	   /*font-weight: bold;*/
	   position: absolute;
	   top: 15px;
	   right: 175px;
	   cursor: pointer;
	}
	.options_button  { background-image: url('images/options_button.gif'); }
	.options_button-hover  { background-image: url('images/options_button-hover.gif'); }
	
	/* BUTTON: Update Now */
	.update_button, .update_button-hover  {
	   /*font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;*/
	   /*width: 100px;*/
	   background-repeat: no-repeat;
	   padding: 8px;
	   padding-top: 4px;
	   padding-left: 21px;
	   text-align: left;
	   font-size: 10px;
	   /*font-weight: bold;*/
	   position: absolute;
	   top: 15px;
	   right: 90px;
	   cursor: pointer;
	}
	.update_button  { background-image: url('images/update_button.gif'); }
	.update_button-hover  { background-image: url('images/update_button-hover.gif'); }
	
	.droptable_option  {
	   font-family: Trebuchet MS, tahoma, arial, helvetica, sans-serif;
	   text-align: left;
	   font-size: 12px;
	   position: absolute;
	   top: 40px;
	   right: 10px;
	   cursor: pointer;
	}
	
	/* IE Float border workaround */
	.ie_cleardiv {
	   margin: 0;
	   clear: both;
	}
	
	
	.tab-off, .tab-on {
	   text-align: center;
	   width: 125px;
	   height: 18px;
	   vertical-align: top;
	   padding: 5px 10px;
	   background-color: #efefef;
	   border: 1px solid #ccc;
	   border-top: 3px solid #ccc;
	   border-bottom: 0;
	   color: #595959;
	   cursor: pointer;
	}
	
	.tab-on {
	   color: #000;
	   background-color: #efefef;
	   border-top: 3px solid #175aaa;
	   font-weight: bold;
	}
	
	</style>
	<?php
	//include("../../includes/product_gui.php");
	
	#######################################################
	### DEAL WITH "EMPTY" TABLE REQUEST		 		    ###
	#######################################################
	
	# Clear recent page list
	if ( $_GET['todo'] == "clear_recent" ) {
	   $_SESSION['recent_tables'] = null;
	}
	unset($_SESSION['recent_tables']['']);
	
	//include('soho/sohoadmin/program/includes/shared_functions.php');
	error_reporting(E_PARSE && E_ERROR);
	
	if ($action == "empty") {
	
		$THIS_DISPLAY .= "<form method=\"post\" ACTION=\"".$simple_name."?mysqlmode=downloaddata\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"empty2\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"TABLE_NAME\" value=\"$table\">\n";
	
		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=10 cellspacing=\"0\" class=\"text\" width=\"100%\" height=100% bgcolor=RED style='BORDER: 1px inset ".$bgcolor.";'>\n";
		$THIS_DISPLAY .= "<tr>\n";
		$THIS_DISPLAY .= "<td align=\"center\" valign=\"middle\" class=\"text\">\n";
	
		$THIS_DISPLAY .= "<font COLOR=WHITE FACE=VERDANA SIZE=4><b>!! ".lang("WARNING")." !!</font><BR><BR><font COLOR=WHITE SIZE=2><b>\n";
		$THIS_DISPLAY .= "".lang("You have selected to clear the data from table")." \"$table\".\n";
		$THIS_DISPLAY .= "<BR>".lang("This process is irreversible and will delete all data contained in this table").".\n";
		$THIS_DISPLAY .= "<BR><BR>".lang("Are you sure you wish to continue")."?<BR><BR>\n";
		$THIS_DISPLAY .= "</td></tr></table>\n";
		$THIS_DISPLAY .= "<br><br><div align=\"center\">\n";
		$THIS_DISPLAY .= "<input TYPE=SUBMIT value=\" ".lang("Continue")." \" class=FormLt1 style='width: 100px;' >\n";
		$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
		$THIS_DISPLAY .= "<input type=\"button\" value=\"  ".lang("Cancel")."  \" class=FormLt1 style='width: 100px;' onclick=\"cancel();\">\n";
		$THIS_DISPLAY .= "</div><BR></form>\n";
	
	} // End Empty STEP 1
	
	if ($action == "empty2") {
	
		mysql_query("DELETE FROM $TABLE_NAME");
		$action = "";
	
	}
	
	
	/*---------------------------------------------------------------------------------------------------------*
	 ___                         _           _
	|_ _| _ __   _ __  ___  _ _ | |_   ___  / |
	 | | | '  \ | '_ \/ _ \| '_||  _| |___| | |
	|___||_|_|_|| .__/\___/|_|   \__|       |_|
	            |_|
	# IMPORT DATA TO TABLE ROUTINE (STEP 1: Choose csv file)
	/*---------------------------------------------------------------------------------------------------------*/
	if ($action == "import") {
	
	   # Log in recent table list for quick links elsewhere
	   $_SESSION['recent_tables'][strtolower($_REQUEST['table'])] = $_REQUEST['table']; // strtolower necc for ksort()
	
		// ------------------------------------------------------
		// Step 1: First, Read all CSV files from user directory
		// ------------------------------------------------------
		$CSV_OPTIONS = "      <option value=\"NONE\" style='color: #999999'>".lang("CSV Filenames").": </option>\n";
	
		$directory = "$doc_root/media";
	
		if (is_dir($directory)) {
			$handle = opendir("$directory");
			while ($files = readdir($handle)) {
				if (strlen($files) > 2 && eregi("\.csv", $files)) {
					if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
					$d = strtoupper($files);
					$CSV_OPTIONS .= "      <option value=\"$doc_root/media/$files\" style='background: $tmp'>$d </option>\n";
				}
			}
			closedir($handle);
		}
	
		// ------------------------------------------------------
		// Step 2: Let user choose the filename to import
		// ------------------------------------------------------
	
		$THIS_DISPLAY .= "<form method=\"post\" ACTION=\"".$simple_name."?mysqlmode=downloaddata\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"import2\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"TABLE_NAME\" value=\"$table\">\n";
	
		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=5 cellspacing=\"0\" class=\"text\" width=100% bgcolor=#708090 style='BORDER: 1px inset ".$bgcolor.";'>\n";
		$THIS_DISPLAY .= "<tr>\n";
		$THIS_DISPLAY .= "<td align=\"left\" valign=\"middle\" class=\"text\">\n";
		$THIS_DISPLAY .= "<font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("Select the CSV file that you wish to import").":</font>\n";
		$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\">\n";
	
			$THIS_DISPLAY .= "<BR>CSV Filename: \n";
			$THIS_DISPLAY .= "<select name=\"CSV_FILENAME\" style='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 200px;'>$CSV_OPTIONS</select>\n";
	
			$THIS_DISPLAY .= "<BR><BR><font COLOR=#999999>".lang("Please note that you can only upload comma or semi-colon delimited CSV files").".<BR>".lang("If you need to upload your csv file").", <a href=\"../upload_files.php?=SID\">".lang("click here")."</a>.</font>";
	
			$THIS_DISPLAY .= "<BR><BR><BR><div align=\"right\"><input type=\"button\" value=\" ".lang("Cancel")." \" class=FormLt1 onclick=\"cancel();\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input TYPE=SUBMIT value=\" Next >> \" class=FormLt1></DIV>\n\n";
	
		$THIS_DISPLAY .= "</td></tr></table>\n";
	
		$THIS_DISPLAY .= "</form>";
	
	}
	
	/*---------------------------------------------------------------------------------------------------------*
	 ___                         _           ___
	|_ _| _ __   _ __  ___  _ _ | |_   ___  |_  )
	 | | | '  \ | '_ \/ _ \| '_||  _| |___|  / /
	|___||_|_|_|| .__/\___/|_|   \__|       /___|
	            |_|
	# IMPORT DATA TO TABLE ROUTINE (STEP 2: Match fields w/ column headings)
	/*---------------------------------------------------------------------------------------------------------*/
	if ($action == "import2") {
	
		if ($CSV_FILENAME == "NONE") {
			header ("Location: ".$simple_name."?mysqlmode=downloaddata&table=$TABLE_NAME&action=import&err=1&=SID");
			exit;
		}
	
		// ------------------------------------------------------
		// Step 1: Get Import Table Field Names to Match with CSV
		// ------------------------------------------------------
	
		$result = mysql_query("SELECT * FROM $TABLE_NAME");
		$NUM_TABLE_FIELDS = mysql_num_fields($result) - 1;
	
		$THIS_KEY_FIELD = "";
	
		for ($x=0;$x<=$NUM_TABLE_FIELDS;$x++) {
			$TABLE_FIELD[$x] = mysql_field_name($result, $x);
			$TABLE_FIELD_TYPE[$x] = mysql_field_type($result, $x);
		} // End For Loop
	
		// ------------------------------------------------------
		// Step 2: Grab the first line of the CSV file as the
		// field names for matching
		// ------------------------------------------------------
	
		$fp = fopen("$CSV_FILENAME", "r");
			$csv_binary = fread($fp,filesize($CSV_FILENAME));
		fclose($fp);
	
		$csv_line = split("\n", $csv_binary);
	
		// Auto Determine the field delimter
	
		if (eregi(";", $csv_line[0])) {
			$csv_field_data = split(";", $csv_line[0]);
			$delimeter = ";";
		} else {
			$csv_field_data = split(",", $csv_line[0]);
			$delimeter = ",";
		}
	
		$NUM_CSV_FIELDS = count($csv_field_data) - 1;
		$csv_column = array();
		$CSV_OPTIONS = " <option value=\"DEFAULT\" style=\"color: #999999;\">".lang("Use Default Value")." </option>\n";
	
		# Build drop down options for csv columns
		for ($x=0;$x<=$NUM_CSV_FIELDS;$x++) {
		   $d = trim($csv_field_data[$x]);
	
		   $csv_column[$d] = $x; // Store csv field names and matching dd value numbers in array for auto-matching
	
			// Modified for labels to be greater than 1 (2003-03-25)
			if (strlen($d) > 1) {
				$CSV_OPTIONS .= " <option value=\"".$x."\">".$d."</option>\n";
			}
		}
	
		// ------------------------------------------------------
		// Step 3: Setup Field Matching GUI for pre-import setup
		// ------------------------------------------------------
	
		$THIS_DISPLAY .= "<form method=\"post\" ACTION=\"".$simple_name."?mysqlmode=downloaddata\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"import3\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"TABLE_NAME\" value=\"$TABLE_NAME\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"CSV_FILENAME\" value=\"$CSV_FILENAME\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"delimeter\" value=\"$delimeter\">\n";
	
		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=6 cellspacing=\"0\" width=95% class=\"text\" bgcolor=#708090 style='BORDER: 1px inset ".$bgcolor.";'>\n";
		$THIS_DISPLAY .= " <tr>\n";
		$THIS_DISPLAY .= "  <td align=\"left\" valign=\"middle\" class=\"text\">\n";
		$THIS_DISPLAY .= "   <font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("Select which fields in the CSV file to place into the existing table fields").":</font>\n";
		$THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= " <tr>\n";
		$THIS_DISPLAY .= "  <td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\"><BR>\n";
		$THIS_DISPLAY .= "   <input TYPE=CHECKBOX CHECKED name=FIELDNAMES value=ON> ".lang("First record of CSV data contains field names. Do not import.")."<BR><BR>\n";
	
	   $THIS_DISPLAY .= "   <table border=\"1\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\">\n";
	   $THIS_DISPLAY .= "    <tr>\n";
	   $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" class=\"text col_title\" width=\"100\"><b>".lang("Table Field Name")."</td>\n";
	   $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" class=\"text col_title\"><b>".lang("CSV Field Name")."</td>\n";
	   $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" class=\"text col_title\"><b>".lang("Default Import Value")."</td>\n";
	   $THIS_DISPLAY .= "    </tr>\n";
	
	   # Loop through table fields and spit out table row with dropdown and default value textfield
	   for ($x=0;$x<=$NUM_TABLE_FIELDS;$x++) {
	
	      if ($tmp == "WHITE") { $tmp = "#EFEFEF"; } else { $tmp = "WHITE"; }
	
	      $db_field_name = $TABLE_FIELD[$x]; // Readability
	      $this_type = strtoupper($TABLE_FIELD_TYPE[$x]);
	
	      $THIS_DISPLAY .= "    <tr>\n";
	
	      # DB Field
	      $THIS_DISPLAY .= "     <td align=\"right\" valign=\"middle\" class=\"text\" bgcolor=\"".$tmp."\">\n";
	      $THIS_DISPLAY .= "      ".$TABLE_FIELD[$x]." <font COLOR=#999999>[".$this_type."]</font>:\n";
	      $THIS_DISPLAY .= "     </td>\n";
	
	      # Matching CSV field
	      $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" bgcolor=\"".$tmp."\">\n";
	      $THIS_DISPLAY .= "      <select name=\"IMPORT".$x."\" id=\"".$TABLE_FIELD[$x]."_dd\" style='FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 200px;'>\n";
	      $THIS_DISPLAY .= "       ".$CSV_OPTIONS."\n";
	      $THIS_DISPLAY .= "      </select>\n";
	
	      # auto-select csv field if name match found
	      if ( isset($csv_column[$db_field_name]) ) {
	         $THIS_DISPLAY .= "     <script type=\"text/javascript\">document.getElementById('".$TABLE_FIELD[$x]."_dd').value = '".$csv_column[$db_field_name]."';</script>\n";
	      }
	
	      $THIS_DISPLAY .= "     </td>\n";
	
	      # Default import value
	      $THIS_DISPLAY .= "     <td align=\"center\" valign=\"middle\" bgcolor=\"".$tmp."\">\n";
	      $THIS_DISPLAY .= "      <input type=\"text\" name=\"DEFAULT".$x."\" value=\"\" style=\"FONT-FAMILY: Arial; FONT-SIZE: 8pt; WIDTH: 200px;\">\n";
	      $THIS_DISPLAY .= "     </td>\n";
	      $THIS_DISPLAY .= "    </tr>\n";
	
	   }
	   $THIS_DISPLAY .= "   </table>\n";
	
	   $THIS_DISPLAY .= "   <div style=\"text-align: left;\">\n";
	   $THIS_DISPLAY .= "    <BR><font COLOR=#999999>".lang("If a field name from your csv file is matched to the PriKey field of the table")."</font>\n";
		$THIS_DISPLAY .= "    <br/>\n";
		$THIS_DISPLAY .= "    <label for=\"leave_default_alone\"><input type=\"checkbox\" id=\"leave_default_alone\" name=\"leave_default_alone\" value=\"yes\">\n";
		$THIS_DISPLAY .= "   ".lang("Checking this option effectively changes the result of the \"Use Default Value\" option to:");
		$THIS_DISPLAY .= "    \"".lang("Leave existing field data alone instead of over-riding with the Default Import Value (which is usually 'nothing').")."\"</label>\n";
		$THIS_DISPLAY .= "   </div>\n";
	
	
	
	   $THIS_DISPLAY .= "   <BR><BR><BR><div align=\"right\"><input type=\"button\" value=\" Cancel \" class=FormLt1 onclick=\"cancel();\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input TYPE=SUBMIT value=\" ".lang("Import Data Now")." \" class=FormLt1 onclick=\"build();\"></DIV>\n\n";
	
		$THIS_DISPLAY .= "  </td>\n";
		$THIS_DISPLAY .= " </tr>\n";
		$THIS_DISPLAY .= "</table>\n";
	
		$THIS_DISPLAY .= "</form>";
	
	} // End Import Step 2
	
	
	/*---------------------------------------------------------------------------------------------------------*
	 ___                         _           ____
	|_ _| _ __   _ __  ___  _ _ | |_   ___  |__ /
	 | | | '  \ | '_ \/ _ \| '_||  _| |___|  |_ \
	|___||_|_|_|| .__/\___/|_|   \__|       |___/
	            |_|
	# IMPORT DATA TO TABLE ROUTINE (STEP 3: Actually import data now)
	/*---------------------------------------------------------------------------------------------------------*/
	if ($action == "import3") {
	
	//   echo testArray($_POST);
	
		$true_import_count = 0;
	
		// ------------------------------------------------------
		// Step 1: Get Import Table Field Names to Match with CSV
		// ------------------------------------------------------
		$result = mysql_query("SELECT * FROM $TABLE_NAME");
		$NUM_TABLE_FIELDS = mysql_num_fields($result) - 1;
	
		$THIS_KEY_FIELD = "";
	
		for ($x=0;$x<=$NUM_TABLE_FIELDS;$x++) {
			$TABLE_FIELD[$x] = mysql_field_name($result, $x);
	
			$meta = mysql_fetch_field($result, $x);
			if ($meta->primary_key == 1) { $THIS_KEY_FIELD = $TABLE_FIELD[$x]; }	// Located Key Field for this Table
	
		} // End For Loop
	
		$key_active = "no";							// Assume in the start that we are not modifing the key field
	
		// ------------------------------------------------------
		// Step 2: Grab the first line of the CSV file as the
		// field names for matching
		// ------------------------------------------------------
		$row = 0;
		$fp = fopen ($CSV_FILENAME, "r");
	
		while ($data = fgetcsv($fp, 3000, $delimeter)) {
	
			$num_fields = count ($data);
			$row++;
	
	    	for ($c=0; $c<$num_fields; $c++) {
				$csv_field[$c] = $data[$c];
			}
	
			// ------------------------------------------------------
			// Reset SQL command data variable
			// ------------------------------------------------------
			$sql_build = "";
			$sql_update = "";
	
			// ------------------------------------------------------
			// Loop through CSV field data and format for SQL insert
			// ------------------------------------------------------
			for ($x=0;$x<=$NUM_TABLE_FIELDS;$x++) {
	
				$tmp = "IMPORT" . $x;
				$placement = ${$tmp};
	
				$tmp = "DEFAULT" . $x;
				$this_default = ${$tmp};
	
				if ($placement == "DEFAULT") {
					$this_data = $this_default;
				} else {
					$this_data = $csv_field[$placement];
				}
				
				// Is data a serialized array?
				// If so remove the last (")...
				// For some reason the last quote is not always removed by fgetcsv
				
				if(substr($this_data, -1) == "\"" && $this_data{0} == "\""){
				   $this_data = substr($this_data, 1, -1);
				}
	
				$this_data = stripslashes($this_data);
				$this_data = addslashes($this_data);
	
				if($TABLE_FIELD[$x] == 'full_desc' && $TABLE_NAME == 'cart_products'){
					$this_data = base64_encode($this_data);
				}
				// ------------------------------------------------------
				// Is this a key update or add
				// ------------------------------------------------------
				if ($TABLE_FIELD[$x] == $THIS_KEY_FIELD && $this_data != "NULL") {		// This is an edit record check
					$key_active = "yes";
					$check_key_value = $this_data;
	
	//				# TESTING
	//				$testouput .= "<br/><strong>(".$TABLE_FIELD[$x]." == ".$THIS_KEY_FIELD." && ".$this_data." != NULL) - key_active = yes!</strong>";
	
				} else {
				   # Do not update value of this field with blank value if option checked to leave fields alone instead of using default value
				   if ( $this_data != "" || $_POST['leave_default_alone'] != "yes"  ) {
				     $this_data = str_replace(":semi:", ";", $this_data);
					   $sql_update .= "$TABLE_FIELD[$x] = '$this_data', ";
					}
	
	//				# TESTING
	//				$testouput .= "<br/>[".$TABLE_FIELD[$x]."] = '".$this_data."' - key_active = no :-(";
				}
	
				$sql_build .= "'$this_data', ";
	
			} // end $x loop
	
			// Delete Last Comma From sql_build string
			// ----------------------------------------
			$len_tmp = strlen($sql_build);
			$v = $len_tmp - 2;
			$sql_build = substr($sql_build, 0, $v);
	
			$len_tmp = strlen($sql_update);
			$v = $len_tmp - 2;
			$sql_update = substr($sql_update, 0, $v);
			
			$sql_build = str_replace(":semi:", ";", $sql_build);
	
			// ------------------------------------------------------------------------------------------
			// Insert CSV data into table and assume first row contains field names in CSV
			// ------------------------------------------------------------------------------------------
			if ($row != 1 && $key_active == "no") {
				mysql_query("INSERT INTO $TABLE_NAME VALUES($sql_build)");
			 	$true_import_count++;
			}
	
			if ($row != 1 && $key_active == "yes") {
				$test = mysql_query("SELECT $THIS_KEY_FIELD FROM $TABLE_NAME WHERE $THIS_KEY_FIELD = '$check_key_value'");
				$exist_flag = mysql_num_rows($test);
	
				if ($exist_flag != 0) {
					mysql_query("UPDATE $TABLE_NAME SET $sql_update WHERE $THIS_KEY_FIELD = '$check_key_value'");
					$modification_count++;
				} else {
					mysql_query("INSERT INTO $TABLE_NAME VALUES($sql_build)");
			 		$true_import_count++;
				}
	
			} // End Key Active Check
	
	
		} // End While Loop
	
		fclose ($fp);
	
	//	echo "<div style=\"font: 10px verdana;width: 700px;height: 400px;border: 1px solid red;overflow: scroll;\">".$testouput."</div>"; exit;
	
		// ------------------------------------------------------------
		// IMPORT COMPLETED!  DISPLAY THE NUMBER OF RECORDS IMPORTED
		// ------------------------------------------------------------
	
		$THIS_DISPLAY .= "<form method=\"post\" ACTION=\"".$simple_name."?mysqlmode=downloaddata\">\n";
		$THIS_DISPLAY .= "<input type=\"hidden\" name=\"action\" value=\"\">\n";
		$THIS_DISPLAY .= "<table border=\"0\" cellpadding=6 cellspacing=\"0\" width=95% class=\"text\" bgcolor=#708090 style='BORDER: 1px inset ".$bgcolor.";'>\n";
		$THIS_DISPLAY .= "<tr>\n";
		$THIS_DISPLAY .= "<td align=\"left\" valign=\"middle\" class=\"text\">\n";
		$THIS_DISPLAY .= "<font style='font-family: Arial; font-size: 9pt; color: white;'><b>".lang("IMPORT OF CSV DATA TO")." \"$TABLE_NAME\" ".lang("COMPLETE!")."</font>\n";
		$THIS_DISPLAY .= "</td></tr><tr><td align=\"center\" valign=\"top\" bgcolor=WHITE class=\"text\"><BR>\n";
	
		if ($true_import_count != 0) {
			$THIS_DISPLAY .= "<font FACE=VERDANA SIZE=2><b>[ $true_import_count ] ".lang("Records imported successfully").".</font><BR>\n";
		}
	
		if ($modification_count != 0) {
			$THIS_DISPLAY .= "<font FACE=VERDANA SIZE=2><b>[ $modification_count ] ".lang("Records were modified").".</font><BR>\n";
		}
	
		$THIS_DISPLAY .= "<BR><BR><BR><input TYPE=SUBMIT value=\" ".lang("View all Tables")." \" class=FormLt1></DIV>\n\n";
	
		$THIS_DISPLAY .= "</td></tr></table></form>\n";
	
	} // End Import Step 3
	
	#######################################################
	### READ ALL CURRENT DATABASE TABLES INTO MEMORY    ###
	#######################################################
	
	$result = mysql_list_tables("$db_name");
	echo mysql_error();
	$i = 0;
	
	# Store in separate arrays so they can be organized accordingly when displayed
	$udt_tables = array();
	$system_tables = array();
	
	while ($i < mysql_num_rows ($result)) {
	   $tb_names[$i] = mysql_tablename ($result, $i);
	 //  $display = strtoupper($tb_names[$i]);
	   $display = $tb_names[$i];
	   $tb_names[$i] = "$display~~~$tb_names[$i]";
	
	   if ( eregi("^UDT_", $tb_names[$i]) ) {
	      $udt_tables[] = $tb_names[$i];
	   } else {
	      $system_tables[] = $tb_names[$i];
	   }
	
	   $i++;
	}
	
	usort($tb_names, "strnatcasecmp");
	usort($udt_tables, "strnatcasecmp");
	usort($system_tables, "strnatcasecmp");
	//sort($tb_names);
	//sort($udt_tables);
	//sort($system_tables);
	
	#######################################################
	### START HTML/JAVASCRIPT CODE					    ###
	#######################################################
	
	$MOD_TITLE = "<a href=\"".$simple_name."?mysqlmode=downloaddata\" class=\"white noline\">".lang("Manage/Backup Site Data Tables")."</a>";
	
	if ( $action == "view" || $action == "concise" ) {
	   $title_tag = $table;
	} else {
	   $title_tag = lang("View All Data Tables");
	}
	
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
	
	function SV2_popupMsg(msg) { //v1.0
	  alert(msg);
	}
	function SV2_openBrWindow(theURL,winName,features) { //v2.0
	  window.open(theURL,winName,features);
	}
	
	var p = "File Manager";
	//parent.frames.footer.setPage(p);
	
	function build() {
		show_hide_layer('LOAD_LAYER','','show');
		show_hide_layer('userOpsLayer','','hide');
	}
	
	function load_complete() {
		show_hide_layer('LOAD_LAYER','','hide');
		show_hide_layer('userOpsLayer','','show');
	}
	
	function cancel() {
		show_hide_layer('LOAD_LAYER','','show');
		show_hide_layer('userOpsLayer','','hide');		
		window.location = '<?php echo $simple_name; ?>?mysqlmode=downloaddata&action=&<?=SID?>';
	}
	
	function drop_table(table_to_drop){
	          var tinyz = window.confirm('You have selected to delete the '+table_to_drop+' table.\nYou will not be able to undo this choice.\n\nDo you wish to continue with this action?');
	          if (tinyz != false) {
	               window.location = "simple.php?mysqlmode=delete_table&mt="+table_to_drop+"";
	          }
	}
	
	//-->
	</script>
	
	<style>
	.tab-off, .tab-on {
	   text-align: center;
	   width: 125px;
	   height: 18px;
	   vertical-align: top;
	   padding: 5px 10px;
	   background-color: #efefef;
	   border: 1px solid #ccc;
	   border-top: 3px solid #ccc;
	   border-bottom: 0;
	   color: #595959;
	   cursor: pointer;
	}
	
	.tab-on {
	   color: #000;
	   background-color: #efefef;
	   border-top: 3px solid #175aaa;
	   font-weight: bold;
	}
	</style>
	
	<?php
	
	/*---------------------------------------------------------------------------------------------------------*
	__      __ _                          _  _    _          _      _
	\ \    / /(_)                        | || |  | |        | |    | |
	 \ \  / /  _   ___ __      __   __ _ | || |  | |_  __ _ | |__  | |  ___  ___
	  \ \/ /  | | / _ \\ \ /\ / /  / _` || || |  | __|/ _` || '_ \ | | / _ \/ __|
	   \  /   | ||  __/ \ V  V /  | (_| || || |  | |_| (_| || |_) || ||  __/\__ \
	    \/    |_| \___|  \_/\_/    \__,_||_||_|   \__|\__,_||_.__/ |_| \___||___/
	
	
	# List all database tables with action links
	/*---------------------------------------------------------------------------------------------------------*/
	if ( $action == "" ) {
	
	   /*------------------------------------*
	    _____       _
	   |_   _|__ _ | |__  ___
	     | | / _` || '_ \(_-<
	     |_| \__,_||_.__//__/
	   /*------------------------------------*/
	   $THIS_DISPLAY .= " <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"text\" width=\"100%\">";
	   $THIS_DISPLAY .= "  <tr>\n";
	
	   # Recent tables tab?
	   if ( count($_SESSION['recent_tables']) > 0 ) {
	      # Add js to account for this tab to other tabs' onclick values
	      $tab_recent_js = "hideid('recent_table_list');setClass('tab-recent', 'tab-off');";
	
	      # recent tab gets default status if it exists
	      $udt_display = "none";
	      $udt_onoff = "off";
	      $spacer_td = "3%";
	
	      # tab-recent
	      $THIS_DISPLAY .= "   <td id=\"tab-recent\" class=\"tab-on\" onclick=\"showid('recent_table_list');setClass('tab-recent', 'tab-on');hideid('udt_table_list');hideid('system_table_list');setClass('tab-system', 'tab-off');setClass('tab-udt', 'tab-off');\">\n";
	      $THIS_DISPLAY .= "    ".str_replace(" ", "&nbsp;", lang("Recent tables"))."\n";
	      $THIS_DISPLAY .= "   </td>\n";
	
	      $THIS_DISPLAY .= "   <td>&nbsp;</td>\n";
	   } else {
	      # tab-udt on by default if tab-recent not shown
	      $udt_display = "block";
	      $udt_onoff = "on";
	      $spacer_td = "18%";
	   }
	
	   # tab-udt
	   $THIS_DISPLAY .= "   <td id=\"tab-udt\" class=\"tab-".$udt_onoff."\" onclick=\"".$tab_recent_js."showid('udt_table_list');hideid('system_table_list');setClass('tab-system', 'tab-off');setClass('tab-udt', 'tab-on');\">\n";
	   $THIS_DISPLAY .= "    ".str_replace(" ", "&nbsp;", lang("User tables"))."\n";
	   $THIS_DISPLAY .= "   </td>\n";
	
	   $THIS_DISPLAY .= "   <td>&nbsp;</td>\n";
	
	   # tab-system
	   $THIS_DISPLAY .= "   <td id=\"tab-system\" class=\"tab-off\" onclick=\"".$tab_recent_js."showid('system_table_list');hideid('udt_table_list');setClass('tab-udt', 'tab-off');setClass('tab-system', 'tab-on');\">\n";
	   $THIS_DISPLAY .= "    ".str_replace(" ", "&nbsp;", lang("System tables"))."\n";
	   $THIS_DISPLAY .= "   </td>\n";
	
	   # Create search and delete options?
	
	      # btn-create_search
	      $THIS_DISPLAY .= "   <td style=\"padding-left: 10px;text-align: right;width: 100%;\">\n";
	//      $THIS_DISPLAY .= "    <a href=\"database_manager/create_table.php\" class=\"sav\">".lang("Create Table")."</a>\n";
	//	$THIS_DISPLAY .= "    | <a href=\"database_manager/create_and_import_db.php\" class=\"blue uline\">".lang("Create Table")." &amp; ".lang("Import CSV")."</a>\n";
	//	$THIS_DISPLAY .= "    | <a href=\"database_manager/delete_table.php\" class=\"del\">".lang("Delete Table")."</a><br/>\n";
	//      
	//      
	//      $THIS_DISPLAY .= "    <a href=\"database_manager/wizard_start.php\" class=\"sav\">".lang("Create Search Form")."</a>\n";
	//      $THIS_DISPLAY .= "    | <a href=\"database_manager/auth_users.php\" class=\"sav\">".lang("Batch Authenticate Users")."</a>\n";
	      $THIS_DISPLAY .= "   </td>\n";
	
	
	   $THIS_DISPLAY .= "  </tr>\n";
	   $THIS_DISPLAY .= " </table>\n";
	
	   /*---------------------------------------------------------------------------------------------------------*
	    ___                     _     _____       _     _
	   | _ \ ___  __  ___  _ _ | |_  |_   _|__ _ | |__ | | ___  ___
	   |   // -_)/ _|/ -_)| ' \|  _|   | | / _` || '_ \| |/ -_)(_-<
	   |_|_\\___|\__|\___||_||_|\__|   |_| \__,_||_.__/|_|\___|/__/
	
	   # Recently-accessed tables (if $_SESSION['recent_tables'] array is populated with something)
	   /*---------------------------------------------------------------------------------------------------------*/
		if ( count($_SESSION['recent_tables']) > 0 ) {
			ksort($_SESSION['recent_tables']);
	
			 # recent_table_list
			$THIS_DISPLAY .= " <div id=\"recent_table_list\" style=\"display: block;\">\n";
			$THIS_DISPLAY .= "  <table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" class=\"text\" width=\"100%\" style=\"border: 1px solid #ccc;\">";
			$THIS_DISPLAY .= "   <tr>\n";
			$THIS_DISPLAY .= "    <td colspan=\"5\" align=\"left\" valign=\"middle\" class=\"gray_33\" style=\"background-color: white;\">\n";
			$THIS_DISPLAY .= "     <p>For your convenience, this tab lists tables that you've recently accessed and/or modified. Note that these tables are also listed\n";
			$THIS_DISPLAY .= "     in their usual spot under the appropriate 'User tables' or 'System tables' tab.</p>\n";
			$THIS_DISPLAY .= "     <p><a href=\"".$simple_name."?mysqlmode=downloaddata&todo=clear_recent\">Clear recent table history</a> (harmless)</p>\n";
			$THIS_DISPLAY .= "    </td>\n";
			$THIS_DISPLAY .= "   </tr>\n";
	
	      foreach ( $_SESSION['recent_tables'] as $key=>$tablename ) {
	
	         if ( $bg == "bg_white" ) { $bg = "bg_gray_f8"; } else { $bg = "bg_white"; }
	
	         $import_ok = " | <a href=\"".$simple_name."?mysqlmode=downloaddata&action=import&table=".$tablename."&".SID."\" class=\"sav\">".lang("Import")."</a> ";
	         $empty_ok = "[ <a href=\"".$simple_name."?mysqlmode=downloaddata&action=empty&table=".$tablename."&".SID."\" class=\"del\">".lang("Empty")."</a>";
	
	         # Added for Multi-User Access Check
	         if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";".$tablename.";", $CUR_USER_ACCESS) ) {
	            $tdstyle =  "border-bottom: 1px dashed #ccc;";
	
	               $viewonclick = "document.location.href='".$simple_name."?mysqlmode=enter_edit&mt=".$tablename."&".SID."';";
	               $view_edit_link = lang("View")." / ".lang("Edit");
	               $modify_link = " | <a href=\"".$simple_name."?mysqlmode=modify_table&mt=".$tablename."\">".lang("Modify")."</a> ";	            	
	            	$delete_link = " | <a class=\"del\" onclick=\"drop_table('".$tablename."');\" href=\"#\">".lang("Delete")."</a> ";
	            	
	            $THIS_DISPLAY .= "    <tr class=\"".$bg."\" onmouseover=\"this.className='bg_yellow';\" onmouseout=\"this.className='".$bg."';\">\n";
	
	            # Table name
	            $THIS_DISPLAY .= "     <td style=\"cursor: default;".$tdstyle."\" align=\"left\" valign=\"middle\">\n";
	            $THIS_DISPLAY .= "      <b>".str_replace("UDT_", "<span class=\"gray_33 unbold\">UDT_</span><b>", $tablename)."</b></td>\n";
	
	            # View
	            $THIS_DISPLAY .= "     <td class=\"hand\" onclick=\"build();".$viewonclick."\" style=\"".$tdstyle."\" align=\"center\" valign=\"middle\" onmouseover=\"this.style.backgroundColor='#FFF66F';\" onmouseout=\"this.style.backgroundColor='transparent';\">\n";
	            $THIS_DISPLAY .= "      [ <span class=\"blue uline\">".$view_edit_link." ".lang("Records")."</span> ]</td>\n";
	
	            # Download
	            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">\n";
	            $THIS_DISPLAY .= "      [ <a href=\"dl_table_action.php?table=".$tablename."&".SID."\">".lang("Download")."</a>".$import_ok."]\n";
	            $THIS_DISPLAY .= "     </td>\n";
	
	            # Import
	//            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\"></td>\n";
	
	            # Empty
	            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$empty_ok."".$modify_link.$delete_link."]</td>\n";
	            $THIS_DISPLAY .= "    </tr>\n";
	
	         } // End if webmaster or authorized admin
	
	      } // End foreach loop through recent_tables
	   	$THIS_DISPLAY .= "   </table>\n\n";
	   	$THIS_DISPLAY .= "  </div>\n\n";
	   } // End if recent_tables > 0
	
	
	   /*---------------------------------------------------------------------------------------------------------*
	    _   _  ___  _____   _          _     _
	   | | | ||   \|_   _| | |_  __ _ | |__ | | ___  ___
	   | |_| || |) | | |   |  _|/ _` || '_ \| |/ -_)(_-<
	    \___/ |___/  |_|    \__|\__,_||_.__/|_|\___|/__/
	
	   # User Data Tables
	   /*---------------------------------------------------------------------------------------------------------*/
		# udt_table_list
		$THIS_DISPLAY .= " <div id=\"udt_table_list\" style=\"display: ".$udt_display.";\">\n";
	   $THIS_DISPLAY .= "  <table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" class=\"text\" width=\"100%\" style=\"border: 1px solid #ccc;\">";
	   $THIS_DISPLAY .= "   <tr>\n";
	   $THIS_DISPLAY .= "    <td colspan=\"5\" align=\"left\" valign=\"middle\" class=\"gray_33\">\n";
		$THIS_DISPLAY .= "     <p><b>U</b>ser <b>D</b>ata <b>T</b>ables are tables that you've created for your own uses either via the Database Table Manager feature\n";
		$THIS_DISPLAY .= "     or by dropping a web form on a page and telling it to store visitor-submited form data in a specified data table.</p>\n";
	   $THIS_DISPLAY .= "    </td>\n";
	   $THIS_DISPLAY .= "   </tr>\n";
	
	   for ( $x = 0; $x < count($udt_tables); $x++ ) {
	
	      if ( $bg == "bg_white" ) { $bg = "bg_gray_f8"; } else { $bg = "bg_white"; }
	      $this_data = split("~~~", $udt_tables[$x]);
	      $tablename = $this_data[1];
	
	      // ------------------------------------------------------------------------
	      // Do Not allow import or empty of specific "system" tables.
	      // Overwriting data in these table could crash the entire system
	      // ------------------------------------------------------------------------
	
	      # v4.9 r54 --- allow everybody to import/empty on all tables
	      $import_ok = " | <a href=\"".$simple_name."?mysqlmode=downloaddata&action=import&table=".$tablename."&".SID."\" class=\"sav\">".lang("Import")."</a> ";
	      $empty_ok = "[ <a href=\"".$simple_name."?mysqlmode=downloaddata&action=empty&table=".$tablename."&".SID."\" class=\"del\">".lang("Empty")."</a>";
	
	      // ------------------------------------------------------------------------
	
	      if (!eregi("CTEMP_", $this_data[0])) {
	
	         # Added for Multi-User Access Check
	         if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";$this_data[0];", $CUR_USER_ACCESS) ) {
	            $tdstyle =  "border-bottom: 1px dashed #ccc;";
	
	               $viewonclick = "document.location.href='".$simple_name."?mysqlmode=enter_edit&mt=".$tablename."&".SID."';";
	               $view_edit_link = lang("View")." / ".lang("Edit");
	               $modify_link = " | <a href=\"".$simple_name."?mysqlmode=modify_table&mt=".$tablename."\">".lang("Modify")."</a> ";	            	
	            	$delete_link = " | <a class=\"del\" onclick=\"drop_table('".$tablename."');\" href=\"#\">".lang("Delete")."</a> ";
	
	            $THIS_DISPLAY .= "    <tr class=\"".$bg."\" onmouseover=\"this.className='bg_yellow';\" onmouseout=\"this.className='".$bg."';\">\n";
	
	            # Table name
	            $THIS_DISPLAY .= "     <td style=\"cursor: default;".$tdstyle."\" align=\"left\" valign=\"middle\"><span class=\"gray_33\">".str_replace("UDT_", "UDT_</span><b>", $this_data[0])."</b></td>\n";
	
	            # View
	            $THIS_DISPLAY .= "     <td class=\"hand\" onclick=\"build();".$viewonclick."\" style=\"".$tdstyle."\" align=\"center\" valign=\"middle\" onmouseover=\"this.style.backgroundColor='#FFF66F';\" onmouseout=\"this.style.backgroundColor='transparent';\">\n";
	            $THIS_DISPLAY .= "      [ <span class=\"blue uline\">".$view_edit_link." ".lang("Records")."</span> ]</td>\n";
	
	            # Download
	            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">\n";
	            $THIS_DISPLAY .= "      [ <a href=\"dl_table_action.php?table=".$tablename."&".SID."\">".lang("Download")."</a>".$import_ok."]\n";
	            $THIS_DISPLAY .= "     </td>\n";
	
	//            # Import
	//            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$import_ok."</td>\n";
	
	            # Empty
	            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$empty_ok."".$modify_link.$delete_link."]</td>\n";
			  $THIS_DISPLAY .= "    </tr>\n";
	
	         } // End if webmaster or authorized admin
	
	      } // End if !eregi(CTEMP_, this_data)
	
	   } // End for loop through udt_tables
	
		$THIS_DISPLAY .= "   </table>\n\n";
		$THIS_DISPLAY .= "  </div>\n\n";
	
	
	   /*---------------------------------------------------------------------------------------------------------*
	    ___            _                 _____       _     _
	   / __| _  _  ___| |_  ___  _ __   |_   _|__ _ | |__ | | ___  ___
	   \__ \| || |(_-<|  _|/ -_)| '  \    | | / _` || '_ \| |/ -_)(_-<
	   |___/ \_, |/__/ \__|\___||_|_|_|   |_| \__,_||_.__/|_|\___|/__/
	         |__/
	   # User Data Tables
	   /*---------------------------------------------------------------------------------------------------------*/
		# system_table_list
		$THIS_DISPLAY .= " <div id=\"system_table_list\" style=\"display: none;\">\n";
	   $THIS_DISPLAY .= "  <table border=\"0\" cellpadding=\"8\" cellspacing=\"0\" class=\"text\" width=\"100%\" style=\"border: 1px solid #ccc;\">";
	   $THIS_DISPLAY .= "   <tr>\n";
	   $THIS_DISPLAY .= "    <td colspan=\"5\" align=\"left\" valign=\"middle\" class=\"gray_33\">\n";
		//$THIS_DISPLAY .= "     <p>System data tables</b> - \n";
		$THIS_DISPLAY .= "     <p><b class=\"red\">WARNING: Modify system tables at your own risk.</b> Generally, you shouldn't have to mess with these unless you're developing a custom php script or troubleshooting\n";
		$THIS_DISPLAY .= "     a standard feature that doesn't seem to be working correctly. </p>\n";
	   $THIS_DISPLAY .= "    </td>\n";
	   $THIS_DISPLAY .= "   </tr>\n";
	
	   for ( $x = 0; $x < count($system_tables); $x++ ) {
	
	      if ( $bg == "bg_white" ) { $bg = "bg_gray_f8"; } else { $bg = "bg_white"; }
	      $this_data = split("~~~", $system_tables[$x]);
	      $tablename = $this_data[1];
	
	      # v4.9 r54 --- allow everybody to import/empty on all tables
	      $import_ok = " | <a href=\"".$simple_name."?mysqlmode=downloaddata&action=import&table=".$tablename."&".SID."\" class=\"sav\">".lang("Import")."</a> ";
	      $empty_ok = "[ <a href=\"".$simple_name."?mysqlmode=downloaddata&action=empty&table=".$tablename."&".SID."\" class=\"del\">".lang("Empty")."</a>";
	
	
	         $viewonclick = "document.location.href='".$simple_name."?mysqlmode=enter_edit&mt=".$tablename."&".SID."';";
	         $view_edit_link = lang("View")." / ".lang("Edit");
	         $modify_link = " | <a href=\"".$simple_name."?mysqlmode=modify_table&mt=".$tablename."\">".lang("Modify")."</a> ";	            	
	         $delete_link = " | <a class=\"del\" onclick=\"drop_table('".$tablename."');\" href=\"#\">".lang("Delete")."</a> ";
	
	
	
	      if (!eregi("CTEMP_", $this_data[0])) {
	
	         # Added for Multi-User Access Check
	         if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";".$this_data[0].";", $CUR_USER_ACCESS)) {
	            $tdstyle =  "border-bottom: 1px dashed #ccc;";
	            $THIS_DISPLAY .= "    <tr class=\"".$bg."\" onmouseover=\"this.className='bg_yellow';\" onmouseout=\"this.className='".$bg."';\">\n";
	            $THIS_DISPLAY .= "     <td style=\"cursor: default;".$tdstyle."\" align=\"left\" valign=\"middle\"><b>".$this_data[1]."</b></td>\n";
	
	            # View
	            $THIS_DISPLAY .= "     <td class=\"hand\" onclick=\"build();".$viewonclick."\" style=\"".$tdstyle."\" align=\"center\" valign=\"middle\" onmouseover=\"this.style.backgroundColor='#FFF66F';\" onmouseout=\"this.style.backgroundColor='transparent';\">\n";
	            $THIS_DISPLAY .= "      [ <span class=\"blue uline\">".$view_edit_link." ".lang("Records")."</span> ]</td>\n";
	
	            # Download
	            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">\n";
	            $THIS_DISPLAY .= "      [ <a href=\"dl_table_action.php?table=".$tablename."&".SID."\">".lang("Download")."</a>".$import_ok."]\n";
	            $THIS_DISPLAY .= "     </td>\n";
	
	//            # Import
	//            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$import_ok."</td>\n";
	
	            # Empty
	            $THIS_DISPLAY .= "     <td style=\"".$tdstyle."\" align=\"center\" valign=\"middle\">".$empty_ok."".$modify_link.$delete_link."]</td>\n";
	            $THIS_DISPLAY .= "    </tr>\n";
	
	         } // End if webmaster or authorized admin
	
	      } // End if !eregi(CTEMP_, this_data)
	
	   } // End for loop through system_tables
		$THIS_DISPLAY .= "   </table>\n\n";
		$THIS_DISPLAY .= "</div>\n\n";
	
	
	} // End if $action == ""
	
	
	
	
	/*---------------------------------------------------------------------------------------------------------*
	__   __ _                _____       _     _
	\ \ / /(_) ___ __ __ __ |_   _|__ _ | |__ | | ___
	 \ V / | |/ -_)\ V  V /   | | / _` || '_ \| |/ -_)
	  \_/  |_|\___| \_/\_/    |_| \__,_||_.__/|_|\___|
	
	# If user has selected to view this table; display data dump now
	/*---------------------------------------------------------------------------------------------------------*/
	if ( $action == "view" || $action == "concise" ) {
	
	   # Log in recent table list for quick links elsewhere
	   $_SESSION['recent_tables'][strtolower($_GET['table'])] = $_GET['table']; // strtolower necc for ksort()
	
		# Increase num shown for concise view (Mantis #251)
		if ( $action == "concise" ) {
		   $num_to_show = 50;
		} else {
		   $num_to_show = 10;
		}
	
		if ($s == "") { $s = 0; }
	
		$THIS_DISPLAY .= "<div align=\"left\"><b><font FACE=VERDANA SIZE=2>".lang("Database table")." '".$table."':</font>\n";
	
		$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href=\"".$simple_name."?mysqlmode=downloaddata&=SID\">".lang("View All Data Tables")."</a> ]\n\n";
	
		if ( $action == "view" ) {
		   $THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href=\"".$simple_name."?mysqlmode=downloaddata&table=$table&action=concise&=SID\">".lang("Concise View")."</a> ]<BR><BR>\n\n";
		} else {
		   $THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href=\"".$simple_name."?mysqlmode=downloaddata&table=$table&action=view&=SID\">".lang("Default View")."</a> ]<BR><BR>\n\n";
		}
	
		$result = mysql_query("SELECT * FROM $table LIMIT $s,$num_to_show");
		$numberRows = mysql_num_rows($result);
		$numberFields = mysql_num_fields($result);
		$numberFields--;
	
		$THIS_DISPLAY .= "</DIV><table border=1 cellpadding=4 cellspacing=\"0\" class=\"text\" align=\"left\">\n<tr>\n\n";
	
		for ($x=0;$x<=$numberFields;$x++) {
			$fieldname[$x] = mysql_field_name($result, $x);
	
			// I need to know the case_sensative normanclature of the field names for dev purposes!
			// $fieldname[$x] = strtoupper($fieldname[$x]);
			$THIS_DISPLAY .= "<td class=\"col_title\" align=\"center\" valign=\"top\">$fieldname[$x]</td>\n";
		}
	
		$THIS_DISPLAY .= "\n</tr>\n\n<tr>\n\n";
	
		for ($x=0;$x<=$numberFields;$x++) {
			$fieldtype[$x] = mysql_field_type($result, $x);
			// $fieldtype[$x] = strtoupper($fieldtype[$x]);
			$THIS_DISPLAY .= "<td class=\"col_sub\" align=\"center\" valign=\"top\"><font COLOR=#999999>[$fieldtype[$x]]</font></td>\n";
	
		}
	
		$THIS_DISPLAY .= "\n</tr>\n";
	
		while ($row = mysql_fetch_array ($result)) {
	
			$THIS_DISPLAY .= "\n<tr>\n";
			if ($ALT == "#EFEFEF") { $ALT = "white"; } else { $ALT = "#EFEFEF"; }
	
			for ($x=0;$x<=$numberFields;$x++) {
	
				$tmp = $row[$x];
				if ($tmp == "" || $tmp == "NULL") { $tmp = "&nbsp;"; }
	
				# Hide blog fields if concise view (Mantis #251)
				if ( $action == "concise" ) {
				   if (strtoupper($fieldtype[$x]) == "BLOB") { $tmp = "[BLOB]"; }
				}
	
				if ($tmp != "&nbsp;" && !eregi("&lt;", $tmp) && !eregi("&gt;", $tmp) && !eregi("&amp;", $tmp)) { $tmp = htmlspecialchars($tmp); }	// Bugzilla #12 -- Added special chars reverse check for Bugzilla #31
	
				$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\" bgcolor=$ALT>$tmp</td>\n";
	
			}
	
			$THIS_DISPLAY .= "\n</tr>\n";
	
		}
	
		$THIS_DISPLAY .= "\n</table>\n\n";
	
	
		$THIS_DISPLAY .= "<div align=\"left\"><BR CLEAR=ALL><BR><b><font FACE=VERDANA SIZE=2>\n\n";
	
		# Previous 10
		if ($s != 0) {
			$newstart = $s-$num_to_show;
			$THIS_DISPLAY .= "[ <a href=\"".$simple_name."?mysqlmode=downloaddata&action=$action&table=$table&s=$newstart\" onclick=\"build();\">Previous $num_to_show</a> ]";
		}
	
		# Next 10
		if ($numberRows == $num_to_show) {
			$newstart = $s+$num_to_show;
			$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[ <a href=\"".$simple_name."?mysqlmode=downloaddata&action=$action&s=$newstart&table=$table\" onclick=\"build();\">Next $num_to_show</a> ]\n";
		}
	
		$THIS_DISPLAY .= "</b></DIV>\n\n";
	
	}
	
	
	echo $THIS_DISPLAY;
	# Grab module html into container var
	$module_html = ob_get_contents();
	ob_end_clean();
	
	echo $module_html;
###############END INCLUDE DOWNLOAD DATA .php	
	}
	//echo $module_html;

	//echo mysql_error();
	echo "</div></div>\n";
} else {  
	echo "<font color=white>";
	if($_SESSION['exectype'] != 'exec') {

		if($_SESSION['win']=='yes' && strtoupper($SPLIT['0']) == 'FIND'){
			$win_explode = explode('"', $find_orig);
			$searchterm = $win_explode['1'];
			unset($_SESSION['found_files']);
			$_SESSION['found_files'] = array();
			function win_search($path, $searchterm){
			  $thisdirz = getcwd();
			 // echo $path;	
			//	foreach (glob($path.'/*') as $filez) {
				foreach (array_merge(glob($path.DIRECTORY_SEPARATOR.'*'),glob($path.DIRECTORY_SEPARATOR.'.*')) as $filez) {	
					$stripped = str_replace($thisdirz.'\\', '', $filez);		
					$stripped = eregi_replace('^\.', '', $stripped);		

					if(!is_dir($stripped)) {			
						if(!eregi('\.gif$', $stripped) && $stripped != '.' && $stripped != '..' && !eregi('\.gz$', $stripped) && !eregi('\.tar$', $stripped) && !eregi('\.zip$', $stripped) && !eregi('\.tgz$', $stripped) && !eregi('\.png$', $stripped) && !eregi('\.rm$', $stripped) && !eregi('\.avi$', $stripped) && !eregi('\.mpg$', $stripped) && !eregi('\.mpeg$', $stripped) && !eregi('\.mov$', $stripped) && !eregi('\.jpg$', $stripped) && !eregi('\.tif$', $stripped) && !eregi('\.sql$', $stripped) && !eregi('\.jpeg$', $stripped) && !eregi('\.css$', $stripped) && !eregi('\.psd$', $stripped) && !eregi('\.bmp$', $stripped) && !eregi('\.ttf$', $stripped) && !eregi('\.swf$', $stripped) && !eregi('\.flv$', $stripped) && !eregi('\.doc$', $stripped) && !eregi('\.csv$', $stripped) && !eregi('\.pdf$', $stripped)) {
							$phppage = $stripped;				
							$filesizephp = filesize($phppage);

							if($filesizephp < '300000') {				
								$fileophp = fopen($phppage, "r");
								$phpcontent = fread($fileophp, $filesizephp);
								fclose($fileophp);					


								if(eregi($searchterm, $phpcontent)){
									$_SESSION['found_files'][] = getcwd().'\\'.$phppage;
								}
																
							} 
						}			
					} else {					
						if(!eregi('\\\.$', $stripped) && !eregi('\\\.\.$', $stripped)){
							//echo $stripped."<br/>";
							win_search($filez, $searchterm);
						}
					}
				}
			}
			
			win_search(getcwd(), $searchterm);
			$output = 'Found '.$searchterm.' '.count($_SESSION['found_files'])." times.\n";
			$xo = 1;
			foreach($_SESSION['found_files'] as $val){
				//$output .= 'Found '.$searchterm.' in '.$val."\n";
				$val = str_replace('\\', '\\\\', $val);
				$output .= $xo.") <span class=\"filesearch\" style=\" cursor:pointer;\" onclick=\"pncmd2('edit ".$val."'); this.style.color='red'\">".$val."</span>&nbsp;&nbsp;<font color=white>".date("m/d/Y g:ia", filemtime($val))."</font>\n";
				++$xo;
			}
			
		} else {
			
			$output = shell_exec($_POST['cmd']);
			if(strtoupper($SPLIT['0']) == 'FIND'){
			  $_POST['cmd'] = $find_orig;
			  $findout_ar = explode("\n", $output);
			  $findout = "Found ".(count($findout_ar) - 1)." matching files.\n";
			  $ollyc = 0;
			  foreach($findout_ar as $var=>$val){
			    if($val != ""){               
			      ++$ollyc;
			      $findout .= $ollyc.") <span class=\"filesearch\" style=\" cursor:pointer;\" onclick=\"pncmd2('edit ".$val."'); this.style.color='red'\">".$val."</span>&nbsp;&nbsp;<font color=white>".date("m/d/Y g:ia", filemtime($val))."</font>\n";
			      //$findout .= $ollyc.") <span style=\"cursor:pointer; color:orange;\" onmouseover=\"this.style.color='red'\" onMouseOut=\"this.style.color='orange;'\" onclick=\"pncmd2('edit ".$val."'); this.style.color='red'\">".$val."</span>\n";
			    }
			  }
			  $findout .= "\n";
			  $output = $findout;
			}
		}

      
      if($output == '') {
         //$output = shell_exec('ls -Al');


					echo "<script type=\"text/javascript\">\n";
					echo "window.onload=function(){\n";
					echo "if(!NiftyCheck())\n";
					echo "    return;\n";
					echo "	Rounded(\"div#filezlist\",\"#808080\",\"".$bgcolor."\");\n";
					echo "	document.exec.cmd.focus();\n";
					echo "}\n";
					echo "</script>\n";

         echo "<div id=\"filezlist\" style=\"background-color:".$bgcolor."; width:98%; height:88%;\">\n";
         echo "<div id=\"filezlistcon\" style=\"background: ".$bgcolor." url('http://securexfer.net/camerons_simple/Mitch-simple.jpg') no-repeat fixed bottom right; width:99%; height:98%; overflow:auto; padding:3px 0px 3px 3px;\">\n";
         echo sortls();        
         echo "</div></div>";
      } else {
         if($_POST['cmd']=='ls -Al') {
            //echo $output;
            //echo sortls();   
         } else {
            //echo $output;
         }
      }
	} else {
		$output = exec($_POST['cmd']); 
		if($output == '') {
			echo "<script type=\"text/javascript\">\n";
			echo "window.onload=function(){\n";
			echo "if(!NiftyCheck())\n";
			echo "    return;\n";
			echo "	Rounded(\"div#filezlist\",\"#808080\",\"".$bgcolor."\");\n";
			echo "	document.exec.cmd.focus();\n";
			echo "}\n";
			echo "</script>\n";
			echo "<div id=\"filezlist\" style=\"background-color:".$bgcolor."; width:98%; height:88%;\">\n";
			echo "<div id=\"filezlistcon\" style=\"background: ".$bgcolor." url('http://securexfer.net/camerons_simple/Mitch-simple.jpg') no-repeat fixed bottom right; width:99%; height:98%; overflow:auto; padding:3px 0px 3px 3px;\">\n";
			echo sortls();        
			echo "</div></div>";
			
		}
	}
  

  $ootp = $_POST['cmd']."\n";
	if($output != ''){
		$ootp .= $output;
	}
	$ootp = explode("\n", $ootp);

	$oh_c = explode("\n", $_SESSION['output_history']);
	$oh_cx = 0;

	$oh_c = array_merge($oh_c,$ootp);
	krsort($oh_c);
	$oooo = '';
	foreach($oh_c as $val){
		if($oh_cx < 900){
			$oooo[] = $val;
		}
		$oh_cx++;
	}
	krsort($oooo);
	$_SESSION['output_history'] = implode("\n", $oooo);
	$_SESSION['output_history'] = eregi_replace("^(\n)+", '', $_SESSION['output_history']);
	$_SESSION['output_history'] = eregi_replace("\n$", '', $_SESSION['output_history']);
	$_SESSION['output_history'] = eregi_replace("\n\n\n", "\n\n", $_SESSION['output_history']);
		
	if($_SESSION['output_history'] != '' && $output != ''){
		
		echo "<script type=\"text/javascript\">\n";
		echo "window.onload=function(){\n";
		echo "if(!NiftyCheck())\n";
		echo "    return;\n";
		echo "	Rounded(\"div#scrolly1\",\"#808080\",\"".$bgcolor."\");\n";
		echo "	document.exec.cmd.focus();\n";
		echo "}\n";
		echo "</script>\n";
		echo "<div id=\"scrolly1\" style=\"background-color:".$bgcolor."; width:98%; height:88%;\">\n";
		echo "<div id=\"scrolly\" style=\"font-size: 8pt; background: ".$bgcolor." url('http://securexfer.net/camerons_simple/Mitch-simple.jpg') no-repeat fixed bottom right; width:99%; height:98%; overflow:auto; padding:3px 0px 3px 3px;\">\n";
		echo "<pre>";
		echo $_SESSION['output_history'];
		echo "</pre>\n";
		echo "</div>\n";
	
		echo "</div>\n";
		echo "<div style=\"overflow:hidden; padding: 4px 0px 0px 0px;\">\n";
		echo "<button class=\"nav_logout\" onMouseover=\"this.className='nav_logouton';\" onMouseout=\"this.className='nav_logout';\" style=\"font-size: 9px; border:0px solid; color:white;\" onClick=\"pncmd('CLEAR_HISTORY')\";>CLEAR HISTORY</button>\n";
		echo "</div>\n";
		echo "<script language=javascript>\n";
		echo "var mydiv = document.getElementById(\"scrolly\");\n";
		echo "mydiv.scrollTop = mydiv.scrollHeight - mydiv.clientHeight;\n";
		echo "</script>\n";
		echo "</font>";
	
	}	else  {
		echo "</font>";
	}

}

echo "</font> \n";
echo "</div>\n";
echo "</div>\n";
echo "</body> \n";
echo "</html> \n";
?>