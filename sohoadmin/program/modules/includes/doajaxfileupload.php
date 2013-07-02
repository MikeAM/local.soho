<?php

error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../includes/product_gui.php');
chdir($curdir);

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

$error = "";
$msg = "";
$fileElementName = 'fileToUpload';

$file_ext = strtolower(array_pop(explode('.', $_FILES['fileToUpload']['name'])));

if(!empty($_FILES[$fileElementName]['error'])){
	switch($_FILES[$fileElementName]['error']){
		case '1':
			$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case '2':
			$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			break;
		case '3':
			$error = 'The uploaded file was only partially uploaded';
			break;
		case '4':
			$error = 'No file was uploaded.';
			break;
		case '6':
			$error = 'Missing a temporary folder';
			break;
		case '7':
			$error = 'Failed to write file to disk';
			break;
		case '8':
			$error = 'File upload stopped by extension';
			break;
		case '999':
		default:
			$error = 'No error code avaiable';
	}
} elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none'){
	$error = 'No file was uploaded..';
} elseif($_REQUEST['father']=='cart_product' && !preg_match('/^(jpg|jpeg|tif|tiff|bmp|gif|png)$/i', strtolower($file_ext)) ){
	$error = 'Only bmp, gif, jpg, jpeg, tif, tiff, and png file types can be used as an image.';
} else {
		$_FILES['fileToUpload']['name'] = preg_replace('/[\/"\'\\\]/', '', str_replace(' ', '_', $_FILES['fileToUpload']['name']));
		$ext = strtolower(array_pop(explode('.', $_FILES['fileToUpload']['name'])));
		$msg .= $_FILES['fileToUpload']['name'];
//		$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";		
		//$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name'])." ".$ext;
		if(preg_match('/^(jpg|jpeg|tif|bmp|gif|png)$/i', strtolower($ext))){
			if(!copy($_FILES['fileToUpload']['tmp_name'], $_SESSION['doc_root'].'/images/'.$_FILES['fileToUpload']['name'])){
				$error = 'could not write new file '.$ext.' '.$_SESSION['doc_root'].'/images/'.$_FILES['fileToUpload']['name'].'.';	
			}
		} else {
			if(!copy($_FILES['fileToUpload']['tmp_name'], $_SESSION['doc_root'].'/media/'.$_FILES['fileToUpload']['name'])){
				$error = 'could not write new file. '.$ext;	
			}
		}		
		@unlink($_FILES['fileToUpload']['tmp_name']);
		//@unlink($_FILES['fileToUpload']);
}


echo "{";
echo				"error: '" . $error . "',\n";
echo				"msg: '" . $msg . "'\n";
echo "}";
?>