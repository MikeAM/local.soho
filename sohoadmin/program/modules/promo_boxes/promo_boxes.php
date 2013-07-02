<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Author: 			Joe Lain
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc.  All Rights Reserved.
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

session_start();

require_once("../../includes/product_gui.php");
$globalprefObj = new userdata('global');

//# Double check for promo_boxes table
//if ( !table_exists("promo_boxes") ) {
//   include_once
//if (


# COLORS - Pre-build options for color dropdowns (i.e. bg color for sitepal scenes)
$filename = "../mods_full/shopping_cart/shared/color_table.dat";
$colordat = file_get_contents($filename);
$colorLines = split("\n", $colordat);
$max = count($colorLines);
$colors = array();
$keynum = 0;
$color_options = "";
for ($x = 0; $x <= $max; $x++ ) {
   $temp = split(",", $colorLines[$x]); // Sample line: "Red,#ff0000"
   if ($temp[0] != "") {
      $colors[$keynum]['name'] = $temp[0];
      $colors[$keynum]['hex'] = $temp[1];

      # Build dropdown options
      $color_options .= "<option value=\"".$colors[$keynum]['hex']."\" alt=\"".$colors[$keynum]['name']."\" style=\"background-color: #".$colors[$keynum]['hex']."\">".$colors[$keynum]['name']."</option>\n";
   }
}
?>

<link rel="stylesheet" type="text/css" href="box_manager.css">
<link id="myStyle" rel="stylesheet" type="text/css" href="">

<?php

# Check for blog tables (and promo_boxes table...which neccessitates calling the whole table-create include)
//include_once($_SESSION['docroot_path']."/sohoadmin/includes/create_system_tables.inc.php");
//include_once("../blog-dbtable_check.inc.php");


