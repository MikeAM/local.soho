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
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
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

########################################################################
### TEMPLATE BUILDER SOHOLAUNCH MGT. TOOL
### -------------------------------------------------------
### NOTE: THIS FILE IS USED FOR MODULES ONLY.  IT WILL NOT FUNCTION
### FOR LITE VERSION OR NORMAL PAGES
###
### VERY IMPORTANT DEVELOPER NOTE: WHEN ADDING OR MODIFING THIS SCRIPT,
### DO NOT USE COMMON VARIABLE NAMES SUCH AS $x, $y, ETC.  THE REASON
### IS THAT THE APPLICATION ALLOWS CUSTOM PHP INCLUDES TO BE EXECUTED
### IN REAL-TIME FROM THE APP INTERFACE.  THEREFORE, IF AN INCLUDE USES
### THESE VARIABLES FOR EXECUTION, IT WILL CAUSE THIS SCRIPT TO CRASH
### BECAUSE THE VARIABLES WILL CONFLICT WITH EACH OTHER!
########################################################################
# Used mainly by client-side display elements when building url paths for absolute links
if ( !function_exists("isHttps") ) {
	function isHttps() {
	   if ( $_SERVER['https'] == "on" || $_SERVER['HTTPS'] == "on" ) {
	      return "https://";
	   } else {
	      return "http://";
	   }
	}
}
session_start();

$globalprefObj = new userdata('global');

