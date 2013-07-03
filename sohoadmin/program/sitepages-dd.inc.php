<?php
error_reporting(E_PARSE);
session_start();
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('includes/product_gui.php');
chdir($curdir);


/*************************************************************************************************
 ___                     _   ___   _        _         ___
/ __| _ __  ___  ___  __| | |   \ (_) __ _ | |  ___  | _ \ __ _  __ _  ___  ___
\__ \| '_ \/ -_)/ -_)/ _` | | |) || |/ _` || | |___| |  _// _` |/ _` |/ -_)(_-<
|___/| .__/\___|\___|\__,_| |___/ |_|\__,_||_|       |_|  \__,_|\__, |\___|/__/
     |_|                                                        |___/

###==================================================================================
*************************************************************************************************/
// Loop all and split into diff arrays by menu status
if($_REQUEST['page_editordd']==1 || $page_editordd==1){
	include('modules/sitepage_dropdown.inc.php');
	//$dropdown_options = str_replace('>[On-Menu Pages]',' selected="selected" selected>[On-Menu Pages]',$dropdown_options);
	
	echo "		<select id=\"jump_menupg1\" class=\"pagedd\" name=\"jump_menupg\"  onchange=\"edit_thispage(parent.frames.header.document.getElementById('jump_menupg1').options[parent.frames.header.document.getElementById('jump_menupg1').selectedIndex].value);\" >\n";
	
	//echo "		<select id=\"jump_menupg1\" class=\"pagedd\" name=\"jump_menupg\" onchange=\"edit_thispage(parent.frames.header.document.getElementById('jump_menupg1').options[parent.frames.header.document.getElementById('jump_menupg1').selectedIndex].value);\" >\n";
	//echo "		<select id=\"jump_menupg1\" class=\"pagedd\" name=\"jump_menupg\" >\n";
	echo $dropdown_options;
	echo "\n		</select>\n";
} else {
	
//	echo "<script type=\"text/javascript\">\n";
//	echo "	function edit_thispage(v) { \n";
//	echo "		if(v.length > 0){\n";	
//	echo "var linktogo='page_editor.php?currentPage='+v;\n";


	//echo "		parent.frames['ultramenu'].ConfirmLink('http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+v);\n";
//	
//	echo "			var nocache = '".time()."';\n";
//	echo "			document.getElementById('jump_menupg').selectedIndex=0;\n";
////	echo "			parent.frames['body'].document.location = 'http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+v+'&nocache='+nocache; \n";
//
//	echo "			var nn = parent.frames['body'].document.location+\"\";\n";
//	echo "			if(nn.search(\"sohoadmin/program/modules/page_editor/page_editor.php\") > 0 || nn.search(\"sohoadmin/program/modules/blog/blog.php\") > 0){\n";
//	echo "				var wordz=nn.split(\"currentPage=\");\n";
//	echo "				var thepagetogoto=wordz[1].split(\"&nocache=\");\n";
//	echo "				if(v.replace(/[+]/g,' ') != thepagetogoto[0].replace(/[+]/g,' ')){\n";
//	echo "					if(confirm(\"".lang('Do you wish to continue without saving the changes you have made?')."\")){\n";
//	
//	echo "						if(top.location != location){\n";
//	echo "							v = v;\n";
//	echo "							show_hide_layer('loadingLayer','','show');\n";
//	echo "							show_hide_layer('userOpsLayer','','hide');\n";
//	echo "							var p = 'Editing Page : '+v.replace(/[+]/g,' ');\n";
//	echo "							parent.frames.footer.setPage(p);\n";
//	echo "						}\n";
//	
//	echo "						parent.frames['body'].document.location = 'http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+v+'&nocache='+nocache; \n";
//	echo "					} else {\n";
//	echo "						return false;\n";
//	echo "					}\n";
//	
//	echo "				} else {\n";
//	echo "					return false;\n";
//	echo "				}\n";
//	echo "			} else {\n";
//	echo "				if(top.location != location){\n";
//	echo "					v = v;\n";
//	echo "					show_hide_layer('loadingLayer','','show');\n";
//	echo "					show_hide_layer('userOpsLayer','','hide');\n";
//	echo "					var p = 'Editing Page : '+v.replace(/[+]/g,' ');\n";
//	echo "					parent.frames.footer.setPage(p);\n";
//	echo "				}\n";
//	
//	echo "				parent.frames['body'].document.location = 'http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+v+'&nocache='+nocache; \n";
////	echo "			}\n";
//	echo "		}\n";
//	echo "	}\n";
//echo "top.header.document.getElementById('jump_menupg1').onclick = function () {
//	edit_thispage(this.options[this.selectedIndex].value);
//}\n";
//	echo "var jjmenu=parent.frames.ultramenu.document.getElementById(\"jump_menupg1\");
//	jjmenu.onchange=function(){
//	//	parent.frames.ultramenu.edit_thispage(this.options[this.selectedIndex].value);
//		parent.frames.ultramenu.ConfirmLink('http://ultra.soholaunch.com/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+this.options[this.selectedIndex].value+'&nocache='+nocache)
//	//this.options[this.selectedIndex]
//	}\n";
//echo "	alert('".getcwd()."');\n";
?>
 
		<?php
	//echo "			var nocache = '".time()."';\n";
//	echo "</script>\n";
	
	

	
	// Loop main menu page array to build jump options
	//-------------------------------------------------------
	//$dd_menpgz = "      <select id=\"jump_menupg\" class=\"pagedd\" name=\"jump_menupg\" onchange=\"top.header.edit_thispage(this.options[this.selectedIndex].value);\" style=\"margin-top:1px;border:1px solid #B7B7B7;position:absolute;top:0;right:2px;width:120px;\">\n"; 
	// parent.frames.header.document.getElementById('jump_menupg1').focus();parent.frames.header.document.getElementById('jump_menupg1').blur();
	$dd_menpgz = "      <select id=\"jump_menupg\" class=\"pagedd\" name=\"jump_menupg\" onchange=\"parent.frames.body.focus();parent.frames.header.edit_thispage(this.options[this.selectedIndex].value);\" style=\"margin-top:1px;border:1px solid #B7B7B7;position:absolute;top:0;right:2px;width:120px;\">\n"; 
	//$dd_menpgz = "      <select id=\"jump_menupg1\" class=\"pagedd\" name=\"jump_menupg\"  style=\"margin-top:1px;border:1px solid #B7B7B7;position:absolute;top:0;right:2px;width:120px;\">\n";
	include('modules/sitepage_dropdown.inc.php');
	//$dropdown_options = str_replace('>[On-Menu Pages]',' selected="selected" selected>[On-Menu Pages]',$dropdown_options);
	$dd_menpgz .= "       ".$dropdown_options;
	
	$dd_menpgz .= "      </select>\n";
	
	/// Begin Speed Dial Menu table and form
	###==================================================================================
	
	echo "		".$dd_menpgz;
 
}


?>