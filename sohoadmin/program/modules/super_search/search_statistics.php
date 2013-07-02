<?PHP
error_reporting(E_PARSE);
session_start();
if(!include('../../includes/product_gui.php')) {
  exit;
}



# Enable/Disabled logging for search stats
if ( isset($_GET['set_stats']) ) {
   $qry = "update search_core set STATS = '".$_GET['set_stats']."'";
   mysql_query($qry);
}

# Clear all stats logs
if ( isset($_GET['reset_stats']) ) {
   $qry = "delete from search_stats";
   mysql_query($qry);
}

ob_start();
?>
<link rel="stylesheet" type="text/css" href="module.css">
<style>
#account_id {
   font-family: verdana, arial, helvetica, sans-serif;
   font-size: 10px;
   width: 65px;
}

#username {
   font-family: verdana, arial, helvetica, sans-serif;
   font-size: 10px;
   width: 220px;
}

#password {
   font-family: verdana, arial, helvetica, sans-serif;
   font-size: 10px;
   width: 100px;
}

h1 { font-size: 13px; }

.help_link {
   color: #ff7900;
   text-decoration: underline;
   cursor: pointer;
}
</style>

<script language="javascript">
//---------------------------------------------------------------------------------------------------------
//      _      _   _   __  __
//     /_\  _ | | /_\  \ \/ /
//    / _ \| || |/ _ \  >  <
//   /_/ \_\\__//_/ \_\/_/\_\
//
//---------------------------------------------------------------------------------------------------------
// The following script (as commonly seen in other AJAX javascripts) is used to detect which browser the client is using.
// If the browser is Internet Explorer we make the object with ActiveX.
// (note that ActiveX must be enabled for it to work in IE)
function makeObject() {
   var x;
   var browser = navigator.appName;

   if ( browser == "Microsoft Internet Explorer" ) {
      x = new ActiveXObject("Microsoft.XMLHTTP");
   } else {
      x = new XMLHttpRequest();
   }

   return x;
}

// The javascript variable 'request' now holds our request object.
// Without this, there's no need to continue reading because it won't work ;)
var request = makeObject();

function ajaxDo(qryString, boxid) {
   //alert(qryString+', '+boxid);

   rezBox = boxid; // Make global so parseInfo can get it

   // The function open() is used to open a connection. Parameters are 'method' and 'url'. For this tutorial we use GET.
   request.open('get', qryString);

   // This tells the script to call parseInfo() when the ready state is changed
   request.onreadystatechange = parseInfo;

   // This sends whatever we need to send. Unless you're using POST as method, the parameter is to remain empty.
   request.send('');

}

function parseInfo() {
   // Loading
   if ( request.readyState == 1 ) {
      document.getElementById(rezBox).innerHTML = 'Loading...';
   }

   // Finished
   if ( request.readyState == 4 ) {
      var answer = request.responseText;
      document.getElementById(rezBox).innerHTML = answer;
   }
}

// Gets confirmation before redirecting to passed url
function confirm_reset_stats(url) {
   var usure = window.confirm("Are you sure you want to clear ALL of your current search statistics logs? This will effectively be like starting over as far as search statistics are concerned.");

   if ( usure == true ) {
      document.location.href=url;
   }
}
</script>

<!---Preload rollover images in hidden div--->
<div style="display: none;">
 <img src="options_button-hover.gif" width="1" height="1">
 <img src="uninstall_button-hover.gif" width="1" height="1">
 <img src="install_plugin-hover.gif" width="1" height="1">
</div>


<!---Module heading--->
<div id="user_option" style="height: 100%;overflow: auto;">
<table width="95%" border="0" align="center" cellpadding="5" cellspacing="0" class="feature_sub" style="margin-top: 10px;">
 <tr>
  <td colspan="2" valign="top" class="nopad">
   <table width="100%" border="0" cellspacing="0" cellpadding="5" >
    <tr>

<?php
# Outputs section links
include("link_bar.php");
echo $link_bar;
?>

    </tr>
   </table>
  </td>
 </tr>

