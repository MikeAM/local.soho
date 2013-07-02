<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

# Include in the shopping cart wherever you have to check for/pull various policies.

# Pull other polices
$filename = "$cgi_bin/other_policies.txt";
$other_policy_definedBool = false;
if (file_exists($filename)) {
	$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
	$OTHER_POLICY = fread($file,filesize($filename));
	fclose($file);
	if (strlen($OTHER_POLICY) > 10) { 
		$other_policy_definedBool = true; 
	}
}

# Returns & Exchanges
$filename = "$cgi_bin/cart_returns.txt";
$return_policy_definedBool = false;
if (file_exists($filename)) {
	$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
	$RETURNS_POLICY = fread($file,filesize($filename));
	fclose($file);
	if ( strlen($RETURNS_POLICY) > 10 ) {
		$return_policy_definedBool = true;
	}
}

# Shipping
$filename = "$cgi_bin/cart_delivery.txt";
$shipping_policy_definedBool = false;
if (file_exists($filename)) {
	$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
	$SHIPPING_POLICY = fread($file,filesize($filename));
	fclose($file);
	if ( strlen($SHIPPING_POLICY) > 10 ) {
		$shipping_policy_definedBool = true;
	}	
}

# Shipping
$filename = "$cgi_bin/privacy.txt";
$privacy_policy_definedBool = false;
if (file_exists($filename)) {
	$file = fopen("$filename", "r") or DIE(lang("Error").": ".lang("Could not open privacy policy")." ($filename)");
	$PRIVACY_POLICY = fread($file,filesize($filename));
	fclose($file);
	if ( strlen($PRIVACY_POLICY) > 10 ) {
		$privacy_policy_definedBool = true;
	}	
}



?>