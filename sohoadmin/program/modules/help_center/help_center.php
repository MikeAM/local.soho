<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Homepage:      http://www.soholaunch.com
## Bug Reports:   http://bugz.soholaunch.com
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc.  All Rights Reserved.
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
header('Content-type: text/html; charset=UT'.'F-8');
session_start();
error_reporting(E_PARSE);

# Primary interface include
require_once("../../includes/product_gui.php");


if (!function_exists(mb_list_encodings)) {
	function mb_list_encodings(){
		$list_encoding = array("pass", "auto", "wchar", "byte2be", "byte2le", "byte4be", "byte4le", "BASE64", "UUENCODE", "HTML-ENTITIES", "Quoted-Printable", "7bit", "8bit", "UCS-4", "UCS-4BE", "UCS-4LE", "UCS-2", "UCS-2BE", "UCS-2LE", "UTF-32", "UTF-32BE", "UTF-32LE", "UTF-16", "UTF-16BE", "UTF-16LE", "UTF-8", "UTF-7", "UTF7-IMAP", "ASCII", "EUC-JP", "SJIS", "eucJP-win", "SJIS-win", "JIS", "ISO-2022-JP", "Windows-1252", "ISO-8859-1", "ISO-8859-2", "ISO-8859-3", "ISO-8859-4", "ISO-8859-5", "ISO-8859-6", "ISO-8859-7", "ISO-8859-8", "ISO-8859-9", "ISO-8859-10", "ISO-8859-13", "ISO-8859-14", "ISO-8859-15", "EUC-CN", "CP936", "HZ", "EUC-TW", "BIG-5", "EUC-KR", "UHC", "ISO-2022-KR", "Windows-1251", "CP866", "KOI8-R", "utf-8");
		return $list_encoding;
	}
}

if(!function_exists("is_utf8")){	
	function is_utf8($str) {
	    $c=0; $b=0;
	    $bits=0;
	    $len=strlen($str);
	    for($i=0; $i<$len; $i++){
	        $c=ord($str[$i]);
	        if($c > 128){
	            if(($c >= 254)) return false;
	            elseif($c >= 252) $bits=6;
	            elseif($c >= 248) $bits=5;
	            elseif($c >= 240) $bits=4;
	            elseif($c >= 224) $bits=3;
	            elseif($c >= 192) $bits=2;
	            else return false;
	            if(($i+$bits) > $len) return false;
	            while($bits > 1){
	                $i++;
	                $b=ord($str[$i]);
	                if($b < 128 || $b > 191) return false;
	                $bits--;
	            }
	        }
	    }
	    return true;
	}
}

if(function_exists("mb_detect_encoding")){
	$encodings = mb_list_encodings();
	if(!function_exists("fixEncoding")){
		function fixEncoding($in_str){
			$encodings = mb_list_encodings();
			$cur_encoding = mb_detect_encoding($in_str, $encodings);
			if(strtoupper($cur_encoding) == "UTF-8" && mb_check_encoding($in_str,"UTF-8")){
				return $in_str;
			} else {
				return utf8_encode($in_str);
			} // fixEncoding 
		}
	}
} else {
	$encodings = mb_list_encodings();
	if(!function_exists("fixEncoding")){
		function fixEncoding($in_str){
	//		$encodings = mb_list_encodings();
	//		$cur_encoding = mb_detect_encoding($in_str, $encodings);
	//		if(strtoupper($cur_encoding) == "UTF-8" && mb_check_encoding($in_str,"UTF-8")){
			if(is_utf8($in_str)){
				return $in_str;
			} else {
				return utf8_encode($in_str);
			} // fixEncoding 
		}
	}
}
if($_SESSION['product_mode']=='trial'){
	$_SESSION['suspend_msg'] = "Support available in full version only.\n";
}
if($_REQUEST['man']==''){
	$manual_start = 'http://saas.soholaunch.com/index.php';
} else {
	$manual_start = 'http://saas.soholaunch.com/'.$_REQUEST['man'];
}

$OS = strtoupper(PHP_OS);
$thisdomain = $_SESSION['this_ip'];
$browser = $_SERVER['HTTP_USER_AGENT'];
$server_os = $_SERVER['SERVER_SOFTWARE'];
$my_ip = $_SERVER['REMOTE_ADDR'];

