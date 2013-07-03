<?php
error_reporting('341');
ini_set("max_execution_time", "99");
ini_set("default_socket_timeout", "99");
ini_set("max_post_size", "200M");
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

# Include essential interface files
if(!include_once("../includes/product_gui.php")){ exit; }

echo "<meta http-equiv=Content-Type content=\"text/html; charset=UTF-8\">\n";


$ncmd = $_SESSION['doc_root'].'/media/'.$_GET['file'];
$file = fopen($ncmd, "r"); 
$content = fread($file, filesize($ncmd));      
fclose($file);


if(file_exists($ncmd)){
	$savecontent = $content;
	$filesave = fopen($ncmd, "w+");		
	if(!fwrite($filesave, $savecontent)) {
		//echo "<font color=".$red."> COULD NOT SAVE FILE!!!</font>";
	} else {
		//echo "<font color=green> file saved!!!</font>";
	}
	fclose($filesave);
}

?>

 
<style type="text/css">
   #content{
      width:750px;
      z-Index: 802;
   }
	
.textAreaWithLines {
	background: #808080;
	background-color: #808080;
	color: white;
	z-Index: 750;
}
.filelist {
 padding:0px 2px 0px 2px;
 font-size: 8pt;
}

.nav_main { background-image: url(<?php echo httpvar().$_SESSION['this_ip']; ?>/sohoadmin/program/includes/display_elements/graphics/btn-nav_save-off.jpg); } 
.nav_mainon { background-image: url(<?php echo httpvar().$_SESSION['this_ip']; ?>/sohoadmin/program/includes/display_elements/graphics/btn-nav_save-on.jpg); } 

.nav_main, .nav_mainon, .nav_mainmenu, .nav_mainmenuon, .nav_save, .nav_saveon, .nav_soho, .nav_sohoon, .nav_logout, .nav_logouton { 
	color: #FFFFFF; 
	font-family: verdana, arial, helvetica, sans-serif; 
	font-size: 10px;
	cursor: pointer; 
} 
.nav_main, .nav_mainon, .nav_mainmenu, .nav_mainmenuon { 
   background-color: #10D91A; 
	border: 1px solid #595959; 
} 

.nav_mainmenu { 
	font-weight: bold; 
} 
.nav_mainmenuon { 
	background-color: #3283D3; 
	font-weight: bold; 
} 
.nav_save, .nav_saveon { 
	background-color: #087D34; 
	border: 2px solid #66CC70; 
} 
.nav_saveon { 
	background-color: #149845; 
} 
.nav_soho, .nav_sohoon { 
	background-color: #815714; 
	border: 2px solid #CC9B66; 
} 
.nav_sohoon { 
	background-color: #FF6600; 
} 
.nav_logout { 
	border: 1px solid #595959; 
	background-image: url(<?php echo httpvar().$_SESSION['this_ip']; ?>/sohoadmin/program/includes/display_elements/graphics/btn-nav_logout-off.jpg); 
} 
.nav_logouton { 
	border: 1px solid #595959; 
	background-image: url(<?php echo httpvar().$_SESSION['this_ip']; ?>/sohoadmin/program/includes/display_elements/graphics/btn-nav_logout-on.jpg); 
} 
.btn_edit, .btn_editon, .btn_save, .btn_saveon, .btn_delete, .btn_deleteon, .btn_build, .btn_buildon, .btn_risk, .btn_riskon { 
	background-color: #C3DEFF; 
	font-family: tahoma, verdana, arial, helvetica, sans-serif; 
	color: #000000; 
	font-size: 8pt; 
	cursor: pointer; 
	border: 2px solid #6699CC; 
	border-right: 2px solid #336699; 
	border-bottom: 2px solid #336699; 
   border-left: 2px solid #6699CC; 
} 
.btn_editon { 
	background-color: #C3EDFF; 
} 
.btn_save, .btn_saveon { 
	background-color: #14B21C; 
	color: #ffffff; 
	border-top: 2px solid #158B1A; 
	border-right: 2px solid #166D1A; 
	border-bottom: 2px solid #166D1A; 
   border-left: 2px solid #158B1A; 
} 
.btn_saveon { 
	background-color: #10D91A; 
} 
.btn_delete, .btn_deleteon { 
	background-color: #E31A1A; 
	color: #FFFFFF; 
	border-top: 2px solid #B81B1B; 
	border-right: 2px solid #680808; 
	border-bottom: 2px solid #680808; 
   border-left: 2px solid #B81B1B; 
} 
.btn_deleteon { 
	background-color: #FF0000; 
} 
.btn_risk, .btn_riskon { 
	background-color: #F75D00; 
	color: #FFFFFF; 
	border-top: 2px solid #B81B1B; 
	border-right: 2px solid #680808; 
	border-bottom: 2px solid #680808; 
   border-left: 2px solid #B81B1B; 
} 
.btn_riskon { 
	background-color: #FE7613; 
} 
.btn_build, .btn_buildon { 
	background-color: #BDEED1; 
	color: #000000; 
	border-top: 2px solid #66CCA2; 
	border-right: 2px solid #33996D; 
	border-bottom: 2px solid #33996D; 
   border-left: 2px solid #66CCA2; 
} 
.btn_buildon { 
	background-color: #B1FAD0; 
} 
.btn_blue, .btn_green, .btn_red, .btn_#FF2F37 { 
	background-color: #C3DEFF; 
	font-family: tahoma, verdana, arial, helvetica, sans-serif; 
	color: #FFF; 
	font-size: 8pt; 
	cursor: hand; 
} 
.btn_blue { 
	background-color: #336699; 
	color: #FFFFFF; 
	font-size: 8pt; 
	cursor: hand; 
	border: 2px outset #6699CC; 
} 
.btn_green { 
	background-color: #087D34; 
	color: #FFFFFF; 
	font-size: 8pt; 
	cursor: hand; 
	border: 2px outset #66CC91; 
} 
.btn_red { 
	background-color: #6E0000; 
	color: #FFFFFF; 
	font-size: 8pt; 
	cursor: hand; 
	border: 2px outset #9B0000; 
} 
.btn_#FF2F37 { 
	background-color: #D75B00; 
	color: #FFFFFF; 
	font-size: 8pt; 
	cursor: hand; 
	border: 2px outset #9B5800; 
} 
div.upload_div2 { 
	position: relative; 
} 
div.fakefile { 
	position: absolute; 
	top: 0px; 
	left: 0px; 
	z-index: 1; 
} 
input.file { 
	position: relative; 
	text-align: right; 
	-moz-opacity:0 ; 
	filter:alpha(opacity: 0); 
	opacity: 0; 
	z-index: 2; 
	font-size: 2; 
} 
form.upload_shit input:focus {
	background-color: transparent;
}
.skin0{ 
position:absolute; 
width:180px; 
border:2px solid black; 
background-color:menu; 
font-family:Verdana; 
line-height:20px; 
cursor:default; 
font-size:12px; 
z-index:100; 
visibility:hidden; 
} 
.menuitems{ 
padding-left:10px; 
padding-right:10px; 
} 
a.dropdown{ 
	color: yellow; 
} 
a.dropdown:hover{ 
	color: orange; 
} 
</style>



