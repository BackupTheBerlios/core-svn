<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new DB_SQL;

switch ($action) {
	
	case "show": // wy�wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
					SELECT * FROM 
						$mysql_data[db_table_pages] 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
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

		$ft->define('form_pageedit', "form_pageedit.tpl");
		$ft->parse('ROWS',	".form_pageedit");
		break;

	case "edit": // edycja wybranego wpisu
	
		$text		= nl2br($_POST['text']);
		$title		= $_POST['title'];
		$published	= $_POST['published'];
		
		$query = sprintf("
					UPDATE 
						$mysql_data[db_table_pages] 
					SET 
						title		= '$title', 
						text		= '$text', 
						published	= '$published' 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Strona zosta�a zmodyfikowana.");
		$ft->parse('ROWS',	".result_note");
		break;

	case "delete": // usuwanie wybranego wpisu
	
		$query = sprintf("
					DELETE FROM 
						$mysql_data[db_table_pages] 
					WHERE 
						id = '%1\$d'", $_GET['id']);
		
		$db->query($query);
		
		$ft->assign('CONFIRM', "Strona zosta�a usuni�ta.");
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
						$mysql_data[db_table_pages] 
					WHERE
						parent_id = '%1\$d' 	
					ORDER BY 
						id 
					ASC", 0);
		
		$db->query($query);
		
		// Sprawdzamy, czy w bazie danych s� ju� jakie� wpisy
		if($db->num_rows() > 0) {
		
			// P�tla wyswietlaj�ca wszystkie wpisy + stronnicowanie ich
			while($db->next_record()) {
		
				$page_id 		= $db->f("id");
				$title 			= $db->f("title");
				$published		= $db->f("published");
			
				$ft->assign(array(	'ID'	=>$page_id,
									'TITLE'	=>$title));
								
				if($published == 'Y') {

					$ft->assign('PUBLISHED', "Tak");
				} else {
				
					$ft->assign('PUBLISHED', "Nie");
				}						
			
				// deklaracja zmiennej $idx1::color switcher
				$idx1 = empty($idx1) ? '' : $idx1;
				
				$idx1++;
				
				$ft->define("editlist_pages", "editlist_pages.tpl");
				$ft->define_dynamic("row", "editlist_pages");
			
				// naprzemienne kolorowanie wierszy tabeli
				if (($idx1%2)==1) {
				
					$ft->assign('ID_CLASS', "class=\"mainList\"");
					
					$ft->parse('ROWS', ".row");
				} else {
				
					$ft->assign('ID_CLASS', "class=\"mainListAlter\"");
					
					$ft->parse('ROWS', ".row");
				}
				
				get_editpage_cat($page_id, 2);
			}
		
			$ft->parse('ROWS',	"editlist_pages");
		} else {
		
			$ft->assign('CONFIRM', "W bazie danych nie ma �adnych wpis�w");
			$ft->parse('ROWS',	".result_note");
		}
}
?>