/*---------------------------------------------------------------------------------------------------------*
   ____                   _____            __             __
  / __/___ _ _  __ ___   / ___/___   ___  / /_ ___  ___  / /_
 _\ \ / _ `/| |/ // -_) / /__ / _ \ / _ \/ __// -_)/ _ \/ __/
/___/ \_,_/ |___/ \__/  \___/ \___//_//_/\__/ \__//_//_/\__/

# Change content type and content associated with a particular box
# Called by update_category() js function
/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['todo'] == "update_category" ) {


   # disable or save change?
   if ( $_GET['newcat'] == "disable" ) {
      # DISABLE
      $_GET['do'] = "disThis";
      $_GET['daKey'] = $_GET['box'];
      $_GET['newvalue'] = "off";

   } else {
      # SAVE
      $content_src = $_GET['newcat'];

      # Restore current settings array from db
      $selBox = "select * from promo_boxes where BOX = '".$_GET['box']."'";
      $rez = mysql_query($selBox);
      $getBox = mysql_fetch_array($rez);
      $boxArray = unserialize($getBox['CONTENT']);

      # Change content element to reflect new value
      $boxArray['content'] = $content_src;
      $boxArray['display'] = "on";
      $content_data = serialize($boxArray);

      # Update db record now!
      $qry = "update promo_boxes set CONTENT = '".$content_data."', content_type = '".$_GET['new_content_type']."', content_src = '".$content_src."' where BOX = '".$_GET['box']."'";
      mysql_query($qry);

   } // End else newcat != "disable"

} // End if todo == update_category



/*---------------------------------------------------------------------------------------------------------*
    ____   _               __     __
   / __ \ (_)_____ ____ _ / /_   / /___
  / / / // // ___// __ `// __ \ / // _ \
 / /_/ // /(__  )/ /_/ // /_/ // //  __/
/_____//_//____/ \__,_//_.___//_/ \___/

# Disable/enable box when user clicks the checkbox
/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['do'] == "disThis" ) {
//   echo "disablehere"; exit;

   # Restore current settings array from db
   $selBox = "select * from promo_boxes where BOX = '".$_GET['daKey']."'";
   $rez = mysql_query($selBox);
   $getBox = mysql_fetch_array($rez);
   $boxArray = unserialize($getBox['CONTENT']);

   # Change display element to reflect new value
   $boxArray['display'] = $_GET['newvalue'];

   # Update db record now!
   $qry = "update promo_boxes set CONTENT = '".serialize($boxArray)."', content_type = 'disable' where BOX = '".$_GET['daKey']."'";
   mysql_query($qry);

} // End if $_GET['do'] = disThis



if (!$result = mysql_query("SELECT CATEGORY_NAME FROM blog_category")){
   echo "Cannot select from blog_category table<br>";
	echo "Mysql says: ".mysql_error();
	exit;
}

while($BLOG_CATS = mysql_fetch_array($result)){
   //CREATE ARRAY OF BLOG CATEGORYS
   $tmpCats .= "<option name=\"".$BLOG_CATS['CATEGORY_NAME']."\" value=\"".$BLOG_CATS['CATEGORY_NAME']."\">".$BLOG_CATS['CATEGORY_NAME']."</option>\n";
}
$daBox = $_GET['box'];
#######################################################
### READ CURRENT BASE TEMPLATE NAME INTO MEM   		###
#######################################################
//$filename = $doc_root."/sohoadmin/tmp_content/template.conf";
//if (file_exists("$filename")) {
//	$file = fopen("$filename", "r");
//	$CUR_TEMPLATE = fread($file,filesize($filename));
//	fclose($file);
//	$CUR_TEMPLATE = rtrim($CUR_TEMPLATE);
//}

$CUR_TEMPLATE = rtrim($globalprefObj->get('site_base_template'));

######################################################
## Calculate # of #BOX# in all possible files       ##
## Joe Lain 12-29-05                                ##
######################################################

$filename = "../site_templates/pages/".$CUR_TEMPLATE;

$handle = opendir("$filename");
while ($files = readdir($handle)) {
	if (strlen($files) > 2) {
	   if($files == "index.html" || $files == "news.html" || $files == "home.html" || $files == "cart.html"){
	      $tmpltFiles .= $files.";";
	   }
	}
}
closedir($handle);

// Check for #BOX# variable in index.html, home.html, news.html, cart.html
// Create array of files

$fileArray = split(";", $tmpltFiles);
$arrLen = count($fileArray)-1;
$y = "";
$cartArr = ""; $newsArr = ""; $homeArr = ""; $indexArr = "";
$indexCnt = "";

for($tmp=0;$tmp<$arrLen;$tmp++){

   ob_start();
   	include("".$filename."/".$fileArray[$tmp]."");
   	$HTML_CONTENT = ob_get_contents();
   ob_end_clean();
   $num_boxes = substr_count($HTML_CONTENT, '#BOX');
   $num_titles = substr_count($HTML_CONTENT, '#BOX-TITLE');

   $num_boxes = $num_boxes - $num_titles;

   // Count box records in current file
   $y += $num_boxes;
   //echo "num boxes in (".$fileArray[$tmp].") is (".$num_boxes.")<br><br> total so far (((".$y.")))<br><br>";

   if(eregi("cart.html", $fileArray[$tmp])){
      $cartArr .= $y.";".$num_boxes;
   }elseif(eregi("news.html", $fileArray[$tmp])){
      $newsArr .= $y.";".$num_boxes;
   }elseif(eregi("home.html", $fileArray[$tmp])){
      $homeArr .= $y.";".$num_boxes;
   }else{
      $indexArr .= $y.";".$num_boxes;
      $indexCnt = $num_boxes;
   }
}
$num_boxes = $y;
$cntFiles = 0;
// Update index boxes
if($indexArr != ""){
   $indexArr = split(";", $indexArr);
   if($indexArr[0] == $indexArr[1]){
      for($x=1;$x<=$indexArr[0];$x++){
	       if($cntFiles < $x){
	       	 $cntFiles = $x;
	       }
         $box = "box".$x;
         $indexBox = "index".$x;
         mysql_query("UPDATE promo_boxes SET FILE = '$indexBox' WHERE BOX = '$box'");
      }
   }else{
      $startbox = $indexArr[0] - $indexArr[1] + 1;
      $boxNum = 1;
      for($x=$startbox;$x<=$indexArr[0];$x++){
	       if($cntFiles < $x){
	       	 $cntFiles = $x;
	       }
         $box = "box".$x;
         $indexBox = "index".$boxNum;
         mysql_query("UPDATE promo_boxes SET FILE = '$indexBox' WHERE BOX = '$box'");
         $boxNum++;
      }
   }
}


// Update cart boxes
if($cartArr != ""){
   $cartArr = split(";", $cartArr);
   if($cartArr[0] == $cartArr[1]){
      for($x=1;$x<=$cartArr[0];$x++){
	       if($cntFiles < $x){
	       	 $cntFiles = $x;
	       }
         $box = "box".$x;
         $cartBox = "cart".$x;
         mysql_query("UPDATE promo_boxes SET FILE = '$cartBox' WHERE BOX = '$box'");
      }
   }else{
      $startbox = $cartArr[0] - $cartArr[1] + 1;
      $boxNum = 1;
      for($x=$startbox;$x<=$cartArr[0];$x++){
	       if($cntFiles < $x){
	       	 $cntFiles = $x;
	       }
         $box = "box".$x;
         $cartBox = "cart".$boxNum;
         mysql_query("UPDATE promo_boxes SET FILE = '$cartBox' WHERE BOX = '$box'");
         $boxNum++;
      }
   }
}

// Update news boxes
if($newsArr != ""){

   $newsArr = split(";", $newsArr);
   if($newsArr[0] == $newsArr[1]){
      for($x=1;$x<=$newsArr[0];$x++){
	       if($cntFiles < $x){
	       	 $cntFiles = $x;
	       }
         $box = "box".$x;
         $newsBox = "news".$x;
         mysql_query("UPDATE promo_boxes SET FILE = '$newsBox' WHERE BOX = '$box'");
      }
   }else{
      $startbox = $newsArr[0] - $newsArr[1] + 1;
      $boxNum = 1;
      for($x=$startbox;$x<=$newsArr[0];$x++){
	       if($cntFiles < $x){
	       	 $cntFiles = $x;
	       }
         $box = "box".$x;
         $newsBox = "news".$boxNum;
         mysql_query("UPDATE promo_boxes SET FILE = '$newsBox' WHERE BOX = '$box'");
         $boxNum++;
      }
   }
}

// Update home boxes
if($homeArr != ""){
   $homeArr = split(";", $homeArr);
   if($homeArr[0] == $homeArr[1]){
      for($x=1;$x<=$homeArr[0];$x++){
	       if($cntFiles < $x){
	       	 $cntFiles = $x;
	       }
         $box = "box".$x;
         $homeBox = "home".$x;
         mysql_query("UPDATE promo_boxes SET FILE = '$homeBox' WHERE BOX = '$box'");

      }
   }else{
      $startbox = $homeArr[0] - $homeArr[1] + 1;
      $boxNum = 1;
      for($x=$startbox;$x<=$homeArr[0];$x++){
	       if($cntFiles < $x){
	       	 $cntFiles = $x;
	       }
         $box = "box".$x;
         $homeBox = "home".$boxNum;
         mysql_query("UPDATE promo_boxes SET FILE = '$homeBox' WHERE BOX = '$box'");
         $boxNum++;
      }
   }
}

if (!$result = mysql_query("SELECT * FROM promo_boxes")){
   echo "Cannot select from promo_boxes table<br>";
	echo "Mysql says: ".mysql_error();
	exit;
}


$promo_file_check = "";
while($PROMO_CHECK = mysql_fetch_array($result)){
	//echo "(".$PROMO_CHECK['FILE'].")";
	if(!eregi($PROMO_CHECK['FILE'], $promo_file_check) && $PROMO_CHECK['FILE'] != ""){
		//echo "Added File (".$PROMO_CHECK['FILE'].")<br>";
		//echo "This (".$PROMO_CHECK['FILE'].") is not in here (".$promo_file_check.")<br>";
		$promo_file_check .= $PROMO_CHECK['FILE']."-".$PROMO_CHECK['BOX'].";";
	}else{
		if($PROMO_CHECK['FILE'] != ""){
			//echo "<b>Duplicate File Found(".$PROMO_CHECK['FILE'].")</b><br>";
			$daFiles = split(";", $promo_file_check);
			foreach($daFiles as $var=>$val){
				//echo "var = (".$var.") val = (".$val.")<br>";

			  if(eregi($PROMO_CHECK['FILE'], $daFiles[$var])){
			  	//echo "this (".$daFiles[$var].") = (".$PROMO_CHECK['FILE'].")<br>";
			  	$myUpdate = "";
			  	$daFileBox = split("-", $val);
			  	//echo "0 = (".$daFileBox[0].") 1 = (".$daFileBox[1].")<br>";

					$daContent = $PROMO_CHECK['CONTENT'];
					$daNumDisplay = $PROMO_CHECK['NUM_DISPLAY'];
					$daDispTitle = $PROMO_CHECK['DISP_TITLE'];
					$daDispContent = $PROMO_CHECK['DISP_CONTENT'];
					$daDispDate = $PROMO_CHECK['DISP_DATE'];
					$daDispMore = $PROMO_CHECK['DISP_MORE'];
					$daSettings = $PROMO_CHECK['SETTINGS'];

			  	$myUpdate = "CONTENT = '$daContent', NUM_DISPLAY = '$daNumDisplay', DISP_TITLE = '$daDispTitle',";
			  	$myUpdate .= "DISP_CONTENT = '$daDispContent', DISP_DATE = '$daDispDate', DISP_MORE = '$daDispMore',";
			  	$myUpdate .= "SETTINGS = '$daSettings'";

			  	//echo "Updateing BOX(".$daFileBox[1].")<br>";
			  	//echo "With this info (".$myUpdate.")<br>";

			  	mysql_query("UPDATE promo_boxes SET ".$myUpdate." WHERE BOX = '$daFileBox[1]'");
			  }
			}
		}
	}

}


					##### CLEAR FILE FIELD FOR ALL BOXES NOT IN USE #####

$cntFiles++;
$fileDetect = split(";", $fileDetect);
for($y=$cntFiles;$y<=25;$y++){
	$daBox = "box".$y;
	mysql_query("UPDATE promo_boxes SET FILE = '' WHERE BOX = '$daBox'");
}


if (!$result = mysql_query("SELECT * FROM promo_boxes")){
   echo "Cannot select from promo_boxes table<br>";
	echo "Mysql says: ".mysql_error();
	exit;
}

// Build display for box
$cnt = 1; $cartCnt = 1; $newsCnt = 1; $homeCnt = 1; $main = 1;
$CONT = ""; $boxName = "";
while($PROMO = mysql_fetch_array($result)){
   $CONT .= $PROMO['CONTENT'].";";
   if(eregi("cart", $PROMO['FILE'])){
      $cartfile[$PROMO['BOX']]['BOX'] = $PROMO['BOX'];
      $cartfile[$PROMO['BOX']]['CONTENT'] = unserialize($PROMO['CONTENT']);
      $cartfile[$PROMO['BOX']]['FUTURE1'] = unserialize($PROMO['FUTURE1']);
      $cartfile[$PROMO['BOX']]['content_type'] = $PROMO['content_type'];
      $cartfile[$PROMO['BOX']]['content_src'] = $PROMO['content_src'];
      $display[$main]['CART_FILE'] = $PROMO['prikey'];
      $boxName .= "cart.html Box ".$cartCnt.";";
      $cartCnt++;
   }elseif(eregi("news", $PROMO['FILE'])){
      $newsfile[$PROMO['BOX']]['BOX'] = $PROMO['BOX'];
      $newsfile[$PROMO['BOX']]['CONTENT'] = unserialize($PROMO['CONTENT']);
      $newsfile[$PROMO['BOX']]['FUTURE1'] = unserialize($PROMO['FUTURE1']);
      $newsfile[$PROMO['BOX']]['content_type'] = $PROMO['content_type'];
      $newsfile[$PROMO['BOX']]['content_src'] = $PROMO['content_src'];
      $display[$main]['NEWS_FILE'] = $PROMO['prikey'];
      $boxName .= "news.html Box ".$newsCnt.";";
      $newsCnt++;
   }elseif(eregi("home", $PROMO['FILE'])){
      $homefile[$PROMO['BOX']]['BOX'] = $PROMO['BOX'];
      $homefile[$PROMO['BOX']]['content_type'] = $PROMO['content_type'];
      $homefile[$PROMO['BOX']]['content_src'] = $PROMO['content_src'];
      $homefile[$PROMO['BOX']]['CONTENT'] = unserialize($PROMO['CONTENT']);
      $homefile[$PROMO['BOX']]['FUTURE1'] = unserialize($PROMO['FUTURE1']);
      $display[$homeCnt]['HOME_FILE'] = $PROMO['prikey'];
      $boxName .= "home.html Box ".$homeCnt.";";
      $homeCnt++;
   } else {
      if(eregi("index", $PROMO['FILE'])){
         $indexfile[$PROMO['BOX']]['BOX'] = $PROMO['BOX'];
         $indexfile[$PROMO['BOX']]['content_type'] = $PROMO['content_type'];
         $indexfile[$PROMO['BOX']]['content_src'] = $PROMO['content_src'];
         $indexfile[$PROMO['BOX']]['CONTENT'] = unserialize($PROMO['CONTENT']);
         $indexfile[$PROMO['BOX']]['FUTURE1'] = unserialize($PROMO['FUTURE1']);
         $boxName .= "Box ".$cnt.";";
         $cnt++;
      }
   }

   $main++;
}

if($cnt == 1){ $cnt = 0; }
if($cartCnt == 1){ $cartCnt = 0; }else{ $cartCnt--; }
if($newsCnt == 1){ $newsCnt = 0; }else{ $newsCnt--; }
if($homeCnt == 1){ $homeCnt = 0; }else{ $homeCnt--; }

$boxName = split(";", $boxName);


/*---------------------------------------------------------------------------------------------------------*
   ____
  / __/___ _ _  __ ___
 _\ \ / _ `/| |/ // -_)
/___/ \_,_/ |___/ \__/
/*---------------------------------------------------------------------------------------------------------*/
if ( $_POST['do'] == "saveBox" ) {
   $display_data = ""; $colNames = "";
   $box = $_POST['box'];
   foreach ( $_POST as $var=>$val ) {
      if($var != "do" && $var != "box" && $var != "addMe"){
         $colNames .= $var.";";
         //echo "var = (".$var.") val = (".$val.")<br>";
         if(is_array($val)){
            $display_data = serialize($_POST[$var]);
            mysql_query("UPDATE promo_boxes SET $var = '$display_data' WHERE BOX = '$box'");
            //echo "var = (".$var.") val = (".$display_data.")<br>";
         }
      }
   }
   if( !eregi("DISP_CONTENT", $colNames) ) {
      mysql_query("UPDATE promo_boxes SET DISP_CONTENT = 'a:2:{s:7:\"display\";s:0:\"\";s:6:\"border\";s:0:\"\";}' WHERE BOX = '$box'");
   }
   if( !eregi("NUM_DISPLAY", $colNames) ) {
      mysql_query("UPDATE promo_boxes SET NUM_DISPLAY = 'a:2:{s:4:\"blog\";s:0:\"\";s:5:\"chars\";s:0:\"\";}' WHERE BOX = '$box'");
   }
   if( !eregi("SETTINGS", $colNames) ) {
      mysql_query("UPDATE promo_boxes SET SETTINGS = 'a:1:{s:8:\"template\";s:0:\"\";}' WHERE BOX = '$box'");
   }
}



