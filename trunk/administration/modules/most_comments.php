<?php

// inicjowanie funkcji stronnicuj�cej wpisy
main_pagination('mostcomments.', '', 'editposts_per_page', '', 'db_table_comments');

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

if (empty($action)) {
	
	$data_base_config = new MySQL_DB;
	$data_base_config->query("SELECT * FROM $mysql_data[db_table_config] WHERE config_name = 'editposts_per_page'");
	$data_base_config->next_record();
		
	$editposts_per_page = $data_base_config->f("config_value");
	
	$data_base = new MySQL_DB;
	
	$data_base->query(	"SELECT n.id, n.title, n.date, count(DISTINCT c.id) AS comments 
						FROM $mysql_data[db_table] n LEFT JOIN $mysql_data[db_table_comments] c 
						ON n.id = c.comments_id 
						GROUP BY n.id 
						ORDER BY comments DESC
						LIMIT $start, $editposts_per_page");
	
	// Sprawdzamy, czy w bazie danych s� ju� jakie� wpisy
	if($data_base->num_rows() !== 0) {
	
		// P�tla wyswietlaj�ca wszystkie wpisy + stronnicowanie ich
		while($data_base->next_record()) {
	
			$id 		= $data_base->f("id");
			$title 		= $data_base->f("title");
			$date 		= $data_base->f("date");
			$comments 	= $data_base->f("comments");
		
			$date = explode(' ', $date);
		
			$ft->assign(array(	'ID'		=>$id,
								'TITLE'		=>$title,
								'DATE'		=>$date[0],
								'COMMENTS'	=>$comments));	
							
			if($page_string !== "") {
		
				$ft->assign('STRING', "<b>Id� do strony:</b> " . $page_string);
			} else {
		
				$ft->assign('STRING', $page_string);
			}					
		
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
		
			// naprzemienne kolorowanie wierszy tabeli
			if (($idx1%2)==1) {
			
				$ft->assign('ID_CLASS', "id=\"mainList\"");
				// parsowanie szablon�w
				$ft->parse('NOTE_ROWS',	".table_mostcommentslist");
			} else {
			
				$ft->assign('ID_CLASS', "id=\"mainListAlter\"");
				$ft->parse('NOTE_ROWS',	".table_mostcommentslist");
			}
		}
	
		$ft->parse('ROWS',	".header_mostcommentslist");
	} else {
	
		$ft->assign(array(	'CONFIRM'	=>"W bazie danych nie ma �adnych wpis�w"));

		$ft->parse('ROWS',	".result_note");
	}
}

// wy�wietlanie wpisu pobranego do modyfikacji
if ($action == "show") {
	
	$db_base = new MySQL_DB;
	$db_base->query("SELECT * FROM $mysql_data[db_table_comments] WHERE id='$_GET[id]'");
	$db_base->next_record();
	
	$date 		= $db_base->f("date");
	$title 		= $db_base->f("title");
	$text 		= $db_base->f("text");
	$author		= $db_base->f("author");
	$published	= $db_base->f("published");

	
	$date	= substr($date, 0, 16);
	$dat1	= explode(" ", $date);
	$dat	= explode("-", $dat1[0]);
	$date	= "$dat[2]-$dat[1]-$dat[0] <b>$dat1[1]</b>";
	
	$text = str_replace("<br />", "\r\n", $text);
	$text = ereg_replace("(\r\n)+", "\r\n\r\n", $text);
	
	$ft->assign(array(	'AUTHOR'		=>$author,
						'DATE' 			=>$date,
						'ID'			=>$_GET['id'],
						'TEXT'			=>$text));

	$ft->parse('ROWS',	".form_commentsedit");
	
}

// edycja wybranego wpisu
if ($action == "edit") {
	
	$text		= nl2br($_POST['text']);
	$author		= $_POST['author'];
	
	$d_base = new MySQL_DB;
	$d_base->query(	"UPDATE $mysql_data[db_table_comments] SET author = '$author', text = '$text' WHERE id = '$_GET[id]'");
	$d_base->next_record();
	
	$ft->assign(array(	'CONFIRM'	=>"Komentarz zosta� zmodyfikowany."));

	$ft->parse('ROWS',	".result_note");
	
}

// usuwanie wybranego wpisu
if ($action == "delete") {
	
	$d_base = new MySQL_DB;
	$d_base->query("DELETE FROM $mysql_data[db_table_comments] WHERE id='$_GET[id]'");
	$d_base->next_record();
	
	$ft->assign(array(	'CONFIRM'	=>"Komentarz zosta� usuni�ty."));

	$ft->parse('ROWS', ".result_note");
	
}
?>