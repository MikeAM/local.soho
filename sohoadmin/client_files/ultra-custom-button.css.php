<?php
error_reporting('341');
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

if(preg_match('/(?i)msie [0-9]{1,2}/i',$_SERVER['HTTP_USER_AGENT'])){
	header("X-Content-Type-Options: nosniff");
	header("Content-type: text/css", true);	
} else {
	header("Content-Type: text/css");
}
session_cache_limiter('none');
session_start();
$buttonColor='#1D88D9';
$buttonColor='#828282';
$activeColor='';

$buttonColor='#E2E2E2';

$idtouse='';

include_once("../client_files/pgm-site_config.php");
include_once("../program/includes/shared_functions.php");

$globalprefObj = new userdata('global');
$buttonColor=$globalprefObj->get("buttonColor");
$activeColor=$globalprefObj->get("activeColor");

if($_GET['mode']=='cart'){
	$cartprefs = new userdata("cart");
	if($cartprefs->get("buttonColor")!=''){
		$buttonColor=$cartprefs->get("buttonColor");
	} else {
		if($buttonColor=='disabled'){
			$buttonColor='';
			$activeColor='';
		}
	}
	if($cartprefs->get("activeColor")!=''){
		$activeColor=$cartprefs->get("activeColor");
	}
	if($_GET['id']!='' && $_GET['hex']=='disabled'){
		exit;	
	}
	if($buttonColor=='disabled'&&$_GET['id']==''){
		exit;	
	}
}

if($_SESSION['CUR_USER']!=''&&$_SESSION['PHP_AUTH_USER']!=''){
	if($_GET['hex']!=''){
		if($_GET['hex']=='disabled'){
			exit;	
		}
		//$_GET['hex']=str_replace('#','',$_GET['hex']);
		$buttonColor='#'.str_replace('#','',$_GET['hex']);
	}
	if($_GET['hex2']!=''){
		//$_GET['hex2']=str_replace('#','',$_GET['hex2']);
		$activeColor='#'.str_replace('#','',$_GET['hex2']);
	}
	if($_GET['id']!=''){
		$idtouse='#'.$_GET['id'].' ';	
	}
}

if($_GET['id']=='' && $buttonColor=='disabled' && $_GET['mode']!='cart'){
		exit;
}

if($buttonColor==''){
	$buttonColor='#DBDBDB';	
}

// <link href="/sohoadmin/client_files/ultra-custom-button.css.php" rel="stylesheet" type="text/css" />

//$buttonColor='#4CB1EB';
//$buttonColorGradient='#1D88D9';
//$activeColor='#49AAE3';
//$activeColorGradient='#1A7CC7';

function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);
   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   return $rgb;
}

function rgb2hex($rgb) {
   $hex = "#";
   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
   return strtoupper($hex); // returns the hex value including the number sign (#)
}

function getfont($bColor){
	$smval=0;
	$hrgb = hex2rgb($bColor);
	foreach($hrgb as $tval){
		$smval=$tval+$smval;	
	}
	// echo $smval;
	
	if($smval > 500 || ($hrgb['0']+$hrgb['1']) > 340){
		return '#565656';	
	} else {
		return 'white';		
	}
}
function findcounter($color){
		$ca=hex2rgb($color);
		$newrgb=array();
		if(getfont($color)=='white'){
			foreach($ca as $val){
				$newrgb[]=((255-$val)/2.1)+$val;
			}
		} else {
			foreach($ca as $val){
				$newrgb[]=((255-$val)/2.4)+$val;
			}
		}
		return(rgb2hex($newrgb));
}

function findHilight($color){
		$ca=hex2rgb($color);
		$newrgb=array();
		if(getfont($color)=='white'){
			foreach($ca as $val){
				$newrgb[]=((255-$val)/4.5)+$val;
			}
		} else {
			foreach($ca as $val){
				//$newrgb[]=$val+((255-$val)/2);
				if($val==255){
					$val=round($val-($val*.02));	
				} else {
					$val=round($val+($val*.04));	
				}
				if($val > 255){ $val=255; }
				if($val < 0){ $val=0; }
				
				$newrgb[]=$val;
				
			}
		}
		return(rgb2hex($newrgb));
}
//$buttonColorGradient='#4CB1EB';
//$activeColor='#1A7CC7';
//$activeColorGradient='#49AAE3';
//$buttonColor='#00B205';
//$buttonColor='#FFF8E8';
//$buttonColor='#FFF5DD';
////$activeColor='#1A7CC7';
//$activeColor='#00D303';
//$activeColor='#FFDC9B';
////$textcolor='white';

$textColor=getfont($buttonColor);

$textColorActive=getfont($activeColor);
//$textColorActive='white';
if($activeColor==''){
	$activeColor=findHilight($buttonColor);
	$textColorActive=$textColor;
}
//echo $activeColor;
$buttonColorGradient=findcounter($buttonColor);
$activeColorGradient=findcounter($activeColor);


echo $idtouse."button,".$idtouse."input[type=\"button\"],".$idtouse."input[type=\"file\"],".$idtouse."input[type=\"submit\"],".$idtouse."button:hover,".$idtouse."input[type=button]:hover,".$idtouse."input[type=\"file\"]:hover,".$idtouse."input[type=\"submit\"]:hover,".$idtouse."button:active,".$idtouse."input[type=button]:active,".$idtouse."input[type=\"file\"]:active,".$idtouse."input[type=\"submit\"]:active {\n";
$border_depth=4;
?>
	cursor: pointer;

	display: inline-block;
	background-attachment: scroll;
	background-clip: border-box;
	padding: 4px 8px;
	font-size:9pt;
	font-family:Helvetica,Arial,Verdana,sans-serif;
	
