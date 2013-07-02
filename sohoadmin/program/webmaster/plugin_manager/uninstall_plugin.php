<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

error_reporting(E_PARSE);

# Uninstallation process report
$errors = array();

# Read install manifest
include("../../../plugins/".$_GET['plugin_folder']."/install_manifest.php");

# Kill associated database tables?
if ( $_GET['droptables'] == "yes" ) {
   foreach ( $drop_tables as $key=>$table ) {
      if ( $table != "" && table_exists($table) ) {
         if ( !mysql_query("drop table $table") ) { $errors[] = "Unable to drop table '".$table."': ".mysql_error(); }
      }
   }

   # Any fields to drop?
   if ( is_array($drop_fieldsArr) ) {
      foreach ( $drop_fieldsArr as $tableStr=>$fieldArr ) {
         if ( $tableStr != "" && table_exists($tableStr) ) {
            $countInt = count($fieldArr);
            for ( $x = 0; $x < $countInt; $x++ ) {
               if ( !mysql_query("alter table $tableStr drop column ".$fieldArr[$x]) ) { $errors[] = "Unable to drop field '".$fieldArr[$x]."' from table '".$tableStr."': ".mysql_error(); }
            }
         }
      }
   }

   # Kill any associated userdata
   $plugin = new userdata($_GET['plugin_folder']);
   $plugin->delete();

} // End if droptables = yes


# Restore original source files overwritten by this plugin's hook_overwrite()'s
$odir = getcwd();
chdir($_SESSION['docroot_path']);
$qry = "select * from system_hook_attachments where PLUGIN_FOLDER = '".$_GET['plugin_folder']."' and HOOK_TYPE = 'overwrite'";
$rez = mysql_query($qry);
while ( $gethack = mysql_fetch_array($rez) ) {
   $orig_backup = $gethack['HOOK_FILE'].".".$_GET['plugin_folder'];
   $hacked_file = $gethack['HOOK_FILE'];

   # Only proceed if backup of original source file found
   if ( file_exists($orig_backup) ) {

      # Kill hacked source file
      unlink($hacked_file);

      # Replace with backup of original source file
      rename($orig_backup, $hacked_file);
   }
}
chdir($odir);

# Restore original source code lines replaced by this plugin's hook_replace()'s
uninstall_replacements();

# Kill hook attachments
$qry = "delete from system_hook_attachments where PLUGIN_FOLDER = '".$_GET['plugin_folder']."'";
if ( !mysql_query($qry) ) { $errors[] = "Unable to remove plugin file associations (hooks).".mysql_error(); }

# Kill plugin record
$qry = "delete from system_plugins where PLUGIN_FOLDER = '".$_GET['plugin_folder']."'";
if ( !mysql_query($qry) ) { $errors[] = "Unable to remove plugin record.".mysql_error(); }

# Kill folder
rmdirr("../../../plugins/".$_GET['plugin_folder']);
if ( file_exists($_GET['plugin_folder']) ) {
   $errors[] = "Unable to delete plugin folder 'sohoadmin/plugins/".$_GET['plugin_folder']."'. Unless you notice immediate problems, it shouldn't do any harm to leave it there. Otherwise, consider checking permissions on this folder.'";
}

# Display success message/errors
if ( count($errors) > 0 ) {
   foreach ( $errors as $key=>$msg ) {
      echo "ERROR: ".$msg."<br>";
   }
   exit;
} else { // Success message alert
   echo js_alert("Uninstallation complete! The plugin was removed (uninstalled) successfully.");
}
//# Return to plugin manager
//header ("Location: plugin_manager.php");
//exit;

?>