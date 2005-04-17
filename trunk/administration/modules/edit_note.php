<?php

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = main_pagination('main.php?p=2&amp;start=', '', 'editposts_per_page', '', 'db_table');

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show":// wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            $mysql_data['db_table'],
            $_GET['id']
        );
		
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
		
		/* nie dziala tak jak powinno, chwilowo zakomentowane
		 *
		 * $text = str_replace("<br />", "\r\n", $text);
		 * $text = preg_replace("/(\r\n){2,}/", "\\1\\1", $text);
		*/
		
		$ft->assign(array(
            'SESSION_LOGIN'	=>$_SESSION['login'],
            'AUTHOR'		=>$author,
            'DATE' 			=>$date,
            'ID'			=>$_GET['id'],
            'TITLE'			=>stripslashes($title),
            'TEXT'			=>br2nl(stripslashes($text))
        ));

		if($comments_allow == 1) {

			$ft->assign('COMMENTS_YES', 'checked="checked"');
		} else {
			
			$ft->assign('COMMENTS_NO', 'checked="checked"');
		}					
								
		if($published == "Y") {

			$ft->assign('CHECKBOX_YES', 'checked="checked"');
		} else {
			
			$ft->assign('CHECKBOX_NO', 'checked="checked"');
		}
		
		$query = sprintf("
            SELECT 
                category_id, category_name 
            FROM 
                %1\$s", 
		
            $mysql_data['db_table_category']
        );
		
		$db->query($query);
		while($db->next_record()) {
			
			$c_id 	= $db->f("category_id");
			$c_name = $db->f("category_name");
			
			if($c_id == $category) {
				
				$ft->assign('CURRENT_CAT', 'selected="selected"');
			} else {
				$ft->assign('CURRENT_CAT', '');
			}
		
			$ft->assign(array(
                'C_ID'		=>$c_id,
                'C_NAME'	=>$c_name
            ));
            
            $ft->define("form_noteedit", "form_noteedit.tpl");
			$ft->define_dynamic("category_row", "form_noteedit");

			$ft->parse('ROWS',	".category_row");					
		
		}
		
		$ft->parse('ROWS',	"form_noteedit");
		break;
		
	case "edit": // edycja wybranego wpisu
	
        $query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            $mysql_data['db_table'],
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
		$note_author = $db->f("author");
		
		if($permarr['writer'] && ($note_author == $_SESSION['login'])) {
	
            $text		= str_nl2br(addslashes($_POST['text']));
            $title		= addslashes($_POST['title']);
            $author		= $_POST['author'];
            $published	= $_POST['published'];
            $c_id		= $_POST['category_id'];
            
            $comments_allow = $_POST['comments_allow'];
		
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    title			= '%2\$s', 
                    author			= '%3\$s', 
                    text			= '%4\$s', 
                    published		= '%5\$s', 
                    c_id			= '%6\$d', 
                    comments_allow	= '%7\$d'  
                WHERE 
                    id = '%8\$d'", 
            
                $mysql_data['db_table'], 
                $title, 
                $author, 
                $text, 
                $published, 
                $c_id, 
                $comments_allow, 
                $_GET['id']
            );
            
            $db->query($query);
            
            $ft->assign('CONFIRM', $i18n['edit_note'][0]);
            $ft->parse('ROWS',	".result_note");
		} else {
		    
		    $monit[] = $i18n['edit_note'][3];

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
		
                $mysql_data['db_table'], 
                $_GET['id']
            );
		
            $db->query($query);
		
            $ft->assign('CONFIRM', $i18n['edit_note'][1]);
            $ft->parse('ROWS', ".result_note");
        } else {
            
            $monit[] = $i18n['edit_note'][2];

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
                %1\$s 
            WHERE 
                config_name = '%2\$s'", 
		
            $mysql_data['db_table_config'], 
            "editposts_per_page"
        );
		
		$db->query($query);
		$db->next_record();
			
		$editposts_per_page = $db->f("config_value");
		$editposts_per_page = empty($editposts_per_page) ? 10 : $editposts_per_page;
		
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            ORDER BY 
                date 
            DESC 
            LIMIT 
                %2\$d, %3\$d", 
		
            $mysql_data['db_table'], 
            $start, 
            $editposts_per_page
        );
		
		$db->query($query);
		
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
			// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
			while($db->next_record()) {
		
				$id 		= $db->f("id");
				$title 		= $db->f("title");
				$date 		= $db->f("date");
				$published	= $db->f("published");
				$author     = $db->f("author");
			
				$date = explode(' ', $date);
			
                $ft->assign(array(
                    'ID'        =>$id,
                    'TITLE'     =>$title,
                    'DATE'      =>$date[0],
                    'AUTHOR'    =>$author
                ));
								
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
				
				$ft->define("editlist_notes", "editlist_notes.tpl");
				$ft->define_dynamic("row", "editlist_notes");
				
				// naprzemienne kolorowanie wierszy
				if (($idx1%2)==1) {
				
					$ft->assign('ID_CLASS', 'class="mainList"');
					
					$ft->parse('ROWS',	".row");

				} else {
				
					$ft->assign('ID_CLASS', 'class="mainListAlter"');
				    
				    $ft->parse('ROWS',	".row");
				}
			}
		
			$ft->parse('ROWS', "editlist_notes");
		} else {
		
			$ft->assign('CONFIRM', $i18n['edit_note'][4]);
			$ft->parse('ROWS',	".result_note");
		}
}

?>