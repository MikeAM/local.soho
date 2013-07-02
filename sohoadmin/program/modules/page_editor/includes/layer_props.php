<?php
$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../includes/product_gui.php');
chdir($curdir);

?>

<!-- ############################################################# -->
<!-- #### BEGIN PHOTO SELECTION LAYER (4.5 030804)			     #### -->
<!-- ############################################################# -->

<DIV ID="photoLayer" class="prop_layer" style="" >

   <div class="prop_head">Photo Album Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">

		<b>Which photo album category should appear here?</b>
		<SELECT id="photocat" NAME="photocat" style='font-face: Arial; font-size: 8pt; width: 250px;'>
			<option value="NULL" STYLE='color:#999999;'><?php echo lang("Photo Album Categories"); ?>:</option>

			<?php

			# Pull faq cats from table (if available)
			$photo_result = mysql_query("SELECT * FROM photo_album");

			# Build faq drop-down options
			while($photos = mysql_fetch_array($photo_result)) {
            if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
            echo "<option value=\"".$photos['prikey']."\" STYLE='background: $tmp;'>".$photos['album_name']."</option>\n";
			}

			?>

		</SELECT>
		<!--<br><br>
		<INPUT type=radio id="photoUser" name="photoDisplay" onClick="photoDisable()" value="user"><label for="photoUser">Show all albums</label>&nbsp;
		<INPUT type=radio id="photoSelected" name="photoDisplay" onClick="photoDisable()" value="selected" checked><label for="photoSelected">Show selected album</label>
		-->
		<input type=hidden  name="photoDisplay" value="selected">
		</td>
		<td align="center" valign="middle">

	 	<button type="button" class="blueButton" onClick="photoalbum();show_hide_layer('objectbar','','show','photoLayer','','hide');"><span><span>OK</span></span></button>
		&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','photoLayer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>

		</td>
		</tr>
		</table>

</DIV>

<!-- ############################################################# -->
<!-- #### BEGIN FAQ SELECTION LAYER (4.5 030804)			  #### -->
<!-- ############################################################# -->

<DIV ID="faqLayer" class="prop_layer" style="" >

   <div class="prop_head">FAQ Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle"><br/>

		<b>Which FAQ Category should appear here?</b>
		<SELECT id="faqcat" NAME="faqcat" style='font-face: Arial; font-size: 8pt; width: 250px;'>
			<option value="NULL" STYLE='color:#999999;'>FAQ Categorys:</option>

			<?php

			# Pull faq cats from table (if available)
			$faq_result = mysql_query("SELECT * FROM faq_category ORDER BY CAT_NAME");

			if ( !$faq_result = mysql_query("SELECT * FROM faq_category ORDER BY CAT_NAME") ) {
				echo "\n\n\n<!-- Cannot select from faq table: ".mysql_error()." -->\n\n\n";

			} else {
   			# Build faq drop-down options
   			while($faqs = mysql_fetch_array($faq_result)) {
               if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
               echo "<option value=\"".$faqs['CAT_NAME']."\" STYLE='background: $tmp;'>".$faqs['CAT_NAME']."</option>\n";
   			}
   		}

			?>

		</SELECT>

		</td>
		<td align="center" valign="middle"><br/>

	 	<button type="button" class="blueButton" onClick="OkFaq();show_hide_layer('objectbar','','show','faqLayer','','hide');"><span><span>OK</span></span></button>
		&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','faqLayer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>

		</td>
		</tr>
		</table>


</DIV>


<!-- ############################################################# -->
<!-- #### BEGIN BLOG SELECTION LAYER (4.5 030804)			  #### -->
<!-- ############################################################# -->

<DIV ID="blogLayer" class="prop_layer" style="" >

   <div class="prop_head">Blog Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">

		<b>Which Blog Subject should appear here?</b><br/>
		<SELECT id="blogsubj" NAME="blogsubj" style='font-face: Arial; font-size: 8pt; width: 250px;'>
			<option value="NULL" STYLE='color:#999999;'>Blog Subjects:</option>

			<?php
			//echo "IN HERE -----------\n";
			$blog_result = mysql_query("SELECT * FROM blog_category ORDER BY category_name");
			while($blogs = mysql_fetch_array($blog_result)) {
					if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
					echo "<option value=\"".$blogs['prikey']."\" STYLE='background: $tmp;'>".$blogs['category_name']."</option>\n";
			}
			echo "<option value=\"ALL\" STYLE='background: $tmp;font-weight:bold;'>ALL SUBJECTS</option>\n";
			?>

		</SELECT>

		</td>
		<td align="center" valign="middle"><br/>

	 	<button type="button" class="blueButton" onClick="OkBlog();show_hide_layer('objectbar','','show','blogLayer','','hide');"><span><span>OK</span></span></button>
		&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','blogLayer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>

		</td>
		</tr>
		</table>

