<?php
 /*********************************************************
 * Author    : JJFP
 * Para i18n
  **********************************************************/

require_once("modelos/j3_i18n.php");


function i18n($text, $lang) {
	$i = new J3_i18n();
	$i->addCondition("text_code", $text);
	$i->addCondition("lang_code", $lang);
	if ($i->doSelectAll() && $i->next()) {
		return $i->getValue("text_trans");
	} else {
		return $text;
	}
}

function i18n($text) {
	if (isset($_SESSION["j3_jang"])) {
		return i18n($text, $_SESSION["j3_jang"]);		
	}
	return $text;
}

function setJ3Lang($lang) {
	$_SESSION["j3_jang"] = $lang;
}

function setDefaultJ3Lang() {
	$_SESSION["j3_lang"] = DEFAULT_LANG;
}


  
?>