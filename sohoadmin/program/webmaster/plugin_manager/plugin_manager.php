<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


# Plugin Manager
error_reporting(E_PARSE);
session_start();
include_once("../../includes/product_gui.php");
error_reporting(E_PARSE);

# Plugin install/misc functions (hook_attach, hook_special, etc)
include_once("plugin_functions.php");

# Create plugin tables if the do not exist
include_once("dbtable_check-plugins.php");


# Kill stuff for testing
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*
$plugin_folder = "VIASTEPPHOTOGALLERY";
//rmdirr("../../../plugins/".$plugin_folder);
$qry = "delete from system_plugins where PLUGIN_FOLDER = '".$plugin_folder."'";
//if ( !mysql_query($qry) ) { echo mysql_error(); exit; }
$qry = "delete from system_hook_attachments where PLUGIN_FOLDER = '".$plugin_folder."'";
//if ( !mysql_query($qry) ) { echo mysql_error(); exit; }
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


/*---------------------------------------------------------------------------------------------------------*
 ___                                   _
| __| _ _  _ _  ___  _ _   __  ___  __| | ___  ___
| _| | '_|| '_|/ _ \| '_| / _|/ _ \/ _` |/ -_)(_-<
|___||_|  |_|  \___/|_|   \__|\___/\__,_|\___|/__/

# Define error codes/messages (like when an error code is passed from install_plugin.php via GET in header() redirect)
/*---------------------------------------------------------------------------------------------------------*/
$errormsg['nopluginfolder'] = "No value found for 'plugin_folder'. It may be missing from the install manifest for this plugin.<br>";
$errormsg['notlicensed'] = "Cannot install this plugin because it does not appear to be licensed for this domain (".$_SESSION['this_ip'].") or server (".php_uname(n).").";

# notlicensed-update
$errormsg['notlicensed-update'] = "Cannot update to the new version of this plugin because \n";
$errormsg['notlicensed-update'] .= "it does not appear to be licensed for this domain (".$_SESSION['this_ip'].") or server (".php_uname(n).").</p>";
$errormsg['notlicensed-update'] .= "<h2>Why this usually happens</h2>\n";
$errormsg['notlicensed-update'] .= "<p class=\"nomar_top\">This error can often occur when a plugin was offered for free when you originally downloaded it,\n";
$errormsg['notlicensed-update'] .= "but has since become a premium plugin (as in, you have to pay something for it).</p>\n";

$errormsg['notlicensed-update'] .= "<h2>Old version will still work</h2>\n";
$errormsg['notlicensed-update'] .= "<p class=\"nomar_top\">The old version of this plugin that you've already installed will continue to function, \n";
$errormsg['notlicensed-update'] .= "but you will not be able to update to new versions until you purchase this plugin.\n";

# Do not refer them to addons.soholaunch.com if in branded mode
if ( $_SESSION['hostco']['get_more_plugins_link'] != "off" ) {
   $errormsg['notlicensed-update'] .= "<h2>Buy a license and get new versions</h2>\n";
   $errormsg['notlicensed-update'] .= " Click the button below to go to the Soholaunch Add-ons website and purchase a domain license for this plugin.\n";
   $errormsg['notlicensed-update'] .= " Your domain license will be created instantly when you complete the checkout process, \n";
   $errormsg['notlicensed-update'] .= " so you can come right back here after checkout and click the Update button again \n";
   $errormsg['notlicensed-update'] .= " and it'll actually update to the new version \n";
   $errormsg['notlicensed-update'] .= " (and every version after that).\n";
   $errormsg['notlicensed-update'] .= " <p><b>Note:</b> During checkout, enter <span class=\"red\">".$_SESSION['docroot_url']."</span> as the domain you want to license.\n";

   # Buy Plugin button
   $errormsg['notlicensed-update'] .= " <div style=\"text-align: center;border: 0px solid red;position: relative;height: 30px;\" align=\"right\">\n";
   $errormsg['notlicensed-update'] .= "  <span onclick=\"window.open('https://addons.soholaunch.com/View_Addon.php?addonid=#addonid#');\" class=\"dialog_button\" id=\"buy_btn_off\" onmouseover=\"this.id='buy_btn_on'\" onmouseout=\"this.id='buy_btn_off'\" style=\"position: absolute;right: 40%;padding-top: 0;padding-right: 0px;width: 100px;\">\n";
   $errormsg['notlicensed-update'] .= "   <span class=\"dialog_button_text\" style=\"border: 0px solid red;padding-top: 5px;padding-right: 0;padding-left: 30px;\">".lang("Buy Plugin")."</span>\n";
   $errormsg['notlicensed-update'] .= "  </span>\n";
//   $errormsg['notlicensed-update'] .= "  <div class=\"buy_button\" onClick=\"window.open('https://addons.soholaunch.com/index.php?pr=Plugins&cat=sp:premium');\" onmouseover=\"this.className='buy_button-hover';\" onmouseout=\"this.className='buy_button';\">Buy Plugin</div>\n";
   $errormsg['notlicensed-update'] .= " </div>\n";
}

