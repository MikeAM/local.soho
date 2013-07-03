<?php
error_reporting(E_PARSE);
session_start();
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

if(!require_once('../../includes/product_gui.php')) {
  exit;
}


ini_set("max_execution_time", "300000");
ini_set("default_socket_timeout", "555");
ini_set("max_post_size", "100M");

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


if(!table_exists("search_contents")){
	$mquery = "CREATE TABLE search_contents (prikey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, page_name VARCHAR(255), include_in_search VARCHAR(255), page_contents BLOB)"; // EDIT HERE and specify your table and field names for the SQL query
	if (!mysql_query($mquery)) {
	  echo mysql_error();
	  exit;
	}
}


ob_start();

if ($_POST['action'] != "update") {
$curtmp = mysql_query("select * from search_core where prikey='1'");
$curtmp = mysql_fetch_array($curtmp);
?>
<HEAD>

<SCRIPT LANGUAGE="JavaScript">
function all_servers(check_uncheck) {
   // Loop through server checkboxes
   var server_list = document.getElementById('list');
   var server_checkboxes = server_list.getElementsByTagName('input');

   for ( var i=0; i < server_checkboxes.length; i++ ) {
      if ( check_uncheck == "check" ) {
         server_checkboxes[i].checked = true;

      } else {
         server_checkboxes[i].checked = false;
      }

   } // End for loop through checkboxes

}



var checkflag = "false";
function check(field) {
if (checkflag == "false") {
for (i = 0; i < field.length; i++) {
field[i].checked = true;}
checkflag = "true";
return "Unselect All"; }
else {
for (i = 0; i < field.length; i++) {
field[i].checked = false; }
checkflag = "false";
return "Select All"; }
}

</script>

</script>
</HEAD>


<script type="text/javascript" src="http://<? echo $_SESSION['docroot_url']; ?>/sohoadmin/program/includes/display_elements/js_functions.php"></script>

<link rel="stylesheet" type="text/css" href="module.css">

<div id="Layerzz1" style="position:absolute; left:0px; width:100%;border: 0px solid #000000; visibility: visible; overflow: auto;">
 <table border="0" cellpadding="0" cellspacing="0" width="95%" style="margin-top: 10px;" align="center">
  <tr>
   <td align=center valign=top>
<?
$coretable .= "    <form name=\"ctemplate\" method=\"post\" action=\"what_gets_searched.php\">\n";
$coretable .= "    <input type=\"hidden\" name=\"action\" value=\"update\">\n";
$coretable .= "    <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" width=\"100%\" bgcolor=\"white\" class=\"feature_sub\" align=\"center\">\n";
$coretable .= "      <tr>\n";
//$coretable .= "       <td valign=\"top\" class=\"nopad\">\n";
//$coretable .= "        <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\" class=\"feature_module_heading\">\n";
//$coretable .= "         <tr>\n";
//$coretable .= "          <td width=\"100%\" colspan=\"3\" class=\"fgroup_title\"><a href=\"".$_SERVER['PHP_SELF']."\" class=\"white noline\">SuperSearch Management Module</a></td>\n";
//$coretable .= "         <!---icon logo and heading text-->\n";
//$coretable .= "         </tr>\n";

//$coretable .= "          <td width=\"9%\" align=\"center\"><a href=\"".$_SERVER['PHP_SELF']."\"><img src=\"supersearch-d9_bg.gif\" border=\"0\"></a></td>\n";
//$coretable .= "          <td><h1>What gets searched</h1>\n";
//$coretable .= "           <p></p>\n";
//$coretable .= "          </td>\n";
//$coretable .= "         <tr>\n";
# Creates $link_bar var that contains html for section links
include("link_bar.php");
$coretable .= $link_bar;
//$coretable .= "         </tr>\n";
//$coretable .= "        </table>\n";
//$coretable .= "       </td>\n";
$coretable .= "      </tr>\n";
echo $coretable;
?>
     <tr>
      <td>
<?
echo "<table id=list style='font-family: Tahoma; font-size: 8pt;'>";
$query = "SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages order by page_name ASC"; // EDIT HERE and specify your table and field names for the SQL query
$numresults=mysql_query($query);
$numrows=mysql_num_rows($numresults);
if($_GET['update'] == 'yes') {
	echo "<tr>\n";
	echo "<td colspan=4>\n";
	echo "<font color=red>Page content was successfully databased!<br><br></font>";
	echo "</td>\n";
} else {
	echo "<tr>\n";
	echo "<td colspan=4>\n";
	echo "&nbsp;";
	echo "</td>\n";
}
$x=0;
echo "<tr><td><button class=\"grayButton\" type=button onClick=\"$(this).children().children().html(check(this.form.list));\"><span><span>Select All</span></span></button><br></td>\n";

while ($row = mysql_fetch_array($numresults)) {
	if(!preg_match('/^cartid:/', $row['page_name'])){
		$x++;		
		$title = $row["page_name"];
		$this_page = eregi_replace(' ', '_', $title);
		$num_results2 = mysql_query("select include_in_search from search_contents where page_name = '$title'");
		$search_include = mysql_fetch_array($num_results2);
		
		if ($search_include['include_in_search'] == 'hide') {
		  $checks = '';
		} else {
		  $checks = ' checked';
		}
		$url = "index.php?pr=$this_page";
		if ($check_uncheck == "1") { $CHECKEDit = " CHECKED"; } else { $CHECKEDit = "";  }
		echo "<input type=\"hidden\" NAME=\"".$this_page."\" value=\"hide\">";
		if ($title != 'Search') {
			if (($x % 5) == 1)  {
				echo "</tr><tr><td><INPUT TYPE=\"checkbox\" id=\"list\" NAME=\"".$this_page."\" value=\"include\"".$checks."> ".$title."&nbsp;&nbsp;</td>\n";
			} else {
				echo "<td><INPUT TYPE=\"checkbox\" id=\"list\" NAME=\"".$this_page."\"  value=\"include\"".$checks."> ".$title."</td>\n";
			}	
		}
	} else {
		$numrows = $numrows - 1;
	}
}

echo "</table>";

	?>
				</tr>
				</td>
			  <tr>
				<td align="left" valign="top">
					<br>
    			<INPUT type="radio" name="allow_template_search" value="hide"<? if ($curtmp['allow_template_search'] != "include" ) { echo " checked"; } ?>> Do not include each page's template when searching.  Only Include each page's content when searching.<BR>
					<INPUT type="radio" name="allow_template_search" value="include"<? if ($curtmp['allow_template_search'] == "include") { echo " checked"; } ?>> Include each page's template when searching.<BR>
					</td>
				</tr>
				<tr>
             <td align="center">
          	<button onClick="document.ctemplate.submit();" type="button" name="Submit" class="greenButton"><span><span>Update Search Index Now</span></span></button>
              </form>
             </td>
				</tr>
		  </table>
			</td>
		</tr>
	</table>
</div>
<?php
}