$ginfo_q = mysql_query("select df_company, df_phone, df_email from site_specs limit 1");
$ginfo = mysql_fetch_assoc($ginfo_q);

$encodings = mb_list_encodings();
$supinfo = urlencode(base64_encode(fixEncoding('df_email~@~'.$ginfo['df_email'].'~#~soho_un~@~'.strtolower($_SESSION['PHP_AUTH_USER']).'~#~soho_pw~@~'.strtolower($_SESSION['PHP_AUTH_PW']).'~#~name~@~'.$ginfo['df_company'].'~#~build~@~'.current_version().'~#~phone~@~'.$ginfo['df_phone'])));


$getstring = 'https://partner.soholaunch.com/media/ultra_support/ultra_tickets.php?test=yes&domain_name='.$_SESSION['this_ip'].'&domain_key='.$_SESSION['key'].'&supstring='.$supinfo.'&loadtime='.time();


$HTML_DISPLAY .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
$HTML_DISPLAY .= "<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"ltr\">\n";
$HTML_DISPLAY .= "<head>\n";

$HTML_DISPLAY .= "<link rel=\"shortcut icon\" href=\"../../../skins/default/icons/help_center-enabled.gif\" />\n";
$HTML_DISPLAY .= "<link rel=\"icon\" type=\"image/x-icon\" href=\"../../../skins/default/icons/help_center-enabled.gif\">\n";

$HTML_DISPLAY .= "<title>Help Center</title>\n";
$HTML_DISPLAY .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf".'-'."8\">\n";

$HTML_DISPLAY .= "<script type=\"text/javascript\" src=\"http://ultra.soholaunch.com/sohoadmin/program/includes/display_elements/js_functions.php\"></script>\n";

$HTML_DISPLAY .= "<link rel=\"stylesheet\" href=\"http://ultra.soholaunch.com/sohoadmin/program/product_gui.css\">\n";
$HTML_DISPLAY .= "<link rel=\"stylesheet\" href=\"http://ultra.soholaunch.com/sohoadmin/program/includes/product_buttons-ultra.css\">\n";

$HTML_DISPLAY .= "<link rel=\"stylesheet\" href=\"http://ultra.soholaunch.com/sohoadmin/program/includes/product_interface-ultra.css\">\n";
$HTML_DISPLAY .= "<style>\n";
$HTML_DISPLAY .= ".tab-off, .tab-on {\n";
$HTML_DISPLAY .= "   text-align: center;\n";
$HTML_DISPLAY .= "   width: 125px;\n";
$HTML_DISPLAY .= "   height: 18px;\n";
$HTML_DISPLAY .= "   vertical-align: top;\n";
$HTML_DISPLAY .= "   padding: 5px 10px;\n";
$HTML_DISPLAY .= "   background-color: #efefef;\n";
$HTML_DISPLAY .= "   border: 1px solid #ccc;\n";
$HTML_DISPLAY .= "   border-top: 3px solid #ccc;\n";
$HTML_DISPLAY .= "   border-bottom: 0;\n";
$HTML_DISPLAY .= "   color: #595959;\n";
$HTML_DISPLAY .= "   cursor: pointer;\n";
$HTML_DISPLAY .= "}\n";

$HTML_DISPLAY .= ".top-left\n";
$HTML_DISPLAY .= "{\n";
$HTML_DISPLAY .= "	position:absolute;\n";
$HTML_DISPLAY .= "	background-position: -203px 0px;\n";
$HTML_DISPLAY .= "	width:250px;\n";
$HTML_DISPLAY .= "	height:11px;\n";
$HTML_DISPLAY .= "	top:-3px;\n";
$HTML_DISPLAY .= "	left:-240px;\n";
$HTML_DISPLAY .= "	z-index:1;\n";
$HTML_DISPLAY .= "}\n";


$HTML_DISPLAY .= ".tab-on {\n";
$HTML_DISPLAY .= "   color: #000;\n";
$HTML_DISPLAY .= "   background-color: #efefef;\n";
$HTML_DISPLAY .= "   border-top: 3px solid #EAA510;\n";
$HTML_DISPLAY .= "   font-weight: bold;\n";
$HTML_DISPLAY .= "}\n";


