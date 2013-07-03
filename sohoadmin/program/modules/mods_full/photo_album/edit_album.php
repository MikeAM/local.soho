<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '' || $_REQUEST['_SESSION'] != '') { exit; }
ini_set("memory_limit", "-1");
#=====================================================================================
# Soholaunch(R) Site Management Tool
#
# Homepage:      http://www.soholaunch.com
# Release Notes: http://wiki.soholaunch.com
#
# module and keep it's look consistent with the rest of the product
#=====================================================================================

error_reporting(E_PARSE);
session_start();

require_once("../../../includes/product_gui.php");

$id = $_REQUEST['id'];
$gn = mysql_query("select album_name from photo_album where prikey='".$id."'");
$gna = mysql_fetch_assoc($gn);
$album_name = $gna['album_name'];

if ($id == "") {
	header("Location: photo_album.php?=SID");
	exit;
}
if(count($_POST['image_name']) > 0){
	$l = 0;
	$rl = 1;
	$insrt = "insert into photo_album_images (album_id, image_order, image_name, caption, link) values ";
	while($l < count($_POST['image_name'])){
		if($_POST['image_name'][$l] != ''){
			$insrt .= "('".$id."', '".$rl."', '".slashthis($_POST['image_name'][$l])."', '".slashthis($_POST['caption'][$l])."', '".slashthis($_POST['link'][$l])."'), ";
			++$rl;
		}
		++$l;	
	}
	$insrt = preg_replace('/, $/', '', $insrt);
	mysql_query("delete from photo_album_images where album_id='".$id."'");
	mysql_query($insrt);
}
ob_start();

echo "<script type=\"text/javascript\">	\n";

echo "$(function () {\n";
echo "	$('body').on('change keyup', 'select.arrowsel', function () {\n";
echo "		if($(this).children(\"option:selected\").attr('value')!=''){\n";
echo "			update_demo(this);\n";
echo "		}\n";
echo "	}).change();\n";
echo "});\n";

echo "function update_demo(disguy){	\n";
 
echo "	var valuez = disguy.value;\n";
echo "	disguy.options[disguy.selectedIndex].defaultSelected = true;\n";
echo "	var other = disguy.id.substr(5);		\n";
echo "	if(valuez!=''){\n";
echo "		eval(\"document.getElementById('DEMO\"+other+\"').innerHTML='<img src=http://".$_SESSION['this_ip']."/images/\"+valuez+\" style=\\\"max-height:60px;\\\" border=1 align=absmiddle>';\");\n";
echo "	} else { \n";
echo "		eval(\"document.getElementById('DEMO\"+other+\"').innerHTML='<img src=\\\"http://".$_SESSION['this_ip']."/sohoadmin/program/spacer.gif\\\" style=\\\"max-height:60px;\\\" border=0 align=absmiddle>';\");\n";
echo "	} \n";
echo "} // End Func \n";
echo "</script>\n";

echo "<style>\n";
echo ".palbum img { max-width:110px; }\n";
echo "</style>\n";
echo "<div class=\"palbum\" style=\"position:relative; width:100%;\">\n";
$gq = mysql_query("select * from photo_album_images where album_id='".$id."' order by image_order asc");

while($v2 = mysql_fetch_assoc($gq)){
	//echo testArray($v2['atr']);
	$image = $v2['image_name'];
	$caption = $v2['caption'];
	$link = $v2['link'];
	$gall_ar[] = array('image'=>$image, 'caption'=>$caption, 'link'=>$link);
}

if(count($gall_ar) == 0){
	$gall_ar[] = array('image'=>'', 'caption'=>'','link'=>'');
}

$xc = 1;

$filnames = array();
$filePath = array();
$fileLinks = array();

echo "	<form name=\"selectimages\" action=\"#\" method=\"post\">\n";
echo "	<input type=\"hidden\" name=\"id\" value=\"".$id."\"/>\n";
echo "	<div id=\"bigdiv\" style=\"position:relative; width:620px; clear:right;\">\n";
$img_dd = "\n		<div style=\"float:left;  width:470px; height:62px;\">\n";
$img_dd .= "			<p style=\"margin:0px; padding:0px;\">Image:&nbsp;&nbsp;<select id=image".$xc." name=\"image_name[]\" style=\"font-size: 10px; width: 402px; background: none repeat scroll 0% 0% rgb(239, 239, 239); color: darkblue;\" onchange=\"update_demo(this);\" class=\"arrowsel\">\n";
$img_dd .= "      		<option value=\"\">[Select Image]</option>\n";

foreach(glob($_SESSION['doc_root']."/images/*") as $keyz){
	if(strtolower(array_pop(explode('.',$keyz)))!='html'){
		$key_ar[]=$keyz;
	}
}
natcasesort($key_ar);
foreach($key_ar as $key){
	$filePath[] = $key;
	$key = basename($key);
	$filenames[] = $key;
	$img_dd .=  "      <option value=\"".$key."\">".$key."</option>\n";		
}

