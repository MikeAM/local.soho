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

session_start();
error_reporting(0);
require_once('../includes/product_gui.php');



######################################################
### READ TEXT AND ALIGNMENT SETTING INTO MEMORY    ###
######################################################

$filename = "$cgi_bin/menu.conf";
if (file_exists("$filename")) {
	$file = fopen("$filename", "r");
		$body = fread($file,filesize($filename));
	fclose($file);
	$pConfig = split("\n", $body);
	$numLines = count($pConfig);
	for ($x=0;$x<=$numLines;$x++) {
		$temp = split("=", $pConfig[$x]);
		$variable = $temp[0];
		$value = $temp[1];
		${$variable} = $value;
	}
}

######################################################
### READ BUTTON COLORS INTO MEMORY		            ###
######################################################

$filename = "$cgi_bin/menucolor.conf";

if (file_exists("$filename")) {
	$file = fopen("$filename", "r");
		$body = fread($file,filesize($filename));
	fclose($file);
	$pConfig = split("\n", $body);
	$numLines = count($pConfig);
	for ($x=0;$x<=$numLines;$x++) {
		$temp = split("=", $pConfig[$x]);
		$variable = $temp[0];
		$value = $temp[1];
		${$variable} = $value;
	}
}

if($_POST['remove_custom'] != ''){
	mysql_query("delete from site_pages where prikey='".$_POST['remove_custom']."'");
}

if($_POST['rename_cust_page'] != '' && $_POST['edit_cust_page_new'] != ''){	
	if(!eregi('^[ _]+$', $_POST['edit_cust_page_new'])){

		if($_POST['new_window'] == 'blank'){
		   $_POST['relink_cust_page_new'] = $_POST['relink_cust_page_new']."#blank";
		}
		$_POST['edit_cust_page_new'] = str_replace('_', ' ', $_POST['edit_cust_page_new']);
		mysql_query("update site_pages set page_name='".$_POST['edit_cust_page_new']."', url_name='".$_POST['relink_cust_page_new']."', link='".$_POST['relink_cust_page_new']."' where prikey='".$_POST['rename_cust_page']."'");
	}
}

#######################################################
### START HTML/JAVASCRIPT CODE			    		###
#######################################################

# Start buffering output
ob_start();
?>

