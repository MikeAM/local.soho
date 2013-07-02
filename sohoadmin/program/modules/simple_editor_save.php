<?php
error_reporting(E_PARSE && E_ERROR);
ini_set("max_execution_time", "99");
ini_set("default_socket_timeout", "99");
ini_set("max_post_size", "200M");
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
# Include essential interface files
if(!include_once("../includes/product_gui.php")){ exit; }
//echo testArray($_POST);

if($_POST['realcontent'] != '') {

	$savefile = $_SESSION['doc_root'].'/media/'.basename($_POST['file']);
	$savecontent = str_replace(' ', '+', $_POST['realcontent']);
	$savecontent = base64_decode($savecontent);

	$filesave = fopen($savefile, "w+");		
	fwrite($filesave, $savecontent);

	fclose($filesave);
} 


?>
