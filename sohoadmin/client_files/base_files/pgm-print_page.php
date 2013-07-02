<?php
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

session_start();
error_reporting(0);
track_vars;

##########################################################################
### WE WILL NEED TO KNOW THE DATABASE NAME; UN; PW; ETC TO OPERATE THE ###
### REAL-TIME EXECUTION.  THIS IS CONFIGURED IN THE isp.conf FILE      ###
##########################################################################

include_once("sohoadmin/client_files/pgm-site_config.php");
include_once("sohoadmin/program/includes/shared_functions.php");
$globalprefObj = new userdata('global');
$currentPage = preg_replace("/['\"\/+]/i", '', str_replace(' ', '_', $_GET['currentPage']));
$query = "SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages where page_name='".$currentPage."'"; // EDIT HERE and specify your table and field names for the SQL query
$numresults=mysql_query($query);

while ($row = mysql_fetch_array($numresults)) {
	$title = $row["page_name"];
	$this_page = eregi_replace(' ', '_', $title);
	$include_in_search = $_POST["$this_page"];
	$securegroup = '';
	if ($row["username"] != '' ) {
		$securegroup = $row["username"];
		mysql_query("update site_pages set username='' where page_name='".$title."'");
	}		
		$templatef = $row["template"];
		if($templatef != ''){
			$templatef = $row["template"];
		} else {
//			$baseT = "sohoadmin/tmp_content/template.conf";
//	   		$file = fopen("$baseT", "r");
//	   		$what_template = fread($file,filesize($baseT));
//	   		fclose($file);
			$what_template = $globalprefObj->get('site_base_template');
	   		$templatef = $what_template;	// In case of individual page definitions
		}
		$css_file = "sohoadmin/program/modules/site_templates/pages/".$templatef."/custom.css";
}

//	$url = "http://".$_SESSION['this_ip']."/".pagename($_GET['currentPage'], "&")."nft=../../../../../sohoadmin/includes/blank_template";
	$url = "http://".$_SESSION['this_ip']."/".pagename($_GET['currentPage'], "&")."nft=blank_template";

	$pagecontent = include_r($url);
	$pagecontent = ob_get_contents();
	$pagecontent = eregi_replace("(<input type=button class=FormLt1 value=\"Printable Page\")([^<])*", "", $pagecontent);
	$remove = "<div align=\"center\"><a href=\"pgm-email_friend.php?mailpage=".$this_page."\"><font size=\"1\" face=\"Arial\">[ Email this page to a friend ]</font></a><BR></div>";
	$pagecontent = str_replace($remove, "", $pagecontent);
	$remove = "<div align=\"center\"><form name=\"printpage\"><input type=\"button\" class=\"FormLt1\" value=\"Printable Page\" onclick=\"window.open('pgm-print_page.php?currentPage=".$this_page."','printwin','scrollbars=yes,width=700,height=450');\"></form></div>\n</div>";
	$pagecontent = str_replace($remove, "", $pagecontent);



if ($securegroup != '' ) {
	mysql_query("update site_pages set username='$securegroup' where page_name='$title'");
	$securegroup = '';
}

$template_line = "<HTML>";
$template_line .= "<HEAD>";
$template_line .= "<TITLE>".$url = "http://".$_SESSION['this_ip']."/".pagename($_GET['currentPage'])."</TITLE>\n\n";
//$template_line .= "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n\n";

if(file_exists($css_file)){
	$template_line .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$css_file."\"/></link>\n";
}
$template_line .= "<SCRIPT language=Javascript>\n window.print();\n</SCRIPT>\n\n";
$template_line .= "</HEAD>\n\n";
$template_line .= "<BODY style=\"background-color:white; color:black;\">\n\n";

$template_line .= "<CENTER>\n\n".$pagecontent."\n\n</CENTER>\n\n";

echo $template_line .= "</BODY></HTML>\n";

exit;

