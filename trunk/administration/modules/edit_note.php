<?php

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = main_pagination('start,2,', '', 'editposts_per_page', '', 'db_table');

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

$db = new MySQL_DB;

switch ($action) {
	
	case "show":// wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = "	SELECT * FROM 
						$mysql_data[db_table] 
					WHERE 
						id='$_GET[id]'";
		
		$db->query($query);
		$db->next_record();
		
		$date 			= $db->f("date");
		$title 			= $db->f("title");
		$text 			= $db->f("text");
		$author			= $db->f("author");
		$published		= $db->f("published");
		$category		= $db->f("c_id");
		$comments_allow = $db->f("comments_allow");
		
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

		if($comments_allow == 1) {

			$ft->assign(array(	'COMMENTS_YES'	=>'<input style="border: 0px;" type="radio" name="comments_allow" value="1" align="top" checked="checked" />',
								'COMMENTS_NO'	=>'<input style="border: 0px;" type="radio" name="comments_allow" value="0" align="top" />'));
		} else {
			
			$ft->assign(array(	'COMMENTS_YES'	=>'<input style="border: 0px;" type="radio" name="comments_allow" value="1" align="top" />',
								'COMMENTS_NO'	=>'<input style="border: 0px;" type="radio" name="comments_allow" value="0" align="top" checked="checked" />'));
		}					
								
		if($published == "Y") {

			$ft->assign(array(	'CHECKBOX_YES'	=>'<input style="border: 0px;" type="radio" name="published" value="Y" align="top" checked="checked" />',
								'CHECKBOX_NO'	=>'<input style="border: 0px;" type="radio" name="published" value="N" align="top" />'));
		} else {
			
			$ft->assign(array(	'CHECKBOX_YES'	=>'<input style="border: 0px;" type="radio" name="published" value="Y" align="top" />',
								'CHECKBOX_NO'	=>'<input style="border: 0px;" type="radio" name="published" value="N" align="top" checked="checked" />'));
		}
		
		$query = "	SELECT 
						category_id, category_name 
					FROM 
						$mysql_data[db_table_category]";
		
		$db->query($query);
		while($db->next_record()) {
			
			$c_id 	= $db->f("category_id");
			$c_name = $db->f("category_name");
			
			if($c_id == $category) {
				
				$ft->assign('CURRENT_CAT', 'selected="selected"');
			} else {
				$ft->assign('CURRENT_CAT', '');
			}
		
			$ft->assign(array(	'C_ID'		=>$c_id,
								'C_NAME'	=>$c_name));
								
							
			$ft->define('form_categoryoption', "form_categoryoption.tpl");
			$ft->parse('CATEGORY_ROWS', ".form_categoryoption");					
		
		}

		$ft->define('form_noteedit', "form_noteedit.tpl");
		$ft->parse('ROWS',	".form_noteedit");
		break;
		
	case "edit": // edycja wybranego wpisu
	
		$text		= str_nl2br($_POST['text']);
		$title		= $_POST['title'];
		$author		= $_POST['author'];
		$published	= $_POST['published'];
		$c_id		= $_POST['category_id'];
		
		$comments_allow = $_POST['comments_allow'];
		
		$query = "	UPDATE 
						$mysql_data[db_table] 
					SET 
						title = '$title', 
						author = '$author', 
						text = '$text', 
						published = '$published', 
						c_id = '$c_id', 
						comments_allow = '$comments_allow'  
					WHERE 
						id = '$_GET[id]'";
		$db->query($query);
		
		$ft->assign('CONFIRM', "Wpis zosta³ zmodyfikowany.");
		$ft->parse('ROWS',	".result_note");
		break;
		
	case "delete": // usuwanie wybranego wpisu
	
		$query = "	DELETE FROM 
						$mysql_data[db_table] 
					WHERE 
						id = '$_GET[id]'";
		$db->query($query);
		
		$ft->assign('CONFIRM', "Wpis zosta³ usuniêty.");
		$ft->parse('ROWS', ".result_note");
		break;
		
	default:
	
		$query = "	SELECT * FROM 
						$mysql_data[db_table_config] 
					WHERE 
						config_name = 'editposts_per_page'";
		$db->query($query);
		$db->next_record();
			
		$editposts_per_page = $db->f("config_value");
		$editposts_per_page = empty($editposts_per_page) ? 10 : $editposts_per_page;
		
		$query = "	SELECT * FROM 
						$mysql_data[db_table] 
					ORDER BY 
						date 
					DESC 
					LIMIT 
						$start, $editposts_per_page";
		$db->query($query);
		
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
				
					$ft->assign('ID_CLASS', "class=\"mainList\"");
					// parsowanie szablonów
					$ft->define('table_notelist', "table_notelist.tpl");
					$ft->parse('NOTE_ROWS',	".table_notelist");
				} else {
				
					$ft->assign('ID_CLASS', "class=\"mainListAlter\"");
					$ft->define('table_notelist', "table_notelist.tpl");
					$ft->parse('NOTE_ROWS',	".table_notelist");
				}
			}
		
			$ft->define('header_notelist', "header_notelist.tpl");
			$ft->parse('ROWS',	".header_notelist");
		} else {
		
			$ft->assign('CONFIRM', "W bazie danych nie ma ¿adnych wpisów");
			$ft->parse('ROWS',	".result_note");
		}
}
?>