<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new MySQL_DB;

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
		$query = "	SELECT * 
					FROM 
						$mysql_data[db_table_pages] 
					WHERE 
						id = '$_GET[id]'";
		
		$db->query($query);
		$db->next_record();
		
		$title 		= $db->f("title");
		$text 		= $db->f("text");
		$published	= $db->f("published");
		
		$text = str_replace("<br />", "\r\n", $text);
		$text = preg_replace("/(\r\n)+/", "\\1\\1", $text);
		
		$ft->assign(array(	'ID'	=>$_GET['id'],
							'TITLE'	=>$title,
							'TEXT'	=>$text));
							
		if($published == "Y") {

			$ft->assign(array(	'CHECKBOX_YES'	=>'<input style="border: 0px;" type="radio" name="published" value="Y" align="top" checked="checked" />',
								'CHECKBOX_NO'	=>'<input style="border: 0px;" type="radio" name="published" value="N" align="top" />'));
		} else {
			
			$ft->assign(array(	'CHECKBOX_YES'	=>'<input style="border: 0px;" type="radio" name="published" value="Y" align="top" />',
								'CHECKBOX_NO'	=>'<input style="border: 0px;" type="radio" name="published" value="N" align="top" checked="checked" />'));
		}

		$ft->parse('ROWS',	".form_pageedit");
		break;

	case "edit": // edycja wybranego wpisu
		$text		= nl2br($_POST['text']);
		$title		= $_POST['title'];
		$published	= $_POST['published'];
		
		$query = "	UPDATE 
						$mysql_data[db_table_pages] 
					SET 
						title = '$title', 
						text = '$text', 
						published = '$published' 
					WHERE 
						id='$_GET[id]'";
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Strona zosta³a zmodyfikowana.");
		$ft->parse('ROWS',	".result_note");
		break;

	case "delete": // usuwanie wybranego wpisu
		$query = "	DELETE FROM 
						$mysql_data[db_table_pages] 
					WHERE 
						id = '$_GET[id]'";
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Strona zosta³a usuniêta.");
		$ft->parse('ROWS', ".result_note");
		break;

	default:
		$query = "	SELECT * 
					FROM 
						$mysql_data[db_table_config] 
					WHERE 
						config_name = 'editposts_per_page'";
		
		$db->query($query);
		$db->next_record();
			
		$editposts_per_page = $db->f("config_value");
		
		$query = "	SELECT * 
					FROM 
						$mysql_data[db_table_pages] 
					ORDER BY 
						id 
					DESC";
		
		$db->query($query);
		
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
			// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
			while($db->next_record()) {
		
				$id 		= $db->f("id");
				$title 		= $db->f("title");
				$published	= $db->f("published");
			
				$ft->assign(array(	'ID'	=>$id,
									'TITLE'	=>$title));
								
				if($published == 'Y') {

					$ft->assign('PUBLISHED', "Tak");
				} else {
				
					$ft->assign('PUBLISHED', "Nie");
				}						
			
				// deklaracja zmiennej $idx1::color switcher
				$idx1 = empty($idx1) ? '' : $idx1;
				
				$idx1++;
			
				// naprzemienne kolorowanie wierszy tabeli
				if (($idx1%2)==1) {
				
					$ft->assign('ID_CLASS', "id=\"mainList\"");
					// parsowanie szablonów
					$ft->parse('NOTE_ROWS',	".table_pagelist");
				} else {
				
					$ft->assign('ID_CLASS', "id=\"mainListAlter\"");
					$ft->parse('NOTE_ROWS',	".table_pagelist");
				}
			}
		
			$ft->parse('ROWS',	".header_pagelist");
		} else {
		
			$ft->assign('CONFIRM', "W bazie danych nie ma ¿adnych wpisów");
			$ft->parse('ROWS',	".result_note");
		}
}
?>