<?php
# Get current stats settings
$qry = "select STATS from search_core";
$rez = mysql_query($qry);
$stats_setting = mysql_result($rez, 0);


# Enable search statistics?
echo "      <tr>\n";
echo "       <td align=\"left\" valign=\"bottom\">\n";
echo "        Search statistics are currently:\n";
if ( $stats_setting == "no" ) {
   echo "<a href=\"".$_SERVER['PHP_SELF']."?set_stats=yes\" class=\"del\">Disabled</a>\n";
} else {
   echo "<a href=\"".$_SERVER['PHP_SELF']."?set_stats=no\" class=\"sav\">Enabled</a>\n";
}
echo " (click to change)\n";
echo "       </td>\n";

# Quickstats output layer
echo "  <td rowspan=\"2\" valign=\"top\" class=\"nopad\">\n";

echo "   <div id=\"quickstats_output\">\n";
//echo "    asdfasfd\n";
echo "   </div>\n";
echo "  </td>\n";

echo "      </tr>\n";


# Pre-build javascript/ajax 'submit' action for readability
$month_dd = "'+document.getElementById('month').value+'";
$limit_dd = "'+document.getElementById('limit').value+'";

$pull_stats = "";
//$pull_stats .= "alert('Date range: [".$start_date."]');";
$pull_stats .= "ajaxDo(";
$pull_stats .= "'statistics.ajax.php?";
$pull_stats .= "month=".$month_dd;
$pull_stats .= "&limit=".$limit_dd;
$pull_stats .= "', 'ajax_output');";

$quick_stats = "";
$quick_stats .= "ajaxDo(";
$quick_stats .= "'quickstats.ajax.php?";
$quick_stats .= "month=".$month_dd;
$quick_stats .= "&limit=".$limit_dd;
$quick_stats .= "', 'quickstats_output');";
?>
 <tr>
  <td valign="top">
   <table border="0" cellpadding="3" cellspacing="0">
    <tr>
     <td>Show top queries for a specific month?</td>
     <td>
      <select id="month" onchange="<? echo $pull_stats; ?>">
       <option value="all" selected>Show for all months</option>

<?php
# Pull list of available months
$qry = "SELECT month FROM search_stats group by month order by month asc";
$rez = mysql_query($qry);
while ( $getMo = mysql_fetch_array($rez) ) {
   echo "       <option>".$getMo['month']."</option>\n";
}
?>
      </select>
     </td>

     <td style="padding-left: 20px;">Limit to only top X number of queries? </td>
     <td>
      <select id="limit" onchange="<? echo $pull_stats; ?>">
       <option value="10" selected>Top 10</option>
       <option value="50">Top 50</option>
       <option value="100">Top 100</option>
       <option value="nolimit">No limit</option>
      </select>
     </td>
    </tr>
   </table>
  </td>
 </tr>


 <tr>
  <td colspan="2" valign="top">

   <!---Start ajax output layer-->
   <div id="ajax_output">

   </div>
   <!---End ajax output layer-->

   <div style="text-align: center;">
    <a href="#" onclick="confirm_reset_stats('<? echo $_SERVER['PHP_SELF']; ?>?reset_stats=yes');" class="del">Clear all stats</a>
   </div>

  </td>
 </tr>
</table>
</div>
<!---End user_options div-->

<script type="text/javascript">
// Pull default stats on page load
<?php echo $pull_stats; ?>
</script>

<?php
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Find out what your visitors are looking for on your site, and whether or not they're finding it.");

$module = new smt_module($module_html);
$module->meta_title = "Search Statistics";
$module->add_breadcrumb_link("Search Statistics", "program/modules/super_search/search_statistics.php");
//$module->add_breadcrumb_link("Create Campaign", "program/modules/mods_full/enewsletter/create_campaign.php");
$module->icon_img = "program/modules/super_search/plugin_icon-supersearch.gif";
$module->heading_text = "Search Statistics";
$module->description_text = $instructions;
$module->good_to_go();

?>