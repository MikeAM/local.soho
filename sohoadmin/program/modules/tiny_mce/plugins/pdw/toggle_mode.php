<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once("../../../../includes/product_gui.php");
chdir($curdir);

if($_GET['tinymode']==''){
	exit;	
}

if ( !is_object($tinymce_prefs) ) {
	$tinymce_prefs = new userdata('global');
}
if($_GET['tinymode']=='basic'){
	$tinymce_prefs->set('tinymode', 'basic');
} elseif($_GET['tinymode']=='advanced'){
	$tinymce_prefs->set('tinymode', 'advanced');	
}

?>