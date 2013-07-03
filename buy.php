<?php
error_reporting(E_PARSE);
session_start();
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_REQUEST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");
$pagefile = __FILE__;
if($pagefile == ''){
	$pagefile = $_SERVER['SCRIPT_FILENAME'];
}
$pagefile = preg_replace('/\.php$/i', '', basename($pagefile));
$pagetitle = str_replace( "_", " ", $pagefile);
$secure_setting = mysql_query("select username from site_pages where page_name = '$pagetitle'");
$secure_name = mysql_fetch_array($secure_setting);
if (!isset($secure_name['username']) or ($secure_name['username'] == "")) {
	$pr = $pagefile;
	$_REQUEST['pr'] = $pagefile;
	$_GET['pr'] = $pagefile;
	$_POST['pr'] = $pagefile;
	$pageRequest = $pagefile;
	include("index.php");
} else { 
	$pr = $pagefile;
	$_REQUEST['pr'] = $pagefile;
	$_GET['pr'] = $pagefile;
	$_POST['pr'] = $pagefile;
	$pageRequest = $pagefile;
	include("index.php");
}
exit;
?>
