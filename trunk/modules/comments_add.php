<?php

// dekalracja zmiennej $page_string
$page_string = empty($page_string) ? '' : $page_string;
$comment_author = empty($_COOKIE['devlog_comment_user']) ? '' : $_COOKIE['devlog_comment_user'];

if (!isset($_GET['action'])){
	
	// Je¶li wpis jest cytowany::request
	if(isset($_GET['c'])) {
		
		// Tworzymy egzemplarz nowej klasy
		$data_base = new MySQL_DB;

		// Pobieramy tekst cytowanego wpisu::db
		$data_base->query("	SELECT text 
							FROM $mysql_data[db_table_comments] 
							WHERE id = '$_GET[c]' 
							LIMIT 1");
		
		$data_base->next_record();
		
		// przypisanie zmiennych	
		$cite		= $data_base->f("text");
		$author		= $data_base->f("author");
		
		$match_count = preg_match_all("#\<div class=\"quote\"\>(.*?)\</div\>#si", $cite, $matches);
		
		for ($i = 0; $i < $match_count; $i++) {
			
			$cite = str_replace("<div class=\"quote\">", "[quote]", $cite);
			$cite = str_replace("</div>", "[/quote]", $cite);
		}
		
		// Pobieramy id i tytu³ wpisu jakiego dotyczy komentarz::db
		$data_base->query("	SELECT id, title 
							FROM $mysql_data[db_table] 
							WHERE id = '$_GET[id]' 
							LIMIT 1");
	
		$data_base->next_record();
			
		// przypisanie zmiennych
		$title 		= $data_base->f("title");
		$id 		= $data_base->f("id");
		
		// przypisanie tablicy szablonów::ft
		$ft->assign(array(	'NEWS_TITLE'	=>$title,
							'NEWS_ID'		=>$id,
							'COMMENT_AUTHOR'=>$comment_author,
							'QUOTE'			=>"[quote]" . strip_tags($cite) . "[/quote]",
							'STRING'		=>$page_string));
	} else {
		
		// Tworzymy egzemplarz nowej klasy
		$data_base = new MySQL_DB;
		$data_base->query("	SELECT id, title 
							FROM $mysql_data[db_table] 
							WHERE id = '$_GET[id]' 
							LIMIT 1");
	
		$data_base->next_record();
	
		// przypisanie zmiennych
		$title 		= $data_base->f("title");
		$id 		= $data_base->f("id");
		
		// przypisanie tablicy szablonów::ft
		$ft->assign(array(	'NEWS_TITLE'	=>$title,
							'NEWS_ID'		=>$id,
							'QUOTE'			=>'',
							'COMMENT_AUTHOR'=>$comment_author,
							'STRING'		=>$page_string));
	}
	
	// prasowanie szablonu::ft
	$ft->parse('ROWS',".comments_form");
}

if (@$_GET['action']=="add") {

	$monit = empty($monit) ? '' : $monit;
	
	// Obs³uga formularza, jesli go zatwierdzono
	if(eregi("^[[:alnum:]]+$", $_POST['author'])) {
		$a = TRUE;
	} else {
		$a = FALSE;
		$monit = "Proszê podaæ swoje imiê, ewentualnie nick.<br />";
	}
	
	if($_POST['email'] != '') {
		
		if (eregi("^([[:alnum:]]|_|\.|-)+@([[:alnum:]]|\.|-)+(\.)([a-z]{2,4})$", $_POST['email'])) {
			
			$b  = TRUE;
		} else {
			$b = FALSE;
			$monit .= "Proszê podaæ poprawny adres e-mail.<br />";
		}
	} else {
		
		$b = TRUE;
	}
	
	$monit .= "<br /><a href=\"javascript:history.back(-1);\">powrót</a>";
	
	// Je¿eli dane spe³niaja wszystkie kryteria dodanie nowego komentarza
	if($a AND $b) {
		
		$tmp=time();
		$date = date("Y-m-d H:i:s",$tmp);
		
		$text = nl2br(addslashes($_POST['text']));
		
		$text = strip_tags($text, '<br>');
		
		// [b] i [/b] dla tekstu pogrubionego.
		$text = preg_replace('/\[b\]([^\"]+)\[\/(b)\]/','<b>\\1</\\2>', $text);
		
		// [i] i [/i] dla tekstu pochylonego.
		$text = preg_replace('/\[i\]([^\"]+)\[\/(i)\]/','<i>\\1</\\2>', $text);
		
		// [u] i [/u] dla tekstu podkre¶lonego.
		$text = preg_replace('/\[u\]([^\"]+)\[\/(u)\]/','<u>\\1</\\2>', $text);
		
		// [quote] i [/quote] dla tekstu cytowanego.
		$text = preg_replace('/\[quote\]([^\"]+)\[\/(quote)\]/','<div class="quote">\\1</div>', $text);
		
		// [abbr] i [/abbr] dla akronimów.
		$text = preg_replace('/\[abbr=([^\"]+)\]([^\"]+)\[\/(abbr)\]/','<abbr title="\\1">\\2</\\3>', $text);
		
		// [link] i [/link] dla odsy³aczy.
		$text = preg_replace('/\[link=([^\"]+)\]([^\"]+)\[\/(link)\]/','<a href="\\1" target="_blank">\\2</a>', $text);
		
		$match_count = preg_match_all("#\[quote\](.*?)\[/quote\]#si", $text, $matches);
		
		for ($i = 0; $i < $match_count; $i++) {
			
			$text = str_replace("[quote]", "<div class=\"quote\">", $text);
			$text = str_replace("[/quote]", "</div>", $text);
			
		}
		
		$id 			= $_POST['id'];
		$comments_id 	= $_POST['comments_id'];
		$author 		= $_POST['author'];
		$email			= $_POST['email'];
		$author_ip		= $_SERVER['REMOTE_ADDR'];
		
		$cl = time()+2592000;
		
		$cn 	= "devlog_comment_user";
		$value 	= $author;
		
		if(!isset($_COOKIE['devlog_comment_user'])){
			
			setcookie($cn, $value, $cl);
		}
		
		// egzemplarz klasy ³aduj¹cej komentarz do bazy danych
		$d_base = new MySQL_DB;
		$d_base->query("INSERT INTO $mysql_data[db_table_comments] VALUES ('','$date','$comments_id','$author','$author_ip','$email','$text')");
		$d_base->next_record();

		// przydzielamy zmienne i parsujemy szablon
		$ft->assign(array(	'NEWS_ID'		=>$_POST['id'],
							'STRING'		=>$page_string,
							//'NEWS_TITLE'	=>$title,
							'CONFIRMATION'	=>"Komentarz zosta³ dodany."));
					
		$ft->parse('ROWS',".comments_submit");
	} else {
		
		// przydzielanie zmiennych i parsowanie szablonu, je¶li próba dodania komentarza siê nie powiedzie
		$ft->assign(array(	'NEWS_ID'		=>$_POST['id'],
							'STRING'		=>$page_string,
							//'NEWS_TITLE'	=>$title,
							'CONFIRMATION'	=>"<span>" . $monit . "</span>"));
					
		$ft->parse('ROWS',".comments_submit");
	}
}
?>