<?php
echo "	-webkit-border-radius: ".$border_depth."px;
	-moz-border-radius: ".$border_depth."px;
	border-radius: ".$border_depth."px;\n";
?>
	-webkit-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.65) inset, 0 1px 2px rgba(0, 0, 0, 0.45);
	-moz-box-shadow: 0 1px 0 rgba(255, 255, 255, 0.65) inset, 0 1px 2px rgba(0, 0, 0, 0.45);
	box-shadow: 0 1px 0 rgba(255, 255, 255, 0.65) inset, 0 1px 2px rgba(0, 0, 0, 0.45);
<?php
if($textColor=='white'){
	//echo "border-width:1px solid transparent;\n";
	echo "border:1px solid ".$buttonColor.";\n";
} else {
	echo "border:1px solid ".$buttonColor.";\n";
}
	echo "	color: ".$textColor.";\n";

	if(!preg_match('/(?i)msie [0-9]{1,2}/i',$_SERVER['HTTP_USER_AGENT'])){
		echo "font-weight:600;\n";	
	}
	?>
	text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.1);	
	text-align:center;
	text-decoration: none;
	vertical-align: middle;
}

<?php
	echo $idtouse."button,".$idtouse."input[type=button],".$idtouse."input[type=\"submit\"],".$idtouse."input[type=\"file\"]{\n";
	if(preg_match('/(?i)msie [0-9]{1,2}/i',$_SERVER['HTTP_USER_AGENT'])){
		echo "	background-color: ".$buttonColor.";\n";
	}
	echo "	background: -webkit-gradient(linear, left top, left bottom, from(".$buttonColorGradient."), to(".$buttonColor."));
	background: -webkit-linear-gradient(top, ".$buttonColorGradient.", ".$buttonColor.");
	background: -moz-linear-gradient(top, ".$buttonColorGradient.", ".$buttonColor.");
	background: -ms-linear-gradient(top, ".$buttonColorGradient.", ".$buttonColor.");
	background: -o-linear-gradient(top, ".$buttonColorGradient.", ".$buttonColor.");
}\n\n";

	echo $idtouse."button:hover,".$idtouse."input[type=button]:hover,".$idtouse."input[type=\"submit\"]:hover,".$idtouse."input[type=\"file\"]:hover,".$idtouse."button:active,".$idtouse."input[type=\"file\"]:active,".$idtouse."input[type=button]:active,".$idtouse."input[type=\"submit\"]:active {\n";
	if(preg_match('/(?i)msie [0-9]{1,2}/i',$_SERVER['HTTP_USER_AGENT'])){
		echo "	background-color: ".$activeColor.";\n";
	}
	echo "	color: ".$textColorActive.";\n";
	if($textColorActive=='white'){
		//echo "border-width:1px solid transparent;\n";
		echo "border:1px solid ".$activeColor.";\n";
	} else {
		echo "border:1px solid ".$activeColor.";\n";
	}

	
echo "	background: -webkit-gradient(linear, left top, left bottom, from(".$activeColorGradient."), to(".$activeColor."));
	background: -webkit-linear-gradient(top, ".$activeColorGradient.", ".$activeColor.");
	background: -moz-linear-gradient(top, ".$activeColorGradient.", ".$activeColor.");
	background: -ms-linear-gradient(top, ".$activeColorGradient.", ".$activeColor.");
	background: -o-linear-gradient(top, ".$activeColorGradient.", ".$activeColor.");
}\n\n";

	echo $idtouse."button:hover,".$idtouse."input[type=button]:hover,".$idtouse."input[type=\"submit\"]:hover,".$idtouse."input[type=\"file\"]:hover{
    -moz-text-blink: none;
    -moz-text-decoration-color: -moz-use-text-color;
    -moz-text-decoration-line: none;
    -moz-text-decoration-style: solid;
}\n\n";

	echo $idtouse."button:active,".$idtouse."input[type=button]:active,".$idtouse."input[type=\"submit\"]:active,".$idtouse."input[type=\"file\"]:active {
	box-shadow: 0 1px ".$border_depth."px rgba(0, 0, 0, 0.5) inset;
}\n\n";


$classT='.FormLt1';
echo $idtouse."button".$classT.",".$idtouse."input[type=\"button\"]".$classT.",".$idtouse."input[type=\"file\"]".$classT.",".$idtouse."input[type=\"submit\"]".$classT.",".$idtouse."button:hover".$classT.",".$idtouse."input[type=button]:hover".$classT.",".$idtouse."input[type=\"file\"]:hover".$classT.",".$idtouse."input[type=\"submit\"]:hover".$classT.",".$idtouse."button:active".$classT.",".$idtouse."input[type=button]:active".$classT.",".$idtouse."input[type=\"file\"]:active".$classT.",".$idtouse."input[type=\"submit\"]:active {\n";
//echo "	padding: 3px 6px;\n";
//echo "	font-size:7pt;\n";
//echo "	font-family:Helvetica,Arial,Verdana,sans-serif;\n";
echo "}\n";

?>