$errormsg['blankuploadfield'] = "Plug .zip file upload field was blank. Please select a plugin .zip file to upload.";
$errormsg['cannotupdate-url_fopen'] = "Unable to download new plugin build file! Make sure that allow_url_fopen is enabled in your php.ini (may have to get your web host to do this).";

$errormsg['cannotupdate-nowrite'] = "Unable to write downloaded plugin plugin file to sohoadmin/plugins folder.\n";
$errormsg['cannotupdate-nowrite'] .= "Make sure that your \"plugins\" folder (".dirname($_SESSION['docroot_path'])."/sohoadmin/plugins) is writeable.<br/>";
$errormsg['cannotupdate-nowrite'] .= "You can do this by logging-in to your site via FTP, navigating to ".dirname($_SESSION['docroot_path']."sohoadmin/plugins").",\n";
$errormsg['cannotupdate-nowrite'] = " right-clicking on the \"plugins\" folder,\n";
$errormsg['cannotupdate-nowrite'] = "and making sure permissions are set to <span style=\"color: red;\">";
if ( php_suexec() ) { $errormsg['cannotupdate-nowrite'] = "755"; } else { $errormsg['cannotupdate-nowrite'] = "777"; }
$errormsg['cannotupdate-nowrite'] = "</span>\n";

# Start buffering output
ob_start();
/*---------------------------------------------------------------------------------------------------------*
  __  __         __       __         _  __
 / / / /___  ___/ /___ _ / /_ ___   / |/ /___  _    __
/ /_/ // _ \/ _  // _ `// __// -_) /    // _ \| |/|/ /
\____// .__/\_,_/ \_,_/ \__/ \__/ /_/|_/ \___/|__,__/
     /_/
# Update plugin now
/*---------------------------------------------------------------------------------------------------------*/
if ( $_GET['todo'] == "update_plugin" && $_GET['plugin_folder'] != "" ) {
   # Get build file info
   $addons_api = addons_api($_GET['plugin_folder']);
   $plugins_dir_path = $_SESSION['docroot_path']."/sohoadmin/plugins/";
   $zipfile_name = $addons_api['zipfile_name'];
   $download_url = $addons_api['zipfile_url']."&update_domain=".$_SESSION['this_ip'];
   $downloaded_buildfile = $plugins_dir_path . "temp/" . $zipfile_name;

   # Make sure temporary folder exists to stick zip in
   if ( !is_dir($plugins_dir_path."temp") ) {
      if ( !php_suexec() ) { testWrite("sohoadmin/plugins", true); }
   	mkdir($plugins_dir_path."temp", 0755);
   }

   # Make sure plugin is still licensed before downloading .zip
   if ( !addon_licensed($_GET['plugin_folder'], 'update') ) {
      $errorcode = "notlicensed-update";
      $errormsg['notlicensed-update'] = str_replace("#addonid#", $addons_api['addonid'], $errormsg['notlicensed-update']); // So buy button can link directly to the specific plugin's detail page
   }

   # Download update file now!
   if ( $errorcode == "" ) {
      $dlUpdate = new file_download($download_url, $downloaded_buildfile);

      if ( !file_exists($downloaded_buildfile) ) {
         if ( ini_get('allow_url_fopen') != "1" ) {
            $errorcode = "cannotupdate-url_fopen";

         } else {
            $errorcode = "cannotupdate-nowrite";
         }
      }
   }

   # Redirect to finish install process if no errors
   if ( $errorcode == "" ) {
      echo "<h1>Update downloaded. About to install...</h1>";
      echo "<script type=\"text/javascript\">document.location.href='install_plugin.php?downloaded_zipfile=".$zipfile_name."';</script>\n";
      //echo "</div>\n";
      exit;
   }
}

