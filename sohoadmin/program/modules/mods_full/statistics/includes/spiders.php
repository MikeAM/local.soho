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

# Include core interface files
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../../includes/product_gui.php');
chdir($curdir);
$spiderident = 'ABCdatos;Accoona-AI-Agent;aconon Index;Ahoy!;ia_archiver;AlkalineBOT;http://www.almaden.ibm.com/cs/crawler;EMC Spider;Anthill;Aport;Arachnophilia;Araneo;ArchitextSpider;arks/1.0;ASpider;ATN_Worldwide;Atomz;AURESYS;BackRub;Baiduspider;bbot;BecomeBot;Big Brother;BigmirSpider;Biz360 Spider;Bjaaland;BlackWidow;BoardPulse;BoardReader;BoardViewer;boitho.com-robot;borg-bot;BSpider;CACTVS Chemistry Spider;Calif;Checkbot;ChristCrawler.com;www.cienciaficcion.net;CMC/0.01;ColdFusion;combine;Crawler (cometsearch@cometsystems.com);ComputingSite Robi/1.0;conceptbot;Cooby.de Crawler;CoolBot;Cusco;CyberSpyder;DesertRealm.com;Deweb;Die Blinde Kuh;dienstspider;Digger/1.0 JDK/1.3.0;Digimarc WebReader;Digimarc CGIReader;DIIbot;grabber;DNAbot/1.0;DragonBot/1.0 libwww/5.0;DWCP/2.0;e-SocietyRobot;exactseek-pagereaper;EbiNess/0.01a;EIT-Link-Verifier-Robot/0.2;elfinbot;Emacs-w3/v[0-9\.]+;esther;EuripBot/;Evliya Celebi;ExactSeek_Spider;NG/2.0;ExaBot;fast-webcrawler;FastCrawler;FeedBlitz;FeedValidator/;FEHLSTART Superspider;FelixIDE;ESIRover;fido;Fish-Search-Robot;Mozilla/4.0 (compatible: FDSE robot);fouineur.9bit.qc.ca;Freecrawl;FunnelWeb;GaisBot;gamekitbot;gammaSpider;gazz;gcreep;genieBot;GetterroboPlus;GetURL.rexx;Gigabot;Girafabot;Golem;Googlebot/;Mediapartners-Google;Gpostbot;griffon;Gromit;http://grub.org;Gulper Web Bot;H?m?h?kki;havIndex;HeinrichderMiragoRobot;HenryTheMiragoRobot;heritrix;HKU WWW Robot;Hometown;htdig;AITCSRobot;HTMLgobble;I Robot;iajaBot;IBM_Planetwide;IlTrovatore-Setaccio;image.kapsi.net;Mozilla 3.01 PBWF (Win95);IncyWincy;Informant;InfoSeek Robot;Infoseek Sidewinder;InfoSpiders;INGRID;slurp@inktomi;Insitor;inspectorwww;IAGENT;Intelliseek;Internet Cruiser Robot;sharp-info-agent;InternetLinkAgent;Iron33;IsraeliSearch;itchBot;JavaBee;JBot;JCrawler;JetBot;JoBo;Jobot;JoeBot;jumpstation;Katipo;KDD-Explorer;KIT-Fireball;KO_Yappo_Robot;LabelGrab;larbin;legs;Linkidator;LinkScan Server;LinkWalker;livedoorCheckers/;Lockon;logo.gif crawler;Lycos;Magpie;MJ12bot;Mammoth;Marvin;marvin/infoseek;M/3.8;MediaFox;mercator;MerzScope;METASpider;MetaGer-LinkChecker;MindCrawler;UdmSearch;moget;MOMspider;Monster;Moreoverbot;Motor;msnbot;MuscatFerret;MwdSearch;NPBot;NaverBot;NEC-MeshExplorer;Nederland.zoek;NetCarta CyberPilot Pro;NetMechanic;NetScoop;newscan-online;NextGenSearchBot 1;NHSEWalker;Nomad;Norbert the Spider;Gulliver;explorersearch;Occam;Ocelli;Online24-Bot;Openbot;Openfind;Orbsearch;PackRat;PageBoy;ParaSite;Patric;PEGASUS;PerlCrawler/1.0 Xavatoria/2.0;PGP-KA;Duppies;phpdig;PiltdownMan;Pimptrain\'s robot;Pioneer;PluckFeedCrawler;PlumtreeWebAccessor;PodNova;Pompos;Poppi;gestaltIconoclast;PortalJuice.com;PortalBSpider;www.kolinka.com;psbot;Qango.com Web Directory;StackRambler;Raven;Resume Robot;Road Runner: ImageScape Robot;RHCS;Robbie;RoboCrawl;Robofox;Robot du CRIM 1.0a;Robozilla;Roverbot;RuLeS;SafetyNet Robot;Scharia;Science-Index;Scooter;SearchNZ;searchprocess;Seekbot;Senrigan;Sensis Web Crawler;SG-Scout;Shagseeker;Shai\'Hulud;SimBot/1.0;ssearcher100;Site Valet;SiteTech-Rover;+SitiDi.net/SitiDiBot/;aWapClient;SLCrawler;Sleek Spider;ESISmartSpider;Snooper;sohu-search;Solbot;www.entireweb.com/speedy.html;Sphere Scout;Sphider2;SpiderBot;spiderline;SpiderMan;SpiderView;mouse.house;suke;suntek;Szukacz;T-H-U-N-D-E-R-S-T-O-N-E;Black Widow;Tarantula;tarspider;dlw3robot;TechBOT;Templeton;teoma;JubiiRobot;NorthStar;w3index;Peregrinator-Mathematics;TITAN;TitIn;TLSpider;slysearch;TurnitinBot/;TurtleScanner;UCSD-Crawler;urlck;URL Spider Pro;Valkyrie;Verticrawl;Victoria;vision-search;Voyager;VWbot_K;W3M2;w3mir;w@pSpider;appie;CrawlPaper;root;WebMoose;WebBandit;WebCatcher;Webclipping;WebCopy;WebFetcher;weblayers;WebLinker;wlm;WebQuest;WebReaper;webs@recruit.co.jp;websearchbench;WOLP;webvac;webwalk;WebWalker;WebWatch;Wget;whatUseek_winona;Hazel\'s Ferret Web hopper;WinHTTP;wired-digital-newsbot;zyborg;OmniExplorer_Bot;WWWC;WWWeasel Robot;wwwster;WWWWanderer;TECOMAC-Crawler;XGET;cosmos;Yahoo! Slurp;Yahoo-VerticalCrawler;YahooFeedSeeker;Yandex;zeus;ClickTale bot;Google Mediapartners';
$spiderdesc = 'ABCdatos BotLink;Accoona;aconon Index  (raubfische.de);Ahoy!;Alexa;Alkaline;Almaden Crawler;ananzi;Anthill;Aport;Arachnophilia;Araneo;ArchitextSpider;arks;ASpider;ATN Worldwide;Atomz.com;AURESYS;BackRub;Baiduspider;BBot;BecomeBot;Big Brother;Bigmir;Biz;Bjaaland;BlackWidow;BoardPulse;BoardReader;BoardViewer;Boitho;Borg-Bot;BSpider;CACTVS Chemistry;Calif;Checkbot;ChristCrawler.com;cIeNcIaFiCcIoN.nEt;CMC/0.01;ColdFusion;Combine System;cometsystems.com;ComputingSite Robi/1.0;Conceptbot;Cooby.de Crawler;CoolBot;Cusco;CyberSpyder;Desert Realm;DeWeb(c);Die Blinde Kuh;DienstSpider;Digger;Digimarc MarcSpider;Digimarc Marcspider/CGI;Digital Integrity Robot;Direct Hit Grabber;DNAbot;DragonBot;DWCP (Dridus\' Web Cataloging Project);e-Society;eaxactseek-page;EbiNess;EIT Link Verifier Robot;ELFINBOT;Emacs-w3 Search Engine;Esther;EuripBot;Evliya Celebi;ExactSeek_Spider;ExaLead;ExaLead Beta;FAST / AlltheWeb;FastCrawler;FeedBlitz;FeedValidator;FEHLSTART;Felix IDE;FetchRover;fido;Fish search;Fluid Dynamics;Fouineur;Freecrawl;FunnelWeb;Gais;GAMEKIT;gammaSpider;gazz;GCreep;genieBot;GetterroboPlus Puu;GetURL;Gigabot;Girafabot;Golem;Google;Google AdSense;Gpostbot;Griffon;Gromit;Grub Client;Gulper Bot;H?m?h?kki;havIndex;HeinrichderMiragoRobot;HenryTheMiragoRobot;Heritrix;HKU WWW Octopus;Hometown;ht://Dig;HTML Index;HTMLgobble;I, Robot;iajaBot;IBM_Planetwide;IlTrovatore-Setaccio;image.kapsi.net;Imagelock;IncyWincy;Informant;InfoSeek Robot 1.0;Infoseek Sidewinder;InfoSpiders;Ingrid;Inktomi;Insitor;Inspector Web;IntelliAgent;Intelliseek;Internet Cruiser;Internet Shinchakubin;InternetLinkAgent;Iron33;Israeli-search;itch;JavaBee;JBot;JCrawler;JetEye;JoBo;Jobot;JoeBot;JumpStation;Katipo;KDD-Explorer;KIT-Fireball;KO_Yappo_Robot;LabelGrabber;larbin;legs;Link Validator;LinkScan;LinkWalker;livedoorCheckers;Lockon;logo.gif;Lycos;Magpie;Majestics MJ12bot;Mammoth;Marvin;marvin/infoseek;Mattie;MediaFox;Mercator;MerzScope;META;MetaGer;MindCrawler;mnoGoSearch;moget;MOMspider;Monster;Moreover;Motor;Bing;Muscat Ferret;Mwd.Search;NameProtect;NaverBot;NEC-MeshExplorer;Nederland.zoek;NetCarta WebMap;NetMechanic;NetScoop;newscan-online;NextGenSearchBot;NHSE Web Forager;Nomad;Norbert;Northern Light;nzexplorer;Occam;Ocelli;Online24-Bot;Openbot;Openfind data gatherer;Orb Search;Pack Rat;PageBoy;ParaSite;Patric;pegasus;PerlCrawler 1.0;PGP Key Agent;Phantom;PhpDig;PiltdownMan;Pimptrain.com\'s;Pioneer;Pluck;PlumtreeWebAccessor;PodNova;Pompos;Poppi;Popular Iconoclast;Portal Juice;PortalB Spider;Project Kolinka Forum Search;psbot;Qango;Rambler;Raven Search;Resume Robot;Road Runner: The ImageScape Robot;RoadHouse Crawling System;Robbie the Robot;RoboCrawl;RoboFox;Robot Francoroute;Robozilla;Roverbot;RuLeS;SafetyNet;Scharia;Science-Index;Scooter;SearchNZ;SearchProcess;Seekbot;Senrigan;Sensis Web Crawler;SG-Scout;ShagSeeker;Shai\'Hulud;Simmany Robot Ver1.0;Site Searcher;Site Valet;SiteTech-Rover;SitiDi.net/SitiDiBot;Skymob.com;SLCrawler;Sleek;Smart Spider;Snooper;sohu-search;Solbot;Speedy Spider;Sphere;Sphider;SpiderBot;Spiderline Crawler;SpiderMan;SpiderView(tm);spider_monkey;Suke;suntek search engine;Szukacz;T-H-U-N-D-E-R-S-T-O-N-E;TACH Black Widow;Tarantula;tarspider;Tcl W3 Robot;TechBOT;Templeton;Teoma/Ask Jeeves;The Jubii;The NorthStar Robot;The NWI Robot;The Peregrinator;TITAN;TitIn;TLSpider;Turnitin.com;TurnitinBot;Turtle;UCSD Crawl;URL Check;URL Spider Pro;Valkyrie;Verticrawl;Victoria;vision-search;Voyager;VWbot;W3M2;w3mir;w@pSpider;Walhello appie;WallPaper;Web Core / Roots;Web Moose;WebBandit;WebCatcher;Webclipping;WebCopy;webfetcher;weblayers;WebLinker;Weblog Monitor;WebQuest;WebReaper;webs;WebSearchBench;WebStolperer;WebVac;webwalk;WebWalker;WebWatch;Wget;whatUseek Winona;Wild Ferret Web Hopper;WinHTTP;Wired Digital;WiseNut;WorldIndexer;WWWC;WWWeasel Robot;wwwster;WWWWanderer;X-Crawler;XGET;XYLEME Robot;Yahoo! Slurp;Yahoo-VerticalCrawler;YahooFeedSeeker;Yandex;Zeus Internet Marketing;Click Tale;Mediapartners-Google/2.1';
$spiderid_arr = explode(';', $spiderident);
$spiderdesc_arr = explode(';', $spiderdesc);
foreach($spiderdesc_arr as $spidvar=>$spid){
	$spideridentify = $spiderid_arr[$spidvar];
	$spider[$spid] = $spideridentify;
}
//ksort($spider);
//echo testArray($spider);
//echo $_SERVER['HTTP_USER_AGENT'];
//echo $key = array_search('Accoona-AI-Agent', $spider);
//echo $key2 = array_search('ACCOONA-AI-Agent', $spider);
#######################################################
### SET MONTHS ARRAY
#######################################################

