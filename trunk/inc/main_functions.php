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
	$months = array(
		'01' => 'Stycznia',
		'02' => 'Lutego',
		'03' => 'Marca',
		'04' => 'Kwietnia',
		'05' => 'Maja',
		'06' => 'Czerwca',
		'07' => 'Lipca',
		'08' => 'Sierpnia',
		'09' => 'Wrze¶nia',
		'10' => 'Pa¼dziernika',
		'11' => 'Listopada',
		'12' => 'Grudnia'
	);
	
	$date_ex[1] = $months[$date_ex[1]];
	
	$date		= $date_ex[2] . " " . $date_ex[1] . ", " . $date_ex[0] . "&nbsp;&nbsp;" . $newdate[1];
	
	return $date;
}


function coreRssDateConvert($date) {
	
	$newdate	= explode(' ', $date);
	
	$date_ex	= explode('-', $newdate[0]);
	$months = array(
		'01' => 'Jan',
		'02' => 'Feb',
		'03' => 'Mar',
		'04' => 'Apr',
		'05' => 'May',
		'06' => 'Jun',
		'07' => 'Jul',
		'08' => 'Aug',
		'09' => 'Sep',
		'10' => 'Oct',
		'11' => 'Nov',
		'12' => 'Dec'
	);
	
	$date_ex[1] = $months[$date_ex[1]];
		
	$date		= $date_ex[2] . " " . $date_ex[1] . " " . $date_ex[0] . " " . $newdate[1];
	
	return $date;
}


function str_nl2br($s) {
	
	return str_replace(array("\r\n", "\r", "\n"), '<br />', $s);
}

?>