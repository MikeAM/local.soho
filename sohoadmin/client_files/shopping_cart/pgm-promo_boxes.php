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
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston 
## Copyright 2003-2007 Soholaunch.com, Inc.
## All Rights Reserved.
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

###############################################################################
## This script pulls from the blog dB tables to create the promo and newsbox
## features enabled by the precense of particular variables in template HTML
###############################################################################


$newsbox = "";
$newsbox_flex = "";

$promohdr1 = "";
$promotxt1 = "";

$promohdr2 = "";
$promotxt2 = "";

$promohdr3 = "";
$promotxt3 = "";

//$news_cat = $getSpec['news_cat'];
//$promo_cat = $getSpec['promo_cat'];

if ( $news_cat == "" ) { $news_cat = 1; }
if ( $promo_cat == "" ) { $promo_cat = 2; }

$box1 = "";
$box2 = "";
$box3 = "";
$box4 = "";

$boxArray = split(";", $boxCheck);
$arrLen = count($boxArray);
//$box1 .= "(".$arrLen.")<br>";

#######################################################
### Build #BOX# display     	                      ###
#######################################################

for($x=0;$x<$arrLen;$x++){
   if (eregi("box", $boxArray[$x]) && !eregi("news", $boxArray[$x]) && !eregi("title", $boxArray[$x])){

      $boxNum = eregi_replace("box", "", $boxArray[$x]);
   		$fileBox = $promoFile.$boxNum;
   		//echo "(".$fileBox.")";

      $selBox = mysql_query("SELECT * FROM promo_boxes WHERE FILE = '$fileBox'");
      $num_found = mysql_num_rows($selBox);
      
      if($num_found == 0){
         $selBox = mysql_query("SELECT * FROM promo_boxes WHERE BOX = 'box25'");
      }
      $getBox = mysql_fetch_array($selBox);

      $settings['CONTENT'] = unserialize($getBox['CONTENT']);
      $settings['NUM_DISPLAY'] = unserialize($getBox['NUM_DISPLAY']);
      $settings['DISP_TITLE'] = unserialize($getBox['DISP_TITLE']);
      $settings['DISP_CONTENT'] = unserialize($getBox['DISP_CONTENT']);
      $settings['DISP_DATE'] = unserialize($getBox['DISP_DATE']);
      $settings['DISP_MORE'] = unserialize($getBox['DISP_MORE']);
      $settings['SETTINGS'] = unserialize($getBox['SETTINGS']);

      if($settings['CONTENT']['display'] == "off"){
      	##### BOX DISPLAY ALL OFF #####
      	${"box".$boxNum} .= "";
      }else{
				##### BOX DISPLAY ON #####

	      $selBlogCat = mysql_query("SELECT prikey FROM blog_category WHERE CATEGORY_NAME = '".$settings['CONTENT']['content']."'");
	      $getBlogCat = mysql_fetch_array($selBlogCat);

	      $selBlog = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '".$getBlogCat['prikey']."' ORDER BY BLOG_DATE DESC LIMIT 1");
	      $getBlog = mysql_fetch_array($selBlog);

	      # **** TEST BOX VALUES ****
	//      ${"box".$boxNum} .= "The box (".$boxArray[$x].")<br>";
	//      foreach($getBox as $var=>$val){
	//         ${"box".$boxNum} .= "var = (".$var.") val = (".$val.")<br>\n";
	//      }



	      #######################################################
				### Box Settings				     	                      ###
				#######################################################

	      ### Border settings
	      #######################################################
	      if($settings['DISP_TITLE']['border'] == "on"){
	         $Tborder = "1px solid #666666";
	         $TborderStyle = "solid solid solid solid";
	      }else{
	         $Tborder = "";
	         $Tborder = "1px solid #666666";
	         $TborderStyle = "none none none none";
	      }
	      if($settings['DISP_CONTENT']['border'] == "on"){
	      	$Cborder = "1px solid #666666";
	      	 	if($settings['DISP_TITLE']['border'] == "on"){
		         	$CborderStyle = "none solid solid solid";
		        }else{
		         	$CborderStyle = "solid solid solid solid";
		        }
	      }else{
	         $Cborder = "";
	         $CborderStyle = "none none none none";
	      }
	      if($settings['DISP_MORE']['border'] == "on"){
	      	$Mborder = "1px solid #666666";
	      	 	if($settings['DISP_CONTENT']['border'] == "on"){
		         	$MborderStyle = "none solid solid solid";
		        }else{
		         	$MborderStyle = "solid solid solid solid";
		        }
	      }else{
	         $Mborder = "";
	         $MborderStyle = "none none none none";
	      }
	      if($settings['DISP_DATE']['border'] == "on"){
	      	$Dborder = "1px solid #666666";
	      	 	if($settings['DISP_MORE']['border'] == "on" && $settings['DISP_DATE']['position'] == "dateBottom"){
		         	$DborderStyle = "none solid solid solid";
		        }elseif($settings['DISP_TITLE']['border'] == "on" && $settings['DISP_DATE']['position'] == "dateTop"){
		         	$DborderStyle = "solid solid none solid";
		        }else{
		         	$DborderStyle = "solid solid solid solid";
		        }
	      }else{
	         $Dborder = "";
	         $DborderStyle = "none none none none";
	      }

				### Date display settings
				#######################################################

	//			// Display Date type
	//			$DateFirst = "none";
	//			$DateLast = "none";
	//			if($settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on"){
	//				$DateFirst = "inline";
	//			}elseif($settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on"){
	//				$DateLast = "inline";
	//			}

				// Date Italic
				$dateItalic = "";
				if($settings['DISP_DATE']['fontStyle'] == "on"){
					$dateItalic = "italic";
				}

				// Date Format
	      if($settings['DISP_DATE']['format'] == "allNum"){
	      	$timestamp = strtotime($getBlog['BLOG_DATE']);
	      	$dateDisp = date('Y-j-n', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "allNum2"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('n-j-Y', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "full"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('F-j-Y', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "full2"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('j-F-Y', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "half"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('D-j-Y', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "half2"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('j-M-Y', $timestamp);
	      }

				// Date Weight
				$chkDateWeightNormal = "";
				$chkDateWeightBold = "selected";
				$chkDateWeight = "bold";
				if($settings['DISP_DATE']['weight'] == "normal"){
				   $chkDateWeightNormal = "selected";
				   $chkDateWeightBold = "";
				   $chkDateWeight = "normal";
				}

	      # Pre-format news teaser text
	      if($settings['NUM_DISPLAY']['chars'] != ""){
		      $disTease = strip_tags($getBlog['BLOG_DATA'], '<br>');
		      $disTease = substr("$disTease", 0, $settings['NUM_DISPLAY']['chars']);
		    }else{
		    	$disTease = $getBlog['BLOG_DATA'];
		    }

	//			${"box".$boxNum} .= $boxArray[$x]."<br><br>";
	//      ${"box".$boxNum} .= $fileBox;
	//      ${"box".$boxNum} .= "<b>BOX ".$boxNum."</b><br>\n";


				######################################################################
				### Build display for the latest entry(user settings)              ###
				######################################################################

	      ${"box".$boxNum} .= "\n\n\n<!--- BEGIN BOX ".$boxNum." --->\n\n\n";
	      if($settings['CONTENT']['type'] == "latest" && $settings['SETTINGS']['template'] != "on"){

	         ${"box".$boxNum} .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	         if($settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
	            ${"box".$boxNum} .= "   ".$dateDisp."\n";
	            ${"box".$boxNum} .= "  </td>\n";
	            ${"box".$boxNum} .= " </tr>\n";
	         }
	         if($settings['DISP_TITLE']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_TITLE']['align']."\" style=\"font-weight: ".$settings['DISP_TITLE']['weight']."; border: ".$Tborder."; border-style: ".$TborderStyle."; \">\n";
	            if($settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on"){
	            	${"box".$boxNum} .= " <span id=\"dateFirst\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
	            }
	            ${"box".$boxNum} .= " ".$getBlog['BLOG_TITLE']."\n";
	            if($settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on"){
								${"box".$boxNum} .= " <span id=\"dateLast\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
							}
	            ${"box".$boxNum} .= " </td></tr>\n";
	         }
	         if($settings['DISP_CONTENT']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td style=\"border: ".$Cborder."; border-style: ".$CborderStyle.";\">\n";
	            ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
	            ${"box".$boxNum} .= "  </td>\n";
	            ${"box".$boxNum} .= " </tr>\n";
	         }
	         if($settings['DISP_MORE']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_MORE']['align']."\" style=\"font-weight: ".$settings['DISP_MORE']['weight']."; font-size: 10px; border: ".$Mborder."; border-style: ".$MborderStyle.";\">\n";
	            ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".$settings['DISP_MORE']['text']."</a>\n";
	            ${"box".$boxNum} .= "  </td>\n";
	            ${"box".$boxNum} .= " </tr>\n";
	         }
	         if($settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
	            ${"box".$boxNum} .= "   ".$dateDisp."\n";
	            ${"box".$boxNum} .= "  </td>\n";
	            ${"box".$boxNum} .= " </tr>\n";
	         }
	         ${"box".$boxNum} .= "</table>\n";
	      }

				##########################################################################
				### Build display for the latest entry(template settings)              ###
				##########################################################################

	      //${"box".$boxNum} .= "\n\n\n<!--- BEGIN BOX ".$boxNum." --->\n\n\n";
	      if($settings['CONTENT']['type'] == "latest" && $settings['SETTINGS']['template'] == "on"){

	         ${"box".$boxNum} .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	         if($settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
	            ${"box".$boxNum} .= "   ".$dateDisp."\n";
	            ${"box".$boxNum} .= "  </td>\n";
	            ${"box".$boxNum} .= " </tr>\n";
	         }
	         if($settings['DISP_TITLE']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td class=\"boxTitle".$boxNum."\">\n";
	            if($settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on"){
	            	${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
	            }
	            ${"box".$boxNum} .= " ".$getBlog['BLOG_TITLE']."\n";
	            if($settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on"){
								${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
							}
	            ${"box".$boxNum} .= " </td></tr>\n";
	         }
	         if($settings['DISP_CONTENT']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td class=\"boxContent".$boxNum."\">\n";
	            ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
	            ${"box".$boxNum} .= "  </td>\n";
	            ${"box".$boxNum} .= " </tr>\n";
	         }
	         if($settings['DISP_MORE']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td class=\"boxReadMore".$boxNum."\">\n";
	            ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".$settings['DISP_MORE']['text']."</a>\n";
	            ${"box".$boxNum} .= "  </td>\n";
	            ${"box".$boxNum} .= " </tr>\n";
	         }
	         if($settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on"){
	            ${"box".$boxNum} .= " <tr>\n";
	            ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
	            ${"box".$boxNum} .= "   ".$dateDisp."\n";
	            ${"box".$boxNum} .= "  </td>\n";
	            ${"box".$boxNum} .= " </tr>\n";
	         }
	         ${"box".$boxNum} .= "</table>\n";
	      }


				#######################################################
				### Build display for multiple blog output          ###
				#######################################################

	      $blogCnt = 1;
	      ${"box".$boxNum} .= "<table width=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
	      $selBlog = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '".$getBlogCat['prikey']."' ORDER BY BLOG_DATE DESC LIMIT ".$settings['NUM_DISPLAY']['blog']."");
	      while($getBlog = mysql_fetch_array($selBlog)){
				// Date Format
	      if($settings['DISP_DATE']['format'] == "allNum"){
	      	$timestamp = strtotime($getBlog['BLOG_DATE']);
	      	$dateDisp = date('Y-j-n', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "allNum2"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('n-j-Y', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "full"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('F-j-Y', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "full2"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('j-F-Y', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "half"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('D-j-Y', $timestamp);
	      }elseif($settings['DISP_DATE']['format'] == "half2"){
					$timestamp = strtotime($getBlog['BLOG_DATE']);
					$dateDisp = date('j-M-Y', $timestamp);
	      }

	      # Pre-format news teaser text
	      if($settings['NUM_DISPLAY']['chars'] != ""){
		      $disTease = strip_tags($getBlog['BLOG_DATA'], '<br>');
		      $disTease = substr("$disTease", 0, $settings['NUM_DISPLAY']['chars']);
		    }else{
		    	$disTease = $getBlog['BLOG_DATA'];
		    }


				######################################################################
				### Build display for multiple blog output(user settings)          ###
				######################################################################


	         if($settings['CONTENT']['type'] == "muli" && $settings['SETTINGS']['template'] != "on"){
							if($settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on"){
								${"box".$boxNum} .= " <tr>\n";
								${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
								${"box".$boxNum} .= "   ".$dateDisp."\n";
								${"box".$boxNum} .= "  </td>\n";
								${"box".$boxNum} .= " </tr>\n";
							}
							if($settings['DISP_TITLE']['display'] == "on"){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_TITLE']['align']."\" style=\"font-weight: ".$settings['DISP_TITLE']['weight']."; border: ".$Tborder."; border-style: ".$TborderStyle."; \">\n";
							   if($settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on"){
							   		${"box".$boxNum} .= " <span id=\"dateFirst\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
							   }
							   ${"box".$boxNum} .= " ".$getBlog['BLOG_TITLE']."\n";
							   if($settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on"){
								 		${"box".$boxNum} .= " <span id=\"dateLast\" style=\"font-style: ".$dateItalic."; font-weight: ".$chkDateWeight."; font-size: 11px;\">".$dateDisp."</span>\n";
								 }
							   ${"box".$boxNum} .= " </td></tr>\n";
							}
							if($settings['DISP_CONTENT']['display'] == "on"){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td style=\"border: ".$Cborder."; border-style: ".$CborderStyle.";\">\n";
	            	 ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
							   ${"box".$boxNum} .= "  </td>\n";
							   ${"box".$boxNum} .= " </tr>\n";
							}
							if($settings['DISP_MORE']['display'] == "on"){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_MORE']['align']."\" style=\"font-weight: ".$settings['DISP_MORE']['weight']."; font-size: 10px; border: ".$Mborder."; border-style: ".$MborderStyle.";\">\n";
							   ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".$settings['DISP_MORE']['text']."</a>\n";
							   ${"box".$boxNum} .= "  </td>\n";
							   ${"box".$boxNum} .= " </tr>\n";
							}
							if($settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on"){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td align=\"".$settings['DISP_DATE']['align']."\" style=\"font-weight: ".$settings['DISP_DATE']['weight']."; font-style: ".$dateItalic."; border: ".$Dborder."; border-style: ".$DborderStyle.";\">\n";
								 ${"box".$boxNum} .= "   ".$dateDisp."\n";
							   ${"box".$boxNum} .= "  </td>\n";
							   ${"box".$boxNum} .= " </tr>\n";
							}
							if($settings['NUM_DISPLAY']['blog'] > $blogCnt){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td>\n";
								 ${"box".$boxNum} .= "   &nbsp;\n";
							   ${"box".$boxNum} .= "  </td>\n";
							   ${"box".$boxNum} .= " </tr>\n";
							   $blogCnt++;
							}
	         }


				##########################################################################
				### Build display for multiple blog output(template settings)          ###
				##########################################################################


	         if($settings['CONTENT']['type'] == "muli" && $settings['SETTINGS']['template'] == "on"){
							if($settings['DISP_DATE']['position'] == "dateTop" && $settings['DISP_DATE']['display'] == "on"){
								${"box".$boxNum} .= " <tr>\n";
								${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
								${"box".$boxNum} .= "   ".$dateDisp."\n";
								${"box".$boxNum} .= "  </td>\n";
								${"box".$boxNum} .= " </tr>\n";
							}
							if($settings['DISP_TITLE']['display'] == "on"){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td class=\"boxTitle".$boxNum."\">\n";
							   if($settings['DISP_DATE']['position'] == "dateFirst" && $settings['DISP_DATE']['display'] == "on"){
							   		${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
							   }
							   ${"box".$boxNum} .= " ".$getBlog['BLOG_TITLE']."\n";
							   if($settings['DISP_DATE']['position'] == "dateLast" && $settings['DISP_DATE']['display'] == "on"){
								 		${"box".$boxNum} .= " <span class=\"boxDateTitle".$boxNum."\">".$dateDisp."</span>\n";
								 }
							   ${"box".$boxNum} .= " </td></tr>\n";
							}
							if($settings['DISP_CONTENT']['display'] == "on"){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td class=\"boxContent".$boxNum."\">\n";
	            	 ${"box".$boxNum} .= "   ".stripslashes($disTease)."\n";
							   ${"box".$boxNum} .= "  </td>\n";
							   ${"box".$boxNum} .= " </tr>\n";
							}
							if($settings['DISP_MORE']['display'] == "on"){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td class=\"boxReadMore".$boxNum."\">\n";
							   ${"box".$boxNum} .= "   <a href=\"index.php?bShow=".$getBlog['PRIKEY']."&cat=".$getBlogCat['prikey']."\">".$settings['DISP_MORE']['text']."</a>\n";
							   ${"box".$boxNum} .= "  </td>\n";
							   ${"box".$boxNum} .= " </tr>\n";
							}
							if($settings['DISP_DATE']['position'] == "dateBottom" && $settings['DISP_DATE']['display'] == "on"){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td class=\"boxDate".$boxNum."\">\n";
								 ${"box".$boxNum} .= "   ".$dateDisp."\n";
							   ${"box".$boxNum} .= "  </td>\n";
							   ${"box".$boxNum} .= " </tr>\n";
							}
							if($settings['NUM_DISPLAY']['blog'] > $blogCnt){
							   ${"box".$boxNum} .= " <tr>\n";
							   ${"box".$boxNum} .= "  <td class=\"boxSpacer".$boxNum."\">\n";
								 ${"box".$boxNum} .= "   &nbsp;\n";
							   ${"box".$boxNum} .= "  </td>\n";
							   ${"box".$boxNum} .= " </tr>\n";
							   $blogCnt++;
							}
	         }

	      }
	      ${"box".$boxNum} .= "</table>\n";
	      ${"box".$boxNum} .= "\n\n\n<!--- END BOX ".$boxNum." --->\n\n\n";
	    }
   }
}

$boxArray = split(";", $boxCheck);
$arrLen = count($boxArray);

#######################################################
### Build #BOX-TITLE# display	                      ###
#######################################################

for($x=0;$x<$arrLen;$x++){
	if ( eregi("box-title([0-9])", $boxCheck, $snip) ) {

		$daBox = $snip[1];
		$fileBox = $promoFile.$daBox;

      $selBox = mysql_query("SELECT * FROM promo_boxes WHERE FILE = '$fileBox'");
      $getBox = mysql_fetch_array($selBox);

      $settings['CONTENT'] = unserialize($getBox['CONTENT']);


      if($settings['CONTENT']['display'] == "off"){
      	##### BOX DISPLAY ALL OFF #####
      	${"box_title".$snip[1]} = "";
      }else{

		   $promo_title = mysql_query("SELECT FILE, FUTURE1 FROM promo_boxes");
		   while($box_titles = mysql_fetch_array($promo_title) ) {
		   	if(eregi($promoFile.$daBox, $box_titles['FILE'])){
		   		$title_promo = unserialize($box_titles['FUTURE1']);

		   		${"box_title".$snip[1]} = $title_promo['title'];
		   		//echo "(".${"box_title".$snip[1]}.")";
					$boxCheck = eregi_replace("box-title".$snip[1].";", "", $boxCheck);
		   	}
		   }
		}
	}
}



// #PROMOHDR# and #PROMOTXT#
// ==========================================
if ( eregi("promohdr", $boxCheck) || eregi("promotxt", $boxCheck) ) {
   $selPromo = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$promo_cat' ORDER BY rand() DESC LIMIT 3");

   $bx = 1;
   while ( $getPromo = mysql_fetch_array($selPromo) ) {
      ${"promohdr".$bx} = stripslashes($getPromo['BLOG_TITLE']);
      ${"promotxt".$bx} = stripslashes($getPromo['BLOG_DATA']);
      $bx++;
   }
}

// #NEWSBOX# - Dates and linked titles
// ==========================================
if ( eregi("newsbox;", $boxCheck) ) {

   $selNews = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$news_cat' ORDER BY BLOG_DATE DESC LIMIT 5");
   $b = 0;
   while ( $getNews = mysql_fetch_array($selNews) ) {
      $disNews[$b] .= " <font class=\"newsbox_date\">".stripslashes($getNews['BLOG_DATE']).":</font><br>\n";
      $disNews[$b] .= " <a href=\"index.php?nShow=".$getNews['PRIKEY']."\" class=\"newsbox\">".stripslashes($getNews['BLOG_TITLE'])."</a><br><br>\n";
      $b++;
   }

   $cNews = count($disNews);
   for ( $n=0; $n < $cNews; $n++ ) {
      $newsbox .= "<div class=\"newsbox\">\n";
      $newsbox .= $disNews[$n];
      $newsbox .= "</div>\n";
   }
}


// #NEWSBOX-***# - Dates, linked titles, and specified amount of content
// ============================================================================
if ( eregi("newsbox-([0-9]{1,3})", $boxCheck, $snip) ) {

   $selNews = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$news_cat' ORDER BY BLOG_DATE DESC LIMIT 5");
   $b = 0;
   while ( $getNews = mysql_fetch_array($selNews) ) {

      # Pre-format news teaser text
      $disTease = strip_tags(stripslashes($getNews['BLOG_DATA']), '<br>');
      $disTease = substr("$disTease", 0, $snip[1]);

      # Build news items
      $disfNews[$b] .= " <font class=\"newsbox_date\">".stripslashes($getNews['BLOG_DATE']).":</font><br>\n";
      $disfNews[$b] .= " <a href=\"index.php?nShow=".$getNews['PRIKEY']."\" class=\"newsbox\">".stripslashes($getNews['BLOG_TITLE'])."</a><br>\n";
      $disfNews[$b] .= " <font class=\"newsbox_tease\">".stripslashes($disTease)."</font><br><br>\n";
      $b++;
   }

   $cNews = count($disfNews);
   for ( $n=0; $n < $cNews; $n++ ) {
      $newsbox_flex .= "<div class=\"newsbox\">\n";
      $newsbox_flex .= $disfNews[$n];
      $newsbox_flex .= "</div>\n";
   }
}


if ( $nShow != "" ) {
   $selShow = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$news_cat' AND PRIKEY = '$nShow'");
   $getShow = mysql_fetch_array($selShow);

   $disArt = "\n\n\n<!--- BEGIN NEWS ARTICLE --->\n\n\n";
   $disArt .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"article_table\">\n";
   $disArt .= " <tr>\n";
   $disArt .= "  <td class=\"article_title\" align=\"left\"><font class=\"article_date\">".stripslashes($getShow['BLOG_DATE'])."</font> - ".stripslashes($getShow['BLOG_TITLE'])."</td>\n";
   $disArt .= " </tr>\n";
   $disArt .= " <tr>\n";
   $disArt .= "  <td class=\"article_content\">\n";
   $disArt .= "   ".stripslashes($getShow['BLOG_DATA'])."\n";
   $disArt .= "  </td>\n";
   $disArt .= " </tr>\n";
   $disArt .= "</table>\n";

   $disArticle = $disArt;
   $module_active = "yes";
}
if ( $bShow != "" ) {
   $selShow = mysql_query("SELECT * FROM blog_content WHERE BLOG_SUBJECT = '$cat' AND PRIKEY = '$bShow'");
   $getShow = mysql_fetch_array($selShow);

   $disArt = "\n\n\n<!--- BEGIN NEWS ARTICLE --->\n\n\n";
   $disArt .= "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"article_table\">\n";
   $disArt .= " <tr>\n";
   $disArt .= "  <td class=\"article_title\" align=\"left\"><font class=\"article_date\">".$getShow['BLOG_DATE']."</font> - ".stripslashes($getShow['BLOG_TITLE'])."</td>\n";
   $disArt .= " </tr>\n";
   $disArt .= " <tr>\n";
   $disArt .= "  <td class=\"article_content\">\n";
   $disArt .= "   ".stripslashes($getShow['BLOG_DATA'])."\n";
   $disArt .= "  </td>\n";
   $disArt .= " </tr>\n";
   $disArt .= "</table>\n";

   $disArticle = $disArt;
   $module_active = "yes";
}
?>