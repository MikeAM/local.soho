<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


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

session_start();
include("../../includes/product_gui.php");

#######################################################
### DELETE PAGE CONFIG FILES AND DB ENTRY		    ###
#######################################################
if($_GET['currentPage'] == ''){ header("Location: ../open_page.php?=SID"); exit; }
$fpage = mysql_query("select prikey, page_name, url_name from site_pages where prikey='".$_GET['currentPage']."'");
if(mysql_num_rows($fpage) != 1){ header("Location: ../open_page.php?=SID"); exit; }
$this_page = mysql_fetch_assoc($fpage);
$this_del_page = eregi_replace("_", " ", $this_page['url_name']);

mysql_query("DELETE FROM site_pages WHERE prikey = '".$_GET['currentPage']."'");
mysql_query("DELETE FROM search_contents WHERE page_name='".$this_del_page."'");

#######################################################
### Read Config Dir and Delete page HTML files	    ###
#######################################################

$directory = "$cgi_bin";

$old_file = eregi_replace(" ", "_", $this_del_page);
// $old_file .= ".";  Mike M Fix

@unlink($_SESSION['doc_root'].'/'.$old_file.'.php');

unset($_SESSION['recent_pages'][$old_file]);

$handle = opendir("$directory");
while ($files = readdir($handle)) {
	if (eregi("$old_file", $files)) {
		if ("$old_file".".con" == $files || "$old_file".".regen" == $files) {
			$this_file = $directory . "/$files";
			@unlink($this_file);
		} // End Exact Match Fix -- Mike Morrison 5/2003
	}
}
closedir($handle);

header ("Location: ../open_page.php?=SID");
exit;

?>