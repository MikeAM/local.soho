<?php
error_reporting(E_PARSE);
require_once('product_gui.php');
session_start();

$global_admin_prefs = new userdata('admin');

if(!is_array($_SESSION['nav_heading_array'])){
	$headingArr = $global_admin_prefs->get('nav_heading_array');
	$_SESSION['nav_heading_array'] = $nav_heading_array;
} else {
	$headingArr = $_SESSION['nav_heading_array'];
}

if ( !is_array($headingArr) || count($headingArr) < 1 ) {
	$headingArr = array();
}

$default = 'hide';
$other_way = 'show';

if ( $_GET['section_id'] != '' ) {
	# format
	if ( $_GET['show_or_hide'] == 'hide' ) { $show_or_hide = 'hide'; } else { $show_or_hide = 'show'; }
	$heading_id = str_replace('-heading', '', $_GET['section_id']);
	$headingArr[$heading_id] = $show_or_hide;
	//echo $heading_id.'='.$_GET['show_or_hide'].' | ';
	//echo $heading_id.'='.$show_or_hide;
	$global_admin_prefs->set('nav_heading_array', $headingArr);
	$_SESSION['nav_heading_array'] = $headingArr;
//	echo testArray($headingArr);
//	echo testArray($global_admin_prefs->get('nav_heading_array'));
} elseif ( $_GET['thing_id'] != '' ) {
	# Generic hide/show prefernce (not for nav, e.g., for wizard or somethign)
	if ( $_GET['show_or_hide'] == 'hide' ) { $show_or_hide = 'hide'; } else { $show_or_hide = 'show'; }
	$global_admin_prefs->set($_GET['thing_id'], $show_or_hide);
	$_SESSION[$_GET['thing_id']] = $show_or_hide;
	echo '<p>'.$_GET['thing_id'].'='.$show_or_hide.'</p>';

} else {
//	echo 'no heading id passed';
}
?>