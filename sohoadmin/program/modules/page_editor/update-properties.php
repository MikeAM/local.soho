<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

require_once('../../includes/product_gui.php');


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc.  All Rights Reserved.
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

$sresult = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages where page_name = '$currentPage'");
$srow = mysql_fetch_array($sresult);

if($srow['username'] != "" && $srow['username'] != "NULL") {
   $pgsec = "1";
} else {
   $pgsec = "";
}

if($new_name) {
   $pge_request = $new_name;
   $pge_request = str_replace("&", "", $pge_request);
   $pge_request = str_replace("'", "", $pge_request);
   $pge_request = str_replace("\"", "", $pge_request);
   $pge_request = eregi_replace(" .", "_", $pge_request);
//   $pge_request = htmlspecialchars($pge_request, ENT_QUOTES);
//   echo $pge_request; exit;

} else {
   $pge_request = eregi_replace(" ", "_", $currentPage);
}

//$pge_request = trim($pge_request);

$page_content = $pge_request . ".con";
$contentpath = $doc_root."/sohoadmin/tmp_content/".$page_content;
$GATEway = stripslashes($GATEway);

#######################################
### PULL GATEWAY BASE TEMPLATE FILE ###
#######################################

$filename = "data/GATEWAY_BASE.HTML";
$file = fopen("$filename", "r");
	$BASE_HTML = fread($file,filesize($filename));
fclose($file);

// Build Gateway Text Menu
// ----------------------------------------------

$thisMenu = "";
$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE type = 'main' AND main_menu <> '' ORDER BY main_menu");
while ($row = mysql_fetch_array ($result)) {

$htmlpgname = eregi_replace(" ", "_", $row['page_name']);
$htmlpgname .= ".html";
$htmlpath = $doc_root."/".$htmlpgname;
	if(!file_exists($htmlpath)) {
	$thisMenu .= "<a href=\"".pagename($row['page_name'])."\">".$row['page_name']."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	} else {
	$thisMenu .= "<a href=\"".$htmlpgname."\">".$row['page_name']."</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
	}

//	$thisMenu .= "<a href=\"index.php?pr=$row[page_name]\">$row[page_name]</a>&nbsp;&nbsp;|&nbsp;&nbsp;";
}

$mnu_len = strlen($thisMenu);
$mnu_subt = $mnu_len - 25;
$thisMenu = substr("$thisMenu", 0, $mnu_subt);


// ----------------------------------------------
// START RADIUS 3 ALTERATION
// ----------------------------------------------
$mfilename = "$cgi_bin/meta.conf";
if (file_exists($mfilename)) {
	$file = fopen("$mfilename", "r");
		$body = fread($file,filesize($mfilename));
	fclose($file);
	$lines = split("\n", $body);
	$numLines = count($lines);
	for ($x=0;$x<=$numLines;$x++) {
		$temp = split("=", $lines[$x]);
		$variable = $temp[0];
		$value = $temp[1];
		${$variable} = $value;
	}
}


// ----------------------------------------------
// TEMPLATE UPDATE
// ----------------------------------------------

$disTemplate = $_POST['pTemplate'];
$disTemplate = eregi_replace($doc_root."/","",$disTemplate);
//$disTemplate = eregi_replace("","",$disTemplate);

if($srow['template'] != $disTemplate && $_POST['pTemplate'] != ''){

   if($disTemplate == "default"){
      mysql_query("UPDATE site_pages SET template = '' WHERE page_name = '$PROP_KEYNAME'");
   }else{
      if(!mysql_query("UPDATE site_pages SET template = '$disTemplate' WHERE page_name = '$PROP_KEYNAME'")){
         //echo "Could not update template for (".$PROP_KEYNAME.")<br>";
      }else{
         //echo "Updated (".$PROP_KEYNAME.")<br>";
      }
   }
}

//echo "this template (".$_POST['pTemplate'].")<br>";
//exit;

