<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
##############################################################################

##############################################################################
## COPYright NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston
## Copyright 2003-2007 Soholaunch.com, Inc.
## All Rights Reserved.
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
###############################################################################

session_start();
require_once('../includes/product_gui.php');
//include($_SESSION['product_gui']);

/*---------------------------------------------------------------------------------------------------------*
  __  __         __       __
 / / / /___  ___/ /___ _ / /_ ___
/ /_/ // _ \/ _  // _ `// __// -_)
\____// .__/\_,_/ \_,_/ \__/ \__/
     /_/
/*---------------------------------------------------------------------------------------------------------*/
if ($ACTION == "UPDATE") {
   $errors = array();

   $qry1 = "select Username from login where PriKey = ".$_POST['EDIT_USER'];

   $rez1 = mysql_query($qry1);
   if ( mysql_num_rows($rez1) > 0 ) {
      $getName = mysql_fetch_array($rez1);
      //echo "real(".$getName['Username'].") != current(".$_POST['LOGIN_USERNAME'].")<br/>\n";
      
      if($getName['Username'] != $_POST['LOGIN_USERNAME']){
         
         //echo "Nope!<br/>\n";

         # Make sure username doesn't already exist...
         $qry = "select * from login where lcase(Username) = lcase('".$_POST['LOGIN_USERNAME']."') LIMIT 1";
      
        // $qry = "select * from login where Username = '".$_POST['LOGIN_USERNAME']."' LIMIT 1";
         $rez = mysql_query($qry);
         if ( mysql_num_rows($rez) > 0 ) {
            $getOther = mysql_fetch_array($rez);
            $msg = "".lang("The username")." '".$_POST['LOGIN_USERNAME']."' ".lang("is already assigned to another administrator")." \n";
            $msg .= "(<a href=\"edit_user.php?EDIT_USER=".$getOther['PriKey']."\">".$getOther['Email']."</a>).\n";
            $msg .= lang("Please choose a different username.");
            $errors[] = $msg;
         }
      }
   }


	if ( count($errors) < 1 ) {
		// First delete the occurrence of this user in the system
		// ---------------------------------------------------------------
	
//		if ($EDIT_USER > 1) {
//			mysql_query("DELETE FROM login WHERE PriKey = '$EDIT_USER'");
//			mysql_query("DELETE FROM user_access_rights WHERE LOGIN_KEY = '$EDIT_USER'");
//		} else {
//			exit;
//		}
	
		// sleep(2);
	
		// Now pretend this was a new addition to the data table set
		// ---------------------------------------------------------------
	
		$ACCESS_STRING = ";";
	
		reset($HTTP_POST_VARS);
		while (list($name, $value) = each($HTTP_POST_VARS)) {
	
			$value = stripslashes($value);
			$name = stripslashes($name);
	
			if ($name != "EDIT_USER" && $name != "ACTION" && $name != "FULL_NAME" && $name != "LOGIN_USERNAME" && $name != "LOGIN_PASSWORD") {
	         if ( $name == "INVOICES_ONLY" ) {
	            $ACCESS_STRING .= "$value;";
	         } else {
	            $ACCESS_STRING .= "$name;";
	         }
			}
	
		} // End While Loop
	
		// Step 1: Add New User to Login Table
		// --------------------------------------------------------------
	
		$tmp_pw = md5("$LOGIN_PASSWORD");
		mysql_query("UPDATE login set Owner='".$SERVER_NAME."', First_Name='SOHOUSER', Last_Name='".$FULL_NAME."', Email='".$LOGIN_USERNAME."', Username='".$LOGIN_USERNAME."', Password='".$LOGIN_PASSWORD."', Rank='".$tmp_pw."' WHERE PriKey='".$EDIT_USER."'");
	
		//$login_key = mysql_insert_id();
	
		// Step 2: Insert Access Rights Compliment
		// --------------------------------------------------------------
	
		mysql_query("UPDATE user_access_rights set ACCESS_STRING='".$ACCESS_STRING."' WHERE LOGIN_KEY = '".$EDIT_USER."'");
	
		echo "<SCRIPT LANGUAGE=Javascript>\n";
		echo "     alert('".lang("The settings for")." $FULL_NAME ".lang("have been updated.")."');\n";
		echo "     window.location = 'webmaster.php?=SID';\n";
		echo "</SCRIPT>\n";
	
		exit;
	}
} // End Action IF

