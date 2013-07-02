<?php
session_start();
if(!require_once('../../includes/product_gui.php')) {
  exit;
}

$globalprefObj = new userdata('global');

# Build color options for dropdowns
$filename = $_SESSION['docroot_path']."/sohoadmin/program/modules/mods_full/shopping_cart/shared/color_table.dat";
$file = fopen("$filename", "r");
   $data = fread($file,filesize($filename));
fclose($file);
$tData = split("\n", $data);
$tLoop = count($tData);
$numcolors = 0;
for ($x=0;$x<=$tLoop;$x++) {
   $temp = split(",", $tData[$x]);
   if ($temp[0] != "") {
      $numcolors++;
      $color_name[$numcolors] = $temp[0];
      $color_hex[$numcolors] = $temp[1];
   }
}
$color_options = "";
for ( $i=1; $i<=$numcolors; $i++ ) {
   $color_options .= "<option value=\"".$color_hex[$i]."\" style=\"background-color: #".$color_hex[$i]."\">".$color_name[$i]."</option>\n";
}

# Build list of site templates
$templatecount = 0;
$newscount = 0;
$directory = $_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/pages";
if (is_dir($directory)) {
$handle = opendir("$directory");
   while ($files = readdir($handle)) {
      if (strlen($files) > 2) {
         $templatecount++;
         $templateFile[$templatecount] = $files;
      }
   }
closedir($handle);
}

//# Build list of depreciated tCustom templates
//$directory = $doc_root."/tCustom";
//if (is_dir($directory)) {
//   $handle = opendir("$directory");
//   while ($files = readdir($handle)) {
//      if (strlen($files) > 2) {
//         if (eregi(".html", $files) || eregi(".htm", $files)) {
//            $templatecount++;
//            $templateFile[$templatecount] = "[CUSTOM]~~~".$files;
//         }
//      }
//   }
//
//   $numTemplates = $templatecount;
//   $newsTemplates = $newscount;
//   closedir($handle);
//
//} // End if is_dir()

$numTemplates = $templatecount;
$newsTemplates = $newscount;

if ($templatecount > 1) {
   sort($templateFile);
   $numTemplates--;
}
//$filename = "$doc_root/media/page_templates.txt";
//if (file_exists("$filename")) {
//   $file = fopen("$filename", "r");
//   $page_templates = fread($file,filesize($filename));
//   fclose($file);
//}

#######################################################
//$filename = $doc_root."/sohoadmin/tmp_content/template.conf";
$curtmp = mysql_query("select * from search_core where prikey='1'");
$curtmp = mysql_fetch_array($curtmp);
$CUR_TEMPLATE = $curtmp['template'];

if ($CUR_TEMPLATE == '') {
	$CUR_TEMPLATE = rtrim($globalprefObj->get('site_base_template'));
//   if (file_exists("$filename")) {
//      $file = fopen("$filename", "r");
//      $CUR_TEMPLATE = fread($file,filesize($filename));
//      fclose($file);
//      $CUR_TEMPLATE = rtrim($CUR_TEMPLATE);
//   }
}

$SELECT_OPTS = " <option value=\"\" style='font-family: Tahoma; font-size: 8pt;'>".lang("Select Base Template")."... </option>\n";
for ($x=0;$x<=$numTemplates;$x++) {
   # Custom HTML templates (uploaded via template manager)
   if (eregi("CUSTOM", $templateFile[$x])) {
      $custarray = split("~~~", $templateFile[$x]);
      if(count($custarray) == 1){
         $tmp = split("-", $templateFile[$x]);
         $tCategory = strtoupper($tmp[0]);
         $tmp[1] = eregi_replace("_", " ", $tmp[1]);
         $display = "$tCategory  > $tmp[1] ";
         if (!eregi("none", $tmp[2])) { $display .= "($tmp[2])"; }
         if ($templateFile[$x] == $CUR_TEMPLATE) { $isSel = " selected"; } else { $isSel = ""; }
         $SELECT_OPTS .= " <option value=\"".$templateFile[$x]."\" style=\"font-family: Tahoma; font-size: 8pt;\"".$isSel.">".$display." </option>\n";
      }else{
         $display = "CUSTOM > ".$custarray[1];
         $thisFile = $doc_root."/tCustom/".$custarray[1];

            // Check for Win32/IIS Directory Formating
            if (eregi("IIS", $SERVER_SOFTWARE)) {
               $thisFile = eregi_replace("/", "\\", $thisFile);
            }

         if ($thisFile == $CUR_TEMPLATE) { $isSel = " selected"; } else { $isSel = ""; }
         $SELECT_OPTS .= " <option value=\"$thisFile\" style=\"font-family: Tahoma; font-size: 8pt;\"".$isSel.">$display </option>\n";
      }

   # Factory templates (from /pages dir)
   } elseif(!eregi("unzips", $templateFile[$x])) {
      $tmp = split("-", $templateFile[$x]);
      $tCategory = strtoupper($tmp[0]);
      $tmp[1] = eregi_replace("_", " ", $tmp[1]);
      $display = "$tCategory  > $tmp[1] ";
      if (!eregi("none", $tmp[2])) { $display .= "($tmp[2])"; }
      if ($templateFile[$x] == $CUR_TEMPLATE) { $isSel = " selected"; } else { $isSel = ""; }
         $SELECT_OPTS .= " <option value=\"".$templateFile[$x]."\" style=\"font-family: Tahoma; font-size: 8pt;\"".$isSel.">".$display." </option>\n";
   } // End If CUSTOM
} // End For Loop