</DIV>

<!-- ############################################################# -->
<!-- #### BEGIN IMAGE DROP LAYER (3.5 Mod)					  #### -->
<!-- ############################################################# -->

<DIV ID="imageDrop" class="prop_layer">

   <div class="prop_head">Image Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">

				<b>Choose Image:</b>

				<!--- New school way to pull fresh file lists -->
				<span id="imgFileList">File list container</span>


            &nbsp; <button type="button" class="blueButton"  onClick="OkImageData();show_hide_layer('objectbar','','show','imageDrop','','hide');"><span><span>Select Image</span></span></button>
				&nbsp;&nbsp;  <button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','imageDrop','','hide');replaceImageData();"><span><span>Cancel</span></span></button>
            &nbsp;&nbsp; <button type="button" class="blueButton" onClick="loadUploadDialog('img');show_hide_layer('objectbar','','show','imageDrop','','hide');"><span><span>Upload Image</span></span></button>
				<br/><br/><font color="#666666" size="1"><b><i>To link an image, select an image here then click the image to see link options.</i></b></font>

			</td>
		</tr>
	</table>

</DIV>





<!-- ############################################################# -->
<!-- #### BEGIN IMAGE "LINK" LAYER (3.5 MOD)				  #### -->
<!-- ############################################################# -->

<DIV ID="imageLink" class="prop_layer" style="" >

   <div class="prop_head">Image Link</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">

		<U>Option 1</U>: Link to Internal Page<BR><BR>
		<SELECT id="imagePageLink" NAME="imagePageLink" STYLE='font-family: Arial; font-size: 8pt; width=175px;'>
			<option value="NONE" STYLE='color: #999999;'>Site Pages:</option>

			<?php

			for ($a=1;$a<=$numSitePages;$a++) {

				if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
				$thisPage = $page_name[$a]; // Mantis #0000030
				$linkto = eregi_replace(" ", "_", $thisPage);
				echo "<option value=\"".$linkto."\" STYLE=\"BACKGROUND: ".$tmp.";\">".$page_name[$a]."</option>\n";

			}

			?>

		</select>

		</td><td align="left" valign="middle" class="ctable">

		<U>Option 2</U>: Link to External Site<BR><BR>
		<input type="text" size="28" id="imageUrlLink" name="imageUrlLink" value="http://" STYLE='font-family: Arial; font-size: 8pt; width=175px;'>

		</td><td align="left" valign="middle" class="ctable">

		<U>Option 3</U>: Link to an Email Address<BR><BR>
		<input type="text" size="28" id="emailImageLink" name="emailImageLink" value="" STYLE='font-family: Arial; font-size: 8pt; width=175px;'>

		</td><td align="center" valign="middle">

		&nbsp;<input type="button" class="mikebut" onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="show_hide_layer('objectbar','','show','imageLink','','hide');inputImageLink();">
		&nbsp;&nbsp;<input type="button" class="mikebut" onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="show_hide_layer('objectbar','','show','imageLink','','hide');makeUnScroll(ColRowID);">


   	</td>
   	</tr>
   </table>

