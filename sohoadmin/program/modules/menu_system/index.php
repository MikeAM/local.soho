<?php
error_reporting('0');
session_start();

exit;

if (strtolower($_SERVER['HTTPS']) != "on"){
	$thisurla = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
} else {
	$thisurla = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
}
$exp = explode('/sohoadmin/', $thisurla);
$thisurl = $exp['0'].'/';
header("Location: ".$thisurl);

exit;
?>