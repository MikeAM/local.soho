<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
ini_set("memory_limit", "-1");

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

session_start();
set_time_limit(0);

require_once("../../includes/product_gui.php");

error_reporting(E_PARSE);
//resizeimages

//echo testArray($_FILES['FILE0']);
//error_reporting(E_ALL);

ini_set('post_max_size', '90M');
ini_set('upload_max_filesize', 90);
ini_set('max_input_time', 6000);
ini_set('max_execution_time', 6000);
###############################################################################
error_reporting('E_PARSE');
if ($doc_root == "" || $cgi_bin == "") {
	echo ("ERROR: You have not conifgured the isp.conf file in the /conf directory to show an upload location");
	exit;
}

######################################################

$success = "";

function sterilize ($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[.0-9a-z_-]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}
	$sterile_var = $tmp;
	return $sterile_var;
}

#######################################################
### STEP 1: CREATE REQ DIR STRUCTURES IF NOT EXIST  ###
#######################################################

$err = 0;

		$DIR = "$doc_root/media";
		if (!is_dir($DIR)) {
 			if (!mkdir ("$DIR", 0755)) { echo ("Dir Creation Error: Error Creating <B>$DIR</b> directory<BR>"); $err=1; };
		}

		$DIR = "$doc_root/images";
		if (!is_dir($DIR)) {
 			if (!mkdir ("$DIR", 0755)) { echo ("Dir Creation Error: Error Creating <B>$DIR</b> directory<BR>"); $err=1; };
		}

//		$DIR = "$doc_root/import";
//		if (!is_dir($DIR)) {
// 			if (!mkdir ("$DIR", 0755)) { echo ("Dir Creation Error: Error Creating <B>$DIR</b> directory<BR>"); $err=1; };
//		}
//
//		$DIR = "$doc_root/tCustom";
//		if (!is_dir($DIR)) {
// 			if (!mkdir ("$DIR", 0755)) { echo ("Dir Creation Error: Error Creating <B>$DIR</b> directory<BR>"); $err=1; };
//		}

if ($err == 1) { echo ("<h4><font color=red><U>Possible Solution:</U></font><BR><BR>Modify <U>isp.conf</U> file for doc_root to equal <U>$DOCUMENT_ROOT</U>.<BR>Remember, Linux is fickle!"); exit; }

#######################################################
### STEP 2: CONFIRM ACCEPTABLE FORMATS	     	       ###
#######################################################

