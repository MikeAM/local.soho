<?php
error_reporting('E_PARSE');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

##################################################
### COPY ALL BASE RUNTIME FILES TO USER        ###
### DIRECTORY FOR LATEST UPDATE OPERATION      ###
##################################################
$pagefile = __FILE__;
if($pagefile == ''){
	$pagefile = $_SERVER['SCRIPT_FILENAME'];
}
$pagefile = basename($pagefile);

$gc = fopen('../sohoadmin/client_files/shopping_cart/'.$pagefile, 'r');
$cont = fread($gc, filesize('../sohoadmin/client_files/shopping_cart/'.$pagefile));
fclose($gc);
eval('?>'.$cont);
?>