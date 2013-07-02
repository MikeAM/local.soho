<?php
error_reporting(E_PARSE);
session_start();
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

#=======================================================================
# Download factory template library as defined in Branding Controls
# This script is included by first_login.php
#======================================================================

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../program/includes/product_gui.php');
chdir($curdir);


$site_templates_dir = $_SESSION['doc_root']."/sohoadmin/program/modules/site_templates";
$pages_dir = $_SESSION['doc_root']."/sohoadmin/program/modules/site_templates/pages";

if ( !testWrite($pages_dir) ) {
   //echo "Error: cannot write to template folder (".$pages_dir.")."; exit;
   echo "Error: cannot write to template folder (".$pages_dir.").";

} else {

//   # Kill Soholaunch default templates if host only wants his custom library to be installed
//   if ( $_SESSION['hostco']['soholaunch_template_library'] === "no" ) {
//      rename($pages_dir, $pages_dir."-backup");
//      //unlink($pages_dir, $pages_dir);
//
//      # Recreate pages dir
//      mkdir($pages_dir);
//   }

   # Make sure host data in session
   if ( count($_SESSION['hostco']) < 5 ) {
      /*---------------------------------------------------------------------------------------------------------*
       ___                      _  _               ___         __
      | _ ) _ _  __ _  _ _   __| |(_) _ _   __ _  |_ _| _ _   / _| ___
      | _ \| '_|/ _` || ' \ / _` || || ' \ / _` |  | | | ' \ |  _|/ _ \
      |___/|_|  \__,_||_||_|\__,_||_||_||_|\__, | |___||_||_||_|  \___/
                                           |___/
      /*---------------------------------------------------------------------------------------------------------*/
      # Pull down latest host config data from partner area
      include("includes/get_host_config.php");

      # Host config data (branding options)
      $hostco_file = "config/hostco.conf.php";

      # Read host config options into session array
      if ( !$hostco_fp = fopen($hostco_file, 'r') ) {
         //echo "Unable to open host config file."; exit;

      } else {
         if ( !$hostco_data = fread($hostco_fp, filesize($hostco_file)) ) {
            //echo "Unable to read config file!"; exit;
         }
         $_SESSION['hostco'] = unserialize($hostco_data);
         fclose($hostco_fp);

         # Set various defaults
         if ( $_SESSION['hostco']['get_more_plugins_url'] == "" ) {
            $_SESSION['hostco']['get_more_plugins_url'] = "addons.soholaunch.com";
         }

         if ( $_SESSION['hostco']['get_more_templates_url'] == "" ) {
            $_SESSION['hostco']['get_more_templates_url'] = "addons.soholaunch.com";
         }
      }
   } // End if count($_SESSION['hostco'])

   # Download and install custom template library?
   if ( $_SESSION['hostco']['custom_template_library'] == "yes" && strlen($_SESSION['hostco']['custom_template_zip_url']) > 7 ) {
      $zipfile = basename($_SESSION['hostco']['custom_template_zip_url']);
      $remotepath = $_SESSION['hostco']['custom_template_zip_url'];
      $localpath = $pages_dir."/".basename($_SESSION['hostco']['custom_template_zip_url']);
      $dl = new file_download($remotepath, $localpath);

      if ( file_exists($localpath) ) {
         //echo "File downloaded and ready for extract!"; exit;
      	unZip($localpath);


         # Kill downloaded zip file

         @unlink($localpath);

         # Make sure permissions don't get screwed up on suexec servers
         $php_suexec = strtoupper(php_sapi_name());
         if ( $php_suexec == "CGI" ) {
            shell_exec("chmod -R 0755 ".$_SESSION['doc_root']."/sohoadmin");
         }

      } else {
         echo "Local file does not exist!".$php_errormsg; exit;
      }
   }

   # Download and install Soholaunch default templates? (< 10 thing is basic check to try and make sure templates are not already installed as with -full wrap)
   $template_folders = dirlist($_SESSION['doc_root']."/sohoadmin/program/modules/site_templates/pages");
   if ( $_SESSION['hostco']['soholaunch_template_library'] != "no" && count($template_folders['dirs']) < 3 ) {
      $latest_build = build_info();
      $template_zip = str_replace("-lite", "-templates", basename($latest_build['download_lite']));
      $remotepath = "http://update.securexfer.net/wrap_templates/".$template_zip;
      $localpath = $_SESSION['doc_root']."/".$template_zip;
      //echo $template_zip; exit;
      $grabum = new file_download($remotepath, $localpath);

      if ( file_exists($localpath) ) {
//         extract_tgz($localpath);
		unZip($localpath);
         # Kill downloaded zip file
         @unlink($localpath);

         # Make sure permissions don't get screwed up on suexec servers
         $php_suexec = strtoupper(php_sapi_name());
         if ( $php_suexec == "CGI" ) {
            shell_exec("chmod -R 0755 ".$_SESSION['doc_root']."/sohoadmin");
         }

      } else {
         echo "Local file does not exist!".$php_errormsg; exit;
      }
   }
}



?>