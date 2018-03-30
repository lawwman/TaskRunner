<?php
	require('debugging.php');

	function getSanitizedEmail($email) {
		return filter_var($email, FILTER_SANITIZE_EMAIL);
	}

	function isValidEmail($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	// returns sanitized email if sanitized email is valid, else return false
	function getValidEmail($email) {
		$sanitized = getSanitizedEmail($email);
		if (isValidEmail($sanitized)) {
			return $sanitized;
		} else {
			return false;
		}
	}

	function getSanitizedString($string) {
		return filter_var($string, FILTER_SANITIZE_STRING);
	}

	// returns sanitized string 
	function getValidString($string) {
		return getSanitizedString($string);
	}

	function getSanitizedNumeral($numeral) {
		return filter_var($numeral, FILTER_SANITIZE_NUMBER_INT);
	}

	function isValidNumeral($numeral) {
		return filter_var($numeral, FILTER_VALIDATE_INT);
	}

	// returns sanitized nuemral
	function getValidNumeral($numeral) {
		$sanitized = getSanitizedNumeral($numeral);
		if (isValidNumeral($sanitized)) {
			return $sanitized;
		} else {
			return false;
		}
	}
	
	/*
	function testSanitize() {
		$emailTest = "tesla123123/';/@gmail.com";
		consoleLog(getSanitizedEmail($emailTest));
		consoleLog(getValidEmail($emailTest));
		consoleLog(getSanitizedString($emailTest));
		consolelog(getSanitizedNumeral($emailTest));
		consoleLog(getSanitizedEmail($emailTest));
		consoleLog($_SERVER['DOCUMENT_ROOT']);
	}
	*/
?>
