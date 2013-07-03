<?php
error_reporting('341');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2013 Soholaunch.com, Inc.
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
require_once("../includes/product_gui.php");



# Delete file
if ( $_GET['delete_file'] != '' ) {
	unlink($_SESSION['docroot_path'].'/'.urldecode($_GET['delete_file']));
	$displayfile = preg_replace('/^(images|media)\//i','',urldecode($_GET['delete_file']));
	$report[] = $displayfile.' deleted';
}


# Start buffering output
ob_start();
?>

<style type="text/css">
#right-panel {
	min-width:800px;
}
#filebuttons {
	height:22px;
	top:118px;
	right:37px;
	width:330px;
	position:fixed;
	background-color:#FFF;
	z-index:1099;
}
#filebuttons1 {
	float:right;
}
#filebuttons2 {
	float:right;
	margin-right:20px;
}
#file_manager_module {
	width:100%;
	height:100%;
	margin-top:90px;
}
#right-panel h3 {
	height:34px;
	width:96%!important;
	min-width:800px;
	top:49px;
	position:fixed;
	background-color:#FFF;
	z-index:999;
}
#module_description_text {
	height:57px;
	top:83px;
	width:96%!important;
	position:fixed;
	background-color:#FFF;
	z-index:999;
	margin-bottom:6px!important;
}

#imgPreviewWithStyles {
    background: #D6D6D6;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    padding: 15px;
    z-index: 999;
    border: none;
    text-align:center;
}

/* Text below image */
#imgPreviewWithStyles span {
    color: black;
    font-weight:bold;
    text-align: center;
    display: block;
    padding: 10px 0 3px 0;
}

#imgPreviewWithStyles img {
    
    max-width:200px;
    width:auto;
    max-height:300px;
    height:auto;
}

</style>

