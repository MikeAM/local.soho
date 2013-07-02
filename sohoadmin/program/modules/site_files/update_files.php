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
require_once('../../includes/product_gui.php');
function sterilize ($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[.0-9a-z_ ]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}
	$sterile_var = $tmp;
	return $sterile_var;
}

#######################################################
### GET ALL VARIABLES AND STERILIZE VARS ##############
#######################################################	

reset($HTTP_POST_VARS);
while (list($name, $value) = each($HTTP_POST_VARS)) {

	if (!eregi("file", $name)) {
		$value = eregi_replace(" ", "_", $value);
		// $var_value = sterilize($value);
		$var_name = sterilize($name);
		// $var_value = eregi_replace("media", "media/", $var_value);
		// $var_value = eregi_replace("images", "images/", $var_value);
		$var_value = stripslashes($value);
		${$var_name} = $var_value;
		// echo (">> $var_name = $var_value ||||");
	}
	
}

#######################################################

$base_dir = "$doc_root/";

#######################################################

for ($x=0;$x<=$true_count;$x++) {
   //echo "file #".$x." - ".${"file".$x}."<br>";

   // ==========================================================
   // ==========================================================

   $tmp = "file" . $x;
   $old_filename = ${$tmp};

   $tmp = "new_name" . $x;
   $new_filename = ${$tmp};

   $tmp = "file_delete" . $x;
   $delete_flag = ${$tmp};

   // ==========================================================
   // ==========================================================

   if ($delete_flag == "yes") { 

      $dfile = $base_dir . $old_filename;
      unlink($dfile);

   } else {
   
      if (strlen($new_filename) > 2) {

         $tmp = split("/", $old_filename);
         $tmpdir = $tmp[0];

         if (eregi("\.gif", $old_filename) && !eregi("\.gif", $new_filename)) { $new_filename .= ".gif"; }
         if (eregi("\.jpg", $old_filename) && !eregi("\.jpg", $new_filename)) { $new_filename .= ".jpg"; }
         if (eregi("\.swf", $old_filename) && !eregi("\.swf", $new_filename)) { $new_filename .= ".swf"; }
         if (eregi("\.txt", $old_filename) && !eregi("\.txt", $new_filename)) { $new_filename .= ".txt"; }
         if (eregi("\.xls", $old_filename) && !eregi("\.xls", $new_filename)) { $new_filename .= ".xls"; }
         if (eregi("\.pdf", $old_filename) && !eregi("\.pdf", $new_filename)) { $new_filename .= ".pdf"; }
         if (eregi("\.form", $old_filename) && !eregi("\.form", $new_filename)) { $new_filename .= ".form"; }
         if (eregi("\.doc", $old_filename) && !eregi("\.doc", $new_filename)) { $new_filename .= ".doc"; }
         if (eregi("\.avi", $old_filename) && !eregi("\.avi", $new_filename)) { $new_filename .= ".avi"; }
         if (eregi("\.mov", $old_filename) && !eregi("\.mov", $new_filename)) { $new_filename .= ".mov"; }
         if (eregi("\.mp3", $old_filename) && !eregi("\.mp3", $new_filename)) { $new_filename .= ".mp3"; }
         if (eregi("\.html", $old_filename) && !eregi("\.html", $new_filename)) { $new_filename .= ".html"; }
         if (eregi("\.csv", $old_filename) && !eregi("\.csv", $new_filename)) { $new_filename .= ".csv"; }

         $old = $base_dir . $old_filename;
         $new = $base_dir . $tmpdir . "/" . $new_filename;

         // echo ("$old >> $new");
         // exit;

         rename("$old", "$new"); 

      } // END RENAME DATA
   }

   // ==========================================================
   // ==========================================================

} // True Count Loop

#######################################################

header ("Location: ../site_files.php?update=1&=SID");
exit;

?>

		