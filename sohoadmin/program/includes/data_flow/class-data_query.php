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

/*::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*
....##.....##.##....##..######...#######..##.............#######..##.....##.########.########..##....##......
....###...###..##..##..##....##.##.....##.##............##.....##.##.....##.##.......##.....##..##..##.......
....####.####...####...##.......##.....##.##............##.....##.##.....##.##.......##.....##...####........
....##.###.##....##.....######..##.....##.##............##.....##.##.....##.######...########.....##.........
....##.....##....##..........##.##..##.##.##............##..##.##.##.....##.##.......##...##......##.........
....##.....##....##....##....##.##....##..##............##....##..##.....##.##.......##....##.....##.........
....##.....##....##.....######...#####.##.########.......#####.##..#######..########.##.....##....##.........
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
class mysql_qry {
   var $db_name;
   var $db_table; // Name of applicable database table
   var $db_field = array(); // Starts as array of fld names, gets data, does something with data (insert, update, etc.)
   var $needles = array();  // Field names and values used to locate particular db record(s)
   var $qry_string; // final query string


   /*#############################################################################################
    __  __        _        _      ___  _       _     _
   |  \/  | __ _ | |_  __ | |_   | __|(_) ___ | | __| | ___
   | |\/| |/ _` ||  _|/ _|| ' \  | _| | |/ -_)| |/ _` |(_-<
   |_|  |_|\__,_| \__|\__||_||_| |_|  |_|\___||_|\__,_|/__/

   >> Compile updated array of fields and values
   ##############################################################################################*/
   function prep_fields( $tablename, $newStuff ) { // (releveant db table, known values indexed by field name)
      global $db_name;
      $this->db_table = $tablename;

      if ( !$get_list = mysql_query("SELECT * FROM $tablename") ) {
//         echo "Error: Unable to list fields of table '$tablename' on DB '$db_name'<br><u>Because</u>:".mysql_error(); exit;
      }
      $get_num = mysql_num_fields($get_list);

      // Add known data to qry array
      #[----------------------------------------------------------]
      for ( $f=0; $f < $get_num; $f++ ) {
         $col = mysql_field_name($get_list, $f);

         if ( isset($newStuff[$col]) && $col != "PRIKEY" ) {
            $this->db_field[$col] = $newStuff[$col];

         } elseif ( $col == "PRIKEY" ) {
            $this->db_field[$col] = NULL;

         } else {
            $this->db_field[$col] = "";
         }
      }

      # Build db insert string
      foreach ( $this->db_field as $fld => $val ) {
         $this->qry_string .= "'$val', ";
      }

      # Format qry string
      $this->qry_string = substr($this->qry_string, 0, -2); // Kill trailing ", "

   } // End array-building form_qry constructor


   /*#############################################################################################
    _____          _      ___
   |_   _|___  ___| |_   / _ \  _  _  ___  _ _  _  _
     | | / -_)(_-<|  _| | (_) || || |/ -_)| '_|| || |
     |_| \___|/__/ \__|  \__\_\ \_,_|\___||_|   \_, |
                                                |__/
   >> Output as raw string and HTML table for testing
   ##############################################################################################*/
   function test_qry() {
      echo "<div id=\"scrollLayer\" style=\"position:absolute; visibility:visible; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000\">\n";
      echo "<br><u>Test Qry Output:</u><br>\n";
      echo "<textarea style=\"font-family: arial; font-size: 11px; width: 725px; height: 100px;\">".$this->qry_string."</textarea><br><br>\n";
      echo "<table width=\"100%\" cellpadding=\"4\" cellspacing=\"0\" border=\"1\">\n";
      echo " <tr>\n";
      foreach ( $this->db_field as $col => $val ) {
         echo "  <td bgcolor=\"#000000\" style=\"font-family: arial; font-size: 11px; color: #F8F9FD;\">\n";
         echo "   <b>".$col."</b>\n";
         echo "  </td>\n";
      }
      echo " </tr>\n";
      $tFlds = explode(",", $this->qry_string);
      echo " <tr>\n";
      foreach ( $tFlds as $val ) {
         $val = str_replace("'", "", $val);
         echo "  <td bgcolor=\"#EFEFEF\" style=\"font-family: arial; font-size: 11px; color: #000000;\">\n";
         echo "   ".$val."\n";
         echo "  </td>\n";
      }
      echo " </tr>\n";
      echo "</table>\n";
      echo "</div>\n";

   } // End test_qry method


   /*#############################################################################################
       ____                          __     ____          __
      /  _/____   _____ ___   _____ / /_   / __ \ ____ _ / /_ ____ _
      / / / __ \ / ___// _ \ / ___// __/  / / / // __ `// __// __ `/
    _/ / / / / /(__  )/  __// /   / /_   / /_/ // /_/ // /_ / /_/ /
   /___//_/ /_//____/ \___//_/    \__/  /_____/ \__,_/ \__/ \__,_/
   ##############################################################################################*/
   function do_insert() {
      if ( !$inserted = mysql_query("INSERT INTO $this->db_table VALUES($this->qry_string)") ) {
         return false;
         //echo "<b>Error</b>: <i>".mysql_error()."</i><br>\n";
      } else {
         return true;
      }
   }

} // End form_qry class


?>