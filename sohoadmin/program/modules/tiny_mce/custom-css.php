<?php

error_reporting(E_PARSE);
header("Content-Type: text/css;X-Content-Type-Options: nosniff;");
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

include_once('../../includes/product_gui.php');


function FuckIt_CutEmUp($cssfile) {
	$cssfile  = preg_replace('/(\/\*)[^\e]*?(\*\/)/i', '', $cssfile);
	$cca = eregi_replace('\}', '}_camsplit_', $cssfile);
	$cca_a = explode('_camsplit_', $cca);
	array_pop($cca_a);
	
	foreach($cca_a as $xa){	   
	   $xplode = explode('{', $xa);
	   $cssvar = $xplode['0'];
	   if(array_key_exists($cssvar, $xplode_css)) {
	      $xplode_var = eregi_replace('\}', '', $xplode['1']);
	      $xplode_var = explode(';', $xplode_var);
	      array_pop($xplode_var);
	         foreach($xplode_var as $xplode_vars){
	            $final_xplode_var = explode(':', $xplode_vars);
	            $cssbit1 = $final_xplode_var[0];
	            $xplode_css[$cssvar][$cssbit1] = $final_xplode_var['1'];       
	         }
	   } else {
	      $xplode_var = eregi_replace('\}', '', $xplode['1']);
	      $xplode_var = explode(';', $xplode_var);
	      array_pop($xplode_var);
	      
	         foreach($xplode_var as $xplode_vars){
	            $final_xplode_var = explode(':', $xplode_vars);
	            $cssbit1 = $final_xplode_var[0];
	            $Cssbits[$cssbit1][$cssbit1] = $final_xplode_var['1'];            	         
	            $xplode_css[$cssvar][$cssbit1] = $final_xplode_var['1'];
	      }
	   }
	}	
	//uksort($xplode_css, "strnatcasecmp");	
	return($xplode_css);
}

function ereg_MatchedHTMLTags($tagname) {
    return "<[ \\n\\r\\t]*$tagname*>(.*)<[ \\n\\r\\t]*/[ \\n\\r\\t]*$tagname*>";
}

function MitchAllTogether($cssinc){
	
	   $pagecontents = include_r($cssinc);

	
	$css_array = '';
	preg_match_all('/[^\'" =]+\.(css)/i', $pagecontents, $matches);      
	$ssmatch = '';
	$ssmatchn[] = 'sohoadmin/program/includes/display_elements/window/default.css';
	$ssmatchn[] = 'sohoadmin/program/includes/display_elements/window/onscreen_edit.css';
	foreach($matches['0'] as $css_matches){   

	   if(!in_array($css_matches, $ssmatchn)){    
			$css_matcherup = eregi_replace('sohoadmin/program/modules', '..', $css_matches);
	   	ob_start();
	      	include($css_matcherup);
	      	$scontents = ob_get_contents();
	   	ob_end_clean();

	   	$ssmatchn[] = $css_matches;         
	   	$ssmatch[] = $scontents;      
	   }
	}
   
   //$daContent = eregi_replace("[\n\t\r$]", "", $pagecontents);
   //$daContent = eregi_replace("[\n]", "<br />", $daContent);
   //echo "0(".$daContent.")<br/>\n";
       
   eregi(ereg_MatchedHTMLTags('style'), $pagecontents, $stylematches);
   
//   echo "0(".$stylematches[0].")<br/>\n";
//   echo "1(".$stylematches[1].")<br/>\n";
//   echo "2(".$stylematches[2].")<br/>\n";
//   echo "3(".$stylematches[3].")<br/>\n";
//   echo "4(".$stylematches[4].")<br/>\n";
//   echo "5(".$stylematches[5].")<br/>\n";
//   echo "6(".$stylematches[6].")<br/>\n";
//   echo "7(".$stylematches[7].")<br/>\n";

	$inlinecss = '';
   $inlinecss .= $stylematches[1];
	
	if($inlinecss != ''){
		$ssmatch[] = $inlinecss;
	}

	foreach($ssmatch as $koolaid){
		$finalarray[] = FuckIt_CutEmUp($koolaid);	
	}
	return($finalarray);
}
$pagename = base64_decode($_GET['pr']);
//$pagename = 'About_Us';
//$_GET['cust_temp'] = base64_encode('FUN-Field-Blue');
$site = 'http://'.$_SESSION['this_ip'].'/'.pagename($pagename);
	//$site = 'http://provx.soholaunch.com/index.php?pr=asdf';
