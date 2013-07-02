<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
####################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com
##
## COPYRIGHT NOTICE
## Copyright 1999-2007 Soholaunch.com, Inc. and Mike Johnston All Rights Reserved.
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
####################################################################################
#===================================================================================================================
# Functions for Template Manager
# Originally included by site_templates.php
#===================================================================================================================
error_reporting(E_PARSE);
session_start();

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../includes/product_gui.php');
chdir($curdir);
$globalprefObj = new userdata('global');
# Global vars for use in template-related scripts
# Defined globally here so you don't have to type out over and over again
$tpl_base_path = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages";
$tpl_base_url = $_SESSION['docroot_url']."/sohoadmin/program/modules/site_templates/pages";

# Define different html layout files to check for in a given template foder
$layout_files = array("index.html", "home.html", "cart.html", "news.html");
//$layout_files[] = array('filename'=>"index.html", 'display_name'=>"Default site page layout");
//$layout_files[] = array('filename'=>"home.html", 'display_name'=>"Splash home page layout");
//$layout_files[] = array('filename'=>"cart.html", 'display_name'=>"Shopping cart layout");
$layout_names = array();
$layout_names['index.html'] = "Default site page layout";
$layout_names['home.html'] = "Splash home page layout";
$layout_names['cart.html'] = "Shopping page layout";
$layout_names['news.html'] = "News article page layout";


# Set name of site base template from template.conf
//$filename = $_SESSION['docroot_path']."/sohoadmin/tmp_content/template.conf";
//if (file_exists("$filename")) {
//   $file = fopen("$filename", "r");
//   $CUR_TEMPLATE = fread($file,filesize($filename));
//   fclose($file);
//   $CUR_TEMPLATE = rtrim($CUR_TEMPLATE);
//}

$CUR_TEMPLATE = rtrim($globalprefObj->get('site_base_template'));

# Set list of templates assigned to individual pages
$ptrez = mysql_query("SELECT page_name, template FROM site_pages GROUP BY TEMPLATE");
$page_templates = array();
while ( $getPg= mysql_fetch_array($ptrez) ) {
   if ( $getPg['template'] != "" && $getPg['template'] != $CUR_TEMPLATE ) {
      $page_templates[] = $getPg['template'];
   }
}



# Searches template html file(s) for $string and returns true/false
# Note: Depends on $CUR_TEMPLATE var and $page_templates array already being defined by parent script
function findin_template($string) {
   $base_path = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages";

   # Will contain html for all files
   $template_html = "";

   # Build list of in-use template(s)
   $folders = array();
   $folders[] = $GLOBALS['CUR_TEMPLATE'];
   foreach ( $GLOBALS['page_templates'] as $key=>$template ) {
      if ( !in_array($template, $folders) ) {
         $folders[] = $template;
      }
   }

   # Put html from all files into container for searching
   foreach ( $folders as $key=>$template ) {
      $template_html .= file_get_contents($base_path."/".$template."/index.html");
   }

   if ( eregi($string, $template_html) ) {
      return true;
   } else {
      return false;
   }
}


# Returns template name as formatted for display - CAT > Name (version)
function format_templatename($foldername) {
   $tname = split("-", $foldername);

   # Category
   $display = $tname[0]." &gt; ";

   # Template name
   $display .= ucwords(str_replace("_", " ", $tname[1]));

   # Color/version
   $display .= " (".strtolower(str_replace("_", " ", $tname[2])).")";

   return $display;
}


# Returns array containing every template html file currently used on website
# Note: Depends on $CUR_TEMPLATE var and $page_templates array already being defined by parent script
# Note2: If option $searchfor is passed, this function assumes that you already know that
#        at least one template contains $searchfor (i.e. by already calling findin_template).
#        As in, will just return empty array if zero occurances are found
# Note3: $exclude option = folder name of template NOT to include in the list
#        ...so you can pull "besides the one currently being worked on"
function inuse_templates($searchfor = "", $exclude = "") {
   $base_path = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages";

   # Will contain html for all files
   $template_html = "";

   # Build list of in-use template(s)
   $all_folders = array();

   # Start with site base template
   $all_folders[] = $GLOBALS['CUR_TEMPLATE'];

   # Page-assigned templates
   foreach ( $GLOBALS['page_templates'] as $key=>$template ) {
      if ( !in_array($template, $all_folders) ) {
         $all_folders[$template] = $template;
      }
   }

   # This is the one that actually gets returned, even if it might ultimately be exactly the same as $all_folders if no searchin or exclude is passed
   $foundin_folders = array();

   if ( $searchfor == "" ) {
      # Return all in-user template folders
      $foundin_folders = $all_folders;

   } else {
      # Limit folder list to templates where $searchfor found in their html file(s)

      foreach ( $all_folders as $key=>$template ) {
         $pathtotemplate = $base_path."/".$template;

         # Put html from this template's html file(s) into container for searching
         $template_html = "";
         foreach ( $GLOBALS['layout_files'] as $key=>$htmlfile ) {
            $pathtohtmlfile = $pathtotemplate."/".$htmlfile;
            if ( file_exists($pathtohtmlfile) ) {
               $template_html .= file_get_contents($pathtohtmlfile);
            }
         }

         # Search html for passed string
         if ( strpos($template_html, $searchfor) !== false ) {
            $foundin_folders[$template] = $template;
         }

      } // End for each $all_folders

   } // End else $searchfor != ""

   # Loop and strip out exlude template?
   if ( $exclude != "" ) {
      unset($foundin_folders[$exclude]);
   }

   return $foundin_folders;

} // End inuse_templates() function


# layouts_used(CORPORATE-My_Template-blue)
# Used so loops can know how many and what layouts to check (i.e. for certain pound var config)
# Returns array of html layout files found in a given template folder
function layouts_used($template_folder) {
   $layouts_used = array();
   $pathtotemplate = $GLOBALS['tpl_base_path']."/".$template_folder;

   foreach ( $GLOBALS['layout_files'] as $key=>$htmlfile ) {
      $pathtohtmlfile = $pathtotemplate."/".$htmlfile;
      if ( file_exists($pathtohtmlfile) ) {
         $layouts_used[] = $htmlfile;
      }
   }

   return $layouts_used;
}

?>