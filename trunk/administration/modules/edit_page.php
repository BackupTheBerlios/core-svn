<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            $mysql_data['db_table_pages'], 
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
		$title 		= $db->f("title");
		$text 		= $db->f("text");
		$published	= $db->f("published");
		
		$ft->assign(array(
            'ID'	=>$_GET['id'],
            'TITLE'	=>stripslashes($title),
            'TEXT'	=>br2nl(stripslashes($text))
        ));
							
		if($published == "Y") {

			$ft->assign('CHECKBOX_YES', 'checked="checked"');
		} else {
			
			$ft->assign('CHECKBOX_NO', 'checked="checked"');
		}			

		$ft->define('form_pageedit', "form_pageedit.tpl");
		$ft->parse('ROWS',	".form_pageedit");
		break;

	case "edit": // edycja wybranego wpisu
	
        if($permarr['writer']) {
	
            $text		= nl2br(addslashes($_POST['text']));
            $title		= addslashes($_POST['title']);
            $published	= $_POST['published'];
		
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    title		= '%2\$s', 
                    text		= '%3\$s', 
                    published	= '%4\$s' 
                WHERE 
                    id = '%5\$d'", 
		
                $mysql_data['db_table_pages'], 
                $title, 
                $text, 
                $published, 
                $_GET['id']
            );
		
            $db->query($query);
		
            $ft->assign('CONFIRM', $i18n['edit_page'][0]);
            $ft->parse('ROWS',	".result_note");
        } else {
            
            $monit[] = $i18n['edit_page'][3];
            
            foreach ($monit as $error) {
			    
			    $ft->assign('ERROR_MONIT', $error);
			    
			    $ft->parse('ROWS',	".error_row");
			}
			
			$ft->parse('ROWS', "error_reporting");
        }
		break;

	case "delete": // usuwanie wybranego wpisu
	
        if($permarr['moderator']) {	

            $query = sprintf("
                DELETE FROM 
                    %1\$s 
                WHERE 
                    id = '%2\$d'", 
		
                $mysql_data['db_table_pages'], 
                $_GET['id']
            );
		
            $db->query($query);
		
            $ft->assign('CONFIRM', $i18n['edit_page'][1]);
            $ft->parse('ROWS', ".result_note");
        } else {
            
            $monit[] = $i18n['edit_page'][2];
            
            foreach ($monit as $error) {
			    
			    $ft->assign('ERROR_MONIT', $error);
			    
			    $ft->parse('ROWS',	".error_row");
			}
			
			$ft->parse('ROWS', "error_reporting");
        }
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
		
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
			// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
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
				
					$ft->assign('ID_CLASS', 'class="mainList"');
					
					$ft->parse('ROWS', ".row");
				} else {
				
					$ft->assign('ID_CLASS', 'class="mainListAlter"');
					
					$ft->parse('ROWS', ".row");
				}
				
				get_editpage_cat($page_id, 2);
			}
		
			$ft->parse('ROWS',	"editlist_pages");
		} else {
		
			$ft->assign('CONFIRM', "W bazie danych nie ma ¿adnych wpisów");
			$ft->parse('ROWS',	".result_note");
		}
}
?>