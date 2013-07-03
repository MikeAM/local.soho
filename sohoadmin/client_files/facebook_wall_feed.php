<?php
error_reporting('341');
session_start();

##############################################################################
## Soholaunch(R) Site Management Tool
##
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Release Notes:	http://wiki.soholaunch.com
###############################################################################

######################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2012 Soholaunch.com, Inc.
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
//$fb_facebookid='106892657903';
//$fb_include_pictures = 0;
//$fb_show_follow_us = 0;
//$fb_hide_author = 1;
//$fb_post_limit = 5;
$todaysdate=date("m/d/Y");
$yesterdaysdate=date("m/d/Y", strtotime('yesterday'));

$nfeeds = new userdata("facebookfeed");
$fbrss = $nfeeds->get($fb_facebookid);

if($fb_facebookid!=''){	
	
	if(strlen($fbrss) < 2){
		$nfeeds->set($fb_facebookid, include_r('http://www.facebook.com/feeds/page.php?id='.$fb_facebookid.'&format=rss20'));
		$nfeeds->set('updatetime', time());
		$nfeeds->set('fbid', $fb_facebookid);
		$fbrss = $nfeeds->get($fb_facebookid);
	} else {
		if(time()-$nfeeds->get('updatetime') > 1800){
			
			$fbrss2 = include_r('http://www.facebook.com/feeds/page.php?id='.$fb_facebookid.'&format=rss20');
			$feedlines = explode("\n",$fbrss2);
			if($feedlines['0'] == '<?xml version="1.0" encoding="utf-8"?>'){
				$nfeeds->set($fb_facebookid, $fbrss2);
				$nfeeds->set('updatetime', time());
				$nfeeds->set('fbid', $fb_facebookid);
				$fbrss = $nfeeds->get($fb_facebookid);
			}
		}
	}

	$fb_array=xml2array($fbrss);
	$counter = 0;
	$fbfeed = '';

	echo "<div class=\"facebookdiv\">\n";
	if($fb_show_follow_us == 1){
		echo "<div class=\"fbtitle\"><img src=\"sohoadmin/program/modules/page_editor/images/soc_facebook.png\" style=\"width:18px;height:18px;border:0;vertical-align:bottom;\"><a class=\"facebookheader\" href=\"http://www.facebook.com/".$fb_facebookid."\" target=\"_BLANK\">Follow Us On Facebook!</a></div>\n";
	}
	echo "<ul>\n";
	foreach($fb_array['rss']['channel']['item'] as $fb_upv=>$feed){
		if($counter < $fb_post_limit){
			$fbdate = strtotime($feed['pubDate']);
			if($fb_include_pictures!=1){
				$feed['description'] = preg_replace("/<img [^>]+[>]{1}/i","",$feed['description']);
			}
			$feed['description'] = preg_replace("/<br( )?(\/)?>/i"," ",$feed['description']);
			$timeago = round((time()-$fbdate)/60);
			if($todaysdate == date("m/d/Y",$fbdate)){
				if($timeago == 1){
					$fb_display_date = '1 minute ago';
				} elseif($timeago < 60) {
					$fb_display_date = $timeago.' minutes ago';
				} elseif(($timeago/60) > 59 || ($timeago/60) < 120){
					$fb_display_date = '1 hour ago';
				} else {
					//$fb_display_date = date('F jS', $fbdate)." at ".date('g:ia', $fbdate);	
					$fb_display_date = ($timeago/60).' hours ago';
				}
			} elseif($yesterdaysdate == date("m/d/Y",$fbdate)){
				$fb_display_date = "Yesterday at ".date('g:ia', $fbdate);
			} else {
				$fb_display_date = date('F jS', $fbdate)." at ".date('g:ia', $fbdate);
			}
			if($fb_hide_author!=1){			
				$fbfeed .= '<li><span class="fb_entry_title"><a target=\"_BLANK\" href="'. $feed['link'] .'">'.$feed['author'].'</a></span> '. $feed['description'] . ' <span class="fb_post_date">Posted '. $fb_display_date."</span></li>\n";
			} else {
				$fbfeed .= '<li><span class="fb_entry_title"><a target=\"_BLANK\" href="'. $feed['link'] .'">' . $fb_display_date . "</a></span> " . $feed['description'] . "</li>\n";
			}
			++$counter;
		}
	}
	$fbfeed =  preg_replace("/onmouseover=\".+(;\")/i",'', $fbfeed);
	$fbfeed =  preg_replace("/onclick=\".+(;\")/i",'', $fbfeed);
	
	$fbfeed = urldecode(str_replace('/l.php?u=http%3A%2F%2F','http://',$fbfeed));
	echo $fbfeed =  preg_replace('/&amp;h=[^"]+[^"]/i','" target="_BLANK" ', $fbfeed); 
	echo "</ul>\n";
	echo "</div>\n";
}
?>