# Create search options table if doesn't already exist
if ( !table_exists("search_core") ) {
   $squery = "CREATE TABLE search_core (";
   $squery .= " prikey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT";
   $squery .= ", template VARCHAR(255), results_per_page VARCHAR(255)";
   $squery .= ", show_search_type VARCHAR(255), search_button_text VARCHAR(255)";
   $squery .= ", custom_button VARCHAR(255), display_percent VARCHAR(255)";
   $squery .= ", percent_text VARCHAR(255), display_hits VARCHAR(255)";
   $squery .= ", hits_text VARCHAR(255), allow_template_search VARCHAR(255)";
   $squery .= ", min_word_chars VARCHAR(255), style1 VARCHAR(255)";
   $squery .= ", style2 VARCHAR(255), style3 VARCHAR(255)";
   $squery .= ", style4 VARCHAR(255), search_field_label VARCHAR(75)";
   $squery .= ", display_search_within_results VARCHAR(5), display_match_exact_phrase VARCHAR(5), link_color VARCHAR(6)";
   $squery .= ", link_hover VARCHAR(6), stats VARCHAR(10)";
   $squery .= ", display_number VARCHAR(5)";
   $squery .= ")"; // EDIT HERE and specify your table and field names for the SQL query

   if ( !mysql_query($squery) ) {
      echo mysql_error(); exit;
   }

   # Insert default display settings
   $qry = "insert into search_core (";
   $qry .= "prikey, template, results_per_page, show_search_type, search_button_text, custom_button";
   $qry .= ", display_percent, percent_text, display_hits, hits_text, allow_template_search, min_word_chars";
   $qry .= ", style1, style2, style3, style4, search_field_label, display_search_within_results, display_match_exact_phrase, link_color";
   $qry .= ", link_hover, stats, display_number";
   $qry .= ") ";

   $qry .= "values (";
   $qry .= "'', '', '25', '', 'Search', ''";
   $qry .= ", 'yes', '', 'yes', '', '', '3'";
   $qry .= ", 'FF000', '', '', '13', '', 'no', 'yes', ''";
   $qry .= ", '', 'yes', 'yes'";
   $qry .= ")";
   if ( !mysql_query($qry) ) {
      echo mysql_error(); exit;
   }
}


if($_POST['newdesign'] == '1') {
   $prikey = $_POST['prikey'];
   $template = $_POST['template'];
   $unsearch_pages = $_POST['unsearch_pages'];
   $results_per_page = $_POST['results_per_page'];
   $show_search_type = $_POST['show_search_type'];
   $search_button_text = $_POST['search_button_text'];
   $custom_button = $_POST['custom_button'];
   $display_percent = $_POST['display_percent'];
   $percent_text = $_POST['percent_text'];
   $display_hits = $_POST['display_hits'];
   $hits_text = $_POST['hits_text'];
   $allow_template_search = $_POST['allow_template_search'];
   $min_word_chars = $_POST['min_word_chars'];
   $style1 = $_POST['style1'];
   $style2 = $_POST['style2'];
   $style3 = $_POST['style3'];
   $style4 = $_POST['style4'];
   $templatename = $_POST['site'];

   $qry = "update search_core set template='$templatename', results_per_page='$results_per_page',";
   $qry .= "show_search_type='$show_search_type', show_search_type='$show_search_type', ";
   $qry .= "search_button_text='$search_button_text', custom_button='$custom_button',";
   $qry .= "display_percent='$display_percent', percent_text='$percent_text', display_hits='$display_hits', ";
   $qry .= "hits_text='$hits_text', allow_template_search='$allow_template_search', min_word_chars='$min_word_chars', ";
   $qry .= "style1='$style1', style2='$style2', style3='$style3', style4='$style4', search_field_label='".$_POST['search_field_label']."'";
   $qry .= ", display_search_within_results='".$_POST['display_search_within_results']."'";
   $qry .= ", display_match_exact_phrase='".$_POST['display_match_exact_phrase']."'";
   
   $qry .= ", link_color='".$_POST['link_color']."'";
   $qry .= ", link_hover='".$_POST['link_hover']."'";
   $qry .= ", stats='".$_POST['stats']."'";
   $qry .= ", display_number='".$_POST['display_number']."'";
   $qry .= " where prikey='1'";
   if( !mysql_query($qry) ) {
      echo mysql_error(); exit;
   }

	$getq = mysql_query("select page_name from site_pages where page_name='Search'");
	$qget = mysql_fetch_array($getq);
	if($qget[0] == 'Search') {
		mysql_query("update site_pages set TEMPLATE='$templatename' where page_name='Search'");
	}

}

ob_start();
?>

