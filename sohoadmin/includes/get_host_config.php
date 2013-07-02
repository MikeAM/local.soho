<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


/*---------------------------------------------------------------------------------------------------------*
 _    _              _      _____                __  _
| |  | |            | |    / ____|              / _|(_)
| |__| |  ___   ___ | |_  | |      ___   _ __  | |_  _   __ _
|  __  | / _ \ / __|| __| | |     / _ \ | '_ \ |  _|| | / _` |
| |  | || (_) |\__ \| |_  | |____| (_) || | | || |  | || (_| |
|_|  |_| \___/ |___/ \__|  \_____|\___/ |_| |_||_|  |_| \__, |
                                                         __/ |
                                                        |___/

# Pull down host's global config (branding) options and write them to a local file
/*---------------------------------------------------------------------------------------------------------*/

error_reporting(E_PARSE);

# Grab remote output into var

$branding_data = include_r("http://securexfer.net/product_reports/host_config.php?dom=".$_SESSION['this_ip']);


# Write branding options to file if avail
if ( $branding_data != "" ) {
   $configfile = "config/hostco.conf.php";

   # Kill existing file
   if ( file_exists($configfile) ) { @unlink($configfile); }

   # Re-create with latest data
   if ( !$hostconf = fopen($configfile, 'w+') ) {
      //echo "ERROR: Unable to create config file!<br>";
      //echo "Check permissions on <i>sohoadmin/config</i> folder.<br><br>";
      //echo "Current dir: (".getcwd().")";
      //exit;

   } else {
      if ( !fwrite($hostconf, $branding_data) ) {
         echo "Unable to write global config data to file (".$configfile.")!<br>";
         echo "Check permissions on <i>sohoadmin/config</i> folder.";
         exit;
      } else {
         //echo "Data written to <b>".$hostconf."</b> successfully!"; exit;
         fclose($hostconf);
      }
   }

} // End if host branding data retrieved




?>