<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
///////////// pgm-add_cart.php include  hook line 719
$exname = explode(';',$_SESSION['CART_PRODNAME']);
$exsku = explode(';',eregi_replace(';$', '', $_SESSION['CART_SKUNO']));
$countsku = count($exsku);
$exsku = array_unique($exsku);
$prikeyz = explode(';', $_SESSION['CART_KEYID']);
array_pop($prikeyz);
$newprikz = array_unique($prikeyz);
$copts= mysql_query("select * from cart_options");
$coptsa = mysql_fetch_array($copts);
$DISPLAY_HEADERBG = $coptsa['DISPLAY_HEADERBG'];

$xs = 0;
while($xs < $countsku) {
	if($exsku[$xs] != '') {		
		$thisprikey = $newprikz[$xs];
		$itemdiscq = mysql_query("select * from cart_products where PRIKEY='".$thisprikey."'");
		$itemarray = mysql_fetch_array($itemdiscq);
		$varprices = unserialize($itemarray['variant_prices']);
		$varnames = unserialize($itemarray['variant_names']);
		$pname = $itemarray['PROD_NAME'];
		$PROD_UNITPRICE = $itemarray['PROD_UNITPRICE'];
		$skuname = $itemarray['PROD_SKU'];
		
		$qtydiscq = mysql_query("select * from qty_discounts where sku='".$skuname ."'");
		$qtyarray = mysql_fetch_array($qtydiscq);
		$min_qty = unserialize($qtyarray['min_qty']);
		$max_qty = unserialize($qtyarray['max_qty']);
		$disc = unserialize($qtyarray['disc']);
		$arnum = count($min_qty);	
		$checkzero = $arnum-1;
		unset($varpricesnew);
		foreach($varprices as $pricek=>$pricevar){
			if($pricevar != ''){
				$varpricesnew[$pricek] = $pricevar;
			}
		}

		if($arnum==1 && $disc[$checkzero]==0){	
		} else {
			
			if(1==1){
			  $THIS_DISPLAY .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" id=\"moreinfo-pricing\">\n";
				$THIS_DISPLAY .= " <tr>\n";
				$THIS_DISPLAY .= "  <th colspan=\"".($arnum + 1)."\" class=\"smtext\" style=\"text-align:left;\">".$pname." Pricing</td>\n";
				$THIS_DISPLAY .= "	</tr>\n";
			  
			  $THIS_DISPLAY .= " <tr>\n";
			  $THIS_DISPLAY .= "  <th class=\"smtext\" style=\"text-align:left;\">".lang("Quantity").":</td>\n";
				foreach($disc as $qtyarraya=>$qtyarrayb){
				  $THIS_DISPLAY .= "<th class=\"smtext\" style=\"text-align:left;\">".$min_qty[$qtyarraya];
				  if($max_qty[$qtyarraya] > 9999998){
				    $THIS_DISPLAY .= "+</td>\n";
				  } else {
				    $THIS_DISPLAY .= " - ".$max_qty[$qtyarraya]."</td>\n";
				  }
				} 
			  $THIS_DISPLAY .= "</tr>\n";

				if(count($varpricesnew) > 0){	  					
					foreach($varprices as $pricek=>$pricevar){
					  if($pricevar != ''){
					  $THIS_DISPLAY .= "<tr><td style=\"border: 1px solid #".$DISPLAY_HEADERBG.";\">".$varnames[$pricek]."</td>\n";   
					    foreach($disc as $qtyarraya=>$qtyarrayb){
					      if($qtyarray['disc_type'] == '%') { 
					        $qtyarrayb = $pricevar * ($qtyarrayb * .01);
					        
					        $THIS_DISPLAY .= "<td class=\"smtext\" style=\"border: 1px solid #".$DISPLAY_HEADERBG."; text-align:left;\">".$dollarsign.sprintf("%01.2f", ($pricevar - $qtyarrayb))."&nbsp;(Each)</td>\n";
					      } else {
					        $THIS_DISPLAY .= "<td class=\"smtext\" style=\"border: 1px solid #".$DISPLAY_HEADERBG."; text-align:left;\">".$dollarsign.sprintf("%01.2f", ($pricevar - $qtyarrayb))."&nbsp;(Each)</td>\n";
					      }
					    }
					    $THIS_DISPLAY .= "</tr>\n";
					  }
					}
					
			 	} else {

	        $THIS_DISPLAY .= "<tr><td style=\"border: 1px solid #".$DISPLAY_HEADERBG.";\">".$pname."</td>\n";  
	    
	        foreach($disc as $qtyarraya=>$qtyarrayb){
	          if($qtyarray['disc_type'] == '%') {         
	            $qtyarrayb = $PROD_UNITPRICE * ($qtyarrayb * .01);            
	            $THIS_DISPLAY .= "<td class=\"smtext\" style=\"border: 1px solid #".$DISPLAY_HEADERBG.";\" style=\"text-align:left;\">".$dollarsign.sprintf("%01.2f", ($PROD_UNITPRICE - $qtyarrayb))."&nbsp;(Each)</td>\n";
	          } else {
	            $THIS_DISPLAY .= "<td class=\"smtext\" style=\"border: 1px solid #".$DISPLAY_HEADERBG.";\" style=\"text-align:left;\">".$dollarsign.sprintf("%01.2f", ($PROD_UNITPRICE - $qtyarrayb))."&nbsp;(Each)</td>\n";
	          }
	        }
	        $THIS_DISPLAY .= "</tr>\n";

			 	}
			  
				$THIS_DISPLAY .= "   </table><br/>\n";
			
			} else {
			  if($arnum==1 && $disc[$checkzero]==0){ 
			  } else {
			    $THIS_DISPLAY .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" id=\"moreinfo-pricing\">\n";       
			    $THIS_DISPLAY .= " <tr>\n";
			    $THIS_DISPLAY .= "  <th class=\"smtext\" style=\"text-align:left;\">".lang("Quantity")."</td>\n";
			    $THIS_DISPLAY .= "  <th class=\"smtext\" style=\"text-align:left;\">".lang("Discount")."</td>\n";
			    $THIS_DISPLAY .= "    </tr>\n";
			    if($qtyarray['disc_type'] == '%') {
			      $disctxt1 = "% off";
			      $disctxt2 = '';
			    } else {
			      $paysignq = mysql_query("select PAYMENT_CURRENCY_SIGN from cart_options");
			      while($ps = mysql_fetch_array($paysignq)) {
			        $dollarsign = $ps['PAYMENT_CURRENCY_SIGN'];
			      }
			      $disctxt1 = " per item.";
			      $disctxt2 = "- ".$dollarsign;
			    }
			    $x1 = 0;
			    while($x1 < $arnum) {
			//      if($disc[$x1] > 0){
			        $THIS_DISPLAY .= "    <tr>\n";
			        $THIS_DISPLAY .= "     <td align=\"left\" width=\"35%\" valign=\"top\">\n";
			         if(($arnum - 1) != $x1) {
			            $THIS_DISPLAY .= "     ".$min_qty[$x1]."&nbsp;-&nbsp;".$max_qty[$x1];
			         } else {
			            $THIS_DISPLAY .= "     ".$min_qty[$x1]."&nbsp;+";
			         }
			        //$THIS_DISPLAY .= "    ".$min_qty[$x1]."&nbsp;-&nbsp;".$max_qty[$x1];
			        $THIS_DISPLAY .= "     </td>\n";
			        $THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\">\n";
			        
			        if($disc[$x1] > 0 && is_numeric($disc[$x1])){
			          $THIS_DISPLAY .= "    ".$disctxt2.sprintf ("%01.2f", $disc[$x1]).$disctxt1;
			        } else {
			          $THIS_DISPLAY .= "    ---";
			        }
			        $THIS_DISPLAY .= "     </td>\n";
			        $THIS_DISPLAY .= "    </tr>\n";
			        $x1++;  
			      }
			      
			  }
			  if($qtyarray['required_qty'] > 1){
			    $THIS_DISPLAY .= "     <tr>\n";
			    $THIS_DISPLAY .= "     <td colspan=2 align=\"left\" valign=\"top\">\n";
			    $THIS_DISPLAY .= "     <i>* A minimum quantity of ".$qtyarray['required_qty']." is required to purchase.</i></td>\n";
			    $THIS_DISPLAY .= "    </tr>\n";
			  }
			  $THIS_DISPLAY .= "   </table><br/>\n";
			
			}
			
		}
	}
	$xs++;
}
//
//$exname = explode(';',$_SESSION['CART_PRODNAME']);
//$exsku = explode(';',eregi_replace(';$', '', $_SESSION['CART_SKUNO']));
//$countsku = count($exsku);
//$exsku = array_unique($exsku);
//$xs = 0;
//while($xs < $countsku) {
//	if($exsku[$xs] != '') {		
//		$pname = $exname[$xs];
//		$skuname = $exsku[$xs];
//
//		$qtydiscq = mysql_query(mysql_query("select * from qty_discounts where sku='".$skuname."'");
//		$qtyarray = mysql_fetch_array($qtydiscq);
//		$min_qty = unserialize($qtyarray['min_qty']);
//		$max_qty = unserialize($qtyarray['max_qty']);
//		$disc = unserialize($qtyarray['disc']);
//		
//		$arnum = count($min_qty);
//		$checkzero = $arnum-1;
//		$itemdiscq = mysql_query("select * from cart_products where PRIKEY='".$_GET['id']."'");
//		$itemarray = mysql_fetch_array($itemdiscq);
//		$varprices = unserialize($itemarray['variant_prices']);
//		$varnames = unserialize($itemarray['variant_names']);
//		
//		
//		$paysignq = mysql_query("select PAYMENT_CURRENCY_SIGN from cart_options");
//		while($ps = mysql_fetch_array($paysignq)) {
//			$dollarsign = $ps['PAYMENT_CURRENCY_SIGN'];
//		}
//		
//		if((count($disc) > 1) || ($disc['0'] > 0)){
//		
//			if(1==1){
//				$THIS_DISPLAY .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" id=\"moreinfo-pricing\">\n";
//				$THIS_DISPLAY .= " <tr>\n";
//				$THIS_DISPLAY .= "  <th class=\"smtext\" style=\"text-align:left;\">".lang("Quantity").":</td>\n";
//			foreach($disc as $qtyarraya=>$qtyarrayb){
//				$THIS_DISPLAY .= "<th class=\"smtext\" style=\"text-align:left;\">".$min_qty[$qtyarraya];
//				if($max_qty[$qtyarraya] > 9999998){
//					$THIS_DISPLAY .= "+</td>\n";
//				} else {
//					$THIS_DISPLAY .= " - ".$max_qty[$qtyarraya]."</td>\n";
//				}
//			}	
//				$THIS_DISPLAY .= "</tr>\n";
//				foreach($varprices as $pricek=>$pricevar){
//					if($pricevar != ''){
//					$THIS_DISPLAY .= "<tr><td>".$varnames[$pricek]."</td>\n";		
//						foreach($disc as $qtyarraya=>$qtyarrayb){
//							if($qtyarray['disc_type'] == '%') {	
//								$qtyarrayb = $pricevar * ($qtyarrayb * .01);
//								
//								$THIS_DISPLAY .= "<td class=\"smtext\" style=\"text-align:left;\">".$dollarsign.sprintf("%01.2f", ($pricevar - $qtyarrayb))."&nbsp;(Each)</td>";
//							} else {
//								$THIS_DISPLAY .= "<td class=\"smtext\" style=\"text-align:left;\">".$dollarsign.sprintf("%01.2f", ($pricevar - $qtyarrayb))."&nbsp;(Each)</td>";
//							}
//						}
//						$THIS_DISPLAY .= "</tr>\n";
//					}
//				}
//			$THIS_DISPLAY .= "   </table>\n";
//			
//			} else {
//				if($arnum==1 && $disc[$checkzero]==0){ 
//				} else {
//					$THIS_DISPLAY .= "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\" id=\"moreinfo-pricing\">\n";
//					$THIS_DISPLAY .= " <tr>\n";
//					$THIS_DISPLAY .= "  <th class=\"smtext\" style=\"text-align:left;\">".lang("Quantity")."</td>\n";
//					$THIS_DISPLAY .= "  <th class=\"smtext\" style=\"text-align:left;\">".lang("Discount")."</td>\n";
//					$THIS_DISPLAY .= "    </tr>\n";
//					if($qtyarray['disc_type'] == '%') {
//						$disctxt1 = "% off";
//						$disctxt2 = '';
//					} else {
//						$paysignq = mysql_query("select PAYMENT_CURRENCY_SIGN from cart_options");
//						while($ps = mysql_fetch_array($paysignq)) {
//							$dollarsign = $ps['PAYMENT_CURRENCY_SIGN'];
//						}
//						$disctxt1 = " per item.";
//						$disctxt2 = "- ".$dollarsign;
//					}
//					$x1 = 0;
//					while($x1 < $arnum) {
//			//			if($disc[$x1] > 0){
//							$THIS_DISPLAY .= "    <tr>\n";
//							$THIS_DISPLAY .= "     <td align=\"left\" width=\"35%\" valign=\"top\">\n";
//						   if(($arnum - 1) != $x1) {
//						      $THIS_DISPLAY .= "     ".$min_qty[$x1]."&nbsp;-&nbsp;".$max_qty[$x1];
//						   } else {
//						      $THIS_DISPLAY .= "     ".$min_qty[$x1]."&nbsp;+";
//						   }
//							//$THIS_DISPLAY .= "	  ".$min_qty[$x1]."&nbsp;-&nbsp;".$max_qty[$x1];
//							$THIS_DISPLAY .= "     </td>\n";
//							$THIS_DISPLAY .= "     <td align=\"left\" valign=\"top\">\n";
//							
//							if($disc[$x1] > 0 && is_numeric($disc[$x1])){
//								$THIS_DISPLAY .= "	  ".$disctxt2.sprintf ("%01.2f", $disc[$x1]).$disctxt1;
//							} else {
//								$THIS_DISPLAY .= "	  ---";
//							}
//							$THIS_DISPLAY .= "     </td>\n";
//							$THIS_DISPLAY .= "    </tr>\n";
//							$x1++;	
//						}
//						
//					}
//					if($qtyarray['required_qty'] > 1){
//						$THIS_DISPLAY .= "     <tr>\n";
//						$THIS_DISPLAY .= "     <td colspan=2 align=\"left\" valign=\"top\">\n";
//						$THIS_DISPLAY .= "     <i>* A minimum quantity of ".$qtyarray['required_qty']." is required to purchase.</i></td>\n";
//						$THIS_DISPLAY .= "    </tr>\n";
//					}
//					$THIS_DISPLAY .= "   </table>\n";
//			//	}
//			}
//		}
//	}
//	++$xs;
//}
?>