<link rel="stylesheet" type="text/css" href="module.css">
<!--
<script type="text/javascript" src="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/display_elements/js_functions.php"></script>
-->

<script type="text/javascript">
// Use this one for all new stuff, phase old method out
function set_cartcss(field_id, newval) {
   // Populate textbox with passed value
   document.getElementById(field_id).value = newval;

   // Select new value from dropdown, if there's an option for it
   ddval = document.getElementById(field_id+'-dd');
   ddval.value = newval;

   // Custom option selected?
   if ( (ddval.value !== newval) || newval == 'custom' ) {
      ddval.value = 'custom';
      //$(field_id).disabled = false;
   } else {
      //$(field_id).disabled = true;
   }
}

// Update search column preview to show new color setting
function preview_cartcss(thingid, cssprop, newval) {
   thing = document.getElementById(thingid);
   eval("thing.style."+cssprop+" = '"+newval+"'");
   //alert('['+cssprop+'] = ('+eval("thing.style."+cssprop)+')');
}

function set_headerbg(color) {
   var fullcolor = "#"+color;
   document.displaysettings.display_headerbg.value = fullcolor;
   header1.style.background = fullcolor;
   header2.style.background = fullcolor;
   header3.style.background = fullcolor;
}

function set_headertxt(color) {
   var fullcolor = "#"+color;
   document.displaysettings.display_headertxt.value = fullcolor;
   header1.style.color = fullcolor;
   header2.style.color = fullcolor;
   header3.style.color = fullcolor;
}

function set_cartbg(color) {
   var fullcolor = "#"+color;
   document.displaysettings.display_cartbg.value = fullcolor;
   cartarea.style.background = fullcolor;
   cartarea.style.background = fullcolor;
   cartarea.style.background = fullcolor;
}

function set_carttxt(color) {
   var fullcolor = "#"+color;
   document.displaysettings.display_carttxt.value = fullcolor;
   cartarea.style.color = fullcolor;
   cartarea.style.color = fullcolor;
   cartarea.style.color = fullcolor;
}

// Show/hide options in preview area (custom for search plugin)
function yesno_toggle(thingid, ddvalue) {
   if ( ddvalue == "no" ) {
      document.getElementById(thingid).style.display = 'none';
   } else {
      document.getElementById(thingid).style.display = 'inline';
   }
}

</script>

</head>

<?php

/////////




$curtmp = mysql_query("select * from search_core where prikey='1'");
$curtmp = mysql_fetch_array($curtmp);

$coretable = "<div id=\"displaysettings_div\" style=\"position:absolute; left:0px; width:100%; border: 2px none #000000; visibility: visible; overflow: auto;\">\n";
$coretable .= " <table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"95%\" align=\"center\" style=\"margin-top: 10px;\">\n";
$coretable .= "  <tr> \n";
$coretable .= "   <td align=\"center\" valign=\"top\">\n";

# Main module table starts here

$coretable .= "    <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" bgcolor=\"white\" class=\"feature_sub\" align=\"center\">\n";
//
//$coretable .= "      <tr>\n";
//$coretable .= "       <td valign=\"top\" class=\"nopad\">\n";
//$coretable .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"feature_module_heading\">\n";
//$coretable .= "         <tr>\n";
//$coretable .= "          <td width=\"100%\" colspan=\"3\" class=\"fgroup_title\"><a href=\"".$_SERVER['PHP_SELF']."\" class=\"white noline\">SuperSearch Management Module</a></td>\n";
//$coretable .= "         <!---icon logo and heading text-->\n";
//$coretable .= "         </tr>\n";
//$coretable .= "         <tr>\n";
//$coretable .= "          <td width=\"9%\" align=\"center\"><a href=\"".$_SERVER['PHP_SELF']."\"><img src=\"supersearch-d9_bg.gif\" border=\"0\"></a></td>\n";
//$coretable .= "          <td><h1>Display Settings</h1>\n";
//$coretable .= "           <p>Set up how you want your search results page to look.</p>\n";
//$coretable .= "          </td>\n";
//
//# Creates $link_bar var that contains html for section links
include("link_bar.php");
$coretable .= $link_bar;

//
//$coretable .= "         </tr>\n";
//$coretable .= "        </table>\n";
//$coretable .= "       </td>\n";
//$coretable .= "      </tr>\n";



$coretable .= "      <tr>\n";
$coretable .= "       <td align=\"left\" valign=\"top\">\n";

$coretable .= "    <form name=\"displaysettings\" method=\"post\" action=\"#\">\n";
$coretable .= "    <input type=\"hidden\" name=\"action\" value=\"template\">\n";
/*---------------------------------------------------------------------------------------------------------*
 ___                 _
| _ \ _ _  ___ __ __(_) ___ __ __ __
|  _/| '_|/ -_)\ V /| |/ -_)\ V  V /
|_|  |_|  \___| \_/ |_|\___| \_/\_/

# Begin preview area
/*---------------------------------------------------------------------------------------------------------*/
$searchboxsample = "";
$searchboxsample .= "<div style=\"text-align: center;background-image:url('preview_area.gif');padding: 8px;margin: -5px -5px 10px -5px;\">\n";
$searchboxsample .= "<table width=\"500\" border=\"0\" cellpadding=\"10\" cellspacing=\"0\" id=\"style2-text\" align=\"center\" style=\"opacity: .9;border: 0px dashed #ccc;color: ".$style2."; font-family: ".$style3.";\">";
$searchboxsample .= " <tr valign=\"top\" align=left>";
$searchboxsample .= "  <td align=\"left\" class=sohotext valign=\"top\" width=100% id=\"style4-text\" style=\"background-color: white; font-size: ".$style4."px;\" >\n";
$searchboxsample .= "  <form name=\"form\" action=\"\" method=\"get\">";

