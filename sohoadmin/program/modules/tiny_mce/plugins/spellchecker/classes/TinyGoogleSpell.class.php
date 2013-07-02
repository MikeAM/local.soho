<?php
    error_reporting(E_PARSE);
/* *
 * Tiny Spelling Interface for TinyMCE Spell Checking.
 *
 * Copyright © 2006 Moxiecode Systems AB
 */

//require_once("HttpClient.class.php");

class TinyGoogleSpell {
	var $lang;

	function TinyGoogleSpell(&$config, $lang, $mode, $spelling, $jargon, $encoding) {
		$this->lang = $lang;
	}

	// Returns array with bad words or false if failed.
	function checkWords($word_array) {
		$words = array();
		$wordstr = implode(' ', $word_array);

		$matches = $this->_getMatches($wordstr);

		for ($i=0; $i<count($matches); $i++)
			$words[] = substr($wordstr, $matches[$i][1], $matches[$i][2]);

		return $words;
	}

	// Returns array with suggestions or false if failed.
	function getSuggestion($word) {
		$sug = array();

		$matches = $this->_getMatches($word);

		if (count($matches) > 0)
			$sug = explode("\t", $matches[0][4]);

		return $sug;
	}

	function _getMatches($word_list) {

        $server = "www.google.com";
        $port = 443;
        $path = "/tbproxy/spell?lang=".$this->lang."&hl=".$this->lang;
       // $path = "/tbproxy/spell?lang=en&hl=en";
        $host = "www.google.com";
        $url = "https://" . $server;

			// Setup XML request
			$xml = '<?xml version="1.0" encoding="utf-8" ?><spellrequest textalreadyclipped="0" ignoredups="0" ignoredigits="1" ignoreallcaps="1"><text>' . htmlentities($word_list) . '</text></spellrequest>';

        $header  = "POST ".$path." HTTP/1.0 \r\n";
        $header .= "MIME-Version: 1.0 \r\n";
        $header .= "Content-type: application/PTI26 \r\n";
        $header .= "Content-length: ".strlen($xml)." \r\n";
        $header .= "Content-transfer-encoding: text \r\n";
        $header .= "Request-number: 1 \r\n";
        $header .= "Document-type: Request \r\n";
        $header .= "Interface-Version: Test 1.4 \r\n";
        $header .= "Connection: close \r\n\r\n";
        $header .= $xml;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $xml = curl_exec($ch);
        curl_close($ch);

		// Grab and parse content
		preg_match_all('/<c o="([^"]*)" l="([^"]*)" s="([^"]*)">([^<]*)<\/c>/', $xml, $matches, PREG_SET_ORDER);

		return $matches;
	}
}

// Setup classname, should be the same as the name of the spellchecker class
$spellCheckerConfig['class'] = "TinyGoogleSpell";

?>