$HTML_DISPLAY .= "body, html {\n";
$HTML_DISPLAY .= "	background-position: -203px 0px;\n";
$HTML_DISPLAY .= "} \n";


$HTML_DISPLAY .= ".right-panel {\n";
$HTML_DISPLAY .= "	position:relative;\n";
$HTML_DISPLAY .= "	padding:5px 10px 10px 10px;\n";

$HTML_DISPLAY .= "	margin-left:30;\n";
$HTML_DISPLAY .= "	padding:10px 10px 10px 10px;\n";
$HTML_DISPLAY .= "}\n";

$HTML_DISPLAY .= ".top-left {\n";
$HTML_DISPLAY .= "	top:-3px;\n";
$HTML_DISPLAY .= "	left:-20px;\n";
$HTML_DISPLAY .= "}\n";

$HTML_DISPLAY .= "</style>\n";
$HTML_DISPLAY .= "</head>\n";
$HTML_DISPLAY .= "<body onLoad=\"window.focus();\">\n";


$HTML_DISPLAY .= "			<div class=\"top-left\"></div>\n";

$HTML_DISPLAY .= "<div class=\"right-panel\">	\n";

$HTML_DISPLAY .= "			<h3><img src=\"http://".$_SESSION['this_ip']."/sohoadmin/skins/default/icons/help_center-enabled.gif\" style=\"margin-bottom: 4px; margin-right: 4px; vertical-align: text-top; height: 30px;\">Help Center</h3>\n";
$HTML_DISPLAY .= "			<p style=\"width: 100%;\" id=\"module_description_text\">Need help?&nbsp;Read about the different features in our user manual.\n&nbsp;&nbsp;Have Questions?&nbsp;Open a support ticket to our knowledgeable staff!<br/></p>\n";

############
$tutorial_array = array();
function add_tutorial($idname, $caption, $url, $thumbnail) {
	static $t = 0;
	global $tutorial_array;
	$tutorial_array[$t]['idname'] = $idname;
	$tutorial_array[$t]['caption'] = $caption;
	$tutorial_array[$t]['url'] = $url;
	$tutorial_array[$t]['thumbnail'] = $thumbnail;
	$t++;
}
$tutorialz = '';

$tutorialz .= "<div class=\"box video\" style=\"background: url(video-med.png); background-repeat: no-repeat; position:relative;border:1px solid rgb(204, 204, 204);\" id=\"widget-tutorial-videos\">\n";
$tutorialz .= "<div class=\"hdng\" style=\"padding-left:33px;padding-top:10px;\"><h3>Tutorial Videos</h3></div>\n";
$tutorialz .= "<ul id=\"tutorial-thumbs\">\n";
//add_tutorial('login', 'Log-in', 'http://securexfer.net/tutorials/player.php?tutorial=01_login_tutorial');
add_tutorial('create-pages', 'Create Pages', 'http://securexfer.net/tutorials/player.php?tutorial=03_new_pages');
add_tutorial('edit-pages', 'Open/Edit Pages', 'http://securexfer.net/tutorials/player.php?tutorial=04_open_pages');
add_tutorial('page-editor', 'Page Editor', 'http://securexfer.net/tutorials/player.php?tutorial=05_page_editor');
//add_tutorial('creating-links', 'Text Editor: Creating Links', 'http://securexfer.net/tutorials/player.php?tutorial=06_text_editor_-_creating_links');
//add_tutorial('inserting-copy', 'Copy-Pasting from MS Word', 'http://securexfer.net/tutorials/player.php?tutorial=07_text_editor_-_inserting_copy');
add_tutorial('inserting-images', 'Text Editor: Inserting Images', 'http://securexfer.net/tutorials/player.php?tutorial=08_text_editor_-_inserting_images');
//add_tutorial('using-tables', 'Text Editor: Using Tables', 'http://securexfer.net/tutorials/player.php?tutorial=09_text_editor_-_using_tables');
add_tutorial('templates-part1', 'Template Manager Part 1', 'http://securexfer.net/tutorials/player.php?tutorial=10_template_manager_part_1');
add_tutorial('templates-part2', 'Template Manager Part 2', 'http://securexfer.net/tutorials/player.php?tutorial=11_template_manager_part_2');
add_tutorial('menu-part1', 'Menu Navigation Part 1', 'http://securexfer.net/tutorials/player.php?tutorial=12_menu_navigation_part_1');
add_tutorial('menu-part2', 'Menu Navigation Part 2', 'http://securexfer.net/tutorials/player.php?tutorial=13_menu_navigation_part_2');
add_tutorial('files', 'Uploading & Managing Files', 'http://securexfer.net/tutorials/player.php?tutorial=14_file_manager');
add_tutorial('stats', 'Traffic Statistics', 'http://securexfer.net/tutorials/player.php?tutorial=15_traffic_statistics');
add_tutorial('databases', 'Database Table Manager', 'http://securexfer.net/tutorials/player.php?tutorial=17_database_table_manager');
add_tutorial('cart-payment', 'Shopping: Payment Options', 'http://securexfer.net/tutorials/player.php?tutorial=18_Shopping_Cart_-_Payment%20Options');
add_tutorial('cart-display', 'Shopping: Display Settings', 'http://securexfer.net/tutorials/player.php?tutorial=19_Shopping_Cart_-_Display%20Settings');
add_tutorial('cart-place-item', 'Shopping: Place Item on Page', 'http://securexfer.net/tutorials/player.php?tutorial=21_Shopping_Cart_-_Putting_Items_on_a_Page');
add_tutorial('cart-tax', 'Shopping: Tax & Shipping', 'http://securexfer.net/tutorials/player.php?tutorial=22_Shopping_Cart_-_Tax_and_Shipping');
//add_tutorial('photo-album', 'Photo Albums', 'http://securexfer.net/tutorials/player.php?tutorial=23_Photo%20Albums');
add_tutorial('backup', 'Backup & Restore', 'http://securexfer.net/tutorials/player.php?tutorial=16_backup_and_restore');
add_tutorial('webmaster', 'Webmaster Settings', 'http://securexfer.net/tutorials/player.php?tutorial=24_Webmaster');

