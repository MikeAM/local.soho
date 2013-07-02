<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


/*####################################################################################################################
 _____          __  _                                  _   _             _         _
/  ___|        / _|| |                                | | | |           | |       | |
\ `--.   ___  | |_ | |_ __      __ __ _  _ __  ___    | | | | _ __    __| |  __ _ | |_  ___  ___
 `--. \ / _ \ |  _|| __|\ \ /\ / // _` || '__|/ _ \   | | | || '_ \  / _` | / _` || __|/ _ \/ __|
/\__/ /| (_) || |  | |_  \ V  V /| (_| || |  |  __/   | |_| || |_) || (_| || (_| || |_|  __/\__ \
\____/  \___/ |_|   \__|  \_/\_/  \__,_||_|   \___|    \___/ | .__/  \__,_| \__,_| \__|\___||___/
                                                             | |
                                                             |_|
####################################################################################################################*/

error_reporting(E_PARSE);

# Include core interface scripts
require_once("../includes/product_gui.php");

# Start buffering output
ob_start();

# Restore build info array
$installed_build = build_info();

$updateprefs = new userdata("updateprefs");
$page_editor_details = new userdata("page_editor_details");

# Uncomment to test all availalbe updates (not just new ones)
//$installed_build['build_date'] = "1038053389";

//echo "[".ini_get('allow_url_fopen')."]<br/>";


# DEFAULT: Do not allow installation of internal test builds
if ( $updateprefs->get("testing_builds") == "" ) { $updateprefs->set("testing_builds", "no"); }
# SETTING: Chmod to 777 after?
if ( $_GET['testing_builds'] != "" ) { $updateprefs->set("testing_builds", $_GET['testing_builds']); }

# DEFAULT: Do not chmod to 777 after updating
if ( $updateprefs->get("chmod_after") == "" ) { $updateprefs->set("chmod_after", "no"); }
# SETTING: Chmod to 777 after?
if ( $_GET['chmod_after'] != "" ) { $updateprefs->set("chmod_after", $_GET['chmod_after']); }

# DEFAULT: Do not chmod ignore shell_exec output check
if ( $updateprefs->get("ignore_shellexec") == "" ) { $updateprefs->set("ignore_shellexec", "yes"); }
# SETTING: Chmod to 777 after?
if ( $_GET['ignore_shellexec'] != "" ) { $updateprefs->set("ignore_shellexec", $_GET['ignore_shellexec']); }


/*---------------------------------------------------------------------------------------------------------*
   ______ __                 __      _   __
  / ____// /_   ___   _____ / /__   / | / /____  _      __
 / /    / __ \ / _ \ / ___// //_/  /  |/ // __ \| | /| / /
/ /___ / / / //  __// /__ / ,<    / /|  // /_/ /| |/ |/ /
\____//_/ /_/ \___/ \___//_/|_|  /_/ |_/ \____/ |__/|__/

# Check for available auto updates
# Get build date from local file
# Compare dates
/*---------------------------------------------------------------------------------------------------------*/
//echo testArray($_GET);

if ( $_REQUEST['todo'] == "checknow" ) {

   # Make sure environment allows for updates to function
   $env_conflicts = updates_allowed(true);

   # Proceed if no error array returned
   if ( is_array($env_conflicts) ) {
      $popup_content = "problems"; // Show error list in popup

   } else {

      # Pull info on local build
      $installed_build['build_date'];

      # Pull remote info on latest STABLE
      $stable_avail = 0;

	$stable_update = include_r("http://update.securexfer.net/public_builds/api-ultra_build_info-stable.php");

      $stable_update = unserialize($stable_update);

      if ( is_array($stable_update) ) { $stable_avail = 1; }

      # Pull remote info on latest LATEST
      $latest_avail = 0;
      
	 $latest_update = include_r("http://update.securexfer.net/public_builds/api-ultra_build_info-latest.php");
      
      $latest_update = unserialize($latest_update);
      
      if ( is_array($latest_update) ) { $latest_avail = 1; }

      # Pull remote info on latest SOHO (soholaunch internal testing only)
      if ( $updateprefs->get("testing_builds") == "yes" ) {
         $soho_avail = 0;
         
         $soho_update = include_r("http://update.securexfer.net/public_builds/api-ultra_build_info-soho.php");
         
         $soho_update = unserialize($soho_update);
         
         if ( is_array($soho_update) ) { $soho_avail = 1; }
      }

      # Show available updates in 'Software Updates' popup
      $popup_content = "available_updates";

      # Disable updates that are already installed
      if ( $installed_build['build_date'] == $stable_update['build_date'] ) { $stable_disabled = " disabled"; }
      if ( $installed_build['build_date'] == $latest_update['build_date'] ) { $latest_disabled = " disabled"; }
      if ( $stable_update['build_date'] == $latest_update['build_date'] ) { $same_build = "yes"; } else { $same_build = "no"; }

   } // End if no $env_conflicts


   //echo testArray($stable_update);
   //echo testArray($latest_update);
   //exit;
}

