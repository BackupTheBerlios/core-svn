<?php

// automatyczne sprawdzanie stanu magic_quotes
// i w zaleznosci od tego wstawianie addslashes, badz nie.
if(!get_magic_quotes_gpc()) {
    if(is_array($_GET)) {
        while(list($k, $v) = each($_GET)) {
            if(is_array($_GET[$k])) {
                while(list($k2, $v2) = each($_GET[$k])) {
                    $_GET[$k][$k2] = addslashes($v2);
                }
                @reset($_GET[$k]);
            } else {
                $_GET[$k] = addslashes($v);
            }
        }
        @reset($_GET);
    }
    
    if(is_array($_POST)) {
        while(list($k, $v) = each($_POST)) {
            if(is_array($_POST[$k])) {
                while(list($k2, $v2) = each($_POST[$k])) {
                    $_POST[$k][$k2] = addslashes($v2);
                }
                @reset($_POST[$k]);
            } else {
                $_POST[$k] = addslashes($v);
            }
        }
        @reset($_POST);
    }
    
    if(is_array($_COOKIE)) {
        while(list($k, $v) = each($_COOKIE)) {
            if(is_array($_COOKIE[$k])) {
                while(list($k2, $v2) = each($_COOKIE[$k])) {
                    $_COOKIE[$k][$k2] = addslashes($v2);
                }
                @reset($_COOKIE[$k]);
            } else {
                $_COOKIE[$k] = addslashes($v);
            }
        }
        @reset($_COOKIE);
    }
}


function replace_amp($s) {
    return str_replace('&', '&amp;', $s);
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
	    // Obliczanie strony, na której obecnie jestesmy
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
	    $page_string .= '&nbsp;&nbsp;<a href="' . $url . ($on_page * $mainposts_per_page) . '">' . "<b>nastêpna</b> " . '</a>';
	}
	
	$ret['mainposts_per_page'] = $mainposts_per_page;
	$ret['page_string']        = $page_string;
	
	return $ret;
}


function check_mail($email) {
    return eregi("^([a-z0-9_]|\\-|\\.)+@(((([a-z0-9_]|\\-)+\\.)+[a-z]{2,4})|localhost)$", $email);
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
function get_httproot($with_slash = true) {

  $path = sprintf('%s/%s', $_SERVER['HTTP_HOST'], substr(dirname($_SERVER['REQUEST_URI']), 1));
  if ($with_slash) {

    $path .= '/';
  }

  return $path;
}


function v_array($array, $exit = 0) { 
	
	printf('<pre>%s</pre>', print_r($array, 1));
	
	if($exit){
		exit;
	}
}


function br2nl($text, $nl = "\r\n") {
    return str_replace(array('<br />', '<br>', '<br/>'), $nl, $text);
}


function str_nl2br($s) {
	return str_replace(array("\r\n", "\r", "\n"), '<br />', $s);
}

?>
