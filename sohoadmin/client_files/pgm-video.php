<?php
error_reporting('341');
// You were here 2/13: Got mp4 working via HTML 5 in IE using doctype
// Next: Get ogg working in IE via anything, treat all IEs the same
//if ( $_SESSION['cachetest'] < 1 ) { $_SESSION['cachetest'] = 1; }
//$_SESSION['cachetest']++;
//echo '['.$_SESSION['cachetest'].']';
/*
INCLUDED BY: pgm-realtime_builder.php
DEPENDS ON: $video_filename being defined in parent script

OUTLINE
if OGG
	If Chrome || FF
		<video>
	else
		object
		
if MP4
	If Chrome || IE9+ || S5+
		<video>
	else 
		This video will only play in Chrome, IE, or Safari.
		Download link
if WMV
	object embed

if FLV
	object embed
	
if MOV
	object embed
if AVI
	object embed
*/

# Edit as browsers add compatibility
$use_playerArr = array('flv', 'swf', 'mpeg', 'mpg');
$use_legacyArr = array('avi', 'mov', 'wmv');

if ( !function_exists('browser_can_ogg') ) {
	function browser_is($browser_name) {
		if ( $browser_name == 'ie' ) { $browser_name = 'MSIE'; }
		if ( $browser_name == 'ie9' ) { $browser_name = 'MSIE 9'; }
		if ( $browser_name == 'ie10' ) { $browser_name = 'MSIE 10'; }
		if ( strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), strtoupper($browser_name)) !== false ) {
			return true;
		} else {
			return false;
		}
	}	
	
	function browser_can_ogg() {
		if ( browser_is('chrome') || browser_is('firefox') || browser_is('opera') ) {
			return true;
		} else {
			return false;
		}
	}
	function browser_can_mp4() {
		if ( browser_is('firefox') || browser_is('opera') || browser_is('ie') ) {
//			echo 'false'; exit;
			return false;
		} else {
//			echo 'true'; exit;
			return true;
		}
	}
}

$video_file_type = strtolower(array_pop(explode('.',$video_filename)));
$video_file_ext = '.'.$video_file_type;
$video_filename_noext = preg_replace('/'.$video_file_ext.'$/i', '', $video_filename);
//# Get file type
////preg_match('/([\d\s\w]+)$/i', $video_filename, $out);
//$video_file_ext = strtolower($out[0]);
//$video_file_type = str_replace('.', '', $video_file_ext);
//$video_filename_noext = preg_replace('/'.$video_file_ext.'$/i', '', $video_filename);
$video_width = str_replace('px', '', $video_width);
$video_height = str_replace('px', '', $video_height);

if ( $video_width == '' ) { $video_width = 500; }
if ( $video_height == '' ) { $video_height = 312; }

