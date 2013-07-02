<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##      
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]                 
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
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
//include_once('pgm-site_config.php');
$bgcolor = '#333333';
$bgcolor = '#ffffff';
$bgcolor = 'transparent';
?>
	<meta charset="utf-8">
	
	<script type="text/javascript" src="sohoadmin/client_files/jquery.min.js"></script>
	<script type="text/javascript" src="sohoadmin/client_files/galleria.js"></script>
	<style>

	.galcontent{color:inherit;font:14px/1.4 "helvetica neue", arial,sans-serif;width:620px;margin:20px auto}
	h1{line-height:1.1;letter-spacing:-1px;}
	/*a {color:#fff;}*/
	#galleria{height:450px; }
	
	.galleria-container {
		position: relative;
		overflow: hidden;
		background: <?php echo $bgcolor; ?>; 
	}
	.galleria-container img {
		-moz-user-select: none;
		-webkit-user-select: none;
		-o-user-select: none;
		cursor:pointer;
	}
		
	.galleria-stage {
		position: absolute;
		top: 10px;
		bottom: 110px;
		left: 10px;
		right: 10px;		
		overflow:visible; 
		
	}
	
	.galleria-images {
		overflow:visible;
	}

	
	.galleria-image {
		overflow:visible;
	}
	
	.galleria-stage img {
		border:3px solid #000000;
	}
	.galleria-thumbnails-container {
		height: 50px;
		bottom: 0;
		position: absolute;
		left: 10px;
		right: 10px;
		z-index: 2;
	}
	.galleria-carousel .galleria-thumbnails-list {
		margin-left: 20px;
		margin-right: 20px;
	
	}
	.galleria-thumbnails .galleria-image {
		height: 40px;
		width: 60px;
		background: <?php echo $bgcolor; ?>;
		margin: 0 3px 0 0;
		border: 1px solid #000;
		float: left;
		cursor: pointer;
	}

	.galleria-counter {
		position: absolute;
		top: 10px;
		left: 5px;
		text-align: right;
		/*color: #fff;*/
		color:inherit;
		font: normal 11px/1 arial,sans-serif;
		z-index: 2;
	}
	.galleria-loader {
		/* background: <?php echo $bgcolor; ?>; */
		width: 20px;
		height: 20px;
		position: absolute;
		top: 10px;
		right: 10px;
		z-index: 2;
		display: none;
		background: url(sohadmin/client_files/classic-loader.gif) no-repeat 2px 2px;
	}
	.galleria-info {
		width: 100%;
		bottom: 60px;
		left: 0px;
		z-index: 2;
		text-align:center;
		position: absolute;
	}
	.galleria-info-text {
	/*	background-color: <?php echo $bgcolor; ?>;  */
		padding: 12px;
		display: none;
		_zoom:1;
	}
	.galleria-info-title {
		font: bold 12px/1.1 arial,sans-serif;
		margin: 0;
		/*color: #fff;*/
		color:inherit;
	}
	.galleria-info-description {
		font: italic 12px/1.4 georgia,serif;
		margin: 0;
		/*color: #bbb;*/
		color:inherit;
	}
	.galleria-info-title+.galleria-info-description {
		margin-top: 7px;
	}
	.galleria-info-close {
		width: 9px;
		height: 9px;
		position: absolute;
		top: 5px;
		right: 5px;
		background-position: -753px -11px;
		opacity: .5;
		_filter: alpha(opacity=50);
		cursor: pointer;
		display: none;
	}
	.galleria-info-close:hover{
		opacity:1;
		_filter: alpha(opacity=100);
	}
	.galleria-info-link {
		background-position: -669px -5px;
		opacity: .7;
		_filter: alpha(opacity=70);
		position: absolute;
		width: 20px;
		height: 20px;
		cursor: pointer;
		/* background-color: <?php echo $bgcolor; ?>; */
	}
	.galleria-info-link:hover {
		opacity: 1;
		_filter: alpha(opacity=100);
	}
	.galleria-image-nav {
		position: absolute;
		top: 50%;
		margin-top: -62px;
		width: 100%;
		height: 62px;
		left: 0;
	}
	.galleria-image-nav-left,
	.galleria-image-nav-right {
		opacity: .3;
		_filter: alpha(opacity=30);
		cursor: pointer;
		width: 64px;
		height: 124px;
		position: absolute;
		left: 10px;
		z-index: 2;
		background-position: 0 50px;
	}
	.galleria-image-nav-right {
		left: auto;
		right: 10px;
		background-position: -254px 50px;
		z-index: 2;
	}
	
	.galleria-image-nav-right {

		width: 64px;
	}
	.galleria-image-nav-left:hover,
	.galleria-image-nav-right:hover {
		opacity: 1;
		_filter: alpha(opacity=100);
	}
	.galleria-thumb-nav-left,
	.galleria-thumb-nav-right {
		cursor: pointer;
		display: none;
		background-position: -495px 5px;
		position: absolute;
		left: 0;
		top: 0;
		height: 40px;
		width: 23px;
		z-index: 3;
		opacity: .8;
		_filter: alpha(opacity=80);
	}
	.galleria-thumb-nav-right {
		background-position: -578px 5px;
		border-right: none;
		right: 0;
		left: auto;
	}
	.galleria-thumbnails-container .disabled {
		opacity: .2;
		_filter: alpha(opacity=20);
		cursor: default;
	}
	.galleria-thumb-nav-left:hover,
	.galleria-thumb-nav-right:hover {
		opacity: 1;
		_filter: alpha(opacity=100);
		background-color: #111;
	}
	.galleria-thumbnails-container .disabled:hover {
		opacity: 0.2;
		_filter: alpha(opacity=20);
		background-color: transparent;
	}
	
	.galleria-carousel .galleria-thumb-nav-left,
	.galleria-carousel .galleria-thumb-nav-right {
		display: block;
	}
	.galleria-thumb-nav-left,
	.galleria-thumb-nav-right,
	.galleria-info-link,
	.galleria-info-close,
	.galleria-image-nav-left,
	.galleria-image-nav-right {
		background-image: url(sohoadmin/client_files/classic-map.png);
		background-repeat: no-repeat;
	}
	</style>
		<div style="position:relative; text-align:left; ">
		<div class="galcontent">    
			<div id="galleria">
			<?php
			$getimages = mysql_query("select * from photo_album_images where album_id='".$THIS_ID."' order by image_order asc");
			while($imgn = mysql_fetch_assoc($getimages)){
				echo "<img alt=\"".$imgn['caption']."\" src=\"images/".$imgn['image_name']."\"> \n";
			}
			?>
			</div>
		</div>
	</div>
		<script type="text/javascript">
		// Load the classic theme
		Galleria.loadTheme('sohoadmin/client_files/galleria.classic.js');
		//$('#galleria').galleria();
		$('#galleria').galleria({
			on_image: function( image, thumb ) {
				var gallery = this;
				// image is now the image element and gallery the instance
				$( image ).click( function() {               
					gallery.openLightbox();
					// this.$('image-nav-left,image-nav-right,counter').hide();
				})
			}
		});
		</script>
