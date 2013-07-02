<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.6
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

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// By License you may not modify any portion of this script. This particular
// script has dependancies and programming that can not be modified.
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

// Open and read type.lic (define license type)
// ====================================================
$filename = "filebin/type.lic";
$file = fopen("$filename", "r");
	$prod = fread($file,filesize($filename));
fclose($file);
$prodnam = split("::::", $prod);
$product_name = $prodnam[1];

# Make sure we get an IP
if ( $_SERVER['SERVER_ADDR'] != "" ) {
   $disIP = $_SERVER['SERVER_ADDR'];
}elseif ( gethostbyname(php_uname(n)) != "" ) {
   $disIP = gethostbyname(php_uname(n));
}

# Make sure we get a server host name
if ( php_uname(n) != "" ) { // php_uname(n)
   $disHname = php_uname(n);

} elseif ( php_uname() != "" ) { // php_uname() - formatted
   $string = php_uname();
   $invalid = " ";
   $tok = strtok($string, $invalid);
   while ($tok) {
      $token[]=$tok;
      $tok = strtok($invalid);
   }
   $disHname = $token[1];

} elseif ( gethostbyaddr($_SERVER['SERVER_ADDR']) != "" ) { // Reverse lookup
   $disHname = gethostbyaddr($_SERVER['SERVER_ADDR']);
}


$host = "securexfer.net";
$target_api_script = "api-domain_pulse_check-ultra.php";
$data = "version=$product_name&dom=".$_SESSION['this_ip']."&ip=".$disIP."&sname=".$disHname."&dkey=".$_SESSION['key'];
if($_SESSION['final_ip']!=''){
	$data .= "&final_ip=".$_SESSION['final_ip'];
}
$buf = "";


// Ping server for server and leased licenses
// ================================================
if ( $product_name != "proretail" && $product_name != "proedition" ) {

   // Connect to server and check key status
   // -------------------------------------------------
   if ($fp = fsockopen($host,80)) {

      // Pull license for this domain
      // -------------------------------------------------------
      fputs($fp, "POST /product_reports/".$target_api_script." HTTP/1.1\n");
      fputs($fp, "Host: $host\n");
      fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
      fputs($fp, "Content-length: " . strlen($data) . "\n");
      fputs($fp, "User-Agent: MSIE\n");
      fputs($fp, "Connection: close\n\n");
      fputs($fp, $data);

      while (!feof($fp)) {
         $buf .= fgets($fp,128);
      }

      $tmp = split("~STAT~", $buf);
      $pulse['code'] = $tmp[1];
      $pulse['message'] = $tmp[2];

      fclose($fp);

   } // end if server connect successful

##############################################################################
// Error message if not valid (excluding proretail) or if frozen (all)
##############################################################################

   # Display pulse response data for testing
   /*----------------------------------------------*
   echo "<div style=\"padding: 10px; border: 1px solid red; text-align: left;\">\n";
   echo "product_name = [".$product_name."]<br>\n";
   echo "Pulse = [".$PULSE."]<br>";
   echo "</div>\n";
   exit;
   /*----------------------------------------------*/
unset($_SESSION['product_mode']);
unset($_SESSION['suspend_msg']);
   if ( $pulse['code'] == "" ) {
      /*--------------------*
      echo "<center><BR><BR><font face=\"tahoma,arial,sans-serif\" color=\"darkred\">";
      echo "Unable to locate a valid '$product_name' license for this domain (".$_SERVER['SERVER_NAME'].") on server (".$_SERVER['SERVER_ADDR'].").";
      echo "</font></center>";
      exit;
      /*--------------------*/
      //print_r($_SERVER);

   } elseif ( $pulse['code'] == "frozen" ) {
//      echo "<br><br><br>\n\n\n";
//      echo "<div style=\"border: 1px solid #d70000; background-color: #F8F9FD; padding: 50px; font-family: arial 13px; text-align: left; color: #000;\">\n";
//      echo " ".$pulse['message'];
//      echo "</div>\n";
//      
      $_SESSION['suspend_msg'] = $pulse['message'];
      $_SESSION['product_mode'] = "frozen";

   } elseif ( $pulse['code'] == "suspended" ) {
   	 $_SESSION['product_mode'] = "suspended";
      $_SESSION['suspend_msg'] = $pulse['message'];
//      echo "<br><br><br>\n\n\n";
//      echo "<div style=\"border: 1px solid #d70000; background-color: #F8F9FD; padding: 50px; font-family: arial 13px; text-align: left; color: #000;\">\n";
//      echo " ".$pulse['message'];
//      echo "</div>\n";
//      exit;

   } elseif ( $pulse['code'] == "wrongserver" ) {
      echo "<br><br><br>\n\n\n";
      echo "<div style=\"border: 1px solid #d70000; background-color: #F8F9FD; padding: 50px; font-family: arial 13px; text-align: left; color: #000;\">\n";
      echo " ".$pulse['message'];
      echo "</div>\n";
      exit;

   } elseif ( $pulse['code'] == "orphan" ) {
      $_SESSION['product_mode'] = "orphan";
      $_SESSION['suspend_msg'] = $pulse['message'];

   } elseif ( $pulse['code'] == "trial" ) {
      $_SESSION['product_mode'] = "trial";
      $_SESSION['trial_expires'] = $pulse['message'];
   }


}
include('includes/sense_pulse.php');
?>