/*---------------------------------------------------------------------------------------------------------*
    ____              __          __ __   __  __            __        __
   /  _/____   _____ / /_ ____ _ / // /  / / / /____   ____/ /____ _ / /_ ___
   / / / __ \ / ___// __// __ `// // /  / / / // __ \ / __  // __ `// __// _ \
 _/ / / / / /(__  )/ /_ / /_/ // // /  / /_/ // /_/ // /_/ // /_/ // /_ /  __/
/___//_/ /_//____/ \__/ \__,_//_//_/   \____// .___/ \__,_/ \__,_/ \__/ \___/
                                            /_/
/*---------------------------------------------------------------------------------------------------------*/
if ( $_REQUEST['todo'] == "install_update" ) {

      # Make sure they picked a build...and not the one that's already installed
      if ( $_POST['build_type'] == "" ) {
         $errors[] = lang("It appears that you have not selected a build to install, or that you are trying to install a version that is already installed").". <br><br> ".lang("Please try again and make sure you're choosing a version to install, and that it's different from the version already installed").".";
         $popup_content = "install_report"; // Return to 'choose update to install' popup
      }


      /*---------------------------------------------------------------------------------------------------------*
       ___                      _                _
      |   \  ___ __ __ __ _ _  | | ___  __ _  __| |
      | |) |/ _ \\ V  V /| ' \ | |/ _ \/ _` |/ _` |
      |___/ \___/ \_/\_/ |_||_||_|\___/\__,_|\__,_|
      /*---------------------------------------------------------------------------------------------------------*/
      # Proceed if no errors
      if ( count($errors) < 1 ) {
         
         $page_editor_details->set("refresh_editor", "2");
         
         # Pull info about selected update build
         
         $new_build = include_r("http://update.securexfer.net/public_builds/api-ultra_build_info-".$_POST['build_type'].".php");
         $new_build = unserialize($new_build);
         

         # Make sure we've got a download link, then grab the build!
         if ( $new_build['download_full'] != "" ) {

            # Save build file to...
            $downloaded_buildfile = $_SESSION['docroot_path']."/".basename($new_build['download_full']);

            # Download build file now!
            $dlUpdate = new file_download($new_build['download_full'], $downloaded_buildfile);

            if ( !file_exists($downloaded_buildfile) ) {
               if ( ini_get('allow_url_fopen') != "1" ) {
                  $errmsg = "";
                  $errmsg .= lang("Unable to download new build file")."! \n";
                  $errmsg .= lang("Make sure that allow_url_fopen is enabled in your php.ini file")." (".lang("You may have to get your web host to do this").").";
                  $errors[] = $errmsg;

               } else {
                  # Format involved data
                  $docrootPerms = array();
                  $tmpdata = array(posix_getpwuid(fileowner($_SESSION['docroot_path'])), posix_getgrgid(filegroup($_SESSION['docroot_path'])));
                  $docrootPerms['perms'] = substr(sprintf('%o', fileperms($_SESSION['docroot_path'])), -4);
                  $docrootPerms['owner'] = $tmpdata[0]['name'];
                  $docrootPerms['group'] = $tmpdata[1]['name'];
                  $nonsuexec_owners = array("nobody", "apache", "root");
                  if ( php_suexec() ) { $changeto_perms = "755"; } else { $changeto_perms = "775"; }
                  $errmsg = "";
                  $errmsg .= "<h2 class=\"nomar_top\">".lang("Unable to download new build file")."!</h2>\n";
                  $errmsg .= "<p>".lang("This error typically occurs when the document root folder")." (".basename($_SESSION['docroot_path']).") ".lang("is not writeable").".</p>";
                  $errmsg .= "<p><b>".lang("Current permissions set on")." ".basename($_SESSION['docroot_path'])." ".lang("folder").":</b><br/>\n";
                  $errmsg .= "<span class=\"mono\">".basename($_SESSION['docroot_path'])." ".$docrootPerms['perms']." ".$docrootPerms['owner']." ".$docrootPerms['group']."</span></p>";

                  $errmsg .= "<p><b>".lang("Reccommended Fixes").":</b><br/>";

                  if ( php_suexec() ) {
                     $errmsg .= lang("Change permissions on docroot to 755.");
                  } else {
                     # Non-suexec
                     $errmsg .= lang("Do one of the following")."...\n";
                     $errmsg .= "<ul>\n";

                     if ( eregi("^075", $docrootPerms['perms']) && in_array($docrootPerms['group'], $nonsuexec_owners) ) {
                        # 755 with user as owner
                        $errmsg .= "<li>".lang("Change permissions on this folder to")." <b>775</b>.</li>\n";
                        $errmsg .= "<li>".lang("Leave permissions as they are but change owner (chown) to")." <b>".$docrootPerms['group']."</b>.</li>\n";
                     } else {
                        # 775 with user.user
                        $errmsg .= "<li>".lang("Change permissions on this folder to")." <b>777</b>.</li>\n";
                     }
                     $errmsg .= "</ul>\n";
                  }
                  $errmsg .= "<p><b>".lang("How to change permissions")."</b><br/>\n";
                  $errmsg .= lang("Log-in to your site via FTP, right-click on the")." <b>".basename($_SESSION['docroot_path'])."</b> ".lang("folder").",\n";
                  $errmsg .= lang("and checking/un-check the various read/write boxes until the permissions number value")." &mdash; \n";
                  $errmsg .= lang("usually displayed above the checkboxes in most FTP software")." &mdash; ".lang("equals the desired setting").".</p>";

                  $errmsg .= "<p><b>".lang("How to change owner/group")."</b><br/>\n";
                  $errmsg .= lang("Ask your web host to do this, unless you're on a dedicated server with root access and are comfortable logging-in via SSH and typing this command")."...<br/>\n";
                  $errmsg .= "<span class=\"mono\">chown -R ownername.groupname ".basename($_SESSION['docroot_path'])."</span>\n";

                  $errors[] = $errmsg;
               }
            }

         } else {
            # Remote build info no longer available! - should only happen if we update in the middle of their upgrade
            echo lang("Unable to pull info for")." '".$_POST['build_type']."' ".lang("update build").".<br>";
            $errors[] = lang("The build you selected is no longer available, perhaps because a new build was just posted. Please trying checking for updates again.");
         }


         /*---------------------------------------------------------------------------------------------------------*
          ___       _                   _
         | __|__ __| |_  _ _  __ _  __ | |_
         | _| \ \ /|  _|| '_|/ _` |/ _||  _|
         |___|/_\_\ \__||_|  \__,_|\__| \__|
         /*---------------------------------------------------------------------------------------------------------*/
         # Proceed with install  if no download errors
         if ( count($errors) < 1 ) {

            # Preserve working directory (will switch back to it after build file extract)
            $orig_workingdir = getcwd();

            # Switch to docroot (comment out to extrac to temp location under webmaster/)
            chdir("../../../");

            # Try to fix permissions before extract (non-suexec only because suexec servers don't usually have the problems this is meant to fix)
            if ( !php_suexec() ) {
               testWrite("sohoadmin", true);
            }

            # Get last modified date of test file (so we can verify that it changes after extracting new build)
            $ow_testfile = "sohoadmin/index.php";
            $ow_test_before = filemtime($ow_testfile);

            # Extract build!
            //if ( !extract_tgz($downloaded_buildfile) ) {
            ##Ultra change to extract zip file
            if ( !unZip($downloaded_buildfile) ) {
            

               if ( !shell_exec_allowed() && $updateprefs->get("ignore_shellexec") != "yes" ) {
                  # shell_exec() disabled
                  $errmsg = "<p>".lang("Unable to extract downloaded build file. The extract command failed.")." \n";
                  $errmsg .= lang("Check to make sure that php's shell_exec() function is enabled on your server.")."</p>";
                  $errmsg .= "<p>".lang("If you have no idea what \"php's shell_exec() function\" means, you may want to contact your web host and ask them about this.")."\n";
                  $errmsg .= lang("They're probably the people who'll need to do the actual fixing anyway")." (".lang("because it usually involves changing a server config file").").</p>\n";
                  $errors[] = $errmsg;
               }
            }

            # Delete local build file
            if ( file_exists($downloaded_buildfile) && !unlink($downloaded_buildfile)) {
               //echo "Unable to delete downloaded build file after extraction. Check permissions on document root folder.<br/>".$php_errormsg; exit;
               $errors[] = lang("Unable to delete downloaded build file after extraction. Check permissions on document root folder.");
            }

            # Write build data to local file
            if ( count($errors) < 1 ) {
               $bdata_file = $_SESSION['docroot_path']."/sohoadmin/filebin/build.conf.php";

               if ( !$bdata_stream = fopen($bdata_file, "w+") ) {
                  echo lang("Unable to open build data file")." (".$bdata_file.") ".lang("for writing")."!"; exit;

               } else {
                  if ( !fwrite($bdata_stream, serialize($new_build)) ) {
                     echo lang("Unable to write new build info file. Please check permissions on sohoadmin/filebin."); exit;
                  }
                  fclose($bdata_file);
               }
            }

            # Switch back to original working directory
            chdir($orig_workingdir);

            # Apply any db changes, etc in new version_updates script
            include_once($_SESSION['docroot_path']."/sohoadmin/includes/create_system_tables.inc.php");
            include_once($_SESSION['docroot_path']."/sohoadmin/includes/create_system_folders.inc.php");
            //include($_SESSION['docroot_path']."/sohoadmin/includes/normalize_db_tables.inc.php");
            
            include_once($_SESSION['docroot_path']."/sohoadmin/includes/version_compat_updates.inc.php");
            $report = array(); // Do not show report info here, keep that just for when running this via Help Center
            $_SESSION['refresh_css'] = true; // Reload the main menu once after update to make sure they're not running the old css file out of cache

		  $curr_docroot = getcwd();
		  chdir($_SESSION['docroot_path'].'/sohoadmin');

            # Copy all pgm- files from master location to docroot location
            
            include_once($_SESSION['docroot_path']."/sohoadmin/includes/copy_runtime_files.inc.php");
            chdir($curr_docroot);
		
            @unlink($_SESSION['docroot_path']."/sohoadmin/config/justupdated.txt");

            # Reprocess plugin overwrites and replacements on freshly-updated source files
            include_once("plugin_manager/plugin_functions.php");
            $plugin_errors_ar = reprocess_filemods();
            
            foreach($plugin_errors_ar as $pval){
            	$plugin_errors[] = $pval;
            }
            
            # Set permissions on sohoadmin to something aggreeable?
            if ( $updateprefs->get("chmod_after") == "yes" && !php_suexec() ) {
               exec("chmod -R 0777 ".$_SESSION['docroot_path']."/sohoadmin");
            } else {
               exec("chmod -R 0755 ".$_SESSION['docroot_path']."/sohoadmin");
            }

            # Copy new pgm- files to docroot locations


         } // End if no remote download errors

         # Show success/error message in 'Software Updates' popup
         $popup_content = "install_report";

         //echo "<div style=\"width: 500px; height: 200px; overflow: scroll;\">".$extract_output."</div>\n";
         //echo "<br><b>Done with update installation!</b>";

      } // End if no errors/problems with update build choosen for install

} // End if installing update


