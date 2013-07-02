<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
include("../../includes/product_gui.php");

foreach (glob($_SESSION['doc_root'].'/media/*') as $filename) {      
	if(is_file($_SESSION['doc_root'].'/media/'.basename($filename))){
		if(!eregi('\.(inc|php|swf|bak)$', basename($filename))){
			$filename = str_replace("'", "\'", $filename);
			$filename = basename($filename);
			$DISF_ARRAYz[] = $filename;
		}
	}
}

natcasesort($DISF_ARRAYz);

foreach($DISF_ARRAYz as $fixem){
	$ftype = eregi_replace('[^\.]*\.', '', $fixem);
	$ftype = eregi_replace('[^\.]*\.', '', $ftype);
	$efilearr[strtolower($ftype)][]=$fixem;
}
//natcasesort($efilearr);
uksort($efilearr, "strnatcasecmp");
//echo testArray($efilearr);
// BUILD LINK LIST
$jsdisp = "var tinyMCEMediaList = new Array( \n";
foreach($efilearr as $DISF_ARRAY=>$DISF_ARRAYv){
	foreach($DISF_ARRAYv as $fvalz){
//		$jsdisp .= "[\"".basename($fvalz)."\", \"media/".basename($fvalz)."\"],\n";
		$jsdisp .= "[\"".basename($fvalz)."\", \"pgm-download_media.php?name=".basename($fvalz)."\"],\n";
		
	}
	
}
$jsdisp = eregi_replace("\,\n$", '', $jsdisp);
echo $jsdisp .= "); \n";
?>