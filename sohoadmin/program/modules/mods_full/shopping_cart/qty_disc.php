<?php
error_reporting('E_PARSE');
session_start();

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../../includes/product_gui.php');
chdir($curdir);


$tablename = "qty_discounts";
if ( !table_exists($tablename) ) {
   $wDeez .= "sku VARCHAR(255)"; // Name of page that link appears on
   $wDeez .= ", disc_type VARCHAR(255)"; // Human-friendly for user reference
   $wDeez .= ", required_qty VARCHAR(255)"; // String identifier for particular link (user-defined in href)
   $wDeez .= ", min_qty BLOB"; // Number of times this link has been clicked
	$wDeez .= ", max_qty BLOB"; // Number of times this link has been clicked
	$wDeez .= ", disc BLOB"; // Number of times this link has been clicked

   $create_qry = "CREATE TABLE ".$tablename." (".$wDeez.")";
   if ( !mysql_db_query($db_name, $create_qry) ) { echo "Unable to create ".$tablename." table!<br>".mysql_error(); }
}

$get_discs = mysql_query("select * from qty_discounts where sku='".$SKU[PROD_SKU]."'");
$discnums = mysql_num_rows($get_discs);
if($discnums == 0) {
	$discnums = 1;
	$min_qty['0'] = '1';
	$max_qty['0'] = '9999999';
	$disc['0'] = '0';
	$discsarray['required_qty'] = '1';
} else {
$discsarray = mysql_fetch_array($get_discs);
	$discnums = count(unserialize($discsarray['min_qty']));	
	$min_qty = unserialize($discsarray['min_qty']);
	$max_qty = unserialize($discsarray['max_qty']);
	$disc = unserialize($discsarray['disc']);	
}
/*---------------------------------------------------------------------------------------------------------*
 ___       _           __   __           _        _    _
| _ \ _ _ (_) __  ___  \ \ / /__ _  _ _ (_) __ _ | |_ (_) ___  _ _
|  _/| '_|| |/ _|/ -_)  \ V // _` || '_|| |/ _` ||  _|| |/ _ \| ' \
|_|  |_|  |_|\__|\___|   \_/ \__,_||_|  |_|\__,_| \__||_|\___/|_||_|
/*---------------------------------------------------------------------------------------------------------*/
# Restore price variation arrays 
	$THIS_DISPLAY .= "<script language=\"JavaScript\"> \n";
	$THIS_DISPLAY .= "var countenstuff=Number(0); \n";
	
	$THIS_DISPLAY .= "function showdivlayerz(divnum) { \n";
	
//	$THIS_DISPLAY .= "  alert( document.getElementById(min_qty7).value ) \n";
	$THIS_DISPLAY .= "	var divnum = Number(divnum)+Number(countenstuff); \n";
	//$THIS_DISPLAY .= "	alert(divnum); \n";
	$THIS_DISPLAY .= "		var divnumo = Number(divnum); \n";

	$THIS_DISPLAY .= "	var divnumo = Number(divnumo) - 1; \n";
	
		//$THIS_DISPLAY .= "	var min_qtyzo=\"min_qty6\"; \n";
	$THIS_DISPLAY .= "	var min_qtyzo=\"min_qty\"+divnumo; \n";
	$THIS_DISPLAY .= "	var max_qtyzo=\"max_qty\"+divnumo; \n";
	//$THIS_DISPLAY .= "	alert(document.getElementById(min_qtyzo).value); \n";
	$THIS_DISPLAY .= "	var newmaxval = Number(document.getElementById(min_qtyzo).value) + 1; \n";
	$THIS_DISPLAY .= "  document.getElementById(max_qtyzo).value = newmaxval; \n";

	$THIS_DISPLAY .= "  document.getElementById(max_qtyzo).disabled = false; \n";
	
	
	//$THIS_DISPLAY .= "	alert(divnumo); \n";
	$THIS_DISPLAY .= "	var min_qtyz=\"min_qty\"+divnum; \n";
	$THIS_DISPLAY .= "	var discz=\"disc\"+divnum; \n";
	$THIS_DISPLAY .= "	var max_qtyz=\"max_qty\"+divnum; \n";
	$THIS_DISPLAY .= "	var divname=\"qtyfield\"+divnum; \n";
	
	
	$THIS_DISPLAY .= "	var newnewmaxval = Number(newmaxval) + 1; \n";
	$THIS_DISPLAY .= "  document.getElementById(min_qtyz).value=newnewmaxval;  \n";
	
	$THIS_DISPLAY .= "  document.getElementById(divname).style.display='block'; \n";
	$THIS_DISPLAY .= "  document.getElementById(min_qtyz).disabled = false; \n";
	$THIS_DISPLAY .= "  document.getElementById(max_qtyz).disabled = true; \n";
	$THIS_DISPLAY .= "  document.getElementById(discz).disabled = false; \n";
	$THIS_DISPLAY .= "countenstuff++; \n";
	
	
	
	
	$THIS_DISPLAY .= "} \n";  
	
	$THIS_DISPLAY .= "function removedivlayerz(divrem) { \n";
	$THIS_DISPLAY .= "	var dcount = Number(divrem) + Number(countenstuff); \n";
	$THIS_DISPLAY .= "	var divrem = Number(divrem); \n";
	$THIS_DISPLAY .= "	if(dcount > 1) { \n";
	$THIS_DISPLAY .= "		countenstuff--; \n";
	
	$THIS_DISPLAY .= "		var divrem = Number(divrem)+Number(countenstuff); \n";


	$THIS_DISPLAY .= "		var min_qtyzz=\"min_qty\"+divrem; \n";
	$THIS_DISPLAY .= "		var max_qtyzz=\"max_qty\"+divrem; \n";
	$THIS_DISPLAY .= "		var divnamez=\"qtyfield\"+divrem; \n";
	$THIS_DISPLAY .= "		var disczz=\"disc\"+divrem; \n";
	$THIS_DISPLAY .= "		var divremnewmax = Number(divrem) - 1; \n";
	$THIS_DISPLAY .= "		var newmax=\"max_qty\"+divremnewmax; \n";
	$THIS_DISPLAY .= "		document.getElementById(newmax).value = 'or more'; \n";
	$THIS_DISPLAY .= "		document.getElementById(newmax).disabled = true; \n";
	
	$THIS_DISPLAY .= "		document.getElementById(divnamez).style.display='none'; \n";
	$THIS_DISPLAY .= "		document.getElementById(min_qtyzz).disabled = true; \n";
	$THIS_DISPLAY .= "		document.getElementById(max_qtyzz).disabled = true; \n";
	$THIS_DISPLAY .= "		document.getElementById(disczz).disabled = true; \n";
	$THIS_DISPLAY .= "	} \n";
