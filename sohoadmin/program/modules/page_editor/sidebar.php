<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();
require_once("../../includes/product_gui.php");
##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-20012 Soholaunch.com, Inc.  All Rights Reserved.
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

//echo $currentpageprik;
//echo testArray($row);
?>	
<script type="text/javascript">
function sidebarradio(){
	var bxcount = document.getElementById('boxcount').value;
	if(bxcount > 0){
		for(i=1;i<=bxcount;i++){
			//alert(document.getElementById('defaultbox'+i).checked);
			var radioval = document.getElementById('sidebaroption'+i).checked;
			if(radioval==true){
				document.getElementById('copybox'+i).style.display='inline';
				document.getElementById('TDB'+i).innerHTML='copy of <b>'+document.getElementById('copybox'+i).options[document.getElementById('copybox'+i).selectedIndex].text+'</b>';
				document.getElementById('TDB'+i).className = "editTable disabledrop";
				document.getElementById('TDB'+i).style.height = "130px";
				document.getElementById('defaultbox'+i).checked = false;
				//document.getElementById('defaultbox'+i).disabled = true;
				document.getElementById('defaultdiv'+i).style.display = 'none';
				
			} else {
				if(document.getElementById('TDB'+i).className == "editTable disabledrop"){
					document.getElementById('TDB'+i).innerHTML = '      <img width="99" height="50%" border="0" src="pixel.gif">\n';
				}
				document.getElementById('copybox'+i).style.display='none';
				document.getElementById('TDB'+i).className = "editTable";
				//document.getElementById('defaultbox'+i).disabled = false;
				document.getElementById('defaultdiv'+i).style.display = 'inline';
			}
		}
	}
}
</script>
<?php
echo "<input type=\"hidden\" name=\"boxcount\" id=\"boxcount\" value=\"".$sidebarcount."\">\n";
if($sidebarcount > 0){
	$xx=1;
	$sidebar_default=array();
	while($xx <= $sidebarcount){
		$sidebar_default[$xx]='';
		++$xx;
	}
//	$getdefaultsq=mysql_query("select * from sidebar_default where pageid='".$currentpageprik."'");
//	while($getdd_defaults = mysql_fetch_assoc($getdefaultsq)){
//		$sidebar_default[$getdd_defaults['box_number']]=' checked';
//	}
//		
	$getdefault_boxes=mysql_query("select sidebar_boxes.pageid, sidebar_boxes.box_number, sidebar_boxes.copy_box, sidebar_boxes.boxregen, site_pages.prikey, site_pages.page_name, site_pages.url_name from sidebar_boxes inner join site_pages on sidebar_boxes.pageid=site_pages.prikey where sidebar_boxes.boxregen != '' and sidebar_boxes.pageid!='".$currentpageprik."' order by site_pages.page_name ASC, sidebar_boxes.box_number ASC");
	$sidbarpagedropdown = "";
	while($goxddq = mysql_fetch_assoc($getdefault_boxes)){
		$sidbarpagedropdown .= "<option value=\"".$goxddq['prikey']."~~".$goxddq['box_number']."\">".$goxddq['page_name']." &gt; Sidebar ".$goxddq['box_number']."</option>\n";
	}
	
	$getboxregq=mysql_query("select * from sidebar_boxes where pageid='".$currentpageprik."'");
	while($getboxreg=mysql_fetch_assoc($getboxregq)){
		$sidebarcontent_ar[$getboxreg['box_number']]=array('regen'=>$getboxreg['boxregen'], 'copy_box'=>$getboxreg['copy_box']);
	}
	
	$finddefaultsqq=mysql_query("select * from sidebar_default");
	while($finddefaults = mysql_fetch_assoc($finddefaultsqq)){
		if($finddefaults['pageid']==$currentpageprik){
			$sidebar_default[$finddefaults['box_number']]=' checked';	
		}
		$finddefaultsq_ar[$finddefaults['box_number']]=$finddefaults['pageid'];	
	}
	
	$x=1;
	while($x <= $sidebarcount){
		if(!is_array($sidebarcontent_ar[$x])){

			if($finddefaultsq_ar[$x] != '' && $finddefaultsq_ar[$x]!=$currentpageprik){
				$sidebarcontent_ar[$x]['regen']=$finddefaultsq_ar[$x].'~~'.$x;
				$sidebarcontent_ar[$x]['copy_box']=$finddefaultsq_ar[$x].'~~'.$x;
			} else {
				$sidebarcontent_ar[$x]['regen']='';
				$sidebarcontent_ar[$x]['copy_box']='';
			}
			//echo $sidebarcontent_ar[$x]['copy_box'];
		}
		++$x;
	}

	$x=1;
	while($x <= $sidebarcount){
		if($x %4 == 0 || $x == 1) {				
			echo "<div class=\"sidebarbox\" style=\"clear:both;\">Sidebar ".$x."\n";
		} else {
			echo "<div class=\"sidebarbox\" >Sidebar ".$x."\n";
		}

		$copyboxchecked = ' ';
		$copyboxddchecked = ' style="display:none;"';
		$defaultchecked =  ' display:inline; ';
		if($sidebarcontent_ar[$x]['copy_box']!=''){
			$copyboxchecked = ' checked';
			$copyboxddchecked = ' style="display:inline;"';
			$defaultchecked =  ' display:none; ';
		}
		echo "<br/><div id=\"defaultdiv".$x."\" style=\"float:right;".$defaultchecked."\">Default?<input id=\"defaultbox".$x."\" name=\"default".$x."\" type=\"checkbox\" ".$sidebar_default[$x]."></div>\n";
		//echo $weeee."<br/>";
		
		echo "<div style=\"text-align:left;\">\n";
		echo "<input onBlur=\"sidebarradio();\" onClick=\"sidebarradio();\" type=\"checkbox\" name=\"sidebaroption".$x."\" id=\"sidebaroption".$x."\" value=\"copy\" ".$copyboxchecked.">Copy\n";
		echo "<select onBlur=\"sidebarradio();\" onChange=\"sidebarradio();\" name=\"copybox".$x."\" id=\"copybox".$x."\" ".$copyboxddchecked.">\n";
		if($sidebarcontent_ar[$x]['copy_box']!=''){
			echo str_replace('value="'.$sidebarcontent_ar[$x]['copy_box'].'"', 'value="'.$sidebarcontent_ar[$x]['copy_box'].'" selected="selected" ', $sidbarpagedropdown);
		} else {
			echo $sidbarpagedropdown;
		}
		echo "</select>\n";
		echo "</div>\n";
		echo "	<div align=\"center\" value=\"TDB".$x."\" id=\"TDB".$x."\" bgcolor=\"white\" valign=\"top\" class=\"editTable\" style=\"width: 240px; height: 164px;\">\n";
		if($sidebarcontent_ar[$x]!=''){
			echo $sidebarcontent_ar[$x]['regen'];
		} else {
			echo "      <img width=\"99\" height=\"50%\" border=\"0\" src=\"pixel.gif\">\n";	
		}
		
		echo "	</div>\n";
		echo "</div>\n";
		++$x;
	}
}
?>