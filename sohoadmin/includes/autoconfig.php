<?php
error_reporting(E_PARSE);
if($_GET['_SESSION'] != '' || $_POST['_SESSION'] != '' || $_COOKIE['_SESSION'] != '') { exit; }

$serverstatus = include_r('http://update.securexfer.net/media/proxy.php?proxy=update.securexfer.net/media/serverup.php');

if($serverstatus == 'Server Up'){
	$this_ip = '';
	$final_ip = '';
	$body = '';
	$filenameisp = "config/isp.conf.php";
	if(!$fileisp = fopen($filenameisp, "r")) {
	} else {
		$body = fread($fileisp,filesize($filenameisp));
		$lines = split("\n", $body);
		$numLines = count($lines);
	
		for ($x=2;$x<=$numLines;$x++) {
			if (!eregi("#", $lines[$x])) {
				$variable = strtok($lines[$x], "="); 
				$value = strtok("\n");
				$value = rtrim($value);
				if($variable == 'this_ip' || $variable == 'final_ip'){
				${$variable} = $value;			
				}
			}
		}
		fclose($fileisp);
	
		if(!is_dir('filebin')) {
			mkdir('filebin', 0755);
		}
		$testfile = "filebin/test.txt";
		if(is_file($testfile)) { unlink($testfile); }
		$testtime = base64_encode(microtime());
		if(!$twfile = fopen($testfile, "w+")) {
			echo "<font color=\"#FFFFFF\">*</font><font color=\"#3873B9\">Can't Write to /sohoadmin/filebin , autoresolve failed.</font>";
		} else {
			fwrite($twfile, $testtime);
			fclose($twfile);
			
			if($final_ip == ''){
				$this_ipurl = $this_ip."/sohoadmin/filebin/test.txt";
			} else {     
				$this_ipurl = $final_ip."/sohoadmin/filebin/test.txt";
			}
			$proxyz = 'http://update.securexfer.net/media/proxy.php?proxy=';
			$ddomain = $proxyz.$this_ipurl;
					
  
			$readtime = include_r($ddomain);

			$readtime = eregi_replace(' ', '', $readtime);
		
			if($testtime == $readtime){
				$resolved = 'hellzyes';
			} else {
				$resolved = 'hellzno';
			}
			
			unlink($testfile);      
			
			if($resolved == 'hellzyes' && $final_ip != '' && $done != 'log em in'){
				/// domain is finaly resolving yay
        echo "<font color=\"#FFFFFF\">*</font><font color=\"#3873B9\">".$final_ip." is now resolving!<br>  changing domain to ".$final_ip." in the config file, and updating all domain paths for all site content.</font>";
        
        $blogquery = mysql_query('select * from blog_content');
				while($blogcont = mysql_fetch_array($blogquery)) {
					$dapri = $blogcont['PRIKEY'];
					$oldcontt = $blogcont['BLOG_DATA'];
					$newcott = eregi_replace($this_ip, $final_ip, $oldcontt);
					$newqry = "update blog_content set BLOG_DATA = '".$newcott."' where PRIKEY = '".$dapri."'";
					mysql_query($newqry);
      	}
        
        $ifinal_ip = $final_ip;
        $ithis_ip = $this_ip; 
        $newisp = eregi_replace('this_ip='.$ithis_ip, 'this_ip='.$ifinal_ip, $body);
        $newisp2 = eregi_replace('final_ip='.$ifinal_ip, '', $newisp);
         
        $fileispw = fopen($filenameisp, "w");
        fwrite($fileispw, $newisp2);
        fclose($fileispw);
        $mycwd = getcwd();

        //fix page content files
        foreach (glob('tmp_content/*.con') as $filename) {
          $pagecon = $filename;
          $filesizecon = filesize($pagecon);
          $pagereg = eregi_replace('\.con', '.regen', $pagecon);
          $filesizereg = filesize($pagereg);  
          
          if($filesizecon > 0) {
            $filec = fopen($pagecon, "r");      
            $concontent = fread($filec, $filesizecon);      
            $newconcontent = eregi_replace($this_ip, $final_ip, $concontent);
            fclose($filec);
            $filecw = fopen($pagecon, "w");
            fwrite($filecw, $newconcontent);
            fclose($filecw);
          }
                    
          if($filesizereg > 0) {
            $filer = fopen($pagereg, "r");
            $regcontent = fread($filer, $filesizereg);
            $newregcontent = eregi_replace($this_ip, $final_ip, $regcontent);
            fclose($filer);
            $filerw = fopen($pagereg, "w");
            fwrite($filerw, $newregcontent);
            fclose($filerw);
          }       
        }
        

        $_SESSION['this_ip'] = $ifinal_ip;  
        $this_ip = $ifinal_ip;
        $_SESSION['final_ip'] = '';
        $final_ip = '';     
        $done = 'log em in';      
      } 
      
      if($resolved == 'hellzno' && $final_ip == '' && $done != 'log em in'){
        // new account that is trying to login to unresolved domain
        
        $thisscript = basename(__FILE__);
        $uri = eregi_replace($thisscript, '', $_SERVER['PHP_SELF']);
        $this_url = rtrim($_SERVER['HTTP_HOST'].$uri, "/\\");
        $this_url = eregi_replace('/sohoadmin/index.php', '', $this_url);
        
        $camurl = eregi_replace('www\.', '', $this_url);

        if(eregi($camurl, $this_ip)) {
      	} else {
	        $fileispw = fopen($filenameisp, "w+");
	        $newisp = eregi_replace('this_ip='.$this_ip, 'this_ip='.$this_url."\n".'final_ip='.$this_ip, $body);
	        fwrite($fileispw, $newisp);
	        fclose($fileispw);	
	        echo "<font color=\"#FFFFFF\">*</font><font color=\"#3873B9\">Cant resolve ".$this_ip." to this server. Temporarily Changing this domain name to ".$this_url." in config file.<br>When ".$this_ip." starts resolving to this server, the config file will automaticly be changed back.</font>";
	        $_SESSION['this_ip'] = $this_url;
	        $_SESSION['final_ip'] = $this_ip;
	        $this_ip = $this_url;
	        $final_ip = $this_ip;
				}
				$done = 'log em in';
      }
        
      
      if($resolved == 'hellzyes' && $final_ip == '' && $done != 'log em in'){
        //echo "3normal";//// let them there is no prob
      }
		}
	}
}
?>