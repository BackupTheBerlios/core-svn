<?php

$page_string = empty($page_string) ? '' : $page_string;

// inicjowanie zmiennej przechowuj±cej szukan± frazê
$search_word = (isset($_POST['search_word']) ) ? $_POST['search_word'] : $_GET['search_word'];
$search_word = trim($search_word);

// inicjowanie funkcji stronnicuj±cej wpisy
main_pagination('search.' . $search_word . '.', '', 'mainposts_per_page', 'AND published = \'Y\' AND text LIKE \'%' . $search_word . '%\' OR title LIKE \'%' . $search_word . '%\'', 'db_table');

if(!empty($search_word)) {

	$data_base = new MySQL_DB;
	
	$data_base->query("	SELECT a.*, b.*, c.comments_id, count(c.id) AS comments 
						FROM $mysql_data[db_table] a 
						LEFT JOIN $mysql_data[db_table_category] b 
						ON b.category_id = a.c_id 
						LEFT JOIN $mysql_data[db_table_comments] c 
						ON a.id = c.comments_id
						WHERE published = 'Y' 
						AND a.text LIKE '%" . $search_word . "%' 
						OR a.title LIKE '%" . $search_word . "%' 
						GROUP BY a.date 
						DESC LIMIT $start, $mainposts_per_page");
	
	function highlight($words, $haystack){
	
		global $common;
	
		if (trim($words) != '') {
		
			$term = @explode(' ', trim($words));
			$count = count($term);
			for ($i = 0; $i < $count; $i++) {
			
				if (strlen($term[$i]) >= 2 && !grep_values($term[$i], $common)) {
				
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


	function common_words($words) {
	
		global $common;
	
		$word	= @explode(' ', $words);
		
		$term	= array();
		foreach($word as $value) {
		
			if (grep_values($value, $common)) {
			
				$term[] = $value;
			}
		}
	
		return $term;
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


	if($data_base->num_rows() !== 0) {
	
		while($data_base->next_record()) {
			
			$c_id		= $data_base->f("c_id");
			$c_name		= $data_base->f("category_name");
			$c_id		= $data_base->f("category_id");

			$comments 	= $data_base->f("comments");
	
			if($c_name === "art&design") { 
		
				$c_name = "art&amp;design";
			}
			
			$date 			= $data_base->f("date");
			$title 			= $data_base->f("title");
			$text 			= $data_base->f("text");
			$author 		= $data_base->f("author");
			$id 			= $data_base->f("id");
			$image			= $data_base->f("image");
			$comments_allow = $data_base->f("comments_allow");
			
			// konwersja daty na bardziej ludzki format
			$date			= coreDateConvert($date);
			
			$text			= strip_tags($text, '<br>');
			
			$ft->assign(array(	'DATE'				=>$date,
								'NEWS_TITLE'		=>highlight($search_word, $title),
								'NEWS_TEXT'			=>highlight($search_word, $text),
								'NEWS_AUTHOR'		=>$author,
								'NEWS_ID'			=>$id,
								'CATEGORY_NAME'		=>$c_name,
								'NEWS_CATEGORY'		=>$c_id,
								'STRING'			=>$page_string));
								
			if(($comments_allow) == 0 ) {
			
				$ft->assign(array('COMMENTS_ALLOW'	=>"<br />"));
			} else {
		
				if($comments == 0) {
			
					// template prepare
					$ft->define('comments_link_empty', "comments_link_empty.tpl");
				
					$ft->parse('COMMENTS_ALLOW', "comments_link_empty");
				} else {
			
					// template prepare
					$ft->define('comments_link_alter', "comments_link_alter.tpl");
					$ft->assign('COMMENTS', $comments);
				
					$ft->parse('COMMENTS_ALLOW', "comments_link_alter");
				}
			}
	
			if(empty($image)) {

				$ft->assign(array('IMAGE' =>""));
			} else {
		
				list($width, $height) = getimagesize("photos/" . $image);
				
				// wysokoæ, szeroko¶æ obrazka
				$ft->assign(array(	'WIDTH'		=>$width,
									'HEIGHT'	=>$height));
		
				if($width > 440) {
			
					// template prepare
					$ft->define('image_alter', "image_alter.tpl");
					$ft->assign('UID', $id);
				
					$ft->parse('IMAGE', "image_alter");
				} else {
			
					// template prepare
					$ft->define('image_main', "image_main.tpl");
					$ft->assign('IMAGE_NAME', $image);

					$ft->parse('IMAGE', "image_main");
				}
			}						
				
			$ft->parse('ROWS',".rows");

		} 
	} else {
	
		$ft->assign(array(	'QUERY_FAILED'		=>"Niestety nie znaleziono ¿adnego rekordu pasuj±cego do <span class=\"search\">" . $_POST['search_word'] . "</span>.",
							'STRING'			=>$page_string));
						
		$ft->parse('ROWS',".query_failed");
	}
} else {
	
	$ft->assign(array(	'QUERY_FAILED'		=>"Nie podano frazy do wyszukania.",
						'STRING'			=>''));
						
	$ft->parse('ROWS',".query_failed");	
}
?>
