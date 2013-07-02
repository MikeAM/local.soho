<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

#=====================================================================================
# Soholaunch(R) Site Management Tool
#
# Author:        Mike Morrison
# Homepage:      http://www.soholaunch.com
# Release Notes: http://wiki.soholaunch.com
#
# Script notes
# >> Allows user to swap out certain images in passed template for their own
# >> Depends on $_GET['templatefolder'] being passed from link in template manager
#=====================================================================================


error_reporting(E_PARSE);
session_start();

//# Include core files
//include_once($_SESSION['docroot_path']."/sohoadmin/program/includes/product_gui.php");
//include_once($_SESSION['docroot_path']."/sohoadmin/program/includes/smt_module.class.php");
//
//# Template-related functions
//include_once($_SESSION['docroot_path']."/sohoadmin/program/modules/site_templates/template_functions.inc.php");
require_once('../../includes/product_gui.php');
require_once('../../includes/smt_module.class.php');
require_once('../../modules/site_templates/template_functions.inc.php');

# CHRIS_MOD
if (isset($_GET['currentPageList']))
{
	if ($_GET['currentPageList'] != $_SESSION['currentPageList'])
	{
		$_SESSION['currentPageList'] = $_GET['currentPageList'];
	}
}
if (isset($_SESSION['currentPageList']))
{
	$pgList = $_SESSION['currentPageList'];
}
if ($pgList == "")
{
	unset($pgList);
	unset($_SESSION['currentPageList']);
	unset($_GET['currentPageList']);
}

$qry = "SELECT page FROM smt_userimages WHERE page = '".$pgList."'";
$test = mysql_query($qry);
$testResult = mysql_result($test, 0);
# END CHRIS_MOD
# Report message coming from edit screen?
if ( $_GET['code'] == "editdone" ) {
   $report[] = "Image setting saved!";
}


# So you can write straight HTML without having to build every line into a container var (i.e. $disHTML .= "another line of html")
ob_start();


/*---------------------------------------------------------------------------------------------------------*
 _   _                 ___
| | | | ___ ___  _ _  |_ _| _ __   __ _  __ _  ___  ___
| |_| |(_-</ -_)| '_|  | | | '  \ / _` |/ _` |/ -_)(_-<
 \___/ /__/\___||_|   |___||_|_|_|\__,_|\__, |\___|/__/
                                        |___/

/*---------------------------------------------------------------------------------------------------------*/

# Build site image file options for logo/userimage dropdowns
$directory = "$doc_root/images";
$handle = opendir("$directory");
 while ($files = readdir($handle)) {
    if (strlen($files) > 2) {
       if ( $files == $df_logo ) {
          $imagefile_options .= "    <option value=\"$files\" selected>$files</option>\n";
       } else {
          $imagefile_options .= "    <option value=\"$files\">$files</option>\n";
       }
    }
 }
closedir($handle);
?>
<style>
#involved_templates {
   position: relative;
}
h2 {
   font-size: 13px;
}
h3 {
   font-size: 11px;
   margin: 0;
}
#involved_templates h6 {
   margin: 0;
   font-size: 10px;
}

#working_tpl-container {
   float: left;
   padding: 5px;
   /*border: 1px solid #ccc;*/
}

#working_tpl-container img {
   float: left;
   width: 100px;
   height: 69px;
}
#working_tpl-caption {
   float: left;
}

#userimg_form {
   clear: both;
}

#choose_other-container {
   position: absolute;
   top: -10px;
   right: -10px;
   width: 330px;
   height: 150px;
   overflow: auto;
   margin-left: 15px;
   padding: 5px 5px 0 0;
   border: 1px solid #ccc;
   border-top: none;
   text-align: right;
}

.choose_other-template {
   float: right;
   width: 90px;
   margin: 3px;
   padding: 2px;
   border: 1px dashed #ccc;
   text-align: center;
   opacity: .5;
   cursor: pointer;
}

.choose_other-template img {
   /*float: left;*/
}
p.choose_other-caption {
   /*float: left;*/
   font-size: 90%;
}

.floatbdrfix {
   margin: 0;
   clear: both;
}

div.userimage-container {
   position: relative;
   padding: 5px 2px;
   margin-bottom: 25px;
   border: 1px dotted #ccc;
}

/* Box that wraps around the <img> tag to allow for overflow: auto; */
div.userimage-img {
   /*width: 700px;*/
   /*overflow: auto;*/
}

