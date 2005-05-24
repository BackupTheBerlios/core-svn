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


function br2nl($text) {
    
    $text = preg_replace( "#(?:\n|\r)?<br />(?:\n|\r)?#", "\r\n", $text);
    $text = preg_replace( "#(?:\n|\r)?<br>(?:\n|\r)?#"  , "\r\n", $text);
    
    return $text;
}


function str_nl2br($s) {
	
	return str_replace(array("\r\n", "\r", "\n"), '<br />', $s);
}


function get_breadcrumb($page_id, $level) {
	
	global 
        $ft, 
        $rewrite, 
        $pages_sort, 
        $pages_id;

	$query = sprintf("
        SELECT 
            id, 
            parent_id, 
            title 
        FROM 
            %1\$s 
        WHERE 
            id = '%2\$d' 
        AND 
            published = 'Y' 
        ORDER BY 
            id 
        ASC", 
	
        TABLE_PAGES, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$page_name 	= $db->f("title");
		
		$page_link  = isset($rewrite) && $rewrite == 1 ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
	
		$ft->assign(array(
            'PAGE_TITLE'    =>$page_name,
            'PAGE_ID'       =>$page_id,
            'CLASS'         =>"child",
            'PARENT'        =>str_repeat('&nbsp; ', $level), 
            'PAGE_LINK'     =>$page_link
        ));
        
        $pages_sort[]   = $page_name;
        $pages_id[]     = $page_id;

		//$ft->parse('BREADCRUMB_ROW', ".breadcrumb_row");
		get_breadcrumb($parent_id, $level+2);
	}
	
}


function get_cat($page_id, $level) {
	
	global 
        $ft, 
        $rewrite;

	$query = sprintf("
        SELECT 
            id, 
            parent_id, 
            title 
        FROM 
            %1\$s 
        WHERE 
            parent_id = '%2\$d' 
        AND 
            published = 'Y' 
        ORDER BY 
            id 
        ASC", 
	
        TABLE_PAGES, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$page_name 	= $db->f("title");
		
		$page_link  = isset($rewrite) && $rewrite == 1 ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
	
		$ft->assign(array(
            'PAGE_NAME' =>$page_name,
            'PAGE_ID'   =>$page_id,
            'CLASS'     =>"child",
            'PARENT'    =>str_repeat('&nbsp; ', $level), 
            'PAGE_LINK' =>$page_link
        ));

		$ft->parse('PAGES_ROW', ".pages_row");
		get_cat($page_id, $level+2);
	}
}


function get_addpage_cat($page_id, $level) {
	
	global $ft;

	$query = sprintf("
        SELECT 
            id, 
            parent_id, 
            title 
        FROM 
            %1\$s 
        WHERE 
            parent_id = '%2\$d' 
        AND 
            published = 'Y' 
        ORDER BY 
            id 
        ASC", 
	
        TABLE_PAGES, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$parent_id 	= $db->f("parent_id");
		$title 		= $db->f("title");
	
		$ft->assign(array(
            'C_ID'		=>$page_id,
            'C_NAME'	=>str_repeat('&nbsp; ', $level) . "- " .$title
        ));

		$ft->define("form_pageadd", "form_pageadd.tpl");
        $ft->define_dynamic("page_row", "form_pageadd");
        
        $ft->parse('ROWS', ".page_row");
		
		get_addpage_cat($page_id, $level+2);
	}
}

// funkcja pobierajaca rekurencyjnie kategorie na stronie g³ównej
function get_category_cat($cat_id, $level) {
	
	global 
        $ft, 
        $rewrite;

	$query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$d' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        $cat_id
    );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");
		
		$cat_link = isset($rewrite) && $rewrite == 1 ? '1,' . $cat_id . ',4,item.html' : 'index.php?p=4&amp;id=' . $cat_id . '';
	
		$ft->assign(array(
            'CAT_NAME'  =>$cat_name,
            'NEWS_CAT'  =>$cat_id,
            'CLASS'     =>"cat_child",
            'PARENT'    =>str_repeat('&nbsp; ', $level), 
            'CAT_LINK'   =>$cat_link
        ));

		$ft->parse('CATEGORY_ROW', ".category_row");
		get_category_cat($cat_id, $level+2);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie
