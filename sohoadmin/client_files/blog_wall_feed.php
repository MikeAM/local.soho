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

//echo $sohoblog_cat;
//echo 'hi'; exit;

//$sohoblog_post_limit = 1;
//$sohoblog_show_timestamp = 1;
//$sohoblog_show_readmore = 1;
//$sohoblog_show_author = 1;


if($sohoblog_cat!=''){
	
	if($sohoblog_cat=='All'){
		$blog_feed_api = "http://".$_SESSION['this_ip']."/blog.rss.php?limit=".$sohoblog_post_limit;
	} else {
		$blog_feed_api = "http://".$_SESSION['this_ip']."/blog.rss.php?cat=".$sohoblog_cat."&limit=".$sohoblog_post_limit;	
	}	

	$blogrss = include_r($blog_feed_api);

	echo "<div class=\"blogfeeddiv\">\n";
	$sohoblog_ar=xml2array($blogrss);
	$sohoblog_count = 0;
//	if($tw_show_follow_us == 1){
//		echo "<div class=\"twtitle\"><img src=\"sohoadmin/program/modules/page_editor/images/soc_twitter.gif\" style=\"width:18px;height:18px;border:0;vertical-align:bottom;\"><a class=\"twitheader\" href=\"http://twitter.com/".$twitter_id."\" target=\"_BLANK\">Follow Us On Twitter!</a></div>\n";
//	}

	echo "<ul>\n";
	$sohoblogfeed = '';
	foreach($sohoblog_ar['rss']['channel']['item'] as $sohoblog_val){
		if($sohoblog_count < $sohoblog_post_limit){			
			$sohoblogtimestamp=strtotime($sohoblog_val['pubDate']);
			$timeago = round((time()-$sohoblogtimestamp)/60);
			if($todaysdate == date("m/d/Y",$sohoblogtimestamp)){
				if($timeago == 1){
					$sohoblog_val['pubDate'] = '1 minute ago';
				} elseif($timeago < 60) {
					$sohoblog_val['pubDate'] = $timeago.' minutes ago';
				} elseif(($timeago/60) > 59 || ($timeago/60) < 120){
					$sohoblog_val['pubDate'] = '1 hour ago';
				} else {
					//$sohoblog_val['created_at'] = date('F jS', $sohoblogtimestamp)." at ".date('g:ia', $sohoblogtimestamp);	
					$sohoblog_val['pubDate'] = ($timeago/60).' hours ago';
				}
			} elseif($yesterdaysdate == date("m/d/Y",$sohoblogtimestamp)){
				$sohoblog_val['pubDate'] = "Yesterday at ".date('g:ia', $sohoblogtimestamp);
			} else {
				$sohoblog_val['pubDate'] = date('F jS', $sohoblogtimestamp)." at ".date('g:ia', $sohoblogtimestamp);
			}
			
			$sohoblogfeed .= "<li><span class=\"blog_entry_title\">";
			if($sohoblog_show_timestamp==1){
				$sohoblogfeed .= "<a href=\"".$sohoblog_val['link']."\">".$sohoblog_val['pubDate']."</a></span> ";
			}
			$sohoblogfeed .= $sohoblog_val['title'];
			
			if($sohoblog_show_author==1){
				$sohoblogfeed .= " <span class=\"sohoblog_auth\">".lang('by').": ".$sohoblog_val['author']."</span>";
			}
			
			if($sohoblog_show_readmore==1){
				$sohoblogfeed .= " <a href=\"".$sohoblog_val['link']."\">".lang('read more...')."</a>";
			}
			
			$sohoblogfeed .= "</li>\n";
		}
		++$sohoblog_count;
	}
	echo $sohoblogfeed;
	echo "</ul>\n";
	echo "</div>\n";
}

?>