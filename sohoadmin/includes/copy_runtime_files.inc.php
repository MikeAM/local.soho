<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

##################################################
### COPY ALL BASE RUNTIME FILES TO USER        ###
### DIRECTORY FOR LATEST UPDATE OPERATION      ###
### v4.7 -- This now includes runtime.css      ###
##################################################
//$_SESSION['docroot_path'] = '/home/soho/product/pro/dev/ultra.soholaunch.com/htdocs';
//chdir($_SESSION['docroot_path'].'/sohoadmin');
session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../program/includes/product_gui.php');
chdir($curdir);

//$rr = fopen('client_files/base_files/robots.txt','r');
//$robots_txt = fread($rr,filesize('client_files/base_files/robots.txt'));
//fclose($rr);
//if(!preg_match('/Sitemap\:/i', $robots_txt)){
//	$rr = fopen('client_files/base_files/robots.txt','w');
//	$robots_txt.="\n".'Sitemap: http://'.$_SESSION['this_ip'].'/sitemap.xml.php';
//	fwrite($rr,$robots_txt);
//	fclose($rr);
//}
unlink('client_files/base_files/robots.txt');


# Preserve original working dir and force "sohoadmin" working dir
# b/c this the code in this file was originally cut form update_client.php and expects a working dir of "sohoadmin"
# but is now included in different places like software_updates.php and update_client.php)
$orig_dir = getcwd();
chdir($_SESSION['docroot_path'].'/sohoadmin');

$globalprefObj = new userdata('global');

$userdir = $_SESSION['docroot_path'];
$clientdir = "client_files/base_files";
$cn = 0; // counter

foreach(glob('client_files/base_files/*.*') as $cfile){
	$cfile = basename($cfile);

	if(!preg_match('/\.php$/i', $cfile)){
		@copy("$clientdir/$cfile", "../$cfile");
	} else {
		@copy("client_files/client_files_include_file.php", "../$cfile");
		//echo $cfile;
	}
}
//$rbs=fopen('../robots.txt','r');
//$robots=fread($rbs,filesize('../robots.txt'));
//fclose($rbs);
if(file_exists('client_files/robots.txt') && !file_exists('../robots.txt')){
	@copy('client_files/robots.txt', "../robots.txt");
}

$rbs=fopen('../robots.txt','r');
$robots=fread($rbs,filesize('../robots.txt'));
fclose($rbs);

if(preg_match('/#thisip#/',$robots) || preg_match('/ultra\.soholaunch\.com/',$robots)){
	$robots=str_replace('#thisip#',$_SESSION['this_ip'],$robots);
	$robots=str_replace('ultra.soholaunch.com',$_SESSION['this_ip'],$robots);	
	$rbs=fopen('../robots.txt','w');
	fwrite($rbs,$robots);
	fclose($rbs);
}


##################################################
### COPY INITIAL IMAGES TO IMAGES FOLDER	     ###
##################################################
$userdir = $_SESSION['docroot_path']."/images";
//@copy("icons/image_global.gif", "$userdir/image_global.gif");
//@copy("icons/image_paperwork.gif", "$userdir/image_paperwork.gif");
@copy("icons/sheepdog.jpg", "$userdir/sheepdog.jpg");




####################################################
### COPY BASE TEMPLATE TO SITE IF NOT ONE IN USE ###
####################################################
//$tmp_file = $_SESSION['docroot_path']."/sohoadmin/tmp_content/template.conf";
$userdir = $_SESSION['docroot_path']."/sohoadmin/tmp_content";

if ($globalprefObj->get('site_base_template') == '') {
	$globalprefObj->set('site_base_template', 'Professional-Cutting_Edge-blue');
} // End If File Exists


##################################################
### DEFINE CONTENT AREA STATEGY				   ###
##################################################
$filename = $cgi_bin . "/contentarea.conf";
if (!file_exists($filename)) {
	$file = fopen("$filename", "w");
		fwrite($file, "LIQUID");
	fclose($file);
}


##########################################################################################
### SETUP PRO MODULES IF THEY EXIST
##########################################################################################

