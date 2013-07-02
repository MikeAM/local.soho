<?php
	/// Login button function
	###------------------------------------------------------------------------------------###
	if (eregi("#LOGINBTN#", $template_line[$xedusvar])) {
		if(isset($_SESSION['GROUPS'])) {
			$loginbtn = "<a href=\"/Logout.php\"><img src=\"images/logout.jpg\" alt=\"image\" width=\"93\" height=\"16\" border=\"0\" /></a>";
		} else {
			$loginbtn = "<a href=\"/pgm-secure_login.php\"><img src=\"images/userlogin.jpg\" alt=\"image\" width=\"93\" height=\"16\" border=\"0\" /></a>";
		}
		
		$template_line[$xedusvar] = eregi_replace("#LOGINBTN#", $loginbtn, $template_line[$xedusvar]);
	}
	
	if (eregi('#HOMELINK#', $template_line[$xedusvar])) {
		$homepagelink = "http://".$_SESSION['this_ip'];
		$template_line[$xedusvar] = eregi_replace("#HOMELINK#", $homepagelink, $template_line[$xedusvar]);
	}
	
	/// #HMAINS# - Place horizontal sub menu in tamplate
	###------------------------------------------------------------------------------------###
	if (eregi("#HMAINSARRAY#", $template_line[$xedusvar])) {
		$hmainzprint = "";
		for($g=0;$g<count($hmainz);$g++){
			$hmainzprint .= $hmainz[$g]."\n";
		}
		$hmain_menu = "\n\n<!-- START AUTO MENU SYSTEM -->\n\n<div id=\"containerLeftnav\">\n<ul><li>\n" . $hmainzprint . "\n</li></ul>\n</div>\n\n<!-- END AUTO MENU SYSTEM -->\n\n";
		$template_line[$xedusvar] = eregi_replace("#HMAINSARRAY#", $hmain_menu, $template_line[$xedusvar]);
	}
	/// #TMENU# - Place Text Menu into Template
	###------------------------------------------------------------------------------------###
	if (eregi("#TMENU#", $template_line[$xedusvar])) {
		$tmenu = "";
		if ($textmenu == "on") {
			$tmenu = "$main_textmenu ]";
		}
		$tmenu = eregi_replace("\|  ]", "", $tmenu);
		$template_line[$xedusvar] = eregi_replace("#TMENU#", $tmenu, $template_line[$xedusvar]);
	}
if (eregi("#PAGE_PARENT#", $template_line[$xedusvar])) {
$pageRequest1 = eregi_replace("_", " ", $pageRequest);
/*$pagelink1 = eregi_replace("_", " ", $pagelink);*/
$currentpage1=eregi_replace("_", " ", $currentpage);
	if($numsubpages!=0) 
	{
		if($currentpage !=$pageRequest)
		{
    		$template_line[$xedusvar] = eregi_replace("#HOME_PAGE#","<a href=\"http://".$_SESSION['this_ip']."/\" class=\"breadcrumbs\"><b>Home</b></a>", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#PAGE_PARENT#",">> <a href=\"".$currentpage.".php\" class=\"breadcrumbs\"><b>".$currentpage1."</b></a>", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#PAGENAME#",">> ".$pageRequest1, $template_line[$xedusvar]);
		}
		else
		{
		if($pageRequest != "Home_Page")
		{
			$template_line[$xedusvar] = eregi_replace("#HOME_PAGE#","<a href=\"http://".$_SESSION['this_ip']."/\" class=\"breadcrumbs\"><b>Home</b></a>", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#PAGE_PARENT#", "", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#PAGENAME#",">> ".$pageRequest1, $template_line[$xedusvar]);
		}
			else
			{
			$template_line[$xedusvar] = eregi_replace("#PAGE_PARENT#", "", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#PAGENAME#","", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#HOME_PAGE#","Home", $template_line[$xedusvar]);
			}
		}
		
	}
	else
	{
	if($pageRequest != "Home_Page"){
        	$template_line[$xedusvar] = eregi_replace("#HOME_PAGE#","<a href=\"http://".$_SESSION['this_ip']."/\" class=\"breadcrumbs\"><b>Home</b></a>", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#PAGE_PARENT#","", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#PAGENAME#",">> ".$pageRequest1, $template_line[$xedusvar]);
	} 
	else
			{
			$template_line[$xedusvar] = eregi_replace("#PAGE_PARENT#", "", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#PAGENAME#","", $template_line[$xedusvar]);
			$template_line[$xedusvar] = eregi_replace("#HOME_PAGE#","Home", $template_line[$xedusvar]);
			}
			
	}
}
	
	/*if (eregi("#COPYRIGHT#", $template_line[$xedusvar]))
	{
		//die($copyright);
		$copyright = substr($copyright,1,strlen($copyright));
		$template_line[$xedusvar] = eregi_replace("#COPYRIGHT#", $copyright, $template_line[$xedusvar]);
	}*/
	$visitcount = $HTTP_COOKIE_VARS["visits"];
	if($visitcount>50000)
	 {
	 $visitcount=1;
	  }
	if( $visitcount == "")	
	$visitcount = 0;
	else
	 $visitcount++;
	 
	setcookie("visits",$visitcount);
?>
