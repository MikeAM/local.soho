<?php
    error_reporting(E_PARSE);
	$spellCheckerConfig = array();

	// Spellchecker class use
	require_once("classes/TinyGoogleSpell.class.php"); // Google web service

	// General settings
	$spellCheckerConfig['enabled'] = true;

	// Default settings
	$spellCheckerConfig['default.language'] = 'en';

	// Normaly not required to configure
	$spellCheckerConfig['default.spelling'] = "";
	$spellCheckerConfig['default.jargon'] = "";
	$spellCheckerConfig['default.encoding'] = "";
?>
