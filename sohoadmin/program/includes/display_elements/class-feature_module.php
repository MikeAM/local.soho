<?php
//error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

##########################################################################################################################################
## Soholaunch(R) Site Management Tool
## Version 4.7
##
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugz.soholaunch.com
## Community:     http://forum.soholaunch.com
##########################################################################################################################################

##########################################################################################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2005 Soholaunch.com, Inc.  All Rights Reserved.
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
##########################################################################################################################################


/*====================================================================================================================*
 ______            _                          __  __             _         _
|  ____|          | |                        |  \/  |           | |       | |
| |__  ___   __ _ | |_  _   _  _ __  ___     | \  / |  ___    __| | _   _ | |  ___
|  __|/ _ \ / _` || __|| | | || '__|/ _ \    | |\/| | / _ \  / _` || | | || | / _ \
| |  |  __/| (_| || |_ | |_| || |  |  __/    | |  | || (_) || (_| || |_| || ||  __/
|_|   \___| \__,_| \__| \__,_||_|   \___|    |_|  |_| \___/  \__,_| \__,_||_| \___|

/// Output complete module page inc. any relevant tables, forms, html, etc.
###===================================================================================================================*/

class feature_module {
   var $fmod = array(); // Titles, info, html output - Data that varies between features
   var $fgroups = array(); // Contains html tables for each row of parent module table
   var $title;
   var $navbar;
   var $csslink; // Link to external stylesheet
   var $sohoadmin; // SMT folder name
   var $jscripts; // Module-specific javascript
   var $popdivs; // Div layers above main module layer


   function feature_module($modtitle = "", $navlayer = "main menu") {
      $this->sohoadmin = "sohoadmin";
      $this->css_main = "/program/product_gui.css";
      //$this->css_main = "/program/includes/product_interface.css";
      $this->css_alt = "/program/includes/display_elements/product_gui-v2.css";
      $this->title = $modtitle;
      $this->navbar = $navlayer;
   }

   function tophtml() {
      $html_head = "<html>\n";
      $html_head .= "<head>\n";
      $html_head .= "<title>".$this->title."</title>\n";
      $html_head .= "\n";
      $html_head .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=iso-8859-1\">\n";
      $html_head .= "<meta http-equiv=\"pragma\" content=\"no-cache\">\n";
      $html_head .= "<meta http-equiv=\"expires\" content=\"-1\">\n";

      $html_head .= "\n\n";
      $html_head .= "<!-------------------------------------->\n";
      $html_head .= "<!-----------Stylesheet Link------------>\n";
      $html_head .= "<!-------------------------------------->\n";
      $html_head .= "<link rel=\"stylesheet\" href=\"http://".$_SESSION['docroot_url']."/".$this->sohoadmin.$this->css_main."\">\n";
      //$html_head .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"http://".$_SESSION['docroot_url']."/".$this->sohoadmin."/program/includes/display_elements/wz_tooltip.js\"></script>\n";

      $html_head .= "\n\n";
      $html_head .= "<!-------------------------------------->\n";
      $html_head .= "<!--- Standard javascript functions ---->\n";
      $html_head .= "<!-------------------------------------->\n";
      $html_head .= "<script language=\"JavaScript\">\n";
      $html_head .= "\n";
      $html_head .= "function navto(a) {\n";
      $html_head .= "   window.location = a;\n";
      $html_head .= "}\n";
      $html_head .= "\n";
      $html_head .= "function mksure_go(msg, addr) {\n";
      $html_head .= "   var conwin = window.confirm(msg);\n";
      $html_head .= "   if ( conwin ) {\n";
      $html_head .= "      window.location=addr;\n";
      $html_head .= "   }\n";
      $html_head .= "}\n";
      $html_head .= "\n";
      $html_head .= "function popupMsg(msg) { //v1.0\n";
      $html_head .= "  alert(msg);\n";
      $html_head .= "}\n";
      $html_head .= "\n";
      $html_head .= "function openBrWindow(theURL,winName,features) { //v2.0\n";
      $html_head .= "  window.open(theURL,winName,features);\n";
      $html_head .= "}\n";
      $html_head .= "\n";
      $html_head .= "\n";
      $html_head .= "function showme(targetid) {\n";
      $html_head .= "  document.getElementById(targetid).style.display='block';\n";
      $html_head .= "}\n";
      $html_head .= "function hideme(targetid) {\n";
      $html_head .= "  document.getElementById(targetid).style.display='none';\n";
      $html_head .= "}\n";
      $html_head .= "function toggle(targetid) {\n";
      $html_head .= "  var isnow = document.getElementById(targetid).style.display;\n";
      $html_head .= "  if ( isnow == 'block' ) {\n";
      $html_head .= "     document.getElementById(targetid).style.display='none';\n";
      $html_head .= "  } else {\n";
      $html_head .= "     document.getElementById(targetid).style.display='block';\n";
      $html_head .= "  }\n";
      $html_head .= "}\n";
      $html_head .= "\n";
      $html_head .= "function show_hide_layer() { \n";
      $html_head .= "  var i,p,v,obj,args=show_hide_layer.arguments;\n";
      $html_head .= "  for (i=0; i<(args.length-2); i+=3) if ((obj=find_object(args[i]))!=null) { v=args[i+2];\n";
      $html_head .= "   if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }\n";
      $html_head .= "   obj.visibility=v; }\n";
      $html_head .= "}\n";

      # Original show-hide javascript (mainly for upper nav menus)
      #~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
      $html_head .= "function SV2_findObj(n, d) { //v3.0\n";
      $html_head .= "  var p,i,x;  if(!d) d=document; if((p=n.indexOf(\"?\"))>0&&parent.frames.length) {\n";
      $html_head .= "    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}\n";
      $html_head .= "  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];\n";
      $html_head .= "  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;\n";
      $html_head .= "}\n";
      $html_head .= "function SV2_showHideLayers() { //v3.0\n";
      $html_head .= "  var i,p,v,obj,args=SV2_showHideLayers.arguments;\n";
      $html_head .= "  for (i=0; i<(args.length-2); i+=3) if ((obj=SV2_findObj(args[i]))!=null) { v=args[i+2];\n";
      $html_head .= "    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }\n";
      $html_head .= "    obj.visibility=v; }\n";
      $html_head .= "}\n";

      $html_head .= "\n";

      // Format casual layer names for upper nav
      //------------------------------------------------------
      $this->navbar = str_replace(" ", "_", $this->navbar);
      $this->navbar = strtoupper($this->navbar);
      $this->navbar = trim($this->navbar);
      $upnav = "MAIN_MENU_LAYER;PAGE_EDITOR_LAYER;CART_MENU_LAYER;DATABASE_LAYER;NEWSLETTER_LAYER;CALENDAR_MENU_LAYER;WEBMASTER_MENU_LAYER";
      $upbtns = explode(";", $upnav);


      // Display applicable upper navigation buttons
      //------------------------------------------------------
      foreach ( $upbtns as $lay ) {
         if ( eregi($this->navbar, $lay) ) { $disp = "show"; } else { $disp = "hide"; }
         $html_head .= "show_hide_layer('".$lay."?header','','".$disp."');\n";
         $html_head .= "SV2_showHideLayers('".$lay."?header','','".$disp."');\n";
      }


      # Module-specific javascript functions
      #-----------------------------------------------------------------------
      $html_head .= "\n\n";
      $html_head .= "<!-------------------------------------->\n";
      $html_head .= "<!----Module-specific Javascript-------->\n";
      $html_head .= "<!-------------------------------------->\n";
      $html_head .= $this->jscripts;

      $html_head .= "</script>\n";
      $html_head .= "\n";
      $html_head .= "</head>\n";
      $html_head .= "\n";

      $html_head .= "<body bgcolor=\"white\" text=\"black\" link=\"darkblue\" vlink=\"darkblue\" alink=\"darkblue\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">\n";
      $html_head .= $this->popdivs;
      $html_head .= "<div id=\"scrollLayer\" style=\"position:absolute; visibility:visible; left:0px; top:0; width:100%; height:100%; z-index:1; overflow: auto; border: 1px none #000000\">\n";

      return $html_head;

   } // End tophtml() method


