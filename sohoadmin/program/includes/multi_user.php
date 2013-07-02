<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Pro Edition
## Version 4.6b
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
###############################################################################


//========================================================
## This script checks the user's login against
## permissions set through the Multi-User Access Feature,
## which is under the Webmaster menu.
##
## If client is not using this feature, then they'll
## be seen as 'WEBMASTER', and will have full access
//========================================================


function enc($v) {
	$v = md5($v);
	return $v;
}


$is_active = "onclick=\"load_program('#LOC#');\" class=\"black noline\"";
$no_access = "onclick=\"no_access();\" class=\"gray_df noline\"";
$non_active = "onclick=\"load_program('#LOC#');\" class=\"gray_df noline\"";

$filename = "../filebin/soholaunch.lic";
$file = fopen("$filename", "r");
	$data = fread($file,filesize($filename));
fclose($file);
$keydata = split("\n", $data);

# Register status of each feature in session so it can be checked elsewhere like in full_version() function.
$_SESSION['keyfile'] = array();


function noshow($pos,$enc,$txt) {
   global $keydata;
   $bye = "bye".$enc;

   if ( trim($keydata[$pos]) == md5($bye) ) {
      return true;
   } else {
      return false;
   }
}


// Create New Pages
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_CREATE_PAGES;", $CUR_USER_ACCESS)) {
   $mkPages['link'] = eregi_replace("#LOC#", "modules/create_pages.php", $is_active);
   $mkPages['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/create_pages-enabled.gif\" ".$mkPages['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $mkPages['link'] = $no_access;
   $mkPages['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/create_pages-disabled.gif\" ".$mkPages['link']." style=\"cursor: pointer;\"><br>\n";
}

// Edit Pages
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_EDIT_PAGES;", $CUR_USER_ACCESS)) {
   $editPages['link'] = eregi_replace("#LOC#", "modules/open_page.php", $is_active);
   $editPages['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/edit_pages-enabled.gif\" ".$editPages['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $editPages['link'] = $no_access;
   $editPages['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/edit_pages-disabled.gif\" ".$editPages['link']." style=\"cursor: pointer;\"><br>\n";
}

// Menu Display
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_MENUSYS;", $CUR_USER_ACCESS)) {
   $menusys['link'] = eregi_replace("#LOC#", "modules/auto_menu_system.php", $is_active);
   $menusys['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/menu_display-enabled.gif\" ".$menusys['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $menusys['link'] = $no_access;
   $menusys['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/menu_display-disabled.gif\" ".$menusys['link']." style=\"cursor: pointer;\"><br>\n";
}

// File Manager
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_FILES;", $CUR_USER_ACCESS)) {
   $fileMan['link'] = eregi_replace("#LOC#", "modules/site_files.php", $is_active);
   $fileMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/file_manager-enabled.gif\" ".$fileMan['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $fileMan['link'] = $no_access;
   $fileMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/file_manager-disabled.gif\" ".$fileMan['link']." style=\"cursor: pointer;\"><br>\n";
}

// Template Manager
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_TEMPLATES;", $CUR_USER_ACCESS)) {
   $tempMan['link'] = eregi_replace("#LOC#", "modules/site_templates.php", $is_active);
   $tempMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/template_manager-enabled.gif\" ".$tempMan['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $tempMan['link'] = $no_access;
   $tempMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/template_manager-disabled.gif\" ".$tempMan['link']." style=\"cursor: pointer;\"><br>\n";
}

// Forms Manager
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_FORMS;", $CUR_USER_ACCESS)) {
   $formMan['link'] = eregi_replace("#LOC#", "modules/forms_manager.php", $is_active);
   $formMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/forms_manager-enabled.gif\" ".$formMan['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $formMan['link'] = $no_access;
   $formMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/forms_manager-disabled.gif\" ".$formMan['link']." style=\"cursor: pointer;\"><br>\n";
}

// Site Statistics
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_STATS;", $CUR_USER_ACCESS)) {
   $stats['link'] = eregi_replace("#LOC#", "modules/mods_full/statistics.php", $is_active);
   $stats['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/site_statistics-enabled.gif\" ".$stats['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $stats['link'] = $no_access;
   $stats['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/site_statistics-disabled.gif\" ".$stats['link']." style=\"cursor: pointer;\"><br>\n";
}

// Photo Albums
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_PHOTO_ALBUM;", $CUR_USER_ACCESS)) {
   $album['link'] = eregi_replace("#LOC#", "modules/mods_full/photo_album/photo_album.php", $is_active);
   $album['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/photo_albums-enabled.gif\" ".$album['link']." style=\"cursor: pointer;\"><br>\n";

} else {
   $album['link'] = $no_access;
   $album['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/photo_albums-disabled.gif\" ".$album['link']." style=\"cursor: pointer;\"><br>\n";
}

eval(hook("mm-module_access_rule", basename(__FILE__)));

// Site Data Tables
if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_TABLES;", $CUR_USER_ACCESS)) {
   $dbTables['link'] = eregi_replace("#LOC#", "modules/mods_full/download_data.php", $is_active);
   $dbTables['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/site_data_tables-enabled.gif\" ".$dbTables['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $dbTables['link'] = $no_access;
   $dbTables['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/site_data_tables-disabled.gif\" ".$dbTables['link']." style=\"cursor: pointer;\"><br>\n";
}

// Blog Manager
// ---------------------------------------
$check_sum = enc("blog");
$check_what = enc("byeblog");
$blogtxt = lang("Blog Manager");

if (trim($keydata[2]) == $check_sum) {
   $_SESSION['keyfile']['blog'] = "enabled";

   if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_BLOG;", $CUR_USER_ACCESS)) {
	   $blog['link'] = eregi_replace("#LOC#", "modules/blog.php", $is_active);
	   $blog['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/blog_manager-enabled.gif\" ".$blog['link']." style=\"cursor: pointer;\"><br>\n";
	} else {
	   $blog['link'] = $no_access;
	   $blog['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/blog_manager-disabled.gif\" ".$blog['link']." style=\"cursor: pointer;\"><br>\n";
	}

} elseif ( trim($keydata[2]) == $check_what ){
   $_SESSION['keyfile']['blog'] = "deactivated";
   $blogtxt = "";
   $blog['icon'] = "";

} else {
   $_SESSION['keyfile']['blog'] = "disabled";
	$blog['link'] = eregi_replace("#LOC#", "../marketing/promotion.php?mod=blog", $non_active);
	$blog['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/blog_manager-disabled.gif\" ".$blog['link']." style=\"cursor: pointer;\"><br>\n";
}


// Shopping Cart
// ---------------------------------------
$check_sum = enc("cart");
$check_what = enc("byecart");
$carttxt = lang("Shopping Cart");

if (trim($keydata[3]) == $check_sum) {
   $_SESSION['keyfile']['cart'] = "enabled";

   if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SHOPPING_CART;", $CUR_USER_ACCESS)) { // Enabled
	   $cart['link'] = eregi_replace("#LOC#", "modules/mods_full/shopping_cart.php", $is_active);
	   $cart['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/shopping_cart-enabled.gif\" ".$cart['link']." style=\"cursor: pointer;\"><br>\n";

	} else { // Enabled but user not authorized to access
	   $cart['link'] = $no_access;
	   $cart['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/shopping_cart-disabled.gif\" ".$cart['link']." style=\"cursor: pointer;\"><br>\n";
	}

} elseif ( trim($keydata[3]) == $check_what ) { // Deactivated
   $_SESSION['keyfile']['cart'] = "deactivated";
	$carttxt = "";
	$cart['icon'] = "";

} else { // Disabled
   $_SESSION['keyfile']['cart'] = "disabled";
	$cart['link'] = eregi_replace("#LOC#", "../marketing/promotion.php?mod=cart", $non_active);
	$cart['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/shopping_cart-disabled.gif\" ".$cart['link']." style=\"cursor: pointer;\"><br>\n";
}


// Event Calendar
// ---------------------------------------
$check_sum = enc("calendar");
$check_what = enc("byecalendar");
$caltxt = lang("Event Calendar");

if ( trim($keydata[4]) == $check_sum ) {
   $_SESSION['keyfile']['calendar'] = "enabled";

   if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_CALENDAR;", $CUR_USER_ACCESS)) {
	   $calendar['link'] = eregi_replace("#LOC#", "modules/mods_full/event_calendar.php", $is_active);
	   $calendar['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/event_calendar-enabled.gif\" ".$calendar['link']." style=\"cursor: pointer;\"><br>\n";
	} else {
	   $calendar['link'] = $no_access;
	   $calendar['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/event_calendar-disabled.gif\" ".$calendar['link']." style=\"cursor: pointer;\"><br>\n";
	}

} elseif ( trim($keydata[4]) == $check_what ){
   $_SESSION['keyfile']['calendar'] = "deactivated";
	$caltxt = "";
	$calendar['icon'] = "";

} else {
   $_SESSION['keyfile']['calendar'] = "disabled";
	$calendar['link'] = eregi_replace("#LOC#", "../marketing/promotion.php?mod=calendar", $non_active);
	$calendar['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/event_calendar-disabled.gif\" ".$calendar['link']." style=\"cursor: pointer;\"><br>\n";
}


// Enewsletter
// ---------------------------------------
$check_sum = enc("enewsletter");
$check_what = enc("byeenewsletter");
$newstxt = $lang["eNewsletter"];

if (trim($keydata[5]) == $check_sum) {
   $_SESSION['keyfile']['news'] = "enabled";

   if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_NEWSLETTER;", $CUR_USER_ACCESS)) {
	   $enewsletter['link'] = eregi_replace("#LOC#", "modules/mods_full/enewsletter.php", $is_active);
	   $enewsletter['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/enewsletter-enabled.gif\" ".$enewsletter['link']." style=\"cursor: pointer;\"><br>\n";
	} else {
	   $enewsletter['link'] = $no_access;
	   $enewsletter['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/enewsletter-disabled.gif\" ".$enewsletter['link']." style=\"cursor: pointer;\"><br>\n";
	}

} elseif ( trim($keydata[5]) == $check_what ){
   $_SESSION['keyfile']['news'] = "deactivated";
   $newstxt = "";

} else {
   $_SESSION['keyfile']['news'] = "disabled";
   $enewsletter['link'] = eregi_replace("#LOC#", "../marketing/promotion.php?mod=enewsletter", $non_active);
	$enewsletter['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/enewsletter-disabled.gif\" ".$enewsletter['link']." style=\"cursor: pointer;\"><br>\n";
}


// Database Table Manager
// ---------------------------------------
$check_sum = enc("dbtable");
$check_what = enc("byedbtable");
$datatxt = lang("Database Tables");

if (trim($keydata[6]) == $check_sum) {
   $_SESSION['keyfile']['data'] = "enabled";
   if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_DB_MANAGER;", $CUR_USER_ACCESS)) {
	   $dbMan['link'] = eregi_replace("#LOC#", "modules/mods_full/download_data.php", $is_active);
	   $dbMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/site_data_tables-enabled.gif\" ".$dbMan['link']." style=\"cursor: pointer;\"><br>\n";
	} else {
	   $dbMan['link'] = $no_access;
	   $dbMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/site_data_tables-disabled.gif\" ".$dbMan['link']." style=\"cursor: pointer;\"><br>\n";
	}

} elseif ( trim($keydata[6]) == $check_what ){
   $_SESSION['keyfile']['data'] = "deactivated";
   $datatxt = "";
   $dbMan['icon'] = "";

} else {
   $_SESSION['keyfile']['data'] = "disabled";
	$dbMan['link'] = eregi_replace("#LOC#", "../marketing/promotion.php?mod=dbtables", $non_active);
	$dbMan['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/data_table_manager-disabled.gif\" ".$dbMan['link']." style=\"cursor: pointer;\"><br>\n";
}


// Secure Users & Backup Restore
// ---------------------------------------
$check_sum = enc("secure");
$check_what = enc("byesecure");
$sectxt = lang("Member Logins");
$baktxt = lang("Backup/Restore");

if (trim($keydata[7]) == $check_sum) {
   $_SESSION['keyfile']['secure'] = "enabled";
   
   # Secure Users
   if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SECURITY;", $CUR_USER_ACCESS)) {
	   $secure['link'] = eregi_replace("#LOC#", "modules/mods_full/security.php", $is_active);
   	$secure['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/secure_users-enabled.gif\" ".$secure['link']." style=\"cursor: pointer;\"><br>\n";
	} else {
	   $secure['link'] = $no_access;
   	$secure['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/secure_users-disabled.gif\" ".$secure['link']." style=\"cursor: pointer;\"><br>\n";
	}
	
	# Backup/Restore
	if ($CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_BACKUPRESTORE;", $CUR_USER_ACCESS)) {
	   $backup['link'] = eregi_replace("#LOC#", "webmaster/backup_restore.php", $is_active);
	   $backup['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/backup_restore-enabled.gif\" ".$backup['link']." style=\"cursor: pointer;\"><br>\n";
	}else{
	   $backup['link'] = $no_access;
   	$backup['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/backup_restore-disabled.gif\" ".$backup['link']." style=\"cursor: pointer;\"><br>\n";
	}
	   
	   

} elseif ( trim($keydata[7]) == $check_what ) { // Do not show if deactivated
   $_SESSION['keyfile']['secure'] = "deactivated";
   $sectxt = "";
   $baktxt = "";
   $secure['icon'] = "";
   $backup['icon'] = "";

} else {
   $_SESSION['keyfile']['secure'] = "disabled";
   $secure['link'] = eregi_replace("#LOC#", "../marketing/promotion.php?mod=secure", $non_active);
   $backup['link'] = eregi_replace("#LOC#", "../marketing/promotion.php?mod=backup", $non_active);
	$secure['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/secure_users-disabled.gif\" ".$secure['link']." style=\"cursor: pointer;\"><br>\n";
	$backup['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/backup_restore-disabled.gif\" ".$backup['link']." style=\"cursor: pointer;\"><br>\n";
}


/*-----------------------*
 ___  _    ___
| __|/_\  / _ \
| _|/ _ \| (_) |
|_|/_/ \_\\__\_\
/*-----------------------*/
// FAQ Manager
if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_FAQ;", $CUR_USER_ACCESS) ) {
   $faq['link'] = eregi_replace("#LOC#", "webmaster/faq_manager.php", $is_active);
   $faq['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/faq_manager-enabled.gif\" ".$faq['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $faq['link'] = $no_access;
   $faq['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/faq_manager-disabled.gif\" ".$faq['link']." style=\"cursor: pointer;\"><br>\n";
}


/*-----------------------------------------------------------------*
__      __     _                      _
\ \    / /___ | |__  _ __   __ _  ___| |_  ___  _ _
 \ \/\/ // -_)| '_ \| '  \ / _` |(_-<|  _|/ -_)| '_|
  \_/\_/ \___||_.__/|_|_|_|\__,_|/__/ \__|\___||_|

/*-----------------------------------------------------------------*/
// Webmaster Settings
if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_WEBMASTER;", $CUR_USER_ACCESS) ) {
   $webmaster['link'] = eregi_replace("#LOC#", "webmaster/webmaster.php", $is_active);
   $webmaster['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/webmaster-enabled.gif\" ".$webmaster['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $webmaster['link'] = $no_access;
   $webmaster['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/webmaster-disabled.gif\" ".$webmaster['link']." style=\"cursor: pointer;\"><br>\n";
}


/*---------------------------------------------------------------------------------------------------------*
 ___  _              _         __  __
| _ \| | _  _  __ _ (_) _ _   |  \/  | __ _  _ _   __ _  __ _  ___  _ _
|  _/| || || |/ _` || || ' \  | |\/| |/ _` || ' \ / _` |/ _` |/ -_)| '_|
|_|  |_| \_,_|\__, ||_||_||_| |_|  |_|\__,_||_||_|\__,_|\__, |\___||_|
              |___/                                     |___/

-Don't show if disabled in branding options (effectively: 'deactivated')
-Show disabled icon and link to either promo screen or 'no access' message
/*---------------------------------------------------------------------------------------------------------*/
# Make sure plugins are not turned off by branding options
if ( plugins_allowed() ) {

//   if ( full_version() ) {
      # Must be logged-in as WEBMASTER
	if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_PLUGINMANAGER;", $CUR_USER_ACCESS)  ) {
	   $plugins['link'] = eregi_replace("#LOC#", "webmaster/plugin_manager/plugin_manager.php", $is_active);
	//         $plugins['mouseover'] = eregi_replace("#LOC#", "webmaster/plugin_manager/plugin_manager.php", $plugins['link']);
	//         $plugins['mouseover'] = eregi_replace("onclick", "onmouseover", $plugins['mouseover']);
	   $plugins['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/plugins-enabled.gif\" ".$plugins['link']." style=\"cursor: pointer;\"><br>\n";
	} else {
	   $plugins['link'] = $no_access;
	   $plugins['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/plugins-disabled.gif\" ".$plugins['link']." style=\"cursor: pointer;\"><br>\n";
	}
//   } else {
//   	$plugins['link'] = eregi_replace("#LOC#", "../marketing/promotion.php?mod=plugins", $non_active);
//   	$plugins['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/plugins-disabled.gif\" ".$plugins['link']." style=\"cursor: pointer;\"><br>\n";
//	}

} else { // Turned off (don't even show it)
   $plugins['link'] = "";
   $plugins['icon'] = "";
}


/*-----------------------------------------------------------------*
 _  _       _          ___            _
| || | ___ | | _ __   / __| ___  _ _ | |_  ___  _ _
| __ |/ -_)| || '_ \ | (__ / -_)| ' \|  _|/ -_)| '_|
|_||_|\___||_|| .__/  \___|\___||_||_|\__|\___||_|
              |_|
/*-----------------------------------------------------------------*/
if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_HELPCENTER;", $CUR_USER_ACCESS) ) {
   $help_center['link'] = eregi_replace("#LOC#", "javascript:parent.frames.header.showHelp();", $is_active);
   $help_center['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/help_center-enabled.gif\" ".$help_center['link']." style=\"cursor: pointer;\"><br>\n";
} else {
   $help_center['link'] = $no_access;
   $help_center['icon'] = "<img src=\"../skins/".$_SESSION['skin']."/icons/help_center-disabled.gif\" ".$help_center['link']." style=\"cursor: pointer;\"><br>\n";
}

/*-----------------------------------------------------------------*
 ___  _  _         ___        _
/ __|(_)| |_  ___ | _ \ __ _ | |
\__ \| ||  _|/ -_)|  _// _` || |
|___/|_| \__|\___||_|  \__,_||_|
/*-----------------------------------------------------------------*/
if ( supersearch_allowed() ) {
   $supersearch_txt = lang("Search");

   # SitePal is full-version-only
   if ( full_version() ) {
      if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SEARCH;", $CUR_USER_ACCESS) ) {
         # enabled
         $supersearch['link'] = eregi_replace("#LOC#", "modules/super_search/display_settings.php", $is_active);
         $supersearch['icon'] = "<img src=\"modules/super_search/plugin_icon-supersearch.gif\" ".$supersearch['link']." style=\"cursor: pointer;\"><br>\n";
      } else {
         # no access
         $supersearch['link'] = $no_access;
         $supersearch['icon'] = "<img src=\"modules/super_search/plugin_icon-supersearch.gif\" ".$supersearch['link']." style=\"cursor: pointer;\"><br>\n";
      }

   } else {
      # disabled
   $supersearch_txt = "";
   $supersearch['link'] = "";
   $supersearch['icon'] = "";
   }

} else {
   $supersearch_txt = "";
   $supersearch['link'] = "";
   $supersearch['icon'] = "";
}


/*---------------------------------------------------------------------------------------------------------*
    ____          __ __          __               _
   / __ \ __  __ / // /  ____   / /__  __ ____ _ (_)____   _____
  / /_/ // / / // // /  / __ \ / // / / // __ `// // __ \ / ___/
 / ____// /_/ // // /  / /_/ // // /_/ // /_/ // // / / /(__  )
/_/     \__,_//_//_/  / .___//_/ \__,_/ \__, //_//_/ /_//____/
                     /_/               /____/
/*---------------------------------------------------------------------------------------------------------*/
# Pull buttons for add-on modules, if any
$hooked_plugin = special_hook("main_menu_button");

# For each mod utilizing the special main menu hook...
if ( count($hooked_plugin) > 0 ) {
   for ( $n = 0; $n < count($hooked_plugin); $n++ ) {
   //foreach ( $hooked_plugin as $plugin_folder=>$data ) {

      # Build access rule and appropriate button link
      if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi($hooked_plugin[$n]['plugin_folder'], $CUR_USER_ACCESS) || eregi($hooked_plugin[$n]['multiuser_access_code'], $CUR_USER_ACCESS) ) {
         $hooked_plugin[$n]['link'] = eregi_replace("#LOC#", "../plugins/".$hooked_plugin[$n]['plugin_folder']."/".$hooked_plugin[$n]['enabled_button_link'], $is_active);
         $hooked_plugin[$n]['icon'] = "<img src=\"../plugins/".$hooked_plugin[$n]['plugin_folder']."/".$hooked_plugin[$n]['enabled_button_image']."\" ".$hooked_plugin[$n]['link']." style=\"cursor: pointer; max-width: 35px; max-height: 35px;\"><br>\n";
      } else {
         $hooked_plugin[$n]['link'] = $no_access;
         # Disabled image exist or just use enabled icon?
         $disabled_iconfile = $_SESSION['docroot_path']."/sohoadmin/plugins/".$hooked_plugin[$n]['plugin_folder']."/".$hooked_plugin[$n]['disabled_button_image'];
         if ( file_exists($disabled_iconfile) ) {
            # YES - Use special 'disabled' icon
            $hooked_plugin[$n]['icon'] = "<img src=\"../plugins/".$hooked_plugin[$n]['plugin_folder']."/".$hooked_plugin[$n]['disabled_button_image']."\" ".$hooked_plugin[$n]['link']." style=\"cursor: pointer; max-width: 35px; max-height: 35px;\"><br>\n";
         } else {
            # NO - Default to 'enabled' icon, but try to fade it out (in mozilla at least).
            $hooked_plugin[$n]['icon'] = "<img src=\"../plugins/".$hooked_plugin[$n]['plugin_folder']."/".$hooked_plugin[$n]['enabled_button_image']."\" ".$hooked_plugin[$n]['link']." style=\"cursor: pointer; max-width: 35px; max-height: 35px;opacity: .5;\"><br>\n";
         }

      }

   } // End for each mod

} // End if mods found



?>