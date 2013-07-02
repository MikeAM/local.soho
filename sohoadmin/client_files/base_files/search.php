<?php

session_start();
error_reporting(E_PARSE);

include("sohoadmin/includes/config.php");
include("sohoadmin/includes/db_connect.php");
include("sohoadmin/program/includes/shared_functions.php");
include("sohoadmin/program/includes/smt_functions.php");
include('sohoadmin/includes/emulate_globals.php');

$sessiontime = $_SESSION['searchtime'];
$_SESSION['searchtime'] = round(microtime(), 2);
$output = '';
$cansearch = '';
$searchopts = mysql_query("select * from search_core where prikey='1'");
$searchopts = mysql_fetch_array($searchopts);

$globalprefObj = new userdata('global');

$pageRequest = 'Super_Search';
$thisPage = 'Super_Search';
if ($searchopts['template'] == "") {
	$nft = '';
	include_once('sohoadmin/client_files/pgm-realtime_builder.php');
} else {
	//$nfts = file_get_contents($_SESSION['doc_root']."/sohoadmin/tmp_content/template.conf");
	$nfts = $globalprefObj->get('site_base_template');
	if(eregi('tCustom', $nfts)) {
		$nft = $searchopts['template']; //$searchopts['template'];
		if(eregi('tCustom', $searchopts['template'])) {
			$nft = eregi_replace($_SESSION['doc_root'].'/tCustom/', '', 'tCustom/'.$searchopts['template']);
			$page_temp = $nft;
			$filename = $nft;
			$nft = '';
			include_once('sohoadmin/client_files/pgm-realtime_builder.php');
		} else {
			include_once('sohoadmin/client_files/pgm-realtime_builder.php');
			$template_header = eregi_replace("images/", "sohoadmin/program/modules/site_templates/pages/".$searchopts['template']."/", $template_header);
			$template_footer = eregi_replace("images/", "sohoadmin/program/modules/site_templates/pages/".$searchopts['template']."/", $template_footer);		
		}						
	} else {
		if(eregi('tCustom', $searchopts['template'])) {
			$nft = eregi_replace($_SESSION['doc_root'].'/tCustom/', '', 'tCustom/'.$searchopts['template']);
			$page_temp = $nft;
			$filename = $nft;
			$nft = '';
			include_once('sohoadmin/client_files/pgm-realtime_builder.php');
		} else {
			$nft = $searchopts['template'];
			include_once('sohoadmin/client_files/pgm-realtime_builder.php');
		}
	}
}
$searchoutput = $template_header;

if ($searchopts['style1'] != "") { $style1 = $searchopts['style1']; } else { $style1 = ""; }

if ($searchopts['style2'] != "") { $style2 =  " color:#".$searchopts['style2'].";"; } else { $style2 = ""; }

if( $searchopts['style3'] != '' ) { $style3 = "font-family: ".$searchopts['style3'].";"; } else { $style3 = ""; }

if( $searchopts['style4'] != '' ) { $style4 = " font-size: ".$searchopts['style4']."px;"; } else { $style4 = ""; }
	
$mstyle = " style=\"".$style3.$style4.$style2."\"";
$qvar = $_GET['q'];
$qvar = str_replace('<', '', $qvar);
//$qvar = stripslashes($qvar);
$qvar = str_replace('>', '', $qvar);
$trimmed = trim($qvar); //trim whitespace from the stored variable

$trimmed = slashthis($trimmed);

if (!isset($_GET['t'])) { $t='single'; } else { $t = $_GET['t']; }

if ($_GET['z']=='yes') { $z = 'yes'; } else { $z = ''; }

if (!isset($_GET['l'])) { $l=$searchopts['results_per_page']; $limit = $l; } else { $l = $_GET['l']; $limit = $l; }

if (!isset($_GET['s'])) { $s=0; } else { $s = $_GET['s']; }

if ($_GET['s'] < 0) { $s=0; $_GET['s']=0; }

