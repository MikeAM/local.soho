<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

if(!include_once("../includes/product_gui.php")){
	exit;	
}
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
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston 
## Copyright 2003-2007 Soholaunch.com, Inc.
## All Rights Reserved.
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


#######################################################################
### STEP 1: PULL ALL CURRENT SITE PAGES FROM DATA TABLE
#######################################################################

$result = mysql_query("SELECT prikey, page_name, url_name FROM site_pages");
$t_chk = ";";
while ($row = mysql_fetch_array($result)) {
	$t_chk .= $row['page_name'].";";
}
$t_chk .= "search;index;Index;INDEX;";

$PAGE_ERR = "";

#######################################################################

//
//function sterilize($sterile_var) {
//	$sterile_var = stripslashes($sterile_var);
//	$st_l = strlen($sterile_var);
//	$st_a = 0;
//	$tmp = "";
//	while($st_a != $st_l) {
//		$temp = substr($sterile_var, $st_a, 1);
//		if (eregi("[0-9a-z_ !\-ÅÄÖåäö]", $temp)) { $tmp .= $temp; }
//		$st_a++;
//	}
//	$sterile_var = $tmp;
//	$sterile_var = trim($sterile_var);
//	return $sterile_var;
//}

function sterilize($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
//	while($st_a != $st_l) {
//		$temp = substr($sterile_var, $st_a, 1);
//		if (eregi("[0-9a-z_ !æøå-]", $temp)) { $tmp .= $temp; }
//		$st_a++;
//	}
	$tmp = str_replace('?', '', $sterile_var);
	$tmp = str_replace(',', '', $tmp);
	$tmp = str_replace('', '', $tmp);
	$tmp = str_replace('/', '', $tmp);
	$tmp = str_replace('\\', '', $tmp);
	//$tmp = str_replace('&', "%26", $tmp);
	$tmp = str_replace('=', '', $tmp);
	$tmp = str_replace('"', '', $tmp);
	$tmp = str_replace('\'', '', $tmp);
	$sterile_var = $tmp;
	$sterile_var = trim($sterile_var);
	return $sterile_var;
}



$string = implode("~~~", $_POST);
$string = stripslashes($string);
$string = eregi_replace("'", "", $string);
//$string = str_replace("&", "and", $string);
$string = str_replace("#", "", $string);
$string = str_replace("\"", "", $string);
$string = str_replace("/", "", $string);
$string = str_replace("_", " ", $string);
$formValues = split("~~~", $string);
$numVars = count($formValues);

$c=0;
for ($d=1;$d<=$numVars;$d=$d+2) {
   $nxt = $d+1;
   if (strlen($formValues[$d]) >= 1) {
      $c++;
      $addPage[$c] = $formValues[$d];
      //$subPage[$c] = $formValues[$nxt];
   }
}

#######################################################################
### Loop through "pages to create" and make sure they do not already
### exist within the web site
#######################################################################
$errors = array();