# Show 'Software Updates' popup if returning from one of the above processes
# Also hide drop-down for "Check at login.." so it doesn't poke through the popup in IE
if ( isset($_REQUEST['todo']) ) {
   $popup_display = "display: block;";
   $dd_display = "display: none;";
} else {
   $popup_display = "display: none;";
   $dd_display = "display: block;";
}
?>


<script language="javascript">
//parent.header.flip_header_nav('WEBMASTER_MENU_LAYER');

// Keep screen alive during development (comment out before public wrap)
//window.setTimeout('window.location.reload();', 1500000);

// Make sure radio is not disabled before checking it
function already_installed(radio_id) {
   var radChk = document.getElementById('stable_radio').checked;
   var radDis = document.getElementById('stable_radio').disabled;

   if ( radDis == true ) {
      // Confirm re-install of currently-installed version
      var confirm_msg = "Are you sure that you want to re-install the same version that is already installed on your website?\n\n";
      confirm_msg += "Re-installing may help repair software files that have been corrupted or lost, but it's rarely necessary.";
      //var uSure = window.confirm(confirm_msg);

      // Alternative to annoying confirm box -- create unique install report for re-install (will have to anyway)
      var uSure = true;

   } else {
      // Newer or different than currently-installed version
      var uSure = true;
   }

   // Check the radio if selected build is OK'd as either newer or confirmed reinstall
   if ( uSure == true ) { document.getElementById('stable_radio').checked = true; }

}


