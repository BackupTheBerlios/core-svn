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
	
            $mysql_data['db_table_category'], 
            $_GET['id']
        );
	
        $db->query($query);
        $db->next_record();
        
        $cat_id            = $db->f("category_id");
        $cat_name          = $db->f("category_name");
        $cat_description   = $db->f("category_description");
		
		$ft->assign(array(
            'CATEGORY_ID'		=>$cat_id,
            'CATEGORY_NAME'		=>$cat_name,
            'CATEGORY_DESC'		=>br2nl($cat_description),
            'CATNAME_DESC'		=>$cat_description,
            'SUBMIT_HREF_DESC'	=>$i18n['edit_category'][0]
        ));

		$ft->define("form_categoryedit", "form_categoryedit.tpl");
		$ft->parse('ROWS',	".form_categoryedit");
		break;
		
	case "edit":// edycja wybranego wpisu
	
        if($permarr['moderator']) {
	
            $category_description	= nl2br($_POST['category_description']);
            $category_name			= trim($_POST['category_name']);
            
            $monit = array();
            
            // Obs³uga formularza, jesli go zatwierdzono
		    if($category_name == '') {
		        
		        $monit[] = $i18n['add_category'][0];
		    }
		    
		    if(empty($monit)) {
		        
		        $query = sprintf("
                    UPDATE 
                        %1\$s 
                    SET 
                        category_name = '%2\$s', 
                        category_description = '%3\$s' 
                    WHERE 
                        category_id='%4\$d'", 
		
                    $mysql_data['db_table_category'], 
                    $category_name, 
                    $category_description, 
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
		
	case "remark":// edycja wybranego wpisu
	
        if($permarr['moderator']) {
            
            $move = intval($_GET['move']);
	
            $query = sprintf("
                UPDATE 
                    %1\$s 
                SET 
                    category_order = category_order + '%2\$d' 
                WHERE 
                    category_id='%3\$d'", 
		
                $mysql_data['db_table_category'], 
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
    
                $mysql_data['db_table_category']
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
        
                    $mysql_data['db_table_category']
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
                            a.category_id = b.c_id 
                        WHERE 
                            category_id = '%3\$d'
                        GROUP BY 
                            category_id 
                        ORDER BY 
                            category_id 
                        ASC", 
		
                        $mysql_data['db_table_category'], 
                        $mysql_data['db_table'],
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
		
                            $mysql_data['db_table_category'], 
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
                                c_id = '%2\$d' 
                            WHERE 
                                c_id = '%3\$d'", 
		
                            $mysql_data['db_table'], 
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
		
                            $mysql_data['db_table_category'], 
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
        
            $mysql_data['db_table_category']
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
                a.category_id = b.c_id 
            WHERE 
                category_parent_id = '%3\$d'
            GROUP BY 
                category_id 
            ORDER BY 
                category_order 
            ASC", 
		
            $mysql_data['db_table_category'], 
            $mysql_data['db_table'],
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
                'CATEGORY_ID'		=>$category_id,
                'CATEGORY_NAME'		=>$category_name,
                'COUNT'				=>$count
            ));
            
            if($category_order == $max_order) {
                // przydzielamy przycisk do podwy¿eszenia pozycji kategorii
                $ft->assign(array(
                    'DOWN'  =>'',
                    'UP'    =>'<a href="main.php?p=9&amp;action=remark&amp;move=-15&amp;id=' . $category_id . '"><img src="templates/images/up.gif" width="11" height="7" /></a>'
                ));
            } elseif ($category_order == $min_order) {
                // przydzielamy przycisk do obnizenia pozycji kategorii
                $ft->assign(array(
                    'DOWN'  =>'<a href="main.php?p=9&amp;action=remark&amp;move=15&amp;id=' . $category_id . '"><img src="templates/images/down.gif" width="11" height="7" /></a>', 
                    'UP'    =>''
                    
                ));
            } else {
                // przydzielamy dwa przyciski do zmiany polozenia kategorii
                $ft->assign(array(
                    'UP'    =>'<a href="main.php?p=9&amp;action=remark&amp;move=-15&amp;id=' . $category_id . '"><img src="templates/images/up.gif" width="11" height="7" /></a>', 
                    'DOWN'  =>'<a href="main.php?p=9&amp;action=remark&amp;move=15&amp;id=' . $category_id . '"><img src="templates/images/down.gif" width="11" height="7" /></a>'
                ));
            }
								
			if(empty($category_description)) {

				$ft->assign('CATEGORY_DESC', $i18n['edit_category'][4]);
			} else {
				
				$ft->assign('CATEGORY_DESC', $category_description);
			}	

			// deklaracja zmiennej $page_string::page switcher
			$page_string = empty($page_string) ? '' : $page_string;
				
			$ft->assign('STRING', $page_string);					
			
			// deklaracja zmiennej $idx1::color switcher
			$idx1 = empty($idx1) ? '' : $idx1;
			
			$idx1++;
			
			$ft->define("editlist_category", "editlist_category.tpl");
			$ft->define_dynamic("row", "editlist_category");
			
			// naprzemienne kolorowanie wierszy
			if (($idx1%2)==1) {
			    
			    $ft->assign('ID_CLASS', 'mainList');
			    
			    $ft->parse('ROWS',	".row");
			} else {
			    
			    $ft->assign('ID_CLASS', 'mainListAlter');
			    
			    $ft->parse('ROWS', ".row");
			}
			
			get_editcategory_cat($category_id, 2);
		}
		
		$ft->parse('ROWS', "editlist_category");
}

?>