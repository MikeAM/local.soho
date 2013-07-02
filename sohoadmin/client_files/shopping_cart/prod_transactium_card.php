<?php
/*
'**********************************************************************
' D I S C L A I M E R
' WARNING: ANY USE BY YOU OF THE SAMPLE CODE PROVIDED IS AT YOUR OWN RISK.
' Transactium © provides this code "as is" without warranty of any kind, either express or implied, including but not limited to the implied warranties of merchantability and/or fitness for a particular purpose.
'**********************************************************************
*/
?>

<?php
session_start();

define('HPSURL',"https://psp.stg.transactium.com/hpservices/ws/hpws.v1310.asmx?wsdl");
define('JSURL',"https://psp.stg.transactium.com/hpservices/site/js/startHPS.js");
//define('USERNAME',"Transactium");
//define('PASSWORD',"Transactium[1]");
define('USERNAME',$cartpref->get('transactium_username'));
define('PASSWORD',$cartpref->get('transactium_password'));
define('TAG',$cartpref->get('transactium_tag'));
//define('BASE',"http://194.204.103.151/s/PHP/MerchantTestShop/PHP5%20+%20SOAP%20Addon");
define('BASE',"http://".$_SESSION['docroot_url']."/shopping");

//echo USERNAME;
//echo PASSWORD;

class CTransactiumHPP {
	var $soap;
	
	function CTransactiumHPP($username,$password) {
		$this->soap=new SoapClient(HPSURL);
		$this->soap->__setSoapHeaders(array(new SoapHeader('http://www.transactium.com','HPSAuthHeader',
		array(	'Username' => $username, 'Password' => $password))));
	}
	function GetHostedPayment($HPSID) {
		return $this->soap->GetHostedPayment(array("HPSID"=>$HPSID));
	}
	function CreateHostedPayment($paramarr)	{
		return $this->soap->CreateHostedPayment(array("paymentDetails"=>$paramarr));
	}
}

///* AMEND FOLLOWING SECTION WITH PAYMENT DETAILS */
//$hp=new CTransactiumHPP(USERNAME,PASSWORD);
//$params=array(
//'Amount'=>$ORDER_TOTAL, ///payment amount
//'Currency'=>$dType, ///payment currency
//'PaymentType'=>"Sale", ///payment type
//'HPSProfileTag'=>"", ///payment profile
//'HPSProfileTag'=>TAG, ///payment profile
//'MaxTimeLimit'=>0, ///max session time limit
//'OrderReference'=>"", ///order reference
//'ClientReference'=>"", ///client reference
//'ClientIPRestriction'=>getenv('REMOTE_ADDR'), ///customer IP
//'ClientBillingCountry'=>"MT", ///customer billing country code
//'ClientEmail'=>"", ///customer email address
//'SuccessURL'=>BASE."/pgm-payment_gateway.php?todo=transactium_return",
//'FailURL'=>BASE."/end.php", ///URL to redirect in case of error
//'LanguageCode'=>"", ///hosted payment frame language code
//'PayInstId'=>"", ///the encrypted payment instrument id
//'RefId'=>"", ///the reference transaction id
//'ProcessWithRandomCode'=>false);///the random code flag

//echo $dType;

/* AMEND FOLLOWING SECTION WITH PAYMENT DETAILS */
$hp=new CTransactiumHPP(USERNAME,PASSWORD);
$params=array(
'Amount'=> $ORDER_TOTAL * 100, ///payment amount
'Currency'=>'EUR', ///payment currency
'PaymentType'=>"Sale", ///payment type
'HPSProfileTag'=>"", ///payment profile
'HPSProfileTag'=>TAG, ///payment profile
'MaxTimeLimit'=>0, ///max session time limit
'OrderReference'=> $ORDER_NUMBER, ///order reference
'ClientReference'=>"", ///client reference
'ClientIPRestriction'=>getenv('REMOTE_ADDR'), ///customer IP
'ClientBillingCountry'=>"MT", ///customer billing country code
'ClientEmail'=>"", ///customer email address
'SuccessURL'=>BASE."/pgm-show_invoice.php?todo=transactium_success",
'FailURL'=>BASE."/pgm-payment_gateway.php?todo=transactium_error",
'LanguageCode'=>"", ///hosted payment frame language code
'PayInstId'=>"", ///the encrypted payment instrument id
'RefId'=>"", ///the reference transaction id
'ProcessWithRandomCode'=>false);///the random code flag

//'FailURL'=>BASE."/pgm-payment_gateway.php?todo=transactium_error",
//'SuccessURL'=>BASE."/pgm-payment_gateway.php?todo=transactium_success",

//echo testArray($params);

//'SuccessURL'=>BASE."/pgm-show_invoice.php",

$payment = $hp->CreateHostedPayment($params);
$_SESSION["HPSID"]=$payment->CreateHostedPaymentResult->HPSID;

//echo "[".$dType."]<br/>";

if ( $_REQUEST['todo'] == 'transactium_error' ) {
//	echo testArray($_REQUEST);
	# Declined: show error message a cc form
	echo "<div align=\"center\" style=\"border: 1px solid red; background-color: #F7DFDF;\" class=\"text\">\n";
	echo ' <strong>'.lang('ERROR').'</strong>: '.lang("We were unable to complete your order due to a problem processing your credit card (or because you cancelled the transaction).").". ".lang("Your credit card has not been charged").".<br>";
	echo "</div><br>\n";

} elseif ( $_REQUEST['todo'] == 'transactium_success' ) {
	if ( $_SESSION['HPSID'] != $_REQUEST['hpsid'] ) {
		echo 'Session ids do not match';
	}
	# Accepted: show final invoice & 'thank you'
	//echo "Accepted:<hr>";
//	echo testArray($_REQUEST);
	include("pgm-show_invoice.php");
	exit;
}
?>
<!--SCRIPT 1 - SET HPS URL-->
<script type="text/javascript">HPS_redirectUrl = "<?=$payment->CreateHostedPaymentResult->RedirectURL?>";</script>
<!--SCRIPT 2 - CALL JS FILE ON SERVER TO RENDER HPP IFRAME-->
<script type="text/javascript" src="<?=JSURL?>" id="hpsscript"></script>
<!--SCRIPT 3-->
<noscript>This payment requires JavaScript and your browser does not support JavaScript or JavaScript is not enabled. Please upgrade your browser or enable JavaScript and try again.</noscript>

<?php
//}
?>