</DIV>
<?php
function getNats( $field ) {

	$natOpts = "  <select style=\"width:136px;\" name=\"".$field."\" id=\"".$field."\">";
	$natOpts .= "   <option value=''></option>\n";
	global $localNat;
	global ${$field};
	$filename = "../../../client_files/shopping_cart/countries.dat";
	$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open country data")." (contries.dat).");
	$tmp_data = fread($file,filesize($filename));
	fclose($file);

	$natDat = split("\n", $tmp_data);
	$numNats = count($natDat);
	$GLOBALS['countlist'] = $natDat;
	//natDat: T.M.I (for now) format for proper display and usage
	$natNam = "";
	for ($f=0; $f < $numNats; $f++) {
		$tmpSplt = split("::", $natDat[$f]);
		$natNam[$f] = ucwords(strtolower($tmpSplt[0]));
		$natNam[$f] = strtoupper($natNam[$f]);
	}

	if ( $localNat == "" || $localNat == "No Default Country" ) {

      //Display full country drop-down
		for ($c=0;$c < $numNats;$c++) {
				$sel = "";
			if ($natNam[$c] == ${$field}) { $sel = " selected"; }
				$natOpts .= "   <option value='$natNam[$c]'$sel>$natNam[$c]</option>\n";
			}

		} else {

      //Display limited drop down (might add 'multiple local countries' soon)
      for ($c=0;$c < $numNats;$c++) {
         $sel = "";
				if($_SESSION[$field] == ''){	
					if ( $c == 0 ) { $sel = "selected"; }
				}
				if($_SESSION[$field] == $natNam[$c] && $_SESSION[$field] != ''){
					$sel = "selected";
				}
         
				if ( eregi($natNam[$c].":n:", $localNat) ) {
					$natOpts .= "   <option value='$natNam[$c]' $sel>$natNam[$c]</option>\n";
				}
			}
		}

		$natOpts .= "</select>\n";
		return $natOpts;
}
?>
<!-- ############################################################# -->
<!-- #### BEGIN DIRECTIONS LAYER (3.5 MOD)					  #### -->
<!-- ############################################################# -->
<script language="javascript">
function showhidecdd(onvar){
	if(onvar==0){
		document.getElementById('cdd').style.display='none';		
	} else {
		document.getElementById('cdd').style.display='inline';
	}
	
}
</script>
<?php
$spcRez = mysql_query("SELECT * from site_specs");
$getSpec = mysql_fetch_assoc($spcRez);
$df_address1 = $getSpec['df_address1'];
$df_address2 = $getSpec['df_address2'];
if($df_address2 != ''){
	$df_address2 = " ".$df_address2;	
}
$df_city = $getSpec['df_city'];
$df_state = $getSpec['df_state'];
$df_zip = $getSpec['df_zip'];
if($getSpec['df_country']!=''){
	$df_country_ar = explode(' - ', $getSpec['df_country']);
	$df_country = $df_country_ar['0'];
	$country_dd = str_replace('>'.$df_country.'<', ' selected="selected">'.$df_country.'<', getNats('country'));
} else {
	$country_dd = getNats('country');
}
?>
<div id="mapquest" class="prop_layer" style="" >

   <div class="prop_head">Driving Directions</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="left">

			<B>Enter an address that you wish to link a map to</B><BR>

			Address:<input type="text" size="28" id=street name=street value="<?php echo $df_address1.$df_address2; ?>" STYLE='font-family: Arial; font-size: 8pt; width: 175px;'>
			&nbsp; City: <input type="text" size=15 id=city name=city value="<?php echo $df_city; ?>" STYLE='font-family: Arial; font-size: 8pt; width: 75px;'>
			&nbsp; State: <input type="text" size=2 id=state name=state value="<?php echo $df_state; ?>" STYLE='font-family: Arial; font-size: 8pt; width: 50px;'>
			&nbsp; Zip: <input type="text" size=5 id=zip name=zip value="<?php echo $df_zip; ?>" STYLE='font-family: Arial; font-size: 8pt; width: 50px;'>
			<div id="cdd" style="display:inline;">&nbsp; Country: <?php echo $country_dd; ?></div>
			
			</td></tr>
			<tr><td align="center" valign="middle">
				
			<INPUT checked type=radio id=MAPLINKTOgoogle name=MAPLINKTO value="GOOGLEMAPS" onClick="showhidecdd('1');">Google Maps<font size=1><SUP>TM</SUP></font> &nbsp;&nbsp;&nbsp;
			<INPUT type=radio id=MAPLINKTOyahoo name=MAPLINKTO value="YAHOO" onClick="showhidecdd('0');">Yahoo Maps!<font size=1><SUP>TM</SUP></font>
			 &nbsp;&nbsp;&nbsp;<input type=radio id=MAPLINKTOquest name=MAPLINKTO value="MAPQUEST" onClick="showhidecdd('0');">Mapquest<font size=1><SUP>TM</SUP></font>
			 
			<button type="button" class="blueButton" onClick="OkMapquestData();show_hide_layer('objectbar','','show','mapquest','','hide');"><span><span>OK</span></span></button>
			&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','mapquest','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>

			</td>
		</tr>
	</table>