$dot_com = $this_ip;
$websiteTitle = strtoupper($SERVER_NAME);
$thisDatabase = $_SESSION['db_name'];
##########################################################################
### SINCE WE ARE DISPLAYING A BLANK WHITE PAGE WITH THE CONTENT ONLY,
### DEFINE THE $headertext AS THE NAME OF THE WEB SITE.  ALSO, LETS
### GO AHEAD AND DEFINE THE STYLESHEET INCLUDE
##########################################################################

$headertext = "$SERVER_NAME";
$subheadertext = "";

$stylesheet_routine = "<LINK rel=\"stylesheet\" href=\"runtime.css\" type=\"text/css\">\n";


##########################################################################
### NOW, LET'S GET THE PAGE CONTENT ONLY FOR DISPLAY
##########################################################################

$filename = "$cgi_bin/$currentPage.con";

if (!file_exists("$filename")) {

	$error = 404;
	$htmllines = "";
	$numlines = 0;

	// ******************************************************************************
	// It is highly unlikely that this page will not exist since the site visitor
	// is looking at it when they decide to print the page, however, you never
	// can tell about the web
	// ******************************************************************************

	$errordisplay = "<table border=1 cellpadding=2 cellspacing=2 width=500><tr><td align=center valign=top bgcolor=orange>\n";
	$errordisplay .= "<font color=DARKBLUE><TT>".$lang["THIS PAGE IS CURRENTLY UNDER CONSTRUCTION"]."</B></TT></font></td></tr></table><BR>&nbsp;<BR>\n";

} else {

	$file = fopen("$filename", "r");
		$body = fread($file,filesize($filename));
	fclose($file);

	$content_line = split("\n", $body);

	$numlines = count($content_line);

}

##########################################################################
### LET'S DEFINE A BLANK WHITE TEMPLATE THAT WILL AUTO-LAUNCH THE PRINT
### WINDOW FOR EASY USE OF THIS FEATURE
##########################################################################

$template_line[0] = "<HTML>";
$template_line[1] = "<HEAD>";
$template_line[2] = "<TITLE>$websiteTitle</TITLE>\n\n";

$template_line[3] = "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n\n";

$template_line[4] = "$stylesheet_routine\n\n";

$template_line[5] = "<SCRIPT language=Javascript>\n window.print();\n</SCRIPT>\n\n";

$template_line[6] = "</HEAD>\n\n";

$template_line[7] = "<BODY BGCOLOR=WHITE LINK=RED ALINK=RED VLINK=RED>\n\n";

$template_line[8] = "<CENTER>\n\n#CONTENT#\n\n</CENTER>\n\n";

$template_line[9] = "</BODY></HTML>\n";

$numtlines = 9;

#######################################################
### START DISPLAY OF PAGE				    ###
#######################################################