if ($_GET['t'] == 'phrase') {
	$trickytrimmed = eregi_replace(' ', '', $trimmed);
  if (strlen($trickytrimmed) >= $searchopts['min_word_chars']) {
    $cansearch = 'true';
  }
	$qvar2 = array($qvar);
	$countarray = 1;
} else {
	$trimmed = eregi_replace('  ', ' ', $trimmed);
	$trimmed = eregi_replace('  ', ' ', $trimmed);
	$trimmed = eregi_replace('  ', ' ', $trimmed);
	$qvarfirst = split(' ',$trimmed);
	$iii = 0;

	foreach ($qvarfirst as $ii=>$uu) {
		$uu = eregi_replace('  ', ' ', $uu);
		$qvar3[$uu] = $iii;
		$iii++;
	}
	$iii = 0;
  foreach ($qvar3 as $iis=>$uus) {
	  $qvar2[$iii] = $iis;
	  $iii++;
		$supstringiis = eregi_replace(' ', '', $iis);
		if (strlen($supstringiis) >= $searchopts['min_word_chars']) {
			$cansearch = 'true';
		}
	}

	$countarray = count($qvar2);
}

if(mysql_get_client_info() >= 4) {
	foreach($qvar2 as $qval=>$qqvar) {
		if ($qval == 0) {
			$searchqry = "select * from search_contents where CAST(page_contents as char) LIKE '%$qqvar%'";
			$querymeta = "select page_name, url_name, password from site_pages where CAST(password as char) LIKE '%$qqvar%'";
			$cartqry = "select * from cart_products where (CAST(PROD_DESC as char) LIKE '%$qqvar%' or CAST(PROD_NAME as char) LIKE '%$qqvar%' or CAST(OPTION_KEYWORDS as char) LIKE '%$qqvar%')";
		} else {
			$searchqry .=  " AND CAST(page_contents as char) LIKE '%$qqvar%'";		
			$querymeta .= " AND CAST(password as char) LIKE '%$qqvar%' OR CAST(password as char) LIKE '%$trimmed%'";
			$cartqry .=  " AND (CAST(PROD_DESC as char) LIKE '%$qqvar%' or CAST(PROD_NAME as char) LIKE '%$qqvar%' or CAST(OPTION_KEYWORDS as char) LIKE '%$qqvar%')";
		}
	}
} else {
	foreach($qvar2 as $qval=>$qqvar) {
		$qqvar = strtolower($qqvar);
		if ($qval == 0) {
			$searchqry = "select * from search_contents where lcase(page_contents) LIKE lcase('%".$qqvar."%')";
			$querymeta = "select page_name, url_name, password from site_pages where lcase(password) LIKE lcase('%".$qqvar."%')";
			$cartqry = "select * from cart_products where (lcase(PROD_DESC) LIKE lcase('%".$qqvar."%') or lcase(PROD_NAME) LIKE lcase('%".$qqvar."%') or lcase(OPTION_KEYWORDS) LIKE lcase('%".$qqvar."%'))";
		} else {
			$searchqry .=  " AND lcase(page_contents) LIKE lcase('%".$qqvar."%')";
			$querymeta .= " AND lcase(password) LIKE lcase('%".$qqvar."%') OR lcase(password) LIKE lcase('%".$trimmed."%')";
			$cartqry .=  " AND (lcase(PROD_DESC) LIKE lcase('%".$qqvar."%') or lcase(PROD_NAME) LIKE lcase('%".$qqvar."%') or lcase(OPTION_KEYWORDS) LIKE lcase('%".$qqvar."%'))";
		}
	}
}

if ($z == 'yes') {
	$detsearch = '';
	foreach($_SESSION['array'] as $sesvar=>$sesval) {
		if (eregi('\$\$cart\$\$', $sesval)){
			$linksplit1 = split('\$\$cart\$\$', $sesval);
			$carttitle = $linksplit1[0];
		}
		
		if ($detsearch == '') {
			$detsearch = "page_name='".$sesval."'";
			$cartdetsearch = "PROD_NAME='".$carttitle."'";
		} else {
			$detsearch .= " or page_name='".$sesval."'";
			$cartdetsearch .= " or PROD_NAME='".$carttitle."'";
		}
	}
	$querymeta = $querymeta." and (".$detsearch.")";
	$searchqry = $searchqry." and (".$detsearch.")";
	$cartqry = $cartqry." and (".$cartdetsearch.")";
}
	if ($trimmed != "" && $cansearch == 'true') {
		$numresults2=mysql_query($searchqry);
		$numrows=mysql_num_rows($numresults2);
		$cartqry = $cartqry." AND (OPTION_DISPLAY='Y')";
		$cartsearch=mysql_query($cartqry);
		$cartnumrows=mysql_num_rows($cartsearch);
		$numrows=$numrows+$cartnumrows;		
	}