</div>

<!-- ############################################################# -->
<!-- #### BEGIN SECURE LOGIN BUTTON LAYER (3.5 MOD)			  #### -->
<!-- ############################################################# -->

<DIV ID="securelayer" class="prop_layer" style="" >

   <div class="prop_head">Member Login</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle"><br/>

		<b>Enter the text to display on login button:</b>
		<input type="text" size=32 id="loginbutton" name="loginbutton" value="Member Login">

		</td><td align="center" valign="middle"><br/>

		<button type="button" class="blueButton" onClick="OkLoginData();show_hide_layer('objectbar','','show','securelayer','','hide');"><span><span>OK</span></span></button>
		&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','securelayer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>

   	</td>
   	</tr>
   </table>

</DIV>


<!-- ############################################################# -->
<!-- #### BEGIN CALENDAR LAYER								  #### -->
<!-- ############################################################# -->

<div id="calendarlayer" class="prop_layer" style="" >

   <div class="prop_head">Calendar Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="left" valign="middle">

			   <b>Please choose one of the calendar display options below.</b><br/>

         	<B>1. Instant Display:</B> (Requires Category)

         		<input type="radio" id="caltypeW" name="caltype" value="wkview"> This Week &nbsp;&nbsp;
         		<input type="radio" id="caltypeM" name="caltype" value="moview"> This Month &nbsp;&nbsp;

         		<SELECT id="calcat" NAME="calcat" class="ctable">
         		<OPTION VALUE="All">All</OPTION>

         		<?php
         			$quick_cat = mysql_query("SELECT * FROM calendar_category ORDER BY Category_Name");

         			while ($quick_cats = mysql_fetch_array($quick_cat)) {
         				echo "<OPTION VALUE=\"$quick_cats[Category_Name]\">$quick_cats[Category_Name]</OPTION>\n";
         			}
         		?>

         		</SELECT>
        </td>
         <td rowspan="2" align="center" valign="middle">         	
            <button type="button" class="blueButton" onClick="do_calendar();show_hide_layer('objectbar','','show','calendarlayer','','hide');"><span><span>OK</span></span></button>
            <button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','calendarlayer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>
         </td>
      </tr>
      <tr>
         <td align="left" valign="top">
         	<B>2. Normal System:</B>
            <input type="radio" id="caltypeS" name="caltype" value="moview" CHECKED> Searchable Calendar System
         		<SELECT id="syscalcat" NAME="syscalcat" class="ctable">
         		<OPTION VALUE="All">All</OPTION>
         		<?php
         			$quick_cat = mysql_query("SELECT * FROM calendar_category ORDER BY Category_Name");
         			while ($quick_cats = mysql_fetch_array($quick_cat)) {
         				echo "<OPTION VALUE=\"$quick_cats[Category_Name]\">$quick_cats[Category_Name]</OPTION>\n";
         			}
         		?>
         		</SELECT>
         </td>
      </tr>
   </table>

</div>


<!-- ######################################################################################### -->
<!-- #### BEGIN DOCUMENT LAYER (3.5 MOD)												  #### -->
<!-- ######################################################################################### -->

<div id="ULDOCLAYER" class="prop_layer" style="" >

   <div class="prop_head">Document Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
				<b>Select the Document you wish to place on this page for download:</b><BR>

				<!--- New school way to pull fresh file lists -->
				<span id="docFileList">File list container</span>

				</td>
				<td align="center" valign="bottom">
				 <table border="0" cellpadding="3" cellspacing="0">
				  <tr>
				   <td><button type="button" class="blueButton" onClick="OkWordData();show_hide_layer('objectbar','','show','ULDOCLAYER','','hide');"><span><span>Place Onto Page</span></span></button></td>
               <td><button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','ULDOCLAYER','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button></td>
               <td width="50">&nbsp;</td>
               <td><button type="button" class="blueButton" value=" Upload File " onClick="loadUploadDialog('doc');show_hide_layer('objectbar','','show','ULDOCLAYER','','hide');"><span><span>Upload File</span></span></button></td>
              </tr>
             </table>
			</td>
		</tr>
	</table>

</div>

<!-- ############################################################# -->
<!-- #### OFF SITE LINKS LAYER (3.5 MOD)					  #### -->
<!-- ############################################################# -->

