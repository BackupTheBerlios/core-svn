<?php

// inicjowanie funkcji stronnicuj±cej wpisy
main_pagination('start,5,', '', 'editposts_per_page', '', 'db_table_comments');

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// inicjalizacja instancji klasy MySQL_DB
$db = new MySQL_DB;

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
					SELECT * FROM 
						$mysql_data[db_table_comments] 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
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
		
		$ft->assign(array(	'AUTHOR'		=>$author,
							'DATE' 			=>$date,
							'ID'			=>$_GET['id'],
							'TEXT'			=>$text));

		$ft->define('form_commentsedit', "form_commentsedit.tpl");
		$ft->parse('ROWS',	".form_commentsedit");
		break;
	
	case "edit": // edycja wybranego wpisu
	
		$text		= nl2br($_POST['text']);
		$author		= $_POST['author'];
		
		$query = sprintf("
					UPDATE 
						$mysql_data[db_table_comments] 
					SET 
						author	= '$author', 
						text	= '$text' 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Komentarz zosta³ zmodyfikowany.");
		$ft->parse('ROWS',	".result_note");
		break;
	
	case "delete": // usuwanie wybranego wpisu
	
		$query = sprintf("
					DELETE FROM 
						$mysql_data[db_table_comments] 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Komentarz zosta³ usuniêty.");
		$ft->parse('ROWS', ".result_note");
		break;

	default:
	
		$query = sprintf("
					SELECT * FROM 
						$mysql_data[db_table_config] 
					WHERE 
						config_name = '%1\$s'", "editposts_per_page");
		
		$db->query($query);
		$db->next_record();
			
		$editposts_per_page = $db->f("config_value");
		
		$query = sprintf("
					SELECT * FROM 
						$mysql_data[db_table_comments] 
					ORDER BY 
						date 
					DESC LIMIT 
						%1\$d, %2\$d", $start, $editposts_per_page);
		
		$db->query($query);
		
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
			// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
			while($db->next_record()) {
		
				$id 		= $db->f("id");
				$text 		= $db->f("text");
				$date 		= $db->f("date");
				$author		= $db->f("author");
				$author_ip	= $db->f("author_ip");
			
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
			
				// deklaracja zmiennej $idx1::color switcher
				$idx1 = empty($idx1) ? '' : $idx1;
				
				$idx1++;
			
				$ft->define("editlist_comments", "editlist_comments.tpl");
				$ft->define_dynamic("row", "editlist_comments");
				
				// naprzemienne kolorowanie wierszy
				if (($idx1%2)==1) {
				
					$ft->assign('ID_CLASS', "class=\"mainList\"");
					
					$ft->parse('ROWS',	".row");

				} else {
				
					$ft->assign('ID_CLASS', "class=\"mainListAlter\"");
				    
				    $ft->parse('ROWS',	".row");
				}
			}
		
			$ft->parse('ROWS', "editlist_comments");;
		} else {
		
			$ft->assign(array(	'CONFIRM'	=>"W bazie danych nie ma ¿adnych komentarzy."));

			$ft->parse('ROWS',	".result_note");
		}
}

?>
