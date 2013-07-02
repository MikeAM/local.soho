<?php
error_reporting('E_PARSE');

require_once("pgm-site_config.php");
include_once("../program/includes/shared_functions.php");


if(!isset($_REQUEST['m'])){
	$getchatq = mysql_query("select * from simple_chat where time > '".strtotime('-24 hours')."' order by time asc");
	////header('Content-Type: text/html; charset=utf-8');
	echo "<div>\n";
	while($getchat = mysql_fetch_assoc($getchatq)){
		$mins_ago = "(".round((time() - $getchat['time']) / 60)." mins ago)";
		echo "<p class=\"chatmsgpar\"><span class=\"chatnamespan\">".$getchat['chat_name']."</span><span class=\"timespan\">".$mins_ago.":</span><span class=\"chatmsgspan\">".$getchat['message']."</span></p>\n";
	}
	echo "</div>\n";
	
} else {
	
	$maxlines = 18;
	$nick_length = 20;
	/* Set this to a minimum wait time between posts (in sec) */
	$waittime_sec = 0;	
	
	/* spam keywords */
	$spam[] = "nigger";
	$spam[] = "fuck";
	$spam[] = "shit";
	$spam[] = "fag";
	
	/* IP's to block */
	$blockip[] = "72.60.167.89";
	
	/* spam, if message IS exactly that string */	
	$espam[] = "ajax";
	
	/* Get Message & Nick from the Request and Escape them */
	$msg = $_REQUEST["m"];
	$msg = str_replace('http://', '', $msg);
	$msg = htmlspecialchars(slashthis($msg));
	
	$n = $_REQUEST["n"];
	$n = htmlspecialchars(slashthis($n));
	
	if (strlen($n) >= $nick_length) { 
		$n = substr($n, 0, $nick_length); 
	} else { 
		for ($i=strlen($n); $i<$nick_length; $i++) $n .= "";
	}
	
	if ($waittime_sec > 0) {
		$lastvisit = $_COOKIE["lachatlv"];
		setcookie("lachatlv", time());
	
		if ($lastvisit != "") {
			$diff = time() - $lastvisit;
			if ($diff < 5) { die();	}
		} 
	}
	
	if ($msg != "")  {
		if (strlen($msg) < 2) { die(); }
		if (strlen($msg) > 3) { 
			/* Smilies are ok */
			if (strtoupper($msg) == $msg) { die(); }
		}
		if (strlen($msg) > 280) { die(); }
		if (strlen($msg) > 15) { 
			if (substr_count($msg, substr($msg, 6, 8)) > 1) { die(); }
		}
	
		foreach ($blockip as $a) {
			if ($_SERVER["REMOTE_ADDR"] == $a) { die(); }
		}
		
		$mystring = strtoupper($msg);
		foreach ($spam as $a) {	
			 if (strpos($mystring, strtoupper($a)) === false) {
			 	/* Everything Ok Here */
			 } else {
			 	die();
			 }
		}		
	
		foreach ($espam as $a) {
			if (strtoupper($msg) == strtoupper($a)) { die(); }		
		}
	
		mysql_query("insert into simple_chat (chat_name, message, date, time, ip_address) values('".$n."','".$msg."','".date('Y/m/d')."','".time()."', '".$_SERVER['REMOTE_ADDR']."')");
		setcookie("chat_name", $n, time()+604800, '/');		
	}
}
?>
