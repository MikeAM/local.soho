<?php
error_reporting(E_PARSE);
session_start();
require_once("../../includes/product_gui.php");


/*---------------------------------------------------------------------------------------------------------*
   ____                   ____ _       __    __
  / __/___ _ _  __ ___   / __/(_)___  / /___/ /
 _\ \ / _ `/| |/ // -_) / _/ / // -_)/ // _  /
/___/ \_,_/ |___/ \__/ /_/  /_/ \__//_/ \_,_/

# REQUIRES: field_id, save_field, newvalue
/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['save_field'] != "" ) {
   $qry = "update form_fields set ".$_GET['save_field']." = '".$_GET['newvalue']."' where field_id = '".$_GET['field_id']."'";
   if ( $rez = mysql_query($qry) ) {
      echo "saved";
   } else {
      echo "NOT saved";
   }
} else {
   echo "blah";
}

?>

<script type="text/javascript" src="../../includes/display_elements/js_functions.php"></script>