<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

##########################################################################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Community:     http://forum.soholaunch.com
##########################################################################################################################################

##########################################################################################################################################
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
##########################################################################################################################################

/*------------------------------------------------------------------------------------------------------------------------------------------*
..######.......##.....##.....####................######...#######..########..########.....########.####.##.......########..######.............
.##....##......##.....##......##................##....##.##.....##.##.....##.##...........##........##..##.......##.......##....##............
.##............##.....##......##................##.......##.....##.##.....##.##...........##........##..##.......##.......##..................
.##...####.....##.....##......##.....#######....##.......##.....##.########..######.......######....##..##.......######....######.............
.##....##......##.....##......##................##.......##.....##.##...##...##...........##........##..##.......##.............##............
.##....##..###.##.....##.###..##................##....##.##.....##.##....##..##...........##........##..##.......##.......##....##............
..######...###..#######..###.####................######...#######..##.....##.########.....##.......####.########.########..######.............
/*------------------------------------------------------------------------------------------------------------------------------------------*/

session_start();


/// Ensure 100% legit docroot var is available
###-------------------------------------------------------------------------------------
if ( !is_dir($_SESSION['docroot_path']) ) {

   # Known aspects of path
   $clipknown = DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR.basename(__FILE__);

   # Strip away fluff and presto: garaunteed-accurate docroot path
   $_SESSION['docroot_path'] = str_replace( $clipknown, "", __FILE__);
   $_SESSION['product_gui'] = $_SESSION['docroot_path'].DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."product_gui.php";

   # Define domain root path (for html stuff)
   $goodpath = eregi_replace("/sohoadmin/.*", $_SERVER['PHP_SELF']);
   $_SESSION['docroot_url'] = $_SERVER['HTTP_HOST'].$goodpath;

   # Define path to interface graphics directory
   $_SESSION['icon_dir'] = $_SESSION['docroot_url'].DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."includes".DIRECTORY_SEPARATOR."display_elements".DIRECTORY_SEPARATOR."graphics".DIRECTORY_SEPARATOR;
}


########################################################################################
/// Include all core scripts now
###=====================================================================================
# Format path to main include directories
$incdir_main = $_SESSION['docroot_path'].DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."includes";
$incdir_program = $_SESSION['docroot_path'].DIRECTORY_SEPARATOR."sohoadmin".DIRECTORY_SEPARATOR."program".DIRECTORY_SEPARATOR."includes";


# Administrative login verification
include($incdir_main.DIRECTORY_SEPARATOR."config.php");


# Make sure login session isn't expired (only for product-side)
if ( !isset($_SESSION['PHP_AUTH_USER']) && eregi("sohoadmin/", $_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) != "index.php" ) {
		
	# Display 'session expired' message box
	echo "<div style=\"border: 1px solid #d70000; background-color: #CCC; padding: 50px; font-family: tahoma, arial, helvetica, sans-serif; text-align: center; font-weight: bold; color: #980000;\">\n";
	echo " Your session has expired.\n";
	echo " Please close this browser window and re-login.<br><br>";
	//   echo " <a href=\"../index.php\">login</a>\n";   
	
	echo " <a href=\"#\" onClick=\"parent.close();\">Close Window</a>\n";   
	echo "</div>\n";
	$soho_login_url_ar = explode('sohoadmin/', $_SERVER['PHP_SELF']);
	$soho_login_url = $soho_login_url_ar['0'].'sohoadmin/index.php';
	unset($_SESSION);
	session_destroy();
	echo "<script type=\"text/JavaScript\">\n";
	echo "   setTimeout(\"window.location = '".$soho_login_url."';\",2000); \n";
	echo "</script>\n";
}

/// DATA FLOW - Classes that manage the flow of internal data (i.e. hitting db)
###---------------------------------------------------------------------------------
// Format path to subdirectory
$incdir_sub = $incdir_program.DIRECTORY_SEPARATOR."data_flow";

# MySQL database query class
//include_once($incdir_main.DIRECTORY_SEPARATOR."mysql_insert.class.php");

# Function for fetching data from DB
include($incdir_sub.DIRECTORY_SEPARATOR."function-db_quickies.php");


/// Display Elements
###---------------------------------------------------------------------------------
// Format path to subdirectory
$incdir_sub = $incdir_program.DIRECTORY_SEPARATOR."display_elements";

# HTML GUI for each feature module --- depreciated, use smt_module.class.php (included below)
include($incdir_sub.DIRECTORY_SEPARATOR."class-feature_module.php");

# Newschool module builder (to be used instead of class-feature_module.php)

# Used to be
//include("../includes/smt_module.class.php");


/// REMOTE ACTIONS - Classes that manipulate remote files
###---------------------------------------------------------------------------------
// Format path to subdirectory
$incdir_sub = $incdir_program.DIRECTORY_SEPARATOR."remote_actions";

# Socket Connection - fsockopen()
include($incdir_sub.DIRECTORY_SEPARATOR."class-fsockit.php");

# Download files from remote
include($incdir_sub.DIRECTORY_SEPARATOR."class-file_download.php");

# MySQL database connection script
include($incdir_main.DIRECTORY_SEPARATOR."db_connect.php");

# Shared Functions called throughout product
include_once($incdir_program.DIRECTORY_SEPARATOR."shared_functions.php");

# Build version / Autoupdate - related functions
include_once($incdir_program.DIRECTORY_SEPARATOR."smt_functions.php");

# Administrative login verification
include($incdir_main.DIRECTORY_SEPARATOR."login.php");

# This works for modules not located in modules dir (Yay Module Template!)
include($incdir_program.DIRECTORY_SEPARATOR."smt_module.class.php");

# Track click path on demo sites
if ( $_SESSION['demo_site'] == "yes" ) {
   # Make sure demo_track table is created
   if ( !table_exists("demo_track") ) {
   	$qry = "prikey int(15) NOT NULL PRIMARY KEY AUTO_INCREMENT, session_id VARCHAR(75), filename VARCHAR(255), timestamp varchar(20)";

   	mysql_db_query($_SESSION['db_name'],"CREATE TABLE demo_track ($qry)");
   }

   $current_admin_file = str_replace($_SESSION['docroot_path'], "", $_SERVER['SCRIPT_FILENAME']);
   $qry = "insert into demo_track (prikey, session_id, filename, timestamp)";
   $qry .= " values('', '".session_id()."', '".$current_admin_file."', '".time()."')";
   if ( !mysql_query($qry) ) {
//      echo mysql_error(); exit;
   }

   # Custom for soholaunch.com live demo sites - if 30min time limit about to expire (and user is apparently still clicking),
   # ping info. and reset time limit for this site
   $demo_timespent = (time() - $_SESSION['demostart_timestamp']);
   if ( $demo_timespent > 1500 ) { // Get more time at 25min mark
      $newtime = time();
      
      include_r("http://info.soholaunch.com/demosite_moretime.php?sitenum=".$_SESSION['demo_num']."&newtime=".$newtime);
      
      $_SESSION['demostart_timestamp'] = $newtime;
   }
}
?>
