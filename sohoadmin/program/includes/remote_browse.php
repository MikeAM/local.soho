<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


##############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.9
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com
###############################################################################

######################################################################################
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
#######################################################################################

###
### PAGE BUILDER FOR SOHOLAUNCH MGT. TOOL
### ----------------------------------------------
###
### VERY IMPORTANT DEVELOPER NOTE: WHEN ADDING OR MODIFING THIS SCRIPT,
### DO NOT USE COMMON VARIABLE NAMES SUCH AS $x, $y, ETC.  THE REASON
### IS THAT THE APPLICATION ALLOWS CUSTOM PHP INCLUDES TO BE EXECUTED
### IN REAL-TIME FROM THE APP INTERFACE.  THEREFORE, IF AN INCLUDE USES
### THESE VARIABLES FOR EXECUTION, IT WILL CAUSE THIS SCRIPT TO CRASH
### BECAUSE THE VARIABLES WILL CONFLICT WITH EACH OTHER!
###

error_reporting(0);
session_start();
////
include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");
$_SESSION['rmtemplate'] = $_GET['rmtemplate'];	
////$nft = 'remote_template_file';
//echo testArray($_GET);
//$_GET['rmtemplate'] = 'ANIMALS-Sea1-startsub';
//$_GET['rmtemplate'] = 'remote_template_file';
//$nft = 'remote_template_browsing';
//$nft = $_GET['rmtemplate'];
$rmzemplate = $_GET['rmtemplate'];
$rmztemplate = base64_encode($_GET['rmtemplate']);
$globalprefObj = new userdata('global');
$getseoopt = mysql_query("select data from smt_userdata where plugin='seolink' and fieldname='pref'");
while($seo_optionq = mysql_fetch_assoc($getseoopt)){
     $seo_option = $seo_optionq['data'];
}

	##########################################################################################################
	##========================================================================================================
	## UNDER CONSTRUCTION? - Check for nowiz.txt. Go to custom under construction page if not found.
	##========================================================================================================
	##########################################################################################################
	$filename = "sohoadmin/nowiz.txt";
	if ( !file_exists($filename) && $undercon != "" ) {
	   if ( ini_get("allow_url_fopen") == 1 ) {
	      $file = fopen("$undercon", "r");
	
	      if (!$file) {
	         echo "<p>Unable to open remote file.\n";
	         exit;
	      } else {
	         while (!feof ($file)) {
	            $line = fgets ($file, 1024);
	            if ( trim($line) != "" ) {
	               echo $line."\n";
	            }
	         }
	      }
	      fclose($file);
	      exit;
	   } else {
	      header("location:$undercon");
	      exit;
	   }
	}
	
	$news_promo = mysql_query("SELECT BOX, CONTENT FROM promo_boxes WHERE prikey > 25");
	while($news_promo_cats = mysql_fetch_array($news_promo)){
		if($news_promo_cats['BOX'] == "newsbox"){
			$news_cat = $news_promo_cats['CONTENT'];
		}
		if($news_promo_cats['BOX'] == "promobox"){
			$promo_cat = $news_promo_cats['CONTENT'];
		}
	}
	
	##########################################################################################################
	##========================================================================================================
	## GLOBALS - Configure system variables for language version. Register settings from site_specs table.
	##========================================================================================================
	##########################################################################################################
	if ( !$selSpecs = mysql_query("SELECT * FROM site_specs") ) {
	   echo "\n\n\n\n<!---Unable to select data from site_specs table.--->\n";
	   echo "<!---".mysql_error()."--->\n\n\n\n";
	}
	$getSpec = mysql_fetch_array($selSpecs);
	
	if ( $getSpec['df_lang'] == "" ) {
		$language = "english.php";
		//echo "getSpec[df_lang] = ($getSpec[df_lang])\n";
		//exit;
	
	} else {
	   $language = $getSpec['df_lang'];
	}
	
	if ( $lang_dir != "" ) {
	   $lang_include = $lang_dir."/".$language;
	} else {
	   $lang_include = "sohoadmin/language/$language";
	}
	
	include ($lang_include);
	
	session_register("lang");
	session_register("language");
	
	$_SESSION['getSpec'] = $getSpec;
	
	
	# Restore template-related prefs
	$tplpref = new userdata("template");
	
	
	##########################################################################################################
	##========================================================================================================
	## FUNCTIONS - Build frequently called php and javascript functions
	##========================================================================================================
	##########################################################################################################
	
	/**********************************************************************
	 CREATE STERILIZE PHONE NUMBER FUNCTION.
	 This function makes sure that all phone numbers entered into input
	 boxes are formated the same way each time (pretty cool).
	/*********************************************************************/
	function sterilize_phone ($sterile_var) {
		$sterile_var = eregi_replace("\.", "", $sterile_var);
		$st_l = strlen($sterile_var);
		$st_a = 0;
		$tmp = "";
		while($st_a != $st_l) {
			$temp = substr($sterile_var, $st_a, 1);
			if (eregi("[0-9]", $temp)) { $tmp .= $temp; }
			$st_a++;
		}
		$sterile_var = $tmp;
		$acode = substr($sterile_var, 0, 3);
		$prefix = substr($sterile_var, 3, 3);
		$suffix = substr($sterile_var, 6, 4);
		$thisNum = $acode.$prefix.$suffix;
		$sterile_var = $thisNum;
	
		return $sterile_var;
	}
	
	####################################################################################
	// DEFINE BUILT-IN JAVASCRIPT FUNCTIONS
	
	$thisYear = date("Y");
	
	/**********************************************************************
	 All custom HTML inserts and/or templates can utilize these built-in
	 Javascript functions.  Do not remove these because all modules such
	 as the shopping cart, etc. utilize these as a shortcut.
	/*********************************************************************/
	
	# Include client-side js functions from remote file vs. sticking them all here and junking up the html output
	$javascript = "\n\n<script src=\"sohoadmin/client_files/site_javascript.php\" type=\"text/javascript\"></script>\n\n";
	$javascript .= "<script type=\"text/javascript\" src=\"sohoadmin/program/includes/display_elements/window/prototype.js\"></script>\n";
	$javascript .= "<script type=\"text/javascript\" src=\"sohoadmin/program/includes/display_elements/window/window.js\"></script>\n";
	$javascript .= "<script type=\"text/javascript\" src=\"sohoadmin/program/includes/display_elements/window/effects.js\"></script>\n";
	$javascript .= "<script type=\"text/javascript\" src=\"sohoadmin/program/includes/display_elements/window/debug.js\"></script>\n";
	$javascript .= "<script type=\"text/javascript\" src=\"sohoadmin/client_files/embed.js\"></script>\n";
	
	
	/**********************************************************************
	 Some modules return variable flags that confirm to the end user
	 that an action has been completed.  These inserts are created
	 in javascript, real-time, based on variables past back from the
	 various modules.  Do not remove these for proper performance.
	/*********************************************************************/
	
	if ($emailsent == 1) {
	   $javascript .= "\n\n<script type=\"text/javascript\">\n<!--\n\n";
		$javascript .= "alert(\"".lang("Your message has been sent. Thank you.")."\");\n\n";
		$javascript .= "-->\n</script>\n\n";
	}
	
	if ($epagesent == 1) {
	   $javascript .= "\n\n<script type=\"text/javascript\">\n<!--\n\n";
	   $javascript .= "alert(\"".lang("This page has been emailed to your friend")."! ".lang("Thank you")."!\");\n\n";
	   $javascript .= "-->\n</script>\n\n";
	}
	
	
	// END JAVASCRIPT INSERTION
	####################################################################################
	
	
	
	########################################################################
	### DEFINE BASE STYLESHEET FOR ALL PAGES GENERATED TO UTILIZE. THIS
	### CAN BE MODIFIED FOR TEMPLATES TO USE AS WELL ON A SITE BY SITE BASIS
	########################################################################
	
	
	//$stylesheet = "\n\n<LINK rel=\"stylesheet\" href=\"runtime.css\" type=\"text/css\">\n";
	
	########################################################################
	### IF USING THE DATABASE MODULE, A DATABASE STYLE SHEET WILL BE COPIED
	### TO THE DOC_ROOT.  THEREFORE, IT MUST BE ADDED SO THAT THE DATABASE
	### MODULE CAN UTILIZE FOR SPECIFIC DISPLAY NEEDS.
	########################################################################
	
	if (file_exists("database.css")) {
		$stylesheet .= "<LINK rel=\"stylesheet\" href=\"database.css\" type=\"text/css\">\n\n";
	}
	
	########################################################################
	### BUILD USER DEFINED META-TAG DATA HEADERS AS DEFINED IN THE
	### "Options & Settings" MAIN MENU OPTION OF THE APPLICATION
	########################################################################
	
	$filename = "$cgi_bin/meta.conf";
	
	if (file_exists("$filename")) {
		$file = fopen("$filename", "r");
			$body = fread($file,filesize($filename));
		fclose($file);
		$lines = split("\n", $body);
		$numLines = count($lines);
		for ($xedusvar=0;$xedusvar<=$numLines;$xedusvar++) {
			$temp = split("=", $lines[$xedusvar]);
			$variable = $temp[0];
			$value = $temp[1];
			${$variable} = $value;
		}
	
	} else {
	
		// -----------------------------------------------------------------------
		// If user has not set any keywords or descriptive text, then let's take
		// this opportunity to make a shameless plug for our product
		// --Removed 05/10/04 for White-Label
		// -----------------------------------------------------------------------
	
		$site_description = $_SERVER['SERVER_NAME'];
		$site_keywords = $_SERVER['SERVER_NAME'];
	}
	
	
	#################################################################################
	// #LOGO#
	#################################################################################
	$logoconf = "$cgi_bin/logo.conf"; // Only a concern until first login after upgrade
	
	if ( $getSpec[df_hdrtxt] != "" ) {
	   // Check site_specs first (current method)
	   // --------------------------------------------
	   $headertext = $getSpec[df_hdrtxt];
	   $subheadertext = $getSpec[df_slogan];
	
	} elseif (file_exists("$logoconf")) {
	   // Check for config file
	   // -------------------------------
	   $file = fopen("$logoconf", "r");
	      $body = fread($file,filesize($logoconf));
	   fclose($file);
	   $lines = split("\n", $body);
	   $numLines = count($lines);
	   for ($xedusvar=0;$xedusvar<=$numLines;$xedusvar++) {
	      $temp = split("=", $lines[$xedusvar]);
	      $variable = $temp[0];
	      $value = $temp[1];
	      $value = stripslashes($value);
	      ${$variable} = $value;
	   }
	
	} else {
	   # Default to whatever's in the db even if one of them is blank (v4.9 r38)
	   $headertext = $getSpec[df_hdrtxt];
	   $subheadertext = $getSpec[df_slogan];
	}
	
	###################################################################################
	### THE PAGE REQUEST WAS SENT TO US VIA $pageRequest OR $pr DEPENDING
	### ON THE MODULE ACCESSING THE index.php FILE.  LET'S GET THAT INFO
	### FROM OUR site_pages DATABASE TABLE.  IF NO PAGE REQUEST DATA WAS
	### SENT TO THE SCRIPT, WE ARE ASSUMING THIS IS A FIRST TIME HIT AND WE
	### WILL RETURN THE HOME PAGE BY DEFAULT.
	###################################################################################
	
	// STEP 1: Make Sure Spaces are eliminated
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	//$pageRequest = eregi_replace("_", " ", $pageRequest);
	$pageRequest = eregi_replace(" ", "_", $pageRequest);
	
	// STEP 2: If PR var is empty, assign it to the Home Page
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	if ($pageRequest == "") { $pageRequest = startpage(); }
	
	// STEP 3: Define if the page is being called by internal link or not.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// DEVNOTE: Each page created in the system is assigned a primary key number that is
	// used to access pages from within modules and content areas. (This is the key to
	// making the word processor work properly in the page editor)
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	$filename = "$cgi_bin/$pageRequest.con";
	
	if (!file_exists("$filename")) {	//
	   # .con file not found - This page must have been called by the PriKey
		$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE link = '$pageRequest'");
		$tmp = mysql_num_rows($result); // In case underscore is used when actually creating a page
		if ($tmp <= 0) {
			$newSqlQuery = $pageRequest;
			$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE page_name = '$newSqlQuery'");
		}
	
		while ($row = mysql_fetch_array ($result)) {
			$pageRequest = $row['page_name'];
			$security_code = $row['username'];	// Does this page require Authentication?
			$page_template = $row['password'];
			$tmppkw = split("~~~SEP~~~", $row['password']);
			$splashpage = $row['splash'];
			$splash_bg = $row['bgcolor'];
			$page_title = $row['title'];
			$page_description = $row['description'];
			$page_keywords = $tmppkw[0];
			$page_temp = $row['template'];
		}
	
		$pageRequest = eregi_replace(" ", "_", $pageRequest);
	
	} else {
	   # .con file found - Normal page request
		$thisPage = eregi_replace("_", " ", $pageRequest);
		$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE page_name = '$thisPage'");
		$tmp = mysql_num_rows($result);	// In case underscore is used when actually creating a page
	
		if ($tmp <= 0) {
			$newSqlQuery = $pageRequest;
			$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE page_name = '$newSqlQuery'");
		}
	
		while ($row = mysql_fetch_array ($result)) {
	
			$pageRequest = $row['page_name'];
			$security_code = $row['username'];	// Does this page require Authentication?
			$page_template = $row['password'];
			$tmppkw = split("~~~SEP~~~", $row['password']);
			$splashpage = $row['splash'];
			$splash_bg = $row['bgcolor'];
			$page_title = $row['title'];
			$page_description = $row['description'];
			$page_keywords = $tmppkw[0];
			$page_temp = $row['template'];
	
		}
		$page_temp = 'remote_template_browsing';
		$pageRequest = eregi_replace(" ", "_", $pageRequest);
	
	}
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// We have new pageRequest name if available.  Let's make sure this is page that has
	// been created within the page editor or if content exists
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	$filename = "$cgi_bin/$pageRequest.con";
	
	// STEP 4: Determine if this page has been created by the user yet
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	if (!file_exists("$filename")) {	// Page content does not exist
	
		$error = 404;
		$content_line = "";
		$numlines = 0;
	
		// **********************************************************************
		// At this point, we have found this page to not be present
		// at all or "yet", so we do an under construction display for content.
		// **********************************************************************
	
		$errordisplay = "";
	//	$errordisplay .= "\n<img src=\"sohoadmin/client_files/under_construction.gif\" width=\"273\" height=\"74\" border=\"0\">\n"; // Commented-out by request in v4.9 r32
	
	} else {					// Page does exist; get the content HTML
	
		$file = fopen("$filename", "r");
			$body = fread($file,filesize($filename));
		fclose($file);
	
		$content_line = split("\n", $body);	// We have just placed the content HTML into the $content_line Array.
		$numlines = count($content_line);	// $numlines is now equal to the number of lines in the content HTML
	
	}
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// STEP 5: Does Page Require Authentication?
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	$SECURE_OK = 0;
	if (eregi("$security_code", $GROUPS)) { $SECURE_OK = 1; }
	if (strlen($security_code) >= 3 && $SECURE_OK == 0) {
		// If so, then this page needs security authentication before it can be accessed.
		// The authentication routine is built as an include so that we can make sure
		// that the authentication works on both Apache compiled PHP and CGI PHP.
		// Plus, we don't need all that code if no auth is required. It will make our
		// script run faster.
		include("pgm-authenticate.php");
	}
	
	##############################################################################
	### SETUP BASE TEMPLATE HTML AS ASSIGNED IN "Site Template(s)" MENU OPTION.
	### NOW THAT WE HAVE THE CONTENT HTML PLACED INTO THE $content_line
	### ARRAY, LET'S GET THE TEMPLATE HTML AND PLACE IT INTO AN ARRAY AS
	### WELL SO THAT WE CAN PROCESS ALL THE HTML THROUGH THE "BUILD INTERPRETER"
	##############################################################################
	
	###############################################################################
	// Read Template Config File
	###############################################################################
	
	
	# Pull base template if no page-specific definition
	if ( $page_temp != "" ) {
	
	   # Pull page-specific template, as stored in site_pages table
	   $filename = $page_temp;
	   $daTemp = $page_temp;
	   $what_template = $page_temp;
	
	} else {
	
	   # Pull base site template from config file
//	   $baseT = "sohoadmin/tmp_content/template.conf";
	
	   # MM 2004-08-01: Create and select default template if none specified (fixes blank screen problem)
	   $default_template = "remote_template_browsing"; // Dedicated to Mark Reedy ;-)

		if($globalprefObj->get('site_base_template') == ''){
			$globalprefObj->set('site_base_template', $default_template);
		}
	
//	   if ( !file_exists($baseT) ) {
//	      $file = fopen("$baseT", "w");
//	      	fwrite($file, "$default_template");
//	      fclose($file);
//	   }
	
//	   $file = fopen("$baseT", "r");
//	   $what_template = fread($file,filesize($baseT));
//	   fclose($file);
		$what_template = $globalprefObj->get('site_base_template');
	   $base_template = $what_template;	// In case of individual page definitions
		$base_template = 'remote_template_browsing';
	  	$filename = 'remote_template_browsing';
	  	$daTemp = 'remote_template_browsing';
	  // $filename = $base_template;
	   //$daTemp = $base_template;
	
	} // End if unique template assigned to this page
	$filename = 'remote_template_browsing';
	###############################################################################################################
	// Determine the directory where we will find our template HTML and open it; parse image data and move on
	###############################################################################################################
	
	if (eregi("tCustom", $filename)) {
		// This is a custom template.
		$template_dir = "tCustom/";
		$CustomFlag = 1;
		//$filename = eregi_replace("/tCustom/","tCustom/",$filename);
		$automenu = "pgm-auto_menu.php"; // Use standard auto-menu
		$faq_display_file = "pgm-faq_display.php";
	
	} else {
		if ( is_dir($_SESSION['template_path']) ) {
		   $template_dir = "sohoadmin/program/modules/site_templates/"; // Pull from remote template directory
		} else {
		   $template_dir = "sohoadmin/program/modules/site_templates/";	// Pull from base dir (just in case)
		}
	
		//echo "Template directory: [".$template_dir."]";
		$CustomFlag = 0;
	
	   # Stick entire path to template folder in one var (vs. doing $template_dir.$filename over and over)
		$template_path = $template_dir.$filename;
	   $template_path_full_url = isHttps().$_SESSION['docroot_url']."/".str_replace($_SESSION['docroot_path'], "", $template_path);
	   $template_folder = $filename;
	
		// Allow unique Home Page and News Article template files -- MM v4.7 RC1 & RC4, respectively
		// =========================================================================================
		$hpTemp = $template_dir.$filename."/home.html";
		$nwTemp = $template_dir.$filename."/news.html";
	
		if ( $pr == "" && $_GET['mailpage'] != "" ) { $pr = $_GET['mailpage']; }
	
		if ( file_exists($hpTemp) && ($pr == "" || $pr == startpage()) && ($nShow == "" && $bShow == "") ) {
			$promoFile = "home";
		   $layout_file = "home.html";
	
		} elseif ( file_exists($nwTemp) && ($nShow != "" || $bShow != "") ) {
			$promoFile = "news";
	      $layout_file = "news.html";
	
		} else {
			$promoFile = "index";
	      $layout_file = "index.html";
		}
	
		$template = $filename."/".$layout_file;
	
		// Let individual templates use their own stylesheet -- MM v4.7 RC1
		// =======================================================================
		if ($nft != "") {
			$filename = $nft;
		}
		$cStyle = $template_dir.$filename."/custom.css";
		if ( file_exists($cStyle) ) { $stylesheet = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$cStyle."\"/>"; }
	
		# Allow for a separte home.css for the start (home) page
		$home_css = $template_dir.$filename."/home.css";
		if ( file_exists($home_css) && ($pr == "" || $pr == startpage()) && ($nShow == "" || $bShow == "") ) {
		   $stylesheet = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$home_css."\"/>";
		}
	
		# news.css
		$news_css = $template_dir.$filename."/news.css";
		if ( file_exists($news_css) && ($_REQUEST['nShow'] != "" || $_REQUEST['bShow'] != "") ) {
		   $stylesheet = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$news_css."\"/>";
		}
	
		$stylesheet .= "<link href=\"sohoadmin/program/includes/display_elements/window/default.css\" rel=\"stylesheet\" type=\"text/css\"></link>\n";
	  // $stylesheet .= "<link href=\"sohoadmin/program/includes/display_elements/window/onscreen_edit.css\" rel=\"stylesheet\" type=\"text/css\"></link>\n";
	
	
		// Let individual templates use their own pgm-auto_menu.php -- MM v4.7 RC4
		// ==============================================================================
		$cMenu = $template_dir.$filename."/pgm-auto_menu.php";
		if ( file_exists($cMenu) ) { $automenu = $cMenu; } else { $automenu = "pgm-auto_menu.php"; } // Use normal auto_menu if no custom one is found
	
	eval(hook("pgm-realtime_builder.php:after_custom_auto_menu_check"));
	
		// Let individual templates use their own pgm-promo_boxes.php -- MM v4.81
		// ==============================================================================
		$cPbox = $template_dir.$filename."/pgm-promo_boxes.php";
		if ( file_exists($cPbox) ) { $prnewsbox = $cPbox; } else { $prnewsbox = "pgm-promo_boxes.php"; } // Use normal promo_boxes if no custom one is found
	
		# Let individual templates use their own pgm-faq_display.php -- MM v4.8.5 r3
		$cFaq = $template_dir.$filename."/pgm-faq_display.php";
		if ( file_exists($cFaq) ) { $faq_display_file = $cFaq; } else { $faq_display_file = "pgm-faq_display.php"; } // Use normal promo_boxes if no custom one is found
	
		# Let individual templates use their own pgm-blog_display.php -- MM v4.9 r32
		$cBlog = $template_path."/pgm-blog_display.php";
		if ( file_exists($cBlog) ) { $blog_display_file = $cBlog; } else { $blog_display_file = "pgm-blog_display.php"; } // Use normal file if no custom one is found
	
	
		// Let individual templates use their own custom includes -- MM v4.7 RC1
	// Let individual templates use their own custom includes -- MM v4.7 RC1
	// =========================================================================
	// includethis.inc
	$cussfiley = $template_dir.$filename."/remote_template.php";
	$truefile = $template_dir.$filename."/includethis.inc";
	$incfile = $template_dir.$filename."/includethis.inc";
//	$incfile = $template_dir.$filename."/includethis.inc";
//	$cInc = $template_dir.$filename."/includethis.inc";
	$cInc = $template_dir.$filename."/includethis.inc";
	if ( file_exists($cInc) ) { $incfile = $cInc; }

	// includethis2.inc
	$cIncB = $template_dir.$filename."/includethis2.inc";
	if ( file_exists($cIncB) ) { $incfileB = $cIncB; }

	// includethis3.inc
	$cIncC = $template_dir.$filename."/includethis3.inc";
	if ( file_exists($cIncC) ) { $incfileC = $cIncC; }


	$filename = $template_dir.$template;
}

