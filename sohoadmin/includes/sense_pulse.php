<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

$host = "securexfer.net";
$target_api_script = "api-ultra-adsense.php";
$data = "version=$product_name&domain=".$_SESSION['this_ip']."&ip=".$disIP."&hname=".$disHname;
$buf = "";

   // Connect to server and check key status
   // -------------------------------------------------
if ($fp = fsockopen($host,80)) {
   // Pull license for this domain
   // -------------------------------------------------------
   fputs($fp, "POST /".$target_api_script." HTTP/1.1\n");
   fputs($fp, "Host: $host\n");
   fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
   fputs($fp, "Content-length: " . strlen($data) . "\n");
   fputs($fp, "User-Agent: MSIE\n");
   fputs($fp, "Connection: close\n\n");
   fputs($fp, $data);
   while (!feof($fp)) {
      $buf .= fgets($fp,128);
   }
   $tmp = split("~STAT~", $buf);

   $pulse['message'] = $tmp[1];
   fclose($fp);
} // end if server connect successful


$azense = new userdata("asense");
if($pulse['message'] == '') {
	$azense->set("id", 'HIDE');
} else {
	$azense->set("id", $pulse['message']);
}
?>