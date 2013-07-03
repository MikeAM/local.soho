<?php
error_reporting('341');
/*
NOTE: IE Cannot play .OGG under even with Windows Media Player
NOTE: Opera can't use html5 or google to play mp3s, and it doesn't embed well either
NOTE: Firefox cannot play .ogg without .htaccess mod, sometimes
*/


# Get file type

$file_ext = strtolower(array_pop(explode('.',$audio_filename)));
//$file_type = $file_ext;
$file_type = '.'.$file_ext;
$filename_no_ext = preg_replace('/'.$file_ext.'$/i', '', $audio_filename);


//preg_match('/([\d\s\w]+)$/i', $audio_filename, $out);
//$file_ext = strtolower($out[0]);
//$file_type = $file_ext;
//$filename_no_ext = str_replace($file_ext, '', $audio_filename);
$html5_audio_extArr = array('mp3', 'm4a', 'ogg', 'wav', 'oga');

if ( !function_exists('browser_is') ) {
	function browser_is($browser_name) {
		if ( $browser_name == 'ie' ) { $browser_name = 'MSIE'; }
//		if ( ucwords(strpos($_SERVER['HTTP_USER_AGENT']), ucwords($browser_name)) !== false ) {
//			return true;
//		}
		
		if ( strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), strtoupper($browser_name)) !== false ) {
			return true;
		} else {
			return false;
		}
		
	}
}

if($file_ext=='mp3' && (!browser_is('Opera') && !browser_is('Firefox'))){
	
	echo "<!-- HTML5 <audio> --->\n";
	echo "<audio controls>\n";
	echo "   <source src=\"media/".$filename_no_ext."mp3\" type=\"audio/mpeg\" />\n";
//	echo "   <embed type=\"application/x-shockwave-flash\" src=\"http://www.google.com/reader/ui/3523697345-audio-player.swf\" quality=\"best\" flashvars=\"audioUrl=http://".$_SESSION['this_ip']."/media/".$audio_filename."\" width=\"500\" height=\"27\"/>   \n";
//	echo "   <object id=\"MediaPlayer\" classid=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" standby=\"Loading Media Player components...\" type=\"application/x-oleobject\">\n";
//	echo "      <param name=\"FileName\" value=\"".$audio_filename."\">\n";
//	echo "      <param name=\"autostart\" value=\"false\">\n";
//	echo "      <param name=\"ShowControls\" value=\"true\">\n";
//	echo "      <param name=\"ShowStatusBar\" value=\"false\">\n";
//	echo "      <param name=\"ShowDisplay\" value=\"false\">\n";
//	echo "      <embed type=\"application/x-mplayer2\" src=\"http://".$_SESSION['docroot_url']."/media/".$audio_filename."\" name=\"MediaPlayer\" ShowControls=\"1\" ShowStatusBar=\"0\" ShowDisplay=\"0\" autostart=\"0\"></EMBED>      \n";
//	echo "   </object>\n";
	echo "</audio>\n";


} elseif ( in_array($file_type, $html5_audio_extArr)  && browser_is('chrome')) {

	echo "<!-- HTML5 <audio> --->\n";
	echo "<audio controls>\n";
	if(file_exists("media/".$filename_no_ext."mp3")){
		echo "   <source src=\"media/".$filename_no_ext."mp3\" type=\"audio/mpeg\" />\n";
	}
	if(file_exists("media/".$filename_no_ext."ogg")){
		echo "   <source src=\"media/".$filename_no_ext."ogg\" type=\"audio/ogg\" />\n";
	}
	if(file_exists("media/".$filename_no_ext."wav")){
		echo "   <source src=\"media/".$filename_no_ext."wav\" type=\"audio/wav\" />\n";
	}
	if(file_exists("media/".$filename_no_ext."oga")){
		echo "   <source src=\"media/".$filename_no_ext."oga\" type=\"audio/ogg\" />\n";
	}
	if(file_exists("media/".$filename_no_ext."m4a")){		
		echo "   <source src=\"media/".$filename_no_ext."m4a\" type=\"audio/mpeg\" />\n";
	}
	echo "   <embed type=\"application/x-shockwave-flash\" src=\"".httpvar()."reader.googleusercontent.com/reader/ui/3523697345-audio-player.swf\" quality=\"best\" flashvars=\"audioUrl=".httpvar().$_SESSION['this_ip']."/media/".$audio_filename."\" width=\"500\" height=\"27\"/>   \n";
	echo "   <object id=\"MediaPlayer\" classid=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" standby=\"Loading Media Player components...\" type=\"application/x-oleobject\">\n";
	echo "      <param name=\"FileName\" value=\"media/".$audio_filename."\">\n";
	echo "      <param name=\"autostart\" value=\"false\">\n";
	echo "      <param name=\"ShowControls\" value=\"true\">\n";
	echo "      <param name=\"ShowStatusBar\" value=\"false\">\n";
	echo "      <param name=\"ShowDisplay\" value=\"false\">\n";
	echo "      <embed type=\"application/x-mplayer2\" src=\"".httpvar().$_SESSION['docroot_url']."/media/".$audio_filename."\" name=\"MediaPlayer\" ShowControls=\"1\" ShowStatusBar=\"0\" ShowDisplay=\"0\" autostart=\"0\"></EMBED>      \n";
	echo "   </object>\n";
	echo "</audio>\n";

} else {

	echo "<object id=\"MediaPlayer\" classid=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\" standby=\"Loading Media Player components...\" type=\"application/x-oleobject\">\n";
	echo "   <param name=\"FileName\" value=\"media/".$audio_filename."\">\n";
	echo "   <param name=\"autostart\" value=\"false\">\n";
	echo "   <param name=\"ShowControls\" value=\"true\">\n";
	echo "   <param name=\"ShowStatusBar\" value=\"false\">\n";
	echo "   <param name=\"ShowDisplay\" value=\"false\">\n";
	echo "   <embed type=\"application/x-mplayer2\" src=\"media/".$audio_filename."\" name=\"MediaPlayer\" ShowControls=\"1\" ShowStatusBar=\"0\" ShowDisplay=\"0\" autostart=\"0\"  width=\"500\" height=\"20\"></embed>\n";
	echo "</object>\n";


}
//####/*<div class="media-filename">Download: <a href="pgm-download_media.php?name=< ?php echo $audio_filename; ? >" class="link-download">< ?php echo $audio_filename; ? ></a></div>  */
?>