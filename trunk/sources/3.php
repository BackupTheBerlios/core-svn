<?php

// inicjowanie funkcji stronnicuj±cej wpisy
main_pagination('editcomments.', '', 'editposts_per_page', '', 'db_table_comments');

if (!isset($_GET['action'])) {
	
	$data_base_config = new MySQL_DB;
	$data_base_config->query("SELECT * FROM $mysql_data[db_table_config] WHERE config_name = 'editposts_per_page'");
	$data_base_config->next_record();
		
	$editposts_per_page = $data_base_config->f("config_value");
	
	
	$data_base = new MySQL_DB;
	$data_base->query("SELECT * FROM $mysql_data[db_table_comments] ORDER BY date DESC LIMIT $start, $editposts_per_page");
	
	// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
	if($data_base->num_rows() !== 0) {
	
		// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
		while($data_base->next_record()) {
	
			$id 		= $data_base->f("id");
			$text 		= $data_base->f("text");
			$date 		= $data_base->f("date");
			$author		= $data_base->f("author");
			$author_ip	= $data_base->f("author_ip");
		
			$date = explode(' ', $date);
		
			if (strlen($text) > 70 ) {
			
				$text = substr_replace($text, '...',70);
			} else {
				$text = $text;
			}
		
			$ft->assign(array(	'ID'		=>$id,
								'TEXT'		=>$text,
								'DATE'		=>$date[0],
								'AUTHOR'	=>$author,
								'AUTHOR_IP'	=>$author_ip));	
							
			if($page_string !== "") {
		
				$ft->assign('STRING', "<b>Id¼ do strony:</b> " . $page_string);
			} else {
		
				$ft->assign('STRING', $page_string);
			}					
		
			$idx1++;
		
			// naprzemienne kolorowanie wierszy tabeli
			if (($idx1%2)==1) {
			
				$ft->assign('ID_CLASS', "id=\"mainList\"");
				// parsowanie szablonów
				$ft->parse('NOTE_ROWS',	".table_commentslist");
			} else {
			
				$ft->assign('ID_CLASS', "id=\"mainListAlter\"");
				$ft->parse('NOTE_ROWS',	".table_commentslist");
			}
		}
	
		$ft->parse('ROWS',	".header_commentslist");
	} else {
	
		$ft->assign(array(	'CONFIRM'	=>"W bazie danych nie ma ¿adnych komentarzy."));

		$ft->parse('ROWS',	".result_note");
	}
}

// wy¶wietlanie wpisu pobranego do modyfikacji
if ($_GET['action']=="show") {
	
	$db_base = new MySQL_DB;
	$db_base->query("SELECT * FROM $mysql_data[db_table_comments] WHERE id = '$_GET[id]'");
	$db_base->next_record();
	
	$date 		= $db_base->f("date");
	$title 		= $db_base->f("title");
	$text 		= $db_base->f("text");
	$author		= $db_base->f("author");
	$published	= $db_base->f("published");

	
	$date	= substr($date, 0, 16);
	$dat1	= explode(" ", $date);
	$dat	= explode("-", $dat1[0]);
	$date	= "$dat[2]-$dat[1]-$dat[0] $dat1[1]";
	
	$text = str_replace("<br />", "\r\n", $text);
	$text = ereg_replace("(\r\n)+", "\r\n\r\n", $text);
	
	$ft->assign(array(	'AUTHOR'		=>$author,
						'DATE' 			=>$date,
						'ID'			=>$_GET['id'],
						'TEXT'			=>$text));

	$ft->parse('ROWS',	".form_commentsedit");
	
}

// edycja wybranego wpisu
if ($_GET['action']=="edit") {
	
	$text		= nl2br($_POST['text']);
	$author		= $_POST['author'];
	
	$d_base = new MySQL_DB;
	$d_base->query(	"UPDATE $mysql_data[db_table_comments] SET author='$author', text='$text' WHERE id='$_GET[id]'");
	$d_base->next_record();
	
	$ft->assign(array(	'CONFIRM'	=>"Komentarz zosta³ zmodyfikowany."));

	$ft->parse('ROWS',	".result_note");
	
}

// usuwanie wybranego wpisu
if ($_GET['action']=="delete") {
	
	$d_base = new MySQL_DB;
	$d_base->query("DELETE FROM $mysql_data[db_table_comments] WHERE id='$_GET[id]'");
	$d_base->next_record();
	
	$ft->assign(array(	'CONFIRM'	=>"Komentarz zosta³ usuniêty."));

	$ft->parse('ROWS', ".result_note");
	
}
?>