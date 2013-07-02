<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
require_once("../../includes/product_gui.php");

if ( !$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE type != 'menu'") ){
   echo "Cannot select from site_pages table<br>";
	echo "Mysql says: ".mysql_error();
	exit;
}

while($DIS_ARRAY1 = mysql_fetch_array($result)){
	$DIS_ARRAYz[] = $DIS_ARRAY1['page_name'];
}


natcasesort($DIS_ARRAYz);

// BUILD LINK LIST
$jsdisp = "var tinyMCELinkList = new Array( \n";
foreach($DIS_ARRAYz as $DIS_ARRAY){
   $page_link = str_replace(" ", "_", $DIS_ARRAY);
   $jsdisp .= "[\"".$DIS_ARRAY."\", \"".pagename($page_link)."\"],\n";
}
$jsdisp = eregi_replace("\,\n$", '', $jsdisp);
echo $jsdisp .= "); \n";
?>