<?php

session_start();

error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }



###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston
## Copyright 2003-2007 Soholaunch.com, Inc.
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

$filename = "$cgi_bin/menu.conf";
$file = fopen("$filename", "r");
	$body = fread($file,filesize($filename));
fclose($file);
$lines = split("\n", $body);
$numLines = count($lines);
for ($xedusvar=0;$xedusvar<=$numLines;$xedusvar++) {
	$temp = split("=", $lines[$xedusvar]);
	$variable = $temp[0];
	$value = $temp[1];
	${$variable} = $value;
}

$filename = "$cgi_bin/menucolor.conf";
$file = fopen("$filename", "r");
	$body = fread($file,filesize($filename));
fclose($file);
$lines = split("\n", $body);
$numLines = count($lines);
for ($xedusvar=0;$xedusvar<=$numLines;$xedusvar++) {
	$temp = split("=", $lines[$xedusvar]);
	$variable = $temp[0];
	$value = $temp[1];
	${$variable} = $value;
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

$mainmenu = "vertical"; // Since 4.5 Release 2, this is the default for all nav systems


######################################################################################
##====================================================================================
// STEP 2: Define all Sub-Pages of Current PageRequest
##====================================================================================
######################################################################################
function get_subpages($pageRequest,$ppageRequest) {
$thispage = eregi_replace("_", " ", $ppageRequest);
//$pthispage = eregi_replace("_", " ", $ppageRequest);

$result = mysql_query("SELECT page_name, sub_page_of, link, type FROM site_pages WHERE sub_page_of LIKE '$thispage~~~%'");
$active_subs = mysql_num_rows($result);

if ($active_subs != 0) {

	$a=0;
	while ($row = mysql_fetch_array ($result)) {
		$a++;
		$tmp = split("~~~", $row[sub_page_of]);

		if (strlen($tmp[1]) == 1) { $tmp[1] = "0".$tmp[1]; } // Make single digit order nums a two digit for sort

		$subpage_order[$a] = $tmp[1]."~~~".$row[page_name]; // subpage_order gets sorted nicely
		$subpage_name[$a] = $row["page_name"];  // but subpage_name doesn't match up!?
		if($row["type"] == "menu"){
			//echo "MENU ITEM! (".$row["link"].")<br>";
			$subpage_link[$a] = $row["link"];
		}else{
			//echo "NOT MENU ITEM!<br>";
			$subpage_link[$a] = "";
		}
	}

	$numsubpages = $a;
	if ($numsubpages > 1) { sort($subpage_order); } // I swear this should be 'asort('

} else {

	$numsubpages = 0;

}

##========================================================================================
## What if we are currently viewing a sub-page?  We should still show the "sub" menu
## options.  Lets Check for sure
## =======================================================================================

if ($numsubpages == 0) {

	$result = mysql_query("SELECT sub_page_of FROM site_pages WHERE page_name = '$thispage'");
	$tmp = mysql_fetch_array($result);

	$dup = $tmp["sub_page_of"];
	$tmp = split("~~~", $dup);
	$dup = $tmp[0]; // Name of main page that this page is a sub of (Parent Page)

	$a=0;

	if ($dup != "") {
		$result = mysql_query("SELECT * FROM site_pages WHERE sub_page_of LIKE '$dup%'"); // Select ALL sub-pages of the Parent Page
		while ($row = mysql_fetch_array ($result)) {
			$a++;
			$tmp = split("~~~", $row[sub_page_of]);
			if (strlen($tmp[1]) == 1) { $tmp[1] = "0".$tmp[1]; } // Make single digit order nums a two digit for sort
			$subpage_order[$a] = $tmp[1]."~~~".$row[page_name];
			$subpage_name[$a] = $row["page_name"];
			if($row["type"] == "menu"){
				//echo "MENU ITEM! (".$row["link"].")<br>";
				$subpage_link[$a] = $row["link"];
			}else{
				//echo "NOT MENU ITEM!<br>";
				$subpage_link[$a] = "";
			}
		}
		$dup = eregi_replace(" ", "_", $dup);

	}

	$numsubpages = $a;
	if ($numsubpages > 1) { sort($subpage_order); }

}


// Only affects buttons
if ($mainmenu != "vertical") {
	$finishLine = "<BR clear=all><img src=spacer.gif width=10 height=2><BR>\n";
	$vertOption = "";
} else {
	$vertOption = "<BR clear=all><img src=spacer.gif width=100 height=4 border=0><BR>\n";
	$finishLine = "<BR clear=all><img src=spacer.gif width=10 height=4><BR>\n";
}


#############################################################################################################
##-----------------------------------------------------------------------------------------------------------
// STEP 3: Define SUB PAGE Text & Button Links
##-----------------------------------------------------------------------------------------------------------
#############################################################################################################

if ($numsubpages != 0) {

	$sub_textmenu = "";
	$sub_quicknav = "";


	if ($MENUTYPE == "buttons") {

		$sub_buttons = "";

	} else {

	   // Begin vmenu sub table (within cell of main link table)
	   // ========================================================
		$sub_buttons = "\n\n   <TABLE BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" ALIGN=\"LEFT\" class=\"vmenu_subs\">\n";

	}


	// Build independant (subs/mains not displayed together) menus regardless
	// ===========================================================================
	
	$hsubz = "<ul class=\"flyout\" >";

	if ($numsubpages > 1) { $substart = 0; } else { $substart = 1; } // Think this is supposed to account for 'name' starting with a blank val and 'order' ending with one


   // Loops through each subpage_order
   // =============================================================
	for ( $allsubs_added=$substart; $allsubs_added<=$numsubpages; $allsubs_added++) {

		// Loops through each subpage_name
		// =============================================================
		for ( $xedusvar=1; $xedusvar<=$numsubpages; $xedusvar++ ) {
         
         // Bug fix #0000623
         $subpage_name[$xedusvar] = str_replace("?", "__QMARK__", $subpage_name[$xedusvar]);
         $subpage_order[$allsubs_added] = str_replace("?", "__QMARK__", $subpage_order[$allsubs_added]);
         
			if (eregi("~~~$subpage_name[$xedusvar]~~~", "$subpage_order[$allsubs_added]~~~")) { // v4.7 RC3: added ~~~'s to prevent duplication of similar page names
			   
			   // Bug fix #0000623
            $subpage_name[$xedusvar] = str_replace("__QMARK__", "?", $subpage_name[$xedusvar]);
            $subpage_order[$allsubs_added] = str_replace("__QMARK__", "?", $subpage_order[$allsubs_added]);
            
				$tmp = eregi_replace(" ", "_", $subpage_name[$xedusvar]);

            // Define on and off SUB MENU classes
			   $urlnew = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

                /*** return the full address ***/

	                if($_SERVER['REQUEST_URI']==''){

                                	$urlnew.='://'.$_SERVER['HTTP_HOST'].'/';

	          	} else {

                              $urlnew.='://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                    }

		      if ($tmp == $pageRequest || $tmp == $dup || strcmp($urlnew,$subpage_link[$xedusvar])==0) {
		         $subBullet = "vmenu_sub_bull_on";
		         $subClass = "vmenu_sub_on";
 				 $subNav = "hsubselected";

		      } else {
		         $subBullet = "vmenu_sub_bull_off";
		         $subClass = "vmenu_sub_off";
				 $subNav = "hsubs";
		      }

				// Create SUB MENU options
				//aaa ----------------------------------------------------------------------------------------
				if( eregi("http://", $subpage_link[$xedusvar]) || eregi("https://", $subpage_link[$xedusvar]) || eregi("mailto", $subpage_link[$xedusvar]) ){
				//$main_buttons .= "    <a href=\"".$subpage_link[$a]."\" class=\"".$mainClass."\">".$thispage."</a>\n";
					$sub_textmenu .= "<a href=\"".$subpage_link[$xedusvar]."\">$subpage_name[$xedusvar]</a> | ";
				} else {
					$sub_textmenu .= "<a href=\"index.php?pr=$tmp\">$subpage_name[$xedusvar]</a> | ";
				}

				if ($MENUTYPE == "buttons") {
					if($subpage_link[$xedusvar] == ""){
						$sub_buttons .= "<INPUT TYPE=BUTTON VALUE=\"$subpage_name[$xedusvar]\" STYLE=\"font-family: Verdana; font-size: 7pt; width: 100px; height: 20px; background: #$menubg; color: #$linkc; cursor: pointer;\" onclick=\"navto('$tmp');\">\n$finishLine\n";
					}else{
         			if( eregi("http://", $subpage_link[$xedusvar]) || eregi("https://", $subpage_link[$xedusvar]) || eregi("mailto", $subpage_link[$xedusvar]) ){
         				$sub_buttons .= "<INPUT TYPE=BUTTON VALUE=\"$subpage_name[$xedusvar]\" STYLE=\"font-family: Verdana; font-size: 7pt; width: 100px; height: 20px; background: #$menubg; color: #$linkc; cursor: pointer;\" onclick=\"navtoLink('$subpage_link[$xedusvar]');\">\n$finishLine\n";
         			}else{
         				$sub_buttons .= "<INPUT TYPE=BUTTON VALUE=\"$subpage_name[$xedusvar]\" STYLE=\"font-family: Verdana; font-size: 7pt; width: 100px; height: 20px; background: #$menubg; color: #$linkc; cursor: pointer;\" onclick=\"navtoLink('$subpage_link[$xedusvar]');\">\n$finishLine\n";
         			}
						//$sub_buttons .= "<INPUT TYPE=BUTTON VALUE=\"$subpage_name[$xedusvar]\" STYLE=\"font-family: Verdana; font-size: 7pt; width: 100px; height: 20px; background: #$menubg; color: #$linkc; cursor: pointer;\" onclick=\"navtoLink('$subpage_link[$xedusvar]');\">\n$finishLine\n";
					}
				} else {
      			$sub_buttons .= "    <tr>\n";

      			// Bullet cell
      			$sub_buttons .= "     <td class=\"".$subBullet."\">\n";
      			$sub_buttons .= "      <div class=\"".$subBullet."\">\n";
      			$sub_buttons .= "       &gt;&gt;\n";
      			$sub_buttons .= "      </div>\n";
      			$sub_buttons .= "     </td>\n";

      			// Link cell
      			$sub_buttons .= "     <td class=\"".$subClass."\">\n";
      			$sub_buttons .= "      <div class=\"".$subClass."\">\n";
      			if($subpage_link[$xedusvar] == ""){
      				$sub_buttons .= "       <a href=\"index.php?pr=".$tmp."\" class=\"".$subClass."\">".$subpage_name[$xedusvar]."</a>\n";
      			}else{
						if( eregi("http://", $subpage_link[$xedusvar]) || eregi("https://", $subpage_link[$xedusvar]) || eregi("mailto", $subpage_link[$xedusvar]) ){
							$sub_buttons .= "       <a href=\"".$subpage_link[$xedusvar]."\" class=\"".$subClass."\">".$subpage_name[$xedusvar]."</a>\n";
						}else{
							$sub_buttons .= "       <a href=\"http://".$subpage_link[$xedusvar]."\" class=\"".$subClass."\">".$subpage_name[$xedusvar]."</a>\n";
						}
      				//$sub_buttons .= "       <a href=\"".$subpage_link[$xedusvar]."\" class=\"".$subClass."\">".$subpage_name[$xedusvar]."</a>\n";
      			}
      			$sub_buttons .= "      </div>\n";
      			$sub_buttons .= "     </td>\n";

      			$sub_buttons .= "    </tr>\n";
				}

				if($subpage_link[$xedusvar] == ""){
					$hsubz .= "  <li class=\"".$subNav."\"><a href=\"index.php?pr=".$tmp."\" class=\"".$subNav."\">".$subpage_name[$xedusvar]."</a></li>\n";
				}else{
					if( eregi("http://", $subpage_link[$xedusvar]) || eregi("https://", $subpage_link[$xedusvar]) || eregi("mailto", $subpage_link[$xedusvar]) ){
						$hsubz .= "  <li class=\"".$subNav."\"><a href=\"".$subpage_link[$xedusvar]."\" class=\"".$subNav."\">".$subpage_name[$xedusvar]."</a></li>\n";
					}else{
						$hsubz .= "  <li class=\"".$subNav."\"><a href=\"http://".$subpage_link[$xedusvar]."\"  class=\"".$subNav."\">".$subpage_name[$xedusvar]."</a></li>\n";
					}
				}
				// ----------------------------------------------------------------------------------------

			}
		} // End For Loop
	} // End Outer Loop

	if ($MENUTYPE != "buttons") {
		$sub_buttons .= "\n   </TABLE>\n\n";
	}
    $hsubz .= "</ul>";
	return ($hsubz);
	 } // End if numsubpages greater than zero
     else {
	
		return false;
	 }// return false if numsubpages equal to zero

}//End sub-menu function

#############################################################################################################
##-----------------------------------------------------------------------------------------------------------
// STEP 4: Define MAIN MENU Text, Buttons and Quick Nav Links
##-----------------------------------------------------------------------------------------------------------
#############################################################################################################

$result = mysql_query("SELECT page_name, type, link FROM site_pages WHERE main_menu <> ' ' AND (type = 'Main' OR type = 'menu' OR type = 'newsletter') ORDER BY main_menu");
$a=0;


$main_textmenu = "";

if ($MENUTYPE == "buttons") {
	$main_buttons = "<form name=netscapebs>\n\n<SCRIPT LANGUAGE=JAVASCRIPT>\n\n";
	$main_buttons .= "function navto(where) {\n";
	$main_buttons .= "     window.location = 'index.php?pr='+where+'';\n";
	$main_buttons .= "}\n\n";
	$main_buttons .= "function navtoLink(where) {\n";
	$main_buttons .= "     window.location = where\n";
	$main_buttons .= "}\n\n</SCRIPT>\n\n";
} else {
	$main_buttons = "<TABLE width=\"100%\" BORDER=\"0\" CELLPADDING=\"0\" CELLSPACING=\"0\" ALIGN=\"CENTER\" CLASS=\"vmenu_mainz\">\n";
}

// Build independant menu vars regardless
$hmainz = "<div id=\"top-menu\" align=\"center\"><ul>";

while ($row = mysql_fetch_array ($result)) {

	$a++;
	$thispage = $row["page_name"];
	if($row["type"] == "menu"){
		//echo "MENU ITEM! (".$row["link"].")<br>";
		$subpage_link[$a] = $row["link"];
	}else{
		//echo "NOT MENU ITEM!<br>";
		$subpage_link[$a] = "";
	}
	$pagelink = eregi_replace(" ", "_", $thispage);

	# v4.9.2 r8 -- allows for Home_Page to link to domain root so search engines to penalize for duplicate content
	if ( $pagelink == startpage() && $webmasterpref->get("replace_homelinks") == "yes" ) {
	   $pagelink_href = isHttps().$_SESSION['this_ip'];
	} else {

       //if($pagelink=="Home_Page")

       // $pagelink_href = '/';

       //else

	   $pagelink_href = 'index.php?pr='.$pagelink;
	}


   // Define on and off MAIN MENU classes
    /*** return the full address ***/

	$urlnew = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

    $urlnewpath = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$urlnewpath.='://'.$_SESSION['this_ip'].'/';
	if($_SERVER['REQUEST_URI']==''){

        	$urlnew.='://'.$_SERVER['HTTP_HOST'].'/';

	} else {

           $urlnew.='://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}
$presult = mysql_query("SELECT page_name, sub_page_of, link, type FROM site_pages WHERE page_name LIKE '$pageRequest'");

$parentrow = mysql_fetch_array ($presult);

$ppage= split("~~~", $parentrow[sub_page_of]);

$parentpage=eregi_replace(" ", "_", $ppage[0]);
   if ($pagelink == $pageRequest || $pagelink == $dup) {
      $mainClass = "vmenu_main_on";
  	  $hmainsClass="hmainsselected";
   } else {
      $mainClass = "vmenu_main_off";
	   $hmainsClass="hmains";
   }
/********** Home Page Class ******/
if(strcmp($urlnew,$subpage_link[$a])!=0 &&  $pageRequest=="Home_Page"){
                            $hmainsClass = "hmains";
                            }
// When Home Page Selected ///////

if($pageRequest==$pagelink  && $urlnewpath==$urlnew){
               $hmainsClass="hmainsselected";
}

// Selected Parent Page/////// 

if($pagelink ==$parentpage){
	$hmainsClass="hmainsselected";
}

/********** Define External Link Class ******/
 if (strcmp($urlnew,$subpage_link[$a])==0) {

                      $mainClass = "hmainsselected";

   } else {
                        $mainClass = "hmains";

  }

	// Create MAIN MENU option
	// ----------------------------------------------------------------------------------------
	if ($MENUTYPE == "buttons") {
		if($subpage_link[$a] == ""){
			$main_buttons .= "<INPUT TYPE=BUTTON VALUE=\"$thispage\" STYLE=\"font-family: Verdana; font-size: 8pt; width: 120px; height: 20px; background: #$menubg; color: #$linkc; cursor: pointer;\" onclick=\"navto('$pagelink');\">\n$vertOption\n";
		}else{
			if( eregi("http://", $subpage_link[$a]) || eregi("https://", $subpage_link[$a]) || eregi("mailto", $subpage_link[$a]) ){
				$main_buttons .= "<INPUT TYPE=BUTTON VALUE=\"$thispage\" STYLE=\"font-family: Verdana; font-size: 8pt; width: 120px; height: 20px; background: #$menubg; color: #$linkc; cursor: pointer;\" onclick=\"navtoLink('$subpage_link[$a]');\">\n$vertOption\n";
			}else{
				$main_buttons .= "<INPUT TYPE=BUTTON VALUE=\"$thispage\" STYLE=\"font-family: Verdana; font-size: 8pt; width: 120px; height: 20px; background: #$menubg; color: #$linkc; cursor: pointer;\" onclick=\"navtoLink('http://$subpage_link[$a]');\">\n$vertOption\n";
			}
		}
	} else {
		$main_buttons .= " <tr>\n";
		$main_buttons .= "  <td valign=\"middle\" class=\"".$mainClass."\">\n";
		$main_buttons .= "   <div class=\"".$mainClass."\">\n";
		if($subpage_link[$a] == ""){
			$main_buttons .= "    <a href=\"".$pagelink_href."\" class=\"".$mainClass."\">".$thispage."</a>\n";
		}else{
			if( eregi("http://", $subpage_link[$a]) || eregi("https://", $subpage_link[$a]) || eregi("mailto", $subpage_link[$a]) ){
				$main_buttons .= "    <a href=\"".$subpage_link[$a]."\" class=\"".$mainClass."\">".$thispage."</a>\n";
			}else{
				$main_buttons .= "    <a href=\"http://".$subpage_link[$a]."\" class=\"".$mainClass."\">".$thispage."</a>\n";
			}
		}
		$main_buttons .= "   </div>\n";
		$main_buttons .= "  </td>\n";
		$main_buttons .= " </tr>\n";
	}

  if($subpage_link[$a] == ""){
	$thispage1=$thispage;
	$thispage=ereg_replace("Home Page","Home",$thispage1);

  	$hmainz .= "  <li class=\"".$hmainsClass."\"><a href=\"".$pagelink_href."\"  class=\"".$hmainsClass."\">".$thispage."</a>".get_subpages($pageRequest,$thispage)."</li>\n";
  	$main_textmenu .= "<a href=\"".$pagelink_href."\">$thispage</a> | ";
  }else{
	if( eregi("http://", $subpage_link[$a]) || eregi("https://", $subpage_link[$a]) || eregi("mailto", $subpage_link[$a]) ){
	  	$hmainz .= "  <li class=\"".$mainClass."\"><a href=\"".$subpage_link[$a]."\" class=\"".$mainClass."\">".$thispage."</a></li>\n";
	  	$main_textmenu .= "<a href=\"".$subpage_link[$a]."\">$thispage</a> | ";
	}else{
	  	$hmainz .= "  <li class=\"".$mainClass."\"><a href=\"http://".$subpage_link[$a]."\"  class=\"".$mainClass."\">".$thispage."</a></li>\n";
	  	$main_textmenu .= "<a href=\"http://".$subpage_link[$a]."\">$thispage</a> | ";
	}
//  	$hmainz .= "  <td class=\"hmains\"><a href=\"".$subpage_link[$a]."\" class=\"hmains\">".$thispage."</a></td>\n";
//  	$main_textmenu .= "<a href=\"".$subpage_link[$a]."\">$thispage</a> | ";
  }

	// ----------------------------------------------------------------------------------------

	$pageRequest = eregi_replace(" ", "_", $pageRequest);

   // Place main links in separate variable for #VMAINS#
   $vmainz = $main_buttons;

	// Build combination main and sub link table if necessary
	if ($pagelink == $pageRequest || $pagelink == $dup) {

		if ($numsubpages != 0 ) {

			if ($MENUTYPE == "buttons") {
				$main_buttons .= $sub_buttons;
			} else {
				$main_buttons .= " <tr>\n";
				$main_buttons .= "  <td valign=\"top\" class=\"vmenu_main_sub\">\n";
				$main_buttons .= "   ".$sub_buttons."\n";
				$main_buttons .= "  </td>\n";
				$main_buttons .= " </tr>\n";
			}
			$main_textmenu .= $sub_textmenu;

		} else {
			$main_textmenu .= $sub_textmenu;
		}
	}
}

if ($MENUTYPE == "buttons") {
	$main_buttons .= "</form>";
} else {
	$main_buttons .= "</table>\n\n";
}

$main_buttons .= "<!---END: main_buttons-->\n\n";

# Close row & table for #hmains#
$hmainz .= " </ul></div>\n";

# Close table for #vmains#
$vmainz .= "</table>\n";
?>