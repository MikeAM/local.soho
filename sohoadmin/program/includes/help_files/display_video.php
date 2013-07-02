<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }
?>

<html>
  <head>
    <title>adminonew</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <meta name="Keywords" content="">
      <meta name="Description" content="">
    </head>

<body bgcolor="#ffffff">

<center>

	<PARAM NAME=movie VALUE="adminonew.swf">
	<PARAM NAME=quality VALUE=high>
	<PARAM NAME=bgcolor VALUE=#FFFFFF> 
	<EMBED src="http://<? echo $_GET['flash_demo_path']; ?>/<? echo $_GET['swffile']; ?>.swf" quality=high bgcolor=#FFFFFF WIDTH="750" HEIGHT="541"
	NAME="adminonew" ALIGN="" TYPE="application/x-shockwave-flash"	PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer">
	</EMBED>


</center>

</body>

</html>