for ($x=0;$x<=9;$x++) {
	$filefield = "FILE".$x;
	$filename = $_FILES[$filefield]['name'];
	$filesize = "FILE".$x."_size";
	$filesize = $_FILES[$filefield]['size'];
	$FILE = $_FILES[$filefield]['tmp_name'];
	$extension = end(explode(".", $filename));

	// ---------------------------------------------------------------------------------------------------------------
	// DEVNOTE: IN CASE THIS SYSTEM IS RUNNING A PERL VERSION OF PHP; DO NOT ALLOW PHP CGI OR PHP.INI TO BE UPLOADED.
	//          THIS IS A MAJOR SECURITY FAULT.  DO NOT ERASE THE NEXT FEW LINES OF CODE OR A MALICIOUS
	//          SYSTEM USER COULD KILL YOUR SERVER
	//          AS LATER VERSIONS ARE RELEASED YOU MAY MODIFY THIS TO INCLUDE PHP5, PHP6, ETC.
	// ---------------------------------------------------------------------------------------------------------------

	if ($filename == "php4" || eregi("php.ini", $filename)) {
		echo ("Upload Error: Upload Security Violation in filename.");
		exit;
	}

	// ---------------------------------------------------------------------------------------------------------------
	// OK -- SECURITY CHECKED NOW MAKE SURE OF VALID FORMATS (THIS IS ANOTHER WAY TO SECURE YOUR SERVER)
	// ---------------------------------------------------------------------------------------------------------------

	$fileok = 0;
	$checkfor = strtolower($filename);
	$filename = eregi_replace(" ", "_", $filename);
	$filename = sterilize($filename);


	if (strstr($checkfor, ".gif")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".bmp")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".jpg")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".jpeg")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".png")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".pdf")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".rm")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".wav")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".ipx")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".swf")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if ( strstr($checkfor, ".mp3") || strstr($checkfor, ".mp4") ) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".m4a")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".avi")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".wmv")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".wma")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".asf")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".asx")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".mpg")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".mpeg")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".exe")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".mov")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".xls")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".doc")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".ppt")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".pps")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".css")) {
		$newfile = "$doc_root/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".js")) {
		$newfile = "$doc_root/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".pl")) {
		$newfile = "$cgi_bin/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".php") || strstr($checkfor, ".inc")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".html") || strstr($checkfor, ".htm")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".form")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".txt")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".flv")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".exe") || strstr($checkfor, ".zip") || strstr($checkfor, ".tar") || strstr($checkfor, ".tgz") || strstr($checkfor, ".rpm")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".csv")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}


	//Added by Cameron -- Webmaster should be able to upload any file type.
	if($fileok != 1) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if(isset($FILE)) {
	if($fileok == 1) {
		if (strlen($filename) > 0) {

			if($_POST['extractzip']=='on' && $extension=='zip'){
				$currdir = getcwd();
				chdir($_SESSION['doc_root']);
				$rezult = unzip_files($FILE);
				foreach($rezult as $rezfile){
					$filesize = filesize($rezfile);
					if ($filesize >= 1048576) {
						$filesize = round($filesize/1048576*100)/100;
						$filesize = $filesize . "Mb";
					} elseif ($filesize >= 1024) {
						$filesize = round($filesize/1024*100)/100;
						$filesize = $filesize . "K";
					} else {
						$filesize = $filesize . "Bytes";
					}
					$success .= " <strong>".basename($rezfile)."</strong> ".lang('uploaded')."! (".$filesize.") <br/>";
				}
				unlink($FILE);
				chdir($currdir);
			} else {

	
	//			$newfile = stripslashes($newfile); // Mantis #008
	
				if ( eregi("WIN", PHP_OS) || eregi("microsoft", $_SERVER['SERVER_SOFTWARE']) || ereg("IIS", $_SERVER['SERVER_SOFTWARE']) ) {
					$tempfile = $FILE;					// WIN32 UPLOAD
				} else {
					$tempfile = stripslashes($FILE); 	// LINUX UPLOAD
					@unlink($newfile);
				}
	
				$nt_err = 0;
				if (file_exists($newfile)) { $nt_err = 1; }
	
				if(@copy($tempfile, $newfile)) {
					 if ($filesize >= 1048576) {
						$filesize = round($filesize/1048576*100)/100;
						$filesize = $filesize . "Mb";
					 } elseif ($filesize >= 1024) {
						$filesize = round($filesize/1024*100)/100;
						$filesize = $filesize . "K";
					 } else {
						$filesize = $filesize . "Bytes";
					 }
	
					if (strstr($checkfor, ".pl")) {
						@chmod ("$newfile", 0755);
					}
					
					if($_POST['resizeimages']=='on' && preg_match('/\.(gif|jpg|jpeg|png)$/i', $newfile) && function_exists('smart_resize_image')){
						smart_resize_image($newfile, 0, 0, false, 'file', true);
						$filesize = filesize($newfile);
						 if ($filesize >= 1048576) {
							$filesize = round($filesize/1048576*100)/100;
							$filesize = $filesize . "Mb";
						 } elseif ($filesize >= 1024) {
							$filesize = round($filesize/1024*100)/100;
							$filesize = $filesize . "K";
						 } else {
							$filesize = $filesize . "Bytes";
						 }
					}
					
					$success .= " <strong>".$filename."</strong> ".lang('uploaded')."! (".$filesize.") <br/>";
	
	 			} else {
	
	
					if ($nt_err == 0) {
						$success .= " <strong>".$filename."</strong> ".lang("Did not upload")." <font color=red>".lang('System Error')."!</font><br/>";
					} else {
						$success .= " <strong>".$filename."</strong> ".lang("Did not upload")." <font color=red>".lang('Filename already exists')."</font><br/>";
						
					}
	
				}
			}
		}
	} else {
		if (strlen($filename)>0) {
			$success .= " <strong>".$filename."</strong> ".lang("Did not upload")." <font color=red>".lang('File is not an accepted file format')."</font><br/>";
		}
	}
	}

//	if (strstr($filename, ".pl") || strstr($filename, ".txt") || strstr($filename, ".inc") || strstr($filename, ".php") || strstr($filename, ".form")) {
//			while (!file_exists("$newfile")) {
//				# Wait
//			}
//			$nfilename = "$newfile";
//			$nfile = fopen("$nfilename", "r");
//				$nbody = fread($nfile,filesize($nfilename));
//			fclose($nfile);
//			$nbody = eregi_replace("\n", "", $nbody);
//			$nlines = split("\r", $nbody);
//			$nnumLines = count($nlines);
//
//			$nfile = fopen("$nfilename", "w");
//			for ($z=0;$z<=$nnumLines;$z++) {
//				fwrite($nfile, "$nlines[$z]\n");
//			}
//			fclose($nfile);
//			@chmod ("$newfile", 0755);
//	}


}


###################################################################
#### Go Back to Page			 				   ####
###################################################################

// Un-comment for testing
//echo "<script language=\"javascript\">\n";
//echo "alert('success=[".$success."]');\n";
//echo "</script>\n";

header ("Location: ../site_files.php?success=$success&=SID");
exit;
?>