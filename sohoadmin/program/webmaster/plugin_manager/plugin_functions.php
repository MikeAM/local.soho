<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


# Clear hook entries while testing hook_attach function
//mysql_query("delete from system_hook_attachments where EXTENSION_ID = 'VIASTEPPHOTOGALLERY'");

/*---------------------------------------------------------------------------------------------------------*
 ___                             _   ___                 _        _
| _ \ ___  __ _   __ _  _ _   __| | / __| _ __  ___  __ (_) __ _ | |
|   // -_)/ _` | / _` || ' \ / _` | \__ \| '_ \/ -_)/ _|| |/ _` || |
|_|_\\___|\__, | \__,_||_||_|\__,_| |___/| .__/\___|\__||_|\__,_||_|
          |___/                          |_|

# Functions related to normal (include) hook attachments and special hook attachments
/*---------------------------------------------------------------------------------------------------------*/

# HOOK ATTACHMENT
# >> ACCEPTS:
#    $mod_file - Filename of custom include
#    $hook_id - Name of hook to where custom file should be included (hook id name hard-coded in source file)
#    $hook_file - Name of source file containing hook (don't think this is really needed...no biggie to make hook ids unique)
function hook_attach($mod_file, $hook_id, $hook_file = "") {
   global $plugin_folder;
   global $hook_attachments;

   $data['PRIKEY'] = "NULL";
   $data['PLUGIN_FOLDER'] = $plugin_folder;
   $data['HOOK_TYPE'] = "include";
   $data['HOOK_ID'] = $hook_id;
   $data['HOOK_FILE'] = $hook_file;
   $data['MOD_FILE'] = $mod_file;
   $data['MOD_FILE'] = str_replace('\\', '/', $data['MOD_FILE']);
   $data['HOOK_DATA'] = "";
   $data['LAST_UPDATED'] = time();

   # Add to attachment array
   $hook_attachments[] = $data;
}

# Special hooks that Soholaunch accounts for in the product code
# ACCEPTS: hook id, predefined data array
function hook_special($hook_type, $hook_data) {
   global $plugin_folder;
   global $hook_attachments;

   $data['PRIKEY'] = "NULL";
   $data['PLUGIN_FOLDER'] = $plugin_folder;
   $data['HOOK_TYPE'] = $hook_type;
   $data['HOOK_ID'] = "";
   $data['HOOK_FILE'] = "";
   $data['MOD_FILE'] = "";
   $data['LAST_UPDATED'] = time();

   # Pathing fix on Win servers - H/T to Dimitry Chaplinsky
   foreach ($hook_data as $k=>$v) {
     $hook_data[$k] = addslashes(str_replace('\\', '/', $v));
   }

   $data['HOOK_DATA'] = serialize($hook_data);

   # Add to global attachment array
   $hook_attachments[] = $data;
}

# PROCESS HOOK ATTACHMENTS
#-----------------------------------------------------------------------
# Attach hooks now (insert records into db table)
# Depends on $hook_attachments array built by hook_attach()
function attach_hooks() {
   global $hook_attachments;
   global $hook_replacements;
   global $hook_overwrites;

   # Insert attachment records into db table now
   foreach ( $hook_attachments as $key=>$data ) {
      $qry = new mysql_insert("system_hook_attachments", $data);
      $qry->insert();
   }

   # Insert replacement records into db table now
   foreach ( $hook_replacements as $key=>$data ) {
      $qry = new mysql_insert("system_hook_attachments", $data);
      $qry->insert();
   }

   # Insert replacement records into db table now
   foreach ( $hook_overwrites as $key=>$data ) {
      $qry = new mysql_insert("system_hook_attachments", $data);
      $qry->insert();
   }
}


