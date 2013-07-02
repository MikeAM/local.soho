<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.6
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

error_reporting(E_PARSE);
session_cache_limiter('none'); 
session_start();

include("../../../includes/product_gui.php");

#############################################################################################

if ($t == "" && $p == "") {
	echo "ERROR";
	exit;
}

if (eregi("\[CUSTOM\]", $t)) {
	$tmp = split("~~~", $t);
	$TEMPLATE_NAME = "$doc_root/tCustom/$tmp[1]";
} else {
	$TEMPLATE_NAME = "../../site_templates/pages/".$t."/index.html";
}


$PAGE_NAME = $p;
$PROMO_MENU = "<table><tr><td class=menusys><a href=\"http://$this_ip\" class=menusys>".$lang["Visit our Website"]."</a></td></tr></table>";

########################################################################
### GET HTML NEWSLETTER CONTENT AND TEMPLATE
########################################################################
		
$tplate_name = "$TEMPLATE_NAME";
if ( $tplate_file = fopen("$tplate_name", "r") ) {
   $TEMPLATE_BODY = fread($tplate_file,filesize($tplate_name));
   fclose($tplate_file);
} else {
   echo "Could not open template file - $tplate_file!";
   exit;
}
				
// Create relative link paths for "preview" generation
if (!eregi("\[CUSTOM\]", $t)) {
	$TEMPLATE_BODY = eregi_replace("src=\"", "src=\"http://$this_ip/sohoadmin/program/modules/site_templates/pages/$t/", $TEMPLATE_BODY);
	$TEMPLATE_BODY = eregi_replace("background=\"", "background=\"http://$this_ip/sohoadmin/program/modules/site_templates/pages/$t/", $TEMPLATE_BODY);
} else {
	$TEMPLATE_BODY = eregi_replace("src=\"", "src=\"http://$this_ip/images/", $TEMPLATE_BODY);
	$TEMPLATE_BODY = eregi_replace("background=\"", "background=\"http://$this_ip/images/", $TEMPLATE_BODY);
}
	
// Remove Auto-Menu Calls for Newsletter Campaign
$TEMPLATE_BODY = eregi_replace("#VMENU#", $PROMO_MENU, $TEMPLATE_BODY);
$TEMPLATE_BODY = eregi_replace("#TMENU#", "", $TEMPLATE_BODY);						

// Title Template as email subject line
$TEMPLATE_BODY = eregi_replace("#LOGO#", $title, $TEMPLATE_BODY);
	
########################################################################
### DEFINE CONTENT AREA (IF ANY)
########################################################################								

