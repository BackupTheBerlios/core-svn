<?php

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = main_pagination('start,2,', '', 'editposts_per_page', '', 'db_table');

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new MySQL_DB;

switch ($action) {
	
	case "show":// wy¶wietlanie wpisu pobranego do modyfikacji
		$db->query("SELECT * FROM $mysql_data[db_table] WHERE id='$_GET[id]'");
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
		$text = preg_replace("/(\r\n){2,}/", "\\1\\1", $text);
		
		$ft->assign(array(	'SESSION_LOGIN'	=>$_SESSION['login'],
							'AUTHOR'		=>$author,
							'DATE' 			=>$date,
							'ID'			=>$_GET['id'],
							'TITLE'			=>$title,
							'TEXT'			=>$text));
							
		if($published == "Y") {

			$ft->assign(array(	'CHECKBOX_YES'	=>'<input style="border: 0px;" type="radio" name="published" value="Y" align="top" checked="checked" />',
								'CHECKBOX_NO'	=>'<input style="border: 0px;" type="radio" name="published" value="N" align="top" />'));
		} else {
			
			$ft->assign(array(	'CHECKBOX_YES'	=>'<input style="border: 0px;" type="radio" name="published" value="Y" align="top" />',
								'CHECKBOX_NO'	=>'<input style="border: 0px;" type="radio" name="published" value="N" align="top" checked="checked" />'));
		}

		$ft->parse('ROWS',	".form_noteedit");
		break;
		
	case "edit": // edycja wybranego wpisu
		$text		= str_nl2br($_POST['text']);
		$title		= $_POST['title'];
		$author		= $_POST['author'];
		$published	= $_POST['published'];
		
		$db->query(	"UPDATE $mysql_data[db_table] SET title='$title', author='$author', text='$text', published='$published' WHERE id='$_GET[id]'");
		
		$ft->assign(array(	'CONFIRM'	=>"Wpis zosta³ zmodyfikowany."));

		$ft->parse('ROWS',	".result_note");
		break;
		
	case "delete": // usuwanie wybranego wpisu
		$db->query("DELETE FROM $mysql_data[db_table] WHERE id='$_GET[id]'");
		
		$ft->assign(array(	'CONFIRM'	=>"Wpis zosta³ usuniêty."));

		$ft->parse('ROWS', ".result_note");
		break;
		
	default:
		$db->query("SELECT * FROM $mysql_data[db_table_config] WHERE config_name = 'editposts_per_page'");
		$db->next_record();
			
		$editposts_per_page = $db->f("config_value");
		if (empty($editposts_per_page)) {

			$editposts_per_page = 10;
		}
		
		
		$db->query("SELECT * FROM $mysql_data[db_table] ORDER BY date DESC LIMIT $start, $editposts_per_page");
		
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
			// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
			while($db->next_record()) {
		
				$id 		= $db->f("id");
				$title 		= $db->f("title");
				$date 		= $db->f("date");
				$published	= $db->f("published");
			
				$date = explode(' ', $date);
			
				$ft->assign(array(	'ID'		=>$id,
									'TITLE'		=>$title,
									'DATE'		=>$date[0]));
								
				if($published == 'Y') {

					$ft->assign('PUBLISHED', "Tak");
				} else {
				
					$ft->assign('PUBLISHED', "Nie");
				}		
								
				if($page_string !== "") {
			
					$ft->assign('STRING', "<b>Id¼ do strony:</b> " . $page_string);
				} else {
			
					$ft->assign('STRING', $page_string);
				}					
			
				// deklaracja zmiennej $idx1::color switcher
				$idx1 = empty($idx1) ? '' : $idx1;
				
				$idx1++;
			
				// naprzemienne kolorowanie wierszy tabeli
				if (($idx1%2)==1) {
				
					$ft->assign('ID_CLASS', "id=\"mainList\"");
					// parsowanie szablonów
					$ft->parse('NOTE_ROWS',	".table_notelist");
				} else {
				
					$ft->assign('ID_CLASS', "id=\"mainListAlter\"");
					$ft->parse('NOTE_ROWS',	".table_notelist");
				}
			}
		
			$ft->parse('ROWS',	".header_notelist");
		} else {
		
			$ft->assign(array(	'CONFIRM'	=>"W bazie danych nie ma ¿adnych wpisów"));

			$ft->parse('ROWS',	".result_note");
		}
}

?>
