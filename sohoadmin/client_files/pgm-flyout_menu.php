<?php
error_reporting('341');
session_start();
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


###############################################################################
## Soholaunch(R) Site Management Tool
##
## Author: 		Cameron Allen
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2012 Soholaunch.com, Inc.
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


$webmasterpref = new userdata("webmaster");

######################################################################################
##====================================================================================
## STEP 1: Load Menu Display Settings into Memory (i.e. "buttons or text links")
##====================================================================================
######################################################################################
$menuresult = mysql_query("SELECT page_name, url_name, sub_pages, sub_page_of, type, link, main_menu FROM site_pages");
while($pages_array_ar=mysql_fetch_assoc($menuresult)){
	
	$thispage = $pages_array_ar["page_name"];
	if(strtolower($pages_array_ar["type"]) == "menu"){
		//echo "MENU ITEM! (".$pages_array_ar["link"].")<br>";	
		if(preg_match('/cartid:/',$pages_array_ar['page_name'])){
			$pagelink = $pages_array_ar["link"];
		} else {
			$pagelink = $pages_array_ar["link"];
		}
		$thispage = $pages_array_ar["page_name"];
	}elseif(strtolower($pages_array_ar["type"]) == "main"){
		$pagelink = str_replace(" ", "_", $thispage);
		if($pagelink==startpage()){
			$pagelink = httpvar().$_SESSION['this_ip'].'/';
		} else {
			$pagelink = pagename($pagelink);
		}
		$thispage = $pages_array_ar["page_name"];
	}
	$hClass="";
	if($pages_array_ar['main_menu'] > 0 ){
		$hClass="main";
	}
	
	if (str_replace(" ", "_", $thispage) == str_replace(" ", "_",$pageRequest)) {
		$hClass .= " active";

	}

	if(strlen($hClass)>0){
		$hClass = " class=\"".$hClass."\"";
	}
	$hClass = " class=\"\"";
	if(preg_match('/#blank$/',$pagelink)){
		$pagelink = "<a".$hClass." href=\"".str_replace('#blank','',$pagelink)."\" target=\"_BLANK\">".$thispage."</a>";
	} else {
		$pagelink = "<a".$hClass." href=\"".$pagelink."\">".$thispage."</a>";
	}
	if(strlen($pages_array_ar["sub_pages"]) > 0){
		$ddlink = "<a class=\"flyout-toggle\" href=\"javascript:void(0);\"><span> </span></a>";
		$ddlink = "";
		$pagelink = $pagelink.$ddlink;
	}
	$main_page_array[$thispage]=$pagelink;
}

$result = mysql_query("SELECT page_name, url_name, sub_pages, sub_page_of, type, link, main_menu FROM site_pages WHERE main_menu !=0 ORDER BY main_menu");
$a=0;

$flyoutmenu = $flyoutcss;
if($vflyoutmenu==1){
	$thesedivclass="navigation nav-collapse sidebar-nav";
	$ulclass="nav-bar nav vertical";	
} else {
	$thesedivclass="navigation nav-collapse";
	$ulclass="nav-bar nav";
}
$flyoutmenu .= "<div class=\"".$thesedivclass."\">\n	<ul class=\"".$ulclass."\">\n";

//$flyoutmenu .= "<div class=\"navigation nav-collapse\">\n	<ul class=\"nav-bar nav\">\n";
$main_textmenu='';

while ($row = mysql_fetch_array ($result)) {
	$a++;
	$thispage = $row["page_name"];
	if(strtolower($row["type"]) == "menu"){
		$thispage = $row["page_name"];
	}elseif(strtolower($row["type"]) == "main"){
		$thispage = $row["page_name"];
	}
 
	if (str_replace(" ", "_", $thispage) == str_replace(" ", "_",$pageRequest)) {
		$hmainsClass="flymenu active";
		$hClass="main active";
	} else {
		$hmainsClass="flymenu";
		$hClass="main";
	}
	if(strlen($row["sub_pages"]) > 0){
		$hmainsClass .= " has-flyout dropdown pull-right";
		
		$flyoutmenu .= "	<li class=\"".$hmainsClass."\">".str_replace('class=""','class="'.$hClass.'"',$main_page_array[$thispage])."\n";
		
		$flyoutmenu .= "<a class=\"flyout-toggle dropdown-toggle\" data-toggle=\"dropdown\" href=\"javascript:void(0);\" style=\"display:inline-block;\"><span class=\"caret\"> </span></a>";
		//$flyoutmenu .= "	<a class=\"flyout-toggle\" href=\"#\"><span></span></a>\n";

		$flyoutmenu .= "		<ul class=\"flyout dropdown-menu\" >\n";
		$subz=explode(';',$row["sub_pages"]);
		foreach($subz as $subval){
			$subpagename=$subval;
			$subpclass = "subfly";
			if (str_replace(" ", "_", $subpagename) == str_replace(" ", "_",$pageRequest)) {
				$subpclass = "subfly active";
			}
			
			$flyoutmenu .= "			<li class=\"sub-flyout\">".str_replace('class=""','class="'.$subpclass.'"',$main_page_array[$subpagename])."</li>\n";
		}
		$flyoutmenu .= "		</ul>\n";
		$flyoutmenu .= "	</li>\n";
	} else {
		$flyoutmenu .= "	<li class=\"".$hmainsClass."\">".str_replace('class=""','class="'.$hClass.'"',$main_page_array[$thispage])."</li>\n";
	}
	
	$main_textmenu .= $main_page_array[$thispage]." | ";	
}
$main_textmenu = str_replace('class="main"','',$main_textmenu);
//$main_textmenu=preg_replace('/ | $/','',$main_textmenu);
# Close row & table for #hmains#
$flyoutmenu .= "	</ul>\n</div>\n";

?>