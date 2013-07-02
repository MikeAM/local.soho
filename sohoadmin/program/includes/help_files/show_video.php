<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


session_start();
error_reporting(E_PARSE);


# Primary interface include
include_once("../product_gui.php");
include_once("../autoupdate_functions.php");

//$videoDirTest = "/home/joejamb/public_html/media/FlashTest/".$_GET['swffile'].".swf";
//$videoDirTest = "http://".$_GET['demo_path']."/".$_GET['swffile'].".swf";
$videoDirTest = "http://joe.jambuildit.com/media/FlashTest/adminonew.swf";

if ( $fp = fopen($videoDirTest, "r") ) {
   $contents = fread($fp, filesize($videoDirTest));
   fclose();
} else {
   echo "Unable to open file!<br>";
}

echo $contents;

//echo "<textarea style=\"width:400; height:400;\">".$contents."</textarea><br><br>\n";
//echo "(This is the display<br><br>".$out1.")";



?>