// Display proper build_description when choosing an update build
function update_description() {
   if ( document.getElementById('latest_radio') && document.getElementById('latest_radio').checked == true ) { // latest
      title = document.getElementById('latest[build_name]').value;
      text = document.getElementById('latest[changelog]').value;
      document.getElementById('update_description_title').innerHTML = title;
      document.getElementById('update_description_text').innerHTML = text;

   } else if ( document.getElementById('stable_radio').checked == true ) { // Stable
      title = document.getElementById('stable[build_name]').value;
      text = document.getElementById('stable[changelog]').value;
      document.getElementById('update_description_title').innerHTML = title;
      document.getElementById('update_description_text').innerHTML = text;

   } else if ( document.getElementById('soho_radio') && document.getElementById('soho_radio').checked == true ) { // Soholaunch internal version
      title = document.getElementById('soho[build_name]').value;
      text = document.getElementById('soho[changelog]').value;
      document.getElementById('update_description_title').innerHTML = title;
      document.getElementById('update_description_text').innerHTML = text;
   }

}

// Hide popup box and show "Installing..." progress msg
function installing_now() {
   //document.getElementById('choose_update_content').style.display='none';
   document.getElementById('popup_updates').style.display='none';
   document.getElementById('installing_now_msg').style.display='block';
}
</script>

<style>
/* Main Software Updates popup layer */
div#popup_updates {
   width: 650px;
   padding: 0px;
   position: absolute;
   left: 7%;
   top: 2%;
   z-index: 15;
}

div#popup_updates h1 {
   font-size: 14px;
   margin-bottom: 0;
}

/* Container for available update selection */
div#choose_update_content {

}

/* Form block with radio options for each available update build */
div#available_builds {
   border: 1px dashed #ccc;
   width: 100%;
   background-color: #336699;
}

/* Outer box that contains inner span that contains description text */
div#changelog_box {
   margin-top: 5px;
   vertical-align: top;
   height: 100px;
   border: 1px dashed #ccc;
   overflow: auto;
   padding: 3px;
}

#update_description_text {
   width: 100%;
}

#update_description_title {
   font-weight: normal;
}
</style>


<div id="image_preloads" style="display: none;">
 <img src="../includes/display_elements/graphics/check_updates_btn-on.gif">
 <img src="../includes/display_elements/graphics/install_btn-on.gif">
 <img src="../includes/display_elements/graphics/cancel_btn-on.gif">
 <img src="../includes/display_elements/graphics/restart_btn-on.gif">
</div>


<!---  "Installation in progress..." message --->
<div class="bg_green_df content middle center" id="installing_now_msg" style="border:1px solid #2e2e2e; padding:25px; display:none; width:300px; position:absolute; left:25%; top:20%; z-index:15;">
<b style="font-size: 125%;">Installing Update Now...</b> <br><br>
Please wait.<br>
</div>


<?
/*---------------------------------------------------------------------------------------------------------*
 _   _           _        _          ___
| | | | _ __  __| | __ _ | |_  ___  | _ \ ___  _ __  _  _  _ __
| |_| || '_ \/ _` |/ _` ||  _|/ -_) |  _// _ \| '_ \| || || '_ \
 \___/ | .__/\__,_|\__,_| \__|\___| |_|  \___/| .__/ \_,_|| .__/
       |_|                                    |_|         |_|
/*---------------------------------------------------------------------------------------------------------*/
?>
<!--- ======================================================================================================== --->
<!--- ================================        POPUP: AVAILABLE UPDATES    ==================================== --->
<!--- ======================================================================================================== --->
<div id="popup_updates" style="<? echo $popup_display; ?>;">
 <table class="feature_sub" cellspacing="0" cellpadding="8">
  <tr>
   <td class="fsub_title gray_gel"><img border="0" src="../includes/display_elements/graphics/software_update_icon.gif"></td>
   <td class="fsub_title gray_gel" width="100%">Software Updates</td>
  </tr>
  <tr>
   <td colspan="2" class="bg_blue_30 white">

<?php
# Total updates available
$updates_avail = $stable_avail + $latest_avail;