# prev-search_field_label
$searchboxsample .= "   <span id=\"prev-search_field_label\">".$curtmp['search_field_label']."</span>";

# q (search field)
$searchboxsample .= "   <input type=\"text\" name=\"q\" value=\"company\">";

# prev-search_button_text
$searchboxsample .= "   <input id=\"prev-search_button_text\" type=\"button\" value=\"".$curtmp['search_button_text']."\">&nbsp;&nbsp;\n";

# prev-display_search_within_results
# [ ] search within these results


$searchboxsample .= "   <br/>\n";

if ( $curtmp['display_match_exact_phrase'] == "no" ) { $display = "none"; } else { $display = "inline"; }
$searchboxsample .= "   <span id=\"prev-display_match_exact_phrase\" style=\"display: ".$display."\">\n";
$searchboxsample .= "   <input type=\"checkbox\" name=\"t\" value=\"phrase\">Match Exact Phrase&nbsp;&nbsp;&nbsp;&nbsp;\n";
$searchboxsample .= "   </span>\n";


if ( $curtmp['display_search_within_results'] == "no" ) { $display = "none"; } else { $display = "inline"; }

$searchboxsample .= "   <span id=\"prev-display_search_within_results\" style=\"display: ".$display."\">\n";
$searchboxsample .= "    <input type=\"checkbox\" name=\"z\" value=\"yes\">search within these results";
$searchboxsample .= "   </span><br><br>\n";

$searchboxsample .= "   <i>Results 1 - 1 of 1 for <span id=\"style1-text\" style=\"font-family: ".$style3."; color: ".$style1.";\">company</span>. \n";
$searchboxsample .= "   (0.44 seconds)\n<br/>\n";

# prev-display_number
if ( $curtmp['display_percent'] == "no" ) { $display = "none"; } else { $display = "inline"; }
$searchboxsample .= "   <span id=\"prev-display_number\" style=\"display: ".$display."\">1)</span>\n";

# prev-link_color
$searchboxsample .= " <a id=\"prev-link_color\" style=\"color: #".$curtmp['link_color'].";\"><u>About Us</u></a> \n";

# prev-display_percent
if ( $curtmp['display_percent'] == "no" ) { $display = "none"; } else { $display = "inline"; }
$searchboxsample .= "   <span id=\"prev-display_percent\" style=\"display: ".$display."\">99.9%</span>\n";

$searchboxsample .= "   &nbsp;\n";

# prev-display_hits
if ( $curtmp['display_hits'] == "no" ) { $display = "none"; } else { $display = "inline"; }
$searchboxsample .= "   <span id=\"prev-display_hits\" style=\"display: ".$display."\">(1 Hit)</span>\n";
$searchboxsample .= "   </i>";

$searchboxsample .= "   <br/>\n";
$searchboxsample .= "&nbsp;&nbsp;&nbsp;&nbsp;Our <span id=\"style1-text2\" style=\"color: ".$style1.";\">Company</span> has been a leader in our field for...<br/>";
$searchboxsample .= "  </td>";
$searchboxsample .= " </tr>";
$searchboxsample .= "</table>";
$searchboxsample .= "</div>";
/*--------------------------------------------End preview table--------------------------------------------*/

# Add preview to main html container var
$coretable .= $searchboxsample;


/*---------------------------------------------------------------------------------------------------------*
 ___       _
| __|__ __| |_  _ _  __ _
| _| \ \ /|  _|| '_|/ _` |
|___|/_\_\ \__||_|  \__,_|

# These are all tied to the preview area
/*---------------------------------------------------------------------------------------------------------*/
$coretable .= "      <fieldset>\n";
$coretable .= "      <legend><b>Search results page styles -- on/off</b></legend>\n";
$coretable .= "       <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" style=\"font-family: Tahoma; font-size: 8pt; border: 0;\" class=\"feature_sub\">\n";
$coretable .= "        <tr>\n";

# search_field_label
$coretable .= "         <td align=\"left\" valign=\"top\" style=\"padding-right: 20px;\">\n";
$coretable .= "          Search Field Label:&nbsp;\n";
$onblur_action = "onblur=\"document.getElementById('prev-search_field_label').innerHTML = this.value;\"";
$coretable .= "          <input id=\"search_field_label\" type=\"text\" name=\"search_field_label\" value=\"".$curtmp['search_field_label']."\" ".$onblur_action." style=\"font-family: Tahoma; font-size: 8pt; visibility: visible;\">\n";
$coretable .= "         </td>\n";