if ($_POST['action'] == "update") {
	
	if(!mysql_query("update search_core set allow_template_search='$allow_template_search' where prikey='1'")) {
	  echo mysql_error(); exit;
	}
	
	if(!table_exists("search_contents")){
	  $mquery = "CREATE TABLE search_contents (prikey INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT, page_name VARCHAR(255), include_in_search VARCHAR(255), page_contents BLOB)"; // EDIT HERE and specify your table and field names for the SQL query
	  if (!mysql_query($mquery)) {
	    echo mysql_error();
	  }
	}
	
	$query = "SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages order by page_name ASC"; // EDIT HERE and specify your table and field names for the SQL query
	$numresults=mysql_query($query);
	$numrows=mysql_num_rows($numresults);
	$tabledisplay = "<html>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">\n<body bgcolor=\"blue\">\n</head>\n";
//	$tabledisplay .= "<script type=\"text/javascript\" src=\"../../program/includes/display_elements/window/prototype.js\"></script>\n";
	$tabledisplay .= "<script type=\"text/javascript\" src=\"../../program/includes/display_elements/window/window.js\"></script>\n";
	$tabledisplay .= "<script type=\"text/javascript\" src=\"../../program/includes/display_elements/window/effects.js\"></script>\n";
	$tabledisplay .= "<link href=\"../../program/includes/display_elements/window/default.css\" rel=\"stylesheet\" type=\"text/css\"></link>\n";
	$tabledisplay .= "<link href=\"../../program/includes/display_elements/window/alert_lite.css\" rel=\"stylesheet\" type=\"text/css\"></link>\n";
	$tabledisplay .= "<script language=\"javascript\">\n";
//	$tabledisplay .= "function openInfoDialog() {\n";
//	$tabledisplay .= " Dialog.info(\"$title<br>Updating...\", {windowParameters: {className: \"alert_lite\",width:250, height:100}, showProgress: true});\n";
//	$tabledisplay .= "}\n";
//	$tabledisplay .= "   openInfoDialog()\n";
	echo $tabledisplay .= "</script>\n";
	$curtmp = mysql_query("select allow_template_search from search_core where prikey='1'");
	$curtmp = mysql_fetch_array($curtmp);
	$CUR_TEMPLATE = $curtmp['allow_template_search'];
	
	if($CUR_TEMPLATE == 'include') {
		$nft= '';
	} else {
		$nft = '&nft=blank_template';
	}
	
	while ($row = mysql_fetch_array($numresults)) {
		if(!preg_match('/^cartid:/', $row['page_name'])){
			$title = $row["page_name"];
			$this_page = eregi_replace(' ', '_', $title);
			$include_in_search = $_POST["$this_page"];
			$query2 = "select * from search_contents where page_name='$title' "; // EDIT HERE and specify your table and field names for the SQL query
			$numresults2=mysql_query($query2);
			$contentzz = mysql_fetch_array($numresults2);
			if ($title == 'Search') { $include_in_search='hide'; }    
			if ($include_in_search=="include") {		
				$url = "index.php?pr=$this_page".$nft;      
				$url2 = "http://".$_SESSION['this_ip']."/index.php?pr=$this_page";
				$tags = '';			
				$securegroup = '';
				
				if ($row["username"] != '' ) {
					echo $securegroup = $row["username"];
					mysql_query("update site_pages set username='' where page_name='".$title."'");
				}
				$tags = get_meta_tags($url2);
				if ($tags['resource-type'] != '') {         
					
					$pagecontent = include_r("http://".$_SESSION['this_ip']."/".$url);				
	
					$pagecontent = preg_replace('/\"<</', '\"', $pagecontent);
					$pagecontent = preg_replace('/>>\"/', '\"', $pagecontent);
					$pagecontent = preg_replace('/<style[^\e]*?<\/style>/i', '', $pagecontent);			
					$pagecontent = preg_replace('/<script[^\e]*?><\/script>/i', '', $pagecontent);
					$pagecontent = preg_replace('/<noscript>[^\e]*?<\/noscript>/i', '', $pagecontent);			
					$pagecontent = preg_replace('/<!--[^\e]*?-->/', '', $pagecontent);
					$pagecontent = preg_replace('/<[^\e]*?>/', '', $pagecontent);
					$pagecontent = eregi_replace('&nbsp;', ' ', $pagecontent);
					$pagecontent = preg_replace("/(\n\s\n)+/", "\n", $pagecontent);
					$pagecontent = preg_replace("/(\n\n)+/", "\n", $pagecontent);
					$pagecontent = preg_replace("/(\n\s\n)+/", "\n", $pagecontent);
					$pagecontent = preg_replace("/(\n\s\n)+/", "\n", $pagecontent);
					$pagecontent = preg_replace("/(\r\n)+/", "\n", $pagecontent);
					$pagecontent = preg_replace("/\n(\s*)\n/", "\n", $pagecontent); 
					$pagecontent = mysql_real_escape_string($pagecontent);
					
					if ($securegroup != '' ) {
						$securegroup = $row["username"];
						mysql_query("update site_pages set username='$securegroup' where page_name='$title'");
						$securegroup = '';
					}
				}
			}
			$title = mysql_real_escape_string($title);
			if (!$contentzz['page_name'] == $title) {
				$query3 = "'','$title', '$include_in_search', '$pagecontent'";
				if(!mysql_query("INSERT INTO search_contents VALUES(".$query3.")")){
					echo mysql_error();exit;
				}
			} else {
				if(!mysql_query("update search_contents set include_in_search='$include_in_search', page_contents='$pagecontent' where page_name='$title'")){
					echo mysql_error(); exit;
				}
			}
			
			echo "<script language=\"javascript\">\n";
			if(!isset($isFirst)){
				//echo "   document.getElementById('modal_dialog_progress').innerHTML += '<div id=\'modal_dialog_scroll\' style=\'margin-top: 25px; overflow: hidden; height: 25px;\'>&nbsp;</div>'\n";
				$isFirst = 1;
			}
			//echo "   document.getElementById('modal_dialog_scroll').innerHTML += '".$title."<br/>'\n";
			//echo "   var cur_pos = document.getElementById('modal_dialog_scroll').scrollTop;\n";
			echo "   var cur_pos = Number(cur_pos);\n";
			echo "   var posi = (cur_pos+200);\n";
			//echo "   document.getElementById('modal_dialog_scroll').scrollTop= posi;\n";
			echo "</script>\n";
			$pagecontent = '';
		} else {
			$numrows=$numrows-1;
		}
	}
	$tabledisplay = "       </td>\n";
	$tabledisplay .= "    </tr>\n";
	$tabledisplay .= "  </table>\n";
	$tabledisplay .= "</div>\n";
	$tabledisplay .= "<script language=\"javascript\">\n";
	//$tabledisplay .= " Dialog.closeInfo()\n";
	$tabledisplay .= " window.location='".$_SERVER['PHP_SELF']."?update=yes'\n";
	$tabledisplay .= "</script>\n";
	$tabledisplay .= "</html>\n";
	
	echo $tabledisplay;
}




# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Select which pages that you want to be included in the search feature.  Pages that are not checked will not be included searches.");

$module = new smt_module($module_html);
$module->meta_title = "What Gets Searched";
$module->add_breadcrumb_link("What Gets Searched", "program/modules/super_search/what_gets_searched.php");
//$module->add_breadcrumb_link("Create Campaign", "program/modules/mods_full/enewsletter/create_campaign.php");
$module->icon_img = "program/modules/super_search/plugin_icon-supersearch.gif";
$module->heading_text = "What Gets Searched";
$module->description_text = $instructions;
$module->good_to_go();

?>