if ($PAGE_NAME == "NONE") {				// This is a custom template only HTML newsletter						

	$HTML_CONTENT = $TEMPLATE_BODY;
	$HTML_CONTENT = eregi_replace("#CONTENT#", "", $HTML_CONTENT);
		
	$THIS_HTML = eregi_replace("href=\"media/", "href=\"http://$this_ip/media/", $HTML_CONTENT);
	$THIS_HTML = eregi_replace("runtime.css", "http://$this_ip/runtime.css", $THIS_HTML);
	$THIS_HTML = eregi_replace("shopping/", "http://$this_ip/shopping/", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-secure", "http://$this_ip/pgm-secure", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-form", "http://$this_ip/pgm-form", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("pgm-download", "http://$this_ip/pgm-download", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-email", "http://$this_ip/pgm-email", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-print", "http://$this_ip/pgm-print", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-view", "http://$this_ip/pgm-view", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("pgm-print", "http://$this_ip/pgm-print", $THIS_HTML);
	$THIS_HTML = eregi_replace("pgm-view_video", "http://$this_ip/pgm-view_video", $THIS_HTML);
			
	$THIS_HTML = eregi_replace("VALUE=\"media/", "VALUE=\"http://$this_ip/media/", $THIS_HTML);
	$THIS_HTML = eregi_replace("index.php", "http://$this_ip/index.php", $THIS_HTML);
			
	$THIS_HTML = eregi_replace("src=\"images", "src=\"http://$this_ip/images", $THIS_HTML);
	$THIS_HTML = eregi_replace("background=\"images", "background=\"http://$this_ip/images", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("spacer.gif", "http://$this_ip/spacer.gif", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("http://$this_ip/http://$this_ip/", "http://$this_ip/", $THIS_HTML);	// Text Editor Work around for images

} else {

	$tmp = split("/", $TEMPLATE_NAME);
	$tmpc = count($tmp) - 1;
	$this_template = $tmp[$tmpc];
	$new_template_name = eregi_replace('/'.$this_template.'$', '', $TEMPLATE_NAME);
	$this_page = eregi_replace(" ", "_", $PAGE_NAME);
	
	
	$filename = "http://$this_ip/".pagename($this_page, "&")."nft=$new_template_name";
	$THIS_HTML = include_r("$filename");
	
	$this_title = strtoupper($SERVER_NAME);
	$THIS_HTML = eregi_replace("#TITLE#", "$this_title", $THIS_HTML);
	$THIS_HTML = eregi_replace("#UNSUBSCRIBE#", "news?=unsubscribe", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("background=\"sohoadmin", "background=\"http://$this_ip/sohoadmin", $THIS_HTML);
	$THIS_HTML = eregi_replace("src=\"sohoadmin", "src=\"http://$this_ip/sohoadmin", $THIS_HTML);
	$THIS_HTML = eregi_replace("src=\"images", "src=\"http://$this_ip/images", $THIS_HTML);
	$THIS_HTML = eregi_replace("<a href=\"pgm-email", "<a href=\"http://$this_ip/pgm-email", $THIS_HTML);
	$THIS_HTML = eregi_replace("href=\"index.php", "href=\"http://$this_ip/index.php", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("href\=\"index\.php", "href=\"http://$this_ip/index.php", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("<img src\=\"sohoadmin", "<img src=\"http://".$this_ip."/sohoadmin", $THIS_HTML);
	$THIS_HTML = eregi_replace("src\=\"sohoadmin", "src=\"http://".$this_ip."/sohoadmin", $THIS_HTML);
	$THIS_HTML = eregi_replace("background\=\"sohoadmin", "background=\"http://".$this_ip."/sohoadmin", $THIS_HTML);
	
	$THIS_HTML = eregi_replace("src\=\"images", "src=\"http://$this_ip/images", $THIS_HTML);
	$THIS_HTML = eregi_replace("background\=\"images", "background=\"http://$this_ip/images", $THIS_HTML);
	$THIS_HTML = eregi_replace("http://$this_ip/http://$this_ip/", "http://$this_ip/", $THIS_HTML); // Text Editor Work around for images
	
	$THIS_HTML = eregi_replace("pgm\-download", "http://$this_ip/pgm-download", $THIS_HTML);
	$THIS_HTML = eregi_replace("href\=\"media/", "href=\"http://$this_ip/media/", $THIS_HTML);
	$THIS_HTML = str_replace("href=\"sohoadmin", "href=\"http://".$this_ip."/sohoadmin", $THIS_HTML);
	$THIS_HTML = eregi_replace("src\=\"download_icon\.gif", "src=\"http://$this_ip/sohoadmin/program/modules/page_editor/client/download_icon.gif", $THIS_HTML);
	$THIS_HTML = eregi_replace("http://".$this_ip."http://".$this_ip, "http://".$this_ip, $THIS_HTML);
	
	$THIS_HTML = eregi_replace("\"shopping/", "\"http://$this_ip/shopping/", $THIS_HTML);
	$THIS_HTML = eregi_replace('(title|alt|mce_src)\=("|\'| )[^"\' ]*("|\'| )', '', $THIS_HTML);
	
} // End Custom / Tool Generation Check



echo $THIS_HTML; 

?>