for ($xedusvar=0;$xedusvar<=$numtlines;$xedusvar++) {

	if (eregi("#CONTENT#", $template_line[$xedusvar])) {

		// ***************************************************************************************
		// In case of troubleshooting needs, lets place some HTML comment code to indicate where
		// the actual page_content starts that was created by the page editor system
		// ***************************************************************************************

		$pagecontent = "\n\n\n\n<!-- \n\n";
		$pagecontent .= "###########################################################################\n";
		$pagecontent .= "### PGM-REALTIME-BUILDER ==> START PAGE CONTENT FROM CONTENT EDITOR \n";
		$pagecontent .= "###########################################################################\n\n";
		$pagecontent .= "-->\n\n\n\n";


		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
		// If we have determined a 404 error for this page (See above content retreival) then
		// place the error display HTML here
		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

		if ($error == 404 && $module_active != "yes") { $pagecontent .= $errordisplay; }

		##############################################################################################
		## START ACTUAL "INNER LOOP" THROUGH CONTENT LINES WITHIN THE $xedusvar LOOP
		##############################################################################################

		for ($sohocontent=0;$sohocontent<=$numlines;$sohocontent++) {


			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// INSERT CODE FOR AUTOMATIC DATE STAMP
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			if (eregi("<!-- ##PHPDATE## -->", $content_line[$sohocontent])) {
				$today = date("F j, Y");
				$content_line[$sohocontent] = eregi_replace("<!-- ##PHPDATE## -->", "<font face=Verdana, Arial, Helvetica size=2><B>$today</B></font>", $content_line[$sohocontent]);
			}

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// INSERT CODE FOR HIT COUNTER CALCULATION AND DISPLAY
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			if (eregi("##COUNTER##", $content_line[$sohocontent])) {
				$filename = $_SESSION['docroot_path']."/sohoadmin/filebin/hitcounter.txt";
				if (file_exists("$filename")) {
					$file = fopen("$filename", "r");
						$hitcount = fread($file,filesize($filename));
					fclose($file);
					$hitcount = eregi_replace("\n", "", $hitcount);
				} else {
					$hitcount = 1;
				}
				$content_line[$sohocontent] = "<font size=2 face=Verdana, Helvetica, Sans-Serif><B>".$lang["Page Visits"].": $hitcount</b></font>\n";
				$hitcount++;
				$file = fopen("$filename", "w");
					fwrite($file, "$hitcount\n");
				fclose($file);
			}

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// INSERT CODE FOR WEEKLY CALENDAR VIEW AND SEARCH
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			if (eregi("##CALENDAR", $content_line[$sohocontent])) {
				include('http://'.$_SESSION['this_ip'].'/pgm-cal-monthview.php');
				$shopmatch = 0;
				$tablename = "calendar_display";
				$result = mysql_list_tables("$thisDatabase");
				$i = 0;
				while ($i < mysql_num_rows ($result)) {
					$tb_names[$i] = mysql_tablename ($result, $i);
					if ($tb_names[$i] == $tablename) {
						$shopmatch = 1;
					}
					$i++;
				}

				if ($shopmatch == 1) {
					$result = mysql_query("SELECT * FROM $tablename");
					while ($row = mysql_fetch_array ($result)) {
						$cbgcolor = $row["headerbgcolor"];
						$ctextcolor = $row["headertextcolor"];
						$cfrom = $row["displaytype"];
					}
				} else {
					$cbgcolor = "000099";
					$ctextcolor = "FFFFFF";
					$cfrom = "calmonth.php";
				}

				$calendarcode = "<div align=center>";



				$calendarcode .= "<form method=post action=\"calendar.php\">
							<input type=hidden name=customernumber value=\"$customernumber\">
							<input type=hidden name=alterFlag value=frontpage>
							<table width=\"199\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		  					<tr>
						      <td bgcolor=#7D8386>
						      <img src=\"cal_header.gif\" width=199 height=21 border=0>
						      </td>
							</tr>
							<tr>
							<td align=\"center\" valign=\"middle\" bgcolor=#7D8386>
						      <img src=\"spacer.gif\" width=150 height=2 border=0><BR>
							<select name=\"monthselect\">\n";

							$todayis = date("j");
							$monthselect = date("F");
							$sohocontentearselect = date("Y");
							$thisMonthNum = date("n");

							$months[1] = lang("January");
							$months[2] = lang("February");
							$months[3] = lang("March");
							$months[4] = lang("April");
							$months[5] = lang("May");
							$months[6] = lang("June");
							$months[7] = lang("July");
							$months[8] = lang("August");
							$months[9] = lang("September");
							$months[10] = lang("October");
							$months[11] = lang("November");
							$months[12] = lang("December");

							for ($dd=1;$dd<=12;$dd++) {
								$mdisplay = substr("$months[$dd]", 0, 3);

								if ($monthselect == $months[$dd]) {
									$calendarcode .="        <option value=\"$monthselect\" selected>$mdisplay</option>\n";
									$dWnum = $dd;
								} else {
									$calendarcode .="        <option value=\"$months[$dd]\">$mdisplay</option>\n";
								}
							}

							$calendarcode .= "</select>\n<select name=\"yearselect\">\n";

							for ($dd=2001;$dd<=2030;$dd++) {
								if ($sohocontentearselect == $dd) {
									$calendarcode .= "        <option value=\"$sohocontentearselect\" selected>$sohocontentearselect</option>\n";
								} else {
									$calendarcode .= "		 <option value=\"$dd\">$dd</option>\n";
								}
							}

							$calendarcode .= "</select>\n";


							$calendarcode .="&nbsp;<input type=\"image\" src=\"cal_view.gif\" width=44 height=21 align=absmiddle border=0><BR>\n<img src=\"spacer.gif\" width=150 height=2 border=0>
									    </td>
								          </tr>
									    <tr>
									    <td align=\"left\" valign=\"top\"><table border=1 cellpadding=2 cellspacing=0 width=100% bordercolorlight=\"#7D8386\" bordercolordark=\"#7D8386\" bordercolor=\"#7D8386\"><tr><td>
									    <img src=\"spacer.gif\" width=150 height=2 border=0><BR>
									    <font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"> <b>".lang("This Week in")." $monthselect:</b><br>&nbsp;<BR></font>\n";


							$vstart = $todayis;
							$vend = $todayis + 7;

							if ($vend > 31) {
								$vend = 31;
							}

							for ($WK=$vstart;$WK<=$vend;$WK++) {
								$daysearch = sprintf ("%02s", $WK);
								$mdisplay = substr("$monthselect", 0, 3);
								$dayofweek = date ("l", mktime(0,0,0,$dWnum,$WK,$sohocontentearselect));

								$shopmatch = 0;
								$tablename = "calendar_events";
								$result = mysql_list_tables("$thisDatabase");
								$i = 0;
								while ($i < mysql_num_rows ($result)) {
									$tb_names[$i] = mysql_tablename ($result, $i);
									if ($tb_names[$i] == $tablename) {
										$shopmatch = 1;

									}
									$i++;
								}

								if ($shopmatch == 1) {
									$mdisplay = substr("$monthselect", 0, 3);
									$daysearch = sprintf ("%02s", $WK);
									$result = mysql_query("SELECT * FROM $tablename WHERE (month = '$mdisplay' AND day = '$daysearch' AND year = '$sohocontentearselect') OR (display = '$dayofweek') ORDER BY title");
									$numberevents = mysql_num_rows($result);
									$a=0;
									if ($numberevents != 0) {
										while ($row = mysql_fetch_array ($result)) {
											$a++;
											$recordnumber[$a] = $row["recordnumber"];
											$category1[$a] = $row["category1"];
											$display[$a] = $row["display"];
											$itmonth[$a] = $row["month"];
											$ityear[$a] = $row["year"];
											$title[$a] = $row["title"];
											$titlecolor[$a] = $row["titlecolor"];
											$stime[$a] = $row["stime"];
											$stimeampm[$a] = $row["stimeampm"];
											$etime[$a] = $row["etime"];
											$etimeampm[$a] = $row["etimeampm"];
											$shortdescription[$a] = $row["shortdescription"];
											$detailfilename[$a] = $row["detailfilename"];
											$detailpage[$a] = $row["detailpage"];
										}
								}
							} else {
								$numberevents == 0;
							}

							if ($numberevents != 0) {
								$tEventCount = 0;
								for ($ev=1;$ev<=$numberevents;$ev++) {
									$cfrom = eregi_replace("\.php", "", $cfrom);
									if (checkdate ($thisMonthNum, $WK, $sohocontentearselect)) {
										if ($detailpage[$ev] != "" || file_exists("$cgi_bin/$detailfilename[$ev]")) {
											$moreinfo = "<BR><font size=1><I><a href=\"calmonth.php?category=$category&customernumber=$customernumber&view=details&what=$recordnumber[$ev]&from=$cfrom&day=$WK&yearselect=$sohocontentearselect\">[".$lang["More Info"]."]</a></font></i>";
										} else {
											$moreinfo = "";
										}

										if (strlen($display[$ev]) < 3 || $display[$ev] == $dayofweek) {
											if ($ityear[$ev] == $sohocontentearselect && $itmonth[$ev] == $mdisplay) {
												$calendarcode .= "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><b>$dayofweek, $mdisplay $WK</b><BR>$title[$ev]<font size=1>$moreinfo<BR>&nbsp;<BR></font>\n";
												$tEventCount++;
											}
										}

									} // END VALID DATE CHECK
								} // END FOR

								if ($tEventCount == 0) {
									$calendarcode .= "";
								}

							} else {

								

							} // END IF NUM EVENTS

							} // END WK LIST
					$calendarcode .= include('pgm-cal-monthview.php');
					$calendarcode .= "</td></tr></table></td></tr></table></form></div>\n";
					$content_line[$sohocontent] = eregi_replace("<!-- ##CALENDAR-ONEMONTH-VIEW;All## -->", $calendarcode, $content_line[$sohocontent]);
			}

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// INSERT CODE FOR SINGLE SEARCH CALENDAR VIEW
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


			if (eregi("##CALSEARCH", $content_line[$sohocontent])) {

			$shopmatch = 0;
			$tablename = "calendar_display";
			$result = mysql_list_tables("$thisDatabase");
			$i = 0;
			while ($i < mysql_num_rows ($result)) {
				$tb_names[$i] = mysql_tablename ($result, $i);
				if ($tb_names[$i] == $tablename) {
					$shopmatch = 1;
				}
				$i++;
			}

			if ($shopmatch == 1) {
				$result = mysql_query("SELECT * FROM $tablename");
				while ($row = mysql_fetch_array ($result)) {
					$cbgcolor = $row["headerbgcolor"];
					$ctextcolor = $row["headertextcolor"];
					$cfrom = $row["displaytype"];
				}
			} else {
				$cbgcolor = "000099";
				$ctextcolor = "FFFFFF";
				$cfrom = "calmonth.php";
			}

			$calendarcode = "<div align=center>";
			$calendarcode .= "<form method=post action=calendar.php>
					<input type=hidden name=customernumber value=\"$customernumber\">
					<input type=hidden name=alterFlag value=frontpage>
					<table width=\"199\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" bgcolor=#7D8386>
  					<tr>
				      <td>
			            <img src=\"cal_header.gif\" width=199 height=21 border=0>
				      </td>
					</tr>
					<tr bgcolor=#7D8386>
					<td align=\"center\" valign=\"middle\">
				      <img src=\"spacer.gif\" width=150 height=2 border=0><BR>
					<select name=\"monthselect\">\n";

			$todayis = date("j");
			$monthselect = date("F");
			$sohocontentearselect = date("Y");
			$months[1] = lang("January");
			$months[2] = lang("February");
			$months[3] = lang("March");
			$months[4] = lang("April");
			$months[5] = lang("May");
			$months[6] = lang("June");
			$months[7] = lang("July");
			$months[8] = lang("August");
			$months[9] = lang("September");
			$months[10] = lang("October");
			$months[11] = lang("November");
			$months[12] = lang("December");

			for ($dd=1;$dd<=12;$dd++) {
				$mdisplay = substr("$months[$dd]", 0, 3);
				if ($monthselect == $months[$dd]) {
					$calendarcode .="        <option value=\"$monthselect\" selected>$mdisplay</option>\n";
					$dWnum = $dd;
				} else {
					$calendarcode .="        <option value=\"$months[$dd]\">$mdisplay</option>\n";
				}
			}

			$calendarcode .= "</select>\n<select name=\"yearselect\">\n";

			for ($dd=2001;$dd<=2030;$dd++) {
				if ($sohocontentearselect == $dd) {
					$calendarcode .= "        <option value=\"$sohocontentearselect\" selected>$sohocontentearselect</option>\n";
				} else {
					$calendarcode .= "		 <option value=\"$dd\">$dd</option>\n";
				}
			}

			$calendarcode .= "</select>\n";
			$calendarcode .="&nbsp;<input type=\"image\" src=\"cal_view.gif\" width=44 height=21 align=absmiddle border=0>\n";
			$calendarcode .= "<BR><img src=\"spacer.gif\" width=150 height=4 border=0></td></tr></table></form></div>\n";
			$content_line[$sohocontent] = eregi_replace("<!-- ##CALSEARCH## -->", $calendarcode, $content_line[$sohocontent]);

			}

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// Translate all submit buttons into proper style class
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			$content_line[$sohocontent] = eregi_replace("input type=submit", "input type=submit class=FormLt1", $content_line[$sohocontent]);
			$content_line[$sohocontent] = eregi_replace("input type=\"submit\"", "input type=submit class=FormLt1", $content_line[$sohocontent]);

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// INSERT CODE FOR PHP INCLUDE SCRIPT
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			if (eregi("##MIKEINC;", $content_line[$sohocontent])) {

				$temp = eregi("<!-- ##MIKEINC;(.*)## -->", $content_line[$sohocontent], $out);
				$INCLUDE_FILE = $out[1];

				$filename = "media/$INCLUDE_FILE";

				ob_start();
				include("$filename");
				$output = ob_get_contents();
				ob_end_clean();

				$content_line[$sohocontent] = "\n\n<!-- ~~~~~~~ CUSTOM PHP OUTPUT ~~~~~~ -->\n\n" . $output . "\n\n<!-- ~~~~~~~~~~~~ END CUSTOM PHP OUTPUT ~~~~~~~~~~~~ -->\n\n";
			}

			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
			// Add the current loop through the content_line array to the "$pagecontent" var
			// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

			$pagecontent .= $content_line[$sohocontent] . "\n";

		} // END LOOP

		##############################################################################################
		### END OF $sohocontent LOOP
		##############################################################################################

	$pagecontent .= "\n\n\n\n<!-- \n\n";
	$pagecontent .= "##############################################################################\n";
	$pagecontent .= "### PGM-REALTIME-BUILDER ==> END DYNAMIC PAGE CONTENT FROM PAGE EDITOR SYSTEM \n";
	$pagecontent .= "##############################################################################\n\n";
	$pagecontent .= "-->\n\n\n\n";

	$template_line[$xedusvar] = eregi_replace("#CONTENT#", $pagecontent, $template_line[$xedusvar]);

	} // End If Content

	$template_line[$xedusvar] = eregi_replace("pgm-cal-monthview.php\?", "pgm-cal-monthview.phpcustomernumber=$customernumber&", $template_line[$xedusvar]);
	$template_line[$xedusvar] = eregi_replace("shopping.php\?", "shopping.php?customernumber=$customernumber&", $template_line[$xedusvar]);
	$template_line[$xedusvar] = eregi_replace("href=\"shopping.php\"", "href=\"shopping.php?customernumber=$customernumber\"", $template_line[$xedusvar]);
	$template_line[$xedusvar] = eregi_replace("index.php\?", "index.php?customernumber=$customernumber&", $template_line[$xedusvar]);
	$template_line[$xedusvar] = eregi_replace("pgm-email_friend.php\?", "pgm-email_friend.php?customernumber=$customernumber&", $template_line[$xedusvar]);
	$template_line[$xedusvar] = eregi_replace("</form>","<input type=hidden name=customernumber value=\"$customernumber\"></form>\n", $template_line[$xedusvar]);

	echo ("$template_line[$xedusvar]\n");

} // End Template Loop

echo ("<BR>&nbsp;<BR>\n");

exit;

?>