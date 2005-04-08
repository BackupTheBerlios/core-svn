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


function get_cat($page_id, $level) {
	
	global $mysql_data, $ft;

	$query = "	SELECT 
					id, parent_id, title 
				FROM 
					$mysql_data[db_table_pages] 
				WHERE 
					parent_id = '$page_id' 
				AND 
					published = 'Y' 
				ORDER BY 
					id 
				ASC";

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$page_name 	= $db->f("title");
	
		$ft->assign(array(	'PAGE_NAME'	=>$page_name,
							'PAGE_ID'	=>$page_id,
							'PARENT'	=>str_repeat('&nbsp; ', $level)));

				
		$ft->parse('PAGES_LIST', ".pages_list");
		get_cat($page_id, $level+2);
	}
}


function get_addpage_cat($page_id, $level) {
	
	global $mysql_data, $ft;

	$query = "	SELECT 
					id, parent_id, title 
				FROM 
					$mysql_data[db_table_pages] 
				WHERE 
					parent_id = '$page_id' 
				AND 
					published = 'Y' 
				ORDER BY 
					id 
				ASC";

	$db = new DB_SQL;
	$db->query($query);
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$title 		= $db->f("title");
	
		$ft->assign(array(	'C_ID'		=>$page_id,
							'C_NAME'	=>str_repeat('&nbsp; ', $level) . "- " .$title));

		$ft->define('page_categoryoption', "page_categoryoption.tpl");		
		$ft->parse('CATEGORY_ROWS', ".page_categoryoption");
		
		get_addpage_cat($page_id, $level+2);
	}
}


function get_editpage_cat($page_id, $level) {
	
	global $mysql_data, $ft, $idx1;

	$query = "	SELECT 
					id, parent_id, title, published 
				FROM 
					$mysql_data[db_table_pages] 
				WHERE 
					parent_id = '$page_id' 
				ORDER BY 
					id 
				ASC";

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$title 		= $db->f("title");
		$published	= $db->f("published");
	
		$ft->assign(array(	'ID'		=>$page_id,
							'TITLE'		=>str_repeat('&nbsp; ', $level) . "<img src=\"layout/ar.gif\" />&nbsp;" . $title));
							
		if($published == 'Y') {

			$ft->assign('PUBLISHED', "Tak");
		} else {
				
			$ft->assign('PUBLISHED', "Nie");
		}
		
		// deklaracja zmiennej $idx1::color switcher
		$idx1 = empty($idx1) ? '' : $idx1;
				
		$idx1++;
			
		// naprzemienne kolorowanie wierszy tabeli
		if (($idx1%2)==1) {
				
			$ft->assign('ID_CLASS', "class=\"mainList\"");
			$ft->parse('NOTE_ROWS',	".table_pagelist");
		} else {
				
			$ft->assign('ID_CLASS', "class=\"mainListAlter\"");
			$ft->parse('NOTE_ROWS',	".table_pagelist");
		}
		
		get_editpage_cat($page_id, $level+2);
	}
}

function str_getext($file, $with_dot = true) {
	
	$p = pathinfo($file);
	if ($with_dot) {
		
		return '.' . $p['extension'];
	}
	return $p['extension'];
}

function get_root() {
	
	$p = pathinfo(__file__);
	return dirname($p['dirname']);
}

function v_array($array, $exit = 0) { 
	
	printf('<pre>%s</pre>', print_r($array, 1));
	
	if ($exit) {
		
		exit;
	}
}

function get_config($name) {

    $db = new DB_SQL;
    global $mysql_data;

    $query = sprintf("
        SELECT
            config_value
        FROM
            %1\$s
        WHERE
            config_name = '%2\$s'",
          
        $mysql_data['db_table_config'],
        $name
    );

    $db -> query($query);
    $db -> next_record();

    return $db -> f('config_value');
}

function check_mail($email) {
    return eregi("^([a-z0-9_]|\\-|\\.)+@(((([a-z0-9_]|\\-)+\\.)+[a-z]{2,4})|localhost)$", $email);
}

?>
