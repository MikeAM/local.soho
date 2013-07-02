<?php

echo "(".$getTemplate['folder_name'].")<br/>\n";
echo "(".$addonSiteRoot."/templates/".$getTemplate['folder_name']."/index.html)<br/>\n";


# Check for template features
$template_features = array();

if(file_exists($addonSiteRoot."/templates/".$getTemplate['folder_name']."/home.html")){ $template_features[] = "Home Page Splash"; }
if(file_exists($addonSiteRoot."/templates/".$getTemplate['folder_name']."/news.html")){ $template_features[] = "Custom News Layout"; }
if(file_exists($addonSiteRoot."/templates/".$getTemplate['folder_name']."/cart.html")){ $template_features[] = "Custom Cart Layout"; }

if(file_exists($addonSiteRoot."/templates/".$getTemplate['folder_name']."/index.html")){
   echo "<b>OKOOKOKOKK</b>";
   $template_HTML = file_get_contents($addonSiteRoot."/templates/".$getTemplate['folder_name']."/index.html");
	if(eregi("#VMENU#", $template_HTML) || eregi("#VMAINS#", $template_HTML) || eregi("#VSUBS#", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Vertical Menu";
	}
	if(eregi("#HMENU#", $template_HTML) || eregi("#HMAINS#", $template_HTML) || eregi("#HSUBS#", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Horizontal Menu";
	}
	if(eregi("#TMENU#", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Text Link Menu";
	}
	if(eregi("#USERSONLINE#", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Users Online Display";
	}
	if(eregi("#LOGO#", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Header Logo Text";
	}
	if(eregi("#LOGOIMG#", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Swappable Logo Image";
	}
	if(eregi("#SLOGAN#", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Slogan Text";
	}
	if(eregi("#BOX[0-9]#", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Template Boxes";
	}
	if(eregi("#PROMOTXT", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Promotional Boxes";
	}
	if(eregi("#NEWSBOX", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "News Boxes";
	}
	if(eregi("#BIZ-", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Business Info";
	}
	if(eregi("#CUSTOMPHP", $template_HTML) || eregi("#CUSTOMINC", $template_HTML) || eregi("#INC-", $template_HTML)){
		$is_soho = 1;
		$template_features[] = "Custom Includes";
	}

	# _userimg-
	if(eregi("_userimg-", $template_HTML) ){
		$is_soho = 1;
		$template_features[] = "Swappable Templates Images";
	}

	# Error if no pound vars found
	if ( $is_soho != 1 ) {
	   $template_features[] = "Oops, this template does not seem to be in the correct format.  Please select a different template.";
	}
}

?>