// $thisTitle = strtoupper($dot_com);
if ( $prop_title != "" ) { $thisTitle = $prop_title; } else { $thisTitle = strtoupper($this_ip); }
$sitetitle = $site_title;
$conflag = "";
if ($GATEway != "CON" && $GATEway != "" && $pgsec != "1") {
$BASE_HTML = eregi_replace("#GATEWAY#", "$GATEway", $BASE_HTML);
$BASE_HTML = eregi_replace("#MENU#", "$thisMenu", $BASE_HTML);
} else if(($GATEway == "CON" || $GATEway == "") && file_exists($contentpath) && $pgsec != "1") {
$filename = "$contentpath";
$file = fopen("$filename", "r");
$CONtent = fread($file,filesize($filename));
fclose($file);
$BASE_HTML = eregi_replace("#GATEWAY#", "$CONtent", $BASE_HTML);
$BASE_HTML = eregi_replace("#MENU#", "$thisMenu", $BASE_HTML);
$conflag = "1";
} else {
$filename = "data/GATEWAY_DEFAULT.HTML";
$file = fopen("$filename", "r");
$GATEway = fread($file,filesize($filename));
fclose($file);

$GATEway = eregi_replace("#PAGE#", "$pge_request", $GATEway);
$GATEway = eregi_replace("#DOTCOM#", "$dot_com", $GATEway);
$BASE_HTML = eregi_replace("#GATEWAY#", "$GATEway", $BASE_HTML);
$BASE_HTML = eregi_replace("#MENU#", "If not connected within 5 seconds... <a href=\"".pagename($pge_request)."\">click here</a>", $BASE_HTML);
$GATEway = ""; // RESET FOR TOOL SAKE
}

// Build Gateway Text Menu
// ----------------------------------------------

$BASE_HTML = eregi_replace("#SITETITLE#", "$sitetitle", $BASE_HTML);
$BASE_HTML = eregi_replace("#HEADING#", "<a href='../index.php'>$thisTitle</a>", $BASE_HTML);
$BASE_HTML = eregi_replace("#TITLE#", "$thisTitle", $BASE_HTML);

if($KEYwords != "") {
   $BASE_HTML = eregi_replace("#KEYWORDS#", "$KEYwords", $BASE_HTML);
} else {
   $BASE_HTML = eregi_replace("#KEYWORDS#", "$site_keywords", $BASE_HTML);
}

if($prop_desc != "") {
   $BASE_HTML = eregi_replace("#DESCRIPTION#", "$prop_desc", $BASE_HTML);
} else {
   $BASE_HTML = eregi_replace("#DESCRIPTION#", "$site_description", $BASE_HTML);
}

$date = date("m/d/Y");
$BASE_HTML = eregi_replace("#DATE#", "$date", $BASE_HTML);

##########################################################################
##########################################################################