# search_button_text
$coretable .= "        <td align=\"left\" valign=\"bottom\">\n";
$coretable .= "         Search button text:&nbsp;\n";
$onblur_action = "onblur=\"document.getElementById('prev-search_button_text').value = this.value;\"";
$coretable .= "         <input id=\"search_button_text\" type=\"text\" name=\"search_button_text\" value=\"".$curtmp['search_button_text']."\" ".$onblur_action." style=\"font-family: Tahoma; font-size: 8pt; visibility: visible;\">\n";
$coretable .= "         </td>\n";


# display_number
$coretable .= "        <td colspan=2 align=\"left\" valign=\"bottom\">\n";
$coretable .= "         Display result number?&nbsp;\n";
$onchange_action = "onchange=\"yesno_toggle('prev-display_number', this.value);\"";
$coretable .= "          <select id=\"display_number\" name=\"display_number\" ".$onchange_action." style=\"font-family: Tahoma; font-size: 8pt; visibility: visible;\">\n";
$option_values = array('yes', 'no');
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['display_number'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   $coretable .= "           <option value=\"".$value."\"".$selected.">".ucwords($value)."</option>\n";
}
$coretable .= "          </select>\n";
$coretable .= "         </td>\n";

$coretable .= "        </tr>\n";

# display_percent
$coretable .= "        <tr>\n";
$coretable .= "         <td align=\"left\">\n";
$coretable .= "          Display relevance percentage?&nbsp;\n";
$onchange_action = "onchange=\"yesno_toggle('prev-display_percent', this.value);\"";
$coretable .= "          <select id=\"display_percent\" name=\"display_percent\" ".$onchange_action." style=\"font-family: Tahoma; font-size: 8pt; visibility: visible;\">\n";
$option_values = array('yes', 'no');
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['display_percent'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   $coretable .= "           <option value=\"".$value."\"".$selected.">".ucwords($value)."</option>\n";
}
$coretable .= "          </select>\n";
$coretable .= "         </td>\n";

# display_hits
$coretable .= "         <td align=\"left\" style=\"padding-right: 20px;\">\n";
$coretable .= "          Display match/hit count?&nbsp;\n";
$onchange_action = "onchange=\"yesno_toggle('prev-display_hits', this.value);\"";
$coretable .= "          <select id=\"display_hits\" name=\"display_hits\" ".$onchange_action." style=\"font-family: Tahoma; font-size: 8pt; visibility: visible;\">\n";
$option_values = array('yes', 'no');
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['display_hits'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   $coretable .= "           <option value=\"".$value."\"".$selected.">".ucwords($value)."</option>\n";
}
$coretable .= "          </select>\n";
$coretable .= "         </td>\n";


# display_search_within_results
$coretable .= "         <td align=\"left\" style=\"padding-right: 20px;\" valign=\"bottom\">\n";
$coretable .= "          Display search within results?&nbsp;\n";
$onchange_action = "onchange=\"yesno_toggle('prev-display_search_within_results', this.value);\"";
$coretable .= "          <select id=\"display_search_within_results\" name=\"display_search_within_results\" ".$onchange_action.">\n";
$option_values = array('yes', 'no');
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['display_search_within_results'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   $coretable .= "           <option value=\"".$value."\"".$selected.">".ucwords($value)."</option>\n";
}
$coretable .= "          </select>\n";
$coretable .= "         </td>\n";


# display_search_within_results
$coretable .= "         <td align=\"left\" valign=\"bottom\">\n";
$coretable .= "          Display \"Match Exact Phrase\" check box?&nbsp;\n";
$onchange_action = "onchange=\"yesno_toggle('prev-display_match_exact_phrase', this.value);\"";
$coretable .= "          <select id=\"display_match_exact_phrase\" name=\"display_match_exact_phrase\" ".$onchange_action.">\n";
$option_values = array('yes', 'no');
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['display_match_exact_phrase'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   $coretable .= "           <option value=\"".$value."\"".$selected.">".ucwords($value)."</option>\n";
}
$coretable .= "          </select>\n";
$coretable .= "         </td>\n";



$coretable .= "        </tr>\n";
$coretable .= "       </table>\n";
$coretable .= "      </fieldset>\n";




$coretable .= "      <fieldset>\n";
$coretable .= "      <legend><b>Colors and font styles</b></legend>\n";
$coretable .= "       <table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" style=\"font-family: Tahoma; font-size: 8pt; border: 0;\" class=\"feature_sub\">\n";
$coretable .= "    <tr>\n";
$coretable .= "     <td align=\"left\" valign=\"middle\">\n";
$coretable .= "      <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" style=\"font-family: Tahoma; font-size: 8pt; border: 0;\" class=\"feature_sub\">\n";

#--------------------------------------------------------------------
# style2 (text color)
$coretable .= "       <tr>\n";
$coretable .= "        <td align=\"left\" valign=\"top\" style=\"padding-left: 0px;\">\n";
$coretable .= "         Text color:\n";
$coretable .= "        </td>\n";

