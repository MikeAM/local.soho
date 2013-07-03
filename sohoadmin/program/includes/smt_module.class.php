<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

/*---------------------------------------------------------------------------------------------------------*
$module = new smt_module($module_html);
$module->title_tag = "Create New Pages";
$module->add_breadcrumb_link("Create New Pages", "create_pages.php");

# Path from sohoadmin/
$module->icon = "skins/".$_SESSION['skin']."/icons/full_size/create_pages-enabled.gif";

$module->heading_text = "Create New Pages";

$module->description = "You may create up to 10 new pages at a time. Please only use Alpha Numerical characters and Underscores.";

$module->goodtogo();
/*---------------------------------------------------------------------------------------------------------*/
$globalprefObj = new userdata('global');
$_SESSION['utf8value'] = $globalprefObj->get('utf8');
$_SESSION['goog_trans'] = $globalprefObj->get('goog_trans');
class smt_module {
   var $meta_title;
   var $breadcrumb_links = array();
   var $heading_text;
   var $icon_img;
   var $output;
   var $container_css;
   var $module_table_css;

   # Constructor
   function smt_module($module_html) {
      # Populate module html container var with passed data
      if($_SESSION['goog_trans']=='off'){
      	$this->module_html = $module_html;	
	 } else {
      	$this->module_html = $module_html.display_google_translate();
      }

      # Read-in module template html to work with
      # Populates $this->output
      ob_start();

      include_once('smt_module_template-ultra.php');
     
      $this->output = ob_get_contents();
      ob_end_clean();
   }

   # add_breadcrumb_link()
   # Add link to path-to-module breadcrumb
   function add_breadcrumb_link($display_text, $link_href) {
      $this->breadcrumb_links[] = array('display_text' => $display_text, 'link_href' => $link_href);
   }

   # add_cssfile()
   # Hook in a dedicated external css file for this module
   function add_cssfile($filepath) {
      # Hash code in module template to hook this file link in at
      $hooktag = "<!---#add_cssfile#-->";

      # Build link html
      $csslink = "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$filepath."\"/>\n";

      # Add to output html
      $this->output = str_replace($hooktag, $csslink.$hooktag, $this->output);
   }

