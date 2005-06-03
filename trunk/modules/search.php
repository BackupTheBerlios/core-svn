<?php

$page_string = empty($page_string) ? '' : $page_string;

// inicjowanie zmiennej przechowuj±cej szukan± frazê
$search_word = (isset($_POST['search_word']) ) ? $_POST['search_word'] : $_GET['search_word'];
$search_word = trim($search_word);

$search_link = isset($rewrite) && $rewrite == 1 ? 'search.' . $search_word . '.' : 'index.php?p=search&search_word=' . $search_word . '&amp;start=';

// inicjowanie funkcji stronnicuj±cej wpisy
main_pagination($search_link, '', 'mainposts_per_page', 'WHERE published = \'1\' AND text LIKE \'%' . $search_word . '%\' OR title LIKE \'%' . $search_word . '%\'', TABLE_MAIN, false);

if(!empty($search_word)) {
	
    $query = sprintf("
        SELECT 
            a.*,
            UNIX_TIMESTAMP(a.date) AS date,
            b.*,
            c.comments_id,
            count(c.id) AS comments 
		FROM 
			%1\$s a 
		LEFT JOIN 
			%2\$s b 
		ON 
            b.category_id = a.c_id 
        LEFT JOIN 
            %3\$s c 
        ON 
            a.id = c.comments_id
        WHERE 
            published = '1' 
        AND 
            a.text LIKE '%%" . $search_word . "%%' 
        OR 
            a.title LIKE '%%" . $search_word . "%%' 
        GROUP BY 
            a.date 
        DESC LIMIT 
            $start, $mainposts_per_page", 
    
        TABLE_MAIN, 
        TABLE_CATEGORY, 
        TABLE_COMMENTS
    );
	
	$db->query($query);
	
	class search {
		
		var $common = array();
		
		function highlight($words, $haystack){
	
			if (trim($words) != '') {
		
				$term = @explode(' ', trim($words));
				$count = count($term);
				for ($i = 0; $i < $count; $i++) {
			
					if (strlen($term[$i]) >= 2 && !$this->grep_values($term[$i], $this->common)) {
				
						$terms[] = $term[$i];
					} else {
						continue;
					} 
				}
		
				if (isset($terms)) {
			
					foreach ($terms as $key => $value) {
					
						$pattern[] = "/" . preg_quote($value, "/") . "/i";
						$replacement[] = '<span class="search">' . $value . '</span>';
					} 
            
					ksort($replacement);
					ksort($pattern);
					$haystack = preg_replace($pattern, $replacement, $haystack);
            
					return stripslashes($haystack);
				} else {
				
					return stripslashes($haystack);
				}
			} else {
		
				return stripslashes($haystack);
			}
		} 


		function grep_values($pattern, $array) {
	
			$newarray = Array();
			while (list($key, $val) = each($newarray)) {
			
				$pattern = urlencode($pattern);
				if (preg_match("/" . $pattern . "/i", $val)) {
			
					$newarray[$key] = $val;
				}
			}
	
			return $newarray;
		}	
	}
	
	$search = new search();


	if($db->num_rows() !== 0) {
	
		while($db->next_record()) {
			
			$c_id		= $db->f("c_id");
			$c_name		= $db->f("category_name");
			$c_id		= $db->f("category_id");

			$comments 	= $db->f("comments");
	
			// Zmiana '&' na ampersand - xhtml
			$c_name 	= str_replace('&', '&amp;', $c_name);
			
			$date 			= date($date_format, $db->f("date"));
			$title 			= $db->f("title");
			$text 			= $db->f("text");
			$author 		= $db->f("author");
			$id 			= $db->f("id");
			$image			= $db->f("image");
			$comments_allow = $db->f("comments_allow");
			
			// usuwamy <a />
			$text = preg_replace('/(?is)(<\/?(?:a)(?:|\s.*?)>)/', '', $text);
			
			$perma_link    = isset($rewrite) && $rewrite == 1 ? '1,' . $id . ',1,item.html' : 'index.php?p=1&amp;id=' . $id . '';
			$category_link = isset($rewrite) && $rewrite == 1 ? '1,' . $c_id . ',4,item.html' : 'index.php?p=4&amp;id=' . $c_id . '';
			
			$text   = highlighter($text, '<code>', '</code>');
			
			$ft->assign(array(
                'DATE'				=>$date,
                'NEWS_TITLE'		=>$search->highlight($search_word, $title),
                'NEWS_TEXT'			=>$search->highlight($search_word, $text),
                'NEWS_AUTHOR'		=>$author,
                'NEWS_ID'			=>$id,
                'CATEGORY_NAME'		=>$c_name,
                'NEWS_CATEGORY'		=>$c_id,
                'STRING'			=>$page_string, 
                'PERMA_LINK'        =>$perma_link,
                'CATEGORY_LINK'     =>$category_link
            ));
								
			get_comments_link($comments_allow, $comments, $id);
			get_image_status($image, $id);
            
			// definiujemy blok dynamiczny szablonu
			$ft->define_dynamic("note_row", "rows");
			
			$ft->assign('RETURN', '');
			$ft->parse('MAIN', ".note_row");
		} 
	} else {
	
		$ft->assign(array(
            'QUERY_FAILED'  =>$i18n['search'][0] . '<span class="search">' . $_POST['search_word'] . '</span>.',
            'STRING'        =>$page_string
        ));
						
		$ft->parse('MAIN',".query_failed");
	}
} else {
	
	$ft->assign(array(
        'QUERY_FAILED'  =>$i18n['search'][1],
        'STRING'        =>''
    ));
						
	$ft->parse('MAIN',".query_failed");	
}

?>