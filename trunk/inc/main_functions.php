<?php

function coreMakeClickable($text) {

	$ret = ' ' . $text;

	$text = preg_replace("#(^|[\n ])([\w]+?://[^ \"\n\r\t<]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $text);
	$text = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r<]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);
	$text = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $text);

	// Remove our padding..
	//$text = substr($text, 1);

	return($text);
}


function coreRssDateConvert($date) {
	
	$newdate = explode(' ', $date);
	$date_ex = explode('-', $newdate[0]);
	
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


function br2nl($text, $nl = "\r\n") {
    return str_replace(array('<br />', '<br>', '<br/>'), $nl, $text);
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
		$page_link  = (bool)$rewrite ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
	
		$ft->assign(array(
            'PAGE_TITLE'    =>$page_name,
            'PAGE_ID'       =>$page_id,
            'CLASS'         =>"child",
            'PARENT'        =>str_repeat('&nbsp; ', $level), 
            'PAGE_LINK'     =>$page_link
        ));
        
        $pages_sort[]   = $page_name;
        $pages_id[]     = $page_id;

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
		$page_link  = (bool)$rewrite ? '1,' . $page_id . ',5,item.html' : 'index.php?p=5&amp;id=' . $page_id . '';
	
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


function get_addpage_cat($page_id, $level, $current_id = 0, $pageid_prefix = '') {
	
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
            'P_ID'      =>$pageid_prefix . $page_id,
            'P_NAME'    =>str_repeat('&nbsp; ', $level) . "- " .$title,
            'CURRENT'   =>$page_id == $current_id ? 'selected="selected"' : ''
        ));
        
        $ft->parse('PAGE_ROW', ".page_row");
		
		get_addpage_cat($page_id, $level+2, $current_id, $pageid_prefix);
	}
}

// funkcja pobierajaca rekurencyjnie kategorie na stronie g��wnej
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
		$cat_link         = (bool)$rewrite ? '1,' . $cat_id . ',4,item.html' : 'index.php?p=4&amp;id=' . $cat_id . '';
	
		$ft->assign(array(
            'CAT_NAME'  =>$cat_name,
            'NEWS_CAT'  =>$cat_id,
            'CLASS'     =>"cat_child",
            'PARENT'    =>str_repeat('&nbsp; ', $level), 
            'CAT_LINK'  =>$cat_link
        ));

		$ft->parse('CATEGORY_ROW', ".category_row");
		get_category_cat($cat_id, $level+2);
	}
}


// funkcja pobierajaca rekurencyjnie kategorie
function get_addcategory_cat($page_id, $level, $current_id = 0, $pageid_prefix = '') {
	
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
            'C_ID'		=>$pageid_prefix . $cat_id,
            'C_NAME'	=>str_repeat('&nbsp; ', $level) . "- " .$cat_name,
            'CURRENT'   =>$cat_id == $current_id ? 'selected="selected"' : ''
        ));

        $ft->parse('CATEGORY_ROW', ".category_row");
		
		get_addcategory_cat($cat_id, $level+2, $current_id, $pageid_prefix);
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
            a.category_id = b.category_id 
        WHERE 
            category_parent_id = '%3\$d'
        GROUP BY 
            category_id 
        ORDER BY 
            category_id 
        ASC", 
	
        TABLE_CATEGORY, 
        TABLE_ASSIGN2CAT,
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
            'CATEGORY_ID'   =>$category_id,
            'CATEGORY_NAME' =>str_repeat('&nbsp; ', $level) . "<img src=\"templates/images/ar.gif\" />&nbsp;" . $category_name,
            'COUNT'         =>$count, 
            'UP'            =>'', 
            'DOWN'          =>'', 
            'CATEGORY_DESC' =>empty($category_description) ? $i18n['edit_category'][4] : $category_description
        ));
		
		// deklaracja zmiennej $idx1::color switcher
		$idx1 = empty($idx1) ? '' : $idx1;
				
		$idx1++;
			
		// naprzemienne kolorowanie wierszy tabeli
		$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
		
		$ft->parse('ROWS', ".row");
		
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
            'ID'        =>$page_id,
            'TITLE'     =>str_repeat('&nbsp; ', $level) . "<img src=\"templates/images/ar.gif\" />&nbsp;" . $title, 
            'UP'        =>'', 
            'DOWN'      =>'', 
            'PUBLISHED' =>$published == 'Y' ? 'Tak' : 'Nie'
        ));
		
		// deklaracja zmiennej $idx1::color switcher
		$idx1 = empty($idx1) ? '' : $idx1;
				
		$idx1++;
		
        $ft->define("editlist_pages", "editlist_pages.tpl");
        $ft->define_dynamic("row", "editlist_pages");
			
		// naprzemienne kolorowanie wierszy tabeli
		$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
		
		$ft->parse('ROWS', ".row");
		
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
	
	if($exit){
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

    $db->query($query);
    $db->next_record();

    return $db->f('config_value');
}

function set_config($name, $value) {

    $db = new DB_SQL;

    $query = sprintf("
        UPDATE
            %1\$s
        SET
            config_value = '%2\$s'
        WHERE
            config_name = '%3\$s'",
          
        TABLE_CONFIG,
        $value,
        $name
    );

    $db -> query($query);

    return true;
}


