<?php

if(is_numeric($_GET['id'])) {
	
	// inicjowanie funkcji stronnicujacej wpisy
	main_pagination('category.' . $_GET['id'] . '.', 'c_id=' . $_GET['id'] . ' AND ', 'mainposts_per_page', 'AND published = \'Y\'', 'db_table');
	
	$query = "	SELECT 
					a.*, b.*, c.comments_id, count(c.id) AS comments 
				FROM 
					$mysql_data[db_table] a, $mysql_data[db_table_category] b 
				LEFT JOIN 
					$mysql_data[db_table_comments] c 
				ON 
					a.id = c.comments_id
				WHERE 
					a.c_id='$_GET[id]' 
				AND 
					b.category_id='$_GET[id]' 
				AND 
					published = 'Y' 
				GROUP BY 
					a.date 
				DESC LIMIT 
					$start, $mainposts_per_page";
	
	$db->query($query);
	
	if($db->num_rows() > 0) {

		while($db->next_record()) {
	
			$date 			= $db->f("date");
			$title 			= $db->f("title");
			$text 			= $db->f("text");
			$author 		= $db->f("author");
			$id 			= $db->f("id");
			$c_id			= $db->f("c_id");
			$image			= $db->f("image");
			$comments_allow = $db->f("comments_allow");
	
			$c_name			= $db->f("category_name");
			$c_id			= $db->f("category_id");

			$comments		= $db->f("comments");
	
			// konwersja daty na bardziej ludzki format
			$date			= coreDateConvert($date);
			
			$ft->assign(array(	'DATE'				=>$date,
								'NEWS_TITLE'		=>$title,
								'NEWS_TEXT'			=>$text,
								'NEWS_AUTHOR'		=>$author,
								'NEWS_ID'			=>$id,
								'CATEGORY_NAME'		=>$c_name,
								'NEWS_CATEGORY'		=>$c_id));
						
			if($page_string !== "") {
		
				$ft->assign('STRING', "<b>Id� do strony:</b> " . $page_string);
			} else {
		
				$ft->assign('STRING', $page_string);
			}
					

			if(($comments_allow) == 0 ) {
			
				$ft->assign('COMMENTS_ALLOW', "<br />");
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
				
				if(is_file('photos/' . $image)) {
					list($width, $height) = getimagesize("photos/" . $image);
				
					// wysoko��, szeroko�� obrazka
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
			}	
				
			$ft->parse('ROWS', ".rows");
		}
	} else {
		
		// Obs�uga b��du, kiedy �adana jest kategoria, jakiej nie ma w bazie danych
		$ft->assign(array(	'QUERY_FAILED'	=>"W bazie danych nie ma wpis�w z kategorii o �adanym id",
							'STRING'		=>""));
							
		$ft->parse('ROWS', ".query_failed");
	}					
} else {
	
	// Obs�uga b��du, kiedy u�ytkownik pr�buje kombinowa� ze zmiennymi przechwytywanymi przez $_GET
	$ft->assign(array(	'QUERY_FAILED'	=>"Szukasz czego�",
						'STRING'	=>""));
						
	$ft->parse('ROWS', ".query_failed");
}
?>
