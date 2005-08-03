<?php
// $Id$

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "add":
	
		$category_name        = trim($_POST['category_name']);
		$category_description = $_POST['category_description'];
		$category_parent_id   = $_POST['category_id'];
		$category_perpage     = $_POST['category_post_perpage'];
		$template_name        = $_POST['template_name'];
		
		$monit = array();
		
		// definicja szablonow parsujacych wyniki bledow.
		$ft->define("error_reporting", "error_reporting.tpl");
		$ft->define_dynamic("error_row", "error_reporting");
	
		if($permarr['moderator']) {
		    
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
                    SELECT 
                        max(category_order) as max_order 
                    FROM 
                        %1\$s",
        
                    TABLE_CATEGORY
                );
            
                $db->query($query);
                $db->next_record();
			
                // Przypisanie zmiennej $id
                $max_order = $db->f("max_order");
		        
		        $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$d', '%3\$d', '%4\$s', '%5\$s', '%6\$s', '%7\$d')",
			
                    TABLE_CATEGORY, 
                    $category_parent_id, 
                    $max_order + 10, 
                    $category_name,
                    $category_description, 
                    $template_name, 
                    $category_perpage
                );
                
                $db->query($query);
                
                $ft->assign('CONFIRM', $i18n['add_category'][1]);
                
                $ft->parse('ROWS', ".result_note");
		    } else {

                foreach ($monit as $error) {
    
                    $ft->assign('ERROR_MONIT', $error);
                    
                    $ft->parse('ROWS',	".error_row");
                }
                        
                $ft->parse('ROWS', "error_reporting");
		    }
		} else {
		    
		    $monit[] = $i18n['add_category'][4];

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
                category_id, 
                category_parent_id,
                category_name 
            FROM 
                %1\$s 
            WHERE 
                category_parent_id = '%2\$d' 
            ORDER BY 
                category_id 
            ASC", 
	
            TABLE_CATEGORY,
            0
        );
	
        $db->query($query);
	
        $ft->define("form_categoryadd", "form_categoryadd.tpl");
        $ft->define_dynamic("category_row", "form_categoryadd");
        
        while($db->next_record()) {
		
            $category_id        = $db->f("category_id");
            $category_parent_id = $db->f("category_parent_id");
            $category_name      = $db->f("category_name");
            
            $ft->assign(array(
                'C_ID'		=>$category_id,
                'C_NAME'	=>$category_name
            ));
        
            $ft->parse('CATEGORY_ROW', ".category_row");
        
            get_addcategory_cat($category_id, 2);
        }
		
		$path = '../templates/' . $lang . '/main/tpl/';
        
        $dir = @dir($path);
        
        // definiowanie dynamicznej czesci szablonu
        $ft->define_dynamic("template_row", "form_categoryadd");
        
        // wyswietlanie listy dostepnych szablonow
        while($file = $dir->read()) {
            
            // wyswietlamy szablony nazwane tylko w formie (.*)_rows.tpl
            if(eregi("_rows.tpl", $file)) {
                
                $file = explode('_', $file);
                $ft->assign(array(
                    'TEMPLATE_ASSIGNED'		=>$file[0]
                ));
                
                $ft->parse('TEMPLATE_ROW', ".template_row");
            }
        }
        
        $dir->close();
		
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->parse('ROWS', "form_categoryadd");
		break;
}

?>
