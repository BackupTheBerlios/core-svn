<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

if (empty($action)) {
	
	$data_base_config = new MySQL_DB;
	$data_base_config->query("	SELECT * 
								FROM $mysql_data[db_table_config] 
								WHERE config_name = 'editposts_per_page'");
	$data_base_config->next_record();
		
	$editposts_per_page = $data_base_config->f("config_value");
	
	
	$data_base = new MySQL_DB;
	$data_base->query("	SELECT * 
						FROM $mysql_data[db_table_pages] 
						ORDER BY id DESC");
	
	// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
	if($data_base->num_rows() !== 0) {
	
		// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
		while($data_base->next_record()) {
	
			$id 		= $data_base->f("id");
			$title 		= $data_base->f("title");
			$published	= $data_base->f("published");
		
			$ft->assign(array(	'ID'		=>$id,
								'TITLE'		=>$title));
							
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
	
		$ft->assign(array(	'CONFIRM'	=>"W bazie danych nie ma ¿adnych wpisów"));

		$ft->parse('ROWS',	".result_note");
	}
}


// wy¶wietlanie wpisu pobranego do modyfikacji
if ($action == "show") {
	
	$db_base = new MySQL_DB;
	$db_base->query("	SELECT * 
						FROM $mysql_data[db_table_pages] 
						WHERE id='$_GET[id]'");
	$db_base->next_record();
	
	$title 		= $db_base->f("title");
	$text 		= $db_base->f("text");
	$published	= $db_base->f("published");
	
	$text = str_replace("<br />", "\r\n", $text);
	$text = ereg_replace("(\r\n)+", "\r\n\r\n", $text);
	
	$ft->assign(array(	'ID'			=>$_GET['id'],
						'TITLE'			=>$title,
						'TEXT'			=>$text));
						
	if($published == "Y") {

		$ft->assign(array(	'CHECKBOX_YES'	=>'<input style="border: 0px;" type="radio" name="published" value="Y" align="top" checked="checked" />',
							'CHECKBOX_NO'	=>'<input style="border: 0px;" type="radio" name="published" value="N" align="top" />'));
	} else {
		
		$ft->assign(array(	'CHECKBOX_YES'	=>'<input style="border: 0px;" type="radio" name="published" value="Y" align="top" />',
							'CHECKBOX_NO'	=>'<input style="border: 0px;" type="radio" name="published" value="N" align="top" checked="checked" />'));
	}

	$ft->parse('ROWS',	".form_pageedit");
	
}

// edycja wybranego wpisu
if ($action == "edit") {
	
	$text		= nl2br($_POST['text']);
	$title		= $_POST['title'];
	$published	= $_POST['published'];
	
	$d_base = new MySQL_DB;
	$d_base->query("UPDATE $mysql_data[db_table_pages] 
					SET title='$title', text='$text', published='$published' 
					WHERE id='$_GET[id]'");
	$d_base->next_record();
	
	$ft->assign(array(	'CONFIRM'	=>"Wpis zosta³ zmodyfikowany."));

	$ft->parse('ROWS',	".result_note");
	
}

// usuwanie wybranego wpisu
if ($action == "delete") {
	
	$d_base = new MySQL_DB;
	$d_base->query("DELETE FROM $mysql_data[db_table_pages] 
					WHERE id='$_GET[id]'");
	$d_base->next_record();
	
	$ft->assign(array(	'CONFIRM'	=>"Wpis zosta³ usuniêty."));

	$ft->parse('ROWS', ".result_note");
	
}
?>