if (strlen($SAVEAS_name) < 2) {
	$string = stripslashes($string);
	$string = eregi_replace("'", "", $string);
	$string = str_replace("&", "", $string);

	$PROP_name = stripslashes($PROP_name);
	$PROP_name = str_replace('_', ' ', $PROP_name);
	//$PROP_name = sterilize($PROP_name);
	//$PROP_name = ucwords($PROP_name);

	#########################################
	### UPDATE PROPERTIES NOW! ##############
	#########################################

	$GATEway = addslashes($GATEway);

   if($conflag != "1") {
   	$KEYwords = $KEYwords . "~~~SEP~~~" . $GATEway;
   } elseif($conflag == "1") {
      $KEYwords = $KEYwords . "~~~SEP~~~CON";
   }


   if( $PROP_splash == "y" && $PROP_splash_type == "y" ){		// bgcolor
   	$prop_bgcolor = eregi_replace("#", "", $prop_bgcolor);
   	$prop_bgcolor_image = strtoupper($prop_bgcolor);
   } elseif ($PROP_splash == "y" && $PROP_splash_type == "i") {		// bg image
   	$prop_bgcolor_image = addslashes($PROP_image_type);
   } else {
   	$prop_bgcolor_image = "";
   	$PROP_splash_type = "";
   }
  

	$disStuff = "password = '".slashthis($KEYwords)."', page_name = '$PROP_name', url_name = '".str_replace(' ', '_', $PROP_name)."', type = 'Main', username = '$PROP_sec_code', ";
	$disStuff .= "splash = '$PROP_splash_type',";
	$disStuff .= "bgcolor = '$prop_bgcolor_image', title = '".slashthis($prop_title)."', description = '".slashthis($prop_desc)."'";
	if ( !mysql_query("UPDATE site_pages SET $disStuff WHERE page_name = '$PROP_KEYNAME'") ) { echo "Could not save page properties because: ".mysql_error(); exit; }

	if (str_replace(' ', '_', $PROP_KEYNAME) != str_replace(' ', '_',$PROP_name)) { $currentPage = $PROP_name; }


# Save As...
} else {
	$time = time();
 	for ($i=0;$i<=10;$i++) {
		srand((double)microtime()*1000000);
		$tempVar = rand(0,9);
		$time = "$tempVar$time";
	}
	$link = substr("$time", 0, 10);

	$SAVEAS_name = slashthis($SAVEAS_name);
	$SAVEAS_name = eregi_replace("'", "", $SAVEAS_name);
	$SAVEAS_name = str_replace("&", "", $SAVEAS_name);

	$checkq = mysql_query("select prikey, page_name, url_name from site_pages where page_name='".str_replace('_', ' ', $SAVEAS_name)."' or url_name='".str_replace(' ', '_', $SAVEAS_name)."'");
	if(mysql_num_rows($checkq) > 0){
		echo "<script type=\"text/javascript\">\n";
		echo "alert('".lang('A page named ').$SAVEAS_name.' '.lang('already exists.')."');\n";
		echo "window.location = 'page_editor.php?currentPage=".$PROP_name."&nocache=".time()."'; \n";
		echo "</script>\n"; exit;
		
	} else {
		////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////
		$PROP_name = $srow["page_name"];
		$PROP_splash = $srow["splash"];
		$prop_bgcolor = $srow["bgcolor"];
		$PROP_sec_code = $srow["username"];
		$PROP_pagetype = $srow["type"];
		$CUR_TEMPLATE = $srow["template"];
		$tmp = $srow["password"];
		$key_gate = split("~~~SEP~~~", $tmp);
		$prop_title = $srow[title];
		$prop_desc = $srow[description];
	
	////////////////////////////////////////////////////////////////////////////////////////
	
	
		# Must be webmaster or have access to Create Pages to use Save As...
		if ( $_SESSION['CUR_USER_ACCESS'] == "WEBMASTER" || eregi(";MOD_CREATE_PAGES;", $_SESSION['CUR_USER_ACCESS']) ) {
			//$qry = "INSERT INTO site_pages VALUES('$SAVEAS_name','$PROP_pagetype','', '$tmp', '','$link','$PROP_sec_code','$PROP_splash','$prop_bgcolor','$prop_title','".addslashes($prop_desc)."','$CUR_TEMPLATE')";
			$qry = "INSERT INTO site_pages (page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, link, username, splash, bgcolor, title, description, template) ";
			$qry .= "VALUES('$SAVEAS_name','".str_replace(' ', '_', $SAVEAS_name)."','$PROP_pagetype','','','', '$tmp','$link','$PROP_sec_code','$PROP_splash','$prop_bgcolor','$prop_title','".addslashes($prop_desc)."','$CUR_TEMPLATE')";
			mysql_query($qry);
		}
	
		# If this is a non-webmaster user, make sure they have access to create new pages, then update their access rights to include new page name
		if ( $_SESSION['CUR_USER_ACCESS'] != "WEBMASTER" && eregi(";MOD_CREATE_PAGES;", $_SESSION['CUR_USER_ACCESS']) ) {
	      $pagename_underscored = eregi_replace(" ", "_", $SAVEAS_name);
	      $_SESSION['CUR_USER_ACCESS'] = $_SESSION['CUR_USER_ACCESS'].$pagename_underscored.";";
	      $qry = "UPDATE user_access_rights SET ACCESS_STRING = '".$_SESSION['CUR_USER_ACCESS']."' WHERE LOGIN_KEY = '".$_SESSION['CUR_USER_KEY']."'";
	      mysql_query($qry);
		}
	
		$currentPage = $SAVEAS_name;
	
	}

}

$currentPage = trim($currentPage);
$pge_request = str_replace(" ", "_", $currentPage);

$search_engine = $BASE_HTML;

# Add to recent pages list for priority display on open/edit pages
$global_admin_prefs = new userdata('admin');

if($currentPage != ''){
	$_SESSION['recent_pages'][$currentPage] = time();
	$recent_pages_array = $global_admin_prefs->get('recent_pages');
	if ( !is_array($recent_pages_array) ) { $recent_pages_array = array(); }
	$recent_pages_array[$currentPage] = time();
	$global_admin_prefs->set('recent_pages', $recent_pages_array);
}



#### Super Search Stuff	
if(!table_exists("search_contents")){
	$mquery = "CREATE TABLE search_contents (prikey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, page_name VARCHAR(255), include_in_search VARCHAR(255), page_contents BLOB)"; // EDIT HERE and specify your table and field names for the SQL query
	if (!mysql_query($mquery)) {
		echo mysql_error();
	}
}

