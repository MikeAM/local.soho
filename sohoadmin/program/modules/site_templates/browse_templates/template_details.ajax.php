<?php
#=========================================================================================================
# Called via ajaxDo() in browse_templates.php
# Gets detailed info about passed template
# Ouput of this script is placed in #template_details box in browse_templates.php
#=========================================================================================================
# ACCEPTS: $_GET['addonid']

error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

$apiurl = "http://securexfer.net/remote_template/gettemplate_remote.api.php?addonid=".$_GET['addonid'];
$apireturn = file_get_contents($apiurl);

//echo "(".$apireturn.")<br/>\n";
//exit;

$getTemplate = unserialize($apireturn);


require_once("../../../includes/product_gui.php");
require_once("../../../includes/shared_functions.php");


# Plugin install/misc functions (hook_attach, hook_special, etc)
include_once("plugin_functions.php");

//# Pull and populate template features var ($template_features[])
//if(!include("remote_template_features.php")){
//   echo "Cannot include!";
//}else{
//   echo "yay!";
//}

//$addons_api = addons_api($_GET['plugin_folder']);

/*============================================================================================================================
# REFERENCE: gettemplate.api.php array structure
$output['addonid'] = $getAddon['PRIKEY'];
$output['name'] = $getAddon['PROD_NAME'];
$output['author'] = acc_info($getAddon['PROD_SHIPB'], "DISPLAY_NAME");
$output['updated'] = $getAddon['PROD_SHIPC'];
$output['thumbnail_url'] = "http://addons.soholaunch.com/media/image.php?fileid=".$getAddon['PROD_FULLIMAGENAME'];
$output['detailpage_url'] = "http://addons.soholaunch.com/View_Addon.php?addonid=".$getAddon['PRIKEY'];
============================================================================================================================*/
?>

<script type="text/javascript" src="http://<?php echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/display_elements/js_functions.php"></script>

 <h2><?php echo $getTemplate['name']; ?></h2>
 <img src="<?php echo $getTemplate['thumbnail_url']; ?>" width="150" height="102" border="0">

 <div id="info">
  <div id="features">
   <h3>Features:</h3>
  </div>

   <div id="layouts">
      <ul>
      <?
         foreach($getTemplate['template_features'] as $var=>$val){
            echo "         <li>".$val."</li>\n";
         }
         if(count($getTemplate['template_features']) == 0){
            echo "         <li>Oops, this template does not seem to be in the correct format.<br/>Please select a different template.</li>\n";
         }
      ?>
      </ul>
  </div> 

  <div class="ie_cleardiv"></div>
 </div>

<?
if(count($getTemplate['template_features']) > 0){
?>
 <div id="buttons">
  <button type="button" class="blueButton" onclick="popup_window('http://<?php echo $_SESSION['docroot_url']; ?>/index.php?rmtemplate=<?php echo $getTemplate['folder_name']; ?>', '', '800', '600');"><span><span>Preview on my website &gt;&gt;</span></span></button>
  <button type="button" class="greenButton" onclick="document.location.href='browse_templates.php?todo=install_template&template_folder=<?php echo $getTemplate['folder_name']; ?>';"><span><span>Install Template &gt;&gt;</span></span></button>

  <div class="ie_cleardiv"></div>
 </div>
<?
}
?>

 <div id="close_details" onclick="hideid('template_details');">[x] close</div>

 <div class="ie_cleardiv"></div>



<?
//   echo "       <div id=\"cell-".$a."\" ".$mouseover." class=\"template_container-off\" onclick=\"view_template_details('".$addon_id."');\">\n";
//   echo "        <img src=\"".$topaddons[$a]['thumbnail_url']."\" width=\"113\" height=\"77\" border=\"0\">\n";
//   echo "        <p class=\"thumbnail_caption\"><b>".$template_name."</b><br/>\n";
//   echo "        Updated: ".date("M d, Y", $topaddons[$a]['updated'])."</p>\n";
//   echo "       </div>\n";
?>