<DIV ID="sitelinks" class="prop_layer" style="" >

   <div class="prop_head">Plugin Link Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle" width="80%">

			<FONT STYLE='font-size: 7pt; color: darkblue;'>PlugIn Links are software programs needed to view or operate special features on the Internet. For example,
			you may wish to post a .PDF document for download. You will want to place the link for Adobe<SUP>tm</SUP> Acrobat Reader<SUP>tm</SUP> on the page
			as well.</FONT>

			<br/><b>Choose link to place on page:</b> <SELECT id="grlink" NAME="grlink" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>

				<option value="NONE" STYLE='COLOR: #999999;'>PlugIn Links:</option>
				<option value="adobelink" STYLE='background: white;'>Adobe Acrobat Reader</option>
				<option value="flashlink" STYLE='background: #EFEFEF;'>Macromedia Flash Player</option>
				<option value="winamplink" STYLE='background: white;'>Winamp MP3 Player</option>
				<option value="quicktimelink" STYLE='background: #EFEFEF;'>Apple Quicktime Player</option>
			</select>

			<BR><FONT SIZE=1 COLOR=#999999><B><I>All product images and names are trademarked and copyrighted by their respective owners.</I></B></FONT>

			</td>
			<td align="center" valign="middle">

			&nbsp;<input type="button" class="mikebut" onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="OkGrlinkData();show_hide_layer('objectbar','','show','sitelinks','','hide');"><br/><br/>
			&nbsp;&nbsp;<input type="button" class="mikebut" onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="show_hide_layer('objectbar','','show','sitelinks','','hide');replaceImageData();makeUnScroll(ColRowID);">

		</td>
	</tr>
	</table>

</div>

<!-- ############################################################# -->
<!-- #### BEGIN MP3 LAYER (3.5 MOD)							  #### -->
<!-- ############################################################# -->

<DIV ID="mp3layer" class="prop_layer" style="" >

   <div class="prop_head">Audio File Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
		<b>You may choose to place a .WAV or .MP3 file on this page for site visitors to download.</b><br/><br/>

		<!--- New school way to pull fresh file lists -->
		<span id="mp3FileList">File list container</span>

		</td><td align="center" valign="middle">

		<button type="button" class="blueButton" onClick="OkMP3Data();show_hide_layer('objectbar','','show','mp3layer','','hide');"><span><span>OK</span></span></button>
		&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','mp3layer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>
          <button type="button" class="blueButton" onClick="loadUploadDialog('mp3');show_hide_layer('objectbar','','show','mp3layer','','hide');"><span><span>Upload File</span></span></button>
	</td>
	</tr>
	</table>

</DIV>

<!-- ############################################################# -->
<!-- #### BEGIN VIDEO LAYER (3.5 MOD)						  #### -->
<!-- ############################################################# -->

<script language="javascript">
function vidSize(ddval) {
   var valStuff = ddval.split(";");
   //alert('ddval[0] = '+valStuff[2]);
   document.getElementById("videow").value=valStuff[1];
   document.getElementById("videoh").value=valStuff[2];
}
</script>

<DIV ID="videolayer" class="prop_layer" style="" >

   <div class="prop_head">Video File Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
		<b>Select the video file you wish to allow site visitors to view.</b><br>
		<font color="#666666"><B>[ Acceptable file formats include: .AVI .MOV .MPEG .MPG .WMV .ASF .ASX .IPIX .SWF ]</B></font><br/><br/>

		<!--- New school way to pull fresh file lists -->
		<span id="videoFileList">File list container</span>

		<b>Width:</b> <input name="videow" id="videow" type="text" size="4" value="<?php echo $mediaheight; ?>" STYLE='font-family: Arial; font-size: 8pt; '> &nbsp;
		<b>Height:</b> <input name="videoh" id="videoh" type="text" size="4" value="<?php echo $mediawidth; ?>" STYLE='font-family: Arial; font-size: 8pt; '>

		</td><td align="center" valign="middle">

		<button type="button" class="blueButton" onClick="show_hide_layer('objectbar','','show','videolayer','','hide');OkVideoData();"><span><span>OK</span></span></button>
		&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','videolayer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button><br/><br/>
      <button type="button" class="blueButton" onClick="loadUploadDialog('vid');show_hide_layer('objectbar','','show','videolayer','','hide');"><span><span>Upload File</span></span></button>
	</td>
	</tr>
	</table>