# Start buffering output
ob_start();
?>

<script language="javascript">


//---------------------------------------------------------------------------------------------------------
//      _      _   _   __  __
//     /_\  _ | | /_\  \ \/ /
//    / _ \| || |/ _ \  >  <
//   /_/ \_\\__//_/ \_\/_/\_\
//
//---------------------------------------------------------------------------------------------------------
// The following script (as commonly seen in other AJAX javascripts) is used to detect which browser the client is using.
// If the browser is Internet Explorer we make the object with ActiveX.
// (note that ActiveX must be enabled for it to work in IE)
function makeObject() {
   var x;
   var browser = navigator.appName;

   if ( browser == "Microsoft Internet Explorer" ) {
      x = new ActiveXObject("Microsoft.XMLHTTP");
   } else {
      x = new XMLHttpRequest();
   }

   return x;
}

// The javascript variable 'request' now holds our request object.
// Without this, there's no need to continue reading because it won't work ;)
var request = makeObject();

function ajaxDo(qryString, boxid) {
   //alert(qryString+', '+boxid);

   rezBox = boxid; // Make global so parseInfo can get it

   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
   request.open('get', qryString);

   // This tells the script to call parseInfo() when the ready state is changed
   request.onreadystatechange = parseInfo;

   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
   request.send('');

}

function parseInfo() {
   // Loading
   if ( request.readyState == 1 ) {
      document.getElementById(rezBox).innerHTML = 'Loading...';
   }

   // Finished
   if ( request.readyState == 4 ) {
      var answer = request.responseText;
      document.getElementById(rezBox).innerHTML = answer;
   }
}

// End Ajax


//#######################################################
//### promo_pref.php functions                        ###
//#######################################################


function dispNumEnt(){
   var selType = document.getElementById('dispType').selectedIndex;
   var selValue = eval("document.getElementById('dispType').options["+selType+"].value");
   //alert(selValue);
   if(selValue == "muli"){
      document.getElementById('NUM_DISPLAY').disabled=false;
      document.getElementById('NUM_DISPLAY').style.backgroundColor='#FFFFFF';
   }else{
      document.getElementById('NUM_DISPLAY').disabled=true;
      document.getElementById('NUM_DISPLAY').style.backgroundColor='#CCCCCC';
      document.getElementById('NUM_DISPLAY').value='';
   }
}

function dispNumChar(){
   if(document.getElementById('limitChar').checked){
      document.getElementById('NUM_LIMIT').disabled=false;
      document.getElementById('NUM_LIMIT').style.backgroundColor='#FFFFFF';
   }else{
      document.getElementById('NUM_LIMIT').disabled=true;
      document.getElementById('NUM_LIMIT').style.backgroundColor='#CCCCCC';
      document.getElementById('NUM_LIMIT').value='';
   }
}

function createTable(item, boxId, setting){
   if(setting == "border"){
      if(document.getElementById(item).checked){
         document.getElementById(boxId).style.border='1px solid #666666';
      }else{
         document.getElementById(boxId).style.border='';
      }
   }else{
      if(document.getElementById(item).checked){
         document.getElementById(boxId).style.display='block';
      }else{
         document.getElementById(boxId).style.display='none';
      }
   }
}

function cancelEdit(){
   document.getElementById('pref').style.display='none';
   document.getElementById('selBox').style.display='block';
   document.getElementById('tab_interface_container').style.display='block'; // Container div (layout selector tabs, box list, etc.)
   document.getElementById('fileBoxes').style.display='block';
}

function showMore(){
   document.getElementById('moreSettings').style.display='block';
}

function swapImg(tab){
   //alert('something');
   if(tab == "tab1"){
      document.getElementById(tab).src='images/settings_tabs_front.gif';
      document.getElementById('tab2').src='images/settings_tabs_middle-cont.gif';
      document.getElementById('tab3').src='images/settings_tabs_middle-read.gif';
      document.getElementById('tab4').src='images/settings_tabs_back.gif';
      document.getElementById('tabTitle').style.display='block';
      document.getElementById('tabCont').style.display='none';
      document.getElementById('tabRead').style.display='none';
      document.getElementById('tabDate').style.display='none';
      //document.getElementById('tabStyle').style.display='none';
   }
   if(tab == "tab2"){
      document.getElementById('tab1').src='images/settings_tabs_front-back.gif';
      document.getElementById(tab).src='images/settings_tabs_middle-top-co.gif';
      document.getElementById('tab3').src='images/settings_tabs_middle-read.gif';
      document.getElementById('tab4').src='images/settings_tabs_back.gif';
      document.getElementById('tabCont').style.display='block';
      document.getElementById('tabTitle').style.display='none';
      document.getElementById('tabRead').style.display='none';
      document.getElementById('tabDate').style.display='none';
      //document.getElementById('tabStyle').style.display='none';
   }
   if(tab == "tab3"){
      document.getElementById('tab1').src='images/settings_tabs_front-back.gif';
      document.getElementById('tab2').src='images/settings_tabs_middle-opp-co.gif';
      document.getElementById(tab).src='images/settings_tabs_middle-top-re.gif';
      document.getElementById('tab4').src='images/settings_tabs_back.gif';
      document.getElementById('tabRead').style.display='block';
      document.getElementById('tabCont').style.display='none';
      document.getElementById('tabTitle').style.display='none';
      document.getElementById('tabDate').style.display='none';
      //document.getElementById('tabStyle').style.display='none';
   }
   if(tab == "tab4"){
      document.getElementById('tab1').src='images/settings_tabs_front-back.gif';
      document.getElementById('tab2').src='images/settings_tabs_middle-opp-co.gif';
      document.getElementById('tab3').src='images/settings_tabs_middle-opp-re.gif';
      document.getElementById(tab).src='images/settings_tabs_back-front.gif';
      document.getElementById('tabDate').style.display='block';
      document.getElementById('tabCont').style.display='none';
      document.getElementById('tabRead').style.display='none';
      document.getElementById('tabTitle').style.display='none';
      //document.getElementById('tabStyle').style.display='none';
   }
}

function AlignPreview(dis, disValue){
	if(dis=='date'){
		document.getElementById('dateTop').align=disValue;
		document.getElementById('dateBottom').align=disValue;
	}else{
    document.getElementById(dis).align=disValue;
  }
}

