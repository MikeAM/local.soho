<?php
error_reporting(E_PARSE);
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

echo "<script type=\"text/javascript\">\n";
echo "	function edit_thispage(v) { \n";
echo "		if(v.length > 0){\n";
echo "			if(top.location != location){\n";
echo "				show_hide_layer('loadingLayer','','show');\n";
echo "				show_hide_layer('userOpsLayer','','hide');\n";
echo "				var p = 'Editing Page : '+v;\n";
echo "				parent.frames.footer.setPage(p);\n";
echo "			}\n";
echo "			var nocache = '".microtime()."';\n";
echo "			window.location = 'http://".$_SESSION['docroot_url']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage='+v+'&nocache='+nocache; \n";
echo "		}\n";
echo "	}\n";
echo "</script>\n";

$pgrez = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages ORDER BY page_name asc");
while ( $getPg = mysql_fetch_array($pgrez) ) {
	// Main menu pages
	if(!preg_match('/^http:/i', $getPg['link'])){
   
		if ( $getPg['main_menu'] > 0 ) {
		   $main_pgz[] = $getPg['page_name'];
		
		// Sub-menu pages
		} elseif ( strlen($getPg['sub_page_of']) > 4 ) {
		   $tmppg = split("~~~", $getPg['sub_page_of']);
		   $sub_pgz[$tmppg[0]][] = array( sort=>$tmppg[1], name=>$getPg['page_name'] );
		
		// Off-menu pages
		} else {
		   $other_pgz[] = $getPg['page_name'];
		}
	}
}

// Loop main menu page array to build jump options
//-------------------------------------------------------
$dd_menpgz = "      <select class=\"pagedd\" name=\"jump_menupg\" onchange=\"edit_thispage(this.options[this.selectedIndex].value);\">\n";
$dd_menpgz .= "       <option class=\"pageddtitle\" value=\"\">".lang("Edit-Page-Quick-Select")."</option>\n";
$dd_menpgz .= "       <option class=\"pageddtitle\" value=\"\">".lang("On-Menu Pages")."</option>\n";
foreach ( $main_pgz as $key=>$mp ) {
   $dd_menpgz .= "       <option class=\"pagedd\" value=\"".$mp."\">".$mp."</option>\n";

   // Pull sub-pages for this page
   foreach ( $sub_pgz[$mp] as $sp ) {
      $dd_menpgz .= "       <option class=\"pagedd\" value=\"".$sp[name]."\">&gt;&gt; ".$sp[name]."</option>\n";
   }
}

$dd_menpgz .= "       <option class=\"pageddtitle\" value=\"\">".lang("Off-Menu Pages")."</option>\n";

foreach ( $other_pgz as $key=>$op ) {
   $dd_menpgz .= "       <option class=\"pagedd\" value=\"".$op."\">".$op."</option>\n";
}

$dd_menpgz .= "      </select>\n";

/// Begin Speed Dial Menu table and form
###==================================================================================

echo "		".$dd_menpgz;



?>