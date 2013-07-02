<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../program/includes/product_gui.php');
chdir($curdir);


#================================================================================
# CREATE SYSTEM FOLDERS
# Checks for and creates (if not found) the directries /sohoadmin depends upon
# This script is included the first time a user logs-in on a fresh install
#================================================================================

$err = 0;
//$first_time = "no"; // Should be obsolete now

# Used to show action log when including this file from diagnostic screen in help center
$report = array();

# /filebin
$DIR = $_SESSION['docroot_path']."/sohoadmin/filebin";
if ( !is_dir($DIR) ) {
   $first_time = "yes"; // Denotes this is the first time product has been run
   if (!mkdir ($DIR, 0755)) { echo ("<font face=Tahoma size=2>Dir Creation Error: Error Creating <B>$DIR</b> directory<BR></font>"); $err=1; } else { $report[] = "Created ".basename($DIR); }
}

# /plugins
$DIR = $_SESSION['docroot_path']."/sohoadmin/plugins";
if ( !is_dir($DIR) ) {
   $first_time = "yes"; // Denotes this is the first time product has been run
   if (!mkdir ($DIR, 0755)) { echo ("<font face=Tahoma size=2>Dir Creation Error: Error Creating <B>$DIR</b> directory<BR></font>"); $err=1; } else { $report[] = "Created ".basename($DIR); }
}

# /tmp_content
$DIR = $_SESSION['docroot_path']."/sohoadmin/tmp_content";
if ( !is_dir($DIR) ) {
   $first_time = "yes"; // Denotes this is the first time product has been run
	if(!mkdir ($DIR, 0755)) { echo ("<font face=Tahoma size=2>Dir Creation Error: Error Creating <B>$DIR</b> directory<BR></font>"); $err=1; 
	} else {
		if(!file_exists($_SESSION['docroot_path'].'/sohoadmin/tmp_content/Home.regen')){
			copy($_SESSION['docroot_path'].'/sohoadmin/includes/default_content/Home.regen', $_SESSION['docroot_path'].'/sohoadmin/tmp_content/Home.regen');
			copy($_SESSION['docroot_path'].'/sohoadmin/includes/default_content/Home.con', $_SESSION['docroot_path'].'/sohoadmin/tmp_content/Home.con');
		}
		$report[] = "Created ".basename($DIR); 
	}
}

# /media
$DIR = $_SESSION['docroot_path']."/media";
if (!is_dir($DIR)) {
   $first_time = "yes"; // Denotes this is the first time product has been run
   if (!mkdir ($DIR, 0755)) { echo ("<font face=Tahoma size=2>Dir Creation Error: Error Creating <B>$DIR</b> directory<BR></font>"); $err=1; } else { $report[] = "Created ".basename($DIR); }
}

# images
$DIR = $_SESSION['docroot_path']."/images";
if (!is_dir($DIR)) {
   $first_time = "yes"; // Denotes this is the first time product has been run
   if (!mkdir ($DIR, 0755)) { echo ("<font face=Tahoma size=2>Dir Creation Error: Error Creating <B>$DIR</b> directory<BR></font>"); $err=1; } else { $report[] = "Created ".basename($DIR); }
   chmod ($DIR, 0755);
}


# tCustom
//$DIR = $_SESSION['docroot_path']."/tCustom";
//if (!is_dir($DIR)) {
//   $first_time = "yes"; // Denotes this is the first time product has been run
//   if (!mkdir ($DIR, 0755)) { echo ("<font face=Tahoma size=2>Dir Creation Error: Error Creating <B>$DIR</b> directory<BR></font>"); $err=1; } else { $report[] = "Created ".basename($DIR); }
//   chmod ($DIR, 0755);
//}

//# template
//$DIR = $_SESSION['docroot_path']."/template";
//if (!is_dir($DIR)) {
//   $first_time = "yes"; // Denotes this is the first time product has been run
//   if (!mkdir ($DIR, 0755)) { echo ("<font face=Tahoma size=2>Dir Creation Error: Error Creating <B>$DIR</b> directory<BR></font>"); $err=1; } else { $report[] = "Created ".basename($DIR); }
//   chmod ($DIR, 0755);
//}

if ($err == 1) {
	echo "<h4>\n";
	echo " <font color=red face=Tahoma><U>Possible Solution:</U></font>\n";
	echo " <BR><BR>\n";
	echo " <font size=2>\n";
	echo "  1. Change permissions on your document root directory (eg: CHMOD -R a+rw htdocs).</font>";
	echo "  <BR><BR>";
	echo "  2. Modify <U>sohoadmin/config/isp.conf.php</U> file for doc_root to equal <U>".$_SESSION['docroot_path']."</U>.\n";
	exit;
}



?>