function FontPreview(dis, disValue){
	if(dis == 'dateBottom'){
		document.getElementById(dis).style.fontWeight=disValue;
		document.getElementById('dateFirst').style.fontWeight=disValue;
		document.getElementById('dateLast').style.fontWeight=disValue;
	}
  document.getElementById(dis).style.fontWeight=disValue;
}

function BorderPreview(dis, disValue){
   if(document.all){
   	if(dis=='date'){
      if(document.getElementById('dateTop').style.border == '#666666 1px solid'){
         document.getElementById('dateTop').style.border='';
         document.getElementById('dateBottom').style.border='';
      }else{
         document.getElementById('dateTop').style.border='1px solid #666666';
         document.getElementById('dateBottom').style.border='1px solid #666666';
      }
    }else{
      if(document.getElementById(dis).style.border == '#666666 1px solid'){
         document.getElementById(dis).style.border='';
      }else{
         document.getElementById(dis).style.border='1px solid #666666';
      }
    }
   }else{
   	if(dis=='date'){
      if(document.getElementById('dateTop').style.border == disValue){
         document.getElementById('dateTop').style.border='';
         document.getElementById('dateBottom').style.border='';
      }else{
         document.getElementById('dateTop').style.border=disValue;
         document.getElementById('dateBottom').style.border=disValue;
      }
    }else{
      if(document.getElementById(dis).style.border == disValue){
         document.getElementById(dis).style.border='';
      }else{
         document.getElementById(dis).style.border=disValue;
      }
    }
   }
}


function showText(curText){
	document.getElementById('more').innerHTML = curText;
}

function LocNow(disLoc){
	//alert(disLoc);
	if(disLoc == 'dateFirst'){
		if(document.getElementById('DISP_DATE').checked==true){
			document.getElementById(disLoc).style.display='inline';
		}else{
			document.getElementById(disLoc).style.display='none';
		}
		document.getElementById('BordDate1').style.display='none';
		document.getElementById('BordDate2').style.display='none';
		document.getElementById('AlignDate1').style.display='none';
		document.getElementById('AlignDate2').style.display='none';

		document.getElementById('dateBottom').style.display='none';
		document.getElementById('dateLast').style.display='none';
		document.getElementById('dateTop').style.display='none';
	}
	if(disLoc == 'dateLast'){
		if(document.getElementById('DISP_DATE').checked==true){
			document.getElementById(disLoc).style.display='inline';
		}else{
			document.getElementById(disLoc).style.display='none';
		}
		document.getElementById('BordDate1').style.display='none';
		document.getElementById('BordDate2').style.display='none';
		document.getElementById('AlignDate1').style.display='none';
		document.getElementById('AlignDate2').style.display='none';
		document.getElementById('dateBottom').style.display='none';
		document.getElementById('dateFirst').style.display='none';
		document.getElementById('dateTop').style.display='none';
	}
	if(disLoc == 'dateBottom'){
		if(document.getElementById('DISP_DATE').checked==true){
			document.getElementById(disLoc).style.display='block';
		}else{
			document.getElementById(disLoc).style.display='none';
		}
		document.getElementById('BordDate1').style.display='block';
		document.getElementById('BordDate2').style.display='block';
		document.getElementById('AlignDate1').style.display='block';
		document.getElementById('AlignDate2').style.display='block';
		document.getElementById('dateFirst').style.display='none';
		document.getElementById('dateLast').style.display='none';
		document.getElementById('dateTop').style.display='none';
	}
	if(disLoc == 'dateTop'){
		if(document.getElementById('DISP_DATE').checked==true){
			document.getElementById(disLoc).style.display='block';
		}else{
			document.getElementById(disLoc).style.display='none';
		}
		document.getElementById('BordDate1').style.display='block';
		document.getElementById('BordDate2').style.display='block';
		document.getElementById('AlignDate1').style.display='block';
		document.getElementById('AlignDate2').style.display='block';
		document.getElementById('dateFirst').style.display='none';
		document.getElementById('dateLast').style.display='none';
		document.getElementById('dateBottom').style.display='none';
	}
	if(disLoc == 'all'){
		if(document.getElementById('DISP_DATE').checked==false){
			document.getElementById('dateBottom').style.display='none';
			document.getElementById('dateFirst').style.display='none';
			document.getElementById('dateLast').style.display='none';
			document.getElementById('dateTop').style.display='none';
		}else{
			if(document.getElementById('dateLoc').value=='dateFirst'){
				document.getElementById('dateBottom').style.display='none';
				document.getElementById('dateFirst').style.display='inline';
				document.getElementById('dateLast').style.display='none';
				document.getElementById('dateTop').style.display='none';
			}
			if(document.getElementById('dateLoc').value=='dateLast'){
				document.getElementById('dateBottom').style.display='none';
				document.getElementById('dateFirst').style.display='none';
				document.getElementById('dateLast').style.display='inline';
				document.getElementById('dateTop').style.display='none';
			}
			if(document.getElementById('dateLoc').value=='dateBottom'){
				document.getElementById('dateBottom').style.display='block';
				document.getElementById('dateFirst').style.display='none';
				document.getElementById('dateLast').style.display='none';
				document.getElementById('dateTop').style.display='none';
			}
			if(document.getElementById('dateLoc').value=='dateTop'){
				document.getElementById('dateBottom').style.display='none';
				document.getElementById('dateFirst').style.display='none';
				document.getElementById('dateLast').style.display='none';
				document.getElementById('dateTop').style.display='block';
			}
		}
	}
}

function formatNow(disLoc){
	if(disLoc == 'allNum'){
		document.getElementById('dateTop').innerHTML='2010-1-1';
		document.getElementById('dateBottom').innerHTML='2010-1-1';
		document.getElementById('dateFirst').innerHTML='2010-1-1&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		document.getElementById('dateLast').innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2010-1-1';
	}
	if(disLoc == 'allNum2'){
		document.getElementById('dateTop').innerHTML='1-1-2010';
		document.getElementById('dateBottom').innerHTML='1-1-2010';
		document.getElementById('dateFirst').innerHTML='1-1-2010&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		document.getElementById('dateLast').innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1-1-2010';
	}
	if(disLoc == 'full'){
		document.getElementById('dateTop').innerHTML='January 1 2010';
		document.getElementById('dateBottom').innerHTML='January 1 2010';
		document.getElementById('dateFirst').innerHTML='January 1 2010&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		document.getElementById('dateLast').innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;January 1 2010';
	}
	if(disLoc == 'full2'){
		document.getElementById('dateTop').innerHTML='1 January 2010';
		document.getElementById('dateBottom').innerHTML='1 January 2010';
		document.getElementById('dateFirst').innerHTML='1 January 2010&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		document.getElementById('dateLast').innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 January 2010';
	}
	if(disLoc == 'half'){
		document.getElementById('dateTop').innerHTML='Jan 1 2010';
		document.getElementById('dateBottom').innerHTML='Jan 1 2010';
		document.getElementById('dateFirst').innerHTML='Jan 1 2010&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		document.getElementById('dateLast').innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Jan 1 2010';
	}
	if(disLoc == 'half2'){
		document.getElementById('dateTop').innerHTML='1 Jan 2010';
		document.getElementById('dateBottom').innerHTML='1 Jan 2010';
		document.getElementById('dateFirst').innerHTML='1 Jan 2010&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		document.getElementById('dateLast').innerHTML='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 Jan 2010';
	}
}

function dateStyle(){
	if(document.getElementById('styleDate').checked){
		document.getElementById('dateBottom').style.fontStyle='italic';
		document.getElementById('dateFirst').style.fontStyle='italic';
		document.getElementById('dateLast').style.fontStyle='italic';
		document.getElementById('dateTop').style.fontStyle='italic';
	}else{
		document.getElementById('dateBottom').style.fontStyle='';
		document.getElementById('dateFirst').style.fontStyle='';
		document.getElementById('dateLast').style.fontStyle='';
		document.getElementById('dateTop').style.fontStyle='';
	}
}

function modSettings(useDis){

	//document.getElementById('myStyle').href="../site_templates/pages/LANDSCAPE-Mountains_Man-Blue/joe.css";
	//alert(document.getElementById('myStyle').href);
//	if(useDis == 'useMine'){
//		document.getElementById('useTempStyles').checked=false;
//		document.getElementById('styles').style.display='none';
//		document.getElementById('styles2').style.display='block';
//
//		document.getElementById('moreSettings').style.display='block';
//		document.getElementById('text1').style.visibility='visible';
//		document.getElementById('text2').style.visibility='visible';
//		document.getElementById('prevDisp').style.display='block';
//	}else{
//		document.getElementById('styles').style.display='block';
//		document.getElementById('styles2').style.display='none';
//
//		document.getElementById('moreSettings').style.display='none';
//		document.getElementById('text1').style.visibility='hidden';
//		document.getElementById('text2').style.visibility='hidden';
//		document.getElementById('prevDisp').style.display='none';
//	}

}


