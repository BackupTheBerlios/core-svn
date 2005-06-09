<?php

$page_string = empty($page_string) ? '' : $page_string;

// inicjowanie zmiennej przechowującej szukaną frazę
$search_word = trim($_REQUEST['search_word']);

if((bool)$rewrite) {
    $search_link = sprintf('search.%s.', $search_word);
} else {
    $search_link = sprintf('index.php?p=8&search_word=%s&amp;start=', $search_word);
}

// inicjowanie funkcji stronnicującej wpisy
main_pagination($search_link, '', 'mainposts_per_page', '', TABLE_MAIN, false, false, true);

if(!empty($search_word)) {
	
    $query = sprintf("
        SELECT 
            a.*,
            UNIX_TIMESTAMP(a.date) AS date,
            c.comments_id,
            count(c.id) AS comments 
        FROM 
            %1\$s a 
        LEFT JOIN 
            %3\$s c 
        ON 
            a.id = c.comments_id
        WHERE 
            published = 1 
        AND 
            a.text LIKE '%%" . $search_word . "%%' 
        OR 
            a.title LIKE '%%" . $search_word . "%%' 
        GROUP BY 
            a.date 
        DESC 
        LIMIT %4\$d, %5\$d",
    
        TABLE_MAIN, 
        TABLE_CATEGORY, 
        TABLE_COMMENTS,
        $start,
        $mainposts_per_page
    );
	
	$db->query($query);
	
	class search {
		
		var $common = array();
		
		function highlight($words, $haystack){
	
			if (trim($words)) {
		
				$term = @explode(' ', trim($words));
				$count = count($term);
				for ($i = 0; $i < $count; $i++) {
			
					if (strlen($term[$i]) >= 2 && !$this->grep_values($term[$i], $this->common)) {
				
						$terms[] = $term[$i];
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
            foreach ($newarray as $key => $val) {
			
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
	
	    $ft->define_dynamic("cat_row", "rows");
	    
		while($db->next_record()) {
			
			$date 			= date($date_format, $db->f("date"));
			$title 			= $db->f("title");
			$text 			= $db->f("text");
			$author 		= $db->f("author");
			$id 			= $db->f("id");
			$image			= $db->f("image");
			$comments_allow = $db->f("comments_allow");
			
			// usuwamy <a />
			$text = preg_replace('/(?is)(<\/?(?:a)(?:|\s.*?)>)/', '', $text);
			
			$comments 	= $db->f("comments");
			
            list_assigned_categories($id);
            $perma_link = (bool)$rewrite ? sprintf('1,%s,1,item.html', $id) : 'index.php?p=1&amp;id=' . $id;
			
			$text   = highlighter($text, '<code>', '</code>');
			
			$ft->assign(array(
                'DATE'				=>$date,
                'NEWS_TITLE'		=>$search->highlight($search_word, $title),
                'NEWS_TEXT'			=>$search->highlight($search_word, $text),
                'NEWS_AUTHOR'		=>$author,
                'NEWS_ID'			=>$id,
                'STRING'			=>$page_string, 
                'PERMA_LINK'        =>$perma_link
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
            'QUERY_FAILED'  =>sprintf('%s <span class="search">%s</span>.', $i18n['search'][0], $_POST['search_word']),
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