/*---------------------------------------------------------------------------------------------------------*
  ___                               _  _
 / _ \ __ __ ___  _ _ __ __ __ _ _ (_)| |_  ___
| (_) |\ V // -_)| '_|\ V  V /| '_|| ||  _|/ -_)
 \___/  \_/ \___||_|   \_/\_/ |_|  |_| \__|\___|

/*---------------------------------------------------------------------------------------------------------*/
# HOOK OVERWRITE
# ACCEPTS:
#  $hook_file - Name of source file to be overwritten, path relative to docroot (i.e. sohoadmin/program/main_menu.php)
#  $mod_file - Filename of custom include to overwrite source file with, path relative to plugin folder (i.e. main_menu-helloworld.php)
function hook_overwrite($hook_file, $mod_file) {
   global $plugin_folder;
   global $hook_overwrites;

   $data['PRIKEY'] = "NULL";
   $data['PLUGIN_FOLDER'] = $plugin_folder;
   $data['HOOK_TYPE'] = "overwrite";
   $data['HOOK_FILE'] = $hook_file;
   $data['MOD_FILE'] = $mod_file;
   $data['MOD_FILE'] = str_replace('\\', '/', $data['MOD_FILE']);
   $data['LAST_UPDATED'] = time();

   # Add to overwrite array
   $hook_overwrites[] = $data;
}

# PROCESS OVERWRITES
# -ACCEPTS (optional) >> MySql-ready data array of records to insert
# -Loops through $hook_overwrites array --- populated as global by hook_overwrite or passed to function from wherever
# -Renames source file as .orig and replaces it with mod file
function process_overwrites($qry_array = false) {

   # Use passed array or assume global is available (as it is when this is called from install_plugin.php)
   if ( is_array($qry_array) ) {
      $hook_overwrites = $qry_array;
      $reprocessing = true;
   } else {
      global $hook_overwrites;
      $reprocessing = false;
   }

   # Preserver original working dir
   $odir = getcwd();

   # Switch working dir to docroot
   chdir($_SESSION['docroot_path']);

   # Loop through overwrites
   foreach ( $hook_overwrites as $key=>$data ) {
      $orig_file = $data['HOOK_FILE'];
      $mod_file = "sohoadmin/plugins/".$data['PLUGIN_FOLDER']."/".$data['MOD_FILE'];

      # Make sure replacement file exists before proceeding
      if ( file_exists($mod_file) ) {

         # Rename source file as filename.plugin_name
         rename($orig_file, $orig_file.".".$data['PLUGIN_FOLDER']);

         # Copy/rename plugin file in place of source file
         copy($mod_file, $orig_file);

         # chmod -R 0755 folder
         shell_exec("chmod -R 0755 ".$orig_file);

//         # Insert overwrite record if installing (not if reprocessing file write after update)
//         if ( !$reprocessing ) {
//            $qry = new mysql_insert("system_hook_attachments", $data);
//            $qry->insert();
//         }
      }
   }

   # Switch back to orig working dir
   chdir($odir);
}


/*---------------------------------------------------------------------------------------------------------*
 ___             _
| _ \ ___  _ __ | | __ _  __  ___
|   // -_)| '_ \| |/ _` |/ _|/ -_)
|_|_\\___|| .__/|_|\__,_|\__|\___|
          |_|

/*---------------------------------------------------------------------------------------------------------*/
# HOOK REPLACE
# ACCEPTS:
#  $hook_file - Name of source file where , path relative to docroot (i.e. sohoadmin/program/main_menu.php)
#  $replace_manifest - Path to file within plugin folder containing source code replacements for this $hook_file
#
# Sister Function: install_replacements()
function hook_replace($hook_file, $replace_manifest) {
   global $plugin_folder;
   global $hook_replacements;

   $data['PRIKEY'] = "NULL";
   $data['PLUGIN_FOLDER'] = $plugin_folder;
   $data['HOOK_TYPE'] = "replace";
   $data['HOOK_FILE'] = $hook_file;
   $data['MOD_FILE'] = $replace_manifest;
   $data['MOD_FILE'] = str_replace('\\', '/', $data['MOD_FILE']);
   $data['LAST_UPDATED'] = time();

   # Add to overwrite array
   $hook_replacements[] = $data;
}

