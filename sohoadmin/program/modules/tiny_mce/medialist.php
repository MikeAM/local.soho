<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
$filenames = '';
foreach (glob($_SESSION['doc_root'].'/media/*') as $filename) {
	$filenames .= basename($filename)."\n";
}
$filenames = eregi_replace("\n$", '', $filenames);
$imglist_AR = explode("\n", $filenames);
natcasesort($imglist_AR);



$jsdisp = "var tinyMCEMediaList = new Array( \n";
foreach($imglist_AR as $imgv=>$imgn) {
	//if(!eregi('\.inc$', $imgn) && !eregi('\.csv$', $imgn) && !eregi('\.txt$', $imgn) && !eregi('\.html$', $imgn) && !eregi('\.htm$', $imgn) && !eregi('\.php$', $imgn) && !eregi('\.pdf$', $imgn) && !eregi('\.zip$', $imgn) && !eregi('\.ppt$', $imgn) && !eregi('\.psd$', $imgn) && !eregi('\.doc$', $imgn) && !eregi('\.tgz$', $imgn) && !eregi('\.gz$', $imgn)){
	if(preg_match('/\.(avi|mov|m4v|ram|rm|swf|flv|mp3|mp4|mpeg|dcr|qt|mpg|wmv|wm|asf|asx|wmx|wvx|rmp|ra|wav|oog)$/i',$imgn)){
		$jsdisp .= "[\"".$imgn."\", \"media/".$imgn."\"],\n";
	}
}
$jsdisp = eregi_replace("\,\n$", '', $jsdisp);
echo $jsdisp .= "); \n";
?>