<style>
.menupg_list {
   height: 250px;
   width: 150px;
   font-family: verdana, arial, helvetica, sans-serif;
   font-size: 10px;
   border: 1px black inset;
   color: #336699;
   background-color: #F2F2F2;
}
</style>
<SCRIPT LANGUAGE="JavaScript">
// Show edit pages upper nav button menu (works well enough for this module...dedicated header not really neccessary here)


	function killErrors() {
		return true;
	}
	//window.onerror = killErrors;

	function SV2_findObj(n, d) { //v3.0
	  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=SV2_findObj(n,d.layers[i].document); return x;
	}

	function SV2_showHideLayers() { //v3.0
	  var i,p,v,obj,args=SV2_showHideLayers.arguments;
	  for (i=0; i<(args.length-2); i+=3) if ((obj=SV2_findObj(args[i]))!=null) { v=args[i+2];
	    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
	    obj.visibility=v; }
	}

	function SV2_popupMsg(msg) { //v1.0
	  alert(msg);
	}

	function SV2_openBrWindow(theURL,winName,features) { //v2.0
	  window.open(theURL,winName,features);
	}



	// New Delete Page Function added (2002-10-24 M. Johnston)
	// --------------------------------------------------------

	function del_page() {

		var sText = MNUFORM.SELECTPAGES.options(MNUFORM.SELECTPAGES.selectedIndex).value;	// Select Page for Removal
		var oldMenu = OUTPUT.innerHTML;	// Current Menu Setup

		var NewMenu = "";	// Reset the Future Current Menu to Blank

		// Place Current Menu Layout into Array

		var curMnu = oldMenu.split('<BR>');

		// Does New Selection Exist in Current Menu?

		var thisSel = sText.toString();
		var thisSub = "&gt;&gt; "+thisSel;

		var i = 0;
		while(curMnu[i] != "") {
			if (curMnu[i] != thisSel && curMnu[i] != thisSub) {
				NewMenu = NewMenu+curMnu[i]+"<BR>";
			}
			i++;
		}

		OUTPUT.innerHTML = NewMenu;

	} // End Func

	// --------------------------------------------------------------------


	function up( )
	{
	    var selectObject = document.MNUFORM.menu_order;
	    var index = selectObject.selectedIndex;
	    if ( index > 0 )
	    {
	        swapOptions( selectObject, index, index - 1 );
	    } // if
	} // up( )

	function down( )
	{
	    var selectObject = document.MNUFORM.menu_order;
	    var index = selectObject.selectedIndex;
	    if ( (index >= 0) && (index != selectObject.options.length - 1) )
	    {
	        swapOptions( selectObject, index, index + 1 );
	    } // if
	} // down( )

	function swapOptions(obj,i,j)
	{
    	var o = obj.options;
    	var i_selected = o[i].selected;
    	var j_selected = o[j].selected;
    	var temp = new Option(o[i].text, o[i].value, o[i].defaultSelected, o[i].selected);
    	var temp2= new Option(o[j].text, o[j].value, o[j].defaultSelected, o[j].selected);
    	o[i] = temp2;
    	o[j] = temp;
    	o[i].selected = j_selected;
    	o[j].selected = i_selected;
	}


	function add_main() {

      var selectObj = document.MNUFORM.menu_order;
      var selectPgs = document.MNUFORM.SELECTPAGES;
      var toAdd = selectPgs.options[ selectPgs.selectedIndex ];

      //alert(" selectObj: ["+selectObj+"]\n selectPgs: ["+selectPgs+"]\n");

      if ( selectPgs.selectedIndex != -1 )
      {
          var options = selectObj.options;

          for ( var i = 0; i < options.length; i++ )
             if ( (options[i].value == toAdd.value) || (options[i].value == ">> " + toAdd.value) )
             {
                 alert( "You may only add a menu page once." );
                 return;
             } // if

          options[ options.length ] = new Option( toAdd.text, toAdd.value, toAdd.defaultSelected, toAdd.selected );
      } // if
	} // add_main( )

	function add_sub()
	{
	    var selectObj = document.MNUFORM.menu_order;
	    var selectPgs = document.MNUFORM.SELECTPAGES;

	    var toAdd = selectPgs.options[ selectPgs.selectedIndex ];

	    if ( selectPgs.selectedIndex != -1 )
	    {
	        var options = selectObj.options;

	        for ( var i = 0; i < options.length; i++ )
	           if ( (options[i].value == toAdd.value) || (options[i].value == ">> " + toAdd.value) )
	           {
	               alert( "You may only add a menu page once." );
	               return;
	           } // if

	        options[ options.length ] = new Option( ">> " + toAdd.text, ">> " + toAdd.value, toAdd.defaultSelected, toAdd.selected );
	    } // if
	} // add_main( )

	function remove_item( )
	{
	    var selectObj = document.MNUFORM.menu_order;
	    var selected = selectObj.selectedIndex;

	    if ( selected > -1 )
	    {
	        for ( var i = selected; i < (selectObj.options.length - 1); i++ )
	           swapOptions( selectObj, i, i+1 );

            selectObj.options.length--;

            if ( selectObj.options.length > 0)
                selectObj.selectedIndex = ( selected < selectObj.options.length ) ? selected : selectObj.options.length - 1;
	    } // if
	} // remove_item


	function clear_menu()
    {
	    var selectObj = document.MNUFORM.menu_order;

        if ( confirm( 'Are you sure you wish to clear the list?' ) )
            selectObj.options.length = 0;
	}