function check_mail($email) {
    return eregi("^([a-z0-9_]|\\-|\\.)+@(((([a-z0-9_]|\\-)+\\.)+[a-z]{2,4})|localhost)$", $email);
}

// stronnicowanie 
function main_pagination($url, $q, $p, $published, $table, $category_pagination, $cat_count=false, $search_count=false) {
    
    global 
        $db, 
        $mainposts_per_page, 
        $page_string,
        $id;
    
    //$id = is_null($id) ? $_GET['id'] : $id;

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
            $id
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
        
        // $mainposts_per_page = get_config('config_value');
	}
    
	$mainposts_per_page = empty($mainposts_per_page) ? 10 : $mainposts_per_page;
	
	if($cat_count == true) {
	    
        $query = sprintf("
            SELECT 
                COUNT(*) AS id 
            FROM 
                %1\$s a 
            LEFT JOIN 
                %2\$s b 
            ON 
                a.id = b.news_id 
            WHERE 
                b.category_id = %3\$d 
            AND
                published = 1
            ORDER BY date", 
	
            TABLE_MAIN, 
            TABLE_ASSIGN2CAT, 
            $q
        );
	} elseif($search_count == true) {
	    
	    $search_word = trim($_REQUEST['search_word']);
	    
	    $query = sprintf("
            SELECT 
                COUNT(*) AS id 
            FROM 
                %1\$s a 
            LEFT JOIN 
                %2\$s b 
            ON 
                a.id = b.news_id 
            WHERE 
                published = 1 
            AND 
                a.text LIKE '%%" . $search_word . "%%' 
            OR 
                a.title LIKE '%%" . $search_word . "%%' 
            ORDER BY date", 
	
            TABLE_MAIN, 
            TABLE_ASSIGN2CAT, 
            $q
        );
	} else {
	    $query = sprintf("
            SELECT 
                COUNT(*) AS id 
            FROM 
                %1\$s 
                %2\$s %3\$s 
            ORDER BY date", 
	
            $table, 
            $q, 
            $published
        );
	}
    
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
	    // Obliczanie strony, na kt�rej obecnie jestesmy
		$on_page = floor($start / $mainposts_per_page) + 1;
	} else {
		$total_pages = 0;
		$on_page = 0;
	}
	
	//if($total_pages == 1) echo '';
	
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
	    $page_string .= '&nbsp;&nbsp;<a href="' . $url . ($on_page * $mainposts_per_page) . '">' . "<b>nast�pna</b> " . '</a>';
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
    
    preg_match_all("#<(ul|li|ol)[^>]*?>.*?</(\\1)>#si", $text, $pregResultArr);
    
    $pregResultArrSize = sizeOf($pregResultArr[0]);
    
    for($i=0; $i<$pregResultArrSize; $i++){
        $tempArr[$i] = $hash.'_'.$i;
    }
    
    $text = str_replace($pregResultArr[0], $tempArr, $text);
    
    $break  == 1 ? $text = str_nl2br($text) : '';
    $tab    == 1 ? $text = str_replace("\t", str_repeat('&nbsp;', $tab_long), $text) : '';
    $text   = str_replace($tempArr, $pregResultArr[0], $text);
    
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

function highlighter($text, $code_start, $code_end) {
        
    $matches = array();
    $match_count = preg_match_all('#\[php\](.*?)\[\/php\]#si', $text, $matches);
    for ($i=0; $i<$match_count; $i++) {

        $before = $matches[1][$i];
        $after  = str_replace("<br />", "\n", trim($matches[1][$i]));
        $str_to_match   = "[php]" . $before . "[/php]";
        $replacement    = $code_start;
            
        $after  = str_replace(array('&lt;', '&gt;', '&amp;'), array('<', '>', '&'), $after);
        $added  = FALSE;
            
        if(preg_match('/^<\?.*?\?>$/si', $after) <= 0) {
            $after = "<?php $after ?>";
            $added = TRUE;
        }
            
        if(strcmp('4.2.0', phpversion()) > 0) {
            ob_start();
            highlight_string($after);
            $after = ob_get_contents();
            ob_end_clean();
        } else {
                $after = highlight_string($after, TRUE);
        }
            
        if($added == TRUE) {
            $after  = str_replace(array('<font color="#0000BB">&lt;?php ', '<font color="#0000BB">?&gt;</font>'), array('<font color="#0000BB">', ''), $after);
        }
            
        $after  = preg_replace('/<font color="(.*?)">/si', '<span style="color: \\1;">', $after);
        $after  = str_replace(array('</font>', '\n', '<code>', '</code>'), array('</span>', '', '', ''), $after);
        $replacement .= $after;
        $replacement .= $code_end;
        $text = str_replace($str_to_match, $replacement, $text);
    }
        
    $text  = str_replace(array('[php]', '[/php]'), array($code_start, $code_end), $text);
    
    return $text;
}


