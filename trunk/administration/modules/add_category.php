<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "add":
	
		$category_name        = $_POST['category_name'];
		$category_description = $_POST['category_description'];
		$category_parent_id   = $_POST['category_id'];
		
		$monit = array();
		
		// definicja szablonow parsujacych wyniki bledow.
		$ft->define("error_reporting", "error_reporting.tpl");
		$ft->define_dynamic("error_row", "error_reporting");
	
		if($permarr['moderator']) {
		    
		    // Obs³uga formularza, jesli go zatwierdzono
		    if(!eregi("^([^0-9]+){2,}$", $category_name)) {
		        
		        $monit[] = $i18n['add_category'][0];
		    }
		    
		    if(empty($monit)) {
		        
		        $query = sprintf("
                    SELECT 
                        max(category_order) as max_order 
                    FROM 
                        %1\$s",
        
                    $mysql_data['db_table_category']
                );
            
                $db->query($query);
                $db->next_record();
			
                // Przypisanie zmiennej $id
                $max_order = $db->f("max_order");
		        
		        $query = sprintf("
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$d', '%3\$d', '%4\$s', '%5\$s')",
			
                    $mysql_data['db_table_category'], 
                    $category_parent_id, 
                    $max_order + 10, 
                    $category_name,
                    $category_description
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
	
            $mysql_data['db_table_category'],
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
        
            $ft->parse('ROWS', ".category_row");
        
            get_addcategory_cat($category_id, 2);
        }
	
		// przydzielenie zmiennych::array
		$ft->assign(array(
            'SUBMIT_HREF_DESC'	=>$i18n['add_category'][2]
		));
		
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->parse('ROWS', "form_categoryadd");
		break;
}

?>