<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


session_start();
error_reporting(E_PARSE);

/*=============================================================================================*
  ___                  _          _____       _     _
 / __| _ _  ___  __ _ | |_  ___  |_   _|__ _ | |__ | | ___  ___
| (__ | '_|/ -_)/ _` ||  _|/ -_)   | | / _` || '_ \| |/ -_)(_-<
 \___||_|  \___|\__,_| \__|\___|   |_| \__,_||_.__/|_|\___|/__/

/*=============================================================================================*/
# The purpose of this script is to check whether the two database tables utilized by the
# Forms Manager are set up and ready for data. If a table is missing, this script will create
# it with the correct structure.
#
# This script also contains functions  that are called throughout the different areas of the
# Forms Manager, but are listed here to mimimize clutter in the parent scripts.
#-----------------------------------------------------------------------------------------------
# Drop old tables if they exist
//if ( !mysql_query("select form_filename from form_properties") ) {
//   mysql_query("drop table form_properties");
//   mysql_query("drop table form_fields");
//}

# form_properties
if ( !table_exists("form_properties") ) {
   //echo "create table!"; exit;
	create_table("form_properties");
//   # Build Query String
//   #---------------------------------
//   $fprop_mk = "prikey INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
//
//   $fprop_mk .= "form_name VARCHAR(255),";
//   $fprop_mk .= "date_created int(25),";
//   $fprop_mk .= "form_id varchar(100),";
//   $fprop_mk .= "form_filename varchar(255),";
//   $fprop_mk .= "style blob,";
//
//   # Strip trailing ","
//   $fprop_mk = substr($fprop_mk, 0, -1);
//
//   # Create Table Now!
//   /*vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv*/
//   if ( !$chk = mysql_db_query($_SESSION['db_name'], "CREATE TABLE form_properties ($fprop_mk)")) {
//      echo "<b>ERROR! Unable to create form properties table!</b><br>";
//      echo "<u>Reason</u>: ".mysql_error()."<br>";
//      exit;
//
//   } else {
//      //echo "<b>SUCCESS!! Database form properties created!</b><br>";
//   }
   /*vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv*/

} // End form_properties check

# form_fields
#===============================================================
if ( !table_exists("form_fields") ) {

	create_table("form_fields");
//
//   # Build Query String
//   #---------------------------------
//   $fields_mk = "prikey INT NOT NULL AUTO_INCREMENT PRIMARY KEY,";
//
//   $fields_mk .= "dbname VARCHAR(255),"; // Field name for db
//   $fields_mk .= "title VARCHAR(255),"; // Text label displayed to visitor
//   $fields_mk .= "field_type VARCHAR(100),";
//   $fields_mk .= "field_id VARCHAR(100),";
//   $fields_mk .= "form_id VARCHAR(255),";
//   $fields_mk .= "sort_order int(10),";
//   $fields_mk .= "required varchar(50),";
//   $fields_mk .= "width varchar(50),";
//   $fields_mk .= "style BLOB,";
//   $fields_mk .= "choices BLOB,";
//   $fields_mk .= "checked VARCHAR(255),";
//   $fields_mk .= "notes BLOB,";
//
//   # Strip trailing ","
//   $fields_mk = substr($fields_mk, 0, -1);
//
//   # Create Table Now!
//   /*vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv*/
//   if ( !$chk = mysql_db_query($_SESSION['db_name'],"CREATE TABLE form_fields ($fields_mk)")) {
//      echo "<b>ERROR! Unable to create form_fields table!</b><br>";
//      echo "<u>Reason</u>: ".mysql_error()."<br>";
//      exit;
//
//   } else {
//      //echo "<b>SUCCESS!! Database form properties created!</b><br>";
//   }
//   /*vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv*/

} // else { echo "Fields table already exists!<br>"; } // End form_fields check


/// Remove all from a string except Alpha-Numeric Characters and Underscores
###============================================================================
function sterilize_char ($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$sterile_var = eregi_replace(" ", "_", $sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[0-9a-z_]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}
	$sterile_var = $tmp;
	return $sterile_var;
}


/// Popup javascript alert/confirm
###============================================================================
function jspop($type, $msg, $loc) {
   echo "<script language=\"javascript\">\n";

   # Alert Box
   if ( $type == "alert" ) {
      echo " alert('$msg');\n";
   }

   # Redirect
   if ( $type == "nav" ) {
      echo "window.location='$loc';\n";
   }

   echo "</script>\n";

}

?>