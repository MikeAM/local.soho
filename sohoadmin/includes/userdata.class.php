<?php
//error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


session_start();
/*---------------------------------------------------------------------------------------------------------*
                     ___         _
 _  _  ___ ___  _ _ |   \  __ _ | |_  __ _
| || |(_-</ -_)| '_|| |) |/ _` ||  _|/ _` |
 \_,_|/__/\___||_|  |___/ \__,_| \__|\__,_|

# About userData class...
# For use by plugins and eventually small factory features like payment gateways as well
# This file is included by shared_functions.php
/*---------------------------------------------------------------------------------------------------------*/
class userdata {

   # Plugin (folder) name. Must be set so functions know whose data to manipulate
   var $plugin; 

   # Called first -- the other methods depend on this being set
   function userdata($plugin) {
      $this->plugin = $plugin;

      # Make sure smt_userdata table exists
      if ( !table_exists("smt_userdata") ) {
         include($_SESSION['docroot_path']."/sohoadmin/includes/create_system_tables.inc.php");
      }
   }

   # Updates value of specific field (or inserts as new rec if fieldname not found)
   # Example call: set("firstname", "billy")
   function set($fieldname, $data) {
   	if ( is_array($data) ) { $data = serialize($data); }
      $qry = "SELECT * FROM smt_userdata WHERE plugin='".$this->plugin."' AND fieldname = '".$fieldname."'";
      $rez = mysql_query($qry);

      # Insert new or update existing?
      if ( mysql_num_rows($rez) < 1 ) {

         $qry = "INSERT INTO smt_userdata VALUES('', '".$this->plugin."', '".$fieldname."', '".$data."')";
         mysql_query($qry);
         //echo mysql_error(); exit;

      } else {
         $qry = "UPDATE smt_userdata SET data = '".$data."' WHERE plugin='".$this->plugin."' AND fieldname = '".$fieldname."'";
         mysql_query($qry);
      }
   }


   # If $fieldname is NOT passed: Gets all userdata related to passed plugin and returns it in an array
   # If $fieldname IS passed: Gets data for requested field
   # Example call: get() or get("account_id")
   function get($fieldname = "") {

      # Return value of all fields or just a specific one?
      if ( $fieldname == "" ) {
         # Return all field data for this plugin
         $userdata = array();
         $qry = "SELECT * FROM smt_userdata WHERE plugin='".$this->plugin."'";
         $rez = mysql_query($qry);
         while ( $getData = mysql_fetch_array($rez) ) {
            $userdata[$getData['fieldname']] = $getData['data'];
         }

      } else {
         # Return value of specific fieldname
         $qry = "SELECT data FROM smt_userdata WHERE plugin='".$this->plugin."' and fieldname='".$fieldname."'";
         $rez = mysql_query($qry);

         $userdata = mysql_result($rez, 0);
      }

      if ( is_serialized($userdata) ) {
      	return unserialize($userdata);
      } else {
      	return $userdata;
      }
   }


   # Delete all data associated with this plugin
   function delete() {
      $qry = "DELETE FROM smt_userdata WHERE plugin='".$this->plugin."'";
      mysql_query($qry);
   }

} // End userData class

?>