if(!mysql_query("update search_core set allow_template_search='$allow_template_search' where prikey='1'")) {
	echo mysql_error(); exit;
}

if(!table_exists("search_contents")){
	$mquery = "CREATE TABLE search_contents (prikey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, page_name VARCHAR(255), include_in_search VARCHAR(255), page_contents BLOB)"; // EDIT HERE and specify your table and field names for the SQL query
	if (!mysql_query($mquery)) {
		echo mysql_error();
	}
}

$query = "SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages where page_name = '$PROP_name'"; // EDIT HERE and specify your table and field names for the SQL query
$numresults=mysql_query($query); 
$numrows=mysql_num_rows($numresults);	
$curtmp = mysql_query("select allow_template_search from search_core where prikey='1'");
$curtmp = mysql_fetch_array($curtmp);
$CUR_TEMPLATE = $curtmp['allow_template_search'];

if ($CUR_TEMPLATE == 'include') {
	$nft= '';
} else {
	$CUR_TEMPLATE == 'hide';
	$nft = '&nft=../../../../program/modules/super_search/search_template';
}

while ($row = mysql_fetch_array($numresults)) {							
	$title = $row["page_name"]; 	
	$this_page = eregi_replace(' ', '_', $title);
	$query2 = "select * from search_contents where page_name='$title'"; // EDIT HERE and specify your table and field names for the SQL query
	$numresults2=mysql_query($query2); 
	while($contentzz = mysql_fetch_array($numresults2)) {
		$contentzz['include_in_search'];
		if ($contentzz['include_in_search'] != 'hide') {
			$include_in_search = 'include';
		}
	}
	if ($include_in_search != 'hide') {
		$url = "index.php?pr=$this_page".$nft;
		
		if ($row["username"] != '' ) {
			$securegroup = $row["username"];
			mysql_query("update site_pages set username='' where page_name='$title'");
		}
		
		
		$pagecontent = include_r("http://".$_SESSION['this_ip']."/".$url);		
		$pagecontent = preg_replace('/\"<</', '\"', $pagecontent);
		$pagecontent = preg_replace('/>>\"/', '\"', $pagecontent);
		$pagecontent = preg_replace('/<style[^\e]*?<\/style>/i', '', $pagecontent);
		$pagecontent = preg_replace('/<script[^\e]*?<\/script>/i', '', $pagecontent);
		$pagecontent = preg_replace('/<noscript>[^\e]*?<\/noscript>/i', '', $pagecontent);				
		$pagecontent = preg_replace('/<!--[^\e]*?-->/', '', $pagecontent);
		$pagecontent = preg_replace('/<[^\e]*?>/', '', $pagecontent);
		$pagecontent = eregi_replace('&nbsp;', ' ', $pagecontent);
		$pagecontent = preg_replace("/(\n\s\n)+/", "\n", $pagecontent);
		$pagecontent = preg_replace("/(\n\n)+/", "\n", $pagecontent);
		$pagecontent = preg_replace("/(\n\s\n)+/", "\n", $pagecontent);
		$pagecontent = preg_replace("/(\n\s\n)+/", "\n", $pagecontent);
		$pagecontent = preg_replace("/(\r\n)+/", "\n", $pagecontent);
		$pagecontent = preg_replace("/\n(\s*)\n/", "\n", $pagecontent);   
		
		$pagecontent = mysql_real_escape_string($pagecontent);

		if ($row["username"] != '' ) {
			$securegroup = $row["username"];
			mysql_query("update site_pages set username='$securegroup' where page_name='$title'");
		}		
	}	
	$findmatch = mysql_query("select page_name from search_contents where page_name = '$title'");
	$findnmatch = mysql_fetch_array($findmatch);		
	
	if ( $findnmatch['page_name'] != $title) {
		$titlez = mysql_real_escape_string($title);
		$query3 = "'','$titlez', '$include_in_search', '$pagecontent'";
		
		if(!mysql_query("INSERT INTO search_contents VALUES(".$query3.")")){
			echo mysql_error();
			exit;
		}			 
	} else {
		$titlez = mysql_real_escape_string($title);
		if(!mysql_query("update search_contents set include_in_search='$include_in_search', page_contents='$pagecontent' where page_name='$titlez'")){
			echo mysql_error();
			exit;
		}		
	}
	//echo $title."<br>";
	$pagecontent = ''; 
}

eval(hook("update-properties.php:bottom"));
?>