if($_GET['rmtemplate'] != '' && $_SESSION['PHP_AUTH_USER'] != '' && $_SESSION['PHP_AUTH_PW'] != ''){
	$_SESSION['rmtemplate'] = $_GET['rmtemplate'];
}
if($_SESSION['rmtemplate'] != '' && $_SESSION['PHP_AUTH_USER'] != '' && $_SESSION['PHP_AUTH_PW'] != ''){
	$_GET['rmtemplate'] = $_SESSION['rmtemplate'];
	$_GET['SHOPPING'] = 'YES';
	chdir('../');
	//include_once('..sohoadmin/program/includes/shared_functions.php');
	include_once('sohoadmin/program/includes/remote_browse.php');
	$template_header = eregi_replace("\"index\.php\?", "\"../index.php?", $template_header);
	$template_footer  = eregi_replace("\"index\.php\?", "\"../index.php?", $template_footer );
////
//	$template_header = eregi_replace("\.php", ".php?rmtemplate=".$_GET['rmtemplate'], $template_header);
//	$template_footer  = eregi_replace("\.php", ".php?rmtemplate=".$_GET['rmtemplate'], $template_footer );

} else {


	if ( strlen($lang["New Customer"]) < 4 || count($getSpec) < 1 ) {

	   // ----------------------------------------------------
	   // Configure System Variables for Language Version
	   // ----------------------------------------------------

	   // Register default settings from site_specs table
	   // ------------------------------------------------
	   $selSpecs = mysql_query("SELECT * FROM site_specs");
	   $getSpec = mysql_fetch_array($selSpecs);

	   if ( $getSpec[df_lang] == "" ) {
	      $language = "english.php";
	      //echo "getSpec[df_lang] = ($getSpec[df_lang])\n";
	      //exit;

	   } else {
	      $language = $getSpec[df_lang];
	   }

	   if ( $lang_dir != "" ) {
	      $lang_include = $lang_dir."/".$language;
	   } else {
	      $lang_include = "../sohoadmin/language/$language";
	   }

	   include ("$lang_include");

	   session_register("lang");
	   session_register("language");
	   session_register("getSpec");

// Commented out because causing some problems with SEO indexing...let's see if the session data problems come back
//	   $ggstring = '';
//	   foreach($_GET as $gs=>$go){
//	   	if($gs != 'SID' && $gs != 'PHPSESSID' && $gs != 'sid'){
//		   	if($ggstring == ''){
//		   		$ggstring .= "?".$gs."=".$go;
//		   	} else {
//					$ggstring .= "&".$gs."=".$go;
//				}
//			}
//		}
//	  
//	  $refreshme = $_SERVER['PHP_SELF'].$ggstring;
//	   header("location:$refreshme");

	}

	// ----------------------------------------------------------------------
	// Create sterilize phone number function.
	// This function makes sure that all phone numbers entered into input
	// boxes are formated the same way each time (pretty cool).
	// ----------------------------------------------------------------------

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

	########################################################################
	### DEFINE REAL-TIME VARIABLE DATA NEEDED
	########################################################################

	$thisYear = date("Y");

	########################################################################
	### DEFINE BUILT-IN JAVASCRIPT AND STYLE SHEET DATA
	### All custom HTML inserts and/or templates can utilize these built-in
	### Javascript functions.  Do not remove these because all modules such
	### as the shopping cart, etc. utilize these as a shortcut.
	########################################################################
	$javascript = "\n\n<script src=\"../sohoadmin/client_files/site_javascript.php\" type=\"text/javascript\"></script>\n\n";
	$javascript .= "\n\n<script language=Javascript>\n<!--\n\n";

	$javascript .= "function killErrors() {\n";
	$javascript .= "     return true; \n";
	$javascript .= "}\n";
	$javascript .= "window.onerror = killErrors;\n\n";

	$javascript .= "function MM_reloadPage(init) {  // reloads the window if Nav4 resized\n";
	$javascript .= "     if (init==true) with (navigator) {if ((appName==\"Netscape\")&&(parseInt(appVersion)==4)) {\n";
	$javascript .= "          document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}\n";
	$javascript .= "     else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();\n";
	$javascript .= "}\n";
	$javascript .= "MM_reloadPage(true);\n\n";

	$javascript .= "function MM_swapImgRestore() { //v3.0\n";
	$javascript .= "     var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;\n";
	$javascript .= "}\n\n";
	$javascript .= "function MM_preloadImages() { //v3.0\n";
	$javascript .= "     var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();\n";
	$javascript .= "     var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)\n";
	$javascript .= "     if (a[i].indexOf(\"#\")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}\n";
	$javascript .= "}\n\n";
	$javascript .= "function MM_findObj(n, d) { //v3.0\n";
	$javascript .= "     var p,i,x;  if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {\n";
	$javascript .= "     d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}\n";
	$javascript .= "     if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];\n";
	$javascript .= "     for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;\n";
	$javascript .= "}\n\n";
	$javascript .= "function MM_swapImage() { //v3.0\n";
	$javascript .= "     var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)\n";
	$javascript .= "     if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}\n";
	$javascript .= "}\n\n";
	$javascript .= "function MM_openBrWindow(theURL,winName,features) { //v2.0\n";
	$javascript .= "     window.open(theURL,winName,features);\n";
	$javascript .= "}\n\n";
	$javascript .= "if (document.styleSheets) { // Fix Netscape Style Sheet Problems\n";
	$javascript .= "     if (document.styleSheets.length > 0) {\n";
	$javascript .= "	        var siteStyles = document.styleSheets[0];\n";
	$javascript .= " 	        siteStyles.addRule(\"cinput\", \"font-size:9pt; height:18px; width:100px;\");\n";
	$javascript .= "     }\n";
	$javascript .= "}\n\n";

	########################################################################
	### SOME MODULES RETURN VARIABLE FLAGS THAT CONFIRM TO THE END USER
	### THAT AN ACTION HAS BEEN COMPLETED.  THESE INSERTS ARE CREATED
	### IN JAVASCRIPT, REAL-TIME, BASED ON VARIABLES PAST BACK FROM THE
	### VARIOUS MODULES.  DO NOT REMOVE THESE FOR PROPER PERFORMANCE.
	########################################################################

	if ($emailsent == 1) {
		$javascript .= "alert(\"Your message has been sent.  Thank you\");\n\n";
	}

	if ($epagesent == 1) {
	   $javascript .= "alert(\"This page has been emailed to your friend! Thank you!\");\n\n";
	}

	########################################################################
	### END JAVASCRIPT INSERTION
	########################################################################

	$javascript .= "-->\n</script>\n\n";

	########################################################################
	### DEFINE BASE STYLESHEET FOR ALL PAGES GENERATED TO UTILIZE. THIS
	### CAN BE MODIFIED FOR TEMPLATES TO USE AS WELL ON A SITE BY SITE BASIS
	########################################################################

	$stylesheet = "\n\n<LINK rel=\"stylesheet\" href=\"../runtime.css\" type=\"text/css\">\n";

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

		$site_description = "$SERVER_NAME";
		$site_keywords = "$SERVER_NAME, Application, Software, Thin-Client";
	}

	if ( $globalprefObj->get('utf8') == 'on' ) {
		$metatag = "<META http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	} else {
		$metatag = "<META http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n";	
	}

	
	$metatag .= "<META name=\"resource-type\" content=\"document\">\n";
	$metatag .= "<META name=\"description\" content=\"$site_description\">\n";
	$metatag .= "<META name=\"keywords\" content=\"$site_keywords\">\n";
	//$metatag .= "<META name=\"distribution\" content=\"global\">\n";
	$metatag .= "<META name=\"copyright\" content=\"(c) $SERVER_NAME $thisYear.  All rights reserved.\">\n";


   # Auto-detect and link favicon.ico 
	if($globalprefObj->get('site_favicon') !='' && file_exists('../'.$globalprefObj->get('site_favicon'))){
		$fav_ext = explode('.', $globalprefObj->get('site_favicon'));
		$fav_ext_num = count($fav_ext) - 1;
		$fav_ext_type = strtolower($fav_ext[$fav_ext_num]);
		if($fav_ext_type == 'png'){
			$metatag .= "<link rel=\"icon\" type=\"image/png\" href=\"../".$globalprefObj->get('site_favicon')."\">\n";	
		} elseif($fav_ext_type == 'ico'){
			if(preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])){
				$metatag .= "<link rel=\"shortcut icon\" href=\"../".$globalprefObj->get('site_favicon')."\" />\n";
			} else {
				$metatag .= "<link rel=\"icon\" type=\"image/ico\" href=\"../".$globalprefObj->get('site_favicon')."\">\n";	
			}			
		} elseif($fav_ext_type == 'gif'){
			$metatag .= "<link rel=\"icon\" type=\"image/gif\" href=\"../".$globalprefObj->get('site_favicon')."\">\n";	
		} elseif($fav_ext_type == 'jpg' || $fav_ext_type == 'jpeg'){
			$metatag .= "<link rel=\"icon\" type=\"image/jpeg\" href=\"../".$globalprefObj->get('site_favicon')."\">\n";	
		}				
	} else {
		if(file_exists('../favicon.ico')){
			$metatag .= "<link rel=\"shortcut icon\" href=\"../favicon.ico\" />\n";
		}	
	}

	#############################################################################
	### PULL HEADER INFORMATION FOR #LOGO# TEMPLATE VAR
	### This only applies if using a built-in template that has a #LOGO# var
	#############################################################################
	$filename = "$cgi_bin/logo.conf";

	if ( $getSpec[df_hdrtxt] != "" ) {
	   # Try to pull from db
	   $headertext = $getSpec[df_hdrtxt];
	   $subheadertext = $getSpec[df_slogan];

	} elseif (file_exists("$logoconf")) {
	   # Try to pull from config file
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
	   # Create default coming soon
	   $tmp = $getSpec[df_company];
	   $headertext = "";
	   $subheadertext = "";
	}


	########################################################################
	### THE PAGE REQUEST WAS SENT TO US VIA $pageRequest OR $pr DEPENDING
	### ON THE MODULE ACCESSING THE index.php FILE.  LET'S GET THAT INFO
	### FROM OUR site_pages DATABASE TABLE.  IF NO PAGE REQUEST DATA WAS
	### SENT TO THE SCRIPT, WE ARE ASSUMING THIS IS A FIRST TIME HIT AND WE
	### WILL RETURN THE HOME PAGE BY DEFAULT.
	########################################################################

	// STEP 1: Make Sure All First Letters Are Upper Case In Var and Spaces are eliminated
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	$pageRequest = eregi_replace("_", " ", $pageRequest);
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

	if (!file_exists("$filename")) {	// This page must have been called by the PriKey

		$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE link = '$pageRequest'");

		while ($row = mysql_fetch_array ($result)) {

			$pageRequest = $row["page_name"];

			if ($module_active != "yes") {
				$security_code = $row["username"];	// Does this page require Authentication?
			} else {
				$security_code = "";
			}

			$page_template = $row["password"];
			$splashpage = $row["splash"];
			$splash_bg = $row["bgcolor"];
		}

		$pageRequest = eregi_replace(" ", "_", $pageRequest);

	} else {

		$thisPage = eregi_replace("_", " ", $pageRequest);

		$result = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages WHERE page_name = '$thisPage'");

		while ($row = mysql_fetch_array ($result)) {

			$pageRequest = $row["page_name"];

			if ($module_active != "yes") {
				$security_code = $row["username"];	// Does this page require Authentication?
			} else {
				$security_code = "";
			}

			$page_template = $row["password"];
			$splashpage = $row["splash"];
			$splash_bg = $row[bgcolor];
			$page_title = $row[title];

		}

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

		$errordisplay = "<table border=0 cellpadding=10 cellspacing=0 width=500><tr><td align=center valign=top>\n";
		$errordisplay .= "<img src=\"../sohoadmin/client_files/under_construction.gif\"  width=273 height=74  border=0></td></tr></table><BR>&nbsp;<BR>\n";

	} else {					// Page does exist; get the content HTML

		$file = fopen("$filename", "r");
			$body = fread($file,filesize($filename));
		fclose($file);

		$content_line = split("\n", $body);	// We have just placed the content HTML into the $content_line Array.
		$numlines = count($content_line);	// $numlines is now equal to the number of lines in the content HTML

	}


	##############################################################################
	### SETUP BASE TEMPLATE HTML AS ASSIGNED IN "Site Template(s)" MENU OPTION.
	### NOW THAT WE HAVE THE CONTENT HTML PLACED INTO THE $content_line
	### ARRAY, LET'S GET THE TEMPLATE HTML AND PLACE IT INTO AN ARRAY AS
	### WELL SO THAT WE CAN PROCESS ALL THE HTML THROUGH THE "BUILD INTERPRETER"
	##############################################################################


	// Read Template Config File
	//$filename = "../sohoadmin/tmp_content/template.conf";

	// 2004-08-01: Create and select default template if none specified (fixes blank screen problem)
	$default_template = "Professional-Cutting_Edge-blue"; // Dedicated to Mark Reedy ;-)
