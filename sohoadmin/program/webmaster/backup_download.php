<?php
# Increase memory limit to 500MB
ini_set('memory_limit', '524288000');

error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

//include("../includes/shared_functions.php");
//include("../includes/smt_functions.php");
require_once("../includes/product_gui.php");
if ( $_GET['todo'] == "download_backup" && $_GET['backup_file'] != "" ) {

   $filePath = "backups/".$_GET['backup_file'];
   $fileSize = filesize($filePath);

   $sizecap = ini_get("memory_limit");


   // Hack for IE-bug
   if ( strstr($HTTP_USER_AGENT, "MSIE") ) {
      $attachment = "";
   } else {
      $attachment = "attachment;";
   }

$handle = fopen($filePath, "r");
$output = fread($handle, $fileSize);
fclose($handle);
//   $output = file_get_contents($filePath);

   header('Content-Description: File Transfer');
   header("Content-disposition: $attachment;  filename=\"".$_GET['backup_file']."\"");
   header('Content-Type: application/zip; name='.$_GET['backup_file']."'");
   header("Content-Length: $fileSize");

   echo $output;
   exit;
}

?>