p.userimage-caption {
/*   position: absolute;
   bottom: 0px;
   right: 0;*/
   margin: 0 0 3px;
}

div.userimage-option_box {
/*   position: absolute;
   right: 5px;
   bottom: 10px;*/
   /*background-color: #336699;*/
   color: #fff;
   /*padding: 15px;*/
   margin: 5px;
}

.custPgs {
	font-weight: bold;
}

</style>

<!--- CHRIS_MOD --->
<script type="text/javascript">
	$(document).ready( function() {
	
	});

	function GetSelectedItem()
	{
		len = document.getElementById('pageListDrop').length;
		i=0;
		chosen="none";
		for (i=0;i<len;i++)
		{
			if (document.getElementById('pageListDrop')[i].selected)
			{
				chosen = document.getElementById('pageListDrop')[i].value;
			}
		}
		//alert(chosen);
		window.location.href="template_images.php?<?php echo preg_replace("/&currentPageList=(.*)/", "", $_SERVER['QUERY_STRING']); ?>&currentPageList=" + chosen;
	}
</script>
<!--- END CHRIS_MOD --->

<div id="involved_templates">


 <div id="working_tpl-container">
  <h1>Template</h1>

<?php
echo "<img src=\"http://".$tpl_base_url."/".$_GET['templatefolder']."/screenshot.jpg\">\n";
?>


  
  <br/><div id="working_tpl-caption">
<?php
# Template name
echo "   ".format_templatename($_GET['templatefolder']);
?>
  </div>
    <div class="floatbdrfix">&nbsp;</div>
 </div>




<?php
# Get available layouts used by this template
$layouts_used = layouts_used($_GET['templatefolder']);


/*---------------------------------------------------------------------------------------------------------*
  ___   _    _                _____                   _        _
 / _ \ | |_ | |_   ___  _ _  |_   _|___  _ __   _ __ | | __ _ | |_  ___  ___
| (_) ||  _|| ' \ / -_)| '_|   | | / -_)| '  \ | '_ \| |/ _` ||  _|/ -_)(_-<
 \___/  \__||_||_|\___||_|     |_| \___||_|_|_|| .__/|_|\__,_| \__|\___|/__/
                                               |_|

# Show links to other templates with userimgs in them?
/*---------------------------------------------------------------------------------------------------------*/
$other_templates = inuse_templates("_userimg");
//unset($other_templates[$_GET['templatefolder']]);

if ( count($other_templates) > 0 ) {
   echo " <div id=\"choose_other-container\">\n";
   echo "  <h6>Assigned templates with swap-able images...</h6>\n";

   foreach ( $other_templates as $key=>$template ) {
      # Special mouseover-impervious style for working template
      if ( $template == $_GET['templatefolder'] ) { $wrkingstyle = " style=\"background: #ffff61;opacity: 1;\""; } else { $wrkingstyle = ""; }
      $onevents = " onmouseover=\"setClass(this.id, 'choose_other-template bg_yellow');\"";
      $onevents .= " onmouseout=\"setClass(this.id, 'choose_other-template');\"";
      $onevents .= " onclick=\"document.location.href='".$_SERVER['PHP_SELF']."?templatefolder=".$template."';\"";

      echo "  <div id=\"other_tpl-".$key."\" class=\"choose_other-template\"".$onevents."".$wrkingstyle.">\n";
      echo "   <img src=\"http://".$tpl_base_url."/".$template."/screenshot.jpg\" width=\"75\">\n";
      echo "   <p class=\"choose_other-caption\">".format_templatename($template)."</p>\n";
      echo "  </div>\n";
   }
   echo "  <div class=\"floatbdrfix\">&nbsp;</div>\n";
   echo " </div>\n";
}
echo "</div>\n"; // End involved_templates


/*---------------------------------------------------------------------------------------------------------*
 _   _                 ___
| | | | ___ ___  _ _  |_ _| _ __   __ _  __ _  ___  ___
| |_| |(_-</ -_)| '_|  | | | '  \ / _` |/ _` |/ -_)(_-<
 \___/ /__/\___||_|   |___||_|_|_|\__,_|\__, |\___|/__/
                                        |___/
# Begin user img form for this template
/*---------------------------------------------------------------------------------------------------------*/
echo "<div id=\"userimg_form\">\n";
echo " <form action=\"site_templates.php\" method=\"POST\" name=\"userimgform\">\n";
echo " <input type=\"hidden\" name=\"todo\" value=\"save_userimg\">\n";
echo " <input type=\"hidden\" name=\"showTab\" value=\"tab2\">\n";

