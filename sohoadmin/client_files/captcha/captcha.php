<?php
	$pattern = "abdehklmprsuwx123456789";	
	$capcapkey = "\n<div class=\"field-container\" style=\"display:block;\">\n";
	$synckey = '';
	$capcapkeyoutput='';
	for($i=0;$i<6;$i++){  	
		$capcapkeyoutput .= "		<td style=\"height:36px;width:36px;\" align=\"left\"><image src=\"sohoadmin/client_files/captcha/";
		$keyz = $pattern{rand(0,22)};		
		$capcapkeyoutput .= $keyz.".gif\" width=\"36px\" height=\"34px\" style=\"border:1px solid black;\"></td>\n";
		$synckey .= $keyz;
	}
	
	if(function_exists('imagecreatetruecolor')){
		$capcapkeyoutput = "		<td colspan=\"6\" align=\"left\" style=\"height:36px;width:216px;\"><image style=\"border:1px solid black; width:216px; height:34px;\" src=\"sohoadmin/client_files/captcha/captcha_img.php\"></td>\n";
	}

	$synckey = strtoupper($synckey);
	$_SESSION['form_verification'] = md5($synckey);
	$_SESSION['form_verification_key'][md5($synckey)] = $synckey;
	$capcapkeyoutput = "	<table align=left style=\"height:36px;width:216px;\" cellpadding=\"0\"  cellspacing=\"0\"><tr>\n		".$capcapkeyoutput."	</tr></table>\n";
	$capcapkey .= $capcapkeyoutput;
	$capcapkey .= "	<div style=\"clear:left;text-align:left;white-space:nowrap;padding:4px 0px;\">\n		".lang("Please enter the phrase above")."\n";
	$capcapkey .= "		<input name=\"capval\"  id=\"capval\" type=\"hidden\" value=\"".md5($synckey)."\">\n";
	$capcapkey .= "		<input name=\"cap\"  id=\"cap\" type=\"text\" size=\"6\" maxlength=\"6\" autocomplete=\"off\" style=\"border:1px solid black; text-align:left; font-size:18px;\">\n	</div>\n";
	$capcapkey .= "</div>\n";
	echo $capcapkey;
	//echo $ckey;
//	echo "<br/>";
//	echo $synckey;
//	echo "<br/>";
	//echo md5($synckey);
?>