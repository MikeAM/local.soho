<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
require_once('includes/product_gui.php');
/*************************************************************************************************
 ___                     _   ___   _        _         ___
/ __| _ __  ___  ___  __| | |   \ (_) __ _ | |  ___  | _ \ __ _  __ _  ___  ___
\__ \| '_ \/ -_)/ -_)/ _` | | |) || |/ _` || | |___| |  _// _` |/ _` |/ -_)(_-<
|___/| .__/\___|\___|\__,_| |___/ |_|\__,_||_|       |_|  \__,_|\__, |\___|/__/
     |_|                                                        |___/

# Included at the bottom of main_menu.php
/*************************************************************************************************/

/// Build page arrays (based on menu status) for jump menus
###==================================================================================
$CUR_USER_ACCESS=$_SESSION['CUR_USER_ACCESS'];
// Loop all and split into diff arrays by menu status
$pgrez = mysql_query("SELECT prikey, page_name, url_name, type, custom_menu, sub_pages, sub_page_of, password, main_menu, link, username, splash, bgcolor, title, description, template FROM site_pages ORDER BY page_name asc");
while ( $getPg = mysql_fetch_array($pgrez) ) {
	// Main menu pages
	if(!preg_match('/^http:/i', $getPg['link'])){
   
		$TMP_CHK = eregi_replace(" ", "_", $getPg['page_name']);
		if ($CUR_USER_ACCESS == "WEBMASTER" || strpos($CUR_USER_ACCESS, ";".$TMP_CHK.";") !== false || eregi(";MOD_ALLPAGES;", $CUR_USER_ACCESS) ) { // Admin is authorized, build page row now
	   
			if ( $getPg['main_menu'] > 0 ) {
			   $main_pgz[] = $getPg['page_name'];
			
			// Sub-menu pages
			} elseif ( strlen($getPg['sub_page_of']) > 4 ) {
			   $tmppg = split("~~~", $getPg['sub_page_of']);
			   $sub_pgz[$tmppg[0]][] = array( sort=>$tmppg[1], name=>$getPg['page_name'] );
			
			// Off-menu pages
			} else {
			   $other_pgz[] = $getPg['page_name'];
			}
		}
	}
}

if ($CUR_USER_ACCESS == 'WEBMASTER') {
   // Loop main menu page array to build jump options
   //-------------------------------------------------------
   $dd_menpgz = "      <select name=\"jump_menupg\" style=\"width: 200px;\" onchange=\"window.document.quickgo_pg.submit();\">\n";
   $dd_menpgz .= "       <option value=\"\">[".lang("On-Menu Pages")."]</option>\n";
   foreach ( $main_pgz as $key=>$mp ) {
      $dd_menpgz .= "       <option value=\"".$mp."\">".$mp."</option>\n";

      // Pull sub-pages for this page
      foreach ( $sub_pgz[$mp] as $sp ) {
         $dd_menpgz .= "       <option value=\"".$sp[name]."\">&gt;&gt; ".$sp[name]."</option>\n";
      }
   }
   $dd_menpgz .= "      </select>\n";

   // Create 'all other pages' jump menu
   //--------------------------------------------------------
   $dd_regpgz = "      <select name=\"jump_regpg\" style=\"width: 200px;\" onchange=\"window.document.quickgo_pg.submit();\">\n";
   $dd_regpgz .= "       <option value=\"\">[".lang("Off-Menu Pages")."]</option>\n";

   foreach ( $other_pgz as $key=>$op ) {
      $dd_regpgz .= "       <option value=\"".$op."\">".$op."</option>\n";
   }

   $dd_regpgz .= "      </select>\n";


   /// Begin Speed Dial Menu table and form
   ###==================================================================================
   echo "   <form name=\"quickgo_pg\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\" style=\"margin-top: 0; margin-bottom: 5px;\">\n";
   echo "   <input type=\"hidden\" name=\"devdo\" value=\"pgjump\">\n";
   echo "    <table border=\"0\" cellpadding=\"4\" cellspacing=\"0\" width=\"700\" class=\"feature_sub\" id=\"speeddialgo\" align=\"center\">\n";

   ## 'Developer Speed-Dial Menu'
//   echo "     <tr>\n";
//   echo "      <td width=\"100%\" colspan=\"3\" align=\"left\" valign=\"top\" class=\"fgroup_title\" style=\"border-bottom: 1px solid #336699;\">\n";
//   echo "       ".lang("Speed-Dial Pages Menu")."\n";
//   echo "      </td>\n";
//   echo "     </tr>\n";

   echo "     <tr>\n";

   ## 'Open Page'
   echo "      <td width=\"15%\" align=\"right\" style=\"padding: 10px;\">\n";
   echo "       <font style=\"color: #6699CC;\">".lang("Open Page").":</font>\n";
   echo "      </td>\n";

   ## Display menu page drop-down
   ##===============================================
   echo "      <td align=\"left\" valign=\"top\" style=\"padding-top: 6px; padding-left: 0px;\">\n";
   echo "       ".$dd_menpgz."\n";
   echo "      </td>\n";

   ## Display 'other' page drop-down
   ##===============================================
   echo "      <td align=\"left\" valign=\"top\" style=\"padding-top: 6px; padding-left: 0px;\">\n";
   echo "       ".$dd_regpgz."\n";
   echo "      </td>\n";

   ## [ JUMP ] Button
   /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*
   echo "  <td align=\"center\">\n";
   echo "   <input type=\"submit\" value=\"JUMP\" ".$btn_edit."\">\n";
   echo "  </td>\n";
   /*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

   echo "     </tr>\n";
   echo "   </table>\n";
   echo "  </form>\n";

} // End if user has webmaster rights
?>