$max = count($tutorial_array);
for ( $n = 0; $n < $max; $n++ ) {
	$tutorialz .= '<div style="width:130px; height:170px; float:left;clear:none;padding:10px 4px 10px 4px; text-align:center;font:12px Arial, Helvetica, sans-serif; color:#939292;">';
	$tutorialz .= '<a href="#" onclick="window.open(\''.$tutorial_array[$n]['url'].'\', \''.$tutorial_array[$n]['caption'].'\', \'width=810,height=590\');" id="'.$tutorial_array[$n]['idname'].'"><img src="tutorial-thumbs/'.$tutorial_array[$n]['idname'].'.png" alt="'.$tutorial_array[$n]['caption'].'"/>';
	$tutorialz .= '<h4 style="text-align:center;">'.$tutorial_array[$n]['caption'].'</h4></a>';
	$tutorialz .= '</div>';
}

$tutorialz .= "</ul>\n";
$tutorialz .= "<div class=\"clear\"></div>\n";
$tutorialz .= "</div>\n";
#######################

if(strlen($_SESSION['suspend_msg']) > 3 && ($_SESSION['product_mode']=='suspended' || $_SESSION['product_mode']=='frozen' || $_SESSION['product_mode']=='orphan' || $_SESSION['product_mode']=='trial')){
	$HTML_DISPLAY .= "<div style=\"border: 1px solid #d70000; background-color: #F8F9FD; padding: 2px; margin:10px; font-family: arial 13px; text-align: left; color: #000;\">\n";
	$HTML_DISPLAY .= $_SESSION['suspend_msg'];
	$HTML_DISPLAY .= "</div>\n";

	
	$HTML_DISPLAY .= "<table class=\"text\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tbody><tr>\n";
	if($_SESSION['product_mode']=='trial'){
		$HTML_DISPLAY .= "	<td id=\"tab-manual\" class=\"tab-on\" onclick=\"showid('manual_list');hideid('tuts_list');hideid('support_table_list');setClass('tab-support', 'tab-off');setClass('tab-tuts', 'tab-off');setClass('tab-manual', 'tab-on');\">\n";
	} else {
		$HTML_DISPLAY .= "	<td id=\"tab-manual\" class=\"tab-off\" onclick=\"showid('manual_list');hideid('tuts_list');hideid('support_table_list');setClass('tab-support', 'tab-off');setClass('tab-tuts', 'tab-off');setClass('tab-manual', 'tab-on');\">\n";	
	}
	
	$HTML_DISPLAY .= "		User&nbsp;Manual\n";
	$HTML_DISPLAY .= "	</td>\n";
	$HTML_DISPLAY .= "	<td>&nbsp;</td>\n";
	
	$HTML_DISPLAY .= "	<td id=\"tab-tuts\" class=\"tab-off\" onclick=\"showid('tuts_list');hideid('manual_list');hideid('support_table_list');setClass('tab-support', 'tab-off');setClass('tab-manual', 'tab-off');setClass('tab-tuts', 'tab-on');\">\n";
	$HTML_DISPLAY .= "		Tutorials\n";
	$HTML_DISPLAY .= "	</td>\n";
	$HTML_DISPLAY .= "	<td>&nbsp;</td>\n";
	
	
if($_SESSION['product_mode']=='trial'){
	$HTML_DISPLAY .= "	<td id=\"tab-support\" class=\"tab-off\" onclick=\"alert('Integrated Support available in the full version only. Upgrade Today!');\">	\n";
} else {
	$HTML_DISPLAY .= "	<td id=\"tab-support\" class=\"tab-on\" onclick=\"showid('support_table_list');hideid('tuts_list');hideid('manual_list');setClass('tab-manual', 'tab-off');setClass('tab-tuts', 'tab-off');setClass('tab-support', 'tab-on');document.getElementById('partnertickets').src='".$getstring."';\">	\n";
}
	
	$HTML_DISPLAY .= "		Support&nbsp;Tickets\n";
	$HTML_DISPLAY .= "	</td>\n";
	$HTML_DISPLAY .= "	<td style=\"padding-left: 10px; text-align: right; width: 100%;\">\n";
	$HTML_DISPLAY .= "		&nbsp;\n";
	$HTML_DISPLAY .= "	</td>\n";
	$HTML_DISPLAY .= "</tr></tbody></table>\n";
	 
	if($_SESSION['product_mode']=='trial'){
		$HTML_DISPLAY .= "<div id=\"manual_list\" style=\"width:100%; display: block;\">\n";
	} else {
		$HTML_DISPLAY .= "<div id=\"manual_list\" style=\"width:100%; display: none;\">\n";
	}
	
	$HTML_DISPLAY .= "	<div style=\"width:100%;padding:0px;border:1px solid rgb(204, 204, 204);\">\n";
	$HTML_DISPLAY .= "	<iframe src=\"".$manual_start."?loadtime=".time()."\" scrolling=\"yes\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"0\" vspace=\"0\" hspace=\"0\" style=\"height:80%;overflow:auto; width:100%;  display:block; border: 1px solid #666666;\"></iframe>\n";
	$HTML_DISPLAY .= "	</div>\n";
	$HTML_DISPLAY .= "</div>\n";
	
	$HTML_DISPLAY .= "<div id=\"tuts_list\" style=\"width:100%; display: none;\">\n";
	$HTML_DISPLAY .= "	<div style=\"width:100%;padding:0px;border:1px solid rgb(204, 204, 204);\">\n";
	$HTML_DISPLAY .= "		<div style=\"width:100%;padding:0px;border:1px solid rgb(204, 204, 204);\">\n";
	$HTML_DISPLAY .= $tutorialz;
	$HTML_DISPLAY .= "		</div>\n";
	$HTML_DISPLAY .= "	</div>\n";
	$HTML_DISPLAY .= "</div>\n";
	
	if($_SESSION['product_mode']=='trial'){
		$HTML_DISPLAY .= "<div id=\"support_table_list\" style=\"display: none;\">\n";
	} else {
		$HTML_DISPLAY .= "<div id=\"support_table_list\" style=\"display: block;\">\n";	
	}
	$HTML_DISPLAY .= "	<div style=\"width:100%;padding:0px;border:1px solid rgb(204, 204, 204);\">\n";
	$HTML_DISPLAY .= "	<iframe id=\"partnertickets\" name=\"partnertickets\" src='".$getstring."' scrolling=\"yes\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"0\" vspace=\"0\" hspace=\"0\" style=\"height:80%;overflow:auto; width:100%;  display:block; border: 1px solid #666666;\"></iframe>\n";
	$HTML_DISPLAY .= "	</div>\n";
	$HTML_DISPLAY .= "</div>\n";

} else {
		
	$HTML_DISPLAY .= "<table class=\"text\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tbody><tr>\n";
	$HTML_DISPLAY .= "	<td id=\"tab-manual\" class=\"tab-on\" onclick=\"showid('manual_list');hideid('tuts_list');hideid('support_table_list');setClass('tab-support', 'tab-off');setClass('tab-manual', 'tab-on');setClass('tab-tuts', 'tab-off');\">\n";
	$HTML_DISPLAY .= "		User&nbsp;Manual\n";
	$HTML_DISPLAY .= "	</td>\n";
	$HTML_DISPLAY .= "	<td>&nbsp;</td>\n";
	
	$HTML_DISPLAY .= "	<td id=\"tab-tuts\" class=\"tab-off\" onclick=\"showid('tuts_list');hideid('manual_list');hideid('support_table_list');setClass('tab-support', 'tab-off');setClass('tab-manual', 'tab-off');setClass('tab-tuts', 'tab-on');\">\n";
	$HTML_DISPLAY .= "		Tutorial&nbsp;Videos\n";
	$HTML_DISPLAY .= "	</td>\n";
	$HTML_DISPLAY .= "	<td>&nbsp;</td>\n";
	
	
	//$HTML_DISPLAY .= "	<td id=\"tab-support\" class=\"tab-off\" onclick=\"showid('support_table_list');hideid('manual_list');setClass('tab-manual', 'tab-off');setClass('tab-support', 'tab-on');\">	\n";
	$HTML_DISPLAY .= "	<td id=\"tab-support\" class=\"tab-off\" onclick=\"showid('support_table_list');hideid('tuts_list');hideid('manual_list');setClass('tab-manual', 'tab-off');setClass('tab-tuts', 'tab-off');setClass('tab-support', 'tab-on');document.getElementById('partnertickets').src='".$getstring."';\">	\n";
	$HTML_DISPLAY .= "		Support&nbsp;Tickets\n";
	$HTML_DISPLAY .= "	</td>\n";
	$HTML_DISPLAY .= "	<td style=\"padding-left: 10px; text-align: right; width: 100%;\">\n";
	$HTML_DISPLAY .= "		&nbsp;\n";
	$HTML_DISPLAY .= "	</td>\n";
	$HTML_DISPLAY .= "</tr></tbody></table>\n";
	 
	$HTML_DISPLAY .= "<div id=\"manual_list\" style=\"width:100%; display: block;\">\n";
	$HTML_DISPLAY .= "	<div style=\"width:100%;padding:0px;border:1px solid rgb(204, 204, 204);\">\n";
	$HTML_DISPLAY .= "	<iframe src=\"".$manual_start."?loadtime=".time()."\" scrolling=\"yes\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"0\" vspace=\"0\" hspace=\"0\" style=\"height:80%;overflow:auto; width:100%;  display:block; border: 1px solid #666666;\"></iframe>\n";
	$HTML_DISPLAY .= "	</div>\n";
	$HTML_DISPLAY .= "</div>\n";
	
	
	$HTML_DISPLAY .= "<div id=\"tuts_list\" style=\"width:100%; display: none;\">\n";
	
	$HTML_DISPLAY .= $tutorialz;
	
	$HTML_DISPLAY .= "</div>\n";
	
	$HTML_DISPLAY .= "<div id=\"support_table_list\" style=\"display: none;\">\n";
	$HTML_DISPLAY .= "	<div style=\"width:100%;padding:0px;border:1px solid rgb(204, 204, 204);\">\n";
	$HTML_DISPLAY .= "	<iframe id=\"partnertickets\" name=\"partnertickets\" src='".$getstring."' scrolling=\"yes\" marginwidth=\"0\" marginheight=\"0\" frameborder=\"0\" vspace=\"0\" hspace=\"0\" style=\"height:80%;overflow:auto; width:100%;  display:block; border: 1px solid #666666;\"></iframe>\n";
	$HTML_DISPLAY .= "	</div>\n";
	$HTML_DISPLAY .= "</div>\n";


}

$HTML_DISPLAY .= "</div>\n";
$HTML_DISPLAY .= "</body>\n</html>";

echo $HTML_DISPLAY;
?>