$coretable .= "        <td align=\"left\" valign=\"middle\">\n";
$coretable .= "          <select id=\"style2-dd\" class=\"text\" onchange=\"set_cartcss('style2',this.value);preview_cartcss('style2-text', 'color', this.value);preview_cartcss('style2', 'color', this.value);\">\n";
$coretable .= "           <option value=\"\">(template default)</option>\n";
$coretable .= "           ".$color_options;
$coretable .= "           <option value=\"custom\" style=\"font-weight: bold;\">Custom</option>\n";
$coretable .= "          </select>\n\n";
$coretable .= "        </td>\n";

$coretable .= "        <td align=\"left\" valign=\"middle\">\n";
$onblur = "onblur=\"this.value=this.value.toUpperCase();set_cartcss('style2',this.value);preview_cartcss('style2-text', 'color', this.value);preview_cartcss('style2', 'color', this.value);\"";
$coretable .= "      Hex: #\n";
$coretable .= "      <input type=\"text\" name=\"style2\" id=\"style2\" value=\"".$curtmp['style2']."\" ".$onblur." class=\"tfield_hex\" size=\"6\" maxlength=\"6\" style=\"width: 100px;\">\n";
$coretable .= "        </td>\n";
$coretable .= "       </tr>\n";


#--------------------------------------------------------------------
# Page link color
$coretable .= "       <tr>\n";
$coretable .= "        <td align=\"left\" valign=\"top\" style=\"padding-left: 0px;\">\n";
$coretable .= "         Page link color:\n";
$coretable .= "        </td>\n";

# link_color-dd
$coretable .= "        <td align=\"left\" valign=\"middle\">\n";
$coretable .= "         <select id=\"link_color-dd\" class=\"text\" onchange=\"set_cartcss('link_color',this.value);preview_cartcss('prev-link_color', 'color', this.value);preview_cartcss('link_color', 'color', this.value);\">\n";
$coretable .= "           <option value=\"\">(template default)</option>\n";
$coretable .= "          ".$color_options;
$coretable .= "          <option value=\"custom\" style=\"font-weight: bold;\">Custom</option>\n";
$coretable .= "         </select>\n\n";
$coretable .= "        </td>\n";

# link_color
$coretable .= "        <td align=\"left\" valign=\"middle\">\n";
$onblur = "onblur=\"this.value=this.value.toUpperCase();set_cartcss('link_color',this.value);preview_cartcss('prev-link_color', 'color', this.value);preview_cartcss('link_color', 'color', this.value);\"";
$coretable .= "      Hex: #\n";
$coretable .= "      <input type=\"text\" name=\"link_color\" id=\"link_color\" value=\"".$curtmp['link_color']."\" ".$onblur." class=\"tfield_hex\" size=\"6\" maxlength=\"6\" style=\"width: 100px;\">\n";
$coretable .= "        </td>\n";
$coretable .= "       </tr>\n";

#--------------------------------------------------------------------
# Page link hover color
$coretable .= "       <tr>\n";
$coretable .= "        <td align=\"left\" valign=\"middle\" style=\"padding-left: 0px;\">\n";
$coretable .= "         Page link color (hover):\n";
$coretable .= "        </td>\n";

# link_hover-dd
$coretable .= "        <td align=\"left\" valign=\"middle\">\n";
$coretable .= "         <select id=\"link_hover-dd\" class=\"text\" onchange=\"set_cartcss('link_hover',this.value);preview_cartcss('link_hover', 'color', this.value);\">\n";
$coretable .= "           <option value=\"\">(template default)</option>\n";
$coretable .= "          ".$color_options;
$coretable .= "          <option value=\"custom\" style=\"font-weight: bold;\">Custom</option>\n";
$coretable .= "         </select>\n\n";
$coretable .= "        </td>\n";

# link_hover (hex #)
$coretable .= "        <td align=\"left\" valign=\"middle\">\n";
$onblur = "onblur=\"this.value=this.value.toUpperCase();set_cartcss('link_hover',this.value);preview_cartcss('link_hover', 'color', this.value);\"";
$coretable .= "      Hex: #\n";
$coretable .= "      <input type=\"text\" name=\"link_hover\" id=\"link_hover\" value=\"".$curtmp['link_hover']."\" ".$onblur." class=\"tfield_hex\" size=\"6\" maxlength=\"6\" style=\"width: 100px; color: #".$curtmp['link_hover']."\">\n";
$coretable .= "        </td>\n";
$coretable .= "       </tr>\n";

#--------------------------------------------------------------------
# style1
# HiLite color
$coretable .= "       <tr>\n";
$coretable .= "        <td align=\"left\" valign=\"middle\" style=\"padding-left: 0px;\">\n";
$coretable .= "         HiLite color:\n";
$coretable .= "        </td>\n";
$coretable .= "        <td align=\"left\" valign=\"middle\">\n";
$coretable .= "         <select id=\"style1-dd\" class=\"text\" onchange=\"set_cartcss('style1',this.value);preview_cartcss('style1-text', 'color', this.value);preview_cartcss('style1-text2', 'color', this.value);preview_cartcss('style1', 'color', this.value);\">\n";
$coretable .= "           <option value=\"\">(template default)</option>\n";
$coretable .= "          ".$color_options;
$coretable .= "          <option value=\"custom\" style=\"font-weight: bold;\">Custom</option>\n";
$coretable .= "         </select>\n\n";
$coretable .= "        </td>\n";

