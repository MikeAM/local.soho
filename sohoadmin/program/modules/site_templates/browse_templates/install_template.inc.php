<?php
#=========================================================================================================
# Download/Install selected template
#=========================================================================================================
# ACCEPTS: $_GET['template_folder']

/* ==================START Script Outline==================*
#Is template licensed for this site? (free or paid-for)
# Pass template folder name, domain, and server hostname to license check api
if yes licensed {
   # Get template file download url from api

   # Download remote template .zip file to /pages folder

   # Extract downloaded .zip in pages folder

   # Verify that new template folder exists in pages

   # Delete copy of downloaded zip in pages folder

   # Redirect to Browse Templates page (preserve search filter settings so last-viewed templates still display)

   # Show success message (report at top or something, not popup that they have to close)
      # "Template Installed! Go to My Templates to view/manage it."

} elseif not licensed {
   # Redirect to Browse Templates (preserve search filter settings)

   # Show "not license-able" message popup

}
/* ==================END Script Outline==================*/
require_once('../../../includes/product_gui.php');
error_reporting(E_PARSE);
# Was template folder passed as expected?
if ( $_GET['template_folder'] != "" ) {
//   echo "<p>Template Folder: [".$_GET['template_folder']."]</p>"; exit;

   $errorbreak = false;

   while ( $errorbreak == false ) {

      # Licensed?
      if ( !addon_licensed($_GET['template_folder']) ) {
         $report[] = "You must purchase a license for this template before you can install it.";
         break;
      }

      # Get template file download url from api
      $getAddon = addons_api($_GET['template_folder']);


      # Download remote template .zip file to /pages folder
      $sitetemplates_pages_folder = $_SESSION['docroot_path'].'/sohoadmin/program/modules/site_templates/pages';
      $downloaded_zip_path = $sitetemplates_pages_folder.'/'.$getAddon['zipfile_name'];
      $download_url = $getAddon['zipfile_url']."&update_domain=".$_SESSION['this_ip'];
      $dlFile = new file_download($download_url, $downloaded_zip_path, "chill");
      if ( !$dlFile->dlnow() ) {
         $report[] = $dlFile->msg;
         break;
      }

      # Did file download?
      if ( !file_exists($downloaded_zip_path) ) {
         $report[] = "Unable to download template file successfully....<br/>".$getAddon['zipfile_url'];
         break;
      }

      # Extract downloaded .zip in pages folder
      $odir = getcwd();
      chdir($sitetemplates_pages_folder);
      soho_unzip($downloaded_zip_path);
      chdir($odir);
      
      # Readable template name
      $tmp = split("-", $getAddon['folder_name']);
      $tCategory = strtoupper($tmp[0]);
      $tmp[1] = eregi_replace("_", " ", $tmp[1]);
      $display_name = "$tCategory  > $tmp[1] ";
      if (!eregi("none", $tmp[2])) { $display_name .= "($tmp[2])"; }
      

      # Verify that new template folder exists in pages
      if ( is_dir($sitetemplates_pages_folder.'/'.$getAddon['folder_name']) ) {
         $report[] = "Template <b>".$display_name."</b> installed successfully!";
         $report[] = "<a href=\"../../site_templates.php\">Click here to go to the Template Manager</a>";
      } else {
         $report[] = "Unable to extract template file successfully. Template folder [".$getAddon['folder_name']."] was not created.";
      }

      # Delete copy of downloaded zip in pages folder
      @unlink($downloaded_zip_path);


      # saftey net
      $errorbreak = true;

   }

//   echo testArray($report);
//   echo "done"; exit;
}



?>