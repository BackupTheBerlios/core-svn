<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new DB_SQL;

switch ($action)
{
	case "show":// wy¶wietlanie wpisu pobranego do modyfikacji
		$query = "	SELECT * 
					FROM 
						TABLE_COMMENTS 
					WHERE 
						id = '$_GET[id]'";
		
		$db->query($query);
		$db->next_record();
		
		$date 		= $db->f("date");
		$title 		= $db->f("title");
		$text 		= $db->f("text");
		$author		= $db->f("author");
		$published	= $db->f("published");

		
		$date	= substr($date, 0, 16);
		$dat1	= explode(" ", $date);
		$dat	= explode("-", $dat1[0]);
		$date	= "$dat[2]-$dat[1]-$dat[0] $dat1[1]";
		
		$text = str_replace("<br />", "\r\n", $text);
		$text = preg_replace("/(\r\n)+/", "\\1\\1", $text);

		
		$ft->assign(array(	'AUTHOR'	=>$author,
							'DATE' 		=>$date,
							'ID'		=>$_GET['id'],
							'TEXT'		=>$text));

		$ft->define('form_commentsedit', "form_commentsedit.tpl");
		$ft->parse('ROWS',	".form_commentsedit");
		break;

	case "edit":// edycja wybranego wpisu
		$text		= nl2br($_POST['text']);
		$author		= $_POST['author'];
		
		$query = "	UPDATE 
						TABLE_COMMENTS 
					SET 
						author = '$author', 
						text = '$text' 
					WHERE 
						id = '$_GET[id]'";
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Komentarz zosta³ zmodyfikowany.");

		$ft->parse('ROWS',	".result_note");
		break;

	case "delete":// usuwanie wybranego wpisu
		$query = "	DELETE 
					FROM 
						TABLE_COMMENTS 
					WHERE 
						id = '$_GET[id]'";
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Komentarz zosta³ usuniêty.");

		$ft->parse('ROWS', ".result_note");
		break;
		
	default:
		$query = " 	SELECT 
						n.id, n.title, n.date, 
						count(DISTINCT c.id) 
					AS 
						comments 
					FROM 
						TABLE_MAIN n 
					LEFT JOIN 
						TABLE_COMMENTS c 
					ON 
						n.id = c.comments_id 
					GROUP BY 
						n.id 
					HAVING 
						count(c.id) > 0 	
					ORDER BY 
						comments 
					DESC 
					LIMIT 
						20";
		
		$db->query($query);
		
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
			// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
			while($db->next_record()) {
		
				$id 		= $db->f("id");
				$title 		= $db->f("title");
				$date 		= $db->f("date");
				$comments 	= $db->f("comments");
			
				$date = explode(' ', $date);
			
				$ft->assign(array(	'ID'		=>$id,
									'TITLE'		=>$title,
									'DATE'		=>$date[0],
									'COMMENTS'	=>$comments));				
			
				// deklaracja zmiennej $idx1::color switcher
				$idx1 = empty($idx1) ? '' : $idx1;
				
				$idx1++;
			
				$ft->define("editlist_mostcomments", "editlist_mostcomments.tpl");
				$ft->define_dynamic("row", "editlist_mostcomments");
				
				// naprzemienne kolorowanie wierszy
				if (($idx1%2)==1) {
				
					$ft->assign('ID_CLASS', 'mainList');
					
					$ft->parse('ROWS',	".row");

				} else {
				
					$ft->assign('ID_CLASS', 'mainListAlter');
				    
				    $ft->parse('ROWS',	".row");
				}
			}
		
			$ft->parse('ROWS', "editlist_mostcomments");
		} else {
		
			$ft->assign('CONFIRM', "W bazie danych nie ma ¿adnych wpisów");

			$ft->parse('ROWS',	".result_note");
		}
}

?>
