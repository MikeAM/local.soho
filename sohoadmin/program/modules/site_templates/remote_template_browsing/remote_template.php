<?php
session_start();
error_reporting(0);
require_once('sohoadmin/program/includes/product_gui.php');

$file_name = base64_encode($layout_file);
//$template_name = base64_encode($_GET['template_name']);
//$file_name = base64_encode($_GET['file_name']);
if (($_GET['pr'] == "" || $_GET['pr'] == startpage()) && ($_GET['nShow'] == "" && $_GET['bShow'] == "") && ($_GET['SHOPPING'] != 'YES')) {
	$file_name = base64_encode('home.html');
} elseif($_GET['nShow'] != "" || $_GET['bShow'] != ""){
	$file_name = base64_encode('news.html');	
} elseif($_GET['SHOPPING'] == 'YES'){
	$file_name = base64_encode('cart.html');
} else {
	$file_name = base64_encode('index.html');	
}

$source = 'http://securexfer.net/remote_template/remote_template_file.php?template='.$rmztemplate.'&file='.$file_name;

//include_template($source.'/index.html');
$tempcon = include_r($source);

//$imgsource = 'http://securexfer.net/remote_template/remote_template_file.php?template='.$rmztemplate.'&file=';
//$tempcon = eregi_replace('\</head\>', "<link rel=\"stylesheet\" type=\"text/css\" href=\"http://securexfer.net/remote_template/remote_template_file.php?template=".$rmztemplate."&file=".$imgsource.base64_encode('custom.css')."\"/> \n</head>", $tempcon);
//if ( file_exists($hpTemp) && ($pr == "" || $pr == startpage()) && ($nShow == "" && $bShow == "") ) {

//echo startpage();
//echo $hpTemp;
echo $tempcon;
?>