function get_comments_link($comments_allow, $comments, $id) {
    
    global 
        $ft, 
        $rewrite;
    
    if(($comments_allow) == 0 ) {
        $ft->assign(array(
            'COMMENTS_ALLOW'    =>false, 
            'COMMENTS'          =>''
        ));
    } else {
        if($comments == 0) {
            $comments_link = (bool)$rewrite ? '1,' . $id . ',3,item.html' : 'index.php?p=3&amp;id=' . $id . '';
            $ft->assign(array(
                'COMMENTS_LINK' =>$comments_link, 
                'COMMENTS_ALLOW'=>true, 
                'COMMENTS'      =>''
            ));
	    } else {
            $comments_link = (bool)$rewrite ? '1,' . $id . ',2,item.html' : 'index.php?p=2&amp;id=' . $id . '';
            $ft->assign(array(
                'COMMENTS_LINK' =>$comments_link, 
                'COMMENTS_ALLOW'=>true, 
                'COMMENTS'      =>$comments
            ));
	    }
    }
}


function get_image_status($image, $id) {
    
    global 
        $ft, 
        $max_photo_width, 
        $rewrite;
    
    if(empty($image)) {
        // IFDEF: IMAGE_EXIST zwraca pusta wartosc, przechodzimy
        // do warunku ELSE
        $ft->assign(array(
            'IMAGE'         =>'', 
            'IMAGE_EXIST'   =>false, 
            'IMAGE_NAME'    =>false
        ));
    } else {
        
        $img_path = get_root() . '/photos/' . $image;
        
        if(is_file($img_path)) {
            
            list($width, $height) = getimagesize($img_path);
            
            $photo_link = (bool)$rewrite ? 'photo?id=' . $id . '' : 'photo.php?id=' . $id . '';
            
            // wysoko��, szeroko�� obrazka
            $ft->assign(array(
                'WIDTH'         =>$width,
                'HEIGHT'        =>$height,
                'PHOTO_LINK'    =>$photo_link
            ));
            
            if($width > $max_photo_width) {
                
                $ft->assign(array(
                    'UID'           =>$id,
                    'IMAGE_NAME'    =>''
                ));
            } else {
                $ft->assign('IMAGE_NAME', $image);
            }
            
            $ft->assign('IMAGE_EXIST', true);
        } else {
            
            $ft->assign(array(
                'IMAGE_EXIST'   =>false, 
                'IMAGE_NAME'    =>false
            ));
        }
    }
}

function replace_amp($s) {
    return str_replace('&', '&amp;', $s);
}


// funkcja pobierajaca rekurencyjnie kategorie::edycja newsa
function get_editnews_assignedcat($c_id, $level) {
	
	global 
        $ft, 
        $category, 
        $sql;

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
		
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                category_id = '%2\$d' 
            AND 
                news_id = '%3\$d'", 
		
            TABLE_ASSIGN2CAT, 
            $cat_id, 
            $_GET['id']
        );
        
        $sql->query($query);
        $sql->next_record();
	
		$ft->assign(array(
            'C_ID'          =>$cat_id,
            'PAD'           =>'style="padding-left:' . 8*$level . 'px;" ', 
            'C_NAME'        =>$cat_name, 
            'CURRENT_CAT'   =>$cat_id == ($assigned = $sql->f("category_id")) ? 'checked="checked"' : ''
        ));
        
        $ft->parse('CAT_ROW', ".cat_row");
		
		get_editnews_assignedcat($cat_id, $level+2);
	}
}


function get_addcategory_assignedcat($page_id, $level, $current_id = 0, $pageid_prefix = '') {
	
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
            'C_ID'		=>$pageid_prefix . $cat_id, 
            'PAD'       =>'style="padding-left:' . 8*$level . 'px;" ', 
            'C_NAME'	=>$cat_name,
            'CURRENT'   =>$cat_id == $current_id ? 'selected="selected"' : ''
        ));

        $ft->parse('CAT_ROW', ".cat_row");
		
		get_addcategory_assignedcat($cat_id, $level+2, $current_id, $pageid_prefix);
	}
}


function list_assigned_categories($id) {
    
    global 
        $ft, 
        $rewrite;
    
    $query = sprintf("
        SELECT 
            a.*, b.* 
        FROM 
            %1\$s a 
        LEFT JOIN 
            %2\$s b 
        ON 
            a.category_id = b.category_id 
        WHERE 
            a.news_id = '%3\$d'", 
	    
        TABLE_ASSIGN2CAT, 
        TABLE_CATEGORY, 
        $id
    );
	    
    $sql = new DB_SQL;
    $sql->query($query);
    
    while($sql->next_record()) {
        
        $cname = replace_amp($sql->f('category_name'));
        $cid   = $sql->f('category_id');
        
        $category_link  = (bool)$rewrite ? sprintf('1,%s,4,item.html', $cid) : 'index.php?p=4&amp;id=' . $cid;
        
        $ft->assign(array(
            'CATEGORY_NAME' =>$cname, 
            'CATEGORY_LINK' =>$category_link
        ));
        
        $ft->parse('CAT_ROW', ".cat_row");
    }
    
    // CAT_ROW musi byc czyste
    $ft->clear_parse('CAT_ROW');
}

?>