<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

#=====================================================================================
# Soholaunch(R) Site Management Tool
#
# Author:        Mike Morrison
# Homepage:      http://www.soholaunch.com
# Release Notes: http://wiki.soholaunch.com
#
# This Script: Simple example module to illustrate how to create a new
# module and keep it's look consistent with the rest of the product
#=====================================================================================

error_reporting(E_PARSE);
session_start();

require_once("../../../includes/product_gui.php");
require_once("../../../includes/smt_module.class.php");
# Plugin install/misc functions (hook_attach, hook_special, etc)
require_once("../../../webmaster/plugin_manager/plugin_functions.php");

header('Content-Type: text/html');

# PROCESS: Install template
if ( $_GET['todo'] == "install_template" ) {
   include("install_template.inc.php");
}


# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();


?>

<script type="text/javascript" src="browse_templates.js"></script>

<script type="text/javascript">
//$('#jqresult').load('http://securexfer.net/remote_template/list_addons_remote.api.php?type=templates&limit=25&next_start=0&category=all&sortby=name&color=all&paidonly=no&freeonly=yes&paidfirst=no');
//$('#template_results').load('http://securexfer.net/remote_template/list_addons_remote.api.php?type=templates&limit=25&next_start=0&category=all&sortby=name&color=all&paidonly=no&freeonly=yes&paidfirst=no');
//'template_results.ajax.php?category='+showCats+'&next_start='+next_start+'&color='+showColor+'&sortby='+sortBy+'&limit_num='+limit_num, 'template_results'
</script>

<link rel="stylesheet" type="text/css" href="browse_templates.css"/>

<!--- <div id="loading_overlay" style="">
   <div id="loading_box">
      <div id="loading_text">Loading Templates...</div>
   </div>
</div> -->

<!---Module html goes here-->
<div id="template_details">
 <h2>Template Details</h2>
 No template selected. Select a template thumbnail to see details.
</div>

<?php
if(count($report) > 0){
	echo "<div style=\"width:530px; color:green;background:white;border:2px solid #EAA510; padding:5px; margin:5px;\">\n";
	foreach($report as $rval){
		echo "<p>".$rval."</p>\n";
	}
	echo "</div>\n";
}
?>

<!--- START: search_tools -->
<fieldset id="search_tools" style="">
 <!--- browse_category -->
 <div id="browse_category" style="">
  <label for="category">Browse Category:</label>
  <select id="category" onchange="loadTemplates('category');">
   <option value="all" selected>All</option>
   <option value="animals_dog_cat_nature_pets">Animals</option>
   <option value="beauty_health_fitness_hiking_active_garden_food">Beauty and Health</option>
   <option value="business_tech_medical_college_industry_construction">Business</option>
   <option value="outdoors_hiking_active_travel_nature">Travel</option>
   <option value="business_art_dance_creative_music">Art</option>
   <option value="education_college">Education</option>
  </select>
 </div>

 <!--- color_container -->
 <div id="color_container" style="">
  <label for="show_colors">Show colors:</label>
  <select id="show_colors" onchange="loadTemplates('color');">
      <option value="all" selected>All</option>
      <?
      $category_colors = array("Blue", "Green", "Red", "Black", "Grey", "Purple", "Teal", "Orange", "Yellow", "Brown");
      foreach($category_colors as $var){
         echo "<option value=\"".$var."\">".$var."</option>\n";
      }
      ?>
  </select>
 </div>

 <!--- sort_container -->
 <div id="sort_container" style="">
  <label for="sort_by">Sort by:</label>
  <select id="sort_by" onchange="loadTemplates('sort');">
   <option value="name">Template Name</option>
   <option value="updated">Newest</option>
   <option value="downloaded" selected>Most Popular</option>
  </select>
 </div>
 
 <!--- limit_container -->
 <div id="limit_container" style="">
  <label for="limit_num">Display Number:</label>
  <select id="limit_num" onchange="loadTemplates('limit');">
   <option value="25">25</option>
   <option value="50" selected>50</option>
   <option value="75">75</option>
  </select>
 </div>

 <div class="ie_cleardiv"></div>
</fieldset>
<!--- END: filter_controls -->

<!--- template_results -->
<div id="template_results">

</div>

<!--- Next/Prev buttons -->
<div id="next_prev" style="position: relative; text-align: center; padding: 0 20; ">
   <div id="prev_btn" style="width: 100px; float:left; text-align: left; visibility: hidden; ">
      <a href="#" onclick="loadTemplates('prev')">
         <img src="24-arrow-previous.png" border="0" title="Back" />
      </a>
   </div>
   
   <div id="next_btn" style="width: 100px; float:right; text-align: right; visibility: visible; ">
      <a href="#" onclick="loadTemplates('next')">
         <img src="24-arrow-next.png" border="0" align="center" title="Next" />
      </a>
   </div>
   
   <div id="num_results_display" style="position: relative; width: 300px; margin: 0px auto 0px auto; text-align: center;">
      &nbsp;
   </div>
</div>

<script type="text/javascript">
//$('Layer1').style.visibility='hidden'
show_hide_layer('Layer1','','hide','userOpsLayer','','show');

//alert($('loading_overlay').style.width)

loadTemplates('all');
//ajaxDoBrowse('template_results.ajax.php?color=all', 'template_results');

</script>


<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

# Note: "Create Pages" used for example purposes. Replace with your own stuff.
$module = new smt_module($module_html);
$module->add_breadcrumb_link("Template Manager", "program/modules/site_templates.php");
$module->add_breadcrumb_link("Browse Templates", "program/modules/site_templates/browse_templates/browse_templates.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/create_pages-enabled.gif";
$module->heading_text = "Browse Templates";
$module->description_text = "Browse the latest and greatest inventory of website template layouts.";
$module->good_to_go();
?>