if ( file_exists($_SESSION['docroot_path']."/sohoadmin/filebin/soholaunch.lic") ) {

	// -----------------------------------------------------------
	// COPY SHOPPING CART RUNTIME FILES TO ROOT/SHOPPING
	// -----------------------------------------------------------
	if (is_dir($_SESSION['docroot_path']."/sohoadmin/client_files/shopping_cart")) {

		$DIR = $_SESSION['docroot_path']."/shopping";
		if (!is_dir($DIR)) {
			if (!mkdir($DIR, 0755)) { echo ("Dir Creation Error: Error Creating <B>$DIR</b> directory<BR>"); $err=1; };
			chmod ($DIR, 0755);
		}

		$userdir = $_SESSION['docroot_path']."/shopping";
		$clientdir = "client_files/shopping_cart";
		
		
		foreach(glob('client_files/shopping_cart/*.*') as $cfile){
			$cfile = basename($cfile);
		
			if(!preg_match('/\.php$/i', $cfile)){
				@copy("$clientdir/$cfile", "../shopping/$cfile");
			} else {
				@copy("client_files/shopping_files_include_file.php", "../shopping/$cfile");
				//echo $cfile;
			}
		}

//		$handle = opendir("$clientdir");
//		while ($files = readdir($handle)) {
//		   $cn++;
//			if (strlen($files) > 2) {
//
//			   // Un-comment echo lines to test file copy
//			   /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
//      		if ( @copy("$clientdir/$files", "$userdir/$files") ) {
//      		   //echo "<font style=\"color: #339959;\">$cn) $clientdir/<b>$files</b></font><br>\n";
//      		} else {
//      		   //echo "<font style=\"color: #980000;\">$cn) $clientdir/<b>$files</b></font><br>\n";
//      		}
//      		/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
//
//			}
//		}
//		closedir($handle);

	} // end cart confirm


	// -----------------------------------------------------------
	// COPY DEMO INCLUDE FILES TO MEDIA FOLDER FOR USE BY CLIENT
	// -----------------------------------------------------------

	if (is_dir("client_files/demo_includes")) {

		$userdir = $_SESSION['docroot_path']."/media";
		$clientdir = "client_files/demo_includes";

		$handle = opendir("$clientdir");
		while ($files = readdir($handle)) {
			if (strlen($files) > 2) {
				@copy("$clientdir/$files", "$userdir/$files");
			}
		}
		closedir($handle);

	} // end demo_includes confirm


	// -----------------------------------------------------------
	// COPY SECURE LOGIN FILES TO ROOT
	// -----------------------------------------------------------
//
//	if (is_dir("client_files/secure_login")) {
//
//		$userdir = $_SESSION['docroot_path'];
//		$clientdir = "client_files/secure_login";
//
//		$handle = opendir("$clientdir");
//		while ($files = readdir($handle)) {
//			if (strlen($files) > 2) {
//				@copy("$clientdir/$files", "$userdir/$files");
//			}
//		}
//		closedir($handle);
//
//	} // end secure confirm


	// -----------------------------------------------------------
	// COPY NEWSLETTER SUBSCRIPTION CENTER
//	// -----------------------------------------------------------
//
//	if (is_dir("client_files/newsletter")) {
//
//		$DIR = $_SESSION['docroot_path']."/subscription";
//		if (!is_dir($DIR)) {
//			if (!mkdir ($DIR, 0755)) { echo ("Dir Creation Error: Error Creating <B>$DIR</b> directory<BR>"); $err=1; };
//			chmod ($DIR, 0755);
//		}
//
//	 	$userdir = $_SESSION['docroot_path']."/subscription";
//	 	$clientdir = "client_files/newsletter";
//
//	 	$handle = opendir("$clientdir");
//		while ($files = readdir($handle)) {
//			if (strlen($files) > 2) {
//				@copy("$clientdir/$files", "$userdir/$files");
//			}
//		}
//		closedir($handle);
//
//	 } // end secure confirm

	// -----------------------------------------------------------
	// COPY CALENDAR MODS TO ROOT IF EXIST
	// -----------------------------------------------------------
//
//	if (is_dir("client_files/calendar")) {
//
//	 	$userdir = $_SESSION['docroot_path'];
//	 	$clientdir = "client_files/calendar";
//
//	 	$handle = opendir("$clientdir");
//		while ($files = readdir($handle)) {
//			if (strlen($files) > 2) {
//				@copy("$clientdir/$files", "$userdir/$files");
//			}
//		}
//		closedir($handle);
//
//	 } // end calendar client mod xfer

	// -----------------------------------------------------------
	// COPY PHOTO ALBUM MODS TO ROOT IF EXIST
	// -----------------------------------------------------------
//
//	if (is_dir("client_files/photo_album")) {
//
//	 	$userdir = $_SESSION['docroot_path'];
//	 	$clientdir = "client_files/photo_album";
//
//	 	$handle = opendir("$clientdir");
//		while ($files = readdir($handle)) {
//			if (strlen($files) > 2) {
//				@copy("$clientdir/$files", "$userdir/$files");
//			}
//		}
//		closedir($handle);
//
//	 } // end photo album client mod xfer

	// -----------------------------------------------------------
	// COPY STATISTICS RUNTIME FILE TO ROOT IF EXISTS
	// -----------------------------------------------------------
//
//	if (is_dir("client_files/statistics")) {
//
//	 	$userdir = $_SESSION['docroot_path'];
//	 	$clientdir = "client_files/statistics";
//
//	 	$handle = opendir("$clientdir");
//		while ($files = readdir($handle)) {
//			if (strlen($files) > 2) {
//				@copy("$clientdir/$files", "$userdir/$files");
//			}
//		}
//		closedir($handle);
//
//	 } // end stats mod xfer


} // End verify license exists

# Switch back to original working folder
chdir($orig_dir);

?>