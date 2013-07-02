<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


error_reporting(0);
exit;
session_start();

$pdDir = str_replace(".php", "", $_SESSION['lang_set']);
$pfix = substr($pdDir, 0, 3);
$pdPath = $pdDir."/".$doc."-".$pfix.".html";

include($pdPath);

//foreach ( $_SESSION as $var=>$val ) {
//   echo "[".$var."] = (".$val.")<br>";
//}










?>