# INSTALL REPLACEMENTS
# -Loops through $hook_replacements array built by hook_replace()
# -Read contents of original source file
# -Read replacement manifest file
# -Replace specified code within original source code with plugin's version of that code
# -Write modified source code to source file
#
# NOTE: Uninstall routine can just reverse-process the replacment manifests with uninstall_replacements()
# but still have to insert records into hook_attachments table so replacements can be re-proccessed after software updates
function install_replacements($qry_array = false) {

   # Use passed array or assume global is available (as it is when this is called from install_plugin.php)
   if ( is_array($qry_array) ) {
      $hook_replacements = $qry_array;
      $reprocessing = true;
   } else {
      $reprocessing = false;
      global $hook_replacements;
   }

   # Preserve original working dir, switch to docroot
   $odir = getcwd();
   chdir($_SESSION['docroot_path']);

   # Loop through replacments
   foreach ( $hook_replacements as $key=>$data ) {
      $source_file = $data['HOOK_FILE'];
      $manifest_file = "sohoadmin/plugins/".$data['PLUGIN_FOLDER']."/".$data['MOD_FILE'];

      # Read contents of original source file
      $source_code = file_get_contents($source_file);

      # Read contents of replacement manifest
      $rep_manifest = file_get_contents($manifest_file);

      # Segment manifest by replacement groups
      $replacement = explode("#new replacement#", $rep_manifest);

      # Apply changes to source code defined in each groups
      for ( $r = 1; $r < count($replacement); $r++ ) {
         $origmatch = array();
         $modmatch = array();

         # Get original source to replace
         eregi("<oldcode>(.*)</oldcode>", $replacement[$r], $origmatch);
         $oldcode = trim($origmatch[1]);

         # Get replacement code
         eregi("<newcode>(.*)</newcode>", $replacement[$r], $modmatch);
         $newcode = trim($modmatch[1]);

         # Replace line breaks for win and lnx
         $oldcode = eregi_replace("[\n]", "::NEWN::", $oldcode);
         $oldcode = eregi_replace("[\r]", "::NEWR::", $oldcode);
         $newcode = eregi_replace("[\n]", "::NEWN::", $newcode);
         $newcode = eregi_replace("[\r]", "::NEWR::", $newcode);
         
         # Kill duplicate line breaks that do not show up in file but are there
         $oldcode = eregi_replace("::NEWR::::NEWN::", "::NEWN::", $oldcode);
         $oldcode = eregi_replace("::NEWN::::NEWR::", "::NEWN::", $oldcode);
         $newcode = eregi_replace("::NEWR::::NEWN::", "::NEWN::", $newcode);
         $newcode = eregi_replace("::NEWN::::NEWR::", "::NEWN::", $newcode);
         
         # Replace line breaks for win and lnx
         $source_code = eregi_replace("[\n]", "::NEWN::", $source_code);
         $source_code = eregi_replace("[\r]", "::NEWR::", $source_code);
         
         # Kill duplicate line breaks that do not show up in file but are there
         $source_code = eregi_replace("::NEWR::::NEWN::", "::NEWN::", $source_code);
         $source_code = eregi_replace("::NEWN::::NEWR::", "::NEWN::", $source_code);
         
         # Replace original code with mod code
         $source_code = str_replace($oldcode, $newcode, $source_code);
         
         # Replace temp line breaks
         $source_code = eregi_replace("::NEWN::", "\n", $source_code);
         $source_code = eregi_replace("::NEWR::", "\r", $source_code);
         
// Code from v4.9.2 r17
//         # Replace original code with mod code
//         $oldcode = eregi_replace("[\r\n]", "::NEWLINE::", $oldcode);
//         $oldcode = eregi_replace("::NEWLINE::::NEWLINE::", "::NEWLINE::", $oldcode);
//         $newcode = eregi_replace("[\r\n]", "::NEWLINE::", $newcode);
//         $newcode = eregi_replace("::NEWLINE::::NEWLINE::", "::NEWLINE::", $newcode);
//         $source_code = eregi_replace("[\r\n]", "::NEWLINE::", $source_code);
//
//         $source_code = str_replace($oldcode, $newcode, $source_code);
//         $source_code = eregi_replace("::NEWLINE::", "\n", $source_code);
      }

      # Open source file and write modified data
      if ( !$sourceFile = fopen($source_file, "w+") ) {
        // echo "Unable to open $source_file for writing! Check permissions on this file."; exit;
         $plugin_errors[] = "Failed to install ".$data['PLUGIN_FOLDER']." plugin. Unable to open $source_file for writing! Check permissions on this file.";
      } else {
         if ( !fwrite($sourceFile, $source_code) ) {
						$plugin_errors[] = "Failed to install ".$data['PLUGIN_FOLDER']." plugin. Unable to open $source_file for writing! Check permissions on this file.";
						//echo "Unable to write new source data to ".$source_file.". Check permissions on this file."; exit;
         }
         fclose($sourceFile);
      }

//      # Insert replacement record if installing (not if reprocessing file write after update)
//      if ( !$reprocessing ) {
//         $qry = new mysql_insert("system_hook_attachments", $data);
//         $qry->insert();
//      }

   } // End foreach $hook_replacements

   # Switch back to orig working dir
   chdir($odir);
	return $plugin_errors;
} // End install_replacements()