//	$THIS_DISPLAY .= "  document.getElementById(discz).disabled = false; \n";

	$THIS_DISPLAY .= "} \n";  
	
	
	$THIS_DISPLAY .= "function fix_min(fnum,lastmax) { \n";
	$THIS_DISPLAY .= "	var lastmax = Number(lastmax) + 1; \n";
	$THIS_DISPLAY .= "	var fnum = Number(fnum); \n";
	$THIS_DISPLAY .= "	var fminnum = Number(fnum) + 1; \n";
	$THIS_DISPLAY .= "	var min_qtyval=\"min_qty\"+fminnum; \n";
	$THIS_DISPLAY .= "	var divname=\"qtyfield\"+fnum; \n";
	$THIS_DISPLAY .= "  document.getElementById(min_qtyval).value = lastmax; \n";
	$THIS_DISPLAY .= "} \n";  
	

	$THIS_DISPLAY .= "function fix_max(fnum,lastmax) { \n";
	$THIS_DISPLAY .= "	var lastmax = Number(lastmax) - 1; \n";
	$THIS_DISPLAY .= "	var fnum = Number(fnum); \n";
	$THIS_DISPLAY .= "	var fminnum = Number(fnum) - 1; \n";
	$THIS_DISPLAY .= "	var max_qtyval=\"max_qty\"+fminnum; \n";
	$THIS_DISPLAY .= "	var divname=\"qtyfield\"+fnum; \n";
	$THIS_DISPLAY .= "  document.getElementById(max_qtyval).value = lastmax; \n";
	$THIS_DISPLAY .= "} \n";  
	
	$THIS_DISPLAY .= "</script>";

//echo "<pre>".$SKU['sub_cats']."</pre>";
//echo testArray(unserialize($SKU['sub_cats']));

$THIS_DISPLAY .= "  <div id=\"PRODDISC\" style='display: block;'>\n";
//$THIS_DISPLAY .= "   <input type=\"hidden\" name=\"num_variants\" value=\"".$num_variants."\">\n";
$THIS_DISPLAY .= "   <table border=\"0\" cellpadding=5 cellspacing=\"0\" width=\"100%\" class=\"feature_sub\" style=\"border: 1px solid #ccc;\">\n";
$THIS_DISPLAY .= "    <tr>\n\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "      <br>\n";

$THIS_DISPLAY .= "      <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=50% align=\"center\">\n";