   function make_module($hdrtxt = "") {

      # Compile and include html header and body tags
      #-----------------------------------------------------------------------
      $html_output = $this->tophtml();

      # Build main module table
      #-----------------------------------------------------------------------
      $html_output .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" class=\"feature_module\" id=\"module_feature\">\n";

      # Make module title optional until all modules are OOP
      if ( $hdrtxt != "" && $this->title != "" ) {
         $html_output .= " <tr>\n";
         $html_output .= "  <td class=\"fmod_title\">\n";
         $html_output .= "   ".$this->title."\n";
         $html_output .= "  </td>\n";
         $html_output .= " </tr>\n";

      } else {
         $html_output .= " <tr>\n";
         $html_output .= "  <td id=\"pad_none\">\n";
         $html_output .= "   &nbsp;\n";
         $html_output .= "  </td>\n";
         $html_output .= " </tr>\n";
      }

      foreach ( $this->fgroups as $rowdata ) {
         $html_output .= " <tr>\n";
         $html_output .= "  <td align=\"center\" valign=\"top\">\n";
         $html_output .= "   ".$rowdata."\n";
         $html_output .= "  </td>\n";
         $html_output .= " </tr>\n";
      }

      $html_output .= "</table>\n";
      $html_output .= "</div>\n";
      $html_output .= "<script language=\"JavaScript\" type=\"text/javascript\" src=\"http://".$_SESSION['docroot_url']."/".$this->sohoadmin."/program/includes/display_elements/wz_tooltip.js\"></script>\n";
      $html_output .= "</body>\n";
      $html_output .= "</html>\n";

      return $html_output;
   }


   function add_fgroup($subtitle, $data) {
      $fgroup = "<table width=\"95%\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\" class=\"feature_group\">\n";
      $fgroup .= " <tr>\n";
      $fgroup .= "  <td class=\"fgroup_title\">\n";
      $fgroup .= "   ".$subtitle."\n";
      $fgroup .= "  </td>\n";
      $fgroup .= " </tr>\n";
      $fgroup .= " <tr>\n";
      $fgroup .= "  <td align=\"center\" valign=\"top\">\n";
      $fgroup .= "   ".$data."\n";
      $fgroup .= "  </td>\n";
      $fgroup .= " </tr>\n";
      $fgroup .= "</table>\n";
      $this->fgroups[] = $fgroup;
   }

}

# Uncomment to test class
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*
$newMod = new feature_module("testing the class");
echo $newMod->dosom();
$newMod->add_fgroup("awesome title", "awesome data");
echo $newMod->make_module();
echo "This is from the bottom of the script."; exit;
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*=============================================================================================*/

?>