<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

//echo "somethig"; exit;
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

error_reporting(0);
session_cache_limiter('public'); 
session_start();

include("../../../includes/product_gui.php");

#############################################################################################

$result = mysql_query("SELECT HTML_CONTENT, TEXT_CONTENT FROM campaign_manager WHERE PRIKEY = '$v'");
$row = mysql_fetch_array($result);


$JOEZ = mysql_query("SELECT * FROM campaign_manager WHERE PRIKEY = '$id'");
$IMGZ = mysql_fetch_array($JOEZ);


#############################################################################################

	echo "<STYLE>\n";
	echo "a.prevsubmnu:link {color: white; text-decoration: none;}\n";
	echo "a.prevsubmnu:visited {color: white; text-decoration: none;}\n";
	echo "a.prevsubmnu:hover {color: yellow; text-decoration: none;}\n";
	echo "</STYLE>\n\n";
	
	echo "<DIV ID=PREVLAYER style=\"position:absolute; visibility:visible; left:0px; top:0; width:100%; height:8%; z-index:500; overflow: hidden; border: 1px none #000000\">\n";

	echo "<TABLE BORDER=0 CELLPADDING=9 width=100% CELLSPACING=0 ALIGN=CENTER STYLE='border: 2px dashed black; background: #336699;'>\n";
	echo "<tr><td align=center valign=top><font STYLE='font-family: Arial; font-size: 8pt;'><B>\n";
	echo "[ <A HREF=\"preview.php?v=$v&vtype=HTML\" class=prevsubmnu>".$lang["View HTML Preview"]."</a> ] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	echo "[ <A HREF=\"preview.php?v=$v&vtype=TEXT\" class=prevsubmnu>".$lang["View TEXT Preview"]."</a> ] &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
	echo "[ <A HREF=\"#\" onclick=\"javascript: self.close();\" class=prevsubmnu>".$lang["Close Preview Window"]."</a> ]\n";
	echo "</TD></TR></TABLE></DIV><BR>\n\n";
	
#############################################################################################

if ($vtype == "") { $vtype = "HTML"; }

#############################################################################################

if ($vtype == "HTML") {

	$THIS_HTML = $row['HTML_CONTENT'];
	
	$THIS_HTML = eregi_replace("href\=\"index\.php", "href=\"http://$this_ip/index.php", $THIS_HTML);

	$THIS_HTML = eregi_replace("<img src\=\"sohoadmin", "<img src=\"http://".$this_ip."/sohoadmin", $THIS_HTML);
	$THIS_HTML = eregi_replace("src\=\"sohoadmin", "src=\"http://".$this_ip."/sohoadmin", $THIS_HTML);
	$THIS_HTML = eregi_replace("background\=\"sohoadmin", "background=\"http://".$this_ip."/sohoadmin", $THIS_HTML);

	$THIS_HTML = eregi_replace("src\=\"images", "src=\"http://$this_ip/images", $THIS_HTML);
	$THIS_HTML = eregi_replace("background\=\"images", "background=\"http://$this_ip/images", $THIS_HTML);
	$THIS_HTML = eregi_replace("http://$this_ip/http://$this_ip/", "http://$this_ip/", $THIS_HTML);	// Text Editor Work around for images

	$THIS_HTML = eregi_replace("pgm\-download", "http://$this_ip/pgm-download", $THIS_HTML);
	$THIS_HTML = eregi_replace("href\=\"media/", "href=\"http://$this_ip/media/", $THIS_HTML);
	$THIS_HTML = str_replace("href=\"sohoadmin", "href=\"http://".$this_ip."/sohoadmin", $THIS_HTML);
	$THIS_HTML = eregi_replace("src\=\"download_icon\.gif", "src=\"http://$this_ip/sohoadmin/program/modules/page_editor/client/download_icon.gif", $THIS_HTML);
	$THIS_HTML = eregi_replace("http://".$this_ip."http://".$this_ip, "http://".$this_ip, $THIS_HTML);

	$THIS_HTML = eregi_replace("\"shopping/", "\"http://$this_ip/shopping/", $THIS_HTML);
	$THIS_HTML = eregi_replace('(title|alt|mce_src)\=("|\'| )[^"\' ]*("|\'| )', '', $THIS_HTML);
	
	echo "<DIV ID=HTMLLAYER style=\"position:absolute; visibility:visible; left:0px; top:8%; width:100%; height:92%; z-index:5; overflow: auto; border: 1px solid #000000\">\n";
	echo " ".$THIS_HTML."\n";
	echo "</DIV>";
	exit;

$THIS_HTML = eregi_replace("href=\"index.php", "href=\"http://$this_ip/index.php", $THIS_HTML);
$THIS_HTML = eregi_replace("/images", "http://$this_ip/images", $THIS_HTML);
$THIS_HTML = eregi_replace("src=\"sohoadmin", "src=\"http://".$this_ip."/sohoadmin", $THIS_HTML);


$THIS_HTML = eregi_replace("src=\"images", "src=\"http://$this_ip/images", $THIS_HTML);
$THIS_HTML = eregi_replace("background=\"images", "background=\"http://$this_ip/images", $THIS_HTML);
		
$THIS_HTML = eregi_replace("http://$this_ip/http://$this_ip/", "http://$this_ip/", $THIS_HTML);	// Text Editor Work around for images
$THIS_HTML = eregi_replace("<img src=\"sohoadmin", "<img src=\"http://".$this_ip."/sohoadmin", $THIS_HTML);
$THIS_HTML = eregi_replace("background=\"sohoadmin", "background=\"http://".$this_ip."/sohoadmin", $THIS_HTML);

	
	echo "<DIV ID=HTMLLAYER style=\"position:absolute; visibility:visible; left:0px; top:8%; width:100%; height:92%; z-index:5; overflow: auto; border: 1px solid #000000\">\n";
	echo " ".$THIS_HTML."\n";
	echo "</DIV>";
	exit;
	
}

#############################################################################################

if ($vtype == "TEXT") {

	echo "<DIV ID=HTMLLAYER style=\"position:absolute; visibility:visible; left:0px; top:8%; width:100%; height:92%; z-index:5; overflow: auto; border: 1px none #000000\">\n";


	$tmp = $row[TEXT_CONTENT];
	$tmp = eregi_replace("\n", "<BR>", $tmp);
	$tmp = eregi_replace("\[this_id\]", "$v", $tmp);
	
	echo "<BR><TABLE BORDER=0 CELLPADDING=5 CELLSPACING=0 ALIGN=CENTER WIDTH=612 STYLE='background: #EFEFEF; border: 1px inset black;'>\n";
	echo "<tr><td align=left valign=top><font STYLE='font-size: 8pt;'><TT>\n";
	echo $tmp;
	echo "</TD></TR></TABLE></DIV>\n\n";
}

#############################################################################################

exit;

?>