$THIS_DISPLAY .= "<tr>\n";
$THIS_DISPLAY .= "         <h3>Quantity&nbsp;Discounts</h3>\n";
$THIS_DISPLAY .= "<td align=left class=\"text\"><strong>Discount&nbsp;type:</strong></td>\n";
$dolcheck = '';
$percheck = '';
if($discsarray['disc_type'] != '$') {
	$percheck = 'checked';
} else {
	$dolcheck = 'checked';
}
$THIS_DISPLAY .= "<td align=left class=\"text\"><strong>%</strong><input type=\"radio\" name=\"disc_type\" value=\"%\" ".$percheck.">&nbsp;&nbsp;\n";
$THIS_DISPLAY .= " <strong>$</strong><input type=\"radio\" name=\"disc_type\" value=\"$\" ".$dolcheck."></td>\n";
$THIS_DISPLAY .= "<td align=left class=\"text\"><strong>Min&nbsp;Qty&nbsp;Required:</strong> ";
$THIS_DISPLAY .= " <input name=\"required_qty\" id=\"required_qty\" maxlength=\"10\" value=\"".$discsarray['required_qty']."\" style=\"width: 50px;\" type=\"text\" onblur=\"document.getElementById('min_qty0').value=document.getElementById('required_qty').value\"></td>\n";
$THIS_DISPLAY .= "</tr>\n";
$THIS_DISPLAY .= "      </table>\n";

$THIS_DISPLAY .= "      <table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=50% align=\"center\">\n";

# Sub-Categories

	$THIS_DISPLAY .= "<tr>\n";
   $THIS_DISPLAY .= "<td class=\"text\" width=\"12px\"><br/></td>\n";
   $THIS_DISPLAY .= "<td class=\"text\" width=\"52px\"><br/>Min&nbsp;Qty</td>\n";
   $THIS_DISPLAY .= "<td class=\"text\" width=\"15px\"><br/></td>\n";
   $THIS_DISPLAY .= "<td class=\"text\" width=\"52px\"><br/>Max&nbsp;Qty</td>\n";   
   $THIS_DISPLAY .= "<td class=\"text\" width=\"52px\"><br/>Discount</td>\n";
	$THIS_DISPLAY .= "<td class=\"text\"><br/></td>\n";
   $THIS_DISPLAY .= "</tr></table>\n";
   
	$THIS_DISPLAY .= "<div id=\"qtyfield0\" name=\"qtyfield0\" style=\"display:block;\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=50% align=\"center\">\n";
	$THIS_DISPLAY .= "<tr>\n";
	$THIS_DISPLAY .= "<td class=\"text\" width=\"12px\">1 </td> \n";
	$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"15px\">\n";
	$THIS_DISPLAY .= "<input id=\"min_qty0\" name=\"min_qty[]\" maxlength=\"10\" value=\"".$discsarray['required_qty']."\" style=\"width: 50px;\" type=\"text\" READONLY>\n";
	$THIS_DISPLAY .= "</td> \n";
	$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"15px\">-</td>\n";
	$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"52px\">\n";
	
	if($max_qty['0'] == '9999999') {
		$max_qty['0'] = 'or more';	
	}
	$THIS_DISPLAY .= "<input id=\"max_qty0\" name=\"max_qty[]\" maxlength=\"10\" value=\"".$max_qty['0']."\" style=\"width: 50px;\" type=\"text\" onblur=\"fix_min('0',document.getElementById('max_qty0').value);\"> \n";
	
	
	$THIS_DISPLAY .= "</td> \n";
	$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"52px\">\n";
	$THIS_DISPLAY .= "<input id=\"disc0\" name=\"disc[]\" maxlength=\"10\" value=\"".$disc['0']."\" style=\"width: 50px;\" type=\"text\"></td> \n";
	$THIS_DISPLAY .= "<td align=left class=\"text\">&nbsp;</td>\n";
	$THIS_DISPLAY .=  "</tr></table></div>\n";
   
$x = 1;

