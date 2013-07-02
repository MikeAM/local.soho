<?php
// Payment confirmation from http post
include("pgm-cart_config.php");
error_reporting(E_PARSE);


$your_email = 'you@domain.com'; // your merchant account email address
function nochex_http_post($server, $port, $url, $vars) {
	// get urlencoded vesion of $vars array
	$urlencoded = "";
	foreach ($vars as $Index => $Value){ // get all variables to be used in query
		$urlencoded .= urlencode($Index ) . "=" . urlencode($Value) . "&";
	}
	$urlencoded = substr($urlencoded,0,-1); // returns portion of string, everything but last character
	$headers = "POST $url HTTP/1.0\r\n" // headers to be sent to the server
	. "Content-Type: application/x-www-form-urlencoded\r\n"
	. "Content-Length: ". strlen($urlencoded) . "\r\n\r\n"; // length of the string
	$fp = fsockopen($server, $port, $errno, $errstr, 10); // returns file pointer
	if(!$fp){
		return "ERROR: fsockopen failed.\r\nError no: $errno - $errstr"; // if cannot open socket then display error message
	}
	fputs($fp, $headers); //writes to file pointer
	fputs($fp, $urlencoded);
	$ret = "";
	while (!feof($fp)){
		$ret .= fgets($fp, 1024); // while it?s not the end of the file it will loop
	}
	fclose($fp); // closes the connection
	return $ret; // array
}
// uncomment below to force a DECLINED response
//$_POST['order_id'] = "1";

$nochex['to_email'] = 'cameron.allen@soholaunch.com';	// Email address of payment recipient (the new purchase notification email address)
$nochex['from_email'] = 'cameron.allen@soholaunch.com';	// Customers email address
$nochex['transaction_id'] = '1';	// Unique code generated to distinguish transactions.
//$nochex['transaction_date'] = '';	// Date of transaction 15/02/2010 17:20:46
$nochex['order_id'] = '1';	// Transaction specific code, order id as passed by you, the merchant. Your customer is not able to view or edit this. It must be unique per transaction.
$nochex['amount'] = '1.00';	// full ammount of payment
//$nochex['security key'] = '';	//System generated key (for Nochex use).
$nochex['status'] = 'test';	// Live or Test




//$response = nochex_http_post("www.nochex.com", 80, "/nochex.dll/apc/apc", $_POST);
$response = nochex_http_post("www.nochex.com", 80, "/nochex.dll/apc/apc", $_POST);


//https://www.nochex.com/

// stores the response from the Nochex server
$debug = "IP -> " . $_SERVER['REMOTE_ADDR'] ."\r\n\r\nPOST DATA:\r\n";
foreach($_POST as $Index => $Value){
	$debug .= "$Index -> $Value\r\n";
	$debug .= "\r\nRESPONSE:\r\n$response";
	if(!strstr($response, "AUTHORISED")){ // searches response to see if AUTHORISED is present if it isn?t a failure message is displayed
		$msg = "APC was not AUTHORISED.\r\n\r\n$debug"; // displays debug message
		$nochex_auth = 0;
	} else {		
		$nochex_auth = 1;		
		$msg = "APC was AUTHORISED."; // if AUTHORISED was found in the response then it was successful
		//mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'NoChex', TRANSACTION_STATUS = 'Paid' WHERE ORDER_NUMBER = '".$_REQUEST['orderid']."'");
		// whatever else you want to do
	}
}


$cart_optq = mysql_query("select BIZ_EMAIL_NOTICE from cart_options");
$cart_opt = mysql_fetch_assoc($cart_optq);

if($nochex_auth == 1 && $_REQUEST['order_id'] != ''){	
	$ORDER_NUMBER = $_REQUEST['order_id'];
	mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'NoChex', TRANSACTION_STATUS = 'Paid' WHERE ORDER_NUMBER = '$ORDER_NUMBER'");
	mail($cart_opt['BIZ_EMAIL_NOTICE'], 'New '.$this_ip.' purchase ', "APC Purchase Notification <br/>\n".$msg.$debug);
	exit;
}

if($_REQUEST['status'] == 'cancel' && $_REQUEST['orderid'] != ''){
	$findq = mysql_query("select * from cart_invoice where ORDER_NUMBER='".$_REQUEST['orderid']."'");
	$findinv = mysql_fetch_assoc($findq);
	if($findinv['PAY_METHOD'] == 'NoChex' && $findinv['TRANSACTION_STATUS'] = 'Sent'){
		mysql_query("UPDATE cart_invoice SET PAY_METHOD = 'NoChex', TRANSACTION_STATUS = 'Canceled' WHERE ORDER_NUMBER = '".$_REQUEST['orderid']."'");	
//		mail( 'email@soholaunch.com', 'APC', "APC Debug <br/>\n".$msg.$debug);
	}
	session_destroy();
	header("Location: ../shopping/");
} else {
//	mail( 'testemailaddr', 'APC', "APC Debug <br/>\n".$msg.$debug);
}

//echo "APC Debug <br/>\n".$msg; // sends an email explaining whether APC was successful or not, the subject will be “APC Debug” but you can change this to whatever you want.









?>