//	$site = 'http://cameronallen.com/';
	//$cam2 = MitchAllTogether('http://provx.soholaunch.com/index.php?pr=asdf');
	//echo testArray($cam2);
	$cam2 = MitchAllTogether($site);
	//echo testArray($cam2);
	$cssdisplay = '';
	foreach($cam2 as $sa){
 		foreach($sa as $sa2=>$sa2val){	
 			$cssdisplay .= $sa2." {\n";
 			foreach($sa2val as $sa3var=>$sa3val){	
 				$cssdisplay .= "	".$sa3var.":".$sa3val.";\n";
 			}
 			$cssdisplay .= "}\n";
		
		}
	}



	echo $cssdisplay;

//	$bodyCssInc = 'http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/page_editor/contentStyles.txt';
//	ob_start();
//	   include_r($bodyCssInc);
//	   $bodyInc = ob_get_contents();
//	ob_end_clean();
	echo "html {\n";
	echo "background: #FFFFFF;\nmargin: 0 !important;\npadding: 0 !important;\n";
	echo "}\n";

	echo "body {\n";	
	echo "color:#000; background-color: #FFFFFF;\nmargin: 0 !important;\npadding: 0 !important;\n";	
	//echo $bodyInc;
	echo "}\n";
	
	//echo "background-color: white;\n margin: 0px;\n \n padding: 0px;\n";
	exit;
