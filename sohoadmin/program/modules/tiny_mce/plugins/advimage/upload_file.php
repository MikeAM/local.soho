<?php

session_start();
require_once("../../../../includes/product_gui.php");

set_time_limit(0);
ini_set("max_input_time", "600");
ini_set("max_execution_time", "600");
ini_set("memory_limit", "104857600");
ini_set("upload_max_filesize", "104857600");
ini_set("post_max_size", "104857600");

//echo "IN here!<br/>\n";

//print_r($_FILES);

$img_dir = $_SESSION['doc_root']."/images";

$info = array();

if (isSet($_REQUEST["action"]))
{
	if ($_REQUEST["action"] == "upload_file")
	{
		$filename = $img_dir . "/" . basename($_FILES["file"]["name"]);
		//echo "<b>(".$filename.")</b><br/>\n";
		if (file_exists($filename)){
		   if(!$_REQUEST['overwrite']){
   		   $NEW_filename = rand() . "_" . basename($_FILES["file"]["name"]);
   		   $filename = $img_dir . "/" . $NEW_filename;
   		   $info[] = "File name <b>".basename($_FILES["file"]["name"])."</b> already exsists and was not overwritten. File uploaded as <b>".$NEW_filename."</b>\n";
   		   //$info[] = "\n";
   		}else{
   		   $info[] = "<b>".basename($_FILES["file"]["name"])." uploaded correctly and overwrote the exsisting file.</b>\n";
   		}
   	}else{
   	   $info[] = "<b>".basename($_FILES["file"]["name"])." uploaded!</b>\n";
   	}
		
		if (move_uploaded_file($_FILES["file"]["tmp_name"], $filename)){
		   if(count($info) > 0){
            foreach($info as $var=>$val){
               echo $val."<br>\n";
            }
		   }
			//echo = "File uploaded as <b>".$NEW_filename."</b>\n";
		}else{
		   echo "<b>Could not upload file.  The file may be to large.</b><br/>\n";
		}
	}
	
   $filenames = '';
   foreach (glob($_SESSION['doc_root'].'/images/*') as $filename1) {
	if(!is_dir($filename1) && preg_match('/\.(jpg|jpeg|gif|png|tiff|bmp)$/i', $filename1)){
   		$filenames .= basename($filename1)."\n";
   	}
   }
   $filenames = eregi_replace("\n$", '', $filenames);
   $imglist_AR = explode("\n", $filenames);
   
   usort($imglist_AR, "strnatcasecmp");
   $jsdisp = "<select onchange=\"this.form.src.value=this.options[this.selectedIndex].value;onSelectMainImage('src',this.options[this.selectedIndex].text,this.options[this.selectedIndex].value);\" onfocus=\"tinyMCE.addSelectAccessibility(event, this, window);\" class=\"mceImageList\" name=\"imagelistsrc\" id=\"imagelistsrc\">\n";
   $jsdisp .= "<option>---</option>\n";
   foreach($imglist_AR as $imgv=>$imgn) {
   	$jsdisp .= "<option value=\"images/".$imgn."\">".$imgn."</option>\n";
   }
   $jsdisp = eregi_replace("\,\n$", '', $jsdisp);
   $jsdisp .= "</select>\n";
	
//	echo "<script language=\"javascript\">\n";
//	echo "   function joeTalk(){\n";
//	echo "      alert('joetalk');\n";
//	echo "   }\n";
//	
//	echo "   alert('doing it..')\n";
//	echo "   var new_image_list = '".$jsdisp."'\n";
//	echo "   document.getElementById('imagelistsrccontainer').innerHTML = new_image_list;\n";
//	echo "</script>\n";
	
	echo "<div id=\"new_image_list\" style=\"display: none;\">".$jsdisp."</div>\n";
	
	
	
}

?>