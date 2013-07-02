<?php
#================================================================================
# Super Search Plugin
# Ouput search stats tables (called via ajax method by statistics.php)
#================================================================================
session_start();
if(!include($_SESSION['docroot_path']."/sohoadmin/program/includes/product_gui.php")) {
  exit;
}
if ( $_GET['limit'] == "nolimit" ) {
   $top_x = "All";
} else {
   $top_x = "Top ".$_GET['limit'];
}
?>
   <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
<!---     <tr>
     <td colspan="3"><span class="bold red">*</span> Denotes queries that returned zero matches</td>
    </tr> -->
    <tr>
     <td width="34%" valign="top"><? echo $top_x; ?> search queries</td>
     <td width="33%" valign="top">Queries that returned results</td>
     <td width="33%" valign="top"><span class="bold red">*</span>Queries that returned zero matches</td>
    </tr>
    <tr>
     <td valign="top">
      <table valign="top" width="100%" border="1" cellspacing="0" cellpadding="4">
       <tr>
        <td class="col_title">&nbsp;</td>
        <td class="col_title" width="75%">Search Term </td>
        <td class="col_title">Searches</td>
       </tr>

<?php
# Create search_stats table if it does not exist
if ( !table_exists("search_stats") ) { if(!mysql_query("CREATE TABLE search_stats (search_count VARCHAR(30), search_phrase VARCHAR(100), month VARCHAR(40), found VARCHAR(3))")) { echo mysql_error(); exit; } }

# Build db query to pull stats
$qry = "select * from search_stats";
if ( $_GET['month'] != "all" ) { $qry .= " where month='".$_GET['month']."'"; } // Pull from specific month?
$qry .= " order by search_count desc, search_phrase asc";
if ( $_GET['limit'] != "nolimit" ) { $qry .= " limit ".$_GET['limit']; } // Only pull top X results
$rez = mysql_query($qry);
$bgcolor = "bg_blue_f8";
$counter = 1;
while ( $getData = mysql_fetch_array($rez) ) {

   # Alternate bg color
   if ( $bgcolor == "bg_blue_f8" ) { $bgcolor = "bg_gray_ef"; } else { $bgcolor = "bg_blue_f8"; }

   # Spit out html row
   echo "    <tr class=\"".$bgcolor."\">\n";
   echo "     <td class=\"gray\">".$counter."</td>\n";
   if ( $getData['found'] == "no" ) {
      echo "     <td><a href=\"http://".$_SESSION['docroot_url']."/search.php?q=".$getData['search_phrase']."\" target=\"_blank\">".$getData['search_phrase']."</a><span class=\"bold red\">*</span></td>\n";
   } else {
      echo "     <td><a href=\"http://".$_SESSION['docroot_url']."/search.php?q=".$getData['search_phrase']."\" target=\"_blank\">".$getData['search_phrase']."</a></td>\n";
   }
   echo "     <td>".$getData['search_count']."</td>\n";
   echo "    </tr>\n";

   $counter++;
}
?>
      </table>
     </td>


     <td valign="top">
      <table width="100%" border="1" cellspacing="0" cellpadding="4">
       <tr>
        <td class="col_title">&nbsp;</td>
        <td class="col_title" width="75%">Search Term</td>
        <td class="col_title">Searches</td>
       </tr>

<?
# Queries that DID return results
# Re-run original query and filter out non-matching queries
$rez = mysql_query($qry);
$bgcolor = "bg_blue_f8";
$counter = 1;
while ( $getData2 = mysql_fetch_array($rez) ) {

   # Spit out html row
   if ( $getData2['found'] == "yes" ) {
      # Alternate bg color
      if ( $bgcolor == "bg_blue_f8" ) { $bgcolor = "bg_gray_ef"; } else { $bgcolor = "bg_blue_f8"; }

      echo "    <tr class=\"".$bgcolor."\">\n";
      echo "     <td class=\"gray\">".$counter."</td>\n";
      echo "     <td><a href=\"http://".$_SESSION['docroot_url']."/search.php?q=".$getData2['search_phrase']."\" target=\"_blank\">".$getData2['search_phrase']."</a></td>\n";
      echo "     <td>".$getData2['search_count']."</td>\n";
      echo "    </tr>\n";
      $counter++;
   }
}
?>
      </table>
     </td>


     <td valign="top">
      <table width="100%" border="1" cellspacing="0" cellpadding="4">
       <tr>
        <td class="col_title">&nbsp;</td>
        <td class="col_title" width="75%">Search Term </td>
        <td class="col_title">Searches</td>
       </tr>
<?php
# Queries that did NOT return results
# Re-run original query and filter out matching queries
//$qry = "select * from search_stats where found = 'no'";
//if ( $_GET['month'] != "all" ) { $qry .= " and month='".$_GET['month']."'"; } // Pull from specific month?
//$qry .= " order by search_count desc, search_phrase asc";
//if ( $_GET['limit'] != "nolimit" ) { $qry .= " limit ".$_GET['limit']; } // Only pull top X results
$rez = mysql_query($qry);
$bgcolor = "bg_blue_f8";
$counter = 1;
while ( $getData = mysql_fetch_array($rez) ) {

   # Spit out html row
   if ( $getData['found'] == "no" ) {
      # Alternate bg color
      if ( $bgcolor == "bg_blue_f8" ) { $bgcolor = "bg_gray_ef"; } else { $bgcolor = "bg_blue_f8"; }

      echo "    <tr class=\"".$bgcolor."\">\n";
      echo "     <td class=\"gray\">".$counter."</td>\n";
      echo "     <td><a href=\"http://".$_SESSION['docroot_url']."/search.php?q=".$getData['search_phrase']."\" target=\"_blank\">".$getData['search_phrase']."</a></td>\n";
      echo "     <td>".$getData['search_count']."</td>\n";
      echo "    </tr>\n";

      $counter++;
   }
}
?>
      </table>
     </td>
    </tr>
   </table>