//////echo "cust_temp=".base64_encode('Soholaunch-Bare_Essentials-Brittany').'&style='.base64_encode("width:100%;valign:top;bgcolor:#ffffff;border-style: none; padding: 0px; vertical-align: top;width:780;cellspacing:0;cellpadding:0;border:0;align:center;border-right: 1px solid rgb(185, 190, 193); border-bottom: 1px solid rgb(185, 190, 193);bgcolor:#f3f5f7;align:left;width:100%;cellspacing:0;cellpadding:0;border:0;border-top: 0px none;").'&tags='.base64_encode("DIV;TD;TD;TD;TD;TD;TABLE;TABLE;TABLE;TABLE;TABLE;TABLE;TD;TD;TABLE;TABLE;TABLE;TABLE;TABLE;").'&classes='.base64_encode("sohotext;camcam;");
////
//////} else {
////	include_once('../../includes/product_gui.php');
////	
//////$CUR_TEMPLATE = 'Soholaunch-Bare_Essentials-Brittany';
//////$_GET['cust_temp'] = $CUR_TEMPLATE;
//////$_SESSION['this_ip'] = 'provx.soholaunch.com';
//////$_SESSION['doc_root'] = '/home/provx/public_html';
//////echo testArray($_GET);
////
////$_GET['cust_temp'] = base64_decode($_GET['cust_temp']);
////$_GET['style'] = base64_decode($_GET['style']);
////$_GET['tags'] = base64_decode($_GET['tags']);
////$_GET['classes'] = base64_decode($_GET['classes']);
////$_GET['ids'] = base64_decode($_GET['ids']);
//////echo testArray($_GET);
////
////$_GET['style'] = eregi_replace(";$", '', $_GET['style']);
////$_GET['tags'] = eregi_replace(";$", '', $_GET['tags']);
////$_GET['classes'] = eregi_replace(";$", '', $_GET['classes']);
////$page=base64_decode($_GET['pr']);
////$page = eregi_replace(' ', '_', $page);
////$CUR_TEMPLATE = $_GET['cust_temp'];
////
////
////
////
////	ob_start();
////		include_r('http://'.$_SESSION['this_ip'].'/index.php?pr='.$page);
////		$pagecontents = ob_get_contents();
////	ob_end_clean();
////	
//////$csscontents = eregi_replace("\<link rel\=\"shortcut icon\" href\=\"favicon\.ico\"/\>", '', $csscontents);
//////$csscontents = eregi_replace("\<link href\=\"sohoadmin/program/includes/display_elements/window/onscreen_edit\.css\" rel\=\"stylesheet\" type\=\"text/css\"\>\</link\>", '', $csscontents);
//////$csscontents = eregi_replace("\<link href\=\"sohoadmin/program/includes/display_elements/window/default\.css\" rel\=\"stylesheet\" type\=\"text/css\"\>\</link\>", '', $csscontents);
//////$csscontents = eregi_replace("\<script src\=\"sohoadmin/client_files/site_javascript\.php\" type\=\"text/javascript\"\>\</script\>", '', $csscontents);
//////$csscontents = eregi_replace("\<script type\=\"text/javascript\" src\=\"sohoadmin/program/includes/display_elements/window/prototype\.js\"\>\</script\>", '', $csscontents);
//////$csscontents = eregi_replace("\<script type\=\"text/javascript\" src\=\"sohoadmin/program/includes/display_elements/window/window\.js\"\>\</script\>", '', $csscontents);
//////$csscontents = eregi_replace("\<script type\=\"text/javascript\" src\=\"sohoadmin/program/includes/display_elements/window/effects\.js\"\>\</script\>", '', $csscontents);
//////$csscontents = eregi_replace("\<script type\=\"text/javascript\" src\=\"sohoadmin/program/includes/display_elements/window/debug\.js\"\>\</script\>", '', $csscontents);
//////$csscontents = eregi_replace("\<script type\=\"text/javascript\" src\=\"sohoadmin/client_files/embed\.js\"\>\</script\>", '', $csscontents);
////	preg_match_all('/[^\'" =]+\.(css)/i', $pagecontents, $matches);		
////	$csscontents = '';
////
////	foreach($matches['0'] as $css_matches){			
////			if(!eregi('default\.css', $css_matches) && !eregi('onscreen_edit\.css', $css_matches) && $css_matches != 'custom.css'){
////				ob_start();
////				//$css_matches = eregi_replace('sohoadmin/program/modules', '../', $css_matches);
////				include($_SESSION['doc_root'].'/'.$css_matches);
////				$csscontents .= ob_get_contents();
////				ob_end_clean();
////			}		
////	}
//////ob_start();
//////include('sohoadmin/program/modules/site_templates/pages/LINCOLN-roadchapel-startsub/custom.css');
////$csscontents  = preg_replace('/(\/\*)[^\e]*?(\*\/)/i', '', $csscontents);
//////echo $csscontents;
////			
////echo $csscontents;
////echo "body { background-color: white;\n margin: 0px;\n \n padding: 0px;  } \n";
////	//echo eregi_replace('[^ ]\{', ' {', $csscontents);
//////	echo "* { background-color: white;\n margin: 0px;\n \n padding: 0px;  } \n";
////	//echo $csscontents;
////
////	exit;




//$_GET['cust_temp'] = 'LINCOLN-roadchapel-startsub';
//$CUR_TEMPLATE = $_GET['cust_temp'];
//$page = 'joe';
//	ob_start();
//		include('../site_templates/pages/'.$_GET['cust_temp'].'/custom.css');
//		$cap_display = ob_get_contents();
//	ob_end_clean();
	//$cap_display = eregi_replace('#CONTENT#', "<div id=\"contentz\">#CONTENT#</div>", $cap_display);
//	echo $cap_display;


