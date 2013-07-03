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
error_reporting('341');
header("Content-Type:text/xml");
session_start();
include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");

unset($authorid_array);
$getwebmaster = mysql_query("select * from login where First_Name='WEBMASTER' limit 1");
$getwebmaster_ar = mysql_fetch_assoc($getwebmaster);
$author_array[$getwebmaster_ar['PriKey']]=array('name'=>$getwebmaster_ar['Last_Name'],'email'=>$getwebmaster_ar['Email']);
$authorid_arrayemail[$getwebmaster_ar['PriKey']] = $getwebmaster_ar['Email'];
$authorid_array[$getwebmaster_ar['Last_Name']] = $getwebmaster_ar['PriKey'];
$authqry =  mysql_query("SELECT login.PriKey, login.First_Name, login.Last_Name, login.Email, user_access_rights.LOGIN_KEY, user_access_rights.PICTURE FROM login INNER JOIN user_access_rights ON user_access_rights.LOGIN_KEY=login.PriKey order by login.PriKey");
while($auth_ar = mysql_fetch_assoc($authqry)){	
	$author_array[$auth_ar['PriKey']]['name']=$auth_ar['Last_Name'];
	$author_array[$auth_ar['PriKey']]['email']=$auth_ar['Email'];
}
echo '<?xml version="1.0" encoding="UT'.'F-8"?>
<rss version="2.0"
      xmlns:media="http://search.yahoo.com/mrss/"
      xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <description>'.$_SESSION['this_ip'].' Blog Feed</description>
    <link>http://'.$_SESSION['this_ip'].'</link>
    <description>'.$_SESSION['this_ip'].' Blog Feed</description>
    <language>en-us</language>
    <lastBuildDate>'.date('Y-m-d').'T'.preg_replace('/00$/', ':00', date('G:i:sO')).'</lastBuildDate>'."\n";
$urls=array();
//$urls[]=array('url'=>'http://'.$_SESSION['this_ip'].'/','modify'=>filemtime('sohoadmin/tmp_content/'.startpage().'.con'));
if($_REQUEST['cat']!=''){
	$cat=preg_replace('[^0-9]','',$_REQUEST['cat']);	
}

if($cat!=''){
	$gblogs = mysql_query("select prikey,blog_category,blog_title,blog_author,blog_tags,blog_date,live,timestamp from blog_content where live='publish' and blog_category='".$cat."' order by timestamp desc");
} else {
	$gblogs = mysql_query("select prikey,blog_category,blog_title,blog_author,blog_tags,blog_date,live,timestamp from blog_content where live='publish' order by timestamp desc");	
}

if(mysql_num_rows($gblogs)>0){
	while($blgs = mysql_fetch_assoc($gblogs)){
		$urls[]=array('author'=>$author_array[$blgs['blog_author']]['name'],'title'=>$blgs['blog_title'] ,'category'=>$blgs['category'],'url'=>'http://'.$_SESSION['this_ip'].'/?id='.$blgs['prikey'].'&amp;art='.str_replace('&','&amp;',str_replace(' ','%20',preg_replace('/[^0-9a-zA-Z ]/i', '', urlencode(substr($blgs['blog_title'],0,40))))),'modify'=>$blgs['timestamp']);
	}
}

foreach($urls as $urlval){
	echo '    <item>
      <guid isPermaLink="false">'.$urlval['url'].'</guid>
      <title><![CDATA['.$urlval['title'].']]></title>
      <link>'.$urlval['url'].'</link>
      <description><![CDATA['.$urlval['title'].']]></description>
      <pubDate>'.date('Y-m-d', $urlval['modify']).'T'.preg_replace('/00$/', ':00', date('G:i:sO', $urlval['modify'])).'</pubDate>
      <author>'.$urlval['author'].'</author>
      <dc:creator>'.$urlval['author'].'</dc:creator>
    </item>'."\n";


}

//echo '</urlset>';
echo '  </channel>
</rss>';
?>