<?php
//if($_POST['realcontent'] != '') {
//	$savecontent = base64_decode($_POST['realcontent']);
//	$filesave = fopen($_POST['filename'], "w+");		
//	if(!fwrite($filesave, $savecontent)) {
//		fclose($filesave);
//		echo "<font color=".$red."> COULD NOT SAVE FILE!!!</font>";
//	} else {
//		echo "<font color=green> file saved!!!</font>";
//	}
//} 



$concnt = explode("\n", $content);
$concnt = count($concnt);


echo "<div style=\"text-align:right; padding:5px; position:absolute; top:520px; right:0px; width:700px; z-index: 3001; >";
echo "\n<form onscroll=\"\" style=\"display: inline;\" id=edit name=edit method=POST action=\"#\">";
echo "<input type=hidden ID=\"realcontent\" name=realcontent value=\"\">\n"; 
echo "<input type=hidden name=filename value=\"".$ncmd."\">\n"; 
echo "<input type=hidden id=lastcmd name=lastcmd value=\"".$lstcmd."\">\n"; 
echo "<input type=hidden id=cmd2 name=cmd value=\"".$_POST['cmd']."\">\n"; 
echo "<input type=hidden ID=\"getback\" name=getback value=\"\">\n"; 
echo "<font color=\"black\">&nbsp;Editing:&nbsp;".basename($ncmd)."</font>\n";

echo "<button type=button class=\"greenButton\" onclick=\"saveCustomsimple('".$ncmd."', Base64.encode(document.getElementById('simplecontent').value));\"><span><span>&nbsp;SAVE&nbsp;</span></span></button>\n";       

echo "&nbsp;&nbsp;&nbsp;";
echo "<button type=button class=\"redButton\" onclick=\"show_hide_layer('objectbar','','show','simple_editor_container','','hide');\"><span><span>&nbsp;Cancel&nbsp;</span></span></button>";  
echo "</div>\n";

echo "<div style=\"color:white; background:#DCDCDC; padding:5px; height:100%; width:100%;\">";
//echo "<div id=\"ronshouse\" style=\"position:fixed; z-index: 302; valign:top; font-family:Courier New; font-size:8pt; width:800px; height:86%; background: #808080; border-top: 2px solid #ffffff; border-bottom: 0px solid #ffffff; border 1px solid #A6A498; overflow:none;\">\n";

echo "<textarea name=\"simplecontent\" id=\"simplecontent\" onkeydown=\"tab_to_tab(event,document.getElementById('simplecontent')); camenter(event,document.getElementById('simplecontent')); positionLineObj(document.getElementById('lineObj'),document.getElementById('simplecontent'));\" spellcheck=\"false\" WRAP=\"OFF\" rows=\"".($concnt)."\" style=\"height:500px;  color:black; background: #ffffff; overflow:scroll; width:750px; font-family:Courier New; font-size:8pt;\">\n";
echo base64_encode($content);
echo "</textarea>\n";    


echo "</form>\n"; 
echo "</div>\n";

			
//echo "<script type=\"text/javascript\">\n";
//echo "createTextAreaWithLines('simplecontent');\n";
//echo "CodePress(); \n";
//echo "</script>\n";

?>
</body>