/*
	function change_button(color) {
		$('DEMOBTN').style.backgroundColor = color;
		var sText = "#"+color;
		CLRSELECT.customBtn.value = sText;
	}

	function change_text(color) {
		$('DEMOBTN').style.color = color;
		var sText = "#"+color;
		CLRSELECT.customTxtBtn.value = sText;
	} 

	function set_button(btn,txt) {

		button = "#"+btn;
		btext = "#"+txt;
		CLRSELECT.customBtn.value = button;
		CLRSELECT.customTxtBtn.value = btext;

		CLRSELECT.BTNCLR.options.value = btn;
		CLRSELECT.BTNTXTCLR.options.value = txt;

		DEMOBTN.style.background = button;
		DEMOBTN.style.color = btext;
	}

	function demobtn() {
		alert('This is a text representation of the color scheme\nin which your menu system colors will look like.');
	}
*/
	function save_menu()
	{

        var val = '';
	    var selectObj = document.MNUFORM.menu_order;

        for ( var i = 0; i < selectObj.options.length; i++ )
            val += selectObj.options[i].value + "\n";

    	//MNUFORM.newMenu.value = val;		// Insert new value into hidden TEXTAREA
    	document.getElementById('newMenu').value=val;
		document.MNUFORM.submit();		// Submit the FORM

		/*
	    var options = document.getElementById('menu_order').options;
	    var val = "";
	    for (var i = 0; i < options.length; i++)
	       val += options[i].value;

	    alert( val );

	    MNUFORM.newMenu.value = val;
		document.MNUFORM.submit();		// Submit the FORM
*/
	}

	function saveLink(){

		var linkVal = document.getElementById('disLink').value;
		var nameVal = document.getElementById('disPage').value;

		if(nameVal.length > 2){
			if(linkVal.length > 3){
				document.LNKFORM.submit();
			}else{
				alert('Please enter a link');
			}
		}else{
			alert('Please enter the text to display for this link.');
		}
	}

</SCRIPT>

<style>

form {
   margin:0;
}

.feature_contain {
	/*width: 141px;*/
	padding: 0;
	margin: 0;
	border-left: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	font: 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	color: #33393F;
	text-align: center;
	background-color: #fff;
}

.feature_contain th {
	font: bold 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
	/*color: #4D565F;*/
	background: #D9E2E1;
	border-right: 1px solid #A2ADBC;
	border-bottom: 1px solid #A2ADBC;
	border-top: 1px solid #A2ADBC;
	padding:2;
}

.feature_contain td {
   padding-bottom:7px;
	/*border-right: 1px solid #A2ADBC;*/
	/*border-bottom: 1px solid #A2ADBC;*/
	/*text-align: center;*/
}

.cal_btn {
   margin:0;
   /*padding-top:2px;
   padding-bottom:2px;*/
   text-align: center;
   border: 2px outset #CFCFCF;
   /*border: 1px dashed red;*/
   cursor: pointer;
   background: #A7DFAF;
   /*width: 100%;*/
}

.cal_btn_over {
   /*padding-top:2px;
   padding-bottom:2px;*/
   text-align: center;
   border: 2px outset #AFFFBA;
   cursor: pointer;
   background: #6FDF7E;
   /*width: 100%;*/
}

.cal_del_btn {
   margin:0;
   /*padding-top:2px;
   padding-bottom:2px;*/
   text-align: center;
   border: 2px outset #CFCFCF;
   /*border: 1px dashed red;*/
   cursor: pointer;
   background: #FF0000;
   /*width: 100%;*/
   color: #FFFFFF;
}

.cal_del_btn_over {
   /*padding-top:2px;
   padding-bottom:2px;*/
   text-align: center;
   border: 2px outset #CCCCCC;
   cursor: pointer;
   background: #FF4F4F;
   /*width: 100%;*/
   color: #FFFFFF;
}


</style>


<a name="top"></a>
 <FORM NAME=MNUFORM METHOD=POST ACTION="menu_system/update_menu.php">
   	  <table border=0 cellpadding=0 cellspacing=0 width="725px" class="feature_contain">
         <tr>
          <th align=left valign=top style="width:715px;">
            <IMG SRC="arrow.gif" WIDTH="17" HEIGHT="13" ALIGN="absmiddle"><? echo lang("Auto-Menu System Setup"); ?>
          </th>
         </tr>
         <tr>
 			 <td align=left valign=top style="width:100%; padding:10px 15px 10px 15px; border-right: 1px solid #A2ADBC;">