$trimmed = stripslashes($trimmed);


if ($countarray > 1) {
		$cxc = $countarray + 1;
		$qvar2[$cxc] = $trimmed;
}

$a = $s + ($limit) ;
if ($a > $numrows) { $a = $numrows ; }


if($searchopts['link_color'] != '') {
$linkstyle = "<style>\n";
$linkstyle .= "a.searchlink:link {color: #".$searchopts['link_color']."; text-decoration:none; border-bottom:1px solid #".$searchopts['link_color'].";}\n";
$linkstyle .= "a.searchlink:visited {color: #".$searchopts['link_color']."; text-decoration:none; border-bottom:1px solid #".$searchopts['link_color'].";}\n";
$linkstyle .= "a.searchlink:hover {color: #".$searchopts['link_hover']."; text-decoration:none; border-bottom:1px solid #".$searchopts['link_hover'].";}\n";
$linkstyle .= "a.searchlink:active {color: #".$searchopts['link_color']."; text-decoration:none; border-bottom:1px solid #".$searchopts['link_color'].";}\n";
$linkstyle .= "</style>";
$searchoutput .= $linkstyle;
}

$searchoutput .= "<table valign=top border=0 cellpadding=0 cellspacing=0 width=100% class=sohotext".$mstyle."align=left>\n";
$searchoutput .= "  <tr>\n";
$searchoutput .= "    <td".$mstyle."class=sohotext valign=bottom width=100%><br>\n";

$searchoutput .= "    <form name=\"form\" action=\"search.php";
if ($_GET['l'] != '') { $searchoutput .= '?s='.$_GET['s']; }
$searchoutput .= "\" method=\"get\">\n";
$searchoutput .= "    ".$searchopts['search_field_label']." <input type=\"text\" name=\"q\" value=\"".str_replace('"', '&quot;', $trimmed)."\"num>\n";
$searchoutput .= "    <input type=\"submit\" name=\"Submit\" value=\"";
if ($searchopts['search_button_text'] != '') {
  $searchoutput .= $searchopts['search_button_text'];
} else {
  $searchoutput .= "Search";
}



$searchoutput .= "\">&nbsp;&nbsp;";

