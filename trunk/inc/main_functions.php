<?php

function coreMakeClickable($text) {

	// pad it with a space so we can match things at the start of the 1st line.
	$ret = ' ' . $text;

	// matches an "xxxx://yyyy" URL at the start of a line, or after a space.
	// xxxx can only be alpha characters.
	// yyyy is anything up to the first space, newline, comma, double quote or <
	$text = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text);

	// matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
	// Must contain at least 2 dots. xxxx contains either alphanum, or "-"
	// zzzz is optional.. will contain everything up to the first space, newline, 
	// comma, double quote or <.
	$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);

	// matches an email@domain type address at the start of a line, or after a space.
	// Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
	$text = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $text);

	// Remove our padding..
	//$text = substr($text, 1);

	return($text);
}


function coreDateConvert($date) {
	
	$newdate	= explode(' ', $date);
	
	$date_ex	= explode('-', $newdate[0]);
	if($date_ex[1] == '01') $date_ex[1] = "Stycznia";
	if($date_ex[1] == '02') $date_ex[1] = "Lutego";
	if($date_ex[1] == '03') $date_ex[1] = "Marca";
	if($date_ex[1] == '04') $date_ex[1] = "Kwietnia";
	if($date_ex[1] == '05') $date_ex[1] = "Maja";
	if($date_ex[1] == '06') $date_ex[1] = "Czerwca";
	if($date_ex[1] == '07') $date_ex[1] = "Lipca";
	if($date_ex[1] == '08') $date_ex[1] = "Sierpnia";
	if($date_ex[1] == '09') $date_ex[1] = "Wrze¶nia";
	if($date_ex[1] == '10') $date_ex[1] = "Pa¼dziernika";
	if($date_ex[1] == '11') $date_ex[1] = "Listopada";
	if($date_ex[1] == '12') $date_ex[1] = "Grudnia";
	
	$date		= $date_ex[2] . " " . $date_ex[1] . ", " . $date_ex[0] . "&nbsp;&nbsp;" . $newdate[1];
	
	return $date;
}

?>