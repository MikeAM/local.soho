<?php
error_reporting(0);
require_once('sohoadmin/program/includes/product_gui.php');


$file_name = base64_encode(basename(__FILE__));
$rmztemplate = base64_encode($_GET['rmtemplate']);
$source = 'http://securexfer.net/remote_template/remote_template_file.php?template='.$rmztemplate.'&file='.$file_name;
$tempcon = include_r($source);
$tempcon = eregi_replace('^\<\?(php)?', '//', $tempcon);
eval($tempcon);
?>