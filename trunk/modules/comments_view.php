<?php

if(is_numeric($_GET['id'])) {

	// Tworzymy egzemplarz nowej klasy dla drugiego zapytania
	$dbase = new MySQL_DB;
	$dbase->query("SELECT * FROM $mysql_data[db_table] WHERE id = '$_GET[id]' LIMIT 1");

	if($dbase->num_rows() !== 0) {
	
		$page_string = empty($page_string) ? '' : $page_string;
		
		// Wy¶wietlamy tuty³, którego dotyczyæ bêdzie komentarz
		while($dbase->next_record()) {
	
			$id 			= $dbase->f("id");
			$title 			= $dbase->f("title");
			$comments_id 	= $dbase->f("id");

			$ft->assign(array(	'NEWS_TITLE'	=>$title,
								'NEWS_ID'		=>$id,
								'COMMENTS_ID'	=>$id,
								'STRING'		=>$page_string,
								//'ROWS'			=>$rows,
								'COMMENTS_ADD'	=>"<a class=\"comments\" href=\"1," . $id . ",3,item.html\">Dodaj komentarz</a>"));
				
			// Tworzymy egzemplarz nowej klasy
			$data_base2 = new MySQL_DB;
			$data_base2->query("SELECT * 
								FROM $mysql_data[db_table_comments] 
								WHERE comments_id = '$_GET[id]' 
								ORDER BY date");
	
			// Wy¶wietlamy komentarze do konkretnego wpisu
			while($data_base2->next_record()) {
	
				$date 			= $data_base2->f("date");
				$text 			= $data_base2->f("text");
				$author 		= $data_base2->f("author");
				$comments_id 	= $data_base2->f("comments_id");
				$email			= $data_base2->f("email");
				$id 			= $data_base2->f("id");
				
				// konwersja daty na bardziej ludzki format
				$date			= coreDateConvert($date);
				
				// przeszukanie text w poszukiwaniu ci±gów http, mail, ftp
				// zamiana ich na format linków
				$text			= coreMakeClickable($text);
				
				// [quote] i [/quote] dla tekstu cytowanego.
				//$text = preg_replace('/\[quote\].*?\[\/(quote)\]/','<div class="quote">\\1</div>', $text);
				
				$search = array("'\[quote\]'si",
								"'\[\/quote\]'si");
								
				$replace= array("<div class=\"quote\">",
								"</div>");
								
				$text = preg_replace($search, $replace, $text);
		
				$ft->assign(array(	'DATE'				=>$date,
									'COMMENTS_TEXT'		=>$text,
									'COMMENTS_AUTHOR'	=>$author,
									'COMMENTS_ID'		=>$comments_id,
									'AUTHOR_EMAIL'		=>$email,
									'STRING'			=>$page_string,
									'ID'				=>$id,
									'COMMENTS_QUOTE'	=>"<a class=\"comments\" href=\"1," . $comments_id . ",3," . $id . ",1,quote.html" . "\">odpowiedz cytuj±c</a>"));
					
				$ft->parse('ROWS', ".comments_rows");
			}	
		}
	} else {
	
		// Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
		$ft->assign(array(	'QUERY_FAILED'	=>"W bazie danych nie ma wpisu o ¿±danym id",
							'STRING'		=>""));
	
		$ft->parse('ROWS',".query_failed");
	} 
} else {
	
	// Obs³uga b³êdu, kiedy u¿ytkownik próbuje kombinowaæ ze zmiennymi przechwytywanymi przez $_GET
	$ft->assign(array(	'QUERY_FAILED'	=>"Szukasz czego¶?",
						'STRING'		=>""));
						
	$ft->parse('ROWS', ".query_failed");
}
?>