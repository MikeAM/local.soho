<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

session_start();
error_reporting(E_PARSE);
# Let's try this 
include("../../../../includes/emulate_globals.php");
include("../../includes/product_gui.php");
?>

<html>
<head>
<title>Page Editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<link rel="stylesheet" type="text/css" href="../../product_gui.css">
<script language="JavaScript" src="../../includes/display_elements/js_functions.php"></script>


<style type="text/css">
	.ob1 { background: #3E99DF; border: outset #99CCFF 2px; }
	.ob2 { background: #3E99DF; padding: 1px; border: ridge #245981 1px; }
</style>

<script language="javascript">

   this_ip = '<? echo $this_ip; ?>';

function layerSelect(layer){
   document.getElementById(layer).style.display='block';
}

function loadForm() {
   alert('something');
   loadwindow('formlib/forms.php?selkey=Forms&=SID',600,500,'that');
}

function sendDis(){
   window.opener.putThis('billy');
}

function OkLoginData() {
	doOperation = 0;
	tloginbutton = loginbutton.value;
	//alert(tloginbutton);
	tloginbutton = tloginbutton.toString();

   //TextHeader = "<img src=images/text_header.gif width=199 height=15 border=0 align=left vspace=0 hspace=0 style='cursor: move;'><BR CLEAR=ALL>";
	TableStart = "<table border=0 cellpadding=2 cellspacing=0 style='border: 1px inset black; padding: 2px;'><tr><td width=199 align=center valign=top bgcolor=#EFEFEF>";
   TableEnd = "</td></tr></table><!-- ~~~ -->";
	
	if (tloginbutton != "") {
	   //alert('something');
		sText = TableStart+"<input type=button value=\""+tloginbutton+"\" id=SECURELOGIN class=FormLt1><BR><BR><FONT FACE=ARIAL STYLE='FONT-SIZE: 7pt;'><b><?php echo lang("Forgotten your password?"); ?> <U><FONT COLOR=BLUE>Click Here</FONT></U>.<!-- ##EDITOR_SECURELOGIN;"+tloginbutton+"## -->"+TableEnd;
		window.opener.putThis(sText);
	} else {
		alert("You did not fill in a name for the button.");
	}
}

// ========================================================= 
// SHOPPING CART SKU PLACEMENT
// =========================================================

function OkCartSku() {

	doOperation = 0;
	   
	disOne = SINGLESKU.selectedIndex;
	tSingleSku = eval("SINGLESKU.options["+disOne+"].value");
	tSingleSku = tSingleSku.toString();

	TableStart = "<table border=0 width=199 cellpadding=0 cellspacing=0 style='border: 1px inset black; background: #EFEFEF'><tr><td width=199 align=center valign=top>";
   TableEnd = "</td></tr></table><!-- ~~~ -->";

	if (tSingleSku != "NONE") {
		sText = TableStart+"<div ID=SKUPROMO align=center><img class=tHead src=http://"+this_ip+"/sohoadmin/program/modules/page_editor/client/singlesku.gif width=199 height=196 border=1><BR clear=all><font face=Arial size=1><B>Par No. (sku): <U>"+tSingleSku+"</U></div><!-- ##EDITOR_SINGLESKU;"+tSingleSku+"## -->"+TableEnd;
      window.opener.putThis(sText);
	}
}

// ========================================================= 

function placeCartSearch() {

	sText = "<img src=http://"+this_ip+"/sohoadmin/program/modules/page_editor/client/cartsearch_tool.gif width=199 height=71 border=0 class=tHead vspace=0 hspace=0><!-- ##EDITOR_CARTSEARCH## -->";
	window.opener.putThis(sText);

}

// ========================================================= 
	
function OkBlog() {
	
	disOne = blogsubj.selectedIndex;
	tBlogSubject = eval("blogsubj.options["+disOne+"].value");	
   tBlogSubject = tBlogSubject.toString();

	TableStart = "<table border=0 cellpadding=2 cellspacing=0 style='border: 1px inset black; background: #EFEFEF;'><tr><td width=199 align=center valign=top>";
   TableEnd = "</td></tr></table><!-- ~~~ -->";

	sText = TableStart+"<font style='font-family: Arial; font-size: 8pt;'><U><font color=darkblue>Blog : "+tBlogSubject+"</font></U></FONT><!-- ##EDITOR_BLOG;"+tBlogSubject+"## -->"+TableEnd;
   window.opener.putThis(sText);
}

// ========================================================= 

function OkFaq() {

	disOne = faqcat.selectedIndex;
	tFaqCat = eval("faqcat.options["+disOne+"].value");	

	tFaqCat = tFaqCat.toString();

	sText = "<font style='font-family: Arial; font-size: 8pt;'><U><font color=darkblue>Faq : "+tFaqCat+"</font></U></FONT><!-- ##EDITOR_FAQ;"+tFaqCat+"## -->";
   window.opener.putThis(sText);
}






</script>

</head>





<body>
<!--###########################################################################################################
###   Begin layer select
###########################################################################################################-->

<div id="objectbar" style="position:absolute; left:0px; top:0px; width:100%; height:100%; z-index:5; overflow: none; background-color: background; layer-background-color: background; border: 1px solid blue;">
<table width="300" align="center" border="0" cellspacing="1" cellpadding="2">
  <tr>
    <td align="center"><font color="#FFFFFF">Please select a module to insert.</font></td>
  </tr>
  <tr>
    <td>
      <table width="240"  border="0" align="center" cellpadding="5" cellspacing="1">
        <tr>
          <td colspan="2"><img class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" src="obj_bar_icons/eng-icon_login.gif" width="80" height="18" onClick="show_hide_layer('objectbar','','hide','securelayer','','show');"></td>
          <td><img class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" src="obj_bar_icons/eng-icon_shopping.gif" width="80" height="18" onClick="show_hide_layer('objectbar','','hide','shoppingCartLayer','','show');"></td>
        </tr>
        <tr>
          <td><img class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" src="obj_bar_icons/eng-icon_blog.gif" width="80" height="18" onClick="show_hide_layer('objectbar','','hide','blogLayer','','show');"></td>
          <td><img class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" src="obj_bar_icons/eng-icon_faq.gif" width="80" height="18" onClick="show_hide_layer('objectbar','','hide','faqLayer','','show');"></td>
          <td><img class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" src="obj_bar_icons/eng-icon_photoalbum.gif" width="80" height="18"></td>
        </tr>
        <tr>
          <td colspan="2"><img class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" src="obj_bar_icons/eng-icon_socialmedia.gif" width="80" height="18"></td>
          <td><img class="ob2" onmouseover="this.className='ob1';" onmouseout="this.className='ob2';" src="obj_bar_icons/eng-icon_calendar.gif" width="80" height="18"></td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</div>


<DIV ID="securelayer" style="position:absolute; left:0px; top:0px; width:100%; height:100%; z-index:4; overflow: none; background-color: oldlace; visibility: hidden" onMouseOver="HighDropZone();">

<table border=1 cellpadding=0 cellspacing=0 width=100% height=100% style='border: 1px inset black;'>
	<tr>
	<td align=center valign=middle>

		<table border=0 cellpadding=4 cellspacing=0 width=100%>
		<tr><td align=center valign=top class=ctable>

		Enter the text to display on login button:<BR>
		<input type=text size=32 id=loginbutton name=loginbutton value="Member Login">

		</td><td align=center valign=middle>
		
		<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="OkLoginData();show_hide_layer('objectbar','','show','securelayer','','hide');">
		&nbsp;&nbsp;<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="show_hide_layer('objectbar','','show','securelayer','','hide');">

		</td></tr></table>

	</td>
	</tr>
</table>

</DIV>

<!-- ############################################################# -->
<!-- #### BEGIN SHOPPING CART OPTION LAYER (3.5 MOD)		  #### -->
<!-- ############################################################# -->

<div id="shoppingCartLayer" style="position:absolute; left:0px; top:0px; width:100%; height:115; z-index:4; overflow: none; background-color: oldlace; visibility: hidden" onMouseOver="HighDropZone();">

	<table border=0 cellpadding=0 cellspacing=0 width=100% height=100% STYLE='BORDER: 1px inset black;'>
	<tr>
	<td align=center valign=middle width="65%" class="text" style="font-family: arial, helvetica, sans-serif; font-size: 12px; color: #2E2E2E;">


		<? 

		$tmp = mysql_query("SELECT PROD_SKU, PROD_NAME FROM cart_products ORDER BY PROD_NAME");
		$tmp_cnt = mysql_num_rows($tmp);
	
		if ($tmp_cnt > 0) {		// Sku's Exist in product database
	

				echo "<table border=0 cellpadding=5 cellspacing=0 width=400 style='border: 1px inset black; background: #EFEFEF' align=center><TR>\n";
				echo "<td align=center valign=top class=ctable width=275>\n\n";
	
				echo "<FONT COLOR=$header_text>Single Product Promotion (Link):<BR>";
				echo "<SELECT id=SINGLESKU NAME=SINGLESKU STYLE='font-family: Arial; font-size: 8pt; width: 250px;'>\n";
				echo "<OPTION VALUE=\"NONE\" SELECTED>Current Products...</OPTION>\n";
		
				while($sku = mysql_fetch_array($tmp)) {
	
					echo "<OPTION VALUE=\"$sku[PROD_SKU]\">$sku[PROD_NAME]</OPTION>\n";
	
				}
	
				echo "</SELECT>";
	
				echo "</td><td align=left valign=bottom class=ctable width=150>\n";
	
				echo "<input type=button class=mikebut onMouseOver=\"this.className='mikebutOn';\" onMouseOut=\"this.className='mikebut';\" value=\" Place Item \" STYLE='WIDTH: 80px;' onClick=\"OkCartSku();show_hide_layer('objectbar','','show','shoppingCartLayer','','hide');\">\n";
	
				echo "</td>\n";
	
				echo "</tr></table>\n";
	
		} else {
		   echo lang("Once you have created at least one product sku in the shopping cart ");
		   echo lang("a drop-down menu will appear in this space so that you may place a single sku onto the page.");
		}
		
		 // End sku verify and populate option
	
		?>

		<td align=center valign=middle class=ctable width=45%>
		
		<input type=button class=calbut onMouseOver="this.className='calbutOn';" onMouseOut="this.className='calbut';" value="Place Search/Browse Box" onClick="placeCartSearch();show_hide_layer('objectbar','','show','shoppingCartLayer','','hide');">
		&nbsp;&nbsp;<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " STYLE='WIDTH: 50px;' onClick="show_hide_layer('objectbar','','show','shoppingCartLayer','','hide');">
		

		</td></tr></table>

	</td>
	</tr>
</table>

</div>

<!-- ############################################################# -->
<!-- #### BEGIN FAQ SELECTION LAYER (4.5 030804)			  #### -->
<!-- ############################################################# -->

<DIV ID="faqLayer" style="position:absolute; left:0px; top:0px; width:100%; height:115; z-index:4; overflow: none; background-color: oldlace; visibility: hidden" onMouseOver="HighDropZone();">

	<table border=1 cellpadding=0 cellspacing=0 width=100% height=100% style='border: 1px inset black;'>
	<tr>
	<td align=center valign=middle>
	
		<table border=0 cellpadding=2 cellspacing=0 width=100%>
		<tr>
		<td align=center valign=top class=ctable>
		
		Which FAQ Category should appear here? &nbsp;
		<SELECT id="faqcat" NAME="faqcat" style='font-face: Arial; font-size: 8pt; width: 250px;'>
			<option value="NULL" STYLE='color:#999999;'>FAQ Categories:</option>

			<?
			
			# Pull faq cats from table (if available)
			$faq_result = mysql_query("SELECT * FROM faq_category ORDER BY CAT_NAME");
			
			if ( !$faq_result = mysql_query("SELECT * FROM faq_category ORDER BY CAT_NAME") ) {
				echo "\n\n\n<!---Cannot select from faq table: ".mysql_error()."---->\n\n\n";
				
			} else {
   			# Build faq drop-down options
   			while($faqs = mysql_fetch_array($faq_result)) {
               if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
               echo "<option value=\"$faqs[CAT_NAME]\" STYLE='background: $tmp;'>$faqs[CAT_NAME]</option>\n";
   			}
   		}
			
			?>

		</SELECT>
		
		</td>
		<td align=center valign=middle>
		
	 	<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="OkFaq();show_hide_layer('objectbar','','show','faqLayer','','hide');">
		&nbsp;&nbsp;<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="show_hide_layer('objectbar','','show','faqLayer','','hide');">

		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
	
</DIV>

<!-- ############################################################# -->
<!-- #### BEGIN BLOG SELECTION LAYER (4.5 030804)			  #### -->
<!-- ############################################################# -->

<DIV ID="blogLayer" style="position:absolute; left:0px; top:0px; width:100%; height:115; z-index:4; overflow: none; background-color: oldlace; visibility: hidden" onMouseOver="HighDropZone();">

	<table border=1 cellpadding=0 cellspacing=0 width=100% height=100% style='border: 1px inset black;'>
	<tr>
	<td align=center valign=middle>
	
		<table border=0 cellpadding=2 cellspacing=0 width=100%>
		<tr>
		<td align=center valign=top class=ctable>
		
		Which Blog Subject should appear here? &nbsp;
		<SELECT id="blogsubj" NAME="blogsubj" style='font-face: Arial; font-size: 8pt; width: 250px;'>
			<option value="NULL" STYLE='color:#999999;'>Blog Subjects:</option>

			<?
			//echo "IN HERE -----------\n";
			$blog_result = mysql_query("SELECT * FROM blog_category ORDER BY CATEGORY_NAME");
			while($blogs = mysql_fetch_array($blog_result)) {
					if ($tmp == "#EFEFEF") { $tmp = "WHITE"; } else { $tmp = "#EFEFEF"; }
					echo "<option value=\"$blogs[CATEGORY_NAME]\" STYLE='background: $tmp;'>$blogs[CATEGORY_NAME]</option>\n";
			}
			
			?>

		</SELECT>
		
		</td>
		<td align=center valign=middle>
		
	 	<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" OK " onClick="OkBlog();show_hide_layer('objectbar','','show','blogLayer','','hide');">
		&nbsp;&nbsp;<input type=button class=mikebut onMouseOver="this.className='mikebutOn';" onMouseOut="this.className='mikebut';" value=" Cancel " onClick="show_hide_layer('objectbar','','show','blogLayer','','hide');">

		</td>
		</tr>
		</table>
	</td>
	</tr>
	</table>
	
</DIV>





</body>
</html>

<?
div_window();
?>