# Check for URL first
if ( preg_match('/^http/i', $video_filename) ) {

	# Make sure Vimeo embeds use their player URL (or else they won't work)
	$vimeo_player_url = 'player.vimeo.com';
	if ( stripos($video_filename, 'vimeo') != false && stripos($video_filename, $vimeo_player_url) === false ) {
		$video_filename = preg_replace('/(http[s]?:\/\/)??(www\.)?vimeo.com/i', 'http://'.$vimeo_player_url.'/video', $video_filename);
	}

	/* 
   # YouTube
   #--------------------------
   # NOTE: No passing timecode with embed code. Only ID matters & rel = matters (at least in their standard embed code).
   # rel= turns off related videos, works on all embed urls

   // Doesn't Work
   # Address bar        = http://www.youtube.com/watch?v=6Jz0JcQYtqo
   # Share Link C/P     = http://www.youtube.com/watch?v=6Jz0JcQYtqo&feature=youtu.be&t=1s&rel=0
   # FB Share Link      = http://youtu.be/6Jz0JcQYtqo?t=1s&rel=0
   
   // Works
   # Embed + No Related = http[s]://www.youtube.com/embed/6Jz0JcQYtqo?rel=0
   # Embed URL          = http[s]://www.youtube.com/embed/6Jz0JcQYtqo
   # Embed + Privacy    = https://www.youtube-nocookie.com/embed/6Jz0JcQYtqo
   # Embed + Old Code   = https://www.youtube-nocookie.com/v/6Jz0JcQYtqo?version=3&amp;hl=en_US
   
   OUTLINE: YouTube
   Embed code?
   -YES: Use URL as-is
   -ELSE: 
      -extract id
      -extract rel preference
      -use http
      -use non-privacy url
	*/
	# Is their embed URL good as-is? Or did they copy-paste a bad url?
	if ( stripos($video_filename, 'youtu') && !preg_match('/(\/embed\/|\/v\/)/i', $video_filename) ) {
		# Non-embed URL, extract video id and rebuild
		preg_match('/v=([\d\w-]+)|be\/([\d\w]+)/i', $video_filename, $idmatches);
		$idmatches = array_values(array_filter($idmatches)); // Kill empties and reindex
		$youtube_id = $idmatches[1];
		$relstr = 'rel=0';
		if ( stripos($video_filename, $relstr) ) {
			$relstr = '?'.$relstr;
		} else {
			$relstr = '';
		}
		
		$video_filename = 'http://www.youtube.com/embed/'.$youtube_id.$relstr;
	}
?>
	<iframe src="<?php echo $video_filename; ?>" width="<?php echo $video_width; ?>" height="<?php echo $video_height; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe> 
	<!--- <iframe src="http://www.youtube.com/embed/wt-iVFxgFWk" width="560" height="315" frameborder="0" allowfullscreen></iframe>	 -->
<?php
} elseif ( in_array($video_file_type, $use_legacyArr)) {
	if($globalprefObj->get('video_popup') == 'true'){
		if($vidcounter==''){ $vidcounter=1; } else { ++$vidcounter; }		
		echo "<div align=\"center\"><form name=\"vidnet".$vidcounter."\">
		<input value=\" View Video \" class=\"FormLt1\" onclick=\"MM_openBrWindow('pgm-view_video.php?name=".$video_filename."&w=640&h=480','videowin','width=660,height=520,location=no, menubar=no, titlebar=no, resizable=no, status=no, toolbar=no');\" type=\"button\">
		</form></div>\n";
	} else {
		echo "<embed src=\"media/".$video_filename."\" width=\"".$video_width."\" height=\"".$video_height."\" AUTOSTART=false LOOP=false showcontrols=1></embed> \n";
	}
} elseif ( in_array($video_file_type, $use_playerArr) || ($video_file_type == 'mp4' && !browser_can_mp4()) ) {
?>
<!--Flowplayer-->
<object data="http://releases.flowplayer.org/swf/flowplayer-3.1.5.swf" type="application/x-shockwave-flash" style="width: <?php echo $video_width; ?>px;height: <?php echo $video_height; ?>px;"> 
	<param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.1.5.swf" /> 
	<param name="allowfullscreen" value="true" /> 
	<param name="allowscriptaccess" value="always" /> 
	<param name="play" value="false">
	<param name="flashvars" value='config={"plugins":{"pseudo":{"url":"flowplayer.pseudostreaming-3.1.3.swf"},"controls":{"backgroundColor":"#000000","backgroundGradient":"low"}},"clip":{"provider":"pseudo","url":"<?php echo $video_filename; ?>", "autoPlay":false},"playlist":[{"provider":"pseudo","url":"http://<?php echo $_SESSION['this_ip']; ?>/media/<?php echo $video_filename; ?>"}]}' /> 
</object> 
<?php	
} else {
	
	echo "	<video width=\"".$video_width."\" height=\"".$video_height."\" controls>\n";
	if(file_exists($_SESSION['docroot_path']."/media/".$video_filename_noext."ogg")){
		echo "	<!-- if Firefox -->
		<source src=\"media/".$video_filename_noext."ogg\" type=\"video/ogg\" />\n";
	}
	if(file_exists($_SESSION['docroot_path']."/media/".$video_filename_noext."mp4")){
		echo "	<!-- if Safari/Chrome-->
		<source src=\"media/".$video_filename_noext."mp4\" type=\"video/mp4\" />\n";
	}
	
	if(file_exists($_SESSION['docroot_path']."/media/".$video_filename_noext."webm")){		
		echo"		<!-- if Safari/Chrome-->
		<source src=\"media/".$video_filename_noext."webm\" type=\"video/webm\" />\n";
	}
	
	echo "	<embed src=\"http://".$_SESSION['this_ip']."/media/".$video_filename."\" width=\"".$video_width."\" height=\"".$video_height."\" showcontrols=\"1\" autostart=\"false\" loop=\"false\">\n";
	echo "	Your cannot play this HTML 5 video. Please try a different browser, or download the video through the link below.
	</video>\n";

}
if($globalprefObj->get('video_popup') != 'true'){
	echo "<div class=\"containter-download-link\">
		<label>Download Video:</label> <a href=\"pgm-download_media.php?".$video_filename."\" class=\"link-download\">(".$video_filename.")</a>
	</div>\n";	
}