<?php 
		echo lang("Select a page from your available site pages. Then, choose to add it to the bottom")." ".lang("of your \"live\" menu as a Main Menu Item or a Sub-Page of a Main Menu Item").".";
		echo "<BR><br/>";
###################################################################
### HORIZONTAL/VERTICAL MENU SELECT AS WELL AS TEXT MENU OPTION ###
###################################################################

echo "              <table border=0 cellpadding=2 cellspacing=0 width=100% class=text>\n";
echo "               <tr>\n";

$seol = new userdata("seolink");
$seol->set("pref", 'yes');
//echo "                <td align=center valign=top width=25% style=\"color: #000099;\">\n";
//if ($MENUTYPE == "buttons") { $bchk = "CHECKED"; $tchk = ""; } else { $tchk = "CHECKED"; $bchk = ""; }
//
if ( $textmenu == "on" ) { $y = "CHECKED"; $n = ""; } else { $y = ""; $n = "CHECKED"; }
//echo lang("Auto-Menu Display Type");
//echo ":<BR>\n";
//	echo "<input type=radio name=MENUTYPE value=textlink $tchk>"; echo lang("Text Links");
//	echo "&nbsp;&nbsp;";
//	echo "<input type=radio name=MENUTYPE value=buttons $bchk>"; echo lang("Buttons");
//	echo "                </td>\n";
//			$seol = new userdata("seolink");
//
//			if($seol->get("pref") == "no") {
//				$noc = "CHECKED";
//			} else {
//				$yoc = "CHECKED";
//			}
//
//
//	echo "                <td align=left valign=top width=10% style=\"color: #000099;\">&nbsp;</td>\n";
//	echo lang("S.E.O. friendly links");
//	echo ":<BR>\n";
//	echo "<input type=radio name=seolink value=yes $yoc>"; echo lang("yes");
//	echo "&nbsp;&nbsp;";
//	echo "<input type=radio name=seolink value=no $noc>"; echo lang("no");
//	echo "                </td>\n";

echo "                <td valign=middle style=\"align:right; width:111px; text-align:left; color: #000099;\">\n";
echo                  lang("Text Menu Display").":";
echo "                </td>\n";
echo "<td valign=bottom style=\"align:left; text-align:left;\">\n";

echo "                 <input type=radio name=textmenu value=on $y>"; echo lang("Yes");
echo "                 &nbsp;&nbsp;";
echo "                 <input type=radio name=textmenu value=off $n>"; echo lang("No");
echo "</td>\n";
//echo "                <td align=center valign=middle width=25% >\n";
//
//	if ($bchk == "CHECKED") {
//		echo "<a href='#colors'>[ Edit Button Colors ]</a>";
//	}
//
//echo "                </td>\n";

//echo "                </td>\n";

echo "               </tr>\n";
echo "              </table>\n";

###################################################################

