<?php

session_start();
require_once("../../../../includes/product_gui.php");
set_time_limit(0);
ini_set("max_input_time", "600");
ini_set("max_execution_time", "600");
ini_set("memory_limit", "104857600");
ini_set("upload_max_filesize", "104857600");
ini_set("post_max_size", "104857600");


function sterilize ($sterile_var) {
	$sterile_var = stripslashes($sterile_var);
	$st_l = strlen($sterile_var);
	$st_a = 0;
	$tmp = "";
	while($st_a != $st_l) {
		$temp = substr($sterile_var, $st_a, 1);
		if (eregi("[.0-9a-z_-]", $temp)) { $tmp .= $temp; }
		$st_a++;
	}
	$sterile_var = $tmp;
	return $sterile_var;
}


//print_r($_FILES);
	$fileok = 0;
	$filename = $_FILES['file']['name'];
	$checkfor = strtolower($filename);
	$filename = eregi_replace(" ", "_", $filename);
	$filename = sterilize($filename);


	if (strstr($checkfor, ".gif")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".bmp")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".jpg")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".jpeg")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".png")) {
		$newfile = "$doc_root/images/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".pdf")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".rm")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".wav")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".ipx")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".swf")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".mp3")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".avi")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".wmv")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".wma")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".asf")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".asx")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".mpg")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}
	
	if (strstr($checkfor, ".mpeg")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".exe")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".mov")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".xls")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".doc")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".ppt")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".pps")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".css")) {
		$newfile = "$doc_root/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".js")) {
		$newfile = "$doc_root/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".pl")) {
		$newfile = "$cgi_bin/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".php") || strstr($checkfor, ".inc")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".html") || strstr($checkfor, ".htm")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".form")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".txt")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".flv")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".exe") || strstr($checkfor, ".zip") || strstr($checkfor, ".tar") || strstr($checkfor, ".tgz") || strstr($checkfor, ".rpm")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}

	if (strstr($checkfor, ".csv")) {
		$newfile = "$doc_root/media/".$filename;
		$fileok = 1;
	}


//echo "(".$newfile.")<br/>";
//echo "(".$filename.")<br/>";

$info = array();
$no_overwrite = 0;
if (isSet($_REQUEST["action"]) && $fileok == 1){
//	if ($_REQUEST["action"] == "upload_file"){
	if(file_exists($newfile)){
		$info[] = "<b>".basename($_FILES["file"]["name"])."</b> uploaded correctly and overwrote the existing file.\n";
   	}else{
		$info[] = "<b>".basename($_FILES["file"]["name"])."</b> uploaded!\n";
   	}
		
	if (@copy($_FILES["file"]["tmp_name"], $newfile)){
	   if(count($info) > 0){
	          foreach($info as $var=>$val){
	             echo $val."<br>\n";
	          }
	   }
		//echo "File uploaded as <b>".$filename."</b>\n";
	}else{
	   echo "<b>Could not upload file.  The file may be to large.</b><br/>\n";
	}

	
}else{
   echo "Please select a file to upload and click Submit\n";
}

?>