// Newsletter Force Template (Generate Newsletter HTML Code)
if ($nft != "") {
	$filename = "sohoadmin/program/modules/site_templates/".$nft."/index.html";
	$template_dir = "sohoadmin/program/modules/site_templates/";
	$daTemp = $nft;
	$single_template_change = 1;
}

// Read actual template HTML into memory
//echo "this template (".$filename.")<br>";
$file = fopen("$filename", "r");
	$tbody = fread($file,filesize($filename));
fclose($file);

ob_start();
include($cussfiley);
$tbodyz = ob_get_contents();
ob_end_clean();
$tbody = $tbodyz;

//ob_start();
//include($truefile);
//$tbodyzz = ob_get_contents();
//ob_end_clean();
//$tbody2 = $tbodyzz;
//
//
//echo $tbody = eregi_replace('#CUSTOMINC#', $tbody2, $tbody);


//$incfile
//echo "This is the template!(<br>".$tbody."<br>)";
//echo "<textarea name=\"textarea\" style=\" width: 600; height: 500;\">".$tbody."</textarea><br><br>\n";

// Make Content Area splitable by process routine later in pgm
$tbody = eregi_replace("#CONTENT#", "\n#CONTENT#\n", $tbody);
$template_line = split("\n", $tbody);
$numtlines = count($template_line);
	
	// Kill body properties in case we re-write for calendar's, etc.
	
	for ($xedusvar=0;$xedusvar<=$numtlines;$xedusvar++) {
		if (eregi("<body", $template_line[$xedusvar])) {
			$bodytag = eregi("<body(.*)>", $template_line[$xedusvar], $out);
			$bodytag = "<body " . $out[1] . ">";
		}
		if ($CustomFlag == 1) {
			$template_line[$xedusvar] = eregi_replace("amp;", "", $template_line[$xedusvar]);
	
			// 2004-08-01: Added checks for absolute paths
			if ( eregi("src=\"", $template_line[$xedusvar]) && !eregi("src=\"https:", $template_line[$xedusvar]) && !eregi("src=\"http:", $template_line[$xedusvar]) && !eregi(".swf", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("src=\"", "src=\"images/", $template_line[$xedusvar]);
			}
	
			// Mantis #0000072: Added checks for swf files (so they pull from /media vs. /images)
			if ( eregi("src=\"", $template_line[$xedusvar]) && eregi(".swf", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("src=\"", "src=\"media/", $template_line[$xedusvar]);
			}
	
			if ( eregi("background=\"", $template_line[$xedusvar]) && !eregi("background=\"http:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("background=\"", "background=\"images/", $template_line[$xedusvar]);
			}
	
			if ( eregi("background-image: url(", $template_line[$xedusvar]) && !eregi("background-image: url(http:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("background-image: url(", "background-image: url(images/", $template_line[$xedusvar]);
			}
	
		} else {	// Change Image Directory for template regardless; sometimes Windows servers screw up and can't copy files correctly!
			if ( eregi("src=\"", $template_line[$xedusvar]) && !eregi("src=\"http:", $template_line[$xedusvar]) && !eregi("src=\"https:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("src=\"", "src=\"$template_dir".$daTemp."/", $template_line[$xedusvar]);
			}
	
			if ( eregi("background=\"", $template_line[$xedusvar]) && !eregi("background=\"http:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("background=\"", "background=\"$template_dir".$daTemp."/", $template_line[$xedusvar]);
			}
	
			if ( eregi("background-image: url(", $template_line[$xedusvar]) && !eregi("background-image: url(http:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("background-image: url(", "background-image: url(".$template_dir.$daTemp."/", $template_line[$xedusvar]);
			}
	
		}
	
	} // End For Loop
	
	
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// STEP 2: Finally, none of the template data matters if this page has been classified
	// as a splash page from the page properties settings.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	//echo "(".$splashpage.")";
	//echo "(".$splash_bg.")";
	
	# Splash page?
	if (($splashpage == "y" || $splashpage == "i") && $module_active != "yes") {
	
		if ($splash_bg != "" && $splashpage == "y") { $splash_bg = "#".$splash_bg; }
	
		if ($splash_bg != "" && $splashpage == "i") { $splash_bg = stripslashes($splash_bg); }
	
		//echo "(".$splash_bg.")";
	
		// *********************************************************************
		// Open runtime.css to see if .bg has been placed inside
		// style sheet call (this can control a BODY style for the splash page).
		// *********************************************************************
	
		$filename = $WIN_FULL_PATH."runtime.css";
	
		$file = fopen("$filename", "r");
			$csbody = fread($file,filesize($filename));
		fclose($file);
	
		if (eregi("\.bg", $csbody)) {
			$classify = "class=\"bg\"";
			$centertag = "no";
	
			if (eregi("sohoalign", $csbody)) {
				$centertag = "yes";
				$classify = "class=\"bg\"";
			}
	
		} else {
	
			$centertag = "yes";
	
			if ($splash_bg != "" && $splashpage == "y") {
				$classify = "bgcolor=".$splash_bg;
			}
			if ($splash_bg != "" && $splashpage == "i") {
				$classify = "background=images/".$splash_bg;
			}
	
		}
	
		$site_title = stripslashes($site_title);
	
	
		# CREATE THE SPLASH PAGE TEMPLATE HTML (VERY SIMPLE AND DIRECT)
		$template_line[0] = "<html>";
		$template_line[1] = "<head>";
	
		// Check for unique page title (via page properties)
		# Remember: This is just for splash pages
		if ( strlen($page_title) > 2 ) {
		   $template_line[2] = "<title>$page_title</title>\n";
		} else {
		   $template_line[2] = "<title>$site_title</title>\n";
		}
	
		$template_line[3] = "\n<!-- SPLASH PAGE -->\n\n";
		$template_line[4] = "</head>\n";
		$template_line[5] = "<body marginheight=0 marginwidth=0 topmargin=0 leftmargin=0 $classify>\n";
	
		if ($centertag == "yes") {
			$template_line[5] .= "<center>\n";
		}
	
		$template_line[6] = "#CONTENT#";
	
		$template_line[7] = "</body></html>\n";
	
		$numtlines = 7;
	} // End if splash page
	
	// ***************************************************************************************
	// DEVNOTE: At this point, the template HTML is housed in the $template_line[] array and
	// the content HTML is housed in the $content_line[] array.
	// ***************************************************************************************
	
	##############################################################################
	### DEFINE USE OF AUTO-MENU SYSTEM // IF NOT USING, DISREGARD, ELSE INCLUDE
	### THE MENU CREATION ROUTINE AND BUILD DYNAMIC MENU SYSTEM.  THIS IS AN
	### INCLUDE BECAUSE 90% OF THE SITES BUILT UTILIZE A CUSTOM TEMPLATE WITH
	### SOME TYPE OF CUSTOM NAVIGATION STRUCTURE.  THEREFORE, THIS CODE SIMPLY
	### SLOWS THE GENERATION OF THE PAGES DOWN IF THEY ARE NOT USING IT.
	##############################################################################
	
	##############################################################################
	/// Pro Edtion v4.7 -- 2004-07-26
	/// ------------------------------------------MM
	### Added checks for new template variables to menu check loop
	##############################################################################
	
	// STEP 1: What variable features is this template utilizing?
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	$auto_menu_on = "no";
	$boxCheck = "";
	
	for ($menu_chk=0;$menu_chk<=$numtlines;$menu_chk++) {
	
		// Check for menu variables
		// ---------------------------------
		if (eregi("#HMENU#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#VMENU#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#TMENU#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#HMAINS#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#VMAINS#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; $vmenuCheck = 1; }
		if (eregi("#HSUBS#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#VSUBS#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#CUSTOMPHP#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; } // Allows custom scripts to output VMENU var
		if (eregi("#CUSTOMPHP2#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; } // Allows custom scripts to output VMENU var
		if (eregi("#CUSTOMINC#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; } // Allows custom scripts to output VMENU var
		if (eregi("#CUSTOMINC2#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; } // Allows custom scripts to output VMENU var
	
		// Check for promo and news variables
		// -------------------------------------
		if (eregi("#BOX1#", $template_line[$menu_chk])) { $boxCheck .= "box1;"; }
		if (eregi("#BOX2#", $template_line[$menu_chk])) { $boxCheck .= "box2;"; }
		if (eregi("#BOX3#", $template_line[$menu_chk])) { $boxCheck .= "box3;"; }
		if (eregi("#BOX4#", $template_line[$menu_chk])) { $boxCheck .= "box4;"; }
		if (eregi("#BOX5#", $template_line[$menu_chk])) { $boxCheck .= "box5;"; }
		if (eregi("#BOX6#", $template_line[$menu_chk])) { $boxCheck .= "box6;"; }
		if (eregi("#BOX7#", $template_line[$menu_chk])) { $boxCheck .= "box7;"; }
		if (eregi("#BOX8#", $template_line[$menu_chk])) { $boxCheck .= "box8;"; }
	
		if (eregi("#BOX-TITLE1#", $template_line[$menu_chk])) { $boxCheck .= "box-title1;"; }
		if (eregi("#BOX-TITLE2#", $template_line[$menu_chk])) { $boxCheck .= "box-title2;"; }
		if (eregi("#BOX-TITLE3#", $template_line[$menu_chk])) { $boxCheck .= "box-title3;"; }
		if (eregi("#BOX-TITLE4#", $template_line[$menu_chk])) { $boxCheck .= "box-title4;"; }
		if (eregi("#BOX-TITLE5#", $template_line[$menu_chk])) { $boxCheck .= "box-title5;"; }
		if (eregi("#BOX-TITLE6#", $template_line[$menu_chk])) { $boxCheck .= "box-title6;"; }
		if (eregi("#BOX-TITLE7#", $template_line[$menu_chk])) { $boxCheck .= "box-title7;"; }
		if (eregi("#BOX-TITLE8#", $template_line[$menu_chk])) { $boxCheck .= "box-title8;"; }
	
		if (eregi("#PROMOHDR1#", $template_line[$menu_chk])) { $boxCheck .= "promohdr1;"; }
		if (eregi("#PROMOHDR2#", $template_line[$menu_chk])) { $boxCheck .= "promohdr2;"; }
		if (eregi("#PROMOHDR3#", $template_line[$menu_chk])) { $boxCheck .= "promohdr3;"; }
		if (eregi("#PROMOTXT1#", $template_line[$menu_chk])) { $boxCheck .= "promotxt1;"; }
		if (eregi("#PROMOTXT2#", $template_line[$menu_chk])) { $boxCheck .= "promotxt2;"; }
		if (eregi("#PROMOTXT3#", $template_line[$menu_chk])) { $boxCheck .= "promotxt3;"; }
		if (eregi("#NEWSBOX#", $template_line[$menu_chk])) { $boxCheck .= "newsbox;"; }
		if (eregi("#NEWSBOX-([0-9]{1,3})#", $template_line[$menu_chk], $nbvar)) { $boxCheck .= "newsbox-".$nbvar[1].";"; } // And thus begins a new era in Soholaunch variable-features (2004-09-13).

	}
	
	if ($auto_menu_on == "yes") { include("$automenu"); } // Include auto-menu script to build menu vars
	if ($boxCheck != "" || $nShow != "" || $bShow != "" ) { include("$prnewsbox"); } // Include promo and newsbox script to build vars
	
	
	#######################################################################################
	### DEFINE template_header AND template_footer variable TO SEND BACK
	### TO index.php. THIS IS WHERE WE START WORKING ON THE DYNAMIC ELEMENTS
	### OF THE CONTENT AND TEMPALTE HTML, INSERTING DATA WHERE NEEDED AND
	### MODIFING THE HTML FOR FINAL OUTPUT
	#######################################################################################
	
	$switchvar = 0;			// This will determine when we switch from header to footer
	
	$template_header = "";		// Start header blank
	$template_footer = "";		// Start footer blank
	
	# Allow templates to include their own global functions include
	$filename = $template_path."/template_functions.php";
	if ( file_exists($filename) ) { include_once($filename); }
	
	#################################################################################################################
	##===============================================================================================================
	// Start "xedusvar" loop through template HTML code now (outer loop)
	##===============================================================================================================
	#################################################################################################################
	
	for ($xedusvar=0;$xedusvar<=$numtlines;$xedusvar++) {
	
	   eval(hook("pgm-realtime_builder.php:template_loop"));
	
		// Place current year in place of #YEAR# variable (Added June 2002)
		//====================================================================================
		$TROT = date("Y");
		$template_line[$xedusvar] = eregi_replace("#YEAR#", "$TROT", $template_line[$xedusvar]);
	
	
		// Add proper css class to all "input type=submit" button tags
		//====================================================================================
		$template_line[$xedusvar] = eregi_replace("input type=submit", "input type=submit class=FormLt1", $template_line[$xedusvar]);
		$template_line[$xedusvar] = eregi_replace("input type=\"submit\"", "input type=submit class=FormLt1", $template_line[$xedusvar]);
	
	
		// Add correct <title> to html
		//==================================================================================
		$site_title = stripslashes($site_title);
	   if ($site_title == "") { $site_title = $SERVER_NAME; }
	
		// Does this page have a unique title?
		if ( strlen($page_title) > 2 ) {
		   $dTtle = $page_title;
		} else {
		   $dTtle = $site_title;
		}
	
		# Place generated <title> and strip hardcoded <title> - v4.9 RC2
		$title_tag_line = "<title>".$dTtle."</title>\n";
		$template_line[$xedusvar] = eregi_replace("<title>(.*)</title>", "", $template_line[$xedusvar]);
	
	
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// If "force_link" var == YES : Force all Links on page to maroon for easy viewing
		// This variable is pushed to this module from the calendar module. Because of the
		// white background display of the calendar, we want to make sure that any link colors
		// specified in the template do not make the calendar unreadable.
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
		if ($force_link == "yes") {
	
			$modbgcolor = "FFFFFF";
	
			if (eregi("<body", $template_line[$xedusvar])) {
	
				$calendarline = $template_line[$xedusvar];
				$fi = split(" ", $calendarline);
	
				$nfi = count($fi);
				$newline = "";
	
				for ($cl=0;$cl<=$nfi;$cl++) {
					$doneit = 0;
					if (eregi("link=", $fi[$cl])) { $newline .= " link=maroon "; $doneit = 1; }
					if (eregi("alink=", $fi[$cl])) { $newline .= " alink=maroon "; $doneit = 1; }
					if (eregi("vlink=", $fi[$cl])) { $newline .= " vlink=maroon "; $doneit = 1; }
					if (eregi("text=", $fi[$cl])) { $newline .= " text=black "; $doneit = 1; }
					if ($doneit == 0) { $newline .= " $fi[$cl] "; }
				}
	
				$template_line[$xedusvar] = $newline;
	
			}
	
		} // End if "force_link" is on
	
	
	   #######################################################################
		// Add META data and Javascript to <head>
		#######################################################################
	
		// Build meta tags
		// ============================
	   $metatag = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"/>\n";
	   $metatag .= "<meta name=\"resource-type\" content=\"document\"/>\n";
	
	   // Does this page have a unique description?
	   // ------------------------------------------
	   if ( strlen($page_description) > 2 ) {
	      $metatag .= "<meta name=\"description\" content=\"".$page_description."\"/>\n";
	   } else {
	      $metatag .= "<meta name=\"description\" content=\"".$site_description."\"/>\n";
	   }
	
	   // Add both site and page keywords
	   if($page_keywords != "" && $page_keywords != " "){
	      $metatag .= "<meta name=\"keywords\" content=\"".$page_keywords."\"/>\n";
	   }else{
	      $metatag .= "<meta name=\"keywords\" content=\"".$site_keywords."\"/>\n";
	   }
	
	   // Has user defined custom copyright text?
	   // ------------------------------------------
	   if ( strlen($getSpec[copyright]) > 6 && !eregi("My Company", $getSpec['copyright']) ) {
	      $metatag .= "<meta name=\"copyright\" content=\"".$getSpec['copyright']."\"/>\n";
	   } else {
	      $metatag .= "<meta name=\"copyright\" content=\"".$_SESSION['docroot_url']." $thisYear.  All rights reserved.\"/>\n";
	   }
	
	   # Auto-detect and link favicon.ico -v4.7 r11222004
	   $metatag .= "<link rel=\"shortcut icon\" href=\"favicon.ico\"/>\n";
	
		if (eregi("<head", $template_line[$xedusvar])) {
			$template_line[$xedusvar] = $template_line[$xedusvar]."\n" . $title_tag_line. $metatag . "\n\n" . $stylesheet . "\n\n<!-- $site_keywords -->\n\n" . $javascript . "\n\n";
		}
	
	
	   ##########################################################################################################
	   ##========================================================================================================
	   ## #VARIABLES# - Detect presence of template variable-features and 'activate' them
	   ##========================================================================================================
	   ##########################################################################################################
	
		/// #INC-filename# - Include specified php script
		###------------------------------------------------------------------------------------###
		if (eregi("<!---#INC-(.*)#-->", $template_line[$xedusvar])) {
	
			$temp = eregi("<!---#INC-(.*)#-->", $template_line[$xedusvar], $out);
			$INCLUDE_FILE = $out[1];
			//echo $INCLUDE_FILE;
			$filename = $template_path."/".$INCLUDE_FILE;
	
			include($filename);
	
			//$template_line[$xedusvar] = eregi_replace("<!---#INC-(.*)#-->", "", $template_line[$xedusvar]);
		}
	
		/// #OUTPUT-filename# - Insert output from specified php include script
		###------------------------------------------------------------------------------------###
		if (eregi("#OUTPUT-", $template_line[$xedusvar])) {
	
			$temp = eregi("#OUTPUT-(.*)#", $template_line[$xedusvar], $out);
			$INCLUDE_FILE = $out[1];
	
			//echo $INCLUDE_FILE; exit;
	
			$filename = $template_path."/".$INCLUDE_FILE;
	
			$output = "";
			ob_start();
				include("$filename");
				$output = ob_get_contents();
			ob_end_clean();
	
	      # Account for commented-out and not commented-out methods
			$template_line[$xedusvar] = eregi_replace("<!---#OUTPUT-(.*)#-->", $output, $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#OUTPUT-(.*)#", $output, $template_line[$xedusvar]);
		}
	
	
		/// pound_variable_rules.php
		###-----------------------------------------------------------------------------------###
		# Allow templates to include their own pound variable rules
		# This file is included up here above the standard pound var rules so that custom rules can preempt/override standard rules (if so desired)
		# Checking for '#' in line to reduce bomb-potential when this file calls functions defined in #INC-filename#
		# ...otherwise #INC-filename#'s would all have to be on first line (otherwise once it hits this in first loop iteration KABOOM undefined function call
		$filename = $template_path."/template_variable_rules.php";
		if ( eregi("#", $template_line[$xedusvar]) && file_exists($filename) ) { include($filename); }
	
	
		/// #TMENU# - Place Text Menu into Template
		###------------------------------------------------------------------------------------###
		if (eregi("#TMENU#", $template_line[$xedusvar])) {
			$tmenu = "";
			if ($textmenu == "on") {
				$tmenu = "<div id=\"smt_tmenu\">[ $main_textmenu ]</div>";
			}
			$tmenu = eregi_replace("\|  ]", "]", $tmenu);
			$template_line[$xedusvar] = eregi_replace("#TMENU#", $tmenu, $template_line[$xedusvar]);
		}
	
	
		/// #CUSTOMPHP# (Filename Must be media/template_include.inc)
		###------------------------------------------------------------------------------------###
		if (eregi("#CUSTOMPHP#", $template_line[$xedusvar])) {
			$custom_include = "";
			$filename = "media/template_include.inc";
			if (file_exists($filename)) {
				ob_start();
				include("$filename");
				$custom_include = ob_get_contents();
				ob_end_clean();
			}
			$template_line[$xedusvar] = eregi_replace("#CUSTOMPHP#", $custom_include, $template_line[$xedusvar]);
		}
	
		/// #CUSTOMPHP2# (Filename Must be media/template_include2.inc)
		###------------------------------------------------------------------------------------###
		if (eregi("#CUSTOMPHP2#", $template_line[$xedusvar])) {
			$custom_include = "";
			$filename = "media/template_include2.inc";
			if (file_exists($filename)) {
				ob_start();
				include("$filename");
				$custom_include = ob_get_contents();
				ob_end_clean();
			}
			$template_line[$xedusvar] = eregi_replace("#CUSTOMPHP2#", $custom_include, $template_line[$xedusvar]);
		}
	
	
		/// #CUSTOMINC# (Filename Must be includethis.inc in template dir)
		###------------------------------------------------------------------------------------###
		if (eregi("#CUSTOMINC#", $template_line[$xedusvar])) {
			$custominc = "";
			$filename = $incfile;
			if (file_exists($filename)) {
				ob_start();
				include("$filename");
				$custominc = ob_get_contents();
				ob_end_clean();
			}
			$template_line[$xedusvar] = eregi_replace("#CUSTOMINC#", $custominc, $template_line[$xedusvar]);
		}
	
		/// #CUSTOMINC2# (Filename Must be includethis2.inc in template dir)
		###------------------------------------------------------------------------------------###
		if (eregi("#CUSTOMINC2#", $template_line[$xedusvar])) {
			$customincB = "";
			$filename = $incfileB;
			if (file_exists($filename)) {
				ob_start();
				include("$filename");
				$customincB = ob_get_contents();
				ob_end_clean();
			}
			$template_line[$xedusvar] = eregi_replace("#CUSTOMINC2#", $customincB, $template_line[$xedusvar]);
		}
	
		/// #CUSTOMINC3# (Filename Must be includethis3.inc in template dir)
		###------------------------------------------------------------------------------------###
		if (eregi("#CUSTOMINC3#", $template_line[$xedusvar])) {
			$customincC = "";
			$filename = $incfileC;
			if (file_exists($filename)) {
				ob_start();
				include("$filename");
				$customincC = ob_get_contents();
				ob_end_clean();
			}
			$template_line[$xedusvar] = eregi_replace("#CUSTOMINC3#", $customincC, $template_line[$xedusvar]);
		}
	
	
		/// #VMENU# - Place Vertical Button Menu into Template
		###------------------------------------------------------------------------------------###
		if (eregi("#VMENU#", $template_line[$xedusvar])) {
			$vertmenu = "";
			if ($mainmenu == "vertical") {
				$vertmenu = $main_buttons;
			} else {
				$vertmenu = $sub_buttons;
			}
	
			$vertmenu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n" . $vertmenu . "\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
			$template_line[$xedusvar] = eregi_replace("#VMENU#", $vertmenu, $template_line[$xedusvar]);
		}
	
		/// #VMAINS# - Place vertical main menu in tamplate
	   ###------------------------------------------------------------------------------------###
		if (eregi("#VMAINS#", $template_line[$xedusvar])) {
			$vmain_menu = $vmainz;
			$vmain_menu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n" . $vmain_menu . "\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
			$template_line[$xedusvar] = eregi_replace("#VMAINS#", $vmain_menu, $template_line[$xedusvar]);
		}
	
		/// #VSUBS# - Place vertical sub menu in tamplate
		###------------------------------------------------------------------------------------###
		if (eregi("#VSUBS#", $template_line[$xedusvar])) {
			$vsub_menu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n";
			if(!$vmenuCheck && $vmenuCheck != 1){
	      	$vsub_menu .= "<script language=javascript>\n\n";
	      	$vsub_menu .= "function navto(where) {\n";

			
			if($seo_option == 'yes'){	
	      		$vsub_menu .= "     window.location = where+'.php';\n";
	      	} else {
	      		$vsub_menu .= "     window.location = 'index.php?pr='+where+'';\n";	
	      	}
	      	$vsub_menu .= "}\n\n";
	      	$vsub_menu .= "function navtoLink(where) {\n";
	      	$vsub_menu .= "     window.location = where\n";
	      	$vsub_menu .= "}\n\n</script>\n\n";
	      }
			$vsub_menu .= $sub_buttons . "\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
			$template_line[$xedusvar] = eregi_replace("#VSUBS#", $vsub_menu, $template_line[$xedusvar]);
		}
	
	
		/// #HMENU# - Place Horizontal Button Menu into Template
		###------------------------------------------------------------------------------------###
	   /* This never turns out right. To be refined and un-commented.
		if (eregi("#HMENU#", $template_line[$xedusvar])) {
			$horizmenu = "";
			if ($mainmenu != "vertical") {
				$horizmenu = $main_buttons;
			}
	
			$horizmenu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n" . $horizmenu . "\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
			$template_line[$xedusvar] = eregi_replace("#HMENU#", $horizmenu, $template_line[$xedusvar]);
		}*/
	
		/// #HMAINS# - Place horizontal sub menu in tamplate
		###------------------------------------------------------------------------------------###
		if (eregi("#HMAINS#", $template_line[$xedusvar])) {
			$hmain_menu = $hmainz;
			$hmain_menu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n" . $hmain_menu . "\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
			$template_line[$xedusvar] = eregi_replace("#HMAINS#", $hmain_menu, $template_line[$xedusvar]);
		}
	
		/// #HSUBS# - Place horizontal sub menu in tamplate
		###------------------------------------------------------------------------------------###
		if (eregi("#HSUBS#", $template_line[$xedusvar])) {
			$hsub_menu = $hsubz;
			$hsub_menu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n" . $hsub_menu . "\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
			$template_line[$xedusvar] = eregi_replace("#HSUBS#", $hsub_menu, $template_line[$xedusvar]);
		}
	
	
		/// ##PHPINCLUDE; - Insert code for php include script
		###------------------------------------------------------------------------------------###
		if (eregi("##PHPINCLUDE;", $template_line[$xedusvar])) {
	
			$temp = eregi("<!-- ##PHPINCLUDE;(.*)## -->", $template_line[$xedusvar], $out);
			$INCLUDE_FILE = $out[1];
	
			$filename = "media/$INCLUDE_FILE";
	
			// Inserted for V5.  Makes it easier to add new objects to object bar in editor
			if (eregi("pgm-", $INCLUDE_FILE)) { $filename = "$INCLUDE_FILE"; }
	
			$output = "";
			ob_start();
				include("$filename");
				$output = ob_get_contents();
			ob_end_clean();
	
			$template_line[$xedusvar] = "\n\n<!-- ~~~~~~~ CUSTOM PHP TEMPLATE OUTPUT ~~~~~~ -->\n\n" . $output . "\n\n<!-- ~~~~~~~~~~~~ END CUSTOM PHP TEMPLATE OUTPUT ~~~~~~~~~~~~ -->\n\n";
		}
	   $template_line[$xedusvar] = eregi_replace("#DOTCOM#", $dot_com, $template_line[$xedusvar]); // Extreme backwards compatibility is the only thing keeping this here.
	
	
		/// #LOGO# - Place Header Text Title/Logo
		###------------------------------------------------------------------------------------###
		if (eregi("#LOGO#", $template_line[$xedusvar])) {
			$logo = "$headertext";
			$template_line[$xedusvar] = eregi_replace("#LOGO#", $logo, $template_line[$xedusvar]);
		}
	
		/// #LOGOIMG# - Place Logo Image into template
		###------------------------------------------------------------------------------------###
		if ( eregi("#LOGOIMG#", $template_line[$xedusvar]) ) {
	
			if ( strlen($getSpec[df_logo]) > 4 ) { // Make sure file name exists
			   $logoFile = "images/".$getSpec[df_logo];
	
	   		if ( file_exists($logoFile) ) { // Make sure file exists
	   		   $logoImg = "<img src=\"".$logoFile."\" border=\"0\">";
	   		} else {
	   		   $logoImg = "&nbsp;";
	   		}
	   	} else {
	   	   $logoImg = "&nbsp;";
	   	}
	
			$template_line[$xedusvar] = eregi_replace("#LOGOIMG#", $logoImg, $template_line[$xedusvar]);
		}
	
		/// #SLOGAN# - Text slogan or motto
		###------------------------------------------------------------------------------------###
		if (eregi("#SLOGAN#", $template_line[$xedusvar])) {
			$slogan = $subheadertext;
			$template_line[$xedusvar] = eregi_replace("#SLOGAN#", $slogan, $template_line[$xedusvar]);
		}
	
	
		/// #PAGENAME# - Current page name (w/o underscores)
		###------------------------------------------------------------------------------------###
		if (eregi("#PAGENAME#", $template_line[$xedusvar])) {
	
			if ( $pr == "" ) {
			   $pound_pagename = startpage(false);
			} else {
			   $pound_pagename = eregi_replace("_", " ", $pr);
			}
	
			$template_line[$xedusvar] = eregi_replace("#PAGENAME#", $pound_pagename, $template_line[$xedusvar]);
		}
	
		/// #PAGETITLE# - Current page title (or name if not available)
		###------------------------------------------------------------------------------------###
		if (eregi("#PAGETITLE#", $template_line[$xedusvar])) {
	
			if ( $page_title == "" ) {
	
	   		if ( $pr == "" ) {
	   		   $pound_pagetitle = "Welcome";
	   		} else {
	   		   $pound_pagetitle = eregi_replace("_", " ", $pr);
	   		}
	
	   	} else {
	   	   $pound_pagetitle = $page_title;
	   	}
	
			$template_line[$xedusvar] = eregi_replace("#PAGETITLE#", $pound_pagetitle, $template_line[$xedusvar]);
		}
	
	
		/// #COPYRIGHT# - Copyright text from 'Global Settings'
		###------------------------------------------------------------------------------------###
		if ( eregi("#COPYRIGHT#", $template_line[$xedusvar]) ) {
	
			if ( $getSpec[copyright] != "" ) {
			   $pound_copyright = "&#169;".$getSpec['copyright'];
			} else {
			   $pound_copyright = "&nbsp;";
			}
	
			$template_line[$xedusvar] = eregi_replace("#COPYRIGHT#", $pound_copyright, $template_line[$xedusvar]);
		}
	
	
		/// #BIZ-AAAAAAA# - Pull company info data from site_specs table
		###------------------------------------------------------------------------------------###
		if (eregi("#BIZ-([0-9a-zA-Z]{1,20})#", $template_line[$xedusvar], $bizVar)) {
		   $rep_field = "df_".$bizVar[1];
		   $rep_field = strtolower($rep_field);
	
			if ( $getSpec[$rep_field] != "" ) {
			   $pound_bizvar = $getSpec[$rep_field];
			} else {
			   $pound_bizvar = "&nbsp;";
			}
	
			$template_line[$xedusvar] = eregi_replace($bizVar[0], $pound_bizvar, $template_line[$xedusvar]);
		}
	
	   // Reg expression above doesn't catch these BIZ vars. (refine for later versions)
	
	   #BIZ-FAX#
	   if ( eregi("#BIZ-FAX#", $template_line[$xedusvar]) ) {
	      if ( $getSpec['df_fax'] != "" ) { $pound_fax = $getSpec['df_fax']; } else { $pound_fax = "&nbsp;"; }
	      $template_line[$xedusvar] = eregi_replace("#BIZ-FAX#", $pound_fax, $template_line[$xedusvar]);
	   }
	
	   #BIZ-PHONE#
	   if ( eregi("#BIZ-PHONE#", $template_line[$xedusvar]) ) {
	      if ( $getSpec[df_phone] != "" ) { $pound_co = $getSpec[df_phone]; } else { $pound_co = "&nbsp;";   }
	      $template_line[$xedusvar] = eregi_replace("#BIZ-PHONE#", $pound_co, $template_line[$xedusvar]);
	   }
	
		// #BIZ-COMPANY#
		if ( eregi("#BIZ-COMPANY#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_company] != "" ) { $pound_co = $getSpec[df_company]; } else { $pound_co = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-COMPANY#", $pound_co, $template_line[$xedusvar]);
		}
	
		// #BIZ-ADDRESS1#
		if ( eregi("#BIZ-ADDRESS1#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_address1] != "" ) { $pound_addr1 = $getSpec[df_address1]; } else { $pound_addr1 = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-ADDRESS1#", $pound_addr1, $template_line[$xedusvar]);
		}
	
		// #BIZ-ADDRESS2#
		if ( eregi("#BIZ-ADDRESS2#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_address2] != "" ) { $pound_addr2 = $getSpec[df_address2]; } else { $pound_addr2 = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-ADDRESS2#", $pound_addr2, $template_line[$xedusvar]);
		}
	
		// #BIZ-ZIP#
		if ( eregi("#BIZ-ZIP#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_zip] != "" ) { $pound_zip = $getSpec[df_zip]; } else { $pound_zip = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-ZIP#", $pound_zip, $template_line[$xedusvar]);
		}
	
		// #BIZ-STATE#
		if ( eregi("#BIZ-STATE#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_state] != "" ) { $pound_state = $getSpec[df_state]; } else { $pound_state = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-STATE#", $pound_state, $template_line[$xedusvar]);
		}
	
		// #BIZ-CITY#
		if ( eregi("#BIZ-CITY#", $template_line[$xedusvar]) ) {
			if ( $getSpec['df_city'] != "" ) { $pound_city = $getSpec['df_city']; } else { $pound_city = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-CITY#", $pound_city, $template_line[$xedusvar]);
		}
	
		// #BIZ-COUNTRY#
		if ( eregi("#BIZ-COUNTRY#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_country] != "" ) { $pound_country = $getSpec[df_country]; } else { $pound_country = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-COUNTRY#", $pound_country, $template_line[$xedusvar]);
		}
	
		// #BIZ-EMAIL#
		if ( eregi("#BIZ-EMAIL#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_email] != "" ) { $pound_email = $getSpec[df_email]; } else { $pound_email = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-EMAIL#", $pound_email, $template_line[$xedusvar]);
		}
	
		// #BIZ-DOMAIN#
		if ( eregi("#BIZ-DOMAIN#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_domain] != "" ) { $pound_dom = $getSpec[df_domain]; } else { $pound_dom = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-DOMAIN#", $pound_dom, $template_line[$xedusvar]);
		}
	
	
	
		/// #AUTODATESTAMP# - Place Automatic Date Stamp into Template
		###------------------------------------------------------------------------------------###
		if (eregi("#AUTODATESTAMP#", $template_line[$xedusvar])) {
			$tmp = date("l, F j, Y");
			$template_line[$xedusvar] = eregi_replace("#AUTODATESTAMP#", $tmp, $template_line[$xedusvar]);
		}
	
	
		/// #NEWSBOX#
		###------------------------------------------------------------------------------------###
		if ( eregi("#NEWSBOX#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#NEWSBOX#", $newsbox, $template_line[$xedusvar]);
		}
	
		/// #NEWSBOX-000# (flexible article snippet)
		###------------------------------------------------------------------------------------###
		if ( eregi("#NEWSBOX-([0-9]{1,3})#", $template_line[$xedusvar], $flex) ) {
		   $repDis = $flex[0];
			$template_line[$xedusvar] = eregi_replace("#NEWSBOX-([0-9]{1,3})#", $newsbox_flex, $template_line[$xedusvar]);
		}
	
		/// #BOX-TITLE1#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX-TITLE1#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX-TITLE1#", $box_title1, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box1."</TEXTAREA>\n";
		}
		/// #BOX-TITLE2#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX-TITLE2#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX-TITLE2#", $box_title2, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box2."</TEXTAREA>\n";
		}
		/// #BOX-TITLE3#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX-TITLE3#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX-TITLE3#", $box_title3, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box3."</TEXTAREA>\n";
		}
		/// #BOX-TITLE4#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX-TITLE4#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX-TITLE4#", $box_title4, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box4."</TEXTAREA>\n";
		}
		/// #BOX-TITLE5#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX-TITLE5#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX-TITLE5#", $box_title5, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box5."</TEXTAREA>\n";
		}
		/// #BOX-TITLE6#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX-TITLE6#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX-TITLE6#", $box_title6, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box6."</TEXTAREA>\n";
		}
		/// #BOX-TITLE7#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX-TITLE7#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX-TITLE7#", $box_title7, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box7."</TEXTAREA>\n";
		}
		/// #BOX-TITLE8#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX-TITLE8#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX-TITLE8#", $box_title8, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box8."</TEXTAREA>\n";
		}
	
	
		/// #BOX1#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX1#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX1#", $box1, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box1."</TEXTAREA>\n";
		}
		/// #BOX2#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX2#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX2#", $box2, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box2."</TEXTAREA>\n";
		}
		/// #BOX3#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX3#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX3#", $box3, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box3."</TEXTAREA>\n";
		}
		/// #BOX4#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX4#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX4#", $box4, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box4."</TEXTAREA>\n";
		}
		/// #BOX5#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX5#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX5#", $box5, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box5."</TEXTAREA>\n";
		}
		/// #BOX6#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX6#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX6#", $box6, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box6."</TEXTAREA>\n";
		}
		/// #BOX7#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX7#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX7#", $box7, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box7."</TEXTAREA>\n";
		}
		/// #BOX8#
		###------------------------------------------------------------------------------------###
		if ( eregi("#BOX8#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#BOX8#", $box8, $template_line[$xedusvar]);
			//echo "<TEXTAREA STYLE='width: 612; height: 225;'>".$box8."</TEXTAREA>\n";
		}
	
		/// #PROMOHDR1#
		###------------------------------------------------------------------------------------###
		if ( eregi("#PROMOHDR1#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#PROMOHDR1#", $promohdr1, $template_line[$xedusvar]);
		}
		/// #PROMOTXT1#
		###------------------------------------------------------------------------------------###
		if ( eregi("#PROMOTXT1#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#PROMOTXT1#", $promotxt1, $template_line[$xedusvar]);
		}
	
		/// #PROMOHDR2#
		###------------------------------------------------------------------------------------###
		if ( eregi("#PROMOHDR2#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#PROMOHDR2#", $promohdr2, $template_line[$xedusvar]);
		}
		/// #PROMOTXT2#
		###------------------------------------------------------------------------------------###
		if ( eregi("#PROMOTXT2#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#PROMOTXT2#", $promotxt2, $template_line[$xedusvar]);
		}
	
		/// #PROMOHDR3#
		###------------------------------------------------------------------------------------###
		if ( eregi("#PROMOHDR3#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#PROMOHDR3#", $promohdr3, $template_line[$xedusvar]);
		}
		/// #PROMOTXT3#
		###------------------------------------------------------------------------------------###
		if ( eregi("#PROMOTXT3#", $template_line[$xedusvar]) ) {
			$template_line[$xedusvar] = eregi_replace("#PROMOTXT3#", $promotxt3, $template_line[$xedusvar]);
		}
	
	
		/// SWF IE-Compatibility issue (Mantis #73)
		###xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx###
		$template_line[$xedusvar] = eregi_replace("<object[^>]*>", "", $template_line[$xedusvar]);
		//$template_line[$xedusvar] = eregi_replace("<object*>", "", $template_line[$xedusvar]);
		//$template_line[$xedusvar] = eregi_replace("</object>", "", $template_line[$xedusvar]);
	
	
	##############################################################################################
	### WHILE $xedusvar LOOP IS STILL IN MOTION; START LOOPING THROUGH CONTENT HTML
	### THAT WAS CREATED FROM THE PAGE EDITOR AND START INSERTING REAL-TIME DATA INTERPRETATION
	### FOR FINAL OUTPUT.
	##############################################################################################
	
	
		if ( eregi("#CONTENT#", $template_line[$xedusvar]) ) {
	
			$switchvar = 1;	// The Content Variable indicates the switch from header to footer
	
	
			// ***************************************************************************************
			// In case of troubleshooting needs, lets place some HTML comment code to indicate where
			// the actual page_content starts that was created by the page editor system
			// ***************************************************************************************
	
			$pagecontent = "\n\n\n\n<!--- \n\n";
			$pagecontent .= "###########################################################################\n";
			$pagecontent .= "### PGM-REALTIME-BUILDER:  START PAGE CONTENT FROM CONTENT EDITOR \n";
			$pagecontent .= "###########################################################################\n\n";
			$pagecontent .= "-->\n\n\n\n";
	
	
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// If we have determined a 404 error for this page (See above content retreival) then
			// place the error display HTML here
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
			if ($error == 404 && $module_active != "yes") { $pagecontent .= $errordisplay; }
	
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// If $module_active is eq "yes", then we have called the builder program from a
			// module, meaning we want to allow the module code to place data in the content
			// area, so we really don't need the content for the "pageRequest", we just need
			// to offer up another #CONTENT# var to the module script.
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
			if ($module_active == "yes") {
	
				if ( $nShow != "" || $bShow != "") {
	   		   // Newsbox link - show article
	   		   $pagecontent .= $disArticle;
	   		} else {
	   		   $pagecontent .= "\n\n#CONTENT#\n\n";
	   		}
	
			} else {
	
	   		##############################################################################################
	   		## START ACTUAL "INNER LOOP" THROUGH CONTENT LINES WITHIN THE $xedusvar LOOP
	   		##############################################################################################
	
	   		for ($sohocontent=0;$sohocontent<=$numlines;$sohocontent++) {
	
	//            # Register unique validator token for custom form display (Mantis 412)
	//   			if (eregi("<!---#UNIQUETOKEN", $content_line[$sohocontent])) {
	//   			   //echo "Original line: <textarea style=\"width: 450px; height: 100px; font: 10px verdana;\">".$content_line[$sohocontent]."</textarea><br>\n";
	//   			   $form_token = eregi_replace("<!---#UNIQUETOKEN~~", "", $content_line[$sohocontent]);
	//   			   $form_token = eregi_replace("~~#-->", "", $form_token);
	//   			   $_SESSION['UNIQUE_TOKEN'] = $form_token;
	//   				$content_line[$sohocontent] = "";
	//   			}
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR AUTOMATIC DATE STAMP
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			if (eregi("<!-- ##PHPDATE## -->", $content_line[$sohocontent])) {
	   				$today = date("F j, Y");
	   				$content_line[$sohocontent] = eregi_replace("<!-- ##PHPDATE## -->", "<font face=Verdana, Arial, Helvetica size=2><B>$today</B></font>", $content_line[$sohocontent]);
	   			}
	
	   			if (eregi("#DATE#", $content_line[$sohocontent])) {
	   				$today = date("F j, Y");
	   				$content_line[$sohocontent] = eregi_replace("#DATE#", "$today", $content_line[$sohocontent]);
	   			}
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR HIT COUNTER CALCULATION AND DISPLAY
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			if (eregi("##COUNTER##", $content_line[$sohocontent])) {
	
	   				$filename = $_SESSION['docroot_path']."/sohoadmin/filebin/hitcounter.txt";
	
	   				if (file_exists("$filename")) {
	
	   					$file = fopen("$filename", "r");
	   						$hitcount = fread($file,filesize($filename));
	   					fclose($file);
	
	   					$hitcount = eregi_replace("\n", "", $hitcount);
	   					$hitcount = chop($hitcount);
	   					$hitcount = ltrim($hitcount);
	   					$hitcount = rtrim($hitcount);
	
	   				} else {
	
	   					$hitcount = "1";
	
	   				}
	
	   				// Build Graphical representation of counter number for display
	
	   				$hit_count_graphic = "";
	
	   				$tmp = strlen($hitcount);	// Get number of digits in number
	   				for ($hc_cnt=0;$hc_cnt<=$tmp;$hc_cnt++) {
	   					$hc_number = substr($hitcount, $hc_cnt, 1);
	   					if ($hc_number != "") { $hit_count_graphic .= "<IMG SRC=\"$hc_number.gif\" width=15 height=20 border=0 align=absmiddle vspace=0 hspace=0 border=0>"; }
	   				}
	
	   				$content_line[$sohocontent] = "<TABLE BORDER=0 CELLPADDING=2 CELLSPACING=0 ALIGN=CENTER><TR><TD ALIGN=CENTER VALIGN=MIDDLE>\n";
	   				$content_line[$sohocontent] .= "$hit_count_graphic\n";
	   				$content_line[$sohocontent] .= "</TD></TR></TABLE>\n";
	
	   				$hitcount++;
	   				$file = fopen("$filename", "w");
	   					fwrite($file, "$hitcount\n");
	   				fclose($file);
	
	   			}
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR REALTIME CALENDAR MODULE
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			if (eregi("##CALENDAR", $content_line[$sohocontent])) {
	
	   				$this_mod = str_replace("<!-- ##", "", $content_line[$sohocontent]);
	   				$this_mod = str_replace("## -->", "", $this_mod);
	
	   				if (eregi(";", $this_mod)) {
	   					$tmp = split(";", $this_mod);
	   					$this_mod = $tmp[0];
	   					$CHANGE_CAT = $tmp[1];
	   				}
	
	   				$this_mod = ltrim($this_mod);
	   				$this_mod = rtrim($this_mod);
	
	   				if ($this_mod == "CALENDAR-WEEKLY-VIEW") {
	   					$filename = "pgm-cal-weekview.php";
	   				}
	
	   				if ($this_mod == "CALENDAR-ONEMONTH-VIEW") {
	   					$filename = "pgm-cal-monthview.php";
	   				}
	
	   				if ($this_mod == "CALENDAR-SYSTEM") {
	   					$filename = "pgm-cal-system.php";
	   				}
	
	   				ob_start();
	   					include("$filename");
	   					$content_line[$sohocontent] = ob_get_contents();
	   				ob_end_clean();
	
	   			}
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// Translate all submit buttons into proper style class
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			$content_line[$sohocontent] = eregi_replace("input type=submit", "input type=submit class=FormLt1", $content_line[$sohocontent]);
	   			$content_line[$sohocontent] = eregi_replace("input type=\"submit\"", "input type=submit class=FormLt1", $content_line[$sohocontent]);
	
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR SINGLE PRODUCT SKU PROMOTION (REAL-TIME UPDATE) Bugzilla #21
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			if (eregi("<!--##SINGLESKU;", $content_line[$sohocontent])) {
	   				$tmp = eregi("<!--##SINGLESKU;(.*)##-->", $content_line[$sohocontent], $out);
	   				$sku_number = $out[1];
	
	   				include("pgm-single_sku.php");	// Added 2003-09-09
	
	   				$content_line[$sohocontent] = $SINGLE_SKU_PROMO_HTML;
	
	   			} // End Sku Promotion
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR PHOTO ALBUM
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			if (eregi("##PHOTO;", $content_line[$sohocontent])) {
	
	   				$temp = eregi("<!-- ##PHOTO;(.*)## -->", $content_line[$sohocontent], $out);
	   				$THIS_ID = $out[1];
	
	   				$filename = "pgm-photo_album-single.php";
	
	   				ob_start();
	   				include("$filename");
	   				$output = ob_get_contents();
	   				ob_end_clean();
	
	   				$content_line[$sohocontent] = "\n\n<!-- ~~~~~~~ PHOTO ALBUM OUTPUT ~~~~~~ -->\n\n" . $output . "\n\n<!-- ~~~~~~~~~~~~ END PHOTO ALBUM OUTPUT ~~~~~~~~~~~~ -->\n\n";
	   			}
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR PHP INCLUDE SCRIPT
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			if (eregi("##MIKEINC;", $content_line[$sohocontent])) {
	
	   				$temp = eregi("<!-- ##MIKEINC;(.*)## -->", $content_line[$sohocontent], $out);
	   				$INCLUDE_FILE = $out[1];
	
	   				// For testing
	   				// echo "<font style=\"font-family: arial; font-size: 11px; color: #d70000;\">This file: <b>$INCLUDE_FILE</b></font>\n"; // TAKE THIS LINE OUT BEFORE WRAPPING!!!
	
	   				$filename = "media/$INCLUDE_FILE";
	
	   				// Inserted for V5.  Makes it easier to add new objects to object bar in editor
	   				if (eregi("pgm-", $INCLUDE_FILE)) { $filename = "$INCLUDE_FILE"; }
	
	   				ob_start();
	   				include("$filename");
	   				$output = ob_get_contents();
	   				ob_end_clean();
	
	   				$content_line[$sohocontent] = "\n\n<!-- ~~~~~~~ CUSTOM PHP OUTPUT ~~~~~~ -->\n\n" . $output . "\n\n<!-- ~~~~~~~~~~~~ END CUSTOM PHP OUTPUT ~~~~~~~~~~~~ -->\n\n";
	   			}
	
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR REAL-TIME FAQ READER
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			if (eregi("##FAQ", $content_line[$sohocontent])) {
	   				$tmp = eregi("<!-- ##FAQ;(.*)## -->", $content_line[$sohocontent], $out);
	   				$FAQ_CATEGORY_NAME = $out[1];
	
	   				$filename = $faq_display_file;
	   				ob_start();
	   					include("$filename");
	   					$content_line[$sohocontent] = ob_get_contents();
	   				ob_end_clean();
	
	   			} // End Blog Display
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR REAL-TIME BLOG READER
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			if ( eregi("##BLOG", $content_line[$sohocontent]) ) {
	   				$tmp = eregi("<!-- ##BLOG;(.*)## -->", $content_line[$sohocontent], $out);
	   				$BLOG_CATEGORY_NAME = $out[1];
	
	   				$filename = $blog_display_file;
	   				ob_start();
	   					include("$filename");
	   					$content_line[$sohocontent] = ob_get_contents();
	   				ob_end_clean();
	
	   			} // End Blog Display
	
	   			# SitePal
	   			include($_SESSION['docroot_path']."/sohoadmin/program/modules/sitepal/page_editor/realtime_builder-html_display.php");
	
	
	            /*---------------------------------------------------------------------------------------------------------*
	             ___
	            | __|___  _ _  _ __
	            | _|/ _ \| '_|| '  \
	            |_| \___/|_|  |_|_|_|
	
	            # Pull web form html and add hidden fields
	            /*---------------------------------------------------------------------------------------------------------*/
	            if ( eregi("##CONTACTFORM", $content_line[$sohocontent]) ) {
	         		$tmp = eregi("<!-- ##CONTACTFORM;(.*)## -->", $content_line[$sohocontent], $out);
	         		$ctemp = $out[1];
	         		$mtemp = split(";", $ctemp);
	
	         		$send_to = $mtemp[0];
	         		$database_file = $mtemp[1];
	         		$formfile = $mtemp[2];
	
	               # Rebuild path to get around missing backslash issue (i.e., from $formfile path) on Windows servers causing form to not appear
	               if ( eregi("WIN|IIS", $_SERVER['SERVER_SOFTWARE']) ) {
	                  $badpath = stripslashes($_SESSION['docroot_path']);
	                  $formfile = eregi_replace($badpath, $_SESSION['docroot_path']."/", $formfile);
	               }
	//               $formfile = "media/".basename($formfile);
	
	         		// =====================================================
	         		// === COMPENSATE FOR NEW "UNHIDDEN" DATA
	         		// =====================================================
	         		$rFrom = $mtemp[3];
	         		$rSubject = $mtemp[4];
	         		$rFile = $mtemp[5];
	         		$rClose = $mtemp[6];
	         		$rPageGo = $mtemp[7];
	         		// =====================================================
	
	         		$content_line[$sohocontent] = "\n\n<!-- \n\n";
	         		$content_line[$sohocontent] .= "###########################################################\n";
	         		$content_line[$sohocontent] .= "### ADD FORM NOW\n";
	         		$content_line[$sohocontent] .= "###########################################################\n\n";
	         		$content_line[$sohocontent] .= "--> \n\n<DIV ALIGN=CENTER>\n\n";
	
	         		$filename = $formfile;	// Modified for IIS and Version 4.5
	
	         		$file = fopen($filename, "r");
	         	   $thisCode = fread($file,filesize($filename));
	         		fclose($file);
	
	         		$formlines = split("\n", $thisCode);
	         		$nFLines = count($formlines);
	
	         		$startup = 0;
	
	         		# Generate unique token (Mantis 414)
	         		$unique_token = md5(time());
	
	         		for ($j=0;$j<=$nFLines;$j++) {
	
	         			$formlines[$j] = ltrim($formlines[$j]);  // Make form spacing even on final HTML output
	         			$formlines[$j] = rtrim($formlines[$j]);  // Make form spacing even on final HTML output
	
	         			if (eregi("<form ", $formlines[$j])) {
	         				$startup = 1;
	         				$formlines[$j] .= "\n\n          <input type=hidden name=EMAILTO value=\"$send_to\">\n";
	         				$formlines[$j] .= "          <input type=hidden name=PAGEREQUEST value=\"".$pr."\">\n";
	         				$formlines[$j] .= "          <input type=hidden name=DATABASE value=\"$database_file\">\n";
	
	         				if ($rClose == "yes") {
	         					$formlines[$j] .= "          <input type=hidden name=SELFCLOSE value=\"yes\">\n";
	         				}
	
	         				$formlines[$j] .= "          <input type=hidden name=PAGEGO value=\"$rPageGo\">\n";
	         				$formlines[$j] .= "          <input type=hidden name=RESPONSEFROM value=\"$rFrom\">\n";
	         				$formlines[$j] .= "          <input type=hidden name=SUBJECTLINE value=\"$rSubject\">\n";
	         				$formlines[$j] .= "          <input type=hidden name=RESPONSEFILE value=\"$rFile\">\n";
	         				$formlines[$j] .= "          <input type=hidden name=CUST_FILENAME value=\"$filename\">\n\n";
	         				$formlines[$j] .= "          <input type=hidden name=\"UNIQUETOKEN\" value=\"".$unique_token."\">\n\n";
	
	         			}
	
	         			$formlines[$j] = "          " . $formlines[$j];	// final HTML output is indented 10 spaces for looks
	
	         			// *****************************************************************************************
	         			// For legacy code, forms where submitted to "email.php3" -- now for open source release,
	         			// all client side runtime scripts have been renamed for clarity when viewing via FTP, etc.
	         			// So, let's make sure that the legacy forms will conform to the new naming conventions.
	         			// ******************************************************************************************
	         			$formlines[$j] = str_replace("email.php3", "pgm-form_submit.php", $formlines[$j]);
	
	         			if ($startup == 1) {
	         				$content_line[$sohocontent] .= $formlines[$j]."\n";
	         			}
	
	         			if (eregi("</form>", $formlines[$j])) {
	         				$startup = 0;
	         			}
	
	         		}
	
	         		$content_line[$sohocontent] .= "\n\n</DIV>\n\n\n";
	
	               # Mantis 412
	               $content_line[$sohocontent] .= "<!---#UNIQUETOKEN~~".$unique_token."~~#--->\n\n";
	
	         		$content_line[$sohocontent] .= "<!--- end form ---> \n\n";
	            } // End if eregi(CONTACTFORM)
	
	   			eval(hook("rtb_contentloop", basename(__FILE__)));
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// INSERT CODE FOR SECURE LOGIN FEATURE
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	
	   			if (eregi("<!-- ##SECURELOGIN;", $content_line[$sohocontent])) {
	   				$tmp = eregi("<!-- ##SECURELOGIN;(.*)## -->", $content_line[$sohocontent], $out);
	   				$BUTTON_NAME = $out[1];
	
	   				if ($OWNER_EMAIL != "" && $OWNER_NAME != "") {
	
	   					$content_line[$sohocontent] = "\n\n<!-- Secure Authentication Login -->\n\n<div align=center>\n";
	   					$content_line[$sohocontent] .= "\n\n\n\n\n<!---$this_ip-->\n\n\n\n\n";
	   					$content_line[$sohocontent] .= "<form method=\"post\" action=\"pgm-secure_manage.php\">\n";
	   					$content_line[$sohocontent] .= "<table border=0 cellpadding=5 cellspacing=0 class=border width=199><tr><td align=center valign=top bgcolor=\"#EFEFEF\">\n";
	   					$content_line[$sohocontent] .= "<input type=submit value=\"".lang("Manage Account")."\" STYLE=\"cursor: hand; font-family: Arial; font-size: 8pt;\"><BR>\n";
	   					$content_line[$sohocontent] .= "<font size=1 face=Arial, Helvetica>&nbsp;<BR><B>Welcome $OWNER_NAME!<br>\n";
	   					$content_line[$sohocontent] .= "<a href=\"$REDIRECT_PAGE\">".lang("Member Area")."</a>\n";
	   					$content_line[$sohocontent] .= "</td></tr></table>\n</form>\n</div>\n\n\n";
	
	   				} else {
	
	   					$content_line[$sohocontent] = "\n\n<!-- Secure Authentication Login -->\n\n<div align=center>\n";
	   					$content_line[$sohocontent] .= "\n\n\n\n\n<!---$this_ip-->\n\n\n\n\n";
	   					$content_line[$sohocontent] .= "<form method=\"post\" action=\"pgm-secure_login.php\">\n";
	   					$content_line[$sohocontent] .= "<table border=0 cellpadding=5 cellspacing=0 class=border width=199><tr><td align=center valign=top bgcolor=\"#EFEFEF\">\n";
	   					$content_line[$sohocontent] .= "<input type=submit value=\"$BUTTON_NAME\" STYLE=\"cursor: hand; font-family: Arial; font-size: 8pt;\"><BR>\n";
	   					$content_line[$sohocontent] .= "<font size=1 face=Arial, Helvetica>&nbsp;<BR>".lang("Forget your password?")." <a href=\"pgm-secure_remember.php\">Click Here.</a>\n";
	   					$content_line[$sohocontent] .= "</td></tr></table>\n</form>\n</div>\n\n\n";
	
	   				}
	
	   			}
	
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   			// Add the current loop through the content_line array to the "$pagecontent" var
	   			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	
	   			$pagecontent .= $content_line[$sohocontent] . "\n";
	
	   		} // END LOOP
	
			##############################################################################################
			### END OF $sohocontent LOOP
			##############################################################################################
	
		   } // END MODULE-ACTIVE = YES IF STATEMENT
	
			$pagecontent .= "\n\n\n\n<!--- \n\n";
			$pagecontent .= "##############################################################################\n";
			$pagecontent .= "### PGM-REALTIME-BUILDER: END DYNAMIC PAGE CONTENT FROM PAGE EDITOR SYSTEM \n";
			$pagecontent .= "##############################################################################\n\n";
			$pagecontent .= "-->\n\n\n\n";
	
			//adsense
			$asense = new userdata("asense");
	
			if($asense->get("id") != "HIDE" && $asense->get("id") != "") {
				$adsense = $asense->get("id");
				$adsense = base64_decode($adsense);
				$pagecontent .= $adsense;
			}
			//end addsense
	
			$template_line[$xedusvar] = eregi_replace("#CONTENT#", $pagecontent, $template_line[$xedusvar]);
	
		} // End if eregi(#CONTENT#) in this LINE
	
	
	##############################################################################################
	### WE HAVE NOW COMPLETED THE "IF '#CONTENT#'" VARIABLE STATEMENT WHILE LOOPING THROUGH
	### THE TEMPLATE HTML CODE.  NOW, LETES MAKE SURE THAT WE ARE PASSING PROPER SESSION ID'S
	### AND DYNAMIC VARIABLE DATA BETWEEN MODULES BY FORCING CORRECT LINKING WITHIN THE CURRENT
	### TEMPLATE LINE. (WHICH CURRENTLY INCLUDES ALL CONTENT JUST INTERPRETED AT THIS POINT).
	##############################################################################################
	
		// ------------------------------------------------------------------------
		// Add current interpreted line data to header or footer vars respectively
		// ------------------------------------------------------------------------
	
		if ($switchvar == 1) {
			$template_footer .= $template_line[$xedusvar] . "\n";
		} else {
			$template_header .= $template_line[$xedusvar] . "\n";
		}
	
	} // End Template Loop
	
	################################################################################################
	### WE HAVE NOW FINISHED PUTTING THE $template_header AND $template_footer VARIABLES
	### TOGETHER AND HAVE COMPLETED BUILDING OUR PAGE DISPLAY HTML!  KOOL HUH?
	#################################################################################################
	
	// By license, you can not modify below this line
	// -------------------------------------------------
	
	if (eregi("xj17b", $com_key)) {
		$template_footer .= "<CENTER><DIV STYLE='width: 300; background: white; border: 1px inset black;'><FONT SIZE=3 FACE=ARIAL><B>Soholaunch Evaluation Website</B></FONT><BR><FONT FACE=ARIAL><a href=\"http://www.soholaunch.com\">Visit Soholaunch</a></DIV>\n\n";
	}
	
	// ----------------------------------------------------------------
	// Look For Number of User Addition and do it now -- 4.5 Addition
	// ---------------------------------------------------------------
	
	$template_on = 1;
	$filename = "sohoadmin/client_files/pgm-numusers.php";
	if (file_exists($filename)) {
		ob_start();
			include("$filename");
			$numUserOpt = ob_get_contents();
		ob_end_clean();
	}
	
	$template_header = eregi_replace("#USERSONLINE#", $numUserOpt, $template_header);
	$template_footer = eregi_replace("#USERSONLINE#", $numUserOpt, $template_footer);
	
	#TEMPLATE_FOLDER# - Replaced with name of current template folder
	$template_header = eregi_replace("#template_folder#", $template_folder, $template_header);
	$template_footer = eregi_replace("#template_folder#", $template_folder, $template_footer);
	
	#template_path_full_url# - Replaced with absolute url path to template folder, accounts for http/https, helps with image src's and such
	$template_header = eregi_replace("#template_path_full_url#", $template_path_full_url, $template_header);
	$template_footer = eregi_replace("#template_path_full_url#", $template_path_full_url, $template_footer);
	
	#template_path# - Replaced with absolute root path to template folder
	$template_path_from_root = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/".basename($template_folder);
	$template_header = eregi_replace("#template_path#", $template_path_from_root, $template_header);
	$template_footer = eregi_replace("#template_path#", $template_path_from_root, $template_footer);
	
	# Pound var name TBD
	$template_path_from_docroot = "sohoadmin/program/modules/site_templates/".$template_folder;
	
	
	/*---------------------------------------------------------------------------------------------------------*
	                        _
	    _  _  ___ ___  _ _ (_) _ __   __ _
	   | || |(_-</ -_)| '_|| || '  \ / _` |
	 ___\_,_|/__/\___||_|  |_||_|_|_|\__, |
	|___|                            |___/
	
	# _userimgX - Special-named images that user can swap out via template manager
	/*---------------------------------------------------------------------------------------------------------*/
	# Pull user images from table
	$qry = "select orig_image, user_image from smt_userimages";
	$qry .= " where template_folder = '".$template_folder."'";
	$qry .= " and layout_file = '".$layout_file."'";
	$qry .= " and user_image != ''";
	$userimg_rez = mysql_query($qry);
	$userimgs_defined = mysql_num_rows($userimg_rez);
	if ( $userimgs_defined > 0 && (strpos($template_header, "_userimg") !== false || strpos($template_footer, "_userimg") !== false) ) {
	   while ( $getImg = mysql_fetch_assoc($userimg_rez) ) {
	      $template_header = eregi_replace($template_path_from_docroot."/".$getImg['orig_image'], "images/".$getImg['user_image'], $template_header);
	      $template_footer = eregi_replace($template_path_from_docroot."/".$getImg['orig_image'], "images/".$getImg['user_image'], $template_footer);
	   }
	}
	
	
	$template_on = 0;
	$template_header = eregi_replace("<body", "<body onkeydown=\"mouse_capture( event );\" ", $template_header);
	# UDT_CONTENT_SEARCH_REPLACE - Pull Global Search and Replace Vars and process now
	$tResult = mysql_query("SELECT * FROM UDT_CONTENT_SEARCH_REPLACE");
	while ($srRow = mysql_fetch_array($tResult)) {
		$repString = $srRow[REPLACE_WITH];
		if ($srRow[AUTO_IMAGE] != "NULL") { $repString = "<img src=\"images/$srRow[AUTO_IMAGE]\" align=absmiddle border=0>"; }
		if (strlen($srRow[SEARCH_FOR]) > 3) {
			$template_header = eregi_replace($srRow[SEARCH_FOR], $repString, $template_header);
			$template_footer = eregi_replace($srRow[SEARCH_FOR], $repString, $template_footer);
		}
	} // End While
	
	$template_header = eregi_replace("SOHOLINK=", "href=", $template_header);
	$template_footer = eregi_replace("SOHOLINK=", "href=", $template_footer);
	
	if($seo_option == 'yes'){     
		$template_header = str_replace("window.location = 'index.php?pr='+where+'';", "window.location = where+'.php&rmtemplate=".$_GET['rmtemplate']."'+'';", $template_header);
	} else {
	     $template_header = str_replace("window.location = 'index.php?pr='+where+'';", "window.location = 'index.php?pr='+where+'&rmtemplate=".$_GET['rmtemplate']."'+'';", $template_header);
	}
	
	
	$template_footer = str_replace("window.location = where", "window.location = where+'&rmtemplate=".$_GET['rmtemplate']."'", $template_footer);
	
	
	$template_header = eregi_replace($_SESSION['this_ip']."'", $_SESSION['this_ip']."/index.php'", $template_header);
	$template_footer = eregi_replace($_SESSION['this_ip']."'", $_SESSION['this_ip']."/index.php'", $template_footer);

	$template_header = eregi_replace($_SESSION['this_ip']."\"", $_SESSION['this_ip']."/index.php\"", $template_header);
	$template_footer = eregi_replace($_SESSION['this_ip']."\"", $_SESSION['this_ip']."/index.php\"", $template_footer);
	

	
	$template_header = eregi_replace("\index.php\?", "index.php?&", $template_header);
	$template_footer  = eregi_replace("\index.php\?", "index.php?&", $template_footer );
	
	$template_header = eregi_replace("\index.php", "index.php?", $template_header);
	$template_footer  = eregi_replace("\index.php", "index.php?", $template_footer );

	$template_header = eregi_replace("\index.php\?\?", "index.php?", $template_header);
	$template_footer  = eregi_replace("\index.php\?\?", "index.php?", $template_footer );
	
	$template_header = eregi_replace("\index.php\?", "index.php?rmtemplate=".$_GET['rmtemplate'], $template_header);
	$template_footer  = eregi_replace("\index.php\?", "index.php?rmtemplate=".$_GET['rmtemplate'], $template_footer );
	
//	$template_header = eregi_replace("\.php[^\?]", ".php?rmtemplate=".$_GET['rmtemplate'], $template_header);
//	$template_footer  = eregi_replace("\.php[^\?]", ".php?rmtemplate=".$_GET['rmtemplate'], $template_footer );
	
	# Allow templates to replace things in the fully-compiled content right before it gets displayed
	if ( file_exists($template_path_from_root."/content_replacements.php") ) {
	   include($template_path_from_root."/content_replacements.php");
	}


?>