if($searchopts['display_match_exact_phrase'] == 'yes'){
	$searchoutput .= "<br>\n";
	$searchoutput .= "<span style=\"font-size: 80%;\" valign=bottom><input type=\"checkbox\" name=\"t\" value=\"phrase\"";
	if ($_GET['t']=='phrase') { $searchoutput .= " checked"; }
	$searchoutput .= ">Match Exact Phrase&nbsp;&nbsp;&nbsp;&nbsp;</span>\n";
}
if($numrows > 1) {
  $searchoutput .= "<span style=\"font-size: 80%;\"><input  type=\"checkbox\" name=\"z\" value=\"yes\">Search Within Results</span>";
}
$searchoutput .= "    </td>\n";
$searchoutput .= "  </tr>\n";
$searchoutput .= "  <tr valign=top>\n";
$bmstyle = "style=\"padding-bottom:5px;\"";
$searchoutput .= "    <td ".$bmstyle.$mstyle."class=sohotext valign=top width=100%>\n";



	if( $searchopts['stats'] != 'no' ) {

		if(!table_exists("search_stats")) {	if(!mysql_query("CREATE TABLE search_stats (search_count VARCHAR(30), search_phrase VARCHAR(100), month VARCHAR(40), found VARCHAR(3))")) { echo "1".mysql_error(); exit;	} }
		if($numrows != 0) { $found = 'yes'; } else { $found = 'no'; }
		$thismonth = date(M)."-".date(Y);
		foreach($qvar2 as $qval=>$qqvar) {
		$qqvar = eregi_replace('  ', ' ', $qqvar);
		$qqvar = eregi_replace('  ', ' ', $qqvar);
		if($qqvar != ' ' && $qqvar != '') {
			if(mysql_get_client_info() >= 4) {
				$searchstatsqrystring = "select * from search_stats where CAST(search_phrase as char) = '$qqvar' and month='$thismonth'";
			} else {
				$searchstatsqrystring = "select * from search_stats where lcase(search_phrase) = lcase('$qqvar') and month='$thismonth'";
			}
			$searchstatsqry = mysql_query($searchstatsqrystring);
			$searchstatsqry = mysql_fetch_array($searchstatsqry);

			
			if ($searchstatsqry['search_phrase'] == '') {
				$qqvar = slashthis($qqvar);
				if(!mysql_query("insert into search_stats (search_count, search_phrase, month, found) values('1', '$qqvar', '$thismonth', '$found')")){ echo "3".mysql_error(); exit;	}
			} else {
				$search_phrase = $searchstatsqry['search_phrase'];
				$statcount = $searchstatsqry['search_count'] + 1;
				if(!mysql_query("update search_stats set search_count='$statcount', search_phrase='$qqvar', month='$thismonth', found='$found' where search_phrase='$qqvar' AND month='$thismonth'")){ echo "2".mysql_error(); exit;	}
			}
			
		}
		}
	}

if ($_GET['q'] == '') {
	$cansearch = 'false';
}

