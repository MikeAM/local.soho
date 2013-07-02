<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
ini_set("memory_limit", "-1");

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
require_once('../../includes/product_gui.php');

if($_POST['action']=='resize'){
	
	if($_POST['newname']!=''){
		$_POST['newname'] = $_SESSION['doc_root'].'/images/'.basename(str_replace(' ', '_', $_POST['newname']));
		//$ext = end(explode(".",$_POST['newname']));
		if(end(explode(".",$_POST['newname'])) != end(explode(".",$_POST['img']))){
			$_POST['newname'] = $_POST['newname'].'.'.end(explode(".",$_POST['img']));
		}

		$rotate = $_POST['rotated'];
		if($rotate == '360'){ $rotate = 0; }
		$new_x = $_POST['new_x'];
		$new_y = $_POST['new_y'];
		
		//$_POST['newname'] = 'browser';
		$propor = false;

		
		if(smart_resize_image($_SESSION['doc_root'].'/images/'.$_POST['img'], $_POST['new_width'], $_POST['new_height'], $propor, $_POST['newname'], false, $rotate, $new_x, $new_y,$_POST['sel_w'],$_POST['sel_h'])){
			$success= lang('Resized').' '.basename($_POST['newname']).'.';
		}
	}
	header("Location: ../site_files.php?success=$success&=SID");
	exit;
	
} elseif($_POST['action']=='preview'){
	ob_start();
	echo "<link rel=\"stylesheet\" href=\"resize_image.css\" type=\"text/css\" media=\"all\" />\n";
	echo "<div style=\"position:relative; text-align:left; padding:10px; width:100%; display:block; z-index:2000;\">\n";
	echo "	<form name=\"preview\" method=\"POST\" action=\"#\">\n";
	foreach($_POST as $v=>$va){
		if($v != 'action'){
			echo "<input type=\"hidden\" name=\"".$v."\" value=\"".$va."\">\n";
		}
	}
	echo "<input type=\"hidden\" name=\"action\" value=\"resize\">\n";
		
	$rotate = $_POST['rotated'];
	if($rotate == '360'){ $rotate = 0; }
	$new_x = $_POST['new_x'];
	$new_y = $_POST['new_y'];
	$resizestring = 'preview_resize.php?1='.$_POST['img'].'&2='.$_POST['new_width'].'&3='.$_POST['new_height'].'&4=false&5=browser&6=false&7='.$rotate.'&8='.$new_x.'&9='.$new_y.'&10='.$_POST['sel_w'].'&11='.$_POST['sel_h'];


	echo "<img style=\"width:".$_POST['new_width']."px; height:".$_POST['height']."px; align:left;\" src=\"".$resizestring."\">\n";
	
//	echo "	<div style=\"padding:0px 20px 10px 20px; clear:both;\"> \n";
//	echo "	Save&nbsp;As:&nbsp;<input style=\"width:250px; border-width: 0px 0px 1px 0px; border-color: #CCCCCC; border-style: solid; background-color: transparent;\" id=\"newname\" name=\"newname\" type=\"text\" value=\"".$img."\">\n";
//	echo "	</div>  \n";
	echo "	<div class=\"cleared\"></div>\n";
	
	if(file_exists($_SESSION['doc_root'].'/images/'.$_POST['newname'])){
		echo "<p><span style=\"color:red;\">*</span>".lang("Saving this image will overwrite the old image.")."</p>\n";
		$instructions = lang("Click \"Save Changes\" to save the changes to this image.").".<br/>";	
	} else {
		$instructions = lang("Click \"Save Image\" to save this image.").".<br/>";
	}
	
	echo "	<div style=\"padding-top:10px;\">\n";
	echo "		<span class=\"button-wrapper\">\n";
	echo "		<button style=\"width: 105px;\" class=\"blueButton\" type=\"button\" onClick=\"document.location='resize_image.php?img=".$img."';\"><span><span>".lang('Cancel')."</span></span></button>\n";
	echo "		</span>\n";
	echo "		&nbsp;&nbsp;&nbsp;&nbsp;\n";
	echo "		<span class=\"button-wrapper\" >\n";
	if(file_exists($_SESSION['doc_root'].'/images/'.$_POST['newname'])){
		echo "			<button type=\"button\" class=\"greenButton\" onClick=\"document.preview.submit();\"><span><span>Save Changes</span></span></button>\n";
	} else {
		echo "			<button type=\"button\" class=\"greenButton\" onClick=\"document.preview.submit();\"><span><span>Save Image</span></span></button>\n";	
	}
	echo "		</span>\n";
	echo "	</div>\n";
	echo "</form>\n</div>\n";
	

	$module_html = ob_get_contents();
	ob_end_clean();
	$img = basename($_POST['newname']);
	$heading_text = lang("Preview of ".$img);
	
	
	
} else {

	ob_start();
	//echo "<!DOCTYPE html>\n";
	echo "<html>\n";
	echo "<head>\n";
	echo "	<meta charset=\"UTF-8\" />\n";
	//echo "	<script type=\"text/javascript\" src=\"../../../client_files/jquery.min.js\"></script>\n";
	echo "	<script type=\"text/javascript\" src=\"resize.jquery.js\"></script>\n";
	echo "	<link rel=\"stylesheet\" href=\"resize_image.css\" type=\"text/css\" media=\"all\" />\n";
	echo "</head>\n";
	echo "<body>\n";
	
	if($_REQUEST['img'] != ''){
		//echo $img = $_SESSION['doc_root'].'/images/'.basename($_REQUEST['img']);
		$img = basename($_REQUEST['img']);
		$imgpath = '../../../../images/'.basename($img);
	}
	$size_ar = getimagesize($imgpath);
	
	echo "<script type=\"text/javascript\">\n";
	echo "$(document).ready(function(){\n";
	echo "	var cropzoom = $('#crop_container').cropzoom({\n";
	$ww = ($size_ar['0'] + 64);
	$hh = ($size_ar['1']);
	//$hh = ($size_ar['1']+76);
	echo "		width:".($size_ar['0']+2).",\n";
	echo "		height:".($size_ar['1']+2).",\n";

	//echo "		bgColor: '#E8E8E8',\n";
	echo "		bgColor: 'transparent',\n";
	echo "		enableRotation:true,\n";
	echo "		enableZoom:true,\n";
	echo "		zoomSteps:1,\n";
	echo "		rotationSteps:90,\n";
	echo "		expose:{\n";
	echo "			slidersOrientation:'vertical',\n";
	echo "			zoomElement: '#zoom',\n";
	echo "			rotationElement: '#rot'\n";
	
	echo "		},\n";
	
	echo "		selector:{        \n";	
	echo "			centered:false,\n";
	echo "			x:0,\n";
	echo "			y:0,\n";
	echo "			w:".$size_ar['0'].",\n";
	echo "			h:".$size_ar['1'].",\n";
	echo "			borderColor:'blue',\n";
	echo "			borderColorHover:'yellow',\n";
	echo "			startWithOverlay: true,\n";
	echo "			showPositionsOnDrag: true,\n";
	echo "			showDimetionsOnDrag: false,\n";
	echo "			hideOverlayOnDragAndResize: false\n";
	echo "		},\n";
	echo "		image:{\n";
	echo "			source:'".$imgpath."',\n";
	echo "			width:".$size_ar['0'].",\n";
	echo "			height:".$size_ar['1'].",\n";
	echo "			startZoom:100,\n";
	echo "			minZoom:1,\n";
	echo "			maxZoom:99,\n";
	echo "			snapToContainer:true\n";
	echo "		}\n";
	echo "	});\n";


	echo "});\n";
	echo "</script>\n";
	
	echo "<div style=\"padding:22px 10px 10px 10px;\" class=\"Post-inner\">\n";
	
	echo "	<form style=\"display:inline;\" name=\"resizethis\" action=\"#\" method=\"POST\">\n";
	echo "	<input name=\"action\" type=\"hidden\" value=\"preview\">\n";
	echo "	<input id=\"img\" name=\"img\" type=\"hidden\" value=\"".$img."\">\n";
	
	echo "	<input id=\"rotated\" name=\"rotated\" type=\"hidden\" value=\"0\">\n";
	echo "	<input id=\"orig_width\" name=\"orig_width\" type=\"hidden\" value=\"".$size_ar['0']."\">\n";
	echo "	<input id=\"orig_height\" name=\"orig_height\" type=\"hidden\" value=\"".$size_ar['1']."\">\n";
	echo "	<input id=\"new_width\" name=\"new_width\" type=\"hidden\" value=\"".$size_ar['0']."\">\n";
	echo "	<input id=\"new_height\" name=\"new_height\" type=\"hidden\" value=\"".$size_ar['1']."\">\n";

	echo "	<input id=\"new_x\" name=\"new_x\" type=\"hidden\" value=\"0\">\n";
	echo "	<input id=\"new_y\" name=\"new_y\" type=\"hidden\" value=\"0\">\n";
	
	echo "	<input id=\"sel_w\" name=\"sel_w\" type=\"hidden\" value=\"".$size_ar['0']."\">\n";
	echo "	<input id=\"sel_h\" name=\"sel_h\" type=\"hidden\" value=\"".$size_ar['1']."\">\n";

	
	echo "	<div class=\"PostContent\" style=\"min-height:250px;\">\n";
	//echo "		<div style=\"position:relative; width:".($ww+5)."px;\" id=\"boxes\">\n";
	echo "		<div style=\"position:relative; width:".($ww+5)."px;\" id=\"boxes\">\n";
	
	echo "			<div id=\"wow\" style=\"position:absolute;overflow:hidden; left:31px;top:21;width:".($ww-62)."px;height:".$hh."px;border-width: 0px 0px 0px 0px; border-style: solid; border-color: #FFFFFF; background-color:#CCCCCC;z-index:0;\" >\n";
	echo "			</div>\n";
	
	//width:250px;
	
	
	echo "			<div id=\"topcontainer\" style=\"clear:none;position:relative; height:20px; background-color:#FFFFFF;z-index:2; white-space: nowrap;\">\n";
	echo "				<div style=\"font-size:13px;  display:inline; text-align:center; padding-left:31px;\">\n";
	echo "					width:&nbsp;<div id=\"display_width\" style=\"display:inline; font-weight:bold; \">".$size_ar['0']."</div><strong>px</strong>\n";
	echo "					&nbsp;&nbsp;&nbsp;height:&nbsp;<div id=\"display_height\" style=\"font-weight:bold; display:inline;\">".$size_ar['1']."</div><strong>px</strong>\n";
	echo "				</div>\n";
	echo "			</div>\n";
echo "			<img src=\"../spacer.gif\" style=\"float:left;width:1px; height:276px;\">\n";	
	//echo "			<div style=\"margin-left:31px;overflow:hidden;background-color:#FFFFFF;width:".$size_ar['0']."px;height:".$size_ar['1']."px; z-index:1\" id=\"crop_container\">";
	echo "			<div style=\"margin-left:31px;overflow:hidden;background-color:#FFFFFF;z-index:1\" id=\"crop_container\">";
	echo "			</div>\n";
	

	
	echo "			<div id=\"zoom\">\n</div>\n";
	echo "			<div id=\"rot\">\n</div>\n";
	echo "		</div>\n";
		
	echo "	</div>  \n";
	echo "</div>\n";
	echo "	<div class=\"cleared\" style=\"clear:right;width:100%;\"></div>\n";
//	echo "		<div id=\"savecontainer\" style=\"position:relative; display:block; width:100%; z-index:2000;\">\n";
	echo "		<div id=\"savecontainer\" style=\"position:relative; display:block;  z-index:2000;\">\n";
	echo "			<div style=\"padding:0px 20px 10px 20px;\"> \n";
	echo "				Image&nbsp;Name:&nbsp;<input style=\"width:250px; border-width: 0px 0px 1px 0px; border-color: #CCCCCC; border-style: solid; background-color: transparent;\" id=\"newname\" name=\"newname\" type=\"text\" value=\"".$img."\">\n";
	echo "				</form>\n";
	echo "			</div>  \n";
	echo "			<span class=\"button-wrapper\">\n";
	echo "				<span class=\"l\"> </span>\n";
	echo "				<span class=\"r\"> </span>\n";
	echo "				<button style=\"width: 105px;\" class=\"blueButton\" type=\"button\" onClick=\"document.location='../site_files.php?show=images';\"><span><span>".lang('Cancel')."</span></span></button>\n";
	echo "			</span>\n";
	echo "			&nbsp;&nbsp;&nbsp;&nbsp;\n";
	echo "			<span class=\"button-wrapper\" >\n";
	echo "				<span class=\"l\"> </span>\n";
	echo "				<span class=\"r\"> </span>\n";
	echo "				<button class=\"greenButton\" type=\"button\" onClick=\"document.resizethis.submit();\"><span><span>".lang('Preview Changes')."</span></span></button>\n";
	echo "			</span>\n";
	echo "		</div>\n";
	
	
	echo "</div>\n";
	echo "</body>\n";
	echo "</html>\n";
	
	$module_html = ob_get_contents();
	ob_end_clean();
	
	$heading_text = lang("Editing ".$img);
	$instructions = lang("Resize the image by dragging the left slider up and down.")."<br/>".lang("Rotate the image by dragging the right slider up and down.")."<br/>".lang("Crop the image by dragging the bottom right corner to resize and move the crop selector.")."<br/>";
	
}

	$module = new smt_module($module_html);
	
	$module->meta_title = lang("Edit Image: ".$img);
	$module->add_breadcrumb_link(lang("File Manager"), "program/modules/site_files.php");
	$module->add_breadcrumb_link(lang("Edit Image"), "program/modules/site_files/resize_image.php?img=".$img);
	$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/file_manager-enabled.gif";
	$module->heading_text = $heading_text;	
	$module->description_text = $instructions;
	$module->good_to_go();
	

?>
