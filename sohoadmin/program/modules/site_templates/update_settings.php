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

session_start();
require_once("../../includes/product_gui.php");

########################################################
## SET TEMPLATE STORAGE LOCATIONS                     ##
########################################################
$globalprefObj = new userdata('global');
$template_dir = "$doc_root/template";
$new_template_dir = $site;

#######################################################
### Delete Old Template Files in Root Dir		    ###
#######################################################	

$directory = $template_dir;
if (is_dir($directory)) {
	$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$deleteit = $directory."/".$files;
			@unlink($deleteit);
		}
	}
	closedir($handle);
}

######################################################
## Copy New Template Files to User Directory	    ##
######################################################

if (!eregi("tCustom", $site)) {	

	// If this is NOT a custom uploaded template then copy runtime files to template dir
	$directory = "pages/".$new_template_dir;
	// Open Built-In Directory and copy files to "client" side
	$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$basefile = "$directory/".$files;		
			$clientfile = $template_dir."/".$files;
			@copy($basefile, $clientfile);
		}
	}
	closedir($handle);

	// Now write template.conf file so that we can load the current 
	// template from memory next time user enters "Site Templates" option


//	$filename = $doc_root . "/sohoadmin/tmp_content/template.conf";
//
//	$file = fopen("$filename", "w");
//		fwrite($file, "$site");
//	fclose($file);
	
	$globalprefObj->set('site_base_template', $site);
	

} else {
//	$filename = $doc_root . "/sohoadmin/tmp_content/template.conf";
//	
//	$file = fopen("$filename", "w");
//	fwrite($file, "$site");
//	fclose($file);
	$globalprefObj->set('site_base_template', $site);
}


##########################################################
## UPDATE SINGLE PAGE TEMPLATE ASSIGNMENTS FILE			##
##########################################################

/*
if ($page_templates != "") {
	$filename = "$doc_root/media/page_templates.txt";
	$file = fopen("$filename", "w");
	fwrite($file, "$page_templates\n");
	fclose($file);
}

*/

##########################################################
## UPDATE CONTENT AREA SETTINGS							##
##########################################################

$filename = $cgi_bin . "/contentarea.conf";
$file = fopen("$filename", "w");
	fwrite($file, "$CONTENTAREA");
fclose($file);

###################################################################
#### Go Back to Page			 				  			   ####
###################################################################

header ("Location: ../site_templates.php?success=$success&=SID");
exit;
?>