# Show available updates and allow user to pick which (if any) to install
if ( $popup_content == "available_updates" ) {
   /*---------------------------------------------------------------------------------------------------------*
      _              _  _        _     _         _   _           _        _
     /_\ __ __ __ _ (_)| | __ _ | |__ | | ___   | | | | _ __  __| | __ _ | |_  ___  ___
    / _ \\ V // _` || || |/ _` || '_ \| |/ -_)  | |_| || '_ \/ _` |/ _` ||  _|/ -_)(_-<
   /_/ \_\\_/ \__,_||_||_|\__,_||_.__/|_|\___|   \___/ | .__/\__,_|\__,_| \__|\___|/__/
                                                       |_|
   /*---------------------------------------------------------------------------------------------------------*/

	# Stick all this in a div that we can flip off via js and replace with "installing..." message
	echo "   <div id=\"choose_update_content\" style=\"display: block;\">\n";

	# List any availble updates as radio options
	if(strlen($_SESSION['updates_expiration']) > 2){
		
		echo "    <h1>".lang("License Expired")."</h1>\n";
		echo "<p class=\"white\" style=\"margin-top: 0;\"><b>".lang("Note").":</b> Access to product updates for this site's license expired on ".date('F d, Y' , $_SESSION['updates_expiration']).". To update this site to the latest version you can purchase a license renewel which will allow this site update to all new versions for one year.  Click Here to go to the license renewel purchase page.</p>\n";
	} else {
   
		if ( $stable_avail > 0 || $latest_avail > 0 || $soho_avail ) {
		
		   # Instructional text: Please select update...
		   echo "    <h1>".lang("Select an update to install")."</h1>\n";
		   echo "    <p class=\"white\" style=\"margin-top: 0;\"><b>".lang("Note").":</b> ".lang("'Latest' builds may contain more features and fixes not yet available in the 'Stable' build,")."\n";
		   echo "    ".lang("but the 'Stable' build has weathered the test of time for stability.")."\n";
		
		   echo "    <b>".lang("Also Note").":</b> ".lang("Sometimes the stable build may be more than one number away from the latest build (i.e. stable = r47, latest = r49).")."\n";
		   echo "    ".lang("This can happen when a more significant/stable 'latest' build is wrapped to replace a recently-released, trivial/problematic 'latest' build.")."</p>\n";
		
		   # FORM: Select update build
		   echo "    <form name=\"choose_update\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">\n";
		   echo "    <input type=\"hidden\" name=\"todo\" value=\"install_update\">\n";
		
		   echo "       <div id=\"available_builds\">\n";
		   echo "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"text\">\n";
		
		   # Stable build available?
		   if ( $stable_avail > 0 ) {
		      $check_onclick = "onClick=\"already_installed('stable_radio');update_description();\"";
		      echo "         <tr class=\"bg_blue_df hand\" onMouseover=\"this.className='bg_green_df hand';\" onMouseout=\"this.className='bg_blue_df hand';\" ".$check_onclick.">\n";
		      echo "          <td valign=\"top\" width=\"6%\" align=\"left\">\n";
		      echo "           <input type=\"hidden\" name=\"stable[build_name]\" id=\"stable[build_name]\" value=\"".stripslashes($stable_update['build_name'])."\">\n";
		      echo "           <input type=\"hidden\" name=\"stable[changelog]\" id=\"stable[changelog]\" value=\"".stripslashes(nl2br(base64_decode($stable_update['changelog'])))."\">\n";
		      echo "           <input type=\"radio\" name=\"build_type\" id=\"stable_radio\" value=\"stable\" onClick=\"update_description();\"></td>\n";
		
		//         # Do not display radio if build disabled (because it's already installed)
		//         if ( !isset($stable_disabled) ) {
		//            echo "           <input type=\"radio\" name=\"build_type\" id=\"stable_radio\" value=\"stable\" onClick=\"update_description();\"></td>\n";
		//         } else {
		//            echo "&nbsp;\n";
		//         }
		
		      echo "          <td valign=\"top\" class=\"gray_33\" style=\"padding-top: 8px;\">\n";
		
		      # Mark build as 'already installed'?
		      if ( !isset($stable_disabled) ) {
		         echo "           <span class=\"red\">".$stable_update['build_name']."</span> (stable)\n";
		      } else {
		         echo "           <span class=\"gray\">".$stable_update['build_name']."</span> (stable) - <span class=\"red\"><i>".lang("installed")."</i></span>\n";
		      }
		
		      echo "          </td>\n";
		      echo "         </tr>\n";
		   }
		
		   # latest build available?
		   if ( $latest_avail > 0 && $same_build != "yes") {
		      $check_onclick = "onClick=\"document.getElementById('latest_radio').checked=true;update_description();\"";
		      echo "         <tr class=\"bg_blue_df hand\" onMouseover=\"this.className='bg_green_df hand';\" onMouseout=\"this.className='bg_blue_df hand';\" ".$check_onclick.">\n";
		      echo "          <td valign=\"top\" width=\"6%\" align=\"left\">\n";
		      echo "           <input type=\"hidden\" name=\"latest[build_name]\" id=\"latest[build_name]\" value=\"".stripslashes($latest_update['build_name'])."\">\n";
		      echo "           <input type=\"hidden\" name=\"latest[changelog]\" id=\"latest[changelog]\" value=\"".stripslashes(nl2br(base64_decode($latest_update['changelog'])))."\">\n";
		      echo "           <input type=\"radio\" name=\"build_type\" id=\"latest_radio\" value=\"latest\" onClick=\"update_description();\">\n";
		      echo "          </td>\n";
		      echo "          <td valign=\"top\" width=\"94%\" class=\"gray_33\" style=\"padding-top: 8px;\">\n";
		
		      # Mark build as 'already installed'?
		      if ( !isset($latest_disabled) ) {
		         echo "           <span class=\"red\">".$latest_update['build_name']."</span> (latest)\n";
		      } else {
		         echo "           <span class=\"gray italic\">".$latest_update['build_name']."</span> (latest) - <span class=\"red\">installed</span>\n";
		      }
		
		      echo "          </td>\n";
		      echo "         </tr>\n";
		   }
		
		   # Soho build available?
		   if ( $soho_avail > 0 ) {
		      $check_onclick = "onClick=\"document.getElementById('soho_radio').checked=true;update_description();\"";
		      echo "         <tr class=\"bg_blue_df hand\" onMouseover=\"this.className='bg_green_df hand';\" onMouseout=\"this.className='bg_blue_df hand';\" ".$check_onclick.">\n";
		      echo "          <td valign=\"top\" width=\"6%\" align=\"left\">\n";
		      echo "           <input type=\"hidden\" name=\"soho[build_name]\" id=\"soho[build_name]\" value=\"".stripslashes($soho_update['build_name'])."\">\n";
		      echo "           <input type=\"hidden\" name=\"soho[changelog]\" id=\"soho[changelog]\" value=\"".stripslashes(nl2br(base64_decode($soho_update['changelog'])))."\">\n";
		      echo "           <input type=\"radio\" name=\"build_type\" id=\"soho_radio\" value=\"soho\" onclick=\"update_description();\">\n";
		      echo "          </td>\n";
		      echo "          <td valign=\"top\" width=\"94%\" class=\"gray_33\" style=\"padding-top: 8px;\">\n";
		
		      # Mark build as 'already installed'?
		      if ( !isset($soho_disabled) ) {
		         echo "           <span class=\"red\">".$soho_update['build_name']."</span> (unstable test)\n";
		      } else {
		         echo "           <span class=\"gray italic\">".$soho_update['build_name']."</span> (latest) - <span class=\"red\">installed</span>\n";
		      }
		
		      echo "          </td>\n";
		      echo "         </tr>\n";
		   }
		
		   echo "        </table>\n";
		   echo "       </div>\n";
		
		
		   # Build description text (display based on build selected)
		   echo "      <h1>Change Log - <span id=\"update_description_title\"></span></h1>\n";
		   echo "      <div id=\"changelog_box\" class=\"bg_blue_f8\">\n";
		   echo "       <span class=\"gray_31\" id=\"update_description_text\"><div style=\"text-align: center;\">[ ".lang("Choose an update to see description").". ]</div></span>\n";
		   echo "      </div>\n";
		
		   echo "    </form>\n";
		
		   # Show description for default-checked update
		   echo "    <script language=\"javascript\">update_description();</script>\n";
		
		
		} else {
		   # Show 'Now updates available' message
		   echo "    <p style=\"text-align: center; font: 11px verdana;\">\n";
		   echo "     <b>".lang("Your current version appears to be up-to-date").".</b><br><br>\n";
		   echo "     ".lang("No updates are available at this time").".\n";
		   echo "    </p>\n";
		
		} // End if any updates available or not

	}
	
	echo "    </div>\n"; // End choose_update_content layer


} elseif ( $popup_content == "install_report" ) {
   /*---------------------------------------------------------------------------------------------------------*
    ___            _          _  _   ___                       _
   |_ _| _ _   ___| |_  __ _ | || | | _ \ ___  _ __  ___  _ _ | |_
    | | | ' \ (_-<|  _|/ _` || || | |   // -_)| '_ \/ _ \| '_||  _|
   |___||_||_|/__/ \__|\__,_||_||_| |_|_\\___|| .__/\___/|_|   \__|
                                              |_|
   /*---------------------------------------------------------------------------------------------------------*/

   # List errors or show success message?
   if ( count($errors) > 0 ) {
      echo "<b>".lang("Could not complete update process due to the following errors").":</b><br><br>\n";
      echo "<div class=\"bg_blue_f8 red\" style=\"font-size: 110%; padding: 10px; border: 1px dashed #ccc;height: 250px;overflow: auto;\">\n";

      foreach ( $errors as $key=>$msg ) {
         echo $msg."<br>";
      }
      echo "</div>\n";

   } else {
   		if(count($plugin_errors) > 0){
   			 echo "<b>".lang("The following Plugins could returned errors after updating").":</b><br><br>\n";
	      echo "<div class=\"bg_blue_f8 red\" style=\"font-size: 110%; padding: 10px; border: 1px dashed #ccc;overflow: auto;\">\n";
	
	      foreach ( $plugin_errors as $key=>$msg ) {
	         echo $msg."<br><br/>";
	      }
	      echo "</div><br/>\n";
   		}
   	
      echo "<b>".lang("New version installed sucessfully")."!</b><br>\n";
      //echo lang("Please log-out and log-in again to complete the update process").".\n";
   }

} elseif ( $popup_content == "problems" ) {
   /*---------------------------------------------------------------------------------------------------------*
    ___             ___            _     _
   | __| _ _ __ __ | _ \ _ _  ___ | |__ | | ___  _ __   ___
   | _| | ' \\ V / |  _/| '_|/ _ \| '_ \| |/ -_)| '  \ (_-<
   |___||_||_|\_/  |_|  |_|  \___/|_.__/|_|\___||_|_|_|/__/

   /*---------------------------------------------------------------------------------------------------------*/

   # User-friendly message
   echo "<div class=\"bg_gray_f8 black\" style=\"font-size: 110%; border: 1px dashed #ccc;\">\n";
   echo " <p style=\"padding: 10px;\"><b class=\"red\">".lang("The Software Update feature is either disabled or cannot function properly due to certain server settings").".</b><br><br>\n";
   echo " ".lang("Please contact")." ".please_contact()." ".lang("for more information").".</p>\n";

   # Hidden error details
   echo "<div id=\"conflict_list\" class=\"black\" style=\"font-size: 110%; display: none;\">\n";
   echo " <ol class=\"mono\" style=\"margin-left: 0;\">\n";

   # List evironment conflicts detected (reasons why auto-updates won't work)
   for ( $e = 0; $e < count($env_conflicts); $e++ ) {
      echo "  <li>".$env_conflicts[$e]."<br><br></li>";
   }

   echo " </ol>\n";
   echo "</div>\n";

   # [ Show Error Details... ]
   echo "<div class=\"black\" style=\"text-align: right; padding: 5px;\">\n";
   echo " [ <span class=\"red hand\" onClick=\"document.getElementById('conflict_list').style.display='block';\">".lang("Details")." &gt;&gt;</span> ]\n";
   echo "</div>\n";

   echo "</div>\n";


}// End if popup should show available_updates, report, or problems