?>

              <table border=0 cellpadding=5 cellspacing=0 width=600 class="feature_sub">



	<?php

	// Pull all Main Menu Pages into Memory

	$result = mysql_query("SELECT prikey, page_name, url_name FROM site_pages WHERE UPPER(type) = 'MAIN' OR UPPER(type) = 'MENU' ORDER BY page_name");

	$a=0;
	$page_data = "";
	$cartcats_q = mysql_query("select * from cart_category where level='1'");
	while($cartcatz = mysql_fetch_assoc($cartcats_q)){
		$cartcats[$cartcatz['keyfield']]=$cartcatz['category'];
	}
	
	while ($row = mysql_fetch_assoc ($result)) {
		$a++;
		$page_name[$a] = $row['page_name'];

		if ($a == 1) { $SEL = "SELECTED"; } else { $SEL = ""; }
		if(preg_match('/^cartid:/', $row['page_name'])){
			$kfield = str_replace(':', '', str_replace('cartid:', '', $row['page_name']));
			$displayname = $cartcats[$kfield];
			//$cartcats = mysql_query("select category from cart_category where keyfield='".$kfield."'");
			$page_data .= "     <OPTION VALUE='".$row['page_name']."' $SEL>[cart] ".$displayname."</OPTION>\n";
		} else {
			$page_data .= "     <OPTION VALUE='".$row['page_name']."' $SEL>".$row['page_name']."</OPTION>\n";	
		}
		
	}
	
	
	

	$num_pages = $a;
	$HCALC = $num_pages * 18;
	$box_size = $num_pages + 2;

	// Sort current Menu Setup and fix display variable in Memory

	$current_menu = array( );

	// Modified 2002-10-24 to eliminate "All" pages displaying on menu system
	// when "No Menu" has been set or blanked

	$result = mysql_query("SELECT prikey, page_name, url_name, type, sub_page_of, password, main_menu, link, username FROM site_pages WHERE main_menu <> '' ORDER BY main_menu");
	$found = mysql_num_rows($result);

	if ($found > 0) {

		while ($row = mysql_fetch_array ($result)) {

			if ($row[main_menu] != "") {

				$current_menu[] = $row[page_name];

				$subs = mysql_query("SELECT prikey, page_name, url_name, sub_page_of FROM site_pages WHERE sub_page_of LIKE '$row[page_name]~~~%'");
				$test = mysql_num_rows($subs);

				if ($test > 0) {

					unset($dSubz);

					// Pre-build sub page array with correct order
					// --------------------------------------------------
					$s = 0; // Sub page counter for array

					while ($sub_array = mysql_fetch_array ($subs)) {
						$sTmp = split("~~~", $sub_array[sub_page_of]);
						$dSubz[$s] = $sTmp[1].":::".$sub_array[page_name];
						$s++;
					}

					sort($dSubz, SORT_NUMERIC);

					// Loop through sub page array and add to current menu
					// -----------------------------------------------------
					for ( $b=0; $b < count($dSubz); $b++ ) {
					   $tSubz = split(":::", $dSubz[$b]);
					   $current_menu[] = ">> " . $tSubz[1];
						//$current_menu[] = ">> " . $sub_array[page_name];
						//echo $tSubz[1]."<br>\n";
					}

				} // end if sub pages exist

		  } // if End Main_Menu Blank check
	   } // while

	} // End if Menu Setting where found



	// Display GUI

   // Available Pages
   // ------------------------------------------------------------
	echo "               <tr>\n";
	echo "                <td class=text valign=top align=center>\n";
	echo "                 <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"text\">\n";
	echo "                  <tr>\n";
	echo "                   <td align=\"center\" style=\"color: #FF0000;\">\n";
	echo                     lang("Available Pages");
	echo "                   </td>\n";
	echo "                  </tr>\n";
	echo "                  <tr>\n";
	echo "                   <td align=\"center\">\n";
	echo "                    <SELECT NAME=SELECTPAGES SIZE=$box_size class=\"menupg_list\">\n";
	echo "                     ".$page_data;
	echo "                    </SELECT>\n";
	echo "                   </td>\n";
	echo "                  </tr>\n";
	echo "                  <tr>\n";
	echo "                   <td align=\"center\">\n";
	echo "                    <a href='create_pages.php?=SID'>[ Create New Pages ]</a>\n";
	echo "                   </td>\n";
	echo "                  </tr>\n";
	echo "                 </table>\n";
	echo "                </td>\n\n";

   // Add Main & Add Sub buttons
   // ------------------------------------------------------------
	echo "                <td class=\"text\" valign=\"top\" align=\"center\" style=\"padding-top: 55px; width: 130px;\">\n";
	echo "                 <button type=\"button\" class=\"blueButton\" onclick='add_main();'><span><span>".lang("Add as Main >>")."</span></span></button><BR><BR><BR>\n";
	echo "                 <button type=\"button\" class=\"blueButton\" onclick='add_sub();'><span><span>".lang("Add as Sub >>")."</span></span></button><br/><br/><br/>\n";
	//echo "                 <input type=\"button\" value='&lt;&lt; Remove Item' ".$btn_edit." onclick='remove_item();' style=\"color: #980000;\">\n";
	echo "                </td>\n";
	echo "                <td class=text valign=top align=center>\n";
	echo "                 <table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" class=\"text\">\n";
	echo "                  <tr>\n";
	echo "                   <td align=\"center\" colspan=\"1\" style=\"color: #FF0000;\">\n";
	echo                     lang("Current Menu");
	echo "                   </td>\n";
	echo "                  </tr>\n";
	echo "                  <tr>\n";
	echo "                   <td align=\"center\" valign=\"top\">\n";
	echo "                    <select name='menu_order' align='left' size='$box_size' class=\"menupg_list\">\n";

   // Echo option values for current menu list
   foreach( $current_menu as $menuitem ) {

      // Bold main menu items
      if ( eregi(">>", $menuitem) ) {
         $msub = " style=\"color: #6699CC;\"";
      } else {
         $msub = "";
      }

	if(preg_match('/cartid:/', $menuitem)){
		
		$kfield = str_replace(':', '', str_replace('cartid:', '', preg_replace('/(&gt;&gt; |>> )?/', '', $menuitem)));
		$displayname = preg_replace('/cartid:[0-9]+:/', '[cart] '.$cartcats[$kfield], $menuitem);
//		$cartcatsie = mysql_query("select category from cart_category where keyfield='".$kfield."'");		
		echo "                     <option value='" . htmlspecialchars( $menuitem, ENT_NOQUOTES ) . "'".$msub.">" .htmlspecialchars( $displayname ) . "</option>\n" ;
	} else {
		echo "                     <option value='" . htmlspecialchars( $menuitem, ENT_NOQUOTES ) . "'".$msub.">" . htmlspecialchars( $menuitem ) . "</option>\n" ;	
	}

    	
   }

	echo "                    </select>\n";
	echo "                    <TEXTAREA STYLE=\"display: none\"' NAME='newMenu' id='newMenu'>$current_menu</TEXTAREA>\n\n";
	echo "                   </td>\n";
	echo "                   <td align=\"left\" valign=\"top\">\n";
	echo "                    <br><button type=\"button\" class='blueButton' onclick='up();'><span><span>".lang("[^] Move Up")."</span></span></button>\n";
	echo "                    <br><br/><button type=\"button\" class='blueButton' onclick='down();'><span><span>".lang("[v] Move Down")."</span></span></button>\n";
	//echo "                    <br><br><br><br><br><input type=button value='Remove Item' class='btn_red hand' onclick='remove_item();' style=\"width: 80px;\">\n";
	echo "                 <br/><br/><br/><br/><button type=\"button\" class=\"blueButton\" onclick='remove_item();'><span><span>".lang("Remove Item")."</span></span></button>\n";
	echo "                    <br/><br/><br/><br/><br/><br/><br><br><br><button type=\"button\" class=\"redButton\" onclick='clear_menu();'><span><span>".lang("Clear Menu")."</span></span></button>\n";
	echo "                   </td>\n";
	echo "                  </tr>\n";
	echo "                 </table>\n";
	echo "                </td>\n";
	echo "               </tr>\n";

	?>

               </table>
               <br/>
               <!---Save menu button-->
		   <div style="width: 150px;"><button type="button" class="greenButton" onClick="save_menu();"><span><span><?php echo lang("Save Menu System"); ?></span></span></button></div>
              </td>
             </tr>
            </table>

     </FORM>