while($x < $discnums) {

	if(($x+1)==$discnums){
		$THIS_DISPLAY .= "<div id=\"qtyfield".$x."\" name=\"qtyfield".$x."\" style=\"display:block;\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=50% align=\"center\">\n";
		$THIS_DISPLAY .= "<tr>\n";
		$THIS_DISPLAY .= "<td class=\"text\" width=\"12px\">".($x+1)." </td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"15px\">\n";
		$THIS_DISPLAY .= "<input id=\"min_qty".$x."\" name=\"min_qty[]\" maxlength=\"10\" value=\"".$min_qty[$x]."\" style=\"width: 50px;\" type=\"text\" onblur=\"fix_max('".$x."',document.getElementById('min_qty".$x."').value);\">\n";
		$THIS_DISPLAY .= "</td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"15px\">-</td>\n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"52px\">\n";
		$THIS_DISPLAY .= "<input id=\"max_qty".$x."\" name=\"max_qty[]\" maxlength=\"10\" value=\"or more\" style=\"width: 50px;\" type=\"text\" DISABLED> \n";
		$THIS_DISPLAY .= "</td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"52px\">\n";
		$THIS_DISPLAY .= "<input id=\"disc".$x."\" name=\"disc[]\" maxlength=\"10\" value=\"".preg_replace('/[aray]+/i', '', $disc[$x])."\" style=\"width: 50px;\" type=\"text\"></td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\">&nbsp;</td>\n";
		$THIS_DISPLAY .=  "</tr></table></div>\n";
	} else {
		$THIS_DISPLAY .= "<div id=\"qtyfield".$x."\" name=\"qtyfield".$x."\" style=\"display:block;\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=50% align=\"center\">\n";
		$THIS_DISPLAY .= "<tr>\n";
		$THIS_DISPLAY .= "<td class=\"text\" width=\"12px\">".($x+1)." </td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"15px\">\n";
		
		$THIS_DISPLAY .= "<input id=\"min_qty".$x."\" name=\"min_qty[]\" maxlength=\"10\" value=\"".$min_qty[$x]."\" style=\"width: 50px;\" type=\"text\" onblur=\"fix_max('".$x."',document.getElementById('min_qty".$x."').value);\">\n";
		
		$THIS_DISPLAY .= "</td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"15px\">-</td>\n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"52px\">\n";
		
		$THIS_DISPLAY .= "<input id=\"max_qty".$x."\" name=\"max_qty[]\" maxlength=\"10\" value=\"".$max_qty[$x]."\" style=\"width: 50px;\" type=\"text\" onblur=\"fix_min('".$x."',document.getElementById('max_qty".$x."').value);\"> \n";

		$THIS_DISPLAY .= "</td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"52px\">\n";
		$THIS_DISPLAY .= "<input id=\"disc".$x."\" name=\"disc[]\" maxlength=\"10\" value=\"".preg_replace('/[aray]+/i', '', $disc[$x])."\" style=\"width: 50px;\" type=\"text\"></td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\">&nbsp;</td>\n";
		$THIS_DISPLAY .=  "</tr></table></div>\n";
	}
	$x++;
}

$svalue = $discnums; 
while($svalue < 120) {
		$THIS_DISPLAY .= "<div id=\"qtyfield".$svalue."\" name=\"qtyfield".$svalue."\" style=\"display:none;\"><table border=\"0\" cellpadding=\"5\" cellspacing=\"0\" class=\"text\" width=50% align=\"center\">\n";
		$THIS_DISPLAY .= "<tr>\n";
		$THIS_DISPLAY .= "<td class=\"text\" width=\"12px\">".($svalue+1)." </td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"15px\">\n";
		

		$THIS_DISPLAY .= "<input id=\"min_qty".$svalue."\" name=\"min_qty[]\" maxlength=\"10\" value=\"".$min_qty[$svalue]."\" style=\"width: 50px;\" type=\"text\" onblur=\"fix_max('".$svalue."',document.getElementById('min_qty".$svalue."').value);\" disabled>\n";

		$THIS_DISPLAY .= "</td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"15px\">-</td>\n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"52px\">\n";
		
		$THIS_DISPLAY .= "<input id=\"max_qty".$svalue."\" name=\"max_qty[]\" maxlength=\"10\" value=\"or more\" style=\"width: 50px;\" type=\"text\" onblur=\"fix_min('".$svalue."',document.getElementById('max_qty".$svalue."').value);\" disabled> \n";

		
		$THIS_DISPLAY .= "</td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\" width=\"52px\">\n";
		$THIS_DISPLAY .= "<input id=\"disc".$svalue."\" name=\"disc[]\" maxlength=\"10\" value=\"".preg_replace('/[aray]+/i', '', $disc[$svalue])."\" style=\"width: 50px;\" type=\"text\" disabled></td> \n";
		$THIS_DISPLAY .= "<td align=left class=\"text\">&nbsp;</td>\n";
		$THIS_DISPLAY .=  "</tr></table></div>\n";
	$svalue++;
}
// $THIS_DISPLAY .=  "</table>\n";


$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

$THIS_DISPLAY .= "     <tr>\n";
$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\">\n";
$THIS_DISPLAY .= "         [ <span id=\"dhowlayas\" onclick=\"showdivlayerz('".$discnums."');\" class=\"blue uline hand\">\n";
$THIS_DISPLAY .= "         Add discount a field</span> ]&nbsp;&nbsp;&nbsp;\n";
$THIS_DISPLAY .= "         [ <span id=\"dremove\" onclick=\"removedivlayerz('".$discnums."');\" class=\"blue uline hand\">\n";
$THIS_DISPLAY .= "         Remove discount a field</span> ]\n";
$THIS_DISPLAY .= "     </td>\n";
$THIS_DISPLAY .= "    </tr>\n";

$THIS_DISPLAY .= "   </table>\n";
$THIS_DISPLAY .= "  </div>\n\n";
?>