<?php
error_reporting('341');
session_start();
/*
INCLUDED BY: object_write.php
DEPENDS ON: $video_filename being defined in parent script
WARNING: Will bomb Page Editor if you try to use ob_start() in this file.

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
$always_flashArr = array('wmv', 'flv', 'mov', 'avi', 'swf', 'mpeg', 'mpg');

if ( !function_exists('browser_can_ogg') ) {
	function browser_can_ogg() {
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false ) {
			return true;
		} else {
			return false;
		}
	}
	function browser_can_mp4() {
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false ) {
			return false;
		} else {
			return true;
		}
	}
}

# Get file type
$video_file_type = strtolower(substr($video_filename, -3));

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
		# Non-embed URL, extract stuff and rebuild
		preg_match('/v=([\d\w]+)|be\/([\d\w]+)/i', $video_filename, $idmatches);
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
	<iframe src="<?php echo $video_filename; ?>" width="<?php $video_width; ?>" height="<?php $video_height; ?>" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe> 
	<!--- <iframe src="http://www.youtube.com/embed/wt-iVFxgFWk" width="560" height="315" frameborder="0" allowfullscreen></iframe>	 -->
<?php
} elseif ( ($video_file_type == 'mp4' && !browser_can_mp4()) || in_array($video_file_type, $always_flashArr) ) {
?>
	<object width="500" height="300">
		<param name="movie" value="media/<?php echo $video_filename; ?>">
		<param name="autostart" value="False">
		<param name="autostart" value="0">
		<param name="play" value="false">
		<param name="controller" value="true">
		<param name="ShowControls" value="true">
		<param name="ShowStatusBar" value="false">
		<PARAM name="ShowDisplay" VALUE="false">
		<embed src="media/<?php echo $video_filename; ?>" TYPE="application/x-mplayer2" width="500" height="300" autoplay="false" showcontrols="1" showstatusbar="false" showdisplay="false" autostart="0"></embed>
	</object>
<?php	
} elseif ( ($video_file_type == 'mp4' && browser_can_mp4()) || (($video_file_type == 'ogg' || $video_file_type == 'ogv') && browser_can_ogg()) ) {
?>
	<video width="500" height="300" controls>
<?php echo "		<source src=\"".httpvar().$_SESSION['docroot_url']."/media/".$video_filename."\" type=\"video/".$video_file_type."\"/>\n"; ?>
		Your browser does not support HTML 5 video. Please update to a more modern browser.
	</video>
<?php
} else {
?>
	<p>Your browser cannot play this video file. Please try <a href="http://browsehappy.com" title="This video will play happily in Firefox, Chrome, and Opera">different browser</a>, or you can download the video here.</p>
<?php
}
?>