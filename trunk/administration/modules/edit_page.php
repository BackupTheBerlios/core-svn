<?php

// deklaracja zmiennej $action::form
$action     = empty($_GET['action']) ? '' : $_GET['action'];
$preview    = empty($_POST['preview']) ? '' : $_POST['preview'];
$post       = empty($_POST['post']) ? '' : $_POST['post'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show":
	
        // podglad
        if(!empty($preview)) {
            $ft->assign(array(
                'PG_TEXT'       =>nl2br(parse_markers(stripslashes($_POST['text']), 1)), 
                'PAGE_PREVIEW'  =>true
            ));
        } else {
        
            $ft->assign(array( 
                'PAGE_PREVIEW'  =>false
            ));
        }
        
        // submit formularza
        if(!empty($post)) {
            if($permarr['writer']) {
	
                $text           = $_POST['text'];
                $title          = $_POST['title'];
                $published      = $_POST['published'];
                $template_name  = $_POST['template_name'];
            
                $text = parse_markers($text, 1);
		
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        title           = '%2\$s', 
                        text            = '%3\$s', 
                        published       = '%4\$s', 
                        assigned_tpl    = '%5\$s' 
                    WHERE 
                        id = '%6\$d'", 
		
                    TABLE_PAGES, 
                    $title, 
                    $text, 
                    $published, 
                    $template_name, 
                    $_GET['id']
                );
		
                $db->query($query);
            
                // usuwamy istniej±ce zdjêcie
                if(isset($_POST['delete_image']) && (($_POST['delete_image']) == 1)) {
                
                    $query = sprintf("
                        UPDATE 
                            %1\$s 
                        SET 
                            image = '' 
                        WHERE 
                            id = '%2\$d'", 
                
                        TABLE_PAGES, 
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
			    
                            TABLE_PAGES,
                            $file,
                            $_GET['id']
                        );
                
				        $db->query($query);
                    }
                }
		
                $ft->assign('CONFIRM', $i18n['edit_page'][0]);
                $ft->parse('ROWS',	".result_note");
            } else {
            
                $monit[] = $i18n['edit_page'][3];
            
                foreach ($monit as $error) {
			    
                    $ft->assign('ERROR_MONIT', $error);
                    $ft->parse('ROWS', ".error_row");
                }
                $ft->parse('ROWS', "error_reporting");
            }
        // wyswietlanie noty  
        } else {
	
            $query = sprintf("
                SELECT * FROM 
                    %1\$s 
                WHERE 
                    id = '%2\$d'", 
		
                TABLE_PAGES, 
                $_GET['id']
            );
		
            $db->query($query);
            $db->next_record();
		
            $title          = $db->f("title");
            $text           = $db->f("text");
            $published      = $db->f("published");
            $image          = $db->f("image");
            $assigned_tpl   = $db->f("assigned_tpl");
		
            $ft->assign(array(
                'ID'	=>$_GET['id'],
                'TITLE'	=>!empty($_POST['title']) ? stripslashes($_POST['title']) : $title,
                'TEXT'	=>!empty($_POST['text']) ? stripslashes(br2nl($_POST['text'])) : br2nl($text)
            ));
        
            $path = '../templates/main/tpl/';
        
            $dir = @dir($path);
        
            // definiowanie dynamicznej czesci szablonu
            $ft->define('form_pageedit', "form_pageedit.tpl");
            $ft->define_dynamic("template_row", "form_pageedit");
        
            // wyswietlanie listy dostepnych szablonow
            while($file = $dir->read()) {
            
                // pomijamy szablony stanowiace skladowa calej strony
                if(eregi("_page.tpl", $file)) {
                
                    $file = explode('_', $file);
                    $ft->assign(array(
                        'TEMPLATE_ASSIGNED' =>$file[0], 
                        'CURRENT_TPL'       =>$assigned_tpl == $file[0] ? 'selected="selected"' : ''
                    ));
                
                    $ft->parse('TEMPLATE_ROW', ".template_row");
                }
            }
        
            $dir->close();
							
            if($published == "Y") {
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
            
            $ft->parse('ROWS', ".form_pageedit");
            
        }
        break;
        
	case "delete": // usuwanie wybranego wpisu
	
        // potwierdzenie usuniecia strony
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
		
                        TABLE_PAGES, 
                        $post_id
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
                        
        case $i18n['confirm'][1]:
        
            header("Location: main.php?p=4");
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
    
	case "remark": // kolejnosc
	
        if($permarr['moderator']) {
            
            $move = intval($_GET['move']);
	
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    page_order = page_order + '%2\$d' 
                WHERE 
                    id='%3\$d'", 
		
                TABLE_PAGES, 
                $move, 
                $_GET['id']
            );
		
            $db->query($query);
            
            // instancja potrzebna
            $sql = new DB_SQL;
            
            $query = sprintf("
                SELECT * FROM 
                    %1\$s 
                WHERE 
                    parent_id = '0' 
                ORDER BY 
                    page_order 
                ASC", 
    
                TABLE_PAGES
            );
    
            $sql->query($query);
    
            $i = 10;
    
            while($sql->next_record()) {
        
                $pid = $sql->f("id");
        
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        page_order = '$i' 
                    WHERE 
                        id = '$pid'", 
        
                    TABLE_PAGES
                );
                    
                $db->query($query);
                    
                $i += 10;
            }
            
            header("Location: main.php?p=4");
            exit;
		
        } else {
            
            $monit[] = $i18n['edit_category'][6];
            
            foreach ($monit as $error) {
                
                $ft->assign('ERROR_MONIT', $error);
                
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
		break;

	default:
	
        $query = sprintf("
            SELECT 
                MIN(page_order) as min_order, 
                MAX(page_order) as max_order 
            FROM 
                %1\$s 
            WHERE 
                parent_id = '0'",
        
            TABLE_PAGES
        );
            
        $db->query($query);
        $db->next_record();
			
        // Przypisanie zmiennej $id
        $max_order = $db->f("max_order");
        $min_order = $db->f("min_order");
	
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                config_name = '%2\$s'", 
		
            TABLE_CONFIG, 
            "editposts_per_page"
        );
		
		$db->query($query);
		$db->next_record();
			
		$editposts_per_page = $db->f("config_value");
		
		$query = sprintf("
            SELECT * FROM 
                %1\$s 
            WHERE 
                parent_id = '%2\$d' 
            ORDER BY 
                page_order 
            ASC", 
		
            TABLE_PAGES, 
            0
        );
		
		$db->query($query);
		
		// Sprawdzamy, czy w bazie danych s± ju¿ jakie¶ wpisy
		if($db->num_rows() > 0) {
		
			// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
			while($db->next_record()) {
		
				$page_id 		= $db->f("id");
				$title 			= $db->f("title");
				$page_order     = $db->f("page_order");
				$published		= $db->f("published");
			
				$ft->assign(array(
				    'ID'        =>$page_id,
                    'TITLE'     =>$title, 
                    'PUBLISHED' =>$published == 'Y' ? $i18n['confirm'][0] : $i18n['confirm'][1]
                ));

				if($page_order == $max_order) {

                    $ft->assign(array(
                        'REORDER_DOWN'  =>false, 
                        'REORDER_UP'    =>true
                    ));
                } elseif ($page_order == $min_order) {

                    $ft->assign(array(
                        'REORDER_DOWN'  =>true, 
                        'REORDER_UP'    =>false
                    
                    ));
                } else {

                    $ft->assign(array(
                        'REORDER_DOWN'  =>true, 
                        'REORDER_UP'    =>true
                    ));
                }			
			
				// deklaracja zmiennej $idx1::color switcher
				$idx1 = empty($idx1) ? '' : $idx1;
				
				$idx1++;
				
				$ft->define("editlist_pages", "editlist_pages.tpl");
				$ft->define_dynamic("row", "editlist_pages");
			
				// naprzemienne kolorowanie wierszy tabeli
				$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
				
				$ft->parse('ROWS', ".row");
				
				get_editpage_cat($page_id, 2);
			}
		
			$ft->parse('ROWS',	"editlist_pages");
		} else {
		
			$ft->assign('CONFIRM', "W bazie danych nie ma ¿adnych wpisów");
			$ft->parse('ROWS',	".result_note");
		}
}
?>