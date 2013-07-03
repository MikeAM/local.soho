<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../includes/product_gui.php');
chdir($curdir);


/*************************************************************************************************
 ___                     _   ___   _        _         ___
/ __| _ __  ___  ___  __| | |   \ (_) __ _ | |  ___  | _ \ __ _  __ _  ___  ___
\__ \| '_ \/ -_)/ -_)/ _` | | |) || |/ _` || | |___| |  _// _` |/ _` |/ -_)(_-<
|___/| .__/\___|\___|\__,_| |___/ |_|\__,_||_|       |_|  \__,_|\__, |\___|/__/
     |_|                                                        |___/

# Developer 'Open Page' drop-downs
# Include this where ever you want to have a dropdown box with all the site pages in it
/*************************************************************************************************/

# Build page arrays (based on menu status) for jump menus
# Loop all and split into diff arrays by menu status
$pgrez = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages ORDER BY main_menu ASC,sub_page_of,page_name");

$main_pages = array();
$sub_pages = array();
$offmenu_pages = array();
$dropdown_options = "";

# Build sortable page name array
#-----------------------------------------------
while ( $getPage = mysql_fetch_array($pgrez) ) {
	if(!preg_match('/^http:/i', $getPage['link'])){
	   # Main menu pages
	   if ( $getPage['main_menu'] > 0 ) {
	      $main_pages[] = $getPage['page_name'];
	
	   # Sub-menu pages
	   } elseif ( strlen($getPage['sub_page_of']) > 4 ) {
	      $tmppg = split("~~~", $getPage['sub_page_of']);
	      $sub_pages[$tmppg[0]][] = array('sort'=>$tmppg[1], 'name'=>$getPage['page_name']);
	
	   # Off-menu pages
	   } else {
	      $offmenu_pages[] = $getPage['page_name'];
	   }
	}
}


# Is the currently-logged-in admin user authorized to edit this page?

$CUR_USER_ACCESS=$_SESSION['CUR_USER_ACCESS'];
$dropdown_options .= "       <option class=\"\" value=\"\" style=\"background-color: #ccc;font-style:italic;\">".lang("Select Page")."</option>\n";
# Build dropdown options
#-----------------------------------------------
# [ On-menu pages ]
//$dropdown_options .= "       <option value=\"\" style=\"background-color: #ccc;\">[".lang("On-Menu Pages")."]</option>\n";
foreach ( $main_pages as $key=>$mp ) {
	$TMP_CHK = eregi_replace(" ", "_", $mp);
	if ($CUR_USER_ACCESS == "WEBMASTER" || strpos($CUR_USER_ACCESS, ";".$TMP_CHK.";") !== false || eregi(";MOD_ALLPAGES;", $CUR_USER_ACCESS) ) { // Admin is authorized, build page row now
   		$dropdown_options .= "       <option style=\"font-style:normal!important;\" value=\"". str_replace('&','%26',str_replace(' ','+',$mp))."\">".htmlspecialchars($mp)."</option>\n";

		# Pull sub-pages for this page
		foreach ( $sub_pages[$mp] as $sp ) {
		   $dropdown_options .= "       <option style=\"font-style:normal!important;\" value=\"". str_replace('&','%26',str_replace(' ','+',$sp[name]))."\">&gt;&gt; ".htmlspecialchars($sp[name])."</option>\n";
		}
	}
}

# [ Off-menu pages ]
if(count($offmenu_pages) > 0){
	$dropdown_options .= "       <option value=\"\" style=\"font-style:italic;background-color: #ccc;\">[".lang("Off-Menu Pages")."]</option>\n";
	foreach ( $offmenu_pages as $key=>$op ) {
		$TMP_CHK = eregi_replace(" ", "_", $op);
		if ($CUR_USER_ACCESS == "WEBMASTER" || strpos($CUR_USER_ACCESS, ";".$TMP_CHK.";") !== false || eregi(";MOD_ALLPAGES;", $CUR_USER_ACCESS) ) { // Admin is authorized, build page row now
			$dropdown_options .= "       <option style=\"font-style:normal!important;\" value=\"".str_replace('&','%26',str_replace(' ','+',$op))."\">".htmlspecialchars($op)."</option>\n";
		}
	}
}



?>