/*---------------------------------------------------------------------------------------------------------*
  _____                 __         __  __
 / ___/____ ___  ___ _ / /_ ___   / / / /___ ___  ____
/ /__ / __// -_)/ _ `// __// -_) / /_/ /(_-</ -_)/ __/
\___//_/   \__/ \_,_/ \__/ \__/  \____//___/\__//_/
# Create new user now
/*---------------------------------------------------------------------------------------------------------*/
if ( $_POST['ACTION'] == "ADD" ) {
   $errors = array();

   # Make sure username doesn't already exist...
   $qry = "select * from login where lcase(Username) = lcase('".$_POST['LOGIN_USERNAME']."') LIMIT 1";

  // $qry = "select * from login where Username = '".$_POST['LOGIN_USERNAME']."' LIMIT 1";
   $rez = mysql_query($qry);
   if ( mysql_num_rows($rez) > 0 ) {
      $getOther = mysql_fetch_array($rez);
      $msg = "".lang("The username")." '".$_POST['LOGIN_USERNAME']."' ".lang("is already assigned to another administrator")." \n";
      $msg .= "(<a href=\"edit_user.php?EDIT_USER=".$getOther['PriKey']."\">".$getOther['Email']."</a>).\n";
      $msg .= lang("Please choose a different username.");
      $errors[] = $msg;
   }


	if ( count($errors) < 1 ) {
   	$ACCESS_STRING = ";";

   	reset($_POST);
   	while ( list($name, $value) = each($_POST) ) {

   		$value = stripslashes($value);
   		$name = stripslashes($name);

   		if ($name != "ACTION" && $name != "FULL_NAME" && $name != "LOGIN_USERNAME" && $name != "LOGIN_PASSWORD" && $name != $pluginaccess ) {
   		   if ( $name == "INVOICES_ONLY" ) {
   		      $ACCESS_STRING .= "$value;";
   		   } else {
   		      $ACCESS_STRING .= "$name;";
   		   }
   		}

   	} // End While Loop

   	# Add per-plugin settings to access string?
   	if ( count($_POST['pluginaccess']) > 0 ) {
   	   foreach ( $_GET['pluginaccess'] as $key=>$value ) {
   	      $ACCESS_STRING .= $key.";";
   	   }
   	}

//   	echo "[".$ACCESS_STRING."]"; exit;

   	# login table insert
   	$tmp_pw = md5("$LOGIN_PASSWORD");
   	$qry = "INSERT INTO login VALUES('NULL','$SERVER_NAME','SOHOUSER','$FULL_NAME','$LOGIN_USERNAME','$LOGIN_USERNAME','$LOGIN_PASSWORD','$tmp_pw')";
   	mysql_query($qry);

   	$login_key = mysql_insert_id();

   	# user_access_rights insert
   	$qry = "INSERT INTO user_access_rights VALUES('NULL','$login_key','".$ACCESS_STRING."','','future')";
   	mysql_query($qry);

   	# Redirect to edit user
   	$success_msg = lang("New administrator added successfully");
   	header("location: add_user.php?EDIT_USER=".$login_key."&success_msg=".$success_msg); exit;

   	echo "<SCRIPT LANGUAGE=\"Javascript\">\n";
   	echo "     alert('$FULL_NAME ".lang("has been added to your administrative users list.")."');\n";
   	echo "</SCRIPT>\n";


   } // End if no errors

} // End ACTION == ADD


