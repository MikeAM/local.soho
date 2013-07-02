<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


/*--------------------------------------------------------------------------------------------------*
   ______ __                 __      ____                __          __     __
  / ____// /_   ___   _____ / /__   / __/____   _____   / /_ ____ _ / /_   / /___   _____
 / /    / __ \ / _ \ / ___// //_/  / /_ / __ \ / ___/  / __// __ `// __ \ / // _ \ / ___/
/ /___ / / / //  __// /__ / ,<    / __// /_/ // /     / /_ / /_/ // /_/ // //  __/(__  )
\____//_/ /_/ \___/ \___//_/|_|  /_/   \____//_/      \__/ \__,_//_.___//_/ \___//____/

/*--------------------------------------------------------------------------------------------------*/

if(!table_exists("system_plugins")){
	create_table("system_plugins");
}

//# system_plugins
//if ( !table_exists("system_plugins") ) {
//   $wDeez = "PRIKEY INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT";
//   $wDeez .= ", PLUGIN_FOLDER VARCHAR(255)"; // Ties to same field in hook table
//   $wDeez .= ", TITLE VARCHAR(255)";
//   $wDeez .= ", VERSION VARCHAR(255)"; // For auto-update
//
//   $wDeez .= ", DESCRIPTION VARCHAR(255)";
//   $wDeez .= ", AUTHOR  VARCHAR(255)";
//   $wDeez .= ", HOMEPAGE VARCHAR(255)";
//   $wDeez .= ", ICON VARCHAR(255)";
//   $wDeez .= ", OPTIONS_LINK VARCHAR(255)";
//   $wDeez .= ", MISC BLOB";
//   $wDeez .= ", LAST_UPDATED VARCHAR(30)";
//   $wDeez .= ", changelog BLOB";
//   $wDeez .= ", release_date VARCHAR(50)";
//
//   $create_qry = "CREATE TABLE system_plugins (".$wDeez.")";
//   if ( !mysql_db_query($db_name, $create_qry) ) { echo "Unable to create system_plugins table!<br>".mysql_error(); }
//}

//# system_hook_attachments
//if ( !table_exists("system_hook_attachments") ) {
//   $wDeez = "PRIKEY INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT";
//   $wDeez .= ", PLUGIN_FOLDER CHAR(255)";
//   $wDeez .= ", HOOK_TYPE VARCHAR(255)";
//   $wDeez .= ", HOOK_ID VARCHAR(255)";
//   $wDeez .= ", HOOK_FILE VARCHAR(255)";
//   $wDeez .= ", MOD_FILE VARCHAR(255)";
//   $wDeez .= ", HOOK_DATA BLOB";
//   $wDeez .= ", LAST_UPDATED VARCHAR(30)";
//
//   $create_qry = "create table system_hook_attachments (".$wDeez.")";
//   if ( !mysql_db_query($db_name, $create_qry) ) { echo "Unable to create system_hook_attachments table!<br>".mysql_error(); }
//}


if(!table_exists("system_hook_attachments")){
	create_table("system_hook_attachments");
}

?>