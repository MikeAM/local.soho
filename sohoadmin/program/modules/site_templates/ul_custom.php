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
error_reporting(E_PARSE);
session_start();

require_once('../../includes/product_gui.php');
$OS = strtoupper(PHP_OS);

$updateprefs = new userdata("updateprefs");

$pages = $doc_root."/sohoadmin/program/modules/site_templates/pages";

	chdir($doc_root."/sohoadmin/program/modules/site_templates");
	if (!is_dir("pages")) {
		if (!mkdir("pages", 0755)) {
			echo "Can't create ".$pages." folder.  Chmod the /sohoadmin/program/modules/site_templates folder to 777";
		}
	}

	chdir($pages);

	if (!is_dir("tmp") ) {
		mkdir("tmp", 0755);
	} else {
		rmdirr($pages."/tmp");
		mkdir("tmp", 0755);
	}
	function scandirs($directory){
		foreach (GLOB($directory."/*") as $file) {
			if (is_dir($file)) {
				$file = eregi_replace($directory."/", '', $file);
				$files .= $file.";";
			}
		}
		$files = split(';', $files);
		return($files);
	}

chdir($pages."/tmp");
$this_zip = $_FILES['FILE1']['name'];
$this_zip = eregi_replace(" ", "_", $this_zip);

if (move_uploaded_file($_FILES['FILE1']['tmp_name'], $this_zip)) {
	chmod($this_zip, 0755);
//  if ( eregi("WIN", $OS) ) {
//		if(!file_exists("../unzips/unzip.exe")){
//			mkdir("..\unzips", 0755);
//			$sshRez .= exec("expand -r -F:*.* ..\..\unzip.cab ..\unzips\ ");
//		}
//		$sshRez = exec("..\unzips\unzip -o -L $this_zip");
//	} else {
//		$sshRez = shell_exec("unzip -o $this_zip");
//		$sshRez .= exec("unzip -o $this_zip");
//	}
	$sshRez = unZip($this_zip);
	$dirs = scandirs($doc_root."/sohoadmin/program/modules/site_templates/pages/tmp");
	$fdrName = $dirs[0];

	if ($fdrName != '') {
		chdir($pages);
		if(is_dir($fdrName)) {
			if ( !rmdirr($pages."/".$fdrName) ) {
				echo "2) Can't overwrite to template folder!  Chmod the ".$Pages."/".$fdrName." to 777";
			}
		}
		copy($pages."/tmp/".$this_zip, $pages."/".$this_zip);
//		if ( eregi("WIN", $OS) ) {
//			$sshRez = exec("unzips\unzip -o -L $this_zip");
//		} else {
//			$sshRez = exec("unzip -o $this_zip");
//			$sshRez .= shell_exec("unzip -o $this_zip");
//		}
		$sshRez = unZip($this_zip);
		if (eregi(' ', $fdrName)) {
			rename($fdrName, eregi_replace(' ', '_', $fdrName));
		}
		unlink($pages."/".$this_zip);

	} else {

		$fdrName = eregi_replace(".zip", "", $this_zip);
		chdir($pages);
		if(is_dir($fdrName)) {
			if ( !rmdirr($pages."/".$fdrName) ) {
				echo "2) Can't overwrite to template folder!  Chmod the ".$Pages."/".$fdrName." to 777";
			}
		}
	mkdir($fdrName, 0755);
	chdir($pages."/".$fdrName);
	copy($pages."/tmp/".$this_zip, $pages."/".$fdrName."/".$this_zip);
//	if ( eregi("WIN", $OS) ) {
//		$sshRez = exec("..\unzips\unzip -o -L $this_zip");
//   } else {
//		$sshRez = exec("unzip -o $this_zip");
//		$sshRez = shell_exec("unzip -o $this_zip");
//   }
	$sshRez = unZip($this_zip);
	unlink($this_zip);
 }
	chdir($doc_root."/sohoadmin/program/modules/site_templates");
	rmdirr($doc_root."/sohoadmin/program/modules/site_templates/pages/tmp");

eval(hook("ul_custom.php:before-permissions"));

	# Set permissions on sohoadmin to something aggreeable?
	if ( $updateprefs->get("chmod_after") == "yes" && !php_suexec() ) {
	   shell_exec("chmod -R 0777 ".$_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages");
	   exec("chmod -R 0777 ".$_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages");
	}

	//$showTab = $_REQUEST['showTab'];
	header ("Location: ../site_templates.php?success=1&=SID");
	exit;
} else {
	header ("Location: ../site_templates.php?success=0&=SID");
	exit;
}



?>