<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

include("../../includes/product_gui.php");

function MitchAllTogether($cssinc){
   //echo "Here we go!<br/>\n";
	$pagecontents = include_r($cssinc);
	
	
	$css_inc = '<link rel="stylesheet" type="text/css" href="http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$_GET['pageTemp'].'/custom.css" />';
	
	$add_dis = $css_inc."\n";
	$add_dis .= "</head>\n";
	
	$pagecontents = eregi_replace('</head>', $add_dis, $pagecontents);
	
	
//	function traverseIt(daItem){
//   	var home_base = document.getElementById(daItem);
//   	//alert(home_base.childNodes.length)
//   	for(var j=0; j < home_base.childNodes.length; j++){
//   	   var cur_item = home_base.childNodes[j];
//   	   alert(cur_item
//   	}
//	}
	
	$javascript_content = "";
	$javascript_content .= "<script language=\"javascript\">\n";
	
   $javascript_content .= "function GiveDec(Hex)\n";
   $javascript_content .= "{\n";
   
   $javascript_content .= "   if(Hex == 'A')\n";
   $javascript_content .= "      Value = 10;\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Hex == 'B')\n";
   $javascript_content .= "      Value = 11;\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Hex == 'C')\n";
   $javascript_content .= "      Value = 12;\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Hex == 'D')\n";
   $javascript_content .= "      Value = 13;\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Hex == 'E')\n";
   $javascript_content .= "      Value = 14;\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Hex == 'F')\n";
   $javascript_content .= "      Value = 15;\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "      Value = eval(Hex);\n";
   $javascript_content .= "   return Value;\n";
   $javascript_content .= "}\n";
   
   $javascript_content .= "function GiveHex(Dec)\n";
   $javascript_content .= "{\n";
   $javascript_content .= "   if(Dec == 10)\n";
   $javascript_content .= "      Value = \"A\";\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Dec == 11)\n";
   $javascript_content .= "      Value = \"B\";\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Dec == 12)\n";
   $javascript_content .= "      Value = \"C\";\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Dec == 13)\n";
   $javascript_content .= "      Value = \"D\";\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Dec == 14)\n";
   $javascript_content .= "      Value = \"E\";\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "   if(Dec == 15)\n";
   $javascript_content .= "      Value = \"F\";\n";
   $javascript_content .= "   else\n";
   $javascript_content .= "      Value = \"\" + Dec;\n";
   $javascript_content .= "\n";
   $javascript_content .= "   return Value;\n";
   $javascript_content .= "}\n";
   $javascript_content .= "\n";
   $javascript_content .= "function HexToDec(myHex)\n";
   $javascript_content .= "{\n";
   $javascript_content .= "   Input = myHex;\n";
   $javascript_content .= "   Input = Input.toUpperCase();\n";
   $javascript_content .= "   a = GiveDec(Input.substring(0, 1));\n";
   $javascript_content .= "   b = GiveDec(Input.substring(1, 2));\n";
   $javascript_content .= "   c = GiveDec(Input.substring(2, 3));\n";
   $javascript_content .= "   d = GiveDec(Input.substring(3, 4));\n";
   $javascript_content .= "   e = GiveDec(Input.substring(4, 5));\n";
   $javascript_content .= "   f = GiveDec(Input.substring(5, 6));\n";
   $javascript_content .= "   x = (a * 16) + b;\n";
   $javascript_content .= "   y = (c * 16) + d;\n";
   $javascript_content .= "   z = (e * 16) + f;\n";
   $javascript_content .= "   var col_val = 'rgb('+x+','+y+','+z+')';\n";
   $javascript_content .= "   return(col_val)\n";
   $javascript_content .= "}\n";

	
	
   $javascript_content .= "function mkObject() {\n";
   $javascript_content .= "   var x;\n";
   $javascript_content .= "   var browser = navigator.appName;\n";
   $javascript_content .= "   if ( browser == \"Microsoft Internet Explorer\" ) {\n";
   $javascript_content .= "      x = new ActiveXObject(\"Microsoft.XMLHTTP\");\n";
   $javascript_content .= "   } else {\n";
   $javascript_content .= "      x = new XMLHttpRequest();\n";
   $javascript_content .= "   }\n";
   $javascript_content .= "   return x;\n";
   $javascript_content .= "}\n";
   
   $javascript_content .= "var request = mkObject();\n";
   $javascript_content .= "function ajaxGet(qryString, boxid) {\n";
   $javascript_content .= "   rezBox = boxid; // Make global so parseInfo can get it\n";
   $javascript_content .= "   request.open('get', qryString);\n";
   $javascript_content .= "   request.onreadystatechange = placeInfo;\n";
   $javascript_content .= "   request.send('');\n";
   $javascript_content .= "}\n";
   
   $javascript_content .= "function placeInfo() {\n";
   $javascript_content .= "   if ( request.readyState == 1 ) {\n";
   $javascript_content .= "      document.getElementById(rezBox).innerHTML = 'Loading...';\n";
   $javascript_content .= "   }\n";
   $javascript_content .= "   if ( request.readyState == 4 ) {\n";
   $javascript_content .= "      var answer = request.responseText;\n";
   $javascript_content .= "      document.getElementById(rezBox).innerHTML = answer;\n";
   $javascript_content .= "   }\n";
   $javascript_content .= "}\n";
   
   $javascript_content .= "   function pullStyleFromTree(ele, eleStyle, badStyle){\n";
   $javascript_content .= "      var startItem = ele;\n";
   $javascript_content .= "      var startItemParent = ele.parentNode;\n";
   $javascript_content .= "      var allStyles = document.defaultView.getComputedStyle(startItem, null);\n";
   $javascript_content .= "      var v=allStyles.getPropertyValue(eleStyle)\n";
   $javascript_content .= "      if(v && v != badStyle){\n";
   $javascript_content .= "         alert(v)\n";
   $javascript_content .= "         var myV = 'billy';\n";
   
   $javascript_content .= "         return myV;\n";
   
   $javascript_content .= "      }else{\n";
   $javascript_content .= "         pullStyleFromTree(startItemParent, eleStyle, badStyle)\n";
   $javascript_content .= "      }\n";
   $javascript_content .= "   }\n";
   
	
	
   $javascript_content .= "      function cStyles(ele){\n";
   $javascript_content .= "         var RefDiv = ele;\n";
   $javascript_content .= "         var ParRefDiv = RefDiv.parentNode;\n";
   //$javascript_content .= "         alert(RefDiv+'---'+ParRefDiv)\n";
   
   $javascript_content .= "         var allStyles = document.defaultView.getComputedStyle(RefDiv, null);\n";
   $javascript_content .= "         var ParallStyles = document.defaultView.getComputedStyle(ParRefDiv, null);\n";

   $javascript_content .= "         for(var i=0;i<allStyles.length;++i){\n";
   $javascript_content .= "            var e=allStyles.item(i)\n";
   $javascript_content .= "            var v=allStyles.getPropertyValue(e)\n";
   $javascript_content .= "            var pv=ParallStyles.getPropertyValue(e);\n";
   
   $javascript_content .= "            if( v!=pv || e=='color' || e=='background-color' ){\n";
   $javascript_content .= "               if(e!='width' && e!='display' && e!='height' && e!='float'){\n";
   $javascript_content .= "                  if( e=='background-color' ){\n";
   //$javascript_content .= "                         alert(v+'---'+pv)\n";
	$javascript_content .= "                     if(ParRefDiv.bgColor){\n";
   $javascript_content .= "                        alert('br color!!!---'+ParRefDiv.bgColor)\n";
   $javascript_content .= "                        var daBgColor = ParRefDiv.bgColor.replace('#','')\n";
   $javascript_content .= "                        v = HexToDec(daBgColor)\n";
   //$javascript_content .= "                        alert(e+'--dobedo--'+v)\n";
   $javascript_content .= "                     }else{\n";
   $javascript_content .= "                        var newV = pullStyleFromTree(RefDiv, 'background-color', 'transparent')\n";
   $javascript_content .= "                        alert(newV+'---'+pv)\n";
   $javascript_content .= "                     }\n";
   //$javascript_content .= "                     alert(v+'--joe--'+pv)\n";
   $javascript_content .= "                  }\n";
   
   $javascript_content .= "                  document.getElementById('cssStyles').innerHTML += e+': '+v+';'\n";
   $javascript_content .= "               }\n";
   $javascript_content .= "            }\n";
   $javascript_content .= "         }\n";
   //$javascript_content .= "         alert('done')\n";
   $javascript_content .= "      }\n";
	
	$javascript_content .= "var contentElement;\n";
	$javascript_content .= "function traverseIt(daItem, childLvl){\n";
	$javascript_content .= "   var home_base = daItem;\n";
	//$javascript_content .= "   alert(home_base.childNodes.length)\n";
	$javascript_content .= "   for(var j=0; j < home_base.childNodes.length; j++){\n";
	$javascript_content .= "      var cur_item = home_base.childNodes[j];\n";
	$javascript_content .= "      if(cur_item.nodeName != 'SCRIPT' && cur_item.nodeType == 1 && cur_item.childNodes.length > 0) { \n";
	$javascript_content .= "         var lvlSpace = '';\n";
	$javascript_content .= "         for(var spc=0; spc <= childLvl; spc++){\n";
	$javascript_content .= "            var lvlSpace = lvlSpace+'  --  ';\n";
	$javascript_content .= "         }\n";
	$javascript_content .= "         var newLvl = childLvl+1;\n";
	//('+childLvl+')('+newLvl+')
	//$javascript_content .= "         document.getElementById('footer').innerHTML += '<br/>'+lvlSpace+'current id=('+cur_item.id+') current nodeName=('+cur_item.nodeName+') current nodeType=('+cur_item.nodeType+') num childNodes=('+cur_item.childNodes.length+')'\n";
	$javascript_content .= "         var isContent = cur_item.innerHTML.search('#CONTENT#')\n";
	$javascript_content .= "         if(isContent > -1){\n";
	//$javascript_content .= "            document.getElementById('footer').innerHTML += '<br/>isContent=('+isContent+')'\n";
	$javascript_content .= "            contentElement = cur_item;\n";
	$javascript_content .= "         }\n";
	$javascript_content .= "         traverseIt(cur_item, newLvl)\n";
	$javascript_content .= "      }\n";
	
	$javascript_content .= "   }\n";
	$javascript_content .= "}\n";
	
	
	//$javascript_content .= "alert('something');\n";
	
	$javascript_content .= "var myStart = document.body\n";
	$javascript_content .= "traverseIt(myStart, 0)\n";
	//$javascript_content .= "alert(contentElement.id)\n";
	
   $javascript_content .= "var newdiv=document.createElement(\"div\")\n";
   $javascript_content .= "newdiv.setAttribute(\"id\", \"cssStyles\")\n";
   $javascript_content .= "document.body.appendChild(newdiv)\n";
   
	
	$javascript_content .= "cStyles(contentElement)\n";
	
	$javascript_content .= "var daStyles = document.getElementById('cssStyles').innerHTML;\n";
	
	//$javascript_content .= "alert(daStyles)\n";
	
	$javascript_content .= "ajaxGet('writeCss.php?theCss='+daStyles, 'left-col');\n";
	
	
	
	
	$javascript_content .= "</script>\n";
	$javascript_content .= "</html>\n";
	
	
	$pagecontents = eregi_replace('</html>', $javascript_content, $pagecontents);
	
	echo $pagecontents;
}

$site = 'http://'.$_SESSION['this_ip'].'/sohoadmin/program/modules/site_templates/pages/'.$_GET['pageTemp'].'/index.html';

	$cam2 = MitchAllTogether($site);
//	echo testArray($cam2);
	
//	echo "body {\n";
//	echo "background-image: none;\n";	
//   echo "background-repeat: repeat;\n";
//   echo "float: left;\n";
//   echo "height: 51.4667px;\n";
//   echo "margin-left: 4px;\n";
//   echo "padding-bottom: 7px;\n";
//   echo "padding-left: 12px;\n";
//   echo "padding-right: 12px;\n";
//   echo "padding-top: 7px;\n";
//   echo "width: 530px;\n";
//   echo "color: rgb(255, 255, 255);\n";
//   echo "}\n";
   

	
	//echo "body { background-color: white;\n margin: 0px;\n \n padding: 0px;  } \n";
	exit;

?>