#######################################################
### IF THE 'user_access_rights' TABLE DOES NOT EXIST;
### CREATE IT NOW
#######################################################
if(!table_exists('user_access_rights')){
	create_table('user_access_rights');
}
//		$match = 0;
//		$tablename = "user_access_rights";
//
//		$result = mysql_list_tables("$db_name");
//		$i = 0;
//		while ($i < mysql_num_rows ($result)) {
//			$tb_names[$i] = mysql_tablename ($result, $i);
//			if ($tb_names[$i] == $tablename) {
//				$match = 1;
//			}
//			$i++;
//		}
//
//		if ($match != 1) {
//
//			mysql_db_query("$db_name","CREATE TABLE $tablename (
//
//				PRIKEY INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
//				LOGIN_KEY INT(25),
//				ACCESS_STRING BLOB,
//				FUTURE1 BLOB,
//				FUTURE2 BLOB	)");
//
//		} // End if Match != 1

#######################################################
### START HTML/JAVASCRIPT CODE					    ###
#######################################################

?>
<link rel="stylesheet" type="text/css" href="add_edit_user.css"/>

<style>
.op_title {
   background: #DDE2F8;
   border: 1px solid #d3e9ef;
   font-family: tahoma, verdana, arial, helvetica, sans-serif;
   font-size: 12px;
   font-wieght: bold;
   letter-spacing: 1;
}

.op_box {
   background: #f8f9fd;
   border: 1px solid #d3e9ef;
   font-family: tahoma, verdana, arial, helvetica, sans-serif;
   font-size: 11px;
}

.mod_links {
   font-weight: bold;
}
</style>

<script type="text/javascript">
function del_user(key) {
	var tiny = window.confirm('<? echo lang("You have selected to delete the user")." $EDIT_FULL_NAME"; ?>.\n\n<? echo lang("Once you click OK, you can not undo this process."); ?>\n\n<? echo lang("Are you sure you wish to delete this user"); ?>?');
	if (tiny != false) {
		window.location = "del_user.php?id="+key+"&<?=SID?>";
	} else {
		// Cancel Action
	}
}