for ($d=1;$d<=$c;$d++) {
	//$addPage[$d] = ucwords($addPage[$d]);
	$l = strlen($addPage[$d]);
	$a = 0;
	$tmpName = "";
	while($a != $l) {
		$temp = substr($addPage[$d], $a, 1);
		$tmpName .= $temp;
		$a++;
	}
	$addPage[$d] = $tmpName;
	$link = md5($tmpName);

	if ( $addPage[$d] != "php.ini" && $addPage[$d] != "php" && !eregi(";$addPage[$d];", $t_chk) ) {    // DO NOT REMOVE -- THIS IS FOR SECURITY
		$pagefile = sterilize($addPage[$d]);
		//$pagefile = urlencode($pagefile);
		$pagefile = str_replace(' ', '_', $pagefile);


		$searchpage = eregi_replace('_', ' ', $pagefile);
		$searchqry = mysql_query("select prikey, page_name, url_name, from search_contents where page_name='".$searchpage."'");
		$searchnum = mysql_num_rows($searchqry);
		if($searchnum==0){
			mysql_query("INSERT INTO search_contents (prikey,page_name,include_in_search,page_contents) VALUES('','".$searchpage."','include','')");
		}

		$indexphpurl = "<?php\n";
		$indexphpurl .= 'error_reporting(E_PARSE);'."\n";
		$indexphpurl .= 'session_start();'."\n";
		$indexphpurl .= 'if($_GET[\'_SESSION\'] != \'\' || $_POST[\'_SESSION\'] != \'\' || $_REQUEST[\'_SESSION\'] != \'\' || $_COOKIE[\'_SESSION\'] != \'\') { exit; }'."\n\n";
		$indexphpurl .= 'include_once("sohoadmin/client_files/pgm-site_config.php");'."\n";
		$indexphpurl .= 'include_once("sohoadmin/program/includes/shared_functions.php");'."\n";
			
		$indexphpurl .= '$pagefile = __FILE__;'."\n";
		$indexphpurl .= 'if($pagefile == \'\'){'."\n";
		$indexphpurl .= '	$pagefile = $_SERVER[\'SCRIPT_FILENAME\'];'."\n";
		$indexphpurl .= '}'."\n";
		
//		$indexphpurl .= 'if(function_exists(\'url_get_encoding\')){
//	if(strtoupper(url_get_encoding(\'http://\'.$_SESSION[\'this_ip\'].\'/sohoadmin/config/isp.conf.php\')) != \'UTF-8\' && function_exists(\'utf8_decode\')){
//		if(file_exists(\'sohoadmin/tmp_content/\'.str_replace(\'.php\',\'.con\',utf8_decode(basename($pagefile))))){
//			$pagefile = utf8_decode($pagefile);
//		}
//	}
//}'."\n";
		
		$indexphpurl .= '$pagefile = preg_replace(\'/\.php$/i\', \'\', basename($pagefile));'."\n";
		
		$indexphpurl .= '$pagetitle = str_replace( "_", " ", $pagefile);'."\n";
		$indexphpurl .= '$secure_setting = mysql_query("select username from site_pages where page_name = \'$pagetitle\'");'."\n";
		$indexphpurl .= '$secure_name = mysql_fetch_array($secure_setting);'."\n";
		$indexphpurl .= 'if (!isset($secure_name[\'username\']) or ($secure_name[\'username\'] == "")) {'."\n";
		$indexphpurl .= '	$pr = $pagefile;'."\n";
		$indexphpurl .= '	$_REQUEST[\'pr\'] = $pagefile;'."\n";
		$indexphpurl .= '	$_GET[\'pr\'] = $pagefile;'."\n";
		$indexphpurl .= '	$_POST[\'pr\'] = $pagefile;'."\n";
		$indexphpurl .= '	$pageRequest = $pagefile;'."\n";
		$indexphpurl .= '	include("index.php");'."\n";
		$indexphpurl .= '} else { '."\n";
//		$indexphpurl .= '	$destination = "index.php?pr=".$pagefile;'."\n";
//		$indexphpurl .= '	header("Location:$destination");'."\n";
		$indexphpurl .= '	$pr = $pagefile;'."\n";
		$indexphpurl .= '	$_REQUEST[\'pr\'] = $pagefile;'."\n";
		$indexphpurl .= '	$_GET[\'pr\'] = $pagefile;'."\n";
		$indexphpurl .= '	$_POST[\'pr\'] = $pagefile;'."\n";
		$indexphpurl .= '	$pageRequest = $pagefile;'."\n";
		$indexphpurl .= '	include("index.php");'."\n";
		$indexphpurl .= '}'."\n";
		$indexphpurl .= 'exit;'."\n";
		$indexphpurl .= "?>"."\n";
		
		$pagelinkfile = $pagefile;
		if(url_get_encoding('http://'.$_SESSION['this_ip'].'/sohoadmin/config/isp.conf.php') != 'UTF-8' && function_exists('utf8_encode')){
			$pagelinkfile = utf8_encode($pagelinkfile);
		}

		$indexphpfilename = $_SESSION['doc_root']."/".$pagelinkfile.'.php';
		$indexphpfile = fopen($indexphpfilename, "w");
		fwrite($indexphpfile, $indexphpurl);
		fclose($indexphpfile);
		chmod($indexphpfilename, 0755);

	} else {
      # Problem with this one
      $errors[] = $addPage[$d];
//       $PAGE_ERR .= "$addPage[$d] \\n";
	}
} // End For $d


