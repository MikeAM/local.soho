<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
##
## Homepage:	 	http://www.soholaunch.com
###############################################################################
##############################################################################
## COPYRIGHT NOTICE                                                     
## Copyright 2003-2012 Soholaunch.com, Inc.
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
header("Content-Type:text/xml");
session_start();
include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");

echo '<?xml version="1.0" encoding="UT'.'F-8"?>';
echo '<?xml-stylesheet type="text/xsl" href="http://'.$_SESSION['this_ip'].'/sohoadmin/client_files/sitemap.xsl"?>'."\n";
echo '<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'."\n";

$urls=array();
$urls[]=array('url'=>'http://'.$_SESSION['this_ip'].'/','modify'=>filemtime('sohoadmin/tmp_content/'.startpage().'.con'));
$gu = mysql_query("select prikey,page_name,url_name,type,custom_menu,sub_pages,sub_page_of,main_menu,link,username from site_pages where username='' and type='Main' and (sub_page_of!='' or main_menu != 0) order by main_menu");

while($g_url = mysql_fetch_assoc($gu)){
	if($g_url['url_name']!=startpage()){
		$urls[]=array('url'=>'http://'.$_SESSION['this_ip'].'/'.htmlspecialchars($g_url['url_name']).'.php','modify'=>filemtime('sohoadmin/tmp_content/'.$g_url['url_name'].'.con'));
	}
}

$gblogs = mysql_query("select prikey,blog_category,blog_title,blog_date,live,timestamp from blog_content where live='publish' order by timestamp");
if(mysql_num_rows($gblogs)>0){
	while($blgs = mysql_fetch_assoc($gblogs)){
		$urls[]=array('url'=>'http://'.$_SESSION['this_ip'].'/?id='.$blgs['prikey'].'&amp;art='.str_replace('&','&amp;',str_replace(' ','%20',preg_replace('/[^0-9a-zA-Z ]/i', '', htmlentities($blgs['blog_title'])))),'modify'=>$blgs['timestamp']);
	}
}

foreach($urls as $urlval){
echo '<url>
  <loc>'.$urlval['url'].'</loc>
  <lastmod>'.date('Y-m-d', $urlval['modify']).'T'.preg_replace('/00$/', ':00', date('G:i:sO', $urlval['modify'])).'</lastmod>
  <changefreq>always</changefreq>
</url>'."\n";
}

echo '</urlset>';
?>