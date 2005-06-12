<?php

// deklaracja zmiennej $action::form
$post       = empty($_POST['post']) ? '' : $_POST['post'];
$preview    = empty($_POST['preview']) ? '' : $_POST['preview'];

// definicja szablonow parsujacych wyniki bledow.
$ft->define("error_reporting", "error_reporting.tpl");
$ft->define_dynamic("error_row", "error_reporting");

if(!empty($post)) {
	
        if($permarr['writer']) {
	
            $text           = $_POST['text'];
		
            $title          = trim($_POST['title']);
            $published      = $_POST['published'];
            $page_id        = $_POST['category_id'];
            $template_name  = $_POST['template_name'];
            
            $text = parse_markers($text, 1);
            
            // Sprawdzanie czy tytul strony jest wype³niony
            if(!empty($title)) {
                
                $query = sprintf("
                    SELECT 
                        max(page_order) as max_order 
                    FROM 
                        %1\$s",
        
                    TABLE_PAGES
                );
            
                $db->query($query);
                $db->next_record();
			
                // Przypisanie zmiennej $id
                $max_order = $db->f("max_order");
		      
                $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$d', '%3\$d', '%4\$s', '%5\$s', '', '%6\$s', '%7\$s')", 
		
                    TABLE_PAGES, 
                    $page_id, 
                    $max_order + 10, 
                    $title, 
                    $text, 
                    $published, 
                    $template_name
                );
            
                $db->query($query);
		
                $query = sprintf("
                    SELECT MAX(id) 
                        as maxid 
                    FROM 
                        %1\$s", 

                    TABLE_PAGES
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
			    
                            TABLE_PAGES,
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
} else {
    
    if(!empty($preview)) {
        
        $p_text   = str_replace('\\', '', $_POST['text']);
        $p_title  = trim(str_replace('\\', '', $_POST['title']));
        
        $ft->assign(array(
            'P_TITLE'       =>$p_title, 
            'P_TEXT'        =>br2nl($p_text), 
            'PG_TEXT'       =>nl2br(parse_markers($p_text, 1)), 
            'PAGE_PREVIEW'  =>true
        ));
        
    } else {
        
        $ft->assign(array(
            'P_TITLE'       =>!empty($p_title) ? $p_title : '', 
            'P_TEXT'        =>!empty($p_text) ? $p_text : '', 
            'PAGE_PREVIEW'  =>false
        ));
    }
		
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
	
        TABLE_PAGES,
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
            'P_ID'		=>$page_id,
            'P_NAME'	=>$title
        ));
        
        $ft->parse('PAGE_ROW', ".page_row");
        
        get_addpage_cat($page_id, 2);
    }
        
    $path = '../templates/main/tpl/';
        
    $dir = @dir($path);
        
    // definiowanie dynamicznej czesci szablonu
    $ft->define_dynamic("template_row", "form_pageadd");
        
    // wyswietlanie listy dostepnych szablonow
    while($file = $dir->read()) {
        
        // pomijamy szablony stanowiace skladowa calej strony
        if(eregi("_page.tpl", $file)) {
            $file = explode('_', $file);
            
            $ft->assign(array(
                'TEMPLATE_ASSIGNED' =>$file[0]
            ));
                
            $ft->parse('TEMPLATE_ROW', ".template_row");
        }
    }
    $dir->close();
    
    $ft->parse('ROWS', "form_pageadd");
}

?>