//#######################################################
//### End promo_pref.php functions                    ###
//#######################################################

function boxPref(boxType, boxNum){
   var selValue = document.getElementById(boxNum+'_content_dd').selectedIndex;
   var selContValue = eval("document.getElementById(boxNum+'_content_dd').options["+selValue+"].value");

   // Hide container div (layout selector tabs, box list, etc.)
   document.getElementById('tab_interface_container').style.display='none';

   document.getElementById('selBox').style.display='none';
   document.getElementById('pref').style.display='block';
   if ( document.getElementById(boxNum+'_content_type').value == "disabled" ){
   	 ajaxDo('promo_pref.php?box='+boxNum+'&cont='+selContValue+'&disp=off', 'pref');
   }else{
   	 ajaxDo('promo_pref.php?box='+boxNum+'&cont='+selContValue+'&disp=on', 'pref');
   }
   document.getElementById('fileBoxes').style.display='none';
}


function getBox(box){
   var box1 = document.getElementById(box).innerHTML;
   alert(box1);
}

function boxIt(box){
   document.getElementById(box).style.backgroundColor="#CCCCCC";
}

function unboxIt(box){
   document.getElementById(box).style.backgroundColor="";
}

function showPref(box){
   document.getElementById(box).style.display='block';
}


// Change blog category associated with box
// Called onChange from dropdown box
function update_cat(dropdown_id){
   // Strip id name to get actual box number (box1, box2, etc)
   var box = dropdown_id.replace('_content_dd', '');

   // Pull value of content type dd
   var content_type_boxid = box+'_content_type';
   var new_content_type = $(content_type_boxid).value;

   // Get the new content value
   var newcat = document.getElementById(dropdown_id).value;
   //alert('box: ['+box+']\nnewcat: ['+newcat+']');

   // Redirect to self and trigger update routine (todo=update_category)
   var goHere = "promo_boxes.php?todo=update_category&box="+box+"&newcat="+newcat+"&new_content_type="+new_content_type;
   window.location = goHere;
}

function disableBox(daKey) {
	var goHere = "promo_boxes.php?do=disThis&daKey="+daKey;

	if ( document.getElementById('DISP_'+daKey).checked==true ) {
		var uSure = confirm("This box will be disabled, would you like to continue?");
	} else {
		var uSure = confirm("This box will be enabled, would you like to continue?");
	}

	if ( uSure ) {
		if ( document.getElementById('DISP_'+daKey).checked==true ) {
		   goHere += "&newvalue=off";
		} else {
			goHere += "&newvalue=on";
		}
		window.location = goHere;

	} else {
		if ( document.getElementById('DISP_'+daKey).checked==true ) {
			document.getElementById('DISP_'+daKey).checked=false;
		} else {
			document.getElementById('DISP_'+daKey).checked=true;
		}
	}

}

<?

//echo "function goHere(){\n";
//	if($_POST['do']){
//		echo "document.getElementById('".$box."_content_dd').value = '".$CONTENT['content']."'\n";
//
//	 if($CONTENT['display'] == "off"){
//	 	 echo "document.getElementById('DISP_".$box."').checked = true;\n";
//	 }else{
//	 	 echo "document.getElementById('DISP_".$box."').checked = false;\n";
//	 }
//	}else if($_GET['do'] == "disThis"){
//		// Disable/Enable box
//	 if($disable_hook == "off"){
//	 	 echo "document.getElementById('DISP_".$daKey."').checked = true;\n";
//	 }else{
//	 	 echo "document.getElementById('DISP_".$daKey."').checked = false;\n";
//	 }
//	}
//
//echo "}\n";
?>