# UNINSTALL REPLACEMENTS
# Basically the reverse of install_replacements()
function uninstall_replacements() {
   global $plugin_folder;
   global $hook_replacements;

   # Preserver original working dir, switch to docroot
   $odir = getcwd();
   chdir($_SESSION['docroot_path']);

   # Loop through replacments
   foreach ( $hook_replacements as $key=>$data ) {
      $source_file = $data['HOOK_FILE'];
      $manifest_file = "sohoadmin/plugins/".$plugin_folder."/".$data['MOD_FILE'];

      # Read contents of original source file
      $source_code = file_get_contents($source_file);

      # Read contents of replacement manifest
      $rep_manifest = file_get_contents($manifest_file);

      # Segment manifest by replacement groups
      $replacement = explode("#new replacement#", $rep_manifest);

      # Apply changes to source code defined in each groups
      for ( $r = 1; $r < count($replacement); $r++ ) {
         $origmatch = array();
         $modmatch = array();

         # Get original source to replace
         eregi("<oldcode>(.*)</oldcode>", $replacement[$r], $origmatch);
         $oldcode = trim($origmatch[1]);

         # Get replacement code
         eregi("<newcode>(.*)</newcode>", $replacement[$r], $modmatch);
         $newcode = trim($modmatch[1]);

         # Replace line breaks for win and lnx
         $oldcode = eregi_replace("[\n]", "::NEWN::", $oldcode);
         $oldcode = eregi_replace("[\r]", "::NEWR::", $oldcode);
         $newcode = eregi_replace("[\n]", "::NEWN::", $newcode);
         $newcode = eregi_replace("[\r]", "::NEWR::", $newcode);
         
         # Kill duplicate line breaks that do not show up in file but are there
         $oldcode = eregi_replace("::NEWR::::NEWN::", "::NEWN::", $oldcode);
         $oldcode = eregi_replace("::NEWN::::NEWR::", "::NEWN::", $oldcode);
         $newcode = eregi_replace("::NEWR::::NEWN::", "::NEWN::", $newcode);
         $newcode = eregi_replace("::NEWN::::NEWR::", "::NEWN::", $newcode);
         
         # Replace line breaks for win and lnx
         $source_code = eregi_replace("[\n]", "::NEWN::", $source_code);
         $source_code = eregi_replace("[\r]", "::NEWR::", $source_code);
         
         # Kill duplicate line breaks that do not show up in file but are there
         $source_code = eregi_replace("::NEWR::::NEWN::", "::NEWN::", $source_code);
         $source_code = eregi_replace("::NEWN::::NEWR::", "::NEWN::", $source_code);
         
         # Replace original code with mod code
         $source_code = str_replace($newcode, $oldcode, $source_code);
         
         # Replace temp line breaks
         $source_code = eregi_replace("::NEWN::", "\n", $source_code);
         $source_code = eregi_replace("::NEWR::", "\r", $source_code);


// Code from v4.9.2 r17
//         # Replace original code with mod code
//         $oldcode = eregi_replace("[\r\n]", "::NEWLINE::", $oldcode);
//         $oldcode = eregi_replace("::NEWLINE::::NEWLINE::", "::NEWLINE::", $oldcode);
//         $newcode = eregi_replace("[\r\n]", "::NEWLINE::", $newcode);
//         $newcode = eregi_replace("::NEWLINE::::NEWLINE::", "::NEWLINE::", $newcode);
//         $source_code = eregi_replace("[\r\n]", "::NEWLINE::", $source_code);
//
//         $source_code = str_replace($newcode, $oldcode, $source_code);
//         $source_code = eregi_replace("::NEWLINE::", "\n", $source_code);
      }

      # Open source file and write modified data
      if ( !$sourceFile = fopen($source_file, "w+") ) {
         $plugin_errors[] = "Failed to install ".$plugin_folder." plugin. Unable to open $source_file for writing! Check permissions on this file.";
      } else {
         if ( !fwrite($sourceFile, $source_code) ) {
            $plugin_errors[] = "Failed to install ".$plugin_folder." plugin. Unable to open $source_file for writing! Check permissions on this file.";
         }
         fclose($sourceFile);
      }

   } // End foreach $hook_replacements

   # Switch back to orig working dir
   chdir($odir);
	return $plugin_errors;
} // End uninstall_replacements()


