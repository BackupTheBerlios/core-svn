<?php

if(is_numeric($_GET['id'])) {

	$query = "	SELECT * FROM 
					$mysql_data[db_table] 
				WHERE 
					id = '$_GET[id]' 
				LIMIT 1";
	
	$db->query($query);

	if($db->num_rows() !== 0) {
	
		$page_string = empty($page_string) ? '' : $page_string;
		
		// Wy�wietlamy tuty�, kt�rego dotyczy� b�dzie komentarz
		while($db->next_record()) {
	
			$id 			= $db->f("id");
			$title 			= $db->f("title");
			$comments_id 	= $db->f("id");

			$ft->assign(array(
							'NEWS_TITLE'	=>$title,
							'NEWS_ID'		=>$id,
							'COMMENTS_ID'	=>$id,
							'STRING'		=>$page_string,
							'COMMENTS_ADD'	=>"<a class=\"comments\" href=\"1," . $id . ",3,item.html\">Dodaj komentarz</a>"
			));
				
			$query = "	SELECT * FROM 
							$mysql_data[db_table_comments] 
						WHERE 
							comments_id = '$_GET[id]' 
						ORDER BY 
							date";
			
			$db->query($query);
	
			// Wy�wietlamy komentarze do konkretnego wpisu
			while($db->next_record()) {
	
				$date 			= $db->f("date");
				$text 			= $db->f("text");
				$author 		= $db->f("author");
				$comments_id 	= $db->f("comments_id");
				$email			= $db->f("email");
				$id 			= $db->f("id");
				
				// konwersja daty na bardziej ludzki format
				$date			= coreDateConvert($date);
				
				// przeszukanie text w poszukiwaniu ci�g�w http, mail, ftp
				// zamiana ich na format link�w
				$text			= coreMakeClickable($text);
				
				// [quote] i [/quote] dla tekstu cytowanego.
				//$text = preg_replace('/\[quote\].*?\[\/(quote)\]/','<div class="quote">\\1</div>', $text);
				
				$search = array("'\[quote\]'si",
								"'\[\/quote\]'si");
								
				$replace= array("<div class=\"quote\">",
								"</div>");
								
				$text = preg_replace($search, $replace, $text);
		
				$ft->assign(array(
								'DATE'				=>$date,
								'COMMENTS_TEXT'		=>$text,
								'COMMENTS_AUTHOR'	=>$author,
								'COMMENTS_ID'		=>$comments_id,
								'AUTHOR_EMAIL'		=>$email,
								'STRING'			=>$page_string,
								'ID'				=>$id,
								'COMMENTS_QUOTE'	=>"<a class=\"comments\" href=\"1," . $comments_id . ",3," . $id . ",1,quote.html" . "\">odpowiedz cytuj�c</a>"
				));
					
				$ft->parse('ROWS', ".comments_rows");
			}	
		}
	} else {
	
		// Obs�uga b��du, kiedy u�ytkownik pr�buje kombinowa� ze zmiennymi przechwytywanymi przez $_GET
		$ft->assign(array(	'QUERY_FAILED'	=>"W bazie danych nie ma wpisu o ��danym id",
							'STRING'		=>""));
	
		$ft->parse('ROWS',".query_failed");
	} 
} else {
	
	// Obs�uga b��du, kiedy u�ytkownik pr�buje kombinowa� ze zmiennymi przechwytywanymi przez $_GET
	$ft->assign(array(	'QUERY_FAILED'	=>"Szukasz czego�?",
						'STRING'		=>""));
						
	$ft->parse('ROWS', ".query_failed");
}
?>