# Uninstall plugin
if ( $_GET['todo'] == "uninstall" && $_GET['plugin_folder'] != "" ) {
   include("uninstall_plugin.php");
}

?>

<link rel="stylesheet" type="text/css" href="plugin_manager.css">


<script type="text/javascript">
// Get confirmation
function uninstall_plugin(plugin_folder, plugin_title) {
   //alert('soemthing');
   usure = window.confirm("If you unistall this plugin ("+plugin_title+"), the functionality it offers will no longer be available. Are you sure you want to uninstall "+plugin_title+"?\n");

   //Confirm drop plugin table on uninstall
   if ( usure == true ) {
      killmsg = "REMOVE PLUGIN SETTINGS AND CONTENT?\n\n\n";
      killmsg += "[OK] - The plugin will be uninstalled and any data tables created by the plugin (which typically contain plugin-specific content, user config settings, etc) ";
      killmsg += "that are directly related to this plugin will be deleted (permanently).\n\n";
      killmsg += "[CANCEL] - The plugin will be uninstalled but data tables created by the plugin will be left alone ";
      killmsg += "so that any settings, etc they contain can be restored if you choose to install the plugin again in the future.\n";
      killdata = window.confirm(""+killmsg+"");

      if ( killdata == true ) {
         document.location.href="plugin_manager.php?todo=uninstall&plugin_folder="+plugin_folder+"&droptables=yes";
      } else {
         document.location.href="plugin_manager.php?todo=uninstall&plugin_folder="+plugin_folder+"&droptables=no";
      }
   }

} // End uninstall confirmation
</script>

<?php
/*---------------------------------------------------------------------------------------------------------*
 ___
| _ \ ___  _ __  _  _  _ __  ___
|  _// _ \| '_ \| || || '_ \(_-<
|_|  \___/| .__/ \_,_|| .__//__/
          |_|         |_|
# Error message display, Plugin Updated message
/*---------------------------------------------------------------------------------------------------------*/
# Error display
if ( $errorcode != "" ) {
   $popup = "";
   $popup .= $errormsg[$errorcode];
   echo help_popup("popup-popup_underlay", "Problem", $popup, "display: block;top: 11%;left: 9%;width: 650px;");
}

