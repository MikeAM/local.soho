<?php
//echo "<div class=\"all_dir\">\n";
//
//$count = 0;
//for ($i=0;$i<sizeof($dirs);$i++) {
//	$count++;
//	echo "<div class=\"dir_disp\"><a class='dir' href='?type=" . $type . "&dir=" . $requested_dir . "/" . $dirs[$i] . "'><img src='" . $folder_large_image . "' width='" . $dir_width . "' border='0px'><br><a href='#' onClick='delete_folder(\"" . $dirs[$i] . "\")'><img border=0 src='" . $delete_image . "'></a> " . $dirs[$i] . "</a></div>\n";
//	if ($count == $pics_per_row || $i == (sizeof($dirs)-1)) {
//		$count = 0;
//	}
//}
//echo "</div>\n";


$count = 0;
for ($i=0;$i<sizeof($files);$i++) {
   if(strlen($files[$i]) > 2){
   	$count++;
   	echo "      <div class=\"image_disp\"><a class='file' href='#' onClick='prev_selected(\"".$files[$i]."\");'>".$files[$i]."</a></div>\n";
   	if ($count == $pics_per_row || $i == (sizeof($files)-1)) {
   		$count = 0;
   	}
   }
}
?>