</DIV>

<!-- ############################################################# -->
<!-- #### BEGIN POPUP WINDOW LAYER (3.5 MOD)				  #### -->
<!-- ############################################################# -->

<DIV ID="popupwin" class="prop_layer" style="" >

   <div class="prop_head">Popup Window Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
		<b>Create an automatic PopUp Window to be activated when this page is accessed:</b><br/><br/>

		<b>Display Page:</b>
		<SELECT id="popname" NAME="popname" STYLE='font-family: Arial; font-size: 8pt; width: 150px;'>
			<OPTION VALUE="NONE" STYLE='color: #999999;'>Page Names:</OPTION>

			<?php

			for ($a=1;$a<=$numSitePages;$a++) {
				if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
				echo "<option value=\"".str_replace(' ', '_', $page_name[$a])."\" STYLE='BACKGROUND: $tmp;'>$page_name[$a]</option>\n";
			}

			?>

		</SELECT>

		&nbsp; <b>Window Width:</b> <input type="text" size=4 id="winw" name="winw"> &nbsp; <b>Window Height:</b> <input type="text" size=4 id="winh" name="winh">

		</td><td align="center" valign="middle">

	<button type="button" class="blueButton" onClick="OkPopupData();show_hide_layer('objectbar','','show','popupwin','','hide');"><span><span>OK</span></span></button>
		&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','popupwin','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>

	</td>
	</tr>
	</table>

</DIV>


<!-- ############################################################# -->
<!-- #### BEGIN CUSTOM cameron mod HTML (3.5 MOD)						  #### -->
<!-- ############################################################# -->

<?php

	foreach (glob($_SESSION['doc_root'].'/media/*') as $filename) {
		if(!is_dir($filename)) {
			$dafile .= basename($filename).';';
		}
	}
?>
<script language="javascript">
function OkNewCustomData(tCustom) {
   var finalObj,RandNum;
   var tmplt = objTemplate('CUSTOMOBJ', true, 80);

   RandNum = tmplt[0];
   customID = RandNum.replace("CUSTOMOBJ", "NEWCUSTOMOBJ");

   finalObj = tmplt[1].replace("##OBJ_DISPLAY##", "<div ID="+customID+" align=center><font style='font-family: Arial; font-size: 8pt;'><B>Custom Code Include:</B></font><BR>[ "+tCustom+" ]<br/><br/><button TYPE=BUTTON VALUE=\"&nbsp;&nbsp;Edit&nbsp;&nbsp;\" CLASS=\"blueButton\" onClick=\"loadNewCustom('"+tCustom+"');\"><span><span>Edit</span></span></button></div><!-- ##CUSTOMHTML;"+tCustom+"## -->");
   if (tCustom != "NONE") {
      document.getElementById(ColRowID).innerHTML= finalObj;
   }
	checkRow(ColRowID);
	document.getElementById('customlayer_new_custinc').style.display='none';
	document.getElementById('objectbar').style.display='block';
	loadNewCustom(tCustom);

}

<?php

echo "function fileNameCheck(){ \n";
echo "	var newFileName = document.getElementById('file_name_new_cust').value; \n";
echo "	if(newFileName == ''){ \n";
echo "		alert('A file named '+words[i]+' already exists!  Please choose a different file name.')\n";
echo "		var rett='yes'; \n";
echo "		return false; \n";
echo "	} \n";
echo "	var filelist = '".$dafile."';\n";
echo "	var words=filelist.split(\";\") \n";
echo "	for (i=0; i<words.length; i++) { \n";
echo "		var texas = newFileName; \n";
echo "		texas = \"^\"+texas+\".*\" \n";
echo "		summ=words[i].search(texas) \n";
echo "		if (summ>-1) {   //alert( words[i] ) \n";
echo "			alert('A file named '+words[i]+' already exists!  Please choose a different file name.')\n";
echo "			var rett='yes'; \n";
echo "			return false; \n";
echo "		} \n";
echo "	} \n";
echo "	if (rett!='yes') {  \n";
//echo "		parent.replaceImageData(); \n";
//echo "		parent.closeUploadWin(); \n";
//echo "		document.new_custom.submit();  \n";
echo "	var wordssplit=newFileName.split('.'); \n";
echo "	for (iz=0; iz<wordssplit.length; iz++) {  \n";
echo "	} \n";
echo "	if(iz == 1){ \n";
echo "		newFileName = newFileName +'.inc' \n";
echo "	} \n";