function checkusername() {
	var returnval=true; //by default, allow form submission
//	verify_username new_username
	var newemail = document.getElementById('LOGIN_USERNAME');
	var str = newemail.value;
	var at="@";
	var dot=".";
	var comma=",";
	var lat=str.indexOf(at);
	var lstr=str.length;
	var ldot=str.indexOf(dot);
//	if(str != document.getElementById('verify_username').value){
//		alert("The Login Email Addresses do not match!")
//		newemail.style.border = '1px solid red'
//		newemail.focus();
//		returnval=false; //disallow form submission
//		return false; 
////		break; //end loop. No need to continue.
//	}

	if (str.indexOf(at)==-1){
		alert("Invalid Login Email Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(comma)>0){
		alert("Invalid Login Email Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
 		alert("Invalid Login Email Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
  		alert("Invalid Login Email Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(at,(lat+1))!=-1){
  		alert("Invalid Login Email Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
   		alert("Invalid Login Email Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(dot,(lat+2))==-1){
   		alert("Invalid Login Email Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if (str.indexOf(" ")!=-1){
  		alert("Invalid Login Email Address")
		newemail.style.border = '1px solid red'
		newemail.focus();
		returnval=false; //disallow form submission
		return false; 
//		break; //end loop. No need to continue.
	}
	if(returnval!=false){
		document.addUser.submit();	
	}
	return returnval;
}

</script>

</head>

<?

####################################################################
### FOR VISUAL CONSISTANCY; WE USE AN HTML TEMPLATE BUILDER FILE
### LOCATED IN THE /shared FOLDER.  THIS WAY ALL OF OUR MODULE
### INTERFACES LOOK THE SAME. YOU MUST SUPPLY THE VARIABLES:
###
### $MOD_TITLE		Title of this Module
### $THIS_DISPLAY		HTML Content to display to end user
### $BG 			Background Image for content table if used
###
### THIS SAME METHOD SHOULD BE USED WHEN BUILDING ANY OF YOUR OWN
### CUSTOM MODULES.  REMEMBER TO INCLUDE THE HEADER "INCLUDES"
### ABOVE FOR PROPER FUNCTIONALITY WITHIN THE APPLICAITON.
####################################################################
$THIS_DISPLAY = "";

# Errors?
if ( count($errors) > 0 ) {
   $THIS_DISPLAY .= "<div class=\"bg_yellow\" style=\"padding: 5px 10px;\">\n";
   for ( $e = 0; $e < count($errors); $e++ ) {
      $THIS_DISPLAY .= "<p class=\"nomar\"><b>Error:</b> ".$errors[$e]."</p>\n";
   }
   $THIS_DISPLAY .= "</div>\n";
}

$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=\"100%\"><tr>\n";
$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\">\n";


$THIS_DISPLAY .= "<form name=\"addUser\" method=\"post\" action=\"add_user.php\">\n";

# Add or edit?
if ( $_REQUEST['EDIT_USER'] != "" ) {
   /*-----------------------------------*
    ___     _  _  _
   | __| __| |(_)| |_
   | _| / _` || ||  _|
   |___|\__,_||_| \__|
   /*-----------------------------------*/
   $save_btn_text = lang("Save Changes");
   $THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"UPDATE\">\n";
   $THIS_DISPLAY .= "<input type=\"hidden\" name=\"EDIT_USER\" value=\"".$_REQUEST['EDIT_USER']."\">\n";

   # Pull user data
   $result = mysql_query("SELECT * FROM login WHERE PriKey = '$EDIT_USER'");
   while ($row = mysql_fetch_array($result)) {
   	$_POST['FULL_NAME'] = $row['Last_Name'];
   	$_POST['LOGIN_USERNAME'] = $row['Username'];
   	$_POST['LOGIN_PASSWORD'] = $row['Password'];
   }

   $qry = "SELECT ACCESS_STRING FROM user_access_rights WHERE LOGIN_KEY = '".$_REQUEST['EDIT_USER']."'";
   $ares = mysql_query($qry);
   while ($row = mysql_fetch_array($ares)) {
   	$ACCESS_STRING = $row['ACCESS_STRING'];
   }

} else {
   /*-----------------------------------*
      _       _     _
     /_\   __| | __| |
    / _ \ / _` |/ _` |
   /_/ \_\\__,_|\__,_|
   /*-----------------------------------*/
   $save_btn_text = lang("Create New User");
   $THIS_DISPLAY .= "<input type=\"hidden\" name=\"ACTION\" value=\"ADD\">\n";
}

$THIS_DISPLAY .= lang("Admin User's Full Name").":<br/>\n";
$THIS_DISPLAY .= "<input id=\"full_name\" type=\"text\" class=\"text\" name=\"FULL_NAME\" value=\"".$_POST['FULL_NAME']."\" style=\"width:200px;\"><br/><br/>\n";

$THIS_DISPLAY .= lang("User's Login Email Address").":<br/>\n";
$THIS_DISPLAY .= "<input id=\"LOGIN_USERNAME\" type=\"text\" class=\"text\" name=\"LOGIN_USERNAME\" value=\"".$_POST['LOGIN_USERNAME']."\" style=\"width:200px;\"><br/><br/>\n";

$THIS_DISPLAY .= lang("Login Password").":<br/>\n";
$THIS_DISPLAY .= "<input id=\"password\" type=\"text\" class=\"text\" name=\"LOGIN_PASSWORD\" value=\"".$_POST['LOGIN_PASSWORD']."\" style=\"width:150px;\"><br/><br/>\n";

// ========================================================================================
// == Control Access to Each Module
// ========================================================================================
$THIS_DISPLAY .= "<hr style='HEIGHT: 1PX; COLOR: BLACK;'>\n";

$THIS_DISPLAY .= "<b>".lang("Select the separate Modules that this user should have access to").":</b><br/><br/>\n";

//$hooked_plugin = special_hook("main_menu_button");
//$THIS_DISPLAY .= testArray($hooked_plugin);

# Builds data array
$countgroups = 0;
function feature_array($text, $accesscode, $img) {
   static $x = 0;

   if ( $accesscode == "group" ) {
      $GLOBALS['countgroups']++;
   }

   $GLOBALS['feature_array'][$x]['text'] = $text;
   $GLOBALS['feature_array'][$x]['accesscode'] = $accesscode;

   # format image path -- full path passed for plugins, shorthand for standard features
   if ( !eregi("sohoadmin/plugins/", $img) ) {
      $GLOBALS['feature_array'][$x]['img'] = "http://".$_SESSION['this_ip']."/sohoadmin/skins/default/icons/".$img."-enabled.gif";
   } else {
      $GLOBALS['feature_array'][$x]['img'] = $img;
   }
   $x++;
}

$feature_array = array();
//feature_array("Basic Features Group", "group");
feature_array("".lang("Create New Pages")."", "MOD_CREATE_PAGES", "create_pages");
feature_array("".lang("Edit Pages")."", "MOD_EDIT_PAGES", "edit_pages");
feature_array("".lang("Menu Navigation")."", "MOD_MENUSYS", "menu_display");
feature_array("".lang("FAQ Manager")."", "MOD_FAQ", "faq_manager");
feature_array("".lang("File Manager")."", "MOD_SITE_FILES", "file_manager");
feature_array("".lang("Template Manager")."", "MOD_TEMPLATES", "template_manager");
feature_array("".lang("Web Forms")."", "MOD_FORMS", "forms_manager");

# advanced group
//feature_array("Advanced Features Group", "group");
feature_array("".lang("Traffic Statistics")."", "MOD_STATS", "site_statistics");
feature_array("".lang("Photo Albums")."", "MOD_PHOTO_ALBUM", "photo_albums");
feature_array("".lang("Database Tables")."", "MOD_DB_MANAGER", "site_data_tables");
feature_array("".lang("Blog Manager")."", "MOD_BLOG", "blog_manager");
feature_array("".lang("Shopping Cart")."", "MOD_SHOPPING_CART", "shopping_cart");
feature_array("".lang("Event Calendar")."", "MOD_CALENDAR", "event_calendar");
feature_array("".lang("eNewsletter")."", "MOD_NEWSLETTER", "enewsletter");
feature_array("".lang("Member Logins")."", "MOD_SECURITY", "secure_users");
if ($_SESSION['hostco']['sitepal_features'] == "on") {
   feature_array("SitePal", "MOD_SITEPAL", "sitepal");
}
feature_array("".lang("Backup/Restore")."", "MOD_BACKUPRESTORE", "backup_restore");
feature_array("".lang("Manage Plugins")."", "MOD_PLUGINMANAGER", "plugins");
feature_array("".lang("Help Center")."", "MOD_HELPCENTER", "help_center");
feature_array("".lang("Webmaster")."", "MOD_WEBMASTER", "webmaster");

# plugins
$hooked_plugin = special_hook("main_menu_button");
$countplugins = count($hooked_plugin);
if ( $countplugins > 0 ) {
//   feature_array("Plugin Features", "group");
   for ( $p = 0; $p < $countplugins; $p++ ) {
      # Build img path
      $plugin_btnpath = $_SESSION['docroot_fullurl']."/sohoadmin/plugins/".$hooked_plugin[$p]['plugin_folder']."/".$hooked_plugin[$p]['enabled_button_image'];

      # Access code already specified or use plugin folder?
      if ( $hooked_plugin[$p]['multiuser_access_code'] != "" ) {
         $accesscode = str_replace(";", "", $hooked_plugin[$p]['multiuser_access_code']);
      } else {
         $accesscode = $hooked_plugin[$p]['plugin_folder'];
      }

      if ( !array_search($accesscode, $feature_array) !== false ) {
         feature_array($hooked_plugin[$p]['button_caption_text'], $accesscode, $plugin_btnpath);
      }
   }
}

# Build table HTML
# How many rows?
$countbtns = count($feature_array);
$rowmax = ceil(($countbtns - $countgroups) / 5); // 5 per row
$n = 0; // feature_array key counter
$THIS_DISPLAY .= "<table border=\"0\" celpadding=\"5\" cellspacing=\"0\" width=\"90%\" class=\"op_box\">\n";
$THIS_DISPLAY .= " <tr>\n";
$THIS_DISPLAY .= "  <td colspan=\"5\" class='op_title'>";
$THIS_DISPLAY .= "   <b>".lang("Which feature modules should they have access to")."?</b><br/>\n";
$THIS_DISPLAY .= "   ".lang("Click icon to enable/disable")."...\n";
$THIS_DISPLAY .= "  </td>\n";
$THIS_DISPLAY .= " </tr>\n";
for ( $r = 0; $r < $rowmax; $r++ ) { // <tr> loop

   $THIS_DISPLAY .= " <tr>\n";

   for ( $c = 1; $c <= 5; $c++ ) { // <td> loop
      $cellid = "btntd_".$n;

//      if ( eregi("sohoadmin/plugins", $feature_array[$n]['img']) ) {
//         $chkbox_name = "pluginbtns[".$feature_array[$n]['accesscode']."]";
//      } else {
//         $chkbox_name = $feature_array[$n]['accesscode'];
//      }
      $chkbox_name = $feature_array[$n]['accesscode'];

      $chkbox_id = "mmbtn_chk_".$n;
      $btnimgpath = $feature_array[$n]['img'];

      # Enough plugins to fill this cell?
      if ( $feature_array[$n]['text'] != "" ) {
         if ( eregi($feature_array[$n]['accesscode'], $ACCESS_STRING) ) {
            $checked = " checked";
            $dfClass = "fadein";
         } else {
            $checked = "";
            $dfClass = "fade30";
         }

         # YES - Show next plugin button
         $THIS_DISPLAY .= "  <td id=\"".$cellid."\" align=\"center\" valign=\"middle\" class=\"".$dfClass."\" style=\"width: 20%;height: 65px;\">\n";
         # container
         $THIS_DISPLAY .= "   <div id=\"pluginbtn_".$idX."\" style=\"position: relative;\" class=\"mmbtn-container\" onmouseover=\"setClass(this.id, 'mmbtn-container mmbtn-container-hover');\" onmouseout=\"setClass(this.id, 'mmbtn-container');\" onclick=\"toggle_checkbox('".$chkbox_id."');ifChecked_setClass('".$chkbox_id."', '".$cellid."', 'fadein', 'fade30');\">\n";
         # checkbox
         $THIS_DISPLAY .= "    <input id=\"".$chkbox_id."\" type=\"checkbox\" name=\"".$chkbox_name."\" style=\"position: absolute;top: 15px;\"".$checked." onclick=\"toggle_checkbox('".$chkbox_id."');ifChecked_setClass('".$chkbox_id."', '".$cellid."', 'fadein', 'fadeout');\" class=\"fadein\">\n";

         # icon img
         $THIS_DISPLAY .= "    <img src=\"".$btnimgpath."\" style=\"width: 35px;height: 35px;\"/><br/>\n";

         # caption text
         $THIS_DISPLAY .= "    ".plugin_strip_strings($feature_array[$n]['text'])."\n";
//         $THIS_DISPLAY .= "   <br/>".$chkbox_name."\n";


         $THIS_DISPLAY .= "   </div>\n";

         $THIS_DISPLAY .= "  </td>\n";

         $idX++;

      } else {
         # NO - Empty table cell
         $THIS_DISPLAY .= "<td class=\"module_button_cell\">&nbsp;</td>\n";
      }

      $n++;

   } // End for cell loop

   $THIS_DISPLAY .= "   </tr>\n";

} // End for row loop

$THIS_DISPLAY .= " </tr>\n";
$THIS_DISPLAY .= "</table><br/><br/>\n\n";


$THIS_DISPLAY .= "<hr style='HEIGHT: 1PX; COLOR: BLACK;'>\n";

$THIS_DISPLAY .= "<b>".lang("Select each Site Page this user should have access to").":</b><br/>\n";
$THIS_DISPLAY .= "<i>".lang("Note: User will not be able to access these pages unless the Edit Pages module itself is enabled (above).")."</i><br/><br/>\n";

// ========================================================================================
// == LOOP THROUGH ALL SITE PAGES AND PLACE CHECKBOX OPTIONS NEXT TO EACH FOR BLOB RIGHTS
// ========================================================================================
$result = mysql_query("SELECT page_name, url_name, link FROM site_pages WHERE type = 'Main' ORDER BY page_name");

$THIS_DISPLAY .= "\n";
ob_start();
?>
<script type="text/javascript">
// Disables/enables individual page right options based on whether all pages option is checked
function allpages_option() {
   var allpagesisnow = document.getElementById('MOD_ALLPAGES').checked;

   var pageoptions = document.getElementById('page_rights').getElementsByTagName("input");
   var max = pageoptions.length;
   for ( p = 0; p < max; p++ ) {
      if ( allpagesisnow == true ) {
         // All pages
         pageoptions[p].checked = true;
         pageoptions[p].disabled = true;
      } else {
         // Page-by-page
         pageoptions[p].checked = false;
         pageoptions[p].disabled = false;
      }
   }
}
</script>
<?
$THIS_DISPLAY .= ob_get_contents();
ob_end_clean();

$THIS_DISPLAY .= "<div id=\"allpages_option_container\">\n";
$THIS_DISPLAY .= " <input type=\"checkbox\" name=\"MOD_ALLPAGES\" id=\"MOD_ALLPAGES\" onchange=\"allpages_option();\">\n";
$THIS_DISPLAY .= " <label for=\"MOD_ALLPAGES\">".lang("Give user access to edit all site pages, present and future").".</label>\n";
$THIS_DISPLAY .= " <div class=\"ie_cleardiv\"></div>\n";
$THIS_DISPLAY .= "</div>\n";

if ( eregi("MOD_ALLPAGES", $ACCESS_STRING) ) {
   $THIS_DISPLAY .= "<script type=\"text/javascript\">document.getElementById('MOD_ALLPAGES').checked = true;window.setTimeout('allpages_option()', 1000);</script>\n";
}

$THIS_DISPLAY .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"5\" class=\"text\" id=\"page_rights\">\n";

$rcnt = 1;

while ($row = mysql_fetch_array($result)) {
	if(!preg_match('/^http:/i', $row['link'])){
		if ($rcnt == 1) { $THIS_DISPLAY .= "<tr>\n"; }
		$rcnt++;
		
		$checked = "";
		$tmp_chk = eregi_replace(" ", "_", $row['page_name']);
		if ( strpos($ACCESS_STRING, ";$tmp_chk;") ) { $checked = "CHECKED"; }
		
		$THIS_DISPLAY .= "<td align=\"left\" valign=\"top\">\n";
		$THIS_DISPLAY .= " <input class=\"pageright\" type=\"CHECKBOX\" ".$checked." id=\"".$row['page_name']."\" name=\"".$row['page_name']."\" value=\"".$row['page_name']."\">\n";
		$THIS_DISPLAY .= " <label for=\"".$row['page_name']."\">".str_replace(" ", "&nbsp;", $row['page_name'])."</label>";
		$THIS_DISPLAY .= "</td>\n";
		
		if ($rcnt == 5) {
		   $THIS_DISPLAY .= "</tr>\n";
		   $rcnt = 1;
		}
	}
} // End While Loop
$THIS_DISPLAY .= "</TABLE><BR><BR>\n";

$THIS_DISPLAY .= "<hr style='HEIGHT: 1PX; COLOR: BLACK;'>\n";

// ========================================================================================
// == SHOPPING CART ACCESS OPTIONS
// ========================================================================================
if (eregi("INVOICES_NO", $ACCESS_STRING)) {
   $invNoChk = "selected";
} elseif (eregi("INVOICES_YES", $ACCESS_STRING)) {
   $invYesChk = "selected";
}

//$THIS_DISPLAY .= "[$ACCESS_STRING]<br/><br/>";
$THIS_DISPLAY .= "<b>".lang("Shopping Cart access options").":</b><br/>\n";
$THIS_DISPLAY .= "<i>".lang("Note: User must have access to Shopping Cart module itself (above).")."</b><br/><br/>\n";

$THIS_DISPLAY .= "<TABLE BORDER=0 CELPADDING=0 CELLSPACING=0 WIDTH=90% CLASS=op_box>\n";
$THIS_DISPLAY .= "<tr>\n";
$THIS_DISPLAY .= " <td align='left' width='150px' style='padding: 3px;'>".lang("Manage Invoices Only")."?</td>";
$THIS_DISPLAY .= " <td align='left' style='padding: 3px;'>\n";
$THIS_DISPLAY .= "  <select name=\"INVOICES_ONLY\">\n";
$THIS_DISPLAY .= "   <option value=\"INVOICES_NO\" $invNoChk>".lang("No")."</option>\n";
$THIS_DISPLAY .= "   <option value=\"INVOICES_YES\" $invYesChk>".lang("Yes")."</option>\n";
$THIS_DISPLAY .= " </td>\n";
$THIS_DISPLAY .= "</tr>\n";
$THIS_DISPLAY .= "</TABLE>\n";

$THIS_DISPLAY .= "<br/><hr style='HEIGHT: 1PX; COLOR: BLACK;'>\n";

$THIS_DISPLAY .= "<b>".lang("Select each User Data Table this user should have access to").":</b><br/><br/>\n";

// ========================================================================================
// == LOOP THROUGH ALL UDT_ TABLES AND PLACE CHECKBOX OPTIONS NEXT TO EACH FOR BLOB rightS
// ========================================================================================

$THIS_DISPLAY .= "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 WIDTH=90% CLASS=text>\n";
$rcnt = 1;

$result = mysql_list_tables("$db_name");
$i = 0;

while ($i < mysql_num_rows ($result)) {
   $tb_names[$i] = mysql_tablename ($result, $i);
   if (eregi("UDT_", $tb_names[$i])) {    // Only Get UDT Tables (Remember They can delete these kinds of tables (Dangerous)

         if ($rcnt == 1) { $THIS_DISPLAY .= "<TR>\n"; }
         $rcnt++;

         $checked = "";
         if (eregi(";$tb_names[$i];", $ACCESS_STRING)) { $checked = "CHECKED"; }

         $THIS_DISPLAY .= "<TD ALIGN=LEFT VALIGN=TOP>\n";
         $THIS_DISPLAY .= "<INPUT TYPE=CHECKBOX $checked NAME=\"$tb_names[$i]\" VALUE=\"$tb_names[$i]\"> $tb_names[$i]";
         $THIS_DISPLAY .= "</TD>\n";

         if ($rcnt == 4) {
            $THIS_DISPLAY .= "</TR>\n";
            $rcnt = 1;
         }


   }  // End IF UDT
   $i++; // Increment Table Counter
} // End While

$THIS_DISPLAY .= "</TABLE><BR><BR>\n";

$THIS_DISPLAY .= "<div align=\"center\">\n";
if ( $_REQUEST['EDIT_USER'] != "" ) {
	$THIS_DISPLAY .= "<button TYPE=BUTTON class=\"redButton\" onclick=\"del_user('".$_REQUEST['EDIT_USER']."');\"><span><span>".lang("Delete User")."</span></span></button>\n";
}



$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
$THIS_DISPLAY .= "<button type=\"button\" class=\"redButton\" onclick=\"document.location='webmaster.php';\"><span><span>".lang("Cancel")."</span></span></button>\n";
$THIS_DISPLAY .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
$THIS_DISPLAY .= "<button type=\"button\" class=\"greenButton\" onClick=\"checkusername();\"><span><span>".$save_btn_text." &gt;&gt;</span></span></button></div>\n";

$THIS_DISPLAY .= "</form>\n";


$THIS_DISPLAY .= "</td></tr></table>\n";


$module_html = $THIS_DISPLAY;


$module = new smt_module($module_html);
$module->add_breadcrumb_link("".lang("Webmaster")."", "program/webmaster/webmaster.php");

if ( $_REQUEST['EDIT_USER'] != "" ) {
   $module->meta_title = ("".lang("Edit Administrative User").": ".$_POST['LOGIN_USERNAME']);
   $module->add_breadcrumb_link($module->meta_title, "program/webmaster/add_user.php?EDIT_USER=".$_REQUEST['EDIT_USER']);
} else {
   $module->meta_title = lang("Add New Administrative User");
   $module->add_breadcrumb_link($module->meta_title, "program/webmaster/add_user.php");
}
$module->heading_text = $module->meta_title;
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/webmaster-enabled.gif";
$module->description_text = "When this administrative user logs-in, what should he/she have access to? What aspects of your website should he be able to manage?";
$module->good_to_go();

####################################################################

?>
