<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch ($action) {
	
	case "show": // wy¶wietlanie wpisu pobranego do modyfikacji
	
		$query = sprintf("
            SELECT 
                * 
            FROM 
                %1\$s 
            WHERE 
                category_id = '%2\$d'", 
	
            TABLE_CATEGORY, 
            $_GET['id']
        );
	
        $db->query($query);
        $db->next_record();
        
        $cat_id             = $db->f("category_id");
        $cat_name           = $db->f("category_name");
        $cat_description    = $db->f("category_description");
        $category_tpl       = $db->f("category_tpl");
        $category_perpage   = $db->f("category_post_perpage");
		
		$ft->assign(array(
            'CATEGORY_ID'		=>$cat_id,
            'CATEGORY_NAME'		=>$cat_name,
            'CATEGORY_DESC'		=>br2nl($cat_description),
            'CATNAME_DESC'		=>$cat_description, 
            'CATEGORY_PERPAGE'  =>$category_perpage, 
            'SUBMIT_HREF_DESC'	=>$i18n['edit_category'][0]
        ));
        
        $path = '../templates/main/tpl/';
        
        $dir = @dir($path);
        
        // definiowanie dynamicznej czesci szablonu
        $ft->define("form_categoryedit", "form_categoryedit.tpl");
        $ft->define_dynamic("template_row", "form_categoryedit");
        
        // wyswietlanie listy dostepnych szablonow
        while($file = $dir->read()) {
            
            // wyswietlamy szablony nazwane tylko w formie (.*)_rows.tpl
            if(eregi("_rows.tpl", $file)) {
                
                $file = explode('_', $file);
                $ft->assign(array(
                    'TEMPLATE_ASSIGNED' =>$file[0], 
                    'CURRENT_TPL'       =>$category_tpl == $file[0] ? 'selected="selected"' : ''
                ));
                
                $ft->parse('TEMPLATE_ROW', ".template_row");
            }
        }
        
        $dir->close();

		$ft->parse('ROWS',	".form_categoryedit");
		break;
		
	case "edit":// edycja wybranego wpisu
	
        if($permarr['moderator']) {
	
            $category_description	= nl2br($_POST['category_description']);
            $category_name			= trim($_POST['category_name']);
            $category_perpage       = $_POST['category_post_perpage'];
            $template_name          = $_POST['template_name'];
            
            $monit = array();
            
            // Obs³uga formularza, jesli go zatwierdzono
		    if($category_name == '') {
		        $monit[] = $i18n['add_category'][0];
		    }
		    
		    // Sprawdzamy czy liczba postow na stronie jest w odpowiednim przedziale
		    if(!is_int($category_perpage) && ($category_perpage < 3 || $category_perpage > 99)) {
		        $monit[] = $i18n['add_category'][5];
		    }
		    
		    if(empty($monit)) {
		        
		        $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        category_name = '%2\$s', 
                        category_description = '%3\$s', 
                        category_tpl = '%4\$s', 
                        category_post_perpage = '%5\$d' 
                    WHERE 
                        category_id='%6\$d'", 
		
                    TABLE_CATEGORY, 
                    $category_name, 
                    $category_description, 
                    $template_name, 
                    $category_perpage, 
                    $_GET['id']
                );
		
                $db->query($query);
		
                $ft->assign('CONFIRM', $i18n['edit_category'][2]);
                $ft->parse('ROWS',	".result_note");
		    } else {
		        
		        foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                        
                $ft->parse('ROWS', "error_reporting");
		    }
        } else {
            
            $monit[] = $i18n['edit_category'][6];
            
            foreach ($monit as $error) {
                
                $ft->assign('ERROR_MONIT', $error);
                
                $ft->parse('ROWS',	".error_row");
            }
                        
            $ft->parse('ROWS', "error_reporting");
        }
		break;
		
	case "remark": // zmiana pozycji wybranej kategorii
	
        if($permarr['moderator']) {
            
            $move = intval($_GET['move']);
	
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    category_order = category_order + '%2\$d' 
                WHERE 
                    category_id='%3\$d'", 
		
                TABLE_CATEGORY, 
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
                    category_parent_id = '0' 
                ORDER BY 
                    category_order 
                ASC", 
    
                TABLE_CATEGORY
            );
    
            $sql->query($query);
    
            $i = 10;
            $inc = 10;
    
            while($sql->next_record()) {
        
                $cid = $sql->f("category_id");
        
                $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        category_order = '$i' 
                    WHERE 
                        category_id = '$cid'", 
        
                    TABLE_CATEGORY
                );
                    
                $db->query($query);
                    
                $i += 10;
            }
            
            header("Location: main.php?p=9");
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
		
	case "delete":// usuwanie wybranego wpisu
	
        // potwierdzenie usuniecia kategorii
        $confirm = empty($_POST['confirm']) ? '' : $_POST['confirm'];
        switch ($confirm) {
            
            case "Tak":
            
                $post_id = empty($_POST['post_id']) ? '' : $_POST['post_id'];
	
                if($permarr['moderator']) {
            
                    $query = sprintf("
                        SELECT 
                            a.*, count(b.id) AS count 
                        FROM 
                            %1\$s a 
                        LEFT JOIN 
                            %2\$s b 
                        ON 
                            a.category_id = b.category_id 
                        WHERE 
                            a.category_id = '%3\$d'
                        GROUP BY 
                            a.category_id 
                        ORDER BY 
                            a.category_id 
                        ASC", 
		
                        TABLE_CATEGORY, 
                        TABLE_ASSIGN2CAT,
                        $post_id
                    );
	
                    $db->query($query);
                    $db->next_record();
            
                    $category_id    = $db->f("category_id");
                    $cat_parent_id  = $db->f("category_parent_id");
                    $count          = $db->f("count");
            
                    if($cat_parent_id > 0) {
                
                        // zmiana parent_id kategorii dziedziczacej na ta poziom wyzsza
                        // ------------------------------------------------------------
            
                        $query = sprintf("
                            UPDATE 
                                %1\$s 
                            SET 
                                category_parent_id = '%2\$s' 
                            WHERE 
                                category_parent_id = '%3\$d'", 
		
                            TABLE_CATEGORY, 
                            $cat_parent_id, 
                            $category_id
                        );
	
                        $db->query($query);
                        $db->next_record();
            
                        // transfer wpisow z usuwanej kategorii do poziom wyzszej
                        // ------------------------------------------------------
            
                        $query = sprintf("
                            UPDATE 
                                %1\$s 
                            SET 
                                category_id = '%2\$d' 
                            WHERE 
                                category_id = '%3\$d'", 
		
                            TABLE_ASSIGN2CAT, 
                            $cat_parent_id,
                            $post_id
                        );
	
                        $db->query($query);
                        $db->next_record();
            
                        // usuwamy kategorie
                        // -----------------------------------------------------
	
                        $query = sprintf("
                            DELETE FROM 
                                %1\$s 
                            WHERE 
                                category_id = '%2\$d'", 
		
                            TABLE_CATEGORY, 
                            $post_id
                        );
		
                        $db->query($query);
		
                        $ft->assign('CONFIRM', $i18n['edit_category'][3]);
                        $ft->parse('ROWS', ".result_note");
                    } else {
                
                        $monit[] = $i18n['edit_category'][7];

                        foreach ($monit as $error) {
    
                            $ft->assign('ERROR_MONIT', $error);
                    
                            $ft->parse('ROWS',	".error_row");
                        }
                        
                        $ft->parse('ROWS', "error_reporting");
                    }
                } else {
            
                    $monit[] = $i18n['edit_category'][5];

                    foreach ($monit as $error) {
    
                        $ft->assign('ERROR_MONIT', $error);
                    
                        $ft->parse('ROWS',	".error_row");
                    }
                        
                    $ft->parse('ROWS', "error_reporting");
                }
            break;
                                    
        case "Nie":
        
            header("Location: main.php?p=9");
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
            SELECT 
                MIN(category_order) as min_order, 
                MAX(category_order) as max_order 
            FROM 
                %1\$s 
            WHERE 
                category_parent_id = '0'",
        
            TABLE_CATEGORY
        );
            
        $db->query($query);
        $db->next_record();
			
        // Przypisanie zmiennej $id
        $max_order = $db->f("max_order");
        $min_order = $db->f("min_order");
	
		$query = sprintf("
            SELECT 
                a.*, COUNT(b.id) AS count 
            FROM 
                %1\$s a 
            LEFT JOIN 
                %2\$s b 
            ON 
                a.category_id = b.category_id 
            WHERE 
                category_parent_id = '%3\$d'
            GROUP BY 
                category_id 
            ORDER BY 
                category_order 
            ASC", 
		
            TABLE_CATEGORY, 
            TABLE_ASSIGN2CAT,
            0
        );
		
		$db->query($query);
	
		// Pêtla wyswietlaj¹ca wszystkie wpisy + stronnicowanie ich
		while($db->next_record()) {
		
			$category_id			= $db->f("category_id");
			$category_order			= $db->f("category_order");
			$category_name			= $db->f("category_name");
			$category_description	= $db->f("category_description");
			$count					= $db->f("count");
			
			// obcinamy opis kategorii, jesli dluzszy niz 70 znakow
			$category_description = strlen($category_description) > 70 ? substr_replace($category_description, '...',70) : $category_description;
			
			$ft->assign(array(
                'CATEGORY_ID'   =>$category_id,
                'CATEGORY_NAME' =>$category_name,
                'COUNT'         =>$count, 
                'CATEGORY_DESC' =>empty($category_description) ? $i18n['edit_category'][4] : $category_description, 
                'STRING'        =>$page_string = empty($page_string) ? '' : $page_string
            ));
            
            if($category_order == $max_order) {

                $ft->assign(array(
                    'REORDER_DOWN'  =>false, 
                    'REORDER_UP'    =>true
                ));
            } elseif ($category_order == $min_order) {

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
				
			$ft->assign('STRING', $page_string);					
			
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
			
			$ft->define("editlist_category", "editlist_category.tpl");
			$ft->define_dynamic("row", "editlist_category");
			
			// naprzemienne kolorowanie wierszy tabeli
			$ft->assign('ID_CLASS', $idx1%2 ? 'mainList' : 'mainListAlter');
			
			$ft->parse('ROWS', ".row");
			
			get_editcategory_cat($category_id, 2);
		}
		
		$ft->parse('ROWS', "editlist_category");
}

?>