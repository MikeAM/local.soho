<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../includes/product_gui.php');
chdir($curdir);
/*--------------------------------------------------------------------------------------------------*
   ______ __                 __      ____                __          __     __
  / ____// /_   ___   _____ / /__   / __/____   _____   / /_ ____ _ / /_   / /___   _____
 / /    / __ \ / _ \ / ___// //_/  / /_ / __ \ / ___/  / __// __ `// __ \ / // _ \ / ___/
/ /___ / / / //  __// /__ / ,<    / __// /_/ // /     / /_ / /_/ // /_/ // //  __/(__  )
\____//_/ /_/ \___/ \___//_/|_|  /_/   \____//_/      \__/ \__,_//_.___//_/ \___//____/

/*--------------------------------------------------------------------------------------------------*/

# smt_userimages
if ( !table_exists("smt_userimages") ) {
   $wDeez = "prikey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT";
   $wDeez .= ", template_folder VARCHAR(255)";
   $wDeez .= ", layout_file VARCHAR(255)";
   $wDeez .= ", orig_image VARCHAR(255)";
   $wDeez .= ", user_image VARCHAR(255)";

   $create_qry = "CREATE TABLE smt_userimages (".$wDeez.")";
   if ( !mysql_db_query($db_name, $create_qry) ) { echo "Unable to create smt_userimages table!<br>".mysql_error(); }
}

?>