<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
require_once("../../includes/product_gui.php");

// BUILD LINK LIST
$jsdisp = "var tinyMCELinkList = new Array( \n";


$resultc = mysql_query("SELECT prikey, page_name, url_name FROM site_pages WHERE UPPER(type) = 'MAIN' OR UPPER(type) = 'MENU' ORDER BY page_name");

$a=0;
$page_data = "";
$cartcats_q = mysql_query("select * from cart_category where level='1'");
while($cartcatz = mysql_fetch_assoc($cartcats_q)){
	$cartcats[$cartcatz['keyfield']]=$cartcatz['category'];
}
while ($row = mysql_fetch_assoc ($resultc)) {
	$a++;
	$page_name[$a] = $row['page_name'];
	if ($a == 1) { $SEL = "SELECTED"; } else { $SEL = ""; }
	if(preg_match('/^cartid:/', $row['page_name'])){
		$kfield = str_replace(':', '', str_replace('cartid:', '', $row['page_name']));
		$displayname = "[cart] ".$cartcats[$kfield];
		//$cartcats = mysql_query("select category from cart_category where keyfield='".$kfield."'");
//		$jsdisp .= "     <OPTION VALUE='".$row['page_name']."' $SEL>[cart] ".$displayname."</OPTION>\n";
		$page_link = str_replace(" ", "_", $DIS_ARRAY);
		$page_link="shopping/start.php?browse=1&cat=".$kfield;
		$jsdisp .= "[\"".str_replace('"','',$displayname)."\", \"".str_replace('"','',$page_link)."\"],\n";
	} else {
//		$jsdisp .= "     <OPTION VALUE='".$row['page_name']."' $SEL>".$row['page_name']."</OPTION>\n";	
		$page_link = str_replace(" ", "_", $row['page_name']);
		$jsdisp .= "[\"".str_replace('"','',$row['page_name'])."\", \"".str_replace('"','',pagename($page_link))."\"],\n";
	}
}

$filecount=0;
foreach (glob($_SESSION['doc_root'].'/media/*') as $filename) {      
	if(is_file($_SESSION['doc_root'].'/media/'.basename($filename))){
		if(!eregi('\.(inc|php|swf|bak|html|htm|js)$', basename($filename))){
			$filename = str_replace("'", "\'", $filename);
			$filename = basename($filename);
			$DISF_ARRAYz[] = $filename;
			++$filecount;
		}
	}
}
natcasesort($DISF_ARRAYz);
foreach($DISF_ARRAYz as $fixem){
	$ftype = eregi_replace('[^\.]*\.', '', $fixem);
	$ftype = eregi_replace('[^\.]*\.', '', $ftype);
	$efilearr[strtolower($ftype)][]=$fixem;
}
//natcasesort($efilearr);
uksort($efilearr, "strnatcasecmp");
//if($filecount > 0){
//	$jsdisp .= "[\"--- SITE FILES DOWNLOAD ---\", \"\"],\n";
//}
//foreach($efilearr as $DISF_ARRAY=>$DISF_ARRAYv){
//	foreach($DISF_ARRAYv as $fvalz){
////		$jsdisp_media .= "[\"".basename($fvalz)."\", \"media/".basename($fvalz)."\"],\n";
//		$jsdisp .= "[\"".str_replace('"','',basename($fvalz))."\", \"pgm-download_media.php?name=".str_replace('"','',basename($fvalz))."\"],\n";
//	}
//}

$jsdisp = eregi_replace("\,\n$", '', $jsdisp);
echo $jsdisp .= "); \n";

?>