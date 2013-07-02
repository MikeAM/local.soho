<?php
//TODO it woudn't be bad to check for admin rights here ;)
session_start();
require_once("../../includes/product_gui.php");

set_time_limit(0);
ini_set("max_input_time", "600");
ini_set("max_execution_time", "600");
ini_set("memory_limit", "104857600");
ini_set("upload_max_filesize", "104857600");
ini_set("post_max_size", "104857600");

require("file_manager_config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $strings["title"]; ?></title>

<link href="file_manager/styles.css" rel="stylesheet" type="text/css">
<?php
//require("file_manager/utils.php");

//foreach($_REQUEST as $var=>$val){
//   echo "var = (".$var.") val = (".$val.")<br>";
//}

if (isSet($_REQUEST["type"])) {
	$type = $_REQUEST["type"];
}else {
	$type = -2;
}
$default_dir = ".";
$ext = array("*");
$url_dir = "";
//echo "type(".$type.")<br/>";
if ($type != -2) {
   //echo "OK<br/>";
	if (isSet($settings[$type])) {
		$default_dir = $settings[$type]["dir"];
		$url_dir = $settings[$type]["url_dir"];
		$ext = $settings[$type]["ext"];
		//echo "ext(".$ext.")<br/>";
	}
}
//echo "ext(".$ext.")<br/>type(".$settings[$type].")";
if (!isSet($_REQUEST["dir"]) || strlen($_REQUEST["dir"]) == 0) {
	$dir = $default_dir;
	$requested_dir = "";
}
else {
	$requested_dir = $_REQUEST["dir"];
	$dir = $default_dir . "/" . $requested_dir;
}
if (strpos($dir, "..") > 0) //'..' in our path is a big no-no
	$dir = $default_dir;

if (isSet($_REQUEST["action"]))
{
	if ($_REQUEST["action"] == "upload_file")
	{
		$filename = $default_dir . $requested_dir . "/" . basename($_FILES["uploaded_file"]["name"]);
		if (file_exists($filename))
			$filename = $default_dir . "/" . $requested_dir . "/" . rand() . "_" . basename($_FILES["uploaded_file"]["name"]);
		if (move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], $filename))
			echo "<!-- file upload successful -->\n";
	}
	else if ($_REQUEST["action"] == "create_dir")
	{
		if (@mkdir($default_dir . "/" . $requested_dir . "/" . $_REQUEST["dir_name"]) === FALSE)
			echo "<!-- creation of directory failed! -->\n";
		else
			echo "<!-- directory created successfully -->\n";
	}
	else if ($_REQUEST["action"] == "delete_folder" || $_REQUEST["action"] == "delete_file")
	{
		@rmdirr($_REQUEST["item_name"]);
	}
}

echo "<script language=\"javascript\">\n";
?>

function prev_selected(file_name){
   document.getElementById('img_prev').innerHTML = '<img src="http://<? echo $_GET["dot_com"]."/".$url_dir; ?>/'+file_name+'" />'
   document.getElementById('choose_it').style.display='block'
   document.getElementById('dis_file_name').innerHTML=file_name
}

function fileSelected() {
   var filename = document.getElementById('dis_file_name').innerHTML;
   
//   alert('not focused')
//   window.top.opener.my_win.focus()
   //alert('focused')
   
	//let our opener know what we want
	window.top.opener.my_win.document.getElementById(window.top.opener.my_field).value = "<?php echo $url_dir; ?>/" + filename;
	//we close ourself, cause we don't need us anymore ;)
	window.close();
}
function switchDivs() {
	document.getElementById("upload_div").style.display = "none";
	document.getElementById("uploading_div").style.display = "block";
	return true;
}
</script>
</head>
<body>
<div class="div_curr_dir"><b><?php echo $strings["curr_dir"]; ?></b><br><?php
if (strlen($requested_dir) > 0)
{
	$requested_dirs = explode("/", $requested_dir);
	$tmp_dirs = "";
	foreach ($requested_dirs AS $tmp_dir)
	{
		if ($tmp_dir != "")
		{
			if ($tmp_dirs == "")
				echo "<a class='back' href='?type=" . $type . "'>/</a>";
			else
				echo "/";
				
			$tmp_dirs .= "/" . $tmp_dir;
			if ($requested_dir == $tmp_dirs)
				echo $tmp_dir;
			else
				echo "<a class='back' href='?type=" . $type . "&dir=" . $tmp_dirs . "'>" . $tmp_dir . "</a>";
		}
	}
}
else
	echo "&nbsp;";