$MONTHS[1] = "January";
$MONTHS[2] = "February";
$MONTHS[3] = "March";
$MONTHS[4] = "April";
$MONTHS[5] = "May";
$MONTHS[6] = "June";
$MONTHS[7] = "July";
$MONTHS[8] = "August";
$MONTHS[9] = "September";
$MONTHS[10] = "October";
$MONTHS[11] = "November";
$MONTHS[12] = "December";

?>

<HTML>
<HEAD>
<TITLE>Web Crawler Statistics</TITLE>
<?php echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset=UT"."F-8\">\n"; ?>
<LINK REL="stylesheet" HREF="../shared/soholaunch.css" TYPE="TEXT/CSS">
<script language="JavaScript">
<!--

window.focus();

function MM_callJS(jsStr) { //v2.0
  return eval(jsStr)
}
//-->
</script>
</HEAD>

<BODY BGCOLOR="#FFFFFF" TEXT="#000000" LINK="#FF0000" VLINK="#FF0000" ALINK="#FF0000" LEFTMARGIN="10" TOPMARGIN="10" MARGINWIDTH="10" MARGINHEIGHT="10">

	<?php

	// First; find out what the first month logged in the system is and let's loop in DESC order
	// Through each month and display all stats to date
	// ------------------------------------------------------------------------------------------

	echo "<H5><FONT FACE=VERDANA><U>".$lang["BROWSER AND OPERATING SYSTEMS USED"]."</U></FONT></H5>\n";
	$used=array();
	$result = mysql_query("SELECT Month, Year, Real_Date FROM stats_browser GROUP BY Real_Date UNION SELECT Month, Year, Real_Date FROM stats_browser_archive GROUP BY Real_Date ORDER BY Real_Date DESC");	
	while($ALL_MONTHS = mysql_fetch_array($result)) {
		if(!in_array($ALL_MONTHS['Month'].$ALL_MONTHS['Year'], $used)){
			$used[]=$ALL_MONTHS['Month'].$ALL_MONTHS['Year'];
			####################################################################
			#### START CALCULATION FOR BROWSERS USED TO ACCESS SITE 	     ###
			####################################################################

			$a=1;
			$a_cnt=0;

			$db_result = mysql_query("SELECT * FROM stats_browser WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' UNION SELECT * FROM stats_browser_archive WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' ORDER BY Hits DESC");

			while ($row = mysql_fetch_array($db_result)) {

				

				$mflag = 0;
				$nflag = 0;

				$tmp_data = $row[Browser];
				foreach($spider as $spidname=>$spidid){
					if (eregi($spidid, $tmp_data)) {
						$a_cnt = $a_cnt + $row[Hits];
						$eflag=0;
	
						for ($y=1;$y<=$a;$y++) {
							if ($Browser[$y] == $spidname) {
								$Usage[$y] = $Usage[$y] + $row[Hits];
								$eflag=1;
							}
						}
	
						if ($eflag != 1) {
							$Browser[$a] = $spidname;
							$Usage[$a] = $row[Hits];
							$a++;
						}
							$nflag = 1;
					}
				}
//				if (eregi($spidid, $tmp_data)) {
//
//					$tmp_array = split(";", $tmp_data);
//					$tmp_array[1] = eregi_replace(")", "", $tmp_array[1]);
//					$tmp_array[1] = eregi_replace("b", "", $tmp_array[1]);
//					$tmp_array[1] = eregi_replace("MSIE\.", "MSIE ", $tmp_array[1]);
//
//					$eflag=0;
//
//					for ($y=1;$y<=$a;$y++) {
//						if ($Browser[$y] == $tmp_array[1]) {
//							$Usage[$y] = $Usage[$y] + $row[Hits];
//							$eflag=1;
//						}
//					}
//
//					if ($eflag != 1) {
//						$Browser[$a] = $tmp_array[1];
//						$Usage[$a] = $row[Hits];
//						$a++;
//					}
//					$mflag = 1;
//				}

//				if (eregi("Netscape", $tmp_data)) {
//
//					$eflag=0;
//
//					for ($y=1;$y<=$a;$y++) {
//						if ($Browser[$y] == "Netscape Navigator") {
//							$Usage[$y] = $Usage[$y] + $row[Hits];
//							$eflag=1;
//						}
//					}
//
//					if ($eflag != 1) {
//						$Browser[$a] = "Netscape Navigator";
//						$Usage[$a] = $row[Hits];
//						$a++;
//					}
//						$nflag = 1;
//				}
//
//				if ($nflag == 0 && $mflag == 0) {
//
//					$eflag=0;
//
//					for ($y=1;$y<=$a;$y++) {
//						if ($Browser[$y] == "Other (Linux/Sun/Lynx)") {
//							$Usage[$y] = $Usage[$y] + $row[Hits];
//							$eflag=1;
//						}
//					}
//
//					if ($eflag != 1) {
//						$Browser[$a] = "Other (Linux/Sun/Lynx)";
//						$Usage[$a] = $row[Hits];
//						$a++;
//					}
//				}
			}

		$total_browsers = $a - 1;

		if($total_browsers > 0){
			echo "<DIV CLASS=text><B><U>$ALL_MONTHS[Month] $ALL_MONTHS[Year]</U></B></DIV>\n";
	
	        echo "<TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4 STYLE='border: 1px solid black; background: #708090;' ALIGN=LEFT>
	          	<TR>
	            <TD ALIGN=\"CENTER\" VALIGN=\"TOP\" BGCOLOR=\"#EFEFEF\" WIDTH=150 class=text><B>Web Crawler</B></TD>
	            <TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"500\" BGCOLOR=\"#EFEFEF\" class=text><B>".$lang["Usage Data"]."</B></TD>
	          	</TR>\n";
	
	
	
			  $color = "white";
			  $perc_total = 0;
			  for ($x=1;$x<=$total_browsers;$x++) {
	
				
					$perc = $Usage[$x]/$a_cnt;
					$perc = $perc*100;
					$perc = number_format($perc,2);
		
					$perc_total = $perc_total + $perc;
		
				//	$Browser[$x] = eregi_replace("MSIE", "Internet Explorer", $Browser[$x]);
		
					$lwidth = ceil($perc);
					$line_chart = "<table border=0 cellpadding=0 cellspacing=0 class=allBorder width=".$lwidth."% height=10 align=LEFT><tr><td class=htext bgcolor=darkgreen align=right><B><FONT COLOR=white>".$perc."% (".$Usage[$x].")</font></B></td></tr></table>";
		
				  	echo ("<TR BGCOLOR=\"$color\">\n");
			            echo ("<TD ALIGN=\"RIGHT\" VALIGN=\"TOP\" class=text WIDTH=\"150\">$Browser[$x]</TD>\n");
			            echo ("<TD ALIGN=\"LEFT\" VALIGN=\"TOP\" classs=text WIDTH=\"500\">$line_chart</TD>\n");
					echo ("</TR>\n");
	
	
			  }
	
			  echo "</TABLE><BR CLEAR=ALL>\n";
			}
			####################################################################
			#### START CALCULATION FOR OPERATING SYSTEMS USED TO ACCESS SITE ###
			####################################################################

//			$a=1;
//			$a_cnt = 0;
//
//			$db_result = mysql_query("SELECT * FROM stats_browser WHERE Month = '$ALL_MONTHS[Month]' AND Year = '$ALL_MONTHS[Year]' ORDER BY Hits DESC");
//
//			while ($row = mysql_fetch_array($db_result)) {
//
//				$a_cnt = $a_cnt + $row[Hits];
//
//				$winflag=0;
//				$macflag=0;
//
//				$tmp_data = $row[Browser];
//
//				// *************************************
//				// ** Look for MAC OS
//				// *************************************
//
//				if (eregi("Mac", $tmp_data)) {
//
//					$this_OS = "Apple Macintosh";
//
//					$eflag=0;
//
//					for ($y=1;$y<=$a;$y++) {
//						if ($OSSYS[$y] == $this_OS) {
//							$HITS[$y] = $HITS[$y] + $row[Hits];
//							$eflag = 1;
//						}
//					}
//
//					if ($eflag != 1) {
//						$OSSYS[$a] = $this_OS;
//						$HITS[$a] = $row[Hits];
//						$a++;
//					}
//
//					$macflag = 1;
//
//				}
//
//				// *************************************
//				// ** Finnally - Look for Windows OS
//				// *************************************
//
//				if (eregi("Win", $tmp_data)) {
//
//					// *** DETERMINE IF WINDOWS NT OR NOT **
//
//					if (eregi("NT", $tmp_data)) {
//
//						$s = split("NT", $tmp_data);
//						$right = eregi_replace(" ", "", $s[1]);
//						$right = substr($right, 0, 3);
//
//						if (substr($right, 0, 1) == "5") {
//							$this_OS = "Windows XP";
//						} else {
//							$this_OS = "Windows NT " . $right;
//
//							// *** Is there a version number here? ***
//							// ***************************************
//
//							$l = strlen($this_OS);
//							$zz = 0;
//							$vflag = 0;
//							while($zz != $l) {
//								$tmp = substr($this_OS, $zz, 1);
//								if (eregi("[0-9]", $tmp)) { $vflag++; }
//								$zz++;
//							}
//
//							if ($vflag == 0) {
//								$this_OS = "Windows NT";
//							}
//
//						}
//
//					} else {
//
//						$s = split("Windows", $tmp_data);
//						$right = eregi_replace(" ", "", $s[1]);
//						$right = substr($right, 0, 2);
//
//						$this_OS = "Windows " . $right;
//
//						// *** Is there a version number here? ***
//						// ***************************************
//
//						$l = strlen($this_OS);
//						$zz = 0;
//						$vflag = 0;
//						while($zz != $l) {
//							$tmp = substr($this_OS, $zz, 1);
//							if (eregi("[0-9]", $tmp)) { $vflag++; }
//							$zz++;
//						}
//
//						// *** If not, it's probably a WIN notation
//						// *****************************************
//						if ($vflag == 0) {
//							$s = split("Win", $tmp_data);
//							$right = eregi_replace(" ", "", $s[1]);
//							$right = substr($right, 0, 2);
//							$this_OS = "Windows " . $right;
//						}
//
//						// *** NOW, Is there a version number here? ***
//						// ********************************************
//
//						$l = strlen($this_OS);
//						$zz = 0;
//						$vflag = 0;
//						while($zz != $l) {
//							$tmp = substr($this_OS, $zz, 1);
//							if (eregi("[0-9]", $tmp)) { $vflag++; }
//							$zz++;
//						}
//
//						// ** If not, assume Win 95 - Come on Bill!
//						// *********************************************
//
//							if ($vflag == 0) {
//								$this_OS = "Windows 95";
//							}
//
//					}
//
//					// *** PLACE IN SYSTEM AS A SPECIFIC OS ***
//
//					$eflag=0;
//
//					for ($y=1;$y<=$a;$y++) {
//						if ($OSSYS[$y] == $this_OS) {
//							$HITS[$y] = $HITS[$y] + $row[Hits];
//							$eflag = 1;
//						}
//					}
//
//					if ($eflag != 1) {
//						$OSSYS[$a] = $this_OS;
//						$HITS[$a] = $row[Hits];
//						$a++;
//					}
//
//					$winflag = 1;
//
//				} // End Win OS Look up
//
//
//				// ***************************************
//				// ** Default everything else to OTHER
//				// ***************************************
//
//				if ($macflag == 0 && $winflag == 0) {
//					$this_OS = "Other (Linux/Sun/BeOS)";
//
//					$eflag=0;
//
//					for ($y=1;$y<=$a;$y++) {
//						if ($OSSYS[$y] == $this_OS) {
//							$HITS[$y] = $HITS[$y] + $row[Hits];
//							$eflag = 1;
//						}
//					}
//
//					if ($eflag != 1) {
//						$OSSYS[$a] = $this_OS;
//						$HITS[$a] = $row[Hits];
//						$a++;
//					}
//				}
//
//			}
//
//		$total_os = $a - 1;
//
//	 	 echo "<BR><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4 STYLE='border: 1px solid black; background: #708090;' ALIGN=LEFT>
//				<TR>
//				<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" BGCOLOR=\"#EFEFEF\" WIDTH=150 class=text><B>Operating System</B></TD>
//				<TD ALIGN=\"CENTER\" VALIGN=\"TOP\" WIDTH=\"500\" BGCOLOR=\"#EFEFEF\" class=text><B>Usage Data</B></TD>
//				</TR>\n";
//
//		  $color = "WHITE";
//		  $perc_total = 0;
//
//		  for ($x=1;$x<=$total_os;$x++) {
//
//
//			$perc = $HITS[$x]/$a_cnt;
//			$perc = $perc*100;
//			$perc = number_format($perc,2);
//
//			$perc_total = $perc_total + $perc;
//
//			$lwidth = ceil($perc);
//			$line_chart = "<table border=0 cellpadding=0 cellspacing=0 class=allBorder width=".$lwidth."% height=10 align=LEFT><tr><td class=htext bgcolor=darkgreen align=right><B><FONT COLOR=white>$perc%</font></B></td></tr></table>";
//
//		  	echo ("<TR BGCOLOR=\"white\">\n");
//	            echo ("<TD ALIGN=\"right\" VALIGN=\"TOP\" WIDTH=\"150\" class=text>$OSSYS[$x]</TD>\n");
//	            echo ("<TD ALIGN=\"left\" VALIGN=\"TOP\" class=text>$line_chart</TD>\n");
//			echo ("</TR>\n");
//
//
//		  }
//
//		  echo "</TABLE><BR CLEAR=ALL><BR><BR>\n\n";

	
		}
	} // End Monthly While Loop

?>

</BODY>
</HTML>