$coretable .= "        <td align=\"left\" valign=\"middle\">\n";
$onblur = "onblur=\"this.value=this.value.toUpperCase();set_cartcss('style1',this.value);preview_cartcss('style1-text', 'color', this.value);\"";
$coretable .= "      Hex: #\n";
$coretable .= "      <input type=\"text\" name=\"style1\" id=\"style1\" value=\"\" ".$onblur." maxlength=\"6\" size=\"6\" class=\"tfield_hex\" style=\"width: 100px;\">\n";
$coretable .= "        </td>\n";
$coretable .= "       </tr>\n";
$coretable .= "      </table>\n";
$coretable .= "     </td>\n";


/*---------------------------------------------------------------------------------------------------------*
 ___  _        _    _              _
| _ \(_) __ _ | |_ | |_   __  ___ | | _  _  _ __   _ _
|   /| |/ _` || ' \|  _| / _|/ _ \| || || || '  \ | ' \
|_|_\|_|\__, ||_||_|\__| \__|\___/|_| \_,_||_|_|_||_||_|
        |___/
# Start right column
/*---------------------------------------------------------------------------------------------------------*/
$coretable .= "     <td align=\"left\" valign=\"top\">\n";
$coretable .= "      <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" style=\"font-family: Tahoma; font-size: 8pt; border: 0;\" class=\"feature_sub\">\n";

#--------------------------------------------------------------------
# style3 (Font)
$coretable .= "       <tr>\n";
$coretable .= "        <td align=\"left\" valign=\"middle\" style=\"padding-left: 0px;\">\n";
$coretable .= "         Font:&nbsp;\n";
$coretable .= "        </td>\n";

$coretable .= "        <td colspan=\"2\" align=\"left\" valign=\"middle\">\n";
$coretable .= "          <select id=\"style3\" name=\"style3\" onchange=\"preview_cartcss('style4-text', 'fontFamily', this.value);preview_cartcss('style2-text', 'fontSize', this.value);preview_cartcss('style4-text2', 'fontSize', this.value);\">\n";
$coretable .= "           <option value=\"\" style=\"font-family: Tahoma; font-size: 8pt;\"";
$option_values = array('', 'Arial', 'Courier', 'Courier New', 'Tahoma', 'Times New Roman', 'Verdana', 'Trebuchet MS');
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['style3'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   if ( $value == "" ) { $option_text = "Default template font"; } else { $option_text = $value; }
   $coretable .= "        <option value=\"".$value."\" style=\"font-family: ".$value.";\"".$selected.">".$option_text."</option>\n";
}
$coretable .= "          </select>\n";
$coretable .= "        </td>\n";
$coretable .= "       </tr>\n";

# style4 (font size)
$coretable .= "       <tr>\n";
$coretable .= "        <td align=\"left\" valign=\"middle\" style=\"padding-left: 0px;\">\n";
$coretable .= "         Font Size:&nbsp;\n";
$coretable .= "        </td>\n";

$coretable .= "        <td colspan=\"2\" align=\"left\" valign=\"middle\">\n";
$coretable .= "         <select id=\"style4\" name=\"style4\" style=\"width: 65px;font-family: Tahoma; visibility: visible;\" class=\"text\" onchange=\"preview_cartcss('style4-text', 'fontSize', this.value);preview_cartcss('style4-text2', 'fontSize', this.value);\">\n";
$option_values = array(6, 8, 9, 10, 11, 12, 13, 14, 15);
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['style4'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   $coretable .= "       <option value=\"".$value."\" style=\"font-size: ".$value."px;\"".$selected.">".$value."px</option>\n";
}
$coretable .= "         </select>\n";
$coretable .= "        </td>\n";
$coretable .= "       </tr>\n";
$coretable .= "      </table>\n";

$coretable .= "     </td>\n";
$coretable .= "    </tr>\n\n";
$coretable .= "   </table>\n";
$coretable .= "   </fieldset>\n"; // End "Colors and font styles"


/*---------------------------------------------------------------------------------------------------------*
 __  __  _            ___         _    _
|  \/  |(_) ___ __   / _ \  _ __ | |_ (_) ___  _ _   ___
| |\/| || |(_-</ _| | (_) || '_ \|  _|| |/ _ \| ' \ (_-<
|_|  |_||_|/__/\__|  \___/ | .__/ \__||_|\___/|_||_|/__/
                           |_|
# These are the fields that don't affect the preview area
/*---------------------------------------------------------------------------------------------------------*/
$coretable .= "      <fieldset>\n";
$coretable .= "      <legend><b>Misc options</b></legend>\n";
$coretable .= "       <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" style=\"font-family: Tahoma; font-size: 8pt; border: 0;\" class=\"feature_sub\">\n";