if ($cansearch == 'true') {

	foreach($qvar2 as $qtval=>$qqvar) {
		if ($qtval == 0) {
			$sstring = "[^|\r:\n\t,\.]*".$qqvar."[^\r\n\t\.:]*[\r\t\n\.:]";
		} else {
			$sstring .= "|[^|\r:\n\t,\.]*".$qqvar."[^\r\n\t\.:]*[\r\t\n\.:]";
		}
	}

	if (count($qvar2) == '1') {
	  $sstring = "[^|\r:\n\t,\.]*".$qqvar."[^\r\n\t\.:]*[\r\t\n\.:]";
	}

	$scnum = 0;
	while($shopparr = mysql_fetch_array($cartsearch)) {		
		$prodsecurity = $shopparr["OPTION_SECURITYCODE"];
		if($prodsecurity == 'Public') {
			$secauth = 1;
		}	else {
			$secauth = eregi($prodsecurity, $_SESSION['GROUPS']);
		}
		
		if($secauth == 1) {
			$title = $shopparr["PROD_NAME"];
			$page_name = $title;
			$this_page = $title;				
			$pagecontent = $title.$shopparr["OPTION_KEYWORDS"].$shopparr["PROD_DESC"];
			$prodpri = $shopparr["PRIKEY"];
			$row[$title] = $pagecontent;
			$rowzz[$title.'$$cart$$'.$prodpri] = $pagecontent;
			$countitz = '';		
			$q = 1;
			$countitz = '';
			foreach($qvar2 as $qval=>$qqvar) {
				$countit = spliti($qqvar, $pagecontent);
				$countit[$q] = count($countit) - 1;
				$countitz = $countit[$q] + $countitz;
				$q++;				
			}
			$countitz;
			$hitz = $countitz;
			$srow = $numrows - $scnum;
			$scnum = $scnum + 1;
		  $lastnum = (($numrows + 3)  - $srow );
		  $lastnum = $lastnum * .001;
		  $lastnum = $lastnum * - 1;
			$hitz = $hitz + $lastnum;
		  $soccer["$hitz"] = $title.'$$cart$$'.$prodpri;		
		}
	}

	while ($row = mysql_fetch_array($numresults2)) {
		$title = $row["page_name"];
		$page_name = $title;
		$this_page = $title;
		$pagecontent = $row["page_contents"];
		$rowzz[$title] = $pagecontent;
		$countits = '';
		$countmeta = '';
		$querymetaf = $querymeta." AND page_name='$title'";
		$resultsmeta = mysql_query($querymetaf);
		$numrowsmeta = mysql_num_rows($resultsmeta);
		$meta = mysql_fetch_array($resultsmeta);
		$keywords = eregi_replace('~~~SEP~~~CON', '', $meta['password']);

		foreach($qvar2 as $qval=>$qqvar) {
			$countmet = spliti($qqvar, $keywords);
			$countmeta = count($countmet) + $countmeta;
			$countit = spliti($qqvar, $pagecontent);
			$countits = count($countit) + $countits;
		}

		$countmetass = $countmeta - 1;
		$countmeta1 = $countmetass * 5;
		$countits = $countits + $countmeta1;
		$hits = $countits;
	  $lastnum = (($numrows + 3)  - $row["prikey"] );
	  $lastnum = $lastnum * .001;
	  $lastnum = $lastnum * -1;
	  $hits = $hits + $lastnum;
	  $hits = $hits - 1;
	  $soccer["$hits"] = $title;
	}

	krsort($soccer);
	unset($_SESSION['array']);
	$_SESSION['array'] = $soccer;

	foreach($soccer as $ballz=>$srchtxt) {
		$pagecontent = $rowzz[$srchtxt];
		$title = $srchtxt;
		$ball=$ballz;

		if ($howmany2 = preg_match_all("/".$sstring."?/i", $pagecontent, $texts)) {
			$texts[0][0] = eregi_replace(".$", '', $texts[0][0]);
			$texts[0][1] = eregi_replace(".$", '', $texts[0][1]);
			$texts[0][2] = eregi_replace(".$", '', $texts[0][2]);

		  if ($howmany2 == 2) { $resttext = "&nbsp;&nbsp;".$texts[0][1]."..."; }
		  if ($howmany2 > 3) { $resttext = "&nbsp;&nbsp;".$texts[0][1]."...&nbsp;&nbsp;".$texts[0][2]."..."; }

			$pagecontent = $texts[0][0]."...".$resttext;
			$resttext = '';
		  $hitcount++;
		  $hits = round($ball);

		  if ($dividend == "" ) {
		    $dividend = 100 / $hits;
		  }
		  $matcharray = array();

		  foreach($qvar2 as $qval=>$qqvar) {
		    preg_match_all("/$qqvar/i", $pagecontent, $regs);

		    foreach($regs[0] as $xx=>$xy) {
					$matcharray[$xy] = $xy;
		    }
		  }

			foreach($matcharray as $xxy=>$xyy) {
				$pagecontent = ereg_replace("$xyy{1}", "#!~~~!#".$xyy."#@~~~@#", $pagecontent);
			}

			$pagecontent = ereg_replace('#!~~~!#', "<font color=\"#".$style1."\">", $pagecontent);
			$pagecontent = ereg_replace('#@~~~@#', "</font>", $pagecontent);
			$secqry = mysql_query("select username from site_pages where page_name = '$title'");
			$loginsecure = '';
			$secgrp = mysql_fetch_array($secqry);

			if ((!eregi($secgrp['username'], $_SESSION['GROUPS']) && $secgrp['username'] != '') || ($_SESSION['GROUPS'] == '' && $secgrp['username'] != '')) {
				$pagecontent = "<font color=\"#".$style1."\">Secure Page!</font> You must login to display the search results for this page.";
			}

		  $webtitle = eregi_replace(' ', '_', $title);
		  $hits = $hits * $dividend;
		  $hits = $hits - 1;
		  $hits = $hits + .90;
		  $hits = round($hits, 1);

		  if(($hitcount <= ($limit + $s)) && $hitcount > $s) {
		    if (round($ball) == 1) { $times = "Hit"; } else { $times = "Hits"; }
		    if ($searchopts['display_percent'] != "no") { $display_percent = $hits.'%'; }
		    if ($searchopts['display_hits'] != "no") {$disphits = "</strong><span style=\"font-size: 80%;\">(".round($ball)." ".$times.")</span>"; }
		    if ($searchopts['link_color'] != '') { $numcolor = " color:#".$searchopts['style2'].";"; }
		    if ($searchopts['display_number'] != "no") { $hittcount = "<strong><span style=\"font-size: 90%; color:#".$searchopts['link_color'].";\">".$hitcount.")</strong></span>&nbsp;"; }
		  
		  ////searc cart 
		    if (eregi('\$\$cart\$\$', $title)){
		    	$linksplit = split('\$\$cart\$\$', $title);
		    	$pagelink = "<a href=shopping/pgm-more_information.php?id=".$linksplit[1]."&=SID#MOREINFO class=\"searchlink\">".$linksplit[0]."";				
					$shopparrq = mysql_query("select * from cart_products where PROD_NAME = '$linksplit[0]'");
					$shopparr = mysql_fetch_array($shopparrq);
					$THIS_IMAGE = "";
					$TEST_IMAGE = "";
					if ((strlen($shopparr[PROD_THUMBNAIL]) != '') || (strlen($shopparr[PROD_FULLIMAGENAME]) != '' )) {
						if (strlen($shopparr[PROD_THUMBNAIL]) > 2) {
							$THIS_IMAGE = "images/$shopparr[PROD_THUMBNAIL]";
							$TEST_IMAGE = $doc_root."/images/$shopparr[PROD_THUMBNAIL]";
						} else {
							$THIS_IMAGE = "images/$shopparr[PROD_FULLIMAGENAME]";
							$TEST_IMAGE = $doc_root."/images/$shopparr[PROD_FULLIMAGENAME]";
						}
			
						$WH = "";
						if (file_exists("$TEST_IMAGE")) {
							$tempArray = getImageSize($TEST_IMAGE);
							$origW = $tempArray[0];
							$origH = $tempArray[1];
							$oW = $origW;	
							$oH = $origH;
							$WH = " width=".$origW." height=".$origH;
							if ( $origW > 99 ) {
								$calc = 99 / $origW;
								$hcalc = $origH * $calc;
								$nheight = round($hcalc);
								$WH = "width=\"100\" height=\"".$nheight."\"";
							}
						}
						$shopoutput = "<IMG SRC=\"".$THIS_IMAGE."\" ".$WH." VSPACE=\"2\" valign=text-bottom align=left HSPACE=\"5\"  BORDER=\"0\">";
						$shopoutput2 = "<tr>\n		<td class=sohotext".$mstyle."><br></td></tr>";

					}	else {

		   			$shopoutput = "";
		   			$shopoutput2 = "";
		   		}
		   														
				} else {

		   		$pagelink = "<a href=index.php?pr=".$webtitle." class=\"searchlink\">".$title."";
		   		$shopoutput = "";
		   		$shopoutput2 = "";
		  	}
				////end searc cart 
				$outputz .= "	<tr>\n		<td class=sohotext".$mstyle.">".$hittcount."<strong>".$pagelink."</strong></a> ".$display_percent."&nbsp;".$disphits."</font>\n		</td>\n	</tr>\n";
				$outputz .= "	<tr>\n		<td class=sohotext style=\"padding-left:11; padding-top:1;\"><span".$mstyle.">".$shopoutput.$pagecontent."</span>\n<br><br></td>\n	</tr>\n";
				$outputz .= $shopoutput2;
			}
		}
	}

	$numrows = $hitcount;
	$numrows = $hitcount;
	$a = $s + ($limit) ;
	if ($a > $numrows) { $a = $numrows ; }

	if($numrows != 0) {
		$output = $searchoutput;
		$b = $s + 1 ;
		$thistime = round(microtime(), 2);
		$finaltime = ($thistime-$sessiontime);
		if($finaltime < 0){ $finaltime = ($finaltime * -1); }
		$output .= "		<span".$mstyle.">Results $b - $a of $hitcount for <font color=\"#".$style1."\">".$trimmed."</font> <span style=\"font-size: 70%;\">(".$finaltime." seconds).</span></span></form>\n		</td>\n	</tr>\n";
		$_SESSION['searchtime'] = '';
		$thistime = '';
		$sessiontime = '';
	} else {
		$output .= $searchoutput."		<p>Sorry, your search: &quot;" . $trimmed . "&quot; returned zero results...</p>\n		</td>\n	</tr>\n";
		$_SESSION['searchtime'] = '';
		$thistime = '';
		$sessiontime = '';
	}
	$output .= $outputz;
	
	
	if ($hitcount > 0) {
		$output .= "		<td valign=bottom align=left".$mstyle."class=sohotext>\n";
		$output .= "		<table border=0 cellpadding=0 cellspacing=0 width=100% align=left valign=bottom>\n";
		$output .= "			<tr>\n";
		$currPage = (($s/$limit) + 1);
	  if ($s>=1) { // bypass PREV link if s is 0
	  	$output .= "				<td align=center valign=top class=sohotext".$mstyle."width=\"33%\"><br>\n";
			$prevs=($s-$limit);
			$q = $qvar;
			if (($s - $limit) < 0) {
				$output .= "				&nbsp;<a href='$PHP_SELF?s=$prevs&q=$q&l=$limit&t=$t' class=\"searchlink\">&lt;&lt; Prev ".($limit + ($s - $limit))."</a>&nbsp&nbsp;\n";
			  $output .= "				</td>\n";
			} else {
				$output .= "				&nbsp;<a href='$PHP_SELF?s=$prevs&q=$q&l=$limit&t=$t' class=\"searchlink\">&lt;&lt; Prev $limit</a>&nbsp&nbsp;\n";
			  $output .= "				</td>\n";
			}

	  } else {
	  	$output .= "				<td align=center valign=top class=sohotext".$mstyle."width=\"33%\"><br>&nbsp;\n				</td>\n";
	  }

	  $pages=intval($numrows/$limit);
	  if ($numrows%$limit) {
	  	$pages++;
	  }

		$output .= "				<td align=center valign=top class=sohotext".$mstyle.">\n";
		$output .= "				<br>&nbsp;\n				</td>\n";
	  if (!((($s+$limit)/$limit)==$pages) && $pages!=1  && $hitcount - ($limit+$s) > 0)  {
			$q = $qvar;
			$news=$s+$limit;
			if (($hitcount - ($limit + $s)) < $limit ) {
			  $output .= "				<td align=center valign=top class=sohotext".$mstyle."width=\"33%\">\n";
			  $output .= "				<br>&nbsp;<a href='$PHP_SELF?s=$news&q=$q&l=$limit&t=$t' class=\"searchlink\">Next ".($hitcount - ($limit+$s))." &gt;&gt;</a>\n";
			  $output .= "				</td>\n";
				$output .= "			</tr>\n";

			} else {
			  $output .= "				<td align=center valign=top class=sohotext".$mstyle."width=\"33%\">\n";
			  $output .= "				<br>&nbsp;<a href='$PHP_SELF?s=$news&q=$q&l=$limit&t=$t' class=\"searchlink\">Next $limit &gt;&gt;</a>\n";
			  $output .= "				</td>\n";
			  $output .= "			</tr>\n";
			}

		} else {
			$q = $qvar;
			$output .= "				<td align=center valign=top width=\"33%\" class=sohotext".$mstyle."><br>&nbsp;\n				</td>\n";
			$output .= "			</tr>\n";
		}

	if ($hitcount - 10 > '-1') {
		if ($hitcount > 10) { $botmenu = "			<tr>\n				<td align=left width=\"33%\">\n				&nbsp;\n				</td>\n				<td align=center valign=bottom width=\"33%\" class=sohotext".$mstyle."><br><br><a href='$PHP_SELF?s=$s&q=$q&l=10&t=$t' class=\"searchlink\">10</a> | <a href='$PHP_SELF?s=$s&q=$q&l=15&t=$t' class=\"searchlink\">15</a> | <a href='$PHP_SELF?s=$s&q=$q&l=25&t=$t' class=\"searchlink\">25</a> | <a href='$PHP_SELF?s=$s&q=$q&l=50&t=$t' class=\"searchlink\">50</a><br>(results per page)\n				</td>\n				<td align=left>\n				&nbsp;\n				</td>\n			</tr>\n"; }
		if ($limit == "10") { $botmenu = "			<tr>\n				<td align=left width=\"33%\">\n				&nbsp;\n				</td>\n				<td align=center valign=bottom width=\"33%\" class=sohotext".$mstyle."><br><br>10 | <a href='$PHP_SELF?s=$s&q=$q&l=15&t=$t' class=\"searchlink\">15</a> | <a href='$PHP_SELF?s=$s&q=$q&l=25&t=$t' class=\"searchlink\">25</a> | <a href='$PHP_SELF?s=$s&q=$q&l=50&t=$t' class=\"searchlink\">50</a><br>(results per page)\n				</td>\n				<td align=left>\n				&nbsp;\n				</td>\n			</tr>\n"; }
		if ($limit == "15") { $botmenu = "			<tr>\n				<td align=left width=\"33%\">\n				&nbsp;\n				</td>\n				<td align=center valign=bottom width=\"33%\" class=sohotext".$mstyle."><br><br><a href='$PHP_SELF?s=$s&q=$q&l=10&t=$t' class=\"searchlink\">10</a> | 15 | <a href='$PHP_SELF?s=$s&q=$q&l=25&t=$t' class=\"searchlink\">25</a> | <a href='$PHP_SELF?s=$s&q=$q&l=50&t=$t' class=\"searchlink\">50</a><br>(results per page)\n				</td>\n				<td align=left>\n				&nbsp;\n				</td>\n			</tr>\n"; }
		if ($limit == "25") { $botmenu = "			<tr>\n				<td align=left width=\"33%\">\n				&nbsp;\n				</td>\n				<td align=center valign=bottom width=\"33%\" class=sohotext".$mstyle."><br><br><a href='$PHP_SELF?s=$s&q=$q&l=10&t=$t' class=\"searchlink\">10</a> | <a href='$PHP_SELF?s=$s&q=$q&l=15&t=$t' class=\"searchlink\">15</a> | 25 | <a href='$PHP_SELF?s=$s&q=$q&l=50&t=$t' class=\"searchlink\">50</a><br>(results per page)\n				</td>\n				<td align=left>\n				&nbsp;\n				</td>\n			</tr>\n"; }
		if ($limit == "50") { $botmenu = "			<tr>\n				<td align=left width=\"33%\">\n				&nbsp;\n				</td>\n				<td align=center valign=bottom width=\"33%\" class=sohotext".$mstyle."><br><br><a href='$PHP_SELF?s=$s&q=$q&l=10&t=$t' class=\"searchlink\">10</a> | <a href='$PHP_SELF?s=$s&q=$q&l=15&t=$t' class=\"searchlink\">15</a> | <a href='$PHP_SELF?s=$s&q=$q&l=25&t=$t' class=\"searchlink\">25</a> | 50<br>(results per page)\n				</td>\n				<td align=left>\n				&nbsp;\n				</td>\n			</tr>\n"; }
		}

	$output .= $botmenu;
	$output .= "		</table>\n";
	$output .= "		</td>\n";
	$output .= "	</tr>\n";
	}

	$output .= "</table>\n";
	$template_footer = eregi_replace('START PAGE CONTENT FROM CONTENT EDITOR.*END DYNAMIC PAGE CONTENT FROM PAGE EDITOR SYSTEM', '', $template_footer);
	$output .= $template_footer."\n";

} else {
	if ($trimmed == "") {
		$searchoutput .= "		<p>Please enter a search...</p></form>\n		</td>\n	</tr>\n</table>\n"; $template_footer = eregi_replace('START PAGE CONTENT FROM CONTENT EDITOR.*END DYNAMIC PAGE CONTENT FROM PAGE EDITOR SYSTEM', '', $template_footer); echo $searchoutput .= $template_footer;
	} else {
		$searchoutput .= "		<p>Sorry, your search term, <i>".$trimmed."</i>, was too short and returned zero results...</p></form>\n		</td>\n	</tr>\n</table>\n"; $template_footer = eregi_replace('START PAGE CONTENT FROM CONTENT EDITOR.*END DYNAMIC PAGE CONTENT FROM PAGE EDITOR SYSTEM', '', $template_footer); echo $searchoutput .= $template_footer; exit;
	}
}

echo $output;
?>