</script>
<style type="text/css">
.joe { color: red; }
.style1 { font-size: 16px;	color: #0000CC; }
.thinBox {

  	border-style: solid;
  	border-width: 1px;
  	border-color: #666666;
  	font-size: 10px;
	color: #666666;

}
</style>
<style>
.tab-off, .tab-on {
   position: absolute;
   top: -25px;
   z-index: 2;
   text-align: center;
   width: 125px;
   /*height: 25px;*/
   vertical-align: top;
   padding-top: 5px;
   padding-bottom: 5px;
   margin-right: 15px;
   background-color: #efefef;
   border: 1px solid #ccc;
   border-top: 3px solid #ccc;
   color: #595959;
   cursor: pointer;
}

.tab-on {
   color: #000;
   background-color: #efefef;
   border-top: 3px solid #175aaa;
   font-weight: bold;
}

/* Table containing content for each tab */
table.tab_content {
   /*margin: 40px 5px 20px 5px;*/
   border: 1px solid #ccc;
   margin-left: 5px;
   /*width: 100%;*/
   /*position: relative;*/
}

#layout_tab1 { left: 5px; }
#layout_tab2 { left: 140px; }
#layout_tab3 { left: 275px; }
#layout_tab4 { left: 410px; }

.backbar_hover { background-color: #aeecbb; }
</style>


<script type="text/javascript">
// Shows appropriate drop down based on what content type is selected
// Example: If blog, show categories; if sitepal, show scenes
// idnum = $key value from loop iteration
function boxContent(idnum, content_type) {
   var thisHTML = '';

   // Put together involved element id names
   container_id = 'container-boxcontent_dd-'+idnum;

   if ( content_type == "blog" ) {
      // BLOG content
      thisHTML += '<select id="'+idnum+'_content_dd" name="'+idnum+'_content_dd" class="thinBox" onchange="saveBtn(\''+idnum+'\');">\n';
      thisHTML += ' <? echo str_replace("\n", "\\n", addslashes($tmpCats)); ?>\n';
      thisHTML += '</select>\n';

      // Show edit settings button
      $(idnum+'_edit_btn-blog').style.display = 'block';

   } else if ( content_type == "disable"  ) {
      // DISABLE
      thisHTML += '<input type="hidden" id="'+idnum+'_content_dd" name="'+idnum+'_content_dd" value="disable">\n';
      thisHTML += 'Box will be disabled/empty.\n';

      // Hide both buttons
      $(idnum+'_edit_btn-blog').style.display = 'none';
   }

   // Set inner html of container element
   $(container_id).innerHTML = thisHTML;

} // End boxContent() function

// Save/Cancel btns appear when option is changed and display settings button is disabled
function saveBtn(keynum) {
   var contenttype_dd = keynum+'_content_type';
   var boxtype = $(contenttype_dd).value;

   // Show Save/Cancel for this box?
   savebtnid = keynum+'_savebtn';
   $(savebtnid).style.display = 'inline';

   // Disable which edit button?
   if ( boxtype != "disable" ) {
      // Disable whichever box type is shown
      $(keynum+'_edit_btn-'+boxtype).disabled = true;
      $(keynum+'_edit_btn-'+boxtype).style.color = '#888c8e';
      $(keynum+'_edit_btn-'+boxtype).style.fontStyle = 'italic';
   } else {
      // Hide both buttons
      $(keynum+'_edit_btn-blog').style.display = 'none';
   }
} // End saveBtn() function
</script>


    <!---Preferences block-->
    <div id="pref" style="border: 0px solid red;"></div>

    <!---Container div-->
    <div id="tab_interface_container" style="display: block; width: 98%;margin: 40px 5px 20px 5px;position: relative; border: 0px solid red;">

     <!---================== Tabs - START ==================-->
     <div id="layout_tab1" class="tab-on" onclick="showid('tab1-content');hideid('tab2-content');hideid('tab3-content');hideid('tab4-content');setClass('layout_tab1', 'tab-on');setClass('layout_tab2', 'tab-off');setClass('layout_tab3', 'tab-off');setClass('layout_tab4', 'tab-off');">
      Site Base Template
     </div>

     <div id="layout_tab2" class="tab-off" onclick="showid('tab2-content');hideid('tab1-content');hideid('tab3-content');hideid('tab4-content');setClass('layout_tab2', 'tab-on');setClass('layout_tab1', 'tab-off');setClass('layout_tab3', 'tab-off');setClass('layout_tab4', 'tab-off');">
      Home Page
     </div>

     <div id="layout_tab3" class="tab-off" onclick="showid('tab3-content','table');hideid('tab1-content');hideid('tab2-content');hideid('tab4-content');setClass('layout_tab1', 'tab-off');setClass('layout_tab2', 'tab-off');setClass('layout_tab3', 'tab-on');setClass('layout_tab4', 'tab-off');">
      Shopping Cart
     </div>

     <div id="layout_tab4" class="tab-off" onclick="showid('tab4-content');hideid('tab1-content');hideid('tab2-content');hideid('tab3-content');setClass('layout_tab1', 'tab-off');setClass('layout_tab2', 'tab-off');setClass('layout_tab3', 'tab-off');setClass('layout_tab4', 'tab-on');">
      News/Blog Article
     </div>
     <!---================== Tabs - END ==================-->


<?
/*---------------------------------------------------------------------------------------------------------*
 _
| |_   ___  _ __   ___
| ' \ / _ \| '  \ / -_)
|_||_|\___/|_|_|_|\___|

/*---------------------------------------------------------------------------------------------------------*/
?>
    <!---home.html-->
    <table id="tab2-content" border="0" cellspacing="0" cellpadding="5" class="feature_sub tab_content" style="display: none;">
     <tr>
      <td valign="top" class="nopad_btm">
       <p>The <b>Home Page</b> template is a special layout specifically designed for the first page visitors see when arriving at your website (i.e. 'home page', 'start page', 'index page', etc.)</p>

       <p>Number of boxes in your Home Page Layout: [<span class="red"><? echo $homeCnt; ?></span>]<br/>
        <a href="../site_templates/pages/<? echo $CUR_TEMPLATE; ?>/home.html" target="_blank">View raw template skeleton so you can see where each box is placed</a></p>
      </td>
     </tr>
      <tr>
       <td valign="top">

        <div name="selBox" id="selBox">
         <table width="100%" border="0" cellspacing="0" cellpadding="4">
           <tr>
             <td>&nbsp;</td>
             <td><b><? echo lang("What do you want to put in this box?"); ?></b></td>
             <td>&nbsp;</td>
           </tr>

          <!--****  home.html row  ****-->

<?
$cnt = 1;
foreach ( $homefile as $key=>$val ) {
   foreach ( $val as $disvar=>$datval ) {
      foreach ( $datval as $disvar2=>$datval2 ) {
         if ( $disvar2 == "display") {
            if ( $datval2 == "off" ) {
               $disableBox = " selected";
               $disabled_keys['home'] .= $key.";"; // Back compat: So new js doesn't overwrite setting from content_type = blog default set on v4.91 update
            } else {
               $disableBox = "";
            }
         }
      }
   }

   # Alternate bg color
   if ( $bgclass == "bg_blue_f8" ) { $bgclass = "bg_gray_ef"; } else { $bgclass = "bg_blue_f8"; }

   echo "           <tr class=\"".$bgclass." row_spliter\">\n";

   # title
   echo "            <td>Box".$cnt.": <b>".$homefile[$key]['FUTURE1']['title']."</b></td>\n";

   # category dropdown & Save button
   echo "            <td>\n";
   echo "             <select id=\"".$key."_content_type\" name=\"".$key."_content_type\" class=\"thinBox\" style=\"width: 200px;\" onchange=\"boxContent('".$key."', this.value);saveBtn('".$key."');\">\n";
   echo "              <option value=\"disable\"".$disableBox.">".lang("Nothing (disable)")."</option>\n";
   echo "              <option value=\"blog\">".lang("Content from Blog Manager")."</option>\n";

   echo "             </select><br/>\n";

   echo "             <span id=\"container-boxcontent_dd-".$key."\">\n";
   # Default to showing blog category select
   echo "              <select id=\"".$key."_content_dd\" name=\"".$key."_content_dd\" class=\"thinBox\" onchange=\"saveBtn('".$key."');\">\n";
//   echo "               <option value=\"\" selected>".lang("Which blog category")."?</option>\n";
   echo "               ".$tmpCats;
   echo "              </select>\n";
   echo "             </span>\n";

   # [save]
   echo "             <input id=\"".$key."_savebtn\" type=\"button\" ".$_SESSION['btn_build']." onclick=\"update_cat('".$key."_content_dd');\" value=\"".lang("save")."\" style=\"display: none;\">\n";
   echo "            </td>\n";

   # prefs button
   echo "            <td align=\"center\">\n";
   //if ( $homefile[$key]['content_type'] == "sitepal" ) { $sp_edit_display = "inline"; $blog_edit_display = "none"; } else { $sp_edit_display = "none"; $blog_edit_display = "inline"; }

   # [ sitepal styles ]
  // echo "             <input id=\"".$key."_edit_btn-sitepal\" type=\"button\" ".$_SESSION['btn_edit']." onclick=\"showid('popconfig-sitepal_style');\" value=\"".lang("SitePal scene settings")."\" style=\"display: ".$sp_edit_display.";\">\n";

   # [ Edit display settings ]
   echo "             <input id=\"".$key."_edit_btn-blog\" type=\"button\" ".$_SESSION['btn_edit']." name=\"pref_butt\" onClick=\"boxPref('home', '".$key."');\" value=\"".lang("Edit display settings")."\" style=\"display: ".$blog_edit_display.";\">\n";
   echo "            </td>\n";
   echo "           </tr>\n";
   $cnt++;

} // End foreach box in this template
?>
      </table>
     </div>

    </td>
   </tr>
  </table>


<?
/*---------------------------------------------------------------------------------------------------------*
 _           _
(_) _ _   __| | ___ __ __
| || ' \ / _` |/ -_)\ \ /
|_||_||_|\__,_|\___|/_\_\

/*---------------------------------------------------------------------------------------------------------*/
?>
     <!---index.html-->
     <table id="tab1-content" border="0" cellspacing="0" cellpadding="5" class="feature_sub tab_content" style="display: table;">
      <tr>
       <td valign="top" class="nopad_btm">
        <p>Your <b>Site Base Template</b> is your primary or 'default' design layout that is used on all (or nearly all) of your site pages.
        As in, when you create a new page, it will use this template unless you specifically set it to use a different one via the Page Properties menu in the Page Editor.</p>

        <p>Number of boxes in your Site Base Template: [<span class="red"><? echo $indexCnt; ?></span>]<br/>
         <a href="../site_templates/pages/<? echo $CUR_TEMPLATE; ?>/index.html" target="_blank">View raw template skeleton so you can see where each box is placed</a></p>
       </td>
      </tr>
      <tr>
       <td valign="top">

        <div name="selBox" id="selBox">
         <table width="100%" border="0" cellspacing="0" cellpadding="4">
           <tr>
             <td>&nbsp;</td>
             <td><b><? echo lang("What do you want to put in this box?"); ?></b></td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
           </tr>

          <!--****  index.html row  ****-->

<?
$cnt = 1;
$disabled_keys['index'] = "";
foreach ( $indexfile as $key=>$val ) {
   foreach ( $val as $disvar=>$datval ) {
      foreach ( $datval as $disvar2=>$datval2 ) {
         if ( $disvar2 == "display") {
            if ( $datval2 == "off" ) {
               $disableBox = " selected";
               $disabled_keys['index'] .= $key.";"; // Back compat: So new js doesn't overwrite setting from content_type = blog default set on v4.91 update
            } else {
               $disableBox = "";
            }
         }
      }
   }

   # Alternate bg color
   if ( $bgclass == "bg_blue_f8" ) { $bgclass = "bg_gray_ef"; } else { $bgclass = "bg_blue_f8"; }

   echo "           <tr class=\"".$bgclass." row_spliter\">\n";

   # title
//   if(!$_POST['FUTURE1']['title']){
      echo "            <td>Box".$cnt.": <b>".$indexfile[$key]['FUTURE1']['title']."</b></td>\n";
//   }else{
//      echo "            <td>Box".$cnt.": <b>".$_POST['FUTURE1']['title']."</b></td>\n";
//   }

   # category dropdown & Save button
   echo "            <td>\n";
   echo "             <select id=\"".$key."_content_type\" name=\"".$key."_content_type\" class=\"thinBox\" style=\"width: 200px;\" onchange=\"boxContent('".$key."', this.value);saveBtn('".$key."');\">\n";
   echo "              <option value=\"disable\"".$disableBox.">".lang("Nothing (disable)")."</option>\n";
   echo "              <option value=\"blog\">".lang("Content from Blog Manager")."</option>\n";
//   $_SESSION['sitepal_verified'] = false;
//   if ( sitepal_features() ) {
//      echo "              <option value=\"sitepal\">".lang("SitePal virtual character")."</option>\n";
//   }
   echo "             </select><br/>\n";

   echo "             <span id=\"container-boxcontent_dd-".$key."\" onchange=\"saveBtn('".$key."');\">\n";
   # Default to showing blog category select
   echo "              <select id=\"".$key."_content_dd\" name=\"".$key."_content_dd\" class=\"thinBox\">\n";
//   echo "               <option value=\"\" selected>".lang("Which blog category")."?</option>\n";
   echo "               ".$tmpCats;
   echo "              </select>\n";
   echo "             </span>\n";

   # [save]
   echo "             <input id=\"".$key."_savebtn\" type=\"button\" ".$_SESSION['btn_build']." onclick=\"update_cat('".$key."_content_dd');\" value=\"".lang("save")."\" style=\"display: none;\">\n";
   echo "            </td>\n";

   # prefs button
   echo "            <td align=\"center\">\n";
  // if ( $indexfile[$key]['content_type'] == "sitepal" ) { $sp_edit_display = "inline"; $blog_edit_display = "none"; } else { $sp_edit_display = "none"; $blog_edit_display = "inline"; }

   # [ sitepal styles ]
//   echo "             <input id=\"".$key."_edit_btn-sitepal\" type=\"button\" ".$_SESSION['btn_edit']." onclick=\"showid('popconfig-sitepal_style');\" value=\"".lang("SitePal scene settings")."\" style=\"display: ".$sp_edit_display.";\">\n";

   # [ Edit display settings ]
   echo "             <input id=\"".$key."_edit_btn-blog\" type=\"button\" ".$_SESSION['btn_edit']." name=\"pref_butt\" onClick=\"boxPref('index', '".$key."');\" value=\"".lang("Edit display settings")."\" style=\"display: ".$blog_edit_display.";\">\n";
   echo "            </td>\n";
   echo "           </tr>\n";
   $cnt++;

} // End foreach box in this template
?>
      </table>
     </div>

    </td>
   </tr>
  </table>


<?
/*---------------------------------------------------------------------------------------------------------*
                _
 __  __ _  _ _ | |_
/ _|/ _` || '_||  _|
\__|\__,_||_|   \__|

/*---------------------------------------------------------------------------------------------------------*/
?>
    <!---cart.html-->
    <table id="tab3-content" align="center" border="0" cellspacing="0" cellpadding="5" class="feature_sub tab_content" style="display: none;">
     <tr>
      <td colspan="2" valign="top" class="nopad_btm">
       <p>The <b>Shopping Cart</b> template is a special layout specifically designed to be used on pages that are part of the shopping cart check out process (i.e. when visitor is going through your checkout process to purchase one of your products).</p>

       <p>Number of boxes in your Shopping Cart layout: [<span class="red"><? echo $cartCnt; ?></span>]<br/>
        <a href="../site_templates/pages/<? echo $CUR_TEMPLATE; ?>/cart.html" target="_blank">View raw template skeleton so you can see where each box is placed</a></p>
      </td>
     </tr>
      <tr>
       <td valign="top">

        <div name="selBox" id="selBox">
         <table width="100%" border="0" cellspacing="0" cellpadding="4">
           <tr>
             <td>&nbsp;</td>
             <td><b><? echo lang("What do you want to put in this box?"); ?></b></td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
           </tr>

          <!--****  cart.html row  ****-->

<?
$cnt = 1;
foreach ( $cartfile as $key=>$val ) {
   foreach ( $val as $disvar=>$datval ) {
      foreach ( $datval as $disvar2=>$datval2 ) {
         if ( $disvar2 == "display") {
            if ( $datval2 == "off" ) {
               $disableBox = " selected";
               $disabled_keys['cart'] .= $key.";"; // Back compat: So new js doesn't overwrite setting from content_type = blog default set on v4.91 update
            } else {
               $disableBox = "";
            }
         }
      }
   }

   # Alternate bg color
   if ( $bgclass == "bg_blue_f8" ) { $bgclass = "bg_gray_ef"; } else { $bgclass = "bg_blue_f8"; }

   echo "           <tr class=\"".$bgclass." row_spliter\">\n";

   # title
   echo "            <td>Box".$cnt.": <b>".$cartfile[$key]['FUTURE1']['title']."</b></td>\n";

   # category dropdown & Save button
   echo "            <td>\n";
   echo "             <select id=\"".$key."_content_type\" name=\"".$key."_content_type\" class=\"thinBox\" style=\"width: 200px;\" onchange=\"boxContent('".$key."', this.value);saveBtn('".$key."');\">\n";
   echo "              <option value=\"disable\"".$disableBox.">".lang("Nothing (disable)")."</option>\n";
   echo "              <option value=\"blog\">".lang("Content from Blog Manager")."</option>\n";
//   $_SESSION['sitepal_verified'] = false;
//   if ( sitepal_features() ) {
//      echo "              <option value=\"sitepal\">".lang("SitePal virtual character")."</option>\n";
//   }
   echo "             </select><br/>\n";

   echo "             <span id=\"container-boxcontent_dd-".$key."\">\n";
   # Default to showing blog category select
   echo "              <select id=\"".$key."_content_dd\" style=\"width: 200px;\" name=\"".$key."_content_dd\" class=\"thinBox\" onchange=\"saveBtn('".$key."');\">\n";
//   echo "               <option value=\"\" selected>".lang("Which blog category")."?</option>\n";
   echo "               ".$tmpCats;
   echo "              </select>\n";
   echo "             </span>\n";

   # [save]
   echo "             <input id=\"".$key."_savebtn\" type=\"button\" ".$_SESSION['btn_build']." onclick=\"update_cat('".$key."_content_dd');\" value=\"".lang("save")."\" style=\"display: none;\">\n";
   echo "            </td>\n";

   # prefs button
   echo "            <td align=\"center\">\n";
//   if ( $cartfile[$key]['content_type'] == "sitepal" ) { $sp_edit_display = "inline"; $blog_edit_display = "none"; } else { $sp_edit_display = "none"; $blog_edit_display = "inline"; }

   # [ sitepal styles ]
   //echo "             <input id=\"".$key."_edit_btn-sitepal\" type=\"button\" ".$_SESSION['btn_edit']." onclick=\"showid('popconfig-sitepal_style');\" value=\"".lang("SitePal scene settings")."\" style=\"display: ".$sp_edit_display.";\">\n";

   # [ Edit display settings ]
   echo "             <input id=\"".$key."_edit_btn-blog\" type=\"button\" ".$_SESSION['btn_edit']." name=\"pref_butt\" onClick=\"boxPref('cart', '".$key."');\" value=\"".lang("Edit display settings")."\" style=\"display: ".$blog_edit_display.";\">\n";
   echo "            </td>\n";
   echo "           </tr>\n";
   $cnt++;

} // End foreach box in this template
?>
      </table>
     </div>

    </td>
   </tr>
    </table>


<?
/*---------------------------------------------------------------------------------------------------------*
 _ _   ___ __ __ __ ___
| ' \ / -_)\ V  V /(_-<
|_||_|\___| \_/\_/ /__/

/*---------------------------------------------------------------------------------------------------------*/
?>
    <!---news.html-->
    <table id="tab4-content" align="center" border="0" cellspacing="0" cellpadding="5" class="feature_sub tab_content" style="display: none;">
     <tr>
      <td colspan="2" valign="top" class="nopad_btm">
       <p>This is a special layout specifically designed to show on your news/blog article pages. Example: Visitor clicks on "read more" link next to one of the items in a template box to see the full text of a headlined item.</p>

       <p>Number of boxes in your News Article layout: [<span class="red"><? echo $newsCnt; ?></span>]<br/>
        <a href="../site_templates/pages/<? echo $CUR_TEMPLATE; ?>/news.html" target="_blank">View raw template skeleton so you can see where each box is placed</a></p>
      </td>
     </tr>
      <tr>
       <td valign="top">

        <div name="selBox" id="selBox">
         <table width="100%" border="0" cellspacing="0" cellpadding="4">
           <tr>
             <td>&nbsp;</td>
             <td><b><? echo lang("What do you want to put in this box?"); ?></b></td>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           
          <!--****  news.html row  ****-->

<?
$cnt = 1;
foreach ( $newsfile as $key=>$val ) {
   foreach ( $val as $disvar=>$datval ) {
      foreach ( $datval as $disvar2=>$datval2 ) {
         if ( $disvar2 == "display") {
            if ( $datval2 == "off" ) {
               $disableBox = " selected";
               $disabled_keys['news'] .= $key.";"; // Back compat: So new js doesn't overwrite setting from content_type = blog default set on v4.91 update
            } else {
               $disableBox = "";
            }
         }
      }
   }

   # Alternate bg color
   if ( $bgclass == "bg_blue_f8" ) { $bgclass = "bg_gray_ef"; } else { $bgclass = "bg_blue_f8"; }

   echo "           <tr class=\"".$bgclass." row_spliter\">\n";

   # title
   echo "            <td>Box".$cnt.": <b>".$newsfile[$key]['FUTURE1']['title']."</b></td>\n";

   # category dropdown & Save button
   echo "            <td>\n";
   echo "             <select id=\"".$key."_content_type\" name=\"".$key."_content_type\" class=\"thinBox\" style=\"width: 200px;\" onchange=\"boxContent('".$key."', this.value);saveBtn('".$key."');\">\n";
   echo "              <option value=\"disable\"".$disableBox.">".lang("Nothing (disable)")."</option>\n";
   echo "              <option value=\"blog\">".lang("Content from Blog Manager")."</option>\n";

   echo "             </select><br/>\n";

   echo "             <span id=\"container-boxcontent_dd-".$key."\">\n";
   # Default to showing blog category select
   echo "              <select id=\"".$key."_content_dd\" style=\"width: 200px;\" name=\"".$key."_content_dd\" class=\"thinBox\" onchange=\"saveBtn('".$key."');\">\n";
//   echo "               <option value=\"\" selected>".lang("Which blog category")."?</option>\n";
   echo "               ".$tmpCats;
   echo "              </select>\n";
   echo "             </span>\n";

   # [save]
   echo "             <input id=\"".$key."_savebtn\" type=\"button\" ".$_SESSION['btn_build']." onclick=\"update_cat('".$key."_content_dd');\" value=\"".lang("save")."\" style=\"display: none;\">\n";
   echo "            </td>\n";

   # prefs button
   echo "            <td align=\"center\">\n";
//   if ( $newsfile[$key]['content_type'] == "sitepal" ) { $sp_edit_display = "inline"; $blog_edit_display = "none"; } else { $sp_edit_display = "none"; $blog_edit_display = "inline"; }



   # [ Edit display settings ]
   echo "             <input id=\"".$key."_edit_btn-blog\" type=\"button\" ".$_SESSION['btn_edit']." name=\"pref_butt\" onClick=\"boxPref('cart', '".$key."');\" value=\"".lang("Edit display settings")."\" style=\"display: ".$blog_edit_display.";\">\n";
   echo "            </td>\n";
   echo "           </tr>\n";
   $cnt++;

} // End foreach box in this template
?>
           
           

          <!--****  news.html row  ****-->

<?
//$cnt = 1;
//foreach ( $newsfile as $key=>$val ) {
//   foreach ( $val as $disvar=>$datval ) {
//      foreach ( $datval as $disvar2=>$datval2 ) {
//         if ( $disvar2 == "display") {
//            if ( $datval2 == "off" ) {
//               $disableBox = "checked";
//            } else {
//               $disableBox = "";
//            }
//         }
//      }
//   }
//
//   # Alternate bg color
//   if ( $bgclass == "bg_blue_f8" ) { $bgclass = "bg_gray_ef"; } else { $bgclass = "bg_blue_f8"; }
//
//   echo "           <tr class=\"".$bgclass." row_spliter\">\n";
//
//   # title
//   echo "            <td>Box".$cnt." - ".$newsfile[$key]['FUTURE1']['title']."</td>\n";
//
//   # category dropdown & Save button
//   echo "            <td>\n";
//   echo "             <select id=\"".$key."_content_dd\" name=\"".$key."_content_dd\" class=\"thinBox\">\n";
//   echo "              ".$tmpCats;
//   echo "             </select>\n";
//   echo "             <input type=\"button\" $btn_build onclick=\"update_cat('".$key."_content_dd');\" value=\"".lang("save")."\">\n";
//   echo "            </td>\n";
//
//   # disable checkbox
//   echo "            <td>\n";
//   echo "             <input ".$disableBox." id=\"DISP_".$key."\" type=\"checkbox\" onClick=\"disableBox('".$key."');\">\n";
//   echo "            </td>\n";
//
//   # prefs button
//   echo "            <td align=\"center\">\n";
//   echo "             <input type=\"button\" name=\"pref_butt\" $btn_edit onClick=\"boxPref('news', '".$key."');\" value=\"".lang("Edit display settings")."\">\n";
//   echo "            </td>\n";
//   echo "           </tr>\n";
//   $cnt++;
//
//} // End foreach box in this template
?>
      </table>
     </div>

    </td>
   </tr>
 </table>

</div>

<?
//echo "Disabled Keys: [".$disabled_keys['index']."]<br/>";

//echo "(".$CONTENT['content'].")";
//echo "(".$_POST['do'].")";
//echo testArray($disabled_keys['cart']);

# Populate/re-select with current settings from db
echo "<script language=\"javascript\">\n";

foreach ( $indexfile as $key=>$val ) {
   if ( !eregi($key.";", $disabled_keys['index']) ) {
      echo "boxContent('".$key."', '".$indexfile[$key]['content_type']."');\n";
      echo "document.getElementById('".$key."_content_type').value = '".$indexfile[$key]['content_type']."'\n";
      echo "document.getElementById('".$key."_content_dd').value = '".$indexfile[$key]['content_src']."'\n";
   } else {
      # Box disabled from pre-v4.91 setting
      echo "boxContent('".$key."', 'disable');\n";
      echo "document.getElementById('".$key."_content_type').value = 'disable'\n";
   }

}
foreach ( $homefile as $key=>$val ) {
   if ( !eregi($key.";", $disabled_keys['home']) ) {
      echo "boxContent('".$key."', '".$homefile[$key]['content_type']."');\n";
      echo "document.getElementById('".$key."_content_type').value = '".$homefile[$key]['content_type']."'\n";
      echo "document.getElementById('".$key."_content_dd').value = '".$homefile[$key]['content_src']."'\n";
   } else {
      # Box disabled from pre-v4.91 setting
      echo "boxContent('".$key."', 'disable');\n";
      echo "document.getElementById('".$key."_content_type').value = 'disable'\n";
   }
}
foreach ( $cartfile as $key=>$val ) {
   if ( !eregi($key.";", $disabled_keys['cart']) ) {
      echo "boxContent('".$key."', '".$cartfile[$key]['content_type']."');\n";
      echo "document.getElementById('".$key."_content_type').value = '".$cartfile[$key]['content_type']."';\n";
      echo "document.getElementById('".$key."_content_dd').value = '".$cartfile[$key]['content_src']."';\n";
   } else {
      # Box disabled from pre-v4.91 setting
      echo "boxContent('".$key."', 'disable');\n";
      echo "document.getElementById('".$key."_content_type').value = 'disable';\n";
   }
}
//foreach ( $cartfile as $key=>$val ) {
//   echo "document.getElementById('".$key."_content_dd').value = '".$cartfile[$key]['CONTENT']['content']."'\n";
//}

foreach ( $newsfile as $key=>$val ) {
   if ( !eregi($key.";", $disabled_keys['news']) ) {
      echo "boxContent('".$key."', '".$newsfile[$key]['content_type']."');\n";
      echo "document.getElementById('".$key."_content_type').value = '".$newsfile[$key]['content_type']."';\n";
      echo "document.getElementById('".$key."_content_dd').value = '".$newsfile[$key]['content_src']."';\n";
   } else {
      # Box disabled from pre-v4.91 setting
      echo "boxContent('".$key."', 'disable');\n";
      echo "document.getElementById('".$key."_content_type').value = 'disable';\n";
   }
}

//foreach ( $newsfile as $key=>$val ) {
//   echo "document.getElementById('".$key."_content_dd').value = '".$newsfile[$key]['CONTENT']['content']."'\n";
//}

# Do not show tabs for template layouts that don't exist for this template
//if ( $indexCnt < 1 ) { echo "document.getElementById('layout_tab1').style.display='none';\n"; } // Gotta at least show one
if ( $homeCnt < 1 ) { echo "document.getElementById('layout_tab2').style.display='none';\n"; }
if ( $cartCnt < 1 ) { echo "document.getElementById('layout_tab3').style.display='none';\n"; }
if ( $newsCnt < 1 ) { echo "document.getElementById('layout_tab4').style.display='none';\n"; }

# TESTING: Force to home layout tab
//echo "showid('tab2-content');hideid('tab1-content');hideid('tab3-content');hideid('tab4-content');setClass('layout_tab2', 'tab-on');setClass('layout_tab1', 'tab-off');setClass('layout_tab3', 'tab-off');setClass('layout_tab4', 'tab-off');\n";

echo "</script>\n";


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Template boxes are places in your website's design template (layout) that are set aside for specialized content like latest news items or special promotions.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Template Boxes";
$module->add_breadcrumb_link("Site Templates", "program/modules/site_templates.php");
$module->add_breadcrumb_link("Template Boxes", "program/modules/promo_boxes/promo_boxes.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/template_manager-enabled.gif";
$module->heading_text = "Template Boxes";
$module->description_text = $instructions;
$module->good_to_go();
?>