# base_sel (template for search page)
$coretable .= "      <tr>\n";
$coretable .= "        <td align=\"left\">Template for Search Page:&nbsp;&nbsp;&nbsp;\n";
$coretable .= "        <select id=\"base_sel\" name=\"site\" style=\"font-family: Tahoma; font-size: 8pt; visibility: visible;\">\n";
$coretable .= "        ".$SELECT_OPTS."\n";
$coretable .= "        </select>\n";
$coretable .= "        <input type=\"hidden\" name=\"newdesign\" value=\"1\">\n";
$coretable .= "       </td>\n";
$coretable .= "      </tr>\n";


# results_per_page
$coretable .= "    <tr>\n";
$coretable .= "     <td align=\"left\" valign=\"bottom\">\n";
$coretable .= "      Default number of search results to display per page:&nbsp;\n";
$coretable .= "      <select id=\"results_per_page\" name=\"results_per_page\" style=\"font-family: Tahoma; font-size: 8pt; visibility: visible;\">\n";
$option_values = array(10, 15, 25, 50);
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['results_per_page'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   $coretable .= "       <option value=\"".$value."\"".$selected.">".$value."</option>\n";
}
$coretable .= "      </select>\n";
$coretable .= "     </td>\n";
$coretable .= "    </tr>\n";


# min_word_chars
$coretable .= "          <tr>\n";
$coretable .= "           <td align=\"left\" valign=\"bottom\">\n";
$coretable .= "            Minimum number of characters in search word:&nbsp;\n";
$coretable .= "            <select id=\"min_word_chars\" name=\"min_word_chars\" style=\"font-family: Tahoma; font-size: 8pt; visibility: visible;\">\n";
$option_values = array(2, 3, 4, 5, 6);
foreach ( $option_values as $key=>$value ) {
   if ( $curtmp['min_word_chars'] == $value ) { $selected = " selected"; } else { $selected = ""; }
   $coretable .= "          <option value=\"".$value."\"".$selected.">".$value."</option>\n";
}
$coretable .= "            </select>\n";
$coretable .= "           </td>\n";
$coretable .= "          </tr>\n";

$coretable .= "     </table>\n";

$coretable .= "     </fieldset>\n";


$coretable .= "       </td>\n";
$coretable .= "      </tr>\n";

$coretable .= "      <tr>\n";
$coretable .= "       <td align=\"center\">\n";
$coretable .= "        <button onClick=\"document.displaysettings.submit();\" type=\"button\" class=\"greenButton\"><span><span>Save Changes</span></span></button>\n";
$coretable .= "        </form>\n";
$coretable .= "       </td>\n";
$coretable .= "      </tr>\n";

$coretable .= "        </table>\n";

$coretable .= "         </td>\n";
$coretable .= "      </tr>\n";
# [Save Changes] button

$coretable .= "   </table>\n";


$coretable .= "</div>\n";




////////




echo $coretable;


echo "<SCRIPT LANGUAGE=\"Javascript\">\n\n";
echo "set_cartcss('style1', '".$curtmp['style1']."');\n";
echo "set_cartcss('style2', '".$curtmp['style2']."');\n";

echo "set_cartcss('link_color', '".$curtmp['link_color']."');\n";
echo "set_cartcss('link_hover', '".$curtmp['link_hover']."');\n";

echo "document.getElementById('style4').value = '".$curtmp['style4']."';\n";
echo "document.getElementById('base_sel').value = '".$curtmp['template']."';\n";

# Apply settings to preview area on load
# link_hover
echo "preview_cartcss('link_hover', 'color', '".$curtmp['link_hover']."');\n";

# style1 - HiLite color
echo "preview_cartcss('style1-text', 'color', '".$curtmp['style1']."');\n";
echo "preview_cartcss('style1-text2', 'color', '".$curtmp['style1']."');\n";
echo "preview_cartcss('style1', 'color', '".$curtmp['style1']."');\n";

# style2 - text color
echo "preview_cartcss('style2-text', 'color', '".$curtmp['style2']."');\n";
echo "preview_cartcss('style2', 'color', '".$curtmp['style2']."');\n";

# link_color
echo "preview_cartcss('prev-link_color', 'color', '".$curtmp['link_color']."');\n";
echo "preview_cartcss('link_color', 'color', '".$curtmp['link_color']."');\n";



# Font
echo "preview_cartcss('style4-text', 'fontFamily', '".$curtmp['style3']."');\n";

# Font size
echo "preview_cartcss('style2-text', 'fontSize', '".$curtmp['style4']."');\n";
//echo "preview_cartcss('style4-text', 'fontSize', '".$curtmp['style4']."');\n";
//echo "preview_cartcss('style4-text2', 'fontSize', '".$curtmp['style4']."');\n";

echo "</SCRIPT>\n\n";



# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Set up how you want your search results page to look.");

$module = new smt_module($module_html);
$module->meta_title = "Search Display Settings";
$module->add_breadcrumb_link("Search Display Settings", "program/modules/super_search/search_display_settings.php");
$module->icon_img = "program/modules/super_search/plugin_icon-supersearch.gif";
$module->heading_text = "Search Display Settings";
$module->description_text = $instructions;
$module->good_to_go();
?>