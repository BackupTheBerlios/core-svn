<?php

// inicjowanie funkcji stronnicuj±cej wpisy
$pagination = main_pagination('main.php?p=2&amp;start=', '', 'editposts_per_page', '', TABLE_MAIN, false);

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show":// wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
            SELECT
                id,
	            DATE_FORMAT(date, '%%d-%%m-%%Y %%T') AS date,
	            title,
	            author,
	            text,
	            image,
	            comments_allow,
	            published, 
	            only_in_category
            FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            TABLE_MAIN,
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
        $date           = $db->f('date');
		$title 			= $db->f('title');
		$text 			= $db->f('text');
		$author			= $db->f('author');
		$published		= $db->f('published');
        $image          = $db->f('image');
		$comments_allow = $db->f('comments_allow');
        $only_in_cat    = $db->f('only_in_category');
		
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
            'TITLE'			=>$title,
            'TEXT'			=>br2nl($text)
        ));

		if($comments_allow == 1) {
			$ft->assign('COMMENTS_YES', 'checked="checked"');
		} else {
			$ft->assign('COMMENTS_NO', 'checked="checked"');
		}

		if($only_in_cat == "1") {
			$ft->assign('ONLYINCAT_YES', 'checked="checked"');
		} else {
			$ft->assign('ONLYINCAT_NO', 'checked="checked"');
		}	
								
		if($published == "1") {

			$ft->assign('CHECKBOX_YES', 'checked="checked"');
		} else {
			
			$ft->assign('CHECKBOX_NO', 'checked="checked"');
		}
		
		if(!empty($image)) {
		    
		    $ft->define("form_imageedit", "form_imageedit.tpl");
		    $ft->assign(array(
                'IMAGE'             =>$image, 
                'OVERWRITE_PHOTO'   =>'Poprzednie zostanie nadpisane'
		    ));
            // parsujemy szablon informujacy o do³±czonym do wpisu zdjêciu
			$ft->parse('IF_IMAGE_EXIST', ".form_imageedit");
		}
		
		$query = sprintf("
            SELECT 
                category_id, 
                category_parent_id, 
                category_name 
            FROM 
                %1\$s 
            WHERE 
                category_parent_id = '%2\$d'", 
		
            TABLE_CATEGORY, 
            0
        );
        $sql = new DB_SQL;
		
		$db->query($query);
		while($db->next_record()) {
			
			$c_id 	= $db->f("category_id");
			$c_name = $db->f("category_name");
			
			$query = sprintf("
                SELECT * FROM 
                    %1\$s 
                WHERE 
                    category_id = '%2\$d' 
                AND 
                    news_id = '%3\$d'", 
			
                TABLE_ASSIGN2CAT, 
                $c_id, 
                $_GET['id']
            );
            
            $sql->query($query);
            $sql->next_record();
            
            $assigned = $sql->f("category_id");
            
            if($c_id == $assigned) {
                $ft->assign('CURRENT_CAT', 'checked="checked"');
			} else {
				$ft->assign('CURRENT_CAT', '');
			}
		
			$ft->assign(array(
                'C_ID'		=>$c_id,
                'C_NAME'	=>$c_name, 
                'PAD'       =>''
            ));
            
            $ft->define("form_noteedit", "form_noteedit.tpl");
			$ft->define_dynamic("cat_row", "form_noteedit");

			$ft->parse('CAT_ROW', ".cat_row");
			
			get_editnews_assignedcat($c_id, 2);		
		
		}
		
		$ft->parse('ROWS',	"form_noteedit");
		break;
		
	case "edit": // edycja wybranego wpisu
	
        $query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                id = '%2\$d'", 
		
            TABLE_MAIN,
            $_GET['id']
        );
		
		$db->query($query);
		$db->next_record();
		
		$note_author = $db->f("author");
		
		if($permarr['moderator'] || ($permarr['writer'] && $note_author == $_SESSION['login'])) {
	
            $text		= $_POST['text'];
            $title		= $_POST['title'];
            $author		= $_POST['author'];
            $published	= $_POST['published'];
            
            $comments_allow = $_POST['comments_allow'];
            $only_in_cat    = $_POST['only_in_category'];
            $assign2cat     = $_POST['assign2cat'];

            //sprawdzania daty
            if (isset($_POST['now']) || !preg_match('#^([0-9][0-9])-([0-9][0-9])-([0-9][0-9][0-9][0-9]) ([0-9][0-9]:[0-9][0-9]:[0-9][0-9])$#', $_POST['date'], $matches)) {

                $date = date("Y-m-d H:i:s");
            } else {

              $date = sprintf('%s-%s-%s %s', $matches[3], $matches[2], $matches[1], $matches[4]);
            }
            
            $text = parse_markers($text, 1);
		
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    title			= '%2\$s', 
                    author			= '%3\$s', 
                    text			= '%4\$s', 
                    published		= '%5\$s', 
                    comments_allow	= '%6\$d',
                    date            = '%7\$s', 
                    only_in_category= '%8\$s'
                WHERE 
                    id = '%9\$d'", 
            
                TABLE_MAIN, 
                $title, 
                $author, 
                $text, 
                $published, 
                $comments_allow, 
                $date, 
                $only_in_cat, 
                $_GET['id']
            );
            
            $db->query($query);
            
            $query = sprintf("
                DELETE FROM 
                    %1\$s 
                WHERE 
                    news_id = '%2\$d'", 
            
                TABLE_ASSIGN2CAT, 
                $_GET['id']
            );
            $db->query($query);
            
            // wprowadzamy informacje o przynaleznych kategoriach
            foreach ($assign2cat as $selected_cat) {
                $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES('', '%2\$d', '%3\$d')", 
                
                    TABLE_ASSIGN2CAT, 
                    $_GET['id'], 
                    $selected_cat
                );
                $db->query($query);
            }
            
            // usuwamy istniej±ce zdjêcie
            if(isset($_POST['delete_image']) && (($_POST['delete_image']) == 1)) {
                
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        image = '' 
                    WHERE 
                        id = '%2\$d'", 
                
                    TABLE_MAIN, 
                    $_GET['id']
                );
                
                $db->query($query);
            }
            
            // dodajemy zdjêcie do wpisu
            if(!empty($_FILES['file']['name'])) {
                
                $up = new upload;
                $upload_dir = "../photos";
			
                // upload pliku na serwer.
                $file = $up->upload_file($upload_dir, 'file', true, true, 0, "jpg|jpeg|gif");
                if($file == false) {
				
				    echo $up->error;
                } else {
			    
                    $query = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            image = '%2\$s' 
                        WHERE 
                            id = '%3\$d'", 
			    
                        TABLE_MAIN,
                        $file,
                        $_GET['id']
                    );
                
				    $db->query($query);
                }
            }
            
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
	
        // potwierdzenie usuniecia wpisu
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
		
                        TABLE_MAIN, 
                        $post_id
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
            
        case "Nie":
        
            header("Location: main.php?p=2");
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
		
    case "multidelete": // usuwanie wybranego wpisu
	
        if($permarr['moderator']) {
            
            if(isset($_POST['selected_note']) || isset($_POST['selected_status'])) {
                if(!empty($_POST['selected_note'])) {
            
                    $query = sprintf("
                        DELETE FROM 
                            %1\$s 
                        WHERE 
                            id
                        IN(".implode(',', $_POST['selected_note']).")", 
		
                        TABLE_MAIN
                    );
		
                    $db->query($query);
                    $ft->assign('CONFIRM', 'Wpisy zosta³y usuniête.');
                }
            
                if(!empty($_POST['selected_status'])) {
            
                    $query = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            published = published * -1 
                        WHERE 
                            id 
                        IN(".implode(',', $_POST['selected_status']).")", 
		
                        TABLE_MAIN
                    );
		
                    $db->query($query);
                    $ft->assign('CONFIRM', 'Status wpisów zosta³ zmieniony.');
                }
            
            } else {
                
                $ft->assign('CONFIRM', 'Nie zaznaczono ¿adnych wpisów');
            }
            
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
                config_name = 'editposts_per_page'", 
		
            TABLE_CONFIG, 
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
		
            TABLE_MAIN, 
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
								
				if($published == '1') {

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
				
					$ft->assign('ID_CLASS', 'mainList');
					
					$ft->parse('ROWS',	".row");

				} else {
				
					$ft->assign('ID_CLASS', 'mainListAlter');
				    
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
