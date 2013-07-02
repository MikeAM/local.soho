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
.....########..########...........#######..##.....##.####..######..##....##.####.########..######...........
.....##.....##.##.....##.........##.....##.##.....##..##..##....##.##...##...##..##.......##....##..........
.....##.....##.##.....##.........##.....##.##.....##..##..##.......##..##....##..##.......##................
.....##.....##.########..........##.....##.##.....##..##..##.......#####.....##..######....######...........
.....##.....##.##.....##.........##..##.##.##.....##..##..##.......##..##....##..##.............##..........
.....##.....##.##.....##.........##....##..##.....##..##..##....##.##...##...##..##.......##....##..........
.....########..########...........#####.##..#######..####..######..##....##.####.########..######...........
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/


/*------------------------------------------------------------------------------------------------
 ___      _        _      ___         _
| __|___ | |_  __ | |_   |   \  __ _ | |_  __ _
| _|/ -_)|  _|/ _|| ' \  | |) |/ _` ||  _|/ _` |
|_| \___| \__|\__||_||_| |___/ \__,_| \__|\__,_|

# This function is designed to allow for simple and easy extraction of table data,
# and consequently eliminating some of the repetitive aspects of doing db queries
# that are fairly basic in nature...should reduce script bulk tiny bit as well.
#
# Accepts: name of involved database table and (optionally) a specific fieldname
#
# [single-row], "all" >> One-dimensional assoc array of fieldnames and data
#
# [single-row], [fieldname] >> Raw data contained in requested field
#
# [multi-row], "all" >> Multi-dimensional array of fields and data indexed by row
#
/*------------------------------------------------------------------------------------------------*/
function db_fetch($tablename, $fieldname = "all", $where = "" ) {
   if ( $fieldname == "all" ) {
      $thisstuff = "*";
   } else {
      $thisstuff = $fieldname;
   }


   # Select all data from table
   $qry = "select ".$thisstuff." from ".$tablename;
   if ( $where != "" ) {
      $qry .= " where ".$where;
   }
   if ( !$sel_all = mysql_query($qry) ) {
      //echo "Error: Unable to list fields of table '$tablename' on DB '$db_name'<br><u>Because</u>:".mysql_error(); exit;
   }

   # Count number of rows in table
   $rows = mysql_num_rows($sel_all);

   if ( $rows > 1 ) {
      # Multi-dimensional array
      while ( $rowdata = mysql_fetch_array($sel_all) ) {
         $dbGot[] = $rowdata;
      }

   } else {
      # One-dimensional array
      $dbGot = mysql_fetch_array($sel_all);
   }

   // Return full array or single value?
   if ( $fieldname == "all" ) {
      return $dbGot;
   } else {
      return $dbGot[$fieldname];
   }

} // End db_fetch function


/*------------------------------------------------------------------------------------------------
 _   _           _        _          ___         _
| | | | _ __  __| | __ _ | |_  ___  |   \  __ _ | |_  __ _
| |_| || '_ \/ _` |/ _` ||  _|/ -_) | |) |/ _` ||  _|/ _` |
 \___/ | .__/\__,_|\__,_| \__|\___| |___/ \__,_| \__|\__,_|
       |_|

# Note: This function is meant primarily for quick one or two-field updates on single-row tables
#
# >> Accepts:
# 1. Name of involved database table
# 2. Field update string of field='value' pairs (or assoc array of fields and values)
/*------------------------------------------------------------------------------------------------*/
function db_set($tablename, $newvals) {
   if ( !$quickie = mysql_query("UPDATE $tablename SET $newvals") ) {
      //echo "Error: Unable to set $newvaules in $tablename.<br>".mysql_error(); exit;
   }

} // End db_fetch function

?>