<style type="text/css">
tr.row_bgcolor1 td,
tr.row_bgcolor2 td {
   border-bottom: 1px dashed #ccc;
   
   padding:0px;
   margin:0px;
}
tr.row_bgcolor1 td { background-color: #fff; }
tr.row_bgcolor2 td { background-color: #f8f9fd; }
</style>

<script type="text/javascript">
<!--

function URL_decode(url) {
	return decodeURIComponent(url.replace(/\+/g, ' '));
}

function killErrors() {
		return true;
	}

window.onerror = killErrors;

function MM_popupMsg(msg) { //v1.0
  alert(msg);
}

function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}

//-->
</script>





<script type="text/javascript">
(function(c){c.expr[':'].linkingToImage=function(a,g,e){return!!(c(a).attr(e[3])&&c(a).attr(e[3]).match(/\.(gif|jpe?g|png|bmp)$/i))};c.fn.imgPreview=function(j){var b=c.extend({imgCSS:{},distanceFromCursor:{top:10,left:10},preloadImages:true,onShow:function(){},onHide:function(){},onLoad:function(){},containerID:'imgPreviewContainer',containerLoadingClass:'loading',thumbPrefix:'',srcAttr:'href'},j),d=c('<div/>').attr('id',b.containerID).append('<img/>').hide().css('position','absolute').appendTo('body'),f=c('img',d).css(b.imgCSS),h=this.filter(':linkingToImage('+b.srcAttr+')');function i(a){return a.replace(/(\/?)([^\/]+)$/,'$1'+b.thumbPrefix+'$2')}if(b.preloadImages){(function(a){var g=new Image(),e=arguments.callee;g.src=i(c(h[a]).attr(b.srcAttr));g.onload=function(){h[a+1]&&e(a+1)}})(0)}h.mousemove(function(a){d.css({top:a.pageY+b.distanceFromCursor.top+'px',left:a.pageX+b.distanceFromCursor.left+'px'})}).hover(function(){var a=this;d.addClass(b.containerLoadingClass).show();f.load(function(){d.removeClass(b.containerLoadingClass);f.show();b.onLoad.call(f[0],a)}).attr('src',i(c(a).attr(b.srcAttr)));b.onShow.call(d[0],a)},function(){d.hide();f.unbind('load').attr('src','').hide();b.onHide.call(d[0],this)});return this}})(jQuery);
</script>







<script type="text/javascript">

function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function preview (image) {
	eval("MM_openBrWindow('site_files/prev_image.php?image="+image+"&dot_com=<?echo $dot_com; ?>','imagepreview','width=450,height=300,scrollbars=no,resizable=no');");
}

function download(image,lname) {
	var str = 'site_files/dlfile.php?name='+image+'&localname='+lname+'&dot_com=<? echo $dot_com; ?>';
	// eval("window.location(str);");
	eval("MM_openBrWindow('"+str+"','downloadfile','width=450,height=150');");

}


function update_status(text) {
	// STATUS UPDATE
}

function verify_delete(prikey) {
	//update_status('Verify Delete!');
	var r = document.getElementById('file_delete'+prikey).checked;
	if (r) {
	   //alert('soemthing');
		var tiny = window.confirm('<? echo lang("You have selected for this file to be deleted"); ?>.\n\n<? echo lang("Once you click \"Update Files\" you can not undo this process"); ?>.\n\n<? echo lang("Are you sure you wish to select this file for deletion"); ?>?');
	   	if (tiny != false) {
			// OK With client
	 	  } else {
			document.getElementById('file_delete'+prikey).checked=false;
			//update_status('Idle');
	  	 }
	}
}

function removeID(eleid){
    return (elem=document.getElementById(eleid)).parentNode.removeChild(elem);
}

function saving_updates() {
	show_hide_layer('Layer2','','hide','Layer1','','hide','Layer3','','show');
	$(':input', '#CURRENT').each(function() {
     	if(this.name.match(/^new_name/)){
     		var varnum = this.name.replace(/^new_name/,'');
     		var oldnamez = 'file'+varnum;
     		var newnamez = 'new_name'+varnum;
     		if(this.value==''){
     			var curCnt = document.getElementById('true_count').value;
     			//document.getElementById('true_count').value = eval(curCnt-1);
				removeID(newnamez);
				removeID(oldnamez);
			}
		}
		
	});
	
	document.CURRENT.submit();
}


function upload_now() {
	window.location="upload_files.php";
}

function toggle(targetid) {
  var isnow = document.getElementById(targetid).style.display;
  if ( isnow == 'block' ) {
     document.getElementById(targetid).style.display='none';
  } else {
     document.getElementById(targetid).style.display='block';
  }
}
var p = "File Manager";
parent.frames.footer.setPage(p);

parent.header.flip_header_nav('MAIN_MENU_LAYER');


function delete_file(fileName) {
	var displayFilename = URL_decode(fileName.replace(/^(images|media)\//i, ''));	
	var usure = confirm('Are you sure you want to delete '+displayFilename+'?');	
	if ( usure == true ) {
		document.location = 'site_files.php?delete_file='+fileName;
	}
}

</script>



<?php

#######################################################
####### Read Current Site Files Into Memory ###########
#######################################################

$count = 0;

$video_extensionsArr = array('mov', 'mpg', 'wmv', 'mpeg', 'flv', 'mp4', 'swf', 'rm');

# MEDIA/ -- Documents and custom includes
$directory = $_SESSION['docroot_path']."/media";
if (is_dir($directory)) {
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		
		if (strlen($files) > 2) {
			$count++;
			$tmp = "$directory/$files";
			$tmp_space = filesize($tmp);
			$tmp_srt = $files;
			$site_File[strtolower($files)] = $tmp_srt . "~~~media~~~$tmp_space~~~" . $files;
			preg_match('/([\d\s\w]+)$/i', $files, $out);
			$file_extension = $out[0];

			# Custom include scripts
			if ( eregi("\.inc", $files) || eregi("\.php", $files)){
				if($files!='index.php'){
	   			$incFile[$files]['size'] = $tmp_space;
	   			$incFile[$files]['name'] = $tmp_srt;
	   			$incFile[$files]['dir'] = "media";
	   		}

   		# Custom HTML
   		} elseif ( eregi("\.html", $files) || eregi("\.htm", $files) ) {
   			$htmlFile[$files]['size'] = $tmp_space;
   			$htmlFile[$files]['name'] = $tmp_srt;
   			$htmlFile[$files]['dir'] = "media";

   		# Custom web forms and text files
   		} elseif ( eregi("\.form", $files) || eregi("\.txt", $files) ) {
   			$formFile[$files]['size'] = $tmp_space;
   			$formFile[$files]['name'] = $tmp_srt;
   			$formFile[$files]['dir'] = "media";

   		# Video files
   		} elseif ( in_array($file_extension, $video_extensionsArr) ) {
   			$vidFile[$files]['size'] = $tmp_space;
   			$vidFile[$files]['name'] = $tmp_srt;
   			$vidFile[$files]['dir'] = "media";

   		# Spreadsheets
   		} elseif ( eregi("\.csv", $files) || eregi("\.xls", $files) ) {
   			$csvFile[$files]['size'] = $tmp_space;
   			$csvFile[$files]['name'] = $tmp_srt;
   			$csvFile[$files]['dir'] = "media";

   		# Documents, PowerPoint Presentations, & Adobe PDF files
   		} elseif ( eregi("\.doc", $files) || eregi("\.pdf", $files) || eregi("\.ppt", $files) ) {
   			$docFile[$files]['size'] = $tmp_space;
   			$docFile[$files]['name'] = $files;
   			$docFile[$files]['dir'] = "media";

   		# Unclassified files
   		} else {
   			$wtfFile[$files]['size'] = $tmp_space;
   			$wtfFile[$files]['name'] = $tmp_srt;
   			$wtfFile[$files]['dir'] = "media";
   		}

		}
	}
closedir($handle);
}


$num = 1;
# Images
$imgFile = array();
$directory = $doc_root."/images";
if (is_dir($directory)) {
   $handle = opendir("$directory");
	while ( $files = readdir($handle) ) {
		if ( strlen($files) > 2 && !is_dir($directory."/".$files) ) {
			$count++;
			$tmp = "$directory/$files";
			if ( eregi('"', $files) ) {
				$new_name = str_replace('"', '', $files);
				copy($tmp, $directory.'/'.$new_name);
				unlink($tmp);  
				$report[] = 'File <em>'.$files.'</em> contained invalid characters. A new version of the file has been created under the name <em>'.$new_name.'</em>. If you have placed this file on any pages, you should check those pages and re-place it.';
				$tmp = "$directory/$new_file";
				$files = $new_file;
				continue;
			}			
			$tmp_space = filesize($tmp);
			$tmp_srt = $files;
			$site_File[$files] = $tmp_srt . "~~~images~~~$tmp_space~~~" . $files;
			$imgFile[$files]['size'] = $tmp_space;
			$imgFile[$files]['name'] = $tmp_srt;
//			$SRT_IMG[$num] = $imgFile[$files]['name'];
			$imgFile[$files]['dir'] = "images";
			$num++;
		}
	}
closedir($handle);
//sort($SRT_IMG);
}
//echo testArray($imgFile); exit;


# Custom template HTML files
$directory = "$doc_root/tCustom";
if (is_dir($directory)) {
$handle = opendir("$directory");
	while ($files = readdir($handle)) {
		if (strlen($files) > 2) {
			$count++;
			$tmp = "$directory/$files";
			$tmp_space = filesize($tmp);
			$tmp_srt = $files;
			$site_File[$files] = $tmp_srt . "~~~tCustom~~~$tmp_space~~~" . $files;
			$tplFile[$files]['size'] = $tmp_space;
			$tplFile[$files]['name'] = $tmp_srt;
			$tplFile[$files]['dir'] = "tCustom";
		}
	}
closedir($handle);
}

//# CSV files
//$directory = "$doc_root/import";
//if (is_dir($directory)) {
//$handle = opendir("$directory");
//	while ($files = readdir($handle)) {
//		if (strlen($files) > 2) {
//			$count++;
//			$tmp = "$directory/$files";
//			$tmp_space = filesize($tmp);
//			$tmp_srt = $files;
//			$site_File[$files] = $tmp_srt . "~~~import~~~$tmp_space~~~" . $files;
//         $wtfFile[$files]['size'] = $tmp_space;
//         $wtfFile[$files]['name'] = $tmp_srt;
//         $wtfFile[$files]['dir'] = "import";
//		}
//	}
//closedir($handle);
//}

#######################################################

if ($count > 1) { sort($site_file); };
$file_count = count($site_file);


echo "<div id=\"filebuttons\">\n";
echo "	<button id=\"filebuttons1\" type=\"button\" class=\"greenButton\" onclick=\"saving_updates();\"><span><span>".lang("Update File Changes")."</span></span></button>\n";
echo "	<button id=\"filebuttons2\" TYPE=BUTTON CLASS=\"greenButton\" onclick=\"upload_now();\"><span><span>".lang("Upload New Files")." </span></span></button>\n";
//echo "<br/>   <font color=\"#FF0000\"><U>".lang("Remember")."</U>: ".lang("Changes and deletions are final and can not be undone.")."</font>\n";
echo "</div>\n";
?>
<div id="file_manager_module">
<!-- ======================================================================================================== -->
<!-- ======================================================================================================== -->
<!-- ======================================================================================================== -->

<DIV ID="Layer3" style="position:absolute; left:0px; top:40%; width:100%; height:110px; z-index:100; border: 2px none #000000; visibility: hidden; overflow: hidden">

  <table border=0 cellpadding=0 width=100% bgcolor=WHITE>
    <tr>
      <td align=center valign=middle class=text>
		<img src="site_files/upload.gif" width=156 height=30 border=0>
      </td>
    </tr>
  </table>

</DIV>

<!-- ======================================================================================================== -->
<!-- ======================================================================================================== -->
<!-- ======================================================================================================== -->
<div style="width:100%;height:">

<form method=post action="site_files/update_files.php" name="CURRENT" id="CURRENT">
<img src="spacer.gif" height=5 width=500>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
 <tr>
  <td align=center valign=top>
	<table border="0" cellspacing="0" cellpadding="5" width="100%">
    <tr>
     <td align="center" valign="top" class=text>
	   <table width="95%" border="0" cellspacing="0" cellpadding="3" align="center" class=text>
		 <tr>
		  <td align="left" valign="middle" class="text" style="text-align:left;">
		<?php
		if($_GET['success']!=''){
			echo "<span style=\"font-size:110%;\"><span style=\"color:green;\"><strong>".lang('Success!')."</strong></span> ".stripslashes($_GET['success'])."&nbsp;</span>\n";
			echo "<br/>";
		}
		?>		   
		  </td>
		 </tr>
		</table>
		
		<table cellpadding="0" cellspacing="0" border="0" width="98%" align="center" class="feature_group">
		 <tr>
		  <td valign="top">
		   <table cellpadding="3" cellspacing="0" border="0" width="100%">
    		 <tr>
      	  <td NOWRAP class="fgroup_title">
		      <img src="arrow.gif" width="17" height="13" align="absmiddle"><? echo lang("Current Site Files"); ?>
			  </td>
			  <td NOWRAP class="fgroup_title" style="text-align:right; padding-right:10px; width:177px;">
		  <!-- <button TYPE=BUTTON CLASS="greenButton" onclick="upload_now();"><span><span> <? echo lang("Upload New Files"); ?> </span></span></button> -->
			</td>
			 </tr>
			</table>
		  </td>
		 </tr>
		 <tr>
		  <td valign="top" align="center">

			<! -- ## HEADER SETUP ## -->
			<table cellpadding="0" cellspacing="0" width="100%" style="border: 0px solid #3C3C3C;" >

<?php
#--------------------------------------------------------------------------------
# CALCULATE USER-READABLE FILESIZE
# Accepts: size value, size/flag
# Returns: formatted size value ('size') | flag image if too big ('flag')
#--------------------------------------------------------------------------------
function sizeInfo($filesize, $gimme) {

   # Will contain tag for 'too big' image (if it's too big)
   $red_flag = "";

   # MB
   if ( $filesize >= 1048576 ) {
      $filesize = round($filesize / 1048576 * 100) / 100;
      if ( $filesize > 1 ) {
         $red_flag = "<img style=\"float:left;\" src=\"site_files/red_flag.gif\" width=10 height=10 border=0 hspace=5 align=absmiddle>";
      }
      $filesize = $filesize . "&nbsp;MB";

   # KB
   } elseif ( $filesize >= 1024 ) {
      $filesize = round($filesize / 1024 * 100) / 100;
      if ( $filesize > 500 ) {
         $red_flag = "<img style=\"float:left;\" src=\"site_files/red_flag.gif\" width=10 height=10 border=0 hspace=5 align=absmiddle>";
      }
      $filesize = $filesize . "&nbsp;KB";

   # Bytes
   } else {
      $filesize = $filesize . "&nbsp;Bytes";
   }

   if ( $gimme == "size" ) {
      return $filesize;
   } elseif ( $gimme == "flag" ) {
      return $red_flag;
   }
}

#--------------------------------------------------------------------------------
# DEFINE IMAGE PREVIEW OPTION
#--------------------------------------------------------------------------------
function prevImg($filedir, $filename) {

	if (preg_match("/\.(gif|jpg|jpeg|bmp|png|tif|tiff)$/i", $filename)){
      //$preview = "<img src=\"site_files/preview.gif\" width=21 height=21 border=0 alt=\"Preview Image\" onclick=\"preview('$filedir/$filename');\" style=\"cursor: pointer;\">";
		if(function_exists('smart_resize_image')){
			$preview = "<div onclick=\"document.location='site_files/resize_image.php?img=".urlencode($filename)."';\" style=\"width:25px;position:relative;margin-top:-10px;padding:0;cursor: pointer;\">\n";
			$preview .= "	<div style=\"position:absolute;top:0;left:0;height:18px;width:29px;background:url(site_files/preview.png) no-repeat -2px 0px;filter:alpha(opacity=40);opacity:.40;\">&nbsp;</div>\n";
			$preview .= "	<div style=\"position:absolute;top:0;left:0;height:18px;width:29px;\">\n";
			$preview .= "		<a href=\"site_files/resize_image.php?img=".urlencode($filename)."\" style=\"text-decoration:none;border-bottom:1px dashed #EAA510;font-size:8px;font-weight:normal;color:#000;\"><span style=\"font-weight:bold;color:#000;\">E</span>dit</a>";
			$preview .= "	</div>\n";
			$preview .= "</div>\n";			
		} else {
			$preview = "<img src=\"site_files/preview.gif\" width=21 height=21 border=0 alt=\"Preview Image\" onclick=\"preview('$filedir/$filename');\" style=\"cursor: pointer;\">";
		}
	} else {
		$preview = "<img src=\"site_files/dl.gif\" width=21 height=21 border=0 alt=\"Download File\" onclick=\"download('$filedir/$filename','$filename');\" style=\"cursor: pointer;\">";
		if (preg_match("/\.swf$/i", $filename)) { $preview = ""; $mouseit = ""; }
	}

	return $preview;
}

# Total file count
$c = 0;
$d = 0;
#--------------------------------------------------------------------------------
# SHOW FOLDERS FOR EACH FILE TYPE
#--------------------------------------------------------------------------------
function mkFolder($title, $filearray) {
   global $c;
   global $d;
   uksort($filearray, "strnatcasecmp");
   $the_file_count=count($filearray);
   # For js show/hide folder div
   $toggle_id = str_replace(" ", "_", strtolower($title));

if($the_file_count > 0){
   # File group folder
   echo " <tr>\n";
   //echo "  <td style=\"border: 1px solid #3C3C3C;\" class=\"text\">\n";
   echo "  <td style=\"border: 1px solid #C8C7C7;\" class=\"text\">\n";
   
   echo "   <div style=\"align:left;text-align:left;\" width=\"100%\" class=\"col_title\">\n";
   echo "    <table cellpadding=\"0\" cellspacing=\"0\" class=\"text\">\n";
   echo "     <tr>\n";
   echo "      <td><img src=\"../includes/display_elements/graphics/icon-folder-col_title.gif\"></td>\n";   
   echo "      <td style=\"text-align:left; padding-left: 10px;\"><a href=\"javascript:void(0);\" onClick=\"toggle('".$toggle_id."')\" class=\"darkbg\">".lang($title)."</a>&nbsp;<span style=\"color:#6B6B6B;\">[".$the_file_count."]</span></td>\n";
   echo "     </tr>\n";
   echo "    </table>\n";
   echo "   </div>\n";
   echo "   <div id=\"".$toggle_id."\" style=\"width:100%; display: none;\" >\n";
   echo "   <table class=\"text\" cellpadding=\"4\" cellspacing=\"0\"  style=\"width:100%; background-color: #FFF; \">\n";
   
   echo "    <tbody style=\"width:100% !important;\"><tr>\n";
   echo "     <td class=\"col_sub\" style=\"width:20px; padding-left: 0px; text-align: left;\">&nbsp;</td>\n";
   
   
   if($d == 0){
   		echo "     <td style=\"width:320px;text-align:left;padding-left:15px;\" class=\"col_sub\">".lang("File Name")." (<i>".lang('hover to preview')."</i>)</td>\n";
   } else {
		echo "     <td style=\"width:320px;text-align:left;padding-left:15px;\" class=\"col_sub\">".lang("File Name")."</td>\n";	
   }
   
   echo "     <td style=\"width:160px;\" class=\"col_sub\">".lang("Rename")."</td>\n";
   echo "     <td style=\"width:50px;\" class=\"col_sub\">".lang("Delete")."</td>\n";
   echo "     <td style=\"width:70px;\" class=\"col_sub\">".lang("File Size")."</td>\n";
   echo "     <td class=\"col_sub\" style=\"text-align:left;\">&nbsp;</td>\n";
   echo "    </tr>\n";

   foreach ( $filearray as $key=>$file ) {
      if ( $trbgclass == "row_bgcolor1" ) { $trbgclass = "row_bgcolor2"; } else { $trbgclass = "row_bgcolor1"; }
      echo "    <tr class=\"$trbgclass\">\n";

      # Preview icon
	echo "     <td style=\"padding-left: 5px; text-align: left; cursor: hand;\">\n";
	echo "      ".prevImg($file['dir'], $file['name'])."\n";
	echo "     </td>\n";     	


      # File name
      //echo "     <td style=\"color: #000099;\">\n"; 
      echo "     <td style=\"text-align:left;color: #000000;padding-top:0;padding-bottom:0;padding-left:15px;\">\n";
		if($file['dir']=='images'){
      		echo "      <div style=\"padding-top:3px;height:20px;width:100%;text-decoration:none;cursor: pointer;\" href=\"../../../".$file['dir']."/".$file['name']."\" onClick=\"document.location='site_files/resize_image.php?img=".urlencode($file['name'])."';\">".$file['name']."</div>\n";
		} else {
     		echo "      ".$file['name']."\n";	
		}
      echo "<input type=hidden id=\"file".$c."\" name=\"file".$c."\" value=\"".$file['dir']."/".$file['name']."\">\n";
      echo "     </td>\n";
      
      # If demo site, dont allow rename or file delete
      if($_SESSION['demo_site'] == "yes"){ $demo_disabled = "disabled=\"true\""; }

      # Rename field
      echo "     <td style=\"text-align:left;\">\n";
      echo "      <input class=\"text\" type=\"text\" id=\"new_name".$c ."\" name=\"new_name".$c ."\" size=\"22\" ".$demo_disabled.">\n";
      echo "     </td>\n";

      # Delete checkbox
      echo "     <td style=\"text-align:left;\">\n";
      echo "      [<span class=\"red hand\" onclick=\"delete_file('".$file['dir']."/".urlencode($file['name'])."');\">X</span>]\n";
      echo "     </td>\n";
//      echo "     <td>\n";
//      echo "      <input type=\"checkbox\" id=\"file_delete".$c."\" name=\"file_delete".$c."\" value=\"yes\" onclick=\"verify_delete('".$c."');\" ".$demo_disabled.">\n";
//      echo "     </td>\n";

      # File size & too big flag
      //echo "     <td  style=\"color: #000099;\">\n";
      echo "     <td style=\"text-align:left;color: #666565;\">\n";
      
      echo "      ".sizeInfo($file['size'], "size")."\n";
      echo "     </td>\n";

      echo "     <td style=\"text-align:left;\">\n";
      echo "      ".sizeInfo($file['size'], "flag")."&nbsp;\n";
      echo "     </td>\n";

      echo "    </tr>\n";

      # Increment file counter
      $c++;
   }
   echo "   </tbody></table></div>\n";
   echo "  </td>\n";
   echo " </tr>\n";
}

	$d = 1;

}

# Now compile each folder listing
//sort($imgFile);
//echo testArray($imgFile);

mkFolder(lang("Images"), $imgFile);
mkFolder(lang("Documents, Presentations, and Adobe PDFs"), $docFile);
mkFolder(lang("Video Files"), $vidFile);
mkFolder(lang("Spreadsheets and CSV files"), $csvFile);
mkFolder(lang("Custom web forms and text files"), $formFile);
mkFolder(lang("Custom HTML includes"), $htmlFile);
mkFolder(lang("Custom HTML template files"), $tplFile);
mkFolder(lang("Custom PHP scripts"), $incFile);
mkFolder(lang("Unclassified files"), $wtfFile);

//echo "<tr>\n<td colspan=\"6\" style=\"border-top: 1px solid #3C3C3C;\" class=\"text\">\n</td>\n</tr>\n";
echo "</table>\n";
echo "</td></tr></table>\n";

echo "<input type=hidden id=\"true_count\" name=\"true_count\" value=\"".$c."\">\n";
echo "<table width=\"85%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" align=\"center\">\n";
echo " <tr> \n";
echo "  <td class=text align=\"right\" valign=\"middle\">\n";


if($_SESSION['demo_site'] == "yes"){
   echo "   <font color=\"#FF0000\"><U>".lang("Files cannot be changed in the demo version")."</U></font>\n";
}else{
   echo "   <font color=\"#FF0000\"><U>".lang("Remember")."</U>: ".lang("Changes and deletions are final and can not be undone.")."</font>\n";
   echo "   <br/><br/><br/>\n";
   echo "   <button type=\"button\" class=\"greenButton\" onclick=\"saving_updates();\"><span><span>".lang("Update File Changes")."</span></span></button>\n";
}

echo "  </td>\n";
echo " </tr>\n";
echo "</table>\n";


echo "</form>\n";

echo "  </td>\n";
echo " </tr>\n";
echo "</table>\n";
echo "  </td>\n";
echo " </tr>\n";
echo "</table>\n";
//echo "  </td>\n";
//echo " </tr>\n";
//echo "</table>\n";

echo "<script type=\"text/javascript\">\n";

if($_GET['show']=='images'){	
	echo "	toggle('images');\n";
	//echo "window.attachEvent(\"onload\", toggle('images'));\n";
	//echo "window.onload = toggle('images');\n";	
}

echo '$(\'div\').imgPreview({	'."\n";
echo '    containerID: \'imgPreviewWithStyles\','."\n";
echo '    imgCSS: '."{\n";
echo '        // Limit preview size:'."\n";
//echo '        width: \'100%\''."\n";
echo '        width: \'auto\','."\n";
echo '        maxWidth: \'200px\','."\n";
echo '        height: \'auto\','."\n";
echo '        maxHeight: \'300px\''."\n";
echo '    },'."\n";
echo '    // When container is shown:'."\n";
echo '    onShow: function(link){'."\n";
echo '        $(\'<span>\' + $(link).text() + \'</span>\').appendTo(this);'."\n";
echo '    },'."\n";
echo '    // When container hides: '."\n";
echo '    onHide: function(link){'."\n";
echo '        $(\'span\', this).remove();'."\n";
echo '    }'."\n";
echo '});'."\n";

echo "</script>\n";
echo "</div>\n";
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();
//echo $module_html; exit;
//$instructions = "<img src=\"site_files/dl.gif\" width=\"21\" height=\"21\" border=\"0\" alt=\"Download File\" align=\"absmiddle\"> ".lang("Media, document, and code files may be downloaded by clicking on the arrow next to the filename.<br/>");
//$instructions .= "<img src=\"site_files/preview.gif\" width=\"21\" height=\"21\" border=\"0\" alt=\"Preview Image\" align=\"absmiddle\"> ".lang("Image files can be viewed and saved by clicking the preview icon next to the filename.<br/>");
//$instructions .= "<img src=\"site_files/red_flag.gif\" width=\"10\" height=\"10\" border=\"0\" hspace=\"5\" align=\"absmiddle\"> ".lang("Indicates image should be reduced in filesize. This file causes slow load-times when viewing your web site.<br/>");

$instructions = "<img src=\"site_files/dl.gif\" width=\"21\" height=\"21\" border=\"0\" alt=\"Download File\" align=\"absmiddle\">".lang("Media, document, and code files may be downloaded by clicking on the arrow next to the filename").".<br/>";
$instructions .= "<img src=\"site_files/preview.gif\" width=\"21\" height=\"21\" border=\"0\" alt=\"Preview Image\" align=\"absmiddle\"> ".lang("Image files can be viewed and saved by clicking the preview icon next to the filename").".<br/>";
$instructions .= "<img src=\"site_files/red_flag.gif\" width=\"10\" height=\"10\" border=\"0\" hspace=\"5\" align=\"absmiddle\"> ".lang("Indicates image should be reduced in filesize. This file causes slow load-times when viewing your web site").".<br/>";


# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("File Manager");
$module->add_breadcrumb_link(lang("File Manager"), "program/modules/site_files.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/file_manager-enabled.gif";
$module->heading_text = lang("File Manager");
$module->description_text = $instructions;
$module->good_to_go();
?>