if ( $_GET['todo'] == "update_check" || $_GET['todo'] == "plugin_updated" ) {
   $addons_api = addons_api($_GET['plugin_folder']);
   $changelog = base64_decode($addons_api['changelog']);
   $popup = "";
   $popup .= "<h2 class=\"nomar_btm\">Version ".$addons_api['plugin_version']."</h2>\n";
   $popup .= "<p class=\"nomar_top\"><b>Released:</b> ".date("F jS, Y - g:ia", $addons_api['release_date'])."</p>\n";
   $popup .= "<h2>What's new in this version</h2>";

   # View complete version history?
   if ( $_SESSION['hostco']['company_name'] == "Soholaunch" && $_SESSION['hostco']['get_more_plugins_link'] != "off" ) {
      $popup .= ' (<span class="blue uline hand" onclick="popup_window(\''.$addons_api['version_history_url'].'\', \'Soholaunch Addons\');">View complete version history</span>)'."\n";
   }

   $popup .= "<div class=\"changelog\">\n";
   $popup .= " ".nl2br($changelog)."\n";
   $popup .= "</div>\n";

   # Install Button and Popup title -- just checking, or just updated?
   if ( $_GET['todo'] == "plugin_updated" ) {
      $popup_title = "<span class=\"green_33\">New version of ".$addons_api['plugin_title']." was installed successfully!</span>";

   } elseif ( $_GET['todo'] == "update_check" ) {
      $popup_title = "New version of ".$addons_api['plugin_title']." available";
      $popup .= "<div style=\"text-align: right;border: 0px solid red;position: relative;height: 30px;\" align=\"right\">\n";
      $popup .= " <span onclick=\"document.location.href='plugin_manager.php?todo=update_plugin&plugin_folder=".$_GET['plugin_folder']."';\" class=\"dialog_button\" id=\"install_btn_off\" onmouseover=\"this.id='install_btn_on'\" onmouseout=\"this.id='install_btn_off'\" style=\"position: absolute;right: 0;padding-top: 0;\">\n";
      $popup .= "  <span class=\"dialog_button_text\" style=\"border: 0px solid red;padding-top: 5px;\">".lang("Install Now")."</span>\n";
      $popup .= " </span>\n";
      $popup .= "</div>\n";
   }
   echo help_popup("popup-plugin_updated", $popup_title, $popup, "display: block;top: 15%;left: 15%;");
}
?>

<!---Installing Plugin...-->
<div id="installing_plugin" style="display:none;position:absolute; top:0; left:0; border:0px solid red; width:100%; height:100%; z-index:2;">
 <div style="position: absolute;top: 28%;left: 18%;width: 400px;text-align: center; border: 1px solid #888c8e; background-color: #efefef; padding: 20px;">
  <h1>Installing Plugin...</h1>
  <p>Please Wait</p>
 </div>
</div>


<?
/*---------------------------------------------------------------------------------------------------------*
                      ___             _
 _  _  ___ ___  _ _  / _ \  _ __  ___| |    __ _  _  _  ___  _ _
| || |(_-</ -_)| '_|| (_) || '_ \(_-<| |__ / _` || || |/ -_)| '_|
 \_,_|/__/\___||_|   \___/ | .__//__/|____|\__,_| \_, |\___||_|
                           |_|                    |__/
/*---------------------------------------------------------------------------------------------------------*/
?>

<!---Preload rollover images in hidden div--->
<div style="display: none;">
 <img src="images/options_button-hover.gif" width="1" height="1">
 <img src="images/uninstall_button-hover.gif" width="1" height="1">
 <img src="images/install_plugin-hover.gif" width="1" height="1">
 <img src="images/update_button-hover.gif" width="1" height="1">
 <img src="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/display_elements/graphics/buy_btn-on.gif" width="1" height="1">
 <img src="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/display_elements/graphics/install_btn-on.gif" width="1" height="1">
</div>

<?
//# Show 'Get more plugins' link?
//if ( plugins_allowed() && $_SESSION['hostco']['get_more_plugins_link'] != "off" ) {
//   echo "<a href=\"http://".$_SESSION['hostco']['get_more_plugins_url']."\" target=\"_blank\" class=\"white normal unbold font90\" style=\"font-size: 90%; letter-spacing: normal;\">".lang("Get more plugins")."</a>\n";
//} else {
//   echo "&nbsp;\n";
//}
?>

  <!------------------------------START: Install new plugin------------------------------>
   <form enctype="multipart/form-data" action="install_plugin.php" method="POST" name="plugin_upload_form">
   <input type="hidden" name="todo" value="install">
   <input type="hidden" name="MAX_FILE_SIZE" value="9999999">
	 <table cellspacing="0" cellpadding="8" border="0" class="text">
     <tr>
      <td width="30" align="right"><img src="../../../skins/<? echo $_SESSION['skin']; ?>/icons/zip_icon-20px.gif" valign="middle"></td>
      <td align="right" valign="middle" class="text"><? echo lang("Plugin .zip file"); ?>:</td>

