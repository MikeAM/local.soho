<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


// *********************************************************************
// ** SOHOLAUNCH OPEN SOURCE CODE CONTENT MANAGEMENT SYSTEM           **
// **                                                                 **
// ** Author: Mike Johnston                                           **
// **  Email: mike@soholaunch.com; mike@mikejsolutions.com            **
// **                                                                 **
// ** Portions of the overall system code are copyrighted and patented**
// ** by Soholaunch.com, Inc.  Please read and agree to all license   **
// ** agreements before modifing or utilizing this program.           **
// **                                                                 **
// *********************************************************************

		########################################################################
		### GET HTML NEWSLETTER CONTENT AND TEMPLATE
		########################################################################
		
		$filename = "$TEMPLATE_NAME";
		$file = fopen("../$filename", "r");
			$TEMPLATE_BODY = fread($file,filesize(../$filename));
		fclose($file);
						
		if ($PAGE_NAME == "NONE") {				// This is a custom template only HTML newsletter
						
				$HTML_CONTENT = $TEMPLATE_BODY;
				
		} else {
		
				// ------------------------------------------------------------------------
				// More complex; this is a tool generated email with template selection
				// Step 1: Read Template into tmp_variable
				// ------------------------------------------------------------------------
		
				$tmp = split("/", $TEMPLATE_NAME);
				$tmpc = count($tmp) - 1;
				$this_template = $tmp[$tmpc];
								
				$this_page = eregi_replace(" ", "_", $PAGE_NAME);
				$filename = "http://$this_ip/".pagename($this_page, "&")."news_force_template=$this_template";
				
				
				$HTML_CONTENT = include_r($filename);
				
				// ------------------------------------------------------------------------
				// Replace Newsletter Template Variable Data with appropriate values now
				// ------------------------------------------------------------------------
				
				$this_title = strtoupper($SERVER_NAME);
				$HTML_CONTENT = eregi_replace("#TITLE#", "$this_title", $HTML_CONTENT);
				$HTML_CONTENT = eregi_replace("#UNSUBSCRIBE#", "news?=unsubscribe", $HTML_CONTENT);
			
		} // End Custom / Tool Generation Check
				
		// For Display Test Only -- Comment out for final build
				
		// $preview_html = eregi_replace("src=\"", "src=\"http://$this_ip/", $HTML_CONTENT);
		// $preview_html = eregi_replace("href=\"", "href=\"http://$this_ip/", $preview_html);
		// $THIS_DISPLAY .= "<br>$preview_html<hr>";		
		
		########################################################################
		### LET'S BUILD THE IMAGE_ARRAY VAR SO WE KNOW WHAT IMAGES TO MIME
		### ENCODE WITH THE EMAIL SEND
		########################################################################
				
		$work_html = eregi_replace(">", ">\n", $HTML_CONTENT);		// Make sure that all image calls are on a single line by themselves
		$work_html = eregi_replace("<", "\n<", $work_html);
		
		$IMAGE_ARRAY = "";			// Set main array build var to empty
		
		$html_line = split("\n", $work_html);
		$lc = count($html_line);
		
		for ($x=0;$x<=$lc;$x++) {	// Start loop thru each html line
		
			if (eregi("\.gif", $html_line[$x]) || eregi("\.jpg", $html_line[$x])) { 	// This line contains an image filename

				$tmp = split(" ", $html_line[$x]);	// One more step; split this line by spaces
				$tmpc = count($tmp);
				$this_image = "";					// Clear var before next find
				
				for ($y=0;$y<=$tmpc;$y++) {
				
						if (eregi("\.gif", $tmp[$y]) || eregi("\.jpg", $tmp[$y])) {

							$tmp_find = eregi("src=(.*)", $tmp[$y], $out);
							$this_image = $out[1];
							
							if ($this_image == "") {		// If not normal img tag; must be background tag
								$tmp_find = eregi("background=\"(.*)\"", $out, $tmp[$y]);
								$this_image = $out[1];
							}
							
						} // End if GIF or JPG found inside HTML TAG
				} // End $y Loop
				
				$this_image = eregi_replace("\"", "", $this_image);	// Remove quotes from image name (bi-product of effective image find)

				if ($this_image != "" && !eregi("$this_image", $IMAGE_ARRAY)) {
						
						$IMAGE_ARRAY .= $doc_root . "/" . $this_image . ";";
				}
				
			} // End if GIF or JPG found in this line
		} // End $x loop through each HTML line
		
		
		// The next two lines are for dev purposes and should be commented out for final build
		// $image_test = eregi_replace(";", "<BR>", $IMAGE_ARRAY);
		// $THIS_DISPLAY .= "<BR>Images:<BR><BR>$image_test<BR>";
				
		########################################################################
		### EMBED TEXT CONTENT VALUE INTO HTML CODE [Head off MIME errors]
		########################################################################	
				
		$HTML_CONTENT = "<!--\n\n$TEXT_EMAIL\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n -->\n\n\n\n" . $HTML_CONTENT;
		
		########################################################################
		### ADD CAMPAIGN TO CAMPAIN_MANAGER TABLE NOW
		########################################################################
		
		// ----------------------------------------------------
		// Make sure all values are insertable
		// ----------------------------------------------------
		
		$HTML_CONTENT = addslashes($HTML_CONTENT);
		$TEXT_EMAIL = addslashes($TEXT_EMAIL);
		
?>