?></div>

   <div class="div_main" style="border: 0px solid red;">&nbsp;
   <?php
   $dh  = opendir($dir);
   while (false !== ($filename = readdir($dh))) {
   	if ($filename != "." && $filename != "..") {
   		if (is_dir($dir . "/" . $filename)) {
   			$dirs[] = $filename;
   		}
   		else {
   			if (sizeof($ext) > 0) {
   				for ($i=0;$i<sizeof($ext);$i++) {
   					if ($ext[$i] == "*" || (strtolower($ext[$i]) == strtolower(substr($filename, -strlen($ext[$i]))))) {
   						$files[] = $filename;
   						break;
   					}
   				}
   			}
   			else {
   				$files[] = $filename;
   			}
   		}
   	}
   }
   $files[] = sort($files);
   
   include($types[$settings[$type]["type"]]);
   ?>
   </div>

<div id="div_main_preview" class="div_main_preview">
   <span>Image Preview</span>
   <div id="img_prev">&nbsp;</div>
</div>
<div class="div_back">
   <div class="div_close"><a class="close" href="javascript: window.close();"><?php echo $strings["close"]; ?></a></div>
   <div id="choose_it" class="div_close"><a class="close" href="javascript: fileSelected();">Select</a></div>
   <div id="dis_file_name" class="div_file_name">&nbsp;</div>
</div>
<div class="footer">
   <div class="create_dir_div">
   	<?php echo $strings["create_dir"]; ?>
   	<form method="post">
   		<input type="hidden" name="action" value="create_dir">
   		<input type="text" name="dir_name">
   		<input type="submit" value="<?php echo $strings["create_dir_submit"]; ?>">
   	</form>
   </div>
		<?php
		if ( $CUR_USER_ACCESS == "WEBMASTER" || eregi(";MOD_SITE_FILES;", $CUR_USER_ACCESS)) {
		   $show_upload = "block";
		}else{
		   $show_upload = "none";
		}
		?>
	<div id="upload_div" style="display: <?php echo $show_upload; ?>;">
   	<?php echo $strings["upload_file"]; ?>
   	<form method="post" enctype="multipart/form-data" onSubmit="switchDivs();">
   		<input type="hidden" name="action" value="upload_file">
   		<input type="hidden" name="MAX_FILE_SIZE" value="104857600" /> <!-- ~100mb -->
   		<input type="file" name="uploaded_file">
   		<input type="submit" value="<?php echo $strings["upload_file_submit"]; ?>">
   	</form>
	</div>
	<div id="uploading_div" style="display: none;">
	<?php echo $strings["sending"]; ?>
	</div>
</div>

<script>
function delete_folder(dir_name)
{
	document.getElementById("hidden_action").value = "delete_folder";
	document.getElementById("hidden_item_name").value = "<?php echo $dir . "/"; ?>" + dir_name;
	document.getElementById("hidden_form").submit();
}
function delete_file(file_name)
{
	document.getElementById("hidden_action").value = "delete_file";
	document.getElementById("hidden_item_name").value = "<?php echo $dir . "/"; ?>" + file_name;
	document.getElementById("hidden_form").submit();
}

<?
if($type == "flash"){
   echo "document.getElementById('div_main_preview').style.display='none'";
}
?>
</script>
<div style="display: none;">
	<form method="post" id="hidden_form">
	<input type="hidden" name="action" id="hidden_action" value="">
	<input type="hidden" name="item_name" id="hidden_item_name" value="">
	</form>
</div>
</body>
</html>