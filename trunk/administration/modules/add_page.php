<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

switch($action) {
	
	case "add":
	
        if($permarr['writer']) {
	
            $text = nl2br($_POST['text']);
		
            $title 		= trim($_POST['title']);
            $published 	= $_POST['published'];
            $page_id	= $_POST['category_id'];
            
            // Sprawdzanie czy tytul strony jest wype³niony
            if(!empty($title)) {
                
                $query = sprintf("
                    SELECT 
                        max(page_order) as max_order 
                    FROM 
                        %1\$s",
        
                    $mysql_data['db_table_pages']
                );
            
                $db->query($query);
                $db->next_record();
			
                // Przypisanie zmiennej $id
                $max_order = $db->f("max_order");
		      
                $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$d', '%3\$d', '%4\$s', '%5\$s', '', '%6\$s')", 
		
                    $mysql_data['db_table_pages'], 
                    $page_id, 
                    $max_order + 10, 
                    $title, 
                    $text, 
                    $published
                );
            
                $db->query($query);
		
                $query = sprintf("
                    SELECT MAX(id) 
                        as maxid 
                    FROM 
                        %1\$s", 

                    $mysql_data['db_table_pages']
                );
            
                $db->query($query);
                $db->next_record();
            
                // Przypisanie zmiennej $id
                $id = $db->f("0");
            
                if(!empty($_FILES['file']['name'])) {
                
                    $up = new upload;
                    $upload_dir = "../photos";
                
                    // upload file.
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
			    
                            $mysql_data['db_table_pages'],
                            $file,
                            $id
                        );
				
				        $db->query($query);
				
				        $ft->assign('CONFIRM', $i18n['add_page'][0]);
				        $ft->parse('ROWS',	".result_note");
                    }
                }
            
                $ft->assign('CONFIRM', $i18n['add_page'][1]);
                $ft->parse('ROWS',	".result_note");
            
            } else {
		    
                $monit    = array();
                $monit[]  = $i18n['add_page'][2];
		    
                $ft->define("error_reporting", "error_reporting.tpl");
                $ft->define_dynamic("error_row", "error_reporting");

                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                        
                $ft->parse('ROWS', "error_reporting");
            }
        } else {
            
            $monit[] = $i18n['add_page'][3];
            
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
                id, parent_id, title 
            FROM 
                %1\$s 
            WHERE 
                published = 'Y' 
            AND 
                parent_id = '%2\$d' 
            ORDER BY 
                id 
            ASC", 
	
            $mysql_data['db_table_pages'],
            0
        );
	
        $db->query($query);
	
        $ft->define("form_pageadd", "form_pageadd.tpl");
        $ft->define_dynamic("page_row", "form_pageadd");
        
        while($db->next_record()) {
		
            $page_id      = $db->f("id");
            $parent_id    = $db->f("parent_id");
            $title        = $db->f("title");
            
            $ft->assign(array(
                'C_ID'		=>$page_id,
                'C_NAME'	=>$title
            ));
        
            $ft->parse('ROWS', ".page_row");
        
            get_addpage_cat($page_id, 2);
        }
	
        $ft->parse('ROWS', "form_pageadd");
        break;
}

?>