?>

   </td>
  </tr>
  <tr>
   <td colspan="2" class="text bg_blue_30" align="right">
    <table border="0" cellpadding="0" cellspacing="0" width="225">
     <tr>
<?php
# INSTALL | CANCEL
if ( $popup_content == "available_updates" && $updates_avail > 0 ) {
   echo "      <!--- Install --->\n";
   echo "      <td align=\"right\">\n";
   echo "       <!--- <input type=\"button\" value=\"Install Now\" onClick=\"document.choose_update.submit();\"> --->\n";
#disable updates for Now


if(!preg_match('/tra\.soholaunch\.com\//', $_SESSION['docroot_path'])){
	echo "       <span onclick=\"installing_now(); window.setTimeout('document.choose_update.submit()', 1000);\" class=\"dialog_button\" id=\"install_btn_off\" onMouseover=\"this.id='install_btn_on'\" onMouseout=\"this.id='install_btn_off'\">\n";
} else {
	echo "       <span  class=\"dialog_button\" id=\"install_btn_off\" onMouseover=\"this.id='install_btn_on'\" onMouseout=\"this.id='install_btn_off'\">\n";
}
   
   echo "        <span class=\"dialog_button_text\">".lang("Install Now")."</span>\n";
   echo "       </span>\n";
   echo "      </td>\n";
   echo "      <!--- Cancel --->\n";
   echo "      <td align=\"right\">\n";
   echo "       <!--- <input type=\"button\" value=\"Cancel\" onClick=\"document.getElementById('popup_updates').style.display='none';\"> --->\n";
   echo "       <span onclick=\"document.getElementById('popup_updates').style.display='none';\" class=\"dialog_button\" id=\"cancel_btn_off\" onMouseover=\"this.id='cancel_btn_on'\" onMouseout=\"this.id='cancel_btn_off'\">\n";
   echo "        <span class=\"dialog_button_text\">".lang("Cancel")."</span>\n";
   echo "       </span>\n";
   echo "      </td>\n";

# LOGOUT NOW | LATER
} elseif ( $popup_content == "install_report" ) {
   echo "      <!--- Logout Now --->\n";
   echo "      <td align=\"right\">&nbsp;\n";
//   echo "       <!--- <input type=\"button\" value=\"Restart Now\" onClick=\"document.choose_update.submit();\"> --->\n";
//   echo "       <span onclick=\"parent.close();\" class=\"dialog_button\" id=\"restart_btn_off\" onMouseover=\"this.id='restart_btn_on'\" onMouseout=\"this.id='restart_btn_off'\">\n";
//   echo "        <span class=\"dialog_button_text\">".lang("Logout Now")."</span>\n";
//   echo "       </span>\n";
   echo "      </td>\n";
   echo "      <!--- Later --->\n";
   echo "      <td align=\"right\">\n";   
//   echo "       <span onclick=\"document.getElementById('popup_updates').style.display='none';\" class=\"dialog_button\" id=\"cancel_btn_off\" onMouseover=\"this.id='cancel_btn_on'\" onMouseout=\"this.id='cancel_btn_off'\">\n";
   echo "       <span onclick=\"window.top.location.href='http://".$_SESSION['this_ip']."/sohoadmin/version.php';\" class=\"dialog_button\" id=\"restart_btn_off\" onMouseover=\"this.id='restart_btn_on'\" onMouseout=\"this.id='restart_btn_off'\">\n";
;
   echo "        <span class=\"dialog_button_text\">".lang("OK")."</span>\n";
   echo "       </span>\n";
   echo "      </td>\n";

# OK
} else {
   # Show 'OK' if no updates available
   echo "      <!--- OK --->\n";
   echo "      <td align=\"center\">\n";
//   echo "       <!--- <input type=\"button\" value=\"Cancel\" onClick=\"document.getElementById('popup_updates').style.display='none';\"> --->\n";
   //document.getElementById('check_at_login').style.display='block'; // Show "Check at login..." dropdown again --- uncomment and stick in onclick when this feature actually does something.
   echo "       <span onclick=\"document.getElementById('popup_updates').style.display='none';\" class=\"dialog_button\" id=\"cancel_btn_off\" onMouseover=\"this.id='cancel_btn_on'\" onMouseout=\"this.id='cancel_btn_off'\">\n";
   echo "        <span class=\"dialog_button_text\">".lang("OK")."</span>\n";
   echo "       </span>\n";
   echo "      </td>\n";

} // End pick 'OK' buttons to show based on $popup_content
?>


     </tr>
    </table>
   </td>
  </tr>
 </table>