<?php
if ( $_SESSION['demo_site'] == "yes" ) {
   $onlick = "alert('Plugin installation is disabled in demo site mode.');";
   $browse_disable = "disabled=\"disabled\"";

} else {
   $onlick = "showid('installing_plugin');document.plugin_upload_form.submit();";
   $browse_disable = "";
}
?>

      <td align=left valign="middle" class="text">
       <input type="file" name="FILE1" size="45" class="catselect" style="width: 300px;" <? echo $browse_disable; ?>>
      </td>

      <!---Install plugin button--->
      <td valign="middle" style="padding-left: 15px;">
       <div class="install_button" onclick="<? echo $onlick; ?>" onMouseOver="this.className='install_button-hover';" onMouseOut="this.className='install_button';">Install a New Plugin</div>
      </td>
     </tr>
	 </table>

<?php
if ( $_SESSION['demo_site'] == "yes" ) {
?>
	 <div class="bg_yellow center" style="padding: 10px;">
	  <p class="red bold">Note: Plugin installation is disabled in demo site mode.</p>
	  <p>Once you get a copy of <? echo $_SESSION['hostco']['company_name']." ".$_SESSION['hostco']['sitebuilder_name']; ?> installed on your website,
	  you can install all the plugins you'd like. :)</p>
	 </div>
<?
} // End if demo_site == "yes"
?>
	</form>
  <!------------------------------END: Install new plugin------------------------------>

<?
# List installed plugins
$result = mysql_query("select * from system_plugins ORDER BY TITLE ASC");

# List installed plugins or show 'none found' message (which should ultimately point them to addons.)
if ( mysql_num_rows($result) > 0 ) {
$plugins_ar=array();
   while ( $getPlug = mysql_fetch_array($result) ) {
	$plugins_ar=$getPlug['AUTHOR'];

      echo "   <div id=\"".$getPlug['PLUGIN_FOLDER']."\" class=\"plugin_block\" onMouseOver=\"this.className='plugin_block-hover'\" onMouseOut=\"this.className='plugin_block'\">\n";;

      # ICON - Show default puzzle piece if no custom plugin icon
      $iconfile = "../../../plugins/".$getPlug['PLUGIN_FOLDER']."/".$getPlug['ICON'];
      if ( !file_exists($iconfile) || trim($getPlug['ICON']) == "" ) {
         $plugin_icon = "../../../skins/".$_SESSION['skin']."/icons/plugins-enabled.gif";
      } else {
         $plugin_icon = "../../../plugins/".$getPlug['PLUGIN_FOLDER']."/".$getPlug['ICON'];
      }

      # AUTHOR NAME?
      # Do not show this if in branded mode
      if ( $_SESSION['hostco']['plugin_author_name'] != "off" ) {
         $author_name_display = " by ".$getPlug['AUTHOR'];
      } else {
         $author_name_display = "";
      }

      # TITLE & DESCRIPTION -- Show red text and "will be installed when you restart" message if plugin was just installed
      if ( in_array($getPlug['PLUGIN_FOLDER'], $_SESSION['new_plugins']) ) {
         # Just installed message
         echo "    <div class=\"plugin_description red\" style=\"background-image: url('".$plugin_icon."');\">\n";
         echo "     <b>".plugin_strip_strings($getPlug['TITLE'])."</b> ".$getPlug['VERSION']."".$author_name_display."<br>\n";
         echo "     This plugin was just installed/updated. You may want to restart (log-out then log back in again) to ensure that it works properly.";

      } else {
         # Plugin name, description
         echo "    <div class=\"plugin_description\" style=\"background-image: url('".$plugin_icon."');\">\n";
         echo "     <b>".plugin_strip_strings($getPlug['TITLE'])."</b> ".$getPlug['VERSION']."".$author_name_display."<br/>\n";
         echo  "     ".eregi_replace("soholaunch", "", base64_decode($getPlug['DESCRIPTION']));
      }
      echo "    </div>\n";

      # Options button
      if ( $getPlug['OPTIONS_LINK'] != "" ) {
         echo "    <div class=\"options_button\" onClick=\"document.location.href='../../../plugins/".$getPlug['PLUGIN_FOLDER']."/".$getPlug['OPTIONS_LINK']."';\" onMouseOver=\"this.className='options_button-hover';\" onMouseOut=\"this.className='options_button';\">Options</div>\n";
      }

      # Update available?
      if ( plugin_update_avail($getPlug['PLUGIN_FOLDER'], $getPlug['VERSION']) ) {
         echo "    <div class=\"update_button\" onclick=\"document.location.href='".$_SERVER['PHP_SELF']."?todo=update_check&plugin_folder=".$getPlug['PLUGIN_FOLDER']."';\" onmouseover=\"this.className='update_button-hover';\" onmouseout=\"this.className='update_button';\">Update</div>\n";
      }
      # Uninstall button
      echo "    <div class=\"uninstall_button\" onclick=\"uninstall_plugin('".$getPlug['PLUGIN_FOLDER']."', '".$getPlug['TITLE']."');\" onMouseOver=\"this.className='uninstall_button-hover';\" onMouseOut=\"this.className='uninstall_button';\">Uninstall</div>\n";
      # CHECKBOX: Drop plugin tables?
      //echo "    <div class=\"droptable_option\">Drop plugin db tables?<input type=\"checkbox\" id=\"".$getPlug['PLUGIN_FOLDER']."_droptable_option\" value=\"yes\"></div>\n";
      echo "   </div>\n";
   }

} else { // No plugins found
   echo "<div class=\"center red bold\">".lang("No plugins installed")."</div>";
}

