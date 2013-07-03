<?php
error_reporting(E_PARSE);
session_start();
require_once('../includes/product_gui.php');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


session_start();
error_reporting(E_PARSE);

/*=============================================================================================*
  ___                  _          _____       _     _
 / __| _ _  ___  __ _ | |_  ___  |_   _|__ _ | |__ | | ___  ___
| (__ | '_|/ -_)/ _` ||  _|/ -_)   | | / _` || '_ \| |/ -_)(_-<
 \___||_|  \___|\__,_| \__|\___|   |_| \__,_||_.__/|_|\___|/__/

/*=============================================================================================*/
# The purpose of this script is to check whether the database tables utilized by the
# Webmaster feature are set up and ready for data. If a table is missing, this script will create
# it with the correct structure.
#------------------------------------------------------------------------------------------------


//mysql_query("DROP TABLE site_backup");

// Loop through all table names for  match
//==============================================================
function table_exist($tablename) {
   global $db_name;
   $result = mysql_query('show tables from '.$db_name);
   $i = 0;
   while ($i < mysql_num_rows ($result)) {
      $tb_names[$i] = mysql_tablename ($result, $i);
      if ($tb_names[$i] == $tablename) {
         return true;
      }
      $i++;
   }
   return false;

} // End table existence check


# site_backup
#===============================================================
if ( !table_exist("site_backup") ) {

   # Build Query String
   #---------------------------------
   $fields_mk = "PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
   $fields_mk .= "BAK_ID VARCHAR(50),";

   $fields_mk .= "BAK_DATE VARCHAR(50),";
   $fields_mk .= "BAK_TIME VARCHAR(25),";
   $fields_mk .= "BAK_TITLE VARCHAR(255),";
   $fields_mk .= "BAK_NOTES BLOB,";
   $fields_mk .= "BAK_FILE VARCHAR(255),";

   $fields_mk .= "BAK_TYPE VARCHAR(255),";

   $fields_mk .= "BAK_DIRS VARCHAR(255),";
   $fields_mk .= "BAK_TABLES VARCHAR(255),";
   $fields_mk .= "BAK_TEMPLATES VARCHAR(255),";
   $fields_mk .= "BAK_FILES VARCHAR(255),";
   $fields_mk .= "BAK_PAGES VARCHAR(255),";

   $fields_mk .= "BAK_LIST BLOB,";

   $fields_mk .= "CUSTOM1 VARCHAR(255),";
   $fields_mk .= "CUSTOM2 VARCHAR(255),";
   $fields_mk .= "CUSTOM3 BLOB,";
   $fields_mk .= "FUTURE1 VARCHAR(255),";
   $fields_mk .= "FUTURE2 VARCHAR(255),";
   $fields_mk .= "FUTURE3 BLOB";

   # Create Table Now!
   /*vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv*/
   if ( !$chk = mysql_db_query("$db_name","CREATE TABLE site_backup ($fields_mk)")) {
      echo "<b>".lang("ERROR")."! ".lang("Unable to create site_backup table")."!</b><br>";
      echo "<u>".lang("Reason")."</u>: ".mysql_error()."<br>";
      exit;

   } else {
      //echo "<b>SUCCESS!! Database form properties created!</b><br>";
   }
   /*vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv*/

}

?>