</div>


<?php
/*---------------------------------------------------------------------------------------------------------*
 __  __        _         ___   _            _                _
|  \/  | __ _ (_) _ _   |   \ (_) ___ _ __ | | __ _  _  _   | |    __ _  _  _  ___  _ _
| |\/| |/ _` || || ' \  | |) || |(_-<| '_ \| |/ _` || || |  | |__ / _` || || |/ -_)| '_|
|_|  |_|\__,_||_||_||_| |___/ |_|/__/| .__/|_|\__,_| \_, |  |____|\__,_| \_, |\___||_|
                                     |_|             |__/                |__/
/*---------------------------------------------------------------------------------------------------------*/


# Webmaster button row
include_once("webmaster_nav_buttons.inc.php");
echo $THIS_DISPLAY;

# popup-diagnostic_info
$popup = "";
$popup .= "<p class=\"nomar_btm\">".lang("This information is meant to help tech support diagnose any problems you might be having with Software Updates.")."</p>\n";
$popup .= "<div style=\"border: 1px dashed #ccc;padding: 5px;\" class=\"mono nomar_top\">\n";
$popup .= " allow_url_fopen = ".url_fopen_allowed(true)."<br/>\n";
$popup .= " shell_exec = ".shell_exec_allowed(true)."\n<br/>";
$popup .= " php_suexec = ".php_suexec(true)."\n";
$popup .= "</div>\n";

