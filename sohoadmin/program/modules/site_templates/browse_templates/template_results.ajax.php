<?php
#=========================================================================================================
# Called via ajaxDoBrowse() in browse_templates.php
# Gets info on templates matching passed qry arguments and returns as clickable thumbnails
# Ouput of this script is placed in #template_results box in browse_templates.php
#=========================================================================================================

# ACCEPTS: $_GET['type'] = plugins/templates, $_GET['limit'] = num to pull,
#          $_GET['sortby'] = updated/downloaded, $_GET['paidonly'] = yes/no
#          $_GET['color']  $_GET['category']  $_GET['limit_num']   $_GET['next_start']
$apistring = "type=templates&limit=".$_GET['limit_num']."&next_start=".$_GET['next_start']."&category=".$_GET['category']."&sortby=".$_GET['sortby']."&color=".$_GET['color']."&paidonly=no&freeonly=yes&paidfirst=no";
$apiurl = "http://securexfer.net/remote_template/list_addons_remote.api.php?".$apistring;
//echo $apiurl; exit;



//include_once('../../../includes/shared_functions.php');
require_once('../../../includes/product_gui.php');


//echo "(".$apiurl.")";
//exit;
//(http://addons.soholaunch.com/api/list_addons_remote.api.php?type=templates&limit=50&next_start=&category=all&sortby=downloaded&color=all&paidonly=no&freeonly=yes&paidfirst=no)
//(http://addons.soholaunch.com/api/list_addons_remote.api.php?type=templates&limit=50&next_start=50&category=all&sortby=downloaded&color=all&paidonly=no&freeonly=yes&paidfirst=no)

$apireturn = include_r($apiurl);

$apipiece = split("~~~", $apireturn);

//foreach($apipiece as $var=>$val){
//   echo "var = (".$var.") val = (".$val.")<br>\n";
//}
//exit;

//echo "(".count($apipiece).")";
//exit;
$topaddons = unserialize($apipiece[0]);

$num_cats = 0;


$next_start = "";
if(count($apipiece) > 1){
   $next_start = $apipiece[1];
//   if(eregi("END", $next_start)){
//      $next_start = str_replace("END", $next_start);
//   }
}
header('Content-Type: text/html');
echo "<form>\n<input type=\"hidden\" id=\"next_start\" name=\"next_start\" value=\"".$next_start."\" />\n";
echo "<input type=\"hidden\" id=\"total_results\" name=\"total_results\" value=\"".$apipiece[2]."\" />\n";

//echo "(".$_GET['color'].")<br/>\n";

for ( $a = 0; $a < count($topaddons); $a++ ) {

   # Keep template name as consise as possible to avoid breaking display with awkwardly floated divs
   $template_name = $topaddons[$a]['name'];
   if ( strlen($template_name) > 24 ) {
      # Try stripping often-meaningless prefix
      $template_name = eregi_replace("[a-zA-Z0-9]+ - ", "", $template_name);
   }

   if ( strlen($template_name) > 20 ) {
      $template_name = substr($template_name, 0, 20);
   }

   # Just for sake of familiarity
   $addon_id = $topaddons[$a]['addonid'];

   //$linkhref = "https://addons.soholaunch.com/View_Addon.php?addonid=".$topaddons[$a]['addonid'];
   $mouseover = "onmouseover=\"setClass(this.id, 'template_container-on');\" onmouseout=\"setClass(this.id, 'template_container-off');\"";
   
   # Generate category list
   $cat_list[] = array(); 
   
   
   
   # Clean additional characters
   $clean_name = eregi_replace("[^a-zA-Z0-9 ]+", "", $topaddons[$a]['name']);
   $clean_name = str_replace("-", " ", $clean_name);
   $clean_name = str_replace(",", " ", $clean_name);
   
   $tmp_list[$a] = array();
   //$tmp_list[$a]['item_name'] = 
   $tmp_list[$a]['output'] = "<div id=\"cell-".$a."\" ".$mouseover." class=\"template_container-off\" onclick=\"view_template_details('".$addon_id."');\">\n";
   $tmp_list[$a]['output'] .= "	<img src=\"".$topaddons[$a]['thumbnail_url']."\" width=\"113\" height=\"77\" border=\"0\" />\n";
   $tmp_list[$a]['output'] .= "	<p class=\"thumbnail_caption\" style=\"white-space:nowrap;\"><strong>".$template_name."</strong><br/>\n";
   //$tmp_list[$a]['output'] .= "        <p class=\"thumbnail_caption\"><b>".$template_name."</b><br/>(".$topaddons[$a]['name'].")<br/>\n";
   $tmp_list[$a]['output'] .= "	Updated: ".date("M d, Y", $topaddons[$a]['updated'])."</p>\n";
   $tmp_list[$a]['output'] .= "</div>\n";
   echo $tmp_list[$a]['output'];
}

//foreach($cat_list as $var=>$val){
   //echo "var = (".$var.") val = (".$val.")<br>\n";
//   if(is_array($val)){
//      echo "<b>Found array (".count($val).")</b><br/>\n";
//      
//      foreach($val as $var1=>$val1){
//         echo "-----<b>var = (".$var1.") val = (".$val1.")</b><br>\n";
//      }
//   }
//}
echo "</form>"
?>