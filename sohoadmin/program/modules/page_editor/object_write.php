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
## Copyright 1999-2003 Soholaunch.com, Inc.  All Rights Reserved.
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

################################################################################
### This script controls all aspects of how an object will look and operate
### once viewed from the actual web site.
################################################################################

error_reporting(E_PARSE);
require_once('../../includes/product_gui.php');

$spacer = ""; //Killed spacer 2004-03-12
$sfont = "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\"><B>";
$nfont = "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\">";


$object = split("~~~", $thisrow);
$objcount = count($object);
$objcount--;

$droparea .= "<div>\n";

for ($numobj=0;$numobj<=$objcount;$numobj++) {

	$thisobj = $object[$numobj];
	$thisobj = addslashes($thisobj);
	$flashpass = 0;

	########################################
	#### DIRECTIONS OBJECT				####
	########################################
	
	if (eregi("##SOC_", $thisobj)) {

		$tmp = eregi("<!--##(.*)## -->", $thisobj, $out);
		$tmp = '##'.$out[1].'##';
		$droparea .= $tmp."<br/>\n";
		
	}
	if (eregi("##MAPQUEST", $thisobj)) {

		$tmp = eregi("<!-- ##MAPQUEST;(.*)## -->", $thisobj, $out);
		$tmp = $out[1];
		$mapaddr = split(";", $tmp);

		// ***********************************************************************************************
		// DEVNOTE: This form action was copied from mapquests website.  As with all off-site resources
		// they tend to change every six months or so.  If the mapquest link suddenly stops working,
		// it is likely because mapquest has updated or modified their site.  You will need to see
		// how they are doing new data queries and modify this code as needed.
		// ***********************************************************************************************

		if ($mapaddr[4] == "MAPQUEST") {

				// Insert MAPQUEST(tm) Search

				$droparea .= "<form action=\"http://www.mapquest.com/maps/map.adp\" target=\"_blank\">\n";
				$droparea .= "<input type=\"hidden\" name=\"countrycode\" value=\"250\">\n";
				$droparea .= "<input type=\"hidden\" name=\"country\" value=\"US\">\n";
				$droparea .= "<input type=\"hidden\" name=\"address\" value=\"".$mapaddr[0]."\">\n";
				$droparea .= "<input type=\"hidden\" name=\"city\" value=\"".$mapaddr[1]."\">\n";
				$droparea .= "<input type=\"hidden\" name=\"state\" value=\"".$mapaddr[2]."\">\n";
				$droparea .= "<input type=\"hidden\" name=\"zipcode\" value=\"".$mapaddr[3]."\">\n";
				$droparea .= "<input type=\"hidden\" name=\"addtohistory\">\n";
				$droparea .= "<input type=\"submit\" value=\" ".lang("Get Directions")." \" class=FormLt1>\n";
				$droparea .= "<BR><FONT STYLE='font-family: Arial; font-size: 7pt; color: #999999;'>".lang("Courtesy of").": MapQuest<SUP>tm</SUP>\n\n";
				$droparea .= "</form>\n\n";

		} elseif ($mapaddr[4] == "GOOGLEMAPS") {

				// Insert GOOGLEMAPS
				$droparea .= "<form name=\"mapFrom\" action=\"http://maps.google.com/maps\" method=\"GET\" target=\"_blank\">\n";
				$droparea .= "<input type=\"hidden\" name=\"saddr\" value=\"\" />\n";
				$droparea .= "<input type=\"hidden\" name=\"daddr\" id=\"daddr\" value=\"".$mapaddr[0].", ".$mapaddr[1].", ".$mapaddr[2]." ".$mapaddr[3]." ".$mapaddr[5]."\" />\n";
				$droparea .= "<input type=submit value=\" ".lang("Get Directions")." \" class=FormLt1>\n";
				$droparea .= "<BR><FONT STYLE='font-family: Arial; font-size: 7pt; color: #999999;'>".lang("Courtesy of").": Google Maps<SUP>tm</SUP>\n\n";
				$droparea .= "<input type=\"hidden\" name=\"hl\" value=\"en\" />\n";
				$droparea .= "</form>\n\n";

//
//				$droparea .= "<form name=\"mapFrom\" action=\"http://maps.yahoo.com/py/maps.py\" method=\"GET\" target=\"_blank\">\n";
//
//				$droparea .= "<input type=\"hidden\" name=\"addr\" value=\"$mapaddr[0]\">\n";
//				$droparea .= "<input type=\"hidden\" name=\"city\" value=\"$mapaddr[1]\">\n";
//				$droparea .= "<input type=\"hidden\" name=\"state\" value=\"$mapaddr[2]\">\n";
//				$droparea .= "<input type=\"hidden\" name=\"zip\" value=\"$mapaddr[3]\">\n";
//				$droparea .= "<input type=\"hidden\" name=\"country\" value=\"us\">\n";
//
//				$droparea .= "<input type=submit value=\" ".lang("Get Directions")." \" class=FormLt1>\n";
//				$droparea .= "<BR><FONT STYLE='font-family: Arial; font-size: 7pt; color: #999999;'>".lang("Courtesy of").": Yahoo! Maps<SUP>tm</SUP>\n\n";
//				$droparea .= "</form>\n\n";


		} else {

				// Insert YAHOO!(tm) Search

				$droparea .= "<form name=\"mapFrom\" action=\"http://maps.yahoo.com/py/maps.py\" method=\"GET\" target=\"_blank\">\n";

				$droparea .= "<input type=\"hidden\" name=\"addr\" value=\"$mapaddr[0]\">\n";
				$droparea .= "<input type=\"hidden\" name=\"city\" value=\"$mapaddr[1]\">\n";
				$droparea .= "<input type=\"hidden\" name=\"state\" value=\"$mapaddr[2]\">\n";
				$droparea .= "<input type=\"hidden\" name=\"zip\" value=\"$mapaddr[3]\">\n";
				$droparea .= "<input type=\"hidden\" name=\"country\" value=\"us\">\n";

				$droparea .= "<input type=submit value=\" ".lang("Get Directions")." \" class=FormLt1>\n";
				$droparea .= "<BR><FONT STYLE='font-family: Arial; font-size: 7pt; color: #999999;'>".lang("Courtesy of").": Yahoo! Maps<SUP>tm</SUP>\n\n";
				$droparea .= "</form>\n\n";


		} // End Search Type Check


	}

	########################################
	#### PDF DOWNLOAD OBJECT			####
	########################################

	if (eregi("##PDF", $thisobj)) {
		$tmp = eregi("<!-- ##PDF;(.*)## -->", $thisobj, $out);
		$pdf_file = $out[1];

		$image = "sohoadmin/program/modules/page_editor/client/pdf_download.gif";
		$userfile = "$doc_root/pdf_download.gif";
//		if (file_exists("$userfile")) {
//			@unlink($userfile);
//		}
		//@copy($image, $userfile);

		$pdfdisplay = eregi_replace("_", " ", $pdf_file);
		$pdfdisplay = eregi_replace(".pdf", "", $pdfdisplay);
		$pdfdisplay = ucwords($pdfdisplay);

		$clientname = "$doc_root/media/$pdf_file";

		$size = filesize($clientname);

		 if ($size >= 1048576) {
			$size = round($size/1048576*100)/100;
			$size = $size . "Mb";
		 } elseif ($size >= 1024) {
			$size = round($size/1024*100)/100;
			$size = $size . "K";
		 } else {
			$size = $size . "Bytes";
		 }

		// width=$l[$x] (For table)

		$droparea .= "<table border=0 cellpadding=2 cellspacing=0 align=center>\n";
		$droparea .= "  <tr>\n";
		$droparea .= "    <td align=left valign=top class=smtext>\n";
		$droparea .= "      <a href=\"media/$pdf_file\" target=\"_blank\"><img src=\"sohoadmin/program/modules/page_editor/client/pdf_download.gif\" border=0 width=21 height=22 vspace=0 hspace=3 align=absmiddle>$pdfdisplay</a> [$size]\n";
		$droparea .= "    </td>\n";
		$droparea .= "  </tr>\n";
		$droparea .= "</table>\n";

	}

	########################################
	#### DOCUMENT DOWNLOAD OBJECT		####
	########################################

	if (eregi("##MSWORD", $thisobj)) {
		$tmp = eregi("<!-- ##MSWORD;(.*)## -->", $thisobj, $out);
		$msword_file = $out[1];

		$image = "sohoadmin/program/modules/page_editor/client/download_icon.gif";
		$userfile = "$doc_root/download_icon.gif";
//		if (file_exists("$userfile")) {
//			@unlink($userfile);
//		}
		//@copy($image, $userfile);

		// $msworddisplay = eregi_replace("_", " ", $msword_file);
		// $msworddisplay = ucwords($msworddisplay);

		$clientname = "$doc_root/media/$msword_file";
		$size = filesize($clientname);

		 if ($size >= 1048576) {
			$size = round($size/1048576*100)/100;
			$size = $size . "Mb";
		 } elseif ($size >= 1024) {
			$size = round($size/1024*100)/100;
			$size = $size . "K";
		 } else {
			$size = $size . "Bytes";
		 }

		$droparea .= "<div style=\"padding 5px;\" class=\"text\" align=\"left\">";
		$droparea .= "<a href=\"pgm-download_media.php?name=".$msword_file."\"><img src=\"sohoadmin/program/modules/page_editor/client/download_icon.gif\" border=\"0\" width=\"20\" height=\"19\" hspace=\"2\" vspace=\"2\" align=\"absmiddle\">\n";
		$droparea .= "$msword_file</a> &nbsp;<font size=\"1\">[".$size."]</font></div><br>\n";

	}

	########################################
	#### PRINT PAGE OBJECT				####
	########################################

	if (eregi("##PRINTTHIS", $thisobj)) {
		$tmplink = eregi_replace(" ", "_", $currentPage);
		$droparea .= "<div align=\"center\"><form name=\"printpage\"><input type=\"button\" class=\"FormLt1\" value=\"".lang("Printable Page")."\" onclick=\"window.open('pgm-print_page.php?currentPage=$tmplink','printwin','scrollbars=yes,width=700,height=450');\">";
		$droparea .= "</form></div>\n";
	}

	########################################
	#### EMAIL FRIEND OBJECT		####
	########################################

	if (eregi("##EFRIEND", $thisobj)) {
		$tmplink = eregi_replace(" ", "_", $currentPage);

		$droparea .= "<div id=\"efriend\" align=\"center\">".$spacer."<a href=\"pgm-email_friend.php?mailpage=".$tmplink."\">[ ".lang("Email this page to a friend")." ]</a><BR>".$spacer."</div>\n";

	}

	########################################
	#### HIT COUNTER OBJECT				   ####
	########################################
	if (eregi("##COUNTER", $thisobj)) {

		// Copy counter image's to root directory

		for ($cnt_img=0;$cnt_img<=9;$cnt_img++) {

			$image = "client/".$cnt_img.".gif";
			$userfile = "$doc_root/".$cnt_img.".gif";

			//@copy($image, $userfile);

		}

		// Place Notice for builder program - counter must work in 'real-time'

		$droparea .= "<div align=\"center\">".$spacer."<!-- ##COUNTER## --></div>\n";

	}

	########################################
	#### DATABASE OBJECT (3.5 MOD)		####
	########################################

	if (eregi("##MEMBERSHIP", $thisobj)) {
		$tmp = eregi("<!-- ##MEMBERSHIP;(.*)## -->", $thisobj, $out);
		$dataname = $out[1];
		$droparea .= "<!-- ##MIKEINC;$dataname## -->\n\n";		// Treat this just like a custom PHP include
	}

	########################################
	#### PHOTO SYSTEM (4.5 MOD)			####
	########################################

	if (eregi("##PHOTO", $thisobj)) {
		$tmp = eregi("<!-- ##PHOTO;(.*)## -->", $thisobj, $out);
		$dataname = $out[1];
		$droparea .= "\n\n<!-- ##PHOTO;$dataname## -->\n\n";
	}

	########################################
	#### FAQ SYSTEM (4.5 MOD)			####
	########################################

	if (eregi("##FAQ", $thisobj)) {
		$tmp = eregi("<!-- ##FAQ;(.*)## -->", $thisobj, $out);
		$dataname = $out[1];
		$droparea .= "\n\n<!-- ##FAQ;$dataname## -->\n\n";
	}

	########################################
	#### BLOG SYSTEM (4.5 MOD)			####
	########################################

	if (eregi("##BLOG", $thisobj)) {
		$tmp = eregi("<!-- ##BLOG;(.*)## -->", $thisobj, $out);
		$dataname = $out[1];
		$droparea .= "\n\n<!-- ##BLOG;$dataname## -->\n\n";
	}

	########################################
	#### NEWSLETTER SIGNUP OBJECT		####
	########################################

	if (eregi("##NEWSLETTER", $thisobj)) {
		$tmp = eregi("<!-- ##NEWSLETTER;(.*)## -->", $thisobj, $out);
		$tmp = $out[1];
		$tmp = split(";", $tmp);
		$newscat = $tmp[0];
		$contest = $tmp[1];
		$dval = lang("Sign-up Now")." >>";
		$droparea .= "<div align=\"center\">\n<form method=\"post\" action=\"newsletter.php\">\n";
		$droparea .= "<input type=\"hidden\" name=\"newscategory\" value=\"$newscat\">\n";
		$droparea .= "<input type=\"hidden\" name=\"contestvar\" value=\"$contest\">\n";
		$droparea .= "<input type=\"submit\" class=FormLt1 value=\"$dval\">\n</form>\n</div>\n";
	}

	########################################
	#### SECURE LOGIN OBJECT			####
	########################################

	if (eregi("##SECURELOGIN", $thisobj)) {
		$tmp = eregi("<!-- ##SECURELOGIN;(.*)## -->", $thisobj, $out);
		$BUTTON_NAME = $out[1];

		$droparea .= "\n\n<!-- ##SECURELOGIN;".$BUTTON_NAME."## -->\n\n";

//		$droparea .= "<div align=\"center\">\n";
//		$droparea .= "<form method=\"post\" action=\"pgm-secure_login.php\">\n";
//		$droparea .= "<table border=0 cellpadding=5 cellspacing=0 class=border width=199><tr><td align=center valign=top bgcolor=#EFEFEF>\n";
//		$droparea .= "<input type=submit class=FormLt1 value=\"$BUTTON_NAME\"><BR>\n";
//		$droparea .= "<font size=1 face=Arial, Helvetica>&nbsp;<BR>".lang("Forget your password")."? <a href=\"pgm-secure_remember.php\">".lang("Click Here").".</a>\n";
//		$droparea .= "</td></tr></table>\n</form>\n</div>\n";

	}

	########################################
	#### PLACE IMAGE IN DROP ZONE		####
	########################################

	if (eregi("##IMAGE", $thisobj)) {

	   //echo "(".$thisobj.")<br/>\n";


		// **************************************************************************************
		// Some string replace functions have been added here specifically for web sites
		// that were built custom prior to distribution.  I have not removed any of the
		// string replacements as they will not interfere with the operation of the product
		// as a whole.  You may experiment with removing some statements if you wish, but
		// it is recommended that you DO NOT.  It will not increase speed enough to matter.
		// **************************************************************************************

		$thisobj = str_replace("http://216.122.89.50/5150/rotarytemplate.com/htdocs/images/", "http://216.122.89.50/5150/$dot_com/htdocs/images/", $thisobj);

		// ----------------------------------------------------------------------
		// 2003-03-04 Make modifications for Images based on Liquid Expansion
		// ----------------------------------------------------------------------

		if (eregi("\%", $l[$x])) {
			if ($l[$x] == "100%") {	$maxImgLen = 612; }
			if ($l[$x] == "33%") { $maxImgLen = 199; }
			if ($l[$x] == "66%") { $maxImgLen = 398; }
			if ($l[$x] == "50%") { $maxImgLen = 300; }	// Split cells
		} else {
			$maxImgLen = $l[$x];
		}

		// ----------------------------------------------------------

		$tmp = eregi("<!-- ##IMAGE;(.*)## -->", $thisobj, $out);
		$tmp = $out[1];
		$tmp = split(";", $tmp);

		$imagesrc = $tmp[0];
		$linkto = ${$tmp[1]};

//		echo "(".$tmp[0].")<br/>";
//		echo "(".$tmp[1].")<br/>";
//		echo "(".${$tmp[0]}.")<br/>";
//		echo "(".${$tmp[1]}.")<br/>";
//		echo "(".$out[0].")<br/>";
//		echo "(".$out[1].")<br/>";
//		echo "(".$imagesrc.")<br/>";
//		exit;

		if (!eregi("clipart", $imagesrc)) {
			$parse_data = "http://$this_ip/images/";                       //Fix for images when logged in to sohoadmin as http://www.domain.com
			$www_parse_data = "http://www.$this_ip/images/";               //and then making changes as http://domain.com  -Joe Lain

			$no_www = eregi_replace("www.", "", $parse_data);
			$imagesrc = eregi_replace("$parse_data", "", $imagesrc);			//add www. when logged in through http://www.domain.com
			$imagesrc = eregi_replace("$www_parse_data", "", $imagesrc);   //strip www. when logged in through http://domain.com -Joe Lain


			$imagesrc = eregi_replace("$no_www", "", $imagesrc);

			if($tmp = eregi("(.*) width=", $imagesrc, $out)){
			   $imagesrc = $out[1];
			}
			$imagesrc = chop($imagesrc);
		} else {
			$parse_data = "../../../clipart/";
			$imagesrc = eregi_replace("$parse_data", "", $imagesrc);

			$tmp = eregi("(.*)width=", $imagesrc, $out);
			$imagesrc = chop($out[1]);

			$newfile = "$clipart_directory/$imagesrc";

			$tmpone = split("/", $imagesrc);
			$imagesrc = $tmpone[1];

			$userfile = "$doc_root/images/$imagesrc";
//			if (file_exists("$userfile")) {
//				@unlink($userfile);
//			}
//			@copy($newfile, $userfile);
		}


		$directory = "$doc_root/images";

		if (file_exists("$directory/$imagesrc")) {
			$tmparray = getImageSize("$directory/$imagesrc");
			$origw = $tmparray[0];
			$origh = $tmparray[1];
			$WH = "width=$origw height=$origh";
			if ($origw > $maxImgLen) {
				$calc = $maxImgLen/$origw;
				$hcalc = $origh*$calc;
				$nheight = round($hcalc);
				$WH = "width=\"".$maxImgLen."\" height=\"".$nheight."\"";
			}

			$imagecode = "<img src=\"images/$imagesrc\" $WH border=\"0\">";
			$imagesrc = $imagecode;
		}else{
			$imagecode = "<img src=\"".$imagesrc."\" border=\"0\">";
			$imagesrc = $imagecode;
		}

		if (strlen($linkto) > 3) {
			$imagesrc = "<a href=\"".pagename($linkto)."\">".$imagecode."</a>\n";
			if (eregi("mailto:", $linkto)) {
				$imagesrc = "<a href=\"".$linkto."\">".$imagecode."</a>\n";
			}
			if (eregi("https?://", $linkto)) {
				$imagesrc = "<a href=\"".$linkto."\" target=\"_blank\">".$imagecode."</a>\n";
			}
		}

		$droparea .= "<div align=\"center\">\n".$spacer."\n".$imagesrc."<BR clear=all></div>\n";

	}


	########################################
	#### TEXT AREA OBJECT				####
	########################################

 	if (eregi("NEWOBJ", $thisobj) || eregi("NEWEDIT", $thisobj)) {

      $browserType = $_SERVER['HTTP_USER_AGENT'];

      //echo "1(".$thisobj.")<br/>";
      if(eregi("<sohotextarea", $thisobj)){
      	$thisobj = eregi_replace("<sohotextarea", "<textarea", $thisobj);
      	$thisobj = eregi_replace("</sohotextarea", "</textarea", $thisobj);
      }
      //echo "2(".$thisobj.")<br/>";
      //exit;


	   // Old way of reading text
	   // Needed for reading text boxes from pages
	   // that were made with old editor.
	   // This should resolve any problems with
	   // sites built with old editor.
	   // Joe Lain 11-14-05

	   if(!eregi("<blink>", $thisobj)){
   		$tOutput = explode("SOHOTEXTSTART", $thisobj);
   		$tmp = $tOutput[1];
   		$endofspan = strpos($tmp, ">")+1;
   		$tmp = substr($tmp, $endofspan);
   		$text = $tmp;

   		//echo "<textarea name=\"textarea\" style=\" width: 300; height: 300;\">".$text." --></textarea><br><br>\n";

   		$text = eregi_replace("<!--", "", $text);	// Kill Multi-Object Split Sign
   		$newSplit = spliti("</span>", $text);
   		$text = $newSplit[0];
//         echo "<textarea name=\"textarea\" style=\" width: 300; height: 300;\">".$text." --></textarea><br><br>\n";
//         exit;
      }else{
         eregi("<blink>(.*)</blink>", $thisobj, $myout);
         $text = $myout[1];
      }

		$text = stripslashes($text);


      //echo "<textarea name=\"textarea\" style=\" width: 600px; height: 200px;\">".$text." --></textarea><br>\n";
      //exit;


			$final_text = "";
			$tLoop = split("\n", $text);
			$nTxt = count($tLoop);

			for ($q=0;$q<=$nTxt;$q++) {

				$text = $tLoop[$q];
				//echo "(".$q.")(".$text.")<br/>\n";

				if(eregi("<img", $text) && eregi("mceItem", $text)){
               $findImgObject = '<img class=([^\)]*) title="([^\)]*)" ([^\)]*)">';

               eregi($findImgObject, $text, $myout2);
//               echo "1(".$myout2[0].")<br/>\n";
//               echo "2(".$myout2[1].")<br/>\n";
//               echo "3(".$myout2[2].")<br/>\n";
//               echo "4(".$myout2[3].")<br/>\n";

               $classType = eregi_replace("mceItem", "", $myout2[1]);
               $classType = eregi_replace("\"", "", $classType);

               $replaceImgObject = "\n<script type=\"text/javascript\"> write".$classType."({".$myout2[2]."});</script>\n";

               $text = eregi_replace($myout2[0], $replaceImgObject, $text);

//               echo "finalText(".$text.")<br/>\n";
//               exit;
            }

				$text = eregi_replace("align=middle", "align=\"center\"", $text);

				// ========================================================
				// Format Text Area Links and Soho Code for final Output
				// ========================================================

//				$text = eregi_replace("<SPAN id=SOHOTEXTSTART>", "", $text);
//				$text = eregi_replace("</SPAN>", "", $text);

				$text = eregi_replace("SOHOLINK=", "href=", $text);

      		$text = str_replace("http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage=".$currentPage."&=SID", "", $text);
      		$text = str_replace("http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage=".$currentPage, "", $text);

            $daPage = eregi_replace(" ", "%20", $currentPage);

      		$text = str_replace("http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage=".$daPage."&=SID", "", $text);
      		$text = str_replace("http://".$_SESSION['this_ip']."/sohoadmin/program/modules/page_editor/page_editor.php?currentPage=".$daPage, "", $text);

      		$text = eregi_replace("http://".$_SESSION['this_ip']."/#", "#", $text);

      		$currentPage_with = eregi_replace(" ", "_", $currentPage);

      		$text = eregi_replace('href="?#', "href=\"".pagename($currentPage_with)."#", $text);

					// ----------------------------------------------------------------------------------
					// Replace Stuff Inserted Automatically by Text Editor when hardcoding links and such
					// ----------------------------------------------------------------------------------
					$tmp = "href=\"http://$this_ip/sohoadmin/program/modules/page_editor/";
					$text = eregi_replace($tmp,"href=\"", $text);

					$nTmpFind = eregi("href=\"text_editor_obj_45.php\?curtext=(.*)&=SID#", $text, $out);
					$thisNewObj = $out[1];
					//$text = eregi_replace("href=\"text_editor_obj_45.php\?curtext=$thisNewObj&=SID#","href=\"#", $text);
					// ----------------------------------------------------------------------------------

				# Only target a blank browser window if link is off-site (2003-03-04)
//				if (eregi(" href=\"http:", $text) && !eregi(" href=\"http://".$this_ip, $text)) {
//					$text = eregi_replace(" href=\"http:", " target=\"_blank\" href=\"http:", $text);
//				}


				// ====================================================================
				// Remove Microsoft's XML data inserted by Word
				// 2003-03-04 This issue concreted with addition of "Word" paste option
				// within the text editor... These lines could be removed all together
				// now.  We'll leave them anyway, just in case some idiot does a normal
				// paste feature.
				// ====================================================================

				$text = eregi_replace("<\?XML(.*)/>", "<!-- XML REMOVAL -->", $text);
				$text = eregi_replace("_BORDER=", "_ID=", $text);

				$final_text .= "	$text\n";

			} // End Loop Through Each Text Area Line Feed ($q)
			//exit;

		$text = $final_text;
//		echo "(".$text.")<br>";
//  		echo "<textarea name=\"textarea\" style=\" width: 600px; height: 200px;\">".$text." </textarea><br><br>\n";
//  		exit;
		// ==================================================

		$formatSpacer = "          ";
		$droparea .= "$spacer\n";
		$droparea .= $formatSpacer."\n\n";
		$droparea .= "\n\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">\n";
		$droparea .= " <tr>\n";
		$droparea .= "  <td align=\"left\" valign=\"top\" class=\"sohotext\" width=\"100%\">";
		$droparea .= "   ".$text."\n";
		$droparea .= "  </td>\n";
		$droparea .= " </tr>\n";
		$droparea .= "</table>\n";
		$droparea .= $formatSpacer."\n\n";

	}

	########################################
	#### AUDIO FILE DOWNLOAD OBJECT		####
	########################################

	if (eregi("##MP3", $thisobj)) {

			$tmp = eregi("<!-- ##MP3;(.*)## -->", $thisobj, $out);
			$audio_file = $out[1];

			$image = "client/download_icon.gif";
			$userfile = "$doc_root/download_icon.gif";
//			if (file_exists("$userfile")) {
//				@unlink($userfile);
//			}
//			@copy($image, $userfile);

			$this_display = strtoupper($audio_file);

			$clientname = "$doc_root/media/$audio_file";
			$size = filesize($clientname);

			 if ($size >= 1048576) {
				$size = round($size/1048576*100)/100;
				$size = $size . "Mb";
			 } elseif ($size >= 1024) {
				$size = round($size/1024*100)/100;
				$size = $size . "K";
			 } else {
				$size = $size . "Bytes";
			 }

			if (eregi("\.mp3", $audio_file)) {
				$audiolink = "media/$audio_file";
			} else {
				$audiolink = "pgm-download_media.php?name=$audio_file";
			}

			$droparea .= "<table border=\"0\" cellpadding=\"2\" cellspacing=\"0\" width=\"199\" align=\"center\">\n<tr>\n<td align=\"left\" valign=\"top\" class=\"text\">";
			$droparea .= "<a href=\"".$audiolink."\"><img src=\"sohoadmin/program/modules/page_editor/client/download_icon.gif\" border=\"0\" width=\"20\" height=\"19\" hspace=\"2\" vspace=\"2\" align=\"absmiddle\">\n";
			$droparea .= "$this_display</a> <FONT STYLE='font-size: 7pt;'>[$size]</font></td>\n</tr>\n</table>\n";

	}

	########################################
	#### CUSTOM HTML OBJECT				####
	########################################

	if (eregi("##CUSTOMHTML", $thisobj)) {

		$tmp = eregi("<!-- ##CUSTOMHTML;(.*)## -->", $thisobj, $out);
		$custom_file = $out[1];

			if (eregi("\.inc", $custom_file) || eregi("\.php", $custom_file) || eregi("\.html", $custom_file) ) {
 				$droparea .= "<!-- ##MIKEINC;$custom_file## -->\n\n";
			} else {
				$filename = "$doc_root/media/$custom_file";
				$file = fopen("$filename", "r");
					$thisCode = fread($file,filesize($filename));
				fclose($file);
				$formlines = split("\n", $thisCode);
				$nFLines = count($formlines);
				for ($j=0;$j<=$nFLines;$j++) {

					$formlines[$j] = eregi_replace("<html>", "", $formlines[$j]);
					$formlines[$j] = eregi_replace("<head>", "", $formlines[$j]);
					$formlines[$j] = eregi_replace("<title>", "<!-- ", $formlines[$j]);
					$formlines[$j] = eregi_replace("</title>", " -->", $formlines[$j]);
					$formlines[$j] = eregi_replace("</head>", "", $formlines[$j]);
					if (eregi("<body", $formlines[$j])) {
						$formlines[$j] = "";
					}
					$formlines[$j] = eregi_replace("</body>", "", $formlines[$j]);
					$formlines[$j] = eregi_replace("</html>", "", $formlines[$j]);

					$formlines[$j] = eregi_replace("img src=\"", "img src=\"images/", $formlines[$j]);
					$formlines[$j] = eregi_replace("background=\"", "background=\"images/", $formlines[$j]);

					$formlines[$j] = eregi_replace("images/images/", "images/", $formlines[$j]);
					$formlines[$j] = eregi_replace("images/\"images/", "images/", $formlines[$j]);

					$droparea .= $formlines[$j]."\n";
				} // END FOR

			}

	}

	########################################
	#### ADOBE LINK OBJECT				####
	########################################

	if (eregi("##ADOBELINK##", $thisobj)) {

		$image = "client/adobe_link.gif";
		$userfile = "$doc_root/adobe_link.gif";
//		if (file_exists("$userfile")) {
//			@unlink($userfile);
//		}
//		@copy($image, $userfile);

		$droparea .= "$spacer\n$spacer\n";
		$droparea .= "<div align=\"center\"><a href=\"http://www.adobe.com/products/acrobat/readstep.html\" target=\"_blank\"><img src=\"sohoadmin/program/modules/page_editor/client/adobe_link.gif\" border=0 width=71 height=25></a></div>\n";

	}

	########################################
	#### FLASH LINK OBJECT				####
	########################################

	if (eregi("##FLASHLINK##", $thisobj)) {

		$image = "client/flash_link.gif";
		$userfile = "$doc_root/flash_link.gif";
//		if (file_exists("$userfile")) {
//			@unlink($userfile);
//		}
//		@copy($image, $userfile);

		$droparea .= "$spacer\n$spacer\n";
		$droparea .= "<div align=\"center\"><a href=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\" target=\"_blank\"><img src=\"sohoadmin/program/modules/page_editor/client/flash_link.gif\" border=0 width=71 height=25></a></div>\n";
		$flashpass = 1;
	}

	########################################
	#### WINAMP LINK OBJECT				####
	########################################

	if (eregi("##WINAMPLINK##", $thisobj)) {

		$image = "client/winamp_link.gif";
		$userfile = "$doc_root/winamp_link.gif";
//		if (file_exists("$userfile")) {
//			@unlink($userfile);
//		}
//		@copy($image, $userfile);

		$droparea .= "$spacer\n$spacer\n";
		$droparea .= "<div align=\"center\"><a href=\"http://www.winamp.com/?partner=http://www.soholaunch.com\" target=\"_blank\"><img src=\"sohoadmin/program/modules/page_editor/client/winamp_link.gif\" border=0 width=71 height=25></a></div>\n";

	}

	########################################
	#### QUICKTIME LINK OBJECT			####
	########################################

	if (eregi("##QUICKTIMELINK##", $thisobj)) {

		$image = "client/quicktime_link.gif";
		$userfile = "$doc_root/quicktime_link.gif";
//		if (file_exists("$userfile")) {
//			@unlink($userfile);
//		}
//		@copy($image, $userfile);

		$droparea .= "$spacer\n$spacer\n";
		$droparea .= "<div align=\"center\"><a href=\"http://www.apple.com/quicktime/download/\" target=\"_blank\"><img src=\"sohoadmin/program/modules/page_editor/client/quicktime_link.gif\" border=0 width=71 height=25></a></div>\n";

	}

	########################################
	#### CALENDAR NORMAL OBJECT			####
	########################################

	if (eregi("##CALENDAR", $thisobj)) {

		$tmp = eregi("<!-- ##CALENDAR;(.*)## -->", $thisobj, $out);
		$tmpc = split(";", $out[1]);

		$display_type = $tmpc[0];
		$category_use = $tmpc[1];

		if (eregi("w", $display_type)) {
			$droparea .= "\n\n<!-- CALENDAR MODULE INSERT -->\n\n";
			$droparea .= "<!-- ##CALENDAR-WEEKLY-VIEW;$category_use## -->\n\n";
			$droparea .= "<!-- END CALENDAR MODULE INSERT -->\n\n\n";
		}

		if (eregi("m", $display_type)) {
			$droparea .= "\n\n<!-- CALENDAR MODULE INSERT -->\n\n";
			$droparea .= "<!-- ##CALENDAR-ONEMONTH-VIEW;$category_use## -->\n\n";
			$droparea .= "<!-- END CALENDAR MODULE INSERT -->\n\n\n";
		}

		if (eregi("sys", $display_type)) {
			$droparea .= "\n\n<!-- CALENDAR MODULE INSERT -->\n\n";
			$droparea .= "<!-- ##CALENDAR-SYSTEM## -->\n\n";
			$droparea .= "<!-- END CALENDAR MODULE INSERT -->\n\n\n";
		}

		if (eregi("SINGLE", $display_type)) {
			$droparea .= "\n\n<!-- CALENDAR MODULE INSERT -->\n\n";
			$droparea .= "<!-- ##CALENDAR-SINGLE_CAT_SYSTEM;$category_use## -->\n\n";
			$droparea .= "<!-- END CALENDAR MODULE INSERT -->\n\n\n";
		}
	}

	########################################
	#### DATE STAMP OBJECT				   ####
	########################################

	if (eregi("##DATESTAMP", $thisobj)) {
		$droparea .= "<div align=\"center\">$spacer<!-- ##PHPDATE## --></div>\n";
	}

	########################################
	#### VIDEO OBJECT					      ####
	########################################

	if (eregi("##VIDEO", $thisobj)) {

		$videolocation = "media";

		$tmp = eregi("<!-- ##VIDEO;(.*)## -->", $thisobj, $out);
		$tmp = $out[1];

		$tmp = split(";", $tmp);

		$video_file = $tmp[0];
		$video_width = $tmp[1];
		$video_height = $tmp[2];
		$video_setW = $tmp[3];
		$video_setH = $tmp[4];

		if($video_setH != "" && $video_setW != "") {
   		$video_width = $video_setW;
   		$video_height = $video_setH;
   	}
//		echo $video_file."<br><br>";
//		echo $video_width."<br><br>";
//		echo $video_height."<br><br>";
//		exit;

		if ($video_width == "") { $video_width="320"; }
		if ($video_height == "") { $video_height="240"; }

		$openw = $video_width + 20;
		$openh = $video_height + 40;

		// ------------------------------------------------------------------
		// If this is a QuickTime(tm) Movie, copy the 'Click Here to start'
		// Movie file to document root so we can pre-load player for viewers
		// ------------------------------------------------------------------

		if (eregi("\.mov", $video_file)) {
			$image = "client/quicktime_start.mov";
			$userfile = "$doc_root/quicktime_start.mov";
//			if (file_exists("$userfile")) {
//				@unlink($userfile);
//			}
//			@copy($image, $userfile);
		}

		// ------------------------------------------------------------------
		// Create 'View Video Button' for page display
		// ------------------------------------------------------------------
		$droparea .= "\n\n";
		$droparea .= "<div align=\"center\"><form name=vidnet>\n";
		$droparea .= "<input type=button value=\" ".lang("View Video")." \" class=\"FormLt1\" onclick=\"window.open('pgm-view_video.php?name=".$video_file."&w=".$video_width."&h=".$video_height."','videowin','width=".$openw.",height=".$openh.",location=no, menubar=no, titlebar=no, resizable=no, status=no, toolbar=no');\">\n";
		$droparea .= "</form></div>\n\n";

	}


	########################################
	#### POP-UP WINDOW OBJECT			####
	########################################

	if (eregi("##POPUP", $thisobj)) {

		$tmp = eregi("<!-- ##POPUP;(.*)## -->", $thisobj, $out);
		$tmp = $out[1];

		$tmp = split(";", $tmp);
		$ppagename = $tmp[0];
		$pwinwidth = $tmp[1];
		$pwinheight = $tmp[2];

		$droparea .= "\n\n";
		$droparea .= "<SCRIPT Language=Javascript>\n window.open('".pagename($ppagename)."','popupwindow','width=$pwinwidth,height=$pwinheight,location=no, menubar=no, titlebar=no, resizable=yes, status=no, toolbar=no, scrollbars=yes');\n</SCRIPT>";
		$droparea .= "\n\n";

	}


	########################################
	#### MACROMEDIA FLASH OBJECT		####
	########################################

	if (eregi("##FLASH", $thisobj) && $flashpass != 1) {

		$flashlocation = "media";


		$tmp = eregi("<!-- ##FLASH;(.*)## -->", $thisobj, $out);
		$flash_file = $out[1];
		$flash_file = split(";", $flash_file);
		if($flash_file[3] != "" && $flash_file[4] != ""){
         $flash_file[1] = $flash_file[3];
         $flash_file[2] = $flash_file[4];
      }

		$flash_width = "WIDTH=$flash_file[1]";

		if ($flash_file[2] != "") {
			$flash_height = "HEIGHT=$flash_file[2]";
		} else {
			$flash_height = "";
		}

		if ($flash_file[1] == "" && $flash_file[2] == "") {
			$flash_width = "WIDTH=100%";
			$flash_height = "";
		}

		//if($flash_width != "" &&

		$droparea = "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://active.macromedia.com/flash2/cabs/swflash.cab#version=4,0,0,0\" ID=thisflashobj $flash_width $flash_height>\n";
		$droparea .= "<PARAM NAME=movie VALUE=\"$flashlocation/$flash_file[0]\">\n<PARAM NAME=loop VALUE=true>\n<PARAM NAME=menu VALUE=false>\n<param name=\"wmode\" value=\"transparent\">\n<PARAM NAME=quality VALUE=high>\n";
		$droparea .= "<EMBED wmode=\"transparent\" src=\"$flashlocation/$flash_file[0]\" loop=true menu=false quality=high $flash_width $flash_height TYPE=\"application/x-shockwave-flash\" PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\"></EMBED>\n";
		$droparea .= "</OBJECT>\n";

	}

	########################################
	#### CUSTOM FORM LIBRARY OBJECT    	####
	########################################

	if (eregi("##CONTACTFORM", $thisobj)) {

		$tmp = eregi("<!-- ##CONTACTFORM;(.*)## -->", $thisobj, $out);
		$ctemp = $out[1];
		$mtemp = split(";", $ctemp);

		$send_to = $mtemp[0];
		$database_file = $mtemp[1];
		$formfile = $mtemp[2];

		// =====================================================
		// === COMPENSATE FOR NEW "UNHIDDEN" DATA
		// =====================================================

		$rFrom = $mtemp[3];
		$rSubject = $mtemp[4];
		$rFile = $mtemp[5];
		$rClose = $mtemp[6];
		$rPageGo = $mtemp[7];

		// =====================================================

		$droparea .= "\n\n<!-- \n\n";
		$droparea .= "###########################################################\n";
		$droparea .= "### ADD FORM NOW\n";
		$droparea .= "###########################################################\n\n";
		$droparea .= "--> \n\n<DIV ALIGN=CENTER>\n\n";

		$filename = $formfile;	// Modified for IIS and Version 4.5
		//cameron windows form fix
		if(eregi("WIN", PHP_OS) && !file_exists($filename)){
			$filename = str_replace('\\', '/', $formfile);
			if(!file_exists($filename)){
				$filename = $doc_root.'/'.$formfile;
				if(!file_exists($filename)){
					$filename = 'media/'.basename($formfile);
					if(!file_exists($filename)){
						$filename = $_SESSION['doc_root'].'/media/'.basename($formfile);
					}
				}
			}
		}

		if(eregi("WIN", PHP_OS) && !file_exists($filename)){
			$filename = str_replace('\\', '/', $formfile);
			if(!file_exists($filename)){
				$filename = $doc_root.'/'.$formfile;
				if(!file_exists($filename)){
					$filename = 'media/'.basename($formfile);
					if(!file_exists($filename)){
						$filename = $_SESSION['doc_root'].'/sohoadmin/program/modules/page_editor/formlib/forms/'.basename($formfile);
						if(!file_exists($filename)){
							$filename = $_SESSION['doc_root'].'/media/'.basename($formfile);
						}
					}
				}
			}
		}

		$file = fopen("$filename", "r");
			$thisCode = fread($file,filesize($filename));
		fclose($file);

		preg_match_all("/name=\"(.+?)\"/i", $thisCode, $form_field_names_ar);
		$form_fs = array();
		foreach($form_field_names_ar['1'] as $fn_val){
			if(!preg_match('/\[\]$/', $fn_val) && in_array($fn_val, $form_fs)){
				//echo 'double: '.$fn_val."<br/>";
				$thisCode = str_replace('name="'.$fn_val.'"', 'name="'.$fn_val.'[]"', $thisCode);
				$form_fs[] = $fn_val;
			} else {
				$form_fs[] = str_replace('[]', '', $fn_val);
			}
		}


		$formlines = split("\n", $thisCode);
		$nFLines = count($formlines);

		$startup = 0;

		# Generate unique token (Mantis 414)
		$unique_token = md5(time());

		for ($j=0;$j<=$nFLines;$j++) {

			$formlines[$j] = ltrim($formlines[$j]);  // Make form spacing even on final HTML output
			$formlines[$j] = rtrim($formlines[$j]);  // Make form spacing even on final HTML output

			if (eregi("<form ", $formlines[$j])) {
				$startup = 1;
				$formlines[$j] .= "\n\n          <input type=hidden id=\"EMAILTO\" name=EMAILTO value=\"".str_replace('@', '>', $send_to)."\">\n";
				$formlines[$j] .= "          <input type=hidden name=PAGEREQUEST value=\"$currentPage\">\n";
				$formlines[$j] .= "          <input type=hidden name=DATABASE value=\"$database_file\">\n";

				if ($rClose == "yes") {
					$formlines[$j] .= "          <input type=hidden name=SELFCLOSE value=\"yes\">\n";
				}

				$formlines[$j] .= "          <input type=hidden name=PAGEGO value=\"$rPageGo\">\n";
				$formlines[$j] .= "          <input type=hidden name=RESPONSEFROM value=\"$rFrom\">\n";
				$formlines[$j] .= "          <input type=hidden name=SUBJECTLINE value=\"$rSubject\">\n";
				$formlines[$j] .= "          <input type=hidden name=RESPONSEFILE value=\"media/".basename($rFile)."\">\n";
				$formlines[$j] .= "          <input type=hidden name=CUST_FILENAME value=\"".basename($filename)."\">\n\n";
				$formlines[$j] .= "          <input type=hidden name=\"UNIQUETOKEN\" value=\"".$unique_token."\">\n\n";

			}

			$formlines[$j] = "          " . $formlines[$j];	// final HTML output is indented 10 spaces for looks

			// *****************************************************************************************
			// For legacy code, forms where submitted to "email.php3" -- now for open source release,
			// all client side runtime scripts have been renamed for clarity when viewing via FTP, etc.
			// So, let's make sure that the legacy forms will conform to the new naming conventions.
			// ******************************************************************************************

			$formlines[$j] = str_replace("email.php3", "pgm-form_submit.php", $formlines[$j]);

			if ($startup == 1) {
				$droparea .= $formlines[$j]."\n";
			}

			if (eregi("</form>", $formlines[$j])) {
				$startup = 0;
			}

		}

		$droparea .= "\n\n</DIV>\n\n\n";

      # Mantis 412
      $droparea .= "<!-- #UNIQUETOKEN~~".$unique_token."~~#--->\n\n";

		$droparea .= "<!-- end form ---> \n\n";
//		$droparea .= "###########################################################\n";
//		$droparea .= "### END FORM \n";
//		$droparea .= "###########################################################\n\n";
//		$droparea .= "--> \n\n\n";
	}


	########################################
	#### SHOPPING CART SEARCH OBJECT	####
	########################################

	if (eregi("##CARTSEARCH", $thisobj)) {

      # Pull css rules for cart system
      include_once($_SESSION['docroot_path']."sohoadmin/client_files/shopping_cart/pgm-shopping_css.inc.php"); // Defines $module_css

		$droparea .= $module_css;

		$droparea .= "\n\n<!-- \n\nBEGIN SHOPPING CART SEARCH OBJECT \n\n-->\n\n";

		$result = mysql_query("SELECT * FROM cart_options");
		$OPTIONS = mysql_fetch_array($result);

		$droparea .= "<SCRIPT LANGUAGE=JAVASCRIPT>\n\n";
		$droparea .= "function browse_cart() { \n";
		$droparea .= "     window.document.location = 'shopping/start.php?browse=1'; \n";
		$droparea .= "} \n\n";
		$droparea .= "</SCRIPT>\n\n";

		$droparea .= "<div align=\"center\" id=\"shopping_module\">\n";
		$droparea .= "<form method=\"post\" action=\"shopping/start.php\">\n";
		$droparea .= "<input type=\"hidden\" name=\"find\" value=\"1\">\n";

		$droparea .= "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" width=\"90%\" id=\"shopping-searchbrowse_object\" class=\"shopping-selfcontained_box\">\n";
		$droparea .= "<tr>\n";
		$droparea .= "<th align=\"left\" valign=\"top\">\n";
		$droparea .= " ".lang("Search Products").":\n";
		$droparea .= "</th>\n";
		$droparea .= "</tr>\n";

		$droparea .= "<tr>\n<td align=\"center\" valign=\"top\" class=\"smtext\">\n";
			$droparea .= "<input type=\"text\" name=\"searchfor\" class=\"textfield\" style='font-family: Arial; font-size: 7pt; width: 60%;'>";
			$droparea .= "&nbsp;<input type=\"submit\" class=\"smtext\" style='width: 60px; cursor: hand;' value=\"Find\">\n";
		$droparea .= "</td></tr><tr><td align=\"center\" valign=\"top\" class=\"smtext\">\n";
			$droparea .= "<input type=\"button\" class=\"sohotext button\" value=\"".lang("Browse Categories")."\" style='cursor: hand;' onclick=\"browse_cart();\">\n";
		$droparea .= "</td></tr></table></form>\n\n";

		// $droparea .= "<img src=\"cart_header.gif\" width=199 height=21 border=0></td>\n</tr>\n";
		// $droparea .= "<tr>\n<td align=center valign=middle><img src=\"spacer.gif\" width=199 height=5 border=0><BR>&nbsp;\n";
		// $droparea .= "<input type=image src=\"cart_find.gif\" align=absmiddle width=43 height=21 border=0></td>\n</tr>\n";
		// $droparea .= "<tr>\n<td><img src=\"spacer.gif\" width=199 height=8 border=0></td>\n</tr>\n";
		// $droparea .= "<tr>\n<td align=center valign=middle><a href=\"shopping/start.php?browse=1\"><img src=\"cart_browse.gif\" border=0 width=117 height=21></a><BR><img src=\"spacer.gif\" width=199 height=8 border=0></td>\n</tr>\n</table>\n</form>\n</div>\n";

		$droparea .= "\n<!-- \n\nEND SHOPPING CART SEARCH OBJECT \n\n-->\n\n";

	} // End if eregi ##CARTSEARCH

   ########################################
   #### SITEPAL                        ####
   ########################################
  // include("../sitepal/page_editor/object_write-confile_data.php");

	########################################
	#### SINGLE SKU PROMO OBJECT		####
	########################################

	if (eregi("##SINGLESKU", $thisobj)) {

		$tmp = eregi("<!-- ##SINGLESKU;(.*)## -->", $thisobj, $out);
		$sku_number = $out[1];

		// Added to replace all code re: Bugzilla #21
		$droparea .= "\n\n<!--##SINGLESKU;$sku_number##-->\n\n";

		/* Moved build code to runtime side to address Bugzilla #21

		$droparea .= "\n\n<!-- $sku_number PROMOTION -->\n\n";

		$result = mysql_query("SELECT * FROM cart_options");
		$OPTIONS = mysql_fetch_array($result);

		$result = mysql_query("SELECT PRIKEY, PROD_SKU, PROD_NAME, PROD_DESC, PROD_UNITPRICE, PROD_THUMBNAIL FROM cart_products WHERE PROD_SKU = '$sku_number'");
		$PROD = mysql_fetch_array($result);

		// Find thumbnail and deal with it
		// --------------------------------------------------

			$imagesrc = chop($PROD[PROD_THUMBNAIL]);
			$directory = "$doc_root/images";

			if (file_exists("$directory/$imagesrc")) {
				$tmparray = getImageSize("$directory/$imagesrc");
				$origw = $tmparray[0];
				$origh = $tmparray[1];
				$WH = "width=$origw height=$origh";

				if ($origw > 99) {
					$calc = 99/$origw;
					$hcalc = $origh*$calc;
					$nheight = round($hcalc);
					$WH = "width=99 height=$nheight";
				}

				$IMAGE_PLACEMENT = "<a href=\"shopping/pgm-more_information.php?id=$PROD[PRIKEY]&=SID\"><img src=\"images/$imagesrc\" $WH border=0 align=left vspace=2 hspace=3 alt=\"Click Here for Product Details!\"></A>";

			} else {

				$IMAGE_PLACEMENT = "";
				$txt_format = "";

			} // End confirm image exists

		// ------------------------------------------------------------------------
		// BUILD DISPLAY HTML FOR SAVE ROUTINE
		// ------------------------------------------------------------------------

		$droparea .= "<FORM METHOD=GET ACTION=\"shopping/pgm-more_information.php\">\n";
		$droparea .= "<INPUT TYPE=HIDDEN NAME=id VALUE=\"$PROD[PRIKEY]\">\n";

		$droparea .= "<table border=0 cellpadding=3 cellspacing=0 width=90% style='border: 1px inset black;'>\n";
		$droparea .= "<tr>\n";
		$droparea .= "<td align=left valign=top class=text bgcolor=$OPTIONS[DISPLAY_HEADERBG]>\n";

			$droparea .= "<B><FONT FACE=VERDANA COLOR=$OPTIONS[DISPLAY_HEADERTXT]>$PROD[PROD_NAME]</FONT></B>\n";
			$droparea .= "</td>\n";
			$droparea .= "<td align=right valign=top class=text bgcolor=$OPTIONS[DISPLAY_HEADERBG]>\n";
			$droparea .= "<B><FONT FACE=VERDANA COLOR=$OPTIONS[DISPLAY_HEADERTXT]>$$PROD[PROD_UNITPRICE]</FONT></B>\n";

		$droparea .= "</td>\n";
		$droparea .= "</tr><tr>\n";
		$droparea .= "<td colspan=2 align=left valign=top class=text BGCOLOR=WHITE>\n";

			$droparea .= "$IMAGE_PLACEMENT";
			$droparea .= "<FONT STYLE='font-size: 8pt;'>$PROD[PROD_DESC]<BR><DIV ALIGN=LEFT>\n";
			$droparea .= "[ <a href=\"shopping/pgm-more_information.php?id=$PROD[PRIKEY]&=SID\">More Information</a> ]</DIV></FONT>\n";

		$droparea .= "</td>\n";
		$droparea .= "</tr><tr>\n";
		$droparea .= "<td colspan=2 align=center valign=middle>\n";

			$droparea .= "<input type=submit class=FormLt1 value=\" Buy Now! \">\n";

		$droparea .= "</td></tr></table>\n";
		$droparea .= "</form>\n";

		*/

	} // End Single Sku Promo


   eval(hook("pe-confile_object_data", basename(__FILE__)));

	
	########################################
	#### SUPER SEARCH PLUGIN            ####
	########################################
	
	if (eregi("##SUPERSEARCH", $thisobj)) {
	   $tmp = eregi("<!-- ##SUPERSEARCH## -->", $thisobj, $out);
	   $dataname = $out[1];
	   $droparea .= "\n\n<!-- ##SUPERSEARCH## -->\n\n";
	}

} // End $numobj Loop

$droparea .= "</div>\n";

// ---------------------------------------------------------
// INSERT MAC OS DISPLAY FIX
// ---------------------------------------------------------

$droparea = str_replace("&#8217;", "'", $droparea);
$droparea = str_replace("&#8230;", "...", $droparea);

// ---------------------------------------------------------

?>