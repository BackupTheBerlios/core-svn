<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new MySQL_DB;

if (empty($action)) {
	
	$query = " 	SELECT 
					n.id, n.title, n.date, 
					count(DISTINCT c.id) 
				AS 
					comments 
				FROM 
					$mysql_data[db_table] n 
				LEFT JOIN 
					$mysql_data[db_table_comments] c 
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
	if($db->num_rows() !== 0) {
	
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
		
			// naprzemienne kolorowanie wierszy tabeli
			if (($idx1%2)==1) {
			
				$ft->assign('ID_CLASS', "id=\"mainList\"");
				// parsowanie szablonów
				$ft->parse('NOTE_ROWS',	".table_mostcommentslist");
			} else {
			
				$ft->assign('ID_CLASS', "id=\"mainListAlter\"");
				$ft->parse('NOTE_ROWS',	".table_mostcommentslist");
			}
		}
	
		$ft->parse('ROWS',	".header_mostcommentslist");
	} else {
	
		$ft->assign('CONFIRM', "W bazie danych nie ma ¿adnych wpisów");

		$ft->parse('ROWS',	".result_note");
	}
}

// wy¶wietlanie wpisu pobranego do modyfikacji
if ($action == "show") {
	
	$query = "	SELECT * 
				FROM 
					$mysql_data[db_table_comments] 
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
	$date	= "$dat[2]-$dat[1]-$dat[0] <b>$dat1[1]</b>";
	
	$text = str_replace("<br />", "\r\n", $text);
	//$text = ereg_replace("(\r\n)+", "\r\n\r\n", $text);
	$text = preg_replace("/(\r\n)+/g", "\1\1", $text);

	
	$ft->assign(array(	'AUTHOR'	=>$author,
						'DATE' 		=>$date,
						'ID'		=>$_GET['id'],
						'TEXT'		=>$text));

	$ft->parse('ROWS',	".form_commentsedit");
	
}

// edycja wybranego wpisu
if ($action == "edit") {
	
	$text		= nl2br($_POST['text']);
	$author		= $_POST['author'];
	
	$query = "	UPDATE 
					$mysql_data[db_table_comments] 
				SET 
					author = '$author', 
					text = '$text' 
				WHERE 
					id = '$_GET[id]'";
	
	$db->query($query);
	$db->next_record();
	
	$ft->assign('CONFIRM', "Komentarz zosta³ zmodyfikowany.");

	$ft->parse('ROWS',	".result_note");
	
}

// usuwanie wybranego wpisu
if ($action == "delete") {
	
	$query = "	DELETE 
				FROM 
					$mysql_data[db_table_comments] 
				WHERE 
					id = '$_GET[id]'";
	
	$db->query($query);
	$db->next_record();
	
	$ft->assign('CONFIRM', "Komentarz zosta³ usuniêty.");

	$ft->parse('ROWS', ".result_note");
	
}
?>