# Called after software update
# OUTLINE:
# -Select file overwrites by last updated date
# -Loop through and fetch arrays
# -Place data array in $hook_overwrite array
# -Pass array to process_overwrites
# -Select replacements from hook attachment table
# -
# NOTES:
# -Pull overwrites first then replacements second to minimize potential plugin conflicts
#
# If $plugin is passed, only file mods for that plugin will be (re)processed
# If $plugin is NOT passed, all file mods for all plugins will be (re)processed
function reprocess_filemods($plugin = "") {
   $hook_overwrites = array();
   $hook_replacements = array();

   # Do hook_overwrites first, by the order that they were installed/updated
   $qry = "SELECT * FROM system_hook_attachments WHERE HOOK_TYPE = 'overwrite'";
   if ( $plugin != "" ) { $qry .= " AND PLUGIN_FOLDER = '".$plugin."'"; }
   $qry .= " ORDER BY LAST_UPDATED ASC";
   $rez = mysql_query($qry);
   while ( $data = mysql_fetch_array($rez) ) {
      $hook_overwrites[] = $data;
   }
   process_overwrites($hook_overwrites);


   # Now do hook_replacements
   $qry = "SELECT * FROM system_hook_attachments WHERE HOOK_TYPE = 'replace'";
   if ( $plugin != "" ) { $qry .= " AND PLUGIN_FOLDER = '".$plugin."'"; }
   $qry .= " ORDER BY LAST_UPDATED ASC";
   $rez = mysql_query($qry);
   while ( $data = mysql_fetch_array($rez) ) {
      $hook_replacements[] = $data;
   }

  $plugin_errors_ar = install_replacements($hook_replacements);
	return $plugin_errors_ar;
}


# Ping addons.soholaunch.com and get current version data for passed plugin
# Accepts: addons_api(plugin folder name [, field index name to return])
# If second argument is not passed, it returns the whole api result array
function addons_api($plugin_folder, $justthisfield = "") {
   $output = file_get_contents("http://addons.soholaunch.com/api/getversion.api.php?plugin_folder=".$plugin_folder);
   $response = unserialize($output);
   if ( $justthisfield != "" ) {
      return $response[$justthisfield];
   } else {
      return $response;
   }
}

# Compare intalled plugin version against latest available on addons.soholaunch.com
function plugin_update_avail($plugin_folder, $installed_version) {
   if ( $installed_version != addons_api($plugin_folder, "plugin_version") && addons_api($plugin_folder, "plugin_version") != "" ) {
      return true;
   } else {
      return false;
   }
}

?>