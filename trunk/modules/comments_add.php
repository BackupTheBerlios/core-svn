<?php

// dekalracja zmiennej $page_string
$page_string	= empty($page_string) ? '' : $page_string;
$comment_author	= empty($_COOKIE['devlog_comment_user']) ? '' : $_COOKIE['devlog_comment_user'];

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "add":

		$monit = empty($monit) ? '' : $monit;
	
		// Obs³uga formularza, jesli go zatwierdzono
		if(!eregi("^([^0-9]+){2,}$", $_POST['author'])) {
			
			$monit = "Proszê podaæ swoje imiê, ewentualnie nick.<br />";
		}
	
		if($_POST['email'] != '') {
		
			// Sprawdzenie poprawnosci adresu e-mail
			if(!eregi("^[^@\s]+@([-a-z0-9]+\.)+([a-z]{2,})$", $_POST['email'])) {
			
				$monit .= "Proszê podaæ poprawny adres e-mail.<br />";
			}
		}
	
		// Je¿eli dane spe³niaja wszystkie kryteria dodanie nowego komentarza
		if(empty($monit)) {
		
			$tmp=time();
			$date = date("Y-m-d H:i:s",$tmp);
		
			$text = nl2br(addslashes($_POST['text']));
		
			$text = strip_tags($text, '<br>');
		
			// [b] i [/b] dla tekstu pogrubionego.
			$text = preg_replace('/\[b\]([^\"]+)\[\/b\]/','<b>\\1</b>', $text);
		
			// [i] i [/i] dla tekstu pochylonego.
			$text = preg_replace('/\[i\]([^\"]+)\[\/i\]/','<i>\\1</i>', $text);
		
			// [u] i [/u] dla tekstu podkre¶lonego.
			$text = preg_replace('/\[u\]([^\"]+)\[\/u\]/','<u>\\1</u>', $text);
		
			// [abbr] i [/abbr] dla akronimów.
			$text = preg_replace('/\[abbr=([^\"]+)\]([^\"]+)\[\/abbr\]/','<abbr title="\\1">\\2</abbr>', $text);
		
			// [link] i [/link] dla odsy³aczy.
			$text = preg_replace('/\[link=([^\"]+)\]([^\"]+)\[\/link\]/','<a href="\\1" target="_blank">\\2</a>', $text);
		
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
		
			$cl = time()+3600*8760;
		
			$cn 	= "devlog_comment_user";
			$value 	= $author;
		
			@setcookie($cn, $value, $cl);
		
			// egzemplarz klasy ³aduj¹cej komentarz do bazy danych
			$query = "	INSERT INTO 
							$mysql_data[db_table_comments] 
						VALUES 
							('','$date','$comments_id','$author','$author_ip','$email','$text')";
		
			$db->query($query);
			$db->next_record();

			// przydzielamy zmienne i parsujemy szablon
			$ft->assign(array(
							'NEWS_ID'		=>$_POST['id'],
							'STRING'		=>$page_string,
							'CONFIRMATION'	=>"Komentarz zosta³ dodany."
			));
					
			$ft->parse('ROWS',".comments_submit");
		} else {
		
			$monit .= "<br /><a href=\"javascript:history.back(-1);\">powrót</a>";
			
			// przydzielanie zmiennych i parsowanie szablonu, je¶li próba dodania komentarza siê nie powiedzie
			$ft->assign(array(
							'NEWS_ID'		=>$_POST['id'],
							'STRING'		=>$page_string,
							'CONFIRMATION'	=>"<span>" . $monit . "</span>"
			));
					
			$ft->parse('ROWS',".comments_submit");
		}
		break;
	
	default:
	
		// Je¶li wpis jest cytowany::request
		if(isset($_GET['c'])) {
		
			// Pobieramy tekst cytowanego wpisu::db
			$query = "	SELECT 
							text 
						FROM 
							$mysql_data[db_table_comments] 
						WHERE 
							id = '$_GET[c]' 
						LIMIT 1";
		
			$db->query($query);
			$db->next_record();
		
			// przypisanie zmiennych	
			$cite		= $db->f("text");
			$author		= $db->f("author");
		
			$match_count = preg_match_all("#\<div class=\"quote\"\>(.*?)\</div\>#si", $cite, $matches);
		
			for ($i = 0; $i < $match_count; $i++) {
			
				$cite = str_replace("<div class=\"quote\">", "[quote]", $cite);
				$cite = str_replace("</div>", "[/quote]", $cite);
			}
		
			// Pobieramy id i tytu³ wpisu jakiego dotyczy komentarz::db
			$query = "	SELECT 
							id, title 
						FROM 
							$mysql_data[db_table] 
						WHERE 
							id = '$_GET[id]' 
						LIMIT 1";
	
			$db->query($query);
			$db->next_record();
			
			// przypisanie zmiennych
			$title 		= $db->f("title");
			$id 		= $db->f("id");
		
			// przypisanie tablicy szablonów::ft
			$ft->assign(array(
							'NEWS_TITLE'	=>$title,
							'NEWS_ID'		=>$id,
							'COMMENT_AUTHOR'=>$comment_author,
							'QUOTE'			=>"[quote]" . strip_tags($cite) . "[/quote]",
							'STRING'		=>$page_string
			));
		} else {
		
			$query = "	SELECT 
							id, title 
						FROM 
							$mysql_data[db_table] 
						WHERE 
							id = '$_GET[id]' 
						LIMIT 1";
	
			$db->query($query);
			$db->next_record();
	
			// przypisanie zmiennych
			$title 		= $db->f("title");
			$id 		= $db->f("id");
		
			// przypisanie tablicy szablonów::ft
			$ft->assign(array(
							'NEWS_TITLE'	=>$title,
							'NEWS_ID'		=>$id,
							'QUOTE'			=>'',
							'COMMENT_AUTHOR'=>$comment_author,
							'STRING'		=>$page_string
			));
		}
	
		// parsowanie szablonu::ft
		$ft->parse('ROWS',".comments_form");
		break;
}
?>