//See if plugins are uploaded but not installed, ask them to install
$plugin_folder_names=array();
if ($handle = opendir("../../../plugins/")) {
    while (false !== ($dir = readdir($handle))) {
	$manifest_file = "../../../plugins/".$dir."/install_manifest.php";
      	if (file_exists($manifest_file)) {
			$plugin_folder_names[]=$dir;
		} 
    }
    closedir($handle);
}

if(count($plugin_folder_names) > mysql_num_rows($result) ) {

	echo '<h1><div style="display:inline; width:55px;"><img border="0" src="../../../skins/default/icons/full_size/plugins-enabled.gif"></div><div style="vertical-align:top; display:inline;">Manually Install Plugins</div></h1>';
	echo '';
		echo "<div style=\"position:relative;  \" >\n";
		foreach($plugin_folder_names as $plugin) {
			//check if plugin is installed
			$plugin_installed_query="select * from system_plugins WHERE PLUGIN_FOLDER=\"$plugin\"";
			$plugin_installed=mysql_query($plugin_installed_query);
			if(mysql_num_rows($plugin_installed) == 0 ) {			
			echo '<div style=" padding:10px; font-weight:bold;">'.ucwords(str_replace('_', " ", $plugin))."&nbsp;&nbsp;&nbsp;&nbsp;";			
			echo '<div onClick="document.location=\'install_plugin.php?uninstalled_plugin_name='.$_SESSION['docroot_path'].'/sohoadmin/plugins/'.$plugin.'\'"'." class=\"nav_save\" onmouseover=\"this.style.backgroundImage='url(../../includes/display_elements/graphics/btn-nav_save-on.jpg)';\" onmouseout=\"this.style.backgroundImage='url(../../includes/display_elements/graphics/btn-nav_save-off.jpg)';\" style=\"float:left; position:relative; width:100px; background-image: url(../../includes/display_elements/graphics/btn-nav_save-off.jpg);  height: 16px; padding: 0px 15px 0px 15px; border: 1px solid #000; border-style: none solid solid solid; margin: -2px 15px 0px 5px;\" ><div style=\"width:100px; text-align:center;padding-top: 2px; margin: 0;\" >Install Plugin</div></div>\n";
			
			echo "</div>";
						
		}
	}
	echo '</div>';
}

# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Plugins allow you to add new features and custom modifications.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Plugin Manager";
$module->add_breadcrumb_link("Plugin Manager", "program/webmaster/plugin_manager/plugin_manager.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/plugins-enabled.gif";
$module->heading_text = "Plugin Manager";
$module->description_text = $instructions;
$module->good_to_go();

?>