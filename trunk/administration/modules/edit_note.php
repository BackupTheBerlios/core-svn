<?php

// deklaracja zmiennej $action::form
$action     = empty($_GET['action']) ? '' : $_GET['action'];
$preview    = empty($_POST['preview']) ? '' : $_POST['preview'];
$post       = empty($_POST['post']) ? '' : $_POST['post'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show":// wy¶wietlanie wpisu pobranego do modyfikacji
	
        // podglad
        if(!empty($preview)) {
            $ft->assign(array(
                'NT_TEXT'       =>nl2br(parse_markers(stripslashes($_POST['text']), 1)), 
                'NOTE_PREVIEW'  =>true
            ));
        } else {
            $ft->assign(array( 
                'NOTE_PREVIEW'  =>false
            ));
        }
        
        // submit formularza
        if(!empty($post)) {
            
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
        } else {
            
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
		
            $ft->assign(array(
                'SESSION_LOGIN'	=>$_SESSION['login'],
                'AUTHOR'		=>$author,
                'DATE' 			=>$date,
                'ID'			=>$_GET['id'],
                'TITLE'         =>!empty($_POST['title']) ? stripslashes($_POST['title']) : $title,
                'TEXT'          =>!empty($_POST['text']) ? stripslashes(br2nl($_POST['text'])) : br2nl($text)
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
            
            $ft->assign('OVERWRITE_PHOTO', !empty($image) ? true : false);
                
            if(!empty($image)) {
		    
                $ft->define("form_imageedit", "form_imageedit.tpl");
                $ft->assign('IMAGE', $image);

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
		
                $ft->assign(array(
                    'C_ID'		    =>$c_id,
                    'C_NAME'	   =>$c_name, 
                    'PAD'           =>'', 
                    'CURRENT_CAT'   =>$c_id == $assigned ? 'checked="checked"' : ''
                ));
            
                $ft->define("form_noteedit", "form_noteedit.tpl");
                $ft->define_dynamic("cat_row", "form_noteedit");

                $ft->parse('CAT_ROW', ".cat_row");
                    
                get_editnews_assignedcat($c_id, 2);
            }
		
            $ft->parse('ROWS',	"form_noteedit");
        }              
		break;
		
	case "delete": // usuwanie wybranego wpisu
	
        // potwierdzenie usuniecia wpisu
        $confirm = empty($_POST['confirm']) ? '' : $_POST['confirm'];
        switch ($confirm) {
            
            case $i18n['confirm'][0]:
            
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
            
        case $i18n['confirm'][1]:
        
            header("Location: main.php?p=2");
            exit;
            break;
            
        default:
        
            $ft->define('confirm_action', 'confirm_action.tpl');
            $ft->assign(array(
                'PAGE_NUMBER'   =>$p, 
                'POST_ID'       =>$_GET['id'], 
                'CONFIRM_YES'   =>$i18n['confirm'][0],
                'CONFIRM_NO'    =>$i18n['confirm'][1]
            ));
            
            $ft->parse('ROWS', ".confirm_action");
            break;
        }
    break;
		
	default:
        if (isset($_POST['selected_notes']) && is_array($_POST['selected_notes']))
        {
            if (isset($_POST['sub_status'])) {
                if($permarr['moderator']) {
                    $query = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            published = published * -1 
                        WHERE 
                            id IN (%2\$s)", 
        
                        TABLE_MAIN,
                        implode(',', $_POST['selected_notes'])
                    );

                    $db->query($query);

                    $ft->assign('CONFIRM', $i18n['confirm'][3]);
                    $ft->parse('ROWS', ".result_note");
                } else {
            
                    $monit[] = $i18n['edit_note'][2];

                    foreach ($monit as $error) {

                        $ft->assign('ERROR_MONIT', $error);
                    
                        $ft->parse('ROWS',	".error_row");
                    }
                        
                    $ft->parse('ROWS', "error_reporting");
                }
            } elseif (isset($_POST['sub_delete'])) {
                if($permarr['moderator']) {
                    $query = sprintf("
                        DELETE FROM 
                            %1\$s 
                        WHERE 
                            id IN (%2\$s)",
        
                        TABLE_MAIN,
                        implode(',', $_POST['selected_notes'])
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
            } else {
                $default = true;
            }

        } else {
            $default = true;
        }



        if (isset($default) && $default) {
	
            $mainposts_per_page = get_config('editposts_per_page');

            // zliczamy posty
            $query = sprintf("
                SELECT 
                    COUNT(*) AS id 
                FROM 
                    %1\$s 
                ORDER BY 
                    date", 
        
                TABLE_MAIN
            );

            $db->query($query);
            $db->next_record();
        
            $num_items = $db->f("0");

            // inicjowanie funkcji stronnicuj±cej wpisy
            $pagination = pagination('main.php?p=2&amp;start=', $mainposts_per_page, $num_items);
            
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
                $mainposts_per_page
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
                            'AUTHOR'    =>$author, 
                            'PUBLISHED' =>$published == 1 ? $i18n['confirm'][0] : $i18n['confirm'][1], 
                            'PAGINATED' =>!empty($pagination['page_string']) ? true : false, 
                            'STRING'    =>$pagination['page_string']
                        ));
                    
                    // deklaracja zmiennej $idx1::color switcher
                    $idx1 = empty($idx1) ? '' : $idx1;
                    
                    $idx1++;
                    
                    $ft->define("editlist_notes", "editlist_notes.tpl");
                    $ft->define_dynamic("row", "editlist_notes");
                    
                    // naprzemienne kolorowanie wierszy tabeli
                    $ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
                    
                    $ft->parse('ROW', ".row");
                }
                
                $ft->parse('ROWS', "editlist_notes");
            } else {
                
                $ft->assign('CONFIRM', $i18n['edit_note'][4]);
                $ft->parse('ROWS',	".result_note");
            }
        }
}

?>
