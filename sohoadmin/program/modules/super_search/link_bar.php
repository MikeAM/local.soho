<?
#===============================================================================================================
# SuperSearch plugin
# Links to sections within SuperSearch management module
# Include wherever you want to show the links
#===============================================================================================================

# Links | to | other | sections
$section_links = array("Display Settings" => "search_display_settings.php", "What gets searched" => "what_gets_searched.php", "Statistics" => "search_statistics.php");
$link_bar = "          <td width=\"38%\" style=\"padding-right: 5px;background:#E3E2E2;text-align: left;\">\n";
foreach ( $section_links as $text=>$link ) {
   if ( basename($_SERVER['PHP_SELF']) == $link ) {
      $link_bar .= "           <b><a href=\"".$link."\">".$text."</a></b> |\n";
   } else {
      $link_bar .= "           <a href=\"".$link."\">".$text."</a> |\n";
   }
}
$link_bar = substr($link_bar, 0, -4);
$link_bar .= "          </td>\n";
?>