echo "		show_hide_layer('objectbar','','show','customlayer','','hide'); \n";
echo "		OkNewCustomData(newFileName); \n";
echo "	}  \n";
echo "} \n";

echo "</script>\n";
?>

</script>

<DIV ID="customlayer_new_custinc" class="prop_layer" style="" >

   <div class="prop_head_upload">New Custom Include</div>

	<table cellpadding="0" cellspacing="0" width="90%" class="prop_table">
		<tr>
			<td align="center" valign="middle" width="75%">
			   <div id="custing" style="display: none;"><font style="color: red; font-size: 10pt;">Uploading, please wait...</font></div>
            <b>Please select name for the new include file.<br>
            <form name="new_custom" style="margin: 0px;" class="FormLt2" size="80" method="post">
               New File Name: <input type="text" id="file_name_new_cust" name="file_name_new_cust" value="">
            </form>
         </td>
         <td valign="top" align="center" style=""><br/>
            <input type="button" value="<? echo lang("Create New File"); ?>" onClick="fileNameCheck();" <? echo $btn_save; ?>>
            &nbsp;&nbsp;<input type="button" value=" Cancel " onClick="document.getElementById('customlayer_new_custinc').style.display='none'; document.getElementById('objectbar').style.display='block';" <? echo $btn_edit; ?>>
         </td>
        </tr>
      </table>

</DIV>

<!-- ############################################################# -->
<!-- #### BEGIN CUSTOM HTML (3.5 MOD)						  #### -->
<!-- ############################################################# -->

<DIV ID="customlayer" class="prop_layer" style="" >

   <div class="prop_head">Custom Code Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle" width="75%">
	<FONT STYLE='font-size: 7pt; color: darkblue;'>The "Custom Code" object allows you to create your own HTML or PHP code; upload it via file upload and place it on any
	content page. All PHP code executes in real-time as an include when site visitors view this page. Accepted file formats are: <font color=maroon>.html .htm .php .inc </font></font><br/>

	<!--- New school way to pull fresh file lists -->
	<span id="customFileList">File list container</span>

	</td><td align="center" valign="middle">

	<button type="button" class="blueButton" onClick="OkCustomData();show_hide_layer('objectbar','','show','customlayer','','hide');"><span><span>Select File</span></span></button>
	&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','customlayer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button><br/><br/>
	<button type="button" class="blueButton" onClick="loadNewCust('cust');show_hide_layer('objectbar','','show','customlayer','','hide'); document.getElementById('customlayer_new_custinc').style.display='block';"><span><span>New File</span></span></button>
	<button type="button" class="blueButton" onClick="loadUploadDialog('cust');show_hide_layer('objectbar','','show','customlayer','','hide');"><span><span>Upload File</span></span></button>
	</td>
	</tr>
	</table>

</DIV>

<!-- ############################################################# -->
<!-- #### BEGIN SEARCHABLE TABLE LAYER (3.5 MOD)			  #### -->
<!-- ############################################################# -->


<DIV ID="memDatabase" class="prop_layer" style="" >

   <div class="prop_head">Searchable Table Selection</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">

			<b>Select the 'Search' that you wish to place on this page and click 'Ok':</b><br/>

			<SELECT id="dbaseSelect" NAME="dbaseSelect" STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>
			     <OPTION VALUE="NONE" STYLE='color: #999999;'>User Data Tables:</option>

			<?php

			for ($a=1;$a<=$memberBases;$a++) {
				$tmp = strtoupper($memberdatabase[$a]);
				echo "     <option value=\"$memberdatabase[$a]\" STYLE='COLOR: DARKBLUE;'>$tmp</option>\n";
			}

			?>

			</SELECT><BR><FONT SIZE=1 COLOR=#999999><B><I>Note: This should be placed on a row with no other objects to the right or left.</I></B></FONT>

			</td><td align="center" valign="middle">

			<button type="button" class="blueButton" onClick="okMembership();show_hide_layer('objectbar','','show','memDatabase','','hide');"><span><span>OK</span></span></button>
			&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','memDatabase','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>

		</td>
	</tr>
	</table>