   # Compile final module html for display
   function good_to_go() {

      # REPORT_MESSAGES?
      if ( count($GLOBALS['report']) > 0) {
         $report_bullets = "<ul>\n";
         for ( $rr = 0; $rr < count($GLOBALS['report']); $rr++ ) {
            $report_bullets .= " <li>".$GLOBALS['report'][$rr]."</li>\n";
         }
         $report_bullets .= "</ul>\n";
         $this->output = str_replace("#REPORT_DISPLAY#", "block", $this->output);
         $this->output = str_replace("#REPORT_MESSAGES#", $report_bullets, $this->output);
      } else {
         $this->output = str_replace("#REPORT_DISPLAY#", "none", $this->output);
      }


      # META_TITLE - default to heading_text if empty
      if ( $this->meta_title == "" ) { $this->meta_title = $this->heading_text; }

      # Build breadcrumb links
      $breadcrumb_html = "";
      for ( $x = 0; $x < count($this->breadcrumb_links); $x++ ) {
         if ( $x == (count($this->breadcrumb_links) - 1) ) { $self = " class=\"current\""; } else { $self = ""; }
         $breadcrumb_html .= "&gt;  <a href=\"http://".$_SESSION['docroot_url']."/sohoadmin/".$this->breadcrumb_links[$x]['link_href']."\"".$self.">".$this->breadcrumb_links[$x]['display_text']."</a> ";
      }
      
      # Set flag for current module
      $module_section = '';
      if ( preg_match('/shopping_cart/i', $breadcrumb_html) > 0 ) {
      	$module_section = 'cart';
      } elseif(preg_match('/blog/i', $breadcrumb_html) > 0) {
      	$module_section = 'blog';
      } elseif(preg_match('/calendar/i', $breadcrumb_html) > 0) {
      	$module_section = 'calendar';
      } elseif(preg_match('/newsletter/i', $breadcrumb_html) > 0) {
      	$module_section = 'newsletter';
      } elseif(preg_match('/download_data/i', $breadcrumb_html) > 0) {
      	$module_section = 'database_manager';
      } elseif(preg_match('/security/i', $breadcrumb_html) > 0) {
      	$module_section = 'security';
      }
      

      # container_css #
      # containercss (cellpadding for <div> element in module template that contains module html) - 10px pad UOD
      # 0px will screw up recently-updated modules like template manager and template manager, but is needed by some new mods like forms manager 2.0
      if ( $this->container_css == "" ) { $this->container_css = "margin: 0;padding: 7px;"; }

      # module_table_css #
      # Default or special css for outer module table? Use sparingly. All should look the same ideally.
      if ( $this->module_table_css == "" ) { $this->module_table_css = "margin-top: 10px;width: 97%;"; }

      # bodyid
      # DEFAULT: id of body element is "body", set to something diff for css-based tab switching
      if ( $this->bodyid == "" ) { $this->bodyid = "body"; }
      
	if($_GET['show']=='summary' && $module_section=='cart' && $_SESSION['product_mode']=='trial'){
		$this->icon_img = '';
		$this->meta_title = '';
		$this->heading_text='';
		$breadcrumb_html='';
	}
      
      # Misc Text/Element replacements
      $this->output = str_replace("#bodyid#", $this->bodyid, $this->output);
      $this->output = str_replace("#module_table_css#", $this->module_table_css, $this->output);
      $this->output = str_replace("#container_css#", $this->container_css, $this->output);
      $this->output = str_replace("#META_TITLE#", $this->meta_title, $this->output);
      $this->output = str_replace("#BREADCRUMB_LINKS#", $breadcrumb_html, $this->output);

		if($this->icon_img!=""){
			$this->output = str_replace("#ICON_IMG#", "<img src=\"".admin_nav_link($this->icon_img)."\" style=\"margin-bottom:4px;margin-right:4px; vertical-align:text-top; height:30px;\">", $this->output);
		} else {
			$this->output = str_replace("#ICON_IMG#", "", $this->output);
		}
      //$this->output = eregi_replace("#ICON_IMG#", $this->icon_img, $this->output);
      
      $this->output = str_replace("#HEADING_TEXT#", str_replace("'",'&#39;',$this->heading_text), $this->output);
	
	if(!is_array($disabled_modules)){
		$disabled_modules = array();
		$linkincludefile=$_SESSION['doc_root'].'/sohoadmin/config/ultrahost.link.php';
		if(file_exists($linkincludefile)){
			include_once($linkincludefile);
			if(count($disabled_modules) < 2){
				$disabled_modules = array('cart', 'newsletter', 'database_manager');
			}
		} else {
	//	      $disabled_modules = array('blog','security', 'calendar', 'cart', 'newsletter', 'database_manager');		
			$disabled_modules = array('cart', 'newsletter', 'database_manager');
		}
	}
 
      if ( in_array($module_section, $disabled_modules) && $_SESSION['product_mode']=='trial') {
      	// Pull disabled message
      	include('upgrade-required.php');
      	$this->output = str_replace("#DESCRIPTION_TEXT#", '', $this->output);
      	$this->output = str_replace("#MODULE_HTML#", $upgrade_required_html, $this->output);
      } else {
	      $this->output = str_replace("#DESCRIPTION_TEXT#", $this->description_text, $this->output);
	      $this->output = str_replace("#MODULE_HTML#", $this->module_html, $this->output);
	   }
      
      if ( $_SESSION['utf8value'] == 'on' ) {
      	$this->output = str_replace('iso-8859-1', 'utf-8', $this->output);
      	$this->output = str_replace('ISO-8859-1', 'utf-8', $this->output);
      }

      echo $this->output;
   }
}

?>