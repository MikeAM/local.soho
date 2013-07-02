<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }


// NOTE:
// UNLESS YOU HAVE A REALLY ODD SETUP YOU SHOULD
//
//                     NOT
//
// CHANGE THIS FILE FOR ANY REASON.

//--------------------------------------------------------
// Payment Authorization Gateway via PHP (v0.1-0)
// ----------------
// Written by: John M. Brown <jmbrown@ipupdater.com>
//             IWAS2 Tech's Dynamic DNS Services
//             http://www.ipupdater.com
// ----------------
// Purpose:    Interface with the Payment Gateway in order
//             to verify credit card numbers and payments.
//---------------------------------------------------------
//
// Most of this file should be straightforward so make sure
// you read the entire file before sending an email.
//
//---------------------------------------------------------

function PostTransaction ($transaction) {
//	This function accepts one variable as an array.
//	That array should contain all of the variables
//	required to connect to and authorized payments in
//	the payment gateway.
//
//	You can create as many "object arrays" as you like
//	and when ready send them through the PostTransaction()
//	function to perform the appropriate action.

//--< GATEWAY/ISP OPTIONS >------------------

	$Url = "https://transactions.innovativegateway.com/servlet/com.gateway.aai.Aai";
	$user_agent = "Mozilla/4.0";
	$proxy = ""; // If you use a proxy server to connect
		     // your server to the Internet then put
		     // in its address here.

	// Create the POST form to send to the gateway using
	// the incoming array.
	$data = "";
	foreach ($transaction as $name => $value) {
	    $data .= "&" . $name . "=" . urlencode($value);
	}

	$data = substr($data,1);

      # Initiate curl connection
      $ch = curl_init($Url);

      # Workaround from php.net user notes for SSL certificate error (happens with authorize.net transactions sometimes)
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

      curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
      curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $data, "& " )); // use HTTP POST to send form data

      $result =  curl_exec($ch); //execute post and get results
      curl_close ($ch);

//	// Create the connection through the cURL extension
//        $ch = curl_init();
//        curl_setopt ($ch, CURLOPT_URL, $url);
//
//	if ($proxy != "")
//
//
//		curl_setopt ($ch, CURLOPT_PROXY, $proxy);
//
//        curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
//	curl_setopt ($ch, CURLOPT_POST, 1);
//	curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
//        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
//        curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
//        $result = curl_exec ($ch);
//        curl_close($ch);
//
//	// Now we've got the results back in a big string.

	// Parse the string into an array to return
	$rArr = explode("|",$result);

	$returnArr="";
	for($i=0;$i<count($rArr);$i++)
	{
		$tmp2 = explode("=", $rArr[$i]);

		// YES, we put all returned field names in lowercase
		$tmp2[0] = strtolower($tmp2[0]);

		// YES, we strip out HTML tags.
		$returnArr[$tmp2[0]] = strip_tags($tmp2[1]);
	}

	// Return the array.
	return $returnArr;
}

?>
