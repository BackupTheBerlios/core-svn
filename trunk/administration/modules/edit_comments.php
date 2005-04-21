<?php

// inicjowanie funkcji stronnicuj±cej wpisy
main_pagination('main.php?p=5&amp;start=', '', 'editposts_per_page', '', 'db_table_comments');

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
		
            $mysql_data['db_table_comments'], 
            $_GET['id']
        );
		
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
		
		$ft->assign(array(
            'AUTHOR'    =>$author,
            'DATE'      =>$date,
            'ID'        =>$_GET['id'],
            'TEXT'      =>br2nl(stripslashes($text))
        ));

		$ft->define('form_commentsedit', "form_commentsedit.tpl");
		$ft->parse('ROWS',	".form_commentsedit");
		break;
	
	case "edit": // edycja wybranego wpisu
	
        if($permarr['moderator']) {
	
            $text     = nl2br(addslashes($_POST['text']));
            $author   = $_POST['author'];
		
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    author	= '%2\$s', 
                    text	= '%3\$s' 
                WHERE 
                    id = '%4\$d'", 
		
                $mysql_data['db_table_comments'], 
                $author, 
                $text, 
                $_GET['id']
            );
		
            $db->query($query);
		
            $ft->assign('CONFIRM', $i18n['edit_comments'][0]);
            $ft->parse('ROWS',	".result_note");
        } else {
            
            $monit[] = $i18n['edit_comments'][3];

            foreach ($monit as $error) {
    
                $ft->assign('ERROR_MONIT', $error);
                    
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
		break;
	
	case "delete": // usuwanie wybranego wpisu
	
        // potwierdzenie usuniecia komentarza
        $confirm = empty($_POST['confirm']) ? '' : $_POST['confirm'];
        switch ($confirm) {
            
            case "Tak":
            
                $post_id = empty($_POST['post_id']) ? '' : $_POST['post_id'];
	
                if($permarr['moderator']) {
	
                    $query = sprintf("
                        DELETE FROM 
                            %1\$s 
                        WHERE 
                            id = '%2\$d'", 
            
                        $mysql_data['db_table_comments'], 
                        $post_id
                    );
		
                    $db->query($query);
            
                    $ft->assign('CONFIRM', $i18n['edit_comments'][1]);
                    $ft->parse('ROWS', ".result_note");
                } else {
            
                    $monit[] = $i18n['edit_comments'][4];

                    foreach ($monit as $error) {
    
                        $ft->assign('ERROR_MONIT', $error);
                    
                        $ft->parse('ROWS',	".error_row");
                    }
                        
                $ft->parse('ROWS', "error_reporting");
                }
            break;
            
        case "Nie":
        
            header("Location: main.php?p=5");
            exit;
            break;
            
        default:
        
            $ft->define('confirm_action', 'confirm_action.tpl');
            $ft->assign(array(
                'PAGE_NUMBER'   =>$p, 
                'POST_ID'       =>$_GET['id']
            ));
            
            $ft->parse('ROWS', ".confirm_action");
            break;
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
				
					$ft->assign('ID_CLASS', 'mainList');
					
					$ft->parse('ROWS',	".row");

				} else {
				
					$ft->assign('ID_CLASS', 'mainListAlter');
				    
				    $ft->parse('ROWS',	".row");
				}
			}
		
			$ft->parse('ROWS', "editlist_comments");;
		} else {
		
			$ft->assign('CONFIRM', $i18n['edit_comments'][2]);

			$ft->parse('ROWS',	".result_note");
		}
}

?>