<?php

// deklaracja zmiennej $action::form
$action = empty($_GET['action']) ? '' : $_GET['action'];

switch ($action) {
	
	case "add":
	
		$category_name			= $_POST['category_name'];
		$category_description	= $_POST['category_description'];
		
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
                    INSERT INTO 
                        %1\$s 
                    VALUES 
                        ('', '%2\$s', '%3\$s')",
			
                    $mysql_data['db_table_category'],
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
	
		// przydzielenie zmiennych::array
		$ft->assign(array(
            'SUBMIT_URL'		=>"main.php?p=8&amp;action=add",
            'CATNAME_VALUE'		=>"",
            'CATNAME_DESC'		=>"",
            'SUBMIT_HREF_DESC'	=>$i18n['add_category'][2],
            'HEADER_DESC'		=>$i18n['add_category'][3]
		));
		
		// w przypadku braku akcji wy¶wietlanie formularza
		$ft->define('form_category', "form_category.tpl");
		$ft->parse('ROWS', ".form_category");
}

?>