# Just for this template...save some keystrokes
$basesrc_orig = "http://".$tpl_base_url."/".$_GET['templatefolder'];
$basesrc_user = "http://".$_SESSION['docroot_url']."/images";
$basepath_orig = $tpl_base_path."/".$_GET['templatefolder'];




# CHRIS_MOD - Add page column to smt_userimages
if (!mysql_query("SELECT COUNT(*) FROM smt_userimages GROUP BY page"))
{
	$alterQry = "ALTER TABLE smt_userimages ADD COLUMN page VARCHAR(255)";
	if (!mysql_query($alterQry))
	{
		die("Error: ".mysql_error());
	}
}

# Add list of pages here!
$qry = "SELECT page_name, link FROM site_pages;";
$rez = mysql_query($qry);
while ($result = mysql_fetch_assoc($rez))
{
	$pageList[] = $result;
}
$qry = "SELECT page FROM smt_userimages WHERE page != ''";
$rez = mysql_query($qry);
while ($result = mysql_fetch_row($rez)) {
	$customPages[] = $result[0];
}

unset($qry);
unset($rez);
unset($result);

$PLDROPDOWN = "<div id=\"pageList\">";
$PLDROPDOWN .= "	<h2>Your Site Pages</h2>";
$PLDROPDOWN .= "	<select id=\"pageListDrop\" onChange=\"GetSelectedItem()\">";
$PLDROPDOWN .= "		<option value=\"\" selected>All Pages</option>";

for ($i = 0;$i < count($pageList); $i++)
{
	if (preg_match("/^(http|https|ftp|ftps)/", $pageList[$i]['link'])) {
		continue;
	}
	if ($_SESSION['currentPageList'] == $pageList[$i]['link'])
	{
		if (in_array($pageList[$i]['link'], $customPages))
		{
			$PLDROPDOWN .= "		<option class=\"custPgs\" value=\"".$pageList[$i]['link']."\" selected>".$pageList[$i]['page_name']."</option>";
		} else {
			$PLDROPDOWN .= "		<option  value=\"".$pageList[$i]['link']."\" selected>".$pageList[$i]['page_name']."</option>";
		}
		$selectedPage = $pageList[$i]['page_name'];
	}
	else
	{
		if (in_array($pageList[$i]['link'], $customPages))
		{
			$PLDROPDOWN .= "		<option class=\"custPgs\" value=\"".$pageList[$i]['link']."\">".$pageList[$i]['page_name']."</option>";
		} else {
			$PLDROPDOWN .= "		<option  value=\"".$pageList[$i]['link']."\">".$pageList[$i]['page_name']."</option>";
		}
	}
}
$PLDROPDOWN .= "	</select>";
$PLDROPDOWN .= "</div>";
echo $PLDROPDOWN;
# END CHRIS_MOD

//echo testArray($layout_names);