//
//	if ( !file_exists($filename) ) {
//	   $file = fopen("$filename", "w");
//	   	fwrite($file, "$default_template");
////	   fclose($file);
////	   chmod($filename, 0755);
////	}
//
//	$file = fopen("$filename", "r");
//		$what_template = fread($file,filesize($filename));
//	fclose($file);
	$what_template = $globalprefObj->get('site_base_template');
	$base_template = $what_template;	// In case of individual page definitions

	//echo "base_template = ($base_template)<br>\n"; exit;

	$single_template_change = 0;


	// Does this page have a specific template specified for it? [CUSTOM TEMPLATE]
	// --------------------------------------------------------------------------------	
	$what_template = $globalprefObj->get('shopping_cart_base_template');
	if($what_template==''){
		$what_template = $globalprefObj->get('site_base_template');
		if($what_template==''){
			$what_template	= $default_template;
		}
	}
	$Shopping_Cart = $what_template;

	$single_template_change = 1;

	// Determine the directory where we will find our template HTML and open it; parse image data and move on
	$stock_dir = "../sohoadmin/program/modules/site_templates/pages/";

	if (eregi("tCustom", $what_template)) {
		// This is a custom template.
		$template_dir = "../tCustom/";
		$CustomFlag = 1;
		$filename = $what_template;
		$automenu = "../pgm-auto_menu.php"; // Use standard auto-menu

	} else {
		$template_dir = "../template/index.html";
		if ($single_template_change == 1) {
			$template_dir = "../sohoadmin/program/modules/site_templates/pages/";

			// Allow for custom cart templates
			$cTemplate = $template_dir.$what_template."/cart.html";
			if ( file_exists($cTemplate) ) {
				 $promoFile = "cart";
				 $layout_file = "cart.html";
			   $template = $what_template."/cart.html";
			} else {
				 $promoFile = "index";
				 $layout_file = "index.html";
			   $template = $what_template."/index.html";
			}

	   	// Let individual templates use their own stylesheet
	   	$cStyle = $template_dir.$what_template."/cart.css";
	   	$tStyle = $template_dir.$what_template."/custom.css";

	   	if ( file_exists($cStyle) ) {
	   	   $stylesheet = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$cStyle."\">";
	   	} elseif ( file_exists($tStyle) ) {
	   	   $stylesheet = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$tStyle."\">";
	   	}


	   	// Let individual templates use their own pgm-auto_menu.php
	   	// -------------------------------------------------------------
	   	$cMenu = $template_dir.$what_template."/pgm-auto_menu.php";
	   	$custommenu = 'no';
	   	if ( file_exists($cMenu) ) { $automenu = $cMenu; $custommenu='yes'; } else { $automenu = "../pgm-auto_menu.php"; } // Use normal auto_menu if no custom one is found

	   	eval(hook("pgm-template_builder.php:after_custom_auto_menu_check"));

	   	// Let individual templates use their own pgm-promo_boxes.php -- MM v4.81
	   	// ==============================================================================
	   	$cPbox = $template_dir.$what_template."/pgm-promo_boxes.php";
	   	if ( file_exists($cPbox) ) { $prnewsbox = $cPbox; } else { $prnewsbox = "../pgm-promo_boxes.php"; } // Use normal promo_boxes if no custom one is found


	   	// Let individual templates use their own includes
	   	// ---------------------------------------------------
	   	$cInc = $template_dir.$what_template."/includethis.inc";
	   	if ( file_exists($cInc) ) { $incfile = $cInc; }

	   	$cIncB = $template_dir.$what_template."/includethis2.inc";
	   	if ( file_exists($cIncB) ) { $incfileB = $cIncB; }

	   	$cIncC = $template_dir.$what_template."/includethis3.inc";
	   	if ( file_exists($cIncC) ) { $incfileC = $cIncC; }

		}

		$CustomFlag = 0;
		$filename = $template_dir.$template;
	}

	# Stick entire path to template folder in one var (vs. doing $template_dir.$filename over and over)
	$template_path = dirname($filename);
	$template_path_full_url = isHttps().$_SESSION['docroot_url']."/".str_replace($_SESSION['docroot_path'], "", $template_path);
	$template_path_full_url = str_replace("../sohoadmin", "sohoadmin", $template_path_full_url);
	$template_folder = $what_template;


	// Read actual template HTML into memory
	$file = fopen("$filename", "r");
		$tbody = fread($file,filesize($filename));
	fclose($file);

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

	//	if ($CustomFlag == 1) {
	//		$template_line[$xedusvar] = eregi_replace("amp;", "", $template_line[$xedusvar]);
	//
	//		// 2004-08-01: Added checks for absolute paths
	//		if ( eregi("src=\"", $template_line[$xedusvar]) && !eregi("src=\"http:", $template_line[$xedusvar]) ) {
	//		   $template_line[$xedusvar] = eregi_replace("src=\"", "src=\"../images/", $template_line[$xedusvar]);
	//		}
	//
	//		if ( eregi("background=\"", $template_line[$xedusvar]) && !eregi("background=\"http:", $template_line[$xedusvar]) ) {
	//		   $template_line[$xedusvar] = eregi_replace("background=\"", "background=\"../images/", $template_line[$xedusvar]);
	//		}
	//
	//	} else {	// Change Image Directory for template regardless; sometimes Windows servers screw up and can't copy files correctly!
	//
	//		if ( eregi("src=\"", $template_line[$xedusvar]) && !eregi("src=\"http:", $template_line[$xedusvar]) ) {
	//		   $template_line[$xedusvar] = eregi_replace("src=\"", "src=\"$stock_dir".$what_template."/", $template_line[$xedusvar]);
	//		}
	//
	//		if ( eregi("background=\"", $template_line[$xedusvar]) && !eregi("background=\"http:", $template_line[$xedusvar]) ) {
	//		   $template_line[$xedusvar] = eregi_replace("background=\"", "background=\"$stock_dir".$what_template."/", $template_line[$xedusvar]);
	//		}
	//
	//	}
		if ($CustomFlag == 1) {
			$template_line[$xedusvar] = eregi_replace("amp;", "", $template_line[$xedusvar]);

			// 2004-08-01: Added checks for absolute paths
			if ( eregi("src=\"", $template_line[$xedusvar]) && !eregi("src=\"http:", $template_line[$xedusvar]) && !eregi("src=\"https:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("src=\"", "src=\"../images/", $template_line[$xedusvar]);
			}

			if ( eregi("background=\"", $template_line[$xedusvar]) && !eregi("background=\"http:", $template_line[$xedusvar]) && !eregi("background=\"https:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("background=\"", "background=\"../images/", $template_line[$xedusvar]);
			}


		} else {	// Change Image Directory for template regardless; sometimes Windows servers screw up and can't copy files correctly!

	//		echo $stock_dir.$what_template;
	//
			if ( eregi("src=\"", $template_line[$xedusvar]) && !eregi("src=\"http:", $template_line[$xedusvar]) && !eregi("src=\"https:", $template_line[$xedusvar]) ) {

			   $template_line[$xedusvar] = eregi_replace("src=\"", "src=\"".$stock_dir.$what_template."/", $template_line[$xedusvar]);
			}

			if ( eregi("background=\"", $template_line[$xedusvar]) && !eregi("background=\"http:", $template_line[$xedusvar]) && !eregi("background=\"https:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("background=\"", "background=\"".$stock_dir.$what_template."/", $template_line[$xedusvar]);
			}

			if ( eregi("background-image: url(", $template_line[$xedusvar]) && !eregi("background-image: url(http:", $template_line[$xedusvar]) && !eregi("background-image: url(https:", $template_line[$xedusvar]) ) {
			   $template_line[$xedusvar] = eregi_replace("background-image: url(", "background-image: url(".$stock_dir.$what_template."/", $template_line[$xedusvar]);
			}

		}


	} // End For Loop

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	// STEP 2: Finally, none of the template data matters if this page has been classified
	// as a splash page from the page properties settings.
	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if ($splashpage == "y" && $module_active != "yes") {

		if ($splash_bg == "") { $splash_bg = "#".$splash_bg; } else { $splash_bg = "white"; }	// In case no setting exists

		// *********************************************************************
		// Open runtime.css to see if .bg has been placed inside
		// style sheet call (this can control a BODY style for the splash page).
		// *********************************************************************

		$filename = "../runtime.css";

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
			$classify = "bgcolor=$splash_bg";

		}

		$site_title = stripslashes($site_title);

		// *********************************************************************
		// CREATE THE SPLASH PAGE TEMPLATE HTML (VERY SIMPLE AND DIRECT)
		// *********************************************************************

		$template_line[0] = "<HTML>";
		$template_line[1] = "<HEAD>";
		$template_line[2] = "<TITLE>$site_title</TITLE>\n";
		$template_line[3] = "\n<!-- SPLASH PAGE -->\n\n";
		$template_line[4] = "</HEAD>\n";
		$template_line[5] = "<body marginheight=0 marginwidth=0 topmargin=0 leftmargin=0 $classify>\n";

		if ($centertag == "yes") {
			$template_line[5] .= "<CENTER>\n";
		}

		$template_line[6] = "#CONTENT#";

		$template_line[7] = "</body></html>\n";

		$numtlines = 7;

	}

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
	/// -----------------------------------------
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
		if (eregi("#VMAINS#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#HSUBS#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#VSUBS#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; }
		if (eregi("#CUSTOMPHP#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; } // Allows custom scripts to output VMENU var
		if (eregi("#CUSTOMPHP2#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; } // Allows custom scripts to output VMENU var
		if (eregi("#CUSTOMINC#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; } // Allows custom scripts to output VMENU var
		if (eregi("#CUSTOMINC2#", $template_line[$menu_chk])) { $auto_menu_on = "yes"; } // Allows custom scripts to output VMENU var

		// Check for promo and nwes variables
		// ---------------------------------
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

	//echo $prnewsbox."<br>";
	if ($auto_menu_on == "yes") { include($automenu); } // Include auto-menu script to build menu vars
		#############
		## New Cart Category Display
		$cartcats_q = mysql_query("select keyfield, category from cart_category where level='1'");
		while($cartcatz = mysql_fetch_assoc($cartcats_q)){
			
			$vmainz = str_replace('cartid:'.$cartcatz['keyfield'].':', $cartcatz['category'], $vmainz);
			$hmainz = str_replace('cartid:'.$cartcatz['keyfield'].':', $cartcatz['category'], $hmainz);
			$main_buttons = str_replace('cartid:'.$cartcatz['keyfield'].':', $cartcatz['category'], $main_buttons);
			$main_textmenu = str_replace('cartid:'.$cartcatz['keyfield'].':', $cartcatz['category'], $main_textmenu);
			$hsubz = str_replace('cartid:'.$cartcatz['keyfield'].':', $cartcatz['category'], $hsubz);
			$sub_buttons = str_replace('cartid:'.$cartcatz['keyfield'].':', $cartcatz['category'], $sub_buttons);
			$sub_textmenu = str_replace('cartid:'.$cartcatz['keyfield'].':', $cartcatz['category'], $sub_textmenu);
		
		
		}
		
		$vmainz = str_replace('http://shopping/start.php', 'start.php', $vmainz);
		$hmainz = str_replace('http://shopping/start.php', 'start.php', $hmainz);
		$main_buttons = str_replace('http://shopping/start.php', 'start.php', $main_buttons);
		$main_textmenu = str_replace('http://shopping/start.php', 'start.php', $main_textmenu);
		$hsubz = str_replace('http://shopping/start.php', 'start.php', $hsubz);
		$sub_buttons = str_replace('http://shopping/start.php', 'start.php', $sub_buttons);
		$sub_textmenu = str_replace('http://shopping/start.php', 'start.php', $sub_textmenu);
		
		#############
	
	// Check S.E.O. friend page links option
	if($custommenu == 'yes') {
		$seol = new userdata("seolink");
		if($seol->get("pref") == "yes") {
			$pageq = mysql_query('SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages order by page_name DESC');
			while($page_names_ar = mysql_fetch_assoc($pageq)){
				if( !eregi("http://", $page_names_ar['link']) && !eregi("https://", $page_names_ar['link']) && !eregi("mailto", $page_names_ar['link'])){
					$this_page_name = str_replace(' ', '_', $page_names_ar['page_name']);
					$this_page_name_repl = '../'.urlencode($this_page_name);
		
					$vmainz = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $vmainz);
					$hmainz = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $hmainz);
					$main_buttons = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $main_buttons);
						 
					$main_buttons = str_replace('window.location = \'index.php?pr=\'+where+\'&=SID\';', 'window.location = where+\'.php\';', $main_buttons);
					$main_buttons = str_replace('window.location = \'index.php?pr=\'+where+\'\';', 'window.location = where+\'.php\';', $main_buttons);
					
					$main_textmenu = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $main_textmenu);
					$hsubz = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $hsubz);
					$sub_buttons = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $sub_buttons);
					$sub_textmenu = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $sub_textmenu);
			
				}
			}
		}
	}
//	// Check S.E.O. friend page links option
//	if($custommenu == 'yes') {
//		$seol = new userdata("seolink");
//		if($seol->get("pref") == "yes") {
//			$pageq = mysql_query('select prikey, page_name, url_name, link from site_pages');
//			while($page_names_ar = mysql_fetch_assoc($pageq)){
//				if( !eregi("http://", $page_names_ar['link']) && !eregi("https://", $page_names_ar['link']) && !eregi("mailto", $page_names_ar['link'])){
//					$this_page_name = str_replace(' ', '_', $page_names_ar['page_name']);
//					$this_page_name_repl = '../'.urlencode($this_page_name);
//	
//					$vmainz = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $vmainz);
//					$hmainz = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $hmainz);
//					$main_buttons = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $main_buttons);
//	
//					$main_buttons = str_replace('window.location = \'index.php?pr=\'+where+\'&=SID\';', 'window.location = \'../\'+where+\'.php\';', $main_buttons);
//					$main_buttons = str_replace('window.location = \'index.php?pr=\'+where+\'\';', 'window.location = \'../\'+where+\'.php\';', $main_buttons);
//	
//					$main_textmenu = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $main_textmenu);
//					$hsubz = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $hsubz);
//					$sub_buttons = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $sub_buttons);
//					$sub_textmenu = str_replace('index.php?pr='.$this_page_name, $this_page_name_repl.'.php', $sub_textmenu);
//	
//				}
//			}
//		}
//	}
	$coptions = mysql_query('select PAYMENT_SSL from cart_options');
	while($copt = mysql_fetch_assoc($coptions)){
		if(strlen($copt['PAYMENT_SSL']) > 5){
			$main_buttons = str_replace('window.location = \'../\'+where+\'.php\';', 'window.location = \'http://'.$_SESSION['this_ip'].'/\'+where+\'.php\';', $main_buttons);
			$sub_buttons = str_replace('window.location = \'../\'+where+\'.php\';', 'window.location = \'http://'.$_SESSION['this_ip'].'/\'+where+\'.php\';', $sub_buttons);

				$vmainz = str_replace('href="../', 'href="http://'.$_SESSION['this_ip'].'/', $vmainz);
				$hmainz = str_replace('href="../', 'href="http://'.$_SESSION['this_ip'].'/', $hmainz);

				$main_textmenu = str_replace('href="../', 'href="http://'.$_SESSION['this_ip'].'/', $main_textmenu);
				$hsubz = str_replace('href="../', 'href="http://'.$_SESSION['this_ip'].'/', $hsubz);
				$sub_buttons = str_replace('href="../', 'href="http://'.$_SESSION['this_ip'].'/', $sub_buttons);
				$sub_textmenu = str_replace('href="../', 'href="http://'.$_SESSION['this_ip'].'/', $sub_textmenu);
				$main_buttons = str_replace('href="../', 'href="http://'.$_SESSION['this_ip'].'/', $main_buttons);

		}
	}

	if ($boxCheck != "" || $nShow != "" ) { include("$prnewsbox"); } // Include promo and newsbox script to build vars


	#######################################################################################
	### DEFINE template_header AND template_footer variable TO SEND BACK
	### TO index.php. THIS IS WHERE WE START WORKING ON THE DYNAMIC ELEMENTS
	### OF THE CONTENT AND TEMPALTE HTML, INSERTING DATA WHERE NEEDED AND
	### MODIFING THE HTML FOR FINAL OUTPUT
	#######################################################################################

	$switchvar = 0;			// This will determine when we switch from header to footer

	$template_header = "";		// Start header blank
	$template_footer = "";		// Start footer blank


	// ******************************************************************
	// Start "xedusvar" loop through template HTML code now (outer loop)
	// ******************************************************************

	for ($xedusvar=0;$xedusvar<=$numtlines;$xedusvar++) {

	   eval(hook("pgm-template_builder.php:template_loop"));

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Parse Outdated Variables.  These could be used if you wish to add other variables
		// to the template for interpretation.  Simply replace these with the HTML code that
		// you wish to put in place of the "variable" call.
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		$template_line[$xedusvar] = eregi_replace("#poweredby#", "", $template_line[$xedusvar]);
		$template_line[$xedusvar] = eregi_replace("#cart#", "", $template_line[$xedusvar]);

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Place current year in place of #YEAR# variable (Added June 2002)
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		$TROT = date("Y");
		$template_line[$xedusvar] = eregi_replace("#YEAR#", "$TROT", $template_line[$xedusvar]);

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Replace all "input type submit" tags with the proper style sheet class defined
		// in the runtime.css file.
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		$template_line[$xedusvar] = eregi_replace("input type=submit", "input type=submit class=FormLt1", $template_line[$xedusvar]);
		$template_line[$xedusvar] = eregi_replace("input type=\"submit\"", "input type=submit class=FormLt1", $template_line[$xedusvar]);

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// If Not a Custom Template, Make Sure all Template Images are pulled from Template
		// since they will be moved there when base template is selected.
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		if ($CustomFlag == 0) {
			$template_line[$xedusvar] = eregi_replace("img src=\"", "img src=\"../template/", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("background=\"", "background=\"../template/", $template_line[$xedusvar]);
		}

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Insert User Defined Page Title and Meta Tags into display of HTML
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

      // Updated to match realtime_builder 6-24-08
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

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// Insert Javascript and Style Sheet info defined in the beginning of this script
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

      // Updated to match realtime_builder 6-24-08
		if (eregi("<head", $template_line[$xedusvar])) {
			$template_line[$xedusvar] = $template_line[$xedusvar]."\n" . $title_tag_line. $metatag . "\n\n" . $stylesheet . "\n\n" . $javascript . "\n\n";
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
		###------------------------------------------------------------------------------------###
		# Allow templates to include their own pound variable rules
		# This file is included up here above the standard pound var rules so that custom rules can preempt/override standard rules (if so desired)
		# Checking for '#' in line to reduce bomb-potential when this file calls functions defined in #INC-filename#
		# ...otherwise #INC-filename#'s would all have to be on first line (otherwise once it hits this in first loop iteration KABOOM undefined function call
		$filename = $template_path."/template_variable_rules.php";
		if ( eregi("#", $template_line[$xedusvar]) && file_exists($filename) ) { include($filename); }


		#TEMPLATE_FOLDER# - Replaced with name of current template folder, helps with custom scripts
		if (eregi("#TEMPLATE_FOLDER#", $template_line[$xedusvar])) {
			$template_line[$xedusvar] = eregi_replace("#TEMPLATE_FOLDER#", $template_folder, $template_line[$xedusvar]);
		}

		#TEMPLATE_PATH# - Replaced with path from docroot to current template folder, helps with custom scripts
		if (eregi("#TEMPLATE_PATH#", $template_line[$xedusvar])) {
			$template_line[$xedusvar] = eregi_replace("#TEMPLATE_PATH#", $template_path, $template_line[$xedusvar]);
		}


		/// #TMENU# - Place Text Menu into Template
		###------------------------------------------------------------------------------------###
		if (eregi("#TMENU#", $template_line[$xedusvar])) {
			$tmenu = "";
			if ($textmenu == "on") {
				$tmenu = "<div align=center><font size=1 face=Arial, Helvetica, Sans-Serif><BR><B>[ $main_textmenu ]<BR></B></font></div>";
			}
			$tmenu = eregi_replace("\|  ]", "]", $tmenu);
			$template_line[$xedusvar] = eregi_replace("#TMENU#", $tmenu, $template_line[$xedusvar]);
		}

		/// #CUSTOMPHP# (Filename Must be media/template_include.inc)
		###------------------------------------------------------------------------------------###
		if (eregi("#CUSTOMPHP#", $template_line[$xedusvar])) {
			$custom_include = "";
			$filename = "../media/template_include.inc";
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
			$filename = "../media/template_include2.inc";
			if (file_exists($filename)) {
				ob_start();
				include("$filename");
				$custom_include = ob_get_contents();
				ob_end_clean();
			}
			$template_line[$xedusvar] = eregi_replace("#CUSTOMPHP2#", $custom_include, $template_line[$xedusvar]);
		}

		/// #supersearch# - Insert searchbox for Site Search plugin
		###------------------------------------------------------------------------------------###
		if (eregi("#supersearch#", $template_line[$xedusvar])) {
		   ob_start();
		   include("../sohoadmin/plugins/super_search/search_box_include.php");
		   $searchHTML = ob_get_contents();
		   ob_end_clean();
			$searchHTML = eregi_replace('search.php', '../search.php', $searchHTML);

		   $template_line[$xedusvar] = eregi_replace("#supersearch#", $searchHTML, $template_line[$xedusvar]);
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
			$vsub_menu = $sub_buttons;
			$vsub_menu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n" . $vsub_menu . "\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
			$template_line[$xedusvar] = eregi_replace("#VSUBS#", $vsub_menu, $template_line[$xedusvar]);
		}

		/// #HMENU# - Place Horizontal Button Menu into Template
		###------------------------------------------------------------------------------------###
	   /* This never turns out right. To be refined (i.e. w/javascript & dhtml) and un-commented.
	   	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	   	// Place Horizontal Button Menu into Template
	   	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	   	if (eregi("#HMENU#", $template_line[$xedusvar])) {
	   		$horizmenu = "";
	   		if ($mainmenu != "vertical") {
	   			$horizmenu = $main_buttons;
	   		}

	   		$horizmenu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n" . $horizmenu . "\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
	   		$template_line[$xedusvar] = eregi_replace("#HMENU#", $horizmenu, $template_line[$xedusvar]);
	   	}
	   */

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


		/// #LOGO# - Place Header Text Title/Logo
		###------------------------------------------------------------------------------------###
		if (eregi("#LOGO#", $template_line[$xedusvar])) {
			$logo = "$headertext";
			$template_line[$xedusvar] = eregi_replace("#LOGO#", $logo, $template_line[$xedusvar]); // Mantis #0000009
		}
		$template_line[$xedusvar] = eregi_replace("#DOTCOM#", $dot_com, $template_line[$xedusvar]);

		/// #LOGOIMG# - Place Logo Image into template
		###------------------------------------------------------------------------------------###
		if ( eregi("#LOGOIMG#", $template_line[$xedusvar]) ) {
			$logoFile = "../images/".$getSpec['df_logo'];

			if ( file_exists($logoFile) && strlen($getSpec['df_logo']) > 4  ) {
			   $logoImg = "<img src=\"../images/".$getSpec['df_logo']."\" border=\"0\">";
			} else {
			   $logoImg = "&nbsp;";
			}

			$template_line[$xedusvar] = eregi_replace("#LOGOIMG#", $logoImg, $template_line[$xedusvar]);
		}

		/// #SLOGAN# - Text slogan or motto
		###------------------------------------------------------------------------------------###
		if (eregi("#SLOGAN#", $template_line[$xedusvar])) {
			$slogan = $getSpec['df_slogan'];
			$slogan = html_entity_decode($slogan);
			$template_line[$xedusvar] = eregi_replace("#SLOGAN#", $slogan, $template_line[$xedusvar]);
		}

		/// #PAGENAME# - Current page name (w/o underscores)
		###------------------------------------------------------------------------------------###
		if (eregi("#PAGENAME#", $template_line[$xedusvar])) {

			$template_line[$xedusvar] = eregi_replace("#PAGENAME#", "", $template_line[$xedusvar]);

		}


		// #PAGETITLE# - Current page title (will return blank here within module)
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		if (eregi("#PAGETITLE#", $template_line[$xedusvar])) {

			$template_line[$xedusvar] = eregi_replace("#PAGETITLE#", "", $template_line[$xedusvar]);
		}


		// #COPYRIGHT# - Copyright text from 'Global Settings'
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
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
		   //echo "rep_fields -- > [".$rep_field."] | ";
		   //echo "rep field = ($rep_field)!!??";
		   //exit;

			if ( $getSpec[$rep_field] != "" ) {
			   $pound_bizvar = $getSpec[$rep_field];
			} else {
			   $pound_bizvar = "&nbsp;";
			}

			$template_line[$xedusvar] = eregi_replace($bizVar[0], $pound_bizvar, $template_line[$xedusvar]);
		}

	   #BIZ-PHONE#
	   if ( eregi("#BIZ-PHONE#", $template_line[$xedusvar]) ) {
	      if ( $getSpec['df_phone'] != "" ) { $pound_co = $getSpec['df_phone']; } else { $pound_co = "&nbsp;";   }
	      $template_line[$xedusvar] = eregi_replace("#BIZ-PHONE#", $pound_co, $template_line[$xedusvar]);
	   }

		// #BIZ-ADDRESS1#
		if ( eregi("#BIZ-ADDRESS1#", $template_line[$xedusvar]) ) {
			if ( $getSpec['df_address1'] != "" ) { $pound_addr1 = $getSpec['df_address1']; } else { $pound_addr1 = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-ADDRESS1#", $pound_addr1, $template_line[$xedusvar]);
		}

	   #BIZ-FAX#
	   if ( eregi("#BIZ-FAX#", $template_line[$xedusvar]) ) {
	      if ( $getSpec['df_fax'] != "" ) { $pound_fax = $getSpec['df_fax']; } else { $pound_fax = "&nbsp;"; }
	      $template_line[$xedusvar] = eregi_replace("#BIZ-FAX#", $pound_fax, $template_line[$xedusvar]);
	   }

		/// #BIZ-ADDRESS2# - Pull address 2 field from site_specs table
		###------------------------------------------------------------------------------------###
		if ( eregi("#BIZ-ADDRESS2#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_address2] != "" ) { $pound_addr2 = $getSpec[df_address2]; } else { $pound_addr2 = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-ADDRESS2#", $pound_addr2, $template_line[$xedusvar]);
		}

		/// #BIZ-STATE# - Pull state field from site_specs table
		###------------------------------------------------------------------------------------###
		if ( eregi("#BIZ-STATE#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_state] != "" ) { $pound_state = $getSpec[df_state]; } else { $pound_state = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-STATE#", $pound_state, $template_line[$xedusvar]);
		}

		/// #BIZ-CITY# - Pull state field from site_specs table
		###------------------------------------------------------------------------------------###
		if ( eregi("#BIZ-CITY#", $template_line[$xedusvar]) ) {
			if ( $getSpec['df_city'] != "" ) { $pound_city = $getSpec['df_city']; } else { $pound_city = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-CITY#", $pound_city, $template_line[$xedusvar]);
		}

		/// #BIZ-ZIP# - Pull state field from site_specs table
		###------------------------------------------------------------------------------------###
		if ( eregi("#BIZ-ZIP#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_zip] != "" ) { $pound_zip = $getSpec[df_zip]; } else { $pound_zip = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-ZIP#", $pound_zip, $template_line[$xedusvar]);
		}

		// #BIZ-DOMAIN#
		if ( eregi("#BIZ-DOMAIN#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_domain] != "" ) { $pound_dom = $getSpec[df_domain]; } else { $pound_dom = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-DOMAIN#", $pound_dom, $template_line[$xedusvar]);
		}

		// #BIZ-COMPANY#
		if ( eregi("#BIZ-COMPANY#", $template_line[$xedusvar]) ) {
			if ( $getSpec[df_company] != "" ) { $pound_co = $getSpec[df_company]; } else { $pound_co = "&nbsp;";	}
			$template_line[$xedusvar] = eregi_replace("#BIZ-COMPANY#", $pound_co, $template_line[$xedusvar]);
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


	##############################################################################################
	### WHILE $xedusvar LOOP IS STILL IN MOTION; START LOOPING THROUGH CONTENT HTML
	### THAT WAS CREATED FROM THE PAGE EDITOR AND START INSERTING REAL-TIME DATA INTERPRETATION
	### FOR FINAL OUTPUT.
	##############################################################################################


		if (eregi("#CONTENT#", $template_line[$xedusvar])) {

			$switchvar = 1;	// The Content Variable indicates the switch from header to footer


			// ***************************************************************************************
			// In case of troubleshooting needs, lets place some HTML comment code to indicate where
			// the actual page_content starts that was created by the page editor system
			// ***************************************************************************************

			$pagecontent = "\n\n\n\n<!-- \n\n";
			$pagecontent .= "###########################################################################\n";
			$pagecontent .= "### PGM-REALTIME-BUILDER ==> START PAGE CONTENT FROM CONTENT EDITOR \n";
			$pagecontent .= "###########################################################################\n\n";
			$pagecontent .= "-->\n\n\n\n";

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// $module_active is eq "yes", so we have called the builder program from a
			// module, meaning we want to allow the module code to place data in the content
			// area, so we really don't need the content for the "pageRequest", we just need
			// to offer up another #CONTENT# var to the module script. -- This particular builder
			// program is designed to work with modules only.
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			# TESTING: How easily could we detect what module is calling this script?
			//$pagecontent .= "Current Module: [".dirname($_SERVER['PHP_SELF'])."]<br/>";
			$pagecontent .= "<div id=\"".str_replace("/", "", dirname($_SERVER['PHP_SELF']))."_module\">\n";
			$pagecontent .= "\n\n#CONTENT#\n\n";
			$pagecontent .= "</div>\n";


			if($globalprefObj->get('goog_trans_website')=='on'){
				$pagecontent .= display_google_translate();
			}


			if($_SERVER['HTTPS'] != "on"){				
				$GOOGLE_CODE = $globalprefObj->get('google_analytics_non');	
			}else{
				$GOOGLE_CODE = $globalprefObj->get('google_analytics_secure');
			}
			
			if(isset($GOOGLE_CODE)){
			   $GOOGLE_CODES = "\n\n<!-- ADD GOOGLE ANALYTICS CODE NOW -->\n\n";
			   $GOOGLE_CODES .= $GOOGLE_CODE;
			   $GOOGLE_CODES .= "\n<!-- END GOOGLE ANALYTICS CODE -->\n\n";
			   $pagecontent .= $GOOGLE_CODES;
			}

			$pagecontent .= "\n\n\n\n<!-- \n\n";
			$pagecontent .= "##############################################################################\n";
			$pagecontent .= "### PGM-REALTIME-BUILDER ==> END DYNAMIC PAGE CONTENT FROM PAGE EDITOR SYSTEM \n";
			$pagecontent .= "##############################################################################\n\n";
			$pagecontent .= "-->\n\n\n\n";

			$template_line[$xedusvar] = eregi_replace("#CONTENT#", $pagecontent, $template_line[$xedusvar]);

		}

		##############################################################################################
		### WE HAVE NOW COMPLETED THE "IF '#CONTENT#'" VARIABLE STATEMENT WHILE LOOPING THROUGH
		### THE TEMPLATE HTML CODE.  NOW, LETES MAKE SURE THAT WE ARE PASSING PROPER SESSION ID'S
		### AND DYNAMIC VARIABLE DATA BETWEEN MODULES BY FORCING CORRECT LINKING WITHIN THE CURRENT
		### TEMPLATE LINE. (WHICH CURRENTLY INCLUDES ALL CONTENT JUST INTERPRETED AT THIS POINT).
		##############################################################################################

		$template_line[$xedusvar] = eregi_replace("index.php\?", "../index.php?", $template_line[$xedusvar]);

		// ------------------------------------------------------------------------
		// Add current interpreted line data to header or footer vars respectively
		// ------------------------------------------------------------------------

		if ($switchvar == 1) {
			$template_footer .= $template_line[$xedusvar] . "\n";
		} else {
			$template_header .= $template_line[$xedusvar] . "\n";
		}

	} // End Template Loop

	// July 1 2003 - Fix Javascript Rollover Image Links in Templates
	$template_footer = eregi_replace("\"images", "\"../images", $template_footer);
	$template_header = eregi_replace("\"images", "\"../images", $template_header);


	// ----------------------------------------------------------------
	// Look For Number of User Addition and do it now -- 4.5 Addition
	// ----------------------------------------------------------------

	$template_on = 1;
	$filename = "../sohoadmin/client_files/pgm-numusers.php";
	if (file_exists($filename)) {
		ob_start();
			include("$filename");
			$numUserOpt = ob_get_contents();
		ob_end_clean();
	}

	$template_header = eregi_replace("#USERSONLINE#", $numUserOpt, $template_header);
	$template_footer = eregi_replace("#USERSONLINE#", $numUserOpt, $template_footer);

	#template_path_full_url# - Replaced with absolute url path to template folder, accounts for http/https, helps with image src's and such
	$template_header = eregi_replace("#template_path_full_url#", $template_path_full_url, $template_header);
	$template_footer = eregi_replace("#template_path_full_url#", $template_path_full_url, $template_footer);

	# Pound var name TBD
	$template_path_from_docroot = "sohoadmin/program/modules/site_templates/pages/".$template_folder;

	/*---------------------------------------------------------------------------------------------------------*
	                        _
	    _  _  ___ ___  _ _ (_) _ __   __ _
	   | || |(_-</ -_)| '_|| || '  \ / _` |
	 ___\_,_|/__/\___||_|  |_||_|_|_|\__, |
	|___|                            |___/

	# _userimgX - Special-named images that user can swap out via template manager
	/*---------------------------------------------------------------------------------------------------------*/
	if ( $layout_file == "" ) { $layout_file = "index.html"; }
	
	# Pull user images from table
	$qry = "select orig_image, user_image from smt_userimages";
	$qry .= " where template_folder = '".$template_folder."'";
	$qry .= " and layout_file = '".$layout_file."'";
	$qry .= " and user_image != ''";
	$userimg_rez = mysql_query($qry);
	$userimgs_defined = mysql_num_rows($userimg_rez);
	if ( $userimgs_defined > 0 && (strpos($template_header, "_userimg") !== false || strpos($template_footer, "_userimg") !== false) ) {
	   while ( $getImg = mysql_fetch_assoc($userimg_rez) ) {
		 if($getImg['user_image'] == 'sohoadmin/program/spacer.gif'){
	      	$template_header = str_replace($template_path_from_docroot."/".$getImg['orig_image'], "".$getImg['user_image'], $template_header);
	      	$template_footer = str_replace($template_path_from_docroot."/".$getImg['orig_image'], "".$getImg['user_image'], $template_footer);
		} else {
	      	$template_header = eregi_replace($template_path_from_docroot."/".$getImg['orig_image'], "images/".$getImg['user_image'], $template_header);
	      	$template_footer = eregi_replace($template_path_from_docroot."/".$getImg['orig_image'], "images/".$getImg['user_image'], $template_footer);
		}
	   }
	}

	$template_on = 0;

	$template_header = eregi_replace("<body", "<body onkeydown=\"mouse_capture();\" ", $template_header);

	# Pull css rules for cart system
	include_once($_SESSION['docroot_path']."/sohoadmin/client_files/shopping_cart/pgm-shopping_css.inc.php"); // Defines $module_css
	$template_footer = $module_css.$template_footer;


	#################################################################################################
	### WE HAVE NOW FINISHED PUTTING THE $template_header AND $template_footer VARIABLES
	### TOGETHER AND HAVE COMPLETED BUILDING OUR PAGE DISPLAY HTML!  KOOL HUH?
	#################################################################################################
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
	

	if ( $globalprefObj->get('utf8') == 'on' ) {
		$template_header = eregi_replace('iso-8859-1', 'utf-8', $template_header);
		$template_footer = eregi_replace('iso-8859-1', 'utf-8', $template_footer);
	}
   
   # Add stuff to final html
   eval(hook("pgm-template_builder.php:add-to-final-html"));		

}
?>