<!-- ########################################################## -->
<!-- #### ADD EXTERNAL LINK TO MENU SYSTEM                  ### -->
<!-- ########################################################## -->

   <FORM NAME="LNKFORM" METHOD="POST" ACTION="menu_system/update_menu.php">
   <input type="hidden" name="do" value="saveLink">
   
	  <table border=0 cellpadding=0 cellspacing=0 width=725 class="feature_contain" style="margin-top: 15px;">
      <tr>
         <th align=left valign=top style="border-right: 0;">
            <IMG SRC="arrow.gif" WIDTH="17" HEIGHT="13" ALIGN="absmiddle"><? echo lang("Add Custom Menu Link"); ?>
         </th>
         <th valign="top" align="right" style="padding-right: 10px;">
            <a onClick="toggleid('custom_link_help');" valign="middle" style="cursor: pointer;" href="#">[?]</a>
         </th>
      </tr>
     <tr>
      <td colspan="3" valign="top" style="border-right: 1px solid #A2ADBC; padding: 5px; ">

       <!---help text-->
       <div id="custom_link_help" style="display: block; padding-top: 0px; margin-top: 0; line-height: 1.3em; text-align: left;">
        <p class="nomar_top" style="" ><? echo lang("If you would like a menu item to link somewhere other than one of your site pages (like to another website, for example),
        you can do that here. Fill in the text you want to display on the menu for the link (i.e. \"Nintendo corporate site\"), and then
        provide the url you want to link to (i.e. \"http://nintendo.com\").");?><b><? echo lang("Please provide the entire url including the http://"); ?></b></p>

        <p><? echo lang("The URL link can be anything you want. For example, if you wanted to link to an email address you could put in
        \"mailto:me@example.com\" for the 'Link to URL' field"); ?>.</p>
       </div>

		<table border="0" cellspacing="0" cellpadding="3" width="100%">
         <tr>
				<td><b><? echo lang("Text to display"); ?>:</b></td>
				<td><b><? echo lang("Link to URL"); ?>..</b></td>
				<td colspan=2>&nbsp;</td>
         </tr>
         <tr>
           <td><input type="text" id="disPage" name="disPage" class="tfield" style="width: 150px;"></td>
           <td><input type="text" id="disLink" name="disLink" class="tfield" style="width: 250px;"></td>
<?php
	echo "				<td style=\"color: #000099;\"><select name=\"new_window\" class=\"text\" style=\"background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;\">\n";
	echo "				<option value=\"same\">".lang("Open In Same Window")."</option>\n";
	echo "				<option value=\"blank\">".lang("Open In New Window")."</option>\n";
	echo "				</select></td>\n";		
?>
           <td>
		   		<button type="button" class="greenButton" onClick="saveLink();"><span><span><?php echo lang("Add Link"); ?></span></span></button>
				</FORM></td>
			</tr>
		</table>
				

      </td>
     </tr>
    </table>
<?php

//	$resultz = mysql_query("SELECT page_name, link FROM site_pages WHERE lcase(link) LIKE lcase('http%') or lcase(link) LIKE lcase('mailto:%')");
	$resultz = mysql_query("SELECT prikey, page_name, url_name, type, sub_page_of, main_menu, link FROM site_pages WHERE type = 'menu'");
	
	$reznum = mysql_num_rows($resultz);
	if($reznum > 0){
		
				echo "	  <table border=0 cellpadding=0 cellspacing=0 width=725 class=\"feature_contain\" style=\"margin-top: 15px;\">\n";
				echo "	      <tr>\n";
				echo "	         <th align=left valign=top>\n";
				echo "	            <IMG SRC=\"arrow.gif\" WIDTH=\"17\" HEIGHT=\"13\" ALIGN=\"absmiddle\">".lang("Manage Custom Links")."\n";
				echo "	         </th>\n";
				echo "	      </tr><tr><td style=\"border-right: 1px solid #A2ADBC; padding: 5px; \">\n";


				echo "			<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">\n";
				echo "			<tr>\n";
            echo "<td><b>\n";
				echo 				lang("Link display Text:");
			echo "</b></td><td><b>\n";
				echo 				lang("Link Url:");
			echo "</b></td>\n";
				echo "				<td colspan=3>&nbsp;</td>\n";
				echo "			</tr>\n";

            
				$camx = 0;

            while ($rowz = mysql_fetch_array ($resultz)) {
            	if(!preg_match('/^http:\/\/shopping\/start\.php/', $rowz['link'])){
	            	$bchecked = '';
	            	if(eregi('blank', $rowz['link'])){
	            		$rowz['link'] = str_replace('" target="_blank' , '', $rowz['link']);
	            		$rowz['link'] = str_replace('#blank' , '', $rowz['link']);
	            		$bchecked = ' SELECTED';	
	            	}
	
						echo "			<tr>\n";
			         		echo "				<td style=\"color: #000099;\"><form name=\"update_custom_".$camx."\" method=\"POST\" action=\"auto_menu_system.php\" style=\"display:inline;\"><input type=\"hidden\" name=\"rename_cust_page\" value='".$rowz['prikey']."'><input type=\"text\" name=\"edit_cust_page_new\" class=\"tfield\" style=\"width: 150px;\" value='".$rowz['page_name']."'></td>\n";
			         		if(basename($rowz['link'])=='search.php'){
			         			$searchdisable=' readonly="readonly" ';
			         		} else {
			         			$searchdisable='';	
			         		}
						echo "				<td style=\"color: #000099;\"><input type=\"hidden\" name=\"relink_cust_page\" value='".$rowz['link']."'><input type=\"text\" name=\"relink_cust_page_new\" class=\"tfield\" style=\"width: 150px;\" ".$searchdisable." value='".$rowz['link']."'></td>\n";
						
						echo "				<td style=\"color: #000099;\"><select name=\"new_window\" class=\"text\" style=\"background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: -moz-initial; -moz-background-origin: -moz-initial; -moz-background-inline-policy: -moz-initial;\">\n";
						echo "				<option value=\"same\">".lang("Open In Same Window")."</option>\n";
						echo "				<option value=\"blank\"".$bchecked.">".lang("Open In New Window")."</option>\n";
						echo "				</select></td>\n";			
						
						echo "				<td style=\"color: #000099;\"><button type=\"button\" class=\"blueButton\" onclick=\"document.update_custom_".$camx.".submit();\"><span><span>".lang("Save Link")."</span></span></button></form></td>\n";
						if(basename($rowz['link'])=='search.php'){
							echo "				<td>&nbsp;</td>\n";
						} else {
							echo "				<td><form name=\"delete_custom_".$camx."\" method=\"POST\" action=\"auto_menu_system.php\" style=\"display:inline;\"><button type=\"button\" class=\"redButton\" onclick=\"document.delete_custom_".$camx.".submit();\"><span><span>".lang("Remove Link")."</span></span></button><input type=hidden name=remove_custom value=\"".$rowz['prikey']."\"></form></td>\n";	
						}						
						echo "			</tr>\n";
	            	$camx++;
          	}
            }

				echo "		</table>\n";
				echo "      </td>\n";
				echo "     </tr>\n";
				echo "    </table>\n";
	}
?>



</FORM>




<?php
if ($ok == 1) { $report[] = "New Menu Layout has been saved."; }
if ($cok == 1) { $report[] = 'New button colors have been saved.'; }
if ($lok == 1) { $report[] = 'External Page Link Added.'; }
if ($linkc == "") { $menubg = "5F9EA0"; $linkc = "FFFFFF"; }
?>

<?
# Grab module html into container var
$module_html = ob_get_contents();
ob_end_clean();

$instructions = lang("Customize your site menu here.  Add/Remove pages, text or buttons, button color and add custom menu links.");

# Build into standard module template
$module = new smt_module($module_html);
$module->meta_title = "Menu Navigation";
$module->add_breadcrumb_link("Look & Feel", "program/modules/site_templates.php");
$module->add_breadcrumb_link("Menu Navigation", "program/modules/auto_menu_system.php");
$module->icon_img = "skins/".$_SESSION['skin']."/icons/full_size/edit_pages-enabled.gif";
$module->heading_text = lang("Menu Navigation");
$module->description_text = $instructions;

$module->good_to_go();
?>