$popup .= "<form name=\"chmodafterform\" method=\"get\" action=\"".$_SERVER['PHP_SELF']."\">\n";
$popup .= "<h2>".lang("chmod to 777 after updating?")."</h2>\n";
$popup .= "<p class=\"nomar_top nomar_btm\">".lang("For plugin developers on non-phpsuexec servers who constantly have to go in and re-chmod to 777 after running Software Updates")."\n";
$popup .= lang("so they can modify source files via FTP again").".</p>\n";
$popup .= "<select id=\"chmod_after\" name=\"chmod_after\" onchange=\"document.chmodafterform.submit();\">\n";
$popup .= " <option value=\"no\">".lang("No")."</option>\n";
$popup .= " <option value=\"yes\">".lang("Yes")."</option>\n";
$popup .= "</select>\n";
$popup .= "</form>\n";

$popup .= "<form name=\"ignore_shellexecform\" method=\"get\" action=\"".$_SERVER['PHP_SELF']."\">\n";
$popup .= "<h2>".lang("Suppress 'shell_exec() disabled' error message?")."</h2>\n";
$popup .= "<p class=\"nomar_top nomar_btm\">".lang("Allows update routine to proceed past the extract step even though the extract command didn't return any output.")."</p>\n";
$popup .= "<select id=\"ignore_shellexec\" name=\"ignore_shellexec\" onchange=\"document.ignore_shellexecform.submit();\">\n";
$popup .= " <option value=\"no\">".lang("No")."</option>\n";
$popup .= " <option value=\"yes\">".lang("Yes")."</option>\n";
$popup .= "</select>\n";
$popup .= "</form>\n";

$popup .= "<form name=\"testing_buildsform\" method=\"get\" action=\"".$_SERVER['PHP_SELF']."\">\n";
$popup .= "<h2>".lang("Allow installation of un-released internal testing builds?")."</h2>\n";
$popup .= "<p class=\"nomar_top nomar_btm\">".lang("A build may be wrapped for testing purposes several times before being deemed ready for release.")." \n";
$popup .= lang("If you enable this option you will be able to see and install these \"internal testing\" builds. <span class=\"red\"><b>Warning:</b> These builds may be completely unstable. INSTALL AT YOUR OWN RISK.")."</span></p>\n";
$popup .= "<select id=\"testing_builds\" name=\"testing_builds\" onchange=\"document.testing_buildsform.submit();\">\n";
$popup .= " <option value=\"no\">".lang("No")."</option>\n";
$popup .= " <option value=\"yes\">".lang("Yes")."</option>\n";
$popup .= "</select>\n";
$popup .= "</form>\n";

$popup .= "<script type=\"text/javascript\">\n";
$popup .= "\$('chmod_after').value = '".$updateprefs->get("chmod_after")."';\n";
$popup .= "\$('ignore_shellexec').value = '".$updateprefs->get("ignore_shellexec")."';\n";
$popup .= "\$('testing_builds').value = '".$updateprefs->get("testing_builds")."';\n";
$popup .= "</script>\n";
echo help_popup("popup-diagnostic_info", "Technical diagnostic info", $popup, "top: 5%;left: 5%;width: 650px;");

# popup-advanced_options
$popup = "";
echo help_popup("popup-advanced_options", "Advanced Settings", $popup, "top: 10%;left: 10%;");

?>
    <table width="100%" border="0" cellpadding="3" cellspacing="0" class="text" style="margin-top: 10px;">
     <tr>
      <td align="left">
       <ul style="list-style-type: square;line-height: 1.5em;">
        <li><span class="help_link" onclick="toggleid('popup-diagnostic_info');">[?] Technical diagnostic info (for Geeks)</span></li>
       </ul>
      </td>

      <td align="right">
       <span onclick="document.check_now.submit();" class="button_image" id="check_updates_btn_off" onMouseover="this.id='check_updates_btn_on'" onMouseout="this.id='check_updates_btn_off'">
        <span class="button_image_text"><? echo lang("Check&nbsp;for&nbsp;Updates&nbsp;Now"); ?></span>
        <span style="display:none;" id="check_updates_btn_on">&nbsp;</span>
       </span>
       <span class="nopad_left">
        <form name="check_now" method="post" action="<? echo $_SERVER['PHP_SELF']; ?>">
         <input type="hidden" name="todo" value="checknow">
        </form>
       </span>
      </td>
     </tr>

    </table>
   </td>
  </tr>
  <tr>
   <td valign="top" class="module_body_area">
    <table width="100%" border="0" cellpadding="10" cellspacing="0" style="border: 1px dashed #980000;" class="bg_white">
     <tr>
      <td><b>Installed Version: </b><br><? echo $installed_build['build_name'];; ?></td>
     </tr>
     <tr>
      <td><b>Release Date: </b><br><? echo date("F jS, Y", $installed_build['build_date']); ?></td>
     </tr>
     <tr>
      <td><b>Change Log: </b><br>
       <div id="installed_changelog" style="height: 150px;overflow: auto;">
        <? echo nl2br(stripslashes(base64_decode($installed_build['changelog']))); ?>
       </div>
      </td>
     </tr>
    </table></td>
  </tr>
 </table>

<?


# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("We are always thinking of ways to improve the product.  You can check for and download updates as we issue them.");

# Build into standard module template
$module = new smt_module($module_html);
$module->add_breadcrumb_link(lang("Webmaster"), "program/webmaster/webmaster.php");
$module->add_breadcrumb_link(lang("Software Updates"), "program/webmaster/software_updates.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/webmaster-enabled.gif";
$module->heading_text = lang("Software Updates");
$module->description_text = $instructions;
$module->good_to_go();
?>