foreach ( $layouts_used as $key=>$htmlfile ) {
   # Read html from this layout file
   $rawhtml = file_get_contents($tpl_base_path."/".$_GET['templatefolder']."/".$htmlfile);

   # _userimg found in this layout html?
   if ( eregi("(['".'"'."])?([a-zA-Z0-9_ /-]*)_userimg-([a-zA-Z0-9_ -]+)\.(gif|jpg|png)", $rawhtml) ) {

      echo " <a name=\"".str_replace(".html", "_layout", $htmlfile)."\"></a>\n";
      
	if ($pgList != ""){
		echo " <h2>".str_replace(' site page layout', '', $layout_names[$htmlfile])." image for page: ".$selectedPage."</h2>";
	} else {
		echo " <h2>".str_replace(' site page layout', '', $layout_names[$htmlfile])." image </h2>";
	}
      
      //echo " <h2>".$layout_names[$htmlfile]." <span class=\"unbold\">(".$htmlfile.")</span></h2>";
      preg_match_all("/([a-zA-Z0-9_ \/-]*)_userimg-([a-zA-Z0-9_ -]+)\.(gif|jpg|png)/", $rawhtml, $regs, PREG_SET_ORDER);

      # Will contain image filenames. Temp array used to check for duplicates
      $usedimgs = array();

      # Now build array of user-defined images set for this template/layout
      $usrimages = array();
      $qry = "select orig_image, user_image from smt_userimages";
      $qry .= " where template_folder = '".$_GET['templatefolder']."' and layout_file = '".$htmlfile."'";
      $qry .= " and user_image != ''";
      
	  # CHRIS_MOD
	  ($testResult != "" && $pgList != "") ? $qry .= " AND page = '".$pgList."'" : $qry .= " AND page = ''";
	  # END CHRIS_MOD
      
      if ( $rez = mysql_query($qry) ) {
         while ( $getImg = mysql_fetch_assoc($rez) ) {
            $usrimages[$getImg['orig_image']] = $getImg['user_image'];
         }
      }

      $counter = 1; // For js ids

      # Loop through user images found in this layout
      for ( $x=0; $x < count($regs); $x++ ) {
         $imgfile_orig = $regs[$x][0];
         $imgfile_orig = eregi_replace("^/", "", $imgfile_orig); // Stupid gremlins

         # Only display image once even if used several places in template html
         if ( !in_array($imgfile_orig, $usedimgs) ) {
            $usedimgs[] = $imgfile_orig;
            $widthheight = getImageSize($basepath_orig."/".$imgfile_orig);
            $optionboxid = "optionbox-".str_replace(".html", "_layout", $htmlfile)."-".$counter;
            $imgid = "userimg-".str_replace(".html", "_layout", $htmlfile)."-".$counter;
            $onevents = "";

		# CHRIS_MOD
		($_SESSION['currentPageList'] != "") ? $currentPage = "&amp;currentPageList=".$_SESSION['currentPageList'] : $currentPage = "";
		# END CHRIS_MOD
            $editlink = "template_images-edit.php?edit_img=".$imgfile_orig."&amp;templatefolder=".$_GET['templatefolder']."&amp;layoutfile=".$htmlfile.$currentPage;

            $img_onclick = " onclick=\"document.location.href='".$editlink."'\"";

            # Display each image w/edit button
            echo " <div id=\"".str_replace(".html", "_layout", $htmlfile)."-".$counter."\" class=\"userimage-container\"".$onevents.">\n";

            # img - default or swapped?
            if ( !isset($usrimages[$imgfile_orig]) ) {
               # original
               echo "  <div class=\"userimage-img\">\n";
               echo "   <img src=\"".$basesrc_orig."/".$imgfile_orig."\" ".$widthheight[3].$img_onclick.">";
               echo "   <br/><span style=\"color: #888c8e;\">".$imgfile_orig."</span>";
               echo "  </div>\n";
            } else {
               # user-defined
               if($usrimages[$imgfile_orig] == 'sohoadmin/program/spacer.gif'){
				$basesrc_user = str_replace('/images', '', $basesrc_user);
               }               
               echo "  <div class=\"userimage-img\">\n";
               echo "   <img src=\"".$basesrc_user."/".$usrimages[$imgfile_orig]."\" style=\"border: 1px dotted #ffc417;\" ".$widthheight[3].$img_onclick.">";
               echo "   <br/><span style=\"color: #888c8e;\">".$imgfile_orig."</span><span style=\"color: #ffc417;\"><b>-&gt;</b></span>".str_replace('sohoadmin/program/spacer.gif', '[No Image]', $usrimages[$imgfile_orig]);
               echo "  </div>\n";
            }

            # Edit >>
            echo "  <div class=\"userimage-option_box\" id=\"".$optionboxid."\" style=\"display: block;\">\n";
            //echo "   <input type=\"button\" value=\"Edit &gt;&gt;\" onclick=\"document.location.href='".$editlink."'\">\n";
            echo "   <button class=\"grayButton\" type=\"button\" onclick=\"document.location.href='".$editlink."'\"><span><span>Edit &gt;&gt;</span></span></button>\n";
            
            echo "  </div>\n";
            echo " </div>";
            $counter++;

         } // End if not a dup

      } // End for found images

   } // End if userimg found in layout

} // End foreach layout

echo "  <input type=\"hidden\" name=\"total_userimgs\" value=\"".$total_userimgs."\">\n";
echo " </form>\n";
?>
</div>



<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();



$module = new smt_module($module_html);
$module->meta_title = "Edit Template Images";
$module->add_breadcrumb_link("Template Manager", "program/modules/site_templates.php");
$module->add_breadcrumb_link("Template Images <span class=\"unbold\">(".$_GET['templatefolder'].")</span>", "program/modules/site_templates/template_images.php?templatefolder=".$_GET['templatefolder']);
$module->icon_img = "skins/".$_SESSION['skin']."/icons/template_manager-enabled.gif";
$module->heading_text = "Template Images";
$module->description_text = "Certain images within one or more of your templates can be swapped-out for other images of your choosing.";
$module->good_to_go();
?>
