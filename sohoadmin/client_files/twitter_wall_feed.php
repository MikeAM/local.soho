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
$todaysdate=date("m/d/Y");
$yesterdaysdate=date("m/d/Y", strtotime('yesterday'));

//$tw_post_limit = 5;
//$twitter_id = 'soholaunch';
//$tw_show_follow_us = 1;

if($twitter_id!=''){
	
	//$twitter_feed_api = "http://twitter.com/statuses/user_timeline/".$twitter_id.".xml?count=20";
	$twitter_feed_api = "https://api.twitter.com/1/statuses/user_timeline.xml?include_entities=false&include_rts=false&exclude_replies=true&screen_name=".$twitter_id."&count=20";
	$tweetfeeds = new userdata("twitterfeed");
	$twrss = $tweetfeeds->get($fb_facebookid);
	if(strlen($fbrss) < 2){
		//$twrss = include_r("http://twitter.com/statuses/user_timeline/".$twitter_id.".xml?count=20");	
		$twrss = include_r("https://api.twitter.com/1/statuses/user_timeline.xml?include_entities=false&include_rts=false&exclude_replies=true&screen_name=".$twitter_id."&count=20");	
		$tweetfeeds->set($twitter_id, $twrss);
		$tweetfeeds->set('updatetime', time());
		$tweetfeeds->set('twid', $twitter_id);
	} else {
		if(time()-$tweetfeeds->get('updatetime') > 1800){
			//$twrss2 = include_r("http://twitter.com/statuses/user_timeline/".$twitter_id.".xml?count=20");
			$twrss2 = include_r("https://api.twitter.com/1/statuses/user_timeline.xml?include_entities=false&include_rts=false&exclude_replies=true&screen_name=".$twitter_id."&count=20");
			$feedlines = explode("\n",$twrss2);
			if($feedlines['0'] == '<?xml version="1.0" encoding="UTF-8"?>'){
				$tweetfeeds->set($twitter_id, $twrss2);
				$tweetfeeds->set('updatetime', time());
				$tweetfeeds->set('twid', $twitter_id);
				$twrss = $twrss2;
			}
		}
	}

	echo "<div class=\"twitdiv\">\n";
	$twitter_ar=xml2array($twrss);
	$twit_count = 0;
	if($tw_show_follow_us == 1){
		echo "<div class=\"twtitle\"><img src=\"sohoadmin/program/modules/page_editor/images/soc_twitter.gif\" style=\"width:18px;height:18px;border:0;vertical-align:bottom;\"><a class=\"twitheader\" href=\"http://twitter.com/".$twitter_id."\" target=\"_BLANK\">Follow Us On Twitter!</a></div>\n";
	}
	echo "<ul>\n";
	$twfeed = '';
	foreach($twitter_ar['statuses']['status'] as $tw_val){
		if($twit_count < $tw_post_limit){			
			$twittimestamp=strtotime($tw_val['created_at']);
			$timeago = round((time()-$twittimestamp)/60);
			if($todaysdate == date("m/d/Y",$twittimestamp)){
				if($timeago == 1){
					$tw_val['created_at'] = '1 minute ago';
				} elseif($timeago < 60) {
					$tw_val['created_at'] = $timeago.' minutes ago';
				} elseif(($timeago/60) > 59 || ($timeago/60) < 120){
					$tw_val['created_at'] = '1 hour ago';
				} else {
					//$tw_val['created_at'] = date('F jS', $twittimestamp)." at ".date('g:ia', $twittimestamp);	
					$tw_val['created_at'] = ($timeago/60).' hours ago';
				}
			} elseif($yesterdaysdate == date("m/d/Y",$twittimestamp)){
				$tw_val['created_at'] = "Yesterday at ".date('g:ia', $twittimestamp);
			} else {
				$tw_val['created_at'] = date('F jS', $twittimestamp)." at ".date('g:ia', $twittimestamp);
			}
			$tw_val['text'] = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="\\1" rel="nofollow" target="_BLANK">\\1</a>', $tw_val['text']);
			//$tw_val['created_at'] = ;
			$twfeed .= "<li><span class=\"tw_entry_title\"><a rel=\"nofollow\" href=\"http://twitter.com/".$twitter_id."/statuses/".$tw_val['id']."\" target=\"_BLANK\">".$tw_val['created_at']."</a></span> " . $tw_val['text'] . "</li>\n";
		}
		++$twit_count;
	}
	echo $twfeed;
	echo "</ul>\n";
	echo "</div>\n";
}

?>