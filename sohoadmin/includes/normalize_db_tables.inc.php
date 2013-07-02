<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

#================================================================================
# DATABASE TABLE STRUCTURE CHECK
# Make sure all tables have correct field structure
# ...compared against build_dbmaster.php included in product build
#================================================================================
error_reporting(E_PARSE);
session_start();
require_once('../program/includes/product_gui.php');
$build_dbmaster = $_SESSION['docroot_path']."/sohoadmin/config/build_dbmaster.conf.php";
$dbMaster = unserialize(file_get_contents($build_dbmaster));
$tables = dbtables();

# Get column structure of site tables as they are now (before update check)
$dbLocal = array();
foreach ( $tables as $key=>$tablename ) {
   $qry = "SHOW COLUMNS FROM ".$tablename;
   $rez = mysql_query($qry);

   while ( $getCol = mysql_fetch_assoc($rez) ) {
      $dbLocal[$tablename][] = array('name' => $getCol['Field'], 'type' => $getCol['Type']);
   }
}

function check_table_structure($tablename) {
   global $dbLocal;
   global $dbMaster;

   for ( $f = 0; $f < count($dbMaster[$tablename]); $f++ ) {
      $qry = NULL;

      # Add a new column?
      if ( !isset($dbLocal[$tablename][$f]['name']) ) {
         $qry = "ALTER TABLE ".$tablename." ADD COLUMN ".$dbMaster[$tablename][$f]['name']." ".$dbMaster[$tablename][$f]['type'];

      } else {

         # Rename column?
         if ( $dbLocal[$tablename][$f]['name'] != $dbMaster[$tablename][$f]['name'] ) {
            $qry = "ALTER TABLE ".$tablename." CHANGE ".$dbLocal[$tablename][$f]['name']." ".$dbMaster[$tablename][$f]['name']." ".$dbMaster[$tablename][$f]['type'];

         } else {

            # Change column length/type?
            if ( $dbLocal[$tablename][$f]['type'] != $dbMaster[$tablename][$f]['type'] ) {
               $qry = "ALTER TABLE ".$tablename." MODIFY ".$dbMaster[$tablename][$f]['name']." ".$dbMaster[$tablename][$f]['type'];
            }

         } // End if column names don't match

      } // End if isset(column)

      if ( isset($qry) ) {
         if ( !$rez = mysql_query($qry) ) { echo "There was a problem updating the db table structure for '".$tablename."'. If you experience problems that you think may be related to this, you might try dropping the '".$tablename."' and logging-in again so the can product re-create it fresh.<br/>".mysql_error(); }
      }

   }
}


//# Loop through ALL tables and check/fix column structure
//foreach ( $tables as $key->$tablename ) {
//   check_table_structure($tablename);
//}


/*---------------------------------------------------------------------------------------------------------*
 ___                 _   __  _        ___  _              _
/ __| _ __  ___  __ (_) / _|(_) __   / __|| |_   ___  __ | |__ ___
\__ \| '_ \/ -_)/ _|| ||  _|| |/ _| | (__ | ' \ / -_)/ _|| / /(_-<
|___/| .__/\___|\__||_||_|  |_|\__|  \___||_||_|\___|\__||_\_\/__/
     |_|
# Check/fix other known problem areas
/*---------------------------------------------------------------------------------------------------------*/

//# cart_options - make sure it has exactly one row
//if ( table_exists("cart_options") ) {
//   $qry = "SELECT * FROM cart_options";
//   $rez = mysql_query($qry);
//
//   if ( mysql_num_rows($rez) < 1 ) {
//      # Drop then re-create w/default data
//      if ( !mysql_query("DROP TABLE cart_options") ) { echo "Unable to drop cart_options because...<br/>".mysql_error(); }
//      include($_SESSION['docroot_path']."/sohoadmin/program/modules/mods_full/shopping_cart/includes/cart_options.dbtable.php");
//   }
//}
//
//# site_specs - make sure it has exactly one row
//if ( table_exists("site_specs") ) {
//   $qry = "SELECT * FROM site_specs";
//   $rez = mysql_query($qry);
//
//   if ( mysql_num_rows($rez) < 1 ) {
//      $nowww = eregi_replace("^www.", "", $this_ip);
//
//      # Build INSERT data
//      $nDis = "'$dfuser_company','$dfuser_address','$dfuser_aptnum','$dfuser_city',";
//      $nDis .= "'$dfuser_state','$dfuser_zip','$dfuser_country',";
//      $nDis .= "'$dfuser_phone','$dfuser_email@$nowww','$this_ip',";
//      $nDis .= "'Home','','$lang_set',";
//      $nDis .= "'$newscat','$promocat',";
//      $nDis .= "'".date('Y')." $dfuser_company','','',"; // Clear through df_misc2
//      $nDis .= "'$headertext','$subheadertext','', 'Home', ''"; // Clear through df_fax
//
//      # CREATE TABLE site_specs
//
//      # INSERT INTO site_specs
//      if ( !mysql_query("INSERT INTO site_specs VALUES($nDis)") ) { "Unable to insert default data into site_specs table!"; }
//   }
//} // End if site_specs exists


//////# How it should work in the end
//////include("../../filebin/build_dbtables.php");
//////
//////$existing_tables = dbtables();
//////
//////foreach ( $existing_tables as $key=>$table ) {
//////   if ( check_table_structure($table) ) {
//////      echo $table." -COOL<br/>";
//////   } else {
//////      echo $table." <span style=\"color: red;\"><b>-NOT COOL</b></span><br/>";
//////   }
//////}
?>