</DIV>




<!-- ############################################################# -->
<!-- #### BEGIN SHOPPING CART OPTION LAYER (3.5 MOD)		  #### -->
<!-- ############################################################# -->
<div id="shoppingCartLayer" class="prop_layer" style="" >
   <div class="prop_head">Shopping Cart Selection</div>
	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td align="center" valign="middle">
		<?php
		$tmp = mysql_query("SELECT PROD_SKU, PROD_NAME, PROD_THUMBNAIL, PROD_FULLIMAGENAME FROM cart_products ORDER BY PROD_NAME");
		$tmp_cnt = mysql_num_rows($tmp);
		if ($tmp_cnt > 0) {		// Sku's Exist in product database
				echo "<FONT COLOR=$header_text><b>Single Product Promotion (Link):</b><BR>";
				echo "<SELECT id=SINGLESKU NAME=SINGLESKU STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>\n";
				echo "<OPTION VALUE=\"NONE\" SELECTED>Current Products...</OPTION>\n";
				while($sku = mysql_fetch_array($tmp)) {
					$skuimg = $sku['PROD_THUMBNAIL'];
					if($skuimg == ''){ $skuimg = $sku['PROD_FULLIMAGENAME']; }
					echo "<OPTION VALUE=\"".$sku['PROD_SKU']."~~".$sku['PROD_NAME']."~~".$skuimg."\">$sku[PROD_NAME]</OPTION>\n";
				}
				echo "</SELECT>&nbsp;";				
				echo "<button type=\"button\" class=\"blueButton\" onClick=\"OkCartSku();show_hide_layer('objectbar','','show','shoppingCartLayer','','hide');\"><span><span>Place Item</span></span></button>\n";
				echo "</td>\n";
		} else {
		   echo lang("Once you have created at least one product sku in the shopping cart ");
		   echo lang("a drop-down menu will appear in this space so that you may place a single sku onto the page.");
		}
		 // End sku verify and populate option
		?>
		<td align="center" valign="middle" width=45%><br/>
		<button type="button" class="blueButton" onClick="OkCartSku('search');show_hide_layer('objectbar','','show','shoppingCartLayer','','hide');"><span><span>Place Search/Browse Box</span></span></button>
		&nbsp;&nbsp;<button type="button" class="grayButton" onClick="show_hide_layer('objectbar','','show','shoppingCartLayer','','hide');replaceImageData();makeUnScroll(ColRowID);"><span><span>Cancel</span></span></button>
	</td>
	</tr>
</table>
</div>


<!-- ############################################################# -->
<!-- #### BEGIN Social Media Layer                            #### -->
<!-- ############################################################# -->
<div id="socialMediaLayer" class="prop_layer" style="" >
   <div class="prop_head">Social Media</div>
	<table cellpadding="0" cellspacing="0" width="100%" class="prop_table">
		<tr>
			<td style="padding-left:20px; width:355px;" align="left" valign="middle">
		<?php

		//echo "<FONT COLOR=$header_text><b>Select social media icons:</b><BR>";
		echo "<img src=\"images/soc_facebook.png\">\n";
		echo "<input type=\"checkbox\" name=\"sel_facebook\" id=\"sel_facebook\" checked />\n";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"images/soc_twitter.gif\">\n";
		echo "<input type=\"checkbox\" name=\"sel_twitter\" id=\"sel_twitter\" checked />\n";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"images/soc_stumble.png\">\n";
		echo "<input type=\"checkbox\" name=\"sel_stumble\" id=\"sel_stumble\" checked />\n";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"images/soc_google.png\">\n";
		echo "<input type=\"checkbox\" name=\"sel_google\" id=\"sel_google\" checked />\n";
		echo "<br/><input type=\"checkbox\" name=\"sel_social_count\" id=\"sel_social_count\" checked />\n";
		echo "&nbsp;Display social media count\n";
		echo "</td><td style=\"vertical-align:bottom; align:left; text-align:left;\">\n";
		echo "<button type=\"button\" class=\"blueButton\"  STYLE='WIDTH: 140px;' onClick=\"show_hide_layer('objectbar','','show','socialMediaLayer','','hide');OkSocialMedia();\"><span><span>Place Social Icons</span></span></button>\n";
		echo "</td>\n";
		echo "</tr>\n";
		?>
</table>
</div>
