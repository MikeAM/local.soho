<?php
# Plugin Manager
error_reporting(E_PARSE);
session_start();

require_once("../../../../includes/product_gui.php");

//chdir('../
//include_once($_SESSION['doc_root']."/ultraadmin/program/includes/product_gui.php");
error_reporting(E_PARSE);
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Upload File</title>
	<script language="javascript" type="text/javascript" src="../../tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="../../utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="../../utils/validate.js"></script>
	<link rel="stylesheet" href="../../../../includes/product_buttons-ultra.css">
	<script language="javascript" type="text/javascript" src="jscripts/functions.js"></script>
	<link href="css/upFile.css" rel="stylesheet" type="text/css" />
	<base target="_self" />
	
    <script type="text/javascript" src="jscripts/webtoolkit.aim.js"></script>
    <script type="text/javascript">
        function startCallback() {
            // make something useful before submit (onStart)
            return true;
        }

        function completeCallback(response) {
        //	alert(response);
		document.getElementById('r').innerHTML = response;
          //  alert(document.getElementById('new_image_list').innerHTML)
           // var newList = document.getElementById('new_image_list').innerHTML;
            //document.getElementById("imagelistsrccontainer").innerHTML = newList;

        }
        
        function toggleUpload(){
            document.getElementById('file').value=''
            document.getElementById('r').innerHTML='&nbsp;'
            if(document.getElementById('upload_popup').style.display=='none'){
               document.getElementById('upload_popup').style.display='block';
            }else{
               document.getElementById('upload_popup').style.display='none'
            }
        }
        
        
    </script>
	
</head>
<body id="advimage" onload="tinyMCEPopup.executeOnLoad('init();');" style="display: none">

   <div id="upload_popup" style="display: block;">
    <form action="upload_now.php" enctype="multipart/form-data" method="post" onsubmit="return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback})">
    <input type="hidden" name="action" value="upload_file">
      <div class="panel_wrapper" style="border-top: 1px solid #919B9C;">
      
			<fieldset>
				<legend>Upload Files</legend>
				
   				<table class="properties">
   					<tr>
   						<td class="column1" style="width:20%; text-align:right; padding-bottom:20px; padding-right:10px;"><label for="file">Select File</label></td>
   						<td colspan="2" style="padding-bottom:20px;"><input type="file" id="file" name="file" size=50 /></td>
   					</tr>
   					<tr>
   						<td  id="r" style="text-align:center;padding-top:0px;" colspan="3" align="right">&nbsp;<br/>
   						</td>
   					</tr>
   					<tr>
   						<td  style="padding-top:25px;" colspan="3" align="right">
							<button type="submit" class="greenButton" role="button" name="insert" id="insert" style="background-image:none!important;margin:0px 10px;float:right!important;padding:0;height:22px;"/><span><span>Upload</span></span></button>
							<button type="button" id="cancel" name="cancel" value="{#cancel}" class="grayButton" style="background-image:none!important;margin:0px 10px;float:right!important;padding:0;height:22px;"  onclick="tinyMCEPopup.close();" /><span><span>Done</span></span></button>
   						</td>
   					</tr>
   				</table>
              
         </fieldset>

      </div>
    </form>
   </div>
    
    
</body> 
</html> 
