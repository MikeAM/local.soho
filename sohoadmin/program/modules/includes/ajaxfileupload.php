<?php
exit;
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
session_start();

$curdir = getcwd();
chdir(str_replace(basename(__FILE__), '', __FILE__));
require_once('../../includes/product_gui.php');
chdir($curdir);

###############################################################################
## Soholaunch(R) Site Management Tool
## Version 4.5
##
## Author: 			Mike Johnston [mike.johnston@soholaunch.com]
## Homepage:	 	http://www.soholaunch.com
## Bug Reports: 	http://bugzilla.soholaunch.com
## Release Notes:	sohoadmin/build.dat.php
###############################################################################

##############################################################################
## COPYRIGHT NOTICE
## Copyright 1999-2003 Soholaunch.com, Inc. and Mike Johnston
## Copyright 2003-2007 Soholaunch.com, Inc.
## All Rights Reserved.
##
## This script may be used and modified in accordance to the license
## agreement attached (license.txt) except where expressly noted within
## commented areas of the code body. This copyright notice and the comments
## comments above and below must remain intact at all times.  By using this
## code you agree to indemnify Soholaunch.com, Inc, its coporate agents
## and affiliates from any liability that might arise from its use.
##
## Selling the code for this program without prior written consent is
## expressly forbidden and in violation of Domestic and International
## copyright laws.
###############################################################################

echo "<html>\n";
echo "	<head>\n";
echo "	<link href=\"ajaxfileupload.css\" type=\"text/css\" rel=\"stylesheet\">\n";
echo "	<script type=\"text/javascript\" src=\"http://".$_SESSION['this_ip'].'/sohoadmin/client_files/jquery.min.js">'."</script>\n";
echo "	<script type=\"text/javascript\" src=\"http://".$_SESSION['this_ip'].'/sohoadmin/program/modules/includes/ajaxfileupload.js">'."</script>\n";
?>
	<script type="text/javascript">
	function ajaxFileUpload(){
		$("#loading")
		.ajaxStart(function(){
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();
		});

		$.ajaxFileUpload
		(
			{
				url:'doajaxfileupload.php',
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{
							alert(data.error);
						}else
						{
							alert(data.msg);
						}
					}
				},
				error: function (data, status, e)
				{
					alert(e);
				}
			}
		)
		
		return false;

	}
	</script>
	</head>

	<body>
		<img id="loading" src="loading.gif" style="display:none;">
		<form name="form" action="" method="POST" enctype="multipart/form-data">
		<input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input">
		<button class="button" id="buttonUpload" onclick="return ajaxFileUpload();">Upload</button>
		</form>
    

	</body>
</html>