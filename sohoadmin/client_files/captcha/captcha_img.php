<?php

header('Expires: Tue, 08 Oct 1991 00:00:00 GMT');
header('Cache-Control: no-cache, must-revalidate');
header("Content-type: image/png");
error_reporting('E_PARSE');
session_start();
$xu=0;
$keykeystring = strtolower($_SESSION['form_verification_key'][$_SESSION['form_verification']]);
unset($keyzkeyzz);
unset($keyzkeyzzttf);
while($xu<6){
	$keyzkeyzz[]=str_replace(basename(__FILE__),'',__FILE__).$keykeystring[$xu].'.gif';
	$keyzkeyzzttf[]=$keykeystring[$xu];
	++$xu;
	
}


$font = str_replace(basename(__FILE__),'',__FILE__).'captcha2.ttf';
$iOut = imagecreatetruecolor("216","34");

$black = imagecolorallocate($iOut, 0, 0, 0);
$white = imagecolorallocate($iOut, 255, 255, 255);


if(function_exists('imagettftext')){
	$imglft=0;
	foreach($keyzkeyzzttf as $fval){		
		//$fval='A';
		if(preg_match('/[0-9]/',$fval)){
			imagefilledrectangle($iOut, $imglft, -1, ($imglft+36), 34, $white);
			imagerectangle($iOut, ($imglft-1), -1, ($imglft+36), 34, $black);
			
			imagettftext($iOut, 34, 0, ($imglft+4), 28, $black, $font, $fval);
			
		} else {
			imagefilledrectangle($iOut, $imglft, -1, ($imglft+36), 34, $black);
			imagerectangle($iOut, ($imglft-1), -1, ($imglft+36), 34, $black);
			imagettftext($iOut, 34, 0, ($imglft+4), 28, $white, $font, strtoupper($fval));	
			
		}
		
		$imglft=$imglft+36;
	}
	imagepng($iOut);

} else {
	$imgBuf = array();
	foreach ($keyzkeyzz as $link){
		$iTmp = imagecreatefromgif($link);
		array_push($imgBuf,$iTmp);
	}

	imagecopy ($iOut,$imgBuf[0],0,0,0,0,imagesx($imgBuf[0]),imagesy($imgBuf[0]));
	
	$imglft=0;
	
	imagerectangle($iOut, -1, -1, ($imglft+36), 34, $black);
	imagedestroy ($imgBuf[0]);
	$imglft=36;
	imagecopy ($iOut,$imgBuf[1],$imglft,0,0,0,imagesx($imgBuf[1]),imagesy($imgBuf[1]));
	imagerectangle($iOut, $imglft, -1, ($imglft+36), 34, $black);
	imagedestroy ($imgBuf[1]);
	$imglft=$imglft+36;
	imagecopy ($iOut,$imgBuf[2],$imglft,0,0,0,imagesx($imgBuf[2]),imagesy($imgBuf[2]));
	imagerectangle($iOut, $imglft, -1, ($imglft+36), 34, $black);
	imagedestroy ($imgBuf[2]);
	$imglft=$imglft+36;
	imagecopy ($iOut,$imgBuf[3],$imglft,0,0,0,imagesx($imgBuf[3]),imagesy($imgBuf[3]));
	imagerectangle($iOut, $imglft, -1, ($imglft+36), 34, $black);
	imagedestroy ($imgBuf[3]);
	$imglft=$imglft+36;
	imagecopy ($iOut,$imgBuf[4],$imglft,0,0,0,imagesx($imgBuf[4]),imagesy($imgBuf[4]));
	imagerectangle($iOut, $imglft, -1, ($imglft+36), 34, $black);
	imagedestroy ($imgBuf[4]);
	$imglft=$imglft+36;
	imagecopy ($iOut,$imgBuf[5],$imglft,0,0,0,imagesx($imgBuf[5]),imagesy($imgBuf[5]));
	imagerectangle($iOut, $imglft, -1, ($imglft+36), 34, $black);
	//imagerectangle($iOut, 0, 0, 216, 34, $black);
	imagedestroy ($imgBuf[5]);
	imagepng($iOut); 
}


?>