$img_dd .= "			</select></p>\n";
$img_dd .= "			<p style=\"margin:3px 0px 0px 0px; padding:0px;\">Caption:&nbsp;<input name=\"caption[]\" id=caption".$xc." value=\"\" class=\"text\" style=\"width: 400px;\" type=\"TEXT\"></p>\n";
$img_dd .= "			<p style=\"margin:3px 0px 0px 0px; padding:0px;\">Link:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name=\"link[]\" id=link".$xc." value=\"\" class=\"text\" style=\"width: 400px;\" type=\"TEXT\"></p>\n";
$img_dd .= "			<span style=\"float:right; padding-top:3px; padding-right:18px;\">[<a style=\"color:#BF0000;\" href=\"javascript:void(0);\" onclick=\"removediv(this.parentNode.parentNode)\">Remove Image</a>]</span>\n";
$img_dd .= "		</div>\n";
$img_dd .= "		<div style=\"float:left; padding:2px 0px 2px 0px;\">\n";

foreach($gall_ar as $gvar=>$gval){
	$img_dd2 = str_replace('value="'.$gval['image'].'"', 'value="'.$gval['image'].'" selected', $img_dd);
	$img_dd2 = str_replace('id=image1', 'id=image'.$xc, $img_dd2);
	$img_dd2 = str_replace("name=\"caption[]\" id=caption1 value=\"\"", "name=\"caption[]\" id=caption".$xc." value=\"".$gval['caption']."\"", $img_dd2);
	$img_dd2 = str_replace("name=\"link[]\" id=link1 value=\"\"", "name=\"link[]\" id=link".$xc." value=\"".$gval['link']."\"", $img_dd2);
	if($xc == 1){
		echo str_replace("<span style=\"float:right; padding-top:3px; padding-right:18px;\">[<a style=\"color:#BF0000;\" href=\"javascript:void(0);\" onclick=\"removediv(this.parentNode.parentNode)\">Remove Image</a>]</span>", '', $img_dd2);
	} else {
		echo $img_dd2;
	}
	echo "<span id=\"DEMO".$xc."\">\n";
	if($gval['image'] != ''){
		echo "<img src=\"http://".$_SESSION['this_ip']."/images/".$gval['image']."\" style=\"max-height:60px;\" border=1 align=absmiddle>\n";
	} else {
		echo "<img src=\"http://".$_SESSION['this_ip']."/sohoadmin/program/spacer.gif\" style=\"max-height:60px;\" border=0 align=absmiddle>\n";		
	}
	echo "</span><br/>\n";
	++$xc;	
	echo "</div>\n";
}



echo "</div>\n";

echo "<div style=\"clear:right;\">&nbsp;</div>\n<div class=\"nomar_top\" style=\"font-size:120%;width:100%;white-space:nowrap;float:left;\">[<span style=\"white-space:nowrap;\" class=\"green uline hand\" onclick=\"addDiv();\">+ Add Another Image</span>]\n";
echo "<input type=hidden id=\"imgcount\" value='".($xc+1)."'>\n";
echo "<p style=\"padding-left:470px; padding-top:5px;\">\n";
//echo "<input class=\"btn_save\" onmouseover=\"this.className='btn_saveon';\" onmouseout=\"this.className='btn_save';\" type=\"submit\" value=\"Save Album\" />\n";
echo "<button TYPE=button CLASS=\"greenButton\" onClick=\"document.selectimages.submit();\"><span><span>".lang("Save Album")."</span></span></button>\n";

echo "</p>\n";
echo "</form>\n";

echo "</div>\n";

echo "<script type=\"text/javascript\">	\n";
echo "function addDiv(){\n";
echo "	var mydiv = document.getElementById(\"bigdiv\"); \n";
echo "	var imgc = parseInt(document.getElementById('imgcount').value);\n";
echo "	document.getElementById('imgcount').value = parseInt(imgc+1);\n";
echo "	var newcontent = document.createElement('div'); \n";
echo "	newcontent.innerHTML = '".str_replace("id=image1 ", "id=image'+imgc+' ", str_replace("'", "\'", str_replace("\n", '\n', str_replace("name=\"caption[]\" id=caption1 value=\"\"", "name=\"caption[]\" id=caption'+imgc+' ", str_replace('selected="selected"','',$img_dd)))))."<span id=\"DEMO'+imgc+'\"></span></div><br/>';";
echo "    while (newcontent.firstChild) { \n";
echo "        mydiv.appendChild(newcontent.firstChild); \n";
echo "    } \n";
echo "}\n";

echo "function removediv(tdivname){\n";
echo "	if(tdivname.nextSibling.tagName == 'div'){ \n";
echo "		tdivname.nextSibling.style.height = '0'; \n";
echo "		tdivname.nextSibling.innerHTML = ''; \n";
echo "	} else { \n";
echo "		tdivname.nextSibling.nextSibling.style.height = '0'; \n";
echo "		tdivname.nextSibling.nextSibling.innerHTML = ''; \n";
echo "	} \n";
echo "	tdivname.style.height = '0'; \n";
echo "	tdivname.innerHTML = ''; \n";
echo "}\n";

echo "</script>\n";


echo "</div>\n";
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();


$instructions = lang("Edit this album's images and captions.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = lang("Photo Album").": ".$album_name;
$module->add_breadcrumb_link(lang("Photo Album"), "program/modules/mods_full/photo_album/photo_album.php");
$module->add_breadcrumb_link(lang("Album: ".$album_name), "program/modules/mods_full/photo_album/edit_album.php?id=".$id);

$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/photo_albums-enabled.gif";
$module->heading_text = lang("Photo Album").": ".$album_name;
$module->description_text = $instructions;
$module->good_to_go();
?>
