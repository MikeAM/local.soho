<?php

session_start();
error_reporting(E_PARSE);

include("../../includes/config.php");
include("../../includes/db_connect.php");
include("../../program/includes/shared_functions.php");
include("../../program/includes/smt_functions.php");
$sessiontime = $_SESSION['searchtime'];
$_SESSION['searchtime'] = round(microtime(), 2);
$output = '';
$cansearch = '';
$searchopts = mysql_query("select * from search_core where prikey='1'");
$searchopts = mysql_fetch_array($searchopts);

if ($searchopts['style1'] != "") { $style1 = $searchopts['style1']; } else { $style1 = ""; }

if ($searchopts['style2'] != "") { $style2 = $searchopts['style2']; } else { $style2 = ""; }

if( $searchopts['style3'] != '' ) {
	$mstyle = " style=\"font-family: ".$searchopts['style3']."; font-size: ".$searchopts['style4']."pt; color:".$searchopts['style2'].";\" ";
} else {
	$mstyle = " ";
}

if($searchopts['link_color'] != '') {
	$linkstyle = "<style>\n";
	$linkstyle .= "a.searchlink:link {color: #".$searchopts['link_color']."; text-decoration:none; border-bottom:1px solid #".$searchopts['link_color'].";}\n";
	$linkstyle .= "a.searchlink:visited {color: #".$searchopts['link_color']."; text-decoration:none; border-bottom:1px solid #".$searchopts['link_color'].";}\n";
	$linkstyle .= "a.searchlink:hover {color: #".$searchopts['link_hover']."; text-decoration:none; border-bottom:1px solid #".$searchopts['link_hover'].";}\n";
	$linkstyle .= "a.searchlink:active {color: #".$searchopts['link_color']."; text-decoration:none; border-bottom:1px solid #".$searchopts['link_color'].";}\n";
	$linkstyle .= "</style>";
	$searchoutput = $linkstyle;
} else {
	$searchoutput = '';
}

$searchoutput .= "<table valign=top border=0 cellpadding=0 cellspacing=0 width=100% id=\"supersearch-container\" class=sohotext".$mstyle."align=left>\n";
$searchoutput .= "  <tr>\n";
$searchoutput .= "    <td".$mstyle."class=sohotext valign=bottom width=100%><br>\n";
$searchoutput .= "    <form name=\"form\" action=\"search.php";
if ($_GET['l'] != '') { $searchoutput .= '?s='.$_GET['s']; }
$searchoutput .= "\" method=\"get\" id=\"supersearch\">\n";
$searchoutput .= "    ".$searchopts['search_field_label']." <input type=\"text\" id=\"supersearch-q\" name=\"q\" value=\"".eregi_replace("\"", '&quot;', $trimmed)."\"num>\n";
$searchoutput .= "    <input type=\"submit\" id=\"supersearch-submit\" name=\"Submit\" value=\"";
if ($searchopts['search_button_text'] != '') {
  $searchoutput .= $searchopts['search_button_text'];
} else {
  $searchoutput .= "Search";
}
$searchoutput .= "\">&nbsp;&nbsp;";
if($searchopts['display_match_exact_phrase'] == 'yes' && 1==2){
	$searchoutput .= "<br>\n";
	$searchoutput .= "<span id=\"ss-exact-phrase-container\" style=\"font-size: 80%;\" valign=bottom><input type=\"checkbox\" name=\"t\" value=\"phrase\"";
	$searchoutput .= ">";
	$searchoutput .= "<span>Match Exact Phrase</span>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
}
$searchoutput .= "</form>\n";
$searchoutput .= "    </td>\n";
$searchoutput .= "  </tr>\n";
$searchoutput .= "  </table>";
echo $searchoutput;
?>