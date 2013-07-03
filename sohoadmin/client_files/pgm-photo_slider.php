<?php
error_reporting('341');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
##      /// http://slidesjs.com/
## Homepage:	 	http://www.soholaunch.com
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

session_start();
global $jqueryincluded;
global $photoslidercount;
if(preg_match('/[^0-9]+/',$THIS_ID)){
	exit;	
}

if($photoslidercount==''){
	$photoslidercount=0;
}
++$photoslidercount;

$getimages = mysql_query("select * from photo_album_images where album_id='".$THIS_ID."' order by image_order asc");
$piccount = mysql_num_rows($getimages);
$sh_slider_imagesArr = array();

while ( $imgArr = mysql_fetch_assoc($getimages) ) {
	$sh_slider_imagesArr[] = $imgArr;
}

# Check for custom slider.php
$custom_slider_file = 'sohoadmin/program/modules/site_templates/pages/'.$template_folder.'/slider.php';
if ( file_exists($custom_slider_file) ) {
	include($custom_slider_file);

} else {

	if($shopping_cart_on==1 && $photoslidercount==1){
		if($jqueryincluded==''){
			echo "	<script type=\"text/javascript\" src=\"../sohoadmin/client_files/jquery.min.js\"></script>\n";
			$jqueryincluded=1;
		}
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../sohoadmin/client_files/slidersytles.css\" />\n";
	} elseif($photoslidercount==1) {
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"sohoadmin/client_files/slidersytles.css\" />\n";		
		if($jqueryincluded==''){
			echo "	<script type=\"text/javascript\" src=\"sohoadmin/client_files/jquery.min.js\"></script>\n";
			$jqueryincluded=1;
		}
		echo "	<script src=\"sohoadmin/client_files/slider.jquery.js\" type=\"text/javascript\"></script>\n";
	}
	
	if(!function_exists('resize_dimensions')){
		function resize_dimensions($goal_width,$goal_height,$width,$height) { 
			$return = array('width' => $width, 'height' => $height); 
			if ($width/$height > $goal_width/$goal_height && $width > $goal_width) { 
				$return['width'] = $goal_width; 
				$return['height'] = $goal_width/$width * $height; 
			} else if ($height > $goal_height) { 
				$return['width'] = $goal_height/$height * $width; 
				$return['height'] = $goal_height;
			} else if ($height < $goal_height && ($width/$height) < ($goal_width/$goal_height)) { 
			//	$return['width'] = (($goal_height/$height) * $width);
			//	$return['height'] = $goal_height;
			}
			return $return; 
		}
				
	}
	
	echo "<style>
	.pagination {
		width:".(16*$piccount)."px;
	}
	.sliderImage {
		max-width:570px;
		max-height:270px;
	}
	</style>\n";


	echo "<script  type=\"text/javascript\">
		$(function(){
			$('#slides".$photoslidercount."').slides({
				preload: true,
				preloadImage: 'sohoadmin/client_files/sliderimg/loading.gif',
				play: 5000,
				pause: 2500,
				hoverPause: true,
				animationStart: function(current){
					$('.caption').animate({
						bottom:-35
					},100);
				},
				animationComplete: function(current){
					$('.caption').animate({
						bottom:0
					},200);
				},
				slidesLoaded: function() {
					$('.caption').animate({
						bottom:0
					},200);
				}
			});
		});
	</script>\n";
?>
	<div class="fcontainer" style="position:relative;">
		<div class="framecontainer" >			
<?php
echo "			<img src=\"sohoadmin/client_files/sliderimg/example-frame.png\" width=\"739\" height=\"341\"  alt=\"\" class=\"framess\" id=\"frame".$photoslidercount."\" style=\"max-width:739px!important;width:739px!important;height:341px!important;\">\n";
echo "			<div class=\"slides\" id=\"slides".$photoslidercount."\">\n";
?>
				<div class="slides_container">
				<?php
				$sh_max = count($sh_slider_imagesArr);
				for ( $xax = 0; $xax < $sh_max; $xax++ ) {
					$imgn = $sh_slider_imagesArr[$xax];
					$disImg = getimagesize("images/".$imgn['image_name']);
					$new_dimensions = resize_dimensions(570,270,$disImg['0'],$disImg['1']);
					$margleft=0;
					$margtop=0;
					if($new_dimensions['width'] < 570){
						$margleft=(570-$new_dimensions['width'])/2;
					}
					if($new_dimensions['height'] < 270){
						$margtop=(270-$new_dimensions['height'])/2;
					}
					echo "<div class=\"slide\">\n";
					if(strlen($imgn['link']) > 1){
						echo "<a href=\"".$imgn['link']."\" title=\"".$imgn['caption']."\">";							
					}
					echo "<img class=\"sliderImage\" style=\"width:".$new_dimensions['width']."px;height:".$new_dimensions['height']."px;margin-left:".$margleft."px;margin-top:".$margtop."px;\" src=\"images/".$imgn['image_name']."\"  alt=\"Slide ".($xax+1)."\">";
					if(strlen($imgn['link']) > 1){
						echo "</a>\n";
					}
					if(strlen($imgn['caption']) > 0){
						$displaytext = 'block';
					} else {
						$displaytext = 'none';	
					}
					echo "<div class=\"caption\" style=\"bottom:0;display:".$displaytext.";\">\n";
					echo "	<p>".$imgn['caption']."</p>\n";
					echo "</div>\n";
					echo "</div>\n";
				}
				?>
				</div>
				<a href="javascript:void(0);" class="prev"><img src="sohoadmin/client_files/sliderimg/arrow-prev.png" width="24" height="43" alt="Arrow Prev"></a>
				<a href="javascript:void(0);" class="next"><img src="sohoadmin/client_files/sliderimg/arrow-next.png" width="24" height="43" alt="Arrow Next"></a>
			</div>
			
		</div>
	</div>
<?php
} // End else use default slider
?>