//$CUR_TEMPLATE = $_GET['cust_temp'];
//	ob_start();
//		include('../site_templates/pages/'.$_GET['cust_temp'].'/custom.css');
//		$cap_display = ob_get_contents();
//	ob_end_clean();
	//$cap_display = eregi_replace('#CONTENT#', "<div id=\"contentz\">#CONTENT#</div>", $cap_display);
//	echo $cap_display;




//	ob_start();
//	include("href=\"http://provx.jambuildit.com/sohoadmin/program/modules/site_templates/pages/FUN-Field-Blue/custom.css");
//	$cap_display = ob_get_contents();
//	ob_end_clean();
//	$cap_display = eregi_replace('body', '', $cap_display);
//	$cap_display = eregi_replace('alignTop', '', $cap_display);
//	$cap_display = eregi_replace('mainContent_A', '', $cap_display);

//
////echo $cap_display = eregi_replace("\}", "}\n<br/>", $cap_display);
//ob_start();	
//	include('http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$CUR_TEMPLATE.'/index.html');
//	$cap_display = ob_get_contents();
//ob_end_clean();
////preg_match_all('/[^="\']+\.(css)/i', $cap_display, $css_matches);
//////echo testArray($css_matches);
////	foreach($css_matches['0'] as $css_link){
////		if(!file_exists($css_link)){
////			$css_link = basename($css_link);
////		} 
////		
////		if(!file_exists($css_link)){
////			$css_link = $_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$CUR_TEMPLATE.'/'.basename($css_link);	
////		}
////		
////		if(!file_exists($css_link)){
////			$css_link = $_SESSION['doc_root'].'/sohoadmin/program/modules/site_templates/pages/'.$CUR_TEMPLATE.'/'.basename($css_link);
////		}
////	} 
////$css_link;
////$oCSS=new CSS();
////$oCSS->parseFile($css_link);
////$oCSS->css;
////echo testArray($_GET);
//$arrstyley = explode(';', $_GET['classes']);
////echo testArray($arrstyley);
////if(!eregi('url\(["\']?http', $_GET['style'])){
////	$_GET['style'] = eregi_replace('url\(', 'url(http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$CUR_TEMPLATE.'/', $_GET['style']);
////}
//
//
//
//
//$stylems = explode(';', eregi_replace(';$', '', $_GET['style']));
////$thisstyley = "*.* { \n";
////$stylems = array_reverse($stylems);
////$sause = "body, p, td, div, table, html, blink, address, span, font  * { ";
////$sause = "body { \n";
//$sause = "* { \n";
//foreach($stylems as $mm){
//	$sause .= eregi_replace('bgcolor', 'background-color', $mm).";\n";
//}
//$thisstyley = eregi_replace(';$', '', $thisstyley);
////foreach($arrstyley as $kk){	
////	foreach($oCSS->css[$kk] as $cssvar=>$cssval){
////		$thisstyley .= $cssvar.': '.$cssval.";\n";
////	}
////}
////echo $thisstyley .= "}\n";
//
////echo $oCSS->buildcss();
//
//
////
////blockquote p 
////{
////	margin: 0px;
////	padding: 0px; 
////}
//
//
//
///////////////newstuff
//
//
////$CUR_TEMPLATE = 'ANIMALS-Sea1-startsub';
//
//
//ob_start();
//	echo include_r('http://'.$_SESSION['this_ip'].'/index.php?pr='.$page);
//	$pagecontents = ob_get_contents();
//ob_end_clean();
//
//$css_array = '';
////$css_array[] = 
//
//preg_match_all('/[^\'" =]+\.(css)/i', $pagecontents, $matches);
//
////$matches['0'][] = 'http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/coaks.css';
//
//$master_style_sheet = '';
//
//foreach($matches['0'] as $css_matches){
//   $csscheckz = ''; 
//   $css_sheets = '';  
//   $css_sheets = $css_matches;
//	if($css_sheets != ''){
//		ob_start();
//			if(!include($css_sheets)){				
//				$css_sheets = 'http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$CUR_TEMPLATE.'/'.basename($css_sheets);	
//				include($css_sheets);
//			}
//			$csscheckz = ob_get_contents();
//		ob_end_clean();
//		$master_style_sheet .= $csscheckz."\n";
////	   if($csscheckz != ''){
////			$css_array[] = $css_sheets;
////	   }	  
//	}
//	$css_matches = '';
//}
//
////$master_style_sheet = eregi_replace('(\/\*){1}[^*/]*(\*\/){1}', '', $master_style_sheet);
////$master_style_sheet = eregi_replace('}', 'ssssplitarraysss', $master_style_sheet);
//
//$ss_array = explode('}', $master_style_sheet);
////echo testArray($arrstyley);
////echo testArray($arrstyley);
////echo $arrstyleyval;
//
//
//foreach($ss_array as $ss_var=>$ss_val){
//	$ss_val_arr = explode('{', $ss_val);
//	foreach($arrstyley as $arrstyleyval){
//		if(eregi($arrstyleyval, $ss_val_arr['0'])){
//			$sause .= eregi_replace('bgcolor', 'background-color', $ss_val_arr['1']);
//		}
//	}
//	$ss_array[$ss_var] = $ss_val.'}';
//}
//$sause .= "\n} \n\n";
//
////echo $master_style_sheet."\n";
////echo testArray($ss_array);
////echo "<br/><br/><br/>";
////echo "<pre>";
//
//
////$tagarray = array_reverse($tagarray);
//
//
//
//
////$tagarray2 = array_reverse($tagarray2);
//
//
//$master_style_sheet = eregi_replace('bgcolor', 'background-color', $master_style_sheet);
//
//
//$tagarray3 = explode(';', $_GET['ids']);
//$tagarray3 = array_reverse($tagarray3);
////foreach($tagarray3 as $arrtagval3){
////	if($arrtagval3 != ''){
////		$master_style_sheet = eregi_replace("#".$arrtagval3.' ', '* ', $master_style_sheet);
////	}
////}
//
//foreach($tagarray3 as $arrtagval3){
//	if($arrtagval3 != ''){
//	//	$master_style_sheet = eregi_replace("#".$arrtagval3, '#conten', $master_style_sheet);
//	//	$master_style_sheet = eregi_replace("(#".$arrtagval3." )\{", 'body {', $master_style_sheet);
//		if(eregi("(#".$arrtagval3." )[^{]")){
//			$master_style_sheet = eregi_replace("(#".$arrtagval3.")", '*', $master_style_sheet);
//		}
//	}
//}
//
//
//
////echo $master_style_sheet;
////$master_style_sheet = eregi_replace("#content", '*', $master_style_sheet);
//$tagarray2 = explode(';', $_GET['classes']);
//foreach($tagarray2 as $arrtagval2){
//	if($arrtagval2 != ''){
//		//$master_style_sheet = eregi_replace("\.".$arrtagval2, '', $master_style_sheet);
//	}
//}
//
////
//$tagarray = explode(';', $_GET['tags']);
//foreach($tagarray as $arrtagval){
//	if($arrtagval != ''){
//		//$master_style_sheet = eregi_replace($arrtagval, '', $master_style_sheet);
//	}
//}
//
////if(preg_match('/\/.*\.(jpg|jpeg|gif|png|bmp)/i', $master_style_sheet, $mms)){
////	echo testArray($mms);
////}
////	$master_style_sheet = eregi_replace('url(', 'url(http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$CUR_TEMPLATE.'/', $master_style_sheet);
//
//
//echo $master_style_sheet = eregi_replace("url\(", 'url(http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$CUR_TEMPLATE.'/', $master_style_sheet);
//
//echo $sause = eregi_replace("url\(", 'url(http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$CUR_TEMPLATE.'/', $sause);
//

//echo testArray($css_array);
//.mceContentBody
?>