//	#######################################################################
//	### If a page exists; bomb now and return to create pages
//	#######################################################################
//
//	if ($PAGE_ERR != "") {
//		echo "<SCRIPT LANGUAGE=Javascript>\n";
//		echo "	alert('".$lang["Could Not Create the Following Pages because they already exist on the system:"]."\\n\\n$PAGE_ERR');\n";
//		echo "  history.back();\n";
//		echo "</SCRIPT>\n";
//		exit;
//	}

	#######################################################################
	### No duplicates detected, proceed with creation of pages
	#######################################################################

// This checks for the main menu pages and adds
// the page to the menu if selected in Create Pages
// Joe Lain 7-25-05

//error_reporting(E_ALL);

$thisnum = 0;
$findOnmenu = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE main_menu>0");
$mainnum = "";
while ($OnMenu = mysql_fetch_array($findOnmenu))
{
	$mainnum = $OnMenu['main_menu'];
	if ( $mainnum > $thisnum )
	{
		$bigone = $mainnum;
		$thisnum = $bigone;
	}
}

$bigone++;

for ($d=1;$d<=$c;$d++) {

   //$addPage[$d] = ucwords($addPage[$d]);

   $l = strlen($addPage[$d]);
   $a = 0;
   $tmpName = "";
   while($a != $l) {
      $temp = substr($addPage[$d], $a, 1);
      $tmpName .= $temp;
      $a++;
   }
   $addPage[$d] = $tmpName;
   
   $link = md5($tmpName);

   // Fixed problem when creating pages with underscores from tool
   // Mike Morrison - 5/2003
   // created db_page_name var and removed slashes before adding
   // to db
   // ------------------------------------------------------------

   if ( !in_array($addPage[$d], $errors) ) {
      $addPage[$d] = sterilize($addPage[$d]);
      
      $db_page_name[$d] = eregi_replace("_", " ", $addPage[$d]);
      
      //$pageName = htmlentities($db_page_name[$d], ENT_QUOTES);

      if ( strtolower(${"ONMENU".$d}) != "yes" ) {
         # Do not add page to menu
         $SQL_INSERT = "VALUES('".$db_page_name[$d]."','Main','$ts', '', '','".$link."','','','','','','')";
         $dataArr = array();
         $dataArr['page_name'] = $db_page_name[$d];
         $dataArr['url_name'] = str_replace(' ', '_', $db_page_name[$d]);
         $dataArr['type'] = 'Main';
         $dataArr['sub_page_of'] = $ts;
         $dataArr['link'] = $link;
         $myQry = new mysql_insert('site_pages', $dataArr);
         $myQry->insert();
         

      } else {
         # Adds page to menu
         $SQL_INSERT = "VALUES('".$db_page_name[$d]."','Main','$ts', '', ".$bigone.",'".$link."','','','','','','')";
         $dataArr = array();
         $dataArr['page_name'] = $db_page_name[$d];
         $dataArr['url_name'] = str_replace(' ', '_', $db_page_name[$d]);
         $dataArr['type'] = 'Main';
         $dataArr['sub_page_of'] = $ts;
         $dataArr['main_menu'] = $bigone;
         $dataArr['link'] = $link;
         $myQry = new mysql_insert('site_pages', $dataArr);
         $myQry->insert();         
         
         $bigone++;
      }

//      if ( !$mkpages = mysql_query("INSERT INTO site_pages $SQL_INSERT") ) {
//          echo "Could not create new pages!<br>";
//          echo "MySQL Error: [".mysql_error()."]\n";
//          exit;
//      }

      # Update User Access Rights with created page if applicable.
      if ($CUR_USER_ACCESS != "WEBMASTER") {
         $this_page = eregi_replace(" ", "_", $addPage[$d]);
         $CUR_USER_ACCESS = $CUR_USER_ACCESS."$this_page;";
      }

   
	$_SESSION['recent_pages'][$addPage[$d]] = time();
   
   } // End if !in_array($addPage, $errors)

   # Add pages to "recently accessed" list for priority display on open/edit pages menu
//   error_reporting(E_ALL);
//   $arraykey = time();
   

} // End $d For Loop


# Update user access rights in db with updated $CUR_USER_ACCESS string (presumably added to in for loop)
if ($CUR_USER_ACCESS != "WEBMASTER") {
   mysql_query("UPDATE user_access_rights SET ACCESS_STRING = '$CUR_USER_ACCESS' WHERE LOGIN_KEY = '$CUR_USER_KEY'");
}


$action = "DONE";	// TELL CREATE_PAGES SCRIPT THAT THE ADDITION IS COMPLETE

?>