function get_addcategory_cat($page_id, $level) {
	
	global $ft;

	$query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$d' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");
	
		$ft->assign(array(
            'C_ID'		=>$cat_id,
            'C_NAME'	=>str_repeat('&nbsp; ', $level) . "- " .$cat_name
        ));

		$ft->define("form_category", "form_category.tpl");
        $ft->define_dynamic("category_row", "form_category");
        
        $ft->parse('ROWS', ".category_row");
		
		get_addcategory_cat($cat_id, $level+2);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie::transfer wpisow
function get_transfercategory_cat($page_id, $level) {
	
	global $ft;

	$query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$d' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");

        $ft->assign(array(
            'CURRENT_CID'   =>$cat_id,
            'TARGET_CID'    =>$cat_id,
            'CURRENT_CNAME' =>str_repeat('&nbsp; ', $level) . "- " .$cat_name,
            'TARGET_CNAME'  =>str_repeat('&nbsp; ', $level) . "- " .$cat_name
        ));

		$ft->parse('CURRENT_ROW', ".current_row");
        $ft->parse('TARGET_ROW', ".target_row");
		
		get_transfercategory_cat($cat_id, $level+2);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie::edycja newsa
function get_editnews_cat($c_id, $level) {
	
	global 
        $ft, 
        $category;

	$query = sprintf("
        SELECT 
            category_id, 
            category_parent_id, 
            category_name 
        FROM 
            %1\$s 
        WHERE 
            category_parent_id = '%2\$s' 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        $c_id
    );

	$db = new DB_SQL;
	$db->query($query);
	
	$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		
	while($db->next_record()) {
	
		$cat_id           = $db->f("category_id");
		$cat_parent_id    = $db->f("category_parent_id");
		$cat_name         = $db->f("category_name");
		
		if($cat_id == $category) {
		    
		    $ft->assign('CURRENT_CAT', 'selected="selected"');
		} else {
		    $ft->assign('CURRENT_CAT', '');
		}
	
		$ft->assign(array(
            'C_ID'		=>$cat_id,
            'C_NAME'	=>str_repeat('&nbsp; ', $level) . "- " .$cat_name
        ));

		$ft->define("form_noteedit", "form_noteedit.tpl");
        $ft->define_dynamic("category_row", "form_noteedit");
        
        $ft->parse('ROWS', ".category_row");
		
		get_editnews_cat($cat_id, $level+2);
	}
}

// funkcja pobierajaca rekurencyjnie kategorie::lista kategorii
function get_editcategory_cat($category_id, $level) {
	
	global 
	   $ft, 
	   $idx1, 
	   $count, 
	   $i18n;

	$query = sprintf("
        SELECT 
            a.*, count(b.id) AS count 
        FROM 
            %1\$s a 
        LEFT JOIN 
            %2\$s b 
        ON 
            a.category_id = b.c_id 
        WHERE 
            category_parent_id = '%3\$d'
        GROUP BY 
            category_id 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        TABLE_MAIN,
        $category_id
        );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$category_id          = $db->f("category_id");
		$category_name        = $db->f("category_name");
		$cat_parent_id        = $db->f("category_parent_id");
		$category_descrition  = $db->f("category_description");
		$count                = $db->f("count");
	
		$ft->assign(array(
            'CATEGORY_ID'		=>$category_id,
            'CATEGORY_NAME'		=>str_repeat('&nbsp; ', $level) . "<img src=\"templates/images/ar.gif\" />&nbsp;" . $category_name,
            'COUNT'				=>$count, 
            'UP'                =>'', 
            'DOWN'              =>''
        ));
        
        if(empty($category_description)) {
            
            $ft->assign('CATEGORY_DESC', $i18n['edit_category'][4]);
        } else {
            
            $ft->assign('CATEGORY_DESC', $category_description);
        }
		
		// deklaracja zmiennej $idx1::color switcher
		$idx1 = empty($idx1) ? '' : $idx1;
				
		$idx1++;
		
        $ft->define("editlist_links", "editlist_links.tpl");
        $ft->define_dynamic("row", "editlist_links");
			
		// naprzemienne kolorowanie wierszy tabeli
		if (($idx1%2)==1) {
				
			$ft->assign('ID_CLASS', 'mainList');
			
			$ft->parse('ROWS',	".row");
		} else {
				
			$ft->assign('ID_CLASS', 'mainListAlter');
			
			$ft->parse('ROWS',	".row");
		}
		
		get_editcategory_cat($category_id, $level+2);
	}
}


function get_editpage_cat($page_id, $level) {
	
	global 
        $ft, 
        $idx1;

	$query = sprintf("
        SELECT 
            id, 
            parent_id, 
            title, 
            published 
        FROM 
            %1\$s 
        WHERE 
            parent_id = '%2\$d' 
        ORDER BY 
            id 
        ASC", 
	
        TABLE_PAGES, 
        $page_id
    );

	$db = new DB_SQL;
	$db->query($query);
		
	while($db->next_record()) {
	
		$page_id 	= $db->f("id");
		$title 		= $db->f("title");
		$published	= $db->f("published");
	
		$ft->assign(array(
            'ID'    =>$page_id,
            'TITLE' =>str_repeat('&nbsp; ', $level) . "<img src=\"templates/images/ar.gif\" />&nbsp;" . $title, 
            'UP'    =>'', 
            'DOWN'  =>''
        ));
							
		if($published == 'Y') {

			$ft->assign('PUBLISHED', "Tak");
		} else {
				
			$ft->assign('PUBLISHED', "Nie");
		}
		
		// deklaracja zmiennej $idx1::color switcher
		$idx1 = empty($idx1) ? '' : $idx1;
				
		$idx1++;
		
        $ft->define("editlist_pages", "editlist_pages.tpl");
        $ft->define_dynamic("row", "editlist_pages");
			
		// naprzemienne kolorowanie wierszy tabeli
		if (($idx1%2)==1) {
				
			$ft->assign('ID_CLASS', 'mainList');
			
			$ft->parse('ROWS',	".row");
		} else {
				
			$ft->assign('ID_CLASS', 'mainListAlter');
			
			$ft->parse('ROWS',	".row");
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

    $query = sprintf("
        SELECT
            config_value
        FROM
            %1\$s
        WHERE
            config_name = '%2\$s'",
          
        TABLE_CONFIG,
        $name
    );

    $db -> query($query);
    $db -> next_record();

    return $db -> f('config_value');
}


function check_mail($email) {
    return eregi("^([a-z0-9_]|\\-|\\.)+@(((([a-z0-9_]|\\-)+\\.)+[a-z]{2,4})|localhost)$", $email);
}

// stronnicowanie 
function main_pagination($url, $q, $p, $published, $table, $category_pagination) {
    
    global 
        $db, 
        $mainposts_per_page, 
        $page_string;

	$ret = array();
	
	if($category_pagination === true) {
	    
	    $query = sprintf("
            SELECT
                category_post_perpage 
            FROM 
                %1\$s 
            WHERE 
                category_id = '%2\$d'", 
	
            TABLE_CATEGORY, 
            $_GET['id']
        );
        
        $db->query($query);
        $db->next_record();
        
        $mainposts_per_page = $db->f('category_post_perpage');
	} else {
	    $query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                config_name = '%2\$s'", 
	
            TABLE_CONFIG, 
            $p
        );
        
        $db->query($query);
        $db->next_record();
        
        $mainposts_per_page = $db->f('config_value');
	}
    
	$mainposts_per_page = empty($mainposts_per_page) ? 10 : $mainposts_per_page;
	
	$query = sprintf("
        SELECT 
            COUNT(*) AS id 
        FROM 
            %1\$s 
            %2\$s %3\$s 
        ORDER BY date", 
	
        $table, 
        $q, 
        $published);
    
	$db->query($query);
	$db->next_record();
	
	$num_items     = $db->f("0");
	$total_pages   = empty($total_pages) ? '' : $total_pages;
	
	$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	
	// Obliczanie liczby stron
	if($mainposts_per_page > 0) {
	    
	    if($num_items > $mainposts_per_page) {
	        $total_pages = ceil($num_items/$mainposts_per_page);
	    }
	    // Obliczanie strony, na której obecnie jestesmy
		$on_page = floor($start / $mainposts_per_page) + 1;
	} else {
		$total_pages = 0;
		$on_page = 0;
	}
	
	if($total_pages == 1) echo '';
	
	$page_string = '';
	
	if($total_pages > 6) {
	    $init_page_max = ($total_pages > 3) ? 3 : $total_pages;
	    for($i = 1; $i < $init_page_max + 1; $i++) {
	        $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . $url . (($i - 1) * $mainposts_per_page) . '">' . $i . '</a>';
	        if($i <  $init_page_max) {
	            $page_string .= ", ";
	        }
	    }
	    
	    if($total_pages > 3) {
	        if($on_page > 1  && $on_page < $total_pages) {
	            $page_string .= ($on_page > 5) ? ' ... ' : ', ';
	            
	            $init_page_min = ($on_page > 4) ? $on_page : 5;
	            $init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;
	            
	            for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++) {
	                $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . $url . (($i - 1) * $mainposts_per_page) . '">' . $i . '</a>';
	                if($i < $init_page_max + 1) {
	                    $page_string .= ', ';
	                }
	            }
	            
	            $page_string .= ($on_page < $total_pages - 4) ? ' ... ' : ', ';
	        } else {
	            $page_string .= ' ... ';
	        }
	        
	        for($i = $total_pages - 2; $i < $total_pages + 1; $i++) {
	            $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>'  : '<a href="' . $url . (($i - 1) * $mainposts_per_page) . '">' . $i . '</a>';
	            if($i < $total_pages) {
	                $page_string .= ", ";
	            }
	        }
	    }
	} else {
	    
	    for($i = 1; $i < $total_pages + 1; $i++) {
	        $page_string .= ($i == $on_page) ? '<b>' . $i . '</b>' : '<a href="' . $url . (($i - 1) * $mainposts_per_page) . '">' . $i . '</a>';
	        if($i <  $total_pages) {
	            $page_string .= ', ';
	        }
	    }
	}
	
	if($on_page > 1) {
	    $page_string = ' <a href="' . $url . (($on_page - 2) * $mainposts_per_page) . '">' . " <b>poprzednia</b>" . '</a>&nbsp;&nbsp;' . $page_string;
	}
	
	if($on_page < $total_pages) {
	    $page_string .= '&nbsp;&nbsp;<a href="' . $url . ($on_page * $mainposts_per_page) . '">' . "<b>nastêpna</b> " . '</a>';
	}
	
	$ret['mainposts_per_page'] = $mainposts_per_page;
	$ret['page_string']        = $page_string;
	
	return $ret;
}


function parse_markers($text, $break = 0, $tab = 0, $tab_long = 4) {
    
    $pregResultArr      = array();
    $pregResultArrSize  = 0;
    $hash               = md5($text);
    $tempArr            = array();
    
    preg_match_all("#<(ul|li)[^>]*?>.*?</(\\1)>#si", $text, $pregResultArr);
    
    $pregResultArrSize = sizeOf($pregResultArr[0]);
    
    for($i=0; $i<$pregResultArrSize; $i++){
        $tempArr[$i] = $hash.'_'.$i;
    }
    
    $text = str_replace($pregResultArr[0], $tempArr, $text);
    
    $break  == 1 ? $text = str_nl2br($text) : '';
    $tab    == 1 ? $text = str_replace("\t", str_repeat('&nbsp;', $tab_long), $text) : '';
    
    $text = str_replace($tempArr, $pregResultArr[0], $text);
    
    return $text;
}


function show_me_more($text) {
    
    global 
        $perma_link, 
        $i18n;
    
	if($find = strpos($text, '[podziel]')) {
	        
        $text = sprintf('%s<br /><a href="%s">%s</a>',
        
            substr($text, 0, $find),
            $perma_link,
            $i18n['main_view'][1]
        );
	}
	
	return $text;
}


?>