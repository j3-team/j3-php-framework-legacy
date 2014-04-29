<?php

function url($text) {
	if (FS_TYPE == 2) {
		$text = "/$text";
	}
	return $text;
}

?>